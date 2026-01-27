<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	'name'      => 'CPH Team Grid',
	'shortcode' => 'cph_team_grid',
	'version'   => '1.0.0',
	'category'  => 'Clear pH Elements',

	'files' => array(
		'map'    => __DIR__ . '/team-grid-map.php',
		'render' => __DIR__ . '/team-grid-render.php',
		'setup'  => __DIR__ . '/team-grid-setup.php',
	),

	'assets' => array(
		'css' => array(
			array(
				'handle'   => 'cph-team-grid',
				'file'     => 'assets/css/team-grid.css',
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
