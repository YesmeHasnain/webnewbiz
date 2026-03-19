(function ($) {
  "use strict";

  

  $('.main-menu .main-menu__list > li').slice(-4).addClass('menu-last');

  $('select').niceSelect();

  function skillsBar() {
    //Progress Bar / Levels
    if ($(".progress-levels .progress-box .bar-fill").length) {
      $(".progress-box .bar-fill").each(
        function () {
          $(".progress-box .bar-fill").appear(function () {
            var progressWidth = $(this).attr("data-percent");
            $(this).css("width", progressWidth + "%");
          });
        },
        {
          accY: 0
        }
      );
    }
    if ($(".count-box").length) {
      $(".count-box").appear(
        function () {
          var $t = $(this),
            n = $t.find(".count-text").attr("data-stop"),
            r = parseInt($t.find(".count-text").attr("data-speed"), 10);
  
          if (!$t.hasClass("counted")) {
            $t.addClass("counted");
            $({
              countNum: $t.find(".count-text").text()
            }).animate(
              {
                countNum: n
              },
              {
                duration: r,
                easing: "linear",
                step: function () {
                  $t.find(".count-text").text(Math.floor(this.countNum));
                },
                complete: function () {
                  $t.find(".count-text").text(this.countNum);
                }
              }
            );
          }
        },
        {
          accY: 0
        }
      );
    }
  }

  function servicesSlider() {
    if ($(".services-one__carousel").length) {
      $(".services-one__carousel").owlCarousel({
        loop: true,
        margin: 30,
        nav: false,
        smartSpeed: 500,
        autoHeight: false,
        autoplay: true,
        dots: true,
        autoplayTimeout: 10000,
        navText: [
          '<span class="icon-left-arrow"></span>',
          '<span class="icon-right-arrow"></span>'
        ],
        responsive: {
          0: {
            items: 1
          },
          600: {
            items: 1
          },
          800: {
            items: 2
          },
          1024: {
            items: 3
          },
          1200: {
            items: 3
          }
        }
      });
    }
    if ($(".services-two__carousel").length) {
      $(".services-two__carousel").owlCarousel({
        loop: true,
        margin: 30,
        nav: false,
        smartSpeed: 500,
        autoHeight: false,
        autoplay: true,
        dots: true,
        autoplayTimeout: 10000,
        navText: [
          '<span class="icon-left-arrow"></span>',
          '<span class="icon-right-arrow"></span>'
        ],
        responsive: {
          0: {
            items: 1
          },
          600: {
            items: 1
          },
          800: {
            items: 2
          },
          1024: {
            items: 3
          },
          1200: {
            items: 3
          }
        }
      });
    }
  }

  function teamSlider() {
    if ($(".team-one__carousel").length) {
      $(".team-one__carousel").owlCarousel({
        loop: true,
        margin: 30,
        nav: false,
        smartSpeed: 500,
        autoHeight: false,
        autoplay: true,
        dots: true,
        autoplayTimeout: 10000,
        navText: [
          '<span class="icon-left-arrow"></span>',
          '<span class="icon-right-arrow"></span>'
        ],
        responsive: {
          0: {
            items: 1
          },
          600: {
            items: 1
          },
          800: {
            items: 2
          },
          1024: {
            items: 3
          },
          1200: {
            items: 4
          }
        }
      });
    }
  }

  function testimonialSlider() {
    if ($(".testimonial-one__carousel").length) {
      $(".testimonial-one__carousel").owlCarousel({
        loop: true,
        margin: 30,
        nav: false,
        smartSpeed: 500,
        autoHeight: false,
        autoplay: true,
        dots: true,
        autoplayTimeout: 10000,
        navText: [
          '<span class="icon-left-arrow"></span>',
          '<span class="icon-right-arrow"></span>'
        ],
        responsive: {
          0: {
            items: 1
          },
          600: {
            items: 1
          },
          800: {
            items: 2
          },
          1024: {
            items: 2
          },
          1200: {
            items: 2
          }
        }
      });
    }
  }

  function blogSlider() {
    // Blog One Carousel
    if ($(".blog-one__carousel").length) {
      $(".blog-one__carousel").owlCarousel({
        loop: true,
        margin: 30,
        nav: false,
        smartSpeed: 500,
        autoHeight: false,
        autoplay: true,
        dots: true,
        autoplayTimeout: 10000,
        navText: [
          '<span class="icon-left-arrow"></span>',
          '<span class="icon-right-arrow"></span>'
        ],
        responsive: {
          0: {
            items: 1
          },
          600: {
            items: 1
          },
          800: {
            items: 2
          },
          1024: {
            items: 3
          },
          1200: {
            items: 3
          }
        }
      });
    }
  }

  function brandSlider() {
  if ($(".brand-one__carousel").length) {
    $(".brand-one__carousel").owlCarousel({
        loop: true,
        margin: 30,
        nav: false,
        smartSpeed: 500,
        autoHeight: false,
        autoplay: true,
        dots: true,
        autoplayTimeout: 10000,
        navText: [
          '<span class="icon-left-arrow"></span>',
          '<span class="icon-right-arrow"></span>'
        ],
        responsive: {
          0: {
            items: 1
          },
          600: {
            items: 1
          },
          800: {
            items: 2
          },
          1024: {
            items: 3
          },
          1200: {
            items: 5
          }
        }
      });
    }
  }

  if ($(".scroll-to-target").length) {
    $(".scroll-to-target").on("click", function () {
      var target = $(this).attr("data-target");
      // animate
      $("html, body").animate(
        {
          scrollTop: $(target).offset().top
        },
        1000
      );

      return false;
    });
  }
    
  // News One Carousel
  if ($(".news-one__carousel").length) {
    $(".news-one__carousel").owlCarousel({
      loop: true,
      margin: 30,
      nav: false,
      smartSpeed: 500,
      autoHeight: false,
      autoplay: true,
      dots: true,
      autoplayTimeout: 10000,
      navText: [
        '<span class="icon-left-arrow"></span>',
        '<span class="icon-right-arrow"></span>'
      ],
      responsive: {
        0: {
          items: 1
        },
        600: {
          items: 1
        },
        800: {
          items: 2
        },
        1024: {
          items: 3
        },
        1200: {
          items: 4
        }
      }
    });
  }

  function videoPopup() {
    if ($(".video-popup").length) {
      $(".video-popup").magnificPopup({
        type: "iframe",
        mainClass: "mfp-fade",
        removalDelay: 160,
        preloader: true,
  
        fixedContentPos: false
      });
    }
  }

  

  if ($(".img-popup").length) {
    var groups = {};
    $(".img-popup").each(function () {
      var id = parseInt($(this).attr("data-group"), 10);

      if (!groups[id]) {
        groups[id] = [];
      }

      groups[id].push(this);
    });

    $.each(groups, function () {
      $(this).magnificPopup({
        type: "image",
        closeOnContentClick: true,
        closeBtnInside: false,
        gallery: {
          enabled: true
        }
      });
    });
  }

  function dynamicCurrentMenuClass(selector) {
    let FileName = window.location.href.split("/").reverse()[0];

    selector.find("li").each(function () {
      let anchor = $(this).find("a");
      if ($(anchor).attr("href") == FileName) {
        $(this).addClass("current");
      }
    });
    // if any li has .current elmnt add class
    selector.children("li").each(function () {
      if ($(this).find(".current").length) {
        $(this).addClass("current");
      }
    });
    // if no file name return
    if ("" == FileName) {
      selector.find("li").eq(0).addClass("current");
    }
  }

  if ($(".main-menu__list").length) {
    // dynamic current class
    let mainNavUL = $(".main-menu__list");
    dynamicCurrentMenuClass(mainNavUL);
  }

  if ($(".main-menu__list").length && $(".mobile-nav__container").length) {
    let navContent = document.querySelector(".main-menu__list").outerHTML;
    let mobileNavContainer = document.querySelector(".mobile-nav__container");
    mobileNavContainer.innerHTML = navContent;
  }
  if ($(".sticky-header__content").length) {
    let navContent = document.querySelector(".main-menu").innerHTML;
    let mobileNavContainer = document.querySelector(".sticky-header__content");
    mobileNavContainer.innerHTML = navContent;
  }

  if ($(".mobile-nav__container .main-menu__list").length) {
    let dropdownAnchor = $(
      ".mobile-nav__container .main-menu__list .dropdown > a"
    );
    dropdownAnchor.each(function () {
      let self = $(this);
      let toggleBtn = document.createElement("BUTTON");
      toggleBtn.setAttribute("aria-label", "dropdown toggler");
      toggleBtn.innerHTML = "<i class='fa fa-angle-down'></i>";
      self.append(function () {
        return toggleBtn;
      });
      self.find("button").on("click", function (e) {
        e.preventDefault();
        let self = $(this);
        self.toggleClass("expanded");
        self.parent().toggleClass("expanded");
        self.parent().parent().children("ul").slideToggle();
      });
    });
  }

  if ($(".mobile-nav__toggler").length) {
    $(".mobile-nav__toggler").on("click", function (e) {
      e.preventDefault();
      $(".mobile-nav__wrapper").toggleClass("expanded");
      $("body").toggleClass("locked");
    });
  }

  if ($(".wow").length) {
    var wow = new WOW({
      boxClass: "wow", // animated element css class (default is wow)
      animateClass: "animated", // animation css class (default is animated)
      mobile: true, // trigger animations on mobile devices (default is true)
      live: true // act on asynchronously loaded content (default is true)
    });
    wow.init();
  }

  function thmOwlInit() {
    // owl slider

    if ($(".thm-owl__carousel").length) {
      $(".thm-owl__carousel").each(function () {
        let elm = $(this);
        let options = elm.data('owl-options');
        let thmOwlCarousel = elm.owlCarousel(options);
      });
    }

    if ($(".thm-owl__carousel--custom-nav").length) {
      $(".thm-owl__carousel--custom-nav").each(function () {
        let elm = $(this);
        let owlNavPrev = elm.data('owl-nav-prev');
        let owlNavNext = elm.data('owl-nav-next');
        $(owlNavPrev).on("click", function (e) {
          elm.trigger('prev.owl.carousel');
          e.preventDefault();
        })

        $(owlNavNext).on("click", function (e) {
          elm.trigger('next.owl.carousel');
          e.preventDefault();
        })
      });
    }

    if ($(".video-popup").length) {
      $(".video-popup").magnificPopup({
        type: "iframe",
        mainClass: "mfp-fade",
        removalDelay: 160,
        preloader: true,
        fixedContentPos: false
      });
    }

  }

  

  function funFact() {
    if ($(".odometer").length) {
      var odo = $(".odometer");
      odo.each(function () {
        $(this).appear(function () {
          var countNumber = $(this).attr("data-count");
          $(this).html(countNumber);
        });
      });
    }
  }
  
  // window load event

  $(window).on("load", function () {
    if ($(".preloader").length) {
      $(".preloader").fadeOut();
    }

  });

  $("[data-background").each(function () {
		$(this).css("background-image", "url( " + $(this).attr("data-background") + "  )");
	});

  // window scroll event

  $(window).on("scroll", function () {
    if ($(".sticked-menu").length) {
      var headerScrollPos = 130;
      var sticky = $(".sticked-menu");
      if ($(window).scrollTop() > headerScrollPos) {
        sticky.addClass("sticky-fixed");
      } else if ($(this).scrollTop() <= headerScrollPos) {
        sticky.removeClass("sticky-fixed");
      }
    }
    if ($(".scroll-to-top").length) {
      var stickyScrollPos = 100;
      if ($(window).scrollTop() > stickyScrollPos) {
        $(".scroll-to-top").fadeIn(500);
      } else if ($(this).scrollTop() <= stickyScrollPos) {
        $(".scroll-to-top").fadeOut(500);
      }
    }
  });


  $(window).on("elementor/frontend/init", function () {
		elementorFrontend.hooks.addAction(
			"frontend/element_ready/twinkle_video_popup.default",videoPopup
		);
		elementorFrontend.hooks.addAction(
			"frontend/element_ready/twinkle_fun_fact.default",funFact
		);
		elementorFrontend.hooks.addAction(
			"frontend/element_ready/twinkle_services_slider.default",servicesSlider
		);
		elementorFrontend.hooks.addAction(
			"frontend/element_ready/twinkle_slider.default",thmOwlInit
		);
		elementorFrontend.hooks.addAction(
			"frontend/element_ready/twinkle_skill_bar.default",skillsBar
		);
		elementorFrontend.hooks.addAction(
			"frontend/element_ready/twinkle_testimonial_slider.default",testimonialSlider
		);
		elementorFrontend.hooks.addAction(
			"frontend/element_ready/twinkle_blog_post.default",blogSlider
		);
		elementorFrontend.hooks.addAction(
			"frontend/element_ready/twinkle_brand.default",brandSlider
		);
		elementorFrontend.hooks.addAction(
			"frontend/element_ready/twinkle_team_slider.default",teamSlider
		);
	});

})(jQuery);
