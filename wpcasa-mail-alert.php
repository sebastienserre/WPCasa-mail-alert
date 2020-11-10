<?php

/**
 * Plugin Name: WPCasa Mail Alert
 * Plugin URI: https://www.thivinfo.com/en/shop/add-mail-alert-to-your-wpcasa-website/
 * Description: Allow Visitor to subscribe to a mail alert to receive a mail when a new property is added.
 * Version: 3.3.0
 * Author: Sébastien Serre
 * Author URI: http://www.thivinfo.com
 * License: GPL2
 * Tested up to: 5.5
 * Text Domain: wpcasa-mail-alert
 * Domain Path: /pro/languages
 * Depends: WPCasa,
 **/


define( 'PLUGIN_VERSION', '3.3.0' );
define( 'WPCASAMA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WPCASAMA_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'WPCASAMA_PLUGIN_DIR', untrailingslashit( WPCASAMA_PLUGIN_PATH ) );
include_once WPCASAMA_PLUGIN_PATH . '/inc/class/thfo_mailalert_load.php';
include_once WPCASAMA_PLUGIN_PATH . '/inc/class/thfo_mailalert_widget.php';
include_once WPCASAMA_PLUGIN_PATH . '/inc/class/thfo_mailalert_unsubscribe.php';
include_once WPCASAMA_PLUGIN_PATH . '/inc/admin/wpcasa-admin.php';
/**
 * 2.0.0
 */
include_once WPCASAMA_PLUGIN_PATH . '/inc/cpt/mail-alert-cpt.php';
include_once WPCASAMA_PLUGIN_PATH . '/inc/cpt/metabox.php';
include_once WPCASAMA_PLUGIN_PATH . '/inc/class/wpcasama_account.php';
include_once WPCASAMA_PLUGIN_PATH . '/inc/class/wpcasama_search.php';
include_once WPCASAMA_PLUGIN_PATH . '/inc/class/WPCasamaMigration.php';
include_once WPCASAMA_PLUGIN_PATH . '/inc/blocks/class-blocks-form.php';
include_once WPCASAMA_PLUGIN_PATH . 'inc/3rd-party/plugin-dependencies/plugin-dependencies.php';

new thfo_mailalert();
new thfo_mailalert_widget();
new wpcasama_metabox();
new wpcasama_account();
new wpcasama_search();
new WPCasamaMigration();
add_action( 'plugins_loaded', 'thfo_load_textdomain' );
add_action( 'admin_init', 'thfo_register_admin_style' );
add_action( 'wp_enqueue_scripts', 'thfo_register_style' );
add_filter( 'acf/helpers/get_path', 'wpcasama_settings_path' );
add_filter( 'acf/helpers/get_dir', 'wpcasama_settings_dir' );
add_action( 'init', 'wpcasama_remove_adminbar' );
add_action( 'admin_init', 'wpcasa_mailalert_policy' );

register_activation_hook( __FILE__, 'wpcasama_activation' );
register_uninstall_hook( __FILE__, 'wpcasama_uninstall' );


function wpcasama_activation()
{

	// Is WPCasa activated
	if ( ! class_exists( 'WPSight_Framework' ) ) {
		wp_die( __( 'WPCASA is not activated. WPCasa Mail-Alert need it to work properly. Please activate WPCasa.', 'wpcasa-mail-alert' ) );

	}

	do_action( 'wpcasama_pro_activation' );
	wpcasama_cpt();
	flush_rewrite_rules();
	wpcasama_create_table();

	if (! wp_next_scheduled ( 'wpcasama_hourly' )) {
		wp_schedule_event(time(), 'hourly', 'wpcasama_hourly');
	}
}
/**
 * Hide admin bar for non admin
 */
function wpcasama_remove_adminbar()
{
    $user = wp_get_current_user();
    $allowed_roles = array( 'administrator' );
    if ( !array_intersect( $allowed_roles, $user->roles ) ) {
        add_filter( 'show_admin_bar', '__return_false' );
    }
}

function wpcasama_uninstall()
{
    global  $wpdb ;
    $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}wpcasama_mailalert;" );
}

function thfo_load_textdomain()
{
    load_plugin_textdomain( 'wpcasa-mail-alert', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

function thfo_register_admin_style()
{
    wp_enqueue_style( 'thfo_mailalert_admin_style', plugins_url( 'assets/css/admin-styles.css', __FILE__ ) );
}

function thfo_register_style()
{
    wp_enqueue_style( 'wpcasa_style', plugins_url( 'assets/css/wpcasa-mail-alert.css', __FILE__ ) );
}

function wpcasa_mailalert_policy()
{
    if ( !function_exists( 'wp_add_privacy_policy_content' ) ) {
        return;
    }
    $content = __( 'When you register to receive a mail alert, we\'re registering your name, your e-mail address and your phone number', 'wpcasa-mail-alert' );
    $content = apply_filters( 'wpcasama/policy/text', $content );
    wp_add_privacy_policy_content( 'WPCasa Mail Alert', wp_kses_post( wpautop( $content, false ) ) );
}

function wpcasama_create_table() {
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name      = $wpdb->prefix . 'wpcasama';
	$sql             = "CREATE TABLE IF NOT EXISTS $table_name(
id mediumint(9) NOT NULL AUTO_INCREMENT,
user_mail varchar (45) DEFAULT NULL,
bien mediumint (9) DEFAULT NULL,
PRIMARY KEY (id)
) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}

/**
 * Load carbon Fields
 */
add_action( 'after_setup_theme', 'crb_load' );
function crb_load() {
	require_once( WPCASAMA_PLUGIN_PATH . '/inc/3rd-party/vendor/autoload.php' );
	\Carbon_Fields\Carbon_Fields::boot();
}
