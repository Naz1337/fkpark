<?php

require_once 'layout_top.php';
require_once 'database_util.php'; // Include the database connection file

if (!isset($_SESSION['username'])) {
    header('location:login.php');
    return;
}

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
</style>

<h1>Add Parking Zone</h1>

<div style="margin-bottom: 20px;">
    <a href="parking_zones.php" class="btn btn-primary">Back</a>
</div>

<p><b>Please fill in the form below:</b></p>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $zoneName = $_POST['zone_name'];
    $status = $_POST['status'];

    // SQL query to insert form data into the database
    $sql = "INSERT INTO parking_zones (name, status) VALUES (?, ?)";

    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("ss", $zoneName, $status);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<div class='alert alert-success' role='alert'>Record added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }

    // Close the statement
    $stmt->close();
}
?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <table class="table">
        <tbody>
            <tr style="margin-bottom: 10px;">
                <td><label for="zone_name">Parking Zone Name:</label></td>
                <td><input type="text" class="form-control" id="zone_name" name="zone_name" required
                        placeholder="Enter Parking Zone Name"></td>
            </tr>
            <tr style="margin-bottom: 10px;">
                <td><label for="status">Status:</label></td>
                <td>
                    <select class="form-select" id="status" name="status" required>
                        <option value="" selected disabled>Select Status</option>
                        <option value="Open">Open</option>
                        <option value="Closed">Closed</option>
                        <option value="Under maintenance">Under Maintenance</option>
                    </select>
                </td>
            </tr>
        </tbody>
    </table>
    <button type="submit" class="btn btn-primary">Submit</button>
    <button type="reset" class="btn btn-primary">Reset</button>
    <a href="parking_zones.php" class="btn btn-secondary">Cancel</a>
</form>

<?php
require_once 'layout_bottom.php';
$conn->close();
?>