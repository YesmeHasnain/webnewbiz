/***********************************************
 * WIDGET: IMAGE SLIDER
 ***********************************************/
(function ($) {

	'use strict';

	// check if plugin defined
	if (typeof Swiper === 'undefined') {
		return;
	}

	VLTJS.imageSlider = {
		init: function ($scope) {

			var slider = $scope.find('.vlt-image-slider'),
				container = slider.find('.swiper-container'),
				anchor = slider.data('navigation-anchor'),
				gap = slider.data('gap') || 0,
				loop = slider.data('loop') == 'yes' ? true : false,
				speed = slider.data('speed') || 1000,
				autoplay = slider.data('autoplay') == 'yes' ? true : false,
				centered = slider.data('slides-centered') == 'yes' ? true : false,
				freemode = slider.data('free-mode') == 'yes' ? true : false,
				slider_offset = slider.data('slider-offset') == 'yes' ? true : false,
				mousewheel = slider.data('mousewheel') == 'yes' ? true : false,
				autoplay_speed = slider.data('autoplay-speed'),
				settings = slider.data('slide-settings');

			if (container.length) {

				var swiper = new Swiper(container.get(0), {
					init: false,
					spaceBetween: gap,
					grabCursor: true,
					initialSlide: settings.initial_slide ? settings.initial_slide : 0,
					loop: loop,
					speed: speed,
					centeredSlides: centered,
					freeMode: freemode,
					mousewheel: mousewheel,
					autoplay: autoplay ? {
						delay: autoplay_speed,
						disableOnInteraction: false
					} : false,
					autoHeight: true,
					slidesOffsetBefore: 100,
					slidesOffsetBefore: slider_offset ? $('.container').get(0).getBoundingClientRect().left + 15 : false,
					slidesOffsetAfter: slider_offset ? $('.container').get(0).getBoundingClientRect().left + 15 : false,
					navigation: {
						nextEl: slider.find('.vlt-swiper-button-next').length ? slider.find('.vlt-swiper-button-next').get(0) : $(anchor).find('.vlt-swiper-button-next').get(0),
						prevEl: slider.find('.vlt-swiper-button-prev').length ? slider.find('.vlt-swiper-button-prev').get(0) : $(anchor).find('.vlt-swiper-button-prev').get(0)
					},
					pagination: {
						el: $(anchor).find('.vlt-swiper-dots').get(0),
						clickable: true
					},
					breakpoints: {
						// when window width is >= 576px
						576: {
							slidesPerView: settings.slides_to_show_mobile || settings.slides_to_show_tablet || settings.slides_to_show || 1,
							slidesPerGroup: settings.slides_to_scroll_mobile || settings.slides_to_scroll_tablet || settings.slides_to_scroll || 1,
						},
						// when window width is >= 768px
						768: {
							slidesPerView: settings.slides_to_show_tablet || settings.slides_to_show || 1,
							slidesPerGroup: settings.slides_to_scroll_tablet || settings.slides_to_scroll || 1,
						},
						// when window width is >= 992px
						992: {
							slidesPerView: settings.slides_to_show || 1,
							slidesPerGroup: settings.slides_to_scroll || 1,
						}
					}
				});

				swiper.init();

			}

		}
	}

	VLTJS.window.on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/vlt-image-slider.default',
			VLTJS.imageSlider.init
		);
	});

})(jQuery);