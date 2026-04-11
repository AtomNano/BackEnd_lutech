-- MySQL dump 10.13  Distrib 8.0.45, for Linux (x86_64)
--
-- Host: localhost    Database: backendlutech
-- ------------------------------------------------------
-- Server version	8.0.45

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
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `whatsapp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES ('019d7be8-03da-73af-b694-053f9bc1edcc','Dr. Jamarcus Jones',NULL,'2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-03ea-7352-951c-f646ceaf3caf','Ms. Reba Barton',NULL,'2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-041f-7309-9ed6-53d66feda77a','Dr. Abelardo Grady',NULL,'2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-042f-7048-ae69-088677f9f654','Blanche Cronin PhD',NULL,'2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-043f-73d1-bc18-686a5aae0a8c','Ms. Cierra Mayer IV',NULL,'2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-044d-734e-aa2d-e520a360a650','Eryn Blanda',NULL,'2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-045d-72fc-9109-899f532f28fc','Miss Marquise Rippin DDS',NULL,'2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-0470-70fa-946c-33780eb9e9fd','Marisa Bauch Sr.',NULL,'2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-047f-71dd-820c-1dcc300f71b1','Tianna Kassulke',NULL,'2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-048e-730b-9000-bbe232468648','Dahlia McKenzie V',NULL,'2026-04-11 09:38:16','2026-04-11 09:38:16',NULL);
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `finance_accounts`
--

DROP TABLE IF EXISTS `finance_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `finance_accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `workspace_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('bank','ewallet','cash') COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `finance_accounts_workspace_id_foreign` (`workspace_id`),
  CONSTRAINT `finance_accounts_workspace_id_foreign` FOREIGN KEY (`workspace_id`) REFERENCES `workspaces` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `finance_accounts`
--

