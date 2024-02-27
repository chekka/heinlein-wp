/* global jQuery, soWidgets */

jQuery( function( $ ) {
	window.SiteOriginPremium = window.SiteOriginPremium || {};
	// The Copy Paste Addon requires a secure connection.
	$( 'body' ).on( 'siteorigin_addon_status_changed', function( e, addon ) {
		if (
			addon.id == 'plugin/cross-domain-copy-paste' &&
			addon.status == 1 &&
			window.location.protocol == 'http:'
		) {
			alert( soPremiumCrossDomainCopyPaste.https );
			$( '.so-addon[data-id="plugin/cross-domain-copy-paste"] .so-addon-deactivate' ).trigger( 'click' );
		}
	} );

	var hideModal = false;
	SiteOriginPremium.CrossDomainCopyPasteAddon = function() {
		return {
			setupBrowserStorage: function ( resolve, reject ) {
				if ( typeof SiteOriginPremium.CrossDomainCopyPasteAddon.allowed !== 'boolean' ) {
					if ( $( '.siteorigin-premium-cross-domain-copy-paste' ).length == 0 ) {
						$( '.siteorigin-premium-cross-domain-copy-paste-container' ).append(
							'<iframe src="' +
								$( '.siteorigin-premium-cross-domain-copy-paste-container' ).data( 'src' ) +
							'" class="siteorigin-premium-cross-domain-copy-paste" />'
						);
					}

					var consentTimer = setInterval( function() {
						if ( typeof SiteOriginPremium.CrossDomainCopyPasteAddon.allowed === 'boolean' ) {
							clearInterval( consentTimer );

							if ( hideModal ) {
								hideCopyPasteModal();
								hideModal = false;
							}
							resolve();
						}
					}, 200 );
				} else {
					resolve();
				}
			},
			action: function( data ) {
				var addon = this;
				if ( soPremiumCrossDomainCopyPaste.method == 'clipboard' ) {
					navigator.clipboard.writeText( JSON.stringify( data.data ) );
				}

				if (
					(
						soPremiumCrossDomainCopyPaste.method == 'storage' ||
						pagenow == 'siteorigin_page_siteorigin-premium-addons'
					) &&
					typeof localStorage['so_premium_cross_domain_allowed'] != 'undefined'
				) {
					new Promise ( addon.setupBrowserStorage ).then( () => {
						$( '.siteorigin-premium-cross-domain-copy-paste' )[0].contentWindow.postMessage(
							JSON.stringify( data ),
							'*'
						);
					} );
				}
			},
			copy: function ( data ) {
				this.action( { method: 'set', data: data } );
			},

			paste: function() {
				pasteData = this.action( { method: 'get' } );
			}
		}
	};

	window.onmessage = function( response ) {
		// Permission Check.
		if ( response.origin == 'https://clipboard.siteorigin.com' ) {
			// Passing data between the site and SiteOrigin.com.
			var jsonData = JSON.parse( response.data );
			if ( typeof jsonData == 'object' && jsonData != null ) {
				if ( typeof jsonData.close == 'boolean' && typeof jsonData.automatic == 'undefined' ) {
					hideCopyPasteModal();
					hideModal = true;
				}

				if ( typeof jsonData.resize == 'number' ) {
					$( '.siteorigin-premium-cross-domain-copy-paste' ).height( ( Number( jsonData.resize ) + 25 ) + 'px' );
				}

				if ( typeof jsonData.allowed == 'boolean' ) {
					if ( jsonData.allowed ) {
						SiteOriginPremium.CrossDomainCopyPasteAddon.allowed = true;
						localStorage['so_premium_cross_domain_allowed'] = true;
						$( '.so-premium-copy-paste-prompt' ).hide();
					} else if ( jsonData.denied ) {
						// Permission revoked/denied, prevent the iframe from being loaded every page load.
						localStorage['so_premium_cross_domain_allowed'] = false;
						SiteOriginPremium.CrossDomainCopyPasteAddon.allowed = false;
					}
				}

				// Do we need to update the current site clipboard?
				if (
					SiteOriginPremium.CrossDomainCopyPasteAddon.allowed &&
					(
						typeof jsonData.class != 'undefined' || // Widget.
						typeof jsonData.thingType != 'undefined' // Row.
					)
				) {
					localStorage.setItem( 'panels_clipboard_' + userSettings.uid, response.data );
				}
			}
		}
	};

	var showCopyPasteModal = function() {
		$( '.siteorigin-premium-cross-domain-copy-paste-container' ).show();
		$( '.so-premium-copy-paste-prompt' ).addClass( 'disabled' );
		$( window ).one( 'keyup', function( e ) {
			// User presses escape.
			if ( e.which === 27 && $( '.siteorigin-premium-cross-domain-copy-paste' ).is( ':visible' ) ) {
				hideCopyPasteModal();
			}
		} );
	};

	var hideCopyPasteModal = function() {
		$( '.siteorigin-premium-cross-domain-copy-paste-container' ).hide();
		$( '.so-premium-copy-paste-prompt' ).removeClass( 'disabled' );
	};

	var soCopyPasteaddon = SiteOriginPremium.CrossDomainCopyPasteAddon();

	$( document ).on( 'click', '.so-premium-copy-paste-prompt', function() {
		$( this ).addClass( 'disabled' );
		showCopyPasteModal();
		if ( typeof SiteOriginPremium.CrossDomainCopyPasteAddon.allowed !== 'boolean' ) {
			new Promise ( soCopyPasteaddon.setupBrowserStorage );
		}
	} );

	if (
		soPremiumCrossDomainCopyPaste.method == 'storage' &&
		typeof localStorage['so_premium_cross_domain_allowed'] != 'undefined' &&
		JSON.parse( localStorage['so_premium_cross_domain_allowed'] ) === true
	) {
		$( '.so-premium-copy-paste-prompt' ).hide();
		new Promise ( soCopyPasteaddon.setupBrowserStorage ).then( () => {
			// Every 1.5 seconds we look for new paste data.
			setInterval( function() {
				SiteOriginPremium.CrossDomainCopyPasteAddon().paste();
			}, 1500 );
		} );
	}

	// Browser Clipboard.
	if ( soPremiumCrossDomainCopyPaste.method == 'clipboard' ) {
		$( document ).on( 'paste', '.siteorigin-widget-field-copy_paste_data textarea', function() {
			const field = $( this ).parents( '.siteorigin-widget-field-copy_paste_data' );
			// Delay processing the paste due to there sometimes being a delay after larger pastes.
			setTimeout( function() {
				const data = field.find( 'textarea' ).val();
				let message = soPremiumCrossDomainCopyPaste.fail;
				if ( data != '' ) {
					try {
						const jsonData = JSON.parse( data );
						if (
							typeof jsonData.class != 'undefined' || // Widget.
							typeof jsonData.thingType != 'undefined' // Row.
						) {
							localStorage.setItem( 'panels_clipboard_' + userSettings.uid, data );
							message = soPremiumCrossDomainCopyPaste.success;
						} else {
							return false;
						}
					} catch ( e ) {
						// Outputting the issue should help with debugging.
						console.log( e );
						message = soPremiumCrossDomainCopyPaste.fail;
					}
				}
				field.find( '.siteorigin-widget-field-label' ).text( message );
				field.find( 'textarea' ).val( '' );
			}, 150 );
		} );
	}

	$( 'body' ).on( 'siteorigin_addon_settings_loaded', function( e, addon ) {
		if (
			$( addon ).parents( '.so-addon' ).data( 'id' ) == 'plugin/cross-domain-copy-paste' &&
			typeof localStorage['so_premium_cross_domain_allowed'] != 'undefined'
		) {
			$( '.so-premium-copy-paste-prompt' ).hide();
		}
	} );

	// Add Browser Storage to Layout Builder on Widgets page.
	if (
		soPremiumCrossDomainCopyPaste.method == 'clipboard' &&
		pagenow.length > 0 &&
		pagenow == 'widgets'
	) {
		$( document ).on( 'click', '.siteorigin-panels-display-builder', function() {
			const builderModal = $( '.so-panels-dialog-wrapper:last-of-type' );
			if ( builderModal.find( '.siteorigin-premium-copy-page-widgets-fields' ).length == 0 ) {
				builderModal.find( '.so-content.panel-dialog' ).append( $( '.siteorigin-premium-copy-page-widgets' ).html() );
			}
		} );
	}
} );
