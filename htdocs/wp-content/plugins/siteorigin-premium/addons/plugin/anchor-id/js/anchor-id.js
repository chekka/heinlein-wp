/* globals jQuery, sowb */

var soPremium = window.SiteOriginPremium || {};

jQuery( function( $ ) {
	let soPremiumAnchors = {};
	let soPremiumPreventHashDetection = false;
	soPremium.anchorIds = () => ( {
		// Find all widgets with anchor ids, and return their ids.
		validAnchorsIds: function() {
			const widgetsWithAnchors = $( '[class^="so-widget-"][data-anchor-id], .sow-carousel-wrapper[data-anchor-id], .sow-slider-images[data-anchor-id], .so-widget-sow-accordion:not([data-anchor-id]) .sow-accordion-panel[data-anchor-id], .so-widget-sow-tabs:not([data-anchor-id]) .sow-tabs-tab[data-anchor-id]' );
			const validAnchors = widgetsWithAnchors
				.filter( function() {
					const anchorId = $( this ).data( 'anchor-id' );
					return anchorId !== '' &&
						anchorId !== undefined &&
						anchorId !== '__proto__' &&
						anchorId !== 'constructor' &&
						anchorId !== 'prototype';
				} )
				.map( function() {
					const anchorId = String( $( this ).data( 'anchor-id' ) );
					return anchorId.replace( /[^a-z0-9-]/gi, '' );
				} );

			return validAnchors;
		},

		// Retrieve the anchors from the hash. Uses validAnchorsIds to ensure the anchor is valid, and to group as needed.
		getAnchors: async function( hash = window.location.hash ) {
			const validAnchorsIds = soPremium.anchorIds().validAnchorsIds();
			if ( ! hash ) {
				return {};
			}

			const anchorsInHash = hash.slice( 1 ).split(',');
			let detectedAnchors = {};
			anchorsInHash.forEach( anchor => {
				let anchorId = null;
				let currentItem = null;

				// Check if the anchor starts with any of the validAnchorsIds.
				for ( let validAnchorId of Object.values( validAnchorsIds ) ) {
					if ( anchor.startsWith( validAnchorId ) ) {
						anchorId = validAnchorId;
						// If the anchor is the same as the anchorId, set the currentItem as is.
						currentItem = anchorId !== anchor ? anchor.slice( validAnchorId.length + 1 ) : anchor;
						break;
					}
				}

				if ( anchorId ) {
					if ( currentItem ) {
						if ( ! detectedAnchors[ anchorId ]) {
							detectedAnchors[ anchorId ] = [];
						}
						detectedAnchors[ anchorId ].push( currentItem );
					}
				}
			} );

			return detectedAnchors;
		},

		// Returns a single anchor id, or false if it doesn't exist.
		getAnchor: async function( anchor ) {
			// If this is called before set, we'll need to get the anchors.
			if (
				typeof soPremiumAnchors === 'undefined' ||
				! Object.keys( soPremiumAnchors ).length
			) {
				soPremiumAnchors = await soPremium.anchorIds().getAnchors();
			}

			if ( soPremiumAnchors[ anchor ] ) {
				return soPremiumAnchors[ anchor ];
			}

			return false;
		},

		update: function( anchor, id = false ) {
			const anchorId = String( anchor );
			const IdStr = String( id );

			// Ensure the anchor is valid.
			const validAnchors = Object.values( soPremium.anchorIds().validAnchorsIds() );

			if ( ! validAnchors.includes( anchorId ) ) {
				return;
			}

			// Ensure id is a valid item. If it's not, clear the anchor.
			if ( Array.isArray( IdStr ) ? id.length > 0 : IdStr !== '' ) {
				// In certain situations, the widget won't have a anchor id set.
				// Let's check if this is one of those.
				if ( anchorId === IdStr && window.location.hash.includes( anchorId ) ) {
					delete soPremiumAnchors[ anchorId ];
				} else {
					soPremiumAnchors[ anchorId ] = String( IdStr );
				}
			} else {
				delete soPremiumAnchors[ anchorId ];
			}

			const hash = Object.entries( soPremiumAnchors )
				.map( ( [ anchor, id ] ) => anchor === id ? `${ anchor }` : `${ anchor }-${ id }`)
				.join( ',' );

			// Prevent hashChange() from being triggered by this.
			soPremiumPreventHashDetection = true;

			if ( hash === '' ) {
				// If there's no anchor tags (or anything else), safely remove # from the URL.
				history.pushState( {}, null, window.location.pathname );
			} else {
				// Otherwise, update the location hash.
				window.location.hash = hash;
			}

			// Restore hashChange Detection.
			setTimeout( function() {
				soPremiumPreventHashDetection = false;
			}, 200 );
		},

		// To prevent multiple jumps on load, certain widgets will need to disable scrollto temporarily.
		temporarilyDisableScrollTo: function( setting ) {
			let scrollToSetting = setting.scrollto_after_change;

			// If there are multiple of the same widget, we need to
			// confirm we're not overriding the scrollto setting
			// with an invalid value.
			if ( ! scrollToSetting ) {
				return;
			}

			delete setting.scrollto_after_change;
			setTimeout( function() {
				setting.scrollto_after_change = scrollToSetting;
			}, 500 );
		},

		hashChange: async function() {
			if ( soPremiumPreventHashDetection ) {
				soPremiumPreventHashDetection = false;
				return;
			}

			// Get hash and trigger the tab change.
			const hash = window.location.hash;
			if ( ! hash ) {
				return;
			}

			const soPremiumAnchors = await soPremium.anchorIds().getAnchors();
			if ( ! Object.keys( soPremiumAnchors ).length ) {
				return;
			}

			soPremiumPreventHashDetection = true;
			// Loop through the anchors. Using both the value and the key.
			for ( let anchor in soPremiumAnchors ) {
				$( '[data-anchor-id="' + anchor + '"]' ).trigger(
					'anchor_id_hash_change',
					[
						soPremiumAnchors[ anchor ]
					]
				);
			}

			soPremiumPreventHashDetection = false;
		},

		setupAnchorLinks: async function() {
			soPremiumAnchors = await soPremium.anchorIds().getAnchors();
			const validAnchorsIds = soPremium.anchorIds().validAnchorsIds();
			let validAnchorsIdsValues = [];

			if ( ! validAnchorsIds || typeof validAnchorsIds !== 'object' ) {
				// No valid anchors links found, bail.
				return;
			}

			validAnchorsIdsValues = Object.values(validAnchorsIds);

			// Identify any links that have an anchor id on the page.
			$( 'a[href*="#"]' ).each( function() {
				const href = $( this ).attr( 'href' );
				$( 'a[href*="#"]' ).each( function() {
					const href = $( this ).attr( 'href' );
					const anchors = href.split( '#' )[ 1 ].split( ',' );

					for ( const anchor of anchors ) {
						if ( validAnchorsIdsValues.includes( anchor ) ) {
							continue;
						}

						$( this ).addClass( 'so-anchor-id-link' );
						break;
					}
				} );

				$( this ).addClass( 'so-anchor-id-link' );
			} );
		},

		init: async function() {
			if (
				typeof soPremiumAnchors === 'undefined' ||
				! Object.keys( soPremiumAnchors ).length
			) {
				soPremiumAnchors = await soPremium.anchorIds().getAnchors();
				soPremium.anchorIds().setupAnchorLinks();
			}

			if ( Object.keys( soPremiumAnchors ).length ) {
				let firstAnchor;
				let $firstAnchor;

				// Find the first anchor that exists.
				for ( let anchor in soPremiumAnchors ) {
					let $anchor = $( '[data-anchor-id="' + anchor + '"]' );
					if ( $anchor.length > 0 ) {
						firstAnchor = anchor;
						$firstAnchor = $anchor;
						break;
					}
				}

				// If we were able to find an active anchor, scroll to it.
				if ( $firstAnchor ) {
					let widgetId = $firstAnchor.attr( 'class' ).match( /so-widget-sow-([a-z-]+)/ );

					// Determine the target element to scroll to.
					let target;
					if (
						widgetId &&
						(
							widgetId[1] === 'accordion' ||
							widgetId[1] === 'tabs'
						)
					) {
						target = $firstAnchor.find( '.sow-accordion-panel[data-anchor-id="' + soPremiumAnchors[ firstAnchor ] + '"], .sow-tabs-tab[data-anchor-id="' + soPremiumAnchors[ firstAnchor ] + '"]' )
					} else {
						target = $firstAnchor.parent().parent();
					}

					// Delay the scroll to ensure the page has loaded.
					$( function() {
						setTimeout( function() {
							soPremium.anchorIds().scrollToAnchor( target );
						}, 200 );
					} );
				}
			}
		},

		scrollToAnchor: function( target ) {
			let navOffset = soPremiumAnchorId.scrollto_offset ? soPremiumAnchorId.scrollto_offset : 90;

			if ( ! target.length ) {
				return;
			}

			$( 'body, html' ).animate( {
				scrollTop: target.offset().top - navOffset,
			}, 200, function() {
				const currentScrollTop = $( window ).scrollTop();
				// Is the scroll location is incorrect?
				const correctScrollTop = target.offset().top - navOffset;
				if ( currentScrollTop != correctScrollTop ) {
					// Set the viewport to to the correct location.
					$( 'body, html' ).scrollTop( correctScrollTop );
				}
			} );
		},
	} );
	soPremium.anchorIds().init();

	// Handle external hash changes.
	$( window ).on( 'hashchange', function() {
		soPremium.anchorIds().hashChange();
	} );

	// Handle anchor id link clicks after page load.
	$( document ).on( 'click', '.so-anchor-id-link', async function() {
		// Ensure this is a valid link.
		const href = $( this ).attr( 'href' );
		if ( ! href ) {
			return;
		}

		const hashAndAnchors = href.split( '#' ).slice( 1 ).join( '#' );
		const anchorsObject = await soPremium.anchorIds().getAnchors(`#${ hashAndAnchors }`);

		// Did we find some valid anchors?
		if ( ! anchorsObject ) {
			return;
		}

		const anchors = Object.entries( anchorsObject ).map( ( [ index, value ] ) => ( { index, value: value[0] } ) );

		for ( const anchor of anchors ) {
			const { index, value } = anchor;
			let $anchor;

			// Is this a parent/child anchor?
			if ( ! isNaN( parseInt( value ) ) ) {
				const $parent = $( `[data-anchor-id="${ index }"]` );
				if ( ! $parent.length ) {
					continue;
				}

				let $child = $parent.find( `[data-anchor-id="${ value }"]` );
				if ( ! $child.length ) {
					// If the child doesn't exist, it's a slide based anchor.
					// Try triggering a hash change, and then scrolling to the parent.
					$parent.trigger( 'anchor_id_hash_change', [ value ] );
					$anchor = $parent;
				} else {
					// Scroll to child.
					$anchor = $child;
				}
			} else {
				$anchor = $( `[data-anchor-id="${ value }"]` );
			}

			if ( ! $anchor.length ) {
				continue;
			}

			// Scroll to the anchor.
			// The actual functionality of the anchor id will be
			// handled by the relevant widget.
			soPremium.anchorIds().scrollToAnchor( $anchor );

			// We're only able to scroll to the first valid anchor,
			// so stop processing after that's found.
			break;
		}
	} );
} );

window.SiteOriginPremium = soPremium;
