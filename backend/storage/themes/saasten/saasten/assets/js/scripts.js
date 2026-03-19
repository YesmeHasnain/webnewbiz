(function($) {
    "use strict";

	
		
		
		/* ----------------------------------------------------------- */
		/*  Back to top
		/* ----------------------------------------------------------- */

		$(window).scroll(function () {
			if ($(this).scrollTop() > 300) {
				 $('.backto').fadeIn();
			} else {
				 $('.backto').fadeOut();
			}
		});

		// scroll body to 0px on click
		$('.backto').on('click', function () {
			 $('.backto').tooltip('hide');
			 $('body,html').animate({
				  scrollTop: 0
			 }, 800);
			 return false;
		});

		
		

    /* ----------------------------------------------------------- */
      /*  Video popup
    /* ----------------------------------------------------------- */

    if ($('.theme-video-play-btn').length > 0) {
     $('.theme-video-play-btn').magnificPopup({
         type: 'iframe',
         mainClass: 'mfp-with-zoom',
         zoom: {
             enabled: true, // By default it's false, so don't forget to enable it

             duration: 300, // duration of the effect, in milliseconds
             easing: 'ease-in-out', // CSS transition easing function

             opener: function (openerElement) {
                 return openerElement.is('img') ? openerElement : openerElement.find('img');
             }
           }
       });
    }



  $(document).ready(function () {


        /*====================================
    Header Sticky JS
  ======================================*/
    var activeSticky = $("#active-sticky"),
      winDow = $(window);
    winDow.on("scroll", function () {
      var scroll = $(window).scrollTop(),
        isSticky = activeSticky;
      if (scroll < 80) {
        isSticky.removeClass("is-sticky");
      } else {
        isSticky.addClass("is-sticky");
      }
    });



	
    /*====================================
		Testimonial SLider JS
	======================================*/
    $(".testimonial-g-slide").slick({
      autoplay: true,
      speed: 500,
      autoplaySpeed: 3500,
      slidesToShow: 2,
      slidesToScroll: 1,
      pauseOnHover: true,
      infinite: true,
      dots: true,
      arrows: false,
      cssEase: "ease",
      draggable: true,
      prevArrow: '<button class="Prev">Prev</button>',
      nextArrow: '<button class="Prev">Next</button>',
      responsive: [
        {
          breakpoint: 1500,
          settings: {
            slidesToShow: 2,
          },
        },
        {
          breakpoint: 992,
          settings: {
            slidesToShow: 1,
          },
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 1,
          },
        },
      ],
    });


    /*====================================
    Aos Animate JS
  ======================================*/
    // AOS.init({
    //   duration: 1500,
    //   disable: !1,
    //   offset: 0,
    //   once: !0,
    //   easing: "ease",
    // });




  /*====================================
    Mobile Menu
  ======================================*/
  var $offcanvasNav = $("#offcanvas-menu a");
  $offcanvasNav.on("click", function () {
    var link = $(this);
    var closestUl = link.closest("ul");
    var activeLinks = closestUl.find(".active");
    var closestLi = link.closest("li");
    var linkStatus = closestLi.hasClass("active");
    var count = 0;

    closestUl.find("ul").slideUp(function () {
      if (++count == closestUl.find("ul").length)
        activeLinks.removeClass("active");
    });
    if (!linkStatus) {
      closestLi.children("ul").slideDown();
      closestLi.addClass("active");
    }
  });


  /*====================================
    Scrool To Top JS
  ======================================*/
  var lastScrollTop = "";
  var scrollToTopBtn = ".scrollToTop";

  function stickyMenu($targetMenu, $toggleClass) {
    var st = $(window).scrollTop();
    if ($(window).scrollTop() > 600) {
      if (st > lastScrollTop) {
        $targetMenu.removeClass($toggleClass);
      } else {
        $targetMenu.addClass($toggleClass);
      }
    } else {
      $targetMenu.removeClass($toggleClass);
    }
    lastScrollTop = st;
  }
  $(scrollToTopBtn).on("click", function (e) {
    e.preventDefault();
    $("html, body").animate(
      {
        scrollTop: 0,
      },
      500
    );
    return false;
  });
  $(window).on("scroll", function () {
    stickyMenu($(".sticky-header"), "active");
    if ($(this).scrollTop() > 400) {
      $(scrollToTopBtn).addClass("show");
    } else {
      $(scrollToTopBtn).removeClass("show");
    }
  });



  /*====================================
    Preloader JS
   ======================================*/
  $(window).on("load", function (event) {
    $(".preloader").delay(800).fadeOut(500);
  });





   });
	
			


document.addEventListener("DOMContentLoaded", function () {
  const buttons = document.querySelectorAll(
    ".software-card__button, .software-card__heading"
  );

  buttons.forEach(function (button) {
    button.addEventListener("click", function () {
      const softwareCard = button.closest(".software-card");
      const expandContent = softwareCard.querySelector(".software-card-expand");
      const softwareCardParent = softwareCard.parentElement;

      // Remove active class from all .software-card parents
      const allSoftwareCards = document.querySelectorAll(".software-card");
      allSoftwareCards.forEach(function (card) {
        if (card !== softwareCard) {
          card.classList.remove("active");
          card.parentElement.classList.remove("active");
        }
      });

      // Toggle active class for .software-card-expand
      expandContent.classList.toggle("active");

      // Toggle active class for .software-card
      softwareCard.classList.toggle("active");

      // Toggle active class for parent of .software-card
      softwareCardParent.classList.toggle("active");
    });
  });
});
       
	
})(jQuery);