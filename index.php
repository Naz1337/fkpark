<?php

require_once 'layout_top.php';

if (!isset($_SESSION['username'])) {
    header('location:login.php');
    return;
}

?>

<h1>User Dashboard</h1>
<p>Logged in as: <?= $_SESSION['username'] ?></p>

<?php
require_once 'layout_bottom.php';

