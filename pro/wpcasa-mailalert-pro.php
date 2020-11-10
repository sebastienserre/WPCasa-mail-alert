<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.
require_once plugin_dir_path( __FILE__ ) . '/admin/wpcasamapro_admin_menu.php';
require_once plugin_dir_path( __FILE__ ) . '/admin/wpcasama_pro_export.php';
require_once plugin_dir_path( __FILE__ ) . '/inc/wpcasama-widget-pro-functions.php';
require_once plugin_dir_path( __FILE__ ) . '/inc/wpcasama_pro_search.php';
require_once plugin_dir_path( __FILE__ ) . '/inc/shortcode.php';
require_once WPCASAMA_PLUGIN_PATH . '/pro/inc/helpers.php';

add_action( 'plugins_loaded', 'wpcasama_load_textdomain' );
add_action( 'admin_init', 'wpcasama_register_admin_style' );
add_action( 'wp_enqueue_scripts', 'wpcasama_register_style' );

register_activation_hook( __FILE__, 'wpcasama_pro_activation' );
register_deactivation_hook( __FILE__, 'wpcasama_pro_deactivation' );
register_uninstall_hook( __FILE__, 'wpcasama_pro_uninstall' );

function wpcasama_pro_deactivation() {
	do_action( 'wpcasama/pro/before/deactivation' );

	delete_option( 'wpcasama_pro_activated' );

	do_action( 'wpcasama_pro_deactivation' );
}

function wpcasama_pro_uninstall() {
	do_action( 'wpcasama/pro/before/uninstall' );
}

function wpcasama_register_admin_style() {
	wp_enqueue_style( 'wpcasama_mailalert_admin_style', plugins_url( 'assets/css/admin-styles.css', __FILE__ ) );
}

function wpcasama_register_style() {
	if ( '1' === wpsight_get_option( 'wpcasama_pro_load_css' ) ) {
		wp_enqueue_style( 'wpcasama_pro_style', plugins_url( 'assets/css/wpcasama-pro.css', __FILE__ ), array( 'wpcasa_style' ) );
	}
}
