<?php

require_once 'layout_top.php';
require_once 'database_util.php'; // Include the database connection file

if (!isset($_SESSION['username'])) {
    header('location:login.php');
    return;
}

?>

<link rel="stylesheet" href="styles/module2/parking_zones.css">

<!-- Parking Space List -->
<h1>Parking Space</h1><br>
<h2>Parking Space List</h2><br>

<!-- Add button -->
<input type='button' class='btn btn-primary add-button' value='Add' onclick="location.href='add_parking_space.php'"><br>

<!-- Search Form for Parking Space-->
<form action="parking_spaces.php" method="get" class="form-search d-flex align-items-center">
    <label for="search_space" class="me-2">Search Parking Spaces:</label>
    <input type="text" id="search_space" name="search_space" placeholder="Enter parking space name"
        class="form-control me-2" style="max-width: 25rem;">
    <button type="submit" class="btn btn-secondary search-reset ms-2">Search</button>
    <button type="reset" class="btn btn-secondary search-reset ms-2"
        onclick="location.href='parking_spaces.php'">Reset</button>
</form>
<br>

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
        // Prepare SQL statement for fetching parking spaces
        $sql_spaces = "
            SELECT 
                ps.id AS space_id, 
                ps.name AS space_name, 
                ps.is_available, 
                pz.name AS zone_name,
                pz.status AS zone_status  
            FROM 
                parking_spaces ps
            JOIN 
                parking_zones pz 
            ON 
                ps.zone_id = pz.id";

        // Check if search query is provided
        if (isset($_GET['search_space']) && !empty($_GET['search_space'])) {
            $search_query = $_GET['search_space'];
            // Modify SQL query to filter based on search query
            $sql_spaces .= " WHERE ps.name LIKE '%$search_query%'";
        }

        $stmt_spaces = $conn->prepare($sql_spaces);
        $stmt_spaces->execute();
        $result_spaces = $stmt_spaces->get_result();
        if ($result_spaces->num_rows > 0) {
            $counter = 1;
            while ($row = $result_spaces->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $counter++ . "</td>";
                echo "<td>" . htmlspecialchars($row['space_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['zone_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['zone_status']) . "</td>";  /* Add status to table */
                echo "<td>" . ($row['is_available'] == 1 ? "Available" : "Not Available") . "</td>";

                // Buttons in the same row within a div
                echo "<td class='action-buttons'>";
                echo "<div class='edit-delete-buttons'>";

                // View button
                echo "<form action='view_parking_space.php' method='get' style='display:inline;'>";
                echo "<input type='hidden' name='id' value='" . $row['space_id'] . "'>";
                echo "<input type='submit' class='btn btn-warning mr-2 update-button view-button' value='View'>";
                echo "</form>";

                // Edit button 
                echo "<form action='edit_parking_space.php' method='get' style='display:inline;'>";
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
        $stmt_spaces->close();
        ?>
    </tbody>
</table>


<?php
require_once 'layout_bottom.php';
$conn->close();
?>