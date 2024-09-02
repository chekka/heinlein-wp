(function ($) {

  $(document).ready(function () {

    $('.ui-accordion-content-active input, .ui-accordion-content-active select').prop('disabled', false);

    $('.toggle.send-box').on('click', function () {      
      setTimeout(function(){
        // alert("Click!");
        $('.ui-accordion-content-active input, .ui-accordion-content-active select').prop('disabled', false);
      }, 333 );      
    });

  });

})(jQuery);
                        