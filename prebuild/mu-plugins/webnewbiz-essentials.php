<?php
/**
 * Plugin Name: Webnewbiz Essentials
 * Description: Auto-loaded must-use plugin for Webnewbiz generated sites. Handles custom CSS output, Elementor CSS regeneration, and full-width layout fixes.
 * Version: 1.0
 * Author: Webnewbiz
 */

if (!defined('ABSPATH')) exit;

/**
 * 1. Output custom CSS stored in wp_options by the Webnewbiz builder.
 *    This CSS includes brand colors, typography overrides, and layout fixes.
 */
add_action('wp_head', function () {
    $css = get_option('webnewbiz_custom_css', '');
    if ($css) {
        echo "\n<style id=\"webnewbiz-custom-css\">\n" . $css . "\n</style>\n";
    }
}, 999);

/**
 * 2. Force full-width layout for Elementor pages.
 *    Removes theme content wrappers that constrain width.
 */
add_action('wp_head', function () {
    if (!defined('ELEMENTOR_VERSION')) return;

    echo '<style id="webnewbiz-elementor-fixes">
/* Full width Elementor content */
.elementor-page .site-content,
.elementor-page .content-area,
.elementor-page #primary,
.elementor-page #main {
    margin: 0 !important;
    padding: 0 !important;
    max-width: 100% !important;
    width: 100% !important;
}
/* Hide theme header/footer on HFE template pages */
body.elementor-template-full-width .site-header,
body.elementor-template-full-width .site-footer,
body.elementor-template-canvas .site-header,
body.elementor-template-canvas .site-footer {
    display: none !important;
}
/* Ensure sections span full width */
.elementor-section.elementor-section-stretched {
    width: 100vw !important;
    max-width: 100vw !important;
}
/* Fix Font Awesome icons in HTML cards */
.elementor-widget-text-editor .fas,
.elementor-widget-text-editor .far,
.elementor-widget-text-editor .fab {
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
</style>
';
}, 998);

/**
 * 3. Enqueue Font Awesome 5 (needed for HTML card icons).
 *    Elementor includes it for its own widgets but our inline HTML cards need it too.
 */
add_action('wp_enqueue_scripts', function () {
    if (defined('ELEMENTOR_VERSION')) {
        wp_enqueue_style(
            'font-awesome-5-all',
            ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/all.min.css',
            [],
            '5.15.3'
        );
    }
}, 20);

/**
 * 4. Trigger Elementor CSS regeneration on first frontend visit.
 *    The build job warmup always times out (XAMPP single-thread issue),
 *    so CSS is generated lazily on first real browser visit.
 */
add_action('wp', function () {
    if (is_admin() || !defined('ELEMENTOR_VERSION') || wp_doing_ajax()) return;

    // Only run once — check a flag
    $cssGenerated = get_option('webnewbiz_elementor_css_generated', '');
    if ($cssGenerated) return;

    // Mark as done immediately to prevent concurrent regeneration
    update_option('webnewbiz_elementor_css_generated', '1');

    // Regenerate CSS for all Elementor pages
    if (class_exists('\Elementor\Plugin')) {
        try {
            $instance = \Elementor\Plugin::instance();
            if (isset($instance->files_manager)) {
                $instance->files_manager->clear_cache();
            }
        } catch (\Exception $e) {
            // Non-fatal — CSS will be generated per-page on visit
        }
    }
}, 5);

/**
 * 5. Add Google Fonts preconnect for faster font loading.
 */
add_action('wp_head', function () {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}, 1);

/**
 * 6. Entrance animations via CSS + IntersectionObserver.
 *    Works with both section-based and container-based Elementor layouts.
 *    Containers use 'animation' setting, widgets use '_animation'.
 *    Elementor adds .elementor-invisible but its JS may not fire; this is the reliable fallback.
 */
add_action('wp_head', function () {
    echo '<style id="webnewbiz-animations">
@keyframes wnbFadeInUp { from { opacity:0; transform:translateY(30px); } to { opacity:1; transform:translateY(0); } }
@keyframes wnbFadeInDown { from { opacity:0; transform:translateY(-30px); } to { opacity:1; transform:translateY(0); } }
@keyframes wnbFadeInLeft { from { opacity:0; transform:translateX(-30px); } to { opacity:1; transform:translateX(0); } }
@keyframes wnbFadeInRight { from { opacity:0; transform:translateX(30px); } to { opacity:1; transform:translateX(0); } }
@keyframes wnbFadeIn { from { opacity:0; } to { opacity:1; } }
@keyframes wnbSafetyFadeIn { to { opacity: 1; } }
.elementor-invisible { visibility: visible !important; opacity: 0; }
.wnb-animated { animation-fill-mode: both; animation-timing-function: ease-out; }
.wnb-animated.wnb-slow { animation-duration: 1s; }
.wnb-animated.wnb-fadeInUp { animation-name: wnbFadeInUp; }
.wnb-animated.wnb-fadeInDown { animation-name: wnbFadeInDown; }
.wnb-animated.wnb-fadeInLeft { animation-name: wnbFadeInLeft; }
.wnb-animated.wnb-fadeInRight { animation-name: wnbFadeInRight; }
.wnb-animated.wnb-fadeIn { animation-name: wnbFadeIn; }
</style>
';
}, 997);

add_action('wp_footer', function () {
    echo '<script id="webnewbiz-animation-observer">
(function(){
    var els = document.querySelectorAll(".elementor-invisible");
    if (!els.length) return;

    function getAnim(el) {
        try {
            var ds = el.getAttribute("data-settings");
            if (ds) {
                var s = JSON.parse(ds);
                return s.animation || s._animation || "fadeInUp";
            }
        } catch(e){}
        return "fadeInUp";
    }

    var animMap = {
        fadeInUp:"wnb-fadeInUp", fadeInDown:"wnb-fadeInDown",
        fadeInLeft:"wnb-fadeInLeft", fadeInRight:"wnb-fadeInRight",
        fadeIn:"wnb-fadeIn"
    };

    function activate(el, stagger) {
        setTimeout(function(){
            var anim = getAnim(el);
            var cls = animMap[anim] || "wnb-fadeInUp";
            el.classList.remove("elementor-invisible");
            el.classList.add("wnb-animated", "wnb-slow", cls);
        }, stagger || 0);
    }

    if ("IntersectionObserver" in window) {
        var obs = new IntersectionObserver(function(entries){
            entries.filter(function(e){ return e.isIntersecting; }).forEach(function(e, i){
                activate(e.target, i * 150);
                obs.unobserve(e.target);
            });
        }, {threshold:0.05, rootMargin:"0px 0px -30px 0px"});
        els.forEach(function(el){ obs.observe(el); });
    } else {
        els.forEach(function(el, i){ activate(el, i * 100); });
    }
})();
</script>
';
}, 999);

/**
 * 7. Force-enqueue Elementor animation assets if animations are used.
 */
add_action('wp_enqueue_scripts', function () {
    if (!defined('ELEMENTOR_VERSION')) return;
    // Load Elementor's animate.css for proper animation classes
    $animateCss = ELEMENTOR_ASSETS_URL . 'lib/animations/animations.min.css';
    wp_enqueue_style('elementor-animations', $animateCss, [], ELEMENTOR_VERSION);
}, 25);
