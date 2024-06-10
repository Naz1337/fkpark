<?php

require_once 'bootstrap.php';

if (!isset($_GET['id'])) {
    to_url('users.php');
    return;
}

/*
 * This is example of what in $_GET and $_POST
 *
 * array(1) { ["id"]=> string(1) "8" }
 * array(6) { ["username"]=> string(7) "student" ["user_type"]=> string(7) "student" ["first_name"]=> string(7) "Student" ["last_name"]=> string(4) "Satu" ["contact_number"]=> string(3) "000" ["address"]=> string(4) "No 1" }
 */

$id = (int) $_GET['id'];

require_once 'database_util.php';
global $conn;

// update sql
$update = <<<EOL
    UPDATE users
       SET username = ?
         , user_type = ?
         , first_name = ?
         , last_name = ?
         , contact_number = ?
         , address = ?
     WHERE id = ?
EOL;

$stmt = mysqli_prepare($conn, $update);
mysqli_stmt_bind_param(
    $stmt,
    'ssssssi',
    $_POST['username'],
    $_POST['user_type'],
    $_POST['first_name'],
    $_POST['last_name'],
    $_POST['contact_number'],
    $_POST['address'],
    $id
);

mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

to_url('user_profile_show.php?id=' . $id);


?>
<div>
    <?php var_dump($_GET); ?>
</div>
<div>
    <?php var_dump($_POST); ?>
</div>
