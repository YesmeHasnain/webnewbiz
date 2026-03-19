/***********************************************
 * WIDGET: PROGRESS BAR
 ***********************************************/
(function ($) {

	'use strict';

	// check if plugin defined
	if (typeof gsap === 'undefined') {
		return;
	}

	VLTJS.progressBar = {
		init: function ($scope) {

			var progressBar = $scope.find('.vlt-progress-bar'),
				final_value = progressBar.data('final-value') || 0,
				animation_duration = progressBar.data('animation-speed') || 0,
				delay = 150,
				obj = {
					count: 0
				};

			progressBar.one('inview', function () {

				gsap.to(obj, (animation_duration / 1000) / 2, {
					count: final_value,
					delay: delay / 1000,
					onUpdate: function () {
						progressBar.find('.vlt-progress-bar__title > .counter').text(Math.round(obj.count));
					}
				});

				gsap.to(progressBar.filter('.vlt-progress-bar--default').find('.vlt-progress-bar__track > .bar'), animation_duration / 1000, {
					width: final_value + '%',
					delay: delay / 1000
				});

				gsap.to(obj, animation_duration / 1000, {
					count: final_value,
					delay: delay / 1000,
					onUpdate: function () {
						progressBar.filter('.vlt-progress-bar--dotted').find('.vlt-progress-bar__track > .bar').css('clip-path', 'inset(0 ' + (100 - Math.round(obj.count)) + '% 0 0)');
					}
				});

			});

		}
	}

	VLTJS.window.on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/vlt-progress-bar.default',
			VLTJS.progressBar.init
		);
	});

})(jQuery);