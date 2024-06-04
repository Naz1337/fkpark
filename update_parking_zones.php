<?php
require_once 'database_util.php'; // Include the database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && isset($_POST['status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    // Update parking zone status
    $sql = "UPDATE parking_zones SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        // Determine the availability based on the status
        if ($status === 'Open') {
            $availability = 1; // Available
        } else {
            $availability = 0; // Not Available
        }

        // Update the availability of parking spaces within this zone
        $sql_update_spaces = "UPDATE parking_spaces SET is_available = ? WHERE zone_id = ?";
        $stmt_update_spaces = $conn->prepare($sql_update_spaces);
        $stmt_update_spaces->bind_param("ii", $availability, $id);

        if ($stmt_update_spaces->execute()) {
            // Update successful
            header("Location: parking_zones.php"); // Redirect back to parking zones page
            exit();
        } else {
            // Update of parking spaces failed
            echo "Error updating parking spaces: " . $conn->error;
        }

        $stmt_update_spaces->close();
    } else {
        // Update of parking zone failed
        echo "Error updating parking zone: " . $conn->error;
    }

    $stmt->close();
} else {
    // Invalid request
    echo "Invalid request";
}

$conn->close();
?>