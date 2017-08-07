<?php

defined( 'ABSPATH' ) OR exit;

add_action('wp_enqueue_scripts','gugl_add_my_stylesheet');
add_action( 'admin_print_styles', 'gugl_add_my_stylesheet' ); //Admin style
function gugl_add_my_stylesheet() {
    wp_register_style('gugl-style', plugins_url('style.css', __FILE__));
    wp_enqueue_style('gugl-style');
}

//for tranlation
$color_translations= array(__('Grey','gugl'),__('Green','gugl'),__('Blue','gugl'),__('Red','gugl'),__('Orange','gugl'),__('White','gugl'),__('Purple','gugl'),__('Pink','gugl'),__('Custom','gugl'));

load_plugin_textdomain('gugl', 'wp-content/plugins/gugl/lang','gugl/lang');

?>