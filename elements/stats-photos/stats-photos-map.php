<?php
/**
 * CPH Stats Photos - WPBakery Element Map
 *
 * Defines the element configuration and parameters for the WPBakery page builder.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Text alignment options.
$text_alignment = array(
	esc_html__( 'Center', 'cph-elements' ) => 'center',
	esc_html__( 'Left', 'cph-elements' )   => 'left',
	esc_html__( 'Right', 'cph-elements' )  => 'right',
);

// Text placement options.
$text_placement = array(
	esc_html__( 'Bottom', 'cph-elements' ) => 'bottom',
	esc_html__( 'Middle', 'cph-elements' ) => 'middle',
	esc_html__( 'Top', 'cph-elements' )    => 'top',
);

// Image alignment options (object-position).
$image_alignment = array(
	esc_html__( 'Center Center', 'cph-elements' ) => 'center center',
	esc_html__( 'Top Center', 'cph-elements' )    => 'top center',
	esc_html__( 'Top Left', 'cph-elements' )      => 'top left',
	esc_html__( 'Top Right', 'cph-elements' )     => 'top right',
	esc_html__( 'Center Left', 'cph-elements' )   => 'center left',
	esc_html__( 'Center Right', 'cph-elements' )  => 'center right',
	esc_html__( 'Bottom Center', 'cph-elements' ) => 'bottom center',
	esc_html__( 'Bottom Left', 'cph-elements' )   => 'bottom left',
	esc_html__( 'Bottom Right', 'cph-elements' )  => 'bottom right',
);

return array(
	'name'        => esc_html__( 'CPH Stats Photos', 'cph-elements' ),
	'base'        => 'cph_stats_photos',
	'icon'        => 'icon-wpb-images-stack',
	'category'    => esc_html__( 'Clear pH Elements', 'cph-elements' ),
	'description' => esc_html__( 'Photo grid with statistics overlay on hover or random reveal.', 'cph-elements' ),
	'params'      => array(

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * PHOTO ITEMS (REPEATER)
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Photos', 'cph-elements' ),
			'param_name' => 'group_header_photos',
		),

		array(
			'type'        => 'param_group',
			'heading'     => esc_html__( 'Photo Items', 'cph-elements' ),
			'param_name'  => 'photos',
			'description' => esc_html__( 'Add photos with optional statistics overlay.', 'cph-elements' ),
			'params'      => array(
				array(
					'type'        => 'attach_image',
					'heading'     => esc_html__( 'Photo', 'cph-elements' ),
					'param_name'  => 'image',
					'description' => esc_html__( 'Select or upload a photo.', 'cph-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Value', 'cph-elements' ),
					'param_name'  => 'value',
					'admin_label' => true,
					'description' => esc_html__( 'The statistic value (e.g., "7,339", "43.7%"). Leave empty for photo-only.', 'cph-elements' ),
				),
				array(
					'type'        => 'textarea',
					'heading'     => esc_html__( 'Label', 'cph-elements' ),
					'param_name'  => 'label',
					'description' => esc_html__( 'The label below the value (e.g., "HOMES SOLD MONTHLY"). Use line breaks for multiple lines.', 'cph-elements' ),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Image Alignment', 'cph-elements' ),
					'param_name'  => 'image_position',
					'value'       => array_merge(
						array( esc_html__( 'Use Global Setting', 'cph-elements' ) => '' ),
						$image_alignment
					),
					'std'         => '',
					'description' => esc_html__( 'Override the global image alignment for this photo.', 'cph-elements' ),
				),
			),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * BEHAVIOR
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Behavior', 'cph-elements' ),
			'param_name' => 'group_header_behavior',
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Show Stats on Hover', 'cph-elements' ),
			'param_name'  => 'show_on_hover',
			'value'       => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'std'         => 'yes',
			'description' => esc_html__( 'Show statistics when hovering over a photo.', 'cph-elements' ),
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Show Stats Randomly', 'cph-elements' ),
			'param_name'  => 'show_randomly',
			'value'       => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'std'         => '',
			'description' => esc_html__( 'Randomly show/hide statistics on page load and at intervals.', 'cph-elements' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Random Interval (seconds)', 'cph-elements' ),
			'param_name'  => 'random_interval',
			'value'       => '3',
			'dependency'  => array(
				'element' => 'show_randomly',
				'value'   => array( 'yes' ),
			),
			'description' => esc_html__( 'How often to randomly change which stats are visible.', 'cph-elements' ),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * LAYOUT
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Layout', 'cph-elements' ),
			'param_name' => 'group_header_layout',
		),

		// Desktop Container Height.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="group-title">' . esc_html__( 'Container Height', 'cph-elements' ) . '</span>',
			'param_name'       => 'container_height',
			'value'            => '500px',
			'edit_field_class' => 'vc_col-sm-12 desktop stats-photos-height-device-group',
			'description'      => esc_html__( 'Height of the photo grid (e.g., 500px, 60vh).', 'cph-elements' ),
		),

		// Tablet Container Height.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Height', 'cph-elements' ) . '</span>',
			'param_name'       => 'container_height_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 tablet stats-photos-height-device-group',
			'description'      => '',
		),

		// Phone Container Height.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Height', 'cph-elements' ) . '</span>',
			'param_name'       => 'container_height_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 phone stats-photos-height-device-group',
			'description'      => '',
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Text Alignment', 'cph-elements' ),
			'param_name'  => 'text_alignment',
			'value'       => $text_alignment,
			'std'         => 'center',
			'description' => esc_html__( 'Horizontal alignment of the statistics text.', 'cph-elements' ),
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Text Placement', 'cph-elements' ),
			'param_name'  => 'text_placement',
			'value'       => $text_placement,
			'std'         => 'bottom',
			'description' => esc_html__( 'Vertical position of the statistics text.', 'cph-elements' ),
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Image Alignment (Global)', 'cph-elements' ),
			'param_name'  => 'image_alignment',
			'value'       => $image_alignment,
			'std'         => 'center center',
			'description' => esc_html__( 'Default focal point for all images. Can be overridden per photo.', 'cph-elements' ),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * STYLE
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Style', 'cph-elements' ),
			'param_name' => 'group_header_style',
		),

		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Overlay Color', 'cph-elements' ),
			'param_name'  => 'overlay_color',
			'value'       => '#000000',
			'description' => esc_html__( 'Color of the overlay when stats are shown.', 'cph-elements' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Overlay Opacity', 'cph-elements' ),
			'param_name'  => 'overlay_opacity',
			'value'       => '0.4',
			'description' => esc_html__( 'Opacity of the overlay (0 to 1).', 'cph-elements' ),
		),

		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Text Color', 'cph-elements' ),
			'param_name'  => 'text_color',
			'value'       => '#ffffff',
			'description' => esc_html__( 'Color of the statistics text.', 'cph-elements' ),
		),

		// Desktop Value Font Size.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="group-title">' . esc_html__( 'Value Font Size', 'cph-elements' ) . '</span>',
			'param_name'       => 'value_font_size',
			'value'            => '72px',
			'edit_field_class' => 'vc_col-sm-12 desktop stats-photos-value-size-device-group',
			'description'      => esc_html__( 'Font size for statistic values.', 'cph-elements' ),
		),

		// Tablet Value Font Size.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Size', 'cph-elements' ) . '</span>',
			'param_name'       => 'value_font_size_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 tablet stats-photos-value-size-device-group',
			'description'      => '',
		),

		// Phone Value Font Size.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Size', 'cph-elements' ) . '</span>',
			'param_name'       => 'value_font_size_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 phone stats-photos-value-size-device-group',
			'description'      => '',
		),

		// Desktop Label Font Size.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="group-title">' . esc_html__( 'Label Font Size', 'cph-elements' ) . '</span>',
			'param_name'       => 'label_font_size',
			'value'            => '16px',
			'edit_field_class' => 'vc_col-sm-12 desktop stats-photos-label-size-device-group',
			'description'      => esc_html__( 'Font size for labels.', 'cph-elements' ),
		),

		// Tablet Label Font Size.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Size', 'cph-elements' ) . '</span>',
			'param_name'       => 'label_font_size_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 tablet stats-photos-label-size-device-group',
			'description'      => '',
		),

		// Phone Label Font Size.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Size', 'cph-elements' ) . '</span>',
			'param_name'       => 'label_font_size_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 phone stats-photos-label-size-device-group',
			'description'      => '',
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
