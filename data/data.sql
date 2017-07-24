-- MySQL dump 10.13  Distrib 5.7.18, for Linux (x86_64)
--
-- Host: localhost    Database: challenge
-- ------------------------------------------------------
-- Server version	5.7.18-0ubuntu0.16.04.1

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
-- Table structure for table `game_session`
--

DROP TABLE IF EXISTS `game_session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game_session` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `personId` int(11) NOT NULL,
  `stepIdInQuest` int(11) NOT NULL,
  `data` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `session_uuid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastUpdated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_4586AAFBA20C4B1C` (`personId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game_session`
--

LOCK TABLES `game_session` WRITE;
/*!40000 ALTER TABLE `game_session` DISABLE KEYS */;
/*!40000 ALTER TABLE `game_session` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `person`
--

DROP TABLE IF EXISTS `person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `uuid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `person`
--

LOCK TABLES `person` WRITE;
/*!40000 ALTER TABLE `person` DISABLE KEYS */;
INSERT INTO `person` VALUES (1,'MyMagicNickname','Researcher','Researcher');
/*!40000 ALTER TABLE `person` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `person_on_map`
--

DROP TABLE IF EXISTS `person_on_map`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person_on_map` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `personId` int(11) NOT NULL,
  `stepId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_D4589EBA20C4B1C` (`personId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `person_on_map`
--

LOCK TABLES `person_on_map` WRITE;
/*!40000 ALTER TABLE `person_on_map` DISABLE KEYS */;
/*!40000 ALTER TABLE `person_on_map` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quest_steps`
--

DROP TABLE IF EXISTS `quest_steps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quest_steps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stepId` int(11) NOT NULL,
  `text` longtext COLLATE utf8_unicode_ci NOT NULL,
  `nextStepId` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `formDataCollection` longtext COLLATE utf8_unicode_ci NOT NULL,
  `defaultPreviousStepIdForForm` int(11) NOT NULL,
  `nodeType` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_679F93EE34BA6AF` (`stepId`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quest_steps`
--

LOCK TABLES `quest_steps` WRITE;
/*!40000 ALTER TABLE `quest_steps` DISABLE KEYS */;
INSERT INTO `quest_steps` VALUES (1,1,'You find yourself behind mountain','2','',0,'normal'),(2,2,'You come closer to the mountain and see the villiage','3','',0,'normal'),(3,3,'The major of the village comes to you. And you start to talk with mayor: ','4','',0,'normal'),(4,4,'And the major said : \"Welcome in our village. We have the great people here. But, Sir our village was attacked by wolfs. Please come to smith to get weapon. 3 Swords\"','5','',0,'normal'),(5,5,'You say : 1) Yes, I will help you 2) No, thanks','6','',0,'normal'),(6,6,'','0','[{\"value_from_previous_form_node\": 1, \"stepIdFromWhichWasTransition\": 5, \"parametersFromTheForm\":[], \"handlersListForThisTransition\":[], \"jump_to_next_node\": 9, \"error_message\": \"Not all parameters specified\"},{\"value_from_previous_form_node\": 2, \"stepIdFromWhichWasTransition\": 5, \"parametersFromTheForm\":[], \"handlersListForThisTransition\":[\"DecreaseHealth\"], \"jump_to_next_node\": 7, \"error_message\": \"Not all parameters specified\"}]',5,'routing_processing'),(7,7,'The mayor get upset, And you should temporary return to your Experimental Laboratory cause to go ahead you should pass this village','1','',0,'normal'),(8,8,'You start to go left till the house of smith. You knocked the door. KNOCK- KNOCK. And soone opened it. You realized that this guy is smith. And then you come cloer to smith and told him: \"Hey , I need 3 swords\"','9','',0,'normal'),(9,9,'And smith told: \"Ok, but I need 3 bucket of water. Could you bring me ?\". Your choice : \"1) Bring 3 buckets of water, or 2) gain your power with trainings to get power to bring this 3 buckets\"','','',0,'normal');
/*!40000 ALTER TABLE `quest_steps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `health` int(11) NOT NULL,
  `strength` int(11) NOT NULL,
  `experience` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_57698A6A5E237E06` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` VALUES (3,'Researcher',100,50,50),(4,'Wizard',100,50,50);
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `skills`
--

DROP TABLE IF EXISTS `skills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `skills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `health` int(11) NOT NULL,
  `strength` int(11) NOT NULL,
  `experience` int(11) NOT NULL,
  `defence` int(11) NOT NULL,
  `agility` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `personId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_D5311670A20C4B1C` (`personId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `skills`
--

LOCK TABLES `skills` WRITE;
/*!40000 ALTER TABLE `skills` DISABLE KEYS */;
/*!40000 ALTER TABLE `skills` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-07-21 21:01:54
