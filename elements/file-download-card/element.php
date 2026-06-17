<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	'name'      => 'File Download Card',
	'shortcode' => 'file_download_card',
	'version'   => '1.2.1',
	'category'  => 'Clear pH Elements',

	'files' => array(
		'map'    => __DIR__ . '/file-download-card-map.php',
		'render' => __DIR__ . '/file-download-card-render.php',
		'setup'  => __DIR__ . '/file-download-card-setup.php',
	),

	'assets' => array(
		'css' => array(
			array(
				'handle'   => 'cph-file-download-card',
				'file'     => 'assets/css/file-download-card.css',
				'priority' => 100,
			),
		),
		'js'  => array(
			array(
				'handle'   => 'cph-file-download-card',
				'file'     => 'assets/js/file-download-card.js',
				'priority' => 60,
				'deps'     => array( 'jquery' ),
			),
		),
	),

	'gsap' => array(),

	'admin_device_groups' => array(),

	'requires' => array(
		'wpbakery' => true,
		'salient'  => false,
	),
);
