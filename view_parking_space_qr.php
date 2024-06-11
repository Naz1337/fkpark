<?php

require_once 'layout_top.php';
require_once 'database_util.php'; // Include the database connection file

?>

<style>
    .btn-primary:hover {
        color: #fff;
        background-color: #4caf50;
        border-color: #269abc;
    }

    .btn-secondary:hover {
        background-color: #dc3545;
        /* Red color */
        border-color: #dc3545;
        /* Red color */
    }

    .table th {
        width: 200px; /* Adjust the width as needed */
    }
</style>

<h1>View Parking Space</h1>

<br>

<?php
// Check if an ID is provided in the URL
if (isset($_GET['id'])) {
    // Fetch parking space details based on the provided ID
    $id = $_GET['id'];
    $stmt = $conn->prepare("
        SELECT 
            ps.id AS space_id, 
            ps.name AS space_name, 
            ps.is_available, 
            pz.name AS zone_name, 
            pz.status AS zone_status
        FROM 
            parking_spaces ps
        JOIN 
            parking_zones pz 
        ON 
            ps.zone_id = pz.id
        WHERE 
            ps.id = ?
    ");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Display parking space details if found
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        echo "<table class='table table-bordered'>";
        echo "<tbody>";

        echo "<tr>";
        echo "<th>Parking Space Name:</th>";
        echo "<td>" . htmlspecialchars($row['space_name']) . "</td>";
        echo "</tr>";

        echo "<tr>";
        echo "<th>Parking Zone Name:</th>";
        echo "<td>" . htmlspecialchars($row['zone_name']) . "</td>";
        echo "</tr>";

        echo "<tr>";
        echo "<th>Availability:</th>";
        echo "<td>" . ($row['is_available'] == 1 ? "Available" : "Not Available") . "</td>";
        echo "</tr>";
        
        echo "<tr>";
        echo "<th>Status:</th>";
        echo "<td>" . htmlspecialchars($row['zone_status']) . "</td>";
        echo "</tr>";

        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<p>No parking space found with the provided ID.</p>";
    }

    $stmt->close();
} else {
    echo "<p>No ID provided in the URL.</p>";
}
?>

<?php
require_once 'layout_bottom.php';
$conn->close();
?>
