<?php
list($key, $value) = explode('=', trim(file_get_contents(__DIR__ . '/.env')), 2);
putenv("$key=$value");
function timings($busStopCode) {
    
    $services = []; 
    if (!empty($busStopCode) && is_numeric($busStopCode) && strlen($busStopCode) == 5) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://datamall2.mytransport.sg/ltaodataservice/BusArrivalv2?BusStopCode=' . $busStopCode,
            CURLOPT_RETURNTRANSFER => true,
            // CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            // CURLOPT_FOLLOWLOCATION => true,
            // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array('AccountKey: ' . getenv("API_KEY")),
        ));
        $response_data = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response_data, true);

        if (!empty($data['Services'])) {
            foreach ($data['Services'] as &$service) {
                foreach (['NextBus', 'NextBus2'] as $nextBus) {
                    if (!empty($service[$nextBus]['EstimatedArrival'])) {
                        $estimatedArrival = new DateTime($service[$nextBus]['EstimatedArrival']);
                        $now = new DateTime();
                        
                        $interval = $now->diff($estimatedArrival);
                        $service[$nextBus]['TimeRemaining'] = ($interval->invert) ? [0,0] : [$interval->i,$interval->s];
                        unset($service[$nextBus]['OriginCode']);
                        unset($service[$nextBus]['DestinationCode']);
                        unset($service[$nextBus]['EstimatedArrival']);
                        unset($service[$nextBus]['Latitude']);
                        unset($service[$nextBus]['Longitude']);
                        unset($service[$nextBus]['VisitNumber']);
                    } else {
                        $service[$nextBus] = [];
                    }
                }
                unset($service['NextBus3']);
                $serviceNo = $service['ServiceNo'];
                unset($service['ServiceNo']);
                $services[$serviceNo] = $service;
            }
        }
        ksort($services);
    }
    return $services;
}

function getStop($stop){
    $count = 0;
    while (true){
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://datamall2.mytransport.sg/ltaodataservice/BusStops?$skip='.$count,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array('AccountKey: '. getenv("API_KEY")),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $count+=500;
        $data = json_decode($response, true)["value"];
        if (empty($data)) return NULL;
        foreach ($data as $poss) {
            if ($poss["BusStopCode"] == $stop){
                return $poss["Description"];
            }
        }
        // function binarySearch($array, $target) {
        //     $left = 0;
        //     $right = count($array) - 1;
        
        //     while ($left <= $right) {
        //         $mid = floor(($left + $right) / 2);
        
        //         if ($array[$mid] == $target) {
        //             return $mid;
        //         } elseif ($array[$mid] < $target) {
        //             $left = $mid + 1;
        //         } else {
        //             $right = $mid - 1;
        //         }
        //     }
        
        //     return -1;
        // }
    }
    
    // return $data;
    
}
// $services = timings("11111");
// echo getStop("99189");
// header('Content-Type: application/json');
// echo json_encode($services);
?>
