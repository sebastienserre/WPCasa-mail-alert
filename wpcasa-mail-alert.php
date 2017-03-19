<?php

/*
Plugin Name: WPCasa Mail Alert
Plugin URI: https://www.thivinfo.com/downloads/wpcasa-mail-alert-pro/
Description: Allow Visitor to subscribe to a mail alert to receive a mail when a new property is added.
Version: 1.0
Author: Sébastien Serre
Author URI: http://www.thivinfo.com
License: GPL2
Tested up to: 4.7.3
Text Domain: wpcasa-mail-alert
Domain Path: /languages
*/

class thfo_mail_alert {
	function __construct() {

		include_once plugin_dir_path( __FILE__ ) . '/class/thfo_mailalert_load.php';
		include_once plugin_dir_path( __FILE__ ) . '/class/thfo_mailalert_widget.php';
		include_once plugin_dir_path( __FILE__ ) . '/class/thfo_mailalert_search.php';
		include_once plugin_dir_path( __FILE__ ) . '/class/thfo_mailalert_admin_menu.php';
		include_once plugin_dir_path( __FILE__ ) . '/class/thfo_mailalert_unsubscribe.php';

		new thfo_mailalert();
		new thfo_mailalert_widget();
		new thfo_mailalert_admin_menu();
		new thfo_mailalert_unsubscribe();

		add_action( 'plugins_loaded', array( $this, 'thfo_load_textdomain' ) );
		add_action( 'admin_init', array( $this, 'thfo_register_admin_style' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'thfo_register_style' ) );

		register_activation_hook( __FILE__, array( $this, 'wpcasama_pro_activation' ) );
		register_uninstall_hook( __FILE__, 'wpcasama_pro_deactivation' );

		define( 'PLUGIN_VERSION', '1.0' );

	}

	function wpcasama_pro_activation() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$table_name = $wpdb->prefix . 'wpcasama_mailalert';

		$sql = "CREATE TABLE $table_name (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
name VARCHAR (255),
email VARCHAR (255) NOT NULL,
tel VARCHAR (20),
city VARCHAR (255),
max_price VARCHAR (10),
min_price VARCHAR (10),
room VARCHAR (2),
subscription datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  PRIMARY KEY  (id)
) $charset_collate;";


		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		do_action( 'wpcasama_pro_activation' );
	}

	function wpcasama_pro_deactivation() {
		global $wpdb;
		$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}wpcasama_mailalert;" );
		do_action( 'wpcasama_pro_deactivation' );
	}

	public function thfo_load_textdomain() {
		load_plugin_textdomain( 'wpcasa-mail-alert', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	public function thfo_register_admin_style() {
		wp_enqueue_style( 'thfo_mailalert_admin_style', plugins_url( 'assets/css/admin-styles.css', __FILE__ ) );
	}

	public function thfo_register_style() {
		wp_enqueue_style( 'thfo_mailalert_style', plugins_url( 'assets/css/styles.css', __FILE__ ) );
	}


}

new thfo_mail_alert();