<?php
/**
 * File Download Card - HTML Template
 *
 * Variables available:
 * - $title (string) - Card title
 * - $title_tag (string) - HTML tag for title (h2-h6)
 * - $image_url (string) - Preview image URL (medium_large size)
 * - $image_full_url (string) - Full size image URL for lightbox
 * - $file_url (string) - URL to the downloadable file
 * - $file_name (string) - Original filename
 * - $file_size (string) - Formatted file size (e.g., "2.5 MB")
 * - $file_type (string) - File extension uppercase (e.g., "PDF")
 * - $is_video (bool) - Whether the downloadable file is a video
 * - $is_link (bool) - Whether this is a Link card (vs File Download card)
 * - $link_url (string) - Destination URL for link cards
 * - $link_target (string) - Anchor target attribute ("_blank" or "")
 * - $link_rel (string) - Anchor rel attribute for new-tab/external links
 * - $is_external (bool) - Whether the link is external (shows outbound arrow)
 * - $extra_text (string) - Optional text shown below the title on link cards
 * - $button_text (string) - Button text
 * - $image_click (string) - Preview image click action (lightbox|download)
 * - $aspect_ratio (string) - Image aspect ratio (4-3, 16-9, 3-4)
 * - $image_position (string) - Image crop focus (center-center, top-left, etc.)
 * - $icon_always (bool) - Whether the action icon stays visible (vs. hover-reveal)
 * - $show_meta (bool) - Whether to show file metadata
 * - $border_radius (string) - none|sm|md|lg
 * - $shadow_class (string) - Shadow variant class
 * - $color_class (string) - Color scheme class
 * - $inline_styles (string) - CSS custom properties for colors
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Build card classes.
$card_classes = array(
	'file-download-card',
	$color_class,
	'file-download-card--radius-' . esc_attr( $border_radius ),
	isset( $shadow_class ) ? $shadow_class : 'file-download-card--shadow-medium',
);

// Ensure title_tag is set.
$title_tag = isset( $title_tag ) ? $title_tag : 'h3';

// Ensure aspect_ratio is set.
$aspect_ratio = isset( $aspect_ratio ) ? $aspect_ratio : '4-3';

// Sanitize image_position against allowed values.
$allowed_positions = array( 'center-center', 'top-left', 'top-center', 'top-right', 'center-left', 'center-right', 'bottom-left', 'bottom-center', 'bottom-right' );
$image_position    = ( isset( $image_position ) && in_array( $image_position, $allowed_positions, true ) ) ? $image_position : 'center-center';

// Whether the action icon stays visible at all times (vs. hover-reveal default).
$icon_always = isset( $icon_always ) ? $icon_always : false;

// Link-mode defaults (in case template is rendered standalone).
$is_link     = isset( $is_link ) ? $is_link : false;
$link_url    = isset( $link_url ) ? $link_url : '';
$link_target = isset( $link_target ) ? $link_target : '';
$link_rel    = isset( $link_rel ) ? $link_rel : '';
$is_external = isset( $is_external ) ? $is_external : false;
$extra_text  = isset( $extra_text ) ? $extra_text : '';

// Anchor attributes for link-mode cards.
$link_attrs = '';
if ( $link_target ) {
	$link_attrs .= ' target="' . esc_attr( $link_target ) . '"';
}
if ( $link_rel ) {
	$link_attrs .= ' rel="' . esc_attr( $link_rel ) . '"';
}

// Inline SVG icons for link cards: outbound arrow for external, right arrow otherwise.
$external_arrow_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>';
$arrow_right_svg    = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>';
$link_icon_svg      = $is_external ? $external_arrow_svg : $arrow_right_svg;
?>

<div class="<?php echo esc_attr( implode( ' ', $card_classes ) ); ?>"<?php echo $inline_styles ? ' style="' . esc_attr( $inline_styles ) . '"' : ''; ?>>

	<?php if ( ! empty( $image_url ) ) : ?>
	<?php
	// Determine video MIME type for FancyBox.
	$is_video       = isset( $is_video ) ? $is_video : false;
	$video_mime_map = array(
		'MP4'  => 'video/mp4',
		'MOV'  => 'video/quicktime',
		'WEBM' => 'video/webm',
		'OGG'  => 'video/ogg',
		'AVI'  => 'video/x-msvideo',
	);
	$video_mime     = ( $is_video && isset( $video_mime_map[ $file_type ] ) ) ? $video_mime_map[ $file_type ] : '';
	?>
	<div class="file-download-card__image file-download-card__image--<?php echo esc_attr( $aspect_ratio ); ?> file-download-card__image--pos-<?php echo esc_attr( $image_position ); ?><?php echo $is_video ? ' file-download-card__image--video' : ''; ?><?php echo $is_link ? ' file-download-card__image--link' : ''; ?><?php echo $icon_always ? ' file-download-card__image--icon-always' : ''; ?>">
		<?php if ( $is_link ) : ?>
		<a href="<?php echo esc_url( $link_url ); ?>" class="file-download-card__preview-link file-download-card__preview-link--link"<?php echo $link_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> title="<?php echo $is_external ? esc_attr__( 'Opens an external link', 'cph-elements' ) : esc_attr__( 'Click to open', 'cph-elements' ); ?>">
			<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy" />
			<span class="file-download-card__preview-icon file-download-card__preview-icon--link" aria-hidden="true">
				<?php echo $link_icon_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</span>
		</a>
		<?php elseif ( $is_video ) : ?>
		<a href="<?php echo esc_url( $file_url ); ?>" class="file-download-card__preview-link" data-fancybox="fdc-video" data-type="video" data-video-format="<?php echo esc_attr( $video_mime ); ?>" data-caption="<?php echo esc_attr( $title ); ?>" title="<?php esc_attr_e( 'Click to play video', 'cph-elements' ); ?>">
			<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy" />
			<span class="file-download-card__preview-icon file-download-card__preview-icon--play" aria-hidden="true">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" stroke="none">
					<polygon points="6,3 20,12 6,21"></polygon>
				</svg>
			</span>
		</a>
		<?php elseif ( isset( $image_click ) && 'download' === $image_click ) : ?>
		<a href="<?php echo esc_url( $file_url ); ?>" class="file-download-card__preview-link" download="<?php echo esc_attr( $file_name ); ?>" title="<?php esc_attr_e( 'Click to download', 'cph-elements' ); ?>">
			<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy" />
			<span class="file-download-card__preview-icon" aria-hidden="true">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
					<polyline points="7 10 12 15 17 10"></polyline>
					<line x1="12" y1="15" x2="12" y2="3"></line>
				</svg>
			</span>
		</a>
		<?php else : ?>
		<a href="<?php echo esc_url( $image_full_url ? $image_full_url : $image_url ); ?>" class="file-download-card__preview-link" data-fancybox="fdc-gallery" data-caption="<?php echo esc_attr( $title ); ?>" title="<?php esc_attr_e( 'Click to preview', 'cph-elements' ); ?>">
			<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy" />
			<span class="file-download-card__preview-icon" aria-hidden="true">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<circle cx="11" cy="11" r="8"></circle>
					<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
					<line x1="11" y1="8" x2="11" y2="14"></line>
					<line x1="8" y1="11" x2="14" y2="11"></line>
				</svg>
			</span>
		</a>
		<?php endif; ?>
	</div>
	<?php endif; ?>

	<div class="file-download-card__content">

		<<?php echo esc_html( $title_tag ); ?> class="file-download-card__title"><?php echo esc_html( $title ); ?></<?php echo esc_html( $title_tag ); ?>>

		<?php if ( $show_meta && ( ! empty( $file_type ) || ! empty( $file_size ) ) ) : ?>
		<p class="file-download-card__meta">
			<?php
			$meta_parts = array();
			if ( ! empty( $file_type ) ) {
				$meta_parts[] = $file_type;
			}
			if ( ! empty( $file_size ) ) {
				$meta_parts[] = $file_size;
			}
			echo esc_html( implode( ' • ', $meta_parts ) );
			?>
		</p>
		<?php endif; ?>

		<?php if ( $is_link && '' !== trim( $extra_text ) ) : ?>
		<p class="file-download-card__extra"><?php echo nl2br( esc_html( $extra_text ) ); ?></p>
		<?php endif; ?>

		<?php if ( $is_link ) : ?>
		<a href="<?php echo esc_url( $link_url ); ?>" class="file-download-card__button file-download-card__button--link<?php echo $is_external ? ' file-download-card__button--external' : ''; ?>"<?php echo $link_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<span class="file-download-card__button-text"><?php echo esc_html( $button_text ); ?></span>
			<span class="file-download-card__button-icon" aria-hidden="true">
				<?php echo $link_icon_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</span>
		</a>
		<?php else : ?>
		<a href="<?php echo esc_url( $file_url ); ?>" class="file-download-card__button" download="<?php echo esc_attr( $file_name ); ?>">
			<span class="file-download-card__button-icon" aria-hidden="true">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
					<polyline points="7 10 12 15 17 10"></polyline>
					<line x1="12" y1="15" x2="12" y2="3"></line>
				</svg>
			</span>
			<span class="file-download-card__button-text"><?php echo esc_html( $button_text ); ?></span>
		</a>
		<?php endif; ?>

	</div>

</div>
