<?php

require_once 'layout_top.php';
require_once 'database_util.php'; // Include the database connection file

if (!isset($_SESSION['username'])) {
    header('location:login.php');
    return;
}

?>

<?php
// Fetching available spaces count
$stmt = $conn->prepare("SELECT COUNT(*) AS available_spaces FROM parking_spaces WHERE is_available = 1");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$available_spaces = $row['available_spaces'];
$stmt->close();

// Fetching not available spaces count
$stmt = $conn->prepare("SELECT COUNT(*) AS not_available_spaces FROM parking_spaces WHERE is_available != 1");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$not_available_spaces = $row['not_available_spaces'];
$stmt->close();
?>

<script>
    window.onload = function () {
        var availableSpaces = <?php echo $available_spaces; ?>;
        var notAvailableSpaces = <?php echo $not_available_spaces; ?>;

        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            title: {
                text: "Parking Space Status"
            },
            data: [{
                type: "pie",
                startAngle: 240,
                yValueFormatString: "##0.00\"%\"",
                indexLabel: "{label} {y}",
                dataPoints: [
                    { label: "Available", y: availableSpaces },
                    { label: "Not Available", y: notAvailableSpaces }
                ]
            }]
        });
        chart.render();
    }
</script>

<style>
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

    .btn-primary:hover {
        color: #fff;
        background-color: #4caf50;
        border-color: #269abc;
    }

    .btn-secondary:hover {
        background-color: #dc3545;
        border-color: #dc3545;
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

<h1>Administrator Dashboard</h1>

<div class="dashboard-container">
    <!-- Total Summon Counter Summary -->
    <div class="dashboard-card">
        <h3>Total Summon</h3>
        <?php
        $stmt = $conn->prepare("
            SELECT 
                COUNT(*) AS total_zones,
                SUM(CASE WHEN status = 'Open' THEN 1 ELSE 0 END) AS open_zones,
                SUM(CASE WHEN status = 'Closed' THEN 1 ELSE 0 END) AS closed_zones,
                SUM(CASE WHEN status = 'Under maintenance' THEN 1 ELSE 0 END) AS maintenance_zones
            FROM parking_zones
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        ?>
        <p>Total Zones: <?php echo $row['total_zones']; ?></p>
        <p>Open: <?php echo $row['open_zones']; ?></p>
        <p>Closed: <?php echo $row['closed_zones']; ?></p>
        <p>Under Maintenance: <?php echo $row['maintenance_zones']; ?></p>
        <a href="traffic_summon.php" class="btn btn-primary">Manage Traffic Summon</a>
    </div>

    <!-- Summon Type Summary -->
    <div class="dashboard-card">
        <h3>Total Summon Type</h3>
        <p>Total Spaces:
            <?php
            $stmt = $conn->prepare("SELECT COUNT(*) AS total_spaces FROM parking_spaces");
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            echo $row['total_spaces'];
            $stmt->close();
            ?>
        </p>
        <p>Available:
            <?php
            $stmt = $conn->prepare("SELECT COUNT(*) AS available_spaces FROM parking_spaces WHERE is_available = 1");
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            echo $row['available_spaces'];
            $stmt->close();
            ?>
        </p>
        <p>Not Available:
            <?php
            $stmt = $conn->prepare("SELECT COUNT(*) AS not_available_spaces FROM parking_spaces WHERE is_available != 1");
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            echo $row['not_available_spaces'];
            $stmt->close();
            ?>
        </p>
        <a href="accident_report.php" class="btn btn-primary">Manage Accident Report</a>
    </div>
</div>

<div class="container2">
    <div class="piechart"></div>
</div>



<?php
require_once 'layout_bottom.php';
$conn->close();
?>