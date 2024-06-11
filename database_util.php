<?php

$conn = mysqli_connect('localhost', 'root', '', 'fkpark');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


