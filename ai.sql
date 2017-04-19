-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 19, 2017 at 10:16 AM
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
-- Table structure for table `actions`
--

CREATE TABLE IF NOT EXISTS `actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eventname` varchar(64) NOT NULL,
  `party` tinyint(4) NOT NULL COMMENT '0=user,1=admin,2=restaurant',
  `sms` tinyint(1) NOT NULL,
  `phone` tinyint(1) NOT NULL,
  `email` tinyint(1) NOT NULL,
  `message` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `actions`
--

INSERT INTO `actions` (`id`, `eventname`, `party`, `sms`, `phone`, `email`, `message`) VALUES
(1, 'order_placed', 2, 0, 1, 1, 'londonpizza.ca - A new order was placed'),
(2, 'order_placed', 1, 1, 0, 0, 'londonpizza.ca - A new order was placed'),
(3, 'order_placed', 0, 0, 0, 1, 'londonpizza.ca - Here is your receipt'),
(4, 'order_declined', 0, 1, 0, 1, 'londonpizza.ca - Your order was cancelled: [reason]'),
(5, 'order_declined', 1, 1, 0, 0, 'londonpizza.ca - An order was cancelled: [reason]'),
(6, 'order_confirmed', 1, 1, 0, 0, 'londonpizza.ca - An order was approved: [reason]'),
(7, 'user_registered', 0, 0, 0, 1, 'londonpizza.ca - Thank you for registering'),
(8, 'order_placed', 2, 1, 0, 0, 'londonpizza.ca - A new order was placed. Click [url] to see it');

-- --------------------------------------------------------

--
-- Table structure for table `additional_toppings`
--

