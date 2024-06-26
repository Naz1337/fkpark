<?php

require_once 'bootstrap.php';

require_once 'database_util.php';
global $conn;

if (isset($_GET['plate'])) {
    $plate = $_GET['plate'];
    $select = 'SELECT id FROM vehicles WHERE vehicle_plate = ?';
    $stmt = mysqli_prepare($conn, $select);
    mysqli_stmt_bind_param($stmt, 's', $plate);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id);
    if (mysqli_stmt_fetch($stmt)) {
        to_url('vehicle_show.php?id=' . $id);
    } else {
        to_url('vehicles.php');
    }

    exit();
}

$select = <<<EOL
   SELECT qr_code
        , approval_status
        , vehicle_model
        , vehicle_plate
        , user_id
        , approved_by
        , approval_date
        , users.first_name
        , users.last_name
     FROM vehicles 
LEFT JOIN users ON vehicles.approved_by = users.id
    WHERE vehicles.id = ?
EOL;

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
    $vehicle_plate,
    $user_id,
    $approved_by,
    $approval_date,
    $first_name,
    $last_name
);

if (!mysqli_stmt_fetch($stmt)) {
    to_url('vehicles.php');
}

mysqli_stmt_close($stmt);

if (isset($_GET['download']) && $_GET['download'] === '1') {
    // Prepare the SQL query to fetch the vehicle_grant
    $select_grant = 'SELECT vehicle_grant FROM vehicles WHERE id = ?';
    $stmt_grant = mysqli_prepare($conn, $select_grant);
    mysqli_stmt_bind_param($stmt_grant, 'i', $id);

    // Execute the query
    mysqli_stmt_execute($stmt_grant);

    // Bind the result to a variable
    mysqli_stmt_bind_result($stmt_grant, $vehicle_grant);

    // Fetch the result
    if (!mysqli_stmt_fetch($stmt_grant)) {
        to_url('vehicles.php');
    }

    // Close the statement
    mysqli_stmt_close($stmt_grant);

    // Create a temporary file
    $temp_file = tmpfile();

    // Write the content to the temporary file
    fwrite($temp_file, $vehicle_grant);

    // Get the path of the temporary file
    $temp_file_path = stream_get_meta_data($temp_file)['uri'];

    // Set headers
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $vehicle_plate . '.pdf"');

    // Read the file and send it to the user
    readfile($temp_file_path);

    // Close the temporary file
    fclose($temp_file);

    exit();
}

require_once 'layout_top.php';
?>

<h1 class="mb-4">Vehicle</h1>

<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h3>Vehicle Information</h3>
            <p><strong>Model:</strong> <?php echo htmlspecialchars($vehicle_model); ?></p>
            <p><strong>Plate:</strong> <?php echo htmlspecialchars(strtoupper($vehicle_plate)); ?></p>
            <p><strong>Status:</strong>
                <span class="badge bg-<?php echo $approval_status ? 'success' : 'danger'; ?>">
                <?php echo $approval_status ? 'Approved' : 'Not Approved'; ?>
            </span>
            </p>
        </div>
        <?php if ($qr_filepath): ?>
            <div class="col-md-6">
                <h3>QR Code</h3>
                <img src="data:image/png;base64,<?php echo $qr_filepath; ?>" alt="QR Code" class="img-fluid">
            </div>
        <?php endif; ?>
    </div>
    <?php if ($approval_status === 1): ?>
        <div class="row mb-4">
            <div class="col">
                <h3>Approval</h3>
                <p><strong>Approved By:</strong> <?= $first_name . ' ' . $last_name ?></p>
                <p><strong>Approval Date:</strong> <?= $approval_date ?></p>
            </div>
        </div>
    <?php endif; ?>
    <div class="d-flex justify-content-center gap-3">

        <a href="user_profile_show.php?id=<?= $user_id ?>" class="btn btn-outline-primary">Owner Profile</a>
        <?php if (isset($_SESSION['user_id']) && ($_SESSION['user_id'] === $user_id || get_user_type() == 'staff')): ?>
            <form action="vehicle_delete.php" class="w-auto" method="post">
                <input type="hidden" value="<?= $id ?>" name="id">
                <button type="submit" class="btn btn-outline-danger">Delete</button>
            </form>
            <?php if ($_SESSION['user_id'] === $user_id && $approval_status === 0): ?>
                <a href="vehicle_edit.php?id=<?= $id ?>" class="btn btn-outline-secondary">Edit</a>
            <?php endif; ?>
            <a href="vehicle_show.php?id=<?= $id ?>&download=1" class="btn btn-primary">Download Grant</a>
            <?php if (get_user_type() == 'staff' && $approval_status === 0): ?>
                   <a href="vehicle_approve.php?id=<?= $id ?>" class="btn btn-success">Approve</a>
            <?php endif; ?>
        <?php endif; ?>

    </div>
</div>


<?php
require_once 'layout_bottom.php';
