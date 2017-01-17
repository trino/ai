-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 17, 2017 at 04:51 PM
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
(1, 'Small', 0.79),
(2, 'Medium', 0.79),
(3, 'Large', 0.79),
(4, 'X-Large', 0.79),
(6, 'Panzerotti', 1.2),
(7, 'Delivery', 3.99);

-- --------------------------------------------------------

--
-- Table structure for table `hours`
--

CREATE TABLE IF NOT EXISTS `hours` (
  `restaurant_id` int(11) NOT NULL,
  `0_open` smallint(6) NOT NULL,
  `0_close` smallint(6) NOT NULL,
  `1_open` smallint(6) NOT NULL,
  `1_close` smallint(6) NOT NULL,
  `2_open` smallint(6) NOT NULL,
  `2_close` smallint(6) NOT NULL,
  `3_open` smallint(6) NOT NULL,
  `3_close` smallint(6) NOT NULL,
  `4_open` smallint(6) NOT NULL,
  `4_close` smallint(6) NOT NULL,
  `5_open` smallint(6) NOT NULL,
  `5_close` smallint(6) NOT NULL,
  `6_open` smallint(6) NOT NULL,
  `6_close` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `hours`
--

INSERT INTO `hours` (`restaurant_id`, `0_open`, `0_close`, `1_open`, `1_close`, `2_open`, `2_close`, `3_open`, `3_close`, `4_open`, `4_close`, `5_open`, `5_close`, `6_open`, `6_close`) VALUES
(0, -1, -1, 1100, 2250, 1100, 2250, 1100, 2250, 1100, 2250, 1100, 50, 1100, 50);

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(10) unsigned NOT NULL,
  `category_id` int(10) NOT NULL,
  `category` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `item` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `toppings` tinyint(1) NOT NULL,
  `wings_sauce` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `category_id`, `category`, `item`, `price`, `toppings`, `wings_sauce`) VALUES
(1, 1, 'Pizza', 'Small Pizza', 5.99, 1, 0),
(2, 1, 'Pizza', 'Medium Pizza', 6.99, 1, 0),
(3, 1, 'Pizza', 'Large Pizza', 8.99, 1, 0),
(4, 1, 'Pizza', 'X-Large Pizza', 11.99, 1, 0),
(5, 2, '241 Pizza', '2 Small Pizzas', 10.99, 2, 0),
(6, 2, '241 Pizza', '2 Medium Pizzas', 12.99, 2, 0),
(7, 2, '241 Pizza', '2 Large Pizzas', 15.99, 2, 0),
(8, 2, '241 Pizza', '2 X-Large Pizzas', 19.99, 2, 0),
(15, 4, 'Wings', '1 Lbs Wings', 6.99, 0, 1),
(16, 4, 'Wings', '2 Lbs Wings', 15.99, 0, 2),
(17, 4, 'Wings', '3 Lbs Wings', 17.99, 0, 3),
(18, 4, 'Wings', '4 Lbs Wings', 24.99, 0, 4),
(19, 4, 'Wings', '5 Lbs Wings', 28.99, 0, 5),
(20, 5, 'Sides', 'Panzerotti', 5.99, 1, 0),
(21, 5, 'Sides', 'Garlic Bread', 2.25, 0, 0),
(22, 5, 'Sides', 'French Fries', 3.99, 0, 0),
(23, 5, 'Sides', 'Potato Wedges', 3.99, 0, 0),
(24, 5, 'Sides', '12 Chicken Nuggets', 4.99, 0, 0),
(25, 5, 'Sides', '24 Chicken Nuggets', 7.99, 0, 0),
(26, 5, 'Sides', 'Large Lasagna', 6.99, 0, 0),
(27, 5, 'Sides', 'Chicken Salad ', 5.99, 0, 0),
(28, 5, 'Sides', 'Garden Salad', 3.99, 0, 0),
(29, 5, 'Sides', 'Caesar Salad', 3.99, 0, 0),
(9, 3, 'Dips', 'Tomato Dip', 0.7, 0, 0),
(10, 3, 'Dips', 'Hot Dip', 0.7, 0, 0),
(11, 3, 'Dips', 'Cheddar Dip', 0.7, 0, 0),
(12, 3, 'Dips', 'Marinara Dip', 0.7, 0, 0),
(13, 3, 'Dips', 'Ranch Dip', 0.7, 0, 0),
(14, 3, 'Dips', 'Blue Cheese Dip', 0.7, 0, 0),
(30, 6, 'Drinks', 'Diet Pepsi', 0.95, 0, 0),
(31, 6, 'Drinks', 'Pepsi', 0.95, 0, 0),
(32, 6, 'Drinks', 'Coca-Cola', 0.95, 0, 0),
(33, 6, 'Drinks', 'Diet Coca-Cola', 0.95, 0, 0),
(34, 6, 'Drinks', '7-up', 0.95, 0, 0),
(35, 6, 'Drinks', 'Crush Orange', 0.95, 0, 0),
(36, 6, 'Drinks', 'Dr. Pepper', 0.95, 0, 0),
(37, 6, 'Drinks', 'Ginger Ale', 0.95, 0, 0),
(38, 6, 'Drinks', 'Iced Tea', 0.95, 0, 0),
(39, 6, 'Drinks', 'Water Bottle', 0.95, 0, 0),
(40, 6, 'Drinks', '2L Coca-Cola', 2.99, 0, 0),
(41, 6, 'Drinks', '2L Diet Coca-Cola', 2.99, 0, 0),
(42, 6, 'Drinks', '2L Sprite', 2.99, 0, 0),
(43, 6, 'Drinks', '2L Iced Tea', 2.99, 0, 0);

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
  `email` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=65 ;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `placed_at`, `number`, `unit`, `buzzcode`, `street`, `postalcode`, `city`, `province`, `latitude`, `longitude`, `accepted_at`, `restaurant_id`, `type`, `payment_type`, `phone`, `cell`, `paid`, `stripeToken`, `deliverytime`, `cookingnotes`, `status`, `price`, `email`) VALUES
