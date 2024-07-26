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
    <style>
        .wobble {
            animation: wobble 1s infinite;
        }
        @keyframes wobble {
            0% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            50% { transform: translateX(10px); }
            75% { transform: translateX(-10px); }
            100% { transform: translateX(0); }
        }
        .accessible {
            color: green;
        }
        .extended {
            display: none;
        }
    </style>
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
                $services = ref($busStopCode);
                if (empty($services)) {
                    echo "<h2>No bus services found at this bus stop. Check the bus stop code and try again.</h2>";
                } else {
                    echo "<table id='busTable'>";
                    echo "<tr>
                            <th>Service Number</th>
                            <th>Operator</th>
                            <th>Next Bus</th>
                            <th>Next Bus 2</th>
                        </tr>";
                    foreach ($services as $serviceNumber => $service) {
                        echo "<tr data-service='" . json_encode($service) . "'>";
                        echo "<td>" . htmlspecialchars($serviceNumber) . "</td>";
                        echo "<td>" . htmlspecialchars($service['Operator']) . "</td>";
                        foreach (['NextBus', 'NextBus2'] as $nextBus) {
                            // $timeRemaining = isset($service[$nextBus]['TimeRemaining'])
                            //     ? htmlspecialchars($service[$nextBus]['TimeRemaining'])
                            //     : 'No data';
                            if (empty($service[$nextBus])){
                                echo "<td></td>";
                            }else{
                                $accessibility = htmlspecialchars($service[$nextBus]['Feature']) == "WAB" ? "inline" : "none";
                                echo "<td>";
                                echo "<span class='arrival-time'>" . $service[$nextBus]['TimeRemaining'][0]  . "mins".$service[$nextBus]['TimeRemaining'][1]."</span>";
                                echo "<span class='accessible' style='display: $accessibility;'>♿</span>";
                                echo "<div class='extended'>";
                                echo "Load: " . htmlspecialchars($service[$nextBus]['Load']) . "<br>";
                                echo "Type: " . htmlspecialchars($service[$nextBus]['Type']);
                                echo "</div></td>";
                            }
                            
                        }
                        echo "</tr>";
                    }
                    echo "</table>";
                }
            ?>
        </div>
    </div>
    <script src="update.js"></script>
</body>
</html>
