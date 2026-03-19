/***********************************************
 * WIDGET: SECTION TITLE
 ***********************************************/
(function ($) {

	'use strict';

	// check if plugin defined
	if (typeof fitty === 'undefined') {
		return;
	}

	VLTJS.sectionTitle = {
		init: function ($scope) {

			var title = $scope.find('.vlt-section-title--style-2'),
				heading = title.find('.vlt-section-title__heading'),
				maxFitValue = title.data('max-fit-value') || 390;

			fitty(heading.get(0), {
				minSize: 15,
				maxSize: maxFitValue
			});

		}
	}

	VLTJS.window.on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/vlt-section-title.default',
			VLTJS.sectionTitle.init
		);
	});

})(jQuery);