<?php
session_start();

require_once 'layout_top.php';
require_once 'database_util.php'; // Include the database connection file

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Fetch summons from the database
$stmt = $conn->prepare("SELECT id, vehicle_id, summon_date, qr_code, username, violation_type, merit_points FROM summons");
$stmt->execute();
$result = $stmt->get_result();
$summons = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Function to generate random summon ID
function generateSummonID($id) {
    return "SM-FKB321-" . date('y') . "-" . str_pad($id, 6, '0', STR_PAD_LEFT);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Accident Report</title>
    <style>
        table {
            border-collapse: collapse;
            width: auto;
        }

        th, td {
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
    <script>
        window.onload = function() {
            var message = "<?php echo $message; ?>";
            if (message) {
                alert(message);
            }
        }
    </script>
</head>
<body>

<h1>Traffic Summon Records</h1>

<table>
    <thead>
        <tr>
            <th>No.</th>
            <th>Summon ID</th>
            <th>QR Code</th>
            <th>Date and Time</th>
            <th>Reasons</th>
            <th>Merit Points</th>
            <th>Details</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($summons) > 0): ?>
            <?php foreach ($summons as $index => $summon): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo generateSummonID($summon['id']); ?></td>
                    <td>
                        <a href="traffic_summon_details.php?summon_id=<?php echo urlencode($summon['id']); ?>">
                            <img src="traffic_summon_qr.php?text=<?php echo urlencode(generateSummonID($summon['id'])); ?>" alt="QR Code" width="50">
                        </a>
                    </td>
                    <td><?php echo htmlspecialchars($summon['summon_date']); ?></td>
                    <td><?php echo htmlspecialchars($summon['violation_type']); ?></td>
                    <td><?php echo htmlspecialchars($summon['merit_points']); ?></td>
                    <td>
                        <a href="traffic_summon_details.php?summon_id=<?php echo htmlspecialchars($summon['id']); ?>" class="btn">More</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">No records found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<p>
    <button type="button" class="btn btn-primary" onclick="location.href='traffic_enforcement.php'">Total Summon Enforcement</button>
    <button type="button" class="btn btn-danger" onclick="location.href='accident_report.php'">Accident Report</button>
</p>

<button type="button" class="btn btn-secondary" onclick="history.back();">Back</button>

<?php
require_once 'layout_bottom.php';
$conn->close();
?>

</body>
</html>
