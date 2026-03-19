/***********************************************
 * HEADER: MENU FULLSCREEN
 ***********************************************/
(function ($) {

	'use strict';

	// check if plugin defined
	if (typeof gsap == 'undefined') {
		return;
	}

	var menuIsOpen = false;

	VLTJS.menuFullscreen = {

		init: function () {

			var menu = $('.vlt-nav--fullscreen'),
				menuToggle = $('.js-fullscreen-menu-toggle'),
				navItem = menu.find('ul.sf-menu > li'),
				menuBackground = menu.find('.vlt-nav--fullscreen__background'),
				socials = $('.vlt-navbar-socials'),
				navbarContacts = $('.vlt-navbar-contacts');

			// add sep
			navbarContacts.find('li+li').before('<li class="sep"><span></span></li>');

			menuToggle.on('click', function (e) {
				e.preventDefault();
				if (!menuIsOpen) {
					menuToggle.addClass('vlt-menu-burger--opened');
					VLTJS.menuFullscreen.open_menu(menu, navItem, menuBackground, socials);
				} else {
					menuToggle.removeClass('vlt-menu-burger--opened');
					VLTJS.menuFullscreen.close_menu(menu);
				}
			});

			VLTJS.document.keyup(function (e) {
				if (e.keyCode === 27 && menuIsOpen) {
					e.preventDefault();
					VLTJS.menuFullscreen.close_menu(menu);
				}
			});

		},
		open_menu: function (menu, navItem, menuBackground, socials) {

			menuIsOpen = true;

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
		close_menu: function (menu) {

			menuIsOpen = false;

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

	VLTJS.menuFullscreen.init();

})(jQuery);