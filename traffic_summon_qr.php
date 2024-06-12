<?php
include 'phpqrcode/qrlib.php';

$traffic_summon = isset($_GET['text']) ? $_GET['text'] : '';

// Create the URL that the QR code should redirect to
$url = "https://indah.ump.edu.my/CB22159/fkpark/traffic_summon_details.php?id=" . urlencode($traffic_summon);

// Generate the QR code
QRcode::png($url);
