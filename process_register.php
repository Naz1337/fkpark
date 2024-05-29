<?php
require 'database_util.php';
global $conn;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    return header('location: login.php');
}

$username = strtolower($_POST['username']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$insert_statement = <<<EOL
INSERT INTO users (username, password, user_type, first_name, last_name, contact_number, address)
VALUES (?, ?, ?, ?, ?, ?, ?);
EOL;

$stmt = mysqli_prepare($conn, $insert_statement);

mysqli_stmt_bind_param(
    $stmt,
    'sssssss',
    $username,
    $password,
    $_POST['user_type'],
    $_POST['first_name'],
    $_POST['last_name'],
    $_POST['contact_number'],
    $_POST['address']
);
session_start();
if (mysqli_stmt_execute($stmt)) {
    $_SESSION['msg'] = [
        'type' => 'success',
        'text' => 'User successfully registered!'
    ];
    header('location: login.php');
    return;
}
$_SESSION['msg'] = [
    'type' => 'fail',
    'text' => 'Something went wrong!'
];
header('location: register.php');