<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('location:login.php');
    exit();
}

$conn = mysqli_connect('localhost:3307', 'root', '', 'fkpark');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$username = $_SESSION['username'];
$sql = "SELECT id FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['id'];

$stmt->close();

if (isset($_GET['id'])) {
    $reservation_id = $_GET['id'];

    $sql = "SELECT * FROM reservations WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $reservation_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $sql = "DELETE FROM reservations WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $reservation_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<script>
                    alert('Reservation cancelled successfully!');
                    window.location.href = 'reservation_list.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Failed to cancel the reservation. Please try again.');
                    window.location.href = 'reservation_list.php';
                  </script>";
        }

        $stmt->close();
    } else {
        echo "<script>
                alert('Invalid reservation or you do not have permission to cancel this reservation.');
                window.location.href = 'reservation_list.php';
              </script>";
    }
} else {
    echo "<script>
            alert('Invalid request.');
            window.location.href = 'reservation_list.php';
          </script>";
}

$conn->close();
?>
