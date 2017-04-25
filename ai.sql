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
/*Table structure for table `actions` */

CREATE TABLE `actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eventname` varchar(64) NOT NULL,
  `party` tinyint(4) NOT NULL COMMENT '0=user,1=admin,2=restaurant',
  `sms` tinyint(1) NOT NULL,
  `phone` tinyint(1) NOT NULL,
  `email` tinyint(1) NOT NULL,
  `message` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/*Data for the table `actions` */

LOCK TABLES `actions` WRITE;

insert  into `actions`(`id`,`eventname`,`party`,`sms`,`phone`,`email`,`message`) values 
(1,'order_placed',2,0,1,1,'londonpizza.ca - A new order was placed'),
(2,'order_placed',1,1,0,0,'londonpizza.ca - A new order was placed'),
(3,'order_placed',0,0,0,1,'londonpizza.ca - Here is your receipt'),
(4,'order_declined',0,1,0,1,'londonpizza.ca - Your order was cancelled: [reason]'),
(5,'order_declined',1,1,0,0,'londonpizza.ca - An order was cancelled: [reason]'),
(6,'order_confirmed',1,1,0,0,'londonpizza.ca - An order was approved: [reason]'),
(7,'user_registered',0,0,0,1,'londonpizza.ca - Thank you for registering'),
(9,'user_registered',1,0,0,1,'londonpizza.ca - Thank you for registering');

UNLOCK TABLES;

/*Table structure for table `additional_toppings` */

CREATE TABLE `additional_toppings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `size` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `additional_toppings` */

LOCK TABLES `additional_toppings` WRITE;

insert  into `additional_toppings`(`id`,`size`,`price`) values 
(1,'Small',0.95),
(2,'Medium',1.2),
(3,'Large',1.5),
(4,'X-Large',1.7),
(6,'Panzerotti',0.95),
(7,'Delivery',3),
(8,'Minimum',15),
(10,'DeliveryTime',45);

UNLOCK TABLES;

/*Table structure for table `hours` */

CREATE TABLE `hours` (
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

/*Data for the table `hours` */

LOCK TABLES `hours` WRITE;

insert  into `hours`(`restaurant_id`,`0_open`,`0_close`,`1_open`,`1_close`,`2_open`,`2_close`,`3_open`,`3_close`,`4_open`,`4_close`,`5_open`,`5_close`,`6_open`,`6_close`) values 
(0,-1,-1,1100,2250,1100,2250,1100,2250,1100,2250,1100,50,1100,50);

UNLOCK TABLES;

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
(32,6,'Drinks','Coca-Cola',0.95,0,0,'139','sugar=40g'),
(33,6,'Drinks','Diet Coca-Cola',0.95,0,0,'0',''),
(34,6,'Drinks','Pepsi',0.95,0,0,'150',''),
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

/*Table structure for table `orders` */

CREATE TABLE `orders` (
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
) ENGINE=InnoDB AUTO_INCREMENT=245 DEFAULT CHARSET=latin1;

/*Data for the table `orders` */

LOCK TABLES `orders` WRITE;

insert  into `orders`(`id`,`user_id`,`placed_at`,`number`,`unit`,`buzzcode`,`street`,`postalcode`,`city`,`province`,`latitude`,`longitude`,`accepted_at`,`restaurant_id`,`type`,`payment_type`,`phone`,`cell`,`paid`,`stripeToken`,`deliverytime`,`cookingnotes`,`status`,`price`,`email`) values 
(241,53,'2017-04-22 14:07:10',300,'123','','Dundas St','N6B 1T6','London','Ontario','42.9854177','-81.244139099999','0000-00-00 00:00:00',1,0,0,'','',1,'tok_AWVffGXm9uLn37','Deliver Now','',0,145.33,NULL),
(242,54,'2017-04-23 12:27:38',18,'side door','','Oakland Dr','L8E 3Z2','Hamilton','Ontario','43.2304400000000','-79.7693198','0000-00-00 00:00:00',3,0,0,'9055315331','',1,'tok_AWrHyVmwWDT0Js','April 24 at 1100','',0,68.46,NULL),
(243,54,'2017-04-23 12:29:58',18,'side door','','Oakland Dr','L8E 3Z2','Hamilton','Ontario','43.2304400000000','-79.7693198','0000-00-00 00:00:00',3,0,0,'9055315331','',1,'tok_AWrJfau3ExrF5E','April 24 at 1100','',0,25.93,NULL),
(244,54,'2017-04-23 12:41:23',18,'side door','','Oakland Dr','L8E 3Z2','Hamilton','Ontario','43.2304400000000','-79.7693198','0000-00-00 00:00:00',3,0,0,'','',1,'','April 24 at 1100','',0,25.88,NULL);

UNLOCK TABLES;

/*Table structure for table `presets` */

CREATE TABLE `presets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `toppings` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Data for the table `presets` */

