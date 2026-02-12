<?php
/**
 * CPH Animated Vert Line - WPBakery Map
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	'name'                    => esc_html__( 'CPH Animated Vert Line', 'cph-elements' ),
	'base'                    => 'cph_animated_vert_line',
	'content_element'         => true,
	'show_settings_on_create' => true,
	'category'                => esc_html__( 'Clear pH Elements', 'cph-elements' ),
	'icon'                    => 'icon-wpb-ui-separator',
	'description'             => esc_html__( 'Decorative vertical line with optional grow animation.', 'cph-elements' ),
	'params'                  => array(

		// ==================================================================
		//  General
		// ==================================================================

		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Line Color', 'cph-elements' ),
			'param_name'  => 'line_color',
			'value'       => '#87A80E',
			'group'       => esc_html__( 'General', 'cph-elements' ),
			'description' => esc_html__( 'Color of the vertical line.', 'cph-elements' ),
		),

		array(
			'type'       => 'dropdown',
			'heading'    => esc_html__( 'Animation', 'cph-elements' ),
			'param_name' => 'animate',
			'value'      => array(
				esc_html__( 'Grow Top to Bottom', 'cph-elements' ) => 'top-to-bottom',
				esc_html__( 'Grow Bottom to Top', 'cph-elements' ) => 'bottom-to-top',
				esc_html__( 'None', 'cph-elements' )               => 'none',
			),
			'group'       => esc_html__( 'General', 'cph-elements' ),
			'description' => esc_html__( 'Optional entrance animation triggered on scroll.', 'cph-elements' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Animation Duration', 'cph-elements' ),
			'param_name'  => 'animation_duration',
			'value'       => '0.8s',
			'group'       => esc_html__( 'General', 'cph-elements' ),
			'description' => esc_html__( 'CSS time value (e.g. 0.8s, 400ms).', 'cph-elements' ),
			'dependency'  => array(
				'element'            => 'animate',
				'value_not_equal_to' => array( 'none' ),
			),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Extra Class', 'cph-elements' ),
			'param_name'  => 'el_class',
			'value'       => '',
			'group'       => esc_html__( 'General', 'cph-elements' ),
			'description' => esc_html__( 'Optional CSS class for the wrapper.', 'cph-elements' ),
		),

		// ==================================================================
		//  Layout — Height
		// ==================================================================

		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Height', 'cph-elements' ),
			'param_name'       => 'height',
			'value'            => '185px',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Layout', 'cph-elements' ),
			'description'      => esc_html__( 'Desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Tablet', 'cph-elements' ),
			'param_name'       => 'height_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Layout', 'cph-elements' ),
			'description'      => esc_html__( 'Inherits desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Phone', 'cph-elements' ),
			'param_name'       => 'height_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Layout', 'cph-elements' ),
			'description'      => esc_html__( 'Inherits tablet.', 'cph-elements' ),
		),

		// ==================================================================
		//  Layout — Width (line thickness)
		// ==================================================================

		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Width', 'cph-elements' ),
			'param_name'       => 'width',
			'value'            => '2px',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Layout', 'cph-elements' ),
			'description'      => esc_html__( 'Desktop. Line thickness.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Tablet', 'cph-elements' ),
			'param_name'       => 'width_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Layout', 'cph-elements' ),
			'description'      => esc_html__( 'Inherits desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Phone', 'cph-elements' ),
			'param_name'       => 'width_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Layout', 'cph-elements' ),
			'description'      => esc_html__( 'Inherits tablet.', 'cph-elements' ),
		),

		// ==================================================================
		//  Layout — Alignment (horizontal position)
		// ==================================================================

		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Alignment', 'cph-elements' ),
			'param_name'       => 'align',
			'value'            => 'center',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Layout', 'cph-elements' ),
			'description'      => esc_html__( 'Desktop. "center" or CSS left value (25vw, 30%).', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Tablet', 'cph-elements' ),
			'param_name'       => 'align_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Layout', 'cph-elements' ),
			'description'      => esc_html__( 'Inherits desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Phone', 'cph-elements' ),
			'param_name'       => 'align_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Layout', 'cph-elements' ),
			'description'      => esc_html__( 'Inherits tablet.', 'cph-elements' ),
		),

		// ==================================================================
		//  Layout — Top Position (vertical offset)
		// ==================================================================

		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Top Position', 'cph-elements' ),
			'param_name'       => 'top',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Layout', 'cph-elements' ),
			'description'      => esc_html__( 'Desktop. Empty = auto-center. Any CSS value.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Tablet', 'cph-elements' ),
			'param_name'       => 'top_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Layout', 'cph-elements' ),
			'description'      => esc_html__( 'Inherits desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Phone', 'cph-elements' ),
			'param_name'       => 'top_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Layout', 'cph-elements' ),
			'description'      => esc_html__( 'Inherits tablet.', 'cph-elements' ),
		),
	),
);
