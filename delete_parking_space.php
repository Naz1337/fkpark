<?php
require_once 'database_util.php'; // Include the database connection file

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the ID is set and is a valid integer
    if (isset($_POST['id']) && is_numeric($_POST['id'])) {
        // Prepare and execute the SQL DELETE statement
        $stmt = $conn->prepare("DELETE FROM parking_spaces WHERE id = ?");
        $stmt->bind_param("i", $_POST['id']);
        
        if ($stmt->execute()) {
            // Deletion successful
            echo "<script>alert('Parking space deleted successfully!');</script>";
        } else {
            // Error in deletion
            echo "<script>alert('Error: Unable to delete parking space.');</script>";
        }
        
        // Close the statement
        $stmt->close();
    } else {
        // Invalid ID
        echo "<script>alert('Error: Invalid parking space ID.');</script>";
    }
}

// Redirect back to the previous page
echo "<script>window.location.href = 'parking_zones.php';</script>";
?>
