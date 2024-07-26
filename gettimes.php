<?php
function ref($busStopCode) {
    $services = []; 
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

        // Decode the JSON response
        $data = json_decode($response_data, true);

        // Check if services data exists
        if (!empty($data['Services'])) {
            foreach ($data['Services'] as &$service) {
                foreach (['NextBus', 'NextBus2'] as $nextBus) {
                    if (!empty($service[$nextBus]['EstimatedArrival'])) {
                        $estimatedArrival = new DateTime($service[$nextBus]['EstimatedArrival']);
                        $now = new DateTime();
                        $interval = $now->diff($estimatedArrival);
                        $service[$nextBus]['TimeRemaining'] = [$interval->i,$interval->s];
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
$services = ref("11111");
// header('Content-Type: application/json');
// echo json_encode($services);
?>
