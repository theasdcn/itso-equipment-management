-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 29, 2025 at 01:04 PM
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
-- Database: `itso_equipment_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `borrow_requests`
--

CREATE TABLE `borrow_requests` (
  `borrow_id` int(11) NOT NULL,
  `equipment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `purpose` enum('class','lab_experiment','research','academic_activity') NOT NULL,
  `professor_name` varchar(100) NOT NULL,
  `room_number` varchar(50) NOT NULL,
  `borrow_date` date NOT NULL,
  `expected_return_date` date NOT NULL,
  `academic_subject` varchar(100) NOT NULL,
  `status` enum('pending','approved','rejected','borrowed','returned','cancelled') DEFAULT 'pending',
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `returned_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrow_requests`
--

INSERT INTO `borrow_requests` (`borrow_id`, `equipment_id`, `student_id`, `purpose`, `professor_name`, `room_number`, `borrow_date`, `expected_return_date`, `academic_subject`, `status`, `approved_by`, `approved_at`, `returned_at`, `created_at`, `updated_at`) VALUES
(1, 26, 4, '', 'N/A', 'N/A', '2025-11-28', '2025-11-29', 'General Use', 'borrowed', NULL, NULL, NULL, '2025-11-28 23:02:59', '2025-11-28 23:02:59'),
(2, 6, 8, '', 'N/A', 'N/A', '2025-11-29', '2025-11-30', 'Associate Use', 'borrowed', NULL, NULL, NULL, '2025-11-29 07:54:43', '2025-11-29 07:54:43'),
(3, 6, 8, '', 'Hannah Pablo', 'Lab Room A', '2025-11-29', '2025-12-05', 'Computer Science', 'pending', NULL, NULL, NULL, '2025-11-29 07:56:48', '2025-11-29 07:56:48'),
(4, 6, 9, '', 'N/A', 'N/A', '2025-11-29', '2025-11-29', 'General Use', 'returned', NULL, NULL, '2025-11-29 10:54:39', '2025-11-29 08:02:17', '2025-11-29 10:54:39'),
(5, 6, 11, '', 'N/A', 'N/A', '2025-11-29', '2025-11-30', 'Associate Use', 'returned', NULL, NULL, '2025-11-29 10:50:56', '2025-11-29 10:48:03', '2025-11-29 10:50:56'),
(6, 6, 11, '', 'N/A', 'N/A', '2025-11-29', '2025-11-30', 'Associate Use', 'returned', NULL, NULL, '2025-11-29 10:51:32', '2025-11-29 10:51:15', '2025-11-29 10:51:32');

-- --------------------------------------------------------

--
-- Table structure for table `equipment`
--

