<?php

namespace App\Services\Layouts;

/**
 * Ember — Bold, energetic dark theme with fiery red accent.
 * Dark charcoal backgrounds with red accent pops, bold uppercase
 * Montserrat headings (900 weight), scroll-reveal animations,
 * cards with red top-border on hover, custom red cursor,
 * image overlays with warm dark gradients, ticker bar, big red stats.
 */
class LayoutEmber extends AbstractLayout
{
    public function slug(): string { return 'ember'; }
    public function name(): string { return 'Ember'; }
    public function description(): string { return 'Bold, energetic dark theme with fiery red accent for high-impact brands'; }
    public function bestFor(): array { return ['Restaurant', 'Food', 'Entertainment', 'Nightlife']; }
    public function isDark(): bool { return true; }

    public function colors(): array
    {
        return [
            'primary'   => '#DC2626',
            'secondary' => '#EF4444',
            'accent'    => '#F59E0B',
            'bg'        => '#0C0A09',
            'surface'   => '#1C1917',
            'surface2'  => '#292524',
            'text'      => '#FAFAF9',
            'muted'     => 'rgba(250,250,249,0.5)',
            'border'    => 'rgba(250,250,249,0.08)',
        ];
    }

    public function fonts(): array
    {
        return ['heading' => 'Montserrat', 'body' => 'Open Sans'];
    }

    // ═══════════════════════════════════════════════════════════
    // GLOBAL CSS
    // ═══════════════════════════════════════════════════════════

    public function buildGlobalCss(): string
    {
        $c = $this->colors();
        $f = $this->fonts();
        $hf = str_replace(' ', '+', $f['heading']);
        $bf = str_replace(' ', '+', $f['body']);

        return <<<CSS
<link href="https://fonts.googleapis.com/css2?family={$hf}:wght@300;400;600;700;800;900&family={$bf}:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{--ember-bg:{$c['bg']};--ember-surface:{$c['surface']};--ember-surface2:{$c['surface2']};--ember-text:{$c['text']};--ember-muted:{$c['muted']};--ember-border:{$c['border']};--ember-primary:{$c['primary']};--ember-secondary:{$c['secondary']};--ember-accent:{$c['accent']};}
body,body.elementor-template-canvas{background:var(--ember-bg);color:var(--ember-text);font-family:'{$f['body']}',sans-serif;overflow-x:hidden;margin:0;padding:0;}
.elementor-element,.elementor.elementor-2{font-family:'{$f['body']}',sans-serif;}
.elementor-widget{margin-bottom:0 !important;}
.e-con{--gap:0px;}
.e-con:not(.e-con--row)>.elementor-widget{width:100%;}
.e-con--row>.elementor-widget{width:auto;}

/* Custom Cursor (red dot) */
#ember-cr{width:12px;height:12px;background:var(--ember-primary);border-radius:50%;position:fixed;top:0;left:0;z-index:99999;pointer-events:none;mix-blend-mode:screen;transition:transform .15s;}
#ember-cr2{width:40px;height:40px;border:1px solid rgba(220,38,38,.5);border-radius:50%;position:fixed;top:0;left:0;z-index:99998;pointer-events:none;}

/* Animations */
@keyframes fadeUp{from{opacity:0;transform:translateY(28px)}to{opacity:1;transform:translateY(0)}}
@keyframes fadeIn{from{opacity:0}to{opacity:1}}
@keyframes roll{from{transform:translateX(0)}to{transform:translateX(-50%)}}
@keyframes pulse{0%,100%{opacity:.6}50%{opacity:1}}
@keyframes glow{0%,100%{box-shadow:0 0 20px rgba(220,38,38,.2)}50%{box-shadow:0 0 40px rgba(220,38,38,.4)}}
.sr{opacity:0;transform:translateY(36px);transition:opacity .85s ease,transform .85s ease;}
.sr.d1{transition-delay:.1s}.sr.d2{transition-delay:.2s}.sr.d3{transition-delay:.3s}.sr.d4{transition-delay:.45s}
.sr.in{opacity:1;transform:none;}

/* Eyebrow */
.eyebrow{display:inline-flex;align-items:center;gap:12px;font-size:11px;font-weight:700;letter-spacing:4px;text-transform:uppercase;color:var(--ember-primary);margin-bottom:20px;}
.eyebrow::before{content:'';width:28px;height:2px;background:var(--ember-primary);}

/* Ticker */
.ember-ticker{overflow:hidden;white-space:nowrap;padding:16px 0;}
.ember-ticker-inner{display:inline-flex;animation:roll 24s linear infinite;}
.ember-ticker-inner span{font-family:'{$f['heading']}',sans-serif;font-size:15px;font-weight:900;letter-spacing:5px;text-transform:uppercase;color:var(--ember-bg);padding:0 40px;}
.ember-ticker-inner .dot{color:rgba(12,10,9,.3)!important;padding:0 4px!important;}

/* Card Hovers — red top-border on hover */
.ember-card{position:relative;overflow:hidden;transition:background .3s,transform .3s;cursor:pointer;}
.ember-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:var(--ember-primary);transform:scaleX(0);transform-origin:left;transition:transform .5s;}
.ember-card:hover{background:rgba(250,250,249,.03)!important;transform:translateY(-4px);}
.ember-card:hover::before{transform:scaleX(1);}

/* Benefit Card */
.ember-bcard{text-align:center;transition:all .35s;position:relative;overflow:hidden;}
.ember-bcard::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:var(--ember-primary);transform:scaleX(0);transform-origin:center;transition:transform .5s;}
.ember-bcard:hover{background:var(--ember-surface2)!important;transform:translateY(-5px);}
.ember-bcard:hover::before{transform:scaleX(1);}

/* Step Container */
.ember-step{border-bottom:1px solid var(--ember-border);transition:all .3s;}
.ember-step:first-child{border-top:1px solid var(--ember-border);}

/* Testimonial Card */
.ember-tcard{border:1px solid var(--ember-border);border-radius:4px;transition:all .35s;}
.ember-tcard:hover{border-color:rgba(220,38,38,.3);transform:translateY(-5px);}
.ember-tcard-feat{border-color:var(--ember-primary)!important;}
.ember-tcard-feat:hover{transform:translateY(-8px)!important;box-shadow:0 24px 60px rgba(220,38,38,.35);}

/* Badge */
.ember-badge{display:inline-flex;align-items:center;gap:5px;margin-top:14px;padding:5px 12px;border-radius:50px;font-size:10px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;background:rgba(220,38,38,.08);border:1px solid rgba(220,38,38,.2);color:var(--ember-primary);}

/* Stars */
.ember-stars{color:var(--ember-accent);letter-spacing:2px;font-size:14px;margin-bottom:16px;}

/* Pricing */
.ember-plan{border-radius:4px;overflow:hidden;box-shadow:0 24px 64px rgba(220,38,38,.3);}
.ep-price{display:flex;align-items:flex-start;justify-content:center;gap:4px;margin-bottom:10px;}
.ep-cur{font-size:26px;font-weight:700;color:var(--ember-bg);margin-top:14px;}
.ep-num{font-family:'{$f['heading']}',sans-serif;font-size:108px;font-weight:900;line-height:1;color:var(--ember-bg);letter-spacing:-4px;}
.ep-mo{font-size:16px;color:rgba(12,10,9,.4);align-self:flex-end;padding-bottom:14px;}
.ep-features{list-style:none;display:grid;grid-template-columns:1fr 1fr;gap:14px 40px;margin:0 0 40px 0;padding:0;}
.ep-features li{display:flex;align-items:center;gap:10px;font-size:15px;color:rgba(12,10,9,.75);}
.ep-chk{width:22px;height:22px;min-width:22px;border-radius:50%;background:rgba(12,10,9,.14);display:flex;align-items:center;justify-content:center;font-size:11px;color:var(--ember-bg);}

/* Stats */
.ember-stat{text-align:center;}
.ember-stat-n{font-family:'{$f['heading']}',sans-serif;font-size:60px;font-weight:900;color:var(--ember-primary);line-height:1;}
.ember-stat-l{font-size:11px;letter-spacing:3px;text-transform:uppercase;color:rgba(250,250,249,.3);margin-top:6px;font-weight:600;}

