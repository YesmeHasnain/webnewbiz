(function ($) {
  "use strict";

  jQuery(document).ready(function () {
    var stellarnav = jQuery(".stellarnav");
    stellarnav.stellarNav({
      theme: "light",
      breakpoint: 991,
      position: "right",
      phoneBtn: mainJsObj.phoneText,
      locationBtn: mainJsObj.locationUrl,
    });

    var play_btn = $("#play-video");
    var play_btn_overlay = $("#video-overlay");
    var play_btn_overlay_close = $(".video-overlay, .video-overlay-close");
    var play_btn_overlay_open = $(".video-overlay.open");
    var navbarmneuclass = $(".navbarmneuclass");
    var hover_project_item = $(" #da-thumbs > li ");
    var project_slide = $(".portfolio-carousel");
    var project_slide2 = $(".portfolio-carousel2");
    var banner_slide = $(".banner-carousel");
    var testimonials = $(".testimonials");
    var blogs = $(".blogs");
    var logos = $(".logos");
    var preloader = $(".preloader");
    var facts = $("#facts");
    var number1 = $("#number_1");
    var number2 = $("#number_2");
    var number3 = $("#number_3");
    var number4 = $("#number_4");
    var slide_widgets = $("#slide-widgets");
    var grid_side_open = $("#grid-side");
    var close_btn = $("#close-btn");
    var side_content = $("#side-content");
    var portfolio_filter = $(".portfolio-filter li");
    var portfolio_list = $(".portfolio-list");
    var portfolio_list2 = $(".portfolio-list2");
    var scrollToTop = $("a.up-btn");
    var section_scroller = $('#scroll > a[href*="#"]');
    var bodyAnimate = $("html, body");

    var hover_project_item = jQuery(" #da-thumbs > li ");
    hover_project_item.hoverdir();

    var play_btn = jQuery("#play-video");
    var play_btn_overlay = jQuery("#video-overlay");
    var play_btn_overlay_close = jQuery(".video-overlay, .video-overlay-close");
    var play_btn_overlay_open = jQuery(".video-overlay.open");
    play_btn.on("click", function (e) {
      e.preventDefault();
      play_btn_overlay.addClass("open");
    });
    play_btn_overlay_close.on("click", function (e) {
      e.preventDefault();
      close_video();
    });

    /*---------------------
            EXPANDING SEARCH
        -----------------------*/
    jQuery(".search__bur__open").on("click", function () {
      jQuery(".expanding__search__bar").addClass("open__search");
      jQuery(".expanding__search__close").on("click", function () {
        jQuery(".expanding__search__bar").removeClass("open__search");
      });
    });

    /*---------------------
        	Nice Select ***
        ----------------------*/
    jQuery("select").niceSelect();

    const dataset = document.querySelector(".js-modal-video-btn")
      ? document.querySelector(".js-modal-video-btn").dataset.videoId
      : "";
    const channel = isNaN(dataset) ? "youtube" : "vimeo";

    new ModalVideo(".js-modal-video-btn", {
      channel: `${channel}`,
      youtube: {
        controls: 1,
        nocookie: false,
        autoplay: true,
        modestbranding: 1,
        rel: 0,
      },
    });

    new WOW().init();

    var filterizd = jQuery(".filtr-container");
    if (filterizd.length) {
      filterizd.filterizr({
        layout: "sameWidth",
        gutterPixels: 20,
      });
    }

    jQuery(".navs__link--filter").on("click", function () {
      jQuery(this).removeAttr("href");
    });
    jQuery(".navs--filter .navs__item--filter").on("click", function () {
      jQuery(this).toggleClass("active").siblings().removeClass("active");
    });

    var slide_widgets = jQuery("#slide-widgets");
    var grid_side_open = jQuery("#grid-side");
    var close_btn = jQuery("#close-btn");
    var side_content = jQuery("#side-content");
    grid_side_open.on("click", function () {
      slide_widgets.addClass("active");
      side_content.addClass("active");
    });
    close_btn.on("click", function () {
      slide_widgets.removeClass("active");
      side_content.removeClass("active");
    });

    jQuery(".circlechart").circlechart();

    /*--------------------
            Counter UP
        ---------------------*/
    jQuery("h2.project-counter").counterUp({
      delay: 10,
      time: 1500,
    });

    function stickyNav() {
      window.onscroll = function () {
        myFunction();
      };
      var header = document.getElementById("header");
      var sticky = header.offsetTop;

      function myFunction() {
        if (window.pageYOffset > sticky) {
          header.classList.add("sticky");
        } else {
          header.classList.remove("sticky");
        }
      }
    }
    stickyNav();

    var instaload = false;
    jQuery.fn.func_instafeed = function (new_obj) {
      var jQuerythis = jQuery(this),
        jQueryaccessToken = jQuery(this).attr("data-accessToken"),
        jQueryclientId = jQuery(this).attr("data-clientId"),
        jQueryuserId = jQuery(this).attr("data-userId"),
        jQuerylimitImg = jQuery(this).attr("data-limit");
      if (!jQuerythis.length) return;
      var new_obj = new_obj || {},
        set_obj = {
          get: "user",
          userId: jQueryuserId,
          clientId: jQueryclientId,
          limit: jQuerylimitImg,
          sortBy: "most-liked",
          resolution: "standard_resolution",
          accessToken: jQueryaccessToken,
          template:
            '<div class="single-social"><div class="sinlge-social-hover" style="background-image:url({{image}});"><a href=""><span class="single-soicial-text"><p>#Instagram</p></span></a></div></div>',
        };
      jQuery.extend(set_obj, new_obj);
      var feed = new Instafeed(set_obj);
      feed.run();
    };

    jQuery(window).scroll(function () {
      if (jQuery(document).scrollTop() > 300) {
        if (!instaload) {
          jQuery("#instafeed").each(function () {
            jQuery(this).func_instafeed();
          });
          instaload = true;
        }
      }
    });

    section_scroller.on("click", function (e) {
      bodyAnimate.animate(
        {
          scrollTop: $($(this).attr("href")).offset().top(),
        },
        1000,
        "linear"
      );
    });

    scrollToTop.on("click", function (e) {
      bodyAnimate.animate(
        {
          scrollTop: 0,
        },
        700
      );
      return false;
    });
  });

  jQuery(window).on("load", function () {
    var preloader = jQuery(".preloader");
    function preloader_func() {
      preloader.delay(200).fadeOut(500);
    }
    preloader_func();

    var wlc_slide = jQuery(".welcome-carousel");
    wlc_slide.owlCarousel({
      loop: true,
      nav: false,
      dots: true,
      items: 1,
      animateIn: "fadeIn",
      animateOut: "fadeOut",
      smartplay: 1000,
      autoplay: true,
    });

    var project_slide = jQuery(".portfolio-carousel");
    project_slide.owlCarousel({
      loop: true,
      nav: true,
      dots: true,
      items: 3,
      animateIn: "fadeIn",
      animateOut: "fadeOut",
      smartplay: 1000,
      autoplay: false,
      navText: [
        '<i class="fa fa-arrow-left"></i>',
        '<i class="fa fa-arrow-right"></i>',
      ],
      responsive: {
        0: {
          items: 1,
        },
        600: {
          items: 2,
        },
        1000: {
          items: 3,
        },
      },
    });

    var project_slide2 = jQuery(".portfolio-carousel2");
    project_slide2.owlCarousel({
      loop: true,
      nav: true,
      dots: true,
      items: 4,
      animateIn: "fadeIn",
      animateOut: "fadeOut",
      smartplay: 1000,
      autoplay: false,
      navText: [
        '<i class="fa fa-arrow-left"></i>',
        '<i class="fa fa-arrow-right"></i>',
      ],
      responsive: {
        0: {
          items: 1,
        },
        600: {
          items: 2,
        },
        1000: {
          items: 4,
        },
      },
    });

    var banner_slide = jQuery(".banner-carousel");
    banner_slide.owlCarousel({
      loop: true,
      nav: true,
      dots: false,
      animateIn: "fadeInUp",
      autoplay: true,
      items: 1,
      navText: [
        '<i class="fa fa-arrow-left"></i>',
        '<i class="fa fa-arrow-right"></i>',
      ],
    });

    var testimonials = jQuery(".testimonials");
    testimonials.owlCarousel({
      loop: true,
      nav: true,
      dots: false,
      animateIn: "fadeInRight",
      animateOut: "fadeInRight",
      autoplay: true,
      items: 1,
      navText: [
        '<i class="fa fa-arrow-left"></i>',
        '<i class="fa fa-arrow-right"></i>',
      ],
    });

    var blogs = jQuery(".blogs");
    blogs.owlCarousel({
      loop: true,
      nav: true,
      dots: false,
      margin: 30,
      autoplay: false,
      navText: [
        '<i class="fa fa-arrow-left"></i>',
        '<i class="fa fa-arrow-right"></i>',
      ],
      responsive: {
        0: {
          items: 1,
        },
        600: {
          items: 2,
        },
        1000: {
          items: 3,
        },
      },
    });

    var logos = jQuery(".logos");
    logos.owlCarousel({
      loop: true,
      nav: false,
      dots: false,
      autoplay: false,
      responsive: {
        0: {
          items: 1,
        },
        600: {
          items: 3,
        },
        1000: {
          items: 5,
        },
      },
    });

    /*welcome_area animation*/
    wlc_slide.on("translate.owl.carousel", function () {
      jQuery(".welcome-carousel .wlc-title h1")
        .removeClass("fadeInDown animated")
        .hide();
      jQuery(".welcome-carousel .wlc-title h6")
        .removeClass("fadeInDown animated")
        .hide();
      jQuery(".welcome-carousel .wlc-title a")
        .removeClass("fadeInDown animated")
        .hide();
      jQuery(".welcome-carousel h1.wlc-filltext")
        .removeClass("fadeInDown animated")
        .hide();
    });
    wlc_slide.on("translated.owl.carousel", function () {
      jQuery(".owl-item.active .wlc-title h1")
        .addClass("fadeInDown animated")
        .show();
      jQuery(".owl-item.active .wlc-title h6")
        .addClass("fadeInDown animated")
        .show();
      jQuery(".owl-item.active .wlc-title a")
        .addClass("fadeInDown animated")
        .show();
      jQuery(".owl-item.active h1.wlc-filltext")
        .addClass("fadeInDown animated")
        .show();
    });
  });
})(jQuery);
