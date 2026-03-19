<?php

namespace App\Services\Layouts;

/**
 * Blush — Elegant pink/gold luxury layout.
 * Warm cream backgrounds, gold accents, Cormorant Garamond italic headlines,
 * luxury spacing, soft animations, scroll-reveal, no custom cursor.
 */
class LayoutBlush extends AbstractLayout
{
    public function slug(): string { return 'blush'; }
    public function name(): string { return 'Blush'; }
    public function description(): string { return 'Elegant and luxurious design with soft pink tones and gold accents'; }
    public function bestFor(): array { return ['Beauty', 'Wedding', 'Spa', 'Fashion']; }
    public function isDark(): bool { return false; }

    public function colors(): array
    {
        return [
            'primary'   => '#C9A87C',
            'secondary' => '#B8956A',
            'accent'    => '#D4A574',
            'bg'        => '#FBF9F7',
            'surface'   => '#FFFFFF',
            'surface2'  => '#F7F3EF',
            'text'      => '#2D2424',
            'muted'     => 'rgba(45,36,36,0.55)',
            'border'    => 'rgba(45,36,36,0.08)',
        ];
    }

    public function fonts(): array
    {
        return ['heading' => 'Cormorant Garamond', 'body' => 'Jost'];
    }

    // ═════════════════════��═════════════════════════════════════
    // GLOBAL CSS
    // ═══════════════════════════════════════════════════════════

