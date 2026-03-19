/***********************************************
 * SHOWCASE: STYLE 3
 ***********************************************/
(function ($) {

	'use strict';

	// check if plugin defined
	if (typeof tippy === 'undefined') {
		return;
	}

	var showcaseStyle3 = function ($scope, $) {

		var showcase = $scope.find('.vlt-showcase--style-3'),
			link = showcase.find('.vlt-showcase-link');

		link.each(function () {

			tippy(this, {
				arrow: false,
				allowHTML: true,
				distance: '1rem',
				duration: [500, 0],
				maxWidth: 270
			});

		});

	};

	VLTJS.window.on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/vlt-showcase-3.default',
			showcaseStyle3
		);
	});

})(jQuery);