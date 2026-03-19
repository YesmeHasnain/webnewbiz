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
.e-con:not(.e-con--row)>.elementor-widget{width:100%;}
.e-con--row>.elementor-widget{width:auto;}

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
@media(max-width:1100px){
  .forest-photo{min-height:200px;}
  .forest-card.e-con,.forest-bcard.e-con,.forest-photo.e-con{--width:48% !important;width:48% !important;}
}
@media(max-width:767px){
  .forest-card.e-con,.forest-bcard.e-con,.forest-stat.e-con,.forest-photo.e-con{--width:100% !important;width:100% !important;}
  .forest-tcard.e-con{--width:100% !important;width:100% !important;}
  .forest-nav ul{display:none !important;}
  .forest-nav{padding:0 24px !important;height:64px !important;}
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
            'align' => 'center',
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
            'align' => 'center',
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
            'align' => 'center',
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
            'align' => 'center',
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

        $services = $c['services'] ?? [
            ['icon' => '🏠', 'title' => 'Residential Projects', 'desc' => 'Custom homes and renovations built to the highest standards of quality and craftsmanship.'],
            ['icon' => '🏗️', 'title' => 'Commercial Construction', 'desc' => 'Professional commercial builds delivered on time and within budget, every single time.'],
            ['icon' => '🌿', 'title' => 'Landscape Design', 'desc' => 'Beautiful outdoor spaces that enhance your property value and quality of life.'],
        ];

        $testimonials = $c['testimonials'] ?? [
            ['quote' => 'They transformed our property beyond our wildest expectations. Professional, punctual, and passionate about their work.', 'name' => 'Sarah M.', 'role' => 'Homeowner', 'initials' => 'SM'],
            ['quote' => 'The attention to detail and quality of work was outstanding. Highly recommend to anyone.', 'name' => 'James K.', 'role' => 'Property Developer', 'initials' => 'JK'],
        ];

        $stats = $c['stats'] ?? [
            ['number' => '500', 'suffix' => '+', 'label' => 'Projects Completed'],
            ['number' => '98', 'suffix' => '%', 'label' => 'Client Satisfaction'],
            ['number' => '25', 'suffix' => '+', 'label' => 'Years Experience'],
        ];

        $processSteps = $c['process_steps'] ?? [
            ['title' => 'Consultation', 'desc' => 'We listen to your vision, assess the site, and discuss your goals and budget in detail.'],
            ['title' => 'Planning', 'desc' => 'Our team creates detailed blueprints, timelines, and cost estimates for your approval.'],
            ['title' => 'Construction', 'desc' => 'Skilled craftsmen bring your project to life with quality materials and precision work.'],
            ['title' => 'Completion', 'desc' => 'Final walkthrough, quality inspection, and handover — your satisfaction guaranteed.'],
        ];

        $heroImg = $img['hero'] ?? '';
        $galleryImgs = [
            $img['gallery1'] ?? '',
            $img['gallery2'] ?? '',
            $img['gallery3'] ?? '',
        ];

        $col = $this->colors();
        $fnt = $this->fonts();
        $sections = [];

        // ═══════════════════════════════════════════════════════════
        // 1. HERO — Reversed split-screen (image LEFT, text RIGHT)
        // ═══════════════════════════════════════════════════════════
        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'row',
            'flex_direction_mobile' => 'column',
            'flex_direction_tablet' => 'column',
            'flex_wrap' => 'nowrap',
            'flex_gap' => ['size' => 0, 'unit' => 'px', 'column' => '0', 'row' => '0'],
            'min_height' => ['size' => 600, 'unit' => 'px', 'sizes' => []],
            'min_height_mobile' => ['size' => 0, 'unit' => 'px', 'sizes' => []],
            'padding' => self::pad(0),
            '_element_id' => 'hero',
        ], [
            // LEFT — Hero image with green tint overlay (55%)
            self::container(array_merge([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'flex_justify_content' => 'center',
                'background_background' => 'classic',
                'background_image' => ['url' => $heroImg, 'id' => ''],
                'background_position' => 'center center',
                'background_size' => 'cover',
                'min_height' => ['size' => 600, 'unit' => 'px', 'sizes' => []],
                'min_height_mobile' => ['size' => 350, 'unit' => 'px', 'sizes' => []],
                'padding' => self::pad(0),
                'custom_css' => "selector{position:relative;overflow:hidden;}
selector::after{content:'';position:absolute;inset:0;background:linear-gradient(135deg,rgba(45,106,79,.55) 0%,rgba(45,106,79,.25) 100%);z-index:1;}",
            ], self::rWidth(55, 100, 100)), []),

            // RIGHT — White background with text content (45%)
            self::container(array_merge([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'flex_justify_content' => 'center',
                'background_background' => 'classic',
                'background_color' => '#FFFFFF',
                'padding' => self::pad(60, 56),
                'padding_mobile' => self::pad(40, 24),
                'padding_tablet' => self::pad(50, 40),
            ], self::rWidth(45, 100, 100)), [
                // Free Estimate badge
                self::html('<div style="display:inline-flex;align-items:center;gap:8px;background:rgba(45,106,79,.07);border:1px solid rgba(45,106,79,.15);border-radius:20px;padding:6px 16px 6px 10px;margin-bottom:24px;opacity:0;animation:fadeUp .6s ease .15s forwards;"><span style="display:flex;align-items:center;justify-content:center;width:24px;height:24px;background:' . $col['primary'] . ';border-radius:50%;color:#FFF;font-size:12px;">✓</span><span style="font-family:\'' . $fnt['body'] . '\',sans-serif;font-size:13px;font-weight:600;color:' . $col['primary'] . ';letter-spacing:.5px;">FREE ESTIMATE</span></div>'),

                self::heading($heroTitle, 'h1', array_merge(
                    self::responsiveSize(48, 38, 30),
                    [
                        'title_color' => $col['text'],
                        'typography_typography' => 'custom',
                        'typography_font_family' => $fnt['heading'],
                        'typography_font_weight' => '900',
                        'typography_line_height' => ['size' => 1.15, 'unit' => 'em'],
                        '_margin' => self::margin(0, 0, 20, 0),
                        'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .3s forwards;}',
                    ]
                )),

                self::textEditor('<p>' . e($heroSub) . '</p>', [
                    'text_color' => $col['muted'],
                    'typography_typography' => 'custom',
                    'typography_font_family' => $fnt['body'],
                    'typography_font_size' => ['size' => 16, 'unit' => 'px'],
                    'typography_line_height' => ['size' => 1.75, 'unit' => 'em'],
                    'typography_font_weight' => '400',
                    '_margin' => self::margin(0, 0, 28, 0),
                    'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .45s forwards;}',
                ]),

                // Buttons row
                self::container([
                    'flex_direction' => 'row',
                    'flex_direction_mobile' => 'column',
                    'flex_align_items' => 'center',
                    'flex_gap' => ['size' => 12, 'unit' => 'px', 'column' => '12', 'row' => '12'],
                    'content_width' => 'full',
                    'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .6s forwards;}',
                ], [
                    self::button($heroCta, $heroCtaUrl, [
                        'button_type' => 'default',
                        'background_color' => $col['primary'],
                        'button_text_color' => '#FFFFFF',
                        'typography_typography' => 'custom',
                        'typography_font_family' => $fnt['body'],
                        'typography_font_size' => ['size' => 15, 'unit' => 'px'],
                        'typography_font_weight' => '600',
                        'border_radius' => self::radius(6),
                        'button_padding' => self::pad(16, 36),
                        'button_background_hover_color' => $col['secondary'],
                        'custom_css' => 'selector .elementor-button:hover{transform:translateY(-2px);box-shadow:0 12px 32px rgba(45,106,79,.35);}',
                    ]),
                    self::button($c['hero_ghost_cta'] ?? 'Our Process', '#process', [
                        'button_type' => 'default',
                        'background_color' => 'transparent',
                        'button_text_color' => $col['primary'],
                        'typography_typography' => 'custom',
                        'typography_font_family' => $fnt['body'],
                        'typography_font_size' => ['size' => 15, 'unit' => 'px'],
                        'typography_font_weight' => '600',
                        'border_border' => 'solid',
                        'border_width' => self::pad(1),
                        'border_color' => $col['primary'],
                        'border_radius' => self::radius(6),
                        'button_padding' => self::pad(15, 34),
                    ]),
                ]),

                // Trust indicator
                self::html('<div style="display:flex;align-items:center;gap:16px;margin-top:32px;padding-top:24px;border-top:1px solid ' . $col['border'] . ';opacity:0;animation:fadeUp .7s ease .75s forwards;"><div style="display:flex;align-items:center;gap:-6px;">
<span style="display:inline-block;width:32px;height:32px;border-radius:50%;background:' . $col['accent'] . ';border:2px solid #FFF;font-size:14px;display:flex;align-items:center;justify-content:center;">★</span></div><span style="font-family:\'' . $fnt['body'] . '\',sans-serif;font-size:13px;color:' . $col['muted'] . ';line-height:1.5;">Trusted by <strong style="color:' . $col['text'] . ';">' . ($stats[0]['number'] ?? '500') . '+</strong> property owners</span></div>'),
            ]),
        ]);

        // ═══════════════════════════════════════════════════════════
        // 2. PROCESS TIMELINE — Numbered horizontal steps (unique!)
        // ═══════════════════════════════════════════════════════════
        $stepHtml = '';
        foreach ($processSteps as $i => $step) {
            $num = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
            $isLast = ($i === count($processSteps) - 1);
            $connector = $isLast ? '' : '<div class="forest-timeline-line"></div>';
            $stepHtml .= '<div class="forest-step sr d' . min($i + 1, 4) . '">
                <div class="forest-step-num">' . $num . '</div>
                ' . $connector . '
                <h4>' . e($step['title']) . '</h4>
                <p>' . e($step['desc']) . '</p>
            </div>';
        }

        $timelineHtml = '<div class="forest-timeline">' . $stepHtml . '</div>
