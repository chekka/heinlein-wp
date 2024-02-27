/**
 * @file
 * Global utilities.
 *
 */
(function ($, Drupal) {
  "use strict";

  Drupal.behaviors.heinlein_menu = {
    attach: function (context, settings) {
      this.smooth_scroll(context);
      this.menu(context);
    },

    hide_xs_menu: function () {
      if (window.innerWidth < 992) {
        $("header#header").removeClass("xs-active");
        $(".navbar-toggler-icon").addClass("fa-bars");
        $(".navbar-toggler-icon").removeClass("close-icon");
        $(".collapse").collapse("hide");
        this.xs_sticky();
      }
    },

    show_xs_menu: function () {
      if (window.innerWidth < 992) {
        $("header#header").addClass("xs-active");
        $(".navbar-toggler-icon").removeClass("fa-bars");
        $(".navbar-toggler-icon").addClass("close-icon");
      }
    },

    xs_sticky: function () {
      if (window.innerWidth < 992) {
        if (window.pageYOffset > 10 && $("header#header").hasClass("xs-active") == false) {
          $("header#header").addClass("xs-sticky");
        } else {
          $("header#header").removeClass("xs-sticky");
        }
      }
    },

    menu: function (context) {
      var $this = this;
      if ($("body", context)) {
        //burger menu xs
        if (window.innerWidth < 992) {
          var height = 0;
          $(".navbar-toggler").on("click", function (c) {
            if ($("#CollapsingNavbar").hasClass("show")) {
              $this.hide_xs_menu();
            } else {
              $this.show_xs_menu();
            }
          });
          window.onscroll = function () {
            $this.xs_sticky();
          };
          $this.xs_sticky();
        } else {
          $(".navbar-nav .dropdown-menu").remove();

          $("a.nav-link").on("mouseenter", function () {
            // window.location = $(this).attr("href");
            $("#header").addClass("active");
          });

          $(".navbar-nav a.dropdown-toggle").each(function (idx, a) {
            var base = $this.baseName($(a).prop("href"));
            $(this).on("click", function () {
              var target = $(this).attr("href");
              window.location.href = target;
            });
            $(a).on("mouseenter", function (e) {
              $("div[id^=desk-]")
                .not("#desk" + base)
                .removeClass("active");
              $("#desktop-menu,#header").addClass("show");
              $("#desk-" + base).toggleClass("active");
              $("#desk-" + base).attr("data-open", "true");
              $("header.sticky").addClass("sticky-auto");
            });
          });

          $('html[lang=en] #block-menudesktop .content .field .field__item:nth-child(6)').remove();
          $('html[lang=fr] #block-menudesktop .content .field .field__item:nth-child(6)').remove();
          $('html[lang=ru] #block-menudesktop .content .field .field__item:nth-child(6)').remove();          

          $("#block-menudesktop .field > .field__item").each(function (i) {
            $(this).attr("data-index", i + 1);
          });
          $(".navbar-nav a.dropdown-toggle").each(function (i) {
            $(this).on("mouseenter", function () {
              var current = i + 1;
              $("div[id^=desk-]").removeClass("active");
              $("#desktop-menu,#header").addClass("show");
              $("#block-menudesktop .field > .field__item[data-index=" + current + "] > div")
                .toggleClass("active")
                .attr("data-open", "true");
              $("header.sticky").addClass("sticky-auto");
            });
          });

          window.onscroll = function () {
            doSticky();
            $("#header").removeClass("active");
          };
          doSticky();

          function doSticky() {
            if ($("header div[data-open]").length == 0) {
              $("div[id^=desk-]").removeClass("active");
              $("#desktop-menu,#header").removeClass("show");
            }
            $("header div[data-open]").removeAttr("data-open");
            if (window.pageYOffset > 0) {
              $("header#header").addClass("sticky");
              $("body").addClass("sticky scrolled");
              if ($("header div[data-open]").length == 0) {
                $("header#header").removeClass("sticky-auto");
              }
            } else {
              $("header#header").removeClass("sticky");
              $("body").removeClass("sticky scrolled");
              //$('div#main-wrapper').css('paddingTop', '0px');
            }
          }

          $("#header").on("mouseleave", function () {
            $("#desktop-menu,#header").removeClass("show");
          });

          /*
        $('body')
          .on('mouseenter mouseleave','.dropdown',toggleDropdown)
          .on('click', '.dropdown-menu a', toggleDropdown);

         function toggleDropdown (e) {
          const _d = $(e.target).closest('.dropdown'),
            _m = $('.dropdown-menu', _d);
            setTimeout(function(){
            const shouldOpen = e.type !== 'click' && _d.is(':hover');
            _m.toggleClass('show', shouldOpen);
            _d.toggleClass('show', shouldOpen);
            $('[data-toggle="dropdown"]', _d).attr('aria-expanded', shouldOpen);
            }, e.type === 'mouseleave' ? 200 : 0);
          }
        */
        }
      }
    },
    smooth_scroll: function (context) {
      if ($("body", context).length > 0) {
        var $this = this;
        $(window).on("load", function () {
          if (window.location.hash) {
            var t = window.location.hash;
            $("html, body").animate(
              {
                scrollTop: $(t).offset().top - 130,
              },
              1000,
              function () {
                $(t).focus();
              }
            );
          }
        });

        $('a[href*="#"]')
          // Remove links that don't actually link to anything
          .not('[href="#"]')
          .not('[href="#0"]')
          .click(function (event) {
            // On-page links
            $this.hide_xs_menu();
            if (location.pathname.replace(/^\//, "") == this.pathname.replace(/^\//, "") && location.hostname == this.hostname) {
              // Figure out element to scroll to
              var target = $(this.hash);
              target = target.length ? target : $("[name=" + this.hash.slice(1) + "]");
              // Does a scroll target exist?
              if (target.length) {
                // Only prevent default if animation is actually gonna happen
                event.preventDefault();
                $("html, body").animate(
                  {
                    scrollTop: target.offset().top - 130,
                  },
                  1000,
                  function () {
                    // Callback after animation
                    // Must change focus!
                    var $target = $(target);
                    $target.focus();
                  }
                );
              }
              var hash = $(this).attr("href").split("#").pop();
              window.location.hash = "#" + hash;
            }
          });
      }
    }, // END smooth_scroll()

    baseName: function (str) {
      var base = new String(str).substring(str.lastIndexOf("/") + 1);
      if (base.lastIndexOf(".") != -1) base = base.substring(0, base.lastIndexOf("."));
      return base;
    },
  };
})(jQuery, Drupal);
