(function ($) {
  ('use strict');

  $(window).on('load', function () {
    $('body').addClass('loaded');
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
  });

  $(document).on('ready',function(){

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
