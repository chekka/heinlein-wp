(function ($) {
  ('use strict');
  
  $(window).on('load', function () {
    $('body').addClass('loaded');
  });

  $(window).on('resize', function () {
    $winW = $(window).innerWidth();
    $('body').attr({ 'data-width':$winW });
  });

  $(document).on('ready',function(){

    $(".wpcf7-form").removeAttr('novalidate');

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
  });

})(jQuery);
