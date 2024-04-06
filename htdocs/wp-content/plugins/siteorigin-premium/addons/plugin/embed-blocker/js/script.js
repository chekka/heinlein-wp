jQuery( function( $ ) {
	function SiteOriginSanitizeElementData( string ) {
		return string.replace( /[<>"']/g, function( match ) {
			switch ( match ) {
				case '<': return '&lt;';
				case '>': return '&gt;';
				case '"': return '&quot;';
				case "'": return '&apos;';
			}
		} );
	}

	function SiteOriginSanitizePreventPrototypePollution( string ) {
		const pollutionVectors = ['__proto__', 'constructor', 'prototype', '__defineGetter__', '__defineSetter__', '__lookupGetter__', '__lookupSetter__'];
		if ( pollutionVectors.some( vector => string.includes( vector ) ) ) {
			throw new Error( 'Embed blocker: String contains invalid characters' );
		}

		return string;
	}

	const SiteOriginUnblockContent = function( siteSlug ) {
		const validTypes = [ 'blockquote', 'iframe', 'script', 'div' ];

		let blockedContent;
		if ( siteSlug ) {
			blockedContent = $( `[so-embed-blocker][data-site-slug="${ siteSlug }"]` );
		} else {
			blockedContent = $( '[so-embed-blocker]' );
		}

		blockedContent.each( function() {
			const $$ = $( this );
			let type = SiteOriginSanitizePreventPrototypePollution( $$.data( 'type' ) );

			// Remove prompt.
			$$.next().remove();

			// If the type isn't something we understand, skip it.
			if ( ! validTypes.includes( type ) ) {
				return true;
			}

			if ( type == 'script' ) {
				// jQuery won't load scripts added to the DOM, so we need to do this using vanilla JS.
				const script = document.createElement( 'script' );
				script.src = $$.attr( 'src' );
				$$.parent()[0].replaceChild( script, $$[0] );

				return true;
			}
			const element = $( `<${ SiteOriginSanitizeElementData( type ) } />` );
			$.each( $$.prop( 'attributes' ), function() {
				SiteOriginSanitizePreventPrototypePollution( this.name );
				SiteOriginSanitizePreventPrototypePollution( this.value );
				element.attr(
					SiteOriginSanitizeElementData( this.name ),
					SiteOriginSanitizeElementData( this.value )
				);
			} );

			const children = $$.children().clone();
			element.append( children );

			element.show();
			$$.replaceWith( element );
		} );
	}

	// Check if the user has already consented to viewing the embed.
	// This is to allow for page caching plugins to work.
	const cookies = document.cookie.split( ';' );
	for ( let i = 0; i < cookies.length; i++ ) {
		const parts = cookies[ i ].split( '=' );
		const cookieName = parts[0].trim();
		if ( cookieName.startsWith( 'siteorigin-premium-content-blocker-' ) ) {
			const siteSlug = SiteOriginSanitizePreventPrototypePollution(
				cookieName.substring( 'siteorigin-premium-content-blocker-'.length )
			);
			SiteOriginUnblockContent( SiteOriginSanitizeElementData( siteSlug ) );
			break;
		}
	}

	$( '.siteorigin-premium-embed-blocker-button' ).on( 'click', function() {
		// Find the embed details.
		const $embedBlocker = $( this ).closest( '.siteorigin-premium-embed-blocker-message' ).prev();

		if ( $embedBlocker.length === 0 ) {
			console.error( 'Embed blocker: Container not found' );
			return;
		}

		let siteSlug = SiteOriginSanitizePreventPrototypePollution( $embedBlocker.data( 'site-slug' ) );

		if ( ! siteSlug ) {
			console.error( 'Embed blocker: Site slug not found' );
			return;
		}

		// User has consented to viewing the embed.
		const expiration = new Date( Date.now() + 60 * 60 * 24 * 30 );
		document.cookie = `siteorigin-premium-content-blocker-${ SiteOriginSanitizeElementData( siteSlug ) }=true; path=/; domain=${ window.location.hostname }; SameSite=Strict; expires=${ expiration.toUTCString() }`;

		SiteOriginUnblockContent( siteSlug );
	} );
} );
