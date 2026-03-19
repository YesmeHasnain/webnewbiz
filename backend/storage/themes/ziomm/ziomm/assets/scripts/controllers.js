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
      }
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
      };

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
        easing: 'ease'
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
      close: '<button data-fancybox-close class="fancybox-button fancybox-button--close">' + '<i class="icon-cross"></i>' + '</button>',
      arrowLeft: '<a data-fancybox-prev class="fancybox-button fancybox-button--arrow_left" href="javascript:;">' + '<i class="icon-arrow-left"></i>' + '</a>',
      arrowRight: '<a data-fancybox-next class="fancybox-button fancybox-button--arrow_right" href="javascript:;">' + '<i class="icon-arrow-right"></i>' + '</a>',
      smallBtn: '<button type="button" data-fancybox-close class="fancybox-button fancybox-close-small">' + '<i class="icon-cross"></i>' + '</button>'
    };
    $.fancybox.defaults.buttons = ['close'];
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
/***********************************************
 * HEDAER: DROP EFFECTS
 ***********************************************/
(function ($) {
  'use strict'; // check if plugin defined

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
          $navbars = effect.find('ul.sf-menu'); // prepend back button

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
          minHeight: submenuHeight
        });
      } // open / close submenu


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
        }).to($oldItems, .2, {
          autoAlpha: 0,
          onComplete: function () {
            $oldItems.css('display', 'none');
          }
        }).set($newItems, {
          autoAlpha: 0,
          display: 'block',
          y: 30,
          onComplete: function () {
            _update_submenu_height(clickedLink);
          }
        }).to($newItems, .2, {
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
  };
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
        }
      });
    }
  };
  VLTJS.submenuEffectStyle2.init();
})(jQuery);
/***********************************************
 * HEDAER: MENU DEFAULT
 ***********************************************/
(function ($) {
  'use strict'; // check if plugin defined

  if (typeof $.fn.superfish == 'undefined') {
    return;
  }

  VLTJS.menuDefault = {
    init: function () {
      var menu = $('.vlt-nav--default'),
          navigation = menu.find('ul.sf-menu'),
          correctDropdownTrigger = menu.parents('.container');
      navigation.superfish({
        popUpSelector: 'ul.sub-menu',
        delay: 0,
        speed: 300,
        speedOut: 300,
        cssArrows: false,
        animation: {
          opacity: 'show',
          marginTop: '0',
          visibility: 'visible'
        },
        animationOut: {
          opacity: 'hide',
          marginTop: '10px',
          visibility: 'hidden'
        }
      });

      function correctDropdownPosition($item) {
        $item.removeClass('left');
        var $dropdown = $item.children('ul.sub-menu');

        if ($dropdown.length) {
          var rect = $dropdown[0].getBoundingClientRect();

          if (rect.left + rect.width > correctDropdownTrigger.width()) {
            $item.addClass('left');
          }
        }
      }

      navigation.on('mouseenter', 'li.menu-item-has-children', function () {
        correctDropdownPosition($(this));
      });
    }
  };
  VLTJS.menuDefault.init();
})(jQuery);
/***********************************************
 * HEADER: MENU FULLSCREEN
 ***********************************************/
