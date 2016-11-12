

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
(6,'Panzerotti',1.2),
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

/*Table structure for table `keywords` */

CREATE TABLE `keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `synonyms` varchar(1024) NOT NULL,
  `weight` int(11) NOT NULL,
  `keywordtype` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=latin1;

/*Data for the table `keywords` */

LOCK TABLES `keywords` WRITE;

insert  into `keywords`(`id`,`synonyms`,`weight`,`keywordtype`) values
(1,'italian',1,0),
(2,'pizza',5,0),
(6,'2 two',1,1),
(7,'chicken',1,0),
(8,'wing',5,0),
(9,'3 three',1,1),
(10,'dip',5,0),
(11,'cheddar',1,0),
(12,'jalapeno',1,0),
(13,'marinara',1,0),
(14,'bbq barbeque',1,0),
(15,'garlic',1,0),
(16,'parmesan',1,0),
(17,'honey',1,0),
(18,'hot',1,0),
(19,'medium med',1,0),
(20,'mild',1,0),
(21,'ranch',1,0),
(22,'buffalo',1,0),
(23,'spicy',1,0),
(24,'1 one',1,1),
(25,'4 four',1,1),
(26,'5 five',1,1),
(27,'small sm',1,2),
(28,'large lg',1,2),
(29,'extra xl ex',1,2),
(30,'lbl pound lb',1,2),
(32,'drink beverage soda pop can',5,0),
(33,'coke cola',1,0),
(34,'diet',1,0),
(35,'liter litre lt bottle ltr',1,0),
(36,'iced ice tea nestea lipton brisk',1,0),
(37,'ginger ale',1,0),
(38,'pepper doctor',1,0),
(39,'crush',1,0),
(40,'orange',1,0),
(41,'pepsi',1,0),
(42,'7up seven',1,0),
(44,'water h20',1,0),
(45,'salad',5,0),
(46,'caesar',1,0),
(47,'greek',1,0),
(48,'garden',1,0),
(49,'side',1,0),
(50,'poutine',5,0),
(51,'french fry frie',5,0),
(52,'wedges potato potatoe',5,0),
(53,'ring',5,0),
(54,'veggie',5,0),
(55,'stick',1,0),
(56,'bread',5,0),
(60,'regular classic',1,0),
(61,'panzerotti calzone pocket panzerottie',5,0);

UNLOCK TABLES;

/*Table structure for table `locations` */

CREATE TABLE `locations` (
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

/*Data for the table `locations` */

LOCK TABLES `locations` WRITE;

UNLOCK TABLES;

/*Table structure for table `menu` */

CREATE TABLE `menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) NOT NULL,
  `category` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `item` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `toppings` tinyint(1) NOT NULL,
  `wings_sauce` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `menu` */

LOCK TABLES `menu` WRITE;

