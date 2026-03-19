jQuery( document ).ready( function( $ ) {
    'use strict';

//------------------------------------- Waiting for the entire site to load ------------------------------------------------//

jQuery(window).load(function() { 
		jQuery("#loaderInner").fadeOut(); 
		jQuery("#loader").delay(400).fadeOut("slow"); 
});

$(document).ready(function(){
	
//------------------------------------- Navigation setup ------------------------------------------------//

$('a.scroll').smoothScroll({
        speed: 800,
        offset: -117
});

//------------------------------------- End navigation setup ------------------------------------------------//



//---------------------------------- Main slider setup-----------------------------------------//

$('.mainSlider').flexslider({
	animation: "fade",
	slideshow: true,
	directionNav:false,
	controlNav: true,
	animationSpeed: 1500
});


$('.mainSlider .slides li').css('height', $(window).height());


for(var i = 0; i < $('.mainSlider .slides li').length; i++){
    
    var path = $('.mainSlider .slides li').eq(i).find('img.slide').attr('src');
	$('.mainSlider .slides li').eq(i).addClass('parallax');
    $('.mainSlider .slides li').eq(i).css('backgroundImage', 'url("' + path + '")');
    $('.mainSlider .slides li').eq(i).find('img.slide').detach();
    
}


$(document).scroll(function () {

        var treshhold = Math.round($(window).scrollTop() / 5);
        $('li.parallax').css('backgroundPosition', '100% ' + treshhold + 'px');    
});

//---------------------------------- End main slider setup-----------------------------------------//





//---------------------------------- Site slider-----------------------------------------//


$('.testiSlider').flexslider({
	animation: "slide",
	slideshow: true,
	directionNav:false,
	controlNav: false
});


$('.postSlider, .postSliderLarge, .projectSlider').flexslider({
	animation: "slide",
	slideshow: true,
	directionNav:false,
	controlNav: true
});


//---------------------------------- End site slider-----------------------------------------//



//---------------------------------- Testimonials parallax-----------------------------------------//

$(".testimonials").parallax("100%", 0.3);

//---------------------------------- End testimonials parallax -----------------------------------------//


//---------------------------------- Portfolio -----------------------------------------//

$(".itemDesc").css({ opacity: 0 });

//--------------------------------- Hover animation for the elements of the portfolio --------------------------------//
				
	
$('.itemDesc').hover( function(){ 
	$(this).stop().animate({ opacity: 1 }, 'slow');
}, function(){ 
	$(this).stop().animate({ opacity: 0 }, 'slow'); 
});

	$('.itemDesc').hover(function () {
    var projDesc = $(this).find('.itemDesc');
    var offset = ($(this).height() / 2) - (projDesc.height() / 2);
    $(this).find('.itemDescInner').css('padding-top', offset -30);
});
			

//--------------------------------- End hover animation for the elements of the portfolio --------------------------------//

//-----------------------------------Initilaizing magnificPopup for the portfolio-------------------------------------------------//

		$('.folio').magnificPopup({
		  type: 'image'
		});

				
//-----------------------------------End initilaizing fancybox for the portfolio-------------------------------------------------//

	//--------------------------------- Sorting portfolio elements with quicksand plugin  --------------------------------//
	
		var $portfolioClone = $('.portfolio').clone();

		$('.filter a').click(function(e){
			$('.filter li').removeClass('current');	
			var $filterClass = $(this).parent().attr('class');
			if ( $filterClass == 'all' ) {
				var $filteredPortfolio = $portfolioClone.find('li');
			} else {
				var $filteredPortfolio = $portfolioClone.find('li[data-type~=' + $filterClass + ']');
			}
			$('.portfolio').quicksand( $filteredPortfolio, { 
				duration: 800,
				easing: 'easeInOutQuad' 
			}, function(){
					$('.itemDesc').hover( function(){ 
						$(this).stop().animate({ opacity: 1 }, 'slow');
					}, function(){ 
						$(this).stop().animate({ opacity: 0 }, 'slow'); 
					});
					
						$('.itemDesc').hover(function () {
					    var projDesc = $(this).find('.itemDesc');
					    var offset = ($(this).height() / 2) - (projDesc.height() / 2);
					    $(this).find('.itemDescInner').css('padding-top', offset -30);
					});
					
					


//------------------------------ Reinitilaizing fancybox for the new cloned elements of the portfolio----------------------------//


			
			$('.folio').magnificPopup({
			  type: 'image'
			});


//-------------------------- End reinitilaizing fancybox for the new cloned elements of the portfolio ----------------------------//

			});


			$(this).parent().addClass('current');
			e.preventDefault();
		});

//--------------------------------- End sorting portfolio elements with quicksand plugin--------------------------------//


//---------------------------------- End portfolio-----------------------------------------//





//---------------------------------- Form validation-----------------------------------------//






//---------------------------------- End form validation-----------------------------------------//



//--------------------------------- Mobile menu --------------------------------//


var mobileBtn = $('.mobileBtn');
	var nav = $('.mainNav ul');
	var navHeight= nav.height();

$(mobileBtn).click(function(e) {
		e.preventDefault();
		nav.slideToggle();
		$('.mainNav li a').addClass('mobile');


});

$(window).resize(function(){
		var w = $(window).width();
		if(w > 320 && nav.is(':hidden')) {
			nav.removeAttr('style');
			$('.mainNav li a').removeClass('mobile');
		}

});



$('.mainNav li a').click(function(){
	if ($(this).hasClass('mobile')) {
        nav.slideToggle();
	}

});


//--------------------------------- End mobile menu --------------------------------//

});

});