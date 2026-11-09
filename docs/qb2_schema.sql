-- MySQL dump 10.13  Distrib 8.0.46, for Linux (x86_64)
--
-- Host: db-mysql-fra1-qbgol-do-user-6805390-0.db.ondigitalocean.com    Database: qb2
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
SET @MYSQLDUMP_TEMP_LOG_BIN = @@SESSION.SQL_LOG_BIN;
SET @@SESSION.SQL_LOG_BIN= 0;

--
-- GTID state at the beginning of the backup 
--

SET @@GLOBAL.GTID_PURGED=/*!80000 '+'*/ '2a457774-e5c1-11ef-b1bf-92fdbeed34bd:1-11992,
2fac4b2c-baa2-11eb-a99c-b6c0d06099bd:1-21736,
379fb9cf-bc47-11f0-ac24-126c79a8b897:1-50364,
3de34443-883e-11ef-a61c-f2761e052f51:1-153546,
4366318a-3843-11f0-aeb2-e237c0c26b60:1-51966,
4b9e0fc0-a1a9-11ec-aa9b-a605674aa7c3:1-1355,
596a7b33-3ea6-11ec-9239-56db0d3be930:1-60046,
71165e0c-ff2b-11ec-bbd0-b69109327106:1-130228,
794b6d92-a79a-11ea-ac45-82c752eca04c:1-133335,
7a2faca6-144a-11f1-987f-7e87c387c602:1-28813,
80b9dff1-d248-11f0-8a7a-a210b92cd40a:1-100282,
a495b2e9-6c25-11f1-806a-f2fe76c73f7b:1-28713,
a8ef0c58-b94e-11f1-ab4c-8adf6fc62f2e:1-192,
adc49570-b1b8-11ee-a8e3-d248e3311d30:1-33458,
b5631e47-1a3b-11ef-b9c5-caa63e6bbb16:1-48049,
d29bf848-1d33-11ee-ad74-4e77c5ec532f:1-256272,
eefee64e-143a-11ea-a664-c66301584220:1-88744,
f04ef7aa-b7a9-11ec-8cb4-d2c918347c6d:1-31570,
ff4a78f1-0c41-11f0-9f6a-c20dbdb8b7e2:1-8762';

--
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `accounts_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `activity_log`
--

DROP TABLE IF EXISTS `activity_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `log_name` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `causer_type` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint unsigned DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `batch_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB AUTO_INCREMENT=330157 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bordereaux_er_info`
--

