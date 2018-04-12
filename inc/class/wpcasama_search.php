<?php
	if ( ! defined( 'ABSPATH' ) ){ exit; } // Exit if accessed directly
	
	
	class wpcasama_search {
		
		function __construct() {
			add_action( 'post_submitbox_misc_actions', array( $this, 'wpcasama_search_alert' ) );
		}
		
		
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
			 * $args = array(
			'post_type'  => 'wpse_cpt',
			'meta_query' => array(
			'relation' => 'AND' //**** Use AND or OR as per your required Where Clause
			array(
			'key'     => 'post_code',
			'value'   => '432C',
			),
			array(
			'key'     => 'location',
			'value'   => 'XYZ',
			),
			),
			);
			$query = new WP_Query( $args );
			 */
			
			var_dump($alerts);
		}
	}