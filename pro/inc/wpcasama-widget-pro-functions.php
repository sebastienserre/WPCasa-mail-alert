<?php
	
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}
	
	add_action( 'wpcasama_end_widget', 'wpcasama_pro_add_filters' );
	function wpcasama_pro_add_filters() {
		
		$details = wpsight_details();
		
		foreach ( $details as $key => $detail ) {
			$key = wpsight_get_option( 'wpcasama_pro_' . $detail['label'] );
			if ( ! empty( $detail['unit'] ) ) {
				$unit = ' (' . $detail['unit'] . ')';
			} else {
				$unit = '';
			}
			
			if ( $key == "1" ) { ?>
                <div class="wpcasama-widget-field">
                    <label for="thfo_mailalert_<?php echo $detail['id']; ?>"> <?php echo $detail['label'] . $unit ?></label>
                    <input id="thfo_mailalert_<?php echo $detail['id']; ?>"
                           name="thfo_mailalert_<?php echo $detail['id']; ?>" type="number" step="1" value="0" min="0"/>
                </div>
			<?php }
			
		}
		$offer = wpsight_get_option( 'wpcasama_pro_type' );
		
		if ( $offer == '1' ) {
			add_action( 'wpcasama_end_widget', 'wpcasama_add_filter_offer' );
		}
	}
	
	function wpcasama_add_filter_offer() {
		?>
        <div class="wpcasama-widget-field">
            <label for="thfo_mailalert_offer"> <?php _e( 'Offer Type', 'wpcasa-mail-alert-pro' ) ?></label>
            <select name="thfo_mailalert_offer">
                <option name="thfo_mailalert_offer"
                        value="sale"><?php _e( 'For Sale', 'wpcasa-mail-alert-pro' ) ?></option>
                <option name="thfo_mailalert_offer"
                        value="rent"><?php _e( 'For Rent', 'wpcasa-mail-alert-pro' ) ?></option>
            </select>
        </div>
		<?php
	}
	
	add_filter( 'wpcasama/pro/save/meta', 'wpcasama_save' );
	function wpcasama_save( $params ) {
		
		$details1 = sanitize_text_field( $_POST['thfo_mailalert_details_1'] );
		$details2 = sanitize_text_field( $_POST['thfo_mailalert_details_2'] );
		$details3 = sanitize_text_field( $_POST['thfo_mailalert_details_3'] );
		$details4 = sanitize_text_field( $_POST['thfo_mailalert_details_4'] );
		$details5 = sanitize_text_field( $_POST['thfo_mailalert_details_5'] );
		$details6 = sanitize_text_field( $_POST['thfo_mailalert_details_6'] );
		$details7 = sanitize_text_field( $_POST['thfo_mailalert_details_7'] );
		$details8 = sanitize_text_field( $_POST['thfo_mailalert_details_8'] );
		
		$pro_meta = array(
			'details_1' => $details1,
			'details_2' => $details2,
			'details_3' => $details3,
			'details_4' => $details4,
			'details_5' => $details5,
			'details_6' => $details6,
			'details_7' => $details7,
			'details_8' => $details8,
		);
		$params   = array_merge( $params, $pro_meta );
		
		
		return $params;
		
	}
	
	function wpcasama_pro_metabox_alert() {
		$details = wpsight_details();
		$meta    = get_post_custom( $post_id->ID );
		
		
		?>
        <div class="wpcasama_search_criteria_right">
			<?php foreach ( $details as $detail ) { ?>
				<?php
				
				$key = array_key_exists( $detail['id'], $meta );
				if ( $key ) { ?>

                    <div class="wpcasama_search_criteria wpcasama_<?php $detail['id'] ?>">
                        <p><?php echo $detail['label'] ?>: <?php echo $meta[ $detail['id'] ][0];
								if ( ! empty( $detail['unit'] ) ) {
									echo $detail['unit'];
								} ?> </p>
                        <div class="clear"></div>
                    </div>
				
				<?php }
			} ?>

        </div>
		<?php
	}
	
	add_action( 'wpcasama/pro/criteria', 'wpcasama_pro_metabox_alert' );