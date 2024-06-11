<?php
require_once 'bootstrap.php';

// check if id exist in get parameter
if (!isset($_GET['id'])) {
    to_url('user_profiles.php');
    return;
}

// get user information using the id
$id = (int) $_GET['id'];
require_once 'database_util.php';
global $conn;

$select = 'SELECT * from users where id = ?';
$stmt = mysqli_prepare($conn, $select);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result(
    $stmt,
    $id,
    $username,
    $password,
    $user_type,
    $first_name,
    $last_name,
    $contact_number,
    $address
);

if (!mysqli_stmt_fetch($stmt)) {
    to_url('user_profiles.php');
    return;
}
mysqli_stmt_close($stmt);

require_once 'layout_top.php';
?>

<div class="container">
    <h1 class="mb-4">User Profile</h1>

    <div class="mb-4">
        <h3 class="mb-4">User Information</h3>
        <div class="row">
            <div class="col-6">
                <div class="row">
                    <div class="col-3 col-form-label">
                        <label for="" class="form-label">Username: </label>
                    </div>
                    <div class="col">
                        <div class="form-control-plaintext">
                            <?= $username ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="row">
                    <div class="col-3 col-form-label">
                        <label for="" class="form-label">User Type: </label>
                    </div>
                    <div class="col">
                        <div class="form-control-plaintext">
                            <span class="badge bg-primary"><?= ucwords($user_type) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-4">
        <h3 class="mb-4">Personal Information</h3>
        <div class="row">
            <div class="col-6">
                <div class="row">
                    <div class="col-3 col-form-label">
                        <label for="" class="form-label">First Name: </label>
                    </div>
                    <div class="col">
                        <div class="form-control-plaintext">
                            <?= $first_name ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-3 col-6">
                <div class="row">
                    <div class="col-3 col-form-label">
                        <label for="" class="form-label">Last Name: </label>
                    </div>
                    <div class="col">
                        <div class="form-control-plaintext">
                            <?= $last_name ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-3 col-6">
                <div class="row">
                    <div class="col-3 col-form-label">
                        <label for="" class="form-label">Contact Number: </label>
                    </div>
                    <div class="col">
                        <div class="form-control-plaintext">
                            <?= $contact_number ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-3 col-6">
                <div class="row">
                    <div class="col-3 col-form-label">
                        <label for="" class="form-label">Address: </label>
                    </div>
                    <div class="col">
                        <div class="form-control-plaintext"><?= $address ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center gap-3">
        <a href="vehicles.php?user_id=<?= $id ?>" class="btn btn-primary">View Cars</a>
        <a href="user_edit.php?id=<?= $id ?>" class="btn btn-outline-primary">Edit</a>
        <?php if (get_user_type() == 'admin'): ?>
            <a href="user_delete.php?id=<?= $id ?>" class="btn btn-outline-danger">Delete</a>
        <?php endif; ?>

    </div>
</div>

<?php
require_once 'layout_bottom.php';
?>