insert  into `menu`(`id`,`category_id`,`category`,`item`,`price`,`toppings`,`wings_sauce`) values
(1,1,'Pizza','Small Pizza',5.99,1,0),
(2,1,'Pizza','Medium Pizza',6.99,1,0),
(3,1,'Pizza','Large Pizza',8.99,1,0),
(4,1,'Pizza','X-Large Pizza',11.99,1,0),
(5,2,'2 for 1 Pizza','2 Small Pizza',10.99,2,0),
(6,2,'2 for 1 Pizza','2 Medium Pizza',12.99,2,0),
(7,2,'2 for 1 Pizza','2 Large Pizza',15.99,2,0),
(8,2,'2 for 1 Pizza','2 X-Large Pizza',19.99,2,0),
(9,4,'Wings','1 Pound Wings',6.99,0,1),
(10,4,'Wings','2 Pound Wings',15.99,0,2),
(11,4,'Wings','3 Pound Wings',17.99,0,3),
(12,4,'Wings','4 Pound Wings',24.99,0,4),
(13,4,'Wings','5 Pound Wings',28.99,0,5),
(14,5,'Sides','Panzerotti',5.99,0,0),
(15,5,'Sides','Garlic Bread',2.25,0,0),
(16,5,'Sides','French Fries',28.99,0,0),
(17,5,'Sides','Potato Wedges',3.99,0,0),
(18,5,'Sides','Onion Rings',28.99,0,0),
(19,5,'Sides','12 Chicken Nuggets',4.99,0,0),
(20,5,'Sides','24 Chicken Nuggets',7.99,0,0),
(21,5,'Sides','Large Lasagna',6.99,0,0),
(22,5,'Sides','Veggie Sticks',28.99,0,0),
(23,5,'Sides','Garden Salad ',28.99,0,0),
(24,5,'Sides','Caesar Salad',3.99,0,0),
(25,5,'Sides','Large Caesar Salad',6.99,0,0),
(26,6,'Dips','Tomato',0.7,0,0),
(27,6,'Dips','Hot ',0.7,0,0),
(28,6,'Dips','Garlic Bread',0.7,0,0),
(29,6,'Dips','Cheddar',0.7,0,0),
(30,6,'Dips','Marinara',0.7,0,0),
(31,6,'Dips','Ranch',0.7,0,0),
(32,6,'Dips','Blue Cheese',0.7,0,0),
(33,7,'Drinks','Diet Pepsi',0.95,0,0),
(34,7,'Drinks','Pepsi',0.95,0,0),
(35,7,'Drinks','Coca-Cola',0.95,0,0),
(36,7,'Drinks','Diet Coca-Cola',0.95,0,0),
(37,7,'Drinks','7-up',0.95,0,0),
(38,7,'Drinks','Crush Orange',0.95,0,0),
(39,7,'Drinks','Dr. Pepper',0.95,0,0),
(40,7,'Drinks','Ginger Ale',0.95,0,0),
(41,7,'Drinks','Iced Tea',0.95,0,0),
(42,7,'Drinks','Water Bottle',0.95,0,0),
(43,7,'Drinks','2L Diet Pepsi',2.99,0,0),
(44,7,'Drinks','2L Pepsi',2.99,0,0),
(45,7,'Drinks','2L Coca-Cola',2.99,0,0),
(46,7,'Drinks','2L Diet Coca-Cola',2.99,0,0),
(47,7,'Drinks','2L 7-up',2.99,0,0),
(48,7,'Drinks','2L Crush Orange',2.99,0,0),
(49,7,'Drinks','2L Dr. Pepper',2.99,0,0),
(50,7,'Drinks','2L Ginger Ale',2.99,0,0),
(51,7,'Drinks','2L Iced Tea',2.99,0,0);

UNLOCK TABLES;

/*Table structure for table `menukeywords` */

