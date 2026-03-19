/***********************************************
 * WIDGET: ALERT MESSAGE
 ***********************************************/
(function ($) {

	'use strict';

	VLTJS.alertMessage = {
		init: function ($scope) {

			var alert = $scope.find('.vlt-alert-message');

			alert.on('click', '.vlt-alert-message__dismiss', function (e) {
				e.preventDefault();
				$scope.fadeOut(500);
			});

		}
	}

	VLTJS.window.on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/vlt-alert-message.default',
			VLTJS.alertMessage.init
		);
	});

})(jQuery);