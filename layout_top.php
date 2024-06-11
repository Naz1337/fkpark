<?php 
require_once 'bootstrap.php';
global $base_url;

global $base_url;

if (!isset($title)) {
    $title = 'Fakulti Komputeran Parking System';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FKPark - <?= $title ?></title>
    <?php if ($_SERVER['APP_ENV'] === 'development'): ?>
        <script type="module" src="http://localhost:5173/@vite/client"></script>
        <script type="module" src="http://localhost:5173/js/main.js"></script>
    <?php else: ?>
        <?php
        $manifest = json_decode(file_get_contents(__DIR__ . '/build/.vite/manifest.json'), true);
        ?>
        <script type="module" src="<?= $base_url ?>/build/<?= $manifest['js/main.js']['file'] ?>"></script>
        <link rel="stylesheet" href="<?= $base_url ?>/build/<?= $manifest['js/main.js']['css'][0] ?>">
    <?php endif; ?>
</head>
<body>
    <div class="container-fluid p-0 d-flex fkpark-body">
        <div class="collapse collapse-horizontal show border-end" id="navMenu">
            <div style="min-width: 350px;">
                
            <?php require_once 'navigation_sidebar.php'; ?>

            </div>
        </div>
    
        <div class="flex-grow-1 d-flex flex-column align-items-center">
            <div class="border-bottom mb-5 d-flex align-items-center justify-content-between px-5 bg-white" style="width: 100%; min-height: 4rem;">
                <div>
                    <button class="btn rounded-5 nav-button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                        <i class="bi bi-window-sidebar"></i>
                    </button>
                </div>
                <div class="dropdown">
                    <div class="ps-2" style="cursor: pointer;" data-bs-toggle="dropdown">
                        <div class="shape-circle border">
                            <img src="./admin.jpg" alt="">
                        </div>
                    </div>
                    <ul class="dropdown-menu">
                        <li><div class="dropdown-item-text"><strong> <?= $_SESSION['username'] ?></strong></div></li>
                        <li>
                            <a class="dropdown-item d-flex align-content-center gap-2" href="user_profile_show.php?id=<?= $_SESSION['user_id'] ?>">
                                <i class="bi bi-person"></i>
                                View Profile
                            </a>
                        </li>
                        <li class="d-flex justify-content-center align-content-center p-2">
                            <a href="logout.php" class="btn btn-outline-danger w-100">Logout</a>
                        </li>
                    </ul>
                </div>
                
            </div>
            <div class="container">
                <div class="card">
                    <div class="card-body">
                        