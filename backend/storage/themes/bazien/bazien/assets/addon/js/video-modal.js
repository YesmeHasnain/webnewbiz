(function ($) {

    "use strict";

    $(window).on('elementor/frontend/init', function () {

        window.elementorFrontend.hooks.addAction('frontend/element_ready/novaworks-video-modal.default', function ($scope) {
          if($('.js-video-modal-btn').length > 0) {
            $(".js-video-modal-btn").modalVideo();
          }
          if($('.js-vimeo-modal-btn').length > 0) {
            $(".js-vimeo-modal-btn").modalVideo({channel:'vimeo'});
          }
        });
    });

}(jQuery));
