<?php
/**
 * Coop B-Ball site — NATIVE Elementor widgets (client-editable)
 * CSS handles styling, Elementor widgets handle content
 */

$dbHost = '127.0.0.1';
$dbUser = 'root';
$dbPass = '';
$dbName = 'wp_zaid';
$siteUrl = 'http://localhost/zaid';

// Helper: generate 7-char hex ID
function eid() { return substr(bin2hex(random_bytes(4)), 0, 7); }

// Helper: Container
function container($settings = [], $elements = []) {
    return [
        'id' => eid(),
        'elType' => 'container',
        'settings' => array_merge(['content_width' => 'full-width'], $settings),
        'elements' => $elements,
    ];
}

// Helper: Heading widget
function heading($text, $tag = 'h2', $settings = []) {
    return [
        'id' => eid(),
        'elType' => 'widget',
        'widgetType' => 'heading',
        'settings' => array_merge([
            'title' => $text,
            'header_size' => $tag,
        ], $settings),
        'elements' => [],
    ];
}

// Helper: Text Editor widget
function textEditor($html, $settings = []) {
    return [
        'id' => eid(),
        'elType' => 'widget',
        'widgetType' => 'text-editor',
        'settings' => array_merge(['editor' => $html], $settings),
        'elements' => [],
    ];
}

// Helper: Image widget
function image($url, $settings = []) {
    return [
        'id' => eid(),
        'elType' => 'widget',
        'widgetType' => 'image',
        'settings' => array_merge([
            'image' => ['url' => $url, 'id' => ''],
            'image_size' => 'full',
        ], $settings),
        'elements' => [],
    ];
}

// Helper: Button widget
function button($text, $url = '#', $settings = []) {
    return [
        'id' => eid(),
        'elType' => 'widget',
        'widgetType' => 'button',
        'settings' => array_merge([
            'text' => $text,
            'link' => ['url' => $url, 'is_external' => false, 'nofollow' => false],
        ], $settings),
        'elements' => [],
    ];
}

// Helper: HTML widget (for decorative/structural elements only)
function html($code) {
    return [
        'id' => eid(),
        'elType' => 'widget',
        'widgetType' => 'html',
        'settings' => ['html' => $code],
        'elements' => [],
    ];
}

// Helper: Spacer widget
function spacer($size = 20) {
    return [
        'id' => eid(),
        'elType' => 'widget',
        'widgetType' => 'spacer',
        'settings' => ['space' => ['size' => $size, 'unit' => 'px']],
        'elements' => [],
    ];
}

// Helper: Divider widget
function divider($settings = []) {
    return [
        'id' => eid(),
        'elType' => 'widget',
        'widgetType' => 'divider',
        'settings' => $settings,
        'elements' => [],
    ];
}

// Helper: Icon widget
function icon($iconClass = 'fas fa-star', $settings = []) {
    return [
        'id' => eid(),
        'elType' => 'widget',
        'widgetType' => 'icon',
        'settings' => array_merge([
            'selected_icon' => ['value' => $iconClass, 'library' => 'fa-solid'],
        ], $settings),
        'elements' => [],
    ];
}

// Shortcut: Barlow Condensed headline with standard styling
function bcHeadline($text, $tag = 'h2', $extra = []) {
    return heading($text, $tag, array_merge([
        'title_color' => '#F8F8F6',
        'typography_typography' => 'custom',
        'typography_font_family' => 'Barlow Condensed',
        'typography_font_weight' => '900',
        'typography_line_height' => ['size' => 0.9, 'unit' => 'em'],
        'typography_text_transform' => 'uppercase',
        'typography_font_size' => ['size' => 72, 'unit' => 'px'],
        'typography_font_size_tablet' => ['size' => 52, 'unit' => 'px'],
        'typography_font_size_mobile' => ['size' => 42, 'unit' => 'px'],
    ], $extra));
}

// Shortcut: Eyebrow label
function eyebrow($text) {
    return textEditor('<p class="eyebrow">' . $text . '</p>');
}

// Shortcut: body paragraph
function bodyPara($text, $extra = []) {
    return textEditor('<p>' . $text . '</p>', array_merge([
        'text_color' => 'rgba(255,255,255,0.4)',
        'typography_typography' => 'custom',
        'typography_font_family' => 'Barlow',
        'typography_font_size' => ['size' => 15, 'unit' => 'px'],
        'typography_line_height' => ['size' => 1.8, 'unit' => 'em'],
        'typography_font_weight' => '300',
    ], $extra));
}

// Image URLs
$img = [
    'hero'   => 'http://coopbballtraining.com/wp-content/uploads/2026/02/IMG_7194_1.jpg',
    'coach'  => 'http://coopbballtraining.com/wp-content/uploads/2026/03/IMG_7352.jpg',
    'video'  => 'http://coopbballtraining.com/wp-content/uploads/2026/03/IMG_7578.jpg',
    'vidSrc' => 'http://coopbballtraining.com/wp-content/uploads/2026/02/video-output-C3E31B30-E0A4-40FA-B7B1-CB853BD9B261-1.mov',
    'how1'   => 'http://coopbballtraining.com/wp-content/uploads/2026/03/IMG_7582.jpg',
    'how2'   => 'http://coopbballtraining.com/wp-content/uploads/2026/03/IMG_7124.jpg',
    'how3'   => 'http://coopbballtraining.com/wp-content/uploads/2026/03/IMG_7577.jpg',
    'pb1'    => 'http://coopbballtraining.com/wp-content/uploads/2026/03/IMG_7575.jpg',
    'pb2'    => 'http://coopbballtraining.com/wp-content/uploads/2026/03/IMG_7195.jpg',
    'pb3'    => 'http://coopbballtraining.com/wp-content/uploads/2026/03/IMG_1176.jpg',
    'cta'    => 'http://coopbballtraining.com/wp-content/uploads/2026/03/IMG_7124.jpg',
    'bio'    => 'http://coopbballtraining.com/wp-content/uploads/2026/02/IMG_7482.jpg',
    'logo'   => 'http://coopbballtraining.com/wp-content/uploads/2026/02/image_-_2026-02-27T002144.133-removebg-preview.png',
];

echo "Building Elementor sections with NATIVE widgets...\n";

// ================================================================
// SECTION 0: Global CSS
// ================================================================
$globalCss = html(<<<'CSS'
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@0,300;0,400;0,600;0,700;0,900;1,700;1,900&family=Barlow:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root{--black:#080808;--dark:#0E0E0E;--dark2:#161616;--white:#F8F8F6;--gray:#A0A0A0;--gray2:#666;--border:rgba(255,255,255,0.08);--orange:#FF4500;--orange2:#FF6A1A;--gold:#FFB800;}
body.elementor-template-canvas{background:var(--black);color:var(--white);font-family:'Barlow',sans-serif;overflow-x:hidden;margin:0;padding:0;}
.elementor-element{font-family:'Barlow',sans-serif;}
.elementor.elementor-2{font-family:'Barlow',sans-serif;}

/* Remove Elementor default gaps */
.elementor-widget{margin-bottom:0 !important;}
.e-con{--gap:0px;}
.e-con > .elementor-widget{width:100%;}

/* Cursor */
#cr{width:12px;height:12px;background:var(--orange);border-radius:50%;position:fixed;top:0;left:0;z-index:99999;pointer-events:none;mix-blend-mode:screen;transition:transform .15s;}
#cr2{width:40px;height:40px;border:1px solid rgba(255,69,0,.5);border-radius:50%;position:fixed;top:0;left:0;z-index:99998;pointer-events:none;}

/* Animations */
@keyframes fadeUp{from{opacity:0;transform:translateY(28px)}to{opacity:1;transform:translateY(0)}}
@keyframes fadeIn{from{opacity:0}to{opacity:1}}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.2}}
@keyframes roll{from{transform:translateX(0)}to{transform:translateX(-50%)}}
@keyframes scrollPulse{0%,100%{opacity:.5;height:60px}50%{opacity:1;height:80px}}
.sr{opacity:0;transform:translateY(36px);transition:opacity .85s ease,transform .85s ease;}
.sr.d1{transition-delay:.1s}.sr.d2{transition-delay:.2s}.sr.d3{transition-delay:.3s}.sr.d4{transition-delay:.45s}
.sr.in{opacity:1;transform:none;}

/* Eyebrow */
.eyebrow{display:inline-flex;align-items:center;gap:12px;font-size:11px;font-weight:600;letter-spacing:4px;text-transform:uppercase;color:var(--orange);margin-bottom:20px;}
.eyebrow::before{content:'';width:28px;height:1px;background:var(--orange);}

/* Signup Bar */
.signup-bar{background:var(--orange);padding:12px 64px;display:flex;align-items:center;justify-content:space-between;gap:16px;position:fixed;top:0;left:0;right:0;z-index:1002;}
.sb-text{display:flex;align-items:center;gap:12px;}
.sb-dot{width:7px;height:7px;background:rgba(0,0,0,.35);border-radius:50%;animation:blink 1.5s infinite;flex-shrink:0;}
.sb-text span{font-size:13px;font-weight:500;color:rgba(0,0,0,.8);}
.sb-text strong{color:var(--black);font-weight:700;}
.sb-btn{display:inline-flex;align-items:center;gap:8px;padding:8px 24px;background:var(--black);color:var(--white);font-family:'Barlow Condensed',sans-serif;font-size:12px;font-weight:700;letter-spacing:2px;text-transform:uppercase;border-radius:2px;text-decoration:none;white-space:nowrap;transition:all .3s;}
.sb-btn:hover{background:var(--dark2);transform:translateY(-1px);}

/* Nav */
nav.bc-nav{position:fixed;top:56px;left:0;right:0;z-index:1000;padding:0 64px;height:110px;display:flex;align-items:center;justify-content:space-between;transition:background .4s,height .3s,top .3s;}
nav.bc-nav.bg{background:rgba(8,8,8,.95);backdrop-filter:blur(20px);height:68px;border-bottom:1px solid var(--border);}
.nav-logo{display:flex;align-items:center;gap:12px;text-decoration:none;}
.nav-logo img{height:60px;filter:drop-shadow(0 0 16px rgba(255,69,0,.4));transition:height .3s;}
nav.bc-nav.bg .nav-logo img{height:48px;}
.nav-wordmark{display:flex;flex-direction:column;line-height:1.15;}
.nav-wm1{font-family:'Barlow Condensed',sans-serif;font-size:20px;font-weight:900;letter-spacing:1px;color:var(--white);}
.nav-wm2{font-size:10px;font-weight:600;letter-spacing:4px;text-transform:uppercase;color:var(--orange);}
.nav-links{display:flex;gap:0;list-style:none;position:absolute;left:50%;transform:translateX(-50%);padding:0;margin:0;}
.nav-links a{display:block;padding:8px 20px;font-size:12px;font-weight:600;letter-spacing:2px;text-transform:uppercase;color:rgba(255,255,255,.5);text-decoration:none;transition:color .3s;position:relative;}
.nav-links a::after{content:'';position:absolute;bottom:4px;left:20px;right:20px;height:1px;background:var(--orange);transform:scaleX(0);transform-origin:center;transition:transform .3s;}
.nav-links a:hover{color:var(--white);}
.nav-links a:hover::after{transform:scaleX(1);}
.nav-right{display:flex;align-items:center;gap:12px;}
.nav-cta{padding:10px 28px;background:var(--orange);color:var(--white);font-size:12px;font-weight:700;letter-spacing:2px;text-transform:uppercase;border-radius:2px;text-decoration:none;transition:all .3s;position:relative;overflow:hidden;font-family:'Barlow Condensed',sans-serif;}
.nav-cta::before{content:'';position:absolute;inset:0;background:rgba(255,255,255,.15);transform:scaleX(0);transform-origin:left;transition:transform .4s;}
.nav-cta:hover::before{transform:scaleX(1);}
.nav-cta:hover{box-shadow:0 8px 24px rgba(255,69,0,.45);transform:translateY(-1px);}
.btn-ghost{padding:15px 36px;border:1px solid rgba(255,255,255,.2);color:rgba(255,255,255,.7);font-family:'Barlow Condensed',sans-serif;font-size:14px;font-weight:600;letter-spacing:2px;text-transform:uppercase;border-radius:2px;text-decoration:none;transition:all .3s;}
.btn-ghost:hover{border-color:var(--white);color:var(--white);}

