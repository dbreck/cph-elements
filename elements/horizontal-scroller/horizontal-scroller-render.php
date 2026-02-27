<?php
/**
 * CPH Horizontal Scroller - Shortcode Rendering
 *
 * Renders the horizontal scroller element HTML output.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render the CPH Horizontal Scroller shortcode.
 *
 * @since 1.0.0
 *
 * @param array $atts Shortcode attributes.
 * @return string HTML output.
 */
function cph_horizontal_scroller_shortcode( $atts ) {
	// Generate unique ID for this instance.
	static $instance_id = 0;
	++$instance_id;
	$scroller_id = 'cph-scroller-' . $instance_id;

	$atts = shortcode_atts(
		array(
			'post_type'            => 'bti_team',
			'posts_per_page'       => '6',
			'orderby'              => 'menu_order',
			'category'             => '',
			'direction'            => 'left',
			'card_width'           => '25vw',
			'card_width_tablet'    => '',
			'card_width_phone'     => '',
			'aspect_ratio'         => '3/4',
			'custom_aspect_ratio'  => '3/4',
			'gap'                  => '178px',
			'gap_tablet'           => '',
			'gap_phone'            => '',
			'arrow_offset'         => '158px',
			'arrow_offset_tablet'  => '',
			'arrow_offset_phone'   => '',
			'content_inset'        => '356px',
			'content_inset_tablet' => '',
			'content_inset_phone'  => '',
			'el_class'             => '',
			'team_category'        => '',
			'show_filter'          => 'no',
			'show_short_bio'       => 'yes',
			'button_text'          => '+ LOAD MORE',
			'button_style'         => 'text-link',
			'arrow_mode'           => 'single',
			'border_radius'        => '25px',
			'max_height'           => '',
			'visible_cards'        => '',
			'visible_cards_tablet' => '',
			'visible_cards_phone'  => '',
		),
		$atts,
		'cph_horizontal_scroller'
	);

	// Build query args.
	$direction  = sanitize_text_field( $atts['direction'] );
	$post_type  = sanitize_text_field( $atts['post_type'] );
	$orderby    = sanitize_text_field( $atts['orderby'] );
	$query_args = array(
		'post_type'      => $post_type,
		'posts_per_page' => absint( $atts['posts_per_page'] ),
		'post_status'    => 'publish',
	);

	// Set ordering based on element setting.
	switch ( $orderby ) {
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
			$query_args['orderby'] = 'menu_order';
			$query_args['order']   = 'ASC';
			break;
	}

	// Add category filter for blog posts.
	if ( 'post' === $atts['post_type'] && ! empty( $atts['category'] ) ) {
		$query_args['category_name'] = sanitize_text_field( $atts['category'] );
	}

	// Add team category filter.
	if ( 'bti_team' === $atts['post_type'] && ! empty( $atts['team_category'] ) ) {
		$query_args['tax_query'] = array(
			array(
				'taxonomy' => 'bti_team_category',
				'field'    => 'slug',
				'terms'    => sanitize_text_field( $atts['team_category'] ),
			),
		);
	}

	$query = new WP_Query( $query_args );

	if ( ! $query->have_posts() ) {
		return '<p class="cph-scroller-empty">' . esc_html__( 'No items found.', 'cph-elements' ) . '</p>';
	}

	// Build CSS custom properties.
	$card_width           = sanitize_text_field( $atts['card_width'] );
	$card_width_tablet    = sanitize_text_field( $atts['card_width_tablet'] );
	$card_width_phone     = sanitize_text_field( $atts['card_width_phone'] );
	$aspect_ratio         = sanitize_text_field( $atts['aspect_ratio'] );
	$gap                  = sanitize_text_field( $atts['gap'] );
	$gap_tablet           = sanitize_text_field( $atts['gap_tablet'] );
	$gap_phone            = sanitize_text_field( $atts['gap_phone'] );
	$arrow_offset         = sanitize_text_field( $atts['arrow_offset'] );
	$arrow_offset_tablet  = sanitize_text_field( $atts['arrow_offset_tablet'] );
	$arrow_offset_phone   = sanitize_text_field( $atts['arrow_offset_phone'] );
	$content_inset        = sanitize_text_field( $atts['content_inset'] );
	$content_inset_tablet = sanitize_text_field( $atts['content_inset_tablet'] );
	$content_inset_phone  = sanitize_text_field( $atts['content_inset_phone'] );
	$el_class             = sanitize_html_class( $atts['el_class'] );
	$post_type            = sanitize_text_field( $atts['post_type'] );
	$border_radius        = sanitize_text_field( $atts['border_radius'] );
	$max_height           = sanitize_text_field( $atts['max_height'] );

	// Use custom aspect ratio if "custom" is selected.
	if ( 'custom' === $aspect_ratio ) {
		$aspect_ratio = sanitize_text_field( $atts['custom_aspect_ratio'] );
	}

	// Build responsive CSS using style block (not inline) for proper media query support.
	// Desktop styles (base values).
	$desktop_vars = array(
		'--card-width: ' . esc_attr( $card_width ),
		'--card-aspect-ratio: ' . esc_attr( $aspect_ratio ),
		'--gap: ' . esc_attr( $gap ),
		'--arrow-offset: ' . esc_attr( $arrow_offset ),
		'--content-inset: ' . esc_attr( $content_inset ),
		'--card-border-radius: ' . esc_attr( $border_radius ),
	);
	if ( ! empty( $max_height ) ) {
		$desktop_vars[] = '--card-max-height: ' . esc_attr( $max_height );
	}

	$element_css = '#' . esc_attr( $scroller_id ) . ' { ' . implode( '; ', $desktop_vars ) . '; }';

	// Tablet styles (max-width: 999px).
	$tablet_vars = array();
	if ( ! empty( $card_width_tablet ) ) {
		$tablet_vars[] = '--card-width: ' . esc_attr( $card_width_tablet );
	}
	if ( ! empty( $gap_tablet ) ) {
		$tablet_vars[] = '--gap: ' . esc_attr( $gap_tablet );
	}
	if ( ! empty( $arrow_offset_tablet ) ) {
		$tablet_vars[] = '--arrow-offset: ' . esc_attr( $arrow_offset_tablet );
	}
	if ( ! empty( $content_inset_tablet ) ) {
		$tablet_vars[] = '--content-inset: ' . esc_attr( $content_inset_tablet );
	}
	if ( ! empty( $tablet_vars ) ) {
		$element_css .= ' @media only screen and (max-width: 999px) { #' . esc_attr( $scroller_id ) . ' { ' . implode( '; ', $tablet_vars ) . '; } }';
	}

	// Phone styles (max-width: 690px).
	$phone_vars = array();
	if ( ! empty( $card_width_phone ) ) {
		$phone_vars[] = '--card-width: ' . esc_attr( $card_width_phone );
	}
	if ( ! empty( $gap_phone ) ) {
		$phone_vars[] = '--gap: ' . esc_attr( $gap_phone );
	}
	if ( ! empty( $arrow_offset_phone ) ) {
		$phone_vars[] = '--arrow-offset: ' . esc_attr( $arrow_offset_phone );
	}
	if ( ! empty( $content_inset_phone ) ) {
		$phone_vars[] = '--content-inset: ' . esc_attr( $content_inset_phone );
	}
	if ( ! empty( $phone_vars ) ) {
		$element_css .= ' @media only screen and (max-width: 690px) { #' . esc_attr( $scroller_id ) . ' { ' . implode( '; ', $phone_vars ) . '; } }';
	}

	// Build classes.
	$classes = array(
		'cph-scroller',
		'cph-scroller--' . $direction,
		'cph-scroller--' . $post_type,
	);
	if ( ! empty( $el_class ) ) {
		$classes[] = $el_class;
	}

	$arrow_mode = sanitize_text_field( $atts['arrow_mode'] );
	if ( 'both' === $arrow_mode ) {
		$classes[] = 'cph-scroller--arrows-both';
	}

	ob_start();

	// Output element CSS (includes responsive media queries).
	echo '<style>' . $element_css . '</style>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CSS is escaped above.
	?>
	<div id="<?php echo esc_attr( $scroller_id ); ?>"
		 class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
		 data-direction="<?php echo esc_attr( $direction ); ?>"
		 data-post-type="<?php echo esc_attr( $post_type ); ?>"
		 data-arrow-mode="<?php echo esc_attr( $arrow_mode ); ?>"
		 data-visible-cards="<?php echo esc_attr( sanitize_text_field( $atts['visible_cards'] ) ); ?>"
		 data-visible-cards-tablet="<?php echo esc_attr( sanitize_text_field( $atts['visible_cards_tablet'] ) ); ?>"
		 data-visible-cards-phone="<?php echo esc_attr( sanitize_text_field( $atts['visible_cards_phone'] ) ); ?>">

		<?php
		$show_filter = sanitize_text_field( $atts['show_filter'] );
		if ( 'yes' === $show_filter && 'bti_team' === $post_type ) :
			$filter_terms = get_terms(
				array(
					'taxonomy'   => 'bti_team_category',
					'hide_empty' => true,
				)
			);
			if ( ! is_wp_error( $filter_terms ) && ! empty( $filter_terms ) ) :
			?>
			<nav class="cph-scroller__filter" aria-label="<?php esc_attr_e( 'Team filter', 'cph-elements' ); ?>">
				<button class="cph-scroller__filter-btn is-active" data-filter="" type="button"><?php esc_html_e( 'All', 'cph-elements' ); ?></button>
				<?php foreach ( $filter_terms as $term ) : ?>
					<button class="cph-scroller__filter-btn" data-filter="<?php echo esc_attr( $term->slug ); ?>" type="button"><?php echo esc_html( $term->name ); ?></button>
				<?php endforeach; ?>
			</nav>
			<?php
			endif;
		endif;
		?>

		<?php if ( 'single' === $arrow_mode ) : ?>
			<button class="cph-scroller__arrow" type="button" aria-label="<?php esc_attr_e( 'Scroll carousel', 'cph-elements' ); ?>">
				<svg width="60" height="12" viewBox="0 0 60 12" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
					<path d="M0 6H58M58 6L53 1M58 6L53 11" stroke="currentColor" stroke-width="1"/>
				</svg>
			</button>
		<?php endif; ?>

		<?php if ( 'both' === $arrow_mode ) : ?>
			<button class="cph-scroller__arrow cph-scroller__arrow--prev" type="button" aria-label="<?php esc_attr_e( 'Previous', 'cph-elements' ); ?>">
				<svg width="60" height="12" viewBox="0 0 60 12" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
					<path d="M60 6H2M2 6L7 1M2 6L7 11" stroke="currentColor" stroke-width="1"/>
				</svg>
			</button>
			<button class="cph-scroller__arrow cph-scroller__arrow--next" type="button" aria-label="<?php esc_attr_e( 'Next', 'cph-elements' ); ?>">
				<svg width="60" height="12" viewBox="0 0 60 12" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
					<path d="M0 6H58M58 6L53 1M58 6L53 11" stroke="currentColor" stroke-width="1"/>
				</svg>
			</button>
		<?php endif; ?>

		<div class="cph-scroller__viewport">
			<div class="cph-scroller__track">
				<?php
				while ( $query->have_posts() ) :
					$query->the_post();
					$post_id = get_the_ID();

					if ( 'bti_team' === $post_type ) {
						echo cph_render_team_card( $post_id, $atts );
					} else {
						echo cph_render_blog_card( $post_id );
					}
				endwhile;
				?>
			</div>
		</div>

	</div>
	<?php
	wp_reset_postdata();

	return ob_get_clean();
}
add_shortcode( 'cph_horizontal_scroller', 'cph_horizontal_scroller_shortcode' );

