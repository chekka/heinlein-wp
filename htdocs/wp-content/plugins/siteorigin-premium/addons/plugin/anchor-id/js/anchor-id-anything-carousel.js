/* globals jQuery, sowb */
var sowb = window.sowb || {};

jQuery( function( $ ) {
	let $carouselsWithAnchor = $( '.sow-carousel-wrapper[data-anchor-id]' );
	let preventAnchorUpdate;

	// Set the initial slide.
	$( sowb ).on( 'carousel_setup', function() {
		preventAnchorUpdate = true;
		setTimeout( function() {
			preventAnchorUpdate = false;
		}, 200 );

		$carouselsWithAnchor.each( async function() {
			let slide = await soPremium.anchorIds().getAnchor( $( this ).data( 'anchor-id' ) );

			if ( slide ) {
				$( this ).find( '.sow-carousel-items' ).slick( 'slickGoTo', slide );
			}
		} );
	} );

	// Handle external hash changes.
	$carouselsWithAnchor.on( 'anchor_id_hash_change', async function( event, anchor ) {
		preventAnchorUpdate = true;
		setTimeout( function() {
			preventAnchorUpdate = false;
		}, 100 );

		let slide = await soPremium.anchorIds().getAnchor( $( this ).data( 'anchor-id' ) );

		$( this ).find( '.sow-carousel-items' ).slick( 'slickGoTo', slide );
	} );

	// Update the anchor when the slide changes.
	$( '.sow-carousel-wrapper[data-anchor-id] .sow-carousel-items' ).on( 'afterChange', function( e, slick, currentSlide ) {
		if ( preventAnchorUpdate ) {
			return;
		}

		soPremium.anchorIds().update( $( this ).parent().data( 'anchor-id' ), currentSlide );
	} );
} );

window.sowb = sowb;
