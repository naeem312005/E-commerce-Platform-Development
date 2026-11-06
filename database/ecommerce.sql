-- ============================================================
-- E-commerce Platform Database Export
-- Candidate: Md Naimul Hasan Naeem
-- Bangla Puzzle Limited - Mid-Level Laravel Developer Assignment
-- Generated: 2026-11-06 15:58:15
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
SET time_zone = '+00:00';

-- ------------------------------------------------------------
-- Table structure for `users`
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `users`
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `created_at`, `updated_at`) VALUES ('1', 'Naimul Hasan', 'naimulhasan312005@gmail.com', '2026-11-06 13:12:24', '$2y$12$Sj.QOJOo7VcFFvjW5xCuX.GNUKrq1HwW9UhG31Hx1/DNr8qs5Mp/S', NULL, NULL, NULL, NULL, '2026-11-06 13:11:32', '2026-11-06 13:12:24');
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `created_at`, `updated_at`) VALUES ('2', 'Md Naimul Hasan Naeem', 'admin@banglapuzzle.com', '2026-11-06 13:12:23', '$2y$12$3W6IQmO0x9s9kqU.WXXy2uy3kWMJuixjib5KZQvBcgAJB/8BevglO', NULL, NULL, NULL, 'Pye2VErKmPsIIiHnL5Nam67r5kWqer5DBoxKT1hU8k6mRuNSZLC7GzUegxWa', '2026-11-06 13:12:23', '2026-11-06 13:12:23');

-- ------------------------------------------------------------
-- Table structure for `categories`
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `categories`
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `is_active`, `created_at`, `updated_at`) VALUES ('1', 'Smartphones & Gadgets', 'smartphones-gadgets', 'Flagship smartphones, smart wearables, and mobile lifestyle accessories.', '0', '2026-11-06 13:12:24', '2026-11-06 15:55:51');
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `is_active`, `created_at`, `updated_at`) VALUES ('2', 'Computers & Accessories', 'computers-accessories', 'High performance laptops, mechanical typing experiences, and gaming peripherals.', '1', '2026-11-06 13:12:24', '2026-11-06 13:12:24');
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `is_active`, `created_at`, `updated_at`) VALUES ('3', 'Audio & Entertainment', 'audio-entertainment', 'Audiophile headphones, noise-canceling wireless earbuds, and spatial sound.', '1', '2026-11-06 13:12:24', '2026-11-06 13:12:24');

-- ------------------------------------------------------------
-- Table structure for `subcategories`
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `subcategories`;
CREATE TABLE `subcategories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subcategories_slug_unique` (`slug`),
  KEY `subcategories_category_id_foreign` (`category_id`),
  CONSTRAINT `subcategories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `subcategories`
INSERT INTO `subcategories` (`id`, `category_id`, `name`, `slug`, `description`, `is_active`, `created_at`, `updated_at`) VALUES ('1', '1', 'Flagship Smartphones', 'flagship-smartphones', 'Top tier flagship and premium Android and iOS devices.', '1', '2026-11-06 13:12:24', '2026-11-06 13:12:24');
INSERT INTO `subcategories` (`id`, `category_id`, `name`, `slug`, `description`, `is_active`, `created_at`, `updated_at`) VALUES ('2', '1', 'Smartwatches & Fitness', 'smartwatches-fitness', 'Health monitoring, GPS tracking, and smart notifications on your wrist.', '1', '2026-11-06 13:12:24', '2026-11-06 13:12:24');
INSERT INTO `subcategories` (`id`, `category_id`, `name`, `slug`, `description`, `is_active`, `created_at`, `updated_at`) VALUES ('3', '2', 'Laptops & Ultrabooks', 'laptops-ultrabooks', 'Slim, powerful, and durable laptops for developers and creators.', '1', '2026-11-06 13:12:24', '2026-11-06 13:12:24');
INSERT INTO `subcategories` (`id`, `category_id`, `name`, `slug`, `description`, `is_active`, `created_at`, `updated_at`) VALUES ('4', '2', 'Mechanical Keyboards', 'mechanical-keyboards', 'Tactile custom mechanical keyboards and premium desk accessories.', '1', '2026-11-06 13:12:24', '2026-11-06 13:12:24');
INSERT INTO `subcategories` (`id`, `category_id`, `name`, `slug`, `description`, `is_active`, `created_at`, `updated_at`) VALUES ('5', '3', 'Wireless Headphones', 'wireless-headphones', 'Active noise cancellation over-ear and wireless listening.', '1', '2026-11-06 13:12:24', '2026-11-06 13:12:24');
INSERT INTO `subcategories` (`id`, `category_id`, `name`, `slug`, `description`, `is_active`, `created_at`, `updated_at`) VALUES ('6', '3', 'Bluetooth Speakers', 'bluetooth-speakers', 'Portable, waterproof room-filling wireless audio sound systems.', '1', '2026-11-06 13:12:24', '2026-11-06 13:12:24');

-- ------------------------------------------------------------
-- Table structure for `products`
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `subcategory_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_price` decimal(10,2) NOT NULL,
  `new_price` decimal(10,2) NOT NULL,
  `stock_quantity` int unsigned NOT NULL DEFAULT '10',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  KEY `products_category_id_foreign` (`category_id`),
  KEY `products_subcategory_id_foreign` (`subcategory_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `products_subcategory_id_foreign` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `products`
