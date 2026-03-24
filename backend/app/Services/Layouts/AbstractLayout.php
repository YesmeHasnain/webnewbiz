<?php

namespace App\Services\Layouts;

/**
 * Base class for premium Elementor layouts.
 *
 * Each layout is a complete design system: global CSS/JS, header, footer,
 * and 5 page builders (home, about, services, portfolio, contact).
 * Uses only native Elementor Free widgets: heading, text-editor, image,
 * button, icon, spacer, divider, html.
 */
abstract class AbstractLayout
{
    // ─── Abstract: each layout defines its own design system ───

    /** CSS variable colors: primary, secondary, accent, bg, surface, text, muted, border */
    abstract public function colors(): array;

    /** Font families: ['heading' => '...', 'body' => '...'] */
    abstract public function fonts(): array;

    /** Layout metadata */
    abstract public function slug(): string;
    abstract public function name(): string;
    abstract public function description(): string;
    abstract public function bestFor(): array;

    /** Is this a dark theme? (affects header/footer contrast) */
    public function isDark(): bool { return false; }

    /** Is this a theme-based layout? (installs actual WP theme instead of generating Elementor JSON) */
    public function isThemeBased(): bool { return false; }

    /** WP theme slug for theme-based layouts */
    public function themeSlug(): string { return ''; }

    // ─── Page builders ───

    abstract public function buildHomePage(array $content, array $images): array;
    abstract public function buildAboutPage(array $content, array $images): array;
    abstract public function buildServicesPage(array $content, array $images): array;
    abstract public function buildPortfolioPage(array $content, array $images): array;
    abstract public function buildContactPage(array $content, array $images): array;

    /** Global CSS injected as first element on every page */
    abstract public function buildGlobalCss(): string;

    /** Global JS injected as last element on every page */
    abstract public function buildGlobalJs(): string;

    // ─── Header / Footer (HFE) ───

    abstract public function buildHeader(string $siteName, array $pages): array;
    abstract public function buildFooter(string $siteName, array $pages, array $contact): array;

    // ─── Build a complete page with CSS/JS wrapper ───

    public function buildPage(string $pageType, array $content, array $images): array
    {
        $method = 'build' . ucfirst($pageType) . 'Page';
        if (!method_exists($this, $method)) {
            $method = 'buildHomePage';
        }

        // Normalize AI content structure to flat keys that layouts expect
        $content = self::normalizeContent($content);

        $elements = $this->$method($content, $images);

        // CSS/JS as top-level containers wrapping the html widgets
        $cssSection = self::container([
            'content_width' => 'full',
            'flex_direction' => 'column',
            'padding' => self::pad(0),
        ], [self::html($this->buildGlobalCss())]);

        $jsSection = self::container([
            'content_width' => 'full',
            'flex_direction' => 'column',
            'padding' => self::pad(0),
        ], [self::html($this->buildGlobalJs())]);

        return array_merge(
            [$cssSection],
            $elements,
            [$jsSection]
        );
    }

    // ═══════════════════════════════════════════════════════════════
    // CONTENT NORMALIZER — maps AI/fallback content to flat keys
    // ═══════════════════════════════════════════════════════════════