(function ($) {
  'use strict'; // check if plugin defined

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
          navbarContacts = $('.vlt-navbar-contacts'); // add sep

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
/***********************************************
 * HEADER: MENU MOBILE
 ***********************************************/
(function ($) {
  'use strict'; // check if plugin defined

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
/***********************************************
 * HEADER: MENU SLIDE
 ***********************************************/
(function ($) {
  'use strict'; // check if plugin defined

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
/***********************************************
 * THEME: BLOG
 ***********************************************/
(function ($) {
  'use strict'; // check if plugin defined

  if (typeof Swiper === 'undefined') {
    return;
  }

  VLTJS.blog = {
    init: function () {
      VLTJS.blog.postFormatGallerySlider();
    },
    postFormatGallerySlider: function () {
      $('.vlt-post-media__gallery').each(function () {
        var gap = $(this).data('gap') || 0;
        new Swiper($(this).find('.swiper-container').get(0), {
          loop: true,
          spaceBetween: gap,
          // centeredSlides: true,
          autoplay: {
            delay: 5000
          },
          slidesPerView: 1,
          loopAdditionalSlides: 1,
          grabCursor: true,
          speed: 600,
          mousewheel: false,
          navigation: {
            nextEl: $(this).find('.vlt-swiper-button-next').get(0),
            prevEl: $(this).find('.vlt-swiper-button-prev').get(0)
          }
        });
      });
    }
  };
  VLTJS.blog.init();
})(jQuery);
/***********************************************
 * THEME: ELEMENTOR COLUMN
 ***********************************************/
(function ($) {
  'use strict';

  VLTJS.stickyColumn = {
    init: function ($scope) {
      const $parent = $scope.filter('.has-sticky-column');

      if ($parent.length) {
        $parent.find('>.elementor-widget-wrap').addClass('sticky-parent').find('>.elementor-element').wrap('<div class="sticky-column">');
      }
    }
  };
  VLTJS.window.on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/column', VLTJS.stickyColumn.init);
  });
})(jQuery);
/***********************************************
 * THEME: FIXED FOOTER
 ***********************************************/
(function ($) {
  'use strict'; // check if plugin defined

  if (typeof gsap == 'undefined') {
    return;
  }

  VLTJS.fixedFooterEffect = {
    init: function () {
      var footer = $('.vlt-footer'),
          main = $('.vlt-main'),
          footerBlackShape = footer.find('.elementor-section.has-black-top-shape'),
          footerWhiteShape = footer.find('.elementor-section.has-white-top-shape');

      if ((footerBlackShape.length || footerWhiteShape.length) && !$('.vlt-is--footer-shape-disable').length) {
        main.append('<div class="vlt-main__footer-shape"></div>');
      }

      if (footerBlackShape.length && VLT_LOCALIZE_DATAS.shape_black) {
        $('.vlt-main__footer-shape').css({
          'background-image': 'url(' + VLT_LOCALIZE_DATAS.shape_black + ')'
        });
      }

      if (footerWhiteShape.length && VLT_LOCALIZE_DATAS.shape_white) {
        $('.vlt-main__footer-shape').css({
          'background-image': 'url(' + VLT_LOCALIZE_DATAS.shape_white + ')'
        });
      }
    },
    fixedFooter: function () {
      var footer = $('.vlt-footer.vlt-footer--fixed'),
          delta = .75,
          translateY = 0;

      if (footer.length && VLTJS.window.outerWidth() >= 768) {
        VLTJS.window.on('load scroll', function () {
          var footerHeight = footer.outerHeight(),
              windowHeight = VLTJS.window.outerHeight(),
              documentHeight = VLTJS.document.outerHeight();
          translateY = delta * (Math.max(0, $(this).scrollTop() + windowHeight - (documentHeight - footerHeight)) - footerHeight);
        });
        gsap.ticker.add(function () {
          gsap.set(footer, {
            translateY: translateY,
            translateZ: 0
          });
        });
      }
    }
  };

  if (!VLTJS.isMobile.any()) {
    VLTJS.fixedFooterEffect.fixedFooter();
    VLTJS.debounceResize(function () {
      VLTJS.fixedFooterEffect.fixedFooter();
    });
  }

  VLTJS.fixedFooterEffect.init();
})(jQuery);
/***********************************************
 * THEME: FOLLOW INFO
 ***********************************************/
(function ($) {
  'use strict';

  VLTJS.followInfo = {
    init: function ($scope) {
      if (!$('.vlt-follow-info').length) {
        VLTJS.body.append('\
			<div class="vlt-follow-info">\
			<div class="vlt-follow-info__title"></div><br>\
			<div class="vlt-follow-info__subtitle"></div>\
			</div>\
			');
      }

      var getFollowInfo = $scope.find('[data-follow-info]'),
          followInfo = $('.vlt-follow-info'),
          title = followInfo.find('.vlt-follow-info__title'),
          subtitle = followInfo.find('.vlt-follow-info__subtitle');
      getFollowInfo.each(function () {
        var currentItem = $(this);
        currentItem.on('mousemove', function (e) {
          followInfo.css({
            top: e.clientY,
            left: e.clientX
          });
        });
        currentItem.on({
          mouseenter: function () {
            var $this = $(this),
                title_text = $this.find('[data-follow-info-title]').html(),
                subtitle_text = $this.find('[data-follow-info-content]').html();

            if (!followInfo.hasClass('is-active')) {
              followInfo.addClass('is-active');
              title.html(title_text).wrapInner('<h4>');
              subtitle.html(subtitle_text).wrapInner('<span>');
            }
          },
          mouseleave: function () {
            if (followInfo.hasClass('is-active')) {
              followInfo.removeClass('is-active');
              title.html('');
              subtitle.html('');
            }
          }
        });
      });
    }
  };
  VLTJS.followInfo.init(VLTJS.body);
  VLTJS.document.on('init.vpf endLoadingNewItems.vpf', function (e) {
    VLTJS.followInfo.init(VLTJS.body);
  });
})(jQuery);
/***********************************************
 * THEME: ISOTOPE
 ***********************************************/
(function ($) {
  'use strict'; // check if plugin defined

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
  };
  VLTJS.initIsotope.init();
})(jQuery);
/***********************************************
 * THEME: OFFCANVAS SIDEBAR
 ***********************************************/
(function ($) {
  'use strict'; // check if plugin defined

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
      });
      gsap.to(siteOverlay, .3, {
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
  VLTJS.offcanvasSidebar.init();
})(jQuery);
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
/***********************************************
 * THEME: SCROLL TO SECTION
 ***********************************************/
(function ($) {
  'use strict'; // check if plugin defined

  if (typeof $.fn.scrollTo === 'undefined') {
    return;
  }

  $('a[href^="#"]').not('[href="#"]').not('[rel="nofollow"]').on('click', function (e) {
    e.preventDefault();
    VLTJS.html.scrollTo($(this).attr('href'), 500);
  });
})(jQuery);
/***********************************************
 * THEME: SCROLL TO TOP
 ***********************************************/


(function ($) {
  'use strict'; // check if plugin defined

  if (typeof $.fn.scrollTo === 'undefined') {
    return;
  }

  var backToTopBtn = $('.vlt-btn--back-to-top'),
      shopCartIcon = $('.vlt-shop-cart-icon');

  function _show_btn() {
    backToTopBtn.removeClass('is-hidden').addClass('is-visible');
    shopCartIcon.addClass('is-active');
  }

  function _hide_btn() {
    backToTopBtn.removeClass('is-visible').addClass('is-hidden');
    shopCartIcon.removeClass('is-active');
  }

  _hide_btn();

  VLTJS.throttleScroll(function (type, scroll) {
    var offset = VLTJS.window.outerHeight() + 100;

    if (scroll > offset) {
      if (type === 'down') {
        _hide_btn();
      } else if (type === 'up') {
        _show_btn();
      }
    } else {
      _hide_btn();
    }
  });
  backToTopBtn.on('click', function (e) {
    e.preventDefault();
    VLTJS.html.scrollTo(0, 500);
  });
})(jQuery);
/***********************************************
 * THEME: PRELOADER
 ***********************************************/
(function ($) {
  'use strict'; // check if plugin defined

  if (typeof $.fn.animsition == 'undefined') {
    VLTJS.window.trigger('vlt.site-loaded');
    VLTJS.html.addClass('vlt-is-page-loaded');
    return;
  }

  var preloader = $('.animsition'),
      preloaderStyle = VLTJS.body.data('animsition-style'),
      //animsition-bounce, animsition-image
  loadingInner;

  switch (preloaderStyle) {
    case 'animsition-bounce':
      loadingInner = '<span class="double-bounce-one"></span><span class="double-bounce-two"></span>';
      break;

    case 'animsition-image':
      if (VLT_LOCALIZE_DATAS.preloader_image) {
        loadingInner = '<img src="' + VLT_LOCALIZE_DATAS.preloader_image + '" alt="preloader">';
      }

      break;
  }

  if (preloader.length) {
    preloader.animsition({
      inDuration: 500,
      outDuration: 500,
      loadingParentElement: 'html',
      linkElement: 'a:not(.remove):not(.vp-pagination__load-more):not(.elementor-accordion-title):not([href="javascript:;"]):not([role="slider"]):not([data-elementor-open-lightbox]):not([data-fancybox]):not([data-vp-filter]):not([target="_blank"]):not([href^="#"]):not([rel="nofollow"]):not([href~="#"]):not([href^=mailto]):not([href^=tel]):not(.sf-with-ul):not(.elementor-toggle-title)',
      loadingClass: preloaderStyle,
      loadingInner: loadingInner
    });
    preloader.on('animsition.inEnd', function () {
      VLTJS.window.trigger('vlt.site-loaded');
      VLTJS.html.addClass('vlt-is-page-loaded');
    });
  } else {
    VLTJS.window.trigger('vlt.site-loaded');
    VLTJS.html.addClass('vlt-is-page-loaded');
  }
})(jQuery);
/***********************************************
 * THEME: SITE PROTECTION
 ***********************************************/
(function ($) {
  'use strict';

  if (!VLTJS.html.hasClass('vlt-is--site-protection')) {
    return;
  }

  var isClicked = false;
  VLTJS.document.bind('contextmenu', function (e) {
    e.preventDefault();

    if (!isClicked) {
      $('.vlt-site-protection').addClass('is-visible');
      VLTJS.body.addClass('is-right-clicked');
      isClicked = true;
    }

    VLTJS.document.on('mousedown', function () {
      $('.vlt-site-protection').removeClass('is-visible');
      VLTJS.body.removeClass('is-right-clicked');
      isClicked = false;
    });
    isClicked = false;
  });
})(jQuery);
/***********************************************
 * THEME: STICKY NAVBAR
 ***********************************************/
(function ($) {
  'use strict';

  VLTJS.stickyNavbar = {
    init: function () {
      var navbarMain = $('.vlt-header:not(.vlt-header--slide) .vlt-navbar--main');
      navbarMain.each(function () {
        var currentNavbar = $(this); // sticky navbar

        var navbarHeight = currentNavbar.length ? currentNavbar.outerHeight() : 0,
            navbarMainOffset = currentNavbar.hasClass('vlt-navbar--offset') ? VLTJS.window.outerHeight() : navbarHeight; // fake navbar

        var navbarFake = $('<div class="vlt-fake-navbar">').hide();

        function _fixed_navbar() {
          currentNavbar.addClass('vlt-navbar--fixed');
          navbarFake.show();
        }

        function _unfixed_navbar() {
          currentNavbar.removeClass('vlt-navbar--fixed');
          navbarFake.hide();
        }

        function _on_scroll_navbar() {
          if (VLTJS.window.scrollTop() >= navbarMainOffset) {
            _fixed_navbar();
          } else {
            _unfixed_navbar();
          }
        }

        if (currentNavbar.hasClass('vlt-navbar--sticky')) {
          VLTJS.window.on('scroll resize', _on_scroll_navbar);

          _on_scroll_navbar(); // append fake navbar


          currentNavbar.after(navbarFake); // fake navbar height after resize

          navbarFake.height(currentNavbar.innerHeight());
          VLTJS.debounceResize(function () {
            navbarFake.height(currentNavbar.innerHeight());
          });
        } // hide navbar on scroll


        var navbarHideOnScroll = currentNavbar.filter('.vlt-navbar--hide-on-scroll');
        VLTJS.throttleScroll(function (type, scroll) {
          var start = 450;

          function _show_navbar() {
            navbarHideOnScroll.removeClass('vlt-on-scroll-hide').addClass('vlt-on-scroll-show');
          }

          function _hide_navbar() {
            navbarHideOnScroll.removeClass('vlt-on-scroll-show').addClass('vlt-on-scroll-hide');
          } // hide or show


          if (type === 'down' && scroll > start) {
            _hide_navbar();
          } else if (type === 'up' || type === 'end' || type === 'start') {
            _show_navbar();
          } // add solid color


          if (currentNavbar.hasClass('vlt-navbar--transparent') && currentNavbar.hasClass('vlt-navbar--sticky')) {
            scroll > navbarHeight ? currentNavbar.addClass('vlt-navbar--solid') : currentNavbar.removeClass('vlt-navbar--solid');
          } // sticky column fix


          if (currentNavbar.hasClass('vlt-navbar--fixed') && currentNavbar.hasClass('vlt-navbar--sticky') && !currentNavbar.hasClass('vlt-on-scroll-hide')) {
            VLTJS.html.addClass('vlt-is--header-fixed');
          } else {
            VLTJS.html.removeClass('vlt-is--header-fixed');
          }
        });
      });
    }
  };
  VLTJS.stickyNavbar.init();
})(jQuery);
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
  };
  VLTJS.stretchElement.init();
  VLTJS.debounceResize(function () {
    VLTJS.stretchElement.init();
  });
})(jQuery);
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
  };
  VLTJS.wooCommerce.init();
})(jQuery);
/***********************************************
 * WIDGET: ALERT MESSAGE
 ***********************************************/
