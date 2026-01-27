<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	'name'      => 'CPH Stats Bubbles',
	'shortcode' => 'cph_stats_bubbles',
	'version'   => '1.0.0',
	'category'  => 'Clear pH Elements',

	'files' => array(
		'map'    => __DIR__ . '/stats-bubbles-map.php',
		'render' => __DIR__ . '/stats-bubbles-render.php',
	),

	'assets' => array(
		'css' => array(
			array(
				'handle'   => 'cph-stats-bubbles',
				'file'     => 'assets/css/stats-bubbles.css',
				'priority' => 100,
			),
		),
		'js'  => array(
			array(
				'handle'   => 'cph-stats-bubbles',
				'file'     => 'assets/js/stats-bubbles.js',
				'priority' => 60,
				'deps'     => array( 'fg-gsap-core', 'fg-gsap-scrolltrigger' ),
			),
		),
	),

	'gsap' => array( 'core', 'scrolltrigger' ),

	'admin_device_groups' => array(
		'stats-bubbles-height-device-group',
		'stats-bubbles-value-size-device-group',
		'stats-bubbles-label-size-device-group',
	),

	'requires' => array(
		'wpbakery' => true,
		'salient'  => false,
	),
);
