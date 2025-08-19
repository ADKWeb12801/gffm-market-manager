<?php
/**
 * Plugin Name: GFFM Market Manager
 * Description: All-in-one market manager for Glens Falls Farmers' Market — vendor assignments, enrollment + waitlist, invoices, exports, vendor portal, and more.
 * Version: 3.1.2
 * Author: ADK Web Solutions
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Text Domain: gffm
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define('GFFM_VERSION', '3.1.2');
define('GFFM_DIR', plugin_dir_path(__FILE__));
define('GFFM_URL', plugin_dir_url(__FILE__));

// Activation/Deactivation
register_activation_hook(__FILE__, function(){
    // Ensure roles & caps
    if ( file_exists( GFFM_DIR . 'includes/class-gffm-roles.php' ) ) {
        require_once GFFM_DIR . 'includes/class-gffm-roles.php';
        if ( class_exists('GFFM_Roles') ) { GFFM_Roles::add_roles(); }
    }
    flush_rewrite_rules();
});
register_deactivation_hook(__FILE__, function(){ flush_rewrite_rules(); });

// Core includes (guarded)
$files = [
    'includes/class-gffm-roles.php',
    'includes/class-gffm-post-types.php',
    'includes/class-gffm-settings.php',
    'includes/class-gffm-admin.php',
    'includes/class-gffm-enrollment.php',
    'includes/class-gffm-waitlist.php',
    'includes/class-gffm-invoices.php',
    'includes/class-gffm-export.php',
    'includes/class-gffm-cron.php',
    'includes/class-gffm-rest.php',
    // Phase 0 additions
    'includes/class-gffm-schema.php',
    'includes/class-gffm-wizard.php',
    'includes/class-gffm-integrations.php',
    'includes/class-gffm-portal.php',
];
foreach ( $files as $rel ) {
    $path = GFFM_DIR . $rel;
    if ( file_exists($path) ) { require_once $path; }
}

// Enqueue admin assets on our screens
add_action('admin_enqueue_scripts', function($hook){
    if ( strpos($hook, 'gffm') !== false ) {
        wp_enqueue_style('gffm-admin', GFFM_URL . 'assets/css/admin.css', [], GFFM_VERSION);
        wp_enqueue_script('gffm-admin', GFFM_URL . 'assets/js/admin.js', [], GFFM_VERSION, true);
    }
});

// Shortcodes
if ( class_exists('GFFM_Enrollment') ) {
    add_shortcode('gffm_enroll', ['GFFM_Enrollment', 'shortcode']);
}

// Compatibility placeholder
add_action('init', function(){
    // Ensure shortcodes work with editors/builders
}, 11);

// Safe DB installer: manual step (avoid activation fatals)
add_action('admin_post_gffm_run_schema', function(){
    if( ! current_user_can('gffm_manage') ) { wp_die(__('No permission','gffm')); }
    check_admin_referer('gffm_run_schema');
    if ( file_exists( GFFM_DIR . 'includes/class-gffm-schema.php' ) ) {
        require_once GFFM_DIR . 'includes/class-gffm-schema.php';
        if ( class_exists('GFFM_Schema') ) { GFFM_Schema::install(); }
    }
    update_option('gffm_schema_installed', 1);
    wp_safe_redirect( admin_url('admin.php?page=gffm') );
    exit;
});

add_action('admin_notices', function(){
    if( ! current_user_can('gffm_manage') ) { return; }
    if( ! get_option('gffm_schema_installed') ) {
        $url = wp_nonce_url( admin_url('admin-post.php?action=gffm_run_schema'), 'gffm_run_schema' );
        echo '<div class="notice notice-warning"><p>';
        echo esc_html__('GFFM Market Manager needs to create/upgrade its database tables.','gffm').' ';
        echo '<a class="button button-primary" href="'.esc_url($url).'">'.esc_html__('Run Database Setup','gffm').'</a>';
        echo '</p></div>';
    }
});
