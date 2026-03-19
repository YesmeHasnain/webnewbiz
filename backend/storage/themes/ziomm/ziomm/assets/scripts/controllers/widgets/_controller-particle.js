/***********************************************
 * WIDGET: PARTICLE
 ***********************************************/
(function ($) {

	'use strict';

	VLTJS.particleImage = {
		init: function ($scope) {

			$scope.find('.vlt-particle').each(function () {

				var $this = $(this),
					animationName = $this.data('animation-name'),
					prefix = 'animate__';

				VLTJS.window.on('vlt.site-loaded', function () {

					$this.one('inview', function () {
						$this.addClass(prefix + 'animated').addClass(prefix + animationName);
					});

				});

			});

		}
	};

	VLTJS.particleImage.init(VLTJS.body);

})(jQuery);