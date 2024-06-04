<?php

require_once 'layout_top.php';
require_once 'database_util.php'; // Include the database connection file

if (!isset($_SESSION['username'])) {
    header('location:login.php');
    return;
}

?>

<style>
    /* Existing CSS styles */
    table {
        border-collapse: collapse;
        width: auto;
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

    .add-button:hover {
        color: #fff;
        background-color: #4caf50;
        border-color: #269abc;
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

    .edit-delete-buttons form {
        margin-right: 10px;
        /* Adjust the spacing as needed */
    }

    .view-button {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #fff;
    }

    .view-button:hover {
        background-color: #e0a800;
        border-color: #d39e00;
    }
</style>

<h1>Parking Zone</h1><br>

<!-- Parking Zone List -->
<h2>Parking Zone List</h2>
<br>
<table border="1" class="table table-bordered">
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

                echo "<td class=\"w-auto\">";
                // Update selection 
                echo "<form action='update_parking_zones.php' method='post' class='d-flex gap-2'>";
                echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
                echo "<select name='status' class='form-control' style='max-width: 20rem;'>";
                echo "<option value='Open'" . ($row['status'] == 'Open' ? " selected" : "") . ">Open</option>";
                echo "<option value='Closed'" . ($row['status'] == 'Closed' ? " selected" : "") . ">Closed</option>";
                echo "<option value='Under maintenance'" . ($row['status'] == 'Under maintenance' ? " selected" : "") . ">Under maintenance</option>";
                echo "</select>";
                
                // Update button
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

<!-- Parking Space List -->
<h2>Parking Space List</h2>
<br>

<!-- Add button -->
<input type='button' class='btn btn-primary add-button' value='Add' onclick="location.href='add_parking_space.php'"><br>

<!-- Parking Space Table list -->
<table border="1" class="table table-bordered">
    <thead>
        <tr>
            <th>No.</th>
            <th>Parking Space Name</th>
            <th>Parking Zone Name</th>
            <th>Status</th> <!-- New Status column header -->
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
                pz.name AS zone_name,
                pz.status AS zone_status  /* Add status in query */
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
                echo "<td>" . htmlspecialchars($row['zone_status']) . "</td>";  /* Add status to table */
                echo "<td>" . ($row['is_available'] == 1 ? "Available" : "Not Available") . "</td>";

                // Edit and Delete buttons in the same row within a div
                echo "<td class='action-buttons'>";
                echo "<div class='edit-delete-buttons'>";

                // View button
                echo "<form action='view_parking_space.php' method='get' style='display:inline;'>";
                echo "<input type='hidden' name='id' value='" . $row['space_id'] . "'>";
                echo "<input type='submit' class='btn btn-warning mr-2 update-button view-button' value='View'>";
                echo "</form>";

                // Edit button 
                echo "<form action='update_parking_space.php' method='get' style='display:inline;'>";
                echo "<input type='hidden' name='id' value='" . $row['space_id'] . "'>";
                echo "<input type='submit' class='btn btn-warning mr-2 update-button' value='Edit'>";
                echo "</form>";

                // Delete button 
                echo "<form action='delete_parking_space.php' method='post' style='display:inline;'>";
                echo "<input type='hidden' name='id' value='" . $row['space_id'] . "'>";
                echo "<input type='submit' class='btn btn-danger delete-button' value='Delete'>";
                echo "</form>";

                echo "</div>";
                echo "</td>";

                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No data available</td></tr>";
        }
        $stmt->close();
        ?>
    </tbody>
</table>

<?php
require_once 'layout_bottom.php';
$conn->close();
?>
