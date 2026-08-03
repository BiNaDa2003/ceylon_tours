-- phpMyAdmin SQL Dump
-- Tour Package Booking System — Complete Merged Database Script
-- Safe for importing into phpMyAdmin

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tour_booking`
--
CREATE DATABASE IF NOT EXISTS `tour_booking` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `tour_booking`;

-- Drop existing tables cleanly to avoid schema mismatch errors on existing databases
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `custom_packages`;
DROP TABLE IF EXISTS `wishlist`;
DROP TABLE IF EXISTS `reviews`;
DROP TABLE IF EXISTS `itineraries`;
DROP TABLE IF EXISTS `package_images`;
DROP TABLE IF EXISTS `bookings`;
DROP TABLE IF EXISTS `tour_packages`;
DROP TABLE IF EXISTS `contacts`;
DROP TABLE IF EXISTS `customers`;
DROP TABLE IF EXISTS `admins`;
SET FOREIGN_KEY_CHECKS = 1;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `admins` (`id`, `username`, `email`, `password`) VALUES
(1, 'admin', 'admin@ceylontours.com', 'Admin@12345');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `customers` (`id`, `name`, `email`, `phone`, `password`) VALUES
(1, 'Malshi Navodya', 'malshi@example.com', '077 5004567', 'Admin@12345');

-- --------------------------------------------------------

--
-- Table structure for table `tour_packages`
--

CREATE TABLE `tour_packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `destination` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `category` enum('Adventure','Cultural','Wildlife','Beach','Family','Honeymoon','Religious') NOT NULL DEFAULT 'Cultural',
  `difficulty_level` enum('Easy','Moderate','Challenging') NOT NULL DEFAULT 'Easy',
  `includes_services` text DEFAULT NULL,
  `excluded_services` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration` int(11) NOT NULL COMMENT 'Duration in days',
  `image` varchar(255) DEFAULT NULL,
  `available_slots` int(11) NOT NULL,
  `rating` decimal(3,2) DEFAULT 0.00,
  `is_featured` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tour_packages` (`id`, `title`, `destination`, `description`, `category`, `difficulty_level`, `includes_services`, `excluded_services`, `price`, `duration`, `image`, `available_slots`, `rating`, `is_featured`) VALUES
(1, 'Sigiriya Rock Fortress Tour', 'Sigiriya, Sri Lanka', 'Explore the ancient rock fortress of Sigiriya, a UNESCO World Heritage site known as the Eighth Wonder of the World.', 'Cultural', 'Moderate', 'Professional English-speaking guide,Round-trip transportation,Entrance fees,Bottled water,Travel insurance', 'Personal expenses,Tips and gratuities,International flights,Meals (unless specified)', 15000.00, 2, 'Sigiriya.png', 20, 5.00, 1),
(2, 'Kandy Cultural Experience', 'Kandy, Sri Lanka', 'Visit the sacred Temple of the Tooth Relic and experience the rich cultural heritage of the hill capital.', 'Religious', 'Easy', 'Expert cultural guide,Air-conditioned transport,Temple entrance fees,Traditional welcome kit,Photography assistance', 'Accommodation,Food and beverages,Personal items', 25000.00, 3, 'Temple of the tooth relic Kandy.png', 15, 5.00, 1),
(3, 'Galle Fort Heritage Walk', 'Galle, Sri Lanka', 'Walk through the historic Galle Fort, a Dutch colonial masterpiece with charming streets and stunning ocean views.', 'Cultural', 'Easy', 'Licensed tour guide,Transport in air-conditioned vehicle,Fort entrance,Lunch at local restaurant,Sunset cruise', 'Accommodation,Alcoholic beverages,Personal shopping', 12000.00, 2, 'Galle fort.png', 12, 4.60, 1),
(4, 'Ella Hiking & Scenic Train', 'Ella, Sri Lanka', 'Experience the breathtaking views of Ella Rock and the famous Nine Arch Bridge. Includes the scenic train journey from Kandy to Ella through lush tea plantations.', 'Adventure', 'Challenging', 'Train tickets (Kandy-Ella),Professional hiking guide,Ella Rock hike,Nine Arch Bridge visit,Accommodation in Ella,Breakfast daily', 'Lunch and dinner,Personal hiking gear,Travel insurance', 18500.00, 3, 'Ella.png', 18, 4.70, 1),
(5, 'Koggala Coastal Retreat', 'Koggala, Sri Lanka', 'Relax on the pristine beaches of Koggala near Galle. Experience stilt fishing, traditional boat rides on Koggala Lake, and explore spice gardens.', 'Beach', 'Easy', 'Beachfront accommodation,Stilt fishing experience,Boat ride on Koggala Lake,Spice garden visit,Coconut tasting session', 'International flights,Personal expenses,Alcoholic beverages', 22000.00, 4, 'Koggala.png', 14, 4.40, 0),
(6, 'Pinnawala Elephant Safari', 'Pinnawala, Sri Lanka', 'Visit the world-famous Pinnawala Elephant Orphanage and witness the daily bathing ritual of over 70 rescued elephants in the Maha Oya River.', 'Wildlife', 'Easy', 'Elephant Orphanage entrance,Guided wildlife tour,Transportation,Elephant feeding experience,Photography guide', 'Food and drinks,Personal items,Gratuities', 9500.00, 1, 'Pinnawala Elephant Orphanage.png', 30, 4.90, 1);

