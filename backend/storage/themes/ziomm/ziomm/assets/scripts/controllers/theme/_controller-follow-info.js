/***********************************************
 * THEME: FOLLOW INFO
 ***********************************************/
(function ($) {

	'use strict';

	VLTJS.followInfo = {
		init: function ($scope) {

			if (!$('.vlt-follow-info').length) {
				VLTJS.body.append('\
			<div class="vlt-follow-info">\
			<div class="vlt-follow-info__title"></div><br>\
			<div class="vlt-follow-info__subtitle"></div>\
			</div>\
			');
			}
			var getFollowInfo = $scope.find('[data-follow-info]'),
				followInfo = $('.vlt-follow-info'),
				title = followInfo.find('.vlt-follow-info__title'),
				subtitle = followInfo.find('.vlt-follow-info__subtitle');

			getFollowInfo.each(function () {

				var currentItem = $(this);

				currentItem.on('mousemove', function (e) {

					followInfo.css({
						top: e.clientY,
						left: e.clientX
					});

				});

				currentItem.on({
					mouseenter: function () {

						var $this = $(this),
							title_text = $this.find('[data-follow-info-title]').html(),
							subtitle_text = $this.find('[data-follow-info-content]').html();

						if (!followInfo.hasClass('is-active')) {
							followInfo.addClass('is-active');
							title.html(title_text).wrapInner('<h4>');
							subtitle.html(subtitle_text).wrapInner('<span>');
						}

					},
					mouseleave: function () {

						if (followInfo.hasClass('is-active')) {
							followInfo.removeClass('is-active');
							title.html('');
							subtitle.html('');
						}

					}
				});

			});
		}
	}

	VLTJS.followInfo.init(VLTJS.body);

	VLTJS.document.on('init.vpf endLoadingNewItems.vpf', function (e) {
		VLTJS.followInfo.init(VLTJS.body);
	});

})(jQuery);