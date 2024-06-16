(function ($) {
  "use strict";

  $(document).on("ready", function () {
    
    $(".mainnav-desktop:not(#menu-produkte, #menu-products)").each(function () {
      $(this).find(".panel-grid-cell .widget_sow-image > div:not(.no-target)").each(function(){
        var title = $(this).find(".widget-title").text();
        var target = title.toLowerCase().replace(" ","-").replace("ü","ue");
        var url = $(this).find('.sow-image-container a').attr("href");
        var new_url = url + "#" + target;
        $(this).find('.sow-image-container a').attr("href", new_url);      
      });
    });
    

    $("#nav-row div[class^=menu-hauptmenue]").on("mouseenter", function () {
      $("#desktop-menu").addClass("show");
    });

    $(".site-header").on("mouseleave", function () {
      $("#desktop-menu").removeClass("show");
    });

    $("ul[id^=menu-hauptmenue] .menu-item a").on("mouseenter", function () {
      var target = $(this).text().toLowerCase();
      var text = target.replace("ä", "ae");
      $(".mainnav-desktop").parent(".panel-grid").removeClass("active");
      $("#menu-" + text)
        .parent(".panel-grid")
        .addClass("active");
    });

    $("#nav-row div[class^=menu-hauptmenue] li.separator").on("click", function () {
      $(this).removeClass("show");
    });

    $("#nav-row div[class^=menu-hauptmenue] li.separator + li").on("click", function (e) {
      e.preventDefault();
      $("#nav-row div[class^=menu-hauptmenue]").find(".separator").addClass("show");
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
