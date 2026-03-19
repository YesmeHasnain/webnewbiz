/***********************************************
 * WIDGET: COUNTDOWN
 ***********************************************/
(function ($) {

	'use strict';

	// check if plugin defined
	if (typeof $.fn.countdown === 'undefined') {
		return;
	}

	VLTJS.countdown = {
		init: function ($scope) {

			var countdown = $scope.find('.vlt-countdown'),
				due_date = countdown.data('due-date') || false;

			countdown.countdown(due_date, function (event) {
				countdown.find('[data-weeks]').html(event.strftime('%W'));
				countdown.find('[data-days]').html(event.strftime('%D'));
				countdown.find('[data-hours]').html(event.strftime('%H'));
				countdown.find('[data-minutes]').html(event.strftime('%M'));
				countdown.find('[data-seconds]').html(event.strftime('%S'));
			});

		}
	}

	VLTJS.window.on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/vlt-countdown.default',
			VLTJS.countdown.init
		);
	});

})(jQuery);