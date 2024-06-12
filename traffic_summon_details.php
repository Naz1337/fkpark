<?php
session_start();

require_once 'layout_top.php';
require_once 'database_util.php'; // Include the database connection file

if (!isset($_SESSION['username'])) {
    header('location:login.php');
    return;
}

// Assuming you have a way to get the summon details from the database
$summon_id = 1; // This should be dynamically fetched based on user selection or other logic
$query = "SELECT * FROM summons WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $summon_id);
$stmt->execute();
$result = $stmt->get_result();
$summon = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Traffic Summon Details</title>
    <style>
        /* Existing CSS styles */
        table {
            border-collapse: collapse;
            width: auto;
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
</head>

<body>
    <h1>Traffic Summon</h1>
    <p>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?></p>

    <h2>Record Details</h2>

    <table border="1" class="table table-bordered">
        <tbody>
            <tr>
                <td>Student Name:</td>
                <td><?php echo htmlspecialchars($summon['username']); ?></td>
                <td rowspan="6">
                    QR Code:
                    <!-- QR Code generation -->
                    <img src='traffic_summon_qr.php?text=<?php echo urlencode("summon_id=" . $summon_id); ?>'
                        alt="QR Code">
                </td>
            </tr>
            <tr>
                <td>Summon ID:</td>
                <td><?php echo htmlspecialchars($summon['id']); ?></td>
            </tr>
            <tr>
                <td>Vehicle Number:</td>
                <td><?php echo htmlspecialchars($summon['vehicle_id']); ?></td>
            </tr>
            <tr>
                <td>Date & Time:</td>
                <td><?php echo htmlspecialchars($summon['summon_date']); ?></td>
            </tr>
            <tr>
                <td>Reason:</td>
                <td><?php echo htmlspecialchars($summon['violation_type']); ?></td>
            </tr>
            <tr>
                <td>Demerit:</td>
                <td><?php echo htmlspecialchars($summon['merit_points']); ?> Points</td>
            </tr>
            <tr>
                <td>Status:</td>
                <td colspan="2">Warning Given(&gt;20 Points)</td>
            </tr>
        </tbody>
    </table>

    <button type="button" class="btn btn-primary"
        onclick="location.href='update_summon.php?id=<?php echo urlencode($summon_id); ?>'">Update</button>
    <button type="button" class="btn btn-secondary" onclick="location.href='traffic_summon.php'">Cancel</button>

    <?php
    require_once 'layout_bottom.php';
    $conn->close();
    ?>
</body>

</html>