(44, 32, '2017-01-14 22:08:17', 90, 'Front Door', '', 'Bay Street North', 'L8R 3N3', 'Hamilton', 'Ontario', '43.2607047', '-79.872526100000', '0000-00-00 00:00:00', 1, 0, 0, '9055315331', '', 1, 'tok_9vmZpW2z7WP3vX', 'January 14 at 1645', '', 0, '36.80', NULL),
(45, 32, '2017-01-14 22:29:11', 90, 'Front Door', '', 'Bay Street North', 'L8R 3N3', 'Hamilton', 'Ontario', '43.2607047', '-79.872526100000', '0000-00-00 00:00:00', 1, 0, 0, '9055315331', '', 1, '', 'Deliver Now', '', 0, '16.48', NULL),
(46, 32, '2017-01-14 22:32:06', 90, 'Front Door', '', 'Bay Street North', 'L8R 3N3', 'Hamilton', 'Ontario', '43.2607047', '-79.872526100000', '0000-00-00 00:00:00', 1, 0, 0, '9055315331', '', 1, '', 'Deliver Now', '', 0, '22.58', NULL),
(47, 32, '2017-01-14 22:34:52', 90, 'Front Door', '', 'Bay Street North', 'L8R 3N3', 'Hamilton', 'Ontario', '43.2607047', '-79.872526100000', '0000-00-00 00:00:00', 1, 0, 0, '9055315331', '', 1, '', 'Deliver Now', '', 0, '5.58', NULL),
(48, 32, '2017-01-14 22:38:21', 90, 'Front Door', '', 'Bay Street North', 'L8R 3N3', 'Hamilton', 'Ontario', '43.2607047', '-79.872526100000', '0000-00-00 00:00:00', 1, 0, 0, '9055315331', '', 1, 'tok_9vn4PuqUwXhDHq', 'Deliver Now', '', 0, '5.58', NULL),
(49, 32, '2017-01-14 22:51:53', 90, 'Front Door', '', 'Bay Street North', 'L8R 3N3', 'Hamilton', 'Ontario', '43.2607047', '-79.872526100000', '0000-00-00 00:00:00', 1, 0, 0, '9055315331', '', 1, '', 'Deliver Now', '', 0, '49.45', NULL),
(50, 32, '2017-01-15 00:02:22', 90, 'Front Door', '', 'Bay Street North', 'L8R 3N3', 'Hamilton', 'Ontario', '43.2607047', '-79.872526100000', '0000-00-00 00:00:00', 1, 0, 0, '9055315332', '', 1, '', 'January 14 at 1745', '567', 0, '25.96', 'info@trinoweb.com'),
(51, 32, '2017-01-15 00:11:23', 90, 'Front Door', '', 'Bay Street North', 'L8R 3N3', 'Hamilton', 'Ontario', '43.2607047', '-79.872526100000', '0000-00-00 00:00:00', 1, 0, 0, '9055315332', '', 1, '', 'Deliver Now', '', 0, '5.30', 'info@trinoweb.com'),
(52, 32, '2017-01-15 00:18:08', 567, '567', '', 'Queen Street West', 'M5V 2B6', 'Toronto', 'Ontario', '43.6477016', '-79.400757199999', '0000-00-00 00:00:00', 1, 0, 0, '9055315332', '', 1, '', 'Deliver Now', '', 0, '27.66', 'info@trinoweb.com'),
(53, 32, '2017-01-15 00:22:28', 567, '567', '', 'Queen Street West', 'M5V 2B6', 'Toronto', 'Ontario', '43.6477016', '-79.400757199999', '0000-00-00 00:00:00', 1, 0, 0, '9055315332', '', 1, '', 'Deliver Now', '', 0, '10.03', 'info@trinoweb.com'),
(54, 32, '2017-01-15 00:25:31', 90, 'Front Door', '', 'Bay Street North', 'L8R 3N3', 'Hamilton', 'Ontario', '43.2607047', '-79.872526100000', '0000-00-00 00:00:00', 1, 0, 0, '9055315332', '', 1, '', 'Deliver Now', '', 0, '25.29', 'info@trinoweb.com'),
(55, 32, '2017-01-15 00:27:51', 567, '567', '', 'Queen Street West', 'M5V 2B6', 'Toronto', 'Ontario', '43.6477016', '-79.400757199999', '0000-00-00 00:00:00', 1, 0, 0, '9055315332', '', 1, '', 'Deliver Now', '', 0, '14.33', 'info@trinoweb.com'),
(56, 32, '2017-01-15 02:50:07', 90, 'Front Door', '', 'Bay Street North', 'L8R 3N3', 'Hamilton', 'Ontario', '43.2607047', '-79.872526100000', '0000-00-00 00:00:00', 1, 0, 0, '9055315332', '', 1, 'tok_9vr7pmTpoGifxh', 'January 14 at 2100', '', 0, '47.99', 'info@trinoweb.com'),
(57, 32, '2017-01-15 20:36:09', 567, '567', '', 'Queen Street West', 'M5V 2B6', 'Toronto', 'Ontario', '43.6477016', '-79.400757199999', '0000-00-00 00:00:00', 1, 0, 0, '9055315332', '', 1, '', 'Deliver Now', '', 0, '36.76', 'info@trinoweb.com'),
(58, 32, '2017-01-15 21:19:00', 567, '567', '', 'Queen Street West', 'M5V 2B6', 'Toronto', 'Ontario', '43.6477016', '-79.400757199999', '0000-00-00 00:00:00', 1, 0, 0, '9055315332', '', 1, '', 'Deliver Now', '', 0, '9.88', 'info@trinoweb.com'),
(59, 32, '2017-01-15 22:31:27', 567, '567', '', 'Queen Street West', 'M5V 2B6', 'Toronto', 'Ontario', '43.6477016', '-79.400757199999', '0000-00-00 00:00:00', 1, 0, 0, '9055315332', '', 1, 'tok_9wABGPiBVVXYyv', 'January 16 at 1500', '', 0, '8.80', 'info@trinoweb.com'),
(60, 32, '2017-01-15 22:46:40', 567, '567', '', 'Queen Street West', 'M5V 2B6', 'Toronto', 'Ontario', '43.6477016', '-79.400757199999', '0000-00-00 00:00:00', 1, 0, 0, '9055315332', '', 1, '', 'Deliver Now', '', 0, '22.40', 'info@trinoweb.com'),
(61, 32, '2017-01-15 22:47:55', 567, '567', '', 'Queen Street West', 'M5V 2B6', 'Toronto', 'Ontario', '43.6477016', '-79.400757199999', '0000-00-00 00:00:00', 1, 0, 0, '9055315332', '', 1, '', 'Deliver Now', '', 0, '19.19', 'info@trinoweb.com'),
(62, 32, '2017-01-15 22:49:11', 567, '567', '', 'Queen Street West', 'M5V 2B6', 'Toronto', 'Ontario', '43.6477016', '-79.400757199999', '0000-00-00 00:00:00', 1, 0, 0, '9055315332', '', 1, '', 'Deliver Now', '', 0, '7.89', 'info@trinoweb.com'),
(63, 32, '2017-01-15 22:51:10', 567, '567', '', 'Queen Street West', 'M5V 2B6', 'Toronto', 'Ontario', '43.6477016', '-79.400757199999', '0000-00-00 00:00:00', 1, 0, 0, '9055315332', '', 1, '', 'Deliver Now', '', 0, '22.95', 'info@trinoweb.com'),
(64, 32, '2017-01-15 23:48:26', 567, '567', '', 'Queen Street West', 'M5V 2B6', 'Toronto', 'Ontario', '43.6477016', '-79.400757199999', '0000-00-00 00:00:00', 1, 0, 0, '9055315332', '', 1, 'tok_9wBQSfQBol9w6h', 'Deliver Now', '', 0, '24.84', 'info@trinoweb.com');

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
(1, 'Fabulous 2 for 1 Pizza & Wings', '', '', '(905) 512-3067', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=119 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `keyname`, `value`) VALUES
(1, 'lastSQL', '1484666526'),
(20, 'orders', '1481122592'),
(24, 'menucache', '1484668245'),
(25, 'useraddresses', '1484430787'),
(37, 'users', '1479345588'),
(38, 'additional_toppings', '1479345609');

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
  `group` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=41 ;

