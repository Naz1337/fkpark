<?php
require_once 'bootstrap.php';
// get the parameter id from the url
if (!isset($_GET['id'])) {
    to_url('vehicles.php');
    return;
}

require_once 'database_util.php';
global $conn;

// get the vehicle information
$id = (int) $_GET['id'];

$approved_by = $_SESSION['user_id'];
// set it to current time but in the form of what mysql accepts
$approval_date = date('Y-m-d H:i:s');

// update the vehicle information approval_status to 1
$update = 'UPDATE vehicles SET approval_status = 1, approved_by = ?, approval_date = ? WHERE id = ?';
$stmt = mysqli_prepare($conn, $update);
mysqli_stmt_bind_param($stmt, 'isi', $approved_by, $approval_date, $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

// redirect to vehicles.php
to_url('vehicle_show.php?id=' . $id);