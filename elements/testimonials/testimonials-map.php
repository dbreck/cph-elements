<?php
/**
 * CPH Testimonials - WPBakery Element Map
 *
 * Defines the element configuration and parameters for the WPBakery page builder.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Posts per page options.
$posts_per_page_options = array(
	esc_html__( '3', 'cph-elements' )       => '3',
	esc_html__( '6', 'cph-elements' )       => '6',
	esc_html__( '9', 'cph-elements' )       => '9',
	esc_html__( '12', 'cph-elements' )      => '12',
	esc_html__( 'All (-1)', 'cph-elements' ) => '-1',
);

// Order by options.
$orderby_options = array(
	esc_html__( 'Menu Order', 'cph-elements' )  => 'menu_order',
	esc_html__( 'Date', 'cph-elements' )         => 'date',
	esc_html__( 'Random', 'cph-elements' )       => 'rand',
);

// Icon mode options.
$icon_mode_options = array(
	esc_html__( 'Character', 'cph-elements' )  => 'character',
	esc_html__( 'Font Icon', 'cph-elements' )  => 'icon',
	esc_html__( 'None', 'cph-elements' )       => 'none',
);

// Transition style options.
$transition_style_options = array(
	esc_html__( 'Fade', 'cph-elements' )        => 'fade',
	esc_html__( 'Slide Left', 'cph-elements' )   => 'slide-left',
	esc_html__( 'Slide Right', 'cph-elements' )  => 'slide-right',
	esc_html__( 'Fade Up', 'cph-elements' )      => 'fade-up',
	esc_html__( 'Fade Down', 'cph-elements' )    => 'fade-down',
	esc_html__( 'Zoom Fade', 'cph-elements' )    => 'zoom-fade',
	esc_html__( 'Pinch Fade', 'cph-elements' )   => 'pinch-fade',
);

// Character tag options.
$icon_tag_options = array(
	esc_html__( 'h1', 'cph-elements' )   => 'h1',
	esc_html__( 'h2', 'cph-elements' )   => 'h2',
	esc_html__( 'h3', 'cph-elements' )   => 'h3',
	esc_html__( 'h4', 'cph-elements' )   => 'h4',
	esc_html__( 'h5', 'cph-elements' )   => 'h5',
	esc_html__( 'h6', 'cph-elements' )   => 'h6',
	esc_html__( 'p', 'cph-elements' )    => 'p',
	esc_html__( 'span', 'cph-elements' ) => 'span',
);

return array(
	'name'        => esc_html__( 'CPH Testimonials', 'cph-elements' ),
	'base'        => 'cph_testimonials',
	'icon'        => 'icon-wpb-toggle-small-expand',
	'category'    => esc_html__( 'Clear pH Elements', 'cph-elements' ),
	'description' => esc_html__( 'Fade slider with testimonial quotes, dot navigation, and optional button.', 'cph-elements' ),
	'params'      => array(

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * GENERAL TAB
		 * ─────────────────────────────────────────────────────────────────
		 */

		// Content header.
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Content', 'cph-elements' ),
			'param_name' => 'group_header_content',
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Posts Per Page', 'cph-elements' ),
			'param_name'  => 'posts_per_page',
			'value'       => $posts_per_page_options,
			'std'         => '-1',
			'admin_label' => true,
			'description' => esc_html__( 'Number of testimonials to display.', 'cph-elements' ),
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Order By', 'cph-elements' ),
			'param_name'  => 'orderby',
			'value'       => $orderby_options,
			'std'         => 'menu_order',
			'description' => esc_html__( 'How to order the testimonials.', 'cph-elements' ),
		),

		// Transition header.
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Transition', 'cph-elements' ),
			'param_name' => 'group_header_transition',
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Transition Style', 'cph-elements' ),
			'param_name'  => 'transition_style',
			'value'       => $transition_style_options,
			'std'         => 'fade',
			'description' => esc_html__( 'Animation style when transitioning between testimonials.', 'cph-elements' ),
		),

		// Decorative Icon header.
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Decorative Icon', 'cph-elements' ),
			'param_name' => 'group_header_icon',
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Icon Mode', 'cph-elements' ),
			'param_name'  => 'icon_mode',
			'value'       => $icon_mode_options,
			'std'         => 'character',
			'description' => esc_html__( 'Display a text character, font icon, or no icon.', 'cph-elements' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Character', 'cph-elements' ),
			'param_name'  => 'icon_character',
			'value'       => "\u{201C}",
			'dependency'  => array(
				'element' => 'icon_mode',
				'value'   => array( 'character' ),
			),
			'description' => esc_html__( 'Text character to display (e.g., a quotation mark).', 'cph-elements' ),
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Character Tag', 'cph-elements' ),
			'param_name'  => 'icon_tag',
			'value'       => $icon_tag_options,
			'std'         => 'h2',
			'dependency'  => array(
				'element' => 'icon_mode',
				'value'   => array( 'character' ),
			),
			'description' => esc_html__( 'HTML tag for the character.', 'cph-elements' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Character Font Family', 'cph-elements' ),
			'param_name'  => 'icon_font',
			'value'       => '',
			'dependency'  => array(
				'element' => 'icon_mode',
				'value'   => array( 'character' ),
			),
			'description' => esc_html__( 'Optional CSS font-family override (e.g., Georgia, serif).', 'cph-elements' ),
		),

		array(
			'type'        => 'iconpicker',
			'heading'     => esc_html__( 'Font Icon', 'cph-elements' ),
			'param_name'  => 'icon_fontawesome',
			'value'       => 'fa fa-quote-left',
			'settings'    => array(
				'emptyIcon'    => false,
				'iconsPerPage' => 100,
				'type'         => 'fontawesome',
			),
			'dependency'  => array(
				'element' => 'icon_mode',
				'value'   => array( 'icon' ),
			),
			'description' => esc_html__( 'Select a Font Awesome icon.', 'cph-elements' ),
		),

		// Icon Size — responsive.
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Icon Size', 'cph-elements' ),
			'param_name'       => 'icon_size',
			'value'            => '80px',
			'edit_field_class' => 'vc_col-sm-4',
			'dependency'       => array(
				'element'            => 'icon_mode',
				'value_not_equal_to' => array( 'none' ),
			),
			'description'      => esc_html__( 'Desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Tablet', 'cph-elements' ),
			'param_name'       => 'icon_size_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'dependency'       => array(
				'element'            => 'icon_mode',
				'value_not_equal_to' => array( 'none' ),
			),
			'description'      => esc_html__( 'Inherits desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Phone', 'cph-elements' ),
			'param_name'       => 'icon_size_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'dependency'       => array(
				'element'            => 'icon_mode',
				'value_not_equal_to' => array( 'none' ),
			),
			'description'      => esc_html__( 'Inherits tablet.', 'cph-elements' ),
		),

		// Button header.
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Button', 'cph-elements' ),
			'param_name' => 'group_header_button',
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Show Button', 'cph-elements' ),
			'param_name'  => 'show_button',
			'value'       => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'std'         => '',
			'description' => esc_html__( 'Display a CTA button below the testimonials.', 'cph-elements' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Button Text', 'cph-elements' ),
			'param_name'  => 'button_text',
			'value'       => 'Read More',
			'dependency'  => array(
				'element' => 'show_button',
				'value'   => array( 'yes' ),
			),
			'description' => esc_html__( 'Text displayed on the button.', 'cph-elements' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Button URL', 'cph-elements' ),
			'param_name'  => 'button_url',
			'value'       => '',
			'dependency'  => array(
				'element' => 'show_button',
				'value'   => array( 'yes' ),
			),
			'description' => esc_html__( 'Link destination for the button.', 'cph-elements' ),
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Open in New Tab', 'cph-elements' ),
			'param_name'  => 'button_new_tab',
			'value'       => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'std'         => '',
			'dependency'  => array(
				'element' => 'show_button',
				'value'   => array( 'yes' ),
			),
			'description' => esc_html__( 'Open the button link in a new browser tab.', 'cph-elements' ),
		),

		// Autoplay header.
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Autoplay', 'cph-elements' ),
			'param_name' => 'group_header_autoplay',
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Autoplay', 'cph-elements' ),
			'param_name'  => 'autoplay',
			'value'       => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'std'         => 'yes',
			'description' => esc_html__( 'Automatically cycle through testimonials.', 'cph-elements' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Speed (seconds)', 'cph-elements' ),
			'param_name'  => 'autoplay_speed',
			'value'       => '6',
			'dependency'  => array(
				'element' => 'autoplay',
				'value'   => array( 'yes' ),
			),
			'description' => esc_html__( 'Seconds between slide transitions.', 'cph-elements' ),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * STYLE TAB
		 * ─────────────────────────────────────────────────────────────────
		 */

		// Colors header.
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Colors', 'cph-elements' ),
			'param_name' => 'group_header_colors',
			'group'      => esc_html__( 'Style', 'cph-elements' ),
		),

		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Background Color', 'cph-elements' ),
			'param_name'  => 'bg_color',
			'value'       => '#232323',
			'group'       => esc_html__( 'Style', 'cph-elements' ),
			'description' => esc_html__( 'Background color of the testimonials section.', 'cph-elements' ),
		),

		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Text Color', 'cph-elements' ),
			'param_name'  => 'text_color',
			'value'       => '#ffffff',
			'group'       => esc_html__( 'Style', 'cph-elements' ),
			'description' => esc_html__( 'Color for quote text and attribution.', 'cph-elements' ),
		),

		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Accent Color', 'cph-elements' ),
			'param_name'  => 'accent_color',
			'value'       => '#87A80E',
			'group'       => esc_html__( 'Style', 'cph-elements' ),
			'description' => esc_html__( 'Color for the icon and active dot.', 'cph-elements' ),
		),

		// Typography header.
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Typography', 'cph-elements' ),
			'param_name' => 'group_header_typography',
			'group'      => esc_html__( 'Style', 'cph-elements' ),
		),

		// Quote Font Size — responsive.
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Quote Font Size', 'cph-elements' ),
			'param_name'       => 'quote_font_size',
			'value'            => '40px',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Style', 'cph-elements' ),
			'description'      => esc_html__( 'Desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Tablet', 'cph-elements' ),
			'param_name'       => 'quote_font_size_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Style', 'cph-elements' ),
			'description'      => esc_html__( 'Inherits desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Phone', 'cph-elements' ),
			'param_name'       => 'quote_font_size_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Style', 'cph-elements' ),
			'description'      => esc_html__( 'Inherits tablet.', 'cph-elements' ),
		),

		// Quote Max Width — responsive.
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Quote Max Width', 'cph-elements' ),
			'param_name'       => 'quote_max_width',
			'value'            => '1604px',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Style', 'cph-elements' ),
			'description'      => esc_html__( 'Desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Tablet', 'cph-elements' ),
			'param_name'       => 'quote_max_width_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Style', 'cph-elements' ),
			'description'      => esc_html__( 'Inherits desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Phone', 'cph-elements' ),
			'param_name'       => 'quote_max_width_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Style', 'cph-elements' ),
			'description'      => esc_html__( 'Inherits tablet.', 'cph-elements' ),
		),

		// Attribution Font Size — responsive.
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Attribution Font Size', 'cph-elements' ),
			'param_name'       => 'attribution_font_size',
			'value'            => '22px',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Style', 'cph-elements' ),
			'description'      => esc_html__( 'Desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Tablet', 'cph-elements' ),
			'param_name'       => 'attribution_font_size_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Style', 'cph-elements' ),
			'description'      => esc_html__( 'Inherits desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Phone', 'cph-elements' ),
			'param_name'       => 'attribution_font_size_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Style', 'cph-elements' ),
			'description'      => esc_html__( 'Inherits tablet.', 'cph-elements' ),
		),

		// Spacing header.
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Spacing', 'cph-elements' ),
			'param_name' => 'group_header_spacing',
			'group'      => esc_html__( 'Style', 'cph-elements' ),
		),

		// Section Height — responsive.
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Section Height', 'cph-elements' ),
			'param_name'       => 'slider_height',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Style', 'cph-elements' ),
			'description'      => esc_html__( 'Desktop. Empty = auto-size.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Tablet', 'cph-elements' ),
			'param_name'       => 'slider_height_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Style', 'cph-elements' ),
			'description'      => esc_html__( 'Inherits desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Phone', 'cph-elements' ),
			'param_name'       => 'slider_height_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Style', 'cph-elements' ),
			'description'      => esc_html__( 'Inherits tablet.', 'cph-elements' ),
		),

		// Icon Bottom Margin — responsive.
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Icon Bottom Margin', 'cph-elements' ),
			'param_name'       => 'icon_margin_bottom',
			'value'            => '10px',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Style', 'cph-elements' ),
			'dependency'       => array(
				'element'            => 'icon_mode',
				'value_not_equal_to' => array( 'none' ),
			),
			'description'      => esc_html__( 'Desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Tablet', 'cph-elements' ),
			'param_name'       => 'icon_margin_bottom_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Style', 'cph-elements' ),
			'dependency'       => array(
				'element'            => 'icon_mode',
				'value_not_equal_to' => array( 'none' ),
			),
			'description'      => esc_html__( 'Inherits desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Phone', 'cph-elements' ),
			'param_name'       => 'icon_margin_bottom_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Style', 'cph-elements' ),
			'dependency'       => array(
				'element'            => 'icon_mode',
				'value_not_equal_to' => array( 'none' ),
			),
			'description'      => esc_html__( 'Inherits tablet.', 'cph-elements' ),
		),

		// Quote Bottom Margin — responsive.
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Quote Bottom Margin', 'cph-elements' ),
			'param_name'       => 'quote_margin_bottom',
			'value'            => '20px',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Style', 'cph-elements' ),
			'description'      => esc_html__( 'Desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Tablet', 'cph-elements' ),
			'param_name'       => 'quote_margin_bottom_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Style', 'cph-elements' ),
			'description'      => esc_html__( 'Inherits desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Phone', 'cph-elements' ),
			'param_name'       => 'quote_margin_bottom_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Style', 'cph-elements' ),
			'description'      => esc_html__( 'Inherits tablet.', 'cph-elements' ),
		),

		// Button Top Margin — responsive.
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Button Top Margin', 'cph-elements' ),
			'param_name'       => 'button_margin_top',
			'value'            => '30px',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Style', 'cph-elements' ),
			'dependency'       => array(
				'element' => 'show_button',
				'value'   => array( 'yes' ),
			),
			'description'      => esc_html__( 'Desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Tablet', 'cph-elements' ),
			'param_name'       => 'button_margin_top_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Style', 'cph-elements' ),
			'dependency'       => array(
				'element' => 'show_button',
				'value'   => array( 'yes' ),
			),
			'description'      => esc_html__( 'Inherits desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Phone', 'cph-elements' ),
			'param_name'       => 'button_margin_top_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Style', 'cph-elements' ),
			'dependency'       => array(
				'element' => 'show_button',
				'value'   => array( 'yes' ),
			),
			'description'      => esc_html__( 'Inherits tablet.', 'cph-elements' ),
		),

		// Dots header.
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Dots', 'cph-elements' ),
			'param_name' => 'group_header_dots',
			'group'      => esc_html__( 'Style', 'cph-elements' ),
		),

		// Dot Size — responsive.
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Dot Size', 'cph-elements' ),
			'param_name'       => 'dot_size',
			'value'            => '18px',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Style', 'cph-elements' ),
			'description'      => esc_html__( 'Desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Tablet', 'cph-elements' ),
			'param_name'       => 'dot_size_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Style', 'cph-elements' ),
			'description'      => esc_html__( 'Inherits desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Phone', 'cph-elements' ),
			'param_name'       => 'dot_size_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Style', 'cph-elements' ),
			'description'      => esc_html__( 'Inherits tablet.', 'cph-elements' ),
		),

		// Dot Gap — responsive.
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Dot Gap', 'cph-elements' ),
			'param_name'       => 'dot_gap',
			'value'            => '12px',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Style', 'cph-elements' ),
			'description'      => esc_html__( 'Desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Tablet', 'cph-elements' ),
			'param_name'       => 'dot_gap_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Style', 'cph-elements' ),
			'description'      => esc_html__( 'Inherits desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Phone', 'cph-elements' ),
			'param_name'       => 'dot_gap_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Style', 'cph-elements' ),
			'description'      => esc_html__( 'Inherits tablet.', 'cph-elements' ),
		),

		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Dot Inactive Color', 'cph-elements' ),
			'param_name'  => 'dot_inactive_color',
			'value'       => '#D9D9D9',
			'group'       => esc_html__( 'Style', 'cph-elements' ),
			'description' => esc_html__( 'Color for inactive dots.', 'cph-elements' ),
		),

		// Button Style header.
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Button Style', 'cph-elements' ),
			'param_name' => 'group_header_button_style',
			'group'      => esc_html__( 'Style', 'cph-elements' ),
			'dependency' => array(
				'element' => 'show_button',
				'value'   => array( 'yes' ),
			),
		),

		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Button Border Color', 'cph-elements' ),
			'param_name'  => 'button_border_color',
			'value'       => '#ffffff',
			'group'       => esc_html__( 'Style', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'show_button',
				'value'   => array( 'yes' ),
			),
			'description' => esc_html__( 'Border color of the CTA button.', 'cph-elements' ),
		),

		// Extra class.
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Extra Class Name', 'cph-elements' ),
			'param_name'  => 'el_class',
			'value'       => '',
			'group'       => esc_html__( 'Style', 'cph-elements' ),
			'description' => esc_html__( 'Add custom CSS class(es) to the wrapper element.', 'cph-elements' ),
		),

	), // End params.
);
