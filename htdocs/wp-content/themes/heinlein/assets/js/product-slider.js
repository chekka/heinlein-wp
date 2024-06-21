(function ($) {
  $(document).on('ready', function () {
    $("#product-slider > .panel-layout > .panel-grid").slick({
      dots: false,
      adaptiveHeight: false,
      cssEase: "cubic-bezier(0.230, 0.375, 0.100, 1.000)",
      arrows: true,
      infinite: true,
      slidesToShow: 5,
      slidesToScroll: 1,
      speed: 1500,
      draggable: true,
      touchMove: true,
      accessibility: false,
      centerMode: false,
      mobileFirst: true,
      responsive: [{
        breakpoint: 1200,
          settings: {
            slidesToShow: 5
          }
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 1,
          }
        }]
    });

    $("#product-slider > .panel-layout > .panel-grid").on("beforeChange", function (event, slick, currentSlide, nextSlide) {
      console.log("nextSlide: " + nextSlide);

      $('.slick-slide[data-slick-index="' + parseInt(nextSlide - 4) + '"] img').css({ transform: "scale(1)" });
      $('.slick-slide[data-slick-index="' + parseInt(nextSlide - 3) + '"] img').css({ transform: "scale(1.5)" });
      $('.slick-slide[data-slick-index="' + parseInt(nextSlide - 2) + '"] img').css({ transform: "scale(1)" });
      $('.slick-slide[data-slick-index="' + parseInt(nextSlide - 1) + '"] img').css({ transform: "scale(0.1)" });

      $('.slick-slide[data-slick-index="' + parseInt(nextSlide + 0) + '"] img').css({ transform: "scale(0.5)" });
      $('.slick-slide[data-slick-index="' + parseInt(nextSlide + 9) + '"] img').css({ transform: "scale(0.5)" });

      $('.slick-slide[data-slick-index="' + parseInt(nextSlide + 1) + '"] img').css({ transform: "scale(1)" });
      $('.slick-slide[data-slick-index="' + parseInt(nextSlide + 5) + '"] img').css({ transform: "scale(0.1)" });
      $('.slick-slide[data-slick-index="' + parseInt(nextSlide + 10) + '"] img').css({ transform: "scale(1)" });
      $('.slick-slide[data-slick-index="' + parseInt(nextSlide + 14) + '"] img').css({ transform: "scale(1)" });

      $('.slick-slide[data-slick-index="' + parseInt(nextSlide + 2) + '"] img').css({ transform: "scale(1.5)" });
      $('.slick-slide[data-slick-index="' + parseInt(nextSlide + 6) + '"] img').css({ transform: "scale(0.5)" });
      $('.slick-slide[data-slick-index="' + parseInt(nextSlide + 11) + '"] img').css({ transform: "scale(1.5)" });
      $('.slick-slide[data-slick-index="' + parseInt(nextSlide + 15) + '"] img').css({ transform: "scale(1.5)" });

      $('.slick-slide[data-slick-index="' + parseInt(nextSlide + 3) + '"] img').css({ transform: "scale(1)" });
      $('.slick-slide[data-slick-index="' + parseInt(nextSlide + 12) + '"] img').css({ transform: "scale(1)" });
      $('.slick-slide[data-slick-index="' + parseInt(nextSlide + 16) + '"] img').css({ transform: "scale(1)" });

      $('.slick-slide[data-slick-index="' + parseInt(nextSlide + 4) + '"] img').css({ transform: "scale(0.5)" });
      $('.slick-slide[data-slick-index="' + parseInt(nextSlide + 13) + '"] img').css({ transform: "scale(0.5)" });
    });
  });
})(jQuery);