(function ($) {
  'use strict';

  VLTJS.alertMessage = {
    init: function ($scope) {
      var alert = $scope.find('.vlt-alert-message');
      alert.on('click', '.vlt-alert-message__dismiss', function (e) {
        e.preventDefault();
        $scope.fadeOut(500);
      });
    }
  };
  VLTJS.window.on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/vlt-alert-message.default', VLTJS.alertMessage.init);
  });
})(jQuery);
/***********************************************
 * WIDGET: BUTTON
 ***********************************************/
(function ($) {
  'use strict';

  VLTJS.button = {
    init: function ($scope) {
      var button = $scope.find('.vlt-btn.vlt-btn--effect');

      if (!button.find('span.blind').length) {
        button.append('<span class="blind">');
      }

      button.on('mouseenter', function (e) {
        var $this = $(this),
            parentOffset = $this.offset(),
            relX = e.pageX - parentOffset.left,
            relY = e.pageY - parentOffset.top;
        $this.find('.blind').css({
          top: relY,
          left: relX
        });
      }).on('mouseout', function (e) {
        var $this = $(this),
            parentOffset = $this.offset(),
            relX = e.pageX - parentOffset.left,
            relY = e.pageY - parentOffset.top;
        $this.find('.blind').css({
          top: relY,
          left: relX
        });
      });
    }
  };
  VLTJS.window.on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/vlt-button.default', VLTJS.button.init);
    elementorFrontend.hooks.addAction('frontend/element_ready/vlt-showcase-2.default', VLTJS.button.init);
    elementorFrontend.hooks.addAction('frontend/element_ready/vlt-showcase-6.default', VLTJS.button.init);
    elementorFrontend.hooks.addAction('frontend/element_ready/vlt-contact-form-7.default', VLTJS.button.init);
    elementorFrontend.hooks.addAction('frontend/element_ready/slider_revolution.default', VLTJS.button.init);
  });
  VLTJS.button.init(VLTJS.body);
  VLTJS.document.on('init.vpf endLoadingNewItems.vpf', function (e) {
    VLTJS.button.init(VLTJS.body);
  });
})(jQuery);
/***********************************************
 * WIDGET: CONTENT SLIDER
 ***********************************************/
