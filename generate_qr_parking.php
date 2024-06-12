<?php
include 'phpqrcode/qrlib.php';
require_once 'bootstrap.php';

$space_id = isset($_GET['text']) ? $_GET['text'] : '';

// Create the URL that the QR code should redirect to
if ($_SERVER['APP_ENV'] !== 'development')
    $uri = '/CB22159/fkpark/view_qr_parking.php?text=' . $space_id;
else
    $uri = '/fkpark/view_gr_parking.php?text=' . $space_id;


// $url = "https://indah.ump.edu.my/CB22159/fkpark/view_qr_parking.php?id=" . urlencode($space_id);

// Generate the QR code
QRcode::png(get_base_url().$uri);