-- --------------------------------------------------------

--
-- Table structure for table `package_images`
--

CREATE TABLE `package_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `is_primary` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `package_id` (`package_id`),
  CONSTRAINT `pkg_images_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `tour_packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `package_images` (`package_id`, `image_path`, `caption`, `is_primary`) VALUES
(1, 'Sigiriya.png', 'Sigiriya Rock Fortress — UNESCO World Heritage', 1),
(2, 'Temple of the tooth relic Kandy.png', 'Sacred Temple of the Tooth Relic, Kandy', 1),
(3, 'Galle fort.png', 'Historic Galle Fort — Dutch Colonial Architecture', 1),
(4, 'Ella.png', 'Ella Rock and Nine Arch Bridge', 1),
(5, 'Koggala.png', 'Pristine Beaches of Koggala', 1),
(6, 'Pinnawala Elephant Orphanage.png', 'Pinnawala Elephant Orphanage', 1);

-- --------------------------------------------------------

--
-- Table structure for table `itineraries`
--

CREATE TABLE `itineraries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `day_number` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `package_id` (`package_id`),
  CONSTRAINT `itin_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `tour_packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `itineraries` (`package_id`, `day_number`, `title`, `description`) VALUES
(1, 1, 'Arrival & Sigiriya Ascent', 'Depart from Colombo early morning. Arrive at Sigiriya by 9 AM. Climb the ancient rock fortress with your guide. Evening visit to Dambulla Cave Temple.'),
(1, 2, 'Minneriya Safari & Departure', 'Morning optional visit to Minneriya National Park for elephant spotting. Depart back to Colombo.'),
(2, 1, 'Colombo to Kandy — Cultural Drive', 'Depart Colombo via Kandy Road. Stop at Batik factory and spice garden. Arrive Kandy by afternoon.'),
(2, 2, 'Temple of the Tooth & City Tour', 'Visit Sacred Temple of the Tooth Relic, Peradeniya Botanical Gardens, and evening Kandyan dance show.'),
(2, 3, 'Tea Plantation & Return', 'Visit tea factory in Nuwara Eliya direction. Experience Ceylon tea making. Depart back to Colombo.'),
(3, 1, 'Colombo to Galle — Coastal Drive', 'Scenic coastal drive along Southern Expressway. Arrive Galle Fort for guided heritage walk.'),
(3, 2, 'Beach & Sunset Cruise', 'Free time at Galle beach. Explore local market. Afternoon sunset boat cruise in Galle harbor.'),
(4, 1, 'Kandy to Ella — Scenic Train', 'Board iconic scenic train from Kandy to Ella through tea estates and valleys.'),
(4, 2, 'Ella Rock Hike', 'Hike to Ella Rock summit (1,045m). Visit Nine Arch Bridge and Little Adam\'s Peak.'),
(4, 3, 'Ravana Falls & Departure', 'Visit Ravana Falls and Ravana Cave. Depart back to Colombo.'),
(6, 1, 'Full Day Elephant Experience', 'Watch 70+ elephants bathe in Maha Oya River at Pinnawala. Elephant feeding and nursery tour.');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `travel_date` date NOT NULL,
  `travelers` int(11) NOT NULL,
  `special_requests` text DEFAULT NULL,
  `booking_status` enum('Pending','Confirmed','Cancelled') NOT NULL DEFAULT 'Pending',
  `payment_status` enum('Unpaid','Paid','Refunded') NOT NULL DEFAULT 'Unpaid',
  `cancel_reason` varchar(255) DEFAULT NULL,
  `total_price` decimal(12,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  KEY `package_id` (`package_id`),
  CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`package_id`) REFERENCES `tour_packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `bookings` (`id`, `customer_id`, `package_id`, `travel_date`, `travelers`, `special_requests`, `booking_status`, `payment_status`, `total_price`) VALUES
(1, 1, 1, '2026-08-15', 2, 'Vegetarian meals', 'Confirmed', 'Paid', 30000.00);

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `rating` tinyint(1) NOT NULL CHECK (`rating` BETWEEN 1 AND 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `one_review_per_booking` (`customer_id`, `package_id`),
  KEY `package_id` (`package_id`),
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`package_id`) REFERENCES `tour_packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `reviews` (`customer_id`, `package_id`, `rating`, `comment`) VALUES
(1, 1, 5, 'Absolutely stunning! The climb to Sigiriya top was challenging but the view from the summit was breathtaking. Our guide Priya was very knowledgeable.'),
(1, 2, 5, 'The Temple of the Tooth visit was a deeply spiritual experience. The evening Kandyan dance performance was spectacular!');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_wishlist` (`customer_id`, `package_id`),
  KEY `package_id` (`package_id`),
  CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`package_id`) REFERENCES `tour_packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `custom_packages`
--

CREATE TABLE `custom_packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `duration` int(11) NOT NULL,
  `activities` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `estimated_price` decimal(12,2) DEFAULT 0.00,
  `status` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `custom_pkg_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;