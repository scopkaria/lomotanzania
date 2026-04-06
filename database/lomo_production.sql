-- MySQL 8.0 compatible dump of `lomo`
-- Generated: 2026-04-06 10:04:34

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `accommodation_images`;
CREATE TABLE `accommodation_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `accommodation_id` bigint unsigned NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `accommodation_id` (`accommodation_id`),
  CONSTRAINT `accommodation_images_ibfk_1` FOREIGN KEY (`accommodation_id`) REFERENCES `accommodations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `accommodation_images` (`id`, `accommodation_id`, `image_path`, `created_at`, `updated_at`) VALUES
('1', '1', 'accommodations/JmpNefBXQA4e9YQ5sZim8boMGttrO2nUTo3kMIPI.jpg', '2026-03-31 12:36:07', '2026-03-31 12:36:07'),
('2', '1', 'accommodations/olU2N1yUihT8S2t0F9wszPVn8q6IJGGvKPDpzmEf.jpg', '2026-03-31 12:36:07', '2026-03-31 12:36:07'),
('3', '1', 'accommodations/o2XqO1iL5HgzAIgissdhhIqPFswRSAzi11MTjlGu.png', '2026-03-31 12:36:07', '2026-03-31 12:36:07'),
('4', '2', 'accommodations/1mHWCry6qYMiTFC0Oid0S7GjahIGM8vhpMZFlkJw.png', '2026-03-31 15:35:07', '2026-03-31 15:35:07'),
('5', '3', 'accommodations/UvKwSKHntNxzsFMSDnyhpys2EpI17ijz16Jwub5v.jpg', '2026-03-31 15:36:44', '2026-03-31 15:36:44'),
('6', '3', 'accommodations/3uMgXzDt3GKCkqAGwk8sqteNDZuWElnOPD8PRKYZ.jpg', '2026-03-31 15:36:44', '2026-03-31 15:36:44');

DROP TABLE IF EXISTS `accommodations`;
CREATE TABLE `accommodations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` bigint unsigned NOT NULL,
  `destination_id` bigint unsigned NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_translations` text COLLATE utf8mb4_unicode_ci,
  `description_translations` text COLLATE utf8mb4_unicode_ci,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `meta_keywords` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `country_id` (`country_id`),
  KEY `destination_id` (`destination_id`),
  CONSTRAINT `accommodations_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `accommodations_ibfk_2` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `accommodations` (`id`, `name`, `description`, `category`, `country_id`, `destination_id`, `created_at`, `updated_at`, `slug`, `name_translations`, `description_translations`, `meta_title`, `meta_description`, `meta_keywords`, `og_image`) VALUES
('1', 'Moivaro Arusha Lodge', 'Spacious thatched brick cottages are set in a lovely tropical garden, with private verandas to gaurantee every visitor all the privacy needed. Relax and enjoy the beautiful vista of Mount Meru from the comfortable veranda, go for a stroll around the Moivaro property walking trail, or laze at the pool nestling in the midst of tropical trees and coffee plants. Spacious rooms with fireplace, en-suite bathroom, desk, single or double beds, mosquito nets, ceiling fan, and a private veranda', 'luxury', '1', '4', '2026-03-31 12:36:07', '2026-03-31 12:36:07', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2', 'Africa Safari Karatu', NULL, 'luxury', '1', '4', '2026-03-31 15:35:07', '2026-03-31 15:35:07', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('3', 'Zuri Serengeti Camp', NULL, 'luxury', '1', '1', '2026-03-31 15:36:44', '2026-03-31 15:36:44', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('4', 'Serengeti Serena Safari Lodge', 'Perched on a ridge overlooking the Serengeti plains with panoramic views of the migration.', 'Luxury', '1', '1', '2026-04-05 01:37:19', '2026-04-05 01:37:19', 'serengeti-serena-safari-lodge', NULL, NULL, NULL, NULL, NULL, NULL),
('5', 'Ngorongoro Crater Lodge', 'Opulent Maasai-inspired suites on the crater rim with personal butler service.', 'Ultra-Luxury', '1', '2', '2026-04-05 01:37:19', '2026-04-05 01:37:19', 'ngorongoro-crater-lodge', NULL, NULL, NULL, NULL, NULL, NULL),
('6', 'Four Seasons Safari Lodge', 'Five-star resort in the heart of the Serengeti with infinity pool overlooking a waterhole.', 'Ultra-Luxury', '1', '1', '2026-04-05 01:37:19', '2026-04-05 01:37:19', 'four-seasons-safari-lodge', NULL, NULL, NULL, NULL, NULL, NULL),
('7', 'Tarangire Treetops', 'Elevated treehouses among ancient baobabs in Tarangire\'s wildlife corridor.', 'Luxury', '1', '5', '2026-04-05 01:37:19', '2026-04-05 01:37:19', 'tarangire-treetops', NULL, NULL, NULL, NULL, NULL, NULL),
('11', 'Lake Manyara Tree Lodge', 'Intimate treehouse-style suites tucked into an ancient mahogany forest.', 'Luxury', '1', '6', '2026-04-05 01:37:19', '2026-04-05 01:37:19', 'lake-manyara-tree-lodge', NULL, NULL, NULL, NULL, NULL, NULL),
('12', 'Selous Riverside Camp', 'Simple tented camp on the Rufiji River with boat safari access.', 'Budget', '1', '8', '2026-04-05 01:37:19', '2026-04-05 01:37:19', 'selous-riverside-camp', NULL, NULL, NULL, NULL, NULL, NULL),
('13', 'Kilimanjaro Mountain Hut', 'Basic mountain accommodation along the Marangu route to the summit.', 'Budget', '1', '7', '2026-04-05 01:37:19', '2026-04-05 01:37:19', 'kilimanjaro-mountain-hut', NULL, NULL, NULL, NULL, NULL, NULL);

DROP TABLE IF EXISTS `agents`;
CREATE TABLE `agents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `commission_rate` decimal(10,2) NOT NULL DEFAULT '10.00',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `agents_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `agents_chk_1` CHECK ((`status` in ('pending','active','suspended')))
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `agents` (`id`, `user_id`, `company_name`, `phone`, `country`, `commission_rate`, `status`, `created_at`, `updated_at`) VALUES
('1', '2', 'town colors', '+255758273300', 'Tanzania', '30.00', 'active', '2026-04-01 15:17:38', '2026-04-01 16:46:25');

DROP TABLE IF EXISTS `author_profiles`;
CREATE TABLE `author_profiles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `linkedin_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expertise` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `years_experience` bigint DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `author_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `blog_categories`;
CREATE TABLE `blog_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` bigint NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `bookings`;
CREATE TABLE `bookings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `agent_id` bigint unsigned NOT NULL,
  `safari_package_id` bigint unsigned DEFAULT NULL,
  `client_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `travel_date` date NOT NULL,
  `num_people` bigint NOT NULL DEFAULT '1',
  `total_price` decimal(10,2) NOT NULL DEFAULT '0',
  `commission_amount` decimal(10,2) NOT NULL DEFAULT '0',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `safari_package_id` (`safari_package_id`),
  KEY `agent_id` (`agent_id`),
  CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`safari_package_id`) REFERENCES `safari_packages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`agent_id`) REFERENCES `agents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `bookings` (`id`, `agent_id`, `safari_package_id`, `client_name`, `client_email`, `client_phone`, `travel_date`, `num_people`, `total_price`, `commission_amount`, `status`, `notes`, `created_at`, `updated_at`) VALUES
('1', '1', '3', 'Roger Emmanuel', 'Roger@SafarisWithAHeart.com', '0754853391', '2026-04-23', '1', '0.00', '0.00', 'pending', NULL, '2026-04-01 15:37:49', '2026-04-01 15:37:49'),
('2', '1', NULL, 'Roger Emmanuel', 'Roger@SafarisWithAHeart.com', '+27 458781324565', '2026-04-17', '2', '7000.00', '700.00', 'confirmed', 'Custom safari: 4 dats', '2026-04-01 16:41:12', '2026-04-01 16:41:12');

DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-scopkaria@gmail.com|127.0.0.1', 'i:1;', '1775400058'),
('laravel-cache-scopkaria@gmail.com|127.0.0.1:timer', 'i:1775400058;', '1775400058');

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `featured_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_translations` text COLLATE utf8mb4_unicode_ci,
  `description_translations` text COLLATE utf8mb4_unicode_ci,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `meta_keywords` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` (`id`, `name`, `slug`, `created_at`, `updated_at`, `description`, `featured_image`, `name_translations`, `description_translations`, `meta_title`, `meta_description`, `meta_keywords`, `og_image`) VALUES
('1', 'Luxury Safari', 'luxury', '2026-03-31 09:00:03', '2026-04-02 16:08:35', NULL, NULL, '{\"fr\":\"Safari de luxe\",\"de\":\"Luxus-Safari\",\"es\":\"Safari de lujo\"}', NULL, NULL, NULL, NULL, NULL),
('2', 'Budget', 'budget', '2026-04-05 01:36:48', '2026-04-05 01:36:48', 'Affordable adventures without compromising on wildlife. Camping and basic lodges.', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('3', 'Mid-Range', 'mid-range', '2026-04-05 01:36:48', '2026-04-05 01:36:48', 'Comfortable lodges with quality service, great food, and prime locations.', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('4', 'Ultra-Luxury', 'ultra-luxury', '2026-04-05 01:36:48', '2026-04-05 01:36:48', 'The finest safari experiences — private concessions, personal butlers, and helicopter transfers.', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('5', 'Backpacker', 'backpacker', '2026-04-05 01:36:48', '2026-04-05 01:36:48', 'No-frills group safaris and shared camping for budget-conscious travellers.', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('6', 'Group Tour', 'group-tour', '2026-04-05 01:36:48', '2026-04-05 01:36:48', 'Scheduled departures with shared vehicles and like-minded travellers.', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('7', 'Private Safari', 'private-safari', '2026-04-05 01:36:48', '2026-04-05 01:36:48', 'Exclusive use of vehicle and guide — your schedule, your pace.', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('8', 'Solo Traveller', 'solo-traveller', '2026-04-05 01:36:49', '2026-04-05 01:36:49', 'Tailored itineraries and room-share options for independent explorers.', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('9', 'Couple Getaway', 'couple-getaway', '2026-04-05 01:36:49', '2026-04-05 01:36:49', 'Romantic packages with sunset drives, spa treatments, and intimate dining.', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('10', 'Premium', 'premium', '2026-04-05 01:36:49', '2026-04-05 01:36:49', 'A step above mid-range — boutique lodges, private guides, and curated touches.', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

DROP TABLE IF EXISTS `chat_messages`;
CREATE TABLE `chat_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `chat_session_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `sender_type` enum('visitor','agent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'visitor',
  `message_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `whisper_to` bigint unsigned DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chat_messages_chat_session_id_foreign` (`chat_session_id`),
  KEY `chat_messages_user_id_foreign` (`user_id`),
  KEY `chat_messages_whisper_to_foreign` (`whisper_to`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `chat_messages` (`id`, `chat_session_id`, `user_id`, `sender_type`, `message_type`, `whisper_to`, `message`, `is_read`, `created_at`, `updated_at`) VALUES
('1', '1', '1', 'agent', 'normal', NULL, 'hello', '0', '2026-04-04 19:39:17', '2026-04-04 19:39:17'),
('2', '2', NULL, 'visitor', 'normal', NULL, 'hello', '1', '2026-04-04 19:44:12', '2026-04-04 19:44:19'),
('3', '2', '1', 'agent', 'normal', NULL, 'hey', '0', '2026-04-04 19:44:23', '2026-04-04 19:44:23'),
('4', '2', NULL, 'visitor', 'normal', NULL, 'i wanted to know more about safari in tanzania', '1', '2026-04-04 19:45:02', '2026-04-04 20:13:59'),
('5', '2', '1', 'agent', 'normal', NULL, 'i see i can help you link', '0', '2026-04-04 19:45:25', '2026-04-04 19:45:25'),
('6', '3', '1', 'agent', 'whisper', NULL, 'msaada jaman', '0', '2026-04-04 23:47:14', '2026-04-04 23:47:14'),
('7', '3', NULL, 'visitor', 'normal', NULL, 'hello', '1', '2026-04-04 23:59:08', '2026-04-05 00:02:11'),
('8', '3', '1', 'agent', 'normal', NULL, 'yes', '0', '2026-04-04 23:59:20', '2026-04-04 23:59:20'),
('9', '3', '1', 'agent', 'normal', NULL, 'msaada', '0', '2026-04-04 23:59:48', '2026-04-04 23:59:48'),
('10', '3', '1', 'agent', 'whisper', NULL, 'sasa', '0', '2026-04-05 00:01:31', '2026-04-05 00:01:31'),
('11', '3', '1', 'agent', 'whisper', NULL, 'sasa', '0', '2026-04-05 00:01:51', '2026-04-05 00:01:51'),
('12', '4', NULL, 'visitor', 'normal', NULL, 'hello', '1', '2026-04-05 00:42:35', '2026-04-05 00:42:43'),
('13', '4', '3', 'agent', 'normal', NULL, 'hello lisa', '0', '2026-04-05 00:42:49', '2026-04-05 00:42:49'),
('14', '4', '3', 'agent', 'whisper', NULL, 'help', '0', '2026-04-05 00:43:00', '2026-04-05 00:43:00'),
('15', '4', NULL, 'visitor', 'normal', NULL, 'okay', '1', '2026-04-05 00:47:55', '2026-04-05 00:48:04'),
('16', '4', '3', 'agent', 'normal', NULL, 'i see let tranfer you to my colique', '0', '2026-04-05 00:48:24', '2026-04-05 00:48:24'),
('17', '4', '3', 'agent', 'system', NULL, 'scop kariah transferred this chat to Super Admin', '0', '2026-04-05 00:48:47', '2026-04-05 00:48:47'),
('18', '4', NULL, 'visitor', 'normal', NULL, 'thanks', '1', '2026-04-05 00:49:23', '2026-04-05 00:49:43'),
('19', '5', '3', 'agent', 'normal', NULL, 'hello', '0', '2026-04-05 15:16:53', '2026-04-05 15:16:53'),
('20', '5', NULL, 'visitor', 'normal', NULL, 'hello', '1', '2026-04-05 15:17:26', '2026-04-05 15:17:36'),
('21', '5', NULL, 'visitor', 'normal', NULL, 'what is safari packages you offer', '1', '2026-04-05 15:18:21', '2026-04-05 16:29:33'),
('22', '5', '3', 'agent', 'whisper', NULL, 'wazee msaada', '0', '2026-04-05 15:18:49', '2026-04-05 15:18:49'),
('23', '5', '1', 'agent', 'whisper', NULL, 'tuma hapa', '0', '2026-04-05 15:19:28', '2026-04-05 15:19:28'),
('24', '5', '3', 'agent', 'system', NULL, 'scop kariah transferred this chat to Super Admin', '0', '2026-04-05 15:19:46', '2026-04-05 15:19:46'),
('25', '5', '1', 'agent', 'whisper', NULL, 'hello', '0', '2026-04-05 15:20:02', '2026-04-05 15:20:02'),
('26', '5', '1', 'agent', 'normal', NULL, 'hello', '0', '2026-04-05 15:20:11', '2026-04-05 15:20:11');

DROP TABLE IF EXISTS `chat_sessions`;
CREATE TABLE `chat_sessions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `visitor_id` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `visitor_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visitor_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visitor_ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','closed','missed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `assigned_to` bigint unsigned DEFAULT NULL,
  `transferred_from` bigint unsigned DEFAULT NULL,
  `transfer_note` text COLLATE utf8mb4_unicode_ci,
  `department_id` bigint unsigned DEFAULT NULL,
  `page_history` json DEFAULT NULL,
  `current_page` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_activity_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chat_sessions_user_id_foreign` (`user_id`),
  KEY `chat_sessions_assigned_to_foreign` (`assigned_to`),
  KEY `chat_sessions_visitor_id_index` (`visitor_id`),
  KEY `chat_sessions_transferred_from_foreign` (`transferred_from`),
  KEY `chat_sessions_department_id_foreign` (`department_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `chat_sessions` (`id`, `user_id`, `visitor_id`, `visitor_name`, `visitor_email`, `visitor_ip`, `user_agent`, `status`, `assigned_to`, `transferred_from`, `transfer_note`, `department_id`, `page_history`, `current_page`, `last_activity_at`, `created_at`, `updated_at`) VALUES
('1', NULL, 'a371cabb-bd42-4686-8e0a-7f3850c3009c', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 Avira/145.0.34271.162', 'active', '1', NULL, NULL, NULL, '[null]', NULL, '2026-04-04 19:39:17', '2026-04-04 19:37:27', '2026-04-04 19:39:17'),
('2', NULL, '6ad144de-c567-4131-a93d-d4a80d36f1a2', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'active', '1', NULL, NULL, NULL, '[null]', NULL, '2026-04-04 19:45:25', '2026-04-04 19:39:55', '2026-04-04 19:45:25'),
('3', NULL, '35b42774-84cc-4109-9197-d95943611cb0', 'scopkariah', 'scopkariah@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'active', '1', NULL, NULL, NULL, '[\"http://127.0.0.1:8000/en\"]', 'http://127.0.0.1:8000/en', '2026-04-04 23:59:48', '2026-04-04 23:46:43', '2026-04-04 23:59:48'),
('4', NULL, '464f3ce8-a37a-494a-ae87-93fce54954bc', 'lissa', 'lisslamode8@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'closed', '1', '3', 'scop', NULL, '[\"http://127.0.0.1:8000/en\"]', 'http://127.0.0.1:8000/en', '2026-04-05 00:49:23', '2026-04-05 00:42:30', '2026-04-05 15:14:47'),
('5', NULL, '3acb9092-1719-46ed-80f6-3ac74689dcf3', 'juma', 'towncolorsmail@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'active', '1', '3', NULL, NULL, '[\"http://127.0.0.1:8000/en\"]', 'http://127.0.0.1:8000/en', '2026-04-05 15:20:11', '2026-04-05 15:16:12', '2026-04-05 15:20:11'),
('6', NULL, '8df55afb-cbbc-4b60-a43c-c1b34032bd4d', 'jujm', 'Roger@SafarisWithAHeart.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 Avira/145.0.34271.162', 'active', NULL, NULL, NULL, NULL, '[\"http://127.0.0.1:8000/en/destinations\"]', 'http://127.0.0.1:8000/en/destinations', '2026-04-05 18:24:06', '2026-04-05 18:24:06', '2026-04-05 18:24:06');

DROP TABLE IF EXISTS `countries`;
CREATE TABLE `countries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `featured_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `latitude` decimal(10,2) DEFAULT NULL,
  `longitude` decimal(10,2) DEFAULT NULL,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keywords` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `countries` (`id`, `name`, `slug`, `description`, `featured_image`, `created_at`, `updated_at`, `latitude`, `longitude`, `meta_title`, `meta_description`, `meta_keywords`, `og_image`) VALUES
('1', 'Tanzania', 'tanzania', NULL, 'countries/b3pjg3h4UktOoCh0ISZ9YcnsiEocC599rWwqrfkp.png', '2026-03-27 07:37:38', '2026-03-27 10:42:09', '-6.27', '34.82', NULL, NULL, NULL, NULL);

DROP TABLE IF EXISTS `country_safari_package`;
CREATE TABLE `country_safari_package` (
  `country_id` bigint unsigned NOT NULL,
  `safari_package_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`country_id`,`safari_package_id`),
  KEY `safari_package_id` (`safari_package_id`),
  CONSTRAINT `country_safari_package_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `country_safari_package_ibfk_2` FOREIGN KEY (`safari_package_id`) REFERENCES `safari_packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `country_safari_package` (`country_id`, `safari_package_id`) VALUES
('1', '2'),
('1', '3');

DROP TABLE IF EXISTS `departments`;
CREATE TABLE `departments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#083321',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `destination_safari_package`;
CREATE TABLE `destination_safari_package` (
  `destination_id` bigint unsigned NOT NULL,
  `safari_package_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`destination_id`,`safari_package_id`),
  KEY `safari_package_id` (`safari_package_id`),
  CONSTRAINT `destination_safari_package_ibfk_1` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `destination_safari_package_ibfk_2` FOREIGN KEY (`safari_package_id`) REFERENCES `safari_packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `destination_safari_package` (`destination_id`, `safari_package_id`) VALUES
('3', '2'),
('4', '2'),
('5', '2'),
('1', '3'),
('2', '3'),
('4', '3');

DROP TABLE IF EXISTS `destinations`;
CREATE TABLE `destinations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `country_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `featured_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `latitude` decimal(10,2) DEFAULT NULL,
  `longitude` decimal(10,2) DEFAULT NULL,
  `name_translations` text COLLATE utf8mb4_unicode_ci,
  `description_translations` text COLLATE utf8mb4_unicode_ci,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keywords` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `country_id` (`country_id`),
  CONSTRAINT `destinations_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `destinations` (`id`, `country_id`, `name`, `slug`, `description`, `featured_image`, `created_at`, `updated_at`, `latitude`, `longitude`, `name_translations`, `description_translations`, `meta_title`, `meta_description`, `meta_keywords`, `og_image`) VALUES
('1', '1', 'Serengeti National Park', 'serengeti-national-park', NULL, 'destinations/WN835Z2GeGeJr8burSgC4Vkk5QgGSc9DkJzzlfi0.jpg', '2026-03-27 07:38:03', '2026-03-31 17:04:23', '-2.33', '34.83', NULL, NULL, NULL, NULL, NULL, NULL),
('2', '1', 'Ngorongoro Crater', 'ngorongoro-crater', 'Ngorongoro Crater is one of the world???s largest intact volcanic calderas and a UNESCO World Heritage Site. Often referred to as ???Africa???s Garden of Eden,??? it hosts a dense population of wildlife including lions, rhinos, elephants, buffalos, and hippos. The crater???s unique ecosystem makes it a must-see on any Tanzania safari. With a mix of grasslands, forests, and lakes, it offers incredible game viewing all year round. The dry season (June to October) is best for visibility, while the green season (November to May) offers dramatic scenery.', 'destinations/HqMdNiFFepcNlQDKolaeVnfaIAzZbMkF1bHvBh4n.jpg', '2026-03-27 09:08:16', '2026-03-28 10:50:23', '-3.17', '35.57', NULL, NULL, NULL, NULL, NULL, NULL),
('3', '1', 'Arusha National Park', 'arusha-national-park', NULL, 'destinations/UjfJnNP4B26JfubB7AGOMygoTKZHBj2VgrrbVF6j.jpg', '2026-03-27 09:09:34', '2026-03-27 10:45:02', '-3.26', '36.85', NULL, NULL, NULL, NULL, NULL, NULL),
('4', '1', 'Arusha', 'arusha', NULL, 'destinations/BuM271d9OojuksaVdn77Djn9f7B58dSLRDxMmUfi.jpg', '2026-03-27 11:31:14', '2026-03-27 11:31:14', '-3.37', '36.69', NULL, NULL, NULL, NULL, NULL, NULL),
('5', '1', 'Tarangire National Park', 'tarangire-national-park', NULL, 'destinations/FLPYMMs6VLHRHfbPIaBRrr9KdIh0OZtrKWRVHW57.jpg', '2026-03-27 11:34:18', '2026-03-27 11:34:18', '-4.16', '36.09', NULL, NULL, NULL, NULL, NULL, NULL),
('6', '1', 'Lake Manyara National Park', 'lake-manyara-national-park', NULL, 'destinations/FLPYMMs6VLHRHfbPIaBRrr9KdIh0OZtrKWRVHW57.jpg', '2026-03-27 12:36:52', '2026-04-02 12:01:28', '-3.61', '35.76', '{\"fr\":\"Parc national du lac Manyara\",\"de\":\"Lake Manyara Nationalpark\",\"es\":\"Parque nacional del Lago Manyara\"}', NULL, NULL, NULL, NULL, NULL),
('7', '1', 'Mount Kilimanjaro', 'mount-kilimanjaro', 'Africa\'s highest peak at 5,895m. A bucket-list trek through five distinct climate zones.', 'countries/b3pjg3h4UktOoCh0ISZ9YcnsiEocC599rWwqrfkp.png', '2026-04-05 01:36:48', '2026-04-05 19:26:18', '-3.07', '37.36', '{\"fr\":\"Mont Kilimandjaro\",\"de\":\"Kilimandscharo\",\"es\":\"Monte Kilimanjaro\"}', '{\"fr\":\"Le plus haut sommet d\'Afrique \\u00e0 5 895 m. Un trek de la liste des seaux \\u00e0 travers cinq zones climatiques distinctes.\",\"de\":\"Afrikas h\\u00f6chster Gipfel mit 5.895 m. Eine Wanderung auf der Bucket-List durch f\\u00fcnf verschiedene Klimazonen.\",\"es\":\"El pico m\\u00e1s alto de \\u00c1frica con 5.895 m. Un recorrido por cinco zonas clim\\u00e1ticas distintas.\"}', NULL, NULL, NULL, NULL),
('8', '1', 'Selous Game Reserve', 'selous-game-reserve', 'One of Africa\'s largest protected areas — wild dogs, boat safaris, and remote wilderness.', NULL, '2026-04-05 01:36:48', '2026-04-05 01:36:48', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('9', '1', 'Ruaha National Park', 'ruaha-national-park', 'Tanzania\'s largest national park — rugged landscapes and incredible predator density.', NULL, '2026-04-05 01:36:48', '2026-04-05 01:36:48', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `hero_settings`;
CREATE TABLE `hero_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `background_video` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_poster` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `overlay_opacity` decimal(10,2) NOT NULL DEFAULT '0.50',
  `autoplay` tinyint(1) NOT NULL DEFAULT '1',
  `transition_speed` bigint NOT NULL DEFAULT '5000',
  `hero_safari_ids` json DEFAULT NULL,
  `button_text` json DEFAULT NULL,
  `button_link` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `hero_settings` (`id`, `background_video`, `video_poster`, `overlay_opacity`, `autoplay`, `transition_speed`, `hero_safari_ids`, `button_text`, `button_link`, `created_at`, `updated_at`) VALUES
('1', 'accommodations/olU2N1yUihT8S2t0F9wszPVn8q6IJGGvKPDpzmEf.jpg', 'safaris/Sy6BdtdamfKGcVioraGTB6dbwtjfjZwRwVyYibru.png', '0.50', '1', '5000', NULL, NULL, NULL, '2026-04-02 23:42:57', '2026-04-02 23:43:19');

DROP TABLE IF EXISTS `hero_slides`;
CREATE TABLE `hero_slides` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `page_section_id` bigint unsigned NOT NULL,
  `label` text COLLATE utf8mb4_unicode_ci,
  `title` text COLLATE utf8mb4_unicode_ci,
  `subtitle` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_text` text COLLATE utf8mb4_unicode_ci,
  `button_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `next_up_text` text COLLATE utf8mb4_unicode_ci,
  `bg_color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bg_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_alt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` bigint NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `page_section_id` (`page_section_id`),
  CONSTRAINT `hero_slides_ibfk_1` FOREIGN KEY (`page_section_id`) REFERENCES `page_sections` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `hero_slides` (`id`, `page_section_id`, `label`, `title`, `subtitle`, `image`, `button_text`, `button_link`, `next_up_text`, `bg_color`, `bg_image`, `image_alt`, `order`, `created_at`, `updated_at`) VALUES
('2', '13', '{\"en\":\"LUXURY COLLECTION\",\"fr\":\"COLLECTION LUXE\",\"de\":\"LUXUS-KOLLEKTION\",\"es\":\"COLECCIu00d3N DE LUJO\"}', '{\"en\":\"Discover the Wild Heart of Tanzania\",\"fr\":\"Du00e9couvrez le Cu0153ur Sauvage de la Tanzanie\",\"de\":\"Entdecken Sie das Wilde Herz Tansanias\",\"es\":\"Descubra el Corazu00f3n Salvaje de Tanzania\"}', '{\"en\":\"Handcrafted luxury safaris through the Serengeti, Ngorongoro Crater, and beyond\",\"fr\":\"Safaris de luxe sur mesure u00e0 travers le Serengeti, le cratu00e8re du Ngorongoro et au-delu00e0\",\"de\":\"Handgefertigte Luxus-Safaris durch die Serengeti, den Ngorongoro-Krater und daru00fcber hinaus\",\"es\":\"Safaris de lujo artesanales por el Serengeti, el cru00e1ter del Ngorongoro y mu00e1s allu00e1\"}', NULL, '{\"en\":\"Explore Safaris\",\"fr\":\"Explorer les Safaris\",\"de\":\"Safaris Entdecken\",\"es\":\"Explorar Safaris\"}', '/en/safaris', '{\"en\":\"Luxury Private Journeys\",\"fr\":\"Voyages Privu00e9s de Luxe\",\"de\":\"Luxus-Privatreisen\",\"es\":\"Viajes Privados de Lujo\"}', '#083321', NULL, 'Luxury safari in the Serengeti', '0', '2026-04-04 09:27:46', '2026-04-04 09:32:52'),
('3', '13', '{\"en\":\"NEW EXPERIENCE\",\"fr\":\"NOUVELLE EXPu00c9RIENCE\",\"de\":\"NEUES ERLEBNIS\",\"es\":\"NUEVA EXPERIENCIA\"}', '{\"en\":\"Witness the Great Migration\",\"fr\":\"Assistez u00e0 la Grande Migration\",\"de\":\"Erleben Sie die Grou00dfe Migration\",\"es\":\"Sea Testigo de la Gran Migraciu00f3n\"}', '{\"en\":\"Follow millions of wildebeest across the endless Serengeti plains\",\"fr\":\"Suivez des millions de gnous u00e0 travers les plaines infinies du Serengeti\",\"de\":\"Folgen Sie Millionen von Gnus u00fcber die endlosen Serengeti-Ebenen\",\"es\":\"Siga millones de u00f1us a travu00e9s de las interminables llanuras del Serengeti\"}', NULL, '{\"en\":\"View Migration Safaris\",\"fr\":\"Voir les Safaris Migration\",\"de\":\"Migrations-Safaris Ansehen\",\"es\":\"Ver Safaris de Migraciu00f3n\"}', '/en/safaris', '{\"en\":\"Zanzibar Beach Escape\",\"fr\":\"Escapade Balnu00e9aire u00e0 Zanzibar\",\"de\":\"Zanzibar Strandausflug\",\"es\":\"Escapada a la Playa de Zanzu00edbar\"}', '#131414', NULL, 'Great wildebeest migration in Tanzania', '1', '2026-04-04 09:27:46', '2026-04-04 09:32:52'),
('4', '13', '{\"en\":\"EXCLUSIVE\",\"fr\":\"EXCLUSIF\",\"de\":\"EXKLUSIV\",\"es\":\"EXCLUSIVO\"}', '{\"en\":\"Zanzibar u2014 Where Safari Meets the Sea\",\"fr\":\"Zanzibar u2014 Ou00f9 le Safari Rencontre la Mer\",\"de\":\"Sansibar u2014 Wo Safari das Meer Trifft\",\"es\":\"Zanzu00edbar u2014 Donde el Safari se Encuentra con el Mar\"}', '{\"en\":\"End your adventure on pristine white-sand beaches and turquoise waters\",\"fr\":\"Terminez votre aventure sur des plages de sable blanc immaculu00e9\",\"de\":\"Beenden Sie Ihr Abenteuer an unberu00fchrten weiu00dfen Sandstru00e4nden\",\"es\":\"Termine su aventura en playas de arena blanca pru00edstina\"}', NULL, '{\"en\":\"Discover Zanzibar\",\"fr\":\"Du00e9couvrir Zanzibar\",\"de\":\"Sansibar Entdecken\",\"es\":\"Descubrir Zanzu00edbar\"}', '/en/safaris', '{\"en\":\"Serengeti Luxury Camp\",\"fr\":\"Camp de Luxe Serengeti\",\"de\":\"Serengeti Luxus-Camp\",\"es\":\"Campamento de Lujo Serengeti\"}', '#083321', NULL, 'Zanzibar beach tropical paradise', '2', '2026-04-04 09:27:46', '2026-04-04 09:32:52');

DROP TABLE IF EXISTS `inquiries`;
CREATE TABLE `inquiries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `safari_package_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `travel_date` date DEFAULT NULL,
  `number_of_people` bigint DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `inquiry_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inquiry',
  `contact_methods` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `safari_package_id` (`safari_package_id`),
  CONSTRAINT `inquiries_ibfk_1` FOREIGN KEY (`safari_package_id`) REFERENCES `safari_packages` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `inquiries` (`id`, `safari_package_id`, `name`, `email`, `phone`, `country`, `travel_date`, `number_of_people`, `message`, `status`, `created_at`, `updated_at`, `inquiry_type`, `contact_methods`) VALUES
('1', '2', 'Roger Emmanuel', 'Roger@SafarisWithAHeart.com', '255754853391', 'Tanzania', '2026-04-09', '3', 'sddssdsd', 'contacted', '2026-03-29 09:17:07', '2026-03-29 09:20:06', 'inquiry', NULL),
('2', '3', 'Mr Roger Emmanuel', 'scopkariah@gmail.com', '+255 758273300', NULL, NULL, NULL, 'axsdsdsdsdsdsd', 'new', '2026-03-31 15:30:11', '2026-03-31 15:30:11', 'inquiry', NULL);

DROP TABLE IF EXISTS `itineraries`;
CREATE TABLE `itineraries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `safari_package_id` bigint unsigned NOT NULL,
  `day_number` bigint NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `accommodation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `destination_id` bigint unsigned DEFAULT NULL,
  `accommodation_id` bigint unsigned DEFAULT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title_translations` text COLLATE utf8mb4_unicode_ci,
  `description_translations` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `destination_id` (`destination_id`),
  KEY `safari_package_id` (`safari_package_id`),
  KEY `accommodation_id` (`accommodation_id`),
  CONSTRAINT `itineraries_ibfk_1` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `itineraries_ibfk_2` FOREIGN KEY (`safari_package_id`) REFERENCES `safari_packages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `itineraries_ibfk_3` FOREIGN KEY (`accommodation_id`) REFERENCES `accommodations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=293 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `itineraries` (`id`, `safari_package_id`, `day_number`, `title`, `description`, `accommodation`, `created_at`, `updated_at`, `destination_id`, `accommodation_id`, `image_path`, `title_translations`, `description_translations`) VALUES
('283', '2', '1', 'ARRIVAL DAY', 'Upon arrival at the airport, you will be greeted by a professional guide who will be waiting to assist you. After your long flight, we offer a selection of coffee, tea, or hot chocolate, served with delicious snacks to help you refresh. You will then be driven to your hotel for further relaxation. In the evening, your guide will provide a safari briefing to prepare you for the exciting journey ahead.', NULL, '2026-04-02 23:55:49', '2026-04-02 23:55:49', '4', NULL, NULL, '{\"fr\":\"Date d\'arrivu00e9e\",\"de\":\"Anreisetag gu00fcltig\",\"es\":\"Fecha de llegada:\"}', '{\"fr\":\"u00c0 votre arrivu00e9e u00e0 l\'au00e9roport, vous serez accueilli par un guide professionnel qui vous attendra pour vous aider. Apru00e8s votre long vol, nous vous proposons une su00e9lection de cafu00e9, de thu00e9 ou de chocolat chaud, servis avec de du00e9licieuses collations pour vous aider u00e0 vous rafrau00eechir. Vous serez ensuite conduit u00e0 votre hu00f4tel pour vous du00e9tendre davantage. Dans la soiru00e9e, votre guide vous donnera un briefing sur le safari pour vous pru00e9parer au voyage passionnant qui vous attend.\",\"de\":\"Bei Ihrer Ankunft am Flughafen werden Sie von einem professionellen Reiseleiter begru00fcu00dft, der Ihnen gerne behilflich ist. Nach Ihrem langen Flug bieten wir Ihnen eine Auswahl an Kaffee, Tee oder heiu00dfer Schokolade, serviert mit leckeren Snacks zur Erfrischung. Anschlieu00dfend werden Sie zur weiteren Entspannung zu Ihrem Hotel gefahren. Am Abend bietet Ihnen Ihr Reiseleiter ein Safari-Briefing an, um Sie auf die aufregende Reise vorzubereiten.\",\"es\":\"Al llegar al aeropuerto, seru00e1s recibido por un guu00eda profesional que te estaru00e1 esperando para ayudarte. Despuu00e9s de su largo vuelo, ofrecemos una selecciu00f3n de cafu00e9, tu00e9 o chocolate caliente, servido con deliciosos bocadillos para ayudarlo a refrescarse. A continuaciu00f3n, nos dirigiremos a nuestro hotel para relajarnos. Por la noche, el guu00eda ofreceru00e1 una sesiu00f3n informativa de safari para prepararnos para el emocionante viaje que tenemos por delante.\"}'),
('284', '2', '2', 'ARUSHA NATIONAL PARK', 'After a leisurely breakfast at the lodge, you will receive a brief safari orientation, where we???ll cover all the essential information and answer any questions you may have about your upcoming adventure.\n\nWe will pack a delicious hot lunch in our picnic hamper before heading to our first destination???Arusha National Park.\n\nLunch will be served in the afternoon at one of the scenic picnic sites, accompanied by your choice of coffee, tea, hot chocolate, or soft drinks.\n\nDay???s Highlight\nGame drive in Arusha National Park, with a nature walking safari.', NULL, '2026-04-02 23:55:49', '2026-04-02 23:55:49', '3', NULL, NULL, NULL, NULL),
('285', '2', '3', 'TARANGIRE NATIONAL PARK', 'After a hearty breakfast, your driver-guide will pick you up from the hotel for a scenic drive to Tarangire National Park, famously known as the \"Land of Giants\" because of its impressive elephant herds. As we explore the vast savannahs and iconic baobab landscapes, you\'ll have the chance to see a variety of wildlife, including lions, leopards, giraffes, zebras, and numerous bird species.\n\nAround midday, we\'ll enjoy a packed lunch in a beautiful setting, surrounded by nature. Afterward, we???ll continue our journey to your lodge in Karatu, where you\'ll be treated to a delicious dinner and a restful overnight stay.\n\nMeals plan: Full Board', NULL, '2026-04-02 23:55:49', '2026-04-02 23:55:49', '5', NULL, NULL, NULL, NULL),
('286', '2', '4', 'KARATU - SERENGETI NATIONAL PARK', 'Start your day with a delicious breakfast at the lodge, then depart with a packed lunch as we journey into the legendary Serengeti National Park. Enjoy an exhilarating day of game drives, where you???ll have the opportunity to spot the Big Five and a variety of other wildlife roaming the vast savannah.\n\nTake a break to enjoy your packed lunch within the park???s stunning landscapes before continuing your adventure. In the evening, we???ll head to the camp, where a delicious dinner awaits, and you can unwind, ready for another exciting day of exploration.\n\nMeals plan: Full Board', NULL, '2026-04-02 23:55:49', '2026-04-02 23:55:49', '5', NULL, NULL, NULL, NULL),
('287', '3', '1', 'ARRIVAL DAY', 'Upon arrival at the airport, you will be greeted by a professional guide who will be waiting to assist you. After your long flight, we offer a selection of coffee, tea, or hot chocolate, served with delicious snacks to help you refresh. You will then be driven to your hotel for further relaxation. In the evening, your guide will provide a safari briefing to prepare you for the exciting journey ahead.', NULL, '2026-04-03 07:27:12', '2026-04-03 07:27:12', '4', '1', 'safaris/Sy6BdtdamfKGcVioraGTB6dbwtjfjZwRwVyYibru.png', '{\"fr\":\"Date d\'arrivu00e9e\",\"de\":\"Anreisetag gu00fcltig\",\"es\":\"Fecha de llegada:\"}', '{\"fr\":\"u00c0 votre arrivu00e9e u00e0 l\'au00e9roport, vous serez accueilli par un guide professionnel qui vous attendra pour vous aider. Apru00e8s votre long vol, nous vous proposons une su00e9lection de cafu00e9, de thu00e9 ou de chocolat chaud, servis avec de du00e9licieuses collations pour vous aider u00e0 vous rafrau00eechir. Vous serez ensuite conduit u00e0 votre hu00f4tel pour vous du00e9tendre davantage. Dans la soiru00e9e, votre guide vous donnera un briefing sur le safari pour vous pru00e9parer au voyage passionnant qui vous attend.\",\"de\":\"Bei Ihrer Ankunft am Flughafen werden Sie von einem professionellen Reiseleiter begru00fcu00dft, der Ihnen gerne behilflich ist. Nach Ihrem langen Flug bieten wir Ihnen eine Auswahl an Kaffee, Tee oder heiu00dfer Schokolade, serviert mit leckeren Snacks zur Erfrischung. Anschlieu00dfend werden Sie zur weiteren Entspannung zu Ihrem Hotel gefahren. Am Abend bietet Ihnen Ihr Reiseleiter ein Safari-Briefing an, um Sie auf die aufregende Reise vorzubereiten.\",\"es\":\"Al llegar al aeropuerto, seru00e1s recibido por un guu00eda profesional que te estaru00e1 esperando para ayudarte. Despuu00e9s de su largo vuelo, ofrecemos una selecciu00f3n de cafu00e9, tu00e9 o chocolate caliente, servido con deliciosos bocadillos para ayudarlo a refrescarse. A continuaciu00f3n, nos dirigiremos a nuestro hotel para relajarnos. Por la noche, el guu00eda ofreceru00e1 una sesiu00f3n informativa de safari para prepararnos para el emocionante viaje que tenemos por delante.\"}'),
('288', '3', '2', 'TARANGIRE NATIONAL PARK', 'After a hearty breakfast, your driver-guide will pick you up from the hotel for a scenic drive to Tarangire National Park, famously known as the \"Land of Giants\" for its impressive elephant herds. As we explore the vast savannahs and baobab landscapes, you\'ll encounter diverse wildlife, including lions, leopards, giraffes, zebras, and a variety of bird species. In the middle of the day, you\'ll enjoy a packed lunch amidst the stunning surroundings. Afterward, we\'ll head to your lodge in Karatu for a delicious dinner and a restful overnight stay.', NULL, '2026-04-03 07:27:12', '2026-04-03 07:27:12', '4', '2', 'itineraries/TFWru34zhV3YuOgBJsvJyYiBCX5hHev7GnpOXpfn.jpg', '{\"fr\":\"PARC NATIONAL DE TARANGIRE\",\"de\":\"TARANGIRE-NATIONALPARK\",\"es\":\"El Parque Nacional de Tarangire\"}', NULL),
('289', '3', '3', 'KARATU - SERENGETI NATIONAL PARK', 'Following a delicious breakfast at the lodge, depart with a packed lunch and head into the iconic Serengeti National Park. Spend the day on thrilling game drives, spotting the Big Five and other wildlife across the vast Savannah. Enjoy your packed lunch in the park, then drive to the Camp in the evening for a delicious dinner and a restful night, ready for another exciting day ahead.', NULL, '2026-04-03 07:27:12', '2026-04-03 07:27:12', '1', '3', 'itineraries/wJ9r3hX7cwdkNsmH8K7VL88qGTPa8jZ2xh3w0WMy.jpg', '{\"fr\":\"KARATU - PARC NATIONAL DU SERENGETI\",\"de\":\"KARATU - SERENGETI-NATIONALPARK\",\"es\":\"KARATU - PARQUE NACIONAL DEL SERENGETI\"}', '{\"fr\":\"Apru00e8s un du00e9licieux petit-du00e9jeuner au lodge, partez avec un panier-repas et dirigez-vous vers l\'emblu00e9matique parc national du Serengeti. Passez la journu00e9e sur des safaris passionnants, en apercevant les Big Five et d\'autres animaux sauvages u00e0 travers la vaste savane. Profitez de votre panier-repas dans le parc, puis dirigez-vous vers le camp dans la soiru00e9e pour un du00e9licieux du00eener et une nuit reposante, pru00eat pour une autre journu00e9e passionnante u00e0 venir.\",\"de\":\"Nach einem ku00f6stlichen Fru00fchstu00fcck in der Lodge fahren Sie mit einem Lunchpaket in den legendu00e4ren Serengeti-Nationalpark. Verbringen Sie den Tag auf spannenden Pirschfahrten und entdecken Sie die Big Five und andere Wildtiere in der weiten Savanne. Genieu00dfen Sie Ihr Lunchpaket im Park und fahren Sie dann abends zum Camp fu00fcr ein ku00f6stliches Abendessen und eine erholsame Nacht, bereit fu00fcr einen weiteren aufregenden Tag.\",\"es\":\"Despuu00e9s de un delicioso desayuno en el albergue, saldremos con un almuerzo para llevar y nos dirigiremos al emblemu00e1tico Parque Nacional del Serengeti. Pasa el du00eda en emocionantes paseos de caza, observando a los Cinco Grandes y otros animales salvajes en la vasta sabana. Disfrute de su almuerzo para llevar en el parque, luego conduzca al campamento por la noche para disfrutar de una deliciosa cena y una noche de descanso, listo para otro emocionante du00eda por delante.\"}'),
('290', '3', '4', 'FULL DAY IN SERENGETI NATIONAL PARK', 'Ready for another full day in the iconic Serengeti, home to Africa???s Big Five and the awe-inspiring Great Migration. Enjoy exhilarating game drives across the endless plains, witnessing the incredible wildlife and natural beauty that make the Serengeti world-famous. As the day winds down, head back to your Camp for a peaceful evening, savoring a delicious dinner and reflecting on the unforgettable wildlife encounters and stunning landscapes you???ve experienced.', NULL, '2026-04-03 07:27:12', '2026-04-03 07:27:12', '1', '3', 'itineraries/uk3f81rGtyx0ofjGzG0pifZg0ze5UuXNkNNt9OTQ.jpg', '{\"fr\":\"JOURNu00c9E COMPLu00c8TE DANS LE PARC NATIONAL DU SERENGETI\",\"de\":\"GANZER TAG IM SERENGETI-NATIONALPARK\",\"es\":\"Du00cdA COMPLETO EN EL PARQUE NACIONAL DEL SERENGETI\"}', '{\"fr\":\"Pru00eat pour une autre journu00e9e complu00e8te dans l\'emblu00e9matique Serengeti, qui abrite les Big Five d\'Afrique et l\'impressionnante Grande Migration. Profitez de safaris exaltants u00e0 travers les plaines sans fin, en admirant l\'incroyable faune et la beautu00e9 naturelle qui font la renommu00e9e mondiale du Serengeti. u00c0 la fin de la journu00e9e, retournez u00e0 votre camp pour une soiru00e9e paisible, savourant un du00e9licieux du00eener et ru00e9flu00e9chissant aux rencontres inoubliables avec la faune et aux paysages u00e9poustouflants que vous avez vu00e9cus.\",\"de\":\"Bereit fu00fcr einen weiteren Tag in der legendu00e4ren Serengeti, der Heimat von Afrikas Big Five und der beeindruckenden Great Migration. Genieu00dfen Sie aufregende Pirschfahrten durch die endlosen Ebenen und erleben Sie die unglaubliche Tierwelt und natu00fcrliche Schu00f6nheit, die die Serengeti weltberu00fchmt machen. Wenn der Tag zu Ende geht, kehren Sie zu Ihrem Camp zuru00fcck, um einen ruhigen Abend zu verbringen, ein ku00f6stliches Abendessen zu genieu00dfen und u00fcber die unvergesslichen Begegnungen mit Wildtieren und atemberaubenden Landschaften nachzudenken, die Sie erlebt haben.\",\"es\":\"Listos para otro du00eda completo en el emblemu00e1tico Serengeti, hogar de los Cinco Grandes de u00c1frica y la impresionante Gran Migraciu00f3n. Disfruta de emocionantes recorridos por las interminables llanuras, presenciando la increu00edble vida silvestre y la belleza natural que hacen que el Serengeti sea mundialmente famoso. Al terminar el du00eda, regresaremos al campamento para pasar una noche tranquila, saborear una deliciosa cena y reflexionar sobre los inolvidables encuentros con la vida silvestre y los impresionantes paisajes que hemos experimentado.\"}'),
('291', '3', '5', 'SERENGETI - NGORONGORO CRATER', 'Rise early for a hearty breakfast at your Camp, ready for another incredible day. Bid farewell to the Serengeti as you make your way to the breathtaking Ngorongoro Crater, a true wildlife paradise home to the Big Five and a variety of plains game. Explore the crater floor, immersing yourself in the sights and sounds of this unique ecosystem. Take a break for lunch at a picturesque picnic spot, surrounded by the serene beauty of nature.', NULL, '2026-04-03 07:27:12', '2026-04-03 07:27:12', '2', NULL, 'itineraries/J7j4r1j5TzoRrIkEhSDdgKAmHD3ZEdXGq13hhPMm.jpg', '{\"fr\":\"SERENGETI - CRATu00c8RE NGORONGORO\",\"de\":\"SERENGETI - NGORONGORO-KRATER\",\"es\":\"SERENGETI - CRu00c1TER DEL NGORONGORO\"}', '{\"fr\":\"Levez-vous tu00f4t pour un copieux petit-du00e9jeuner u00e0 votre camp, pru00eat pour une autre journu00e9e incroyable. Faites vos adieux au Serengeti en vous dirigeant vers l\'u00e9poustouflant cratu00e8re du Ngorongoro, vu00e9ritable paradis de la faune sauvage abritant les Big Five et une variu00e9tu00e9 de gibiers des plaines. Explorez le fond du cratu00e8re, immergez-vous dans les images et les sons de cet u00e9cosystu00e8me unique. Faites une pause du00e9jeuner dans un lieu de pique-nique pittoresque, entouru00e9 par la beautu00e9 sereine de la nature.\",\"de\":\"Stehen Sie fru00fch auf fu00fcr ein herzhaftes Fru00fchstu00fcck in Ihrem Camp, bereit fu00fcr einen weiteren unglaublichen Tag. Verabschieden Sie sich von der Serengeti, wu00e4hrend Sie sich auf den Weg zum atemberaubenden Ngorongoro-Krater machen, einem wahren Tierparadies, in dem die Big Five und eine Vielzahl von Wildtieren leben. Erkunden Sie den Kraterboden und tauchen Sie ein in die Sehenswu00fcrdigkeiten und Klu00e4nge dieses einzigartigen u00d6kosystems. Machen Sie eine Pause zum Mittagessen an einem malerischen Picknickplatz, umgeben von der ruhigen Schu00f6nheit der Natur.\",\"es\":\"Levu00e1ntate temprano para un buen desayuno en tu campamento, listo para otro du00eda increu00edble. Despu00eddete del Serengeti mientras te diriges al impresionante cru00e1ter del Ngorongoro, un verdadero parau00edso de la vida silvestre que alberga a los Cinco Grandes y una variedad de juegos de llanuras. Explora el suelo del cru00e1ter y sumu00e9rgete en las vistas y los sonidos de este ecosistema u00fanico. Tomaremos un descanso para almorzar en un pintoresco lugar de picnic, rodeado de la serena belleza de la naturaleza.\"}'),
('292', '3', '6', 'SERENGETI - NGORONGORO CRATER', 'Rise early for a hearty breakfast at your Camp, ready for another incredible day. Bid farewell to the Serengeti as you make your way to the breathtaking Ngorongoro Crater, a true wildlife paradise home to the Big Five and a variety of plains game. Explore the crater floor, immersing yourself in the sights and sounds of this unique ecosystem. Take a break for lunch at a picturesque picnic spot, surrounded by the serene beauty of nature.', NULL, '2026-04-03 07:27:12', '2026-04-03 07:27:12', '2', NULL, 'itineraries/kFvof7ZxqkqqBaSmTn66QaXchejNGfWHvjCAud93.png', '{\"fr\":\"SERENGETI - CRATu00c8RE NGORONGORO\",\"de\":\"SERENGETI - NGORONGORO-KRATER\",\"es\":\"SERENGETI - CRu00c1TER DEL NGORONGORO\"}', '{\"fr\":\"Levez-vous tu00f4t pour un copieux petit-du00e9jeuner u00e0 votre camp, pru00eat pour une autre journu00e9e incroyable. Faites vos adieux au Serengeti en vous dirigeant vers l\'u00e9poustouflant cratu00e8re du Ngorongoro, vu00e9ritable paradis de la faune sauvage abritant les Big Five et une variu00e9tu00e9 de gibiers des plaines. Explorez le fond du cratu00e8re, immergez-vous dans les images et les sons de cet u00e9cosystu00e8me unique. Faites une pause du00e9jeuner dans un lieu de pique-nique pittoresque, entouru00e9 par la beautu00e9 sereine de la nature.\",\"de\":\"Stehen Sie fru00fch auf fu00fcr ein herzhaftes Fru00fchstu00fcck in Ihrem Camp, bereit fu00fcr einen weiteren unglaublichen Tag. Verabschieden Sie sich von der Serengeti, wu00e4hrend Sie sich auf den Weg zum atemberaubenden Ngorongoro-Krater machen, einem wahren Tierparadies, in dem die Big Five und eine Vielzahl von Wildtieren leben. Erkunden Sie den Kraterboden und tauchen Sie ein in die Sehenswu00fcrdigkeiten und Klu00e4nge dieses einzigartigen u00d6kosystems. Machen Sie eine Pause zum Mittagessen an einem malerischen Picknickplatz, umgeben von der ruhigen Schu00f6nheit der Natur.\",\"es\":\"Levu00e1ntate temprano para un buen desayuno en tu campamento, listo para otro du00eda increu00edble. Despu00eddete del Serengeti mientras te diriges al impresionante cru00e1ter del Ngorongoro, un verdadero parau00edso de la vida silvestre que alberga a los Cinco Grandes y una variedad de juegos de llanuras. Explora el suelo del cru00e1ter y sumu00e9rgete en las vistas y los sonidos de este ecosistema u00fanico. Tomaremos un descanso para almorzar en un pintoresco lugar de picnic, rodeado de la serena belleza de la naturaleza.\"}');

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` bigint NOT NULL,
  `pending_jobs` bigint NOT NULL,
  `failed_jobs` bigint NOT NULL,
  `failed_job_ids` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` text COLLATE utf8mb4_unicode_ci,
  `cancelled_at` bigint DEFAULT NULL,
  `created_at` bigint NOT NULL,
  `finished_at` bigint DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` bigint NOT NULL,
  `reserved_at` bigint DEFAULT NULL,
  `available_at` bigint NOT NULL,
  `created_at` bigint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `native_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `flag` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` bigint NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `languages` (`id`, `name`, `code`, `native_name`, `flag`, `is_default`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
('1', 'English', 'en', 'English', '????????', '1', '1', '0', '2026-04-02 14:39:40', '2026-04-02 14:39:40'),
('2', 'French', 'fr', 'Fran??ais', '????????', '0', '1', '1', '2026-04-02 14:39:40', '2026-04-02 14:39:40'),
('3', 'German', 'de', 'Deutsch', '????????', '0', '1', '2', '2026-04-02 14:39:40', '2026-04-02 14:39:40'),
('4', 'Spanish', 'es', 'Espa??ol', '????????', '0', '1', '3', '2026-04-02 14:39:40', '2026-04-02 14:39:40');

DROP TABLE IF EXISTS `media`;
CREATE TABLE `media` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` bigint NOT NULL DEFAULT '0',
  `alt_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'public',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `media` (`id`, `filename`, `path`, `mime_type`, `size`, `alt_text`, `disk`, `created_at`, `updated_at`) VALUES
('1', 'SBT_172_original.webp', 'media/w3hC9c76LOmYxt3wtGRMYA6rgokYd9tlKSKx6cFs.webp', 'image/webp', '186468', NULL, 'public', '2026-04-02 00:34:53', '2026-04-02 00:34:53'),
('2', 'Sy6BdtdamfKGcVioraGTB6dbwtjfjZwRwVyYibru.png', 'safaris/Sy6BdtdamfKGcVioraGTB6dbwtjfjZwRwVyYibru.png', 'image/png', '389958', NULL, 'public', '2026-04-02 02:45:02', '2026-04-02 02:45:02'),
('3', 'J19HK7oWEeDQ2jyYrkRS7oFF5wmoPG1KiFNTSllc.png', 'safaris/J19HK7oWEeDQ2jyYrkRS7oFF5wmoPG1KiFNTSllc.png', 'image/png', '509827', NULL, 'public', '2026-04-02 02:45:02', '2026-04-02 02:45:02'),
('4', 'b3pjg3h4UktOoCh0ISZ9YcnsiEocC599rWwqrfkp.png', 'countries/b3pjg3h4UktOoCh0ISZ9YcnsiEocC599rWwqrfkp.png', 'image/png', '803539', NULL, 'public', '2026-04-02 02:45:02', '2026-04-02 02:45:02'),
('5', 'WN835Z2GeGeJr8burSgC4Vkk5QgGSc9DkJzzlfi0.jpg', 'destinations/WN835Z2GeGeJr8burSgC4Vkk5QgGSc9DkJzzlfi0.jpg', 'image/jpeg', '152924', NULL, 'public', '2026-04-02 02:45:02', '2026-04-02 02:45:02'),
('6', 'HqMdNiFFepcNlQDKolaeVnfaIAzZbMkF1bHvBh4n.jpg', 'destinations/HqMdNiFFepcNlQDKolaeVnfaIAzZbMkF1bHvBh4n.jpg', 'image/jpeg', '240801', NULL, 'public', '2026-04-02 02:45:02', '2026-04-02 02:45:02'),
('7', 'UjfJnNP4B26JfubB7AGOMygoTKZHBj2VgrrbVF6j.jpg', 'destinations/UjfJnNP4B26JfubB7AGOMygoTKZHBj2VgrrbVF6j.jpg', 'image/jpeg', '157860', NULL, 'public', '2026-04-02 02:45:02', '2026-04-02 02:45:02'),
('8', 'BuM271d9OojuksaVdn77Djn9f7B58dSLRDxMmUfi.jpg', 'destinations/BuM271d9OojuksaVdn77Djn9f7B58dSLRDxMmUfi.jpg', 'image/jpeg', '259512', NULL, 'public', '2026-04-02 02:45:02', '2026-04-02 02:45:02'),
('9', 'FLPYMMs6VLHRHfbPIaBRrr9KdIh0OZtrKWRVHW57.jpg', 'destinations/FLPYMMs6VLHRHfbPIaBRrr9KdIh0OZtrKWRVHW57.jpg', 'image/jpeg', '258661', NULL, 'public', '2026-04-02 02:45:02', '2026-04-02 02:45:02'),
('10', 'LG0UVwK7nC8TSZadXgzd0vcy0Agz8sgygMPIcldN.webp', 'tour-types/LG0UVwK7nC8TSZadXgzd0vcy0Agz8sgygMPIcldN.webp', 'image/webp', '186468', NULL, 'public', '2026-04-02 02:45:03', '2026-04-02 02:45:03'),
('11', 'f9J6LSaLM1mBoNKv4c4Cw6M4gyJTYOnBjW4CVeks.webp', 'blog/f9J6LSaLM1mBoNKv4c4Cw6M4gyJTYOnBjW4CVeks.webp', 'image/webp', '186468', NULL, 'public', '2026-04-02 02:45:03', '2026-04-02 02:45:03'),
('12', 'ILVAW5WoNGpPJOQKG0iaoF0BqbIdyFLcC1vg87Fq.webp', 'itineraries/ILVAW5WoNGpPJOQKG0iaoF0BqbIdyFLcC1vg87Fq.webp', 'image/webp', '186468', NULL, 'public', '2026-04-02 02:45:03', '2026-04-02 02:45:03'),
('13', 'TFWru34zhV3YuOgBJsvJyYiBCX5hHev7GnpOXpfn.jpg', 'itineraries/TFWru34zhV3YuOgBJsvJyYiBCX5hHev7GnpOXpfn.jpg', 'image/jpeg', '187143', NULL, 'public', '2026-04-02 02:45:03', '2026-04-02 02:45:03'),
('14', 'wJ9r3hX7cwdkNsmH8K7VL88qGTPa8jZ2xh3w0WMy.jpg', 'itineraries/wJ9r3hX7cwdkNsmH8K7VL88qGTPa8jZ2xh3w0WMy.jpg', 'image/jpeg', '341405', NULL, 'public', '2026-04-02 02:45:03', '2026-04-02 02:45:03'),
('15', 'uk3f81rGtyx0ofjGzG0pifZg0ze5UuXNkNNt9OTQ.jpg', 'itineraries/uk3f81rGtyx0ofjGzG0pifZg0ze5UuXNkNNt9OTQ.jpg', 'image/jpeg', '341405', NULL, 'public', '2026-04-02 02:45:03', '2026-04-02 02:45:03'),
('16', 'J7j4r1j5TzoRrIkEhSDdgKAmHD3ZEdXGq13hhPMm.jpg', 'itineraries/J7j4r1j5TzoRrIkEhSDdgKAmHD3ZEdXGq13hhPMm.jpg', 'image/jpeg', '187143', NULL, 'public', '2026-04-02 02:45:03', '2026-04-02 02:45:03'),
('17', 'kFvof7ZxqkqqBaSmTn66QaXchejNGfWHvjCAud93.png', 'itineraries/kFvof7ZxqkqqBaSmTn66QaXchejNGfWHvjCAud93.png', 'image/png', '382989', NULL, 'public', '2026-04-02 02:45:03', '2026-04-02 02:45:03'),
('18', 'JmpNefBXQA4e9YQ5sZim8boMGttrO2nUTo3kMIPI.jpg', 'accommodations/JmpNefBXQA4e9YQ5sZim8boMGttrO2nUTo3kMIPI.jpg', 'image/jpeg', '187143', NULL, 'public', '2026-04-02 02:45:03', '2026-04-02 02:45:03'),
('19', 'olU2N1yUihT8S2t0F9wszPVn8q6IJGGvKPDpzmEf.jpg', 'accommodations/olU2N1yUihT8S2t0F9wszPVn8q6IJGGvKPDpzmEf.jpg', 'image/jpeg', '341405', NULL, 'public', '2026-04-02 02:45:03', '2026-04-02 02:45:03'),
('20', 'o2XqO1iL5HgzAIgissdhhIqPFswRSAzi11MTjlGu.png', 'accommodations/o2XqO1iL5HgzAIgissdhhIqPFswRSAzi11MTjlGu.png', 'image/png', '382989', NULL, 'public', '2026-04-02 02:45:03', '2026-04-02 02:45:03'),
('21', '1mHWCry6qYMiTFC0Oid0S7GjahIGM8vhpMZFlkJw.png', 'accommodations/1mHWCry6qYMiTFC0Oid0S7GjahIGM8vhpMZFlkJw.png', 'image/png', '1093725', NULL, 'public', '2026-04-02 02:45:03', '2026-04-02 02:45:03'),
('22', 'UvKwSKHntNxzsFMSDnyhpys2EpI17ijz16Jwub5v.jpg', 'accommodations/UvKwSKHntNxzsFMSDnyhpys2EpI17ijz16Jwub5v.jpg', 'image/jpeg', '98611', NULL, 'public', '2026-04-02 02:45:03', '2026-04-02 02:45:03'),
('23', '3uMgXzDt3GKCkqAGwk8sqteNDZuWElnOPD8PRKYZ.jpg', 'accommodations/3uMgXzDt3GKCkqAGwk8sqteNDZuWElnOPD8PRKYZ.jpg', 'image/jpeg', '1169708', NULL, 'public', '2026-04-02 02:45:03', '2026-04-02 02:45:03'),
('24', 'G8ai80Dq8kXY2EvGZrkBoB2HAJEqr7PBvCGBZxZx.png', 'logo/G8ai80Dq8kXY2EvGZrkBoB2HAJEqr7PBvCGBZxZx.png', 'image/png', '89963', NULL, 'public', '2026-04-02 02:45:03', '2026-04-02 02:45:03'),
('25', 'WhatsApp Image 2026-02-20 at 10.59.04 (1).jpeg', 'media/bMZ53FIQttw8Ry71iaVhrvPLLYhYeATIXKumuann.jpg', 'image/jpeg', '187741', NULL, 'public', '2026-04-04 06:35:59', '2026-04-04 06:35:59');

DROP TABLE IF EXISTS `menu_items`;
CREATE TABLE `menu_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `open_in_new_tab` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `menu_items_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `menu_items` (`id`, `label`, `slug`, `url`, `is_enabled`, `open_in_new_tab`, `sort_order`, `created_at`, `updated_at`) VALUES
('1', 'Home', 'home', NULL, '1', '0', '1', '2026-04-05 16:32:42', '2026-04-05 16:32:42'),
('2', 'Destinations', 'destinations', NULL, '1', '0', '2', '2026-04-05 16:32:42', '2026-04-05 16:32:42'),
('3', 'Safaris', 'safaris', NULL, '1', '0', '3', '2026-04-05 16:32:42', '2026-04-05 16:32:42'),
('4', 'Experiences', 'experiences', NULL, '1', '0', '4', '2026-04-05 16:32:42', '2026-04-05 16:32:42'),
('5', 'Blog', 'blog', NULL, '1', '0', '5', '2026-04-05 16:32:42', '2026-04-05 16:32:42'),
('6', 'About', 'about', NULL, '1', '0', '6', '2026-04-05 16:32:42', '2026-04-05 16:32:42'),
('7', 'Contact', 'contact', NULL, '1', '0', '7', '2026-04-05 16:32:42', '2026-04-05 16:32:42');

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` bigint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
('1', '0001_01_01_000000_create_users_table', '1'),
('2', '0001_01_01_000001_create_cache_table', '1'),
('3', '0001_01_01_000002_create_jobs_table', '1'),
('4', '2026_03_27_004808_add_role_to_users_table', '2'),
('5', '2026_03_27_005721_create_safari_packages_table', '3'),
('6', '2026_03_27_011632_create_itineraries_table', '4'),
('7', '2026_03_27_011845_create_safari_images_table', '5'),
('8', '2026_03_27_011929_create_testimonials_table', '6'),
('9', '2026_03_27_013710_add_featured_to_safari_packages_table', '7'),
('10', '2026_03_27_014343_create_countries_table', '8'),
('11', '2026_03_27_014344_create_destinations_table', '8'),
('12', '2026_03_27_014431_create_country_safari_package_table', '8'),
('13', '2026_03_27_014432_create_destination_safari_package_table', '8'),
('14', '2026_03_27_014433_drop_destination_from_safari_packages_table', '8'),
('15', '2026_03_27_015414_add_coordinates_to_destinations_table', '9'),
('16', '2026_03_27_015415_add_destination_id_to_itineraries_table', '9'),
('17', '2026_03_27_103453_add_coordinates_to_countries_table', '10'),
('18', '2026_03_27_110112_create_tour_types_table', '11'),
('19', '2026_03_27_110120_create_categories_table', '11'),
('20', '2026_03_27_110143_add_taxonomy_foreign_keys_to_safari_packages_table', '11'),
('21', '2026_03_27_142912_drop_destination_string_from_itineraries_table', '12'),
('22', '2026_03_29_080649_create_inquiries_table', '13'),
('23', '2026_03_31_084656_add_inquiry_type_and_contact_methods_to_inquiries_table', '14'),
('24', '2026_03_31_090850_add_content_lists_to_safari_packages_table', '15'),
('25', '2026_03_31_092930_add_section_copy_fields_to_safari_packages_table', '16'),
('26', '2026_03_31_093500_create_accommodations_table', '17'),
('27', '2026_03_31_093501_create_accommodation_images_table', '17'),
('28', '2026_03_31_093502_add_accommodation_fields_to_itineraries_table', '17'),
('29', '2026_03_31_121500_add_editorial_fields_to_safari_packages_table', '18'),
('30', '2026_03_31_221059_create_settings_table', '19'),
('31', '2026_04_01_100000_create_safari_plans_table', '20'),
('32', '2026_04_01_100001_create_planner_settings_table', '20'),
('33', '2026_04_01_145604_create_agents_table', '21'),
('34', '2026_04_01_145607_create_bookings_table', '21'),
('35', '2026_04_01_162152_create_safari_requests_table', '22'),
('36', '2026_04_01_162154_create_request_responses_table', '22'),
('37', '2026_04_01_162448_make_safari_package_id_nullable_in_bookings', '23'),
('38', '2026_04_01_170822_add_notification_settings_to_settings_table', '24'),
('39', '2026_04_01_180835_add_translations_to_safari_packages_and_destinations', '25'),
('40', '2026_04_01_195039_create_pages_table', '26'),
('41', '2026_04_01_201609_create_blog_categories_table', '27'),
('42', '2026_04_01_201611_create_posts_table', '27'),
('43', '2026_04_01_234658_add_description_and_image_to_tour_types_and_categories', '28'),
('44', '2026_04_02_001523_create_media_table', '29'),
('45', '2026_04_02_005755_add_seo_fields_to_all_tables', '30'),
('46', '2026_04_02_140000_create_languages_table', '31'),
('47', '2026_04_02_140001_create_seo_meta_table', '31'),
('48', '2026_04_02_140002_create_seo_rankings_table', '31'),
('49', '2026_04_02_160000_add_translations_and_seo_to_taxonomies_and_accommodations', '32'),
('50', '2026_04_02_200001_create_seo_domination_tables', '33'),
('51', '2026_04_03_000001_add_type_to_pages_table', '34'),
('52', '2026_04_03_000002_create_page_sections_table', '34'),
('53', '2026_04_03_000003_create_hero_slides_table', '34'),
('54', '2026_04_03_100001_add_featured_fields_to_safari_packages_table', '35'),
('55', '2026_04_03_100002_create_hero_settings_table', '35'),
('56', '2026_04_03_200001_add_is_homepage_and_settings_to_pages_table', '36'),
('57', '2026_04_04_175306_create_destinations_table', '57'),
('58', '2026_04_04_200000_add_profile_fields_to_users_table', '58'),
('59', '2026_04_04_200001_create_chat_tables', '58'),
('60', '2026_04_04_200002_create_notifications_table', '59'),
('61', '2026_04_04_200003_add_chat_fields_to_settings_table', '59'),
('62', '2026_04_04_195122_add_bio_to_users_table', '60'),
('63', '2026_04_04_210001_create_departments_and_chat_upgrades', '61'),
('64', '2026_04_05_011102_add_safari_type_to_safari_packages_table', '62'),
('65', '2026_04_05_150200_add_branding_and_search_engine_fields_to_settings_table', '63'),
('66', '2026_04_05_150300_create_menu_items_table', '63'),
('67', '2026_04_05_100000_create_mockup_tables', '64'),
('68', '2026_04_06_200000_add_hero_safari_selection_and_button_to_hero_settings', '65');

DROP TABLE IF EXISTS `mockup_categories`;
CREATE TABLE `mockup_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mockup_categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `mockup_designs`;
CREATE TABLE `mockup_designs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `template_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `front_data` json NOT NULL,
  `back_data` json DEFAULT NULL,
  `preview_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mockup_designs_template_id_foreign` (`template_id`),
  KEY `mockup_designs_user_id_foreign` (`user_id`),
  CONSTRAINT `mockup_designs_template_id_foreign` FOREIGN KEY (`template_id`) REFERENCES `mockup_templates` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mockup_designs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `mockup_templates`;
CREATE TABLE `mockup_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `width` int unsigned NOT NULL DEFAULT '1050',
  `height` int unsigned NOT NULL DEFAULT '600',
  `has_back` tinyint(1) NOT NULL DEFAULT '0',
  `preview_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mockup_templates_slug_unique` (`slug`),
  KEY `mockup_templates_category_id_foreign` (`category_id`),
  CONSTRAINT `mockup_templates_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `mockup_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `page_sections`;
CREATE TABLE `page_sections` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `page_id` bigint unsigned NOT NULL,
  `section_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` bigint NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `data` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `page_id` (`page_id`),
  CONSTRAINT `page_sections_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `page_sections` (`id`, `page_id`, `section_type`, `order`, `is_active`, `data`, `created_at`, `updated_at`) VALUES
('13', '2', 'hero', '0', '1', '{\"slider_autoplay\":\"1\",\"slider_interval\":\"6000\"}', '2026-04-04 09:27:46', '2026-04-04 09:32:52'),
('14', '2', 'icon_features', '1', '1', '{\"heading\":{\"en\":\"Why Choose Lomo Africa\",\"fr\":\"Pourquoi Choisir Lomo Africa\",\"de\":\"Warum Lomo Africa Wu00e4hlen\",\"es\":\"Por Quu00e9 Elegir Lomo Africa\"},\"subheading\":{\"en\":\"Crafting unforgettable safari experiences since day one\",\"fr\":\"Cru00e9er des expu00e9riences de safari inoubliables depuis le premier jour\",\"de\":\"Unvergessliche Safari-Erlebnisse seit dem ersten Tag\",\"es\":\"Creando experiencias de safari inolvidables desde el primer du00eda\"},\"columns\":\"4\",\"bg_color\":\"#000000\",\"items\":[{\"icon\":\"heart\",\"title\":{\"en\":\"Handcrafted Safaris\",\"fr\":\"Safaris Sur Mesure\",\"de\":\"Handgefertigte Safaris\",\"es\":\"Safaris Artesanales\"},\"description\":{\"en\":\"Every journey is tailor-made to your dreams and preferences\",\"fr\":\"Chaque voyage est conu00e7u sur mesure selon vos ru00eaves\",\"de\":\"Jede Reise wird nach Ihren Tru00e4umen mau00dfgeschneidert\",\"es\":\"Cada viaje se adapta a sus sueu00f1os y preferencias\"}},{\"icon\":\"globe\",\"title\":{\"en\":\"Local Experts\",\"fr\":\"Experts Locaux\",\"de\":\"Lokale Experten\",\"es\":\"Expertos Locales\"},\"description\":{\"en\":\"Born and raised in Tanzania u2014 we know every hidden gem\",\"fr\":\"Nu00e9s et u00e9levu00e9s en Tanzanie u2014 nous connaissons chaque tru00e9sor cachu00e9\",\"de\":\"Geboren und aufgewachsen in Tansania u2014 wir kennen jeden verborgenen Schatz\",\"es\":\"Nacidos y criados en Tanzania u2014 conocemos cada joya oculta\"}},{\"icon\":\"shield\",\"title\":{\"en\":\"Luxury Lodges\",\"fr\":\"Lodges de Luxe\",\"de\":\"Luxus-Lodges\",\"es\":\"Lodges de Lujo\"},\"description\":{\"en\":\"Hand-picked 5-star lodges and tented camps in the wilderness\",\"fr\":\"Lodges 5 u00e9toiles su00e9lectionnu00e9s dans la nature sauvage\",\"de\":\"Handverlesene 5-Sterne-Lodges und Zeltcamps in der Wildnis\",\"es\":\"Lodges de 5 estrellas seleccionados en la naturaleza\"}},{\"icon\":\"clock\",\"title\":{\"en\":\"24/7 Support\",\"fr\":\"Support 24/7\",\"de\":\"24/7 Unterstu00fctzung\",\"es\":\"Soporte 24/7\"},\"description\":{\"en\":\"Round-the-clock assistance from booking to your last sunset\",\"fr\":\"Assistance 24h/24 de la ru00e9servation u00e0 votre dernier coucher de soleil\",\"de\":\"Rund-um-die-Uhr-Betreuung von der Buchung bis zum letzten Sonnenuntergang\",\"es\":\"Asistencia las 24 horas desde la reserva hasta su u00faltima puesta de sol\"}}]}', '2026-04-04 09:27:46', '2026-04-05 17:45:41'),
('15', '2', 'safari_grid', '2', '1', '{\"heading\":{\"en\":\"Featured Safari Journeys\",\"fr\":\"Safaris en Vedette\",\"de\":\"Ausgewu00e4hlte Safari-Reisen\",\"es\":\"Safaris Destacados\"},\"subheading\":{\"en\":\"Our most sought-after luxury experiences across Tanzania\",\"fr\":\"Nos expu00e9riences de luxe les plus recherchu00e9es en Tanzanie\",\"de\":\"Unsere gefragtesten Luxuserlebnisse in Tansania\",\"es\":\"Nuestras experiencias de lujo mu00e1s buscadas en Tanzania\"},\"count\":6,\"featured_only\":\"1\",\"columns\":\"3\",\"category_filter\":null,\"show_rating\":\"1\",\"slider_autoplay\":\"1\"}', '2026-04-04 09:27:46', '2026-04-04 09:32:52'),
('16', '2', 'destination_showcase', '3', '1', '{\"heading\":{\"en\":\"Explore Tanzania\'s Finest\",\"fr\":\"Explorez le Meilleur de la Tanzanie\",\"de\":\"Entdecken Sie Tansanias Bestes\",\"es\":\"Explore lo Mejor de Tanzania\"},\"subheading\":{\"en\":\"From the endless Serengeti to the spice island of Zanzibar\",\"fr\":\"Des plaines infinies du Serengeti u00e0 l\'u00eele aux u00e9pices de Zanzibar\",\"de\":\"Von der endlosen Serengeti bis zur Gewu00fcrzinsel Sansibar\",\"es\":\"Desde el interminable Serengeti hasta la isla de las especias de Zanzu00edbar\"},\"count\":6,\"featured_only\":\"1\",\"columns\":\"3\",\"category_filter\":null,\"show_rating\":\"1\",\"slider_autoplay\":\"1\"}', '2026-04-04 09:27:46', '2026-04-05 17:45:41'),
('17', '2', 'split_hero', '4', '1', '{\"heading\":{\"en\":\"Experience Africa Beyond Expectations\",\"fr\":\"Vivez l\'Afrique Au-Delu00e0 des Attentes\",\"de\":\"Erleben Sie Afrika Jenseits Aller Erwartungen\",\"es\":\"Viva u00c1frica Mu00e1s Allu00e1 de las Expectativas\"},\"subheading\":{\"en\":\"A journey designed around you\",\"fr\":\"Un voyage conu00e7u autour de vous\",\"de\":\"Eine Reise, die um Sie herum gestaltet wurde\",\"es\":\"Un viaje diseu00f1ado en torno a usted\"},\"body\":{\"en\":\"For over a decade, we have been guiding discerning travelers through Tanzania\'s most extraordinary landscapes. Every safari we craft is a deeply personal journey u2014 from private game drives at dawn to candlelit dinners under the stars. This is not just a trip. This is your story, written in the wild.\",\"fr\":\"Depuis plus d\'une du00e9cennie, nous guidons des voyageurs exigeants u00e0 travers les paysages les plus extraordinaires de Tanzanie. Chaque safari que nous cru00e9ons est un voyage profondu00e9ment personnel u2014 des safaris privu00e9s u00e0 l\'aube aux du00eeners aux chandelles sous les u00e9toiles.\",\"de\":\"Seit u00fcber einem Jahrzehnt fu00fchren wir anspruchsvolle Reisende durch Tansanias auu00dfergewu00f6hnlichste Landschaften. Jede Safari, die wir gestalten, ist eine zutiefst persu00f6nliche Reise u2014 von privaten Pirschfahrten bei Morgengrauen bis zu Abendessen bei Kerzenschein unter den Sternen.\",\"es\":\"Durante mu00e1s de una du00e9cada, hemos guiado a viajeros exigentes a travu00e9s de los paisajes mu00e1s extraordinarios de Tanzania. Cada safari que creamos es un viaje profundamente personal u2014 desde safaris privados al amanecer hasta cenas a la luz de las velas bajo las estrellas.\"},\"button_text\":{\"en\":\"Plan Your Safari\",\"fr\":\"Planifiez Votre Safari\",\"de\":\"Planen Sie Ihre Safari\",\"es\":\"Planifique Su Safari\"},\"button_url\":\"/en/plan-safari\",\"layout\":\"image_right\",\"image\":\"safaris/Sy6BdtdamfKGcVioraGTB6dbwtjfjZwRwVyYibru.png\",\"bg_color\":\"#083321\"}', '2026-04-04 09:27:46', '2026-04-04 09:35:26'),
('18', '2', 'testimonial_slider', '5', '1', '{\"heading\":{\"en\":\"What Our Guests Say\",\"fr\":\"Ce Que Disent Nos Invitu00e9s\",\"de\":\"Was Unsere Gu00e4ste Sagen\",\"es\":\"Lo Que Dicen Nuestros Huu00e9spedes\"},\"subheading\":{\"en\":\"Real stories from travelers who experienced the magic\",\"fr\":\"De vraies histoires de voyageurs qui ont vu00e9cu la magie\",\"de\":\"Echte Geschichten von Reisenden, die die Magie erlebt haben\",\"es\":\"Historias reales de viajeros que experimentaron la magia\"},\"count\":6,\"featured_only\":\"1\",\"columns\":\"3\",\"category_filter\":null,\"show_rating\":\"1\",\"slider_autoplay\":\"1\"}', '2026-04-04 09:27:46', '2026-04-04 09:32:52'),
('19', '2', 'image_gallery', '6', '1', '{\"heading\":{\"en\":\"Safari Moments\",\"fr\":\"Moments Safari\",\"de\":\"Safari-Momente\",\"es\":\"Momentos de Safari\"},\"gallery_layout\":\"grid\",\"lightbox\":\"1\",\"images\":\"itineraries/uk3f81rGtyx0ofjGzG0pifZg0ze5UuXNkNNt9OTQ.jpg, destinations/BuM271d9OojuksaVdn77Djn9f7B58dSLRDxMmUfi.jpg, destinations/HqMdNiFFepcNlQDKolaeVnfaIAzZbMkF1bHvBh4n.jpg, destinations/WN835Z2GeGeJr8burSgC4Vkk5QgGSc9DkJzzlfi0.jpg, countries/b3pjg3h4UktOoCh0ISZ9YcnsiEocC599rWwqrfkp.png, destinations/BuM271d9OojuksaVdn77Djn9f7B58dSLRDxMmUfi.jpg\"}', '2026-04-04 09:27:46', '2026-04-04 09:43:32'),
('20', '2', 'cta_banner', '7', '1', '{\"heading\":{\"en\":\"Start Planning Your Dream Safari\",\"fr\":\"Commencez u00e0 Planifier Votre Safari de Ru00eave\",\"de\":\"Beginnen Sie mit der Planung Ihrer Traumsafari\",\"es\":\"Comience a Planificar Su Safari Sou00f1ado\"},\"subheading\":{\"en\":\"Let our experts craft a bespoke journey tailored to your every wish. No templates u2014 just your story, written in the wild.\",\"fr\":\"Laissez nos experts cru00e9er un voyage sur mesure adaptu00e9 u00e0 chacun de vos souhaits.\",\"de\":\"Lassen Sie unsere Experten eine mau00dfgeschneiderte Reise nach Ihren Wu00fcnschen gestalten.\",\"es\":\"Deje que nuestros expertos elaboren un viaje a medida adaptado a cada uno de sus deseos.\"},\"button_text\":{\"en\":\"Plan My Safari\",\"fr\":\"Planifier Mon Safari\",\"de\":\"Meine Safari Planen\",\"es\":\"Planificar Mi Safari\"},\"button_url\":\"/en/plan-safari\",\"bg_color\":\"#083321\",\"bg_image\":null}', '2026-04-04 09:27:46', '2026-04-04 09:32:52'),
('21', '2', 'blog', '8', '1', '{\"heading\":{\"en\":\"Safari Insights & Stories\",\"fr\":\"Aperu00e7us & Histoires de Safari\",\"de\":\"Safari-Einblicke & Geschichten\",\"es\":\"Perspectivas e Historias de Safari\"},\"subheading\":{\"en\":\"Expert tips, travel guides, and tales from the African bush\",\"fr\":\"Conseils d\'experts, guides de voyage et ru00e9cits de la brousse africaine\",\"de\":\"Expertentipps, Reisefu00fchrer und Geschichten aus dem afrikanischen Busch\",\"es\":\"Consejos de expertos, guu00edas de viaje e historias de la sabana africana\"},\"count\":3,\"featured_only\":\"1\",\"columns\":\"3\",\"category_filter\":null,\"show_rating\":\"1\",\"slider_autoplay\":\"1\"}', '2026-04-04 09:27:46', '2026-04-04 09:32:52'),
('22', '1', 'split_hero', '0', '1', '{\"heading\":{\"en\":\"Your Journey. Our Passion.\",\"fr\":\"Votre Voyage. Notre Passion.\",\"de\":\"Ihre Reise. Unsere Leidenschaft.\",\"es\":\"Tu Viaje. Nuestra Pasiu00f3n.\"},\"subheading\":{\"en\":\"A Safari That Changes Lives\",\"fr\":\"Un Safari Qui Change des Vies\",\"de\":\"Eine Safari, Die Leben Veru00e4ndert\",\"es\":\"Un Safari Que Cambia Vidas\"},\"body\":{\"en\":\"<p>Lomo Tanzania Safaris is a locally owned and operated safari company based in Tanzania, offering authentic and personalized safari experiences across the country\'s most iconic destinations.</p>\",\"fr\":\"<p>Lomo Tanzania Safaris est une entreprise de safari locale basu00e9e en Tanzanie, offrant des expu00e9riences de safari authentiques et personnalisu00e9es u00e0 travers les destinations les plus emblu00e9matiques du pays.</p>\",\"de\":\"<p>Lomo Tanzania Safaris ist ein lokal gefu00fchrtes Safariunternehmen mit Sitz in Tansania, das authentische und personalisierte Safarierlebnisse an den ikonischsten Reisezielen des Landes anbietet.</p>\",\"es\":\"<p>Lomo Tanzania Safaris es una empresa de safari local con sede en Tanzania, que ofrece experiencias de safari autu00e9nticas y personalizadas en los destinos mu00e1s icu00f3nicos del pau00eds.</p>\"},\"button_text\":{\"en\":\"Start Planning Your Safari\",\"fr\":\"Planifiez Votre Safari\",\"de\":\"Planen Sie Ihre Safari\",\"es\":\"Planifica Tu Safari\"},\"button_url\":\"/custom-tour\",\"layout\":\"image_right\",\"image\":\"destinations/WN835Z2GeGeJr8burSgC4Vkk5QgGSc9DkJzzlfi0.jpg\",\"bg_color\":\"#000000\"}', '2026-04-04 10:25:57', '2026-04-04 10:30:02'),
('23', '1', 'image_text', '1', '1', '{\"heading\":{\"en\":\"Founded on Passion, Built on Experience\",\"fr\":\"Fondu00e9 sur la Passion, Construit sur l\'Expu00e9rience\",\"de\":\"Gegru00fcndet aus Leidenschaft, Aufgebaut auf Erfahrung\",\"es\":\"Fundada en la Pasiu00f3n, Construida en la Experiencia\"},\"body\":{\"en\":\"<p>Lomo Tanzania Safaris was founded by Mr. Erasto, a passionate Tanzanian wildlife guide with over 19 years of experience in the safari industry.</p><p>Born and raised in Tanzania, Erasto began his journey in 2003 at Selous Game Reserve. His deep love for wildlife led him to train at Mwewe Training College in Serengeti, where he qualified as a ranger.</p><p>Over the years, he worked with luxury camps across Serengeti, mastering the art of guiding and delivering unforgettable safari experiences.</p><p>During one safari, he met a Dutch couple who shared his vision u2014 together, they built Lomo Tanzania Safaris.</p>\",\"fr\":\"<p>Lomo Tanzania Safaris a u00e9tu00e9 fondu00e9 par M. Erasto, un guide passionnu00e9 de la faune tanzanienne avec plus de 19 ans d\'expu00e9rience dans l\'industrie du safari.</p><p>Nu00e9 et u00e9levu00e9 en Tanzanie, Erasto a commencu00e9 son parcours en 2003 u00e0 la Ru00e9serve de Selous. Son amour profond pour la faune l\'a conduit u00e0 se former au Mwewe Training College dans le Serengeti, ou00f9 il a obtenu son diplu00f4me de ranger.</p><p>Au fil des annu00e9es, il a travaillu00e9 avec des camps de luxe u00e0 travers le Serengeti, mau00eetrisant l\'art du guidage et offrant des expu00e9riences de safari inoubliables.</p><p>Lors d\'un safari, il a rencontru00e9 un couple nu00e9erlandais qui partageait sa vision u2014 ensemble, ils ont construit Lomo Tanzania Safaris.</p>\",\"de\":\"<p>Lomo Tanzania Safaris wurde von Herrn Erasto gegru00fcndet, einem leidenschaftlichen tansanischen Wildlife-Guide mit u00fcber 19 Jahren Erfahrung in der Safari-Branche.</p><p>In Tansania geboren und aufgewachsen, begann Erasto seine Reise 2003 im Selous Game Reserve. Seine tiefe Liebe zur Tierwelt fu00fchrte ihn zum Mwewe Training College im Serengeti, wo er sich als Ranger qualifizierte.</p><p>Im Laufe der Jahre arbeitete er mit Luxuscamps im Serengeti zusammen und perfektionierte die Kunst des Guidings und das Liefern unvergesslicher Safari-Erlebnisse.</p><p>Wu00e4hrend einer Safari traf er ein niederlu00e4ndisches Paar, das seine Vision teilte u2014 gemeinsam bauten sie Lomo Tanzania Safaris auf.</p>\",\"es\":\"<p>Lomo Tanzania Safaris fue fundada por el Sr. Erasto, un apasionado guu00eda de vida silvestre tanzano con mu00e1s de 19 au00f1os de experiencia en la industria del safari.</p><p>Nacido y criado en Tanzania, Erasto comenzu00f3 su viaje en 2003 en la Reserva de Selous. Su profundo amor por la vida silvestre lo llevu00f3 a formarse en el Mwewe Training College en Serengeti, donde se calificu00f3 como ranger.</p><p>A lo largo de los au00f1os, trabaju00f3 con campamentos de lujo en Serengeti, dominando el arte de guiar y ofrecer experiencias de safari inolvidables.</p><p>Durante un safari, conociu00f3 a una pareja holandesa que compartu00eda su visiu00f3n u2014 juntos, construyeron Lomo Tanzania Safaris.</p>\"},\"image\":\"countries/b3pjg3h4UktOoCh0ISZ9YcnsiEocC599rWwqrfkp.png\",\"layout\":\"image_left\"}', '2026-04-04 10:25:57', '2026-04-04 10:30:02'),
('24', '1', 'highlight', '2', '1', '[]', '2026-04-04 10:25:57', '2026-04-04 10:30:02'),
('25', '1', 'two_column_feature', '3', '1', '[]', '2026-04-04 10:25:57', '2026-04-04 10:30:02'),
('26', '1', 'icon_features', '4', '1', '{\"heading\":{\"en\":\"Why Choose Us\",\"fr\":\"Pourquoi Nous Choisir\",\"de\":\"Warum Uns Wu00e4hlen\",\"es\":\"Por Quu00e9 Elegirnos\"},\"subheading\":{\"en\":\"Five reasons travelers trust Lomo Tanzania Safaris\",\"fr\":\"Cinq raisons pour lesquelles les voyageurs font confiance u00e0 Lomo Tanzania Safaris\",\"de\":\"Fu00fcnf Gru00fcnde, warum Reisende Lomo Tanzania Safaris vertrauen\",\"es\":\"Cinco razones por las que los viajeros confu00edan en Lomo Tanzania Safaris\"},\"columns\":\"3\",\"bg_color\":\"#000000\",\"items\":[{\"icon\":\"shield\",\"title\":{\"en\":\"Locally Owned Experts\",\"fr\":\"Experts Locaux\",\"de\":\"Lokale Experten\",\"es\":\"Expertos Locales\"},\"description\":{\"en\":\"Born and raised in Tanzania, our team knows every trail, every animal behavior, and every hidden gem across the country.\",\"fr\":\"Nu00e9s et u00e9levu00e9s en Tanzanie, notre u00e9quipe connau00eet chaque sentier, chaque comportement animal et chaque joyau cachu00e9 du pays.\",\"de\":\"In Tansania geboren und aufgewachsen, kennt unser Team jeden Pfad, jedes Tierverhalten und jedes versteckte Juwel des Landes.\",\"es\":\"Nacidos y criados en Tanzania, nuestro equipo conoce cada sendero, cada comportamiento animal y cada joya oculta del pau00eds.\"}},{\"icon\":\"map\",\"title\":{\"en\":\"Tailor-Made Safaris\",\"fr\":\"Safaris Sur Mesure\",\"de\":\"Mau00dfgeschneiderte Safaris\",\"es\":\"Safaris a Medida\"},\"description\":{\"en\":\"Every safari is designed around your interests, pace, and budget. No cookie-cutter itineraries u2014 only personalized experiences.\",\"fr\":\"Chaque safari est conu00e7u autour de vos intu00e9ru00eats, votre rythme et votre budget. Pas d\'itinu00e9raires standardisu00e9s u2014 uniquement des expu00e9riences personnalisu00e9es.\",\"de\":\"Jede Safari wird nach Ihren Interessen, Ihrem Tempo und Ihrem Budget gestaltet. Keine Standardreisen u2014 nur personalisierte Erlebnisse.\",\"es\":\"Cada safari estu00e1 diseu00f1ado en torno a sus intereses, ritmo y presupuesto. Sin itinerarios estu00e1ndar u2014 solo experiencias personalizadas.\"}},{\"icon\":\"heart\",\"title\":{\"en\":\"Community Impact\",\"fr\":\"Impact Communautaire\",\"de\":\"Gemeinschaftliche Wirkung\",\"es\":\"Impacto Comunitario\"},\"description\":{\"en\":\"Every booking directly supports education, healthcare, and development in local Tanzanian communities.\",\"fr\":\"Chaque ru00e9servation soutient directement l\'u00e9ducation, la santu00e9 et le du00e9veloppement des communautu00e9s locales tanzaniennes.\",\"de\":\"Jede Buchung unterstu00fctzt direkt Bildung, Gesundheitswesen und Entwicklung in lokalen tansanischen Gemeinden.\",\"es\":\"Cada reserva apoya directamente la educaciu00f3n, la salud y el desarrollo en las comunidades locales de Tanzania.\"}},{\"icon\":\"camera\",\"title\":{\"en\":\"Attention to Detail\",\"fr\":\"Attention aux Du00e9tails\",\"de\":\"Liebe zum Detail\",\"es\":\"Atenciu00f3n al Detalle\"},\"description\":{\"en\":\"From your first inquiry to your last sunset game drive, we ensure every detail is taken care of with precision and warmth.\",\"fr\":\"De votre premiu00e8re demande u00e0 votre dernier safari au coucher du soleil, nous veillons u00e0 ce que chaque du00e9tail soit pris en charge avec pru00e9cision et chaleur.\",\"de\":\"Von Ihrer ersten Anfrage bis zu Ihrer letzten Pirschfahrt bei Sonnenuntergang sorgen wir dafu00fcr, dass jedes Detail mit Pru00e4zision und Wu00e4rme erledigt wird.\",\"es\":\"Desde su primera consulta hasta su u00faltimo safari al atardecer, nos aseguramos de que cada detalle sea atendido con precisiu00f3n y calidez.\"}},{\"icon\":\"globe\",\"title\":{\"en\":\"Sustainable Tourism\",\"fr\":\"Tourisme Durable\",\"de\":\"Nachhaltiger Tourismus\",\"es\":\"Turismo Sostenible\"},\"description\":{\"en\":\"We are committed to protecting Tanzania\'s wildlife and ecosystems through responsible and sustainable travel practices.\",\"fr\":\"Nous nous engageons u00e0 protu00e9ger la faune et les u00e9cosystu00e8mes de la Tanzanie gru00e2ce u00e0 des pratiques de voyage responsables et durables.\",\"de\":\"Wir setzen uns fu00fcr den Schutz der Tierwelt und u00d6kosysteme Tansanias durch verantwortungsvolle und nachhaltige Reisepraktiken ein.\",\"es\":\"Estamos comprometidos a proteger la vida silvestre y los ecosistemas de Tanzania a travu00e9s de pru00e1cticas de viaje responsables y sostenibles.\"}}]}', '2026-04-04 10:25:57', '2026-04-04 10:30:02'),
('27', '1', 'destinations', '5', '1', '{\"heading\":{\"en\":\"Where We Take You\",\"fr\":\"Ou00f9 Nous Vous Emmenons\",\"de\":\"Wohin Wir Sie Bringen\",\"es\":\"Adu00f3nde Te Llevamos\"},\"subheading\":{\"en\":\"Explore Tanzania\'s most iconic destinations with expert local guides\",\"fr\":\"Explorez les destinations les plus emblu00e9matiques de Tanzanie avec des guides locaux experts\",\"de\":\"Erkunden Sie Tansanias ikonischste Reiseziele mit erfahrenen lokalen Guides\",\"es\":\"Explora los destinos mu00e1s icu00f3nicos de Tanzania con guu00edas locales expertos\"},\"count\":6,\"featured_only\":\"1\",\"columns\":\"3\",\"category_filter\":null,\"show_rating\":\"1\",\"slider_autoplay\":\"1\"}', '2026-04-04 10:25:57', '2026-04-04 10:30:02'),
('28', '1', 'experience_grid', '6', '1', '[]', '2026-04-04 10:25:57', '2026-04-04 10:30:02'),
('29', '1', 'cta_banner', '7', '1', '{\"heading\":{\"en\":\"Join a Journey That Changes Lives\",\"fr\":\"Rejoignez un Voyage Qui Change des Vies\",\"de\":\"Werden Sie Teil einer Reise, Die Leben Veru00e4ndert\",\"es\":\"u00danete a un Viaje Que Cambia Vidas\"},\"subheading\":{\"en\":\"Every safari is more than a trip u2014 it\'s a connection, an experience, and an impact.\",\"fr\":\"Chaque safari est plus qu\'un voyage u2014 c\'est une connexion, une expu00e9rience et un impact.\",\"de\":\"Jede Safari ist mehr als eine Reise u2014 es ist eine Verbindung, ein Erlebnis und eine Wirkung.\",\"es\":\"Cada safari es mu00e1s que un viaje u2014 es una conexiu00f3n, una experiencia y un impacto.\"},\"button_text\":{\"en\":\"Start Planning\",\"fr\":\"Commencer u00e0 Planifier\",\"de\":\"Jetzt Planen\",\"es\":\"Empezar a Planificar\"},\"button_url\":\"/custom-tour\",\"bg_color\":\"#083321\",\"bg_image\":null}', '2026-04-04 10:25:57', '2026-04-04 10:30:02'),
('30', '3', 'text', '0', '0', '{\"heading\":{\"en\":\"Safari listing intro\",\"fr\":null,\"de\":null,\"es\":null},\"body\":{\"en\":\"<p>Edit this section from the backend to control the safari listing introduction, trust signals, or planning tips shown above the grid.<\\/p>\",\"fr\":null,\"de\":null,\"es\":null}}', '2026-04-05 19:13:32', '2026-04-05 19:19:14'),
('31', '4', 'text', '0', '1', '{\"heading\":{\"en\":\"Destinations intro\"},\"body\":{\"en\":\"<p>Use this editable intro block for destination highlights, planning advice, or seasonal guidance above the filters and cards.<\\/p>\"}}', '2026-04-05 19:13:32', '2026-04-05 19:13:32'),
('32', '5', 'text', '0', '1', '{\"heading\":{\"en\":\"Experiences intro\"},\"body\":{\"en\":\"<p>Introduce your signature experiences here and keep the content fully editable from the CMS.<\\/p>\"}}', '2026-04-05 19:13:32', '2026-04-05 19:13:32'),
('33', '6', 'text', '0', '1', '{\"heading\":{\"en\":\"Blog intro\"},\"body\":{\"en\":\"<p>Add editorial positioning, author notes, or featured-story copy for the blog landing page here.<\\/p>\"}}', '2026-04-05 19:13:32', '2026-04-05 19:13:32'),
('34', '7', 'text', '0', '1', '{\"heading\":{\"en\":\"Contact page intro\",\"fr\":\"Page de contact intro\",\"de\":null,\"es\":null},\"body\":{\"en\":\"<p>Add welcome text, support promises, office details, or travel-planning notes for the contact page here.<\\/p>\",\"fr\":\"Ajoutez un texte de bienvenue, des promesses d\'assistance, des informations sur le bureau ou des notes de planification de voyage pour la page de contact ici.\",\"de\":null,\"es\":null}}', '2026-04-05 19:13:32', '2026-04-05 19:17:42');

DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `sections` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `template` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default',
  `sort_order` bigint NOT NULL DEFAULT '0',
  `meta` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keywords` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'page',
  `is_homepage` tinyint(1) NOT NULL DEFAULT '0',
  `layout` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'full_width',
  `bg_color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `section_spacing` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `pages` (`id`, `title`, `slug`, `content`, `sections`, `status`, `template`, `sort_order`, `meta`, `created_at`, `updated_at`, `meta_title`, `meta_description`, `meta_keywords`, `og_image`, `type`, `is_homepage`, `layout`, `bg_color`, `section_spacing`) VALUES
('1', '{\"en\":\"About Us\",\"fr\":\"u00c0 Propos\",\"de\":\"u00dcber Uns\",\"es\":\"Sobre Nosotros\"}', 'about-us', '[]', '[{\"type\":\"hero\",\"order\":0,\"heading\":{\"en\":\"About\",\"fr\":\"u00c0 propos\",\"de\":\"u00dcber\",\"es\":\"\"},\"subheading\":{\"en\":\"about tza\",\"fr\":\"\",\"de\":\"u00fcber tza\",\"es\":\"\"},\"button_text\":{\"en\":\"\",\"fr\":\"\",\"de\":\"\",\"es\":\"\"},\"image\":\"safaris/Sy6BdtdamfKGcVioraGTB6dbwtjfjZwRwVyYibru.png\"},{\"type\":\"image_text\",\"order\":1,\"heading\":{\"en\":\"about us\",\"fr\":\"\",\"de\":\"u00fcber uns\",\"es\":\"\"},\"body\":{\"en\":\"Sequi reprehenderit aut exercitationem ac exercitation nostrud cupiditate mi facilisi, fuga quasi, commodi, litora consequuntur laboriosam iste ad? Minima ipsa semper est! Tempor vehicula vero, quisque? Massa quia hendrerit hac, assumenda animi incidunt fuga. Lectus, eiusmod. Egestas id, faucibus modi, maecenas cras, fames commodi, perspiciatis senectus mollis lacus, tincidunt ullamco nulla, diam fugiat anim atque explicabo, tortor cubilia facilis diam. Bibendum repellendus lacinia, quidem. Senectus, suscipit orci aliquam rerum nam, quia tenetur eget sociis eos congue venenatis laoreet orci rem magnam mollitia diamlorem suscipit? Minima purus dis impedit! Aptent esse aliquip eget tristique! Dolores nisl explicabo, ea magnam hendrerit? Officia.\r\n\r\nHac rutrum aliquid accusamus porttitor temporibus corrupti eum adipiscing nesciunt incidunt, conubia animi quam scelerisque integer corrupti nostra aliquet posuere. Magna, porttitor sequi minus praesentium! Tristique, quisquam! Aute, rhoncus vitae lacus nostrud. Similique, qui dictum impedit eget porro wisi. Sociosqu ab placerat parturient vestibulum mus tortor dictum ullamcorper, modi cillum, iusto tempus animi ut? Lectus elementum suspendisse nostrum vestibulum varius? Integer beatae tempore numquam unde? Ipsa quo eu hendrerit sem nibh diam feugiat magnam aptent pharetra donec ultrices, nisl sequi ornare consectetuer posuere quisquam? Pulvinar, facilisi repellendus exercitation? Molestias faucibus wisi, consequuntur modi irure! Sunt dui adipisci quae scelerisque sed.\r\n\r\nPorta ornare ultricies doloribus purus fugit ipsum, condimentum ridiculus ac labore! Veritatis, eiusmod iaculis? Ullamco. Minim placerat sequi perferendis primis asperiores? Venenatis, purus egestas. Veritatis omnis optio, proident consequatur turpis, habitasse pharetra nostrum occaecat? Expedita illo ut sunt tellus? Euismod sapiente porttitor, pharetra congue! Aspernatur, nonummy nihil ut ridiculus optio? Hendrerit, nibh! Quis rerum officia adipiscing excepteur cursus bibendum possimus? Distinctio molestias urna, officiis massa exercitationem? Recusandae nascetur! Egestas quod, pretium laudantium, eget, pretium, quos officiis, sodales fugiat vitae pede. Mus irure morbi tellus distinctio tempora, laoreet ornare voluptatem, dictum? Error, nam tincidunt est maxime inceptos. Magni aliquid reiciendis cumque.\",\"fr\":\"\",\"de\":\"\",\"es\":\"\"},\"image\":\"media/w3hC9c76LOmYxt3wtGRMYA6rgokYd9tlKSKx6cFs.webp\",\"layout\":\"image_right\"},{\"type\":\"cta\",\"order\":2,\"heading\":{\"en\":\"book safari\",\"fr\":\"\",\"de\":\"safari buchen\",\"es\":\"\"},\"subheading\":{\"en\":\"Hendrerit est eu phasellus proin occaecati numquam dis, arcu ultrices nascetur porttitor in illo dolorem praesent doloribus etiam interdum mattis arcu minima? Est aspernatur. Placerat eveniet cillum possimus! Vitae earum\",\"fr\":\"\",\"de\":\"Hendrerit est eu phasellus proin occaecati numquam dis, arcu ultrices nascetur porttitor in illo dolorem praesent doloribus etiam interdum mattis arcu minima? Est aspernatur. Placerat eveniet cillum possimus! Vitae earum\",\"es\":\"\"},\"button_text\":{\"en\":\"\",\"fr\":\"\",\"de\":\"\",\"es\":\"\"}},{\"type\":\"text\",\"order\":3,\"heading\":{\"en\":\"Hendrerit est e\",\"fr\":\"\",\"de\":\"Hendrerit est e\",\"es\":\"\"},\"body\":{\"en\":\"Hendrerit est eu phasellus proin occaecati numquam dis, arcu ultrices nascetur porttitor in illo dolorem praesent doloribus etiam interdum mattis arcu minima? Est aspernatur. Placerat eveniet cillum possimus! Vitae earum\",\"fr\":\"\",\"de\":\"Hendrerit est eu phasellus proin occaecati numquam dis, arcu ultrices nascetur porttitor in illo dolorem praesent doloribus etiam interdum mattis arcu minima? Est aspernatur. Placerat eveniet cillum possimus! Vitae earum\",\"es\":\"\"}}]', 'published', 'default', '5', '{\"description\":null,\"keywords\":null}', '2026-04-01 21:36:45', '2026-04-04 10:30:02', 'Lomo Tanzania Safaris | Luxury African Safari Experts', 'Discover authentic Tanzania safaris with Lomo Tanzania Safaris. Locally owned, expertly guided, and designed to create unforgettable and meaningful travel experiences.', 'Tanzania safari, luxury safari Tanzania, Serengeti safari, Ngorongoro tours, African safari company, local safari operator Tanzania', NULL, 'page', '0', 'full_width', NULL, 'normal'),
('2', '{\"en\":\"Homepage\",\"fr\":null,\"de\":null,\"es\":null}', 'homepage', NULL, NULL, 'published', 'default', '0', '{\"description\":null,\"keywords\":null}', '2026-04-02 23:07:24', '2026-04-04 06:40:49', NULL, NULL, NULL, NULL, 'homepage', '1', 'full_width', '#ffffff', 'normal'),
('3', '{\"en\":\"Safaris\",\"fr\":\"Safaris\",\"de\":\"Safaris\",\"es\":\"Safaris\"}', 'safaris', NULL, NULL, 'published', 'default', '10', '{\"description\":\"Safari listing hero and intro content.\",\"keywords\":null}', '2026-04-05 19:13:32', '2026-04-05 19:19:14', NULL, NULL, NULL, NULL, 'system', '0', 'full_width', NULL, 'normal'),
('4', '{\"en\":\"Destinations\",\"fr\":\"Destinations\",\"de\":\"Destinations\",\"es\":\"Destinations\"}', 'destinations', NULL, NULL, 'published', 'default', '20', '{\"description\":\"Destination listing intro and filter copy.\"}', '2026-04-05 19:13:32', '2026-04-05 19:13:32', NULL, NULL, NULL, NULL, 'system', '0', 'full_width', NULL, 'normal'),
('5', '{\"en\":\"Experiences\",\"fr\":\"Experiences\",\"de\":\"Experiences\",\"es\":\"Experiences\"}', 'experiences', NULL, NULL, 'published', 'default', '30', '{\"description\":\"Experiences page messaging and support content.\"}', '2026-04-05 19:13:32', '2026-04-05 19:13:32', NULL, NULL, NULL, NULL, 'system', '0', 'full_width', NULL, 'normal'),
('6', '{\"en\":\"Blog\",\"fr\":\"Blog\",\"de\":\"Blog\",\"es\":\"Blog\"}', 'blog', NULL, NULL, 'published', 'default', '40', '{\"description\":\"Blog landing page intro content.\"}', '2026-04-05 19:13:32', '2026-04-05 19:13:32', NULL, NULL, NULL, NULL, 'system', '0', 'full_width', NULL, 'normal'),
('7', '{\"en\":\"Contact\",\"fr\":\"Contact\",\"de\":\"Contact\",\"es\":\"Contact\"}', 'contact', NULL, NULL, 'published', 'default', '50', '{\"description\":\"Contact page intro, support details, and map widgets.\",\"keywords\":null}', '2026-04-05 19:13:32', '2026-04-05 19:17:42', NULL, NULL, NULL, NULL, 'system', '0', 'full_width', NULL, 'normal');

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `planner_settings`;
CREATE TABLE `planner_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `step_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `options` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `planner_settings` (`id`, `step_key`, `title`, `description`, `options`, `created_at`, `updated_at`) VALUES
('1', 'intro', 'Your dream African safari starts here', 'Answer a few quick questions and our safari experts will craft a personalised itinerary tailored to your pace, interests, and budget.', NULL, '2026-04-01 13:33:24', '2026-04-01 13:33:24'),
('2', 'destinations', 'Where would you like to travel?', 'Select one or more destinations that interest you.', NULL, '2026-04-01 13:33:24', '2026-04-01 13:33:24'),
('3', 'travel_time', 'When would you like to travel?', 'Select your preferred travel months.', NULL, '2026-04-01 13:33:24', '2026-04-01 13:33:24'),
('4', 'travel_group', 'Who are you traveling with?', 'This helps us tailor your experience.', NULL, '2026-04-01 13:33:24', '2026-04-01 13:33:24'),
('5', 'interests', 'What experiences excite you most?', 'Select all that appeal to you.', NULL, '2026-04-01 13:33:24', '2026-04-01 13:33:24'),
('6', 'budget', 'What is your budget range?', 'Per person, approximate range.', '[\"$2,000 u2013 $5,000\",\"$5,000 u2013 $10,000\",\"$10,000 u2013 $20,000\",\"$20,000+\"]', '2026-04-01 13:33:24', '2026-04-01 13:33:24'),
('7', 'contact', 'How should we contact you?', 'Share your details so our team can reach out.', NULL, '2026-04-01 13:33:24', '2026-04-01 13:33:24');

DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `featured_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blog_category_id` bigint unsigned DEFAULT NULL,
  `author_id` bigint unsigned DEFAULT NULL,
  `meta` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `published_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keywords` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author_profile_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `author_id` (`author_id`),
  KEY `blog_category_id` (`blog_category_id`),
  KEY `author_profile_id` (`author_profile_id`),
  CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`blog_category_id`) REFERENCES `blog_categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `posts_ibfk_3` FOREIGN KEY (`author_profile_id`) REFERENCES `author_profiles` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `posts` (`id`, `title`, `slug`, `content`, `excerpt`, `featured_image`, `blog_category_id`, `author_id`, `meta`, `status`, `published_at`, `created_at`, `updated_at`, `meta_title`, `meta_description`, `meta_keywords`, `og_image`, `author_profile_id`) VALUES
('1', '{\"en\":\"Perferendis tenetur quod blandit, voluptates imperdiet.\",\"fr\":null,\"de\":null,\"es\":null}', 'perferendis-tenetur-quod-blandit-voluptates-imperdiet', '{\"en\":\"Vestibulum felis? Eos purus, aenean morbi parturient nonummy earum, facilisi possimus tempus. Minim lacus tempora? Volutpat ac nihil cupidatat proident. Doloremque! Lacinia occaecati iure, voluptatibus semper repudiandae beatae nesciunt ducimus pede tincidunt arcu iaculis! Fugit lacus, rutrum dolorum minus euismod mauris adipisicing ullam senectus neque? Et sapien pariatur duis cillum. Consequat? Dicta accumsan! Sociosqu tristique euismod! Diam voluptates fusce ante elementum lobortis, habitasse qui? Accumsan, nullam justo aliqua odit varius labore ipsa! Ipsam optio ac. Aliquip, rutrum illum, quis turpis? Lacinia tempus varius porttitor ipsa, lectus, incididunt, corporis! Elementum mi. Facere unde mattis interdum habitant facilisis commodi aptent, natus! Malesuada.\r\n\r\nEius dignissim? Eveniet duis wisi repellat repudiandae soluta leo expedita natoque dolores, ipsa nisi nam, lacus mauris! Cupiditate pariatur soluta minus diamlorem nascetur, nam excepturi nullam nisl aliquip. Maiores unde non aute, blanditiis diamlorem eligendi adipisicing nonummy illo quasi reiciendis facilisis quod recusandae placeat fames ab commodo arcu dolorum voluptates possimus adipisci repudiandae, magnis, quis? Placerat soluta nihil, nihil vulputate molestiae ad, posuere elit, varius! Optio, eu nemo commodi pulvinar iure, minima? Atque facilisi maxime? Aut ut quaerat ut lacinia. Eius volutpat necessitatibus? Porta, facere atque sed mi rutrum. Debitis, ea, auctor repudiandae in eu. Deserunt, quas incididunt iste quam.\r\n\r\nJusto sed pede. Dolor diamlorem sodales, luctus etiam? Arcu repellat magnis, magnis. Penatibus optio error minima diamlorem occaecati ridiculus luctus voluptatum aenean! Nesciunt quia pariatur similique accusamus dictumst distinctio qui, risus consequat. Distinctio bibendum dicta tenetur pharetra voluptate cillum donec! Sapiente diam, laboris feugiat adipiscing. Ligula. Nostrum cupiditate! Cubilia curae, odit, nemo totam ligula. Eveniet, do fringilla? Sint magnam pharetra distinctio quos sequi, elementum officiis laoreet dis vehicula, laborum vel, facere excepturi? Cubilia, aspernatur ultricies ridiculus accumsan curae? Cupidatat vel, varius semper perferendis faucibus lectus dapibus potenti mollit veritatis dolorum, illo proident, elit unde vel cras impedit vel harum hac.\",\"fr\":null,\"de\":null,\"es\":null}', '{\"en\":null,\"fr\":null,\"de\":null,\"es\":null}', 'blog/f9J6LSaLM1mBoNKv4c4Cw6M4gyJTYOnBjW4CVeks.webp', NULL, '1', '{\"meta_title\":{\"en\":null,\"fr\":null,\"de\":null,\"es\":null},\"meta_description\":{\"en\":null,\"fr\":null,\"de\":null,\"es\":null}}', 'published', '2026-04-01 22:12:54', '2026-04-01 22:12:54', '2026-04-01 22:12:54', NULL, NULL, NULL, NULL, NULL);

DROP TABLE IF EXISTS `request_responses`;
CREATE TABLE `request_responses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `request_id` bigint unsigned NOT NULL,
  `safari_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `request_id` (`request_id`),
  CONSTRAINT `request_responses_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `safari_requests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `request_responses` (`id`, `request_id`, `safari_title`, `description`, `price`, `notes`, `status`, `created_at`, `updated_at`) VALUES
('1', '1', '4 dats', 'asasasasaxassd', '7000.00', 'asasasasas', 'accepted', '2026-04-01 16:40:48', '2026-04-01 16:41:12');

DROP TABLE IF EXISTS `safari_images`;
CREATE TABLE `safari_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `safari_package_id` bigint unsigned NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `safari_package_id` (`safari_package_id`),
  CONSTRAINT `safari_images_ibfk_1` FOREIGN KEY (`safari_package_id`) REFERENCES `safari_packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `safari_images` (`id`, `safari_package_id`, `image_path`, `created_at`, `updated_at`) VALUES
('5', '2', 'safaris/gallery/qje770G5h1HG8wYXPjCTejIy4ZaNOWHnTLEqdFJm.jpg', '2026-03-27 08:54:31', '2026-03-27 08:54:31'),
('6', '2', 'safaris/gallery/4hUywc8BnBeXk5VfJwGQOCkMO8CbHKmBhRZRDqCa.jpg', '2026-03-27 08:54:31', '2026-03-27 08:54:31'),
('7', '2', 'safaris/gallery/2eqC6ZYruxikV8otn0hkolOEzgKmBq4emZFnpAYp.jpg', '2026-03-27 08:54:31', '2026-03-27 08:54:31'),
('8', '2', 'safaris/gallery/QiwsKiQJyaPTKsZdgNYZSBaV4yeqOr0m7Yj2ckGX.png', '2026-03-27 08:54:31', '2026-03-27 08:54:31'),
('9', '2', 'safaris/gallery/qahEpStvWsPtS8giDkSbI1AMyVNwrHQVp1CP5BAD.jpg', '2026-03-27 08:54:31', '2026-03-27 08:54:31'),
('10', '3', 'safaris/gallery/gW6lzogR3qEAZwK7ZrLre9voMbjjhNbS9PkBWtD1.jpg', '2026-03-27 13:23:10', '2026-03-27 13:23:10'),
('11', '3', 'safaris/gallery/pnnlT8GL0AGJDHg5CaPC5awiOdZweMB5G8V5OcXq.jpg', '2026-03-27 13:23:10', '2026-03-27 13:23:10'),
('12', '3', 'safaris/gallery/qYXHdanvgQ8RKd1HlbqwOLpB0iWALbIuISirnEKV.png', '2026-03-27 13:23:10', '2026-03-27 13:23:10'),
('13', '3', 'safaris/gallery/i1AHzE1eTH2c33WbDjUiEJ2GTJIE5mMxy70il5Ls.png', '2026-03-27 13:23:10', '2026-03-27 13:23:10'),
('14', '3', 'safaris/gallery/FiIVl9A51khhz46QcfWsHrWq2yDSPKD25nY1hJCn.png', '2026-03-27 13:23:10', '2026-03-27 13:23:10'),
('15', '3', 'safaris/gallery/nz29aqUfiqfg4RcuWQ5yoMRQV7d3P3XIgbGF2y6b.jpg', '2026-03-27 13:23:10', '2026-03-27 13:23:10');

DROP TABLE IF EXISTS `safari_packages`;
CREATE TABLE `safari_packages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `duration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tour_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `difficulty` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `featured_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `map_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `map_embed` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `safari_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'safari',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `tour_type_id` bigint unsigned DEFAULT NULL,
  `category_id` bigint unsigned DEFAULT NULL,
  `highlights` text COLLATE utf8mb4_unicode_ci,
  `included` text COLLATE utf8mb4_unicode_ci,
  `excluded` text COLLATE utf8mb4_unicode_ci,
  `highlights_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `highlights_intro` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inclusions_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inclusions_intro` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `overview_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seasonal_pricing` text COLLATE utf8mb4_unicode_ci,
  `title_translations` text COLLATE utf8mb4_unicode_ci,
  `short_description_translations` text COLLATE utf8mb4_unicode_ci,
  `description_translations` text COLLATE utf8mb4_unicode_ci,
  `highlights_translations` text COLLATE utf8mb4_unicode_ci,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keywords` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `highlights_title_translations` text COLLATE utf8mb4_unicode_ci,
  `highlights_intro_translations` text COLLATE utf8mb4_unicode_ci,
  `inclusions_title_translations` text COLLATE utf8mb4_unicode_ci,
  `inclusions_intro_translations` text COLLATE utf8mb4_unicode_ci,
  `overview_title_translations` text COLLATE utf8mb4_unicode_ci,
  `featured_order` bigint NOT NULL DEFAULT '0',
  `featured_label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tour_type_id` (`tour_type_id`),
  KEY `category_id` (`category_id`),
  KEY `safari_packages_safari_type_index` (`safari_type`),
  CONSTRAINT `safari_packages_ibfk_1` FOREIGN KEY (`tour_type_id`) REFERENCES `tour_types` (`id`) ON DELETE SET NULL,
  CONSTRAINT `safari_packages_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `safari_packages` (`id`, `title`, `slug`, `short_description`, `description`, `duration`, `tour_type`, `category`, `difficulty`, `price`, `currency`, `featured_image`, `video_url`, `map_image`, `map_embed`, `status`, `safari_type`, `created_at`, `updated_at`, `featured`, `tour_type_id`, `category_id`, `highlights`, `included`, `excluded`, `highlights_title`, `highlights_intro`, `inclusions_title`, `inclusions_intro`, `overview_title`, `seasonal_pricing`, `title_translations`, `short_description_translations`, `description_translations`, `highlights_translations`, `meta_title`, `meta_description`, `meta_keywords`, `og_image`, `highlights_title_translations`, `highlights_intro_translations`, `inclusions_title_translations`, `inclusions_intro_translations`, `overview_title_translations`, `featured_order`, `featured_label`) VALUES
('2', '8 Days Tanzania Luxury Safari', '8-days-tanzania-luxury-safari', 'Embark on an extraordinary 8-day luxury safari through Tanzania???s most iconic destinations, beginning in the vibrant city of Arusha and venturing deep into the heart of the Northern Safari Circuit. This unforgettable journey blends breathtaking landscap', '<p>&lt;p&gt;&amp;lt;p&amp;gt;&amp;amp;lt;p&amp;amp;gt;&amp;amp;amp;lt;p&amp;amp;amp;gt;&amp;amp;amp;amp;lt;p&amp;amp;amp;amp;gt;Embark on an extraordinary 8-day luxury safari through Tanzania???s most iconic destinations, beginning in the vibrant city of Arusha and venturing deep into the heart of the Northern Safari Circuit. This unforgettable journey blends breathtaking landscapes, extraordinary wildlife encounters, and rich cultural experiences, all while indulging in the comfort of Tanzania???s finest lodges and camps.&amp;amp;amp;amp;lt;/p&amp;amp;amp;amp;gt;&amp;amp;amp;lt;/p&amp;amp;amp;gt;&amp;amp;lt;/p&amp;amp;gt;&amp;lt;/p&amp;gt;&lt;/p&gt;</p>', '8 Days / 7 Nights', NULL, NULL, NULL, NULL, 'USD', 'safaris/Sy6BdtdamfKGcVioraGTB6dbwtjfjZwRwVyYibru.png', NULL, NULL, NULL, 'published', 'safari', '2026-03-27 08:54:31', '2026-04-02 23:55:49', '1', NULL, NULL, NULL, NULL, NULL, 'Highlights', 'The standout moments built into this safari.', 'Inclusions & Exclusions', 'A clear view of what is covered and what to plan for separately.', 'Experience Overview', NULL, '{\"fr\":\"Safari de luxe en Tanzanie de 8 jours\"}', '{\"fr\":\"Embarquez pour un extraordinaire safari de luxe de 8 jours u00e0 travers les destinations les plus emblu00e9matiques de Tanzanie, en commenu00e7ant par la ville animu00e9e d\'Arusha et en vous aventurant au cu0153ur du circuit Northern Safari. Ce voyage inoubliable allie des paysages u00e0 couper le souffle, des rencontres extraordinaires avec la faune et des expu00e9riences culturelles riches, tout en profitant du confort des meilleurs lodges et camps de Tanzanie.\"}', '{\"fr\":\"&lt;p&gt; &amp;lt;p&amp;gtu00a0;Embarquez pour un extraordinaire safari de luxe de 8 jours u00e0 travers les destinations les plus emblu00e9matiques de Tanzanie, en commenu00e7ant par la ville animu00e9e d\'Arusha et en vous aventurant au cu0153ur du circuit Northern Safari. Ce voyage inoubliable allie des paysages u00e0 couper le souffle, des rencontres extraordinaires avec la faune et des expu00e9riences culturelles riches, tout en profitant du confort des meilleurs lodges et camps de Tanzanie. &amp;lt;/p&amp;gtu00a0;&lt;/p&gt;\"}', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '{\"fr\":\"Aperu00e7u de l\'expu00e9rience\"}', '0', NULL),
('3', '6-Days Tanzania Safari Adventure', '6-days-tanzania-safari-adventure', 'This 6-day safari &amp;lt;/strong&amp;gt;takes you on an unforgettable journey through Tanzania???s most iconic parks: Tarangire, renowned for its elephants and baobab trees; the Serengeti, where the Great Migration unfolds amidst diverse predators;', '<p>arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania arusha tanzania i love tanzania </p>', '6 Days/ 5 Nights', NULL, NULL, NULL, NULL, 'USD', 'destinations/FLPYMMs6VLHRHfbPIaBRrr9KdIh0OZtrKWRVHW57.jpg', NULL, NULL, NULL, 'published', 'safari', '2026-03-27 13:11:27', '2026-04-03 07:27:12', '1', '1', '1', '[\"Big Five safari\",\"Luxury lodges\",\"Serengeti migration\"]', '[\"asasasasasasas\",\"asasasas\",\"as\",\"asasas\",\"as\",\"as\",\"asas\",\"as\",\"as\",\"sasas\"]', '[\"noooooooo\",\"noooooooooooooo\",\"nooooooooo\",\"noooooooooooo\",\"nooooooooooooooooooooooooo\",\"nooooooooooooooooo\",\"nnoooooooooooooooooo\",\"ooooonoooooooooooooo\"]', 'Highlights', 'The standout moments built into this safari.', 'Inclusions & Exclusions', 'A clear view of what is covered and what to plan for separately.', 'Tanzania Safari Adventure', NULL, '{\"fr\":\"Aventure safari de 6 jours en Tanzanie\",\"de\":\"6-tu00e4giges Safari-Abenteuer in Tansania: Tarangire, Serengeti, Ngorongoro & Lake Manyara\",\"es\":\"Aventura de safari de 6 du00edas por Tanzania: Tarangire, Serengeti, Ngorongoro y el lago Manyara\"}', '{\"fr\":\"Ce safari de 6 jours &amp;lt;/strong&amp;gtu00a0; vous emmu00e8ne dans un voyage inoubliable u00e0 travers les parcs les plus emblu00e9matiques de Tanzanieu00a0: Tarangire, ru00e9putu00e9 pour ses u00e9lu00e9phants et ses baobabsu00a0; le Serengeti, ou00f9 la Grande Migration se du00e9roule au milieu de divers pru00e9dateursu00a0;\",\"de\":\"Diese 6-tu00e4gige Safari &amp;lt;/strong&amp;gt;nimmt Sie mit auf eine unvergessliche Reise durch Tansanias beru00fchmteste Parks: Tarangire, bekannt fu00fcr seine Elefanten und Baobab-Bu00e4ume; die Serengeti, wo sich die Grou00dfe Migration inmitten verschiedener Raubtiere entfaltet;\",\"es\":\"Este safari de 6 du00edas &amp;lt;/strong&amp;gt; te lleva en un viaje inolvidable por los parques mu00e1s emblemu00e1ticos de Tanzania: Tarangire, famoso por sus elefantes y baobabs; el Serengeti, donde la Gran Migraciu00f3n se desarrolla en medio de diversos depredadores;\"}', '{\"fr\":\"&lt;p&gt;Notre itinu00e9raire soigneusement conu00e7u du00e9crit chaque jour de votre voyage en du00e9tail, assurant une expu00e9rience de voyage transparente. De l\'arrivu00e9e au du00e9part, chaque u00e9tape a u00e9tu00e9 soigneusement planifiu00e9e pour maximiser le confort, la du00e9couverte et la valeur.\",\"de\":\"&lt;p&gt;Unsere sorgfu00e4ltig gestaltete Reiseroute beschreibt jeden Tag Ihrer Reise im Detail und sorgt fu00fcr ein nahtloses Reiseerlebnis. Von der Ankunft bis zur Abreise wurde jeder Schritt sorgfu00e4ltig geplant, um Komfort, Entdeckung und Wert zu maximieren\",\"es\":\"&lt;p&gt;Nuestro itinerario cuidadosamente diseu00f1ado describe cada du00eda de tu viaje en detalle, lo que garantiza una experiencia de viaje perfecta. Desde la llegada hasta la salida, cada paso se ha planificado cuidadosamente para maximizar la comodidad, el descubrimiento y el valor.\"}', NULL, NULL, NULL, NULL, NULL, '{\"fr\":\"Nouveautu00e9s\",\"de\":\"Highlights\",\"es\":\"Destacados\"}', '{\"fr\":\"Les moments forts de ce safari.\",\"de\":\"Die herausragenden Momente, die in diese Safari eingebaut sind.\",\"es\":\"Los momentos mu00e1s destacados de este safari.\"}', '{\"fr\":\"Inclusions et exclusions\",\"de\":\"Ein- und Ausschlu00fcsse\",\"es\":\"Inclusiones/Exclusiones\"}', '{\"fr\":\"Une vision claire de ce qui est couvert et de ce qu\'il faut planifier su00e9paru00e9ment.\",\"de\":\"Ein klarer u00dcberblick daru00fcber, was abgedeckt ist und was separat zu planen ist.\",\"es\":\"Una visiu00f3n clara de lo que estu00e1 cubierto y quu00e9 planificar por separado.\"}', '{\"fr\":\"Aventure safari en Tanzanie\",\"de\":\"Safari-Abenteuer in Tansania\",\"es\":\"Aventura de safari en Tanzania\"}', '0', NULL),
('4', 'The Great Migration Encounter — 6-Day Serengeti Safari', 'great-migration-encounter-6-day-serengeti', 'Witness the thundering hooves of over two million wildebeest as they cross the Serengeti in one of nature\'s most awe-inspiring spectacles.', 'Journey into the heart of the Serengeti during the height of the Great Migration — a front-row seat to Earth\'s greatest wildlife drama. Your six-day luxury expedition begins in Arusha with a private charter flight into the Central Serengeti, touching down where golden plains stretch endlessly to the horizon.\n\nDays are spent with your expert Maasai guide tracking enormous herds across the Seronera Valley, stopping to watch Nile crocodiles patrol the banks of the Grumeti River. As dusk falls, return to your luxury tented camp — complete with en-suite rain showers, evening sundowners on the kopjes, and gourmet farm-to-table dinners under a canopy of equatorial stars.\n\nThis safari includes an exclusive hot-air balloon flight at dawn over the Serengeti, a private bush breakfast, and a cultural visit to an authentic Maasai boma. Every detail has been curated to ensure you don\'t just witness the migration — you become part of it.', '6 Days / 5 Nights', NULL, NULL, 'Easy', '1799.00', 'USD', NULL, NULL, NULL, NULL, 'published', 'safari', '2026-04-05 02:41:57', '2026-04-05 02:41:57', '1', '2', '1', '\"[\\\"Witness the Great Migration river crossings up close\\\",\\\"Exclusive hot-air balloon flight at sunrise over the Serengeti\\\",\\\"Private bush breakfast on the savanna\\\",\\\"Luxury tented camp with en-suite facilities\\\",\\\"Expert Maasai guide with 15+ years of experience\\\",\\\"Cultural visit to an authentic Maasai boma\\\",\\\"Private charter flight from Arusha to the Serengeti\\\"]\"', '\"[\\\"All park entrance fees\\\",\\\"Private 4x4 safari vehicle\\\",\\\"Professional English-speaking guide\\\",\\\"All meals and premium beverages\\\",\\\"Luxury tented accommodation\\\",\\\"Hot-air balloon flight\\\",\\\"Airport transfers\\\",\\\"AMREF Flying Doctors insurance\\\",\\\"Complimentary binoculars\\\"]\"', '\"[\\\"International flights\\\",\\\"Visa fees\\\",\\\"Travel insurance\\\",\\\"Personal items\\\",\\\"Gratuities\\\"]\"', NULL, NULL, NULL, NULL, NULL, NULL, '\"{\\\"fr\\\":\\\"La Grande Migration \\\\u2014 Safari de 6 jours dans le Serengeti\\\",\\\"de\\\":\\\"Die Gro\\\\u00dfe Migration \\\\u2014 6-Tage Serengeti-Safari\\\",\\\"es\\\":\\\"La Gran Migraci\\\\u00f3n \\\\u2014 Safari de 6 d\\\\u00edas en el Serengeti\\\"}\"', '\"{\\\"fr\\\":\\\"Assistez au spectacle grandiose de plus de deux millions de gnous traversant le Serengeti lors de l\'une des merveilles les plus impressionnantes de la nature.\\\",\\\"de\\\":\\\"Erleben Sie das donnernde Spektakel von \\\\u00fcber zwei Millionen Gnus auf ihrer Wanderung durch die Serengeti \\\\u2014 eines der atemberaubendsten Naturschauspiele der Erde.\\\",\\\"es\\\":\\\"Sea testigo del estruendoso paso de m\\\\u00e1s de dos millones de \\\\u00f1us cruzando el Serengeti en uno de los espect\\\\u00e1culos m\\\\u00e1s impresionantes de la naturaleza.\\\"}\"', '\"{\\\"fr\\\":\\\"Partez au c\\\\u0153ur du Serengeti pendant l\'apog\\\\u00e9e de la Grande Migration \\\\u2014 une place au premier rang du plus grand drame animalier de la Terre. Votre exp\\\\u00e9dition de luxe de six jours commence \\\\u00e0 Arusha avec un vol charter priv\\\\u00e9 vers le Serengeti central, atterrissant l\\\\u00e0 o\\\\u00f9 les plaines dor\\\\u00e9es s\'\\\\u00e9tendent \\\\u00e0 l\'infini jusqu\'\\\\u00e0 l\'horizon.\\\\n\\\\nVos journ\\\\u00e9es se passent avec votre guide expert Maasa\\\\u00ef \\\\u00e0 traquer d\'immenses troupeaux \\\\u00e0 travers la vall\\\\u00e9e de Seronera, observant les crocodiles du Nil patrouiller les rives de la rivi\\\\u00e8re Grumeti. Au cr\\\\u00e9puscule, retournez \\\\u00e0 votre camp de luxe avec douches tropicales, cocktails du soir sur les kopjes et d\\\\u00eeners gastronomiques sous une canop\\\\u00e9e d\'\\\\u00e9toiles \\\\u00e9quatoriales.\\\\n\\\\nCe safari comprend un vol exclusif en montgolfi\\\\u00e8re \\\\u00e0 l\'aube au-dessus du Serengeti, un petit-d\\\\u00e9jeuner priv\\\\u00e9 en brousse et une visite culturelle d\'un authentique boma Maasa\\\\u00ef.\\\",\\\"de\\\":\\\"Reisen Sie in das Herz der Serengeti w\\\\u00e4hrend des H\\\\u00f6hepunkts der Gro\\\\u00dfen Migration \\\\u2014 ein Platz in der ersten Reihe beim gr\\\\u00f6\\\\u00dften Tierdrama der Erde. Ihre sechst\\\\u00e4gige Luxus-Expedition beginnt in Arusha mit einem privaten Charterflug in die zentrale Serengeti, wo sich goldene Ebenen endlos bis zum Horizont erstrecken.\\\\n\\\\nDie Tage verbringen Sie mit Ihrem erfahrenen Maasai-Guide beim Aufsp\\\\u00fcren riesiger Herden durch das Seronera-Tal und beobachten Nilkrokodile an den Ufern des Grumeti-Flusses. Bei Einbruch der D\\\\u00e4mmerung kehren Sie in Ihr Luxus-Zeltcamp zur\\\\u00fcck \\\\u2014 mit Regendusche, Sundowner auf den Kopjes und Gourmet-Dinner unter dem \\\\u00e4quatorialen Sternenhimmel.\\\\n\\\\nDiese Safari beinhaltet einen exklusiven Hei\\\\u00dfluftballonflug bei Sonnenaufgang \\\\u00fcber der Serengeti, ein privates Buschfr\\\\u00fchst\\\\u00fcck und einen kulturellen Besuch eines authentischen Maasai-Bomas.\\\",\\\"es\\\":\\\"Ad\\\\u00e9ntrese en el coraz\\\\u00f3n del Serengeti durante el apogeo de la Gran Migraci\\\\u00f3n \\\\u2014 un asiento de primera fila para el mayor espect\\\\u00e1culo de vida silvestre de la Tierra. Su expedici\\\\u00f3n de lujo de seis d\\\\u00edas comienza en Arusha con un vuelo ch\\\\u00e1rter privado al Serengeti central, donde las llanuras doradas se extienden sin fin hasta el horizonte.\\\\n\\\\nLos d\\\\u00edas los pasa con su gu\\\\u00eda experto Maas\\\\u00e1i rastreando enormes manadas a trav\\\\u00e9s del valle de Seronera, observando cocodrilos del Nilo patrullar las orillas del r\\\\u00edo Grumeti. Al anochecer, regrese a su campamento de lujo con duchas tropicales, c\\\\u00f3cteles al atardecer en los kopjes y cenas gourmet bajo un dosel de estrellas ecuatoriales.\\\\n\\\\nEste safari incluye un vuelo exclusivo en globo aerost\\\\u00e1tico al amanecer sobre el Serengeti, un desayuno privado en el bush y una visita cultural a un aut\\\\u00e9ntico boma Maas\\\\u00e1i.\\\"}\"', NULL, 'Great Migration Safari — 6-Day Luxury Serengeti Experience | Lomo Tanzania', 'Experience the Great Migration on a 6-day luxury Serengeti safari. Witness river crossings, enjoy hot-air balloon flights, and stay in premium tented camps. Book now.', 'great migration safari, serengeti safari, luxury safari tanzania, wildebeest migration, 6 day safari, serengeti tented camp, hot air balloon serengeti', NULL, NULL, NULL, NULL, NULL, NULL, '1', 'Most Popular'),
('5', 'The Ultimate Northern Circuit — 8-Day Big Five Safari', 'ultimate-northern-circuit-8-day-big-five', 'Traverse Tanzania\'s crown jewels — from the Ngorongoro Crater to the Serengeti, Tarangire, and Lake Manyara in an unforgettable Big Five expedition.', 'Eight days of pure, unadulterated wilderness — the Northern Circuit is Tanzania\'s most iconic safari route, and this itinerary elevates it to an art form. Beginning in Arusha, you\'ll embark on a private overland journey through landscapes that have captivated explorers for centuries.\n\nYour adventure opens in Tarangire National Park, where ancient baobab trees frame herds of over 300 elephants. Continue to the emerald-fringed shores of Lake Manyara, famous for its tree-climbing lions and vast flocks of flamingos. The Ngorongoro Crater — a UNESCO World Heritage Site and the world\'s largest intact volcanic caldera — offers virtually guaranteed Big Five sightings in a single morning\'s game drive.\n\nThe journey culminates with three full days in the Serengeti, staying in a private concession area far from the crowds. Expect intimate encounters with lion prides, leopard lounging in acacia branches, and the eternal ebb and flow of the Great Migration. Nights are spent in award-winning lodges and fly camps chosen for their unrivalled vantage points.', '8 Days / 7 Nights', NULL, NULL, 'Easy', '2310.00', 'USD', NULL, NULL, NULL, NULL, 'published', 'safari', '2026-04-05 02:41:57', '2026-04-05 02:41:57', '1', '1', '10', '\"[\\\"Explore all four Northern Circuit parks in one safari\\\",\\\"Big Five sightings virtually guaranteed in Ngorongoro Crater\\\",\\\"Tarangire\'s legendary elephant herds among ancient baobabs\\\",\\\"Tree-climbing lions of Lake Manyara\\\",\\\"Three full days in the Serengeti on a private concession\\\",\\\"Award-winning lodges and exclusive fly camps\\\",\\\"Sunset cocktails on the Ngorongoro Crater rim\\\"]\"', '\"[\\\"All park and conservation fees\\\",\\\"Private 4x4 Land Cruiser with pop-up roof\\\",\\\"Professional safari guide\\\",\\\"All meals, water, and select beverages\\\",\\\"Premium lodge and tented camp accommodation\\\",\\\"Ngorongoro Crater service fee\\\",\\\"Airport transfers\\\",\\\"AMREF Flying Doctors insurance\\\"]\"', '\"[\\\"International flights\\\",\\\"Visa fees\\\",\\\"Travel insurance\\\",\\\"Optional balloon safari ($599)\\\",\\\"Gratuities\\\",\\\"Personal expenditure\\\"]\"', NULL, NULL, NULL, NULL, NULL, NULL, '\"{\\\"fr\\\":\\\"Le Circuit Nord Ultime \\\\u2014 Safari Big Five de 8 jours\\\",\\\"de\\\":\\\"Die ultimative Nordroute \\\\u2014 8-Tage Big-Five-Safari\\\",\\\"es\\\":\\\"El Circuito Norte Definitivo \\\\u2014 Safari Big Five de 8 d\\\\u00edas\\\"}\"', '\"{\\\"fr\\\":\\\"Parcourez les joyaux de la Tanzanie \\\\u2014 du crat\\\\u00e8re du Ngorongoro au Serengeti, Tarangire et lac Manyara dans une exp\\\\u00e9dition Big Five inoubliable.\\\",\\\"de\\\":\\\"Durchqueren Sie Tansanias Kronjuwelen \\\\u2014 vom Ngorongoro-Krater zur Serengeti, Tarangire und Manyara-See in einer unvergesslichen Big-Five-Expedition.\\\",\\\"es\\\":\\\"Recorra las joyas de Tanzania \\\\u2014 desde el cr\\\\u00e1ter del Ngorongoro hasta el Serengeti, Tarangire y lago Manyara en una expedici\\\\u00f3n Big Five inolvidable.\\\"}\"', '\"{\\\"fr\\\":\\\"Huit jours de nature sauvage pure \\\\u2014 le Circuit Nord est l\'itin\\\\u00e9raire safari le plus embl\\\\u00e9matique de Tanzanie, et ce programme l\'\\\\u00e9l\\\\u00e8ve au rang d\'art. Au d\\\\u00e9part d\'Arusha, embarquez pour un voyage terrestre priv\\\\u00e9 \\\\u00e0 travers des paysages qui captivent les explorateurs depuis des si\\\\u00e8cles.\\\\n\\\\nVotre aventure d\\\\u00e9bute dans le parc national de Tarangire, o\\\\u00f9 d\'anciens baobabs encadrent des troupeaux de plus de 300 \\\\u00e9l\\\\u00e9phants. Continuez vers les rives \\\\u00e9meraude du lac Manyara, c\\\\u00e9l\\\\u00e8bre pour ses lions grimpeurs et ses vastes colonies de flamants roses. Le crat\\\\u00e8re du Ngorongoro \\\\u2014 site du patrimoine mondial de l\'UNESCO \\\\u2014 offre des observations des Big Five pratiquement garanties en une seule matin\\\\u00e9e.\\\\n\\\\nLe voyage culmine avec trois jours complets dans le Serengeti, s\\\\u00e9journant dans une concession priv\\\\u00e9e loin des foules. Nuits dans des lodges prim\\\\u00e9s et camps exclusifs choisis pour leurs points de vue in\\\\u00e9gal\\\\u00e9s.\\\",\\\"de\\\":\\\"Acht Tage pure Wildnis \\\\u2014 die Nordroute ist Tansanias ikonischste Safari-Route, und dieses Programm erhebt sie zur Kunstform. Von Arusha aus beginnen Sie eine private \\\\u00dcberlandreise durch Landschaften, die Entdecker seit Jahrhunderten faszinieren.\\\\n\\\\nIhr Abenteuer beginnt im Tarangire-Nationalpark, wo uralte Baobab-B\\\\u00e4ume Herden von \\\\u00fcber 300 Elefanten einrahmen. Weiter geht es zu den smaragdgr\\\\u00fcnen Ufern des Manyara-Sees, ber\\\\u00fchmt f\\\\u00fcr baumkletternde L\\\\u00f6wen und riesige Flamingokolonien. Der Ngorongoro-Krater \\\\u2014 UNESCO-Welterbe \\\\u2014 bietet praktisch garantierte Big-Five-Sichtungen an einem einzigen Vormittag.\\\\n\\\\nDie Reise gipfelt in drei vollen Tagen in der Serengeti, in einer privaten Konzession abseits der Massen. N\\\\u00e4chte in preisgekr\\\\u00f6nten Lodges und exklusiven Fly-Camps an unvergleichlichen Aussichtspunkten.\\\",\\\"es\\\":\\\"Ocho d\\\\u00edas de pura naturaleza salvaje \\\\u2014 el Circuito Norte es la ruta de safari m\\\\u00e1s ic\\\\u00f3nica de Tanzania, y este itinerario la eleva a una forma de arte. Partiendo de Arusha, emprender\\\\u00e1 un viaje privado por tierra a trav\\\\u00e9s de paisajes que han cautivado a exploradores durante siglos.\\\\n\\\\nSu aventura comienza en el Parque Nacional de Tarangire, donde antiguos baobabs enmarcan manadas de m\\\\u00e1s de 300 elefantes. Contin\\\\u00fae hacia las orillas esmeralda del lago Manyara, famoso por sus leones trepadores y vastas colonias de flamencos. El cr\\\\u00e1ter del Ngorongoro \\\\u2014 Patrimonio de la Humanidad de la UNESCO \\\\u2014 ofrece avistamientos de los Big Five pr\\\\u00e1cticamente garantizados en una sola ma\\\\u00f1ana.\\\\n\\\\nEl viaje culmina con tres d\\\\u00edas completos en el Serengeti, hosped\\\\u00e1ndose en una concesi\\\\u00f3n privada lejos de las multitudes. Noches en lodges galardonados y campamentos exclusivos con vistas incomparables.\\\"}\"', NULL, '8-Day Big Five Safari — Northern Circuit Tanzania | Lomo Tanzania', 'Explore Tanzania\'s Northern Circuit on an 8-day Big Five luxury safari. Visit Serengeti, Ngorongoro Crater, Tarangire & Lake Manyara. Premium lodges included.', 'big five safari, northern circuit tanzania, ngorongoro crater safari, serengeti safari, tarangire safari, 8 day safari tanzania, luxury safari', NULL, NULL, NULL, NULL, NULL, NULL, '2', 'Best Value'),
('6', 'Romantic Serengeti & Zanzibar — 7-Day Honeymoon Safari', 'romantic-serengeti-zanzibar-7-day-honeymoon', 'A once-in-a-lifetime honeymoon blending the raw romance of the Serengeti with the turquoise shores of Zanzibar — designed exclusively for two.', 'This is not merely a honeymoon; it\'s a love letter written across the African landscape. Your seven-day journey begins with three nights in the Serengeti, where you\'ll share your first sundowner as married travellers from the vantage of a kopje overlooking an endless sea of golden grass.\n\nStay in an intimate luxury lodge with just twelve suites, each with a private plunge pool and outdoor shower. Private game drives reveal lion prides, leopard pairs, and the world\'s fastest cheetah sprinting across the plains. On your final Serengeti evening, a private bush dinner is arranged under the Milky Way, complete with candlelight, champagne, and the distant call of hyenas.\n\nFrom the savanna, you\'ll fly directly to Zanzibar\'s Stone Town, transferring to a boutique oceanfront resort on the secluded east coast. Spend your final three days snorkelling prismacolor reefs, kayaking through mangrove channels, enjoying couples\' spa treatments with ocean views, and savouring freshly caught seafood by lantern light on powdery white sand.', '7 Days / 6 Nights', NULL, NULL, 'Easy', '2318.00', 'USD', NULL, NULL, NULL, NULL, 'published', 'safari', '2026-04-05 02:41:57', '2026-04-05 02:41:57', '1', '4', '1', '\"[\\\"Private bush dinner under the Milky Way in the Serengeti\\\",\\\"Intimate lodge with private plunge pool & outdoor shower\\\",\\\"Champagne sundowners on the kopjes\\\",\\\"Scenic charter flight from Serengeti to Zanzibar\\\",\\\"Boutique oceanfront resort on Zanzibar\'s east coast\\\",\\\"Couples\' spa treatments with Indian Ocean views\\\",\\\"Snorkelling pristine coral reefs & mangrove kayaking\\\"]\"', '\"[\\\"All park entrance fees\\\",\\\"Private game drives in luxury Land Cruiser\\\",\\\"Domestic charter flights (Serengeti\\\\u2013Zanzibar)\\\",\\\"Luxury lodge & boutique beach resort\\\",\\\"All meals, champagne, and premium beverages\\\",\\\"Private bush dinner experience\\\",\\\"Airport transfers\\\",\\\"Honeymoon amenities & decoration\\\"]\"', '\"[\\\"International flights\\\",\\\"Visa fees\\\",\\\"Travel insurance\\\",\\\"Personal purchases\\\",\\\"Optional diving excursions\\\",\\\"Gratuities\\\"]\"', NULL, NULL, NULL, NULL, NULL, NULL, '\"{\\\"fr\\\":\\\"Romance au Serengeti & Zanzibar \\\\u2014 Lune de miel de 7 jours\\\",\\\"de\\\":\\\"Romantik in der Serengeti & Sansibar \\\\u2014 7-Tage Flitterwochen-Safari\\\",\\\"es\\\":\\\"Romance en el Serengeti y Zanz\\\\u00edbar \\\\u2014 Luna de miel de 7 d\\\\u00edas\\\"}\"', '\"{\\\"fr\\\":\\\"Une lune de miel unique m\\\\u00ealant la romance brute du Serengeti aux rivages turquoise de Zanzibar \\\\u2014 con\\\\u00e7ue exclusivement pour deux.\\\",\\\"de\\\":\\\"Eine einmalige Hochzeitsreise, die die wilde Romantik der Serengeti mit den t\\\\u00fcrkisfarbenen K\\\\u00fcsten Sansibars verbindet \\\\u2014 exklusiv f\\\\u00fcr zwei.\\\",\\\"es\\\":\\\"Una luna de miel \\\\u00fanica que fusiona el romance salvaje del Serengeti con las costas turquesas de Zanz\\\\u00edbar \\\\u2014 dise\\\\u00f1ada exclusivamente para dos.\\\"}\"', '\"{\\\"fr\\\":\\\"Ce n\'est pas simplement une lune de miel ; c\'est une lettre d\'amour \\\\u00e9crite \\\\u00e0 travers le paysage africain. Votre voyage de sept jours commence par trois nuits dans le Serengeti, o\\\\u00f9 vous partagerez votre premier sundowner en tant que jeunes mari\\\\u00e9s depuis un kopje surplombant une mer infinie d\'herbes dor\\\\u00e9es.\\\\n\\\\nS\\\\u00e9journez dans un lodge intimiste avec seulement douze suites, chacune avec piscine priv\\\\u00e9e et douche ext\\\\u00e9rieure. Les safaris priv\\\\u00e9s r\\\\u00e9v\\\\u00e8lent des lionnes, des l\\\\u00e9opards et les gu\\\\u00e9pards les plus rapides du monde. Le dernier soir, un d\\\\u00eener priv\\\\u00e9 en brousse est organis\\\\u00e9 sous la Voie lact\\\\u00e9e.\\\\n\\\\nDepuis la savane, envolez-vous directement vers Stone Town \\\\u00e0 Zanzibar, puis rejoignez un resort boutique en bord d\'oc\\\\u00e9an. Passez vos trois derniers jours \\\\u00e0 faire du snorkeling, du kayak dans les mangroves et des soins spa en couple face \\\\u00e0 l\'oc\\\\u00e9an.\\\",\\\"de\\\":\\\"Dies ist nicht nur eine Hochzeitsreise; es ist ein Liebesbrief, geschrieben \\\\u00fcber die afrikanische Landschaft. Ihre siebent\\\\u00e4gige Reise beginnt mit drei N\\\\u00e4chten in der Serengeti, wo Sie Ihren ersten Sundowner als frisch Verm\\\\u00e4hlte auf einem Kopje mit Blick auf ein endloses Meer goldenen Grases genie\\\\u00dfen.\\\\n\\\\n\\\\u00dcbernachten Sie in einer intimen Luxus-Lodge mit nur zw\\\\u00f6lf Suiten, jede mit privatem Tauchbecken und Au\\\\u00dfendusche. Private Pirschfahrten offenbaren L\\\\u00f6wenrudel, Leopardenpaare und die schnellsten Geparden der Welt. Am letzten Abend wird ein privates Busch-Dinner unter der Milchstra\\\\u00dfe arrangiert.\\\\n\\\\nVon der Savanne fliegen Sie direkt nach Sansibar Stone Town und weiter zu einem Boutique-Resort an der abgelegenen Ostk\\\\u00fcste. Verbringen Sie die letzten drei Tage beim Schnorcheln, Mangroven-Kajakfahren und Spa-Behandlungen f\\\\u00fcr Paare mit Meerblick.\\\",\\\"es\\\":\\\"Esto no es simplemente una luna de miel; es una carta de amor escrita a trav\\\\u00e9s del paisaje africano. Su viaje de siete d\\\\u00edas comienza con tres noches en el Serengeti, donde compartir\\\\u00e1 su primer sundowner como reci\\\\u00e9n casados desde un kopje con vistas a un mar infinito de hierba dorada.\\\\n\\\\nAl\\\\u00f3jese en un lodge \\\\u00edntimo con solo doce suites, cada una con piscina privada y ducha al aire libre. Los safaris privados revelan manadas de leones, leopardos y los guepardos m\\\\u00e1s r\\\\u00e1pidos del mundo. La \\\\u00faltima noche, se organiza una cena privada en el bush bajo la V\\\\u00eda L\\\\u00e1ctea.\\\\n\\\\nDesde la sabana, vuele directamente a Stone Town en Zanz\\\\u00edbar y luego a un resort boutique frente al oc\\\\u00e9ano. Pase sus \\\\u00faltimos tres d\\\\u00edas haciendo snorkel, kayak en manglares y tratamientos de spa para parejas con vistas al oc\\\\u00e9ano.\\\"}\"', NULL, 'Honeymoon Safari Tanzania — Serengeti & Zanzibar 7 Days | Lomo Tanzania', 'Plan the perfect honeymoon: 3 days in the Serengeti with private game drives, then 3 days on Zanzibar\'s pristine beaches. Luxury lodge & beach resort included.', 'honeymoon safari tanzania, serengeti zanzibar honeymoon, romantic safari, luxury honeymoon africa, 7 day honeymoon, zanzibar beach resort', NULL, NULL, NULL, NULL, NULL, NULL, '3', 'Romantic'),
('7', 'Ngorongoro & Tarangire — 5-Day Family Safari Adventure', 'ngorongoro-tarangire-5-day-family-safari', 'A family-friendly expedition combining the Ngorongoro Crater\'s Big Five spectacle with Tarangire\'s legendary elephants — perfectly paced for all ages.', 'Designed for families seeking authentic wilderness without the marathon drives, this five-day safari is the ideal introduction to Tanzania\'s wildlife for travellers of all ages. Departing Arusha, you\'ll reach Tarangire National Park within two hours — just enough time for the children to absorb the stunning baobab-studded landscape through the open roof of your private Land Cruiser.\n\nTarangire\'s 300-strong elephant herds provide extraordinary viewing: calves splashing in watering holes, matriarchs guiding their families through ancient corridors of giants. Your family-friendly lodge features interconnecting rooms, a pool, and guided nature walks designed for young explorers.\n\nThe heart of the safari is the Ngorongoro Crater — a 260-square-kilometre arena where lions, rhinos, elephants, buffalos, and leopards roam in a self-contained paradise. Junior rangers receive their own binoculars and wildlife journals. The final evening features a stargazing session on the crater rim with a local astronomer, mapping constellations visible only from the African equator.', '5 Days / 4 Nights', NULL, NULL, 'Easy', '1590.00', 'USD', NULL, NULL, NULL, NULL, 'published', 'safari', '2026-04-05 02:41:57', '2026-04-05 02:41:57', '1', '8', '3', '\"[\\\"Big Five safari inside the Ngorongoro Crater\\\",\\\"Tarangire\'s 300-strong elephant herds\\\",\\\"Family lodge with interconnecting rooms & pool\\\",\\\"Junior Ranger programme with binoculars & wildlife journal\\\",\\\"Stargazing session on the Ngorongoro Crater rim\\\",\\\"Short driving distances \\\\u2014 ideal for younger travellers\\\",\\\"Guided nature walks for young explorers\\\"]\"', '\"[\\\"All park and crater fees\\\",\\\"Private 4x4 Land Cruiser\\\",\\\"Professional family-friendly guide\\\",\\\"All meals and beverages\\\",\\\"Family lodge accommodation\\\",\\\"Junior Ranger kit\\\",\\\"Airport transfers\\\",\\\"AMREF Flying Doctors insurance\\\"]\"', '\"[\\\"International flights\\\",\\\"Visa fees\\\",\\\"Travel insurance\\\",\\\"Personal items\\\",\\\"Gratuities\\\"]\"', NULL, NULL, NULL, NULL, NULL, NULL, '\"{\\\"fr\\\":\\\"Ngorongoro & Tarangire \\\\u2014 Aventure Safari Familiale de 5 jours\\\",\\\"de\\\":\\\"Ngorongoro & Tarangire \\\\u2014 5-Tage Familien-Safari-Abenteuer\\\",\\\"es\\\":\\\"Ngorongoro y Tarangire \\\\u2014 Aventura Safari Familiar de 5 d\\\\u00edas\\\"}\"', '\"{\\\"fr\\\":\\\"Une exp\\\\u00e9dition familiale combinant le spectacle Big Five du crat\\\\u00e8re du Ngorongoro avec les \\\\u00e9l\\\\u00e9phants l\\\\u00e9gendaires de Tarangire \\\\u2014 parfaitement rythm\\\\u00e9e pour tous les \\\\u00e2ges.\\\",\\\"de\\\":\\\"Eine familienfreundliche Expedition, die das Big-Five-Spektakel des Ngorongoro-Kraters mit Tarangires legend\\\\u00e4ren Elefanten verbindet \\\\u2014 perfekt getaktet f\\\\u00fcr alle Altersgruppen.\\\",\\\"es\\\":\\\"Una expedici\\\\u00f3n familiar que combina el espect\\\\u00e1culo Big Five del cr\\\\u00e1ter Ngorongoro con los legendarios elefantes de Tarangire \\\\u2014 perfectamente dise\\\\u00f1ada para todas las edades.\\\"}\"', '\"{\\\"fr\\\":\\\"Con\\\\u00e7u pour les familles en qu\\\\u00eate d\'authenticit\\\\u00e9 sans les longues routes, ce safari de cinq jours est l\'introduction id\\\\u00e9ale \\\\u00e0 la faune tanzanienne. Au d\\\\u00e9part d\'Arusha, vous atteignez Tarangire en deux heures \\\\u2014 juste le temps pour les enfants d\'admirer le paysage parsem\\\\u00e9 de baobabs.\\\\n\\\\nLes 300 \\\\u00e9l\\\\u00e9phants de Tarangire offrent un spectacle extraordinaire : \\\\u00e9l\\\\u00e9phanteaux s\'\\\\u00e9claboussant aux points d\'eau, matriarches guidant leurs familles. Votre lodge familial dispose de chambres communicantes, d\'une piscine et de promenades nature pour jeunes explorateurs.\\\\n\\\\nLe c\\\\u0153ur du safari est le crat\\\\u00e8re du Ngorongoro \\\\u2014 une ar\\\\u00e8ne de 260 km\\\\u00b2 o\\\\u00f9 lions, rhinoc\\\\u00e9ros, \\\\u00e9l\\\\u00e9phants, buffles et l\\\\u00e9opards \\\\u00e9voluent librement. Les jeunes rangers re\\\\u00e7oivent jumelles et journal de la faune. La derni\\\\u00e8re soir\\\\u00e9e propose une observation des \\\\u00e9toiles sur le bord du crat\\\\u00e8re.\\\",\\\"de\\\":\\\"F\\\\u00fcr Familien konzipiert, die authentische Wildnis ohne Marathonfahrten suchen \\\\u2014 diese f\\\\u00fcnft\\\\u00e4gige Safari ist die ideale Einf\\\\u00fchrung in Tansanias Tierwelt. Von Arusha erreichen Sie Tarangire in zwei Stunden \\\\u2014 gerade genug Zeit f\\\\u00fcr die Kinder, die atemberaubende Baobab-Landschaft zu bewundern.\\\\n\\\\nTarangires 300-k\\\\u00f6pfige Elefantenherden bieten au\\\\u00dfergew\\\\u00f6hnliche Beobachtungen: K\\\\u00e4lber planschen an Wasserl\\\\u00f6chern, Matriarchinnen f\\\\u00fchren ihre Familien. Ihre familienfreundliche Lodge bietet Verbindungszimmer, Pool und gef\\\\u00fchrte Naturwanderungen f\\\\u00fcr junge Entdecker.\\\\n\\\\nDas Herzst\\\\u00fcck ist der Ngorongoro-Krater \\\\u2014 eine 260 km\\\\u00b2 gro\\\\u00dfe Arena, in der L\\\\u00f6wen, Nash\\\\u00f6rner, Elefanten, B\\\\u00fcffel und Leoparden frei umherstreifen. Junior-Ranger erhalten Ferngl\\\\u00e4ser und Wildtier-Tageb\\\\u00fccher. Der letzte Abend bietet eine Sternenbeobachtung am Kraterrand.\\\",\\\"es\\\":\\\"Dise\\\\u00f1ado para familias que buscan naturaleza aut\\\\u00e9ntica sin largas rutas, este safari de cinco d\\\\u00edas es la introducci\\\\u00f3n ideal a la fauna de Tanzania. Desde Arusha, llegar\\\\u00e1 a Tarangire en dos horas \\\\u2014 justo el tiempo para que los ni\\\\u00f1os admiren el paisaje salpicado de baobabs.\\\\n\\\\nLas 300 manadas de elefantes de Tarangire ofrecen un espect\\\\u00e1culo extraordinario: cr\\\\u00edas chapoteando en abrevaderos, matriarcas guiando a sus familias. Su lodge familiar cuenta con habitaciones comunicadas, piscina y paseos por la naturaleza para j\\\\u00f3venes exploradores.\\\\n\\\\nEl coraz\\\\u00f3n del safari es el cr\\\\u00e1ter Ngorongoro \\\\u2014 una arena de 260 km\\\\u00b2 donde leones, rinocerontes, elefantes, b\\\\u00fafalos y leopardos deambulan libremente. Los j\\\\u00f3venes rangers reciben prism\\\\u00e1ticos y diarios de fauna. La \\\\u00faltima noche ofrece una sesi\\\\u00f3n de observaci\\\\u00f3n de estrellas en el borde del cr\\\\u00e1ter.\\\"}\"', NULL, '5-Day Family Safari — Ngorongoro Crater & Tarangire | Lomo Tanzania', 'Book a family-friendly 5-day safari in Tanzania. Explore Ngorongoro Crater Big Five & Tarangire elephants. Family lodges, junior ranger kits & short drives.', 'family safari tanzania, ngorongoro crater safari, tarangire elephants, 5 day safari, family safari africa, kids safari tanzania, big five family', NULL, NULL, NULL, NULL, NULL, NULL, '4', 'Family Favourite'),
('8', 'Kilimanjaro Marangu Route — 5-Day Classic Summit Trek', 'kilimanjaro-marangu-5-day-classic-summit', 'Ascend Africa\'s highest peak via the legendary \"Coca-Cola Route\" — the only Kilimanjaro path offering hut accommodation and a gentler gradient.', 'The Marangu Route is the classic gateway to Uhuru Peak, offering a perfect balance of challenge and comfort. Known affectionately as the \"Coca-Cola Route\" for its relative accessibility, it\'s the only trail providing permanent mountain hut accommodation — no tents required.\n\nYour five-day ascent begins at Marangu Gate (1,840m) and winds through four distinct ecological zones: lush montane rainforest alive with Colobus monkeys, heather moorland dotted with giant lobelias, an alpine desert of surreal volcanic scree, and finally the arctic summit zone where equatorial glaciers glint in the pre-dawn light.\n\nThe push to Uhuru Peak (5,895m) begins at midnight. Guided by your expert mountain team under a blanket of stars, each step takes you closer to the Roof of Africa. At sunrise, the views from the crater rim stretch across the curvature of the Earth — a moment of triumph that will stay with you forever.\n\nAll treks include a highly experienced KINAPA-licensed lead guide, assistant guides, porters, a camp chef, and daily health monitoring with pulse oximetry.', '5 Days / 4 Nights', NULL, NULL, 'Moderate', '1807.00', 'USD', NULL, NULL, NULL, NULL, 'published', 'trekking', '2026-04-05 02:41:57', '2026-04-05 02:41:57', '0', '7', '3', '\"[\\\"Summit Africa\'s highest peak at 5,895m (Uhuru Peak)\\\",\\\"Only route with mountain hut accommodation\\\",\\\"Four distinct ecological zones in five days\\\",\\\"KINAPA-licensed expert guides with pulse oximetry\\\",\\\"Colobus monkeys in the montane rainforest\\\",\\\"Sunrise from the crater rim\\\",\\\"Certificate of achievement at the summit\\\"]\"', '\"[\\\"All KINAPA park fees\\\",\\\"KINAPA-licensed lead guide and assistants\\\",\\\"Porters and camp chef\\\",\\\"Mountain hut accommodation\\\",\\\"All meals on the mountain\\\",\\\"Purified drinking water\\\",\\\"Pulse oximetry health checks\\\",\\\"Summit certificate\\\",\\\"Airport transfers\\\",\\\"Pre-trek hotel night in Moshi\\\"]\"', '\"[\\\"International flights\\\",\\\"Visa fees\\\",\\\"Travel insurance (mandatory)\\\",\\\"Personal climbing gear\\\",\\\"Gratuities for mountain crew\\\",\\\"Personal snacks and extras\\\"]\"', NULL, NULL, NULL, NULL, NULL, NULL, '\"{\\\"fr\\\":\\\"Kilimandjaro Voie Marangu \\\\u2014 Trek Classique de 5 jours vers le Sommet\\\",\\\"de\\\":\\\"Kilimandscharo Marangu-Route \\\\u2014 5-Tage Klassischer Gipfeltrek\\\",\\\"es\\\":\\\"Kilimanjaro Ruta Marangu \\\\u2014 Trekking Cl\\\\u00e1sico de 5 d\\\\u00edas a la Cumbre\\\"}\"', '\"{\\\"fr\\\":\\\"Gravissez le plus haut sommet d\'Afrique par la l\\\\u00e9gendaire \\\\u00ab Route Coca-Cola \\\\u00bb \\\\u2014 le seul sentier du Kilimandjaro offrant un h\\\\u00e9bergement en refuge et un d\\\\u00e9nivel\\\\u00e9 plus doux.\\\",\\\"de\\\":\\\"Besteigen Sie Afrikas h\\\\u00f6chsten Gipfel \\\\u00fcber die legend\\\\u00e4re \\\\u00abCoca-Cola-Route\\\\u00bb \\\\u2014 den einzigen Kilimandscharo-Pfad mit H\\\\u00fcttenunterkunft und sanfterem Anstieg.\\\",\\\"es\\\":\\\"Ascienda al pico m\\\\u00e1s alto de \\\\u00c1frica por la legendaria \\\\u00abRuta Coca-Cola\\\\u00bb \\\\u2014 el \\\\u00fanico sendero del Kilimanjaro que ofrece alojamiento en refugios y un desnivel m\\\\u00e1s suave.\\\"}\"', '\"{\\\"fr\\\":\\\"La voie Marangu est la porte d\'entr\\\\u00e9e classique vers le pic Uhuru. Connue affectueusement comme la \\\\u00ab Route Coca-Cola \\\\u00bb, c\'est le seul sentier offrant un h\\\\u00e9bergement permanent en refuge de montagne.\\\\n\\\\nVotre ascension de cinq jours commence \\\\u00e0 la porte Marangu (1 840 m) et traverse quatre zones \\\\u00e9cologiques distinctes : for\\\\u00eat tropicale luxuriante peupl\\\\u00e9e de singes Colobes, lande de bruy\\\\u00e8re avec lobelias g\\\\u00e9antes, d\\\\u00e9sert alpin de scories volcaniques, et enfin la zone arctique du sommet.\\\\n\\\\nLa mont\\\\u00e9e vers le pic Uhuru (5 895 m) commence \\\\u00e0 minuit. Guid\\\\u00e9 par votre \\\\u00e9quipe experte sous un manteau d\'\\\\u00e9toiles, chaque pas vous rapproche du Toit de l\'Afrique. Au lever du soleil, les vues depuis le bord du crat\\\\u00e8re s\'\\\\u00e9tendent jusqu\'\\\\u00e0 la courbure de la Terre.\\\",\\\"de\\\":\\\"Die Marangu-Route ist das klassische Tor zum Uhuru Peak. Liebevoll als \\\\u00abCoca-Cola-Route\\\\u00bb bekannt, ist sie der einzige Pfad mit permanenter H\\\\u00fcttenunterkunft.\\\\n\\\\nIhr f\\\\u00fcnft\\\\u00e4giger Aufstieg beginnt am Marangu Gate (1.840 m) und f\\\\u00fchrt durch vier \\\\u00f6kologische Zonen: \\\\u00fcppigen Bergregenwald mit Colobus-Affen, Heidemoorland mit Riesenlobelien, alpine W\\\\u00fcste aus vulkanischem Ger\\\\u00f6ll und die arktische Gipfelzone.\\\\n\\\\nDer Aufstieg zum Uhuru Peak (5.895 m) beginnt um Mitternacht. Gef\\\\u00fchrt von Ihrem erfahrenen Bergteam unter einem Sternenhimmel, bringt Sie jeder Schritt n\\\\u00e4her ans Dach Afrikas. Bei Sonnenaufgang erstreckt sich der Blick vom Kraterrand bis zur Erdkr\\\\u00fcmmung.\\\",\\\"es\\\":\\\"La ruta Marangu es la puerta de entrada cl\\\\u00e1sica al Pico Uhuru. Conocida cari\\\\u00f1osamente como la \\\\u00abRuta Coca-Cola\\\\u00bb, es el \\\\u00fanico sendero con alojamiento permanente en refugios de monta\\\\u00f1a.\\\\n\\\\nSu ascenso de cinco d\\\\u00edas comienza en la puerta Marangu (1.840 m) y atraviesa cuatro zonas ecol\\\\u00f3gicas: selva tropical con monos Colobus, p\\\\u00e1ramos de brezo con lobelias gigantes, desierto alpino de escoria volc\\\\u00e1nica y la zona \\\\u00e1rtica de la cumbre.\\\\n\\\\nEl ascenso al Pico Uhuru (5.895 m) comienza a medianoche. Guiado por su experto equipo bajo un manto de estrellas, cada paso le acerca al Techo de \\\\u00c1frica. Al amanecer, las vistas desde el borde del cr\\\\u00e1ter se extienden hasta la curvatura de la Tierra.\\\"}\"', NULL, 'Kilimanjaro Marangu Route — 5-Day Hut Trek to Uhuru Peak | Lomo Tanzania', 'Climb Kilimanjaro via the classic Marangu Route in 5 days. Hut accommodation, expert guides, and 4 ecological zones. Book your Uhuru Peak summit trek today.', 'kilimanjaro marangu route, 5 day kilimanjaro trek, coca cola route kilimanjaro, uhuru peak climb, kilimanjaro hut trek, mount kilimanjaro tanzania', NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL),
('9', 'Kilimanjaro Machame Route — 7-Day Whiskey Trail', 'kilimanjaro-machame-7-day-whiskey-trail', 'Take on the dramatic \"Whiskey Route\" — Kilimanjaro\'s most scenic trail with exceptional acclimatisation and a thrilling summit-night approach via Stella Point.', 'The Machame Route is widely regarded as the most beautiful path to the summit of Kilimanjaro. Nicknamed the \"Whiskey Route\" for its greater challenge compared to Marangu, this seven-day itinerary provides superior acclimatisation through its \"climb high, sleep low\" profile, resulting in one of the highest summit success rates on the mountain.\n\nFrom the Machame Gate, you\'ll ascend through a cathedral of moss-draped rainforest, emerging onto the Shira Plateau where the Western Breach towers above. The route traverses the spectacular Barranco Wall — a thrilling scramble rewarded with panoramic views of the Southern Icefields — before the gradual climb through the Karanga Valley to Barafu Base Camp.\n\nSummit night takes you up through switchbacks under the Southern Cross, reaching Stella Point on the crater rim before the final triumphant walk to Uhuru Peak. Your professional mountain team — lead guide, assistants, porters, and private chef — ensures every detail is managed so you can focus entirely on the climb.', '7 Days / 6 Nights', NULL, NULL, 'Challenging', '2202.00', 'USD', NULL, NULL, NULL, NULL, 'published', 'trekking', '2026-04-05 02:41:57', '2026-04-05 02:41:57', '0', '7', '3', '\"[\\\"Kilimanjaro\'s most scenic and popular route\\\",\\\"Superior acclimatisation with \\\\\\\"climb high, sleep low\\\\\\\" profile\\\",\\\"Thrilling Barranco Wall scramble with panoramic views\\\",\\\"Shira Plateau sunset with Western Breach backdrop\\\",\\\"High summit success rate (85%+)\\\",\\\"Professional KINAPA-licensed mountain team\\\",\\\"Private chef preparing gourmet mountain cuisine\\\"]\"', '\"[\\\"All KINAPA park fees\\\",\\\"KINAPA-licensed lead guide and assistants\\\",\\\"Porters and private camp chef\\\",\\\"Quality camping equipment (4-season tents)\\\",\\\"All meals on the mountain\\\",\\\"Purified drinking water\\\",\\\"Pulse oximetry health checks\\\",\\\"Summit certificate\\\",\\\"Airport transfers\\\",\\\"Pre-trek hotel night in Moshi\\\"]\"', '\"[\\\"International flights\\\",\\\"Visa fees\\\",\\\"Travel insurance (mandatory)\\\",\\\"Personal climbing gear\\\",\\\"Gratuities for mountain crew\\\",\\\"Personal snacks\\\"]\"', NULL, NULL, NULL, NULL, NULL, NULL, '\"{\\\"fr\\\":\\\"Kilimandjaro Voie Machame \\\\u2014 Trek de 7 jours par la Route du Whiskey\\\",\\\"de\\\":\\\"Kilimandscharo Machame-Route \\\\u2014 7-Tage Whiskey Trail\\\",\\\"es\\\":\\\"Kilimanjaro Ruta Machame \\\\u2014 Trekking de 7 d\\\\u00edas por la Ruta del Whiskey\\\"}\"', '\"{\\\"fr\\\":\\\"Relevez le d\\\\u00e9fi de la dramatique \\\\u00ab Route du Whiskey \\\\u00bb \\\\u2014 le sentier le plus panoramique du Kilimandjaro avec une acclimatation exceptionnelle.\\\",\\\"de\\\":\\\"Nehmen Sie die dramatische \\\\u00abWhiskey-Route\\\\u00bb in Angriff \\\\u2014 Kilimandscharos landschaftlich sch\\\\u00f6nster Pfad mit hervorragender Akklimatisierung.\\\",\\\"es\\\":\\\"Afronte la espectacular \\\\u00abRuta del Whiskey\\\\u00bb \\\\u2014 el sendero m\\\\u00e1s panor\\\\u00e1mico del Kilimanjaro con una aclimataci\\\\u00f3n excepcional.\\\"}\"', '\"{\\\"fr\\\":\\\"La voie Machame est largement consid\\\\u00e9r\\\\u00e9e comme le plus beau chemin vers le sommet du Kilimandjaro. Ce programme de sept jours offre une acclimatation sup\\\\u00e9rieure gr\\\\u00e2ce \\\\u00e0 son profil \\\\u00ab monter haut, dormir bas \\\\u00bb.\\\\n\\\\nDepuis la porte Machame, vous monterez \\\\u00e0 travers une cath\\\\u00e9drale de for\\\\u00eat tropicale recouverte de mousse, \\\\u00e9mergeant sur le plateau Shira. La route traverse le spectaculaire mur de Barranco \\\\u2014 une escalade palpitante r\\\\u00e9compens\\\\u00e9e par des vues panoramiques sur les champs de glace sud.\\\\n\\\\nLa nuit du sommet vous conduit par des lacets sous la Croix du Sud, atteignant Stella Point sur le bord du crat\\\\u00e8re avant la marche triomphale finale vers le pic Uhuru.\\\",\\\"de\\\":\\\"Die Machame-Route gilt als der sch\\\\u00f6nste Weg zum Gipfel des Kilimandscharo. Dieses siebent\\\\u00e4gige Programm bietet \\\\u00fcberlegene Akklimatisierung durch sein \\\\u00abhoch steigen, tief schlafen\\\\u00bb-Profil.\\\\n\\\\nVom Machame Gate steigen Sie durch eine Kathedrale moosbedeckten Regenwaldes auf und erreichen das Shira-Plateau. Die Route \\\\u00fcberquert die spektakul\\\\u00e4re Barranco-Wand \\\\u2014 ein packender Aufstieg mit Panoramablick auf die s\\\\u00fcdlichen Eisfelder.\\\\n\\\\nDie Gipfelnacht f\\\\u00fchrt \\\\u00fcber Serpentinen unter dem Kreuz des S\\\\u00fcdens zum Stella Point am Kraterrand, bevor der triumphale Gang zum Uhuru Peak folgt.\\\",\\\"es\\\":\\\"La ruta Machame es ampliamente considerada el camino m\\\\u00e1s bello hacia la cumbre del Kilimanjaro. Este itinerario de siete d\\\\u00edas ofrece una aclimataci\\\\u00f3n superior con su perfil de \\\\u00absubir alto, dormir bajo\\\\u00bb.\\\\n\\\\nDesde la puerta Machame, ascender\\\\u00e1 a trav\\\\u00e9s de una catedral de selva tropical cubierta de musgo, emergiendo en la meseta Shira. La ruta atraviesa el espectacular muro de Barranco \\\\u2014 una emocionante escalada recompensada con vistas panor\\\\u00e1micas.\\\\n\\\\nLa noche de cumbre le lleva por zigzags bajo la Cruz del Sur, alcanzando Stella Point en el borde del cr\\\\u00e1ter antes de la caminata triunfal final al Pico Uhuru.\\\"}\"', NULL, 'Kilimanjaro Machame Route — 7-Day Scenic Summit Trek | Lomo Tanzania', 'Climb Kilimanjaro via the scenic Machame Route in 7 days. High summit success rate, Barranco Wall scramble, and expert mountain guides. Book your trek today.', 'kilimanjaro machame route, whiskey route kilimanjaro, 7 day kilimanjaro trek, machame trail, kilimanjaro scenic route, mount kilimanjaro climb', NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL),
('10', 'Kilimanjaro Lemosho Route — 8-Day Wilderness Expedition', 'kilimanjaro-lemosho-8-day-wilderness-expedition', 'The connoisseur\'s choice — Kilimanjaro\'s most remote and pristine route offering unmatched scenery, wildlife encounters, and the highest summit success rate.', 'If there is a route built for those who seek the extraordinary, it is the Lemosho. Beginning on the remote western slopes of Kilimanjaro at Londorossi Gate, this eight-day expedition traverses the mountain\'s wildest terrain — and rewards you with the highest summit success rate of any route (over 90%).\n\nThe first two days pass through untouched montane rainforest, where elephant tracks cross the trail and blue monkeys swing through the canopy. Emerging onto the vast Shira Plateau — a collapsed caldera at 3,600m — you\'ll enjoy 360-degree views stretching from Mount Meru to the Rift Valley.\n\nThe route then joins the Southern Circuit, traversing the Barranco Wall and ascending through the Karanga Valley before the final push from Barafu Camp. An additional acclimatisation day built into the itinerary means your body adapts gradually, dramatically reducing altitude sickness and maximising your summit chances.\n\nThis is our most recommended route for first-time and returning climbers alike — the perfect combination of wilderness immersion, gradual acclimatisation, and summit confidence.', '8 Days / 7 Nights', NULL, NULL, 'Challenging', '2478.00', 'USD', NULL, NULL, NULL, NULL, 'published', 'trekking', '2026-04-05 02:41:57', '2026-04-05 02:41:57', '1', '7', '10', '\"[\\\"Highest summit success rate on Kilimanjaro (90%+)\\\",\\\"Most remote and scenic starting point (Londorossi Gate)\\\",\\\"Untouched montane rainforest with wildlife encounters\\\",\\\"Vast Shira Plateau with 360-degree panoramic views\\\",\\\"Extra acclimatisation day for maximum summit confidence\\\",\\\"Barranco Wall scramble and Southern Circuit traverse\\\",\\\"Our most recommended route for all experience levels\\\"]\"', '\"[\\\"All KINAPA park fees\\\",\\\"KINAPA-licensed lead guide and assistants\\\",\\\"Porters and private camp chef\\\",\\\"Premium camping equipment (4-season tents, sleeping mats)\\\",\\\"All meals on the mountain\\\",\\\"Purified drinking water\\\",\\\"Pulse oximetry and health monitoring\\\",\\\"Summit certificate\\\",\\\"Airport transfers\\\",\\\"Pre-trek hotel night in Moshi\\\"]\"', '\"[\\\"International flights\\\",\\\"Visa fees\\\",\\\"Travel insurance (mandatory)\\\",\\\"Personal climbing gear\\\",\\\"Gratuities for mountain crew\\\",\\\"Personal snacks and extras\\\"]\"', NULL, NULL, NULL, NULL, NULL, NULL, '\"{\\\"fr\\\":\\\"Kilimandjaro Voie Lemosho \\\\u2014 Exp\\\\u00e9dition Sauvage de 8 jours\\\",\\\"de\\\":\\\"Kilimandscharo Lemosho-Route \\\\u2014 8-Tage Wildnis-Expedition\\\",\\\"es\\\":\\\"Kilimanjaro Ruta Lemosho \\\\u2014 Expedici\\\\u00f3n Salvaje de 8 d\\\\u00edas\\\"}\"', '\"{\\\"fr\\\":\\\"Le choix du connaisseur \\\\u2014 la route la plus sauvage et pr\\\\u00e9serv\\\\u00e9e du Kilimandjaro avec des paysages incomparables et le taux de r\\\\u00e9ussite au sommet le plus \\\\u00e9lev\\\\u00e9.\\\",\\\"de\\\":\\\"Die Wahl des Kenners \\\\u2014 Kilimandscharos abgelegenste und unber\\\\u00fchrteste Route mit un\\\\u00fcbertroffener Szenerie und der h\\\\u00f6chsten Gipfelerfolgsquote.\\\",\\\"es\\\":\\\"La elecci\\\\u00f3n del conocedor \\\\u2014 la ruta m\\\\u00e1s remota y pr\\\\u00edstina del Kilimanjaro con paisajes inigualables y la tasa de \\\\u00e9xito en cumbre m\\\\u00e1s alta.\\\"}\"', '\"{\\\"fr\\\":\\\"S\'il existe une route faite pour ceux qui recherchent l\'extraordinaire, c\'est la Lemosho. D\\\\u00e9butant sur les pentes occidentales isol\\\\u00e9es du Kilimandjaro, cette exp\\\\u00e9dition de huit jours traverse les terrains les plus sauvages \\\\u2014 et vous r\\\\u00e9compense avec le taux de r\\\\u00e9ussite au sommet le plus \\\\u00e9lev\\\\u00e9 (plus de 90 %).\\\\n\\\\nLes deux premiers jours traversent une for\\\\u00eat tropicale intacte, o\\\\u00f9 des traces d\'\\\\u00e9l\\\\u00e9phants croisent le sentier et des singes bleus se balancent dans la canop\\\\u00e9e. \\\\u00c9mergeant sur le vaste plateau Shira \\\\u00e0 3 600 m, vous profiterez de vues \\\\u00e0 360 degr\\\\u00e9s s\'\\\\u00e9tendant du mont Meru \\\\u00e0 la vall\\\\u00e9e du Rift.\\\\n\\\\nUn jour d\'acclimatation suppl\\\\u00e9mentaire int\\\\u00e9gr\\\\u00e9 \\\\u00e0 l\'itin\\\\u00e9raire signifie que votre corps s\'adapte progressivement, r\\\\u00e9duisant consid\\\\u00e9rablement le mal d\'altitude et maximisant vos chances au sommet.\\\",\\\"de\\\":\\\"Wenn es eine Route f\\\\u00fcr jene gibt, die das Au\\\\u00dfergew\\\\u00f6hnliche suchen, ist es die Lemosho. Beginnend an den abgelegenen Westh\\\\u00e4ngen des Kilimandscharo, durchquert diese achtt\\\\u00e4gige Expedition das wildeste Terrain des Berges \\\\u2014 mit der h\\\\u00f6chsten Gipfelerfolgsquote aller Routen (\\\\u00fcber 90 %).\\\\n\\\\nDie ersten zwei Tage f\\\\u00fchren durch unber\\\\u00fchrten Bergregenwald, wo Elefantenspuren den Pfad kreuzen und Blaue Meerkatzen durch das Bl\\\\u00e4tterdach schwingen. Auf dem weiten Shira-Plateau auf 3.600 m genie\\\\u00dfen Sie 360-Grad-Panoramen vom Mount Meru bis zum Rift Valley.\\\\n\\\\nEin zus\\\\u00e4tzlicher Akklimatisierungstag bedeutet, dass sich Ihr K\\\\u00f6rper schrittweise anpasst \\\\u2014 die H\\\\u00f6henkrankheit wird dramatisch reduziert und Ihre Gipfelchancen maximiert.\\\",\\\"es\\\":\\\"Si existe una ruta para quienes buscan lo extraordinario, es la Lemosho. Comenzando en las remotas laderas occidentales del Kilimanjaro, esta expedici\\\\u00f3n de ocho d\\\\u00edas atraviesa el terreno m\\\\u00e1s salvaje \\\\u2014 con la tasa de \\\\u00e9xito en cumbre m\\\\u00e1s alta (m\\\\u00e1s del 90 %).\\\\n\\\\nLos dos primeros d\\\\u00edas atraviesan selva tropical intacta, donde huellas de elefantes cruzan el sendero y monos azules se balancean en la copa de los \\\\u00e1rboles. Emergiendo en la vasta meseta Shira a 3.600 m, disfrutar\\\\u00e1 de vistas de 360 grados desde el Monte Meru hasta el Valle del Rift.\\\\n\\\\nUn d\\\\u00eda adicional de aclimataci\\\\u00f3n integrado en el itinerario permite que su cuerpo se adapte gradualmente, reduciendo dr\\\\u00e1sticamente el mal de altura y maximizando sus posibilidades de cumbre.\\\"}\"', NULL, 'Kilimanjaro Lemosho Route — 8-Day Premium Wilderness Trek | Lomo Tanzania', 'Trek Kilimanjaro via the Lemosho Route in 8 days — 90%+ summit success rate, pristine wilderness, and expert guides. The ultimate Kilimanjaro experience. Book now.', 'kilimanjaro lemosho route, 8 day kilimanjaro trek, best kilimanjaro route, lemosho trail, kilimanjaro wilderness trek, highest success rate kilimanjaro', NULL, NULL, NULL, NULL, NULL, NULL, '5', NULL),
('11', 'Kilimanjaro Umbwe Route — 6-Day Direct Ascent', 'kilimanjaro-umbwe-6-day-direct-ascent', 'The steepest and most direct path to the summit — a raw, challenging ascent through primordial forest for experienced trekkers seeking solitude.', 'The Umbwe Route is Kilimanjaro\'s most demanding and least-trafficked trail — a vertical odyssey that appeals to seasoned mountaineers who prize solitude and raw challenge above all else. With the steepest gradient of any route, it climbs relentlessly through ancient, moss-laden forest before breaking onto exposed ridgelines with staggering views.\n\nStarting from the Umbwe Gate on the mountain\'s southern face, you\'ll ascend through dark, atmospheric montane forest draped in old man\'s beard lichen. Camp one sits in a clearing surrounded by tree heathers, while camp two perches on the dramatic Barranco Wall with the Western Breach looming overhead.\n\nThe route then merges with the Southern Circuit via Barafu Camp for the midnight summit attempt. Despite its difficulty, the Umbwe offers an intimacy with Kilimanjaro that no other route can match — days can pass without seeing another climbing party.\n\nRecommended for fit, experienced hikers comfortable with steep terrain and rapid altitude gain. Prior high-altitude experience is strongly advised.', '6 Days / 5 Nights', NULL, NULL, 'Very Challenging', '2003.00', 'USD', NULL, NULL, NULL, NULL, 'published', 'trekking', '2026-04-05 02:41:57', '2026-04-05 02:41:57', '0', '7', '3', '\"[\\\"Kilimanjaro\'s steepest and most direct route\\\",\\\"Least-trafficked trail \\\\u2014 maximum solitude\\\",\\\"Ancient moss-laden forest with old man\'s beard lichen\\\",\\\"Dramatic Barranco Wall camp with Western Breach views\\\",\\\"For experienced trekkers seeking a raw challenge\\\",\\\"Professional KINAPA-licensed mountain team\\\",\\\"Merges with Southern Circuit for summit attempt\\\"]\"', '\"[\\\"All KINAPA park fees\\\",\\\"KINAPA-licensed lead guide and assistants\\\",\\\"Porters and private camp chef\\\",\\\"Quality camping equipment\\\",\\\"All meals on the mountain\\\",\\\"Purified drinking water\\\",\\\"Pulse oximetry health checks\\\",\\\"Summit certificate\\\",\\\"Airport transfers\\\",\\\"Pre-trek hotel night in Moshi\\\"]\"', '\"[\\\"International flights\\\",\\\"Visa fees\\\",\\\"Travel insurance (mandatory)\\\",\\\"Personal climbing gear\\\",\\\"Gratuities for mountain crew\\\",\\\"Personal snacks\\\"]\"', NULL, NULL, NULL, NULL, NULL, NULL, '\"{\\\"fr\\\":\\\"Kilimandjaro Voie Umbwe \\\\u2014 Ascension Directe de 6 jours\\\",\\\"de\\\":\\\"Kilimandscharo Umbwe-Route \\\\u2014 6-Tage Direkter Aufstieg\\\",\\\"es\\\":\\\"Kilimanjaro Ruta Umbwe \\\\u2014 Ascenso Directo de 6 d\\\\u00edas\\\"}\"', '\"{\\\"fr\\\":\\\"Le chemin le plus raide et le plus direct vers le sommet \\\\u2014 une ascension brute \\\\u00e0 travers la for\\\\u00eat primordiale pour les trekkeurs exp\\\\u00e9riment\\\\u00e9s en qu\\\\u00eate de solitude.\\\",\\\"de\\\":\\\"Der steilste und direkteste Weg zum Gipfel \\\\u2014 ein rauer, herausfordernder Aufstieg durch urzeitlichen Wald f\\\\u00fcr erfahrene Trekker, die Einsamkeit suchen.\\\",\\\"es\\\":\\\"El camino m\\\\u00e1s empinado y directo a la cumbre \\\\u2014 un ascenso crudo y desafiante a trav\\\\u00e9s de bosque primordial para excursionistas experimentados que buscan soledad.\\\"}\"', '\"{\\\"fr\\\":\\\"La voie Umbwe est le sentier le plus exigeant et le moins fr\\\\u00e9quent\\\\u00e9 du Kilimandjaro \\\\u2014 une odyss\\\\u00e9e verticale qui s\\\\u00e9duit les alpinistes chevronn\\\\u00e9s qui privil\\\\u00e9gient la solitude et le d\\\\u00e9fi brut.\\\\n\\\\nDepuis la porte Umbwe sur la face sud, vous monterez \\\\u00e0 travers une for\\\\u00eat tropicale sombre et atmosph\\\\u00e9rique drap\\\\u00e9e de lichens barbe de vieillard. Le camp un se niche dans une clairi\\\\u00e8re entour\\\\u00e9e de bruy\\\\u00e8res arborescentes, tandis que le camp deux se perche sur le mur de Barranco.\\\\n\\\\nLa route rejoint ensuite le Circuit Sud via le camp Barafu pour la tentative de sommet \\\\u00e0 minuit. Malgr\\\\u00e9 sa difficult\\\\u00e9, l\'Umbwe offre une intimit\\\\u00e9 avec le Kilimandjaro qu\'aucune autre route ne peut \\\\u00e9galer.\\\",\\\"de\\\":\\\"Die Umbwe-Route ist Kilimandscharos anspruchsvollster und am wenigsten begangener Pfad \\\\u2014 eine vertikale Odyssee f\\\\u00fcr erfahrene Bergsteiger, die Einsamkeit und pure Herausforderung \\\\u00fcber alles sch\\\\u00e4tzen.\\\\n\\\\nVom Umbwe Gate an der S\\\\u00fcdflanke steigen Sie durch dunklen, atmosph\\\\u00e4rischen Bergregenwald auf, behangen mit Bartflechten. Camp eins liegt auf einer Lichtung umgeben von Baumheide, w\\\\u00e4hrend Camp zwei dramatisch auf der Barranco-Wand thront.\\\\n\\\\nDie Route m\\\\u00fcndet dann \\\\u00fcber Barafu Camp in den S\\\\u00fcdkreis f\\\\u00fcr den mittern\\\\u00e4chtlichen Gipfelversuch. Trotz ihrer Schwierigkeit bietet die Umbwe eine N\\\\u00e4he zum Kilimandscharo, die keine andere Route erreichen kann.\\\",\\\"es\\\":\\\"La ruta Umbwe es el sendero m\\\\u00e1s exigente y menos transitado del Kilimanjaro \\\\u2014 una odisea vertical que atrae a monta\\\\u00f1eros experimentados que valoran la soledad y el desaf\\\\u00edo puro.\\\\n\\\\nDesde la puerta Umbwe en la cara sur, ascender\\\\u00e1 a trav\\\\u00e9s de un bosque tropical oscuro y atmosf\\\\u00e9rico cubierto de l\\\\u00edquenes barba de viejo. El campamento uno se encuentra en un claro rodeado de brezos arb\\\\u00f3reos, mientras el campamento dos se sit\\\\u00faa en el dram\\\\u00e1tico muro de Barranco.\\\\n\\\\nLa ruta se une al Circuito Sur v\\\\u00eda campamento Barafu para el intento de cumbre a medianoche. A pesar de su dificultad, la Umbwe ofrece una intimidad con el Kilimanjaro que ninguna otra ruta puede igualar.\\\"}\"', NULL, 'Kilimanjaro Umbwe Route — 6-Day Steep Direct Summit | Lomo Tanzania', 'Challenge yourself on the Umbwe Route — Kilimanjaro\'s steepest, most direct trail. 6 days of solitude, ancient forest, and raw mountain adventure. Expert guides included.', 'kilimanjaro umbwe route, steep kilimanjaro route, 6 day kilimanjaro trek, challenging kilimanjaro, direct summit kilimanjaro, umbwe trail', NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL),
('12', 'Kilimanjaro Rongai Route — 7-Day Northern Approach', 'kilimanjaro-rongai-7-day-northern-approach', 'Approach the Roof of Africa from Kenya\'s border — a quieter, drier route through untouched wilderness with striking views of the Rift Valley.', 'The Rongai Route is the only trail that approaches Kilimanjaro from the north, near the Kenyan border, offering a completely different perspective of the mountain. Its drier climate and gentler gradient make it an excellent choice during the rainy season, while its remote character guarantees a more intimate wilderness experience.\n\nBeginning at the Rongai Gate (1,950m), the trail winds through pine and heather forest before opening onto sprawling moorlands. On clear days, spectacular views of Kenya\'s Tsavo plains stretch to the north. The route is uniquely positioned to witness the mountain\'s northern glaciers and ice cliffs — features rarely seen from other approaches.\n\nThe path joins the summit trail at School Hut before the final midnight push to Gilman\'s Point and onward to Uhuru Peak. Descent is via the Marangu Route, giving you a completely different experience going down — a true traverse of Kilimanjaro.\n\nPerfect for trekkers who value solitude and want to avoid the busier southern routes, particularly during peak climbing season (January-March, June-October).', '7 Days / 6 Nights', NULL, NULL, 'Moderate-Challenging', '2235.00', 'USD', NULL, NULL, NULL, NULL, 'published', 'trekking', '2026-04-05 02:41:57', '2026-04-05 02:41:57', '0', '7', '3', '\"[\\\"Only route approaching Kilimanjaro from the north\\\",\\\"Views of Kenya\'s Tsavo plains and the Rift Valley\\\",\\\"Northern glaciers and ice cliffs rarely seen from other routes\\\",\\\"True traverse: ascend north, descend south via Marangu\\\",\\\"Drier climate \\\\u2014 excellent during rainy season\\\",\\\"Quieter and more remote than southern approaches\\\",\\\"Gentle gradient for comfortable acclimatisation\\\"]\"', '\"[\\\"All KINAPA park fees\\\",\\\"KINAPA-licensed lead guide and assistants\\\",\\\"Porters and private camp chef\\\",\\\"Quality camping equipment\\\",\\\"All meals on the mountain\\\",\\\"Purified drinking water\\\",\\\"Pulse oximetry health checks\\\",\\\"Summit certificate\\\",\\\"Airport transfers\\\",\\\"Pre-trek hotel night in Moshi\\\"]\"', '\"[\\\"International flights\\\",\\\"Visa fees\\\",\\\"Travel insurance (mandatory)\\\",\\\"Personal climbing gear\\\",\\\"Gratuities for mountain crew\\\",\\\"Personal snacks\\\"]\"', NULL, NULL, NULL, NULL, NULL, NULL, '\"{\\\"fr\\\":\\\"Kilimandjaro Voie Rongai \\\\u2014 Approche Nord de 7 jours\\\",\\\"de\\\":\\\"Kilimandscharo Rongai-Route \\\\u2014 7-Tage Nordanstieg\\\",\\\"es\\\":\\\"Kilimanjaro Ruta Rongai \\\\u2014 Aproximaci\\\\u00f3n Norte de 7 d\\\\u00edas\\\"}\"', '\"{\\\"fr\\\":\\\"Approchez le Toit de l\'Afrique depuis la fronti\\\\u00e8re kenyane \\\\u2014 une route plus calme et s\\\\u00e8che \\\\u00e0 travers une nature sauvage intacte avec des vues saisissantes sur la vall\\\\u00e9e du Rift.\\\",\\\"de\\\":\\\"N\\\\u00e4hern Sie sich dem Dach Afrikas von der kenianischen Grenze \\\\u2014 eine ruhigere, trockenere Route durch unber\\\\u00fchrte Wildnis mit atemberaubenden Blicken auf das Rift Valley.\\\",\\\"es\\\":\\\"Ac\\\\u00e9rquese al Techo de \\\\u00c1frica desde la frontera keniana \\\\u2014 una ruta m\\\\u00e1s tranquila y seca a trav\\\\u00e9s de naturaleza virgen con impresionantes vistas del Valle del Rift.\\\"}\"', '\"{\\\"fr\\\":\\\"La voie Rongai est le seul sentier qui approche le Kilimandjaro par le nord, pr\\\\u00e8s de la fronti\\\\u00e8re kenyane. Son climat plus sec et sa pente plus douce en font un excellent choix pendant la saison des pluies.\\\\n\\\\nDepuis la porte Rongai (1 950 m), le sentier serpente \\\\u00e0 travers une for\\\\u00eat de pins et de bruy\\\\u00e8res avant de s\'ouvrir sur de vastes landes. Par temps clair, les vues spectaculaires sur les plaines de Tsavo au Kenya s\'\\\\u00e9tendent vers le nord. La route permet d\'observer les glaciers et falaises de glace du nord.\\\\n\\\\nLe sentier rejoint la piste sommitale au School Hut avant la mont\\\\u00e9e finale vers Gilman\'s Point et le pic Uhuru. La descente se fait par la voie Marangu \\\\u2014 une v\\\\u00e9ritable travers\\\\u00e9e du Kilimandjaro.\\\",\\\"de\\\":\\\"Die Rongai-Route ist der einzige Pfad, der den Kilimandscharo von Norden n\\\\u00e4hert, nahe der kenianischen Grenze. Ihr trockeneres Klima und sanfterer Anstieg machen sie zur ausgezeichneten Wahl in der Regenzeit.\\\\n\\\\nVom Rongai Gate (1.950 m) schl\\\\u00e4ngelt sich der Pfad durch Kiefern- und Heidewald, bevor er sich zu weiten Moorlandschaften \\\\u00f6ffnet. An klaren Tagen erstrecken sich die Tsavo-Ebenen Kenias nach Norden. Die Route bietet einzigartige Blicke auf die n\\\\u00f6rdlichen Gletscher und Eisklippen.\\\\n\\\\nDer Weg m\\\\u00fcndet am School Hut in den Gipfelpfad vor dem mittern\\\\u00e4chtlichen Aufstieg zu Gilman\'s Point und Uhuru Peak. Der Abstieg erfolgt \\\\u00fcber die Marangu-Route \\\\u2014 eine echte Durchquerung des Kilimandscharo.\\\",\\\"es\\\":\\\"La ruta Rongai es el \\\\u00fanico sendero que se acerca al Kilimanjaro desde el norte, cerca de la frontera keniana. Su clima m\\\\u00e1s seco y pendiente m\\\\u00e1s suave la convierten en una excelente opci\\\\u00f3n durante la temporada de lluvias.\\\\n\\\\nDesde la puerta Rongai (1.950 m), el sendero serpentea a trav\\\\u00e9s de bosques de pinos y brezos antes de abrirse a extensos p\\\\u00e1ramos. En d\\\\u00edas claros, las vistas de las llanuras de Tsavo se extienden hacia el norte. La ruta permite observar los glaciares y acantilados de hielo del norte.\\\\n\\\\nEl sendero se une a la ruta de cumbre en School Hut antes del ascenso final a Gilman\'s Point y Pico Uhuru. El descenso es por la ruta Marangu \\\\u2014 una verdadera traves\\\\u00eda del Kilimanjaro.\\\"}\"', NULL, 'Kilimanjaro Rongai Route — 7-Day Northern Approach Trek | Lomo Tanzania', 'Climb Kilimanjaro from the north via the quiet Rongai Route. 7 days, Rift Valley views, northern glaciers, and a true mountain traverse. Book your trek today.', 'kilimanjaro rongai route, northern approach kilimanjaro, 7 day kilimanjaro, quiet kilimanjaro route, rongai trail, kenya border kilimanjaro', NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL),
('13', 'Kilimanjaro Northern Circuit — 9-Day Grand Traverse', 'kilimanjaro-northern-circuit-9-day-grand-traverse', 'The ultimate Kilimanjaro experience — a complete circumnavigation of the mountain offering unrivalled acclimatisation, solitude, and a near-perfect summit rate.', 'The Northern Circuit is the longest and most immersive route on Kilimanjaro — a grand traverse that circumnavigates the entire mountain before the summit push. At nine days, it provides the best acclimatisation of any route, resulting in a near-perfect summit success rate exceeding 95%.\n\nBeginning on the Lemosho approach from the west, the route traverses the Shira Plateau before breaking north — entering terrain that fewer than 5% of Kilimanjaro climbers ever see. The Northern Circuit passes through pristine alpine desert with views of the northern glaciers, the imposing Northern Icefields, and on clear mornings, both Mount Meru and Kenya\'s expanse visible simultaneously.\n\nThe extra days allow your body to acclimatise naturally and thoroughly, turning the mountain into an extended wilderness retreat rather than a race to the top. Camp sites are virtually private, the silence is profound, and the connection to the mountain is deeply personal.\n\nConverging with the traditional routes at Barafu Camp, the summit attempt follows the proven midnight approach to Stella Point and Uhuru Peak. This is the definitive Kilimanjaro experience for those who want to savour every step.', '9 Days / 8 Nights', NULL, NULL, 'Moderate-Challenging', '2831.00', 'USD', NULL, NULL, NULL, NULL, 'published', 'trekking', '2026-04-05 02:41:57', '2026-04-05 02:41:57', '1', '7', '10', '\"[\\\"Near-perfect summit success rate (95%+)\\\",\\\"Complete circumnavigation of Kilimanjaro\\\",\\\"Terrain seen by fewer than 5% of climbers\\\",\\\"Northern Icefields and glaciers up close\\\",\\\"Best natural acclimatisation of any route\\\",\\\"Virtually private campsites and profound wilderness solitude\\\",\\\"Simultaneous views of Mount Meru and Kenya\\\"]\"', '\"[\\\"All KINAPA park fees\\\",\\\"KINAPA-licensed lead guide and assistants\\\",\\\"Porters and private camp chef\\\",\\\"Premium camping equipment (4-season tents, thick sleeping mats)\\\",\\\"All meals on the mountain\\\",\\\"Purified drinking water\\\",\\\"Daily pulse oximetry and health monitoring\\\",\\\"Summit certificate\\\",\\\"Airport transfers\\\",\\\"Pre-trek hotel night in Moshi\\\"]\"', '\"[\\\"International flights\\\",\\\"Visa fees\\\",\\\"Travel insurance (mandatory)\\\",\\\"Personal climbing gear\\\",\\\"Gratuities for mountain crew\\\",\\\"Personal snacks and extras\\\"]\"', NULL, NULL, NULL, NULL, NULL, NULL, '\"{\\\"fr\\\":\\\"Kilimandjaro Circuit Nord \\\\u2014 Grande Travers\\\\u00e9e de 9 jours\\\",\\\"de\\\":\\\"Kilimandscharo Northern Circuit \\\\u2014 9-Tage Gro\\\\u00dfe Durchquerung\\\",\\\"es\\\":\\\"Kilimanjaro Circuito Norte \\\\u2014 Gran Traves\\\\u00eda de 9 d\\\\u00edas\\\"}\"', '\"{\\\"fr\\\":\\\"L\'exp\\\\u00e9rience ultime du Kilimandjaro \\\\u2014 une circumnavigation compl\\\\u00e8te de la montagne offrant acclimatation in\\\\u00e9gal\\\\u00e9e, solitude et un taux de r\\\\u00e9ussite quasi parfait.\\\",\\\"de\\\":\\\"Das ultimative Kilimandscharo-Erlebnis \\\\u2014 eine komplette Umrundung des Berges mit un\\\\u00fcbertroffener Akklimatisierung, Einsamkeit und einer nahezu perfekten Gipfelquote.\\\",\\\"es\\\":\\\"La experiencia definitiva del Kilimanjaro \\\\u2014 una circunnavegaci\\\\u00f3n completa de la monta\\\\u00f1a ofreciendo aclimataci\\\\u00f3n inigualable, soledad y una tasa de \\\\u00e9xito casi perfecta.\\\"}\"', '\"{\\\"fr\\\":\\\"Le Circuit Nord est la route la plus longue et immersive du Kilimandjaro \\\\u2014 une grande travers\\\\u00e9e qui fait le tour complet de la montagne. Avec neuf jours, il offre la meilleure acclimatation et un taux de r\\\\u00e9ussite au sommet d\\\\u00e9passant 95 %.\\\\n\\\\nD\\\\u00e9butant par l\'approche Lemosho depuis l\'ouest, la route traverse le plateau Shira avant de bifurquer vers le nord \\\\u2014 entrant dans un terrain que moins de 5 % des grimpeurs voient jamais. Le circuit passe \\\\u00e0 travers un d\\\\u00e9sert alpin pr\\\\u00e9serv\\\\u00e9 avec des vues sur les glaciers nord et les impressionnants champs de glace.\\\\n\\\\nLes jours suppl\\\\u00e9mentaires permettent \\\\u00e0 votre corps de s\'acclimater naturellement, transformant la montagne en une retraite sauvage prolong\\\\u00e9e. Les emplacements de camping sont pratiquement priv\\\\u00e9s et le silence est profond.\\\\n\\\\nConvergeant avec les routes traditionnelles au camp Barafu, la tentative de sommet suit l\'approche \\\\u00e9prouv\\\\u00e9e de minuit vers Stella Point et le pic Uhuru.\\\",\\\"de\\\":\\\"Der Northern Circuit ist die l\\\\u00e4ngste und intensivste Route am Kilimandscharo \\\\u2014 eine gro\\\\u00dfe Durchquerung, die den gesamten Berg umrundet. Mit neun Tagen bietet er die beste Akklimatisierung und eine Gipfelerfolgsquote von \\\\u00fcber 95 %.\\\\n\\\\nBeginnt mit dem Lemosho-Ansatz von Westen, durchquert das Shira-Plateau und biegt dann nach Norden ab \\\\u2014 in Terrain, das weniger als 5 % der Kletterer je sehen. Der Circuit f\\\\u00fchrt durch unber\\\\u00fchrte alpine W\\\\u00fcste mit Blicken auf die n\\\\u00f6rdlichen Gletscher und Eisfelder.\\\\n\\\\nDie zus\\\\u00e4tzlichen Tage erlauben Ihrem K\\\\u00f6rper, sich nat\\\\u00fcrlich zu akklimatisieren. Campingpl\\\\u00e4tze sind praktisch privat, die Stille ist tiefgreifend und die Verbindung zum Berg zutiefst pers\\\\u00f6nlich.\\\\n\\\\nAm Barafu Camp m\\\\u00fcndet die Route in den traditionellen Gipfelpfad f\\\\u00fcr den bew\\\\u00e4hrten Mitternachtsaufstieg zu Stella Point und Uhuru Peak.\\\",\\\"es\\\":\\\"El Circuito Norte es la ruta m\\\\u00e1s larga e inmersiva del Kilimanjaro \\\\u2014 una gran traves\\\\u00eda que circunnavega toda la monta\\\\u00f1a. Con nueve d\\\\u00edas, ofrece la mejor aclimataci\\\\u00f3n y una tasa de \\\\u00e9xito en cumbre superior al 95 %.\\\\n\\\\nComenzando por la aproximaci\\\\u00f3n Lemosho desde el oeste, la ruta atraviesa la meseta Shira antes de girar al norte \\\\u2014 entrando en terreno que menos del 5 % de los escaladores ven jam\\\\u00e1s. El circuito pasa por un desierto alpino pr\\\\u00edstino con vistas de los glaciares norte y los imponentes campos de hielo.\\\\n\\\\nLos d\\\\u00edas adicionales permiten a su cuerpo aclimatarse naturalmente, convirtiendo la monta\\\\u00f1a en un retiro salvaje prolongado. Los campamentos son pr\\\\u00e1cticamente privados y el silencio es profundo.\\\\n\\\\nConvergiendo con las rutas tradicionales en el campamento Barafu, el intento de cumbre sigue la probada aproximaci\\\\u00f3n de medianoche hacia Stella Point y Pico Uhuru.\\\"}\"', NULL, 'Kilimanjaro Northern Circuit — 9-Day Grand Traverse | Lomo Tanzania', 'The ultimate 9-day Kilimanjaro trek via the Northern Circuit. 95%+ summit rate, complete circumnavigation, and total wilderness immersion. Book the definitive climb.', 'kilimanjaro northern circuit, 9 day kilimanjaro trek, longest kilimanjaro route, best summit success kilimanjaro, northern circuit trail, kilimanjaro grand traverse', NULL, NULL, NULL, NULL, NULL, NULL, '6', 'Premium'),
('14', 'Arusha National Park — Full-Day Wildlife & Canoe Safari', 'arusha-national-park-full-day-wildlife-canoe', 'Discover the hidden gem at Kilimanjaro\'s doorstep — game drives past giraffes and buffalos, then paddle a canoe across the Momella Lakes.', 'Arusha National Park is Tanzania\'s best-kept secret — a compact wilderness spanning from the alkaline Momella Lakes to the summit of Mount Meru, all within 45 minutes of Arusha city. This full-day excursion combines a classic game drive with a unique canoe safari, offering a wonderfully varied day in the African bush.\n\nYour morning begins with a game drive through acacia woodland where giraffes browse treetops, Cape buffalos gather in muddy wallows, and black-and-white colobus monkeys leap through the fig canopy. Keep your camera ready for the park\'s resident flamingos — thousands of lesser flamingos paint the Momella Lakes pink against a backdrop of Mount Meru\'s volcanic cone.\n\nAfter a gourmet picnic lunch overlooking the lakes, you\'ll board a canoe for a guided paddle across Big Momella Lake. Gliding silently past hippos, waterbuck, and nesting African fish eagles provides a perspective on African wildlife unavailable from any vehicle. Return to Arusha by late afternoon.', '1 Day', NULL, NULL, 'Easy', '262.00', 'USD', NULL, NULL, NULL, NULL, 'published', 'safari', '2026-04-05 02:41:57', '2026-04-05 02:41:57', '0', '1', '2', '\"[\\\"Canoe safari across the Momella Lakes\\\",\\\"Thousands of flamingos with Mount Meru backdrop\\\",\\\"Giraffes, buffalos, and colobus monkeys on game drive\\\",\\\"Just 45 minutes from Arusha city\\\",\\\"Gourmet picnic lunch overlooking the lakes\\\",\\\"Hippos and African fish eagles from the canoe\\\"]\"', '\"[\\\"Park entrance fees\\\",\\\"Canoe safari fee\\\",\\\"Private 4x4 vehicle\\\",\\\"Professional guide\\\",\\\"Gourmet picnic lunch, water, and snacks\\\",\\\"Hotel pickup and drop-off in Arusha\\\"]\"', '\"[\\\"Gratuities\\\",\\\"Personal items\\\",\\\"Travel insurance\\\"]\"', NULL, NULL, NULL, NULL, NULL, NULL, '\"{\\\"fr\\\":\\\"Parc National d\'Arusha \\\\u2014 Safari Faune & Cano\\\\u00eb d\'une Journ\\\\u00e9e\\\",\\\"de\\\":\\\"Arusha-Nationalpark \\\\u2014 Ganztages-Wildlife- & Kanutour\\\",\\\"es\\\":\\\"Parque Nacional de Arusha \\\\u2014 Safari de D\\\\u00eda Completo con Canoa\\\"}\"', '\"{\\\"fr\\\":\\\"D\\\\u00e9couvrez le joyau cach\\\\u00e9 aux portes du Kilimandjaro \\\\u2014 safaris avec girafes et buffles, puis pagayez en cano\\\\u00eb sur les lacs Momella.\\\",\\\"de\\\":\\\"Entdecken Sie das versteckte Juwel vor den Toren des Kilimandscharo \\\\u2014 Pirschfahrten mit Giraffen und B\\\\u00fcffeln, dann Kanutour auf den Momella-Seen.\\\",\\\"es\\\":\\\"Descubra la joya oculta a las puertas del Kilimanjaro \\\\u2014 safaris con jirafas y b\\\\u00fafalos, luego reme en canoa por los lagos Momella.\\\"}\"', '\"{\\\"fr\\\":\\\"Le parc national d\'Arusha est le secret le mieux gard\\\\u00e9 de Tanzanie \\\\u2014 une nature compacte s\'\\\\u00e9tendant des lacs alcalins Momella au sommet du mont Meru, \\\\u00e0 seulement 45 minutes d\'Arusha.\\\\n\\\\nVotre matin\\\\u00e9e commence par un safari dans les bois d\'acacias o\\\\u00f9 les girafes broutent les cimes, les buffles du Cap se rassemblent et les singes colobes bondissent dans la canop\\\\u00e9e. Des milliers de flamants roses nains peignent les lacs Momella en rose.\\\\n\\\\nApr\\\\u00e8s un pique-nique gastronomique, montez \\\\u00e0 bord d\'un cano\\\\u00eb pour pagayer sur le Grand Lac Momella. Glisser silencieusement devant les hippopotames et les aigles p\\\\u00eacheurs offre une perspective unique sur la faune africaine.\\\",\\\"de\\\":\\\"Der Arusha-Nationalpark ist Tansanias bestgeh\\\\u00fctetes Geheimnis \\\\u2014 kompakte Wildnis von den alkalischen Momella-Seen bis zum Gipfel des Mount Meru, nur 45 Minuten von Arusha entfernt.\\\\n\\\\nIhr Morgen beginnt mit einer Pirschfahrt durch Akazienw\\\\u00e4lder, wo Giraffen Baumkronen abweiden und Kap-B\\\\u00fcffel sich in Schlamml\\\\u00f6chern versammeln. Tausende Zwergflamingos f\\\\u00e4rben die Momella-Seen vor der Kulisse des Mount Meru rosa.\\\\n\\\\nNach einem Gourmet-Picknick paddeln Sie im Kanu \\\\u00fcber den Gro\\\\u00dfen Momella-See. Lautlos an Flusspferden und Schreiseeadlern vorbeigleiten bietet eine einzigartige Perspektive auf Afrikas Tierwelt.\\\",\\\"es\\\":\\\"El Parque Nacional de Arusha es el secreto mejor guardado de Tanzania \\\\u2014 naturaleza compacta que se extiende desde los lagos alcalinos Momella hasta la cumbre del Monte Meru, a solo 45 minutos de Arusha.\\\\n\\\\nSu ma\\\\u00f1ana comienza con un safari por bosques de acacias donde las jirafas ramonean las copas y los b\\\\u00fafalos del Cabo se re\\\\u00fanen en charcos. Miles de flamencos menores pintan los lagos Momella de rosa.\\\\n\\\\nDespu\\\\u00e9s de un almuerzo gourmet, suba a una canoa para remar por el Gran Lago Momella. Deslizarse silenciosamente junto a hipop\\\\u00f3tamos y \\\\u00e1guilas pescadoras ofrece una perspectiva \\\\u00fanica de la fauna africana.\\\"}\"', NULL, 'Arusha National Park Day Trip — Wildlife & Canoe Safari | Lomo Tanzania', 'Explore Arusha National Park on a full-day safari: game drives, flamingos at Momella Lakes, and a unique canoe safari. Just 45 min from Arusha. Book today.', 'arusha national park, day trip arusha, canoe safari tanzania, momella lakes flamingos, arusha day safari, mount meru national park', NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL),
('15', 'Tarangire National Park — Full-Day Elephant Safari', 'tarangire-national-park-full-day-elephant-safari', 'Spend a full day among Africa\'s largest elephant herds in a landscape of ancient baobabs, golden grasslands, and the winding Tarangire River.', 'Tarangire National Park is an elephant paradise — home to over 3,000 elephants, the largest concentration in northern Tanzania. Set against a backdrop of cathedral-sized baobab trees, some over 1,000 years old, Tarangire offers one of the most photogenic safari experiences in all of Africa.\n\nYour day begins with an early-morning departure from Arusha, arriving at the park\'s main gate as the first golden light washes across the savanna. The morning drive follows the Tarangire River, where dry-season concentrations of wildlife are staggering — elephants, wildebeest, zebra, and giraffe all converge on the receding waters.\n\nMidday brings a shaded picnic at one of the park\'s scenic viewpoints, with tree-dwelling pythons and colourful agama lizards for company. The afternoon game drive focuses on the park\'s renowned predator population: lion prides draped across kopjes, leopards in the branches of sausage trees, and the occasional wild dog sighting.\n\nReturn to Arusha by sunset, having experienced one of Tanzania\'s most underrated wildlife destinations in a single, unforgettable day.', '1 Day', NULL, NULL, 'Easy', '215.00', 'USD', NULL, NULL, NULL, NULL, 'published', 'safari', '2026-04-05 02:41:57', '2026-04-05 02:41:57', '0', '1', '2', '\"[\\\"Over 3,000 elephants \\\\u2014 largest herds in northern Tanzania\\\",\\\"Ancient baobab trees over 1,000 years old\\\",\\\"Lions, leopards, and occasional wild dog sightings\\\",\\\"Tarangire River wildlife concentrations\\\",\\\"Scenic picnic lunch in the park\\\",\\\"Two hours from Arusha \\\\u2014 perfect for a day trip\\\"]\"', '\"[\\\"Park entrance fees\\\",\\\"Private 4x4 vehicle with pop-up roof\\\",\\\"Professional guide\\\",\\\"Picnic lunch, water, and snacks\\\",\\\"Hotel pickup and drop-off in Arusha\\\"]\"', '\"[\\\"Gratuities\\\",\\\"Personal items\\\",\\\"Travel insurance\\\"]\"', NULL, NULL, NULL, NULL, NULL, NULL, '\"{\\\"fr\\\":\\\"Parc National de Tarangire \\\\u2014 Safari \\\\u00c9l\\\\u00e9phants d\'une Journ\\\\u00e9e\\\",\\\"de\\\":\\\"Tarangire-Nationalpark \\\\u2014 Ganztages-Elefanten-Safari\\\",\\\"es\\\":\\\"Parque Nacional Tarangire \\\\u2014 Safari de Elefantes de D\\\\u00eda Completo\\\"}\"', '\"{\\\"fr\\\":\\\"Passez une journ\\\\u00e9e enti\\\\u00e8re parmi les plus grands troupeaux d\'\\\\u00e9l\\\\u00e9phants d\'Afrique dans un paysage de baobabs anciens et de prairies dor\\\\u00e9es.\\\",\\\"de\\\":\\\"Verbringen Sie einen ganzen Tag unter Afrikas gr\\\\u00f6\\\\u00dften Elefantenherden in einer Landschaft aus uralten Baobabs und goldenen Graslandschaften.\\\",\\\"es\\\":\\\"Pase un d\\\\u00eda completo entre las manadas de elefantes m\\\\u00e1s grandes de \\\\u00c1frica en un paisaje de baobabs milenarios y praderas doradas.\\\"}\"', '\"{\\\"fr\\\":\\\"Le parc national de Tarangire est un paradis pour les \\\\u00e9l\\\\u00e9phants \\\\u2014 abritant plus de 3 000 \\\\u00e9l\\\\u00e9phants, la plus grande concentration du nord de la Tanzanie. Sur fond de baobabs cath\\\\u00e9drales vieux de plus de 1 000 ans, Tarangire offre l\'un des safaris les plus photog\\\\u00e9niques d\'Afrique.\\\\n\\\\nVotre journ\\\\u00e9e commence par un d\\\\u00e9part matinal d\'Arusha. Le safari du matin suit la rivi\\\\u00e8re Tarangire, o\\\\u00f9 les concentrations de faune en saison s\\\\u00e8che sont stup\\\\u00e9fiantes. Pique-nique \\\\u00e0 l\'ombre \\\\u00e0 un point de vue panoramique.\\\\n\\\\nLe safari de l\'apr\\\\u00e8s-midi se concentre sur les pr\\\\u00e9dateurs : lions drap\\\\u00e9s sur les kopjes, l\\\\u00e9opards dans les branches des saucissonniers, et parfois des lycaons. Retour \\\\u00e0 Arusha au coucher du soleil.\\\",\\\"de\\\":\\\"Der Tarangire-Nationalpark ist ein Elefantenparadies \\\\u2014 Heimat von \\\\u00fcber 3.000 Elefanten, der gr\\\\u00f6\\\\u00dften Konzentration in Nordtansania. Vor der Kulisse kathedralengro\\\\u00dfer Baobab-B\\\\u00e4ume bietet Tarangire eines der fotogensten Safari-Erlebnisse Afrikas.\\\\n\\\\nIhr Tag beginnt mit einer fr\\\\u00fchen Abfahrt aus Arusha. Die Morgenpirschfahrt folgt dem Tarangire-Fluss, wo die Wildtierkonzentrationen in der Trockenzeit atemberaubend sind. Picknick im Schatten an einem malerischen Aussichtspunkt.\\\\n\\\\nDie Nachmittagspirschfahrt konzentriert sich auf Raubtiere: L\\\\u00f6wenrudel auf Kopjes, Leoparden in Wurstb\\\\u00e4umen und gelegentlich Wildhunde. R\\\\u00fcckkehr nach Arusha bei Sonnenuntergang.\\\",\\\"es\\\":\\\"El Parque Nacional de Tarangire es un para\\\\u00edso para elefantes \\\\u2014 hogar de m\\\\u00e1s de 3.000 elefantes, la mayor concentraci\\\\u00f3n del norte de Tanzania. Con baobabs milenarios como tel\\\\u00f3n de fondo, Tarangire ofrece una de las experiencias de safari m\\\\u00e1s fotog\\\\u00e9nicas de \\\\u00c1frica.\\\\n\\\\nSu d\\\\u00eda comienza con una salida temprana desde Arusha. El safari matutino sigue el r\\\\u00edo Tarangire, donde las concentraciones de fauna en temporada seca son asombrosas. Almuerzo picnic en un mirador panor\\\\u00e1mico.\\\\n\\\\nEl safari vespertino se centra en los depredadores: leones en kopjes, leopardos en \\\\u00e1rboles salchicheros y ocasionales avistamientos de perros salvajes. Regreso a Arusha al atardecer.\\\"}\"', NULL, 'Tarangire Day Safari — Full-Day Elephant Experience | Lomo Tanzania', 'See 3,000+ elephants on a full-day Tarangire safari from Arusha. Ancient baobabs, Big Five predators, and stunning landscapes. Book your Tarangire day trip.', 'tarangire national park, tarangire day trip, elephant safari tanzania, tarangire safari arusha, baobab safari, tanzania day safari', NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL),
('16', 'Ngorongoro Crater — Full-Day Big Five Safari', 'ngorongoro-crater-full-day-big-five', 'Descend into the world\'s largest intact volcanic caldera — a 260 km² natural amphitheatre where the Big Five roam in breathtaking concentration.', 'The Ngorongoro Crater is, quite simply, one of the most extraordinary places on Earth. A UNESCO World Heritage Site and the world\'s largest unbroken volcanic caldera, its 260 square kilometres harbour approximately 25,000 large animals — including all of the Big Five — in a self-contained ecosystem of astounding density.\n\nDeparting Arusha before dawn, you\'ll reach the forested crater rim as morning mist lifts to reveal the vast floor 600 metres below. The descent road winds through montane forest before opening onto a patchwork of grassland, swamp, and soda lake.\n\nOn the crater floor, the density of wildlife is staggering. Black rhinos graze in open grassland, lion prides lie in the shade of fever trees, elephants amble along the Lerai Forest edge, and thousands of flamingos rim the shores of Lake Magadi. Your expert guide knows every corner of the crater, maximising your chances of spotting the rarer species.\n\nA picnic lunch at a designated viewpoint offers panoramic views across the entire caldera. The afternoon drive often reveals cheetah, spotted hyena, and — if fortune favours — the elusive serval cat.', '1 Day', NULL, NULL, 'Easy', '286.00', 'USD', NULL, NULL, NULL, NULL, 'published', 'safari', '2026-04-05 02:41:57', '2026-04-05 02:41:57', '0', '1', '3', '\"[\\\"All Big Five in a single day trip\\\",\\\"UNESCO World Heritage Site\\\",\\\"World\'s largest intact volcanic caldera (260 km\\\\u00b2)\\\",\\\"Black rhino sightings in open grassland\\\",\\\"Thousands of flamingos at Lake Magadi\\\",\\\"Panoramic crater-floor picnic lunch\\\"]\"', '\"[\\\"Park entrance and crater service fees\\\",\\\"Private 4x4 vehicle with pop-up roof\\\",\\\"Professional guide\\\",\\\"Picnic lunch, water, and snacks\\\",\\\"Hotel pickup and drop-off\\\"]\"', '\"[\\\"Gratuities\\\",\\\"Personal items\\\",\\\"Travel insurance\\\"]\"', NULL, NULL, NULL, NULL, NULL, NULL, '\"{\\\"fr\\\":\\\"Crat\\\\u00e8re du Ngorongoro \\\\u2014 Safari Big Five d\'une Journ\\\\u00e9e\\\",\\\"de\\\":\\\"Ngorongoro-Krater \\\\u2014 Ganztages Big-Five-Safari\\\",\\\"es\\\":\\\"Cr\\\\u00e1ter del Ngorongoro \\\\u2014 Safari Big Five de D\\\\u00eda Completo\\\"}\"', '\"{\\\"fr\\\":\\\"Descendez dans la plus grande caldeira volcanique intacte au monde \\\\u2014 un amphith\\\\u00e9\\\\u00e2tre naturel de 260 km\\\\u00b2 o\\\\u00f9 les Big Five \\\\u00e9voluent en concentration saisissante.\\\",\\\"de\\\":\\\"Steigen Sie in die gr\\\\u00f6\\\\u00dfte intakte Vulkankaldeira der Welt hinab \\\\u2014 ein 260 km\\\\u00b2 gro\\\\u00dfes nat\\\\u00fcrliches Amphitheater, in dem die Big Five in atemberaubender Dichte leben.\\\",\\\"es\\\":\\\"Descienda a la caldera volc\\\\u00e1nica intacta m\\\\u00e1s grande del mundo \\\\u2014 un anfiteatro natural de 260 km\\\\u00b2 donde los Big Five deambulan en asombrosa concentraci\\\\u00f3n.\\\"}\"', '\"{\\\"fr\\\":\\\"Le crat\\\\u00e8re du Ngorongoro est tout simplement l\'un des endroits les plus extraordinaires sur Terre. Site du patrimoine mondial de l\'UNESCO, ses 260 km\\\\u00b2 abritent environ 25 000 grands animaux \\\\u2014 dont les Big Five.\\\\n\\\\nAu d\\\\u00e9part d\'Arusha avant l\'aube, vous atteindrez le bord bois\\\\u00e9 du crat\\\\u00e8re alors que la brume matinale se l\\\\u00e8ve. La route de descente serpente \\\\u00e0 travers la for\\\\u00eat montagnarde avant de s\'ouvrir sur un patchwork de prairies, mar\\\\u00e9cages et lac de soude.\\\\n\\\\nSur le plancher du crat\\\\u00e8re, la densit\\\\u00e9 de faune est stup\\\\u00e9fiante. Rhinoc\\\\u00e9ros noirs, lions, \\\\u00e9l\\\\u00e9phants et des milliers de flamants roses au lac Magadi. Un pique-nique offre des vues panoramiques sur toute la caldeira.\\\",\\\"de\\\":\\\"Der Ngorongoro-Krater ist schlichtweg einer der au\\\\u00dfergew\\\\u00f6hnlichsten Orte der Erde. UNESCO-Welterbe, beherbergen seine 260 km\\\\u00b2 etwa 25.000 Gro\\\\u00dftiere \\\\u2014 einschlie\\\\u00dflich aller Big Five.\\\\n\\\\nVor Sonnenaufgang aus Arusha aufbrechend, erreichen Sie den bewaldeten Kraterrand, wenn der Morgennebel sich hebt. Die Abstiegsstra\\\\u00dfe windet sich durch Bergwald, bevor sich Grasland, Sumpf und Sodasee er\\\\u00f6ffnen.\\\\n\\\\nAuf dem Kraterboden ist die Wildtierdichte atemberaubend. Spitzmaulnash\\\\u00f6rner, L\\\\u00f6wenrudel, Elefanten und Tausende Flamingos am Magadi-See. Ein Picknick bietet Panoramablick \\\\u00fcber die gesamte Kaldera.\\\",\\\"es\\\":\\\"El cr\\\\u00e1ter del Ngorongoro es, simplemente, uno de los lugares m\\\\u00e1s extraordinarios de la Tierra. Patrimonio de la Humanidad de la UNESCO, sus 260 km\\\\u00b2 albergan aproximadamente 25.000 grandes animales \\\\u2014 incluyendo los Big Five.\\\\n\\\\nPartiendo de Arusha antes del amanecer, alcanzar\\\\u00e1 el borde boscoso del cr\\\\u00e1ter cuando la niebla matutina se disipa. La carretera de descenso serpentea por bosque montano antes de abrirse a praderas, pantanos y lago de soda.\\\\n\\\\nEn el suelo del cr\\\\u00e1ter, la densidad de fauna es asombrosa. Rinocerontes negros, manadas de leones, elefantes y miles de flamencos en el lago Magadi. Un picnic ofrece vistas panor\\\\u00e1micas de toda la caldera.\\\"}\"', NULL, 'Ngorongoro Crater Day Trip — Big Five Safari from Arusha | Lomo Tanzania', 'See the Big Five in a single day at Ngorongoro Crater — UNESCO World Heritage Site. Black rhinos, lions, flamingos & more. Book your crater safari today.', 'ngorongoro crater, ngorongoro day trip, big five safari, crater safari tanzania, ngorongoro big five, unesco safari, volcanic caldera safari', NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL),
('17', 'Materuni Waterfalls & Coffee Plantation Walk', 'materuni-waterfalls-coffee-plantation-walk', 'A cultural immersion on the slopes of Kilimanjaro — hike through Chagga villages to a stunning 80-metre waterfall and learn to roast Tanzanian Arabica coffee.', 'Nestled on the lush southern slopes of Mount Kilimanjaro, the village of Materuni offers a profoundly authentic Tanzanian experience that combines nature, culture, and world-class coffee. This full-day excursion is the perfect counterpoint to safari life — intimate, unhurried, and deeply human.\n\nYour journey begins at a family-owned Chagga coffee farm, where three generations have cultivated shade-grown Arabica beans on the volcanic soil of Kilimanjaro\'s foothills. You\'ll participate in every stage of the traditional process: picking ripe cherries, hand-roasting over wood fire, grinding with a mortar and pestle, and finally brewing and tasting cups of some of the world\'s finest single-origin coffee.\n\nAfterward, a guided forest trail leads through banana plantations and tropical vegetation to the Materuni Waterfall — an 80-metre cascade of crystal water plunging into a natural pool surrounded by emerald forest. On clear mornings, Kilimanjaro\'s snow-capped Kibo peak towers above the treeline behind you.\n\nA traditional Chagga lunch of plantain stew, fresh vegetables, and local brew concludes the experience before your return to Moshi.', '1 Day', NULL, NULL, 'Easy-Moderate', '118.00', 'USD', NULL, NULL, NULL, NULL, 'published', 'safari', '2026-04-05 02:41:57', '2026-04-05 02:41:57', '0', '5', '2', '\"[\\\"Visit an 80-metre waterfall on Kilimanjaro\'s slopes\\\",\\\"Roast and brew Tanzanian Arabica coffee from cherry to cup\\\",\\\"Walk through authentic Chagga villages and banana plantations\\\",\\\"Traditional Chagga lunch with local family\\\",\\\"Views of Kilimanjaro\'s snow-capped Kibo peak\\\",\\\"Cultural storytelling and village life immersion\\\"]\"', '\"[\\\"Village entrance fees\\\",\\\"Coffee farming experience\\\",\\\"Professional local guide\\\",\\\"Traditional Chagga lunch\\\",\\\"Water and snacks\\\",\\\"Hotel pickup and drop-off in Moshi\\\"]\"', '\"[\\\"Gratuities\\\",\\\"Personal items\\\",\\\"Travel insurance\\\"]\"', NULL, NULL, NULL, NULL, NULL, NULL, '\"{\\\"fr\\\":\\\"Cascades de Materuni & Plantation de Caf\\\\u00e9 \\\\u2014 Excursion Culturelle\\\",\\\"de\\\":\\\"Materuni-Wasserf\\\\u00e4lle & Kaffeeplantagen-Wanderung\\\",\\\"es\\\":\\\"Cascadas de Materuni y Plantaci\\\\u00f3n de Caf\\\\u00e9 \\\\u2014 Excursi\\\\u00f3n Cultural\\\"}\"', '\"{\\\"fr\\\":\\\"Une immersion culturelle sur les pentes du Kilimandjaro \\\\u2014 randonn\\\\u00e9e \\\\u00e0 travers des villages Chagga vers une cascade de 80 m\\\\u00e8tres et torr\\\\u00e9faction de caf\\\\u00e9 Arabica.\\\",\\\"de\\\":\\\"Kulturelle Eintauchung an den H\\\\u00e4ngen des Kilimandscharo \\\\u2014 Wanderung durch Chagga-D\\\\u00f6rfer zu einem 80-Meter-Wasserfall und tansanische Arabica-Kaffeer\\\\u00f6stung.\\\",\\\"es\\\":\\\"Una inmersi\\\\u00f3n cultural en las laderas del Kilimanjaro \\\\u2014 caminata por aldeas Chagga hasta una cascada de 80 metros y tostado de caf\\\\u00e9 ar\\\\u00e1bica tanzano.\\\"}\"', '\"{\\\"fr\\\":\\\"Nich\\\\u00e9 sur les pentes luxuriantes du Kilimandjaro, le village de Materuni offre une exp\\\\u00e9rience tanzanienne profond\\\\u00e9ment authentique m\\\\u00ealant nature, culture et caf\\\\u00e9 de classe mondiale.\\\\n\\\\nVotre voyage commence dans une ferme caf\\\\u00e9i\\\\u00e8re familiale Chagga, o\\\\u00f9 trois g\\\\u00e9n\\\\u00e9rations cultivent l\'Arabica sous ombre. Vous participerez \\\\u00e0 chaque \\\\u00e9tape : cueillette des cerises, torr\\\\u00e9faction au feu de bois, mouture au mortier et d\\\\u00e9gustation d\'un des meilleurs caf\\\\u00e9s du monde.\\\\n\\\\nEnsuite, un sentier forestier traverse des plantations de bananiers jusqu\'\\\\u00e0 la cascade de Materuni \\\\u2014 80 m\\\\u00e8tres d\'eau cristalline plongeant dans un bassin naturel entour\\\\u00e9 de for\\\\u00eat \\\\u00e9meraude. Un d\\\\u00e9jeuner Chagga traditionnel conclut l\'exp\\\\u00e9rience.\\\",\\\"de\\\":\\\"Das Dorf Materuni liegt an den \\\\u00fcppigen S\\\\u00fcdh\\\\u00e4ngen des Kilimandscharo und bietet ein zutiefst authentisches tansanisches Erlebnis aus Natur, Kultur und erstklassigem Kaffee.\\\\n\\\\nIhre Reise beginnt auf einer familiengef\\\\u00fchrten Chagga-Kaffeefarm, wo seit drei Generationen schattengewachsene Arabica-Bohnen angebaut werden. Sie nehmen an jedem Schritt teil: Kirschenpfl\\\\u00fccken, Holzfeuerr\\\\u00f6stung, Mahlen mit M\\\\u00f6rser und St\\\\u00f6\\\\u00dfel, und schlie\\\\u00dflich Br\\\\u00fchen und Verkosten.\\\\n\\\\nDanach f\\\\u00fchrt ein Waldweg durch Bananenplantagen zum Materuni-Wasserfall \\\\u2014 80 Meter Kristallwasser, die in einen nat\\\\u00fcrlichen Pool st\\\\u00fcrzen. Ein traditionelles Chagga-Mittagessen rundet das Erlebnis ab.\\\",\\\"es\\\":\\\"Enclavado en las exuberantes laderas del Kilimanjaro, el pueblo de Materuni ofrece una experiencia tanzana profundamente aut\\\\u00e9ntica que combina naturaleza, cultura y caf\\\\u00e9 de clase mundial.\\\\n\\\\nSu viaje comienza en una finca cafetera familiar Chagga, donde tres generaciones cultivan ar\\\\u00e1bica bajo sombra. Participar\\\\u00e1 en cada etapa: recolecci\\\\u00f3n de cerezas, tostado a fuego de le\\\\u00f1a, molienda con mortero y, finalmente, preparaci\\\\u00f3n y degustaci\\\\u00f3n de uno de los mejores caf\\\\u00e9s del mundo.\\\\n\\\\nDespu\\\\u00e9s, un sendero forestal atraviesa plantaciones de banano hasta la cascada de Materuni \\\\u2014 80 metros de agua cristalina que se precipita en una piscina natural rodeada de bosque esmeralda. Un almuerzo Chagga tradicional concluye la experiencia.\\\"}\"', NULL, 'Materuni Waterfall & Coffee Tour — Cultural Day Trip | Lomo Tanzania', 'Hike to Materuni Waterfall (80m) and learn to roast Kilimanjaro Arabica coffee with a Chagga family. An authentic cultural day trip from Moshi. Book today.', 'materuni waterfall, coffee tour kilimanjaro, chagga village, materuni day trip, kilimanjaro coffee, cultural tour tanzania, moshi day trip', NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL),
('18', 'Lake Manyara National Park — Full-Day Tree-Climbing Lion Safari', 'lake-manyara-full-day-tree-climbing-lion-safari', 'Explore the emerald jewel of the Rift Valley — where tree-climbing lions drape across mahogany branches and flamingo flocks colour the alkaline lake pink.', 'Lake Manyara National Park is one of Tanzania\'s most diverse and compact safari destinations — a ribbon of protected wilderness squeezed between the dramatic western escarpment of the Great Rift Valley and the shimmering alkaline lake that gives the park its name.\n\nFrom Arusha (approximately two hours), you\'ll enter through the park\'s iconic gateway beneath towering fig and mahogany trees. The ground-water forest is alive with olive baboons, blue monkeys, and a canopy thick with hornbills and turacos. But the park\'s greatest fame lies in its tree-climbing lions — one of only two populations in Africa known to regularly ascend into the branches of acacia and mahogany trees.\n\nAs the forest gives way to open grassland, elephants move in family groups along the lake shore, while hippos wallow in the river pools. The lake itself hosts one of East Africa\'s great flamingo spectacles — hundreds of thousands of lesser flamingos creating an uninterrupted band of rose-pink along the water\'s edge.\n\nA leisurely picnic lunch is served overlooking the Rift Valley escarpment before an afternoon drive through the southern acacia woodland. Return to Arusha by late afternoon.', '1 Day', NULL, NULL, 'Easy', '215.00', 'USD', NULL, NULL, NULL, NULL, 'published', 'safari', '2026-04-05 02:41:57', '2026-04-05 02:41:57', '0', '1', '2', '\"[\\\"Famous tree-climbing lions of Lake Manyara\\\",\\\"Hundreds of thousands of flamingos on the alkaline lake\\\",\\\"Dramatic Great Rift Valley escarpment backdrop\\\",\\\"Ground-water forest with baboons and blue monkeys\\\",\\\"Elephants, hippos, and over 400 bird species\\\",\\\"Picnic lunch overlooking the Rift Valley\\\"]\"', '\"[\\\"Park entrance fees\\\",\\\"Private 4x4 vehicle with pop-up roof\\\",\\\"Professional guide\\\",\\\"Picnic lunch, water, and snacks\\\",\\\"Hotel pickup and drop-off in Arusha\\\"]\"', '\"[\\\"Gratuities\\\",\\\"Personal items\\\",\\\"Travel insurance\\\"]\"', NULL, NULL, NULL, NULL, NULL, NULL, '\"{\\\"fr\\\":\\\"Parc National du Lac Manyara \\\\u2014 Safari Lions Grimpeurs d\'une Journ\\\\u00e9e\\\",\\\"de\\\":\\\"Manyara-See Nationalpark \\\\u2014 Ganztages-Safari mit Baumkletternden L\\\\u00f6wen\\\",\\\"es\\\":\\\"Parque Nacional Lago Manyara \\\\u2014 Safari de Leones Trepadores de D\\\\u00eda Completo\\\"}\"', '\"{\\\"fr\\\":\\\"Explorez le joyau \\\\u00e9meraude de la vall\\\\u00e9e du Rift \\\\u2014 o\\\\u00f9 des lions grimpeurs se drapent sur les branches d\'acajou et les flamants colorent le lac alcalin en rose.\\\",\\\"de\\\":\\\"Erkunden Sie das smaragdgr\\\\u00fcne Juwel des Rift Valley \\\\u2014 wo baumkletternde L\\\\u00f6wen auf Mahagonizweigen ruhen und Flamingoschw\\\\u00e4rme den alkalischen See rosa f\\\\u00e4rben.\\\",\\\"es\\\":\\\"Explore la joya esmeralda del Valle del Rift \\\\u2014 donde leones trepadores descansan en ramas de caoba y bandadas de flamencos ti\\\\u00f1en el lago alcalino de rosa.\\\"}\"', '\"{\\\"fr\\\":\\\"Le Parc National du Lac Manyara est l\'une des destinations safari les plus diversifi\\\\u00e9es et compactes de Tanzanie \\\\u2014 un ruban de nature prot\\\\u00e9g\\\\u00e9e entre l\'escarpement occidental du Rift et le lac alcalin scintillant.\\\\n\\\\nDepuis Arusha (environ deux heures), vous entrerez sous des figuiers et acajous majestueux. La for\\\\u00eat abrite babouins oliv\\\\u00e2tres, singes bleus et un canop\\\\u00e9e de calaos et turacos. Mais la gloire du parc r\\\\u00e9side dans ses lions grimpeurs \\\\u2014 l\'une des deux seules populations en Afrique.\\\\n\\\\nLes \\\\u00e9l\\\\u00e9phants se d\\\\u00e9placent en familles le long du rivage, tandis que le lac accueille des centaines de milliers de flamants roses cr\\\\u00e9ant une bande ininterrompue de rose le long des eaux. Pique-nique avec vue sur l\'escarpement du Rift.\\\",\\\"de\\\":\\\"Der Manyara-See Nationalpark ist eines der vielf\\\\u00e4ltigsten Safari-Ziele Tansanias \\\\u2014 ein gesch\\\\u00fctzter Wildnisstreifen zwischen der westlichen Rift-Valley-Steilwand und dem schimmernden alkalischen See.\\\\n\\\\nVon Arusha (circa zwei Stunden) betreten Sie den Park unter gewaltigen Feigen- und Mahagonib\\\\u00e4umen. Der Grundwasserwald beherbergt Olivenpaviane und Diademmeerkatzen. Der gr\\\\u00f6\\\\u00dfte Ruhm des Parks liegt bei seinen baumkletternden L\\\\u00f6wen \\\\u2014 eine von nur zwei solchen Populationen in Afrika.\\\\n\\\\nElefanten ziehen in Familiengruppen am Seeufer entlang, w\\\\u00e4hrend Hunderttausende Zwergflamingos eine ununterbrochene rosa Linie am Wasserrand bilden. Picknick mit Blick auf die Rift-Valley-Steilwand.\\\",\\\"es\\\":\\\"El Parque Nacional del Lago Manyara es uno de los destinos de safari m\\\\u00e1s diversos y compactos de Tanzania \\\\u2014 una franja de naturaleza protegida entre el escarpe occidental del Rift y el reluciente lago alcalino.\\\\n\\\\nDesde Arusha (aproximadamente dos horas), entrar\\\\u00e1 bajo imponentes higueras y caobas. El bosque alberga babuinos oliv\\\\u00e1ceos y monos azules. Pero la mayor fama del parque reside en sus leones trepadores \\\\u2014 una de solo dos poblaciones en \\\\u00c1frica.\\\\n\\\\nLos elefantes se mueven en grupos familiares junto a la orilla, mientras cientos de miles de flamencos crean una banda ininterrumpida de rosa a lo largo del agua. Picnic con vistas al escarpe del Valle del Rift.\\\"}\"', NULL, 'Lake Manyara Day Safari — Tree-Climbing Lions & Flamingos | Lomo Tanzania', 'See Lake Manyara\'s famous tree-climbing lions and flamingos on a full-day safari from Arusha. Rift Valley views, elephants, and 400+ bird species. Book now.', 'lake manyara safari, tree climbing lions, lake manyara day trip, flamingos manyara, rift valley safari, tanzania day safari, manyara national park', NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL),
('19', 'Kilimanjaro Day Hike — Shira Plateau Summit Walk', 'kilimanjaro-day-hike-shira-plateau-summit-walk', 'Experience the magic of Kilimanjaro without a multi-day trek — a guided day hike across the stunning Shira Plateau at 3,800m with panoramic summit views.', 'Not everyone has a week to dedicate to conquering Uhuru Peak, but that doesn\'t mean you can\'t experience the extraordinary beauty of Mount Kilimanjaro. This guided day hike takes you to the Shira Plateau — a vast, otherworldly landscape at 3,800 metres where the air is thin, the views are endless, and the proximity to Africa\'s highest peak is humbling.\n\nDeparting from Moshi in the early morning, a 4x4 vehicle carries you to Shira Gate on the western slope of Kilimanjaro. From there, your KINAPA-licensed guide leads you across the plateau — a collapsed volcanic caldera now carpeted in alpine heath, giant groundsel, and everlasting flowers. The terrain feels lunar, with sweeping views of Kibo\'s glaciated dome directly ahead.\n\nThe hike covers approximately 10 kilometres round trip over moderate terrain, reaching vantage points with unobstructed views of the Kibo summit, the Western Breach, and on clear days, Mount Meru floating above the clouds to the west. Altitude effects are possible but manageable for fit walkers.\n\nA packed gourmet lunch is enjoyed at a scenic stop before the gentle descent. You\'ll return to Moshi by late afternoon with a genuine Kilimanjaro experience under your belt — and an appetite for more.', '1 Day', NULL, NULL, 'Moderate', '308.00', 'USD', NULL, NULL, NULL, NULL, 'published', 'safari', '2026-04-05 02:41:57', '2026-04-05 02:41:57', '0', '7', '3', '\"[\\\"Experience Kilimanjaro at 3,800m without a multi-day trek\\\",\\\"Vast Shira Plateau \\\\u2014 a collapsed volcanic caldera\\\",\\\"Unobstructed views of Kibo summit and Western Breach\\\",\\\"Giant groundsel and alpine heath landscape\\\",\\\"KINAPA-licensed professional guide\\\",\\\"Gourmet packed lunch at a scenic viewpoint\\\",\\\"10 km round trip \\\\u2014 manageable for fit walkers\\\"]\"', '\"[\\\"KINAPA park fees\\\",\\\"KINAPA-licensed guide\\\",\\\"4x4 transfer to Shira Gate\\\",\\\"Gourmet packed lunch and water\\\",\\\"Hotel pickup and drop-off in Moshi\\\"]\"', '\"[\\\"Gratuities\\\",\\\"Personal items\\\",\\\"Travel insurance\\\",\\\"Warm clothing (advisory provided)\\\"]\"', NULL, NULL, NULL, NULL, NULL, NULL, '\"{\\\"fr\\\":\\\"Randonn\\\\u00e9e d\'un Jour au Kilimandjaro \\\\u2014 Marche du Plateau Shira\\\",\\\"de\\\":\\\"Kilimandscharo Tageswanderung \\\\u2014 Shira-Plateau auf 3.800m\\\",\\\"es\\\":\\\"Caminata de un D\\\\u00eda al Kilimanjaro \\\\u2014 Meseta Shira a 3.800m\\\"}\"', '\"{\\\"fr\\\":\\\"Vivez la magie du Kilimandjaro sans trek multi-jours \\\\u2014 une randonn\\\\u00e9e guid\\\\u00e9e sur le plateau Shira \\\\u00e0 3 800 m avec vues panoramiques sur le sommet.\\\",\\\"de\\\":\\\"Erleben Sie die Magie des Kilimandscharo ohne Mehrtageswanderung \\\\u2014 eine gef\\\\u00fchrte Tageswanderung \\\\u00fcber das Shira-Plateau auf 3.800 m mit Panoramablick.\\\",\\\"es\\\":\\\"Experimente la magia del Kilimanjaro sin trekking de varios d\\\\u00edas \\\\u2014 una caminata guiada por la meseta Shira a 3.800 m con vistas panor\\\\u00e1micas a la cumbre.\\\"}\"', '\"{\\\"fr\\\":\\\"Tout le monde n\'a pas une semaine \\\\u00e0 consacrer \\\\u00e0 la conqu\\\\u00eate du pic Uhuru, mais cela ne signifie pas que vous ne pouvez pas vivre la beaut\\\\u00e9 extraordinaire du Kilimandjaro. Cette randonn\\\\u00e9e guid\\\\u00e9e vous emm\\\\u00e8ne au plateau Shira \\\\u2014 un paysage vaste et surnaturel \\\\u00e0 3 800 m\\\\u00e8tres.\\\\n\\\\nAu d\\\\u00e9part de Moshi de bon matin, un 4x4 vous conduit \\\\u00e0 la porte Shira sur le versant ouest. Votre guide licenci\\\\u00e9 KINAPA vous m\\\\u00e8ne \\\\u00e0 travers le plateau \\\\u2014 une caldeira volcanique effondr\\\\u00e9e tapiss\\\\u00e9e de bruy\\\\u00e8re alpine et de s\\\\u00e9ne\\\\u00e7ons g\\\\u00e9ants. Le terrain semble lunaire, avec des vues sur le d\\\\u00f4me glac\\\\u00e9 de Kibo.\\\\n\\\\nLa randonn\\\\u00e9e couvre environ 10 km aller-retour sur un terrain mod\\\\u00e9r\\\\u00e9. Un d\\\\u00e9jeuner gastronomique est servi \\\\u00e0 un point panoramique avant la descente. Retour \\\\u00e0 Moshi en fin d\'apr\\\\u00e8s-midi.\\\",\\\"de\\\":\\\"Nicht jeder hat eine Woche f\\\\u00fcr die Besteigung des Uhuru Peak, aber das hei\\\\u00dft nicht, dass Sie die au\\\\u00dfergew\\\\u00f6hnliche Sch\\\\u00f6nheit des Kilimandscharo nicht erleben k\\\\u00f6nnen. Diese gef\\\\u00fchrte Tageswanderung bringt Sie zum Shira-Plateau \\\\u2014 eine weite, unwirkliche Landschaft auf 3.800 Metern.\\\\n\\\\nAm fr\\\\u00fchen Morgen bringt ein 4x4 Sie vom Moshi zum Shira Gate an der Westflanke. Ihr KINAPA-lizenzierter Guide f\\\\u00fchrt \\\\u00fcber das Plateau \\\\u2014 eine eingest\\\\u00fcrzte Vulkankaldeira mit alpiner Heide und Riesensenezien. Die Aussicht auf Kibos vergletscherten Dom ist atemberaubend.\\\\n\\\\nDie Wanderung umfasst etwa 10 km Rundweg \\\\u00fcber moderates Terrain. Ein Gourmet-Mittagessen an einem malerischen Punkt, dann sanfter Abstieg. R\\\\u00fcckkehr nach Moshi am sp\\\\u00e4ten Nachmittag.\\\",\\\"es\\\":\\\"No todos tienen una semana para conquistar el Pico Uhuru, pero eso no significa que no pueda experimentar la extraordinaria belleza del Kilimanjaro. Esta caminata guiada le lleva a la Meseta Shira \\\\u2014 un vasto paisaje sobrenatural a 3.800 metros.\\\\n\\\\nPartiendo de Moshi temprano, un 4x4 le lleva a la puerta Shira en la ladera oeste. Su gu\\\\u00eda licenciado KINAPA le conduce a trav\\\\u00e9s del plateau \\\\u2014 una caldera volc\\\\u00e1nica colapsada con brezo alpino y senecios gigantes. El terreno parece lunar, con vistas al domo glaciado de Kibo.\\\\n\\\\nLa caminata cubre aproximadamente 10 km ida y vuelta en terreno moderado. Un almuerzo gourmet se sirve en un mirador panor\\\\u00e1mico antes del descenso. Regreso a Moshi por la tarde.\\\"}\"', NULL, 'Kilimanjaro Day Hike — Shira Plateau Walk at 3,800m | Lomo Tanzania', 'Hike Kilimanjaro in a day! Walk across the Shira Plateau at 3,800m with panoramic summit views, alpine flora, and expert guides. No multi-day trek required.', 'kilimanjaro day hike, shira plateau walk, kilimanjaro day trip, mount kilimanjaro day tour, shira gate kilimanjaro, kilimanjaro without camping', NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL);

DROP TABLE IF EXISTS `safari_plans`;
CREATE TABLE `safari_plans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `safari_package_id` bigint unsigned DEFAULT NULL,
  `destinations` text COLLATE utf8mb4_unicode_ci,
  `months` text COLLATE utf8mb4_unicode_ci,
  `travel_group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interests` text COLLATE utf8mb4_unicode_ci,
  `budget_range` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_methods` text COLLATE utf8mb4_unicode_ci,
  `wants_updates` tinyint(1) NOT NULL DEFAULT '0',
  `know_destination` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `safari_package_id` (`safari_package_id`),
  CONSTRAINT `safari_plans_ibfk_1` FOREIGN KEY (`safari_package_id`) REFERENCES `safari_packages` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `safari_plans` (`id`, `safari_package_id`, `destinations`, `months`, `travel_group`, `interests`, `budget_range`, `first_name`, `last_name`, `email`, `country_code`, `phone`, `contact_methods`, `wants_updates`, `know_destination`, `created_at`, `updated_at`) VALUES
('1', '3', '[\"Serengeti National Park\",\"Arusha\",\"Lake Manyara National Park\"]', '[\"January\"]', 'Group', '[]', '$10,000 ??? $20,000', 'Roger', 'Emmanuel', 'Roger@SafarisWithAHeart.com', '255', '754853391', '[\"Email\",\"Phone\"]', '1', NULL, '2026-04-01 14:35:46', '2026-04-01 14:35:46'),
('2', NULL, '[\"Arusha National Park\",\"Arusha\"]', '[\"October\"]', 'Group', '[]', '$2,000 ??? $5,000', 'Roger', 'Emmanuel', 'Roger@SafarisWithAHeart.com', '255', '754853391', '[\"WhatsApp\",\"Phone\"]', '1', 'Yes, I do!', '2026-04-01 14:51:33', '2026-04-01 14:51:33'),
('3', NULL, '[\"Arusha National Park\"]', '[\"Novembre\"]', 'Couple', '[]', '$2,000 ??? $5,000', 'Roger', 'Emmanuel', 'Roger@SafarisWithAHeart.com', '255', '754853391', '[\"WhatsApp\",\"Phone\"]', '1', 'Oui, je sais !', '2026-04-01 22:21:47', '2026-04-01 22:21:47'),
('4', NULL, '[\"Serengeti National Park\",\"Tarangire National Park\",\"Ruaha National Park\",\"Volcanoes National Park\"]', '[\"June\"]', 'Group', '[\"Wildlife Safari\"]', '$2,000 u2013 $5,000', 'Roger', 'Emmanuel', 'Roger@SafarisWithAHeart.com', '+255', '754853391', '[\"WhatsApp\",\"Video Call\"]', '1', 'Yes, I do!', '2026-04-05 17:28:26', '2026-04-05 17:28:26');

DROP TABLE IF EXISTS `safari_requests`;
CREATE TABLE `safari_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `agent_id` bigint unsigned NOT NULL,
  `client_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `travel_date` date NOT NULL,
  `people` bigint NOT NULL DEFAULT '1',
  `destinations` text COLLATE utf8mb4_unicode_ci,
  `activities` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `agent_id` (`agent_id`),
  CONSTRAINT `safari_requests_ibfk_1` FOREIGN KEY (`agent_id`) REFERENCES `agents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `safari_requests` (`id`, `agent_id`, `client_name`, `client_email`, `client_phone`, `country`, `travel_date`, `people`, `destinations`, `activities`, `notes`, `status`, `created_at`, `updated_at`) VALUES
('1', '1', 'Roger Emmanuel', 'Roger@SafarisWithAHeart.com', '+27 458781324565', 'Tanzania', '2026-04-17', '2', '[\"3\",\"2\",\"1\"]', '[\"Luxury\"]', 'asasasasssssssssssssssssssssssssssssss', 'completed', '2026-04-01 16:39:33', '2026-04-01 16:40:48');

DROP TABLE IF EXISTS `seo_image_meta`;
CREATE TABLE `seo_image_meta` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `caption` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_optimized` tinyint(1) NOT NULL DEFAULT '0',
  `original_size` bigint DEFAULT NULL,
  `optimized_size` bigint DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `seo_image_meta` (`id`, `path`, `alt_text`, `seo_filename`, `caption`, `is_optimized`, `original_size`, `optimized_size`, `created_at`, `updated_at`) VALUES
('1', 'accommodations/1mHWCry6qYMiTFC0Oid0S7GjahIGM8vhpMZFlkJw.png', '1m hwcry6q ymi tfc0oid0s7gjah igm8vhp mzflk jw', '1mhwcry6qymitfc0oid0s7gjahigm8vhpmzflkjw.png', NULL, '0', NULL, NULL, '2026-04-02 18:08:33', '2026-04-02 18:08:33'),
('2', 'accommodations/3uMgXzDt3GKCkqAGwk8sqteNDZuWElnOPD8PRKYZ.jpg', '3u mg xz dt3gkckq agwk8sqte ndzu weln opd8prkyz', '3umgxzdt3gkckqagwk8sqtendzuwelnopd8prkyz.jpg', NULL, '0', NULL, NULL, '2026-04-02 18:08:33', '2026-04-02 18:08:33'),
('3', 'accommodations/JmpNefBXQA4e9YQ5sZim8boMGttrO2nUTo3kMIPI.jpg', 'jmp nef bxqa4e9yq5s zim8bo mgttr o2n uto3k mipi', 'jmpnefbxqa4e9yq5szim8bomgttro2nuto3kmipi.jpg', NULL, '0', NULL, NULL, '2026-04-02 18:08:33', '2026-04-02 18:08:33'),
('4', 'accommodations/o2XqO1iL5HgzAIgissdhhIqPFswRSAzi11MTjlGu.png', 'o2xq o1i l5hgz aigissdhh iq pfsw rsazi11mtjl gu', 'o2xqo1il5hgzaigissdhhiqpfswrsazi11mtjlgu.png', NULL, '0', NULL, NULL, '2026-04-02 18:08:33', '2026-04-02 18:08:33'),
('5', 'accommodations/olU2N1yUihT8S2t0F9wszPVn8q6IJGGvKPDpzmEf.jpg', 'ol u2n1y uih t8s2t0f9wsz pvn8q6ijggv kpdpzm ef', 'olu2n1yuiht8s2t0f9wszpvn8q6ijggvkpdpzmef.jpg', NULL, '0', NULL, NULL, '2026-04-02 18:08:33', '2026-04-02 18:08:33'),
('6', 'accommodations/UvKwSKHntNxzsFMSDnyhpys2EpI17ijz16Jwub5v.jpg', 'uv kw skhnt nxzs fmsdnyhpys2ep i17ijz16jwub5v', 'uvkwskhntnxzsfmsdnyhpys2epi17ijz16jwub5v.jpg', NULL, '0', NULL, NULL, '2026-04-02 18:08:33', '2026-04-02 18:08:33'),
('7', 'blog/f9J6LSaLM1mBoNKv4c4Cw6M4gyJTYOnBjW4CVeks.webp', 'f9j6lsa lm1m bo nkv4c4cw6m4gy jtyon bj w4cveks', 'f9j6lsalm1mbonkv4c4cw6m4gyjtyonbjw4cveks.webp', NULL, '0', NULL, NULL, '2026-04-02 18:08:33', '2026-04-02 18:08:33'),
('8', 'countries/b3pjg3h4UktOoCh0ISZ9YcnsiEocC599rWwqrfkp.png', 'b3pjg3h4ukt oo ch0isz9ycnsi eoc c599r wwqrfkp', 'b3pjg3h4uktooch0isz9ycnsieocc599rwwqrfkp.png', NULL, '0', NULL, NULL, '2026-04-02 18:08:33', '2026-04-02 18:08:33'),
('9', 'destinations/BuM271d9OojuksaVdn77Djn9f7B58dSLRDxMmUfi.jpg', 'bu m271d9oojuksa vdn77djn9f7b58d slrdx mm ufi', 'bum271d9oojuksavdn77djn9f7b58dslrdxmmufi.jpg', NULL, '0', NULL, NULL, '2026-04-02 18:08:33', '2026-04-02 18:08:33'),
('10', 'destinations/FLPYMMs6VLHRHfbPIaBRrr9KdIh0OZtrKWRVHW57.jpg', 'flpymms6vlhrhfb pia brrr9kd ih0oztr kwrvhw57', 'flpymms6vlhrhfbpiabrrr9kdih0oztrkwrvhw57.jpg', NULL, '0', NULL, NULL, '2026-04-02 18:08:33', '2026-04-02 18:08:33'),
('11', 'destinations/HqMdNiFFepcNlQDKolaeVnfaIAzZbMkF1bHvBh4n.jpg', 'hq md ni ffepc nl qdkolae vnfa iaz zb mk f1b hv bh4n', 'hqmdniffepcnlqdkolaevnfaiazzbmkf1bhvbh4n.jpg', NULL, '0', NULL, NULL, '2026-04-02 18:08:33', '2026-04-02 18:08:33'),
('12', 'destinations/UjfJnNP4B26JfubB7AGOMygoTKZHBj2VgrrbVF6j.jpg', 'ujf jn np4b26jfub b7agomygo tkzhbj2vgrrb vf6j', 'ujfjnnp4b26jfubb7agomygotkzhbj2vgrrbvf6j.jpg', NULL, '0', NULL, NULL, '2026-04-02 18:08:33', '2026-04-02 18:08:33'),
('13', 'destinations/WN835Z2GeGeJr8burSgC4Vkk5QgGSc9DkJzzlfi0.jpg', 'wn835z2ge ge jr8bur sg c4vkk5qg gsc9dk jzzlfi0', 'wn835z2gegejr8bursgc4vkk5qggsc9dkjzzlfi0.jpg', NULL, '0', NULL, NULL, '2026-04-02 18:08:33', '2026-04-02 18:08:33'),
('14', 'itineraries/ILVAW5WoNGpPJOQKG0iaoF0BqbIdyFLcC1vg87Fq.webp', 'ilvaw5wo ngp pjoqkg0iao f0bqb idy flc c1vg87fq', 'ilvaw5wongppjoqkg0iaof0bqbidyflcc1vg87fq.webp', NULL, '0', NULL, NULL, '2026-04-02 18:08:33', '2026-04-02 18:08:33'),
('15', 'itineraries/J7j4r1j5TzoRrIkEhSDdgKAmHD3ZEdXGq13hhPMm.jpg', 'j7j4r1j5tzo rr ik eh sddg kam hd3zed xgq13hh pmm', 'j7j4r1j5tzorrikehsddgkamhd3zedxgq13hhpmm.jpg', NULL, '0', NULL, NULL, '2026-04-02 18:08:33', '2026-04-02 18:08:33'),
('16', 'itineraries/kFvof7ZxqkqqBaSmTn66QaXchejNGfWHvjCAud93.png', 'k fvof7zxqkqq ba sm tn66qa xchej ngf whvj caud93', 'kfvof7zxqkqqbasmtn66qaxchejngfwhvjcaud93.png', NULL, '0', NULL, NULL, '2026-04-02 18:08:33', '2026-04-02 18:08:33'),
('17', 'itineraries/TFWru34zhV3YuOgBJsvJyYiBCX5hHev7GnpOXpfn.jpg', 'tfwru34zh v3yu og bjsv jy yi bcx5h hev7gnp oxpfn', 'tfwru34zhv3yuogbjsvjyyibcx5hhev7gnpoxpfn.jpg', NULL, '0', NULL, NULL, '2026-04-02 18:08:33', '2026-04-02 18:08:33'),
('18', 'itineraries/uk3f81rGtyx0ofjGzG0pifZg0ze5UuXNkNNt9OTQ.jpg', 'uk3f81r gtyx0ofj gz g0pif zg0ze5uu xnk nnt9otq', 'uk3f81rgtyx0ofjgzg0pifzg0ze5uuxnknnt9otq.jpg', NULL, '0', NULL, NULL, '2026-04-02 18:08:33', '2026-04-02 18:08:33'),
('19', 'itineraries/wJ9r3hX7cwdkNsmH8K7VL88qGTPa8jZ2xh3w0WMy.jpg', 'w j9r3h x7cwdk nsm h8k7vl88q gtpa8j z2xh3w0wmy', 'wj9r3hx7cwdknsmh8k7vl88qgtpa8jz2xh3w0wmy.jpg', NULL, '0', NULL, NULL, '2026-04-02 18:08:33', '2026-04-02 18:08:33'),
('20', 'logo/G8ai80Dq8kXY2EvGZrkBoB2HAJEqr7PBvCGBZxZx.png', 'g8ai80dq8k xy2ev gzrk bo b2hajeqr7pbv cgbzx zx', 'g8ai80dq8kxy2evgzrkbob2hajeqr7pbvcgbzxzx.png', NULL, '0', NULL, NULL, '2026-04-02 18:08:33', '2026-04-02 18:08:33'),
('21', 'maps/safari-2.png', 'safari', 'safari-2.png', NULL, '0', NULL, NULL, '2026-04-02 18:08:33', '2026-04-02 18:08:33'),
('22', 'maps/safari-3.png', 'safari', 'safari-3.png', NULL, '0', NULL, NULL, '2026-04-02 18:08:33', '2026-04-02 18:08:33'),
('23', 'media/w3hC9c76LOmYxt3wtGRMYA6rgokYd9tlKSKx6cFs.webp', 'w3h c9c76lom yxt3wt grmya6rgok yd9tl kskx6c fs', 'w3hc9c76lomyxt3wtgrmya6rgokyd9tlkskx6cfs.webp', NULL, '0', NULL, NULL, '2026-04-02 18:08:33', '2026-04-02 18:08:33'),
('24', 'safaris/gallery/2eqC6ZYruxikV8otn0hkolOEzgKmBq4emZFnpAYp.jpg', '2eq c6zyruxik v8otn0hkol oezg km bq4em zfnp ayp', '2eqc6zyruxikv8otn0hkoloezgkmbq4emzfnpayp.jpg', NULL, '0', NULL, NULL, '2026-04-02 18:08:33', '2026-04-02 18:08:33'),
('25', 'safaris/gallery/4hUywc8BnBeXk5VfJwGQOCkMO8CbHKmBhRZRDqCa.jpg', '4h uywc8bn be xk5vf jw gqock mo8cb hkm bh rzrdq ca', '4huywc8bnbexk5vfjwgqockmo8cbhkmbhrzrdqca.jpg', NULL, '0', NULL, NULL, '2026-04-02 18:08:33', '2026-04-02 18:08:33'),
('26', 'safaris/gallery/FiIVl9A51khhz46QcfWsHrWq2yDSPKD25nY1hJCn.png', 'fi ivl9a51khhz46qcf ws hr wq2y dspkd25n y1h jcn', 'fiivl9a51khhz46qcfwshrwq2ydspkd25ny1hjcn.png', NULL, '0', NULL, NULL, '2026-04-02 18:08:33', '2026-04-02 18:08:33'),
('27', 'safaris/gallery/gW6lzogR3qEAZwK7ZrLre9voMbjjhNbS9PkBWtD1.jpg', 'g w6lzog r3q eazw k7zr lre9vo mbjjh nb s9pk bwt d1', 'gw6lzogr3qeazwk7zrlre9vombjjhnbs9pkbwtd1.jpg', NULL, '0', NULL, NULL, '2026-04-02 18:08:33', '2026-04-02 18:08:33'),
('28', 'safaris/gallery/i1AHzE1eTH2c33WbDjUiEJ2GTJIE5mMxy70il5Ls.png', 'i1ahz e1e th2c33wb dj ui ej2gtjie5m mxy70il5ls', 'i1ahze1eth2c33wbdjuiej2gtjie5mmxy70il5ls.png', NULL, '0', NULL, NULL, '2026-04-02 18:08:33', '2026-04-02 18:08:33'),
('29', 'safaris/gallery/nz29aqUfiqfg4RcuWQ5yoMRQV7d3P3XIgbGF2y6b.jpg', 'nz29aq ufiqfg4rcu wq5yo mrqv7d3p3xigb gf2y6b', 'nz29aqufiqfg4rcuwq5yomrqv7d3p3xigbgf2y6b.jpg', NULL, '0', NULL, NULL, '2026-04-02 18:08:33', '2026-04-02 18:08:33'),
('30', 'safaris/gallery/pnnlT8GL0AGJDHg5CaPC5awiOdZweMB5G8V5OcXq.jpg', 'pnnl t8gl0agjdhg5ca pc5awi od zwe mb5g8v5oc xq', 'pnnlt8gl0agjdhg5capc5awiodzwemb5g8v5ocxq.jpg', NULL, '0', NULL, NULL, '2026-04-02 18:08:33', '2026-04-02 18:08:33'),
('31', 'safaris/gallery/qahEpStvWsPtS8giDkSbI1AMyVNwrHQVp1CP5BAD.jpg', 'qah ep stv ws pt s8gi dk sb i1amy vnwr hqvp1cp5bad', 'qahepstvwspts8gidksbi1amyvnwrhqvp1cp5bad.jpg', NULL, '0', NULL, NULL, '2026-04-02 18:08:33', '2026-04-02 18:08:33'),
('32', 'safaris/gallery/QiwsKiQJyaPTKsZdgNYZSBaV4yeqOr0m7Yj2ckGX.png', 'qiws ki qjya ptks zdg nyzsba v4yeq or0m7yj2ck gx', 'qiwskiqjyaptkszdgnyzsbav4yeqor0m7yj2ckgx.png', NULL, '0', NULL, NULL, '2026-04-02 18:08:33', '2026-04-02 18:08:33'),
('33', 'safaris/gallery/qje770G5h1HG8wYXPjCTejIy4ZaNOWHnTLEqdFJm.jpg', 'qje770g5h1hg8w yxpj ctej iy4za nowhn tleqd fjm', 'qje770g5h1hg8wyxpjctejiy4zanowhntleqdfjm.jpg', NULL, '0', NULL, NULL, '2026-04-02 18:08:33', '2026-04-02 18:08:33'),
('34', 'safaris/gallery/qYXHdanvgQ8RKd1HlbqwOLpB0iWALbIuISirnEKV.png', 'q yxhdanvg q8rkd1hlbqw olp b0i walb iu isirn ekv', 'qyxhdanvgq8rkd1hlbqwolpb0iwalbiuisirnekv.png', NULL, '0', NULL, NULL, '2026-04-02 18:08:33', '2026-04-02 18:08:33'),
('35', 'safaris/J19HK7oWEeDQ2jyYrkRS7oFF5wmoPG1KiFNTSllc.png', 'j19hk7o wee dq2jy yrk rs7o ff5wmo pg1ki fntsllc', 'j19hk7oweedq2jyyrkrs7off5wmopg1kifntsllc.png', NULL, '0', NULL, NULL, '2026-04-02 18:08:33', '2026-04-02 18:08:33'),
('36', 'safaris/Sy6BdtdamfKGcVioraGTB6dbwtjfjZwRwVyYibru.png', 'sy6bdtdamf kgc viora gtb6dbwtjfj zw rw vy yibru', 'sy6bdtdamfkgcvioragtb6dbwtjfjzwrwvyyibru.png', NULL, '0', NULL, NULL, '2026-04-02 18:08:33', '2026-04-02 18:08:33'),
('37', 'tour-types/LG0UVwK7nC8TSZadXgzd0vcy0Agz8sgygMPIcldN.webp', 'lg0uvw k7n c8tszad xgzd0vcy0agz8sgyg mpicld n', 'lg0uvwk7nc8tszadxgzd0vcy0agz8sgygmpicldn.webp', NULL, '0', NULL, NULL, '2026-04-02 18:08:33', '2026-04-02 18:08:33');

DROP TABLE IF EXISTS `seo_keywords`;
CREATE TABLE `seo_keywords` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `keyword` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `intent` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'informational',
  `volume` bigint DEFAULT NULL,
  `difficulty` bigint DEFAULT NULL,
  `target_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `priority` bigint NOT NULL DEFAULT '50',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `seo_link_rules`;
CREATE TABLE `seo_link_rules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `keyword` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `anchor_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `priority` bigint NOT NULL DEFAULT '50',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `seo_markets`;
CREATE TABLE `seo_markets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `source_market` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `intro_content` text COLLATE utf8mb4_unicode_ci,
  `flights_info` text COLLATE utf8mb4_unicode_ci,
  `visa_info` text COLLATE utf8mb4_unicode_ci,
  `travel_tips` text COLLATE utf8mb4_unicode_ci,
  `best_routes` text COLLATE utf8mb4_unicode_ci,
  `pricing_info` text COLLATE utf8mb4_unicode_ci,
  `featured_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title_translations` text COLLATE utf8mb4_unicode_ci,
  `intro_translations` text COLLATE utf8mb4_unicode_ci,
  `is_published` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `seo_meta`;
CREATE TABLE `seo_meta` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `seoable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `seoable_id` bigint NOT NULL,
  `locale` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en',
  `focus_keyword` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secondary_keywords` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug_preview` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_score` bigint NOT NULL DEFAULT '0',
  `readability_score` bigint NOT NULL DEFAULT '0',
  `analysis_data` text COLLATE utf8mb4_unicode_ci,
  `last_analyzed_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `seo_pages`;
CREATE TABLE `seo_pages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keywords` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `intro_content` text COLLATE utf8mb4_unicode_ci,
  `body_content` text COLLATE utf8mb4_unicode_ci,
  `featured_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `filter_criteria` text COLLATE utf8mb4_unicode_ci,
  `title_translations` text COLLATE utf8mb4_unicode_ci,
  `intro_translations` text COLLATE utf8mb4_unicode_ci,
  `body_translations` text COLLATE utf8mb4_unicode_ci,
  `is_auto_generated` tinyint(1) NOT NULL DEFAULT '1',
  `is_published` tinyint(1) NOT NULL DEFAULT '1',
  `views` bigint NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `seo_rank_alerts`;
CREATE TABLE `seo_rank_alerts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `seo_ranking_id` bigint unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_position` bigint DEFAULT NULL,
  `new_position` bigint DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `seo_ranking_id` (`seo_ranking_id`),
  CONSTRAINT `seo_rank_alerts_ibfk_1` FOREIGN KEY (`seo_ranking_id`) REFERENCES `seo_rankings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `seo_rankings`;
CREATE TABLE `seo_rankings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `keyword` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` bigint DEFAULT NULL,
  `previous_position` bigint DEFAULT NULL,
  `search_engine` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'google',
  `locale` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en',
  `history` text COLLATE utf8mb4_unicode_ci,
  `last_checked_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` bigint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('fKihzSSwRLiAy0vduPCUhyP04b8Ghs7idyQ8ZyUU', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 Avira/145.0.34271.162', 'eyJfdG9rZW4iOiJQY0NiUzhEemlRVVpsdlU4blk1aVlHdERyR2pDc3JFT2owOFJiM3M2IiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2FkbWluXC9jaGF0XC80In0sIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYWRtaW5cL25vdGlmaWNhdGlvbnNcL2ZldGNoIiwicm91dGUiOiJhZG1pbi5ub3RpZmljYXRpb25zLmZldGNoIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjF9', '1775465601'),
('Fmnwec9ALOOHlZZEcWqxiYzHR9HFXlGTgeVWb85r', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 Avira/145.0.34271.162', 'eyJfdG9rZW4iOiJicHphQ0ZKQTdSV2VDbW42Q3EwRnpaeUtHSzBMVHpYV1ZyNzMyd0N6IiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2FkbWluXC9ub3RpZmljYXRpb25zXC9mZXRjaCJ9LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2FkbWluXC9ub3RpZmljYXRpb25zXC9mZXRjaCIsInJvdXRlIjoiYWRtaW4ubm90aWZpY2F0aW9ucy5mZXRjaCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxfQ==', '1775469861'),
('n0h8im7tgHDd8AupuyYzQIVRtWLEB7VaYNYXzsMa', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 Avira/145.0.34271.162', 'eyJfdG9rZW4iOiJ2bWVDNURyaHkwMTJyTjFjWHM2T056QjE1MGpuWVY0b3lva1pRSjVjIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2xvbW8tbG9naW4iLCJyb3V0ZSI6InN1cGVyLWFkbWluLmxvZ2luIn19', '1775465781'),
('nYUEiYLskyCSqktUPcInqtn19GhwQovmrMGaOjOL', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiI3aDhiMkFkQTZvNnlBSmdFc3BuN0oxenpIb0ZvRjhPc25GR0hPVTNGIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9lblwvc2FmYXJpcyIsInJvdXRlIjoic2FmYXJpcy5pbmRleCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', '1775462705');

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `site_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Lomo Tanzania Safari',
  `tagline` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `favicon_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_width` int unsigned NOT NULL DEFAULT '176',
  `header_color` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#083321',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `notification_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notify_inquiry` tinyint(1) NOT NULL DEFAULT '1',
  `notify_safari_request` tinyint(1) NOT NULL DEFAULT '1',
  `notify_safari_plan` tinyint(1) NOT NULL DEFAULT '1',
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `default_og_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_analytics_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_search_console` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bing_webmaster_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `yandex_verification_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `baidu_verification_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tripadvisor_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chat_greeting` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chat_enabled` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings` (`id`, `site_name`, `tagline`, `logo_path`, `favicon_path`, `logo_width`, `header_color`, `created_at`, `updated_at`, `notification_email`, `notify_inquiry`, `notify_safari_request`, `notify_safari_plan`, `meta_description`, `default_og_image`, `google_analytics_id`, `google_search_console`, `bing_webmaster_code`, `yandex_verification_code`, `baidu_verification_code`, `whatsapp_number`, `tripadvisor_url`, `phone_number`, `chat_greeting`, `chat_enabled`) VALUES
('1', 'Lomo Tanzania Safari', NULL, 'logo/G8ai80Dq8kXY2EvGZrkBoB2HAJEqr7PBvCGBZxZx.png', NULL, '176', '#083321', '2026-04-01 14:52:21', '2026-04-04 19:36:44', NULL, '0', '0', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+255744702048', NULL, '+255 744 702 048', NULL, '1');

DROP TABLE IF EXISTS `testimonials`;
CREATE TABLE `testimonials` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `safari_package_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` bigint NOT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `safari_package_id` (`safari_package_id`),
  CONSTRAINT `testimonials_ibfk_1` FOREIGN KEY (`safari_package_id`) REFERENCES `safari_packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `tour_types`;
CREATE TABLE `tour_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `featured_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_translations` text COLLATE utf8mb4_unicode_ci,
  `description_translations` text COLLATE utf8mb4_unicode_ci,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `meta_keywords` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tour_types` (`id`, `name`, `slug`, `created_at`, `updated_at`, `description`, `featured_image`, `name_translations`, `description_translations`, `meta_title`, `meta_description`, `meta_keywords`, `og_image`) VALUES
('1', 'Wildlife Safari', 'wildlife-safari', '2026-04-01 23:53:24', '2026-04-02 15:45:13', NULL, 'tour-types/LG0UVwK7nC8TSZadXgzd0vcy0Agz8sgygMPIcldN.webp', '{\"fr\":\"Wildlife Safari\",\"de\":\"Wildtiersafari\",\"es\":\"Safari por la vida silvestre\"}', NULL, NULL, NULL, NULL, NULL),
('2', 'Great Migration', 'great-migration', '2026-04-05 01:36:48', '2026-04-05 01:36:48', 'Follow the annual movement of millions of wildebeest and zebra across the Serengeti-Mara ecosystem.', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('3', 'Luxury Safari', 'luxury-safari', '2026-04-05 01:36:48', '2026-04-05 01:36:48', 'Five-star lodges, private concessions, and bespoke itineraries for the discerning traveller.', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('4', 'Honeymoon Safari', 'honeymoon-safari', '2026-04-05 01:36:48', '2026-04-05 01:36:48', 'Romantic escapes combining bush and beach with intimate camps and sunset dinners.', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('5', 'Cultural Immersion', 'cultural-immersion', '2026-04-05 01:36:48', '2026-04-05 01:36:48', 'Authentic encounters with Maasai, Hadzabe, and other indigenous communities.', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('6', 'Beach & Island', 'beach-island', '2026-04-05 01:36:48', '2026-04-05 01:36:48', 'Zanzibar, Pemba, and coastal retreats — snorkelling, diving, and tropical relaxation.', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('7', 'Mountain Trekking', 'mountain-trekking', '2026-04-05 01:36:48', '2026-04-05 01:36:48', 'Kilimanjaro, Mount Meru, and East Africa\'s dramatic highland trails.', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('8', 'Family Safari', 'family-safari', '2026-04-05 01:36:48', '2026-04-05 01:36:48', 'Child-friendly lodges, shorter drives, and educational bush activities for all ages.', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('9', 'Photography Safari', 'photography-safari', '2026-04-05 01:36:48', '2026-04-05 01:36:48', 'Specialised vehicles, expert guides, and golden-hour positioning for stunning wildlife shots.', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('10', 'Walking Safari', 'walking-safari', '2026-04-05 01:36:48', '2026-04-05 01:36:48', 'Guided bush walks through wild terrain — track animals on foot with armed rangers.', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('11', 'Bird Watching', 'bird-watching', '2026-04-05 01:36:48', '2026-04-05 01:36:48', 'Over 1,100 species across Tanzania — from flamingos to fish eagles and rare endemics.', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('12', 'Gorilla Trekking', 'gorilla-trekking', '2026-04-05 01:36:48', '2026-04-05 01:36:48', 'Face-to-face encounters with mountain gorillas in the misty forests of Uganda and Rwanda.', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pending_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_change_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `department_id` bigint unsigned DEFAULT NULL,
  `language` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en',
  `theme` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'light',
  `notification_preferences` json DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `profile_image`, `bio`, `pending_email`, `email_change_token`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`, `department_id`, `language`, `theme`, `notification_preferences`) VALUES
('1', 'Super Admin', 'admin@lomotanzania.com', NULL, 'profile-images/vpdfSFHl4rMBcm3FmU4slhe1GFQVzgQgOnvlmHLj.png', NULL, NULL, NULL, '2026-03-27 00:50:37', '$2y$12$w5h/JnA52AZS9YsRMefCVOsbPoCnT5C99a1Iu16EIZbMrNvB9ewu6', '3e9uZwganrPFnG5VgrzRyvlY1bTBW1K0LYq4Hqhd4SySRI4aHTlMvoLHawwp', '2026-03-27 00:50:37', '2026-04-06 10:01:04', 'super_admin', NULL, 'en', 'light', '{\"sound_alerts\": \"1\", \"email_bookings\": \"1\", \"email_inquiries\": \"1\"}'),
('2', 'scop', 'scopkariah@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, '$2y$12$DkdovqFSweCAARMsawZ0/eLpK.AhfMKtH.WYeBc4tJP3Asuvihkz6', 'cMclUO5j6XMXEytCqreBwPJMGvSZNUCu3vQ6Iv9Qenexni7i0N1XX6Jo2URA', '2026-04-01 15:17:38', '2026-04-01 15:17:38', 'agent', NULL, 'en', 'light', NULL),
('3', 'scop kariah', 'scopkariaa@gmail.com', '+255758273300', NULL, 'scop kariah', NULL, NULL, NULL, '$2y$12$Dt/BMPlEzu.IvLDKibAxV.0ipVKzt3ELZiN7hbsIzo3LrTlWMvsO6', 'kCmlecFD7b2YRBMq44czf9BGlTc9tJoj4YUKyvpmEhhW8PweExOZ4B53SBzi', '2026-04-04 20:50:48', '2026-04-04 23:58:05', 'worker', NULL, 'en', 'light', NULL);

SET FOREIGN_KEY_CHECKS = 1;
