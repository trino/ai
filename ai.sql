-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 01, 2016 at 04:40 PM
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `additional_toppings`
--

INSERT INTO `additional_toppings` (`id`, `size`, `price`) VALUES
(1, 'Small', 1),
(2, 'Medium', 1.25),
(3, 'Large', 1.5),
(4, 'X-Large', 2),
(6, 'Panzerotti', 1),
(7, 'Delivery', 5);

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
  `keywordtype` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=62 ;

--
-- Dumping data for table `keywords`
--

INSERT INTO `keywords` (`id`, `synonyms`, `weight`, `keywordtype`) VALUES
(1, 'italian', 1, 0),
(2, 'pizza', 5, 0),
(6, '2 two', 1, 1),
(7, 'chicken', 1, 0),
(8, 'wing', 5, 0),
(9, '3 three', 1, 1),
(10, 'dip', 5, 0),
(11, 'cheddar', 1, 0),
(12, 'jalapeno', 1, 0),
(13, 'marinara', 1, 0),
(14, 'bbq barbeque', 1, 0),
(15, 'garlic', 1, 0),
(16, 'parmesan', 1, 0),
(17, 'honey', 1, 0),
(18, 'hot', 1, 0),
(19, 'medium med', 1, 0),
(20, 'mild', 1, 0),
(21, 'ranch', 1, 0),
(22, 'buffalo', 1, 0),
(23, 'spicy', 1, 0),
(24, '1 one', 1, 1),
(25, '4 four', 1, 1),
(26, '5 five', 1, 1),
(27, 'small sm', 1, 2),
(28, 'large lg', 1, 2),
(29, 'extra xl ex', 1, 2),
(30, 'lbl pound lb', 1, 2),
(32, 'drink beverage soda pop can', 5, 0),
(33, 'coke cola', 1, 0),
(34, 'diet', 1, 0),
(35, 'liter litre lt bottle ltr', 1, 0),
(36, 'iced ice tea nestea lipton brisk', 1, 0),
(37, 'ginger ale', 1, 0),
(38, 'pepper doctor', 1, 0),
(39, 'crush', 1, 0),
(40, 'orange', 1, 0),
(41, 'pepsi', 1, 0),
(42, '7up seven', 1, 0),
(44, 'water h20', 1, 0),
(45, 'salad', 5, 0),
(46, 'caesar', 1, 0),
(47, 'greek', 1, 0),
(48, 'garden', 1, 0),
(49, 'side', 1, 0),
(50, 'poutine', 5, 0),
(51, 'french fry frie', 5, 0),
(52, 'wedges potato potatoe', 5, 0),
(53, 'ring', 5, 0),
(54, 'veggie', 5, 0),
(55, 'stick', 1, 0),
(56, 'bread', 5, 0),
(60, 'regular classic', 1, 0),
(61, 'panzerotti calzone pocket panzerottie', 5, 0);

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
  `toppings` tinyint(1) NOT NULL,
  `wings_sauce` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=70 ;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `category_id`, `category`, `item`, `price`, `toppings`, `wings_sauce`) VALUES
