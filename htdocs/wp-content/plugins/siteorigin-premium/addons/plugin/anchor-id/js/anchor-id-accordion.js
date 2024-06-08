/* globals jQuery, sowb */
var sowb = window.sowb || {};

jQuery( function( $ ) {
	const $accordionPanelsWithAnchor = $( '.so-widget-sow-accordion[data-anchor-id]' );

	const openPanelsIfAnchor = async function() {
		const $$ = $( this );
		const parentAnchorId = $$.data( 'anchor-id' );
		let anchors = await soPremium.anchorIds().getAnchor( parentAnchorId );

		if ( ! anchors ) {
			return;
		}

		soPremium.anchorIds().temporarilyDisableScrollTo( sowAccordion );

		for ( let anchor of anchors ) {
			// We have to be very specific here as it's possible for Accordions to be nested.
			$( '.sow-accordion-panel[data-anchor-id="' + anchor + '"] > .sow-accordion-panel-header-container > .sow-accordion-panel-header' ).trigger( 'click' );
		}
	}

	// On load, open any panels that are in the hash.
	$accordionPanelsWithAnchor.each( openPanelsIfAnchor );

	$accordionPanelsWithAnchor.on( 'accordion_open accordion_close', function( e, panel, $widget ) {
		const anchorId = $widget.data( 'anchor-id' );
		const allOpenPanels = $widget.find( '.sow-accordion-panel-open' ).toArray();
		const anchors = allOpenPanels.map( function( panel ) {
			return $( panel ).data( 'anchor-id' );
		} );

		soPremium.anchorIds().update( anchorId, anchors );
	} );
} );

window.sowb = sowb;
