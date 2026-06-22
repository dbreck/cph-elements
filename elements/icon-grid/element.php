<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	'name'      => 'CPH Icon Grid',
	'shortcode' => 'cph_icon_grid',
	'version'   => '1.1.0',
	'category'  => 'Clear pH Elements',

	'files' => array(
		'map'    => __DIR__ . '/icon-grid-map.php',
		'render' => __DIR__ . '/icon-grid-render.php',
	),

	'assets' => array(
		'css' => array(
			array(
				'handle'   => 'cph-icon-grid',
				'file'     => 'assets/css/icon-grid.css',
				'priority' => 100,
			),
		),
		'js'  => array(),
	),

	'gsap' => array(),

	'admin_device_groups' => array(
		'icon-grid-columns-device-group',
		'icon-grid-cell-height-device-group',
		'icon-grid-gap-device-group',
		'icon-grid-row-gap-device-group',
		'icon-grid-icon-height-device-group',
		'icon-grid-font-size-device-group',
	),

	'requires' => array(
		'wpbakery' => true,
		'salient'  => false,
	),
);
