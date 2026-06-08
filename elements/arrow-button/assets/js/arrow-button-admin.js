/**
 * Arrow Button - WPBakery admin (Spacing & Transform device groups)
 *
 * Reproduces Salient's native createDeviceGroup() + toggle for this element's
 * panel. Salient only builds these groups for vc_row/vc_section/vc_column, so
 * for our shortcode the fields would otherwise render full-width and stacked.
 * We wrap them into the same .nectar-device-group-wrap markup Salient uses, so
 * its global admin CSS produces the identical grid + device toggle.
 *
 * @package CPH_Elements
 * @since   1.6.0
 */

(function ($) {
	'use strict';

	var GROUPS = [
		'row-padding-device-group',
		'row-margin-device-group',
		'row-transform-device-group'
	];

	/**
	 * Wrap a device group's fields and build its header (Salient-native markup).
	 *
	 * @param {string} selector Device group class.
	 */
	function createDeviceGroup( selector ) {
		// Already wrapped (panel reopened or Salient handled it).
		if ( $( '.' + selector + '-wrap' ).length > 0 ) {
			return;
		}

		var $fields = $( 'body' ).find( '.' + selector );
		if ( $fields.length === 0 ) {
			return;
		}

		// Hide tablet/phone fields on load (desktop shown first).
		$( 'body' ).find( '.' + selector + ':not(.desktop)' ).hide();

		// Clone the group title (e.g. "Padding") for the header label.
		var $title = $fields.find( '.group-title' ).first().clone();

		// Wrap all device fields into the native grid container.
		$( 'body' ).find( '.' + selector ).wrapAll( '<div class="' + selector + '-wrap nectar-device-group-wrap vc_column" />' );

		// Hide the in-field group titles now that the header owns the label.
		$( 'body' ).find( '.' + selector ).find( '.group-title' ).hide();

		// Insert the header with device-selection icons.
		$( '.' + selector + '-wrap' ).before( '<div class="' + selector + '-header nectar-device-group-header" />' );

		var $header = $( '.' + selector + '-header' );
		$header.append( $title );
		$header.append(
			'<span class="device-selection">' +
				'<i class="dashicons-before dashicons-desktop active" data-filter="desktop" title="Desktop"></i> ' +
				'<i class="dashicons-before dashicons-tablet" data-filter="tablet" title="Tablet"></i> ' +
				'<i class="dashicons-before dashicons-smartphone" data-filter="phone" title="Phone"></i>' +
			'</span>'
		);
	}

	/**
	 * Bind device toggle clicks (mirrors Salient's deviceGroupEvents).
	 */
	function bindEvents() {
		$( '.nectar-device-group-header i' ).off( 'click.cph_ab' );
		$( '.nectar-device-group-header i' ).on( 'click.cph_ab', function () {
			var filter = $( this ).attr( 'data-filter' );
			var $group = $( this ).parents( '.nectar-device-group-header' ).next( '.nectar-device-group-wrap' );

			if ( $( this ).hasClass( 'active' ) ) {
				return;
			}

			$( this ).parents( '.nectar-device-group-header' ).find( 'i' ).removeClass( 'active' );
			$( this ).addClass( 'active' );

			$group.find( '> div' ).hide();
			$group.find( '> div.' + filter ).fadeIn();

			// Range sliders need a resize to redraw after becoming visible.
			if ( $group.find( '.nectar_range_slider' ).length > 0 ) {
				$( window ).trigger( 'resize' );
			}
		} );
	}

	/**
	 * Build all groups for the Arrow Button panel.
	 */
	function init() {
		$.each( GROUPS, function ( i, selector ) {
			createDeviceGroup( selector );
		} );
		bindEvents();
	}

	/**
	 * Wait for the WPBakery edit panel, then init on open for our shortcode.
	 */
	function waitForPanel() {
		var $panel = $( '#vc_ui-panel-edit-element' );

		if ( $panel.length > 0 ) {
			$panel.off( 'vcPanel.shown.cph_ab' ).on( 'vcPanel.shown.cph_ab', function () {
				var shortcode = $panel.attr( 'data-vc-shortcode' ) || '';
				if ( 'cph_arrow_button' === shortcode ) {
					// Let Salient finish initializing the nectar widgets first.
					setTimeout( init, 100 );
				}
			} );
		} else {
			setTimeout( waitForPanel, 500 );
		}
	}

	$( document ).ready( waitForPanel );
})( jQuery );
