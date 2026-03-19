/***********************************************
 * SHOWCASE: STYLE 5
 ***********************************************/
(function ($) {

	'use strict';

	// check if plugin defined
	if (typeof Swiper === 'undefined') {
		return;
	}

	var showcaseStyle5 = function ($scope, $) {

		var showcase = $scope.find('.vlt-showcase--style-5'),
			images = showcase.find('.vlt-showcase-images'),
			links = showcase.find('.vlt-showcase-links');

		// add active class
		links.find('.vlt-showcase-link').eq(0).addClass('is-active');

		var swiper = new Swiper(images.find('.swiper-container').get(0), {
			loop: false,
			effect: 'fade',
			lazy: true,
			slidesPerView: 1,
			allowTouchMove: false,
			speed: 1000,
			on: {
				init: function () {
					links.on('mouseenter', '.vlt-showcase-link', function (e) {
						e.preventDefault();
						var currentLink = $(this);
						links.find('.vlt-showcase-link').removeClass('is-active');
						currentLink.addClass('is-active');
						swiper.slideTo(currentLink.index());
					});
				},
			}
		});

	};

	VLTJS.window.on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/vlt-showcase-5.default',
			showcaseStyle5
		);
	});

})(jQuery);