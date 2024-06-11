<?php

require_once 'layout_top.php';
require_once 'database_util.php'; // Include the database connection file

if (!isset($_SESSION['username'])) {
    header('location:login.php');
    return;
}

?>

<?php
// Fetching traffic summon data
$stmt = $conn->prepare("
    SELECT 
        SUM(summon_points) AS total_summon_points, 
        COUNT(DISTINCT student_id) AS total_students_summoned, 
        SUM(CASE WHEN violation_type = 'Parking violation' THEN 1 ELSE 0 END) AS parking_violations,
        SUM(CASE WHEN violation_type = 'Accident' THEN 1 ELSE 0 END) AS accidents_caused,
        SUM(CASE WHEN violation_type = 'Campus traffic regulations' THEN 1 ELSE 0 END) AS campus_traffic_regulations
    FROM traffic_summons
");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();
?>

<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script>
    window.onload = function () {
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
                dataPoints: [
                    { label: "Parking Violation", y: <?php echo $row['parking_violations']; ?> },
                    { label: "Accident Caused", y: <?php echo $row['accidents_caused']; ?> },
                    { label: "Campus Traffic Regulations", y: <?php echo $row['campus_traffic_regulations']; ?> }
                ]
            }]
        });
        chart.render();
    }
</script>

<style>
    body {
        background-color: #2c3e50;
        color: #ecf0f1;
        font-family: Arial, sans-serif;
    }
    
    .summary-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin: 20px 0;
    }

    .summary-card {
        background-color: #34495e;
        color: #ecf0f1;
        border: 1px solid #2c3e50;
        border-radius: 5px;
        width: 50%;
        margin: 10px;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    }

    .summary-card h3 {
        margin-top: 0;
    }

    .summary-card p {
        margin: 10px 0;
    }

    .btn-primary, .btn-secondary {
        color: #fff;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
    }

    .btn-primary {
        background-color: #3498db;
    }

    .btn-secondary {
        background-color: #e74c3c;
    }

    .btn-primary:hover {
        background-color: #2980b9;
    }

    .btn-secondary:hover {
        background-color: #c0392b;
    }

    .container2 {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }

    .container2 a {
        margin: 0 10px;
    }
</style>

<h1>Traffic Summon Summary</h1>

<div class="summary-container">
    <div id="chartContainer" style="height: 370px; width: 100%;"></div>
    <div class="summary-card">
        <p>Total Summon Point: <?php echo $row['total_summon_points']; ?></p>
        <p>Total Student Summoned: <?php echo $row['total_students_summoned']; ?></p>
        <p>Parking violation: <?php echo $row['parking_violations']; ?></p>
        <p>Accident caused: <?php echo $row['accidents_caused']; ?></p>
        <p>Campus traffic regulations: <?php echo $row['campus_traffic_regulations']; ?></p>
    </div>
    <div class="container2">
        <a href="edit_summon.php" class="btn btn-primary">Edit</a>
        <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
    </div>
</div>

<?php
require_once 'layout_bottom.php';
$conn->close();
?>
