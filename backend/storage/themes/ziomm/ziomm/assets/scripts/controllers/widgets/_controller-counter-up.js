/***********************************************
 * WIDGET: COUNTER UP
 ***********************************************/
(function ($) {

	'use strict';

	// check if plugin defined
	if (typeof $.fn.numerator == 'undefined') {
		return;
	}

	VLTJS.counterUp = {
		init: function ($scope) {

			var counterUp = $scope.find('.vlt-counter-up'),
				animation_duration = counterUp.data('animation-speed') || 1000,
				ending_number = counterUp.find('.counter'),
				ending_number_value = ending_number.text(),
				delimiter = counterUp.data('delimiter') ? counterUp.data('delimiter') : ',';

			if (counterUp.hasClass('vlt-counter-up--style-2')) {

				ending_number.css({
					'min-width': ending_number.outerWidth() + 'px'
				});

			}

			counterUp.one('inview', function () {

				ending_number.text('0');

				ending_number.numerator({
					easing: 'linear',
					duration: animation_duration,
					delimiter: delimiter,
					toValue: ending_number_value,
				});

			});

		}
	}

	VLTJS.window.on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/vlt-counter-up.default',
			VLTJS.counterUp.init
		);
	});

})(jQuery);