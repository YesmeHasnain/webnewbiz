/***********************************************
 * THEME: BLOG
 ***********************************************/
(function ($) {

	'use strict';

	// check if plugin defined
	if (typeof Swiper === 'undefined') {
		return;
	}

	VLTJS.blog = {
		init: function () {

			VLTJS.blog.postFormatGallerySlider();

		},
		postFormatGallerySlider: function () {

			$('.vlt-post-media__gallery').each(function () {

				var gap = $(this).data('gap') || 0;

				new Swiper($(this).find('.swiper-container').get(0), {
					loop: true,
					spaceBetween: gap,
					// centeredSlides: true,
					autoplay: {
						delay: 5000,
					},
					slidesPerView: 1,
					loopAdditionalSlides: 1,
					grabCursor: true,
					speed: 600,
					mousewheel: false,
					navigation: {
						nextEl: $(this).find('.vlt-swiper-button-next').get(0),
						prevEl: $(this).find('.vlt-swiper-button-prev').get(0),
					}
				});

			});

		}
	};

	VLTJS.blog.init();

})(jQuery);