/*
SQLyog Professional
MySQL - 5.6.17 : Database - ai
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `additional_toppings` */

CREATE TABLE `additional_toppings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `size` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `additional_toppings` */

LOCK TABLES `additional_toppings` WRITE;

insert  into `additional_toppings`(`id`,`size`,`price`) values 
(1,'Small',0.95),
(2,'Medium',1.2),
(3,'Large',1.5),
(4,'X-Large',1.7),
(6,'Panzerotti',0.95),
(7,'Delivery',3.99);

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
  `wings_sauce` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `menu` */

LOCK TABLES `menu` WRITE;

insert  into `menu`(`id`,`category_id`,`category`,`item`,`price`,`toppings`,`wings_sauce`) values 
(1,1,'Pizza','Small Pizza',4.95,1,0),
(2,1,'Pizza','Medium Pizza',5.75,1,0),
(3,1,'Pizza','Large Pizza',6.95,1,0),
(4,1,'Pizza','X-Large Pizza',9.95,1,0),
(5,1,'Pizza','2 Small Pizzas',9.95,2,0),
(6,1,'Pizza','2 Medium Pizzas',15.95,2,0),
(7,1,'Pizza','2 Large Pizzas',17.95,2,0),
(8,1,'Pizza','2 X-Large Pizzas',19.95,2,0),
(15,4,'Wings','1 lb Wings',6.99,0,1),
(16,4,'Wings','2 lbs Wings',12.99,0,2),
(17,4,'Wings','3 lbs Wings',17.99,0,3),
(18,4,'Wings','4 lbs Wings',24.99,0,4),
(19,4,'Wings','5 lbs Wings',28.99,0,5),
(20,5,'Sides','Panzerotti',5.99,1,0),
(21,5,'Sides','Garlic Bread',2.25,0,0),
(22,5,'Sides','French Fries',3.99,0,0),
(23,5,'Sides','Potato Wedges',3.99,0,0),
(27,5,'Sides','Chicken Salad ',5.99,0,0),
(29,5,'Sides','Garden Salad',3.99,0,0),
(28,5,'Sides','Caesar Salad',3.99,0,0),
(9,3,'Dips','Tomato Dip',0.7,0,0),
(10,3,'Dips','Hot Dip',0.7,0,0),
(11,3,'Dips','Cheddar Dip',0.7,0,0),
(12,3,'Dips','Marinara Dip',0.7,0,0),
(13,3,'Dips','Ranch Dip',0.7,0,0),
(14,3,'Dips','Blue Cheese Dip',0.7,0,0),
(35,6,'Drinks','Diet Pepsi',0.95,0,0),
(34,6,'Drinks','Pepsi',0.95,0,0),
(32,6,'Drinks','Coca-Cola',0.95,0,0),
(33,6,'Drinks','Diet Coca-Cola',0.95,0,0),
(36,6,'Drinks','Sprite',0.95,0,0),
(37,6,'Drinks','Crush Orange',0.95,0,0),
(38,6,'Drinks','Dr. Pepper',0.95,0,0),
(39,6,'Drinks','Ginger Ale',0.95,0,0),
(40,6,'Drinks','Nestea',0.95,0,0),
(41,6,'Drinks','Water Bottle',0.95,0,0),
(45,6,'Drinks','2L Coca-Cola',2.99,0,0),
(46,6,'Drinks','2L Sprite',2.99,0,0),
(47,6,'Drinks','2L Brisk Iced Tea',2.99,0,0);

UNLOCK TABLES;

/*Table structure for table `orders` */

CREATE TABLE `orders` (
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `orders` */

LOCK TABLES `orders` WRITE;

insert  into `orders`(`id`,`user_id`,`placed_at`,`number`,`unit`,`buzzcode`,`street`,`postalcode`,`city`,`province`,`latitude`,`longitude`,`accepted_at`,`restaurant_id`,`type`,`payment_type`,`phone`,`cell`,`paid`,`stripeToken`,`deliverytime`,`cookingnotes`,`status`,`price`,`email`) values 
(1,32,'2017-01-29 20:49:33',400,'','','Dundas Street','N6B 1V7','London','Ontario','42.9866144999999','-81.240151500000','0000-00-00 00:00:00',1,0,0,'9055315332','',1,'tok_A1SXBSzbQntVqk','January 30 at 1100','',0,5.58,NULL),
(2,32,'2017-01-29 21:34:12',400,'','','Dundas Street','N6B 1V7','London','Ontario','42.9866144999999','-81.240151500000','0000-00-00 00:00:00',1,0,0,'9055315332','',1,'tok_A1TFeO07cBS3MB','January 30 at 1515','',0,35.76,NULL),
(3,32,'2017-01-29 21:40:11',400,'','','Dundas Street','N6B 1V7','London','Ontario','42.9866144999999','-81.240151500000','0000-00-00 00:00:00',1,0,0,'9055315332','',1,'','January 30 at 1100','',0,23.61,NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `restaurants` */

LOCK TABLES `restaurants` WRITE;

insert  into `restaurants`(`id`,`name`,`slug`,`email`,`phone`,`cuisine`,`website`,`description`,`logo`,`is_delivery`,`is_pickup`,`max_delivery_distance`,`delivery_fee`,`minimum`,`is_complete`,`lastorder_id`,`franchise`,`address_id`) values 
(1,'Fabulous 2 for 1 Pizza & Wings','','','(905) 512-3067','','','','',0,0,0,0,0,0,0,0,1),
(2,'TEST RESTRAUNT','','','(905) 512-3000','','','','',0,0,0,0,0,0,0,0,58);

UNLOCK TABLES;

/*Table structure for table `settings` */

CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyname` varchar(255) NOT NULL,
  `value` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `keyname` (`keyname`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;

/*Data for the table `settings` */

LOCK TABLES `settings` WRITE;

insert  into `settings`(`id`,`keyname`,`value`) values 
(1,'lastSQL','1484789400'),
(20,'orders','1485052315'),
(24,'menucache','1484882445'),
(25,'useraddresses','1485395212'),
(37,'users','1479345588'),
(38,'additional_toppings','1479345609');

UNLOCK TABLES;

/*Table structure for table `toppings` */

CREATE TABLE `toppings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `isfree` tinyint(1) NOT NULL,
  `qualifiers` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'comma delimited list of the names for 1/2,x1,x2 if applicable',
  `isall` tinyint(4) NOT NULL DEFAULT '0',
  `group` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `toppings` */

LOCK TABLES `toppings` WRITE;

insert  into `toppings`(`id`,`name`,`type`,`isfree`,`qualifiers`,`isall`,`group`) values 
(1,'Anchovies','Meat',0,'',0,0),
(2,'Artichoke Heart','Vegetable',0,'',0,0),
(3,'Bacon','Meat',0,'',0,0),
(4,'Beef Salami','Meat',0,'',0,0),
(5,'Black Olives','Vegetable',0,'',0,0),
(6,'Broccoli','Vegetable',0,'',0,0),
(9,'Chicken','Meat',0,'',0,0),
(13,'Green Olives','Vegetable',0,'',0,0),
(14,'Green Peppers','Vegetable',0,'',0,0),
(15,'Ground Beef','Meat',0,'',0,0),
(16,'Ham','Meat',0,'',0,0),
(17,'Hot Banana Peppers','Vegetable',0,'',0,0),
(18,'Hot Italian Sausage','Meat',0,'',0,0),
(19,'Hot Peppers','Vegetable',0,'',0,0),
(20,'Hot Sausage','Meat',0,'',0,0),
(21,'Italian Sausage','Meat',0,'',0,0),
(23,'Jalapeno Peppers','Vegetable',0,'',0,0),
(24,'Mild Sausage','Meat',0,'',0,0),
(27,'Mushrooms','Vegetable',0,'',0,0),
(28,'Onions','Vegetable',0,'',0,0),
(30,'Pepperoni','Meat',0,'',0,0),
(31,'Pineapple','Vegetable',0,'',0,0),
(32,'Red Onions','Vegetable',0,'',0,0),
(33,'Red Peppers','Vegetable',0,'',0,0),
(34,'Salami','Meat',0,'',0,0),
(35,'Spinach','Vegetable',0,'',0,0),
(36,'Sundried Tomatoes','Vegetable',0,'',0,0),
(37,'Tomatoes','Vegetable',0,'',0,0),
(38,'Well Done','zPreparation',1,'Lightly done, Regular, Well done',1,1),
(40,'Lightly Done','zPreparation',1,'',1,1);

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
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=latin1;

/*Data for the table `useraddresses` */

LOCK TABLES `useraddresses` WRITE;

insert  into `useraddresses`(`id`,`user_id`,`number`,`unit`,`buzzcode`,`street`,`postalcode`,`city`,`province`,`latitude`,`longitude`,`phone`) values 
(1,21,483,'','','Dundas Street','N6B 1W4','London','Ontario','42.9871816','-81.2386115',''),
(58,1,490,'A','','Dundas Street','N6B 1W4','London','Ontario','42.9826144999999','-81.220151500000',''),
(60,33,400,'123','','Dundas Street','N6B 1V7','London','Ontario','42.9866144999999','-81.240151500000',''),
(61,33,420,'','','Dundas Street','N6B 1V7','London','Ontario','42.9864902','-81.240078499999',''),
(63,34,2345,'Ieufx','','Bloor Street West','M6S 1P4','Toronto','Ontario','43.64993','-79.480810000000',''),
(64,34,400,'','','Dundas Street','N6B 1V7','London','Ontario','42.9866144999999','-81.240151500000',''),
(65,32,400,'','','Dundas Street','N6B 1V7','London','Ontario','42.9866144999999','-81.240151500000',''),
(66,35,444,'','','Dundas Street','N6B 3K3','London','Ontario','42.9868405','-81.238882900000','');

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
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `users` */

LOCK TABLES `users` WRITE;

insert  into `users`(`id`,`name`,`email`,`password`,`remember_token`,`created_at`,`updated_at`,`phone`,`lastlogin`,`loginattempts`,`profiletype`,`authcode`,`stripecustid`) values 
(1,'Roy Wall','roy@trinoweb.com','$2y$10$440weczzi7gl8OpXQJROPey1Eiyx1BQWk4dFEj9pAHWO2FmagZQ52','','0000-00-00 00:00:00','2017-01-03 18:29:36','905 531 5331',1479912217,6,1,'','cus_9rgc8wsT5ZzV05'),
(20,'Roy Test','roy+test@trinoweb.com','$2y$10$XqUn.RNhx0YbcZUQXWYP0eHIz0aLK8xX00cd.PLVRQsafF9Frod6K','','2016-11-16 15:20:28','0000-00-00 00:00:00','9055315331',0,0,0,'',''),
(21,'Jonas Morse','info+rest@trinoweb.com','$2y$10$XqUn.RNhx0YbcZUQXWYP0eHIz0aLK8xX00cd.PLVRQsafF9Frod6K','','2016-11-16 15:49:31','0000-00-00 00:00:00','9055315331',1481048458,1,2,'','cus_9yYE78hosPbuGH'),
(32,'Van Trinh','info@trinoweb.com','$2y$10$/BnVGwDyTms/wiSNHaUfI.nH.VMN83zcW1H8f3XcTbxWhLXz53nIC','','2017-01-14 17:07:38','2017-01-25 20:50:19','9055315332',1485713654,2,0,'','cus_9zK5kmVw7FLvt8');

UNLOCK TABLES;

/*Table structure for table `wings_sauce` */

CREATE TABLE `wings_sauce` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `isfree` tinyint(1) NOT NULL,
  `qualifiers` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'comma delimited list of the names for 1/2,x1,x2 if applicable',
  `isall` tinyint(4) NOT NULL DEFAULT '1',
  `group` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `wings_sauce` */

LOCK TABLES `wings_sauce` WRITE;

insert  into `wings_sauce`(`id`,`name`,`type`,`isfree`,`qualifiers`,`isall`,`group`) values 
(1,'Mild','Sauce',0,'',1,2),
(2,'Medium','Sauce',0,'',1,2),
(3,'Hot','Sauce',0,'',1,2),
(4,'Suicide','Sauce',0,'',1,2),
(5,'BBQ','Sauce',0,'',1,2),
(6,'Honey Garlic','Sauce',0,'',1,2),
(7,'Well Done','zPreparation',1,'Lightly done, Regular, Well done',1,1),
(9,'Sauce on Side','zPreparation',1,'',1,3);

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
