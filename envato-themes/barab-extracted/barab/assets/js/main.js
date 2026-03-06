function barab_content_load_scripts() {
    var $ = jQuery;
    "use strict";
    /*=================================
        JS Index Here
    ==================================*/ 
    /*
    01. On Load Function
    02. Preloader
    03. Mobile Menu Active
    04. Sticky fix
    05. Scroll To Top
    06. Set Background Image Color & Mask
    07. Global Slider
    08. Custom Animaiton For Slider
    09. Ajax Contact Form
    10. Search Box Popup
    11. Popup Sidemenu
    12. Magnific Popup
    13. Section Position
    14. Filter 
    15. Counter Up
    16. AS Tab
    17. Shape Mockup
    18. Progress Bar Animation
    19. Price Slider
    21. Indicator
    22. Circle Progress
    00. Woocommerce Toggle
    00. Right Click Disable
    */ 
    /*=================================
        JS Index End
    ==================================*/
    /*
  
    /*---------- 03. Mobile Menu ----------*/
    $.fn.thmobilemenu = function (options) {
        var opt = $.extend(
            {
                menuToggleBtn: ".th-menu-toggle",
                bodyToggleClass: "th-body-visible",
                subMenuClass: "th-submenu",
                subMenuParent: "th-item-has-children",
                subMenuParentToggle: "th-active",
                meanExpandClass: "th-mean-expand",
                appendElement: '<span class="th-mean-expand"></span>',
                subMenuToggleClass: "th-open",
                toggleSpeed: 400,
            },
            options
        );

        return this.each(function () {
            var menu = $(this);
    
            function menuToggle() {
                menu.toggleClass(opt.bodyToggleClass);
    
                var subMenu = "." + opt.subMenuClass;
                $(subMenu).each(function () {
                    if ($(this).hasClass(opt.subMenuToggleClass)) {
                        $(this).removeClass(opt.subMenuToggleClass);
                        $(this).css("display", "none");
                        $(this).parent().removeClass(opt.subMenuParentToggle);
                    }
                });
            }
    
            menu.find("li").each(function () {
                var submenu = $(this).find("ul");
                submenu.addClass(opt.subMenuClass);
                submenu.css("display", "none");
                submenu.parent().addClass(opt.subMenuParent);
                submenu.prev("a").append(opt.appendElement);
                submenu.next("a").append(opt.appendElement);
            });
    
            function toggleDropDown($element) {
                var $parent = $($element).parent();
                var $siblings = $parent.siblings(); 

                $siblings.removeClass(opt.subMenuParentToggle);
                $siblings.find("ul").slideUp(opt.toggleSpeed).removeClass(opt.subMenuToggleClass);
    
                $parent.toggleClass(opt.subMenuParentToggle);
                $($element).next("ul").slideToggle(opt.toggleSpeed).toggleClass(opt.subMenuToggleClass);
            }
    
            var expandToggler = "." + opt.meanExpandClass;
            $(expandToggler).each(function () {
                $(this).on("click", function (e) {
                    e.preventDefault();
                    toggleDropDown($(this).parent());
                });
            });
    
            $(opt.menuToggleBtn).each(function () {
                $(this).on("click", function () {
                    menuToggle();
                });
            });
    
            menu.on("click", function (e) {
                e.stopPropagation();
                menuToggle();
            });

            menu.find("div").on("click", function (e) {
                e.stopPropagation();
            });
        });
    };

    $(".th-menu-wrapper").thmobilemenu();

    /*---------- 04. Sticky fix ----------*/
    $(window).scroll(function () {
        var topPos = $(this).scrollTop();
        if (topPos > 500) {
            $('.sticky-wrapper').addClass('sticky');
        } else {
            $('.sticky-wrapper').removeClass('sticky')
        }
    })

    /*----------- 04.1.  One Page Nav ----------*/
    function onePageNav(element) {
        if ($(element).length > 0) {
            $(element).each(function () {
                var link = $(this).find('a');
                $(this).find(link).each(function () {
                    $(this).on('click', function () {
                        var target = $(this.getAttribute('href'));
                        if (target.length) {
                            event.preventDefault();
                            $('html, body').stop().animate({
                                scrollTop: target.offset().top - 10
                            }, 1000);
                        };

                    });
                });
            })
        }
    };
    onePageNav('.onepage-nav');
    onePageNav('.scroll-down');
    //one page sticky menu  
    $(window).on('scroll', function () {
        if ($('.onepage-nav').length > 0) {};
    });

    /*---------- 04. Sticky fix ----------*/
    $(window).scroll(function () {
        var topPos = $(this).scrollTop();
        if (topPos > 500) {
            $('.sticky-wrapper').addClass('sticky');
            $('.category-menu').addClass('close-category');
        } else {
            $('.sticky-wrapper').removeClass('sticky')
            $('.category-menu').removeClass('close-category');
        }
    })

    $(".menu-expand").each(function () {
        $(this).on("click", function (e) {
            e.preventDefault();
            $('.category-menu').toggleClass('open-category');
        });
    });

    /*---------- 05. Scroll To Top ----------*/
    if ($('.scroll-top').length > 0) {
        
        var scrollTopbtn = document.querySelector('.scroll-top');
        var progressPath = document.querySelector('.scroll-top path');
        var pathLength = progressPath.getTotalLength();
        progressPath.style.transition = progressPath.style.WebkitTransition = 'none';
        progressPath.style.strokeDasharray = pathLength + ' ' + pathLength;
        progressPath.style.strokeDashoffset = pathLength;
        progressPath.getBoundingClientRect();
        progressPath.style.transition = progressPath.style.WebkitTransition = 'stroke-dashoffset 10ms linear';		
        var updateProgress = function () {
            var scroll = $(window).scrollTop();
            var height = $(document).height() - $(window).height();
            var progress = pathLength - (scroll * pathLength / height);
            progressPath.style.strokeDashoffset = progress;
        }
        updateProgress();
        $(window).scroll(updateProgress);	
        var offset = 50;
        var duration = 750;
        jQuery(window).on('scroll', function() {
            if (jQuery(this).scrollTop() > offset) {
                jQuery(scrollTopbtn).addClass('show');
            } else {
                jQuery(scrollTopbtn).removeClass('show');
            }
        });				
        jQuery(scrollTopbtn).on('click', function(event) {
            event.preventDefault();
            jQuery('html, body').animate({scrollTop: 0}, duration);
            return false;
        })
    }

    /*---------- 06. Set Background Image Color & Mask ----------*/
    if ($("[data-bg-src]").length > 0) {
        $("[data-bg-src]").each(function () {
            var src = $(this).attr("data-bg-src");
            $(this).css("background-image", "url(" + src + ")");
            $(this).removeAttr("data-bg-src").addClass("background-image");
        });
    }

    if ($('[data-bg-color]').length > 0) {
        $('[data-bg-color]').each(function () {
          var color = $(this).attr('data-bg-color');
          $(this).css('background-color', color);
          $(this).removeAttr('data-bg-color');
        });
    };

    if ($('[data-theme-color]').length > 0) {
        $('[data-theme-color]').each(function () {
          var $color = $(this).attr('data-theme-color');
          $(this).get(0).style.setProperty('--theme-color', $color);
          $(this).removeAttr('data-theme-color');
        });
    };

    $('[data-border]').each(function() {
        var borderColor = $(this).data('border');
        $(this).css('--th-border-color', borderColor);
    });
      
    if ($('[data-mask-src]').length > 0) {
        $('[data-mask-src]').each(function () {
          var mask = $(this).attr('data-mask-src');
          $(this).css({
            'mask-image': 'url(' + mask + ')',
            '-webkit-mask-image': 'url(' + mask + ')'
          });
          $(this).addClass('bg-mask');
          $(this).removeAttr('data-mask-src');
        });
    };


    /*----------- 07. Global Slider ----------*/   
    $(".th-slider").each(function () {
        var thSlider = $(this);
        var settings = $(this).data("slider-options");

        // Store references to navigation and pagination elements
        var prevArrow = thSlider.find(".slider-prev");
        var nextArrow = thSlider.find(".slider-next");
        var paginationEl1 = thSlider.find(".slider-pagination").get(0);
        var paginationEl2 = thSlider.find(".slider-pagination2").get(0); // Second pagination element

        var paginationType = settings["paginationType"] || "bullets";
        var autoplayCondition = settings["autoplay"];

        var sliderDefault = {
            slidesPerView: 1,
            spaceBetween: settings["spaceBetween"] || 24,
            loop: settings["loop"] !== false,
            speed: settings["speed"] || 1000,
            autoplay: autoplayCondition || { delay: 6000, disableOnInteraction: false },
            navigation: {
                nextEl: nextArrow.get(0),
                prevEl: prevArrow.get(0),
            },
            pagination: {
                el: paginationEl1,
                type: paginationType,
                clickable: true,
                renderBullet: function (index, className) {
                    var number = index + 1;
                    var formattedNumber = number < 10 ? "0" + number : number;
                    return (
                        '<span class="' +
                        className +
                        '" aria-label="Go to Slide ' +
                        formattedNumber +
                        '"></span>'
                    );
                },
            },
            on: {
                init: function () {
                    // Calculate the total number of real slides (excluding duplicates)
                    var totalSlides = this.el.querySelectorAll('.swiper-slide:not(.swiper-slide-duplicate)').length;

                    // Initialize pagination for .slider-pagination2
                    if (paginationEl2) {
                        $(paginationEl2).html(
                            '<span class="current-slide">01</span> <span class="total-slides">' +
                            (totalSlides < 10 ? "0" + totalSlides : totalSlides) +
                            "</span>"
                        );
                    }
                },
                slideChange: function () {
                    var realIndex = this.realIndex + 1; // +1 for 1-based index
                    var totalSlides = this.el.querySelectorAll('.swiper-slide:not(.swiper-slide-duplicate)').length;

                    // Update pagination for .slider-pagination2
                    if (paginationEl2) {
                        $(paginationEl2).html(
                            '<span class="current-slide">' +
                            (realIndex < 10 ? "0" + realIndex : realIndex) +
                            '</span> <span class="total-slides">' +
                            (totalSlides < 10 ? "0" + totalSlides : totalSlides) +
                            "</span>"
                        );
                    }
                },
            },
        };

        // Merge default settings with custom options
        var options = $.extend({}, sliderDefault, settings);
        var swiper = new Swiper(thSlider.get(0), options); // Initialize Swiper

        // Add a custom class for the arrow wrapper
        if ($(".slider-area").length > 0) {
            $(".slider-area").closest(".container").parent().addClass("arrow-wrap");
        }

        // team slider specific wheel effect
        if (thSlider.hasClass("translateSlider")) {
            const multiplier = {
                translate: 0.1,
                rotate: 0.01,
            };

            function calculateWheel() {
                const slides = document.querySelectorAll(".single");
                slides.forEach((slide) => {
                    const rect = slide.getBoundingClientRect();
                    const r =
                        window.innerWidth * 0.5 - (rect.x + rect.width * 0.5);
                    let ty =
                        Math.abs(r) * multiplier.translate -
                        rect.width * multiplier.translate;

                    if (ty < 0) {
                        ty = 0;
                    }
                    const transformOrigin = r < 0 ? "left top" : "right top";
                    slide.style.transform = `translate(0, ${ty}px) rotate(${
                        -r * multiplier.rotate
                    }deg)`;
                    slide.style.transformOrigin = transformOrigin;
                });
            }

            function raf() {
                requestAnimationFrame(raf);
                calculateWheel();
            }

            raf();
        }
        
    });

    /*----------- External Navigation for Sliders ----------*/
    $("[data-slider-prev], [data-slider-next]").on("click", function () {
        var sliderSelector = $(this).data("slider-prev") || $(this).data("slider-next");
        var targetSlider = $(sliderSelector);

        if (targetSlider.length) {
            var swiper = targetSlider[0].swiper;

            if (swiper) {
                if ($(this).data("slider-prev")) {
                    swiper.slidePrev();
                } else {
                    swiper.slideNext();
                }
            }
        }
    });

    /*----------- Ensure Swipers Work Inside Tabs ----------*/
    $("[data-bs-toggle='tab']").on("shown.bs.tab", function (e) {
        var targetTabContent = $($(e.target).attr("href"));
        var swiperContainer = targetTabContent.find(".swiper-container");

        swiperContainer.each(function () {
            var swiperInstance = this.swiper;
            if (swiperInstance) {
                swiperInstance.update(); // Update Swiper dimensions
            }
        });
    });
    
    // Function to add animation classes
    function animationProperties() {
        $("[data-ani]").each(function () {
            var animationName = $(this).data("ani");
            $(this).addClass(animationName);
        });
    
        $("[data-ani-delay]").each(function () {
            var delayTime = $(this).data("ani-delay");
            $(this).css("animation-delay", delayTime);
        });
    }
    animationProperties();

    /*--------------. Slider Tab -------------*/
    $.fn.activateSliderThumbs = function (options) {
        var opt = $.extend(
            {
                sliderTab: false,
                tabButton: ".tab-btn",
            },
            options
        );

        return this.each(function () {
            var $container = $(this);
            var $thumbs = $container.find(opt.tabButton);
            var $line = $('<span class="indicator"></span>').appendTo(
                $container
            );

            var sliderSelector = $container.data("slider-tab");
            var $slider = $(sliderSelector);

            var swiper = $slider[0].swiper;

            $thumbs.on("click", function (e) {
                e.preventDefault();
                var clickedThumb = $(this);

                clickedThumb
                    .addClass("active")
                    .siblings()
                    .removeClass("active");
                linePos(clickedThumb, $container);

                if (opt.sliderTab) {
                    var slideIndex = clickedThumb.index();
                    swiper.slideTo(slideIndex);
                }
            });

            if (opt.sliderTab) {
                swiper.on("slideChange", function () {
                    var activeIndex = swiper.realIndex;
                    var $activeThumb = $thumbs.eq(activeIndex);

                    $activeThumb
                        .addClass("active")
                        .siblings()
                        .removeClass("active");
                    linePos($activeThumb, $container);
                });

                var initialSlideIndex = swiper.activeIndex;
                var $initialThumb = $thumbs.eq(initialSlideIndex);
                $initialThumb
                    .addClass("active")
                    .siblings()
                    .removeClass("active");
                linePos($initialThumb, $container);
            }

            function linePos($activeThumb) {
                var thumbOffset = $activeThumb.position();

                var marginTop = parseInt($activeThumb.css("margin-top")) || 0;
                var marginLeft = parseInt($activeThumb.css("margin-left")) || 0;

                $line.css("--height-set", $activeThumb.outerHeight() + "px");
                $line.css("--width-set", $activeThumb.outerWidth() + "px");
                $line.css("--pos-y", thumbOffset.top + marginTop + "px");
                $line.css("--pos-x", thumbOffset.left + marginLeft + "px");
            }
        });
    };

    if ($(".testi-grid-dots").length) {
        $(".testi-grid-dots").activateSliderThumbs({
            sliderTab: true,
            tabButton: ".tab-btn",
        });
    }

    /* Menu text slider start ---------------------*/
    $(document).ready(function () {
        $(".menuTextSlider").each(function () {
            const multiplier = {
                translate: 0.1,

                rotate: 0.2,
            };

            new Swiper(".menuTextSlider", {
                slidesPerView: 3,
                spaceBetween: 60,
                centeredSlides: true,
                loop: true,
                grabCursor: true,
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                breakpoints: {
                    300: {
                        slidesPerView: 1,
                        spaceBetween: 10,
                    },
                    600: {
                        slidesPerView: 2,
                        spaceBetween: 30,
                    },
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 30,
                    },
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 40,
                    },
                    1280: {
                        slidesPerView: 3,
                        spaceBetween: 60,
                    },
                },
            });

            function calculateWheel() {
                const slides = document.querySelectorAll(".single");
                slides.forEach((slide, i) => {
                    const rect = slide.getBoundingClientRect();
                    const r =
                        window.innerWidth * 0.5 - (rect.x + rect.width * 0.5);
                    let ty =
                        Math.abs(r) * multiplier.translate -
                        rect.width * multiplier.translate;

                    if (ty < 0) {
                        ty = 0;
                    }
                    const transformOrigin = r < 0 ? "left top" : "right top";
                    slide.style.transform = `translate(0, ${ty}px) rotate(${
                        -r * multiplier.rotate
                    }deg)`;
                    slide.style.transformOrigin = transformOrigin;
                });
            }

            function raf() {
                requestAnimationFrame(raf);
                calculateWheel();
            }

            raf();
        });
    });

    /* Team slide 2 slider start ---------------------*/
    $(document).ready(function () {
        $(".team2SliderAAAA").each(function () {
            const $slider = $(this);
            const multiplier = {
                translate: 0.2, // translate power
                rotate: 0.02, // rotate power
            };

            const swiper = new Swiper(this, {
                slidesPerView: 3,
                spaceBetween: 60,
                centeredSlides: true,
                loop: true,
                grabCursor: true,
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                breakpoints: {
                    300: { slidesPerView: 1, spaceBetween: 20 },
                    600: { slidesPerView: 2, spaceBetween: 30 },
                    768: { slidesPerView: 2, spaceBetween: 40 },
                    1024: { slidesPerView: 3, spaceBetween: 60 },
                    1280: { slidesPerView: 3, spaceBetween: 60 },
                },
            });

            function calculateWheel() {
                const slides = $slider.find(".swiper-slide");
                const activeIndex = swiper.activeIndex;

                slides.each(function (i, slide) {
                    const rect = slide.getBoundingClientRect();
                    const r =
                        window.innerWidth * 0.3 - (rect.x + rect.width * 0.3);
                    let ty =
                        Math.abs(r) * multiplier.translate -
                        rect.width * multiplier.translate;

                    if (ty < 0) ty = 0;

                    if (i === activeIndex) {
                        // center slide normal
                        slide.style.transform = "translate(0, 0) rotate(0deg)";
                        slide.style.transformOrigin = "center bottom";
                    } else {
                        // 🔄 reverse rotation direction
                        const rotateDeg = Math.abs(r * multiplier.rotate);
                        const finalRotate = r < 0 ? rotateDeg : -rotateDeg;

                        slide.style.transform = `translate(0, ${ty}px) rotate(${finalRotate}deg)`;
                        slide.style.transformOrigin =
                            r < 0 ? "left bottom" : "right bottom";
                    }
                });
            }

            function raf() {
                requestAnimationFrame(raf);
                calculateWheel();
            }
            raf();
        });
    });

    /*  menu 2 hover active and removed ---------------------*/ 
    $(".menu-item-2").hover(
        function () {
            // hover in -> age sob theke remove kore, pore current e add
            $(".menu-item-2").removeClass("active");
            $(this).addClass("active");
        },
        function () {
            // hover out -> jodi chao hover chere dile remove hoye jabe
            $(this).removeClass("active");
        }
    );

    /*  Menu text slider end ---------------------*/

    $(".th-social .size-item").on("click", function () {
        $(".th-social .size-item").removeClass("active");
        $(this).addClass("active");
    });

    function triggerSpinAnimation(swiperInstance) {
        // Get all real (non-duplicate) active slides
        const realSlides = swiperInstance.slides;
        const activeIndex = swiperInstance.activeIndex;
        const activeSlide = realSlides[activeIndex];

        // Skip if it's a duplicate slide
        if (
            !activeSlide ||
            activeSlide.classList.contains("swiper-slide-duplicate")
        ) {
            return;
        }

        // Now animate elements inside the active slide only
        const animatedEls = activeSlide.querySelectorAll(
            '[data-ani="spinCenter"]'
        );

        animatedEls.forEach((el) => {
            el.classList.remove("animate-active");
            void el.offsetWidth; // force reflow to restart animation
            setTimeout(() => {
                el.classList.add("animate-active");
            }, 10);
        });
    }

    /*----------- 08. Ajax Contact Form ----------*/
    
    /*---------- 09. Search Box Popup ----------*/
    function popupSarchBox($searchBox, $searchOpen, $searchCls, $toggleCls) {
        $($searchOpen).on("click", function (e) {
            e.preventDefault();
            $($searchBox).addClass($toggleCls);
        });
        $($searchBox).on("click", function (e) {
            e.stopPropagation();
            $($searchBox).removeClass($toggleCls);
        });
        $($searchBox)
            .find("form")
            .on("click", function (e) {
                e.stopPropagation();
                $($searchBox).addClass($toggleCls);
            });
        $($searchCls).on("click", function (e) {
            e.preventDefault();
            e.stopPropagation();
            $($searchBox).removeClass($toggleCls);
        });
    }
    popupSarchBox( ".popup-search-box", ".searchBoxToggler", ".searchClose", "show" );

    /*---------- 10. Popup Sidemenu ----------*/
    function popupSideMenu($sideMenu, $sideMunuOpen, $sideMenuCls, $toggleCls) {
        // Sidebar Popup
        $($sideMunuOpen).on('click', function (e) {
        e.preventDefault();
        $($sideMenu).addClass($toggleCls);
        });
        $($sideMenu).on('click', function (e) {
        e.stopPropagation();
        $($sideMenu).removeClass($toggleCls)
        });
        var sideMenuChild = $sideMenu + ' > div';
        $(sideMenuChild).on('click', function (e) {
        e.stopPropagation();
        $($sideMenu).addClass($toggleCls)
        });
        $($sideMenuCls).on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $($sideMenu).removeClass($toggleCls);
        });
    };
    popupSideMenu('.sidemenu-cart', '.sideMenuToggler', '.sideMenuCls', 'show');
    popupSideMenu('.sidemenu-info', '.sideMenuInfo', '.sideMenuCls', 'show');

    /*----------- 12. Magnific Popup ----------*/
    /* magnificPopup img view */
    $(".popup-image").magnificPopup({
        type: "image",
        mainClass: 'mfp-zoom-in',
        removalDelay: 260,
        gallery: {
            enabled: true,
        },
    });

    /* magnificPopup video view */
    $(".popup-video").magnificPopup({
        type: "iframe",
        mainClass: 'mfp-zoom-in',
        removalDelay: 260,
    });

    /* magnificPopup video view */
    $(".popup-content").magnificPopup({
        type: "inline",
        midClick: true,
    });

    /*---------- 12. Section Position ----------*/
    // Interger Converter
    function convertInteger(str) {
        return parseInt(str, 10);
    }

    $.fn.sectionPosition = function (mainAttr, posAttr) {
        $(this).each(function () {
            var section = $(this);

            function setPosition() {
                var sectionHeight = Math.floor(section.height() / 2), // Main Height of section
                    posData = section.attr(mainAttr), // where to position
                    posFor = section.attr(posAttr), // On Which section is for positioning
                    topMark = "top-half", // Pos top
                    bottomMark = "bottom-half", // Pos Bottom
                    parentPT = convertInteger($(posFor).css("padding-top")), // Default Padding of  parent
                    parentPB = convertInteger($(posFor).css("padding-bottom")); // Default Padding of  parent

                if (posData === topMark) {
                    $(posFor).css(
                        "padding-bottom",
                        parentPB + sectionHeight + "px"
                    );
                    section.css("margin-top", "-" + sectionHeight + "px");
                } else if (posData === bottomMark) {
                    $(posFor).css(
                        "padding-top",
                        parentPT + sectionHeight + "px"
                    );
                    section.css("margin-bottom", "-" + sectionHeight + "px");
                }
            }
            setPosition(); // Set Padding On Load
        });
    };

    var postionHandler = "[data-sec-pos]";
    if ($(postionHandler).length) {
        $(postionHandler).imagesLoaded(function () {
            $(postionHandler).sectionPosition("data-sec-pos", "data-pos-for");
        });
    }

    /************lettering js***********/
    function injector(t, splitter, klass, after) {
        var a = t.text().split(splitter),
            inject = "";
        if (a.length) {
            $(a).each(function (i, item) {
                inject +=
                    '<span class="' +
                    klass +
                    (i + 1) +
                    '">' +
                    item +
                    "</span>" +
                    after;
            });
            t.empty().append(inject);
        }
    }

    var methods = {
        init: function () {
            return this.each(function () {
                injector($(this), "", "char", "");
            });
        },

        words: function () {
            return this.each(function () {
                injector($(this), " ", "word", " ");
            });
        },

        lines: function () {
            return this.each(function () {
                var r = "eefec303079ad17405c889e092e105b0";
                // Because it's hard to split a <br/> tag consistently across browsers,
                // (*ahem* IE *ahem*), we replaces all <br/> instances with an md5 hash
                // (of the word "split").  If you're trying to use this plugin on that
                // md5 hash string, it will fail because you're being ridiculous.
                injector(
                    $(this).children("br").replaceWith(r).end(),
                    r,
                    "line",
                    ""
                );
            });
        },
    };

    $.fn.lettering = function (method) {
        // Method calling logic
        if (method && methods[method]) {
            return methods[method].apply(this, [].slice.call(arguments, 1));
        } else if (method === "letters" || !method) {
            return methods.init.apply(this, [].slice.call(arguments, 0)); // always pass an array
        }
        $.error("Method " + method + " does not exist on jQuery.lettering");
        return this;
    };

    $(".logo-animation").lettering();

    /*----------- 14. Filter ----------*/
    $(".filter-active").imagesLoaded(function () {
        var $filter = ".filter-active",
            $filterItem = ".filter-item",
            $filterMenu = ".filter-menu-active";

        if ($($filter).length > 0) {
            var $grid = $($filter).isotope({
                itemSelector: $filterItem,
                filter: "*",
                masonry: {
                    // use outer width of grid-sizer for columnWidth
                    columnWidth: 1,
                },
            });

            // filter items on button click
            $($filterMenu).on("click", "button", function () {
                var filterValue = $(this).attr("data-filter");
                $grid.isotope({
                    filter: filterValue,
                });
            });

            // Menu Active Class
            $($filterMenu).on("click", "button", function (event) {
                event.preventDefault();
                $(this).addClass("active");
                $(this).siblings(".active").removeClass("active");
            });
        }
    });

    $(".masonary-active").imagesLoaded(function () {
        var $filter = ".masonary-active",
            $filterItem = ".filter-item";

        if ($($filter).length > 0) {
            $($filter).isotope({
                itemSelector: $filterItem,
                filter: "*",
                masonry: {
                    // use outer width of grid-sizer for columnWidth
                    columnWidth: 1,
                },
            });
        }
    });

    $(".masonary-active, .woocommerce-Reviews .comment-list").imagesLoaded(
        function () {
            var $filter =
                    ".masonary-active, .woocommerce-Reviews .comment-list",
                $filterItem =
                    ".filter-item, .woocommerce-Reviews .comment-list li";

            if ($($filter).length > 0) {
                $($filter).isotope({
                    itemSelector: $filterItem,
                    filter: "*",
                    masonry: {
                        // use outer width of grid-sizer for columnWidth
                        columnWidth: 1,
                    },
                });
            }
            $('[data-bs-toggle="tab"]').on("shown.bs.tab", function (e) {
                $($filter).isotope({
                    filter: "*",
                });
            });
        }
    );

    /*----------- 15. Counter Up ----------*/
    $(".counter-number").counterUp({
        delay: 10,
        time: 1000,
    });

    /*----------- 16. Shape Mockup ----------*/
    $.fn.shapeMockup = function () {
        var $shape = $(this);
        $shape.each(function() {
            var $currentShape = $(this),
            shapeTop = $currentShape.data('top'),
            shapeRight = $currentShape.data('right'),
            shapeBottom = $currentShape.data('bottom'),
            shapeLeft = $currentShape.data('left');
            $currentShape.css({
            top: shapeTop,
            right: shapeRight,
            bottom: shapeBottom,
            left: shapeLeft,
            }).removeAttr('data-top')
            .removeAttr('data-right')
            .removeAttr('data-bottom')
            .removeAttr('data-left')
            .closest('.elementor-widget').css('position', 'static')
            .closest('.e-parent').addClass('shape-mockup-wrap');
        });
    };

    if ($('.shape-mockup')) {
        $('.shape-mockup').shapeMockup();
    }

    /*----------- 16. Progress Bar Animation ----------*/
    $('.progress-bar').waypoint(function() {
        $('.progress-bar').css({
        animation: "animate-positive 1.8s",
        opacity: "1"
        });
    }, { offset: '75%' });

    /*----------- 17. Countdown ----------*/
    $.fn.countdown = function () {
        $(this).each(function () {
            var $counter = $(this),
                countDownDate = new Date($counter.data("offer-date")).getTime(), // Set the date we're counting down toz
                exprireCls = "expired";

            // Finding Function
            function s$(element) {
                return $counter.find(element);
            }

            // Update the count down every 1 second
            var counter = setInterval(function () {
                // Get today's date and time
                var now = new Date().getTime();

                // Find the distance between now and the count down date
                var distance = countDownDate - now;

                // Time calculations for days, hours, minutes and seconds
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor(
                    (distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
                );
                var minutes = Math.floor(
                    (distance % (1000 * 60 * 60)) / (1000 * 60)
                );
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Check If value is lower than ten, so add zero before number
                days < 10 ? (days = "0" + days) : null;
                hours < 10 ? (hours = "0" + hours) : null;
                minutes < 10 ? (minutes = "0" + minutes) : null;
                seconds < 10 ? (seconds = "0" + seconds) : null;

                // If the count down is over, write some text
                if (distance < 0) {
                    clearInterval(counter);
                    $counter.addClass(exprireCls);
                    $counter.find(".message").css("display", "block");
                } else {
                    // Output the result in elements
                    s$(".day").html(days);
                    s$(".hour").html(hours);
                    s$(".minute").html(minutes);
                    s$(".seconds").html(seconds);
                }
            }, 1000);
        });
    };

    if ($(".counter-list").length) {
        $(".counter-list").countdown();
    }

    /*---------- 19. Circle Progress ----------*/
    function animateElements() {
        $('.feature-circle .progressbar').each(function () {
            var elementPos = $(this).offset().top;
            var topOfWindow = $(window).scrollTop();
            var percent = $(this).find('.circle').attr('data-percent');
            var percentage = parseInt(percent, 10) / parseInt(100, 10);
            var animate = $(this).data('animate');
            if (elementPos < topOfWindow + $(window).height() - 30 && !animate) {
                $(this).data('animate', true);
                $(this).find('.circle').circleProgress({
                startAngle: -Math.PI / 2,
                value: percent / 100,
                size: 100,
                thickness: 5,
                emptyFill: "#B7B7B7",
                fill: {
                    color: '#F84923'
                }
                }).on('circle-animation-progress', function (event, progress, stepValue) {
                $(this).find('.circle-num').text((stepValue*100).toFixed(0) + "%");
                }).stop();
            }
        });
    }
    // Show animated elements
    animateElements();
    $(window).scroll(animateElements);

    /*----------- 22. Indicator ----------*/
    // Indicator
    $.fn.indicator = function () {
        // Loop through each .indicator-active element
        $(this).each(function () {
            var $menu = $(this),
                $linkBtn = $menu.find("a"),
                $btn = $menu.find("button");

            // Append indicator
            $menu.append('<span class="indicator"></span>');
            var $line = $menu.find(".indicator");

            // Check which type button is Available
            var $currentBtn;
            if ($linkBtn.length) {
                $currentBtn = $linkBtn;
            } else if ($btn.length) {
                $currentBtn = $btn;
            }

            // On Click Button Class Remove
            $currentBtn.on("click", function (e) {
                e.preventDefault();
                $(this).addClass("active");
                $(this).siblings(".active").removeClass("active");
                linePos();
            });

            // Indicator Position
            function linePos() {
                var $btnActive = $menu.find(".active"),
                    $height = $btnActive.css("height"),
                    $width = $btnActive.css("width"),
                    $top = $btnActive.position().top + "px",
                    $left = $btnActive.position().left + "px";

                $(window).on('resize', function () {
                    $top = $btnActive.position().top + "px",
                    $left = $btnActive.position().left + "px";
                });

                $line.get(0).style.setProperty("--height-set", $height);
                $line.get(0).style.setProperty("--width-set", $width);
                $line.get(0).style.setProperty("--pos-y", $top);
                $line.get(0).style.setProperty("--pos-x", $left);
            }

            linePos();
            $(window).on('resize', function () {
                linePos();
            });
        });
    };

    if ($(".indicator-active").length) {
        $(".indicator-active").indicator();
    }

    /*----------- 00. Woocommerce Toggle ----------*/
    // Ship To Different Address
    $("#ship-to-different-address-checkbox").on("change", function () {
        if ($(this).is(":checked")) {
            $("#ship-to-different-address")
                .next(".shipping_address")
                .slideDown();
        } else {
            $("#ship-to-different-address").next(".shipping_address").slideUp();
        }
    });

    // Woocommerce Payment Toggle
    $('.wc_payment_methods input[type="radio"]:checked')
        .siblings(".payment_box")
        .show();
    $('.wc_payment_methods input[type="radio"]').each(function () {
        $(this).on("change", function () {
            $(".payment_box").slideUp();
            $(this).siblings(".payment_box").slideDown();
        });
    });

    // Woocommerce Rating Toggle
    $(".rating-select .stars a").each(function () {
        $(this).on("click", function (e) {
            e.preventDefault();
            $(this).siblings().removeClass("active");
            $(this).parent().parent().addClass("selected");
            $(this).addClass("active");
        });
    });

    // Quantity Plus Minus ---------------------------
    $(document).on('click', '.quantity-plus, .quantity-minus', function (e) {
        e.preventDefault();
        // Get current quantity values
        var qty = $(this).closest('.quantity, .product-quantity').find('.qty-input');
        var val = parseFloat(qty.val());
        var max = parseFloat(qty.attr('max'));
        var min = parseFloat(qty.attr('min'));
        var step = parseFloat(qty.attr('step'));

        // Change the value if plus or minus
        if ($(this).is('.quantity-plus')) {
            if (max && (max <= val)) {
                qty.val(max);
            } else {
                qty.val(val + step);
            }
        } else {
            if (min && (min >= val)) {
                qty.val(min);
            } else if (val > 0) {
                qty.val(val - step);
            }
        }
        $('.cart_table button[name="update_cart"]').prop('disabled', false);
    });

    /*----------- Search Masonary ----------*/
    $('.search-active').imagesLoaded(function () {
        var $filter = '.search-active',
        $filterItem = '.filter-item';

        if ($($filter).length > 0) {
        var $grid = $($filter).isotope({
            itemSelector: $filterItem,
            filter: '*',
            // masonry: {
            // // use outer width of grid-sizer for columnWidth
            //     columnWidth: 1
            // }
        });
        };
    });

    
}


