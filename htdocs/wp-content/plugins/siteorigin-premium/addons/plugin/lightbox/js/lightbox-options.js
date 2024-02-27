/* globals jQuery, lightbox, SiteOriginPremium */

window.SiteOriginPremium = window.SiteOriginPremium || {};

SiteOriginPremium.setupLightbox = function ( $ ) {
	$( 'a[data-lightbox]' ).on( 'click', function () {
		// Set options just before lightbox is opened to ensure instance specific settings are applied.
		var instanceOptions = $( this ).data( 'lightboxOptions' );
		lightbox.option( instanceOptions );
		var $overlay = $( '#lightboxOverlay' );
		$overlay.css( 'background-color', instanceOptions.overlayColor );
		$overlay.css( 'opacity', instanceOptions.overlayOpacity );
	} );

	// Prevent situation where Anything Carousel Widget can result in
	// duplicated items due to how Slick handles infinite scrolling.
	$( '.sow-carousel-item.slick-slide.slick-cloned a[data-lightbox]' ).removeAttr( 'data-lightbox' )
};

jQuery( function( $ ) {
	SiteOriginPremium.setupLightbox( $ );

	if ( window.sowb ) {
		$( window.sowb ).on( 'setup_widgets', function() {
			SiteOriginPremium.setupLightbox( $ );
		} );
	}
} );
