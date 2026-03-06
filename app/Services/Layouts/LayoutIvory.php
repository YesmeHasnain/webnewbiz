<?php

namespace App\Services\Layouts;

/**
 * Ivory -- Clean, professional light layout with sage green accent.
 * White/cream backgrounds, subtle shadows, clean borders.
 * Playfair Display headings (title case), Inter body.
 * Trustworthy aesthetic for Medical, Legal, Finance, Education.
 */
class LayoutIvory extends AbstractLayout
{
    public function slug(): string { return 'ivory'; }
    public function name(): string { return 'Ivory'; }
    public function description(): string { return 'Clean, professional light theme with sage green accent and elegant typography'; }
    public function bestFor(): array { return ['Medical', 'Legal', 'Finance', 'Education']; }
    public function isDark(): bool { return false; }

    public function colors(): array
    {
        return [
            'primary'   => '#1B4D3E',
            'secondary' => '#2D6A4F',
            'accent'    => '#D4A574',
            'bg'        => '#FAFAF8',
            'surface'   => '#FFFFFF',
            'surface2'  => '#F5F3EF',
            'text'      => '#1A1A1A',
            'muted'     => 'rgba(0,0,0,0.55)',
            'border'    => 'rgba(0,0,0,0.08)',
        ];
    }

    public function fonts(): array
    {
        return ['heading' => 'Playfair Display', 'body' => 'Inter'];
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
<link href="https://fonts.googleapis.com/css2?family={$hf}:wght@400;500;600;700;900&family={$bf}:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{--ivory-primary:{$c['primary']};--ivory-secondary:{$c['secondary']};--ivory-accent:{$c['accent']};--ivory-bg:{$c['bg']};--ivory-surface:{$c['surface']};--ivory-surface2:{$c['surface2']};--ivory-text:{$c['text']};--ivory-muted:{$c['muted']};--ivory-border:{$c['border']};}
body,body.elementor-template-canvas{background:var(--ivory-bg);color:var(--ivory-text);font-family:'{$f['body']}',sans-serif;overflow-x:hidden;margin:0;padding:0;}
.elementor-element,.elementor.elementor-2{font-family:'{$f['body']}',sans-serif;}
.elementor-widget{margin-bottom:0 !important;}
.e-con{--gap:0px;}
.e-con>.elementor-widget{width:100%;}

/* Animations */
@keyframes fadeUp{from{opacity:0;transform:translateY(28px)}to{opacity:1;transform:translateY(0)}}
@keyframes fadeIn{from{opacity:0}to{opacity:1}}
.sr{opacity:0;transform:translateY(36px);transition:opacity .85s ease,transform .85s ease;}
.sr.d1{transition-delay:.1s}.sr.d2{transition-delay:.2s}.sr.d3{transition-delay:.3s}.sr.d4{transition-delay:.45s}
.sr.in{opacity:1;transform:none;}

/* Eyebrow */
.eyebrow{display:inline-flex;align-items:center;gap:12px;font-size:11px;font-weight:600;letter-spacing:4px;text-transform:uppercase;color:var(--ivory-accent);margin-bottom:20px;}
.eyebrow::before{content:'';width:28px;height:2px;background:var(--ivory-accent);}

/* Cards */
.ivory-card{position:relative;overflow:hidden;border:1px solid var(--ivory-border);border-radius:8px;background:var(--ivory-surface);box-shadow:0 1px 3px rgba(0,0,0,0.04);transition:all .35s ease;cursor:pointer;}
.ivory-card:hover{transform:translateY(-4px);box-shadow:0 12px 32px rgba(0,0,0,0.08);}

/* Benefit Card */
.ivory-bcard{text-align:center;border:1px solid var(--ivory-border);border-radius:8px;background:var(--ivory-surface);box-shadow:0 1px 3px rgba(0,0,0,0.04);transition:all .35s ease;}
.ivory-bcard:hover{transform:translateY(-4px);box-shadow:0 12px 32px rgba(0,0,0,0.08);border-color:rgba(27,77,62,0.15);}

/* Testimonial Card */
.ivory-tcard{border:1px solid var(--ivory-border);border-radius:8px;background:var(--ivory-surface);box-shadow:0 1px 3px rgba(0,0,0,0.04);transition:all .35s ease;}
.ivory-tcard:hover{transform:translateY(-4px);box-shadow:0 12px 32px rgba(0,0,0,0.08);}
.ivory-tcard-feat{border-color:var(--ivory-primary)!important;border-width:2px;}
.ivory-tcard-feat:hover{box-shadow:0 20px 50px rgba(27,77,62,0.12);}

/* Stars */
.ivory-stars{color:var(--ivory-accent);letter-spacing:2px;font-size:14px;margin-bottom:16px;}

/* Stats */
.ivory-stat{text-align:center;}
.ivory-stat-n{font-family:'{$f['heading']}',serif;font-size:56px;font-weight:700;color:var(--ivory-primary);line-height:1;}
.ivory-stat-l{font-size:10px;letter-spacing:2px;text-transform:uppercase;color:rgba(0,0,0,0.35);margin-top:5px;}

/* Pricing */
.ivory-plan{border-radius:8px;overflow:hidden;box-shadow:0 8px 40px rgba(27,77,62,0.12);}
.sp-price{display:flex;align-items:flex-start;justify-content:center;gap:4px;margin-bottom:10px;}
.sp-cur{font-size:26px;font-weight:700;color:var(--ivory-primary);margin-top:14px;}
.sp-num{font-family:'{$f['heading']}',serif;font-size:108px;font-weight:700;line-height:1;color:var(--ivory-primary);letter-spacing:-4px;}
.sp-mo{font-size:16px;color:rgba(0,0,0,.4);align-self:flex-end;padding-bottom:14px;}

/* Credential block */
.ivory-cred{background:rgba(27,77,62,0.04);border:1px solid rgba(27,77,62,0.12);border-radius:8px;padding:24px 22px;margin:28px 0;}
.ivory-cred h4{font-family:'{$f['heading']}',serif;font-size:14px;font-weight:700;color:var(--ivory-primary);margin-bottom:14px;}
.ivory-cred-item{display:flex;align-items:flex-start;gap:10px;font-size:14px;color:rgba(0,0,0,0.6);line-height:1.5;margin-bottom:9px;}
.ivory-cred-item .star{color:var(--ivory-accent);flex-shrink:0;font-size:12px;margin-top:2px;}

/* Pillar rows */
.ivory-pillar{display:flex;align-items:center;gap:20px;padding:22px 24px;border-bottom:1px solid var(--ivory-border);transition:all .3s;cursor:pointer;}
.ivory-pillar:last-child{border-bottom:none;}
.ivory-pillar:hover{background:rgba(27,77,62,0.03);padding-left:32px;}
.ivory-pillar-ico{width:44px;height:44px;min-width:44px;background:rgba(27,77,62,0.06);border:1px solid rgba(27,77,62,0.12);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:20px;}
.ivory-pillar h4{font-family:'{$f['heading']}',serif;font-size:17px;font-weight:700;color:var(--ivory-text);margin-bottom:3px;}
.ivory-pillar p{font-size:13px;color:rgba(0,0,0,0.45);line-height:1.5;margin:0;}

/* Gallery photo hover */
.ivory-photo{overflow:hidden;position:relative;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.06);}
.ivory-photo img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .7s ease;}
.ivory-photo:hover img{transform:scale(1.04);}

