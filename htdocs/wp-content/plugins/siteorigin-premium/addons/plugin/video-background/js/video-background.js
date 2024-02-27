/* globals jQuery, FitVids */

( function( $ ) {
	var soVideoBackgrounds = $( '.so-premium-video-background' );
	if ( typeof $.fn.fitVids == 'function' ) {
		soVideoBackgrounds.fitVids();
		var sizeVideoBackgrounds = function() {
			soVideoBackgrounds.each( function() {
				var $video = $( this ).find( 'video, iframe' ),
					$parent = $( this ).parent().parent().parent();

				$parent.css( 'position', 'relative' );
				$video.css( 'max-height', $parent.outerHeight() + 'px' );
			} );
		};

		$( window ).on( 'load resize panelsStretchRows', sizeVideoBackgrounds );
	}
} )( jQuery );
