/* globals jQuery, pikaday */

( function( $ ) {
	const soPremiumToggleVisibilityAddonSetupPicker = ( field ) => {
		field.find( 'input[type="text"]' ).pikaday( {
			isRTL: soPremiumToggleVisibilityAddon.isRTL,
			i18n: soPremiumToggleVisibilityAddon.i18n,
		} );
	}

	// Page Builder Style fields.
	$( document ).on( 'setup_style_fields', function( e, view ) {
		const prefix = typeof siteoriginPremiumToggleUseToggle !== 'undefined' ? 'toggle_scheduling_' : '';

		const date_from = view.$el.find( '.so-field-' + prefix + 'toggle_date_from' );
		const date_to = view.$el.find( '.so-field-' + prefix + 'toggle_date_to' );
		const date_display = view.$el.find( '.so-field-' + prefix + 'toggle_display' );

		const dates = [ date_from, date_to, date_display ];
		const fields = view.$el.find( '.so-field-toggle_scheduling input[type="checkbox"]' );

		fields.on( 'change', function() {
			const checked = $( this ).is( ':checked' );
			dates.forEach( date => checked ? date.show() : date.hide());
			}
		).trigger( 'change' );

		soPremiumToggleVisibilityAddonSetupPicker( date_from );
		soPremiumToggleVisibilityAddonSetupPicker( date_to );
	} );

	// Metabox.
	$( document ).on( 'sowsetupformfield', '.siteorigin-widget-field-toggle_visibility', function( e ) {
		const $field = $( this );

		if ( $field.data( 'initialized' ) ) {
			return;
		}

		const date_to = $field.find( '.siteorigin-widget-field-toggle_date_to' );
		const date_from = $field.find( '.siteorigin-widget-field-toggle_date_from' );

		soPremiumToggleVisibilityAddonSetupPicker(
			date_to
		);
		soPremiumToggleVisibilityAddonSetupPicker(
			date_from
		);

		// Resize fields more appropriately.
		date_to.find( '.siteorigin-widget-input' )
			.removeClass( 'widefat' )
			.css( 'width', 'auto' );

		date_from.find( '.siteorigin-widget-input' )
			.removeClass( 'widefat' )
			.css( 'width', 'auto' );


		$field.data( 'initialized', true );
	} );
} )( jQuery );
