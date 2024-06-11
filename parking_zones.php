<?php

require_once 'layout_top.php';
require_once 'database_util.php'; // Include the database connection file

if (!isset($_SESSION['username'])) {
    header('location:login.php');
    return;
}

?>

<link rel="stylesheet" href="styles/module2/parking_zones.css">

<h1>Parking Zone</h1><br>

<!-- Parking Zone List -->
<h2>Parking Zone List</h2>
<br>
<?php if (get_user_type() === 'admin'): ?>
    <!-- Add button -->
    <input type='button' class='btn btn-primary add-button' value='Add' onclick="location.href='add_parking_zone.php'">
<?php endif; ?>
<br>

<!-- Search Form for Parking Zone-->
<form action="parking_zones.php" method="get" class="form-search d-flex align-items-center">
    <label for="search_zone" class="me-2">Search Parking Zones:</label>
    <input type="text" id="search_zone" name="search_zone" placeholder="Enter parking zone name"
        class="form-control me-2" style="max-width: 25rem;">
    <button type="submit" class="btn btn-secondary search-reset ms-2">Search</button>
    <button type="reset" class="btn btn-secondary search-reset ms-2"
        onclick="location.href='parking_zones.php'">Reset</button>
</form>
<br>

<table border="1" class="table table-bordered">
    <thead>
        <tr>
            <th>No.</th>
            <th>Parking Zone</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Prepare SQL statement for fetching parking zones
        $sql = "SELECT id, name, status FROM parking_zones";

        // Check if search query is provided
        if (isset($_GET['search_zone']) && !empty($_GET['search_zone'])) {
            $search_query = $_GET['search_zone'];
            // Modify SQL query to filter based on search query
            $sql .= " WHERE name LIKE '%$search_query%'";
        }

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $counter = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $counter++ . "</td>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['status']) . "</td>";

                // Buttons in the same row within a div
                echo "<td class='action-buttons'>";
                echo "<div class='edit-delete-buttons'>";

                // View button
                echo "<form action='view_parking_zone.php' method='get' style='display:inline;'>";
                echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
                echo "<input type='submit' class='btn btn-warning mr-2 update-button view-button' value='View'>";
                echo "</form>";

                if (get_user_type() === 'admin'): //for admin
                    // Edit button 
                    echo "<form action='edit_parking_zone.php' method='get' style='display:inline;'>";
                    echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
                    echo "<button type='submit' class='btn btn-warning mr-2 update-button'>Edit</button>";
                    echo "</form>";

                    // Delete button 
                    echo "<form action='delete_parking_zone.php' method='post' style='display:inline;'>";
                    echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
                    echo "<input type='submit' class='btn btn-danger delete-button' value='Delete'>";
                    echo "</form>";
                endif;

                echo "</div>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No data available</td></tr>";
        }
        $stmt->close();
        ?>
    </tbody>
</table>

<?php
require_once 'layout_bottom.php';
$conn->close();
?>