(function ($) {
  'use strict'; // check if plugin defined

  if (typeof Swiper === 'undefined') {
    return;
  }

  VLTJS.contentSlider = {
    init: function ($scope) {
      var slider = $scope.find('.vlt-content-slider'),
          container = slider.find('.swiper-container'),
          anchor = slider.data('navigation-anchor'),
          gap = slider.data('gap') || 0,
          loop = slider.data('loop') == 'yes' ? true : false,
          speed = slider.data('speed') || 1000,
          autoplay = slider.data('autoplay') == 'yes' ? true : false,
          centered = slider.data('slides-centered') == 'yes' ? true : false,
          freemode = slider.data('free-mode') == 'yes' ? true : false,
          slider_offset = slider.data('slider-offset') == 'yes' ? true : false,
          mousewheel = slider.data('mousewheel') == 'yes' ? true : false,
          autoplay_speed = slider.data('autoplay-speed'),
          settings = slider.data('slide-settings');

      if (container.length) {
        var swiper = new Swiper(container.get(0), {
          init: false,
          spaceBetween: gap,
          grabCursor: true,
          initialSlide: settings.initial_slide ? settings.initial_slide : 0,
          loop: loop,
          speed: speed,
          centeredSlides: centered,
          freeMode: freemode,
          mousewheel: mousewheel,
          autoplay: autoplay ? {
            delay: autoplay_speed,
            disableOnInteraction: false
          } : false,
          autoHeight: true,
          slidesOffsetBefore: slider_offset ? $('.container').get(0).getBoundingClientRect().left + 15 : false,
          slidesOffsetAfter: slider_offset ? $('.container').get(0).getBoundingClientRect().left + 15 : false,
          navigation: {
            nextEl: $(anchor).find('.vlt-swiper-button-next').get(0),
            prevEl: $(anchor).find('.vlt-swiper-button-prev').get(0)
          },
          pagination: {
            el: $(anchor).find('.vlt-swiper-dots').get(0),
            clickable: true
          },
          breakpoints: {
            // when window width is >= 576px
            576: {
              slidesPerView: settings.slides_to_show_mobile || settings.slides_to_show_tablet || settings.slides_to_show || 1,
              slidesPerGroup: settings.slides_to_scroll_mobile || settings.slides_to_scroll_tablet || settings.slides_to_scroll || 1
            },
            // when window width is >= 768px
            768: {
              slidesPerView: settings.slides_to_show_tablet || settings.slides_to_show || 1,
              slidesPerGroup: settings.slides_to_scroll_tablet || settings.slides_to_scroll || 1
            },
            // when window width is >= 992px
            992: {
              slidesPerView: settings.slides_to_show || 1,
              slidesPerGroup: settings.slides_to_scroll || 1
            }
          }
        });
        swiper.init();
      }
    }
  };
  VLTJS.window.on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/vlt-content-slider.default', VLTJS.contentSlider.init);
  });
})(jQuery);
/***********************************************
 * WIDGET: COUNTDOWN
 ***********************************************/
(function ($) {
  'use strict'; // check if plugin defined

  if (typeof $.fn.countdown === 'undefined') {
    return;
  }

  VLTJS.countdown = {
    init: function ($scope) {
      var countdown = $scope.find('.vlt-countdown'),
          due_date = countdown.data('due-date') || false;
      countdown.countdown(due_date, function (event) {
        countdown.find('[data-weeks]').html(event.strftime('%W'));
        countdown.find('[data-days]').html(event.strftime('%D'));
        countdown.find('[data-hours]').html(event.strftime('%H'));
        countdown.find('[data-minutes]').html(event.strftime('%M'));
        countdown.find('[data-seconds]').html(event.strftime('%S'));
      });
    }
  };
  VLTJS.window.on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/vlt-countdown.default', VLTJS.countdown.init);
  });
})(jQuery);
/***********************************************
 * WIDGET: COUNTER UP
 ***********************************************/
(function ($) {
  'use strict'; // check if plugin defined

  if (typeof $.fn.numerator == 'undefined') {
    return;
  }

  VLTJS.counterUp = {
    init: function ($scope) {
      var counterUp = $scope.find('.vlt-counter-up'),
          animation_duration = counterUp.data('animation-speed') || 1000,
          ending_number = counterUp.find('.counter'),
          ending_number_value = ending_number.text(),
          delimiter = counterUp.data('delimiter') ? counterUp.data('delimiter') : ',';

      if (counterUp.hasClass('vlt-counter-up--style-2')) {
        ending_number.css({
          'min-width': ending_number.outerWidth() + 'px'
        });
      }

      counterUp.one('inview', function () {
        ending_number.text('0');
        ending_number.numerator({
          easing: 'linear',
          duration: animation_duration,
          delimiter: delimiter,
          toValue: ending_number_value
        });
      });
    }
  };
  VLTJS.window.on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/vlt-counter-up.default', VLTJS.counterUp.init);
  });
})(jQuery);
/***********************************************
 * WIDGET: FANCY TEXT
 ***********************************************/
