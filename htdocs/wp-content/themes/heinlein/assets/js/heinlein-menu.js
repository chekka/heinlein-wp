(function ($) {
  "use strict";

  $(document).on("ready", function () {
    $("#menu-hauptmenue").on("mouseenter", function () {
      $("#desktop-menu").addClass("show");
    });

    $(".site-header").on("mouseleave", function () {
      $("#desktop-menu").removeClass("show");
    });

    $("#menu-hauptmenue .menu-item a").on("mouseenter", function () {
      var target = $(this).text().toLowerCase();
      var text = target.replace("ä", "ae");
      $(".mainnav-desktop").parent(".panel-grid").removeClass("active");
      $("#menu-" + text)
        .parent(".panel-grid")
        .addClass("active");
    });

    $(".menu-hauptmenue-container li.separator").on("click", function () {
      $(this).removeClass("show");
    });

    $(".menu-hauptmenue-container li.separator + li").on("click", function (e) {
      e.preventDefault();
      $(".menu-hauptmenue-container").find(".separator").addClass("show");
    });

    $(".mainnav-desktop .panel-grid-cell .wide").each(function () {
      $(this).parent(".panel-grid-cell").addClass("wide");
    });

    $(window).on("resize scroll", function () {
      if ($("body").hasClass("front")) {
        var counterPos = $(".counter-wrapper").offset().top;
        if (counterPos > 0 && $("body").scrollTop() >= counterPos) {
          $(".counter var").each(function () {
            $(this).counterUp({
              delay: 1000,
              time: 1000,
            });
          });
        }
      }
    });
  });
})(jQuery);
