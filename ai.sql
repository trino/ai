/*
SQLyog Professional
MySQL - 5.5.54-cll : Database - londonpi_db
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `menu` */

CREATE TABLE `menu` (
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

/*Data for the table `menu` */

LOCK TABLES `menu` WRITE;

insert  into `menu`(`id`,`category_id`,`category`,`item`,`price`,`toppings`,`wings_sauce`,`calories`,`allergens`) values 
(1,1,'Pizza','Small Pizza',4.95,1,0,'',''),
(2,1,'Pizza','Medium Pizza',5.75,1,0,'',''),
(3,1,'Pizza','Large Pizza',6.95,1,0,'',''),
(4,1,'Pizza','X-Large Pizza',9.95,1,0,'',''),
(5,1,'Pizza','2 Small Pizzas',9.95,2,0,'',''),
(6,1,'Pizza','2 Medium Pizzas',15.95,2,0,'',''),
(7,1,'Pizza','2 Large Pizzas',17.95,2,0,'',''),
(8,1,'Pizza','2 X-Large Pizzas',19.95,2,0,'',''),
(9,3,'Dips','Tomato Dip',0.7,0,0,'',''),
(10,3,'Dips','Hot Dip',0.7,0,0,'',''),
(11,3,'Dips','Cheddar Dip',0.7,0,0,'',''),
(12,3,'Dips','Marinara Dip',0.7,0,0,'',''),
(13,3,'Dips','Ranch Dip',0.7,0,0,'',''),
(14,3,'Dips','Blue Cheese Dip',0.7,0,0,'',''),
(15,4,'Wings','1 lb Wings',6.99,0,1,'',''),
(16,4,'Wings','2 lb Wings',12.99,0,2,'',''),
(17,4,'Wings','3 lb Wings',17.99,0,3,'',''),
(18,4,'Wings','4 lb Wings',24.99,0,4,'',''),
(19,4,'Wings','5 lb Wings',28.99,0,5,'',''),
(20,5,'Sides','Panzerotti',5.99,1,0,'',''),
(21,5,'Sides','Garlic Bread',2.25,0,0,'',''),
(22,5,'Sides','French Fries',3.99,0,0,'',''),
(23,5,'Sides','Potato Wedges',3.99,0,0,'',''),
(27,5,'Sides','Chicken Salad ',5.99,0,0,'',''),
(28,5,'Sides','Caesar Salad',3.99,0,0,'',''),
(29,5,'Sides','Garden Salad',3.99,0,0,'',''),
(32,6,'Drinks','Coca-Cola',0.95,0,0,'',''),
(33,6,'Drinks','Diet Coca-Cola',0.95,0,0,'',''),
(34,6,'Drinks','Pepsi',0.95,0,0,'',''),
(35,6,'Drinks','Diet Pepsi',0.95,0,0,'',''),
(36,6,'Drinks','Sprite',0.95,0,0,'',''),
(37,6,'Drinks','Crush Orange',0.95,0,0,'',''),
(38,6,'Drinks','Dr. Pepper',0.95,0,0,'',''),
(39,6,'Drinks','Ginger Ale',0.95,0,0,'',''),
(40,6,'Drinks','Nestea',0.95,0,0,'',''),
(41,6,'Drinks','Water Bottle',0.95,0,0,'',''),
(45,6,'Drinks','2L Coca-Cola',2.99,0,0,'',''),
(46,6,'Drinks','2L Sprite',2.99,0,0,'',''),
(47,6,'Drinks','2L Brisk Iced Tea',2.99,0,0,'','');

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
