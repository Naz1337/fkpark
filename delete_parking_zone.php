<?php

require_once 'database_util.php'; // Include the database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Delete related rows in parking_spaces
        $sql1 = "DELETE FROM parking_spaces WHERE zone_id = ?";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param("i", $id);
        $stmt1->execute();
        $stmt1->close();

        // Delete the parking zone
        $sql2 = "DELETE FROM parking_zones WHERE id = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("i", $id);
        $stmt2->execute();
        $stmt2->close();

        // Commit transaction
        $conn->commit();

        // Redirect back to parking_zones.php after successful deletion
        header("Location: parking_zones.php");
    } catch (mysqli_sql_exception $exception) {
        // Rollback transaction on error
        $conn->rollback();

        echo "Error: " . $exception->getMessage();
    }
}

// Close the database connection
$conn->close();

?>