<?php
header('Content-Type: application/json');
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['lat']) && isset($_GET['long'])){
        include 'gettimes.php';
        $lat = $_GET['lat'];
        $long = $_GET['long'];
        $response = getAllData();
        if ($response == -1) return -1;
        $data = $_SERVER['stops'];
        $dists = [];
        $closest = [[10],[10],[10],[10],[10]];
        foreach ($data as $stop) {
            $x = abs($stop['Latitude'] - $lat) * 111.12;
            $y = abs($stop['Longitude'] - $long) * 111.21;
            $val = sqrt($x * $x + $y * $y);
            for ($i = 0; $i < 5; $i++){
                if ($val < $closest[$i][0]){
                    array_splice($closest, $i, 0, [[$val, $stop['BusStopCode'], $stop['Description']]]);
                    array_pop($closest);
                    break;
                }   
                // echo json_encode($closest), "\n";
            }
            // array_push($dists, [sqrt($x * $x + $y * $y), $stop['BusStopCode'],$stop['Description']]) ;
        }
        // sort($dists);
        echo json_encode(array_slice(array_map(function($arr) {
            return array_slice($arr, 1);
        }, $closest),0,10));
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

