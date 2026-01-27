<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	'name'      => 'CPH Stats Photos',
	'shortcode' => 'cph_stats_photos',
	'version'   => '1.0.0',
	'category'  => 'Clear pH Elements',

	'files' => array(
		'map'    => __DIR__ . '/stats-photos-map.php',
		'render' => __DIR__ . '/stats-photos-render.php',
	),

	'assets' => array(
		'css' => array(
			array(
				'handle'   => 'cph-stats-photos',
				'file'     => 'assets/css/stats-photos.css',
				'priority' => 100,
			),
		),
		'js'  => array(
			array(
				'handle'   => 'cph-stats-photos',
				'file'     => 'assets/js/stats-photos.js',
				'priority' => 60,
				'deps'     => array(),
			),
		),
	),

	'gsap' => array(),

	'admin_device_groups' => array(
		'stats-photos-height-device-group',
		'stats-photos-value-size-device-group',
		'stats-photos-label-size-device-group',
	),

	'requires' => array(
		'wpbakery' => true,
		'salient'  => false,
	),
);
