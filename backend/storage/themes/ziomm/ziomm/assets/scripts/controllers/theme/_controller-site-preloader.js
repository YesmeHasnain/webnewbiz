/***********************************************
 * THEME: PRELOADER
 ***********************************************/
(function ($) {

	'use strict';

	// check if plugin defined
	if (typeof $.fn.animsition == 'undefined') {
		VLTJS.window.trigger('vlt.site-loaded');
		VLTJS.html.addClass('vlt-is-page-loaded');
		return;
	}

	var preloader = $('.animsition'),
		preloaderStyle = VLTJS.body.data('animsition-style'), //animsition-bounce, animsition-image
		loadingInner;

	switch (preloaderStyle) {
		case 'animsition-bounce':
			loadingInner = '<span class="double-bounce-one"></span><span class="double-bounce-two"></span>';
			break;

		case 'animsition-image':
			if (VLT_LOCALIZE_DATAS.preloader_image) {
				loadingInner = '<img src="' + VLT_LOCALIZE_DATAS.preloader_image + '" alt="preloader">';
			}
			break;
	}

	if (preloader.length) {

		preloader.animsition({
			inDuration: 500,
			outDuration: 500,
			loadingParentElement: 'html',
			linkElement: 'a:not(.remove):not(.vp-pagination__load-more):not(.elementor-accordion-title):not([href="javascript:;"]):not([role="slider"]):not([data-elementor-open-lightbox]):not([data-fancybox]):not([data-vp-filter]):not([target="_blank"]):not([href^="#"]):not([rel="nofollow"]):not([href~="#"]):not([href^=mailto]):not([href^=tel]):not(.sf-with-ul):not(.elementor-toggle-title)',
			loadingClass: preloaderStyle,
			loadingInner: loadingInner
		});

		preloader.on('animsition.inEnd', function () {
			VLTJS.window.trigger('vlt.site-loaded');
			VLTJS.html.addClass('vlt-is-page-loaded');
		});

	} else {
		VLTJS.window.trigger('vlt.site-loaded');
		VLTJS.html.addClass('vlt-is-page-loaded');
	}

})(jQuery);