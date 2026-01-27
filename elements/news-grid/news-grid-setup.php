<?php
/**
 * CPH News Grid - Setup
 *
 * AJAX handlers, localized script data, publication meta box,
 * and publication byline/source content filters.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * ==========================================================================
 * AJAX HANDLERS
 * ==========================================================================
 */

if ( ! function_exists( 'cph_news_grid_load_more' ) ) {
	/**
	 * AJAX handler for loading more news posts.
	 *
	 * @since 1.0.0
	 */
	function cph_news_grid_load_more() {
		// Verify nonce.
		check_ajax_referer( 'cph_news_grid_nonce', 'nonce' );

		// Get parameters.
		$page            = isset( $_POST['page'] ) ? (int) $_POST['page'] : 1;
		$posts_per_page  = isset( $_POST['posts_per_page'] ) ? (int) $_POST['posts_per_page'] : 6;
		$category        = isset( $_POST['category'] ) ? sanitize_text_field( wp_unslash( $_POST['category'] ) ) : '';
		$year            = isset( $_POST['year'] ) ? (int) $_POST['year'] : 0;
		$orderby         = isset( $_POST['orderby'] ) ? sanitize_text_field( wp_unslash( $_POST['orderby'] ) ) : 'date';
		$excluded_post   = isset( $_POST['excluded_post'] ) ? (int) $_POST['excluded_post'] : 0;
		$show_source     = isset( $_POST['show_source'] ) ? sanitize_text_field( wp_unslash( $_POST['show_source'] ) ) : 'yes';
		$show_date       = isset( $_POST['show_date'] ) ? sanitize_text_field( wp_unslash( $_POST['show_date'] ) ) : 'yes';
		$card_radius     = isset( $_POST['card_radius'] ) ? sanitize_text_field( wp_unslash( $_POST['card_radius'] ) ) : '45px';

		// Build settings for card rendering.
		$settings = array(
			'show_source' => $show_source,
			'show_date'   => $show_date,
			'card_radius' => $card_radius,
		);

		// Build query args.
		$query_args = array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => $posts_per_page,
			'paged'          => $page,
			'orderby'        => $orderby,
			'order'          => 'DESC',
		);

		// Exclude featured post.
		if ( $excluded_post ) {
			$query_args['post__not_in'] = array( $excluded_post );
		}

		// Category filter.
		if ( ! empty( $category ) ) {
			$query_args['category_name'] = $category;
		}

		// Year filter.
		if ( $year ) {
			$query_args['date_query'] = array(
				array(
					'year' => $year,
				),
			);
		}

		$query = new WP_Query( $query_args );

		$html_grid = '';
		$html_list = '';

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$card       = cph_get_news_card_data( get_the_ID() );
				$html_grid .= cph_render_news_card( $card, $settings );
				$html_list .= cph_render_news_list_item( $card, $settings );
			}
			wp_reset_postdata();
		}

		// Calculate if there are more posts.
		$count_args = array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields'         => 'ids',
		);

		if ( $excluded_post ) {
			$count_args['post__not_in'] = array( $excluded_post );
		}

		if ( ! empty( $category ) ) {
			$count_args['category_name'] = $category;
		}

		if ( $year ) {
			$count_args['date_query'] = array(
				array(
					'year' => $year,
				),
			);
		}

		$total_posts   = count( get_posts( $count_args ) );
		$loaded_posts  = $page * $posts_per_page;
		$has_more      = $total_posts > $loaded_posts;

		wp_send_json_success(
			array(
				'html_grid' => $html_grid,
				'html_list' => $html_list,
				'has_more'  => $has_more,
				'total'     => $total_posts,
				'loaded'    => $loaded_posts,
			)
		);
	}
}

add_action( 'wp_ajax_cph_news_load_more', 'cph_news_grid_load_more' );
add_action( 'wp_ajax_nopriv_cph_news_load_more', 'cph_news_grid_load_more' );

/*
 * ==========================================================================
 * LOCALIZE SCRIPT DATA
 * ==========================================================================
 */

