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
			
			if ( ! defined( 'WPCASAMA_PRO_VERSION' ) ) {
				add_meta_box( 'wpcasama_ads_alert', __( 'Discover Pro Version', 'wpcasa-mail-alert' ), array(
					$this,
					'wpcasama_ads_metabox',
				), 'wpcasa-mail-alerte', 'side' );
			}
		}
		
		function wpcasama_author_metabox( $post_id ) {
			
			$post_author_id   = get_post_field( 'post_author', $post_id );
			$post_author_mail = get_the_author_meta( 'email', $post_author_id );
			$post_author_name = get_the_author_meta( 'display_name', $post_author_id );
			
			echo '<p>' . antispambot( $post_author_mail ) . '</p>';
			echo '<p><a href="' . admin_url( 'user-edit.php?user_id=' . $post_author_id ) . '" >' . $post_author_name . '</a></p>';
		}
		
		function wpcasama_ads_metabox() {
			
			echo wpcasama_display_ads();
		}
		
		
		function wpcasama_alert_data( $post_id ) {
			
			
			$meta = get_post_custom( $post_id->ID );
			
			do_action( 'wpcasama/before/alert/data' );
			
			?>
            <div class="wpcasama_search_criteria_left">
                <div class="wpcasama_search_criteria wpcasama_phone">
                    <p><?php _e( 'Phone number: ', 'wpcasa-mail-alert' ) ?><?php echo $meta['wpcasama_phone'][0]; ?></p>
                    <div class="clear"></div>
                </div>
                <div class="wpcasama_search_criteria wpcasama_city">
                    <p><?php _e( 'City: ', 'wpcasa-mail-alert' ) ?><?php echo $meta['wpcasama_city'][0]; ?></p>
                    <div class="clear"></div>
                </div>
                <div class="wpcasama_search_criteria wpcasama_min_price">
                    <p><?php _e( 'Minimum Price: ', 'wpcasa-mail-alert' ) ?><?php echo $meta['wpcasama_min_price'][0]; ?></p>
                    <div class="clear"></div>
                </div>
                <div class="wpcasama_search_criteria wpcasama_max_price">
                    <p><?php _e( 'Maximum Price: ', 'wpcasa-mail-alert' ) ?><?php echo $meta['wpcasama_max_price'][0]; ?></p>

                </div>
            </div>
			
			<?php if ( defined( 'WPCASAMA_PRO_VERSION' ) ) {
			    do_action('wpcasama/pro/criteria');
            } else { ?>
                <div class="wpcasama_search_criteria_right wpcasama_search_criteria_free">
                    <fieldset>
                        <legend>Pro Features</legend>
                        <p><?php printf(__('Allow Search on all <a href="%s"> WPCasa standard features</a>', 'wpcasa-mail-alert' ), admin_url('admin.php?page=wpsight-settings')) ?></p>
                        
                        <div class="wpcasama_search_criteria wpcasama_details_1">
                            <p><?php _e( 'details_1: ', 'wpcasa-mail-alert' ) ?><?php _e( 'details_1', 'wpcasa-mail-alert' ) ?></p>
                            <div class="clear"></div>
                        </div>
                        <div class="wpcasama_search_criteria wpcasama_details_2">
                            <p><?php _e( 'details_2: ', 'wpcasa-mail-alert' ) ?><?php _e( 'details_1', 'wpcasa-mail-alert' ) ?></p>
                        </div>
                        <div class="wpcasama_search_criteria wpcasama_details_3">
                            <p><?php _e( 'details_3: ', 'wpcasa-mail-alert' ) ?><?php _e( 'details_1', 'wpcasa-mail-alert' ) ?></p>
                        </div>
                        <div class="wpcasama_search_criteria wpcasama_details_4">
                            <p><?php _e( 'details_4: ', 'wpcasa-mail-alert' ) ?><?php _e( 'details_1', 'wpcasa-mail-alert' ) ?></p>
                        </div>
                        <div class="wpcasama_search_criteria wpcasama_details_5">
                            <p><?php _e( 'details_5: ', 'wpcasa-mail-alert' ) ?><?php _e( 'details_1', 'wpcasa-mail-alert' ) ?></p>
                        </div>
                        <div class="wpcasama_search_criteria wpcasama_details_6">
                            <p><?php _e( 'details_6: ', 'wpcasa-mail-alert' ) ?><?php _e( 'details_1', 'wpcasa-mail-alert' ) ?></p>
                        </div>
                        <div class="wpcasama_search_criteria wpcasama_details_7">
                            <p><?php _e( 'details_7: ', 'wpcasa-mail-alert' ) ?><?php _e( 'details_1', 'wpcasa-mail-alert' ) ?></p>
                        </div>
                        <div class="wpcasama_search_criteria wpcasama_details_8">
                            <p><?php _e( 'details_8: ', 'wpcasa-mail-alert' ) ?><?php _e( 'details_1', 'wpcasa-mail-alert' ) ?></p>
                        </div>
                    </fieldset>

                </div>
				
				<?php do_action( 'wpcasama/after/alert/data' );
				
			}
		}
	}