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
    <style>
    body {
        font-family: Arial, sans-serif;
    }
    </style>
    <!-- <link rel="stylesheet" href="styles.css">  -->
</head>
<body>
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
    <?php
        list($key, $value) = explode('=', trim(file_get_contents(__DIR__ . '/.env')), 2);
        putenv("$key=$value");
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://datamall2.mytransport.sg/ltaodataservice/BusArrivalv2?BusStopCode='.$busStopCode,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array('AccountKey: ' . getenv("API_KEY")),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        $data = json_decode($response, true);

        if (empty($data['Services'])) {
            echo "<h2>No bus services found at this bus stop. Check the bus stop code and try again.</h2>";
        }else{
            echo "<table>";
            echo "<tr>
                    <th>Service Number</th>
                    <th>Operator</th>
                    <th>Next Bus Estimated Arrival</th>
                    <th>Next Bus Load</th>
                    <th>Next Bus Feature</th>
                    <th>Next Bus Type</th>
                    <th>Next Bus 2 Estimated Arrival</th>
                    <th>Next Bus 2 Load</th>
                    <th>Next Bus 2 Feature</th>
                    <th>Next Bus 2 Type</th>
                    <th>Next Bus 3 Estimated Arrival</th>
                    <th>Next Bus 3 Load</th>
                    <th>Next Bus 3 Feature</th>
                    <th>Next Bus 3 Type</th>
                </tr>";
            foreach ($data['Services'] as $service) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($service['ServiceNo']) . "</td>";
                echo "<td>" . htmlspecialchars($service['Operator']) . "</td>";
                foreach (['NextBus', 'NextBus2', 'NextBus3'] as $nextBus) {
                    echo "<td>" . htmlspecialchars($service[$nextBus]['EstimatedArrival']). "</td>";
                    echo "<td>" . htmlspecialchars($service[$nextBus]['Load']). "</td>";
                    echo "<td>" . htmlspecialchars($service[$nextBus]['Feature']) . "</td>";
                    echo "<td>" . htmlspecialchars($service[$nextBus]['Type']) . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        } 
    ?>
</body>
</html>
