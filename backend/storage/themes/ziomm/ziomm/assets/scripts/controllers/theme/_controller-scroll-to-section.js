/***********************************************
 * THEME: SCROLL TO SECTION
 ***********************************************/
(function ($) {

	'use strict';

	// check if plugin defined
	if (typeof $.fn.scrollTo === 'undefined') {
		return;
	}

	$('a[href^="#"]').not('[href="#"]').not('[rel="nofollow"]').on('click', function (e) {
		e.preventDefault();
		VLTJS.html.scrollTo($(this).attr('href'), 500);
	});

})(jQuery);

/***********************************************
 * THEME: SCROLL TO TOP
 ***********************************************/
(function ($) {

	'use strict';

	// check if plugin defined
	if (typeof $.fn.scrollTo === 'undefined') {
		return;
	}

	var backToTopBtn = $('.vlt-btn--back-to-top'),
		shopCartIcon = $('.vlt-shop-cart-icon');

	function _show_btn() {
		backToTopBtn.removeClass('is-hidden').addClass('is-visible');
		shopCartIcon.addClass('is-active');
	}

	function _hide_btn() {
		backToTopBtn.removeClass('is-visible').addClass('is-hidden');
		shopCartIcon.removeClass('is-active');
	}

	_hide_btn();

	VLTJS.throttleScroll(function (type, scroll) {
		var offset = VLTJS.window.outerHeight() + 100;
		if (scroll > offset) {
			if (type === 'down') {
				_hide_btn();
			} else if (type === 'up') {
				_show_btn();
			}
		} else {
			_hide_btn();
		}
	});

	backToTopBtn.on('click', function (e) {
		e.preventDefault();
		VLTJS.html.scrollTo(0, 500);
	});

})(jQuery);