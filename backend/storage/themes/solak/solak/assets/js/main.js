function solak_content_load_scripts() {
    var $ = jQuery;
    "use strict";
    /*=================================
        JS Index Here
    ==================================*/ 
    /*
    01. On Load Function
    02. Preloader
    03. Mobile Menu
    04. Sticky fix
    05. Scroll To Top
    06. Set Background Image Color & Mask
    07. Global Slider
    08. Ajax Contact Form
    09. Search Box Popup
    10. Popup Sidemenu
    11. Magnific Popup
    12. Section Position
    13. Filter
    14. Counter Up
    15. Shape Mockup
    16. Progress Bar Animation
    17. Countdown
    18. Image to SVG Code
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

    /*----------- 3. One Page Nav ----------*/
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

    $('[data-border]').each(function () {
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
    $('.th-slider').each(function () {

        var thSlider = $(this);
        var settings = $(this).data('slider-options');

        // Store references to the navigation Slider
        var prevArrow = thSlider.find('.slider-prev');
        var nextArrow = thSlider.find('.slider-next');
        var paginationElN = thSlider.find('.slider-pagination.pagi-number');
        var paginationExternel = thSlider.siblings('.slider-controller').find('.slider-pagination');

        var paginationEl = paginationExternel.length ? paginationExternel.get(0) : thSlider.find('.slider-pagination').get(0);

        var paginationType = settings['paginationType'] ? settings['paginationType'] : 'bullets';

        var autoplayconditon = settings['autoplay'];

        var sliderDefault = {
            slidesPerView: 1,
            spaceBetween: settings['spaceBetween'] ? settings['spaceBetween'] : 24,
            loop: settings['loop'] == false ? false : true,
            speed: settings['speed'] ? settings['speed'] : 1000,
            autoplay: autoplayconditon ? autoplayconditon : {delay: 6000, disableOnInteraction: false},
            navigation: {
                nextEl: nextArrow.get(0),
                prevEl: prevArrow.get(0),  
            },
            pagination: {
                el: paginationEl,
                type: paginationType,
                clickable: true, 
                renderBullet: function (index, className) {
                    var number = index + 1;
                    var formattedNumber = number < 10 ? '0' + number : number;
                    if (paginationElN.length) {
                        return '<span class="' + className + ' number">' + formattedNumber + '</span>';
                    } else {
                        return '<span class="' + className + '" aria-label="Go to Slide ' + formattedNumber + '"></span>';
                    }
                },
                formatFractionCurrent: function (number) {
                    if (number < 10) {
                        return '0' + number;
                    } else {
                        return number;
                    }
                },
                formatFractionTotal: function (number) {
                    if (number < 10) {
                        return '0' + number;
                    } else {
                        return number;
                    }
                }
            },
            on: {
                slideChange: function() {
                    setTimeout(function () {
                        swiper.params.mousewheel.releaseOnEdges = false;
                    }, 500);
                },
                reachEnd: function() {
                    setTimeout(function () {
                        swiper.params.mousewheel.releaseOnEdges = true;
                    }, 750);
                }
            }
        };

        var options = JSON.parse(thSlider.attr('data-slider-options'));
        options = $.extend({}, sliderDefault, options);
        var swiper = new Swiper(thSlider.get(0), options); // Assign the swiper variable

        if ($('.slider-area').length > 0) {
            $('.slider-area').closest(".container").parent().addClass("arrow-wrap");
        }

    });

    function animationProperties() {
        $('[data-ani]').each(function () {
            var animationName = $(this).data('ani');
            $(this).addClass(animationName);
        });

        $('[data-ani-delay]').each(function () {
            var delayTime = $(this).data('ani-delay');
            $(this).css('animation-delay', delayTime);
        });
    }
    animationProperties();

    // Add click event handlers for external slider arrows based on data attributes
    $('[data-slider-prev], [data-slider-next]').on('click', function () {
        var sliderSelector = $(this).data('slider-prev') || $(this).data('slider-next');
        var targetSlider = $(sliderSelector);

        if (targetSlider.length) {
            var swiper = targetSlider[0].swiper;

            if (swiper) {
                if ($(this).data('slider-prev')) {
                    swiper.slidePrev();
                } else {
                    swiper.slideNext();
                }
            }
        }
    });


    /*-------------- 08. Slider Tab -------------*/
    $.fn.activateSliderThumbs = function (options) {
        var opt = $.extend({
                sliderTab: false,
                tabButton: ".tab-btn",
            },
            options
        );

        return this.each(function () {
            var $container = $(this);
            var $thumbs = $container.find(opt.tabButton);
            var $line = $('<span class="indicator"></span>').appendTo($container);

            var sliderSelector = $container.data("slider-tab");
            var $slider = $(sliderSelector);

            var swiper = $slider[0].swiper;

            $thumbs.on("click", function (e) {
                e.preventDefault();
                var clickedThumb = $(this);

                clickedThumb.addClass("active").siblings().removeClass("active");
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

                    $activeThumb.addClass("active").siblings().removeClass("active");
                    linePos($activeThumb, $container);
                });

                var initialSlideIndex = swiper.activeIndex;
                var $initialThumb = $thumbs.eq(initialSlideIndex);
                $initialThumb.addClass("active").siblings().removeClass("active");
                linePos($initialThumb, $container);
            }

            function linePos($activeThumb) {
                var thumbOffset = $activeThumb.position();

                var marginTop = parseInt($activeThumb.css('margin-top')) || 0;
                var marginLeft = parseInt($activeThumb.css('margin-left')) || 0;

                $line.css("--height-set", $activeThumb.outerHeight() + "px");
                $line.css("--width-set", $activeThumb.outerWidth() + "px");
                $line.css("--pos-y", thumbOffset.top + marginTop + "px");
                $line.css("--pos-x", thumbOffset.left + marginLeft + "px");
            }
        });
    };

    if ($(".testi-thumb").length) {
        $(".testi-thumb").activateSliderThumbs({
            sliderTab: true,
            tabButton: ".tab-btn",
        });
    }

    if ($(".feature-thumb").length) {
        $(".feature-thumb").activateSliderThumbs({
            sliderTab: true,
            tabButton: ".tab-btn",
        });
    }

    var swiper = new Swiper(".cubeSwiper", {
      effect: "cube",
      grabCursor: true,
      pauseOnMouseEnter: true,
      speed: 2000,
      autoplay: {
        delay: 2500,
        disableOnInteraction: false,
      },
      cubeEffect: {
        shadow: false,
        slideShadows: true,
        shadowOffset: 20,
        shadowScale: 0.94,
      },
      pagination: {
        el: ".swiper-pagination",
      },
    });

    /*----------- 09. Ajax Contact Form ----------*/
    
    /*---------- 10. Search Box Popup ----------*/
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
    popupSarchBox(
        ".popup-search-box",
        ".searchBoxToggler",
        ".searchClose",
        "show"
    );

    /*---------- 11. Popup Sidemenu ----------*/
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
    popupSideMenu('.sidemenu-wrapper', '.sideMenuToggler', '.sideMenuCls', 'show');

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

    if ($('[data-theme-color]').length > 0) {
        $('[data-theme-color]').each(function () {
            var $color = $(this).attr('data-theme-color');
            $(this).get(0).style.setProperty('--theme-color', $color);
            $(this).removeAttr('data-theme-color');
        });
    };

    $(document).on('mouseover', '.hover-item', function () {
        $(this).addClass('item-active');
        $('.hover-item').removeClass('item-active');
        $(this).addClass('item-active');
    });

    //========== GSAP AREA ============= //

    /*---------- Lenis Js ----------*/
      let lenis;

      // Function to initialize Lenis
      function initializeLenis() {
          lenis = new Lenis({
              lerp: 0.1, // Smooth scrolling duration
          });
  
          lenis.on('scroll', ScrollTrigger.update);
  
          // Safeguard against null or undefined lenis in GSAP ticker
          gsap.ticker.add((time) => {
              if (lenis) {
                  lenis.raf(time * 1000); // Update Lenis with GSAP's ticker
              }
          });
  
          // Allow natural scrolling for specific elements
          const scrollableElements = document.querySelectorAll('.allow-natural-scroll');
          scrollableElements.forEach((element) => {
              element.addEventListener('wheel', (event) => {
                  event.stopPropagation(); // Prevent Lenis from intercepting scroll
              });
  
              element.addEventListener('touchmove', (event) => {
                  event.stopPropagation(); // Prevent Lenis from intercepting touch scrolling
              });
          });
      }
  
      // Function to enable or disable Lenis based on screen width
      function enableOrDisableLenis() {
          if (window.innerWidth > 991) {
              if (!lenis) {
                  initializeLenis(); // Enable Lenis on non-mobile devices
              }
              if (lenis) lenis.start(); // Ensure Lenis is running
          } else {
              if (lenis) {
                  lenis.stop(); // Disable Lenis on mobile
                  lenis = null; // Clean up Lenis instance
              }
          }
      }
  
      // Initial check on load
      enableOrDisableLenis();
  
      // Update Lenis on window resize
      window.addEventListener('resize', enableOrDisableLenis);
  
  
      // Add resize event listener to toggle Lenis dynamically
      window.addEventListener('resize', enableOrDisableLenis);
   

    //gsap sticky fixed 
    $(document).on("DOMContentLoaded", function () {
    
        if ($('.sticky-wrapper').length > 0) {
            // updateSmoothWrapperPadding
            function updateSmoothWrapperPadding() {
                var headerHeight = $('.th-header').outerHeight();
                $('#smooth-wrapper').css('padding-top', '0px');
            }
    
            function refreshPaddingBasedOnSticky() {
                var isSticky = $('.sticky-wrapper').hasClass('sticky');
                if (isSticky) {
                    $('#smooth-wrapper').css('padding-top', '0');
                } else {
                    updateSmoothWrapperPadding();
                }
            }
            refreshPaddingBasedOnSticky();
    
            $(window).on("resize", refreshPaddingBasedOnSticky);
    
            if (typeof MutationObserver !== 'undefined') {
                var observer = new MutationObserver(function (mutations) {
                    mutations.forEach(function (mutation) {
                        if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                            refreshPaddingBasedOnSticky();
                        }
                    });
                });
                observer.observe(document.querySelector('.sticky-wrapper'), {
                    attributes: true
                });
            }
        }
    
        // Cursor smoothness
        $(document).on("mousemove", function (event) {
            var mouseX = event.pageX;
            var mouseY = event.pageY;
            $('.custom-element').stop().animate({
                left: mouseX,
                top: mouseY
            }, 300);
        });
    
    });
    
    //gsap sticky fixed 
  
    if ($('.th-anim').length) {
        gsap.registerPlugin(ScrollTrigger);
        let revealContainers = document.querySelectorAll(".th-anim");
        revealContainers.forEach((container) => {
            let image = container.querySelector("img");
            let tl = gsap.timeline({
                scrollTrigger: {
                    trigger: container,
                    toggleActions: "play none none none"
                }
            });
            tl.set(container, {
                autoAlpha: 1
            });
            tl.from(container, 1.5, {
                xPercent: -100,
                ease: Power2.out
            });
            tl.from(image, 1.5, {
                xPercent: 100,
                scale: 1.3,
                delay: -1.5,
                ease: Power2.out
            });
        });
    }

    /*---------- Images parallax ----------*/
    gsap.utils.toArray('.th-parallax').forEach(container => {
        const img = container.querySelector('img');
    
        const tl = gsap.timeline({
        scrollTrigger: {
            trigger: container,
            scrub: true,
            pin: false,
        }
        });
    
        tl.fromTo(img, {
        yPercent: -15,
        scale: 1.2,
        ease: 'none'
        }, {
        yPercent: 15,
        scale: 1.2,
        ease: 'none'
        });
    });

   /* cursor area start*/
    // /*----------- Gsap Animation ----------*/
    if ($('.cursor-follower').length > 0) {
        var follower = $(".cursor-follower");

        var posX = 0,
            posY = 0;

        var mouseX = 0,
            mouseY = 0;

        TweenMax.to({}, 0.016, {
            repeat: -1,
            onRepeat: function () {
                posX += (mouseX - posX) / 9;
                posY += (mouseY - posY) / 9;

                TweenMax.set(follower, {
                    css: {
                        left: posX - 12,
                        top: posY - 12
                    }
                });
            }
        });

        $(document).on("mousemove", function (e) {
            mouseX = e.clientX;
            mouseY = e.clientY;
        });
        //circle
        $(".slider-area").on("mouseenter", function () {
            follower.addClass("d-none");
        });
        $(".slider-area").on("mouseleave", function () {
            follower.removeClass("d-none");
        });
    }

    const cursor = document.querySelector(".slider-drag-cursor");
    const pos = {
        x: window.innerWidth / 2,
        y: window.innerHeight / 2
    };
    const mouse = {
        x: pos.x,
        y: pos.y
    };
    const speed = 1;

    const xSet = gsap.quickSetter(cursor, "x", "px");
    const ySet = gsap.quickSetter(cursor, "y", "px");

    window.addEventListener("pointermove", e => {
        mouse.x = e.x;
        mouse.y = e.y;
    });

    gsap.set(".slider-drag-cursor", {
        xPercent: -50,
        yPercent: -50
    });
    gsap.ticker.add(() => {
        const dt = 1.0 - Math.pow(1.0 - speed, gsap.ticker.deltaRatio());
        pos.x += (mouse.x - pos.x) * dt;
        pos.y += (mouse.y - pos.y) * dt;
        xSet(pos.x);
        ySet(pos.y);
    });


    $(".slider-drag-wrap").on("mouseenter mouseleave", function (event) {
        $('.slider-drag-cursor').toggleClass('active', event.type === "mouseenter");
    });
    
    $(".slider-drag-wrap a").on("mouseenter mouseleave", function (event) {
        $('.slider-drag-cursor').toggleClass('active', event.type !== "mouseenter");
    });
    

    /* cursor area end */



  

    $(document).on("DOMContentLoaded", function () {
        // Animate each progress bar
        $('.choose-progress-bar .progress-fill .counter').each(function () {
            var percent = $(this).html();
            var pTop = 100 - parseInt(percent) + "%"; 
            $(this).parent().css({
                height: percent // Set height dynamically
            }).animate({
                height: percent // Smooth bottom-to-top animation
            }, 1000);
        }); 
    
        // Calculate total progress
        var total = 0, count = 0;
        $('.choose-progress-bar .progress-fill .counter').each(function () {
            total += parseInt($(this).html());
            count++;
        });
    
        var average = Math.round(total / count) + "%";
        $('#progress-counter').html(average);
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

    /*----------- serviceAccordion ----------*/
    $('#serviceAccordion').on('show.bs.collapse', function (event) {
        var activeIndex = $(event.target).closest('.accordion-item').index();
        $('.th-accordion_images img').removeClass('active');
        $('.th-accordion_images img').eq(activeIndex).addClass('active');
    });

    // Show the first tab and hide the rest
    $('.accordion-item-wrapp li:first-child').addClass('active');
    $('.according-img-tab').hide();
    $('.according-img-tab:first').show();

    // Click function
    $('.accordion-item-wrapp .accordion-item-content').mouseenter(function () {
        $('.accordion-item-wrapp .accordion-item-content').removeClass('active');
        // $(this).addClass('active');
        $('.according-img-tab').hide();

        var activeTab = $(this).find('.accordion-tab-item').attr('data-bs-target');
        $(activeTab).fadeIn();
        return false;
    });

    /*----------- 13. Filter ----------*/
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

    /*----------- 14. Counter Up ----------*/
    $(".counter-number").counterUp({
        delay: 10,
        time: 1000,
    });

    /*----------- 15. Hover Item ----------*/
    $(document).on('mouseover', '.hover-item', function () {
        $(this).addClass('item-active');
        $('.hover-item').removeClass('item-active');
        $(this).addClass('item-active');
    });

    /************lettering js***********/
    function injector(t, splitter, klass, after) {
        var a = t.text().split(splitter),
            inject = '';
        if (a.length) {
            $(a).each(function (i, item) {
                inject += '<span class="' + klass + (i + 1) + '">' + item + '</span>' + after;
            });
            t.empty().append(inject);
        }
    }

    var methods = {
        init: function () {

            return this.each(function () {
                injector($(this), '', 'char', '');
            });

        },

        words: function () {

            return this.each(function () {
                injector($(this), ' ', 'word', ' ');
            });

        },

        lines: function () {

            return this.each(function () {
                var r = "eefec303079ad17405c889e092e105b0";
                // Because it's hard to split a <br/> tag consistently across browsers,
                // (*ahem* IE *ahem*), we replaces all <br/> instances with an md5 hash 
                // (of the word "split").  If you're trying to use this plugin on that 
                // md5 hash string, it will fail because you're being ridiculous.
                injector($(this).children("br").replaceWith(r).end(), r, 'line', '');
            });

        }
    };

    $.fn.lettering = function (method) {
        // Method calling logic
        if (method && methods[method]) {
            return methods[method].apply(this, [].slice.call(arguments, 1));
        } else if (method === 'letters' || !method) {
            return methods.init.apply(this, [].slice.call(arguments, 0)); // always pass an array
        }
        $.error('Method ' + method + ' does not exist on jQuery.lettering');
        return this;
    };

    $(".discount-anime").lettering();

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

    /*----------- 18. Progress Bar Animation ----------*/
    // skill 
    $(function () { 
        $('.progress-bar').each(function () {
            $(this).find('.progress-content').animate({
                width: $(this).attr('data-percentage')
            }, 2000);

            $(this).find('.progress-number-mark').animate({
                left: $(this).attr('data-percentage')
            }, {
                duration: 2000,    
                step: function (now, fx) {
                    var data = Math.round(now);
                    $(this).find('.percent').html(data + '%');
                }
            });
        });
    });

    /*----------- 19. Price Slider ----------*/
    $(".price_slider").slider({
        range: true,
        min: 10,
        max: 100,
        values: [10, 75],
        slide: function (event, ui) {
            $(".from").text("$" + ui.values[0]);
            $(".to").text("$" + ui.values[1]);
        }
    });
    $(".from").text("$" + $(".price_slider").slider("values", 0));
    $(".to").text("$" + $(".price_slider").slider("values", 1));

    /*----------- 20. Tilt Active ----------*/
    $('.tilt-active').tilt({
        maxTilt: 7,
        perspective: 1000,
    })

    /*----------- 16. Progress Bar Animation ----------*/
    $(".progress-bar").waypoint(
        function () {
            $(".progress-bar").css({
                animation: "animate-positive 1.8s",
                opacity: "1",
            });
        },
        { offset: "100%" }
    );

    /*---------- 22. Circle Progress ----------*/
    function animateElements() {
        $('.feature-circle .progressbar').each(function () {
            var pathColor = $(this).attr('data-path-color');
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
                    thickness: 6,
                    emptyFill: "#33425A",
                    lineCap: 'round',
                    fill: {
                        color: pathColor,
                    }
                }).on('circle-animation-progress', function (event, progress, stepValue) {
                    $(this).find('.circle-num').text((stepValue * 100).toFixed(0) + "%");
                }).stop();
            }
        });

        $('.skill-circle .progressbar').each(function () {
            var pathColor = $(this).attr('data-path-color');
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
                    size: 176,
                    thickness: 8,
                    emptyFill: "#EFF1F9",
                    lineCap: 'round',
                    fill: {
                        color: pathColor,
                    }
                }).on('circle-animation-progress', function (event, progress, stepValue) {
                    $(this).find('.circle-num').text((stepValue * 100).toFixed(0) + "%");
                }).stop();
            }
        });
    }

    // Show animated elements
    animateElements();
    $(window).scroll(animateElements);

    function radial_animate() {
        $('.radial-progress').each(function (index, value) {

            $(this).find($('circle.bar--animated')).removeAttr('style');
            // Get element in Veiw port
            var elementTop = $(this).offset().top;
            var elementBottom = elementTop + $(this).outerHeight();
            var viewportTop = $(window).scrollTop();
            var viewportBottom = viewportTop + $(window).height();

            if (elementBottom > viewportTop && elementTop < viewportBottom) {
                var percent = $(value).data('countervalue');
                var radius = $(this).find($('circle.bar--animated')).attr('r');
                var circumference = 2 * Math.PI * radius;
                var strokeDashOffset = circumference - ((percent * circumference) / 100);
                $(this).find($('circle.bar--animated')).animate({
                    'stroke-dashoffset': strokeDashOffset
                }, 2800);
            }
        });
    }
    // To check If it is in Viewport 
    var $window = $(window);

    function check_if_in_view() {
        $('.countervalue').each(function () {
            if ($(this).hasClass('start')) {
                var elementTop = $(this).offset().top;
                var elementBottom = elementTop + $(this).outerHeight();

                var viewportTop = $(window).scrollTop();
                var viewportBottom = viewportTop + $(window).height();

                if (elementBottom > viewportTop && elementTop < viewportBottom) {
                    $(this).removeClass('start');
                    $('.countervalue').text();
                    var myNumbers = $(this).text();
                    if (myNumbers == Math.floor(myNumbers)) {
                        $(this).animate({
                            Counter: $(this).text()
                        }, {
                            duration: 2800,
                            easing: 'swing',
                            step: function (now) {
                                $(this).text(Math.ceil(now) + '%');
                            }
                        });
                    } else {
                        $(this).animate({
                            Counter: $(this).text()
                        }, {
                            duration: 2800,
                            easing: 'swing',
                            step: function (now) {
                                $(this).text(now.toFixed(2) + '$');
                            }
                        });
                    }

                    radial_animate();
                }
            }
        });
    }

    $window.on('scroll', check_if_in_view);
    $window.on('load', check_if_in_view);

    /*----------- 21. Overlay Direction ----------*/

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

        // /*----------- Pricing-switch & Tab ----------*/
        var e = document.getElementById("filt-monthly"),
        d = document.getElementById("filt-yearly"),
        t = document.getElementById("switcher"),
        m = document.getElementById("monthly"),
        y = document.getElementById("yearly");


    if ($('.pricing-tabs').length) {
        e.addEventListener("click", function () {
            t.checked = false;
            e.classList.add("toggler--is-active");
            d.classList.remove("toggler--is-active");
            m.classList.remove("hide");
            y.classList.add("hide");
        });

        d.addEventListener("click", function () {
            t.checked = true;
            d.classList.add("toggler--is-active");
            e.classList.remove("toggler--is-active");
            m.classList.add("hide");
            y.classList.remove("hide");
        });

        t.addEventListener("click", function () {
            d.classList.toggle("toggler--is-active");
            e.classList.toggle("toggler--is-active");
            m.classList.toggle("hide");
            y.classList.toggle("hide");
        });
    }

    /* ==================================================
			# Wow Init
    ===============================================*/
    var wow = new WOW({
        boxClass: 'wow',
        animateClass: 'animated',
        offset: 0,
        mobile: true,
        live: true
    });
    new WOW().init();


    $('.th-progress').each(function () {
        // Get the progress value from the data-progress-value attribute
        var progress = $(this).data('progress-value') / 100; // Convert percentage to decimal
        
        // Select the SVG elements
        var $halfCircle = $(this).find('.half-circle');
        var $text = $(this).find('text');
        
        var circumference = 251.2; // Circumference of the circle
        var offset = circumference - (progress * circumference); // Calculate stroke-dashoffset

        // Animate the stroke-dashoffset
        $halfCircle.css({
            transition: 'stroke-dashoffset 1s ease-in-out',
            'stroke-dashoffset': offset
        });

        // Animate the percentage text update
        $({ progressValue: 0 }).animate({ progressValue: progress * 100 }, {
            duration: 1000,
            step: function (now) {
                $text.text(Math.round(now) + '%');
            }
        });
    });
   
	// Background image area start here ***
    $("[data-background]").each(function () {
        let bgImage = $(this).attr("data-background");
        if (bgImage) {
            $(this).css("background-image", `url(${bgImage})`);
        }
    });
    // Background image area end here ***
    
    // Background image hover change area start here ***
    $(".project-item").on("mouseenter mouseleave", function (event) {
        let newBackground = event.type === "mouseenter" ? $(this).data("bg") : "";
        $(".project-area2")
            .attr("data-background", newBackground)
            .css("background-image", newBackground ? `url(${newBackground})` : "");
    });
    // Background image hover change area end here ***

    /*----------- 00. Color Scheme ----------*/

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

    // $('select').niceSelect(); 
    if ($('.nice-select').length) {
        $('.nice-select').niceSelect();
    }

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
                    solak_content_load_scripts();
                    $(".th-slider").addClass('fade-ani');
                }, 500);
            });
        }
    });

    // Window Load
    $(window).on('load', function () {
        
        solak_content_load_scripts();

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