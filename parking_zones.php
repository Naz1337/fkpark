<?php

require_once 'layout_top.php';
require_once 'database_util.php'; // Include the database connection file

?>

<h1>Parking Zone</h1><br>

<h2>Parking Zone List</h2>

<style>
    /* Existing CSS styles */
    table {
        border-collapse: collapse;
        width: auto;
    }

    table,
    th,
    td {
        border: 1px solid black;
    }

    th,
    td {
        padding: 8px;
        text-align: left;
    }

    .update-button {
        margin-left: 5px;
    }

    .add-button {
        margin-bottom: 10px;
    }

    .update-button {
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
        color: #fff;
        background-color: #5bc0de;
        border-color: #46b8da;
    }

    .update-button:hover {
        color: #fff;
        background-color: #4caf50;
        border-color: #269abc;
    }

    /* Add space between buttons */
    .action-buttons {
        margin-top: 5px;
    }
</style>



<table border="1">
    <thead>
        <tr>
            <th>No.</th>
            <th>Parking Zone</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $stmt = $conn->prepare("SELECT id, name, status FROM parking_zones");
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $counter = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $counter++ . "</td>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td>";
                echo "<form action='update_status.php' method='post'>";
                echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
                echo "<select name='status'>";
                echo "<option value='open'" . ($row['status'] == 'Open' ? " selected" : "") . ">Open</option>";
                echo "<option value='closed'" . ($row['status'] == 'Closed' ? " selected" : "") . ">Closed</option>";
                echo "<option value='maintenance'" . ($row['status'] == 'Under maintenance' ? " selected" : "") . ">On Maintenance</option>";
                echo "</select>";
                echo "<input type='submit' class='update-button' value='Update'>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No data available</td></tr>";
        }
        $stmt->close();
        ?>
    </tbody>
</table>

<br>
<h2>Parking Space List</h2>

<input type='button' class='btn btn-primary add-button' value='Add'
    onclick="location.href='add_parking_space.php'"><br><br>

<table border="1">
    <thead>
        <tr>
            <th>No.</th>
            <th>Parking Space Name</th>
            <th>Parking Zone Name</th>
            <th>Availability</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $stmt = $conn->prepare("
            SELECT 
                ps.id AS space_id, 
                ps.name AS space_name, 
                ps.is_available, 
                pz.name AS zone_name
            FROM 
                parking_spaces ps
            JOIN 
                parking_zones pz 
            ON 
                ps.zone_id = pz.id
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $counter = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $counter++ . "</td>";
                echo "<td>" . htmlspecialchars($row['space_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['zone_name']) . "</td>";
                echo "<td>" . ($row['is_available'] == 1 ? "Available" : "Not Available") . "</td>";

                echo "<td class='action-buttons'>";
                echo "<form action='update_availability.php' method='post' style='display:inline;'>";
                echo "<input type='hidden' name='id' value='" . $row['space_id'] . "'>";
                echo "<input type='submit' class='btn btn-warning mr-2 update-button' value='Edit'>";
                echo "</form>";
                
                echo "<form action='delete_space.php' method='post' style='display:inline;'>";
                echo "<input type='hidden' name='id' value='" . $row['space_id'] . "'>";
                echo "<input type='submit' class='btn btn-danger delete-button' value='Delete'>";
                echo "</form>";
                echo "</td>";


                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No data available</td></tr>";
        }
        $stmt->close();
        ?>
    </tbody>
</table>


<?php
require_once 'layout_bottom.php';
$conn->close();
?>