(function ($) {
  'use strict'; // check if plugin defined

  if (typeof Typed == 'undefined') {
    return;
  } // check if plugin defined


  if (typeof $.fn.Morphext == 'undefined') {
    return;
  }

  VLTJS.fancyText = {
    init: function ($scope) {
      var fancyText = $scope.find('.vlt-fancy-text'),
          strings = fancyText.find('.strings'),
          fancy_text = fancyText.data('fancy-text') || '',
          fancy_text = fancy_text.split('|'),
          animation_type = fancyText.data('animation-type') || '',
          typing_speed = fancyText.data('typing-speed') || '',
          delay = fancyText.data('delay') || '',
          type_cursor = fancyText.data('type-cursor') == 'yes' ? true : false,
          type_cursor_symbol = fancyText.data('type-cursor-symbol') || '|',
          typing_loop = fancyText.data('typing-loop') == 'yes' ? true : false;

      if (animation_type == 'typing') {
        new Typed(strings.get(0), {
          strings: fancy_text,
          typeSpeed: typing_speed,
          backSpeed: 0,
          startDelay: 300,
          backDelay: delay,
          showCursor: type_cursor,
          cursorChar: type_cursor_symbol,
          loop: typing_loop
        });
      } else {
        strings.show().Morphext({
          animation: animation_type,
          separator: ', ',
          speed: delay,
          complete: function () {// Overrides default empty function
          }
        });
      }
    }
  };
  VLTJS.window.on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/vlt-fancy-text.default', VLTJS.fancyText.init);
  });
})(jQuery);
/***********************************************
 * WIDGET: GOOGLE MAP
 ***********************************************/
(function ($) {
  'use strict';

  VLTJS.googleMap = {
    init: function ($scope) {
      var googleMap = $scope.find('.vlt-google-map'),
          map_lat = googleMap.data('map-lat'),
          map_lng = googleMap.data('map-lng'),
          map_zoom = googleMap.data('map-zoom'),
          map_gesture_handling = googleMap.data('map-gesture-handling'),
          map_zoom_control = googleMap.data('map-zoom-control') ? true : false,
          map_zoom_control_position = googleMap.data('map-zoom-control-position'),
          map_default_ui = googleMap.data('map-default-ui') ? false : true,
          map_type = googleMap.data('map-type'),
          map_type_control = googleMap.data('map-type-control') ? true : false,
          map_type_control_style = googleMap.data('map-type-control-style'),
          map_type_control_position = googleMap.data('map-type-control-position'),
          map_streetview_control = googleMap.data('map-streetview-control') ? true : false,
          map_streetview_position = googleMap.data('map-streetview-position'),
          map_info_window_width = googleMap.data('map-info-window-width'),
          map_locations = googleMap.data('map-locations'),
          map_styles = googleMap.data('map-style') || '',
          infowindow,
          map;

      function initMap() {
        var myLatLng = {
          lat: parseFloat(map_lat),
          lng: parseFloat(map_lng)
        };

        if (typeof google === 'undefined') {
          return;
        }

        var map = new google.maps.Map(googleMap[0], {
          center: myLatLng,
          zoom: map_zoom,
          disableDefaultUI: map_default_ui,
          zoomControl: map_zoom_control,
          zoomControlOptions: {
            position: google.maps.ControlPosition[map_zoom_control_position]
          },
          mapTypeId: map_type,
          mapTypeControl: map_type_control,
          mapTypeControlOptions: {
            style: google.maps.MapTypeControlStyle[map_type_control_style],
            position: google.maps.ControlPosition[map_type_control_position]
          },
          streetViewControl: map_streetview_control,
          streetViewControlOptions: {
            position: google.maps.ControlPosition[map_streetview_position]
          },
          styles: map_styles,
          gestureHandling: map_gesture_handling
        });
        $.each(map_locations, function (index, googleMapement, content) {
          var content = '\
					<div class="vlt-google-map__container">\
					<h6>' + googleMapement.title + '</h6>\
					<div>' + googleMapement.text + '</div>\
					</div>';
          var icon = '';

          if (googleMapement.pin_icon !== '') {
            if (googleMapement.pin_icon_custom) {
              icon = googleMapement.pin_icon_custom;
            } else {
              icon = '';
            }
          }

          var marker = new google.maps.Marker({
            map: map,
            position: new google.maps.LatLng(parseFloat(googleMapement.lat), parseFloat(googleMapement.lng)),
            animation: google.maps.Animation.DROP,
            icon: icon
          });

          if (googleMapement.title !== '' && googleMapement.text !== '') {
            addInfoWindow(marker, content);
          }

          google.maps.event.addListener(marker, 'click', toggleBounce);

          function toggleBounce() {
            if (marker.getAnimation() != null) {
              marker.setAnimation(null);
            } else {
              marker.setAnimation(google.maps.Animation.BOUNCE);
            }
          }
        });
      }

      function addInfoWindow(marker, content) {
        google.maps.event.addListener(marker, 'click', function () {
          if (!infowindow) {
            infowindow = new google.maps.InfoWindow({
              maxWidth: map_info_window_width
            });
          }

          infowindow.setContent(content);
          infowindow.open(map, marker);
        });
      }

      initMap();
    }
  };
  VLTJS.window.on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/vlt-google-map.default', VLTJS.googleMap.init);
  });
})(jQuery);
/***********************************************
 * WIDGET: HEADING
 ***********************************************/
(function ($) {
  'use strict'; // check if plugin defined

  if (typeof fitty === 'undefined') {
    return;
  }

  VLTJS.heading = {
    init: function ($scope) {
      var heading = $scope.find('.vlt-heading--style-2'),
          maxFitValue = heading.data('max-fit-value') || 390;

      if (heading.length) {
        fitty(heading.get(0), {
          minSize: 15,
          maxSize: maxFitValue
        });
      }
    }
  };
  VLTJS.window.on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/vlt-heading.default', VLTJS.heading.init);
  });
})(jQuery);
/***********************************************
 * WIDGET: HOVER STYLE
 ***********************************************/