/* Footer */
.ember-fc h4{font-size:10px;font-weight:700;letter-spacing:4px;text-transform:uppercase;color:rgba(250,250,249,.4);margin-bottom:22px;padding-bottom:14px;border-bottom:1px solid var(--ember-border);}
.ember-fc ul{list-style:none;display:flex;flex-direction:column;gap:11px;padding:0;margin:0;}
.ember-fc a{font-size:14px;color:rgba(250,250,249,.35);text-decoration:none;display:flex;align-items:center;gap:8px;transition:color .3s;}
.ember-fc a::before{content:'›';color:rgba(220,38,38,.35);transition:color .3s;}
.ember-fc a:hover{color:rgba(250,250,249,.75);}
.ember-fc a:hover::before{color:var(--ember-primary);}
.ember-social{display:flex;gap:8px;margin-top:24px;}
.ember-social a{width:38px;height:38px;border:1px solid var(--ember-border);border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:16px;text-decoration:none;color:rgba(250,250,249,.3);transition:all .3s;}
.ember-social a:hover{border-color:var(--ember-primary);color:var(--ember-primary);background:rgba(220,38,38,.06);transform:translateY(-3px);}

/* Contact info block */
.ember-ci{display:flex;align-items:flex-start;gap:10px;margin-bottom:14px;}
.ember-ci-i{width:34px;height:34px;min-width:34px;background:rgba(220,38,38,.08);border:1px solid rgba(220,38,38,.15);border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:14px;}
.ember-ci small{display:block;font-size:9px;color:rgba(250,250,249,.25);letter-spacing:2px;text-transform:uppercase;margin-bottom:2px;}
.ember-ci span{font-size:13px;color:rgba(250,250,249,.45);}

/* Credential block */
.ember-cred{background:rgba(220,38,38,.06);border:1px solid rgba(220,38,38,.18);border-radius:3px;padding:24px 22px;margin:28px 0;}
.ember-cred h4{font-family:'{$f['heading']}',sans-serif;font-size:13px;font-weight:800;letter-spacing:3px;text-transform:uppercase;color:var(--ember-primary);margin-bottom:14px;}
.ember-cred-item{display:flex;align-items:flex-start;gap:10px;font-size:14px;color:rgba(250,250,249,.6);line-height:1.5;margin-bottom:9px;}
.ember-cred-item .star{color:var(--ember-primary);flex-shrink:0;font-size:12px;margin-top:2px;}

/* Pillar rows */
.ember-pillar{display:flex;align-items:center;gap:20px;padding:22px 24px;border-bottom:1px solid var(--ember-border);transition:all .3s;cursor:pointer;}
.ember-pillar:last-child{border-bottom:none;}
.ember-pillar:hover{background:rgba(220,38,38,.05);padding-left:32px;}
.ember-pillar-ico{width:44px;height:44px;min-width:44px;background:rgba(220,38,38,.1);border:1px solid rgba(220,38,38,.2);border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:20px;}
.ember-pillar h4{font-family:'{$f['heading']}',sans-serif;font-size:17px;font-weight:800;letter-spacing:.5px;color:var(--ember-text);margin-bottom:3px;}
.ember-pillar p{font-size:13px;color:rgba(250,250,249,.4);line-height:1.5;margin:0;}

/* Gallery photo hover */
.ember-photo{overflow:hidden;position:relative;border-radius:4px;}
.ember-photo img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .7s ease;}
.ember-photo:hover img{transform:scale(1.06);}
.ember-pho{position:absolute;inset:0;background:linear-gradient(to top,rgba(12,10,9,.7) 0%,transparent 50%);opacity:0;transition:opacity .4s;}
.ember-photo:hover .ember-pho{opacity:1;}
.ember-gallery-item{overflow:hidden;}

/* Decorative Ornament (centered diamond + lines) */
.ember-ornament{display:flex;align-items:center;justify-content:center;gap:12px;margin-bottom:20px;}
.ember-ornament-line{display:block;width:40px;height:1px;background:var(--ember-primary);opacity:.5;}
.ember-ornament-diamond{display:block;width:8px;height:8px;background:var(--ember-primary);transform:rotate(45deg);opacity:.7;}

/* Reserve Badge (hero) */
.ember-reserve-badge{display:inline-flex;align-items:center;gap:12px;margin-top:36px;padding:12px 28px;border:1px solid rgba(220,38,38,.35);border-radius:0;background:rgba(220,38,38,.08);backdrop-filter:blur(4px);letter-spacing:4px;text-transform:uppercase;font-family:'{$f['heading']}',sans-serif;font-size:11px;font-weight:700;color:var(--ember-primary);transition:all .4s;cursor:pointer;}
.ember-reserve-badge:hover{background:rgba(220,38,38,.15);border-color:var(--ember-primary);transform:translateY(-2px);}
.ember-reserve-icon{font-size:10px;opacity:.6;}
.ember-reserve-text{letter-spacing:5px;}

/* Large Quote Mark (about section) */
.ember-quote-mark{font-family:Georgia,serif;font-size:120px;line-height:0.6;color:var(--ember-primary);opacity:.25;margin-bottom:12px;}

/* Menu Items (restaurant menu style) */
.ember-menu-col{display:flex;flex-direction:column;gap:0;}
.ember-menu-item{padding:24px 0;border-bottom:1px solid var(--ember-border);transition:all .3s;}
.ember-menu-item:first-child{border-top:1px solid var(--ember-border);}
.ember-menu-item:hover{padding-left:8px;background:rgba(220,38,38,.02);}
.ember-menu-item-top{display:flex;align-items:baseline;gap:8px;margin-bottom:8px;}
.ember-menu-item-name{font-family:'{$f['heading']}',sans-serif;font-size:20px;font-weight:800;letter-spacing:.5px;color:var(--ember-text);white-space:nowrap;text-transform:uppercase;}
.ember-menu-item-dots{flex:1;border-bottom:2px dotted rgba(250,250,249,.12);margin-bottom:4px;min-width:20px;}
.ember-menu-item-desc{font-size:14px;color:rgba(250,250,249,.45);line-height:1.7;margin:0;max-width:95%;}

/* Testimonial (single large centered) */
.ember-testimonial-quote{max-width:680px;margin:0 auto;position:relative;}
.ember-big-quote{font-family:Georgia,serif;font-size:80px;line-height:.5;color:var(--ember-primary);opacity:.35;display:block;}
.ember-big-quote-close{margin-top:16px;}
.ember-testimonial-text{font-family:'{$f['body']}',sans-serif;font-size:22px;line-height:1.9;color:rgba(250,250,249,.7);font-style:italic;margin:24px 0;padding:0 16px;}
.ember-testimonial-author{display:flex;align-items:center;justify-content:center;gap:16px;margin-top:32px;}
.ember-testimonial-avatar{width:56px;height:56px;border-radius:50%;background:rgba(220,38,38,.12);border:2px solid rgba(220,38,38,.3);display:flex;align-items:center;justify-content:center;font-family:'{$f['heading']}',sans-serif;font-size:20px;font-weight:900;color:var(--ember-primary);}
.ember-testimonial-info h5{font-family:'{$f['heading']}',sans-serif;font-size:18px;font-weight:800;color:var(--ember-text);margin:0 0 2px 0;}
.ember-testimonial-info span{font-size:13px;color:rgba(250,250,249,.4);letter-spacing:1px;}

/* Opening Hours Hint (CTA section) */
.ember-hours-hint{display:inline-flex;align-items:center;gap:12px;margin-top:32px;font-family:'{$f['body']}',sans-serif;font-size:13px;letter-spacing:2px;text-transform:uppercase;color:rgba(250,250,249,.35);}
.ember-hours-sep{color:var(--ember-primary);font-size:8px;}

/* Back to top */
#ember-btt{position:fixed;bottom:28px;right:28px;width:46px;height:46px;background:var(--ember-primary);color:var(--ember-text);border-radius:2px;font-size:20px;display:flex;align-items:center;justify-content:center;text-decoration:none;z-index:500;opacity:0;transform:translateY(12px);pointer-events:none;transition:all .4s;box-shadow:0 8px 24px rgba(220,38,38,.4);}
#ember-btt.show{opacity:1;transform:translateY(0);pointer-events:all;}
#ember-btt:hover{background:var(--ember-secondary);transform:translateY(-4px)!important;}