    /**
     * Normalize AI-generated content structure to the flat key format
     * that layout buildXxxPage() methods expect.
     *
     * AI content comes as:
     *   site_title, pages.home.hero_title, pages.home.sections[{type,items}]
     *
     * Layouts expect:
     *   site_name, hero_title, services[], benefits[], testimonials[], stats[]
     */
    public static function normalizeContent(array $c): array
    {
        // Map site_title → site_name
        if (!isset($c['site_name']) && isset($c['site_title'])) {
            $c['site_name'] = $c['site_title'];
        }

        // Parse 'sections' array into flat keys
        $sections = $c['sections'] ?? [];
        foreach ($sections as $section) {
            $type = $section['type'] ?? '';
            $items = $section['items'] ?? [];
            $title = $section['title'] ?? '';

            switch ($type) {
                case 'features':
                    if (!isset($c['services_title']) && $title) {
                        $c['services_title'] = $title;
                    }
                    if (!isset($c['services']) && !empty($items)) {
                        $c['services'] = array_map(function ($item) {
                            return [
                                'icon' => $item['icon'] ?? '⭐',
                                'title' => $item['title'] ?? '',
                                'desc' => $item['description'] ?? $item['desc'] ?? '',
                            ];
                        }, $items);
                    }
                    break;

                case 'stats':
                    if (!isset($c['stats']) && !empty($items)) {
                        $c['stats'] = array_map(function ($item) {
                            $num = $item['title'] ?? $item['number'] ?? '0';
                            $suffix = '';
                            if (preg_match('/^([\d,]+)(\D+)$/', $num, $m)) {
                                $num = $m[1];
                                $suffix = $m[2];
                            }
                            return [
                                'number' => str_replace(',', '', $num),
                                'suffix' => $suffix,
                                'label' => $item['description'] ?? $item['label'] ?? '',
                            ];
                        }, $items);
                    }
                    break;

                case 'about_preview':
                    if (!isset($c['about_title']) && $title) {
                        $c['about_title'] = $title;
                    }
                    if (!isset($c['about_text']) && !empty($section['content'])) {
                        $c['about_text'] = $section['content'];
                    }
                    break;

                case 'testimonials':
                    if (!isset($c['testimonials_title']) && $title) {
                        $c['testimonials_title'] = $title;
                    }
                    if (!isset($c['testimonials']) && !empty($items)) {
                        $c['testimonials'] = array_map(function ($item) {
                            $name = $item['name'] ?? 'Client';
                            $initials = '';
                            foreach (explode(' ', $name) as $word) {
                                $initials .= mb_strtoupper(mb_substr($word, 0, 1));
                            }
                            return [
                                'quote' => $item['content'] ?? $item['quote'] ?? '',
                                'name' => $name,
                                'role' => $item['role'] ?? '',
                                'initials' => $initials,
                            ];
                        }, $items);
                    }
                    break;

                case 'cta':
                    if (!isset($c['cta_title']) && $title) {
                        $c['cta_title'] = $title;
                    }
                    if (!isset($c['cta_text']) && !empty($section['subtitle'])) {
                        $c['cta_text'] = $section['subtitle'];
                    }
                    if (!isset($c['cta_button']) && !empty($section['button_text'])) {
                        $c['cta_button'] = $section['button_text'];
                    }
                    break;
            }
        }

        // Map about page content (AI provides nested about.content, about.mission, about.vision)
        if (!isset($c['about_text']) && isset($c['content'])) {
            $c['about_text'] = $c['content'];
        }
        if (!isset($c['about_text2']) && isset($c['mission'])) {
            $c['about_text2'] = $c['mission'];
        }

        // Map services page (AI provides services.items)
        if (!isset($c['services']) && isset($c['items'])) {
            $c['services'] = array_map(function ($item) {
                return [
                    'icon' => $item['icon'] ?? '⭐',
                    'title' => $item['title'] ?? '',
                    'desc' => $item['description'] ?? $item['desc'] ?? '',
                ];
            }, $c['items']);
        }

        // Map contact page
        if (!isset($c['contact_address']) && isset($c['address'])) {
            $c['contact_address'] = $c['address'];
        }
        if (!isset($c['contact_phone']) && isset($c['phone'])) {
            $c['contact_phone'] = $c['phone'];
        }
        if (!isset($c['contact_email']) && isset($c['email'])) {
            $c['contact_email'] = $c['email'];
        }

        return $c;
    }

    // ═══════════════════════════════════════════════════════════════
    // WIDGET PRIMITIVES — static, shared across all layouts
    // ═══════════════════════════════════════════════════════════════

    /** Generate 7-char hex Elementor ID */
    public static function eid(): string
    {
        return substr(bin2hex(random_bytes(4)), 0, 7);
    }

    /** Container element — uses Elementor's correct 'full' value (not 'full-width') */
    public static function container(array $settings = [], array $elements = []): array
    {
        return [
            'id' => self::eid(),
            'elType' => 'container',
            'settings' => array_merge(['content_width' => 'full'], $settings),
            'elements' => $elements,
        ];
    }

    /** Heading widget */
    public static function heading(string $text, string $tag = 'h2', array $settings = []): array
    {
        return [
            'id' => self::eid(),
            'elType' => 'widget',
            'widgetType' => 'heading',
            'settings' => array_merge([
                'title' => $text,
                'header_size' => $tag,
                'align' => 'center',
            ], $settings),
            'elements' => [],
        ];
    }

    /** Text Editor widget */
    public static function textEditor(string $html, array $settings = []): array
    {
        return [
            'id' => self::eid(),
            'elType' => 'widget',
            'widgetType' => 'text-editor',
            'settings' => array_merge(['editor' => $html], $settings),
            'elements' => [],
        ];
    }

