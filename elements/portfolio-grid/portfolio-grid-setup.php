<?php
/**
 * CPH Portfolio Grid - Setup
 *
 * Registers taxonomies, meta boxes, autocomplete callbacks, and admin columns
 * required by the Portfolio Grid element.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * ==========================================================================
 * PROJECT STATUS TAXONOMY
 * ==========================================================================
 */

if ( ! function_exists( 'cph_register_project_status_taxonomy' ) ) {
	/**
	 * Register the Project Status taxonomy for Portfolio posts.
	 *
	 * Allows filtering by Active/Completed status independent of project type.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function cph_register_project_status_taxonomy() {
		$labels = array(
			'name'              => _x( 'Project Status', 'taxonomy general name', 'cph-elements' ),
			'singular_name'     => _x( 'Project Status', 'taxonomy singular name', 'cph-elements' ),
			'search_items'      => __( 'Search Project Statuses', 'cph-elements' ),
			'all_items'         => __( 'All Project Statuses', 'cph-elements' ),
			'edit_item'         => __( 'Edit Project Status', 'cph-elements' ),
			'update_item'       => __( 'Update Project Status', 'cph-elements' ),
			'add_new_item'      => __( 'Add New Project Status', 'cph-elements' ),
			'new_item_name'     => __( 'New Project Status Name', 'cph-elements' ),
			'menu_name'         => __( 'Project Status', 'cph-elements' ),
		);

		$args = array(
			'hierarchical'      => false,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'project-status' ),
			'show_in_rest'      => true,
		);

		register_taxonomy( 'project-status', array( 'portfolio' ), $args );
	}
	add_action( 'init', 'cph_register_project_status_taxonomy' );
}

if ( ! function_exists( 'cph_create_default_project_status_terms' ) ) {
	/**
	 * Create default project status terms on init.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function cph_create_default_project_status_terms() {
		$terms = array(
			'active'    => __( 'Active', 'cph-elements' ),
			'completed' => __( 'Completed', 'cph-elements' ),
		);

		foreach ( $terms as $slug => $name ) {
			if ( ! term_exists( $slug, 'project-status' ) ) {
				wp_insert_term( $name, 'project-status', array( 'slug' => $slug ) );
			}
		}
	}
	add_action( 'init', 'cph_create_default_project_status_terms', 20 );
}

/*
 * ==========================================================================
 * FEATURED PROJECT META BOX
 * ==========================================================================
 */

if ( ! function_exists( 'cph_add_portfolio_featured_meta_box' ) ) {
	/**
	 * Add Featured Project meta box to Portfolio post editor.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function cph_add_portfolio_featured_meta_box() {
		add_meta_box(
			'cph_portfolio_featured',
			__( 'Featured Project', 'cph-elements' ),
			'cph_render_portfolio_featured_meta_box',
			'portfolio',
			'side',
			'high'
		);
	}
	add_action( 'add_meta_boxes', 'cph_add_portfolio_featured_meta_box' );
}

if ( ! function_exists( 'cph_render_portfolio_featured_meta_box' ) ) {
	/**
	 * Render the Featured Project meta box.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post The post object.
	 * @return void
	 */
	function cph_render_portfolio_featured_meta_box( $post ) {
		wp_nonce_field( 'cph_portfolio_featured', 'cph_portfolio_featured_nonce' );
		$is_featured = get_post_meta( $post->ID, '_cph_portfolio_featured', true );
		?>
		<label>
			<input type="checkbox" name="_cph_portfolio_featured" value="1" <?php checked( $is_featured, '1' ); ?> />
			<?php esc_html_e( 'Display full-width in Projects Grid', 'cph-elements' ); ?>
		</label>
		<p class="description">
			<?php esc_html_e( 'When checked, this project will span the full width of the grid instead of appearing in a 2-column row.', 'cph-elements' ); ?>
		</p>
		<?php
	}
}

if ( ! function_exists( 'cph_save_portfolio_featured_meta' ) ) {
	/**
	 * Save the Featured Project meta box data.
	 *
	 * @since 1.0.0
	 *
	 * @param int $post_id The post ID.
	 * @return void
	 */
	function cph_save_portfolio_featured_meta( $post_id ) {
		// Verify nonce.
		if ( ! isset( $_POST['cph_portfolio_featured_nonce'] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( $_POST['cph_portfolio_featured_nonce'], 'cph_portfolio_featured' ) ) {
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

		// Save featured status.
		$is_featured = isset( $_POST['_cph_portfolio_featured'] ) ? '1' : '';
		update_post_meta( $post_id, '_cph_portfolio_featured', $is_featured );
	}
	add_action( 'save_post_portfolio', 'cph_save_portfolio_featured_meta' );
}

/*
 * ==========================================================================
 * AUTOCOMPLETE CALLBACKS
 * ==========================================================================
 */

if ( ! function_exists( 'cph_portfolio_autocomplete_callback' ) ) {
	/**
	 * Autocomplete callback for Portfolio posts.
	 *
	 * Used by WPBakery autocomplete fields.
	 *
	 * @since 1.0.0
	 *
	 * @param string $search_string Search query string.
	 * @return array Array of matching posts.
	 */
	function cph_portfolio_autocomplete_callback( $search_string ) {
		$query = new WP_Query(
			array(
				'post_type'      => 'portfolio',
				'post_status'    => 'publish',
				'posts_per_page' => 20,
				's'              => $search_string,
				'orderby'        => 'title',
				'order'          => 'ASC',
			)
		);

		$results = array();
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$results[] = array(
					'value' => get_the_ID(),
					'label' => get_the_title(),
				);
			}
			wp_reset_postdata();
		}

		return $results;
	}
}

if ( ! function_exists( 'cph_portfolio_autocomplete_render' ) ) {
	/**
	 * Render callback for autocomplete (displays selected value).
	 *
	 * @since 1.0.0
	 *
	 * @param array $value The autocomplete value.
	 * @return array The rendered value with label.
	 */
	function cph_portfolio_autocomplete_render( $value ) {
		$post = get_post( $value['value'] );
		if ( $post ) {
			return array(
				'value' => $post->ID,
				'label' => $post->post_title,
			);
		}
		return $value;
	}
}

if ( ! function_exists( 'cph_register_portfolio_autocomplete_filters' ) ) {
	/**
	 * Register autocomplete filters for all portfolio grid slots.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function cph_register_portfolio_autocomplete_filters() {
		// Homepage Bento slots (1-3).
		$bento_slots = array( 'slot_1', 'slot_2', 'slot_3' );

		// Grid 2x4 slots (1-8).
		$grid_slots = array();
		for ( $i = 1; $i <= 8; $i++ ) {
			$grid_slots[] = 'grid_slot_' . $i;
		}

		// Featured grid slots (1-10).
		$featured_slots = array();
		for ( $i = 1; $i <= 10; $i++ ) {
			$featured_slots[] = 'featured_slot_' . $i;
		}

		// Combine all slots.
		$all_slots = array_merge( $bento_slots, $grid_slots, $featured_slots );

		// Register callback and render filters for each slot.
		foreach ( $all_slots as $slot ) {
			add_filter(
				"vc_autocomplete_cph_portfolio_grid_{$slot}_callback",
				'cph_portfolio_autocomplete_callback',
				10,
				1
			);
			add_filter(
				"vc_autocomplete_cph_portfolio_grid_{$slot}_render",
				'cph_portfolio_autocomplete_render',
				10,
				1
			);
		}
	}
	add_action( 'init', 'cph_register_portfolio_autocomplete_filters' );
}

/*
 * ==========================================================================
 * PORTFOLIO ADMIN COLUMNS
 * ==========================================================================
 */

if ( ! function_exists( 'cph_portfolio_add_category_column' ) ) {
	/**
	 * Add Project Category column to Portfolio admin list.
	 *
	 * @since 1.0.0
	 *
	 * @param array $columns Existing columns.
	 * @return array Modified columns.
	 */
	function cph_portfolio_add_category_column( $columns ) {
		$new_columns = array();

		foreach ( $columns as $key => $value ) {
			$new_columns[ $key ] = $value;

			// Add Category column after Title.
			if ( 'title' === $key ) {
				$new_columns['project_category'] = __( 'Category', 'cph-elements' );
			}
		}

		return $new_columns;
	}
	add_filter( 'manage_portfolio_posts_columns', 'cph_portfolio_add_category_column' );
}

if ( ! function_exists( 'cph_portfolio_category_column_content' ) ) {
	/**
	 * Display Project Category in admin list column.
	 *
	 * @since 1.0.0
	 *
	 * @param string $column  Column name.
	 * @param int    $post_id Post ID.
	 * @return void
	 */
	function cph_portfolio_category_column_content( $column, $post_id ) {
		if ( 'project_category' !== $column ) {
			return;
		}

		$terms = get_the_terms( $post_id, 'project-type' );

		if ( $terms && ! is_wp_error( $terms ) ) {
			$term_links = array();
			foreach ( $terms as $term ) {
				$term_links[] = sprintf(
					'<a href="%s">%s</a>',
					esc_url( admin_url( 'edit.php?post_type=portfolio&project-type=' . $term->slug ) ),
					esc_html( $term->name )
				);
			}
			echo implode( ', ', $term_links ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			echo '&mdash;';
		}
	}
	add_action( 'manage_portfolio_posts_custom_column', 'cph_portfolio_category_column_content', 10, 2 );
}

if ( ! function_exists( 'cph_portfolio_sortable_category_column' ) ) {
	/**
	 * Make Project Category column sortable.
	 *
	 * @since 1.0.0
	 *
	 * @param array $columns Sortable columns.
	 * @return array Modified sortable columns.
	 */
	function cph_portfolio_sortable_category_column( $columns ) {
		$columns['project_category']        = 'project_category';
		$columns['taxonomy-project-status'] = 'project_status';
		return $columns;
	}
	add_filter( 'manage_edit-portfolio_sortable_columns', 'cph_portfolio_sortable_category_column' );
}

if ( ! function_exists( 'cph_portfolio_taxonomy_column_orderby' ) ) {
	/**
	 * Handle sorting by Project Category and Project Status using custom SQL.
	 *
	 * @since 1.0.0
	 *
	 * @param array    $clauses SQL clauses.
	 * @param WP_Query $query   The query object.
	 * @return array Modified SQL clauses.
	 */
	function cph_portfolio_taxonomy_column_orderby( $clauses, $query ) {
		global $wpdb;

		if ( ! is_admin() || ! $query->is_main_query() ) {
			return $clauses;
		}

		$orderby  = $query->get( 'orderby' );
		$taxonomy = '';

		if ( 'project_category' === $orderby ) {
			$taxonomy = 'project-type';
		} elseif ( 'project_status' === $orderby ) {
			$taxonomy = 'project-status';
		}

		if ( $taxonomy ) {
			$clauses['join'] .= " LEFT JOIN {$wpdb->term_relationships} AS cph_tr ON ({$wpdb->posts}.ID = cph_tr.object_id)";
			$clauses['join'] .= $wpdb->prepare(
				" LEFT JOIN {$wpdb->term_taxonomy} AS cph_tt ON (cph_tr.term_taxonomy_id = cph_tt.term_taxonomy_id AND cph_tt.taxonomy = %s)",
				$taxonomy
			);
			$clauses['join'] .= " LEFT JOIN {$wpdb->terms} AS cph_t ON (cph_tt.term_id = cph_t.term_id)";

			$order              = strtoupper( $query->get( 'order' ) ) === 'DESC' ? 'DESC' : 'ASC';
			$clauses['orderby'] = "cph_t.name {$order}, {$wpdb->posts}.post_title ASC";
		}

		return $clauses;
	}
	add_filter( 'posts_clauses', 'cph_portfolio_taxonomy_column_orderby', 10, 2 );
}

if ( ! function_exists( 'cph_portfolio_add_category_filter' ) ) {
	/**
	 * Add Project Category filter dropdown to Portfolio admin list.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function cph_portfolio_add_category_filter() {
		global $typenow;

		if ( 'portfolio' !== $typenow ) {
			return;
		}

		$taxonomy = 'project-type';
		$selected = isset( $_GET[ $taxonomy ] ) ? sanitize_text_field( $_GET[ $taxonomy ] ) : '';
		$terms    = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
			)
		);

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return;
		}

		echo '<select name="' . esc_attr( $taxonomy ) . '" id="' . esc_attr( $taxonomy ) . '">';
		echo '<option value="">' . esc_html__( 'All Categories', 'cph-elements' ) . '</option>';

		foreach ( $terms as $term ) {
			printf(
				'<option value="%s" %s>%s</option>',
				esc_attr( $term->slug ),
				selected( $selected, $term->slug, false ),
				esc_html( $term->name )
			);
		}

		echo '</select>';
	}
	add_action( 'restrict_manage_posts', 'cph_portfolio_add_category_filter' );
}