/* Footer */
.ivory-fc h4{font-size:10px;font-weight:600;letter-spacing:4px;text-transform:uppercase;color:rgba(255,255,255,0.45);margin-bottom:22px;padding-bottom:14px;border-bottom:1px solid rgba(255,255,255,0.1);}
.ivory-fc ul{list-style:none;display:flex;flex-direction:column;gap:11px;padding:0;margin:0;}
.ivory-fc a{font-size:14px;color:rgba(255,255,255,0.45);text-decoration:none;display:flex;align-items:center;gap:8px;transition:color .3s;}
.ivory-fc a::before{content:'\203A';color:rgba(212,165,116,0.5);transition:color .3s;}
.ivory-fc a:hover{color:rgba(255,255,255,0.85);}
.ivory-fc a:hover::before{color:var(--ivory-accent);}
.ivory-social{display:flex;gap:8px;margin-top:24px;}
.ivory-social a{width:38px;height:38px;border:1px solid rgba(255,255,255,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:16px;text-decoration:none;color:rgba(255,255,255,0.35);transition:all .3s;}
.ivory-social a:hover{border-color:var(--ivory-accent);color:var(--ivory-accent);background:rgba(212,165,116,0.08);transform:translateY(-3px);}

/* Contact info block */
.ivory-ci{display:flex;align-items:flex-start;gap:10px;margin-bottom:14px;}
.ivory-ci-i{width:34px;height:34px;min-width:34px;background:rgba(212,165,116,0.1);border:1px solid rgba(212,165,116,0.2);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:14px;}
.ivory-ci small{display:block;font-size:9px;color:rgba(255,255,255,0.3);letter-spacing:2px;text-transform:uppercase;margin-bottom:2px;}
.ivory-ci span{font-size:13px;color:rgba(255,255,255,0.55);}

/* Badge */
.ivory-badge{display:inline-flex;align-items:center;gap:5px;margin-top:14px;padding:5px 12px;border-radius:50px;font-size:10px;font-weight:600;letter-spacing:1.5px;text-transform:uppercase;background:rgba(27,77,62,0.06);border:1px solid rgba(27,77,62,0.15);color:var(--ivory-primary);}

/* Step */
.ivory-step{border-bottom:1px solid var(--ivory-border);transition:all .3s;}
.ivory-step:first-child{border-top:1px solid var(--ivory-border);}

/* Back to top */
#ivory-btt{position:fixed;bottom:28px;right:28px;width:46px;height:46px;background:var(--ivory-primary);color:#FFF;border-radius:8px;font-size:20px;display:flex;align-items:center;justify-content:center;text-decoration:none;z-index:500;opacity:0;transform:translateY(12px);pointer-events:none;transition:all .4s;box-shadow:0 8px 24px rgba(27,77,62,0.25);}
#ivory-btt.show{opacity:1;transform:translateY(0);pointer-events:all;}
#ivory-btt:hover{background:var(--ivory-secondary);transform:translateY(-4px)!important;}

/* Responsive */
@media(max-width:1100px){.ivory-photo{min-height:200px;}}
@media(max-width:700px){.sp-features{grid-template-columns:1fr;}}
</style>
CSS;
    }

    public function buildGlobalJs(): string
    {
        return <<<'JS'
<script>
(function(){
const btt=document.getElementById('ivory-btt');
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
        $heroTitle = $c['hero_title'] ?? 'Professional Excellence, Trusted Results';
        $heroSub = $c['hero_subtitle'] ?? 'We deliver exceptional results with a commitment to quality, integrity, and personalized service.';
        $heroCta = $c['hero_cta'] ?? 'Get Started';
        $heroCtaUrl = $c['hero_cta_url'] ?? '#services';

        $aboutTitle = $c['about_title'] ?? 'Who We Are';
        $aboutText = $c['about_text'] ?? 'We are a team of dedicated professionals committed to delivering excellence in everything we do.';
        $aboutText2 = $c['about_text2'] ?? 'With years of experience and a passion for innovation, we help businesses achieve their goals.';

        $services = $c['services'] ?? [
            ['icon' => "\xe2\x9a\x96", 'title' => 'Expert Consultation', 'desc' => 'We develop comprehensive strategies tailored to your unique needs and objectives.'],
            ['icon' => "\xf0\x9f\x93\x8b", 'title' => 'Detailed Planning', 'desc' => 'Creating thorough, actionable plans that guide every step of the process.'],
            ['icon' => "\xf0\x9f\x93\x88", 'title' => 'Measurable Growth', 'desc' => 'Data-driven approaches that deliver tangible, measurable results for your organization.'],
        ];

        $benefits = $c['benefits'] ?? [
            ['icon' => "\xf0\x9f\x8f\x86", 'title' => 'Award Winning', 'desc' => 'Recognized for excellence in our industry with multiple awards and accolades.'],
            ['icon' => "\xe2\x9a\xa1", 'title' => 'Fast Delivery', 'desc' => 'Quick turnaround times without compromising on quality or attention to detail.'],
            ['icon' => "\xf0\x9f\xa4\x9d", 'title' => 'Dedicated Support', 'desc' => '24/7 customer support to ensure your complete satisfaction with our services.'],
            ['icon' => "\xf0\x9f\x92\xa1", 'title' => 'Innovation First', 'desc' => 'Cutting-edge solutions using the latest technology and industry best practices.'],
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
            'flex_justify_content' => 'center',
            'padding' => self::pad(0, 64, 100, 64),
            'min_height' => ['size' => 100, 'unit' => 'vh'],
            'background_background' => 'classic',
            'background_image' => ['url' => $heroImg, 'id' => ''],
            'background_position' => 'center center',
            'background_size' => 'cover',
            '_element_id' => 'hero',
            'custom_css' => "selector{position:relative;overflow:hidden;}
selector::before{content:'';position:absolute;inset:0;background:linear-gradient(to right,rgba(250,250,248,.95) 45%,rgba(250,250,248,.4) 100%),linear-gradient(to top,rgba(250,250,248,.98) 0%,rgba(250,250,248,.5) 50%,transparent 100%);z-index:0;}
selector>.e-con-inner,selector>.elementor-widget{position:relative;z-index:2;}",
        ], [
            self::html('<div style="display:inline-flex;align-items:center;gap:10px;margin-bottom:28px;opacity:0;animation:fadeUp .7s ease .2s forwards;"><span class="eyebrow" style="margin-bottom:0;">' . e($c['hero_eyebrow'] ?? 'Welcome to ' . $siteName) . '</span></div>'),

            $this->headline($heroTitle, 'h1', array_merge(
                self::responsiveSize(80, 56, 38),
                [
                    'title_color' => $this->colors()['text'],
                    'typography_text_transform' => 'none',
                    'typography_font_weight' => '700',
                    'typography_line_height' => ['size' => 1.1, 'unit' => 'em'],
                    'typography_letter_spacing' => ['size' => -1, 'unit' => 'px'],
                    '_margin' => self::margin(0, 0, 32, 0),
                    'custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .35s forwards;max-width:700px;}',
                ]
            )),

            self::textEditor('<p>' . e($heroSub) . '</p>', [
                'text_color' => $this->colors()['muted'],
                'typography_typography' => 'custom',
                'typography_font_family' => $this->fonts()['body'],
                'typography_font_size' => ['size' => 17, 'unit' => 'px'],
                'typography_line_height' => ['size' => 1.75, 'unit' => 'em'],
                'typography_font_weight' => '400',
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .55s forwards;max-width:520px;}',
            ]),

            self::container([
                'flex_direction' => 'row',
                'flex_align_items' => 'center',
                'gap' => ['size' => 16, 'unit' => 'px'],
                'content_width' => 'full-width',
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .7s forwards;}',
            ], [
                $this->ctaButton($heroCta, $heroCtaUrl, [
                    'border_radius' => self::radius(6),
                    'custom_css' => 'selector .elementor-button:hover{transform:translateY(-3px);box-shadow:0 16px 40px rgba(27,77,62,.2);}',
                ]),
                $this->ghostButton($c['hero_ghost_cta'] ?? 'Learn More', '#about', [
                    'border_radius' => self::radius(6),
                ]),
            ]),
        ]);

        // --- ABOUT PREVIEW ---
        $credItems = '';
        foreach ($c['credentials'] ?? [
            'Award-winning team with proven track record',
            'Industry-leading expertise and innovation',
            'Trusted by hundreds of organizations worldwide',
        ] as $cred) {
            $credItems .= '<div class="ivory-cred-item"><span class="star">' . "\xe2\x9c\x93" . '</span><span>' . e($cred) . '</span></div>';
        }

        $pillarItems = '';
        foreach ($c['pillars'] ?? [
            ['icon' => "\xf0\x9f\x8e\xaf", 'title' => 'Expert Guidance', 'desc' => 'Personalized strategies from industry veterans.'],
            ['icon' => "\xf0\x9f\x93\xb1", 'title' => 'Modern Solutions', 'desc' => 'Cutting-edge technology tailored to your needs.'],
            ['icon' => "\xf0\x9f\x8f\x86", 'title' => 'Proven Results', 'desc' => 'Track record of delivering measurable outcomes.'],
        ] as $pillar) {
            $pillarItems .= '<div class="ivory-pillar"><div class="ivory-pillar-ico">' . $pillar['icon'] . '</div><div><h4>' . e($pillar['title']) . '</h4><p>' . e($pillar['desc']) . '</p></div></div>';
        }

        $sections[] = self::twoCol(
            // Left: image
            [self::image($aboutImg, [
                'custom_css' => 'selector img{width:100%;height:100%;min-height:400px;object-fit:cover;border-radius:8px;transition:transform 8s ease;} selector:hover img{transform:scale(1.04);}',
            ])],
            // Right: text
            [
                $this->eyebrow($c['about_eyebrow'] ?? 'About Us'),
                $this->headline($aboutTitle, 'h2', [
                    'title_color' => $this->colors()['text'],
                    'typography_text_transform' => 'none',
                    'typography_font_weight' => '700',
                    'typography_font_size' => ['size' => 48, 'unit' => 'px'],
                    'typography_line_height' => ['size' => 1.15, 'unit' => 'em'],
                    '_margin' => self::margin(0, 0, 8, 0),
                ]),
                $this->bodyText($aboutText),
                $this->bodyText($aboutText2),
                self::html('<div class="ivory-cred sr d2"><h4>' . e($c['cred_title'] ?? 'Why Choose Us') . '</h4>' . $credItems . '</div>'),
                self::html('<div class="sr d3" style="border:1px solid var(--ivory-border);border-radius:8px;overflow:hidden;">' . $pillarItems . '</div>'),
            ],
            50,
            ['padding' => self::pad(100, 64), '_element_id' => 'about', 'background_background' => 'classic', 'background_color' => $this->colors()['bg']],
            ['padding' => self::pad(0), 'css_classes' => 'sr'],
            [
                'padding' => self::pad(80, 60, 80, 60),
                'flex_justify_content' => 'center',
                'css_classes' => 'sr d2',
            ]
        );

        // --- SERVICES ---
        $serviceCards = [];
        foreach ($services as $i => $svc) {
            $serviceCards[] = self::container([
                'content_width' => 'full-width',
                'flex_direction' => 'column',
                'width' => ['size' => 33.33, 'unit' => '%'],
                'padding' => self::pad(40, 32),
                'css_classes' => 'ivory-card sr d' . ($i + 1),
            ], [
                self::textEditor('<span style="font-size:36px;display:block;margin-bottom:18px;">' . ($svc['icon'] ?? '') . '</span>'),
                self::heading($svc['title'], 'h3', [
                    'title_color' => $this->colors()['text'],
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['heading'],
                    'typography_font_size' => ['size' => 22, 'unit' => 'px'],
                    'typography_font_weight' => '700',
                    'typography_text_transform' => 'none',
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
            $this->headline($c['services_title'] ?? 'What We Do', 'h2', [
                'title_color' => $this->colors()['text'],
                'typography_text_transform' => 'none',
                'typography_font_weight' => '700',
            ]),
            $this->bodyText($c['services_subtitle'] ?? 'Expert solutions tailored to your needs.', [
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

        // --- BENEFITS ---
        $benefitCards = [];
        foreach ($benefits as $i => $b) {
            $benefitCards[] = self::container([
                'content_width' => 'full-width',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'width' => ['size' => 25, 'unit' => '%'],
                'padding' => self::pad(38, 28),
                'css_classes' => 'ivory-bcard sr d' . ($i + 1),
            ], [
                self::textEditor('<span style="font-size:38px;display:block;margin-bottom:16px;">' . ($b['icon'] ?? '') . '</span>'),
                self::heading($b['title'], 'h3', [
                    'title_color' => $this->colors()['text'],
                    'align' => 'center',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['heading'],
                    'typography_font_size' => ['size' => 20, 'unit' => 'px'],
                    'typography_font_weight' => '700',
                    'typography_text_transform' => 'none',
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
            $this->headline($c['benefits_title'] ?? 'Our Advantages', 'h2', [
                'title_color' => $this->colors()['text'],
                'typography_text_transform' => 'none',
                'typography_font_weight' => '700',
            ]),
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
                'border_border' => 'solid',
                'border_width' => self::pad(0, 1, 0, 0),
                'border_color' => $this->colors()['border'],
                'css_classes' => 'ivory-stat sr d' . ($i + 1),
            ], [
                self::html('<div class="ivory-stat-n" data-count="' . $s['number'] . '" data-suffix="' . ($s['suffix'] ?? '') . '">' . $s['number'] . ($s['suffix'] ?? '') . '</div>'),
                self::html('<div class="ivory-stat-l">' . e($s['label']) . '</div>'),
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

        // --- TESTIMONIALS ---
        $featTest = $testimonials[0] ?? $testimonials[array_key_first($testimonials)];
        $sideTests = array_slice($testimonials, 1, 2);

        $featCard = self::container([
            'content_width' => 'full-width',
            'flex_direction' => 'column',
            'flex_justify_content' => 'space-between',
            'padding' => self::pad(44),
            'background_background' => 'classic',
            'background_color' => $this->colors()['primary'],
            'border_radius' => self::radius(8),
            'css_classes' => 'ivory-tcard ivory-tcard-feat sr',
        ], [
            self::container(['content_width' => 'full-width', 'flex_direction' => 'column'], [
                self::html('<div class="ivory-stars" style="color:#FFD700;">&#9733;&#9733;&#9733;&#9733;&#9733;</div>'),
                self::textEditor('&ldquo;' . e($featTest['quote']) . '&rdquo;', [
                    'text_color' => 'rgba(255,255,255,0.9)',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['body'],
                    'typography_font_size' => ['size' => 18, 'unit' => 'px'],
                    'typography_line_height' => ['size' => 1.85, 'unit' => 'em'],
                    '_margin' => self::margin(0, 0, 28, 0),
                ]),
            ]),
            self::html('<div style="display:flex;align-items:center;gap:14px;"><div style="width:48px;height:48px;border-radius:50%;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-family:\'Playfair Display\',serif;font-size:18px;font-weight:700;color:#FFF;">' . e($featTest['initials'] ?? 'AB') . '</div><div><h5 style="font-family:\'Playfair Display\',serif;font-size:17px;font-weight:700;color:#FFF;margin:0;">' . e($featTest['name']) . '</h5><span style="font-size:12px;color:rgba(255,255,255,.55);">' . e($featTest['role']) . '</span></div></div>'),
        ]);

        $sideCards = [];
        foreach ($sideTests as $i => $t) {
            $sideCards[] = self::container([
                'content_width' => 'full-width',
                'flex_direction' => 'column',
                'padding' => self::pad(44),
                'css_classes' => 'ivory-tcard sr d' . ($i + 1),
            ], [
                self::html('<div class="ivory-stars" style="color:#D4A574;">&#9733;&#9733;&#9733;&#9733;&#9733;</div>'),
                self::textEditor('&ldquo;' . e($t['quote']) . '&rdquo;', [
                    'text_color' => $this->colors()['muted'],
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['body'],
                    'typography_font_size' => ['size' => 15, 'unit' => 'px'],
                    'typography_line_height' => ['size' => 1.85, 'unit' => 'em'],
                    '_margin' => self::margin(0, 0, 28, 0),
                ]),
                self::html('<div style="display:flex;align-items:center;gap:14px;"><div style="width:48px;height:48px;border-radius:50%;background:rgba(27,77,62,.08);display:flex;align-items:center;justify-content:center;font-family:\'Playfair Display\',serif;font-size:18px;font-weight:700;color:var(--ivory-primary);">' . e($t['initials'] ?? 'AB') . '</div><div><h5 style="font-family:\'Playfair Display\',serif;font-size:17px;font-weight:700;color:var(--ivory-text);margin:0;">' . e($t['name']) . '</h5><span style="font-size:12px;color:rgba(0,0,0,.4);">' . e($t['role']) . '</span></div></div>'),
            ]);
        }

        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface2'],
        ], [
            $this->eyebrow($c['testimonials_eyebrow'] ?? 'Testimonials'),
            $this->headline($c['testimonials_title'] ?? 'What Our Clients Say', 'h2', [
                'title_color' => $this->colors()['text'],
                'typography_text_transform' => 'none',
                'typography_font_weight' => '700',
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
        ], [
            $this->eyebrow($c['cta_eyebrow'] ?? 'Ready to Start?'),
            $this->headline($c['cta_title'] ?? 'Let Us Help You Succeed', 'h2', array_merge(
                self::responsiveSize(64, 48, 34),
                [
                    'title_color' => '#FFFFFF',
                    'typography_text_transform' => 'none',
                    'typography_font_weight' => '700',
                    'typography_line_height' => ['size' => 1.15, 'unit' => 'em'],
                    'align' => 'center',
                    '_margin' => self::margin(20, 0, 24, 0),
                ]
            )),
            $this->bodyText($c['cta_text'] ?? 'Get in touch today and discover how we can help your organization grow.', [
                'align' => 'center',
                'text_color' => 'rgba(255,255,255,0.7)',
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
                self::button($c['cta_button'] ?? 'Get Started', '#contact', [
                    'background_color' => '#FFFFFF',
                    'button_text_color' => $this->colors()['primary'],
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['heading'],
                    'typography_font_size' => ['size' => 14, 'unit' => 'px'],
                    'typography_font_weight' => '700',
                    'typography_letter_spacing' => ['size' => 2, 'unit' => 'px'],
                    'typography_text_transform' => 'uppercase',
                    'border_radius' => self::radius(6),
                    'button_padding' => self::pad(16, 44),
                    'button_background_hover_color' => '#F5F3EF',
                ]),
                self::button($c['cta_ghost'] ?? 'Contact Us', '#contact', [
                    'background_color' => 'transparent',
                    'button_text_color' => 'rgba(255,255,255,0.8)',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['heading'],
                    'typography_font_size' => ['size' => 14, 'unit' => 'px'],
                    'typography_font_weight' => '600',
                    'typography_letter_spacing' => ['size' => 2, 'unit' => 'px'],
                    'typography_text_transform' => 'uppercase',
                    'border_border' => 'solid',
                    'border_width' => self::pad(1),
                    'border_color' => 'rgba(255,255,255,0.3)',
                    'border_radius' => self::radius(6),
                    'button_padding' => self::pad(15, 36),
                ]),
            ]),
        ]);

        // Back to top
        $sections[] = self::html('<a href="#hero" id="ivory-btt">&uarr;</a>');

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
            $this->headline($c['about_title'] ?? 'Our Story', 'h1', array_merge(
                self::responsiveSize(72, 52, 38),
                [
                    'title_color' => $this->colors()['text'],
                    'typography_text_transform' => 'none',
                    'typography_font_weight' => '700',
                    'custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .2s forwards;}',
                ]
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
                $this->headline($c['about_subtitle'] ?? 'Passion Meets Expertise', 'h2', [
                    'title_color' => $this->colors()['text'],
                    'typography_text_transform' => 'none',
                    'typography_font_weight' => '700',
                    'typography_font_size' => ['size' => 48, 'unit' => 'px'],
                ]),
                $this->bodyText($c['about_text'] ?? 'We are a team of dedicated professionals.'),
                $this->bodyText($c['about_text2'] ?? 'With years of experience delivering outstanding results.'),
                $this->ctaButton($c['about_cta'] ?? 'Our Services', '#services', [
                    '_margin' => self::margin(20, 0, 0, 0),
                    'border_radius' => self::radius(6),
                ]),
            ],
            50,
            ['padding' => self::pad(100, 64), 'background_background' => 'classic', 'background_color' => $this->colors()['bg']],
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
                'padding' => self::pad(50, 20),
                'background_background' => 'classic',
                'background_color' => $this->colors()['surface'],
                'border_border' => 'solid',
                'border_width' => self::pad(0, 1, 0, 0),
                'border_color' => $this->colors()['border'],
                'css_classes' => 'ivory-stat sr d' . ($i + 1),
            ], [
                self::html('<div class="ivory-stat-n" data-count="' . $s['number'] . '" data-suffix="' . ($s['suffix'] ?? '') . '">' . $s['number'] . ($s['suffix'] ?? '') . '</div>'),
                self::html('<div class="ivory-stat-l">' . e($s['label']) . '</div>'),
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
            'background_color' => $this->colors()['surface2'],
        ], [
            $this->eyebrow('Our Values'),
            $this->headline($c['values_title'] ?? 'What Drives Us', 'h2', [
                'title_color' => $this->colors()['text'],
                'typography_text_transform' => 'none',
                'typography_font_weight' => '700',
            ]),
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
            $this->headline($c['services_title'] ?? 'What We Offer', 'h1', array_merge(
                self::responsiveSize(72, 52, 38),
                [
                    'title_color' => $this->colors()['text'],
                    'typography_text_transform' => 'none',
                    'typography_font_weight' => '700',
                    'custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .2s forwards;}',
                ]
            )),
            $this->bodyText($c['services_subtitle'] ?? 'Comprehensive solutions for every challenge.', [
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .4s forwards;max-width:560px;}',
            ]),
        ]);

        // Service cards
        $services = $c['services'] ?? [
            ['icon' => "\xe2\x9a\x96", 'title' => 'Consultation', 'desc' => 'Comprehensive strategic planning.'],
            ['icon' => "\xf0\x9f\x93\x8b", 'title' => 'Planning', 'desc' => 'Detailed, actionable plans.'],
            ['icon' => "\xf0\x9f\x93\x88", 'title' => 'Growth', 'desc' => 'Data-driven growth strategies.'],
        ];

        $cards = [];
        foreach ($services as $i => $svc) {
            $cards[] = self::container([
                'content_width' => 'full-width',
                'flex_direction' => 'column',
                'padding' => self::pad(40, 32),
                'css_classes' => 'ivory-card sr d' . min($i + 1, 4),
            ], [
                self::textEditor('<span style="font-size:36px;display:block;margin-bottom:18px;">' . ($svc['icon'] ?? '') . '</span>'),
                self::heading($svc['title'], 'h3', [
                    'title_color' => $this->colors()['text'],
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['heading'],
                    'typography_font_size' => ['size' => 24, 'unit' => 'px'],
                    'typography_font_weight' => '700',
                    'typography_text_transform' => 'none',
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
            'background_background' => 'classic',
            'background_color' => $this->colors()['primary'],
            'flex_align_items' => 'center',
        ], [
            $this->headline($c['cta_title'] ?? 'Ready to Start?', 'h2', [
                'title_color' => '#FFFFFF',
                'typography_text_transform' => 'none',
                'typography_font_weight' => '700',
                'align' => 'center',
            ]),
            $this->bodyText($c['cta_text'] ?? 'Contact us today for a free consultation.', [
                'align' => 'center',
                'text_color' => 'rgba(255,255,255,0.7)',
                '_margin' => self::margin(0, 0, 30, 0),
            ]),
            self::button($c['cta_button'] ?? 'Contact Us', '#contact', [
                'background_color' => '#FFFFFF',
                'button_text_color' => $this->colors()['primary'],
                'typography_typography' => 'custom',
                'typography_font_family' => $this->fonts()['heading'],
                'typography_font_size' => ['size' => 14, 'unit' => 'px'],
                'typography_font_weight' => '700',
                'typography_letter_spacing' => ['size' => 2, 'unit' => 'px'],
                'typography_text_transform' => 'uppercase',
                'border_radius' => self::radius(6),
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
            $this->headline($c['portfolio_title'] ?? 'Featured Projects', 'h1', array_merge(
                self::responsiveSize(72, 52, 38),
                [
                    'title_color' => $this->colors()['text'],
                    'typography_text_transform' => 'none',
                    'typography_font_weight' => '700',
                    'custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .2s forwards;}',
                ]
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
                'css_classes' => 'ivory-photo sr d' . min($i + 1, 4),
                'custom_css' => 'selector{min-height:300px;}',
            ], [
                self::image($url, [
                    'custom_css' => 'selector img{width:100%;height:300px;object-fit:cover;border-radius:8px;}',
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
            'background_color' => $this->colors()['surface'],
        ], [
            $this->eyebrow('Contact'),
            $this->headline($c['contact_title'] ?? 'Get In Touch', 'h1', array_merge(
                self::responsiveSize(72, 52, 38),
                [
                    'title_color' => $this->colors()['text'],
                    'typography_text_transform' => 'none',
                    'typography_font_weight' => '700',
                    'custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .2s forwards;}',
                ]
            )),
            $this->bodyText($c['contact_subtitle'] ?? 'We would love to hear from you.', [
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .4s forwards;max-width:500px;}',
            ]),
        ]);

        // Contact info cards
        $contactInfo = $c['contact_info'] ?? [
            ['icon' => "\xf0\x9f\x93\xa7", 'label' => 'Email', 'value' => $c['email'] ?? 'hello@example.com'],
            ['icon' => "\xf0\x9f\x93\x9e", 'label' => 'Phone', 'value' => $c['phone'] ?? '(555) 123-4567'],
            ['icon' => "\xf0\x9f\x93\x8d", 'label' => 'Location', 'value' => $c['address'] ?? '123 Business St, City, State'],
        ];

        $infoCards = [];
        foreach ($contactInfo as $i => $info) {
            $infoCards[] = self::container([
                'content_width' => 'full-width',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'width' => ['size' => 33.33, 'unit' => '%'],
                'padding' => self::pad(40, 28),
                'css_classes' => 'ivory-bcard sr d' . ($i + 1),
            ], [
                self::textEditor('<span style="font-size:36px;display:block;margin-bottom:14px;">' . $info['icon'] . '</span>'),
                self::heading($info['label'], 'h4', [
                    'title_color' => $this->colors()['text'],
                    'align' => 'center',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['heading'],
                    'typography_font_size' => ['size' => 18, 'unit' => 'px'],
                    'typography_font_weight' => '700',
                    'typography_text_transform' => 'none',
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
            $this->headline('We Are Here to Help', 'h2', [
                'title_color' => $this->colors()['text'],
                'typography_text_transform' => 'none',
                'typography_font_weight' => '700',
                'align' => 'center',
                'typography_font_size' => ['size' => 48, 'unit' => 'px'],
                '_margin' => self::margin(0, 0, 40, 0),
            ]),
            self::html('<form style="max-width:600px;width:100%;margin:0 auto;display:flex;flex-direction:column;gap:16px;">
<input type="text" placeholder="Your Name" style="padding:14px 20px;background:' . $this->colors()['surface'] . ';border:1px solid ' . $this->colors()['border'] . ';color:' . $this->colors()['text'] . ';font-family:\'' . $this->fonts()['body'] . '\',sans-serif;font-size:14px;border-radius:6px;outline:none;">
<input type="email" placeholder="Your Email" style="padding:14px 20px;background:' . $this->colors()['surface'] . ';border:1px solid ' . $this->colors()['border'] . ';color:' . $this->colors()['text'] . ';font-family:\'' . $this->fonts()['body'] . '\',sans-serif;font-size:14px;border-radius:6px;outline:none;">
<textarea rows="5" placeholder="Your Message" style="padding:14px 20px;background:' . $this->colors()['surface'] . ';border:1px solid ' . $this->colors()['border'] . ';color:' . $this->colors()['text'] . ';font-family:\'' . $this->fonts()['body'] . '\',sans-serif;font-size:14px;border-radius:6px;outline:none;resize:vertical;"></textarea>
<button type="submit" style="padding:16px 44px;background:' . $this->colors()['primary'] . ';color:#FFF;font-family:\'' . $this->fonts()['heading'] . '\',serif;font-size:14px;font-weight:700;letter-spacing:2px;text-transform:uppercase;border:none;border-radius:6px;cursor:pointer;transition:all .3s;">Send Message</button>
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

        return [self::html('<nav class="ivory-nav" id="mainNav" style="position:fixed;top:0;left:0;right:0;z-index:1000;padding:0 64px;height:90px;display:flex;align-items:center;justify-content:space-between;transition:background .4s,height .3s,box-shadow .4s;background:transparent;">
<a href="/" style="font-family:\'' . $this->fonts()['heading'] . '\',serif;font-size:24px;font-weight:700;color:' . $this->colors()['text'] . ';text-decoration:none;">' . e($siteName) . '</a>
<ul style="display:flex;gap:0;list-style:none;position:absolute;left:50%;transform:translateX(-50%);padding:0;margin:0;">' . $navLinks . '</ul>
<a href="/contact/" style="padding:10px 28px;background:' . $this->colors()['primary'] . ';color:#FFF;font-family:\'' . $this->fonts()['body'] . '\',sans-serif;font-size:12px;font-weight:600;letter-spacing:1.5px;text-transform:uppercase;border-radius:6px;text-decoration:none;transition:all .3s;">Contact Us</a>
</nav>
<style>
.ivory-nav ul a{display:block;padding:8px 20px;font-size:12px;font-weight:500;letter-spacing:1.5px;text-transform:uppercase;color:rgba(0,0,0,.45);text-decoration:none;transition:color .3s;position:relative;}
.ivory-nav ul a::after{content:\'\';position:absolute;bottom:4px;left:20px;right:20px;height:1.5px;background:' . $this->colors()['primary'] . ';transform:scaleX(0);transform-origin:center;transition:transform .3s;}
.ivory-nav ul a:hover{color:' . $this->colors()['text'] . ';}
.ivory-nav ul a:hover::after{transform:scaleX(1);}
.ivory-nav.bg{background:rgba(255,255,255,.97)!important;backdrop-filter:blur(20px);height:68px!important;box-shadow:0 1px 12px rgba(0,0,0,0.06);}
@media(max-width:1100px){.ivory-nav ul{display:none!important;}.ivory-nav{padding:0 28px!important;height:68px!important;background:rgba(255,255,255,.97)!important;backdrop-filter:blur(20px);box-shadow:0 1px 12px rgba(0,0,0,0.06);}}
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

        $contactHtml = '<div class="ivory-ci"><div class="ivory-ci-i">' . "\xf0\x9f\x93\xa7" . '</div><div><small>Email</small><span>' . e($email) . '</span></div></div>';
        if ($phone) {
            $contactHtml .= '<div class="ivory-ci"><div class="ivory-ci-i">' . "\xf0\x9f\x93\x9e" . '</div><div><small>Phone</small><span>' . e($phone) . '</span></div></div>';
        }
        if ($address) {
            $contactHtml .= '<div class="ivory-ci"><div class="ivory-ci-i">' . "\xf0\x9f\x93\x8d" . '</div><div><small>Location</small><span>' . e($address) . '</span></div></div>';
        }

        $sections = [];

        $sections[] = self::container([
            'content_width' => 'full-width',
            'flex_direction' => 'column',
            'padding' => self::pad(0),
            'background_background' => 'classic',
            'background_color' => $this->colors()['primary'],
        ], [
            self::container([
                'flex_direction' => 'row',
                'content_width' => 'full-width',
                'gap' => ['size' => 60, 'unit' => 'px'],
                'padding' => self::pad(80, 64, 60, 64),
                'border_border' => 'solid',
                'border_width' => self::pad(0, 0, 1, 0),
                'border_color' => 'rgba(255,255,255,0.1)',
                'custom_css' => 'selector{display:grid;grid-template-columns:1.5fr 1fr 1fr;}',
            ], [
                self::html('<div><div style="font-family:\'' . $this->fonts()['heading'] . '\',serif;font-size:24px;font-weight:700;color:#FFF;margin-bottom:3px;">' . e($siteName) . '</div><div style="font-size:10px;font-weight:600;letter-spacing:3px;text-transform:uppercase;color:' . $this->colors()['accent'] . ';margin-bottom:16px;">' . e($contact['tagline'] ?? 'Professional Excellence') . '</div><p style="font-size:13.5px;color:rgba(255,255,255,.45);line-height:1.8;max-width:250px;">' . e($contact['footer_text'] ?? 'Delivering excellence with every project.') . '</p><div class="ivory-social"><a href="#">' . "\xf0\x9f\x93\xb7" . '</a><a href="#">' . "\xf0\x9f\x90\xa6" . '</a><a href="#">' . "\xf0\x9f\x92\xbc" . '</a></div></div>'),
                self::html('<div class="ivory-fc"><h4>Navigate</h4><ul>' . $navLinks . '</ul></div>'),
                self::html('<div class="ivory-fc"><h4>Contact</h4>' . $contactHtml . '</div>'),
            ]),
            self::container([
                'flex_direction' => 'row',
                'flex_justify_content' => 'space-between',
                'flex_align_items' => 'center',
                'content_width' => 'full-width',
                'padding' => self::pad(18, 64),
            ], [
                self::textEditor('<p style="font-size:12px;color:rgba(255,255,255,.3);">&copy; ' . date('Y') . ' <span style="color:' . $this->colors()['accent'] . ';">' . e($siteName) . '</span>. All rights reserved.</p>'),
                self::textEditor('<a href="#" style="font-size:11.5px;color:rgba(255,255,255,.3);text-decoration:none;">Privacy Policy</a> &nbsp;&nbsp; <a href="#" style="font-size:11.5px;color:rgba(255,255,255,.3);text-decoration:none;">Terms of Service</a>'),
            ]),
        ]);

        return $sections;
    }
}
