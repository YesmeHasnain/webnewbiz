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
.e-con:not(.e-con--row)>.elementor-widget{width:100%;}
.e-con--row>.elementor-widget{width:auto;}

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
.ivory-fc a::before{content:'\\203A';color:rgba(212,165,116,0.5);transition:color .3s;}
.ivory-fc a:hover{color:rgba(255,255,255,0.85);}
.ivory-fc a:hover::before{color:var(--ivory-accent);}
.ivory-social{display:flex;gap:10px;margin-top:24px;}
.ivory-social a{width:36px;height:36px;border:1px solid rgba(255,255,255,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;text-decoration:none;color:rgba(255,255,255,0.35);transition:all .3s;}
.ivory-social a:hover{border-color:var(--ivory-accent);color:var(--ivory-accent);background:rgba(212,165,116,0.08);transform:translateY(-3px);}
.ivory-social a svg{width:16px;height:16px;}

/* Footer contact items */
.ivory-fci{display:flex;align-items:center;gap:10px;margin-bottom:14px;}
.ivory-fci svg{color:var(--ivory-accent);flex-shrink:0;opacity:.7;}
.ivory-fci span{font-size:13.5px;color:rgba(255,255,255,0.5);line-height:1.5;}

/* Footer office hours */
.ivory-fhours{display:flex;flex-direction:column;gap:0;}
.ivory-fhours-row{display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid rgba(255,255,255,0.06);font-size:13.5px;color:rgba(255,255,255,0.5);}
.ivory-fhours-row:last-child{border-bottom:none;}
.ivory-fhours-row span:first-child{font-weight:500;}
.ivory-fhours-closed span:last-child{color:rgba(255,255,255,0.25);font-style:italic;}

/* Trust badges strip */
.ivory-trust-strip{display:flex;align-items:center;justify-content:center;gap:0;flex-wrap:wrap;width:100%;}
.ivory-trust-badge{display:inline-flex;align-items:center;gap:7px;font-size:10px;font-weight:600;letter-spacing:2.5px;text-transform:uppercase;color:rgba(255,255,255,0.4);padding:6px 20px;white-space:nowrap;}
.ivory-trust-badge svg{color:var(--ivory-accent);opacity:.65;}
.ivory-trust-sep{width:1px;height:14px;background:rgba(255,255,255,0.1);margin:0 4px;}
@media(max-width:767px){.ivory-trust-strip{flex-direction:column;gap:4px;}.ivory-trust-sep{display:none;}}

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
@media(max-width:1100px){
  .ivory-photo{min-height:200px;}
  .ivory-card.e-con,.ivory-bcard.e-con,.ivory-photo.e-con{--width:48% !important;width:48% !important;}
}
@media(max-width:767px){
  .ivory-card.e-con,.ivory-bcard.e-con,.ivory-stat.e-con,.ivory-photo.e-con{--width:100% !important;width:100% !important;}
  .ivory-tcard.e-con{--width:100% !important;width:100% !important;}
  .ivory-nav ul{display:none !important;}
  .sp-features{grid-template-columns:1fr;}
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

    // ═══════════════���═══════════════════════════════════════════
    // HOME PAGE
    // ═══���═══════════════════════════════════════════════════════

    public function buildHomePage(array $c, array $img): array
    {
        $siteName = $c['site_name'] ?? 'Business Name';
        $heroTitle = $c['hero_title'] ?? 'Professional Excellence, Trusted Results';
        $heroSub = $c['hero_subtitle'] ?? 'We deliver exceptional results with a commitment to quality, integrity, and personalized service.';
        $heroCta = $c['hero_cta'] ?? 'Get Started';
        $heroCtaUrl = $c['hero_cta_url'] ?? '#services';

        $services = $c['services'] ?? [
            ['icon' => "\xe2\x9a\x96", 'title' => 'Expert Consultation', 'desc' => 'We develop comprehensive strategies tailored to your unique needs and objectives. Our team of specialists works closely with you to understand your situation and craft the most effective approach.'],
            ['icon' => "\xf0\x9f\x93\x8b", 'title' => 'Detailed Planning', 'desc' => 'Creating thorough, actionable plans that guide every step of the process. We leave nothing to chance, ensuring every detail is accounted for and every outcome is optimized.'],
            ['icon' => "\xf0\x9f\x93\x88", 'title' => 'Measurable Growth', 'desc' => 'Data-driven approaches that deliver tangible, measurable results for your organization. We track progress at every stage and adjust strategies to maximize your success.'],
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

        $trustItems = $c['trust_items'] ?? [
            ['icon' => "\xe2\x9c\x93", 'text' => 'Board Certified Professionals'],
            ['icon' => "\xe2\x9c\x93", 'text' => '15+ Years of Practice'],
            ['icon' => "\xe2\x9c\x93", 'text' => '10,000+ Clients Served'],
            ['icon' => "\xe2\x9c\x93", 'text' => 'Award-Winning Service'],
        ];

        $heroImg = $img['hero'] ?? '';
        $galleryImgs = array_filter([
            $img['gallery1'] ?? '',
            $img['gallery2'] ?? '',
            $img['gallery3'] ?? '',
            $img['services'] ?? '',
        ]);

        $col = $this->colors();
        $fnt = $this->fonts();
        $sections = [];

        // ─────────────────────────────────────────────────────────
        // 1. HERO — SPLIT-SCREEN (text left 55%, image right 45%)
        // ─────────────────────────────────────────────────────────
        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'row',
            'flex_direction_mobile' => 'column',
            'flex_direction_tablet' => 'row',
            'flex_align_items' => 'stretch',
            'flex_gap' => ['size' => 0, 'unit' => 'px', 'column' => '0', 'row' => '0'],
            'padding' => self::pad(0),
            'min_height' => ['size' => 92, 'unit' => 'vh', 'sizes' => []],
            'background_background' => 'classic',
            'background_color' => '#FFFFFF',
            '_element_id' => 'hero',
        ], [
            // Left: text content on white background
            self::container(array_merge([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'flex_justify_content' => 'center',
            ], self::rWidth(55, 55, 100), [
                'padding' => self::pad(80, 80, 80, 100),
                'padding_tablet' => self::pad(60, 40, 60, 50),
                'padding_mobile' => self::pad(50, 24, 40, 24),
                'background_background' => 'classic',
                'background_color' => '#FFFFFF',
            ]), [
                self::html('<div style="display:inline-flex;align-items:center;gap:10px;margin-bottom:28px;opacity:0;animation:fadeUp .7s ease .2s forwards;"><span class="eyebrow" style="margin-bottom:0;">' . e($c['hero_eyebrow'] ?? 'Welcome to ' . $siteName) . '</span></div>'),

                $this->headline($heroTitle, 'h1', array_merge(
                    self::responsiveSize(62, 46, 34),
                    [
                        'align' => 'left',
                        'title_color' => $col['text'],
                        'typography_text_transform' => 'none',
                        'typography_font_weight' => '700',
                        'typography_line_height' => ['size' => 1.12, 'unit' => 'em'],
                        'typography_letter_spacing' => ['size' => -0.5, 'unit' => 'px'],
                        '_margin' => self::margin(0, 0, 24, 0),
                        'custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .35s forwards;max-width:580px;}',
                    ]
                )),

                self::textEditor('<p>' . e($heroSub) . '</p>', [
                    'text_color' => $col['muted'],
                    'typography_typography' => 'custom',
                    'typography_font_family' => $fnt['body'],
                    'typography_font_size' => ['size' => 17, 'unit' => 'px'],
                    'typography_line_height' => ['size' => 1.75, 'unit' => 'em'],
                    'typography_font_weight' => '400',
                    'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .55s forwards;max-width:480px;}',
                ]),

                self::container([
                    'flex_direction' => 'row',
                    'flex_direction_mobile' => 'column',
                    'flex_align_items' => 'center',
                    'flex_gap' => ['size' => 16, 'unit' => 'px', 'column' => '16', 'row' => '16'],
                    'content_width' => 'full',
                    '_margin' => self::margin(12, 0, 0, 0),
                    'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .7s forwards;}',
                ], [
                    $this->ctaButton($heroCta, $heroCtaUrl, [
                        'border_radius' => self::radius(6),
                        'custom_css' => 'selector .elementor-button:hover{transform:translateY(-3px);box-shadow:0 16px 40px rgba(27,77,62,.2);}',
                    ]),
                    $this->ghostButton($c['hero_ghost_cta'] ?? 'Learn More', '#services', [
                        'border_radius' => self::radius(6),
                    ]),
                ]),
            ]),

            // Right: hero image edge-to-edge, no radius
            self::container(array_merge([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'flex_justify_content' => 'center',
                'flex_align_items' => 'stretch',
            ], self::rWidth(45, 45, 100), [
                'padding' => self::pad(0),
                'min_height' => ['size' => 500, 'unit' => 'px', 'sizes' => []],
                'min_height_mobile' => ['size' => 320, 'unit' => 'px', 'sizes' => []],
                'background_background' => 'classic',
                'background_image' => ['url' => $heroImg, 'id' => ''],
                'background_position' => 'center center',
                'background_size' => 'cover',
                'custom_css' => 'selector{overflow:hidden;}',
            ]), []),
        ]);

        // ─────────────────────────────────────────────────────────
        // 2. TRUST BAR — horizontal strip with trust indicators
        // ─────────────────────────────────────────────────────────
        $trustBadges = [];
        foreach ($trustItems as $i => $item) {
            if ($i > 0) {
                $trustBadges[] = self::html('<div style="width:1px;height:32px;background:rgba(27,77,62,0.12);margin:0 8px;"></div>');
            }
            $trustBadges[] = self::html(
                '<div style="display:inline-flex;align-items:center;gap:10px;padding:8px 20px;white-space:nowrap;">'
                . '<span style="width:28px;height:28px;background:' . $col['primary'] . ';border-radius:50%;display:inline-flex;align-items:center;justify-content:center;color:#FFF;font-size:13px;font-weight:700;flex-shrink:0;">' . ($item['icon'] ?? "\xe2\x9c\x93") . '</span>'
                . '<span style="font-family:\'' . $fnt['body'] . '\',sans-serif;font-size:13px;font-weight:600;color:' . $col['primary'] . ';letter-spacing:0.5px;">' . e($item['text']) . '</span>'
                . '</div>'
            );
        }

        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'row',
            'flex_direction_mobile' => 'column',
            'flex_wrap' => 'wrap',
            'flex_justify_content' => 'center',
            'flex_align_items' => 'center',
            'flex_gap' => ['size' => 0, 'unit' => 'px', 'column' => '0', 'row' => '0'],
            'padding' => self::pad(28, 40),
            'padding_mobile' => self::pad(24, 20),
            'background_background' => 'classic',
            'background_color' => '#EDF5F0',
            'border_border' => 'solid',
            'border_width' => self::pad(1, 0),
            'border_color' => 'rgba(27,77,62,0.1)',
            'custom_css' => 'selector .elementor-widget{width:auto !important;}',
        ], $trustBadges);

        // ─────────────────────────────────────────────────────────
        // 3. SERVICES — ALTERNATING ZIGZAG ROWS
        // ─────────────────────────────────────────────────────────

        // Section header
        $sections[] = self::container([
            'boxed_width' => ['size' => 1200, 'unit' => 'px', 'sizes' => []],
            'flex_direction' => 'column',
            'flex_align_items' => 'center',
            'padding' => self::pad(100, 40, 20, 40),
            'padding_mobile' => self::pad(60, 20, 10, 20),
            'background_background' => 'classic',
            'background_color' => '#FFFFFF',
            '_element_id' => 'services',
        ], [
            $this->eyebrow($c['services_eyebrow'] ?? 'Our Services'),
            $this->headline($c['services_title'] ?? 'What We Do', 'h2', [
                'title_color' => $col['text'],
                'typography_text_transform' => 'none',
                'typography_font_weight' => '700',
                '_margin' => self::margin(0, 0, 12, 0),
            ]),
            $this->bodyText($c['services_subtitle'] ?? 'Expert solutions tailored to your specific needs and goals.', [
                'align' => 'center',
                '_margin' => self::margin(0),
                'custom_css' => 'selector{max-width:560px;}',
            ]),
        ]);

        // Zigzag rows
        foreach ($services as $i => $svc) {
            $isEven = ($i % 2 === 0); // even=image left, odd=image right
            $svcImg = $galleryImgs[$i] ?? ($galleryImgs[0] ?? $heroImg);
            $delay = min($i + 1, 4);

            $textCol = [
                self::html('<span style="display:inline-block;font-size:40px;margin-bottom:16px;">' . ($svc['icon'] ?? '') . '</span>'),
                self::heading($svc['title'], 'h3', [
                    'align' => 'left',
                    'title_color' => $col['text'],
                    'typography_typography' => 'custom',
                    'typography_font_family' => $fnt['heading'],
                    'typography_font_size' => ['size' => 32, 'unit' => 'px'],
                    'typography_font_size_tablet' => ['size' => 26, 'unit' => 'px'],
                    'typography_font_size_mobile' => ['size' => 24, 'unit' => 'px'],
                    'typography_font_weight' => '700',
                    'typography_text_transform' => 'none',
                    'typography_line_height' => ['size' => 1.2, 'unit' => 'em'],
                    '_margin' => self::margin(0, 0, 16, 0),
                ]),
                $this->bodyText($svc['desc'], [
                    'align' => 'left',
                    'typography_font_size' => ['size' => 15, 'unit' => 'px'],
                    'typography_line_height' => ['size' => 1.85, 'unit' => 'em'],
                ]),
                self::html('<div style="width:40px;height:3px;background:' . $col['primary'] . ';border-radius:2px;margin-top:8px;"></div>'),
            ];

            $imageCol = [
                self::image($svcImg, [
                    'custom_css' => 'selector img{width:100%;height:100%;min-height:360px;object-fit:cover;border-radius:8px;transition:transform 6s ease;} selector:hover img{transform:scale(1.03);}',
                ]),
            ];

            $leftContent = $isEven ? $imageCol : $textCol;
            $rightContent = $isEven ? $textCol : $imageCol;
            $leftPad = $isEven ? ['padding' => self::pad(0), 'css_classes' => 'sr'] : ['padding' => self::pad(20, 40, 20, 0), 'padding_mobile' => self::pad(10, 0), 'flex_justify_content' => 'center', 'css_classes' => 'sr d' . $delay];
            $rightPad = $isEven ? ['padding' => self::pad(20, 0, 20, 40), 'padding_mobile' => self::pad(10, 0), 'flex_justify_content' => 'center', 'css_classes' => 'sr d' . $delay] : ['padding' => self::pad(0), 'css_classes' => 'sr'];

            $sections[] = self::twoCol(
                $leftContent,
                $rightContent,
                50,
                [
                    'padding' => self::pad(40, 64),
                    'padding_mobile' => self::pad(30, 20),
                    'padding_tablet' => self::pad(40, 30),
                    'background_background' => 'classic',
                    'background_color' => ($i % 2 === 0) ? '#FFFFFF' : '#F8FAF9',
                    'flex_gap' => ['size' => 40, 'unit' => 'px', 'column' => '40', 'row' => '40'],
                ],
                $leftPad,
                $rightPad,
            );
        }

        // ─────────────────────────────────────────────────────────
        // 4. STATS — clean inline row with green accent numbers
        // ─────────────────────────────────────────────────────────
        $statElements = [];
        foreach ($stats as $i => $s) {
            $statElements[] = self::container([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'width' => ['size' => round(100 / count($stats), 2), 'unit' => '%', 'sizes' => []],
                'width_tablet' => ['size' => 48, 'unit' => '%', 'sizes' => []],
                'width_mobile' => ['size' => 100, 'unit' => '%', 'sizes' => []],
                'padding' => self::pad(50, 20),
                'background_background' => 'classic',
                'background_color' => '#FFFFFF',
                'border_border' => 'solid',
                'border_width' => self::pad(0, 1, 0, 0),
                'border_color' => $col['border'],
                'css_classes' => 'ivory-stat sr d' . ($i + 1),
            ], [
                self::html('<div class="ivory-stat-n" data-count="' . $s['number'] . '" data-suffix="' . ($s['suffix'] ?? '') . '">' . $s['number'] . ($s['suffix'] ?? '') . '</div>'),
                self::html('<div class="ivory-stat-l">' . e($s['label']) . '</div>'),
            ]);
        }

        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'row',
            'flex_direction_mobile' => 'column',
            'flex_wrap' => 'wrap',
            'flex_gap' => ['size' => 0, 'unit' => 'px', 'column' => '0', 'row' => '0'],
            'padding' => self::pad(0, 64),
            'background_background' => 'classic',
            'background_color' => '#FFFFFF',
            'border_border' => 'solid',
            'border_width' => self::pad(1, 0),
            'border_color' => $col['border'],
        ], $statElements);

        // ─────────────────────────────────────────────────────────
        // 5. TESTIMONIALS — 3-column equal grid
        // ─────────────────────────────────────────────────────────
        $testimonialCards = [];
        foreach ($testimonials as $i => $t) {
            $testimonialCards[] = self::container([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'flex_justify_content' => 'space-between',
                'width' => ['size' => 31, 'unit' => '%', 'sizes' => []],
                'width_tablet' => ['size' => 48, 'unit' => '%', 'sizes' => []],
                'width_mobile' => ['size' => 100, 'unit' => '%', 'sizes' => []],
                'padding' => self::pad(36, 32),
                'css_classes' => 'ivory-tcard sr d' . ($i + 1),
            ], [
                self::container(['content_width' => 'full', 'flex_direction' => 'column'], [
                    self::html('<div class="ivory-stars" style="color:' . $col['primary'] . ';">&#9733;&#9733;&#9733;&#9733;&#9733;</div>'),
                    self::textEditor('&ldquo;' . e($t['quote']) . '&rdquo;', [
                        'text_color' => $col['muted'],
                        'typography_typography' => 'custom',
                        'typography_font_family' => $fnt['body'],
                        'typography_font_size' => ['size' => 15, 'unit' => 'px'],
                        'typography_line_height' => ['size' => 1.85, 'unit' => 'em'],
                        '_margin' => self::margin(0, 0, 24, 0),
                    ]),
                ]),
                self::html(
                    '<div style="display:flex;align-items:center;gap:14px;padding-top:20px;border-top:1px solid ' . $col['border'] . ';">'
                    . '<div style="width:44px;height:44px;border-radius:50%;background:rgba(27,77,62,.08);display:flex;align-items:center;justify-content:center;font-family:\'' . $fnt['heading'] . '\',serif;font-size:16px;font-weight:700;color:' . $col['primary'] . ';">' . e($t['initials'] ?? 'AB') . '</div>'
                    . '<div><h5 style="font-family:\'' . $fnt['heading'] . '\',serif;font-size:16px;font-weight:700;color:' . $col['text'] . ';margin:0;">' . e($t['name']) . '</h5>'
                    . '<span style="font-size:12px;color:rgba(0,0,0,.4);">' . e($t['role']) . '</span></div></div>'
                ),
            ]);
        }

        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => '#F8FAF9',
        ], [
            $this->eyebrow($c['testimonials_eyebrow'] ?? 'Testimonials'),
            $this->headline($c['testimonials_title'] ?? 'What Our Clients Say', 'h2', [
                'title_color' => $col['text'],
                'typography_text_transform' => 'none',
                'typography_font_weight' => '700',
                '_margin' => self::margin(0, 0, 50, 0),
            ]),
            self::container([
                'flex_direction' => 'row',
                'flex_direction_mobile' => 'column',
                'flex_wrap' => 'wrap',
                'content_width' => 'full',
                'flex_gap' => ['size' => 24, 'unit' => 'px', 'column' => '24', 'row' => '24'],
                'flex_align_items' => 'stretch',
            ], $testimonialCards),
        ], 'testimonials');

        // ─────────────────────────────────────────────────────────
        // 6. CTA — clean boxed section, green bg, white text
        // ─────────────────────────────────────────────────────────
        $sections[] = self::container([
            'boxed_width' => ['size' => 900, 'unit' => 'px', 'sizes' => []],
            'flex_direction' => 'column',
            'flex_align_items' => 'center',
            'flex_justify_content' => 'center',
            'padding' => self::pad(80, 64),
            'padding_mobile' => self::pad(60, 24),
            '_margin' => self::margin(80, 'auto', 80, 'auto'),
            '_margin_mobile' => self::margin(40, 20, 40, 20),
            'background_background' => 'classic',
            'background_color' => $col['primary'],
            'border_radius' => self::radius(12),
            '_element_id' => 'cta',
            'custom_css' => 'selector{box-shadow:0 24px 60px rgba(27,77,62,0.18);}',
        ], [
            $this->headline($c['cta_title'] ?? 'Ready to Get Started?', 'h2', array_merge(
                self::responsiveSize(44, 36, 28),
                [
                    'title_color' => '#FFFFFF',
                    'typography_text_transform' => 'none',
                    'typography_font_weight' => '700',
                    'typography_line_height' => ['size' => 1.2, 'unit' => 'em'],
                    'align' => 'center',
                    '_margin' => self::margin(0, 0, 16, 0),
                ]
            )),
            $this->bodyText($c['cta_text'] ?? 'Contact us today for a confidential consultation. Let our experienced team guide you toward the results you deserve.', [
                'align' => 'center',
                'text_color' => 'rgba(255,255,255,0.75)',
                'typography_font_size' => ['size' => 16, 'unit' => 'px'],
                'typography_font_weight' => '400',
                'custom_css' => 'selector{max-width:520px;margin-left:auto;margin-right:auto;}',
                '_margin' => self::margin(0, 0, 36, 0),
            ]),
            self::container([
                'flex_direction' => 'row',
                'flex_direction_mobile' => 'column',
                'flex_justify_content' => 'center',
                'flex_align_items' => 'center',
                'flex_gap' => ['size' => 14, 'unit' => 'px', 'column' => '14', 'row' => '14'],
                'content_width' => 'full',
                'css_classes' => 'sr d2',
            ], [
                self::button($c['cta_button'] ?? 'Schedule Consultation', '#contact', [
                    'background_color' => '#FFFFFF',
                    'button_text_color' => $col['primary'],
                    'typography_typography' => 'custom',
                    'typography_font_family' => $fnt['heading'],
                    'typography_font_size' => ['size' => 14, 'unit' => 'px'],
                    'typography_font_weight' => '700',
                    'typography_letter_spacing' => ['size' => 1.5, 'unit' => 'px'],
                    'typography_text_transform' => 'uppercase',
                    'border_radius' => self::radius(6),
                    'button_padding' => self::pad(16, 40),
                    'button_background_hover_color' => '#F0F0F0',
                    'custom_css' => 'selector .elementor-button:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(0,0,0,.15);}',
                ]),
                self::button($c['cta_ghost'] ?? 'Call Us Now', '#contact', [
                    'background_color' => 'transparent',
                    'button_text_color' => 'rgba(255,255,255,0.85)',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $fnt['heading'],
                    'typography_font_size' => ['size' => 14, 'unit' => 'px'],
                    'typography_font_weight' => '600',
                    'typography_letter_spacing' => ['size' => 1.5, 'unit' => 'px'],
                    'typography_text_transform' => 'uppercase',
                    'border_border' => 'solid',
                    'border_width' => self::pad(1),
                    'border_color' => 'rgba(255,255,255,0.35)',
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
                'content_width' => 'full',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'width' => ['size' => round(100 / count($stats), 2), 'unit' => '%', 'sizes' => []],
                'width_tablet' => ['size' => 48, 'unit' => '%', 'sizes' => []],
                'width_mobile' => ['size' => 100, 'unit' => '%', 'sizes' => []],
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
            'content_width' => 'full',
            'flex_direction' => 'row',
            'flex_direction_mobile' => 'column',
            'flex_wrap' => 'wrap',
            'flex_gap' => ['size' => 0, 'unit' => 'px', 'column' => '0', 'row' => '0'],
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
                'content_width' => 'full',
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
                'flex_gap' => ['size' => 24, 'unit' => 'px', 'column' => '24', 'row' => '24'],
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
                'content_width' => 'full',
                'width' => ['size' => 31, 'unit' => '%', 'sizes' => []],
                'width_tablet' => ['size' => 48, 'unit' => '%', 'sizes' => []],
                'width_mobile' => ['size' => 100, 'unit' => '%', 'sizes' => []],
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
                    'flex_direction_mobile' => 'column',
                    'flex_wrap' => 'wrap',
                    'content_width' => 'full',
                    'flex_gap' => ['size' => 20, 'unit' => 'px', 'column' => '20', 'row' => '20'],
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
                'content_width' => 'full',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'width' => ['size' => 31, 'unit' => '%', 'sizes' => []],
                'width_tablet' => ['size' => 48, 'unit' => '%', 'sizes' => []],
                'width_mobile' => ['size' => 100, 'unit' => '%', 'sizes' => []],
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
                'flex_direction_mobile' => 'column',
                'flex_wrap' => 'wrap',
                'content_width' => 'full',
                'flex_gap' => ['size' => 24, 'unit' => 'px', 'column' => '24', 'row' => '24'],
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

        $c = $this->colors();
        $f = $this->fonts();

        return [self::html('
<!-- Ivory Two-Row Header -->
<div class="ivory-hdr-wrap" id="ivoryHdrWrap">
<!-- TOP UTILITY BAR -->
<div class="ivory-topbar" id="ivoryTopbar">
<div class="ivory-topbar-inner">
<div class="ivory-topbar-left">
<span class="ivory-topbar-item"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"></path></svg> (555) 123-4567</span>
<span class="ivory-topbar-sep">|</span>
<span class="ivory-topbar-item"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg> info@example.com</span>
</div>
<div class="ivory-topbar-right">
<span class="ivory-topbar-item"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg> Mon &ndash; Fri: 9:00 AM &ndash; 6:00 PM</span>
</div>
</div>
</div>
<!-- MAIN NAV BAR -->
<nav class="ivory-mainnav" id="ivoryMainNav">
<div class="ivory-mainnav-inner">
<a href="/" class="ivory-logo">' . e($siteName) . '</a>
<ul class="ivory-nav-links">' . $navLinks . '</ul>
<div class="ivory-nav-actions">
<a href="/contact/" class="ivory-cta-btn">Book Consultation</a>
<button class="ivory-hamburger" id="ivoryHamburger" aria-label="Menu"><span></span><span></span><span></span></button>
</div>
</div>
</nav>
<!-- MOBILE DROPDOWN -->
<div class="ivory-mobile-menu" id="ivoryMobileMenu">
<ul>' . $navLinks . '</ul>
<a href="/contact/" class="ivory-cta-btn" style="margin:12px 20px 20px;text-align:center;">Book Consultation</a>
</div>
</div>

<style>
/* --- Ivory Utility Bar --- */
.ivory-topbar{background:' . $c['primary'] . ';height:36px;display:flex;align-items:center;transition:margin-top .35s ease,opacity .35s ease;overflow:hidden;}
.ivory-topbar.ivory-hidden{margin-top:-36px;opacity:0;pointer-events:none;}
.ivory-topbar-inner{width:100%;max-width:1280px;margin:0 auto;padding:0 64px;display:flex;align-items:center;justify-content:space-between;}
.ivory-topbar-left,.ivory-topbar-right{display:flex;align-items:center;gap:6px;}
.ivory-topbar-item{display:inline-flex;align-items:center;gap:6px;font-family:\'' . $f['body'] . '\',sans-serif;font-size:12px;font-weight:400;color:rgba(255,255,255,.85);white-space:nowrap;}
.ivory-topbar-item svg{opacity:.7;}
.ivory-topbar-sep{color:rgba(255,255,255,.25);font-size:11px;margin:0 4px;}

/* --- Ivory Main Nav --- */
.ivory-mainnav{background:#FFFFFF;height:70px;display:flex;align-items:center;transition:box-shadow .35s ease;}
.ivory-mainnav.ivory-sticky{position:fixed;top:0;left:0;right:0;z-index:1000;box-shadow:0 2px 16px rgba(0,0,0,0.07);}
.ivory-mainnav-inner{width:100%;max-width:1280px;margin:0 auto;padding:0 64px;display:flex;align-items:center;justify-content:space-between;}
.ivory-logo{font-family:\'' . $f['heading'] . '\',serif;font-size:24px;font-weight:700;color:' . $c['text'] . ';text-decoration:none;white-space:nowrap;}
.ivory-nav-links{display:flex;gap:0;list-style:none;padding:0;margin:0;}
.ivory-nav-links a{display:block;padding:8px 18px;font-family:\'' . $f['body'] . '\',sans-serif;font-size:12px;font-weight:500;letter-spacing:1.5px;text-transform:uppercase;color:rgba(0,0,0,.45);text-decoration:none;transition:color .3s;position:relative;}
.ivory-nav-links a::after{content:\'\';position:absolute;bottom:2px;left:18px;right:18px;height:1.5px;background:' . $c['primary'] . ';transform:scaleX(0);transform-origin:center;transition:transform .3s;}
.ivory-nav-links a:hover{color:' . $c['text'] . ';}
.ivory-nav-links a:hover::after{transform:scaleX(1);}
.ivory-nav-actions{display:flex;align-items:center;gap:16px;}
.ivory-cta-btn{display:inline-block;padding:10px 24px;background:' . $c['primary'] . ';color:#FFF;font-family:\'' . $f['body'] . '\',sans-serif;font-size:12px;font-weight:600;letter-spacing:1px;text-transform:uppercase;border-radius:6px;text-decoration:none;transition:background .3s,transform .2s;border:none;cursor:pointer;}
.ivory-cta-btn:hover{background:' . $c['secondary'] . ';transform:translateY(-1px);}

/* --- Hamburger --- */
.ivory-hamburger{display:none;flex-direction:column;gap:5px;background:none;border:none;cursor:pointer;padding:6px;z-index:1002;}
.ivory-hamburger span{display:block;width:22px;height:2px;background:' . $c['text'] . ';border-radius:2px;transition:all .3s;}
.ivory-hamburger.active span:nth-child(1){transform:rotate(45deg) translate(5px,5px);}
.ivory-hamburger.active span:nth-child(2){opacity:0;}
.ivory-hamburger.active span:nth-child(3){transform:rotate(-45deg) translate(5px,-5px);}

/* --- Mobile Menu --- */
.ivory-mobile-menu{display:none;background:#FFFFFF;border-top:1px solid rgba(0,0,0,.06);box-shadow:0 8px 24px rgba(0,0,0,0.08);}
.ivory-mobile-menu.open{display:block;}
.ivory-mobile-menu ul{list-style:none;padding:8px 0;margin:0;}
.ivory-mobile-menu ul a{display:block;padding:12px 24px;font-family:\'' . $f['body'] . '\',sans-serif;font-size:13px;font-weight:500;color:' . $c['text'] . ';text-decoration:none;border-bottom:1px solid rgba(0,0,0,.04);transition:background .2s;}
.ivory-mobile-menu ul a:hover{background:rgba(27,77,62,.04);}

/* --- Responsive --- */
@media(max-width:1100px){
.ivory-topbar{display:none!important;}
.ivory-nav-links{display:none!important;}
.ivory-hamburger{display:flex!important;}
.ivory-mainnav{position:fixed;top:0;left:0;right:0;z-index:1000;box-shadow:0 2px 16px rgba(0,0,0,0.07);}
.ivory-mainnav-inner{padding:0 24px;}
.ivory-mobile-menu{position:fixed;top:70px;left:0;right:0;z-index:999;}
.ivory-cta-btn.ivory-nav-actions .ivory-cta-btn{display:none;}
.ivory-nav-actions .ivory-cta-btn{display:none;}
}
</style>

<script>
(function(){
var topbar=document.getElementById("ivoryTopbar");
var mainNav=document.getElementById("ivoryMainNav");
var lastY=0;
window.addEventListener("scroll",function(){
var y=window.scrollY||window.pageYOffset;
if(y>60){topbar.classList.add("ivory-hidden");mainNav.classList.add("ivory-sticky");}
else{topbar.classList.remove("ivory-hidden");mainNav.classList.remove("ivory-sticky");}
lastY=y;
});
var ham=document.getElementById("ivoryHamburger");
var mob=document.getElementById("ivoryMobileMenu");
if(ham&&mob){ham.addEventListener("click",function(){ham.classList.toggle("active");mob.classList.toggle("open");});}
})();
</script>
')];
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

        $c = $this->colors();
        $f = $this->fonts();

        $email = $contact['email'] ?? 'hello@example.com';
        $phone = $contact['phone'] ?? '';
        $address = $contact['address'] ?? '';

        // Build contact column HTML
        $contactItems = '';
        $contactItems .= '<div class="ivory-fci"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg><span>' . e($email) . '</span></div>';
        if ($phone) {
            $contactItems .= '<div class="ivory-fci"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"></path></svg><span>' . e($phone) . '</span></div>';
        }
        if ($address) {
            $contactItems .= '<div class="ivory-fci"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg><span>' . e($address) . '</span></div>';
        }

        $sections = [];

        // ── Main footer wrapper ──
        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'column',
            'padding' => self::pad(0),
            'background_background' => 'classic',
            'background_color' => $c['primary'],
        ], [
            // ── 4-column grid ──
            self::container([
                'flex_direction' => 'row',
                'content_width' => 'full',
                'flex_gap' => ['size' => 40, 'unit' => 'px', 'column' => '40', 'row' => '40'],
                'padding' => self::pad(72, 64, 56, 64),
                'padding_tablet' => self::pad(56, 32, 40, 32),
                'padding_mobile' => self::pad(48, 24, 36, 24),
                'border_border' => 'solid',
                'border_width' => self::pad(0, 0, 1, 0),
                'border_color' => 'rgba(255,255,255,0.08)',
                'custom_css' => 'selector{display:grid;grid-template-columns:1.4fr 0.8fr 0.9fr 0.9fr;gap:40px;}@media(max-width:1100px){selector{grid-template-columns:1fr 1fr;}}@media(max-width:767px){selector{grid-template-columns:1fr;}}',
            ], [
                // Col 1: Brand
                self::html('<div>
<div style="font-family:\'' . $f['heading'] . '\',serif;font-size:26px;font-weight:700;color:#FFFFFF;margin-bottom:6px;">' . e($siteName) . '</div>
<div style="font-size:10px;font-weight:600;letter-spacing:3px;text-transform:uppercase;color:' . $c['accent'] . ';margin-bottom:18px;">' . e($contact['tagline'] ?? 'Professional Excellence') . '</div>
<p style="font-family:\'' . $f['body'] . '\',sans-serif;font-size:14px;color:rgba(255,255,255,.5);line-height:1.8;max-width:280px;margin:0;">' . e($contact['footer_text'] ?? 'Committed to providing trusted, professional services with integrity and care.') . '</p>
<div class="ivory-social" style="margin-top:24px;">
<a href="#" aria-label="Facebook"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
<a href="#" aria-label="LinkedIn"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg></a>
<a href="#" aria-label="Twitter"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>
</div>
</div>'),
                // Col 2: Quick Links
                self::html('<div class="ivory-fc"><h4>Quick Links</h4><ul>' . $navLinks . '</ul></div>'),
                // Col 3: Office Hours
                self::html('<div class="ivory-fc"><h4>Office Hours</h4>
<div class="ivory-fhours">
<div class="ivory-fhours-row"><span>Monday &ndash; Friday</span><span>9:00 AM &ndash; 6:00 PM</span></div>
<div class="ivory-fhours-row"><span>Saturday</span><span>9:00 AM &ndash; 1:00 PM</span></div>
<div class="ivory-fhours-row ivory-fhours-closed"><span>Sunday</span><span>Closed</span></div>
</div>
</div>'),
                // Col 4: Contact
                self::html('<div class="ivory-fc"><h4>Contact</h4>' . $contactItems . '</div>'),
            ]),

            // ── Trust Badges Strip ──
            self::container([
                'flex_direction' => 'row',
                'flex_justify_content' => 'center',
                'flex_align_items' => 'center',
                'content_width' => 'full',
                'padding' => self::pad(16, 64),
                'padding_mobile' => self::pad(16, 24),
                'background_background' => 'classic',
                'background_color' => 'rgba(255,255,255,0.05)',
                'border_border' => 'solid',
                'border_width' => self::pad(0, 0, 1, 0),
                'border_color' => 'rgba(255,255,255,0.06)',
            ], [
                self::html('<div class="ivory-trust-strip">
<span class="ivory-trust-badge"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg> Licensed &amp; Insured</span>
<span class="ivory-trust-sep"></span>
<span class="ivory-trust-badge"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg> 24/7 Emergency Service</span>
<span class="ivory-trust-sep"></span>
<span class="ivory-trust-badge"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> 15+ Years Experience</span>
</div>'),
            ]),

            // ── Copyright Row ──
            self::container([
                'flex_direction' => 'row',
                'flex_justify_content' => 'space-between',
                'flex_align_items' => 'center',
                'content_width' => 'full',
                'padding' => self::pad(18, 64),
                'padding_mobile' => self::pad(16, 24),
            ], [
                self::textEditor('<p style="font-size:12px;color:rgba(255,255,255,.3);font-family:\'' . $f['body'] . '\',sans-serif;margin:0;">&copy; ' . date('Y') . ' <span style="color:' . $c['accent'] . ';">' . e($siteName) . '</span>. All rights reserved.</p>'),
                self::textEditor('<a href="#" style="font-size:11.5px;color:rgba(255,255,255,.3);text-decoration:none;font-family:\'' . $f['body'] . '\',sans-serif;transition:color .3s;">Privacy Policy</a> <span style="color:rgba(255,255,255,.15);margin:0 8px;">|</span> <a href="#" style="font-size:11.5px;color:rgba(255,255,255,.3);text-decoration:none;font-family:\'' . $f['body'] . '\',sans-serif;transition:color .3s;">Terms of Service</a>'),
            ]),
        ]);

        return $sections;
    }
}
