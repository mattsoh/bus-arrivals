<!-- <?php
header('Content-Type: application/json');

$response = array();

if (isset($_GET['stop'])) {
    $busStopCode = trim($_GET['stop']);
    
    if (!empty($busStopCode) && is_numeric($busStopCode) && strlen($busStopCode) == 5) {
        list($key, $value) = explode('=', trim(file_get_contents(__DIR__ . '/.env')), 2);
        putenv("$key=$value");
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://datamall2.mytransport.sg/ltaodataservice/BusArrivalv2?BusStopCode=' . $busStopCode,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array('AccountKey: ' . getenv("API_KEY")),
        ));
        $response_data = curl_exec($curl);
        curl_close($curl);

        $data = json_decode($response_data, true);

        if (!empty($data['Services'])) {
            // Sort the services array by ServiceNo
            usort($data['Services'], function($a, $b) {
                return strcmp($a['ServiceNo'], $b['ServiceNo']);
            });

            $response['services'] = $data['Services'];
        }
    }
}

echo json_encode($response);
?> -->
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $busStopCode = trim($_POST['busStopCode']);
    
    if (!empty($busStopCode) && is_numeric($busStopCode) && strlen($busStopCode) == 5) {
        $url = 'http://datamall2.mytransport.sg/ltaodataservice/BusArrivalv2?BusStopCode=' . $busStopCode;
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        if (!empty($data['Services'])) {
            // Sort the services array by ServiceNo
            usort($data['Services'], function($a, $b) {
                return strcmp($a['ServiceNo'], $b['ServiceNo']);
            });

            $response['services'] = $data['Services'];
        }
        // Output or process $data here
    }
}
?>