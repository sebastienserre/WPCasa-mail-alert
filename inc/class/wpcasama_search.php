<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


class wpcasama_search {

	public $subscribers = array();

	public function __construct() {
		//	add_action( 'init', array( $this, 'wpcasama_search_alert' ) );
	}


	/**
	 * @return array
	 */

	public function wpcasama_search_alert() {

		/**
		 * Retrieve Alerts
		 */


		$alerts = get_posts( array(
				'post_type'      => 'wpcasa-mail-alerte',
				'posts_per_page' => - 1,
			)
		);

		$i = 0;
		foreach ( $alerts as $alert ) {

			/**
			 * get city location
			 **/

			$city = get_post_meta( $alert->ID, 'wpcasama_city' );
			if ( ! empty( $city ) ) {
				$city = $city[0];
			}

			/**
			 * get min price from Alert
			 */
			$minprices = get_post_meta( $alert->ID, 'wpcasama_min_price' );
			if ( ! empty( $minprices ) ) {
				$minprices = (int) $minprices[0];
			}

			/**
			 * get max price from Alert
			 */
			$maxprices = get_post_meta( $alert->ID, 'wpcasama_max_price' );
			if ( ! empty( $maxprices ) ) {
				$maxprices = (int) $maxprices[0];
			}

			/**
			 * Research property
			 */
			$meta = array(
				'relation' => 'AND',
				array(
					'key'     => '_price',
					'value'   => $maxprices,
					'compare' => '<=',
				),
				array(
					'key'     => '_price',
					'value'   => $minprices,
					'compare' => '>=',
				),

			);

			$tax = array(
				array(
					'taxonomy' => 'location',
					'field'    => 'slug',
					'terms'    => $city,
				),
			);

			/**
			 * Get Emails from alerts in an array
			 **/

			$properties = get_posts(
				array(
					'post_type'  => apply_filters( 'wpcasama/cpt', 'listing' ),
					'meta_query' => $meta,
					'tax_query'  => $tax,
				)
			);

			foreach ( $properties as $property ) {
				$available = get_post_meta( $property->ID, '_listing_not_available' );
				if ( empty( $available ) || '0' === $available[0] ) {
					$this->wpcasama_send_mail( $alert, $property );
				}
			}
			$i ++;
		}
	}

	public function wpcasama_send_mail( $alert, $property ) {
		$recipient = get_the_author_meta( 'user_email', $alert->post_author );
		if ( empty( $recipient ) ) {
			return;
		}
		$user_name   = get_the_author_meta( 'user_nicename', $alert->post_author );
		$sender_mail = get_option( 'thfo_newsletter_sender_mail' );
		if ( empty( $sender_mail ) ) {
			$sender_mail = get_option( 'admin_email' );
		}

		$sender  = get_option( 'thfo_newsletter_sender' );
		$content = '';
		$object  = get_option( 'thfo_newsletter_object' );
		$img     = get_option( 'empathy-setting-logo' );
		$content .= '<img src="' . $img . '" alt="logo" /><br />';
		$content .= get_option( 'thfo_newsletter_content' );
		$content .= '<br /><a href="' . get_permalink( $property->ID ) . '"></a><br />';
		$content .= $property->guid . '<br />';
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
		$this->wpcasama_insert_db( $recipient, $property );
	}

	public function wpcasama_insert_db( $recipient, $property ) {
		if ( ! empty( $recipient ) ) {
			global $wpdb;
			$table_name = $wpdb->prefix . 'wpcasama';
			$data       = array(
				'user_mail' => $recipient,
				'bien'      => $property->ID,
			);
			$wpdb->insert( $table_name, $data );

		}
	}

	public function wpcasama_check_db( $recipient, $property ){
		global $wpdb;
		$table_name = $wpdb->prefix . 'wpcasama';

	}

}
