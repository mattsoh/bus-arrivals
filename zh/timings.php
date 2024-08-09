<!doctype html>
<html>
<head>
    <title>
        <?php
        include '/gettimes.php';
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $stop = trim($_POST['busStopCode']);
            echo (is_numeric($stop) && strlen($stop) == 5 && getStop($stop) != NULL)
                ? "Stop " . $stop . " Arrival Times"
                : "Invalid Bus Stop Code";
        } else {
            http_response_code(405);
            echo "Invalid request";
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
                echo "Stop " . $stop;
            } else {
                echo "Invalid bus stop code. Please enter a valid code.";
                header("Location: /invalid.html");
                exit();
            }
        } else {
            echo "Invalid request method.";
            exit();
        }
        ?></h1>
        <h2 id="stopName" stop=<?php echo $stop; ?>><?php 
                $stopName = getStop($stop);
                if ($stopName != NULL) {
                    echo $stopName."<br>";
                }
                
            ?></h2>
        <button id="bookmark" onclick="saveStop(<?php echo $stop?>)">Add bookmark</button>
        <h2 id="notfound"><?php 
            $services = timings($stop);
            if (empty($services)) echo "No bus services found.";
        ?></h2>
        <div id="slider-container" <?php echo (empty($services)) ? "style='display:none'" : "" ?>>
            
            <label for="infoSlider">Extended Information: </label>
            <label id="switch">
                <input type="checkbox" id="infoSlider" onchange="toggleExtendedInfo()">
                <span id="slider"></span>
            </label>
        </div>
        <a href="/">Back to home page</a>
        <div class="table-container" <?php echo (empty($services)) ? "style='display:none'" : "" ?>>
            <table>
                <thead>
                    <tr>
                        <th></th>
                        <th>Next</th>
                        <th>Subsequent</th>
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
                                    echo "<p class='arrival-time " . $service[$nextBus]['Load']."' mins='" . $service[$nextBus]['TimeRemaining'][0]."' secs='" . $service[$nextBus]['TimeRemaining'][1]."'>". (($service[$nextBus]['TimeRemaining'][0] > 0) ? $service[$nextBus]['TimeRemaining'][0]." mins" : "Arriving")." </p>";
                                    echo "<div class='extended'>";
                                    echo "<br>";
                                    echo "<span class='".$service[$nextBus]['Load']. "'>".(($service[$nextBus]['Load'] == "SEA") ? "Seats Available" : (($service[$nextBus]['Load'] == "SDA") ? "Standing Available" : "Limited Standing")) . "</span><br>";
                                    echo (($service[$nextBus]['Type'] == "SD") ? "Single Deck" : (($service[$nextBus]['Type'] == "DD") ? "Double Deck" : "Bendy")) ."<br>";
                                    if ($service[$nextBus]['Feature'] == "WAB") echo "♿ Accessable";
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
    <script src="update_arrivals.js"></script>
</body>
</html>
