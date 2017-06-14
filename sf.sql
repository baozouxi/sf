-- MySQL dump 10.13  Distrib 5.1.65, for Win64 (unknown)
--
-- Host: localhost    Database: sfwhlryy
-- ------------------------------------------------------
-- Server version	5.1.65-community

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
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(12) NOT NULL DEFAULT '',
  `password` char(20) NOT NULL DEFAULT '',
  `name` varchar(12) NOT NULL,
  `tel` varchar(11) NOT NULL,
  `last_login_ip` char(15) NOT NULL DEFAULT '',
  `last_login_time` datetime NOT NULL,
  `admin_part` tinyint(4) NOT NULL DEFAULT '0',
  `is_use` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `tel` (`tel`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (2,'123456','9520943dc26494f8941b','sx','13228595558','110.184.56.20','2017-06-14 15:13:02',2,1);
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctor`
--

DROP TABLE IF EXISTS `doctor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doctor` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `sex` varchar(6) NOT NULL DEFAULT '男',
  `age` tinyint(4) NOT NULL,
  `tel` varchar(11) NOT NULL,
  `summary` varchar(255) NOT NULL DEFAULT '',
  `hospital_id` int(10) unsigned NOT NULL,
  `is_use` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `tel` (`tel`),
  UNIQUE KEY `name` (`name`,`tel`,`hospital_id`),
  KEY `doctor_name` (`name`(10)),
  KEY `hospital_id` (`hospital_id`),
  CONSTRAINT `doctor_ibfk_1` FOREIGN KEY (`hospital_id`) REFERENCES `hospital` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctor`
--

LOCK TABLES `doctor` WRITE;
/*!40000 ALTER TABLE `doctor` DISABLE KEYS */;
/*!40000 ALTER TABLE `doctor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hospital`
--

DROP TABLE IF EXISTS `hospital`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hospital` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `intro` text,
  `add_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `add_admin` varchar(30) NOT NULL,
  `is_use` tinyint(4) NOT NULL DEFAULT '1',
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hospital`
--

LOCK TABLES `hospital` WRITE;
/*!40000 ALTER TABLE `hospital` DISABLE KEYS */;
INSERT INTO `hospital` VALUES (17,'万年医院','','2017-06-14 06:01:27','sx',1,0);
/*!40000 ALTER TABLE `hospital` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hospital_admin`
--

DROP TABLE IF EXISTS `hospital_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hospital_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `pwd` char(20) NOT NULL,
  `tel` varchar(30) NOT NULL,
  `hospital_id` int(10) unsigned NOT NULL,
  `add_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_use` tinyint(4) NOT NULL DEFAULT '1',
  `permission` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `last_login_ip` varchar(15) DEFAULT NULL,
  `last_login_date` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `tel` (`tel`),
  KEY `hospital_id` (`hospital_id`),
  KEY `name_index` (`name`(10)),
  CONSTRAINT `hospital_admin_ibfk_1` FOREIGN KEY (`hospital_id`) REFERENCES `hospital` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hospital_admin`
--

LOCK TABLES `hospital_admin` WRITE;
/*!40000 ALTER TABLE `hospital_admin` DISABLE KEYS */;
/*!40000 ALTER TABLE `hospital_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `illness`
--

DROP TABLE IF EXISTS `illness`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `illness` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `illness_name` varchar(255) NOT NULL,
  `hospital_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `hospitalId` (`hospital_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `illness`
--

LOCK TABLES `illness` WRITE;
/*!40000 ALTER TABLE `illness` DISABLE KEYS */;
/*!40000 ALTER TABLE `illness` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patient`
--

DROP TABLE IF EXISTS `patient`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `sex` varchar(6) NOT NULL,
  `age` int(3) NOT NULL DEFAULT '0',
  `tel` varchar(20) NOT NULL,
  `hospital_id` int(10) unsigned NOT NULL,
  `hospital_date` date NOT NULL,
  `illness` varchar(255) NOT NULL,
  `doctor_name` varchar(20) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `tel` (`tel`),
  UNIQUE KEY `name` (`name`,`tel`,`hospital_id`),
  KEY `hospital_id` (`hospital_id`),
  KEY `name_index` (`name`(10)),
  CONSTRAINT `patient_ibfk_1` FOREIGN KEY (`hospital_id`) REFERENCES `hospital` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patient`
--

LOCK TABLES `patient` WRITE;
/*!40000 ALTER TABLE `patient` DISABLE KEYS */;
/*!40000 ALTER TABLE `patient` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proportion`
--

DROP TABLE IF EXISTS `proportion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proportion` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` int(10) unsigned NOT NULL,
  `percent` float(7,6) NOT NULL DEFAULT '0.000000',
  `begin_date` date NOT NULL,
  `end_date` date NOT NULL,
  `add_admin` varchar(12) NOT NULL,
  `is_use` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `hospital_id` (`hospital_id`),
  KEY `add_admin` (`add_admin`),
  CONSTRAINT `proportion_ibfk_1` FOREIGN KEY (`hospital_id`) REFERENCES `hospital` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `proportion_ibfk_2` FOREIGN KEY (`add_admin`) REFERENCES `admin` (`name`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proportion`
--

LOCK TABLES `proportion` WRITE;
/*!40000 ALTER TABLE `proportion` DISABLE KEYS */;
INSERT INTO `proportion` VALUES (67,17,0.300000,'2017-06-14','0000-00-00','sx',1);
/*!40000 ALTER TABLE `proportion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rechange`
--

DROP TABLE IF EXISTS `rechange`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rechange` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` int(10) unsigned NOT NULL,
  `money` int(10) unsigned NOT NULL DEFAULT '0',
  `add_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `add_admin` varchar(12) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `hospital_id` (`hospital_id`),
  KEY `add_admin` (`add_admin`),
  CONSTRAINT `rechange_ibfk_1` FOREIGN KEY (`hospital_id`) REFERENCES `hospital` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `rechange_ibfk_2` FOREIGN KEY (`add_admin`) REFERENCES `admin` (`name`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rechange`
--

LOCK TABLES `rechange` WRITE;
/*!40000 ALTER TABLE `rechange` DISABLE KEYS */;
INSERT INTO `rechange` VALUES (21,17,20000,'2017-06-14 06:01:27','sx');
/*!40000 ALTER TABLE `rechange` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `spending`
--

DROP TABLE IF EXISTS `spending`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `spending` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `spending_date` date NOT NULL,
  `cost_money` int(10) unsigned NOT NULL DEFAULT '0',
  `hospital_id` int(10) unsigned NOT NULL,
  `patient_tel` varchar(20) NOT NULL,
  `patient_name` varchar(30) NOT NULL,
  `summary` varchar(255) NOT NULL DEFAULT '',
  `remark` text,
  `percentId` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `spending_date` (`spending_date`,`hospital_id`,`patient_name`,`patient_tel`),
  KEY `name` (`patient_name`(10)),
  KEY `spending_date1` (`spending_date`),
  KEY `patient_tel` (`patient_tel`),
  KEY `hospital_id` (`hospital_id`),
  KEY `patient_name` (`patient_name`),
  KEY `percentId` (`percentId`),
  CONSTRAINT `spending_ibfk_1` FOREIGN KEY (`patient_tel`) REFERENCES `patient` (`tel`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `spending_ibfk_2` FOREIGN KEY (`hospital_id`) REFERENCES `hospital` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `spending_ibfk_3` FOREIGN KEY (`patient_name`) REFERENCES `patient` (`name`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `spending_ibfk_4` FOREIGN KEY (`percentId`) REFERENCES `proportion` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4787 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `spending`
--

LOCK TABLES `spending` WRITE;
/*!40000 ALTER TABLE `spending` DISABLE KEYS */;
/*!40000 ALTER TABLE `spending` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `verify_recharge`
--

DROP TABLE IF EXISTS `verify_recharge`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `verify_recharge` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` int(10) unsigned NOT NULL,
  `money` int(10) unsigned NOT NULL DEFAULT '0',
  `add_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `add_admin` varchar(12) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `hospital_id` (`hospital_id`),
  KEY `add_admin` (`add_admin`),
  CONSTRAINT `verify_recharge_ibfk_1` FOREIGN KEY (`hospital_id`) REFERENCES `hospital` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `verify_recharge_ibfk_2` FOREIGN KEY (`add_admin`) REFERENCES `admin` (`name`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `verify_recharge`
--

LOCK TABLES `verify_recharge` WRITE;
/*!40000 ALTER TABLE `verify_recharge` DISABLE KEYS */;
INSERT INTO `verify_recharge` VALUES (17,17,10,'2017-06-14 06:01:27','sx');
/*!40000 ALTER TABLE `verify_recharge` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `verify_record`
--

DROP TABLE IF EXISTS `verify_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `verify_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `hospital_id` int(10) unsigned NOT NULL,
  `login_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `login_ip` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `hospital_id` (`hospital_id`),
  KEY `name` (`name`),
  CONSTRAINT `verify_record_ibfk_1` FOREIGN KEY (`hospital_id`) REFERENCES `hospital` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `verify_record_ibfk_2` FOREIGN KEY (`name`) REFERENCES `hospital_admin` (`name`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `verify_record`
--

LOCK TABLES `verify_record` WRITE;
/*!40000 ALTER TABLE `verify_record` DISABLE KEYS */;
/*!40000 ALTER TABLE `verify_record` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-06-14 15:40:56
