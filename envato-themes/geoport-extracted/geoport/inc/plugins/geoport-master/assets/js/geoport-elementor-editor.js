( function( $ ) {

  // counterUp
  var counterpreset = function( $scope, $ ) {
    $('.count').counterUp({
      delay: 10,
      time: 2000
    });
  }

  var databg_img = function( $scope, $ ) {
    // data - background
    $("[data-background]").each(function () {
      $(this).css("background-image", "url(" + $(this).attr("data-background") + ")")
    })
  }
  
  // Make sure you run this code under Elementor.
  $( window ).on( 'elementor/frontend/init', function() {
    elementorFrontend.hooks.addAction( 'frontend/element_ready/counter-items.default', counterpreset );
    elementorFrontend.hooks.addAction( 'frontend/element_ready/contact-tab.default', databg_img );
  } );
})( jQuery );