/* Hero Stats */
.hero-stats{position:absolute;right:0;top:0;bottom:0;width:120px;display:flex;flex-direction:column;justify-content:center;align-items:center;gap:0;z-index:2;opacity:0;animation:fadeIn 1s ease 1s forwards;}
.hstat{flex:1;width:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;border-bottom:1px solid var(--border);padding:20px 10px;}
.hstat:last-child{border-bottom:none;}
.hstat-n{font-family:'Barlow Condensed',sans-serif;font-size:42px;font-weight:900;color:var(--white);line-height:1;}
.hstat-n em{color:var(--orange);font-style:normal;}
.hstat-l{font-size:9px;letter-spacing:2px;text-transform:uppercase;color:rgba(255,255,255,.3);text-align:center;margin-top:4px;}
.hero-scroll{position:absolute;bottom:48px;right:64px;display:flex;flex-direction:column;align-items:center;gap:8px;opacity:0;animation:fadeIn 1s ease 1.2s forwards;z-index:2;}
.hero-scroll span{font-size:9px;letter-spacing:4px;text-transform:uppercase;color:rgba(255,255,255,.3);transform:rotate(90deg);white-space:nowrap;}
.scroll-line{width:1px;height:60px;background:linear-gradient(to bottom,rgba(255,69,0,.8),transparent);animation:scrollPulse 2s infinite;}

/* Ticker */
.ticker{overflow:hidden;white-space:nowrap;padding:14px 0;}
.ticker-inner{display:inline-flex;animation:roll 28s linear infinite;}
.ticker-inner span{font-family:'Barlow Condensed',sans-serif;font-size:14px;font-weight:700;letter-spacing:4px;text-transform:uppercase;color:var(--black);padding:0 40px;}
.ticker-inner .dot{color:rgba(0,0,0,.3)!important;padding:0 4px!important;}

/* About */
.about-photo{position:relative;overflow:hidden;min-height:50vh;}
.about-photo img{width:100%;height:100%;object-fit:cover;display:block;transition:transform 8s ease;}
.about-photo:hover img{transform:scale(1.04);}
.about-photo-overlay{position:absolute;inset:0;background:linear-gradient(to right,transparent 60%,var(--dark) 100%);}
.about-photo-tag{position:absolute;bottom:28px;left:32px;background:rgba(8,8,8,.85);border-left:3px solid var(--orange);padding:14px 20px;backdrop-filter:blur(12px);}
.apt-name{font-family:'Barlow Condensed',sans-serif;font-size:22px;font-weight:900;letter-spacing:1px;color:var(--white);}
.apt-title{font-size:11px;letter-spacing:2.5px;text-transform:uppercase;color:var(--orange);margin-top:3px;}
.about-video{background:var(--black);position:relative;min-height:300px;}
.about-video video{width:100%;height:100%;min-height:300px;object-fit:cover;display:block;}
.av-overlay{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.35);cursor:pointer;transition:background .3s;}
.av-overlay:hover{background:rgba(0,0,0,.15);}
.av-play{width:68px;height:68px;background:var(--orange);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:24px;box-shadow:0 8px 32px rgba(255,69,0,.55);transition:transform .3s;}
.av-overlay:hover .av-play{transform:scale(1.1);}
.cred-block{background:rgba(255,69,0,.05);border:1px solid rgba(255,69,0,.18);border-radius:3px;padding:24px 22px;margin:28px 0;}
.cred-block h4{font-family:'Barlow Condensed',sans-serif;font-size:13px;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:var(--orange);margin-bottom:14px;}
.cred-list{display:flex;flex-direction:column;gap:9px;}
.cred-item{display:flex;align-items:flex-start;gap:10px;font-size:14px;color:rgba(255,255,255,.6);line-height:1.5;}
.cred-star{color:var(--orange);flex-shrink:0;font-size:12px;margin-top:2px;}
.about-pillars{display:flex;flex-direction:column;gap:0;margin-top:28px;border:1px solid var(--border);}
.pillar{display:flex;align-items:center;gap:20px;padding:22px 24px;border-bottom:1px solid var(--border);transition:all .3s;cursor:pointer;}
.pillar:last-child{border-bottom:none;}
.pillar:hover{background:rgba(255,69,0,.04);padding-left:32px;}
.pillar-ico{width:44px;height:44px;min-width:44px;background:rgba(255,69,0,.08);border:1px solid rgba(255,69,0,.15);border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:20px;}
.pillar h4{font-family:'Barlow Condensed',sans-serif;font-size:17px;font-weight:700;letter-spacing:.5px;color:var(--white);margin-bottom:3px;}
.pillar p{font-size:13px;color:rgba(255,255,255,.4);line-height:1.5;margin:0;}

/* Camp Cards — container level */
.camp-card{position:relative;overflow:hidden;transition:background .3s;cursor:pointer;}
.camp-card::after{content:'';position:absolute;bottom:0;left:0;right:0;height:2px;background:var(--orange);transform:scaleX(0);transform-origin:left;transition:transform .5s;}
.camp-card:hover{background:rgba(255,255,255,.02) !important;}
.camp-card:hover::after{transform:scaleX(1);}
.cc-badge{display:inline-flex;align-items:center;gap:5px;margin-top:14px;padding:5px 12px;border-radius:50px;font-size:10px;font-weight:600;letter-spacing:1.5px;text-transform:uppercase;background:rgba(255,69,0,.07);border:1px solid rgba(255,69,0,.2);color:var(--orange);}

/* Benefit Cards — container level */
.bcard{text-align:center;transition:all .35s;position:relative;overflow:hidden;}
.bcard::before{content:'';position:absolute;top:0;left:0;right:0;height:2px;background:var(--orange);transform:scaleX(0);transform-origin:center;transition:transform .5s;}
.bcard:hover{background:var(--dark2) !important;transform:translateY(-4px);}
.bcard:hover::before{transform:scaleX(1);}

/* How Steps — container level */
.how-step-container{border-bottom:1px solid var(--border);transition:all .3s;}
.how-step-container:first-child{border-top:1px solid var(--border);}
.how-step-container:hover .hs-num-text{color:rgba(255,69,0,.12) !important;}
.hp{overflow:hidden;border-radius:4px;position:relative;}
.hp img{width:100%;height:100%;object-fit:cover;transition:transform .6s ease;display:block;}
.hp:hover img{transform:scale(1.06);}
.hp.tall{grid-row:span 2;}
.hp-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(8,8,8,.6) 0%,transparent 50%);opacity:0;transition:opacity .4s;}
.hp:hover .hp-overlay{opacity:1;}
.hp-label{position:absolute;bottom:12px;left:14px;font-family:'Barlow Condensed',sans-serif;font-size:12px;font-weight:600;letter-spacing:2px;text-transform:uppercase;color:var(--white);opacity:0;transition:opacity .4s;}
.hp:hover .hp-label{opacity:1;}

/* Programs — container level */
.prog-container{border-top:1px solid var(--border);transition:all .35s;position:relative;overflow:hidden;cursor:pointer;}
.prog-container::before{content:'';position:absolute;left:0;top:0;bottom:0;width:3px;background:var(--orange);transform:scaleY(0);transition:transform .4s;}
.prog-container:hover{background:rgba(255,255,255,.03) !important;}
.prog-container:hover::before{transform:scaleY(1);}
.prog-container:hover .pi-num-text{color:rgba(255,69,0,.1) !important;}
.pi-pill{display:inline-flex;align-items:center;gap:6px;padding:7px 18px;border-radius:50px;font-size:11px;font-weight:600;letter-spacing:1.5px;text-transform:uppercase;background:rgba(255,69,0,.07);border:1px solid rgba(255,69,0,.2);color:var(--orange);}

/* Photo Break */
.pb-photo{overflow:hidden;position:relative;}
.pb-photo img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .7s ease;}
.pb-photo:hover img{transform:scale(1.06);}
.pb-overlay{position:absolute;inset:0;background:rgba(8,8,8,.3);transition:background .4s;}
.pb-photo:hover .pb-overlay{background:rgba(8,8,8,.1);}
.pb-text{position:absolute;bottom:24px;left:28px;right:28px;z-index:2;}
.pb-text h3{font-family:'Barlow Condensed',sans-serif;font-size:clamp(28px,3vw,44px);font-weight:900;text-transform:uppercase;letter-spacing:1px;color:var(--white);line-height:1;margin:0;}
.pb-text p{font-size:13px;color:rgba(255,255,255,.6);margin-top:6px;font-weight:300;}

/* Testimonial Cards — container level */
.tcard-container{border:1px solid var(--border);border-radius:4px;transition:all .35s;}
.tcard-container:hover{border-color:rgba(255,69,0,.25);transform:translateY(-5px);}
.tcard-featured{border-color:var(--orange) !important;display:flex;flex-direction:column;justify-content:space-between;}
.tcard-featured:hover{transform:translateY(-8px) !important;box-shadow:0 24px 60px rgba(255,69,0,.4);}
.tc-stars{color:var(--gold);letter-spacing:2px;font-size:14px;margin-bottom:16px;}
.tcard-featured .tc-stars{color:rgba(0,0,0,.5);}
.tcard-featured .elementor-heading-title{color:var(--black) !important;}
.tcard-featured .elementor-text-editor,.tcard-featured .elementor-text-editor p{color:rgba(0,0,0,.8) !important;}
.tc-author{display:flex;align-items:center;gap:14px;}
.tc-av{width:48px;height:48px;border-radius:50%;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-family:'Barlow Condensed',sans-serif;font-size:18px;font-weight:900;color:var(--white);flex-shrink:0;}
.tcard-featured .tc-av{background:rgba(0,0,0,.15);color:var(--black);}
.tc-type{display:inline-flex;align-items:center;gap:6px;padding:4px 12px;border-radius:50px;font-size:10px;font-weight:600;letter-spacing:1.5px;text-transform:uppercase;background:rgba(255,69,0,.07);border:1px solid rgba(255,69,0,.2);color:var(--orange);}
.tcard-featured .tc-type{background:rgba(0,0,0,.12);color:rgba(0,0,0,.7);border:none;}

