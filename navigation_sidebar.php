<nav class="d-flex flex-column p-5">
    <div class="mb-3 d-flex justify-content-center">
        <h3 class="m0">FKPark</h3>
    </div>

    <h6 class="text-uppercase fw-bold mb-2 mt-4 mx-3">User Management</h6>
    <a class="nav-button btn" href="index.php">
        <i class="bi bi-house"></i>
        <div>Home</div>
    </a>
    <a class="nav-button btn" href="user_profile_show.php?id=<?= $_SESSION['user_id'] ?>">
        <i class="bi bi-person"></i>
        <div>My Profile</div>
    </a>
    <?php
    require_once 'database_util.php';
    ?>
    <?php if (get_user_type() === 'admin' || get_user_type() == 'staff') : ?>
        <a class="nav-button btn" href="users.php">
            <i class="bi bi-people-fill"></i>
            <div>Users</div>
        </a>
    <?php endif; ?>
    <a class="nav-button btn" href="vehicles.php">
        <i class="bi bi-fuel-pump"></i>
        <div>Vehicles</div>
    </a>
    
    <h6 class="text-uppercase fw-bold mb-2 mt-5 mx-3">Parking Management</h6>
    <a class="nav-button btn" href="admin_dashboard.php">
        <i class="bi bi-arrow-right"></i>
        <div>Dashboard</div>
    </a>
    <a class="nav-button btn" href="parking_zones.php">
        <i class="bi bi-arrow-right"></i>
        <div>Parking Zone</div>
    </a>

    <a class="nav-button btn" href="add_parking_space.php">
        <i class="bi bi-arrow-right"></i>
        <div>Add Parking Space</div>
    </a>

    <h6 class="text-uppercase fw-bold mb-2 mt-5 mx-3">Park Booking</h6>
    <a class="nav-button btn" href="reserve_chart.php">
        <i class="bi bi-arrow-right"></i>
        <div>Dashboard</div>
    </a>
    <a class="nav-button btn" href="new_reservation.php">
        <i class="bi bi-arrow-right"></i>
        <div>Add New Reservation</div>
    </a>

    <a class="nav-button btn" href="reservation_list.php">
        <i class="bi bi-arrow-right"></i>
        <div>Reservation List</div>
    </a>

    <h6 class="text-uppercase fw-bold mb-2 mt-5 mx-3">Traffic Summon</h6>

    <a class="nav-button btn" href="traffic_dashboard.php">>
        <i class="bi bi-arrow-right"></i>
        <div>Dashboard</div>
    </a>

    <a class="nav-button btn" href="trafic_summon.php">
        <i class="bi bi-arrow-right"></i>
        <div>Traffic Summon Record</div>
    </a>

    <a class="nav-button btn"href="accident_report.php">
        <i class="bi bi-arrow-right"></i>
        <div>Accident Report</div>
    </a>
<!--    <a class="nav-button btn">-->
<!--        <i class="bi bi-arrow-right"></i>-->
<!--        <div>Home</div>-->
<!--    </a>-->
</nav>