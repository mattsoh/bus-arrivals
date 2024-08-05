<?php
list($key, $value) = explode('=', trim(file_get_contents(__DIR__ . '/.env')), 2);
putenv("$key=$value");
function getAllData(){
    $mid = 1;
    do {
        echo $mid;
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://datamall2.mytransport.sg/ltaodataservice/Bup?$skip='.$mid*500,
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
           
            // return -1;
        }
        $response_data = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response_data, true);
        echo $data;
        $mid++;
    } while (!empty($data));
}
function getNearestStops($longitude){
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['latitude']) && isset($_POST['longitude'])) {
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        $_SESSION['latitude'] = $latitude;
        $_SESSION['longitude'] = $longitude;
    }
}
// function getStopName($stop){
//     $count = 0;
//     $left = 0;
//     $right = 10;
//     while ($left <= $right){
//         $mid = round(($left+$right)/2);
//         // echo $left . ' '.$right .' '. $mid . "\n";
//         $curl = curl_init();
//         curl_setopt_array($curl, array(
//         CURLOPT_URL => 'http://datamall2.mytransport.sg/ltaodataservice/BusStops?$skip='.$mid*500,
//         CURLOPT_RETURNTRANSFER => true,
//         CURLOPT_ENCODING => '',
//         CURLOPT_MAXREDIRS => 10,
//         CURLOPT_TIMEOUT => 10,
//         CURLOPT_FOLLOWLOCATION => true,
//         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//         CURLOPT_CUSTOMREQUEST => 'GET',
//         CURLOPT_HTTPHEADER => array('AccountKey: '. getenv("API_KEY")),
//         ));
//         if (curl_errno($curl)) {
//             curl_close($curl);
//             return -1;
//         }
//         $response = curl_exec($curl);
//         curl_close($curl);
//         $data = json_decode($response, true)["value"];
//         // echo $data[0]["BusStopCode"] . $data[count($data)-1]["BusStopCode"] . "\n";
//         if (empty($data)) return -5;
//         else if ($data[0]["BusStopCode"] > $stop){
//             $right = $mid-1;
//         }else if ($data[count($data)-1]["BusStopCode"] < $stop){
//             $left = $mid+1;
//         }else{
//             break;
//         }
//     }
//     $left = 0;
//     $right = count($data)-1;
//     while ($left <= $right){
//         $mid = round(($left+$right)/2);
//         if ($data[$mid]["BusStopCode"] == $stop){
//             return $data[$mid]["Description"];
//         }else if ($left == $right){
//             http_response_code(404);
//             return NULL;
//         }else if ($data[$mid]["BusStopCode"] <= $stop){
//             $left = $mid+1;
//         }else{
//             $right = $mid-1;
//         }
//     }
// }
getAllData();
?>