--
-- Dumping data for table `toppings`
--

INSERT INTO `toppings` (`id`, `name`, `type`, `isfree`, `qualifiers`, `isall`, `group`) VALUES
(1, 'Anchovies', 'Meat', 0, '', 0, 0),
(2, 'Artichoke Heart', 'Vegetable', 0, '', 0, 0),
(3, 'Bacon', 'Meat', 0, '', 0, 0),
(4, 'Beef Salami', 'Meat', 0, '', 0, 0),
(5, 'Black Olives', 'Vegetable', 0, '', 0, 0),
(6, 'Broccoli', 'Vegetable', 0, '', 0, 0),
(9, 'Chicken', 'Meat', 0, '', 0, 0),
(13, 'Green Olives', 'Vegetable', 0, '', 0, 0),
(14, 'Green Peppers', 'Vegetable', 0, '', 0, 0),
(15, 'Ground Beef', 'Meat', 0, '', 0, 0),
(16, 'Ham', 'Meat', 0, '', 0, 0),
(17, 'Hot Banana Peppers', 'Vegetable', 0, '', 0, 0),
(18, 'Hot Italian Sausage', 'Meat', 0, '', 0, 0),
(19, 'Hot Peppers', 'Vegetable', 0, '', 0, 0),
(20, 'Hot Sausage', 'Meat', 0, '', 0, 0),
(21, 'Italian Sausage', 'Meat', 0, '', 0, 0),
(23, 'Jalapeno Peppers', 'Vegetable', 0, '', 0, 0),
(24, 'Mild Sausage', 'Meat', 0, '', 0, 0),
(27, 'Mushrooms', 'Vegetable', 0, '', 0, 0),
(28, 'Onions', 'Vegetable', 0, '', 0, 0),
(30, 'Pepperoni', 'Meat', 0, '', 0, 0),
(31, 'Pineapple', 'Vegetable', 0, '', 0, 0),
(32, 'Red Onions', 'Vegetable', 0, '', 0, 0),
(33, 'Red Peppers', 'Vegetable', 0, '', 0, 0),
(34, 'Salami', 'Meat', 0, '', 0, 0),
(35, 'Spinach', 'Vegetable', 0, '', 0, 0),
(36, 'Sundried Tomatoes', 'Vegetable', 0, '', 0, 0),
(37, 'Tomatoes', 'Vegetable', 0, '', 0, 0),
(38, 'Well Done', 'zPreparation', 1, 'Lightly done, Regular, Well done', 1, 1),
(40, 'Lightly Done', 'zPreparation', 1, '', 1, 1);

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
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=59 ;

