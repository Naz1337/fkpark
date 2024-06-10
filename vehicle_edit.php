<?php
require_once 'layout_top.php';

if (!isset($_GET['id'])) {
    to_url('vehicles.php');
    return;
}

require_once 'database_util.php';
global $conn;

$id = (int) $_GET['id'];
$select = 'SELECT  id, vehicle_model, vehicle_plate FROM vehicles WHERE id = ?';
$stmt = mysqli_prepare($conn, $select);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result(
    $stmt,
    $id,
    $vehicle_model,
    $vehicle_plate
);

if (!mysqli_stmt_fetch($stmt)) {
    to_url('vehicles.php');
}

?>

<h1 class="mb-4">Edit Vehicle</h1>
<form action="vehicle_update.php?id=<?= $id ?>" method="post">
    <div class="row mb-3">
        <div class="col-2 col-form-label">
            <label for="vehicleModel">Vehicle Model</label>
        </div>
        <div class="col">
            <input type="text" class="form-control" value="<?= $vehicle_model ?>" id="vehicleModel" name="vehicle_model">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-2 col-form-label">
            <label for="vehiclePlate" class="form-label">Vehicle Plate</label>
        </div>
        <div class="col">
            <input type="text" id="vehiclePlate" name="vehicle_plate" value="<?= $vehicle_plate ?>" class="form-control">
        </div>
    </div>
    <div class="row">
        <div class="col d-flex gap-3">
            <button class="btn btn-primary">Submit</button>
        </div>
    </div>
</form>


<?php
require_once 'layout_bottom.php';
