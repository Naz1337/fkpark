<?php
require_once 'layout_top.php';
require_once 'database_util.php'; // Include the database connection file

if (!isset($_SESSION['username'])) {
    header('location:login.php');
    return;
}

// Check if an ID is provided in the URL
if (isset($_GET['id'])) {
    $zone_id = $_GET['id'];
    // Fetch the parking zone data
    $stmt = $conn->prepare("SELECT name, status FROM parking_zones WHERE id = ?");
    $stmt->bind_param("i", $zone_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $zone_data = $result->fetch_assoc();
    $stmt->close();
} else {
    // Redirect to parking_zones.php if no ID is provided
    header('location:parking_zones.php');
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update the parking zone data
    $zoneName = $_POST['zone_name'];
    $status = $_POST['status'];

    $stmt_update = $conn->prepare("UPDATE parking_zones SET name = ?, status = ? WHERE id = ?");
    $stmt_update->bind_param("ssi", $zoneName, $status, $zone_id);

    if ($stmt_update->execute()) {
        echo "<div class='alert alert-success' role='alert'>Record updated successfully!</div>";
        // Redirect back to parking zones page
        header('location:parking_zones.php');
        exit();
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error updating record: " . $conn->error . "</div>";
    }
    $stmt_update->close();
}
?>

<h1>Edit Parking Zone</h1>

<div style="margin-bottom: 20px;">
    <a href="parking_zones.php" class="btn btn-primary">Back</a>
</div>

<p><b>Please update the form below:</b></p>

<form action="update_parking_zones.php" method="post">
    <input type="hidden" name="id" value="<?php echo $zone_id; ?>">
    <table class="table">
        <tbody>
            <tr style="margin-bottom: 10px;">
                <td><label for="zone_name">Parking Zone Name:</label></td>
                <td><input type="text" class="form-control" id="zone_name" name="zone_name" required
                        placeholder="Enter Parking Zone Name" value="<?php echo $zone_data['name']; ?>"></td>
            </tr>
            <tr style="margin-bottom: 10px;">
                <td><label for="status">Status:</label></td>
                <td>
                    <select class="form-select" id="status" name="status" required>
                        <option value="Open" <?php if ($zone_data['status'] == 'Open')
                            echo 'selected'; ?>>Open</option>
                        <option value="Closed" <?php if ($zone_data['status'] == 'Closed')
                            echo 'selected'; ?>>Closed
                        </option>
                        <option value="Under maintenance" <?php if ($zone_data['status'] == 'Under maintenance')
                            echo 'selected'; ?>>Under Maintenance</option>
                    </select>
                </td>
            </tr>
        </tbody>
    </table>
    <button type="submit" class="btn btn-primary">Save</button>
    <button type="reset" class="btn btn-primary">Reset</button>
    <a href="parking_zones.php" class="btn btn-secondary">Cancel</a>
</form>

<?php
require_once 'layout_bottom.php';
$conn->close();
?>