<style>
.forest-timeline{display:flex;gap:0;justify-content:center;align-items:flex-start;width:100%;position:relative;}
.forest-step{flex:1;text-align:center;padding:0 20px;position:relative;}
.forest-step-num{display:inline-flex;align-items:center;justify-content:center;width:64px;height:64px;border-radius:50%;background:rgba(45,106,79,.08);border:2px solid ' . $col['primary'] . ';font-family:\'' . $fnt['heading'] . '\',serif;font-size:24px;font-weight:900;color:' . $col['primary'] . ';margin-bottom:20px;position:relative;z-index:2;}
.forest-timeline-line{position:absolute;top:32px;left:calc(50% + 40px);width:calc(100% - 80px);height:2px;background:repeating-linear-gradient(90deg,' . $col['accent'] . ' 0,' . $col['accent'] . ' 8px,transparent 8px,transparent 14px);z-index:1;}
.forest-step h4{font-family:\'' . $fnt['heading'] . '\',serif;font-size:18px;font-weight:700;color:' . $col['text'] . ';margin:0 0 8px 0;}
.forest-step p{font-family:\'' . $fnt['body'] . '\',sans-serif;font-size:14px;color:' . $col['muted'] . ';line-height:1.7;margin:0;}
@media(max-width:767px){
.forest-timeline{flex-direction:column;gap:32px;align-items:flex-start;}
.forest-step{text-align:left;display:flex;flex-wrap:wrap;align-items:center;gap:16px;padding:0;}
.forest-step-num{width:48px;height:48px;font-size:18px;margin-bottom:0;flex-shrink:0;}
.forest-step h4,.forest-step p{width:calc(100% - 68px);}
.forest-step p{margin-top:-10px;margin-left:64px;width:calc(100% - 64px);}
.forest-timeline-line{display:none;}
}
</style>';

        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $col['surface'],
            '_element_id' => 'process',
        ], [
            $this->eyebrow($c['process_eyebrow'] ?? 'How We Work'),
            $this->headline($c['process_title'] ?? 'Our Proven Process', 'h2', [
                '_margin' => self::margin(0, 0, 16, 0),
            ]),
            $this->bodyText($c['process_subtitle'] ?? 'A streamlined approach that ensures quality results on every project.', [
                'align' => 'center',
                'custom_css' => 'selector{max-width:520px;margin-left:auto;margin-right:auto;}',
                '_margin' => self::margin(0, 0, 56, 0),
            ]),
            self::html($timelineHtml),
        ], 'process');

        // ═══════════════════════════════════════════════════════════
        // 3. SERVICES — Property-card style with top images
        // ═══════════════════════════════════════════════════════════
        $serviceCards = [];
        foreach ($services as $i => $svc) {
            $cardImg = $galleryImgs[$i] ?? ($galleryImgs[0] ?? $heroImg);
            $serviceCards[] = self::container([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'width' => ['size' => 31, 'unit' => '%', 'sizes' => []],
                'width_tablet' => ['size' => 48, 'unit' => '%', 'sizes' => []],
                'width_mobile' => ['size' => 100, 'unit' => '%', 'sizes' => []],
                'padding' => self::pad(0),
                'background_background' => 'classic',
                'background_color' => '#FFFFFF',
                'border_radius' => self::radius(10),
                'custom_css' => 'selector{overflow:hidden;box-shadow:0 4px 24px rgba(27,27,24,.06);transition:transform .4s ease,box-shadow .4s ease;} selector:hover{transform:translateY(-6px);box-shadow:0 16px 48px rgba(27,27,24,.1);}',
                'css_classes' => 'sr d' . min($i + 1, 3),
            ], [
                // Card image top
                self::container([
                    'content_width' => 'full',
                    'min_height' => ['size' => 220, 'unit' => 'px', 'sizes' => []],
                    'min_height_mobile' => ['size' => 180, 'unit' => 'px', 'sizes' => []],
                    'background_background' => 'classic',
                    'background_image' => ['url' => $cardImg, 'id' => ''],
                    'background_position' => 'center center',
                    'background_size' => 'cover',
                    'padding' => self::pad(0),
                    'custom_css' => 'selector{overflow:hidden;transition:transform .6s ease;}',
                ], []),
                // Card content below
                self::container([
                    'content_width' => 'full',
                    'flex_direction' => 'column',
                    'padding' => self::pad(28, 28, 32, 28),
                ], [
                    self::heading($svc['title'], 'h3', [
                        'title_color' => $col['text'],
                        'typography_typography' => 'custom',
                        'typography_font_family' => $fnt['heading'],
                        'typography_font_size' => ['size' => 20, 'unit' => 'px'],
                        'typography_font_weight' => '700',
                        '_margin' => self::margin(0, 0, 10, 0),
                    ]),
                    $this->bodyText($svc['desc'], [
                        'typography_font_size' => ['size' => 14, 'unit' => 'px'],
                        'typography_line_height' => ['size' => 1.7, 'unit' => 'em'],
                    ]),
                ]),
            ]);
        }

        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $col['bg'],
        ], [
            $this->eyebrow($c['services_eyebrow'] ?? 'Our Services'),
            $this->headline($c['services_title'] ?? 'What We Do', 'h2', [
                '_margin' => self::margin(0, 0, 12, 0),
            ]),
            $this->bodyText($c['services_subtitle'] ?? 'Expert solutions tailored to your project needs.', [
                'align' => 'center',
                'custom_css' => 'selector{max-width:520px;margin-left:auto;margin-right:auto;}',
                '_margin' => self::margin(0, 0, 48, 0),
            ]),
            self::container([
                'flex_direction' => 'row',
                'flex_direction_mobile' => 'column',
                'flex_wrap' => 'wrap',
                'content_width' => 'full',
                'flex_gap' => ['size' => 28, 'unit' => 'px', 'column' => '28', 'row' => '28'],
            ], $serviceCards),
        ], 'services');

        // ═══════════════════════════════════════════════════════════
        // 4. STATS — Dark green bar with white counters
        // ═══════════════════════════════════════════════════════════
        $statElements = [];
        foreach ($stats as $i => $s) {
            $statElements[] = self::container([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'width' => ['size' => round(100 / max(count($stats), 1), 2), 'unit' => '%', 'sizes' => []],
                'width_tablet' => ['size' => 48, 'unit' => '%', 'sizes' => []],
                'width_mobile' => ['size' => 100, 'unit' => '%', 'sizes' => []],
                'padding' => self::pad(48, 20),
                'css_classes' => 'sr d' . min($i + 1, 4),
            ], [
                self::html('<div style="font-family:\'' . $fnt['heading'] . '\',serif;font-size:52px;font-weight:900;color:#FFFFFF;line-height:1;" data-count="' . e($s['number']) . '" data-suffix="' . e($s['suffix'] ?? '') . '">' . e($s['number']) . e($s['suffix'] ?? '') . '</div>'),
                self::html('<div style="font-family:\'' . $fnt['body'] . '\',sans-serif;font-size:13px;font-weight:600;letter-spacing:2px;text-transform:uppercase;color:rgba(255,255,255,.6);margin-top:10px;">' . e($s['label']) . '</div>'),
            ]);
        }

        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'row',
            'flex_direction_mobile' => 'column',
            'flex_wrap' => 'wrap',
            'flex_gap' => ['size' => 0, 'unit' => 'px', 'column' => '0', 'row' => '0'],
            'padding' => self::pad(0, 64),
            'padding_mobile' => self::pad(0, 20),
            'background_background' => 'classic',
            'background_color' => $col['primary'],
            'custom_css' => "selector{position:relative;overflow:hidden;}
