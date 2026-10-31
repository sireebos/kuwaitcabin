<?php
/**
 * MySQL Database Migration Script for Hotel Management System
 * Run this script to create all tables in MySQL/phpMyAdmin
 * 
 * INSTRUCTIONS:
 * 1. Update the database connection settings below
 * 2. Upload this file to your server
 * 3. Run it via browser: https://your-domain.com/mysql_migrate.php
 * 4. Delete this file after migration is complete!
 */

// ==========================================
// DATABASE CONNECTION SETTINGS
// ==========================================
$dbHost = '127.0.0.1';
$dbName = 'sales-protal_DB88';
$dbUser = 'sales-protal_DB88';
$dbPass = 'sales-protal_DB88';

echo "<html><head><title>MySQL Migration</title>";
echo "<style>body{font-family:Arial,sans-serif;padding:20px;max-width:1200px;margin:0 auto;}";
echo "pre{background:#f5f5f5;padding:10px;border:1px solid #ddd;overflow-x:auto;font-size:12px;}";
echo ".success{color:green;}.error{color:red;}.info{color:blue;}</style></head><body>";
echo "<h1>🏨 Hotel Management - MySQL Migration</h1>";

// SQL statements for all tables
$migrations = [

// 1. Properties table (must be first - other tables reference it)
'properties' => "CREATE TABLE IF NOT EXISTS `properties` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `display_name` VARCHAR(255) NULL,
    `logo_path` VARCHAR(255) NULL COMMENT 'Path to hotel logo image',
    `light_logo_path` VARCHAR(255) NULL COMMENT 'Path to light version of hotel logo',
    `favicon_path` VARCHAR(255) NULL COMMENT 'Path to hotel favicon image',
    `background_path` VARCHAR(255) NULL COMMENT 'Path to welcome page background image',
    `home_title` VARCHAR(255) NULL,
    `home_description` TEXT NULL,
    `home_image_path` VARCHAR(255) NULL,
    `home_image_link` VARCHAR(255) NULL,
    `features_enabled` JSON NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `description` TEXT NULL,
    `address` VARCHAR(255) NOT NULL,
    `city` VARCHAR(255) NOT NULL,
    `state` VARCHAR(255) NOT NULL,
    `postal_code` VARCHAR(255) NOT NULL,
    `country` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `website` VARCHAR(255) NULL,
    `total_rooms` INT DEFAULT 0,
    `check_in_time` DECIMAL(5,2) DEFAULT 14.00,
    `check_out_time` DECIMAL(5,2) DEFAULT 11.00,
    `currency` VARCHAR(255) DEFAULT '₦',
    `tax_rate` DECIMAL(5,2) DEFAULT 10.00 NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

// 2. Roles table (needed for users)
'roles' => "CREATE TABLE IF NOT EXISTS `roles` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL UNIQUE,
    `display_name` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    `permissions` JSON NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

// 3. Users table
'users' => "CREATE TABLE IF NOT EXISTS `users` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `property_id` BIGINT UNSIGNED NULL,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `email_verified_at` TIMESTAMP NULL,
    `password` VARCHAR(255) NOT NULL,
    `role_id` BIGINT UNSIGNED NULL,
    `status` ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    `phone` VARCHAR(255) NULL,
    `remember_token` VARCHAR(100) NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    FOREIGN KEY (`property_id`) REFERENCES `properties`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

// 4. Password reset tokens
'password_reset_tokens' => "CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
    `email` VARCHAR(255) PRIMARY KEY,
    `token` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

// 5. Sessions table
'sessions' => "CREATE TABLE IF NOT EXISTS `sessions` (
    `id` VARCHAR(255) PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `payload` LONGTEXT NOT NULL,
    `last_activity` INT NOT NULL,
    INDEX (`user_id`),
    INDEX (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

// 6. Cache table
'cache' => "CREATE TABLE IF NOT EXISTS `cache` (
    `key` VARCHAR(255) PRIMARY KEY,
    `value` MEDIUMTEXT NOT NULL,
    `expiration` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

// 7. Cache locks table
'cache_locks' => "CREATE TABLE IF NOT EXISTS `cache_locks` (
    `key` VARCHAR(255) PRIMARY KEY,
    `owner` VARCHAR(255) NOT NULL,
    `expiration` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

// 8. Jobs table
'jobs' => "CREATE TABLE IF NOT EXISTS `jobs` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `queue` VARCHAR(255) NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `attempts` TINYINT UNSIGNED NOT NULL,
    `reserved_at` INT UNSIGNED NULL,
    `available_at` INT UNSIGNED NOT NULL,
    `created_at` INT UNSIGNED NOT NULL,
    INDEX (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

// 9. Job batches table
'job_batches' => "CREATE TABLE IF NOT EXISTS `job_batches` (
    `id` VARCHAR(255) PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `total_jobs` INT NOT NULL,
    `pending_jobs` INT NOT NULL,
    `failed_jobs` INT NOT NULL,
    `failed_job_ids` LONGTEXT NOT NULL,
    `options` MEDIUMTEXT NULL,
    `cancelled_at` INT NULL,
    `created_at` INT NOT NULL,
    `finished_at` INT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

// 10. Failed jobs table
'failed_jobs' => "CREATE TABLE IF NOT EXISTS `failed_jobs` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `uuid` VARCHAR(255) NOT NULL UNIQUE,
    `connection` TEXT NOT NULL,
    `queue` TEXT NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `exception` LONGTEXT NOT NULL,
    `failed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

// 11. Room types table
'room_types' => "CREATE TABLE IF NOT EXISTS `room_types` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `property_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `description` TEXT NULL,
    `capacity` INT DEFAULT 1,
    `beds` INT DEFAULT 1,
    `base_price` DECIMAL(10,2) NOT NULL,
    `total_rooms` INT DEFAULT 0,
    `amenities` JSON NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    FOREIGN KEY (`property_id`) REFERENCES `properties`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

// 12. Rooms table
'rooms' => "CREATE TABLE IF NOT EXISTS `rooms` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `property_id` BIGINT UNSIGNED NOT NULL,
    `room_type_id` BIGINT UNSIGNED NOT NULL,
    `room_number` VARCHAR(255) NOT NULL,
    `status` ENUM('available', 'occupied', 'maintenance', 'blocked') DEFAULT 'available',
    `floor` INT NULL,
    `wing` VARCHAR(255) NULL,
    `price` DECIMAL(10,2) NULL COMMENT 'Individual room price (overrides room type base price if set)',
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    UNIQUE KEY `rooms_property_room_unique` (`property_id`, `room_number`),
    FOREIGN KEY (`property_id`) REFERENCES `properties`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`room_type_id`) REFERENCES `room_types`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

// 13. Room inventory table
'room_inventory' => "CREATE TABLE IF NOT EXISTS `room_inventory` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `room_id` BIGINT UNSIGNED NOT NULL,
    `date` DATE NOT NULL,
    `status` ENUM('available', 'reserved', 'occupied', 'blocked') DEFAULT 'available',
    `price` DECIMAL(10,2) NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    UNIQUE KEY `room_inventory_room_date_unique` (`room_id`, `date`),
    FOREIGN KEY (`room_id`) REFERENCES `rooms`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

// 14. Rate plans table
'rate_plans' => "CREATE TABLE IF NOT EXISTS `rate_plans` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `property_id` BIGINT UNSIGNED NOT NULL,
    `room_type_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    `base_rate` DECIMAL(10,2) NOT NULL,
    `discount_percentage` DECIMAL(5,2) DEFAULT 0,
    `valid_from` DATE NOT NULL,
    `valid_to` DATE NOT NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    FOREIGN KEY (`property_id`) REFERENCES `properties`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`room_type_id`) REFERENCES `room_types`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

// 15. Guests table
'guests' => "CREATE TABLE IF NOT EXISTS `guests` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `property_id` BIGINT UNSIGNED NOT NULL,
    `first_name` VARCHAR(255) NOT NULL,
    `last_name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `phone` VARCHAR(255) NOT NULL,
    `id_type` VARCHAR(255) NULL,
    `id_number` VARCHAR(255) NULL,
    `nationality` VARCHAR(255) NULL,
    `address` VARCHAR(255) NULL,
    `city` VARCHAR(255) NULL,
    `state` VARCHAR(255) NULL,
    `postal_code` VARCHAR(255) NULL,
    `country` VARCHAR(255) NULL,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    FOREIGN KEY (`property_id`) REFERENCES `properties`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

// 16. Bookings table
'bookings' => "CREATE TABLE IF NOT EXISTS `bookings` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `property_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NULL,
    `guest_id` BIGINT UNSIGNED NOT NULL,
    `room_id` BIGINT UNSIGNED NULL,
    `booking_reference` VARCHAR(255) NOT NULL UNIQUE,
    `check_in_date` DATE NOT NULL,
    `check_out_date` DATE NOT NULL,
    `number_of_guests` INT DEFAULT 1,
    `number_of_nights` INT NOT NULL,
    `room_rate` DECIMAL(10,2) NOT NULL,
    `subtotal` DECIMAL(10,2) NOT NULL,
    `tax` DECIMAL(10,2) DEFAULT 0,
    `total_amount` DECIMAL(10,2) NOT NULL,
    `status` ENUM('pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled') DEFAULT 'pending',
    `booking_source` VARCHAR(255) NULL,
    `special_requests` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    FOREIGN KEY (`property_id`) REFERENCES `properties`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`guest_id`) REFERENCES `guests`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`room_id`) REFERENCES `rooms`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

