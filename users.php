<?php

require_once  'bootstrap.php';
require_once 'database_util.php';
global $conn;

if (isset($_GET['query'])) {
    $query = '%' . $_GET['query'] . '%';
    $select = 'SELECT * FROM users WHERE username LIKE ? OR first_name LIKE ? OR last_name LIKE ?';
    $stmt = mysqli_prepare($conn, $select);
    mysqli_stmt_bind_param($stmt, 'sss', $query, $query, $query);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    // https://www.interviewquery.com/p/sql-count-case-when
    $select = <<<'SQL'
SELECT users.*, 
       COUNT(CASE WHEN vehicles.approval_status = 0 THEN 1 ELSE NULL END) AS unapproved_cars_count
FROM users
LEFT JOIN vehicles ON users.id = vehicles.user_id
GROUP BY users.id
ORDER BY unapproved_cars_count DESC;
SQL;
    $stmt = mysqli_prepare($conn, $select);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
}

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
                <td class="py-3"><span class="badge bg-primary"><?= ucwords($row['user_type']) ?></span></td>
                <td class="py-3"><?= $row['username'] ?></td>
                <td class="py-3"><?= $row['first_name'] . ' ' . $row['last_name'] ?></td>
                <td class="py-3">
                    <div class="d-flex gap-2">
                        <div>
                            <a href="vehicles.php?user_id=<?= $row['id'] ?>" class="btn btn-primary position-relative">
                                View Vehicles
                                <?php if ($row['unapproved_cars_count'] > 0): ?>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?= $row['unapproved_cars_count'] ?></span>
                                <?php endif; ?>
                            </a>
                        </div>
                        <a href="user_profile_show.php?id=<?= $row['id'] ?>" class="btn btn-outline-primary">View</a>
                        <a href="user_edit.php?id=<?= $row['id'] ?>" class="btn btn-outline-secondary">Edit</a>
                        <a href="user_delete.php?id=<?= $row['id'] ?>" class="btn btn-outline-danger">Delete</a>
                    </div>

                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php
require_once 'layout_bottom.php';

