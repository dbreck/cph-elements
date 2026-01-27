<?php
/**
 * CPH Element Loader
 *
 * Auto-discovers elements from the elements/ directory, validates dependencies,
 * registers WPBakery shortcodes, and enqueues assets.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CPH_Element_Loader
 *
 * @since 1.0.0
 */
class CPH_Element_Loader {

	/**
	 * Plugin config (from cph-config.php if present).
	 *
	 * @var array
	 */
	private $config;

	/**
	 * Loaded element configs keyed by slug.
	 *
	 * @var array
	 */
	private $elements = array();

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param array $config Optional config with 'elements' allow-list.
	 */
	public function __construct( $config = array() ) {
		$this->config = $config;
	}

	/**
	 * Initialize: discover elements, register hooks.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		$this->discover_elements();

		// Register WPBakery mappings.
		add_action( 'vc_before_init', array( $this, 'register_elements' ) );

		// Enqueue frontend assets.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_css' ), 100 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_js' ), 60 );

		// Register fallback param type if Salient is not active.
		add_action( 'vc_before_init', array( $this, 'register_fallback_param_types' ), 5 );
	}

	/**
	 * Scan elements/ directory for element.php configs.
	 *
	 * @since 1.0.0
	 */
	private function discover_elements() {
		$elements_dir = CPH_ELEMENTS_PATH . 'elements/';

		if ( ! is_dir( $elements_dir ) ) {
			return;
		}

		$dirs = glob( $elements_dir . '*', GLOB_ONLYDIR );

		if ( empty( $dirs ) ) {
			return;
		}

		// Get optional allow-list from config.
		$allow_list = isset( $this->config['elements'] ) ? $this->config['elements'] : null;

		foreach ( $dirs as $dir ) {
			$slug        = basename( $dir );
			$config_file = $dir . '/element.php';

			if ( ! file_exists( $config_file ) ) {
				continue;
			}

			// Check allow-list (null = allow all).
			if ( is_array( $allow_list ) && ! in_array( $slug, $allow_list, true ) ) {
				continue;
			}

			$element_config = include $config_file;

			if ( ! is_array( $element_config ) || empty( $element_config['shortcode'] ) ) {
				continue;
			}

			// Check dependencies.
			if ( ! $this->check_requirements( $element_config ) ) {
				continue;
			}

			// Store with slug as key.
			$element_config['slug'] = $slug;
			$element_config['dir']  = $dir . '/';
			$this->elements[ $slug ] = $element_config;

			// Load setup file (AJAX handlers, CPTs, meta boxes, etc.).
			if ( ! empty( $element_config['files']['setup'] ) && file_exists( $element_config['files']['setup'] ) ) {
				require_once $element_config['files']['setup'];
			}

			// Load render file (shortcode callback).
			if ( ! empty( $element_config['files']['render'] ) && file_exists( $element_config['files']['render'] ) ) {
				require_once $element_config['files']['render'];
			}
		}
	}

