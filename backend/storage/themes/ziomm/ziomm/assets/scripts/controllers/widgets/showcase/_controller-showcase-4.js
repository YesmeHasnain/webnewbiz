/***********************************************
 * SHOWCASE: STYLE 4
 ***********************************************/
(function ($) {

	'use strict';

	// check if plugin defined
	if (typeof Swiper === 'undefined') {
		return;
	}

	var showcaseStyle4 = function ($scope, $) {

		var showcase = $scope.find('.vlt-showcase--style-4');

		var swiper = new Swiper(showcase.find('.swiper-container').get(0), {
			init: false,
			lazy: true,
			loop: false,
			mousewheel: {
				releaseOnEdges: true,
			},
			slidesPerView: 1,
			speed: 1000,
			touchReleaseOnEdges: true,
			breakpoints: {
				// when window width is >= 576px
				576: {
					slidesPerView: 1
				},
				// when window width is >= 768px
				768: {
					slidesPerView: 2
				},
				// when window width is >= 992px
				992: {
					slidesPerView: 3
				}
			},
		});

		VLTJS.document.on('keyup', function (e) {
			if (e.keyCode == 37) {
				// left
				swiper.slidePrev();
			} else if (e.keyCode == 39) {
				// right
				swiper.slideNext();
			}
		});

		swiper.init();

	};

	VLTJS.window.on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/vlt-showcase-4.default',
			showcaseStyle4
		);
	});

})(jQuery);