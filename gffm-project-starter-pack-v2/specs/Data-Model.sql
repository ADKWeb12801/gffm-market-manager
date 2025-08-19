-- DDL (working draft); engine/charset set by dbDelta
CREATE TABLE wp_gffm_vendors(
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  post_id BIGINT UNSIGNED NOT NULL,
  email VARCHAR(190),
  phone VARCHAR(50),
  products TEXT,
  inside_outside VARCHAR(20),
  power_needed TINYINT(1) DEFAULT 0,
  tables_count TINYINT UNSIGNED DEFAULT 0,
  notes TEXT,
  status VARCHAR(20) DEFAULT 'active',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY post_id (post_id)
);
-- ... see plugin/includes/class-gffm-schema.php for full list
