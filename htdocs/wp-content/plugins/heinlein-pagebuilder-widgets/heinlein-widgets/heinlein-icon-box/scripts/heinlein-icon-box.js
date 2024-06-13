jQuery(function ($) {
  $(document).ready(function () {

    $('a.popup').on('click', function (event) {
      event.preventDefault();
      $('.d-flex.popup').removeClass('show');
      $(this).parents('.d-flex.popup').addClass('show');
    });
    $('.d-flex.popup .popup-content').on('click', function () {
      $(this).parents('.d-flex.popup').removeClass('show');
    });

  });
});