/* Pricing */
.single-plan{border-radius:4px;overflow:hidden;box-shadow:0 24px 64px rgba(255,69,0,.35);}
.sp-top{padding:52px 60px 36px;text-align:center;border-bottom:1px solid rgba(0,0,0,.12);}
.sp-price{display:flex;align-items:flex-start;justify-content:center;gap:4px;margin-bottom:10px;}
.sp-cur{font-size:26px;font-weight:700;color:var(--black);margin-top:14px;}
.sp-num{font-family:'Barlow Condensed',sans-serif;font-size:108px;font-weight:900;line-height:1;color:var(--black);letter-spacing:-4px;}
.sp-mo{font-size:16px;color:rgba(0,0,0,.4);align-self:flex-end;padding-bottom:14px;}
.sp-features{list-style:none;display:grid;grid-template-columns:1fr 1fr;gap:14px 40px;margin:0 0 40px 0;padding:0;}
.sp-features li{display:flex;align-items:center;gap:10px;font-size:15px;color:rgba(0,0,0,.75);}
.sp-chk{width:22px;height:22px;min-width:22px;border-radius:50%;background:rgba(0,0,0,.14);display:flex;align-items:center;justify-content:center;font-size:11px;color:var(--black);}
.btn-sub{display:block;text-align:center;padding:18px;border-radius:2px;background:var(--black);color:var(--white);font-family:'Barlow Condensed',sans-serif;font-size:15px;font-weight:700;letter-spacing:3px;text-transform:uppercase;text-decoration:none;transition:all .3s;}
.btn-sub:hover{background:var(--dark2);transform:translateY(-2px);box-shadow:0 12px 32px rgba(0,0,0,.4);}

/* Bio */
.bio-badge{position:absolute;bottom:-18px;right:-18px;background:var(--orange);padding:20px 22px;border-radius:2px;text-align:center;box-shadow:0 8px 24px rgba(255,69,0,.45);}
.bio-badge-n{font-family:'Barlow Condensed',sans-serif;font-size:38px;font-weight:900;color:var(--black);line-height:1;}
.bio-badge-l{font-size:10px;font-weight:600;letter-spacing:2px;text-transform:uppercase;color:rgba(0,0,0,.55);margin-top:3px;}
.bio-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:2px;background:var(--border);margin-top:40px;}
.bs{background:var(--dark);padding:26px 20px;text-align:center;}
.bs-n{font-family:'Barlow Condensed',sans-serif;font-size:44px;font-weight:900;color:var(--orange);line-height:1;}
.bs-l{font-size:10px;letter-spacing:2px;text-transform:uppercase;color:rgba(255,255,255,.3);margin-top:5px;}
.ba{display:flex;align-items:flex-start;gap:14px;padding:14px 18px;background:var(--dark);border:1px solid var(--border);border-radius:3px;transition:border-color .3s;}
.ba:hover{border-color:rgba(255,69,0,.25);}
.ba-ico{font-size:20px;flex-shrink:0;}
.ba-text{font-size:14px;color:rgba(255,255,255,.6);line-height:1.5;}
.ba-text strong{color:var(--white);font-weight:600;}

/* Footer */
.fb img{height:76px;object-fit:contain;filter:drop-shadow(0 4px 14px rgba(255,69,0,.3));margin-bottom:18px;display:block;}
.fb-name{font-family:'Barlow Condensed',sans-serif;font-size:22px;font-weight:900;letter-spacing:1px;color:var(--white);margin-bottom:3px;}
.fb-sub{font-size:10px;font-weight:600;letter-spacing:3px;text-transform:uppercase;color:var(--orange);margin-bottom:16px;}
.fb-socials{display:flex;gap:8px;margin-top:24px;}
.fb-s{width:38px;height:38px;border:1px solid var(--border);border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:16px;text-decoration:none;color:rgba(255,255,255,.3);transition:all .3s;}
.fb-s:hover{border-color:var(--orange);color:var(--orange);background:rgba(255,69,0,.05);transform:translateY(-3px);}
.fc h4{font-size:10px;font-weight:600;letter-spacing:4px;text-transform:uppercase;color:rgba(255,255,255,.4);margin-bottom:22px;padding-bottom:14px;border-bottom:1px solid var(--border);}
.fc ul{list-style:none;display:flex;flex-direction:column;gap:11px;padding:0;margin:0;}
.fc a{font-size:14px;color:rgba(255,255,255,.35);text-decoration:none;display:flex;align-items:center;gap:8px;transition:color .3s;}
.fc a::before{content:'›';color:rgba(255,69,0,.35);transition:color .3s;}
.fc a:hover{color:rgba(255,255,255,.75);}
.fc a:hover::before{color:var(--orange);}
.fcc{display:flex;align-items:flex-start;gap:10px;}
.fcc-i{width:34px;height:34px;min-width:34px;background:rgba(255,69,0,.06);border:1px solid rgba(255,69,0,.12);border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:14px;}
.fcc-t small{display:block;font-size:9px;color:rgba(255,255,255,.25);letter-spacing:2px;text-transform:uppercase;margin-bottom:2px;}
.fcc-t span{font-size:13px;color:rgba(255,255,255,.45);}

/* Back to top */
#btt{position:fixed;bottom:28px;right:28px;width:46px;height:46px;background:var(--orange);color:var(--white);border-radius:2px;font-size:20px;display:flex;align-items:center;justify-content:center;text-decoration:none;z-index:500;opacity:0;transform:translateY(12px);pointer-events:none;transition:all .4s;box-shadow:0 8px 24px rgba(255,69,0,.4);}
#btt.show{opacity:1;transform:translateY(0);pointer-events:all;}
#btt:hover{background:var(--orange2);transform:translateY(-4px)!important;}

/* Responsive */
@media(max-width:1100px){
  nav.bc-nav{padding:0 28px;top:94px;}
  .nav-links{display:none;}
  .signup-bar{padding:10px 24px;flex-direction:column;text-align:center;gap:10px;}
  .hero-stats{display:none;}
}
@media(max-width:700px){
  nav.bc-nav{padding:0 18px;height:68px;top:104px;}
}
</style>
CSS);

// ================================================================
// SECTION 1: Signup Banner (structural — HTML ok)
// ================================================================
$sec1_signup = html('<div class="signup-bar"><div class="sb-text"><div class="sb-dot"></div><span>🏀 <strong>Now Enrolling — Virtual Training Camps</strong> · Instructional videos, live group sessions, weekly workouts &amp; more for just <strong>$22 to join</strong>. Cancel anytime.</span></div><a href="#pricing" class="sb-btn">⚡ Sign Up Now</a></div>');

// ================================================================
// SECTION 2: Navigation (structural — HTML ok)
// ================================================================
$sec2_nav = html('<nav class="bc-nav" id="mainNav"><a href="#hero" class="nav-logo"><img src="'.$img['logo'].'" alt="Coop B-Ball"><div class="nav-wordmark"><span class="nav-wm1">Coop B-Ball</span><span class="nav-wm2">Virtual Training</span></div></a><ul class="nav-links"><li><a href="#about">About</a></li><li><a href="#benefits">What You Get</a></li><li><a href="#programs">Programs</a></li><li><a href="#testimonials">Results</a></li><li><a href="#pricing">Join — $40/mo</a></li></ul><div class="nav-right"><a href="mailto:james@coopbballtraining.com" class="btn-ghost" style="padding:9px 20px;font-size:12px;letter-spacing:1px;">✉️ Email Us</a><a href="#pricing" class="nav-cta">Join Now</a></div></nav><a href="#hero" id="btt">↑</a><div id="cr"></div><div id="cr2"></div>');

// ================================================================
// SECTION 3: Hero — NATIVE WIDGETS
// ================================================================
$heroContent = container([
    'content_width' => 'full-width',
    'flex_direction' => 'column',
    'flex_justify_content' => 'flex-end',
    'padding' => ['unit'=>'px','top'=>'0','right'=>'64','bottom'=>'100','left'=>'64','isLinked'=>false],
    'min_height' => ['size' => 100, 'unit' => 'vh'],
    'margin' => ['unit'=>'px','top'=>'136','right'=>'0','bottom'=>'0','left'=>'0','isLinked'=>false],
    'background_background' => 'classic',
    'background_image' => ['url'=>$img['hero'],'id'=>''],
    'background_position' => 'center center',
    'background_size' => 'cover',
    '_element_id' => 'hero',
    'custom_css' => "selector{position:relative;overflow:hidden;}
selector::before{content:'';position:absolute;inset:0;background:linear-gradient(to right,rgba(8,8,8,.85) 45%,rgba(8,8,8,.2) 100%),linear-gradient(to top,rgba(8,8,8,.95) 0%,rgba(8,8,8,.3) 50%,transparent 100%);z-index:0;}
selector > .e-con-inner,selector > .elementor-widget{position:relative;z-index:2;}",
], [
    // Eyebrow — decorative
    html('<div style="display:inline-flex;align-items:center;gap:10px;margin-bottom:28px;opacity:0;animation:fadeUp .7s ease .2s forwards;"><div style="width:8px;height:8px;background:var(--orange);border-radius:50%;animation:blink 1.5s infinite;"></div><span style="font-size:11px;font-weight:600;letter-spacing:4px;text-transform:uppercase;color:var(--orange);">Virtual Basketball Training Camps</span></div>'),

    // H1 — NATIVE heading (client can change title)
    heading('Train Elite. Online.', 'h1', [
        'title_color' => '#F8F8F6',
        'typography_typography' => 'custom',
        'typography_font_family' => 'Barlow Condensed',
        'typography_font_size' => ['size' => 140, 'unit' => 'px'],
        'typography_font_size_tablet' => ['size' => 80, 'unit' => 'px'],
        'typography_font_size_mobile' => ['size' => 60, 'unit' => 'px'],
        'typography_font_weight' => '900',
        'typography_line_height' => ['size' => 0.88, 'unit' => 'em'],
        'typography_letter_spacing' => ['size' => -1, 'unit' => 'px'],
        'typography_text_transform' => 'uppercase',
        '_margin' => ['unit'=>'px','top'=>'0','right'=>'0','bottom'=>'32','left'=>'0','isLinked'=>false],
        'custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .35s forwards;max-width:800px;}',
    ]),

    // Subtitle — NATIVE text-editor
    textEditor('Join Coach Coop\'s virtual training camps. Live group sessions, instructional videos, weekly workouts and pro tips — all for $40/month. Train from anywhere.', [
        'text_color' => 'rgba(255,255,255,0.55)',
        'typography_typography' => 'custom',
        'typography_font_family' => 'Barlow',
        'typography_font_size' => ['size' => 17, 'unit' => 'px'],
        'typography_line_height' => ['size' => 1.75, 'unit' => 'em'],
        'typography_font_weight' => '300',
        '_css_classes' => 'sr',
        'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .55s forwards;max-width:480px;}',
    ]),

    // Buttons row — NATIVE buttons
    container([
        'flex_direction' => 'row',
        'flex_align_items' => 'center',
        'gap' => ['size' => 16, 'unit' => 'px'],
        'content_width' => 'full-width',
        'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .7s forwards;}',
    ], [
        button('Join for $40/Month', '#pricing', [
            'button_type' => 'default',
            'background_color' => '#FF4500',
            'button_text_color' => '#F8F8F6',
            'typography_typography' => 'custom',
            'typography_font_family' => 'Barlow Condensed',
            'typography_font_size' => ['size' => 14, 'unit' => 'px'],
            'typography_font_weight' => '700',
            'typography_letter_spacing' => ['size' => 3, 'unit' => 'px'],
            'typography_text_transform' => 'uppercase',
            'border_radius' => ['unit'=>'px','top'=>'2','right'=>'2','bottom'=>'2','left'=>'2','isLinked'=>true],
            'button_padding' => ['unit'=>'px','top'=>'16','right'=>'44','bottom'=>'16','left'=>'44','isLinked'=>false],
            'button_background_hover_color' => '#FF6A1A',
            'custom_css' => 'selector .elementor-button:hover{transform:translateY(-3px);box-shadow:0 20px 50px rgba(255,69,0,.45);}',
        ]),
        button('See What You Get', '#benefits', [
            'button_type' => 'default',
            'background_color' => 'transparent',
            'button_text_color' => 'rgba(255,255,255,0.7)',
            'typography_typography' => 'custom',
            'typography_font_family' => 'Barlow Condensed',
            'typography_font_size' => ['size' => 14, 'unit' => 'px'],
            'typography_font_weight' => '600',
            'typography_letter_spacing' => ['size' => 2, 'unit' => 'px'],
            'typography_text_transform' => 'uppercase',
            'border_border' => 'solid',
            'border_width' => ['unit'=>'px','top'=>'1','right'=>'1','bottom'=>'1','left'=>'1','isLinked'=>true],
            'border_color' => 'rgba(255,255,255,0.2)',
            'border_radius' => ['unit'=>'px','top'=>'2','right'=>'2','bottom'=>'2','left'=>'2','isLinked'=>true],
            'button_padding' => ['unit'=>'px','top'=>'15','right'=>'36','bottom'=>'15','left'=>'36','isLinked'=>false],
            'hover_color' => '#FFFFFF',
            'button_border_hover_color' => '#FFFFFF',
        ]),
    ]),

    // Hero Stats sidebar — decorative HTML
    html('<div class="hero-stats"><div class="hstat"><div class="hstat-n" data-count="200">0<em>+</em></div><div class="hstat-l">Athletes<br>Trained</div></div><div class="hstat"><div class="hstat-n" data-count="98">0<em>%</em></div><div class="hstat-l">Satisfaction<br>Rate</div></div><div class="hstat"><div class="hstat-n" data-count="5">0<em>+</em></div><div class="hstat-l">Years<br>Coaching</div></div></div><div class="hero-scroll"><span>Scroll</span><div class="scroll-line"></div></div>'),
]);