// 17. Payments table
'payments' => "CREATE TABLE IF NOT EXISTS `payments` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `booking_id` BIGINT UNSIGNED NOT NULL,
    `property_id` BIGINT UNSIGNED NULL,
    `transaction_id` VARCHAR(255) NOT NULL UNIQUE,
    `amount` DECIMAL(10,2) NOT NULL,
    `currency` VARCHAR(255) DEFAULT '₦' COMMENT 'Currency used for payment',
    `payment_method` ENUM('credit_card', 'debit_card', 'cash', 'bank_transfer', 'check') DEFAULT 'credit_card',
    `status` ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    `gateway_response` VARCHAR(255) NULL,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`property_id`) REFERENCES `properties`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

// 18. Folios table
'folios' => "CREATE TABLE IF NOT EXISTS `folios` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `booking_id` BIGINT UNSIGNED NOT NULL,
    `room_charges` DECIMAL(10,2) DEFAULT 0,
    `restaurant_charges` DECIMAL(10,2) DEFAULT 0,
    `other_charges` DECIMAL(10,2) DEFAULT 0,
    `total_charges` DECIMAL(10,2) DEFAULT 0,
    `payments_received` DECIMAL(10,2) DEFAULT 0,
    `balance` DECIMAL(10,2) DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

// 19. Folio items table
'folio_items' => "CREATE TABLE IF NOT EXISTS `folio_items` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `folio_id` BIGINT UNSIGNED NOT NULL,
    `description` VARCHAR(255) NOT NULL,
    `type` ENUM('room', 'restaurant', 'bar', 'service', 'other') DEFAULT 'other',
    `amount` DECIMAL(10,2) NOT NULL,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    FOREIGN KEY (`folio_id`) REFERENCES `folios`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

