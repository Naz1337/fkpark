<?php
require_once 'layout_top.php';

if (!isset($_SESSION['username'])) {
    header('location:login.php');
    return;
}

$username = $_SESSION['username'];

$conn = mysqli_connect('localhost:3307', 'root', '', 'fkpark');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT id FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "User not found.";
    require_once 'layout_bottom.php';
    return;
}

$user = $result->fetch_assoc();
$user_id = $user['id'];

$stmt->close();

if (!isset($_GET['id'])) {
    echo "No parking space selected.";
    require_once 'layout_bottom.php';
    return;
}

$parking_id = $_GET['id'];

$sql = "SELECT parking_spaces.area, parking_zones.name FROM parking_spaces INNER JOIN parking_zones ON parking_spaces.zone_id = parking_zones.id WHERE parking_spaces.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $parking_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Parking space not found.";
    require_once 'layout_bottom.php';
    return;
}

$parking_space = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<style>
    .form-group {
        margin-bottom: 15px;
    }
    .form-group label {
        display: block;
        margin-bottom: 5px;
    }
    .form-group input {
        width: 100%;
        padding: 8px;
        box-sizing: border-box;
    }
    .form-group button {
        padding: 10px 15px;
        background-color: #007bff;
        color: white;
        border: none;
        cursor: pointer;
    }
    .form-group button:hover {
        background-color: #0056b3;
    }
</style>

<h1>Reserve Parking Space</h1>
<p>Selected Parking Space: <?= htmlspecialchars($parking_space['name'] . ' - ' . $parking_space['area']) ?></p>

<form action="process_reservation.php" method="post">
    <input type="hidden" name="parking_id" value="<?= htmlspecialchars($parking_id) ?>">
    <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id) ?>">
    <div class="form-group">
        <label for="duration">Parking Duration</label>
        <input type="double" id="duration" name="duration" required>
    </div>
    <div class="form-group">
        <label for="booking_time">Parking Date and Time</label>
        <input type="datetime-local" id="booking_time" name="booking_time" required>
    </div>
    <div class="form-group">
        <button type="submit">Submit Reservation</button>
    </div>
</form>

<?php
require_once 'layout_bottom.php';
?>