	/**
	 * Check element requirements.
	 *
	 * @since 1.0.0
	 *
	 * @param array $config Element config array.
	 * @return bool True if all requirements are met.
	 */
	private function check_requirements( $config ) {
		if ( empty( $config['requires'] ) ) {
			return true;
		}

		$requires = $config['requires'];

		// Check WPBakery requirement.
		if ( ! empty( $requires['wpbakery'] ) && ! defined( 'WPB_VC_VERSION' ) ) {
			return false;
		}

		// Check Salient requirement.
		if ( ! empty( $requires['salient'] ) ) {
			if ( ! function_exists( 'nectar_get_theme_version' ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Register all discovered elements with WPBakery.
	 *
	 * @since 1.0.0
	 */
	public function register_elements() {
		foreach ( $this->elements as $slug => $config ) {
			$shortcode = $config['shortcode'];
			$map_file  = isset( $config['files']['map'] ) ? $config['files']['map'] : '';

			if ( empty( $map_file ) || ! file_exists( $map_file ) ) {
				continue;
			}

			// Declare the WPBakery shortcode class.
			$class_name = $this->get_class_name( $shortcode );
			if ( ! class_exists( $class_name ) ) {
				// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_eval
				eval( 'class ' . $class_name . ' extends WPBakeryShortCode {}' );
			}

			// Map the element using vc_lean_map.
			vc_lean_map( $shortcode, null, $map_file );
		}
	}

	/**
	 * Generate WPBakery class name from shortcode base.
	 *
	 * WPBakery expects class WPBakeryShortCode_{Base} where base uses the
	 * exact shortcode string with underscores.
	 *
	 * @since 1.0.0
	 *
	 * @param string $shortcode Shortcode base name.
	 * @return string Class name.
	 */
	private function get_class_name( $shortcode ) {
		return 'WPBakeryShortCode_' . ucfirst( $shortcode );
	}

	/**
	 * Enqueue CSS for all active elements.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_css() {
		foreach ( $this->elements as $slug => $config ) {
			if ( empty( $config['assets']['css'] ) ) {
				continue;
			}

			foreach ( $config['assets']['css'] as $asset ) {
				$file = $config['dir'] . $asset['file'];
				if ( file_exists( $file ) ) {
					wp_enqueue_style(
						$asset['handle'],
						cph_element_url( $slug ) . $asset['file'],
						array(),
						CPH_ELEMENTS_VERSION
					);
				}
			}
		}
	}

	/**
	 * Enqueue JavaScript for all active elements.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_js() {
		if ( is_admin() ) {
			return;
		}

		foreach ( $this->elements as $slug => $config ) {
			if ( empty( $config['assets']['js'] ) ) {
				continue;
			}

			foreach ( $config['assets']['js'] as $asset ) {
				$file = $config['dir'] . $asset['file'];
				if ( file_exists( $file ) ) {
					$deps = isset( $asset['deps'] ) ? $asset['deps'] : array();
					wp_enqueue_script(
						$asset['handle'],
						cph_element_url( $slug ) . $asset['file'],
						$deps,
						CPH_ELEMENTS_VERSION,
						true
					);
				}
			}
		}
	}

	/**
	 * Register fallback nectar_group_header param type if Salient is not active.
	 *
	 * @since 1.0.0
	 */
	public function register_fallback_param_types() {
		if ( function_exists( 'nectar_get_theme_version' ) ) {
			return;
		}

		if ( ! function_exists( 'vc_add_shortcode_param' ) ) {
			return;
		}

		// Register a basic fallback for nectar_group_header.
		vc_add_shortcode_param(
			'nectar_group_header',
			array( $this, 'render_group_header_param' )
		);

		// Register a basic fallback for nectar_range_slider.
		vc_add_shortcode_param(
			'nectar_range_slider',
			array( $this, 'render_range_slider_param' )
		);
	}

	/**
	 * Render fallback group header param.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $settings Param settings.
	 * @param string $value    Current value.
	 * @return string HTML output.
	 */
	public function render_group_header_param( $settings, $value ) {
		return '<div class="cph-group-header"><h4>' . esc_html( $settings['heading'] ) . '</h4></div>'
			. '<input type="hidden" name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value" value="' . esc_attr( $value ) . '" />';
	}

	/**
	 * Render fallback range slider param.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $settings Param settings.
	 * @param string $value    Current value.
	 * @return string HTML output.
	 */
	public function render_range_slider_param( $settings, $value ) {
		$min  = isset( $settings['options']['min'] ) ? $settings['options']['min'] : '0';
		$max  = isset( $settings['options']['max'] ) ? $settings['options']['max'] : '100';
		$step = isset( $settings['options']['step'] ) ? $settings['options']['step'] : '1';

		return '<input type="range" min="' . esc_attr( $min ) . '" max="' . esc_attr( $max ) . '" step="' . esc_attr( $step ) . '" '
			. 'name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value" value="' . esc_attr( $value ) . '" />';
	}

	/**
	 * Get all loaded element configs.
	 *
	 * @since 1.0.0
	 *
	 * @return array Loaded element configs keyed by slug.
	 */
	public function get_elements() {
		return $this->elements;
	}
}
