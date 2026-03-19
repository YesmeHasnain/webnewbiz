/***********************************************
 * THEME: ELEMENTOR COLUMN
 ***********************************************/
(function ($) {

	'use strict';

	VLTJS.stickyColumn = {
		init: function ($scope) {

			const $parent = $scope.filter('.has-sticky-column');

			if ($parent.length) {
				$parent.find('>.elementor-widget-wrap').addClass('sticky-parent').find('>.elementor-element').wrap('<div class="sticky-column">');
			}

		}
	};

	VLTJS.window.on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/column',
			VLTJS.stickyColumn.init
		);
	});

})(jQuery);