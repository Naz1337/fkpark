<?php
require_once 'layout_top.php';
require_once 'database_util.php'; // Include the database connection file

?>

<h1>Parking Zone</h1><br>

<h2>Parking Zone List</h2>

<style>
    table {
        border-collapse: collapse;
        width: auto;
    }

    table, th, td {
        border: 1px solid black;
    }

    th, td {
        padding: 8px;
        text-align: left;
    }

    .update-button {
        margin-left: 5px; 
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

                // Define options array based on status value in the database
                $options = [
                    1 => 'Open',
                    0 => 'Closed',
                    2 => 'On Maintenance'
                ];

                // Loop through options array to display options
                foreach ($options as $value => $label) {
                    $selected = $row['status'] == $value ? " selected" : "";
                    echo "<option value='$value' $selected>$label</option>";
                }

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
<table border="1">
    <!-- Add content for the Parking Space List table here if needed -->
</table>

<?php
require_once 'layout_bottom.php';
$conn->close();
?>
