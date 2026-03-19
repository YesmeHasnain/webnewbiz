<?php

namespace App\Services\Layouts;

/**
 * Slate — Ultra-minimal gray layout for creative professionals.
 * Near-white backgrounds, maximum whitespace, typography-focused.
 * No borders on cards, subtle hover overlays, refined gallery aesthetic.
 * Work Sans headings (500-700 weight, clean), Inter body text.
 */
class LayoutSlate extends AbstractLayout
{
    public function slug(): string { return 'slate'; }
    public function name(): string { return 'Slate'; }
    public function description(): string { return 'Ultra-minimal design with sophisticated gray tones for creative professionals'; }
    public function bestFor(): array { return ['Portfolio', 'Photography', 'Creative', 'Architecture']; }
    public function isDark(): bool { return false; }

    public function colors(): array
    {
        return [
            'primary'   => '#334155',
            'secondary' => '#475569',
            'accent'    => '#94A3B8',
            'bg'        => '#FAFAFA',
            'surface'   => '#FFFFFF',
            'surface2'  => '#F1F5F9',
            'text'      => '#0F172A',
            'muted'     => 'rgba(15,23,42,0.5)',
            'border'    => 'rgba(15,23,42,0.06)',
        ];
    }

    public function fonts(): array
    {
        return ['heading' => 'Work Sans', 'body' => 'Inter'];
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
<link href="https://fonts.googleapis.com/css2?family={$hf}:wght@300;400;500;600;700&family={$bf}:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root{--slate-bg:{$c['bg']};--slate-surface:{$c['surface']};--slate-surface2:{$c['surface2']};--slate-primary:{$c['primary']};--slate-secondary:{$c['secondary']};--slate-accent:{$c['accent']};--slate-text:{$c['text']};--slate-muted:{$c['muted']};--slate-border:{$c['border']};}
body,body.elementor-template-canvas{background:var(--slate-bg);color:var(--slate-text);font-family:'{$f['body']}',sans-serif;overflow-x:hidden;margin:0;padding:0;}
.elementor-element,.elementor.elementor-2{font-family:'{$f['body']}',sans-serif;}
.elementor-widget{margin-bottom:0 !important;}
.e-con{--gap:0px;}
.e-con:not(.e-con--row)>.elementor-widget{width:100%;}
.e-con--row>.elementor-widget{width:auto;}

/* Animations */
@keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
@keyframes fadeIn{from{opacity:0}to{opacity:1}}
.sr{opacity:0;transform:translateY(24px);transition:opacity .8s cubic-bezier(.25,.46,.45,.94),transform .8s cubic-bezier(.25,.46,.45,.94);}
.sr.d1{transition-delay:.1s}.sr.d2{transition-delay:.2s}.sr.d3{transition-delay:.3s}.sr.d4{transition-delay:.4s}
.sr.in{opacity:1;transform:none;}

/* Eyebrow */
.eyebrow{display:inline-flex;align-items:center;gap:10px;font-size:11px;font-weight:600;letter-spacing:3px;text-transform:uppercase;color:var(--slate-accent);margin-bottom:16px;}
.eyebrow::before{content:'';width:24px;height:1px;background:var(--slate-accent);}

/* Cards — no borders, just spacing and subtle bg */
.slate-card{transition:transform .4s cubic-bezier(.25,.46,.45,.94),box-shadow .4s ease;cursor:pointer;}
.slate-card:hover{transform:translateY(-4px);box-shadow:0 20px 60px rgba(15,23,42,0.06);}

/* Stat */
.slate-stat{text-align:center;padding:40px 20px;}
.slate-stat-n{font-family:'{$f['heading']}',sans-serif;font-size:48px;font-weight:700;color:var(--slate-primary);line-height:1;letter-spacing:-1px;}
.slate-stat-l{font-size:11px;letter-spacing:2px;text-transform:uppercase;color:var(--slate-accent);margin-top:8px;font-weight:500;}

/* Portfolio grid */
.slate-photo{overflow:hidden;position:relative;}
.slate-photo img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .8s cubic-bezier(.25,.46,.45,.94);}
.slate-photo:hover img{transform:scale(1.04);}
.slate-overlay{position:absolute;inset:0;background:rgba(15,23,42,0.4);opacity:0;transition:opacity .5s ease;display:flex;align-items:center;justify-content:center;}
.slate-photo:hover .slate-overlay{opacity:1;}
.slate-overlay span{font-family:'{$f['heading']}',sans-serif;font-size:14px;font-weight:600;letter-spacing:2px;text-transform:uppercase;color:#FFFFFF;transform:translateY(8px);transition:transform .4s ease;opacity:0;}
.slate-photo:hover .slate-overlay span{transform:translateY(0);opacity:1;transition-delay:.1s;}

/* Testimonial */
.slate-tcard{transition:transform .4s ease;}
.slate-tcard:hover{transform:translateY(-3px);}

/* Footer */
.slate-fc h4{font-size:11px;font-weight:600;letter-spacing:3px;text-transform:uppercase;color:var(--slate-accent);margin-bottom:20px;}
.slate-fc ul{list-style:none;display:flex;flex-direction:column;gap:10px;padding:0;margin:0;}
.slate-fc a{font-size:14px;color:rgba(255,255,255,0.5);text-decoration:none;transition:color .3s;}
.slate-fc a:hover{color:rgba(255,255,255,0.85);}

/* Contact info */
.slate-ci{display:flex;align-items:flex-start;gap:12px;margin-bottom:16px;}
.slate-ci-i{width:36px;height:36px;min-width:36px;background:rgba(148,163,184,0.1);display:flex;align-items:center;justify-content:center;font-size:16px;}
.slate-ci small{display:block;font-size:9px;color:rgba(15,23,42,0.3);letter-spacing:2px;text-transform:uppercase;margin-bottom:2px;}
.slate-ci span{font-size:13px;color:var(--slate-muted);}

/* CTA Section */
.slate-cta{position:relative;}
.slate-cta::before{content:'';position:absolute;inset:0;background:linear-gradient(135deg,{$c['primary']} 0%,{$c['secondary']} 100%);z-index:0;}
.slate-cta>*{position:relative;z-index:1;}

/* Back to top */
#slate-btt{position:fixed;bottom:28px;right:28px;width:42px;height:42px;background:var(--slate-primary);color:#FFFFFF;font-size:18px;display:flex;align-items:center;justify-content:center;text-decoration:none;z-index:500;opacity:0;transform:translateY(10px);pointer-events:none;transition:all .4s;box-shadow:0 4px 16px rgba(51,65,85,0.2);}
#slate-btt.show{opacity:1;transform:translateY(0);pointer-events:all;}
#slate-btt:hover{transform:translateY(-3px)!important;box-shadow:0 8px 24px rgba(51,65,85,0.3);}

/* Responsive */
@media(max-width:1100px){
  .slate-photo{min-height:200px;}
  .slate-card.e-con,.slate-tcard.e-con,.slate-photo.e-con{--width:48% !important;width:48% !important;}
}
@media(max-width:767px){
  .slate-card.e-con,.slate-tcard.e-con,.slate-stat.e-con,.slate-photo.e-con{--width:100% !important;width:100% !important;}
  .slate-nav ul{display:none !important;}
  .slate-ci{flex-direction:column;align-items:center;text-align:center;}
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
const btt=document.getElementById('slate-btt');
window.addEventListener("scroll",()=>{if(btt)btt.classList.toggle("show",scrollY>400);});
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
    // SHARED OVERRIDES — typography tuned for minimal style
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
            'typography_font_weight' => '600',
            'typography_line_height' => ['size' => 1.1, 'unit' => 'em'],
            'typography_letter_spacing' => ['size' => -0.5, 'unit' => 'px'],
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
            'typography_font_family' => $f['heading'],
            'typography_font_size' => ['size' => 13, 'unit' => 'px'],
            'typography_font_weight' => '600',
            'typography_letter_spacing' => ['size' => 1.5, 'unit' => 'px'],
            'typography_text_transform' => 'uppercase',
            'border_radius' => self::radius(0),
            'button_padding' => self::pad(16, 40),
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
            'typography_font_family' => $f['heading'],
            'typography_font_size' => ['size' => 13, 'unit' => 'px'],
            'typography_font_weight' => '600',
            'typography_letter_spacing' => ['size' => 1.5, 'unit' => 'px'],
            'typography_text_transform' => 'uppercase',
            'border_border' => 'solid',
            'border_width' => self::pad(1),
            'border_color' => 'rgba(15,23,42,0.15)',
            'border_radius' => self::radius(0),
            'button_padding' => self::pad(15, 38),
        ], $extra));
    }

    // ═══════════════════════════════════════��═══════════════════
    // HOME PAGE
    // ═══════════════════════════════════════════════════════════

    public function buildHomePage(array $c, array $img): array
    {
        $siteName = $c['site_name'] ?? 'Studio Name';
        $heroTitle = $c['hero_title'] ?? 'Design with Purpose';
        $heroSub = $c['hero_subtitle'] ?? 'Creating work that speaks for itself.';
        $heroCta = $c['hero_cta'] ?? 'View Work';
        $heroCtaUrl = $c['hero_cta_url'] ?? '#showcase';

        $aboutText = $c['about_text'] ?? 'We believe in the power of restraint. Every decision is intentional, every detail considered. Our work strips away the unnecessary to reveal what truly matters.';

        $services = $c['services'] ?? [
            ['icon' => '01', 'title' => 'Brand Identity', 'desc' => 'Building cohesive visual identities that communicate your brand essence with clarity and precision.'],
            ['icon' => '02', 'title' => 'Digital Design', 'desc' => 'Crafting elegant digital experiences that balance aesthetics with functionality and usability.'],
            ['icon' => '03', 'title' => 'Art Direction', 'desc' => 'Guiding the creative vision from concept to completion with refined taste and strategic thinking.'],
        ];

        $col = $this->colors();
        $fnt = $this->fonts();

        // Showcase images — full-width stacked portfolio pieces
        $showcaseImgs = array_filter([
            $img['hero'] ?? '',
            $img['about'] ?? '',
            $img['services'] ?? '',
        ]);

        $sections = [];

        // ─── 1. HERO — TYPOGRAPHIC STATEMENT ───
        // Text-only. No image, no background image. Massive type.
        // Feels like opening a design monograph.
        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'column',
            'flex_align_items' => 'flex-start',
            'flex_justify_content' => 'flex-end',
            'padding' => self::pad(200, 80, 100, 80),
            'padding_tablet' => self::pad(160, 40, 80, 40),
            'padding_mobile' => self::pad(120, 20, 60, 20),
            'min_height' => ['size' => 100, 'unit' => 'vh', 'sizes' => []],
            'background_background' => 'classic',
            'background_color' => '#FFFFFF',
            '_element_id' => 'hero',
        ], [
            // Massive typographic heading — portfolio cover feel
            self::heading($heroTitle, 'h1', array_merge(
                self::responsiveSize(140, 80, 52),
                [
                    'align' => 'left',
                    'title_color' => $col['text'],
                    'typography_typography' => 'custom',
                    'typography_font_family' => $fnt['heading'],
                    'typography_font_weight' => '700',
                    'typography_line_height' => ['size' => 0.9, 'unit' => 'em'],
                    'typography_letter_spacing' => ['size' => -3, 'unit' => 'px'],
                    'typography_letter_spacing_tablet' => ['size' => -2, 'unit' => 'px'],
                    'typography_letter_spacing_mobile' => ['size' => -1, 'unit' => 'px'],
                    '_margin' => self::margin(0, 0, 32, 0),
                    'custom_css' => 'selector{opacity:0;animation:fadeUp 1s ease .2s forwards;max-width:1000px;}',
                ]
            )),

            // Minimal subtitle
            self::textEditor('<p>' . e($heroSub) . '</p>', [
                'align' => 'left',
                'text_color' => $col['accent'],
                'typography_typography' => 'custom',
                'typography_font_family' => $fnt['body'],
                'typography_font_size' => ['size' => 18, 'unit' => 'px'],
                'typography_font_size_tablet' => ['size' => 16, 'unit' => 'px'],
                'typography_font_size_mobile' => ['size' => 15, 'unit' => 'px'],
                'typography_font_weight' => '400',
                'typography_line_height' => ['size' => 1.6, 'unit' => 'em'],
                '_margin' => self::margin(0, 0, 40, 0),
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .5s forwards;max-width:420px;}',
            ]),

            // Single understated button
            self::button($heroCta, $heroCtaUrl, [
                'background_color' => 'transparent',
                'button_text_color' => $col['text'],
                'typography_typography' => 'custom',
                'typography_font_family' => $fnt['heading'],
                'typography_font_size' => ['size' => 12, 'unit' => 'px'],
                'typography_font_weight' => '600',
                'typography_letter_spacing' => ['size' => 3, 'unit' => 'px'],
                'typography_text_transform' => 'uppercase',
                'border_border' => 'solid',
                'border_width' => self::pad(1),
                'border_color' => $col['text'],
                'border_radius' => self::radius(0),
                'button_padding' => self::pad(14, 36),
                'button_background_hover_color' => $col['text'],
                'hover_color' => '#FFFFFF',
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .7s forwards;}',
            ]),
        ]);

        // ─── 2. PORTFOLIO SHOWCASE — FULL-WIDTH STACKED IMAGES ───
        // Each image spans the full width with thin gaps between.
        // Clean gallery rhythm, no overlays, no text on images.
        $showcaseElements = [];
        foreach (array_slice($showcaseImgs, 0, 3) as $i => $url) {
            if (!$url) continue;
            $showcaseElements[] = self::container([
                'content_width' => 'full',
                'padding' => self::pad(0),
                'css_classes' => 'sr d' . min($i + 1, 4),
            ], [
                self::image($url, [
                    'image_size' => 'full',
                    'custom_css' => 'selector img{width:100%;height:560px;object-fit:cover;display:block;} @media(max-width:767px){selector img{height:280px;}} @media(min-width:768px) and (max-width:1024px){selector img{height:400px;}}',
                ]),
            ]);
        }

        if ($showcaseElements) {
            $sections[] = self::container([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'padding' => self::pad(0, 40),
                'padding_tablet' => self::pad(0, 24),
                'padding_mobile' => self::pad(0, 12),
                'flex_gap' => ['size' => 6, 'unit' => 'px', 'column' => '6', 'row' => '6'],
                'background_background' => 'classic',
                'background_color' => '#FFFFFF',
                '_element_id' => 'showcase',
            ], $showcaseElements);
        }

        // ─── 3. ABOUT — CENTERED EDITORIAL BLOCKQUOTE ───
        // Single column, large quote-style text. No image.
        // Feels like an editorial magazine pull-quote.
        $sections[] = $this->section([
            'padding' => self::pad(140, 80),
            'padding_tablet' => self::pad(100, 40),
            'padding_mobile' => self::pad(80, 20),
            'flex_align_items' => 'center',
            'background_background' => 'classic',
            'background_color' => $col['surface2'],
        ], [
            self::container([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'custom_css' => 'selector{max-width:720px;margin:0 auto;}',
                'css_classes' => 'sr',
            ], [
                // Thin line above — editorial separator
                self::divider([
                    'color' => $col['accent'],
                    'width' => ['size' => 60, 'unit' => 'px', 'sizes' => []],
                    'gap' => ['size' => 0, 'unit' => 'px', 'sizes' => []],
                    'align' => 'center',
                    '_margin' => self::margin(0, 0, 48, 0),
                ]),

                // Large blockquote-style text
                self::textEditor('<p>' . e($aboutText) . '</p>', [
                    'align' => 'center',
                    'text_color' => $col['text'],
                    'typography_typography' => 'custom',
                    'typography_font_family' => $fnt['heading'],
                    'typography_font_size' => ['size' => 28, 'unit' => 'px'],
                    'typography_font_size_tablet' => ['size' => 22, 'unit' => 'px'],
                    'typography_font_size_mobile' => ['size' => 18, 'unit' => 'px'],
                    'typography_font_weight' => '400',
                    'typography_line_height' => ['size' => 1.6, 'unit' => 'em'],
                    'typography_letter_spacing' => ['size' => -0.3, 'unit' => 'px'],
                    '_margin' => self::margin(0, 0, 48, 0),
                ]),

                // Attribution line
                self::textEditor('<p>' . e($siteName) . '</p>', [
                    'align' => 'center',
                    'text_color' => $col['accent'],
                    'typography_typography' => 'custom',
                    'typography_font_family' => $fnt['heading'],
                    'typography_font_size' => ['size' => 11, 'unit' => 'px'],
                    'typography_font_weight' => '600',
                    'typography_letter_spacing' => ['size' => 3, 'unit' => 'px'],
                    'typography_text_transform' => 'uppercase',
                ]),
            ]),
        ], 'about');

        // ─── 4. SERVICES — MINIMAL TEXT LIST WITH DIVIDERS ───
        // No cards, no grid, no icons. Just clean rows of title + description.
        // Separated by thin horizontal rules. Left-aligned, editorial.
        $serviceListHtml = '';
        foreach ($services as $i => $svc) {
            $num = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
            $title = e($svc['title'] ?? 'Service');
            $desc = e($svc['desc'] ?? '');
            $borderTop = $i === 0
                ? 'border-top:1px solid ' . $col['border'] . ';'
                : '';
            $serviceListHtml .= <<<HTML
<div style="{$borderTop}border-bottom:1px solid {$col['border']};padding:36px 0;display:flex;align-items:flex-start;gap:40px;flex-wrap:wrap;">
  <span style="font-family:'{$fnt['heading']}',sans-serif;font-size:12px;font-weight:500;color:{$col['accent']};letter-spacing:2px;min-width:32px;padding-top:4px;">{$num}</span>
  <div style="flex:1;min-width:200px;">
    <h3 style="font-family:'{$fnt['heading']}',sans-serif;font-size:22px;font-weight:600;color:{$col['text']};margin:0 0 8px 0;letter-spacing:-0.3px;">{$title}</h3>
    <p style="font-family:'{$fnt['body']}',sans-serif;font-size:15px;color:{$col['muted']};line-height:1.7;margin:0;max-width:520px;">{$desc}</p>
  </div>
</div>
HTML;
        }

        $sections[] = $this->section([
            'padding' => self::pad(120, 80),
            'padding_tablet' => self::pad(80, 40),
            'padding_mobile' => self::pad(60, 20),
            'background_background' => 'classic',
            'background_color' => '#FFFFFF',
        ], [
            // Section label
            self::textEditor('<p>' . e($c['services_eyebrow'] ?? 'Services') . '</p>', [
                'align' => 'left',
                'text_color' => $col['accent'],
                'typography_typography' => 'custom',
                'typography_font_family' => $fnt['heading'],
                'typography_font_size' => ['size' => 11, 'unit' => 'px'],
                'typography_font_weight' => '600',
                'typography_letter_spacing' => ['size' => 3, 'unit' => 'px'],
                'typography_text_transform' => 'uppercase',
                '_margin' => self::margin(0, 0, 48, 0),
            ]),

            // Service list via HTML widget
            self::html($serviceListHtml),
        ], 'services');

        // ─── 5. CTA — MINIMAL TEXT + SINGLE BUTTON ───
        // No background image, no overlay, no color block.
        // Just typography and a single action. Maximum restraint.
        $sections[] = $this->section([
            'padding' => self::pad(140, 80),
            'padding_tablet' => self::pad(100, 40),
            'padding_mobile' => self::pad(80, 20),
            'flex_align_items' => 'center',
            'background_background' => 'classic',
            'background_color' => $col['surface2'],
        ], [
            self::container([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'css_classes' => 'sr',
            ], [
                self::heading($c['cta_title'] ?? 'Have a project in mind?', 'h2', array_merge(
                    self::responsiveSize(48, 36, 28),
                    [
                        'align' => 'center',
                        'title_color' => $col['text'],
                        'typography_typography' => 'custom',
                        'typography_font_family' => $fnt['heading'],
                        'typography_font_weight' => '600',
                        'typography_line_height' => ['size' => 1.1, 'unit' => 'em'],
                        'typography_letter_spacing' => ['size' => -1, 'unit' => 'px'],
                        '_margin' => self::margin(0, 0, 20, 0),
                    ]
                )),

                self::textEditor('<p>' . e($c['cta_text'] ?? 'We would love to hear from you.') . '</p>', [
                    'align' => 'center',
                    'text_color' => $col['muted'],
                    'typography_typography' => 'custom',
                    'typography_font_family' => $fnt['body'],
                    'typography_font_size' => ['size' => 16, 'unit' => 'px'],
                    'typography_font_weight' => '400',
                    'typography_line_height' => ['size' => 1.6, 'unit' => 'em'],
                    '_margin' => self::margin(0, 0, 40, 0),
                ]),

                self::button($c['cta_button'] ?? 'Get in Touch', '/contact/', [
                    'background_color' => $col['text'],
                    'button_text_color' => '#FFFFFF',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $fnt['heading'],
                    'typography_font_size' => ['size' => 12, 'unit' => 'px'],
                    'typography_font_weight' => '600',
                    'typography_letter_spacing' => ['size' => 3, 'unit' => 'px'],
                    'typography_text_transform' => 'uppercase',
                    'border_radius' => self::radius(0),
                    'button_padding' => self::pad(16, 48),
                    'button_background_hover_color' => $col['primary'],
                ]),
            ]),
        ], 'cta');

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
            'padding' => self::pad(160, 80, 100, 80),
            'padding_mobile' => self::pad(100, 20, 60, 20),
            'padding_tablet' => self::pad(120, 40, 80, 40),
            'min_height' => ['size' => 50, 'unit' => 'vh', 'sizes' => []],
            'background_background' => 'classic',
            'background_color' => $this->colors()['bg'],
        ], [
            $this->eyebrow('About'),
            $this->headline($c['about_title'] ?? 'Our Story', 'h1', array_merge(
                self::responsiveSize(64, 48, 36),
                ['custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .2s forwards;}']
            )),
            $this->bodyText($c['about_text'] ?? 'A studio built on the belief that less is more.', [
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .4s forwards;max-width:520px;}',
            ]),
        ]);

        // Two-col about
        $sections[] = self::twoCol(
            [self::image($img['about'] ?? '', [
                'custom_css' => 'selector img{width:100%;min-height:400px;object-fit:cover;}',
                'css_classes' => 'sr',
            ])],
            [
                $this->eyebrow('Who We Are'),
                $this->headline($c['about_subtitle'] ?? 'Simplicity is the Ultimate Sophistication', 'h2', [
                    'typography_font_size' => ['size' => 38, 'unit' => 'px'],
                    '_margin' => self::margin(0, 0, 16, 0),
                ]),
                $this->bodyText($c['about_text'] ?? 'We are a creative studio dedicated to purposeful design.'),
                $this->bodyText($c['about_text2'] ?? 'With a focus on clarity and refinement, we create work that lasts.'),
                $this->ctaButton($c['about_cta'] ?? 'Our Services', '/services/', [
                    '_margin' => self::margin(24, 0, 0, 0),
                ]),
            ],
            50,
            ['padding' => self::pad(100, 80), 'background_background' => 'classic', 'background_color' => $this->colors()['surface']],
            [],
            ['css_classes' => 'sr d2', 'flex_justify_content' => 'center']
        );

        // Stats
        $stats = $c['stats'] ?? [
            ['number' => '200', 'suffix' => '+', 'label' => 'Projects'],
            ['number' => '12', 'suffix' => '', 'label' => 'Years'],
            ['number' => '95', 'suffix' => '%', 'label' => 'Retention'],
            ['number' => '40', 'suffix' => '+', 'label' => 'Awards'],
        ];
        $statEls = [];
        foreach ($stats as $i => $s) {
            $statEls[] = self::container([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'width' => ['size' => 23, 'unit' => '%', 'sizes' => []],
                'width_tablet' => ['size' => 48, 'unit' => '%', 'sizes' => []],
                'width_mobile' => ['size' => 100, 'unit' => '%', 'sizes' => []],
                'css_classes' => 'slate-stat sr d' . min($i + 1, 4),
            ], [
                self::html('<div class="slate-stat-n" data-count="' . $s['number'] . '" data-suffix="' . ($s['suffix'] ?? '') . '">' . $s['number'] . ($s['suffix'] ?? '') . '</div>'),
                self::html('<div class="slate-stat-l">' . e($s['label']) . '</div>'),
            ]);
        }
        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface2'],
        ], [
            self::container([
                'flex_direction' => 'row',
                'flex_direction_mobile' => 'column',
                'flex_wrap' => 'wrap',
                'content_width' => 'full',
            ], $statEls),
        ]);

        // Values
        $values = $c['values'] ?? [
            ['icon' => '◻', 'title' => 'Clarity', 'desc' => 'We strip away the unnecessary to reveal what matters most.'],
            ['icon' => '△', 'title' => 'Craft', 'desc' => 'Every detail is considered, every decision intentional.'],
            ['icon' => '○', 'title' => 'Purpose', 'desc' => 'Design should serve a clear, meaningful function.'],
        ];

        $valueCards = [];
        foreach ($values as $i => $v) {
            $valueCards[] = self::container([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'width' => ['size' => 31, 'unit' => '%', 'sizes' => []],
                'width_tablet' => ['size' => 48, 'unit' => '%', 'sizes' => []],
                'width_mobile' => ['size' => 100, 'unit' => '%', 'sizes' => []],
                'padding' => self::pad(48, 36),
                'background_background' => 'classic',
                'background_color' => $this->colors()['surface'],
                'css_classes' => 'slate-card sr d' . ($i + 1),
            ], [
                self::textEditor('<span style="font-size:28px;display:block;margin-bottom:24px;color:' . $this->colors()['accent'] . ';">' . ($v['icon'] ?? '◻') . '</span>'),
                self::heading($v['title'], 'h3', [
                    'title_color' => $this->colors()['text'],
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['heading'],
                    'typography_font_size' => ['size' => 22, 'unit' => 'px'],
                    'typography_font_weight' => '600',
                    '_margin' => self::margin(0, 0, 12, 0),
                ]),
                $this->bodyText($v['desc'], ['typography_font_size' => ['size' => 14, 'unit' => 'px']]),
            ]);
        }

        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['bg'],
        ], [
            $this->eyebrow('Our Values'),
            $this->headline($c['values_title'] ?? 'What Drives Us', 'h2', [
                '_margin' => self::margin(0, 0, 50, 0),
            ]),
            self::container([
                'flex_direction' => 'row',
                'flex_direction_mobile' => 'column',
                'flex_wrap' => 'wrap',
                'content_width' => 'full',
                'flex_gap' => ['size' => 24, 'unit' => 'px', 'column' => '24', 'row' => '24'],
            ], $valueCards),
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
            'padding' => self::pad(160, 80, 100, 80),
            'padding_mobile' => self::pad(100, 20, 60, 20),
            'padding_tablet' => self::pad(120, 40, 80, 40),
            'min_height' => ['size' => 50, 'unit' => 'vh', 'sizes' => []],
            'background_background' => 'classic',
            'background_color' => $this->colors()['bg'],
        ], [
            $this->eyebrow('Services'),
            $this->headline($c['services_title'] ?? 'What We Offer', 'h1', array_merge(
                self::responsiveSize(64, 48, 36),
                ['custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .2s forwards;}']
            )),
            $this->bodyText($c['services_subtitle'] ?? 'Comprehensive creative solutions for forward-thinking brands.', [
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .4s forwards;max-width:520px;}',
            ]),
        ]);

        // Service cards
        $services = $c['services'] ?? [
            ['icon' => '◻', 'title' => 'Brand Identity', 'desc' => 'Complete visual identity systems from logo to brand guidelines.'],
            ['icon' => '△', 'title' => 'Digital Design', 'desc' => 'Web and digital product design with a focus on user experience.'],
            ['icon' => '○', 'title' => 'Art Direction', 'desc' => 'Creative direction for campaigns, editorials, and brand stories.'],
            ['icon' => '◇', 'title' => 'Photography', 'desc' => 'Professional photography that captures the essence of your brand.'],
            ['icon' => '▽', 'title' => 'Print Design', 'desc' => 'Thoughtful print materials from business cards to editorial layouts.'],
            ['icon' => '□', 'title' => 'Strategy', 'desc' => 'Brand strategy and positioning to clarify your market presence.'],
        ];

        $cards = [];
        foreach ($services as $i => $svc) {
            $cards[] = self::container([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'padding' => self::pad(48, 36),
                'background_background' => 'classic',
                'background_color' => $this->colors()['surface'],
                'css_classes' => 'slate-card sr d' . min($i + 1, 4),
            ], [
                self::textEditor('<span style="font-size:28px;display:block;margin-bottom:24px;color:' . $this->colors()['accent'] . ';">' . ($svc['icon'] ?? '◻') . '</span>'),
                self::heading($svc['title'], 'h3', [
                    'title_color' => $this->colors()['text'],
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['heading'],
                    'typography_font_size' => ['size' => 22, 'unit' => 'px'],
                    'typography_font_weight' => '600',
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

        // Process section
        $process = $c['process'] ?? [
            ['step' => '01', 'title' => 'Discovery', 'desc' => 'We listen carefully to understand your goals, audience, and vision.'],
            ['step' => '02', 'title' => 'Concept', 'desc' => 'We explore ideas and develop a clear creative direction.'],
            ['step' => '03', 'title' => 'Refine', 'desc' => 'We iterate and polish until every detail is right.'],
            ['step' => '04', 'title' => 'Deliver', 'desc' => 'We hand over final assets and ensure a smooth launch.'],
        ];

        $processEls = [];
        foreach ($process as $i => $p) {
            $processEls[] = self::container([
                'content_width' => 'full',
                'flex_direction' => 'row',
                'flex_align_items' => 'flex-start',
                'flex_gap' => ['size' => 32, 'unit' => 'px', 'column' => '32', 'row' => '32'],
                'width' => ['size' => 48, 'unit' => '%', 'sizes' => []],
                'width_tablet' => ['size' => 48, 'unit' => '%', 'sizes' => []],
                'width_mobile' => ['size' => 100, 'unit' => '%', 'sizes' => []],
                'padding' => self::pad(32, 24),
                'css_classes' => 'sr d' . min($i + 1, 4),
            ], [
                self::html('<div style="font-family:\'' . $this->fonts()['heading'] . '\',sans-serif;font-size:36px;font-weight:300;color:' . $this->colors()['accent'] . ';line-height:1;min-width:50px;">' . ($p['step'] ?? '0' . ($i + 1)) . '</div>'),
                self::container(['content_width' => 'full', 'flex_direction' => 'column'], [
                    self::heading($p['title'], 'h3', [
                        'title_color' => $this->colors()['text'],
                        'typography_typography' => 'custom',
                        'typography_font_family' => $this->fonts()['heading'],
                        'typography_font_size' => ['size' => 20, 'unit' => 'px'],
                        'typography_font_weight' => '600',
                        '_margin' => self::margin(0, 0, 8, 0),
                    ]),
                    $this->bodyText($p['desc'], ['typography_font_size' => ['size' => 14, 'unit' => 'px']]),
                ]),
            ]);
        }

        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface'],
        ], [
            $this->eyebrow('Process'),
            $this->headline($c['process_title'] ?? 'How We Work', 'h2', [
                '_margin' => self::margin(0, 0, 50, 0),
            ]),
            self::container([
                'flex_direction' => 'row',
                'flex_direction_mobile' => 'column',
                'flex_wrap' => 'wrap',
                'content_width' => 'full',
                'flex_gap' => ['size' => 16, 'unit' => 'px', 'column' => '16', 'row' => '16'],
            ], $processEls),
        ], 'process');

        // CTA
        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'column',
            'flex_align_items' => 'center',
            'padding' => self::pad(100, 80),
            'background_background' => 'classic',
            'background_color' => $this->colors()['primary'],
        ], [
            $this->headline($c['cta_title'] ?? 'Ready to Begin?', 'h2', [
                'title_color' => '#FFFFFF',
                'align' => 'center',
            ]),
            $this->bodyText($c['cta_text'] ?? 'Let us bring your vision to life with clarity and purpose.', [
                'align' => 'center',
                'text_color' => 'rgba(255,255,255,0.55)',
                '_margin' => self::margin(0, 0, 32, 0),
            ]),
            self::button($c['cta_button'] ?? 'Start a Project', '/contact/', [
                'background_color' => '#FFFFFF',
                'button_text_color' => $this->colors()['primary'],
                'typography_typography' => 'custom',
                'typography_font_family' => $this->fonts()['heading'],
                'typography_font_size' => ['size' => 13, 'unit' => 'px'],
                'typography_font_weight' => '600',
                'typography_letter_spacing' => ['size' => 1.5, 'unit' => 'px'],
                'typography_text_transform' => 'uppercase',
                'border_radius' => self::radius(0),
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

        // Hero banner
        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'column',
            'flex_justify_content' => 'center',
            'padding' => self::pad(160, 80, 100, 80),
            'padding_mobile' => self::pad(100, 20, 60, 20),
            'padding_tablet' => self::pad(120, 40, 80, 40),
            'min_height' => ['size' => 50, 'unit' => 'vh', 'sizes' => []],
            'background_background' => 'classic',
            'background_color' => $this->colors()['bg'],
        ], [
            $this->eyebrow('Portfolio'),
            $this->headline($c['portfolio_title'] ?? 'Selected Work', 'h1', array_merge(
                self::responsiveSize(64, 48, 36),
                ['custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .2s forwards;}']
            )),
            $this->bodyText($c['portfolio_subtitle'] ?? 'A curated selection of projects we are proud of.', [
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .4s forwards;max-width:480px;}',
            ]),
        ]);

        // Gallery grid
        $galleryImgs = array_filter([
            $img['hero'] ?? '', $img['about'] ?? '',
            $img['gallery1'] ?? '', $img['gallery2'] ?? '',
            $img['services'] ?? '', $img['team'] ?? '',
        ]);

        $projects = $c['projects'] ?? [
            ['title' => 'Brand Redesign'],
            ['title' => 'Editorial Layout'],
            ['title' => 'Digital Campaign'],
            ['title' => 'Identity System'],
            ['title' => 'Photography Series'],
            ['title' => 'Web Platform'],
        ];

        $photoEls = [];
        foreach (array_slice($galleryImgs, 0, 6) as $i => $url) {
            if (!$url) continue;
            $projectTitle = $projects[$i]['title'] ?? 'Project ' . ($i + 1);
            $photoEls[] = self::container([
                'content_width' => 'full',
                'width' => ['size' => 31, 'unit' => '%', 'sizes' => []],
                'width_tablet' => ['size' => 48, 'unit' => '%', 'sizes' => []],
                'width_mobile' => ['size' => 100, 'unit' => '%', 'sizes' => []],
                'css_classes' => 'slate-photo sr d' . min($i + 1, 4),
                'custom_css' => 'selector{min-height:340px;}',
            ], [
                self::image($url, [
                    'custom_css' => 'selector img{width:100%;height:340px;object-fit:cover;}',
                ]),
                self::html('<div class="slate-overlay"><span>' . e($projectTitle) . '</span></div>'),
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

        // CTA
        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'column',
            'flex_align_items' => 'center',
            'padding' => self::pad(100, 80),
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface'],
        ], [
            $this->eyebrow('Interested?'),
            $this->headline($c['portfolio_cta_title'] ?? 'Let\'s Work Together', 'h2', [
                'align' => 'center',
                '_margin' => self::margin(0, 0, 16, 0),
            ]),
            $this->bodyText($c['portfolio_cta_text'] ?? 'We are always open to new projects and creative collaborations.', [
                'align' => 'center',
                'custom_css' => 'selector{max-width:460px;margin-left:auto;margin-right:auto;}',
                '_margin' => self::margin(0, 0, 32, 0),
            ]),
            $this->ctaButton($c['portfolio_cta_button'] ?? 'Get in Touch', '/contact/'),
        ]);

        return $sections;
    }

    // ═══════════════════════════════════════════════════════════
    // CONTACT PAGE
    // ═══════════════════════════════════════════════════════════

    public function buildContactPage(array $c, array $img): array
    {
        $sections = [];

        // Hero banner
        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'column',
            'flex_justify_content' => 'center',
            'padding' => self::pad(160, 80, 100, 80),
            'padding_mobile' => self::pad(100, 20, 60, 20),
            'padding_tablet' => self::pad(120, 40, 80, 40),
            'min_height' => ['size' => 50, 'unit' => 'vh', 'sizes' => []],
            'background_background' => 'classic',
            'background_color' => $this->colors()['bg'],
        ], [
            $this->eyebrow('Contact'),
            $this->headline($c['contact_title'] ?? 'Get in Touch', 'h1', array_merge(
                self::responsiveSize(64, 48, 36),
                ['custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .2s forwards;}']
            )),
            $this->bodyText($c['contact_subtitle'] ?? 'We would love to hear about your project. Drop us a line.', [
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .4s forwards;max-width:480px;}',
            ]),
        ]);

        // Contact info cards
        $contactInfo = $c['contact_info'] ?? [
            ['icon' => '✉', 'label' => 'Email', 'value' => $c['email'] ?? 'hello@example.com'],
            ['icon' => '☎', 'label' => 'Phone', 'value' => $c['phone'] ?? '(555) 123-4567'],
            ['icon' => '◎', 'label' => 'Location', 'value' => $c['address'] ?? '123 Creative St, City, State'],
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
                'padding' => self::pad(48, 28),
                'background_background' => 'classic',
                'background_color' => $this->colors()['surface'],
                'css_classes' => 'slate-card sr d' . ($i + 1),
            ], [
                self::textEditor('<span style="font-size:24px;display:block;margin-bottom:16px;color:' . $this->colors()['accent'] . ';">' . $info['icon'] . '</span>'),
                self::heading($info['label'], 'h4', [
                    'title_color' => $this->colors()['text'],
                    'align' => 'center',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['heading'],
                    'typography_font_size' => ['size' => 16, 'unit' => 'px'],
                    'typography_font_weight' => '600',
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
            $this->headline('We\'re Listening', 'h2', [
                'align' => 'center',
                'typography_font_size' => ['size' => 42, 'unit' => 'px'],
                '_margin' => self::margin(0, 0, 40, 0),
            ]),
            self::html('<form style="max-width:560px;width:100%;margin:0 auto;display:flex;flex-direction:column;gap:14px;">
<input type="text" placeholder="Your Name" style="padding:14px 20px;background:' . $this->colors()['bg'] . ';border:none;color:' . $this->colors()['text'] . ';font-family:\'' . $this->fonts()['body'] . '\',sans-serif;font-size:14px;outline:none;transition:box-shadow .3s;" onfocus="this.style.boxShadow=\'0 0 0 1px ' . $this->colors()['accent'] . '\'" onblur="this.style.boxShadow=\'none\'">
<input type="email" placeholder="Your Email" style="padding:14px 20px;background:' . $this->colors()['bg'] . ';border:none;color:' . $this->colors()['text'] . ';font-family:\'' . $this->fonts()['body'] . '\',sans-serif;font-size:14px;outline:none;transition:box-shadow .3s;" onfocus="this.style.boxShadow=\'0 0 0 1px ' . $this->colors()['accent'] . '\'" onblur="this.style.boxShadow=\'none\'">
<textarea rows="6" placeholder="Tell us about your project" style="padding:14px 20px;background:' . $this->colors()['bg'] . ';border:none;color:' . $this->colors()['text'] . ';font-family:\'' . $this->fonts()['body'] . '\',sans-serif;font-size:14px;outline:none;resize:vertical;transition:box-shadow .3s;" onfocus="this.style.boxShadow=\'0 0 0 1px ' . $this->colors()['accent'] . '\'" onblur="this.style.boxShadow=\'none\'"></textarea>
<button type="submit" style="padding:16px 40px;background:' . $this->colors()['primary'] . ';color:#FFFFFF;font-family:\'' . $this->fonts()['heading'] . '\',sans-serif;font-size:13px;font-weight:600;letter-spacing:1.5px;text-transform:uppercase;border:none;cursor:pointer;transition:background .3s;">Send Message</button>
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

        // Build overlay nav links with numbered prefixes (01, 02, 03...)
        $overlayLinks = '';
        $i = 1;
        foreach ($pages as $slug => $label) {
            $url = ($slug === 'home') ? '/' : '/' . $slug . '/';
            $num = str_pad($i, 2, '0', STR_PAD_LEFT);
            $overlayLinks .= '<a href="' . $url . '" class="slate-overlay-link"><span class="slate-overlay-num">' . $num . '</span>' . e($label) . '</a>';
            $i++;
        }

        return [self::html('
<nav class="slate-hdr" id="slateNav">
  <a href="/" class="slate-hdr-logo">' . e($siteName) . '</a>
  <button class="slate-hamburger" id="slateHamburger" aria-label="Menu">
    <span class="slate-hamburger-line"></span>
    <span class="slate-hamburger-line"></span>
    <span class="slate-hamburger-line"></span>
  </button>
</nav>

<div class="slate-overlay" id="slateOverlay">
  <button class="slate-overlay-close" id="slateOverlayClose" aria-label="Close">&times;</button>
  <div class="slate-overlay-nav">' . $overlayLinks . '</div>
</div>

<style>
.slate-hdr{position:fixed;top:0;left:0;right:0;z-index:1000;padding:0 80px;height:80px;display:flex;align-items:center;justify-content:space-between;background:transparent;transition:background .4s,border-color .4s;border-bottom:1px solid transparent;}
.slate-hdr.scrolled{background:rgba(255,255,255,0.98);border-bottom-color:' . $c['border'] . ';}
.slate-hdr-logo{font-family:\'' . $f['heading'] . '\',sans-serif;font-size:18px;font-weight:700;letter-spacing:0.5px;color:' . $c['text'] . ';text-decoration:none;}

.slate-hamburger{background:none;border:none;cursor:pointer;padding:8px;display:flex;flex-direction:column;gap:6px;align-items:center;justify-content:center;width:40px;height:40px;}
.slate-hamburger-line{display:block;width:24px;height:2px;background:' . $c['primary'] . ';transition:transform .35s cubic-bezier(.77,0,.18,1),opacity .25s;}
.slate-hamburger.active .slate-hamburger-line:nth-child(1){transform:translateY(8px) rotate(45deg);}
.slate-hamburger.active .slate-hamburger-line:nth-child(2){opacity:0;}
.slate-hamburger.active .slate-hamburger-line:nth-child(3){transform:translateY(-8px) rotate(-45deg);}

.slate-overlay{position:fixed;top:0;right:0;bottom:0;left:0;z-index:999;background:#FFFFFF;display:flex;align-items:center;justify-content:center;transform:translateX(100%);transition:transform .5s cubic-bezier(.77,0,.18,1);pointer-events:none;}
.slate-overlay.open{transform:translateX(0);pointer-events:auto;}
.slate-overlay-close{position:absolute;top:24px;right:80px;background:none;border:none;font-size:36px;color:' . $c['primary'] . ';cursor:pointer;font-weight:300;line-height:1;}
.slate-overlay-nav{display:flex;flex-direction:column;gap:12px;text-align:left;}
.slate-overlay-link{display:flex;align-items:baseline;gap:16px;font-family:\'' . $f['heading'] . '\',sans-serif;font-size:48px;font-weight:300;color:' . $c['primary'] . ';text-decoration:none;transition:color .3s;line-height:1.3;}
.slate-overlay-link:hover{color:' . $c['accent'] . ';}
.slate-overlay-num{font-size:14px;font-weight:400;color:' . $c['accent'] . ';letter-spacing:0.5px;font-family:\'' . $f['body'] . '\',sans-serif;}

@media(max-width:767px){
  .slate-hdr{padding:0 24px;height:64px;}
  .slate-overlay-close{right:24px;top:16px;}
  .slate-overlay-link{font-size:32px;}
  .slate-overlay-num{font-size:12px;}
}
</style>

<script>
(function(){
  var nav=document.getElementById("slateNav"),btn=document.getElementById("slateHamburger"),overlay=document.getElementById("slateOverlay"),close=document.getElementById("slateOverlayClose");
  btn.addEventListener("click",function(){btn.classList.add("active");overlay.classList.add("open");});
  close.addEventListener("click",function(){btn.classList.remove("active");overlay.classList.remove("open");});
  overlay.querySelectorAll(".slate-overlay-link").forEach(function(l){l.addEventListener("click",function(){btn.classList.remove("active");overlay.classList.remove("open");});});
  window.addEventListener("scroll",function(){nav.classList.toggle("scrolled",window.scrollY>50);});
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

        // Build slash-separated nav links
        $linkParts = [];
        foreach ($pages as $slug => $label) {
            $url = ($slug === 'home') ? '/' : '/' . $slug . '/';
            $linkParts[] = '<a href="' . $url . '" class="slate-ftr-link">' . e($label) . '</a>';
        }
        $navHtml = implode(' <span class="slate-ftr-sep">/</span> ', $linkParts);

        $sections = [];

        $sections[] = self::container([
            'content_width' => 'full',
            'padding' => self::pad(0),
            'background_background' => 'classic',
            'background_color' => $c['primary'],
        ], [
            self::html('
<div class="slate-ftr">
  <div class="slate-ftr-left">' . e($siteName) . '</div>
  <div class="slate-ftr-center">' . $navHtml . '</div>
  <div class="slate-ftr-right">&copy; ' . date('Y') . '</div>
</div>
<style>
.slate-ftr{display:flex;align-items:center;justify-content:space-between;padding:32px 80px;}
.slate-ftr-left{font-family:\'' . $f['heading'] . '\',sans-serif;font-size:14px;font-weight:700;color:#FFFFFF;letter-spacing:0.5px;}
.slate-ftr-center{display:flex;align-items:center;gap:8px;}
.slate-ftr-link{font-family:\'' . $f['body'] . '\',sans-serif;font-size:13px;color:rgba(255,255,255,0.5);text-decoration:none;transition:color .3s;}
.slate-ftr-link:hover{color:#FFFFFF;}
.slate-ftr-sep{font-size:13px;color:rgba(255,255,255,0.2);}
.slate-ftr-right{font-family:\'' . $f['body'] . '\',sans-serif;font-size:13px;color:rgba(255,255,255,0.35);}
@media(max-width:767px){
  .slate-ftr{flex-direction:column;gap:16px;padding:32px 24px;text-align:center;}
}
</style>'),
        ]);

        return $sections;
    }
}
