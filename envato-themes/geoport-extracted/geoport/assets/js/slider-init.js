// slider-init.js
(function($) {
    "use strict";

    function initSlider(sliderSelector, options) {
        var $slider = $(sliderSelector);
        
        $slider.on('init', function(e, slick) {
            var $firstAnimatingElements = $(sliderSelector + ' .single-slider:first-child').find('[data-animation]');
            doAnimations($firstAnimatingElements);
        });

        $slider.on('beforeChange', function(e, slick, currentSlide, nextSlide) {
            var $animatingElements = $(sliderSelector + ' .single-slider[data-slick-index="' + nextSlide + '"]').find('[data-animation]');
            doAnimations($animatingElements);
        });

        $slider.slick({
            autoplay: options.autoplay,
            autoplaySpeed: options.autoplaySpeed,
            dots: false,
            fade: options.fade,
            prevArrow: '<button type="button" class="slick-prev"><i class="far fa-arrow-left"></i></button>',
            nextArrow: '<button type="button" class="slick-next"><i class="far fa-arrow-right"></i></button>',
            arrows: options.arrows,
            responsive: [
                { breakpoint: 767, settings: { dots: false, arrows: false } }
            ]
        });

        function doAnimations(elements) {
            var animationEndEvents = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
            elements.each(function() {
                var $this = $(this);
                var $animationDelay = $this.data('delay');
                var $animationType = 'animated ' + $this.data('animation');
                $this.css({
                    'animation-delay': $animationDelay,
                    '-webkit-animation-delay': $animationDelay
                });
                $this.addClass($animationType).one(animationEndEvents, function() {
                    $this.removeClass($animationType);
                });
            });
        }
    }

    // Export the function to be used elsewhere
    window.initSlider = initSlider;
})(jQuery);
