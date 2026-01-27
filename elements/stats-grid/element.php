<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	'name'      => 'CPH Stats Grid',
	'shortcode' => 'cph_stats_grid',
	'version'   => '1.0.0',
	'category'  => 'Clear pH Elements',

	'files' => array(
		'map'    => __DIR__ . '/stats-grid-map.php',
		'render' => __DIR__ . '/stats-grid-render.php',
	),

	'assets' => array(
		'css' => array(
			array(
				'handle'   => 'cph-stats-grid',
				'file'     => 'assets/css/stats-grid.css',
				'priority' => 100,
			),
		),
		'js'  => array(),
	),

	'gsap' => array(),

	'admin_device_groups' => array(),

	'requires' => array(
		'wpbakery' => true,
		'salient'  => false,
	),
);
