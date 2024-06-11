<<?php

require_once 'layout_top.php';
require_once 'database_util.php'; // Include the database connection file

if (!isset($_SESSION['username'])) {
    header('location:login.php');
    return;
}

?>

<!DOCTYPE html>
<html>
<head>
    <style>
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

        .view-button {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #fff;
        }

        .view-button:hover {
            background-color: #e0a800;
            border-color: #d39e00;
        }

        .nav-button {
            display: flex;
            align-items: center;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            text-decoration: none;
            color: #000;
            margin: 10px;
        }

        .nav-button i {
            margin-right: 8px;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .fw-bold {
            font-weight: bold;
        }

        .mb-2 {
            margin-bottom: 0.5rem;
        }

        .mt-5 {
            margin-top: 3rem;
        }

        .mx-3 {
            margin-left: 1rem;
            margin-right: 1rem;
        }

        .dashboard-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin: 20px 0;
        }

        .dashboard-card {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            width: 30%;
            margin: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .dashboard-card h3 {
            margin-top: 0;
        }

        .dashboard-card p {
            margin: 10px 0;
        }

        .container2 {
            display: flex;
            justify-content: center;
        }

        .piechart {
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background-image: conic-gradient(pink 70deg, lightblue 0 235deg, orange 0);
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script>
        window.onload = function () {
            var summonData = [
                { label: "Parking violation", y: 12 },
                { label: "Accident caused", y: 3 },
                { label: "Campus traffic regulations", y: 10 }
            ];

            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                title: {
                    text: "Traffic Summon Summary"
                },
                data: [{
                    type: "pie",
                    startAngle: 240,
                    yValueFormatString: "##0.00\"%\"",
                    indexLabel: "{label} {y}",
                    dataPoints: summonData
                }]
            });
            chart.render();
        }
    </script>
</head>
<body>

<h6 class="text-uppercase fw-bold mb-2 mt-5 mx-3">Traffic Summon</h6>

<a class="nav-button btn" href="traffic_dashboard.php">
    <i class="bi bi-arrow-right"></i>
    <div>Dashboard</div>
</a>

<a class="nav-button btn" href="trafic_summon.php">
    <i class="bi bi-arrow-right"></i>
    <div>Traffic Summon Record</div>
</a>

<a class="nav-button btn" href="accident_report.php">
    <i class="bi bi-arrow-right"></i>
    <div>Accident Report</div>
</a>

<h1>Administrator Dashboard</h1>

<div class="dashboard-container">
    <!-- Traffic Summon Summary -->
    <div class="dashboard-card">
        <h3>Traffic Summon Summary</h3>
        <div id="chartContainer" style="height: 300px; width: 100%;"></div>
        <p>Total Summon Point: 350</p>
        <p>Total Student Summoned: 25</p>
        <p>Parking violation: 12</p>
        <p>Accident caused: 3</p>
        <p>Campus traffic regulations: 10</p>
        <a href="traffic_summon_summary.php" class="btn btn-primary">Edit</a>
        <a href="cancel_summary.php" class="btn btn-secondary">Cancel</a>
    </div>
</div>

<?php
require_once 'layout_bottom.php';
$conn->close();
?>

</body>
</html>
