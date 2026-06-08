<?php
/**
 * Arrow Button - WPBakery Element Map
 *
 * Defines the WPBakery element parameters for the Arrow Button.
 *
 * The "Spacing & Transform" group (Padding + Margin + Transform) reuses
 * Salient's own SalientWPbakeryParamGroups::spacing_group() plus the Row's
 * Transform block, so the editor UI is identical to the native Row controls.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$design_group = esc_html__( 'Design', 'cph-elements' );

// Core element controls (Content + Design tabs).
$params = array(

	// Content Tab.
	array(
		'type'        => 'vc_link',
		'heading'     => esc_html__( 'Link', 'cph-elements' ),
		'param_name'  => 'link',
		'description' => esc_html__( 'Where the button points. Supports target and rel options.', 'cph-elements' ),
	),
	array(
		'type'        => 'textfield',
		'heading'     => esc_html__( 'Accessible Label', 'cph-elements' ),
		'param_name'  => 'aria_label',
		'value'       => 'View more',
		'description' => esc_html__( 'Screen-reader text for the button (no visible text is shown).', 'cph-elements' ),
		'admin_label' => true,
	),

	// Design Tab.
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Alignment', 'cph-elements' ),
		'param_name'  => 'align',
		'value'       => array(
			esc_html__( 'Center', 'cph-elements' ) => 'center',
			esc_html__( 'Left', 'cph-elements' )   => 'left',
			esc_html__( 'Right', 'cph-elements' )  => 'right',
		),
		'std'         => 'center',
		'group'       => $design_group,
	),
	array(
		'type'        => 'textfield',
		'heading'     => esc_html__( 'Width (px)', 'cph-elements' ),
		'param_name'  => 'width',
		'value'       => '140',
		'description' => esc_html__( 'Width of the pill in pixels. Height scales proportionally.', 'cph-elements' ),
		'group'       => $design_group,
	),
	array(
		'type'        => 'textfield',
		'heading'     => esc_html__( 'Stroke Width', 'cph-elements' ),
		'param_name'  => 'stroke_width',
		'value'       => '1',
		'description' => esc_html__( 'Thickness of the pill outline and arrow (SVG units).', 'cph-elements' ),
		'group'       => $design_group,
	),
	array(
		'type'        => 'colorpicker',
		'heading'     => esc_html__( 'Color', 'cph-elements' ),
		'param_name'  => 'color',
		'value'       => '#ffffff',
		'description' => esc_html__( 'Outline and arrow color.', 'cph-elements' ),
		'group'       => $design_group,
	),
	array(
		'type'        => 'colorpicker',
		'heading'     => esc_html__( 'Hover Color', 'cph-elements' ),
		'param_name'  => 'hover_color',
		'description' => esc_html__( 'Optional color on hover. Leave empty to keep the base color.', 'cph-elements' ),
		'group'       => $design_group,
	),
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Hover Effect', 'cph-elements' ),
		'param_name'  => 'hover_effect',
		'value'       => array(
			esc_html__( 'Slide Right', 'cph-elements' ) => 'slide',
			esc_html__( 'Grow', 'cph-elements' )        => 'grow',
			esc_html__( 'None', 'cph-elements' )        => 'none',
		),
		'std'         => 'slide',
		'group'       => $design_group,
	),
);

/*
 * Spacing & Transform — reuse Salient's native Row controls so the editor UI
 * is byte-for-byte the same (Padding + Margin from spacing_group(), plus the
 * Transform translate/scale/rotate block from the Row map). Guarded so the
 * element still loads if Salient Core is ever inactive.
 */
