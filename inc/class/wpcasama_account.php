<?php
	if ( ! defined( 'ABSPATH' ) ){ exit; } // Exit if accessed directly
	
	class wpcasama_account {
		
		private $user_id;
		
		function __construct() {
			add_shortcode('wpcasama_account', array( $this , 'wpcasama_account'));
			
			
			
			
		}
		
		function wpcasama_account(){
			$output = '';
			$this->user_id = get_current_user_id();
			
			if ($this->user_id == 0 ){
				$this->wpcasama_acount_login();
				$output .= wp_login_form();
			}
			
			if ( $this->user_id != 0 ){
				$this->wpcasama_account_html();
			}
			
			
			return $output;
		}
		
		function wpcasama_acount_login(){
			$output = '<p>' . __('You need to be logged to view this content', 'wpcasa-mail-alert') . '</p>';
			
			echo $output;
		}
		
		function wpcasama_account_html(){
			
			$alerts_link = add_query_arg('page', 'alert' );
			$profile_link = add_query_arg('page', 'profile' );
			
			$output =  '<div class="wpcasama-account-main">';
			
			$output .=  '<div class="wpcasama-account-left">';
			$output .= '<p><a href="'. $alerts_link .'">' . __('My alerts', 'wpcasa-mail-alert') . '</a></p>';
			$output .= '<p><a href="'. $profile_link .'">' . __('My Profile', 'wpcasa-mail-alert') . '</a></p>';
			$output .=  '</div><!-- wpcasama-account-left -->';
			
			$output .=  '<div class="wpcasama-account-right">';
			
			if (isset($_GET['page']) && $_GET['page'] == 'alert'){
				$output .=  $this->wpcasama_account_alert();
			}
			if (isset($_GET['page']) && $_GET['page'] == 'profile'){
				$output .=  $this->wpcasama_account_profile();
			}
			
			$output .=  '</div><!-- wpcasama-account-right -->';
			
			$output .=  '</div><!-- wpcasama-account-main -->';
			
			
			echo $output;
		}
		
		function wpcasama_account_alert(){
			$args = array(
				'author'    =>  $this->user_id,
				'post_type' =>  'wpcasa-mail-alerte'
			);
			
			$alerts = get_posts( $args );
			
			foreach ( $alerts as $alert){
				
				$id = $alert->ID;
				$meta = get_post_custom($id);
				
				$alert_main .= '<div class="alert_main">';
				$alert_main .= '<p class="alert_id"><span>' . __('ID:', 'wpcasa-mail-alert') . ' </span>' . $id .'</p>';
				$alert_main .= '<p class="alert_city"><span>'. __('City:', 'wpcasa-mail-alert' ). ' </span>' . $meta['wpcasama_city'][0] .'</p>';
				$alert_main .= '<p class="alert_min"><span>'. __('Minimum Price:', 'wpcasa-mail-alert' ). ' </span>' . $meta['wpcasama_min_price'][0] .'</p>';
				$alert_main .= '<p class="alert_max"><span>'. __('Maximum Price:', 'wpcasa-mail-alert' ). ' </span>' . $meta['maximum_price'][0] .'</p>';
				$alert_main .= '<p class="alert_delete">Delete</p>';
				$alert_main .= '</div><!-- alert_main -->';
				
				
			}
			
			
			return $alert_main;
		}
		
		function wpcasama_account_profile(){
			return 'profile';
		}
		
	}