/**
 * Render a Team member card.
 *
 * @since 1.0.0
 *
 * @param int   $post_id Post ID.
 * @param array $atts    Shortcode attributes.
 * @return string Card HTML.
 */
function cph_render_team_card( $post_id, $atts = array() ) {
	$name      = get_the_title( $post_id );
	$job_title = get_post_meta( $post_id, '_bti_team_job_title', true );
	$short_bio = get_post_meta( $post_id, '_bti_team_short_bio', true );
	$image_url = get_the_post_thumbnail_url( $post_id, 'large' );
	$permalink = get_permalink( $post_id );

	$show_short_bio = isset( $atts['show_short_bio'] ) ? $atts['show_short_bio'] : 'yes';
	$btn_text       = isset( $atts['button_text'] ) ? $atts['button_text'] : '+ LOAD MORE';
	$btn_style      = isset( $atts['button_style'] ) ? $atts['button_style'] : 'text-link';

	$terms          = wp_get_post_terms( $post_id, 'bti_team_category', array( 'fields' => 'slugs' ) );
	$categories_str = ! is_wp_error( $terms ) ? implode( ' ', $terms ) : '';

	ob_start();
	?>
	<article class="cph-scroller__card cph-scroller__card--team" data-post-id="<?php echo esc_attr( $post_id ); ?>" data-categories="<?php echo esc_attr( $categories_str ); ?>">
		<div class="cph-scroller__card-image">
			<?php if ( $image_url ) : ?>
				<img src="<?php echo esc_url( $image_url ); ?>"
					 alt="<?php echo esc_attr( $name ); ?>"
					 loading="lazy" />
			<?php endif; ?>
		</div>
		<div class="cph-scroller__card-content">
			<h3 class="cph-scroller__card-name"><?php echo esc_html( $name ); ?></h3>
			<?php if ( $job_title ) : ?>
				<p class="cph-scroller__card-title"><?php echo esc_html( $job_title ); ?></p>
			<?php endif; ?>
			<?php if ( 'yes' === $show_short_bio && $short_bio ) : ?>
				<p><?php echo esc_html( $short_bio ); ?></p>
			<?php endif; ?>
			<?php if ( 'text-link' === $btn_style ) : ?>
				<button class="cph-scroller__card-button cph-scroller__card-button--text" type="button" data-team-id="<?php echo esc_attr( $post_id ); ?>"><?php echo esc_html( $btn_text ); ?></button>
			<?php else : ?>
				<button class="cph-scroller__card-button" type="button" data-team-id="<?php echo esc_attr( $post_id ); ?>" data-text="<?php echo esc_attr( $btn_text ); ?>">
					<span><?php echo esc_html( $btn_text ); ?></span>
				</button>
			<?php endif; ?>
		</div>
	</article>
	<?php
	return ob_get_clean();
}

