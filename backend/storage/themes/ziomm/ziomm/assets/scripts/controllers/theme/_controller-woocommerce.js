/***********************************************
 * THEME: WOOCOMMERCE
 ***********************************************/
(function ($) {

	'use strict';

	VLTJS.wooCommerce = {
		init: function () {

			VLTJS.document.on('click', '.vlt-quantity .plus, .vlt-quantity .minus', function () {
				var $this = $(this),
					$qty = $this.siblings('.qty'),
					current = parseInt($qty.val(), 10),
					min = parseInt($qty.attr('min'), 10),
					max = parseInt($qty.attr('max'), 10),
					step = parseInt($qty.attr('step'), 10);

				min = min ? min : 1;
				max = max ? max : current + step;

				if ($this.hasClass('minus') && current > min) {
					$qty.val(current - step);
					$qty.trigger('change');
				}

				if ($this.hasClass('plus') && current < max) {
					$qty.val(current + step);
					$qty.trigger('change');
				}

				return false;
			});

		}
	}

	VLTJS.wooCommerce.init();

})(jQuery);