<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
function wpcasama_pro_search( $args, $alert ) {
	$meta     = get_post_custom( $alert->ID );
	$i        = 1;
	$meta_pro = array();
	while ( $i <= 8 ) {
		if ( ! empty( $meta[ 'details_' . $i ][0] ) ) {
			$details = intval( $meta[ 'details_' . $i ][0] );

			$details = array(
				'key'     => 'details_' . $i,
				'value'   => $details,
				'compare' => '>=',
			);
			array_push( $meta_pro, $details );
		}
		$i ++;
	}

	$args = array_merge( $args, $meta_pro );

	return $args;
}

add_filter( 'wpcasama/search/subscriber', 'wpcasama_pro_search', 10, 2 );
