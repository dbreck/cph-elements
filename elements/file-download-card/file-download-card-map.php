<?php
/**
 * File Download Card - WPBakery Element Map
 *
 * Defines the WPBakery element parameters for the File Download Card.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	'name'        => esc_html__( 'File Download Card', 'cph-elements' ),
	'base'        => 'file_download_card',
	'icon'        => 'icon-wpb-application-icon-large',
	'category'    => esc_html__( 'Clear pH Elements', 'cph-elements' ),
	'description' => esc_html__( 'Downloadable file card with preview image', 'cph-elements' ),
	'params'      => array(

		// Content Tab.
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Title', 'cph-elements' ),
			'param_name'  => 'title',
			'description' => esc_html__( 'Card title. Leave empty to auto-populate from filename.', 'cph-elements' ),
			'admin_label' => true,
		),
		array(
			'type'        => 'fws_image',
			'heading'     => esc_html__( 'Preview Image', 'cph-elements' ),
			'param_name'  => 'image',
			'description' => esc_html__( 'Image displayed at the top of the card (4:3 aspect ratio).', 'cph-elements' ),
		),
		array(
			'type'        => 'fdc_file_picker',
			'heading'     => esc_html__( 'Downloadable File', 'cph-elements' ),
			'param_name'  => 'file',
			'description' => esc_html__( 'Select the file to be downloaded (PDF, image, etc.) from the media library.', 'cph-elements' ),
		),
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Button Text', 'cph-elements' ),
			'param_name'  => 'button_text',
			'value'       => 'Download',
			'description' => esc_html__( 'Text for the download button.', 'cph-elements' ),
		),

		// Display Options Tab.
		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Image Aspect Ratio', 'cph-elements' ),
			'param_name'  => 'aspect_ratio',
			'value'       => array(
				esc_html__( '4:3', 'cph-elements' )              => '4-3',
				esc_html__( '16:9', 'cph-elements' )             => '16-9',
				esc_html__( '3:4 (Portrait)', 'cph-elements' )   => '3-4',
			),
			'std'         => '4-3',
			'description' => esc_html__( 'Aspect ratio for the preview image.', 'cph-elements' ),
			'group'       => esc_html__( 'Display Options', 'cph-elements' ),
		),
		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Title Tag', 'cph-elements' ),
			'param_name'  => 'title_tag',
			'value'       => array(
				esc_html__( 'H3', 'cph-elements' ) => 'h3',
				esc_html__( 'H2', 'cph-elements' ) => 'h2',
				esc_html__( 'H4', 'cph-elements' ) => 'h4',
				esc_html__( 'H5', 'cph-elements' ) => 'h5',
				esc_html__( 'H6', 'cph-elements' ) => 'h6',
			),
			'std'         => 'h3',
			'description' => esc_html__( 'HTML tag for the card title.', 'cph-elements' ),
			'group'       => esc_html__( 'Display Options', 'cph-elements' ),
		),
		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Show Meta Info', 'cph-elements' ),
			'param_name'  => 'show_meta',
			'value'       => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'std'         => 'yes',
			'description' => esc_html__( 'Display file type and size below the title.', 'cph-elements' ),
			'group'       => esc_html__( 'Display Options', 'cph-elements' ),
		),
		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Border Radius', 'cph-elements' ),
			'param_name'  => 'border_radius',
			'value'       => array(
				esc_html__( 'None', 'cph-elements' )         => 'none',
				esc_html__( 'Small (8px)', 'cph-elements' )  => 'sm',
				esc_html__( 'Medium (16px)', 'cph-elements' ) => 'md',
				esc_html__( 'Large (24px)', 'cph-elements' ) => 'lg',
			),
			'std'         => 'md',
			'group'       => esc_html__( 'Display Options', 'cph-elements' ),
		),
		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Shadow', 'cph-elements' ),
			'param_name'  => 'shadow',
			'value'       => array(
				esc_html__( 'Enabled', 'cph-elements' )  => 'enabled',
				esc_html__( 'Disabled', 'cph-elements' ) => 'disabled',
			),
			'std'         => 'enabled',
			'description' => esc_html__( 'Show drop shadow on card and hover effect.', 'cph-elements' ),
			'group'       => esc_html__( 'Display Options', 'cph-elements' ),
		),
		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Shadow Strength', 'cph-elements' ),
			'param_name'  => 'shadow_strength',
			'value'       => array(
				esc_html__( 'Light', 'cph-elements' )  => 'light',
				esc_html__( 'Medium', 'cph-elements' ) => 'medium',
				esc_html__( 'Strong', 'cph-elements' ) => 'strong',
			),
			'std'         => 'medium',
			'dependency'  => array(
				'element' => 'shadow',
				'value'   => 'enabled',
			),
			'group'       => esc_html__( 'Display Options', 'cph-elements' ),
		),
		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Shadow Color', 'cph-elements' ),
			'param_name'  => 'shadow_color',
			'description' => esc_html__( 'Color for the drop shadow.', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'shadow',
				'value'   => 'enabled',
			),
			'group'       => esc_html__( 'Display Options', 'cph-elements' ),
		),

		// Color Options Tab.
		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Color Scheme', 'cph-elements' ),
			'param_name'  => 'color_scheme',
			'value'       => array(
				esc_html__( 'Light', 'cph-elements' )         => 'light',
				esc_html__( 'Dark', 'cph-elements' )          => 'dark',
				esc_html__( 'Accent Color', 'cph-elements' )  => 'accent',
				esc_html__( 'Extra Color 1', 'cph-elements' ) => 'extra-1',
				esc_html__( 'Extra Color 2', 'cph-elements' ) => 'extra-2',
				esc_html__( 'Extra Color 3', 'cph-elements' ) => 'extra-3',
				esc_html__( 'Custom', 'cph-elements' )        => 'custom',
			),
			'std'         => 'light',
			'description' => esc_html__( 'Choose a color scheme for the card.', 'cph-elements' ),
			'group'       => esc_html__( 'Color Options', 'cph-elements' ),
		),
		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Custom Background Color', 'cph-elements' ),
			'param_name'  => 'custom_bg_color',
			'description' => esc_html__( 'Background color for the card.', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'color_scheme',
				'value'   => 'custom',
			),
			'group'       => esc_html__( 'Color Options', 'cph-elements' ),
		),
		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Custom Text Color', 'cph-elements' ),
			'param_name'  => 'custom_text_color',
			'description' => esc_html__( 'Text color for title and meta info.', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'color_scheme',
				'value'   => 'custom',
			),
			'group'       => esc_html__( 'Color Options', 'cph-elements' ),
		),
		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Custom Button Color', 'cph-elements' ),
			'param_name'  => 'custom_button_color',
			'description' => esc_html__( 'Background color for the download button.', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'color_scheme',
				'value'   => 'custom',
			),
			'group'       => esc_html__( 'Color Options', 'cph-elements' ),
		),
	),
);