    /** Image widget */
    public static function image(string $url, array $settings = []): array
    {
        return [
            'id' => self::eid(),
            'elType' => 'widget',
            'widgetType' => 'image',
            'settings' => array_merge([
                'image' => ['url' => $url, 'id' => ''],
                'image_size' => 'full',
            ], $settings),
            'elements' => [],
        ];
    }

    /** Button widget — width:auto prevents stretching inside flex containers */
    public static function button(string $text, string $url = '#', array $settings = []): array
    {
        // custom_css forces widget to not stretch in flex row containers
        $flexCss = 'selector{flex-grow:0!important;flex-shrink:0!important;width:auto!important;}';
        $existingCss = $settings['custom_css'] ?? '';
        if ($existingCss) {
            $settings['custom_css'] = $existingCss . ' ' . $flexCss;
        } else {
            $settings['custom_css'] = $flexCss;
        }

        return [
            'id' => self::eid(),
            'elType' => 'widget',
            'widgetType' => 'button',
            'settings' => array_merge([
                'text' => $text,
                'align' => 'center',
                'link' => ['url' => $url, 'is_external' => false, 'nofollow' => false],
            ], $settings),
            'elements' => [],
        ];
    }

    /** HTML widget */
    public static function html(string $code): array
    {
        return [
            'id' => self::eid(),
            'elType' => 'widget',
            'widgetType' => 'html',
            'settings' => ['html' => $code],
            'elements' => [],
        ];
    }

    /** Spacer widget */
    public static function spacer(int $size = 20): array
    {
        return [
            'id' => self::eid(),
            'elType' => 'widget',
            'widgetType' => 'spacer',
            'settings' => ['space' => ['size' => $size, 'unit' => 'px']],
            'elements' => [],
        ];
    }

    /** Divider widget */
    public static function divider(array $settings = []): array
    {
        return [
            'id' => self::eid(),
            'elType' => 'widget',
            'widgetType' => 'divider',
            'settings' => $settings,
            'elements' => [],
        ];
    }

    /** Icon widget */
    public static function icon(string $iconClass = 'fas fa-star', array $settings = []): array
    {
        return [
            'id' => self::eid(),
            'elType' => 'widget',
            'widgetType' => 'icon',
            'settings' => array_merge([
                'selected_icon' => ['value' => $iconClass, 'library' => 'fa-solid'],
            ], $settings),
            'elements' => [],
        ];
    }

    // ═══════════════════════════════════════════════════════════════
    // SHARED HELPERS
    // ═══════════════════════════════════════════════════════════════

    /** Padding shorthand */
    public static function pad(int|string $top, int|string|null $right = null, int|string|null $bottom = null, int|string|null $left = null): array
    {
        $right = $right ?? $top;
        $bottom = $bottom ?? $top;
        $left = $left ?? $right;
        return [
            'unit' => 'px',
            'top' => (string) $top,
            'right' => (string) $right,
            'bottom' => (string) $bottom,
            'left' => (string) $left,
            'isLinked' => ($top === $right && $right === $bottom && $bottom === $left),
        ];
    }

    /** Margin shorthand — accepts 'auto' for centering */
    public static function margin(int|string $top, int|string $right = 0, int|string $bottom = 0, int|string $left = 0): array
    {
        return self::pad($top, $right, $bottom, $left);
    }

    /** Elementor size value with sizes array */
    public static function size(int $desktop, ?int $tablet = null, ?int $mobile = null, string $unit = 'px'): array
    {
        return ['size' => $desktop, 'unit' => $unit, 'sizes' => []];
    }

    /** Responsive size settings (returns multiple keys for typography_font_size + _tablet + _mobile) */
    public static function responsiveSize(int $desktop, int $tablet, int $mobile, string $unit = 'px'): array
    {
        return [
            'typography_font_size' => ['size' => $desktop, 'unit' => $unit],
            'typography_font_size_tablet' => ['size' => $tablet, 'unit' => $unit],
            'typography_font_size_mobile' => ['size' => $mobile, 'unit' => $unit],
        ];
    }

    /** Border radius shorthand */
    public static function radius(int $px): array
    {
        return [
            'unit' => 'px',
            'top' => (string) $px,
            'right' => (string) $px,
            'bottom' => (string) $px,
            'left' => (string) $px,
            'isLinked' => true,
        ];
    }

