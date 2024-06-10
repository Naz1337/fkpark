<?php
include 'phpqrcode/qrlib.php';

$reservation_id = isset($_GET['text']) ? $_GET['text'] : '';

// Create the URL that the QR code should redirect to
$url = "http://localhost/fkpark/view_reservation.php?id=" . urlencode($reservation_id);

// Generate the QR code
QRcode::png($url);
?>