<?php
if ( ! defined( 'ABSPATH' ) ){ exit; } // Exit if accessed directly

if(function_exists("register_field_group"))
{
	//die('function exists');
	register_field_group(array (
		'id' => 'acf_mail-alert',
		'title' => 'Mail Alert',
		'fields' => array (
			array (
				'key' => 'field_5ac3bb4effff5',
				'label' => 'Phone',
				'name' => 'wpcasama_phone',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5ac3bb9adb144',
				'label' => 'city',
				'name' => 'wpcasama_city',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5ac3bbc9db145',
				'label' => 'Minimum Price',
				'name' => 'wpcasama_min_price',
				'type' => 'number',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => 0,
				'max' => '',
				'step' => 1,
			),
			array (
				'key' => 'field_5ac3bbf8db146',
				'label' => 'Maximum Price',
				'name' => 'maximum_price',
				'type' => 'number',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => 0,
				'max' => '',
				'step' => 1,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'wpcasa-mail-alerte',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
}