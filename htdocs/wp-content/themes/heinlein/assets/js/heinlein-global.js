(function ($) {
  ('use strict');
  
  $(window).on('load', function () {
    $('body').addClass('loaded');

    if ($(".glider").length) {
      document.querySelector('.glider').addEventListener('glider-animated', function() {      
        $('.slider-next.disabled').hide().next().show();
      });
    }    

  });

  $(window).on('resize', function () {
    $winW = $(window).innerWidth();
    $('body').attr({ 'data-width':$winW });
  });

  $(document).on('ready',function(){

    $('a[data-box-type="lifestyle"]').on('click', function(){
      $('#pum-647 input[value="LIFESTYLE COLLECTION"]').prop('checked','checked');;
    });

    $('a[data-box-type="classic"]').on('click', function(){
      $('#pum-647 input[value="CLASSIC COLLECTION"]').prop('checked','checked');;
    });
    
    // Popup form in next so-panel
    $('.popup-next').on('click', function(){
      var parent = $(this).parents('.so-panel');
      var next = $(parent).next('.so-panel');
      $(next).find('.popup-form').addClass('show');
    });

    // counterUp
    function counter() {
      if ($('body').length > 0 && 'IntersectionObserver' in window) {
        const targets = document.querySelectorAll('.counter strong');
        var countup = function (target) {
          const io = new IntersectionObserver((entries, observer) => {
            entries.forEach((entry) => {
              if (entry.isIntersecting) {
                const counter = entry.target;
                $(counter).counterUp({
                  delay: 10,
                  time: 2000,
                });
                observer.disconnect();
              }
            });
          });
          io.observe(target);
        };
        targets.forEach(countup);
      }
    }
    counter();

    // Slick text slider
    $('.text-slider .panel-grid-cell').slick({
      arrows: false,
      dots: true
    });

    $('.text-slider-2 .panel-grid-cell').slick({
      arrows: false,
      dots: true,
      slidesToShow: 2,
      slidesToScroll: 1,
      responsive: [{
        breakpoint: 700,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
        }
      }]
    });

    $('form .slick').slick({
      arrows: false,
      dots: true,
      infinite: false,
      adaptiveHeight: true
    });

    $('form .slick .goprev').on('click',function(){
      $('form .slick').slick('slickPrev');
    });
    
    $('form .slick .gonext').on('click',function(){
      $('form .slick').slick('slickNext');
    });

    // Slick history slider
    $('.history-slider .panel-layout').slick({
      slidesToShow: 4,
      responsive: [{
        breakpoint: 1200,
          settings: {
            slidesToShow: 3
          }
        },
        {
          breakpoint: 900,
          settings: {
            slidesToShow: 2,
          }
        },
        {
          breakpoint: 560,
          settings: {
            slidesToShow: 1,
          }
        }]
    });
    
    // Video popup
    $('.pum-theme-186').each(function(){
      
      $video_url = $('iframe').attr('src');
      
      if($video_url.includes( 'vimeo' )){
        $(this).on('pumBeforeOpen', function () {
          var $iframe = $('iframe', $(this));
          var src = $iframe.prop('src');
          $iframe.prop('src', '').prop('src', src + '&autoplay=1'); // Add &muted=1 if needed.
        });
      }

      $(this).on('pumBeforeOpen', function () {
        var video_src = $(this).find('video source').attr('src');
        var video = $('video', $(this));
        if(video_src){
          video[0].play();
        }
      });
      $(this).on('pumBeforeClose', function () {
        var video_src = $(this).find('video source').attr('src');
        var video = $('video', $(this));
        if(video_src){
          video[0].pause();
          video[0].currentTime = 0;
        }
      });
    });

    $('.wpcf7-form').attr({ 'novalidate': 'novalidate' });

  });
  
})(jQuery);
