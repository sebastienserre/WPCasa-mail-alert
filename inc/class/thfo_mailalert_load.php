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
	
	/**
	 * @since 2.0.0
	 * Save Results from Widget to PostMeta
	 */
	public function save_results() {

		if ( isset( $_POST['thfo_mailalert'] ) ) {
			
			var_dump($_POST);
			
			/**
			 * If no agreement => back to form with error message
			 */
			
			if ($_POST['wpcasama-account-agreement'] != 'checked'){
				
				
				add_action('wpcasama_info', array($this, 'wpcasama_error_message'));
				return false;
			}
			
			
			/**
			 * If agreement ok, then, we registered the user
			 */
			
			$userdata = array(
				'user_login' => $_POST['thfo_mailalert_email'],
				'user_email' => $_POST['thfo_mailalert_email'],
				'user_nicename' => $_POST['thfo_mailalert_name'],
				
			);
			
			apply_filters('wpcasama/savedata/user', $userdata);
			
			$user_id = wp_insert_user( $userdata ) ;
			
			
			/**
			 * User created, create alert
			 */
			
			$postarr = array(
				'post_author'   => $user_id,
				'post_title'    => $_POST['thfo_mailalert_name'] . '-' . $_POST['thfo_mailalert_city'],
				'post_status'   => 'publish',
				'post_type' =>  'wpcasa-mail-alerte',
				'meta_input' => array(
					'wpcasama_phone' => $_POST['thfo_mailalert_phone'],
					'wpcasama_city' => $_POST['thfo_mailalert_city'],
					'wpcasama_min_price' => $_POST['thfo_mailalert_min_price'],
					'maximum_price' => $_POST['thfo_mailalert_price'],
				),
				
			);
			
			apply_filters('wpcasama/savedata/post', $userdata);
			
			wp_insert_post($postarr);
			
		}
	}

	public function thfo_delete_subscriber(){
		if (isset($_GET['delete']) && ! empty($_GET['delete'])){
			global $wpdb;
			$wpdb->delete("{$wpdb->prefix}wpcasama_mailalert", array('email' => $_GET['delete']));
		}
	}
	
	/**
	 * @since 2.0.0
	 * @return string|void
	 */
	
	public function wpcasama_error_message() {
		$error = '<p class="wpcasama_error">' . _e('To receive an alert, you need to accept creating an account', 'wpcasa-mail-alert') .'</p>';
		
		return $error;
	}


}