<?php
/**
 * GitHub-based auto-updater for CPH Elements.
 *
 * Hooks into the WordPress plugin update system so the plugin can be
 * updated from WP Admin > Plugins, just like wordpress.org-hosted plugins.
 *
 * @package CPH_Elements
 * @since   1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CPH_GitHub_Updater
 *
 * Checks the GitHub Releases API for a newer version and injects update
 * info into WordPress's update_plugins transient.
 */
class CPH_GitHub_Updater {

	/**
	 * Full path to the main plugin file.
	 *
	 * @var string
	 */
	private $plugin_file;

	/**
	 * Plugin basename (e.g. "cph-elements/cph-elements.php").
	 *
	 * @var string
	 */
	private $plugin_basename;

	/**
	 * Plugin slug (directory name, e.g. "cph-elements").
	 *
	 * @var string
	 */
	private $plugin_slug;

	/**
	 * GitHub repository in "owner/repo" format.
	 *
	 * @var string
	 */
	private $github_repo;

	/**
	 * Current plugin version.
	 *
	 * @var string
	 */
	private $version;

	/**
	 * Transient key for caching the GitHub API response.
	 *
	 * @var string
	 */
	private $transient_key = 'cph_elements_github_update';

	/**
	 * Cache duration in seconds (6 hours).
	 *
	 * @var int
	 */
	private $cache_duration = 21600;

	/**
	 * Cached GitHub release data for the current request.
	 *
	 * @var object|false|null Null when not yet fetched.
	 */
	private $github_release = null;

	/**
	 * Constructor.
	 *
	 * @param string $plugin_file Full path to the main plugin file.
	 * @param string $github_repo GitHub repo in "owner/repo" format.
	 * @param string $version     Current plugin version.
	 */
	public function __construct( $plugin_file, $github_repo, $version ) {
		$this->plugin_file     = $plugin_file;
		$this->plugin_basename = plugin_basename( $plugin_file );
		$this->plugin_slug     = dirname( $this->plugin_basename );
		$this->github_repo     = $github_repo;
		$this->version         = $version;
	}