selector::before{content:'';position:absolute;inset:0;background:linear-gradient(135deg,rgba(45,106,79,1) 0%,rgba(64,145,108,.85) 100%);z-index:0;}
selector>.e-con-inner,selector>.elementor-widget,selector>.e-con{position:relative;z-index:1;}",
        ], $statElements);

        // ═══════════════════════════════════════════════════════════
        // 5. TESTIMONIALS — 2 equal cards side by side
        // ═══════════════════════════════════════════════════════════
        $testCards = [];
        $testSlice = array_slice($testimonials, 0, 2);
        foreach ($testSlice as $i => $t) {
            $testCards[] = self::container(array_merge([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'flex_justify_content' => 'space-between',
                'padding' => self::pad(40),
                'background_background' => 'classic',
                'background_color' => '#FFFFFF',
                'border_border' => 'solid',
                'border_width' => self::pad(1),
                'border_color' => $col['border'],
                'border_radius' => self::radius(10),
                'css_classes' => 'sr d' . ($i + 1),
                'custom_css' => 'selector{transition:transform .35s ease,box-shadow .35s ease;} selector:hover{transform:translateY(-4px);box-shadow:0 16px 40px rgba(27,27,24,.08);}',
            ], self::rWidth(48, 48, 100)), [
                self::container(['content_width' => 'full', 'flex_direction' => 'column'], [
                    self::html('<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;"><div style="color:#D4A017;letter-spacing:2px;font-size:16px;">★★★★★</div><div style="font-family:\'' . $fnt['heading'] . '\',serif;font-size:56px;line-height:1;color:' . $col['accent'] . ';">"</div></div>'),
                    self::textEditor('<p>' . e($t['quote']) . '</p>', [
                        'text_color' => $col['text'],
                        'typography_typography' => 'custom',
                        'typography_font_family' => $fnt['body'],
                        'typography_font_size' => ['size' => 16, 'unit' => 'px'],
                        'typography_line_height' => ['size' => 1.8, 'unit' => 'em'],
                        '_margin' => self::margin(0, 0, 28, 0),
                    ]),
                ]),
                self::html('<div style="display:flex;align-items:center;gap:14px;padding-top:20px;border-top:1px solid ' . $col['border'] . ';"><div style="width:46px;height:46px;border-radius:50%;background:rgba(45,106,79,.08);display:flex;align-items:center;justify-content:center;font-family:\'' . $fnt['heading'] . '\',serif;font-size:16px;font-weight:700;color:' . $col['primary'] . ';">' . e($t['initials'] ?? 'AB') . '</div><div><h5 style="font-family:\'' . $fnt['heading'] . '\',serif;font-size:15px;font-weight:700;color:' . $col['text'] . ';margin:0;">' . e($t['name']) . '</h5><span style="font-size:13px;color:' . $col['muted'] . ';">' . e($t['role']) . '</span></div></div>'),
            ]);
        }

        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $col['surface'],
        ], [
            $this->eyebrow($c['testimonials_eyebrow'] ?? 'Testimonials'),
            $this->headline($c['testimonials_title'] ?? 'What Our Clients Say', 'h2', [
                '_margin' => self::margin(0, 0, 48, 0),
            ]),
            self::container([
                'flex_direction' => 'row',
                'flex_direction_mobile' => 'column',
                'flex_wrap' => 'wrap',
                'content_width' => 'full',
                'flex_gap' => ['size' => 28, 'unit' => 'px', 'column' => '28', 'row' => '28'],
                'flex_align_items' => 'stretch',
            ], $testCards),
        ], 'testimonials');

        // ═══════════════════════════════════════════════════════════
        // 6. CTA — Green gradient with white text
        // ═══════════════════════════════════════════════════════════
        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'column',
            'flex_align_items' => 'center',
            'flex_justify_content' => 'center',
            'padding' => self::pad(100, 64),
            'padding_mobile' => self::pad(60, 24),
            'padding_tablet' => self::pad(80, 40),
            'background_background' => 'classic',
            'background_color' => $col['primary'],
            '_element_id' => 'cta',
            'custom_css' => "selector{position:relative;overflow:hidden;}
