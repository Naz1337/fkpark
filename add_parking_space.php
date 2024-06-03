<?php

require_once 'layout_top.php';
require_once 'database_util.php'; // Include the database connection file

?>

<h1>Add Parking Space</h1>

<div style="margin-bottom: 20px;">
    <a href="javascript:history.back()" class="btn btn-primary">Back</a>
</div>

<p><b>Please fill in the form below:</b></p>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $zone = $_POST['zone'];
    $spaceName = $_POST['spaceName'];
    $status = $_POST['status'];
    $availability = $_POST['is_available'];

    // SQL query to insert form data into the database
    $sql = "INSERT INTO parking_spaces (zone, name, status, is_available) VALUES (?, ?, ?, ?)";

    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("sssi", $zone, $spaceName, $status, $availability);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<div class='alert alert-success' role='alert'>Record added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }

    // Close the statement
    $stmt->close();
}

// Array to hold label lengths
$label_lengths = [
    strlen('Parking Zone No.:'),
    strlen('Parking Space Name:'),
    strlen('Status:'),
    strlen('is_available:')
];

// Calculate the maximum label length
$max_label_length = max($label_lengths);

?>

<!-- HTML Form with Bootstrap classes -->
<style>
    /* Custom CSS for button hover effects */
    .btn-primary:hover {
        background-color: #5cb85c;
        border-color: #4cae4c;
    }

    .btn-secondary:hover {
        background-color: #d9534f;
        border-color: #d43f3a;
    }

    .btn-secondary:hover {
        background-color: #d9534f;
        border-color: #d43f3a;
    }
</style>


<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <table class="table">
        <tbody>
            <tr style="margin-bottom: 10px;">
                <td style="width: <?php echo $max_label_length * 10; ?>px;"><label for="zone">Parking Zone No.:</label></td>
                <td><input type="text" class="form-control" id="zone" name="zone" required></td>
            </tr>
            <tr style="margin-bottom: 10px;">
                <td style="width: <?php echo $max_label_length * 10; ?>px;"><label for="spaceName">Parking Space Name:</label></td>
                <td><input type="text" class="form-control" id="spaceName" name="spaceName" required></td>
            </tr>
            <tr style="margin-bottom: 10px;">
                <td style="width: <?php echo $max_label_length * 10; ?>px;"><label for="status">Status:</label></td>
                <td>
                    <select class="form-control" id="status" name="status">
                        <option value="Open">Open</option>
                        <option value="Closed">Closed</option>
                        <option value="Under maintenance">Under maintenance</option>
                    </select>
                </td>
            </tr>
            <tr style="margin-bottom: 10px;">
                <td style="width: <?php echo $max_label_length * 10; ?>px;"><label for="is_available">Availability:</label></td>
                <td>
                    <select class="form-control" id="is_available" name="is_available">
                        <option value="1">Available</option>
                        <option value="0">Not Available</option>
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
