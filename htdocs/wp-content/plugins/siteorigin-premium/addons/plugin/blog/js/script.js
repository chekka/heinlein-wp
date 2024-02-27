/* globals jQuery, SiteOriginPremium, sowb */

window.SiteOriginPremium = window.SiteOriginPremium || {};
jQuery( function( $ ) {
	SiteOriginPremium.sowBlogWidget = {
		stickyPreloader: function( $el ) {
			const $posts = $el.find( '.sow-blog-posts' );
			const postsTop = $posts.offset().top;
			const loaderheight = 32;
			const cuttOff = $posts.outerHeight() - loaderheight - window.innerHeight / 2;

			if (
				(
					window.pageYOffset >= postsTop
				) &&
				(
					window.pageYOffset <= postsTop + cuttOff
				)
			) {
				$el.find( '.sow-blog-ajax-loader-icon' ).css( {
					position: 'fixed',
					top: '50%'
				} );
			} else {
				$el.find( '.sow-blog-ajax-loader-icon' ).css( {
					position: 'absolute',
					top: ( window.pageYOffset <= postsTop + cuttOff ? window.innerHeight / 2 : $posts.outerHeight() - loaderheight ) + 'px'
				} );
			}
		},

		loadMorePosts: function( nav, $this = {} ) {
			const { settings, $el, totalPages } = nav;

			if ( $el.data( 'fetching' ) ) {
				return false;
			}
			$el.data( 'fetching', true );

			let requestedPage = 0;
			let paged = parseInt( $el.data( 'paged' ) );

			// Work out what page we're navigating too.
			if ( settings.pagination == 'standard' ) {
				requestedPage = $this.text();
				$el.find( '.sow-post-pagination-standard' ).css( 'opacity', 0.5 );
			} else if ( settings.pagination == 'links' ) {
				if ( $this.hasClass( 'sow-previous' ) ) {
					requestedPage = paged - 1;
				} else {
					requestedPage = paged + 1;
				}

				$el.find( '.sow-post-pagination-links' ).css( 'opacity', 0 );
			} else {
				if ( settings.pagination == 'load' ) {
					$this.css( 'opacity', 0 );
				}
				// Infinite and Load More.
				requestedPage = paged + 1;
			}

			// Update the URL to use current "page" number.
			if (
				settings.pagination_reload == 'ajax' ||
				settings.pagination == 'infinite'
			) {
				if (
					settings.pagination == 'standard' ||
					settings.pagination == 'links'
				) {
					window.history.pushState( '', document.title, $this.attr( 'href' ) );
				} else {
					const $loader = settings.pagination == 'load' ? $this : $el.find( '.sow-blog-infinite' );
					window.history.pushState( '', document.title, $loader.data( 'url' ) + '/?' + $loader.data( 'id' ) + '=' + requestedPage );
				}
			}

			$el.find( '.sow-blog-ajax-loader' ).show();

			SiteOriginPremium.sowBlogWidget.stickyPreloader( $el );
			$( window ).on( 'scroll', function() {
				SiteOriginPremium.sowBlogWidget.stickyPreloader( $el );
			} );

			$el.find( '.sow-blog-posts' ).css( 'opacity', 0.35 );

			$.get(
				sowBlogAddon['ajax-url'],
				{
					action: 'sow_blog_load_posts',
					paged: requestedPage,
					instance_hash: $el.data( 'hash' )
				},
				function ( posts ) {
					// Replace or add posts depending on pagination.
					if (
						settings.pagination == 'standard' ||
						settings.pagination == 'links'
					) {
						$el.find( '.sow-blog-posts' ).html( posts.html );
						// 120 a generic offset to account for sticky menus.
						$( 'html' ).animate( {
							scrollTop: $el.offset().top - 120,
						}, 200 );
					} else {
						$el.find( '.sow-blog-posts' ).append( posts.html );
					}

					$el.data( 'fetching', false );
					$el.data( 'paged', requestedPage );
					$( window ).off(
						'scroll',
						SiteOriginPremium.sowBlogWidget.stickyPreloader
					);

					if ( settings.pagination == 'standard' ) {
						// Remove previous .current and restore link.
						$el.find( '.sow-nav-links a.current' ).removeClass( 'current');

            			const current = $el.find( '.sow-nav-links span.current' );
						current.prev().show();
						current.remove();

						// "replace" current page with span.
						let $current = $el.find( '.page-numbers:not(.dots)' ).eq( requestedPage - 1 );
						$current
						.addClass( 'current' )
						.after( '<span aria-current="page" class="page-numbers current">' + requestedPage + '</span>' )
						.hide();

						$el.find( '.sow-post-pagination-standard' ).css( 'opacity', 1 );
					} else if ( settings.pagination == 'links' ) {
						const pagingId = $el.data( 'paging-id' );
						const previous = $el.find( '.sow-previous' );
						const next = $el.find( '.sow-next' );

						$el.find( '.sow-post-pagination-links' ).css( 'opacity', 1 );
						// Hide/show the link depending upon what page we're on.
						if ( requestedPage < 2 ) {
							previous.hide();
						}

						if ( paged == 1 ) {
							previous.show();
						}

						if ( requestedPage == totalPages ) {
							next.hide();
						} else {
							next.show();

							// Update pagination links to reflect the page we're on.
							if ( pagingId ) {
								previous.attr(
									'href',
									previous.attr( 'href' ).replace(
										pagingId + '=' + ( paged - 1 ),
										pagingId + '=' + ( requestedPage - 1 )
									)
								);

								next.attr( 'href',
									next.attr( 'href' ).replace(
										pagingId + '=' + ( paged + 1 ),
										pagingId + '=' + ( requestedPage + 1 )
									)
								);
							}
						}
					} else if ( settings.pagination == 'load' ) {
						if ( requestedPage == totalPages ) {
							$this.remove();
						} else {
							$this.css( 'opacity', 1 );
						}
					} else if ( requestedPage == totalPages ) { // Infinite
						// No more posts to load, remove infinite loading notice.
						$el.find( '.sow-blog-infinite' ).remove();
					}

					if ( $el.hasClass('sow-blog-layout-masonry' ) ) {
						$el.find( '.sow-blog-posts' ).masonry( 'reloadItems' ).masonry();
					} else if ( $el.hasClass( 'sow-blog-layout-portfolio' ) ) {
						$el.find( '.sow-blog-posts' ).isotope( 'reloadItems' ).isotope();
					}

					$el.find( '.sow-blog-ajax-loader' ).hide();
					$el.find( '.sow-blog-posts' ).css( 'opacity', 1 );
				}
			);
		},

		infiniteScroller: function( nav ) {
			const { $el, totalPages } = nav;
			if ( totalPages == $el.data( 'paged' ) ) {
				// No more posts to load, abort.
				return;
			}

			let currentScrollPos = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
			let BlogBottomPos = $el.offset().top + $el.height();

			// Calculate when we need to fetch posts.
			// If the Blog Widgets bottom position is larger than two screen lengths, offset the bottom pos using that.
			if ( BlogBottomPos > window.innerHeight * 2 ) {
				BlogBottomPos -= window.innerHeight * 2;
			} else {
				// Otherwise, fetch posts when the user scrolls to the third last post.
				let posts = $el.find( '.sow-blog-posts > article' );
				BlogBottomPos = posts.eq( posts.length - 3 ).offset().top;
			}

			if ( currentScrollPos > BlogBottomPos ) {
				SiteOriginPremium.sowBlogWidget.loadMorePosts( nav );
			}
		},

		setupAnimation: function( nav ) {
			const { settings, $el } = nav;
			const duration = parseFloat( settings.animation.duration );

			setTimeout( function () {
				const onScreen = new OnScreen( {
					tolerance: parseInt( settings.animation.offset ),
					// Using 0 for debounce causes it to default to 100ms. :/
					debounce: settings.animation.debounce * 1000 || 1,
				} );

				const delay = parseFloat( settings.animation.delay );
				const doAnimation = function ( itemClass, settings ) {
					const $this = $( itemClass );
					if ( settings.animation.animation_hide ) {
						$this.css( 'opacity', 1 );
					}

					$this.addClass( 'animate__animated animate__' + settings.animation.type );
					$this.one( 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
						$this.removeClass( 'animate__animated animate__' + settings.animation.type );
					} )
				};

				const $items = $el.find( 'article' );
				$items.each( function() {
					const $item = $( this );

					if (
						settings.animation.disable_mobile &&
						window.matchMedia( '(max-width: ' + settings.animation.breakpoint + ')' ).matches
					) {
						if ( settings.animation.animation_hide ) {
							$item.css( 'opacity', 1 );
						}
						$item.addClass( 'animate__animated' );
						return;
					}

					if ( ! isNaN( duration ) ) {
						$item.css( {
							'-webkit-animation-duration': duration + 's',
							'animation-duration': duration + 's',
						} );
					}

					const itemClass = 'so-animate-' + $item.index() + ( 0 | Math.random() * 9e6 ).toString( 36 );
					$item.addClass( itemClass );
					setTimeout( function() {
						onScreen.on( 'enter', '.' + itemClass, function( element ) {
							if ( ! isNaN( delay ) && delay > 0 ) {
								setTimeout( function() {
									doAnimation( '.' + itemClass, settings );
								}, delay * 1000 );
							} else {
								doAnimation( '.' + itemClass, settings );
							}
							onScreen.off( 'enter', '.' + itemClass );
						} );
					}, 1000 );
				} );
			}, 150 );
		},
		setupWidget: function( nav ) {
			const { settings, $el, totalPages } = nav;

			if (
				settings.pagination_reload == 'ajax' ||
				settings.pagination == 'load' ||
				settings.pagination == 'infinite'
			) {
				$el.data( 'fetching', false );
				if ( settings.pagination == 'standard' ) {
					// "replace" current page with span.
					let paged = parseInt( $el.data( 'paged' ) );
					let $current = $el.find( '.page-numbers:not(.dots)' ).eq( paged - 1 );
					$current.after( '<span aria-current="page" class="page-numbers current">' + paged + '</span>' );
					$current.hide();
				} else if ( settings.pagination == 'links' ) {
					// Hide the link depending upon what page we're on.
					let paged = parseInt( $el.data( 'paged' ) );
					if ( paged == 1 ) {
						$el.find( '.sow-previous' ).hide();
					}

					if ( paged == totalPages ) {
						$el.find( '.sow-next' ).hide();
					}
				}

				if ( settings.pagination != 'infinite' ) {
					$el.find( '.sow-nav-links a, .sow-nav-links .sow-blog-load-more:not(.sow-loading)' ).on( 'click', function( e ) {
						if ( settings.pagination != 'infinite' ) {
							e.preventDefault();
						}

						SiteOriginPremium.sowBlogWidget.loadMorePosts( nav, $( this ) );
					} );
				} else {
					$el.find( '.sow-blog-infinite' ).hide();

					SiteOriginPremium.sowBlogWidget.infiniteScroller( nav );

					$( window ).on( 'scroll resize', function() {
						SiteOriginPremium.sowBlogWidget.infiniteScroller( nav );
					} );
				}
			}

			if ( settings.animation ) {
				SiteOriginPremium.sowBlogWidget.setupAnimation( nav );
			}
		},
		setup: function() {
			$( '.sow-blog' ).each( function() {
				const $$ = $( this );
				const nav = {
					settings: $$.data( 'settings' ),
					$el: $$,
					totalPages: $$.data( 'total-pages' ),
				}
				SiteOriginPremium.sowBlogWidget.setupWidget( nav );
			} );
		}

	};

	SiteOriginPremium.sowBlogWidget.setup();
	if ( window.sowb ) {
		$( window.sowb ).on( 'setup_widgets', SiteOriginPremium.sowBlogWidget.setup );
	}
} );
