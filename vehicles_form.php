<?php


require_once 'layout_top.php';
require_once 'database_util.php';

global $conn;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $insert_stmt = $conn->prepare(
            "INSERT INTO vehicles (user_id, vehicle_type, vehicle_model, vehicle_plate, vehicle_grant, qr_code, approval_status) VALUES (?, ?, ?, ?, ?, ?, 0)");

    $user_id = $_SESSION['user_id'];
    $vehicle_type = $_POST['vehicle_type'];
    $vehicle_model = $_POST['vehicle_model'];
    $vehicle_plate = strtolower(str_replace(' ', '', $_POST['vehicle_plate']));

    // Handle vehicle_grant file upload
    $vehicle_grant_file = $_FILES['vehicle_grant'];
    $vehicle_grant_pathfile = file_get_contents($vehicle_grant_file['tmp_name']);

    // $qr_code = generateQRCode($vehicle_plate); // Assuming you have a function to generate QR code

    // make car link
    $base = get_base_url();
    if ($_SERVER['APP_ENV'] !== 'development')
        $uri = '/CB22159/fkpark/vehicle_show.php?plate=' . $vehicle_plate;
    else
        $uri = '/fkpark/vehicle_show.php?plate=' . $vehicle_plate;

    $qr_code = qr_base64($base . $uri);
//    $qr_code = generate_qr_code($base . $uri, $vehicle_plate);
    $nullVar = null;
    $insert_stmt->bind_param(
            "isssbs",
            $user_id,
            $vehicle_type,
            $vehicle_model,
            $vehicle_plate,
            $nullVar,
            $qr_code);

    $insert_stmt->send_long_data(4, $vehicle_grant_pathfile);

    if ($insert_stmt->execute()) {
        echo "Vehicle information inserted successfully.";
    } else {
        echo "Error: " . $insert_stmt->error;
    }

    $insert_stmt->close();
//    header('location: index.php');
    ?>
    <script>
        window.location.href = 'vehicles.php';
    </script>
    <?php
    return;
}

if (!isset($_SESSION['username'])) {
    header('location:login.php');
    return;
}

?>

<h1 class="mb-4">Vehicles Form</h1>

<form action="" method="post" enctype="multipart/form-data">
    <div class="mb-3 row">
        <label for="vehicleType" class="col-sm-2 col-form-label">Vehicle Type</label>
        <div class="col-sm-10">
            <select class="form-select" id="vehicleType" name="vehicle_type" required>
                <option value="" selected disabled>Select vehicle type</option>
                <option value="Car">Car</option>
                <option value="Motorcycle">Motorcycle</option>
            </select>
        </div>
    </div>
    <div class="mb-3 row">
        <label for="vehicleModel" class="col-sm-2 col-form-label">Vehicle Model</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="vehicleModel" name="vehicle_model" required>
        </div>
    </div>
    <div class="mb-3 row">
        <label for="vehiclePlate" class="col-sm-2 col-form-label">Vehicle Plate</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="vehiclePlate" name="vehicle_plate" required>
        </div>
    </div>
    <div class="mb-3 row">
        <label for="vehicleGrant" class="col-sm-2 col-form-label">Vehicle Grant (PDF only)</label>
        <div class="col-sm-10">
            <input type="file" class="form-control" id="vehicleGrant" name="vehicle_grant" accept="application/pdf" required>
        </div>
    </div>
    <div class="mb-3 row">
        <div class="col-sm-10 offset-sm-2">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
</form>


<?php
require_once 'layout_bottom.php';
