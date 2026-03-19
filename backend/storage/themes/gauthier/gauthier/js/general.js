/*
Bones Scripts File
Author: Eddie Machado
*/
(function($) {
    "use strict";

    // IE8 ployfill for GetComputed Style (for Responsive Script below)
    if (!window.getComputedStyle) {
        window.getComputedStyle = function(el, pseudo) {
            this.el = el;
            this.getPropertyValue = function(prop) {
                var re = /(\-([a-z]){1})/g;
                if (prop == 'float') prop = 'styleFloat';
                if (re.test(prop)) {
                    prop = prop.replace(re, function() {
                        return arguments[2].toUpperCase();
                    });
                }
                return el.currentStyle[prop] ? el.currentStyle[prop] : null;
            }
            return this;
        }
    }

    // as the page loads, call these scripts
    $(document).ready(function($) {

        /*
        Responsive jQuery is a tricky thing.
        There's a bunch of different ways to handle
        it, so be sure to research and find the one
        that works for you best.
        */

        /* getting viewport width */
        var responsive_viewport = $(window).width();

        /* if is below 481px */
        if (responsive_viewport < 481) {

        } /* end smallest screen */

        /* if is larger than 481px */
        if (responsive_viewport > 481) {

        } /* end larger than 481px */

        /* if is above or equal to 768px */
        if (responsive_viewport >= 768) {
            /* load gravatars */
            $('.comment img[data-gravatar]').each(function() {
                $(this).attr('src', $(this).attr('data-gravatar'));
            });
        }

        /* off the bat large screen actions */
        if (responsive_viewport > 1030) {}

// ALL CUSTOM SCRIPT HERE

	/*
	    Marquee on header
	*/
			$(function (){

				/* Example options:
				
					let options = {
						autostart: true,
						property: 'value',
						onComplete: null,
						duration: 20000,
						padding: 10,
						marquee_class: '.marquee',
						container_class: '.simple-marquee-container',
						sibling_class: 0,
						hover: true,
						velocity: 0.1
						direction: 'right'
					}

					$('.simple-marquee-container').SimpleMarquee(options);
					
				*/

				$('.simple-marquee-container').SimpleMarquee({
					duration: 100000


				});
				
			});
			


			
	/*
	    Slidebar with widget
	*/
	$('.dismiss, .overlay').on('click', function() {
        $('.Sidebar1').removeClass('active');
        $('.overlay').removeClass('active');
    });

    $('.open-menu').on('click', function(e) {
    	e.preventDefault();
        $('.Sidebar1').addClass('active');
        $('.overlay').addClass('active');
        // close opened sub-menus
        $('.collapse.show').toggleClass('show');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
    });
	
	
	// Theme switch
	var themeSwitch = document.getElementById('themeSwitch');
	if(themeSwitch) {
		initTheme(); // if user has already selected a specific theme -> apply it
		themeSwitch.addEventListener('change', function(event){
    	resetTheme(); // update color theme
    });

    function initTheme() {
    	var darkThemeSelected = (localStorage.getItem('themeSwitch') !== null && localStorage.getItem('themeSwitch') === 'dark');
    	// update checkbox
    	themeSwitch.checked = darkThemeSelected;
			// update body class attribute
			darkThemeSelected ? document.body.setAttribute('class', 'dark') : document.body.removeAttribute('class');
    };

    function resetTheme() {
    	if(themeSwitch.checked) { // dark theme has been selected
    		document.body.setAttribute('class', 'dark');
    		localStorage.setItem('themeSwitch', 'dark');
    	} else {
    		document.body.removeAttribute('class');
    		localStorage.removeItem('themeSwitch');
    	} 
    };
	}
	
	
    // BACK TO TOP
		$(window).scroll(function(){ 
        if ($(this).scrollTop() > 400) { 
            $('#back-top').fadeIn(); 
        } else { 
            $('#back-top').fadeOut(); 
        } 
		}); 
		$('#back-top').click(function(){ 
        $("html, body").animate({ scrollTop: 0 }, 600); 
        return false; 
		}); 
	// ADDING CLASS TO FIRST LETTER	
		$(".ctest").each(function () {
		var el = $(this),
		text = el.html(),
		first = text.slice(0, 1),
		rest = text.slice(1);
		el.html("<span class='firstletter'>" + first + "</span>" + rest);
		});
	//	CREATE COLUMNS
		$('.content_two_column,.ctest').columnize({
		width:400,
		columns : 2,
		buildOnce : true,
		lastNeverTallest: true
		});

	//	ADD SPAN TO MONTH ON CALENDAR
		$('#wp-calendar > caption').each(function(){
			var featureTitle = $(this);
			featureTitle.html( featureTitle.text().replace(/(^\w+)/,'<span>$1</span>') );
			});		
    // ADD WIDTH FOR fulldiv class 
        $(".fulldiv").css("width", "+=200");
   // FOR SCROLL STICKY AREA 		
        $('.category1-wrapperinside .sidebar, .single2-wrapper .sidebar')
            .theiaStickySidebar({
                additionalMarginTop: 30,
                additionalMarginBottom: 30
            });


    }); /* end of as page load scripts */

})(jQuery);


/*! A fix for the iOS orientationchange zoom bug.
 Script by @scottjehl, rebound by @wilto.
 MIT License.
*/
(function(w) {
    "use strict";
    // This fix addresses an iOS bug, so return early if the UA claims it's something else.
    if (!(/iPhone|iPad|iPod/.test(navigator.platform) && navigator.userAgent.indexOf("AppleWebKit") > -1)) {
        return;
    }
    var doc = w.document;
    if (!doc.querySelector) {
        return;
    }
    var meta = doc.querySelector("meta[name=viewport]"),
        initialContent = meta && meta.getAttribute("content"),
        disabledZoom = initialContent + ",maximum-scale=1",
        enabledZoom = initialContent + ",maximum-scale=10",
        enabled = true,
        x, y, z, aig;
    if (!meta) {
        return;
    }

    function restoreZoom() {
        meta.setAttribute("content", enabledZoom);
        enabled = true;
    }

    function disableZoom() {
        meta.setAttribute("content", disabledZoom);
        enabled = false;
    }

    function checkTilt(e) {
        aig = e.accelerationIncludingGravity;
        x = Math.abs(aig.x);
        y = Math.abs(aig.y);
        z = Math.abs(aig.z);
        // If portrait orientation and in one of the danger zones
        if (!w.orientation && (x > 7 || ((z > 6 && y < 8 || z < 8 && y > 6) && x > 5))) {
            if (enabled) {
                disableZoom();
            }
        } else if (!enabled) {
            restoreZoom();
        }
    }
    w.addEventListener("orientationchange", restoreZoom, false);
    w.addEventListener("devicemotion", checkTilt, false);
})(this);