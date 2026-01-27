<?php
/**
 * CPH News Grid - Shortcode Rendering
 *
 * Renders the news grid element with featured post, filters, and AJAX load more.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get news card data from a post.
 *
 * @since 1.0.0
 *
 * @param int $post_id Post ID.
 * @return array|null Card data array or null if post doesn't exist.
 */
function cph_get_news_card_data( $post_id ) {
	if ( empty( $post_id ) ) {
		return null;
	}

	$post = get_post( $post_id );
	if ( ! $post || 'post' !== $post->post_type ) {
		return null;
	}

	$publication_name = get_post_meta( $post_id, '_publication_name', true );
	$publication_link = get_post_meta( $post_id, '_publication_link', true );

	return array(
		'id'               => $post_id,
		'title'            => get_the_title( $post_id ),
		'permalink'        => get_permalink( $post_id ),
		'image'            => get_the_post_thumbnail_url( $post_id, 'large' ),
		'date'             => get_the_date( 'm.d.y', $post_id ),
		'excerpt'          => get_the_excerpt( $post_id ),
		'publication_name' => $publication_name,
		'publication_link' => $publication_link,
	);
}

/**
 * Get available years from published posts.
 *
 * @since 1.0.0
 *
 * @return array Array of years with posts.
 */
function cph_get_news_years() {
	global $wpdb;

	$years = $wpdb->get_col(
		"SELECT DISTINCT YEAR(post_date) as year
		FROM {$wpdb->posts}
		WHERE post_type = 'post'
		AND post_status = 'publish'
		ORDER BY year DESC"
	);

	return $years;
}

/**
 * Render the featured post section.
 *
 * @since 1.0.0
 *
 * @param array $card     Card data.
 * @param array $settings Element settings.
 * @return string HTML output.
 */
function cph_render_featured_news( $card, $settings ) {
	if ( empty( $card ) ) {
		return '';
	}

	$section_title = $settings['featured_title'];
	$show_excerpt  = 'yes' === $settings['show_featured_excerpt'];
	$image_radius  = $settings['featured_image_radius'];

	ob_start();
	?>
	<section class="cph-news-featured">
		<?php if ( ! empty( $section_title ) ) : ?>
			<header class="cph-news-section-header">
				<h2 class="cph-news-section-header__title"><?php echo esc_html( $section_title ); ?></h2>
				<div class="cph-news-section-header__line">
					<span class="cph-news-section-header__line-accent"></span>
				</div>
			</header>
		<?php endif; ?>

		<article class="cph-news-featured__card">
			<div class="cph-news-featured__image" style="--featured-radius: <?php echo esc_attr( $image_radius ); ?>">
				<?php if ( ! empty( $card['image'] ) ) : ?>
					<img src="<?php echo esc_url( $card['image'] ); ?>"
					     alt="<?php echo esc_attr( $card['title'] ); ?>"
					     loading="lazy" />
				<?php endif; ?>
			</div>

			<div class="cph-news-featured__content">
				<?php if ( 'yes' === $settings['show_date'] ) : ?>
					<time class="cph-news-featured__date"><?php echo esc_html( $card['date'] ); ?></time>
				<?php endif; ?>

				<h3 class="cph-news-featured__title"><?php echo esc_html( $card['title'] ); ?></h3>

				<?php if ( $show_excerpt && ! empty( $card['excerpt'] ) ) : ?>
					<p class="cph-news-featured__excerpt"><?php echo esc_html( $card['excerpt'] ); ?></p>
				<?php endif; ?>

				<a href="<?php echo esc_url( $card['permalink'] ); ?>" class="cph-news-button cph-news-button--wide">
					<span>Read</span>
				</a>
			</div>
		</article>
	</section>
	<?php
	return ob_get_clean();
}

/**
 * Render the filter bar.
 *
 * @since 1.0.0
 *
 * @param array $settings Element settings.
 * @return string HTML output.
 */
