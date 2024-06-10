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

    .action-buttons {
        margin-top: 5px;
    }

    .edit-delete-buttons form {
        display: inline;
        margin-right: 10px;
    }

    .view-button {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #fff;
    }

    .view-button:hover {
        background-color: #e0a800;
        border-color: #d39e00;
    }
</style>

<h1>Traffic Summon</h1>
<p>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?><br>
CD21052<br>
Total Summon Points: 10</p>

<h2>Traffic Summon Record</h2>

<table border="1" class="table table-bordered">
    <thead>
        <tr>
            <th>No.</th>
            <th>Summon ID</th>
            <th>QR Code</th>
            <th>Date & Time</th>
            <th>Reason</th>
            <th>Details</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $stmt = $conn->prepare("SELECT * FROM summons ORDER BY id DESC");
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $counter = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $counter++ . "</td>";
                echo "<td>" . htmlspecialchars($row['summon_id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['qr_code']) . "</td>";
                echo "<td>" . htmlspecialchars($row['summon_date']) . "</td>";
                echo "<td>" . htmlspecialchars($row['violation_type']) . "</td>";
                echo "<td>";
                echo "<a href='view_summon.php?id=" . $row['id'] . "' class='btn btn-secondary view-button'>More</a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No data available</td></tr>";
        }
        $stmt->close();
        ?>
    </tbody>
</table>

<p>
    <button type="button" class="btn btn-primary" onclick="location.href='total_summon_enforcement.php'">Total Summon Enforcement</button>
    <button type="button" class="btn btn-secondary" onclick="location.href='learn_more.php'">Learn more</button>
    <button type="button" class="btn btn-danger" onclick="location.href='accident_report.php'">Accident Report</button>
</p>

<button type="button" class="btn btn-secondary" onclick="history.back();">Back</button>

<?php
require_once 'layout_bottom.php';
$conn->close();
?>
