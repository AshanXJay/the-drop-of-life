-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 03, 2025 at 06:15 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `the_drop_of_life`
--

-- --------------------------------------------------------

--
-- Table structure for table `camps`
--

CREATE TABLE `camps` (
  `camp_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL,
  `district` varchar(100) DEFAULT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `organizer` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `camps`
--

INSERT INTO `camps` (`camp_id`, `name`, `location`, `district`, `date`, `time`, `organizer`, `created_at`) VALUES
(1, 'Blood Drive 2025 - Colombo', 'National Hospital Colombo', 'Colombo', '2025-08-15', '09:00:00', 'Red Cross Society', '2025-08-03 05:49:02'),
(2, 'Life Saver Camp - Kandy', 'Teaching Hospital Kandy', 'Kandy', '2025-08-20', '08:30:00', 'Lions Club Kandy', '2025-08-03 05:49:02'),
(3, 'Emergency Blood Collection', 'Base Hospital Gampaha', 'Gampaha', '2025-08-25', '10:00:00', 'Blood Bank Gampaha', '2025-08-03 05:49:02'),
(4, 'Community Blood Drive', 'District Hospital Kurunegala', 'Kurunegala', '2025-09-01', '09:30:00', 'Rotary Club', '2025-08-03 05:49:02'),
(5, 'University Blood Camp', 'University of Peradeniya', 'Kandy', '2025-09-05', '08:00:00', 'Medical Faculty', '2025-08-03 05:49:02'),
(6, 'Corporate Blood Drive', 'Dialog Axiata Headquarters', 'Colombo', '2025-09-10', '10:30:00', 'Dialog CSR Team', '2025-08-03 05:49:02'),
(7, 'School Blood Camp', 'Royal College Colombo', 'Colombo', '2025-09-15', '09:00:00', 'Old Boys Association', '2025-08-03 05:49:02'),
(8, 'Temple Blood Drive', 'Kelaniya Raja Maha Viharaya', 'Gampaha', '2025-09-20', '07:30:00', 'Temple Committee', '2025-08-03 05:49:02'),
(9, 'Coastal Blood Collection', 'Base Hospital Matara', 'Matara', '2025-09-25', '08:30:00', 'Matara Medical Association', '2025-08-03 05:49:02'),
(10, 'Northern Province Drive', 'Teaching Hospital Jaffna', 'Jaffna', '2025-10-01', '09:00:00', 'Jaffna Medical Society', '2025-08-03 05:49:02');

-- --------------------------------------------------------

--
-- Table structure for table `camp_registrations`
--

CREATE TABLE `camp_registrations` (
  `registration_id` int(11) NOT NULL,
  `donor_id` int(11) DEFAULT NULL,
  `camp_id` int(11) DEFAULT NULL,
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `camp_registrations`
--

INSERT INTO `camp_registrations` (`registration_id`, `donor_id`, `camp_id`, `registered_at`) VALUES
(1, 1, 1, '2025-07-25 09:00:00'),
(2, 2, 1, '2025-07-25 09:00:00'),
(3, 3, 1, '2025-07-25 09:00:00'),
(4, 9, 1, '2025-08-03 05:49:02'),
(5, 10, 1, '2025-08-03 05:49:02'),
(6, 2, 2, '2025-07-26 04:45:00'),
(7, 5, 2, '2025-07-26 04:45:00'),
(8, 8, 2, '2025-07-26 04:45:00'),
(9, 11, 2, '2025-08-03 05:49:02'),
(10, 3, 3, '2025-08-03 05:49:02'),
(11, 6, 3, '2025-07-27 11:15:00'),
(12, 13, 3, '2025-07-27 11:15:00'),
(13, 14, 3, '2025-07-27 11:15:00'),
(14, 4, 4, '2025-08-03 05:49:02'),
(15, 7, 4, '2025-08-03 05:49:02'),
(16, 12, 4, '2025-07-28 03:50:00'),
(17, 15, 4, '2025-07-28 03:50:00'),
(18, 1, 5, '2025-07-28 03:50:00'),
(19, 5, 5, '2025-08-03 05:49:02'),
(20, 8, 5, '2025-08-03 05:49:02'),
(21, 11, 5, '2025-07-29 06:15:00'),
(22, 2, 5, '2025-07-29 06:15:00'),
(23, 9, 6, '2025-07-29 06:15:00'),
(24, 10, 6, '2025-08-03 05:49:02'),
(25, 13, 6, '2025-08-03 05:49:02'),
(26, 1, 6, '2025-08-03 05:49:02'),
(27, 3, 7, '2025-08-03 05:49:02'),
(28, 6, 7, '2025-08-03 05:49:02'),
(29, 14, 7, '2025-08-03 05:49:02'),
(30, 12, 8, '2025-08-03 05:49:02'),
(31, 15, 8, '2025-08-03 05:49:02'),
(32, 4, 8, '2025-08-03 05:49:02'),
(33, 7, 8, '2025-08-03 05:49:02'),
(34, 5, 9, '2025-08-03 05:49:02'),
(35, 8, 9, '2025-08-03 05:49:02'),
(36, 11, 9, '2025-08-03 05:49:02'),
(37, 10, 10, '2025-08-03 05:49:02'),
(38, 2, 10, '2025-08-03 05:49:02'),
(39, 6, 10, '2025-08-03 05:49:02');

-- --------------------------------------------------------

--
-- Table structure for table `donors`
--

CREATE TABLE `donors` (
  `donor_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `blood_type` varchar(5) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donors`
--

INSERT INTO `donors` (`donor_id`, `name`, `email`, `phone`, `blood_type`, `district`, `created_at`) VALUES
(1, 'Ashan Jayawardana', 'ashan@email.com', '0771234567', 'O+', 'Colombo', '2025-08-03 05:49:02'),
(2, 'Nimali Perera', 'nimali.perera@gmail.com', '0772345678', 'A+', 'Kandy', '2025-08-03 05:49:02'),
(3, 'Kasun Silva', 'kasun.silva@yahoo.com', '0773456789', 'B+', 'Gampaha', '2025-08-03 05:49:02'),
(4, 'Sanduni Fernando', 'sanduni.f@outlook.com', '0774567890', 'AB+', 'Kurunegala', '2025-08-03 05:49:02'),
(5, 'Thilaka Rajapaksa', 'thilaka.r@email.com', '0775678901', 'O-', 'Matara', '2025-08-03 05:49:02'),
(6, 'Priyantha Wickramasinghe', 'priyantha.w@gmail.com', '0776789012', 'A-', 'Anuradhapura', '2025-08-03 05:49:02'),
(7, 'Chamari Dissanayake', 'chamari.d@email.com', '0777890123', 'B-', 'Ratnapura', '2025-08-03 05:49:02'),
(8, 'Ruwan Gunasekara', 'ruwan.g@gmail.com', '0778901234', 'AB-', 'Badulla', '2025-08-03 05:49:02'),
(9, 'Sachini Mendis', 'sachini.m@email.com', '0779012345', 'O+', 'Galle', '2025-08-03 05:49:02'),
(10, 'Dinesh Karunaratne', 'dinesh.k@yahoo.com', '0770123456', 'A+', 'Jaffna', '2025-08-03 05:49:02'),
(11, 'Malika Seneviratne', 'malika.s@gmail.com', '0781234567', 'B+', 'Polonnaruwa', '2025-08-03 05:49:02'),
(12, 'Chaminda Lakmal', 'chaminda.l@email.com', '0782345678', 'O+', 'Hambantota', '2025-08-03 05:49:02'),
(13, 'Dilani Wijesinghe', 'dilani.w@outlook.com', '0783456789', 'A-', 'Kalutara', '2025-08-03 05:49:02'),
(14, 'Sampath Bandara', 'sampath.b@gmail.com', '0784567890', 'AB+', 'Kegalle', '2025-08-03 05:49:02'),
(15, 'Nayomi Ranasinghe', 'nayomi.r@email.com', '0785678901', 'O-', 'Nuwara Eliya', '2025-08-03 05:49:02');

-- --------------------------------------------------------

--
-- Table structure for table `emergency_requests`
--

CREATE TABLE `emergency_requests` (
  `request_id` int(11) NOT NULL,
  `donor_id` int(11) DEFAULT NULL,
  `blood_type` varchar(5) NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `contact` varchar(50) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emergency_requests`
--

INSERT INTO `emergency_requests` (`request_id`, `donor_id`, `blood_type`, `location`, `contact`, `note`, `requested_at`) VALUES
(1, NULL, 'O-', 'National Hospital Colombo', '0112691111', 'Urgent requirement for accident victim. Universal donor needed immediately.', '2025-07-31 03:00:00'),
(2, 5, 'A+', 'Teaching Hospital Kandy', '0812232337', 'Patient undergoing major surgery. Family member registered as backup donor.', '2025-07-30 16:45:00'),
(3, NULL, 'B+', 'Colombo South Teaching Hospital', '0112740700', 'Thalassemia patient requires regular transfusion. Multiple units needed.', '2025-07-29 08:50:00'),
(4, 8, 'AB+', 'Lady Ridgeway Hospital', '0112693711', 'Pediatric emergency case. Rare blood type required urgently.', '2025-07-31 03:00:00'),
(5, NULL, 'O+', 'Base Hospital Gampaha', '0332223261', 'Road traffic accident. Multiple casualties need immediate blood supply.', '2025-07-31 03:00:00'),
(6, 12, 'A-', 'Teaching Hospital Peradeniya', '0812388047', 'Cancer patient chemotherapy support. Regular donor available.', '2025-07-30 16:45:00'),
(7, NULL, 'B-', 'General Hospital Ratnapura', '0452222261', 'Pregnancy complications. Rare blood type needed for mother.', '2025-07-29 08:50:00'),
(8, 3, 'O+', 'Base Hospital Matara', '0412222261', 'Emergency surgery case. Common blood type but large quantity needed.', '2025-07-29 08:50:00'),
(9, NULL, 'AB-', 'Teaching Hospital Jaffna', '0212222261', 'Very rare blood type emergency. Seeking any compatible donors.', '2025-07-31 03:00:00'),
(10, 7, 'A+', 'District General Hospital Badulla', '0552222324', 'Dengue patient with low platelet count. Platelet-rich blood needed.', '2025-07-29 08:50:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `camps`
--
ALTER TABLE `camps`
  ADD PRIMARY KEY (`camp_id`);

--
-- Indexes for table `camp_registrations`
--
ALTER TABLE `camp_registrations`
  ADD PRIMARY KEY (`registration_id`),
  ADD KEY `donor_id` (`donor_id`),
  ADD KEY `camp_id` (`camp_id`);

--
-- Indexes for table `donors`
--
ALTER TABLE `donors`
  ADD PRIMARY KEY (`donor_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `emergency_requests`
--
ALTER TABLE `emergency_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `donor_id` (`donor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `camps`
--
ALTER TABLE `camps`
  MODIFY `camp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `camp_registrations`
--
ALTER TABLE `camp_registrations`
  MODIFY `registration_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `donors`
--
ALTER TABLE `donors`
  MODIFY `donor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `emergency_requests`
--
ALTER TABLE `emergency_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `camp_registrations`
--
ALTER TABLE `camp_registrations`
  ADD CONSTRAINT `camp_registrations_ibfk_1` FOREIGN KEY (`donor_id`) REFERENCES `donors` (`donor_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `camp_registrations_ibfk_2` FOREIGN KEY (`camp_id`) REFERENCES `camps` (`camp_id`) ON DELETE CASCADE;

--
-- Constraints for table `emergency_requests`
--
ALTER TABLE `emergency_requests`
  ADD CONSTRAINT `emergency_requests_ibfk_1` FOREIGN KEY (`donor_id`) REFERENCES `donors` (`donor_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
