<?php

require_once 'layout_top.php';

if (!isset($_SESSION['username'])) {
    to_url('login.php');
}

?>

<h1>User Dashboard</h1>
<p>Logged in as: <?= $_SESSION['username'] ?></p>



<?php
require_once 'layout_bottom.php';

