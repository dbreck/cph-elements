<?php
/**
 * CPH Gallery Slider - WPBakery Element Map
 *
 * Defines the element configuration and parameters for the WPBakery page builder.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Image source options.
$source_options = array(
	esc_html__( 'Media Gallery', 'cph-elements' ) => 'gallery',
	esc_html__( 'Custom Post Type', 'cph-elements' ) => 'cpt',
);

// Post type options (get CPTs with thumbnail support).
$post_type_options = array(
	esc_html__( 'Portfolio', 'cph-elements' ) => 'portfolio',
	esc_html__( 'Post', 'cph-elements' )      => 'post',
);

// Posts per page options.
$posts_per_page_options = array(
	esc_html__( '3 Posts', 'cph-elements' )  => '3',
	esc_html__( '4 Posts', 'cph-elements' )  => '4',
	esc_html__( '6 Posts', 'cph-elements' )  => '6',
	esc_html__( '8 Posts', 'cph-elements' )  => '8',
	esc_html__( '10 Posts', 'cph-elements' ) => '10',
	esc_html__( '12 Posts', 'cph-elements' ) => '12',
);

// Arrow style options.
$arrow_style_options = array(
	esc_html__( 'Pill (Default)', 'cph-elements' ) => 'pill',
	esc_html__( 'Circle', 'cph-elements' )         => 'circle',
	esc_html__( 'Minimal', 'cph-elements' )        => 'minimal',
);

// Navigation type options.
$nav_type_options = array(
	esc_html__( 'Arrows', 'cph-elements' )     => 'arrows',
	esc_html__( 'Pagination', 'cph-elements' ) => 'pagination',
	esc_html__( 'Both', 'cph-elements' )       => 'both',
	esc_html__( 'None', 'cph-elements' )       => 'none',
);

// Image position options (same as WPBakery background position).
$image_position_options = array(
	esc_html__( 'Center Center', 'cph-elements' ) => 'center center',
	esc_html__( 'Center Top', 'cph-elements' )    => 'center top',
	esc_html__( 'Center Bottom', 'cph-elements' ) => 'center bottom',
	esc_html__( 'Left Top', 'cph-elements' )      => 'left top',
	esc_html__( 'Left Center', 'cph-elements' )   => 'left center',
	esc_html__( 'Left Bottom', 'cph-elements' )   => 'left bottom',
	esc_html__( 'Right Top', 'cph-elements' )     => 'right top',
	esc_html__( 'Right Center', 'cph-elements' )  => 'right center',
	esc_html__( 'Right Bottom', 'cph-elements' )  => 'right bottom',
);

// Easing options for animations.
$easing_options = array(
	esc_html__( 'Power2 Out (Default)', 'cph-elements' ) => 'power2.out',
	esc_html__( 'Power1 Out (Gentle)', 'cph-elements' )  => 'power1.out',
	esc_html__( 'Power3 Out (Strong)', 'cph-elements' )  => 'power3.out',
	esc_html__( 'Power4 Out (Very Strong)', 'cph-elements' ) => 'power4.out',
	esc_html__( 'Back Out (Overshoot)', 'cph-elements' ) => 'back.out(1.7)',
	esc_html__( 'Elastic Out (Springy)', 'cph-elements' ) => 'elastic.out(1,0.5)',
	esc_html__( 'Expo Out (Exponential)', 'cph-elements' ) => 'expo.out',
	esc_html__( 'Circ Out (Circular)', 'cph-elements' )  => 'circ.out',
	esc_html__( 'Linear (No Easing)', 'cph-elements' )   => 'none',
);

// 3D effect style presets.
$effect_style_options = array(
	esc_html__( 'Classic (Scale + Fade)', 'cph-elements' )     => 'classic',
	esc_html__( 'Coverflow (3D Rotation)', 'cph-elements' )    => 'coverflow',
	esc_html__( 'Carousel (Rotation + Depth)', 'cph-elements' ) => 'carousel',
	esc_html__( 'Flat (No Transform)', 'cph-elements' )        => 'flat',
	esc_html__( 'Custom', 'cph-elements' )                     => 'custom',
);

return array(
	'name'        => esc_html__( 'CPH Gallery Slider', 'cph-elements' ),
	'base'        => 'cph_gallery_slider',
	'icon'        => 'icon-wpb-images-carousel',
	'category'    => esc_html__( 'Clear pH Elements', 'cph-elements' ),
	'description' => esc_html__( 'Center-mode carousel with 3D effects and image gallery support.', 'cph-elements' ),
	'params'      => array(

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * CONTENT TAB
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Content Source', 'cph-elements' ),
			'param_name' => 'group_header_content',
			'group'      => esc_html__( 'Content', 'cph-elements' ),
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Image Source', 'cph-elements' ),
			'param_name'  => 'source',
			'value'       => $source_options,
			'std'         => 'gallery',
			'admin_label' => true,
			'group'       => esc_html__( 'Content', 'cph-elements' ),
			'description' => esc_html__( 'Choose where images come from.', 'cph-elements' ),
		),

		// Media Gallery source.
		array(
			'type'        => 'attach_images',
			'heading'     => esc_html__( 'Gallery Images', 'cph-elements' ),
			'param_name'  => 'images',
			'group'       => esc_html__( 'Content', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'source',
				'value'   => array( 'gallery' ),
			),
			'description' => esc_html__( 'Select images from the Media Library.', 'cph-elements' ),
		),

		// CPT source.
		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Post Type', 'cph-elements' ),
			'param_name'  => 'post_type',
			'value'       => $post_type_options,
			'std'         => 'portfolio',
			'group'       => esc_html__( 'Content', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'source',
				'value'   => array( 'cpt' ),
			),
			'description' => esc_html__( 'Select the post type to pull images from.', 'cph-elements' ),
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Number of Posts', 'cph-elements' ),
			'param_name'  => 'posts_per_page',
			'value'       => $posts_per_page_options,
			'std'         => '6',
			'group'       => esc_html__( 'Content', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'source',
				'value'   => array( 'cpt' ),
			),
			'description' => esc_html__( 'How many posts to display.', 'cph-elements' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Category/Taxonomy Slug', 'cph-elements' ),
			'param_name'  => 'taxonomy_slug',
			'value'       => '',
			'group'       => esc_html__( 'Content', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'source',
				'value'   => array( 'cpt' ),
			),
			'description' => esc_html__( 'Optional: Filter by category/taxonomy slug (e.g., "urban-communities").', 'cph-elements' ),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * LAYOUT TAB
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Carousel Dimensions', 'cph-elements' ),
			'param_name' => 'group_header_layout',
			'group'      => esc_html__( 'Layout', 'cph-elements' ),
		),

		// Carousel Height — responsive.
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Carousel Height', 'cph-elements' ),
			'param_name'       => 'height',
			'value'            => '600px',
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

		// Slide Width — responsive.
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Slide Width', 'cph-elements' ),
			'param_name'       => 'slide_width',
			'value'            => '60%',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Layout', 'cph-elements' ),
			'description'      => esc_html__( 'Desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Tablet', 'cph-elements' ),
			'param_name'       => 'slide_width_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Layout', 'cph-elements' ),
			'description'      => esc_html__( 'Inherits desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Phone', 'cph-elements' ),
			'param_name'       => 'slide_width_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Layout', 'cph-elements' ),
			'description'      => esc_html__( 'Inherits tablet.', 'cph-elements' ),
		),

		// Slide Border Radius — responsive.
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Slide Border Radius', 'cph-elements' ),
			'param_name'       => 'slide_radius',
			'value'            => '25px',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Layout', 'cph-elements' ),
			'description'      => esc_html__( 'Desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Tablet', 'cph-elements' ),
			'param_name'       => 'slide_radius_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Layout', 'cph-elements' ),
			'description'      => esc_html__( 'Inherits desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Phone', 'cph-elements' ),
			'param_name'       => 'slide_radius_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Layout', 'cph-elements' ),
			'description'      => esc_html__( 'Inherits tablet.', 'cph-elements' ),
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Image Position', 'cph-elements' ),
			'param_name'  => 'image_position',
			'value'       => $image_position_options,
			'std'         => 'center center',
			'group'       => esc_html__( 'Layout', 'cph-elements' ),
			'description' => esc_html__( 'Controls which part of the image is visible when cropped.', 'cph-elements' ),
		),

		// Slide Gap — responsive.
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Slide Gap', 'cph-elements' ),
			'param_name'       => 'slide_gap',
			'value'            => '40px',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Layout', 'cph-elements' ),
			'description'      => esc_html__( 'Desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Tablet', 'cph-elements' ),
			'param_name'       => 'slide_gap_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Layout', 'cph-elements' ),
			'description'      => esc_html__( 'Inherits desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Phone', 'cph-elements' ),
			'param_name'       => 'slide_gap_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Layout', 'cph-elements' ),
			'description'      => esc_html__( 'Inherits tablet.', 'cph-elements' ),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * ANIMATION TAB
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Animation Style', 'cph-elements' ),
			'param_name' => 'group_header_animation_style',
			'group'      => esc_html__( 'Animation', 'cph-elements' ),
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Effect Style', 'cph-elements' ),
			'param_name'  => 'effect_style',
			'value'       => $effect_style_options,
			'std'         => 'classic',
			'group'       => esc_html__( 'Animation', 'cph-elements' ),
			'description' => esc_html__( 'Choose a preset animation style or customize your own.', 'cph-elements' ),
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Easing', 'cph-elements' ),
			'param_name'  => 'easing',
			'value'       => $easing_options,
			'std'         => 'power2.out',
			'group'       => esc_html__( 'Animation', 'cph-elements' ),
			'description' => esc_html__( 'Animation easing curve for slide transitions.', 'cph-elements' ),
		),

		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( '3D Transform Settings', 'cph-elements' ),
			'param_name' => 'group_header_3d',
			'group'      => esc_html__( 'Animation', 'cph-elements' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Perspective', 'cph-elements' ),
			'param_name'  => 'perspective',
			'value'       => '1000px',
			'group'       => esc_html__( 'Animation', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'effect_style',
				'value'   => array( 'coverflow', 'carousel', 'custom' ),
			),
			'description' => esc_html__( 'CSS perspective for 3D depth (e.g., 1000px). Lower = more dramatic.', 'cph-elements' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Rotation Y (degrees)', 'cph-elements' ),
			'param_name'  => 'rotate_y',
			'value'       => '45',
			'group'       => esc_html__( 'Animation', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'effect_style',
				'value'   => array( 'coverflow', 'carousel', 'custom' ),
			),
			'description' => esc_html__( '3D rotation angle for side slides (0-90). Creates coverflow effect.', 'cph-elements' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Z Offset (depth)', 'cph-elements' ),
			'param_name'  => 'z_offset',
			'value'       => '-200',
			'group'       => esc_html__( 'Animation', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'effect_style',
				'value'   => array( 'carousel', 'custom' ),
			),
			'description' => esc_html__( 'Push side slides back in Z-space (negative values). Creates depth.', 'cph-elements' ),
		),

		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Scale & Opacity', 'cph-elements' ),
			'param_name' => 'group_header_scale_opacity',
			'group'      => esc_html__( 'Animation', 'cph-elements' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Side Slide Scale', 'cph-elements' ),
			'param_name'  => 'side_scale',
			'value'       => '0.75',
			'group'       => esc_html__( 'Animation', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'effect_style',
				'value'   => array( 'classic', 'coverflow', 'carousel', 'custom' ),
			),
			'description' => esc_html__( 'Scale factor for side slides (0.5-1.0).', 'cph-elements' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Side Slide Opacity', 'cph-elements' ),
			'param_name'  => 'side_opacity',
			'value'       => '0.6',
			'group'       => esc_html__( 'Animation', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'effect_style',
				'value'   => array( 'classic', 'coverflow', 'carousel', 'custom' ),
			),
			'description' => esc_html__( 'Opacity for side slides (0.0-1.0).', 'cph-elements' ),
		),

		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Advanced Effects', 'cph-elements' ),
			'param_name' => 'group_header_advanced_effects',
			'group'      => esc_html__( 'Animation', 'cph-elements' ),
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Enable Shadow', 'cph-elements' ),
			'param_name'  => 'enable_shadow',
			'value'       => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'std'         => '',
			'group'       => esc_html__( 'Animation', 'cph-elements' ),
			'description' => esc_html__( 'Add dynamic shadow that increases with depth.', 'cph-elements' ),
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Enable Blur', 'cph-elements' ),
			'param_name'  => 'enable_blur',
			'value'       => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'std'         => '',
			'group'       => esc_html__( 'Animation', 'cph-elements' ),
			'description' => esc_html__( 'Blur side slides for focus effect (may impact performance).', 'cph-elements' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Blur Amount', 'cph-elements' ),
			'param_name'  => 'blur_amount',
			'value'       => '4px',
			'group'       => esc_html__( 'Animation', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'enable_blur',
				'value'   => array( 'yes' ),
			),
			'description' => esc_html__( 'Blur intensity for side slides (e.g., 4px).', 'cph-elements' ),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * BEHAVIOR TAB
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Navigation', 'cph-elements' ),
			'param_name' => 'group_header_behavior',
			'group'      => esc_html__( 'Behavior', 'cph-elements' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Start on Slide #', 'cph-elements' ),
			'param_name'  => 'start_slide',
			'value'       => '1',
			'group'       => esc_html__( 'Behavior', 'cph-elements' ),
			'description' => esc_html__( 'Which slide to show initially (1 = first slide, 2 = second slide, etc.).', 'cph-elements' ),
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Infinite Loop', 'cph-elements' ),
			'param_name'  => 'infinite',
			'value'       => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'std'         => 'yes',
			'group'       => esc_html__( 'Behavior', 'cph-elements' ),
			'description' => esc_html__( 'Enable continuous looping.', 'cph-elements' ),
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Enable Drag/Swipe', 'cph-elements' ),
			'param_name'  => 'draggable',
			'value'       => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'std'         => 'yes',
			'group'       => esc_html__( 'Behavior', 'cph-elements' ),
			'description' => esc_html__( 'Allow touch/mouse drag navigation.', 'cph-elements' ),
		),

		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Autoplay', 'cph-elements' ),
			'param_name' => 'group_header_autoplay',
			'group'      => esc_html__( 'Behavior', 'cph-elements' ),
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Autoplay', 'cph-elements' ),
			'param_name'  => 'autoplay',
			'value'       => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'std'         => '',
			'group'       => esc_html__( 'Behavior', 'cph-elements' ),
			'description' => esc_html__( 'Auto-advance slides.', 'cph-elements' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Autoplay Speed (seconds)', 'cph-elements' ),
			'param_name'  => 'autoplay_speed',
			'value'       => '5',
			'group'       => esc_html__( 'Behavior', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'autoplay',
				'value'   => array( 'yes' ),
			),
			'description' => esc_html__( 'Seconds between auto-advance.', 'cph-elements' ),
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Pause on Hover', 'cph-elements' ),
			'param_name'  => 'pause_on_hover',
			'value'       => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'std'         => 'yes',
			'group'       => esc_html__( 'Behavior', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'autoplay',
				'value'   => array( 'yes' ),
			),
			'description' => esc_html__( 'Pause autoplay when hovering.', 'cph-elements' ),
		),

		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Animation', 'cph-elements' ),
			'param_name' => 'group_header_animation',
			'group'      => esc_html__( 'Behavior', 'cph-elements' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Transition Duration (ms)', 'cph-elements' ),
			'param_name'  => 'transition_duration',
			'value'       => '600',
			'group'       => esc_html__( 'Behavior', 'cph-elements' ),
			'description' => esc_html__( 'Animation time in milliseconds.', 'cph-elements' ),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * NAVIGATION TAB
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Navigation Type', 'cph-elements' ),
			'param_name' => 'group_header_nav_type',
			'group'      => esc_html__( 'Navigation', 'cph-elements' ),
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Navigation Style', 'cph-elements' ),
			'param_name'  => 'nav_type',
			'value'       => $nav_type_options,
			'std'         => 'arrows',
			'admin_label' => true,
			'group'       => esc_html__( 'Navigation', 'cph-elements' ),
			'description' => esc_html__( 'Choose navigation style: arrows, pagination dots, both, or none.', 'cph-elements' ),
		),

		/*
		 * Arrow Settings
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Arrow Buttons', 'cph-elements' ),
			'param_name' => 'group_header_arrows',
			'group'      => esc_html__( 'Navigation', 'cph-elements' ),
			'dependency' => array(
				'element' => 'nav_type',
				'value'   => array( 'arrows', 'both' ),
			),
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Arrow Style', 'cph-elements' ),
			'param_name'  => 'arrow_style',
			'value'       => $arrow_style_options,
			'std'         => 'pill',
			'group'       => esc_html__( 'Navigation', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'nav_type',
				'value'   => array( 'arrows', 'both' ),
			),
			'description' => esc_html__( 'Shape of the arrow buttons.', 'cph-elements' ),
		),

		// Arrow Position — responsive.
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Arrow Position (from edge)', 'cph-elements' ),
			'param_name'       => 'arrow_offset',
			'value'            => '30px',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Navigation', 'cph-elements' ),
			'dependency'       => array(
				'element' => 'nav_type',
				'value'   => array( 'arrows', 'both' ),
			),
			'description'      => esc_html__( 'Desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Tablet', 'cph-elements' ),
			'param_name'       => 'arrow_offset_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Navigation', 'cph-elements' ),
			'dependency'       => array(
				'element' => 'nav_type',
				'value'   => array( 'arrows', 'both' ),
			),
			'description'      => esc_html__( 'Inherits desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Phone', 'cph-elements' ),
			'param_name'       => 'arrow_offset_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Navigation', 'cph-elements' ),
			'dependency'       => array(
				'element' => 'nav_type',
				'value'   => array( 'arrows', 'both' ),
			),
			'description'      => esc_html__( 'Inherits tablet.', 'cph-elements' ),
		),

		/*
		 * Arrow Default State
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Arrow Default State', 'cph-elements' ),
			'param_name' => 'group_header_arrow_default',
			'group'      => esc_html__( 'Navigation', 'cph-elements' ),
			'dependency' => array(
				'element' => 'nav_type',
				'value'   => array( 'arrows', 'both' ),
			),
		),

		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Outline Color', 'cph-elements' ),
			'param_name'  => 'arrow_outline_color',
			'value'       => '#ffffff',
			'group'       => esc_html__( 'Navigation', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'nav_type',
				'value'   => array( 'arrows', 'both' ),
			),
			'description' => esc_html__( 'Border/outline color of the arrow button.', 'cph-elements' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Outline Thickness', 'cph-elements' ),
			'param_name'  => 'arrow_outline_thickness',
			'value'       => '1px',
			'group'       => esc_html__( 'Navigation', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'nav_type',
				'value'   => array( 'arrows', 'both' ),
			),
			'description' => esc_html__( 'Border thickness (e.g., 1px, 2px).', 'cph-elements' ),
		),

		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Background Color', 'cph-elements' ),
			'param_name'  => 'arrow_bg_color',
			'value'       => '',
			'group'       => esc_html__( 'Navigation', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'nav_type',
				'value'   => array( 'arrows', 'both' ),
			),
			'description' => esc_html__( 'Leave empty for transparent background.', 'cph-elements' ),
		),

		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Arrow Color', 'cph-elements' ),
			'param_name'  => 'arrow_color',
			'value'       => '#ffffff',
			'group'       => esc_html__( 'Navigation', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'nav_type',
				'value'   => array( 'arrows', 'both' ),
			),
			'description' => esc_html__( 'Color of the arrow icon.', 'cph-elements' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Arrow Thickness', 'cph-elements' ),
			'param_name'  => 'arrow_thickness',
			'value'       => '1px',
			'group'       => esc_html__( 'Navigation', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'nav_type',
				'value'   => array( 'arrows', 'both' ),
			),
			'description' => esc_html__( 'Stroke width of the arrow icon (e.g., 1px, 2px).', 'cph-elements' ),
		),

		/*
		 * Arrow Hover State
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Arrow Hover State', 'cph-elements' ),
			'param_name' => 'group_header_arrow_hover',
			'group'      => esc_html__( 'Navigation', 'cph-elements' ),
			'dependency' => array(
				'element' => 'nav_type',
				'value'   => array( 'arrows', 'both' ),
			),
		),

		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Hover Outline Color', 'cph-elements' ),
			'param_name'  => 'arrow_hover_outline_color',
			'value'       => '',
			'group'       => esc_html__( 'Navigation', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'nav_type',
				'value'   => array( 'arrows', 'both' ),
			),
			'description' => esc_html__( 'Leave empty to use default outline color.', 'cph-elements' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Hover Outline Thickness', 'cph-elements' ),
			'param_name'  => 'arrow_hover_outline_thickness',
			'value'       => '',
			'group'       => esc_html__( 'Navigation', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'nav_type',
				'value'   => array( 'arrows', 'both' ),
			),
			'description' => esc_html__( 'Leave empty to use default thickness.', 'cph-elements' ),
		),

		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Hover Background Color', 'cph-elements' ),
			'param_name'  => 'arrow_hover_bg_color',
			'value'       => '',
			'group'       => esc_html__( 'Navigation', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'nav_type',
				'value'   => array( 'arrows', 'both' ),
			),
			'description' => esc_html__( 'Leave empty for no change on hover.', 'cph-elements' ),
		),

		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Hover Arrow Color', 'cph-elements' ),
			'param_name'  => 'arrow_hover_color',
			'value'       => '',
			'group'       => esc_html__( 'Navigation', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'nav_type',
				'value'   => array( 'arrows', 'both' ),
			),
			'description' => esc_html__( 'Leave empty to use default arrow color.', 'cph-elements' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Hover Arrow Thickness', 'cph-elements' ),
			'param_name'  => 'arrow_hover_thickness',
			'value'       => '',
			'group'       => esc_html__( 'Navigation', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'nav_type',
				'value'   => array( 'arrows', 'both' ),
			),
			'description' => esc_html__( 'Leave empty to use default thickness.', 'cph-elements' ),
		),

		/*
		 * Pagination Settings
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Pagination Dots', 'cph-elements' ),
			'param_name' => 'group_header_pagination',
			'group'      => esc_html__( 'Navigation', 'cph-elements' ),
			'dependency' => array(
				'element' => 'nav_type',
				'value'   => array( 'pagination', 'both' ),
			),
		),

		// Dot Size — responsive.
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Dot Size', 'cph-elements' ),
			'param_name'       => 'dot_size',
			'value'            => '12px',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Navigation', 'cph-elements' ),
			'dependency'       => array(
				'element' => 'nav_type',
				'value'   => array( 'pagination', 'both' ),
			),
			'description'      => esc_html__( 'Desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Tablet', 'cph-elements' ),
			'param_name'       => 'dot_size_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Navigation', 'cph-elements' ),
			'dependency'       => array(
				'element' => 'nav_type',
				'value'   => array( 'pagination', 'both' ),
			),
			'description'      => esc_html__( 'Inherits desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Phone', 'cph-elements' ),
			'param_name'       => 'dot_size_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Navigation', 'cph-elements' ),
			'dependency'       => array(
				'element' => 'nav_type',
				'value'   => array( 'pagination', 'both' ),
			),
			'description'      => esc_html__( 'Inherits tablet.', 'cph-elements' ),
		),

		// Dot Gap — responsive.
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Dot Gap', 'cph-elements' ),
			'param_name'       => 'dot_gap',
			'value'            => '10px',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Navigation', 'cph-elements' ),
			'dependency'       => array(
				'element' => 'nav_type',
				'value'   => array( 'pagination', 'both' ),
			),
			'description'      => esc_html__( 'Desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Tablet', 'cph-elements' ),
			'param_name'       => 'dot_gap_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Navigation', 'cph-elements' ),
			'dependency'       => array(
				'element' => 'nav_type',
				'value'   => array( 'pagination', 'both' ),
			),
			'description'      => esc_html__( 'Inherits desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Phone', 'cph-elements' ),
			'param_name'       => 'dot_gap_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Navigation', 'cph-elements' ),
			'dependency'       => array(
				'element' => 'nav_type',
				'value'   => array( 'pagination', 'both' ),
			),
			'description'      => esc_html__( 'Inherits tablet.', 'cph-elements' ),
		),

		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Dot Outline Color', 'cph-elements' ),
			'param_name'  => 'dot_outline_color',
			'value'       => '#ffffff',
			'group'       => esc_html__( 'Navigation', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'nav_type',
				'value'   => array( 'pagination', 'both' ),
			),
			'description' => esc_html__( 'Border color of inactive dots.', 'cph-elements' ),
		),

		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Dot Fill Color (Active)', 'cph-elements' ),
			'param_name'  => 'dot_fill_color',
			'value'       => '#ffffff',
			'group'       => esc_html__( 'Navigation', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'nav_type',
				'value'   => array( 'pagination', 'both' ),
			),
			'description' => esc_html__( 'Fill color of the active dot.', 'cph-elements' ),
		),

		// Pagination Position — responsive.
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Pagination Position (from bottom)', 'cph-elements' ),
			'param_name'       => 'pagination_offset',
			'value'            => '20px',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Navigation', 'cph-elements' ),
			'dependency'       => array(
				'element' => 'nav_type',
				'value'   => array( 'pagination', 'both' ),
			),
			'description'      => esc_html__( 'Desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Tablet', 'cph-elements' ),
			'param_name'       => 'pagination_offset_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Navigation', 'cph-elements' ),
			'dependency'       => array(
				'element' => 'nav_type',
				'value'   => array( 'pagination', 'both' ),
			),
			'description'      => esc_html__( 'Inherits desktop.', 'cph-elements' ),
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Phone', 'cph-elements' ),
			'param_name'       => 'pagination_offset_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-4',
			'group'            => esc_html__( 'Navigation', 'cph-elements' ),
			'dependency'       => array(
				'element' => 'nav_type',
				'value'   => array( 'pagination', 'both' ),
			),
			'description'      => esc_html__( 'Inherits tablet.', 'cph-elements' ),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * EXTRA TAB
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Extra', 'cph-elements' ),
			'param_name' => 'group_header_extra',
			'group'      => esc_html__( 'Extra', 'cph-elements' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Extra Class Name', 'cph-elements' ),
			'param_name'  => 'el_class',
			'value'       => '',
			'group'       => esc_html__( 'Extra', 'cph-elements' ),
			'description' => esc_html__( 'Add custom CSS class(es) to the wrapper element.', 'cph-elements' ),
		),

	), // End params.
);
