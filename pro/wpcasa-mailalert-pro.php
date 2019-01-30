<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

include_once plugin_dir_path( __FILE__ ) . '/admin/wpcasamapro_admin_menu.php';
include_once plugin_dir_path( __FILE__ ) . '/admin/wpcasama_pro_export.php';
include_once plugin_dir_path( __FILE__ ) . '/inc/wpcasama-widget-pro-functions.php';
include_once plugin_dir_path( __FILE__ ) . '/inc/wpcasama_pro_search.php';
include_once plugin_dir_path( __FILE__ ) . '/inc/shortcode.php';

add_action( 'plugins_loaded', 'wpcasama_load_textdomain' );
add_action( 'admin_init', 'wpcasama_register_admin_style' );
add_action( 'wp_enqueue_scripts', 'wpcasama_register_style' );

register_activation_hook( __FILE__, 'wpcasama_pro_activation' );
register_deactivation_hook( __FILE__, 'wpcasama_pro_deactivation' );
register_uninstall_hook( __FILE__, 'wpcasama_pro_uninstall' );

define( 'WPCASAMA_PRO_VERSION', '2.1.0' );
define( 'THIVINFO_ITEM_NAME', 'WPCasa Mail Alert Pro' );
define( 'SHOP_URL', 'https://www.thivinfo.com' );
define( 'WPCASAPRO_ID', 2186 );
define( 'WPCASAMA_PRO_AUTHOR', 'Sebastien SERRE' );

function wpcasama_pro_deactivation() {
	do_action( 'wpcasama/pro/before/deactivation' );

	delete_option( 'wpcasama_pro_activated' );

	do_action( 'wpcasama_pro_deactivation' );
}

function wpcasama_pro_uninstall() {
	do_action( 'wpcasama/pro/before/uninstall' );
}


function wpcasama_load_textdomain() {
	load_plugin_textdomain( 'wpcasa-mail-alert-pro', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

function wpcasama_register_admin_style() {
	wp_enqueue_style( 'wpcasama_mailalert_admin_style', plugins_url( 'assets/css/admin-styles.css', __FILE__ ) );
}

function wpcasama_register_style() {
	wp_enqueue_style( 'wpcasama_pro_style', plugins_url( 'assets/css/wpcasama-pro.css', __FILE__ ), array( 'wpcasa_style' ) );
}
