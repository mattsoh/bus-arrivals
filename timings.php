<!doctype html>
<html>
<head>
    <title>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $busStopCode = trim($_POST['busStopCode']);
            echo (!empty($busStopCode) && is_numeric($busStopCode) && strlen($busStopCode) == 5)
                ? "Stop " . htmlspecialchars($busStopCode) . " Arrival Times"
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
            if (!empty($busStopCode) && is_numeric($busStopCode) && strlen($busStopCode) == 5) {
                echo "Stop " . htmlspecialchars($busStopCode);
            } else {
                echo "Invalid bus stop code. Please enter a valid code.";
                exit();
            }
        } else {
            echo "Invalid request method.";
            exit();
        }
        ?></h1>
        <div class="sliderContainer">
            <label for="infoSlider">Show Extended Information: </label>
            <label class="switch">
                <input type="checkbox" id="infoSlider" onchange="toggleExtendedInfo()">
                <span class="slider"></span>
            </label>
        </div>
        <div class="table-container">
            <?php
                include 'gettimes.php';
                $services = timings($busStopCode);
                if (empty($services)) {
                    echo "<h2>No bus services found at this bus stop. Check the bus stop code and try again.</h2>";
                } else {
                    echo "<table id='busTable'>";
                    echo "<thead><tr>
                            <th></th>
                            <th>Next Bus</th>
                            <th>Next Bus 2</th>
                        </tr></thead><tbody>";
                    foreach ($services as $serviceNumber => $service) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($serviceNumber) ;
                        echo "<div class='extended'><br>";
                        echo htmlspecialchars($service['Operator']);
                        echo "</div></td>";
                        foreach (['NextBus', 'NextBus2'] as $nextBus) {
                            // $timeRemaining = isset($service[$nextBus]['TimeRemaining'])
                            //     ? htmlspecialchars($service[$nextBus]['TimeRemaining'])
                            //     : 'No data'; 
                            if (empty($service[$nextBus])){
                                echo "<td></td>";
                            }else{
                                $accessibility = htmlspecialchars($service[$nextBus]['Feature']) == "WAB" ? "inline" : "none";
                                echo "<td>";
                                echo "<span class='arrival-time'>" . ($service[$nextBus]['TimeRemaining'][0] > 0) ? $service[$nextBus]['TimeRemaining'][0]." mins".$service[$nextBus]['TimeRemaining'][1] : "Arriving"." </span>";
                                
                                echo "<div class='extended'>";
                                echo ($service[$nextBus]['Feature'] == "WAB") ? "Wheelchair Accessable<br>" : '';
                                echo "Load: " . $service[$nextBus]['Load'] . "<br>";
                                echo "Type: " . $service[$nextBus]['Type'];
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
