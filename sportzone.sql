-- Progettazione Web 
DROP DATABASE if exists sportzone; 
CREATE DATABASE sportzone; 
USE sportzone; 
-- MySQL dump 10.13  Distrib 5.7.28, for Win64 (x86_64)
--
-- Host: localhost    Database: sportzone
-- ------------------------------------------------------
-- Server version	5.7.28

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
-- Table structure for table `partecipante`
--

DROP TABLE IF EXISTS `partecipante`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partecipante` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(255) NOT NULL,
  `Vinte` int(11) DEFAULT '0',
  `Pareggiate` int(11) DEFAULT '0',
  `Torneo` varchar(255) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `fk_partecipante_torneo1` (`Torneo`),
  CONSTRAINT `fk_partecipante_torneo1` FOREIGN KEY (`Torneo`) REFERENCES `torneo` (`Nome`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partecipante`
--

LOCK TABLES `partecipante` WRITE;
/*!40000 ALTER TABLE `partecipante` DISABLE KEYS */;
INSERT INTO `partecipante` VALUES (1,'Meccanica',0,0,'Tennis Ing'),(2,'Informatica',2,0,'Tennis Ing'),(3,'Biomedica',1,0,'Tennis Ing'),(4,'Gestionale',1,0,'Tennis Ing'),(5,'Milan',1,1,'Serie A'),(6,'Juve',0,0,'Serie A'),(7,'Roma',0,1,'Serie A'),(8,'Lazio',0,1,'Serie A'),(9,'Inter',0,0,'Serie A'),(10,'Napoli',1,0,'Serie A'),(11,'Lecce',1,0,'Serie A'),(12,'Sassuolo',0,1,'Serie A'),(13,'Riccione',2,0,'Torneo Estivo'),(14,'Rimini',0,0,'Torneo Estivo'),(15,'Gaeta',0,0,'Torneo Estivo'),(16,'Follonica',1,0,'Torneo Estivo'),(17,'Gallipoli',2,0,'Torneo Estivo'),(18,'Lecce',0,0,'Torneo Estivo'),(19,'Palermo',0,0,'Torneo Estivo'),(20,'Capri',1,0,'Torneo Estivo'),(21,'Medicina',2,0,'Torneo Unipi'),(22,'Ingegneria',1,0,'Torneo Unipi'),(23,'Psicologia',1,0,'Torneo Unipi'),(24,'Farmacia',1,0,'Torneo Unipi'),(25,'Fisica',0,0,'Torneo Unipi'),(26,'Economia',1,0,'Torneo Unipi'),(27,'Francia',2,0,'Mondiali'),(28,'Spagna',0,0,'Mondiali'),(29,'Brasile',0,0,'Mondiali'),(30,'Argentina',3,0,'Mondiali'),(31,'Grecia',1,0,'Mondiali'),(32,'Cina',0,0,'Mondiali'),(33,'Marocco',0,0,'Mondiali'),(34,'Serbia',1,0,'Mondiali');
/*!40000 ALTER TABLE `partecipante` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partita`
--

DROP TABLE IF EXISTS `partita`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partita` (
  `Partecipante1` int(11) NOT NULL,
  `Partecipante2` int(11) NOT NULL,
  `Data` datetime DEFAULT NULL,
  `Turno` int(11) NOT NULL,
  `Luogo` varchar(255) DEFAULT NULL,
  `Punti1` int(11) DEFAULT NULL,
  `Punti2` int(11) DEFAULT NULL,
  PRIMARY KEY (`Partecipante1`,`Partecipante2`),
  KEY `fk_partecipante_has_partecipante_partecipante2` (`Partecipante2`),
  CONSTRAINT `fk_partecipante_has_partecipante_partecipante1` FOREIGN KEY (`Partecipante1`) REFERENCES `partecipante` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_partecipante_has_partecipante_partecipante2` FOREIGN KEY (`Partecipante2`) REFERENCES `partecipante` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partita`
--

LOCK TABLES `partita` WRITE;
/*!40000 ALTER TABLE `partita` DISABLE KEYS */;
INSERT INTO `partita` VALUES (1,2,'2023-10-09 18:00:00',1,'Polo A',1,3),(2,1,'2023-10-12 17:30:00',2,'Polo B',3,1),(3,4,'2023-10-09 19:30:00',1,'Polo B',3,2),(4,3,'2023-10-12 21:00:00',2,'Polo A',3,0),(5,6,'2023-09-08 21:00:00',1,'San Siro',3,2),(6,5,'2023-12-02 18:30:00',8,'',NULL,NULL),(6,11,'2023-10-22 15:00:00',2,'',NULL,NULL),(7,8,'2023-09-10 18:00:00',1,'Olimpico',0,0),(7,10,'2023-10-22 18:30:00',2,'Olimpico',NULL,NULL),(8,7,'2023-12-03 21:00:00',8,'Olimpico',NULL,NULL),(8,9,'2023-10-22 18:30:00',2,'Pisa',NULL,NULL),(9,10,'2023-09-09 14:00:00',1,'San Siro',0,1),(10,9,'0000-00-00 00:00:00',14,'Maradona',NULL,NULL),(11,12,'2023-09-11 21:00:00',1,'Lecce',5,1),(12,5,'2023-10-21 20:45:00',2,'Sassuolo',1,1),(13,14,'2023-06-05 14:00:00',2,'Riccione',102,101),(13,16,'2023-06-06 19:00:00',1,'Riccione',85,84),(15,16,'2023-06-05 15:00:00',2,'Gaeta',99,106),(17,18,'2023-06-05 16:00:00',2,'Gallipoli',98,85),(17,20,'2023-06-06 18:00:00',1,'Gallipoli',103,99),(19,20,'2023-06-05 17:00:00',2,'Palermo',102,104),(21,22,'2023-10-02 18:00:00',1,'Porta Nuova',3,2),(21,26,'2023-10-09 16:00:00',2,'Porta Nuova',3,1),(22,23,'2023-10-09 18:00:00',2,'Cus Pisa',3,0),(22,24,'2023-11-04 10:00:00',3,'Polo B',NULL,NULL),(23,24,'2023-10-02 21:00:00',1,'Polo Piagge',3,0),(23,26,'2023-11-04 20:15:00',3,'Cus Pisa',NULL,NULL),(24,25,'2023-10-09 15:00:00',2,'Cus Pisa',3,2),(25,21,'2023-11-04 18:00:00',3,'Fibonacci',NULL,NULL),(25,26,'2023-10-02 20:45:00',1,'Fibonacci',2,3),(27,28,'2023-10-16 21:00:00',2,'Parigi',3,2),(27,30,'2023-10-22 21:00:00',0,'Roma',2,3),(27,34,'2023-10-20 18:00:00',1,'Parigi',2,0),(29,30,'2023-10-16 20:45:00',2,'San Paolo',0,1),(31,30,'2023-10-20 18:00:00',1,'Atene',2,5),(31,32,'2023-10-16 14:00:00',2,'Atene',3,2),(33,34,'2023-10-16 22:00:00',2,'Rabat',2,3);
/*!40000 ALTER TABLE `partita` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `torneo`
--

DROP TABLE IF EXISTS `torneo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `torneo` (
  `Nome` varchar(255) NOT NULL,
  `Sport` varchar(45) NOT NULL,
  `Luogo` varchar(255) NOT NULL,
  `Tipologia` varchar(45) NOT NULL,
  `DataInizio` datetime DEFAULT NULL,
  `DataFine` datetime DEFAULT NULL,
  `Username` varchar(255) NOT NULL,
  PRIMARY KEY (`Nome`),
  KEY `fk_torneo_utente1` (`Username`),
  CONSTRAINT `fk_torneo_utente1` FOREIGN KEY (`Username`) REFERENCES `utente` (`Username`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `torneo`
--

LOCK TABLES `torneo` WRITE;
/*!40000 ALTER TABLE `torneo` DISABLE KEYS */;
INSERT INTO `torneo` VALUES ('Mondiali','Calcio','San Siro','eliminazione1','2023-10-16 00:00:00','2023-11-22 00:00:00','MarioRossi'),('Serie A','Calcio','Italia','girone2','2023-09-01 00:00:00','2024-05-27 00:00:00','MarioRossi'),('Tennis Ing','Tennis','Polo A','eliminazione2','2023-10-09 00:00:00','2023-10-22 00:00:00','MarioRossi'),('Torneo Estivo','Basket','Italia','eliminazione1','2023-06-05 00:00:00','2023-07-05 00:00:00','MattiaVerdi'),('Torneo Unipi','Pallavolo','Cus Pisa','girone1','2023-10-02 00:00:00','2023-11-05 00:00:00','MattiaVerdi');
/*!40000 ALTER TABLE `torneo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utente`
--

DROP TABLE IF EXISTS `utente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `utente` (
  `Username` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(60) NOT NULL,
  PRIMARY KEY (`Username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utente`
--

LOCK TABLES `utente` WRITE;
/*!40000 ALTER TABLE `utente` DISABLE KEYS */;
INSERT INTO `utente` VALUES ('MarioRossi','rossi@gmail.com','$2y$10$7pBeTSQwg6xolw4MCcO5JONxkWBAtJE2I8QjIjMBbcdSCs6p1H/JG'),('MattiaVerdi','verdi@gmail.com','$2y$10$6D5Z/mgtfPUsx6F3pU/KcOeoWnVhte0wDIMVE6jxR27d7ngxYrhbW');
/*!40000 ALTER TABLE `utente` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-10-30  3:31:38
