/*
 Navicat Premium Dump SQL

 Source Server         : development
 Source Server Type    : MySQL
 Source Server Version : 80402 (8.4.2)
 Source Host           : localhost:3306
 Source Schema         : object_detection

 Target Server Type    : MySQL
 Target Server Version : 80402 (8.4.2)
 File Encoding         : 65001

 Date: 18/07/2025 18:33:11
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cache
-- ----------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache`  (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cache
-- ----------------------------

-- ----------------------------
-- Table structure for cache_locks
-- ----------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks`  (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cache_locks
-- ----------------------------

-- ----------------------------
-- Table structure for cameras
-- ----------------------------
DROP TABLE IF EXISTS `cameras`;
CREATE TABLE `cameras`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cameras
-- ----------------------------
INSERT INTO `cameras` VALUES (1, 'Cam 1', 'Gudang 1', '2025-06-29 19:20:22', NULL);

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `failed_jobs_uuid_unique`(`uuid` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for job_batches
-- ----------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches`  (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `cancelled_at` int NULL DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of job_batches
-- ----------------------------

-- ----------------------------
-- Table structure for jobs
-- ----------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED NULL DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `jobs_queue_index`(`queue` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of jobs
-- ----------------------------

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '0001_01_01_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '0001_01_01_000001_create_cache_table', 1);
INSERT INTO `migrations` VALUES (3, '0001_01_01_000002_create_jobs_table', 1);
INSERT INTO `migrations` VALUES (4, '2025_06_29_094015_create_cameras_table', 1);
INSERT INTO `migrations` VALUES (5, '2025_06_29_094907_create_sack_movements_table', 1);
INSERT INTO `migrations` VALUES (6, '2025_06_29_101548_create_personal_access_tokens_table', 2);

-- ----------------------------
-- Table structure for password_reset_tokens
-- ----------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens`  (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of password_reset_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for personal_access_tokens
-- ----------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `personal_access_tokens_token_unique`(`token` ASC) USING BTREE,
  INDEX `personal_access_tokens_tokenable_type_tokenable_id_index`(`tokenable_type` ASC, `tokenable_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of personal_access_tokens
-- ----------------------------
INSERT INTO `personal_access_tokens` VALUES (1, 'App\\Models\\User', 1, 'API Token', 'f04161ff34553ed24ed112f11d3df17773962fa44084077be22115eda21daa8b', '[\"*\"]', NULL, NULL, '2025-06-30 14:23:59', '2025-06-30 14:23:59');
INSERT INTO `personal_access_tokens` VALUES (2, 'App\\Models\\User', 1, 'API Token', '830963adf260c47db388706bc066016386ee58173558ddc21c242feec5ee0abc', '[\"*\"]', NULL, NULL, '2025-07-04 12:11:19', '2025-07-04 12:11:19');
INSERT INTO `personal_access_tokens` VALUES (3, 'App\\Models\\User', 1, 'API Token', '3541efac071e2d4f68a8b684ddf41a356128cef0e9644a2c8267fc2a1e313747', '[\"*\"]', NULL, NULL, '2025-07-04 12:57:50', '2025-07-04 12:57:50');
INSERT INTO `personal_access_tokens` VALUES (4, 'App\\Models\\User', 1, 'API Token', 'fd1c8cf119e18926afb13f63b0ade950f7c9773fb09fe50da946d2c62a728cbb', '[\"*\"]', NULL, NULL, '2025-07-04 12:58:49', '2025-07-04 12:58:49');
INSERT INTO `personal_access_tokens` VALUES (5, 'App\\Models\\User', 1, 'API Token', 'dc10fb604ff0a95efe248805d49f1b78f75dd26da5ec486073947ab515b02a0c', '[\"*\"]', NULL, NULL, '2025-07-04 13:32:07', '2025-07-04 13:32:07');
INSERT INTO `personal_access_tokens` VALUES (6, 'App\\Models\\User', 1, 'API Token', 'b2867e79bddbd4788b01b08d70100a936acb892b8dc4586bde8b87b64503eecc', '[\"*\"]', NULL, NULL, '2025-07-07 07:50:36', '2025-07-07 07:50:36');
INSERT INTO `personal_access_tokens` VALUES (7, 'App\\Models\\User', 1, 'API Token', '47287afadcc0a0198bcd8830e09e0d7b0f70dd4bbb032bb91e27746f65e83e87', '[\"*\"]', NULL, NULL, '2025-07-07 14:36:17', '2025-07-07 14:36:17');
INSERT INTO `personal_access_tokens` VALUES (8, 'App\\Models\\User', 1, 'API Token', 'ccbf6d9af768ff7c65f69a4e24e54b6e2e74ddd619f80345b1da4abfc31fe637', '[\"*\"]', NULL, NULL, '2025-07-07 14:36:24', '2025-07-07 14:36:24');
INSERT INTO `personal_access_tokens` VALUES (9, 'App\\Models\\User', 1, 'API Token', '39a39626d831839f21bc402d8aa47c77910c877136203242afbcc4d4c7e31ddc', '[\"*\"]', NULL, NULL, '2025-07-07 14:41:17', '2025-07-07 14:41:17');
INSERT INTO `personal_access_tokens` VALUES (10, 'App\\Models\\User', 1, 'API Token', '4d591e0a219064372529a7504a19c7d63ff9fc8392fc63051cd496c60abb07af', '[\"*\"]', NULL, NULL, '2025-07-07 15:43:28', '2025-07-07 15:43:28');
INSERT INTO `personal_access_tokens` VALUES (11, 'App\\Models\\User', 1, 'API Token', 'd3e3827c9906da4668444c9ef83551cd532a3d918037eea4bfa15765116eb081', '[\"*\"]', NULL, NULL, '2025-07-08 10:05:07', '2025-07-08 10:05:07');
INSERT INTO `personal_access_tokens` VALUES (12, 'App\\Models\\User', 1, 'API Token', '732b8c9952954de1ff8abc55c2fd331478183f232d13e56aba605a585bee9739', '[\"*\"]', NULL, NULL, '2025-07-08 10:12:56', '2025-07-08 10:12:56');
INSERT INTO `personal_access_tokens` VALUES (13, 'App\\Models\\User', 1, 'API Token', '1f9323db3b0db83f0bed31c4efd5d825e5ed69aa626b3c1eae998ee3f4f3ff21', '[\"*\"]', NULL, NULL, '2025-07-08 10:13:04', '2025-07-08 10:13:04');
INSERT INTO `personal_access_tokens` VALUES (14, 'App\\Models\\User', 1, 'API Token', 'e60cdec543a33ae89ab2d246ef22f1b50ed807c8f206a8fb739b545691b0caca', '[\"*\"]', '2025-07-08 13:57:27', NULL, '2025-07-08 13:57:05', '2025-07-08 13:57:27');
INSERT INTO `personal_access_tokens` VALUES (15, 'App\\Models\\User', 1, 'API Token', '23915ec607e2477922f4cf9fe339cc9d9506518c15f1af6a054abeadcf19dcbe', '[\"*\"]', NULL, NULL, '2025-07-08 15:06:47', '2025-07-08 15:06:47');
INSERT INTO `personal_access_tokens` VALUES (16, 'App\\Models\\User', 1, 'API Token', '905824d563ce8900e9d133b6d9cf4bf1eef4c58e38c3e97bdbc099cce974f6de', '[\"*\"]', NULL, NULL, '2025-07-08 16:25:40', '2025-07-08 16:25:40');
INSERT INTO `personal_access_tokens` VALUES (17, 'App\\Models\\User', 1, 'API Token', '3f4964600aa1a88df0ed09c00f60c20f14f6122c1f7d998ceb5f4ee2999be6af', '[\"*\"]', NULL, NULL, '2025-07-08 16:26:03', '2025-07-08 16:26:03');

-- ----------------------------
-- Table structure for sack_movements
-- ----------------------------
DROP TABLE IF EXISTS `sack_movements`;
CREATE TABLE `sack_movements`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `camera_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `direction` enum('in','out') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `detected_at` timestamp NOT NULL,
  `sack_count` int NOT NULL DEFAULT 1,
  `image_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `sack_movements_camera_id_foreign`(`camera_id` ASC) USING BTREE,
  INDEX `sack_movements_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `sack_movements_camera_id_foreign` FOREIGN KEY (`camera_id`) REFERENCES `cameras` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `sack_movements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 25 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sack_movements
-- ----------------------------
INSERT INTO `sack_movements` VALUES (1, 1, 1, 'in', '2025-06-29 19:22:07', 1, NULL, '2025-06-29 19:22:13', NULL);
INSERT INTO `sack_movements` VALUES (2, 1, NULL, 'in', '2025-06-29 14:15:04', 1, 'string', '2025-06-29 14:26:08', '2025-06-29 14:26:08');
INSERT INTO `sack_movements` VALUES (3, 1, NULL, 'in', '2025-06-30 02:22:08', 1, NULL, '2025-06-30 02:22:11', '2025-06-30 02:22:11');
INSERT INTO `sack_movements` VALUES (4, 1, NULL, 'in', '2025-06-30 03:56:37', 1, 'string', '2025-06-30 04:27:20', '2025-06-30 04:27:20');
INSERT INTO `sack_movements` VALUES (5, 1, NULL, 'in', '2025-06-30 03:56:37', 1, 'string', '2025-06-30 04:27:20', '2025-06-30 04:27:20');
INSERT INTO `sack_movements` VALUES (6, 1, NULL, 'in', '2025-06-30 03:56:37', 1, 'string', '2025-06-30 04:27:20', '2025-06-30 04:27:20');
INSERT INTO `sack_movements` VALUES (7, 1, NULL, 'in', '2025-06-30 03:56:37', 1, 'string', '2025-06-30 04:32:18', '2025-06-30 04:32:18');
INSERT INTO `sack_movements` VALUES (8, 1, NULL, 'in', '2025-06-30 04:35:27', 1, 'string', '2025-06-30 04:35:34', '2025-06-30 04:35:34');
INSERT INTO `sack_movements` VALUES (9, 1, NULL, 'in', '2025-06-30 04:35:27', 1, 'string', '2025-06-30 04:37:34', '2025-06-30 04:37:34');
INSERT INTO `sack_movements` VALUES (10, 1, NULL, 'in', '2025-06-30 04:35:27', 1, 'string', '2025-06-30 04:38:22', '2025-06-30 04:38:22');
INSERT INTO `sack_movements` VALUES (11, 1, NULL, 'in', '2025-06-30 04:35:27', 1, 'string', '2025-06-30 05:45:30', '2025-06-30 05:45:30');
INSERT INTO `sack_movements` VALUES (12, 1, NULL, 'in', '2025-06-30 05:39:59', 1, 'string', '2025-06-30 05:45:30', '2025-06-30 05:45:30');
INSERT INTO `sack_movements` VALUES (13, 1, NULL, 'in', '2025-06-30 06:58:27', 1, NULL, '2025-06-30 06:59:00', '2025-06-30 06:59:00');
INSERT INTO `sack_movements` VALUES (14, 1, NULL, 'in', '2025-06-30 06:58:37', 1, NULL, '2025-06-30 06:59:09', '2025-06-30 06:59:09');
INSERT INTO `sack_movements` VALUES (15, 1, NULL, 'in', '2025-06-30 06:58:42', 1, NULL, '2025-06-30 06:59:15', '2025-06-30 06:59:15');
INSERT INTO `sack_movements` VALUES (16, 1, NULL, 'in', '2025-06-30 06:59:18', 1, NULL, '2025-06-30 06:59:52', '2025-06-30 06:59:52');
INSERT INTO `sack_movements` VALUES (17, 1, NULL, 'in', '2025-06-30 06:59:27', 1, NULL, '2025-06-30 07:00:01', '2025-06-30 07:00:01');
INSERT INTO `sack_movements` VALUES (18, 1, NULL, 'in', '2025-06-30 06:59:35', 1, NULL, '2025-06-30 07:00:07', '2025-06-30 07:00:07');
INSERT INTO `sack_movements` VALUES (19, 1, NULL, 'in', '2025-06-30 14:12:00', 1, NULL, '2025-06-30 07:12:33', '2025-06-30 07:12:33');
INSERT INTO `sack_movements` VALUES (20, 1, NULL, 'in', '2025-06-30 14:12:10', 1, NULL, '2025-06-30 07:12:42', '2025-06-30 07:12:42');
INSERT INTO `sack_movements` VALUES (21, 1, NULL, 'in', '2025-06-30 14:12:15', 1, NULL, '2025-06-30 07:12:48', '2025-06-30 07:12:48');
INSERT INTO `sack_movements` VALUES (22, 1, NULL, 'in', '2025-06-30 14:20:45', 1, NULL, '2025-06-30 07:21:20', '2025-06-30 07:21:20');
INSERT INTO `sack_movements` VALUES (23, 1, NULL, 'in', '2025-06-30 14:20:51', 1, NULL, '2025-06-30 07:21:26', '2025-06-30 07:21:26');
INSERT INTO `sack_movements` VALUES (24, 1, NULL, 'in', '2025-06-30 14:20:54', 1, NULL, '2025-06-30 07:21:29', '2025-06-30 07:21:29');

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions`  (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `sessions_user_id_index`(`user_id` ASC) USING BTREE,
  INDEX `sessions_last_activity_index`(`last_activity` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sessions
-- ----------------------------
INSERT INTO `sessions` VALUES ('GSaQocdySdHYiOTCvaQaIpVIPaysiISCdPkmWiqO', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZXhBaVRCalVlanZONmkzOGZ6NUJvcDMyV1kwNE9ZVnh6dTF0VXNTQSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hcHBzL3NhY2stbW92ZW1lbnRzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1752081564);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `google_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'Irwansyah', 'mirwansyah1933@gmail.com', NULL, NULL, '2025-06-29 19:21:00', '$2y$12$sVnNMdAOLId0yg9h7xKITu4Gc5Qf03Zlm.yXq2EUP9us2qrM8lka6', NULL, '2025-06-29 19:21:08', '2025-06-30 14:23:44');

-- ----------------------------
-- View structure for data
-- ----------------------------
DROP VIEW IF EXISTS `data`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `data` AS select 'NO' AS `NO`,'NIA' AS `NIA`,'NAMA' AS `NAMA` from `users`;

SET FOREIGN_KEY_CHECKS = 1;
