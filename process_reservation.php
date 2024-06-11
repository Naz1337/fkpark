<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('location:login.php');
    return;
}

date_default_timezone_set('Asia/Kuala_Lumpur');

require_once 'database_util.php';
global $conn;
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$parking_id = $_POST['parking_id'];
$duration = $_POST['duration'];
$booking_time = $_POST['booking_time'];

$username = $_SESSION['username'];
$sql = "SELECT id FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['id'];

$stmt->close();

$booking_datetime = new DateTime($booking_time);

$current_datetime = new DateTime();

// Check if the booking time is in the past
if ($booking_datetime < $current_datetime) {
    echo "<script>
        alert('The booking time you chose already passed, choose another time.');
        window.history.back();
    </script>";
    exit;
}

// Check if the booking time is at least one hour from the current time
$interval = $current_datetime->diff($booking_datetime);
if ($interval->h < 1 && $interval->invert == 0) {
    echo "<script>
        alert('The date and time you chose is too near, please choose a time at least one hour before your parking time.');
        window.history.back();
    </script>";
    exit;
}

$sql = "SELECT * FROM reservations WHERE parking_id = ? AND booking_time = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $parking_id, $booking_time);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<script>
        alert('Your reservation is clashing with another reservation! Please choose a different date/time or parking space.');
        window.location.href = 'new_reservation.php';
    </script>";
} else {
    $sql = "INSERT INTO reservations (parking_id, user_id, duration, booking_time) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiss", $parking_id, $user_id, $duration, $booking_time);
    
    if ($stmt->execute()) {
        echo "<script>
            alert('Your Parking Reservation is successful!');
            window.location.href = 'reservation_list.php';
        </script>";
    } else {
        echo "<script>
            alert('Error occurred while processing your reservation. Please try again.');
            window.location.href = 'new_reservation.php';
        </script>";
    }
}

$stmt->close();
$conn->close();
?>
