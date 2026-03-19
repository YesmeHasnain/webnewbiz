/***********************************************
 * WIDGET: HOVER STYLE
 ***********************************************/
(function ($) {

	'use strict';

	// check if plugin defined
	if (typeof charming === 'undefined') {
		return;
	}

	if (typeof gsap == 'undefined') {
		return;
	}

	VLTJS.hoverStyle = {
		init: function ($scope) {

			var element = $scope.find('[data-hover-style]'),
				hoverStyle = element.data('hover-style') || 'style-1',
				isActive = false,
				mouseTimeout = null;

			if (!element.length) {
				return;
			}

			charming(element.get(0));

			var chars = element.find('span[class^="char"]');

			function _shuffle(arr) {
				for(var j, x, i = arr.length; i; j = parseInt(Math.random() * i), x = arr[--i], arr[i] = arr[j], arr[j] = x);
				return arr;
			}

			function _mouseenter() {

				mouseTimeout = setTimeout(function () {

					isActive = true;

					gsap.killTweensOf(chars);

					switch (hoverStyle) {
						case 'style-1':

							gsap.fromTo(chars, .3, {
								opacity: 0
							}, {
								ease: 'none',
								opacity: 1,
								stagger: .03
							});

							break;

						case 'style-2':

							gsap.fromTo(_shuffle(chars), .3, {
								opacity: 0
							}, {
								ease: 'none',
								opacity: 1,
								stagger: .03
							});

							break;
					}

				}, 50);

			}

			function _mouseleave() {

				clearTimeout(mouseTimeout);
				if ( isActive ) return;
				isActive = false;

			}

			element.on('mouseenter', _mouseenter);
			element.on('touchstart', _mouseenter);
			element.on('mouseleave', _mouseleave);
			element.on('touchend', _mouseleave);

		}
	}

	VLTJS.window.on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/vlt-team-member.default',
			VLTJS.hoverStyle.init
		);
	});

	VLTJS.window.on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/vlt-service-box-1.default',
			VLTJS.hoverStyle.init
		);
	});

	VLTJS.window.on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/vlt-service-box-3.default',
			VLTJS.hoverStyle.init
		);
	});

	VLTJS.window.on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/vlt-service-box-4.default',
			VLTJS.hoverStyle.init
		);
	});

	VLTJS.window.on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/vlt-demo-item.default',
			VLTJS.hoverStyle.init
		);
	});

})(jQuery);

