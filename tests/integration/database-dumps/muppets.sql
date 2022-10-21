-- MySQL dump 10.13  Distrib 8.0.30, for Linux (x86_64)
--
-- Host: localhost    Database: muppets
-- ------------------------------------------------------
-- Server version	8.0.30-0ubuntu0.20.04.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `characters`
--

DROP TABLE IF EXISTS `characters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `characters` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `animal` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Characters in the Jim Henson universes';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `characters`
--

LOCK TABLES `characters` WRITE;
/*!40000 ALTER TABLE `characters` DISABLE KEYS */;
INSERT INTO `characters` VALUES (1,'Kermit','Frog'),(2,'Miss','Piggy'),(3,'Ralph','Dog'),(4,'Barkley','Dog'),(5,'Oscar','Grouch'),(6,'Elmo','Monster'),(7,'Grover','Monster'),(8,'Cookie','Monster'),(9,'Gonzo','Weirdo'),(10,'Camilla','Chicken'),(11,'Statler','Human'),(12,'Waldorf','Human'),(13,'Count','Human'),(14,'Big Bird','Bird'),(15,'Snuffy','Snuffleupagus'),(16,'Red','Fraggle'),(17,'Gobo','Fraggle'),(18,'Wembly','Fraggle'),(19,'Sprocket','Dog');
/*!40000 ALTER TABLE `characters` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `remove_character` BEFORE DELETE ON `characters` FOR EACH ROW BEGIN
    DELETE FROM characters_in_shows WHERE character_id = OLD.id;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `characters_in_shows`
--

DROP TABLE IF EXISTS `characters_in_shows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `characters_in_shows` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `show_id` int unsigned DEFAULT NULL,
  `character_id` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `shows_to_characters_in_shows` (`show_id`),
  KEY `characters_to_characters_in_shows` (`character_id`),
  CONSTRAINT `characters_to_characters_in_shows` FOREIGN KEY (`character_id`) REFERENCES `characters` (`id`),
  CONSTRAINT `shows_to_characters_in_shows` FOREIGN KEY (`show_id`) REFERENCES `shows` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Association table between characters and shows.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `characters_in_shows`
--

LOCK TABLES `characters_in_shows` WRITE;
/*!40000 ALTER TABLE `characters_in_shows` DISABLE KEYS */;
INSERT INTO `characters_in_shows` VALUES (1,1,1),(2,1,2),(3,1,3),(4,1,9),(5,1,10),(6,1,12),(7,1,13),(8,2,1),(9,2,3),(10,2,9),(11,3,1),(12,3,4),(13,3,5),(14,3,6),(15,3,7),(16,3,8),(17,3,13),(18,3,14),(19,3,15),(20,4,16),(21,4,17),(22,4,18),(23,4,19);
/*!40000 ALTER TABLE `characters_in_shows` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `multi_show_characters`
--

DROP TABLE IF EXISTS `multi_show_characters`;
/*!50001 DROP VIEW IF EXISTS `multi_show_characters`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `multi_show_characters` AS SELECT 
 1 AS `name`,
 1 AS `COUNT(DISTINCT show_id)`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `shows`
--

DROP TABLE IF EXISTS `shows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shows` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `format` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Shows produced with Henson characters';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shows`
--

LOCK TABLES `shows` WRITE;
/*!40000 ALTER TABLE `shows` DISABLE KEYS */;
INSERT INTO `shows` VALUES (1,'The Muppet Show','Live'),(2,'Muppet Babies','Animated'),(3,'Seasame Street','Live'),(4,'Fraggle Rock','Live');
/*!40000 ALTER TABLE `shows` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Final view structure for view `multi_show_characters`
--

/*!50001 DROP VIEW IF EXISTS `multi_show_characters`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `multi_show_characters` AS select `characters`.`name` AS `name`,count(distinct `characters_in_shows`.`show_id`) AS `COUNT(DISTINCT show_id)` from (`characters` left join `characters_in_shows` on((`characters`.`id` = `characters_in_shows`.`character_id`))) group by `characters`.`name` having (count(0) > 1) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-10-20 16:47:08
