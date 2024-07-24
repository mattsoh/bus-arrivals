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
            echo "Bus Arrival Information";
        }
        ?>
    </title>
    <link rel="stylesheet" href="styles.css"> 
</head>
<body>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $busStopCode = trim($_POST['busStopCode']);

    if (!empty($busStopCode) && is_numeric($busStopCode) && strlen($busStopCode) == 5) {
        echo "<h1>Bus Stop Code: " . htmlspecialchars($busStopCode) . "</h1>";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://datamall2.mytransport.sg/ltaodataservice/BusArrivalv2?BusStopCode=' . $busStopCode,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array('AccountKey: ' . getenv("API_KEY")),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $data = json_decode($response, true);

        if (!empty($data['Services'])) {
            foreach ($data['Services'] as $service) {
                echo "<div class='bus-info'>";
                echo "<h2>Service Code: " . htmlspecialchars($service['ServiceNo']) . "</h2>";
                echo "<ul>";
                echo "<li>Operator: " . htmlspecialchars($service['Operator']) . "</li>";

                foreach (['NextBus', 'NextBus2', 'NextBus3'] as $nextBus) {
                    if (!empty($service[$nextBus]['EstimatedArrival'])) {
                        echo "<li>" . ($nextBus === 'NextBus' ? 'Next Bus' : ($nextBus === 'NextBus2' ? 'Next Bus 2' : 'Next Bus 3')) . ":</li>";
                        echo "<ul>";
                        echo "<li>Estimated Arrival: " . htmlspecialchars($service[$nextBus]['EstimatedArrival']) . "</li>";
                        echo "<li>Load: " . htmlspecialchars($service[$nextBus]['Load']) . "</li>";
                        echo "<li>Feature: " . htmlspecialchars($service[$nextBus]['Feature']) . "</li>";
                        echo "<li>Type: " . htmlspecialchars($service[$nextBus]['Type']) . "</li>";
                        echo "</ul>";
                    }
                }

                echo "</ul>";
                echo "</div>";
            }
        } else {
            echo "<h2>No bus services found at this bus stop. Check the bus stop </h2>";
        }
    } else {
        echo "<h1>Invalid bus stop code. Please enter a valid code.</h1>";
    }
} else {
    echo "<h1>Invalid request method.</h1>";
}
?>
</body>
</html>
