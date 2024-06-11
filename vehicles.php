<?php
require_once 'layout_top.php';
require_once 'database_util.php';

global $conn;

if (isset($_GET['user_id'])) {
    $user_id = (int) $_GET['user_id'];
    $select = <<<EOL
    SELECT * FROM vehicles WHERE user_id = $user_id;
    EOL;
} else {
    $select = <<<EOL
    SELECT * FROM vehicles WHERE user_id = {$_SESSION['user_id']};
    EOL;

}

$result = mysqli_query($conn, $select);

?>
<div class="container">
<h1 class="mb-3">
    Vehicles
</h1>
<div class="mb-4 d-flex gap-4">
<?php if (isset($_GET['user_id'])) : ?>
    <a href="user_profile_show.php?id=<?= $_GET['user_id'] ?>" class="btn btn-outline-primary mb-4">Back</a>
<?php else: ?>
    <a href="vehicles_form.php" class="btn btn-outline-success">Register Vehicle</a>
<?php endif; ?>
</div>
<div class="row">
<?php
    while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <div class="col-6">
            <div class="card mb-3 shadow" style="min-height: 3rem;">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class=""><?= strtoupper($row['vehicle_plate']) ?></h5>
                        <p><?= $row['vehicle_model'] ?></p>
                        <p>
                            <?php
                            if ($row['approval_status'] === '0')
                                echo '<strong class="text-danger">Not Approved</strong>';
                            else
                                echo '<strong class="text-success">Approved</strong>';
                            ?>
                        </p>
                    </div>

                    <a href="vehicle_show.php?id=<?= $row['id'] ?>" class="btn btn-outline-primary stretched-link">
                        Go
                    </a>
                </div>
            </div>
            </div>
        <?php
    }

?>
</div>
</div>


<?php
require_once 'layout_bottom.php';
