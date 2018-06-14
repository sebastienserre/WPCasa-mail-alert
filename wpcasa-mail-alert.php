<?php

/*
Plugin Name: WPCasa Mail Alert
Plugin URI: https://www.thivinfo.com/downloads/wpcasa-mail-alert-pro/
Description: Allow Visitor to subscribe to a mail alert to receive a mail when a new property is added.
Version: 2.0.3
Author: Sébastien Serre
Author URI: http://www.thivinfo.com
License: GPL2
Tested up to: 4.9
Text Domain: wpcasa-mail-alert
Domain Path: /languages
*/

class thfo_mail_alert {
	function __construct() {


		define( 'PLUGIN_VERSION', '2.0.3' );

		define( 'WPCASAMA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		define( 'WPCASAMA_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
		define( 'WPCASAMA_PLUGIN_DIR', untrailingslashit( WPCASAMA_PLUGIN_PATH ) );
		define( 'WPCASAMA_PLUGIN_PRICE', '29,90€' );
		define( 'WPCASAMAPRO_LINK', 'https://www.thivinfo.com/en/downloads/wpcasa-mail-alert-pro/ref/4/' );

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
		include_once WPCASAMA_PLUGIN_PATH . '/inc/class/WpcasamaSendMail.php';
		include_once WPCASAMA_PLUGIN_PATH . '/inc/class/WPCasamaMigration.php';


		new thfo_mailalert();
		new thfo_mailalert_widget();

		new wpcasama_metabox();
		new wpcasama_account();
		new wpcasama_search();
		new WpcasamaSendMail();
		new WPCasamaMigration();

		add_action( 'plugins_loaded', array( $this, 'thfo_load_textdomain' ) );
		add_action( 'admin_init', array( $this, 'thfo_register_admin_style' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'thfo_register_style' ) );
		add_filter( 'acf/helpers/get_path', array( $this, 'wpcasama_settings_path' ) );
		add_filter( 'acf/helpers/get_dir', array( $this, 'wpcasama_settings_dir' ) );
		add_action( 'init', array( $this, 'wpcasama_remove_adminbar' ) );
		add_action( 'admin_init', array( $this, 'wpcasa_mailalert_policy' ) );


		if ( is_multisite() ) {
			add_action( 'network_admin_notices', array( $this, 'wpcasa_mailalert_check_wpcasa' ) );
		} else {
			add_action( 'admin_notices', array( $this, 'wpcasa_mailalert_check_wpcasa' ) );
		}

		register_activation_hook( __FILE__, array( $this, 'wpcasama_pro_activation' ) );
		register_uninstall_hook( __FILE__, 'wpcasama_uninstall' );


	}

	/**
	 * Hide admin bar for non admin
	 */
	function wpcasama_remove_adminbar() {
		$user          = wp_get_current_user();
		$allowed_roles = array( 'administrator' );
		if ( ! array_intersect( $allowed_roles, $user->roles ) ) {
			add_filter( 'show_admin_bar', '__return_false' );
		}
	}

	function wpcasama_pro_activation() {

		do_action( 'wpcasama_pro_activation' );

		wpcasama_cpt();
		flush_rewrite_rules();
	}

	function wpcasama_uninstall() {
		global $wpdb;
		$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}wpcasama_mailalert;" );
	}

	public function thfo_load_textdomain() {
		load_plugin_textdomain( 'wpcasa-mail-alert', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	public function thfo_register_admin_style() {
		wp_enqueue_style( 'thfo_mailalert_admin_style', plugins_url( 'assets/css/admin-styles.css', __FILE__ ) );
	}

	public function thfo_register_style() {
		wp_enqueue_style( 'wpcasa_style', plugins_url( 'assets/css/wpcasa-mail-alert.css', __FILE__ ) );
	}

	public function wpcasa_mailalert_check_wpcasa() {
		if ( ! class_exists( 'WPSight_Framework' ) ) {

			echo '<div class="notice notice-error"><p>' . __( 'WPCASA is not activated. WPCasa Mail-Alert need it to work properly. Please activate WPCasa.', 'wpcasa-mail-alert' ) . '</p></div>';

			deactivate_plugins( plugin_basename( __FILE__ ) );

		}
	}

	public function wpcasa_mailalert_policy() {

		if ( ! function_exists( 'wp_add_privacy_policy_content' ) ) {

			return;
		}

		$content =
			__( 'When you register to receive a mail alert, we\'re registering your name, your e-mail address and your phone number',
				'wpcasa-mail-alert' );

		$content = apply_filters( 'wpcasama/policy/text', $content );

		wp_add_privacy_policy_content(
			'WPCasa Mail Alert',
			wp_kses_post( wpautop( $content, false ) )
		);

	}


}

new thfo_mail_alert();