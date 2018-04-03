<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	} // Exit if accessed directly
	
	class wpcasama_metabox {
		
		function __construct() {
			add_action( 'add_meta_boxes', array( $this, 'wpcasama_add_metabox' ) );
		}
		
		
		function wpcasama_add_metabox() {
			add_meta_box( 'wpcasama_author_alert', __( 'About Author', 'wpcasa-mail-alert' ), array(
				$this,
				'wpcasama_author_metabox'
			), 'wpcasa-mail-alerte', 'side' );
			
			if ( ! defined( 'WPCASAMA_PRO_VERSION' ) ) {
				add_meta_box( 'wpcasama_ads_alert', __( 'Discover Pro Version', 'wpcasa-mail-alert' ), array(
					$this,
					'wpcasama_ads_metabox'
				), 'wpcasa-mail-alerte', 'side' );
			}
		}
		
		function wpcasama_author_metabox() {
			
			$post_author_id   = get_post_field( 'post_author', $post_id );
			$post_author_mail = get_the_author_meta( 'email', $post_author_id );
			$post_author_name = get_the_author_meta( 'display_name', $post_author_id );
			
			echo '<p>' . antispambot($post_author_mail) . '</p>';
			echo '<p>' . $post_author_name . '</p>';
		}
		
		function wpcasama_ads_metabox() {
			
			echo wpcasama_display_ads();
		}
		
	}