<?php
/**
 * Mira Mar Animated Hero - Shortcode Rendering
 *
 * Pure CSS animation — the scene was authored on a 1140x1419 stage and all
 * layer positions are percentage-based in animated-hero.css so it scales
 * fluidly with its container.
 *
 * @package CPH_Elements
 * @since   1.11.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render the [miramar_animated_hero] shortcode.
 *
 * @since 1.11.0
 *
 * @param array $atts Shortcode attributes.
 * @return string Hero markup.
 */
function cph_animated_hero_shortcode( $atts = array() ) {
	$atts = shortcode_atts(
		array(
			'show_birds'  => 'yes',
			'show_logo'   => 'yes',
			'cloud_drift' => '90',  // Seconds per cloud loop.
			'bird_pass'   => '60',  // Seconds per bird flock cycle.
			'max_width'   => '',    // e.g. "900px" — empty means fill container.
			'class'       => '',
		),
		$atts,
		'miramar_animated_hero'
	);

	$img_base = cph_element_url( 'animated-hero' ) . 'assets/img';

	$styles = array(
		'--mmh-drift:' . floatval( $atts['cloud_drift'] ) . 's',
		'--mmh-birds:' . floatval( $atts['bird_pass'] ) . 's',
	);
	if ( '' !== $atts['max_width'] ) {
		$styles[] = '--mmh-max-width:' . esc_attr( $atts['max_width'] );
	}

	$classes = 'mm-animated-hero';
	if ( '' !== $atts['class'] ) {
		$classes .= ' ' . esc_attr( $atts['class'] );
	}

	ob_start();
	?>
	<div class="<?php echo esc_attr( $classes ); ?>" style="<?php echo esc_attr( implode( ';', $styles ) ); ?>">
		<div class="mm-animated-hero__stage">
			<div class="mm-animated-hero__sky"></div>
			<div class="mm-animated-hero__band mm-animated-hero__band--high">
				<div class="mm-animated-hero__drift">
					<img src="<?php echo esc_url( $img_base . '/clouds-high2.png' ); ?>" alt="" />
					<img src="<?php echo esc_url( $img_base . '/clouds-high2.png' ); ?>" alt="" />
					<img src="<?php echo esc_url( $img_base . '/clouds-high2.png' ); ?>" alt="" />
				</div>
			</div>
			<div class="mm-animated-hero__band mm-animated-hero__band--low">
				<div class="mm-animated-hero__drift">
					<img src="<?php echo esc_url( $img_base . '/clouds-low2.png' ); ?>" alt="" />
					<img src="<?php echo esc_url( $img_base . '/clouds-low2.png' ); ?>" alt="" />
					<img src="<?php echo esc_url( $img_base . '/clouds-low2.png' ); ?>" alt="" />
				</div>
			</div>
			<?php if ( 'yes' === $atts['show_birds'] ) : ?>
				<div class="mm-animated-hero__birds">
					<img src="<?php echo esc_url( $img_base . '/bird-flock.png' ); ?>" alt="" />
				</div>
			<?php endif; ?>
			<img class="mm-animated-hero__ground" src="<?php echo esc_url( $img_base . '/ground.png' ); ?>" alt="" />
			<img class="mm-animated-hero__facade" src="<?php echo esc_url( $img_base . '/facade.webp' ); ?>" alt="Mira Mar Residences" />
			<?php if ( 'yes' === $atts['show_logo'] ) : ?>
				<img class="mm-animated-hero__logo" src="<?php echo esc_url( $img_base . '/logo.svg' ); ?>" alt="Mira Mar Residences, est. 1922" />
			<?php endif; ?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'miramar_animated_hero', 'cph_animated_hero_shortcode' );
