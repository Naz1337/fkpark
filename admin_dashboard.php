<?php

require_once 'layout_top.php';
require_once 'database_util.php'; // Include the database connection file

?>

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
</style>

<h1>Administrator Dashboard</h1>

<div class="dashboard-container">
    <!-- Parking Zones Summary -->
    <div class="dashboard-card">
        <h3>Parking Zones</h3>
        <?php
        $stmt = $conn->prepare("
            SELECT 
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
        <a href="add_parking_space.php" class="btn btn-primary">Add Parking Spaces</a>
    </div>
</div>
<div class="piechart">

</div>

<?php
require_once 'layout_bottom.php';
$conn->close();
?>
