<!doctype html>
<html>
<head>
    <title>
        <?php
        include 'gettimes.php';
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $busStopCode = trim($_POST['busStopCode']);
            echo (is_numeric($busStopCode) && strlen($busStopCode) == 5 && getStop($busStopCode) != NULL)
                ? "Stop " . $busStopCode . " Arrival Times"
                : "Invalid Bus Stop Code";
        } else {
            echo "Invalid request";
        }
        ?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="styles2.css"> -->
    <style>
        .wobble {
    animation: wobble 0.5s infinite !important;
}
@keyframes wobble {
    0% { transform: rotate(0deg); }
    25% { transform: rotate(5deg); }
    50% { transform: rotate(0deg); }
    75% { transform: rotate(-5deg); }
    100% { transform: rotate(0deg); }
}
    </style>
</head>
<body>
    <div>
        <h1><?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $busStopCode = trim($_POST['busStopCode']);
            if (is_numeric($busStopCode) && strlen($busStopCode) == 5 && getStop($busStopCode) != NULL) {
                echo "Stop " . $busStopCode;
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
        <h2 id="stopName"><?php 
                if (getStop($busStopCode) != NULL) {
                    echo getStop($busStopCode)."<br>";
                }
                
            ?></h2>
        <h2><?php 
            $services = timings($busStopCode);
            if (empty($services)) echo "No bus services found.";
        ?></h2>
        <button id="bookmarkButton" onclick="saveStop('<?php echo $busStopCode; ?>')">Bookmark</button>
        <div id="sliderContainer">
            <label for="infoSlider">Show Extended Information: </label>
            <label id="switch">
                <input type="checkbox" id="infoSlider" onchange="toggleExtendedInfo()">
                <span id="slider"></span>
            </label>
        </div>
        <div class="table-container">
            <?php
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
                                echo "<span class='wobble arrival-time " . $service[$nextBus]['Load']."' mins='" . $service[$nextBus]['TimeRemaining'][0]."' secs='" . $service[$nextBus]['TimeRemaining'][1]."'>". (($service[$nextBus]['TimeRemaining'][0] > 0) ? $service[$nextBus]['TimeRemaining'][0]." mins".$service[$nextBus]['TimeRemaining'][1] : "Arriving")." </span>";
                                echo "<span class='wobble'>". (($service[$nextBus]['TimeRemaining'][0] > 0) ? $service[$nextBus]['TimeRemaining'][0]." mins".$service[$nextBus]['TimeRemaining'][1] : "Arriving")." </span>";
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
    <!-- <script src="update.js"></script> -->
    <script src="bookmark.js"></script>
</body>
</html>
