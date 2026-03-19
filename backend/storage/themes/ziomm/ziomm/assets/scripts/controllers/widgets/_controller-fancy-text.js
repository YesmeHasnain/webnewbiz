/***********************************************
 * WIDGET: FANCY TEXT
 ***********************************************/
(function ($) {

	'use strict';

	// check if plugin defined
	if (typeof Typed == 'undefined') {
		return;
	}

	// check if plugin defined
	if (typeof $.fn.Morphext == 'undefined') {
		return;
	}

	VLTJS.fancyText = {
		init: function ($scope) {

			var fancyText = $scope.find('.vlt-fancy-text'),
				strings = fancyText.find('.strings'),
				fancy_text = fancyText.data('fancy-text') || '',
				fancy_text = fancy_text.split('|'),
				animation_type = fancyText.data('animation-type') || '',
				typing_speed = fancyText.data('typing-speed') || '',
				delay = fancyText.data('delay') || '',
				type_cursor = fancyText.data('type-cursor') == 'yes' ? true : false,
				type_cursor_symbol = fancyText.data('type-cursor-symbol') || '|',
				typing_loop = fancyText.data('typing-loop') == 'yes' ? true : false;

			if (animation_type == 'typing') {
				new Typed(strings.get(0), {
					strings: fancy_text,
					typeSpeed: typing_speed,
					backSpeed: 0,
					startDelay: 300,
					backDelay: delay,
					showCursor: type_cursor,
					cursorChar: type_cursor_symbol,
					loop: typing_loop
				});
			} else {
				strings.show().Morphext({
					animation: animation_type,
					separator: ', ',
					speed: delay,
					complete: function () {
						// Overrides default empty function
					}
				});
			}
		}
	}

	VLTJS.window.on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/vlt-fancy-text.default',
			VLTJS.fancyText.init
		);
	});

})(jQuery);