// 20. Menus table
'menus' => "CREATE TABLE IF NOT EXISTS `menus` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `property_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `description` TEXT NULL,
    `type` ENUM('food', 'beverage', 'both') DEFAULT 'both',
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    FOREIGN KEY (`property_id`) REFERENCES `properties`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

// 21. Menu categories table
'menu_categories' => "CREATE TABLE IF NOT EXISTS `menu_categories` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `menu_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `description` TEXT NULL,
    `sort_order` INT DEFAULT 0,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    FOREIGN KEY (`menu_id`) REFERENCES `menus`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

// 22. Menu items table
'menu_items' => "CREATE TABLE IF NOT EXISTS `menu_items` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `menu_category_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `description` TEXT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `cost_price` DECIMAL(10,2) DEFAULT 0,
    `ingredients` JSON NULL,
    `modifiers` JSON NULL,
    `sort_order` INT DEFAULT 0,
    `is_available` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    FOREIGN KEY (`menu_category_id`) REFERENCES `menu_categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

// 23. Restaurant tables table
'restaurant_tables' => "CREATE TABLE IF NOT EXISTS `restaurant_tables` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `property_id` BIGINT UNSIGNED NOT NULL,
    `table_number` VARCHAR(255) NOT NULL UNIQUE,
    `capacity` INT DEFAULT 2,
    `status` ENUM('available', 'occupied', 'reserved', 'maintenance') DEFAULT 'available',
    `section` VARCHAR(255) NULL,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    FOREIGN KEY (`property_id`) REFERENCES `properties`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

// 24. Restaurant reservations table
'restaurant_reservations' => "CREATE TABLE IF NOT EXISTS `restaurant_reservations` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `property_id` BIGINT UNSIGNED NOT NULL,
    `restaurant_table_id` BIGINT UNSIGNED NOT NULL,
    `guest_id` BIGINT UNSIGNED NULL,
    `guest_name` VARCHAR(255) NOT NULL,
    `guest_phone` VARCHAR(255) NOT NULL,
    `reservation_time` DATETIME NOT NULL,
    `number_of_guests` INT NOT NULL,
    `status` ENUM('pending', 'confirmed', 'seated', 'completed', 'cancelled') DEFAULT 'pending',
    `special_requests` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    FOREIGN KEY (`property_id`) REFERENCES `properties`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`restaurant_table_id`) REFERENCES `restaurant_tables`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`guest_id`) REFERENCES `guests`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

// 25. Orders table
'orders' => "CREATE TABLE IF NOT EXISTS `orders` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `property_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NULL,
    `restaurant_table_id` BIGINT UNSIGNED NULL,
    `booking_id` BIGINT UNSIGNED NULL,
    `order_number` VARCHAR(255) NOT NULL UNIQUE,
    `type` ENUM('dine_in', 'takeaway', 'room_service') DEFAULT 'dine_in',
    `order_type` ENUM('restaurant', 'bar') DEFAULT 'restaurant',
    `status` ENUM('pending', 'preparing', 'ready', 'served', 'closed') DEFAULT 'pending',
    `subtotal` DECIMAL(10,2) DEFAULT 0,
    `discount` DECIMAL(10,2) DEFAULT 0,
    `tax` DECIMAL(10,2) DEFAULT 0,
    `total` DECIMAL(10,2) DEFAULT 0,
    `charged_to_room` TINYINT(1) DEFAULT 0,
    `payment_method` ENUM('cash', 'card', 'mobile_money', 'room_charge') NULL,
    `paid_at` TIMESTAMP NULL,
    `notes` TEXT NULL,
    `customer_name` VARCHAR(255) NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    FOREIGN KEY (`property_id`) REFERENCES `properties`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`restaurant_table_id`) REFERENCES `restaurant_tables`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

