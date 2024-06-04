<?php
require_once 'layout_top.php';

require_once 'database_util.php';
global $conn;

$select = 'SELECT qr_code, approval_status, vehicle_model, vehicle_plate FROM vehicles WHERE id = ?';
$stmt = mysqli_prepare($conn, $select);
if (!isset($_GET['id'])) {
    to_url('vehicles.php');
    return;
}
$id = (int) $_GET['id'];


mysqli_stmt_bind_param($stmt, 'i', $id);

mysqli_stmt_execute($stmt);

mysqli_stmt_bind_result(
    $stmt,
    $qr_filepath,
    $approval_status,
    $vehicle_model,
    $vehicle_plate
);

if (!mysqli_stmt_fetch($stmt)) {
    to_url('vehicles.php');
}
?>

<h1 class="mb-4">Vehicle</h1>

<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h3>Vehicle Information</h3>
            <p><strong>Model:</strong> <?php echo htmlspecialchars($vehicle_model); ?></p>
            <p><strong>Plate:</strong> <?php echo htmlspecialchars($vehicle_plate); ?></p>
            <p><strong>Status:</strong>
                <span class="badge bg-<?php echo $approval_status ? 'success' : 'warning'; ?>">
                <?php echo $approval_status ? 'Approved' : 'Pending'; ?>
            </span>
            </p>
        </div>
        <?php if ($qr_filepath): ?>
            <div class="col-md-6">
                <h3>QR Code</h3>
                <img src="<?php echo htmlspecialchars($qr_filepath); ?>" alt="QR Code" class="img-fluid">
            </div>
        <?php endif; ?>
    </div>
    <div class="d-flex justify-content-center gap-3">
        <form action="vehicle_delete.php" class="w-auto" method="post">
            <input type="hidden" value="<?= $id ?>" name="id">
            <button type="submit" class="btn btn-outline-danger">Delete</button>
        </form>
        <a href="vehicle_edit.php?id=<?= $id ?>" class="btn btn-secondary">Edit</a>
    </div>
</div>


<?php
require_once 'layout_bottom.php';