INSERT INTO `products` (`id`, `category_id`, `subcategory_id`, `name`, `slug`, `sku`, `description`, `image`, `old_price`, `new_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('1', '1', '1', 'Apple iPhone 15 Pro Max 256GB', 'apple-iphone-15-pro-max-256gb', 'PRD-GVQDHK', 'Forged in titanium and featuring the groundbreaking A17 Pro chip, a customizable Action button, and the most powerful iPhone camera system ever with 5x optical zoom.', 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=800&auto=format&fit=crop&q=80', '175000.00', '159999.00', '15', '1', '2026-11-06 13:12:24', '2026-11-06 15:34:36');
INSERT INTO `products` (`id`, `category_id`, `subcategory_id`, `name`, `slug`, `sku`, `description`, `image`, `old_price`, `new_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('2', '1', '1', 'Samsung Galaxy S24 Ultra 5G', 'samsung-galaxy-s24-ultra-5g', 'PRD-UKWXS6', 'Galaxy AI is here. Meet Galaxy S24 Ultra, featuring a durable titanium exterior, built-in S Pen, and a 200MP camera system with advanced Nightography.', 'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?w=800&auto=format&fit=crop&q=80', '165000.00', '148500.00', '8', '1', '2026-11-06 13:12:24', '2026-11-06 15:34:36');
INSERT INTO `products` (`id`, `category_id`, `subcategory_id`, `name`, `slug`, `sku`, `description`, `image`, `old_price`, `new_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('3', '1', '1', 'Google Pixel 8 Pro', 'google-pixel-8-pro', 'PRD-UPJJKU', 'The engineered by Google phone with the next-gen Tensor G3 chip, best-in-class computational photography, and immersive 120Hz Super Actua display.', 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=800&auto=format&fit=crop&q=80', '115000.00', '98900.00', '4', '1', '2026-11-06 13:12:24', '2026-11-06 15:34:36');
INSERT INTO `products` (`id`, `category_id`, `subcategory_id`, `name`, `slug`, `sku`, `description`, `image`, `old_price`, `new_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('4', '1', '2', 'Apple Watch Ultra 2 Titanium', 'apple-watch-ultra-2-titanium', 'PRD-JLEXAL', 'The most rugged and capable Apple Watch. Features an ultra-bright display, precision dual-frequency GPS, and up to 36 hours of battery life.', 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&auto=format&fit=crop&q=80', '95000.00', '84500.00', '6', '1', '2026-11-06 13:12:24', '2026-11-06 15:34:36');
INSERT INTO `products` (`id`, `category_id`, `subcategory_id`, `name`, `slug`, `sku`, `description`, `image`, `old_price`, `new_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('5', '1', '2', 'Samsung Galaxy Watch 6 Classic', 'samsung-galaxy-watch-6-classic', 'PRD-T1VIE6', 'Rotating bezel design with advanced sleep coaching, body composition analysis, and ECG heart rate tracking in a stainless steel casing.', 'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?w=800&auto=format&fit=crop&q=80', '38000.00', '32500.00', '12', '1', '2026-11-06 13:12:24', '2026-11-06 15:34:36');
INSERT INTO `products` (`id`, `category_id`, `subcategory_id`, `name`, `slug`, `sku`, `description`, `image`, `old_price`, `new_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('6', '2', '3', 'Apple MacBook Pro 16-inch M3 Max', 'apple-macbook-pro-16-inch-m3-max', 'PRD-E2J3Y4', 'Unbelievable pro performance featuring the M3 Max chip with 16-core CPU, 40-core GPU, Liquid Retina XDR display, and up to 22 hours of battery life.', 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=800&auto=format&fit=crop&q=80', '385000.00', '349000.00', '7', '1', '2026-11-06 13:12:24', '2026-11-06 15:34:36');
INSERT INTO `products` (`id`, `category_id`, `subcategory_id`, `name`, `slug`, `sku`, `description`, `image`, `old_price`, `new_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('7', '2', '3', 'Dell XPS 15 OLED InfinityEdge', 'dell-xps-15-oled-infinityedge', 'PRD-JZ9MIR', 'Stunning 3.5K OLED touchscreen display powered by Intel Core i9 13th Gen with NVIDIA GeForce RTX 4070 graphics in CNC machined aluminum.', 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?w=800&auto=format&fit=crop&q=80', '265000.00', '238000.00', '5', '1', '2026-11-06 13:12:24', '2026-11-06 15:34:36');
INSERT INTO `products` (`id`, `category_id`, `subcategory_id`, `name`, `slug`, `sku`, `description`, `image`, `old_price`, `new_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('8', '2', '3', 'ASUS ROG Zephyrus G14 Gaming Laptop', 'asus-rog-zephyrus-g14-gaming-laptop', 'PRD-JFKXUH', 'Compact 14-inch gaming beast with AMD Ryzen 9, ROG Nebula Display with 165Hz refresh rate, and AniMe Matrix LED lid customization.', 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=800&auto=format&fit=crop&q=80', '210000.00', '189999.00', '3', '1', '2026-11-06 13:12:24', '2026-11-06 15:34:36');
INSERT INTO `products` (`id`, `category_id`, `subcategory_id`, `name`, `slug`, `sku`, `description`, `image`, `old_price`, `new_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('9', '2', '4', 'Keychron Q1 Pro Wireless Custom Mechanical Keyboard', 'keychron-q1-pro-wireless-custom-mechanical-keyboard', 'PRD-91Z5PF', 'Full CNC aluminum body, QMK/VIA programmable, double-gasket acoustic design, hot-swappable switches, and multi-device Bluetooth 5.1.', 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=800&auto=format&fit=crop&q=80', '22000.00', '18500.00', '14', '1', '2026-11-06 13:12:24', '2026-11-06 15:34:36');
INSERT INTO `products` (`id`, `category_id`, `subcategory_id`, `name`, `slug`, `sku`, `description`, `image`, `old_price`, `new_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('10', '2', '4', 'Logitech MX Mechanical Wireless Illuminated Keyboard', 'logitech-mx-mechanical-wireless-illuminated-keyboard', 'PRD-5FRNQ8', 'Low-profile mechanical switches, smart backlighting, dual-OS layout with seamless flow control across 3 computers simultaneously.', 'https://images.unsplash.com/photo-1618384887929-16ec33fab9ef?w=800&auto=format&fit=crop&q=80', '17500.00', '14900.00', '9', '1', '2026-11-06 13:12:24', '2026-11-06 15:34:36');
INSERT INTO `products` (`id`, `category_id`, `subcategory_id`, `name`, `slug`, `sku`, `description`, `image`, `old_price`, `new_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('11', '3', '5', 'Sony WH-1000XM5 Wireless Noise-Canceling Headphones', 'sony-wh-1000xm5-wireless-noise-canceling-headphones', 'PRD-19F8LS', 'Industry-leading noise cancellation with two processors and 8 microphones, crystal clear hands-free calling, and 30-hour battery life with quick charging.', 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80', '45000.00', '38900.00', '11', '1', '2026-11-06 13:12:24', '2026-11-06 15:34:36');
INSERT INTO `products` (`id`, `category_id`, `subcategory_id`, `name`, `slug`, `sku`, `description`, `image`, `old_price`, `new_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('12', '3', '5', 'Apple AirPods Max Space Gray', 'apple-airpods-max-space-gray', 'PRD-ZITJ7D', 'High-fidelity audio combined with industry-leading Active Noise Cancellation and Transparency mode. Computational audio creates a theater-like sound experience.', 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=800&auto=format&fit=crop&q=80', '68000.00', '59500.00', '0', '1', '2026-11-06 13:12:24', '2026-11-06 15:34:36');
INSERT INTO `products` (`id`, `category_id`, `subcategory_id`, `name`, `slug`, `sku`, `description`, `image`, `old_price`, `new_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('13', '3', '6', 'Marshall Stanmore III Bluetooth Speaker', 'marshall-stanmore-iii-bluetooth-speaker', 'PRD-J3DWJ3', 'Iconic vintage styling with a wider soundstage, re-engineered sound for a more immersive home audio experience, and Bluetooth 5.2 connectivity.', 'https://images.unsplash.com/photo-1545454675-3531b543be5d?w=800&auto=format&fit=crop&q=80', '42000.00', '36500.00', '7', '1', '2026-11-06 13:12:24', '2026-11-06 15:34:36');

-- ------------------------------------------------------------
-- Table structure for `product_images`
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `product_images`;
CREATE TABLE `product_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_images_product_id_foreign` (`product_id`),
  CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `product_images`
INSERT INTO `product_images` (`id`, `product_id`, `image`, `sort_order`, `created_at`, `updated_at`) VALUES ('28', '1', 'https://images.unsplash.com/photo-1510557880182-3d4d3cba35a5?w=800&auto=format&fit=crop&q=80', '1', '2026-11-06 15:34:36', '2026-11-06 15:34:36');
INSERT INTO `product_images` (`id`, `product_id`, `image`, `sort_order`, `created_at`, `updated_at`) VALUES ('29', '1', 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800&auto=format&fit=crop&q=80', '2', '2026-11-06 15:34:36', '2026-11-06 15:34:36');
INSERT INTO `product_images` (`id`, `product_id`, `image`, `sort_order`, `created_at`, `updated_at`) VALUES ('30', '1', 'https://images.unsplash.com/photo-1565849904461-04a58ad377e0?w=800&auto=format&fit=crop&q=80', '3', '2026-11-06 15:34:36', '2026-11-06 15:34:36');
INSERT INTO `product_images` (`id`, `product_id`, `image`, `sort_order`, `created_at`, `updated_at`) VALUES ('31', '2', 'https://images.unsplash.com/photo-1580910051074-3eb694886505?w=800&auto=format&fit=crop&q=80', '1', '2026-11-06 15:34:36', '2026-11-06 15:34:36');
INSERT INTO `product_images` (`id`, `product_id`, `image`, `sort_order`, `created_at`, `updated_at`) VALUES ('32', '2', 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&auto=format&fit=crop&q=80', '2', '2026-11-06 15:34:36', '2026-11-06 15:34:36');
INSERT INTO `product_images` (`id`, `product_id`, `image`, `sort_order`, `created_at`, `updated_at`) VALUES ('33', '4', 'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?w=800&auto=format&fit=crop&q=80', '1', '2026-11-06 15:34:36', '2026-11-06 15:34:36');
INSERT INTO `product_images` (`id`, `product_id`, `image`, `sort_order`, `created_at`, `updated_at`) VALUES ('34', '6', 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=800&auto=format&fit=crop&q=80', '1', '2026-11-06 15:34:36', '2026-11-06 15:34:36');
INSERT INTO `product_images` (`id`, `product_id`, `image`, `sort_order`, `created_at`, `updated_at`) VALUES ('35', '6', 'https://images.unsplash.com/photo-1611186871348-b1ce696e52c9?w=800&auto=format&fit=crop&q=80', '2', '2026-11-06 15:34:36', '2026-11-06 15:34:36');
INSERT INTO `product_images` (`id`, `product_id`, `image`, `sort_order`, `created_at`, `updated_at`) VALUES ('36', '11', 'https://images.unsplash.com/photo-1484704849700-f032a568e944?w=800&auto=format&fit=crop&q=80', '1', '2026-11-06 15:34:36', '2026-11-06 15:34:36');

-- ------------------------------------------------------------
-- Table structure for `product_variants`
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `product_variants`;
CREATE TABLE `product_variants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `attribute_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attribute_value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `stock_quantity` int unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_variants_product_id_foreign` (`product_id`),
  CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `product_variants`
INSERT INTO `product_variants` (`id`, `product_id`, `attribute_name`, `attribute_value`, `image`, `sku`, `price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('85', '1', 'Color', 'Natural Titanium', 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=800&auto=format&fit=crop&q=80', NULL, NULL, '5', '0', '2026-11-06 15:34:36', '2026-11-06 15:51:55');
INSERT INTO `product_variants` (`id`, `product_id`, `attribute_name`, `attribute_value`, `image`, `sku`, `price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('86', '1', 'Color', 'Blue Titanium', 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800&auto=format&fit=crop&q=80', NULL, NULL, '6', '0', '2026-11-06 15:34:36', '2026-11-06 15:51:55');
INSERT INTO `product_variants` (`id`, `product_id`, `attribute_name`, `attribute_value`, `image`, `sku`, `price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('87', '1', 'Color', 'Black Titanium', 'https://images.unsplash.com/photo-1510557880182-3d4d3cba35a5?w=800&auto=format&fit=crop&q=80', NULL, NULL, '5', '0', '2026-11-06 15:34:36', '2026-11-06 15:51:55');
INSERT INTO `product_variants` (`id`, `product_id`, `attribute_name`, `attribute_value`, `image`, `sku`, `price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('88', '1', 'Storage', '256 GB', NULL, NULL, '159999.00', '8', '0', '2026-11-06 15:34:36', '2026-11-06 15:51:55');
INSERT INTO `product_variants` (`id`, `product_id`, `attribute_name`, `attribute_value`, `image`, `sku`, `price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('89', '1', 'Storage', '512 GB', NULL, NULL, '178500.00', '5', '0', '2026-11-06 15:34:36', '2026-11-06 15:51:55');
INSERT INTO `product_variants` (`id`, `product_id`, `attribute_name`, `attribute_value`, `image`, `sku`, `price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('90', '1', 'Storage', '1 TB', NULL, NULL, '199000.00', '2', '0', '2026-11-06 15:34:36', '2026-11-06 15:51:55');
INSERT INTO `product_variants` (`id`, `product_id`, `attribute_name`, `attribute_value`, `image`, `sku`, `price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('91', '2', 'Color', 'Titanium Gray', 'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?w=800&auto=format&fit=crop&q=80', 'PRD-UKWXS6-TITANIUM-GRAY', NULL, '4', '1', '2026-11-06 15:34:36', '2026-11-06 15:34:36');
INSERT INTO `product_variants` (`id`, `product_id`, `attribute_name`, `attribute_value`, `image`, `sku`, `price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('92', '2', 'Color', 'Titanium Violet', 'https://images.unsplash.com/photo-1580910051074-3eb694886505?w=800&auto=format&fit=crop&q=80', 'PRD-UKWXS6-TITANIUM-VIOLET', NULL, '4', '1', '2026-11-06 15:34:36', '2026-11-06 15:34:36');
INSERT INTO `product_variants` (`id`, `product_id`, `attribute_name`, `attribute_value`, `image`, `sku`, `price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('93', '2', 'Storage', '256 GB', NULL, 'PRD-UKWXS6-256-GB', '148500.00', '5', '1', '2026-11-06 15:34:36', '2026-11-06 15:34:36');
INSERT INTO `product_variants` (`id`, `product_id`, `attribute_name`, `attribute_value`, `image`, `sku`, `price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('94', '2', 'Storage', '512 GB', NULL, 'PRD-UKWXS6-512-GB', '164000.00', '3', '1', '2026-11-06 15:34:36', '2026-11-06 15:34:36');
INSERT INTO `product_variants` (`id`, `product_id`, `attribute_name`, `attribute_value`, `image`, `sku`, `price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('95', '3', 'Color', 'Bay Blue', NULL, 'PRD-UPJJKU-BAY-BLUE', NULL, '2', '1', '2026-11-06 15:34:36', '2026-11-06 15:34:36');
INSERT INTO `product_variants` (`id`, `product_id`, `attribute_name`, `attribute_value`, `image`, `sku`, `price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('96', '3', 'Color', 'Obsidian', NULL, 'PRD-UPJJKU-OBSIDIAN', NULL, '2', '1', '2026-11-06 15:34:36', '2026-11-06 15:34:36');
INSERT INTO `product_variants` (`id`, `product_id`, `attribute_name`, `attribute_value`, `image`, `sku`, `price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('97', '4', 'Band', 'Orange Alpine Loop', NULL, 'PRD-JLEXAL-ORANGE-ALPINE-LOOP', NULL, '3', '1', '2026-11-06 15:34:36', '2026-11-06 15:34:36');
INSERT INTO `product_variants` (`id`, `product_id`, `attribute_name`, `attribute_value`, `image`, `sku`, `price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('98', '4', 'Band', 'Blue Ocean Band', NULL, 'PRD-JLEXAL-BLUE-OCEAN-BAND', NULL, '3', '1', '2026-11-06 15:34:36', '2026-11-06 15:34:36');
INSERT INTO `product_variants` (`id`, `product_id`, `attribute_name`, `attribute_value`, `image`, `sku`, `price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('99', '5', 'Size', '43mm', NULL, 'PRD-T1VIE6-43MM', '32500.00', '6', '1', '2026-11-06 15:34:36', '2026-11-06 15:34:36');
INSERT INTO `product_variants` (`id`, `product_id`, `attribute_name`, `attribute_value`, `image`, `sku`, `price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('100', '5', 'Size', '47mm', NULL, 'PRD-T1VIE6-47MM', '35500.00', '6', '1', '2026-11-06 15:34:36', '2026-11-06 15:34:36');
INSERT INTO `product_variants` (`id`, `product_id`, `attribute_name`, `attribute_value`, `image`, `sku`, `price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('101', '6', 'Color', 'Space Black', NULL, 'PRD-E2J3Y4-SPACE-BLACK', NULL, '4', '1', '2026-11-06 15:34:36', '2026-11-06 15:34:36');
INSERT INTO `product_variants` (`id`, `product_id`, `attribute_name`, `attribute_value`, `image`, `sku`, `price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('102', '6', 'Color', 'Silver', NULL, 'PRD-E2J3Y4-SILVER', NULL, '3', '1', '2026-11-06 15:34:36', '2026-11-06 15:34:36');
INSERT INTO `product_variants` (`id`, `product_id`, `attribute_name`, `attribute_value`, `image`, `sku`, `price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('103', '6', 'Unified Memory', '36 GB', NULL, 'PRD-E2J3Y4-36-GB', '349000.00', '4', '1', '2026-11-06 15:34:36', '2026-11-06 15:34:36');
INSERT INTO `product_variants` (`id`, `product_id`, `attribute_name`, `attribute_value`, `image`, `sku`, `price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('104', '6', 'Unified Memory', '48 GB', NULL, 'PRD-E2J3Y4-48-GB', '389000.00', '3', '1', '2026-11-06 15:34:36', '2026-11-06 15:34:36');
INSERT INTO `product_variants` (`id`, `product_id`, `attribute_name`, `attribute_value`, `image`, `sku`, `price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('105', '9', 'Switch Type', 'Red (Linear)', NULL, 'PRD-91Z5PF-RED-LINEAR', NULL, '5', '1', '2026-11-06 15:34:36', '2026-11-06 15:34:36');
INSERT INTO `product_variants` (`id`, `product_id`, `attribute_name`, `attribute_value`, `image`, `sku`, `price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('106', '9', 'Switch Type', 'Brown (Tactile)', NULL, 'PRD-91Z5PF-BROWN-TACTILE', NULL, '6', '1', '2026-11-06 15:34:36', '2026-11-06 15:34:36');
INSERT INTO `product_variants` (`id`, `product_id`, `attribute_name`, `attribute_value`, `image`, `sku`, `price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('107', '9', 'Switch Type', 'Banana', NULL, 'PRD-91Z5PF-BANANA', NULL, '3', '1', '2026-11-06 15:34:36', '2026-11-06 15:34:36');
INSERT INTO `product_variants` (`id`, `product_id`, `attribute_name`, `attribute_value`, `image`, `sku`, `price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('108', '11', 'Color', 'Black', 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80', 'PRD-19F8LS-BLACK', NULL, '6', '1', '2026-11-06 15:34:36', '2026-11-06 15:34:36');
INSERT INTO `product_variants` (`id`, `product_id`, `attribute_name`, `attribute_value`, `image`, `sku`, `price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('109', '11', 'Color', 'Silver', 'https://images.unsplash.com/photo-1484704849700-f032a568e944?w=800&auto=format&fit=crop&q=80', 'PRD-19F8LS-SILVER', NULL, '5', '1', '2026-11-06 15:34:36', '2026-11-06 15:34:36');
INSERT INTO `product_variants` (`id`, `product_id`, `attribute_name`, `attribute_value`, `image`, `sku`, `price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('110', '12', 'Color', 'Space Gray', NULL, 'PRD-ZITJ7D-SPACE-GRAY', NULL, '0', '1', '2026-11-06 15:34:36', '2026-11-06 15:34:36');
INSERT INTO `product_variants` (`id`, `product_id`, `attribute_name`, `attribute_value`, `image`, `sku`, `price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('111', '12', 'Color', 'Silver', NULL, 'PRD-ZITJ7D-SILVER', NULL, '0', '1', '2026-11-06 15:34:36', '2026-11-06 15:34:36');
INSERT INTO `product_variants` (`id`, `product_id`, `attribute_name`, `attribute_value`, `image`, `sku`, `price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES ('112', '12', 'Color', 'Sky Blue', NULL, 'PRD-ZITJ7D-SKY-BLUE', NULL, '0', '1', '2026-11-06 15:34:36', '2026-11-06 15:34:36');

-- ------------------------------------------------------------
-- Table structure for `migrations`
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `migrations`
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('1', '0001_01_01_000000_create_users_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('2', '0001_01_01_000001_create_cache_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('3', '0001_01_01_000002_create_jobs_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('4', '2024_01_01_000000_create_passkeys_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('5', '2025_08_14_170933_add_two_factor_columns_to_users_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('6', '2026_11_06_130451_create_ecommerce_tables', '2');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('7', '2026_11_06_151212_add_stock_gallery_and_variants_to_products', '3');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('8', '2026_11_06_152855_add_image_to_product_variants_table', '4');

SET FOREIGN_KEY_CHECKS = 1;