	/**
	 * Register hooks.
	 */
	public function init() {
		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_update' ) );
		add_filter( 'plugins_api', array( $this, 'plugin_info' ), 10, 3 );
		add_filter( 'upgrader_post_install', array( $this, 'post_install' ), 10, 3 );
		add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );
		add_action( 'admin_init', array( $this, 'handle_manual_check' ) );
	}

	/**
	 * Check GitHub for a newer release and inject into the update transient.
	 *
	 * @param object $transient The update_plugins transient object.
	 * @return object Modified transient.
	 */
	public function check_update( $transient ) {
		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		$release = $this->get_github_release();

		if ( ! $release ) {
			return $transient;
		}

		$remote_version = $this->parse_version( $release->tag_name );

		if ( version_compare( $remote_version, $this->version, '>' ) ) {
			$download_url = $this->get_download_url( $release );

			if ( $download_url ) {
				$update              = new stdClass();
				$update->slug        = $this->plugin_slug;
				$update->plugin      = $this->plugin_basename;
				$update->new_version = $remote_version;
				$update->url         = 'https://github.com/' . $this->github_repo;
				$update->package     = $download_url;

				$transient->response[ $this->plugin_basename ] = $update;
			}
		}

		return $transient;
	}

	/**
	 * Serve plugin information for the "View details" modal.
	 *
	 * @param false|object|array $result The result object or array.
	 * @param string             $action The API action being performed.
	 * @param object             $args   Plugin API arguments.
	 * @return false|object
	 */
	public function plugin_info( $result, $action, $args ) {
		if ( 'plugin_information' !== $action ) {
			return $result;
		}

		if ( ! isset( $args->slug ) || $args->slug !== $this->plugin_slug ) {
			return $result;
		}

		$release = $this->get_github_release();

		if ( ! $release ) {
			return $result;
		}

		$plugin_data = get_plugin_data( $this->plugin_file );

		$info                = new stdClass();
		$info->name          = $plugin_data['Name'];
		$info->slug          = $this->plugin_slug;
		$info->version       = $this->parse_version( $release->tag_name );
		$info->author        = $plugin_data['Author'];
		$info->homepage      = 'https://github.com/' . $this->github_repo;
		$info->requires      = $plugin_data['RequiresWP'];
		$info->requires_php  = $plugin_data['RequiresPHP'];
		$info->download_link = $this->get_download_url( $release );
		$info->trunk         = $this->get_download_url( $release );
		$info->last_updated  = $release->published_at;

		$info->sections = array(
			'description'  => $plugin_data['Description'],
			'changelog'    => nl2br( esc_html( $release->body ) ),
		);

		return $info;
	}

	/**
	 * After installation, rename the extracted directory to match the plugin slug.
	 *
	 * GitHub release zips extract to "repo-name-tag" which breaks WordPress
	 * plugin path expectations.
	 *
	 * @param bool  $response   Installation response.
	 * @param array $hook_extra Extra arguments passed to hooked filters.
	 * @param array $result     Installation result data.
	 * @return bool|WP_Error
	 */
	public function post_install( $response, $hook_extra, $result ) {
		if ( ! isset( $hook_extra['plugin'] ) || $hook_extra['plugin'] !== $this->plugin_basename ) {
			return $response;
		}

		global $wp_filesystem;

		$install_dir = $result['destination'];
		$proper_dir  = WP_PLUGIN_DIR . '/' . $this->plugin_slug;

		// Move to the correct directory if needed.
		if ( $install_dir !== $proper_dir ) {
			$wp_filesystem->move( $install_dir, $proper_dir );
			$result['destination'] = $proper_dir;
		}

		// Re-activate if the plugin was active before the update.
		if ( is_plugin_active( $this->plugin_basename ) ) {
			activate_plugin( $this->plugin_basename );
		}

		return $response;
	}

	/**
	 * Add a "Check for updates" link to the plugin row meta.
	 *
	 * @param array  $links Plugin row meta links.
	 * @param string $file  Plugin basename.
	 * @return array Modified links.
	 */
	public function plugin_row_meta( $links, $file ) {
		if ( $file !== $this->plugin_basename ) {
			return $links;
		}

		$check_url = wp_nonce_url(
			add_query_arg( 'cph_check_update', '1', admin_url( 'plugins.php' ) ),
			'cph_check_update'
		);

		$links[] = sprintf(
			'<a href="%s">%s</a>',
			esc_url( $check_url ),
			esc_html__( 'Check for updates', 'cph-elements' )
		);

		return $links;
	}

	/**
	 * Handle manual "Check for updates" click.
	 *
	 * Clears the cached transient and forces WordPress to re-check plugin updates.
	 */
	public function handle_manual_check() {
		if ( ! isset( $_GET['cph_check_update'] ) ) {
			return;
		}

		if ( ! current_user_can( 'update_plugins' ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'cph_check_update' ) ) {
			return;
		}

		// Clear our cached GitHub response.
		delete_transient( $this->transient_key );

		// Force WordPress to re-check plugin updates.
		delete_site_transient( 'update_plugins' );

		wp_safe_redirect( admin_url( 'plugins.php' ) );
		exit;
	}

	/**
	 * Fetch the latest release from the GitHub API, with caching.
	 *
	 * @return object|false Release object on success, false on failure.
	 */
	private function get_github_release() {
		if ( null !== $this->github_release ) {
			return $this->github_release;
		}

		$cached = get_transient( $this->transient_key );

		if ( false !== $cached ) {
			$this->github_release = $cached;
			return $this->github_release;
		}

		$url = sprintf(
			'https://api.github.com/repos/%s/releases/latest',
			$this->github_repo
		);

		$response = wp_remote_get(
			$url,
			array(
				'headers' => array(
					'Accept' => 'application/vnd.github.v3+json',
				),
				'timeout' => 10,
			)
		);

		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			$this->github_release = false;
			return false;
		}

		$body = json_decode( wp_remote_retrieve_body( $response ) );

		if ( empty( $body ) || ! isset( $body->tag_name ) ) {
			$this->github_release = false;
			return false;
		}

		set_transient( $this->transient_key, $body, $this->cache_duration );

		$this->github_release = $body;
		return $this->github_release;
	}

	/**
	 * Get the zipball download URL from a release object.
	 *
	 * @param object $release GitHub release object.
	 * @return string|false Download URL or false.
	 */
	private function get_download_url( $release ) {
		return ! empty( $release->zipball_url ) ? $release->zipball_url : false;
	}

	/**
	 * Strip leading "v" from a version tag.
	 *
	 * @param string $tag Version tag (e.g. "v1.1.0").
	 * @return string Cleaned version (e.g. "1.1.0").
	 */
	private function parse_version( $tag ) {
		return ltrim( $tag, 'v' );
	}
}