    public function buildGlobalCss(): string
    {
        $c = $this->colors();
        $f = $this->fonts();
        $hf = str_replace(' ', '+', $f['heading']);
        $bf = str_replace(' ', '+', $f['body']);

        return <<<CSS
<link href="https://fonts.googleapis.com/css2?family={$hf}:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&family={$bf}:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{--blush-bg:{$c['bg']};--blush-surface:{$c['surface']};--blush-surface2:{$c['surface2']};--blush-text:{$c['text']};--blush-muted:{$c['muted']};--blush-border:{$c['border']};--blush-gold:{$c['primary']};--blush-gold2:{$c['secondary']};--blush-accent:{$c['accent']};}
body,body.elementor-template-canvas{background:var(--blush-bg);color:var(--blush-text);font-family:'{$f['body']}',sans-serif;overflow-x:hidden;margin:0;padding:0;}
.elementor-element,.elementor.elementor-2{font-family:'{$f['body']}',sans-serif;}
.elementor-widget{margin-bottom:0 !important;}
.e-con{--gap:0px;}
.e-con:not(.e-con--row)>.elementor-widget{width:100%;}
.e-con--row>.elementor-widget{width:auto;}

/* Animations */
@keyframes fadeUp{from{opacity:0;transform:translateY(28px)}to{opacity:1;transform:translateY(0)}}
@keyframes fadeIn{from{opacity:0}to{opacity:1}}
@keyframes shimmer{0%{background-position:-200% center}100%{background-position:200% center}}
.sr{opacity:0;transform:translateY(28px);transition:opacity .9s cubic-bezier(.23,1,.32,1),transform .9s cubic-bezier(.23,1,.32,1);}
.sr.d1{transition-delay:.1s}.sr.d2{transition-delay:.2s}.sr.d3{transition-delay:.3s}.sr.d4{transition-delay:.45s}
.sr.in{opacity:1;transform:none;}

/* Eyebrow */
.eyebrow{display:inline-flex;align-items:center;gap:14px;font-size:11px;font-weight:400;letter-spacing:4px;text-transform:uppercase;color:var(--blush-gold);margin-bottom:20px;font-family:'{$f['body']}',sans-serif;}
.eyebrow::before{content:'';width:32px;height:1px;background:var(--blush-gold);}

/* Cards */
.blush-card{position:relative;overflow:hidden;background:var(--blush-surface);border-radius:12px;box-shadow:0 4px 24px rgba(45,36,36,0.06);transition:all .45s cubic-bezier(.23,1,.32,1);}
.blush-card:hover{transform:translateY(-6px);box-shadow:0 16px 48px rgba(45,36,36,0.1);}

/* Service Card */
.blush-scard{text-align:center;border-radius:12px;background:var(--blush-surface);box-shadow:0 4px 20px rgba(45,36,36,0.05);transition:all .45s cubic-bezier(.23,1,.32,1);}
.blush-scard:hover{transform:translateY(-6px);box-shadow:0 16px 48px rgba(201,168,124,0.15);}
.blush-scard-icon{width:64px;height:64px;margin:0 auto 20px;border-radius:50%;background:linear-gradient(135deg,rgba(201,168,124,0.1),rgba(212,165,116,0.15));display:flex;align-items:center;justify-content:center;font-size:28px;transition:transform .3s;}
.blush-scard:hover .blush-scard-icon{transform:scale(1.08);}

/* Benefit card */
.blush-bcard{text-align:center;border-radius:12px;background:var(--blush-surface);box-shadow:0 2px 16px rgba(45,36,36,0.04);transition:all .4s cubic-bezier(.23,1,.32,1);position:relative;overflow:hidden;}
.blush-bcard::after{content:'';position:absolute;bottom:0;left:50%;transform:translateX(-50%);width:40px;height:2px;background:var(--blush-gold);opacity:0;transition:all .4s;}
.blush-bcard:hover{transform:translateY(-5px);box-shadow:0 12px 40px rgba(201,168,124,0.12);}
.blush-bcard:hover::after{opacity:1;width:60px;}

/* Testimonial Card */
.blush-tcard{border-radius:12px;background:var(--blush-surface);box-shadow:0 4px 24px rgba(45,36,36,0.06);transition:all .4s cubic-bezier(.23,1,.32,1);}
.blush-tcard:hover{transform:translateY(-4px);box-shadow:0 12px 40px rgba(201,168,124,0.12);}
.blush-tcard-feat{background:linear-gradient(135deg,{$c['primary']},{$c['accent']}) !important;color:#FFF;}
.blush-tcard-feat:hover{transform:translateY(-8px)!important;box-shadow:0 24px 60px rgba(201,168,124,0.35);}

/* Stats */
.blush-stat{text-align:center;}
.blush-stat-n{font-family:'{$f['heading']}',serif;font-size:56px;font-weight:300;font-style:italic;color:var(--blush-gold);line-height:1;}
.blush-stat-l{font-size:11px;letter-spacing:3px;text-transform:uppercase;color:var(--blush-muted);margin-top:8px;font-family:'{$f['body']}',sans-serif;}

/* Stars */
.blush-stars{color:var(--blush-gold);letter-spacing:2px;font-size:14px;margin-bottom:16px;}

/* Quote mark */
.blush-quote{font-family:'{$f['heading']}',serif;font-size:72px;font-style:italic;color:var(--blush-gold);line-height:1;margin-bottom:-10px;opacity:.4;}

/* Photo hover */
.blush-photo{overflow:hidden;position:relative;border-radius:12px;}
.blush-photo img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .8s cubic-bezier(.23,1,.32,1);}
.blush-photo:hover img{transform:scale(1.05);}
.blush-pho{position:absolute;inset:0;background:linear-gradient(to top,rgba(45,36,36,.3) 0%,transparent 50%);opacity:0;transition:opacity .5s;}
.blush-photo:hover .blush-pho{opacity:1;}

/* Gold divider line */
.blush-divider{width:60px;height:1px;background:var(--blush-gold);margin:24px 0;}
.blush-divider-center{width:60px;height:1px;background:var(--blush-gold);margin:24px auto;}

/* Footer */
.blush-fc h4{font-size:11px;font-weight:400;letter-spacing:4px;text-transform:uppercase;color:var(--blush-gold);margin-bottom:22px;padding-bottom:14px;border-bottom:1px solid var(--blush-border);font-family:'{$f['body']}',sans-serif;}
.blush-fc ul{list-style:none;display:flex;flex-direction:column;gap:11px;padding:0;margin:0;}
.blush-fc a{font-size:14px;color:var(--blush-muted);text-decoration:none;display:flex;align-items:center;gap:8px;transition:color .3s;}
.blush-fc a:hover{color:var(--blush-text);}
.blush-social{display:flex;gap:10px;margin-top:24px;}
.blush-social a{width:38px;height:38px;border:1px solid var(--blush-border);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:16px;text-decoration:none;color:var(--blush-muted);transition:all .3s;}
.blush-social a:hover{border-color:var(--blush-gold);color:var(--blush-gold);background:rgba(201,168,124,.06);transform:translateY(-3px);}

/* Contact info */
.blush-ci{display:flex;align-items:flex-start;gap:12px;margin-bottom:16px;}
.blush-ci-i{width:40px;height:40px;min-width:40px;background:rgba(201,168,124,.08);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:16px;}
.blush-ci small{display:block;font-size:10px;color:var(--blush-muted);letter-spacing:2px;text-transform:uppercase;margin-bottom:2px;font-family:'{$f['body']}',sans-serif;}
.blush-ci span{font-size:14px;color:var(--blush-text);}

/* Feature list */
.blush-features{list-style:none;display:flex;flex-direction:column;gap:12px;padding:0;margin:0;}
.blush-features li{display:flex;align-items:center;gap:10px;font-size:15px;color:var(--blush-muted);}
.blush-features .chk{color:var(--blush-gold);font-size:14px;}

/* Back to top */
#blush-btt{position:fixed;bottom:28px;right:28px;width:46px;height:46px;background:var(--blush-gold);color:#FFF;border-radius:50%;font-size:20px;display:flex;align-items:center;justify-content:center;text-decoration:none;z-index:500;opacity:0;transform:translateY(12px);pointer-events:none;transition:all .4s;box-shadow:0 8px 24px rgba(201,168,124,.3);}
#blush-btt.show{opacity:1;transform:translateY(0);pointer-events:all;}
#blush-btt:hover{background:var(--blush-gold2);transform:translateY(-4px)!important;}

/* Responsive */
@media(max-width:1100px){
  .blush-photo{min-height:200px;}
  .blush-scard.e-con,.blush-bcard.e-con,.blush-photo.e-con{--width:48% !important;width:48% !important;}
}
@media(max-width:767px){
  .blush-scard.e-con,.blush-bcard.e-con,.blush-stat.e-con,.blush-photo.e-con{--width:100% !important;width:100% !important;}
  .blush-tcard.e-con{--width:100% !important;width:100% !important;}
  .blush-nav ul{display:none !important;}
  .blush-features{grid-template-columns:1fr;}
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
const btt=document.getElementById('blush-btt');
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
    // TYPOGRAPHY OVERRIDES — Elegant italic, not uppercase
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
            'typography_font_weight' => '400',
            'typography_font_style' => 'italic',
            'typography_line_height' => ['size' => 1.1, 'unit' => 'em'],
            'typography_text_transform' => 'none',
        ], self::responsiveSize(64, 48, 36), $extra));
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
            'typography_font_weight' => '300',
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
            'typography_font_size' => ['size' => 12, 'unit' => 'px'],
            'typography_font_weight' => '500',
            'typography_letter_spacing' => ['size' => 3, 'unit' => 'px'],
            'typography_text_transform' => 'uppercase',
            'border_radius' => self::radius(50),
            'button_padding' => self::pad(16, 44),
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
            'button_text_color' => $c['text'],
            'typography_typography' => 'custom',
            'typography_font_family' => $f['body'],
            'typography_font_size' => ['size' => 12, 'unit' => 'px'],
            'typography_font_weight' => '500',
            'typography_letter_spacing' => ['size' => 3, 'unit' => 'px'],
            'typography_text_transform' => 'uppercase',
            'border_border' => 'solid',
            'border_width' => self::pad(1),
            'border_color' => 'rgba(45,36,36,0.15)',
            'border_radius' => self::radius(50),
            'button_padding' => self::pad(15, 36),
        ], $extra));
    }

    /** Section with luxury 120px padding */
    public function section(array $settings = [], array $elements = [], string $id = ''): array
    {
        $defaults = [
            'content_width' => 'full',
            'flex_direction' => 'column',
            'padding' => self::pad(120, 64),
        ];
        if ($id) {
            $defaults['_element_id'] = $id;
        }
        return self::container(array_merge($defaults, $settings), $elements);
    }

    // ═══════════════════════════════════════════════════════════
    // HOME PAGE — Elegant minimal: Hero w/ frame, Intro, Magazine Services, Single Quote, CTA
    // ═══════════════════════════════════════════════════════════

    public function buildHomePage(array $c, array $img): array
    {
        $col = $this->colors();
        $fnt = $this->fonts();

        $siteName = $c['site_name'] ?? 'Business Name';
        $heroTitle = $c['hero_title'] ?? 'Timeless Elegance, Refined For You';
        $heroSub = $c['hero_subtitle'] ?? 'We craft exceptional experiences with refined taste and meticulous attention to every detail.';
        $heroCta = $c['hero_cta'] ?? 'Discover More';
        $heroCtaUrl = $c['hero_cta_url'] ?? '#services';

        $introText = $c['about_text'] ?? 'We believe in the transformative power of beauty — in moments crafted with care, spaces designed with intention, and experiences that linger long after they end.';

        $services = $c['services'] ?? [
            ['icon' => '✿', 'title' => 'Bespoke Design', 'desc' => 'Thoughtfully crafted designs tailored to your unique vision.'],
            ['icon' => '◇', 'title' => 'Premium Experience', 'desc' => 'Every interaction designed to delight and inspire.'],
            ['icon' => '❋', 'title' => 'Refined Details', 'desc' => 'Meticulous attention ensures a flawless result.'],
            ['icon' => '♛', 'title' => 'Luxury Quality', 'desc' => 'Only the finest materials and techniques.'],
        ];

        $testimonials = $c['testimonials'] ?? [
            ['quote' => 'An absolutely exquisite experience from start to finish. Every detail was perfection — they transformed my vision into something even more beautiful than I ever imagined.', 'name' => 'Sophia Laurent', 'role' => 'Bride', 'initials' => 'SL'],
        ];

        $heroImg = $img['hero'] ?? '';
        // Gather service card images from gallery + services pool
        $svcImgs = array_values(array_filter([
            $img['gallery1'] ?? '',
            $img['gallery2'] ?? '',
            $img['services'] ?? '',
            $img['gallery3'] ?? '',
            $img['gallery4'] ?? '',
            $img['gallery5'] ?? '',
        ]));

        $sections = [];

        // ─── 1. HERO — Elegant split-screen (warm cream left, image right) ───
        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'row',
            'flex_direction_mobile' => 'column',
            'flex_direction_tablet' => 'column',
            'padding' => self::pad(0),
            'min_height' => ['size' => 100, 'unit' => 'vh', 'sizes' => []],
            'min_height_mobile' => ['size' => 'auto', 'unit' => '', 'sizes' => []],
            '_element_id' => 'hero',
        ], [
            // LEFT — warm cream background with text
            self::container([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'flex_justify_content' => 'center',
                'flex_align_items' => 'flex-start',
                'width' => ['size' => 50, 'unit' => '%', 'sizes' => []],
                'width_tablet' => ['size' => 100, 'unit' => '%', 'sizes' => []],
                'width_mobile' => ['size' => 100, 'unit' => '%', 'sizes' => []],
                'padding' => self::pad(100, 80, 100, 100),
                'padding_mobile' => self::pad(80, 24, 60, 24),
                'padding_tablet' => self::pad(100, 48, 80, 48),
                'background_background' => 'classic',
                'background_color' => $col['bg'],
            ], [
                // Decorative gold line
                self::html('<div style="width:60px;height:1px;background:' . $col['primary'] . ';margin-bottom:28px;opacity:0;animation:fadeUp .6s ease .2s forwards;"></div>'),

                // Eyebrow
                self::html('<div style="opacity:0;animation:fadeUp .7s ease .3s forwards;"><span style="font-family:\'' . $fnt['body'] . '\',sans-serif;font-size:11px;font-weight:400;letter-spacing:5px;text-transform:uppercase;color:' . $col['primary'] . ';">' . e($c['hero_eyebrow'] ?? $siteName) . '</span></div>'),

                // Main title — serif italic
                $this->headline($heroTitle, 'h1', array_merge(
                    self::responsiveSize(60, 48, 36),
                    [
                        'align' => 'left',
                        'title_color' => $col['text'],
                        'typography_letter_spacing' => ['size' => 0.5, 'unit' => 'px'],
                        'typography_font_weight' => '400',
                        'typography_font_style' => 'italic',
                        'typography_text_transform' => 'none',
                        '_margin' => self::margin(16, 0, 20, 0),
                        'custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .45s forwards;}',
                    ]
                )),

                // Subtitle
                self::textEditor('<p>' . e($heroSub) . '</p>', [
                    'text_color' => $col['muted'],
                    'typography_typography' => 'custom',
                    'typography_font_family' => $fnt['body'],
                    'typography_font_size' => ['size' => 15, 'unit' => 'px'],
                    'typography_line_height' => ['size' => 1.8, 'unit' => 'em'],
                    'typography_font_weight' => '300',
                    '_margin' => self::margin(0, 0, 32, 0),
                    'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .6s forwards;max-width:420px;}',
                ]),

                // Elegant CTA
                self::container([
                    'flex_direction' => 'row',
                    'content_width' => 'full',
                    'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .75s forwards;}',
                ], [
                    self::button($heroCta, $heroCtaUrl, [
                        'background_color' => $col['primary'],
                        'button_text_color' => '#FFFFFF',
                        'typography_typography' => 'custom',
                        'typography_font_family' => $fnt['body'],
                        'typography_font_size' => ['size' => 11, 'unit' => 'px'],
                        'typography_font_weight' => '500',
                        'typography_letter_spacing' => ['size' => 4, 'unit' => 'px'],
                        'typography_text_transform' => 'uppercase',
                        'border_radius' => self::radius(0),
                        'button_padding' => self::pad(18, 44),
                        'button_background_hover_color' => $col['secondary'],
                    ]),
                ]),
            ]),

            // RIGHT — full-height image
            self::container([
                'content_width' => 'full',
                'width' => ['size' => 50, 'unit' => '%', 'sizes' => []],
                'width_tablet' => ['size' => 100, 'unit' => '%', 'sizes' => []],
                'width_mobile' => ['size' => 100, 'unit' => '%', 'sizes' => []],
                'min_height' => ['size' => 600, 'unit' => 'px', 'sizes' => []],
                'min_height_mobile' => ['size' => 400, 'unit' => 'px', 'sizes' => []],
                'padding' => self::pad(0),
                'background_background' => 'classic',
                'background_image' => ['url' => $heroImg, 'id' => ''],
                'background_position' => 'center center',
                'background_size' => 'cover',
            ], []),
        ]);

        // ─── 2. INTRO — Centered text with ornamental divider ───
        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $col['bg'],
            'flex_align_items' => 'center',
            'padding' => self::pad(120, 64),
            'padding_mobile' => self::pad(80, 20),
            'padding_tablet' => self::pad(100, 40),
        ], [
            // Ornamental top symbol
            self::html('<div class="sr" style="text-align:center;margin-bottom:32px;"><span style="font-family:\'' . $fnt['heading'] . '\',serif;font-size:28px;color:' . $col['primary'] . ';letter-spacing:12px;opacity:0.6;">&#10022;&nbsp;&nbsp;&nbsp;&#10022;&nbsp;&nbsp;&nbsp;&#10022;</span></div>'),

            // Intro text — elegant centered paragraph
            self::textEditor('<p style="text-align:center;">' . e($introText) . '</p>', [
                'text_color' => $col['text'],
                'typography_typography' => 'custom',
                'typography_font_family' => $fnt['heading'],
                'typography_font_size' => ['size' => 26, 'unit' => 'px'],
                'typography_font_size_tablet' => ['size' => 22, 'unit' => 'px'],
                'typography_font_size_mobile' => ['size' => 19, 'unit' => 'px'],
                'typography_font_weight' => '300',
                'typography_font_style' => 'italic',
                'typography_line_height' => ['size' => 1.9, 'unit' => 'em'],
                'css_classes' => 'sr',
                'custom_css' => 'selector{max-width:680px;margin-left:auto;margin-right:auto;}',
            ]),

            // Bottom ornamental line
            self::html('<div class="sr d2" style="text-align:center;margin-top:32px;"><span style="display:inline-block;width:60px;height:1px;background:' . $col['primary'] . ';opacity:0.5;"></span></div>'),
        ]);

        // ─── 3. SERVICES — Magazine-style image cards with text overlay ───
        // Take first 4 services max, pair with images
        $svcSlice = array_slice($services, 0, 4);
        $serviceCards = [];
        foreach ($svcSlice as $i => $svc) {
            $svcImg = $svcImgs[$i] ?? ($svcImgs[0] ?? $heroImg);
            $delay = ($i % 2 === 0) ? 'd1' : 'd2';

            $serviceCards[] = self::container([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'flex_justify_content' => 'flex-end',
                'width' => ['size' => 48, 'unit' => '%', 'sizes' => []],
                'width_tablet' => ['size' => 48, 'unit' => '%', 'sizes' => []],
                'width_mobile' => ['size' => 100, 'unit' => '%', 'sizes' => []],
                'min_height' => ['size' => 480, 'unit' => 'px', 'sizes' => []],
                'min_height_mobile' => ['size' => 360, 'unit' => 'px', 'sizes' => []],
                'padding' => self::pad(0),
                'background_background' => 'classic',
                'background_image' => ['url' => $svcImg, 'id' => ''],
                'background_position' => 'center center',
                'background_size' => 'cover',
                'border_radius' => self::radius(4),
                'css_classes' => 'sr ' . $delay,
                'custom_css' => "selector{position:relative;overflow:hidden;cursor:pointer;}
selector::before{content:'';position:absolute;inset:0;background:linear-gradient(to top,rgba(45,36,32,0.85) 0%,rgba(45,36,32,0.15) 50%,transparent 100%);z-index:1;transition:opacity .5s;}
selector:hover::before{opacity:.9;}
selector>.e-con-inner,selector>.elementor-widget{position:relative;z-index:2;}
selector img{transition:transform .8s cubic-bezier(.23,1,.32,1);}
selector:hover img{transform:scale(1.04);}",
            ], [
                // Text overlay at bottom
                self::container([
                    'content_width' => 'full',
                    'flex_direction' => 'column',
                    'padding' => self::pad(32, 36),
                    'padding_mobile' => self::pad(24, 20),
                ], [
                    self::heading($svc['title'], 'h3', [
                        'title_color' => '#FFFFFF',
                        'typography_typography' => 'custom',
                        'typography_font_family' => $fnt['heading'],
                        'typography_font_size' => ['size' => 28, 'unit' => 'px'],
                        'typography_font_size_mobile' => ['size' => 22, 'unit' => 'px'],
                        'typography_font_weight' => '400',
                        'typography_font_style' => 'italic',
                        '_margin' => self::margin(0, 0, 8, 0),
                    ]),
                    self::textEditor('<p>' . e($svc['desc']) . '</p>', [
                        'text_color' => 'rgba(255,255,255,0.7)',
                        'typography_typography' => 'custom',
                        'typography_font_family' => $fnt['body'],
                        'typography_font_size' => ['size' => 14, 'unit' => 'px'],
                        'typography_line_height' => ['size' => 1.7, 'unit' => 'em'],
                        'typography_font_weight' => '300',
                    ]),
                ]),
            ]);
        }

        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $col['surface2'],
            'flex_align_items' => 'center',
            'padding' => self::pad(100, 64),
            'padding_mobile' => self::pad(60, 15),
        ], [
            $this->eyebrow($c['services_eyebrow'] ?? 'Our Services'),
            $this->headline($c['services_title'] ?? 'What We Offer', 'h2', [
                'align' => 'center',
                '_margin' => self::margin(0, 0, 56, 0),
            ]),
            self::container([
                'flex_direction' => 'row',
                'flex_direction_mobile' => 'column',
                'flex_wrap' => 'wrap',
                'content_width' => 'full',
                'flex_gap' => ['size' => 24, 'unit' => 'px', 'column' => '24', 'row' => '24'],
                'flex_justify_content' => 'center',
            ], $serviceCards),
        ], 'services');

        // ─── 4. TESTIMONIAL — Single centered elegant quote ───
        $test = $testimonials[0] ?? $testimonials[array_key_first($testimonials)];

        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $col['bg'],
            'flex_align_items' => 'center',
            'padding' => self::pad(120, 64),
            'padding_mobile' => self::pad(80, 20),
        ], [
            // Large decorative opening quote mark
            self::html('<div class="sr" style="text-align:center;"><span style="font-family:\'' . $fnt['heading'] . '\',serif;font-size:96px;line-height:0.6;color:' . $col['primary'] . ';opacity:0.35;display:block;">&ldquo;</span></div>'),

            // The quote itself — large italic serif
            self::textEditor('<p style="text-align:center;">' . e($test['quote']) . '</p>', [
                'text_color' => $col['text'],
                'typography_typography' => 'custom',
                'typography_font_family' => $fnt['heading'],
                'typography_font_size' => ['size' => 28, 'unit' => 'px'],
                'typography_font_size_tablet' => ['size' => 24, 'unit' => 'px'],
                'typography_font_size_mobile' => ['size' => 20, 'unit' => 'px'],
                'typography_font_weight' => '300',
                'typography_font_style' => 'italic',
                'typography_line_height' => ['size' => 1.8, 'unit' => 'em'],
                'css_classes' => 'sr d1',
                'custom_css' => 'selector{max-width:700px;margin-left:auto;margin-right:auto;}',
                '_margin' => self::margin(24, 0, 32, 0),
            ]),

            // Ornamental divider
            self::html('<div class="sr d2" style="text-align:center;margin-bottom:20px;"><span style="display:inline-block;width:40px;height:1px;background:' . $col['primary'] . ';"></span></div>'),

            // Author name — small caps
            self::html('<div class="sr d3" style="text-align:center;"><span style="font-family:\'' . $fnt['body'] . '\',sans-serif;font-size:12px;font-weight:500;letter-spacing:4px;text-transform:uppercase;color:' . $col['text'] . ';">' . e($test['name']) . '</span><br><span style="font-family:\'' . $fnt['body'] . '\',sans-serif;font-size:11px;letter-spacing:2px;text-transform:uppercase;color:' . $col['muted'] . ';margin-top:4px;display:inline-block;">' . e($test['role']) . '</span></div>'),
        ], 'testimonials');

        // ─── 5. CTA — Refined, gold accent line at top ───
        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'column',
            'flex_align_items' => 'center',
            'flex_justify_content' => 'center',
            'padding' => self::pad(100, 64),
            'padding_mobile' => self::pad(70, 20),
            'background_background' => 'classic',
            'background_color' => $col['surface'],
            'border_border' => 'solid',
            'border_width' => ['unit' => 'px', 'top' => '2', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => false],
            'border_color' => $col['primary'],
            '_element_id' => 'cta',
        ], [
            $this->headline($c['cta_title'] ?? 'Your Journey Starts Here', 'h2', array_merge(
                self::responsiveSize(52, 40, 30),
                [
                    'align' => 'center',
                    'title_color' => $col['text'],
                    'typography_font_weight' => '300',
                    'typography_font_style' => 'italic',
                    'css_classes' => 'sr',
                ]
            )),

            $this->bodyText($c['cta_text'] ?? 'Get in touch today and let us create something beautiful together.', [
                'align' => 'center',
                'typography_font_size' => ['size' => 16, 'unit' => 'px'],
                'custom_css' => 'selector{max-width:460px;margin-left:auto;margin-right:auto;}',
                '_margin' => self::margin(16, 0, 40, 0),
                'css_classes' => 'sr d1',
            ]),

            self::container([
                'flex_direction' => 'row',
                'flex_justify_content' => 'center',
                'content_width' => 'full',
                'css_classes' => 'sr d2',
            ], [
                $this->ctaButton($c['cta_button'] ?? 'Get in Touch', '#contact'),
            ]),
        ]);

        // Back to top
        $sections[] = self::html('<a href="#hero" id="blush-btt">&uarr;</a>');

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
            'flex_align_items' => 'center',
            'padding' => self::pad(160, 64, 120, 64),
            'padding_mobile' => self::pad(100, 20, 60, 20),
            'padding_tablet' => self::pad(120, 40, 80, 40),
            'min_height' => ['size' => 50, 'unit' => 'vh', 'sizes' => []],
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface2'],
        ], [
            $this->eyebrow('About Us'),
            $this->headline($c['about_title'] ?? 'Our Story', 'h1', array_merge(
                self::responsiveSize(72, 52, 38),
                [
                    'align' => 'center',
                    'custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .2s forwards;}',
                ]
            )),
            self::html('<div class="blush-divider-center"></div>'),
            $this->bodyText($c['about_text'] ?? 'Dedicated to elegance and excellence since the very beginning.', [
                'align' => 'center',
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .4s forwards;max-width:560px;margin-left:auto;margin-right:auto;}',
            ]),
        ]);

        // Two-col about
        $sections[] = self::twoCol(
            [self::image($img['about'] ?? '', [
                'custom_css' => 'selector img{width:100%;min-height:400px;object-fit:cover;border-radius:12px;}',
            ])],
            [
                $this->eyebrow('Who We Are'),
                $this->headline($c['about_subtitle'] ?? 'Passion Meets Refinement', 'h2', [
                    'typography_font_size' => ['size' => 44, 'unit' => 'px'],
                ]),
                self::html('<div class="blush-divider"></div>'),
                $this->bodyText($c['about_text'] ?? 'We are a team of dedicated artisans and visionaries.'),
                $this->bodyText($c['about_text2'] ?? 'With years of experience, we bring beauty and sophistication to every project.'),
                $this->ctaButton($c['about_cta'] ?? 'Our Services', '/services/', [
                    '_margin' => self::margin(24, 0, 0, 0),
                ]),
            ],
            50,
            ['padding' => self::pad(120, 64)],
            ['css_classes' => 'sr'],
            ['css_classes' => 'sr d2', 'flex_justify_content' => 'center', 'padding' => self::pad(0, 0, 0, 40)]
        );

        // Stats
        $stats = $c['stats'] ?? [
            ['number' => '500', 'suffix' => '+', 'label' => 'Happy Clients'],
            ['number' => '98', 'suffix' => '%', 'label' => 'Satisfaction'],
            ['number' => '12', 'suffix' => '+', 'label' => 'Years'],
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
                'css_classes' => 'blush-stat sr d' . ($i + 1),
            ], [
                self::html('<div class="blush-stat-n" data-count="' . $s['number'] . '" data-suffix="' . ($s['suffix'] ?? '') . '">' . $s['number'] . ($s['suffix'] ?? '') . '</div>'),
                self::html('<div class="blush-stat-l">' . e($s['label']) . '</div>'),
            ]);
        }
        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'row',
            'flex_direction_mobile' => 'column',
            'flex_wrap' => 'wrap',
            'flex_gap' => ['size' => 0, 'unit' => 'px', 'column' => '0', 'row' => '0'],
            'padding' => self::pad(60, 64),
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
            'flex_align_items' => 'center',
        ], [
            $this->eyebrow('Our Values'),
            $this->headline($c['values_title'] ?? 'What Guides Us', 'h2', [
                'align' => 'center',
            ]),
            self::html('<div class="blush-divider-center"></div>'),
            $this->bodyText($c['values_text'] ?? 'Core principles of beauty, quality, and care that shape everything we create.', [
                'align' => 'center',
                '_margin' => self::margin(0, 0, 50, 0),
                'custom_css' => 'selector{max-width:500px;margin-left:auto;margin-right:auto;}',
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
            'flex_align_items' => 'center',
            'padding' => self::pad(160, 64, 120, 64),
            'padding_mobile' => self::pad(100, 20, 60, 20),
            'padding_tablet' => self::pad(120, 40, 80, 40),
            'min_height' => ['size' => 50, 'unit' => 'vh', 'sizes' => []],
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface2'],
        ], [
            $this->eyebrow('Our Services'),
            $this->headline($c['services_title'] ?? 'What We Offer', 'h1', array_merge(
                self::responsiveSize(72, 52, 38),
                [
                    'align' => 'center',
                    'custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .2s forwards;}',
                ]
            )),
            self::html('<div class="blush-divider-center"></div>'),
            $this->bodyText($c['services_subtitle'] ?? 'Refined services crafted with care and expertise.', [
                'align' => 'center',
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .4s forwards;max-width:560px;margin-left:auto;margin-right:auto;}',
            ]),
        ]);

        // Service cards
        $services = $c['services'] ?? [
            ['icon' => '✿', 'title' => 'Bespoke Design', 'desc' => 'Thoughtfully crafted designs tailored to your unique vision.'],
            ['icon' => '◇', 'title' => 'Premium Experience', 'desc' => 'Every interaction designed to delight and inspire.'],
            ['icon' => '❋', 'title' => 'Refined Details', 'desc' => 'Meticulous attention to every element.'],
        ];

        $cards = [];
        foreach ($services as $i => $svc) {
            $cards[] = self::container([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'padding' => self::pad(48, 32),
                'css_classes' => 'blush-scard sr d' . min($i + 1, 4),
            ], [
                self::html('<div class="blush-scard-icon">' . ($svc['icon'] ?? '✿') . '</div>'),
                self::heading($svc['title'], 'h3', [
                    'title_color' => $this->colors()['text'],
                    'align' => 'center',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['heading'],
                    'typography_font_size' => ['size' => 26, 'unit' => 'px'],
                    'typography_font_weight' => '600',
                    'typography_font_style' => 'italic',
                    '_margin' => self::margin(0, 0, 12, 0),
                ]),
                $this->bodyText($svc['desc'], ['align' => 'center']),
            ]);
        }

        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['bg'],
        ], [
            self::cardGrid($cards, min(count($cards), 3), [
                'flex_gap' => ['size' => 28, 'unit' => 'px', 'column' => '28', 'row' => '28'],
            ]),
        ], 'services-grid');

        // CTA
        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'column',
            'flex_align_items' => 'center',
            'padding' => self::pad(120, 64),
            'background_background' => 'gradient',
            'background_color' => $this->colors()['primary'],
            'background_color_b' => $this->colors()['accent'],
            'background_gradient_angle' => ['size' => 135, 'unit' => 'deg'],
        ], [
            $this->headline($c['cta_title'] ?? 'Ready to Begin?', 'h2', [
                'title_color' => '#FFFFFF',
                'align' => 'center',
            ]),
            self::html('<div class="blush-divider-center" style="background:rgba(255,255,255,.4);"></div>'),
            $this->bodyText($c['cta_text'] ?? 'Contact us today for a complimentary consultation.', [
                'align' => 'center',
                'text_color' => 'rgba(255,255,255,0.75)',
                '_margin' => self::margin(0, 0, 30, 0),
            ]),
            self::button($c['cta_button'] ?? 'Contact Us', '/contact/', [
                'background_color' => '#FFFFFF',
                'button_text_color' => $this->colors()['text'],
                'typography_typography' => 'custom',
                'typography_font_family' => $this->fonts()['body'],
                'typography_font_size' => ['size' => 12, 'unit' => 'px'],
                'typography_font_weight' => '500',
                'typography_letter_spacing' => ['size' => 3, 'unit' => 'px'],
                'typography_text_transform' => 'uppercase',
                'border_radius' => self::radius(50),
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
            'flex_align_items' => 'center',
            'padding' => self::pad(160, 64, 120, 64),
            'padding_mobile' => self::pad(100, 20, 60, 20),
            'padding_tablet' => self::pad(120, 40, 80, 40),
            'min_height' => ['size' => 50, 'unit' => 'vh', 'sizes' => []],
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface2'],
        ], [
            $this->eyebrow('Our Work'),
            $this->headline($c['portfolio_title'] ?? 'Featured Portfolio', 'h1', array_merge(
                self::responsiveSize(72, 52, 38),
                [
                    'align' => 'center',
                    'custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .2s forwards;}',
                ]
            )),
            self::html('<div class="blush-divider-center"></div>'),
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
                'css_classes' => 'blush-photo sr d' . min($i + 1, 4),
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
            'flex_align_items' => 'center',
            'padding' => self::pad(160, 64, 120, 64),
            'padding_mobile' => self::pad(100, 20, 60, 20),
            'padding_tablet' => self::pad(120, 40, 80, 40),
            'min_height' => ['size' => 50, 'unit' => 'vh', 'sizes' => []],
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface2'],
        ], [
            $this->eyebrow('Contact'),
            $this->headline($c['contact_title'] ?? 'Get In Touch', 'h1', array_merge(
                self::responsiveSize(72, 52, 38),
                [
                    'align' => 'center',
                    'custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .2s forwards;}',
                ]
            )),
            self::html('<div class="blush-divider-center"></div>'),
            $this->bodyText($c['contact_subtitle'] ?? 'We would love to hear from you. Reach out anytime.', [
                'align' => 'center',
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .4s forwards;max-width:500px;margin-left:auto;margin-right:auto;}',
            ]),
        ]);

        // Contact info cards
        $contactInfo = $c['contact_info'] ?? [
            ['icon' => '✉', 'label' => 'Email', 'value' => $c['email'] ?? 'hello@example.com'],
            ['icon' => '☎', 'label' => 'Phone', 'value' => $c['phone'] ?? '(555) 123-4567'],
            ['icon' => '◎', 'label' => 'Location', 'value' => $c['address'] ?? '123 Business St, City, State'],
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
                'css_classes' => 'blush-bcard sr d' . ($i + 1),
            ], [
                self::html('<div class="blush-scard-icon" style="margin-bottom:14px;">' . $info['icon'] . '</div>'),
                self::heading($info['label'], 'h4', [
                    'title_color' => $this->colors()['text'],
                    'align' => 'center',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['heading'],
                    'typography_font_size' => ['size' => 20, 'unit' => 'px'],
                    'typography_font_weight' => '600',
                    'typography_font_style' => 'italic',
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
            $this->headline('We Would Love to Hear From You', 'h2', [
                'align' => 'center',
                'typography_font_size' => ['size' => 44, 'unit' => 'px'],
                '_margin' => self::margin(0, 0, 10, 0),
            ]),
            self::html('<div class="blush-divider-center"></div>'),
            self::spacer(20),
            self::html('<form style="max-width:600px;width:100%;margin:0 auto;display:flex;flex-direction:column;gap:18px;">
<input type="text" placeholder="Your Name" style="padding:16px 22px;background:' . $this->colors()['surface2'] . ';border:1px solid ' . $this->colors()['border'] . ';color:' . $this->colors()['text'] . ';font-family:\'' . $this->fonts()['body'] . '\',sans-serif;font-size:14px;border-radius:12px;outline:none;transition:border-color .3s;" onfocus="this.style.borderColor=\'#C9A87C\'" onblur="this.style.borderColor=\'' . $this->colors()['border'] . '\'">
<input type="email" placeholder="Your Email" style="padding:16px 22px;background:' . $this->colors()['surface2'] . ';border:1px solid ' . $this->colors()['border'] . ';color:' . $this->colors()['text'] . ';font-family:\'' . $this->fonts()['body'] . '\',sans-serif;font-size:14px;border-radius:12px;outline:none;transition:border-color .3s;" onfocus="this.style.borderColor=\'#C9A87C\'" onblur="this.style.borderColor=\'' . $this->colors()['border'] . '\'">
<textarea rows="5" placeholder="Your Message" style="padding:16px 22px;background:' . $this->colors()['surface2'] . ';border:1px solid ' . $this->colors()['border'] . ';color:' . $this->colors()['text'] . ';font-family:\'' . $this->fonts()['body'] . '\',sans-serif;font-size:14px;border-radius:12px;outline:none;resize:vertical;transition:border-color .3s;" onfocus="this.style.borderColor=\'#C9A87C\'" onblur="this.style.borderColor=\'' . $this->colors()['border'] . '\'"></textarea>
<button type="submit" style="padding:16px 44px;background:linear-gradient(135deg,' . $this->colors()['primary'] . ',' . $this->colors()['accent'] . ');color:#FFF;font-family:\'' . $this->fonts()['body'] . '\',sans-serif;font-size:12px;font-weight:500;letter-spacing:3px;text-transform:uppercase;border:none;border-radius:50px;cursor:pointer;transition:all .3s;box-shadow:0 4px 20px rgba(201,168,124,.25);">Send Message</button>
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

        // Build nav links separated by gold dots
        $navItems = [];
        foreach ($pages as $slug => $label) {
            $url = ($slug === 'home') ? '/' : '/' . $slug . '/';
            $navItems[] = '<a href="' . $url . '" class="blush-hn">' . e($label) . '</a>';
        }
        $navHtml = implode('<span class="blush-hdot">&middot;</span>', $navItems);

        // Hamburger SVG for mobile
        $hamburger = '<svg class="blush-hburger" viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="' . $c['text'] . '" stroke-width="1.5"><line x1="3" y1="7" x2="21" y2="7"/><line x1="6" y1="12" x2="18" y2="12"/><line x1="3" y1="17" x2="21" y2="17"/></svg>';

        return [self::html('
<header class="blush-hdr" id="blushHdr">
  <div class="blush-hdr-full">
    <a href="/" class="blush-hlogo">' . e($siteName) . '</a>
    <div class="blush-hline"></div>
    <nav class="blush-hnav">' . $navHtml . '</nav>
  </div>
  <div class="blush-hdr-compact">
    <a href="/" class="blush-hlogo-sm">' . e($siteName) . '</a>
    <nav class="blush-hnav-sm">' . $navHtml . '</nav>
    <button class="blush-hmenu" aria-label="Menu">' . $hamburger . '</button>
  </div>
</header>
<div class="blush-mobile-drawer" id="blushDrawer">
  <button class="blush-drawer-close" aria-label="Close">&times;</button>
  <a href="/" class="blush-hlogo" style="margin-bottom:12px;">' . e($siteName) . '</a>
  <div class="blush-hline" style="margin-bottom:24px;"></div>
  <nav class="blush-drawer-nav">' . implode('', array_map(function ($slug, $label) {
            $url = ($slug === 'home') ? '/' : '/' . $slug . '/';
            return '<a href="' . $url . '">' . e($label) . '</a>';
        }, array_keys($pages), array_values($pages))) . '</nav>
</div>
<style>
.blush-hdr{position:fixed;top:0;left:0;right:0;z-index:1000;transition:all .5s cubic-bezier(.4,0,.2,1);}
.blush-hdr-full{display:flex;flex-direction:column;align-items:center;padding:32px 64px 20px;transition:all .5s cubic-bezier(.4,0,.2,1);}
.blush-hdr-compact{display:none;align-items:center;justify-content:space-between;padding:0 48px;height:60px;background:rgba(251,249,247,.97);backdrop-filter:blur(20px);box-shadow:0 1px 16px rgba(45,36,36,.06);}
.blush-hdr.scrolled .blush-hdr-full{display:none;}
.blush-hdr.scrolled .blush-hdr-compact{display:flex;}
.blush-hlogo{font-family:\'' . $f['heading'] . '\',serif;font-size:28px;font-weight:600;font-style:italic;color:' . $c['text'] . ';text-decoration:none;letter-spacing:0.5px;}
.blush-hlogo-sm{font-family:\'' . $f['heading'] . '\',serif;font-size:20px;font-weight:600;font-style:italic;color:' . $c['text'] . ';text-decoration:none;}
.blush-hline{width:40px;height:1px;background:' . $c['primary'] . ';margin:10px 0 14px;opacity:.7;}
.blush-hnav,.blush-hnav-sm{display:flex;align-items:center;gap:0;}
.blush-hn{font-family:\'' . $f['body'] . '\',sans-serif;font-size:11px;font-weight:400;letter-spacing:3px;text-transform:uppercase;color:' . $c['muted'] . ';text-decoration:none;padding:4px 14px;transition:color .3s;}
.blush-hn:hover{color:' . $c['text'] . ';}
.blush-hdot{color:' . $c['primary'] . ';font-size:10px;margin:0 2px;opacity:.6;user-select:none;}
.blush-hmenu{display:none;background:none;border:none;cursor:pointer;padding:6px;}
.blush-hburger{display:block;}
.blush-mobile-drawer{position:fixed;top:0;right:-300px;width:280px;height:100vh;z-index:2000;background:' . $c['surface'] . ';padding:40px 32px;display:flex;flex-direction:column;align-items:center;transition:right .4s cubic-bezier(.4,0,.2,1);box-shadow:-4px 0 30px rgba(45,36,36,.1);}
.blush-mobile-drawer.open{right:0;}
.blush-drawer-close{position:absolute;top:16px;right:20px;background:none;border:none;font-size:28px;color:' . $c['muted'] . ';cursor:pointer;line-height:1;}
.blush-drawer-nav{display:flex;flex-direction:column;align-items:center;gap:0;}
.blush-drawer-nav a{font-family:\'' . $f['body'] . '\',sans-serif;font-size:12px;font-weight:400;letter-spacing:3px;text-transform:uppercase;color:' . $c['muted'] . ';text-decoration:none;padding:12px 0;transition:color .3s;border-bottom:1px solid ' . $c['border'] . ';width:160px;text-align:center;}
.blush-drawer-nav a:last-child{border-bottom:none;}
.blush-drawer-nav a:hover{color:' . $c['primary'] . ';}
@media(max-width:900px){
  .blush-hdr-full{display:none!important;}
  .blush-hdr-compact{display:flex!important;}
  .blush-hnav-sm{display:none!important;}
  .blush-hmenu{display:block!important;}
  .blush-hdr.scrolled .blush-hdr-full{display:none!important;}
}
</style>
<script>
(function(){
  var hdr=document.getElementById("blushHdr"),drawer=document.getElementById("blushDrawer");
  window.addEventListener("scroll",function(){hdr.classList.toggle("scrolled",window.scrollY>80);});
  var menuBtn=hdr.querySelector(".blush-hmenu");if(menuBtn)menuBtn.addEventListener("click",function(){drawer.classList.add("open");});
  var closeBtn=drawer.querySelector(".blush-drawer-close");if(closeBtn)closeBtn.addEventListener("click",function(){drawer.classList.remove("open");});
  drawer.addEventListener("click",function(e){if(e.target.tagName==="A")drawer.classList.remove("open");});
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

        // Nav links separated by gold dots
        $navItems = [];
        foreach ($pages as $slug => $label) {
            $url = ($slug === 'home') ? '/' : '/' . $slug . '/';
            $navItems[] = '<a href="' . $url . '" class="blush-fn">' . e($label) . '</a>';
        }
        $navHtml = implode('<span class="blush-fdot">&middot;</span>', $navItems);

        $email = $contact['email'] ?? 'hello@example.com';
        $phone = $contact['phone'] ?? '';
        $address = $contact['address'] ?? '';

        // Contact line: email · phone · address (single line, small)
        $contactParts = [e($email)];
        if ($phone) $contactParts[] = e($phone);
        if ($address) $contactParts[] = e($address);
        $contactLine = implode(' &nbsp;&middot;&nbsp; ', $contactParts);

        $sections = [];

        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'column',
            'flex_align_items' => 'center',
            'padding' => self::pad(0),
            'background_background' => 'classic',
            'background_color' => $c['surface2'],
        ], [
            // Main centered content area
            self::container([
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'content_width' => 'full',
                'padding' => self::pad(60, 32, 0, 32),
                'padding_mobile' => self::pad(48, 24, 0, 24),
            ], [
                // Decorative ornament
                self::html('<div style="text-align:center;color:' . $c['primary'] . ';font-size:24px;letter-spacing:6px;margin-bottom:24px;">&#10038;</div>'),

                // Brand name centered
                self::html('<a href="/" style="font-family:\'' . $f['heading'] . '\',serif;font-size:28px;font-weight:600;font-style:italic;color:' . $c['text'] . ';text-decoration:none;display:block;text-align:center;margin-bottom:8px;">' . e($siteName) . '</a>'),

                // Tagline centered
                self::html('<div style="font-family:\'' . $f['body'] . '\',sans-serif;font-size:10px;font-weight:400;letter-spacing:3px;text-transform:uppercase;color:' . $c['primary'] . ';text-align:center;margin-bottom:24px;">' . e($contact['tagline'] ?? 'Elegance Refined') . '</div>'),

                // Thin gold divider (80px)
                self::html('<div style="width:80px;height:1px;background:' . $c['primary'] . ';opacity:.5;margin:0 auto 28px;"></div>'),

                // Nav links horizontal row with dots
                self::html('<nav style="display:flex;flex-wrap:wrap;justify-content:center;align-items:center;gap:0;margin-bottom:28px;">' . $navHtml . '</nav>'),

                // Contact info single line
                self::html('<div style="font-family:\'' . $f['body'] . '\',sans-serif;font-size:12px;color:' . $c['muted'] . ';text-align:center;margin-bottom:28px;line-height:1.8;">' . $contactLine . '</div>'),

                // Another thin divider
                self::html('<div style="width:120px;height:1px;background:' . $c['border'] . ';margin:0 auto 24px;"></div>'),

                // Copyright centered
                self::html('<div style="font-family:\'' . $f['body'] . '\',sans-serif;font-size:11px;color:' . $c['muted'] . ';text-align:center;margin-bottom:16px;opacity:.7;">&copy; ' . date('Y') . ' ' . e($siteName) . '. All rights reserved.</div>'),

                // Social icons centered — minimal circular outlined
                self::html('<div class="blush-fsocial">
<a href="#" aria-label="Facebook"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg></a>
<a href="#" aria-label="Instagram"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="5"/><circle cx="17.5" cy="6.5" r="1"/></svg></a>
<a href="#" aria-label="Pinterest"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M8 12c0-2.2 1.8-4 4-4s4 1.8 4 4c0 2.5-2 4-3.5 4-.8 0-1.2-.5-1-1.2l1-4"/></svg></a>
<a href="#" aria-label="Email"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 4L12 13 2 4"/></svg></a>
</div>'),
            ]),

            // Bottom spacer
            self::html('<div style="height:40px;"></div>'),
        ]);

        // Footer styles via HTML widget (injected once)
        $sections[] = self::html('<style>
.blush-fn{font-family:\'' . $f['body'] . '\',sans-serif;font-size:11px;font-weight:400;letter-spacing:3px;text-transform:uppercase;color:' . $c['muted'] . ';text-decoration:none;padding:4px 12px;transition:color .3s;}
.blush-fn:hover{color:' . $c['text'] . ';}
.blush-fdot{color:' . $c['primary'] . ';font-size:10px;margin:0 2px;opacity:.5;user-select:none;}
.blush-fsocial{display:flex;justify-content:center;align-items:center;gap:12px;}
.blush-fsocial a{display:flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:50%;border:1px solid ' . $c['border'] . ';color:' . $c['muted'] . ';text-decoration:none;transition:all .3s;}
.blush-fsocial a:hover{border-color:' . $c['primary'] . ';color:' . $c['primary'] . ';}
@media(max-width:600px){.blush-fn{font-size:10px;letter-spacing:2px;padding:4px 8px;}}
</style>');

        return $sections;
    }
}
