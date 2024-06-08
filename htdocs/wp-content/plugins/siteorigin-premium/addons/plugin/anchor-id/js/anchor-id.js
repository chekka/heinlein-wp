/* globals jQuery, sowb */

var soPremium = window.SiteOriginPremium || {};

jQuery( function( $ ) {
	let soPremiumAnchors = {};
	soPremium.anchorIds = () => ( {
		// Find all widgets with anchor ids, and return their ids.
		validAnchorsIds: function() {
			const widgetsWithAnchors = $( '[class^="so-widget-"][data-anchor-id], .sow-carousel-wrapper[data-anchor-id], .sow-slider-images[data-anchor-id]' );
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
					return $( this ).data( 'anchor-id' ).replace( /[^a-z0-9-]/gi, '' );
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
			const anchorsInHash = hash.slice(1).split(',');
			let detectedAnchors = {};
			let lastAnchorName = null;

			anchorsInHash.forEach( anchor => {
				const matches = anchor.match( /([a-z-]+)-(\w+)/ );
				// Is this a new anchor id?
				if ( matches ) {
					const [ ignore, name, id ] = matches;

					lastAnchorName = name;
					if ( Object.values( validAnchorsIds ).includes( name ) ) {
						if ( ! detectedAnchors[ name ] ) {
							detectedAnchors[ name] = [];
						}
						detectedAnchors[ name ].push(id);
					}
				} else if (
					lastAnchorName &&
					Object.values( validAnchorsIds ).includes( lastAnchorName )
				) {
					// Not a new anchor id, add it to the last anchor.
					detectedAnchors[ lastAnchorName ].push( anchor );
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
			// Ensure id is a valid item. If it's not, clear the anchor.
			if ( Array.isArray( id ) ? id.length > 0 : id !== '' ) {
				soPremiumAnchors[ anchor ] = id;
			} else {
				delete soPremiumAnchors[ anchor ];
			}

			const hash = Object.entries( soPremiumAnchors )
				.map ( ( [ anchor, id ] ) => `${ anchor }-${ id }` )
				.join( ',' );

			window.location.hash = hash;
		},

		// To prevent a jump on load, certain widgets will need to disable scrollto temporarily.
		temporarilyDisableScrollTo: function( setting ) {
			let scrollToSetting = setting.scrollto_after_change;
			delete setting.scrollto_after_change;
			setTimeout( function() {
				setting.scrollto_after_change = scrollToSetting;
			}, 500 );
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

					$( 'body, html' ).animate( {
						scrollTop: parent.offset().top - navOffset,
					}, 200 );
				}
			}
		},
	} );
	soPremium.anchorIds().init();
} );

window.SiteOriginPremium = soPremium;