// ================================================================
// SECTION 4: Ticker (animation-based — HTML ok)
// ================================================================
$sec4_ticker = container([
    'content_width' => 'full-width',
    'background_background' => 'classic',
    'background_color' => '#FF4500',
    'padding' => ['unit'=>'px','top'=>'0','right'=>'0','bottom'=>'0','left'=>'0','isLinked'=>true],
], [
    html('<div class="ticker"><div class="ticker-inner"><span>Virtual Training Camps</span><span class="dot">✦</span><span>Group Sessions</span><span class="dot">✦</span><span>Instructional Videos</span><span class="dot">✦</span><span>Ball Handling</span><span class="dot">✦</span><span>Shooting Mechanics</span><span class="dot">✦</span><span>Weekly Workouts</span><span class="dot">✦</span><span>$40 / Month</span><span class="dot">✦</span><span>Train Anywhere</span><span class="dot">✦</span><span>Virtual Training Camps</span><span class="dot">✦</span><span>Group Sessions</span><span class="dot">✦</span><span>Instructional Videos</span><span class="dot">✦</span><span>Ball Handling</span><span class="dot">✦</span><span>Shooting Mechanics</span><span class="dot">✦</span><span>Weekly Workouts</span><span class="dot">✦</span><span>$40 / Month</span><span class="dot">✦</span><span>Train Anywhere</span><span class="dot">✦</span></div></div>'),
]);

// ================================================================
// SECTION 5: About — NATIVE WIDGETS for editable content
// ================================================================
$sec5_about = container([
    'content_width' => 'full-width',
    'flex_direction' => 'row',
    'padding' => ['unit'=>'px','top'=>'0','right'=>'0','bottom'=>'0','left'=>'0','isLinked'=>true],
    '_element_id' => 'about',
], [
    // Left column — photo + video (structural HTML)
    container([
        'content_width' => 'full-width',
        'flex_direction' => 'column',
        'width' => ['size' => 50, 'unit' => '%'],
        'padding' => ['unit'=>'px','top'=>'0','right'=>'0','bottom'=>'0','left'=>'0','isLinked'=>true],
    ], [
        html('<div class="about-photo sr"><img src="'.$img['coach'].'" alt="Coach Coop"><div class="about-photo-overlay"></div><div class="about-photo-tag"><div class="apt-name">Coach Coop</div><div class="apt-title">Pro · Dortmund, Germany · Division III All-American</div></div></div>'),
        html('<div class="about-video"><video id="coopVid" playsinline muted preload="metadata" poster="'.$img['video'].'"><source src="'.$img['vidSrc'].'" type="video/mp4"><source src="'.$img['vidSrc'].'" type="video/quicktime"></video><div class="av-overlay" id="vidOverlay"><div class="av-play">▶</div></div></div>'),
    ]),

    // Right column — NATIVE editable content
    container([
        'content_width' => 'full-width',
        'flex_direction' => 'column',
        'flex_justify_content' => 'center',
        'width' => ['size' => 50, 'unit' => '%'],
        'padding' => ['unit'=>'px','top'=>'80','right'=>'72','bottom'=>'80','left'=>'60','isLinked'=>false],
        'background_background' => 'classic',
        'background_color' => '#0E0E0E',
    ], [
        eyebrow('About Coop B-Ball'),

        bcHeadline('Coached By A Pro.', 'h2', [
            '_margin' => ['unit'=>'px','top'=>'0','right'=>'0','bottom'=>'8','left'=>'0','isLinked'=>false],
        ]),

        textEditor('Virtual camps. Real results.', [
            'text_color' => 'rgba(255,255,255,0.3)',
            'typography_typography' => 'custom',
            'typography_font_family' => 'Barlow',
            'typography_font_size' => ['size' => 20, 'unit' => 'px'],
            'typography_line_height' => ['size' => 1.5, 'unit' => 'em'],
            '_margin' => ['unit'=>'px','top'=>'0','right'=>'0','bottom'=>'28','left'=>'0','isLinked'=>false],
        ]),

        textEditor('Coop B-Ball Training is a <strong style="color:#F8F8F6;font-weight:500;">100% virtual basketball training platform</strong> built for serious athletes at every level. Through live group training camps, instructional videos, and personalized workouts, you get elite-level development for just $40/month.', [
            'text_color' => 'rgba(255,255,255,0.55)',
            'typography_typography' => 'custom',
            'typography_font_family' => 'Barlow',
            'typography_font_size' => ['size' => 16, 'unit' => 'px'],
            'typography_line_height' => ['size' => 1.85, 'unit' => 'em'],
            'typography_font_weight' => '300',
        ]),

        // Credentials block — complex layout, HTML ok
        html('<div class="cred-block sr d2"><h4>🏆 Coach Coop\'s Background</h4><div class="cred-list"><div class="cred-item"><span class="cred-star">★</span><span>Played <strong>professionally in Dortmund, Germany</strong> — competed at the highest level of European professional basketball</span></div><div class="cred-item"><span class="cred-star">★</span><span><strong>Division III All-American</strong> — recognized as one of the top college basketball players in the nation</span></div><div class="cred-item"><span class="cred-star">★</span><span>Multiple <strong>All-Conference awards</strong> throughout his decorated college career</span></div><div class="cred-item"><span class="cred-star">★</span><span>Trained <strong>200+ athletes online</strong> since 2019 through virtual camps and programs</span></div></div></div>'),

        // Pillars — complex layout, HTML ok
        html('<div class="about-pillars sr d3"><div class="pillar"><div class="pillar-ico">🎥</div><div><h4>Virtual Group Training Camps</h4><p>Live group sessions with Coach Coop — train together, get coached together, grow together.</p></div></div><div class="pillar"><div class="pillar-ico">📱</div><div><h4>Train From Anywhere</h4><p>Your driveway, local court, gym — all you need is a phone and a basketball.</p></div></div><div class="pillar"><div class="pillar-ico">🏆</div><div><h4>Pro-Level Knowledge</h4><p>Learn from a player who competed professionally in Germany and was an NAIA All-American.</p></div></div></div>'),
    ]),
]);

echo "Sections 0-5 built...\n";

// ================================================================
// SECTION 6: Camps — NATIVE WIDGETS per card
// ================================================================
$campCards = [
    ['🎥', 'Live Group Sessions', 'Train live with a group of athletes in a virtual camp setting. Coach Coop runs every session, keeps the energy high, and pushes everyone to their limit.', '📅 Scheduled Weekly', 'sr'],
    ['📚', 'Instructional Video Library', 'Full access to a growing library of coaching breakdown videos — ball handling, shooting form, footwork, defense, and more. Watch anytime, anywhere.', '🎬 On-Demand Access', 'sr d1'],
    ['💪', 'Workouts & Coaching Tips', 'Receive weekly workout programs and pro coaching tips from Coach Coop between sessions. Every drill is designed to create real, measurable improvement on the court.', '📊 Updated Weekly', 'sr d2'],
];
$campElements = [];
foreach ($campCards as $c) {
    $campElements[] = container([
        'content_width' => 'full-width',
        'flex_direction' => 'column',
        'width' => ['size' => 33.33, 'unit' => '%'],
        'padding' => ['unit'=>'px','top'=>'40','right'=>'32','bottom'=>'40','left'=>'32','isLinked'=>false],
        'background_background' => 'classic',
        'background_color' => '#0E0E0E',
        'css_classes' => 'camp-card ' . $c[4],
    ], [
        textEditor('<span style="font-size:36px;display:block;margin-bottom:18px;">'.$c[0].'</span>'),
        heading($c[1], 'h3', [
            'title_color' => '#F8F8F6',
            'typography_typography' => 'custom',
            'typography_font_family' => 'Barlow Condensed',
            'typography_font_size' => ['size' => 24, 'unit' => 'px'],
            'typography_font_weight' => '700',
            'typography_letter_spacing' => ['size' => 0.5, 'unit' => 'px'],
            '_margin' => ['unit'=>'px','top'=>'0','right'=>'0','bottom'=>'12','left'=>'0','isLinked'=>false],
        ]),
        textEditor($c[2], [
            'text_color' => 'rgba(255,255,255,0.4)',
            'typography_typography' => 'custom',
            'typography_font_family' => 'Barlow',
            'typography_font_size' => ['size' => 14, 'unit' => 'px'],
            'typography_line_height' => ['size' => 1.7, 'unit' => 'em'],
        ]),
        html('<div class="cc-badge">'.$c[3].'</div>'),
    ]);
}

$sec6_camps = container([
    'content_width' => 'full-width',
    'flex_direction' => 'column',
    'padding' => ['unit'=>'px','top'=>'100','right'=>'64','bottom'=>'100','left'=>'64','isLinked'=>false],
    'background_background' => 'classic',
    'background_color' => '#161616',
    'border_border' => 'solid',
    'border_width' => ['unit'=>'px','top'=>'1','right'=>'0','bottom'=>'0','left'=>'0','isLinked'=>false],
    'border_color' => 'rgba(255,255,255,0.08)',
    '_element_id' => 'camps',
], [
    eyebrow('Virtual Training Camps'),
    bcHeadline('Join The Camp.', 'h2'),
    bodyPara('Our virtual training camps are online group sessions run live by Coach Coop. All skill levels welcome — all you need is a subscription.', [
        '_margin' => ['unit'=>'px','top'=>'0','right'=>'0','bottom'=>'0','left'=>'0','isLinked'=>true],
        'custom_css' => 'selector{max-width:560px;}',
    ]),
    container([
        'flex_direction' => 'row',
        'content_width' => 'full-width',
        'gap' => ['size' => 2, 'unit' => 'px'],
        '_margin' => ['unit'=>'px','top'=>'56','right'=>'0','bottom'=>'0','left'=>'0','isLinked'=>false],
    ], $campElements),
]);

