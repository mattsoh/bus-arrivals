<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $lat = $_GET['lat'];
    $long = $_GET['long'];
// function getNearestStops($lat, $long){
    include 'gettimes.php';
    $response = getAllData();
    if ($response == -1) return -1;
    $data = $_SERVER['stops'];
    // echo count($data), '\n';
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
} else {
    
}

// getAllData();
// $res = getNearestStops(1.3153765, 103.804206);
// foreach ($res as $r){
//     echo $r[1], $r[2];
// }
// echo getStopName('11111');
?>