/* Responsive */
@media(max-width:1100px){
  .ember-photo{min-height:200px;}.ember-ticker-inner span{font-size:12px;padding:0 24px;}
  .ember-card.e-con,.ember-bcard.e-con,.ember-photo.e-con{--width:48% !important;width:48% !important;}
  .ember-menu-item-name{font-size:18px;}
  .ember-testimonial-text{font-size:19px;}
}
@media(max-width:767px){
  .ember-card.e-con,.ember-bcard.e-con,.ember-stat.e-con,.ember-photo.e-con{--width:100% !important;width:100% !important;}
  .ember-tcard.e-con{--width:100% !important;width:100% !important;}
  .ember-nav ul{display:none !important;}
  .ep-features{grid-template-columns:1fr;}
  .ember-menu-item-name{font-size:16px;}
  .ember-testimonial-text{font-size:17px;line-height:1.8;}
  .ember-big-quote{font-size:50px;}
  .ember-reserve-badge{padding:10px 20px;font-size:10px;letter-spacing:3px;}
  .ember-hours-hint{font-size:11px;gap:8px;}
  .ember-gallery-item.e-con{--width:100% !important;width:100% !important;}
}
{$this->woocommerceCss()}
</style>
CSS;
    }

    public function buildGlobalJs(): string
    {
        return <<<'JS'
<script>
(function(){
const cr=document.createElement('div'),cr2=document.createElement('div');
cr.id='ember-cr';cr2.id='ember-cr2';document.body.appendChild(cr);document.body.appendChild(cr2);
let mx=0,my=0,tx=0,ty=0;
document.addEventListener("mousemove",e=>{mx=e.clientX;my=e.clientY;cr.style.left=(mx-6)+"px";cr.style.top=(my-6)+"px";});
(function loop(){tx+=(mx-tx-20)*.13;ty+=(my-ty-20)*.13;cr2.style.left=tx+"px";cr2.style.top=ty+"px";requestAnimationFrame(loop);})();
document.querySelectorAll("a,button").forEach(el=>{el.addEventListener("mouseenter",()=>{cr.style.transform="scale(2.5)";cr.style.opacity=".5";});el.addEventListener("mouseleave",()=>{cr.style.transform="scale(1)";cr.style.opacity="1";});});
const btt=document.getElementById('ember-btt');
window.addEventListener("scroll",()=>{if(btt)btt.classList.toggle("show",scrollY>500);});
const o=new IntersectionObserver(entries=>entries.forEach(e=>{if(e.isIntersecting)e.target.classList.add("in");}),{threshold:.08});
document.querySelectorAll(".sr").forEach(el=>o.observe(el));
function countUp(el){const t=parseInt(el.dataset.count);let n=0;const s=t/60;const tick=()=>{n=Math.min(n+s,t);el.textContent=Math.floor(n)+(el.dataset.suffix||'');if(n<t)requestAnimationFrame(tick);};tick();}
const co=new IntersectionObserver(entries=>entries.forEach(e=>{if(e.isIntersecting){countUp(e.target);co.unobserve(e.target);}}),{threshold:.5});
document.querySelectorAll("[data-count]").forEach(el=>co.observe(el));
})();
</script>
JS;
    }

    // ═══════════════════════════════════════════════════════════
    // HOME PAGE
    // ═══════════════════════════════════════════════════════════

    public function buildHomePage(array $c, array $img): array
    {
        $siteName = $c['site_name'] ?? 'Business Name';
        $heroTitle = $c['hero_title'] ?? 'A Culinary Experience.';
        $heroSub = $c['hero_subtitle'] ?? 'Seasonal ingredients, bold flavors, and an atmosphere that turns every meal into a memory.';
        $heroCta = $c['hero_cta'] ?? 'View Our Menu';
        $heroCtaUrl = $c['hero_cta_url'] ?? '#menu';

        $aboutTitle = $c['about_title'] ?? 'Our Story';
        $aboutText = $c['about_text'] ?? 'Every dish we serve carries the warmth of tradition and the spark of innovation. Our kitchen is a place where heritage recipes meet modern technique, where locally sourced ingredients are transformed into unforgettable experiences.';
        $aboutText2 = $c['about_text2'] ?? 'We believe dining is more than sustenance — it is ritual, celebration, and craft. From the first greeting to the last bite, every detail is considered, every flavor intentional.';

        $services = $c['services'] ?? [
            ['icon' => '', 'title' => 'Wood-Fired Ribeye', 'desc' => 'Prime cut aged 28 days, seared over cherry wood with roasted bone marrow butter and seasonal vegetables.'],
            ['icon' => '', 'title' => 'Truffle Risotto', 'desc' => 'Arborio rice slow-stirred with black truffle, aged parmesan, and a drizzle of truffle-infused olive oil.'],
            ['icon' => '', 'title' => 'Seared Scallops', 'desc' => 'Day-boat scallops with cauliflower puree, crispy pancetta, golden raisins, and brown butter.'],
            ['icon' => '', 'title' => 'Burrata Salad', 'desc' => 'Creamy burrata with heirloom tomatoes, fresh basil, aged balsamic, and extra virgin olive oil.'],
            ['icon' => '', 'title' => 'Duck Confit', 'desc' => 'Slow-braised duck leg with cherry gastrique, roasted root vegetables, and wild mushroom jus.'],
            ['icon' => '', 'title' => 'Chocolate Fondant', 'desc' => 'Valrhona dark chocolate with a molten center, served with vanilla bean ice cream and salted caramel.'],
        ];

        $testimonials = $c['testimonials'] ?? [
            ['quote' => 'The most extraordinary dining experience we have ever had. Every course was a masterpiece, and the atmosphere was simply magical.', 'name' => 'Sarah M.', 'role' => 'Food Critic', 'initials' => 'SM'],
            ['quote' => 'A restaurant that truly understands the art of hospitality. We keep coming back for the warmth as much as the food.', 'name' => 'James K.', 'role' => 'Regular Guest', 'initials' => 'JK'],
            ['quote' => 'From the amuse-bouche to the petit fours, every detail was perfect. This is dining elevated to an art form.', 'name' => 'Lisa R.', 'role' => 'Culinary Blogger', 'initials' => 'LR'],
        ];

        $heroImg = $img['hero'] ?? '';
        $aboutImg = $img['about'] ?? '';
        $galleryImgs = array_filter([
            $img['gallery1'] ?? '',
            $img['gallery2'] ?? '',
            $img['gallery3'] ?? '',
            $img['services'] ?? '',
        ]);

        $sections = [];

        // ─── HERO (centered text, full-bleed bg) ───
        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'column',
            'flex_align_items' => 'center',
            'flex_justify_content' => 'center',
            'padding' => self::pad(180, 64, 140, 64),
            'padding_mobile' => self::pad(120, 24, 100, 24),
            'padding_tablet' => self::pad(140, 40, 120, 40),
            'min_height' => ['size' => 100, 'unit' => 'vh', 'sizes' => []],
            'background_background' => 'classic',
            'background_image' => ['url' => $heroImg, 'id' => ''],
            'background_position' => 'center center',
            'background_size' => 'cover',
            '_element_id' => 'hero',
            'custom_css' => "selector{position:relative;overflow:hidden;text-align:center;}
selector::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse at center,rgba(12,10,9,.65) 0%,rgba(12,10,9,.88) 100%);z-index:0;}
selector>.e-con-inner,selector>.elementor-widget{position:relative;z-index:2;}",
        ], [
            // Decorative ornament above title
            self::html('<div class="ember-ornament sr">
                <span class="ember-ornament-line"></span>
                <span class="ember-ornament-diamond"></span>
                <span class="ember-ornament-line"></span>
            </div>'),

            $this->eyebrow($c['hero_eyebrow'] ?? 'Welcome to ' . $siteName),

            $this->headline($heroTitle, 'h1', array_merge(
                self::responsiveSize(96, 64, 44),
                [
                    'align' => 'center',
                    'typography_letter_spacing' => ['size' => -1, 'unit' => 'px'],
                    '_margin' => self::margin(16, 0, 28, 0),
                    'custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .35s forwards;max-width:900px;margin-left:auto;margin-right:auto;}',
                ]
            )),

            self::textEditor('<p>' . e($heroSub) . '</p>', [
                'align' => 'center',
                'text_color' => 'rgba(250,250,249,0.55)',
                'typography_typography' => 'custom',
                'typography_font_family' => $this->fonts()['body'],
                'typography_font_size' => ['size' => 19, 'unit' => 'px'],
                'typography_line_height' => ['size' => 1.8, 'unit' => 'em'],
                'typography_font_weight' => '400',
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .55s forwards;max-width:560px;margin-left:auto;margin-right:auto;}',
            ]),

            self::container([
                'flex_direction' => 'row',
                'flex_align_items' => 'center',
                'flex_justify_content' => 'center',
                'flex_gap' => ['size' => 16, 'unit' => 'px', 'column' => '16', 'row' => '16'],
                'content_width' => 'full',
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .7s forwards;}',
            ], [
                $this->ctaButton($heroCta, $heroCtaUrl, [
                    'custom_css' => 'selector .elementor-button:hover{transform:translateY(-3px);box-shadow:0 20px 50px rgba(220,38,38,.45);}',
                ]),
                $this->ghostButton($c['hero_ghost_cta'] ?? 'Reserve a Table', '#cta'),
            ]),

            // "Reserve a Table" decorative badge
            self::html('<div class="ember-reserve-badge sr d3">
                <span class="ember-reserve-icon">&#9733;</span>
                <span class="ember-reserve-text">RESERVE A TABLE</span>
                <span class="ember-reserve-icon">&#9733;</span>
            </div>'),
        ]);

        // ─── ABOUT (full-width centered text, decorative quote) ───
        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface'],
            'border_border' => 'solid',
            'border_width' => self::pad(1, 0, 1, 0),
            'border_color' => $this->colors()['border'],
        ], [
            self::container([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'padding' => self::pad(20, 0),
                'custom_css' => 'selector{text-align:center;max-width:800px;margin-left:auto;margin-right:auto;}',
            ], [
                $this->eyebrow($c['about_eyebrow'] ?? 'Our Story'),

                // Decorative large quotation mark
                self::html('<div class="ember-quote-mark sr">&ldquo;</div>'),

                $this->headline($aboutTitle, 'h2', array_merge(
                    self::responsiveSize(52, 40, 32),
                    [
                        'align' => 'center',
                        '_margin' => self::margin(0, 0, 32, 0),
                        'custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .2s forwards;}',
                    ]
                )),

                self::textEditor('<p>' . e($aboutText) . '</p>', [
                    'align' => 'center',
                    'text_color' => 'rgba(250,250,249,0.6)',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['body'],
                    'typography_font_size' => ['size' => 18, 'unit' => 'px'],
                    'typography_line_height' => ['size' => 2, 'unit' => 'em'],
                    'typography_font_weight' => '400',
                    '_margin' => self::margin(0, 0, 20, 0),
                    'custom_css' => 'selector{max-width:720px;margin-left:auto;margin-right:auto;}',
                ]),

                self::textEditor('<p>' . e($aboutText2) . '</p>', [
                    'align' => 'center',
                    'text_color' => 'rgba(250,250,249,0.45)',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['body'],
                    'typography_font_size' => ['size' => 16, 'unit' => 'px'],
                    'typography_line_height' => ['size' => 1.9, 'unit' => 'em'],
                    'typography_font_weight' => '400',
                    'typography_font_style' => 'italic',
                    '_margin' => self::margin(0, 0, 32, 0),
                    'custom_css' => 'selector{max-width:620px;margin-left:auto;margin-right:auto;}',
                ]),

                // Decorative ornament below text
                self::html('<div class="ember-ornament sr d2">
                    <span class="ember-ornament-line"></span>
                    <span class="ember-ornament-diamond"></span>
                    <span class="ember-ornament-line"></span>
                </div>'),
            ]),
        ], 'about');

        // ─── MENU / SERVICES (restaurant menu-style horizontal rows, 2 columns) ───
        $menuColLeft = [];
        $menuColRight = [];
        foreach ($services as $i => $svc) {
            $item = '<div class="ember-menu-item sr d' . min($i + 1, 4) . '">
                <div class="ember-menu-item-top">
                    <span class="ember-menu-item-name">' . e($svc['title']) . '</span>
                    <span class="ember-menu-item-dots"></span>
                </div>
                <p class="ember-menu-item-desc">' . e($svc['desc']) . '</p>
            </div>';
            if ($i < ceil(count($services) / 2)) {
                $menuColLeft[] = $item;
            } else {
                $menuColRight[] = $item;
            }
        }

        $leftHtml = implode('', $menuColLeft);
        $rightHtml = implode('', $menuColRight);

        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['bg'],
        ], [
            self::container([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                '_margin' => self::margin(0, 0, 56, 0),
                'custom_css' => 'selector{text-align:center;}',
            ], [
                $this->eyebrow($c['services_eyebrow'] ?? 'The Menu'),
                $this->headline($c['services_title'] ?? 'Our Signature Dishes.', 'h2', [
                    'align' => 'center',
                ]),
                $this->bodyText($c['services_subtitle'] ?? 'Crafted with passion, served with pride.', [
                    'align' => 'center',
                    '_margin' => self::margin(0),
                    'custom_css' => 'selector{max-width:500px;margin-left:auto;margin-right:auto;}',
                ]),
            ]),
            self::container([
                'flex_direction' => 'row',
                'flex_direction_mobile' => 'column',
                'content_width' => 'full',
                'flex_gap' => ['size' => 48, 'unit' => 'px', 'column' => '48', 'row' => '32'],
            ], [
                // Left column
                self::container([
                    'content_width' => 'full',
                    'flex_direction' => 'column',
                    'width' => ['size' => 50, 'unit' => '%', 'sizes' => []],
                    'width_tablet' => ['size' => 50, 'unit' => '%', 'sizes' => []],
                    'width_mobile' => ['size' => 100, 'unit' => '%', 'sizes' => []],
                ], [
                    self::html('<div class="ember-menu-col">' . $leftHtml . '</div>'),
                ]),
                // Right column
                self::container([
                    'content_width' => 'full',
                    'flex_direction' => 'column',
                    'width' => ['size' => 50, 'unit' => '%', 'sizes' => []],
                    'width_tablet' => ['size' => 50, 'unit' => '%', 'sizes' => []],
                    'width_mobile' => ['size' => 100, 'unit' => '%', 'sizes' => []],
                ], [
                    self::html('<div class="ember-menu-col">' . $rightHtml . '</div>'),
                ]),
            ]),
        ], 'menu');

        // ─── GALLERY (full-width 3-image grid) ───
        $galleryItems = [];
        $gallerySlice = array_slice(array_values($galleryImgs), 0, 3);
        // Pad to 3 if we have fewer images
        while (count($gallerySlice) < 3 && $heroImg) {
            $gallerySlice[] = $aboutImg ?: $heroImg;
        }
        foreach ($gallerySlice as $i => $gImg) {
            $galleryItems[] = self::container([
                'content_width' => 'full',
                'width' => ['size' => 33.33, 'unit' => '%', 'sizes' => []],
                'width_tablet' => ['size' => 33.33, 'unit' => '%', 'sizes' => []],
                'width_mobile' => ['size' => 100, 'unit' => '%', 'sizes' => []],
                'padding' => self::pad(0),
                'css_classes' => 'ember-gallery-item sr d' . ($i + 1),
            ], [
                self::image($gImg, [
                    'custom_css' => 'selector img{width:100%;height:360px;object-fit:cover;display:block;transition:transform .8s ease,filter .6s ease;filter:brightness(0.85);} selector:hover img{transform:scale(1.05);filter:brightness(1);}',
                ]),
            ]);
        }

        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'row',
            'flex_direction_mobile' => 'column',
            'flex_gap' => ['size' => 4, 'unit' => 'px', 'column' => '4', 'row' => '4'],
            'padding' => self::pad(0),
            '_element_id' => 'gallery',
        ], $galleryItems);

        // ─── TESTIMONIAL (single large centered quote) ───
        $featTest = $testimonials[0] ?? $testimonials[array_key_first($testimonials)];

        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface'],
        ], [
            self::container([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'custom_css' => 'selector{text-align:center;max-width:780px;margin-left:auto;margin-right:auto;}',
            ], [
                $this->eyebrow($c['testimonials_eyebrow'] ?? 'Guest Reviews'),

                // Large decorative quotation marks
                self::html('<div class="ember-testimonial-quote sr">
                    <span class="ember-big-quote">&ldquo;</span>
                    <p class="ember-testimonial-text">' . e($featTest['quote']) . '</p>
                    <span class="ember-big-quote ember-big-quote-close">&rdquo;</span>
                </div>'),

                // Author
                self::html('<div class="ember-testimonial-author sr d2">
                    <div class="ember-testimonial-avatar">' . e($featTest['initials'] ?? 'SM') . '</div>
                    <div class="ember-testimonial-info">
                        <h5>' . e($featTest['name']) . '</h5>
                        <span>' . e($featTest['role']) . '</span>
                    </div>
                </div>'),

                // Star rating
                self::html('<div class="ember-stars sr d3" style="margin-top:24px;font-size:18px;letter-spacing:4px;">&#9733;&#9733;&#9733;&#9733;&#9733;</div>'),
            ]),
        ], 'testimonials');

        // ─── CTA (full-bleed image, reservation-focused) ───
        $ctaImg = $galleryImgs[0] ?? $heroImg;
        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'column',
            'flex_align_items' => 'center',
            'flex_justify_content' => 'center',
            'min_height' => ['size' => 550, 'unit' => 'px', 'sizes' => []],
            'padding' => self::pad(100, 64),
            'padding_mobile' => self::pad(80, 24),
            'background_background' => 'classic',
            'background_image' => ['url' => $ctaImg, 'id' => ''],
            'background_position' => 'center center',
            'background_size' => 'cover',
            '_element_id' => 'cta',
            'custom_css' => "selector{position:relative;overflow:hidden;text-align:center;}
selector::before{content:'';position:absolute;inset:0;background:rgba(12,10,9,0.82);z-index:0;}
selector>.e-con-inner,selector>.elementor-widget{position:relative;z-index:2;}",
        ], [
            self::html('<div class="ember-ornament sr">
                <span class="ember-ornament-line"></span>
                <span class="ember-ornament-diamond"></span>
                <span class="ember-ornament-line"></span>
            </div>'),

            $this->headline($c['cta_title'] ?? 'Reserve Your Table.', 'h2', array_merge(
                self::responsiveSize(80, 56, 38),
                ['align' => 'center', '_margin' => self::margin(24, 0, 24, 0)]
            )),

            $this->bodyText($c['cta_text'] ?? 'Join us for an unforgettable dining experience. Book your table today and let us take care of the rest.', [
                'align' => 'center',
                'typography_font_size' => ['size' => 18, 'unit' => 'px'],
                'custom_css' => 'selector{max-width:540px;margin-left:auto;margin-right:auto;}',
                '_margin' => self::margin(0, 0, 44, 0),
            ]),

            self::container([
                'flex_direction' => 'row',
                'flex_justify_content' => 'center',
                'flex_gap' => ['size' => 14, 'unit' => 'px', 'column' => '14', 'row' => '14'],
                'content_width' => 'full',
                'css_classes' => 'sr d3',
            ], [
                $this->ctaButton($c['cta_button'] ?? 'Book a Table', '#contact'),
                $this->ghostButton($c['cta_ghost'] ?? 'View Menu', '#menu'),
            ]),

            // Opening hours hint
            self::html('<div class="ember-hours-hint sr d4">
                <span>Open Daily</span>
                <span class="ember-hours-sep">&bull;</span>
                <span>5:00 PM &ndash; 11:00 PM</span>
            </div>'),
        ]);

        // Back to top
        $sections[] = self::html('<a href="#hero" id="ember-btt">&#8593;</a>');

        return $sections;
    }

    // ═══════════════════════════════════════════════════════════
    // ABOUT PAGE
    // ═══════════════════════════════════════════════════════════

    public function buildAboutPage(array $c, array $img): array
    {
        $sections = [];

        // Hero banner
        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'column',
            'flex_justify_content' => 'center',
            'padding' => self::pad(140, 64, 100, 64),
            'padding_mobile' => self::pad(100, 20, 60, 20),
            'padding_tablet' => self::pad(120, 40, 80, 40),
            'min_height' => ['size' => 50, 'unit' => 'vh', 'sizes' => []],
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface'],
        ], [
            $this->eyebrow('About Us'),
            $this->headline($c['about_title'] ?? 'Our Story.', 'h1', array_merge(
                self::responsiveSize(100, 64, 42),
                ['custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .2s forwards;}']
            )),
            $this->bodyText($c['about_text'] ?? 'Fueled by passion and driven by bold ambition.', [
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .4s forwards;max-width:560px;}',
            ]),
        ]);

        // Two-col about
        $sections[] = self::twoCol(
            [self::image($img['about'] ?? '', [
                'custom_css' => 'selector img{width:100%;min-height:400px;object-fit:cover;border-radius:4px;}',
            ])],
            [
                $this->eyebrow('Who We Are'),
                $this->headline($c['about_subtitle'] ?? 'Passion Meets Power.', 'h2', [
                    'typography_font_size' => ['size' => 48, 'unit' => 'px'],
                ]),
                $this->bodyText($c['about_text'] ?? 'We are a team of fierce professionals.'),
                $this->bodyText($c['about_text2'] ?? 'With years of experience delivering bold, impactful results.'),
                $this->ctaButton($c['about_cta'] ?? 'Our Services', '#services', [
                    '_margin' => self::margin(20, 0, 0, 0),
                ]),
            ],
            50,
            ['padding' => self::pad(100, 64)],
            ['css_classes' => 'sr'],
            ['css_classes' => 'sr d2', 'flex_justify_content' => 'center']
        );

        // Stats
        $stats = $c['stats'] ?? [
            ['number' => '500', 'suffix' => '+', 'label' => 'Projects'],
            ['number' => '98', 'suffix' => '%', 'label' => 'Satisfaction'],
            ['number' => '15', 'suffix' => '+', 'label' => 'Years'],
        ];
        $statEls = [];
        foreach ($stats as $i => $s) {
            $statEls[] = self::container([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'width' => ['size' => round(100 / count($stats), 2), 'unit' => '%', 'sizes' => []],
                'width_tablet' => ['size' => 48, 'unit' => '%', 'sizes' => []],
                'width_mobile' => ['size' => 100, 'unit' => '%', 'sizes' => []],
                'padding' => self::pad(48, 20),
                'background_background' => 'classic',
                'background_color' => $this->colors()['surface'],
                'css_classes' => 'ember-stat sr d' . ($i + 1),
            ], [
                self::html('<div class="ember-stat-n" data-count="' . $s['number'] . '" data-suffix="' . ($s['suffix'] ?? '') . '">' . $s['number'] . ($s['suffix'] ?? '') . '</div>'),
                self::html('<div class="ember-stat-l">' . e($s['label']) . '</div>'),
            ]);
        }
        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'row',
            'flex_direction_mobile' => 'column',
            'flex_wrap' => 'wrap',
            'flex_gap' => ['size' => 2, 'unit' => 'px', 'column' => '2', 'row' => '2'],
            'padding' => self::pad(0),
        ], $statEls);

        // Values section
        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface2'],
        ], [
            $this->eyebrow('Our Values'),
            $this->headline($c['values_title'] ?? 'What Fuels Us.', 'h2'),
            $this->bodyText($c['values_text'] ?? 'Core values that ignite everything we do.', [
                '_margin' => self::margin(0, 0, 50, 0),
                'custom_css' => 'selector{max-width:500px;}',
            ]),
        ]);

        return $sections;
    }

    // ═══════════════════════════════════════════════════════════
    // SERVICES PAGE
    // ═══════════════════════════════════════════════════════════

    public function buildServicesPage(array $c, array $img): array
    {
        $sections = [];

        // Hero banner
        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'column',
            'flex_justify_content' => 'center',
            'padding' => self::pad(140, 64, 100, 64),
            'padding_mobile' => self::pad(100, 20, 60, 20),
            'padding_tablet' => self::pad(120, 40, 80, 40),
            'min_height' => ['size' => 50, 'unit' => 'vh', 'sizes' => []],
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface'],
        ], [
            $this->eyebrow('Our Services'),
            $this->headline($c['services_title'] ?? 'What We Offer.', 'h1', array_merge(
                self::responsiveSize(100, 64, 42),
                ['custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .2s forwards;}']
            )),
            $this->bodyText($c['services_subtitle'] ?? 'Bold, high-impact solutions for ambitious brands.', [
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .4s forwards;max-width:560px;}',
            ]),
        ]);

        // Service cards
        $services = $c['services'] ?? [
            ['icon' => '🔥', 'title' => 'Brand Strategy', 'desc' => 'Bold strategic planning for market dominance.'],
            ['icon' => '🎯', 'title' => 'Creative Design', 'desc' => 'Eye-catching visuals that captivate audiences.'],
            ['icon' => '📈', 'title' => 'Growth Marketing', 'desc' => 'Data-driven campaigns for explosive growth.'],
        ];

        $cards = [];
        foreach ($services as $i => $svc) {
            $cards[] = self::container([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'padding' => self::pad(44, 32),
                'background_background' => 'classic',
                'background_color' => $this->colors()['surface'],
                'css_classes' => 'ember-card sr d' . min($i + 1, 4),
            ], [
                self::textEditor('<span style="font-size:40px;display:block;margin-bottom:20px;">' . ($svc['icon'] ?? '🔥') . '</span>'),
                self::heading($svc['title'], 'h3', [
                    'title_color' => $this->colors()['text'],
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['heading'],
                    'typography_font_size' => ['size' => 26, 'unit' => 'px'],
                    'typography_font_weight' => '800',
                    '_margin' => self::margin(0, 0, 12, 0),
                ]),
                $this->bodyText($svc['desc']),
            ]);
        }

        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['bg'],
        ], [
            self::cardGrid($cards, min(count($cards), 3), [
                'flex_gap' => ['size' => 2, 'unit' => 'px', 'column' => '2', 'row' => '2'],
            ]),
        ], 'services-grid');

        // CTA
        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['primary'],
            'flex_align_items' => 'center',
        ], [
            $this->headline($c['cta_title'] ?? 'Ready to Ignite?', 'h2', [
                'title_color' => '#FAFAF9',
                'align' => 'center',
            ]),
            $this->bodyText($c['cta_text'] ?? 'Contact us today for a free consultation.', [
                'align' => 'center',
                'text_color' => 'rgba(250,250,249,0.7)',
                '_margin' => self::margin(0, 0, 30, 0),
            ]),
            self::button($c['cta_button'] ?? 'Contact Us', '#contact', [
                'background_color' => '#0C0A09',
                'button_text_color' => '#FAFAF9',
                'typography_typography' => 'custom',
                'typography_font_family' => $this->fonts()['heading'],
                'typography_font_size' => ['size' => 14, 'unit' => 'px'],
                'typography_font_weight' => '800',
                'typography_letter_spacing' => ['size' => 3, 'unit' => 'px'],
                'typography_text_transform' => 'uppercase',
                'border_radius' => self::radius(2),
                'button_padding' => self::pad(16, 44),
            ]),
        ]);

        return $sections;
    }

    // ═══════════════════════════════════════════════════════════
    // PORTFOLIO PAGE
    // ═══════════════════════════════════════════════════════════

    public function buildPortfolioPage(array $c, array $img): array
    {
        $sections = [];

        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'column',
            'flex_justify_content' => 'center',
            'padding' => self::pad(140, 64, 100, 64),
            'padding_mobile' => self::pad(100, 20, 60, 20),
            'padding_tablet' => self::pad(120, 40, 80, 40),
            'min_height' => ['size' => 50, 'unit' => 'vh', 'sizes' => []],
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface'],
        ], [
            $this->eyebrow('Our Work'),
            $this->headline($c['portfolio_title'] ?? 'Featured Projects.', 'h1', array_merge(
                self::responsiveSize(100, 64, 42),
                ['custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .2s forwards;}']
            )),
        ]);

        // Gallery grid
        $galleryImgs = array_filter([
            $img['hero'] ?? '', $img['about'] ?? '',
            $img['gallery1'] ?? '', $img['gallery2'] ?? '',
            $img['services'] ?? '', $img['team'] ?? '',
        ]);

        $photoEls = [];
        foreach (array_slice($galleryImgs, 0, 6) as $i => $url) {
            if (!$url) continue;
            $photoEls[] = self::container([
                'content_width' => 'full',
                'width' => ['size' => 31, 'unit' => '%', 'sizes' => []],
                'width_tablet' => ['size' => 48, 'unit' => '%', 'sizes' => []],
                'width_mobile' => ['size' => 100, 'unit' => '%', 'sizes' => []],
                'css_classes' => 'ember-photo sr d' . min($i + 1, 4),
                'custom_css' => 'selector{min-height:300px;}',
            ], [
                self::image($url, [
                    'custom_css' => 'selector img{width:100%;height:300px;object-fit:cover;border-radius:4px;}',
                ]),
            ]);
        }

        if ($photoEls) {
            $sections[] = $this->section([
                'background_background' => 'classic',
                'background_color' => $this->colors()['bg'],
            ], [
                self::container([
                    'flex_direction' => 'row',
                    'flex_direction_mobile' => 'column',
                    'flex_wrap' => 'wrap',
                    'content_width' => 'full',
                    'flex_gap' => ['size' => 16, 'unit' => 'px', 'column' => '16', 'row' => '16'],
                ], $photoEls),
            ]);
        }

        return $sections;
    }

    // ═══════════════════════════════════════════════════════════
    // CONTACT PAGE
    // ═══════════════════════════════════════════════════════════

    public function buildContactPage(array $c, array $img): array
    {
        $sections = [];

        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'column',
            'flex_justify_content' => 'center',
            'padding' => self::pad(140, 64, 100, 64),
            'padding_mobile' => self::pad(100, 20, 60, 20),
            'padding_tablet' => self::pad(120, 40, 80, 40),
            'min_height' => ['size' => 50, 'unit' => 'vh', 'sizes' => []],
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface'],
        ], [
            $this->eyebrow('Contact'),
            $this->headline($c['contact_title'] ?? 'Get In Touch.', 'h1', array_merge(
                self::responsiveSize(100, 64, 42),
                ['custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .2s forwards;}']
            )),
            $this->bodyText($c['contact_subtitle'] ?? 'Ready to bring the heat? Let\'s talk.', [
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .4s forwards;max-width:500px;}',
            ]),
        ]);

        // Contact info cards
        $contactInfo = $c['contact_info'] ?? [
            ['icon' => '📧', 'label' => 'Email', 'value' => $c['email'] ?? 'hello@example.com'],
            ['icon' => '📞', 'label' => 'Phone', 'value' => $c['phone'] ?? '(555) 123-4567'],
            ['icon' => '📍', 'label' => 'Location', 'value' => $c['address'] ?? '123 Business St, City, State'],
        ];

        $infoCards = [];
        foreach ($contactInfo as $i => $info) {
            $infoCards[] = self::container([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'width' => ['size' => 31, 'unit' => '%', 'sizes' => []],
                'width_tablet' => ['size' => 48, 'unit' => '%', 'sizes' => []],
                'width_mobile' => ['size' => 100, 'unit' => '%', 'sizes' => []],
                'padding' => self::pad(44, 28),
                'background_background' => 'classic',
                'background_color' => $this->colors()['surface'],
                'css_classes' => 'ember-bcard sr d' . ($i + 1),
            ], [
                self::textEditor('<span style="font-size:40px;display:block;margin-bottom:14px;">' . $info['icon'] . '</span>'),
                self::heading($info['label'], 'h4', [
                    'title_color' => $this->colors()['text'],
                    'align' => 'center',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['heading'],
                    'typography_font_size' => ['size' => 18, 'unit' => 'px'],
                    'typography_font_weight' => '800',
                    '_margin' => self::margin(0, 0, 8, 0),
                ]),
                self::textEditor('<p style="text-align:center;color:' . $this->colors()['muted'] . ';font-size:14px;">' . e($info['value']) . '</p>'),
            ]);
        }

        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['bg'],
        ], [
            self::container([
                'flex_direction' => 'row',
                'flex_direction_mobile' => 'column',
                'flex_wrap' => 'wrap',
                'content_width' => 'full',
                'flex_gap' => ['size' => 2, 'unit' => 'px', 'column' => '2', 'row' => '2'],
            ], $infoCards),
        ]);

        // Contact form
        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface2'],
            'flex_align_items' => 'center',
        ], [
            $this->eyebrow('Send a Message'),
            $this->headline('Drop Us A Line.', 'h2', [
                'align' => 'center',
                'typography_font_size' => ['size' => 48, 'unit' => 'px'],
                '_margin' => self::margin(0, 0, 40, 0),
            ]),
            self::html('<form style="max-width:600px;width:100%;margin:0 auto;display:flex;flex-direction:column;gap:16px;">
<input type="text" placeholder="Your Name" style="padding:16px 20px;background:' . $this->colors()['surface'] . ';border:1px solid ' . $this->colors()['border'] . ';color:' . $this->colors()['text'] . ';font-family:\'' . $this->fonts()['body'] . '\',sans-serif;font-size:14px;border-radius:2px;outline:none;transition:border-color .3s;" onfocus="this.style.borderColor=\'rgba(220,38,38,.4)\'" onblur="this.style.borderColor=\'' . $this->colors()['border'] . '\'">
<input type="email" placeholder="Your Email" style="padding:16px 20px;background:' . $this->colors()['surface'] . ';border:1px solid ' . $this->colors()['border'] . ';color:' . $this->colors()['text'] . ';font-family:\'' . $this->fonts()['body'] . '\',sans-serif;font-size:14px;border-radius:2px;outline:none;transition:border-color .3s;" onfocus="this.style.borderColor=\'rgba(220,38,38,.4)\'" onblur="this.style.borderColor=\'' . $this->colors()['border'] . '\'">
<textarea rows="5" placeholder="Your Message" style="padding:16px 20px;background:' . $this->colors()['surface'] . ';border:1px solid ' . $this->colors()['border'] . ';color:' . $this->colors()['text'] . ';font-family:\'' . $this->fonts()['body'] . '\',sans-serif;font-size:14px;border-radius:2px;outline:none;resize:vertical;transition:border-color .3s;" onfocus="this.style.borderColor=\'rgba(220,38,38,.4)\'" onblur="this.style.borderColor=\'' . $this->colors()['border'] . '\'"></textarea>
<button type="submit" style="padding:18px 44px;background:' . $this->colors()['primary'] . ';color:#FAFAF9;font-family:\'' . $this->fonts()['heading'] . '\',sans-serif;font-size:14px;font-weight:800;letter-spacing:3px;text-transform:uppercase;border:none;border-radius:2px;cursor:pointer;transition:all .3s;" onmouseover="this.style.background=\'' . $this->colors()['secondary'] . '\';this.style.boxShadow=\'0 12px 36px rgba(220,38,38,.4)\'" onmouseout="this.style.background=\'' . $this->colors()['primary'] . '\';this.style.boxShadow=\'none\'">Send Message</button>
</form>'),
        ]);

        return $sections;
    }

    // ═══════════════════════════════════════════════════════════
    // HEADER (HFE)
    // ═══════════════════════════════════════════════════════════

    public function buildHeader(string $siteName, array $pages): array
    {
        $c = $this->colors();
        $f = $this->fonts();

        $navLinks = '';
        foreach ($pages as $slug => $label) {
            $url = ($slug === 'home') ? '/' : '/' . $slug . '/';
            $navLinks .= '<li><a href="' . $url . '">' . e($label) . '</a></li>';
        }

        return [self::html('
<!-- Left accent stripe -->
<div class="ember-stripe" style="position:fixed;top:0;left:0;width:4px;height:100vh;background:' . $c['primary'] . ';z-index:1002;"></div>

<!-- Main nav -->
<nav class="ember-nav" id="mainNav" style="position:fixed;top:0;left:4px;right:0;z-index:1000;background:rgba(12,10,9,.6);backdrop-filter:blur(12px);transition:all .4s;">
<div class="ember-nav-inner" style="display:flex;align-items:center;justify-content:space-between;padding:0 56px 0 48px;height:80px;transition:height .3s;">
<a href="/" class="ember-logo" style="font-family:\'' . $f['heading'] . '\',sans-serif;font-size:22px;font-weight:900;letter-spacing:2px;color:' . $c['text'] . ';text-decoration:none;text-transform:uppercase;white-space:nowrap;">' . e($siteName) . '</a>
<ul class="ember-nav-links" style="display:flex;gap:0;list-style:none;padding:0;margin:0;position:absolute;left:50%;transform:translateX(-50%);">' . $navLinks . '</ul>
<div class="ember-nav-cta" style="display:flex;align-items:center;gap:12px;">
<a href="tel:+923001234567" class="ember-btn-ghost" style="padding:8px 18px;border:1px solid rgba(250,250,249,.25);color:' . $c['text'] . ';font-family:\'' . $f['heading'] . '\',sans-serif;font-size:11px;font-weight:700;letter-spacing:1px;text-transform:uppercase;border-radius:2px;text-decoration:none;transition:all .3s;white-space:nowrap;">&#x1F4DE; Call Now</a>
<a href="/contact/" class="ember-btn-reserve" style="padding:9px 22px;background:' . $c['primary'] . ';color:#FAFAF9;font-family:\'' . $f['heading'] . '\',sans-serif;font-size:11px;font-weight:800;letter-spacing:1.5px;text-transform:uppercase;border-radius:2px;text-decoration:none;transition:all .3s;white-space:nowrap;">Reserve a Table</a>
</div>
</div>
<!-- Info strip below nav -->
<div class="ember-info-strip" id="emberInfoStrip" style="text-align:center;padding:6px 20px;border-top:1px solid ' . $c['border'] . ';transition:all .4s;overflow:hidden;max-height:40px;opacity:1;">
<span style="font-family:\'' . $f['body'] . '\',sans-serif;font-size:12px;color:rgba(250,250,249,.35);letter-spacing:0.5px;">&#x1F525; Open Daily: 11AM – 11PM &nbsp;|&nbsp; &#x1F4CD; Lahore, Pakistan</span>
</div>
</nav>

<style>
.ember-nav-links a{display:block;padding:8px 20px;font-size:12px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:rgba(250,250,249,.45);text-decoration:none;transition:color .3s;position:relative;}
.ember-nav-links a::after{content:\'\';position:absolute;bottom:2px;left:20px;right:20px;height:2px;background:' . $c['primary'] . ';transform:scaleX(0);transform-origin:center;transition:transform .3s;}
.ember-nav-links a:hover{color:#FAFAF9;}
.ember-nav-links a:hover::after{transform:scaleX(1);}
.ember-btn-ghost:hover{border-color:' . $c['primary'] . '!important;color:' . $c['primary'] . '!important;}
.ember-btn-reserve:hover{background:' . $c['secondary'] . '!important;box-shadow:0 4px 18px rgba(220,38,38,.35);}
.ember-nav.scrolled{background:rgba(12,10,9,.97)!important;backdrop-filter:blur(24px);}
.ember-nav.scrolled .ember-nav-inner{height:62px!important;}
.ember-nav.scrolled .ember-info-strip{max-height:0!important;opacity:0!important;padding-top:0!important;padding-bottom:0!important;border:none!important;}
@media(max-width:1100px){
.ember-nav-links{display:none!important;}
.ember-nav{left:0!important;}
.ember-stripe{display:none!important;}
.ember-nav-inner{padding:0 20px!important;height:64px!important;}
.ember-info-strip{display:none!important;}
.ember-btn-ghost{display:none!important;}
.ember-nav.scrolled .ember-nav-inner{height:58px!important;}
}
</style>
<script>window.addEventListener("scroll",()=>{document.getElementById("mainNav").classList.toggle("scrolled",scrollY>50);});</script>')];
    }

    // ═══════════════════════════════════════════════════════════
    // FOOTER (HFE)
    // ═══════════════════════════════════════════════════════════

    public function buildFooter(string $siteName, array $pages, array $contact): array
    {
        $c = $this->colors();
        $f = $this->fonts();

        $navLinks = '';
        foreach ($pages as $slug => $label) {
            $url = ($slug === 'home') ? '/' : '/' . $slug . '/';
            $navLinks .= '<li><a href="' . $url . '">' . e($label) . '</a></li>';
        }

        $email = $contact['email'] ?? 'hello@example.com';
        $phone = $contact['phone'] ?? '';
        $address = $contact['address'] ?? '';

        $sections = [];

        // ── TOP CTA STRIP ─────────────────────────────────
        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'row',
            'flex_justify_content' => 'center',
            'flex_align_items' => 'center',
            'flex_gap' => ['size' => 32, 'unit' => 'px', 'column' => '32', 'row' => '20'],
            'padding' => self::pad(40, 64),
            'padding_mobile' => self::pad(32, 24),
            'background_background' => 'classic',
            'background_color' => $c['primary'],
            'flex_direction_mobile' => 'column',
        ], [
            self::heading('Ready for an Unforgettable Dining Experience?', 'h3', [
                'title_color' => '#FAFAF9',
                'typography_typography' => 'custom',
                'typography_font_family' => $f['heading'],
                'typography_font_size' => ['size' => 22, 'unit' => 'px', 'sizes' => []],
                'typography_font_size_mobile' => ['size' => 18, 'unit' => 'px', 'sizes' => []],
                'typography_font_weight' => '800',
                'typography_letter_spacing' => ['size' => 0.5, 'unit' => 'px', 'sizes' => []],
            ]),
            self::button('Reserve Your Table', '/contact/', [
                'button_type' => 'default',
                'background_color' => '#FFFFFF',
                'button_text_color' => $c['primary'],
                'typography_typography' => 'custom',
                'typography_font_family' => $f['heading'],
                'typography_font_size' => ['size' => 12, 'unit' => 'px', 'sizes' => []],
                'typography_font_weight' => '800',
                'typography_letter_spacing' => ['size' => 1.5, 'unit' => 'px', 'sizes' => []],
                'typography_text_transform' => 'uppercase',
                'border_radius' => self::pad(2),
                'button_hover_color' => '#FFFFFF',
                'button_background_hover_color' => $c['bg'],
            ]),
        ]);

        // ── MAIN FOOTER (4 columns) ──────────────────────
        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'column',
            'padding' => self::pad(0),
            'background_background' => 'classic',
            'background_color' => $c['surface2'],
        ], [
            // 4-column grid row
            self::container([
                'flex_direction' => 'row',
                'content_width' => 'full',
                'flex_gap' => ['size' => 48, 'unit' => 'px', 'column' => '48', 'row' => '40'],
                'padding' => self::pad(72, 64, 56, 64),
                'padding_mobile' => self::pad(48, 24, 40, 24),
                'border_border' => 'solid',
                'border_width' => self::pad(0, 0, 1, 0),
                'border_color' => $c['border'],
                'custom_css' => 'selector{display:grid;grid-template-columns:1.6fr 1fr 1fr 1.2fr;}',
                'flex_direction_mobile' => 'column',
            ], [
                // Col 1: Brand
                self::html('<div>
<div style="font-family:\'' . $f['heading'] . '\',sans-serif;font-size:22px;font-weight:900;letter-spacing:2px;color:' . $c['text'] . ';text-transform:uppercase;margin-bottom:6px;">' . e($siteName) . '</div>
<div style="font-size:10px;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:' . $c['accent'] . ';margin-bottom:18px;">Authentic Cuisine</div>
<p style="font-family:\'' . $f['body'] . '\',sans-serif;font-size:13.5px;color:rgba(250,250,249,.35);line-height:1.85;max-width:260px;margin:0;">' . e($contact['footer_text'] ?? 'Where every dish tells a story. Crafted with passion, served with fire.') . '</p>
</div>'),

                // Col 2: Opening Hours
                self::html('<div class="ember-fc">
<h4 style="font-family:\'' . $f['heading'] . '\',sans-serif;font-size:13px;font-weight:800;letter-spacing:2px;text-transform:uppercase;color:' . $c['primary'] . ';margin:0 0 20px;">Opening Hours</h4>
<ul class="ember-hours" style="list-style:none;padding:0;margin:0;">
<li><span class="ember-day">Mon – Thu</span><span class="ember-time">11AM – 10PM</span></li>
<li><span class="ember-day">Fri – Sat</span><span class="ember-time">11AM – 11PM</span></li>
<li><span class="ember-day">Sunday</span><span class="ember-time">12PM – 9PM</span></li>
</ul>
</div>'),

                // Col 3: Quick Links
                self::html('<div class="ember-fc">
<h4 style="font-family:\'' . $f['heading'] . '\',sans-serif;font-size:13px;font-weight:800;letter-spacing:2px;text-transform:uppercase;color:' . $c['primary'] . ';margin:0 0 20px;">Quick Links</h4>
<ul class="ember-flinks">' . $navLinks . '</ul>
</div>'),

                // Col 4: Find Us
                self::html('<div class="ember-fc">
<h4 style="font-family:\'' . $f['heading'] . '\',sans-serif;font-size:13px;font-weight:800;letter-spacing:2px;text-transform:uppercase;color:' . $c['primary'] . ';margin:0 0 20px;">Find Us</h4>
<div class="ember-find-list">'
. ($address ? '<div class="ember-find-item"><span class="ember-find-icon">&#x1F4CD;</span><span>' . e($address) . '</span></div>' : '')
. ($phone ? '<div class="ember-find-item"><span class="ember-find-icon">&#x1F4DE;</span><span>' . e($phone) . '</span></div>' : '')
. '<div class="ember-find-item"><span class="ember-find-icon">&#x1F4E7;</span><span>' . e($email) . '</span></div>
</div>
</div>'),
            ]),

            // ── BOTTOM BAR: copyright + social ──────────
            self::container([
                'flex_direction' => 'row',
                'flex_justify_content' => 'space-between',
                'flex_align_items' => 'center',
                'content_width' => 'full',
                'padding' => self::pad(20, 64),
                'padding_mobile' => self::pad(18, 24),
                'flex_direction_mobile' => 'column',
                'flex_gap' => ['size' => 12, 'unit' => 'px', 'column' => '12', 'row' => '12'],
            ], [
                self::textEditor('<p style="font-size:12px;color:rgba(250,250,249,.2);margin:0;">&copy; ' . date('Y') . ' <span style="color:' . $c['primary'] . ';">' . e($siteName) . '</span>. All rights reserved.</p>'),
                self::html('<div class="ember-social-bottom"><a href="#" title="Instagram">&#x1F4F7;</a><a href="#" title="Twitter">&#x1F426;</a><a href="#" title="LinkedIn">&#x1F4BC;</a></div>'),
            ]),
        ]);

        // ── FOOTER STYLES ─────────────────────────────────
        $sections[] = self::html('<style>
.ember-hours li{display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid ' . $c['border'] . ';font-family:\'' . $f['body'] . '\',sans-serif;}
.ember-hours li:last-child{border-bottom:none;}
.ember-day{font-size:13px;color:rgba(250,250,249,.5);}
.ember-time{font-size:13px;color:' . $c['text'] . ';font-weight:600;}
.ember-flinks{list-style:none;padding:0;margin:0;}
.ember-flinks li{margin-bottom:0;}
.ember-flinks a{display:block;padding:7px 0;font-family:\'' . $f['body'] . '\',sans-serif;font-size:13.5px;color:rgba(250,250,249,.4);text-decoration:none;transition:color .3s,padding-left .3s;}
.ember-flinks a:hover{color:' . $c['text'] . ';padding-left:6px;}
.ember-find-list{display:flex;flex-direction:column;gap:14px;}
.ember-find-item{display:flex;align-items:flex-start;gap:10px;font-family:\'' . $f['body'] . '\',sans-serif;font-size:13.5px;color:rgba(250,250,249,.4);line-height:1.6;}
.ember-find-icon{font-size:16px;flex-shrink:0;margin-top:1px;}
.ember-social-bottom{display:flex;gap:16px;}
.ember-social-bottom a{font-size:18px;text-decoration:none;opacity:.3;transition:opacity .3s;}
.ember-social-bottom a:hover{opacity:1;}
@media(max-width:767px){
.ember-fc h4{margin-bottom:14px!important;}
}
</style>');

        return $sections;
    }
}
