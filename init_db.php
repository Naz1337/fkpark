<?php

require_once 'bootstrap.php';

if ($_SERVER['APP_ENV'] === 'development') {
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $dbname = "fkpark";
}
else {
    $hostname = "10.26.30.17";
    $username = "cb22159";
    $password = "cb22159";
    $dbname = "cb22159";
}

$checkFile = 'db_setup_completed.txt';

try {
    // Check if the script has already been run
    if (file_exists($checkFile)) {
        die("The script has already been executed.");
    }



    // Connect to the database
    $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Drop the database if it already exists but in a way that is not using drop. just drop
    // each table
    $pdo->exec("DROP TABLE IF EXISTS merit_points");
    $pdo->exec("DROP TABLE IF EXISTS parking_spaces");
    $pdo->exec("DROP TABLE IF EXISTS parking_zones");
    $pdo->exec("DROP TABLE IF EXISTS reservations");
    $pdo->exec("DROP TABLE IF EXISTS summons");
    $pdo->exec("DROP TABLE IF EXISTS vehicles");
    $pdo->exec("DROP TABLE IF EXISTS users");


    // The SQL script to be executed
    $sql = <<<'SQL'
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2024 at 09:14 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fkpark`
--

-- --------------------------------------------------------

--
-- Table structure for table `merit_points`
--

CREATE TABLE `merit_points` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_id` bigint(20) UNSIGNED NOT NULL,
  `total_points` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parking_spaces`
--

CREATE TABLE `parking_spaces` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `zone_id` bigint(20) UNSIGNED NOT NULL,
  `area` char(1) NOT NULL,
  `name` text NOT NULL,
  `is_available` tinyint(1) NOT NULL,
  `qr_code` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parking_spaces`
--

INSERT INTO parking_spaces (id, zone_id, area, name, is_available, qr_code) VALUES
(1, 1, '1', 'A1-1', 1, ''),
(2, 1, '2', 'A1-2', 1, ''),
(3, 2, '1', 'A2-1', 1, ''),
(4, 2, '2', 'A2-2', 1, ''),
(5, 3, '1', 'A3-1', 1, ''),
(6, 3, '2', 'A3-2', 1, ''),
(7, 4, '1', 'B1-1', 1, ''),
(8, 4, '2', 'B1-2', 1, ''),
(9, 5, '1', 'B2-2', 1, ''),
(10, 5, '2', 'B2-2', 1, ''),
(11, 6, '1', 'B3-1', 1, ''),
(12, 6, '2', 'B3-2', 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `parking_zones`
--

CREATE TABLE `parking_zones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `status` enum('Open','Closed','Under maintenance') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parking_zones`
--

INSERT INTO `parking_zones` (`id`, `name`, `status`) VALUES
(1, 'A1', 'Closed'),
(2, 'A2', 'Under maintenance'),
(3, 'A3', 'Closed'),
(4, 'B1', 'Closed'),
(5, 'B2', 'Closed'),
(6, 'B3', 'Closed');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `parking_id` bigint(20) UNSIGNED NOT NULL,
  `booking_time` datetime NOT NULL,
  `duration` double NOT NULL,
  `qr_code` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `summons`
--

CREATE TABLE `summons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_id` bigint(20) UNSIGNED NOT NULL,
  `summon_date` date NOT NULL,
  `qr_code` text NOT NULL,
  `username` text NOT NULL,
  `violation_type` enum('parking violation','not comply traffic regulation','accident cause') NOT NULL,
  `merit_points` enum('10','15','20') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `summons`
--

INSERT INTO `summons` (`id`, `vehicle_id`, `summon_date`, `qr_code`, `username`, `violation_type`, `merit_points`) VALUES
(1, 1, '2024-06-18', 'QRcode::png($text, $file, $ecc, $pixel_Size, $frame_Size);', 'Ahmad Toib', 'parking violation', '10'),
(2, 2, '2024-06-25', 'QRcode::png($text, $file, $ecc, $pixel_Size, $frame_Size);', 'Danish Malik', 'parking violation', '10'),
(3, 3, '2024-06-30', 'QRcode::png($text, $file, $ecc, $pixel_Size, $frame_Size);', 'staff76', 'not comply traffic regulation', '15'),
(4, 4, '2024-07-06', 'QRcode::png($text, $file, $ecc, $pixel_Size, $frame_Size);', 'Abdul Razaq', 'accident cause', '20');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `user_type` enum('student','admin','staff') NOT NULL,
  `first_name` text NOT NULL,
  `last_name` text NOT NULL,
  `contact_number` text NOT NULL,
  `address` text NOT NULL,
  `is_logged_in` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `user_type`, `first_name`, `last_name`, `contact_number`, `address`) VALUES
(1, 'mangkuk', '$2y$10$/Ga/GYo53JenXvCS2KiWOOE3AZZ/ckM.DsMFLbU2/EW8OJdIK638K', 'admin', 'Mohd Mankuk', 'bin Lejen', '999', 'NO 1'),
(3, 'staff01', '$2y$10$ioms9iOh9tH8HHCrvTJOV.nJh0EviRSMv1a7o/DHNuW3d/abbGuIu', 'staff', 'staff', 'ayah', '999', '1'),
(4, 'staff02', '$2y$10$4C9MeAgNINN3jSg6O5AnsOahBMvacqKezaROfDC9wPLkU293l.qvq', 'staff', 'staff', '', '1', '1'),
(7, 'fwz', '$2y$10$DqzME3XrcL7mplQLDzwpF.JgmdxKZ2jDmhwdTfHQ0yPoHpfgLZ52S', 'admin', 'fawwaz', 'hatmi', '01159912744', 'UMP'),
(8, 'danial', '$2y$10$gBy3WjSpHDVMjFhNZ2r3g.Nbnr3/kt6uw7ZNWW8rFid5ErKUiyPnS', 'student', 'danial', 'aiman', '0123456789', 'eefrf'),
(10, 'staff1', '$2y$10$YJam0t4ClKhS07SYmnc8JOcet3EpRpVcs24cfCdn0m.2siBILmtYy', 'staff', 'Staff', '1', '235', 'No 3'),
(11, 'admin', '$2y$10$asqtCgs9wgz7MQ5YX36oP.1JW9Lvr4Adam18BAtt04lVoq.v8E4zy', 'admin', 'admin', 'Admin', '999', 'No 999');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_type` text NOT NULL,
  `vehicle_model` text NOT NULL,
  `vehicle_plate` text NOT NULL,
  `vehicle_grant` text NOT NULL,
  `qr_code` text NOT NULL,
  `approval_status` tinyint(1) NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approval_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `user_id`, `vehicle_type`, `vehicle_model`, `vehicle_plate`, `vehicle_grant`, `qr_code`, `approval_status`, `approved_by`, `approval_date`) VALUES
(10, 8, 'Car', 'Mini Minter', 'kki223', 'storage/GRP 4 CB22159 Gaja Solution Sdn Bhd POS.pdf_student_kki223.pdf', 'storage/qr_codes/145ee42d.png', 1, 10, '2024-06-11 06:42:58'),
(11, 8, 'Car', 'Naza Azza', 'kop872', 'storage/GRP 4 CB22159 Gaja Solution Sdn Bhd POS.pdf_student_kop872.pdf', 'storage/qr_codes/69b5db5b.png', 1, 10, '2024-06-11 06:52:01');


--
-- Indexes for dumped tables
--

--
-- Indexes for table `merit_points`
--
ALTER TABLE `merit_points`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_vehicle_id` (`vehicle_id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `parking_spaces`
--
ALTER TABLE `parking_spaces`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_zone_id` (`zone_id`);

--
-- Indexes for table `parking_zones`
--
ALTER TABLE `parking_zones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reservation_user_id` (`user_id`),
  ADD KEY `fk_reservation_parking_id` (`parking_id`);

--
-- Indexes for table `summons`
--
ALTER TABLE `summons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_vehicle_id` (`vehicle_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`),
  ADD KEY `fk_approved_by` (`approved_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `merit_points`
--
ALTER TABLE `merit_points`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `parking_spaces`
--
ALTER TABLE `parking_spaces`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `parking_zones`
--
ALTER TABLE `parking_zones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `summons`
--
ALTER TABLE `summons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `parking_spaces`
--
ALTER TABLE `parking_spaces`
  ADD CONSTRAINT `fk_zone_id` FOREIGN KEY (`zone_id`) REFERENCES `parking_zones` (`id`);

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `fk_reservation_parking_id` FOREIGN KEY (`parking_id`) REFERENCES `parking_spaces` (`id`),
  ADD CONSTRAINT `fk_reservation_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `fk_approved_by` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
SQL;

    // Execute the SQL script
    $pdo->exec($sql);

    // Mark the script as executed
    file_put_contents($checkFile, "This script has been executed.");

    echo "SQL script executed successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