    /** Google Fonts link tag */
    public static function googleFontsLink(array $fonts): string
    {
        $families = [];
        foreach ($fonts as $font => $weights) {
            $families[] = 'family=' . str_replace(' ', '+', $font) . ':wght@' . implode(';', $weights);
        }
        return '<link href="https://fonts.googleapis.com/css2?' . implode('&', $families) . '&display=swap" rel="stylesheet">';
    }

    /** Hex to rgba */
    public static function hexToRgba(string $hex, float $alpha = 1.0): string
    {
        $hex = ltrim($hex, '#');
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        return "rgba($r,$g,$b,$alpha)";
    }

    /** Elementor-style responsive width: desktop / tablet / mobile */
    public static function rWidth(int $desktop, ?int $tablet = null, ?int $mobile = null): array
    {
        $result = ['width' => ['size' => $desktop, 'unit' => '%', 'sizes' => []]];
        if ($tablet !== null) {
            $result['width_tablet'] = ['size' => $tablet, 'unit' => '%', 'sizes' => []];
        }
        if ($mobile !== null) {
            $result['width_mobile'] = ['size' => $mobile, 'unit' => '%', 'sizes' => []];
        }
        return $result;
    }

    /** Build a section container — matches 10web's boxed_width pattern */
    public function section(array $settings = [], array $elements = [], string $id = ''): array
    {
        $defaults = [
            'boxed_width' => ['size' => 1200, 'unit' => 'px', 'sizes' => []],
            'flex_direction' => 'column',
            'padding' => self::pad(100, 40),
            'padding_mobile' => self::pad(50, 15),
            'padding_tablet' => self::pad(80, 30),
        ];
        // Remove content_width to let Elementor default to 'boxed'
        unset($defaults['content_width']);
        if ($id) {
            $defaults['_element_id'] = $id;
        }
        $merged = array_merge($defaults, $settings);
        // Ensure content_width is not set so Elementor defaults to boxed
        if (!isset($settings['content_width'])) {
            unset($merged['content_width']);
        }
        return [
            'id' => self::eid(),
            'elType' => 'container',
            'settings' => $merged,
            'elements' => $elements,
        ];
    }

    /** Two-column row — uses flex_direction_mobile:column for stacking (10web pattern) */
    public static function twoCol(array $leftElements, array $rightElements, int $leftPct = 50, array $rowSettings = [], array $leftSettings = [], array $rightSettings = []): array
    {
        return self::container(array_merge([
            'boxed_width' => ['size' => 1200, 'unit' => 'px', 'sizes' => []],
            'flex_direction' => 'row',
            'flex_direction_mobile' => 'column',
            'flex_direction_tablet' => 'row',
            'flex_align_items' => 'center',
            'flex_align_items_mobile' => 'flex-start',
            'flex_justify_content_mobile' => 'center',
            'padding' => self::pad(80, 40),
            'padding_mobile' => self::pad(50, 15),
            'padding_tablet' => self::pad(80, 30),
        ], $rowSettings), [
            self::container(array_merge([
                'content_width' => 'full',
                'flex_direction' => 'column',
            ], self::rWidth($leftPct, $leftPct, 100), $leftSettings), $leftElements),
            self::container(array_merge([
                'content_width' => 'full',
                'flex_direction' => 'column',
            ], self::rWidth(100 - $leftPct, 100 - $leftPct, 100), $rightSettings), $rightElements),
        ]);
    }

    /** Card grid — uses responsive widths for tablet/mobile (10web pattern) */
    public static function cardGrid(array $cards, int $cols = 3, array $gridSettings = []): array
    {
        // Gap-aware widths: flex_wrap+gap needs smaller % so items don't overflow
        // 3-col: 31% (not 33%), 4-col: 23% (not 25%), 2-col: 48% (not 50%)
        $desktopW = match($cols) {
            2 => 48,
            3 => 31,
            4 => 23,
            default => max(round(100 / $cols) - 2, 10),
        };
        $tabletW = $cols > 2 ? 48 : $desktopW;
        $mobileW = 100;

        $wrapped = [];
        foreach ($cards as $card) {
            $rw = self::rWidth($desktopW, $tabletW, $mobileW);
            if (isset($card['settings']) && $card['elType'] === 'container') {
                $card['settings'] = array_merge($card['settings'], $rw);
                $wrapped[] = $card;
            } else {
                $wrapped[] = self::container(array_merge([
                    'content_width' => 'full',
                    'flex_direction' => 'column',
                ], $rw), [$card]);
            }
        }
        return self::container(array_merge([
            'content_width' => 'full',
            'flex_direction' => 'row',
            'flex_direction_mobile' => 'column',
            'flex_wrap' => 'wrap',
            'flex_gap' => ['size' => 24, 'unit' => 'px', 'column' => '24', 'row' => '24'],
        ], $gridSettings), $wrapped);
    }

