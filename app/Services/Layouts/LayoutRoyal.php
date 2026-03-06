<?php

namespace App\Services\Layouts;

/**
 * Royal — Luxurious dark purple layout with glass effects.
 * Deep purple backgrounds, glass cards with backdrop-filter blur,
 * purple/violet gradient accents, custom cursor, scroll-reveal,
 * gradient text on hero, premium hotel/luxury aesthetic.
 */
class LayoutRoyal extends AbstractLayout
{
    public function slug(): string { return 'royal'; }
    public function name(): string { return 'Royal'; }
    public function description(): string { return 'Luxurious dark purple theme with glass effects for premium brands'; }
    public function bestFor(): array { return ['Hospitality', 'Hotel', 'Luxury Retail', 'Jewelry']; }
    public function isDark(): bool { return true; }

    public function colors(): array
    {
        return [
            'primary'   => '#7C3AED',
            'secondary' => '#6D28D9',
            'accent'    => '#A78BFA',
            'bg'        => '#0C0A1A',
            'surface'   => '#16132A',
            'surface2'  => '#1E1B36',
            'text'      => '#F5F3FF',
            'muted'     => 'rgba(245,243,255,0.5)',
            'border'    => 'rgba(245,243,255,0.08)',
        ];
    }

    public function fonts(): array
    {
        return ['heading' => 'DM Sans', 'body' => 'Inter'];
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
<link href="https://fonts.googleapis.com/css2?family={$hf}:wght@300;400;500;600;700;900&family={$bf}:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{--royal-bg:{$c['bg']};--royal-surface:{$c['surface']};--royal-surface2:{$c['surface2']};--royal-text:{$c['text']};--royal-muted:{$c['muted']};--royal-border:{$c['border']};--royal-primary:{$c['primary']};--royal-secondary:{$c['secondary']};--royal-accent:{$c['accent']};}
body,body.elementor-template-canvas{background:var(--royal-bg);color:var(--royal-text);font-family:'{$f['body']}',sans-serif;overflow-x:hidden;margin:0;padding:0;}
.elementor-element,.elementor.elementor-2{font-family:'{$f['body']}',sans-serif;}
.elementor-widget{margin-bottom:0 !important;}
.e-con{--gap:0px;}
.e-con>.elementor-widget{width:100%;}

/* Custom Cursor (purple dot) */
#royal-cr{width:12px;height:12px;background:var(--royal-primary);border-radius:50%;position:fixed;top:0;left:0;z-index:99999;pointer-events:none;mix-blend-mode:screen;transition:transform .15s;box-shadow:0 0 20px rgba(124,58,237,.6);}
#royal-cr2{width:40px;height:40px;border:1px solid rgba(124,58,237,.4);border-radius:50%;position:fixed;top:0;left:0;z-index:99998;pointer-events:none;}

/* Animations */
@keyframes fadeUp{from{opacity:0;transform:translateY(28px)}to{opacity:1;transform:translateY(0)}}
@keyframes fadeIn{from{opacity:0}to{opacity:1}}
@keyframes roll{from{transform:translateX(0)}to{transform:translateX(-50%)}}
@keyframes shimmer{0%{background-position:200% center}100%{background-position:-200% center}}
@keyframes pulseGlow{0%,100%{box-shadow:0 0 20px rgba(124,58,237,.2)}50%{box-shadow:0 0 40px rgba(124,58,237,.5)}}
.sr{opacity:0;transform:translateY(36px);transition:opacity .85s ease,transform .85s ease;}
.sr.d1{transition-delay:.1s}.sr.d2{transition-delay:.2s}.sr.d3{transition-delay:.3s}.sr.d4{transition-delay:.45s}
.sr.in{opacity:1;transform:none;}

/* Gradient Text */
.royal-gradient-text{background:linear-gradient(135deg,#7C3AED 0%,#A78BFA 40%,#EC4899 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}

/* Eyebrow */
.eyebrow{display:inline-flex;align-items:center;gap:12px;font-size:11px;font-weight:600;letter-spacing:4px;text-transform:uppercase;color:var(--royal-accent);margin-bottom:20px;}
.eyebrow::before{content:'';width:28px;height:1px;background:linear-gradient(90deg,var(--royal-primary),var(--royal-accent));}

/* Ticker */
.royal-ticker{overflow:hidden;white-space:nowrap;padding:14px 0;background:linear-gradient(90deg,var(--royal-primary),var(--royal-secondary),#EC4899);}
.royal-ticker-inner{display:inline-flex;animation:roll 28s linear infinite;}
.royal-ticker-inner span{font-family:'{$f['heading']}',sans-serif;font-size:14px;font-weight:700;letter-spacing:4px;text-transform:uppercase;color:rgba(255,255,255,.9);padding:0 40px;}
.royal-ticker-inner .dot{color:rgba(255,255,255,.3)!important;padding:0 4px!important;}

/* Glass Card */
.royal-glass{background:rgba(22,19,42,.6);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border:1px solid rgba(124,58,237,.12);border-radius:16px;transition:all .4s ease;}
.royal-glass:hover{background:rgba(30,27,54,.8);border-color:rgba(124,58,237,.25);transform:translateY(-6px);box-shadow:0 20px 60px rgba(124,58,237,.15);}

/* Service Card */
.royal-card{position:relative;overflow:hidden;background:rgba(22,19,42,.5);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border:1px solid rgba(124,58,237,.1);border-radius:16px;transition:all .4s ease;}
.royal-card::before{content:'';position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,var(--royal-primary),var(--royal-accent),#EC4899);transform:scaleX(0);transform-origin:left;transition:transform .5s;}
.royal-card:hover{background:rgba(30,27,54,.7);border-color:rgba(124,58,237,.25);transform:translateY(-4px);box-shadow:0 24px 60px rgba(124,58,237,.2);}
.royal-card:hover::before{transform:scaleX(1);}

/* Benefit Card */
.royal-bcard{text-align:center;background:rgba(22,19,42,.4);backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);border:1px solid rgba(124,58,237,.08);border-radius:16px;transition:all .4s ease;position:relative;overflow:hidden;}
.royal-bcard::after{content:'';position:absolute;inset:0;background:radial-gradient(circle at 50% 0%,rgba(124,58,237,.15),transparent 70%);opacity:0;transition:opacity .4s;}
.royal-bcard:hover{background:rgba(30,27,54,.6);border-color:rgba(167,139,250,.2);transform:translateY(-6px);}
.royal-bcard:hover::after{opacity:1;}

/* Testimonial Card */
.royal-tcard{border:1px solid var(--royal-border);border-radius:16px;background:rgba(22,19,42,.4);backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);transition:all .4s ease;}
.royal-tcard:hover{border-color:rgba(124,58,237,.3);transform:translateY(-5px);box-shadow:0 20px 50px rgba(124,58,237,.15);}
.royal-tcard-feat{border-color:var(--royal-primary)!important;background:linear-gradient(135deg,rgba(124,58,237,.2),rgba(167,139,250,.1))!important;}
.royal-tcard-feat:hover{transform:translateY(-8px)!important;box-shadow:0 28px 70px rgba(124,58,237,.4);}

/* Stats */
.royal-stat{text-align:center;}
.royal-stat-n{font-family:'{$f['heading']}',sans-serif;font-size:56px;font-weight:900;background:linear-gradient(135deg,var(--royal-primary),var(--royal-accent));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;line-height:1;text-shadow:none;}
.royal-stat-l{font-size:10px;letter-spacing:2px;text-transform:uppercase;color:rgba(245,243,255,.3);margin-top:5px;}

/* CTA pill badge */
.royal-badge{display:inline-flex;align-items:center;gap:5px;margin-top:14px;padding:5px 12px;border-radius:50px;font-size:10px;font-weight:600;letter-spacing:1.5px;text-transform:uppercase;background:rgba(124,58,237,.1);border:1px solid rgba(124,58,237,.25);color:var(--royal-accent);}

/* Stars */
.royal-stars{color:var(--royal-accent);letter-spacing:2px;font-size:14px;margin-bottom:16px;}

/* Credential block */
.royal-cred{background:rgba(124,58,237,.06);border:1px solid rgba(124,58,237,.18);border-radius:12px;padding:24px 22px;margin:28px 0;}
.royal-cred h4{font-family:'{$f['heading']}',sans-serif;font-size:13px;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:var(--royal-accent);margin-bottom:14px;}
.royal-cred-item{display:flex;align-items:flex-start;gap:10px;font-size:14px;color:rgba(245,243,255,.6);line-height:1.5;margin-bottom:9px;}
.royal-cred-item .star{color:var(--royal-accent);flex-shrink:0;font-size:12px;margin-top:2px;}

/* Pillar rows */
.royal-pillar{display:flex;align-items:center;gap:20px;padding:22px 24px;border-bottom:1px solid var(--royal-border);transition:all .3s;cursor:pointer;}
.royal-pillar:last-child{border-bottom:none;}
.royal-pillar:hover{background:rgba(124,58,237,.06);padding-left:32px;}
.royal-pillar-ico{width:44px;height:44px;min-width:44px;background:rgba(124,58,237,.1);border:1px solid rgba(124,58,237,.2);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;}
.royal-pillar h4{font-family:'{$f['heading']}',sans-serif;font-size:17px;font-weight:700;letter-spacing:.5px;color:var(--royal-text);margin-bottom:3px;}
.royal-pillar p{font-size:13px;color:rgba(245,243,255,.4);line-height:1.5;margin:0;}

/* Gallery photo hover */
.royal-photo{overflow:hidden;position:relative;border-radius:12px;}
.royal-photo img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .7s ease;}
.royal-photo:hover img{transform:scale(1.06);}
.royal-pho{position:absolute;inset:0;background:linear-gradient(to top,rgba(12,10,26,.7) 0%,transparent 50%);opacity:0;transition:opacity .4s;}
.royal-photo:hover .royal-pho{opacity:1;}

/* Footer */
.royal-fc h4{font-size:10px;font-weight:600;letter-spacing:4px;text-transform:uppercase;color:rgba(245,243,255,.4);margin-bottom:22px;padding-bottom:14px;border-bottom:1px solid var(--royal-border);}
.royal-fc ul{list-style:none;display:flex;flex-direction:column;gap:11px;padding:0;margin:0;}
.royal-fc a{font-size:14px;color:rgba(245,243,255,.35);text-decoration:none;display:flex;align-items:center;gap:8px;transition:color .3s;}
.royal-fc a::before{content:'\203A';color:rgba(124,58,237,.4);transition:color .3s;}
.royal-fc a:hover{color:rgba(245,243,255,.75);}
.royal-fc a:hover::before{color:var(--royal-primary);}
.royal-social{display:flex;gap:8px;margin-top:24px;}
.royal-social a{width:38px;height:38px;border:1px solid var(--royal-border);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:16px;text-decoration:none;color:rgba(245,243,255,.3);transition:all .3s;}
.royal-social a:hover{border-color:var(--royal-primary);color:var(--royal-accent);background:rgba(124,58,237,.08);transform:translateY(-3px);}

/* Contact info block */
.royal-ci{display:flex;align-items:flex-start;gap:10px;margin-bottom:14px;}
.royal-ci-i{width:34px;height:34px;min-width:34px;background:rgba(124,58,237,.08);border:1px solid rgba(124,58,237,.15);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:14px;}
.royal-ci small{display:block;font-size:9px;color:rgba(245,243,255,.25);letter-spacing:2px;text-transform:uppercase;margin-bottom:2px;}
.royal-ci span{font-size:13px;color:rgba(245,243,255,.45);}

/* Back to top */
#royal-btt{position:fixed;bottom:28px;right:28px;width:46px;height:46px;background:linear-gradient(135deg,var(--royal-primary),var(--royal-secondary));color:#FFF;border-radius:12px;font-size:20px;display:flex;align-items:center;justify-content:center;text-decoration:none;z-index:500;opacity:0;transform:translateY(12px);pointer-events:none;transition:all .4s;box-shadow:0 8px 24px rgba(124,58,237,.4);}
#royal-btt.show{opacity:1;transform:translateY(0);pointer-events:all;}
#royal-btt:hover{transform:translateY(-4px)!important;box-shadow:0 12px 32px rgba(124,58,237,.6);}

/* Step style */
.royal-step{border-bottom:1px solid var(--royal-border);transition:all .3s;}
.royal-step:first-child{border-top:1px solid var(--royal-border);}

/* Pricing */
.royal-plan{border-radius:16px;overflow:hidden;box-shadow:0 24px 64px rgba(124,58,237,.25);background:linear-gradient(135deg,rgba(124,58,237,.15),rgba(167,139,250,.1));backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border:1px solid rgba(124,58,237,.2);}
.sp-price{display:flex;align-items:flex-start;justify-content:center;gap:4px;margin-bottom:10px;}
.sp-cur{font-size:26px;font-weight:700;color:var(--royal-accent);margin-top:14px;}
.sp-num{font-family:'{$f['heading']}',sans-serif;font-size:108px;font-weight:900;line-height:1;background:linear-gradient(135deg,var(--royal-primary),var(--royal-accent));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;letter-spacing:-4px;}
.sp-mo{font-size:16px;color:rgba(245,243,255,.4);align-self:flex-end;padding-bottom:14px;}
.sp-features{list-style:none;display:grid;grid-template-columns:1fr 1fr;gap:14px 40px;margin:0 0 40px 0;padding:0;}
.sp-features li{display:flex;align-items:center;gap:10px;font-size:15px;color:rgba(245,243,255,.65);}
.sp-chk{width:22px;height:22px;min-width:22px;border-radius:50%;background:rgba(124,58,237,.15);display:flex;align-items:center;justify-content:center;font-size:11px;color:var(--royal-accent);}

/* Responsive */
@media(max-width:1100px){.royal-photo{min-height:200px;}.royal-ticker-inner span{font-size:12px;padding:0 24px;}}
@media(max-width:700px){.sp-features{grid-template-columns:1fr;}}
</style>
CSS;
    }

    public function buildGlobalJs(): string
    {
        return <<<'JS'
<script>
(function(){
const cr=document.createElement('div'),cr2=document.createElement('div');
cr.id='royal-cr';cr2.id='royal-cr2';document.body.appendChild(cr);document.body.appendChild(cr2);
let mx=0,my=0,tx=0,ty=0;
document.addEventListener("mousemove",e=>{mx=e.clientX;my=e.clientY;cr.style.left=(mx-6)+"px";cr.style.top=(my-6)+"px";});
(function loop(){tx+=(mx-tx-20)*.13;ty+=(my-ty-20)*.13;cr2.style.left=tx+"px";cr2.style.top=ty+"px";requestAnimationFrame(loop);})();
document.querySelectorAll("a,button").forEach(el=>{el.addEventListener("mouseenter",()=>{cr.style.transform="scale(2.5)";cr.style.opacity=".5";});el.addEventListener("mouseleave",()=>{cr.style.transform="scale(1)";cr.style.opacity="1";});});
const btt=document.getElementById('royal-btt');
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
        $heroTitle = $c['hero_title'] ?? 'Exquisite Luxury. Redefined.';
        $heroSub = $c['hero_subtitle'] ?? 'Experience unparalleled elegance and sophistication with our premium services tailored for discerning clientele.';
        $heroCta = $c['hero_cta'] ?? 'Discover More';
        $heroCtaUrl = $c['hero_cta_url'] ?? '#services';

        $aboutTitle = $c['about_title'] ?? 'Our Heritage';
        $aboutText = $c['about_text'] ?? 'A legacy of excellence built on decades of unwavering commitment to luxury and refinement.';
        $aboutText2 = $c['about_text2'] ?? 'Every detail is meticulously crafted to exceed the expectations of our distinguished guests.';

        $services = $c['services'] ?? [
            ['icon' => "\u{2728}", 'title' => 'Premium Experience', 'desc' => 'Immersive luxury experiences designed to captivate and delight every sense.'],
            ['icon' => "\u{1F451}", 'title' => 'Bespoke Services', 'desc' => 'Tailored solutions crafted exclusively for your unique preferences and desires.'],
            ['icon' => "\u{1F48E}", 'title' => 'Elite Collection', 'desc' => 'Curated selections of the finest offerings from around the world.'],
        ];

        $benefits = $c['benefits'] ?? [
            ['icon' => "\u{1F451}", 'title' => 'World Class', 'desc' => 'Internationally recognized for setting the highest standards of luxury.'],
            ['icon' => "\u{2728}", 'title' => 'Personalized', 'desc' => 'Every experience tailored to your individual taste and preference.'],
            ['icon' => "\u{1F48E}", 'title' => 'Exclusive Access', 'desc' => 'Members-only privileges and priority access to our finest offerings.'],
            ['icon' => "\u{1F31F}", 'title' => 'Timeless Quality', 'desc' => 'Craftsmanship that stands the test of time with enduring elegance.'],
        ];

        $testimonials = $c['testimonials'] ?? [
            ['quote' => 'An extraordinary experience from start to finish. The attention to detail and level of service was truly exceptional.', 'name' => 'Victoria E.', 'role' => 'VIP Guest', 'initials' => 'VE'],
            ['quote' => 'Unmatched luxury and sophistication. This is the gold standard for premium service delivery.', 'name' => 'Alexander P.', 'role' => 'Private Client', 'initials' => 'AP'],
            ['quote' => 'Every visit exceeds my expectations. The team understands true luxury at its finest.', 'name' => 'Isabella M.', 'role' => 'Loyal Member', 'initials' => 'IM'],
        ];

        $stats = $c['stats'] ?? [
            ['number' => '500', 'suffix' => '+', 'label' => 'Distinguished Guests'],
            ['number' => '98', 'suffix' => '%', 'label' => 'Guest Satisfaction'],
            ['number' => '25', 'suffix' => '+', 'label' => 'Years of Excellence'],
        ];

        $tickerItems = $c['ticker_items'] ?? [$siteName, $heroCta, 'Premium Luxury', 'Exclusive Service', 'Timeless Elegance'];

        $heroImg = $img['hero'] ?? '';
        $aboutImg = $img['about'] ?? '';
        $galleryImgs = array_filter([
            $img['gallery1'] ?? '',
            $img['gallery2'] ?? '',
            $img['services'] ?? '',
        ]);

        $sections = [];

        // --- HERO ---
        $sections[] = self::container([
            'content_width' => 'full-width',
            'flex_direction' => 'column',
            'flex_justify_content' => 'flex-end',
            'padding' => self::pad(0, 64, 100, 64),
            'min_height' => ['size' => 100, 'unit' => 'vh'],
            'background_background' => 'classic',
            'background_image' => ['url' => $heroImg, 'id' => ''],
            'background_position' => 'center center',
            'background_size' => 'cover',
            '_element_id' => 'hero',
            'custom_css' => "selector{position:relative;overflow:hidden;}
selector::before{content:'';position:absolute;inset:0;background:linear-gradient(to right,rgba(12,10,26,.9) 40%,rgba(12,10,26,.2) 100%),linear-gradient(to top,rgba(12,10,26,.95) 0%,rgba(12,10,26,.3) 50%,transparent 100%);z-index:0;}
selector>.e-con-inner,selector>.elementor-widget{position:relative;z-index:2;}",
        ], [
            self::html('<div style="display:inline-flex;align-items:center;gap:10px;margin-bottom:28px;opacity:0;animation:fadeUp .7s ease .2s forwards;"><div style="width:8px;height:8px;background:var(--royal-primary);border-radius:50%;box-shadow:0 0 12px rgba(124,58,237,.6);"></div><span class="eyebrow" style="margin-bottom:0;">' . e($c['hero_eyebrow'] ?? 'Welcome to ' . $siteName) . '</span></div>'),

            self::html('<h1 style="font-family:\'' . $this->fonts()['heading'] . '\',sans-serif;font-size:clamp(52px,8vw,120px);font-weight:900;line-height:0.95;text-transform:uppercase;letter-spacing:-1px;margin:0 0 32px 0;max-width:800px;opacity:0;animation:fadeUp .9s ease .35s forwards;"><span class="royal-gradient-text">' . e($heroTitle) . '</span></h1>'),

            self::textEditor('<p>' . e($heroSub) . '</p>', [
                'text_color' => 'rgba(245,243,255,0.55)',
                'typography_typography' => 'custom',
                'typography_font_family' => $this->fonts()['body'],
                'typography_font_size' => ['size' => 17, 'unit' => 'px'],
                'typography_line_height' => ['size' => 1.75, 'unit' => 'em'],
                'typography_font_weight' => '300',
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .55s forwards;max-width:480px;}',
            ]),

            self::container([
                'flex_direction' => 'row',
                'flex_align_items' => 'center',
                'gap' => ['size' => 16, 'unit' => 'px'],
                'content_width' => 'full-width',
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .7s forwards;}',
            ], [
                $this->ctaButton($heroCta, $heroCtaUrl, [
                    'background_color' => 'transparent',
                    'border_border' => 'none',
                    'custom_css' => 'selector .elementor-button{background:linear-gradient(135deg,#7C3AED,#6D28D9)!important;border-radius:12px!important;} selector .elementor-button:hover{transform:translateY(-3px);box-shadow:0 20px 50px rgba(124,58,237,.45);}',
                ]),
                $this->ghostButton($c['hero_ghost_cta'] ?? 'Learn More', '#about', [
                    'border_color' => 'rgba(245,243,255,0.15)',
                    'button_text_color' => 'rgba(245,243,255,0.7)',
                    'border_radius' => self::radius(12),
                ]),
            ]),
        ]);

        // --- TICKER ---
        $tickerHtml = '';
        foreach (array_merge($tickerItems, $tickerItems) as $item) {
            $tickerHtml .= '<span>' . e($item) . '</span><span class="dot">&diams;</span>';
        }
        $sections[] = self::container([
            'content_width' => 'full-width',
            'padding' => self::pad(0),
        ], [
            self::html('<div class="royal-ticker"><div class="royal-ticker-inner">' . $tickerHtml . '</div></div>'),
        ]);

        // --- ABOUT PREVIEW ---
        $credItems = '';
        foreach ($c['credentials'] ?? [
            'Award-winning luxury brand with global recognition',
            'Handpicked team of world-class professionals',
            'Trusted by elite clientele worldwide',
        ] as $cred) {
            $credItems .= '<div class="royal-cred-item"><span class="star">&diams;</span><span>' . e($cred) . '</span></div>';
        }

        $pillarItems = '';
        foreach ($c['pillars'] ?? [
            ['icon' => "\u{1F451}", 'title' => 'Royal Treatment', 'desc' => 'White-glove service for every guest.'],
            ['icon' => "\u{2728}", 'title' => 'Curated Excellence', 'desc' => 'Only the finest materials and experiences.'],
            ['icon' => "\u{1F48E}", 'title' => 'Lasting Impressions', 'desc' => 'Memories that transcend the ordinary.'],
        ] as $pillar) {
            $pillarItems .= '<div class="royal-pillar"><div class="royal-pillar-ico">' . $pillar['icon'] . '</div><div><h4>' . e($pillar['title']) . '</h4><p>' . e($pillar['desc']) . '</p></div></div>';
        }

        $sections[] = self::twoCol(
            [self::image($aboutImg, [
                'custom_css' => 'selector img{width:100%;height:100%;min-height:400px;object-fit:cover;border-radius:16px;transition:transform 8s ease;} selector:hover img{transform:scale(1.04);}',
            ])],
            [
                $this->eyebrow($c['about_eyebrow'] ?? 'About Us'),
                $this->headline($aboutTitle, 'h2', ['_margin' => self::margin(0, 0, 8, 0)]),
                $this->bodyText($aboutText),
                $this->bodyText($aboutText2),
                self::html('<div class="royal-cred sr d2"><h4>' . e($c['cred_title'] ?? "\u{1F451} Why Choose Us") . '</h4>' . $credItems . '</div>'),
                self::html('<div class="sr d3" style="border:1px solid var(--royal-border);border-radius:12px;overflow:hidden;">' . $pillarItems . '</div>'),
            ],
            50,
            ['padding' => self::pad(0), '_element_id' => 'about'],
            ['padding' => self::pad(0)],
            [
                'padding' => self::pad(80, 72, 80, 60),
                'background_background' => 'classic',
                'background_color' => $this->colors()['surface'],
                'flex_justify_content' => 'center',
                'border_radius' => self::radius(0),
            ]
        );

        // --- SERVICES (Glass Cards) ---
        $serviceCards = [];
        foreach ($services as $i => $svc) {
            $serviceCards[] = self::container([
                'content_width' => 'full-width',
                'flex_direction' => 'column',
                'width' => ['size' => 33.33, 'unit' => '%'],
                'padding' => self::pad(40, 32),
                'css_classes' => 'royal-card sr d' . ($i + 1),
            ], [
                self::textEditor('<span style="font-size:36px;display:block;margin-bottom:18px;">' . ($svc['icon'] ?? "\u{2728}") . '</span>'),
                self::heading($svc['title'], 'h3', [
                    'title_color' => $this->colors()['text'],
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['heading'],
                    'typography_font_size' => ['size' => 24, 'unit' => 'px'],
                    'typography_font_weight' => '700',
                    'typography_letter_spacing' => ['size' => 0.5, 'unit' => 'px'],
                    '_margin' => self::margin(0, 0, 12, 0),
                ]),
                $this->bodyText($svc['desc'], ['typography_font_size' => ['size' => 14, 'unit' => 'px']]),
            ]);
        }

        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface2'],
        ], [
            $this->eyebrow($c['services_eyebrow'] ?? 'Our Services'),
            $this->headline($c['services_title'] ?? 'What We Offer.', 'h2'),
            $this->bodyText($c['services_subtitle'] ?? 'Premium solutions crafted for the discerning.', [
                '_margin' => self::margin(0),
                'custom_css' => 'selector{max-width:560px;}',
            ]),
            self::container([
                'flex_direction' => 'row',
                'content_width' => 'full-width',
                'gap' => ['size' => 24, 'unit' => 'px'],
                '_margin' => self::margin(56, 0, 0, 0),
            ], $serviceCards),
        ], 'services');

        // --- BENEFITS (Glass bcard style) ---
        $benefitCards = [];
        foreach ($benefits as $i => $b) {
            $benefitCards[] = self::container([
                'content_width' => 'full-width',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'width' => ['size' => 25, 'unit' => '%'],
                'padding' => self::pad(38, 28),
                'css_classes' => 'royal-bcard sr d' . ($i + 1),
            ], [
                self::textEditor('<span style="font-size:38px;display:block;margin-bottom:16px;">' . ($b['icon'] ?? "\u{2728}") . '</span>'),
                self::heading($b['title'], 'h3', [
                    'title_color' => $this->colors()['text'],
                    'align' => 'center',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['heading'],
                    'typography_font_size' => ['size' => 20, 'unit' => 'px'],
                    'typography_font_weight' => '700',
                    '_margin' => self::margin(0, 0, 10, 0),
                ]),
                $this->bodyText($b['desc'], [
                    'align' => 'center',
                    'typography_font_size' => ['size' => 13.5, 'unit' => 'px'],
                ]),
            ]);
        }

        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['bg'],
        ], [
            $this->eyebrow($c['benefits_eyebrow'] ?? 'Why Choose Us'),
            $this->headline($c['benefits_title'] ?? 'Unmatched Excellence.', 'h2'),
            self::container([
                'flex_direction' => 'row',
                'content_width' => 'full-width',
                'gap' => ['size' => 24, 'unit' => 'px'],
                '_margin' => self::margin(56, 0, 0, 0),
            ], $benefitCards),
        ], 'benefits');

        // --- STATS ---
        $statElements = [];
        foreach ($stats as $i => $s) {
            $statElements[] = self::container([
                'content_width' => 'full-width',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'width' => ['size' => round(100 / count($stats), 2), 'unit' => '%'],
                'padding' => self::pad(50, 20),
                'background_background' => 'classic',
                'background_color' => $this->colors()['surface'],
                'css_classes' => 'royal-stat sr d' . ($i + 1),
            ], [
                self::html('<div class="royal-stat-n" data-count="' . $s['number'] . '" data-suffix="' . ($s['suffix'] ?? '') . '">' . $s['number'] . ($s['suffix'] ?? '') . '</div>'),
                self::html('<div class="royal-stat-l">' . e($s['label']) . '</div>'),
            ]);
        }

        $sections[] = self::container([
            'content_width' => 'full-width',
            'flex_direction' => 'row',
            'gap' => ['size' => 2, 'unit' => 'px'],
            'padding' => self::pad(0),
        ], $statElements);

        // --- TESTIMONIALS ---
        $featTest = $testimonials[0] ?? $testimonials[array_key_first($testimonials)];
        $sideTests = array_slice($testimonials, 1, 2);

        $featCard = self::container([
            'content_width' => 'full-width',
            'flex_direction' => 'column',
            'flex_justify_content' => 'space-between',
            'padding' => self::pad(44),
            'css_classes' => 'royal-tcard royal-tcard-feat sr',
        ], [
            self::container(['content_width' => 'full-width', 'flex_direction' => 'column'], [
                self::html('<div class="royal-stars">&diams;&diams;&diams;&diams;&diams;</div>'),
                self::textEditor('&ldquo;' . e($featTest['quote']) . '&rdquo;', [
                    'text_color' => 'rgba(245,243,255,0.85)',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['body'],
                    'typography_font_size' => ['size' => 18, 'unit' => 'px'],
                    'typography_line_height' => ['size' => 1.85, 'unit' => 'em'],
                    '_margin' => self::margin(0, 0, 28, 0),
                ]),
            ]),
            self::html('<div style="display:flex;align-items:center;gap:14px;"><div style="width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,rgba(124,58,237,.3),rgba(167,139,250,.2));display:flex;align-items:center;justify-content:center;font-family:\'DM Sans\',sans-serif;font-size:18px;font-weight:900;color:var(--royal-accent);">' . e($featTest['initials'] ?? 'AB') . '</div><div><h5 style="font-family:\'DM Sans\',sans-serif;font-size:17px;font-weight:700;color:var(--royal-text);margin:0;">' . e($featTest['name']) . '</h5><span style="font-size:12px;color:rgba(245,243,255,.4);">' . e($featTest['role']) . '</span></div></div>'),
        ]);

        $sideCards = [];
        foreach ($sideTests as $i => $t) {
            $sideCards[] = self::container([
                'content_width' => 'full-width',
                'flex_direction' => 'column',
                'padding' => self::pad(44),
                'css_classes' => 'royal-tcard sr d' . ($i + 1),
            ], [
                self::html('<div class="royal-stars">&diams;&diams;&diams;&diams;&diams;</div>'),
                self::textEditor('&ldquo;' . e($t['quote']) . '&rdquo;', [
                    'text_color' => 'rgba(245,243,255,0.6)',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['body'],
                    'typography_font_size' => ['size' => 15, 'unit' => 'px'],
                    'typography_line_height' => ['size' => 1.85, 'unit' => 'em'],
                    '_margin' => self::margin(0, 0, 28, 0),
                ]),
                self::html('<div style="display:flex;align-items:center;gap:14px;"><div style="width:48px;height:48px;border-radius:50%;background:rgba(124,58,237,.15);display:flex;align-items:center;justify-content:center;font-family:\'DM Sans\',sans-serif;font-size:18px;font-weight:900;color:var(--royal-accent);">' . e($t['initials'] ?? 'AB') . '</div><div><h5 style="font-family:\'DM Sans\',sans-serif;font-size:17px;font-weight:700;color:var(--royal-text);margin:0;">' . e($t['name']) . '</h5><span style="font-size:12px;color:rgba(245,243,255,.4);">' . e($t['role']) . '</span></div></div>'),
            ]);
        }

        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface2'],
        ], [
            $this->eyebrow($c['testimonials_eyebrow'] ?? 'Testimonials'),
            $this->headline($c['testimonials_title'] ?? 'Guest Experiences.', 'h2', [
                '_margin' => self::margin(0, 0, 50, 0),
            ]),
            self::container([
                'flex_direction' => 'row',
                'content_width' => 'full-width',
                'gap' => ['size' => 24, 'unit' => 'px'],
                'flex_align_items' => 'stretch',
            ], [
                self::container([
                    'content_width' => 'full-width',
                    'width' => ['size' => 55, 'unit' => '%'],
                ], [$featCard]),
                self::container([
                    'content_width' => 'full-width',
                    'flex_direction' => 'column',
                    'gap' => ['size' => 16, 'unit' => 'px'],
                    'width' => ['size' => 45, 'unit' => '%'],
                ], $sideCards),
            ]),
        ], 'testimonials');

        // --- CTA ---
        $ctaImg = $galleryImgs[0] ?? $heroImg;
        $sections[] = self::container([
            'content_width' => 'full-width',
            'flex_direction' => 'column',
            'flex_align_items' => 'center',
            'flex_justify_content' => 'center',
            'min_height' => ['size' => 600, 'unit' => 'px'],
            'padding' => self::pad(100, 64),
            'background_background' => 'classic',
            'background_image' => ['url' => $ctaImg, 'id' => ''],
            'background_position' => 'center center',
            'background_size' => 'cover',
            '_element_id' => 'cta',
            'custom_css' => "selector{position:relative;overflow:hidden;}
selector::before{content:'';position:absolute;inset:0;background:linear-gradient(135deg,rgba(12,10,26,.92) 0%,rgba(124,58,237,.3) 100%);z-index:0;}
selector>.e-con-inner,selector>.elementor-widget{position:relative;z-index:2;}",
        ], [
            $this->eyebrow($c['cta_eyebrow'] ?? 'Ready to Experience?'),
            $this->headline($c['cta_title'] ?? 'Begin Your Journey.', 'h2', array_merge(
                self::responsiveSize(100, 64, 42),
                ['align' => 'center', '_margin' => self::margin(20, 0, 24, 0)]
            )),
            $this->bodyText($c['cta_text'] ?? 'Discover the epitome of luxury. Contact us to begin your exclusive experience.', [
                'align' => 'center',
                'typography_font_size' => ['size' => 17, 'unit' => 'px'],
                'custom_css' => 'selector{max-width:540px;margin-left:auto;margin-right:auto;}',
                '_margin' => self::margin(0, 0, 50, 0),
            ]),
            self::container([
                'flex_direction' => 'row',
                'flex_justify_content' => 'center',
                'gap' => ['size' => 14, 'unit' => 'px'],
                'content_width' => 'full-width',
                'css_classes' => 'sr d3',
            ], [
                $this->ctaButton($c['cta_button'] ?? 'Reserve Now', '#contact', [
                    'background_color' => 'transparent',
                    'custom_css' => 'selector .elementor-button{background:linear-gradient(135deg,#7C3AED,#6D28D9)!important;border-radius:12px!important;} selector .elementor-button:hover{box-shadow:0 20px 50px rgba(124,58,237,.45);}',
                ]),
                $this->ghostButton($c['cta_ghost'] ?? 'Contact Us', '#contact', [
                    'border_color' => 'rgba(245,243,255,0.15)',
                    'border_radius' => self::radius(12),
                ]),
            ]),
        ]);

        // Back to top
        $sections[] = self::html('<a href="#hero" id="royal-btt">&uarr;</a>');

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
            'content_width' => 'full-width',
            'flex_direction' => 'column',
            'flex_justify_content' => 'center',
            'padding' => self::pad(140, 64, 100, 64),
            'min_height' => ['size' => 50, 'unit' => 'vh'],
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface'],
        ], [
            $this->eyebrow('About Us'),
            $this->headline($c['about_title'] ?? 'Our Legacy.', 'h1', array_merge(
                self::responsiveSize(100, 64, 42),
                ['custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .2s forwards;}']
            )),
            $this->bodyText($c['about_text'] ?? 'A tradition of excellence and luxury since our founding.', [
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .4s forwards;max-width:560px;}',
            ]),
        ]);

        // Two-col about
        $sections[] = self::twoCol(
            [self::image($img['about'] ?? '', [
                'custom_css' => 'selector img{width:100%;min-height:400px;object-fit:cover;border-radius:16px;}',
            ])],
            [
                $this->eyebrow('Who We Are'),
                $this->headline($c['about_subtitle'] ?? 'Heritage Meets Innovation.', 'h2', [
                    'typography_font_size' => ['size' => 48, 'unit' => 'px'],
                ]),
                $this->bodyText($c['about_text'] ?? 'We are custodians of a rich legacy, dedicated to providing unparalleled luxury.'),
                $this->bodyText($c['about_text2'] ?? 'With decades of expertise, we craft experiences that resonate with elegance and refinement.'),
                $this->ctaButton($c['about_cta'] ?? 'Our Services', '#services', [
                    '_margin' => self::margin(20, 0, 0, 0),
                    'background_color' => 'transparent',
                    'custom_css' => 'selector .elementor-button{background:linear-gradient(135deg,#7C3AED,#6D28D9)!important;border-radius:12px!important;}',
                ]),
            ],
            50,
            ['padding' => self::pad(100, 64)],
            ['css_classes' => 'sr'],
            ['css_classes' => 'sr d2', 'flex_justify_content' => 'center']
        );

        // Stats
        $stats = $c['stats'] ?? [
            ['number' => '500', 'suffix' => '+', 'label' => 'Guests Served'],
            ['number' => '98', 'suffix' => '%', 'label' => 'Satisfaction'],
            ['number' => '25', 'suffix' => '+', 'label' => 'Years'],
        ];
        $statEls = [];
        foreach ($stats as $i => $s) {
            $statEls[] = self::container([
                'content_width' => 'full-width',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'width' => ['size' => round(100 / count($stats), 2), 'unit' => '%'],
                'padding' => self::pad(50, 20),
                'background_background' => 'classic',
                'background_color' => $this->colors()['surface'],
                'css_classes' => 'royal-stat sr d' . ($i + 1),
            ], [
                self::html('<div class="royal-stat-n" data-count="' . $s['number'] . '" data-suffix="' . ($s['suffix'] ?? '') . '">' . $s['number'] . ($s['suffix'] ?? '') . '</div>'),
                self::html('<div class="royal-stat-l">' . e($s['label']) . '</div>'),
            ]);
        }
        $sections[] = self::container([
            'content_width' => 'full-width',
            'flex_direction' => 'row',
            'gap' => ['size' => 2, 'unit' => 'px'],
            'padding' => self::pad(0),
        ], $statEls);

        // Values section
        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface2'],
        ], [
            $this->eyebrow('Our Values'),
            $this->headline($c['values_title'] ?? 'What Defines Us.', 'h2'),
            $this->bodyText($c['values_text'] ?? 'The guiding principles behind every luxury experience we create.', [
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
            'content_width' => 'full-width',
            'flex_direction' => 'column',
            'flex_justify_content' => 'center',
            'padding' => self::pad(140, 64, 100, 64),
            'min_height' => ['size' => 50, 'unit' => 'vh'],
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface'],
        ], [
            $this->eyebrow('Our Services'),
            $this->headline($c['services_title'] ?? 'What We Offer.', 'h1', array_merge(
                self::responsiveSize(100, 64, 42),
                ['custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .2s forwards;}']
            )),
            $this->bodyText($c['services_subtitle'] ?? 'Comprehensive luxury solutions for the most discerning clientele.', [
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .4s forwards;max-width:560px;}',
            ]),
        ]);

        // Service cards
        $services = $c['services'] ?? [
            ['icon' => "\u{2728}", 'title' => 'Premium Experience', 'desc' => 'Immersive luxury experiences designed to delight.'],
            ['icon' => "\u{1F451}", 'title' => 'Bespoke Services', 'desc' => 'Tailored exclusively for your preferences.'],
            ['icon' => "\u{1F48E}", 'title' => 'Elite Collection', 'desc' => 'Curated selections from around the world.'],
        ];

        $cards = [];
        foreach ($services as $i => $svc) {
            $cards[] = self::container([
                'content_width' => 'full-width',
                'flex_direction' => 'column',
                'padding' => self::pad(40, 32),
                'css_classes' => 'royal-card sr d' . min($i + 1, 4),
            ], [
                self::textEditor('<span style="font-size:36px;display:block;margin-bottom:18px;">' . ($svc['icon'] ?? "\u{2728}") . '</span>'),
                self::heading($svc['title'], 'h3', [
                    'title_color' => $this->colors()['text'],
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['heading'],
                    'typography_font_size' => ['size' => 26, 'unit' => 'px'],
                    'typography_font_weight' => '700',
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
                'gap' => ['size' => 24, 'unit' => 'px'],
            ]),
        ], 'services-grid');

        // CTA
        $sections[] = $this->section([
            'flex_align_items' => 'center',
            'custom_css' => 'selector{background:linear-gradient(135deg,#7C3AED,#6D28D9,#EC4899)!important;}',
        ], [
            $this->headline($c['cta_title'] ?? 'Ready to Begin?', 'h2', [
                'title_color' => '#FFFFFF',
                'align' => 'center',
            ]),
            $this->bodyText($c['cta_text'] ?? 'Contact us today for an exclusive consultation.', [
                'align' => 'center',
                'text_color' => 'rgba(255,255,255,0.7)',
                '_margin' => self::margin(0, 0, 30, 0),
            ]),
            self::button($c['cta_button'] ?? 'Contact Us', '#contact', [
                'background_color' => '#FFFFFF',
                'button_text_color' => '#7C3AED',
                'typography_typography' => 'custom',
                'typography_font_family' => $this->fonts()['heading'],
                'typography_font_size' => ['size' => 14, 'unit' => 'px'],
                'typography_font_weight' => '700',
                'typography_letter_spacing' => ['size' => 3, 'unit' => 'px'],
                'typography_text_transform' => 'uppercase',
                'border_radius' => self::radius(12),
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
            'content_width' => 'full-width',
            'flex_direction' => 'column',
            'flex_justify_content' => 'center',
            'padding' => self::pad(140, 64, 100, 64),
            'min_height' => ['size' => 50, 'unit' => 'vh'],
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface'],
        ], [
            $this->eyebrow('Portfolio'),
            $this->headline($c['portfolio_title'] ?? 'Our Collection.', 'h1', array_merge(
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
                'content_width' => 'full-width',
                'width' => ['size' => 33.33, 'unit' => '%'],
                'css_classes' => 'royal-photo sr d' . min($i + 1, 4),
                'custom_css' => 'selector{min-height:300px;}',
            ], [
                self::image($url, [
                    'custom_css' => 'selector img{width:100%;height:300px;object-fit:cover;border-radius:12px;}',
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
                    'flex_wrap' => 'wrap',
                    'content_width' => 'full-width',
                    'gap' => ['size' => 16, 'unit' => 'px'],
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
            'content_width' => 'full-width',
            'flex_direction' => 'column',
            'flex_justify_content' => 'center',
            'padding' => self::pad(140, 64, 100, 64),
            'min_height' => ['size' => 50, 'unit' => 'vh'],
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface'],
        ], [
            $this->eyebrow('Contact'),
            $this->headline($c['contact_title'] ?? 'Get In Touch.', 'h1', array_merge(
                self::responsiveSize(100, 64, 42),
                ['custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .2s forwards;}']
            )),
            $this->bodyText($c['contact_subtitle'] ?? 'We would be delighted to hear from you.', [
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .4s forwards;max-width:500px;}',
            ]),
        ]);

        // Contact info cards (glass style)
        $contactInfo = $c['contact_info'] ?? [
            ['icon' => "\u{1F4E7}", 'label' => 'Email', 'value' => $c['email'] ?? 'concierge@example.com'],
            ['icon' => "\u{1F4DE}", 'label' => 'Phone', 'value' => $c['phone'] ?? '(555) 123-4567'],
            ['icon' => "\u{1F4CD}", 'label' => 'Location', 'value' => $c['address'] ?? '123 Luxury Ave, City, State'],
        ];

        $infoCards = [];
        foreach ($contactInfo as $i => $info) {
            $infoCards[] = self::container([
                'content_width' => 'full-width',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'width' => ['size' => 33.33, 'unit' => '%'],
                'padding' => self::pad(40, 28),
                'css_classes' => 'royal-bcard sr d' . ($i + 1),
            ], [
                self::textEditor('<span style="font-size:36px;display:block;margin-bottom:14px;">' . $info['icon'] . '</span>'),
                self::heading($info['label'], 'h4', [
                    'title_color' => $this->colors()['text'],
                    'align' => 'center',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['heading'],
                    'typography_font_size' => ['size' => 18, 'unit' => 'px'],
                    'typography_font_weight' => '700',
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
                'content_width' => 'full-width',
                'gap' => ['size' => 24, 'unit' => 'px'],
            ], $infoCards),
        ]);

        // Contact form
        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface2'],
            'flex_align_items' => 'center',
        ], [
            $this->eyebrow('Send a Message'),
            $this->headline('We Await Your Inquiry.', 'h2', [
                'align' => 'center',
                'typography_font_size' => ['size' => 48, 'unit' => 'px'],
                '_margin' => self::margin(0, 0, 40, 0),
            ]),
            self::html('<form style="max-width:600px;width:100%;margin:0 auto;display:flex;flex-direction:column;gap:16px;">
<input type="text" placeholder="Your Name" style="padding:14px 20px;background:rgba(22,19,42,.6);backdrop-filter:blur(16px);border:1px solid ' . $this->colors()['border'] . ';color:' . $this->colors()['text'] . ';font-family:\'' . $this->fonts()['body'] . '\',sans-serif;font-size:14px;border-radius:12px;outline:none;transition:border-color .3s;" onfocus="this.style.borderColor=\'rgba(124,58,237,.4)\'" onblur="this.style.borderColor=\'' . $this->colors()['border'] . '\'">
<input type="email" placeholder="Your Email" style="padding:14px 20px;background:rgba(22,19,42,.6);backdrop-filter:blur(16px);border:1px solid ' . $this->colors()['border'] . ';color:' . $this->colors()['text'] . ';font-family:\'' . $this->fonts()['body'] . '\',sans-serif;font-size:14px;border-radius:12px;outline:none;transition:border-color .3s;" onfocus="this.style.borderColor=\'rgba(124,58,237,.4)\'" onblur="this.style.borderColor=\'' . $this->colors()['border'] . '\'">
<textarea rows="5" placeholder="Your Message" style="padding:14px 20px;background:rgba(22,19,42,.6);backdrop-filter:blur(16px);border:1px solid ' . $this->colors()['border'] . ';color:' . $this->colors()['text'] . ';font-family:\'' . $this->fonts()['body'] . '\',sans-serif;font-size:14px;border-radius:12px;outline:none;resize:vertical;transition:border-color .3s;" onfocus="this.style.borderColor=\'rgba(124,58,237,.4)\'" onblur="this.style.borderColor=\'' . $this->colors()['border'] . '\'"></textarea>
<button type="submit" style="padding:16px 44px;background:linear-gradient(135deg,#7C3AED,#6D28D9);color:#FFF;font-family:\'' . $this->fonts()['heading'] . '\',sans-serif;font-size:14px;font-weight:700;letter-spacing:3px;text-transform:uppercase;border:none;border-radius:12px;cursor:pointer;transition:all .3s;box-shadow:0 8px 24px rgba(124,58,237,.3);" onmouseover="this.style.boxShadow=\'0 12px 32px rgba(124,58,237,.5)\'" onmouseout="this.style.boxShadow=\'0 8px 24px rgba(124,58,237,.3)\'">Send Message</button>
</form>'),
        ]);

        return $sections;
    }

    // ═══════════════════════════════════════════════════════════
    // HEADER (HFE)
    // ═══════════════════════════════════════════════════════════

    public function buildHeader(string $siteName, array $pages): array
    {
        $navLinks = '';
        foreach ($pages as $slug => $label) {
            $url = ($slug === 'home') ? '/' : '/' . $slug . '/';
            $navLinks .= '<li><a href="' . $url . '">' . e($label) . '</a></li>';
        }

        return [self::html('<nav class="royal-nav" id="mainNav" style="position:fixed;top:0;left:0;right:0;z-index:1000;padding:0 64px;height:90px;display:flex;align-items:center;justify-content:space-between;transition:background .4s,height .3s;background:transparent;">
<a href="/" style="font-family:\'' . $this->fonts()['heading'] . '\',sans-serif;font-size:22px;font-weight:900;letter-spacing:1px;color:' . $this->colors()['text'] . ';text-decoration:none;text-transform:uppercase;">' . e($siteName) . '</a>
<ul style="display:flex;gap:0;list-style:none;position:absolute;left:50%;transform:translateX(-50%);padding:0;margin:0;">' . $navLinks . '</ul>
<a href="/contact/" style="padding:10px 28px;background:linear-gradient(135deg,#7C3AED,#6D28D9);color:#FFF;font-family:\'' . $this->fonts()['heading'] . '\',sans-serif;font-size:12px;font-weight:700;letter-spacing:2px;text-transform:uppercase;border-radius:10px;text-decoration:none;transition:all .3s;box-shadow:0 4px 16px rgba(124,58,237,.3);">Contact Us</a>
</nav>
<style>
.royal-nav ul a{display:block;padding:8px 20px;font-size:12px;font-weight:600;letter-spacing:2px;text-transform:uppercase;color:rgba(245,243,255,.5);text-decoration:none;transition:color .3s;position:relative;}
.royal-nav ul a::after{content:\'\';position:absolute;bottom:4px;left:20px;right:20px;height:1px;background:linear-gradient(90deg,var(--royal-primary),var(--royal-accent));transform:scaleX(0);transform-origin:center;transition:transform .3s;}
.royal-nav ul a:hover{color:var(--royal-text);}
.royal-nav ul a:hover::after{transform:scaleX(1);}
.royal-nav.bg{background:rgba(12,10,26,.95)!important;backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);height:68px!important;border-bottom:1px solid ' . $this->colors()['border'] . ';}
@media(max-width:1100px){.royal-nav ul{display:none!important;}.royal-nav{padding:0 28px!important;height:68px!important;background:rgba(12,10,26,.95)!important;backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);}}
</style>
<script>window.addEventListener("scroll",()=>{document.getElementById("mainNav").classList.toggle("bg",scrollY>50);});</script>')];
    }

    // ═══════════════════════════════════════════════════════════
    // FOOTER (HFE)
    // ═══════════════════════════════════════════════════════════

    public function buildFooter(string $siteName, array $pages, array $contact): array
    {
        $navLinks = '';
        foreach ($pages as $slug => $label) {
            $url = ($slug === 'home') ? '/' : '/' . $slug . '/';
            $navLinks .= '<li><a href="' . $url . '">' . e($label) . '</a></li>';
        }

        $email = $contact['email'] ?? 'concierge@example.com';
        $phone = $contact['phone'] ?? '';
        $address = $contact['address'] ?? '';

        $contactHtml = '<div class="royal-ci"><div class="royal-ci-i">' . "\u{1F4E7}" . '</div><div><small>Email</small><span>' . e($email) . '</span></div></div>';
        if ($phone) {
            $contactHtml .= '<div class="royal-ci"><div class="royal-ci-i">' . "\u{1F4DE}" . '</div><div><small>Phone</small><span>' . e($phone) . '</span></div></div>';
        }
        if ($address) {
            $contactHtml .= '<div class="royal-ci"><div class="royal-ci-i">' . "\u{1F4CD}" . '</div><div><small>Location</small><span>' . e($address) . '</span></div></div>';
        }

        $sections = [];

        $sections[] = self::container([
            'content_width' => 'full-width',
            'flex_direction' => 'column',
            'padding' => self::pad(0),
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface2'],
        ], [
            self::container([
                'flex_direction' => 'row',
                'content_width' => 'full-width',
                'gap' => ['size' => 60, 'unit' => 'px'],
                'padding' => self::pad(80, 64, 60, 64),
                'border_border' => 'solid',
                'border_width' => self::pad(0, 0, 1, 0),
                'border_color' => $this->colors()['border'],
                'custom_css' => 'selector{display:grid;grid-template-columns:1.5fr 1fr 1fr;}',
            ], [
                self::html('<div><div style="font-family:\'' . $this->fonts()['heading'] . '\',sans-serif;font-size:22px;font-weight:900;letter-spacing:1px;color:' . $this->colors()['text'] . ';margin-bottom:3px;">' . e($siteName) . '</div><div style="font-size:10px;font-weight:600;letter-spacing:3px;text-transform:uppercase;background:linear-gradient(90deg,var(--royal-primary),var(--royal-accent));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;margin-bottom:16px;">' . e($contact['tagline'] ?? 'Luxury Redefined') . '</div><p style="font-size:13.5px;color:rgba(245,243,255,.3);line-height:1.8;max-width:250px;">' . e($contact['footer_text'] ?? 'Crafting extraordinary experiences with timeless elegance.') . '</p><div class="royal-social"><a href="#">' . "\u{1F4F7}" . '</a><a href="#">' . "\u{1F426}" . '</a><a href="#">' . "\u{1F4BC}" . '</a></div></div>'),
                self::html('<div class="royal-fc"><h4>Navigate</h4><ul>' . $navLinks . '</ul></div>'),
                self::html('<div class="royal-fc"><h4>Contact</h4>' . $contactHtml . '</div>'),
            ]),
            self::container([
                'flex_direction' => 'row',
                'flex_justify_content' => 'space-between',
                'flex_align_items' => 'center',
                'content_width' => 'full-width',
                'padding' => self::pad(18, 64),
            ], [
                self::textEditor('<p style="font-size:12px;color:rgba(245,243,255,.2);">&copy; ' . date('Y') . ' <span style="background:linear-gradient(90deg,var(--royal-primary),var(--royal-accent));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">' . e($siteName) . '</span>. All rights reserved.</p>'),
                self::textEditor('<a href="#" style="font-size:11.5px;color:rgba(245,243,255,.2);text-decoration:none;">Privacy Policy</a> &nbsp;&nbsp; <a href="#" style="font-size:11.5px;color:rgba(245,243,255,.2);text-decoration:none;">Terms of Service</a>'),
            ]),
        ]);

        return $sections;
    }
}
