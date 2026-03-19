<?php

namespace App\Services\Layouts;

/**
 * Azure — Modern blue SaaS layout with gradient accents and floating cards.
 * White bg, blue gradient CTAs, rounded 16px corners, scroll-reveal,
 * pill badges, gradient hero text, floating card shadows.
 */
class LayoutAzure extends AbstractLayout
{
    public function slug(): string { return 'azure'; }
    public function name(): string { return 'Azure'; }
    public function description(): string { return 'Modern blue SaaS design with gradient accents and floating cards'; }
    public function bestFor(): array { return ['SaaS', 'Tech', 'Agency', 'Startup']; }
    public function isDark(): bool { return false; }

    public function colors(): array
    {
        return [
            'primary'   => '#2563EB',
            'secondary' => '#1D4ED8',
            'accent'    => '#06B6D4',
            'bg'        => '#F8FAFC',
            'surface'   => '#FFFFFF',
            'surface2'  => '#F1F5F9',
            'text'      => '#0F172A',
            'muted'     => 'rgba(15,23,42,0.55)',
            'border'    => 'rgba(15,23,42,0.08)',
        ];
    }

    public function fonts(): array
    {
        return ['heading' => 'Plus Jakarta Sans', 'body' => 'Inter'];
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
<link href="https://fonts.googleapis.com/css2?family={$hf}:wght@400;500;600;700;800&family={$bf}:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root{--azure-primary:{$c['primary']};--azure-secondary:{$c['secondary']};--azure-accent:{$c['accent']};--azure-bg:{$c['bg']};--azure-surface:{$c['surface']};--azure-surface2:{$c['surface2']};--azure-text:{$c['text']};--azure-muted:{$c['muted']};--azure-border:{$c['border']};}
body,body.elementor-template-canvas{background:var(--azure-bg);color:var(--azure-text);font-family:'{$f['body']}',sans-serif;overflow-x:hidden;margin:0;padding:0;}
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

/* Gradient text */
.azure-gradient-text{background:linear-gradient(135deg,{$c['primary']} 0%,{$c['accent']} 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}

/* Eyebrow pill */
.eyebrow{display:inline-flex;align-items:center;gap:8px;font-size:13px;font-weight:600;letter-spacing:1px;text-transform:uppercase;color:{$c['primary']};background:rgba(37,99,235,0.08);padding:6px 16px;border-radius:50px;margin-bottom:20px;}

/* Floating cards */
.azure-card{background:{$c['surface']};border-radius:16px;box-shadow:0 4px 24px rgba(0,0,0,0.06);transition:all .35s ease;overflow:hidden;border:1px solid {$c['border']};}
.azure-card:hover{transform:translateY(-4px);box-shadow:0 12px 40px rgba(0,0,0,0.1);}

/* Feature card */
.azure-fcard{background:{$c['surface']};border-radius:16px;box-shadow:0 4px 24px rgba(0,0,0,0.06);transition:all .35s ease;border:1px solid {$c['border']};text-align:center;}
.azure-fcard:hover{transform:translateY(-4px);box-shadow:0 12px 40px rgba(37,99,235,0.12);}

/* Icon circle */
.azure-icon{width:56px;height:56px;border-radius:14px;background:linear-gradient(135deg,rgba(37,99,235,0.1) 0%,rgba(6,182,212,0.1) 100%);display:flex;align-items:center;justify-content:center;font-size:24px;margin:0 auto 16px auto;}

/* Benefit card */
.azure-bcard{background:{$c['surface']};border-radius:16px;box-shadow:0 4px 24px rgba(0,0,0,0.06);transition:all .35s ease;border:1px solid {$c['border']};text-align:center;}
.azure-bcard:hover{transform:translateY(-4px);box-shadow:0 12px 40px rgba(37,99,235,0.12);border-color:rgba(37,99,235,0.2);}

/* Stats */
.azure-stat{text-align:center;}
.azure-stat-n{font-family:'{$f['heading']}',sans-serif;font-size:48px;font-weight:800;background:linear-gradient(135deg,{$c['primary']},{$c['accent']});-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;line-height:1;}
.azure-stat-l{font-size:14px;color:{$c['muted']};margin-top:8px;font-weight:500;}

/* Testimonial */
.azure-tcard{background:{$c['surface']};border-radius:16px;box-shadow:0 4px 24px rgba(0,0,0,0.06);transition:all .35s ease;border:1px solid {$c['border']};}
.azure-tcard:hover{transform:translateY(-4px);box-shadow:0 12px 40px rgba(0,0,0,0.1);}
.azure-tcard-feat{background:linear-gradient(135deg,{$c['primary']},{$c['accent']});border:none!important;}
.azure-tcard-feat:hover{transform:translateY(-6px);box-shadow:0 20px 48px rgba(37,99,235,0.35);}

/* Stars */
.azure-stars{color:#FBBF24;letter-spacing:2px;font-size:14px;margin-bottom:14px;}

/* Pill badge */
.azure-badge{display:inline-flex;align-items:center;gap:6px;padding:5px 14px;border-radius:50px;font-size:12px;font-weight:600;letter-spacing:0.5px;background:rgba(37,99,235,0.08);border:1px solid rgba(37,99,235,0.15);color:{$c['primary']};}

/* Photo hover */
.azure-photo{overflow:hidden;position:relative;border-radius:16px;box-shadow:0 4px 24px rgba(0,0,0,0.06);}
.azure-photo img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .7s ease;}
.azure-photo:hover img{transform:scale(1.04);}

/* Footer */
.azure-fc h4{font-size:14px;font-weight:700;color:{$c['text']};margin-bottom:20px;}
.azure-fc ul{list-style:none;display:flex;flex-direction:column;gap:12px;padding:0;margin:0;}
.azure-fc a{font-size:14px;color:{$c['muted']};text-decoration:none;transition:color .3s;}
.azure-fc a:hover{color:{$c['primary']};}
.azure-social{display:flex;gap:10px;margin-top:24px;}
.azure-social a{width:40px;height:40px;border:1px solid {$c['border']};border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:16px;text-decoration:none;color:{$c['muted']};transition:all .3s;background:{$c['surface']};}
.azure-social a:hover{border-color:{$c['primary']};color:{$c['primary']};background:rgba(37,99,235,0.05);transform:translateY(-3px);}

/* Contact info */
.azure-ci{display:flex;align-items:flex-start;gap:12px;margin-bottom:16px;}
.azure-ci-i{width:40px;height:40px;min-width:40px;background:linear-gradient(135deg,rgba(37,99,235,0.1),rgba(6,182,212,0.1));border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:16px;}
.azure-ci small{display:block;font-size:12px;color:{$c['muted']};margin-bottom:2px;}
.azure-ci span{font-size:14px;color:{$c['text']};font-weight:500;}

/* Back to top */
#azure-btt{position:fixed;bottom:28px;right:28px;width:46px;height:46px;background:linear-gradient(135deg,{$c['primary']},{$c['accent']});color:#FFF;border-radius:14px;font-size:20px;display:flex;align-items:center;justify-content:center;text-decoration:none;z-index:500;opacity:0;transform:translateY(12px);pointer-events:none;transition:all .4s;box-shadow:0 8px 24px rgba(37,99,235,0.35);}
#azure-btt.show{opacity:1;transform:translateY(0);pointer-events:all;}
#azure-btt:hover{transform:translateY(-4px)!important;box-shadow:0 12px 32px rgba(37,99,235,0.5);}

/* Responsive */
@media(max-width:1100px){
  .azure-photo{min-height:200px;}
  .azure-fcard.e-con,.azure-bcard.e-con{--width:48% !important;width:48% !important;}
}
@media(max-width:767px){
  .azure-fcard.e-con,.azure-bcard.e-con,.azure-stat.e-con,.azure-photo.e-con{--width:100% !important;width:100% !important;}
  .azure-tcard.e-con{--width:100% !important;width:100% !important;}
  .azure-nav{padding:0 20px !important;}
  .azure-nav ul{display:none !important;}
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
const btt=document.getElementById('azure-btt');
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
    // HELPER OVERRIDES for Azure style
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
            'typography_font_weight' => '800',
            'typography_line_height' => ['size' => 1.1, 'unit' => 'em'],
            'typography_letter_spacing' => ['size' => -0.5, 'unit' => 'px'],
        ], self::responsiveSize(56, 42, 32), $extra));
    }

    public function eyebrow(string $text): array
    {
        return self::textEditor('<p class="eyebrow">' . $text . '</p>', ['align' => 'center']);
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
            'typography_line_height' => ['size' => 1.75, 'unit' => 'em'],
            'typography_font_weight' => '400',
        ], $extra));
    }

    public function ctaButton(string $text, string $url = '#', array $extra = []): array
    {
        $f = $this->fonts();
        return self::button($text, $url, array_merge([
            'align' => 'center',
            'button_type' => 'default',
            'background_color' => '#2563EB',
            'button_text_color' => '#FFFFFF',
            'typography_typography' => 'custom',
            'typography_font_family' => $f['heading'],
            'typography_font_size' => ['size' => 15, 'unit' => 'px'],
            'typography_font_weight' => '600',
            'typography_letter_spacing' => ['size' => 0.5, 'unit' => 'px'],
            'border_radius' => self::radius(12),
            'button_padding' => self::pad(14, 32),
            'button_background_hover_color' => '#1D4ED8',
            'custom_css' => 'selector .elementor-button{background:linear-gradient(135deg,#2563EB,#06B6D4);transition:all .3s;} selector .elementor-button:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(37,99,235,0.35);}',
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
            'typography_font_family' => $f['heading'],
            'typography_font_size' => ['size' => 15, 'unit' => 'px'],
            'typography_font_weight' => '600',
            'typography_letter_spacing' => ['size' => 0.5, 'unit' => 'px'],
            'border_border' => 'solid',
            'border_width' => self::pad(1),
            'border_color' => $c['border'],
            'border_radius' => self::radius(12),
            'button_padding' => self::pad(13, 32),
        ], $extra));
    }

    // ═══════════════════════════════════════════════════════════
    // HOME PAGE
    // ═══════════════════════════════════════════════════════════

    public function buildHomePage(array $c, array $img): array
    {
        $siteName = $c['site_name'] ?? 'Business Name';
        $heroTitle = $c['hero_title'] ?? 'Build Something Amazing Today';
        $heroSub = $c['hero_subtitle'] ?? 'We deliver exceptional digital solutions that help businesses grow faster and smarter with modern technology.';
        $heroCta = $c['hero_cta'] ?? 'Get Started Free';
        $heroCtaUrl = $c['hero_cta_url'] ?? '#services';

        $aboutTitle = $c['about_title'] ?? 'Who We Are';
        $aboutText = $c['about_text'] ?? 'We are a team of passionate innovators building the future of digital business solutions.';
        $aboutText2 = $c['about_text2'] ?? 'With cutting-edge technology and a user-first approach, we help companies transform their ideas into reality.';

        $services = $c['services'] ?? [
            ['icon' => '⚡', 'title' => 'Lightning Fast', 'desc' => 'Blazing performance optimized for speed and reliability across all devices and platforms.'],
            ['icon' => '🎯', 'title' => 'Precision Design', 'desc' => 'Pixel-perfect interfaces crafted with attention to detail and modern design principles.'],
            ['icon' => '📈', 'title' => 'Scale Easily', 'desc' => 'Infrastructure that grows with your business, from startup to enterprise level.'],
        ];

        $benefits = $c['benefits'] ?? [
            ['icon' => '🚀', 'title' => 'Quick Setup', 'desc' => 'Get started in minutes with our streamlined onboarding process.'],
            ['icon' => '🔒', 'title' => 'Secure & Reliable', 'desc' => 'Enterprise-grade security protecting your data around the clock.'],
            ['icon' => '🤝', 'title' => '24/7 Support', 'desc' => 'Our dedicated team is always here to help you succeed.'],
            ['icon' => '💡', 'title' => 'Smart Analytics', 'desc' => 'Real-time insights and reporting to drive informed decisions.'],
        ];

        $testimonials = $c['testimonials'] ?? [
            ['quote' => 'This platform transformed our workflow completely. The results have been incredible since day one.', 'name' => 'Sarah M.', 'role' => 'CEO, TechFlow', 'initials' => 'SM'],
            ['quote' => 'Best investment we made this year. The team is responsive and the product is outstanding.', 'name' => 'James K.', 'role' => 'CTO, ScaleUp', 'initials' => 'JK'],
            ['quote' => 'Intuitive, powerful, and beautifully designed. Everything we needed in one solution.', 'name' => 'Lisa R.', 'role' => 'Founder, Bright Labs', 'initials' => 'LR'],
        ];

        $stats = $c['stats'] ?? [
            ['number' => '500', 'suffix' => '+', 'label' => 'Projects Delivered'],
            ['number' => '98', 'suffix' => '%', 'label' => 'Client Satisfaction'],
            ['number' => '15', 'suffix' => '+', 'label' => 'Years Experience'],
            ['number' => '50', 'suffix' => 'M+', 'label' => 'Users Reached'],
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
            'content_width' => 'full',
            'flex_direction' => 'column',
            'flex_align_items' => 'center',
            'flex_justify_content' => 'center',
            'padding' => self::pad(160, 64, 100, 64),
            'padding_mobile' => self::pad(100, 20, 60, 20),
            'padding_tablet' => self::pad(120, 40, 80, 40),
            'min_height' => ['size' => 100, 'unit' => 'vh', 'sizes' => []],
            'min_height_mobile' => ['size' => 70, 'unit' => 'vh', 'sizes' => []],
            'background_background' => 'classic',
            'background_color' => $this->colors()['bg'],
            '_element_id' => 'hero',
        ], [
            self::html('<div style="text-align:center;margin-bottom:20px;opacity:0;animation:fadeUp .7s ease .2s forwards;"><span class="azure-badge">✨ ' . e($c['hero_eyebrow'] ?? 'Welcome to ' . $siteName) . '</span></div>'),

            $this->headline($heroTitle, 'h1', array_merge(
                self::responsiveSize(72, 52, 36),
                [
                    'align' => 'center',
                    '_margin' => self::margin(0, 0, 24, 0),
                    'custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .35s forwards;max-width:800px;margin-left:auto;margin-right:auto;} selector .elementor-heading-title{background:linear-gradient(135deg,#0F172A 0%,#2563EB 50%,#06B6D4 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}',
                ]
            )),

            self::textEditor('<p style="text-align:center;">' . e($heroSub) . '</p>', [
                'text_color' => $this->colors()['muted'],
                'typography_typography' => 'custom',
                'typography_font_family' => $this->fonts()['body'],
                'typography_font_size' => ['size' => 18, 'unit' => 'px'],
                'typography_line_height' => ['size' => 1.75, 'unit' => 'em'],
                'typography_font_weight' => '400',
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .55s forwards;max-width:580px;margin-left:auto;margin-right:auto;}',
            ]),

            self::container([
                'flex_direction' => 'row',
                'flex_justify_content' => 'center',
                'flex_align_items' => 'center',
                'flex_gap' => ['size' => 14, 'unit' => 'px', 'column' => '14', 'row' => '14'],
                'content_width' => 'full',
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .7s forwards;}',
            ], [
                $this->ctaButton($heroCta, $heroCtaUrl),
                $this->ghostButton($c['hero_ghost_cta'] ?? 'Learn More', '#about'),
            ]),

            // Hero image with floating card effect
            $heroImg ? self::container([
                'content_width' => 'full',
                'flex_align_items' => 'center',
                'padding' => self::pad(60, 0, 0, 0),
                'custom_css' => 'selector{opacity:0;animation:fadeUp 1s ease .9s forwards;max-width:1000px;margin:0 auto;}',
            ], [
                self::image($heroImg, [
                    'custom_css' => 'selector img{width:100%;border-radius:20px;box-shadow:0 24px 80px rgba(37,99,235,0.15);}',
                ]),
            ]) : self::spacer(1),
        ]);

        // ─── STATS BAR ───
        $statElements = [];
        foreach ($stats as $i => $s) {
            $statElements[] = self::container(array_merge([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'padding' => self::pad(40, 20),
                'css_classes' => 'azure-stat sr d' . ($i + 1),
            ], self::rWidth(23, 48, 48)), [
                self::html('<div class="azure-stat-n" data-count="' . $s['number'] . '" data-suffix="' . ($s['suffix'] ?? '') . '">' . $s['number'] . ($s['suffix'] ?? '') . '</div>'),
                self::html('<div class="azure-stat-l">' . e($s['label']) . '</div>'),
            ]);
        }

        $sections[] = self::container([
            'boxed_width' => ['size' => 1200, 'unit' => 'px', 'sizes' => []],
            'flex_direction' => 'row',
            'flex_direction_mobile' => 'row',
            'flex_wrap' => 'wrap',
            'flex_gap' => ['size' => 0, 'unit' => 'px', 'column' => '0', 'row' => '0'],
            'padding' => self::pad(20, 40),
            'padding_mobile' => self::pad(10, 15),
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface'],
            'border_border' => 'solid',
            'border_width' => self::pad(1, 0),
            'border_color' => $this->colors()['border'],
        ], $statElements);

        // ─── ABOUT PREVIEW ───
        $sections[] = self::twoCol(
            // Left: text
            [
                $this->eyebrow($c['about_eyebrow'] ?? 'About Us'),
                $this->headline($aboutTitle, 'h2', ['_margin' => self::margin(0, 0, 16, 0)]),
                $this->bodyText($aboutText),
                $this->bodyText($aboutText2),
                $this->ctaButton($c['about_cta'] ?? 'Learn More', '#about', [
                    '_margin' => self::margin(16, 0, 0, 0),
                ]),
            ],
            // Right: image
            [self::image($aboutImg, [
                'custom_css' => 'selector img{width:100%;min-height:400px;object-fit:cover;border-radius:20px;box-shadow:0 24px 64px rgba(0,0,0,0.08);transition:transform .6s ease;} selector:hover img{transform:scale(1.02);}',
            ])],
            50,
            ['padding' => self::pad(100, 64), '_element_id' => 'about'],
            ['flex_justify_content' => 'center', 'css_classes' => 'sr'],
            ['css_classes' => 'sr d2']
        );

        // ─── SERVICES ───
        $serviceCards = [];
        foreach ($services as $i => $svc) {
            $serviceCards[] = self::container(array_merge([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'padding' => self::pad(44, 32),
                'border_radius' => self::radius(16),
                'css_classes' => 'azure-fcard sr d' . ($i + 1),
            ], self::rWidth(31, 48, 100)), [
                self::html('<div class="azure-icon">' . ($svc['icon'] ?? '⚡') . '</div>'),
                self::heading($svc['title'], 'h3', [
                    'title_color' => $this->colors()['text'],
                    'align' => 'center',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['heading'],
                    'typography_font_size' => ['size' => 22, 'unit' => 'px'],
                    'typography_font_weight' => '700',
                    '_margin' => self::margin(0, 0, 10, 0),
                ]),
                $this->bodyText($svc['desc'], [
                    'align' => 'center',
                    'typography_font_size' => ['size' => 15, 'unit' => 'px'],
                ]),
            ]);
        }

        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface2'],
            'flex_align_items' => 'center',
        ], [
            $this->eyebrow($c['services_eyebrow'] ?? 'Our Services'),
            $this->headline($c['services_title'] ?? 'Everything You Need', 'h2', ['align' => 'center']),
            $this->bodyText($c['services_subtitle'] ?? 'Powerful features designed to help your business thrive.', [
                'align' => 'center',
                '_margin' => self::margin(0),
                'custom_css' => 'selector{max-width:560px;margin-left:auto;margin-right:auto;}',
            ]),
            self::container([
                'content_width' => 'full',
                'flex_direction' => 'row',
                'flex_direction_mobile' => 'column',
                'flex_wrap' => 'wrap',
                'flex_gap' => ['size' => 24, 'unit' => 'px', 'column' => '24', 'row' => '24'],
                '_margin' => self::margin(56, 0, 0, 0),
            ], $serviceCards),
        ], 'services');

        // ─── BENEFITS ───
        $benefitCards = [];
        foreach ($benefits as $i => $b) {
            $benefitCards[] = self::container(array_merge([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'padding' => self::pad(36, 24),
                'border_radius' => self::radius(16),
                'css_classes' => 'azure-bcard sr d' . ($i + 1),
            ], self::rWidth(23, 48, 100)), [
                self::html('<div class="azure-icon">' . ($b['icon'] ?? '⚡') . '</div>'),
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
            'flex_align_items' => 'center',
        ], [
            $this->eyebrow($c['benefits_eyebrow'] ?? 'Why Choose Us'),
            $this->headline($c['benefits_title'] ?? 'Built for Growth', 'h2', ['align' => 'center']),
            self::container([
                'content_width' => 'full',
                'flex_direction' => 'row',
                'flex_direction_mobile' => 'column',
                'flex_wrap' => 'wrap',
                'flex_gap' => ['size' => 24, 'unit' => 'px', 'column' => '24', 'row' => '24'],
                '_margin' => self::margin(56, 0, 0, 0),
            ], $benefitCards),
        ], 'benefits');

        // ─── TESTIMONIALS ───
        $featTest = $testimonials[0] ?? $testimonials[array_key_first($testimonials)];
        $sideTests = array_slice($testimonials, 1, 2);

        $featCard = self::container([
            'content_width' => 'full',
            'flex_direction' => 'column',
            'flex_justify_content' => 'space-between',
            'padding' => self::pad(44),
            'border_radius' => self::radius(16),
            'css_classes' => 'azure-tcard azure-tcard-feat sr',
        ], [
            self::container(['content_width' => 'full', 'flex_direction' => 'column'], [
                self::html('<div class="azure-stars">★★★★★</div>'),
                self::textEditor('"' . e($featTest['quote']) . '"', [
                    'text_color' => 'rgba(255,255,255,0.9)',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['body'],
                    'typography_font_size' => ['size' => 18, 'unit' => 'px'],
                    'typography_line_height' => ['size' => 1.85, 'unit' => 'em'],
                    '_margin' => self::margin(0, 0, 28, 0),
                ]),
            ]),
            self::html('<div style="display:flex;align-items:center;gap:14px;"><div style="width:48px;height:48px;border-radius:50%;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;font-family:\'Plus Jakarta Sans\',sans-serif;font-size:18px;font-weight:800;color:#FFF;">' . e($featTest['initials'] ?? 'AB') . '</div><div><h5 style="font-family:\'Plus Jakarta Sans\',sans-serif;font-size:16px;font-weight:700;color:#FFF;margin:0;">' . e($featTest['name']) . '</h5><span style="font-size:13px;color:rgba(255,255,255,.65);">' . e($featTest['role']) . '</span></div></div>'),
        ]);

        $sideCards = [];
        foreach ($sideTests as $i => $t) {
            $sideCards[] = self::container([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'padding' => self::pad(36),
                'border_radius' => self::radius(16),
                'css_classes' => 'azure-tcard sr d' . ($i + 1),
            ], [
                self::html('<div class="azure-stars">★★★★★</div>'),
                self::textEditor('"' . e($t['quote']) . '"', [
                    'text_color' => $this->colors()['text'],
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['body'],
                    'typography_font_size' => ['size' => 15, 'unit' => 'px'],
                    'typography_line_height' => ['size' => 1.85, 'unit' => 'em'],
                    '_margin' => self::margin(0, 0, 20, 0),
                ]),
                self::html('<div style="display:flex;align-items:center;gap:12px;"><div style="width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,rgba(37,99,235,0.1),rgba(6,182,212,0.1));display:flex;align-items:center;justify-content:center;font-family:\'Plus Jakarta Sans\',sans-serif;font-size:16px;font-weight:800;color:#2563EB;">' . e($t['initials'] ?? 'AB') . '</div><div><h5 style="font-family:\'Plus Jakarta Sans\',sans-serif;font-size:15px;font-weight:700;color:#0F172A;margin:0;">' . e($t['name']) . '</h5><span style="font-size:12px;color:rgba(15,23,42,0.55);">' . e($t['role']) . '</span></div></div>'),
            ]);
        }

        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface2'],
            'flex_align_items' => 'center',
        ], [
            $this->eyebrow($c['testimonials_eyebrow'] ?? 'Testimonials'),
            $this->headline($c['testimonials_title'] ?? 'Loved by Teams', 'h2', [
                'align' => 'center',
                '_margin' => self::margin(0, 0, 50, 0),
            ]),
            self::container([
                'content_width' => 'full',
                'flex_direction' => 'row',
                'flex_direction_mobile' => 'column',
                'flex_wrap' => 'wrap',
                'flex_gap' => ['size' => 24, 'unit' => 'px', 'column' => '24', 'row' => '24'],
                'flex_align_items' => 'stretch',
            ], [
                self::container(array_merge([
                    'content_width' => 'full',
                ], self::rWidth(53, 55, 100)), [$featCard]),
                self::container(array_merge([
                    'content_width' => 'full',
                    'flex_direction' => 'column',
                    'flex_gap' => ['size' => 20, 'unit' => 'px', 'column' => '20', 'row' => '20'],
                ], self::rWidth(43, 45, 100)), $sideCards),
            ]),
        ], 'testimonials');

        // ─── GRADIENT CTA ───
        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'column',
            'flex_align_items' => 'center',
            'flex_justify_content' => 'center',
            'padding' => self::pad(100, 64),
            'custom_css' => 'selector{background:linear-gradient(135deg,#2563EB 0%,#06B6D4 100%);}',
            '_element_id' => 'cta',
        ], [
            self::html('<div style="text-align:center;margin-bottom:16px;"><span class="azure-badge" style="background:rgba(255,255,255,0.15);border-color:rgba(255,255,255,0.25);color:#FFF;">' . e($c['cta_eyebrow'] ?? 'Ready to Start?') . '</span></div>'),
            $this->headline($c['cta_title'] ?? 'Start Building Today', 'h2', array_merge(
                self::responsiveSize(56, 42, 32),
                ['align' => 'center', 'title_color' => '#FFFFFF', '_margin' => self::margin(0, 0, 20, 0)]
            )),
            $this->bodyText($c['cta_text'] ?? 'Join thousands of businesses already growing with our platform.', [
                'align' => 'center',
                'text_color' => 'rgba(255,255,255,0.8)',
                'typography_font_size' => ['size' => 18, 'unit' => 'px'],
                'custom_css' => 'selector{max-width:540px;margin-left:auto;margin-right:auto;}',
                '_margin' => self::margin(0, 0, 40, 0),
            ]),
            self::container([
                'flex_direction' => 'row',
                'flex_justify_content' => 'center',
                'flex_gap' => ['size' => 14, 'unit' => 'px', 'column' => '14', 'row' => '14'],
                'content_width' => 'full',
                'css_classes' => 'sr d2',
            ], [
                self::button($c['cta_button'] ?? 'Get Started Free', '#contact', [
                    'background_color' => '#FFFFFF',
                    'button_text_color' => '#2563EB',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['heading'],
                    'typography_font_size' => ['size' => 15, 'unit' => 'px'],
                    'typography_font_weight' => '700',
                    'border_radius' => self::radius(12),
                    'button_padding' => self::pad(14, 32),
                    'custom_css' => 'selector .elementor-button:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(0,0,0,0.2);}',
                ]),
                self::button($c['cta_ghost'] ?? 'Contact Sales', '#contact', [
                    'background_color' => 'transparent',
                    'button_text_color' => '#FFFFFF',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['heading'],
                    'typography_font_size' => ['size' => 15, 'unit' => 'px'],
                    'typography_font_weight' => '600',
                    'border_border' => 'solid',
                    'border_width' => self::pad(1),
                    'border_color' => 'rgba(255,255,255,0.3)',
                    'border_radius' => self::radius(12),
                    'button_padding' => self::pad(13, 32),
                ]),
            ]),
        ]);

        // Back to top
        $sections[] = self::html('<a href="#hero" id="azure-btt">↑</a>');

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
            'flex_align_items' => 'center',
            'flex_justify_content' => 'center',
            'padding' => self::pad(160, 64, 100, 64),
            'min_height' => ['size' => 50, 'unit' => 'vh'],
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface'],
        ], [
            $this->eyebrow('About Us'),
            $this->headline($c['about_title'] ?? 'Our Story', 'h1', array_merge(
                self::responsiveSize(64, 48, 36),
                [
                    'align' => 'center',
                    'custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .2s forwards;}',
                ]
            )),
            $this->bodyText($c['about_text'] ?? 'Building the future of digital business, one solution at a time.', [
                'align' => 'center',
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .4s forwards;max-width:560px;margin-left:auto;margin-right:auto;}',
            ]),
        ]);

        // Two-col about
        $sections[] = self::twoCol(
            [self::image($img['about'] ?? '', [
                'custom_css' => 'selector img{width:100%;min-height:400px;object-fit:cover;border-radius:20px;box-shadow:0 24px 64px rgba(0,0,0,0.08);}',
            ])],
            [
                $this->eyebrow('Who We Are'),
                $this->headline($c['about_subtitle'] ?? 'Passion Meets Innovation', 'h2', [
                    'typography_font_size' => ['size' => 42, 'unit' => 'px'],
                ]),
                $this->bodyText($c['about_text'] ?? 'We are a team of passionate innovators and builders.'),
                $this->bodyText($c['about_text2'] ?? 'With years of experience delivering outstanding digital solutions.'),
                $this->ctaButton($c['about_cta'] ?? 'Our Services', '/services/', [
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
            ['number' => '50', 'suffix' => 'M+', 'label' => 'Users'],
        ];
        $statEls = [];
        foreach ($stats as $i => $s) {
            $statEls[] = self::container([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'width' => ['size' => round(100 / count($stats), 2), 'unit' => '%'],
                'padding' => self::pad(48, 20),
                'background_background' => 'classic',
                'background_color' => $this->colors()['surface'],
                'border_radius' => self::radius(16),
                'css_classes' => 'azure-card azure-stat sr d' . ($i + 1),
            ], [
                self::html('<div class="azure-stat-n" data-count="' . $s['number'] . '" data-suffix="' . ($s['suffix'] ?? '') . '">' . $s['number'] . ($s['suffix'] ?? '') . '</div>'),
                self::html('<div class="azure-stat-l">' . e($s['label']) . '</div>'),
            ]);
        }
        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface2'],
        ], [
            self::container([
                'content_width' => 'full',
                'flex_direction' => 'row',
                'flex_wrap' => 'wrap',
                'flex_gap' => ['size' => 24, 'unit' => 'px', 'column' => '24', 'row' => '24'],
            ], $statEls),
        ]);

        // Values section
        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['bg'],
            'flex_align_items' => 'center',
        ], [
            $this->eyebrow('Our Values'),
            $this->headline($c['values_title'] ?? 'What Drives Us', 'h2', ['align' => 'center']),
            $this->bodyText($c['values_text'] ?? 'Core principles that guide everything we do and every decision we make.', [
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
            'flex_align_items' => 'center',
            'flex_justify_content' => 'center',
            'padding' => self::pad(160, 64, 100, 64),
            'min_height' => ['size' => 50, 'unit' => 'vh'],
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface'],
        ], [
            $this->eyebrow('Our Services'),
            $this->headline($c['services_title'] ?? 'What We Offer', 'h1', array_merge(
                self::responsiveSize(64, 48, 36),
                [
                    'align' => 'center',
                    'custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .2s forwards;}',
                ]
            )),
            $this->bodyText($c['services_subtitle'] ?? 'Comprehensive solutions designed to accelerate your growth.', [
                'align' => 'center',
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .4s forwards;max-width:560px;margin-left:auto;margin-right:auto;}',
            ]),
        ]);

        // Service cards
        $services = $c['services'] ?? [
            ['icon' => '⚡', 'title' => 'Strategy', 'desc' => 'Comprehensive strategic planning for sustainable growth.'],
            ['icon' => '🎯', 'title' => 'Design', 'desc' => 'Beautiful, user-centered design that converts.'],
            ['icon' => '📈', 'title' => 'Growth', 'desc' => 'Data-driven marketing and growth strategies.'],
        ];

        $cards = [];
        foreach ($services as $i => $svc) {
            $cards[] = self::container([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'padding' => self::pad(44, 32),
                'border_radius' => self::radius(16),
                'css_classes' => 'azure-fcard sr d' . min($i + 1, 4),
            ], [
                self::html('<div class="azure-icon">' . ($svc['icon'] ?? '⚡') . '</div>'),
                self::heading($svc['title'], 'h3', [
                    'title_color' => $this->colors()['text'],
                    'align' => 'center',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['heading'],
                    'typography_font_size' => ['size' => 24, 'unit' => 'px'],
                    'typography_font_weight' => '700',
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
                'flex_gap' => ['size' => 24, 'unit' => 'px', 'column' => '24', 'row' => '24'],
            ]),
        ], 'services-grid');

        // Gradient CTA
        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'column',
            'flex_align_items' => 'center',
            'padding' => self::pad(80, 64),
            'custom_css' => 'selector{background:linear-gradient(135deg,#2563EB 0%,#06B6D4 100%);}',
        ], [
            $this->headline($c['cta_title'] ?? 'Ready to Get Started?', 'h2', [
                'title_color' => '#FFFFFF',
                'align' => 'center',
            ]),
            $this->bodyText($c['cta_text'] ?? 'Contact us today and let us help you build something amazing.', [
                'align' => 'center',
                'text_color' => 'rgba(255,255,255,0.8)',
                '_margin' => self::margin(0, 0, 30, 0),
            ]),
            self::button($c['cta_button'] ?? 'Contact Us', '/contact/', [
                'background_color' => '#FFFFFF',
                'button_text_color' => '#2563EB',
                'typography_typography' => 'custom',
                'typography_font_family' => $this->fonts()['heading'],
                'typography_font_size' => ['size' => 15, 'unit' => 'px'],
                'typography_font_weight' => '700',
                'border_radius' => self::radius(12),
                'button_padding' => self::pad(14, 32),
                'custom_css' => 'selector .elementor-button:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(0,0,0,0.2);}',
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
            'flex_align_items' => 'center',
            'flex_justify_content' => 'center',
            'padding' => self::pad(160, 64, 100, 64),
            'min_height' => ['size' => 50, 'unit' => 'vh'],
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface'],
        ], [
            $this->eyebrow('Our Work'),
            $this->headline($c['portfolio_title'] ?? 'Featured Projects', 'h1', array_merge(
                self::responsiveSize(64, 48, 36),
                [
                    'align' => 'center',
                    'custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .2s forwards;}',
                ]
            )),
            $this->bodyText($c['portfolio_subtitle'] ?? 'A showcase of our best work and successful partnerships.', [
                'align' => 'center',
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .4s forwards;max-width:560px;margin-left:auto;margin-right:auto;}',
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
                'width' => ['size' => 31, 'unit' => '%'],
                'css_classes' => 'azure-photo sr d' . min($i + 1, 4),
                'custom_css' => 'selector{min-height:300px;}',
            ], [
                self::image($url, [
                    'custom_css' => 'selector img{width:100%;height:300px;object-fit:cover;border-radius:16px;}',
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
                    'content_width' => 'full',
                    'flex_gap' => ['size' => 24, 'unit' => 'px', 'column' => '24', 'row' => '24'],
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
            'flex_align_items' => 'center',
            'flex_justify_content' => 'center',
            'padding' => self::pad(160, 64, 100, 64),
            'min_height' => ['size' => 50, 'unit' => 'vh'],
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface'],
        ], [
            $this->eyebrow('Contact'),
            $this->headline($c['contact_title'] ?? 'Get In Touch', 'h1', array_merge(
                self::responsiveSize(64, 48, 36),
                [
                    'align' => 'center',
                    'custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .2s forwards;}',
                ]
            )),
            $this->bodyText($c['contact_subtitle'] ?? 'We would love to hear from you. Send us a message and we will respond promptly.', [
                'align' => 'center',
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .4s forwards;max-width:500px;margin-left:auto;margin-right:auto;}',
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
                'width' => ['size' => 31, 'unit' => '%'],
                'padding' => self::pad(40, 28),
                'border_radius' => self::radius(16),
                'css_classes' => 'azure-fcard sr d' . ($i + 1),
            ], [
                self::html('<div class="azure-icon">' . $info['icon'] . '</div>'),
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
            $this->headline('Drop Us a Line', 'h2', [
                'align' => 'center',
                'typography_font_size' => ['size' => 42, 'unit' => 'px'],
                '_margin' => self::margin(0, 0, 40, 0),
            ]),
            self::html('<form style="max-width:600px;width:100%;margin:0 auto;display:flex;flex-direction:column;gap:16px;">
<input type="text" placeholder="Your Name" style="padding:14px 20px;background:' . $this->colors()['surface2'] . ';border:1px solid ' . $this->colors()['border'] . ';color:' . $this->colors()['text'] . ';font-family:\'' . $this->fonts()['body'] . '\',sans-serif;font-size:14px;border-radius:12px;outline:none;">
<input type="email" placeholder="Your Email" style="padding:14px 20px;background:' . $this->colors()['surface2'] . ';border:1px solid ' . $this->colors()['border'] . ';color:' . $this->colors()['text'] . ';font-family:\'' . $this->fonts()['body'] . '\',sans-serif;font-size:14px;border-radius:12px;outline:none;">
<textarea rows="5" placeholder="Your Message" style="padding:14px 20px;background:' . $this->colors()['surface2'] . ';border:1px solid ' . $this->colors()['border'] . ';color:' . $this->colors()['text'] . ';font-family:\'' . $this->fonts()['body'] . '\',sans-serif;font-size:14px;border-radius:12px;outline:none;resize:vertical;"></textarea>
<button type="submit" style="padding:14px 32px;background:linear-gradient(135deg,#2563EB,#06B6D4);color:#FFF;font-family:\'' . $this->fonts()['heading'] . '\',sans-serif;font-size:15px;font-weight:600;border:none;border-radius:12px;cursor:pointer;transition:all .3s;">Send Message</button>
</form>'),
        ]);

        return $sections;
    }

    // ═══════════════════════════════════════════════════════════
    // HEADER (HFE) — Modern SaaS with announcement bar
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
<!-- Announcement Bar -->
<div class="azure-announce" id="azureAnnounce" style="background:linear-gradient(135deg,#2563EB 0%,#06B6D4 100%);height:38px;display:flex;align-items:center;justify-content:center;position:fixed;top:0;left:0;right:0;z-index:1001;transition:transform .35s cubic-bezier(.4,0,.2,1),opacity .35s;">
  <a href="/services/" style="font-family:\'' . $f['body'] . '\',sans-serif;font-size:13px;font-weight:500;color:#FFF;text-decoration:none;letter-spacing:0.2px;display:flex;align-items:center;gap:6px;">
    <span style="font-size:14px;">🚀</span> New: AI-Powered Solutions <span style="opacity:.7;margin-left:2px;">— Learn More →</span>
  </a>
  <button id="azureAnnounceClose" style="position:absolute;right:20px;top:50%;transform:translateY(-50%);background:rgba(255,255,255,0.15);border:none;color:#FFF;width:22px;height:22px;border-radius:50%;font-size:12px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .2s;line-height:1;padding:0;" aria-label="Close">✕</button>
</div>

<!-- Main Nav -->
<nav class="azure-nav" id="mainNav" style="position:fixed;top:38px;left:0;right:0;z-index:1000;padding:0 56px;height:72px;display:flex;align-items:center;justify-content:space-between;transition:all .4s cubic-bezier(.4,0,.2,1);background:rgba(255,255,255,0.8);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border-bottom:1px solid ' . $c['border'] . ';">
  <a href="/" class="azure-nav-logo" style="font-family:\'' . $f['heading'] . '\',sans-serif;font-size:21px;font-weight:800;text-decoration:none;display:flex;align-items:center;gap:8px;">
    <span style="background:linear-gradient(135deg,#2563EB,#06B6D4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">' . e($siteName) . '</span>
  </a>
  <ul class="azure-nav-links" style="display:flex;gap:0;list-style:none;position:absolute;left:50%;transform:translateX(-50%);padding:0;margin:0;">' . $navLinks . '</ul>
  <a href="/contact/" class="azure-nav-cta" style="padding:10px 28px;background:linear-gradient(135deg,#2563EB,#06B6D4);color:#FFF;font-family:\'' . $f['heading'] . '\',sans-serif;font-size:13px;font-weight:600;border-radius:50px;text-decoration:none;transition:all .35s;box-shadow:0 4px 16px rgba(37,99,235,0.3);letter-spacing:0.3px;">Start Free Trial</a>
  <button class="azure-nav-toggle" id="azureMobileToggle" style="display:none;background:none;border:1px solid ' . $c['border'] . ';border-radius:10px;width:40px;height:40px;cursor:pointer;align-items:center;justify-content:center;font-size:18px;color:' . $c['text'] . ';transition:all .2s;">☰</button>
</nav>

<!-- Mobile Menu Overlay -->
<div class="azure-mobile-menu" id="azureMobileMenu" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;z-index:1002;background:rgba(255,255,255,0.98);backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px);padding:0;opacity:0;transition:opacity .3s;">
  <div style="display:flex;align-items:center;justify-content:space-between;padding:0 28px;height:72px;border-bottom:1px solid ' . $c['border'] . ';">
    <a href="/" style="font-family:\'' . $f['heading'] . '\',sans-serif;font-size:20px;font-weight:800;text-decoration:none;"><span style="background:linear-gradient(135deg,#2563EB,#06B6D4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">' . e($siteName) . '</span></a>
    <button id="azureMobileClose" style="background:none;border:1px solid ' . $c['border'] . ';border-radius:10px;width:40px;height:40px;cursor:pointer;font-size:16px;color:' . $c['text'] . ';display:flex;align-items:center;justify-content:center;">✕</button>
  </div>
  <ul style="list-style:none;padding:24px 28px;margin:0;display:flex;flex-direction:column;gap:0;">' . str_replace(
            '<li><a href=',
            '<li><a class="azure-mobile-link" style="display:block;padding:16px 0;font-family:\'' . $f['body'] . '\',sans-serif;font-size:17px;font-weight:500;color:' . $c['text'] . ';text-decoration:none;border-bottom:1px solid ' . $c['border'] . ';transition:color .2s;" href=',
            $navLinks
        ) . '</ul>
  <div style="padding:24px 28px;">
    <a href="/contact/" style="display:block;text-align:center;padding:14px 28px;background:linear-gradient(135deg,#2563EB,#06B6D4);color:#FFF;font-family:\'' . $f['heading'] . '\',sans-serif;font-size:15px;font-weight:600;border-radius:50px;text-decoration:none;box-shadow:0 4px 16px rgba(37,99,235,0.3);">Start Free Trial</a>
  </div>
</div>

<style>
/* Nav links */
.azure-nav-links a{display:block;padding:8px 18px;font-size:14px;font-weight:500;color:' . $c['muted'] . ';text-decoration:none;transition:color .3s;position:relative;}
.azure-nav-links a::after{content:\'\';position:absolute;bottom:2px;left:18px;right:18px;height:2px;background:linear-gradient(135deg,#2563EB,#06B6D4);border-radius:2px;transform:scaleX(0);transform-origin:center;transition:transform .3s;}
.azure-nav-links a:hover{color:' . $c['text'] . ';}
.azure-nav-links a:hover::after{transform:scaleX(1);}
/* Nav scroll states */
.azure-nav.scrolled{background:rgba(255,255,255,0.95)!important;box-shadow:0 2px 20px rgba(0,0,0,0.06);}
.azure-nav.bar-hidden{top:0!important;}
.azure-nav-cta:hover{box-shadow:0 8px 24px rgba(37,99,235,0.4)!important;transform:translateY(-1px);}
/* Announcement bar hidden */
.azure-announce.hidden{transform:translateY(-100%);opacity:0;pointer-events:none;}
/* Mobile responsive */
@media(max-width:1024px){
  .azure-nav-links{display:none!important;}
  .azure-nav-cta{display:none!important;}
  .azure-nav-toggle{display:flex!important;}
  .azure-nav{padding:0 28px!important;height:64px!important;}
  .azure-announce{font-size:12px;}
}
</style>

<script>
(function(){
  var bar=document.getElementById("azureAnnounce");
  var nav=document.getElementById("mainNav");
  var closeBtn=document.getElementById("azureAnnounceClose");
  var mobileToggle=document.getElementById("azureMobileToggle");
  var mobileMenu=document.getElementById("azureMobileMenu");
  var mobileClose=document.getElementById("azureMobileClose");
  var barHidden=false;

  /* Close announcement bar */
  if(closeBtn){closeBtn.addEventListener("click",function(e){e.preventDefault();bar.classList.add("hidden");nav.classList.add("bar-hidden");barHidden=true;});}

  /* Scroll: hide bar + frosted glass more opaque */
  window.addEventListener("scroll",function(){
    var s=window.scrollY||document.documentElement.scrollTop;
    nav.classList.toggle("scrolled",s>60);
    if(s>120&&!barHidden){bar.classList.add("hidden");nav.classList.add("bar-hidden");barHidden=true;}
  });

  /* Mobile menu */
  if(mobileToggle){mobileToggle.addEventListener("click",function(){mobileMenu.style.display="block";setTimeout(function(){mobileMenu.style.opacity="1";},10);document.body.style.overflow="hidden";});}
  function closeMobile(){mobileMenu.style.opacity="0";setTimeout(function(){mobileMenu.style.display="none";},300);document.body.style.overflow="";}
  if(mobileClose){mobileClose.addEventListener("click",closeMobile);}
  var mobileLinks=mobileMenu?mobileMenu.querySelectorAll("a"):[];
  for(var i=0;i<mobileLinks.length;i++){mobileLinks[i].addEventListener("click",closeMobile);}
})();
</script>')];
    }

    // ═══════════════════════════════════════════════════════════
    // FOOTER (HFE) — Modern SaaS footer with gradient CTA
    // ═══════════════════════════════════════════════════════════

    public function buildFooter(string $siteName, array $pages, array $contact): array
    {
        $c = $this->colors();
        $f = $this->fonts();
        $year = date('Y');

        $email = $contact['email'] ?? 'hello@example.com';
        $phone = $contact['phone'] ?? '';
        $address = $contact['address'] ?? '';
        $footerText = $contact['footer_text'] ?? 'Building the future of digital business with modern, AI-powered solutions.';

        $sections = [];

        // ── Gradient CTA Section ─────────────────────────────
        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'column',
            'flex_align_items' => 'center',
            'padding' => self::pad(80, 40),
            'padding_mobile' => self::pad(50, 24),
            'background_background' => 'gradient',
            'background_color' => $c['primary'],
            'background_color_b' => $c['accent'],
            'background_gradient_type' => 'linear',
            'background_gradient_angle' => ['size' => 135, 'unit' => 'deg', 'sizes' => []],
        ], [
            self::container([
                'content_width' => 'boxed',
                'boxed_width' => ['size' => 720, 'unit' => 'px', 'sizes' => []],
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'padding' => self::pad(0),
            ], [
                self::heading('Ready to Transform Your Business?', 'h2', [
                    'align' => 'center',
                    'title_color' => '#FFFFFF',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $f['heading'],
                    'typography_font_weight' => '800',
                    'typography_font_size' => ['size' => 36, 'unit' => 'px', 'sizes' => []],
                    'typography_font_size_tablet' => ['size' => 30, 'unit' => 'px', 'sizes' => []],
                    'typography_font_size_mobile' => ['size' => 24, 'unit' => 'px', 'sizes' => []],
                    'typography_line_height' => ['size' => 1.2, 'unit' => 'em', 'sizes' => []],
                ]),
                self::spacer(12),
                self::textEditor('<p style="text-align:center;font-size:16px;color:rgba(255,255,255,0.8);max-width:520px;margin:0 auto;line-height:1.7;">Join thousands of businesses already using our platform to grow faster, work smarter, and achieve more.</p>'),
                self::spacer(28),
                self::button('Get Started Free', '/contact/', [
                    'button_type' => 'default',
                    'background_color' => '#FFFFFF',
                    'button_text_color' => $c['primary'],
                    'typography_typography' => 'custom',
                    'typography_font_family' => $f['heading'],
                    'typography_font_weight' => '700',
                    'typography_font_size' => ['size' => 15, 'unit' => 'px', 'sizes' => []],
                    'border_radius' => self::radius(50),
                    'button_padding' => self::pad(14, 40, 14, 40),
                    'button_box_shadow_box_shadow_type' => 'yes',
                    'button_box_shadow_box_shadow' => [
                        'horizontal' => 0, 'vertical' => 8, 'blur' => 28, 'spread' => 0,
                        'color' => 'rgba(0,0,0,0.15)',
                    ],
                    'hover_color' => '#FFFFFF',
                    'button_background_hover_color' => $c['text'],
                ]),
            ]),
        ]);

        // ── Main Footer ──────────────────────────────────────
        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'column',
            'padding' => self::pad(0),
            'background_background' => 'classic',
            'background_color' => $c['surface'],
        ], [
            // 4-Column Grid
            self::container([
                'flex_direction' => 'row',
                'content_width' => 'full',
                'flex_gap' => ['size' => 48, 'unit' => 'px', 'column' => '48', 'row' => '48'],
                'padding' => self::pad(72, 64, 56, 64),
                'padding_mobile' => self::pad(48, 24, 36, 24),
                'border_border' => 'solid',
                'border_width' => self::pad(0, 0, 1, 0),
                'border_color' => $c['border'],
                'custom_css' => 'selector{display:grid;grid-template-columns:1.6fr 1fr 1fr 1fr;}@media(max-width:1024px){selector{grid-template-columns:1fr 1fr;}}@media(max-width:640px){selector{grid-template-columns:1fr;}}',
            ], [
                // Col 1: Brand + description + social pills
                self::html('<div style="max-width:300px;">
  <div style="font-family:\'' . $f['heading'] . '\',sans-serif;font-size:22px;font-weight:800;margin-bottom:16px;">
    <span style="background:linear-gradient(135deg,#2563EB,#06B6D4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">' . e($siteName) . '</span>
  </div>
  <p style="font-size:14px;color:' . $c['muted'] . ';line-height:1.8;margin:0 0 24px 0;">' . e($footerText) . '</p>
  <div class="azure-ftr-social">
    <a href="#" class="azure-social-pill" aria-label="Twitter"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>
    <a href="#" class="azure-social-pill" aria-label="LinkedIn"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20.5 2h-17A1.5 1.5 0 002 3.5v17A1.5 1.5 0 003.5 22h17a1.5 1.5 0 001.5-1.5v-17A1.5 1.5 0 0020.5 2zM8 19H5v-9h3zM6.5 8.25A1.75 1.75 0 118.3 6.5a1.78 1.78 0 01-1.8 1.75zM19 19h-3v-4.74c0-1.42-.6-1.93-1.38-1.93A1.74 1.74 0 0013 14.19a.66.66 0 000 .14V19h-3v-9h2.9v1.3a3.11 3.11 0 012.7-1.4c1.55 0 3.36.86 3.36 3.66z"/></svg></a>
    <a href="#" class="azure-social-pill" aria-label="GitHub"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg></a>
    <a href="#" class="azure-social-pill" aria-label="Instagram"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg></a>
  </div>
</div>'),
                // Col 2: Product
                self::html('<div class="azure-fc"><h4 style="font-family:\'' . $f['heading'] . '\',sans-serif;font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:1.2px;color:' . $c['text'] . ';margin-bottom:22px;">Product</h4><ul><li><a href="/services/">Features</a></li><li><a href="/services/">Pricing</a></li><li><a href="/services/">Integrations</a></li><li><a href="/services/">API</a></li></ul></div>'),
                // Col 3: Resources
                self::html('<div class="azure-fc"><h4 style="font-family:\'' . $f['heading'] . '\',sans-serif;font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:1.2px;color:' . $c['text'] . ';margin-bottom:22px;">Resources</h4><ul><li><a href="/blog/">Blog</a></li><li><a href="/services/">Documentation</a></li><li><a href="/contact/">Support</a></li><li><a href="/blog/">Changelog</a></li></ul></div>'),
                // Col 4: Company
                self::html('<div class="azure-fc"><h4 style="font-family:\'' . $f['heading'] . '\',sans-serif;font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:1.2px;color:' . $c['text'] . ';margin-bottom:22px;">Company</h4><ul><li><a href="/about/">About</a></li><li><a href="/about/">Careers</a></li><li><a href="/contact/">Contact</a></li><li><a href="#">Legal</a></li></ul></div>'),
            ]),
            // Bottom Bar: copyright + made with love + legal links
            self::container([
                'flex_direction' => 'row',
                'flex_justify_content' => 'space-between',
                'flex_align_items' => 'center',
                'content_width' => 'full',
                'padding' => self::pad(22, 64),
                'padding_mobile' => self::pad(20, 24),
                'custom_css' => '@media(max-width:767px){selector{flex-direction:column!important;gap:12px!important;text-align:center;}}',
            ], [
                self::textEditor('<p style="font-size:13px;color:' . $c['muted'] . ';margin:0;">&copy; ' . $year . ' <span style="background:linear-gradient(135deg,#2563EB,#06B6D4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;font-weight:600;">' . e($siteName) . '</span>. All rights reserved.</p>'),
                self::textEditor('<p style="font-size:13px;color:' . $c['muted'] . ';margin:0;">Made with <span style="color:#EF4444;">&#10084;</span> by <span style="font-weight:600;color:' . $c['text'] . ';">' . e($siteName) . '</span></p>'),
                self::html('<div class="azure-ftr-legal"><a href="#">Privacy Policy</a><span style="color:' . $c['border'] . ';margin:0 12px;">|</span><a href="#">Terms of Service</a></div>'),
            ]),
        ]);

        // Footer-specific styles
        $sections[] = self::html('<style>
/* Social pill buttons */
.azure-ftr-social{display:flex;gap:10px;flex-wrap:wrap;}
.azure-social-pill{display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:50px;background:' . $c['surface2'] . ';border:1px solid ' . $c['border'] . ';color:' . $c['muted'] . ';text-decoration:none;transition:all .3s ease;}
.azure-social-pill:hover{background:linear-gradient(135deg,#2563EB,#06B6D4);color:#FFF!important;border-color:transparent;transform:translateY(-2px);box-shadow:0 6px 20px rgba(37,99,235,0.25);}
.azure-social-pill svg{transition:color .3s;}
/* Footer legal links */
.azure-ftr-legal a{font-size:13px;color:' . $c['muted'] . ';text-decoration:none;transition:color .3s;}
.azure-ftr-legal a:hover{color:' . $c['primary'] . ';}
/* Footer column hover underlines */
.azure-fc a{position:relative;}
.azure-fc a::after{content:\'\';position:absolute;bottom:-2px;left:0;width:0;height:1.5px;background:linear-gradient(135deg,#2563EB,#06B6D4);border-radius:1px;transition:width .3s;}
.azure-fc a:hover::after{width:100%;}
</style>');

        return $sections;
    }
}
