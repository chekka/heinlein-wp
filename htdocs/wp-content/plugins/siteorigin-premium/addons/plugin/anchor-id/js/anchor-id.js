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
		getAnchors: async function() {
			const validAnchorsIds = soPremium.anchorIds().validAnchorsIds();
			const hash = window.location.hash;

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
			} else {
				return false;
			}
		},

		update: function( anchor, id = false ) {
			// Ensure the anchor is valid.
			const validAnchors = Object.values( soPremium.anchorIds().validAnchorsIds() );
			if ( ! validAnchors.includes( anchor ) ) {
				return;
			}

			// Ensure id is a valid item. If it's not, clear the anchor.
			if ( Array.isArray( id ) ? id.length > 0 : id !== '' ) {
				// In certain situations, the widget won't have a anchor id set.
				// Let's check if this is one of those.
				if ( anchor === id && window.location.hash.includes( anchor ) ) {
					delete soPremiumAnchors[ anchor ];
				} else {
					soPremiumAnchors[ anchor ] = id;
				}
			} else {
				delete soPremiumAnchors[ anchor ];
			}

			const hash = Object.entries( soPremiumAnchors )
				.map(([anchor, id]) => anchor === id ? `${anchor}` : `${anchor}-${id}`)
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
		},

		// To prevent a jump on load, certain widgets will need to disable scrollto temporarily.
		temporarilyDisableScrollTo: function( setting ) {
			let scrollToSetting = setting.scrollto_after_change;
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

		init: async function() {
			if (
				typeof soPremiumAnchors === 'undefined' ||
				! Object.keys( soPremiumAnchors ).length
			) {
				soPremiumAnchors = await soPremium.anchorIds().getAnchors();
			}

			if ( Object.keys( soPremiumAnchors ).length ) {
				let $firstAnchor;

				// Find the first anchor that exists.
				for ( let anchor in soPremiumAnchors ) {
					let $anchor = $( '[data-anchor-id="' + anchor + '"]' );
					if ( $anchor.length > 0 ) {
						$firstAnchor = $anchor;
						break;
					}
				}

				// If we were able to find an active anchor, scroll to it.
				if ( $firstAnchor ) {
					let navOffset = soPremiumAnchorId.scrollto_offset ? soPremiumAnchorId.scrollto_offset : 90;
					let widgetId = $firstAnchor.attr( 'class' ).match( /so-widget-sow-([a-z-]+)/ );

					// Determine the parent element to scroll to.
					let parent;
					if (
						widgetId &&
						(
							widgetId[1] === 'accordion' ||
							widgetId[1] === 'tabs'
						)
					) {
						parent = $firstAnchor.parent();
					} else {
						parent = $firstAnchor.parent().parent();
					}

					$( function() {
						$( 'body, html' ).animate( {
							scrollTop: parent.offset().top - navOffset,
						}, 200, function() {
							const currentScrollTop = $( window ).scrollTop();
							// Is the scroll location is incorrect?
							const correctScrollTop = parent.offset().top - navOffset;
							if ( currentScrollTop != correctScrollTop ) {
								// Set the viewport to to the correct location.
								$( 'body, html' ).scrollTop( correctScrollTop );
							}
						} );
					} );
				}
			}
		},
	} );
	soPremium.anchorIds().init();

	// Handle external hash changes.
	$( window ).on( 'hashchange', function() {
		soPremium.anchorIds().hashChange();
	} );
} );

window.SiteOriginPremium = soPremium;
