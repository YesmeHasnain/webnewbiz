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

        $elements = $this->$method($content, $images);

        // CSS/JS as top-level containers wrapping the html widgets
        // Each section is a top-level container — no outer wrapper that would cause horizontal flex
        $cssSection = self::container([
            'content_width' => 'full-width',
            'flex_direction' => 'column',
            'padding' => self::pad(0),
        ], [self::html($this->buildGlobalCss())]);

        $jsSection = self::container([
            'content_width' => 'full-width',
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
    // WIDGET PRIMITIVES — static, shared across all layouts
    // ═══════════════════════════════════════════════════════════════

    /** Generate 7-char hex Elementor ID */
    public static function eid(): string
    {
        return substr(bin2hex(random_bytes(4)), 0, 7);
    }

    /** Container element */
    public static function container(array $settings = [], array $elements = []): array
    {
        return [
            'id' => self::eid(),
            'elType' => 'container',
            'settings' => array_merge(['content_width' => 'full-width'], $settings),
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

    /** Button widget */
    public static function button(string $text, string $url = '#', array $settings = []): array
    {
        return [
            'id' => self::eid(),
            'elType' => 'widget',
            'widgetType' => 'button',
            'settings' => array_merge([
                'text' => $text,
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
    public static function pad(int $top, ?int $right = null, ?int $bottom = null, ?int $left = null): array
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

    /** Margin shorthand */
    public static function margin(int $top, int $right = 0, int $bottom = 0, int $left = 0): array
    {
        return self::pad($top, $right, $bottom, $left);
    }

    /** Responsive size (desktop / tablet / mobile) */
    public static function size(int $desktop, ?int $tablet = null, ?int $mobile = null, string $unit = 'px'): array
    {
        return ['size' => $desktop, 'unit' => $unit];
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

    /** Build a section container with standard layout padding */
    public function section(array $settings = [], array $elements = [], string $id = ''): array
    {
        $defaults = [
            'content_width' => 'full-width',
            'flex_direction' => 'column',
            'padding' => self::pad(100, 64),
        ];
        if ($id) {
            $defaults['_element_id'] = $id;
        }
        return self::container(array_merge($defaults, $settings), $elements);
    }

    /** Two-column row (left/right split) */
    public static function twoCol(array $leftElements, array $rightElements, int $leftPct = 50, array $rowSettings = [], array $leftSettings = [], array $rightSettings = []): array
    {
        return self::container(array_merge([
            'content_width' => 'full-width',
            'flex_direction' => 'row',
            'gap' => ['size' => 60, 'unit' => 'px'],
        ], $rowSettings), [
            self::container(array_merge([
                'content_width' => 'full-width',
                'flex_direction' => 'column',
                'width' => ['size' => $leftPct, 'unit' => '%'],
            ], $leftSettings), $leftElements),
            self::container(array_merge([
                'content_width' => 'full-width',
                'flex_direction' => 'column',
                'width' => ['size' => 100 - $leftPct, 'unit' => '%'],
            ], $rightSettings), $rightElements),
        ]);
    }

    /** Card grid — wraps items in equal-width columns */
    public static function cardGrid(array $cards, int $cols = 3, array $gridSettings = []): array
    {
        $width = round(100 / $cols, 2);
        $wrapped = [];
        foreach ($cards as $card) {
            if (isset($card['settings']) && $card['elType'] === 'container') {
                $card['settings']['width'] = ['size' => $width, 'unit' => '%'];
                $wrapped[] = $card;
            } else {
                $wrapped[] = self::container([
                    'content_width' => 'full-width',
                    'flex_direction' => 'column',
                    'width' => ['size' => $width, 'unit' => '%'],
                ], [$card]);
            }
        }
        return self::container(array_merge([
            'content_width' => 'full-width',
            'flex_direction' => 'row',
            'flex_wrap' => 'wrap',
            'gap' => ['size' => 24, 'unit' => 'px'],
        ], $gridSettings), $wrapped);
    }

    // ─── Shortcut: heading with layout typography ───

    public function headline(string $text, string $tag = 'h2', array $extra = []): array
    {
        $c = $this->colors();
        $f = $this->fonts();
        return self::heading($text, $tag, array_merge([
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
        return self::textEditor('<p class="eyebrow">' . $text . '</p>');
    }

    public function bodyText(string $text, array $extra = []): array
    {
        $c = $this->colors();
        $f = $this->fonts();
        return self::textEditor('<p>' . $text . '</p>', array_merge([
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
        ];
        $class = $map[$slug] ?? null;
        return $class ? new $class() : null;
    }

    /** Get all available layout instances */
    public static function all(): array
    {
        $slugs = ['noir', 'ivory', 'azure', 'blush', 'ember', 'forest', 'slate', 'royal'];
        return array_map(fn($s) => self::resolve($s), $slugs);
    }
}