DROP TABLE IF EXISTS `bordereaux_er_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bordereaux_er_info` (
  `product_offer_id` bigint unsigned NOT NULL,
  `rc_eccesso` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `spese_legali_eccesso` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `combinazione` int NOT NULL,
  `massimale_cassieri` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `massimale_patrimoniale` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `massimale_spese_legali` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `italiana_cassa` double(8,2) NOT NULL,
  `italiana_patrimoniale` double(8,2) NOT NULL,
  `chubb_patrimoniale` double(8,2) NOT NULL,
  `sara_patrimoniale` double(8,2) NOT NULL,
  `total_premium` double(8,2) NOT NULL,
  `fee` double(8,2) NOT NULL,
  `total_cost` double(8,2) NOT NULL,
  `periodo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `netto_italiana_cassa` double(8,2) NOT NULL,
  `imposta_cassieri` double(8,2) NOT NULL,
  `italiana_cassa2` double(8,2) NOT NULL,
  `netto_italiana_patrimoniale` double(8,2) NOT NULL,
  `imposta_patrimoniale` double(8,2) NOT NULL,
  `italiana_patrimoniale2` double(8,2) NOT NULL,
  `netto_chubb_patrimoniale` double(8,2) NOT NULL,
  `imposta_chubb_patrimoniale` double(8,2) NOT NULL,
  `chubb_patrimoniale2` double(8,2) NOT NULL,
  `netto_sara_patrimoniale` double(8,2) NOT NULL,
  `imposta_sara_patrimoniale` double(8,2) NOT NULL,
  `sara_patrimoniale2` double(8,2) NOT NULL,
  PRIMARY KEY (`product_offer_id`),
  CONSTRAINT `bordereaux_er_info_product_offer_id_foreign` FOREIGN KEY (`product_offer_id`) REFERENCES `product_offers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bordereaux_er_static`
--

DROP TABLE IF EXISTS `bordereaux_er_static`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bordereaux_er_static` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_offer_id` int NOT NULL,
  `I` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `J` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `K` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `L` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `M` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `N` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `P` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `product_step_id` bigint unsigned NOT NULL,
  `product_offer_id` bigint unsigned DEFAULT NULL,
  `cart_ref` varchar(13) COLLATE utf8mb4_unicode_ci NOT NULL,
  `renewal_date` date DEFAULT NULL,
  `retroactivity` date DEFAULT NULL,
  `total_payable` decimal(8,2) DEFAULT NULL,
  `admin_fee` decimal(8,2) DEFAULT NULL,
  `gross_premium` decimal(8,2) DEFAULT NULL,
  `tax` decimal(8,2) DEFAULT NULL,
  `net_premium` decimal(8,2) DEFAULT NULL,
  `accessory` decimal(8,2) DEFAULT NULL,
  `net_plus_access_premium` decimal(8,2) DEFAULT NULL,
  `indemnity_limit` int DEFAULT NULL,
  `indemnity_limit_patrimonial` int DEFAULT NULL,
  `declarations_signature` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `locked` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  `note` text COLLATE utf8mb4_unicode_ci,
  `expired_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `custom_end_date` date DEFAULT NULL,
  `giorno_completamento_step_1` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `carts_cart_ref_unique` (`cart_ref`),
  KEY `carts_user_id_foreign` (`user_id`),
  KEY `carts_product_id_foreign` (`product_id`),
  KEY `carts_product_step_id_foreign` (`product_step_id`),
  KEY `carts_product_offer_id_foreign` (`product_offer_id`),
  CONSTRAINT `carts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `carts_product_offer_id_foreign` FOREIGN KEY (`product_offer_id`) REFERENCES `product_offers` (`id`),
  CONSTRAINT `carts_product_step_id_foreign` FOREIGN KEY (`product_step_id`) REFERENCES `product_steps` (`id`),
  CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18331 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `certificates`
--

DROP TABLE IF EXISTS `certificates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `certificates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cart_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `product_offer_id` bigint unsigned NOT NULL,
  `contract_id` bigint unsigned DEFAULT NULL,
  `doprint_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `doprint_current_step` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `doprint_certificate_no` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_validity_date` date DEFAULT NULL,
  `end_validity_date` date DEFAULT NULL,
  `retroactivity` date DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `certificates_doprint_certificate_no_unique` (`doprint_certificate_no`),
  KEY `certificates_cart_id_foreign` (`cart_id`),
  KEY `certificates_product_id_foreign` (`product_id`),
  KEY `certificates_product_offer_id_foreign` (`product_offer_id`),
  KEY `certificates_contract_id_foreign` (`contract_id`),
  CONSTRAINT `certificates_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`),
  CONSTRAINT `certificates_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`),
  CONSTRAINT `certificates_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `certificates_product_offer_id_foreign` FOREIGN KEY (`product_offer_id`) REFERENCES `product_offers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15366 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `client_details`
--

DROP TABLE IF EXISTS `client_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `first_name` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fiscal_code` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `address` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `province` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `confirmation` tinyint(1) NOT NULL DEFAULT '0',
  `contact_ref` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `version_ref` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `with_accident` tinyint(1) NOT NULL DEFAULT '0',
  `flag_prev_2022` tinyint(1) NOT NULL DEFAULT '0',
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `motivo_blocco_invio` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_details_user_id_foreign` (`user_id`),
  CONSTRAINT `client_details_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=102089 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `conditional_fields`
--

DROP TABLE IF EXISTS `conditional_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `conditional_fields` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `field_name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_label` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_type` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_value` json NOT NULL,
  `only_client_details` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `conditional_fields_field_name_unique` (`field_name`),
  UNIQUE KEY `conditional_fields_field_label_unique` (`field_label`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contracts`
--

DROP TABLE IF EXISTS `contracts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contracts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `contract_filepath` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contract_prop` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `dip` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dip_aggiuntivo` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contract_old` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contract_prev_2022` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `old_prev_2022` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `document_types`
--

DROP TABLE IF EXISTS `document_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `document_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `document_types_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `document_type_id` bigint unsigned NOT NULL,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `documents_name_unique` (`name`),
  KEY `documents_document_type_id_foreign` (`document_type_id`),
  CONSTRAINT `documents_document_type_id_foreign` FOREIGN KEY (`document_type_id`) REFERENCES `document_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `doprint_counters`
--

DROP TABLE IF EXISTS `doprint_counters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doprint_counters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `year` int NOT NULL,
  `counter` int NOT NULL,
  `prefix` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postfix` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `doprint_counters_prefix_unique` (`prefix`),
  KEY `doprint_counters_product_id_foreign` (`product_id`),
  CONSTRAINT `doprint_counters_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ehealth_buyers`
--

DROP TABLE IF EXISTS `ehealth_buyers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ehealth_buyers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ehealth_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_ref` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `codice_fiscale` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `indirizzo_residenza` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `citta_residenza` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ehealth_codes`
--

DROP TABLE IF EXISTS `ehealth_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ehealth_codes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `used` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `free_amounts`
--

DROP TABLE IF EXISTS `free_amounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `free_amounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `total_payable` double(8,2) NOT NULL,
  `causal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `importo_libero_buyers`
--

DROP TABLE IF EXISTS `importo_libero_buyers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `importo_libero_buyers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `causale` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_payable` double(8,2) NOT NULL,
  `transaction_ref` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `profession` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fiscalcode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dob` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `via` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `citta` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cap` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provincia` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `declarations` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1861 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=19602 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `model_type` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `collection_name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disk` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `conversions_disk` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` bigint unsigned NOT NULL,
  `manipulations` json NOT NULL,
  `custom_properties` json NOT NULL,
  `generated_conversions` json NOT NULL,
  `responsive_images` json NOT NULL,
  `order_column` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `media_uuid_unique` (`uuid`),
  KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  KEY `media_order_column_index` (`order_column`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  `account_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`account_id`,`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  KEY `model_has_permissions_permission_id_foreign` (`permission_id`),
  KEY `model_has_permissions_team_foreign_key_index` (`account_id`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  `account_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`account_id`,`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  KEY `model_has_roles_role_id_foreign` (`role_id`),
  KEY `model_has_roles_team_foreign_key_index` (`account_id`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`),
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `payment_gateways`
--

DROP TABLE IF EXISTS `payment_gateways`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_gateways` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `public` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payment_gateways_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cart_id` bigint unsigned NOT NULL,
  `payment_gateway_id` bigint unsigned NOT NULL,
  `payment_no` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_payable` decimal(8,2) NOT NULL,
  `paid` tinyint(1) NOT NULL DEFAULT '0',
  `transaction_ref` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_at` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_payment_no_unique` (`payment_no`),
  KEY `payments_cart_id_foreign` (`cart_id`),
  KEY `payments_payment_gateway_id_foreign` (`payment_gateway_id`),
  CONSTRAINT `payments_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`),
  CONSTRAINT `payments_payment_gateway_id_foreign` FOREIGN KEY (`payment_gateway_id`) REFERENCES `payment_gateways` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15595 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `product_client_details`
--

DROP TABLE IF EXISTS `product_client_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_client_details` (
  `client_detail_id` bigint unsigned NOT NULL,
  `conditional_field_id` bigint unsigned NOT NULL,
  `selected_field_value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `product_id` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`client_detail_id`,`conditional_field_id`),
  KEY `product_client_details_client_detail_id_index` (`client_detail_id`),
  KEY `product_client_details_conditional_field_id_index` (`conditional_field_id`),
  CONSTRAINT `product_client_details_client_detail_id_foreign` FOREIGN KEY (`client_detail_id`) REFERENCES `client_details` (`id`),
  CONSTRAINT `product_client_details_conditional_field_id_foreign` FOREIGN KEY (`conditional_field_id`) REFERENCES `conditional_fields` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `product_conditions`
--

DROP TABLE IF EXISTS `product_conditions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_conditions` (
  `product_id` bigint unsigned NOT NULL,
  `conditional_field_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`product_id`,`conditional_field_id`),
  KEY `product_conditions_product_id_index` (`product_id`),
  KEY `product_conditions_conditional_field_id_index` (`conditional_field_id`),
  CONSTRAINT `product_conditions_conditional_field_id_foreign` FOREIGN KEY (`conditional_field_id`) REFERENCES `conditional_fields` (`id`),
  CONSTRAINT `product_conditions_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `product_counters`
--

DROP TABLE IF EXISTS `product_counters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_counters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `payment_gateway_id` bigint unsigned NOT NULL,
  `year` year NOT NULL,
  `counter` int NOT NULL,
  `prefix` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_counters_prefix_unique` (`prefix`),
  KEY `product_counters_product_id_foreign` (`product_id`),
  KEY `product_counters_payment_gateway_id_foreign` (`payment_gateway_id`),
  CONSTRAINT `product_counters_payment_gateway_id_foreign` FOREIGN KEY (`payment_gateway_id`) REFERENCES `payment_gateways` (`id`),
  CONSTRAINT `product_counters_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `product_declarations`
--

DROP TABLE IF EXISTS `product_declarations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_declarations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `declaration` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `sort` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_declarations_product_id_foreign` (`product_id`),
  CONSTRAINT `product_declarations_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `product_offer_conditions`
--

DROP TABLE IF EXISTS `product_offer_conditions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_offer_conditions` (
  `product_offer_id` bigint unsigned NOT NULL,
  `conditional_field_id` bigint unsigned NOT NULL,
  `selected_field_value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`product_offer_id`,`conditional_field_id`),
  KEY `product_offer_conditions_product_offer_id_index` (`product_offer_id`),
  KEY `product_offer_conditions_conditional_field_id_index` (`conditional_field_id`),
  CONSTRAINT `product_offer_conditions_conditional_field_id_foreign` FOREIGN KEY (`conditional_field_id`) REFERENCES `conditional_fields` (`id`),
  CONSTRAINT `product_offer_conditions_product_offer_id_foreign` FOREIGN KEY (`product_offer_id`) REFERENCES `product_offers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `product_offer_tables`
--

DROP TABLE IF EXISTS `product_offer_tables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_offer_tables` (
  `product_id` bigint unsigned NOT NULL,
  `table_elements` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`product_id`),
  CONSTRAINT `product_offer_tables_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `product_offers`
--

DROP TABLE IF EXISTS `product_offers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_offers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `contract_id` bigint unsigned NOT NULL,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_payable` decimal(8,2) NOT NULL,
  `admin_fee` decimal(8,2) NOT NULL,
  `gross_premium` decimal(8,2) NOT NULL,
  `tax` decimal(8,2) NOT NULL,
  `net_premium` decimal(8,2) NOT NULL,
  `accessory` decimal(8,2) NOT NULL,
  `net_plus_access_premium` decimal(8,2) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `indemnity_limit` int NOT NULL,
  `indemnity_limit_patrimonial` int NOT NULL,
  `flag_prev_2022` tinyint(1) NOT NULL DEFAULT '0',
  `months` tinyint unsigned NOT NULL,
  `active` tinyint(1) NOT NULL,
  `legal_fees` int NOT NULL,
  `combination` int NOT NULL,
  `hide` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `old` int DEFAULT NULL,
  `MUP` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_offers_name_unique` (`name`),
  KEY `product_offers_product_id_foreign` (`product_id`),
  KEY `product_offers_contract_id_foreign` (`contract_id`),
  CONSTRAINT `product_offers_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`),
  CONSTRAINT `product_offers_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=201 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `product_steps`
--

DROP TABLE IF EXISTS `product_steps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_steps` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `step_number` int NOT NULL,
  `step_name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_steps_product_id_foreign` (`product_id`),
  CONSTRAINT `product_steps_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `product_types`
--

DROP TABLE IF EXISTS `product_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_types_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `account_id` bigint unsigned NOT NULL,
  `product_type_id` bigint unsigned NOT NULL,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `detailed_description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `pre_declarations` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_declarations` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `conditions` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_details_declaration` text COLLATE utf8mb4_unicode_ci,
  `payments_description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `hex_color` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_name_unique` (`name`),
  KEY `products_account_id_foreign` (`account_id`),
  KEY `products_product_type_id_foreign` (`product_type_id`),
  CONSTRAINT `products_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`),
  CONSTRAINT `products_product_type_id_foreign` FOREIGN KEY (`product_type_id`) REFERENCES `product_types` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reminders`
--

DROP TABLE IF EXISTS `reminders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reminders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cart_id` bigint unsigned NOT NULL,
  `one_week_before` tinyint(1) NOT NULL DEFAULT '0',
  `two_weeks_after` tinyint(1) NOT NULL DEFAULT '0',
  `one_month_after` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `one_week_after` tinyint(1) DEFAULT NULL,
  `pw` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reminders_cart_id_foreign` (`cart_id`),
  CONSTRAINT `reminders_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9576 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `renewals`
--

DROP TABLE IF EXISTS `renewals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `renewals` (
  `old_cart_ref` varchar(13) COLLATE utf8mb4_unicode_ci NOT NULL,
  `new_cart_ref` varchar(13) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`old_cart_ref`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `renewals_email`
--

DROP TABLE IF EXISTS `renewals_email`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `renewals_email` (
  `old_cart_ref` varchar(13) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`old_cart_ref`,`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24459 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `account_id` bigint unsigned DEFAULT NULL,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_account_id_name_guard_name_unique` (`account_id`,`name`,`guard_name`),
  KEY `roles_team_foreign_key_index` (`account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `signatures`
--

DROP TABLE IF EXISTS `signatures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `signatures` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `certificate_id` bigint unsigned NOT NULL,
  `procedure_id` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_id` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member_signer_id` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `signature_link` varchar(2000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `signatures_procedure_id_unique` (`procedure_id`),
  UNIQUE KEY `signatures_file_id_unique` (`file_id`),
  UNIQUE KEY `signatures_member_signer_id_unique` (`member_signer_id`),
  KEY `signatures_certificate_id_foreign` (`certificate_id`),
  CONSTRAINT `signatures_certificate_id_foreign` FOREIGN KEY (`certificate_id`) REFERENCES `certificates` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4563 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `account_id` bigint unsigned NOT NULL,
  `first_name` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner` tinyint(1) NOT NULL DEFAULT '0',
  `new_client` tinyint(1) NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_account_id_foreign` (`account_id`),
  CONSTRAINT `users_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6877 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping routines for database 'qb2'
--
SET @@SESSION.SQL_LOG_BIN = @MYSQLDUMP_TEMP_LOG_BIN;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-28 13:39:18
