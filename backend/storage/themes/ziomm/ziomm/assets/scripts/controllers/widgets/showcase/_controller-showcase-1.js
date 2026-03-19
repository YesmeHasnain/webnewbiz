/***********************************************
 * SHOWCASE: STYLE 1
 ***********************************************/
(function ($) {

	'use strict';

	// check if plugin defined
	if (typeof Swiper === 'undefined') {
		return;
	}

	var showcaseStyle1 = function ($scope) {

		var showcase = $scope.find('.vlt-showcase--style-1'),
			images = showcase.find('.vlt-showcase-images'),
			links = showcase.find('.vlt-showcase-links');

		// add active class
		links.find('li').eq(0).addClass('is-active');

		var swiper = new Swiper(images.find('.swiper-container').get(0), {
			init: false,
			effect: 'fade',
			loop: false,
			slidesPerView: 1,
			speed: 1000,
			allowTouchMove: false,
			on: {
				init: function () {

					links.on('mouseenter', '.vlt-showcase-link', function (e) {
						e.preventDefault();
						var currentLink = $(this);
						links.find('.vlt-showcase-link').removeClass('is-active');
						console.log(currentLink.index());
						images.find('.vlt-showcase-image[data-index="' + currentLink.data('index') + '"]').addClass('is-hover');
						currentLink.addClass('is-active');
						swiper.slideTo(currentLink.data('index'));
					});

					links.on('mouseleave', '.vlt-showcase-link', function (e) {
						e.preventDefault();
						images.find('.vlt-showcase-image').removeClass('is-hover');
					});

				}
			}
		});

		swiper.init();

	}

	VLTJS.window.on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/vlt-showcase-1.default',
			showcaseStyle1
		);
	});

})(jQuery);