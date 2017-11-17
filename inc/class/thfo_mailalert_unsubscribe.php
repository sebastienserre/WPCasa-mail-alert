<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

		add_shortcode('thfo_mailalert_unsubscribe','unsubscribe_html');


	function unsubscribe_html() { ?>
        <form class="thfo_unsubscribe" method="post" action="">
            <label><?php _e( 'Please add your mail', 'wpcasa-mail-alert' ); ?></label>
            <input type="email" name="email" <?php
				if ( isset( $_GET['remove'] ) && ! empty( $_GET['remove'] ) ) { ?>
                    value="<?php echo $_GET['remove']; ?>"
				<?php }

			?>/>
            <input type="submit" name="delete" value="<?php _e( 'unsubscribe', 'wpcasa-mail-alert' ); ?>"/>
        </form>
		<?php
	}

	add_action('init', 'wpcasama_unsubscribe');
	function wpcasama_unsubscribe(){

		/**
		 * fires before deleting a subscriber
		 * @since 1.4.0
		 */
		do_action('thfo_before_deleting_subscriber');

		if ( isset( $_POST['delete']) && ! empty( $_POST['delete']) )
		{
			if ( is_email($_POST['email'])){
				$mail = sanitize_email($_POST['email']);
			}

			global $wpdb;
			$row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wpcasama_mailalert WHERE email = '$mail'");

			if (!is_null($row)) {
				$wpdb->delete("{$wpdb->prefix}wpcasama_mailalert", array('email' => $mail)); ?>
				<div class="thfo-mailalert-del"> <?php _e("Your mail address has been successfully deleted from our database","wpcasa-mail-alert"); ?> </div>
			<?php } else { ?>
                <div class="thfo-mailalert-del"> <?php _e("Your mail address doesn't exist in our database","wpcasa-mail-alert"); ?> </div>
			<?php }
		}

		/**
		 * fires after deleting a subscriber
		 * @since 1.4.0
		 */
		do_action('thfo_after_deleting_subscriber');

	}

	add_action('admin_init', 'wpcasama_unsubscribe_admin');
    function wpcasama_unsubscribe_admin(){

	    if (isset($_GET['remove']) && !empty($_GET['remove'])) {
		    $nonce = $_GET['_nonce'];
		    if ( wp_verify_nonce( $nonce, '_nonce-' . urldecode( $_GET['remove'] ) ) ) {
			    global $wpdb;
			    $wpdb->delete( "{$wpdb->prefix}wpcasama_mailalert", array( 'email' => urldecode( $_GET['remove'] ) ) );
		    }
	    }
    }