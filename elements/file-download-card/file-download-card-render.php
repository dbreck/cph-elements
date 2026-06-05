<?php
/**
 * File Download Card - Shortcode Rendering
 *
 * Renders the file download card element HTML output.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render the File Download Card shortcode.
 *
 * @since 1.0.0
 *
 * @param array  $atts    Shortcode attributes.
 * @param string $content Shortcode content (unused).
 * @param string $tag     Shortcode tag.
 * @return string HTML output.
 */
function cph_file_download_card_shortcode( $atts = array(), $content = '', $tag = '' ) {

	// Parse attributes with defaults.
	$atts = shortcode_atts(
		array(
			'title'               => '',
			'image'               => '',
			'file'                => '',
			'button_text'         => 'Download',
			'image_click'         => 'lightbox',
			'aspect_ratio'        => '4-3',
			'image_position'      => 'center-center',
			'title_tag'           => 'h3',
			'show_meta'           => 'yes',
			'border_radius'       => 'md',
			'shadow'              => 'enabled',
			'shadow_strength'     => 'medium',
			'shadow_color'        => '',
			'color_scheme'        => 'light',
			'custom_bg_color'     => '',
			'custom_text_color'   => '',
			'custom_button_color' => '',
		),
		$atts,
		$tag
	);

	// Bail if no file is set.
	if ( empty( $atts['file'] ) ) {
		return '';
	}

	// Get file info.
	$file_id   = intval( $atts['file'] );
	$file_path = get_attached_file( $file_id );

	if ( ! $file_path || ! file_exists( $file_path ) ) {
		return '';
	}

	$file_url  = wp_get_attachment_url( $file_id );
	$file_name = basename( $file_path );
	$file_size = size_format( filesize( $file_path ) );
	$file_type = strtoupper( pathinfo( $file_path, PATHINFO_EXTENSION ) );

	// Detect video files (always preview in a lightbox player).
	$video_extensions = array( 'MP4', 'MOV', 'WEBM', 'OGG', 'AVI' );
	$is_video         = in_array( $file_type, $video_extensions, true );

	// Get title (use filename if not provided).
	$title = ! empty( $atts['title'] ) ? $atts['title'] : pathinfo( $file_name, PATHINFO_FILENAME );
	$title = str_replace( array( '-', '_' ), ' ', $title );
	$title = ucwords( $title );

	// Get image URL.
	$image_url      = '';
	$image_full_url = '';
	if ( ! empty( $atts['image'] ) ) {
		$image_id       = intval( $atts['image'] );
		$image_src      = wp_get_attachment_image_src( $image_id, 'medium_large' );
		$image_full_src = wp_get_attachment_image_src( $image_id, 'full' );
		if ( $image_src ) {
			$image_url = $image_src[0];
		}
		if ( $image_full_src ) {
			$image_full_url = $image_full_src[0];
		}
	}

	// Get Salient theme colors for accent-based schemes.
	$nectar_options = function_exists( 'get_nectar_theme_options' ) ? get_nectar_theme_options() : array();
	$accent_color   = isset( $nectar_options['accent-color'] ) ? $nectar_options['accent-color'] : '#000000';
	$extra_color_1  = isset( $nectar_options['extra-color-1'] ) ? $nectar_options['extra-color-1'] : '#000000';
	$extra_color_2  = isset( $nectar_options['extra-color-2'] ) ? $nectar_options['extra-color-2'] : '#000000';
	$extra_color_3  = isset( $nectar_options['extra-color-3'] ) ? $nectar_options['extra-color-3'] : '#000000';

	// Build inline styles for custom/accent colors.
	$inline_styles = '';
	$color_class   = 'file-download-card--' . esc_attr( $atts['color_scheme'] );

	switch ( $atts['color_scheme'] ) {
		case 'accent':
			$inline_styles = '--fdc-accent-color: ' . esc_attr( $accent_color ) . ';';
			break;
		case 'extra-1':
			$inline_styles = '--fdc-accent-color: ' . esc_attr( $extra_color_1 ) . ';';
			break;
		case 'extra-2':
			$inline_styles = '--fdc-accent-color: ' . esc_attr( $extra_color_2 ) . ';';
			break;
		case 'extra-3':
			$inline_styles = '--fdc-accent-color: ' . esc_attr( $extra_color_3 ) . ';';
			break;
		case 'custom':
			if ( ! empty( $atts['custom_bg_color'] ) ) {
				$inline_styles .= '--fdc-bg-color: ' . esc_attr( $atts['custom_bg_color'] ) . ';';
			}
			if ( ! empty( $atts['custom_text_color'] ) ) {
				$inline_styles .= '--fdc-text-color: ' . esc_attr( $atts['custom_text_color'] ) . ';';
			}
			if ( ! empty( $atts['custom_button_color'] ) ) {
				$inline_styles .= '--fdc-button-color: ' . esc_attr( $atts['custom_button_color'] ) . ';';
			}
			break;
	}

	// Ensure FancyBox is enqueued (runtime dependency from Salient).
	if ( wp_script_is( 'fancyBox', 'registered' ) ) {
		wp_enqueue_script( 'fancyBox' );
	}

	// Sanitize title tag.
	$allowed_tags = array( 'h2', 'h3', 'h4', 'h5', 'h6' );
	$title_tag    = in_array( $atts['title_tag'], $allowed_tags, true ) ? $atts['title_tag'] : 'h3';

	// Build shadow styles.
	$shadow_enabled = 'enabled' === $atts['shadow'];
	$shadow_class   = $shadow_enabled ? 'file-download-card--shadow-' . esc_attr( $atts['shadow_strength'] ) : 'file-download-card--no-shadow';

	// Add shadow color to inline styles if custom color is set.
	if ( $shadow_enabled && ! empty( $atts['shadow_color'] ) ) {
		$inline_styles .= '--fdc-shadow-color: ' . esc_attr( $atts['shadow_color'] ) . ';';
	}

	// Prepare template variables.
	$template_vars = array(
		'title'          => $title,
		'title_tag'      => $title_tag,
		'image_url'      => $image_url,
		'image_full_url' => $image_full_url,
		'file_url'       => $file_url,
		'file_name'      => $file_name,
		'file_size'      => $file_size,
		'file_type'      => $file_type,
		'is_video'       => $is_video,
		'button_text'    => $atts['button_text'],
		'image_click'    => $atts['image_click'],
		'aspect_ratio'   => $atts['aspect_ratio'],
		'image_position' => $atts['image_position'],
		'show_meta'      => 'yes' === $atts['show_meta'],
		'border_radius'  => $atts['border_radius'],
		'shadow_class'   => $shadow_class,
		'color_class'    => $color_class,
		'inline_styles'  => $inline_styles,
	);

	// Extract for use in template.
	extract( $template_vars ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

	// Render template.
	ob_start();
	include cph_element_path( 'file-download-card' ) . 'partials/file-download-card.php';
	return ob_get_clean();
}
add_shortcode( 'file_download_card', 'cph_file_download_card_shortcode' );
