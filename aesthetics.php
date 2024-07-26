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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }
        h1 {
            font-size: 2em;
            margin-bottom: 20px;
            color: #444;
        }
        table {
            width: 100%;
            max-width: 600px;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .accessible {
            color: #009688;
        }
        .sliderContainer {
            margin-bottom: 20px;
            text-align: center;
        }
        .switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 20px;
        }
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 20px;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 14px;
            width: 14px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        input:checked + .slider {
            background-color: #007BFF;
        }
        input:checked + .slider:before {
            transform: translateX(20px);
        }
        .extended {
            display: none;
        }
    </style>
    <!-- <link rel="stylesheet" href="styles.css"> -->
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
                        echo "<td>";
                        echo htmlspecialchars($service[$nextBus]['EstimatedArrival']);
                        if (htmlspecialchars($service[$nextBus]['Feature']) == "WAB") {
                            echo " <span class='accessible'>♿</span>";
                        }
                        echo "<div class='extended'>";
                        echo "Load: " . htmlspecialchars($service[$nextBus]['Load']) . "<br>";
                        echo "Type: " . htmlspecialchars($service[$nextBus]['Type']);
                        echo "</div></td>"; 
                    }
                    echo "</tr>";
                }
                echo "</table>";
            } 
        ?>
    </div>
    <script>
        function toggleExtendedInfo() {
            var checkbox = document.getElementById('infoSlider');
            var extendedInfo = document.querySelectorAll('.extended');
            for (var i = 0; i < extendedInfo.length; i++) {
                if (checkbox.checked) {
                    extendedInfo[i].style.display = "block";
                } else {
                    extendedInfo[i].style.display = "none";
                }
            }
        }
    </script>
</body>
</html>
