<?php

require_once 'layout_top.php';

if (!isset($_SESSION['username'])) {
    to_url('login.php');
}

?>

<h1>Summon Traffic Management</h1>

<?php
require_once 'layout_bottom.php';

