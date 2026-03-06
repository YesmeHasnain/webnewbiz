<?php

namespace App\Services\Layouts;

/**
 * Noir — Dark premium layout with orange accent.
 * Inspired by the Coop B-Ball (zaid-elementor) design.
 * Glassmorphism nav, scroll-reveal, custom cursor, ticker bar,
 * pseudo-border hovers, staggered animations.
 */
class LayoutNoir extends AbstractLayout
{
    public function slug(): string { return 'noir'; }
    public function name(): string { return 'Noir'; }
    public function description(): string { return 'Bold dark theme with vibrant accent colors, glassmorphism effects, and premium animations'; }
    public function bestFor(): array { return ['Sports', 'Fitness', 'Tech', 'Auto', 'Agency']; }
    public function isDark(): bool { return true; }

    public function colors(): array
    {
        return [
            'primary'   => '#FF4500',
            'secondary' => '#FF6A1A',
            'accent'    => '#FFB800',
            'bg'        => '#080808',
            'surface'   => '#0E0E0E',
            'surface2'  => '#161616',
            'text'      => '#F8F8F6',
            'muted'     => 'rgba(255,255,255,0.45)',
            'border'    => 'rgba(255,255,255,0.08)',
        ];
    }

    public function fonts(): array
    {
        return ['heading' => 'Barlow Condensed', 'body' => 'Barlow'];
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
<link href="https://fonts.googleapis.com/css2?family={$hf}:wght@300;400;600;700;900&family={$bf}:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root{--black:{$c['bg']};--dark:{$c['surface']};--dark2:{$c['surface2']};--white:{$c['text']};--gray:#A0A0A0;--border:{$c['border']};--accent:{$c['primary']};--accent2:{$c['secondary']};--gold:{$c['accent']};}
body,body.elementor-template-canvas{background:var(--black);color:var(--white);font-family:'{$f['body']}',sans-serif;overflow-x:hidden;margin:0;padding:0;}
.elementor-element,.elementor.elementor-2{font-family:'{$f['body']}',sans-serif;}
.elementor-widget{margin-bottom:0 !important;}
.e-con{--gap:0px;}
.e-con>.elementor-widget{width:100%;}

/* Cursor */
#noir-cr{width:12px;height:12px;background:var(--accent);border-radius:50%;position:fixed;top:0;left:0;z-index:99999;pointer-events:none;mix-blend-mode:screen;transition:transform .15s;}
#noir-cr2{width:40px;height:40px;border:1px solid rgba(255,69,0,.5);border-radius:50%;position:fixed;top:0;left:0;z-index:99998;pointer-events:none;}

/* Animations */
@keyframes fadeUp{from{opacity:0;transform:translateY(28px)}to{opacity:1;transform:translateY(0)}}
@keyframes fadeIn{from{opacity:0}to{opacity:1}}
@keyframes roll{from{transform:translateX(0)}to{transform:translateX(-50%)}}
@keyframes scrollPulse{0%,100%{opacity:.5;height:60px}50%{opacity:1;height:80px}}
.sr{opacity:0;transform:translateY(36px);transition:opacity .85s ease,transform .85s ease;}
.sr.d1{transition-delay:.1s}.sr.d2{transition-delay:.2s}.sr.d3{transition-delay:.3s}.sr.d4{transition-delay:.45s}
.sr.in{opacity:1;transform:none;}

/* Eyebrow */
.eyebrow{display:inline-flex;align-items:center;gap:12px;font-size:11px;font-weight:600;letter-spacing:4px;text-transform:uppercase;color:var(--accent);margin-bottom:20px;}
.eyebrow::before{content:'';width:28px;height:1px;background:var(--accent);}

/* Ticker */
.ticker{overflow:hidden;white-space:nowrap;padding:14px 0;}
.ticker-inner{display:inline-flex;animation:roll 28s linear infinite;}
.ticker-inner span{font-family:'{$f['heading']}',sans-serif;font-size:14px;font-weight:700;letter-spacing:4px;text-transform:uppercase;color:var(--black);padding:0 40px;}
.ticker-inner .dot{color:rgba(0,0,0,.3)!important;padding:0 4px!important;}

/* Card Hovers */
.noir-card{position:relative;overflow:hidden;transition:background .3s;cursor:pointer;}
.noir-card::after{content:'';position:absolute;bottom:0;left:0;right:0;height:2px;background:var(--accent);transform:scaleX(0);transform-origin:left;transition:transform .5s;}
.noir-card:hover{background:rgba(255,255,255,.02)!important;}
.noir-card:hover::after{transform:scaleX(1);}

/* Benefit Card */
.noir-bcard{text-align:center;transition:all .35s;position:relative;overflow:hidden;}
.noir-bcard::before{content:'';position:absolute;top:0;left:0;right:0;height:2px;background:var(--accent);transform:scaleX(0);transform-origin:center;transition:transform .5s;}
.noir-bcard:hover{background:var(--dark2)!important;transform:translateY(-4px);}
.noir-bcard:hover::before{transform:scaleX(1);}

/* Step Container */
.noir-step{border-bottom:1px solid var(--border);transition:all .3s;}
.noir-step:first-child{border-top:1px solid var(--border);}

/* Testimonial Card */
.noir-tcard{border:1px solid var(--border);border-radius:4px;transition:all .35s;}
.noir-tcard:hover{border-color:rgba(255,69,0,.25);transform:translateY(-5px);}
.noir-tcard-feat{border-color:var(--accent)!important;}
.noir-tcard-feat:hover{transform:translateY(-8px)!important;box-shadow:0 24px 60px rgba(255,69,0,.4);}

/* CTA pill badge */
.noir-badge{display:inline-flex;align-items:center;gap:5px;margin-top:14px;padding:5px 12px;border-radius:50px;font-size:10px;font-weight:600;letter-spacing:1.5px;text-transform:uppercase;background:rgba(255,69,0,.07);border:1px solid rgba(255,69,0,.2);color:var(--accent);}

/* Stars */
.noir-stars{color:var(--gold);letter-spacing:2px;font-size:14px;margin-bottom:16px;}

/* Pricing */
.noir-plan{border-radius:4px;overflow:hidden;box-shadow:0 24px 64px rgba(255,69,0,.35);}
.sp-price{display:flex;align-items:flex-start;justify-content:center;gap:4px;margin-bottom:10px;}
.sp-cur{font-size:26px;font-weight:700;color:var(--black);margin-top:14px;}
.sp-num{font-family:'{$f['heading']}',sans-serif;font-size:108px;font-weight:900;line-height:1;color:var(--black);letter-spacing:-4px;}
.sp-mo{font-size:16px;color:rgba(0,0,0,.4);align-self:flex-end;padding-bottom:14px;}
.sp-features{list-style:none;display:grid;grid-template-columns:1fr 1fr;gap:14px 40px;margin:0 0 40px 0;padding:0;}
.sp-features li{display:flex;align-items:center;gap:10px;font-size:15px;color:rgba(0,0,0,.75);}
.sp-chk{width:22px;height:22px;min-width:22px;border-radius:50%;background:rgba(0,0,0,.14);display:flex;align-items:center;justify-content:center;font-size:11px;color:var(--black);}

/* Stats */
.noir-stat{text-align:center;}
.noir-stat-n{font-family:'{$f['heading']}',sans-serif;font-size:56px;font-weight:900;color:var(--accent);line-height:1;}
.noir-stat-l{font-size:10px;letter-spacing:2px;text-transform:uppercase;color:rgba(255,255,255,.3);margin-top:5px;}

/* Footer */
.noir-fc h4{font-size:10px;font-weight:600;letter-spacing:4px;text-transform:uppercase;color:rgba(255,255,255,.4);margin-bottom:22px;padding-bottom:14px;border-bottom:1px solid var(--border);}
.noir-fc ul{list-style:none;display:flex;flex-direction:column;gap:11px;padding:0;margin:0;}
.noir-fc a{font-size:14px;color:rgba(255,255,255,.35);text-decoration:none;display:flex;align-items:center;gap:8px;transition:color .3s;}
.noir-fc a::before{content:'›';color:rgba(255,69,0,.35);transition:color .3s;}
.noir-fc a:hover{color:rgba(255,255,255,.75);}
.noir-fc a:hover::before{color:var(--accent);}
.noir-social{display:flex;gap:8px;margin-top:24px;}
.noir-social a{width:38px;height:38px;border:1px solid var(--border);border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:16px;text-decoration:none;color:rgba(255,255,255,.3);transition:all .3s;}
.noir-social a:hover{border-color:var(--accent);color:var(--accent);background:rgba(255,69,0,.05);transform:translateY(-3px);}

/* Contact info block */
.noir-ci{display:flex;align-items:flex-start;gap:10px;margin-bottom:14px;}
.noir-ci-i{width:34px;height:34px;min-width:34px;background:rgba(255,69,0,.06);border:1px solid rgba(255,69,0,.12);border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:14px;}
.noir-ci small{display:block;font-size:9px;color:rgba(255,255,255,.25);letter-spacing:2px;text-transform:uppercase;margin-bottom:2px;}
.noir-ci span{font-size:13px;color:rgba(255,255,255,.45);}

/* Credential block */
.noir-cred{background:rgba(255,69,0,.05);border:1px solid rgba(255,69,0,.18);border-radius:3px;padding:24px 22px;margin:28px 0;}
.noir-cred h4{font-family:'{$f['heading']}',sans-serif;font-size:13px;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:var(--accent);margin-bottom:14px;}
.noir-cred-item{display:flex;align-items:flex-start;gap:10px;font-size:14px;color:rgba(255,255,255,.6);line-height:1.5;margin-bottom:9px;}
.noir-cred-item .star{color:var(--accent);flex-shrink:0;font-size:12px;margin-top:2px;}

/* Pillar rows */
.noir-pillar{display:flex;align-items:center;gap:20px;padding:22px 24px;border-bottom:1px solid var(--border);transition:all .3s;cursor:pointer;}
.noir-pillar:last-child{border-bottom:none;}
.noir-pillar:hover{background:rgba(255,69,0,.04);padding-left:32px;}
.noir-pillar-ico{width:44px;height:44px;min-width:44px;background:rgba(255,69,0,.08);border:1px solid rgba(255,69,0,.15);border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:20px;}
.noir-pillar h4{font-family:'{$f['heading']}',sans-serif;font-size:17px;font-weight:700;letter-spacing:.5px;color:var(--white);margin-bottom:3px;}
.noir-pillar p{font-size:13px;color:rgba(255,255,255,.4);line-height:1.5;margin:0;}

/* Gallery photo hover */
.noir-photo{overflow:hidden;position:relative;border-radius:4px;}
.noir-photo img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .7s ease;}
.noir-photo:hover img{transform:scale(1.06);}
.noir-pho{position:absolute;inset:0;background:linear-gradient(to top,rgba(8,8,8,.6) 0%,transparent 50%);opacity:0;transition:opacity .4s;}
.noir-photo:hover .noir-pho{opacity:1;}

/* Back to top */
#noir-btt{position:fixed;bottom:28px;right:28px;width:46px;height:46px;background:var(--accent);color:var(--white);border-radius:2px;font-size:20px;display:flex;align-items:center;justify-content:center;text-decoration:none;z-index:500;opacity:0;transform:translateY(12px);pointer-events:none;transition:all .4s;box-shadow:0 8px 24px rgba(255,69,0,.4);}
#noir-btt.show{opacity:1;transform:translateY(0);pointer-events:all;}
#noir-btt:hover{background:var(--accent2);transform:translateY(-4px)!important;}

/* Responsive */
@media(max-width:1100px){.noir-photo{min-height:200px;}.ticker-inner span{font-size:12px;padding:0 24px;}}
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
cr.id='noir-cr';cr2.id='noir-cr2';document.body.appendChild(cr);document.body.appendChild(cr2);
let mx=0,my=0,tx=0,ty=0;
document.addEventListener("mousemove",e=>{mx=e.clientX;my=e.clientY;cr.style.left=(mx-6)+"px";cr.style.top=(my-6)+"px";});
(function loop(){tx+=(mx-tx-20)*.13;ty+=(my-ty-20)*.13;cr2.style.left=tx+"px";cr2.style.top=ty+"px";requestAnimationFrame(loop);})();
document.querySelectorAll("a,button").forEach(el=>{el.addEventListener("mouseenter",()=>{cr.style.transform="scale(2.5)";cr.style.opacity=".5";});el.addEventListener("mouseleave",()=>{cr.style.transform="scale(1)";cr.style.opacity="1";});});
const btt=document.getElementById('noir-btt');
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
        $heroTitle = $c['hero_title'] ?? 'Premium Quality. Delivered.';
        $heroSub = $c['hero_subtitle'] ?? 'We deliver exceptional results with cutting-edge solutions tailored to your needs.';
        $heroCta = $c['hero_cta'] ?? 'Get Started';
        $heroCtaUrl = $c['hero_cta_url'] ?? '#services';

