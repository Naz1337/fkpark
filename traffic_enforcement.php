<?php

require_once 'layout_top.php';
require_once 'database_util.php'; // Include the database connection file

if (!isset($_SESSION['username'])) {
    header('location:login.php');
    return;
}

?>

<style>
    /* Existing CSS styles */
    table {
        border-collapse: collapse;
        width: 100%;
    }

    th,
    td {
        padding: 8px;
        text-align: left;
    }

    .btn {
        display: inline-block;
        padding: 6px 12px;
        margin-bottom: 0;
        font-size: 14px;
        font-weight: normal;
        line-height: 1.42857143;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        cursor: pointer;
        border: 1px solid transparent;
        border-radius: 4px;
    }

    .btn-primary {
        color: #fff;
        background-color: #0275d8;
        border-color: #0275d8;
    }

    .btn-primary:hover {
        background-color: #025aa5;
        border-color: #025aa5;
    }

    .btn-secondary {
        color: #fff;
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
    }

    .btn-danger {
        color: #fff;
        background-color: #d9534f;
        border-color: #d43f3a;
    }

    .btn-danger:hover {
        background-color: #c9302c;
        border-color: #ac2925;
    }
</style>

<h1>Traffic Summon</h1>
<p>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?></p>

<h2>Total Summon Enforcement</h2>

<table border="1" class="table table-bordered">
    <thead>
        <tr>
            <th>Total point</th>
            <th>Enforcement type</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Less than 20 points</td>
            <td>Warning given</td>
        </tr>
        <tr>
            <td>Less than 50 points</td>
            <td>Revoke of in campus vehicle permission for 1 semester</td>
        </tr>
        <tr>
            <td>Less than 80 points</td>
            <td>Revoke of in campus vehicle permission for 2 semesters</td>
        </tr>
        <tr>
            <td>More than 80 points</td>
            <td>Revoke of in campus vehicle permission for the entire study duration</td>
        </tr>
    </tbody>
</table>

<p>Each summon points: (10) for parking violation, (15) for not comply in campus traffic regulations, and (20) for accident caused.</p>

<button type="button" class="btn btn-secondary" onclick="history.back();">Back</button>

<?php
require_once 'layout_bottom.php';
$conn->close();
?>
