<?php
require_once 'database_util.php';
global $conn;

$selectOpen = "SELECT COUNT(*) FROM parking_zones WHERE status = 'Open'";
$selectClosed = "SELECT COUNT(*) FROM parking_zones WHERE status = 'Closed'";
$selectMaintenance = "SELECT COUNT(*) FROM parking_zones WHERE status = 'Under maintenance'";

$resultOpen = mysqli_query($conn, $selectOpen);
$resultClosed = mysqli_query($conn, $selectClosed);
$resultMaintenace = mysqli_query($conn, $selectMaintenance);

$open = mysqli_fetch_assoc($resultOpen);
$closed = mysqli_fetch_assoc($resultClosed);
$maintenance = mysqli_fetch_assoc($resultMaintenace);

$openCount = $open['COUNT(*)'];
$closedCount = $closed['COUNT(*)'];
$maintenanceCount = $maintenance['COUNT(*)'];

// return parking space that is Available and Not Available in array json

echo json_encode([
    (int) $availableCount,
    (int) $closedCount,
    (int) $maintenanceCount
]);