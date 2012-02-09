/*
SQLyog Ultimate v8.71 
MySQL - 5.5.8 : Database - facemash
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`facemash` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `facemash`;

/*Table structure for table `facemash` */

DROP TABLE IF EXISTS `facemash`;

CREATE TABLE `facemash` (
  `id` varchar(32) NOT NULL,
  `SF` varchar(32) DEFAULT NULL,
  `EX` float DEFAULT NULL,
  `RX` int(11) DEFAULT NULL,
  `ST` int(11) DEFAULT NULL,
  `TS` int(11) DEFAULT NULL,
  `IA` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `facemash` */

LOCK TABLES `facemash` WRITE;

UNLOCK TABLES;

/*Table structure for table `fmlog` */

DROP TABLE IF EXISTS `fmlog`;

CREATE TABLE `fmlog` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id` varchar(32) DEFAULT NULL,
  `result` varchar(16) DEFAULT NULL,
  `RX` int(11) DEFAULT NULL,
  `EX` float DEFAULT NULL,
  `IP` varchar(16) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7862 DEFAULT CHARSET=utf8;

/*Data for the table `fmlog` */

LOCK TABLES `fmlog` WRITE;

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
