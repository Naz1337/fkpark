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
?>

<h1>Parking Zone Details</h1>

<div style="margin-bottom: 20px;">
    <a href="parking_zones.php" class="btn btn-primary">Back</a>
</div>

<table class="table">
    <tbody>
        <tr>
            <td><b>Parking Zone Name:</b></td>
            <td><?php echo $zone_data['name']; ?></td>
        </tr>
        <tr>
            <td><b>Status:</b></td>
            <td><?php echo $zone_data['status']; ?></td>
        </tr>
    </tbody>
</table>

<?php
require_once 'layout_bottom.php';
$conn->close();
?>