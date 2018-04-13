<?php
	if ( ! defined( 'ABSPATH' ) ){ exit; } // Exit if accessed directly
	
	
	class wpcasama_search {
		
		public $subscribers = array();
		
		function __construct() {
			//add_action( 'post_submitbox_misc_actions', array( $this, 'wpcasama_search_alert' ) );
		}
		
		
		/**
		 * @return array
		 */
		
		function wpcasama_search_alert(){
			
			/**
			 * get city location
			 **/
			global $post;
			
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
			
			
			$alerts = get_posts( array(
			'post_type' =>  'wpcasa-mail-alerte',
				'meta_query'    => array(
					'relation'  =>  'AND',
					array(
						'key'   =>  'wpcasama_city',
						'value'    =>  $city,
					),
					array(
						'key'   =>  'wpcasama_min_price',
						'value' =>  $price,
						'compare'   =>  '<='
					),
					array(
						'key'   =>  'wpcasama_max_price',
						'value' =>  $price,
						'compare'   =>  '>='
					)
				)
			) );
			
			/**
			 * Get Emails from alerts in an array
			 */
			
			foreach ($alerts as $alert){
				
				array_push($this->subscribers, get_userdata($alert->post_author));
			}
			
			return $this->subscribers;
		}

	}