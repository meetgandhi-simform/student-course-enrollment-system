-- MySQL dump 10.13  Distrib 8.0.45, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: enrollment_db
-- ------------------------------------------------------
-- Server version	8.4.8

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `course_instructor`
--

DROP TABLE IF EXISTS `course_instructor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `course_instructor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `instructor_id` int NOT NULL,
  `course_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_instructor_course` (`instructor_id`,`course_id`),
  KEY `fk_ci_course` (`course_id`),
  CONSTRAINT `fk_ci_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ci_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course_instructor`
--

LOCK TABLES `course_instructor` WRITE;
/*!40000 ALTER TABLE `course_instructor` DISABLE KEYS */;
INSERT INTO `course_instructor` VALUES (1,14,1),(2,16,2),(3,78,3);
/*!40000 ALTER TABLE `course_instructor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `courses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `course_name` varchar(255) DEFAULT NULL,
  `duration_weeks` int DEFAULT NULL,
  `max_seats` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courses`
--

LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
INSERT INTO `courses` VALUES (1,'PHP',5,100),(2,'Java Script',5,100),(3,'JQuery',6,80);
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `enrollments`
--

DROP TABLE IF EXISTS `enrollments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `enrollments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `course_instructor_id` int NOT NULL,
  `enrolled_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Enrolled','Cancelled','Completed') DEFAULT 'Enrolled',
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_id` (`student_id`,`course_instructor_id`),
  KEY `course_instructor_id` (`course_instructor_id`),
  CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`course_instructor_id`) REFERENCES `course_instructor` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enrollments`
--

LOCK TABLES `enrollments` WRITE;
/*!40000 ALTER TABLE `enrollments` DISABLE KEYS */;
/*!40000 ALTER TABLE `enrollments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` bigint NOT NULL,
  `role` enum('Student','Instructor','Admin') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `isActive` enum('Active','Inactive') DEFAULT 'Active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Rohan','rohan.rohit@example.com','$2y$10$eBZoHdBSjsrMnKEduczWru.ZJt27e4eXMYpCLsWqCn/et0tWzLXQK',123456789,'Student','2026-04-13 10:20:57','Active'),(4,'Abhi','abhi.andani@example.com','$2y$10$a0eoFHTpcNLzov3Xy76aAub05H/j.r8o8cJ3kiKTEwvmhy6vhlS4.',1234567890,'Student','2026-04-13 10:25:36','Active'),(5,'admin','admin@example.com','$2y$10$eBZoHdBSjsrMnKEduczWru.ZJt27e4eXMYpCLsWqCn/et0tWzLXQK',6351398001,'Admin','2026-04-13 10:54:42','Active'),(6,'Dhruv','dhruv.rana@example.com','$2y$10$DrvCsbhGINktakDOZRoJNeYx4XBg3/.p4ebM22A.ajGZWtZ0sZl46',1230987456,'Student','2026-04-13 11:50:01','Active'),(8,'Ankush','ankush.dubey@example.com','$2y$10$yNxzgM6gojyTMtJw.BjYy.3tFphvA45ua6UhShN3aBGSsG6LrFvDy',1230984567,'Student','2026-04-13 11:51:38','Active'),(12,'Alice','alice.bob@example.com','$2y$10$KJG.YyxuNX.mC.edGDitouNvjCCIYjele2FHfoKkoq.4s4CFsVRI6',1230984576,'Student','2026-04-14 08:48:02','Active'),(14,'Shivang','shivang.rathod@example.com','$2y$10$8vZSwf79k2SGhyM1I5k8Veb7jusHLAUBV9o7gpXmJUaZ1XPV0cpmi',1230987456,'Instructor','2026-04-14 09:16:00','Active'),(16,'Denish','denish.bhimani@example.com','$2y$10$zKFI4UDyPCVAezkF2i7HTuOzVFPdBrQIo0oy0yVHdiiBOd8T81H8G',981237465,'Instructor','2026-04-14 10:21:20','Active'),(17,'Bob','bob.alice@example.com','$2y$10$ilili9sq6ORvJc279Yezw..7l4zBmHj/JfkyDrQDZW4ofHhgnMUoe',1230948576,'Student','2026-04-14 12:40:56','Active'),(23,'Raj','raj.patel@example.com','$2y$10$y2v6nIiKQim9mdRPaPEQweQ4OVDukRb9tosBlec8MDo4XuoIOtsl2',987615432,'Student','2026-04-15 09:59:47','Active'),(24,'Raju','raju.patel@example.com','$2y$12$Qn1f0riwRnzIZKZaX4rUXeaw7Ti4a2nO2nXP8rgp.e8BuOgbw.9QS',981237645,'Student','2026-04-15 11:59:13','Active'),(25,'Zampa','adam.zampa@example.com','$2y$10$0zWw/9NhqBbd3g0XXrglWOMs35GKtoffVc6gdbsmqDHTe8..bLVRG',987651234,'Student','2026-04-15 12:19:30','Active'),(26,'Starc','maitchel.starc@example.com','$2y$10$1EV.WvFMEQdU0qqLsxdByOzajVdQkcalIlPG1s9sXkiP7zRQKJBLi',981237645,'Student','2026-04-15 12:33:34','Active'),(27,'Rohan Mehta','rohan.mehta@example.com','$2y$12$oa5epl/lFFORt9ah/r.r1.UAPWMmg6RmE30RR201hhG7/nQbEi6bq',9876543210,'Student','2026-04-16 07:08:27','Active'),(28,'Sam','sam@example.com','$2y$12$M3k.OEcmyVjhFLSaB03h1e7DDWmn95Tg4MJ4EhF0fLSmk8SOH8b5C',9123456781,'Admin','2026-04-16 08:29:32','Active'),(78,'Divyang','divyang@example.com','$2y$12$45ruH18q3hXsVUX3ELlI8OmCuyQsX9jhHmXnY1gN1cxMP5BRb1v5m',6351398110,'Instructor','2026-04-17 05:34:39','Active');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-21 14:51:51
