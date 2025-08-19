<?php
if (!defined('ABSPATH')) { exit; }

class GFFM_Portal {
    public static function init(){
        add_shortcode('gffm_portal', [__CLASS__, 'shortcode']);
        add_action('add_meta_boxes', [__CLASS__, 'vendor_metabox']);
        add_action('admin_post_gffm_send_magic_link', [__CLASS__, 'send_magic_link']);
    }

    public static function vendor_cpt_slug(){
        // Use existing vendor CPT if present, else fallback
        $use_internal = get_option('gffm_use_internal_vendors','no') === 'yes';
        return $use_internal ? 'gffm_vendor' : 'vendor';
    }

    public static function vendor_email_keys(){
        return ['_email', 'email', 'contact_email'];
    }

    public static function vendor_metabox(){
        $cpt = self::vendor_cpt_slug();
        add_meta_box('gffm_vendor_portal', __('Vendor Portal','gffm'), [__CLASS__, 'render_vendor_box'], $cpt, 'side', 'high');
    }

    public static function render_vendor_box($post){
        if (!current_user_can('gffm_manage')) { echo esc_html__('No permission.','gffm'); return; }
        wp_nonce_field('gffm_vendor_portal','gffm_vendor_portal_nonce');

        // find email
        $email = '';
        foreach (self::vendor_email_keys() as $k){
            $v = get_post_meta($post->ID, $k, true);
            if (is_email($v)){ $email = $v; break; }
        }
        if (!$email){
            $email = get_option('gffm_notification_email', get_option('admin_email'));
        }
        $url = admin_url('admin-post.php?action=gffm_send_magic_link&vendor_id='.$post->ID.'&_wpnonce='.wp_create_nonce('gffm_send_magic_link'));
        echo '<p>'.esc_html__('Magic link sends a one-time login to the vendor portal.','gffm').'</p>';
        echo '<p>'.esc_html__('To:','gffm').' <code>'.esc_html($email).'</code></p>';
        echo '<p><a class="button" href="'.esc_url($url).'">'.esc_html__('Send Magic Link','gffm').'</a></p>';
    }

    public static function token_create($vendor_id){
        $data = $vendor_id.'|'.time();
        $sig = wp_hash($data);
        return base64_encode($data.'|'.$sig);
    }

    public static function token_verify($token, $ttl=3600){
        $raw = base64_decode($token);
        if (!$raw) return false;
        $parts = explode('|', $raw);
        if (count($parts) !== 3) return false;
        list($vendor_id, $ts, $sig) = $parts;
        if ((time() - (int)$ts) > $ttl) return false;
        $data = $vendor_id.'|'.$ts;
        if (hash_equals(wp_hash($data), $sig)){
            return absint($vendor_id);
        }
        return false;
    }

    public static function send_magic_link(){
        if (!current_user_can('gffm_manage')) wp_die(__('No permission','gffm'));
        if (!wp_verify_nonce($_GET['_wpnonce'] ?? '', 'gffm_send_magic_link')) wp_die(__('Bad nonce','gffm'));
        $vendor_id = absint($_GET['vendor_id'] ?? 0);
        if (!$vendor_id) wp_die(__('Missing vendor','gffm'));
        $token = self::token_create($vendor_id);

        $portal_url = add_query_arg(['token'=>$token], home_url('/vendor-portal/'));
        $email = '';
        foreach (self::vendor_email_keys() as $k){
            $v = get_post_meta($vendor_id, $k, true);
            if (is_email($v)){ $email = $v; break; }
        }
        if (!$email){
            $email = GFFM_Settings::notify_email();
        }

        $subject = __('Your Vendor Portal Link','gffm');
        $body = sprintf(__('Use this link to access your portal: %s (expires in 60 minutes)','gffm'), $portal_url);
        wp_mail($email, $subject, $body);
        wp_safe_redirect(get_edit_post_link($vendor_id, ''));
        exit;
    }

    public static function shortcode($atts){
        $atts = shortcode_atts([ 'redirect' => '' ], $atts, 'gffm_portal');
        $token = isset($_GET['token']) ? sanitize_text_field($_GET['token']) : '';
        $vendor_id = $token ? self::token_verify($token, 3600) : 0;

        ob_start();
        echo '<div class="gffm-portal">';
        if ($vendor_id){
            $name = get_the_title($vendor_id);
            echo '<h2>'.esc_html__('Welcome,','gffm').' '.esc_html($name).'</h2>';
            echo '<ul>';
            echo '<li>'.esc_html__('My Profile (coming soon)','gffm').'</li>';
            echo '<li>'.esc_html__('My Schedule (coming soon)','gffm').'</li>';
            echo '<li>'.esc_html__('My Booth (coming soon)','gffm').'</li>';
            echo '<li>'.esc_html__('My Invoices (coming soon)','gffm').'</li>';
            echo '<li>'.esc_html__('My Documents (coming soon)','gffm').'</li>';
            echo '</ul>';
        } else {
            echo '<p>'.esc_html__('This link is invalid or expired. Please request a new magic link from the market manager.','gffm').'</p>';
        }
        echo '</div>';
        return ob_get_clean();
    }
}
GFFM_Portal::init();
