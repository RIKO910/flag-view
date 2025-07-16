<?php
/*
Plugin Name: Country Flags Trending
Plugin URI: https://yourwebsite.com
Description: Display country flags with jumping animation on hover
Version: 1.0
Author: Your Name
Author URI: https://yourwebsite.com
License: GPL2
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Enqueue scripts and styles
function cft_enqueue_scripts() {
    wp_enqueue_style('cft-style', plugins_url('assets/css/style.css', __FILE__));
    wp_enqueue_script('cft-script', plugins_url('assets/js/script.js', __FILE__), array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'cft_enqueue_scripts');

// Shortcode to display flags
function cft_display_flags($atts) {
    $atts = shortcode_atts(array(
        'countries' => 'us,gb,ca,au,de,fr,jp,br,in',
        'size' => 'medium'
    ), $atts);

    $countries = explode(',', $atts['countries']);
    $output = '<div class="cft-flags-container">';

    foreach ($countries as $country) {
        $country = trim(strtolower($country));
        $flag_url = plugins_url("flags/{$country}.png", __FILE__);
        $country_name = cft_get_country_name($country);

        $output .= '<div class="cft-flag-item" data-country="' . esc_attr($country) . '">';
        $output .= '<img src="' . esc_url($flag_url) . '" alt="' . esc_attr($country_name) . '" class="cft-flag cft-size-' . esc_attr($atts['size']) . '">';
        $output .= '<span class="cft-country-name">' . esc_html($country_name) . '</span>';
        $output .= '</div>';
    }

    $output .= '</div>';
    return $output;
}
add_shortcode('country_flags', 'cft_display_flags');

// Helper function to get country name from code
function cft_get_country_name($code) {
    $countries = array(
        'us' => 'United States',
        'gb' => 'United Kingdom',
        'ca' => 'Canada',
        'au' => 'Australia',
        'de' => 'Germany',
        'fr' => 'France',
        'jp' => 'Japan',
        'br' => 'Brazil',
        'in' => 'India',
        // Add more countries as needed
    );

    return isset($countries[$code]) ? $countries[$code] : ucfirst($code);
}