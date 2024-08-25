<?php

function register_primary_menu() {
    register_nav_menu('primary', __('Primary Menu', 'menu'));
}
add_action('after_setup_theme', 'register_primary_menu');

function enqueue_custom_styles() {
    wp_enqueue_style('custom-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'enqueue_custom_styles');

function enqueue_custom_ajax_script() {
    wp_enqueue_script('ajax-script', get_template_directory_uri() . '/js/get_site_users.js', array('jquery'), null, true);
	
    wp_localize_script('ajax-script', 'ajax_object', array(
        'ajax_url' => get_template_directory_uri() . '/get_random_site_traffic.php'
    ));
}

add_action('wp_enqueue_scripts', 'enqueue_custom_ajax_script');
