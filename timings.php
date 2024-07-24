<!doctype html>
<html>
<head></head>
<body>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $busStopNumber = trim($_POST['busStopNumber']);

    if (!empty($busStopNumber) && is_numeric($busStopNumber) && strlen($busStopNumber) == 5) {
        echo "<h1>Bus Stop Number: " . htmlspecialchars($busStopNumber) . "</h1>";

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://datamall2.mytransport.sg/ltaodataservice/BusArrivalv2?BusStopCode='. $busStopNumber,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'AccountKey: '.getenv("API_KEY")
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo "<h2>Result: ".$response."</h2>";

    } else {
        echo "<h1>Invalid bus stop number. Please enter a valid number.</h1>";
    }
} else {
    echo "<h1>Invalid request method.</h1>";
}
?>
</body>
</html>