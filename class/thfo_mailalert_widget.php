<?php

/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 27/01/16
 * Time: 17:57
 */
class thfo_mailalert_widget extends WP_Widget {

	function __construct() {
		parent::__construct( 'thfo_mailalert', __('Mail Alert','wpcasa-mail-alert'), array( 'description' => __('Form to add a property search mail alert','wpcasa-mail-alert') ) );

	}

	/**
	 * explode a string with multiple value separated by $delimiter
	 * @param $delimiters
	 * @param $string
	 *
	 * @return array|mixed|void
	 */
	public function multiexplode ($delimiters,$string) {

		$ready = str_replace($delimiters, $delimiters[0], $string);
		$launch = explode($delimiters[0], $ready);

		$launch = apply_filters('thfo_mutliexplode', $launch);
		return  $launch;
	}


	/**
	 * Create a front office widget
	 * @param array $args
	 * @param array $instance
	 */

	public function widget( $args, $instance ) {

		echo $args['before_widget'];

		echo $args['before_title'];

		echo apply_filters( 'widget_title', $instance['title'] );

		echo $args['after_title'];

		$prices = get_option('thfo_max_price');
		$prices = $this->multiexplode(array(',',', '), $prices);
		/**
		 * Find number of rooms
		 */

		$rooms = get_posts(array( 'post_type' => array( 'listing' ), ));
		//var_dump($rooms);
		$nb_rooms= array();
		foreach ( $rooms as $room) {
			//var_dump($room);
			$nb_room =  get_post_meta( $room->ID, '_details_1'  );
			foreach ($nb_room as $room){
				$nb_rooms[] = intval($room);
				//var_dump(intval($room));
			}
			//var_dump( $nb_room);
		}

		sort($nb_rooms, SORT_NUMERIC);
		$nb_rooms = array_unique($nb_rooms);

		//var_dump($nb_rooms);

		do_action('wpcasama_info');
		?>

		<form action="" method="post">
			<p>
				<label for="thfo_mailalert_name"> <?php _e('Your name', 'wpcasa-mail-alert') ?>*</label>
				<input id="thfo_mailalert_name" name="thfo_mailalert_name" required/>
				<label for="thfo_mailalert_email"> <?php _e('Your Email', 'wpcasa-mail-alert') ?>*</label>
				<input id="thfo_mailalert_email" name="thfo_mailalert_email" type="email" required/>
				<label for="thfo_mailalert_phone"> <?php _e('Your Phone number', 'wpcasa-mail-alert') ?></label>
				<input id="thfo_mailalert_phone" name="thfo_mailalert_phone" />
				<label for="thfo_mailalert_city"> <?php _e('City', 'wpcasa-mail-alert') ?></label>
				<select name="thfo_mailalert_city" required>
					<?php
					$city = get_terms( 'location' );
					foreach ($city as $c){
						$cities = $c->name; ?>
						<option name="thfo_mailalert_city" value="<?php echo $cities ?>"><?php echo $cities ?></option>
					<?php }
					?>
				</select>
				<label for="thfo_mailalert_min_price"> <?php _e('Minimum Price', 'wpcasa-mail-alert') ?></label>
				<select name="thfo_mailalert_min_price">
					<option name="thfo_mailalert_min_price" value="0">0€</option>
					<?php
					foreach ($prices as $price){ ?>

						<option name="thfo_mailalert_min_price" value="<?php echo $price  ?>"><?php echo $price  ?>€</option>
					<?php }
					?>
				</select>
				<label for="thfo_mailalert_price"> <?php _e('Maximum Price', 'wpcasa-mail-alert') ?></label>
				<select name="thfo_mailalert_price">
					<?php
					foreach ($prices as $price){ ?>
						<option name="thfo_mailalert_price" value="<?php echo $price  ?>"><?php echo $price  ?>€</option>
					<?php }
					?>
					<option name="thfo_mailalert_price" value="more"><?php _e('Infinite', 'wpcasa-mail-alert') ?></option>
				</select>
				<label for="thfo_mailalert_room"> <?php _e('Room', 'wpcasa-mail-alert') ?></label>
				<select name="thfo_mailalert_room">
					<?php
					foreach ($nb_rooms as $nb_room){ ?>
                        <option name="thfo_mailalert_room" value="<?php echo $nb_room; ?>"><?php echo $nb_room; ?></option>
					<?php }

					?>
				</select>
                <?php do_action('wpcasama_end_widget'); ?>
			</p>
			<input name="thfo_mailalert" class="moretag btn btn-primary" type="submit" />
		</form>
<?php
		echo $args['after_widget'];
	}

	/**
	 * Affichage du Widget en BO
	 * @param array $instance
	 */

	public function form($instance)
	{
		$title = isset($instance['title']) ? $instance['title'] : ''; ?>
		<p>
			<label for="<?php echo $this->get_field_name('title'); ?>"> <?php _e('Title:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
			       name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>"/>

		</p>

		<?php
	}


}