<?php

require 'bootstrap.php';
require 'database_util.php';

$delete = 'DELETE FROM vehicles where id = ?';

global $conn;

$stmt = mysqli_prepare($conn, $delete);

mysqli_stmt_bind_param($stmt, 'i', $_POST['id']);
mysqli_stmt_execute($stmt);

to_url('vehicles.php');

