<?php
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['stop']) && !empty($_GET['stop']) && is_numeric($_GET['stop']) && strlen($_GET['stop']) == 5){ 
    $stop = $_GET['stop'];  
    $response = getAllData();
    if ($response == -1) return -1;
    $data = $_SERVER['stops'];
    $count = 0;
    $left = 0;
    $right = count($data)-1;
    while ($left <= $right){
        // echo $left, ' ', $right, "\n";
        $mid = round(($left+$right)/2);
        // echo $data[$mid]["BusStopCode"], $stop, ($data[$mid]["BusStopCode"]<$stop)?"small" : "big";
        if ($data[$mid]["BusStopCode"] == $stop){
            return $data[$mid]["Description"];
        }else if ($left == $right){
            http_response_code(404);
            // echo $left, $data[$mid]["BusStopCode"];
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
?>