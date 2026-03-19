/***********************************************
 * SHOWCASE: STYLE 2
 ***********************************************/
(function ($) {

	'use strict';

	// check if plugin defined
	if (typeof Swiper === 'undefined') {
		return;
	}

	var showcaseStyle2 = function ($scope, $) {

		var showcase = $scope.find('.vlt-showcase--style-2'),
			duration = 1000;

		VLTJS.html.css('overflow', 'hidden');

		var swiper = new Swiper(showcase.find('.swiper-container').get(0), {
			init: false,
			direction: 'vertical',
			lazy: true,
			loop: false,
			slidesPerView: 1,
			parallax: true,
			mousewheel: {
				releaseOnEdges: true,
			},
			grabCursor: false,
			speed: duration
		});

		VLTJS.document.on('keyup', function (e) {
			if (e.keyCode == 38) {
				// left
				swiper.slidePrev();
			} else if (e.keyCode == 40) {
				// right
				swiper.slideNext();
			}
		});

		swiper.init();

	};

	VLTJS.window.on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/vlt-showcase-2.default',
			showcaseStyle2
		);
	});

})(jQuery);