-- MySQL dump 10.13  Distrib 5.7.27, for Linux (x86_64)
--
-- Host: localhost    Database: 
-- ------------------------------------------------------
-- Server version	5.7.27

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
-- Current Database: `mailerlite`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `mailerlite` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `mailerlite`;

--
-- Table structure for table `field_types`
--

DROP TABLE IF EXISTS `field_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `field_types` (
  `id` char(36) COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:uuid)',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `input_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `validators` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_72971FCB5E237E06` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `field_types`
--

LOCK TABLES `field_types` WRITE;
/*!40000 ALTER TABLE `field_types` DISABLE KEYS */;
INSERT INTO `field_types`
VALUES
('97466350-77bf-45b3-8c0b-d61b876f778b','Boolean', 'checkbox', 'in:0,1'),
('591aa34c-d1c0-41d6-99e4-85b6be44f90f','Date', 'date', 'date:Y-m-d'),
('21af03fe-b044-4067-bb22-9feae069cb0e','Number', 'number', 'alpha_num'),
('1f3f5943-0e39-435e-9ff6-9507f7778852','String', 'text', 'max:255'),
('fee48e3d-ad51-439e-b17d-795f203bde79','Email', 'email', 'max:255|email|emailHost');
/*!40000 ALTER TABLE `field_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fields`
--

DROP TABLE IF EXISTS `fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fields` (
  `id` char(36) COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:uuid)',
  `field_type_id` char(36) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(DC2Type:uuid)',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `variable` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `required` BOOLEAN,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_7EE5E3882B36786B` (`title`),
  KEY `IDX_7EE5E3882B68A933` (`field_type_id`),
  CONSTRAINT `FK_7EE5E3882B68A933` FOREIGN KEY (`field_type_id`) REFERENCES `field_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fields`
--

LOCK TABLES `fields` WRITE;
/*!40000 ALTER TABLE `fields` DISABLE KEYS */;
INSERT INTO `fields`
VALUES
('314f9809-6392-4a50-af68-b9ebd2126b0f','591aa34c-d1c0-41d6-99e4-85b6be44f90f','Date of Birth','dob', false),
('a802af7e-2308-4510-be2a-14e2cf981998','fee48e3d-ad51-439e-b17d-795f203bde79','Email','email', true),
('b3ea95f4-b734-421b-a86f-b6dfb02f67e4','1f3f5943-0e39-435e-9ff6-9507f7778852','Last Name','lastName', false),
('bd8e394f-9e6d-402a-886d-1b241c7bf3d5','1f3f5943-0e39-435e-9ff6-9507f7778852','First Name','firstName', true);
/*!40000 ALTER TABLE `fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscriber_fields`
--

DROP TABLE IF EXISTS `subscriber_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscriber_fields` (
  `id` char(36) COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:uuid)',
  `field_id` char(36) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(DC2Type:uuid)',
  `subscriber_id` char(36) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(DC2Type:uuid)',
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subscriber_field_unique_idx` (`field_id`,`subscriber_id`),
  KEY `IDX_40B034D1443707B0` (`field_id`),
  KEY `IDX_40B034D17808B1AD` (`subscriber_id`),
  CONSTRAINT `FK_40B034D1443707B0` FOREIGN KEY (`field_id`) REFERENCES `fields` (`id`),
  CONSTRAINT `FK_40B034D17808B1AD` FOREIGN KEY (`subscriber_id`) REFERENCES `subscribers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `subscriber_groups`
--

DROP TABLE IF EXISTS `subscriber_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscriber_groups` (
  `id` char(36) COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:uuid)',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscriber_groups`
--

LOCK TABLES `subscriber_groups` WRITE;
/*!40000 ALTER TABLE `subscriber_groups` DISABLE KEYS */;
INSERT INTO `subscriber_groups` VALUES ('4f0dbfb1-3a1e-4e9e-b363-edceae3133ea','Default Group');
/*!40000 ALTER TABLE `subscriber_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscriber_states`
--

DROP TABLE IF EXISTS `subscriber_states`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscriber_states` (
  `id` char(36) COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:uuid)',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscriber_states`
--

LOCK TABLES `subscriber_states` WRITE;
/*!40000 ALTER TABLE `subscriber_states` DISABLE KEYS */;
INSERT INTO `subscriber_states` VALUES ('54a4452e-bd3a-4a92-b800-248170277b72','Unsubscribed'),('5c4cac3d-2f7b-4d95-9cb1-38d4c617b737','Bounced'),('8393b03a-26a7-4586-9fdb-23b085fc2c49','Unconfirmed'),('8e2cfaba-3546-4990-bca4-416678ea3508','Junk'),('d2b3ee19-e9d4-40e1-9dbe-1b6c07300540','Active');
/*!40000 ALTER TABLE `subscriber_states` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscribers`
--

DROP TABLE IF EXISTS `subscribers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscribers` (
  `id` char(36) COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:uuid)',
  `subscriber_group_id` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '(DC2Type:uuid)',
  `subscriber_state_id` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '(DC2Type:uuid)',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2FCD16AC8D9A4678` (`subscriber_group_id`),
  KEY `IDX_2FCD16AC7616A3FE` (`subscriber_state_id`),
  CONSTRAINT `FK_2FCD16AC7616A3FE` FOREIGN KEY (`subscriber_state_id`) REFERENCES `subscriber_states` (`id`),
  CONSTRAINT `FK_2FCD16AC8D9A4678` FOREIGN KEY (`subscriber_group_id`) REFERENCES `subscriber_groups` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-10-13 20:06:27
