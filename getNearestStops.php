<?php
header('Content-Type: application/json');
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['lat']) && isset($_GET['long'])){
        $lat = $_GET['lat'];
        $long = $_GET['long'];
        include 'gettimes.php';
        $response = getAllData();
        if ($response == -1) return -1;
        $data = $_SERVER['stops'];
        $dists = [];
        foreach ($data as $stop) {
            $x = abs($stop['Latitude'] - $lat) * 111.12;
            $y = abs($stop['Longitude'] - $long) * 111.21;
            array_push($dists, [sqrt($x * $x + $y * $y), $stop['BusStopCode'],$stop['Description']]) ;
        }
        sort($dists);
        echo json_encode(array_slice(array_map(function($arr) {
            return array_slice($arr, 1);
        }, $dists),0,10));
    } else {
        http_response_code(400);
    }
} else {
    http_response_code(405);
}

// getAllData();
// $res = getNearestStops(1.3153765, 103.804206);
// foreach ($res as $r){
//     echo $r[1], $r[2];
// }
// echo getStopName('11111');
?>

