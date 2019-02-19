<?php

namespace WPCASAMA\BLOCKS\FORM;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

use Carbon_Fields\Field;
use Carbon_Fields\Block;


class BlocksForm {

	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'basic_block' ), 500 );
	}

	public function basic_block(){
		Block::make( __( 'WPCasa Mail Alert Block', 'wpcasa-mail-alert' ) )
		     ->add_fields( array(
				     Field::make( 'text', 'title', __( 'Block Title', 'wpcasa-mail-alert' ) ),

			     )
		     )
		     ->set_description( __( 'WPCasa Mail Alert Block', 'wpcasa-mail-alert' ) )
		     ->set_category( 'custom-category', 'WPCasa mail Alert', 'email-alt' )
		     ->set_render_callback( array( $this, 'render' ) );


	}

	public function render( $block ){
		if (class_exists('thfo_mailalert_widget')){
			echo get_the_widget('thfo_mailalert_widget', $block);
		}
	}
}
new BlocksForm();
