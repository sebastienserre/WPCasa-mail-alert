<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	} // Exit if accessed directly
	function wpcasama_pro_search( $args ) {
		$meta = get_post_custom( $post_id->ID );
		
		if ( $meta['_details_1'][0] != 0 ) {
			$details_1 = intval($meta['_details_1'][0]);
		} else {
			$details_1 = 0;
		}
		
		if ( $meta['_details_2'][0] != 0 ) {
			$details_2 = intval($meta['_details_2'][0]);
		} else {
			$details_2 = 0;
		}
		
		if ( $meta['_details_3'][0] != 0 ) {
			$details_3 = intval($meta['_details_3'][0]);
		} else {
			$details_3 = 0;
		}
		
		if ( $meta['_details_4'][0] != 0 ) {
			$details_4 = intval($meta['_details_4'][0]);
		} else {
			$details_4 = 0;
		}
		
		if ( $meta['_details_5'][0] != 0 ) {
			$details_5 = intval($meta['_details_5'][0]);
		} else {
			$details_5 = 0;
		}
		
		if ( $meta['_details_6'][0] != 0 ) {
			$details_6 = intval($meta['_details_6'][0]);
		} else {
			$details_6 = 0;
		}
		
		if ( $meta['_details_7'][0] != 0 ) {
			$details_7 = intval($meta['_details_7'][0]);
		} else {
			$details_7 = 0;
		}
		
		if ( $meta['_details_8'][0] != 0 ) {
			$details_8 = intval($meta['_details_8'][0]);
		} else {
			$details_8 = 0;
		}
		
		$meta_pro = array(
			array(
				'relation' => 'OR',
				array(
					'key'     => 'details_1',
					'value'   => $details_1,
					'compare' => '>=',
				),
				array(
					'key'     => 'details_2',
					'value'   => $details_2,
					'compare' => '>=',
				),
				array(
					'key'     => 'details_3',
					'value'   => $details_3,
					'compare' => '>=',
				),
				array(
					'key'     => 'details_4',
					'value'   => $details_4,
					'compare' => '>=',
				),
				array(
					'key'     => 'details_5',
					'value'   => $details_5,
					'compare' => '>=',
				),
				array(
					'key'     => 'details_6',
					'value'   => $details_6,
					'compare' => '>=',
				),
				array(
					'key'     => 'details_7',
					'value'   => $details_7,
					'compare' => '>=',
				),
				array(
					'key'     => 'details_8',
					'value'   => $details_8,
					'compare' => '>=',
				),
			),
		);
		
		$args = array_merge( $args, $meta_pro );
		
		return $args;
	}
	
	add_filter( 'wpcasama/search/subscriber', 'wpcasama_pro_search' );