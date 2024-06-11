<?php
session_start();

header('Location: login.php');

require_once 'database_util.php';
global $conn;

mysqli_query($conn, "UPDATE users SET is_logged_in = 0 WHERE id = " . $_SESSION['user_id'] . ";" );


session_destroy();