LOCK TABLES `presets` WRITE;

insert  into `presets`(`id`,`name`,`toppings`) values 
(1,'hawaiian','pineapple bacon ham'),
(2,'canadian','pepperoni mushrooms bacon'),
(3,'deluxe','pepperoni mushrooms green peppers'),
(4,'vegetarian','mushrooms tomatoes green peppers'),
(5,'meat','sausage salami bacon pepperoni'),
(6,'super','pepperoni mushrooms green peppers'),
(7,'supreme','pepperoni mushrooms green peppers');

UNLOCK TABLES;

/*Table structure for table `restaurants` */

CREATE TABLE `restaurants` (
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `restaurants` */

LOCK TABLES `restaurants` WRITE;

insert  into `restaurants`(`id`,`name`,`slug`,`email`,`phone`,`cuisine`,`website`,`description`,`logo`,`is_delivery`,`is_pickup`,`max_delivery_distance`,`delivery_fee`,`minimum`,`is_complete`,`lastorder_id`,`franchise`,`address_id`) values 
(1,'Fabulous 2 for 1 Pizza','','(905) 512-3067','9055315331','','','','',1,0,0,0,0,0,0,0,1),
(2,'INACTIVE Marvellous Pizza','','(519) 452-1044','9055315331','','','','',0,0,0,0,0,0,0,0,2),
(3,'Quality Pizza & Wings','','','9055315331','','','','',1,0,0,0,0,0,0,0,97);

UNLOCK TABLES;

/*Table structure for table `settings` */

CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyname` varchar(255) NOT NULL,
  `value` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `keyname` (`keyname`)
) ENGINE=InnoDB AUTO_INCREMENT=1393 DEFAULT CHARSET=latin1;

/*Data for the table `settings` */

LOCK TABLES `settings` WRITE;

insert  into `settings`(`id`,`keyname`,`value`) values 
(1,'lastSQL','1492637296'),
(20,'orders','1487775876'),
(24,'menucache','1493084122'),
(25,'useraddresses','1491932853'),
(37,'users','1487175217'),
(38,'additional_toppings','1487175322'),
(43,'actions','1492527405'),
(87,'restaurants','1489588141');

UNLOCK TABLES;

/*Table structure for table `toppings` */

CREATE TABLE `toppings` (
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

/*Data for the table `toppings` */

LOCK TABLES `toppings` WRITE;

insert  into `toppings`(`id`,`name`,`type`,`isfree`,`qualifiers`,`isall`,`groupid`) values 
(1,'Anchovies','Meat',0,'',0,0),
(2,'Bacon','Meat',0,'',0,0),
(3,'Beef Salami','Meat',0,'',0,0),
(4,'Chicken','Meat',0,'',0,0),
(5,'Ground Beef','Meat',0,'',0,0),
(6,'Ham','Meat',0,'',0,0),
(7,'Hot Italian Sausage','Meat',0,'',0,0),
(8,'Hot Sausage','Meat',0,'',0,0),
(9,'Italian Sausage','Meat',0,'',0,0),
(10,'Mild Sausage','Meat',0,'',0,0),
(11,'Pepperoni','Meat',0,'',0,0),
(12,'Salami','Meat',0,'',0,0),
(13,'Artichoke Heart','Vegetable',0,'',0,0),
(14,'Black Olives','Vegetable',0,'',0,0),
(15,'Broccoli','Vegetable',0,'',0,0),
(16,'Green Olives','Vegetable',0,'',0,0),
(17,'Green Peppers','Vegetable',0,'',0,0),
(18,'Hot Banana Peppers','Vegetable',0,'',0,0),
(19,'Hot Peppers','Vegetable',0,'',0,0),
(20,'Jalapeno Peppers','Vegetable',0,'',0,0),
(21,'Mushrooms','Vegetable',0,'',0,0),
(22,'Onions','Vegetable',0,'',0,0),
(23,'Pineapple','Vegetable',0,'',0,0),
(24,'Red Onions','Vegetable',0,'',0,0),
(25,'Red Peppers','Vegetable',0,'',0,0),
(26,'Spinach','Vegetable',0,'',0,0),
(27,'Sundried Tomatoes','Vegetable',0,'',0,0),
(28,'Tomatoes','Vegetable',0,'',0,0),
(29,'Extra Cheese','Vegetable',0,'',0,0),
(31,'Well Done','zPreparation',1,'',1,1);

UNLOCK TABLES;

/*Table structure for table `useraddresses` */

CREATE TABLE `useraddresses` (
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
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=latin1;

/*Data for the table `useraddresses` */

LOCK TABLES `useraddresses` WRITE;

insert  into `useraddresses`(`id`,`user_id`,`number`,`unit`,`buzzcode`,`street`,`postalcode`,`city`,`province`,`latitude`,`longitude`,`phone`) values 
(1,3,483,'','','Dundas Street','N6B 1W4','London','Ontario','42.9871816','-81.2386115',''),
(2,4,1565,'','','Western Rd','N6G 1H5','London','Ontario','43.0187','-81.2812887',''),
(3,5,483,'','','Dundas Street','N6B 1W4','London','Ontario','42.9871816','-81.2386115',''),
(4,6,1569,'','','Oxford Street East','N5V 1W5','London','Ontario','43.0109195','-81.198983600000',''),
(95,48,300,'','','Dundas St','N6B 1T6','London','Ontario','42.9854177','-81.244139099999',''),
(96,49,300,'','','Dundas St','N6B 1T6','London','Ontario','42.9854177','-81.244139099999',''),
(97,50,2372,'','','Barton St E','L8E 2W7','Hamilton','Ontario','43.2376467','-79.765102399999',''),
(103,54,18,'side door','','Oakland Dr','L8E 3Z2','Hamilton','Ontario','43.2304400000000','-79.7693198',''),
(104,55,1001,'','','Fanshawe College Blvd','N5V 2A5','London','Ontario','43.013414','-81.199466000000','');

UNLOCK TABLES;

/*Table structure for table `users` */

CREATE TABLE `users` (
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
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `users` */

LOCK TABLES `users` WRITE;

insert  into `users`(`id`,`name`,`email`,`password`,`remember_token`,`created_at`,`updated_at`,`phone`,`lastlogin`,`loginattempts`,`profiletype`,`authcode`,`stripecustid`) values 
(1,'Roy Wall','roy@trinoweb.com','$2y$10$440weczzi7gl8OpXQJROPey1Eiyx1BQWk4dFEj9pAHWO2FmagZQ52','','0000-00-00 00:00:00','2017-02-01 10:15:40','9055315331',1487608084,0,1,'',''),
(2,'Roy Test','roy+test@trinoweb.com','$2y$10$440weczzi7gl8OpXQJROPey1Eiyx1BQWk4dFEj9pAHWO2FmagZQ52','','2016-11-16 15:20:28','0000-00-00 00:00:00','9055315331',0,0,0,'',''),
(3,'Fabulous','info+fab@trinoweb.com','$2y$10$440weczzi7gl8OpXQJROPey1Eiyx1BQWk4dFEj9pAHWO2FmagZQ52','','2016-11-16 15:49:31','0000-00-00 00:00:00','9055315331',1481048458,0,2,'','cus_9yYE78hosPbuGH'),
(4,'Marvellous','info+mar@trinoweb.com','$2y$10$440weczzi7gl8OpXQJROPey1Eiyx1BQWk4dFEj9pAHWO2FmagZQ52','','2017-02-14 15:28:50','0000-00-00 00:00:00','9055315331',0,0,2,'',''),
(48,'Van Trinh','info@trinoweb.com','$2y$10$.0DQCK8l9YOr49mc3AcEr.8zemyiRmUa1j69p5MJO4vf6PCIAOip.','','2017-04-01 17:18:32','2017-04-22 13:15:53','9055315331',0,0,0,'',''),
(50,'Quality Pizza & Wings','odealyonline@gmail.com','$2y$10$S5SNVTZgWk9Ufe.kLdtaMOOMo2VxRqkXUpv1k/af09Bn1c32UUrcq','','2017-04-01 18:20:18','0000-00-00 00:00:00','9055315331',0,0,2,'',''),
(54,'Van Dao Trinh','dvt1985@hotmail.com','$2y$10$202F5bICMxbyYm1VT7Petey5iUhHeMKI.HJgpM9bsB0MWFg7o5mPa','','2017-04-23 16:16:17','0000-00-00 00:00:00','9055315331',0,0,0,'','cus_AWrH95lAblBCVy'),
(55,'J','j@j.com','$2y$10$4HDeNQb4bjCIr8.LL9LVoeKxK8H.y9ao7GNnqIo7iKWcOVClFptCm','','2017-04-23 21:26:03','0000-00-00 00:00:00','',0,0,0,'','');

UNLOCK TABLES;

/*Table structure for table `wings_sauce` */

CREATE TABLE `wings_sauce` (
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

/*Data for the table `wings_sauce` */

LOCK TABLES `wings_sauce` WRITE;

insert  into `wings_sauce`(`id`,`name`,`type`,`isfree`,`qualifiers`,`isall`,`groupid`) values 
(1,'Honey Garlic','Sauce',0,'',1,1),
(3,'BBQ','Sauce',0,'',1,1),
(4,'Hot','Sauce',0,'',1,1),
(5,'Suicide','Sauce',0,'',1,1),
(6,'Sauce on Side','zPreparation',1,'',1,2),
(7,'Well Done','zPreparation',1,'',1,3);

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
