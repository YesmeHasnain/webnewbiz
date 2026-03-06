(function ($) {
"use strict";

    $(document).ready(function() {

	    //Default Action
	    $(".vt-nav-tab a:first").addClass("active").show(); //Activate first tab
	    $(".vt-nav-tab a:first").show(); //Show first tab content

	    //On Click Event
	    $("vt-nav-tab a").click(function() {
	        $("vt-nav-tab a").removeClass("active"); //Remove any "active" class
	        $(this).addClass("active"); //Add "active" class to selected tab
	        return false;
	    });

	});

})(jQuery);


