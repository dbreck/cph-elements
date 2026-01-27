<?php
/**
 * CPH Elements Configuration (Sample)
 *
 * Copy this file to cph-config.php and customize.
 * When cph-config.php exists, only the elements listed below will load.
 * Delete the file (or don't create it) to load all elements.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(

	/*
	 * Element allow-list.
	 *
	 * Only these element slugs will be loaded. Comment out or remove
	 * any elements you don't need on this site.
	 */
	'elements' => array(
		'portfolio-grid',
		'horizontal-scroller',
		'team-grid',
		'news-grid',
		'icon-grid',
		'stats-grid',
		'stats-bubbles',
		'stats-photos',
		'gallery-slider',
		'file-download-card',
	),
);