    // ─── Shortcut: heading with layout typography ───

    public function headline(string $text, string $tag = 'h2', array $extra = []): array
    {
        $c = $this->colors();
        $f = $this->fonts();
        return self::heading($text, $tag, array_merge([
            'align' => 'center',
            'title_color' => $c['text'] ?? '#FFFFFF',
            'typography_typography' => 'custom',
            'typography_font_family' => $f['heading'],
            'typography_font_weight' => '900',
            'typography_line_height' => ['size' => 0.95, 'unit' => 'em'],
            'typography_text_transform' => 'uppercase',
        ], self::responsiveSize(72, 52, 38), $extra));
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
            'text_color' => $c['muted'] ?? 'rgba(255,255,255,0.5)',
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
            'typography_font_family' => $f['heading'],
            'typography_font_size' => ['size' => 14, 'unit' => 'px'],
            'typography_font_weight' => '700',
            'typography_letter_spacing' => ['size' => 3, 'unit' => 'px'],
            'typography_text_transform' => 'uppercase',
            'border_radius' => self::radius(2),
            'button_padding' => self::pad(16, 44),
            'button_background_hover_color' => $c['secondary'] ?? $c['primary'],
        ], $extra));
    }

    public function ghostButton(string $text, string $url = '#', array $extra = []): array
    {
        $c = $this->colors();
        $f = $this->fonts();
        $borderColor = $this->isDark() ? 'rgba(255,255,255,0.2)' : 'rgba(0,0,0,0.2)';
        $textColor = $this->isDark() ? 'rgba(255,255,255,0.7)' : 'rgba(0,0,0,0.7)';
        return self::button($text, $url, array_merge([
            'align' => 'center',
            'button_type' => 'default',
            'background_color' => 'transparent',
            'button_text_color' => $textColor,
            'typography_typography' => 'custom',
            'typography_font_family' => $f['heading'],
            'typography_font_size' => ['size' => 14, 'unit' => 'px'],
            'typography_font_weight' => '600',
            'typography_letter_spacing' => ['size' => 2, 'unit' => 'px'],
            'typography_text_transform' => 'uppercase',
            'border_border' => 'solid',
            'border_width' => self::pad(1),
            'border_color' => $borderColor,
            'border_radius' => self::radius(2),
            'button_padding' => self::pad(15, 36),
        ], $extra));
    }

    // ─── WooCommerce Product Grid CSS (used by all layouts) ───

    /** Professional WooCommerce CSS — include in buildGlobalCss() */
    protected function woocommerceCss(): string
    {
        $c = $this->colors();
        $primary = $c['primary'];
        $text = $c['text'] ?? '#111';
        $muted = $c['muted'] ?? '#666';
        $bg = $c['bg'] ?? '#fff';
        $surface = $c['surface'] ?? '#fff';
        $border = $c['border'] ?? '#e5e5e5';

        return <<<CSS
/* ═══ WooCommerce Product Grid ═══ */
.woocommerce ul.products,.woocommerce-page ul.products{display:grid!important;grid-template-columns:repeat(4,1fr)!important;gap:24px!important;padding:0!important;margin:0!important;list-style:none!important;}
.woocommerce ul.products::before,.woocommerce ul.products::after{display:none!important;}
.woocommerce ul.products li.product,.woocommerce-page ul.products li.product{background:{$surface}!important;border-radius:12px!important;overflow:hidden!important;border:1px solid {$border}!important;transition:all .35s ease!important;position:relative!important;padding:0!important;margin:0!important;width:100%!important;float:none!important;display:flex!important;flex-direction:column!important;}
.woocommerce ul.products li.product:hover{transform:translateY(-4px)!important;box-shadow:0 12px 40px rgba(0,0,0,.08)!important;}
.woocommerce ul.products li.product a img,.woocommerce ul.products li.product img{width:100%!important;height:280px!important;object-fit:cover!important;display:block!important;transition:transform .5s ease!important;max-width:100%!important;}
.woocommerce ul.products li.product:hover a img{transform:scale(1.05)!important;}
.woocommerce ul.products li.product .woocommerce-loop-product__title{font-size:15px!important;font-weight:600!important;color:{$text}!important;padding:16px 16px 4px!important;margin:0!important;}
.woocommerce ul.products li.product .price{padding:0 16px 8px!important;font-size:15px!important;color:{$primary}!important;font-weight:700!important;}
.woocommerce ul.products li.product .price del{opacity:.45!important;font-weight:400!important;font-size:13px!important;}
.woocommerce ul.products li.product .price ins{text-decoration:none!important;font-weight:700!important;}
.woocommerce ul.products li.product .button,.woocommerce ul.products li.product .add_to_cart_button,.woocommerce ul.products li.product a.add_to_cart_button{display:block!important;text-align:center!important;margin:auto 16px 16px!important;padding:10px 16px!important;background:{$primary}!important;color:#fff!important;border-radius:8px!important;font-size:13px!important;font-weight:600!important;text-decoration:none!important;text-transform:uppercase!important;letter-spacing:.5px!important;transition:all .3s!important;border:none!important;cursor:pointer!important;width:auto!important;}
.woocommerce ul.products li.product .button:hover,.woocommerce ul.products li.product .add_to_cart_button:hover{opacity:.85!important;transform:translateY(-1px)!important;}
/* Sale badge */
.woocommerce ul.products li.product .onsale,.woocommerce span.onsale{position:absolute!important;top:12px!important;right:12px!important;left:auto!important;background:{$primary}!important;color:#fff!important;font-size:11px!important;font-weight:700!important;padding:4px 12px!important;border-radius:20px!important;z-index:2!important;text-transform:uppercase!important;letter-spacing:.5px!important;min-height:auto!important;min-width:auto!important;line-height:1.5!important;}
/* Star rating */
.woocommerce .star-rating{color:#FBBF24;font-size:12px;margin:0 16px 4px;}
/* Single product */
.woocommerce div.product .woocommerce-tabs{margin-top:24px;}
.woocommerce div.product .price{font-size:24px!important;color:{$primary}!important;font-weight:700!important;}
/* Cart & Checkout */
.woocommerce table.shop_table{border-radius:12px!important;overflow:hidden;border:1px solid {$border}!important;}
.woocommerce .checkout .form-row input,.woocommerce .checkout .form-row select,.woocommerce .checkout .form-row textarea{border-radius:8px!important;border:1px solid {$border}!important;padding:10px 14px!important;}
.woocommerce #respond input#submit,.woocommerce a.button,.woocommerce button.button,.woocommerce input.button{background:{$primary}!important;color:#fff!important;border-radius:8px!important;font-weight:600!important;border:none!important;transition:all .3s!important;}
.woocommerce #respond input#submit:hover,.woocommerce a.button:hover,.woocommerce button.button:hover,.woocommerce input.button:hover{opacity:.85!important;}
/* Shop page override */
.woocommerce-page .products ul,.woocommerce .products ul,ul.products{list-style:none!important;}
/* Responsive */
@media(max-width:1024px){.woocommerce ul.products,.woocommerce-page ul.products{grid-template-columns:repeat(3,1fr)!important;gap:16px!important;}}
@media(max-width:767px){.woocommerce ul.products,.woocommerce-page ul.products{grid-template-columns:repeat(2,1fr)!important;gap:12px!important;}
.woocommerce ul.products li.product a img{height:200px!important;}}
@media(max-width:480px){.woocommerce ul.products,.woocommerce-page ul.products{grid-template-columns:1fr!important;}}
CSS;
    }

    // ─── Resolve layout class from slug ───

    public static function resolve(string $slug): ?self
    {
        $map = [
            'noir' => LayoutNoir::class,
            'ivory' => LayoutIvory::class,
            'azure' => LayoutAzure::class,
            'blush' => LayoutBlush::class,
            'ember' => LayoutEmber::class,
            'forest' => LayoutForest::class,
            'slate' => LayoutSlate::class,
            'royal' => LayoutRoyal::class,
            'biddut' => LayoutBiddut::class,
        ];
        $class = $map[$slug] ?? null;
        return $class ? new $class() : null;
    }

    /** Get all available layout instances */
    public static function all(): array
    {
        $slugs = ['noir', 'ivory', 'azure', 'blush', 'ember', 'forest', 'slate', 'royal', 'biddut'];
        return array_map(fn($s) => self::resolve($s), $slugs);
    }
}