(function ($) {
  'use strict'; // check if plugin defined

  if (typeof charming === 'undefined') {
    return;
  }

  if (typeof gsap == 'undefined') {
    return;
  }

  VLTJS.hoverStyle = {
    init: function ($scope) {
      var element = $scope.find('[data-hover-style]'),
          hoverStyle = element.data('hover-style') || 'style-1',
          isActive = false,
          mouseTimeout = null;

      if (!element.length) {
        return;
      }

      charming(element.get(0));
      var chars = element.find('span[class^="char"]');

      function _shuffle(arr) {
        for (var j, x, i = arr.length; i; j = parseInt(Math.random() * i), x = arr[--i], arr[i] = arr[j], arr[j] = x);

        return arr;
      }

      function _mouseenter() {
        mouseTimeout = setTimeout(function () {
          isActive = true;
          gsap.killTweensOf(chars);

          switch (hoverStyle) {
            case 'style-1':
              gsap.fromTo(chars, .3, {
                opacity: 0
              }, {
                ease: 'none',
                opacity: 1,
                stagger: .03
              });
              break;

            case 'style-2':
              gsap.fromTo(_shuffle(chars), .3, {
                opacity: 0
              }, {
                ease: 'none',
                opacity: 1,
                stagger: .03
              });
              break;
          }
        }, 50);
      }

      function _mouseleave() {
        clearTimeout(mouseTimeout);
        if (isActive) return;
        isActive = false;
      }

      element.on('mouseenter', _mouseenter);
      element.on('touchstart', _mouseenter);
      element.on('mouseleave', _mouseleave);
      element.on('touchend', _mouseleave);
    }
  };
  VLTJS.window.on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/vlt-team-member.default', VLTJS.hoverStyle.init);
  });
  VLTJS.window.on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/vlt-service-box-1.default', VLTJS.hoverStyle.init);
  });
  VLTJS.window.on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/vlt-service-box-3.default', VLTJS.hoverStyle.init);
  });
  VLTJS.window.on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/vlt-service-box-4.default', VLTJS.hoverStyle.init);
  });
  VLTJS.window.on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/vlt-demo-item.default', VLTJS.hoverStyle.init);
  });
})(jQuery);
/***********************************************
 * WIDGET: IMAGE SLIDER
 ***********************************************/
(function ($) {
  'use strict'; // check if plugin defined

  if (typeof Swiper === 'undefined') {
    return;
  }

  VLTJS.imageSlider = {
    init: function ($scope) {
      var slider = $scope.find('.vlt-image-slider'),
          container = slider.find('.swiper-container'),
          anchor = slider.data('navigation-anchor'),
          gap = slider.data('gap') || 0,
          loop = slider.data('loop') == 'yes' ? true : false,
          speed = slider.data('speed') || 1000,
          autoplay = slider.data('autoplay') == 'yes' ? true : false,
          centered = slider.data('slides-centered') == 'yes' ? true : false,
          freemode = slider.data('free-mode') == 'yes' ? true : false,
          slider_offset = slider.data('slider-offset') == 'yes' ? true : false,
          mousewheel = slider.data('mousewheel') == 'yes' ? true : false,
          autoplay_speed = slider.data('autoplay-speed'),
          settings = slider.data('slide-settings');

      if (container.length) {
        var swiper = new Swiper(container.get(0), {
          init: false,
          spaceBetween: gap,
          grabCursor: true,
          initialSlide: settings.initial_slide ? settings.initial_slide : 0,
          loop: loop,
          speed: speed,
          centeredSlides: centered,
          freeMode: freemode,
          mousewheel: mousewheel,
          autoplay: autoplay ? {
            delay: autoplay_speed,
            disableOnInteraction: false
          } : false,
          autoHeight: true,
          slidesOffsetBefore: 100,
          slidesOffsetBefore: slider_offset ? $('.container').get(0).getBoundingClientRect().left + 15 : false,
          slidesOffsetAfter: slider_offset ? $('.container').get(0).getBoundingClientRect().left + 15 : false,
          navigation: {
            nextEl: slider.find('.vlt-swiper-button-next').length ? slider.find('.vlt-swiper-button-next').get(0) : $(anchor).find('.vlt-swiper-button-next').get(0),
            prevEl: slider.find('.vlt-swiper-button-prev').length ? slider.find('.vlt-swiper-button-prev').get(0) : $(anchor).find('.vlt-swiper-button-prev').get(0)
          },
          pagination: {
            el: $(anchor).find('.vlt-swiper-dots').get(0),
            clickable: true
          },
          breakpoints: {
            // when window width is >= 576px
            576: {
              slidesPerView: settings.slides_to_show_mobile || settings.slides_to_show_tablet || settings.slides_to_show || 1,
              slidesPerGroup: settings.slides_to_scroll_mobile || settings.slides_to_scroll_tablet || settings.slides_to_scroll || 1
            },
            // when window width is >= 768px
            768: {
              slidesPerView: settings.slides_to_show_tablet || settings.slides_to_show || 1,
              slidesPerGroup: settings.slides_to_scroll_tablet || settings.slides_to_scroll || 1
            },
            // when window width is >= 992px
            992: {
              slidesPerView: settings.slides_to_show || 1,
              slidesPerGroup: settings.slides_to_scroll || 1
            }
          }
        });
        swiper.init();
      }
    }
  };
  VLTJS.window.on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/vlt-image-slider.default', VLTJS.imageSlider.init);
  });
})(jQuery);
/***********************************************
 * WIDGET: JUSTIFIED GALLERY
 ***********************************************/
(function ($) {
  'use strict'; // check if plugin defined

  if (typeof $.fn.justifiedGallery == 'undefined') {
    return;
  }

  VLTJS.justifiedGallery = {
    init: function ($scope) {
      var justifiedGallery = $scope.find('.vlt-justified-gallery'),
          row_height = justifiedGallery.data('row-height') || 360,
          margins = justifiedGallery.data('margins') || 0;
      justifiedGallery.imagesLoaded(function () {
        justifiedGallery.justifiedGallery({
          rowHeight: row_height,
          margins: margins,
          border: 0,
          captions: false
        });
      });
    }
  };
  VLTJS.window.on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/vlt-justified-gallery.default', VLTJS.justifiedGallery.init);
  });
})(jQuery);
/***********************************************
 * WIDGET: MARQUEE TEXT
 ***********************************************/
