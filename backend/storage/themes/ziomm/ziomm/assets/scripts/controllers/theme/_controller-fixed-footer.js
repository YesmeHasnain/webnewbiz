/***********************************************
 * THEME: FIXED FOOTER
 ***********************************************/
(function ($) {

	'use strict';

	// check if plugin defined
	if (typeof gsap == 'undefined') {
		return;
	}

	VLTJS.fixedFooterEffect = {
		init: function () {

			var footer = $('.vlt-footer'),
				main = $('.vlt-main'),
				footerBlackShape = footer.find('.elementor-section.has-black-top-shape'),
				footerWhiteShape = footer.find('.elementor-section.has-white-top-shape');

			if ((footerBlackShape.length || footerWhiteShape.length) && !$('.vlt-is--footer-shape-disable').length) {
				main.append('<div class="vlt-main__footer-shape"></div>');
			}

			if (footerBlackShape.length && VLT_LOCALIZE_DATAS.shape_black) {
				$('.vlt-main__footer-shape').css({
					'background-image': 'url(' + VLT_LOCALIZE_DATAS.shape_black + ')'
				});
			}

			if (footerWhiteShape.length && VLT_LOCALIZE_DATAS.shape_white) {
				$('.vlt-main__footer-shape').css({
					'background-image': 'url(' + VLT_LOCALIZE_DATAS.shape_white + ')'
				});
			}

		},
		fixedFooter: function () {

			var footer = $('.vlt-footer.vlt-footer--fixed'),
				delta = .75,
				translateY = 0;

			if (footer.length && VLTJS.window.outerWidth() >= 768) {

				VLTJS.window.on('load scroll', function () {

					var footerHeight = footer.outerHeight(),
						windowHeight = VLTJS.window.outerHeight(),
						documentHeight = VLTJS.document.outerHeight();

					translateY = delta * (Math.max(0, $(this).scrollTop() + windowHeight - (documentHeight - footerHeight)) - footerHeight);

				});

				gsap.ticker.add(function () {

					gsap.set(footer, {
						translateY: translateY,
						translateZ: 0,
					});

				});

			}

		}

	};

	if (!VLTJS.isMobile.any()) {

		VLTJS.fixedFooterEffect.fixedFooter();

		VLTJS.debounceResize(function () {
			VLTJS.fixedFooterEffect.fixedFooter();
		});

	}

	VLTJS.fixedFooterEffect.init();

})(jQuery);