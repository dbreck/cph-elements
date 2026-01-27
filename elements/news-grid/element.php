<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	'name'      => 'CPH News Grid',
	'shortcode' => 'cph_news_grid',
	'version'   => '1.0.0',
	'category'  => 'Clear pH Elements',

	'files' => array(
		'map'    => __DIR__ . '/news-grid-map.php',
		'render' => __DIR__ . '/news-grid-render.php',
		'setup'  => __DIR__ . '/news-grid-setup.php',
	),

	'assets' => array(
		'css' => array(
			array(
				'handle'   => 'cph-news-grid',
				'file'     => 'assets/css/news-grid.css',
				'priority' => 100,
			),
		),
		'js'  => array(
			array(
				'handle'   => 'cph-news-grid',
				'file'     => 'assets/js/news-grid.js',
				'priority' => 60,
				'deps'     => array(),
			),
		),
	),

	'gsap' => array(),

	'admin_device_groups' => array(
		'news-gap-device-group',
		'card-radius-device-group',
	),

	'requires' => array(
		'wpbakery' => true,
		'salient'  => false,
	),
);
