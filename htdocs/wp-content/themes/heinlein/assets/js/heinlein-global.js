(function ($) {
  ('use strict');

  /*
  $(document).on('ready', function () {

    $('.field--name-field-seitenabschnitt > .field__item:first-child > .paragraph').prepend($('.hidden.hero').html());
    $('.node--type-produkt .type--webform > a').removeClass('cta-blue').addClass('cta-gold').text('Produkt anfragen');
    $('html[lang="en"] .node--type-produkt .type--webform > a').removeClass('cta-blue').addClass('cta-gold').text('Make a request');

    var sid = 0;
    if ($(window).width() > 575) {
      $('#prozesskette .row > .x-column').each(function () {
        $(this)
          .attr('data-sid', sid++)
          .on('click', function () {
            $('body').addClass('fertigungsprozess-open');
            var slideNr = $(this).data('sid');
            $('.slider').slick('slickGoTo', slideNr);
          });
      });
      $('#fertigungsprozess .close').on('click', function () {
        $('body').removeClass('fertigungsprozess-open');
      });

      $('#fertigungsprozess .slider').on('init', function (event, slick) {
        var dots = slick.$dots;
        dots.wrapInner("<div class='content'></div>");
        var $items = slick.$dots.find('li');
        $items.find('button').remove();
      });
      $('#fertigungsprozess .slider').on('beforeChange', function (event, slick, currentSlide, nextSlide) {
        var winW = $(window).width();
        $(this).removeClass('current-' + currentSlide);
        $('#fertigungsprozess .slick-dots .content').css({ marginLeft: 433 * nextSlide * -1 });
        if (winW < 433) {
          $('#fertigungsprozess .slick-dots .content').css({ marginLeft: winW * nextSlide * -1 });
        }
        $('html,body').animate({ scrollTop: 0 }, 500);
      });
      $('#fertigungsprozess .slider').on('afterChange', function (event, slick, currentSlide, nextSlide) {
        $(this).addClass('current-' + currentSlide);
      });

      $('#fertigungsprozess .slider').slick({
        dots: true,
        arrows: false,
        speed: 1000,
        waitForAnimate: true,
        adaptiveHeight: true,
        customPaging: function (slider, i) {
          var slide = slider.$slides[i],
            pagination = $(slide).find('.x-column:last-child > div:first-child .text-formatted').html();
          return '<div>' + pagination + '</div>';
        },
      });
    } else {
      $('#fertigungsprozess .slider .paragraph--id--1452 .row > .x-column:last-child').appendTo('.paragraph--id--803');
      $('#fertigungsprozess .slider .paragraph--id--1467 .row > .x-column:last-child').appendTo('.paragraph--id--806');
      $('#fertigungsprozess .slider .paragraph--id--1474 .row > .x-column:last-child').appendTo('.paragraph--id--808');
      $('#fertigungsprozess .slider .paragraph--id--1481 .row > .x-column:last-child').appendTo('.paragraph--id--827');
      $('#fertigungsprozess .slider .paragraph--id--1488 .row > .x-column:last-child').appendTo('.paragraph--id--812');
      $('#fertigungsprozess .slider .paragraph--id--1495 .row > .x-column:last-child').appendTo('.paragraph--id--814');
      $('#fertigungsprozess .slider .paragraph--id--1502 .row > .x-column:last-child').appendTo('.paragraph--id--817');
      $('#fertigungsprozess .slider .paragraph--id--1511 .row > .x-column:last-child').appendTo('.paragraph--id--819');
      $('#fertigungsprozess .slider .paragraph--id--1518 .row > .x-column:last-child').appendTo('.paragraph--id--825');

      $('#prozesskette .view-mode-bildbox-breit').on('click', function () {
        var innerHeight = $(this).find('.x-column').height();
        $(this)
          .css({ 'margin-bottom': innerHeight + 20 })
          .addClass('open');
      });
      $('#prozesskette .view-mode-bildbox-breit .textx-column').on('click', function () {
        $(this).parent('.view-mode-bildbox-breit').removeClass('open');
      });
    }

    $('#history-slider').slick({
      dots: false,
      arrows: true,
      adaptiveHeight: false,
      centerMode: false,
      centerPadding: '60px',
      slidesToShow: 4,
      slidesToScroll: 1,
      responsive: [
        {
          breakpoint: 576,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
          },
        },
      ],
    });

    $('.formslider > .form-group').slick({
      dots: true,
      arrows: true,
      adaptiveHeight: false,
      infinite: false,
      vertical: false,
      slidesToShow: 1,
      slidesToScroll: 1,
      nextArrow: '<div class="button next">Weiter</div>',
      prevArrow: '<div class="button prev">Zurück</div>',
      speed: 1000,
      cssEase: 'cubic-bezier(0.3, 0.5, 0.2, 1)',
    });
    $('html[lang=en] .formslider > .form-group').slick(
      'slickSetOption',
      {
        nextArrow: '<div class="button next">Next</div>',
        prevArrow: '<div class="button prev">Prev</div>',
      },
      true,
    );

    $('.paragraph').each(function () {
      $p = $(this);
      if ($p.attr('id') === 'kundenstimmen') {
        console.log($p.attr('id'));
        $p.find('.type--slider-container .field--name-field-paragraphs')
          .slick('unslick')
          .slick({
            dots: true,
            arrows: false,
            adaptiveHeight: false,
            appendDots: $('#kundenstimmen').find('.dottis'),
            slidesToShow: 2,
            slidesToScroll: 2,
            centerMode: true,
            autoplay: true,
            autoplaySpeed: 3000,
            speed: 1000,
            cssEase: 'cubic-bezier(0.3, 0.5, 0.2, 1)',
            responsive: [
              {
                breakpoint: 576,
                settings: {
                  adaptiveHeight: false,
                  slidesToShow: 1,
                  slidesToScroll: 1,
                },
              },
            ],
          });
      } else {
        $p.find('.type--slider-container .field--name-field-paragraphs').slick({
          dots: true,
          arrows: false,
          adaptiveHeight: false,
          appendDots: $p.find('.dottis'),
          slidesToShow: 1,
          slidesToScroll: 1,
          speed: 1000,
          cssEase: 'cubic-bezier(0.3, 0.5, 0.2, 1)',
        });
      }
    });

    $('.view-mode--slide-in .close').on('click', function () {
      $('#musteranfrage-form,#produktanfrage-form,.type--webform.view-mode--slide-in').removeClass('show');
    });

    $('#produkt-finder .text-link,#sales-service .text-link').on('click', function (event) {
      event.preventDefault();
      $('.type--webform.view-mode--slide-in').addClass('show');
    });
    $('a.produktanfrage').on('click', function (event) {
      event.preventDefault();
      $('#produktanfrage-form,#produktanfrage-form .type--webform.view-mode--slide-in').addClass('show');
    });
    $('a.musteranfrage').on('click', function (event) {
      event.preventDefault();
      $('#musteranfrage-form,#musteranfrage-form .type--webform.view-mode--slide-in').addClass('show');
    });

    $('.colorbox').colorbox({
      transition: 'fade',
      height: '90%',
      initialHeight: '90%',
      fixed: true,
    });
    $('.page-node-4 #cboxWrapper').on('mousedown', function () {
      $.colorbox.close();
    });
    $('.page-node-4 #cboxWrapper').contextmenu(function () {
      $.colorbox.close();
    });
    $(".type--product-addition-image .field--type-string:contains('Dicht')").parents('.type--product-addition-image').wrapInner("<a href='' class='show-dichtsysteme'></a>");
    $(".type--product-addition-image .field--type-string:contains('Sealing')").parents('.type--product-addition-image').wrapInner("<a href='' class='show-dichtsysteme'></a>");
    $(".type--product-addition-image .field--type-string:contains('tanch')").parents('.type--product-addition-image').wrapInner("<a href='' class='show-dichtsysteme'></a>");
    $(".type--product-addition-image .field--type-string:contains('Системы')").parents('.type--product-addition-image').wrapInner("<a href='' class='show-dichtsysteme'></a>");
    $('.type--product-addition-image .show-dichtsysteme').colorbox({
      inline: true,
      href: '#dichtsysteme',
      transition: 'fade',
      width: '95%',
      maxWidth: 1130,
      fixed: false,
    });
    $('video').attr('playsinline', 'playsinline');
    $('.modal-video').click(function () {
      var thisVideo = $(this).find('video');
      $(this).colorbox({
        href: thisVideo,
        inline: true,
        transition: 'fade',
        width: '95%',
        height: '95%',
        maxWidth: 1920,
        maxHeight: 1080,
        fixed: false,
        scrolling: false,
      });
    });

    $('.accordion-element.active').each(function (idx, element) {
      var panel = $('.panel', element);
      $(panel).css('maxHeight', $(panel).prop('scrollHeight') + 'px');
    });

    $('.accordion-container').each(function (idx, container) {
      $('.accordion-element', container).each(function (idx, element) {
        var acc = $('.accordion', element);
        $(acc).on('click', function (e) {
          var panel = $(this).next();
          var img = $(this).find('img');
          if ($(element).hasClass('active')) {
            $(element).removeClass('active');
            $(panel).css('maxHeight', 0);

            $(img).attr('src', $(img).attr('data-inactive-src'));
          } else {
            $(element).addClass('active');
            $(panel).css('maxHeight', $(panel).prop('scrollHeight') + 10 + 'px');
            $(img).attr('src', $(img).attr('data-active-src'));
          }
        });
      });
    });

    $('.webform-submission-kontakt-produkt-form #edit-flexbox-02 input').on('click', function () {
      $('.webform-submission-kontakt-produkt-form #edit-flexbox-03').toggle();
    });

    $('.webform-submission-kontakt-produkt-form #edit-flexbox-04 input').on('click', function () {
      $('.webform-submission-kontakt-produkt-form #edit-flexbox-05, .webform-submission-kontakt-produkt-form #edit-flexbox-06').toggle();
    });  
  });
*/

  $(window).on('load', function () {
    $('body').addClass('loaded');

    $('.alert .btn-close').on('click', function () {
      $(this).parents('.alert-wrapper').remove();
    });

    $(window).on('scroll', function () {
      $('.alert-wrapper').remove();
    });
  });

  $(window).on('resize', function () {
    $winW = $(window).innerWidth();
    $('body').attr({ 'data-width':$winW });
  });

  $(window).on('scroll', function () {
    if ($(window).width() > 574) {
      t = 0;
    } else {
      t = 50;
    }
    if ($(this).scrollTop() > t) {
      $('body').addClass('scrolled');
      $('.site-header').addClass('active');
    } else {
      $('body').removeClass('scrolled');
      $('.site-header').removeClass('active');
    }
    var scroll = $(window).scrollTop();
    position = scroll;

    if (window.innerHeight + window.scrollY >= document.body.offsetHeight) {
      $('.intercom-namespace .intercom-1qaopps').css({
        transform: 'translateY(-50px)',
        transition: 'all .3s',
      });
    } else {
      $('.intercom-namespace .intercom-1qaopps').css({
        transform: 'translateY(0)',
      });
    }
  });

  /*
  $('#fertigungsprozess').on('scroll', function () {
    var scrollPos = $(this).scrollTop();
    if (scrollPos > $(window).innerHeight() * 0.7) {
      $('#fertigungsprozess .slick-dots').addClass('sticky');
    } else {
      $('#fertigungsprozess .slick-dots').removeClass('sticky');
    }
  });

  $(document).ajaxSuccess(function () {
    $('.site-footer div[id^=block-findcontact] .row .node-105 .telefon').on('click', function () {
      window.location = '/kontakt#sales-service';
    });
    // $('.select-wrapper').on('click', function() {
    //     $(this).find('select').click();
    // });
  });
  */

})(jQuery);
