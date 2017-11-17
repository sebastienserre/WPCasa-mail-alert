<?php
	defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

	/**
	 * Add options in WPCasa options page
	 * Since 1.2.0
	 */

	add_action('admin_init', 'register_settings');
	add_filter( 'wpsight_options', 'wpcasama_options' );

	function wpcasama_options( $options ) {

		do_action('wpcasama_before_settings');

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

			do_action('wpcasama_after_settings');

		$options['wpcasama'] = array(
			__( 'Mail Alert', 'wpcasa-mail-alert' ),
			apply_filters( 'wpsight_listings_map_options', $options_wpacasama )
		);

		//var_dump($options['wpcasama']); die;
		return $options;
	}



	add_action('admin_menu', 'wpcasama_menu_list', 15);
	function wpcasama_menu_list(){

		add_submenu_page('wpsight-settings', WPSIGHT_NAME . ' ' . __( 'Add-Ons', 'wpcasa' ), __('Mail Alert Listing','wpcasa-mail-alert'), 'manage_options', 'wpcasa-listing', 'wpcasama_menu_html');
		add_submenu_page('wpsight-settings',__('Mail Settings', 'wpcasa-mail-alert'),__('Mail Settings', 'wpcasa-mail-alert'),'manage_options', 'thfo-mailalert-mail-settings','menu_html');

	}

	function wpcasama_menu_html(){
	global $wpdb;
	$subscribers = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wpcasama_mailalert ORDER BY subscription DESC");
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

						$url = add_query_arg(array(
							'remove' => $subscriber->email,
							'_nonce' => wp_create_nonce('_nonce-' . $subscriber->email)
						));
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
                <p><?php _e('... and soon ...', 'wpcasa-mail-alert'); ?></p>
                <li><?php _e('Type of property filter', 'wpcasa-mail-alert'); ?></li>
                <li><?php _e('Easy mail customization', 'wpcasa-mail-alert'); ?></li>
            </ul>
            <a href="<?php echo esc_url('https://www.thivinfo.com/en/downloads/wpcasa-mail-alert-pro/ref/4/');  ?>" title="<?php _e('link to Premium Version', 'wpcasa-mail-alert') ?>" target="_blank"><?php _e('Buy WPCasa MailAlert Pro for only 39.90€', 'wpcasa-mail-alert');  ?></a>
        </div>
        <?php
    }

	function menu_html()
{
	echo '<h1>'.get_admin_page_title().'</h1>'; ?>

    <form method="post" action="options.php">
		<?php settings_fields('thfo_newsletter_settings') ?>
		<?php do_settings_sections('thfo_newsletter_settings') ?>
		<?php submit_button(__('Save')); ?>


    </form>

	<?php
}

	function register_settings()
{
	/* Mail Settings */
	add_settings_section('thfo_newsletter_section', __('Outgoing parameters','wpcasa-mail-alert'), 'section_html', 'thfo_newsletter_settings');

	register_setting('thfo_newsletter_settings', 'thfo_newsletter_sender');
	register_setting('thfo_newsletter_settings', 'thfo_newsletter_sender_mail');
	register_setting('thfo_newsletter_settings', 'thfo_newsletter_object');
	register_setting('thfo_newsletter_settings', 'thfo_newsletter_content');
	register_setting('thfo_newsletter_settings', 'thfo_newsletter_footer');
	register_setting('thfo_newsletter_settings', 'empathy-setting-logo');

	add_settings_field('thfo_newsletter_sender', __('Sender','wpcasa-mail-alert'), 'sender_html', 'thfo_newsletter_settings', 'thfo_newsletter_section');
	add_settings_field('empathy-setting-logo', __('Header picture','wpcasa-mail-alert'), 'media_html', 'thfo_newsletter_settings', 'thfo_newsletter_section');
	add_settings_field('thfo_newsletter_footer', __('footer','wpcasa-mail-alert'), 'footer_html', 'thfo_newsletter_settings', 'thfo_newsletter_section');
	add_settings_field('thfo_newsletter_sender_mail', __('email','wpcasa-mail-alert'), 'sender_mail_html', 'thfo_newsletter_settings', 'thfo_newsletter_section');
	add_settings_field('thfo_newsletter_object', __('Object','wpcasa-mail-alert'), 'object_html', 'thfo_newsletter_settings', 'thfo_newsletter_section');
	add_settings_field('thfo_newsletter_content', __('Content','wpcasa-mail-alert'), 'content_html', 'thfo_newsletter_settings', 'thfo_newsletter_section');

	/**
     * transform old settings to new WPCasa Options
     * @since: 1.2.0
     */

	$prices = get_option('thfo_max_price');
	if ( !empty($prices) ){
		wpsight_add_option('thfo_max_price', $prices);
		delete_option('thfo_max_price');
    }

    $thfo_unsubscribe_page = get_option('thfo_unsubscribe_page');
	if ( !empty( $thfo_unsubscribe_page)){
	    wpsight_add_option('thfo_unsubscribe_page', $thfo_unsubscribe_page);
	    delete_option('thfo_unsubscribe_page');
    }

}
	function media_html(){ ?>
        <input type="text" name="empathy-setting-logo" id="empathy-setting-logo" value="<?php echo  esc_attr(get_option( 'empathy-setting-logo' )) ; ?>">
        <a class="button" onclick="upload_image('empathy-setting-logo');"><?php _e('Upload', 'wpcasa-mail-alert') ?></a>
        <script>
            var uploader;
            function upload_image(id) {
                console.log(id);

                //Extend the wp.media object
                uploader = wp.media.frames.file_frame = wp.media({
                    title: 'Choose Image',
                    button: {
                        text: 'Choose Image'
                    },
                    multiple: false
                });

                //When a file is selected, grab the URL and set it as the text field's value
                uploader.on('select', function() {
                    attachment = uploader.state().get('selection').first().toJSON();
                    var url = attachment['url'];
                    jQuery('#'+id).val(url);
                });

                //Open the uploader dialog
                uploader.open();
            }
        </script>
	<?php }

function section_html()

{

	echo '<p>'.__('Advise about outgoing parameters.','wpcasa-mail-alert').'</p>';

}

function footer_html(){
	?>
    <textarea name="thfo_newsletter_footer"><?php echo get_option('thfo_newsletter_footer')?></textarea>

	<?php
}

function sender_html()
{?>
    <input type="text" name="thfo_newsletter_sender" value="<?php echo get_option('thfo_newsletter_sender')?>"/>
	<?php
}

function sender_mail_html()
{?>
    <input type="email" name="thfo_newsletter_sender_mail" value="<?php echo get_option('thfo_newsletter_sender_mail')?>"/>
	<?php
}

function object_html()

{?>

    <input type="text" name="thfo_newsletter_object" value="<?php echo get_option('thfo_newsletter_object')?>"/>
	<?php


}

function content_html()

{
	wp_editor(get_option('thfo_newsletter_content'),'thfo_newsletter_content' );
}