CREATE TABLE IF NOT EXISTS `additional_toppings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `size` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

--
-- Dumping data for table `additional_toppings`
--

INSERT INTO `additional_toppings` (`id`, `size`, `price`) VALUES
(1, 'Small', 0.95),
(2, 'Medium', 1.2),
(3, 'Large', 1.5),
(4, 'X-Large', 1.7),
(6, 'Panzerotti', 0.95),
(7, 'Delivery', 2.99),
(8, 'Minimum', 14.99),
(10, 'DeliveryTime', 40);

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
  `wings_sauce` tinyint(4) NOT NULL,
  `calories` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'for 2 items, separate with a /. For more, use a -',
  `allergens` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `category_id`, `category`, `item`, `price`, `toppings`, `wings_sauce`, `calories`, `allergens`) VALUES
(1, 1, 'Pizza', 'Small Pizza', 4.95, 1, 0, '', ''),
(2, 1, 'Pizza', 'Medium Pizza', 5.75, 1, 0, '', ''),
(3, 1, 'Pizza', 'Large Pizza', 6.95, 1, 0, '', ''),
(4, 1, 'Pizza', 'X-Large Pizza', 9.95, 1, 0, '', ''),
(5, 1, 'Pizza', '2 Small Pizzas', 9.95, 2, 0, '', ''),
(6, 1, 'Pizza', '2 Medium Pizzas', 15.95, 2, 0, '', ''),
(7, 1, 'Pizza', '2 Large Pizzas', 17.95, 2, 0, '', ''),
(8, 1, 'Pizza', '2 X-Large Pizzas', 19.95, 2, 0, '', ''),
(9, 3, 'Dips', 'Tomato Dip', 0.7, 0, 0, '', ''),
(10, 3, 'Dips', 'Hot Dip', 0.7, 0, 0, '', ''),
(11, 3, 'Dips', 'Cheddar Dip', 0.7, 0, 0, '', ''),
(12, 3, 'Dips', 'Marinara Dip', 0.7, 0, 0, '', ''),
(13, 3, 'Dips', 'Ranch Dip', 0.7, 0, 0, '', ''),
(14, 3, 'Dips', 'Blue Cheese Dip', 0.7, 0, 0, '', ''),
(15, 4, 'Wings', '1 lb Wings', 6.99, 0, 1, '', ''),
(16, 4, 'Wings', '2 lb Wings', 12.99, 0, 2, '', ''),
(17, 4, 'Wings', '3 lb Wings', 17.99, 0, 3, '', ''),
(18, 4, 'Wings', '4 lb Wings', 24.99, 0, 4, '', ''),
(19, 4, 'Wings', '5 lb Wings', 28.99, 0, 5, '', ''),
(20, 5, 'Sides', 'Panzerotti', 5.99, 1, 0, '', ''),
(21, 5, 'Sides', 'Garlic Bread', 2.25, 0, 0, '', ''),
(22, 5, 'Sides', 'French Fries', 3.99, 0, 0, '', ''),
(23, 5, 'Sides', 'Potato Wedges', 3.99, 0, 0, '', ''),
(27, 5, 'Sides', 'Chicken Salad ', 5.99, 0, 0, '', ''),
(28, 5, 'Sides', 'Caesar Salad', 3.99, 0, 0, '', ''),
(29, 5, 'Sides', 'Garden Salad', 3.99, 0, 0, '', ''),
(32, 6, 'Drinks', 'Coca-Cola', 0.95, 0, 0, '139', 'sugar=40g'),
(33, 6, 'Drinks', 'Diet Coca-Cola', 0.95, 0, 0, '0', ''),
(34, 6, 'Drinks', 'Pepsi', 0.95, 0, 0, '150', ''),
(35, 6, 'Drinks', 'Diet Pepsi', 0.95, 0, 0, '', ''),
(36, 6, 'Drinks', 'Sprite', 0.95, 0, 0, '', ''),
(37, 6, 'Drinks', 'Crush Orange', 0.95, 0, 0, '', ''),
(38, 6, 'Drinks', 'Dr. Pepper', 0.95, 0, 0, '', ''),
(39, 6, 'Drinks', 'Ginger Ale', 0.95, 0, 0, '', ''),
(40, 6, 'Drinks', 'Nestea', 0.95, 0, 0, '', ''),
(41, 6, 'Drinks', 'Water Bottle', 0.95, 0, 0, '', ''),
(45, 6, 'Drinks', '2L Coca-Cola', 2.99, 0, 0, '', ''),
(46, 6, 'Drinks', '2L Sprite', 2.99, 0, 0, '', ''),
(47, 6, 'Drinks', '2L Brisk Iced Tea', 2.99, 0, 0, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `placed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `number` int(11) NOT NULL,
  `unit` varchar(16) NOT NULL,
  `buzzcode` varchar(32) NOT NULL,
  `street` varchar(255) NOT NULL,
  `postalcode` varchar(16) NOT NULL,
  `city` varchar(64) NOT NULL,
  `province` varchar(32) NOT NULL,
  `latitude` varchar(16) NOT NULL,
  `longitude` varchar(16) NOT NULL,
  `accepted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=226 ;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `placed_at`, `number`, `unit`, `buzzcode`, `street`, `postalcode`, `city`, `province`, `latitude`, `longitude`, `accepted_at`, `restaurant_id`, `type`, `payment_type`, `phone`, `cell`, `paid`, `stripeToken`, `deliverytime`, `cookingnotes`, `status`, `price`, `email`) VALUES
(168, 48, '2017-04-01 17:18:55', 300, '', '', 'Dundas St', 'N6B 1T6', 'London', 'Ontario', '42.9854177', '-81.244139099999', '0000-00-00 00:00:00', 1, 0, 0, '9055315331', '', 1, 'tok_AOd86hf9WWmtzU', 'Deliver Now', '', 0, '25.92', NULL),
(169, 48, '2017-04-01 17:43:32', 300, '', '', 'Dundas St', 'N6B 1T6', 'London', 'Ontario', '42.9854177', '-81.244139099999', '0000-00-00 00:00:00', 1, 0, 0, '9055315331', '', 1, 'tok_AOdXw7z9EnRWLR', 'April 1 at 1515', '', 0, '33.89', NULL),
(170, 49, '2017-04-01 17:52:09', 300, '', '', 'Dundas St', 'N6B 1T6', 'London', 'Ontario', '42.9854177', '-81.244139099999', '0000-00-00 00:00:00', 1, 0, 0, '9055315331', '', 1, 'tok_AOdffgjpJQYBCV', 'Deliver Now', '', 0, '64.50', NULL),
(171, 49, '2017-04-01 17:56:46', 300, '', '', 'Dundas St', 'N6B 1T6', 'London', 'Ontario', '42.9854177', '-81.244139099999', '0000-00-00 00:00:00', 1, 0, 0, '9055315331', '', 1, '', 'Deliver Now', '', 0, '38.58', NULL),
(172, 49, '2017-04-01 17:59:46', 300, '', '', 'Dundas St', 'N6B 1T6', 'London', 'Ontario', '42.9854177', '-81.244139099999', '0000-00-00 00:00:00', 1, 0, 0, '9055315331', '', 1, '', 'Deliver Now', '', 0, '32.82', NULL),
(173, 49, '2017-04-01 18:00:44', 300, '', '', 'Dundas St', 'N6B 1T6', 'London', 'Ontario', '42.9854177', '-81.244139099999', '0000-00-00 00:00:00', 1, 0, 0, '9055315331', '', 1, '', 'Deliver Now', '', 0, '27.05', NULL),
(174, 49, '2017-04-01 18:02:27', 300, '', '', 'Dundas St', 'N6B 1T6', 'London', 'Ontario', '42.9854177', '-81.244139099999', '0000-00-00 00:00:00', 1, 0, 0, '9055315331', '', 1, '', 'Deliver Now', '', 0, '27.05', NULL),
(175, 48, '2017-04-01 18:04:29', 300, '', '', 'Dundas St', 'N6B 1T6', 'London', 'Ontario', '42.9854177', '-81.244139099999', '0000-00-00 00:00:00', 1, 0, 0, '9055315331', '', 1, '', 'Deliver Now', '', 0, '27.05', NULL),
(176, 48, '2017-04-01 18:09:30', 300, '', '', 'Dundas St', 'N6B 1T6', 'London', 'Ontario', '42.9854177', '-81.244139099999', '0000-00-00 00:00:00', 1, 0, 0, '9055315331', '', 1, '', 'Deliver Now', '', 0, '24.79', NULL),
(177, 48, '2017-04-01 18:10:13', 300, '', '', 'Dundas St', 'N6B 1T6', 'London', 'Ontario', '42.9854177', '-81.244139099999', '0000-00-00 00:00:00', 1, 0, 0, '9055315331', '', 1, '', 'Deliver Now', '', 0, '27.05', NULL),
(178, 48, '2017-04-01 18:12:29', 300, '', '', 'Dundas St', 'N6B 1T6', 'London', 'Ontario', '42.9854177', '-81.244139099999', '0000-00-00 00:00:00', 1, 0, 0, '9055315331', '', 1, '', 'Deliver Now', '', 0, '27.05', NULL),
(179, 48, '2017-04-01 18:13:42', 300, '', '', 'Dundas St', 'N6B 1T6', 'London', 'Ontario', '42.9854177', '-81.244139099999', '0000-00-00 00:00:00', 1, 0, 0, '9055315331', '', 1, '', 'Deliver Now', '', 0, '27.05', NULL),
(180, 49, '2017-04-01 18:30:43', 18, '', '', 'Oakland Dr', 'L8E 3Z2', 'Hamilton', 'Ontario', '43.2304400000000', '-79.7693198', '0000-00-00 00:00:00', 3, 0, 0, '9055555555', '', 1, '', 'Deliver Now', 'Please write a note in the pizza box', 0, '27.05', NULL),
(181, 49, '2017-04-01 18:33:16', 18, '', '', 'Oakland Dr', 'L8E 3Z2', 'Hamilton', 'Ontario', '43.2304400000000', '-79.7693198', '0000-00-00 00:00:00', 3, 0, 0, '9055555555', '', 1, '', 'Deliver Now', '', 0, '27.05', NULL),
(182, 49, '2017-04-01 18:33:41', 300, '', '', 'Dundas St', 'N6B 1T6', 'London', 'Ontario', '42.9854177', '-81.244139099999', '0000-00-00 00:00:00', 1, 0, 0, '9055555555', '', 1, '', 'Deliver Now', '', 0, '30.89', NULL),
(183, 49, '2017-04-01 18:34:51', 18, '', '', 'Oakland Dr', 'L8E 3Z2', 'Hamilton', 'Ontario', '43.2304400000000', '-79.7693198', '0000-00-00 00:00:00', 3, 0, 0, '9055555555', '', 1, '', 'Deliver Now', '', 0, '27.05', NULL),
(184, 49, '2017-04-01 18:36:21', 300, '', '', 'Dundas St', 'N6B 1T6', 'London', 'Ontario', '42.9854177', '-81.244139099999', '0000-00-00 00:00:00', 1, 0, 0, '9055555555', '', 1, '', 'Deliver Now', '', 0, '27.05', NULL),
(185, 49, '2017-04-01 18:37:44', 300, '', '', 'Dundas St', 'N6B 1T6', 'London', 'Ontario', '42.9854177', '-81.244139099999', '0000-00-00 00:00:00', 1, 0, 0, '9055555555', '', 1, '', 'Deliver Now', '', 0, '27.05', NULL),
(186, 49, '2017-04-01 18:39:24', 18, '', '', 'Oakland Dr', 'L8E 3Z2', 'Hamilton', 'Ontario', '43.2304400000000', '-79.7693198', '0000-00-00 00:00:00', 3, 0, 0, '9055555555', '', 1, '', 'Deliver Now', '', 0, '32.82', NULL),
(187, 49, '2017-04-01 19:00:19', 300, '', '', 'Dundas St', 'N6B 1T6', 'London', 'Ontario', '42.9854177', '-81.244139099999', '0000-00-00 00:00:00', 1, 0, 0, '9055555555', '', 1, '', 'Deliver Now', '', 0, '38.58', NULL),
(188, 49, '2017-04-01 19:01:16', 18, '', '', 'Oakland Dr', 'L8E 3Z2', 'Hamilton', 'Ontario', '43.2304400000000', '-79.7693198', '0000-00-00 00:00:00', 3, 0, 0, '9055555555', '', 1, '', 'Deliver Now', '', 0, '27.05', NULL),
(189, 49, '2017-04-01 19:03:41', 300, '', '', 'Dundas St', 'N6B 1T6', 'London', 'Ontario', '42.9854177', '-81.244139099999', '0000-00-00 00:00:00', 1, 0, 0, '9055555555', '', 1, '', 'Deliver Now', '', 0, '32.82', NULL),
(190, 49, '2017-04-01 19:04:53', 300, '', '', 'Dundas St', 'N6B 1T6', 'London', 'Ontario', '42.9854177', '-81.244139099999', '0000-00-00 00:00:00', 1, 0, 0, '9055555555', '', 1, '', 'Deliver Now', '', 0, '38.58', NULL),
(191, 49, '2017-04-01 19:10:42', 300, '', '', 'Dundas St', 'N6B 1T6', 'London', 'Ontario', '42.9854177', '-81.244139099999', '0000-00-00 00:00:00', 1, 0, 0, '9055555555', '', 1, 'tok_AOewV17HgWzVTv', 'Deliver Now', '', 0, '27.05', NULL),
(192, 49, '2017-04-01 19:11:23', 18, '', '', 'Oakland Dr', 'L8E 3Z2', 'Hamilton', 'Ontario', '43.2304400000000', '-79.7693198', '0000-00-00 00:00:00', 3, 0, 0, '9055555555', '', 1, '', 'Deliver Now', '', 0, '27.05', NULL),
(193, 49, '2017-04-01 19:14:59', 300, '', '', 'Dundas St', 'N6B 1T6', 'London', 'Ontario', '42.9854177', '-81.244139099999', '0000-00-00 00:00:00', 1, 0, 0, '9055555555', '', 1, '', 'Deliver Now', '', 0, '30.89', NULL),
(194, 49, '2017-04-01 19:16:34', 18, '', '', 'Oakland Dr', 'L8E 3Z2', 'Hamilton', 'Ontario', '43.2304400000000', '-79.7693198', '0000-00-00 00:00:00', 3, 0, 0, '9055555555', '', 1, '', 'Deliver Now', '', 0, '30.43', NULL),
(195, 49, '2017-04-01 19:18:33', 300, '', '', 'Dundas St', 'N6B 1T6', 'London', 'Ontario', '42.9854177', '-81.244139099999', '0000-00-00 00:00:00', 1, 0, 0, '9055555555', '', 1, '', 'Deliver Now', '', 0, '55.36', NULL),
(196, 49, '2017-04-01 19:19:19', 300, '', '', 'Dundas St', 'N6B 1T6', 'London', 'Ontario', '42.9854177', '-81.244139099999', '0000-00-00 00:00:00', 1, 0, 0, '9055555555', '', 1, '', 'Deliver Now', '', 0, '27.05', NULL),
(197, 49, '2017-04-01 19:28:09', 300, '', '', 'Dundas St', 'N6B 1T6', 'London', 'Ontario', '42.9854177', '-81.244139099999', '0000-00-00 00:00:00', 1, 0, 0, '9055555555', '', 1, '', 'Deliver Now', '', 0, '58.18', NULL),
(198, 49, '2017-04-01 19:28:35', 18, '', '', 'Oakland Dr', 'L8E 3Z2', 'Hamilton', 'Ontario', '43.2304400000000', '-79.7693198', '0000-00-00 00:00:00', 3, 0, 0, '9055555555', '', 1, '', 'Deliver Now', '', 0, '37.27', NULL),
(199, 49, '2017-04-01 19:32:18', 300, '', '', 'Dundas St', 'N6B 1T6', 'London', 'Ontario', '42.9854177', '-81.244139099999', '0000-00-00 00:00:00', 1, 0, 0, '9055555555', '', 1, '', 'Deliver Now', '', 0, '30.89', NULL),
(200, 49, '2017-04-01 19:32:53', 18, '', '', 'Oakland Dr', 'L8E 3Z2', 'Hamilton', 'Ontario', '43.2304400000000', '-79.7693198', '0000-00-00 00:00:00', 3, 0, 0, '9055555555', '', 1, '', 'Deliver Now', '', 0, '32.82', NULL),
(201, 49, '2017-04-01 19:34:01', 300, '', '', 'Dundas St', 'N6B 1T6', 'London', 'Ontario', '42.9854177', '-81.244139099999', '0000-00-00 00:00:00', 1, 0, 0, '9055555555', '', 1, '', 'Deliver Now', '', 0, '27.05', NULL),
(202, 49, '2017-04-01 19:34:30', 300, '', '', 'Dundas St', 'N6B 1T6', 'London', 'Ontario', '42.9854177', '-81.244139099999', '0000-00-00 00:00:00', 1, 0, 0, '9055555555', '', 1, '', 'Deliver Now', '', 0, '27.05', NULL),
(203, 49, '2017-04-01 19:37:29', 300, '', '', 'Dundas St', 'N6B 1T6', 'London', 'Ontario', '42.9854177', '-81.244139099999', '0000-00-00 00:00:00', 1, 0, 0, '9055555555', '', 1, '', 'Deliver Now', '', 0, '32.82', NULL),
(204, 49, '2017-04-01 19:38:41', 18, '', '', 'Oakland Dr', 'L8E 3Z2', 'Hamilton', 'Ontario', '43.2304400000000', '-79.7693198', '0000-00-00 00:00:00', 3, 0, 0, '9055555555', '', 1, '', 'Deliver Now', '', 0, '27.05', NULL),
(205, 49, '2017-04-01 19:39:34', 18, '', '', 'Oakland Dr', 'L8E 3Z2', 'Hamilton', 'Ontario', '43.2304400000000', '-79.7693198', '0000-00-00 00:00:00', 3, 0, 0, '9055555555', '', 1, '', 'Deliver Now', '', 0, '32.82', NULL),
(206, 49, '2017-04-01 19:41:06', 18, '', '', 'Oakland Dr', 'L8E 3Z2', 'Hamilton', 'Ontario', '43.2304400000000', '-79.7693198', '0000-00-00 00:00:00', 3, 0, 0, '9055555555', '', 1, '', 'Deliver Now', '', 0, '32.82', NULL),
(207, 49, '2017-04-01 19:42:38', 18, '', '', 'Oakland Dr', 'L8E 3Z2', 'Hamilton', 'Ontario', '43.2304400000000', '-79.7693198', '0000-00-00 00:00:00', 3, 0, 0, '9055555555', '', 1, '', 'Deliver Now', '', 0, '37.34', NULL),
(208, 49, '2017-04-01 19:46:08', 18, '', '', 'Oakland Dr', 'L8E 3Z2', 'Hamilton', 'Ontario', '43.2304400000000', '-79.7693198', '0000-00-00 00:00:00', 3, 0, 0, '9055555555', '', 1, '', 'Deliver Now', '', 0, '27.05', NULL),
(209, 49, '2017-04-01 19:58:59', 300, '', '', 'Dundas St', 'N6B 1T6', 'London', 'Ontario', '42.9854177', '-81.244139099999', '0000-00-00 00:00:00', 1, 0, 0, '9055555555', '', 1, '', 'Deliver Now', '', 0, '98.81', NULL),
(210, 49, '2017-04-01 19:59:26', 18, '', '', 'Oakland Dr', 'L8E 3Z2', 'Hamilton', 'Ontario', '43.2304400000000', '-79.7693198', '0000-00-00 00:00:00', 3, 0, 0, '9055555555', '', 1, '', 'Deliver Now', '', 0, '27.05', NULL),
(211, 49, '2017-04-01 20:00:20', 18, '', '', 'Oakland Dr', 'L8E 3Z2', 'Hamilton', 'Ontario', '43.2304400000000', '-79.7693198', '0000-00-00 00:00:00', 3, 0, 0, '9055555555', '', 1, '', 'Deliver Now', '', 0, '32.82', NULL),
(212, 49, '2017-04-01 20:01:09', 300, '', '', 'Dundas St', 'N6B 1T6', 'London', 'Ontario', '42.9854177', '-81.244139099999', '0000-00-00 00:00:00', 1, 0, 0, '9055555555', '', 1, '', 'Deliver Now', '', 0, '27.05', NULL),
(213, 49, '2017-04-01 20:01:38', 18, '', '', 'Oakland Dr', 'L8E 3Z2', 'Hamilton', 'Ontario', '43.2304400000000', '-79.7693198', '0000-00-00 00:00:00', 3, 0, 0, '9055555555', '', 1, '', 'Deliver Now', '', 0, '34.74', NULL),
(214, 49, '2017-04-01 20:02:15', 18, '', '', 'Oakland Dr', 'L8E 3Z2', 'Hamilton', 'Ontario', '43.2304400000000', '-79.7693198', '0000-00-00 00:00:00', 3, 0, 0, '9055555555', '', 1, '', 'Deliver Now', '', 0, '27.05', NULL),
(215, 49, '2017-04-01 20:03:09', 300, '', '', 'Dundas St', 'N6B 1T6', 'London', 'Ontario', '42.9854177', '-81.244139099999', '0000-00-00 00:00:00', 1, 0, 0, '9055555555', '', 1, '', 'Deliver Now', '', 0, '32.82', NULL),
(216, 49, '2017-04-01 20:06:57', 18, '', '', 'Oakland Dr', 'L8E 3Z2', 'Hamilton', 'Ontario', '43.2304400000000', '-79.7693198', '0000-00-00 00:00:00', 3, 0, 0, '9055555555', '', 1, '', 'Deliver Now', '', 0, '54.94', NULL),
(217, 49, '2017-04-01 21:49:08', 300, '', '', 'Dundas St', 'N6B 1T6', 'London', 'Ontario', '42.9854177', '-81.244139099999', '0000-00-00 00:00:00', 1, 0, 0, '9055555555', '', 1, '', 'Deliver Now', '', 0, '25.38', NULL),
(218, 49, '2017-04-01 21:50:30', 18, '', '', 'Oakland Dr', 'L8E 3Z2', 'Hamilton', 'Ontario', '43.2304400000000', '-79.7693198', '0000-00-00 00:00:00', 3, 0, 0, '9055555555', '', 1, '', 'Deliver Now', '', 0, '27.05', NULL),
(219, 51, '2017-04-01 23:34:53', 18, '', '', 'Oakland Dr', 'L8E 3Z2', 'Hamilton', 'Ontario', '43.2304400000000', '-79.7693198', '0000-00-00 00:00:00', 3, 0, 0, '2897006367', '', 1, 'tok_AOjC48wnqrVkYk', 'Deliver Now', '', 0, '24.44', NULL),
(220, 52, '2017-04-02 01:20:20', 18, 'I''m fat', '', 'Oakland Dr', 'L8E 3Z2', 'Hamilton', 'Ontario', '43.2304400000000', '-79.7693198', '0000-00-00 00:00:00', 3, 0, 0, '9055315333', '', 1, 'tok_AOktyVNv6phsmq', 'April 2 at 0030', '', 0, '46.70', NULL),
(221, 49, '2017-04-02 14:47:29', 300, '', '', 'Dundas St', 'N6B 1T6', 'London', 'Ontario', '42.9854177', '-81.244139099999', '0000-00-00 00:00:00', 1, 0, 0, '', '', 1, '', 'April 3 at 1130', '', 0, '38.30', NULL),
(222, 49, '2017-04-02 18:02:19', 300, '', '', 'Dundas St', 'N6B 1T6', 'London', 'Ontario', '42.9854177', '-81.244139099999', '0000-00-00 00:00:00', 1, 0, 0, '', '', 1, '', 'April 3 at 1100', '', 0, '47.38', NULL),
(223, 49, '2017-04-02 23:27:56', 300, '', '', 'Dundas St', 'N6B 1T6', 'London', 'Ontario', '42.9854177', '-81.244139099999', '0000-00-00 00:00:00', 1, 0, 0, '', '', 1, '', 'April 3 at 1100', '', 0, '25.92', NULL),
(224, 1, '2017-04-04 14:57:20', 2396, '18 Oakland Dr', '', 'Asima Dr', 'N6M 0B3', 'London', 'Ontario', '42.9505', '-81.1735999', '0000-00-00 00:00:00', 1, 0, 0, '9055315331', '', 1, '', 'April 4 at 1145', '', 0, '15.19', NULL),
(225, 1, '2017-04-19 13:54:25', 2396, '18 Oakland Dr', '', 'Asima Dr', 'N6M 0B3', 'London', 'Ontario', '42.9505', '-81.1735999', '0000-00-00 00:00:00', 1, 0, 0, '', '', 1, 'tok_AVJuV6A1UFUxid', 'April 19 at 1100', '', 0, '66.83', NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `restaurants`
--

INSERT INTO `restaurants` (`id`, `name`, `slug`, `email`, `phone`, `cuisine`, `website`, `description`, `logo`, `is_delivery`, `is_pickup`, `max_delivery_distance`, `delivery_fee`, `minimum`, `is_complete`, `lastorder_id`, `franchise`, `address_id`) VALUES
(1, 'Fabulous 2 for 1 Pizza', '', '(905) 512-3067', '9055315331', '', '', '', '', 1, 0, 0, 0, 0, 0, 0, 0, 1),
(2, 'INACTIVE Marvellous Pizza', '', '(519) 452-1044', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 2),
(3, 'Quality Pizza & Wings', '', '', '9055555555', '', '', '', '', 1, 0, 0, 0, 0, 0, 0, 0, 97);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1258 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `keyname`, `value`) VALUES
(1, 'lastSQL', '1492019447'),
(20, 'orders', '1487775876'),
(24, 'menucache', '1492611185'),
(25, 'useraddresses', '1491932853'),
(37, 'users', '1487175217'),
(38, 'additional_toppings', '1487175322'),
(43, 'actions', '1492527405'),
(87, 'restaurants', '1489588141');

-- --------------------------------------------------------

--
-- Table structure for table `toppings`
--

CREATE TABLE IF NOT EXISTS `toppings` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `isfree` tinyint(1) NOT NULL,
  `qualifiers` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'comma delimited list of the names for 1/2,x1,x2 if applicable',
  `isall` tinyint(4) NOT NULL DEFAULT '0',
  `groupid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `toppings`
