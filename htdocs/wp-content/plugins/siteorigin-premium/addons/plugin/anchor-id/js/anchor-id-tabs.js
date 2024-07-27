/* globals jQuery, sowb */
var sowb = window.sowb || {};

jQuery( function( $ ) {

	let initialSetup;
	let preventAnchorUpdate;

	const $tabWithAnchor = $( '.so-widget-sow-tabs[data-anchor-id], .so-widget-sow-tabs:not([data-anchor-id]):has(.sow-tabs-tab[data-anchor-id])' );

	// On load, open any tabs that are in the hash.
	$tabWithAnchor.each( async function() {
		const $$ = $( this );
		const hasEmptyAnchorId = $$.data( 'anchor-id' ) === undefined;

		if ( hasEmptyAnchorId ) {
			// Find all the panels with anchor ids.
			const tabs = $$.find( '.sow-tabs-tab[data-anchor-id]' );

			for ( let tab of tabs ) {
				const $currentTab = $( tab );
				const currentAnchor = $currentTab.data( 'anchor-id' );
				const anchor = await soPremium.anchorIds().getAnchor( currentAnchor );

				// If the anchor is found, stop processing, and active it.
				if (anchor) {
					$currentTab.addClass( 'so-anchor-id-active' );
					soPremium.anchorIds().temporarilyDisableScrollTo( sowTabs );
					initialSetup = true;
					$currentTab.trigger( 'click' );
					setTimeout( function() {
						initialSetup = false;
					}, 200 );
					break;
				}
			}
			return;
		}

		const anchorId = $$.data( 'anchor-id' ) ?? $$.find( '.sow-tabs-tab[data-anchor-id]' ).data( 'anchor-id' );
		const anchor = await soPremium.anchorIds().getAnchor( anchorId );

		if ( ! anchor ) {
			return;
		}

		soPremium.anchorIds().temporarilyDisableScrollTo( sowTabs );
		preventAnchorUpdate = true;
		setTimeout( function() {
			preventAnchorUpdate = false;
		}, 100 );

		$$.find( '> .sow-tabs > .sow-tabs-tab-container .sow-tabs-tab[data-anchor-id="' + anchor + '"]' ).trigger( 'click' );
	} );

	// Handle external hash changes.
	$tabWithAnchor.on( 'anchor_id_hash_change', function( event, anchor ) {
		preventAnchorUpdate = true;
		setTimeout( function() {
			preventAnchorUpdate = false;
		}, 100 );

		$( this ).find( '> .sow-tabs > .sow-tabs-tab-container .sow-tabs-tab[data-anchor-id="' + anchor + '"]' ).trigger( 'click' );
	} );

	$tabWithAnchor.on( 'tab_change', function( e, $tab, $widget ) {
		if ( initialSetup || preventAnchorUpdate) {
			return;
		}

		const tabAnchorId = $tab.data( 'anchor-id' );
		const hasEmptyAnchorId = $widget.data( 'anchor-id' ) === undefined;

		// If the current Tabs widget doesn't have an anchor id set.
		// We need to handle it slightly different.
		if ( hasEmptyAnchorId ) {
			// If there's already an active tab in this widget, we need to remove it.
			const activeTab = $tab.siblings( '.so-anchor-id-active' );
			if ( activeTab.length ) {
				activeTab.removeClass( 'so-anchor-id-active' );
				const activeTabAnchorId = activeTab.data( 'anchor-id' );
				soPremium.anchorIds().update(
					activeTabAnchorId,
					activeTabAnchorId
				);
			}

			$tab.addClass( 'so-anchor-id-active' );
			soPremium.anchorIds().update(
				tabAnchorId,
				tabAnchorId
			);
			return;
		}

		const tabsAnchorId = $widget.data( 'anchor-id' );
		soPremium.anchorIds().update( tabsAnchorId, tabAnchorId );
	} );
} );

window.sowb = sowb;
