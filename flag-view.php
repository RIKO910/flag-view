<?php
/**
 * Plugin Name: Flag View
 * Plugin URI: https://wordpress.org/plugins/flag-view/
 * Description: Display interactive country flags with names and fun hover animations. Perfect for multilingual sites, travel blogs, and country-specific content.
 * Version: 1.0.0
 * Requires at least: 6.5
 * Requires PHP: 7.2
 * Author: Tarikul Islam Riko
 * Author URI: https://tarikul.blog
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: flag-view
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('FLAG_VIEW_VERSION', '1.0.0');
define('FLAG_VIEW_PATH', plugin_dir_path(__FILE__));
define('FLAG_VIEW_URL', plugin_dir_url(__FILE__));

// Enqueue scripts and styles
function flag_view_enqueue_assets() {
    wp_enqueue_style(
        'flag-view-style',
        FLAG_VIEW_URL . 'assets/css/style.css',
        array(),
        FLAG_VIEW_VERSION
    );

    wp_enqueue_script(
        'flag-view-script',
        FLAG_VIEW_URL . 'assets/js/script.js',
        array('jquery'),
        FLAG_VIEW_VERSION,
        true
    );
}
add_action('wp_enqueue_scripts', 'flag_view_enqueue_assets');

// Shortcode to display flags
function flag_view_shortcode($atts) {
    $atts = shortcode_atts(array(
        'countries' => 'us,gb,ca,au,de,fr,jp,br,in',
        'size' => 'medium',
        'animation' => 'jump'
    ), $atts, 'flag_view');

    $countries = explode(',', $atts['countries']);
    $output = '<div class="flag-view-container">';

    foreach ($countries as $country) {
        $country = trim(strtolower($country));
        $flag_url = FLAG_VIEW_URL . "flags/{$country}.png";
        $country_name = flag_view_get_country_name($country);

        $output .= sprintf(
            '<div class="flag-view-item" data-country="%s" data-animation="%s">
                <img src="%s" alt="%s" class="flag-view-flag flag-view-size-%s">
                <span class="flag-view-country-name">%s</span>
            </div>',
            esc_attr($country),
            esc_attr($atts['animation']),
            esc_url($flag_url),
            esc_attr($country_name),
            esc_attr($atts['size']),
            esc_html($country_name)
        );
    }

    $output .= '</div>';
    return $output;
}
add_shortcode('flag_view', 'flag_view_shortcode');

// Helper function to get country name from code
function flag_view_get_country_name($code) {
    $countries = array(
        'us' => __('United States', 'flag-view'),
        'gb' => __('United Kingdom', 'flag-view'),
        'ca' => __('Canada', 'flag-view'),
        'au' => __('Australia', 'flag-view'),
        'de' => __('Germany', 'flag-view'),
        'fr' => __('France', 'flag-view'),
        'jp' => __('Japan', 'flag-view'),
        'br' => __('Brazil', 'flag-view'),
        'in' => __('India', 'flag-view'),
        // Add more countries as needed
    );

    return isset($countries[$code]) ? $countries[$code] : ucfirst($code);
}

// Load textdomain
function flag_view_load_textdomain() {
    load_plugin_textdomain('flag-view', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'flag_view_load_textdomain');