// 26. Inventory items table
'inventory_items' => "CREATE TABLE IF NOT EXISTS `inventory_items` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `property_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `sku` VARCHAR(255) NOT NULL UNIQUE,
    `type` ENUM('ingredient', 'beverage', 'supply') DEFAULT 'ingredient',
    `unit` VARCHAR(255) NOT NULL,
    `quantity` DECIMAL(10,2) NOT NULL,
    `reorder_level` DECIMAL(10,2) NOT NULL,
    `unit_cost` DECIMAL(10,2) NOT NULL,
    `supplier` VARCHAR(255) NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    FOREIGN KEY (`property_id`) REFERENCES `properties`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

// 27. Order items table
'order_items' => "CREATE TABLE IF NOT EXISTS `order_items` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_id` BIGINT UNSIGNED NOT NULL,
    `menu_item_id` BIGINT UNSIGNED NULL,
    `inventory_item_id` BIGINT UNSIGNED NULL,
    `quantity` INT NOT NULL,
    `unit_price` DECIMAL(10,2) NOT NULL,
    `modifiers` JSON NULL,
    `special_instructions` TEXT NULL,
    `status` ENUM('pending', 'preparing', 'ready', 'served') DEFAULT 'pending',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`inventory_item_id`) REFERENCES `inventory_items`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

// 28. Inventory logs table
'inventory_logs' => "CREATE TABLE IF NOT EXISTS `inventory_logs` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `property_id` BIGINT UNSIGNED NULL,
    `inventory_item_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `type` ENUM('purchase', 'usage', 'adjustment', 'waste') DEFAULT 'usage',
    `quantity` DECIMAL(10,2) NOT NULL,
    `unit_cost` DECIMAL(10,2) NULL,
    `notes` TEXT NULL,
    `order_id` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    FOREIGN KEY (`property_id`) REFERENCES `properties`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`inventory_item_id`) REFERENCES `inventory_items`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

// 29. Audit logs table
'audit_logs' => "CREATE TABLE IF NOT EXISTS `audit_logs` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NULL,
    `action` VARCHAR(255) NOT NULL,
    `model_type` VARCHAR(255) NULL,
    `model_id` BIGINT UNSIGNED NULL,
    `old_values` JSON NULL,
    `new_values` JSON NULL,
    `ip_address` VARCHAR(255) NULL,
    `user_agent` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

// 30. Custom fields table
'custom_fields' => "CREATE TABLE IF NOT EXISTS `custom_fields` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `property_id` BIGINT UNSIGNED NOT NULL,
    `entity_type` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `label` VARCHAR(255) NOT NULL,
    `type` ENUM('text', 'number', 'email', 'phone', 'date', 'textarea', 'select', 'checkbox') DEFAULT 'text',
    `options` TEXT NULL,
    `is_required` TINYINT(1) DEFAULT 0,
    `order` INT DEFAULT 0,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    UNIQUE KEY `custom_fields_unique` (`property_id`, `entity_type`, `name`),
    FOREIGN KEY (`property_id`) REFERENCES `properties`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

// 31. Custom field values table
'custom_field_values' => "CREATE TABLE IF NOT EXISTS `custom_field_values` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `custom_field_id` BIGINT UNSIGNED NOT NULL,
    `fieldable_type` VARCHAR(255) NOT NULL,
    `fieldable_id` BIGINT UNSIGNED NOT NULL,
    `value` LONGTEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    UNIQUE KEY `custom_field_values_unique` (`custom_field_id`, `fieldable_id`, `fieldable_type`),
    INDEX (`fieldable_type`, `fieldable_id`),
    FOREIGN KEY (`custom_field_id`) REFERENCES `custom_fields`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

// 32. Booking history table
'booking_history' => "CREATE TABLE IF NOT EXISTS `booking_history` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `booking_id` BIGINT UNSIGNED NOT NULL,
    `property_id` BIGINT UNSIGNED NOT NULL,
    `guest_id` BIGINT UNSIGNED NOT NULL,
    `room_id` BIGINT UNSIGNED NULL,
    `booking_reference` VARCHAR(255) NOT NULL,
    `check_in_date` DATE NOT NULL,
    `check_out_date` DATE NOT NULL,
    `number_of_guests` INT DEFAULT 1,
    `number_of_nights` INT NOT NULL,
    `room_rate` DECIMAL(10,2) NOT NULL,
    `subtotal` DECIMAL(10,2) NOT NULL,
    `tax` DECIMAL(10,2) DEFAULT 0,
    `total_amount` DECIMAL(10,2) NOT NULL,
    `final_amount` DECIMAL(10,2) NULL COMMENT 'Final amount after any adjustments',
    `payments_received` DECIMAL(10,2) DEFAULT 0,
    `balance` DECIMAL(10,2) DEFAULT 0,
    `currency` VARCHAR(255) DEFAULT '₦' COMMENT 'Currency used',
    `special_requests` TEXT NULL,
    `checked_out_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`property_id`) REFERENCES `properties`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`guest_id`) REFERENCES `guests`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`room_id`) REFERENCES `rooms`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