LOCK TABLES `finance_accounts` WRITE;
/*!40000 ALTER TABLE `finance_accounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `finance_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `finance_categories`
--

DROP TABLE IF EXISTS `finance_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `finance_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `workspace_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('income','expense') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `finance_categories_workspace_id_foreign` (`workspace_id`),
  CONSTRAINT `finance_categories_workspace_id_foreign` FOREIGN KEY (`workspace_id`) REFERENCES `workspaces` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `finance_categories`
--

LOCK TABLES `finance_categories` WRITE;
/*!40000 ALTER TABLE `finance_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `finance_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `finances`
--

DROP TABLE IF EXISTS `finances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `finances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `workspace_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `type` enum('income','expense') COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `source` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'web',
  `ai_metadata` text COLLATE utf8mb4_unicode_ci,
  `attachment_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `finance_account_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `finances_user_id_foreign` (`user_id`),
  KEY `finances_workspace_id_transaction_date_index` (`workspace_id`,`transaction_date`),
  KEY `finances_type_index` (`type`),
  KEY `finances_finance_account_id_foreign` (`finance_account_id`),
  CONSTRAINT `finances_finance_account_id_foreign` FOREIGN KEY (`finance_account_id`) REFERENCES `finance_accounts` (`id`) ON DELETE SET NULL,
  CONSTRAINT `finances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `finances_workspace_id_foreign` FOREIGN KEY (`workspace_id`) REFERENCES `workspaces` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `finances`
--

LOCK TABLES `finances` WRITE;
/*!40000 ALTER TABLE `finances` DISABLE KEYS */;
/*!40000 ALTER TABLE `finances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `financial_goals`
--

DROP TABLE IF EXISTS `financial_goals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `financial_goals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `workspace_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'target',
  `target_amount` decimal(15,2) NOT NULL,
  `current_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'bg-blue-500',
  `deadline` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `financial_goals_workspace_id_foreign` (`workspace_id`),
  KEY `financial_goals_user_id_foreign` (`user_id`),
  CONSTRAINT `financial_goals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `financial_goals_workspace_id_foreign` FOREIGN KEY (`workspace_id`) REFERENCES `workspaces` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `financial_goals`
--

LOCK TABLES `financial_goals` WRITE;
/*!40000 ALTER TABLE `financial_goals` DISABLE KEYS */;
/*!40000 ALTER TABLE `financial_goals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `galleries`
--

DROP TABLE IF EXISTS `galleries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `galleries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `galleries`
--

LOCK TABLES `galleries` WRITE;
/*!40000 ALTER TABLE `galleries` DISABLE KEYS */;
/*!40000 ALTER TABLE `galleries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventories`
--

DROP TABLE IF EXISTS `inventories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_barang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stok` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventories`
--

LOCK TABLES `inventories` WRITE;
/*!40000 ALTER TABLE `inventories` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_02_26_084911_create_personal_access_tokens_table',1),(5,'2026_02_26_090425_add_role_to_users_table',1),(6,'2026_02_26_092500_create_customers_table',1),(7,'2026_02_26_092555_tickets',1),(8,'2026_02_26_094336_create_finances_table',1),(9,'2026_02_26_095916_create_inventories_table',1),(10,'2026_02_26_095917_create_galleries_table',1),(11,'2026_03_02_080422_create_workspaces_table',1),(12,'2026_03_02_080432_redesign_finances_table',1),(13,'2026_03_02_090000_add_device_and_cost_to_tickets',1),(14,'2026_03_02_115817_create_finance_categories_table',1),(15,'2026_03_02_142018_create_finance_accounts_table',1),(16,'2026_03_02_142124_add_finance_account_id_to_finances_table',1),(17,'2026_03_03_100444_add_avatar_to_users_table',1),(18,'2026_03_04_035434_add_pin_hash_to_workspaces_table',1),(19,'2026_03_04_100409_create_financial_goals_table',1),(20,'2026_03_04_142629_add_draft_and_ai_columns_to_finances_table',1),(21,'2026_03_05_140000_add_super_admin_role_to_users_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES (1,'App\\Models\\User',1,'api-token','7a51c1a179c7cede7b1b8540cbf576271403c7321487c807a740d1fdbf665475','[\"*\"]','2026-04-11 11:49:21',NULL,'2026-04-11 11:26:48','2026-04-11 11:49:21');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('RZSQ4BilIq0X8YXMvxa1YWhlPefHR4rAxCfZjI5k',NULL,'172.18.0.1','curl/7.81.0','YToyOntzOjY6Il90b2tlbiI7czo0MDoib0t2RFhYVDBQdEVwMnUxY2RNczRpUlVFMEtYOUs5TUpBdWVNS2N0YiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1775900754);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tickets`
--

DROP TABLE IF EXISTS `tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tickets` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_device` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'LAPTOP',
  `merk_device` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estimasi` bigint unsigned NOT NULL DEFAULT '0',
  `biaya_final` bigint unsigned NOT NULL DEFAULT '0',
  `status` enum('open','in_progress','resolved','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `priority` enum('low','medium','high','urgent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tickets_customer_id_foreign` (`customer_id`),
  KEY `tickets_user_id_foreign` (`user_id`),
  KEY `tickets_status_index` (`status`),
  KEY `tickets_priority_index` (`priority`),
  CONSTRAINT `tickets_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tickets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tickets`
--

LOCK TABLES `tickets` WRITE;
/*!40000 ALTER TABLE `tickets` DISABLE KEYS */;
INSERT INTO `tickets` VALUES ('019d7be8-03df-7165-bf0c-3fe2fc4a7178','019d7be8-03da-73af-b694-053f9bc1edcc',NULL,'Aspernatur aperiam saepe culpa consequuntur et accusamus est.','Eveniet perferendis consequatur voluptatem blanditiis ut dolorem non. In doloribus eveniet enim. Voluptatem voluptatem neque alias mollitia totam natus voluptatum voluptas. Laborum aut facere sunt pariatur ut.','LAPTOP',NULL,0,0,'resolved','medium','2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-03e2-7139-b99d-e8ddb2f6c10f','019d7be8-03da-73af-b694-053f9bc1edcc',NULL,'Enim amet asperiores ipsa quisquam cupiditate amet.','Nesciunt error aut quasi necessitatibus est mollitia. Sapiente culpa voluptatum adipisci exercitationem. Ullam maiores consequatur et ut.','LAPTOP',NULL,0,0,'closed','low','2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-03e6-7066-9130-41108b5e6ded','019d7be8-03da-73af-b694-053f9bc1edcc',NULL,'Laboriosam est animi quaerat praesentium eum omnis.','Optio iste nisi non. Sunt modi quia cum atque officia rerum qui. Quo molestias mollitia consequatur reprehenderit qui molestias. Et accusantium rerum et.','LAPTOP',NULL,0,0,'closed','urgent','2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-0411-73e4-b76d-ce4be6da48d8','019d7be8-03ea-7352-951c-f646ceaf3caf',NULL,'Non repellendus in aut aut aspernatur.','Hic omnis ut quos dolore quos. Autem minima tenetur sint atque hic ut. Qui et aut et distinctio est minima aperiam.','LAPTOP',NULL,0,0,'open','urgent','2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-0416-7100-81ce-922214b60bec','019d7be8-03ea-7352-951c-f646ceaf3caf',NULL,'Reiciendis maxime eius et.','Placeat itaque ut deleniti. Voluptatem ad eos porro qui quidem doloremque. At iste laudantium et voluptate dolor libero.','LAPTOP',NULL,0,0,'closed','high','2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-041b-70b3-95fe-b5c90a68205d','019d7be8-03ea-7352-951c-f646ceaf3caf',NULL,'Amet rerum eius sit.','Id maiores ullam sunt corporis tenetur. Consectetur porro beatae placeat itaque aut occaecati. Et accusamus iure non nisi. Porro voluptas provident et.','LAPTOP',NULL,0,0,'open','urgent','2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-0423-7178-8095-0be96ebaf9d1','019d7be8-041f-7309-9ed6-53d66feda77a',NULL,'Omnis suscipit similique architecto optio.','Quis voluptatem accusamus omnis soluta veritatis quia placeat. Modi ex nulla magnam sunt sit. Repudiandae voluptate officiis cupiditate tenetur quas.','LAPTOP',NULL,0,0,'closed','high','2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-0427-712d-833b-4bb60dfaf93e','019d7be8-041f-7309-9ed6-53d66feda77a',NULL,'Error iusto vero pariatur quod sint.','Qui pariatur hic nobis nihil officia corrupti hic. Aut repellat voluptas soluta at ad. Iusto minima temporibus enim repudiandae nesciunt sit repudiandae. Qui quibusdam debitis eius nisi.','LAPTOP',NULL,0,0,'closed','medium','2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-042b-71cd-b763-316a94fe9a88','019d7be8-041f-7309-9ed6-53d66feda77a',NULL,'Repellendus sed et praesentium praesentium voluptas dolorem rerum.','Earum porro repellat illo ratione. Ut quae alias ea tempora quaerat. Officia perspiciatis odit aut delectus et voluptates nisi exercitationem. Porro quasi eum qui suscipit quos dolorem.','LAPTOP',NULL,0,0,'closed','medium','2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-0432-7271-8269-2a0b25fb45a1','019d7be8-042f-7048-ae69-088677f9f654',NULL,'Maxime aut est distinctio molestias quod.','Est aspernatur quam explicabo animi qui qui aut soluta. Voluptatem qui eius vel odit soluta quam eligendi. Corporis distinctio quidem atque et laudantium corporis. Est unde ut dolores quia.','LAPTOP',NULL,0,0,'in_progress','medium','2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-0436-7302-88d3-f6d0b3ec968a','019d7be8-042f-7048-ae69-088677f9f654',NULL,'Officia sit incidunt dolor.','Sit porro ut modi. Tempora pariatur mollitia soluta.','LAPTOP',NULL,0,0,'resolved','urgent','2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-043a-7341-a76b-b3729d79619f','019d7be8-042f-7048-ae69-088677f9f654',NULL,'Voluptatum et ipsum voluptatem nam quia.','Sequi veniam sed temporibus ipsam. Exercitationem consequuntur doloribus rerum. Qui est modi ipsum ut expedita expedita enim. Omnis dolores rerum culpa itaque sed est assumenda.','LAPTOP',NULL,0,0,'open','high','2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-0443-7037-beb1-7e1c931fb6ee','019d7be8-043f-73d1-bc18-686a5aae0a8c',NULL,'Consequatur similique vel sed odit quo quibusdam dolorem.','Voluptas aut impedit et necessitatibus delectus veniam. Tempora dignissimos cum qui quis laborum aliquid repellat reprehenderit. Est nobis corporis vero occaecati autem. Eum expedita omnis esse sed voluptatem officia illo.','LAPTOP',NULL,0,0,'in_progress','medium','2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-0446-724c-8945-bab2348b8a74','019d7be8-043f-73d1-bc18-686a5aae0a8c',NULL,'Eveniet et est explicabo dolor sit.','Non molestiae facere possimus dolores. Voluptatibus pariatur nesciunt et. Necessitatibus dolore facilis optio beatae cupiditate dolorem.','LAPTOP',NULL,0,0,'in_progress','low','2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-044a-716a-8c0e-f1e78aee23b0','019d7be8-043f-73d1-bc18-686a5aae0a8c',NULL,'Molestiae quo sunt omnis aspernatur ut voluptas.','Suscipit id sed id totam. Aut sit sed nobis ullam esse soluta. Qui unde recusandae consequatur voluptas dolor.','LAPTOP',NULL,0,0,'in_progress','urgent','2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-0451-7146-a436-770b4f4054e3','019d7be8-044d-734e-aa2d-e520a360a650',NULL,'Vero repudiandae cumque consectetur mollitia consectetur asperiores quidem.','Molestiae laudantium totam fugiat molestiae. Eligendi omnis et voluptatem itaque. Ducimus nulla quis et et quis.','LAPTOP',NULL,0,0,'in_progress','low','2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-0454-7248-8134-39891f2c37e7','019d7be8-044d-734e-aa2d-e520a360a650',NULL,'Dolorem molestiae adipisci ut doloribus tenetur.','Dolor ut qui consequatur rerum. Ut sed sed fugit pariatur libero. Illo veniam perspiciatis aut. Culpa et quam molestias dicta aut eius ducimus aut.','LAPTOP',NULL,0,0,'resolved','low','2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-0458-73fc-a89c-156156060b6c','019d7be8-044d-734e-aa2d-e520a360a650',NULL,'Nobis aliquid voluptatem ut velit molestiae.','Quia eos omnis enim quaerat. Voluptate quae illum repellendus inventore. Consequuntur modi reprehenderit et sequi.','LAPTOP',NULL,0,0,'closed','medium','2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-0461-728f-b4f7-72626d12f80c','019d7be8-045d-72fc-9109-899f532f28fc',NULL,'Voluptates repellendus voluptatum ad harum recusandae unde placeat.','Et veritatis assumenda aut sint quia. Vitae non est sit quibusdam ad. Eaque ad autem sequi accusantium exercitationem rerum.','LAPTOP',NULL,0,0,'resolved','urgent','2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-0466-71a4-8cf9-fe1164e4c08d','019d7be8-045d-72fc-9109-899f532f28fc',NULL,'Distinctio quos unde in reprehenderit recusandae et.','Ullam consequatur laudantium possimus non recusandae ducimus. Fugiat suscipit sapiente quis fugit. Quod molestiae perspiciatis porro dolorum illum. Aspernatur autem molestias sunt eum architecto voluptates totam.','LAPTOP',NULL,0,0,'open','low','2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-046c-73b4-961f-b96cba81174d','019d7be8-045d-72fc-9109-899f532f28fc',NULL,'Facilis nostrum quas non.','Voluptatem quo iusto velit qui minima sequi aut. Sint explicabo qui ut exercitationem. Maxime et rem eum odit voluptatem. Est quam dolore debitis sunt sit inventore cum. Repellendus quod odio incidunt id.','LAPTOP',NULL,0,0,'open','low','2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-0474-706a-8659-798286f2d8ba','019d7be8-0470-70fa-946c-33780eb9e9fd',NULL,'Voluptatem rerum unde non enim voluptas.','Est error aut qui ut. Ex quo eos veniam magni non reprehenderit. Pariatur sapiente sed ullam enim dicta. Alias quis non aut ut eligendi illo ipsum odit.','LAPTOP',NULL,0,0,'closed','medium','2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-0478-7190-b119-3913f816acc6','019d7be8-0470-70fa-946c-33780eb9e9fd',NULL,'Earum earum velit autem architecto.','Aut sit pariatur sed voluptatem pariatur non quibusdam. A qui non ea quia ipsam occaecati.','LAPTOP',NULL,0,0,'closed','urgent','2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-047b-73cc-b83f-08bed1f4189b','019d7be8-0470-70fa-946c-33780eb9e9fd',NULL,'Provident est magnam odio possimus nihil eum nemo.','Iste quasi molestias corporis tempora voluptatibus modi. Distinctio odio tenetur sunt perferendis vel. Aut minus eius fugit non. Nobis occaecati et rerum iure sed rerum.','LAPTOP',NULL,0,0,'resolved','medium','2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-0483-710e-bfd1-5b5e2cd75e73','019d7be8-047f-71dd-820c-1dcc300f71b1',NULL,'Omnis sit nihil ratione.','Placeat neque natus eveniet maiores enim rerum ullam eum. Cum voluptas eius ullam omnis sunt. Quisquam sit enim aspernatur omnis. Voluptatem adipisci molestiae suscipit autem facilis aut consectetur.','LAPTOP',NULL,0,0,'in_progress','low','2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-0486-73ef-a281-a70f80a79d08','019d7be8-047f-71dd-820c-1dcc300f71b1',NULL,'Vero quia architecto rem quidem quia.','Nisi sequi illum error impedit et sed non. Animi nisi optio necessitatibus odit quo sit est velit. Temporibus hic voluptatibus quisquam qui. Voluptatem recusandae ad beatae earum.','LAPTOP',NULL,0,0,'closed','low','2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-048a-73ce-a3dc-f1ff1625b52d','019d7be8-047f-71dd-820c-1dcc300f71b1',NULL,'Suscipit quasi ducimus quaerat quod laborum.','Deleniti fuga ut velit labore. In nesciunt eligendi aliquid saepe natus est. Illum quod fugiat doloribus. Est est distinctio quia veritatis est debitis.','LAPTOP',NULL,0,0,'resolved','high','2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-0494-739f-ae0a-d91aae814095','019d7be8-048e-730b-9000-bbe232468648',NULL,'A repudiandae fugiat dolor et inventore magni at.','Enim est est quia facere voluptates laborum sit illo. Modi amet magnam et porro cum laudantium consectetur nam. Impedit veritatis quod sint dolor. Magnam voluptate voluptates voluptate nulla saepe odit.','LAPTOP',NULL,0,0,'closed','urgent','2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-0497-7390-ab06-58bc2c3e2a4a','019d7be8-048e-730b-9000-bbe232468648',NULL,'Voluptatem eligendi impedit occaecati necessitatibus.','Beatae dolor reprehenderit dolores voluptas expedita minus magnam saepe. Modi omnis sit est illo. Consequatur velit non dolores recusandae.','LAPTOP',NULL,0,0,'open','high','2026-04-11 09:38:16','2026-04-11 09:38:16',NULL),('019d7be8-049b-7347-a530-c2b2707266a9','019d7be8-048e-730b-9000-bbe232468648',NULL,'Sit quia qui aut quae nesciunt atque numquam.','Autem aut aperiam voluptates ab debitis consectetur voluptates nemo. Neque eaque omnis exercitationem suscipit. Sequi cupiditate qui vitae itaque incidunt. Et corporis veniam repellendus voluptatem.','LAPTOP',NULL,0,0,'in_progress','low','2026-04-11 09:38:16','2026-04-11 09:38:16',NULL);
/*!40000 ALTER TABLE `tickets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('super_admin','admin','technician','customer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin Lutech','admin@lutech.com',NULL,'super_admin','2026-04-11 09:38:15','$2y$12$.EdFBdsZR2veYkglRHXrHOaiUpViZDmr6bl4QrRcu8MHcO7vbQuhe','bbwLsom5cE','2026-04-11 09:38:16','2026-04-11 09:38:16');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `workspaces`
--

DROP TABLE IF EXISTS `workspaces`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `workspaces` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('personal','business') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'personal',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `pin_hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `workspaces_user_id_is_default_index` (`user_id`,`is_default`),
  CONSTRAINT `workspaces_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workspaces`
--

LOCK TABLES `workspaces` WRITE;
/*!40000 ALTER TABLE `workspaces` DISABLE KEYS */;
INSERT INTO `workspaces` VALUES (1,1,'Kasir Lutech','business',1,NULL,'2026-04-11 09:38:16','2026-04-11 09:38:16'),(2,1,'uang','personal',0,NULL,'2026-04-11 11:49:10','2026-04-11 11:49:10');
/*!40000 ALTER TABLE `workspaces` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-11 11:55:12
