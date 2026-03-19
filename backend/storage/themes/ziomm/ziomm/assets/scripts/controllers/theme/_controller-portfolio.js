/***********************************************
 * THEME: PORTFOLIO
 ***********************************************/
(function ($) {

	$('.elementor-widget-visual-portfolio').addClass('elementor-widget-theme-post-content');

	VLTJS.document.on('beforeInitSwiper.vpf', function (event, vpObject, options) {

		if ('vpf' !== event.namespace) {
			return;
		}

		var setStretchToContainer = $(event.target).data('vp-slider-stretch-to-container');
		var setNavigationAnchor = $(event.target).data('vp-slider-navigation-anchor');

		if (setStretchToContainer && $('.container').length) {
			options.slidesOffsetBefore = $('.container').get(0).getBoundingClientRect().left + 15;
			options.slidesOffsetAfter = $('.container').get(0).getBoundingClientRect().left + 15;
		}

		if (setNavigationAnchor) {
			options.navigation = {
				nextEl: setNavigationAnchor + ' .vlt-swiper-button-next',
				prevEl: setNavigationAnchor + ' .vlt-swiper-button-prev'
			};

			options.pagination = {
				el: setNavigationAnchor + ' .vlt-swiper-dots',
				clickable: true
			};
		}

	});

	VLTJS.document.on('initSwiper.vpf', function (event, vpObject, options) {

		if ('vpf' !== event.namespace) {
			return;
		}

		var setNavigationAnchor = $(event.target).data('vp-slider-navigation-anchor');

		if (setNavigationAnchor) {

			var swiper = vpObject.$items_wrap.parent()[0].swiper;

			swiper.on('resize slideChange', function () {

				var el = $(setNavigationAnchor),
					current = swiper.realIndex || 0,
					total = vpObject.$items_wrap.find('.swiper-slide:not(.swiper-slide-duplicate)').length,
					scale = (current + 1) / total;

				if (el.data('direction') == 'vertical') {
					el.find('.current').text(VLTJS.addLedingZero(current + 1));
					el.find('.total').text(VLTJS.addLedingZero(total));
				} else {
					el.find('.current').text(current + 1);
					el.find('.total').text(total);
				}

				if (el.length && el.find('.bar > span').length) {
					el.find('.bar > span')[0].style.setProperty('--scaleX', scale);
					el.find('.bar > span')[0].style.setProperty('--speed', swiper.params.speed + 'ms');
				}

			});

		}

	});

	VLTJS.document.on('init.vpf endLoadingNewItems.vpf', function (e) {

		var tiltPortfolio = $(e.target).filter('[data-vp-tilt-effect="true"]'),
			portfolioStyle = tiltPortfolio.attr('data-vp-items-style'),
			expectStyles = !/^default$/g.test(portfolioStyle),
			items = tiltPortfolio.find((expectStyles ? '.vp-portfolio__item' : '.vp-portfolio__item-img') + ':not(.vp-portfolio__item-tilt)');

		if (items.length) {

			items.each(function () {
				var $this = $(this),
					meta = $this.find('.vp-portfolio__item-meta-wrap');

				if (expectStyles && meta.length) {
					$this.on('change', function (e, transforms) {
						var x = 1.5 * parseFloat(transforms.tiltX),
							y = 1.5 * -parseFloat(transforms.tiltY);
						meta.css('transform', 'translateY(' + y + 'px) translateX(' + x + 'px)');
					}).on('tilt.mouseLeave', function () {
						meta.css('transform', 'translateY(0) translateX(0)');
					});
				}

				$this.addClass('vp-portfolio__item-tilt').tilt({
					maxTilt: 5,
					speed: 1000
				});

			});

		}

	});

})(jQuery);