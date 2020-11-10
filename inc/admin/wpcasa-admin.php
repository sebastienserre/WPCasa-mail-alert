<?php
defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

/**
 * Add options in WPCasa options page
 * Since 1.2.0
 */

add_action( 'admin_init', 'register_settings' );
add_filter( 'wpsight_options', 'wpcasama_options' );

function wpcasama_options( $options ) {

	$options_wpacasama = array(

		'thfo_unsubscribe_page' => array(
			'name' => __( 'Unsubscribe Page', 'wpcasa-mail-alert' ),
			'desc' => sprintf( __( 'Please select the page that uses %s on the selected page.', 'wpcasa-mail-alert' ), '[thfo_mailalert_unsubscribe]' ),
			'id'   => 'thfo_unsubscribe_page',
			'type' => 'pages',
		),

		'thfo_max_price'          => array(
			'name'    => __( 'Maximum Price', 'wpcasa-mail-alert' ),
			'desc'    => __( 'Please enter maximum price separated by a comma', 'wpcasa-mail-alert' ),
			'id'      => 'thfo_max_price',
			'type'    => 'text',
			'default' => __( '', 'wpcasa-listings-map' ),
		),
		'wpcasama_migration_tool' => array(
			'name' => __( 'Migrate from version < 2.0 ', 'wpcasa-mail-alert' ),
			'desc' => __( 'Needed to migrate your existing alerts if you use WPCasa mail Alert before it\'s 2.0.0 version', 'wpcasa-mail-alert' ),
			'id'   => 'wpcasama_migration',
			'type' => 'checkbox',
		),
		'wpcasama_old_framework'  => array(
			'name' => __( 'Are you using the old WPCasa Theme Framework', 'wpcasa-mail-alert' ),
			'desc' => __( 'In old theme version (before WPCasa become a plugin), some settings were diferent. This tool will match the old settings', 'wpcasa-mail-alert' ),
			'id'   => 'wpcasama_framework',
			'type' => 'checkbox',
		),


	);


	$options['wpcasama'] = array(
		__( 'WPCasa Mail Alert', 'wpcasa-mail-alert' ),
		apply_filters( 'wpsight_listings_map_options', $options_wpacasama ),
	);

	return $options;
}

add_action( 'admin_init', 'wpcasama_old_framework' );
/**
 * @since 3.0.1
 */
function wpcasama_old_framework() {
	$options = wpsight_get_option( 'wpcasama_framework' );
	if ( '1' === $options ) {
		add_filter( 'wpcasama/cpt', 'wpcasama_change_postype' );
	}
}

/**
 * @param $cpt string Custom Post Type used by old WPCASA Framework.
 *
 * @return string
 *
 * @since 3.0.1
 */
function wpcasama_change_postype( $cpt ) {
	return 'property';
}

add_action( 'admin_menu', 'wpcasama_menu_list', 15 );
function wpcasama_menu_list() {

	add_submenu_page( 'wpsight-settings', 'WPCasa Mail Alert', __( 'Mail Settings', 'wpcasa-mail-alert' ), 'manage_options', 'wpcasama-mail-settings', 'wpcasama_menu' );

}

