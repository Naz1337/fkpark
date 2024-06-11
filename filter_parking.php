<?php

require_once 'database_util.php';
global $conn;
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$zone = $_GET['zone'] ?? 'all';

$sql = "SELECT parking_spaces.area, parking_spaces.id, parking_zones.name  FROM parking_spaces INNER JOIN parking_zones ON parking_spaces.zone_id=parking_zones.id";
if ($zone !== 'all') {
    $sql .= " WHERE parking_spaces.zone_id = ?";
}

$stmt = $conn->prepare($sql);

if ($zone !== 'all') {
    $stmt->bind_param("s", $zone);
}

$stmt->execute();
$result = $stmt->get_result();

$parkingSpaces = [];
while($row = $result->fetch_assoc()) {
    $parkingSpaces[] = $row;
}

echo json_encode($parkingSpaces);

$stmt->close();
$conn->close();
?>