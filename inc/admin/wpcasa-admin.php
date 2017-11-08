<?php
	defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );


	/**
	 * Add options in WPCasa options page
	 * Since 1.2.0
	 */
	add_filter( 'wpsight_options', 'wpcasama_options' );

	function wpcasama_options( $options ) {

			$options_wpacasama = array(

				'thfo_unsubscribe_page' => array(
					'name' => __( 'Unsubscribe Page', 'wpcasa-mail-alert' ),
					'desc' => __( 'Please select the page that use the [thfo_mailalert_unsubscribe] shortcode on the selected page.', 'wpcasa-listings-map' ),
					'id'   => 'thfo_unsubscribe_page',
					'type' => 'pages'
				),

				'thfo_max_price' => array(
					'name'    => __( 'Maximum Price', 'wpcasa-mail-alert' ),
					'desc'    => __( 'Please enter maximum price separated by a comma', 'wpcasa-mail-alert' ),
					'id'      => 'thfo_max_price',
					'type'    => 'text',
					'default' => __( 'Toggle Map', 'wpcasa-listings-map' ),
				),
			);



		$options['wpcasama'] = array(
			__( 'Mail Alert', 'wpcasa-mail-alert' ),
			apply_filters( 'wpsight_listings_map_options', $options_wpacasama )
		);

		return $options;
	}



	add_action('admin_menu', 'wpcasama_menu_list', 15);
	function wpcasama_menu_list(){

		add_submenu_page('wpsight-settings', WPSIGHT_NAME . ' ' . __( 'Add-Ons', 'wpcasa' ), __('Mail Alert Listing','wpcasa-mail-alert'), 'manage_options', 'wpcasa-listing', 'wpcasama_menu_html');

	}

	function wpcasama_menu_html(){
	global $wpdb;
	$subscribers = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpcasama_mailalert ");
	$count = count($subscribers);

	if (defined('WPCASAMA_PRO_VERSION')){
		echo '<h1>' . get_admin_page_title() . ' ' . WPCASAMA_PRO_VERSION ;
		echo ' Pro</h1>';
	} else {
		echo '<h1>' . get_admin_page_title() . ' ' . PLUGIN_VERSION;
		echo '</h1>';
	}

	echo '<p>';
	if ( $count === 0 ){
		_e('0 subscriber' , 'wpcasa-mail-alert');
	} else {
		printf( _n( '%s subscriber:', '%s subscribers:', $count, 'wpcasa-mail-alert' ), number_format_i18n( $count ) );
	}

	//printf( _n( '%s subscriber:', '%s subscribers:', $count, 'wpcasa-mail-alert' ), number_format_i18n( $count ) );

	echo '</p>';

?>

<table class="thfo_subscriber" >
	<tr>
		<th><?php _e('Date', 'wpcasa-mail-alert') ?></th>
		<th><?php _e('Name', 'wpcasa-mail-alert') ?></th>
		<th><?php _e('Email', 'wpcasa-mail-alert') ?></th>
		<th><?php _e('Phone', 'wpcasa-mail-alert') ?></th>
		<th><?php _e('City Searched', 'wpcasa-mail-alert') ?></th>
		<th><?php _e('Minimum price', 'wpcasa-mail-alert') ?></th>
		<th><?php _e('Maximum price', 'wpcasa-mail-alert') ?></th>
		<th><?php _e('Room', 'wpcasa-mail-alert') ?></th>
		<?php do_action('thfo_after_header_subscriber_table', 10,1); ?>
		<th><?php _e('Delete', 'wpcasa-mail-alert') ?></th>

	</tr>
	<?php
		foreach ($subscribers as $subscriber){
			$id = $subscriber->id;
			$date = mysql2date('G', $subscriber->subscription, true) ?>
			<tr>
				<td><?php echo date_i18n('d/m/Y', $date ); ?></td>
				<td><?php echo $subscriber->name ?></td>
				<td><?php echo $subscriber->email ?></td>
				<td><?php echo $subscriber->tel ?></td>
				<td><?php echo $subscriber->city ?></td>
				<td><?php echo $subscriber->min_price ?>€</td>
				<td><?php echo $subscriber->max_price ?>€</td>
				<td><?php echo $subscriber->room ?></td>
				<?php do_action('thfo_after_tr_subscriber_table'); ?>

				<td>
					<?php

						$url = admin_url( 'admin.php?page=' );
						$url .= basename(dirname( __DIR__));
						$url .= '&id='. $id .'&delete=yes';
					?>
					<a href="<?php echo esc_url($url); ?>" title="<?php _e('Delete', 'wpcasa-mail-alert') ?>"><span class="dashicons dashicons-trash"></span> </a>
				</td>

			</tr>

		<?php }

	?>
</table>
		<div class="ads">
            <?php wpcasama_display_ads(); ?>
		</div>
        <div class="clear"></div>
        <div class="wpcasama-stars">
        <span id="wpcasama-footer-credits">
                <span class="dashicons dashicons-wordpress"></span>
                <?php _e( "You like WPCasa Mail Alert? Don't forget to rate it 5 stars!", "wpcasa-mail-alert" ) ?>

                <span class="wporg-ratings rating-stars">
                    <a href="//wordpress.org/support/view/plugin-reviews/wpcasa-mail-alert?rate=1#postform" data-rating="1" title="" target="_blank"><span class="dashicons dashicons-star-filled" style="color:#FFDE24 !important;"></span></a>
                    <a href="//wordpress.org/support/view/plugin-reviews/wpcasa-mail-alert?rate=2#postform" data-rating="2" title="" target="_blank"><span class="dashicons dashicons-star-filled" style="color:#FFDE24 !important;"></span></a>
                    <a href="//wordpress.org/support/view/plugin-reviews/wpcasa-mail-alert?rate=3#postform" data-rating="3" title="" target="_blank"><span class="dashicons dashicons-star-filled" style="color:#FFDE24 !important;"></span></a>
                    <a href="//wordpress.org/support/view/plugin-reviews/wpcasa-mail-alert?rate=4#postform" data-rating="4" title="" target="_blank"><span class="dashicons dashicons-star-filled" style="color:#FFDE24 !important;"></span></a>
                    <a href="//wordpress.org/support/view/plugin-reviews/wpcasa-mail-alert?rate=5#postform" data-rating="5" title="" target="_blank"><span class="dashicons dashicons-star-filled" style="color:#FFDE24 !important;"></span></a>
                </span>
                <script>
                    jQuery(document).ready( function($) {
                        $(".rating-stars").find("a").hover(
                            function() {
                                $(this).nextAll("a").children("span").removeClass("dashicons-star-filled").addClass("dashicons-star-empty");
                                $(this).prevAll("a").children("span").removeClass("dashicons-star-empty").addClass("dashicons-star-filled");
                                $(this).children("span").removeClass("dashicons-star-empty").addClass("dashicons-star-filled");
                            }, function() {
                                var rating = $("input#rating").val();
                                if (rating) {
                                    var list = $(".rating-stars a");
                                    list.children("span").removeClass("dashicons-star-filled").addClass("dashicons-star-empty");
                                    list.slice(0, rating).children("span").removeClass("dashicons-star-empty").addClass("dashicons-star-filled");
                                }
                            }
                        );
                    });
                </script>
            </span>
        </div>
<?php

	}


	function wpcasama_display_ads(){
	    ?>
        <div class="premium-ads">
            <h4>WPCasa Mail Alert Pro</h4>
            <ul>
                <li><?php _e('Offer filter: sale or rent', 'wpcasa-mail-alert'); ?></li>
                <li><?php _e('Number of Bath Filter', 'wpcasa-mail-alert'); ?></li>
                <li><?php _e('Shortcode to display the form where ever you want', 'wpcasa-mail-alert'); ?></li>
                <li><?php _e('Automatic Update', 'wpcasa-mail-alert'); ?></li>
                <li><?php _e('Priority support', 'wpcasa-mail-alert'); ?></li>
            </ul>
            <a href="<?php echo esc_url('https://www.thivinfo.com/en/downloads/wpcasa-mail-alert-pro/ref/4/');  ?>" title="<?php _e('link to Premium Version', 'wpcasa-mail-alert') ?>" target="_blank"><?php _e('Buy WPCasa MailAlert Pro', 'wpcasa-mail-alert');  ?></a>
        </div>
        <?php
    }

