<?php
require_once 'layout_top.php';

if (!isset($_SESSION['username'])) {
    header('location:login.php');
    return;
}

$conn = mysqli_connect('localhost:3307', 'root', '', 'fkpark');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$reservation_id = isset($_GET['id']) ? $_GET['id'] : null;

if ($reservation_id === null) {
    die("Reservation ID is required.");
}

$sql = "
    SELECT reservations.id, reservations.booking_time, 
           parking_spaces.area, parking_zones.name AS zone_name,
           users.first_name, users.last_name, users.contact_number
    FROM reservations
    INNER JOIN parking_spaces ON reservations.parking_id = parking_spaces.id
    INNER JOIN parking_zones ON parking_spaces.zone_id = parking_zones.id
    INNER JOIN users ON reservations.user_id = users.id
    WHERE reservations.id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $reservation_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No reservation found with the given ID.");
}

$reservation = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reservation</title>
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 50px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
        }
        .container h1 {
            text-align: center;
            color: #333;
        }
        .container table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .container th, .container td {
            border: 1px solid #ddd;
            padding: 20px;
            text-align: left;
        }
        .container th {
            background-color: #4CAF50;
            color: white;
        }
        .container tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .container tr:hover {
            background-color: #ddd;
        }
        .container td {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Reservation Details</h1>
        <table>
            <tr>
                <th>First Name</th>
                <td><?= htmlspecialchars($reservation['first_name']) ?></td>
            </tr>
            <tr>
                <th>Last Name</th>
                <td><?= htmlspecialchars($reservation['last_name']) ?></td>
            </tr>
            <tr>
                <th>Contact Number</th>
                <td><?= htmlspecialchars($reservation['contact_number']) ?></td>
            </tr>
            <tr>
                <th>Reservation ID</th>
                <td><?= htmlspecialchars($reservation['id']) ?></td>
            </tr>
            <tr>
                <th>Parking Zone</th>
                <td><?= htmlspecialchars($reservation['zone_name']) ?></td>
            </tr>
            <tr>
                <th>Parking Space</th>
                <td><?= htmlspecialchars($reservation['zone_name']) ?>-<?= htmlspecialchars($reservation['area']) ?></td>
            </tr>
            <tr>
                <th>Booking Date and Time</th>
                <td><?= htmlspecialchars($reservation['booking_time']) ?></td>
            </tr>
        </table>
    </div>
</body>
</html>

<?php
require_once 'layout_bottom.php';
?>