(function ($) {
  'use strict'; // check if plugin defined

  if (typeof gsap == 'undefined') {
    return;
  }

  VLTJS.marqueeEffect = {
    init: function ($scope) {
      $scope.find('[data-marquee]').each(function () {
        var $this = $(this),
            speed = $this.data('marquee') || 0.5,
            marqueeText = $this.find('[data-marquee-text]'),
            elWidth = marqueeText.outerWidth(),
            elHeight = marqueeText.outerHeight(),
            duration = elWidth / elHeight * speed + 's';
        gsap.set(marqueeText, {
          animationDuration: duration
        });
      });
    }
  };
  VLTJS.window.on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/vlt-marquee-text.default', VLTJS.marqueeEffect.init);
  });
  VLTJS.window.on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/vlt-team-member.default', VLTJS.marqueeEffect.init);
  });
  VLTJS.marqueeEffect.init(VLTJS.body);
  VLTJS.document.on('init.vpf endLoadingNewItems.vpf', function (e) {
    VLTJS.marqueeEffect.init(VLTJS.body);
  });
})(jQuery);
/***********************************************
 * WIDGET: PARTICLE
 ***********************************************/
(function ($) {
  'use strict';

  VLTJS.particleImage = {
    init: function ($scope) {
      $scope.find('.vlt-particle').each(function () {
        var $this = $(this),
            animationName = $this.data('animation-name'),
            prefix = 'animate__';
        VLTJS.window.on('vlt.site-loaded', function () {
          $this.one('inview', function () {
            $this.addClass(prefix + 'animated').addClass(prefix + animationName);
          });
        });
      });
    }
  };
  VLTJS.particleImage.init(VLTJS.body);
})(jQuery);
/***********************************************
 * WIDGET: PIE CHART
 ***********************************************/
(function ($) {
  'use strict'; // check if plugin defined

  if (typeof $.fn.circleProgress === 'undefined') {
    return;
  }

  if (typeof gsap === 'undefined') {
    return;
  }

  VLTJS.pieChart = {
    init: function ($scope) {
      var chart = $scope.find('.vlt-pie-chart'),
          bar = chart.find('.vlt-pie-chart__bar'),
          chart_value = chart.data('chart-value') || 0,
          chart_animation_duration = chart.data('chart-animation-duration') || 0,
          chart_height = chart.data('chart-height') || 0,
          chart_thickness = chart.data('chart-thickness') || 0,
          chart_track_color = chart.data('chart-track-color') || '',
          chart_bar_color = chart.data('chart-bar-color') || '',
          delay = 150,
          obj = {
        count: 0
      }; // predraw

      bar.circleProgress({
        startAngle: -Math.PI / 2,
        value: 0,
        size: chart_height,
        thickness: chart_thickness,
        fill: chart_bar_color,
        emptyFill: chart_track_color,
        animation: {
          duration: chart_animation_duration,
          easing: 'circleProgressEasing',
          delay: delay
        }
      });
      chart.one('inview', function () {
        bar.circleProgress({
          value: chart_value / 100
        });
        gsap.to(obj, chart_animation_duration / 1000, {
          count: chart_value,
          delay: delay / 1000,
          onUpdate: function () {
            chart.find('.vlt-pie-chart__title > .counter').text(Math.round(obj.count));
          }
        });
      });
    }
  };
  VLTJS.window.on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/vlt-pie-chart.default', VLTJS.pieChart.init);
  });
})(jQuery);
/***********************************************
 * WIDGET: PROGRESS BAR
 ***********************************************/
(function ($) {
  'use strict'; // check if plugin defined

  if (typeof gsap === 'undefined') {
    return;
  }

  VLTJS.progressBar = {
    init: function ($scope) {
      var progressBar = $scope.find('.vlt-progress-bar'),
          final_value = progressBar.data('final-value') || 0,
          animation_duration = progressBar.data('animation-speed') || 0,
          delay = 150,
          obj = {
        count: 0
      };
      progressBar.one('inview', function () {
        gsap.to(obj, animation_duration / 1000 / 2, {
          count: final_value,
          delay: delay / 1000,
          onUpdate: function () {
            progressBar.find('.vlt-progress-bar__title > .counter').text(Math.round(obj.count));
          }
        });
        gsap.to(progressBar.filter('.vlt-progress-bar--default').find('.vlt-progress-bar__track > .bar'), animation_duration / 1000, {
          width: final_value + '%',
          delay: delay / 1000
        });
        gsap.to(obj, animation_duration / 1000, {
          count: final_value,
          delay: delay / 1000,
          onUpdate: function () {
            progressBar.filter('.vlt-progress-bar--dotted').find('.vlt-progress-bar__track > .bar').css('clip-path', 'inset(0 ' + (100 - Math.round(obj.count)) + '% 0 0)');
          }
        });
      });
    }
  };
  VLTJS.window.on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/vlt-progress-bar.default', VLTJS.progressBar.init);
  });
})(jQuery);
/***********************************************
 * WIDGET: SECTION TITLE
 ***********************************************/
(function ($) {
  'use strict'; // check if plugin defined

  if (typeof fitty === 'undefined') {
    return;
  }

  VLTJS.sectionTitle = {
    init: function ($scope) {
      var title = $scope.find('.vlt-section-title--style-2'),
          heading = title.find('.vlt-section-title__heading'),
          maxFitValue = title.data('max-fit-value') || 390;
      fitty(heading.get(0), {
        minSize: 15,
        maxSize: maxFitValue
      });
    }
  };
  VLTJS.window.on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/vlt-section-title.default', VLTJS.sectionTitle.init);
  });
})(jQuery);
/***********************************************
 * WIDGET: TABS
 ***********************************************/
