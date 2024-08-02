<!doctype html>
<html>
<head>
    <title>
        <?php
        include 'gettimes.php';
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $stop = trim($_POST['busStopCode']);
            echo (is_numeric($stop) && strlen($stop) == 5 && getStop($stop) != NULL)
                ? "Stop " . $stop . " Arrival Times"
                : "Invalid Bus Stop Code";
        } else {
            echo "Invalid request";
        }
        ?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles2.css">
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
        <h2><?php 
            $services = timings($stop);
            if (empty($services)) echo "No bus services found.";
        ?></h2>
        <div id="sliderContainer" <?php echo (empty($services)) ? "style='display:hidden'" : "" ?>>
            
            <label for="infoSlider">Show Extended Information: </label>
            <label id="switch">
                <input type="checkbox" id="infoSlider" onchange="toggleExtendedInfo()">
                <span id="slider"></span>
            </label>
        </div>
        <div class="table-container">
            <table id='busTable'>
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
                                    echo "<span class='wobble arrival-time " . $service[$nextBus]['Load']."' mins='" . $service[$nextBus]['TimeRemaining'][0]."' secs='" . $service[$nextBus]['TimeRemaining'][1]."'>". (($service[$nextBus]['TimeRemaining'][0] > 0) ? $service[$nextBus]['TimeRemaining'][0]." mins".$service[$nextBus]['TimeRemaining'][1] : "Arriving")." </span>";
                                    echo "<div class='extended'>";
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
    <script src="update.js"></script>
    <script src="bookmark.js"></script>
</body>
</html>
