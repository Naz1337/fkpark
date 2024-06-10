<?php
require_once 'layout_top.php';
require_once 'database_util.php'; // Include the database connection file

if (!isset($_SESSION['username'])) {
    header('location:login.php');
    return;
}

// Functionality to handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehicle_id = $_POST['vehicle_id'];
    $vehicle_number = $_POST['vehicle_number'];
    $student_id = $_POST['student_id'];
    $description = $_POST['description'];

    // Assuming you have a table named 'accident_reports' in your database
    $query = "INSERT INTO accident_reports (vehicle_id, vehicle_number, student_id, description) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $vehicle_id, $vehicle_number, $student_id, $description);

    if ($stmt->execute()) {
        echo "<p>Accident report submitted successfully.</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }
}
?>

<style>
    table {
        border-collapse: collapse;
        width: auto;
    }

    th, td {
        padding: 8px;
        text-align: left;
    }

    .btn {
        display: inline-block;
        padding: 6px 12px;
        margin-bottom: 0;
        font-size: 14px;
        font-weight: normal;
        line-height: 1.42857143;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        cursor: pointer;
        border: 1px solid transparent;
        border-radius: 4px;
    }

    .btn-primary {
        color: #fff;
        background-color: #0275d8;
        border-color: #0275d8;
    }

    .btn-primary:hover {
        background-color: #025aa5;
        border-color: #025aa5;
    }

    .btn-secondary {
        color: #fff;
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
    }

    .btn-danger {
        color: #fff;
        background-color: #d9534f;
        border-color: #d43f3a;
    }

    .btn-danger:hover {
        background-color: #c9302c;
        border-color: #ac2925;
    }
</style>

<h1>Traffic Summon</h1>
<p>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?><br>
CD21052<br>
Total Summon Points: 10</p>

<h2>Accident Report</h2>

<form method="post" action="">
    <table border="1" class="table table-bordered">
        <tbody>
            <tr>
                <td>Vehicle ID:</td>
                <td><input type="text" name="vehicle_id" required></td>
            </tr>
            <tr>
                <td>Vehicle Number:</td>
                <td><input type="text" name="vehicle_number" required></td>
            </tr>
            <tr>
                <td>Student ID:</td>
                <td><input type="text" name="student_id" required></td>
            </tr>
            <tr>
                <td>Description:</td>
                <td><textarea name="description" required></textarea></td>
            </tr>
        </tbody>
    </table>

    <button type="submit" class="btn btn-primary">Update</button>
    <button type="button" class="btn btn-secondary" onclick="location.href='traffic_summon.php'">Cancel</button>
</form>

<?php
require_once 'layout_bottom.php';
$conn->close();
?>
