<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 600px;
            width: 100%;
            text-align: center;
        }
        h1 {
            margin-bottom: 20px;
            color: #007BFF;
        }
        h2 {
            color: #d9534f;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .icon-wheelchair {
            color: #007BFF;
        }
    </style>
</head>
<body>
    <div class="container">
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
            } else {
                echo "<table>";
                echo "<tr>
                        <th>Service Number</th>
                        <th>Operator</th>
                        <th>Arrival</th>
                        <th>Next</th>
                    </tr>";
                foreach ($data['Services'] as $service) { 
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($service['ServiceNo']) . "</td>";
                    echo "<td>" . htmlspecialchars($service['Operator']) . "</td>";
                    foreach (['NextBus', 'NextBus2'] as $nextBus) {
                        echo "<td>" . htmlspecialchars($service[$nextBus]['EstimatedArrival']);
                        if (htmlspecialchars($service[$nextBus]['Feature']) == "WAB") {
                            echo " <span class='icon-wheelchair'>♿</span>";
                        }
                        echo "</td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
            }
        ?>
    </div>
</body>
</html>
