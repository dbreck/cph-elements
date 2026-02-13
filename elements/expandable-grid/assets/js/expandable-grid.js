/**
 * CPH Expandable Grid
 *
 * Accordion-style expand/collapse for grid items.
 * Only one item can be expanded at a time.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		var grids = document.querySelectorAll( '.cph-grid' );

		grids.forEach( function ( grid ) {
			initGrid( grid );
		} );
	} );

	/**
	 * Initialize a single grid instance.
	 *
	 * @param {HTMLElement} grid The grid container.
	 */
	function initGrid( grid ) {
		var detail      = grid.querySelector( '.cph-grid__detail' );
		var detailBody  = detail.querySelector( '.cph-grid__detail-body' );
		var detailBtn   = detail.querySelector( '.cph-grid__detail-btn' );
		var detailClose = detail.querySelector( '.cph-grid__detail-close' );
		var btnText     = grid.dataset.btnText || '+ LOAD MORE';
		var closeText   = grid.dataset.closeText || '+ SHOW LESS';
		var columns     = parseInt( grid.dataset.columns, 10 ) || 3;
		var activeItem  = null;

		// Bind toggle buttons.
		var items = grid.querySelectorAll( '.cph-grid__item' );

		items.forEach( function ( item ) {
			var toggle = item.querySelector( '.cph-grid__toggle' );
			if ( ! toggle ) {
				return;
			}

			toggle.addEventListener( 'click', function () {
				if ( activeItem === item ) {
					closeDetail();
				} else {
					openDetail( item );
				}
			} );
		} );

		// Close button in detail panel.
		if ( detailClose ) {
			detailClose.addEventListener( 'click', function () {
				closeDetail();
			} );
		}

		/**
		 * Get current column count (respects CSS override on mobile).
		 *
		 * @return {number}
		 */
		function getColumns() {
			var style = getComputedStyle( grid );
			var cols  = style.getPropertyValue( '--cph-grid-columns' );

			return parseInt( cols, 10 ) || columns;
		}

		/**
		 * Get the items array (excluding the detail panel).
		 *
		 * @return {HTMLElement[]}
		 */
		function getItems() {
			return Array.from( grid.querySelectorAll( '.cph-grid__item' ) );
		}

		/**
		 * Find the last item in the same row as the given item.
		 *
		 * @param {HTMLElement} item The clicked item.
		 * @return {HTMLElement}
		 */
		function getLastItemInRow( item ) {
			var allItems   = getItems();
			var itemIndex  = allItems.indexOf( item );
			var cols       = getColumns();
			var rowStart   = Math.floor( itemIndex / cols ) * cols;
			var rowEnd     = Math.min( rowStart + cols - 1, allItems.length - 1 );

			return allItems[ rowEnd ];
		}

		/**
		 * Open the detail panel for an item.
		 *
		 * @param {HTMLElement} item The grid item to expand.
		 */
		function openDetail( item ) {
			var wasOpen = activeItem !== null;

			// Close current if open.
			if ( wasOpen ) {
				closeDetailImmediate();
			}

			activeItem = item;

			// Capture computed borders BEFORE .is-expanded removes them.
			var itemStyle = getComputedStyle( item );
			var inheritRight  = itemStyle.borderRight;
			var inheritBottom = itemStyle.borderBottom;

			// Update toggle text.
			var toggle = item.querySelector( '.cph-grid__toggle' );
			if ( toggle ) {
				toggle.textContent = closeText;
			}
			item.classList.add( 'is-expanded' );

			// Get content from hidden source.
			var source    = item.querySelector( '.cph-grid__item-content' );
			var body      = source ? source.querySelector( '.cph-grid__body' ) : null;
			var btnEl     = source ? source.querySelector( '.cph-grid__btn' ) : null;

			// Populate detail panel.
			detailBody.innerHTML = body ? body.innerHTML : '';

			if ( btnEl ) {
				detailBtn.href        = btnEl.href;
				detailBtn.textContent = btnEl.textContent;
				detailBtn.hidden      = false;
			} else {
				detailBtn.hidden = true;
			}

			// Position detail after the last item in the row.
			var lastInRow = getLastItemInRow( item );
			lastInRow.after( detail );

			// Pass the active item's borders to the detail panel
			// so CSS can draw continuous borders on mobile.
			detail.style.setProperty( '--cph-grid-b-right-inherit', inheritRight );
			detail.style.setProperty( '--cph-grid-b-bottom-inherit', inheritBottom );

			// Show and animate open.
			detail.hidden = false;
			detail.setAttribute( 'aria-hidden', 'false' );

			// Force reflow before animating.
			// eslint-disable-next-line no-unused-expressions
			detail.offsetHeight;

			// Measure content height (offsetHeight includes border).
			var inner = detail.querySelector( '.cph-grid__detail-inner' );
			var targetHeight = inner.offsetHeight;

			detail.style.maxHeight = targetHeight + 'px';
			detail.classList.add( 'is-open' );

			// Scroll to detail panel after animation.
			setTimeout( function () {
				detail.scrollIntoView( { behavior: 'smooth', block: 'nearest' } );
			}, 400 );
		}

		/**
		 * Close the detail panel with animation.
		 */
		function closeDetail() {
			if ( ! activeItem ) {
				return;
			}

			var item   = activeItem;
			var toggle = item.querySelector( '.cph-grid__toggle' );

			// Reset toggle text.
			if ( toggle ) {
				toggle.textContent = btnText;
			}
			item.classList.remove( 'is-expanded' );

			// Animate closed.
			detail.style.maxHeight = '0';
			detail.classList.remove( 'is-open' );

			activeItem = null;

			// Hide after transition.
			setTimeout( function () {
				detail.hidden = true;
				detail.setAttribute( 'aria-hidden', 'true' );
				detailBody.innerHTML = '';
				detail.style.removeProperty( '--cph-grid-b-right-inherit' );
				detail.style.removeProperty( '--cph-grid-b-bottom-inherit' );
			}, 400 );
		}

		/**
		 * Close immediately without animation (for switching items).
		 */
		function closeDetailImmediate() {
			if ( ! activeItem ) {
				return;
			}

			var toggle = activeItem.querySelector( '.cph-grid__toggle' );
			if ( toggle ) {
				toggle.textContent = btnText;
			}
			activeItem.classList.remove( 'is-expanded' );

			detail.style.maxHeight = '0';
			detail.classList.remove( 'is-open' );
			detail.hidden = true;
			detail.setAttribute( 'aria-hidden', 'true' );
			detailBody.innerHTML = '';
			detail.style.removeProperty( '--cph-grid-b-right-inherit' );
				detail.style.removeProperty( '--cph-grid-b-bottom-inherit' );

			activeItem = null;
		}

		/**
		 * Handle window resize — reposition detail if open.
		 */
		var resizeTimer;

		window.addEventListener( 'resize', function () {
			clearTimeout( resizeTimer );
			resizeTimer = setTimeout( function () {
				if ( ! activeItem ) {
					return;
				}

				// Reposition detail panel for the new column layout.
				var lastInRow = getLastItemInRow( activeItem );
				lastInRow.after( detail );

				// Recalculate height.
				var inner = detail.querySelector( '.cph-grid__detail-inner' );
				detail.style.maxHeight = inner.offsetHeight + 'px';
			}, 150 );
		} );
	}
} )();