// 33. Bill splits table
'bill_splits' => "CREATE TABLE IF NOT EXISTS `bill_splits` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_id` BIGINT UNSIGNED NOT NULL,
    `guest_name` VARCHAR(255) NOT NULL,
    `amount` DECIMAL(10,2) NOT NULL,
    `payment_method` VARCHAR(255) NULL,
    `status` ENUM('pending', 'paid', 'cancelled') DEFAULT 'pending',
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

// 34. Promotions table
'promotions' => "CREATE TABLE IF NOT EXISTS `promotions` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `property_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(255) NOT NULL UNIQUE,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    `type` ENUM('percentage', 'fixed') DEFAULT 'percentage',
    `value` DECIMAL(10,2) NOT NULL,
    `min_order_amount` DECIMAL(10,2) NULL,
    `max_uses` INT NULL,
    `used_count` INT DEFAULT 0,
    `valid_from` DATE NULL,
    `valid_to` DATE NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    FOREIGN KEY (`property_id`) REFERENCES `properties`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

]; // End of migrations array

// ==========================================
// RUN MIGRATIONS
// ==========================================

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<p class='success'>✅ Connected to database: <strong>$dbName</strong></p>";
    echo "<h2>Running Migrations...</h2>";

    $successCount = 0;
    $errorCount = 0;

    foreach ($migrations as $tableName => $sql) {
        try {
            $pdo->exec($sql);
            echo "<p class='success'>✅ Created table: <strong>$tableName</strong></p>";
            $successCount++;
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'already exists') !== false) {
                echo "<p class='info'>ℹ️ Table already exists: <strong>$tableName</strong></p>";
            } else {
                echo "<p class='error'>❌ Error creating <strong>$tableName</strong>: " . $e->getMessage() . "</p>";
                $errorCount++;
            }
        }
    }

    echo "<hr>";
    echo "<h2>Migration Complete!</h2>";
    echo "<p>✅ Tables created: <strong>$successCount</strong></p>";
    if ($errorCount > 0) {
        echo "<p>❌ Errors: <strong>$errorCount</strong></p>";
    }

    // Insert default roles
    echo "<h3>Inserting Default Roles...</h3>";
    $defaultRoles = [
        ['admin', 'Administrator', 'Full system access', '["all"]'],
        ['manager', 'Manager', 'Property management access', '["dashboard","bookings","guests","rooms","reports","orders","inventory"]'],
        ['receptionist', 'Receptionist', 'Front desk operations', '["dashboard","bookings","guests","check_in","check_out"]'],
        ['waiter', 'Waiter', 'Restaurant operations', '["orders","tables"]'],
        ['bar_manager', 'Bar Manager', 'Bar operations and inventory', '["orders","inventory","bar"]'],
    ];

    foreach ($defaultRoles as $role) {
        try {
            $stmt = $pdo->prepare("INSERT IGNORE INTO roles (name, display_name, description, permissions, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
            $stmt->execute($role);
            echo "<p class='success'>✅ Role: <strong>{$role[1]}</strong></p>";
        } catch (PDOException $e) {
            echo "<p class='info'>ℹ️ Role exists or error: {$role[0]}</p>";
        }
    }

} catch (PDOException $e) {
    echo "<p class='error'>❌ Database Connection Error: " . $e->getMessage() . "</p>";
    echo "<h3>Manual SQL</h3>";
    echo "<p>If you prefer to run manually in phpMyAdmin, copy and paste each SQL statement below:</p>";

    foreach ($migrations as $tableName => $sql) {
        echo "<h4>$tableName</h4>";
        echo "<pre>" . htmlspecialchars($sql) . "</pre>";
    }
}

// ==========================================
// SEED DATA
// ==========================================
echo "<h2>Seeding Data...</h2>";

