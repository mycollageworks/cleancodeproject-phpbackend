-- Adminer 4.8.1 MySQL 8.0.33 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `notes`;
CREATE TABLE `notes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `notes` (`id`, `date`, `content`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1,	'2025-03-02',	'Testing bg',	'2025-06-05 00:56:47',	'2025-06-05 00:56:47',	NULL),
(2,	'2025-01-02',	'Testing bgaaaa',	'2025-06-04 18:10:23',	'2025-06-04 18:32:53',	'2025-06-05 01:33:05');

-- 2025-06-05 01:44:40