--
-- Dumping data for table `useraddresses`
--

INSERT INTO `useraddresses` (`id`, `user_id`, `number`, `unit`, `buzzcode`, `street`, `postalcode`, `city`, `province`, `latitude`, `longitude`, `phone`) VALUES
(1, 21, 483, '', '', 'Dundas Street', 'N6B 1W4', 'London', 'Ontario', '42.9871816', '-81.2386115', ''),
(58, 32, 567, '567', '', 'Queen Street West', 'M5V 2B6', 'Toronto', 'Ontario', '43.6477016', '-79.400757199999', '');

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
  `stripecustid` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=33 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`, `phone`, `lastlogin`, `loginattempts`, `profiletype`, `authcode`, `stripecustid`) VALUES
(1, 'Roy Wall', 'roy@trinoweb.com', '$2y$10$440weczzi7gl8OpXQJROPey1Eiyx1BQWk4dFEj9pAHWO2FmagZQ52', '', '0000-00-00 00:00:00', '2017-01-03 23:29:36', '905 531 5331', 1479912217, 6, 1, '', 'cus_9rgc8wsT5ZzV05'),
(20, 'Roy Test', 'roy+test@trinoweb.com', '$2y$10$XqUn.RNhx0YbcZUQXWYP0eHIz0aLK8xX00cd.PLVRQsafF9Frod6K', '', '2016-11-16 20:20:28', '0000-00-00 00:00:00', '9055315331', 0, 0, 0, '', ''),
(21, 'Jonas Morse', 'roy+rest@trinoweb.com', '$2y$10$XqUn.RNhx0YbcZUQXWYP0eHIz0aLK8xX00cd.PLVRQsafF9Frod6K', '', '2016-11-16 20:49:31', '0000-00-00 00:00:00', '9055315331', 1481048458, 1, 2, '', ''),
(22, 'Van Trinh', 'info+logintest@trinoweb.com', '$2y$10$XqUn.RNhx0YbcZUQXWYP0eHIz0aLK8xX00cd.PLVRQsafF9Frod6K', '', '2016-11-23 20:18:07', '0000-00-00 00:00:00', '9055315331', 0, 0, 0, '', ''),
(28, 'Van Trinh', 'roy+testing@trinoweb.com', '$2y$10$CorqChmOPGd9R4Ht7zaR4.p5fQOVQMyZW7WytqPYodvuxGHewf7lK', '', '2017-01-03 23:28:44', '0000-00-00 00:00:00', '', 0, 0, 0, '', ''),
(32, 'Van Trinh123', 'info@trinoweb.com', '$2y$10$/BnVGwDyTms/wiSNHaUfI.nH.VMN83zcW1H8f3XcTbxWhLXz53nIC', '', '2017-01-14 22:07:38', '2017-01-15 23:30:42', '9055315332', 1484504983, 1, 0, '', 'cus_9vmaEp1T3ncHmj');

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
  `group` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `wings_sauce`
--

INSERT INTO `wings_sauce` (`id`, `name`, `type`, `isfree`, `qualifiers`, `isall`, `group`) VALUES
(1, 'Mild', 'Sauce', 0, '', 1, 2),
(2, 'Medium', 'Sauce', 0, '', 1, 2),
(3, 'Hot', 'Sauce', 0, '', 1, 2),
(4, 'Suicide', 'Sauce', 0, '', 1, 2),
(5, 'BBQ', 'Sauce', 0, '', 1, 2),
(6, 'Honey Garlic', 'Sauce', 0, '', 1, 2),
(7, 'Well Done', 'Preparation', 1, 'Lightly done, Regular, Well done', 1, 1),
(8, 'Lightly Done', 'Preparation', 1, '', 1, 1),
(9, 'Sauce on the side', 'Preparation', 1, '', 1, 3);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
