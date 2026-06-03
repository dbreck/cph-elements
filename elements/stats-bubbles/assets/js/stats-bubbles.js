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
			const autoSize = container.dataset.autoSize === 'true';
			const autoSizePadding = parseInt( container.dataset.autoSizePadding, 10 );
			const bubbleEls = container.querySelectorAll( '.cph-stats-bubbles__bubble' );

			// Capture each bubble's natural size. In auto mode we measure the
			// rendered text block and compute the diameter of the circle that
			// wraps it plus padding. Otherwise we use the CSS-driven size so
			// responsive shrink-to-fit can restore it on up-resize.
			const bubbleData = [];
			bubbleEls.forEach( ( bubble, index ) => {
				let size;
				if ( autoSize ) {
					size = this.measureAutoSize(
						bubble,
						container,
						isNaN( autoSizePadding ) ? 30 : autoSizePadding
					);
					bubble.style.setProperty( '--bubble-size', size + 'px' );
				} else {
					size = bubble.offsetWidth || 200;
				}
				bubbleData.push( {
					element: bubble,
					index: index,
					originalSize: size
				} );
			} );

			// Store instance data.
			const instance = {
				container: container,
				bubbles: bubbleEls,
				bubbleData: bubbleData,
				pattern: pattern,
				enableCountup: enableCountup,
				duration: duration,
				easing: easing,
				enableScramble: enableScramble,
				autoSize: autoSize,
				autoSizePadding: isNaN( autoSizePadding ) ? 30 : autoSizePadding,
				hasAnimated: false
			};

			this.instances.push( instance );

			// Position bubbles with collision avoidance.
			this.positionBubbles( instance );

			// Mark as positioned.
			container.classList.add( 'is-positioned' );

			// If auto-sizing, re-measure after custom fonts have loaded so
			// the initial size isn't based on fallback-font metrics.
			if ( autoSize && document.fonts && document.fonts.status !== 'loaded' ) {
				const self = this;
				document.fonts.ready.then( function() {
					self.remeasureAutoSizes( instance );
					self.positionBubbles( instance );
				} );
			}

			if ( enableCountup && typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined' ) {
				this.setupScrollTrigger( instance );
			} else {
				// No animation, just show bubbles immediately.
				container.classList.add( 'is-initialized' );
			}

			// Observe the container itself so we react to column / flex
			// width changes, not just viewport resizes.
			const debouncedReposition = this.debounce( () => {
				this.positionBubbles( instance );
			}, 150 );

			if ( typeof ResizeObserver !== 'undefined' ) {
				const ro = new ResizeObserver( debouncedReposition );
				ro.observe( container );
				instance.resizeObserver = ro;
			} else {
				window.addEventListener( 'resize', debouncedReposition );
			}
		},

		/**
		 * Debounce helper.
		 */
		debounce: function( fn, wait ) {
			let t;
			return function() {
				clearTimeout( t );
				t = setTimeout( fn, wait );
			};
		},

		/**
		 * Measure the rendered size a bubble needs to wrap its text content,
		 * plus the requested radial padding inside the circle.
		 *
		 * Clones the bubble off-screen with the same class set (so it inherits
		 * fonts / weights / sizes from the live stylesheet), lets the text flow
		 * at a sensible label max-width, measures the bounding box, and returns
		 * the diameter of the smallest circle that encloses that box plus 2×padding.
		 *
		 * @param {HTMLElement} bubble            Live bubble node to measure.
		 * @param {HTMLElement} container         Parent container (for style inheritance).
		 * @param {number}      radialPadding     Extra px between text and circle edge.
		 * @return {number} Bubble diameter in px.
		 */
		measureAutoSize: function( bubble, container, radialPadding ) {
			const LABEL_WRAP_WIDTH = 200;

			const clone = bubble.cloneNode( true );
			clone.style.position = 'absolute';
			clone.style.visibility = 'hidden';
			clone.style.pointerEvents = 'none';
			clone.style.left = '-99999px';
			clone.style.top = '0';
			clone.style.width = 'auto';
			clone.style.height = 'auto';
			clone.style.padding = '0';
			clone.style.borderRadius = '0';
			clone.style.setProperty( '--bubble-size', 'auto' );

			// Let the label wrap at a readable width instead of 80% of a bubble
			// size we haven't calculated yet.
			const labelEl = clone.querySelector( '.cph-stats-bubbles__label' );
			if ( labelEl ) {
				labelEl.style.maxWidth = LABEL_WRAP_WIDTH + 'px';
			}

			container.appendChild( clone );
			const width = clone.offsetWidth;
			const height = clone.offsetHeight;
			container.removeChild( clone );

			if ( ! width || ! height ) {
				return 200;
			}

			// Minimum enclosing circle of the text rectangle is its diagonal.
			const diagonal = Math.sqrt( width * width + height * height );
			const padding = ( typeof radialPadding === 'number' && radialPadding >= 0 ) ? radialPadding : 30;

			return Math.ceil( diagonal + padding * 2 );
		},

		/**
		 * Re-measure and store fresh originalSize for every bubble in an auto-size instance.
		 */
		remeasureAutoSizes: function( instance ) {
			if ( ! instance.autoSize ) {
				return;
			}
			instance.bubbleData.forEach( ( data ) => {
				const newSize = this.measureAutoSize(
					data.element,
					instance.container,
					instance.autoSizePadding
				);
				data.element.style.setProperty( '--bubble-size', newSize + 'px' );
				data.originalSize = newSize;
			} );
		},

		/**
		 * Position bubbles using the selected pattern with collision avoidance.
		 *
		 * Scales bubble sizes down if needed so they fit inside the container
		 * without overlapping. Bubbles are restored to their original size
		 * when the container grows back.
		 *
		 * @param {Object} instance The instance data.
		 */
		positionBubbles: function( instance ) {
			const container = instance.container;
			const pattern = instance.pattern;
			const buffer = 10;

			// Get container dimensions.
			const containerRect = container.getBoundingClientRect();
			const containerWidth = containerRect.width;
			const containerHeight = containerRect.height;

			// Nothing to do if the container has no size (hidden, or mobile
			// layout has flex-stacked the bubbles and removed absolute sizing).
			if ( containerWidth < 1 || containerHeight < 1 ) {
				return;
			}

			// Compute how much we need to scale every bubble down so the set
			// physically fits inside the container. If the container is big
			// enough, scale stays at 1 and originals are used as-is.
			const scale = this.computeFitScale(
				instance.bubbleData,
				containerWidth,
				containerHeight,
				buffer
			);

			// Build the working position set, applying the scaled size to the DOM.
			const positions = instance.bubbleData.map( ( data ) => {
				const scaledSize = Math.max( 40, Math.floor( data.originalSize * scale ) );
				data.element.style.setProperty( '--bubble-size', scaledSize + 'px' );
				return {
					element: data.element,
					index: data.index,
					radius: scaledSize / 2,
					x: 0,
					y: 0
				};
			} );

			// Seed positions based on the selected pattern.
			this.applyPattern( pattern, positions, containerWidth, containerHeight );

			// Resolve collisions iteratively.
			this.resolveCollisions( positions, containerWidth, containerHeight, buffer );

			// Safety net: if any overlap survived the relaxation (e.g. because
			// the container clamped bubbles against a wall), shrink 10% and retry.
			let shrinkTries = 0;
			while ( this.hasOverlap( positions, buffer ) && shrinkTries < 6 ) {
				positions.forEach( ( pos ) => {
					pos.radius *= 0.9;
					pos.element.style.setProperty(
						'--bubble-size',
						Math.max( 40, Math.floor( pos.radius * 2 ) ) + 'px'
					);
				} );
				this.applyPattern( pattern, positions, containerWidth, containerHeight );
				this.resolveCollisions( positions, containerWidth, containerHeight, buffer );
				shrinkTries++;
			}

			// Apply positions to DOM.
			positions.forEach( ( pos ) => {
				pos.element.style.left = pos.x + 'px';
				pos.element.style.top = pos.y + 'px';
			} );
		},

		/**
		 * Dispatch to the right pattern generator and write positions in-place.
		 */
		applyPattern: function( pattern, positions, width, height ) {
			let generated;
			switch ( pattern ) {
				case 'scattered':
					generated = this.getScatteredPositions( positions, width, height );
					break;
				case 'arc':
					generated = this.getArcPositions( positions, width, height );
					break;
				case 'staggered':
					generated = this.getStaggeredPositions( positions, width, height );
					break;
				case 'random':
					generated = this.getRandomPositions( positions, width, height );
					break;
				case 'diagonal':
				default:
					generated = this.getDiagonalPositions( positions, width, height );
					break;
			}

			// The pattern fns return new objects; copy x/y back onto our live positions.
			generated.forEach( ( g, i ) => {
				const target = positions[ i ];
				if ( target ) {
					target.x = g.x;
					target.y = g.y;
				}
			} );
		},

		/**
		 * Compute the largest uniform scale where the bubbles can physically
		 * coexist inside the container without forced overlap.
		 *
		 * Two constraints:
		 *   1. The largest bubble must fit within the container's short side.
		 *   2. Total bubble area (plus buffer) must stay under a realistic
		 *      packing fraction of the container area.
		 */
		computeFitScale: function( bubbleData, width, height, buffer ) {
			if ( ! bubbleData.length ) {
				return 1;
			}

			let scale = 1;

			// Constraint 1: largest bubble fits within container bounds.
			let maxRadius = 0;
			bubbleData.forEach( ( b ) => {
				const r = b.originalSize / 2;
				if ( r > maxRadius ) {
					maxRadius = r;
				}
			} );
			const maxAllowedRadius = Math.min( width, height ) / 2 - buffer;
			if ( maxAllowedRadius > 0 && maxRadius > maxAllowedRadius ) {
				scale = Math.min( scale, maxAllowedRadius / maxRadius );
			}

			// Constraint 2: summed bubble area (with buffer) vs. realistic
			// packable container area. 0.55 is a conservative packing target
			// for arbitrary circle positions with gaps.
			let totalArea = 0;
			bubbleData.forEach( ( b ) => {
				const r = b.originalSize / 2 + buffer / 2;
				totalArea += Math.PI * r * r;
			} );
			const packable = width * height * 0.55;
			if ( totalArea > packable && packable > 0 ) {
				// Area scales with the square of linear scale.
				scale = Math.min( scale, Math.sqrt( packable / totalArea ) );
			}

			return scale;
		},

		/**
		 * Check if any two positions still overlap given the buffer.
		 */
		hasOverlap: function( positions, buffer ) {
			for ( let i = 0; i < positions.length; i++ ) {
				for ( let j = i + 1; j < positions.length; j++ ) {
					const a = positions[ i ];
					const b = positions[ j ];
					const dx = b.x - a.x;
					const dy = b.y - a.y;
					const distance = Math.sqrt( dx * dx + dy * dy );
					if ( distance < a.radius + b.radius + buffer - 0.5 ) {
						return true;
					}
				}
			}
			return false;
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
		 * @param {number} buffer          Minimum gap between bubbles in px.
		 * @return {Array} Adjusted positions.
		 */
		resolveCollisions: function( positions, containerWidth, containerHeight, buffer ) {
			const iterations = 150;
			if ( typeof buffer !== 'number' ) {
				buffer = 10;
			}

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
