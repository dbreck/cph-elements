<?php
/**
 * CPH Testimonials - Shortcode Rendering
 *
 * Renders the testimonials fade slider with dot navigation.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render the testimonials shortcode.
 *
 * @since 1.0.0
 *
 * @param array $atts Shortcode attributes.
 * @return string HTML output.
 */
function cph_testimonials_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			// Content.
			'posts_per_page'      => '-1',
			'orderby'             => 'menu_order',
			'transition_style'    => 'fade',
			// Decorative Icon.
			'icon_mode'           => 'character',
			'icon_character'      => "\u{201C}",
			'icon_tag'            => 'h2',
			'icon_font'           => '',
			'icon_fontawesome'    => 'fa fa-quote-left',
			'icon_size'           => '80px',
			// Button.
			'show_button'         => '',
			'button_text'         => 'Read More',
			'button_url'          => '',
			'button_new_tab'      => '',
			// Autoplay.
			'autoplay'            => 'yes',
			'autoplay_speed'      => '6',
			// Style.
			'bg_color'            => '#232323',
			'text_color'          => '#ffffff',
			'accent_color'        => '#87A80E',
			'quote_font_size'     => '40px',
			'quote_max_width'     => '1604px',
			'attribution_font_size' => '22px',
			'dot_size'            => '18px',
			'dot_gap'             => '12px',
			'slider_height'       => '',
			'icon_margin_bottom'  => '10px',
			'quote_margin_bottom' => '20px',
			'button_margin_top'   => '30px',
			'dot_inactive_color'  => '#D9D9D9',
			'button_border_color' => '#ffffff',
			// Responsive — tablet.
			'icon_size_tablet'             => '',
			'quote_font_size_tablet'       => '',
			'quote_max_width_tablet'       => '',
			'attribution_font_size_tablet' => '',
			'slider_height_tablet'         => '',
			'icon_margin_bottom_tablet'    => '',
			'quote_margin_bottom_tablet'   => '',
			'button_margin_top_tablet'     => '',
			'dot_size_tablet'              => '',
			'dot_gap_tablet'               => '',
			// Responsive — phone.
			'icon_size_phone'              => '',
			'quote_font_size_phone'        => '',
			'quote_max_width_phone'        => '',
			'attribution_font_size_phone'  => '',
			'slider_height_phone'          => '',
			'icon_margin_bottom_phone'     => '',
			'quote_margin_bottom_phone'    => '',
			'button_margin_top_phone'      => '',
			'dot_size_phone'               => '',
			'dot_gap_phone'                => '',
			// Extra.
			'el_class'            => '',
		),
		$atts,
		'cph_testimonials'
	);

	// Build query.
	$query_args = array(
		'post_type'      => 'cph_testimonial',
		'posts_per_page' => intval( $atts['posts_per_page'] ),
		'post_status'    => 'publish',
	);

	if ( 'rand' === $atts['orderby'] ) {
		$query_args['orderby'] = 'rand';
	} else {
		$query_args['orderby'] = array(
			sanitize_key( $atts['orderby'] ) => 'ASC',
			'date'                           => 'DESC',
		);
	}

	$query = new WP_Query( $query_args );

	if ( ! $query->have_posts() ) {
		wp_reset_postdata();
		return '<p class="cph-testimonials-empty">' . esc_html__( 'No testimonials found. Add testimonials in the WordPress admin.', 'cph-elements' ) . '</p>';
	}

	// Collect testimonials.
	$testimonials = array();
	while ( $query->have_posts() ) {
		$query->the_post();
		$testimonials[] = array(
			'quote'       => get_post_meta( get_the_ID(), '_cph_testimonial_quote', true ),
			'attribution' => get_the_title(),
		);
	}
	wp_reset_postdata();

	// Generate unique ID.
	static $instance_id = 0;
	++$instance_id;
	$id = 'cph-testimonials-' . $instance_id;

	// Sanitize values.
	$bg_color            = sanitize_text_field( $atts['bg_color'] );
	$text_color          = sanitize_text_field( $atts['text_color'] );
	$accent_color        = sanitize_text_field( $atts['accent_color'] );
	$quote_font_size     = sanitize_text_field( $atts['quote_font_size'] );
	$quote_max_width     = sanitize_text_field( $atts['quote_max_width'] );
	$attribution_size    = sanitize_text_field( $atts['attribution_font_size'] );
	$icon_size           = sanitize_text_field( $atts['icon_size'] );
	$icon_margin_bottom  = sanitize_text_field( $atts['icon_margin_bottom'] );
	$quote_margin_bottom = sanitize_text_field( $atts['quote_margin_bottom'] );
	$button_margin_top   = sanitize_text_field( $atts['button_margin_top'] );
	$dot_size            = sanitize_text_field( $atts['dot_size'] );
	$dot_gap             = sanitize_text_field( $atts['dot_gap'] );
	$slider_height       = sanitize_text_field( $atts['slider_height'] );
	$dot_inactive        = sanitize_text_field( $atts['dot_inactive_color'] );
	$button_border       = sanitize_text_field( $atts['button_border_color'] );

	// Build scoped CSS custom properties.
	$css_vars = array(
		'--testimonials-bg: ' . esc_attr( $bg_color ),
		'--testimonials-text: ' . esc_attr( $text_color ),
		'--testimonials-accent: ' . esc_attr( $accent_color ),
		'--testimonials-quote-size: ' . esc_attr( $quote_font_size ),
		'--testimonials-max-width: ' . esc_attr( $quote_max_width ),
		'--testimonials-attribution-size: ' . esc_attr( $attribution_size ),
		'--testimonials-icon-size: ' . esc_attr( $icon_size ),
		'--testimonials-icon-mb: ' . esc_attr( $icon_margin_bottom ),
		'--testimonials-quote-mb: ' . esc_attr( $quote_margin_bottom ),
		'--testimonials-button-mt: ' . esc_attr( $button_margin_top ),
		'--testimonials-dot-size: ' . esc_attr( $dot_size ),
		'--testimonials-dot-gap: ' . esc_attr( $dot_gap ),
		'--testimonials-dot-inactive: ' . esc_attr( $dot_inactive ),
		'--testimonials-button-border: ' . esc_attr( $button_border ),
		'--testimonials-transition: 600ms',
	);

	if ( ! empty( $slider_height ) ) {
		$css_vars[] = '--testimonials-slider-height: ' . esc_attr( $slider_height );
	}

	$element_css = '#' . esc_attr( $id ) . ' { ' . implode( '; ', $css_vars ) . '; }';

	// Responsive overrides — tablet (≤999px) and phone (≤599px).
	$responsive_map = array(
		'icon_size'            => '--testimonials-icon-size',
		'quote_font_size'      => '--testimonials-quote-size',
		'quote_max_width'      => '--testimonials-max-width',
		'attribution_font_size' => '--testimonials-attribution-size',
		'slider_height'        => '--testimonials-slider-height',
		'icon_margin_bottom'   => '--testimonials-icon-mb',
		'quote_margin_bottom'  => '--testimonials-quote-mb',
		'button_margin_top'    => '--testimonials-button-mt',
		'dot_size'             => '--testimonials-dot-size',
		'dot_gap'              => '--testimonials-dot-gap',
	);

	$selector = '#' . esc_attr( $id );

	foreach ( array( 'tablet' => '999px', 'phone' => '599px' ) as $device => $breakpoint ) {
		$vars = array();
		foreach ( $responsive_map as $param => $css_var ) {
			$val = sanitize_text_field( $atts[ $param . '_' . $device ] );
			if ( '' !== $val ) {
				$vars[] = $css_var . ': ' . esc_attr( $val );
			}
		}
		if ( ! empty( $vars ) ) {
			$element_css .= ' @media only screen and (max-width: ' . $breakpoint . ') { ' . $selector . ' { ' . implode( '; ', $vars ) . '; } }';
		}
	}

	// Icon font family override.
	if ( 'character' === $atts['icon_mode'] && ! empty( $atts['icon_font'] ) ) {
		$element_css .= ' #' . esc_attr( $id ) . ' .cph-testimonials__icon-char { font-family: ' . esc_attr( sanitize_text_field( $atts['icon_font'] ) ) . '; }';
	}

	// Data attributes.
	$autoplay       = 'yes' === $atts['autoplay'] ? 'true' : 'false';
	$autoplay_speed = intval( $atts['autoplay_speed'] ) * 1000;

	// Wrapper classes.
	$allowed_transitions = array( 'fade', 'slide-left', 'slide-right', 'fade-up', 'fade-down', 'zoom-fade', 'pinch-fade' );
	$transition_style    = in_array( $atts['transition_style'], $allowed_transitions, true ) ? $atts['transition_style'] : 'fade';

	$wrapper_classes = array( 'cph-testimonials', 'cph-testimonials--' . $transition_style );
	if ( ! empty( $slider_height ) ) {
		$wrapper_classes[] = 'cph-testimonials--fixed-height';
	}
	if ( ! empty( $atts['el_class'] ) ) {
		$wrapper_classes[] = esc_attr( $atts['el_class'] );
	}

	// Allowed icon tags.
	$allowed_tags = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span' );
	$icon_tag     = in_array( $atts['icon_tag'], $allowed_tags, true ) ? $atts['icon_tag'] : 'h2';

	$show_button = 'yes' === $atts['show_button'];
	$show_dots   = count( $testimonials ) > 1;

	ob_start();
	?>
	<style><?php echo $element_css; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></style>
	<div id="<?php echo esc_attr( $id ); ?>"
		 class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>"
		 data-autoplay="<?php echo esc_attr( $autoplay ); ?>"
		 data-autoplay-speed="<?php echo esc_attr( $autoplay_speed ); ?>">

		<div class="cph-testimonials__inner">

			<?php if ( 'none' !== $atts['icon_mode'] ) : ?>
				<div class="cph-testimonials__icon">
					<?php if ( 'character' === $atts['icon_mode'] ) : ?>
						<<?php echo esc_attr( $icon_tag ); ?> class="cph-testimonials__icon-char"><?php echo esc_html( $atts['icon_character'] ); ?></<?php echo esc_attr( $icon_tag ); ?>>
					<?php elseif ( 'icon' === $atts['icon_mode'] ) : ?>
						<i class="<?php echo esc_attr( $atts['icon_fontawesome'] ); ?>"></i>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<div class="cph-testimonials__slider" role="region" aria-live="polite">
				<?php foreach ( $testimonials as $index => $testimonial ) : ?>
					<div class="cph-testimonials__slide<?php echo 0 === $index ? ' is-active' : ''; ?>" data-index="<?php echo esc_attr( $index ); ?>">
						<div class="cph-testimonials__quote">
							<?php echo wpautop( wp_kses_post( $testimonial['quote'] ) ); ?>
						</div>
						<p class="cph-testimonials__attribution"><?php echo esc_html( $testimonial['attribution'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>

			<?php if ( $show_button ) : ?>
				<a href="<?php echo esc_url( $atts['button_url'] ); ?>"
				   class="cph-testimonials__button"
				   <?php echo 'yes' === $atts['button_new_tab'] ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
					<?php echo esc_html( $atts['button_text'] ); ?>
				</a>
			<?php endif; ?>

			<?php if ( $show_dots ) : ?>
				<div class="cph-testimonials__dots" role="tablist">
					<?php foreach ( $testimonials as $index => $testimonial ) : ?>
						<button class="cph-testimonials__dot<?php echo 0 === $index ? ' is-active' : ''; ?>"
								data-index="<?php echo esc_attr( $index ); ?>"
								role="tab"
								aria-selected="<?php echo 0 === $index ? 'true' : 'false'; ?>"
								aria-label="<?php echo esc_attr( sprintf( __( 'Go to testimonial %d', 'cph-elements' ), $index + 1 ) ); ?>">
						</button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

		</div>
	</div>
	<?php
	return ob_get_clean();
}

add_shortcode( 'cph_testimonials', 'cph_testimonials_shortcode' );
