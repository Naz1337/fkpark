<?php
require_once 'bootstrap.php';

if ($_SERVER['APP_ENV'] === 'development') {
    $conn = mysqli_connect('localhost', 'root', '', 'fkpark');
}
else {
    $conn = mysqli_connect('10.26.30.17', 'cb22159', 'cb22159', 'cb22159');
}

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