/**
 * Output the team member popup HTML shell.
 *
 * Only outputs once per page, regardless of how many scrollers exist.
 *
 * @since 1.0.0
 */
function cph_team_popup_shell() {
	static $output = false;

	if ( $output ) {
		return;
	}

	$output = true;
	?>
	<div class="cph-team-popup" id="cph-team-popup" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="cph-team-popup-name">
		<div class="cph-team-popup__backdrop"></div>
		<div class="cph-team-popup__container">
			<button class="cph-team-popup__close" type="button" aria-label="<?php esc_attr_e( 'Close popup', 'cph-elements' ); ?>">
				<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
					<line x1="1" y1="1" x2="39" y2="39" stroke="currentColor" stroke-width="1.5"/>
					<line x1="39" y1="1" x2="1" y2="39" stroke="currentColor" stroke-width="1.5"/>
				</svg>
			</button>
			<div class="cph-team-popup__content">
				<div class="cph-team-popup__image-col">
					<div class="cph-team-popup__image">
						<img src="" alt="" id="cph-team-popup-image" />
					</div>
				</div>
				<div class="cph-team-popup__text-col">
					<h2 class="cph-team-popup__name" id="cph-team-popup-name"></h2>
					<p class="cph-team-popup__title" id="cph-team-popup-title"></p>
					<div class="cph-team-popup__bio" id="cph-team-popup-bio"></div>
				</div>
			</div>
		</div>
		<div class="cph-team-popup__loading" aria-hidden="true">
			<div class="cph-team-popup__spinner"></div>
		</div>
	</div>
	<?php
}

