/***********************************************
 * THEME: SITE PROTECTION
 ***********************************************/
(function($) {

	'use strict';

	if (!VLTJS.html.hasClass('vlt-is--site-protection')) {
		return;
	}

	var isClicked = false;

	VLTJS.document.bind('contextmenu', function(e) {
		e.preventDefault();

		if (!isClicked) {
			$('.vlt-site-protection').addClass('is-visible');
			VLTJS.body.addClass('is-right-clicked');
			isClicked = true;
		}

		VLTJS.document.on('mousedown', function() {
			$('.vlt-site-protection').removeClass('is-visible');
			VLTJS.body.removeClass('is-right-clicked');
			isClicked = false;
		});

		isClicked = false;

	});

})(jQuery);