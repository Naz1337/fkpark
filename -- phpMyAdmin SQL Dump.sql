-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 04, 2024 at 03:59 AM
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
-- Database: 'fkpark'
--

CREATE DATABASE IF NOT EXISTS `fkpark` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `fkpark`;

-- --------------------------------------------------------

--
-- Table structure for table parking_spaces
--

CREATE TABLE parking_spaces (
  'id' bigint(20) UNSIGNED NOT NULL,
  'zone_id' bigint(20) UNSIGNED NOT NULL,
  'area' enum('A','B') NOT NULL,
  'name' text NOT NULL,
  'is_available' tinyint(1) NOT NULL,
  'qr_code' text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table parking_spaces
--

INSERT INTO parking_spaces (id, zone_id, area, name, is_available, qr_code) VALUES
(1, 1, 'A', 'A1-1', 0, ''),
(2, 1, 'A', 'A1-2', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table parking_status
--

CREATE TABLE parking_status (
  id bigint(20) UNSIGNED NOT NULL,
  parking_id bigint(20) UNSIGNED NOT NULL,
  status enum('open','close','maintenance') NOT NULL,
  start_time datetime NOT NULL,
  end_time datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table parking_zones
--

CREATE TABLE parking_zones (
  id bigint(20) UNSIGNED NOT NULL,
  name text NOT NULL,
  status enum('Open','Closed','Under maintenance') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table parking_zones
--

INSERT INTO parking_zones (id, name, status) VALUES
(1, 'A1', 'Closed'),
(2, 'A2', 'Under maintenance'),
(3, 'A3', 'Closed'),
(4, 'B1', 'Closed'),
(5, 'B2', 'Closed'),
(6, 'B3', 'Closed');

-- --------------------------------------------------------

--
-- Table structure for table reservations
--

CREATE TABLE reservations (
  id bigint(20) UNSIGNED NOT NULL,
  user_id bigint(20) UNSIGNED NOT NULL,
  parking_id bigint(20) UNSIGNED NOT NULL,
  booking_time datetime NOT NULL,
  duration double NOT NULL,
  qr_code text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table users
--

CREATE TABLE users (
  id bigint(20) UNSIGNED NOT NULL,
  username text NOT NULL,
  password text NOT NULL,
  user_type enum('student','admin','staff') NOT NULL,
  first_name text NOT NULL,
  last_name text NOT NULL,
  contact_number text NOT NULL,
  address text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table summons
--

CREATE TABLE summons(
  id bigint(20) UNSIGNED NOT NULL,
  vehicle_id UNSIGNED NOT NULL,
  summon_date text NOT NULL,
  qr_code text NOT NULL
  username text NOT NULL,
  violation_type enum('parking violation','not comply traffic regulation','accident cause') NOT NULL,
  merit_points enum('10','15','20') NOT NULL,
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Dumping data for table summons
--

INSERT INTO users (id, vehicle_id, summon_date, qr_code, username,violation_type, merit_points) VALUES
('SM24001','VLE 8639', '18/6/24', 'QRcode::png($text, $file, $ecc, $pixel_Size, $frame_Size);', 'Ahmad Toib', 'Parking Violation', '10'),
('SM24003','MON 0512', '25/6/24', 'QRcode::png($text, $file, $ecc, $pixel_Size, $frame_Size);', 'Danish Malik', 'Parking Violation', '10'),
('SM24006','BLR 5465', '30/6/24', 'QRcode::png($text, $file, $ecc, $pixel_Size, $frame_Size);', 'staff76', 'Traffic Violation', '15'),
('SM24008','ALU 1234', '6/7/24', 'QRcode::png($text, $file, $ecc, $pixel_Size, $frame_Size);', 'Abdul Razaq', 'Accident Cause', '20'),
-- --------------------------------------------------------

--
-- Table structure for table merit_points
--
--

CREATE TABLE merit_points(
  id bigint(20) UNSIGNED NOT NULL,
  users id UNSIGNED NOT NULL,
  vehicle id UNSIGNED NOT NULL,
  total_points text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Dumping data for table users
--

INSERT INTO users (id, username, password, user_type, first_name, last_name, contact_number, address) VALUES
(1, 'mangkuk', '$2y$10$/Ga/GYo53JenXvCS2KiWOOE3AZZ/ckM.DsMFLbU2/EW8OJdIK638K', 'admin', 'Mohd Mankuk', 'bin Lejen', '999', 'NO 1'),
(3, 'staff01', '$2y$10$ioms9iOh9tH8HHCrvTJOV.nJh0EviRSMv1a7o/DHNuW3d/abbGuIu', 'staff', 'staff', 'ayah', '999', '1'),
(4, 'staff02', '$2y$10$4C9MeAgNINN3jSg6O5AnsOahBMvacqKezaROfDC9wPLkU293l.qvq', 'staff', 'staff', '', '1', '1'),
(7, 'fwz', '$2y$10$DqzME3XrcL7mplQLDzwpF.JgmdxKZ2jDmhwdTfHQ0yPoHpfgLZ52S', 'admin', 'fawwaz', 'hatmi', '01159912744', 'UMP');

-- --------------------------------------------------------

--
-- Table structure for table vehicles
--

CREATE TABLE vehicles (
  id bigint(20) UNSIGNED NOT NULL,
  user_id bigint(20) UNSIGNED NOT NULL,
  vehicle_type text NOT NULL,
  vehicle_model text NOT NULL,
  vehicle_plate text NOT NULL,
  vehicle_grant text NOT NULL,
  qr_code text NOT NULL,
  approval_status tinyint(1) NOT NULL,
  approved_by bigint(20) UNSIGNED DEFAULT NULL,
  approval_date datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table parking_spaces
--
ALTER TABLE parking_spaces
  ADD PRIMARY KEY (id),
  ADD KEY fk_zone_id (zone_id);

--
-- Indexes for table parking_status
--
ALTER TABLE parking_status
  ADD PRIMARY KEY (id),
  ADD KEY fk_parking_id (parking_id);

--
-- Indexes for table parking_zones
--
ALTER TABLE parking_zones
  ADD PRIMARY KEY (id);

--
-- Indexes for table reservations
--
ALTER TABLE reservations
  ADD PRIMARY KEY (id),
  ADD KEY fk_reservation_user_id (user_id),
  ADD KEY fk_reservation_parking_id (parking_id);

--
-- Indexes for table users
--
ALTER TABLE users
  ADD PRIMARY KEY (id);

--
-- Indexes for table vehicles
--
ALTER TABLE vehicles
  ADD PRIMARY KEY (id),
  
  ADD KEY fk_approved_by (approved_by);

--
-- Indexes for table summons
--
ALTER TABLE summons
  ADD PRIMARY KEY (id),
  ADD KEY fk_vehicle_id (vehicle_id),
  ADD KEY fk_merit_points_id (merit_points_id);

--
-- Indexes for table merit_points
--
ALTER TABLE merit_points
  ADD PRIMARY KEY (id),
  ADD KEY fk_vehicle_id (vehicle_id),
  ADD KEY fk_user_id (user_id),

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table parking_spaces
--
ALTER TABLE 'parking_spaces'
  MODIFY 'id' bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table parking_status
--
ALTER TABLE 'parking_status'
  MODIFY 'id' bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table parking_zones
--
ALTER TABLE 'parking_zones'
  MODIFY 'id' bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table reservations
--
ALTER TABLE 'reservations'
  MODIFY 'id' bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table users
--
ALTER TABLE 'users'
  MODIFY 'id' bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table vehicles
--
ALTER TABLE 'vehicles'
  MODIFY 'id' bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
--AUTO_INCREMENT for table summons
--
ALTER TABLE 'summons'
  MODIFY 'id' bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
--UTO_INCREMENT for table merit_points
--
ALTER TABLE 'merit_points'
  MODIFY 'id' bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table parking_spaces
--
ALTER TABLE parking_spaces
  ADD CONSTRAINT 'fk_zone_id' FOREIGN KEY ('zone_id') REFERENCES 'parking_zones' (id);

--
-- Constraints for table parking_status
--
ALTER TABLE parking_status
  ADD CONSTRAINT 'fk_parking_id' FOREIGN KEY ('parking_id') REFERENCES 'parking_spaces' (id);

--
-- Constraints for table reservations
--
ALTER TABLE reservations
  ADD CONSTRAINT 'fk_reservation_parking_id' FOREIGN KEY ('parking_id') REFERENCES 'parking_spaces' (id),
  ADD CONSTRAINT 'fk_reservation_user_id' FOREIGN KEY ('user_id') REFERENCES 'users' (id);

--
-- Constraints for table vehicles
--
ALTER TABLE vehicles
  ADD CONSTRAINT 'fk_approved_by' FOREIGN KEY ('approved_by') REFERENCES 'users' (id),
  ADD CONSTRAINT 'fk_user_id' FOREIGN KEY ('user_id') REFERENCES 'users' (id);
COMMIT;

--
-- Constraints for table summon
--
ALTER TABLE summon
  ADD CONSTRAINT 'fk_vehicles_id' FOREIGN KEY ('vehicles_id') REFERENCES 'vehicles' (id),
  ADD CONSTRAINT 'fk_merit_points_id' FOREIGN KEY ('merit_points_id') REFERENCES 'merit_points_id' (id);
COMMIT;

--
-- Constraints for table merit_point
--
ALTER TABLE 'merit_point'
  ADD CONSTRAINT 'fk_vehicle_id' FOREIGN KEY ('vehicle_id') REFERENCES 'vehicle'(id),
  ADD CONSTRAINT 'fk_user_id' FOREIGN KEY ('user_id') REFERENCES 'users' (id);
COMMIT;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
