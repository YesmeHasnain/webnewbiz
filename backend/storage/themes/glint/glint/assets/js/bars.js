function bar_group() {
    group_ident = 1, jQuery(".bar_group").each(function () {
        jQuery(this).addClass("group_ident-" + group_ident), jQuery(this).data("gid", group_ident), group_ident++
    })
}

function get_max() {
    jQuery(".bar_group").each(function () {
        var a = [];
        jQuery(this).children().each(function () {
            a.push(jQuery(this).attr("data-value"))
        }), max_arr["group_ident-" + jQuery(this).data("gid")] = a, void 0 !== jQuery(this).attr("data-max") ? jQuery(this).data("bg_max", jQuery(this).attr("data-max")) : jQuery(this).data("bg_max", Math.max.apply(null, a))
    })
}

function data_labels() {
    jQuery(".bar_group__bar").each(function () {
        void 0 !== jQuery(this).attr("data-label") && jQuery('<p class="b_label">' + jQuery(this).attr("data-label") + "</p>").insertBefore(jQuery(this))
    })
}

function show_values() {
    jQuery(".bar_group__bar").each(function () {
        "true" == jQuery(this).attr("data-show-values") && (jQuery(this).css("margin-bottom", "40px"), void 0 !== jQuery(this).attr("data-unit") ? (jQuery(this).append('<p class="bar_label_min">0 ' + jQuery(this).attr("data-unit") + "</p>"), jQuery(this).append('<p class="bar_label_max">' + jQuery(this).parent().data("bg_max") + " " + jQuery(this).attr("data-unit") + "</p>")) : (jQuery(this).append('<p class="bar_label_min">0</p>'), jQuery(this).append('<p class="bar_label_max">' + jQuery(this).parent().data("bg_max") + "</p>")))
    })
}

function show_tooltips() {
    jQuery(".bar_group__bar").each(function () {
        "true" == jQuery(this).attr("data-tooltip") && (jQuery(this).css("margin-bottom", "40px"), jQuery(this).append('<div class="b_tooltip"><span>' + jQuery(this).attr("data-value") + '</span><div class="b_tooltip--tri"></div></div>'))
    })
}

function in_view(a) {
    var t = jQuery(a),
        i = jQuery(window),
        s = i.scrollTop(),
        r = s + i.height(),
        n = t.offset().top,
        o = n + t.height();
    r > o - 45 && t.css("width", t.attr("data-value") / t.parent().data("bg_max") * 100 + "%")
}

function bars() {
    bar_group(), get_max(), data_labels(), show_tooltips(), show_values()
}
max_arr = {}, jQuery(".bar_group__bar").each(function () {
    in_view(jQuery(this))
}), jQuery(window).scroll(function () {
    jQuery(".bar_group__bar").each(function () {
        in_view(jQuery(this))
    })
}), bars();