// ================================================================
// SECTION 7: Benefits — NATIVE WIDGETS per card
// ================================================================
$benefitCards = [
    ['🎥', 'Instructional Videos', 'Full library of coaching breakdowns covering every skill — ball handling, shooting, footwork, defense, and basketball IQ. New videos added weekly.', 'sr'],
    ['💪', 'Weekly Workouts', 'Structured drill programs built by Coach Coop for virtual training. Know exactly what to work on every day to develop your game consistently.', 'sr d1'],
    ['🏕️', 'Virtual Training Camps', 'Live group sessions with Coach Coop online. Train alongside other athletes in a high-energy virtual camp environment — scheduled every week.', 'sr d2'],
    ['💡', 'Pro Tips & Advice', 'Regular coaching tips from a pro who played in Germany and was an NAIA All-American — mental game, skill development, reads, positioning and more.', 'sr d3'],
];
$benefitElements = [];
foreach ($benefitCards as $b) {
    $benefitElements[] = container([
        'content_width' => 'full-width',
        'flex_direction' => 'column',
        'flex_align_items' => 'center',
        'width' => ['size' => 25, 'unit' => '%'],
        'padding' => ['unit'=>'px','top'=>'38','right'=>'28','bottom'=>'38','left'=>'28','isLinked'=>false],
        'background_background' => 'classic',
        'background_color' => '#0E0E0E',
        'css_classes' => 'bcard ' . $b[3],
    ], [
        textEditor('<span style="font-size:38px;display:block;margin-bottom:16px;">'.$b[0].'</span>'),
        heading($b[1], 'h3', [
            'title_color' => '#F8F8F6',
            'align' => 'center',
            'typography_typography' => 'custom',
            'typography_font_family' => 'Barlow Condensed',
            'typography_font_size' => ['size' => 20, 'unit' => 'px'],
            'typography_font_weight' => '700',
            'typography_letter_spacing' => ['size' => 0.5, 'unit' => 'px'],
            '_margin' => ['unit'=>'px','top'=>'0','right'=>'0','bottom'=>'10','left'=>'0','isLinked'=>false],
        ]),
        textEditor($b[2], [
            'text_color' => 'rgba(255,255,255,0.4)',
            'align' => 'center',
            'typography_typography' => 'custom',
            'typography_font_family' => 'Barlow',
            'typography_font_size' => ['size' => 13.5, 'unit' => 'px'],
            'typography_line_height' => ['size' => 1.7, 'unit' => 'em'],
        ]),
    ]);
}

$sec7_benefits = container([
    'content_width' => 'full-width',
    'flex_direction' => 'column',
    'padding' => ['unit'=>'px','top'=>'100','right'=>'64','bottom'=>'100','left'=>'64','isLinked'=>false],
    'background_background' => 'classic',
    'background_color' => '#080808',
    '_element_id' => 'benefits',
], [
    eyebrow('What You Get'),
    bcHeadline('One Plan. Everything Included.', 'h2'),
    bodyPara('Subscribers get access to the full training platform — instructional videos, workouts, live camps, tips and more. All for $40/month.', [
        '_margin' => ['unit'=>'px','top'=>'16','right'=>'0','bottom'=>'0','left'=>'0','isLinked'=>false],
        'custom_css' => 'selector{max-width:480px;}',
    ]),
    container([
        'flex_direction' => 'row',
        'content_width' => 'full-width',
        'gap' => ['size' => 2, 'unit' => 'px'],
        '_margin' => ['unit'=>'px','top'=>'56','right'=>'0','bottom'=>'0','left'=>'0','isLinked'=>false],
    ], $benefitElements),
]);

// ================================================================
// SECTION 8: How It Works — NATIVE WIDGETS per step
// ================================================================
$steps = [
    ['01', 'Subscribe for $40/Month', 'Sign up online in seconds. Get instant access to the full platform — instructional videos, weekly workouts, live camp schedule, and coaching tips.', '⚡ Instant Access', 'sr'],
    ['02', 'Join Virtual Training Camps', 'Attend scheduled live group sessions with Coach Coop online. Train with other motivated athletes, get coaching in real time, and push each other every session.', '🎥 Live Group Sessions', 'sr d1'],
    ['03', 'Watch. Work. Improve.', 'Access instructional videos anytime between sessions. Follow the weekly workout plan and apply Coach Coop\'s pro tips directly to your game — on any court, anywhere.', '📱 On Any Device', 'sr d2'],
    ['04', 'Level Up Continuously', 'New content, new camps, and new challenges every week. Stay subscribed, put in the work, and let a professional European player coach you to the next level.', '📈 Constant Growth', 'sr d3'],
];
$stepElements = [];
foreach ($steps as $s) {
    $stepElements[] = container([
        'content_width' => 'full-width',
        'flex_direction' => 'row',
        'gap' => ['size' => 0, 'unit' => 'px'],
        'padding' => ['unit'=>'px','top'=>'32','right'=>'0','bottom'=>'32','left'=>'0','isLinked'=>false],
        'css_classes' => 'how-step-container ' . $s[4],
    ], [
        // Step number
        heading($s[0], 'span', [
            'title_color' => 'rgba(255,255,255,0.04)',
            '_css_classes' => 'hs-num-text',
            'typography_typography' => 'custom',
            'typography_font_family' => 'Barlow Condensed',
            'typography_font_size' => ['size' => 64, 'unit' => 'px'],
            'typography_font_weight' => '900',
            'typography_line_height' => ['size' => 1, 'unit' => 'em'],
            '_element_width' => 'initial',
            '_element_custom_width' => ['size' => 72, 'unit' => 'px'],
        ]),
        // Step body
        container([
            'content_width' => 'full-width',
            'flex_direction' => 'column',
        ], [
            heading($s[1], 'h4', [
                'title_color' => '#F8F8F6',
                'typography_typography' => 'custom',
                'typography_font_family' => 'Barlow Condensed',
                'typography_font_size' => ['size' => 22, 'unit' => 'px'],
                'typography_font_weight' => '700',
                'typography_letter_spacing' => ['size' => 0.5, 'unit' => 'px'],
                '_margin' => ['unit'=>'px','top'=>'0','right'=>'0','bottom'=>'10','left'=>'0','isLinked'=>false],
            ]),
            textEditor($s[2], [
                'text_color' => 'rgba(255,255,255,0.45)',
                'typography_typography' => 'custom',
                'typography_font_family' => 'Barlow',
                'typography_font_size' => ['size' => 14, 'unit' => 'px'],
                'typography_line_height' => ['size' => 1.75, 'unit' => 'em'],
            ]),
            html('<div class="hs-tag">'.$s[3].'</div>'),
        ]),
    ]);
}

$sec8_how = container([
    'content_width' => 'full-width',
    'flex_direction' => 'column',
    'padding' => ['unit'=>'px','top'=>'120','right'=>'64','bottom'=>'120','left'=>'64','isLinked'=>false],
    'background_background' => 'classic',
    'background_color' => '#0E0E0E',
    '_element_id' => 'how',
], [
    eyebrow('How It Works'),
    bcHeadline('Simple. Effective. Online.', 'h2'),
    container([
        'flex_direction' => 'row',
        'content_width' => 'full-width',
        'gap' => ['size' => 80, 'unit' => 'px'],
        'flex_align_items' => 'center',
        '_margin' => ['unit'=>'px','top'=>'70','right'=>'0','bottom'=>'0','left'=>'0','isLinked'=>false],
    ], [
        // Steps column
        container([
            'content_width' => 'full-width',
            'flex_direction' => 'column',
            'width' => ['size' => 50, 'unit' => '%'],
        ], $stepElements),
        // Photo grid — structural HTML (images with overlays)
        html('<div style="flex:1;display:grid;grid-template-columns:1fr 1fr;grid-template-rows:1fr 1fr;gap:8px;height:520px;" class="sr d2"><div class="hp tall"><img src="'.$img['how1'].'" alt="Training session"><div class="hp-overlay"></div><div class="hp-label">Group Camp</div></div><div class="hp"><img src="'.$img['how2'].'" alt="Drill training"><div class="hp-overlay"></div><div class="hp-label">Drill Work</div></div><div class="hp"><img src="'.$img['how3'].'" alt="Skills training"><div class="hp-overlay"></div><div class="hp-label">Skills</div></div></div>'),
    ]),
]);

// ================================================================
// SECTION 9: Programs — NATIVE WIDGETS per program
// ================================================================
$programs = [
    ['01', '🏀', 'Ball Handling', 'Crossovers, hesitations, behind-the-back, dribble combos — build a handle that breaks defenders at every level of play.', '🎥 Video + Camp', ''],
    ['02', '🎯', 'Shooting Mechanics', 'Break down your form, footwork, and release from the ground up. Build a consistent, repeatable jumper you can trust under pressure.', '🎥 Video + Camp', 'd1'],
    ['03', '⚡', 'Footwork & Speed', 'Get to your spots faster. Improve lateral quickness, first-step explosiveness, and movement efficiency on both ends of the floor.', '💪 Workout', 'd2'],
    ['04', '🧠', 'Basketball IQ', 'Learn to read the game — spacing, defensive rotations, pick-and-roll coverage, and how pros make decisions in real time.', '🎥 Instructional', ''],
    ['05', '🛡️', 'Defense & Positioning', 'Become a stopper. On-ball defense, help-side positioning, closeouts — drills designed to make you a two-way threat.', '💪 Workout', 'd1'],
    ['06', '🔥', 'Finishing at the Rim', 'Euro steps, floaters, contact finishes, reverse layups — master the art of getting buckets in traffic at any level of play.', '🎥 Video + Camp', 'd2'],
];
$progElements = [];
foreach ($programs as $p) {
    $progElements[] = container([
        'content_width' => 'full-width',
        'flex_direction' => 'row',
        'flex_align_items' => 'center',
        'padding' => ['unit'=>'px','top'=>'36','right'=>'64','bottom'=>'36','left'=>'64','isLinked'=>false],
        'background_background' => 'classic',
        'background_color' => '#161616',
        'css_classes' => 'prog-container sr ' . $p[5],
    ], [
        // Number
        heading($p[0], 'span', [
            'title_color' => 'rgba(255,255,255,0.04)',
            '_css_classes' => 'pi-num-text',
            'typography_typography' => 'custom',
            'typography_font_family' => 'Barlow Condensed',
            'typography_font_size' => ['size' => 56, 'unit' => 'px'],
            'typography_font_weight' => '900',
            'typography_line_height' => ['size' => 1, 'unit' => 'em'],
            '_element_width' => 'initial',
            '_element_custom_width' => ['size' => 100, 'unit' => 'px'],
        ]),
        // Body
        container([
            'content_width' => 'full-width',
            'flex_direction' => 'column',
        ], [
            textEditor('<span style="font-size:26px;display:block;margin-bottom:10px;">'.$p[1].'</span>'),
            heading($p[2], 'h3', [
                'title_color' => '#F8F8F6',
                'typography_typography' => 'custom',
                'typography_font_family' => 'Barlow Condensed',
                'typography_font_size' => ['size' => 26, 'unit' => 'px'],
                'typography_font_weight' => '700',
                'typography_letter_spacing' => ['size' => 0.5, 'unit' => 'px'],
                '_margin' => ['unit'=>'px','top'=>'0','right'=>'0','bottom'=>'8','left'=>'0','isLinked'=>false],
            ]),
            textEditor($p[3], [
                'text_color' => 'rgba(255,255,255,0.4)',
                'typography_typography' => 'custom',
                'typography_font_family' => 'Barlow',
                'typography_font_size' => ['size' => 14, 'unit' => 'px'],
                'typography_line_height' => ['size' => 1.65, 'unit' => 'em'],
                'custom_css' => 'selector{max-width:500px;}',
            ]),
        ]),
        // Tag
        html('<div style="text-align:right;min-width:200px;"><div class="pi-pill">'.$p[4].'</div></div>'),
    ]);
}

