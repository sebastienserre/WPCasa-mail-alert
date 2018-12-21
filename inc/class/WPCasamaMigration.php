<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Class WPCasamaMigration
 */
class WPCasamaMigration {

	public $user_id;

	public function __construct() {
		add_action( 'admin_init', array( $this, 'wpcasama_select_old_alert' ) );
	}

	public function wpcasama_select_old_alert() {
		$migrate = wpsight_get_option( 'wpcasama_migration' );

		if ( 'WPCASAMA_MIGRATED' === true ) {
			return;
		}

		if ( false !== $migrate ) {

			global $wpdb;

			$table_name = $wpdb->prefix . 'wpcasama_mailalert';
			$alerts     = $wpdb->get_results( "SELECT * FROM $table_name" );


			foreach ( $alerts as $alert ) {
				$userdata = array(
					'user_login'    => $alert->name,
					'user_email'    => $alert->email,
					'user_nicename' => $alert->name,

				);

				$user_exists = get_user_by( 'email', $userdata['user_email'] );


				if ( false === $user_exists ) {
					$this->user_id = wp_insert_user( $userdata );
				} else {
					$this->user_id = $user_exists->ID;
				}

				$postarr = array(
					'post_author' => $this->user_id,
					'post_title'  => $alert->name . '-' . $alert->city,
					'post_status' => 'publish',
					'post_type'   => 'wpcasa-mail-alerte',
					'post_date'   => $alert->subscription,
					'meta_input'  => array(
						'wpcasama_phone'     => $alert->tel,
						'wpcasama_city'      => $alert->city,
						'wpcasama_min_price' => intval( $alert->min_price ),
						'wpcasama_max_price' => intval( $alert->max_price ),
					),

				);

				$alert_created = wp_insert_post( $postarr );

				$wpdb->query( "DROP TABLE IF EXISTS $table_name" );

				define( 'WPCASAMA_MIGRATED', true );

			}
		}


	}

}
