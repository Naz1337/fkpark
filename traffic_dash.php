<?php
session_start();

require_once 'layout_top.php';
require_once 'database_util.php'; // Include the database connection file

// Function to get user type from session
function get_user_type() {
    return $_SESSION['user_type'] ?? null;
}

if (!isset($_SESSION['username']) || !in_array(get_user_type(), ['admin', 'staff'])) {
    header('location:login.php');
    return();
}

// Fetch summon data
$stmt = $conn->prepare("SELECT SUM(points) AS total_points, COUNT(*) AS total_students, SUM(parking_violation) AS parking_violation, SUM(accident_caused) AS accident_caused, SUM(traffic_regulation) AS traffic_regulation FROM summons");
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Unit Keselamatan Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1a1a1a;
            color: #fff;
        }

        .container {
            width: 80%;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
        }

        p {
            margin: 5px 0;
        }

        .summary {
            margin: 20px;
            text-align: center;
        }

        .summary p {
            margin: 10px 0;
        }

        .buttons {
            text-align: center;
            margin-top: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
        }

        .btn-primary {
            background-color: #0275d8;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #025aa5;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: #fff;
        }

        .btn-secondary:hover {
            background-color: #545b62;
        }

        .btn-danger {
            background-color: #d9534f;
            color: #fff;
        }

        .btn-danger:hover {
            background-color: #c9302c;
        }

        .btn-back {
            margin-top: 20px;
            text-align: center;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="container">
    <h1>Unit Keselamatan Dashboard</h1>
    <p>Hello, <?php echo ucfirst(get_user_type()); ?></p>
    <p>AD21001</p>
    <p>Date: <?php echo date('d/m/Y'); ?></p>

    <div class="summary">
        <p>Total Summon Point: <?php echo $data['total_points']; ?></p>
        <p>Total Student Summoned: <?php echo $data['total_students']; ?></p>
        <p>Parking Violation: <?php echo $data['parking_violation']; ?></p>
        <p>Accident Caused: <?php echo $data['accident_caused']; ?></p>
        <p>Campus Traffic Regulations: <?php echo $data['traffic_regulation']; ?></p>
    </div>

    <div class="piechart-container" style="display: flex; justify-content: center; align-items: center; height: 400px; margin: 20px 0;">
        <canvas id="pieChart" width="400" height="400"></canvas>
    </div>

    <div class="buttons">
        <button class="btn btn-primary" onclick="location.href='traffic_summon.php'">Edit</button>
        <button class="btn btn-secondary" onclick="location.href='accident_report.php'">Cancel</button>
    </div>

    <div class="btn-back">
        <button class="btn btn-danger" onclick="history.back();">Back</button>
    </div>
</div>

<?php
require_once 'layout_bottom.php';
$conn->close();
?>

<script>
    const data = {
        labels: ['Parking Violation', 'Accident Caused', 'Traffic Regulation'],
        datasets: [{
            data: [<?php echo $data['parking_violation']; ?>, <?php echo $data['accident_caused']; ?>, <?php echo $data['traffic_regulation']; ?>],
            backgroundColor: ['#4caf50', '#f44336', '#ff9800'],
        }]
    };

    const config = {
        type: 'pie',
        data: data,
    };

    const pieChart = new Chart(
        document.getElementById('pieChart'),
        config
    );
</script>

</body>
</html>

