<?php
require_once 'bootstrap.php';

// if the request method is post if not, redirect to vehicles.php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    to_url('vehicles.php');
    return;
}

require_once 'database_util.php';
global $conn;

// check if current plate is the same as the new plate

$select = 'SELECT vehicle_plate, qr_code FROM vehicles WHERE id = ?';
$stmt = mysqli_prepare($conn, $select);
mysqli_stmt_bind_param($stmt, 'i', $_GET['id']);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $current_plate, $qr_code);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if ($current_plate !== $_POST['vehicle_plate']) {
    // update qr to new plate
    $base = get_base_url();
    $uri = '/fkpark/vehicle_show.php?plate=' . $_POST['vehicle_plate'];
    $qr_code = qr_base64($base . $uri);
}

// prepare the update statement
$update = 'UPDATE vehicles SET vehicle_model = ?, vehicle_plate = ?, qr_code = ? WHERE id = ?';
$stmt = mysqli_prepare($conn, $update);
mysqli_stmt_bind_param($stmt, 'sssi', $_POST['vehicle_model'], $_POST['vehicle_plate'], $qr_code, $_GET['id']);
mysqli_stmt_execute($stmt);

// redirect to vehicles.php
to_url('vehicles.php');
?>