function cph_render_news_filters( $settings ) {
	$show_category    = 'yes' === $settings['show_category_filter'];
	$show_year        = 'yes' === $settings['show_year_filter'];
	$show_reset       = 'yes' === $settings['show_reset'];
	$show_view_toggle = 'yes' === $settings['show_view_toggle'];
	$default_view     = $settings['default_view'];

	// Get categories.
	$categories = get_terms(
		array(
			'taxonomy'   => 'category',
			'hide_empty' => true,
		)
	);

	// Get years.
	$years = cph_get_news_years();

	// Check if we have any content to show.
	$has_filters = $show_category || $show_year || $show_reset;
	if ( ! $has_filters && ! $show_view_toggle ) {
		return '';
	}

	ob_start();
	?>
	<div class="cph-news-filters">
		<div class="cph-news-filters__left">
			<?php if ( $show_category && ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
				<div class="cph-news-filter cph-news-filter--category">
					<button type="button" class="cph-news-filter__trigger" aria-expanded="false" aria-haspopup="listbox">
						<span class="cph-news-filter__label">Categories</span>
						<svg class="cph-news-filter__arrow" width="22" height="12" viewBox="0 0 22 12" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M1 1L11 11L21 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</button>
					<ul class="cph-news-filter__dropdown" role="listbox" tabindex="-1">
						<li role="option" data-value="">All Categories</li>
						<?php foreach ( $categories as $cat ) : ?>
							<?php if ( 'uncategorized' !== $cat->slug ) : ?>
								<li role="option" data-value="<?php echo esc_attr( $cat->slug ); ?>"><?php echo esc_html( $cat->name ); ?></li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>

			<?php if ( $show_year && ! empty( $years ) ) : ?>
				<div class="cph-news-filter cph-news-filter--year">
					<button type="button" class="cph-news-filter__trigger" aria-expanded="false" aria-haspopup="listbox">
						<span class="cph-news-filter__label">Year</span>
						<svg class="cph-news-filter__arrow" width="22" height="12" viewBox="0 0 22 12" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M1 1L11 11L21 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</button>
					<ul class="cph-news-filter__dropdown" role="listbox" tabindex="-1">
						<li role="option" data-value="">All Years</li>
						<?php foreach ( $years as $year ) : ?>
							<li role="option" data-value="<?php echo esc_attr( $year ); ?>"><?php echo esc_html( $year ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>

			<?php if ( $show_reset ) : ?>
				<button type="button" class="cph-news-button cph-news-button--reset">
					<span>Reset</span>
				</button>
			<?php endif; ?>
		</div>

		<?php if ( $show_view_toggle ) : ?>
			<div class="cph-news-filters__right">
				<div class="cph-news-view-toggle">
					<button type="button" class="cph-news-view-toggle__btn<?php echo 'grid' === $default_view ? ' is-active' : ''; ?>" data-view="grid">
						<svg class="cph-news-view-toggle__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<rect x="3" y="3" width="7" height="7" rx="1"/>
							<rect x="14" y="3" width="7" height="7" rx="1"/>
							<rect x="3" y="14" width="7" height="7" rx="1"/>
							<rect x="14" y="14" width="7" height="7" rx="1"/>
						</svg>
						<span>Grid</span>
					</button>
					<button type="button" class="cph-news-view-toggle__btn<?php echo 'list' === $default_view ? ' is-active' : ''; ?>" data-view="list">
						<svg class="cph-news-view-toggle__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<line x1="3" y1="6" x2="21" y2="6"/>
							<line x1="3" y1="12" x2="21" y2="12"/>
							<line x1="3" y1="18" x2="21" y2="18"/>
						</svg>
						<span>List</span>
					</button>
				</div>
			</div>
		<?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Render a single news grid card.
 *
 * @since 1.0.0
 *
 * @param array $card     Card data.
 * @param array $settings Element settings.
 * @return string HTML output.
 */
function cph_render_news_card( $card, $settings ) {
	if ( empty( $card ) ) {
		return '';
	}

	$show_source = 'yes' === $settings['show_source'];
	$show_date   = 'yes' === $settings['show_date'];
	$card_radius = $settings['card_radius'];

	ob_start();
	?>
	<article class="cph-news-card" style="--card-radius: <?php echo esc_attr( $card_radius ); ?>">
		<div class="cph-news-card__image">
			<?php if ( ! empty( $card['image'] ) ) : ?>
				<img src="<?php echo esc_url( $card['image'] ); ?>"
				     alt="<?php echo esc_attr( $card['title'] ); ?>"
				     loading="lazy" />
			<?php endif; ?>
		</div>

		<div class="cph-news-card__content">
			<h3 class="cph-news-card__title"><?php echo esc_html( $card['title'] ); ?></h3>

			<?php if ( $show_source && ! empty( $card['publication_name'] ) ) : ?>
				<p class="cph-news-card__source"><?php echo esc_html( $card['publication_name'] ); ?></p>
			<?php endif; ?>

			<?php if ( $show_date ) : ?>
				<time class="cph-news-card__date"><?php echo esc_html( $card['date'] ); ?></time>
			<?php endif; ?>

			<a href="<?php echo esc_url( $card['permalink'] ); ?>" class="cph-news-button cph-news-button--small">
				<span>Read</span>
			</a>
		</div>
	</article>
	<?php
	return ob_get_clean();
}

/**
 * Render the news grid shortcode.
 *
 * @since 1.0.0
 *
 * @param array $atts Shortcode attributes.
 * @return string HTML output.
 */
function cph_news_grid_shortcode( $atts ) {
	// Extract attributes with defaults.
	$atts = shortcode_atts(
		array(
			'show_featured'         => 'yes',
			'featured_title'        => '',
			'default_view'          => 'grid',
			'show_view_toggle'      => 'yes',
			'show_category_filter'  => 'yes',
			'show_year_filter'      => 'yes',
			'show_reset'            => 'yes',
			'default_category'      => '',
			'posts_per_page'        => '6',
			'orderby'               => 'date',
			'show_source'           => 'yes',
			'show_date'             => 'yes',
			'show_featured_excerpt' => 'yes',
			'gap'                   => '30px',
			'gap_tablet'            => '',
			'gap_phone'             => '',
			'card_radius'           => '45px',
			'card_radius_tablet'    => '',
			'card_radius_phone'     => '',
			'featured_image_radius' => '25px',
		),
		$atts,
		'cph_news_grid'
	);

	// Build settings array.
	$settings = array(
		'show_featured'         => $atts['show_featured'],
		'featured_title'        => $atts['featured_title'],
		'default_view'          => $atts['default_view'],
		'show_view_toggle'      => $atts['show_view_toggle'],
		'show_category_filter'  => $atts['show_category_filter'],
		'show_year_filter'      => $atts['show_year_filter'],
		'show_reset'            => $atts['show_reset'],
		'default_category'      => $atts['default_category'],
		'posts_per_page'        => (int) $atts['posts_per_page'],
		'orderby'               => $atts['orderby'],
		'show_source'           => $atts['show_source'],
		'show_date'             => $atts['show_date'],
		'show_featured_excerpt' => $atts['show_featured_excerpt'],
		'gap'                   => $atts['gap'],
		'card_radius'           => $atts['card_radius'],
		'featured_image_radius' => $atts['featured_image_radius'],
	);

	// Build query args.
	$query_args = array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => $settings['posts_per_page'],
		'orderby'        => $settings['orderby'],
		'order'          => 'DESC',
		'paged'          => 1,
	);

	// Apply default category filter.
	if ( ! empty( $settings['default_category'] ) ) {
		$query_args['category_name'] = $settings['default_category'];
	}

	// Determine offset for grid if showing featured post.
	$featured_card = null;
	if ( 'yes' === $settings['show_featured'] ) {
		// Get the latest post for featured section.
		$featured_query = new WP_Query(
			array(
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				'orderby'        => $settings['orderby'],
				'order'          => 'DESC',
				'category_name'  => $settings['default_category'],
			)
		);

		if ( $featured_query->have_posts() ) {
			$featured_query->the_post();
			$featured_card = cph_get_news_card_data( get_the_ID() );
			wp_reset_postdata();

			// Exclude featured post from main grid.
			$query_args['post__not_in'] = array( $featured_card['id'] );
		}
	}

	// Run main grid query.
	$query = new WP_Query( $query_args );

	// Calculate total posts for load more.
	$count_args = array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'fields'         => 'ids',
		'category_name'  => $settings['default_category'],
	);

	if ( $featured_card ) {
		$count_args['post__not_in'] = array( $featured_card['id'] );
	}

	$total_posts = count( get_posts( $count_args ) );
	$has_more    = $total_posts > $settings['posts_per_page'];

	// Generate unique ID for this instance.
	static $instance_id = 0;
	++$instance_id;
	$grid_id = 'cph-news-grid-' . $instance_id;

	// Build responsive CSS using style block (not inline) for proper media query support.
	$desktop_vars = array(
		'--grid-gap: ' . esc_attr( $settings['gap'] ),
		'--card-radius: ' . esc_attr( $settings['card_radius'] ),
	);
	$element_css = '#' . esc_attr( $grid_id ) . ' { ' . implode( '; ', $desktop_vars ) . '; }';

	// Tablet styles (max-width: 999px).
	$tablet_vars = array();
	if ( ! empty( $atts['gap_tablet'] ) ) {
		$tablet_vars[] = '--grid-gap: ' . esc_attr( $atts['gap_tablet'] );
	}
	if ( ! empty( $atts['card_radius_tablet'] ) ) {
		$tablet_vars[] = '--card-radius: ' . esc_attr( $atts['card_radius_tablet'] );
	}
	if ( ! empty( $tablet_vars ) ) {
		$element_css .= ' @media only screen and (max-width: 999px) { #' . esc_attr( $grid_id ) . ' { ' . implode( '; ', $tablet_vars ) . '; } }';
	}

	// Phone styles (max-width: 690px).
	$phone_vars = array();
	if ( ! empty( $atts['gap_phone'] ) ) {
		$phone_vars[] = '--grid-gap: ' . esc_attr( $atts['gap_phone'] );
	}
	if ( ! empty( $atts['card_radius_phone'] ) ) {
		$phone_vars[] = '--card-radius: ' . esc_attr( $atts['card_radius_phone'] );
	}
	if ( ! empty( $phone_vars ) ) {
		$element_css .= ' @media only screen and (max-width: 690px) { #' . esc_attr( $grid_id ) . ' { ' . implode( '; ', $phone_vars ) . '; } }';
	}

	ob_start();
	?>
	<style><?php echo $element_css; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></style>
	<div id="<?php echo esc_attr( $grid_id ); ?>" class="cph-news-grid"
	     data-posts-per-page="<?php echo esc_attr( $settings['posts_per_page'] ); ?>"
	     data-orderby="<?php echo esc_attr( $settings['orderby'] ); ?>"
	     data-default-category="<?php echo esc_attr( $settings['default_category'] ); ?>"
	     data-excluded-post="<?php echo esc_attr( $featured_card ? $featured_card['id'] : '' ); ?>"
	     data-show-source="<?php echo esc_attr( $settings['show_source'] ); ?>"
	     data-show-date="<?php echo esc_attr( $settings['show_date'] ); ?>"
	     data-card-radius="<?php echo esc_attr( $settings['card_radius'] ); ?>"
	     data-default-view="<?php echo esc_attr( $settings['default_view'] ); ?>">

		<?php
		// Render featured section.
		if ( 'yes' === $settings['show_featured'] && $featured_card ) {
			echo cph_render_featured_news( $featured_card, $settings ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		// Render filters.
		echo cph_render_news_filters( $settings ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>

		<div class="cph-news-grid__items" data-view="<?php echo esc_attr( $settings['default_view'] ); ?>" data-page="1">
			<?php
			if ( $query->have_posts() ) {
				$default_view = $settings['default_view'];
				while ( $query->have_posts() ) {
					$query->the_post();
					$card = cph_get_news_card_data( get_the_ID() );
					if ( 'list' === $default_view ) {
						echo cph_render_news_list_item( $card, $settings ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					} else {
						echo cph_render_news_card( $card, $settings ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
				}
				wp_reset_postdata();
			} else {
				echo '<p class="cph-news-grid__empty">' . esc_html__( 'No posts found.', 'cph-elements' ) . '</p>';
			}
			?>
		</div>

		<?php if ( $has_more ) : ?>
			<div class="cph-news-grid__load-more">
				<button type="button" class="cph-news-button cph-news-button--wide cph-news-load-more">
					<span>Load More</span>
				</button>
			</div>
		<?php endif; ?>

	</div>
	<?php
	return ob_get_clean();
}

// Register the shortcode.
add_shortcode( 'cph_news_grid', 'cph_news_grid_shortcode' );

/**
 * Render a single news list item.
 *
 * @since 1.0.0
 *
 * @param array $card     Card data.
 * @param array $settings Element settings.
 * @return string HTML output.
 */
function cph_render_news_list_item( $card, $settings ) {
	if ( empty( $card ) ) {
		return '';
	}

	$show_source = 'yes' === $settings['show_source'];
	$show_date   = 'yes' === $settings['show_date'];

	ob_start();
	?>
	<a href="<?php echo esc_url( $card['permalink'] ); ?>" class="cph-news-list-item">
		<div class="cph-news-list-item__image">
			<?php if ( ! empty( $card['image'] ) ) : ?>
				<img src="<?php echo esc_url( $card['image'] ); ?>"
				     alt="<?php echo esc_attr( $card['title'] ); ?>"
				     loading="lazy" />
			<?php endif; ?>
		</div>

		<div class="cph-news-list-item__content">
			<div class="cph-news-list-item__meta">
				<?php if ( $show_date ) : ?>
					<span class="cph-news-list-item__date"><?php echo esc_html( $card['date'] ); ?></span>
				<?php endif; ?>
				<?php if ( $show_source && ! empty( $card['publication_name'] ) ) : ?>
					<span class="cph-news-list-item__source"><?php echo esc_html( $card['publication_name'] ); ?></span>
				<?php endif; ?>
			</div>
			<h3 class="cph-news-list-item__title"><?php echo esc_html( $card['title'] ); ?></h3>
		</div>

		<div class="cph-news-list-item__arrow">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
				<line x1="5" y1="12" x2="19" y2="12"/>
				<polyline points="12 5 19 12 12 19"/>
			</svg>
		</div>
	</a>
	<?php
	return ob_get_clean();
}
