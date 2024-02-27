jQuery( function( $ ) {
	$( '.so-widget-sow-lottie-player lottie-player' ).each( function() {
		var $$ = $( this );

		if ( $$.attr( 'mode' ) != 'normal' ) {
			var mode = $$.attr( 'mode' ),
				animation = $$.attr( 'animation' ),
				actions = {};

			switch ( animation ) {
				case 'scroll':
					actions = {
						visibility: [ 0, 1 ],
						type: 'seek',
						frames: [ 0, parseInt( $$.get( 0 ).getLottie().totalFrames ) ],
					};
					break;
				case 'click':
				case 'toggle':
				case 'hold':
				case 'pauseHold':
					actions = {
						type: animation,
						forceFlag: false
					};
					break;
				case 'visibility':
					actions = {
						type: 'play',
						visibility: [ $$.attr( 'visibility-percentage' ) / 100, 1.0 ]
					};
					break;
				case 'cursor':
					actions = {
						position: { x: [ 0, 1 ], y: [ 0, 1 ] },
						type: 'seek',
						frames: [ 0, parseInt( $$.get( 0 ).getLottie().totalFrames ) ]
					};
					break;
				default:
					actions = {
						type: animation
					};
					break;
			}
			LottieInteractivity.create( {
				player: '#' + $$.attr( 'id' ),
				mode: mode,
				actions: [ actions ]
			} );
		}
	} );
} );
