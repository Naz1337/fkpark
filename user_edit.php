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

<form class="container" action="user_update.php?id=<?= $id ?>" method="post">
    <h1 class="mb-4">User Profile</h1>

    <div class="mb-4">
        <h3 class="mb-4">User Information</h3>
        <div class="row">
            <div class="col-6">
                <div class="row">
                    <div class="col-3 col-form-label">
                        <label for="username" class="form-label">Username: </label>
                    </div>
                    <div class="col">
                        <input type="text" name="username" id="username" class="form-control" value="<?= $username ?>">
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
                        <input type="hidden" class="form-control" name="user_type" id="userType" value="<?= $user_type ?>">
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
                        <label for="firstName" class="form-label">First Name: </label>
                    </div>
                    <div class="col">
                        <input type="text" name="first_name" id="firstName" class="form-control" value="<?= $first_name ?>">
                    </div>
                </div>
            </div>
            <div class="mb-3 col-6">
                <div class="row">
                    <div class="col-3 col-form-label">
                        <label for="lastName" class="form-label">Last Name: </label>
                    </div>
                    <div class="col">
                        <div class="form-control-plaintext">
                            <input type="text" name="last_name" id="lastName" class="form-control" value="<?= $last_name ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-3 col-6">
                <div class="row">
                    <div class="col-3 col-form-label">
                        <label for="contactNumber" class="form-label">Contact Number: </label>
                    </div>
                    <div class="col">
                        <div class="form-control-plaintext">
                            <input type="text" name="contact_number" id="contactNumber" class="form-control" value="<?= $contact_number ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-3 col-6">
                <div class="row">
                    <div class="col-3 col-form-label">
                        <label for="address" class="form-label">Address: </label>
                    </div>
                    <div class="col">
                        <input type="text" name="address" id="address" class="form-control" value="<?= $address ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center gap-3">
        <button type="reset" class="btn btn-outline-secondary">Reset</button>
        <button class="btn btn-primary" type="submit">Submit</button>
    </div>
</form>

<?php
require_once 'layout_bottom.php';
?>

