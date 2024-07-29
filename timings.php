<!doctype html>
<html>
<head>
    <title>
        <?php
        include 'gettimes.php';
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $busStopCode = trim($_POST['busStopCode']);
            echo (!empty($busStopCode) && is_numeric($busStopCode) && strlen($busStopCode) == 5 && getStop($busStopCode) != NULL)
                ? "Stop " . $busStopCode . " Arrival Times"
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
            $busStopCode = trim($_POST['busStopCode']);
            if (!empty($busStopCode) && is_numeric($busStopCode) && strlen($busStopCode) == 5 && getStop($busStopCode) != NULL) {
                echo "Stop " . $busStopCode;
            } else {
                echo "Invalid bus stop code. Please enter a valid code.";
                exit();
            }
        } else {
            echo "Invalid request method.";
            exit();
        }
        ?></h1>
        <h2>
            <?php 
                if (getStop($busStopCode) != NULL) {
                    echo getStop($busStopCode)."<br>";
                }
                if (empty($services)) echo "No bus services found. ";
            ?>
        </h2>
        <div class="sliderContainer">
            <label for="infoSlider">Show Extended Information: </label>
            <label class="switch">
                <input type="checkbox" id="infoSlider" onchange="toggleExtendedInfo()">
                <span class="slider"></span>
            </label>
        </div>
        <div class="table-container">
            <?php
                $services = timings($busStopCode);
                if (!empty($services)){
                    echo "<table id='busTable'>";
                    echo "<thead><tr>
                            <th></th>
                            <th>Next Bus</th>
                            <th>Next Bus 2</th>
                        </tr></thead><tbody>";
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
                                echo "<span class='arrival-time " . $service[$nextBus]['Load']."' mins='" . $service[$nextBus]['TimeRemaining'][0]."' secs='" . $service[$nextBus]['TimeRemaining'][1]."'>". (($service[$nextBus]['TimeRemaining'][0] > 0) ? $service[$nextBus]['TimeRemaining'][0]." mins".$service[$nextBus]['TimeRemaining'][1] : "Arriving")." </span>";
                                echo "<div class='extended'>";
                                echo "<span class='".$service[$nextBus]['Load']. "'>".(($service[$nextBus]['Load'] == "SEA") ? "Seats Available" : (($service[$nextBus]['Load'] == "SDA") ? "Standing Available" : "Limited Standing")) . "</span><br>";
                                echo (($service[$nextBus]['Type'] == "SD") ? "Single Deck" : (($service[$nextBus]['Type'] == "DD") ? "Double Deck" : "Bendy")) ."<br>";
                                if ($service[$nextBus]['Feature'] == "WAB") echo "♿ Accessable";
                                echo "</div></td>";
                            }
                            
                        }
                        echo "</tr>";
                    }
                    echo "</tbody></table>";
                }
            ?>
        </div>
    </div>
    <script src="update.js"></script>
</body>
</html>