if ( ! function_exists( 'cph_news_grid_localize_script' ) ) {
	/**
	 * Localize the news grid script with AJAX data.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function cph_news_grid_localize_script() {
		wp_localize_script(
			'cph-news-grid',
			'btiNewsGridData',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'cph_news_grid_nonce' ),
			)
		);
	}
}

add_action( 'wp_enqueue_scripts', 'cph_news_grid_localize_script', 60 );

/*
 * ==========================================================================
 * PUBLICATION META BOX
 * ==========================================================================
 */

if ( ! function_exists( 'cph_post_publication_add_meta_box' ) ) {
	/**
	 * Add Publication Source meta box to Edit Post screen.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function cph_post_publication_add_meta_box() {
		add_meta_box(
			'cph_post_publication',
			__( 'Publication Source', 'cph-elements' ),
			'cph_post_publication_meta_box_callback',
			'post',
			'side',
			'high'
		);
	}
}

add_action( 'add_meta_boxes', 'cph_post_publication_add_meta_box' );

if ( ! function_exists( 'cph_post_publication_meta_box_callback' ) ) {
	/**
	 * Render the Publication Source meta box.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post The post object.
	 * @return void
	 */
	function cph_post_publication_meta_box_callback( $post ) {
		wp_nonce_field( 'cph_post_publication', 'cph_post_publication_nonce' );

		$name = get_post_meta( $post->ID, '_publication_name', true );
		$link = get_post_meta( $post->ID, '_publication_link', true );
		?>
		<p>
			<label for="cph_publication_name">
				<strong><?php esc_html_e( 'Publication Name', 'cph-elements' ); ?></strong>
			</label>
		</p>
		<p>
			<input type="text"
				id="cph_publication_name"
				name="cph_publication_name"
				value="<?php echo esc_attr( $name ); ?>"
				class="widefat"
				placeholder="<?php esc_attr_e( 'e.g., Business Observer', 'cph-elements' ); ?>" />
		</p>
		<p>
			<label for="cph_publication_link">
				<strong><?php esc_html_e( 'Publication Link', 'cph-elements' ); ?></strong>
			</label>
		</p>
		<p>
			<input type="url"
				id="cph_publication_link"
				name="cph_publication_link"
				value="<?php echo esc_url( $link ); ?>"
				class="widefat"
				placeholder="<?php esc_attr_e( 'https://...', 'cph-elements' ); ?>" />
		</p>
		<?php
	}
}

if ( ! function_exists( 'cph_post_publication_save_meta' ) ) {
	/**
	 * Save Publication Source meta box data.
	 *
	 * @since 1.0.0
	 *
	 * @param int $post_id The post ID.
	 * @return void
	 */
	function cph_post_publication_save_meta( $post_id ) {
		// Verify nonce.
		if ( ! isset( $_POST['cph_post_publication_nonce'] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( $_POST['cph_post_publication_nonce'], 'cph_post_publication' ) ) {
			return;
		}

		// Check autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Save publication name.
		if ( isset( $_POST['cph_publication_name'] ) ) {
			update_post_meta(
				$post_id,
				'_publication_name',
				sanitize_text_field( $_POST['cph_publication_name'] )
			);
		}

		// Save publication link.
		if ( isset( $_POST['cph_publication_link'] ) ) {
			update_post_meta(
				$post_id,
				'_publication_link',
				esc_url_raw( $_POST['cph_publication_link'] )
			);
		}
	}
}

add_action( 'save_post_post', 'cph_post_publication_save_meta' );

/*
 * ==========================================================================
 * PUBLICATION BYLINE & SOURCE ON SINGLE POSTS
 * ==========================================================================
 */

if ( ! function_exists( 'cph_prepend_publication_byline' ) ) {
	/**
	 * Prepend publication byline before post content.
	 *
	 * @since 1.0.0
	 *
	 * @param string $content Post content.
	 * @return string Modified content.
	 */
	function cph_prepend_publication_byline( $content ) {
		if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}

		$name = get_post_meta( get_the_ID(), '_publication_name', true );
		$link = get_post_meta( get_the_ID(), '_publication_link', true );

		if ( ! $name ) {
			return $content;
		}

		$byline_html  = '<div class="cph-publication-byline">';
		$byline_html .= '<span class="cph-publication-label">' . esc_html__( 'Publication:', 'cph-elements' ) . '</span> ';
		if ( $link ) {
			$byline_html .= '<a href="' . esc_url( $link ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( $name ) . '</a>';
		} else {
			$byline_html .= esc_html( $name );
		}
		$byline_html .= '</div>';

		return $byline_html . $content;
	}
}

add_filter( 'the_content', 'cph_prepend_publication_byline', 5 );

if ( ! function_exists( 'cph_append_publication_source' ) ) {
	/**
	 * Append "Read Article" link to post content if publication link exists.
	 *
	 * @since 1.0.0
	 *
	 * @param string $content Post content.
	 * @return string Modified content.
	 */
	function cph_append_publication_source( $content ) {
		if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}

		$link = get_post_meta( get_the_ID(), '_publication_link', true );

		if ( ! $link ) {
			return $content;
		}

		$icon = '<svg class="cph-external-icon" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>';

		$source_html  = '<p class="cph-read-article">';
		$source_html .= '<a href="' . esc_url( $link ) . '" target="_blank" rel="noopener noreferrer">';
		$source_html .= esc_html__( 'Read Article', 'cph-elements' ) . ' ' . $icon;
		$source_html .= '</a>';
		$source_html .= '</p>';

		return $content . $source_html;
	}
}

add_filter( 'the_content', 'cph_append_publication_source' );