CREATE TABLE `equipment` (
  `equipment_id` int(11) NOT NULL,
  `equipment_code` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `specifications` text DEFAULT NULL,
  `total_quantity` int(11) NOT NULL DEFAULT 0,
  `available_quantity` int(11) NOT NULL DEFAULT 0,
  `in_use_quantity` int(11) NOT NULL DEFAULT 0,
  `under_maintenance` int(11) NOT NULL DEFAULT 0,
  `status` enum('available','unavailable','maintenance','retired') DEFAULT 'available',
  `location` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `equipment`
--

INSERT INTO `equipment` (`equipment_id`, `equipment_code`, `name`, `category`, `description`, `specifications`, `total_quantity`, `available_quantity`, `in_use_quantity`, `under_maintenance`, `status`, `location`, `notes`, `image`, `thumbnail`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'EQFD7B8F', 'Dell Latitude 5520 Laptop', 'Laptops', 'Dell Latitude 5520 business laptop with Intel Core i5 processor, 8GB RAM, 256GB SSD. Includes original charger.', 'Intel Core i5-1135G7, 8GB RAM, 256GB SSD, 15.6\" FHD Display, Windows 11 Pro', 12, 12, 0, 0, 'available', 'ITSO Storage Room A', 'Includes Dell 65W AC adapter charger', '1764360341_692a009547659.jpg', 'thumb_1764360341_692a009547659.jpg', NULL, NULL, '2025-11-28 19:34:02', '2025-11-28 20:05:41'),
(2, 'EQ0BD853', 'HP EliteBook 840 G8 Laptop', 'Laptops', 'HP EliteBook 840 G8 with Intel Core i7, 16GB RAM, 512GB SSD. Comes with HP USB-C charger.', 'Intel Core i7-1165G7, 16GB RAM, 512GB SSD, 14\" FHD Display, Windows 11 Pro', 10, 10, 0, 0, 'available', 'ITSO Storage Room A', 'Includes HP 65W USB-C charger', '1764360343_692a0097f0745.jpg', 'thumb_1764360343_692a0097f0745.jpg', NULL, NULL, '2025-11-28 19:34:02', '2025-11-28 20:05:44'),
(3, 'EQ6F39A3', 'Lenovo ThinkPad X1 Carbon', 'Laptops', 'Lenovo ThinkPad X1 Carbon Gen 9 ultrabook with Intel Core i5, 8GB RAM, 256GB SSD. Includes Lenovo charger.', 'Intel Core i5-1135G7, 8GB RAM, 256GB SSD, 14\" FHD Display, Windows 11 Pro', 8, 8, 0, 0, 'available', 'ITSO Storage Room A', 'Includes Lenovo 65W AC adapter', '1764360346_692a009a50ae3.jpg', 'thumb_1764360346_692a009a50ae3.jpg', NULL, NULL, '2025-11-28 19:34:02', '2025-11-28 20:05:46'),
(4, 'EQ760DF7', 'MacBook Air M1', 'Laptops', 'Apple MacBook Air with M1 chip, 8GB RAM, 256GB SSD. Includes MagSafe charger.', 'Apple M1 Chip, 8GB RAM, 256GB SSD, 13.3\" Retina Display, macOS Monterey', 6, 6, 0, 0, 'available', 'Mac Lab Storage', 'Includes Apple 30W USB-C Power Adapter', '1764360348_692a009c751f9.jpg', 'thumb_1764360348_692a009c751f9.jpg', NULL, NULL, '2025-11-28 19:34:02', '2025-11-28 20:05:48'),
(5, 'EQ153E44', 'ASUS VivoBook 15', 'Laptops', 'ASUS VivoBook 15 with AMD Ryzen 5, 8GB RAM, 512GB SSD. Includes ASUS charger.', 'AMD Ryzen 5 5500U, 8GB RAM, 512GB SSD, 15.6\" FHD Display, Windows 11', 10, 10, 0, 0, 'available', 'ITSO Storage Room A', 'Includes ASUS 45W AC adapter', '1764360350_692a009e8dca8.jpg', 'thumb_1764360350_692a009e8dca8.jpg', NULL, NULL, '2025-11-28 19:34:02', '2025-11-28 20:05:50'),
(6, 'EQ7842F2', 'Acer Aspire 5', 'Laptops', 'Acer Aspire 5 with Intel Core i5, 8GB RAM, 256GB SSD. Includes Acer charger.', 'Intel Core i5-1135G7, 8GB RAM, 256GB SSD, 15.6\" FHD Display, Windows 11', 9, 8, 1, 0, 'available', 'ITSO Storage Room A', 'Includes Acer 65W AC adapter', '1764360353_692a00a149c26.jpg', 'thumb_1764360353_692a00a149c26.jpg', NULL, NULL, '2025-11-28 19:34:02', '2025-11-29 10:54:40'),
(7, 'EQ3D9604', 'Microsoft Surface Laptop 4', 'Laptops', 'Microsoft Surface Laptop 4 with Intel Core i5, 8GB RAM, 256GB SSD. Includes Surface charger.', 'Intel Core i5-1135G7, 8GB RAM, 256GB SSD, 13.5\" PixelSense Display, Windows 11', 7, 7, 0, 0, 'available', 'ITSO Storage Room A', 'Includes Microsoft Surface Connect charger', '1764360355_692a00a3701ea.jpg', 'thumb_1764360355_692a00a3701ea.jpg', NULL, NULL, '2025-11-28 19:34:02', '2025-11-28 20:05:55'),
(8, 'EQ3D6766', 'Dell Inspiron 15 3000', 'Laptops', 'Dell Inspiron 15 3000 with Intel Core i3, 8GB RAM, 256GB SSD. Includes Dell charger.', 'Intel Core i3-1115G4, 8GB RAM, 256GB SSD, 15.6\" HD Display, Windows 11', 11, 11, 0, 0, 'available', 'ITSO Storage Room A', 'Includes Dell 45W AC adapter', '1764360357_692a00a55293a.jpg', 'thumb_1764360357_692a00a55293a.jpg', NULL, NULL, '2025-11-28 19:34:02', '2025-11-28 20:05:57'),
(9, 'EQC03A5C', 'Epson PowerLite X41+ DLP Projector', 'DLP Projectors', 'Epson PowerLite X41+ DLP projector with 3600 lumens, includes extension cord, VGA/HDMI cable, and power cable.', '3600 lumens, XGA resolution (1024x768), 3LCD technology, HDMI, VGA inputs', 8, 8, 0, 0, 'available', 'ITSO Storage Room B', 'Includes 10ft extension cord, VGA/HDMI cable, and power cable', '1764360359_692a00a7c377b.jpg', 'thumb_1764360359_692a00a7c377b.jpg', NULL, NULL, '2025-11-28 19:34:02', '2025-11-28 20:05:59'),
(10, 'EQ83B7F2', 'BenQ MX550 DLP Projector', 'DLP Projectors', 'BenQ MX550 DLP projector with 3600 lumens, includes extension cord, VGA/HDMI cable, and power cable.', '3600 lumens, XGA resolution (1024x768), DLP technology, HDMI, VGA inputs', 7, 7, 0, 0, 'available', 'ITSO Storage Room B', 'Includes 10ft extension cord, VGA/HDMI cable, and power cable', '1764360362_692a00aa15aae.jpg', 'thumb_1764360362_692a00aa15aae.jpg', NULL, NULL, '2025-11-28 19:34:02', '2025-11-28 20:06:02'),
(11, 'EQE8FA9B', 'Optoma X341 DLP Projector', 'DLP Projectors', 'Optoma X341 DLP projector with 3600 lumens, includes extension cord, VGA/HDMI cable, and power cable.', '3600 lumens, XGA resolution (1024x768), DLP technology, HDMI, VGA inputs', 6, 6, 0, 0, 'available', 'ITSO Storage Room B', 'Includes 10ft extension cord, VGA/HDMI cable, and power cable', '1764360364_692a00ac5c45d.jpg', 'thumb_1764360364_692a00ac5c45d.jpg', NULL, NULL, '2025-11-28 19:34:02', '2025-11-28 20:06:04'),
(12, 'EQFECA82', 'ViewSonic PA503S DLP Projector', 'DLP Projectors', 'ViewSonic PA503S DLP projector with 3600 lumens, includes extension cord, VGA/HDMI cable, and power cable.', '3600 lumens, SVGA resolution (800x600), DLP technology, HDMI, VGA inputs', 8, 8, 0, 0, 'available', 'ITSO Storage Room B', 'Includes 10ft extension cord, VGA/HDMI cable, and power cable', '1764360366_692a00aec4ee4.jpg', 'thumb_1764360366_692a00aec4ee4.jpg', NULL, NULL, '2025-11-28 19:34:02', '2025-11-28 20:06:06'),
(13, 'EQ0A7682', 'Acer X118H DLP Projector', 'DLP Projectors', 'Acer X118H DLP projector with 3800 lumens, includes extension cord, VGA/HDMI cable, and power cable.', '3800 lumens, XGA resolution (1024x768), DLP technology, HDMI, VGA inputs', 7, 7, 0, 0, 'available', 'ITSO Storage Room B', 'Includes 10ft extension cord, VGA/HDMI cable, and power cable', '1764360369_692a00b10abd2.jpg', 'thumb_1764360369_692a00b10abd2.jpg', NULL, NULL, '2025-11-28 19:34:02', '2025-11-28 20:06:09'),
(14, 'EQFA7B77', 'Epson EX3280 DLP Projector', 'DLP Projectors', 'Epson EX3280 DLP projector with 3600 lumens, includes extension cord, VGA/HDMI cable, and power cable.', '3600 lumens, XGA resolution (1024x768), 3LCD technology, HDMI, VGA inputs', 9, 9, 0, 0, 'available', 'ITSO Storage Room B', 'Includes 10ft extension cord, VGA/HDMI cable, and power cable', '1764360372_692a00b42bf6b.jpg', 'thumb_1764360372_692a00b42bf6b.jpg', NULL, NULL, '2025-11-28 19:34:02', '2025-11-28 20:06:12'),
(15, 'EQFDF8BD', 'BenQ MW536 DLP Projector', 'DLP Projectors', 'BenQ MW536 DLP projector with 3500 lumens, includes extension cord, VGA/HDMI cable, and power cable.', '3500 lumens, XGA resolution (1024x768), DLP technology, HDMI, VGA inputs', 6, 6, 0, 0, 'available', 'ITSO Storage Room B', 'Includes 10ft extension cord, VGA/HDMI cable, and power cable', '1764360374_692a00b69055c.jpg', 'thumb_1764360374_692a00b69055c.jpg', NULL, NULL, '2025-11-28 19:34:02', '2025-11-28 20:06:14'),
(16, 'EQA1A9D9', 'Optoma EH412 DLP Projector', 'DLP Projectors', 'Optoma EH412 DLP projector with 4000 lumens, includes extension cord, VGA/HDMI cable, and power cable.', '4000 lumens, XGA resolution (1024x768), DLP technology, HDMI, VGA inputs', 8, 8, 0, 0, 'available', 'ITSO Storage Room B', 'Includes 10ft extension cord, VGA/HDMI cable, and power cable', '1764360377_692a00b931d3c.jpg', 'thumb_1764360377_692a00b931d3c.jpg', NULL, NULL, '2025-11-28 19:34:02', '2025-11-28 20:06:17'),
(17, 'EQE70F9D', 'HDMI Cable 6ft (High Speed)', 'HDMI and VGA Cables', 'High-speed HDMI cable 6 feet long, supports 4K resolution and audio transmission.', '6ft length, HDMI 2.0, 4K@60Hz support, gold-plated connectors', 25, 25, 0, 0, 'available', 'ITSO Cable Storage', 'Compatible with all HDMI devices', '1764360379_692a00bb1f5bf.jpg', 'thumb_1764360379_692a00bb1f5bf.jpg', NULL, NULL, '2025-11-28 19:34:02', '2025-11-28 20:06:19'),
(18, 'EQ325BBA', 'HDMI Cable 10ft (High Speed)', 'HDMI and VGA Cables', 'High-speed HDMI cable 10 feet long, supports 4K resolution and audio transmission.', '10ft length, HDMI 2.0, 4K@60Hz support, gold-plated connectors', 20, 20, 0, 0, 'available', 'ITSO Cable Storage', 'Compatible with all HDMI devices', '1764360382_692a00be9c1ea.jpg', 'thumb_1764360382_692a00be9c1ea.jpg', NULL, NULL, '2025-11-28 19:34:02', '2025-11-28 20:06:22'),
(19, 'EQ96469F', 'HDMI Cable 15ft (High Speed)', 'HDMI and VGA Cables', 'High-speed HDMI cable 15 feet long, supports 4K resolution and audio transmission.', '15ft length, HDMI 2.0, 4K@60Hz support, gold-plated connectors', 15, 15, 0, 0, 'available', 'ITSO Cable Storage', 'Compatible with all HDMI devices', '1764360384_692a00c08d4f8.jpg', 'thumb_1764360384_692a00c08d4f8.jpg', NULL, NULL, '2025-11-28 19:34:02', '2025-11-28 20:06:24'),
(20, 'EQ8777C4', 'VGA Cable 6ft', 'HDMI and VGA Cables', 'VGA cable 6 feet long for connecting computers to projectors and monitors.', '6ft length, VGA connector, 15-pin male to male', 30, 30, 0, 0, 'available', 'ITSO Cable Storage', 'Standard VGA cable for projectors', '1764360387_692a00c30fbfa.jpg', 'thumb_1764360387_692a00c30fbfa.jpg', NULL, NULL, '2025-11-28 19:34:02', '2025-11-28 20:06:27'),
(21, 'EQ1597D0', 'VGA Cable 10ft', 'HDMI and VGA Cables', 'VGA cable 10 feet long for connecting computers to projectors and monitors.', '10ft length, VGA connector, 15-pin male to male', 25, 25, 0, 0, 'available', 'ITSO Cable Storage', 'Standard VGA cable for projectors', '1764360388_692a00c4c7069.jpg', 'thumb_1764360388_692a00c4c7069.jpg', NULL, NULL, '2025-11-28 19:34:02', '2025-11-28 20:06:28'),
(22, 'EQDFCDE9', 'VGA Cable 15ft', 'HDMI and VGA Cables', 'VGA cable 15 feet long for connecting computers to projectors and monitors.', '15ft length, VGA connector, 15-pin male to male', 20, 20, 0, 0, 'available', 'ITSO Cable Storage', 'Standard VGA cable for projectors', '1764360390_692a00c6e1f7e.jpg', 'thumb_1764360390_692a00c6e1f7e.jpg', NULL, NULL, '2025-11-28 19:34:02', '2025-11-28 20:06:31'),
(23, 'EQ745728', 'HDMI to VGA Adapter Cable', 'HDMI and VGA Cables', 'HDMI to VGA adapter cable for connecting HDMI devices to VGA displays.', 'HDMI male to VGA female adapter, 6ft cable, supports 1080p', 12, 12, 0, 0, 'available', 'ITSO Cable Storage', 'Active adapter with signal conversion', '1764360393_692a00c94aca9.jpg', 'thumb_1764360393_692a00c94aca9.jpg', NULL, NULL, '2025-11-28 19:34:02', '2025-11-28 20:06:33'),
(24, 'EQ4C1385', 'HDMI Cable 3ft (High Speed)', 'HDMI and VGA Cables', 'High-speed HDMI cable 3 feet long, supports 4K resolution and audio transmission.', '3ft length, HDMI 2.0, 4K@60Hz support, gold-plated connectors', 30, 30, 0, 0, 'available', 'ITSO Cable Storage', 'Compatible with all HDMI devices', '1764360395_692a00cb064a3.jpg', 'thumb_1764360395_692a00cb064a3.jpg', NULL, NULL, '2025-11-28 19:34:02', '2025-11-28 20:06:35'),
(25, 'EQ231376', 'Epson Projector Remote Control', 'DLP Remote Controls', 'Universal remote control for Epson DLP projectors with all standard functions.', 'Infrared remote, battery operated, includes power, menu, and navigation buttons', 10, 10, 0, 0, 'available', 'ITSO Storage Room B', 'Compatible with Epson PowerLite and EX series', '1764360396_692a00ccb133c.jpg', 'thumb_1764360396_692a00ccb133c.jpg', NULL, NULL, '2025-11-28 19:34:02', '2025-11-28 20:06:36'),
(26, 'EQ10A043', 'BenQ Projector Remote Control', 'DLP Remote Controls', 'Universal remote control for BenQ DLP projectors with all standard functions.', 'Infrared remote, battery operated, includes power, menu, and navigation buttons', 9, 8, 1, 0, 'available', 'ITSO Storage Room B', 'Compatible with BenQ MX and MW series', '1764360399_692a00cf6a9ee.jpg', 'thumb_1764360399_692a00cf6a9ee.jpg', NULL, NULL, '2025-11-28 19:34:02', '2025-11-28 23:02:59'),
(27, 'EQC89748', 'Optoma Projector Remote Control', 'DLP Remote Controls', 'Universal remote control for Optoma DLP projectors with all standard functions.', 'Infrared remote, battery operated, includes power, menu, and navigation buttons', 8, 8, 0, 0, 'available', 'ITSO Storage Room B', 'Compatible with Optoma X and EH series', '1764360401_692a00d1d2ca7.jpg', 'thumb_1764360401_692a00d1d2ca7.jpg', NULL, NULL, '2025-11-28 19:34:02', '2025-11-28 20:06:41'),
(28, 'EQ550133', 'ViewSonic Projector Remote Control', 'DLP Remote Controls', 'Universal remote control for ViewSonic DLP projectors with all standard functions.', 'Infrared remote, battery operated, includes power, menu, and navigation buttons', 10, 10, 0, 0, 'available', 'ITSO Storage Room B', 'Compatible with ViewSonic PA series', '1764360404_692a00d459ef3.jpg', 'thumb_1764360404_692a00d459ef3.jpg', NULL, NULL, '2025-11-28 19:34:03', '2025-11-28 20:06:44'),
(29, 'EQD814C2', 'Acer Projector Remote Control', 'DLP Remote Controls', 'Universal remote control for Acer DLP projectors with all standard functions.', 'Infrared remote, battery operated, includes power, menu, and navigation buttons', 9, 9, 0, 0, 'available', 'ITSO Storage Room B', 'Compatible with Acer X series projectors', '1764360407_692a00d7cd7ae.jpg', 'thumb_1764360407_692a00d7cd7ae.jpg', NULL, NULL, '2025-11-28 19:34:03', '2025-11-28 20:06:48'),
(30, 'EQ1A072F', 'Universal Projector Remote Control', 'DLP Remote Controls', 'Universal remote control compatible with most DLP projectors from various brands.', 'Infrared remote, battery operated, programmable for multiple brands', 12, 12, 0, 0, 'available', 'ITSO Storage Room B', 'Works with Epson, BenQ, Optoma, ViewSonic, Acer', '1764360409_692a00d9d30d9.jpg', 'thumb_1764360409_692a00d9d30d9.jpg', NULL, NULL, '2025-11-28 19:34:03', '2025-11-28 20:06:49'),
(31, 'EQ8EF49A', 'Epson EX Series Remote Control', 'DLP Remote Controls', 'Original remote control for Epson EX series projectors.', 'Infrared remote, battery operated, original Epson part', 8, 8, 0, 0, 'available', 'ITSO Storage Room B', 'Original Epson remote for EX3280 and similar models', '1764360411_692a00db9994f.jpg', 'thumb_1764360411_692a00db9994f.jpg', NULL, NULL, '2025-11-28 19:34:03', '2025-11-28 20:06:51'),
(32, 'EQ1232C6', 'BenQ MX Series Remote Control', 'DLP Remote Controls', 'Original remote control for BenQ MX series projectors.', 'Infrared remote, battery operated, original BenQ part', 7, 7, 0, 0, 'available', 'ITSO Storage Room B', 'Original BenQ remote for MX550 and similar models', '1764360414_692a00de043b5.jpg', 'thumb_1764360414_692a00de043b5.jpg', NULL, NULL, '2025-11-28 19:34:03', '2025-11-28 20:06:54'),
(33, 'EQ6896DA', 'Apple Magic Keyboard', 'Keyboards and Mice', 'Apple Magic Keyboard for Mac with rechargeable battery. Includes Lightning cable for charging.', 'Wireless, rechargeable, scissor mechanism keys, Bluetooth connectivity', 15, 15, 0, 0, 'available', 'Mac Lab Storage', 'Includes Apple Lightning to USB cable for charging', '1764360416_692a00e0474a0.jpg', 'thumb_1764360416_692a00e0474a0.jpg', NULL, NULL, '2025-11-28 19:34:03', '2025-11-28 20:06:56'),
(34, 'EQD5C656', 'Apple Magic Mouse 2', 'Keyboards and Mice', 'Apple Magic Mouse 2 with rechargeable battery. Includes Lightning cable for charging.', 'Wireless, rechargeable, multi-touch surface, Bluetooth connectivity', 15, 15, 0, 0, 'available', 'Mac Lab Storage', 'Includes Apple Lightning to USB cable for charging', '1764360418_692a00e27f413.jpg', 'thumb_1764360418_692a00e27f413.jpg', NULL, NULL, '2025-11-28 19:34:03', '2025-11-28 20:06:58'),
(35, 'EQ1D5717', 'Apple Magic Trackpad 2', 'Keyboards and Mice', 'Apple Magic Trackpad 2 with rechargeable battery. Includes Lightning cable for charging.', 'Wireless, rechargeable, Force Touch, Bluetooth connectivity', 12, 12, 0, 0, 'available', 'Mac Lab Storage', 'Includes Apple Lightning to USB cable for charging', '1764360420_692a00e4c4469.jpg', 'thumb_1764360420_692a00e4c4469.jpg', NULL, NULL, '2025-11-28 19:34:03', '2025-11-28 20:07:00'),
(36, 'EQ706D3F', 'Logitech MX Keys for Mac', 'Keyboards and Mice', 'Logitech MX Keys wireless keyboard designed for Mac. Includes USB-C charging cable.', 'Wireless, backlit keys, USB-C rechargeable, Bluetooth and USB receiver', 10, 10, 0, 0, 'available', 'Mac Lab Storage', 'Includes USB-C to USB-A cable for charging', '1764360423_692a00e76f741.jpg', 'thumb_1764360423_692a00e76f741.jpg', NULL, NULL, '2025-11-28 19:34:03', '2025-11-28 20:07:03'),
(37, 'EQ993492', 'Logitech MX Master 3 for Mac', 'Keyboards and Mice', 'Logitech MX Master 3 wireless mouse designed for Mac. Includes USB-C charging cable.', 'Wireless, ergonomic design, USB-C rechargeable, Bluetooth and USB receiver', 10, 10, 0, 0, 'available', 'Mac Lab Storage', 'Includes USB-C to USB-A cable for charging', '1764360425_692a00e99385e.jpg', 'thumb_1764360425_692a00e99385e.jpg', NULL, NULL, '2025-11-28 19:34:03', '2025-11-28 20:07:05'),
(38, 'EQ33D10A', 'Apple Magic Keyboard with Numeric Keypad', 'Keyboards and Mice', 'Apple Magic Keyboard with numeric keypad for Mac. Includes Lightning cable for charging.', 'Wireless, rechargeable, full-size with numeric keypad, Bluetooth connectivity', 8, 8, 0, 0, 'available', 'Mac Lab Storage', 'Includes Apple Lightning to USB cable for charging', '1764360427_692a00ebd2b1e.jpg', 'thumb_1764360427_692a00ebd2b1e.jpg', NULL, NULL, '2025-11-28 19:34:03', '2025-11-28 20:07:07'),
(39, 'EQ289931', 'Apple Magic Mouse 2 (Spare)', 'Keyboards and Mice', 'Spare Apple Magic Mouse 2 with rechargeable battery. Includes Lightning cable for charging.', 'Wireless, rechargeable, multi-touch surface, Bluetooth connectivity', 12, 12, 0, 0, 'available', 'Mac Lab Storage', 'Includes Apple Lightning to USB cable for charging', '1764360430_692a00ee951e2.jpg', 'thumb_1764360430_692a00ee951e2.jpg', NULL, NULL, '2025-11-28 19:34:03', '2025-11-28 20:07:10'),
(40, 'EQ089472', 'Apple Magic Trackpad 2 (Spare)', 'Keyboards and Mice', 'Spare Apple Magic Trackpad 2 with rechargeable battery. Includes Lightning cable for charging.', 'Wireless, rechargeable, Force Touch, Bluetooth connectivity', 10, 10, 0, 0, 'available', 'Mac Lab Storage', 'Includes Apple Lightning to USB cable for charging', '1764360433_692a00f14c90f.jpg', 'thumb_1764360433_692a00f14c90f.jpg', NULL, NULL, '2025-11-28 19:34:03', '2025-11-28 20:07:13'),
(41, 'EQFDBC49', 'Wacom Intuos Pro Medium', 'Wacom Drawing Tablets', 'Wacom Intuos Pro Medium drawing tablet with pressure-sensitive stylus. Includes Wacom Pro Pen 2.', 'Active area: 8.7\" x 5.8\", 8192 pressure levels, wireless connectivity, multi-touch', 6, 6, 0, 0, 'available', 'ITSO Storage Room C', 'Includes Wacom Pro Pen 2 stylus and USB cable', '1764360435_692a00f33be4f.jpg', 'thumb_1764360435_692a00f33be4f.jpg', NULL, NULL, '2025-11-28 19:34:03', '2025-11-28 20:07:15'),
(42, 'EQD61CB8', 'Wacom Intuos Small', 'Wacom Drawing Tablets', 'Wacom Intuos Small drawing tablet with pressure-sensitive stylus. Includes Wacom Pen.', 'Active area: 6.0\" x 3.7\", 4096 pressure levels, USB connectivity', 8, 8, 0, 0, 'available', 'ITSO Storage Room C', 'Includes Wacom Pen stylus and USB cable', '1764360437_692a00f5cf068.jpg', 'thumb_1764360437_692a00f5cf068.jpg', NULL, NULL, '2025-11-28 19:34:03', '2025-11-28 20:07:17'),
(43, 'EQF1C759', 'Wacom Intuos Medium', 'Wacom Drawing Tablets', 'Wacom Intuos Medium drawing tablet with pressure-sensitive stylus. Includes Wacom Pen.', 'Active area: 8.5\" x 5.3\", 4096 pressure levels, USB connectivity', 7, 7, 0, 0, 'available', 'ITSO Storage Room C', 'Includes Wacom Pen stylus and USB cable', '1764360440_692a00f85fb2d.jpg', 'thumb_1764360440_692a00f85fb2d.jpg', NULL, NULL, '2025-11-28 19:34:03', '2025-11-28 20:07:20'),
(44, 'EQ76CD42', 'Wacom One Medium', 'Wacom Drawing Tablets', 'Wacom One Medium drawing tablet with pressure-sensitive stylus. Includes Wacom Pen.', 'Active area: 8.5\" x 5.3\", 4096 pressure levels, USB connectivity', 6, 6, 0, 0, 'available', 'ITSO Storage Room C', 'Includes Wacom Pen stylus and USB cable', '1764360442_692a00fa8fbaf.jpg', 'thumb_1764360442_692a00fa8fbaf.jpg', NULL, NULL, '2025-11-28 19:34:03', '2025-11-28 20:07:22'),
(45, 'EQF1D85B', 'Wacom Intuos Pro Large', 'Wacom Drawing Tablets', 'Wacom Intuos Pro Large drawing tablet with pressure-sensitive stylus. Includes Wacom Pro Pen 2.', 'Active area: 12.1\" x 8.4\", 8192 pressure levels, wireless connectivity, multi-touch', 4, 4, 0, 0, 'available', 'ITSO Storage Room C', 'Includes Wacom Pro Pen 2 stylus and USB cable', '1764360444_692a00fccab87.jpg', 'thumb_1764360444_692a00fccab87.jpg', NULL, NULL, '2025-11-28 19:34:03', '2025-11-28 20:07:24'),
(46, 'EQ8D638B', 'Wacom Intuos Small (Spare)', 'Wacom Drawing Tablets', 'Spare Wacom Intuos Small drawing tablet with pressure-sensitive stylus. Includes Wacom Pen.', 'Active area: 6.0\" x 3.7\", 4096 pressure levels, USB connectivity', 9, 9, 0, 0, 'available', 'ITSO Storage Room C', 'Includes Wacom Pen stylus and USB cable', '1764360446_692a00fec5e7a.jpg', 'thumb_1764360446_692a00fec5e7a.jpg', NULL, NULL, '2025-11-28 19:34:03', '2025-11-28 20:07:26'),
(47, 'EQ34B2F2', 'Wacom Pro Pen 2 (Replacement Stylus)', 'Wacom Drawing Tablets', 'Replacement Wacom Pro Pen 2 stylus for Intuos Pro tablets.', '8192 pressure levels, tilt recognition, eraser on back', 10, 10, 0, 0, 'available', 'ITSO Storage Room C', 'Compatible with Wacom Intuos Pro series', '1764360449_692a01016c609.jpg', 'thumb_1764360449_692a01016c609.jpg', NULL, NULL, '2025-11-28 19:34:03', '2025-11-28 20:07:29'),
(48, 'EQ03E6E2', 'Wacom Pen (Replacement Stylus)', 'Wacom Drawing Tablets', 'Replacement Wacom Pen stylus for Intuos and One series tablets.', '4096 pressure levels, battery-free operation', 12, 12, 0, 0, 'available', 'ITSO Storage Room C', 'Compatible with Wacom Intuos and One series', '1764360452_692a01040d768.jpg', 'thumb_1764360452_692a01040d768.jpg', NULL, NULL, '2025-11-28 19:34:03', '2025-11-28 20:07:32'),
(49, 'EQDDA646', 'Logitech Z200 Speaker Set', 'Speaker Sets and Webcams', 'Logitech Z200 2.0 stereo speaker set with 10W total power output.', '2.0 stereo, 10W total power, 3.5mm audio jack, volume control', 12, 12, 0, 0, 'available', 'ITSO Storage Room D', 'Includes power adapter and audio cable', '1764360454_692a0106330a9.jpg', 'thumb_1764360454_692a0106330a9.jpg', NULL, NULL, '2025-11-28 19:34:03', '2025-11-28 20:07:34'),
(50, 'EQ34371D', 'Logitech Z313 Speaker Set', 'Speaker Sets and Webcams', 'Logitech Z313 2.1 speaker set with subwoofer and 25W total power output.', '2.1 with subwoofer, 25W total power, 3.5mm audio jack, wired control pod', 10, 10, 0, 0, 'available', 'ITSO Storage Room D', 'Includes power adapter and audio cable', '1764360456_692a0108ac452.jpg', 'thumb_1764360456_692a0108ac452.jpg', NULL, NULL, '2025-11-28 19:34:03', '2025-11-28 20:07:36'),
(51, 'EQB07E3D', 'Logitech C920 HD Pro Webcam', 'Speaker Sets and Webcams', 'Logitech C920 HD Pro webcam with 1080p video recording and stereo audio.', '1080p @ 30fps, 720p @ 30fps, autofocus, stereo microphones, USB 2.0', 15, 15, 0, 0, 'available', 'ITSO Storage Room D', 'Includes USB cable and privacy cover', '1764360458_692a010a765d3.jpg', 'thumb_1764360458_692a010a765d3.jpg', NULL, NULL, '2025-11-28 19:34:03', '2025-11-28 20:07:38'),
(52, 'EQ71225C', 'Logitech C270 HD Webcam', 'Speaker Sets and Webcams', 'Logitech C270 HD webcam with 720p video recording and built-in microphone.', '720p @ 30fps, fixed focus, built-in microphone, USB 2.0', 18, 18, 0, 0, 'available', 'ITSO Storage Room D', 'Includes USB cable', '1764360460_692a010caa8e4.jpg', 'thumb_1764360460_692a010caa8e4.jpg', NULL, NULL, '2025-11-28 19:34:03', '2025-11-28 20:07:40'),
(53, 'EQ848046', 'Logitech C930e Business Webcam', 'Speaker Sets and Webcams', 'Logitech C930e business webcam with 1080p video, H.264 encoding, and wide-angle lens.', '1080p @ 30fps, H.264 encoding, 90-degree field of view, USB 2.0', 8, 8, 0, 0, 'available', 'ITSO Storage Room D', 'Includes USB cable and privacy cover', '1764360462_692a010eb09bc.jpg', 'thumb_1764360462_692a010eb09bc.jpg', NULL, NULL, '2025-11-28 19:34:03', '2025-11-28 20:07:42'),
(54, 'EQFD3F55', 'Creative Pebble V2 Speaker Set', 'Speaker Sets and Webcams', 'Creative Pebble V2 2.0 USB-powered speaker set with modern design.', '2.0 stereo, USB-powered, 4W RMS per channel, 3.5mm audio jack', 14, 14, 0, 0, 'available', 'ITSO Storage Room D', 'USB-powered, no external power adapter needed', '1764360464_692a0110f1458.jpg', 'thumb_1764360464_692a0110f1458.jpg', NULL, NULL, '2025-11-28 19:34:04', '2025-11-28 20:07:45'),
(55, 'EQD898E9', 'Razer Kiyo Webcam', 'Speaker Sets and Webcams', 'Razer Kiyo webcam with 1080p video and built-in ring light for better lighting.', '1080p @ 30fps, 720p @ 60fps, built-in ring light, USB 2.0', 6, 6, 0, 0, 'available', 'ITSO Storage Room D', 'Includes USB cable and adjustable ring light', '1764360467_692a011304d22.jpg', 'thumb_1764360467_692a011304d22.jpg', NULL, NULL, '2025-11-28 19:34:04', '2025-11-28 20:07:47'),
(56, 'EQA5241B', 'Logitech C310 HD Webcam', 'Speaker Sets and Webcams', 'Logitech C310 HD webcam with 720p video recording and built-in microphone.', '720p @ 30fps, autofocus, built-in microphone, USB 2.0', 12, 12, 0, 0, 'available', 'ITSO Storage Room D', 'Includes USB cable', '1764360469_692a01152feb0.jpg', 'thumb_1764360469_692a01152feb0.jpg', NULL, NULL, '2025-11-28 19:34:04', '2025-11-28 20:07:49'),
(57, 'EQ539005', 'Extension Cord 6ft (3-Outlet)', 'Extension Cords', '6-foot extension cord with 3 outlets and surge protection.', '6ft length, 3 outlets, 15A rating, surge protection, grounded', 20, 20, 0, 0, 'available', 'ITSO Cable Storage', 'UL listed, suitable for office and classroom use', '1764360471_692a01177161b.jpg', 'thumb_1764360471_692a01177161b.jpg', NULL, NULL, '2025-11-28 19:34:04', '2025-11-28 20:07:51'),
(58, 'EQ4ED0B3', 'Extension Cord 10ft (3-Outlet)', 'Extension Cords', '10-foot extension cord with 3 outlets and surge protection.', '10ft length, 3 outlets, 15A rating, surge protection, grounded', 18, 18, 0, 0, 'available', 'ITSO Cable Storage', 'UL listed, suitable for office and classroom use', '1764360473_692a0119c6e29.jpg', 'thumb_1764360473_692a0119c6e29.jpg', NULL, NULL, '2025-11-28 19:34:04', '2025-11-28 20:07:53'),
(59, 'EQB63FE8', 'Extension Cord 15ft (3-Outlet)', 'Extension Cords', '15-foot extension cord with 3 outlets and surge protection.', '15ft length, 3 outlets, 15A rating, surge protection, grounded', 15, 15, 0, 0, 'available', 'ITSO Cable Storage', 'UL listed, suitable for office and classroom use', '1764360476_692a011c2db92.jpg', 'thumb_1764360476_692a011c2db92.jpg', NULL, NULL, '2025-11-28 19:34:04', '2025-11-28 20:07:56'),
(60, 'EQEF78DF', 'Extension Cord 25ft (3-Outlet)', 'Extension Cords', '25-foot extension cord with 3 outlets and surge protection.', '25ft length, 3 outlets, 15A rating, surge protection, grounded', 12, 12, 0, 0, 'available', 'ITSO Cable Storage', 'UL listed, suitable for large classrooms and events', '1764360478_692a011e85772.jpg', 'thumb_1764360478_692a011e85772.jpg', NULL, NULL, '2025-11-28 19:34:04', '2025-11-28 20:07:58'),
(61, 'EQ38CE35', 'Extension Cord 6ft (6-Outlet)', 'Extension Cords', '6-foot extension cord with 6 outlets and surge protection.', '6ft length, 6 outlets, 15A rating, surge protection, grounded', 16, 16, 0, 0, 'available', 'ITSO Cable Storage', 'UL listed, ideal for multiple device charging', '1764360480_692a0120c14ff.jpg', 'thumb_1764360480_692a0120c14ff.jpg', NULL, NULL, '2025-11-28 19:34:04', '2025-11-28 20:08:00'),
(62, 'EQ397EA0', 'Extension Cord 10ft (6-Outlet)', 'Extension Cords', '10-foot extension cord with 6 outlets and surge protection.', '10ft length, 6 outlets, 15A rating, surge protection, grounded', 14, 14, 0, 0, 'available', 'ITSO Cable Storage', 'UL listed, ideal for multiple device charging', '1764360483_692a01230b1c4.jpg', 'thumb_1764360483_692a01230b1c4.jpg', NULL, NULL, '2025-11-28 19:34:04', '2025-11-28 20:08:03'),
(63, 'EQ1E7943', 'Heavy-Duty Extension Cord 15ft', 'Extension Cords', 'Heavy-duty 15-foot extension cord with 3 outlets, rated for high-power devices.', '15ft length, 3 outlets, 20A rating, heavy-duty construction, grounded', 10, 10, 0, 0, 'available', 'ITSO Cable Storage', 'UL listed, suitable for projectors and high-power equipment', '1764360485_692a0125553b2.jpg', 'thumb_1764360485_692a0125553b2.jpg', NULL, NULL, '2025-11-28 19:34:04', '2025-11-28 20:08:05'),
(64, 'EQ4FA540', 'Extension Cord 50ft (3-Outlet)', 'Extension Cords', '50-foot extension cord with 3 outlets for long-distance power needs.', '50ft length, 3 outlets, 15A rating, grounded, outdoor rated', 8, 8, 0, 0, 'available', 'ITSO Cable Storage', 'UL listed, suitable for outdoor events and large venues', '1764360487_692a01279d4f8.jpg', 'thumb_1764360487_692a01279d4f8.jpg', NULL, NULL, '2025-11-28 19:34:04', '2025-11-28 20:08:07'),
(65, 'EQBC8A01', 'RJ45 Crimping Tool', 'Cable Crimping Tools', 'Professional RJ45 crimping tool for Ethernet cable termination.', 'RJ45/RJ11 compatible, ratcheting mechanism, wire cutter and stripper included', 6, 6, 0, 0, 'available', 'ITSO Tool Storage', 'Includes wire stripper and cutter', '1764360489_692a0129d131b.jpg', 'thumb_1764360489_692a0129d131b.jpg', NULL, NULL, '2025-11-28 19:34:04', '2025-11-28 20:08:09'),
(66, 'EQAAA6EF', 'RJ45 Crimping Tool (Heavy-Duty)', 'Cable Crimping Tools', 'Heavy-duty RJ45 crimping tool with reinforced construction for frequent use.', 'RJ45/RJ11 compatible, heavy-duty ratcheting mechanism, reinforced handles', 4, 4, 0, 0, 'available', 'ITSO Tool Storage', 'Professional grade, suitable for daily use', '1764360492_692a012c99eff.jpg', 'thumb_1764360492_692a012c99eff.jpg', NULL, NULL, '2025-11-28 19:34:04', '2025-11-28 20:08:12'),
(67, 'EQ701C11', 'Network Cable Crimper Kit', 'Cable Crimping Tools', 'Complete network cable crimping kit with tool, connectors, and tester.', 'RJ45 crimping tool, 50 RJ45 connectors, cable tester, wire stripper', 5, 5, 0, 0, 'available', 'ITSO Tool Storage', 'Complete kit for network cable installation', '1764360494_692a012ef39d6.jpg', 'thumb_1764360494_692a012ef39d6.jpg', NULL, NULL, '2025-11-28 19:34:04', '2025-11-28 20:08:15'),
(68, 'EQEF9DCD', 'Coaxial Cable Crimping Tool', 'Cable Crimping Tools', 'Coaxial cable crimping tool for RG6 and RG59 cable termination.', 'RG6/RG59 compatible, ratcheting mechanism, includes multiple die sizes', 3, 3, 0, 0, 'available', 'ITSO Tool Storage', 'For coaxial cable installation and repair', '1764360497_692a013134d6e.jpg', 'thumb_1764360497_692a013134d6e.jpg', NULL, NULL, '2025-11-28 19:34:04', '2025-11-28 20:08:17'),
(69, 'EQF2D5D9', 'RJ45 Pass-Through Crimping Tool', 'Cable Crimping Tools', 'RJ45 pass-through crimping tool that allows wires to pass through connector for easier termination.', 'RJ45 pass-through compatible, ratcheting mechanism, wire cutter', 5, 5, 0, 0, 'available', 'ITSO Tool Storage', 'Easier to use with pass-through connectors', '1764360499_692a01336f91b.jpg', 'thumb_1764360499_692a01336f91b.jpg', NULL, NULL, '2025-11-28 19:34:04', '2025-11-28 20:08:19'),
(70, 'EQ8F00A2', 'Modular Plug Crimping Tool', 'Cable Crimping Tools', 'Modular plug crimping tool for RJ45, RJ11, and RJ12 connectors.', 'RJ45/RJ11/RJ12 compatible, ratcheting mechanism, compact design', 7, 7, 0, 0, 'available', 'ITSO Tool Storage', 'Versatile tool for multiple connector types', '1764360501_692a0135801a3.jpg', 'thumb_1764360501_692a0135801a3.jpg', NULL, NULL, '2025-11-28 19:34:04', '2025-11-28 20:08:21'),
(71, 'EQ72FF43', 'Network Tool Kit', 'Cable Crimping Tools', 'Complete network tool kit with crimping tool, punch-down tool, and accessories.', 'RJ45 crimper, punch-down tool, wire stripper, cable tester, connectors', 4, 4, 0, 0, 'available', 'ITSO Tool Storage', 'Professional network installation kit', '1764360503_692a013763bdc.jpg', 'thumb_1764360503_692a013763bdc.jpg', NULL, NULL, '2025-11-28 19:34:04', '2025-11-28 20:08:23'),
(72, 'EQ49E3AF', 'RJ45 Connector Pack (100 pcs)', 'Cable Crimping Tools', 'Pack of 100 RJ45 connectors for use with crimping tools.', '100 RJ45 connectors, CAT5e/CAT6 compatible, gold-plated contacts', 10, 10, 0, 0, 'available', 'ITSO Tool Storage', 'Replacement connectors for network cable installation', '1764360505_692a013938eff.jpg', 'thumb_1764360505_692a013938eff.jpg', NULL, NULL, '2025-11-28 19:34:04', '2025-11-28 20:08:25'),
(73, 'EQ3922C0', 'Network Cable Tester', 'Cable Testers', 'Basic network cable tester for RJ45 Ethernet cables with continuity testing.', 'RJ45 compatible, 8-wire testing, LED indicators, battery powered', 8, 8, 0, 0, 'available', 'ITSO Tool Storage', 'Tests cable continuity and wire mapping', '1764360507_692a013b74477.jpg', 'thumb_1764360507_692a013b74477.jpg', NULL, NULL, '2025-11-28 19:34:04', '2025-11-28 20:08:27'),
(74, 'EQA21580', 'Professional Network Cable Tester', 'Cable Testers', 'Professional network cable tester with advanced features including length measurement.', 'RJ45 compatible, TDR length measurement, wire mapping, tone generator', 4, 4, 0, 0, 'available', 'ITSO Tool Storage', 'Advanced features for professional network troubleshooting', '1764360509_692a013def7db.jpg', 'thumb_1764360509_692a013def7db.jpg', NULL, NULL, '2025-11-28 19:34:04', '2025-11-28 20:08:30'),
(75, 'EQ47F81D', 'Cable Tester with Remote Unit', 'Cable Testers', 'Network cable tester with remote unit for testing cables at both ends.', 'RJ45 compatible, remote unit included, wire mapping, LED indicators', 6, 6, 0, 0, 'available', 'ITSO Tool Storage', 'Remote unit allows testing from both cable ends', '1764360511_692a013fc02c8.jpg', 'thumb_1764360511_692a013fc02c8.jpg', NULL, NULL, '2025-11-28 19:34:04', '2025-11-28 20:08:31'),
(76, 'EQAE4E15', 'Multi-Function Cable Tester', 'Cable Testers', 'Multi-function cable tester for RJ45, RJ11, and coaxial cables.', 'RJ45/RJ11/Coaxial compatible, multiple test modes, LCD display', 5, 5, 0, 0, 'available', 'ITSO Tool Storage', 'Versatile tester for multiple cable types', '1764360513_692a0141ed24f.jpg', 'thumb_1764360513_692a0141ed24f.jpg', NULL, NULL, '2025-11-28 19:34:04', '2025-11-28 20:08:34'),
(77, 'EQ4194EF', 'Pocket Network Cable Tester', 'Cable Testers', 'Compact pocket-sized network cable tester for quick field testing.', 'RJ45 compatible, compact design, LED indicators, battery powered', 10, 10, 0, 0, 'available', 'ITSO Tool Storage', 'Portable tester for quick cable checks', '1764360516_692a01441a6d8.jpg', 'thumb_1764360516_692a01441a6d8.jpg', NULL, NULL, '2025-11-28 19:34:04', '2025-11-28 20:08:36'),
(78, 'EQ4702F6', 'Cable Tester with Tone Generator', 'Cable Testers', 'Network cable tester with built-in tone generator for cable identification.', 'RJ45 compatible, tone generator, wire mapping, LED indicators', 7, 7, 0, 0, 'available', 'ITSO Tool Storage', 'Tone generator helps identify cables in bundles', '1764360517_692a0145e5fa8.jpg', 'thumb_1764360517_692a0145e5fa8.jpg', NULL, NULL, '2025-11-28 19:34:04', '2025-11-28 20:08:38'),
(79, 'EQE18B1B', 'CAT6 Cable Tester', 'Cable Testers', 'CAT6 certified network cable tester for high-speed network installations.', 'CAT6/CAT5e compatible, certification testing, wire mapping, LCD display', 4, 4, 0, 0, 'available', 'ITSO Tool Storage', 'Certified for CAT6 network installations', '1764360520_692a01482e66c.jpg', 'thumb_1764360520_692a01482e66c.jpg', NULL, NULL, '2025-11-28 19:34:04', '2025-11-28 20:08:40'),
(80, 'EQ6AF01B', 'Wireless Cable Tester', 'Cable Testers', 'Wireless network cable tester with Bluetooth connectivity and mobile app.', 'RJ45 compatible, Bluetooth connectivity, mobile app, wire mapping', 3, 3, 0, 0, 'available', 'ITSO Tool Storage', 'Advanced wireless testing with mobile app integration', '1764360522_692a014a8417f.jpg', 'thumb_1764360522_692a014a8417f.jpg', NULL, NULL, '2025-11-28 19:34:05', '2025-11-28 20:08:42'),
(81, 'EQ6BE007', 'Computer Lab 101 Key', 'Lab Room Keys', 'Master key for Computer Lab 101 with access to all equipment storage.', 'Master key, access to main door and storage cabinets', 2, 2, 0, 0, 'available', 'ITSO Key Storage', 'Master key - requires authorization to borrow', '1764360524_692a014c5fa6c.jpg', 'thumb_1764360524_692a014c5fa6c.jpg', NULL, NULL, '2025-11-28 19:34:05', '2025-11-28 20:08:44'),
(82, 'EQBE8421', 'Computer Lab 102 Key', 'Lab Room Keys', 'Master key for Computer Lab 102 with access to all equipment storage.', 'Master key, access to main door and storage cabinets', 2, 2, 0, 0, 'available', 'ITSO Key Storage', 'Master key - requires authorization to borrow', '1764360526_692a014ea8599.jpg', 'thumb_1764360526_692a014ea8599.jpg', NULL, NULL, '2025-11-28 19:34:05', '2025-11-28 20:08:46'),
(83, 'EQCEA0D8', 'Mac Lab Key', 'Lab Room Keys', 'Master key for Mac Lab with access to all equipment storage.', 'Master key, access to main door and storage cabinets', 2, 2, 0, 0, 'available', 'ITSO Key Storage', 'Master key - requires authorization to borrow', '1764360528_692a0150f1bc5.jpg', 'thumb_1764360528_692a0150f1bc5.jpg', NULL, NULL, '2025-11-28 19:34:05', '2025-11-28 20:08:49'),
(84, 'EQBF488C', 'Networking Lab Key', 'Lab Room Keys', 'Master key for Networking Lab with access to all equipment storage.', 'Master key, access to main door and storage cabinets', 2, 2, 0, 0, 'available', 'ITSO Key Storage', 'Master key - requires authorization to borrow', '1764360531_692a01532ae61.jpg', 'thumb_1764360531_692a01532ae61.jpg', NULL, NULL, '2025-11-28 19:34:05', '2025-11-28 20:08:51'),
(85, 'EQA6087E', 'Multimedia Lab Key', 'Lab Room Keys', 'Master key for Multimedia Lab with access to all equipment storage.', 'Master key, access to main door and storage cabinets', 2, 2, 0, 0, 'available', 'ITSO Key Storage', 'Master key - requires authorization to borrow', '1764360533_692a015558181.jpg', 'thumb_1764360533_692a015558181.jpg', NULL, NULL, '2025-11-28 19:34:05', '2025-11-28 20:08:53'),
(86, 'EQF174A1', 'ITSO Storage Room A Key', 'Lab Room Keys', 'Key for ITSO Storage Room A where laptops and main equipment are stored.', 'Storage room key, access to equipment storage', 3, 3, 0, 0, 'available', 'ITSO Key Storage', 'Storage room access key', '1764360535_692a015790b8c.jpg', 'thumb_1764360535_692a015790b8c.jpg', NULL, NULL, '2025-11-28 19:34:05', '2025-11-28 20:08:55'),
(87, 'EQ29A6DA', 'ITSO Storage Room B Key', 'Lab Room Keys', 'Key for ITSO Storage Room B where projectors and AV equipment are stored.', 'Storage room key, access to equipment storage', 3, 3, 0, 0, 'available', 'ITSO Key Storage', 'Storage room access key', '1764360537_692a0159c33fb.jpg', 'thumb_1764360537_692a0159c33fb.jpg', NULL, NULL, '2025-11-28 19:34:05', '2025-11-28 20:08:57'),
(88, 'EQD01FBA', 'ITSO Storage Room C Key', 'Lab Room Keys', 'Key for ITSO Storage Room C where drawing tablets and specialized equipment are stored.', 'Storage room key, access to equipment storage', 3, 3, 0, 0, 'available', 'ITSO Key Storage', 'Storage room access key', '1764360539_692a015bc67e2.jpg', 'thumb_1764360539_692a015bc67e2.jpg', NULL, NULL, '2025-11-28 19:34:05', '2025-11-28 20:08:59');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `username` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','associate','itso_personnel') NOT NULL,
  `department` varchar(100) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `middle_name`, `username`, `email`, `password`, `role`, `department`, `contact_number`, `profile_picture`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Althea Zuleka', 'Sadicon', 'A.', 'altheaxzuleika', 'azsadicon@gmail.com', '$2y$10$9prml0nMZthsyLDoDE.d6ePEXDoR07aTWKPJqKhhNkc8RcQrMe6OC', 'student', 'BSITWMA', '09179012345', NULL, 1, '2025-11-17 06:46:13', '2025-11-29 07:20:40'),
(2, 'Khristian', 'Guinto', 'A.', 'keijey', 'khristianguinto@gmail.com', '$2y$10$VJhPiqsPwViChQF7EcaH1OKdYbT9A51zy9m.oobfIQ//WL7O4ioVi', 'itso_personnel', 'BSITWMA', '09260052555', NULL, 1, '2025-11-17 07:36:46', '2025-11-17 07:36:46'),
(3, 'Maui', 'Sadicon', 'A.', 'mauixsdcn', 'mauisdgc@gmail.com', '$2y$10$AAMTUPIPvNBsLbsgLF6Yw.SsU3GOVbZCHt6oGKsGTlP9h8gY7yMza', 'associate', 'Computer Science', '09179012345', NULL, 1, '2025-11-17 09:53:47', '2025-11-17 09:53:47'),
(4, 'Christian', 'Grey', 'V.', 'christian_grey', 'christiangrey@gmail.com', '$2y$10$woF5uR1SPhM7XjyTkUW/0e/lWOgPOU6fKAPDR2aFfcXhfORxg4t6O', 'student', 'multimedia', '09260052555', NULL, 1, '2025-11-17 15:46:03', '2025-11-17 15:46:03'),
(5, 'Jan Rodmil', 'Pablo', 'Romano', 'Rodmil_1', 'rodmilsama@gmail.com', '$2y$10$4kRj7h.rRmYQMKLivO9lxOVVZHSTg56CDooB/UW2Po6NPl9CWmKiW', 'itso_personnel', 'cs', '09162948372', NULL, 1, '2025-11-29 06:48:39', '2025-11-29 07:19:19'),
(6, 'RODEL', 'SANTOS', 'santos', 'rodmilsama@gmail.com', 'rodel@gmail.com', '$2y$10$ifnzx/OVICH49aJW59ExuOmvkpwtguGlxjNJSqzqoaZM7z9IsOeWC', 'associate', 'cs', '09284681775', NULL, 1, '2025-11-29 07:37:03', '2025-11-29 07:37:03'),
(7, 'RODEL', 'SANTOS', 'santos', 'rodelll1', 'rodelsantos2@gmail.com', '$2y$10$vM70m1hgz0WmXQOPDiVFdeHtVwhncis0Q3/3xEB.gHryfza6d6Vba', 'associate', 'cs', '09284681775', NULL, 1, '2025-11-29 07:37:49', '2025-11-29 07:37:49'),
(8, 'hannah', 'Pablo', '', 'Hannah01', 'rodmilsantos2@gmail.com', '$2y$10$33Gk/2MeXORYemrZo4PwKOc4r.0nswTzk3HKOK/Qgt3Vh6Oo85C3u', 'associate', 'cs', '09284681775', NULL, 1, '2025-11-29 07:39:31', '2025-11-29 07:39:31'),
(9, 'Jan Delle', 'Maso', '', 'JD1', 'jrsantos1@fit.edu.ph', '$2y$10$WKgY0nhuDDftNXxj4eHo5.hJKtj83zWdYP1rKAyQahh23Yj9G1piy', 'student', 'engr', '09284681775', NULL, 1, '2025-11-29 07:58:58', '2025-11-29 07:58:58'),
(10, 'rodmil', 'santos', 'romano', 'rod0809', 'sadiconaltheazuleika@gmail.com', '$2y$10$uZGqmsp/tbwVmy9t54ql0.znyBxe3CfPfECBzLTiUkS9NjLyIEWyy', 'itso_personnel', 'cs', '09284681775', NULL, 1, '2025-11-29 08:17:03', '2025-11-29 08:17:03'),
(11, 'hannah', 'pabloo', '', 'Hannah111', 'pablohannah77@gmail.com', '$2y$10$aej.OMVm.aN1rdKV3s0WfOHHWj03NaMHpv7OV/ONnlmk710L6eRtG', 'associate', 'cs', '09284681775', NULL, 1, '2025-11-29 08:21:26', '2025-11-29 08:21:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `borrow_requests`
--
ALTER TABLE `borrow_requests`
  ADD PRIMARY KEY (`borrow_id`),
  ADD KEY `equipment_id` (`equipment_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `approved_by` (`approved_by`);

--
-- Indexes for table `equipment`
--
ALTER TABLE `equipment`
  ADD PRIMARY KEY (`equipment_id`),
  ADD UNIQUE KEY `equipment_code` (`equipment_code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `borrow_requests`
--
ALTER TABLE `borrow_requests`
  MODIFY `borrow_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `equipment`
--
ALTER TABLE `equipment`
  MODIFY `equipment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `borrow_requests`
--
ALTER TABLE `borrow_requests`
  ADD CONSTRAINT `borrow_requests_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`equipment_id`),
  ADD CONSTRAINT `borrow_requests_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `borrow_requests_ibfk_3` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
