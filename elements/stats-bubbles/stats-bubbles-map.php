<?php
/**
 * CPH Stats Bubbles - WPBakery Element Map
 *
 * Defines the element configuration and parameters for the WPBakery page builder.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Layout pattern options.
$layout_patterns = array(
	esc_html__( 'Diagonal Cascade', 'cph-elements' )  => 'diagonal',
	esc_html__( 'Scattered', 'cph-elements' )         => 'scattered',
	esc_html__( 'Arc', 'cph-elements' )               => 'arc',
	esc_html__( 'Staggered Rows', 'cph-elements' )    => 'staggered',
	esc_html__( 'Random', 'cph-elements' )            => 'random',
);

// Bubble size options.
$bubble_sizes = array(
	esc_html__( 'Small', 'cph-elements' )  => 'small',
	esc_html__( 'Medium', 'cph-elements' ) => 'medium',
	esc_html__( 'Large', 'cph-elements' )  => 'large',
);

// Animation easing options.
$easing_options = array(
	esc_html__( 'Power2 Out', 'cph-elements' )   => 'power2.out',
	esc_html__( 'Power3 Out', 'cph-elements' )   => 'power3.out',
	esc_html__( 'Power4 Out', 'cph-elements' )   => 'power4.out',
	esc_html__( 'Expo Out', 'cph-elements' )     => 'expo.out',
	esc_html__( 'Circ Out', 'cph-elements' )     => 'circ.out',
	esc_html__( 'Back Out', 'cph-elements' )     => 'back.out',
	esc_html__( 'Elastic Out', 'cph-elements' )  => 'elastic.out',
);

return array(
	'name'        => esc_html__( 'CPH Stats Bubbles', 'cph-elements' ),
	'base'        => 'cph_stats_bubbles',
	'icon'        => 'icon-wpb-vc_pie',
	'category'    => esc_html__( 'Clear pH Elements', 'cph-elements' ),
	'description' => esc_html__( 'Display statistics in organic floating bubbles.', 'cph-elements' ),
	'params'      => array(

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * BUBBLE ITEMS (REPEATER)
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Bubbles', 'cph-elements' ),
			'param_name' => 'group_header_bubbles',
		),

		array(
			'type'        => 'param_group',
			'heading'     => esc_html__( 'Bubble Items', 'cph-elements' ),
			'param_name'  => 'bubbles',
			'description' => esc_html__( 'Add statistics to display in bubbles.', 'cph-elements' ),
			'params'      => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Value', 'cph-elements' ),
					'param_name'  => 'value',
					'admin_label' => true,
					'description' => esc_html__( 'The statistic value (e.g., "16,902", "4th", "45.4%").', 'cph-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Label', 'cph-elements' ),
					'param_name'  => 'label',
					'admin_label' => true,
					'description' => esc_html__( 'The label below the value (e.g., "POPULATION").', 'cph-elements' ),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Size', 'cph-elements' ),
					'param_name'  => 'size',
					'value'       => $bubble_sizes,
					'std'         => 'medium',
					'description' => esc_html__( 'Bubble size relative to min/max settings.', 'cph-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_html__( 'Color Override', 'cph-elements' ),
					'param_name'  => 'color_override',
					'value'       => '',
					'description' => esc_html__( 'Override the global bubble color for this bubble.', 'cph-elements' ),
				),
			),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * LAYOUT SETTINGS
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Layout', 'cph-elements' ),
			'param_name' => 'group_header_layout',
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Layout Pattern', 'cph-elements' ),
			'param_name'  => 'layout_pattern',
			'value'       => $layout_patterns,
			'std'         => 'diagonal',
			'admin_label' => true,
			'description' => esc_html__( 'How bubbles are arranged in the container.', 'cph-elements' ),
		),

		// Desktop Container Height.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="group-title">' . esc_html__( 'Container Height', 'cph-elements' ) . '</span>',
			'param_name'       => 'container_height',
			'value'            => '600px',
			'edit_field_class' => 'vc_col-sm-12 desktop stats-bubbles-height-device-group',
			'description'      => esc_html__( 'Height of the layout area (e.g., 600px, 80vh).', 'cph-elements' ),
		),

		// Tablet Container Height.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Height', 'cph-elements' ) . '</span>',
			'param_name'       => 'container_height_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 tablet stats-bubbles-height-device-group',
			'description'      => '',
		),

		// Phone Container Height.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Height', 'cph-elements' ) . '</span>',
			'param_name'       => 'container_height_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 phone stats-bubbles-height-device-group',
			'description'      => '',
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Min Bubble Size', 'cph-elements' ),
			'param_name'  => 'min_bubble_size',
			'value'       => '150px',
			'description' => esc_html__( 'Minimum bubble diameter (used for "Small" size).', 'cph-elements' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Max Bubble Size', 'cph-elements' ),
			'param_name'  => 'max_bubble_size',
			'value'       => '350px',
			'description' => esc_html__( 'Maximum bubble diameter (used for "Large" size).', 'cph-elements' ),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * STYLE SETTINGS
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Style', 'cph-elements' ),
			'param_name' => 'group_header_style',
		),

		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Global Bubble Color', 'cph-elements' ),
			'param_name'  => 'bubble_color',
			'value'       => '#E14A13',
			'description' => esc_html__( 'Default background color for all bubbles.', 'cph-elements' ),
		),

		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Text Color', 'cph-elements' ),
			'param_name'  => 'text_color',
			'value'       => '#ffffff',
			'description' => esc_html__( 'Color for value and label text.', 'cph-elements' ),
		),

		// Desktop Value Font Size.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="group-title">' . esc_html__( 'Value Font Size', 'cph-elements' ) . '</span>',
			'param_name'       => 'value_font_size',
			'value'            => '48px',
			'edit_field_class' => 'vc_col-sm-12 desktop stats-bubbles-value-size-device-group',
			'description'      => esc_html__( 'Font size for statistic values.', 'cph-elements' ),
		),

		// Tablet Value Font Size.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Size', 'cph-elements' ) . '</span>',
			'param_name'       => 'value_font_size_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 tablet stats-bubbles-value-size-device-group',
			'description'      => '',
		),

		// Phone Value Font Size.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Size', 'cph-elements' ) . '</span>',
			'param_name'       => 'value_font_size_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 phone stats-bubbles-value-size-device-group',
			'description'      => '',
		),

		// Desktop Label Font Size.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="group-title">' . esc_html__( 'Label Font Size', 'cph-elements' ) . '</span>',
			'param_name'       => 'label_font_size',
			'value'            => '20px',
			'edit_field_class' => 'vc_col-sm-12 desktop stats-bubbles-label-size-device-group',
			'description'      => esc_html__( 'Font size for labels.', 'cph-elements' ),
		),

		// Tablet Label Font Size.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Size', 'cph-elements' ) . '</span>',
			'param_name'       => 'label_font_size_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 tablet stats-bubbles-label-size-device-group',
			'description'      => '',
		),

		// Phone Label Font Size.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Size', 'cph-elements' ) . '</span>',
			'param_name'       => 'label_font_size_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 phone stats-bubbles-label-size-device-group',
			'description'      => '',
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Enable Shadow', 'cph-elements' ),
			'param_name'  => 'enable_shadow',
			'value'       => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'std'         => 'yes',
			'description' => esc_html__( 'Add subtle drop shadow to bubbles.', 'cph-elements' ),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * ANIMATION SETTINGS
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Animation', 'cph-elements' ),
			'param_name' => 'group_header_animation',
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Enable Count-Up Animation', 'cph-elements' ),
			'param_name'  => 'enable_countup',
			'value'       => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'std'         => 'yes',
			'description' => esc_html__( 'Animate numeric values from 0 on scroll.', 'cph-elements' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Animation Duration', 'cph-elements' ),
			'param_name'  => 'animation_duration',
			'value'       => '2',
			'dependency'  => array(
				'element' => 'enable_countup',
				'value'   => array( 'yes' ),
			),
			'description' => esc_html__( 'Duration in seconds for count-up animation.', 'cph-elements' ),
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Animation Easing', 'cph-elements' ),
			'param_name'  => 'animation_easing',
			'value'       => $easing_options,
			'std'         => 'power2.out',
			'dependency'  => array(
				'element' => 'enable_countup',
				'value'   => array( 'yes' ),
			),
			'description' => esc_html__( 'Easing function for the animation.', 'cph-elements' ),
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Enable Text Scramble', 'cph-elements' ),
			'param_name'  => 'enable_scramble',
			'value'       => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'std'         => '',
			'dependency'  => array(
				'element' => 'enable_countup',
				'value'   => array( 'yes' ),
			),
			'description' => esc_html__( 'Scramble text characters (like "th", "st") during animation.', 'cph-elements' ),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * EXTRA
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Extra', 'cph-elements' ),
			'param_name' => 'group_header_extra',
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Extra Class Name', 'cph-elements' ),
			'param_name'  => 'el_class',
			'value'       => '',
			'description' => esc_html__( 'Add custom CSS class(es) to the wrapper element.', 'cph-elements' ),
		),

	), // End params.
);
