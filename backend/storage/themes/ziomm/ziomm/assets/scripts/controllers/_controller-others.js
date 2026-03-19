/***********************************************
 * INIT THIRD PARTY SCRIPTS
 ***********************************************/
(function ($) {

	'use strict';

	/**
	 * Remove overflow for sticky
	 */
	if ($('.sticky-column, .has-sticky-column').length) {
		$('.vlt-main').css('overflow', 'inherit');
	}

	/**
	 * Add nofollow to child menu link
	 */
	$('.menu-item-has-children>a').attr('rel', 'nofollow');

	/**
	 * Widget menu
	 */
	if (typeof $.fn.superclick !== 'undefined') {

		$('.widget_pages > ul, .widget_nav_menu ul.menu').superclick({
			delay: 300,
			cssArrows: false,
			animation: {
				opacity: 'show',
				height: 'show'
			},
			animationOut: {
				opacity: 'hide',
				height: 'hide'
			},
		});

	}

	/**
	 * Jarallax
	 */
	if (typeof $.fn.jarallax !== 'undefined') {

		$('.jarallax, .elementor-section.jarallax, .elementor-column.jarallax>.elementor-column-wrap').jarallax({
			speed: 0.8
		});

	}

	/**
	 * Fitvids
	 */
	if (typeof $.fn.fitVids !== 'undefined') {
		VLTJS.body.fitVids();
	}

	/**
	 * Lax
	 */
	if (typeof lax !== 'undefined') {

		VLTJS.body.imagesLoaded(function () {

			lax.setup();

			const updateLax = function () {
				lax.update(window.scrollY);
				window.requestAnimationFrame(updateLax);
			}

			window.requestAnimationFrame(updateLax);

			VLTJS.debounceResize(function () {
				lax.updateElements();
			});

		});

	}

	/**
	 * AOS animation
	 */
	if (typeof AOS !== 'undefined') {
		function aos() {

			AOS.init({
				disable: 'mobile',
				offset: 120,
				once: true,
				duration: 1000,
				easing: 'ease',
			});

			function aos_refresh() {
				AOS.refresh();
			}

			aos_refresh();
			VLTJS.debounceResize(aos_refresh);

		}
		VLTJS.window.one('vlt.site-loaded', aos);
	}

	/**
	 * Back button
	 */
	$('.btn-go-back').on('click', function (e) {
		e.preventDefault();
		window.history.back();
	});

	/**
	 * Fancybox
	 */
	if (typeof $.fn.fancybox !== 'undefined') {
		$.fancybox.defaults.btnTpl = {
			close: '<button data-fancybox-close class="fancybox-button fancybox-button--close">' +
				'<i class="icon-cross"></i>' +
				'</button>',
			arrowLeft: '<a data-fancybox-prev class="fancybox-button fancybox-button--arrow_left" href="javascript:;">' +
				'<i class="icon-arrow-left"></i>' +
				'</a>',
			arrowRight: '<a data-fancybox-next class="fancybox-button fancybox-button--arrow_right" href="javascript:;">' +
				'<i class="icon-arrow-right"></i>' +
				'</a>',
			smallBtn: '<button type="button" data-fancybox-close class="fancybox-button fancybox-close-small">' +
				'<i class="icon-cross"></i>' +
				'</button>'
		};
		$.fancybox.defaults.buttons = [
			'close'
		];
		$.fancybox.defaults.infobar = false;
		$.fancybox.defaults.transitionEffect = 'slide';
	}

	/**
	 * Material input
	 */
	if ($('.vlt-form-group').length) {

		$('.vlt-form-group .vlt-form-control').each(function () {
			if ($(this).val().length > 0 || $(this).attr('placeholder') !== undefined) {
				$(this).closest('.vlt-form-group').addClass('active');
			}
		});

		$('.vlt-form-group .vlt-form-control').on({
			mouseenter: function () {
				$(this).closest('.vlt-form-group').addClass('active');
			},
			mouseleave: function () {
				if ($(this).val() == '' && $(this).attr('placeholder') == undefined && !$(this).is(':focus')) {
					$(this).closest('.vlt-form-group').removeClass('active');
				}
			}
		});

		$('.vlt-form-group .vlt-form-control').focus(function () {
			$(this).closest('.vlt-form-group').addClass('active');
		});

		$('.vlt-form-group .vlt-form-control').blur(function () {
			if ($(this).val() == '' && $(this).attr('placeholder') == undefined) {
				$(this).closest('.vlt-form-group').removeClass('active');
			}
		});

		$('.vlt-form-group .vlt-form-control').change(function () {
			if ($(this).val() == '' && $(this).attr('placeholder') == undefined) {
				$(this).closest('.vlt-form-group').removeClass('active');
			} else {
				$(this).closest('.vlt-form-group').addClass('active');
			}
		});

	}

})(jQuery);