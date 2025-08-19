<?php
if (!defined('ABSPATH')) { exit; }

class GFFM_Wizard {
    public static function init(){
        add_action('admin_menu', [__CLASS__, 'menu']);
    }
    public static function menu(){
        add_submenu_page('gffm', __('Setup Wizard','gffm'), __('Setup Wizard','gffm'), 'gffm_manage', 'gffm_wizard', [__CLASS__,'render']);
    }
    public static function render(){
        if (!current_user_can('gffm_manage')) { wp_die(__('No permission','gffm')); }
        echo '<div class="wrap gffm-admin"><h1>'.esc_html__('Setup Wizard','gffm').'</h1>';
        echo '<ol>';
        echo '<li>'.esc_html__('General','gffm').'</li>';
        echo '<li>'.esc_html__('Seasons','gffm').'</li>';
        echo '<li>'.esc_html__('Booths','gffm').'</li>';
        echo '<li>'.esc_html__('Applications','gffm').'</li>';
        echo '<li>'.esc_html__('Payments (Square)','gffm').'</li>';
        echo '<li>'.esc_html__('Communications','gffm').'</li>';
        echo '<li>'.esc_html__('Map','gffm').'</li>';
        echo '</ol>';
        echo '<p>'.esc_html__('Placeholder UI for Phase 0. In Phase 1 we will wire real forms, validation, and save handlers.','gffm').'</p>';
        echo '</div>';
    }
}
GFFM_Wizard::init();
