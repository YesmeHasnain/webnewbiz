/***********************************************
 * WIDGET: JUSTIFIED GALLERY
 ***********************************************/
(function ($) {

	'use strict';

	// check if plugin defined
	if (typeof $.fn.justifiedGallery == 'undefined') {
		return;
	}

	VLTJS.justifiedGallery = {
		init: function ($scope) {

			var justifiedGallery = $scope.find('.vlt-justified-gallery'),
				row_height = justifiedGallery.data('row-height') || 360,
				margins = justifiedGallery.data('margins') || 0;

			justifiedGallery.imagesLoaded(function () {
				justifiedGallery.justifiedGallery({
					rowHeight: row_height,
					margins: margins,
					border: 0,
					captions: false
				});
			});

		}
	}

	VLTJS.window.on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/vlt-justified-gallery.default',
			VLTJS.justifiedGallery.init
		);
	});

})(jQuery);