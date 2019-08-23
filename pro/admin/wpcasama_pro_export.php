<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	} // Exit if accessed directly
	
	function wpcasama_export_csv_link() {
		$url = add_query_arg( array( 'format' => 'csv' ) );
		?>
        <div class="wpcasama-pro-export">
            <a href="<?php echo $url ?>"
               title="<?php _e( 'Export to CSV', 'wpcasa-mail-alert' ); ?>"><?php _e( 'Export to CSV', 'wpcasa-mail-alert' ); ?></a>
        </div>
	<?php }
	
	add_action( 'thfo/wpcasama/after-table', 'wpcasama_export_csv_link' );
	
	function wpcasama_export_csv_script() {
		
		if ( isset( $_GET['bulk_alerts_ids'] ) && ! empty( $_GET['bulk_alerts_ids'] ) ) {
			$ids = explode( ' ', $_GET['bulk_alerts_ids'] );
			
			$general_table = array();
			
			foreach ( $ids as $alert_id ) {
				$post_data   = get_post( $alert_id );
				$post_meta   = get_post_meta( $alert_id );
				$author_data = get_userdata( $post_data->post_author );
				$author_meta = get_user_meta( $post_data->post_author );
				
				$table['name']  = $author_meta['first_name'][0] . ' ' . $author_meta['last_name'][0];
				$table['mail']  = $author_data->data->user_email;
				$table['phone'] = $author_meta['wpcasama_phone'][0];
				
				foreach ( $post_meta as $key => $pm ) {
					if ( $key != '_edit_lock' ) {
						$table[ $key ] = $pm[0];
					}
				}
				
				
				array_push( $general_table, $table );
			}

				$entete = array();
				
				foreach ( $general_table[0] as $key => $value ) {
					array_push( $entete, $key );
				}
				
				$filename = 'wpcasa-mail-alert-export';
				$date     = date_i18n( "Y-m-d-His" );
				
				// create 1st line
				$output = fopen( 'php://output', 'w' );
				
				fputcsv( $output, $entete );
				
				foreach ( $general_table as $value ) {
					fputcsv( $output, $value );
				}
				
				header( "Pragma: public" );
				header( "Expires: 0" );
				header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
				header( "Cache-Control: private", false );
				header( 'Content-Type: text/csv; charset=utf-8' );
				header( "Content-Disposition: attachment; filename=\"" . $filename . "-" . $date . ".csv\";" );
				header( "Content-Transfer-Encoding: binary" );
				exit;
			
			
		}
	}
	
	add_action( 'admin_init', 'wpcasama_export_csv_script' );
	
	
	/**
	 * Add an Export to CSV Option in Bulk action list
	 *
	 * @param $bulk_actions
	 *
	 * @return mixed
	 */
	function wpcasama_pro_bulk_actions( $bulk_actions ) {
		$bulk_actions['export_to_csv'] = __( 'Export to CSV', 'wpcasa-mail-alert' );
		
		return $bulk_actions;
	}
	
	add_filter( 'bulk_actions-edit-wpcasa-mail-alerte', 'wpcasama_pro_bulk_actions' );
	
	add_filter( 'handle_bulk_actions-edit-wpcasa-mail-alerte', 'my_bulk_action_handler', 10, 3 );
	
	function my_bulk_action_handler( $redirect_to, $doaction, $post_ids ) {
		if ( $doaction !== 'export_to_csv' ) {
			return $redirect_to;
		}
		$alerts_id   = implode( '+', $post_ids );
		$redirect_to = add_query_arg( array(
			'bulk_alert_number' => count( $post_ids ),
			'bulk_alerts_ids'   => $alerts_id,
		), $redirect_to );
		
		return $redirect_to;
	}