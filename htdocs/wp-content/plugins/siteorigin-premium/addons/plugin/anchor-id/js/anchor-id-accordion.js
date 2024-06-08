/* globals jQuery, sowb */
var sowb = window.sowb || {};

jQuery( function( $ ) {
	const $accordionPanelsWithAnchor = $( '.so-widget-sow-accordion[data-anchor-id], .so-widget-sow-accordion:not([data-anchor-id]):has(.sow-accordion-panel[data-anchor-id])' );

	let initialSetup = false;

	const openPanelsIfAnchor = async function() {
		const $$ = $( this );
		const hasEmptyAnchorId = $$.data( 'anchor-id' ) === undefined;
		let anchors = [];

		if ( hasEmptyAnchorId ) {
			// Find all the panels with anchor ids.
			const panels = $$.find( '.sow-accordion-panel[data-anchor-id]' ).toArray();

			for ( let panel of panels ) {
				const currentAnchor = $( panel ).data( 'anchor-id' );
				const anchor = await soPremium.anchorIds().getAnchor( currentAnchor );
				anchors.push(anchor);
			}
		} else {
			const anchorId = $$.data( 'anchor-id' );
			anchors = await soPremium.anchorIds().getAnchor( anchorId );
		}

		if ( ! anchors ) {
			return;
		}

		soPremium.anchorIds().temporarilyDisableScrollTo( sowAccordion );
		initialSetup = true;
		setTimeout( function() {
			initialSetup = false;
		}, 200 );

		for ( let anchor of anchors ) {
			// We have to be very specific here as it's possible for Accordions to be nested.
			$( '.sow-accordion-panel[data-anchor-id="' + anchor + '"] > .sow-accordion-panel-header-container > .sow-accordion-panel-header' ).trigger( 'click' );
		}
	}

	// On load, open any panels that are in the hash.
	$accordionPanelsWithAnchor.each( openPanelsIfAnchor );

	// Handle external hash changes.
	$accordionPanelsWithAnchor.on( 'anchor_id_hash_change', function( event, anchor ) {
		$( this ).find( '.sow-accordion-panel[data-anchor-id="' + anchor + '"] > .sow-accordion-panel-header-container > .sow-accordion-panel-header' ).trigger( 'click' );
	} );

	$accordionPanelsWithAnchor.on( 'accordion_open accordion_close', function( e, panel, $widget ) {

		if (initialSetup) {
			return false;
		}


		const hasEmptyAnchorId = $widget.data( 'anchor-id' ) === undefined;
		// The current Accordion doesn't have an anchor id set.
		// We need to handle it slightly different.
		if ( hasEmptyAnchorId ) {
			const $panel = $( panel );
			const anchorId = $panel.data( 'anchor-id' );

			soPremium.anchorIds().update(
				anchorId,
				anchorId,
			);

			return;
		}

		const anchorId = $widget.data( 'anchor-id' );
		const allOpenPanels = $widget.find( '.sow-accordion-panel-open' ).toArray();
		const anchors = allOpenPanels.map( function( panel ) {
			return $( panel ).data( 'anchor-id' );
		} );

		soPremium.anchorIds().update(
			anchorId,
			anchors
		);
	} );
} );

window.sowb = sowb;
