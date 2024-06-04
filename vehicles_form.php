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
    $vehicle_grant_tmp_name = $vehicle_grant_file['tmp_name'];

    $destination_path = dirname(__FILE__) .  '/storage/' . basename($vehicle_grant_file['name']) . '_' . $_SESSION['username'] . '_' . $vehicle_plate;

    move_uploaded_file($vehicle_grant_tmp_name, resolvePath($destination_path));
    $vehicle_grant_pathfile = extract_destination_path($destination_path);

    // $qr_code = generateQRCode($vehicle_plate); // Assuming you have a function to generate QR code

    $url = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . $vehicle_plate    ;
    $options = [
        'http' => [
            'method' =>'GET',
            'header' => 'content-type: applications/octet-stream'
        ]
    ];
    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

    try {
        $qr_code_filepath = dirname(__FILE__) . '/storage/qr_codes/' . generateRandomString(8) . '.png';
    } catch (\Random\RandomException $e) {
        $qr_code_filepath = dirname(__FILE__) . '/storage/qr_codes/' . $vehicle_plate . '.png';
    }

    file_put_contents($qr_code_filepath, $response);

    $qr_code = extract_destination_path($qr_code_filepath);

    $insert_stmt->bind_param(
            "isssss",
            $user_id,
            $vehicle_type,
            $vehicle_model,
            $vehicle_plate,
            $vehicle_grant_pathfile,
            $qr_code);

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
