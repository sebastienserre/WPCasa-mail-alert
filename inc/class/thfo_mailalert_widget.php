<?php

	defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

	class thfo_mailalert_widget extends WP_Widget {

		function __construct() {
			parent::__construct( 'thfo_mailalert', __( 'Mail Alert', 'wpcasa-mail-alert' ), array( 'description' => __( 'Form to add a property search mail alert', 'wpcasa-mail-alert' ) ) );

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

			$prices   = $this->multiexplode( array( ',', ', ' ), $prices );
			$currency = wpsight_get_currency();

			do_action( 'wpcasama_info' );
			?>

            <form action="" method="post">
                <div class="wpcasama-widget-field"><label
                            for="thfo_mailalert_name"> <?php _e( 'Your name', 'wpcasa-mail-alert' ) ?>*</label>
                    <input id="thfo_mailalert_name" name="thfo_mailalert_name" value="<?php if (isset($_POST['thfo_mailalert']) && !empty($_POST['thfo_mailalert_name']) ){ echo $_POST['thfo_mailalert_name']; } ?>" required/>
                </div>
                <div class="wpcasama-widget-field">
                    <label for="thfo_mailalert_email"> <?php _e( 'Your Email', 'wpcasa-mail-alert' ) ?>*</label>
                    <input id="thfo_mailalert_email" name="thfo_mailalert_email" type="email" required/>
                </div>
                <div class="wpcasama-widget-field">
                    <label for="thfo_mailalert_phone"> <?php _e( 'Your Phone number', 'wpcasa-mail-alert' ) ?></label>
                    <input id="thfo_mailalert_phone" name="thfo_mailalert_phone"/>
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
                        <option name="thfo_mailalert_min_price" value="0">0 <?php echo $currency ?></option>
						<?php
							foreach ( $prices as $price ) { ?>

                                <option name="thfo_mailalert_min_price"
                                        value="<?php echo $price ?>"><?php echo $price ?><?php echo $currency ?></option>
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
                                        value="<?php echo $price ?>"><?php echo $price ?><?php echo $currency ?></option>
							<?php }
						?>
                        <option name="thfo_mailalert_price"
                                value="more"><?php _e( 'Infinite', 'wpcasa-mail-alert' ) ?></option>
                    </select>
                </div>
                <div class="wpcasama-widget-field">
                    <label for="wpcasama-account-agreement" required ><?php printf(__('I agree to create an account on %1$s to receive e-mail alerts.', 'wpcasa-mail-alert'), get_bloginfo('name'))?></label>
                    <input name="wpcasama-account-agreement" type="checkbox" value="checked" >
                </div>

				<?php do_action( 'wpcasama_end_widget' ); ?>
				
                <input name="thfo_mailalert" class="moretag btn btn-primary" type="submit"/>
            </form>
            <?php $url     = wpsight_get_option( 'thfo_unsubscribe_page' );?>
            <div class="unsubscribe_link clear"><a href=" <?php echo get_the_permalink( $url ) . '">'.  __( 'Link to unsubscribe page', 'wpcasa-mail-alert' ) ?></a> </div>
            
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
                <label for="<?php echo $this->get_field_name( 'title' ); ?>"> <?php _e( 'Title:' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
                       name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
                       value="<?php echo $title; ?>"/>

            </p>

			<?php
		}


	}