<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $busStopNumber = trim($_POST['busStopNumber']);

    if (!empty($busStopNumber) && is_numeric($busStopNumber)) {
        echo "<h1>Bus Stop Number: " . htmlspecialchars($busStopNumber) . "</h1>";

    } else {
        echo "<h1>Invalid bus stop number. Please enter a valid number.</h1>";
    }
} else {
    echo "<h1>Invalid request method.</h1>";
}
?>