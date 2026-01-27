/**
 * CPH Stats Photos - JavaScript
 *
 * Handles random reveal functionality for stats overlays.
 *
 * @package Salient_Child
 * @since   1.0.0
 */

( function() {
	'use strict';

	/**
	 * Stats Photos Controller
	 */
	const CPHStatsPhotos = {
		instances: [],

		/**
		 * Initialize all stats photos elements on the page.
		 */
		init: function() {
			const containers = document.querySelectorAll( '.cph-stats-photos' );

			containers.forEach( ( container ) => {
				this.initContainer( container );
			} );
		},

		/**
		 * Initialize a single container.
		 *
		 * @param {HTMLElement} container The container element.
		 */
		initContainer: function( container ) {
			// Check if already initialized.
			if ( container.classList.contains( 'is-initialized' ) ) {
				return;
			}

			const showRandomly = container.dataset.showRandomly === 'true';
			const randomInterval = parseFloat( container.dataset.randomInterval ) || 3;
			const items = container.querySelectorAll( '.cph-stats-photos__item--has-stats' );

			// If no random mode or no items with stats, just mark as initialized.
			if ( ! showRandomly || items.length === 0 ) {
				container.classList.add( 'is-initialized' );
				return;
			}

			// Store instance data.
			const instance = {
				container: container,
				items: Array.from( items ),
				interval: randomInterval * 1000,
				timer: null,
				isPaused: false
			};

			this.instances.push( instance );

			// Initial random state.
			this.randomizeActive( instance );

			// Set up interval for random changes.
			instance.timer = setInterval( () => {
				if ( ! instance.isPaused ) {
					this.randomizeActive( instance );
				}
			}, instance.interval );

			// Pause random changes on hover (for hover mode).
			if ( container.dataset.showOnHover === 'true' ) {
				items.forEach( ( item ) => {
					item.addEventListener( 'mouseenter', () => {
						instance.isPaused = true;
					} );
					item.addEventListener( 'mouseleave', () => {
						instance.isPaused = false;
					} );
				} );
			}

			// Mark as initialized.
			container.classList.add( 'is-initialized' );
		},

		/**
		 * Randomly activate some items.
		 *
		 * @param {Object} instance The instance data.
		 */
		randomizeActive: function( instance ) {
			const items = instance.items;
			const count = items.length;

			// Determine how many to show (1 to ceil(count/2), minimum 1).
			const minShow = 1;
			const maxShow = Math.max( 1, Math.ceil( count / 2 ) );
			const numToShow = Math.floor( Math.random() * ( maxShow - minShow + 1 ) ) + minShow;

			// Create array of indices and shuffle.
			const indices = items.map( ( _, i ) => i );
			this.shuffleArray( indices );

			// Select which indices to activate.
			const activeIndices = new Set( indices.slice( 0, numToShow ) );

			// Apply active state.
			items.forEach( ( item, index ) => {
				if ( activeIndices.has( index ) ) {
					item.classList.add( 'is-active' );
				} else {
					item.classList.remove( 'is-active' );
				}
			} );
		},

		/**
		 * Fisher-Yates shuffle algorithm.
		 *
		 * @param {Array} array The array to shuffle.
		 */
		shuffleArray: function( array ) {
			for ( let i = array.length - 1; i > 0; i-- ) {
				const j = Math.floor( Math.random() * ( i + 1 ) );
				[ array[ i ], array[ j ] ] = [ array[ j ], array[ i ] ];
			}
		},

		/**
		 * Destroy an instance and clean up.
		 *
		 * @param {Object} instance The instance to destroy.
		 */
		destroy: function( instance ) {
			if ( instance.timer ) {
				clearInterval( instance.timer );
				instance.timer = null;
			}

			instance.items.forEach( ( item ) => {
				item.classList.remove( 'is-active' );
			} );

			instance.container.classList.remove( 'is-initialized' );

			// Remove from instances array.
			const index = this.instances.indexOf( instance );
			if ( index > -1 ) {
				this.instances.splice( index, 1 );
			}
		},

		/**
		 * Destroy all instances.
		 */
		destroyAll: function() {
			this.instances.forEach( ( instance ) => {
				if ( instance.timer ) {
					clearInterval( instance.timer );
				}
			} );
			this.instances = [];
		}
	};

	/**
	 * Initialize on DOM ready.
	 */
	function onReady() {
		CPHStatsPhotos.init();
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', onReady );
	} else {
		onReady();
	}

	/**
	 * Watch for dynamically added content.
	 */
	const observer = new MutationObserver( function( mutations ) {
		mutations.forEach( function( mutation ) {
			mutation.addedNodes.forEach( function( node ) {
				if ( node.nodeType === 1 ) {
					// Check if the added node is a stats photos container.
					if ( node.classList && node.classList.contains( 'cph-stats-photos' ) ) {
						CPHStatsPhotos.initContainer( node );
					}
					// Check for stats photos containers within the added node.
					const containers = node.querySelectorAll ? node.querySelectorAll( '.cph-stats-photos' ) : [];
					containers.forEach( function( container ) {
						CPHStatsPhotos.initContainer( container );
					} );
				}
			} );
		} );
	} );

	observer.observe( document.body, {
		childList: true,
		subtree: true
	} );

	// Expose to global scope for external access.
	window.CPHStatsPhotos = CPHStatsPhotos;

} )();
