/**
 * CPH Stats Bubbles - JavaScript
 *
 * Handles layout positioning with collision avoidance, count-up animations,
 * text scramble effects, and ScrollTrigger integration.
 *
 * @package Salient_Child
 * @since   1.0.0
 */

( function() {
	'use strict';

	/**
	 * Stats Bubbles Controller
	 */
	const CPHStatsBubbles = {
		instances: [],

		/**
		 * Initialize all stats bubbles on the page.
		 */
		init: function() {
			const containers = document.querySelectorAll( '.cph-stats-bubbles' );

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
			if ( container.classList.contains( 'is-positioned' ) ) {
				return;
			}

			const pattern = container.dataset.pattern || 'diagonal';
			const enableCountup = container.dataset.enableCountup === 'true';
			const duration = parseFloat( container.dataset.animationDuration ) || 2;
			const easing = container.dataset.animationEasing || 'power2.out';
			const enableScramble = container.dataset.enableScramble === 'true';
			const bubbles = container.querySelectorAll( '.cph-stats-bubbles__bubble' );

			// Store instance data.
			const instance = {
				container: container,
				bubbles: bubbles,
				pattern: pattern,
				enableCountup: enableCountup,
				duration: duration,
				easing: easing,
				enableScramble: enableScramble,
				hasAnimated: false
			};

			this.instances.push( instance );

			// Position bubbles with collision avoidance.
			this.positionBubbles( instance );

			// Mark as positioned.
			container.classList.add( 'is-positioned' );

			if ( enableCountup && typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined' ) {
				this.setupScrollTrigger( instance );
			} else {
				// No animation, just show bubbles immediately.
				container.classList.add( 'is-initialized' );
			}

			// Handle window resize.
			let resizeTimeout;
			window.addEventListener( 'resize', () => {
				clearTimeout( resizeTimeout );
				resizeTimeout = setTimeout( () => {
					this.positionBubbles( instance );
				}, 250 );
			} );
		},

		/**
		 * Position bubbles using the selected pattern with collision avoidance.
		 *
		 * @param {Object} instance The instance data.
		 */
		positionBubbles: function( instance ) {
			const container = instance.container;
			const bubbles = instance.bubbles;
			const pattern = instance.pattern;

			// Get container dimensions.
			const containerRect = container.getBoundingClientRect();
			const containerWidth = containerRect.width;
			const containerHeight = containerRect.height;

			// Get bubble data (sizes).
			const bubbleData = [];
			bubbles.forEach( ( bubble, index ) => {
				const size = bubble.offsetWidth || 200;
				bubbleData.push( {
					element: bubble,
					index: index,
					radius: size / 2,
					x: 0,
					y: 0
				} );
			} );

			// Generate initial positions based on pattern.
			let positions;
			switch ( pattern ) {
				case 'diagonal':
					positions = this.getDiagonalPositions( bubbleData, containerWidth, containerHeight );
					break;
				case 'scattered':
					positions = this.getScatteredPositions( bubbleData, containerWidth, containerHeight );
					break;
				case 'arc':
					positions = this.getArcPositions( bubbleData, containerWidth, containerHeight );
					break;
				case 'staggered':
					positions = this.getStaggeredPositions( bubbleData, containerWidth, containerHeight );
					break;
				case 'random':
					positions = this.getRandomPositions( bubbleData, containerWidth, containerHeight );
					break;
				default:
					positions = this.getDiagonalPositions( bubbleData, containerWidth, containerHeight );
			}

			// Apply collision avoidance.
			positions = this.resolveCollisions( positions, containerWidth, containerHeight );

			// Apply positions to DOM.
			positions.forEach( ( pos ) => {
				pos.element.style.left = pos.x + 'px';
				pos.element.style.top = pos.y + 'px';
			} );
		},

		/**
		 * Get diagonal cascade positions.
		 */
		getDiagonalPositions: function( bubbleData, width, height ) {
			const count = bubbleData.length;
			const positions = [];

			bubbleData.forEach( ( bubble, i ) => {
				const progress = count > 1 ? i / ( count - 1 ) : 0.5;
				const radius = bubble.radius;

				// Diagonal from top-left to bottom-right with some variation.
				const baseX = progress * ( width - radius * 2 ) + radius;
				const baseY = progress * ( height - radius * 2 ) * 0.7 + radius;

				// Add some organic variation.
				const offsetX = Math.sin( i * 1.5 ) * width * 0.1;
				const offsetY = Math.cos( i * 2 ) * height * 0.08;

				positions.push( {
					...bubble,
					x: Math.max( radius, Math.min( width - radius, baseX + offsetX ) ),
					y: Math.max( radius, Math.min( height - radius, baseY + offsetY ) )
				} );
			} );

			return positions;
		},

		/**
		 * Get scattered positions.
		 */
		getScatteredPositions: function( bubbleData, width, height ) {
			const positions = [];
			const count = bubbleData.length;

			// Predefined scatter zones for organic feel.
			const zones = [
				{ x: 0.12, y: 0.15 },
				{ x: 0.55, y: 0.08 },
				{ x: 0.82, y: 0.22 },
				{ x: 0.08, y: 0.55 },
				{ x: 0.38, y: 0.42 },
				{ x: 0.72, y: 0.55 },
				{ x: 0.22, y: 0.78 },
				{ x: 0.58, y: 0.72 },
				{ x: 0.85, y: 0.8 },
				{ x: 0.45, y: 0.88 }
			];

			bubbleData.forEach( ( bubble, i ) => {
				const zone = zones[ i % zones.length ];
				const radius = bubble.radius;

				positions.push( {
					...bubble,
					x: Math.max( radius, Math.min( width - radius, zone.x * width ) ),
					y: Math.max( radius, Math.min( height - radius, zone.y * height ) )
				} );
			} );

			return positions;
		},

		/**
		 * Get arc positions.
		 */
		getArcPositions: function( bubbleData, width, height ) {
			const positions = [];
			const count = bubbleData.length;

			bubbleData.forEach( ( bubble, i ) => {
				const progress = count > 1 ? i / ( count - 1 ) : 0.5;
				const radius = bubble.radius;

				// Arc from left to right with parabolic curve.
				const x = progress * ( width - radius * 2 ) + radius;
				// Parabola: highest in middle, lower at edges.
				const arcHeight = height * 0.5;
				const arcY = -4 * arcHeight * ( progress - 0.5 ) * ( progress - 0.5 ) + arcHeight;
				const y = height * 0.1 + ( height * 0.6 - arcY );

				positions.push( {
					...bubble,
					x: Math.max( radius, Math.min( width - radius, x ) ),
					y: Math.max( radius, Math.min( height - radius, y ) )
				} );
			} );

			return positions;
		},

		/**
		 * Get staggered row positions.
		 */
		getStaggeredPositions: function( bubbleData, width, height ) {
			const positions = [];
			const count = bubbleData.length;

			// Calculate rows and columns.
			const cols = Math.ceil( Math.sqrt( count * ( width / height ) ) );
			const rows = Math.ceil( count / cols );

			bubbleData.forEach( ( bubble, i ) => {
				const row = Math.floor( i / cols );
				const col = i % cols;
				const radius = bubble.radius;

				// Calculate spacing.
				const xSpacing = width / ( cols + 0.5 );
				const ySpacing = height / ( rows + 0.5 );

				// Offset every other row.
				const rowOffset = row % 2 === 1 ? xSpacing * 0.4 : 0;

				const x = col * xSpacing + xSpacing * 0.5 + rowOffset;
				const y = row * ySpacing + ySpacing * 0.5;

				positions.push( {
					...bubble,
					x: Math.max( radius, Math.min( width - radius, x ) ),
					y: Math.max( radius, Math.min( height - radius, y ) )
				} );
			} );

			return positions;
		},

		/**
		 * Get random positions (different on each page load).
		 */
		getRandomPositions: function( bubbleData, width, height ) {
			const positions = [];
			const placed = [];

			// Sort by size (largest first) for better placement.
			const sorted = [ ...bubbleData ].sort( ( a, b ) => b.radius - a.radius );

			sorted.forEach( ( bubble ) => {
				const radius = bubble.radius;
				let bestPosition = null;
				let bestDistance = -1;
				const maxAttempts = 100;

				// Try to find a position with maximum distance from other bubbles.
				for ( let attempt = 0; attempt < maxAttempts; attempt++ ) {
					// Random position within bounds.
					const x = radius + Math.random() * ( width - radius * 2 );
					const y = radius + Math.random() * ( height - radius * 2 );

					// Check minimum distance to all placed bubbles.
					let minDistance = Infinity;
					let hasCollision = false;

					for ( const other of placed ) {
						const dx = x - other.x;
						const dy = y - other.y;
						const distance = Math.sqrt( dx * dx + dy * dy );
						const minRequired = radius + other.radius + 10; // 10px buffer.

						if ( distance < minRequired ) {
							hasCollision = true;
							break;
						}

						minDistance = Math.min( minDistance, distance - minRequired );
					}

					if ( ! hasCollision && minDistance > bestDistance ) {
						bestDistance = minDistance;
						bestPosition = { x, y };
					}

					// If we found a good spot with decent spacing, use it.
					if ( bestDistance > 50 ) {
						break;
					}
				}

				// Use best position found, or fallback to last attempted.
				const finalX = bestPosition ? bestPosition.x : radius + Math.random() * ( width - radius * 2 );
				const finalY = bestPosition ? bestPosition.y : radius + Math.random() * ( height - radius * 2 );

				const pos = {
					...bubble,
					x: finalX,
					y: finalY
				};

				positions.push( pos );
				placed.push( pos );
			} );

			// Re-sort by original index for consistent DOM order.
			positions.sort( ( a, b ) => a.index - b.index );

			return positions;
		},

		/**
		 * Resolve collisions using iterative relaxation.
		 *
		 * @param {Array}  positions       Array of position objects.
		 * @param {number} containerWidth  Container width.
		 * @param {number} containerHeight Container height.
		 * @return {Array} Adjusted positions.
		 */
		resolveCollisions: function( positions, containerWidth, containerHeight ) {
			const iterations = 50;
			const buffer = 10; // Minimum gap between bubbles.

			for ( let iter = 0; iter < iterations; iter++ ) {
				let hasCollision = false;

				// Check each pair for collisions.
				for ( let i = 0; i < positions.length; i++ ) {
					for ( let j = i + 1; j < positions.length; j++ ) {
						const a = positions[ i ];
						const b = positions[ j ];

						const dx = b.x - a.x;
						const dy = b.y - a.y;
						const distance = Math.sqrt( dx * dx + dy * dy );
						const minDistance = a.radius + b.radius + buffer;

						if ( distance < minDistance && distance > 0 ) {
							hasCollision = true;

							// Calculate overlap.
							const overlap = minDistance - distance;
							const pushX = ( dx / distance ) * overlap * 0.5;
							const pushY = ( dy / distance ) * overlap * 0.5;

							// Push bubbles apart.
							a.x -= pushX;
							a.y -= pushY;
							b.x += pushX;
							b.y += pushY;
						}
					}
				}

				// Constrain to container bounds.
				positions.forEach( ( pos ) => {
					pos.x = Math.max( pos.radius, Math.min( containerWidth - pos.radius, pos.x ) );
					pos.y = Math.max( pos.radius, Math.min( containerHeight - pos.radius, pos.y ) );
				} );

				// If no collisions, we're done.
				if ( ! hasCollision ) {
					break;
				}
			}

			return positions;
		},

		/**
		 * Setup ScrollTrigger for count-up animation.
		 *
		 * @param {Object} instance The instance data.
		 */
		setupScrollTrigger: function( instance ) {
			const self = this;

			ScrollTrigger.create( {
				trigger: instance.container,
				start: 'top 80%',
				once: true,
				onEnter: function() {
					if ( ! instance.hasAnimated ) {
						instance.hasAnimated = true;
						self.animateBubbles( instance );
					}
				}
			} );
		},

		/**
		 * Animate all bubbles in a container.
		 *
		 * @param {Object} instance The instance data.
		 */
		animateBubbles: function( instance ) {
			const self = this;

			// Add initialized class to trigger CSS transitions.
			instance.container.classList.add( 'is-initialized' );

			// Animate each bubble with stagger.
			instance.bubbles.forEach( ( bubble, index ) => {
				const delay = index * 0.15;

				setTimeout( () => {
					self.animateBubble( bubble, instance );
				}, delay * 1000 );
			} );
		},

		/**
		 * Animate a single bubble's value.
		 *
		 * @param {HTMLElement} bubble   The bubble element.
		 * @param {Object}      instance The instance data.
		 */
		animateBubble: function( bubble, instance ) {
			const valueEl = bubble.querySelector( '.cph-stats-bubbles__value' );
			if ( ! valueEl ) {
				return;
			}

			const rawValue = parseFloat( bubble.dataset.rawValue ) || 0;
			const prefix = bubble.dataset.prefix || '';
			const suffix = bubble.dataset.suffix || '';
			const decimals = parseInt( bubble.dataset.decimals, 10 ) || 0;
			const hasCommas = bubble.dataset.hasCommas === 'true';

			// If value is 0 or not a number, skip animation.
			if ( rawValue === 0 && prefix === '' && suffix === '' ) {
				return;
			}

			// Create animation object.
			const animObj = { value: 0 };

			gsap.to( animObj, {
				value: rawValue,
				duration: instance.duration,
				ease: instance.easing,
				onUpdate: function() {
					let displayValue = animObj.value;

					// Format with decimals.
					if ( decimals > 0 ) {
						displayValue = displayValue.toFixed( decimals );
					} else {
						displayValue = Math.round( displayValue );
					}

					// Add commas if original had them.
					if ( hasCommas ) {
						displayValue = CPHStatsBubbles.addCommas( displayValue );
					}

					// Handle scramble effect for suffix.
					let displaySuffix = suffix;
					if ( instance.enableScramble && suffix.length > 0 ) {
						const progress = animObj.value / rawValue;
						displaySuffix = CPHStatsBubbles.scrambleText( suffix, progress );
					}

					valueEl.textContent = prefix + displayValue + displaySuffix;
				},
				onComplete: function() {
					// Ensure final value is exact.
					let finalValue = rawValue;
					if ( decimals > 0 ) {
						finalValue = rawValue.toFixed( decimals );
					}
					if ( hasCommas ) {
						finalValue = CPHStatsBubbles.addCommas( finalValue );
					}
					valueEl.textContent = prefix + finalValue + suffix;
				}
			} );
		},

		/**
		 * Add commas to a number for display.
		 *
		 * @param {number|string} num The number to format.
		 * @return {string} Formatted number with commas.
		 */
		addCommas: function( num ) {
			const parts = num.toString().split( '.' );
			parts[0] = parts[0].replace( /\B(?=(\d{3})+(?!\d))/g, ',' );
			return parts.join( '.' );
		},

		/**
		 * Scramble text effect - reveals characters progressively.
		 *
		 * @param {string} text     The text to scramble.
		 * @param {number} progress Progress from 0 to 1.
		 * @return {string} Scrambled text.
		 */
		scrambleText: function( text, progress ) {
			const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
			const length = text.length;
			let result = '';

			for ( let i = 0; i < length; i++ ) {
				// Calculate when this character should be revealed.
				const charProgress = ( i + 1 ) / length;

				if ( progress >= charProgress * 0.8 ) {
					// Character is revealed.
					result += text[i];
				} else if ( progress > charProgress * 0.3 ) {
					// Character is scrambling.
					result += chars[ Math.floor( Math.random() * chars.length ) ];
				} else {
					// Character not yet started.
					result += chars[ Math.floor( Math.random() * chars.length ) ];
				}
			}

			return result;
		},

		/**
		 * Refresh ScrollTrigger instances (useful after dynamic content load).
		 */
		refresh: function() {
			if ( typeof ScrollTrigger !== 'undefined' ) {
				ScrollTrigger.refresh();
			}
		}
	};

	/**
	 * Initialize on DOM ready.
	 */
	function onReady() {
		CPHStatsBubbles.init();
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
					// Check if the added node is a stats bubbles container.
					if ( node.classList && node.classList.contains( 'cph-stats-bubbles' ) ) {
						CPHStatsBubbles.initContainer( node );
					}
					// Check for stats bubbles containers within the added node.
					const containers = node.querySelectorAll ? node.querySelectorAll( '.cph-stats-bubbles' ) : [];
					containers.forEach( function( container ) {
						CPHStatsBubbles.initContainer( container );
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
	window.CPHStatsBubbles = CPHStatsBubbles;

} )();
