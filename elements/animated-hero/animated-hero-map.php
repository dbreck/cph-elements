<?php
/**
 * Mira Mar Animated Hero - WPBakery Element Map
 *
 * @package CPH_Elements
 * @since   1.11.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	'name'        => esc_html__( 'Mira Mar Animated Hero', 'cph-elements' ),
	'base'        => 'miramar_animated_hero',
	'icon'        => 'icon-wpb-images-stack',
	'category'    => esc_html__( 'Clear pH Elements', 'cph-elements' ),
	'description' => esc_html__( 'Animated facade hero with drifting clouds.', 'cph-elements' ),
	'params'      => array(
		array(
			'type'       => 'dropdown',
			'heading'    => esc_html__( 'Show Birds', 'cph-elements' ),
			'param_name' => 'show_birds',
			'value'      => array(
				esc_html__( 'Yes', 'cph-elements' ) => 'yes',
				esc_html__( 'No', 'cph-elements' )  => 'no',
			),
			'std'        => 'yes',
		),
		array(
			'type'       => 'dropdown',
			'heading'    => esc_html__( 'Show Logo', 'cph-elements' ),
			'param_name' => 'show_logo',
			'value'      => array(
				esc_html__( 'Yes', 'cph-elements' ) => 'yes',
				esc_html__( 'No', 'cph-elements' )  => 'no',
			),
			'std'        => 'yes',
		),
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Cloud Drift Duration (seconds)', 'cph-elements' ),
			'param_name'  => 'cloud_drift',
			'value'       => '90',
			'description' => esc_html__( 'Seconds for one full cloud loop. Lower = faster.', 'cph-elements' ),
		),
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Bird Pass Duration (seconds)', 'cph-elements' ),
			'param_name'  => 'bird_pass',
			'value'       => '60',
			'description' => esc_html__( 'Seconds per bird flock cycle (flock is visible for the first 60%).', 'cph-elements' ),
		),
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Max Width', 'cph-elements' ),
			'param_name'  => 'max_width',
			'value'       => '',
			'description' => esc_html__( 'Optional, e.g. 900px. Leave empty to fill the column.', 'cph-elements' ),
		),
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Extra class name', 'cph-elements' ),
			'param_name'  => 'class',
			'description' => esc_html__( 'Add an extra class name and refer to it from custom CSS.', 'cph-elements' ),
		),
	),
);
