<?php
defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

class thfo_mailalert_widget extends WP_Widget {

	function __construct() {
		$widget_args = array(
			'classname'   => 'WPCasa_Mail_Alert_Widget',
			'description' => __( 'Add a WPCasa mail Alert Form', 'wpcasa-mail-alert' ),
		);
		parent::__construct(
			'WPCasa_Mail_Alert_Widget',
			__( 'Mail Alert', 'wpcasa-mail-alert' ),
			$widget_args
		);
		add_action( 'widgets_init', array( $this, 'init_wpcasa_mail_alert_widget' ) );

	}

	/**
	 * Initialize a new Widget.
	 */
	public function init_wpcasa_mail_alert_widget() {
		register_widget( 'thfo_mailalert_widget' );
	}

	/**
	 * explode a string with multiple value separated by $delimiter
	 *
	 * @param $delimiters
	 * @param $string
	 *
	 * @return array|mixed|void
	 */
	public function multiexplode( $delimiters, $string ) {

		$ready  = str_replace( $delimiters, $delimiters[0], $string );
		$launch = explode( $delimiters[0], $ready );

		$launch = apply_filters( 'thfo_mutliexplode', $launch );

		return $launch;
	}


	/**
	 * Create a front office widget
	 *
	 * @param array $args
	 * @param array $instance
	 */

