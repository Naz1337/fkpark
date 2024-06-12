<?php

require_once 'layout_top.php';
require_once 'database_util.php'; // Include the database connection file

if (!isset($_SESSION['username'])) {
    header('location:login.php');
    return;
}

require_once 'phpqrcode/qrlib.php'; // Include the QR code library

function generateSummonID($conn) {
    $prefix = "SM-FKB321-24-";
    $sql = "SELECT COUNT(*) as count FROM summons";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $count = $row['count'] + 1;
    return $prefix . str_pad($count, 6, "0", STR_PAD_LEFT);
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
        border-color: #dc3545;
    }
</style>

<h1>Add Traffic Summon</h1>

<div style="margin-bottom: 20px;">
    <a href="summons.php" class="btn btn-primary">Back</a>
</div>

<p><b>Please fill in the form below:</b></p>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $summon_id = generateSummonID($conn);
    $date_time = $_POST['date_time'];
    $summon_type = $_POST['summon_type'];
    $merit_point = $_POST['merit_point'];
    $url = "https://indah.ump.edu.my/CB22159/fkpark/traffic_summon_details.php?id=" . urlencode($summon_id);

    // Generate QR code
    $qr_code_path = "qrcodes/" . $summon_id . ".png";
    QRcode::png($url, $qr_code_path);

    // SQL query to insert form data into the database
    $sql = "INSERT INTO summons (summon_id, date_time, summon_type, merit_point, qr_code) VALUES (?, ?, ?, ?, ?)";

    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("sssis", $summon_id, $date_time, $summon_type, $merit_point, $qr_code_path);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<div class='alert alert-success' role='alert'>Summon added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }

    // Close the statement
    $stmt->close();
}

?>

<!-- HTML Form with Bootstrap classes -->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <table class="table">
        <tbody>
            <tr style="margin-bottom: 10px;">
                <td><label for="date_time">Date & Time of Summon:</label></td>
                <td><input type="datetime-local" class="form-control" id="date_time" name="date_time" required></td>
            </tr>
            <tr style="margin-bottom: 10px;">
                <td><label for="summon_type">Summon Type:</label></td>
                <td>
                    <select class="form-select" id="summon_type" name="summon_type" required>
                        <option value="" selected disabled>Select Summon Type</option>
                        <option value="10">Parking Violation</option>
                        <option value="15">Not Comply in Campus Traffic Regulations</option>
                        <option value="20">Accident Caused</option>
                    </select>
                </td>
            </tr>
            <tr style="margin-bottom: 10px;">
                <td><label for="merit_point">Merit Point:</label></td>
                <td>
                    <select class="form-select" id="merit_point" name="merit_point" required>
                        <option value="" selected disabled>Select Merit Point</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                    </select>
                </td>
            </tr>
        </tbody>
    </table>
    <button type="submit" class="btn btn-primary">Submit</button>
    <button type="reset" class="btn btn-primary">Reset</button>
    <a href="summons.php" class="btn btn-secondary">Cancel</a>
</form>

<?php
require_once 'layout_bottom.php';
$conn->close();
?>