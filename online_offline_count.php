<?php
require_once 'database_util.php';
global $conn;

$selectOnline = 'SELECT COUNT(*) FROM users WHERE is_logged_in = 1';
$selectOffline = 'SELECT COUNT(*) FROM users WHERE is_logged_in = 0';

$resultOnline = mysqli_query($conn, $selectOnline);
$resultOffline = mysqli_query($conn, $selectOffline);

$online = mysqli_fetch_assoc($resultOnline);
$offline = mysqli_fetch_assoc($resultOffline);

$onlineCount = $online['COUNT(*)'];
$offlineCount = $offline['COUNT(*)'];

// return onlinecount and offlinecount in array json

echo json_encode([
    (int) $onlineCount,
    (int) $offlineCount
]);