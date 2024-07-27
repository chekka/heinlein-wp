/* globals jQuery, sowb */

var sowb = window.sowb || {};

jQuery( function( $ ) {
	const $slidersWithAnchor = $( '.sow-slider-images[data-anchor-id]' );
	let preventAnchorUpdate;

	// Set the initial slide.
	const SliderScrollOnInit = function() {
		const $slider = $( this );
		const anchorId = $slider.data( 'anchor-id' );

		preventAnchorUpdate = true;
		setTimeout( function() {
			preventAnchorUpdate = false;
		}, 200 );

		setTimeout( async function() {
			const slide = await soPremium.anchorIds().getAnchor( anchorId );
			if ( slide ) {
				$slider.cycle( 'goto', slide );
			}
		}, 100 );
	}

	// Handle external hash changes.
	$slidersWithAnchor.on( 'anchor_id_hash_change', function( event, slide ) {
		preventAnchorUpdate = true;
		setTimeout( function() {
			preventAnchorUpdate = false;
		}, 200 );

		setTimeout( function() {
			$( this ).cycle( 'goto', slide );
		}, 100 );
	} );

	$slidersWithAnchor.each( function() {
		let $$ = $( this );
		let anchor = $$.data( 'anchor-id' );

		$$.on( 'cycle-post-initialize', SliderScrollOnInit );

		// Update the anchor when the slide changes.
		$$.on( 'cycle-after', function( event, optionHash, outgoingSlideEl, incomingSlideEl, forwardFlag ) {
			if ( preventAnchorUpdate ) {
				return;
			}
			soPremium.anchorIds().update( anchor, optionHash.nextSlide );
		} );
	} );
} );

window.sowb = sowb;