selector::before{content:'';position:absolute;inset:0;background:linear-gradient(135deg,#2D6A4F 0%,#40916C 50%,#2D6A4F 100%);z-index:0;}
selector::after{content:'';position:absolute;top:-50%;right:-20%;width:500px;height:500px;border-radius:50%;background:rgba(149,213,178,.1);z-index:0;}
selector>.e-con-inner,selector>.elementor-widget{position:relative;z-index:1;}",
        ], [
            self::html('<div class="sr" style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.12);border-radius:20px;padding:6px 18px;margin-bottom:20px;"><span style="font-family:\'' . $fnt['body'] . '\',sans-serif;font-size:12px;font-weight:600;letter-spacing:2px;text-transform:uppercase;color:' . $col['accent'] . ';">' . e($c['cta_eyebrow'] ?? 'Ready to Start?') . '</span></div>'),
            self::heading($c['cta_title'] ?? 'Get Your Free Estimate Today', 'h2', array_merge(
                self::responsiveSize(44, 34, 26),
                [
                    'title_color' => '#FFFFFF',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $fnt['heading'],
                    'typography_font_weight' => '900',
                    'typography_line_height' => ['size' => 1.2, 'unit' => 'em'],
                    'align' => 'center',
                    '_margin' => self::margin(0, 0, 16, 0),
                    'custom_css' => 'selector{max-width:600px;}',
                ]
            )),
            $this->bodyText($c['cta_text'] ?? 'Contact us today for a free consultation. We\'ll assess your project, provide a detailed estimate, and get you on the path to your dream build.', [
                'align' => 'center',
                'text_color' => 'rgba(255,255,255,0.7)',
                'typography_font_size' => ['size' => 16, 'unit' => 'px'],
                'custom_css' => 'selector{max-width:520px;margin-left:auto;margin-right:auto;}',
                '_margin' => self::margin(0, 0, 36, 0),
            ]),
            self::button($c['cta_button'] ?? 'Get Your Free Estimate', '#contact', [
                'button_type' => 'default',
                'background_color' => '#FFFFFF',
                'button_text_color' => $col['primary'],
                'typography_typography' => 'custom',
                'typography_font_family' => $fnt['body'],
                'typography_font_size' => ['size' => 15, 'unit' => 'px'],
                'typography_font_weight' => '700',
                'border_radius' => self::radius(6),
                'button_padding' => self::pad(18, 44),
                'custom_css' => 'selector .elementor-button{transition:all .3s ease;} selector .elementor-button:hover{transform:translateY(-2px);box-shadow:0 12px 32px rgba(0,0,0,.2);}',
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
            'content_width' => 'full',
            'flex_direction' => 'column',
            'flex_justify_content' => 'center',
            'padding' => self::pad(140, 64, 100, 64),
            'padding_mobile' => self::pad(100, 20, 60, 20),
            'padding_tablet' => self::pad(120, 40, 80, 40),
            'min_height' => ['size' => 50, 'unit' => 'vh', 'sizes' => []],
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
                'content_width' => 'full',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'width' => ['size' => round(100 / count($stats), 2), 'unit' => '%', 'sizes' => []],
                'width_tablet' => ['size' => 48, 'unit' => '%', 'sizes' => []],
                'width_mobile' => ['size' => 100, 'unit' => '%', 'sizes' => []],
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
            'content_width' => 'full',
            'flex_direction' => 'column',
            'flex_justify_content' => 'center',
            'padding' => self::pad(140, 64, 100, 64),
            'padding_mobile' => self::pad(100, 20, 60, 20),
            'padding_tablet' => self::pad(120, 40, 80, 40),
            'min_height' => ['size' => 50, 'unit' => 'vh', 'sizes' => []],
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
                'content_width' => 'full',
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
                'flex_gap' => ['size' => 24, 'unit' => 'px', 'column' => '24', 'row' => '24'],
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
            'content_width' => 'full',
            'flex_direction' => 'column',
            'flex_justify_content' => 'center',
            'padding' => self::pad(140, 64, 100, 64),
            'padding_mobile' => self::pad(100, 20, 60, 20),
            'padding_tablet' => self::pad(120, 40, 80, 40),
            'min_height' => ['size' => 50, 'unit' => 'vh', 'sizes' => []],
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
                'content_width' => 'full',
                'width' => ['size' => 31, 'unit' => '%', 'sizes' => []],
                'width_tablet' => ['size' => 48, 'unit' => '%', 'sizes' => []],
                'width_mobile' => ['size' => 100, 'unit' => '%', 'sizes' => []],
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
                'content_width' => 'full',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'width' => ['size' => 31, 'unit' => '%', 'sizes' => []],
                'width_tablet' => ['size' => 48, 'unit' => '%', 'sizes' => []],
                'width_mobile' => ['size' => 100, 'unit' => '%', 'sizes' => []],
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
                'flex_direction_mobile' => 'column',
                'flex_wrap' => 'wrap',
                'content_width' => 'full',
                'flex_gap' => ['size' => 24, 'unit' => 'px', 'column' => '24', 'row' => '24'],
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
        $c = $this->colors();
        $f = $this->fonts();
        $navLinks = '';
        foreach ($pages as $slug => $label) {
            $url = ($slug === 'home') ? '/' : '/' . $slug . '/';
            $navLinks .= '<li><a href="' . $url . '">' . e($label) . '</a></li>';
        }

        return [self::html('
<!-- FOREST UTILITY BAR -->
<div class="forest-utility" id="forestUtility" style="height:36px;background:' . $c['primary'] . ';display:flex;align-items:center;justify-content:space-between;padding:0 64px;transition:margin-top .35s ease;">
<div style="display:flex;align-items:center;gap:24px;">
<span style="font-family:\'' . $f['body'] . '\',sans-serif;font-size:12px;color:#fff;font-weight:400;letter-spacing:.2px;">&#x1F4DE; (555) 123-4567</span>
<span style="font-family:\'' . $f['body'] . '\',sans-serif;font-size:12px;color:#fff;font-weight:400;letter-spacing:.2px;">&#x2709; info@company.com</span>
</div>
<div style="display:flex;align-items:center;gap:24px;">
<span style="font-family:\'' . $f['body'] . '\',sans-serif;font-size:12px;color:rgba(255,255,255,.8);font-weight:400;">&#x1F550; Mon-Sat: 7AM - 6PM</span>
<span style="font-family:\'' . $f['body'] . '\',sans-serif;font-size:12px;color:rgba(255,255,255,.9);font-weight:600;">Licensed &amp; Insured &#x2713;</span>
</div>
</div>
<!-- FOREST MAIN NAV -->
<nav class="forest-nav" id="forestNav" style="position:relative;top:0;left:0;right:0;z-index:1000;padding:0 64px;height:72px;display:flex;align-items:center;justify-content:space-between;background:#fff;transition:box-shadow .35s ease;">
<a href="/" class="forest-logo" style="font-family:\'' . $f['heading'] . '\',serif;font-size:22px;font-weight:900;color:' . $c['text'] . ';text-decoration:none;line-height:1;">' . e($siteName) . '</a>
<ul class="forest-nav-links" style="display:flex;gap:0;list-style:none;padding:0;margin:0;">' . $navLinks . '</ul>
<div style="display:flex;align-items:center;gap:12px;">
<a href="/contact/" class="forest-cta" style="padding:11px 26px;background:' . $c['primary'] . ';color:#fff;font-family:\'' . $f['body'] . '\',sans-serif;font-size:13px;font-weight:700;border-radius:6px;text-decoration:none;transition:background .3s,transform .2s;display:inline-block;">Get Free Estimate</a>
<a href="tel:5551234567" class="forest-call-btn" style="display:none;width:40px;height:40px;border-radius:50%;background:' . $c['primary'] . ';color:#fff;align-items:center;justify-content:center;text-decoration:none;font-size:18px;">&#x1F4DE;</a>
</div>
</nav>
<style>
.forest-nav-links a{display:block;padding:8px 18px;font-size:13px;font-weight:500;color:' . $c['muted'] . ';text-decoration:none;transition:color .3s;position:relative;font-family:\'' . $f['body'] . '\',sans-serif;text-transform:none;}
.forest-nav-links a::after{content:\'\';position:absolute;bottom:2px;left:18px;right:18px;height:2px;background:' . $c['primary'] . ';transform:scaleX(0);transform-origin:center;transition:transform .3s;}
.forest-nav-links a:hover{color:' . $c['text'] . ';}
.forest-nav-links a:hover::after{transform:scaleX(1);}
.forest-cta:hover{background:' . $c['secondary'] . '!important;transform:translateY(-1px);}
.forest-nav.sticky{position:fixed!important;top:0!important;left:0;right:0;box-shadow:0 2px 24px rgba(27,27,24,.08);animation:forestSlideDown .35s ease;}
@keyframes forestSlideDown{from{transform:translateY(-100%);}to{transform:translateY(0);}}
@media(max-width:1100px){
.forest-utility{display:none!important;}
.forest-nav-links{display:none!important;}
.forest-cta{display:none!important;}
.forest-call-btn{display:flex!important;}
.forest-nav{padding:0 20px!important;height:60px!important;}
}
</style>
<script>
(function(){
var ut=document.getElementById("forestUtility"),nv=document.getElementById("forestNav"),stuck=false;
window.addEventListener("scroll",function(){
if(window.scrollY>60&&!stuck){stuck=true;ut.style.marginTop="-36px";nv.classList.add("sticky");}
else if(window.scrollY<=10&&stuck){stuck=false;ut.style.marginTop="0";nv.classList.remove("sticky");}
});
})();
</script>')];
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

        // Build contact items for Col4
        $contactItems = '';
        if ($address) {
            $contactItems .= '<div class="forest-ft-ci"><span class="forest-ft-ci-icon">&#x1F4CD;</span><span>' . e($address) . '</span></div>';
        }
        if ($phone) {
            $contactItems .= '<div class="forest-ft-ci"><span class="forest-ft-ci-icon">&#x1F4DE;</span><a href="tel:' . e($phone) . '" style="color:' . $c['text'] . ';text-decoration:none;">' . e($phone) . '</a></div>';
        }
        $contactItems .= '<div class="forest-ft-ci"><span class="forest-ft-ci-icon">&#x2709;</span><a href="mailto:' . e($email) . '" style="color:' . $c['text'] . ';text-decoration:none;">' . e($email) . '</a></div>';

        $sections = [];

        // ── MAIN FOOTER: 4-column grid ──
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
                'flex_gap' => ['size' => 40, 'unit' => 'px', 'column' => '40', 'row' => '40'],
                'padding' => self::pad(72, 64, 56, 64),
                'padding_mobile' => self::pad(40, 24, 32, 24),
                'custom_css' => 'selector{display:grid;grid-template-columns:1.4fr 1fr 1fr 1.2fr;}@media(max-width:767px){selector{grid-template-columns:1fr!important;gap:32px!important;}}@media(min-width:768px) and (max-width:1100px){selector{grid-template-columns:1fr 1fr!important;gap:36px!important;}}',
            ], [
                // Col1: Brand + tagline + description + social
                self::html('<div>
<div style="font-family:\'' . $f['heading'] . '\',serif;font-size:22px;font-weight:900;color:' . $c['text'] . ';margin-bottom:8px;line-height:1.2;">' . e($siteName) . '</div>
<div style="font-family:\'' . $f['body'] . '\',sans-serif;font-size:13px;font-weight:600;color:' . $c['primary'] . ';margin-bottom:18px;letter-spacing:.3px;">Quality Craftsmanship Since 2010</div>
<p style="font-family:\'' . $f['body'] . '\',sans-serif;font-size:14px;color:' . $c['muted'] . ';line-height:1.75;max-width:300px;margin:0 0 24px 0;">' . e($contact['footer_text'] ?? 'Delivering exceptional craftsmanship and professional service for every project, backed by years of experience.') . '</p>
<div class="forest-ft-social">
<a href="#" title="Facebook" style="width:36px;height:36px;border-radius:50%;background:' . $c['primary'] . ';color:#fff;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;font-size:15px;margin-right:8px;transition:background .3s;">f</a>
<a href="#" title="Instagram" style="width:36px;height:36px;border-radius:50%;background:' . $c['primary'] . ';color:#fff;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;font-size:15px;margin-right:8px;transition:background .3s;">&#x1F4F7;</a>
<a href="#" title="LinkedIn" style="width:36px;height:36px;border-radius:50%;background:' . $c['primary'] . ';color:#fff;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;font-size:15px;transition:background .3s;">in</a>
</div>
</div>'),
                // Col2: Our Services
                self::html('<div class="forest-ft-col">
<h4 style="font-family:\'' . $f['heading'] . '\',serif;font-size:16px;font-weight:700;color:' . $c['text'] . ';margin:0 0 20px 0;position:relative;padding-bottom:12px;">Our Services<span style="position:absolute;bottom:0;left:0;width:36px;height:3px;background:' . $c['primary'] . ';border-radius:2px;"></span></h4>
<ul class="forest-ft-links">
<li><a href="/services/">General Construction</a></li>
<li><a href="/services/">Home Renovations</a></li>
<li><a href="/services/">Commercial Projects</a></li>
<li><a href="/services/">Landscape Design</a></li>
<li><a href="/services/">Property Management</a></li>
<li><a href="/services/">Custom Builds</a></li>
</ul>
</div>'),
                // Col3: Quick Links
                self::html('<div class="forest-ft-col">
<h4 style="font-family:\'' . $f['heading'] . '\',serif;font-size:16px;font-weight:700;color:' . $c['text'] . ';margin:0 0 20px 0;position:relative;padding-bottom:12px;">Quick Links<span style="position:absolute;bottom:0;left:0;width:36px;height:3px;background:' . $c['primary'] . ';border-radius:2px;"></span></h4>
<ul class="forest-ft-links">' . $navLinks . '</ul>
</div>'),
                // Col4: Get in Touch
                self::html('<div class="forest-ft-col">
<h4 style="font-family:\'' . $f['heading'] . '\',serif;font-size:16px;font-weight:700;color:' . $c['text'] . ';margin:0 0 20px 0;position:relative;padding-bottom:12px;">Get in Touch<span style="position:absolute;bottom:0;left:0;width:36px;height:3px;background:' . $c['primary'] . ';border-radius:2px;"></span></h4>
' . $contactItems . '
</div>'),
            ]),

            // ── SERVICE AREAS STRIP ──
            self::container([
                'content_width' => 'full',
                'flex_direction' => 'row',
                'flex_justify_content' => 'center',
                'flex_align_items' => 'center',
                'padding' => self::pad(14, 64),
                'background_background' => 'classic',
                'background_color' => $c['primary'],
            ], [
                self::textEditor('<p style="font-family:\'' . $f['body'] . '\',sans-serif;font-size:12px;color:rgba(255,255,255,.85);text-align:center;margin:0;letter-spacing:.4px;">Serving: &nbsp; Lahore &nbsp;|&nbsp; Islamabad &nbsp;|&nbsp; Karachi &nbsp;|&nbsp; Faisalabad &nbsp;|&nbsp; Multan</p>'),
            ]),

            // ── TRUST BADGES ROW ──
            self::container([
                'content_width' => 'full',
                'flex_direction' => 'row',
                'flex_justify_content' => 'center',
                'flex_align_items' => 'center',
                'flex_gap' => ['size' => 32, 'unit' => 'px', 'column' => '32', 'row' => '16'],
                'flex_wrap' => 'wrap',
                'padding' => self::pad(20, 64),
                'padding_mobile' => self::pad(16, 20),
                'background_background' => 'classic',
                'background_color' => $c['surface2'],
                'border_border' => 'solid',
                'border_width' => self::pad(1, 0, 0, 0),
                'border_color' => $c['border'],
            ], [
                self::html('<div class="forest-ft-badges">
<span class="forest-badge"><span class="forest-badge-check">&#x2713;</span> Licensed &amp; Insured</span>
<span class="forest-badge"><span class="forest-badge-check">&#x2713;</span> Free Estimates</span>
<span class="forest-badge"><span class="forest-badge-check">&#x2713;</span> 24/7 Emergency</span>
<span class="forest-badge"><span class="forest-badge-check">&#x2713;</span> 5-Star Rated</span>
</div>'),
            ]),

            // ── BOTTOM BAR: dark background ──
            self::container([
                'content_width' => 'full',
                'flex_direction' => 'row',
                'flex_justify_content' => 'space-between',
                'flex_align_items' => 'center',
                'padding' => self::pad(18, 64),
                'padding_mobile' => self::pad(16, 20),
                'background_background' => 'classic',
                'background_color' => $c['text'],
                'custom_css' => '@media(max-width:767px){selector{flex-direction:column!important;gap:8px;text-align:center;}}',
            ], [
                self::textEditor('<p style="font-size:12px;color:rgba(255,255,255,.5);margin:0;">&copy; ' . date('Y') . ' <span style="color:rgba(255,255,255,.75);">' . e($siteName) . '</span>. All rights reserved.</p>'),
                self::textEditor('<p style="margin:0;"><a href="/privacy-policy/" style="font-size:12px;color:rgba(255,255,255,.45);text-decoration:none;transition:color .3s;">Privacy Policy</a> &nbsp;&nbsp;&bull;&nbsp;&nbsp; <a href="/terms/" style="font-size:12px;color:rgba(255,255,255,.45);text-decoration:none;transition:color .3s;">Terms of Service</a></p>'),
            ]),
        ]);

        // ── FOOTER STYLES ──
        $sections[] = self::html('<style>
.forest-ft-links{list-style:none;padding:0;margin:0;}
.forest-ft-links li{margin-bottom:10px;}
.forest-ft-links a{font-family:\'' . $f['body'] . '\',sans-serif;font-size:14px;color:' . $c['muted'] . ';text-decoration:none;transition:color .3s,padding-left .3s;display:inline-block;position:relative;}
.forest-ft-links a:hover{color:' . $c['primary'] . ';padding-left:6px;}
.forest-ft-ci{display:flex;align-items:flex-start;gap:10px;margin-bottom:14px;font-family:\'' . $f['body'] . '\',sans-serif;font-size:14px;color:' . $c['text'] . ';line-height:1.5;}
.forest-ft-ci-icon{font-size:16px;flex-shrink:0;margin-top:1px;}
.forest-ft-ci a:hover{color:' . $c['primary'] . '!important;}
.forest-ft-social a:hover{background:' . $c['secondary'] . '!important;}
.forest-ft-badges{display:flex;flex-wrap:wrap;gap:28px;justify-content:center;align-items:center;}
.forest-badge{font-family:\'' . $f['body'] . '\',sans-serif;font-size:11px;font-weight:600;color:' . $c['muted'] . ';text-transform:uppercase;letter-spacing:1px;display:inline-flex;align-items:center;gap:6px;}
.forest-badge-check{color:' . $c['primary'] . ';font-weight:700;font-size:13px;}
@media(max-width:767px){.forest-ft-badges{gap:16px;}.forest-badge{font-size:10px;}}
</style>');

        return $sections;
    }
}
