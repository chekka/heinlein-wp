/* globals jQuery */

( function( $ ) {
	const soBackgroundVideoExists = ( videoBackground ) => {
		let videoElement = videoBackground.find( '[name="style[video_background]"]' );

		if ( ! videoElement || videoElement.val() == 0) {
			videoElement = videoBackground.find( '.video-fallback' );
		}
		const video = videoElement.val();

		return video && video != 0;
	};

	const soBackgroundVideoVisibility = ( videoBackground ) => {
		if ( soBackgroundVideoExists( videoBackground ) ) {
			$( '.so-field-video_background_opacity' ).show();
			videoBackground.siblings( '.so-field-video_background_opacity' ).show();
      videoBackground.siblings( '.so-field-video_background_play_once' ).show();
			videoBackground.siblings( '.so-field-video_background_display' ).show();
		} else {
			videoBackground.siblings( '.so-field-video_background_opacity' ).hide();
			videoBackground.siblings( '.so-field-video_background_play_once' ).hide();
			videoBackground.siblings( '.so-field-video_background_display' ).hide();
		}
	}

	$( document ).on( 'setup_style_fields', function( e, view ) {
		const videoBackground = view.$el.find( '.so-field-video_background' );
		// Ensure the image is hidden if there isn't a video set.
		videoBackground.find( '.so-video-placeholder img' ).toggleClass(
			'hidden',
			! soBackgroundVideoExists( videoBackground )
		);

		let frame = null;
		videoBackground.find( '.so-video-selector' ).on( 'click', function( e ) {
			e.preventDefault();

			if ( frame === null ) {
				frame = wp.media( {
					title: soVideoBackgroundField.add_media,
					library: {
						type: 'video'
					},
					button: {
						text: soVideoBackgroundField.add_media_done,
						close: true
					}
				} );

				frame.on( 'open', function() {
					const selection = frame.state().get( 'selection' );
					const selected = videoBackground.find( '.so-video-selector input[type="hidden"]' ).val();
					if ( selected ) {
						selection.add( wp.media.attachment( selected ) );
					}
				} );

				frame.on( 'select', function() {
					const attachment = frame.state().get( 'selection' ).first().attributes;
					videoBackground.find( '.so-video-placeholder img' ).show();
					const videoField = videoBackground.find( '.so-video-selector input[type="hidden"]' );
					videoField.val( attachment.id ).trigger( 'change' )

					videoBackground.find( '.remove-video' ).removeClass( 'hidden' );

					if ( videoField.val() > 0 ) {
						videoBackground.find( '.so-video-placeholder img' ).removeClass( 'hidden' );
					}
				} );
			}
			$( this ).next().trigger( 'focus' );

			frame.open();
		} );

		// Handle clicking on remove.
		videoBackground.find( '.remove-video' ).on( 'click', function( e ) {
			e.preventDefault();
			videoBackground.find( '.so-video-placeholder img' ).addClass( 'hidden' );
			videoBackground.find( '.so-video-selector > input' ).val( '' );
			videoBackground.find( '.remove-video' ).addClass( 'hidden' );
		} );

		// Handle the visibility of the Video Background Opacity setting.
		soBackgroundVideoVisibility( videoBackground );
		videoBackground.find( '[name="style[video_background]"], .video-fallback' ).on( 'change', function() {
			soBackgroundVideoVisibility( videoBackground );
		} );

		videoBackground.find( '.remove-video' ).on( 'click', function() {
			soBackgroundVideoVisibility( videoBackground );
		} );
	} );

} )( jQuery );
