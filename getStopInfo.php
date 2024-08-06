<?php
list($key, $value) = explode('=', trim(file_get_contents(__DIR__ . '/.env')), 2);
putenv("$key=$value");
function getAllData(){
    $skip = 1;
    $allData = [];
    try {
        do {
            // echo $skip;
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://datamall2.mytransport.sg/ltaodataservice/BusStops?$skip='.$skip*500,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array('AccountKey: '. getenv("API_KEY")),
            ));
            if (curl_errno($curl)) {
                echo $code;
                $code = curl_errno($curl);
                switch ($code) {
                    case 408:
                        http_response_code(504);
                        break;
                    default:
                        http_response_code(500);
                }
                return -1;
            }
            $response_data = curl_exec($curl);
            curl_close($curl);
            $data = json_decode($response_data, true);
            // echo $data;
            $allData = array_merge($allData, $data['value']);
            $skip++;
        } while (!empty($data['value']));
        echo json_encode($allData[100]);
        $_SERVER['stops'] = $allData;
        return 0;
    } catch (Exception $e) {
        throw new Exception("Error: " . $e->getMessage() . "\n");
        echo "Error: " . $e->getMessage() . "\n";
        http_response_code(500);
        return -1;
    }
}
function getNearestStops($longitude){
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_POST['latitude']) && isset($_POST['longitude'])) {
        $lat = $_POST['latitude'];
        $long = $_POST['longitude'];
        // $_SESSION['latitude'] = $latitude;
        // $_SESSION['longitude'] = $longitude;
        $dists = [];
        foreach ($data as $stop) {
            $x = ($stop['Latitude'] - $lat) * 111;
            $y = ($stop['Longitude'] - $long) * 111;
            $dists[$stop['BusStopCode']] = (sqrt($x * $x + $y * $y) > 5) ? 0: sqrt($x * $x + $y * $y);
        }
        
    }
}
function getStopName($stop){
    $response = getAllData();
    if ($response == -1) return -1;
    $data = $_SERVER['stops'];
    $count = 0;
    $left = 0;
    $right = count($data)-1;
    while ($left <= $right){
        $mid = round(($left+$right)/2);
        if ($data[$mid]["BusStopCode"] == $stop){
            return $data[$mid]["Description"];
        }else if ($left == $right){
            http_response_code(404);
            return NULL;
        }else if ($data[$mid]["BusStopCode"] <= $stop){
            $left = $mid+1;
        }else{
            $right = $mid-1;
        }
    }
    http_response_code(404);
    return NULL;
}
getAllData();
?>

