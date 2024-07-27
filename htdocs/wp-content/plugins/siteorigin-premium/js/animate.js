/**
 * SiteOrigin specific animation code
 * Copyright SiteOrigin 2016
 */
window.SiteOriginPremium = window.SiteOriginPremium || {};

SiteOriginPremium.setupAnimations = function ( $ ) {

	const animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';

	const animationWatcher = function( animation, threshold = 0 ) {
		const elementHeight = animation.$el.outerHeight();

		// If the elementHeight is 0. It's likely that the element isn't
		// visible yet, or hasn't finished setting up. Let's try again shortly.
		if ( elementHeight === 0 ) {
			setTimeout( function() {
				animationWatcher( animation, threshold );
			}, 100 );
			return;
		}

		const element = document.querySelector( animation.selector );
		const offset = parseInt( threshold, 10);

		// Calculate the threshold as a ratio. This prevents a possible bug where the threshold is outside of the range [0, 1].
		const thresholdRatio = Math.min( Math.max( offset / elementHeight, 0 ), 1 );
		var observer = new IntersectionObserver( function( [ entry ], observer ) {
			if ( entry.isIntersecting ) {
				animateIn( animation, false );
				observer.unobserve( element );
			}
		}, { threshold: thresholdRatio } );
		observer.observe( element );
	}

	const animateIn = function ( animation, repeat ) {
		if ( animation.disableAnimationMobile && window.matchMedia( '(max-width: ' + animation.breakpoint + ')' ).matches ) {
			if ( animation.hide ) {
				animation.$el.css( 'opacity', 1 );
			}
			animation.$el.addClass( 'animate__animated' );
			return;
		}

		var doAnimation = function () {
			if ( animation.hide ) {
				animation.$el.css( 'opacity', 1 );
			}

			if ( repeat ) {
				animation.$el
				.removeClass( 'animate__animated animate__' + animation.animation )
				.addClass( 'animate__animated animate__' + animation.animation );
			} else {
				animation.$el.addClass( 'animate__animated animate__' + animation.animation );
			}
			animation.$el.one( animationEnd, function () {
				animation.$el.removeClass( 'animate__animated animate__' + animation.animation );
				if ( animation.finalState === 'hidden' ) {
					animation.$el.css( 'opacity', 0 );
				} else if ( animation.finalState === 'removed' ) {
					animation.$el.css( 'display', 'none' );
				}
			} )
		};

		var delay = parseFloat( animation.delay );
		if ( !isNaN( delay ) && delay > 0 ) {
			setTimeout( function () {
				doAnimation();
			}, delay * 1000 );
		} else {
			doAnimation();
		}
	};

	$( '[data-so-animation]' ).each( function () {
		var $$ = $( this );
		var animation = $$.data( 'so-animation' );
		animation.$el = $$;

		// Set the animation duration
		var duration = parseFloat( animation.duration );
		if ( !isNaN( duration ) ) {
			$$.css( {
				'-webkit-animation-duration': duration + 's',
				'animation-duration': duration + 's',
			} );
		}

		// Using 0 for debounce causes it to default to 100ms. :/
		var debounce = animation.debounce * 1000 || 1;
		// Only perform animation once for now. Will add option to repeat later.
		if ( animation.animation ) {
			switch ( animation.event ) {
				case 'enter':
					animationWatcher(
						animation,
						animation.offset
					);
					break;

				case 'in':
					animationWatcher(
						animation,
						parseInt( animation.offset ) + $$.outerHeight()
					);
					break;

				case 'hover':

					if ( animation.repeat ) {
						$$.on( 'mouseenter', function () {
							animateIn( animation, true );
							$$.addClass( 'animate__infinite' )
						} )
						.on( 'mouseleave', function () {
							$$.removeClass( 'animate__infinite' )
						} );
					} else {
						$$.on( 'mouseenter', function () {
							animateIn( animation, true );
						} );
					}
					break;

				case 'slide_display':
					var $slide = $$.closest( '.sow-slider-image' );

					if ( $slide.hasClass( 'cycle-slide' ) && $slide.index() === 0 ) {
						// Slider has already been initialized, trigger the animation.
						animateIn( animation, true );
					}

					$slide.on( 'sowSlideCycleAfter sowSlideInitial', function ( e ) {
						animateIn( animation, true );
					} );

					// Don't hide animation if slide has slide out animation
					if ( animation.hide && ! animation.animation_type_slide_out ) {
						$slide.on( 'sowSlideCycleBefore', function ( e ) {
							$$.css( 'opacity', 0 );
						} );
					}

					break;

				case 'load':
					animateIn( animation, false );
					break;
			}
		}

		if ( animation.animation_type_slide_out ) {
			$$.closest( '.sow-slider-images' ).on( 'cycle-before', function ( e ) {
				if ( animation.animation_type_slide_out ) {
					if ( $$.closest( '.sow-slider-image ' ).hasClass( 'cycle-slide-active' ) ) {
					$$
						.addClass( 'animate__animated animate__' + animation.animation_type_slide_out )
						.one( animationEnd, function () {
							$$.removeClass( 'animate__animated animate__' + animation.animation_type_slide_out );
						} )
					}
				}

				if ( animation.animation && animation.hide ) {
					$$.css( 'opacity', 0 );
				}
			} );

			if ( animation.animation && animation.hide ) {
				$$.closest( '.sow-slider-images' ).one( 'cycle-after', function ( e ) {
					$$.css( 'opacity', 1 );
				} );
			}
		}
	} );
};

jQuery( function ( $ ) {
	SiteOriginPremium.setupAnimations( $ );

	if ( window.sowb ) {
		$( window.sowb ).on( 'setup_widgets', function ( event, data ) {
			if ( data && data.preview ) {
				SiteOriginPremium.setupAnimations( $ );
			}
		} );
	}
} );
