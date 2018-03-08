<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


add_action( 'post_submitbox_misc_actions', 'thfo_search_subscriber' );

	function thfo_search_subscriber() {
		global $post;

		if ( $post->post_type === 'listing' ) {
			global $wpdb;
			/**
			 * get city location
			 **/

			$terms = wp_get_object_terms( $post->ID, 'location' );
			if ( ! empty( $terms ) ) {
				$city = $terms[0]->name;
			}

			/**
			 * get price from property
			 */
			$prices = get_post_meta( $post->ID, '_price' );
			if ( ! empty( $prices ) ) {
				$price = (int) $prices[0];
			}
			
			/**
			 * get subcriber list for this city
			 */

			if ( ! empty( $city ) ) {
				$subscribers = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpcasama_mailalert WHERE city = '$city' " );

				/**
				 * @since 1.4.0
				 * Fires after selecting subscribers
				 */
				$subscribers = apply_filters( 'thfo-get-subscriber-list', $subscribers );

				/**
				 * Search is running!
				 */

				/**
				 * Fires before searching subscribers
				 * @since 1.4.0
				 */
				do_action( 'thfo_before_search' );



				if ( ! defined('WPCASAMA_PRO_VERSION')) {
					$mails = array();
					foreach ( $subscribers as $subscriber ) {

						if ( $price <= $subscriber->max_price && $price >= $subscriber->min_price ) {

							

								array_push($mails ,$subscriber->email);
								
							
						}
					}
				} else {
					wpcasama_pro_search($subscribers, $price);
				}
			}
		}

		if ( ! empty( $mails ) ) {
			thfo_send_mail( $mails );
		}


	}

	function thfo_send_mail( $mails ) {
		global $post;
		foreach ( $mails as $mail ) {
			if ( ! is_email( $mail ) ) {
				return false;
			} else {
				$recipient = $mail;

				$sender_mail = get_option( 'thfo_newsletter_sender_mail' );
				if ( empty( $sender_mail ) ) {
					$sender_mail = get_option( 'admin_email' );
				}

				$sender  = get_option( 'thfo_newsletter_sender' );
				$content = "";
				$object  = get_option( 'thfo_newsletter_object' );
				$img     = get_option( 'empathy-setting-logo' );

				$content .= '<img src="' .$img. '" alt="logo" /><br />';
				//var_dump($content); die;
				$content .= get_option( 'thfo_newsletter_content' );
				$content .= '<br /><a href="' . get_permalink() . '"></a><br />';
				$content .= $post->guid . "<br />";

				$content .= '<p>' . __( 'To unsubscribe to this mail please follow this link: ', 'wpcasa-mail-alert' );

				$url     = get_option( 'thfo_unsubscribe_page' );
				$content .= '<a href="' . esc_url( add_query_arg( array(
						'remove' => $recipient,
						'nonce'  => wp_create_nonce('nonce'),
					), home_url( $url ) ) ) . '">'.  __( 'Here', 'wpcasa-mail-alert' ) .'</a><p>';

				$content .= get_option( 'thfo_newsletter_footer' );


				$headers[] = 'Content-Type: text/html; charset=UTF-8';

				$headers[] = 'From:' . $sender . '<' . $sender_mail . '>';

				/**
				 * @since 1.4.0
				 * Fires before sending mail
				 */

				do_action( 'thfo_before_sending_mail' );
				remove_filter( 'wp_mail_content_type', 'set_html_content_type' );

				$result = wp_mail( $recipient, $object, $content, $headers );

			}
		};

		/**
		 * @since 1.4.0
		 * Fires immediatly after sending mail
		 */

		do_action( 'thfo_after_sending_mail' );



	}