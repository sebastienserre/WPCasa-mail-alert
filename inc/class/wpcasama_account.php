<?php
	if ( ! defined( 'ABSPATH' ) ){ exit; } // Exit if accessed directly
	
	class wpcasama_account {
		
		private $user_id;
	
		function __construct() {
			
			
			
			
			add_shortcode('wpcasama_account', array( $this , 'wpcasama_account'));
			
			add_action('init', array($this, 'wpcasama_account_delete_alert'));
			add_action('init', array($this, 'wpcasama_account_update_profile'));
			
			
		}
		
		function wpcasama_account(){
			
			$this->user_id = get_current_user_id();
			
			$output = '';
			
			
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
			$alert_main = '';
			if ( !empty( $alerts) ) {
				foreach ( $alerts as $alert ) {
					
					$id   = $alert->ID;
					$meta = get_post_custom( $id );
					
					$delete_link = add_query_arg( array(
						'action' => 'delete',
						'id'     => $id,
						'nonce'  => wp_create_nonce( 'delete_alert' )
					) );
					
					$alert_main .= '<div class="alert_main">';
					$alert_main .= '<p class="alert_id"><span>' . __( 'ID:', 'wpcasa-mail-alert' ) . ' </span>' . $id . '</p>';
					$alert_main .= '<p class="alert_city"><span>' . __( 'City:', 'wpcasa-mail-alert' ) . ' </span>' . $meta['wpcasama_city'][0] . '</p>';
					$alert_main .= '<p class="alert_min"><span>' . __( 'Minimum Price:', 'wpcasa-mail-alert' ) . ' </span>' . $meta['wpcasama_min_price'][0] . '</p>';
					$alert_main .= '<p class="alert_max"><span>' . __( 'Maximum Price:', 'wpcasa-mail-alert' ) . ' </span>' . $meta['maximum_price'][0] . '</p>';
					$alert_main .= '<p class="alert_delete"><a href="' . $delete_link . '">' . __('Delete', 'wpcasa-mail-alert') . '</a></p>';
					$alert_main .= '</div><!-- alert_main -->';
					
					
				}
			} else {
				$alert_main = '<p>' . __('You do not have any e-mail alert', 'wpcasa-mail-alert') . '</p>';
			}
			
			
			return $alert_main;
		}
		
		function wpcasama_account_profile(){
			
			$user_data = get_userdata($this->user_id);
			
			ob_start(); ?>
			<form method="post">
				<label for="ID"><?php _e('ID:', 'wpcasa-mail-alert') ?></label>
				<input name="ID" type="text" value="<?php if (!empty($user_data)){ echo $user_data->ID; } ?>" readonly>
				
				<label for="last_name"><?php _e('Name:', 'wpcasa-mail-alert') ?></label>
				<input name="last_name" type="text" value="<?php if (!empty($user_data)){ echo $user_data->last_name; } ?>">
				
				<label for="first_name"><?php _e('Firstame:', 'wpcasa-mail-alert') ?></label>
				<input name="first_name" type="text" value="<?php if (!empty($user_data)){ echo $user_data->first_name; } ?>">
				
				<label for="display_name"><?php _e('Display Name:', 'wpcasa-mail-alert') ?></label>
				<input name="display_name" type="text" value="<?php if (!empty($user_data)){ echo $user_data->display_name; } ?>">
				
				<label for="user_email"><?php _e('E-mail:', 'wpcasa-mail-alert') ?></label>
				<input name="user_email" type="text" value="<?php if (!empty($user_data)){ echo $user_data->user_email; } ?>">
				
				<input name="save_profile" type="submit">
			</form>
			
			<?php return ob_get_clean();
		}
		
		function wpcasama_account_update_profile(){
			
			if (isset($_POST['save_profile']) && ! empty($_POST['save_profile'])){
				wp_update_user($_POST);
			}
		}
		
		
		function wpcasama_account_delete_alert(){
			$nonce = $_GET['nonce'];
				if ( isset( $_GET['action'] ) && $_GET['action'] == 'delete' &&  wp_verify_nonce( $nonce, 'delete_alert' ) ) {
					wp_delete_post( $_GET['id'] );
				}
		}
		
	}