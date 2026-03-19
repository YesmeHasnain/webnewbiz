(function($) {
	"use strict";
	
	window.scroll({
	  top: 0, 
	  left: 0, 
	  behavior: 'smooth',
	});	

	$('.accordion--link').on( "click", function() {
		var x = window.pageXOffset,
		y = window.pageYOffset;
		$(window).one('scroll', function () {
			window.scrollTo(x, y);
		})
	});
	
	
	var tooltip = document.querySelectorAll('.tool-tip');
	document.addEventListener('mousemove', fn, false);
		function fn(e) {
			for (var i=tooltip.length; i--;) {
				tooltip[i].style.left = event.clientX + 'px';
				tooltip[i].style.top = event.clientY + 'px';
			}
		}
				
		$(".img-responsive").on({
			mouseenter: function () {
			$('.img-responsive').stop().fadeTo('slow', 0.1);
			$(this).stop().fadeTo('slow', 1);
		},
			mouseleave: function () {
			$('.img-responsive').stop().fadeTo('slow', 1);
		}
	});
        
})(jQuery);