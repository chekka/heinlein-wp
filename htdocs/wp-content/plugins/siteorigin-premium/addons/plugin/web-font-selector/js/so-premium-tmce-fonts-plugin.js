var SOPremiumFontSelectorPlugin = function( editor, url = null ) {
	var $ = jQuery;
	var FONT_FORMAT = 'so_premium_font_format';
	var fontModules = soPremiumFonts.font_modules;
	var $fontSelect;

	function addCommands() {
		editor.addCommand( 'soPremiumApplyFont', function ( format, value ) {
			var ruleVal = value.font ? "'" + value.font + "'" : '';
			ruleVal += value.category ? ", " + value.category : '';
			var fontWeight = 'regular';
			var fontStyle = 'normal';
			if ( value.variant ) {
				var variantParts = value.variant.match( /(\d*)(\w*)/ );
				// Process variant parts.
				for (var i = 1; i < variantParts.length; i++) {
					if ( isNaN( variantParts[ i ] ) ) {
						fontStyle = 'italic';
					} else if ( variantParts[ i ] !== '' ) {
						fontWeight = variantParts[ i ];
					}
				}
			}

			editor.formatter.apply( format, {
				fontFamily: ruleVal,
				fontWeight: fontWeight,
				fontStyle: fontStyle,
				fontInfo: JSON.stringify( value ),
				fontModule: value.module
			} );
			importExistingFonts();
		} );

		editor.addCommand( 'soPremiumRemoveFont', function ( format ) {
			editor.formatter.remove( format, { fontFamily: null, fontModule: null }, null );
		} );
	}

	function importExistingFonts() {
		new SOPremiumFontsImporter(editor.contentDocument).importFonts();
	}

	function initChosen() {
		// Have to use `$.attr` instead of `$.data` for Chosen to find it.
		$fontSelect.attr( 'data-placeholder', soPremiumFonts.placeholder_text );
		$fontSelect.webfontselector( {
			modules: fontModules,
			useVariants: true,
			showInherit: false,
		} );

		var $chosenContainer = $fontSelect.siblings( '.chosen-container' );
		$chosenContainer.addClass( 'mce-so-premium-font-selector' );

		// This is only really necessary for the classic block in the block editor. It forces focus on the text after
		// clicking anywhere on the toolbar, including the Chosen search input. So there's a bit of a tug-of-war going
		// on to get focus in the right place. :/
		var $chosenSearchInput = $chosenContainer.find( '.chosen-search input' );
		var timeoutId;
		$chosenSearchInput.on( 'mouseup', function () {
			if ( ! timeoutId ) {
				timeoutId = setTimeout(
					function () {
						$chosenSearchInput.trigger( 'focus' );
						timeoutId = null;
					},
					100
				);
			}
		} );


		$fontSelect.on( 'font_change', function ( event, value, oldValue ) {

			if ( value && value.hasOwnProperty( 'font' ) && value.font ) {
				editor.execCommand( 'soPremiumApplyFont', FONT_FORMAT, value );
			} else {
				editor.execCommand( 'soPremiumRemoveFont', FONT_FORMAT );
			}
		} );
	}

	function addButtons() {
		editor.addButton( 'so-premium-font-selector', {
			type: 'selectbox',
			icon: false,
			onPostRender: function ( event ) {
				$fontSelect = $( event.control.$el );
				if ( $fontSelect.is( ':visible' ) ) {
					initChosen();
				} else {
					var intervalId = setInterval(
						function () {
							if ( $fontSelect.is( ':visible' ) ) {
								initChosen();
								clearInterval( intervalId );
								intervalId = null;
							}
						},
						500
					);
				}
			},
		} );
	}

	function onNodeChange( element ) {
		if ( editor.formatter && $fontSelect ) {
			var node = element.element;
			var fontStyle = '';
			var fontVariant = '';
			if ( node.hasAttribute( 'data-font-info' ) ) {
				var fontInfo = JSON.parse( node.getAttribute( 'data-font-info' ) );
				fontStyle = fontInfo.font;
				fontVariant = fontInfo.hasOwnProperty( 'variant' ) ? fontInfo.variant : '';
			}
			$fontSelect.webfontselector( 'update', fontStyle, fontVariant );
		}
	}

	function registerFormat() {
		editor.formatter.register( FONT_FORMAT, {
			inline: 'span',
			classes: 'so-premium-web-font',
			attributes: {
				'data-web-font-module': '%fontModule',
				'data-font-info': '%fontInfo',
			 },
			styles: {
				'font-family': '%fontFamily',
				'font-weight': '%fontWeight',
				'font-style': '%fontStyle'
			},
			remove: 'all',
		} );
	}

	function onEditorInit() {
		registerFormat();
		importExistingFonts();
	}

	editor.on( 'init', onEditorInit );
	editor.on( 'NodeChange', onNodeChange );
	addCommands();
	addButtons();

};
( function( $ ) {
	$( document ).on( 'load tinymce-editor-setup', function( e, editor ) {
		if ( window.tinymce ) {
			// Is this a text widget, or a regular TinyMCE instance?
			if ( typeof editor.settings.external_plugins['so-premium-font-selector'] == 'undefined' ) {
				if ( typeof editor.settings.toolbar1 != 'undefined' && editor.settings.toolbar1.indexOf( 'so-premium' ) == -1 ) {
					editor.settings.toolbar1 += ',so-premium-font-selector';
				}
				new SOPremiumFontSelectorPlugin( editor );
			} else {
				window.tinymce.PluginManager.add( 'so-premium-font-selector', function ( editor, url ) {
					new SOPremiumFontSelectorPlugin( editor, url );
				} );
			}
		}
	} );

} )( window.jQuery );
