<!doctype html>
<html>
<head>
    <title>
        <?php
        include '/gettimes.php';
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $stop = trim($_POST['busStopCode']);
            echo (is_numeric($stop) && strlen($stop) == 5 && getStop($stop) != NULL)
                ? $stop . "站到达时间" 
                : "无效的巴士站代码";
        } else {
            http_response_code(405);
            echo "无效的请求";
        }
        ?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
    <div>
        <h1><?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $stop = trim($_POST['busStopCode']);
            if (is_numeric($stop) && strlen($stop) == 5 && getStop($stop) != NULL) {
                echo  $stop . "站点";
            } else {
                echo "无效的巴士站代码。请输入有效的代码。";
                header("Location: /invalid.html");
                exit();
            }
        } else {
            echo "无效的请求方法。";
            exit();
        }
        ?></h1>
        <h2 id="stopName" stop=<?php echo $stop; ?>><?php 
                $stopName = getStop($stop);
                if ($stopName != NULL) {
                    echo $stopName."<br>";
                }
                
            ?></h2>
        <button id="bookmark" onclick="saveStop(<?php echo $stop?>)">添加收藏</button>
        <h2 id="notfound"><?php 
            $services = timings($stop);
            if (empty($services)) echo "未找到巴士服务。";
        ?></h2>
        <div id="slider-container" <?php echo (empty($services)) ? "style='display:none'" : "" ?>>
            
            <label for="infoSlider">扩展信息：</label>
            <label id="switch">
                <input type="checkbox" id="infoSlider" onchange="toggleExtendedInfo()">
                <span id="slider"></span>
            </label>
        </div>
        <a href="/">返回主页</a>
        <div class="table-container" <?php echo (empty($services)) ? "style='display:none'" : "" ?>>
            <table>
                <thead>
                    <tr>
                        <th></th>
                        <th>下一班</th>
                        <th>随后的班次</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    if (!empty($services)){
                        foreach ($services as $serviceNumber => $service) {
                            echo "<tr>";
                            echo "<td>" . $serviceNumber;
                            echo "<div class='extended'><br>" . $service['Operator'] . "</div></td>";
                            foreach (['NextBus', 'NextBus2'] as $nextBus) {
                                if (empty($service[$nextBus])){
                                    echo "<td></td>";
                                }else{
                                    $accessibility = $service[$nextBus]['Feature'] == "WAB" ? "inline" : "none";
                                    echo "<td>";
                                    echo "<p class='arrival-time " . $service[$nextBus]['Load']."' mins='" . $service[$nextBus]['TimeRemaining'][0]."' secs='" . $service[$nextBus]['TimeRemaining'][1]."'>". (($service[$nextBus]['TimeRemaining'][0] > 0) ? $service[$nextBus]['TimeRemaining'][0]." 分钟" : "到达")." </p>";
                                    echo "<div class='extended'>";
                                    echo "<br>";
                                    echo "<span class='".$service[$nextBus]['Load']. "'>".(($service[$nextBus]['Load'] == "SEA") ? "有座位" : (($service[$nextBus]['Load'] == "SDA") ? "有站立空间" : "有限站立空间")) . "</span><br>";
                                    echo (($service[$nextBus]['Type'] == "SD") ? "单层" : (($service[$nextBus]['Type'] == "DD") ? "双层" : "弯曲")) ."<br>";
                                    if ($service[$nextBus]['Feature'] == "WAB") echo "♿ 无障碍";
                                    echo "</div></td>";
                                }
                                
                            }
                            echo "</tr>";
                        }
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="/zh/update_arrivals.js"></script>
</body>
</html>
