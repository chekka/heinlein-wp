/* globals jQuery, sowb */
var sowb = window.sowb || {};

jQuery( function( $ ) {
	let $carouselsWithAnchor = $( '.sow-carousel-wrapper[data-anchor-id]' );

	// Set the initial slide.
	$( sowb ).on( 'carousel_setup', function() {
		$carouselsWithAnchor.each( async function() {
			let slide = await soPremium.anchorIds().getAnchor( $( this ).data( 'anchor-id' ) );
			if ( slide ) {
				$( this ).find( '.sow-carousel-items' ).slick( 'slickGoTo', slide );
			}
		} );
	} );

	// Handle external hash changes.
	$carouselsWithAnchor.on( 'anchor_id_hash_change', function( event, anchor ) {
		$( this ).find( '.sow-carousel-items' ).slick( 'slickGoTo', slide );
	});

	// Update the anchor when the slide changes.
	$( '.sow-carousel-wrapper[data-anchor-id] .sow-carousel-items' ).on( 'afterChange', function( e, slick, currentSlide ) {
		soPremium.anchorIds().update( $( this ).parent().data( 'anchor-id' ), currentSlide );
	} );
} );

window.sowb = sowb;