$sec9_programs = container([
    'content_width' => 'full-width',
    'flex_direction' => 'column',
    'padding' => ['unit'=>'px','top'=>'0','right'=>'0','bottom'=>'0','left'=>'0','isLinked'=>true],
    'background_background' => 'classic',
    'background_color' => '#080808',
    '_element_id' => 'programs',
], [
    container([
        'flex_direction' => 'row',
        'flex_justify_content' => 'space-between',
        'flex_align_items' => 'flex-end',
        'content_width' => 'full-width',
        'padding' => ['unit'=>'px','top'=>'100','right'=>'64','bottom'=>'60','left'=>'64','isLinked'=>false],
    ], [
        container(['content_width' => 'full-width', 'flex_direction' => 'column'], [
            eyebrow('Skill Focus Areas'),
            bcHeadline('What We Train.', 'h2'),
        ]),
        bodyPara('Every camp and video covers these skill areas. Subscribers get access to content and live group training across all categories.', [
            'custom_css' => 'selector{max-width:360px;}',
        ]),
    ]),
    container([
        'content_width' => 'full-width',
        'flex_direction' => 'column',
        'gap' => ['size' => 0, 'unit' => 'px'],
    ], $progElements),
]);

// ================================================================
// SECTION 10: Photo Break — images with overlays (structural HTML ok)
// ================================================================
$sec10_photos = container([
    'content_width' => 'full-width',
    'flex_direction' => 'row',
    'padding' => ['unit'=>'px','top'=>'0','right'=>'0','bottom'=>'0','left'=>'0','isLinked'=>true],
    'gap' => ['size' => 4, 'unit' => 'px'],
    'custom_css' => 'selector{display:grid;grid-template-columns:2fr 1fr 1fr;grid-template-rows:400px;}',
    '_element_id' => 'photo-break',
], [
    html('<div class="pb-photo"><img src="'.$img['pb1'].'" alt="Training"><div class="pb-overlay"></div><div class="pb-text"><h3>Train Live.<br>Train Smart.</h3><p>Virtual group sessions with Coach Coop</p></div></div>'),
    html('<div class="pb-photo"><img src="'.$img['pb2'].'" alt="Drills"><div class="pb-overlay"></div><div class="pb-text"><h3>Elite<br>Drills</h3><p>Pro-designed workouts</p></div></div>'),
    html('<div class="pb-photo"><img src="'.$img['pb3'].'" alt="Skills"><div class="pb-overlay"></div><div class="pb-text"><h3>Real<br>Results</h3><p>200+ athletes improved</p></div></div>'),
]);

echo "Sections 6-10 built...\n";

// ================================================================
// SECTION 11: Testimonials — NATIVE WIDGETS
// ================================================================
// Featured testimonial
$featuredTestimonial = container([
    'content_width' => 'full-width',
    'flex_direction' => 'column',
    'flex_justify_content' => 'space-between',
    'padding' => ['unit'=>'px','top'=>'44','right'=>'44','bottom'=>'44','left'=>'44','isLinked'=>false],
    'background_background' => 'classic',
    'background_color' => '#FF4500',
    'border_radius' => ['unit'=>'px','top'=>'4','right'=>'4','bottom'=>'4','left'=>'4','isLinked'=>true],
    'css_classes' => 'tcard-container tcard-featured sr',
], [
    container(['content_width' => 'full-width', 'flex_direction' => 'column'], [
        html('<div class="tc-type">🏕️ Virtual Training Camp</div>'),
        textEditor('★★★★★', ['_css_classes' => 'tc-stars']),
        textEditor('"I joined Coop\'s virtual training camp and it changed how I play. The group sessions are competitive and push you hard — the instructional videos are something I go back to constantly. Coach Coop played professionally in Germany and it shows. His knowledge of the game is on a completely different level."', [
            'text_color' => 'rgba(0,0,0,0.8)',
            'typography_typography' => 'custom',
            'typography_font_family' => 'Barlow',
            'typography_font_size' => ['size' => 18, 'unit' => 'px'],
            'typography_line_height' => ['size' => 1.85, 'unit' => 'em'],
            '_margin' => ['unit'=>'px','top'=>'0','right'=>'0','bottom'=>'28','left'=>'0','isLinked'=>false],
        ]),
    ]),
    html('<div class="tc-author"><div class="tc-av">MJ</div><div class="tc-info"><h5 style="font-family:Barlow Condensed,sans-serif;font-size:17px;font-weight:700;color:var(--black);margin:0;">Marcus J.</h5><span style="font-size:12px;color:rgba(0,0,0,.5);">High School PG · Virtual Camp Member</span></div></div>'),
]);

// Side testimonials
function testimonialCard($type, $quote, $initials, $name, $role, $delay) {
    return container([
        'content_width' => 'full-width',
        'flex_direction' => 'column',
        'padding' => ['unit'=>'px','top'=>'44','right'=>'44','bottom'=>'44','left'=>'44','isLinked'=>false],
        'background_background' => 'classic',
        'background_color' => '#0E0E0E',
        'border_radius' => ['unit'=>'px','top'=>'4','right'=>'4','bottom'=>'4','left'=>'4','isLinked'=>true],
        'css_classes' => 'tcard-container sr ' . $delay,
    ], [
        html('<div class="tc-type">'.$type.'</div>'),
        textEditor('★★★★★', ['_css_classes' => 'tc-stars']),
        textEditor($quote, [
            'text_color' => 'rgba(255,255,255,0.65)',
            'typography_typography' => 'custom',
            'typography_font_family' => 'Barlow',
            'typography_font_size' => ['size' => 15, 'unit' => 'px'],
            'typography_line_height' => ['size' => 1.85, 'unit' => 'em'],
            '_margin' => ['unit'=>'px','top'=>'0','right'=>'0','bottom'=>'28','left'=>'0','isLinked'=>false],
        ]),
        html('<div class="tc-author"><div class="tc-av">'.$initials.'</div><div class="tc-info"><h5 style="font-family:Barlow Condensed,sans-serif;font-size:17px;font-weight:700;color:var(--white);margin:0;">'.$name.'</h5><span style="font-size:12px;color:rgba(255,255,255,.4);">'.$role.'</span></div></div>'),
    ]);
}

$sec11_test = container([
    'content_width' => 'full-width',
    'flex_direction' => 'column',
    'padding' => ['unit'=>'px','top'=>'120','right'=>'64','bottom'=>'120','left'=>'64','isLinked'=>false],
    'background_background' => 'classic',
    'background_color' => '#161616',
    '_element_id' => 'testimonials',
], [
    eyebrow('Real Athletes'),
    bcHeadline('They Joined. They Level\'d Up.', 'h2', [
        '_margin' => ['unit'=>'px','top'=>'0','right'=>'0','bottom'=>'50','left'=>'0','isLinked'=>false],
    ]),
    container([
        'flex_direction' => 'row',
        'content_width' => 'full-width',
        'gap' => ['size' => 24, 'unit' => 'px'],
        'flex_align_items' => 'stretch',
    ], [
        // Featured — 55% width
        container([
            'content_width' => 'full-width',
            'width' => ['size' => 55, 'unit' => '%'],
        ], [$featuredTestimonial]),

        // Side column — 45% width
        container([
            'content_width' => 'full-width',
            'flex_direction' => 'column',
            'gap' => ['size' => 16, 'unit' => 'px'],
            'width' => ['size' => 45, 'unit' => '%'],
        ], [
            testimonialCard('🎥 Instructional Videos',
                'The video library alone is worth $40/month. Coop breaks down every skill in a way that makes sense. My shooting improved noticeably after following his form videos for just a few weeks.',
                'TK', 'Tyler K.', 'College Player', 'd1'),
            testimonialCard('💪 Weekly Workouts',
                'My son follows the weekly workouts from our driveway. The fact that Coach Coop played pro basketball in Germany and is sharing that knowledge for $40/month is unreal value. Highly recommend.',
                'RP', 'Rachel P.', 'Parent · Camp Member', 'd2'),
            testimonialCard('🏕️ Group Sessions',
                'Training in the group camps keeps me locked in. The energy is real and Coop\'s coaching background makes every session feel like prep for the next level. Best $40 I spend every month.',
                'DW', 'DeShawn W.', 'AAU Player', 'd3'),
        ]),
    ]),
]);

