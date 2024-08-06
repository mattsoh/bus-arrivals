<?php
list($key, $value) = explode('=', trim(file_get_contents(__DIR__ . '/.env')), 2);
putenv("$key=$value");
function getAllData(){
    $skip = 0;
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
        // echo json_encode($allData[100]);
        $_SERVER['stops'] = $allData;
        return 0;
    } catch (Exception $e) {
        throw new Exception("Error: " . $e->getMessage() . "\n");
        echo "Error: " . $e->getMessage() . "\n";
        http_response_code(500);
        return -1;
    }
}
function getNearestStops($lat, $long){
        // $lat = $_POST['latitude'];
        // $long = $_POST['longitude'];
        // $_SESSION['latitude'] = $latitude;
        // $_SESSION['longitude'] = $longitude;
        $response = getAllData();
        if ($response == -1) return -1;
        $data = $_SERVER['stops'];
        echo count($data), '\n';
        $dists = [];
        foreach ($data as $stop) {
            $x = abs($stop['Latitude'] - $lat) * 111.12;
            $y = abs($stop['Longitude'] - $long) * 111.21;
            // echo $x," ",$y,"\n";
            // if (sqrt($x * $x + $y * $y) < 10)
            array_push($dists, [sqrt($x * $x + $y * $y), $stop['BusStopCode'],$stop['Description']]) ;
        }
        sort($dists);
        return array_slice($dists, 0, 10);
        // echo array_keys($dists);
        // echo count($dists), '\n';
        // return $dists;
}

// getAllData();
// $res = getNearestStops(1.3153765, 103.804206);
// foreach ($res as $r){
//     echo $r[1], $r[2];
// }
// echo getStopName('11111');
?>

