/***********************************************
 * WIDGET: MARQUEE TEXT
 ***********************************************/
(function ($) {

	'use strict';

	// check if plugin defined
	if (typeof gsap == 'undefined') {
		return;
	}

	VLTJS.marqueeEffect = {
		init: function ($scope) {

			$scope.find('[data-marquee]').each(function () {
				var $this = $(this),
					speed = $this.data('marquee') || 0.5,
					marqueeText = $this.find('[data-marquee-text]'),
					elWidth = marqueeText.outerWidth(),
					elHeight = marqueeText.outerHeight(),
					duration = elWidth / elHeight * speed + 's';

				gsap.set(marqueeText, {
					animationDuration: duration
				});

			});

		}
	}

	VLTJS.window.on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/vlt-marquee-text.default',
			VLTJS.marqueeEffect.init
		);
	});

	VLTJS.window.on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/vlt-team-member.default',
			VLTJS.marqueeEffect.init
		);
	});

	VLTJS.marqueeEffect.init(VLTJS.body);

	VLTJS.document.on('init.vpf endLoadingNewItems.vpf', function (e) {
		VLTJS.marqueeEffect.init(VLTJS.body);
	});

})(jQuery);