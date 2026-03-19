/***********************************************
 * WIDGET: HEADING
 ***********************************************/
(function ($) {

	'use strict';

	// check if plugin defined
	if (typeof fitty === 'undefined') {
		return;
	}

	VLTJS.heading = {
		init: function ($scope) {

			var heading = $scope.find('.vlt-heading--style-2'),
				maxFitValue = heading.data('max-fit-value') || 390;

			if (heading.length) {

				fitty(heading.get(0), {
					minSize: 15,
					maxSize: maxFitValue
				});

			}

		}
	}

	VLTJS.window.on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/vlt-heading.default',
			VLTJS.heading.init
		);
	});

})(jQuery);