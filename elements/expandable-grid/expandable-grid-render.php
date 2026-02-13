<?php
/**
 * CPH Expandable Grid - Shortcode Rendering
 *
 * Renders the expandable grid element HTML output.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render the CPH Expandable Grid shortcode.
 *
 * @since 1.0.0
 *
 * @param array $atts Shortcode attributes.
 * @return string HTML output.
 */
function cph_expandable_grid_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'posts_per_page' => '-1',
			'columns'        => '3',
			'orderby'        => 'menu_order',
			'button_text'    => '+ LOAD MORE',
			'close_text'     => '+ SHOW LESS',
			'el_class'       => '',
		),
		$atts,
		'cph_expandable_grid'
	);

	// Build query args.
	$query_args = array(
		'post_type'      => 'cph_grid_item',
		'posts_per_page' => intval( $atts['posts_per_page'] ),
		'post_status'    => 'publish',
	);

	// Handle orderby.
	switch ( $atts['orderby'] ) {
		case 'date_desc':
			$query_args['orderby'] = 'date';
			$query_args['order']   = 'DESC';
			break;
		case 'date_asc':
			$query_args['orderby'] = 'date';
			$query_args['order']   = 'ASC';
			break;
		case 'title':
			$query_args['orderby'] = 'title';
			$query_args['order']   = 'ASC';
			break;
		case 'menu_order':
		default:
			$query_args['orderby'] = 'menu_order date';
			$query_args['order']   = 'ASC';
			break;
	}

	$query = new WP_Query( $query_args );

	if ( ! $query->have_posts() ) {
		return '<p class="cph-grid-empty">' . esc_html__( 'No grid items found.', 'cph-elements' ) . '</p>';
	}

	$columns     = absint( $atts['columns'] );
	$button_text = sanitize_text_field( $atts['button_text'] );
	$close_text  = sanitize_text_field( $atts['close_text'] );
	$el_class    = sanitize_text_field( $atts['el_class'] );

	// Build wrapper classes.
	$classes = array( 'cph-grid' );
	if ( ! empty( $el_class ) ) {
		$classes[] = $el_class;
	}

	ob_start();
	?>
	<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
		 style="--cph-grid-columns: <?php echo esc_attr( $columns ); ?>;"
		 data-columns="<?php echo esc_attr( $columns ); ?>"
		 data-btn-text="<?php echo esc_attr( $button_text ); ?>"
		 data-close-text="<?php echo esc_attr( $close_text ); ?>">

		<?php
		while ( $query->have_posts() ) :
			$query->the_post();
			$post_id = get_the_ID();

			// Get meta fields.
			$btn_text      = get_post_meta( $post_id, '_cph_grid_btn_text', true );
			$btn_url       = get_post_meta( $post_id, '_cph_grid_btn_url', true );
			$border_top    = get_post_meta( $post_id, '_cph_grid_border_top', true );
			$border_right  = get_post_meta( $post_id, '_cph_grid_border_right', true );
			$border_bottom = get_post_meta( $post_id, '_cph_grid_border_bottom', true );
			$border_left   = get_post_meta( $post_id, '_cph_grid_border_left', true );

			$border_top_mobile    = get_post_meta( $post_id, '_cph_grid_border_top_mobile', true );
			$border_right_mobile  = get_post_meta( $post_id, '_cph_grid_border_right_mobile', true );
			$border_bottom_mobile = get_post_meta( $post_id, '_cph_grid_border_bottom_mobile', true );
			$border_left_mobile   = get_post_meta( $post_id, '_cph_grid_border_left_mobile', true );

			// Build inline CSS custom properties for borders.
			$style_parts = array();
			$sides = array( 'top', 'right', 'bottom', 'left' );

			foreach ( $sides as $side ) {
				$desktop = ${'border_' . $side};
				$mobile  = ${'border_' . $side . '_mobile'};

				if ( ! empty( $desktop ) ) {
					$style_parts[] = '--cph-grid-b-' . $side . ': ' . $desktop;
				}
				if ( ! empty( $mobile ) ) {
					$style_parts[] = '--cph-grid-b-' . $side . '-m: ' . $mobile;
				}
			}

			$style_attr = ! empty( $style_parts ) ? ' style="' . esc_attr( implode( '; ', $style_parts ) ) . ';"' : '';

			// Get post content for the detail panel.
			$content = apply_filters( 'the_content', get_the_content() );
			?>
			<div class="cph-grid__item" data-item-id="<?php echo esc_attr( $post_id ); ?>"<?php echo $style_attr; ?>>
				<h3 class="cph-grid__title"><?php the_title(); ?></h3>
				<button class="cph-grid__toggle" type="button">
					<?php echo esc_html( $button_text ); ?>
				</button>

				<div class="cph-grid__item-content" hidden>
					<?php if ( ! empty( $content ) ) : ?>
						<div class="cph-grid__body"><?php echo $content; ?></div>
					<?php endif; ?>
					<?php if ( ! empty( $btn_text ) && ! empty( $btn_url ) ) : ?>
						<a href="<?php echo esc_url( $btn_url ); ?>"
						   class="cph-grid__btn"
						   target="_blank"
						   rel="noopener noreferrer">
							<?php echo esc_html( $btn_text ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>
			<?php
		endwhile;
		?>

		<div class="cph-grid__detail" hidden aria-hidden="true">
			<div class="cph-grid__detail-inner">
				<div class="cph-grid__detail-body"></div>
				<div class="cph-grid__detail-footer">
					<a href="" class="cph-grid__detail-btn" hidden target="_blank" rel="noopener noreferrer"></a>
					<button class="cph-grid__detail-close" type="button">+ CLOSE</button>
				</div>
			</div>
		</div>

	</div>
	<?php
	wp_reset_postdata();

	return ob_get_clean();
}
add_shortcode( 'cph_expandable_grid', 'cph_expandable_grid_shortcode' );
