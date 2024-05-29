<?php
require 'database_util.php';
global $conn;
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('location: login.php');
    return;
}

$username = strtolower($_POST['username']);

$select = <<<EOL
SELECT username, password
FROM users
WHERE username = ?
EOL;

$stmt = mysqli_prepare($conn, $select);
mysqli_stmt_bind_param($stmt, 's', $username);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $username, $hashed_password);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
mysqli_close($conn);

session_start();
if (password_verify($_POST['password'], $hashed_password)) {
    $_SESSION['username'] = $username;
    header('location: index.php');
}
else {
    header('location: login.php');
    $_SESSION['msg'] = [
        'type' => 'danger',
        'text' => 'Wrong username or password.'
    ];
}