// Properties
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM properties");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO properties (name, display_name, slug, description, address, city, state, postal_code, country, phone, email, website, total_rooms, check_in_time, check_out_time, currency, is_active, created_at, updated_at) VALUES
        ('Grand Hotel & Restaurant', 'Kuwait Cabin', 'grand-hotel-restaurant', 'A luxury 5-star hotel with world-class dining facilities', '123 Main Street', 'New York', 'NY', '10001', 'USA', '+1-212-555-0100', 'info@grandhotel.com', 'www.grandhotel.com', 150, 14.00, 11.00, 'USD', 1, NOW(), NOW()),
        ('Riverside Inn', NULL, 'riverside-inn', 'A cozy boutique hotel with scenic views', '456 River Road', 'San Francisco', 'CA', '94102', 'USA', '+1-415-555-0200', 'info@riversideinn.com', 'www.riversideinn.com', 50, 15.00, 10.00, 'USD', 1, NOW(), NOW())");
        echo "<p class='success'>✅ Seeded: properties</p>";
    } else {
        echo "<p class='info'>ℹ️ Properties already seeded</p>";
    }
} catch (PDOException $e) {
    echo "<p class='error'>❌ Error seeding properties: " . $e->getMessage() . "</p>";
}

// Roles
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM roles");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO roles (name, display_name, description, permissions, created_at, updated_at) VALUES
        ('admin', 'Administrator', 'Full system access', '[\"view_dashboard\",\"manage_bookings\",\"manage_rooms\",\"manage_menus\",\"manage_orders\",\"manage_inventory\",\"manage_users\",\"view_reports\",\"manage_settings\"]', NOW(), NOW()),
        ('manager', 'Manager', 'Can manage bookings, orders, bar orders, and inventory', '[\"view_dashboard\",\"manage_bookings\",\"manage_orders\",\"manage_bar_orders\",\"create_bar_orders\",\"manage_inventory\",\"view_reports\"]', NOW(), NOW()),
        ('receptionist', 'Receptionist', 'Can manage bookings and check-in/out', '[\"view_dashboard\",\"manage_bookings\",\"checkin_checkout\"]', NOW(), NOW()),
        ('chef', 'Chef', 'Can manage kitchen orders', '[\"view_orders\",\"update_order_status\"]', NOW(), NOW()),
        ('waiter', 'Waiter', 'Can take and manage orders', '[\"view_menus\",\"create_orders\",\"manage_orders\"]', NOW(), NOW()),
        ('bartender', 'Bartender', 'Can manage bar orders and track drink inventory', '[\"view_bar_menus\",\"create_bar_orders\",\"manage_bar_orders\",\"view_inventory\",\"update_order_status\"]', NOW(), NOW()),
        ('bar_manager', 'Bar Manager', 'Can manage bar operations and orders', '[\"view_dashboard\",\"manage_bar_orders\",\"create_bar_orders\",\"update_order_status\"]', NOW(), NOW())");
        echo "<p class='success'>✅ Seeded: roles</p>";
    } else {
        echo "<p class='info'>ℹ️ Roles already seeded</p>";
    }
} catch (PDOException $e) {
    echo "<p class='error'>❌ Error seeding roles: " . $e->getMessage() . "</p>";
}

// Users (password is 'password' hashed with bcrypt)
$hashedPassword = '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO users (property_id, name, email, password, role_id, phone, status, email_verified_at, created_at, updated_at) VALUES
        (1, 'Admin User', 'admin@hotel.test', '$hashedPassword', 1, '+1-212-555-0101', 'active', NOW(), NOW(), NOW()),
        (1, 'Manager User', 'manager@hotel.test', '$hashedPassword', 2, '+1-212-555-0102', 'active', NOW(), NOW(), NOW()),
        (1, 'Receptionist User', 'receptionist@hotel.test', '$hashedPassword', 3, '+1-212-555-0103', 'active', NOW(), NOW(), NOW()),
        (1, 'Chef User', 'chef@hotel.test', '$hashedPassword', 4, '+1-212-555-0104', 'active', NOW(), NOW(), NOW()),
        (1, 'Waiter User', 'waiter@hotel.test', '$hashedPassword', 5, '+1-212-555-0105', 'active', NOW(), NOW(), NOW())");
        echo "<p class='success'>✅ Seeded: users (default password: 'password')</p>";
    } else {
        echo "<p class='info'>ℹ️ Users already seeded</p>";
    }
} catch (PDOException $e) {
    echo "<p class='error'>❌ Error seeding users: " . $e->getMessage() . "</p>";
}