if ( class_exists( 'SalientWPbakeryParamGroups' ) ) {

	// Padding + Margin (includes the "Spacing & Transform" group header).
	$params = array_merge( $params, SalientWPbakeryParamGroups::spacing_group( $design_group ) );

	// Transform (Translate Y/X, Scale, Rotate) per device — mirrors vc_row.
	$transform_params = array(
		// Desktop.
		array(
			'type'             => 'nectar_numerical',
			'class'            => '',
			'group'            => $design_group,
			'heading'          => '<span class="group-title">' . esc_html__( 'Transform', 'cph-elements' ) . '</span>' . esc_html__( 'Translate Y', 'cph-elements' ),
			'value'            => '',
			'edit_field_class' => 'col-md-6 desktop row-transform-device-group',
			'param_name'       => 'translate_y',
			'description'      => '',
		),
		array(
			'type'             => 'nectar_numerical',
			'class'            => '',
			'group'            => $design_group,
			'heading'          => esc_html__( 'Translate X', 'cph-elements' ),
			'value'            => '',
			'edit_field_class' => 'col-md-6 col-md-6-last desktop row-transform-device-group',
			'param_name'       => 'translate_x',
			'description'      => '',
		),
		array(
			'type'             => 'nectar_range_slider',
			'group'            => $design_group,
			'heading'          => esc_html__( 'Scale', 'cph-elements' ),
			'param_name'       => 'scale_desktop',
			'value'            => '1',
			'options'          => array(
				'min'    => '0',
				'max'    => '2',
				'step'   => '0.01',
				'suffix' => 'x',
			),
			'edit_field_class' => 'col-md-6 desktop row-transform-device-group',
			'description'      => '',
		),
		array(
			'type'             => 'nectar_angle_selection',
			'class'            => '',
			'group'            => $design_group,
			'edit_field_class' => 'col-md-6 col-md-6-last desktop row-transform-device-group',
			'heading'          => "<span class='attr-title'>" . esc_html__( 'Rotate', 'cph-elements' ) . '</span>',
			'param_name'       => 'rotate_desktop',
			'value'            => '',
			'description'      => '',
		),
		// Tablet.
		array(
			'type'             => 'nectar_numerical',
			'class'            => '',
			'group'            => $design_group,
			'heading'          => esc_html__( 'Translate Y', 'cph-elements' ),
			'value'            => '',
			'edit_field_class' => 'col-md-6 tablet row-transform-device-group',
			'param_name'       => 'translate_y_tablet',
			'description'      => '',
		),
		array(
			'type'             => 'nectar_numerical',
			'class'            => '',
			'group'            => $design_group,
			'heading'          => esc_html__( 'Translate X', 'cph-elements' ),
			'value'            => '',
			'edit_field_class' => 'col-md-6 col-md-6-last tablet row-transform-device-group',
			'param_name'       => 'translate_x_tablet',
			'description'      => '',
		),
		array(
			'type'             => 'nectar_range_slider',
			'group'            => $design_group,
			'heading'          => esc_html__( 'Scale', 'cph-elements' ),
			'param_name'       => 'scale_tablet',
			'value'            => '1',
			'options'          => array(
				'min'    => '0',
				'max'    => '2',
				'step'   => '0.01',
				'suffix' => 'x',
			),
			'edit_field_class' => 'col-md-6 tablet row-transform-device-group',
			'description'      => '',
		),
		array(
			'type'             => 'nectar_angle_selection',
			'class'            => '',
			'group'            => $design_group,
			'edit_field_class' => 'col-md-6 col-md-6-last tablet row-transform-device-group',
			'heading'          => "<span class='attr-title'>" . esc_html__( 'Rotate', 'cph-elements' ) . '</span>',
			'param_name'       => 'rotate_tablet',
			'value'            => '',
			'description'      => '',
		),
		// Phone.
		array(
			'type'             => 'nectar_numerical',
			'class'            => '',
			'group'            => $design_group,
			'heading'          => esc_html__( 'Translate Y', 'cph-elements' ),
			'value'            => '',
			'edit_field_class' => 'col-md-6 phone row-transform-device-group',
			'param_name'       => 'translate_y_phone',
			'description'      => '',
		),
		array(
			'type'             => 'nectar_numerical',
			'class'            => '',
			'group'            => $design_group,
			'heading'          => esc_html__( 'Translate X', 'cph-elements' ),
			'value'            => '',
			'edit_field_class' => 'col-md-6 col-md-6-last phone row-transform-device-group',
			'param_name'       => 'translate_x_phone',
			'description'      => '',
		),
		array(
			'type'             => 'nectar_range_slider',
			'group'            => $design_group,
			'heading'          => esc_html__( 'Scale', 'cph-elements' ),
			'param_name'       => 'scale_phone',
			'value'            => '1',
			'options'          => array(
				'min'    => '0',
				'max'    => '2',
				'step'   => '0.01',
				'suffix' => 'x',
			),
			'edit_field_class' => 'col-md-6 phone row-transform-device-group',
			'description'      => '',
		),
		array(
			'type'             => 'nectar_angle_selection',
			'class'            => '',
			'group'            => $design_group,
			'edit_field_class' => 'col-md-6 col-md-6-last phone row-transform-device-group',
			'heading'          => "<span class='attr-title'>" . esc_html__( 'Rotate', 'cph-elements' ) . '</span>',
			'param_name'       => 'rotate_phone',
			'value'            => '',
			'description'      => '',
		),
	);

	$params = array_merge( $params, $transform_params );
}

// Standard WPBakery extras.
$params = array_merge(
	$params,
	array(
		array(
			'type'       => 'css_editor',
			'heading'    => esc_html__( 'Css', 'cph-elements' ),
			'param_name' => 'css',
			'group'      => esc_html__( 'Design Options', 'cph-elements' ),
		),
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Extra class name', 'cph-elements' ),
			'param_name'  => 'el_class',
			'description' => esc_html__( 'Add an extra class name and refer to it from custom CSS.', 'cph-elements' ),
		),
		array(
			'type'        => 'el_id',
			'heading'     => esc_html__( 'Element ID', 'cph-elements' ),
			'param_name'  => 'el_id',
			'description' => sprintf(
				/* translators: %s: anchor docs link */
				esc_html__( 'Enter an optional unique element ID. %s', 'cph-elements' ),
				'<a href="https://en.support.wordpress.com/splittesting-and-page-jumps/" target="_blank">' . esc_html__( 'Learn more.', 'cph-elements' ) . '</a>'
			),
		),
	)
);

return array(
	'name'        => esc_html__( 'Arrow Button', 'cph-elements' ),
	'base'        => 'cph_arrow_button',
	'icon'        => 'icon-wpb-arrow',
	'category'    => esc_html__( 'Clear pH Elements', 'cph-elements' ),
	'description' => esc_html__( 'Pill-shaped outlined arrow link button.', 'cph-elements' ),
	'params'      => $params,
);
