<?php
/**
 * CPH Team Grid - Setup
 *
 * Registers the bti_team custom post type, bti_team_category taxonomy,
 * and team member meta boxes.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * ==========================================================================
 * CPH TEAM - CUSTOM POST TYPE
 * ==========================================================================
 */

if ( ! function_exists( 'cph_register_team_post_type' ) ) {
	/**
	 * Register the Team custom post type.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function cph_register_team_post_type() {
		$labels = array(
			'name'                  => _x( 'Team Members', 'Post type general name', 'cph-elements' ),
			'singular_name'         => _x( 'Team Member', 'Post type singular name', 'cph-elements' ),
			'menu_name'             => _x( 'Team', 'Admin Menu text', 'cph-elements' ),
			'add_new'               => __( 'Add New', 'cph-elements' ),
			'add_new_item'          => __( 'Add New Team Member', 'cph-elements' ),
			'edit_item'             => __( 'Edit Team Member', 'cph-elements' ),
			'new_item'              => __( 'New Team Member', 'cph-elements' ),
			'view_item'             => __( 'View Team Member', 'cph-elements' ),
			'search_items'          => __( 'Search Team Members', 'cph-elements' ),
			'not_found'             => __( 'No team members found', 'cph-elements' ),
			'not_found_in_trash'    => __( 'No team members found in Trash', 'cph-elements' ),
			'all_items'             => __( 'All Team Members', 'cph-elements' ),
			'featured_image'        => __( 'Team Member Photo', 'cph-elements' ),
			'set_featured_image'    => __( 'Set team member photo', 'cph-elements' ),
			'remove_featured_image' => __( 'Remove team member photo', 'cph-elements' ),
			'use_featured_image'    => __( 'Use as team member photo', 'cph-elements' ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'team' ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 20,
			'menu_icon'          => 'dashicons-groups',
			'supports'           => array( 'title', 'thumbnail', 'page-attributes' ),
			'show_in_rest'       => true,
		);

		register_post_type( 'bti_team', $args );
	}
	add_action( 'init', 'cph_register_team_post_type' );
}

/*
 * ==========================================================================
 * CPH TEAM - CATEGORY TAXONOMY
 * ==========================================================================
 */

if ( ! function_exists( 'cph_register_team_category_taxonomy' ) ) {
	/**
	 * Register the Team Category taxonomy.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function cph_register_team_category_taxonomy() {
		$labels = array(
			'name'              => _x( 'Team Categories', 'taxonomy general name', 'cph-elements' ),
			'singular_name'     => _x( 'Team Category', 'taxonomy singular name', 'cph-elements' ),
			'search_items'      => __( 'Search Team Categories', 'cph-elements' ),
			'all_items'         => __( 'All Team Categories', 'cph-elements' ),
			'parent_item'       => __( 'Parent Team Category', 'cph-elements' ),
			'parent_item_colon' => __( 'Parent Team Category:', 'cph-elements' ),
			'edit_item'         => __( 'Edit Team Category', 'cph-elements' ),
			'update_item'       => __( 'Update Team Category', 'cph-elements' ),
			'add_new_item'      => __( 'Add New Team Category', 'cph-elements' ),
			'new_item_name'     => __( 'New Team Category Name', 'cph-elements' ),
			'menu_name'         => __( 'Categories', 'cph-elements' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'team-category' ),
			'show_in_rest'      => true,
		);

		register_taxonomy( 'bti_team_category', array( 'bti_team' ), $args );
	}
	add_action( 'init', 'cph_register_team_category_taxonomy' );
}

/*
 * ==========================================================================
 * CPH TEAM - META BOXES
 * ==========================================================================
 */

if ( ! function_exists( 'cph_team_add_meta_boxes' ) ) {
	/**
	 * Add meta box for team member details.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function cph_team_add_meta_boxes() {
		add_meta_box(
			'bti_team_details',
			__( 'Team Member Details', 'cph-elements' ),
			'cph_team_meta_box_callback',
			'bti_team',
			'normal',
			'high'
		);
	}
	add_action( 'add_meta_boxes', 'cph_team_add_meta_boxes' );
}

if ( ! function_exists( 'cph_team_meta_box_callback' ) ) {
	/**
	 * Render the team member meta box.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post The post object.
	 * @return void
	 */
	function cph_team_meta_box_callback( $post ) {
		wp_nonce_field( 'bti_team_meta_box', 'bti_team_meta_box_nonce' );

		$job_title = get_post_meta( $post->ID, '_bti_team_job_title', true );
		$bio       = get_post_meta( $post->ID, '_bti_team_bio', true );
		?>
		<p>
			<label for="bti_team_job_title">
				<strong><?php esc_html_e( 'Job Title', 'cph-elements' ); ?></strong>
			</label>
		</p>
		<p>
			<input type="text"
				id="bti_team_job_title"
				name="bti_team_job_title"
				value="<?php echo esc_attr( $job_title ); ?>"
				class="widefat"
				placeholder="<?php esc_attr_e( 'e.g., Chief Executive Officer', 'cph-elements' ); ?>" />
		</p>
		<p>
			<label for="bti_team_bio">
				<strong><?php esc_html_e( 'Biography', 'cph-elements' ); ?></strong>
			</label>
		</p>
		<?php
		wp_editor(
			$bio,
			'bti_team_bio',
			array(
				'textarea_name' => 'bti_team_bio',
				'textarea_rows' => 12,
				'media_buttons' => false,
				'teeny'         => false,
				'quicktags'     => true,
			)
		);
	}
}

if ( ! function_exists( 'cph_team_save_meta_box' ) ) {
	/**
	 * Save team member meta box data.
	 *
	 * @since 1.0.0
	 *
	 * @param int $post_id The post ID.
	 * @return void
	 */
	function cph_team_save_meta_box( $post_id ) {
		// Verify nonce.
		if ( ! isset( $_POST['bti_team_meta_box_nonce'] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( $_POST['bti_team_meta_box_nonce'], 'bti_team_meta_box' ) ) {
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

		// Save job title.
		if ( isset( $_POST['bti_team_job_title'] ) ) {
			update_post_meta(
				$post_id,
				'_bti_team_job_title',
				sanitize_text_field( $_POST['bti_team_job_title'] )
			);
		}

		// Save bio (allow HTML from WYSIWYG editor).
		if ( isset( $_POST['bti_team_bio'] ) ) {
			update_post_meta(
				$post_id,
				'_bti_team_bio',
				wp_kses_post( $_POST['bti_team_bio'] )
			);
		}
	}
	add_action( 'save_post_bti_team', 'cph_team_save_meta_box' );
}

/*
 * ==========================================================================
 * CPH TEAM - POPUP AJAX HANDLER
 * ==========================================================================
 */

if ( ! function_exists( 'cph_ajax_get_team_member' ) ) {
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
}

if ( ! function_exists( 'cph_team_popup_script_data' ) ) {
	/**
	 * Enqueue popup script data (AJAX URL and nonce).
	 *
	 * Localizes data for the team popup used by both the horizontal scroller
	 * and team grid elements.
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
}

if ( ! function_exists( 'cph_team_popup_shell' ) ) {
	/**
	 * Output the team member popup HTML shell.
	 *
	 * Only outputs once per page, regardless of how many elements use it.
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
	add_action( 'wp_footer', 'cph_team_popup_shell' );
}
