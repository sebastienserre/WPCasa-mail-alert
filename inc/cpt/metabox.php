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
			'wpcasama_author_metabox',
		), 'wpcasa-mail-alerte', 'side' );

		add_meta_box( 'wpcasama_alert_data', __( 'Alert Details', 'wpcasa-mail-alert' ), array(
			$this,
			'wpcasama_alert_data',
		), 'wpcasa-mail-alerte', 'normal' );

	}

	function wpcasama_author_metabox( $post_id ) {

		$post_author_id    = get_post_field( 'post_author', $post_id );
		$post_author_mail  = get_the_author_meta( 'email', $post_author_id );
		$post_author_name  = get_the_author_meta( 'display_name', $post_author_id );
		$post_author_phone = get_the_author_meta( 'wpcasama_phone', $post_author_id );

		echo '<p>' . antispambot( $post_author_mail ) . '</p>';
		echo '<p><a href="' . admin_url( 'user-edit.php?user_id=' . $post_author_id ) . '" >' . $post_author_name . '</a></p>';
		if ( ! empty ( $post_author_phone ) ) {
			echo '<p>' . __( 'Phone Number: ' ) . $post_author_phone;
		}
	}

	function wpcasama_alert_data( $post_id ) {

		$currency = wpsight_get_currency();
		$meta     = get_post_custom( $post_id->ID );

		do_action( 'wpcasama/before/alert/data' );

		?>
        <div class="wpcasama_search_criteria_left">

            <div class="wpcasama_search_criteria wpcasama_city">
                <p><?php _e( 'City: ', 'wpcasa-mail-alert' ) ?><?php echo $meta['wpcasama_city'][0]; ?></p>
                <div class="clear"></div>
            </div>
            <div class="wpcasama_search_criteria wpcasama_min_price">
                <p><?php _e( 'Minimum Price: ', 'wpcasa-mail-alert' ) ?><?php echo $meta['wpcasama_min_price'][0] . $currency; ?></p>
                <div class="clear"></div>
            </div>
            <div class="wpcasama_search_criteria wpcasama_max_price">
                <p><?php _e( 'Maximum Price: ', 'wpcasa-mail-alert' ) ?><?php echo $meta['wpcasama_max_price'][0] . $currency; ?></p>

            </div>
        </div>

		<?php
		do_action( 'wpcasama/pro/criteria' );

		$details = wpsight_details();

		?>
        <div class="wpcasama_search_criteria_right wpcasama_search_criteria_free">
            <fieldset>
                <legend>Pro Features</legend>
                <p><?php printf( __( '<a href="%s">With the Pro version, you could also allowing search on :</a>', 'wpcasa-mail-alert' ), WPCASAMAPRO_LINK ) ?></p>

				<?php foreach ( $details as $detail ) { ?>

                    <div class="wpcasama_search_criteria wpcasama_<?php $detail['id'] ?>">
                        <p><?php echo $detail['label'] ?>: <?php echo rand( 0, 500 );
							if ( ! empty( $detail['unit'] ) ) {
								echo $detail['unit'];
							} ?> </p>
                        <div class="clear"></div>
                    </div>

				<?php } ?>

            </fieldset>

        </div>


		<?php

		/**
		 * @since 2.0.0
		 */
		do_action( 'wpcasama/after/alert/data' );

	}
}
