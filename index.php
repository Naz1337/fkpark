<?php

require_once 'layout_top.php';

if (!isset($_SESSION['username'])) {
    to_url('login.php');
}

?>

<h1>User Dashboard</h1>
<p>Logged in as: <?= $_SESSION['username'] ?></p>
<?php vite_asset('js/user_dashboard.js') ?>
<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                    <div id="onlineOfflinePie"></div>
            </div>
        </div>
        
    </div>
</div>


<?php
require_once 'layout_bottom.php';

