<?php
if (!defined('ABSPATH')) { exit; }

class GFFM_Schema {
    public static function install(){
        global $wpdb;
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        $charset = $wpdb->get_charset_collate();
        $p = $wpdb->prefix;

        $tables = [];

        $tables[] = "CREATE TABLE {$p}gffm_vendors (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            post_id BIGINT UNSIGNED NOT NULL,
            email VARCHAR(190) NULL,
            phone VARCHAR(50) NULL,
            products TEXT NULL,
            inside_outside VARCHAR(20) NULL,
            power_needed TINYINT(1) DEFAULT 0,
            tables_count TINYINT UNSIGNED DEFAULT 0,
            notes TEXT NULL,
            status VARCHAR(20) DEFAULT 'active',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id), KEY post_id (post_id)
        ) $charset;";

        $tables[] = "CREATE TABLE {$p}gffm_seasons (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            name VARCHAR(190) NOT NULL,
            start_date DATE NOT NULL,
            end_date DATE NOT NULL,
            start_time TIME NOT NULL,
            end_time TIME NOT NULL,
            timezone VARCHAR(64) NOT NULL,
            visitor_enabled TINYINT(1) DEFAULT 0,
            visitor_capacity INT DEFAULT 0,
            pricing_json LONGTEXT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset;";

        $tables[] = "CREATE TABLE {$p}gffm_dates (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            season_id BIGINT UNSIGNED NOT NULL,
            date DATE NOT NULL,
            is_exception TINYINT(1) DEFAULT 0,
            notes TEXT NULL,
            PRIMARY KEY (id), KEY season_id (season_id), UNIQUE KEY season_date (season_id, date)
        ) $charset;";

        $tables[] = "CREATE TABLE {$p}gffm_booths (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            season_id BIGINT UNSIGNED NOT NULL,
            zone VARCHAR(50) NOT NULL,
            label VARCHAR(50) NOT NULL,
            width_ft INT DEFAULT 10,
            power TINYINT(1) DEFAULT 0,
            water TINYINT(1) DEFAULT 0,
            price_base DECIMAL(10,2) DEFAULT 0,
            price_per_table DECIMAL(10,2) DEFAULT 0,
            price_power_surcharge DECIMAL(10,2) DEFAULT 0,
            PRIMARY KEY (id), KEY season_id (season_id)
        ) $charset;";

        $tables[] = "CREATE TABLE {$p}gffm_assignments (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            season_id BIGINT UNSIGNED NOT NULL,
            date DATE NOT NULL,
            booth_id BIGINT UNSIGNED NOT NULL,
            vendor_id BIGINT UNSIGNED NOT NULL,
            type VARCHAR(12) DEFAULT 'full',
            status VARCHAR(20) DEFAULT 'pending',
            PRIMARY KEY (id), KEY season_date (season_id, date), KEY booth (booth_id), KEY vendor (vendor_id)
        ) $charset;";

        $tables[] = "CREATE TABLE {$p}gffm_checkins (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            assignment_id BIGINT UNSIGNED NOT NULL,
            scanned_at DATETIME NOT NULL,
            method VARCHAR(12) DEFAULT 'manual',
            notes TEXT NULL,
            staff_user_id BIGINT UNSIGNED NULL,
            PRIMARY KEY (id), KEY assignment_id (assignment_id)
        ) $charset;";

        $tables[] = "CREATE TABLE {$p}gffm_invoices (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            vendor_id BIGINT UNSIGNED NOT NULL,
            season_id BIGINT UNSIGNED NULL,
            total DECIMAL(10,2) DEFAULT 0,
            status VARCHAR(20) DEFAULT 'draft',
            gateway VARCHAR(20) DEFAULT 'none',
            gateway_ref VARCHAR(190) NULL,
            due_date DATE NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id), KEY vendor_id (vendor_id), KEY season_id (season_id)
        ) $charset;";

        $tables[] = "CREATE TABLE {$p}gffm_invoice_items (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            invoice_id BIGINT UNSIGNED NOT NULL,
            description VARCHAR(190) NOT NULL,
            qty INT DEFAULT 1,
            unit_price DECIMAL(10,2) DEFAULT 0,
            tax DECIMAL(10,2) DEFAULT 0,
            total DECIMAL(10,2) DEFAULT 0,
            PRIMARY KEY (id), KEY invoice_id (invoice_id)
        ) $charset;";

        $tables[] = "CREATE TABLE {$p}gffm_documents (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            vendor_id BIGINT UNSIGNED NOT NULL,
            type VARCHAR(50) NOT NULL,
            url TEXT NOT NULL,
            expires_on DATE NULL,
            status VARCHAR(20) DEFAULT 'valid',
            PRIMARY KEY (id), KEY vendor_id (vendor_id), KEY type (type)
        ) $charset;";

        $tables[] = "CREATE TABLE {$p}gffm_messages (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            vendor_id BIGINT UNSIGNED NOT NULL,
            channel VARCHAR(10) NOT NULL,
            template_key VARCHAR(50) NULL,
            subject VARCHAR(190) NULL,
            body LONGTEXT NULL,
            sent_at DATETIME NULL,
            status VARCHAR(20) DEFAULT 'queued',
            PRIMARY KEY (id), KEY vendor_id (vendor_id), KEY channel (channel)
        ) $charset;";

        $tables[] = "CREATE TABLE {$p}gffm_audit (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            actor_id BIGINT UNSIGNED NULL,
            action VARCHAR(50) NOT NULL,
            object_type VARCHAR(50) NOT NULL,
            object_id BIGINT UNSIGNED NULL,
            payload_json LONGTEXT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id), KEY object (object_type, object_id)
        ) $charset;";

        foreach ($tables as $sql){ dbDelta($sql); }
        update_option('gffm_schema_installed', 1);
    }
}