(function ($) {

    /*---------- 01. On Load Function ----------*/
    $(window).on("load", function () {
        $(".preloader").fadeOut();
        $(".th-slider").addClass('fade-ani');
    });

    /*---------- 02. Preloader ----------*/
    if ($(".preloader").length > 0) {
        $(".preloaderCls").each(function () {
            $(this).on("click", function (e) {
                e.preventDefault();
                $(".preloader").css("display", "none");
            });
        });
    }

    // Nice select

    $(document).ready(function () {
        setTimeout(function () {
            $('#loader').addClass('loaded');
            // Once the container has finished, the scroll appears
            if ($('#loader').hasClass('loaded')) {
                // It is so that once the container is gone, the entire preloader section is deleted
                $('#preloader').delay(9000).queue(function () {
                    $(this).remove();
                });
            }
        }, 3000);
    });

    /*---------- Sticky Footer ----------*/
    function checkHeight() {
        if ($('body').height() < $(window).height()) {
          $('.footer-sitcky').addClass('sticky-footer');
        } else {
          $('.footer-sitcky').removeClass('sticky-footer');
        }
    }

    $(window).on('load resize', function () {
        checkHeight();
    });

    // Elementor Frontend Load
    $(window).on('elementor/frontend/init', function () {
        if (elementorFrontend.isEditMode()) {
            elementorFrontend.hooks.addAction('frontend/element_ready/global', function () {
                setTimeout(function () {
                    barab_content_load_scripts();
                    $(".th-slider").addClass('fade-ani');

                }, 500);
            });
        }
    });

    // Window Load
    $(window).on('load', function () {
        
        barab_content_load_scripts();

        /*---------- Image to SVG Code here ----------*/
        const cache = {};

        $.fn.inlineSvg = function fnInlineSvg() {
            this.each(imgToSvg);

            return this;
        };

        function imgToSvg() {
            const $img = $(this);
            const src = $img.attr("src");

            // fill cache by src with promise
            if (!cache[src]) {
                const d = $.Deferred();
                $.get(src, (data) => {
                    d.resolve($(data).find("svg"));
                });
                cache[src] = d.promise();
            }

            // replace img with svg when cached promise resolves
            cache[src].then((svg) => {
                const $svg = $(svg).clone();

                if ($img.attr("id")) $svg.attr("id", $img.attr("id"));
                if ($img.attr("class")) $svg.attr("class", $img.attr("class"));
                if ($img.attr("style")) $svg.attr("style", $img.attr("style"));

                if ($img.attr("width")) {
                    $svg.attr("width", $img.attr("width"));
                    if (!$img.attr("height")) $svg.removeAttr("height");
                }
                if ($img.attr("height")) {
                    $svg.attr("height", $img.attr("height"));
                    if (!$img.attr("width")) $svg.removeAttr("width");
                }

                $svg.insertAfter($img);
                $img.trigger("svgInlined", $svg[0]);
                $img.remove();
            });
        }
        $(".svg-img").inlineSvg();

    });

    // Cart count with ajax
    jQuery(function ($) {
        $(document).on('click', '.add_to_cart_button', function (e) {
            e.preventDefault();
            var $button = $(this);
            var product_id = $button.data('product_id');
    
            $.ajax({
                type: 'POST',
                url: wc_add_to_cart_params.ajax_url,
                data: {
                    'action': 'update_cart_count',
                    'product_id': product_id
                },
                success: function (response) {
                    $('.cart_badge').text(response);
                }
            });
        });
    });
        
    
})(jQuery);