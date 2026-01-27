/**
 * CPH WPBakery Admin Scripts
 *
 * Initializes device groups for CPH custom WPBakery elements.
 * Matches the native Salient/Nectar device toggle structure.
 *
 * Device group mappings are loaded from cphDeviceGroups (localized by PHP).
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

(function($) {
	'use strict';

	/**
	 * Create a device group with toggle icons (native Salient style).
	 *
	 * Structure:
	 * - Desktop field: Label (with device icons injected) + Input (visible by default)
	 * - Tablet field: Label hidden, Input hidden by default
	 * - Phone field: Label hidden, Input hidden by default
	 *
	 * @param {string} selector - The device group class selector.
	 */
	function createDeviceGroup(selector) {
		// Check if device group is already initialized.
		if ($('.' + selector + '.cph-device-initialized').length > 0) {
			return;
		}

		// Find all elements with this class.
		var $allFields = $('body').find('.' + selector);

		if ($allFields.length === 0) {
			return;
		}

		// Find desktop, tablet, phone fields.
		var $desktopField = $allFields.filter('.desktop');
		var $tabletField = $allFields.filter('.tablet');
		var $phoneField = $allFields.filter('.phone');

		if ($desktopField.length === 0) {
			return;
		}

		// Mark all as initialized.
		$allFields.addClass('cph-device-initialized');

		// Get the label text from desktop field's group-title span.
		var $desktopLabel = $desktopField.find('.wpb_element_label');
		var $groupTitle = $desktopLabel.find('.group-title');
		var labelText = $groupTitle.length > 0 ? $groupTitle.text() : $desktopLabel.contents().filter(function() {
			return this.nodeType === 3; // Text nodes only
		}).text().trim();

		// If no label text found, try getting all text
		if (!labelText) {
			labelText = $desktopLabel.text().trim();
		}

		// Rebuild the desktop label with device icons inline.
		$desktopLabel.html(
			'<span class="cph-label-text">' + labelText + '</span>' +
			'<span class="cph-device-selection" data-group="' + selector + '">' +
				'<i class="dashicons dashicons-desktop active" data-filter="desktop" title="Desktop"></i>' +
				'<i class="dashicons dashicons-tablet" data-filter="tablet" title="Tablet"></i>' +
				'<i class="dashicons dashicons-smartphone" data-filter="phone" title="Phone"></i>' +
			'</span>'
		);

		// Hide tablet and phone fields completely (their inputs will be shown when needed).
		$tabletField.addClass('cph-device-field-hidden');
		$phoneField.addClass('cph-device-field-hidden');

		// Store references for easy access during toggle.
		$desktopField.attr('data-device-group', selector);
		$tabletField.attr('data-device-group', selector);
		$phoneField.attr('data-device-group', selector);
	}

	/**
	 * Bind click events for CPH device toggle icons.
	 */
	function bindDeviceGroupEvents() {
		// Bind to our device selection icons.
		$(document).off('click.cph_device_groups', '.cph-device-selection i').on('click.cph_device_groups', '.cph-device-selection i', function() {
			var filter = $(this).attr('data-filter');
			var $selection = $(this).closest('.cph-device-selection');
			var groupSelector = $selection.attr('data-group');

			// Skip if already active.
			if ($(this).hasClass('active')) {
				return;
			}

			// Update active class on icons.
			$selection.find('i').removeClass('active');
			$(this).addClass('active');

			// Find all fields in this group.
			var $allFields = $('body').find('.' + groupSelector + '.cph-device-initialized');
			var $desktopField = $allFields.filter('.desktop');
			var $tabletField = $allFields.filter('.tablet');
			var $phoneField = $allFields.filter('.phone');

			// Hide all field inputs/controls (but keep desktop label visible).
			$desktopField.find('.wpb_element_wrapper').hide();
			$tabletField.addClass('cph-device-field-hidden');
			$phoneField.addClass('cph-device-field-hidden');

			// Show the selected device's input.
			if (filter === 'desktop') {
				$desktopField.find('.wpb_element_wrapper').show();
			} else if (filter === 'tablet') {
				$tabletField.removeClass('cph-device-field-hidden');
			} else if (filter === 'phone') {
				$phoneField.removeClass('cph-device-field-hidden');
			}
		});
	}

	/**
	 * Initialize CPH device groups for the current shortcode.
	 *
	 * Reads device group mappings from the localized cphDeviceGroups object.
	 *
	 * @param {string} shortcode - The shortcode being edited.
	 */
	function initCPHDeviceGroups(shortcode) {
		// Read groups from localized data.
		if (typeof cphDeviceGroups === 'undefined' || !cphDeviceGroups[shortcode]) {
			return;
		}

		var groups = cphDeviceGroups[shortcode];

		// Create each device group.
		$.each(groups, function(i, group) {
			createDeviceGroup(group);
		});

		// Bind events.
		bindDeviceGroupEvents();
	}

	/**
	 * Initialize Stats Grid param_group behavior.
	 * - Expand the last item on panel open so fields are visible
	 * - When adding new item: collapse others, expand new one
	 */
	function initStatsGridParamGroup() {
		var $panel = $('#vc_ui-panel-edit-element');

		// Find the stats param_group wrapper - look for the param with data-vc-shortcode-param-name="stats".
		var $paramGroupWrapper = $panel.find('[data-vc-shortcode-param-name="stats"]');

		// Fallback: try finding by param class.
		if ( 0 === $paramGroupWrapper.length ) {
			$paramGroupWrapper = $panel.find('.vc_param_group-param').filter( function() {
				return $( this ).find( 'input[name*="[stats]"]' ).length > 0;
			});
		}

		if ( 0 === $paramGroupWrapper.length ) {
			return;
		}

		var $paramGroupList = $paramGroupWrapper.find('.vc_param_group-list');

		if ( 0 === $paramGroupList.length ) {
			return;
		}

		// On panel open: expand the last item so user can see fields.
		// Small delay to ensure WPBakery has finished rendering.
		setTimeout( function() {
			expandLastItem( $paramGroupList );
		}, 100 );

		// Watch for new items being added using MutationObserver.
		var observer = new MutationObserver( function( mutations ) {
			mutations.forEach( function( mutation ) {
				if ( mutation.addedNodes.length > 0 ) {
					// New item added, expand it.
					setTimeout( function() {
						expandLastItem( $paramGroupList );
					}, 50 );
				}
			});
		});

		observer.observe( $paramGroupList[0], { childList: true } );

		// Also bind to add button as backup.
		$paramGroupWrapper.off( 'click.cph_stats_add' ).on( 'click.cph_stats_add', '.vc_param_group-add_content, .vc_add', function() {
			setTimeout( function() {
				expandLastItem( $paramGroupList );
			}, 300 );
		});
	}

	/**
	 * Expand the last item in a param_group list and collapse others.
	 *
	 * @param {jQuery} $list - The .vc_param_group-list element.
	 */
	function expandLastItem( $list ) {
		// WPBakery param_group items - try multiple possible selectors.
		var $items = $list.children( '.vc_param_group-param-group' );

		if ( 0 === $items.length ) {
			$items = $list.children( '.vc_param' );
		}

		if ( 0 === $items.length ) {
			return;
		}

		var $lastItem = $items.last();

		// Collapse all other items that are expanded.
		$items.not( $lastItem ).each( function() {
			var $item = $( this );
			// Check if item is expanded - WPBakery uses various classes.
			var isExpanded = $item.hasClass( 'vc_param_group-currently-editing' ) ||
			                 $item.hasClass( 'editing' ) ||
			                 $item.hasClass( 'vc_active' ) ||
			                 $item.find( '.vc_param_group-wrapper:visible' ).length > 0;

			if ( isExpanded ) {
				// Find toggle button - try multiple selectors.
				var $toggle = $item.find( '.vc_param_group-toggle, .vc_control-btn-close, .vc_param-group-admin-labels' ).first();
				if ( $toggle.length > 0 ) {
					$toggle.trigger( 'click' );
				}
			}
		});

		// Expand the last item if not already expanded.
		var lastIsExpanded = $lastItem.hasClass( 'vc_param_group-currently-editing' ) ||
		                     $lastItem.hasClass( 'editing' ) ||
		                     $lastItem.hasClass( 'vc_active' ) ||
		                     $lastItem.find( '.vc_param_group-wrapper:visible' ).length > 0;

		if ( ! lastIsExpanded ) {
			// Find toggle button.
			var $toggle = $lastItem.find( '.vc_param_group-toggle, .vc_param-group-admin-labels' ).first();
			if ( $toggle.length > 0 ) {
				$toggle.trigger( 'click' );
			}
		}
	}

	/**
	 * Wait for panel element and bind event.
	 */
	function waitForPanel() {
		var $panel = $('#vc_ui-panel-edit-element');

		if ($panel.length > 0) {
			// Panel exists, bind the event.
			$panel.off('vcPanel.shown.cph').on('vcPanel.shown.cph', function() {
				var $shortcode = $panel.attr('data-vc-shortcode') || '';

				if ($shortcode) {
					initCPHDeviceGroups($shortcode);

					// Initialize Stats Grid param_group behavior.
					if ('cph_stats_grid' === $shortcode) {
						initStatsGridParamGroup();
					}
				}
			});
		} else {
			// Panel doesn't exist yet, try again in 500ms.
			setTimeout(waitForPanel, 500);
		}
	}

	/**
	 * Initialize on document ready.
	 */
	$(document).ready(function() {
		waitForPanel();
	});

})(jQuery);
