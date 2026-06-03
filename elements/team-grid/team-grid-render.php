<?php
/**
 * CPH Team Grid - Shortcode Rendering
 *
 * Renders the team grid element HTML output.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render the CPH Team Grid shortcode.
 *
 * @since 1.0.0
 *
 * @param array $atts Shortcode attributes.
 * @return string HTML output.
 */
function cph_team_grid_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'category'       => '',
			'columns'        => '3',
			'posts_per_page' => '9',
			'card_height'    => '544px',
			'gap'            => '30px',
			'row_gap'        => '30px',
			'el_class'       => '',
		),
		$atts,
		'cph_team_grid'
	);

	// Build query args.
	$query_args = array(
		'post_type'      => 'bti_team',
		'posts_per_page' => absint( $atts['posts_per_page'] ),
		'post_status'    => 'publish',
		'orderby'        => 'menu_order date',
		'order'          => 'ASC',
	);

	// Add category filter if specified.
	$category = sanitize_text_field( $atts['category'] );
	if ( ! empty( $category ) ) {
		$query_args['tax_query'] = array(
			array(
				'taxonomy' => 'bti_team_category',
				'field'    => 'slug',
				'terms'    => $category,
			),
		);
	}

	$query = new WP_Query( $query_args );

	if ( ! $query->have_posts() ) {
		return '<p class="cph-team-grid-empty">' . esc_html__( 'No team members found.', 'cph-elements' ) . '</p>';
	}

	// Build CSS custom properties.
	$columns     = absint( $atts['columns'] );
	$card_height = sanitize_text_field( $atts['card_height'] );
	$gap         = sanitize_text_field( $atts['gap'] );
	$row_gap     = sanitize_text_field( $atts['row_gap'] );
	$el_class    = sanitize_html_class( $atts['el_class'] );

	$style = sprintf(
		'--columns: %d; --card-height: %s; --column-gap: %s; --row-gap: %s;',
		$columns,
		esc_attr( $card_height ),
		esc_attr( $gap ),
		esc_attr( $row_gap )
	);

	// Build classes.
	$classes = array( 'cph-team-grid' );
	if ( ! empty( $el_class ) ) {
		$classes[] = $el_class;
	}

	ob_start();
	?>
	<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
		 style="<?php echo esc_attr( $style ); ?>">
		<?php
		while ( $query->have_posts() ) :
			$query->the_post();
			$post_id = get_the_ID();
			echo cph_render_team_grid_card( $post_id );
		endwhile;
		?>
	</div>
	<?php
	wp_reset_postdata();

	return ob_get_clean();
}
add_shortcode( 'cph_team_grid', 'cph_team_grid_shortcode' );

/**
 * Render a Team member card for the grid.
 *
 * @since 1.0.0
 *
 * @param int $post_id Post ID.
 * @return string Card HTML.
 */
function cph_render_team_grid_card( $post_id ) {
	$name      = get_the_title( $post_id );
	$job_title = get_post_meta( $post_id, '_bti_team_job_title', true );
	$image_url = get_the_post_thumbnail_url( $post_id, 'large' );

	// The card only opens a popup when there's a bio to show and linking
	// hasn't been disabled for this member. Otherwise it renders as static.
	$bio           = get_post_meta( $post_id, '_bti_team_bio', true );
	$disable_popup = '1' === get_post_meta( $post_id, '_bti_team_disable_popup', true );
	$is_clickable  = ! $disable_popup && '' !== trim( wp_strip_all_tags( (string) $bio ) );

	ob_start();
	?>
	<article class="cph-team-grid__card" data-post-id="<?php echo esc_attr( $post_id ); ?>">
		<?php if ( $is_clickable ) : ?>
		<button class="cph-team-grid__card-button" type="button" data-team-id="<?php echo esc_attr( $post_id ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'View %s profile', 'cph-elements' ), $name ) ); ?>">
		<?php else : ?>
		<div class="cph-team-grid__card-button cph-team-grid__card-button--static">
		<?php endif; ?>
			<div class="cph-team-grid__card-image">
				<?php if ( $image_url ) : ?>
					<img src="<?php echo esc_url( $image_url ); ?>"
						 alt="<?php echo esc_attr( $name ); ?>"
						 loading="lazy" />
				<?php endif; ?>
			</div>
			<div class="cph-team-grid__card-content">
				<h3 class="cph-team-grid__card-name"><?php echo esc_html( $name ); ?></h3>
				<?php if ( $job_title ) : ?>
					<p class="cph-team-grid__card-title"><?php echo esc_html( $job_title ); ?></p>
				<?php endif; ?>
			</div>
		<?php if ( $is_clickable ) : ?>
		</button>
		<?php else : ?>
		</div>
		<?php endif; ?>
	</article>
	<?php
	return ob_get_clean();
}
