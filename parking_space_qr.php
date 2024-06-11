<?php
include 'phpqrcode/qrlib.php';

$parking_space = isset($_GET['text']) ? $_GET['text'] : '';

// Create the URL that the QR code should redirect to
$url = "https://indah.ump.edu.my/CB22159/fkpark/view_reservation.php?id=" . urlencode($parking_space);

// Generate the QR code
QRcode::png($url);
