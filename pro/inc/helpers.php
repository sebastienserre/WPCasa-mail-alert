<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.
//die('pro');
add_action( 'wpcasama_add_tags', 'wpcasama_pro_add_tags', 10, 2 );
function wpcasama_pro_add_tags( $main_content, $alert ) {
	$firstname = get_the_author_meta( 'first_name', $alert->post_author );
	$lastname  = get_the_author_meta( 'last_name', $alert->post_author );
	$details_1 = get_post_meta( $id, '_details_1' );
	$details_2 = get_post_meta( $id, '_details_2' );
	$details_3 = get_post_meta( $id, '_details_3' );
	$details_4 = get_post_meta( $id, '_details_4' );
	$details_5 = get_post_meta( $id, '_details_5' );
	$details_6 = get_post_meta( $id, '_details_6' );
	$details_7 = get_post_meta( $id, '_details_7' );
	$details_8 = get_post_meta( $id, '_details_8' );


	$main_content = str_replace( '{firstname}', $firstname, $main_content );
	$main_content = str_replace( '{lastname}', $lastname, $main_content );
	$main_content = str_replace( '{detail_1}', $details_1, $main_content );
	$main_content = str_replace( '{detail_2}', $details_2, $main_content );
	$main_content = str_replace( '{detail_3}', $details_3, $main_content );
	$main_content = str_replace( '{detail_4}', $details_4, $main_content );
	$main_content = str_replace( '{detail_5}', $details_5, $main_content );
	$main_content = str_replace( '{detail_6}', $details_6, $main_content );
	$main_content = str_replace( '{detail_7}', $details_7, $main_content );
	$main_content = str_replace( '{detail_8}', $details_8, $main_content );

	return $main_content;
}