// Room Types
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM room_types");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO room_types (property_id, name, slug, description, capacity, base_price, amenities, is_active, created_at, updated_at) VALUES
        (1, 'Standard Room', 'standard-room', 'Comfortable room with basic amenities', 2, 100.00, '[\"WiFi\",\"TV\",\"Air Conditioning\",\"Private Bathroom\"]', 1, NOW(), NOW()),
        (1, 'Deluxe Room', 'deluxe-room', 'Spacious room with premium amenities', 2, 150.00, '[\"WiFi\",\"Smart TV\",\"Air Conditioning\",\"Luxury Bathroom\",\"Mini Bar\",\"Work Desk\"]', 1, NOW(), NOW()),
        (1, 'Suite', 'suite', 'Luxurious suite with separate living area', 4, 250.00, '[\"WiFi\",\"Smart TV\",\"Air Conditioning\",\"Luxury Bathroom\",\"Mini Bar\",\"Work Desk\",\"Living Area\",\"Jacuzzi\"]', 1, NOW(), NOW()),
        (2, 'Standard Room', 'standard-room-riverside', 'Comfortable room with basic amenities', 2, 80.00, '[\"WiFi\",\"TV\",\"Air Conditioning\",\"Private Bathroom\"]', 1, NOW(), NOW()),
        (2, 'River View Room', 'river-view-room', 'Room with scenic river views', 2, 120.00, '[\"WiFi\",\"TV\",\"Air Conditioning\",\"Private Bathroom\",\"Balcony\"]', 1, NOW(), NOW())");
        echo "<p class='success'>✅ Seeded: room_types</p>";
    } else {
        echo "<p class='info'>ℹ️ Room types already seeded</p>";
    }
} catch (PDOException $e) {
    echo "<p class='error'>❌ Error seeding room_types: " . $e->getMessage() . "</p>";
}

// Rooms (sample rooms)
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM rooms");
    if ($stmt->fetchColumn() == 0) {
        $roomsSql = "INSERT INTO rooms (property_id, room_type_id, room_number, floor, status, created_at, updated_at) VALUES ";
        $roomsValues = [];

        // Grand Hotel - Floor 1 (Standard rooms 1001-1010)
        for ($i = 1; $i <= 10; $i++) {
            $roomNum = '10' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $roomsValues[] = "(1, 1, '$roomNum', 1, 'available', NOW(), NOW())";
        }
        // Grand Hotel - Floor 2 (Deluxe rooms 2001-2010)
        for ($i = 1; $i <= 10; $i++) {
            $roomNum = '20' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $roomsValues[] = "(1, 2, '$roomNum', 2, 'available', NOW(), NOW())";
        }
        // Grand Hotel - Floor 3 (Suites 3001-3005)
        for ($i = 1; $i <= 5; $i++) {
            $roomNum = '30' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $roomsValues[] = "(1, 3, '$roomNum', 3, 'available', NOW(), NOW())";
        }
        // Riverside Inn - Floor 1 (Standard 1001-1005)
        for ($i = 1; $i <= 5; $i++) {
            $roomNum = '10' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $roomsValues[] = "(2, 4, '$roomNum', 1, 'available', NOW(), NOW())";
        }
        // Riverside Inn - Floor 2 (River View 2001-2005)
        for ($i = 1; $i <= 5; $i++) {
            $roomNum = '20' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $roomsValues[] = "(2, 5, '$roomNum', 2, 'available', NOW(), NOW())";
        }

        $pdo->exec($roomsSql . implode(', ', $roomsValues));
        echo "<p class='success'>✅ Seeded: rooms (35 rooms)</p>";
    } else {
        echo "<p class='info'>ℹ️ Rooms already seeded</p>";
    }
} catch (PDOException $e) {
    echo "<p class='error'>❌ Error seeding rooms: " . $e->getMessage() . "</p>";
}

// Menus
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM menus");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO menus (property_id, name, slug, description, type, is_active, created_at, updated_at) VALUES
        (1, 'Main Restaurant Menu', 'main-restaurant-menu', 'Our signature restaurant menu', 'both', 1, NOW(), NOW())");
        echo "<p class='success'>✅ Seeded: menus</p>";
    } else {
        echo "<p class='info'>ℹ️ Menus already seeded</p>";
    }
} catch (PDOException $e) {
    echo "<p class='error'>❌ Error seeding menus: " . $e->getMessage() . "</p>";
}

// Menu Categories
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM menu_categories");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO menu_categories (menu_id, name, slug, description, sort_order, is_active, created_at, updated_at) VALUES
        (1, 'Appetizers', 'appetizers', 'Start your meal with our delicious appetizers', 1, 1, NOW(), NOW()),
        (1, 'Main Courses', 'main-courses', 'Our signature main courses', 2, 1, NOW(), NOW()),
        (1, 'Desserts', 'desserts', 'Sweet treats to finish your meal', 3, 1, NOW(), NOW()),
        (1, 'Beverages', 'beverages', 'Refreshing drinks', 4, 1, NOW(), NOW())");
        echo "<p class='success'>✅ Seeded: menu_categories</p>";
    } else {
        echo "<p class='info'>ℹ️ Menu categories already seeded</p>";
    }
} catch (PDOException $e) {
    echo "<p class='error'>❌ Error seeding menu_categories: " . $e->getMessage() . "</p>";
}

