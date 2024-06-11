<?php

require_once 'layout_top.php';
require_once 'database_util.php'; // Include the database connection file

if (!isset($_SESSION['username'])) {
    header('location:login.php');
    return;
}

// Check if ID parameter is provided in the URL
if (isset($_GET['id'])) {
    $space_id = $_GET['id'];

    // Fetch existing parking space data
    $sql_fetch_space = "SELECT zone_id, name, is_available FROM parking_spaces WHERE id = ?";
    $stmt_fetch_space = $conn->prepare($sql_fetch_space);
    $stmt_fetch_space->bind_param("i", $space_id);
    $stmt_fetch_space->execute();
    $result_fetch_space = $stmt_fetch_space->get_result();

    // Check if parking space exists
    if ($result_fetch_space->num_rows > 0) {
        $row_space = $result_fetch_space->fetch_assoc();
        $zone_id = $row_space['zone_id'];
        $spaceName = $row_space['name'];
        $availability = $row_space['is_available'];
    } else {
        // Redirect if parking space not found
        header("Location: parking_zones.php");
        exit();
    }
} else {
    // Redirect if ID parameter is not provided
    header("Location: parking_zones.php");
    exit();
}

// Fetch parking zones from the database
$sql_zones = "SELECT id, name FROM parking_zones";
$result_zones = $conn->query($sql_zones);
$parking_zones = [];

if ($result_zones->num_rows > 0) {
    while ($row_zone = $result_zones->fetch_assoc()) {
        $parking_zones[] = $row_zone;
    }
}

// Update parking space information
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_zone_id = $_POST['zone_id'];
    $new_spaceName = $_POST['spaceName'];
    $new_availability = $_POST['is_available'];

    // Extract the last character for the area
    $area = substr($new_spaceName, -1);

    // SQL query to update parking space data
    $sql_update_space = "UPDATE parking_spaces SET zone_id = ?, name = ?, is_available = ?, area = ? WHERE id = ?";

    // Prepare the SQL statement
    $stmt_update_space = $conn->prepare($sql_update_space);

    // Bind parameters
    $stmt_update_space->bind_param("isssi", $new_zone_id, $new_spaceName, $new_availability, $area, $space_id);

    // Execute the statement
    if ($stmt_update_space->execute()) {
        echo "<div class='alert alert-success' role='alert'>Record updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error updating record: " . $conn->error . "</div>";
    }

    // Close the statement
    $stmt_update_space->close();
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

<h1>Edit Parking Space</h1>

<div style="margin-bottom: 20px;">
    <a href="parking_spaces.php" class="btn btn-primary">Back</a>
</div>

<p><b>Please fill in the form below to edit parking space information:</b></p>

<!-- HTML Form with Bootstrap classes -->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $space_id; ?>" method="post">
    <table class="table">
        <tbody>
            <tr style="margin-bottom: 10px;">
                <td><label for="zone_id">Parking Zone Name:</label></td>
                <td>
                    <select class="form-select" id="zone_id" name="zone_id" required>
                        <option value="" selected disabled>Select Parking Zone</option>
                        <?php foreach ($parking_zones as $zone): ?>
                            <option value="<?php echo htmlspecialchars($zone['id']); ?>" <?php echo ($zone_id == $zone['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($zone['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr style="margin-bottom: 10px;">
                <td><label for="spaceName">Parking Space Name:</label></td>
                <td><input type="text" class="form-control" id="spaceName" name="spaceName" required
                        placeholder="Eg. A1-1" value="<?php echo htmlspecialchars($spaceName); ?>"></td>
            </tr>
            <tr style="margin-bottom: 10px;">
                <td><label for="is_available">Availability:</label></td>
                <td>
                    <select class="form-select" id="is_available" name="is_available" required>
                        <option value="1" <?php echo ($availability == 1) ? 'selected' : ''; ?>>Available</option>
                        <option value="0" <?php echo ($availability == 0) ? 'selected' : ''; ?>>Not Available</option>
                    </select>
                </td>
            </tr>
        </tbody>
    </table>
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="parking_spaces.php" class="btn btn-secondary">Cancel</a>
</form>

<?php
require_once 'layout_bottom.php';
$conn->close();
?>