/***********************************************
 * WIDGET: TABS
 ***********************************************/
(function ($) {

	'use strict';

	VLTJS.tabs = {
		init: function ($scope) {

			var tabs = $scope.find('.vlt-tabs'),
				tab = tabs.find('.vlt-tab');

			tab.eq(0).addClass('active').find('.vlt-tab__text').show();

			tabs.on('click', '.vlt-tab:not(.active) .vlt-tab__title > a', function (e) {
				e.preventDefault();
				tab.removeClass('active').find('.vlt-tab__text').slideUp(500);
				$(this).parents('.vlt-tab').addClass('active').find('.vlt-tab__text').slideDown(500);
			});

		}
	};

	VLTJS.window.on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/vlt-tabs.default',
			VLTJS.tabs.init
		);
	});

})(jQuery);