        $aboutTitle = $c['about_title'] ?? 'Who We Are';
        $aboutText = $c['about_text'] ?? 'We are a team of dedicated professionals committed to delivering excellence in everything we do.';
        $aboutText2 = $c['about_text2'] ?? 'With years of experience and a passion for innovation, we help businesses achieve their goals.';

        $services = $c['services'] ?? [
            ['icon' => '⚡', 'title' => 'Strategy & Planning', 'desc' => 'We develop comprehensive strategies tailored to your unique business goals and market position.'],
            ['icon' => '🎯', 'title' => 'Design & Development', 'desc' => 'Creating beautiful, functional solutions that engage your audience and drive measurable results.'],
            ['icon' => '📈', 'title' => 'Growth & Marketing', 'desc' => 'Data-driven marketing campaigns that increase visibility, engagement, and revenue consistently.'],
        ];

        $benefits = $c['benefits'] ?? [
            ['icon' => '🏆', 'title' => 'Award Winning', 'desc' => 'Recognized for excellence in our industry with multiple awards and accolades.'],
            ['icon' => '⚡', 'title' => 'Fast Delivery', 'desc' => 'Quick turnaround times without compromising on quality or attention to detail.'],
            ['icon' => '🤝', 'title' => 'Dedicated Support', 'desc' => '24/7 customer support to ensure your complete satisfaction with our services.'],
            ['icon' => '💡', 'title' => 'Innovation First', 'desc' => 'Cutting-edge solutions using the latest technology and industry best practices.'],
        ];

