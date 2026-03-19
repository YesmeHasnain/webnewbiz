(function($) {
    "use strict";

	
		
		
    /*----------------------------------------
        Scroll to top
    ----------------------------------------*/

    function BackToTop() {
        $('.scrolltotop').on('click', function () {
        $('html, body').animate({
            scrollTop: 0
        }, 800);
        return false;
    });

        $(document).scroll(function () {
            var y = $(this).scrollTop();
            if (y > 600) {
                $('.scrolltotop').fadeIn();
            } else {
                $('.scrolltotop').fadeOut();
            }
        });

        $(document).scroll(function () {
            var m = $(this).scrollTop();
            if (m > 400) {
                $('.chat-popup').fadeIn();
            } else {
                $('.chat-popup').fadeOut();
            }
        });
    }
    
    BackToTop();
		
		
	        $(".pricing-menu-list li a").each(function () {
                $(this).on("click", function () {
                    $(this).parents(".pricing-menu-list").find('a.active').removeClass("active");
                    $(this).addClass("active");
                });
            });
            $(".pricing-menu-list .pricing_mon_swicther").on("click", function () {
                $(".pricing-month").show();
                $(".pricing-year").hide();
                return false;
            });
            $(".pricing-menu-list .pricing_yo_swicther").on("click", function () {
                $(".pricing-month").hide();
                $(".pricing-year").show();
                return false;
            }); //mk feedback slider

	
			

			new WOW().init();


jQuery(window).load(function() {
	jQuery("#preloader").fadeOut();
});


jQuery('.mainmenu ul.theme-main-menu').slicknav({
    allowParentLinks: true,
    prependTo: '.knor-responsive-menu',
    closedSymbol: "&#8594",
    openedSymbol: "&#8595",
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


	
})(jQuery);