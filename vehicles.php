<?php
require_once 'layout_top.php';
require_once 'database_util.php';

global $conn;

$select = <<<EOL
SELECT * FROM vehicles;
EOL;


$result = mysqli_query($conn, $select);

?>

<h1 class="mb-3">
    Vehicles
</h1>
<div class="mb-4 d-flex gap-4">
    <a href="vehicles_form.php" class="btn btn-outline-success">Register Vehicle</a>
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



<?php
require_once 'layout_bottom.php';