/**
 * AJAX handler to fetch team member data.
 *
 * @since 1.0.0
 */
function cph_ajax_get_team_member() {
	// Verify nonce.
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'cph_team_popup_nonce' ) ) {
		wp_send_json_error( array( 'message' => __( 'Security check failed.', 'cph-elements' ) ) );
	}

	// Get and validate post ID.
	$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;

	if ( ! $post_id ) {
		wp_send_json_error( array( 'message' => __( 'Invalid team member ID.', 'cph-elements' ) ) );
	}

	// Verify it's a team member post.
	$post = get_post( $post_id );

	if ( ! $post || 'bti_team' !== $post->post_type ) {
		wp_send_json_error( array( 'message' => __( 'Team member not found.', 'cph-elements' ) ) );
	}

	// Get team member data.
	$name      = get_the_title( $post_id );
	$job_title = get_post_meta( $post_id, '_bti_team_job_title', true );
	$bio       = get_post_meta( $post_id, '_bti_team_bio', true );
	$image_url = get_the_post_thumbnail_url( $post_id, 'large' );

	// Process bio - apply wpautop for proper paragraph formatting.
	$bio_html = $bio ? wpautop( wp_kses_post( $bio ) ) : '';

	wp_send_json_success(
		array(
			'name'      => $name,
			'job_title' => $job_title,
			'bio'       => $bio_html,
			'image_url' => $image_url,
		)
	);
}
add_action( 'wp_ajax_cph_get_team_member', 'cph_ajax_get_team_member' );
add_action( 'wp_ajax_nopriv_cph_get_team_member', 'cph_ajax_get_team_member' );

