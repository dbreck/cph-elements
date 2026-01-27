<?php
/**
 * CPH GSAP Manager
 *
 * Collects GSAP dependency needs from element configs and enqueues
 * only the required CDN modules. Checks if scripts are already
 * registered to avoid conflicts with themes that also load GSAP.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CPH_GSAP
 *
 * @since 1.0.0
 */
class CPH_GSAP {

	/**
	 * GSAP CDN base URL.
	 *
	 * @var string
	 */
	const CDN_BASE = 'https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/';

	/**
	 * Map of module keys to CDN filenames and handles.
	 *
	 * @var array
	 */
	private static $modules = array(
		'core'          => array(
			'file'   => 'gsap.min.js',
			'handle' => 'fg-gsap-core',
			'deps'   => array(),
		),
		'scrolltrigger' => array(
			'file'   => 'ScrollTrigger.min.js',
			'handle' => 'fg-gsap-scrolltrigger',
			'deps'   => array( 'fg-gsap-core' ),
		),
		'draggable'     => array(
			'file'   => 'Draggable.min.js',
			'handle' => 'fg-gsap-draggable',
			'deps'   => array( 'fg-gsap-core' ),
		),
		'inertia'       => array(
			'file'   => 'InertiaPlugin.min.js',
			'handle' => 'fg-gsap-inertia',
			'deps'   => array( 'fg-gsap-core' ),
		),
	);

	/**
	 * Reference to the element loader.
	 *
	 * @var CPH_Element_Loader
	 */
	private $loader;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param CPH_Element_Loader $loader The element loader.
	 */
	public function __construct( CPH_Element_Loader $loader ) {
		$this->loader = $loader;
	}

	/**
	 * Initialize: hook into wp_enqueue_scripts early.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ), 5 );
	}

	/**
	 * Collect GSAP needs from all active elements and enqueue.
	 *
	 * @since 1.0.0
	 */
	public function enqueue() {
		if ( is_admin() ) {
			return;
		}

		$needed = $this->collect_needs();

		if ( empty( $needed ) ) {
			return;
		}

		foreach ( $needed as $module_key ) {
			if ( ! isset( self::$modules[ $module_key ] ) ) {
				continue;
			}

			$module = self::$modules[ $module_key ];

			// Skip if already registered (theme may have loaded it).
			if ( wp_script_is( $module['handle'], 'registered' ) ) {
				continue;
			}

			wp_register_script(
				$module['handle'],
				self::CDN_BASE . $module['file'],
				$module['deps'],
				'3.13.0',
				true
			);

			wp_enqueue_script( $module['handle'] );
		}
	}

	/**
	 * Collect unique GSAP module needs from all active elements.
	 *
	 * @since 1.0.0
	 *
	 * @return array Unique module keys.
	 */
	private function collect_needs() {
		$needed   = array();
		$elements = $this->loader->get_elements();

		foreach ( $elements as $config ) {
			if ( ! empty( $config['gsap'] ) && is_array( $config['gsap'] ) ) {
				$needed = array_merge( $needed, $config['gsap'] );
			}
		}

		return array_unique( $needed );
	}
}
