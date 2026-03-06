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
.e-con>.elementor-widget{width:100%;}

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

/* Back to top */
#ember-btt{position:fixed;bottom:28px;right:28px;width:46px;height:46px;background:var(--ember-primary);color:var(--ember-text);border-radius:2px;font-size:20px;display:flex;align-items:center;justify-content:center;text-decoration:none;z-index:500;opacity:0;transform:translateY(12px);pointer-events:none;transition:all .4s;box-shadow:0 8px 24px rgba(220,38,38,.4);}
#ember-btt.show{opacity:1;transform:translateY(0);pointer-events:all;}
#ember-btt:hover{background:var(--ember-secondary);transform:translateY(-4px)!important;}

/* Responsive */
@media(max-width:1100px){.ember-photo{min-height:200px;}.ember-ticker-inner span{font-size:12px;padding:0 24px;}}
@media(max-width:700px){.ep-features{grid-template-columns:1fr;}}
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
        $heroTitle = $c['hero_title'] ?? 'Ignite Your Brand.';
        $heroSub = $c['hero_subtitle'] ?? 'We bring the heat with bold strategies and fiery results that set your business apart.';
        $heroCta = $c['hero_cta'] ?? 'Get Started';
        $heroCtaUrl = $c['hero_cta_url'] ?? '#services';

        $aboutTitle = $c['about_title'] ?? 'Who We Are';
        $aboutText = $c['about_text'] ?? 'We are a passionate team dedicated to delivering bold, impactful results for every client.';
        $aboutText2 = $c['about_text2'] ?? 'With relentless energy and creative fire, we help brands stand out in a crowded marketplace.';

        $services = $c['services'] ?? [
            ['icon' => '🔥', 'title' => 'Brand Strategy', 'desc' => 'Bold strategic planning that ignites your brand presence and drives real market impact.'],
            ['icon' => '🎯', 'title' => 'Creative Design', 'desc' => 'Eye-catching visuals and experiences that capture attention and leave lasting impressions.'],
            ['icon' => '📈', 'title' => 'Growth Marketing', 'desc' => 'Data-driven campaigns that fuel explosive growth and maximize your return on investment.'],
        ];

        $benefits = $c['benefits'] ?? [
            ['icon' => '🏆', 'title' => 'Award Winning', 'desc' => 'Recognized for bold creativity and outstanding results across the industry.'],
            ['icon' => '⚡', 'title' => 'Fast Execution', 'desc' => 'Rapid turnaround without compromising quality or creative intensity.'],
            ['icon' => '🤝', 'title' => 'Dedicated Team', 'desc' => 'Passionate professionals committed to your success around the clock.'],
            ['icon' => '💡', 'title' => 'Bold Innovation', 'desc' => 'Pushing boundaries with fresh ideas and cutting-edge approaches.'],
        ];

        $testimonials = $c['testimonials'] ?? [
            ['quote' => 'They brought incredible energy to our project. The results were nothing short of spectacular.', 'name' => 'Sarah M.', 'role' => 'CEO, Tech Corp', 'initials' => 'SM'],
            ['quote' => 'Bold, creative, and relentless in pursuing excellence. They transformed our entire brand identity.', 'name' => 'James K.', 'role' => 'Marketing Director', 'initials' => 'JK'],
            ['quote' => 'The fire they bring to every project is unmatched. Our business has never been stronger.', 'name' => 'Lisa R.', 'role' => 'Business Owner', 'initials' => 'LR'],
        ];

        $stats = $c['stats'] ?? [
            ['number' => '500', 'suffix' => '+', 'label' => 'Projects Delivered'],
            ['number' => '98', 'suffix' => '%', 'label' => 'Client Satisfaction'],
            ['number' => '15', 'suffix' => '+', 'label' => 'Years Experience'],
        ];

        $tickerItems = $c['ticker_items'] ?? [$siteName, $heroCta, 'Bold Results', 'Fierce Energy', 'Premium Quality'];

        $heroImg = $img['hero'] ?? '';
        $aboutImg = $img['about'] ?? '';
        $galleryImgs = array_filter([
            $img['gallery1'] ?? '',
            $img['gallery2'] ?? '',
            $img['services'] ?? '',
        ]);

        $sections = [];

        // ─── HERO ───
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
selector::before{content:'';position:absolute;inset:0;background:linear-gradient(to right,rgba(12,10,9,.9) 40%,rgba(12,10,9,.2) 100%),linear-gradient(to top,rgba(12,10,9,.95) 0%,rgba(12,10,9,.3) 50%,transparent 100%);z-index:0;}
selector>.e-con-inner,selector>.elementor-widget{position:relative;z-index:2;}",
        ], [
            self::html('<div style="display:inline-flex;align-items:center;gap:10px;margin-bottom:28px;opacity:0;animation:fadeUp .7s ease .2s forwards;"><div style="width:8px;height:8px;background:var(--ember-primary);border-radius:50%;animation:pulse 2s infinite;"></div><span class="eyebrow" style="margin-bottom:0;">' . e($c['hero_eyebrow'] ?? 'Welcome to ' . $siteName) . '</span></div>'),

            $this->headline($heroTitle, 'h1', array_merge(
                self::responsiveSize(120, 72, 52),
                [
                    'typography_letter_spacing' => ['size' => -1, 'unit' => 'px'],
                    '_margin' => self::margin(0, 0, 32, 0),
                    'custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .35s forwards;max-width:800px;}',
                ]
            )),

            self::textEditor('<p>' . e($heroSub) . '</p>', [
                'text_color' => 'rgba(250,250,249,0.55)',
                'typography_typography' => 'custom',
                'typography_font_family' => $this->fonts()['body'],
                'typography_font_size' => ['size' => 18, 'unit' => 'px'],
                'typography_line_height' => ['size' => 1.75, 'unit' => 'em'],
                'typography_font_weight' => '400',
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .55s forwards;max-width:500px;}',
            ]),

            self::container([
                'flex_direction' => 'row',
                'flex_align_items' => 'center',
                'gap' => ['size' => 16, 'unit' => 'px'],
                'content_width' => 'full-width',
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .7s forwards;}',
            ], [
                $this->ctaButton($heroCta, $heroCtaUrl, [
                    'custom_css' => 'selector .elementor-button:hover{transform:translateY(-3px);box-shadow:0 20px 50px rgba(220,38,38,.45);}',
                ]),
                $this->ghostButton($c['hero_ghost_cta'] ?? 'Learn More', '#about'),
            ]),
        ]);

        // ─── TICKER ───
        $tickerHtml = '';
        foreach (array_merge($tickerItems, $tickerItems) as $item) {
            $tickerHtml .= '<span>' . e($item) . '</span><span class="dot">✦</span>';
        }
        $sections[] = self::container([
            'content_width' => 'full-width',
            'background_background' => 'classic',
            'background_color' => $this->colors()['primary'],
            'padding' => self::pad(0),
        ], [
            self::html('<div class="ember-ticker"><div class="ember-ticker-inner">' . $tickerHtml . '</div></div>'),
        ]);

        // ─── ABOUT PREVIEW ───
        $credItems = '';
        foreach ($c['credentials'] ?? [
            'Award-winning team with a track record of bold results',
            'Industry-leading expertise in creative strategy',
            'Trusted by hundreds of ambitious brands worldwide',
        ] as $cred) {
            $credItems .= '<div class="ember-cred-item"><span class="star">★</span><span>' . e($cred) . '</span></div>';
        }

        $pillarItems = '';
        foreach ($c['pillars'] ?? [
            ['icon' => '🔥', 'title' => 'Fierce Passion', 'desc' => 'We bring relentless energy to every project.'],
            ['icon' => '🎯', 'title' => 'Precision Focus', 'desc' => 'Targeted strategies for maximum impact.'],
            ['icon' => '🏆', 'title' => 'Proven Results', 'desc' => 'A track record of delivering excellence.'],
        ] as $pillar) {
            $pillarItems .= '<div class="ember-pillar"><div class="ember-pillar-ico">' . $pillar['icon'] . '</div><div><h4>' . e($pillar['title']) . '</h4><p>' . e($pillar['desc']) . '</p></div></div>';
        }

        $sections[] = self::twoCol(
            [self::image($aboutImg, [
                'custom_css' => 'selector img{width:100%;height:100%;min-height:400px;object-fit:cover;transition:transform 8s ease;} selector:hover img{transform:scale(1.04);}',
            ])],
            [
                $this->eyebrow($c['about_eyebrow'] ?? 'About Us'),
                $this->headline($aboutTitle, 'h2', ['_margin' => self::margin(0, 0, 8, 0)]),
                $this->bodyText($aboutText),
                $this->bodyText($aboutText2),
                self::html('<div class="ember-cred sr d2"><h4>' . e($c['cred_title'] ?? '🔥 Why Choose Us') . '</h4>' . $credItems . '</div>'),
                self::html('<div class="sr d3" style="border:1px solid var(--ember-border);">' . $pillarItems . '</div>'),
            ],
            50,
            ['padding' => self::pad(0), '_element_id' => 'about'],
            ['padding' => self::pad(0)],
            [
                'padding' => self::pad(80, 72, 80, 60),
                'background_background' => 'classic',
                'background_color' => $this->colors()['surface'],
                'flex_justify_content' => 'center',
            ]
        );

        // ─── SERVICES (Cards with red top-border hover) ───
        $serviceCards = [];
        foreach ($services as $i => $svc) {
            $serviceCards[] = self::container([
                'content_width' => 'full-width',
                'flex_direction' => 'column',
                'width' => ['size' => 33.33, 'unit' => '%'],
                'padding' => self::pad(44, 32),
                'background_background' => 'classic',
                'background_color' => $this->colors()['surface'],
                'css_classes' => 'ember-card sr d' . ($i + 1),
            ], [
                self::textEditor('<span style="font-size:40px;display:block;margin-bottom:20px;">' . ($svc['icon'] ?? '🔥') . '</span>'),
                self::heading($svc['title'], 'h3', [
                    'title_color' => $this->colors()['text'],
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['heading'],
                    'typography_font_size' => ['size' => 24, 'unit' => 'px'],
                    'typography_font_weight' => '800',
                    'typography_letter_spacing' => ['size' => 0.5, 'unit' => 'px'],
                    '_margin' => self::margin(0, 0, 12, 0),
                ]),
                $this->bodyText($svc['desc'], ['typography_font_size' => ['size' => 14, 'unit' => 'px']]),
            ]);
        }

        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface2'],
            'border_border' => 'solid',
            'border_width' => self::pad(1, 0, 0, 0),
            'border_color' => $this->colors()['border'],
        ], [
            $this->eyebrow($c['services_eyebrow'] ?? 'Our Services'),
            $this->headline($c['services_title'] ?? 'What We Do.', 'h2'),
            $this->bodyText($c['services_subtitle'] ?? 'Bold solutions fueled by creative fire.', [
                '_margin' => self::margin(0),
                'custom_css' => 'selector{max-width:560px;}',
            ]),
            self::container([
                'flex_direction' => 'row',
                'content_width' => 'full-width',
                'gap' => ['size' => 2, 'unit' => 'px'],
                '_margin' => self::margin(56, 0, 0, 0),
            ], $serviceCards),
        ], 'services');

        // ─── BENEFITS ───
        $benefitCards = [];
        foreach ($benefits as $i => $b) {
            $benefitCards[] = self::container([
                'content_width' => 'full-width',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'width' => ['size' => 25, 'unit' => '%'],
                'padding' => self::pad(38, 28),
                'background_background' => 'classic',
                'background_color' => $this->colors()['surface'],
                'css_classes' => 'ember-bcard sr d' . ($i + 1),
            ], [
                self::textEditor('<span style="font-size:40px;display:block;margin-bottom:16px;">' . ($b['icon'] ?? '🔥') . '</span>'),
                self::heading($b['title'], 'h3', [
                    'title_color' => $this->colors()['text'],
                    'align' => 'center',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['heading'],
                    'typography_font_size' => ['size' => 20, 'unit' => 'px'],
                    'typography_font_weight' => '800',
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
            $this->headline($c['benefits_title'] ?? 'Built to Burn.', 'h2'),
            self::container([
                'flex_direction' => 'row',
                'content_width' => 'full-width',
                'gap' => ['size' => 2, 'unit' => 'px'],
                '_margin' => self::margin(56, 0, 0, 0),
            ], $benefitCards),
        ], 'benefits');

        // ─── STATS (big red numbers) ───
        $statElements = [];
        foreach ($stats as $i => $s) {
            $statElements[] = self::container([
                'content_width' => 'full-width',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'width' => ['size' => round(100 / count($stats), 2), 'unit' => '%'],
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
            'content_width' => 'full-width',
            'flex_direction' => 'row',
            'gap' => ['size' => 2, 'unit' => 'px'],
            'padding' => self::pad(0),
        ], $statElements);

        // ─── TESTIMONIALS ───
        $featTest = $testimonials[0] ?? $testimonials[array_key_first($testimonials)];
        $sideTests = array_slice($testimonials, 1, 2);

        $featCard = self::container([
            'content_width' => 'full-width',
            'flex_direction' => 'column',
            'flex_justify_content' => 'space-between',
            'padding' => self::pad(44),
            'background_background' => 'classic',
            'background_color' => $this->colors()['primary'],
            'border_radius' => self::radius(4),
            'css_classes' => 'ember-tcard ember-tcard-feat sr',
        ], [
            self::container(['content_width' => 'full-width', 'flex_direction' => 'column'], [
                self::html('<div class="ember-stars">★★★★★</div>'),
                self::textEditor('"' . e($featTest['quote']) . '"', [
                    'text_color' => 'rgba(250,250,249,0.9)',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['body'],
                    'typography_font_size' => ['size' => 18, 'unit' => 'px'],
                    'typography_line_height' => ['size' => 1.85, 'unit' => 'em'],
                    '_margin' => self::margin(0, 0, 28, 0),
                ]),
            ]),
            self::html('<div style="display:flex;align-items:center;gap:14px;"><div style="width:48px;height:48px;border-radius:50%;background:rgba(250,250,249,.15);display:flex;align-items:center;justify-content:center;font-family:\'Montserrat\',sans-serif;font-size:18px;font-weight:900;color:var(--ember-text);">' . e($featTest['initials'] ?? 'AB') . '</div><div><h5 style="font-family:\'Montserrat\',sans-serif;font-size:17px;font-weight:800;color:var(--ember-text);margin:0;">' . e($featTest['name']) . '</h5><span style="font-size:12px;color:rgba(250,250,249,.5);">' . e($featTest['role']) . '</span></div></div>'),
        ]);

        $sideCards = [];
        foreach ($sideTests as $i => $t) {
            $sideCards[] = self::container([
                'content_width' => 'full-width',
                'flex_direction' => 'column',
                'padding' => self::pad(44),
                'background_background' => 'classic',
                'background_color' => $this->colors()['surface'],
                'border_radius' => self::radius(4),
                'css_classes' => 'ember-tcard sr d' . ($i + 1),
            ], [
                self::html('<div class="ember-stars">★★★★★</div>'),
                self::textEditor('"' . e($t['quote']) . '"', [
                    'text_color' => 'rgba(250,250,249,0.65)',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['body'],
                    'typography_font_size' => ['size' => 15, 'unit' => 'px'],
                    'typography_line_height' => ['size' => 1.85, 'unit' => 'em'],
                    '_margin' => self::margin(0, 0, 28, 0),
                ]),
                self::html('<div style="display:flex;align-items:center;gap:14px;"><div style="width:48px;height:48px;border-radius:50%;background:rgba(250,250,249,.1);display:flex;align-items:center;justify-content:center;font-family:\'Montserrat\',sans-serif;font-size:18px;font-weight:900;color:var(--ember-text);">' . e($t['initials'] ?? 'AB') . '</div><div><h5 style="font-family:\'Montserrat\',sans-serif;font-size:17px;font-weight:800;color:var(--ember-text);margin:0;">' . e($t['name']) . '</h5><span style="font-size:12px;color:rgba(250,250,249,.4);">' . e($t['role']) . '</span></div></div>'),
            ]);
        }

        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface2'],
        ], [
            $this->eyebrow($c['testimonials_eyebrow'] ?? 'Testimonials'),
            $this->headline($c['testimonials_title'] ?? 'Real Results.', 'h2', [
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

        // ─── CTA (full-bleed image + dark overlay) ───
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
            'background_overlay_background' => 'classic',
            'background_overlay_color' => 'rgba(12,10,9,0.85)',
            '_element_id' => 'cta',
        ], [
            $this->eyebrow($c['cta_eyebrow'] ?? 'Ready to Ignite?'),
            $this->headline($c['cta_title'] ?? 'Let\'s Light It Up.', 'h2', array_merge(
                self::responsiveSize(100, 64, 42),
                ['align' => 'center', '_margin' => self::margin(20, 0, 24, 0)]
            )),
            $this->bodyText($c['cta_text'] ?? 'Get in touch today and let us bring the fire to your brand.', [
                'align' => 'center',
                'typography_font_size' => ['size' => 18, 'unit' => 'px'],
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
                $this->ctaButton($c['cta_button'] ?? 'Get Started', '#contact'),
                $this->ghostButton($c['cta_ghost'] ?? 'Contact Us', '#contact'),
            ]),
        ]);

        // Back to top
        $sections[] = self::html('<a href="#hero" id="ember-btt">↑</a>');

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
                'content_width' => 'full-width',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'width' => ['size' => round(100 / count($stats), 2), 'unit' => '%'],
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
                'content_width' => 'full-width',
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
                'gap' => ['size' => 2, 'unit' => 'px'],
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
            'content_width' => 'full-width',
            'flex_direction' => 'column',
            'flex_justify_content' => 'center',
            'padding' => self::pad(140, 64, 100, 64),
            'min_height' => ['size' => 50, 'unit' => 'vh'],
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
                'content_width' => 'full-width',
                'width' => ['size' => 33.33, 'unit' => '%'],
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
                'content_width' => 'full-width',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'width' => ['size' => 33.33, 'unit' => '%'],
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
                'content_width' => 'full-width',
                'gap' => ['size' => 2, 'unit' => 'px'],
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
        $navLinks = '';
        foreach ($pages as $slug => $label) {
            $url = ($slug === 'home') ? '/' : '/' . $slug . '/';
            $navLinks .= '<li><a href="' . $url . '">' . e($label) . '</a></li>';
        }

        return [self::html('<nav class="ember-nav" id="mainNav" style="position:fixed;top:0;left:0;right:0;z-index:1000;padding:0 64px;height:90px;display:flex;align-items:center;justify-content:space-between;transition:background .4s,height .3s;background:transparent;">
<a href="/" style="font-family:\'' . $this->fonts()['heading'] . '\',sans-serif;font-size:22px;font-weight:900;letter-spacing:2px;color:' . $this->colors()['text'] . ';text-decoration:none;text-transform:uppercase;">' . e($siteName) . '</a>
<ul style="display:flex;gap:0;list-style:none;position:absolute;left:50%;transform:translateX(-50%);padding:0;margin:0;">' . $navLinks . '</ul>
<a href="/contact/" style="padding:10px 28px;background:' . $this->colors()['primary'] . ';color:#FAFAF9;font-family:\'' . $this->fonts()['heading'] . '\',sans-serif;font-size:12px;font-weight:800;letter-spacing:2px;text-transform:uppercase;border-radius:2px;text-decoration:none;transition:all .3s;">Contact Us</a>
</nav>
<style>
.ember-nav ul a{display:block;padding:8px 20px;font-size:12px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:rgba(250,250,249,.5);text-decoration:none;transition:color .3s;position:relative;}
.ember-nav ul a::after{content:\'\';position:absolute;bottom:4px;left:20px;right:20px;height:2px;background:' . $this->colors()['primary'] . ';transform:scaleX(0);transform-origin:center;transition:transform .3s;}
.ember-nav ul a:hover{color:#FAFAF9;}
.ember-nav ul a:hover::after{transform:scaleX(1);}
.ember-nav.bg{background:rgba(12,10,9,.95)!important;backdrop-filter:blur(20px);height:68px!important;border-bottom:1px solid ' . $this->colors()['border'] . ';}
@media(max-width:1100px){.ember-nav ul{display:none!important;}.ember-nav{padding:0 28px!important;height:68px!important;background:rgba(12,10,9,.95)!important;backdrop-filter:blur(20px);}}
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

        $email = $contact['email'] ?? 'hello@example.com';
        $phone = $contact['phone'] ?? '';
        $address = $contact['address'] ?? '';

        $contactHtml = '<div class="ember-ci"><div class="ember-ci-i">📧</div><div><small>Email</small><span>' . e($email) . '</span></div></div>';
        if ($phone) {
            $contactHtml .= '<div class="ember-ci"><div class="ember-ci-i">📞</div><div><small>Phone</small><span>' . e($phone) . '</span></div></div>';
        }
        if ($address) {
            $contactHtml .= '<div class="ember-ci"><div class="ember-ci-i">📍</div><div><small>Location</small><span>' . e($address) . '</span></div></div>';
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
                self::html('<div><div style="font-family:\'' . $this->fonts()['heading'] . '\',sans-serif;font-size:22px;font-weight:900;letter-spacing:2px;color:' . $this->colors()['text'] . ';text-transform:uppercase;margin-bottom:3px;">' . e($siteName) . '</div><div style="font-size:10px;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:' . $this->colors()['primary'] . ';margin-bottom:16px;">' . e($contact['tagline'] ?? 'Bold Solutions') . '</div><p style="font-size:13.5px;color:rgba(250,250,249,.3);line-height:1.8;max-width:250px;">' . e($contact['footer_text'] ?? 'Bringing the fire to every project.') . '</p><div class="ember-social"><a href="#">📷</a><a href="#">🐦</a><a href="#">💼</a></div></div>'),
                self::html('<div class="ember-fc"><h4>Navigate</h4><ul>' . $navLinks . '</ul></div>'),
                self::html('<div class="ember-fc"><h4>Contact</h4>' . $contactHtml . '</div>'),
            ]),
            self::container([
                'flex_direction' => 'row',
                'flex_justify_content' => 'space-between',
                'flex_align_items' => 'center',
                'content_width' => 'full-width',
                'padding' => self::pad(18, 64),
            ], [
                self::textEditor('<p style="font-size:12px;color:rgba(250,250,249,.2);">&copy; ' . date('Y') . ' <span style="color:' . $this->colors()['primary'] . ';">' . e($siteName) . '</span>. All rights reserved.</p>'),
                self::textEditor('<a href="#" style="font-size:11.5px;color:rgba(250,250,249,.2);text-decoration:none;">Privacy Policy</a> &nbsp;&nbsp; <a href="#" style="font-size:11.5px;color:rgba(250,250,249,.2);text-decoration:none;">Terms of Service</a>'),
            ]),
        ]);

        return $sections;
    }
}
