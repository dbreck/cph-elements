<?php
/**
 * CPH Icon Grid - WPBakery Element Map
 *
 * Defines the element configuration and parameters for the WPBakery page builder.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Column options.
$column_options = array(
	esc_html__( '2 Columns', 'cph-elements' ) => '2',
	esc_html__( '3 Columns', 'cph-elements' ) => '3',
	esc_html__( '4 Columns', 'cph-elements' ) => '4',
	esc_html__( '5 Columns', 'cph-elements' ) => '5',
	esc_html__( '6 Columns', 'cph-elements' ) => '6',
);

// Icon preset options (16 built-in icons + Custom).
$icon_presets = array(
	esc_html__( '-- Select Icon --', 'cph-elements' ) => '',
	esc_html__( 'Marina', 'cph-elements' )            => 'icn-marina',
	esc_html__( 'Pools', 'cph-elements' )             => 'icn-pools',
	esc_html__( 'Gyms', 'cph-elements' )              => 'icn-gyms',
	esc_html__( 'Spa', 'cph-elements' )               => 'icn-spa',
	esc_html__( 'Simulator', 'cph-elements' )         => 'icn-simulator',
	esc_html__( 'Dog Park', 'cph-elements' )          => 'icn-dogpark',
	esc_html__( 'Trails', 'cph-elements' )            => 'icn-trails',
	esc_html__( 'Adult Pool', 'cph-elements' )        => 'icn-adult-pool',
	esc_html__( 'Cabanas', 'cph-elements' )           => 'icn-cabanas',
	esc_html__( 'Fire Pits', 'cph-elements' )         => 'icn-fire-pits',
	esc_html__( 'Food Hall', 'cph-elements' )         => 'icn-food-hall',
	esc_html__( 'Food Truck Festivals', 'cph-elements' ) => 'icn-food-truck-festivals',
	esc_html__( 'Grilling Stations', 'cph-elements' ) => 'icn-grilling-stations',
	esc_html__( 'Lazy River', 'cph-elements' )        => 'icn-lazy-river',
	esc_html__( 'Pickleball', 'cph-elements' )        => 'icn-pickleball',
	esc_html__( 'Play Areas', 'cph-elements' )        => 'icn-play-areas',
	esc_html__( 'Custom (Media Library)', 'cph-elements' ) => 'custom',
);

return array(
	'name'        => esc_html__( 'CPH Icon Grid', 'cph-elements' ),
	'base'        => 'cph_icon_grid',
	'icon'        => 'icon-wpb-application-icon-large',
	'category'    => esc_html__( 'Clear pH Elements', 'cph-elements' ),
	'description' => esc_html__( 'Display icons with labels in a configurable grid layout.', 'cph-elements' ),
	'params'      => array(

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

		// Desktop Columns.
		array(
			'type'             => 'dropdown',
			'heading'          => '<span class="group-title">' . esc_html__( 'Number of Columns', 'cph-elements' ) . '</span>',
			'param_name'       => 'columns',
			'value'            => $column_options,
			'std'              => '5',
			'admin_label'      => true,
			'edit_field_class' => 'vc_col-sm-12 desktop icon-grid-columns-device-group',
			'description'      => esc_html__( 'Number of columns in the grid.', 'cph-elements' ),
		),

		// Tablet Columns.
		array(
			'type'             => 'dropdown',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Columns', 'cph-elements' ) . '</span>',
			'param_name'       => 'columns_tablet',
			'value'            => array_merge( array( esc_html__( 'Inherit', 'cph-elements' ) => '' ), $column_options ),
			'std'              => '',
			'edit_field_class' => 'vc_col-sm-12 tablet icon-grid-columns-device-group',
			'description'      => '',
		),

		// Phone Columns.
		array(
			'type'             => 'dropdown',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Columns', 'cph-elements' ) . '</span>',
			'param_name'       => 'columns_phone',
			'value'            => array_merge( array( esc_html__( 'Inherit', 'cph-elements' ) => '' ), $column_options ),
			'std'              => '',
			'edit_field_class' => 'vc_col-sm-12 phone icon-grid-columns-device-group',
			'description'      => '',
		),

		// Desktop Cell Height.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="group-title">' . esc_html__( 'Cell Height', 'cph-elements' ) . '</span>',
			'param_name'       => 'cell_height',
			'value'            => '175px',
			'edit_field_class' => 'vc_col-sm-12 desktop icon-grid-cell-height-device-group',
			'description'      => esc_html__( 'Fixed height for each grid cell (e.g., 175px).', 'cph-elements' ),
		),

		// Tablet Cell Height.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Height', 'cph-elements' ) . '</span>',
			'param_name'       => 'cell_height_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 tablet icon-grid-cell-height-device-group',
			'description'      => '',
		),

		// Phone Cell Height.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Height', 'cph-elements' ) . '</span>',
			'param_name'       => 'cell_height_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 phone icon-grid-cell-height-device-group',
			'description'      => '',
		),

		// Desktop Gap (between icon and label).
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="group-title">' . esc_html__( 'Gap Between Icon and Label', 'cph-elements' ) . '</span>',
			'param_name'       => 'gap',
			'value'            => '30px',
			'edit_field_class' => 'vc_col-sm-12 desktop icon-grid-gap-device-group',
			'description'      => esc_html__( 'Vertical space between icon and label (e.g., 30px).', 'cph-elements' ),
		),

		// Tablet Gap.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Gap', 'cph-elements' ) . '</span>',
			'param_name'       => 'gap_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 tablet icon-grid-gap-device-group',
			'description'      => '',
		),

		// Phone Gap.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Gap', 'cph-elements' ) . '</span>',
			'param_name'       => 'gap_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 phone icon-grid-gap-device-group',
			'description'      => '',
		),

		// Desktop Row Gap.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="group-title">' . esc_html__( 'Gap Between Rows', 'cph-elements' ) . '</span>',
			'param_name'       => 'row_gap',
			'value'            => '0px',
			'edit_field_class' => 'vc_col-sm-12 desktop icon-grid-row-gap-device-group',
			'description'      => esc_html__( 'Vertical space between grid rows (e.g., 30px).', 'cph-elements' ),
		),

		// Tablet Row Gap.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Row Gap', 'cph-elements' ) . '</span>',
			'param_name'       => 'row_gap_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 tablet icon-grid-row-gap-device-group',
			'description'      => '',
		),

		// Phone Row Gap.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Row Gap', 'cph-elements' ) . '</span>',
			'param_name'       => 'row_gap_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 phone icon-grid-row-gap-device-group',
			'description'      => '',
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * ICON ITEMS (REPEATER)
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Icons', 'cph-elements' ),
			'param_name' => 'group_header_icons',
		),

		array(
			'type'        => 'param_group',
			'heading'     => esc_html__( 'Icon Items', 'cph-elements' ),
			'param_name'  => 'items',
			'description' => esc_html__( 'Add icons with labels to display in the grid.', 'cph-elements' ),
			'params'      => array(
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Icon', 'cph-elements' ),
					'param_name'  => 'icon_preset',
					'value'       => $icon_presets,
					'std'         => '',
					'admin_label' => true,
					'description' => esc_html__( 'Select a built-in icon or choose Custom to upload your own.', 'cph-elements' ),
				),
				array(
					'type'        => 'attach_image',
					'heading'     => esc_html__( 'Custom Icon', 'cph-elements' ),
					'param_name'  => 'custom_icon',
					'dependency'  => array(
						'element' => 'icon_preset',
						'value'   => array( 'custom' ),
					),
					'description' => esc_html__( 'Upload a custom icon from the Media Library (SVG recommended).', 'cph-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Label', 'cph-elements' ),
					'param_name'  => 'label',
					'admin_label' => true,
					'description' => esc_html__( 'The label text displayed below the icon (e.g., "MARINA").', 'cph-elements' ),
				),
			),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * ICON STYLING
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Icon Styling', 'cph-elements' ),
			'param_name' => 'group_header_icon_style',
		),

		// Desktop Icon Max Height.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="group-title">' . esc_html__( 'Icon Max Height', 'cph-elements' ) . '</span>',
			'param_name'       => 'icon_max_height',
			'value'            => '100px',
			'edit_field_class' => 'vc_col-sm-12 desktop icon-grid-icon-height-device-group',
			'description'      => esc_html__( 'Maximum height for icons (e.g., 100px).', 'cph-elements' ),
		),

		// Tablet Icon Max Height.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Height', 'cph-elements' ) . '</span>',
			'param_name'       => 'icon_max_height_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 tablet icon-grid-icon-height-device-group',
			'description'      => '',
		),

		// Phone Icon Max Height.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Height', 'cph-elements' ) . '</span>',
			'param_name'       => 'icon_max_height_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 phone icon-grid-icon-height-device-group',
			'description'      => '',
		),

		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Icon Color', 'cph-elements' ),
			'param_name'  => 'icon_color',
			'value'       => '#000000',
			'description' => esc_html__( 'Color for SVG icons using currentColor.', 'cph-elements' ),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * TYPOGRAPHY
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Typography', 'cph-elements' ),
			'param_name' => 'group_header_typography',
		),

		// Desktop Label Font Size.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="group-title">' . esc_html__( 'Label Font Size', 'cph-elements' ) . '</span>',
			'param_name'       => 'label_font_size',
			'value'            => '28px',
			'edit_field_class' => 'vc_col-sm-12 desktop icon-grid-font-size-device-group',
			'description'      => esc_html__( 'Font size for labels (e.g., 28px).', 'cph-elements' ),
		),

		// Tablet Label Font Size.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Size', 'cph-elements' ) . '</span>',
			'param_name'       => 'label_font_size_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 tablet icon-grid-font-size-device-group',
			'description'      => '',
		),

		// Phone Label Font Size.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Size', 'cph-elements' ) . '</span>',
			'param_name'       => 'label_font_size_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 phone icon-grid-font-size-device-group',
			'description'      => '',
		),

		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Label Color', 'cph-elements' ),
			'param_name'  => 'label_color',
			'value'       => '#000000',
			'description' => esc_html__( 'Color for label text.', 'cph-elements' ),
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
