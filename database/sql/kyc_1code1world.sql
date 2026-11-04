-- 1 Code 1 World (KYC) : รันครั้งเดียวบนฐานข้อมูล mlm_demo
-- สร้าง 3 ตาราง (customers_kyc, customers_kyc_history, customers_doc_history) ไม่แตะตารางเดิม
-- ปลอดภัยถ้ารันซ้ำ (IF NOT EXISTS / WHERE NOT EXISTS)

CREATE TABLE IF NOT EXISTS `customers_kyc` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customers_id` bigint unsigned NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `id_card` varchar(20) NOT NULL,
  `img_card` varchar(255) NOT NULL,
  `bank_no` varchar(30) NOT NULL,
  `bank_id_fk` bigint unsigned NULL,
  `bank_name` varchar(100) NULL,
  `img_bank` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `terms_accepted_at` timestamp NULL,
  `status` tinyint unsigned NOT NULL DEFAULT '0',
  `note` varchar(255) NULL,
  `approved_by` bigint unsigned NULL,
  `approved_at` timestamp NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customers_kyc_customers_id_unique` (`customers_id`),
  KEY `customers_kyc_user_name_index` (`user_name`),
  KEY `customers_kyc_id_card_index` (`id_card`),
  KEY `customers_kyc_bank_no_index` (`bank_no`),
  KEY `customers_kyc_phone_index` (`phone`),
  KEY `customers_kyc_status_index` (`status`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `customers_kyc_history` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customers_id` bigint unsigned NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `id_card` varchar(20) NOT NULL,
  `img_card` varchar(255) NOT NULL,
  `bank_no` varchar(30) NOT NULL,
  `bank_name` varchar(100) NULL,
  `img_bank` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `status` tinyint unsigned NOT NULL,
  `note` varchar(255) NULL,
  `approved_by` bigint unsigned NULL,
  `submitted_at` timestamp NULL,
  `decided_at` timestamp NULL,
  `created_at` timestamp NULL,
  PRIMARY KEY (`id`),
  KEY `customers_kyc_history_customers_id_index` (`customers_id`),
  KEY `customers_kyc_history_user_name_index` (`user_name`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `customers_doc_history` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customers_id` bigint unsigned NOT NULL,
  `user_name` varchar(50) NULL,
  `type` varchar(10) NOT NULL,
  `action` varchar(10) NOT NULL,
  `old_data` longtext NULL,
  `new_data` longtext NULL,
  `admin_id` bigint unsigned NULL,
  `created_at` timestamp NULL,
  PRIMARY KEY (`id`),
  KEY `customers_doc_history_customers_id_index` (`customers_id`),
  KEY `customers_doc_history_user_name_index` (`user_name`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- บันทึกใน migrations เพื่อไม่ให้ php artisan migrate รันซ้ำ (ข้ามได้ถ้าไม่ได้ใช้ artisan บนเซิร์ฟเวอร์)
INSERT INTO `migrations` (`migration`, `batch`)
SELECT m.name, (SELECT COALESCE(MAX(batch),0)+1 FROM (SELECT batch FROM `migrations`) b)
FROM (
  SELECT '2026_11_03_000001_create_customers_kyc_table' AS name
  UNION ALL SELECT '2026_11_03_000002_add_approver_to_customers_kyc'
  UNION ALL SELECT '2026_11_03_000003_create_customers_kyc_history_table'
  UNION ALL SELECT '2026_11_03_000004_create_customers_doc_history_table'
) m
WHERE NOT EXISTS (SELECT 1 FROM `migrations` x WHERE x.migration = m.name);
