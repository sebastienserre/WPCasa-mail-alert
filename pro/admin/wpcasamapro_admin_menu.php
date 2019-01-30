<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	add_filter( 'wpsight_options', 'wpcasamapro_options', 999 );
	/**
	 * @param $options
	 *
	 * @return mixed
	 */

	function wpcasamapro_options( $options ) {


		$details      = wpsight_details();

		foreach ($details as $key => $detail ){
			    $options_wpacasamapro[ $key ] = array(
				    'name' => $detail['label'],
				    'desc' => sprintf( __( 'Would you like allowing %1s filter ?', 'wpcasa-mail-alert-pro' ), $detail['label'] ),
				    'id'   => 'wpcasama_pro_' . $detail['label'],
				    'type' => 'checkbox',
			    );
		}

		$options[' '] = array(
			__( 'WPCasa Mail Alert Pro', 'wpcasa-mail-alert-pro' ),
			apply_filters( 'wpsight_listings_map_options', $options_wpacasamapro )
		);

		return $options;
	}


add_action( 'thfo_after_header_subscriber_table', 'wpcasama_add_columns_th' );
function wpcasama_add_columns_th() {
	$details      = wpsight_details();
	foreach ($details as $key => $detail ){ ?>

	    <th><?php echo $detail['label'] ?></th>

    <?php } ?>

	<th><?php _e( 'Type', 'wpcasa-mail-alert-pro' ) ?></th>

	<?php
}

add_action( 'thfo_after_tr_subscriber_table', 'wpcasama_add_columns_tr' );
function wpcasama_add_columns_tr($subscriber) {    ?>
    <td><?php echo $subscriber->details_1 ?></td>
    <td><?php echo $subscriber->details_2 ?></td>
    <td><?php echo $subscriber->details_3 ?></td>
    <td><?php echo $subscriber->details_4 ?></td>
    <td><?php echo $subscriber->details_5 ?></td>
    <td><?php echo $subscriber->details_6 ?></td>
    <td><?php echo $subscriber->details_7 ?></td>
    <td><?php echo $subscriber->details_8 ?></td>
	<td><?php echo $subscriber->type ?></td>

<?php }
