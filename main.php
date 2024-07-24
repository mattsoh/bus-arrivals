<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SG Bus Arrival</title>
    <link rel="stylesheet" href="styles.css"> 
</head>
<body>
    <h1>Find your bus arrival time!</h1>
    <form action="request.php" id="busStopForm" method="post">
        <label for="busStopNumber">Bus Stop Number:</label>
        <input type="number" id="busStopNumber" name="busStopNumber" maxlength="5" pattern="[0-9]*" placeholder="bus no. goes here!" required>
        <span id="error-message" class="error"></span>
        <input type="submit" value="Submit">
    </form>
    <script src="validstop.js"></script>