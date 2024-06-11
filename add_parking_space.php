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

<h1>Add Parking Space</h1>

<div style="margin-bottom: 20px;">
    <a href="parking_spaces.php" class="btn btn-primary">Back</a>
</div>

<p><b>Please fill in the form below:</b></p>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $zone_id = $_POST['zone_id']; // Change to match your form field name
    $spaceName = $_POST['spaceName'];
    $availability = $_POST['is_available']; // Change to match your form field name
    $area = substr($spaceName, -1); // Extract the last character

    // SQL query to insert form data into the database
    $sql = "INSERT INTO parking_spaces (zone_id, name, is_available, area, qr_code) VALUES (?, ?, ?, ?, '')";

    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("isss", $zone_id, $spaceName, $availability, $area);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<div class='alert alert-success' role='alert'>Record added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }

    // Close the statement
    $stmt->close();
}

// Fetch parking zones from the database
$sql = "SELECT id, name FROM parking_zones";
$result = $conn->query($sql);
$parking_zones = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $parking_zones[] = $row;
    }
}
?>

<!-- HTML Form with Bootstrap classes -->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <table class="table">
        <tbody>
            <tr style="margin-bottom: 10px;">
                <td><label for="zone_id">Parking Zone Name:</label></td>
                <td>
                    <select class="form-select" id="zone_id" name="zone_id" required>
                        <option value="" selected disabled>Select Parking Zone</option>
                        <?php foreach ($parking_zones as $zone): ?>
                            <option value="<?php echo htmlspecialchars($zone['id']); ?>">
                                <?php echo htmlspecialchars($zone['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr style="margin-bottom: 10px;">
                <td><label for="spaceName">Parking Space Name:</label></td>
                <td><input type="text" class="form-control" id="spaceName" name="spaceName" required
                        placeholder="Eg. A1-1"></td>
            </tr>
            <tr style="margin-bottom: 10px;">
                <td><label for="is_available">Availability:</label></td>
                <td>
                    <select class="form-select" id="is_available" name="is_available" required>
                        <option value="" selected disabled>Select Availability</option>
                        <option value="1">Available</option>
                        <option value="0">Not Available</option>
                    </select>
                </td>
            </tr>
        </tbody>
    </table>
    <button type="submit" class="btn btn-primary">Submit</button>
    <button type="reset" class="btn btn-primary">Reset</button>
    <a href="parking_spaces.php" class="btn btn-secondary">Cancel</a>
</form>

<?php
require_once 'layout_bottom.php';
$conn->close();
?>