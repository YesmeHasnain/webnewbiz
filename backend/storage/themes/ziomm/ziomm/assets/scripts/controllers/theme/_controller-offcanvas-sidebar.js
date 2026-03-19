/***********************************************
 * THEME: OFFCANVAS SIDEBAR
 ***********************************************/
(function ($) {

	'use strict';

	// check if plugin defined
	if (typeof gsap == 'undefined') {
		return;
	}

	var sidebarIsOpen = false;

	VLTJS.offcanvasSidebar = {

		init: function () {

			var sidebar = $('.vlt-offcanvas-sidebar'),
				sidebarOpen = $('.js-offcanvas-sidebar-open'),
				sidebarClose = $('.js-offcanvas-sidebar-close'),
				siteOverlay = $('.vlt-site-overlay'),
				sidebarContent = sidebar.find('.vlt-offcanvas-sidebar__inner');

			sidebarOpen.on('click', function (e) {
				e.preventDefault();
				if (!sidebarIsOpen) {
					VLTJS.offcanvasSidebar.open_sidebar(sidebar, siteOverlay, sidebarContent);
				}
			});

			sidebarClose.on('click', function (e) {
				e.preventDefault();
				if (sidebarIsOpen) {
					VLTJS.offcanvasSidebar.close_sidebar(sidebar, siteOverlay, sidebarContent);
				}
			});

			siteOverlay.on('click', function (e) {
				e.preventDefault();
				if (sidebarIsOpen) {
					VLTJS.offcanvasSidebar.close_sidebar(sidebar, siteOverlay, sidebarContent);
				}
			});

			VLTJS.document.on('keyup', function (e) {
				if (e.keyCode === 27 && sidebarIsOpen) {
					e.preventDefault();
					VLTJS.offcanvasSidebar.close_sidebar(sidebar, siteOverlay, sidebarContent);
				}
			});

		},
		open_sidebar: function (sidebar, siteOverlay, sidebarContent) {

			sidebarIsOpen = true;

			gsap.set(VLTJS.html, {
				overflow: 'hidden'
			});

			gsap.to(siteOverlay, .3, {
				autoAlpha: 1
			});

			gsap.fromTo(sidebar, .6, {
				x: '100%'
			}, {
				x: 0,
				visibility: 'visible'
			});

			gsap.fromTo(sidebarContent, .3, {
				y: 30,
				opacity: 0
			}, {
				y: 0,
				delay: .6,
				opacity: 1
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
		close_sidebar: function (sidebar, siteOverlay) {

			sidebarIsOpen = false;

			gsap.set(VLTJS.html, {
				overflow: 'inherit'
			});

			gsap.to(sidebar, .3, {
				x: '100%',
				onComplete: function () {

					gsap.set(sidebar, {
						visibility: 'hidden'
					});

				}
			})

			gsap.to(siteOverlay, .3, {
				autoAlpha: 0,
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
	}

	VLTJS.offcanvasSidebar.init();

})(jQuery);