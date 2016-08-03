-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 03, 2016 at 04:33 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ai`
--

-- --------------------------------------------------------

--
-- Table structure for table `additional_toppings`
--

CREATE TABLE IF NOT EXISTS `additional_toppings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `size` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `additional_toppings`
--

INSERT INTO `additional_toppings` (`id`, `size`, `price`) VALUES
(1, 'Small', 1),
(2, 'Medium', 1.25),
(3, 'Large', 1.5),
(4, 'X-Large', 2);

-- --------------------------------------------------------

--
-- Table structure for table `hours`
--

CREATE TABLE IF NOT EXISTS `hours` (
  `restraunt_id` int(11) NOT NULL,
  `monday_open` time NOT NULL,
  `monday_close` time NOT NULL,
  `tuesday_open` time NOT NULL,
  `tuesday_close` time NOT NULL,
  `wednesday_open` time NOT NULL,
  `wednesday_close` time NOT NULL,
  `thursday_open` time NOT NULL,
  `thursday_close` time NOT NULL,
  `friday_open` time NOT NULL,
  `friday_close` time NOT NULL,
  `saturday_open` time NOT NULL,
  `saturday_close` time NOT NULL,
  `sunday_open` time NOT NULL,
  `sunday_close` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `keywords`
--

CREATE TABLE IF NOT EXISTS `keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `synonyms` varchar(1024) NOT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `keywords`
--

INSERT INTO `keywords` (`id`, `synonyms`, `weight`) VALUES
(1, 'italian', 1),
(2, 'pizza pizzas', 2),
(3, 'pastry', 1),
(4, 'donuts timbits', 2),
(6, '2 two', 1),
(7, 'chicken', 1),
(8, 'wings', 1),
(9, '3 three', 1);

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE IF NOT EXISTS `locations` (
  `restraunt_id` int(11) NOT NULL,
  `formatted_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `province` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `postal_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) NOT NULL,
  `category` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `item` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `toppings` char(255) COLLATE utf8_unicode_ci NOT NULL,
  `wings_sauce` char(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=69 ;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `category_id`, `category`, `item`, `price`, `toppings`, `wings_sauce`) VALUES
(1, 1, 'Pizza', 'Small Pizza', 6.99, '1', ''),
(2, 1, 'Pizza', 'Medium Pizza', 7.99, '1', ''),
(3, 1, 'Pizza', 'Large Pizza', 8.99, '1', ''),
(4, 1, 'Pizza', 'X-Large Pizza', 10.99, '1', ''),
(6, 2, '2 for 1 Pizza', '2 Small Pizza', 19.99, '1', ''),
(7, 2, '2 for 1 Pizza', '2 Medium Pizza', 21.99, '1', ''),
(8, 2, '2 for 1 Pizza', '2 Large Pizza', 33.99, '1', ''),
(9, 2, '2 for 1 Pizza', '2 X-Large Pizza', 45.99, '1', ''),
(11, 3, '3 for 1 Pizza', '3 Small Pizza', 22.99, '1', ''),
(12, 3, '3 for 1 Pizza', '3 Medium Pizza', 33.99, '1', ''),
(13, 3, '3 for 1 Pizza', '3 Large Pizza', 40.99, '1', ''),
(14, 3, '3 for 1 Pizza', '3 X-Large Pizza', 44.99, '1', ''),
(16, 4, 'Wings', '1 Pound Wings', 19.99, '', '1'),
(17, 4, 'Wings', '2 Pound Wings', 21.99, '', '1'),
(18, 4, 'Wings', '3 Pound Wings', 33.99, '', '1'),
(19, 4, 'Wings', '4 Pound Wings', 45.99, '', '1'),
(20, 4, 'Wings', '5 Pound Wings', 45.99, '', '1'),
(22, 5, 'Sides', 'Poutine', 4.99, '', ''),
(23, 5, 'Sides', 'Potato Wedges', 4.99, '', ''),
(24, 5, 'Sides', 'Onion Rings', 3.99, '', ''),
(25, 5, 'Sides', 'Veggie Sticks', 2, '', ''),
(26, 5, 'Sides', 'Garlic Bread', 1.5, '', ''),
(27, 5, 'Sides', 'French Fries', 2.99, '', ''),
(28, 5, 'Sides', 'Chicken Salad', 7.99, '', ''),
(29, 5, 'Sides', 'Side Salad', 3.99, '', ''),
(30, 5, 'Sides', 'Caesar Salad', 3.99, '', ''),
(31, 5, 'Sides', 'Greek Salad', 3.99, '', ''),
(32, 5, 'Sides', 'Garden Salad', 3.99, '', ''),
(35, 6, 'Dips', 'Cheddar Jalapeno', 0.95, '', ''),
(36, 6, 'Dips', 'Marinara', 0.95, '', ''),
(37, 6, 'Dips', 'Garlic Parmesan', 0.95, '', ''),
(38, 6, 'Dips', 'BBQ Sauce', 0.95, '', ''),
(39, 6, 'Dips', 'Cheddar Sauce', 0.95, '', ''),
(40, 6, 'Dips', 'Creamy Garlic Sauce', 0.95, '', ''),
(41, 6, 'Dips', 'Honey Garlic Sauce', 0.95, '', ''),
(42, 6, 'Dips', 'Hot Sauce', 0.95, '', ''),
(43, 6, 'Dips', 'Marinara Sauce', 0.95, '', ''),
(44, 6, 'Dips', 'Medium Sauce', 0.95, '', ''),
(45, 6, 'Dips', 'Mild Sauce', 0.95, '', ''),
(46, 6, 'Dips', 'Ranch Sauce', 0.95, '', ''),
(47, 6, 'Dips', 'Spicy Buffalo Sauce', 0.95, '', ''),
(50, 7, 'Drinks', 'Diet Pepsi', 0.95, '', ''),
(51, 7, 'Drinks', 'Pepsi', 0.95, '', ''),
(52, 7, 'Drinks', 'Coca-Cola', 0.95, '', ''),
(53, 7, 'Drinks', 'Diet Coca-Cola', 0.95, '', ''),
(54, 7, 'Drinks', '7-up', 0.95, '', ''),
(55, 7, 'Drinks', 'Crush Orange', 0.95, '', ''),
(56, 7, 'Drinks', 'Dr. Pepper', 0.95, '', ''),
(57, 7, 'Drinks', 'Ginger Ale', 0.95, '', ''),
(58, 7, 'Drinks', 'Iced Tea', 0.95, '', ''),
(59, 7, 'Drinks', 'Water Bottle', 0.95, '', ''),
(60, 7, 'Drinks', '2L Diet Pepsi', 2.99, '', ''),
(61, 7, 'Drinks', '2L Pepsi', 2.99, '', ''),
(62, 7, 'Drinks', '2L Coca-Cola', 2.99, '', ''),
(63, 7, 'Drinks', '2L Diet Coca-Cola', 2.99, '', ''),
(64, 7, 'Drinks', '2L 7-up', 2.99, '', ''),
(65, 7, 'Drinks', '2L Crush Orange', 2.99, '', ''),
(66, 7, 'Drinks', '2L Dr. Pepper', 2.99, '', ''),
(67, 7, 'Drinks', '2L Ginger Ale', 2.99, '', ''),
(68, 7, 'Drinks', '2L Iced Tea', 2.99, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `menukeywords`
--

CREATE TABLE IF NOT EXISTS `menukeywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menuitem_id` int(11) NOT NULL,
  `keyword_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `menukeywords`
--

INSERT INTO `menukeywords` (`id`, `menuitem_id`, `keyword_id`) VALUES
(9, -1, 2),
(11, -2, 2),
(12, -2, 6),
(13, -3, 2),
(15, -3, 9);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2014_10_12_000000_create_users_table', 1),
('2014_10_12_100000_create_password_resets_table', 1),
('2016_02_04_041533_create_tasks_table', 1),
('2016_07_09_200740_create_locations_table', 1),
('2016_07_09_200754_create_hours_table', 1),
('2016_07_09_200804_create_restaurants_table', 1),
('2016_07_24_170400_menu', 1),
('2016_07_24_170421_additional_toppings', 1),
('2016_07_24_170442_wings_sauce', 1),
('2016_07_24_170457_toppings', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL,
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `restaurants`
--

CREATE TABLE IF NOT EXISTS `restaurants` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` int(11) NOT NULL,
  `cuisine` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_delivery` int(11) NOT NULL,
  `is_pickup` int(11) NOT NULL,
  `max_delivery_distance` int(11) NOT NULL,
  `delivery_fee` int(11) NOT NULL,
  `minimum` int(11) NOT NULL,
  `is_complete` int(11) NOT NULL,
  `lastorder_id` int(11) NOT NULL,
  `franchise` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tasks_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `toppings`
--

CREATE TABLE IF NOT EXISTS `toppings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `isfree` tinyint(1) NOT NULL,
  `qualifiers` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'comma delimited list of the names for 1/2,x1,x2 if applicable',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=39 ;

--
-- Dumping data for table `toppings`
--

INSERT INTO `toppings` (`id`, `name`, `type`, `isfree`, `qualifiers`) VALUES
(1, 'Anchovies', 'Meat', 0, ''),
(2, 'Artichoke Hearts', 'Vegetable', 0, ''),
(3, 'Bacon', 'Meat', 0, ''),
(4, 'Beef Salami', 'Meat', 0, ''),
(5, 'Black Olives', 'Vegetable', 0, ''),
(6, 'Broccoli', 'Vegetable', 0, ''),
(7, 'Cheddar', 'Cheese', 0, ''),
(8, 'Cheese', 'Cheese', 0, ''),
(9, 'Chicken', 'Meat', 0, ''),
(10, 'Extra Cheese', 'Cheese', 0, ''),
(11, 'Feta Cheese', 'Cheese', 0, ''),
(12, 'Fresh Mushrooms', 'Vegetable', 0, ''),
(13, 'Green Olives', 'Vegetable', 0, ''),
(14, 'Green Peppers', 'Vegetable', 0, ''),
(15, 'Ground Beef', 'Meat', 0, ''),
(16, 'Ham', 'Meat', 0, ''),
(17, 'Hot Banana Peppers', 'Vegetable', 0, ''),
(18, 'Hot Italian Sausage', 'Meat', 0, ''),
(19, 'Hot Peppers', 'Vegetable', 0, ''),
(20, 'Hot Sausage', 'Meat', 0, ''),
(21, 'Italian Sausage', 'Meat', 0, ''),
(22, 'Italian Tomato Sauce', 'Preparation', 1, ''),
(23, 'Jalapeno Peppers', 'Vegetable', 0, ''),
(24, 'Mild Sausage', 'Meat', 0, ''),
(25, 'Mixed Cheese', 'Cheese', 0, ''),
(26, 'Mozzarella Cheese', 'Cheese', 0, ''),
(27, 'Mushrooms', 'Vegetable', 0, ''),
(28, 'Onions', 'Vegetable', 0, ''),
(29, 'Parmesan Cheese', 'Cheese', 0, ''),
(30, 'Pepperoni', 'Meat', 0, ''),
(31, 'Pineapple', 'Vegetable', 0, ''),
(32, 'Red Onions', 'Vegetable', 0, ''),
(33, 'Red Peppers', 'Vegetable', 0, ''),
(34, 'Salami', 'Meat', 0, ''),
(35, 'Spinach', 'Vegetable', 0, ''),
(36, 'Sundried Tomatoes', 'Vegetable', 0, ''),
(37, 'Tomatoes', 'Vegetable', 0, ''),
(38, 'Cooked', 'Preparation', 1, 'Lightly done, Regular, Well done');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wings_sauce`
--

CREATE TABLE IF NOT EXISTS `wings_sauce` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `isfree` tinyint(1) NOT NULL,
  `qualifiers` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'comma delimited list of the names for 1/2,x1,x2 if applicable',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `wings_sauce`
--

INSERT INTO `wings_sauce` (`id`, `name`, `type`, `isfree`, `qualifiers`) VALUES
(1, 'Mild', 'Sauce', 0, ''),
(2, 'Medium', 'Sauce', 0, ''),
(3, 'Hot', 'Sauce', 0, ''),
(4, 'Suicide', 'Sauce', 0, ''),
(5, 'BBQ', 'Sauce', 0, ''),
(6, 'Honey Garlic', 'Sauce', 0, ''),
(7, 'Cooked', 'Preparation', 1, 'Lightly done, Regular, Well done');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