(function ($) {
  'use strict';

  VLTJS.tabs = {
    init: function ($scope) {
      var tabs = $scope.find('.vlt-tabs'),
          tab = tabs.find('.vlt-tab');
      tab.eq(0).addClass('active').find('.vlt-tab__text').show();
      tabs.on('click', '.vlt-tab:not(.active) .vlt-tab__title > a', function (e) {
        e.preventDefault();
        tab.removeClass('active').find('.vlt-tab__text').slideUp(500);
        $(this).parents('.vlt-tab').addClass('active').find('.vlt-tab__text').slideDown(500);
      });
    }
  };
  VLTJS.window.on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/vlt-tabs.default', VLTJS.tabs.init);
  });
})(jQuery);
/***********************************************
 * SHOWCASE: STYLE 1
 ***********************************************/
(function ($) {
  'use strict'; // check if plugin defined

  if (typeof Swiper === 'undefined') {
    return;
  }

  var showcaseStyle1 = function ($scope) {
    var showcase = $scope.find('.vlt-showcase--style-1'),
        images = showcase.find('.vlt-showcase-images'),
        links = showcase.find('.vlt-showcase-links'); // add active class

    links.find('li').eq(0).addClass('is-active');
    var swiper = new Swiper(images.find('.swiper-container').get(0), {
      init: false,
      effect: 'fade',
      loop: false,
      slidesPerView: 1,
      speed: 1000,
      allowTouchMove: false,
      on: {
        init: function () {
          links.on('mouseenter', '.vlt-showcase-link', function (e) {
            e.preventDefault();
            var currentLink = $(this);
            links.find('.vlt-showcase-link').removeClass('is-active');
            console.log(currentLink.index());
            images.find('.vlt-showcase-image[data-index="' + currentLink.data('index') + '"]').addClass('is-hover');
            currentLink.addClass('is-active');
            swiper.slideTo(currentLink.data('index'));
          });
          links.on('mouseleave', '.vlt-showcase-link', function (e) {
            e.preventDefault();
            images.find('.vlt-showcase-image').removeClass('is-hover');
          });
        }
      }
    });
    swiper.init();
  };

  VLTJS.window.on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/vlt-showcase-1.default', showcaseStyle1);
  });
})(jQuery);
/***********************************************
 * SHOWCASE: STYLE 2
 ***********************************************/
(function ($) {
  'use strict'; // check if plugin defined

  if (typeof Swiper === 'undefined') {
    return;
  }

  var showcaseStyle2 = function ($scope, $) {
    var showcase = $scope.find('.vlt-showcase--style-2'),
        duration = 1000;
    VLTJS.html.css('overflow', 'hidden');
    var swiper = new Swiper(showcase.find('.swiper-container').get(0), {
      init: false,
      direction: 'vertical',
      lazy: true,
      loop: false,
      slidesPerView: 1,
      parallax: true,
      mousewheel: {
        releaseOnEdges: true
      },
      grabCursor: false,
      speed: duration
    });
    VLTJS.document.on('keyup', function (e) {
      if (e.keyCode == 38) {
        // left
        swiper.slidePrev();
      } else if (e.keyCode == 40) {
        // right
        swiper.slideNext();
      }
    });
    swiper.init();
  };

  VLTJS.window.on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/vlt-showcase-2.default', showcaseStyle2);
  });
})(jQuery);
/***********************************************
 * SHOWCASE: STYLE 3
 ***********************************************/
(function ($) {
  'use strict'; // check if plugin defined

  if (typeof tippy === 'undefined') {
    return;
  }

  var showcaseStyle3 = function ($scope, $) {
    var showcase = $scope.find('.vlt-showcase--style-3'),
        link = showcase.find('.vlt-showcase-link');
    link.each(function () {
      tippy(this, {
        arrow: false,
        allowHTML: true,
        distance: '1rem',
        duration: [500, 0],
        maxWidth: 270
      });
    });
  };

  VLTJS.window.on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/vlt-showcase-3.default', showcaseStyle3);
  });
})(jQuery);
/***********************************************
 * SHOWCASE: STYLE 4
 ***********************************************/
(function ($) {
  'use strict'; // check if plugin defined

  if (typeof Swiper === 'undefined') {
    return;
  }

  var showcaseStyle4 = function ($scope, $) {
    var showcase = $scope.find('.vlt-showcase--style-4');
    var swiper = new Swiper(showcase.find('.swiper-container').get(0), {
      init: false,
      lazy: true,
      loop: false,
      mousewheel: {
        releaseOnEdges: true
      },
      slidesPerView: 1,
      speed: 1000,
      touchReleaseOnEdges: true,
      breakpoints: {
        // when window width is >= 576px
        576: {
          slidesPerView: 1
        },
        // when window width is >= 768px
        768: {
          slidesPerView: 2
        },
        // when window width is >= 992px
        992: {
          slidesPerView: 3
        }
      }
    });
    VLTJS.document.on('keyup', function (e) {
      if (e.keyCode == 37) {
        // left
        swiper.slidePrev();
      } else if (e.keyCode == 39) {
        // right
        swiper.slideNext();
      }
    });
    swiper.init();
  };

  VLTJS.window.on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/vlt-showcase-4.default', showcaseStyle4);
  });
})(jQuery);
/***********************************************
 * SHOWCASE: STYLE 5
 ***********************************************/
(function ($) {
  'use strict'; // check if plugin defined

  if (typeof Swiper === 'undefined') {
    return;
  }

  var showcaseStyle5 = function ($scope, $) {
    var showcase = $scope.find('.vlt-showcase--style-5'),
        images = showcase.find('.vlt-showcase-images'),
        links = showcase.find('.vlt-showcase-links'); // add active class

    links.find('.vlt-showcase-link').eq(0).addClass('is-active');
    var swiper = new Swiper(images.find('.swiper-container').get(0), {
      loop: false,
      effect: 'fade',
      lazy: true,
      slidesPerView: 1,
      allowTouchMove: false,
      speed: 1000,
      on: {
        init: function () {
          links.on('mouseenter', '.vlt-showcase-link', function (e) {
            e.preventDefault();
            var currentLink = $(this);
            links.find('.vlt-showcase-link').removeClass('is-active');
            currentLink.addClass('is-active');
            swiper.slideTo(currentLink.index());
          });
        }
      }
    });
  };

  VLTJS.window.on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/vlt-showcase-5.default', showcaseStyle5);
  });
})(jQuery);