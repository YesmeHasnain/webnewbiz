<?php

namespace App\Services\Layouts;

/**
 * Biddut — Theme-based layout for electrical/services businesses.
 * Unlike other layouts, this installs the actual Biddut WordPress theme
 * with its custom widgets (biddut-core plugin) and demo content,
 * rather than generating Elementor JSON from scratch.
 */
class LayoutBiddut extends AbstractLayout
{
    public function slug(): string { return 'biddut'; }
    public function name(): string { return 'Biddut'; }
    public function description(): string { return 'Professional services theme with 40+ custom Elementor widgets for electrical, contractor, and home service businesses'; }
    public function bestFor(): array { return ['Electrical Services', 'Contractors', 'Home Services', 'Industrial']; }
    public function isDark(): bool { return true; }

    /** Theme-based layouts install an actual WP theme instead of generating Elementor JSON */
    public function isThemeBased(): bool { return true; }

    /** The WP theme slug to activate */
    public function themeSlug(): string { return 'biddut'; }

    public function colors(): array
    {
        return [
            'primary'   => '#007FFF',
            'secondary' => '#3E976C',
            'accent'    => '#F7A600',
            'bg'        => '#0A0F2C',
            'surface'   => '#111936',
            'surface2'  => '#1A2340',
            'text'      => '#FFFFFF',
            'muted'     => 'rgba(255,255,255,0.6)',
            'border'    => 'rgba(255,255,255,0.1)',
        ];
    }

    public function fonts(): array
    {
        return ['heading' => 'Barlow', 'body' => 'Barlow'];
    }

    // ═══════════════════════════════════════════════════════════
    // Theme-based layouts don't generate Elementor JSON.
    // These methods return empty/minimal stubs since the actual
    // content comes from the theme's demo data import.
    // ═══════════════════════════════════════════════════════════

    public function buildGlobalCss(): string { return ''; }
    public function buildGlobalJs(): string { return ''; }

    public function buildHomePage(array $content, array $images): array { return []; }
    public function buildAboutPage(array $content, array $images): array { return []; }
    public function buildServicesPage(array $content, array $images): array { return []; }
    public function buildPortfolioPage(array $content, array $images): array { return []; }
    public function buildContactPage(array $content, array $images): array { return []; }

    public function buildHeader(string $siteName, array $pages): array { return []; }
    public function buildFooter(string $siteName, array $pages, array $contact): array { return []; }
}
