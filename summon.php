<?php

require_once 'layout_top.php';

if (!isset($_SESSION['username'])) {
    to_url('login.php');
}
?>

<h1>Manage Traffic Summons</h1>

<!-- Summon Form -->
<div style="margin-bottom: 20px;">
    <h2>Create Summon</h2>
    <form method="POST" action="summon_management.php">
        <label for="vehicle_id">Vehicle ID:</label>
        <input type="text" id="vehicle_id" name="vehicle_id" required>
        <br>
        <label for="summon_date">Summon Date:</label>
        <input type="text" id="summon_date" name="summon_date" required>
        <br>
        <label for="qr_code">QR Code:</label>
        <input type="text" id="qr_code" name="qr_code" required>
        <br>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="violation_type">Violation Type:</label>
        <select id="violation_type" name="violation_type" required>
            <option value="parking violation">Parking Violation</option>
            <option value="not comply traffic regulation">Not Comply Traffic Regulation</option>
            <option value="accident cause">Accident Cause</option>
        </select>
        <br>
        <label for="merit_points">Merit Points:</label>
        <select id="merit_points" name="merit_points" required>
            <option value="10">10</option>
            <option value="15">15</option>
            <option value="20">20</option>
        </select>
        <br>
        <button type="submit" name="create_summon" class="btn btn-primary">Create Summon</button>
    </form>
</div>

<?php

// Handle summon creation
if (isset($_POST['create_summon'])) {
    $vehicle_id = $_POST['vehicle_id'];
    $summon_date = $_POST['summon_date'];
    $qr_code = $_POST['qr_code'];
    $username = $_POST['username'];
    $violation_type = $_POST['violation_type'];
    $merit_points = $_POST['merit_points'];

    $stmt = $conn->prepare("INSERT INTO summons (vehicle_id, summon_date, qr_code, username, violation_type, merit_points) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $vehicle_id, $summon_date, $qr_code, $username, $violation_type, $merit_points);

    if ($stmt->execute()) {
        echo "<p>Summon created successfully!</p>";
    } else {
        echo "<p>Error creating summon: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

// Fetch and display summons
$result = $conn->query("SELECT * FROM summons ORDER BY id DESC");

if ($result->num_rows > 0) {
    echo "<h2>Traffic Summons</h2>";
    echo "<table class='table table-bordered'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Vehicle ID</th>";
    echo "<th>Summon Date</th>";
    echo "<th>QR Code</th>";
    echo "<th>Username</th>";
    echo "<th>Violation Type</th>";
    echo "<th>Merit Points</th>";
    echo "<th>Actions</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['vehicle_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['summon_date']) . "</td>";
        echo "<td>" . htmlspecialchars($row['qr_code']) . "</td>";
        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
        echo "<td>" . htmlspecialchars($row['violation_type']) . "</td>";
        echo "<td>" . htmlspecialchars($row['merit_points']) . "</td>";
        echo "<td>";
        echo "<a href='summon_management.php?edit=" . $row['id'] . "' class='btn btn-secondary'>Edit</a> ";
        echo "<a href='summon_management.php?delete=" . $row['id'] . "' class='btn btn-danger' onclick='return confirm(\"Are you sure?\");'>Delete</a>";
        echo "</td>";
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
} else {
    echo "<p>No summons found.</p>";
}

// Handle summon deletion
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM summons WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<p>Summon deleted successfully!</p>";
    } else {
        echo "<p>Error deleting summon: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

// Handle summon status update (e.g., marking as paid)
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM summons WHERE id = $id");
    $summon = $result->fetch_assoc();

    if ($summon) {
        echo "<h2>Edit Summon</h2>";
        echo "<form method='POST' action='summon_management.php'>";
        echo "<input type='hidden' name='id' value='" . $summon['id'] . "'>";
        echo "<label for='violation_type'>Violation Type:</label>";
        echo "<select id='violation_type' name='violation_type' required>";
        echo "<option value='parking violation'" . ($summon['violation_type'] == 'parking violation' ? ' selected' : '') . ">Parking Violation</option>";
        echo "<option value='not comply traffic regulation'" . ($summon['violation_type'] == 'not comply traffic regulation' ? ' selected' : '') . ">Not Comply Traffic Regulation</option>";
        echo "<option value='accident cause'" . ($summon['violation_type'] == 'accident cause' ? ' selected' : '') . ">Accident Cause</option>";
        echo "</select>";
        echo "<br>";
        echo "<label for='merit_points'>Merit Points:</label>";
        echo "<select id='merit_points' name='merit_points' required>";
        echo "<option value='10'" . ($summon['merit_points'] == '10' ? ' selected' : '') . ">10</option>";
        echo "<option value='15'" . ($summon['merit_points'] == '15' ? ' selected' : '') . ">15</option>";
        echo "<option value='20'" . ($summon['merit_points'] == '20' ? ' selected' : '') . ">20</option>";
        echo "</select>";
        echo "<br>";
        echo "<button type='submit' name='update_summon' class='btn btn-primary'>Update Summon</button>";
        echo "</form>";
    } else {
        echo "<p>Summon not found.</p>";
    }
}

// Handle status update form submission
if (isset($_POST['update_summon'])) {
    $id = $_POST['id'];
    $violation_type = $_POST['violation_type'];
    $merit_points = $_POST['merit_points'];

    $stmt = $conn->prepare("UPDATE summons SET violation_type = ?, merit_points = ? WHERE id = ?");
    $stmt->bind_param("ssi", $violation_type, $merit_points, $id);

    if ($stmt->execute()) {
        echo "<p>Summon updated successfully!</p>";
    } else {
        echo "<p>Error updating summon: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

require_once 'layout_bottom.php';
?>
