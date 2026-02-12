/**
 * CPH Portfolio Grid - WPBakery Admin
 *
 * Initializes Salient-style responsive device group toggles
 * (desktop/tablet/phone icons) for our custom element fields.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

( function( $ ) {
	'use strict';

	/**
	 * Create a device group from fields sharing a CSS class selector.
	 *
	 * Replicates Salient's createDeviceGroup() which is scoped inside
	 * nectar-element-edit.js and not accessible to external plugins.
	 *
	 * @param {string} selector The device-group class (e.g. "grid-height-device-group").
	 */
	function createDeviceGroup( selector ) {

		// Already initialized — skip.
		if ( $( '.' + selector + '-wrap' ).length > 0 ) {
			return;
		}

		var $fields = $( 'body' ).find( '.' + selector );

		if ( $fields.length === 0 ) {
			return;
		}

		// Hide tablet/phone fields on load.
		$fields.not( '.desktop' ).hide();

		// Clone the group title from the desktop field.
		var $title = $fields.find( '.group-title' ).clone();

		// Wrap all fields in a group container.
		$fields.wrapAll( '<div class="' + selector + '-wrap nectar-device-group-wrap vc_column" />' );

		// Hide the original WPBakery heading containers.
		// Desktop: hide the entire wpb-param-heading (our header replaces it).
		// Tablet/Phone: already fully hidden above.
		$fields.filter( '.desktop' ).find( '.wpb-param-heading' ).hide();

		// Create the header with title + device icons.
		$( '.' + selector + '-wrap' ).before(
			'<div class="' + selector + '-header nectar-device-group-header" />'
		);

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
	 * Highlight device icons that have values set.
	 *
	 * @param {jQuery} $input The input that changed.
	 */
	function deviceHighlightInUse( $input ) {

		var $groupHeader = $input.parents( '.nectar-device-group-wrap' ).prev( '.nectar-device-group-header' );
		var inUse        = false;

		// Determine which device icon to check.
		var iconSelector = 'desktop';
		if ( $input.parents( 'div[class*="vc_wrapper-param-type"].tablet' ).length > 0 ) {
			iconSelector = 'tablet';
		} else if ( $input.parents( 'div[class*="vc_wrapper-param-type"].phone' ).length > 0 ) {
			iconSelector = 'phone';
		}

		$groupHeader.find( 'i[data-filter="' + iconSelector + '"]' ).removeClass( 'in-use' );

		// Check each text input in this device group for a value.
		$input.parents( '.nectar-device-group-wrap' ).find( '.' + iconSelector + ' input[type="text"]' ).each( function() {
			if ( $( this ).val().length ) {
				inUse = true;
			}
		} );

		if ( inUse ) {
			$groupHeader.find( 'i[data-filter="' + iconSelector + '"]' ).addClass( 'in-use' );
		}
	}

	/**
	 * Bind click/change events for device group toggles.
	 *
	 * Uses a namespaced event to avoid conflicts with Salient's own bindings.
	 */
	function bindDeviceGroupEvents() {

		// Device icon clicks.
		$( '.nectar-device-group-header i' ).off( 'click.cph_device_groups' );
		$( '.nectar-device-group-header i' ).on( 'click.cph_device_groups', function() {

			var filter = $( this ).attr( 'data-filter' );
			var group  = $( this ).parents( '.nectar-device-group-header' ).next( '.nectar-device-group-wrap' );

			if ( $( this ).hasClass( 'active' ) ) {
				return;
			}

			$( this ).parents( '.nectar-device-group-header' ).find( 'i' ).removeClass( 'active' );
			$( this ).addClass( 'active' );

			group.find( '> div' ).hide();
			group.find( '> div.' + filter ).fadeIn();
		} );

		// Highlight in-use icons on change + initial load.
		$( '.nectar-device-group-header .device-selection i' ).each( function() {
			var $group = $( this ).parents( '.nectar-device-group-header' ).next( '.nectar-device-group-wrap' );

			$group.find( 'input[type="text"]' ).off( 'change.cph_device_groups' );
			$group.find( 'input[type="text"]' ).on( 'change.cph_device_groups', function() {
				deviceHighlightInUse( $( this ) );
			} );

			// Initial highlight.
			$group.find( 'input[type="text"]' ).each( function() {
				deviceHighlightInUse( $( this ) );
			} );
		} );
	}

	$( document ).ready( function() {

		// Hook into the WPBakery edit panel open event.
		$( '#vc_ui-panel-edit-element' ).on( 'vcPanel.shown', function() {

			var $shortcode = $( '#vc_ui-panel-edit-element[data-vc-shortcode]' ).length > 0
				? $( '#vc_ui-panel-edit-element' ).attr( 'data-vc-shortcode' )
				: '';

			if ( 'cph_portfolio_grid' !== $shortcode ) {
				return;
			}

			// Initialize device groups for our responsive fields.
			createDeviceGroup( 'grid-height-device-group' );
			createDeviceGroup( 'card-height-device-group' );
			createDeviceGroup( 'location-font-size-device-group' );

			bindDeviceGroupEvents();
		} );
	} );

}( jQuery ) );
