<?php
if (!defined('ABSPATH')) { exit; }

class GFFM_Integrations {
    public static function init(){
        add_action('admin_init', [__CLASS__, 'register']);
        add_action('admin_menu', [__CLASS__, 'menu']);
    }
    public static function register(){
        register_setting('gffm_settings', 'gffm_openai_api_key', ['type'=>'string','sanitize_callback'=>'sanitize_text_field','default'=>'']);
        register_setting('gffm_settings', 'gffm_square_access_token', ['type'=>'string','sanitize_callback'=>'sanitize_text_field','default'=>'']);
        register_setting('gffm_settings', 'gffm_square_location_id', ['type'=>'string','sanitize_callback'=>'sanitize_text_field','default'=>'']);
        register_setting('gffm_settings', 'gffm_quickbooks_enabled', ['type'=>'boolean','sanitize_callback'=>function($v){return $v?1:0;},'default'=>0]);
    }
    public static function menu(){
        add_submenu_page('gffm', __('Integrations','gffm'), __('Integrations','gffm'), 'gffm_manage', 'gffm_integrations', [__CLASS__, 'render']);
    }
    public static function render(){
        if (!current_user_can('gffm_manage')) { wp_die(__('No permission','gffm')); }
        echo '<div class="wrap gffm-admin"><h1>'.esc_html__('Integrations','gffm').'</h1>';
        echo '<form method="post" action="options.php">';
        settings_fields('gffm_settings');
        echo '<table class="form-table" role="presentation">';
        echo '<tr><th><label for="gffm_openai_api_key">'.esc_html__('OpenAI API Key','gffm').'</label></th><td>';
        echo '<input type="text" id="gffm_openai_api_key" name="gffm_openai_api_key" value="'.esc_attr(get_option('gffm_openai_api_key','')).'" class="regular-text" />';
        echo '</td></tr>';
        echo '<tr><th><label for="gffm_square_access_token">'.esc_html__('Square Access Token','gffm').'</label></th><td>';
        echo '<input type="text" id="gffm_square_access_token" name="gffm_square_access_token" value="'.esc_attr(get_option('gffm_square_access_token','')).'" class="regular-text" />';
        echo '</td></tr>';
        echo '<tr><th><label for="gffm_square_location_id">'.esc_html__('Square Location ID','gffm').'</label></th><td>';
        echo '<input type="text" id="gffm_square_location_id" name="gffm_square_location_id" value="'.esc_attr(get_option('gffm_square_location_id','')).'" class="regular-text" />';
        echo '</td></tr>';
        echo '<tr><th><label for="gffm_quickbooks_enabled">'.esc_html__('Enable QuickBooks (CSV export first)','gffm').'</label></th><td>';
        echo '<input type="checkbox" id="gffm_quickbooks_enabled" name="gffm_quickbooks_enabled" value="1" '.checked(1, get_option('gffm_quickbooks_enabled',0), false).' />';
        echo '</td></tr>';
        echo '</table>';
        submit_button();
        echo '</form></div>';
    }
}
GFFM_Integrations::init();
