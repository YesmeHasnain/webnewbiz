// Avoid `console` errors in browsers that lack a console.
(function() {
    var method;
    var noop = function () {};
    var methods = [
        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
        'timeline', 'timelineEnd', 'timeStamp', 'trace', 'warn'
    ];
    var length = methods.length;
    var console = (window.console = window.console || {});

    while (length--) {
        method = methods[length];

        // Only stub undefined methods.
        if (!console[method]) {
            console[method] = noop;
        }
    }
}());


/*** rangeslider ***/
jQuery("document").ready(function () {
    jQuery(".rangeS").rangeslider();
});
jQuery.fn.rangeslider = function (options) {
    var obj = this;
    var defautValue = obj.attr("value");
    obj.wrap("<span class='range-slider'></span>");
    obj.after("<span class='slider-container'><span class='bar'><span></span></span><span class='bar-btn'><span>0</span></span></span>");
    obj.attr("oninput", "updateSlider(this)");
    updateSlider(this);
    return obj;
};

function updateSlider(passObj) {
    var obj = jQuery(passObj);
    var value = obj.val();
    var min = obj.attr("min");
    var max = obj.attr("max");
    var range = Math.round(max - min);
    var percentage = Math.round((value - min) * 100 / range);
    var nextObj = obj.next();
    nextObj.find("span.bar-btn").css("left", percentage + "%");
    nextObj.find("span.bar > span").css("width", percentage + "%");
    nextObj.find("span.bar-btn > span").text(percentage);
};


// Google Map
function basicmap() {
    // Basic options for a simple Google Map
    // For more options see: https://developers.google.com/maps/documentation/javascript/reference#MapOptions
    var mapOptions = {
        // How zoomed in you want the map to start at (always required)
        zoom: 11,
        scrollwheel: false,
        // The latitude and longitude to center the map (always required)
        center: new google.maps.LatLng(40.6700, -73.9400), // New York
        // This is where you would paste any style found on Snazzy Maps.
        styles: [{ featureType: "administrative", elementType: "labels.text.fill", stylers: [{ color: "#444444" }] }, { featureType: "landscape", elementType: "all", stylers: [{ color: "#f2f2f2" }] }, { featureType: "poi", elementType: "all", stylers: [{ visibility: "off" }] }, { featureType: "road", elementType: "all", stylers: [{ saturation: -100 }, { lightness: 45 }] }, { featureType: "road.highway", elementType: "all", stylers: [{ visibility: "simplified" }] }, { featureType: "road.arterial", elementType: "labels.icon", stylers: [{ visibility: "off" }] }, { featureType: "transit", elementType: "all", stylers: [{ visibility: "off" }] }, { featureType: "water", elementType: "all", stylers: [{ color: "#46bcec" }, { visibility: "on" }] }]
    };
    // Get the HTML DOM element that will contain your map
    // We are using a div with id="map" seen below in the <body>
    var mapElement = document.getElementById('contact-map');

    // Create the Google Map using our element and options defined above
    var map = new google.maps.Map(mapElement, mapOptions);

    // Let's also add a marker while we're at it
    var marker = new google.maps.Marker({
        position: new google.maps.LatLng(40.6700, -73.9400),
        map: map,
        icon: 'img/icon/map_icon.png',
        title: 'Geoport'
    });
}
if (jQuery('#contact-map').length != 0) {
    google.maps.event.addDomListener(window, 'load', basicmap);
}

// Place any jQuery/helper plugins in here.
jQuery('.rangeS').on('input', function (e) {
    // $(".nopb").text(e.target.value / 20 );
    var val = jQuery(this).val().replace(/\D/g,'');   // check only for digits
    jQuery('.total-ton').val( val / 10 );
})

