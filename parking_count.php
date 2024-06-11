<?php
require_once 'database_util.php';
global $conn;

$selectAvailable = 'SELECT COUNT(*) FROM parking_spaces WHERE is_available = 1';
$selectNotAvailable = 'SELECT COUNT(*) FROM parking_spaces WHERE is_available = 0';

$resultAvailable= mysqli_query($conn, $selectAvailable);
$resultNotAvailable= mysqli_query($conn, $selectNotAvailable);

$available = mysqli_fetch_assoc($resultAvailable);
$notAvailable = mysqli_fetch_assoc($resultNotAvailable);

$availableCount = $available['COUNT(*)'];
$notAvailableCount = $notAvailable['COUNT(*)'];

// return parking space that is Available and Not Available in array json

echo json_encode([
    (int) $availableCount,
    (int) $notAvailableCount
]);