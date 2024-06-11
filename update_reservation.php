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

$reservation_id = $_GET['id'];

$sql = "
    SELECT reservations.id, reservations.booking_time
    FROM reservations
    WHERE reservations.id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $reservation_id);
$stmt->execute();
$result = $stmt->get_result();
$reservation = $result->fetch_assoc();
$stmt->close();

$sql = "SELECT id, name FROM parking_zones";
$zones_result = $conn->query($sql);

$conn->close();
?>

<h1>Update Reservation</h1>

<form action="process_update_reservation.php" method="post">
    <input type="hidden" name="reservation_id" value="<?= $reservation_id ?>">
    
    <label for="parkingZone">Filter by Parking Zone:</label>
    <select id="parkingZone" name="parkingZone" required>
        <option value="all">All</option>
        <?php while ($zone = $zones_result->fetch_assoc()): ?>
            <option value="<?= $zone['id'] ?>"><?= $zone['name'] ?></option>
        <?php endwhile; ?>
    </select>
    <button type="button" onclick="filterParking()">Filter</button>

    <label for="parkingSpace">Parking Space:</label>
    <select id="parkingSpace" name="parkingSpace" required>
        <option value="">Select Space</option>
    </select>

    <label for="booking_time">Booking Date and Time:</label>
    <input type="datetime-local" id="booking_time" name="booking_time" value="<?= date('Y-m-d\TH:i', strtotime($reservation['booking_time'])) ?>" required>
    
    <button style="background-color: blue; color:white;" type="submit">Update Reservation</button>
</form>

<script>
    function filterParking() {
        const parkingZone = document.getElementById('parkingZone').value;
        
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `filter_parking.php?zone=${parkingZone}`, true);
        xhr.onload = function () {
            if (this.status === 200) {
                const data = JSON.parse(this.responseText);
                const parkingSpaceSelect = document.getElementById('parkingSpace');
                parkingSpaceSelect.innerHTML = '<option value="">Select Space</option>';
                data.forEach(row => {
                    const option = document.createElement('option');
                    option.value = row.id;
                    option.textContent = row.name+'-'+row.area;
                    parkingSpaceSelect.appendChild(option);
                });
            }
        };
        xhr.send();
    }

    window.onload = function() {
        filterParking();
    }
</script>

<?php
require_once 'layout_bottom.php';
?>
