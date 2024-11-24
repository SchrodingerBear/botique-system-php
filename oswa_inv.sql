-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 12, 2024 at 05:53 PM
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
-- Database: `oswa_inv`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `image_id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` int(255) NOT NULL,
  `color` varchar(200) NOT NULL,
  `size` text NOT NULL,
  `quantity` int(100) NOT NULL,
  `userName` text NOT NULL,
  `user_id` int(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `image_id`, `name`, `price`, `color`, `size`, `quantity`, `userName`, `user_id`) VALUES
(4, 2, 'Lalaki Damit', 269, 'Violet', '0', 5, 'Jester', 0),
(5, 2, 'Lalaki Damit', 269, 'Blue', '0', 1, 'Jester', 0),
(6, 2, 'Lalaki Damit', 269, 'Blue', 'L', 11, 'Default User', 3),
(7, 6, 'Babae Relo', 222, '', '', 1, 'Jester', 0),
(8, 2, 'Lalaki Damit', 269, 'Blue', 'L', 1, '3', 0),
(9, 2, 'Lalaki Damit', 100, 'Violet', 'L', 6, 'Default User', 3),
(10, 9, 'Test1', 58, 'Black', 'M', 10, ' Admin User', 1),
(11, 10, '', 60, 'Red', 'Choose an option', 3, ' Admin User', 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL,
  `type` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `type`) VALUES
(4, 'Women', 'CLOTHES'),
(5, 'Men', 'CLOTHES'),
(6, 'Shoes', 'SHOES'),
(7, 'Watches', 'ACCESSORIES'),
(8, 'Cosmetics', 'ACCESSORIES');

-- --------------------------------------------------------

--
-- Table structure for table `category_type`
--

CREATE TABLE `category_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category_type`
--

INSERT INTO `category_type` (`id`, `name`) VALUES
(1, 'clothes'),
(2, 'shoes'),
(3, 'accessories');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` int(11) UNSIGNED NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`id`, `file_name`, `file_type`) VALUES
(2, 'mendress.png', 'image/png'),
(3, 'dress.png', 'image/png'),
(4, 'menshoe.png', 'image/png'),
(5, 'womenShoe.png', 'image/png'),
(6, 'womenWatch.png', 'image/png'),
(7, '71TOxRwstaL._AC_UL1500_ (1).jpg', 'image/jpeg'),
(8, 'relo.png', 'image/png'),
(9, 'Screenshot 2024-10-12 225833.png', 'image/png'),
(10, 'makeup.png', 'image/png');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `quantity` varchar(50) DEFAULT NULL,
  `buy_price` decimal(25,2) DEFAULT NULL,
  `sale_price` decimal(25,2) NOT NULL,
  `category` text NOT NULL,
  `category_name` text NOT NULL,
  `categorie_id` int(11) UNSIGNED DEFAULT NULL,
  `media_id` int(11) DEFAULT 0,
  `date` datetime NOT NULL,
  `size` text DEFAULT NULL,
  `sex` text NOT NULL,
  `color` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `quantity`, `buy_price`, `sale_price`, `category`, `category_name`, `categorie_id`, `media_id`, `date`, `size`, `sex`, `color`) VALUES
(21, 'Lalaki Damit', '100', 100.00, 269.00, 'CLOTHES', 'Men', 5, 2, '2024-10-10 13:07:27', 'S', 'Male', 'Violet'),
(22, 'Women Dress', '100', 1.00, 199.00, 'CLOTHES', 'Women', 4, 3, '2024-10-10 13:07:48', 'XL', 'Female', ''),
(23, 'Watch Lalaki', '1', 12.00, 1111.00, 'ACCESSORIES', 'Watches', 7, 8, '2024-10-10 13:08:13', '', 'Male', ''),
(24, 'Babae Relo', '12', 12.00, 222.00, 'ACCESSORIES', 'Watches', 7, 6, '2024-10-10 13:08:42', '', 'Female', ''),
(25, 'Shoe', '1', 133.00, 1444.00, 'SHOES', 'Shoes', 6, 4, '2024-10-10 13:09:03', '16', 'Male', ''),
(26, 'Women Shoe', '13', 432.00, 2456.00, 'SHOES', 'Shoes', 6, 5, '2024-10-10 13:09:21', '16', 'Female', ''),
(27, 'Lalaki Damit', '212', 11.00, 11.00, 'CLOTHES', 'Men', 5, 2, '2024-10-10 15:39:19', 'L', 'Male', 'Blue'),
(28, 'Ihceal', '100', 100.00, 100.00, 'CLOTHES', 'Men', NULL, 2, '2024-10-11 04:11:38', 'XXS', 'Male', 'Red'),
(29, 'Test1', '100', 100.00, 58.00, 'CLOTHES', 'Men', 5, 9, '2024-10-12 17:00:16', 'M', 'Male', 'Black'),
(30, '', '1', 100.00, 60.00, 'ACCESSORIES', 'Cosmetics', NULL, 10, '2024-10-12 17:07:33', '', 'Female', 'Red'),
(31, 'S', '1', 1.00, 1.00, 'CLOTHES', 'Men', NULL, 4, '2024-10-12 17:15:32', 'L', 'Unisex', 'Red');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(25,2) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_level` int(11) NOT NULL,
  `image` varchar(255) DEFAULT 'no_image.jpg',
  `status` int(1) NOT NULL,
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `user_level`, `image`, `status`, `last_login`) VALUES
(1, ' Admin User', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 1, 'no_image.jpg', 1, '2024-10-12 17:39:33'),
(2, 'Special User', 'special', 'ba36b97a41e7faf742ab09bf88405ac04f99599a', 2, 'no_image.jpg', 1, '2015-09-27 21:59:59'),
(3, 'Default User', 'user', '12dea96fec20593566ab75692c9949596833adc9', 3, 'no_image.jpg', 1, '2024-10-12 17:38:47');

-- --------------------------------------------------------

--
-- Table structure for table `user_groups`
--

CREATE TABLE `user_groups` (
  `id` int(11) NOT NULL,
  `group_name` varchar(150) NOT NULL,
  `group_level` int(11) NOT NULL,
  `group_status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_groups`
--

INSERT INTO `user_groups` (`id`, `group_name`, `group_level`, `group_status`) VALUES
(1, 'Admin', 1, 1),
(2, 'special', 2, 1),
(3, 'User', 3, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `category_type`
--
ALTER TABLE `category_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categorie_id` (`categorie_id`),
  ADD KEY `media_id` (`media_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_level` (`user_level`);

--
-- Indexes for table `user_groups`
--
ALTER TABLE `user_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `group_level` (`group_level`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `category_type`
--
ALTER TABLE `category_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_groups`
--
ALTER TABLE `user_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `FK_products` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `SK` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_user` FOREIGN KEY (`user_level`) REFERENCES `user_groups` (`group_level`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
