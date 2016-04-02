-- MySQL dump 10.13  Distrib 5.7.11-4, for Linux (x86_64)
--
-- Host: localhost    Database: parser
-- ------------------------------------------------------
-- Server version	5.7.11-4-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `currency_relation`
--

DROP TABLE IF EXISTS `currency_relation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currency_relation` (
  `relation_id` int(12) NOT NULL AUTO_INCREMENT,
  `our_value` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `user_value` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `user_id` int(12) DEFAULT NULL,
  `default` tinyint(2) DEFAULT NULL,
  PRIMARY KEY (`relation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currency_relation`
--

LOCK TABLES `currency_relation` WRITE;
/*!40000 ALTER TABLE `currency_relation` DISABLE KEYS */;
INSERT INTO `currency_relation` VALUES (1,'1','USD',2,1),(2,'2','RUB',2,0),(3,'3','UAH',2,0),(4,'1','USD',1,1),(5,'3','UAH',1,0),(6,'2','RUB',1,0);
/*!40000 ALTER TABLE `currency_relation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `user_id` int(12) DEFAULT NULL,
  `state` int(12) DEFAULT NULL,
  `order_payment` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency_id` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `data` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `merchant_id` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `relation`
--

DROP TABLE IF EXISTS `relation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `relation` (
  `our_product_id` int(25) NOT NULL,
  `user_product_id` varchar(70) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(25) DEFAULT NULL,
  PRIMARY KEY (`our_product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `relation`
--

LOCK TABLES `relation` WRITE;
/*!40000 ALTER TABLE `relation` DISABLE KEYS */;
/*!40000 ALTER TABLE `relation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `state_relation`
--

DROP TABLE IF EXISTS `state_relation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `state_relation` (
  `relation_id` int(12) NOT NULL AUTO_INCREMENT,
  `user_value` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `our_value` int(3) DEFAULT NULL,
  `user_id` tinyint(3) DEFAULT NULL,
  `default` tinyint(2) DEFAULT NULL,
  PRIMARY KEY (`relation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `state_relation`
--

LOCK TABLES `state_relation` WRITE;
/*!40000 ALTER TABLE `state_relation` DISABLE KEYS */;
INSERT INTO `state_relation` VALUES (1,'declined',3,1,NULL),(2,'declined',3,2,NULL),(3,'pending',2,1,NULL),(4,'pending',2,2,NULL),(5,'approved',4,1,NULL),(6,'approved',4,2,NULL),(7,'unnoun',1,1,1),(8,'unnoun',1,2,1);
/*!40000 ALTER TABLE `state_relation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(25) NOT NULL,
  `update_interval` int(25) DEFAULT NULL COMMENT 'Tyme Interval in minutes',
  `last_parse_time` datetime DEFAULT NULL,
  `url_for_parse` varchar(400) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,10,'2016-04-03 00:12:22','http://parser.dev/web/uploads/csv.csv','eBay'),(2,5,'2016-04-03 00:23:07','http://parser.dev/web/uploads/xml.xml','XMLTest');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `values_relation`
--

DROP TABLE IF EXISTS `values_relation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `values_relation` (
  `relation_id` int(25) NOT NULL AUTO_INCREMENT,
  `our_value` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_value` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(12) DEFAULT NULL,
  PRIMARY KEY (`relation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `values_relation`
--

LOCK TABLES `values_relation` WRITE;
/*!40000 ALTER TABLE `values_relation` DISABLE KEYS */;
INSERT INTO `values_relation` VALUES (1,'id','eBay Item ID',1),(2,'state','status',2),(3,'order_payment','payment',2),(4,'id','action_id',2),(5,'currency_id','currency',2),(6,'order_payment','eBay Total Sale Amount',1),(7,'currency_id','currency',1),(8,'state','status',1),(9,'merchant_id','advcampaign_id',2),(10,'merchant_id','Item Site ID',1);
/*!40000 ALTER TABLE `values_relation` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-04-03  0:51:52
