<?php

require_once 'bootstrap.php';

check_get_id();

$id = (int) $_GET['id'];

// delete sql
$delete = <<<EOL
    DELETE FROM users
          WHERE id = ?
EOL;

require_once 'database_util.php';
global $conn;

$stmt = mysqli_prepare($conn, $delete);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

to_url('users.php');
