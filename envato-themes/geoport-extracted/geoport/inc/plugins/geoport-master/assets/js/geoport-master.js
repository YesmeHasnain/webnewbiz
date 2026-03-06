(function ($) {
"use strict";

    $(window).on('load', function () {
        loader();
    });

    // preloader
    function loader() {
        $('#ctn-preloader').addClass('loaded');
        $("#loading").fadeOut(500);
        if ($('#ctn-preloader').hasClass('loaded')) {
            // Es para que una vez que se haya ido el preloader se elimine toda la seccion preloader
            $('#preloader').delay(900).queue(function () {
                $(this).remove();
            });
        }
    }

    // language-select
    $('.language-name').on('click', function (event) {
        event.stopPropagation();
    });

    $(".language-name").on("click", function () {
        if ($(".list-language").hasClass('active')) {
            $(".list-language").removeClass("active");
            return false;
        } else {
            $(".list-language").addClass("active");
            return false;
        }
    });


    // Dustrial with jQuery Wrapper
    jQuery(document).ready(function(){  
        //fade in/out based on scrollTop value
        jQuery(window).scroll(function () {
            if (jQuery(this).scrollTop() > 0) {
                jQuery('#scroller').fadeIn();
            } else {
                jQuery('#scroller').fadeOut();
            }
        });
     
        // scroll body to 0px on click
        jQuery('#scroller').click(function () {
            jQuery('body,html').animate({
                scrollTop: 0
            }, 400);
            return false;
        });
    });

    jQuery('.elementor-accordion-icon-opened i').removeClass('fas fa-arrow-up');
    jQuery('.elementor-accordion-icon-opened i').addClass('fal fa-arrow-up');

    jQuery('.elementor-accordion-icon-closed i').removeClass('fas fa-arrow-down');
    jQuery('.elementor-accordion-icon-closed i').addClass('fal fa-arrow-down');

})(jQuery);