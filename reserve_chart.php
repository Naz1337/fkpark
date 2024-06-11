<?php
require_once 'layout_top.php';

require_once 'database_util.php';
global $conn;
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

date_default_timezone_set('Asia/Kuala_Lumpur');
$current_date = date('Y-m-d');
echo "$current_date";
$sql = "
    SELECT 
        COUNT(CASE WHEN DATE(booking_time) = '$current_date' THEN 1 END) AS reserved_count,
        (SELECT COUNT(*) FROM parking_spaces) - COUNT(CASE WHEN DATE(booking_time) = '$current_date' THEN 1 END) AS available_count
    FROM reservations
";
$result = $conn->query($sql);

$parkingData = array();
if ($result->num_rows > 0) {
    $parkingData = $result->fetch_assoc();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking Space Reservation Chart</title>
    <style>
        body {
            height: 100vh;
            width: 100%;
            background-image: url("../../images/background.png"); /* Ensure this path is correct */
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat; /* Add this to prevent repeating */
            display: flex;
            justify-content: center;
            align-items: center; /* Center content vertically */
            margin: 0;
            font-family: 'Source Sans Pro', sans-serif;
            color: #000000;
            padding: 20px;
        }
        .chartContainer {
            width: 70%;
            margin: auto;
        }
        canvas {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <h1>Reservation Chart for Today</h1>
    <div class="chartContainer">
        <canvas id="parkingChart"></canvas>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Parking Data
        var parkingData = <?php echo json_encode($parkingData); ?>;
        var availableCount = parkingData.available_count;
        var reservedCount = parkingData.reserved_count;

        // Parking Chart
        var ctx = document.getElementById('parkingChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Available', 'Reserved'],
                datasets: [{
                    data: [availableCount, reservedCount],
                    backgroundColor: ['#3498DB', '#E74C3C'],
                    hoverBackgroundColor: ['#5DADE2', '#F1948A'],
                    borderColor: ['#2980B9', '#C0392B'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>
</html>
<?php
require_once 'layout_bottom.php';
?>