function wpcasama_stars() { ?>
	<div class="wpcasama-stars">
        <span id="wpcasama-footer-credits">
                <span class="dashicons dashicons-wordpress"></span>
	        <?php _e( "You like WPCasa Mail Alert? Don't forget to rate it 5 stars!", "wpcasa-mail-alert" ) ?>

            <span class="wporg-ratings rating-stars">
                    <a href="//wordpress.org/support/view/plugin-reviews/wpcasa-mail-alert?rate=1#postform"
                       data-rating="1" title="" target="_blank"><span class="dashicons dashicons-star-filled"
                                                                      style="color:#FFDE24 !important;"></span></a>
                    <a href="//wordpress.org/support/view/plugin-reviews/wpcasa-mail-alert?rate=2#postform"
                       data-rating="2" title="" target="_blank"><span class="dashicons dashicons-star-filled"
                                                                      style="color:#FFDE24 !important;"></span></a>
                    <a href="//wordpress.org/support/view/plugin-reviews/wpcasa-mail-alert?rate=3#postform"
                       data-rating="3" title="" target="_blank"><span class="dashicons dashicons-star-filled"
                                                                      style="color:#FFDE24 !important;"></span></a>
                    <a href="//wordpress.org/support/view/plugin-reviews/wpcasa-mail-alert?rate=4#postform"
                       data-rating="4" title="" target="_blank"><span class="dashicons dashicons-star-filled"
                                                                      style="color:#FFDE24 !important;"></span></a>
                    <a href="//wordpress.org/support/view/plugin-reviews/wpcasa-mail-alert?rate=5#postform"
                       data-rating="5" title="" target="_blank"><span class="dashicons dashicons-star-filled"
                                                                      style="color:#FFDE24 !important;"></span></a>
                </span>
                <script>
                    jQuery(document).ready(function ($) {
                        $(".rating-stars").find("a").hover(
                            function () {
                                $(this).nextAll("a").children("span").removeClass("dashicons-star-filled").addClass("dashicons-star-empty");
                                $(this).prevAll("a").children("span").removeClass("dashicons-star-empty").addClass("dashicons-star-filled");
                                $(this).children("span").removeClass("dashicons-star-empty").addClass("dashicons-star-filled");
                            }, function () {
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
	</div> <?php
}

function wpcasama_menu() {

	/**
	 * Filter this hooks to add a tab in the setting page
	 */
	$tabs = apply_filters( 'wpcasama_setting_tabs',
		array(
			'mail' => __( 'E-mail', 'wpcasa-mail-alert' ),
			/*'help' => __( 'help', 'wpcasa-mail-alert' ),*/

		)
	);

	if ( isset( $_GET['tab'] ) ) {

		$active_tab = $_GET['tab'];

	} else {
		$active_tab = 'mail';
	}


	if ( defined( 'PLUGIN_VERSION' ) ) {
		echo '<h1>' . get_admin_page_title() . ' Pro ' . PLUGIN_VERSION . '</h1>';

	} else {
		echo '<h1>' . get_admin_page_title() . ' ' . PLUGIN_VERSION;
		echo '</h1>';
	}

	?>
	<div class="wrap">

		<h2><?php _e( 'Settings', 'wpcasa-mail-alert' ); ?></h2>
		<!--<div class="description">This is description of the page.</div>-->
		<?php settings_errors(); ?>

		<h2 class="nav-tab-wrapper">
			<?php
			foreach ( $tabs as $tab => $value ) {
				?>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=wpcasama-mail-settings&tab=' . $tab ) ); ?>"
				   class="nav-tab <?php echo 'nav-tab-' . $tab;
				   echo $active_tab === $tab ? ' nav-tab-active' : ''; ?>"><?php echo $value ?></a>
			<?php } ?>
		</h2>

		<?php
		$active_tab = apply_filters( 'wpcasama_setting_tabs', $active_tab );
		?>

		<form method="post" action="options.php" class="wpcasama-settings-form">
			<?php
			switch ( $active_tab ) {
				case 'mail' :
					settings_fields( 'thfo_newsletter_settings' );
					do_settings_sections( 'thfo_newsletter_settings' );
					submit_button( __( 'Save' ) );
					break;
/*				case 'help' :
					$url = esc_url( 'https://docs.thivinfo.com/collection/25-wpcasa-mail-alert' );
					*/?><!--
				<h2><?php /*_e('Help', 'wpcasa-mail-alert'); */?></h2>
				<p><?php /*_e('The easiest way to find help is coming on my website and read the documentation.', 'wpcasa-mail-alert'); */?></p>
				<p><?php /*_e('If you don\'t find the help needed. Feel free to contact me.', 'wpcasa-mail-alert'); */?></p>
				<a href="<?php /*echo $url; */?>" target="_blank"><?php /*_e('Online Documentation.', 'wpcasa-mail-alert'); */?></a>-->
			<?php
					break;
			}

			?>
		</form>
	</div>

	<?php
	wpcasama_stars();
}

function register_settings() {
	/* Mail Settings */
	add_settings_section( 'thfo_newsletter_section', __( 'Email parameters', 'wpcasa-mail-alert' ), 'section_html', 'thfo_newsletter_settings' );

	register_setting( 'thfo_newsletter_settings', 'thfo_newsletter_sender' );
	register_setting( 'thfo_newsletter_settings', 'thfo_newsletter_sender_mail' );
	register_setting( 'thfo_newsletter_settings', 'thfo_newsletter_object' );
	register_setting( 'thfo_newsletter_settings', 'thfo_newsletter_content' );
	register_setting( 'thfo_newsletter_settings', 'thfo_newsletter_footer' );
	register_setting( 'thfo_newsletter_settings', 'empathy-setting-logo' );

	add_settings_field( 'thfo_newsletter_sender', __( 'Sender', 'wpcasa-mail-alert' ), 'sender_html', 'thfo_newsletter_settings', 'thfo_newsletter_section' );
	add_settings_field( 'empathy-setting-logo', __( 'Header picture', 'wpcasa-mail-alert' ), 'media_html', 'thfo_newsletter_settings', 'thfo_newsletter_section' );
	add_settings_field( 'thfo_newsletter_footer', __( 'footer', 'wpcasa-mail-alert' ), 'footer_html', 'thfo_newsletter_settings', 'thfo_newsletter_section' );
	add_settings_field( 'thfo_newsletter_sender_mail', __( 'email', 'wpcasa-mail-alert' ), 'sender_mail_html', 'thfo_newsletter_settings', 'thfo_newsletter_section' );
	add_settings_field( 'thfo_newsletter_object', __( 'Object', 'wpcasa-mail-alert' ), 'object_html', 'thfo_newsletter_settings', 'thfo_newsletter_section' );
	add_settings_field( 'thfo_newsletter_content', __( 'Content', 'wpcasa-mail-alert' ), 'content_html', 'thfo_newsletter_settings', 'thfo_newsletter_section' );


	/**
	 * transform old settings to new WPCasa Options
	 *
	 * @since: 1.2.0
	 */

	$prices = get_option( 'thfo_max_price' );
	if ( ! empty( $prices ) ) {
		wpsight_add_option( 'thfo_max_price', $prices );
		delete_option( 'thfo_max_price' );
	}

	$thfo_unsubscribe_page = get_option( 'thfo_unsubscribe_page' );
	if ( ! empty( $thfo_unsubscribe_page ) ) {
		wpsight_add_option( 'thfo_unsubscribe_page', $thfo_unsubscribe_page );
		delete_option( 'thfo_unsubscribe_page' );
	}

}

function media_html() { ?>
	<input type="text" name="empathy-setting-logo" id="empathy-setting-logo"
	       value="<?php echo esc_attr( get_option( 'empathy-setting-logo' ) ); ?>">
	<a class="button" onclick="upload_image('empathy-setting-logo');"><?php _e( 'Upload', 'wpcasa-mail-alert' ) ?></a>
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
            uploader.on('select', function () {
                attachment = uploader.state().get('selection').first().toJSON();
                var url = attachment['url'];
                jQuery('#' + id).val(url);
            });

            //Open the uploader dialog
            uploader.open();
        }
	</script>
<?php }

function section_html() {

	echo '<p>' . __( 'Advise about outgoing parameters.', 'wpcasa-mail-alert' ) . '</p>';

}

function footer_html() {
	?>
	<textarea name="thfo_newsletter_footer"><?php echo get_option( 'thfo_newsletter_footer' ) ?></textarea>

	<?php
}

function sender_html() {
	?>
	<input type="text" name="thfo_newsletter_sender" value="<?php echo get_option( 'thfo_newsletter_sender' ) ?>"/>
	<?php
}

function sender_mail_html() {
	?>
	<input type="email" name="thfo_newsletter_sender_mail"
	       value="<?php echo get_option( 'thfo_newsletter_sender_mail' ) ?>"/>
	<?php
}

function object_html() {
	?>

	<input type="text" name="thfo_newsletter_object" value="<?php echo get_option( 'thfo_newsletter_object' ) ?>"/>
	<?php


}

function content_html() {
	ob_start();
	?>
	<p><?php printf( __( 'Hello %s', 'wpcasa-mail-alert'), '{displayname}' ) ?></p>
	<p><?php printf( __( '%s find property matching with your criterias', 'wpcasa-mail-alert' ), '{company}'); ?></p>
	<p><?php _e( 'You can find them on following links', 'wpcasa-mail-alert' ); ?></p>
	<?php
	//delete_option( 'thfo_newsletter_content' );
	$default = ob_get_clean();
	$content = get_option( 'thfo_newsletter_content' );
	if (false === $content ) {
		update_option( 'thfo_newsletter_content', $default );
		$content = get_option( 'thfo_newsletter_content' );
	}
	wp_editor( $content, 'thfo_newsletter_content', array( 'wpautop' => false ) );
	?>
	<p class="bold"><?php _e( 'You can use these t ags to dynamically customize email in content and subject:', 'wpcasa-mail-alert'); ?></p>
	<p>{displayname}</p>
	<p>{company}</p>
	<p>{email}</p>
	<p>{listing}</p>
	<p>{city}</p>
	<p>{min_price}</p>
	<p>{max_price}</p>
	<p>{firstname}</p>
	<p>{lastname}</p>
	<p>{details_1}</p>
	<p>{details_2}</p>
	<p>{details_3}</p>
	<p>{details_4}</p>
	<p>{details_5}</p>
	<p>{details_6}</p>
	<p>{details_7}</p>
	<p>{details_8}</p>
	<?php

}
