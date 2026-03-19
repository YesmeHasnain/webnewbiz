/***********************************************
 * THEME: ISOTOPE
 ***********************************************/
(function ($) {

	'use strict';

	// check if plugin defined
	if (typeof Isotope == 'undefined') {
		return;
	}

	VLTJS.initIsotope = {
		init: function () {

			$('.vlt-isotope-grid').each(function () {

				var $this = $(this),
					setLayout = $this.data('layout'),
					setXGap = $this.data('x-gap').split('|'),
					setYGap = $this.data('y-gap').split('|');

				function vlthemes_set_gaps(el, xGap, yGap) {

					if (VLTJS.window.width() >= 992) {

						el.css({
							'margin-top': -yGap[0] / 2 + 'px',
							'margin-right': -xGap[0] / 2 + 'px',
							'margin-bottom': -yGap[0] / 2 + 'px',
							'margin-left': -xGap[0] / 2 + 'px'
						});

						el.find('.grid-item').css({
							'padding-top': yGap[0] / 2 + 'px',
							'padding-right': xGap[0] / 2 + 'px',
							'padding-bottom': yGap[0] / 2 + 'px',
							'padding-left': xGap[0] / 2 + 'px'
						});

					} else {

						el.css({
							'margin-top': -yGap[1] / 2 + 'px',
							'margin-right': -xGap[1] / 2 + 'px',
							'margin-bottom': -yGap[1] / 2 + 'px',
							'margin-left': -xGap[1] / 2 + 'px'
						});

						el.find('.grid-item').css({
							'padding-top': yGap[1] / 2 + 'px',
							'padding-right': xGap[1] / 2 + 'px',
							'padding-bottom': yGap[1] / 2 + 'px',
							'padding-left': xGap[1] / 2 + 'px'
						});

					}

				}

				vlthemes_set_gaps($this, setXGap, setYGap);

				VLTJS.debounceResize(function () {
					vlthemes_set_gaps($this, setXGap, setYGap);
				});

				var $grid = $this.isotope({
					itemSelector: '.grid-item',
					layoutMode: setLayout,
					masonry: {
						columnWidth: '.grid-sizer'
					},
					cellsByRow: {
						columnWidth: '.grid-sizer'
					}
				});

				$grid.imagesLoaded().progress(function () {
					$grid.isotope('layout');
				});

				$grid.on('layoutComplete', function () {
					vlthemes_set_gaps($this, setXGap, setYGap);
				});

			});

		}
	}

	VLTJS.initIsotope.init();

})(jQuery);