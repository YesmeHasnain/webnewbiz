/***********************************************
 * WIDGET: BUTTON
 ***********************************************/
(function ($) {

	'use strict';

	VLTJS.button = {

		init: function ($scope) {

			var button = $scope.find('.vlt-btn.vlt-btn--effect');

			if (!button.find('span.blind').length) {
				button.append('<span class="blind">');
			}

			button.on('mouseenter', function (e) {
				var $this = $(this),
					parentOffset = $this.offset(),
					relX = e.pageX - parentOffset.left,
					relY = e.pageY - parentOffset.top;
				$this.find('.blind').css({
					top: relY,
					left: relX
				});
			}).on('mouseout', function (e) {
				var $this = $(this),
					parentOffset = $this.offset(),
					relX = e.pageX - parentOffset.left,
					relY = e.pageY - parentOffset.top;
				$this.find('.blind').css({
					top: relY,
					left: relX
				});
			});

		}

	}

	VLTJS.window.on('elementor/frontend/init', function () {

		elementorFrontend.hooks.addAction(
			'frontend/element_ready/vlt-button.default',
			VLTJS.button.init
		);

		elementorFrontend.hooks.addAction(
			'frontend/element_ready/vlt-showcase-2.default',
			VLTJS.button.init
		);

		elementorFrontend.hooks.addAction(
			'frontend/element_ready/vlt-showcase-6.default',
			VLTJS.button.init
		);

		elementorFrontend.hooks.addAction(
			'frontend/element_ready/vlt-contact-form-7.default',
			VLTJS.button.init
		);

		elementorFrontend.hooks.addAction(
			'frontend/element_ready/slider_revolution.default',
			VLTJS.button.init
		);

	});

	VLTJS.button.init(VLTJS.body);

	VLTJS.document.on('init.vpf endLoadingNewItems.vpf', function (e) {
		VLTJS.button.init(VLTJS.body);
	});

})(jQuery);