        $testimonials = $c['testimonials'] ?? [
            ['quote' => 'Working with this team transformed our business completely. The results exceeded all our expectations.', 'name' => 'Sarah M.', 'role' => 'CEO, Tech Corp', 'initials' => 'SM'],
            ['quote' => 'Exceptional quality and attention to detail. They delivered exactly what we needed, on time and on budget.', 'name' => 'James K.', 'role' => 'Marketing Director', 'initials' => 'JK'],
            ['quote' => 'The best investment we have made. Their expertise and professionalism are unmatched in the industry.', 'name' => 'Lisa R.', 'role' => 'Business Owner', 'initials' => 'LR'],
        ];

        $stats = $c['stats'] ?? [
            ['number' => '500', 'suffix' => '+', 'label' => 'Projects Completed'],
            ['number' => '98', 'suffix' => '%', 'label' => 'Client Satisfaction'],
            ['number' => '15', 'suffix' => '+', 'label' => 'Years Experience'],
        ];

        $tickerItems = $c['ticker_items'] ?? [$siteName, $heroCta, 'Premium Quality', 'Expert Team', 'Proven Results'];

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
selector::before{content:'';position:absolute;inset:0;background:linear-gradient(to right,rgba(8,8,8,.85) 45%,rgba(8,8,8,.2) 100%),linear-gradient(to top,rgba(8,8,8,.95) 0%,rgba(8,8,8,.3) 50%,transparent 100%);z-index:0;}
selector>.e-con-inner,selector>.elementor-widget{position:relative;z-index:2;}",
        ], [
            self::html('<div style="display:inline-flex;align-items:center;gap:10px;margin-bottom:28px;opacity:0;animation:fadeUp .7s ease .2s forwards;"><div style="width:8px;height:8px;background:var(--accent);border-radius:50%;"></div><span class="eyebrow" style="margin-bottom:0;">' . e($c['hero_eyebrow'] ?? 'Welcome to ' . $siteName) . '</span></div>'),

            $this->headline($heroTitle, 'h1', array_merge(
                self::responsiveSize(120, 72, 52),
                [
                    'typography_letter_spacing' => ['size' => -1, 'unit' => 'px'],
                    '_margin' => self::margin(0, 0, 32, 0),
                    'custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .35s forwards;max-width:800px;}',
                ]
            )),

            self::textEditor('<p>' . e($heroSub) . '</p>', [
                'text_color' => 'rgba(255,255,255,0.55)',
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
                    'custom_css' => 'selector .elementor-button:hover{transform:translateY(-3px);box-shadow:0 20px 50px rgba(255,69,0,.45);}',
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
            self::html('<div class="ticker"><div class="ticker-inner">' . $tickerHtml . '</div></div>'),
        ]);

        // ─── ABOUT PREVIEW ───
        $credItems = '';
        foreach ($c['credentials'] ?? [
            'Award-winning team with proven track record',
            'Industry-leading expertise and innovation',
            'Trusted by hundreds of businesses worldwide',
        ] as $cred) {
            $credItems .= '<div class="noir-cred-item"><span class="star">★</span><span>' . e($cred) . '</span></div>';
        }

        $pillarItems = '';
        foreach ($c['pillars'] ?? [
            ['icon' => '🎯', 'title' => 'Expert Guidance', 'desc' => 'Personalized strategies from industry veterans.'],
            ['icon' => '📱', 'title' => 'Modern Solutions', 'desc' => 'Cutting-edge technology tailored to your needs.'],
            ['icon' => '🏆', 'title' => 'Proven Results', 'desc' => 'Track record of delivering measurable outcomes.'],
        ] as $pillar) {
            $pillarItems .= '<div class="noir-pillar"><div class="noir-pillar-ico">' . $pillar['icon'] . '</div><div><h4>' . e($pillar['title']) . '</h4><p>' . e($pillar['desc']) . '</p></div></div>';
        }

        $sections[] = self::twoCol(
            // Left: image
            [self::image($aboutImg, [
                'custom_css' => 'selector img{width:100%;height:100%;min-height:400px;object-fit:cover;transition:transform 8s ease;} selector:hover img{transform:scale(1.04);}',
            ])],
            // Right: text
            [
                $this->eyebrow($c['about_eyebrow'] ?? 'About Us'),
                $this->headline($aboutTitle, 'h2', ['_margin' => self::margin(0, 0, 8, 0)]),
                $this->bodyText($aboutText),
                $this->bodyText($aboutText2),
                self::html('<div class="noir-cred sr d2"><h4>' . e($c['cred_title'] ?? '🏆 Why Choose Us') . '</h4>' . $credItems . '</div>'),
                self::html('<div class="sr d3" style="border:1px solid var(--border);">' . $pillarItems . '</div>'),
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

        // ─── SERVICES (Camp Cards style) ───
        $serviceCards = [];
        foreach ($services as $i => $svc) {
            $serviceCards[] = self::container([
                'content_width' => 'full-width',
                'flex_direction' => 'column',
                'width' => ['size' => 33.33, 'unit' => '%'],
                'padding' => self::pad(40, 32),
                'background_background' => 'classic',
                'background_color' => $this->colors()['surface'],
                'css_classes' => 'noir-card sr d' . ($i + 1),
            ], [
                self::textEditor('<span style="font-size:36px;display:block;margin-bottom:18px;">' . ($svc['icon'] ?? '⚡') . '</span>'),
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
            'border_border' => 'solid',
            'border_width' => self::pad(1, 0, 0, 0),
            'border_color' => $this->colors()['border'],
        ], [
            $this->eyebrow($c['services_eyebrow'] ?? 'Our Services'),
            $this->headline($c['services_title'] ?? 'What We Do.', 'h2'),
            $this->bodyText($c['services_subtitle'] ?? 'Expert solutions tailored to your needs.', [
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

        // ─── BENEFITS (bcard style) ───
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
                'css_classes' => 'noir-bcard sr d' . ($i + 1),
            ], [
                self::textEditor('<span style="font-size:38px;display:block;margin-bottom:16px;">' . ($b['icon'] ?? '⚡') . '</span>'),
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
            $this->headline($c['benefits_title'] ?? 'Built Different.', 'h2'),
            self::container([
                'flex_direction' => 'row',
                'content_width' => 'full-width',
                'gap' => ['size' => 2, 'unit' => 'px'],
                '_margin' => self::margin(56, 0, 0, 0),
            ], $benefitCards),
        ], 'benefits');

        // ─── STATS ───
        $statElements = [];
        foreach ($stats as $i => $s) {
            $statElements[] = self::container([
                'content_width' => 'full-width',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'width' => ['size' => round(100 / count($stats), 2), 'unit' => '%'],
                'padding' => self::pad(40, 20),
                'background_background' => 'classic',
                'background_color' => $this->colors()['surface'],
                'css_classes' => 'noir-stat sr d' . ($i + 1),
            ], [
                self::html('<div class="noir-stat-n" data-count="' . $s['number'] . '" data-suffix="' . ($s['suffix'] ?? '') . '">' . $s['number'] . ($s['suffix'] ?? '') . '</div>'),
                self::html('<div class="noir-stat-l">' . e($s['label']) . '</div>'),
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
            'css_classes' => 'noir-tcard noir-tcard-feat sr',
        ], [
            self::container(['content_width' => 'full-width', 'flex_direction' => 'column'], [
                self::html('<div class="noir-stars">★★★★★</div>'),
                self::textEditor('"' . e($featTest['quote']) . '"', [
                    'text_color' => 'rgba(0,0,0,0.8)',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['body'],
                    'typography_font_size' => ['size' => 18, 'unit' => 'px'],
                    'typography_line_height' => ['size' => 1.85, 'unit' => 'em'],
                    '_margin' => self::margin(0, 0, 28, 0),
                ]),
            ]),
            self::html('<div style="display:flex;align-items:center;gap:14px;"><div style="width:48px;height:48px;border-radius:50%;background:rgba(0,0,0,.15);display:flex;align-items:center;justify-content:center;font-family:\'Barlow Condensed\',sans-serif;font-size:18px;font-weight:900;color:var(--black);">' . e($featTest['initials'] ?? 'AB') . '</div><div><h5 style="font-family:\'Barlow Condensed\',sans-serif;font-size:17px;font-weight:700;color:var(--black);margin:0;">' . e($featTest['name']) . '</h5><span style="font-size:12px;color:rgba(0,0,0,.5);">' . e($featTest['role']) . '</span></div></div>'),
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
                'css_classes' => 'noir-tcard sr d' . ($i + 1),
            ], [
                self::html('<div class="noir-stars">★★★★★</div>'),
                self::textEditor('"' . e($t['quote']) . '"', [
                    'text_color' => 'rgba(255,255,255,0.65)',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['body'],
                    'typography_font_size' => ['size' => 15, 'unit' => 'px'],
                    'typography_line_height' => ['size' => 1.85, 'unit' => 'em'],
                    '_margin' => self::margin(0, 0, 28, 0),
                ]),
                self::html('<div style="display:flex;align-items:center;gap:14px;"><div style="width:48px;height:48px;border-radius:50%;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-family:\'Barlow Condensed\',sans-serif;font-size:18px;font-weight:900;color:var(--white);">' . e($t['initials'] ?? 'AB') . '</div><div><h5 style="font-family:\'Barlow Condensed\',sans-serif;font-size:17px;font-weight:700;color:var(--white);margin:0;">' . e($t['name']) . '</h5><span style="font-size:12px;color:rgba(255,255,255,.4);">' . e($t['role']) . '</span></div></div>'),
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

        // ─── CTA ───
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
            'background_overlay_color' => 'rgba(8,8,8,0.82)',
            '_element_id' => 'cta',
        ], [
            $this->eyebrow($c['cta_eyebrow'] ?? 'Ready to Start?'),
            $this->headline($c['cta_title'] ?? 'Let\'s Build Together.', 'h2', array_merge(
                self::responsiveSize(100, 64, 42),
                ['align' => 'center', '_margin' => self::margin(20, 0, 24, 0)]
            )),
            $this->bodyText($c['cta_text'] ?? 'Get in touch today and discover how we can help your business grow.', [
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
                $this->ctaButton($c['cta_button'] ?? 'Get Started', '#contact'),
                $this->ghostButton($c['cta_ghost'] ?? 'Contact Us', '#contact'),
            ]),
        ]);

        // Back to top
        $sections[] = self::html('<a href="#hero" id="noir-btt">↑</a>');

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
            $this->bodyText($c['about_text'] ?? 'Dedicated to excellence since day one.', [
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
                $this->headline($c['about_subtitle'] ?? 'Passion Meets Expertise.', 'h2', [
                    'typography_font_size' => ['size' => 48, 'unit' => 'px'],
                ]),
                $this->bodyText($c['about_text'] ?? 'We are a team of dedicated professionals.'),
                $this->bodyText($c['about_text2'] ?? 'With years of experience delivering outstanding results.'),
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
                'padding' => self::pad(40, 20),
                'background_background' => 'classic',
                'background_color' => $this->colors()['surface'],
                'css_classes' => 'noir-stat sr d' . ($i + 1),
            ], [
                self::html('<div class="noir-stat-n" data-count="' . $s['number'] . '" data-suffix="' . ($s['suffix'] ?? '') . '">' . $s['number'] . ($s['suffix'] ?? '') . '</div>'),
                self::html('<div class="noir-stat-l">' . e($s['label']) . '</div>'),
            ]);
        }
        $sections[] = self::container([
            'content_width' => 'full-width',
            'flex_direction' => 'row',
            'gap' => ['size' => 2, 'unit' => 'px'],
            'padding' => self::pad(0),
        ], $statEls);

        // Team / values section
        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface2'],
        ], [
            $this->eyebrow('Our Values'),
            $this->headline($c['values_title'] ?? 'What Drives Us.', 'h2'),
            $this->bodyText($c['values_text'] ?? 'Core values that define everything we do.', [
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
            $this->bodyText($c['services_subtitle'] ?? 'Comprehensive solutions for every challenge.', [
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .4s forwards;max-width:560px;}',
            ]),
        ]);

        // Service cards
        $services = $c['services'] ?? [
            ['icon' => '⚡', 'title' => 'Strategy', 'desc' => 'Comprehensive strategic planning.'],
            ['icon' => '🎯', 'title' => 'Design', 'desc' => 'Beautiful, functional design.'],
            ['icon' => '📈', 'title' => 'Growth', 'desc' => 'Data-driven growth strategies.'],
        ];

        $cards = [];
        foreach ($services as $i => $svc) {
            $cards[] = self::container([
                'content_width' => 'full-width',
                'flex_direction' => 'column',
                'padding' => self::pad(40, 32),
                'background_background' => 'classic',
                'background_color' => $this->colors()['surface'],
                'css_classes' => 'noir-card sr d' . min($i + 1, 4),
            ], [
                self::textEditor('<span style="font-size:36px;display:block;margin-bottom:18px;">' . ($svc['icon'] ?? '⚡') . '</span>'),
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
                'gap' => ['size' => 2, 'unit' => 'px'],
            ]),
        ], 'services-grid');

        // CTA
        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['primary'],
            'flex_align_items' => 'center',
        ], [
            $this->headline($c['cta_title'] ?? 'Ready to Start?', 'h2', [
                'title_color' => '#080808',
                'align' => 'center',
            ]),
            $this->bodyText($c['cta_text'] ?? 'Contact us today for a free consultation.', [
                'align' => 'center',
                'text_color' => 'rgba(0,0,0,0.6)',
                '_margin' => self::margin(0, 0, 30, 0),
            ]),
            self::button($c['cta_button'] ?? 'Contact Us', '#contact', [
                'background_color' => '#080808',
                'button_text_color' => '#F8F8F6',
                'typography_typography' => 'custom',
                'typography_font_family' => $this->fonts()['heading'],
                'typography_font_size' => ['size' => 14, 'unit' => 'px'],
                'typography_font_weight' => '700',
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

        // Gallery grid using images
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
                'css_classes' => 'noir-photo sr d' . min($i + 1, 4),
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
            $this->bodyText($c['contact_subtitle'] ?? 'We would love to hear from you.', [
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
                'padding' => self::pad(40, 28),
                'background_background' => 'classic',
                'background_color' => $this->colors()['surface'],
                'css_classes' => 'noir-bcard sr d' . ($i + 1),
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
                'gap' => ['size' => 2, 'unit' => 'px'],
            ], $infoCards),
        ]);

        // Contact form HTML
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
<input type="text" placeholder="Your Name" style="padding:14px 20px;background:' . $this->colors()['surface'] . ';border:1px solid ' . $this->colors()['border'] . ';color:' . $this->colors()['text'] . ';font-family:\'' . $this->fonts()['body'] . '\',sans-serif;font-size:14px;border-radius:2px;outline:none;">
<input type="email" placeholder="Your Email" style="padding:14px 20px;background:' . $this->colors()['surface'] . ';border:1px solid ' . $this->colors()['border'] . ';color:' . $this->colors()['text'] . ';font-family:\'' . $this->fonts()['body'] . '\',sans-serif;font-size:14px;border-radius:2px;outline:none;">
<textarea rows="5" placeholder="Your Message" style="padding:14px 20px;background:' . $this->colors()['surface'] . ';border:1px solid ' . $this->colors()['border'] . ';color:' . $this->colors()['text'] . ';font-family:\'' . $this->fonts()['body'] . '\',sans-serif;font-size:14px;border-radius:2px;outline:none;resize:vertical;"></textarea>
<button type="submit" style="padding:16px 44px;background:' . $this->colors()['primary'] . ';color:#FFF;font-family:\'' . $this->fonts()['heading'] . '\',sans-serif;font-size:14px;font-weight:700;letter-spacing:3px;text-transform:uppercase;border:none;border-radius:2px;cursor:pointer;transition:all .3s;">Send Message</button>
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

        return [self::html('<nav class="noir-nav" id="mainNav" style="position:fixed;top:0;left:0;right:0;z-index:1000;padding:0 64px;height:90px;display:flex;align-items:center;justify-content:space-between;transition:background .4s,height .3s;background:transparent;">
<a href="/" style="font-family:\'' . $this->fonts()['heading'] . '\',sans-serif;font-size:22px;font-weight:900;letter-spacing:1px;color:' . $this->colors()['text'] . ';text-decoration:none;text-transform:uppercase;">' . e($siteName) . '</a>
<ul style="display:flex;gap:0;list-style:none;position:absolute;left:50%;transform:translateX(-50%);padding:0;margin:0;">' . $navLinks . '</ul>
<a href="/contact/" style="padding:10px 28px;background:' . $this->colors()['primary'] . ';color:#FFF;font-family:\'' . $this->fonts()['heading'] . '\',sans-serif;font-size:12px;font-weight:700;letter-spacing:2px;text-transform:uppercase;border-radius:2px;text-decoration:none;transition:all .3s;">Contact Us</a>
</nav>
<style>
.noir-nav ul a{display:block;padding:8px 20px;font-size:12px;font-weight:600;letter-spacing:2px;text-transform:uppercase;color:rgba(255,255,255,.5);text-decoration:none;transition:color .3s;position:relative;}
.noir-nav ul a::after{content:\'\';position:absolute;bottom:4px;left:20px;right:20px;height:1px;background:' . $this->colors()['primary'] . ';transform:scaleX(0);transform-origin:center;transition:transform .3s;}
.noir-nav ul a:hover{color:#FFF;}
.noir-nav ul a:hover::after{transform:scaleX(1);}
.noir-nav.bg{background:rgba(8,8,8,.95)!important;backdrop-filter:blur(20px);height:68px!important;border-bottom:1px solid ' . $this->colors()['border'] . ';}
@media(max-width:1100px){.noir-nav ul{display:none!important;}.noir-nav{padding:0 28px!important;height:68px!important;background:rgba(8,8,8,.95)!important;backdrop-filter:blur(20px);}}
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

        $contactHtml = '<div class="noir-ci"><div class="noir-ci-i">📧</div><div><small>Email</small><span>' . e($email) . '</span></div></div>';
        if ($phone) {
            $contactHtml .= '<div class="noir-ci"><div class="noir-ci-i">📞</div><div><small>Phone</small><span>' . e($phone) . '</span></div></div>';
        }
        if ($address) {
            $contactHtml .= '<div class="noir-ci"><div class="noir-ci-i">📍</div><div><small>Location</small><span>' . e($address) . '</span></div></div>';
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
                self::html('<div><div style="font-family:\'' . $this->fonts()['heading'] . '\',sans-serif;font-size:22px;font-weight:900;letter-spacing:1px;color:' . $this->colors()['text'] . ';margin-bottom:3px;">' . e($siteName) . '</div><div style="font-size:10px;font-weight:600;letter-spacing:3px;text-transform:uppercase;color:' . $this->colors()['primary'] . ';margin-bottom:16px;">' . e($contact['tagline'] ?? 'Premium Solutions') . '</div><p style="font-size:13.5px;color:rgba(255,255,255,.3);line-height:1.8;max-width:250px;">' . e($contact['footer_text'] ?? 'Delivering excellence with every project.') . '</p><div class="noir-social"><a href="#">📷</a><a href="#">🐦</a><a href="#">💼</a></div></div>'),
                self::html('<div class="noir-fc"><h4>Navigate</h4><ul>' . $navLinks . '</ul></div>'),
                self::html('<div class="noir-fc"><h4>Contact</h4>' . $contactHtml . '</div>'),
            ]),
            self::container([
                'flex_direction' => 'row',
                'flex_justify_content' => 'space-between',
                'flex_align_items' => 'center',
                'content_width' => 'full-width',
                'padding' => self::pad(18, 64),
            ], [
                self::textEditor('<p style="font-size:12px;color:rgba(255,255,255,.2);">© ' . date('Y') . ' <span style="color:' . $this->colors()['primary'] . ';">' . e($siteName) . '</span>. All rights reserved.</p>'),
                self::textEditor('<a href="#" style="font-size:11.5px;color:rgba(255,255,255,.2);text-decoration:none;">Privacy Policy</a> &nbsp;&nbsp; <a href="#" style="font-size:11.5px;color:rgba(255,255,255,.2);text-decoration:none;">Terms of Service</a>'),
            ]),
        ]);

        return $sections;
    }
}
