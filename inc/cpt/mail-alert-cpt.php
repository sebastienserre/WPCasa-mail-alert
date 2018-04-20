<?php
if ( ! defined( 'ABSPATH' ) ){ exit; } // Exit if accessed directly
	
	if ( ! function_exists('wpcasama_cpt') ) {

// Register Custom Post Type
		function wpcasama_cpt() {
			
			$labels = array(
				'name'                  => _x( 'Email Alerts', 'Post Type General Name', 'wpcasa-mail-alert' ),
				'singular_name'         => _x( 'Email Alert', 'Post Type Singular Name', 'wpcasa-mail-alert' ),
				'menu_name'             => __( 'Email Alert', 'wpcasa-mail-alert' ),
				'name_admin_bar'        => __( 'Email Alert', 'wpcasa-mail-alert' ),
				'archives'              => __( 'Email Alert Archives', 'wpcasa-mail-alert' ),
				'attributes'            => __( 'Email Alert Attributes', 'wpcasa-mail-alert' ),
				'parent_item_colon'     => __( 'Parent Email Alert:', 'wpcasa-mail-alert' ),
				'all_items'             => __( 'All Email Alerts', 'wpcasa-mail-alert' ),
				'add_new_item'          => __( 'Add New Email Alert', 'wpcasa-mail-alert' ),
				'add_new'               => __( 'Add New', 'wpcasa-mail-alert' ),
				'new_item'              => __( 'New Email Alert', 'wpcasa-mail-alert' ),
				'edit_item'             => __( 'Edit Email Alert', 'wpcasa-mail-alert' ),
				'update_item'           => __( 'Update Email Alert', 'wpcasa-mail-alert' ),
				'view_item'             => __( 'View Email Alert', 'wpcasa-mail-alert' ),
				'view_items'            => __( 'View Email Alerts', 'wpcasa-mail-alert' ),
				'search_items'          => __( 'Search Email Alert', 'wpcasa-mail-alert' ),
				'not_found'             => __( 'Not found', 'wpcasa-mail-alert' ),
				'not_found_in_trash'    => __( 'Not found in Trash', 'wpcasa-mail-alert' ),
				'featured_image'        => __( 'Featured Image', 'wpcasa-mail-alert' ),
				'set_featured_image'    => __( 'Set featured image', 'wpcasa-mail-alert' ),
				'remove_featured_image' => __( 'Remove featured image', 'wpcasa-mail-alert' ),
				'use_featured_image'    => __( 'Use as featured image', 'wpcasa-mail-alert' ),
				'insert_into_item'      => __( 'Insert into item', 'wpcasa-mail-alert' ),
				'uploaded_to_this_item' => __( 'Uploaded to this item', 'wpcasa-mail-alert' ),
				'items_list'            => __( 'Email Alerts list', 'wpcasa-mail-alert' ),
				'items_list_navigation' => __( 'Email Alerts navigation', 'wpcasa-mail-alert' ),
				'filter_items_list'     => __( 'Filter Email Alerts list', 'wpcasa-mail-alert' ),
			);
			$capabilities = array(
				'edit_post'             => 'edit_email_alert',
				'read_post'             => 'read_email_alert',
				'delete_post'           => 'delete_email_alert',
				'edit_posts'            => 'edit_email_alerts',
				'edit_others_posts'     => 'edit_others_email_alerts',
				'publish_posts'         => 'publish_email_alert',
				'read_private_posts'    => 'read_private_email_alert',
			);
			$args = array(
				'label'                 => __( 'Email Alert', 'wpcasa-mail-alert' ),
				'description'           => __( 'Receive an email when listings match your criterias', 'wpcasa-mail-alert' ),
				'labels'                => $labels,
				'supports'              => array( 'title' ),
				'hierarchical'          => false,
				'public'                => true,
				'show_ui'               => true,
				'show_in_menu'          => true,
				'menu_position'         => 5,
				'menu_icon'             => 'dashicons-email',
				'show_in_admin_bar'     => true,
				'show_in_nav_menus'     => true,
				'can_export'            => true,
				'has_archive'           => true,
				'exclude_from_search'   => true,
				'publicly_queryable'    => true,
				//'capabilities'          => $capabilities,
				'show_in_rest'          => true,
			);
			register_post_type( 'wpcasa-mail-alerte', $args );
			
		}
		add_action( 'init', 'wpcasama_cpt', 0 );
		
	}
	
	function wpcasama_hide_publishing_actions(){
		$my_post_type = 'wpcasa-mail-alerte';
		global $post;
		if($post->post_type == $my_post_type){
			$style = '
                <style type="text/css">
                    #misc-publishing-actions,
                    #minor-publishing-actions,
                    #major-publishing-actions input{
                        display:none;
                    }
                </style>
            ';
			echo apply_filters('wpcasama/savedata/style', $style);
		}
	}
	
	add_action('admin_head-post.php', 'wpcasama_hide_publishing_actions');
	add_action('admin_head-post-new.php', 'wpcasama_hide_publishing_actions');
	
	
	add_filter( 'manage_edit-wpcasa-mail-alerte_columns', 'wpcasama_edit_columns' ) ;
	
	function wpcasama_edit_columns( $columns ) {
		
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => __( 'Title', 'wpcasa-mail-alert' ),
			'phone' => __( 'Phone', 'wpcasa-mail-alert' ),
			'city' => __( 'City', 'wpcasa-mail-alert' ),
			'min_price' => __( 'Min Price', 'wpcasa-mail-alert' ),
			'max_price' => __( 'Max price', 'wpcasa-mail-alert' ),
			'name'  =>  __('Name', 'wpcasa-mail-alert'),
			'date' => __( 'Date', 'wpcasa-mail-alert' ),
		);
		
		return $columns;
	}
	
	function wpcasama_alert_colmuns($column, $post_id){
		global $post;
		$currency = wpsight_get_currency();
		$meta = get_post_custom( $post->ID );
		$post_author_id   = get_post_field( 'post_author', $post->ID );
		$post_author_phone = get_the_author_meta('wpcasama_phone', $post_author_id);
		switch ($column) {
			case 'phone' :
				if ( ! empty ($post_author_phone ) ) {
					echo $post_author_phone;
				} else {
					_e('Not Provided', 'wpcasa-mail-alert');
				}
				break;
			case 'city' :
				echo $meta['wpcasama_city'][0];
				break;
			case 'min_price' :
				echo $meta['wpcasama_min_price'][0] . $currency;
				break;
			case 'max_price' :
				echo $meta['wpcasama_max_price'][0] . $currency;
				break;
			case 'name' :
				
				echo '<a href="'. admin_url('user-edit.php?user_id=' . get_the_author_meta('ID') ) .'">' . get_the_author() . '</a>';
				break;
		}
	}
	
	add_action('manage_wpcasa-mail-alerte_posts_custom_column', 'wpcasama_alert_colmuns', 10, 2);