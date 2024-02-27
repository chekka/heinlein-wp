/* globals jQuery, sowb */

var sowb = window.sowb || {};

jQuery( function( $ ) {
	var anchors;
	var getAnchors = function() {
		detectedAnchors = {},
			possibleAnchors = [];
		if ( ! window.location.hash ) {
			return {};
		}

		// Are there multiple anchors set?
		if ( window.location.hash.indexOf( ',' ) > -1 ) {
			possibleAnchors = window.location.hash.split( ',' );
		} else {
			possibleAnchors.push( window.location.hash );
		}

		for ( var i = 0; i < possibleAnchors.length; i++ ) {
			detectedAnchors[ possibleAnchors[ i ].replace( /[0-9#]/g, '' ) ] = possibleAnchors[ i ].replace( /[^0-9]/g, '' );
		}

		return detectedAnchors;
	};

	// Ensure Carousels displaying the correct items.
	$( sowb ).on( 'carousel_setup', function( e ) {
		anchors = getAnchors();
		if ( ! $.isEmptyObject( anchors ) ) {
			for ( var anchor in anchors ) {
				// The slice is to remove  the hyphen at the end of the string.
				actualAnchor = anchor.indexOf( '-' ) >= 0 ? anchor.slice( 0, -1 ) : anchor;

				var $anchor = $( '.sow-carousel-wrapper[data-anchor="' + actualAnchor +'"]' );
				if ( $anchor.length ) {
					$anchor.find( '.sow-carousel-items' ).slick( 'slickGoTo', anchors[ actualAnchor ] );
					// If this is the first carousel that's been adjusted, scroll to it.
					if ( typeof sowb.scrolled == 'undefined' ) {
						var navOffset = soPremiumAnchorId.scrollto_offset ? soPremiumAnchorId.scrollto_offset : 90;
						$( 'body, html' ).animate( {
							scrollTop: $anchor.parent().parent().offset().top - navOffset,
						}, 200 );
						sowb.scrolled = true;
					}
				}
			}
		}
	} );

	// Update Anchor ID in URL after changing item.
	$( '.so-widget-sow-anything-carousel .sow-carousel-items.slick-initialized' ).on( 'afterChange', function( e, slick, currentSlide ) {
		var carousel = $( slick.$slider ).parent();
		// If this carousel doesn't have an achor set, don't proceed.
		if ( ! carousel.data( 'anchor' ) ) {
			return;
		}

		anchors = getAnchors();
		if ( $.isEmptyObject( anchors ) ) {
			window.location.hash = carousel.data( 'anchor' ) + '-' + currentSlide;
		} else {
			newAnchors = anchors;

			// Remove the current anchor if the user navigated back to the first item.
			if ( currentSlide == 0 ) {
				delete newAnchors[ carousel.data( 'anchor' ) + '-' ];
			} else {
				newAnchors[ carousel.data( 'anchor' ) + '-' ] = currentSlide;
			}

			if ( $.isEmptyObject( newAnchors ) ) {
				window.history.pushState( '', document.title, window.location.pathname + window.location.search );
			} else if ( newAnchors != anchors ) {
				var hash = '';
				for ( var anchor in newAnchors ) {
					hash += ( hash != '' ? ',' : '' ) + anchor + newAnchors[ anchor ];
				}
				window.location.hash = hash;
			}
		}
	} );
} );

window.sowb = sowb;
