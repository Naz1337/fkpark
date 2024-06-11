<?php

require_once 'layout_top.php';

if (!isset($_SESSION['username'])) {
    header('location:login.php');
    return;
}

require_once 'database_util.php';
global $conn;
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

$sql = "
    SELECT reservations.id, parking_spaces.area, parking_zones.name AS zone_name, reservations.booking_time
    FROM reservations
    INNER JOIN parking_spaces ON reservations.parking_id = parking_spaces.id
    INNER JOIN parking_zones ON parking_spaces.zone_id = parking_zones.id
    WHERE reservations.user_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$reservations = [];
while ($row = $result->fetch_assoc()) {
    $reservations[] = $row;
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation List</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        .btn-blue {
            background-color: blue;
            color: white;
        }
        .btn-gray {
            background-color: gray;
            color: white;
        }
    </style>
</head>
<body>
    <h1>Your Reservation List</h1>
    <?php if (empty($reservations)): ?>
        <p>No reservations found.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Parking Space</th>
                    <th>Parking Zone</th>
                    <th>Date and Time</th>
                    <th>QR Code</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($reservations as $reservation) {
                    echo "<tr>
                        <td>{$no}</td>
                        <td>{$reservation['zone_name']}-{$reservation['area']}</td>
                        <td>{$reservation['zone_name']}</td>
                        <td>{$reservation['booking_time']}</td>
                        <td>
                            <a href='view_reservation.php?id=" . urlencode($reservation['id']) . "'>
                                <img src='indexx.php?text=" . urlencode($reservation['id']) . "' alt='QR Code'>
                            </a>
                        </td>
                        <td>
                            <a class='btn btn-blue' href='update_reservation.php?id={$reservation['id']}'>Update</a>
                            <button class='btn btn-gray' onclick='confirmCancel({$reservation['id']})'>Cancel</button>
                        </td>
                    </tr>";
                    $no++;
                }
                ?>
            </tbody>
        </table>
    <?php endif; ?>

    <script>
        function confirmCancel(id) {
            if (confirm("Are you sure you want to cancel this reservation?")) {
                window.location.href = `cancel_reservation.php?id=${id}`;
            }
        }
    </script>
</body>
</html>
<?php
require_once 'layout_bottom.php';
?>
