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
<link href="https://fonts.googleapis.com/css2?family={$hf}:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&family={$bf}:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{--blush-bg:{$c['bg']};--blush-surface:{$c['surface']};--blush-surface2:{$c['surface2']};--blush-text:{$c['text']};--blush-muted:{$c['muted']};--blush-border:{$c['border']};--blush-gold:{$c['primary']};--blush-gold2:{$c['secondary']};--blush-accent:{$c['accent']};}
body,body.elementor-template-canvas{background:var(--blush-bg);color:var(--blush-text);font-family:'{$f['body']}',sans-serif;overflow-x:hidden;margin:0;padding:0;}
.elementor-element,.elementor.elementor-2{font-family:'{$f['body']}',sans-serif;}
.elementor-widget{margin-bottom:0 !important;}
.e-con{--gap:0px;}
.e-con>.elementor-widget{width:100%;}

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
@media(max-width:1100px){.blush-photo{min-height:200px;}}
@media(max-width:700px){.blush-features{grid-template-columns:1fr;}}
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
            'content_width' => 'full-width',
            'flex_direction' => 'column',
            'padding' => self::pad(120, 64),
        ];
        if ($id) {
            $defaults['_element_id'] = $id;
        }
        return self::container(array_merge($defaults, $settings), $elements);
    }

    // ═══════════════════════════════════════════════════════════
    // HOME PAGE
    // ═══════════════════════════════════════════════════════════

    public function buildHomePage(array $c, array $img): array
    {
        $siteName = $c['site_name'] ?? 'Business Name';
        $heroTitle = $c['hero_title'] ?? 'Timeless Elegance, Refined For You';
        $heroSub = $c['hero_subtitle'] ?? 'We craft exceptional experiences with refined taste and meticulous attention to every detail.';
        $heroCta = $c['hero_cta'] ?? 'Discover More';
        $heroCtaUrl = $c['hero_cta_url'] ?? '#services';

        $aboutTitle = $c['about_title'] ?? 'Our Story';
        $aboutText = $c['about_text'] ?? 'We believe in the power of beauty and elegance to transform everyday moments into extraordinary experiences.';
        $aboutText2 = $c['about_text2'] ?? 'With a passion for perfection and an eye for detail, we curate services that elevate and inspire.';

        $services = $c['services'] ?? [
            ['icon' => '✿', 'title' => 'Bespoke Design', 'desc' => 'Thoughtfully crafted designs tailored to your unique vision and personal aesthetic.'],
            ['icon' => '◇', 'title' => 'Premium Experience', 'desc' => 'Every interaction is designed to delight, from first consultation to final reveal.'],
            ['icon' => '❋', 'title' => 'Refined Details', 'desc' => 'Meticulous attention to every element ensures a flawless, sophisticated result.'],
        ];

        $benefits = $c['benefits'] ?? [
            ['icon' => '♛', 'title' => 'Luxury Quality', 'desc' => 'Only the finest materials and techniques for results that exceed expectations.'],
            ['icon' => '✧', 'title' => 'Personal Touch', 'desc' => 'Every project receives individual care and attention to your preferences.'],
            ['icon' => '❀', 'title' => 'Timeless Style', 'desc' => 'Designs that transcend trends and remain beautiful for years to come.'],
            ['icon' => '✦', 'title' => 'Expert Care', 'desc' => 'Our experienced team brings passion and expertise to every detail.'],
        ];

        $testimonials = $c['testimonials'] ?? [
            ['quote' => 'An absolutely exquisite experience from start to finish. Every detail was perfection.', 'name' => 'Sophia L.', 'role' => 'Bride', 'initials' => 'SL'],
            ['quote' => 'The attention to detail and level of care is unlike anything I have experienced before.', 'name' => 'Amara K.', 'role' => 'Client', 'initials' => 'AK'],
            ['quote' => 'They transformed my vision into something even more beautiful than I imagined.', 'name' => 'Elena R.', 'role' => 'Fashion Designer', 'initials' => 'ER'],
        ];

        $stats = $c['stats'] ?? [
            ['number' => '500', 'suffix' => '+', 'label' => 'Happy Clients'],
            ['number' => '12', 'suffix' => '+', 'label' => 'Years of Excellence'],
            ['number' => '98', 'suffix' => '%', 'label' => 'Satisfaction Rate'],
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
            'flex_align_items' => 'center',
            'padding' => self::pad(0, 64, 120, 64),
            'min_height' => ['size' => 100, 'unit' => 'vh'],
            'background_background' => 'classic',
            'background_image' => ['url' => $heroImg, 'id' => ''],
            'background_position' => 'center center',
            'background_size' => 'cover',
            '_element_id' => 'hero',
            'custom_css' => "selector{position:relative;overflow:hidden;}
selector::before{content:'';position:absolute;inset:0;background:linear-gradient(to bottom,rgba(251,249,247,.4) 0%,rgba(251,249,247,.75) 50%,rgba(251,249,247,.95) 100%);z-index:0;}
selector>.e-con-inner,selector>.elementor-widget{position:relative;z-index:2;}",
        ], [
            self::html('<div style="text-align:center;opacity:0;animation:fadeUp .7s ease .2s forwards;"><span class="eyebrow" style="justify-content:center;">' . e($c['hero_eyebrow'] ?? 'Welcome to ' . $siteName) . '</span></div>'),

            $this->headline($heroTitle, 'h1', array_merge(
                self::responsiveSize(80, 56, 40),
                [
                    'align' => 'center',
                    'typography_letter_spacing' => ['size' => -0.5, 'unit' => 'px'],
                    '_margin' => self::margin(0, 0, 24, 0),
                    'custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .35s forwards;max-width:800px;margin-left:auto;margin-right:auto;}',
                ]
            )),

            self::textEditor('<p style="text-align:center;">' . e($heroSub) . '</p>', [
                'text_color' => $this->colors()['muted'],
                'typography_typography' => 'custom',
                'typography_font_family' => $this->fonts()['body'],
                'typography_font_size' => ['size' => 17, 'unit' => 'px'],
                'typography_line_height' => ['size' => 1.75, 'unit' => 'em'],
                'typography_font_weight' => '300',
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .55s forwards;max-width:520px;margin-left:auto;margin-right:auto;}',
            ]),

            self::container([
                'flex_direction' => 'row',
                'flex_justify_content' => 'center',
                'flex_align_items' => 'center',
                'gap' => ['size' => 16, 'unit' => 'px'],
                'content_width' => 'full-width',
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .7s forwards;}',
            ], [
                $this->ctaButton($heroCta, $heroCtaUrl),
                $this->ghostButton($c['hero_ghost_cta'] ?? 'Learn More', '#about'),
            ]),
        ]);

        // ─── ABOUT PREVIEW ───
        $sections[] = self::twoCol(
            // Left: image
            [self::image($aboutImg, [
                'custom_css' => 'selector img{width:100%;height:100%;min-height:400px;object-fit:cover;border-radius:12px;transition:transform 8s ease;} selector:hover img{transform:scale(1.03);}',
            ])],
            // Right: text
            [
                $this->eyebrow($c['about_eyebrow'] ?? 'About Us'),
                $this->headline($aboutTitle, 'h2', ['_margin' => self::margin(0, 0, 8, 0)]),
                self::html('<div class="blush-divider"></div>'),
                $this->bodyText($aboutText),
                $this->bodyText($aboutText2),
                $this->ctaButton($c['about_cta'] ?? 'Read More', '#about', [
                    '_margin' => self::margin(20, 0, 0, 0),
                ]),
            ],
            50,
            ['padding' => self::pad(120, 64), '_element_id' => 'about'],
            ['css_classes' => 'sr', 'padding' => self::pad(0), 'flex_justify_content' => 'center'],
            ['css_classes' => 'sr d2', 'padding' => self::pad(20, 0, 20, 40), 'flex_justify_content' => 'center']
        );

        // ─── SERVICES ───
        $serviceCards = [];
        foreach ($services as $i => $svc) {
            $serviceCards[] = self::container([
                'content_width' => 'full-width',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'width' => ['size' => 33.33, 'unit' => '%'],
                'padding' => self::pad(48, 32),
                'css_classes' => 'blush-scard sr d' . ($i + 1),
            ], [
                self::html('<div class="blush-scard-icon">' . ($svc['icon'] ?? '✿') . '</div>'),
                self::heading($svc['title'], 'h3', [
                    'title_color' => $this->colors()['text'],
                    'align' => 'center',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['heading'],
                    'typography_font_size' => ['size' => 24, 'unit' => 'px'],
                    'typography_font_weight' => '600',
                    'typography_font_style' => 'italic',
                    '_margin' => self::margin(0, 0, 12, 0),
                ]),
                $this->bodyText($svc['desc'], [
                    'align' => 'center',
                    'typography_font_size' => ['size' => 14, 'unit' => 'px'],
                ]),
            ]);
        }

        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface2'],
            'flex_align_items' => 'center',
        ], [
            $this->eyebrow($c['services_eyebrow'] ?? 'Our Services'),
            $this->headline($c['services_title'] ?? 'What We Offer', 'h2', [
                'align' => 'center',
            ]),
            self::html('<div class="blush-divider-center"></div>'),
            $this->bodyText($c['services_subtitle'] ?? 'Curated services designed with elegance and care.', [
                'align' => 'center',
                '_margin' => self::margin(0, 0, 8, 0),
                'custom_css' => 'selector{max-width:520px;margin-left:auto;margin-right:auto;}',
            ]),
            self::container([
                'flex_direction' => 'row',
                'content_width' => 'full-width',
                'gap' => ['size' => 28, 'unit' => 'px'],
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
                'padding' => self::pad(44, 28),
                'css_classes' => 'blush-bcard sr d' . ($i + 1),
            ], [
                self::textEditor('<span style="font-size:32px;display:block;margin-bottom:16px;color:var(--blush-gold);">' . ($b['icon'] ?? '✧') . '</span>'),
                self::heading($b['title'], 'h3', [
                    'title_color' => $this->colors()['text'],
                    'align' => 'center',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['heading'],
                    'typography_font_size' => ['size' => 20, 'unit' => 'px'],
                    'typography_font_weight' => '600',
                    'typography_font_style' => 'italic',
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
            'flex_align_items' => 'center',
        ], [
            $this->eyebrow($c['benefits_eyebrow'] ?? 'Why Choose Us'),
            $this->headline($c['benefits_title'] ?? 'The Difference is in the Details', 'h2', [
                'align' => 'center',
            ]),
            self::html('<div class="blush-divider-center"></div>'),
            self::container([
                'flex_direction' => 'row',
                'content_width' => 'full-width',
                'gap' => ['size' => 24, 'unit' => 'px'],
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
                'padding' => self::pad(48, 20),
                'css_classes' => 'blush-stat sr d' . ($i + 1),
            ], [
                self::html('<div class="blush-stat-n" data-count="' . $s['number'] . '" data-suffix="' . ($s['suffix'] ?? '') . '">' . $s['number'] . ($s['suffix'] ?? '') . '</div>'),
                self::html('<div class="blush-stat-l">' . e($s['label']) . '</div>'),
            ]);
        }

        $sections[] = self::container([
            'content_width' => 'full-width',
            'flex_direction' => 'row',
            'gap' => ['size' => 0, 'unit' => 'px'],
            'padding' => self::pad(60, 64),
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
            'padding' => self::pad(48),
            'border_radius' => self::radius(12),
            'css_classes' => 'blush-tcard blush-tcard-feat sr',
        ], [
            self::container(['content_width' => 'full-width', 'flex_direction' => 'column'], [
                self::html('<div class="blush-stars">★★★★★</div>'),
                self::textEditor('"' . e($featTest['quote']) . '"', [
                    'text_color' => 'rgba(255,255,255,0.9)',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['heading'],
                    'typography_font_size' => ['size' => 20, 'unit' => 'px'],
                    'typography_font_style' => 'italic',
                    'typography_line_height' => ['size' => 1.8, 'unit' => 'em'],
                    '_margin' => self::margin(0, 0, 28, 0),
                ]),
            ]),
            self::html('<div style="display:flex;align-items:center;gap:14px;"><div style="width:48px;height:48px;border-radius:50%;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;font-family:\'Cormorant Garamond\',serif;font-size:18px;font-weight:600;font-style:italic;color:#FFF;">' . e($featTest['initials'] ?? 'AB') . '</div><div><h5 style="font-family:\'Cormorant Garamond\',serif;font-size:17px;font-weight:600;color:#FFF;margin:0;">' . e($featTest['name']) . '</h5><span style="font-size:12px;color:rgba(255,255,255,.6);">' . e($featTest['role']) . '</span></div></div>'),
        ]);

        $sideCards = [];
        foreach ($sideTests as $i => $t) {
            $sideCards[] = self::container([
                'content_width' => 'full-width',
                'flex_direction' => 'column',
                'padding' => self::pad(40),
                'border_radius' => self::radius(12),
                'css_classes' => 'blush-tcard sr d' . ($i + 1),
            ], [
                self::html('<div class="blush-quote">"</div>'),
                self::textEditor('"' . e($t['quote']) . '"', [
                    'text_color' => $this->colors()['text'],
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['heading'],
                    'typography_font_size' => ['size' => 16, 'unit' => 'px'],
                    'typography_font_style' => 'italic',
                    'typography_line_height' => ['size' => 1.8, 'unit' => 'em'],
                    '_margin' => self::margin(0, 0, 24, 0),
                ]),
                self::html('<div style="display:flex;align-items:center;gap:14px;"><div style="width:44px;height:44px;border-radius:50%;background:rgba(201,168,124,.1);display:flex;align-items:center;justify-content:center;font-family:\'Cormorant Garamond\',serif;font-size:16px;font-weight:600;font-style:italic;color:var(--blush-gold);">' . e($t['initials'] ?? 'AB') . '</div><div><h5 style="font-family:\'Cormorant Garamond\',serif;font-size:16px;font-weight:600;color:var(--blush-text);margin:0;">' . e($t['name']) . '</h5><span style="font-size:12px;color:var(--blush-muted);">' . e($t['role']) . '</span></div></div>'),
            ]);
        }

        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface2'],
            'flex_align_items' => 'center',
        ], [
            $this->eyebrow($c['testimonials_eyebrow'] ?? 'Testimonials'),
            $this->headline($c['testimonials_title'] ?? 'Kind Words From Our Clients', 'h2', [
                'align' => 'center',
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
                    'gap' => ['size' => 20, 'unit' => 'px'],
                    'width' => ['size' => 45, 'unit' => '%'],
                ], $sideCards),
            ]),
        ], 'testimonials');

        // ─── CTA ───
        $sections[] = self::container([
            'content_width' => 'full-width',
            'flex_direction' => 'column',
            'flex_align_items' => 'center',
            'flex_justify_content' => 'center',
            'min_height' => ['size' => 500, 'unit' => 'px'],
            'padding' => self::pad(120, 64),
            'background_background' => 'gradient',
            'background_color' => $this->colors()['primary'],
            'background_color_b' => $this->colors()['accent'],
            'background_gradient_angle' => ['size' => 135, 'unit' => 'deg'],
            '_element_id' => 'cta',
        ], [
            $this->eyebrow($c['cta_eyebrow'] ?? 'Ready to Begin?'),
            $this->headline($c['cta_title'] ?? 'Your Journey Starts Here', 'h2', array_merge(
                self::responsiveSize(64, 48, 36),
                [
                    'align' => 'center',
                    'title_color' => '#FFFFFF',
                    '_margin' => self::margin(20, 0, 24, 0),
                ]
            )),
            $this->bodyText($c['cta_text'] ?? 'Get in touch today and let us create something beautiful together.', [
                'align' => 'center',
                'text_color' => 'rgba(255,255,255,0.75)',
                'typography_font_size' => ['size' => 17, 'unit' => 'px'],
                'custom_css' => 'selector{max-width:500px;margin-left:auto;margin-right:auto;}',
                '_margin' => self::margin(0, 0, 40, 0),
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
                self::button($c['cta_ghost'] ?? 'Contact Us', '#contact', [
                    'background_color' => 'transparent',
                    'button_text_color' => '#FFFFFF',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['body'],
                    'typography_font_size' => ['size' => 12, 'unit' => 'px'],
                    'typography_font_weight' => '500',
                    'typography_letter_spacing' => ['size' => 3, 'unit' => 'px'],
                    'typography_text_transform' => 'uppercase',
                    'border_border' => 'solid',
                    'border_width' => self::pad(1),
                    'border_color' => 'rgba(255,255,255,0.35)',
                    'border_radius' => self::radius(50),
                    'button_padding' => self::pad(15, 36),
                ]),
            ]),
        ]);

        // Back to top
        $sections[] = self::html('<a href="#hero" id="blush-btt">↑</a>');

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
            'flex_align_items' => 'center',
            'padding' => self::pad(160, 64, 120, 64),
            'min_height' => ['size' => 50, 'unit' => 'vh'],
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
                'content_width' => 'full-width',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'width' => ['size' => round(100 / count($stats), 2), 'unit' => '%'],
                'padding' => self::pad(48, 20),
                'css_classes' => 'blush-stat sr d' . ($i + 1),
            ], [
                self::html('<div class="blush-stat-n" data-count="' . $s['number'] . '" data-suffix="' . ($s['suffix'] ?? '') . '">' . $s['number'] . ($s['suffix'] ?? '') . '</div>'),
                self::html('<div class="blush-stat-l">' . e($s['label']) . '</div>'),
            ]);
        }
        $sections[] = self::container([
            'content_width' => 'full-width',
            'flex_direction' => 'row',
            'gap' => ['size' => 0, 'unit' => 'px'],
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
            'content_width' => 'full-width',
            'flex_direction' => 'column',
            'flex_justify_content' => 'center',
            'flex_align_items' => 'center',
            'padding' => self::pad(160, 64, 120, 64),
            'min_height' => ['size' => 50, 'unit' => 'vh'],
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
                'content_width' => 'full-width',
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
                'gap' => ['size' => 28, 'unit' => 'px'],
            ]),
        ], 'services-grid');

        // CTA
        $sections[] = self::container([
            'content_width' => 'full-width',
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
            'content_width' => 'full-width',
            'flex_direction' => 'column',
            'flex_justify_content' => 'center',
            'flex_align_items' => 'center',
            'padding' => self::pad(160, 64, 120, 64),
            'min_height' => ['size' => 50, 'unit' => 'vh'],
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
                'content_width' => 'full-width',
                'width' => ['size' => 33.33, 'unit' => '%'],
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
            'flex_align_items' => 'center',
            'padding' => self::pad(160, 64, 120, 64),
            'min_height' => ['size' => 50, 'unit' => 'vh'],
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
                'content_width' => 'full-width',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'width' => ['size' => 33.33, 'unit' => '%'],
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
        $navLinks = '';
        foreach ($pages as $slug => $label) {
            $url = ($slug === 'home') ? '/' : '/' . $slug . '/';
            $navLinks .= '<li><a href="' . $url . '">' . e($label) . '</a></li>';
        }

        return [self::html('<nav class="blush-nav" id="mainNav" style="position:fixed;top:0;left:0;right:0;z-index:1000;padding:0 64px;height:90px;display:flex;align-items:center;justify-content:space-between;transition:background .4s,height .3s,box-shadow .4s;background:transparent;">
<a href="/" style="font-family:\'Cormorant Garamond\',serif;font-size:24px;font-weight:600;font-style:italic;letter-spacing:0.5px;color:' . $this->colors()['text'] . ';text-decoration:none;">' . e($siteName) . '</a>
<ul style="display:flex;gap:0;list-style:none;position:absolute;left:50%;transform:translateX(-50%);padding:0;margin:0;">' . $navLinks . '</ul>
<a href="/contact/" style="padding:10px 28px;background:' . $this->colors()['primary'] . ';color:#FFF;font-family:\'' . $this->fonts()['body'] . '\',sans-serif;font-size:11px;font-weight:500;letter-spacing:2px;text-transform:uppercase;border-radius:50px;text-decoration:none;transition:all .3s;box-shadow:0 2px 12px rgba(201,168,124,.2);">Get in Touch</a>
</nav>
<style>
.blush-nav ul a{display:block;padding:8px 20px;font-family:\'' . $this->fonts()['body'] . '\',sans-serif;font-size:11px;font-weight:400;letter-spacing:2px;text-transform:uppercase;color:' . $this->colors()['muted'] . ';text-decoration:none;transition:color .3s;position:relative;}
.blush-nav ul a::after{content:\'\';position:absolute;bottom:4px;left:20px;right:20px;height:1px;background:' . $this->colors()['primary'] . ';transform:scaleX(0);transform-origin:center;transition:transform .3s;}
.blush-nav ul a:hover{color:' . $this->colors()['text'] . ';}
.blush-nav ul a:hover::after{transform:scaleX(1);}
.blush-nav.bg{background:rgba(251,249,247,.97)!important;backdrop-filter:blur(20px);height:68px!important;box-shadow:0 2px 20px rgba(45,36,36,.06);}
@media(max-width:1100px){.blush-nav ul{display:none!important;}.blush-nav{padding:0 28px!important;height:68px!important;background:rgba(251,249,247,.97)!important;backdrop-filter:blur(20px);box-shadow:0 2px 20px rgba(45,36,36,.06);}}
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

        $contactHtml = '<div class="blush-ci"><div class="blush-ci-i">✉</div><div><small>Email</small><span>' . e($email) . '</span></div></div>';
        if ($phone) {
            $contactHtml .= '<div class="blush-ci"><div class="blush-ci-i">☎</div><div><small>Phone</small><span>' . e($phone) . '</span></div></div>';
        }
        if ($address) {
            $contactHtml .= '<div class="blush-ci"><div class="blush-ci-i">◎</div><div><small>Location</small><span>' . e($address) . '</span></div></div>';
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
                self::html('<div><div style="font-family:\'Cormorant Garamond\',serif;font-size:24px;font-weight:600;font-style:italic;color:' . $this->colors()['text'] . ';margin-bottom:4px;">' . e($siteName) . '</div><div style="font-size:10px;font-weight:400;letter-spacing:3px;text-transform:uppercase;color:var(--blush-gold);margin-bottom:18px;font-family:\'' . $this->fonts()['body'] . '\',sans-serif;">' . e($contact['tagline'] ?? 'Elegance Refined') . '</div><p style="font-size:14px;color:var(--blush-muted);line-height:1.8;max-width:260px;">' . e($contact['footer_text'] ?? 'Crafting beauty and elegance in every detail.') . '</p><div class="blush-social"><a href="#">✦</a><a href="#">✧</a><a href="#">◇</a></div></div>'),
                self::html('<div class="blush-fc"><h4>Navigate</h4><ul>' . $navLinks . '</ul></div>'),
                self::html('<div class="blush-fc"><h4>Contact</h4>' . $contactHtml . '</div>'),
            ]),
            self::container([
                'flex_direction' => 'row',
                'flex_justify_content' => 'space-between',
                'flex_align_items' => 'center',
                'content_width' => 'full-width',
                'padding' => self::pad(18, 64),
            ], [
                self::textEditor('<p style="font-size:12px;color:var(--blush-muted);">&copy; ' . date('Y') . ' <span style="color:var(--blush-gold);">' . e($siteName) . '</span>. All rights reserved.</p>'),
                self::textEditor('<a href="#" style="font-size:11.5px;color:var(--blush-muted);text-decoration:none;">Privacy Policy</a> &nbsp;&nbsp; <a href="#" style="font-size:11.5px;color:var(--blush-muted);text-decoration:none;">Terms of Service</a>'),
            ]),
        ]);

        return $sections;
    }
}
