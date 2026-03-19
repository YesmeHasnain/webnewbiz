/***********************************************
 * THEME: STRETCH ELEMENT
 ***********************************************/
(function ($) {

	'use strict';

	VLTJS.stretchElement = {
		init: function () {

			var winW = VLTJS.window.outerWidth();

			$('.vlt-stretch-block').each(function () {

				var $this = $(this),
					rect = this.getBoundingClientRect(),
					offsetLeft = rect.left,
					offsetRight = winW - rect.right,
					elWidth = rect.width;

				if ($this.hasClass('to-left')) {
					$this.find('>*').css('margin-left', -offsetLeft);
					$this.find('>*').css('width', elWidth + offsetLeft + 'px');
				}

				if ($this.hasClass('to-right')) {
					$this.find('>*').css('margin-right', -offsetRight);
					$this.find('>*').css('width', elWidth + offsetRight + 'px');
				}

				if ($this.hasClass('reset-mobile') && VLTJS.window.outerWidth() <= 768) {

					$this.find('>*').css({
						'margin-left': '',
						'margin-right': '',
						'width': '100%'
					});

				}

			});

		}
	}

	VLTJS.stretchElement.init();

	VLTJS.debounceResize(function () {
		VLTJS.stretchElement.init();
	});

})(jQuery);