CREATE TABLE `menukeywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menuitem_id` int(11) NOT NULL,
  `keyword_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=latin1;

/*Data for the table `menukeywords` */

LOCK TABLES `menukeywords` WRITE;

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=latin1;

/*Data for the table `orders` */

LOCK TABLES `orders` WRITE;

UNLOCK TABLES;

/*Table structure for table `password_resets` */

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL,
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `password_resets` */

LOCK TABLES `password_resets` WRITE;

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `restaurants` */

LOCK TABLES `restaurants` WRITE;

insert  into `restaurants`(`id`,`name`,`slug`,`email`,`phone`,`cuisine`,`website`,`description`,`logo`,`is_delivery`,`is_pickup`,`max_delivery_distance`,`delivery_fee`,`minimum`,`is_complete`,`lastorder_id`,`franchise`,`address_id`) values
(1,'Home','','','(905) 512-3067','','','','',0,0,0,0,0,0,0,0,1);

UNLOCK TABLES;

/*Table structure for table `settings` */

CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyname` varchar(255) NOT NULL,
  `value` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `keyname` (`keyname`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

/*Data for the table `settings` */

LOCK TABLES `settings` WRITE;

insert  into `settings`(`id`,`keyname`,`value`) values
(1,'lastSQL','1478647158'),
(20,'orders','1477502564'),
(24,'menucache','1478095311'),
(25,'useraddresses','1478971665');

UNLOCK TABLES;

/*Table structure for table `toppings` */

CREATE TABLE `toppings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `isfree` tinyint(1) NOT NULL,
  `qualifiers` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'comma delimited list of the names for 1/2,x1,x2 if applicable',
  `isall` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `toppings` */

LOCK TABLES `toppings` WRITE;

insert  into `toppings`(`id`,`name`,`type`,`isfree`,`qualifiers`,`isall`) values
(1,'Anchovies','Meat',0,'',0),
(2,'Artichoke Heart','Vegetable',0,'',0),
(3,'Bacon','Meat',0,'',0),
(4,'Beef Salami','Meat',0,'',0),
(5,'Black Olives','Vegetable',0,'',0),
(6,'Broccoli','Vegetable',0,'',0),
(7,'Cheddar','Cheese',0,'',0),
(8,'Cheese','Cheese',0,'',0),
(9,'Chicken','Meat',0,'',0),
(11,'Feta Cheese','Cheese',0,'',0),
(12,'Fresh Mushroom','Vegetable',0,'',0),
(13,'Green Olives','Vegetable',0,'',0),
(14,'Green Peppers','Vegetable',0,'',0),
(15,'Ground Beef','Meat',0,'',0),
(16,'Ham','Meat',0,'',0),
(17,'Hot Banana Peppers','Vegetable',0,'',0),
(18,'Hot Italian Sausage','Meat',0,'',0),
(19,'Hot Peppers','Vegetable',0,'',0),
(20,'Hot Sausage','Meat',0,'',0),
(21,'Italian Sausage','Meat',0,'',0),
(22,'Tomato Sauce','Preparation',1,'',0),
(23,'Jalapeno Peppers','Vegetable',0,'',0),
(24,'Mild Sausage','Meat',0,'',0),
(25,'Mixed Cheese','Cheese',0,'',0),
(26,'Mozzarella Cheese','Cheese',0,'',0),
(27,'Mushrooms','Vegetable',0,'',0),
(28,'Onions','Vegetable',0,'',0),
(29,'Parmesan Cheese','Cheese',0,'',0),
(30,'Pepperoni','Meat',0,'',0),
(31,'Pineapple','Vegetable',0,'',0),
(32,'Red Onions','Vegetable',0,'',0),
(33,'Red Peppers','Vegetable',0,'',0),
(34,'Salami','Meat',0,'',0),
(35,'Spinach','Vegetable',0,'',0),
(36,'Sundried Tomatoes','Vegetable',0,'',0),
(37,'Tomatoes','Vegetable',0,'',0),
(38,'Cooked','Preparation',1,'Lightly done, Regular, Well done',1);

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

/*Data for the table `useraddresses` */

LOCK TABLES `useraddresses` WRITE;

insert  into `useraddresses`(`id`,`user_id`,`number`,`unit`,`buzzcode`,`street`,`postalcode`,`city`,`province`,`latitude`,`longitude`,`phone`) values
(26,18,1234,'b@b.com','','King Street West','M6K 1G4','Toronto','Ontario','43.6387913000000','-79.4286783','');

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
  `cc_fname` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `cc_lname` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `cc_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cc_xyear` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cc_xmonth` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cc_cc` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `cc_addressid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `users` */

LOCK TABLES `users` WRITE;

insert  into `users`(`id`,`name`,`email`,`password`,`remember_token`,`created_at`,`updated_at`,`phone`,`lastlogin`,`loginattempts`,`profiletype`,`authcode`,`cc_fname`,`cc_lname`,`cc_number`,`cc_xyear`,`cc_xmonth`,`cc_cc`,`cc_addressid`) values
(1,'Roy Hodson','roy@trinoweb.com','$2y$10$XqUn.RNhx0YbcZUQXWYP0eHIz0aLK8xX00cd.PLVRQsafF9Frod6K','','0000-00-00 00:00:00','2016-10-26 14:22:14','(905) 512-3067',1478976317,0,1,'','eyJpdiI6Ikp2c3BNQjFONVhHSSsrZjhCQjJzV2c9PSIsInZhbHVlIjoiQ1puNVFJajUyMktKVlVGVXRnbDhLQT09IiwibWFjIjoiODZlODNlMzZlNWZlYTE5NmEyZGFkOGExZjY0ZmZkMDI1YTY5MjcwZDM3N2Y3ZTdjNmUxMDNjYTQ0ZTk2ZDljNiJ9','eyJpdiI6IjhKMEUrVmtpcXBnVlhuVzdXTk5vQ2c9PSIsInZhbHVlIjoiQ3N1bFVsQ2NLa1Y0SlFHNXpQU3JJQT09IiwibWFjIjoiOGZmMzc2MjJiYmE4YTYwZmJmMmY5YWNhMTFlNWE1MzgxM2E4OWEyZWYxZTQ2ZGFiOTI2YzRjYmUwMzUzYmJmYiJ9','eyJpdiI6ImlQWW81VTFFNGRpVVNFaytUZHcwWmc9PSIsInZhbHVlIjoiZExvajhPWjN1Y09lMEFWWGx0SHQxNjFsdG5Ea3BlcWdIenVtZ0J6Z0Facz0iLCJtYWMiOiI4MjBhMzNjZTE5OGUzYTYxZjM4NGRhYjM1MzgwMjMwNzUyODI3MDFmNGI1MzE3NjY4YTBiZjAzOTE0NTJjZDNiIn0=','eyJpdiI6IllIN3EyNFpJMzgxSEJSeTF4dEU2YkE9PSIsInZhbHVlIjoiWWQ5YTdpeldKdkROWjRndWRRcWRaUT09IiwibWFjIjoiZWU3MGVmZDNiNGZjMDY1NmVlNTg4MzY5MDcwNTA1YzE0MGRlNjhlODgzMmMzZTU0ZjFiNDQ1ZTFhOTFjM2IwNCJ9','eyJpdiI6IjZxMkVWZjBVNWdpUGx0Z013eG9Db1E9PSIsInZhbHVlIjoic1wvaWN3SzFjNXcyc1wvZGIzYUpvVXJ3PT0iLCJtYWMiOiJhZDZhOWI3NzRhZWVlNTNmMmRkODc1YWM4MjU4MDYyYzQwNWNkMzczZTllODBhYTExZWEwMTM5NjEwYjRkYmQ2In0=','eyJpdiI6InFDd3lFaHEwMFwveURuaGlKeTNNM2JRPT0iLCJ2YWx1ZSI6InEzVW1CXC9aOUo3TnF3cm53Mkdka2VRPT0iLCJtYWMiOiI4YTcyZGVhN2YxM2Y0ZTI1ZmY5NGJjNzY2MzhlOTU2ZDE0NTc1MTA1YWVhZmNiYWE1MTZkNTE2ZTNlOTgwMTU0In0=',1);

UNLOCK TABLES;

/*Table structure for table `wings_sauce` */

CREATE TABLE `wings_sauce` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `isfree` tinyint(1) NOT NULL,
  `qualifiers` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'comma delimited list of the names for 1/2,x1,x2 if applicable',
  `isall` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `wings_sauce` */

LOCK TABLES `wings_sauce` WRITE;

insert  into `wings_sauce`(`id`,`name`,`type`,`isfree`,`qualifiers`,`isall`) values
(1,'Mild','Sauce',0,'',1),
(2,'Medium','Sauce',0,'',1),
(3,'Hot','Sauce',0,'',1),
(4,'Suicide','Sauce',0,'',1),
(5,'BBQ','Sauce',0,'',1),
(6,'Honey Garlic','Sauce',0,'',1),
(7,'Cooked','Preparation',1,'Lightly done, Regular, Well done',1);

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
