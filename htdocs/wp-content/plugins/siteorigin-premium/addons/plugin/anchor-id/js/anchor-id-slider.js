/* globals jQuery, sowb */

var sowb = window.sowb || {};

jQuery( function( $ ) {
	// Set the initial slide.
	$( '.sow-slider-images[data-anchor-id]' ).on( 'slider_setup_after cycle-initialized', function() {
		const anchorId = $( this ).data( 'anchor-id' );
		setTimeout( async function() {
			const slide = await soPremium.anchorIds().getAnchor( anchorId );
			if ( slide ) {
				$( this ).cycle( 'goto', slide );
			}
		}, 100 );
	} );

	// Update the anchor when the slide changes.
	$( '.sow-slider-images[data-anchor-id]' ).each( function() {
		let $$ = $( this );
		let anchor = $$.data( 'anchor-id' );

		$$.on( 'cycle-after', function( event, optionHash, outgoingSlideEl, incomingSlideEl, forwardFlag ) {
			soPremium.anchorIds().update( anchor, optionHash.nextSlide );
		} );
	} );
} );

window.sowb = sowb;
