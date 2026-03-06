<?php

namespace App\Services\Layouts;

/**
 * Forest — Warm, professional earthy theme with forest green accent.
 * Clean borders on cards, subtle hover lift, serif headings,
 * scroll-reveal animations, natural warm feel.
 */
class LayoutForest extends AbstractLayout
{
    public function slug(): string { return 'forest'; }
    public function name(): string { return 'Forest'; }
    public function description(): string { return 'Warm, professional earthy theme with forest green accent'; }
    public function bestFor(): array { return ['Real Estate', 'Construction', 'Home Services', 'Landscaping']; }
    public function isDark(): bool { return false; }

    public function colors(): array
    {
        return [
            'primary'   => '#2D6A4F',
            'secondary' => '#40916C',
            'accent'    => '#95D5B2',
            'bg'        => '#FAFAF5',
            'surface'   => '#FFFFFF',
            'surface2'  => '#F0EDE5',
            'text'      => '#1B1B18',
            'muted'     => 'rgba(27,27,24,0.55)',
            'border'    => 'rgba(27,27,24,0.1)',
        ];
    }

    public function fonts(): array
    {
        return ['heading' => 'Merriweather', 'body' => 'Source Sans 3'];
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
<link href="https://fonts.googleapis.com/css2?family={$hf}:wght@300;400;700;900&family={$bf}:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{--forest-primary:{$c['primary']};--forest-secondary:{$c['secondary']};--forest-accent:{$c['accent']};--forest-bg:{$c['bg']};--forest-surface:{$c['surface']};--forest-surface2:{$c['surface2']};--forest-text:{$c['text']};--forest-muted:{$c['muted']};--forest-border:{$c['border']};}
body,body.elementor-template-canvas{background:var(--forest-bg);color:var(--forest-text);font-family:'{$f['body']}',sans-serif;overflow-x:hidden;margin:0;padding:0;}
.elementor-element,.elementor.elementor-2{font-family:'{$f['body']}',sans-serif;}
.elementor-widget{margin-bottom:0 !important;}
.e-con{--gap:0px;}
.e-con>.elementor-widget{width:100%;}

/* Animations */
@keyframes fadeUp{from{opacity:0;transform:translateY(28px)}to{opacity:1;transform:translateY(0)}}
@keyframes fadeIn{from{opacity:0}to{opacity:1}}
.sr{opacity:0;transform:translateY(28px);transition:opacity .8s ease,transform .8s ease;}
.sr.d1{transition-delay:.1s}.sr.d2{transition-delay:.2s}.sr.d3{transition-delay:.3s}.sr.d4{transition-delay:.45s}
.sr.in{opacity:1;transform:none;}

/* Eyebrow with green accent line */
.eyebrow{display:inline-flex;align-items:center;gap:12px;font-size:12px;font-weight:600;letter-spacing:3px;text-transform:uppercase;color:var(--forest-primary);margin-bottom:20px;font-family:'{$f['body']}',sans-serif;}
.eyebrow::before{content:'';width:32px;height:2px;background:var(--forest-primary);}

/* Cards */
.forest-card{position:relative;background:var(--forest-surface);border:1px solid var(--forest-border);border-radius:8px;overflow:hidden;transition:transform .35s ease,box-shadow .35s ease;}
.forest-card:hover{transform:translateY(-6px);box-shadow:0 20px 48px rgba(27,27,24,.08);}

/* Benefit Card */
.forest-bcard{text-align:center;background:var(--forest-surface);border:1px solid var(--forest-border);border-radius:8px;transition:transform .35s ease,box-shadow .35s ease,border-color .35s ease;}
.forest-bcard:hover{transform:translateY(-5px);box-shadow:0 16px 40px rgba(27,27,24,.07);border-color:var(--forest-accent);}

/* Icon circle */
.forest-icon{width:56px;height:56px;border-radius:50%;background:rgba(45,106,79,.08);border:1px solid rgba(45,106,79,.15);display:flex;align-items:center;justify-content:center;font-size:24px;margin-bottom:18px;transition:background .3s;}
.forest-bcard:hover .forest-icon{background:rgba(45,106,79,.14);}

/* Stat */
.forest-stat{text-align:center;padding:40px 20px;}
.forest-stat-n{font-family:'{$f['heading']}',serif;font-size:48px;font-weight:900;color:var(--forest-primary);line-height:1;}
.forest-stat-l{font-size:12px;font-weight:600;letter-spacing:2px;text-transform:uppercase;color:var(--forest-muted);margin-top:8px;}

/* Testimonial */
.forest-tcard{background:var(--forest-surface);border:1px solid var(--forest-border);border-radius:8px;padding:36px;transition:transform .35s ease,box-shadow .35s ease;}
.forest-tcard:hover{transform:translateY(-4px);box-shadow:0 16px 40px rgba(27,27,24,.07);}
.forest-tcard-feat{border-color:var(--forest-primary)!important;border-width:2px;}
.forest-tcard-feat:hover{box-shadow:0 20px 52px rgba(45,106,79,.12);}
.forest-stars{color:#D4A017;letter-spacing:2px;font-size:14px;margin-bottom:16px;}
.forest-quote-mark{font-family:'{$f['heading']}',serif;font-size:48px;line-height:1;color:var(--forest-accent);margin-bottom:12px;}

/* CTA Section */
.forest-cta{background:var(--forest-primary);color:#FFF;border-radius:0;}
.forest-cta .eyebrow{color:var(--forest-accent);}
.forest-cta .eyebrow::before{background:var(--forest-accent);}

/* Footer */
.forest-fc h4{font-family:'{$f['heading']}',serif;font-size:14px;font-weight:700;color:var(--forest-text);margin-bottom:20px;padding-bottom:14px;border-bottom:1px solid var(--forest-border);}
.forest-fc ul{list-style:none;display:flex;flex-direction:column;gap:10px;padding:0;margin:0;}
.forest-fc a{font-size:14px;color:var(--forest-muted);text-decoration:none;display:flex;align-items:center;gap:8px;transition:color .3s;}
.forest-fc a::before{content:'›';color:var(--forest-secondary);transition:color .3s;}
.forest-fc a:hover{color:var(--forest-text);}
.forest-fc a:hover::before{color:var(--forest-primary);}
.forest-social{display:flex;gap:8px;margin-top:20px;}
.forest-social a{width:38px;height:38px;border:1px solid var(--forest-border);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:16px;text-decoration:none;color:var(--forest-muted);transition:all .3s;}
.forest-social a:hover{border-color:var(--forest-primary);color:var(--forest-primary);background:rgba(45,106,79,.05);transform:translateY(-3px);}

/* Contact info */
.forest-ci{display:flex;align-items:flex-start;gap:12px;margin-bottom:14px;}
.forest-ci-i{width:40px;height:40px;min-width:40px;background:rgba(45,106,79,.06);border:1px solid rgba(45,106,79,.12);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:16px;}
.forest-ci small{display:block;font-size:11px;color:var(--forest-muted);letter-spacing:1px;text-transform:uppercase;margin-bottom:2px;}
.forest-ci span{font-size:14px;color:var(--forest-text);}

/* Credential block */
.forest-cred{background:rgba(45,106,79,.04);border:1px solid rgba(45,106,79,.12);border-radius:8px;padding:24px 22px;margin:28px 0;}
.forest-cred h4{font-family:'{$f['heading']}',serif;font-size:14px;font-weight:700;color:var(--forest-primary);margin-bottom:14px;}
.forest-cred-item{display:flex;align-items:flex-start;gap:10px;font-size:14px;color:var(--forest-muted);line-height:1.6;margin-bottom:8px;}
.forest-cred-item .leaf{color:var(--forest-primary);flex-shrink:0;font-size:12px;margin-top:3px;}

/* Pillar rows */
.forest-pillar{display:flex;align-items:center;gap:18px;padding:20px 22px;border-bottom:1px solid var(--forest-border);transition:all .3s;cursor:pointer;}
.forest-pillar:last-child{border-bottom:none;}
.forest-pillar:hover{background:rgba(45,106,79,.03);padding-left:28px;}
.forest-pillar-ico{width:44px;height:44px;min-width:44px;background:rgba(45,106,79,.06);border:1px solid rgba(45,106,79,.12);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:20px;}
.forest-pillar h4{font-family:'{$f['heading']}',serif;font-size:16px;font-weight:700;color:var(--forest-text);margin-bottom:3px;}
.forest-pillar p{font-size:13px;color:var(--forest-muted);line-height:1.5;margin:0;}

/* Gallery photo hover */
.forest-photo{overflow:hidden;position:relative;border-radius:8px;border:1px solid var(--forest-border);}
.forest-photo img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .7s ease;}
.forest-photo:hover img{transform:scale(1.05);}

/* Back to top */
#forest-btt{position:fixed;bottom:28px;right:28px;width:46px;height:46px;background:var(--forest-primary);color:#FFF;border-radius:50%;font-size:20px;display:flex;align-items:center;justify-content:center;text-decoration:none;z-index:500;opacity:0;transform:translateY(12px);pointer-events:none;transition:all .4s;box-shadow:0 8px 24px rgba(45,106,79,.3);}
#forest-btt.show{opacity:1;transform:translateY(0);pointer-events:all;}
#forest-btt:hover{background:var(--forest-secondary);transform:translateY(-4px)!important;}

/* Responsive */
@media(max-width:1100px){.forest-photo{min-height:200px;}}
@media(max-width:700px){.forest-nav ul{display:none!important;}.forest-nav{padding:0 24px!important;height:64px!important;}}
</style>
CSS;
    }

    public function buildGlobalJs(): string
    {
        return <<<'JS'
<script>
(function(){
const btt=document.getElementById('forest-btt');
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
    // SHARED OVERRIDES — Forest-specific typography
    // ═══════════════════════════════════════════════════════════

    public function headline(string $text, string $tag = 'h2', array $extra = []): array
    {
        $c = $this->colors();
        $f = $this->fonts();
        return self::heading($text, $tag, array_merge([
            'title_color' => $c['text'],
            'typography_typography' => 'custom',
            'typography_font_family' => $f['heading'],
            'typography_font_weight' => '900',
            'typography_line_height' => ['size' => 1.15, 'unit' => 'em'],
        ], self::responsiveSize(56, 42, 32), $extra));
    }

    public function bodyText(string $text, array $extra = []): array
    {
        $c = $this->colors();
        $f = $this->fonts();
        return self::textEditor('<p>' . $text . '</p>', array_merge([
            'text_color' => $c['muted'],
            'typography_typography' => 'custom',
            'typography_font_family' => $f['body'],
            'typography_font_size' => ['size' => 16, 'unit' => 'px'],
            'typography_line_height' => ['size' => 1.8, 'unit' => 'em'],
            'typography_font_weight' => '400',
        ], $extra));
    }

    public function ctaButton(string $text, string $url = '#', array $extra = []): array
    {
        $c = $this->colors();
        $f = $this->fonts();
        return self::button($text, $url, array_merge([
            'button_type' => 'default',
            'background_color' => $c['primary'],
            'button_text_color' => '#FFFFFF',
            'typography_typography' => 'custom',
            'typography_font_family' => $f['body'],
            'typography_font_size' => ['size' => 14, 'unit' => 'px'],
            'typography_font_weight' => '600',
            'typography_letter_spacing' => ['size' => 1, 'unit' => 'px'],
            'border_radius' => self::radius(6),
            'button_padding' => self::pad(16, 36),
            'button_background_hover_color' => $c['secondary'],
        ], $extra));
    }

    public function ghostButton(string $text, string $url = '#', array $extra = []): array
    {
        $c = $this->colors();
        $f = $this->fonts();
        return self::button($text, $url, array_merge([
            'button_type' => 'default',
            'background_color' => 'transparent',
            'button_text_color' => $c['primary'],
            'typography_typography' => 'custom',
            'typography_font_family' => $f['body'],
            'typography_font_size' => ['size' => 14, 'unit' => 'px'],
            'typography_font_weight' => '600',
            'typography_letter_spacing' => ['size' => 1, 'unit' => 'px'],
            'border_border' => 'solid',
            'border_width' => self::pad(1),
            'border_color' => $c['primary'],
            'border_radius' => self::radius(6),
            'button_padding' => self::pad(15, 34),
        ], $extra));
    }

    // ═══════════════════════════════════════════════════════════
    // HOME PAGE
    // ═══════════════════════════════════════════════════════════

    public function buildHomePage(array $c, array $img): array
    {
        $siteName = $c['site_name'] ?? 'Business Name';
        $heroTitle = $c['hero_title'] ?? 'Building Trust, Building Futures';
        $heroSub = $c['hero_subtitle'] ?? 'We deliver exceptional craftsmanship and professional service for every project, big or small.';
        $heroCta = $c['hero_cta'] ?? 'Get a Free Quote';
        $heroCtaUrl = $c['hero_cta_url'] ?? '#services';

        $aboutTitle = $c['about_title'] ?? 'Who We Are';
        $aboutText = $c['about_text'] ?? 'We are a team of experienced professionals committed to delivering quality work and lasting results.';
        $aboutText2 = $c['about_text2'] ?? 'With decades of combined experience, we bring expertise and integrity to every project we undertake.';

        $services = $c['services'] ?? [
            ['icon' => '🏠', 'title' => 'Residential Projects', 'desc' => 'Custom homes and renovations built to the highest standards of quality and craftsmanship.'],
            ['icon' => '🏗️', 'title' => 'Commercial Construction', 'desc' => 'Professional commercial builds delivered on time and within budget, every single time.'],
            ['icon' => '🌿', 'title' => 'Landscape Design', 'desc' => 'Beautiful outdoor spaces that enhance your property value and quality of life.'],
        ];

        $benefits = $c['benefits'] ?? [
            ['icon' => '🏆', 'title' => 'Award Winning', 'desc' => 'Recognized for excellence in our industry with multiple awards.'],
            ['icon' => '⏱️', 'title' => 'On-Time Delivery', 'desc' => 'We respect your schedule and deliver projects on time.'],
            ['icon' => '🤝', 'title' => 'Trusted Partners', 'desc' => 'Long-lasting relationships built on trust and quality.'],
            ['icon' => '🔧', 'title' => 'Expert Craftsmen', 'desc' => 'Skilled professionals with years of hands-on experience.'],
        ];

        $testimonials = $c['testimonials'] ?? [
            ['quote' => 'They transformed our property beyond our wildest expectations. Professional, punctual, and passionate about their work.', 'name' => 'Sarah M.', 'role' => 'Homeowner', 'initials' => 'SM'],
            ['quote' => 'The attention to detail and quality of work was outstanding. Highly recommend to anyone.', 'name' => 'James K.', 'role' => 'Property Developer', 'initials' => 'JK'],
            ['quote' => 'From planning to completion, the entire experience was seamless and professional.', 'name' => 'Lisa R.', 'role' => 'Business Owner', 'initials' => 'LR'],
        ];

        $stats = $c['stats'] ?? [
            ['number' => '500', 'suffix' => '+', 'label' => 'Projects Completed'],
            ['number' => '98', 'suffix' => '%', 'label' => 'Client Satisfaction'],
            ['number' => '25', 'suffix' => '+', 'label' => 'Years Experience'],
        ];

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
            'flex_justify_content' => 'center',
            'padding' => self::pad(0, 64, 100, 64),
            'min_height' => ['size' => 100, 'unit' => 'vh'],
            'background_background' => 'classic',
            'background_image' => ['url' => $heroImg, 'id' => ''],
            'background_position' => 'center center',
            'background_size' => 'cover',
            '_element_id' => 'hero',
            'custom_css' => "selector{position:relative;overflow:hidden;}
selector::before{content:'';position:absolute;inset:0;background:linear-gradient(to right,rgba(27,27,24,.82) 40%,rgba(27,27,24,.25) 100%),linear-gradient(to top,rgba(27,27,24,.9) 0%,rgba(27,27,24,.2) 50%,transparent 100%);z-index:0;}
selector>.e-con-inner,selector>.elementor-widget{position:relative;z-index:2;}",
        ], [
            self::html('<div style="display:inline-flex;align-items:center;gap:10px;margin-bottom:28px;opacity:0;animation:fadeUp .7s ease .2s forwards;"><span class="eyebrow" style="margin-bottom:0;color:#FFF;">' . e($c['hero_eyebrow'] ?? 'Welcome to ' . $siteName) . '</span></div>'),

            self::heading($heroTitle, 'h1', array_merge(
                self::responsiveSize(64, 48, 36),
                [
                    'title_color' => '#FFFFFF',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['heading'],
                    'typography_font_weight' => '900',
                    'typography_line_height' => ['size' => 1.15, 'unit' => 'em'],
                    '_margin' => self::margin(0, 0, 24, 0),
                    'custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .35s forwards;max-width:680px;}',
                ]
            )),

            self::textEditor('<p>' . e($heroSub) . '</p>', [
                'text_color' => 'rgba(255,255,255,0.7)',
                'typography_typography' => 'custom',
                'typography_font_family' => $this->fonts()['body'],
                'typography_font_size' => ['size' => 18, 'unit' => 'px'],
                'typography_line_height' => ['size' => 1.75, 'unit' => 'em'],
                'typography_font_weight' => '400',
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .55s forwards;max-width:520px;}',
            ]),

            self::container([
                'flex_direction' => 'row',
                'flex_align_items' => 'center',
                'gap' => ['size' => 14, 'unit' => 'px'],
                'content_width' => 'full-width',
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .7s forwards;}',
            ], [
                self::button($heroCta, $heroCtaUrl, [
                    'button_type' => 'default',
                    'background_color' => $this->colors()['primary'],
                    'button_text_color' => '#FFFFFF',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['body'],
                    'typography_font_size' => ['size' => 15, 'unit' => 'px'],
                    'typography_font_weight' => '600',
                    'border_radius' => self::radius(6),
                    'button_padding' => self::pad(18, 40),
                    'button_background_hover_color' => $this->colors()['secondary'],
                    'custom_css' => 'selector .elementor-button:hover{transform:translateY(-2px);box-shadow:0 12px 32px rgba(45,106,79,.4);}',
                ]),
                self::button($c['hero_ghost_cta'] ?? 'Learn More', '#about', [
                    'button_type' => 'default',
                    'background_color' => 'transparent',
                    'button_text_color' => 'rgba(255,255,255,0.8)',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['body'],
                    'typography_font_size' => ['size' => 15, 'unit' => 'px'],
                    'typography_font_weight' => '600',
                    'border_border' => 'solid',
                    'border_width' => self::pad(1),
                    'border_color' => 'rgba(255,255,255,0.3)',
                    'border_radius' => self::radius(6),
                    'button_padding' => self::pad(17, 34),
                ]),
            ]),
        ]);

        // ─── ABOUT PREVIEW ───
        $credItems = '';
        foreach ($c['credentials'] ?? [
            'Licensed and fully insured professionals',
            'Award-winning craftsmanship and design',
            'Trusted by hundreds of satisfied clients',
        ] as $cred) {
            $credItems .= '<div class="forest-cred-item"><span class="leaf">✦</span><span>' . e($cred) . '</span></div>';
        }

        $pillarItems = '';
        foreach ($c['pillars'] ?? [
            ['icon' => '🏠', 'title' => 'Quality Materials', 'desc' => 'Only the finest materials for lasting results.'],
            ['icon' => '📐', 'title' => 'Precise Planning', 'desc' => 'Detailed planning ensures smooth execution.'],
            ['icon' => '🤝', 'title' => 'Client Focused', 'desc' => 'Your vision and satisfaction are our priority.'],
        ] as $pillar) {
            $pillarItems .= '<div class="forest-pillar"><div class="forest-pillar-ico">' . $pillar['icon'] . '</div><div><h4>' . e($pillar['title']) . '</h4><p>' . e($pillar['desc']) . '</p></div></div>';
        }

        $sections[] = self::twoCol(
            [self::image($aboutImg, [
                'custom_css' => 'selector img{width:100%;height:100%;min-height:400px;object-fit:cover;border-radius:8px;transition:transform 8s ease;} selector:hover img{transform:scale(1.03);}',
            ])],
            [
                $this->eyebrow($c['about_eyebrow'] ?? 'About Us'),
                $this->headline($aboutTitle, 'h2', ['_margin' => self::margin(0, 0, 12, 0)]),
                $this->bodyText($aboutText),
                $this->bodyText($aboutText2),
                self::html('<div class="forest-cred sr d2"><h4>' . e($c['cred_title'] ?? '✦ Why Choose Us') . '</h4>' . $credItems . '</div>'),
                self::html('<div class="sr d3" style="border:1px solid var(--forest-border);border-radius:8px;overflow:hidden;">' . $pillarItems . '</div>'),
            ],
            50,
            ['padding' => self::pad(0), '_element_id' => 'about'],
            ['padding' => self::pad(0), 'css_classes' => 'sr'],
            [
                'padding' => self::pad(80, 60, 80, 60),
                'background_background' => 'classic',
                'background_color' => $this->colors()['surface'],
                'flex_justify_content' => 'center',
                'css_classes' => 'sr d2',
            ]
        );

        // ─── SERVICES ───
        $serviceCards = [];
        foreach ($services as $i => $svc) {
            $serviceCards[] = self::container([
                'content_width' => 'full-width',
                'flex_direction' => 'column',
                'width' => ['size' => 33.33, 'unit' => '%'],
                'padding' => self::pad(40, 32),
                'background_background' => 'classic',
                'background_color' => $this->colors()['surface'],
                'border_border' => 'solid',
                'border_width' => self::pad(1),
                'border_color' => $this->colors()['border'],
                'border_radius' => self::radius(8),
                'css_classes' => 'forest-card sr d' . ($i + 1),
            ], [
                self::html('<div class="forest-icon">' . ($svc['icon'] ?? '🏠') . '</div>'),
                self::heading($svc['title'], 'h3', [
                    'title_color' => $this->colors()['text'],
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['heading'],
                    'typography_font_size' => ['size' => 22, 'unit' => 'px'],
                    'typography_font_weight' => '700',
                    '_margin' => self::margin(0, 0, 10, 0),
                ]),
                $this->bodyText($svc['desc'], ['typography_font_size' => ['size' => 15, 'unit' => 'px']]),
            ]);
        }

        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface2'],
        ], [
            $this->eyebrow($c['services_eyebrow'] ?? 'Our Services'),
            $this->headline($c['services_title'] ?? 'What We Do', 'h2'),
            $this->bodyText($c['services_subtitle'] ?? 'Expert solutions tailored to your project needs.', [
                '_margin' => self::margin(0),
                'custom_css' => 'selector{max-width:560px;}',
            ]),
            self::container([
                'flex_direction' => 'row',
                'content_width' => 'full-width',
                'gap' => ['size' => 24, 'unit' => 'px'],
                '_margin' => self::margin(48, 0, 0, 0),
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
                'padding' => self::pad(36, 24),
                'css_classes' => 'forest-bcard sr d' . ($i + 1),
            ], [
                self::html('<div class="forest-icon" style="margin-left:auto;margin-right:auto;">' . ($b['icon'] ?? '⚡') . '</div>'),
                self::heading($b['title'], 'h3', [
                    'title_color' => $this->colors()['text'],
                    'align' => 'center',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['heading'],
                    'typography_font_size' => ['size' => 18, 'unit' => 'px'],
                    'typography_font_weight' => '700',
                    '_margin' => self::margin(0, 0, 8, 0),
                ]),
                $this->bodyText($b['desc'], [
                    'align' => 'center',
                    'typography_font_size' => ['size' => 14, 'unit' => 'px'],
                ]),
            ]);
        }

        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['bg'],
        ], [
            $this->eyebrow($c['benefits_eyebrow'] ?? 'Why Choose Us'),
            $this->headline($c['benefits_title'] ?? 'Built on Trust', 'h2'),
            self::container([
                'flex_direction' => 'row',
                'content_width' => 'full-width',
                'gap' => ['size' => 24, 'unit' => 'px'],
                '_margin' => self::margin(48, 0, 0, 0),
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
                'padding' => self::pad(48, 20),
                'background_background' => 'classic',
                'background_color' => $this->colors()['surface'],
                'border_border' => 'solid',
                'border_width' => self::pad(0, 1, 0, 0),
                'border_color' => $this->colors()['border'],
                'css_classes' => 'forest-stat sr d' . ($i + 1),
            ], [
                self::html('<div class="forest-stat-n" data-count="' . $s['number'] . '" data-suffix="' . ($s['suffix'] ?? '') . '">' . $s['number'] . ($s['suffix'] ?? '') . '</div>'),
                self::html('<div class="forest-stat-l">' . e($s['label']) . '</div>'),
            ]);
        }

        $sections[] = self::container([
            'content_width' => 'full-width',
            'flex_direction' => 'row',
            'gap' => ['size' => 0, 'unit' => 'px'],
            'padding' => self::pad(0, 64),
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface'],
            'border_border' => 'solid',
            'border_width' => self::pad(1, 0),
            'border_color' => $this->colors()['border'],
        ], $statElements);

        // ─── TESTIMONIALS ───
        $featTest = $testimonials[0] ?? $testimonials[array_key_first($testimonials)];
        $sideTests = array_slice($testimonials, 1, 2);

        $featCard = self::container([
            'content_width' => 'full-width',
            'flex_direction' => 'column',
            'flex_justify_content' => 'space-between',
            'padding' => self::pad(40),
            'css_classes' => 'forest-tcard forest-tcard-feat sr',
        ], [
            self::container(['content_width' => 'full-width', 'flex_direction' => 'column'], [
                self::html('<div class="forest-quote-mark">"</div>'),
                self::html('<div class="forest-stars">★★★★★</div>'),
                self::textEditor('' . e($featTest['quote']) . '', [
                    'text_color' => $this->colors()['text'],
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['body'],
                    'typography_font_size' => ['size' => 17, 'unit' => 'px'],
                    'typography_line_height' => ['size' => 1.8, 'unit' => 'em'],
                    '_margin' => self::margin(0, 0, 28, 0),
                ]),
            ]),
            self::html('<div style="display:flex;align-items:center;gap:14px;"><div style="width:48px;height:48px;border-radius:50%;background:rgba(45,106,79,.1);display:flex;align-items:center;justify-content:center;font-family:\'Merriweather\',serif;font-size:16px;font-weight:700;color:var(--forest-primary);">' . e($featTest['initials'] ?? 'AB') . '</div><div><h5 style="font-family:\'Merriweather\',serif;font-size:15px;font-weight:700;color:var(--forest-text);margin:0;">' . e($featTest['name']) . '</h5><span style="font-size:13px;color:var(--forest-muted);">' . e($featTest['role']) . '</span></div></div>'),
        ]);

        $sideCards = [];
        foreach ($sideTests as $i => $t) {
            $sideCards[] = self::container([
                'content_width' => 'full-width',
                'flex_direction' => 'column',
                'padding' => self::pad(36),
                'css_classes' => 'forest-tcard sr d' . ($i + 1),
            ], [
                self::html('<div class="forest-stars">★★★★★</div>'),
                self::textEditor('"' . e($t['quote']) . '"', [
                    'text_color' => $this->colors()['muted'],
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['body'],
                    'typography_font_size' => ['size' => 15, 'unit' => 'px'],
                    'typography_line_height' => ['size' => 1.8, 'unit' => 'em'],
                    '_margin' => self::margin(0, 0, 24, 0),
                ]),
                self::html('<div style="display:flex;align-items:center;gap:12px;"><div style="width:42px;height:42px;border-radius:50%;background:rgba(45,106,79,.08);display:flex;align-items:center;justify-content:center;font-family:\'Merriweather\',serif;font-size:14px;font-weight:700;color:var(--forest-primary);">' . e($t['initials'] ?? 'AB') . '</div><div><h5 style="font-family:\'Merriweather\',serif;font-size:14px;font-weight:700;color:var(--forest-text);margin:0;">' . e($t['name']) . '</h5><span style="font-size:12px;color:var(--forest-muted);">' . e($t['role']) . '</span></div></div>'),
            ]);
        }

        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface2'],
        ], [
            $this->eyebrow($c['testimonials_eyebrow'] ?? 'Testimonials'),
            $this->headline($c['testimonials_title'] ?? 'What Our Clients Say', 'h2', [
                '_margin' => self::margin(0, 0, 48, 0),
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
                    'gap' => ['size' => 20, 'unit' => 'px'],
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
            'min_height' => ['size' => 500, 'unit' => 'px'],
            'padding' => self::pad(100, 64),
            'background_background' => 'classic',
            'background_color' => $this->colors()['primary'],
            '_element_id' => 'cta',
            'css_classes' => 'forest-cta',
        ], [
            self::html('<p class="eyebrow" style="color:' . $this->colors()['accent'] . ';">' . e($c['cta_eyebrow'] ?? 'Ready to Start?') . '</p>'),
            self::heading($c['cta_title'] ?? 'Let\'s Build Something Great Together', 'h2', array_merge(
                self::responsiveSize(48, 36, 28),
                [
                    'title_color' => '#FFFFFF',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['heading'],
                    'typography_font_weight' => '900',
                    'typography_line_height' => ['size' => 1.2, 'unit' => 'em'],
                    'align' => 'center',
                    '_margin' => self::margin(16, 0, 20, 0),
                ]
            )),
            $this->bodyText($c['cta_text'] ?? 'Get in touch today for a free consultation and discover how we can help bring your vision to life.', [
                'align' => 'center',
                'text_color' => 'rgba(255,255,255,0.75)',
                'typography_font_size' => ['size' => 17, 'unit' => 'px'],
                'custom_css' => 'selector{max-width:560px;margin-left:auto;margin-right:auto;}',
                '_margin' => self::margin(0, 0, 40, 0),
            ]),
            self::container([
                'flex_direction' => 'row',
                'flex_justify_content' => 'center',
                'gap' => ['size' => 14, 'unit' => 'px'],
                'content_width' => 'full-width',
                'css_classes' => 'sr d2',
            ], [
                self::button($c['cta_button'] ?? 'Get Started', '#contact', [
                    'button_type' => 'default',
                    'background_color' => '#FFFFFF',
                    'button_text_color' => $this->colors()['primary'],
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['body'],
                    'typography_font_size' => ['size' => 14, 'unit' => 'px'],
                    'typography_font_weight' => '600',
                    'border_radius' => self::radius(6),
                    'button_padding' => self::pad(16, 36),
                ]),
                self::button($c['cta_ghost'] ?? 'Contact Us', '#contact', [
                    'button_type' => 'default',
                    'background_color' => 'transparent',
                    'button_text_color' => '#FFFFFF',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['body'],
                    'typography_font_size' => ['size' => 14, 'unit' => 'px'],
                    'typography_font_weight' => '600',
                    'border_border' => 'solid',
                    'border_width' => self::pad(1),
                    'border_color' => 'rgba(255,255,255,0.4)',
                    'border_radius' => self::radius(6),
                    'button_padding' => self::pad(15, 34),
                ]),
            ]),
        ]);

        // Back to top
        $sections[] = self::html('<a href="#hero" id="forest-btt">↑</a>');

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
            'background_color' => $this->colors()['surface2'],
        ], [
            $this->eyebrow('About Us'),
            $this->headline($c['about_title'] ?? 'Our Story', 'h1', array_merge(
                self::responsiveSize(56, 42, 32),
                ['custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .2s forwards;}']
            )),
            $this->bodyText($c['about_text'] ?? 'Dedicated to excellence since day one.', [
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .4s forwards;max-width:560px;}',
            ]),
        ]);

        // Two-col about
        $sections[] = self::twoCol(
            [self::image($img['about'] ?? '', [
                'custom_css' => 'selector img{width:100%;min-height:400px;object-fit:cover;border-radius:8px;}',
            ])],
            [
                $this->eyebrow('Who We Are'),
                $this->headline($c['about_subtitle'] ?? 'Experience Meets Excellence', 'h2', [
                    'typography_font_size' => ['size' => 40, 'unit' => 'px'],
                ]),
                $this->bodyText($c['about_text'] ?? 'We are a team of dedicated professionals.'),
                $this->bodyText($c['about_text2'] ?? 'With years of experience delivering outstanding results for our clients.'),
                $this->ctaButton($c['about_cta'] ?? 'Our Services', '/services/', [
                    '_margin' => self::margin(20, 0, 0, 0),
                ]),
            ],
            50,
            ['padding' => self::pad(100, 64), 'background_background' => 'classic', 'background_color' => $this->colors()['surface']],
            ['css_classes' => 'sr'],
            ['css_classes' => 'sr d2', 'flex_justify_content' => 'center']
        );

        // Stats
        $stats = $c['stats'] ?? [
            ['number' => '500', 'suffix' => '+', 'label' => 'Projects'],
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
                'padding' => self::pad(48, 20),
                'background_background' => 'classic',
                'background_color' => $this->colors()['surface'],
                'css_classes' => 'forest-stat sr d' . ($i + 1),
            ], [
                self::html('<div class="forest-stat-n" data-count="' . $s['number'] . '" data-suffix="' . ($s['suffix'] ?? '') . '">' . $s['number'] . ($s['suffix'] ?? '') . '</div>'),
                self::html('<div class="forest-stat-l">' . e($s['label']) . '</div>'),
            ]);
        }
        $sections[] = self::container([
            'content_width' => 'full-width',
            'flex_direction' => 'row',
            'gap' => ['size' => 0, 'unit' => 'px'],
            'padding' => self::pad(0, 64),
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface'],
            'border_border' => 'solid',
            'border_width' => self::pad(1, 0),
            'border_color' => $this->colors()['border'],
        ], $statEls);

        // Values section
        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['bg'],
        ], [
            $this->eyebrow('Our Values'),
            $this->headline($c['values_title'] ?? 'What Drives Us', 'h2'),
            $this->bodyText($c['values_text'] ?? 'Core values that define everything we do — integrity, quality, and commitment to our clients.', [
                '_margin' => self::margin(0, 0, 50, 0),
                'custom_css' => 'selector{max-width:560px;}',
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
            'background_color' => $this->colors()['surface2'],
        ], [
            $this->eyebrow('Our Services'),
            $this->headline($c['services_title'] ?? 'What We Offer', 'h1', array_merge(
                self::responsiveSize(56, 42, 32),
                ['custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .2s forwards;}']
            )),
            $this->bodyText($c['services_subtitle'] ?? 'Comprehensive solutions for every project need.', [
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .4s forwards;max-width:560px;}',
            ]),
        ]);

        // Service cards
        $services = $c['services'] ?? [
            ['icon' => '🏠', 'title' => 'Residential', 'desc' => 'Custom homes and renovations.'],
            ['icon' => '🏗️', 'title' => 'Commercial', 'desc' => 'Professional commercial builds.'],
            ['icon' => '🌿', 'title' => 'Landscaping', 'desc' => 'Beautiful outdoor spaces.'],
        ];

        $cards = [];
        foreach ($services as $i => $svc) {
            $cards[] = self::container([
                'content_width' => 'full-width',
                'flex_direction' => 'column',
                'padding' => self::pad(40, 32),
                'background_background' => 'classic',
                'background_color' => $this->colors()['surface'],
                'border_border' => 'solid',
                'border_width' => self::pad(1),
                'border_color' => $this->colors()['border'],
                'border_radius' => self::radius(8),
                'css_classes' => 'forest-card sr d' . min($i + 1, 4),
            ], [
                self::html('<div class="forest-icon">' . ($svc['icon'] ?? '🏠') . '</div>'),
                self::heading($svc['title'], 'h3', [
                    'title_color' => $this->colors()['text'],
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['heading'],
                    'typography_font_size' => ['size' => 24, 'unit' => 'px'],
                    'typography_font_weight' => '700',
                    '_margin' => self::margin(0, 0, 10, 0),
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
            'background_background' => 'classic',
            'background_color' => $this->colors()['primary'],
            'flex_align_items' => 'center',
            'css_classes' => 'forest-cta',
        ], [
            self::heading($c['cta_title'] ?? 'Ready to Get Started?', 'h2', [
                'title_color' => '#FFFFFF',
                'align' => 'center',
                'typography_typography' => 'custom',
                'typography_font_family' => $this->fonts()['heading'],
                'typography_font_weight' => '900',
                'typography_font_size' => ['size' => 40, 'unit' => 'px'],
                'typography_line_height' => ['size' => 1.2, 'unit' => 'em'],
            ]),
            $this->bodyText($c['cta_text'] ?? 'Contact us today for a free consultation and project estimate.', [
                'align' => 'center',
                'text_color' => 'rgba(255,255,255,0.75)',
                '_margin' => self::margin(0, 0, 30, 0),
            ]),
            self::button($c['cta_button'] ?? 'Contact Us', '/contact/', [
                'background_color' => '#FFFFFF',
                'button_text_color' => $this->colors()['primary'],
                'typography_typography' => 'custom',
                'typography_font_family' => $this->fonts()['body'],
                'typography_font_size' => ['size' => 14, 'unit' => 'px'],
                'typography_font_weight' => '600',
                'border_radius' => self::radius(6),
                'button_padding' => self::pad(16, 36),
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
            'background_color' => $this->colors()['surface2'],
        ], [
            $this->eyebrow('Our Work'),
            $this->headline($c['portfolio_title'] ?? 'Featured Projects', 'h1', array_merge(
                self::responsiveSize(56, 42, 32),
                ['custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .2s forwards;}']
            )),
            $this->bodyText($c['portfolio_subtitle'] ?? 'A showcase of our finest work and achievements.', [
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .4s forwards;max-width:560px;}',
            ]),
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
                'css_classes' => 'forest-photo sr d' . min($i + 1, 4),
                'custom_css' => 'selector{min-height:280px;}',
            ], [
                self::image($url, [
                    'custom_css' => 'selector img{width:100%;height:280px;object-fit:cover;border-radius:8px;}',
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
                    'gap' => ['size' => 20, 'unit' => 'px'],
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
            'background_color' => $this->colors()['surface2'],
        ], [
            $this->eyebrow('Contact'),
            $this->headline($c['contact_title'] ?? 'Get In Touch', 'h1', array_merge(
                self::responsiveSize(56, 42, 32),
                ['custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .2s forwards;}']
            )),
            $this->bodyText($c['contact_subtitle'] ?? 'We would love to hear from you. Reach out anytime.', [
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
                'css_classes' => 'forest-bcard sr d' . ($i + 1),
            ], [
                self::html('<div class="forest-icon" style="margin-left:auto;margin-right:auto;">' . $info['icon'] . '</div>'),
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
            'background_color' => $this->colors()['surface'],
            'flex_align_items' => 'center',
        ], [
            $this->eyebrow('Send a Message'),
            $this->headline('Drop Us A Line', 'h2', [
                'align' => 'center',
                'typography_font_size' => ['size' => 40, 'unit' => 'px'],
                '_margin' => self::margin(0, 0, 40, 0),
            ]),
            self::html('<form style="max-width:600px;width:100%;margin:0 auto;display:flex;flex-direction:column;gap:16px;">
<input type="text" placeholder="Your Name" style="padding:14px 20px;background:' . $this->colors()['bg'] . ';border:1px solid ' . $this->colors()['border'] . ';color:' . $this->colors()['text'] . ';font-family:\'' . $this->fonts()['body'] . '\',sans-serif;font-size:14px;border-radius:6px;outline:none;">
<input type="email" placeholder="Your Email" style="padding:14px 20px;background:' . $this->colors()['bg'] . ';border:1px solid ' . $this->colors()['border'] . ';color:' . $this->colors()['text'] . ';font-family:\'' . $this->fonts()['body'] . '\',sans-serif;font-size:14px;border-radius:6px;outline:none;">
<textarea rows="5" placeholder="Your Message" style="padding:14px 20px;background:' . $this->colors()['bg'] . ';border:1px solid ' . $this->colors()['border'] . ';color:' . $this->colors()['text'] . ';font-family:\'' . $this->fonts()['body'] . '\',sans-serif;font-size:14px;border-radius:6px;outline:none;resize:vertical;"></textarea>
<button type="submit" style="padding:16px 36px;background:' . $this->colors()['primary'] . ';color:#FFF;font-family:\'' . $this->fonts()['body'] . '\',sans-serif;font-size:14px;font-weight:600;border:none;border-radius:6px;cursor:pointer;transition:all .3s;">Send Message</button>
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

        return [self::html('<nav class="forest-nav" id="mainNav" style="position:fixed;top:0;left:0;right:0;z-index:1000;padding:0 64px;height:80px;display:flex;align-items:center;justify-content:space-between;transition:background .4s,height .3s,box-shadow .4s;background:transparent;">
<a href="/" style="font-family:\'' . $this->fonts()['heading'] . '\',serif;font-size:22px;font-weight:900;color:' . $this->colors()['text'] . ';text-decoration:none;">' . e($siteName) . '</a>
<ul style="display:flex;gap:0;list-style:none;position:absolute;left:50%;transform:translateX(-50%);padding:0;margin:0;">' . $navLinks . '</ul>
<a href="/contact/" style="padding:10px 24px;background:' . $this->colors()['primary'] . ';color:#FFF;font-family:\'' . $this->fonts()['body'] . '\',sans-serif;font-size:13px;font-weight:600;border-radius:6px;text-decoration:none;transition:all .3s;">Get a Quote</a>
</nav>
<style>
.forest-nav ul a{display:block;padding:8px 18px;font-size:13px;font-weight:500;color:' . $this->colors()['muted'] . ';text-decoration:none;transition:color .3s;position:relative;font-family:\'' . $this->fonts()['body'] . '\',sans-serif;}
.forest-nav ul a::after{content:\'\';position:absolute;bottom:4px;left:18px;right:18px;height:2px;background:' . $this->colors()['primary'] . ';transform:scaleX(0);transform-origin:center;transition:transform .3s;}
.forest-nav ul a:hover{color:' . $this->colors()['text'] . ';}
.forest-nav ul a:hover::after{transform:scaleX(1);}
.forest-nav.bg{background:rgba(255,255,255,.97)!important;backdrop-filter:blur(12px);height:64px!important;box-shadow:0 2px 20px rgba(27,27,24,.06);}
@media(max-width:1100px){.forest-nav ul{display:none!important;}.forest-nav{padding:0 24px!important;height:64px!important;background:rgba(255,255,255,.97)!important;backdrop-filter:blur(12px);box-shadow:0 2px 20px rgba(27,27,24,.06);}}
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

        $contactHtml = '<div class="forest-ci"><div class="forest-ci-i">📧</div><div><small>Email</small><span>' . e($email) . '</span></div></div>';
        if ($phone) {
            $contactHtml .= '<div class="forest-ci"><div class="forest-ci-i">📞</div><div><small>Phone</small><span>' . e($phone) . '</span></div></div>';
        }
        if ($address) {
            $contactHtml .= '<div class="forest-ci"><div class="forest-ci-i">📍</div><div><small>Location</small><span>' . e($address) . '</span></div></div>';
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
                'padding' => self::pad(72, 64, 56, 64),
                'border_border' => 'solid',
                'border_width' => self::pad(0, 0, 1, 0),
                'border_color' => $this->colors()['border'],
                'custom_css' => 'selector{display:grid;grid-template-columns:1.5fr 1fr 1fr;}',
            ], [
                self::html('<div><div style="font-family:\'' . $this->fonts()['heading'] . '\',serif;font-size:22px;font-weight:900;color:' . $this->colors()['text'] . ';margin-bottom:6px;">' . e($siteName) . '</div><div style="font-size:13px;font-weight:600;color:' . $this->colors()['primary'] . ';margin-bottom:16px;">' . e($contact['tagline'] ?? 'Quality You Can Trust') . '</div><p style="font-size:14px;color:' . $this->colors()['muted'] . ';line-height:1.7;max-width:280px;">' . e($contact['footer_text'] ?? 'Delivering exceptional craftsmanship and professional service for every project.') . '</p><div class="forest-social"><a href="#">📷</a><a href="#">🐦</a><a href="#">💼</a></div></div>'),
                self::html('<div class="forest-fc"><h4>Navigate</h4><ul>' . $navLinks . '</ul></div>'),
                self::html('<div class="forest-fc"><h4>Contact</h4>' . $contactHtml . '</div>'),
            ]),
            self::container([
                'flex_direction' => 'row',
                'flex_justify_content' => 'space-between',
                'flex_align_items' => 'center',
                'content_width' => 'full-width',
                'padding' => self::pad(18, 64),
            ], [
                self::textEditor('<p style="font-size:12px;color:' . $this->colors()['muted'] . ';">&copy; ' . date('Y') . ' <span style="color:' . $this->colors()['primary'] . ';">' . e($siteName) . '</span>. All rights reserved.</p>'),
                self::textEditor('<a href="#" style="font-size:12px;color:' . $this->colors()['muted'] . ';text-decoration:none;">Privacy Policy</a> &nbsp;&nbsp; <a href="#" style="font-size:12px;color:' . $this->colors()['muted'] . ';text-decoration:none;">Terms of Service</a>'),
            ]),
        ]);

        return $sections;
    }
}
