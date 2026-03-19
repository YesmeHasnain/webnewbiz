(function ($) {
    "use strict";

    /*====  Document Ready Function =====*/
    jQuery(document).ready(function($){

        //Grid Post Masonry
        $('.layout-grid .all-posts-wrapper, .layout-grid-rs .all-posts-wrapper, .layout-grid-ls .all-posts-wrapper').imagesLoaded( function() {
            $('.layout-grid .all-posts-wrapper, .layout-grid-rs .all-posts-wrapper, .layout-grid-ls .all-posts-wrapper').masonry({
                itemSelector: '.single-post-item',
                percentPosition: true,
            });
        });

        // Gallery Post Slider
        $('.post-gallery-slider').slick({
            slidesToShow: 1,
            autoplay: true,
            autoplaySpeed: 5000,
            speed: 1500,
            dots: true,
            arrows: true,
            prevArrow: '<i class="slick-arrow slick-prev fas fa-arrow-left"></i>',
            nextArrow: '<i class="slick-arrow slick-next fas fa-arrow-right"></i>',
        });

        //Mobile Menu
        $("#main-menu").slicknav({
            allowParentLinks: true,
            prependTo: '#mobile-menu-wrap',
            label: 'Menu',
        });

        $(".mobile-menu-trigger").on("click", function(e) {
            $(".mobile-menu-container").addClass("menu-open");
            e.stopPropagation();
        });

        $(".mobile-menu-close").on("click", function(e) {
            $(".mobile-menu-container").removeClass("menu-open");
            e.stopPropagation();
        });

        // Popup Video
        $(".xcency-video-button").magnificPopup({
            type: 'video'
        });

        // Popup Image
        $('.xcency-popup-image').magnificPopup({
            type: 'image',
            gallery: {
                enabled: true
            }
        });

        // Scroll To Top
        $(window).on("scroll",function(){
            var pagescroll = $(window).scrollTop();
            if(pagescroll > 100){
                $(".scroll-to-top").addClass("scroll-to-top-visible");

            }else{
                $(".scroll-to-top").removeClass("scroll-to-top-visible");
            }
        });

        $(".scroll-to-top").click(function() {
            $("html, body").animate({ scrollTop: 0 }, "slow");
            return false;
        });

        // Post Print
        $(document).on('click', '.print-button', function(e){
            console.log();
            e.preventDefault();
            window.print();
            return false;
        });

        /* WooCommerce Related Product */


        $(".related.products .products, .upsells.products .products").slick({
            slidesToShow: 4,//relater_product_data.slide_column,
            autoplay: true,
            autoplaySpeed: "5000", //interval
            speed: 1500, // slide speed
            dots: false,
            arrows: true,
            prevArrow: '<i class="slick-arrow slick-prev fas fa-angle-left"></i>',
            nextArrow: '<i class="slick-arrow slick-next fas fa-angle-right"></i>',
            infinite: true,
            pauseOnHover: false,
            centerMode: false,
            responsive: [
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 2, //768-991
                        arrows: true,
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1, // 0 -767
                        arrows: false,
                    }
                }
            ]
        });

        //Header Search
        $(".xcency-header-src-btn").on("click", function(e) {
            $(".header-search-wrapper").addClass("search-open");
            e.stopPropagation();
        });

        $(".search-close").on("click", function(e) {
            $(".header-search-wrapper").removeClass("search-open");
            e.stopPropagation();
        });

        $('.xcency-product-thumb-image img').removeAttr('sizes');

        new WOW().init();
    });

    /*====  Window Load Function =====*/
    jQuery(window).on('load', function() {
        //Preloader
        if ($('#text-preloader').length) {
            $('#text-preloader').fadeOut();
            $('.text-preloader-wrapper').delay(500).fadeOut('slow');
        }

        $('a[href*="youtube.com/watch"]').magnificPopup({
            type: 'iframe',
            iframe: {
                patterns: {
                    youtube: {
                        index: 'youtube.com',
                        id: 'v=',
                        src: '//www.youtube.com/embed/%id%?rel=0&autoplay=1'
                    }
                }
            }
        });

    });

    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.main-navigation .menu-item-has-children > a').forEach(function (link) {
            // Create span
            const span = document.createElement('span');
            span.classList.add('xcency-submenu-toggle');
            // Append inside <a>
            link.appendChild(span);
        });
    });

}(jQuery));
