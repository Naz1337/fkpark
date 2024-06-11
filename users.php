<?php

require_once  'bootstrap.php';

$select = 'SELECT * FROM users';
require_once 'database_util.php';
global $conn;

$stmt = mysqli_prepare($conn, $select);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

require_once 'layout_top.php';
?>

<div class="container">
    <h1 class="mb-4">Users</h1>
    <div class="d-flex gap-3 align-items-between mb-4">
        <form action="">
            <div class="input-group">
                <input type="text" name="query" id="search" class="form-control" placeholder="Search">
                <button class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
            </div>
        </form>
        <a href="temporary_register.php" class="btn btn-outline-secondary">Create User</a>
    </div>
    <table class="table">
        <thead>
        <tr>
            <th>User Type</th>
            <th>Username</th>
            <th>Full Name</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><span class="badge bg-primary"><?= ucwords($row['user_type']) ?></span></td>
                <td><?= $row['username'] ?></td>
                <td><?= $row['first_name'] . ' ' . $row['last_name'] ?></td>
                <td>
                    <a href="user_profile_show.php?id=<?= $row['id'] ?>" class="btn btn-outline-primary">View</a>
                    <a href="user_edit.php?id=<?= $row['id'] ?>" class="btn btn-outline-secondary">Edit</a>
                    <a href="user_delete.php?id=<?= $row['id'] ?>" class="btn btn-outline-danger">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php
require_once 'layout_bottom.php';

