(function ($) {
  'use strict';

  $(document).on('ready', function () {

    // Site header height
    var headerHeight = $('.site-logo').outerHeight() + 20;
    $('.site-header').css({ 'min-height': headerHeight });

    // Desktop Menu
    $('.hamburger').on('click', function(){
      $(this).toggleClass('open');

      if($(this).hasClass('open')){
        $('#menu-mobile-menu').slideDown();
      } else {
        $('#menu-mobile-menu').slideUp();
      }
    });

    $('.menu-item-has-children > a').on('click', function(e){
      e.preventDefault();
      $('.sub-menu').slideUp();
      $(this).next('.sub-menu').slideToggle();
    });
    
    $('.mainnav-desktop:not(#menu-produkte, #menu-products)').each(function () {
      $(this).find('.panel-grid-cell .widget_sow-image > div:not(.no-target)').each(function(){
        var title = $(this).find('.sow-image-container img').attr('alt');
        var target = title.toLowerCase().replace(' ','-').replace('ä','ae').replace('ü','ue');
        var url = $(this).find('.sow-image-container a').attr('href');
        var new_url = url + '#' + target;
        $(this).find('.sow-image-container a').attr('href', new_url);      
      });
    });

    $('#nav-row div[class^=menu-hauptmenue]').on('mouseenter', function () {
      $('#desktop-menu').addClass('show');
    });

    $('.site-header').on('click mouseleave', function () {
      $('#desktop-menu').removeClass('show');
    });

    $('ul[id^=menu-hauptmenue] .menu-item a').on('mouseenter', function () {
      var target = $(this).text().toLowerCase();
      var text = target.replace('ä', 'ae');
      $('.mainnav-desktop').parent('.panel-grid').removeClass('active');
      $('#menu-' + text).parent('.panel-grid').addClass('active');
    });

    $('#nav-row div[class^=menu-hauptmenue] li.separator').on('click', function () {
      $(this).removeClass('show');
    });

    $('#nav-row div[class^=menu-hauptmenue] li.separator + li').on('click', function (e) {
      e.preventDefault();
      $('#nav-row div[class^=menu-hauptmenue]').find('.separator').addClass('show');
    });

    $('.mainnav-desktop .panel-grid-cell .wide').each(function () {
      $(this).parent('.panel-grid-cell').addClass('wide');
    });

    $(window).on('resize scroll', function () {
      
    });

    $(window).on('scroll', function () {
      $('.hamburger').removeClass('open');
      
      var t = 50;
      // if ($(window).width() < 575) {
      //   $t = 50;
      // }
      if ($(this).scrollTop() > t) {
        $('body').addClass('scrolled');
        $('.site-header').addClass('active');
      } else {
        $('body').removeClass('scrolled');
        $('.site-header').removeClass('active');
      }
    });
  });
})(jQuery);
