jQuery( function( $ ) {

	// If the metabox is moved to the sidebar we want to collapse the tabs.
	var maybeShowTabs = function() {
		var $field = $( '#siteorigin_premium_metabox .siteorigin-widget-field-type-tabs' );
		if ( $field ) {
			if ( $field.parent().width() <= 500 ) {
				if ( $field.parent().width() == 0 ) {
					// The parent hasn't rendered completely yet. Try again later.
					setTimeout( maybeShowTabs, 100 );
				} else {
					$field.each( function( i ) {
						var $item = $( '.siteorigin-widget-field-' + $( this ).data( 'id' ) ).find( '> .siteorigin-widget-field-label' );
						$item.addClass( 'sow-tabs-smaller-show' );
						if ( i == 0 ) {
							$item.addClass( 'siteorigin-widget-section-visible' );
						}
					} );
					$field.hide();
				}
			} else {
				$field.each( function() {
					$( '.siteorigin-widget-field-' + $( this ).data( 'id' ) ).find( '> .siteorigin-widget-field-label' ).removeClass( 'sow-tabs-smaller-show' );
				} );
				$field.show();
			}
		}
	};
	$( '#side-sortables, #normal-sortables, #advanced-sortables' ).on( 'sortstop', maybeShowTabs );
	maybeShowTabs();

	$( '#siteorigin_premium_metabox > .postbox-header .handlediv' ).remove();
	$( '.block-editor-page #siteorigin_premium_metabox > .postbox-header' ).css( 'height', '44px' );
	$( '#siteorigin_premium_metabox' ).removeClass( 'closed' );
} );
