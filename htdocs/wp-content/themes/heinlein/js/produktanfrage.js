(function($){
  $( '.toggle.send-box' ).on( 'click', function(){
    $( '.ui-accordion-content-active input, .ui-accordion-content-active select' ).prop( 'disabled', false );
  });
})(jQuery)
      