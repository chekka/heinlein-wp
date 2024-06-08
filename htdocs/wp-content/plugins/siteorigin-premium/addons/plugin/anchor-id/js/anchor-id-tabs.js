/* globals jQuery, sowb */
var sowb = window.sowb || {};

jQuery( function( $ ) {

	const $tabWithAnchor = $( '.so-widget-sow-tabs[data-anchor-id]' );

	// On load, open any tabs that are in the hash.
	$tabWithAnchor.each( async function() {
		const $$ = $( this );
		const anchorId = $$.data( 'anchor-id' );
		let anchor = await soPremium.anchorIds().getAnchor( anchorId );
		if ( ! anchor ) {
			return;
		}

		soPremium.anchorIds().temporarilyDisableScrollTo( sowTabs );

		$$.find( '> .sow-tabs > .sow-tabs-tab-container .sow-tabs-tab[data-anchor-id="' + anchor + '"]' ).trigger( 'click' );
	} );

	$tabWithAnchor.on( 'tab_change', function( e, $tab, $widget ) {
		const tabsAnchorId = $widget.data( 'anchor-id' );
		const tabAnchorId = $tab.data( 'anchor-id' );

		soPremium.anchorIds().update( tabsAnchorId, tabAnchorId );
	} );
} );

window.sowb = sowb;
