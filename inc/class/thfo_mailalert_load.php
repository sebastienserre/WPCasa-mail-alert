<?php
if ( ! defined( 'ABSPATH' ) ){ exit; } // Exit if accessed directly

class thfo_mailalert {

	//public $params = array();

	function __construct() {
		add_action( 'widgets_init', function () {
			register_widget( 'thfo_mailalert_widget' );
		} );

		add_action( 'wp_loaded', array( $this, 'save_results' ) );
		add_action( 'wp_loaded', array( $this, 'thfo_delete_subscriber' ) );



	}


	public function save_results() {

		if ( isset( $_POST['thfo_mailalert'] ) ) {

			//var_dump($_POST);
			global $wpdb;
			$name  = sanitize_text_field($_POST['thfo_mailalert_name']);
			if (is_email($_POST['thfo_mailalert_email'])) {
				$email = sanitize_email( $_POST['thfo_mailalert_email'] );
			}
			$phone = sanitize_text_field($_POST['thfo_mailalert_phone']);
			$city  = $_POST['thfo_mailalert_city'];
			$price = $_POST['thfo_mailalert_price'];
			$minprice = $_POST['thfo_mailalert_min_price'];
			$date = current_time('mysql');

			do_action( 'thfo_before_saving_result' );

			global $params;
			$params = array(
				'name'         => $name,
				'email'        => $email,
				'tel'          => $phone,
				'city'         => $city,
				'max_price'    => $price,
				'subscription' => $date,
				'min_price'    => $minprice,
			);

			$params = apply_filters( 'thfo_filter_db_save_params', $params );

			if( !empty( $params ) ) {
				$wpdb->replace( "{$wpdb->prefix}wpcasama_mailalert", $params );
			}

			do_action( 'thfo_after_saving_result' );
		}
	}

	public function thfo_delete_subscriber(){
		if (isset($_GET['delete']) && ! empty($_GET['delete'])){
			global $wpdb;
			$wpdb->delete("{$wpdb->prefix}wpcasama_mailalert", array('email' => $_GET['delete']));
		}
	}


}