	public function widget( $args, $instance ) {
		global $options;

		echo $args['before_widget'];

		echo $args['before_title'];

		echo apply_filters( 'widget_title', $instance['title'] );

		echo $args['after_title'];

		$prices = wpsight_get_option( 'thfo_max_price' );

		$prices           = $this->multiexplode( array( ',', ', ' ), $prices );
		$display_currency = wpsight_get_option( 'wpcasama_pro_display_currency' );
		if ( '1' === $display_currency ) {
			$currency_position = wpsight_get_option( 'currency_symbol' );
			$currency          = wpsight_get_currency();

		}

		if ( is_user_logged_in() ) {
			$current_user_id = get_current_user_id();

			$user_info                     = get_userdata( $current_user_id );
			$user_meta                     = get_user_meta( $current_user_id );
			$_POST['thfo_mailalert_name']  = $user_info->data->user_nicename;
			$_POST['thfo_mailalert_email'] = $user_info->data->user_email;
			if ( ! empty( $user_meta['wpcasama_phone'] ) ) {
				$_POST['thfo_mailalert_phone'] = $user_meta['wpcasama_phone'][0];
			}
		}

		do_action( 'wpcasama_info' );
		?>

        <form action="" method="post">

                <div class="wpcasama-widget-field"><label
                            for="thfo_mailalert_name"> <?php _e( 'Name', 'wpcasa-mail-alert' ) ?>*</label>
                    <input id="thfo_mailalert_name" name="thfo_mailalert_name"
                           value="<?php if ( isset( $_POST['thfo_mailalert_name'] ) && ! empty( $_POST['thfo_mailalert_name'] ) ) {
						       echo $_POST['thfo_mailalert_name'];
					       } ?>" required/>
                </div>
			<?php
				do_action( 'wpcasama_pro_form_name' );
			?>
            <div class="wpcasama-widget-field">
                <label for="thfo_mailalert_email"> <?php _e( 'Email', 'wpcasa-mail-alert' ) ?>*</label>
                <input id="thfo_mailalert_email" name="thfo_mailalert_email" type="email"
                       value="<?php if ( isset( $_POST['thfo_mailalert_email'] ) && ! empty( $_POST['thfo_mailalert_email'] ) ) {
					       echo $_POST['thfo_mailalert_email'];
				       } ?>" required/>
            </div>
            <div class="wpcasama-widget-field">
                <label for="thfo_mailalert_phone"> <?php _e( 'Phone number', 'wpcasa-mail-alert' ) ?></label>
                <input id="thfo_mailalert_phone" name="thfo_mailalert_phone"
                       value="<?php if ( isset( $_POST['thfo_mailalert_phone'] ) && ! empty( $_POST['thfo_mailalert_phone'] ) ) {
					       echo $_POST['thfo_mailalert_phone'];
				       } ?>"/>
            </div>
            <div class="wpcasama-widget-field">
                <label for="thfo_mailalert_city"> <?php _e( 'City', 'wpcasa-mail-alert' ) ?></label>
                <select name="thfo_mailalert_city" required>
					<?php
					$city = get_terms( 'location' );
					foreach ( $city as $c ) {
						$cities = $c->name; ?>
                        <option name="thfo_mailalert_city"
                                value="<?php echo $cities ?>"><?php echo $cities ?></option>
					<?php }
					?>
                </select>
            </div>
            <div class="wpcasama-widget-field">
                <label for="thfo_mailalert_min_price"> <?php _e( 'Minimum Price', 'wpcasa-mail-alert' ) ?></label>
                <select name="thfo_mailalert_min_price">
                    <option name="thfo_mailalert_min_price" value="0">
						<?php
						if ( 'before' === $currency_position ) {
							echo $currency;
						}
						?>
                        0
						<?php
						if ( 'after' === $currency_position ) {
							echo $currency;
						}
						?>
                    </option>
					<?php
					foreach ( $prices as $price ) { ?>

                        <option name="thfo_mailalert_min_price"
                                value="<?php echo $price ?>">
							<?php
							if ( 'before' === $currency_position ) {
								echo $currency;
							}
							?>
							<?php echo $price ?>

							<?php
							if ( 'after' === $currency_position ) {
								echo $currency;
							}
							?></option>
					<?php }
					?>
                </select>
            </div>
            <div class="wpcasama-widget-field">
                <label for="thfo_mailalert_price"> <?php _e( 'Maximum Price', 'wpcasa-mail-alert' ) ?></label>
                <select name="thfo_mailalert_price">
					<?php
					foreach ( $prices as $price ) { ?>
                        <option name="thfo_mailalert_price"
                                value="<?php echo $price ?>">
							<?php
							if ( 'before' === $currency_position ) {
								echo $currency;
							}
							?>
							<?php echo $price ?>
							<?php
							if ( 'after' === $currency_position ) {
								echo $currency;
							}
							?>

                        </option>
					<?php }
					?>
                    <option name="thfo_mailalert_price"
                            value="more"><?php _e( 'Infinite', 'wpcasa-mail-alert' ) ?></option>
                </select>
            </div>
			<?php if ( ! is_user_logged_in() ) { ?>
                <div class="wpcasama-widget-field">
                    <label for="wpcasama-account-agreement" required>
						<?php printf( __( 'I agree to create an account on %1$s to receive e-mail alerts.', 'wpcasa-mail-alert' ), get_bloginfo( 'name' ) ) ?>
                    </label>
                    <input name="wpcasama-account-agreement" type="checkbox" value="checked">
                </div>
			<?php } ?>

			<?php do_action( 'wpcasama_end_widget' ); ?>
            <div class="wpcasama-submit">
            <input name="thfo_mailalert" class="moretag btn btn-primary" type="submit"/>
            </div>
        </form>
		<?php $url = wpsight_get_option( 'thfo_unsubscribe_page' ); ?>
        <div class="unsubscribe_link clear" >
        <a href=" <?php echo get_the_permalink( $url ) . '">' . __( 'Link to unsubscribe page', 'wpcasa-mail-alert' ) ?></a>
            </div>

			<?php
		echo $args['after_widget'];
	}

	/**
	 * Affichage du Widget en BO
	 *
	 * @param array $instance
	 */

	public function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : ''; ?>
            <p>
                <label for="<?php echo $this->get_field_name( 'title' ); ?>"> <?php _e( 'Title:' ); ?></div>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
               name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
               value="<?php echo $title; ?>"/>

        </p>

		<?php
	}


}
