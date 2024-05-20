-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 07, 2024 at 05:06 PM
-- Server version: 8.2.0
-- PHP Version: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `marketapplication`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `remember` varchar(200) DEFAULT NULL,
  `profile` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `district` varchar(100) NOT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`email`, `password`, `name`, `remember`, `profile`, `city`, `district`) VALUES
('ali@gmail.com', '$2a$10$e5fur6yolFMdD2fICktZBeMGVtwNjtajsoSnGYRiqDfvLj3aJbsG2', 'Ali Gül', 'be14e9a7b45e3b753bfeabb481e438a572b4410a', NULL, 'Ankara', 'Eryaman'),
('john@gmail.com', '$2a$10$e5fur6yolFMdD2fICktZBeMGVtwNjtajsoSnGYRiqDfvLj3aJbsG2', 'John Lock', NULL, 'fbd506f715c932c6233489d64221fcafbd67a4cf.jpg', 'Ankara', 'Çankaya'),
('kate@gmail.com', '$2a$10$e5fur6yolFMdD2fICktZBeMGVtwNjtajsoSnGYRiqDfvLj3aJbsG2', 'Kate Austen', NULL, '3ea9b3c975c188e23761cd3a7ff925543fe71256.jpg', 'Adana', 'Çukurova');

-- --------------------------------------------------------

--
-- Table structure for table `market_user`
--

DROP TABLE IF EXISTS `market_user`;
CREATE TABLE IF NOT EXISTS `market_user` (
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `market_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `password` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `city` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `district` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `address` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `remember` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `market_user`
--

INSERT INTO `market_user` (`email`, `market_name`, `password`, `city`, `district`, `address`, `remember`) VALUES
('cankaya@migros.com.tr', 'Migros Çankaya', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Ankara', 'Çankaya', 'Çankaya, Ankara', '70a3be7101e0f2c36165d3e4109c27ade6564caf'),
('cukurova@migros.com.tr', 'Migros Çukurova', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Adana', 'Çukurova', 'Çukurova, Adana', NULL),
('eryaman@migros.com.tr', 'Migros Eryaman', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Ankara', 'Eryaman', 'Altay, 06820 Etimesgut/Ankara', NULL),
('golbasi@migros.com.tr', 'Migros Gölbaşı', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Ankara', 'Gölbaşı', 'Gölbaşı, Ankara', NULL),
('kecioren@migros.com.tr', 'Migros Keçiören', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Ankara', 'Keçiören', 'Keçiören, Ankara', NULL),
('yenimahalle@migros.com.tr', 'Migros Yenimahalle', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Ankara', 'Yenimahalle', 'Yenimahalle, Ankara', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `product_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `product_price` decimal(8,2) NOT NULL,
  `product_disc_price` decimal(8,2) NOT NULL,
  `product_exp_date` date NOT NULL,
  `product_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1010 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_title`, `product_price`, `product_disc_price`, `product_exp_date`, `product_image`) VALUES
(1000, 'Egg', 2.00, 1.00, '2024-05-15', 'eggs.jpg'),
(1001, 'Milk', 5.00, 3.00, '2024-05-10', 'milk.jpg'),
(1002, 'Vegetables', 10.00, 2.00, '2024-05-12', 'vegetables.jpg'),
(1003, 'Chicken', 15.00, 3.00, '2024-05-09', 'chicken.jpeg'),
(1004, 'Bread', 3.00, 1.00, '2024-05-14', 'bread.jpeg'),
(1005, 'Cheese', 8.00, 2.00, '2024-05-11', 'cheese.jpeg'),
(1006, 'Yogurt', 4.00, 1.00, '2024-05-13', 'yogurt.jpeg'),
(1007, 'Apples', 6.00, 2.00, '2024-05-08', 'apples.jpeg'),
(1008, 'Rice', 12.00, 3.00, '2024-05-07', 'rice.jpeg'),
(1009, 'Pasta', 5.00, 1.00, '2024-05-16', 'pasta.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

DROP TABLE IF EXISTS `stocks`;
CREATE TABLE IF NOT EXISTS `stocks` (
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `product_id` int NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`email`,`product_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`email`, `product_id`, `stock`) VALUES
('cankaya@migros.com.tr', 1001, 5),
('cankaya@migros.com.tr', 1007, 15),
('eryaman@migros.com.tr', 1007, 15),
('golbasi@migros.com.tr', 1005, 15),
('kecioren@migros.com.tr', 1008, 20);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `stocks`
--
ALTER TABLE `stocks`
  ADD CONSTRAINT `stocks_ibfk_1` FOREIGN KEY (`email`) REFERENCES `market_user` (`email`),
  ADD CONSTRAINT `stocks_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
