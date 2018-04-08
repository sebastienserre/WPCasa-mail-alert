<?php
	if ( ! defined( 'ABSPATH' ) ){ exit; } // Exit if accessed directly
	
	class wpcasama_account {
		
		function __construct() {
			add_shortcode('wpcasama_account', array( $this , 'wpcasama_account'));
		}
		
		function wpcasama_account(){
			$user_id = get_current_user_id();
			
			if ($user_id == 0 ){
				$this->wpcasama_acount_login();
				$output .= wp_login_form();
			}
			
			if ( $user_id != 0 ){
				$this->wpcasama_account_html($user_id);
			}
			
			
			return $output;
		}
		
		function wpcasama_acount_login(){
			$output = '<p>' . __('You need to be logged to view this content', 'wpcasa-mail-alert') . '</p>';
			
			
			echo $output;
		}
		
		function wpcasama_account_html($user_id){
			$output =  '<p>logged</p>';
			
			
			echo $output;
		}
		
	}