// ================================================================
// SECTION 12: Pricing — NATIVE WIDGETS for editable parts
// ================================================================
$sec12_pricing = container([
    'content_width' => 'full-width',
    'flex_direction' => 'column',
    'flex_align_items' => 'center',
    'padding' => ['unit'=>'px','top'=>'120','right'=>'64','bottom'=>'120','left'=>'64','isLinked'=>false],
    'background_background' => 'classic',
    'background_color' => '#0E0E0E',
    '_element_id' => 'pricing',
], [
    eyebrow('Membership'),
    bcHeadline('One Plan. Everything Included.', 'h2', ['align' => 'center']),
    bodyPara('No tiers, no upsells. One subscription gets you the full platform — virtual camps, videos, workouts, and tips.', [
        'align' => 'center',
        '_margin' => ['unit'=>'px','top'=>'18','right'=>'0','bottom'=>'0','left'=>'0','isLinked'=>false],
        'custom_css' => 'selector{max-width:440px;margin-left:auto;margin-right:auto;}',
    ]),

    // Pricing card container
    container([
        'content_width' => 'full-width',
        'flex_direction' => 'column',
        'css_classes' => 'single-plan sr d1',
        'background_background' => 'classic',
        'background_color' => '#FF4500',
        'border_radius' => ['unit'=>'px','top'=>'4','right'=>'4','bottom'=>'4','left'=>'4','isLinked'=>true],
        '_margin' => ['unit'=>'px','top'=>'64','right'=>'0','bottom'=>'0','left'=>'0','isLinked'=>false],
        'custom_css' => 'selector{max-width:700px;margin-left:auto;margin-right:auto;box-shadow:0 24px 64px rgba(255,69,0,.35);}',
    ], [
        // Top section
        container([
            'content_width' => 'full-width',
            'flex_direction' => 'column',
            'flex_align_items' => 'center',
            'padding' => ['unit'=>'px','top'=>'52','right'=>'60','bottom'=>'36','left'=>'60','isLinked'=>false],
            'border_border' => 'solid',
            'border_width' => ['unit'=>'px','top'=>'0','right'=>'0','bottom'=>'1','left'=>'0','isLinked'=>false],
            'border_color' => 'rgba(0,0,0,0.12)',
        ], [
            heading('Join Today', 'h6', [
                'title_color' => 'rgba(0,0,0,0.45)',
                'align' => 'center',
                'typography_typography' => 'custom',
                'typography_font_family' => 'Barlow',
                'typography_font_size' => ['size' => 10, 'unit' => 'px'],
                'typography_font_weight' => '600',
                'typography_letter_spacing' => ['size' => 4, 'unit' => 'px'],
                'typography_text_transform' => 'uppercase',
            ]),
            heading('Coop B-Ball Camp', 'h3', [
                'title_color' => '#080808',
                'align' => 'center',
                'typography_typography' => 'custom',
                'typography_font_family' => 'Barlow Condensed',
                'typography_font_size' => ['size' => 48, 'unit' => 'px'],
                'typography_font_weight' => '900',
                'typography_text_transform' => 'uppercase',
                'typography_letter_spacing' => ['size' => 1, 'unit' => 'px'],
                '_margin' => ['unit'=>'px','top'=>'0','right'=>'0','bottom'=>'26','left'=>'0','isLinked'=>false],
            ]),
            html('<div class="sp-price"><div class="sp-cur">$</div><div class="sp-num">40</div><div class="sp-mo">/ month</div></div>'),
            textEditor('Instant access · Virtual camps · Videos · Workouts', [
                'text_color' => 'rgba(0,0,0,0.5)',
                'align' => 'center',
                'typography_typography' => 'custom',
                'typography_font_family' => 'Barlow',
                'typography_font_size' => ['size' => 13, 'unit' => 'px'],
                'typography_font_style' => 'italic',
            ]),
        ]),

        // Bottom section — features + CTA
        container([
            'content_width' => 'full-width',
            'flex_direction' => 'column',
            'padding' => ['unit'=>'px','top'=>'40','right'=>'60','bottom'=>'52','left'=>'60','isLinked'=>false],
        ], [
            html('<ul class="sp-features"><li><div class="sp-chk">✓</div> Virtual Group Training Camps</li><li><div class="sp-chk">✓</div> Full Instructional Video Library</li><li><div class="sp-chk">✓</div> Weekly Workout Programs</li><li><div class="sp-chk">✓</div> Pro Coaching Tips &amp; Advice</li><li><div class="sp-chk">✓</div> Ball Handling Drills</li><li><div class="sp-chk">✓</div> Shooting Mechanics Content</li><li><div class="sp-chk">✓</div> Footwork &amp; Speed Workouts</li><li><div class="sp-chk">✓</div> Basketball IQ Breakdowns</li><li><div class="sp-chk">✓</div> New Content Added Every Week</li><li><div class="sp-chk">✓</div> Email Access to Coach Coop</li></ul>'),
            button('Join Now — $40/Month', 'mailto:james@coopbballtraining.com', [
                'button_type' => 'default',
                'background_color' => '#080808',
                'button_text_color' => '#F8F8F6',
                'typography_typography' => 'custom',
                'typography_font_family' => 'Barlow Condensed',
                'typography_font_size' => ['size' => 15, 'unit' => 'px'],
                'typography_font_weight' => '700',
                'typography_letter_spacing' => ['size' => 3, 'unit' => 'px'],
                'typography_text_transform' => 'uppercase',
                'border_radius' => ['unit'=>'px','top'=>'2','right'=>'2','bottom'=>'2','left'=>'2','isLinked'=>true],
                'button_padding' => ['unit'=>'px','top'=>'18','right'=>'0','bottom'=>'18','left'=>'0','isLinked'=>false],
                'button_background_hover_color' => '#161616',
                'custom_css' => 'selector .elementor-button{width:100%;} selector .elementor-button:hover{transform:translateY(-2px);box-shadow:0 12px 32px rgba(0,0,0,.4);}',
            ]),
            textEditor('Email james@coopbballtraining.com to get started · Cancel anytime', [
                'text_color' => 'rgba(0,0,0,0.4)',
                'align' => 'center',
                'typography_typography' => 'custom',
                'typography_font_family' => 'Barlow',
                'typography_font_size' => ['size' => 12, 'unit' => 'px'],
                '_margin' => ['unit'=>'px','top'=>'16','right'=>'0','bottom'=>'0','left'=>'0','isLinked'=>false],
            ]),
        ]),
    ]),
]);

// ================================================================
// SECTION 13: CTA — NATIVE WIDGETS
// ================================================================
$sec13_cta = container([
    'content_width' => 'full-width',
    'flex_direction' => 'column',
    'flex_align_items' => 'center',
    'flex_justify_content' => 'center',
    'min_height' => ['size' => 700, 'unit' => 'px'],
    'padding' => ['unit'=>'px','top'=>'100','right'=>'64','bottom'=>'100','left'=>'64','isLinked'=>false],
    'background_background' => 'classic',
    'background_image' => ['url' => $img['cta'], 'id' => ''],
    'background_position' => 'center center',
    'background_size' => 'cover',
    'background_overlay_background' => 'classic',
    'background_overlay_color' => 'rgba(8,8,8,0.82)',
    '_element_id' => 'cta',
], [
    eyebrow('Ready to Level Up?'),

    bcHeadline('Your Court. Our Camp.', 'h2', [
        'align' => 'center',
        'typography_font_size' => ['size' => 120, 'unit' => 'px'],
        'typography_font_size_tablet' => ['size' => 72, 'unit' => 'px'],
        'typography_font_size_mobile' => ['size' => 52, 'unit' => 'px'],
        '_margin' => ['unit'=>'px','top'=>'20','right'=>'0','bottom'=>'24','left'=>'0','isLinked'=>false],
    ]),

    textEditor('Join 200+ athletes training under a professional who played in Dortmund, Germany and was a Division III All-American. $40/month — virtual camps, instructional videos, workouts and more.', [
        'text_color' => 'rgba(255,255,255,0.45)',
        'align' => 'center',
        'typography_typography' => 'custom',
        'typography_font_family' => 'Barlow',
        'typography_font_size' => ['size' => 17, 'unit' => 'px'],
        'typography_line_height' => ['size' => 1.8, 'unit' => 'em'],
        'typography_font_weight' => '300',
        'custom_css' => 'selector{max-width:540px;margin-left:auto;margin-right:auto;}',
        '_margin' => ['unit'=>'px','top'=>'0','right'=>'0','bottom'=>'50','left'=>'0','isLinked'=>false],
    ]),

    container([
        'flex_direction' => 'row',
        'flex_justify_content' => 'center',
        'gap' => ['size' => 14, 'unit' => 'px'],
        'content_width' => 'full-width',
        'css_classes' => 'sr d3',
    ], [
        button('Join for $40/Month', 'mailto:james@coopbballtraining.com', [
            'background_color' => '#FF4500',
            'button_text_color' => '#F8F8F6',
            'typography_typography' => 'custom',
            'typography_font_family' => 'Barlow Condensed',
            'typography_font_size' => ['size' => 14, 'unit' => 'px'],
            'typography_font_weight' => '700',
            'typography_letter_spacing' => ['size' => 3, 'unit' => 'px'],
            'typography_text_transform' => 'uppercase',
            'border_radius' => ['unit'=>'px','top'=>'2','right'=>'2','bottom'=>'2','left'=>'2','isLinked'=>true],
            'button_padding' => ['unit'=>'px','top'=>'16','right'=>'44','bottom'=>'16','left'=>'44','isLinked'=>false],
            'button_background_hover_color' => '#FF6A1A',
        ]),
        button('Email Coach Coop', 'mailto:james@coopbballtraining.com', [
            'background_color' => 'transparent',
            'button_text_color' => 'rgba(255,255,255,0.7)',
            'typography_typography' => 'custom',
            'typography_font_family' => 'Barlow Condensed',
            'typography_font_size' => ['size' => 14, 'unit' => 'px'],
            'typography_font_weight' => '600',
            'typography_letter_spacing' => ['size' => 2, 'unit' => 'px'],
            'typography_text_transform' => 'uppercase',
            'border_border' => 'solid',
            'border_width' => ['unit'=>'px','top'=>'1','right'=>'1','bottom'=>'1','left'=>'1','isLinked'=>true],
            'border_color' => 'rgba(255,255,255,0.2)',
            'border_radius' => ['unit'=>'px','top'=>'2','right'=>'2','bottom'=>'2','left'=>'2','isLinked'=>true],
            'button_padding' => ['unit'=>'px','top'=>'15','right'=>'36','bottom'=>'15','left'=>'36','isLinked'=>false],
            'hover_color' => '#FFFFFF',
            'button_border_hover_color' => '#FFFFFF',
        ]),
    ]),
]);

// ================================================================
// SECTION 14: Bio — NATIVE WIDGETS
// ================================================================
$sec14_bio = container([
    'content_width' => 'full-width',
    'flex_direction' => 'column',
    'padding' => ['unit'=>'px','top'=>'120','right'=>'64','bottom'=>'120','left'=>'64','isLinked'=>false],
    'background_background' => 'classic',
    'background_color' => '#161616',
    'border_border' => 'solid',
    'border_width' => ['unit'=>'px','top'=>'1','right'=>'0','bottom'=>'0','left'=>'0','isLinked'=>false],
    'border_color' => 'rgba(255,255,255,0.08)',
    '_element_id' => 'bio',
], [
    eyebrow('Coach Bio'),
    container([
        'flex_direction' => 'row',
        'content_width' => 'full-width',
        'gap' => ['size' => 80, 'unit' => 'px'],
        'flex_align_items' => 'flex-start',
        '_margin' => ['unit'=>'px','top'=>'64','right'=>'0','bottom'=>'0','left'=>'0','isLinked'=>false],
    ], [
        // Photo column — NATIVE image
        container([
            'content_width' => 'full-width',
            'width' => ['size' => 34, 'unit' => '%'],
            'css_classes' => 'sr',
            'custom_css' => 'selector{position:relative;}',
        ], [
            image($img['bio'], [
                'image_border_radius' => ['unit'=>'px','top'=>'4','right'=>'4','bottom'=>'4','left'=>'4','isLinked'=>true],
                'custom_css' => 'selector img{filter:grayscale(15%);box-shadow:0 20px 60px rgba(0,0,0,.4);}',
            ]),
            html('<div class="bio-badge"><div class="bio-badge-n">PRO</div><div class="bio-badge-l">Germany</div></div>'),
        ]),

        // Text column — NATIVE widgets
        container([
            'content_width' => 'full-width',
            'flex_direction' => 'column',
            'width' => ['size' => 66, 'unit' => '%'],
        ], [
            bcHeadline('Meet Coach Coop.', 'h3', [
                'typography_font_size' => ['size' => 64, 'unit' => 'px'],
                'typography_font_size_tablet' => ['size' => 48, 'unit' => 'px'],
                'typography_font_size_mobile' => ['size' => 36, 'unit' => 'px'],
                '_margin' => ['unit'=>'px','top'=>'0','right'=>'0','bottom'=>'28','left'=>'0','isLinked'=>false],
            ]),

            textEditor('As a player, Coop was known for his efficient shooting, long-range shooting ability, and one-on-one moves. He finished his college career as the school\'s only three-time All-American and was later inducted into the Hall of Fame.', [
                'text_color' => 'rgba(255,255,255,0.55)',
                'typography_typography' => 'custom',
                'typography_font_family' => 'Barlow',
                'typography_font_size' => ['size' => 16, 'unit' => 'px'],
                'typography_line_height' => ['size' => 1.85, 'unit' => 'em'],
                'typography_font_weight' => '300',
            ]),

            textEditor('Coop also scored <strong style="color:#F8F8F6;font-weight:500;">2,037 points</strong> (2nd all-time in school history) while shooting <strong style="color:#F8F8F6;font-weight:500;">54% from the field</strong> and <strong style="color:#F8F8F6;font-weight:500;">46% from the 3-point line</strong>.', [
                'text_color' => 'rgba(255,255,255,0.55)',
                'typography_typography' => 'custom',
                'typography_font_family' => 'Barlow',
                'typography_font_size' => ['size' => 16, 'unit' => 'px'],
                'typography_line_height' => ['size' => 1.85, 'unit' => 'em'],
                'typography_font_weight' => '300',
            ]),

            textEditor('After finishing his career at Wooster, Coop defied the odds as one of very few 6\'0" guards to play professionally in Dortmund, Germany. Today, he is dedicated to helping athletes take their game to the next level.', [
                'text_color' => 'rgba(255,255,255,0.55)',
                'typography_typography' => 'custom',
                'typography_font_family' => 'Barlow',
                'typography_font_size' => ['size' => 16, 'unit' => 'px'],
                'typography_line_height' => ['size' => 1.85, 'unit' => 'em'],
                'typography_font_weight' => '300',
            ]),

            // Stats grid — structural HTML ok
            html('<div class="bio-stats sr d3"><div class="bs"><div class="bs-n">2,037</div><div class="bs-l">Career Points<br>2nd All-Time</div></div><div class="bs"><div class="bs-n">46%</div><div class="bs-l">3-Point<br>Shooting</div></div><div class="bs"><div class="bs-n">HOF</div><div class="bs-l">Hall of Fame<br>Inductee</div></div></div>'),

            // Accolades — structural HTML ok
            html('<div style="display:flex;flex-direction:column;gap:10px;margin-top:28px;" class="sr d3"><div class="ba"><div class="ba-ico">🎯</div><div class="ba-text">Known for <strong>efficient shooting</strong>, <strong>deep range</strong>, and elite <strong>one-on-one moves</strong></div></div><div class="ba"><div class="ba-ico">🏆</div><div class="ba-text">The school\'s <strong>only three-time All-American</strong> and later <strong>Hall of Fame inductee</strong></div></div><div class="ba"><div class="ba-ico">📊</div><div class="ba-text"><strong>2,037 career points</strong> (2nd all-time) with <strong>54% FG</strong> and <strong>46% from three</strong></div></div><div class="ba"><div class="ba-ico">🌍</div><div class="ba-text">Defied the odds as a <strong>6\'0" guard</strong> and played professionally in <strong>Dortmund, Germany</strong></div></div></div>'),
        ]),
    ]),
]);