// Menu Items
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM menu_items");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO menu_items (menu_category_id, name, slug, description, price, cost_price, ingredients, is_available, created_at, updated_at) VALUES
        (1, 'Caesar Salad', 'caesar-salad', 'Fresh romaine lettuce with parmesan and croutons', 12.99, 4.00, '[\"Romaine Lettuce\",\"Parmesan\",\"Croutons\",\"Caesar Dressing\"]', 1, NOW(), NOW()),
        (1, 'Shrimp Cocktail', 'shrimp-cocktail', 'Chilled shrimp with cocktail sauce', 14.99, 6.00, '[\"Shrimp\",\"Cocktail Sauce\",\"Lemon\"]', 1, NOW(), NOW()),
        (2, 'Grilled Salmon', 'grilled-salmon', 'Fresh Atlantic salmon with lemon butter sauce', 28.99, 12.00, '[\"Salmon\",\"Lemon\",\"Butter\",\"Herbs\"]', 1, NOW(), NOW()),
        (2, 'Ribeye Steak', 'ribeye-steak', '12oz prime ribeye with garlic mashed potatoes', 35.99, 18.00, '[\"Ribeye Steak\",\"Garlic\",\"Potatoes\",\"Butter\"]', 1, NOW(), NOW()),
        (3, 'Chocolate Lava Cake', 'chocolate-lava-cake', 'Warm chocolate cake with molten center', 9.99, 3.00, '[\"Chocolate\",\"Flour\",\"Eggs\",\"Butter\"]', 1, NOW(), NOW()),
        (4, 'Iced Tea', 'iced-tea', 'Refreshing iced tea', 3.99, 0.50, '[\"Tea\",\"Ice\",\"Lemon\"]', 1, NOW(), NOW()),
        (4, 'House Wine', 'house-wine', 'Glass of our house wine selection', 8.99, 3.00, '[\"Wine\"]', 1, NOW(), NOW())");
        echo "<p class='success'>✅ Seeded: menu_items</p>";
    } else {
        echo "<p class='info'>ℹ️ Menu items already seeded</p>";
    }
} catch (PDOException $e) {
    echo "<p class='error'>❌ Error seeding menu_items: " . $e->getMessage() . "</p>";
}

// Inventory Items
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM inventory_items");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO inventory_items (property_id, name, sku, type, unit, quantity, reorder_level, unit_cost, supplier, is_active, created_at, updated_at) VALUES
        (1, 'Salmon Fillet', 'SALMON-001', 'ingredient', 'kg', 50.00, 10.00, 15.00, 'Fresh Fish Co.', 1, NOW(), NOW()),
        (1, 'Ribeye Steak', 'STEAK-001', 'ingredient', 'kg', 40.00, 8.00, 25.00, 'Premium Meats Inc.', 1, NOW(), NOW()),
        (1, 'Romaine Lettuce', 'LETTUCE-001', 'ingredient', 'kg', 30.00, 5.00, 2.50, 'Fresh Produce Ltd.', 1, NOW(), NOW()),
        (1, 'House Wine Red', 'WINE-RED-001', 'beverage', 'bottle', 100.00, 20.00, 8.00, 'Wine Distributors', 1, NOW(), NOW()),
        (1, 'House Wine White', 'WINE-WHITE-001', 'beverage', 'bottle', 80.00, 15.00, 7.50, 'Wine Distributors', 1, NOW(), NOW()),
        (1, 'Paper Napkins', 'NAPKINS-001', 'supply', 'box', 50.00, 10.00, 5.00, 'Restaurant Supplies Co.', 1, NOW(), NOW()),
        (1, 'Plastic Cups', 'CUPS-001', 'supply', 'box', 100.00, 20.00, 3.00, 'Restaurant Supplies Co.', 1, NOW(), NOW())");
        echo "<p class='success'>✅ Seeded: inventory_items</p>";
    } else {
        echo "<p class='info'>ℹ️ Inventory items already seeded</p>";
    }
} catch (PDOException $e) {
    echo "<p class='error'>❌ Error seeding inventory_items: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h3>⚠️ IMPORTANT: Next Steps</h3>";
echo "<ol>";
echo "<li><strong>Update your .env file</strong> to use MySQL:
<pre>
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=$dbName
DB_USERNAME=$dbUser
DB_PASSWORD=$dbPass
</pre></li>";
echo "<li><strong>Delete this file</strong> (mysql_migrate.php) for security!</li>";
echo "<li>Clear Laravel cache: <code>php artisan config:clear</code></li>";
echo "</ol>";

echo "</body></html>";

