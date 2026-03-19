/***********************************************
 * HEDAER: DROP EFFECTS
 ***********************************************/
(function ($) {

	'use strict';

	// check if plugin defined
	if (typeof gsap == 'undefined') {
		return;
	}

	if (typeof $.fn.superclick == 'undefined') {
		return;
	}

	VLTJS.submenuEffectStyle1 = {
		config: {
			easing: 'power2.out'
		},
		init: function () {

			var effect = $('[data-submenu-effect="style-1"]'),
				$navbars = effect.find('ul.sf-menu');

			// prepend back button
			$navbars.find('ul.sub-menu').prepend('<li class="sub-menu-back"><a href="#"><span>' + VLT_LOCALIZE_DATAS.menu_back_text + '</span></a></li>');

			function _update_submenu_height($item) {

				var $nav = $item.closest(effect);
				var $sfMenu = $nav.find('ul.sf-menu');
				var $submenu = $sfMenu.find('li.menu-item-has-children.open > ul.sub-menu:not(.closed)');

				var submenuHeight = '';

				if ($submenu.length) {
					submenuHeight = $submenu.innerHeight();
				}

				$sfMenu.css({
					height: submenuHeight,
					minHeight: submenuHeight,
				});

			}

			// open / close submenu
			function _toggle_submenu(open, $submenu, clickedLink) {

				var $newItems = $submenu.find('> ul.sub-menu > li > a');
				var $oldItems = $submenu.parent().find('> li > a');

				if (open) {
					$submenu.addClass('open').parent().addClass('closed');
				} else {
					$submenu.removeClass('open').parent().removeClass('closed');

					var tmp = $newItems;
					$newItems = $oldItems;
					$oldItems = tmp;
				}

				gsap.timeline({
					defaults: {
						ease: VLTJS.submenuEffectStyle1.config.easing
					}
				})
				.to($oldItems, .2, {
					autoAlpha: 0,
					onComplete: function () {
						$oldItems.css('display', 'none');
					}
				})
				.set($newItems, {
					autoAlpha: 0,
					display: 'block',
					y: 30,
					onComplete: function () {
						_update_submenu_height(clickedLink);
					}
				})
				.to($newItems, .2, {
					y: 0,
					autoAlpha: 1,
					stagger: .03
				});

			}

			$navbars.on('click', 'li.menu-item-has-children > a', function (e) {
				_toggle_submenu(true, $(this).parent(), $(this));
				e.preventDefault();
			});

			$navbars.on('click', 'li.sub-menu-back > a', function (e) {
				_toggle_submenu(false, $(this).parent().parent().parent(), $(this));
				e.preventDefault();
			});

		}

	}

	VLTJS.submenuEffectStyle1.init();

	VLTJS.submenuEffectStyle2 = {
		config: {
			easing: 'power2.out'
		},
		init: function () {

			var effect = $('[data-submenu-effect="style-2"]'),
				$navbars = effect.find('ul.sf-menu');

			$navbars.superclick({
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

	}

	VLTJS.submenuEffectStyle2.init();

})(jQuery);