/**
 * Enqueue popup script data (AJAX URL and nonce).
 *
 * @since 1.0.0
 */
function cph_team_popup_script_data() {
	wp_localize_script(
		'cph-horizontal-scroller',
		'btiTeamPopup',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'cph_team_popup_nonce' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'cph_team_popup_script_data', 61 );

/**
 * Output popup shell in footer when team scrollers are present.
 *
 * @since 1.0.0
 */
add_action( 'wp_footer', 'cph_team_popup_shell' );

/**
 * Render a Blog post card.
 *
 * @since 1.0.0
 *
 * @param int $post_id Post ID.
 * @return string Card HTML.
 */
function cph_render_blog_card( $post_id ) {
	$title     = get_the_title( $post_id );
	$date      = get_the_date( 'm.d.y', $post_id );
	$image_url = get_the_post_thumbnail_url( $post_id, 'large' );
	$permalink = get_permalink( $post_id );

	// Get primary category.
	$categories = get_the_category( $post_id );
	$category   = ! empty( $categories ) ? $categories[0]->name : '';

	ob_start();
	?>
	<article class="cph-scroller__card cph-scroller__card--blog" data-post-id="<?php echo esc_attr( $post_id ); ?>">
		<a href="<?php echo esc_url( $permalink ); ?>" class="cph-scroller__card-link">
			<div class="cph-scroller__card-image">
				<?php if ( $image_url ) : ?>
					<img src="<?php echo esc_url( $image_url ); ?>"
						 alt="<?php echo esc_attr( $title ); ?>"
						 loading="lazy" />
				<?php endif; ?>
				<div class="cph-scroller__card-overlay"></div>
			</div>
			<div class="cph-scroller__card-content">
				<h3 class="cph-scroller__card-name"><?php echo esc_html( $title ); ?></h3>
				<?php if ( $category ) : ?>
					<p class="cph-scroller__card-category"><?php echo esc_html( $category ); ?></p>
				<?php endif; ?>
				<p class="cph-scroller__card-date"><?php echo esc_html( $date ); ?></p>
			</div>
		</a>
	</article>
	<?php
	return ob_get_clean();
}
