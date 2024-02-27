/* globals jQuery, soMirrorWidgetAdmin */

jQuery( function( $ ) {
	$( '.edit-slug' ).on( 'click', function( e ) {
		if ( ! confirm( soMirrorWidgetAdmin.confirm_edit_post_type ) ) {
			setTimeout( function() {
				$( '#edit-slug-buttons' ).find( '.cancel' ).trigger( 'click' )
			}, 100 );
		}
	} );
} );