// ================================================================
// SECTION 15: Footer (structural — HTML ok for complex layout)
// ================================================================
$sec15_footer = container([
    'content_width' => 'full-width',
    'flex_direction' => 'column',
    'padding' => ['unit'=>'px','top'=>'0','right'=>'0','bottom'=>'0','left'=>'0','isLinked'=>true],
    'background_background' => 'classic',
    'background_color' => '#161616',
], [
    container([
        'flex_direction' => 'row',
        'content_width' => 'full-width',
        'gap' => ['size' => 60, 'unit' => 'px'],
        'padding' => ['unit'=>'px','top'=>'80','right'=>'64','bottom'=>'60','left'=>'64','isLinked'=>false],
        'border_border' => 'solid',
        'border_width' => ['unit'=>'px','top'=>'0','right'=>'0','bottom'=>'1','left'=>'0','isLinked'=>false],
        'border_color' => 'rgba(255,255,255,0.08)',
        'custom_css' => 'selector{display:grid;grid-template-columns:1.5fr 1fr 1fr;}',
    ], [
        html('<div class="fb"><img src="'.$img['logo'].'" alt="Coop B-Ball"><div class="fb-name">Coop B-Ball Training</div><div class="fb-sub">Virtual Basketball Camps</div><p style="font-size:13.5px;color:rgba(255,255,255,.3);line-height:1.8;max-width:250px;">Pro-coached virtual training camps, instructional videos and weekly workouts for $40/month. Train anywhere, improve everywhere.</p><div class="fb-socials"><a href="#" class="fb-s">📷</a><a href="#" class="fb-s">🎵</a><a href="#" class="fb-s">🐦</a><a href="#" class="fb-s">▶</a><a href="#" class="fb-s">💬</a></div></div>'),
        html('<div class="fc"><h4>Navigate</h4><ul><li><a href="#about">About Coop</a></li><li><a href="#benefits">What You Get</a></li><li><a href="#programs">Skill Areas</a></li><li><a href="#testimonials">Results</a></li><li><a href="#pricing">Join — $40/mo</a></li><li><a href="#bio">Coach Bio</a></li></ul></div>'),
        html('<div class="fc"><h4>Contact</h4><div style="display:flex;flex-direction:column;gap:14px;"><div class="fcc"><div class="fcc-i">📧</div><div class="fcc-t"><small>Email</small><span>james@coopbballtraining.com</span></div></div><div class="fcc"><div class="fcc-i">🌍</div><div class="fcc-t"><small>Platform</small><span>100% Online · Worldwide</span></div></div><div class="fcc"><div class="fcc-i">🏕️</div><div class="fcc-t"><small>Format</small><span>Virtual Group Training Camps</span></div></div><div class="fcc"><div class="fcc-i">💰</div><div class="fcc-t"><small>Price</small><span>$40 / month · Cancel Anytime</span></div></div></div></div>'),
    ]),
    container([
        'flex_direction' => 'row',
        'flex_justify_content' => 'space-between',
        'flex_align_items' => 'center',
        'content_width' => 'full-width',
        'padding' => ['unit'=>'px','top'=>'18','right'=>'64','bottom'=>'18','left'=>'64','isLinked'=>false],
    ], [
        textEditor('<p style="font-size:12px;color:rgba(255,255,255,.2);">© 2026 <span style="color:var(--orange);">Coop B-Ball Training</span>. All rights reserved.</p>'),
        textEditor('<a href="#" style="font-size:11.5px;color:rgba(255,255,255,.2);text-decoration:none;">Privacy Policy</a> &nbsp;&nbsp; <a href="#" style="font-size:11.5px;color:rgba(255,255,255,.2);text-decoration:none;">Terms of Service</a>'),
    ]),
]);

// ================================================================
// SECTION 16: Global JS
// ================================================================
$globalJs = html('<script>
const cr=document.getElementById("cr"),cr2=document.getElementById("cr2");
let mx=0,my=0,tx=0,ty=0;
document.addEventListener("mousemove",e=>{mx=e.clientX;my=e.clientY;cr.style.left=(mx-6)+"px";cr.style.top=(my-6)+"px";});
(function loop(){tx+=(mx-tx-20)*.13;ty+=(my-ty-20)*.13;cr2.style.left=tx+"px";cr2.style.top=ty+"px";requestAnimationFrame(loop);})();
document.querySelectorAll("a,button").forEach(el=>{el.addEventListener("mouseenter",()=>{cr.style.transform="scale(2.5)";cr.style.opacity=".5";});el.addEventListener("mouseleave",()=>{cr.style.transform="scale(1)";cr.style.opacity="1";});});
const vid=document.getElementById("coopVid"),ov=document.getElementById("vidOverlay");
if(vid){vid.muted=true;}
if(vid&&ov){ov.addEventListener("click",()=>{if(vid.paused){vid.play();ov.style.opacity="0";ov.style.pointerEvents="none";}else{vid.pause();ov.style.opacity="1";ov.style.pointerEvents="all";}});vid.addEventListener("pause",()=>{ov.style.opacity="1";ov.style.pointerEvents="all";});vid.addEventListener("ended",()=>{ov.style.opacity="1";ov.style.pointerEvents="all";});}
window.addEventListener("scroll",()=>{const n=document.getElementById("mainNav");const s=document.querySelector(".signup-bar");n.classList.toggle("bg",scrollY>50);document.getElementById("btt").classList.toggle("show",scrollY>500);if(s){const h=s.offsetHeight||56;n.style.top=scrollY>h?"0px":(h+"px");}});
const o=new IntersectionObserver(entries=>entries.forEach(e=>{if(e.isIntersecting)e.target.classList.add("in");}),{threshold:.08});
document.querySelectorAll(".sr").forEach(el=>o.observe(el));
function count(el){const t=parseInt(el.dataset.count);const em=el.querySelector("em").outerHTML;let n=0;const s=t/70;const tick=()=>{n=Math.min(n+s,t);el.innerHTML=Math.floor(n)+em;if(n<t)requestAnimationFrame(tick);};tick();}
const co=new IntersectionObserver(entries=>entries.forEach(e=>{if(e.isIntersecting){count(e.target);co.unobserve(e.target);}}),{threshold:.5});
document.querySelectorAll("[data-count]").forEach(el=>co.observe(el));
</script>');

// ================================================================
// ASSEMBLE ALL SECTIONS
// ================================================================
$allElements = [
    $globalCss,
    $sec1_signup,
    $sec2_nav,
    $heroContent,
    $sec4_ticker,
    $sec5_about,
    $sec6_camps,
    $sec7_benefits,
    $sec8_how,
    $sec9_programs,
    $sec10_photos,
    $sec11_test,
    $sec12_pricing,
    $sec13_cta,
    $sec14_bio,
    $sec15_footer,
    $globalJs,
];

$rootData = [container([
    'content_width' => 'full-width',
    'flex_direction' => 'column',
    'padding' => ['unit'=>'px','top'=>'0','right'=>'0','bottom'=>'0','left'=>'0','isLinked'=>true],
    'gap' => ['size' => 0, 'unit' => 'px'],
], $allElements)];

$elementorJson = json_encode($rootData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

// ================================================================
// UPDATE DATABASE
// ================================================================
echo "Updating database...\n";
$db = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->exec("SET SESSION sql_mode = ''");

$stmt = $db->prepare("UPDATE wp_postmeta SET meta_value = ? WHERE post_id = 2 AND meta_key = '_elementor_data'");
$stmt->execute([$elementorJson]);

if ($stmt->rowCount() > 0) {
    echo "✓ Elementor data updated! (" . strlen($elementorJson) . " bytes)\n";
} else {
    $check = $db->query("SELECT meta_id FROM wp_postmeta WHERE post_id = 2 AND meta_key = '_elementor_data'")->fetch();
    if (!$check) {
        $db->prepare("INSERT INTO wp_postmeta (post_id, meta_key, meta_value) VALUES (2, '_elementor_data', ?)")->execute([$elementorJson]);
        echo "✓ Elementor data inserted!\n";
    }
}

$db->exec("UPDATE wp_postmeta SET meta_value = '' WHERE post_id = 2 AND meta_key = '_elementor_css'");
$cssDir = 'C:\\xampp\\htdocs\\zaid\\wp-content\\uploads\\elementor\\css';
if (is_dir($cssDir)) {
    array_map('unlink', glob("$cssDir/*.css"));
    echo "✓ Elementor CSS cache cleared\n";
}

echo "\n=== DONE! ===\n";
echo "Visit: $siteUrl\n";
echo "Admin: $siteUrl/wp-admin (admin / admin123)\n";
echo "Native widgets: headings, text-editors, buttons, images — all client-editable!\n";
