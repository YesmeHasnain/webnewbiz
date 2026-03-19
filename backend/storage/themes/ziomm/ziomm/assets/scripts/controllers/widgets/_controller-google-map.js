/***********************************************
 * WIDGET: GOOGLE MAP
 ***********************************************/
(function ($) {

	'use strict';

	VLTJS.googleMap = {
		init: function ($scope) {

			var googleMap = $scope.find('.vlt-google-map'),
				map_lat = googleMap.data('map-lat'),
				map_lng = googleMap.data('map-lng'),
				map_zoom = googleMap.data('map-zoom'),
				map_gesture_handling = googleMap.data('map-gesture-handling'),
				map_zoom_control = googleMap.data('map-zoom-control') ? true : false,
				map_zoom_control_position = googleMap.data('map-zoom-control-position'),
				map_default_ui = googleMap.data('map-default-ui') ? false : true,
				map_type = googleMap.data('map-type'),
				map_type_control = googleMap.data('map-type-control') ? true : false,
				map_type_control_style = googleMap.data('map-type-control-style'),
				map_type_control_position = googleMap.data('map-type-control-position'),
				map_streetview_control = googleMap.data('map-streetview-control') ? true : false,
				map_streetview_position = googleMap.data('map-streetview-position'),
				map_info_window_width = googleMap.data('map-info-window-width'),
				map_locations = googleMap.data('map-locations'),
				map_styles = googleMap.data('map-style') || '',
				infowindow,
				map;

			function initMap() {

				var myLatLng = {
					lat: parseFloat(map_lat),
					lng: parseFloat(map_lng)
				};

				if (typeof google === 'undefined') {
					return;
				}

				var map = new google.maps.Map(googleMap[0], {
					center: myLatLng,
					zoom: map_zoom,
					disableDefaultUI: map_default_ui,
					zoomControl: map_zoom_control,
					zoomControlOptions: {
						position: google.maps.ControlPosition[map_zoom_control_position]
					},
					mapTypeId: map_type,
					mapTypeControl: map_type_control,
					mapTypeControlOptions: {
						style: google.maps.MapTypeControlStyle[map_type_control_style],
						position: google.maps.ControlPosition[map_type_control_position]
					},
					streetViewControl: map_streetview_control,
					streetViewControlOptions: {
						position: google.maps.ControlPosition[map_streetview_position]
					},
					styles: map_styles,
					gestureHandling: map_gesture_handling,
				});

				$.each(map_locations, function (index, googleMapement, content) {

					var content = '\
					<div class="vlt-google-map__container">\
					<h6>' + googleMapement.title + '</h6>\
					<div>' + googleMapement.text + '</div>\
					</div>';

					var icon = '';

					if (googleMapement.pin_icon !== '') {
						if (googleMapement.pin_icon_custom) {
							icon = googleMapement.pin_icon_custom;
						} else {
							icon = '';
						}
					}

					var marker = new google.maps.Marker({
						map: map,
						position: new google.maps.LatLng(parseFloat(googleMapement.lat), parseFloat(googleMapement.lng)),
						animation: google.maps.Animation.DROP,
						icon: icon,
					});

					if (googleMapement.title !== '' && googleMapement.text !== '') {
						addInfoWindow(marker, content);
					}

					google.maps.event.addListener(marker, 'click', toggleBounce);

					function toggleBounce() {
						if (marker.getAnimation() != null) {
							marker.setAnimation(null);
						} else {
							marker.setAnimation(google.maps.Animation.BOUNCE);
						}
					}

				});
			}

			function addInfoWindow(marker, content) {
				google.maps.event.addListener(marker, 'click', function () {
					if (!infowindow) {
						infowindow = new google.maps.InfoWindow({
							maxWidth: map_info_window_width
						});
					}
					infowindow.setContent(content);
					infowindow.open(map, marker);
				});
			}

			initMap();

		}
	}

	VLTJS.window.on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/vlt-google-map.default',
			VLTJS.googleMap.init
		);
	});

})(jQuery);