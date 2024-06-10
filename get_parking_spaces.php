<?php
$conn = mysqli_connect('localhost:3307', 'root', '', 'fkpark');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$zone_id = isset($_GET['zone_id']) ? $_GET['zone_id'] : 'all';

if ($zone_id === 'all') {
    $sql = "
        SELECT parking_spaces.id, parking_spaces.area, parking_zones.name AS zone_name
        FROM parking_spaces
        INNER JOIN parking_zones ON parking_spaces.zone_id = parking_zones.id
    ";
    $stmt = $conn->prepare($sql);
} else {
    $zone_id = intval($zone_id);
    $sql = "
        SELECT parking_spaces.id, parking_spaces.area, parking_zones.name AS zone_name
        FROM parking_spaces
        INNER JOIN parking_zones ON parking_spaces.zone_id = parking_zones.id
        WHERE parking_spaces.zone_id = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $zone_id);
}
$stmt->execute();
$result = $stmt->get_result();

$spaces = [];
while ($row = $result->fetch_assoc()) {
    $spaces[] = $row;
}

header('Content-Type: application/json');
echo json_encode($spaces);

$stmt->close();
$conn->close();
?>
