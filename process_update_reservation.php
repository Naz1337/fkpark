<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('location:login.php');
    return;
}

$conn = mysqli_connect('localhost:3307', 'root', '', 'fkpark');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$reservation_id = $_POST['reservation_id'];
$parkingZone = $_POST['parkingZone'];
$parkingSpace = $_POST['parkingSpace'];
$booking_time = $_POST['booking_time'];

$sql = "SELECT COUNT(*) AS count FROM reservations WHERE parking_id = ? AND booking_time = ? AND id != ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isi", $parkingSpace, $booking_time, $reservation_id);
$stmt->execute();
$result = $stmt->get_result();
$count = $result->fetch_assoc()['count'];

if ($count > 0) {
    echo "<script>
            alert('Your reservation is clashing with another reservation! Please choose a different date/time or parking space.');
            window.location.href = 'update_reservation.php?id={$reservation_id}';
          </script>";
} else {
    $sql = "UPDATE reservations SET parking_id = ?, booking_time = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $parkingSpace, $booking_time, $reservation_id);
    if ($stmt->execute()) {
        echo "<script>
                alert('Your Parking Reservation update is successful!');
                window.location.href = 'reservation_list.php';
              </script>";
    } else {
        echo "Error updating reservation: " . $conn->error;
    }
}

$stmt->close();
$conn->close();
?>
