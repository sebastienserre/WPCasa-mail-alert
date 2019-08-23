<?php
	if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @link https://gist.github.com/nciske/6002909
 * Wrap the_widget()
 */
if( !function_exists('get_the_widget') ){

	function get_the_widget( $widget, $instance = '', $args = '' ){
		ob_start();
		the_widget($widget, $instance, $args);
		return ob_get_clean();
	}

}

add_shortcode('wpcasa-mail-alert', 'wpcasama_shortcode');

function wpcasama_shortcode($atts){

	$atts = shortcode_atts( array(
		'title' => __('email alert', 'wpcasa-mail-alert'),
	), $atts );


	//$instance['title'] = $atts['title'];

	if (class_exists('thfo_mailalert_widget')){
		return get_the_widget('thfo_mailalert_widget', $atts);
	}
}