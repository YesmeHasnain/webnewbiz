/***********************************************
 * HEADER: MENU SLIDE
 ***********************************************/

(function ($) {

	'use strict';

	// check if plugin defined
	if (typeof gsap == 'undefined') {
		return;
	}

	var menuIsOpen = false;

	VLTJS.menuSlide = {
		init: function () {

			var menu = $('.vlt-nav--slide'),
				menuToggle = $('.js-slide-menu-toggle'),
				navItem = menu.find('ul.sf-menu > li'),
				menuBackground = menu.find('.vlt-nav--slide__background'),
				socials = menu.find('.vlt-navbar-socials');

			menuToggle.on('click', function (e) {
				e.preventDefault();
				if (!menuIsOpen) {
					VLTJS.menuSlide.open_menu(menuToggle, menu, navItem, menuBackground, socials);
				} else {
					VLTJS.menuSlide.close_menu(menuToggle, menu, menuBackground);
				}
			});

			VLTJS.document.keyup(function (e) {
				if (e.keyCode === 27 && menuIsOpen) {
					e.preventDefault();
					VLTJS.menuSlide.close_menu(menuToggle, menu, menuBackground);
				}
			});

		},
		open_menu: function (menuToggle, menu, navItem, menuBackground, socials) {

			menuIsOpen = true;
			menuToggle.addClass('vlt-menu-burger--opened');
			menuToggle.find('i').removeClass('icon-menu').addClass('icon-cross');

			gsap.set(VLTJS.html, {
				overflow: 'hidden'
			});

			gsap.to(menu, .6, {
				autoAlpha: 1
			});

			gsap.fromTo(menuBackground, .6, {
				scale: 1
			}, {
				scale: 1.05
			});

			gsap.fromTo(navItem, .3, {
				autoAlpha: 0,
				y: 30
			}, {
				autoAlpha: 1,
				y: 0,
				stagger: {
					amount: .3
				}
			});

			gsap.fromTo(socials, .3, {
				autoAlpha: 0,
				y: 30
			}, {
				autoAlpha: 1,
				y: 0,
				delay: .3
			});

			if (VLT_LOCALIZE_DATAS.open_click_sound && typeof Howl !== 'undefined') {

				new Howl({
					src: [VLT_LOCALIZE_DATAS.open_click_sound],
					autoplay: true,
					loop: false,
					volume: 0.3
				});

			}

		},
		close_menu: function (menuToggle, menu) {

			menuIsOpen = false;
			menuToggle.removeClass('vlt-menu-burger--opened');
			menuToggle.find('i').toggleClass('icon-cross').addClass('icon-menu');

			gsap.set(VLTJS.html, {
				overflow: 'inherit'
			});

			gsap.to(menu, .3, {
				autoAlpha: 0
			});

			if (typeof VLT_LOCALIZE_DATAS.close_click_sound && typeof Howl !== 'undefined') {

				new Howl({
					src: [VLT_LOCALIZE_DATAS.close_click_sound],
					autoplay: true,
					loop: false,
					volume: 0.3
				});

			}

		}
	};

	VLTJS.menuSlide.init();

})(jQuery);