--

INSERT INTO `toppings` (`id`, `name`, `type`, `isfree`, `qualifiers`, `isall`, `groupid`) VALUES
(1, 'Anchovies', 'Meat', 0, '', 0, 0),
(2, 'Bacon', 'Meat', 0, '', 0, 0),
(3, 'Beef Salami', 'Meat', 0, '', 0, 0),
(4, 'Chicken', 'Meat', 0, '', 0, 0),
(5, 'Ground Beef', 'Meat', 0, '', 0, 0),
(6, 'Ham', 'Meat', 0, '', 0, 0),
(7, 'Hot Italian Sausage', 'Meat', 0, '', 0, 0),
(8, 'Hot Sausage', 'Meat', 0, '', 0, 0),
(9, 'Italian Sausage', 'Meat', 0, '', 0, 0),
(10, 'Mild Sausage', 'Meat', 0, '', 0, 0),
(11, 'Pepperoni', 'Meat', 0, '', 0, 0),
(12, 'Salami', 'Meat', 0, '', 0, 0),
(13, 'Artichoke Heart', 'Vegetable', 0, '', 0, 0),
(14, 'Black Olives', 'Vegetable', 0, '', 0, 0),
(15, 'Broccoli', 'Vegetable', 0, '', 0, 0),
(16, 'Green Olives', 'Vegetable', 0, '', 0, 0),
(17, 'Green Peppers', 'Vegetable', 0, '', 0, 0),
(18, 'Hot Banana Peppers', 'Vegetable', 0, '', 0, 0),
(19, 'Hot Peppers', 'Vegetable', 0, '', 0, 0),
(20, 'Jalapeno Peppers', 'Vegetable', 0, '', 0, 0),
(21, 'Mushrooms', 'Vegetable', 0, '', 0, 0),
(22, 'Onions', 'Vegetable', 0, '', 0, 0),
(23, 'Pineapple', 'Vegetable', 0, '', 0, 0),
(24, 'Red Onions', 'Vegetable', 0, '', 0, 0),
(25, 'Red Peppers', 'Vegetable', 0, '', 0, 0),
(26, 'Spinach', 'Vegetable', 0, '', 0, 0),
(27, 'Sundried Tomatoes', 'Vegetable', 0, '', 0, 0),
(28, 'Tomatoes', 'Vegetable', 0, '', 0, 0),
(29, 'Extra Cheese', 'Vegetable', 0, '', 0, 0),
(30, 'Lightly Done', 'zPreparation', 1, '', 1, 1),
(31, 'Well Done', 'zPreparation', 1, '', 1, 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=102 ;

--
-- Dumping data for table `useraddresses`
--

INSERT INTO `useraddresses` (`id`, `user_id`, `number`, `unit`, `buzzcode`, `street`, `postalcode`, `city`, `province`, `latitude`, `longitude`, `phone`) VALUES
(1, 3, 483, '', '', 'Dundas Street', 'N6B 1W4', 'London', 'Ontario', '42.9871816', '-81.2386115', ''),
(2, 4, 1565, '', '', 'Western Rd', 'N6G 1H5', 'London', 'Ontario', '43.0187', '-81.2812887', ''),
(3, 5, 483, '', '', 'Dundas Street', 'N6B 1W4', 'London', 'Ontario', '42.9871816', '-81.2386115', ''),
(4, 6, 1569, '', '', 'Oxford Street East', 'N5V 1W5', 'London', 'Ontario', '43.0109195', '-81.198983600000', ''),
(95, 48, 300, '', '', 'Dundas St', 'N6B 1T6', 'London', 'Ontario', '42.9854177', '-81.244139099999', ''),
(96, 49, 300, '', '', 'Dundas St', 'N6B 1T6', 'London', 'Ontario', '42.9854177', '-81.244139099999', ''),
(97, 50, 2372, '', '', 'Barton St E', 'L8E 2W7', 'Hamilton', 'Ontario', '43.2376467', '-79.765102399999', ''),
(98, 49, 18, '', '', 'Oakland Dr', 'L8E 3Z2', 'Hamilton', 'Ontario', '43.2304400000000', '-79.7693198', ''),
(99, 51, 18, '', '', 'Oakland Dr', 'L8E 3Z2', 'Hamilton', 'Ontario', '43.2304400000000', '-79.7693198', ''),
(100, 52, 18, 'I''m fat', '', 'Oakland Dr', 'L8E 3Z2', 'Hamilton', 'Ontario', '43.2304400000000', '-79.7693198', ''),
(101, 1, 2396, '18 Oakland Dr', '', 'Asima Dr', 'N6M 0B3', 'London', 'Ontario', '42.9505', '-81.1735999', '');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=53 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`, `phone`, `lastlogin`, `loginattempts`, `profiletype`, `authcode`, `stripecustid`) VALUES
(1, 'Roy Wall', 'roy@trinoweb.com', '$2y$10$440weczzi7gl8OpXQJROPey1Eiyx1BQWk4dFEj9pAHWO2FmagZQ52', '', '0000-00-00 00:00:00', '2017-02-01 15:15:40', '9055315331', 1487608084, 0, 1, '', 'cus_AVJuSlrIJIF8hc'),
(2, 'Roy Test', 'roy+test@trinoweb.com', '$2y$10$440weczzi7gl8OpXQJROPey1Eiyx1BQWk4dFEj9pAHWO2FmagZQ52', '', '2016-11-16 20:20:28', '0000-00-00 00:00:00', '9055315331', 0, 0, 0, '', ''),
(3, 'Fabulous', 'info+fab@trinoweb.com', '$2y$10$440weczzi7gl8OpXQJROPey1Eiyx1BQWk4dFEj9pAHWO2FmagZQ52', '', '2016-11-16 20:49:31', '0000-00-00 00:00:00', '9055315331', 1481048458, 0, 2, '', 'cus_9yYE78hosPbuGH'),
(4, 'Marvellous', 'info+mar@trinoweb.com', '$2y$10$440weczzi7gl8OpXQJROPey1Eiyx1BQWk4dFEj9pAHWO2FmagZQ52', '', '2017-02-14 20:28:50', '0000-00-00 00:00:00', '9055555555', 0, 0, 2, '', ''),
(48, 'van trinh', 'info@trinoweb.com', '$2y$10$.0DQCK8l9YOr49mc3AcEr.8zemyiRmUa1j69p5MJO4vf6PCIAOip.', '', '2017-04-01 21:18:32', '0000-00-00 00:00:00', '9055315331', 0, 0, 0, '', 'cus_AOdXYiiOWQgnl0'),
(49, 'Van Account 2', 'dvt1985@hotmail.com', '$2y$10$U7sVKr5wZxX4q7tHqlACgOp5b6mtaiA7XU6oiBgjHvbZxYptKN4E2', '', '2017-04-01 21:51:14', '0000-00-00 00:00:00', '9055555555', 1491173929, 1, 0, '', 'cus_AOewx5Y9EC4GYy'),
(50, 'Quality Pizza & Wings', 'info+qua@trinoweb.com', '$2y$10$S5SNVTZgWk9Ufe.kLdtaMOOMo2VxRqkXUpv1k/af09Bn1c32UUrcq', '', '2017-04-01 22:20:18', '0000-00-00 00:00:00', '9055315331', 0, 0, 2, '', ''),
(51, 'Ellen', 'ellentrinh17@hotmail.com', '$2y$10$qWy/VqUvDQ818.d4h6uo3OcsQo9cXvDzmGY6.Ze90L1dC7rQ.6.T2', '', '2017-04-02 03:31:20', '0000-00-00 00:00:00', '2897006367', 0, 0, 0, '', 'cus_AOjCut5qMBakH4'),
(52, 'M', 'ngocminh89@hotmail.com', '$2y$10$KWTO4ry2XYuiOBXnfbAdmeFJyEFFq3CBHyvhH8yotPU7k7nEofvty', '', '2017-04-02 05:17:07', '0000-00-00 00:00:00', '9055315333', 0, 0, 0, '', 'cus_AOktWUGBmxxRCr');

-- --------------------------------------------------------

--
-- Table structure for table `wings_sauce`
--

CREATE TABLE IF NOT EXISTS `wings_sauce` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `isfree` tinyint(1) NOT NULL,
  `qualifiers` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'comma delimited list of the names for 1/2,x1,x2 if applicable',
  `isall` tinyint(4) NOT NULL DEFAULT '1',
  `groupid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `wings_sauce`
--

INSERT INTO `wings_sauce` (`id`, `name`, `type`, `isfree`, `qualifiers`, `isall`, `groupid`) VALUES
(1, 'Honey Garlic', 'Sauce', 0, '', 1, 1),
(2, 'Mild', 'Sauce', 0, '', 1, 1),
(3, 'BBQ', 'Sauce', 0, '', 1, 1),
(4, 'Hot', 'Sauce', 0, '', 1, 1),
(5, 'Suicide', 'Sauce', 0, '', 1, 1),
(6, 'Sauce on Side', 'zPreparation', 1, '', 1, 2),
(7, 'Well Done', 'zPreparation', 1, '', 1, 3);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
