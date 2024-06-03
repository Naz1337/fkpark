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
        // Update successful
        header("Location: parking_zones.php"); // Redirect back to parking zones page
        exit();
    } else {
        // Update failed
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
} else {
    // Invalid request
    echo "Invalid request";
}
?>
