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

<link rel="stylesheet" href="styles/module2/admin_dashboard.css">

<h1>Parking Information</h1>

<div class="dashboard-container">
    <!-- Parking Zones Summary -->
    <div class="dashboard-card">
        <h3>Parking Zones</h3>
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
        <a href="parking_zones.php" class="btn btn-primary">Manage Parking Zones</a>
    </div>

    <!-- Parking Spaces Summary -->
    <div class="dashboard-card">
        <h3>Parking Spaces</h3>
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
        <a href="parking_spaces.php" class="btn btn-primary">Manage Parking Space</a>
    </div>
</div>

<?php
    vite_asset('js/admin_parking_dashboard.js');
    vite_asset('js/admin_parking_status_dashboard.js');
?>

<div class="row">
    <div class="col-6 mx-auto">
        <div class="card">
            <div class="card-body">
                <div id="parkingPie"></div>
            </div>
            <div class="card-body">
                <div id="parkingStatus"></div>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'layout_bottom.php';
$conn->close();
?>