/***********************************************
 * WIDGET: PIE CHART
 ***********************************************/
(function ($) {

	'use strict';

	// check if plugin defined
	if (typeof $.fn.circleProgress === 'undefined') {
		return;
	}

	if (typeof gsap === 'undefined') {
		return;
	}

	VLTJS.pieChart = {
		init: function ($scope) {

			var chart = $scope.find('.vlt-pie-chart'),
				bar = chart.find('.vlt-pie-chart__bar'),
				chart_value = chart.data('chart-value') || 0,
				chart_animation_duration = chart.data('chart-animation-duration') || 0,
				chart_height = chart.data('chart-height') || 0,
				chart_thickness = chart.data('chart-thickness') || 0,
				chart_track_color = chart.data('chart-track-color') || '',
				chart_bar_color = chart.data('chart-bar-color') || '',
				delay = 150,
				obj = {
					count: 0
				};

			// predraw
			bar.circleProgress({
				startAngle: -Math.PI / 2,
				value: 0,
				size: chart_height,
				thickness: chart_thickness,
				fill: chart_bar_color,
				emptyFill: chart_track_color,
				animation: {
					duration: chart_animation_duration,
					easing: 'circleProgressEasing',
					delay: delay
				}
			});

			chart.one('inview', function () {

				bar.circleProgress({
					value: chart_value / 100,
				});

				gsap.to(obj, chart_animation_duration / 1000, {
					count: chart_value,
					delay: delay / 1000,
					onUpdate: function () {
						chart.find('.vlt-pie-chart__title > .counter').text(Math.round(obj.count));
					}
				});

			});

		}
	}

	VLTJS.window.on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/vlt-pie-chart.default',
			VLTJS.pieChart.init
		);
	});

})(jQuery);