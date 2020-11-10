<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class thfo_mailalert {

	//public $params = array();

	function __construct() {
		add_action( 'widgets_init', function () {
			register_widget( 'thfo_mailalert_widget' );
		} );

		add_action( 'wp_loaded', array( $this, 'save_results' ) );
		add_action( 'wp_loaded', array( $this, 'thfo_delete_subscriber' ) );

	}

	/**
	 * @since 2.0.0
	 * Save Results from Widget to PostMeta
	 */
	public function save_results() {

		if ( isset( $_POST['thfo_mailalert'] ) ) {

			/**
			 * If no agreement => back to form with error message
			 */

			if ( $_POST['wpcasama-account-agreement'] != 'checked' && ! is_user_logged_in() ) {


				add_action( 'wpcasama_info', array( $this, 'wpcasama_error_message' ) );

				return false;
			}


			/**
			 * If agreement ok, then, we registered the user
			 */

				$userdata['first_name'] = sanitize_text_field( $_POST['thfo_mailalert_firstname'] );
				$userdata['last_name']  = sanitize_text_field( $_POST['thfo_mailalert_lastname'] );


			if (is_email( $_POST['thfo_mailalert_email'] ) ){
				$email = $_POST['thfo_mailalert_email'];
			}
			$userdata = array(
				'user_login'    => $email,
				'user_email'    => $email,
				'user_nicename' => sanitize_text_field( $_POST['thfo_mailalert_name'] ),

			);

			$userdata = apply_filters( 'wpcasama/savedata/user', $userdata );

			$user_exists = get_user_by( 'email', $userdata['user_email'] );


			if ( $user_exists == false ) {
				$user_id = wp_insert_user( $userdata );
			} else {
				$user_id = $user_exists->ID;
			}

			$update = update_user_meta( $user_id, 'wpcasama_phone', $_POST['thfo_mailalert_phone'] );

			/**
			 * User created, create alert
			 */
			$meta = array(
				'wpcasama_city'         => $_POST['thfo_mailalert_city'],
				'wpcasama_min_price'    => intval( $_POST['thfo_mailalert_min_price'] ),
				'wpcasama_max_price'    => intval( $_POST['thfo_mailalert_price'] ),
				'wpcasama_listing_type' => $_POST['wpcasama_type'],
			);

			$meta = apply_filters( 'wpcasama/pro/save/meta', $meta );

			$postarr = array(
				'post_author' => $user_id,
				'post_title'  => $_POST['thfo_mailalert_name'] . '-' . $_POST['thfo_mailalert_city'],
				'post_status' => 'publish',
				'post_type'   => 'wpcasa-mail-alerte',
				'meta_input'  => $meta,

			);

			apply_filters( 'wpcasama/savedata/post', $userdata );

			wp_insert_post( $postarr );
			$search = new wpcasama_search;
			$search->wpcasama_search_alert();

		}
	}

	public function thfo_delete_subscriber() {
		if ( isset( $_GET['delete'] ) && ! empty( $_GET['delete'] ) ) {
			global $wpdb;
			$wpdb->delete( "{$wpdb->prefix}wpcasama_mailalert", array( 'email' => $_GET['delete'] ) );
		}
	}

	/**
	 * @since 2.0.0
	 * @return string|void
	 */

	public function wpcasama_error_message() {
		$error = '<p class="wpcasama_error">' . _e( 'To receive an alert, you need to accept creating an account', 'wpcasa-mail-alert' ) . '</p>';

		return $error;
	}


}
