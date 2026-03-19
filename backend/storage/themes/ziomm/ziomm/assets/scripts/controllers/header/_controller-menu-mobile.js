/***********************************************
 * HEADER: MENU MOBILE
 ***********************************************/
(function ($) {

	'use strict';

	// check if plugin defined
	if (typeof $.fn.superclick == 'undefined') {
		return;
	}

	var menuIsOpen = false;

	VLTJS.menuMobile = {
		config: {
			easing: 'power2.out'
		},
		init: function () {
			var menu = $('.vlt-nav--mobile'),
				menuToggle = $('.js-mobile-menu-toggle');

			menuToggle.on('click', function (e) {
				e.preventDefault();
				if (!menuIsOpen) {
					VLTJS.menuMobile.open_menu(menu, menuToggle);
				} else {
					VLTJS.menuMobile.close_menu(menu, menuToggle);
				}
			});

			VLTJS.document.keyup(function (e) {
				if (e.keyCode === 27 && menuIsOpen) {
					e.preventDefault();
					VLTJS.menuMobile.close_menu(menu, menuToggle);
				}
			});

		},
		open_menu: function (menu, menuToggle) {

			menuIsOpen = true;
			menuToggle.addClass('vlt-menu-burger--opened');
			menuToggle.find('i').removeClass('icon-menu').addClass('icon-cross');

			menu.slideDown(300);

		},
		close_menu: function (menu, menuToggle) {

			menuIsOpen = false;
			menuToggle.removeClass('vlt-menu-burger--opened');
			menuToggle.find('i').toggleClass('icon-cross').addClass('icon-menu');

			menu.slideUp(300);

		}
	};

	VLTJS.menuMobile.init();

})(jQuery);