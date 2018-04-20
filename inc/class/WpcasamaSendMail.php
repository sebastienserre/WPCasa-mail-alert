<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	} // Exit if accessed directly
	
	
	class WpcasamaSendMail {
		
		function __construct() {
		
				add_action( 'save_post', array( $this, 'wpcasama_send_mail' ), 100 );
				add_action( 'wp_insert_post', array( $this, 'wpcasama_send_mail' ), 100 );
		}
		
		function wpcasama_send_mail() {
			
			global $post;
			
			if ( $post->post_type == 'listing' ) {
				
				$subscribers = new wpcasama_search();
				$subscribers = $subscribers->wpcasama_search_alert();
				
				foreach ( $subscribers as $subscriber ) {
					if ( ! is_email( $subscriber->data->user_email ) ) {
						
						return false;
					} else {
						
						$mail      = $subscriber->data->user_email;
						$recipient = $mail;
						
						$sender_mail = get_option( 'thfo_newsletter_sender_mail' );
						if ( empty( $sender_mail ) ) {
							$sender_mail = get_option( 'admin_email' );
						}
						
						$sender  = get_option( 'thfo_newsletter_sender' );
						$content = "";
						$object  = get_option( 'thfo_newsletter_object' );
						$img     = get_option( 'empathy-setting-logo' );
						
						$content .= '<img src="' . $img . '" alt="logo" /><br />';
						$content .= get_option( 'thfo_newsletter_content' );
						$content .= '<br /><a href="' . get_permalink() . '"></a><br />';
						$content .= $post->guid . "<br />";
						
						$content .= '<p>' . __( 'To unsubscribe to this mail please follow this link: ', 'wpcasa-mail-alert' );
						
						$url     = get_option( 'thfo_unsubscribe_page' );
						$content .= '<a href="' . esc_url( add_query_arg( array(
								'remove' => $recipient,
								'nonce'  => wp_create_nonce( 'nonce' ),
							), home_url( $url ) ) ) . '">' . __( 'Here', 'wpcasa-mail-alert' ) . '</a><p>';
						
						$content .= get_option( 'thfo_newsletter_footer' );
						
						
						$headers[] = 'Content-Type: text/html; charset=UTF-8';
						
						$headers[] = 'From:' . $sender . '<' . $sender_mail . '>';
						
						/**
						 * @since 1.4.0
						 * Fires before sending mail
						 */
						
						do_action( 'thfo_before_sending_mail' );
						remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
						
						wp_mail( $recipient, $object, $content, $headers );
					}
				};
				
				/**
				 * @since 1.4.0
				 * Fires immediatly after sending mail
				 */
				
				do_action( 'thfo_after_sending_mail' );
				
				
			}
		}
		
	}