(1, 1, 'Pizza', 'Small Pizza', 6.99, 1, 0),
(2, 1, 'Pizza', 'Medium Pizza', 7.99, 1, 0),
(3, 1, 'Pizza', 'Large Pizza', 8.99, 1, 0),
(4, 1, 'Pizza', 'X-Large Pizza', 10.99, 1, 0),
(6, 2, '2 for 1 Pizza', '2 Small Pizza', 19.99, 2, 0),
(7, 2, '2 for 1 Pizza', '2 Medium Pizza', 21.99, 2, 0),
(8, 2, '2 for 1 Pizza', '2 Large Pizza', 33.99, 2, 0),
(9, 2, '2 for 1 Pizza', '2 X-Large Pizza', 45.99, 2, 0),
(11, 3, '3 for 1 Pizza', '3 Small Pizza', 22.99, 3, 0),
(12, 3, '3 for 1 Pizza', '3 Medium Pizza', 33.99, 3, 0),
(13, 3, '3 for 1 Pizza', '3 Large Pizza', 40.99, 3, 0),
(14, 3, '3 for 1 Pizza', '3 X-Large Pizza', 44.99, 3, 0),
(16, 4, 'Wings', '1 Pound Wings', 19.99, 0, 1),
(17, 4, 'Wings', '2 Pound Wings', 21.99, 0, 2),
(18, 4, 'Wings', '3 Pound Wings', 33.99, 0, 3),
(19, 4, 'Wings', '4 Pound Wings', 45.99, 0, 4),
(20, 4, 'Wings', '5 Pound Wings', 45.99, 0, 5),
(22, 5, 'Sides', 'Poutine', 4.99, 0, 0),
(23, 5, 'Sides', 'Potato Wedges', 4.99, 0, 0),
(24, 5, 'Sides', 'Onion Rings', 3.99, 0, 0),
(25, 5, 'Sides', 'Veggie Sticks', 2, 0, 0),
(26, 5, 'Sides', 'Garlic Bread', 1.5, 0, 0),
(27, 5, 'Sides', 'French Fries', 2.99, 0, 0),
(28, 5, 'Sides', 'Chicken Salad', 7.99, 0, 0),
(29, 5, 'Sides', 'Side Salad', 3.99, 0, 0),
(30, 5, 'Sides', 'Caesar Salad', 3.99, 0, 0),
(31, 5, 'Sides', 'Greek Salad', 3.99, 0, 0),
(32, 5, 'Sides', 'Garden Salad', 3.99, 0, 0),
(35, 6, 'Dips', 'Cheddar Jalapeno', 0.95, 0, 0),
(36, 6, 'Dips', 'Marinara', 0.95, 0, 0),
(37, 6, 'Dips', 'Garlic Parmesan', 0.95, 0, 0),
(38, 6, 'Dips', 'BBQ Sauce', 0.95, 0, 0),
(39, 6, 'Dips', 'Cheddar Sauce', 0.95, 0, 0),
(40, 6, 'Dips', 'Creamy Garlic Sauce', 0.95, 0, 0),
(41, 6, 'Dips', 'Honey Garlic Sauce', 0.95, 0, 0),
(42, 6, 'Dips', 'Hot Sauce', 0.95, 0, 0),
(43, 6, 'Dips', 'Marinara Sauce', 0.95, 0, 0),
(44, 6, 'Dips', 'Medium Sauce', 0.95, 0, 0),
(45, 6, 'Dips', 'Mild Sauce', 0.95, 0, 0),
(46, 6, 'Dips', 'Ranch Sauce', 0.95, 0, 0),
(47, 6, 'Dips', 'Spicy Buffalo Sauce', 0.95, 0, 0),
(50, 7, 'Drinks', 'Diet Pepsi', 0.95, 0, 0),
(51, 7, 'Drinks', 'Pepsi', 0.95, 0, 0),
(52, 7, 'Drinks', 'Coca-Cola', 0.95, 0, 0),
(53, 7, 'Drinks', 'Diet Coca-Cola', 0.95, 0, 0),
(54, 7, 'Drinks', '7-up', 0.95, 0, 0),
(55, 7, 'Drinks', 'Crush Orange', 0.95, 0, 0),
(56, 7, 'Drinks', 'Dr. Pepper', 0.95, 0, 0),
(57, 7, 'Drinks', 'Ginger Ale', 0.95, 0, 0),
(58, 7, 'Drinks', 'Iced Tea', 0.95, 0, 0),
(59, 7, 'Drinks', 'Water Bottle', 0.95, 0, 0),
(60, 7, 'Drinks', '2L Diet Pepsi', 2.99, 0, 0),
(61, 7, 'Drinks', '2L Pepsi', 2.99, 0, 0),
(62, 7, 'Drinks', '2L Coca-Cola', 2.99, 0, 0),
(63, 7, 'Drinks', '2L Diet Coca-Cola', 2.99, 0, 0),
(64, 7, 'Drinks', '2L 7-up', 2.99, 0, 0),
(65, 7, 'Drinks', '2L Crush Orange', 2.99, 0, 0),
(66, 7, 'Drinks', '2L Dr. Pepper', 2.99, 0, 0),
(67, 7, 'Drinks', '2L Ginger Ale', 2.99, 0, 0),
(68, 7, 'Drinks', '2L Iced Tea', 2.99, 0, 0),
(69, 8, 'Panzerotti', 'Panzerotti', 5.99, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `menukeywords`
--

CREATE TABLE IF NOT EXISTS `menukeywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menuitem_id` int(11) NOT NULL,
  `keyword_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=120 ;

--
-- Dumping data for table `menukeywords`
--

INSERT INTO `menukeywords` (`id`, `menuitem_id`, `keyword_id`) VALUES
(9, -1, 2),
(11, -2, 2),
(12, -2, 6),
(13, -3, 2),
(15, -3, 9),
(16, -6, 10),
(17, -4, 8),
(18, -4, 7),
(19, 39, 11),
(20, 35, 11),
(21, 35, 12),
(22, 36, 13),
(23, 38, 14),
(24, 37, 15),
(25, 37, 16),
(26, 41, 15),
(27, 41, 17),
(28, 42, 18),
(29, 44, 19),
(30, 45, 20),
(31, 46, 21),
(32, 47, 22),
(33, 47, 23),
(34, 16, 24),
(35, 17, 6),
(36, 18, 9),
(37, 19, 25),
(39, 1, 27),
(40, 2, 19),
(41, 6, 27),
(42, 7, 19),
(43, 8, 28),
(44, 9, 29),
(45, -1, 24),
(46, 3, 28),
(47, 4, 29),
(48, 11, 27),
(49, 12, 19),
(50, 13, 28),
(51, 14, 29),
(52, 20, 26),
(54, -4, 30),
(57, -7, 32),
(58, 52, 33),
(59, 53, 33),
(60, 53, 34),
(61, 62, 35),
(62, 62, 33),
(63, 63, 35),
(64, 63, 34),
(66, 63, 33),
(68, 60, 34),
(69, 60, 35),
(70, 64, 35),
(71, 65, 35),
(72, 66, 35),
(73, 67, 35),
(74, 68, 35),
(75, 68, 36),
(76, 67, 37),
(77, 66, 38),
(78, 65, 39),
(79, 65, 40),
(80, 40, 15),
(81, 43, 13),
(82, 50, 34),
(83, 50, 41),
(84, 51, 41),
(85, 54, 42),
(86, 55, 39),
(87, 55, 40),
(88, 56, 38),
(89, 57, 37),
(90, 58, 36),
(91, 59, 35),
(92, 59, 44),
(93, 28, 45),
(94, 28, 7),
(95, 30, 45),
(96, 30, 46),
(97, 31, 47),
(98, 31, 45),
(99, 32, 45),
(100, 32, 48),
(101, 29, 45),
(102, 29, 49),
(103, 61, 35),
(104, 61, 41),
(105, 22, 50),
(106, 27, 51),
(107, 23, 52),
(108, 24, 53),
(109, 25, 54),
(110, 25, 55),
(111, 26, 15),
(112, 26, 56),
(116, 51, 60),
(119, -8, 61);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `placed_at` timestamp NOT NULL,
  `number` int(11) NOT NULL,
  `unit` varchar(16) NOT NULL,
  `buzzcode` varchar(32) NOT NULL,
  `street` varchar(255) NOT NULL,
  `postalcode` varchar(16) NOT NULL,
  `city` varchar(64) NOT NULL,
  `province` varchar(32) NOT NULL,
  `latitude` varchar(16) NOT NULL,
  `longitude` varchar(16) NOT NULL,
  `accepted_at` timestamp NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `payment_type` tinyint(4) NOT NULL,
  `phone` varchar(16) NOT NULL,
  `cell` varchar(16) NOT NULL,
  `paid` tinyint(4) NOT NULL,
  `stripeToken` varchar(64) NOT NULL,
  `deliverytime` varchar(64) NOT NULL,
  `cookingnotes` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `placed_at`, `number`, `unit`, `buzzcode`, `street`, `postalcode`, `city`, `province`, `latitude`, `longitude`, `accepted_at`, `restaurant_id`, `type`, `payment_type`, `phone`, `cell`, `paid`, `stripeToken`, `deliverytime`, `cookingnotes`, `status`, `price`) VALUES
(5, 1, '2016-09-28 05:12:54', 2396, '', '', 'Sinclair Circle', 'L7P 3C3', 'Burlington', 'Ontario', '43.3657646', '-79.836220299999', '0000-00-00 00:00:00', 0, 0, 0, '', '', 0, '', '3:15 pm', 'texffd', 0, '0.00'),
(13, 1, '2016-09-28 18:07:33', 197, 'basement', '', 'Yonge Street', 'M5B 1M4', 'Toronto', 'Ontario', '43.6533455999999', '-79.379373100000', '0000-00-00 00:00:00', 0, 0, 0, '', '', 0, '', '', '', 0, '0.00'),
(14, 1, '2016-09-28 18:07:47', 197, 'basement', '', 'Yonge Street', 'M5B 1M4', 'Toronto', 'Ontario', '43.6533455999999', '-79.379373100000', '0000-00-00 00:00:00', 0, 0, 0, '', '', 0, '', '', '', 0, '0.00'),
(15, 1, '2016-09-28 21:48:27', 2396, 'basement', '', 'Sinclair Circle', 'L7P 3C3', 'Burlington', 'Ontario', '43.3657646', '-79.836220299999', '0000-00-00 00:00:00', 0, 0, 0, '', '9055123067', 0, '', '', '', 0, '0.00'),
(30, 1, '2016-10-04 19:15:26', 2396, 'basement', '', 'Sinclair Circle', 'L7P 3C3', 'Burlington', 'Ontario', '43.3657646', '-79.836220299999', '0000-00-00 00:00:00', 0, 0, 0, '', '9055123067', 0, '', '', '', 0, '0.00'),
(31, 1, '2016-10-11 19:39:37', 2396, 'basement', '', 'Sinclair Circle', 'L7P 3C3', 'Burlington', 'Ontario', '43.3657646', '-79.836220299999', '0000-00-00 00:00:00', 0, 0, 0, '', '', 0, '', '', '', 0, '0.00'),
(32, 1, '2016-10-11 19:41:05', 2396, 'basement', '', 'Sinclair Circle', 'L7P 3C3', 'Burlington', 'Ontario', '43.3657646', '-79.836220299999', '0000-00-00 00:00:00', 0, 0, 0, '', '', 0, '', '', '', 0, '0.00'),
(33, 1, '2016-10-11 21:07:17', 2396, 'basement', '', 'Sinclair Circle', 'L7P 3C3', 'Burlington', 'Ontario', '43.3657646', '-79.836220299999', '0000-00-00 00:00:00', 1, 0, 0, '', '', 0, '', '', '', 0, '0.00'),
(34, 1, '2016-10-11 21:13:41', 2396, 'basement', '', 'Sinclair Circle', 'L7P 3C3', 'Burlington', 'Ontario', '43.3657646', '-79.836220299999', '0000-00-00 00:00:00', 1, 0, 0, '', '', 0, '', '1476208800', 'trydffghhhf', 0, '0.00'),
(35, 1, '2016-10-11 21:41:26', 2396, 'basement', '', 'Sinclair Circle', 'L7P 3C3', 'Burlington', 'Ontario', '43.3657646', '-79.836220299999', '0000-00-00 00:00:00', 1, 0, 0, '', '', 0, '', '1476211500', '', 0, '0.00'),
(36, 1, '2016-10-18 21:16:42', 2396, 'basement', '', 'Sinclair Circle', 'L7P 3C3', 'Burlington', 'Ontario', '43.3657646', '-79.836220299999', '0000-00-00 00:00:00', 1, 0, 0, '', '', 0, '', 'Deliver ASAP', '', 0, '0.00'),
(37, 1, '2016-11-01 18:10:40', 2396, 'up stairs', '', 'Sinclair Circle', 'L7P 3C3', 'Burlington', 'Ontario', '43.3657646', '-79.836220299999', '0000-00-00 00:00:00', 1, 0, 0, '', '', 0, '', 'Deliver ASAP', '', 0, '1.23');

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
-- Table structure for table `presets`
--

CREATE TABLE IF NOT EXISTS `presets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `toppings` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `presets`
--

INSERT INTO `presets` (`id`, `name`, `toppings`) VALUES
(1, 'hawaiian', 'pineapple bacon ham'),
(2, 'canadian', 'pepperoni mushrooms bacon'),
(3, 'deluxe', 'pepperoni mushrooms green peppers'),
(4, 'vegetarian', 'mushrooms tomatoes green peppers'),
(5, 'meat', 'sausage salami bacon pepperoni'),
(6, 'super', 'pepperoni mushrooms green peppers'),
(7, 'supreme', 'pepperoni mushrooms green peppers');

-- --------------------------------------------------------

--
-- Table structure for table `restaurants`
--

CREATE TABLE IF NOT EXISTS `restaurants` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
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
  `address_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `restaurants`
--

INSERT INTO `restaurants` (`id`, `name`, `slug`, `email`, `phone`, `cuisine`, `website`, `description`, `logo`, `is_delivery`, `is_pickup`, `max_delivery_distance`, `delivery_fee`, `minimum`, `is_complete`, `lastorder_id`, `franchise`, `address_id`) VALUES
(1, 'Home', '', '', '(905) 512-3067', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyname` varchar(255) NOT NULL,
  `value` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `keyname` (`keyname`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=55 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `keyname`, `value`) VALUES
(1, 'lastSQL', '1477502595'),
(20, 'orders', '1477502564'),
(24, 'menucache', '1478014539');

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
  `isall` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=39 ;

--
-- Dumping data for table `toppings`
--

INSERT INTO `toppings` (`id`, `name`, `type`, `isfree`, `qualifiers`, `isall`) VALUES
(1, 'Anchovies', 'Meat', 0, '', 0),
(2, 'Artichoke Heart', 'Vegetable', 0, '', 0),
(3, 'Bacon', 'Meat', 0, '', 0),
(4, 'Beef Salami', 'Meat', 0, '', 0),
(5, 'Black Olives', 'Vegetable', 0, '', 0),
(6, 'Broccoli', 'Vegetable', 0, '', 0),
(7, 'Cheddar', 'Cheese', 0, '', 0),
(8, 'Cheese', 'Cheese', 0, '', 0),
(9, 'Chicken', 'Meat', 0, '', 0),
(11, 'Feta Cheese', 'Cheese', 0, '', 0),
(12, 'Fresh Mushroom', 'Vegetable', 0, '', 0),
(13, 'Green Olives', 'Vegetable', 0, '', 0),
(14, 'Green Peppers', 'Vegetable', 0, '', 0),
(15, 'Ground Beef', 'Meat', 0, '', 0),
(16, 'Ham', 'Meat', 0, '', 0),
(17, 'Hot Banana Peppers', 'Vegetable', 0, '', 0),
(18, 'Hot Italian Sausage', 'Meat', 0, '', 0),
(19, 'Hot Peppers', 'Vegetable', 0, '', 0),
(20, 'Hot Sausage', 'Meat', 0, '', 0),
(21, 'Italian Sausage', 'Meat', 0, '', 0),
(22, 'Tomato Sauce', 'Preparation', 1, '', 0),
(23, 'Jalapeno Peppers', 'Vegetable', 0, '', 0),
(24, 'Mild Sausage', 'Meat', 0, '', 0),
(25, 'Mixed Cheese', 'Cheese', 0, '', 0),
(26, 'Mozzarella Cheese', 'Cheese', 0, '', 0),
(27, 'Mushrooms', 'Vegetable', 0, '', 0),
(28, 'Onions', 'Vegetable', 0, '', 0),
(29, 'Parmesan Cheese', 'Cheese', 0, '', 0),
(30, 'Pepperoni', 'Meat', 0, '', 0),
(31, 'Pineapple', 'Vegetable', 0, '', 0),
(32, 'Red Onions', 'Vegetable', 0, '', 0),
(33, 'Red Peppers', 'Vegetable', 0, '', 0),
(34, 'Salami', 'Meat', 0, '', 0),
(35, 'Spinach', 'Vegetable', 0, '', 0),
(36, 'Sundried Tomatoes', 'Vegetable', 0, '', 0),
(37, 'Tomatoes', 'Vegetable', 0, '', 0),
(38, 'Cooked', 'Preparation', 1, 'Lightly done, Regular, Well done', 1);

-- --------------------------------------------------------

--
-- Table structure for table `useraddresses`
--

CREATE TABLE IF NOT EXISTS `useraddresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `unit` varchar(32) NOT NULL,
  `buzzcode` varchar(16) NOT NULL,
  `street` varchar(255) NOT NULL,
  `postalcode` varchar(16) NOT NULL,
  `city` varchar(64) NOT NULL,
  `province` varchar(32) NOT NULL,
  `latitude` varchar(16) NOT NULL,
  `longitude` varchar(16) NOT NULL,
  `phone` varchar(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `useraddresses`
--

INSERT INTO `useraddresses` (`id`, `user_id`, `number`, `unit`, `buzzcode`, `street`, `postalcode`, `city`, `province`, `latitude`, `longitude`, `phone`) VALUES
(1, 1, 2396, 'up stairs', '', 'Sinclair Circle', 'L7P 3C3', 'Burlington', 'Ontario', '43.3657646', '-79.836220299999', ''),
(20, 1, 183, '', '', 'Lottridge Street', 'L8L 6V6', 'Hamilton', 'Ontario', '43.2557729', '-79.831154999999', ''),
(21, 13, 183, '', '', 'Dundas Street West', 'M5G 1C7', 'Toronto', 'Ontario', '43.6549701', '-79.386574399999', ''),
(22, 14, 183, '', '', 'Dundas Street West', 'M5G 1C7', 'Toronto', 'Ontario', '43.6549701', '-79.386574399999', '');

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
  `phone` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `lastlogin` bigint(20) NOT NULL,
  `loginattempts` int(11) NOT NULL,
  `profiletype` tinyint(4) NOT NULL,
  `authcode` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `cc_fname` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `cc_lname` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `cc_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cc_xyear` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cc_xmonth` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cc_cc` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `cc_addressid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`, `phone`, `lastlogin`, `loginattempts`, `profiletype`, `authcode`, `cc_fname`, `cc_lname`, `cc_number`, `cc_xyear`, `cc_xmonth`, `cc_cc`, `cc_addressid`) VALUES
(1, 'Roy Hodson', 'roy@trinoweb.com', '$2y$10$CLqrYcTDDnj04HxZM0VKd.i3G2jQoucxcoV3TBULph2vdc8oniGN6', '', '0000-00-00 00:00:00', '2016-10-26 18:22:14', '(905) 512-3067', 1477491712, 3, 1, '', 'eyJpdiI6Ikp2c3BNQjFONVhHSSsrZjhCQjJzV2c9PSIsInZhbHVlIjoiQ1puNVFJajUyMktKVlVGVXRnbDhLQT09IiwibWFjIjoiODZlODNlMzZlNWZlYTE5NmEyZGFkOGExZjY0ZmZkMDI1YTY5MjcwZDM3N2Y3ZTdjNmUxMDNjYTQ0ZTk2ZDljNiJ9', 'eyJpdiI6IjhKMEUrVmtpcXBnVlhuVzdXTk5vQ2c9PSIsInZhbHVlIjoiQ3N1bFVsQ2NLa1Y0SlFHNXpQU3JJQT09IiwibWFjIjoiOGZmMzc2MjJiYmE4YTYwZmJmMmY5YWNhMTFlNWE1MzgxM2E4OWEyZWYxZTQ2ZGFiOTI2YzRjYmUwMzUzYmJmYiJ9', 'eyJpdiI6ImlQWW81VTFFNGRpVVNFaytUZHcwWmc9PSIsInZhbHVlIjoiZExvajhPWjN1Y09lMEFWWGx0SHQxNjFsdG5Ea3BlcWdIenVtZ0J6Z0Facz0iLCJtYWMiOiI4MjBhMzNjZTE5OGUzYTYxZjM4NGRhYjM1MzgwMjMwNzUyODI3MDFmNGI1MzE3NjY4YTBiZjAzOTE0NTJjZDNiIn0=', 'eyJpdiI6IllIN3EyNFpJMzgxSEJSeTF4dEU2YkE9PSIsInZhbHVlIjoiWWQ5YTdpeldKdkROWjRndWRRcWRaUT09IiwibWFjIjoiZWU3MGVmZDNiNGZjMDY1NmVlNTg4MzY5MDcwNTA1YzE0MGRlNjhlODgzMmMzZTU0ZjFiNDQ1ZTFhOTFjM2IwNCJ9', 'eyJpdiI6IjZxMkVWZjBVNWdpUGx0Z013eG9Db1E9PSIsInZhbHVlIjoic1wvaWN3SzFjNXcyc1wvZGIzYUpvVXJ3PT0iLCJtYWMiOiJhZDZhOWI3NzRhZWVlNTNmMmRkODc1YWM4MjU4MDYyYzQwNWNkMzczZTllODBhYTExZWEwMTM5NjEwYjRkYmQ2In0=', 'eyJpdiI6InFDd3lFaHEwMFwveURuaGlKeTNNM2JRPT0iLCJ2YWx1ZSI6InEzVW1CXC9aOUo3TnF3cm53Mkdka2VRPT0iLCJtYWMiOiI4YTcyZGVhN2YxM2Y0ZTI1ZmY5NGJjNzY2MzhlOTU2ZDE0NTc1MTA1YWVhZmNiYWE1MTZkNTE2ZTNlOTgwMTU0In0=', 1),
(11, 'Van Trinh', 'info@trinoweb.com', '$2y$10$2yAesihK6otSPNnVtnpnIOEO3Ec.6n2mrN1VWoL3qoETP1.T66PcC', '', '2016-09-20 21:19:32', '0000-00-00 00:00:00', '(905) 512-3067', 0, 0, 0, '', '', '', '', '', '', '', 0),
(12, 'Van Trinhb', 'info@gmail.com', '$2y$10$tnOFCR/UlrzmZENb2IO8Uu8.K4T/i0avXFAHcxYEtgqvXcGc7jyYy', '', '2016-10-12 17:25:35', '0000-00-00 00:00:00', '9055315331', 0, 0, 0, 'EC3558B1-3FD1-435B-9EE5-2E974C2C1BFC', '', '', '', '', '', '', 0),
(13, 'Van Trinh', 'roy2@trinoweb.com', '$2y$10$GLJLyeWh1slbMcFUg8jIne17CieNfWVAShrFFCnu8kkVZPDW9aPgq', '', '2016-10-12 17:50:58', '0000-00-00 00:00:00', '', 0, 0, 0, 'E2770638-AB90-409D-A336-DD1ACE9C6BA4', '', '', '', '', '', '', 0),
(14, 'Van Trinh', 'Roy+TeacherTest@trinoweb.com', '$2y$10$D.rVhp9vKl/LyA5WS8eXheHEVaD28rTew7atyzlqmwESBfbYABFHC', '', '2016-10-18 17:11:48', '0000-00-00 00:00:00', '', 0, 0, 0, 'B045B7D4-C76F-4204-A96F-9DC0A0F4DE5A', '', '', '', '', '', '', 0);

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
  `isall` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `wings_sauce`
--

INSERT INTO `wings_sauce` (`id`, `name`, `type`, `isfree`, `qualifiers`, `isall`) VALUES
(1, 'Mild', 'Sauce', 0, '', 1),
(2, 'Medium', 'Sauce', 0, '', 1),
(3, 'Hot', 'Sauce', 0, '', 1),
(4, 'Suicide', 'Sauce', 0, '', 1),
(5, 'BBQ', 'Sauce', 0, '', 1),
(6, 'Honey Garlic', 'Sauce', 0, '', 1),
(7, 'Cooked', 'Preparation', 1, 'Lightly done, Regular, Well done', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
