<?php
namespace WebnewBiz\Builder;

if (!defined('ABSPATH')) exit;

class Theme_Manager {

    /**
     * All 20 theme configurations.
     * Each theme maps to a template style for Elementor data.
     */
    private static array $themes = [
        'wnb-starter' => [
            'name'          => 'WebnewBiz Starter',
            'description'   => 'Clean and professional theme. Perfect for business websites.',
            'template'      => 'starter',
            'primary'       => '#2563eb',
            'primary_dark'  => '#1e40af',
            'secondary'     => '#1e40af',
            'accent'        => '#60a5fa',
            'text'          => '#1f2937',
            'text_light'    => '#6b7280',
            'bg'            => '#ffffff',
            'bg_alt'        => '#f9fafb',
            'border'        => '#e5e7eb',
            'heading_font'  => 'Inter',
            'body_font'     => 'DM Sans',
            'font_url'      => 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=DM+Sans:wght@400;500;700&display=swap',
            'footer_bg'     => '#1f2937, #374151',
            'radius'        => '8px',
            'category'      => 'light',
        ],
        'wnb-agency' => [
            'name'          => 'WebnewBiz Agency',
            'description'   => 'Modern agency theme with indigo-violet gradient. For agencies and startups.',
            'template'      => 'agency',
            'primary'       => '#6366f1',
            'primary_dark'  => '#4f46e5',
            'secondary'     => '#8b5cf6',
            'accent'        => '#a78bfa',
            'text'          => '#1e1b4b',
            'text_light'    => '#6b7280',
            'bg'            => '#ffffff',
            'bg_alt'        => '#f5f3ff',
            'border'        => '#e5e7eb',
            'heading_font'  => 'Montserrat',
            'body_font'     => 'Open Sans',
            'font_url'      => 'https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&family=Open+Sans:wght@400;500;600;700&display=swap',
            'footer_bg'     => '#1e1b4b, #312e81',
            'radius'        => '12px',
            'category'      => 'light',
        ],
        'wnb-corporate' => [
            'name'          => 'WebnewBiz Corporate',
            'description'   => 'Classic refined theme with navy-gold palette. For corporate businesses.',
            'template'      => 'corporate',
            'primary'       => '#1e3a5f',
            'primary_dark'  => '#152d4a',
            'secondary'     => '#d4a574',
            'accent'        => '#64748b',
            'text'          => '#1a1a2e',
            'text_light'    => '#6b7280',
            'bg'            => '#ffffff',
            'bg_alt'        => '#f8f6f3',
            'border'        => '#e5e7eb',
            'heading_font'  => 'Playfair Display',
            'body_font'     => 'Lato',
            'font_url'      => 'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family=Lato:wght@300;400;700&display=swap',
            'footer_bg'     => '#1a1a2e, #1e3a5f',
            'radius'        => '8px',
            'category'      => 'light',
        ],
        'wnb-flavor' => [
            'name'          => 'WebnewBiz Flavor',
            'description'   => 'Warm and inviting with earthy tones. For cafes and restaurants.',
            'template'      => 'flavor',
            'primary'       => '#c2703e',
            'primary_dark'  => '#a85d30',
            'secondary'     => '#d4a574',
            'accent'        => '#7c956b',
            'text'          => '#3d2b1f',
            'text_light'    => '#78716c',
            'bg'            => '#ffffff',
            'bg_alt'        => '#faf5f0',
            'border'        => '#e7e5e4',
            'heading_font'  => 'Raleway',
            'body_font'     => 'Nunito',
            'font_url'      => 'https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;500;600;700&family=Nunito:wght@400;500;600;700&display=swap',
            'footer_bg'     => '#3d2b1f, #5c3d2e',
            'radius'        => '8px',
            'category'      => 'light',
        ],
        'wnb-prestige' => [
            'name'          => 'WebnewBiz Prestige',
            'description'   => 'Luxurious black-gold theme. For luxury brands and high-end services.',
            'template'      => 'prestige',
            'primary'       => '#c5a55a',
            'primary_dark'  => '#b8953d',
            'secondary'     => '#1a1a1a',
            'accent'        => '#f5e6c8',
            'text'          => '#1a1a1a',
            'text_light'    => '#78716c',
            'bg'            => '#ffffff',
            'bg_alt'        => '#faf8f2',
            'border'        => '#e7e5e4',
            'heading_font'  => 'Cormorant Garamond',
            'body_font'     => 'Libre Franklin',
            'font_url'      => 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Libre+Franklin:wght@300;400;500;600&display=swap',
            'footer_bg'     => '#0a0a0a, #1a1a1a',
            'radius'        => '4px',
            'category'      => 'light',
        ],
        'wnb-vivid' => [
            'name'          => 'WebnewBiz Vivid',
            'description'   => 'Vibrant fuchsia-cyan theme. For creative studios and portfolios.',
            'template'      => 'vivid',
            'primary'       => '#d946ef',
            'primary_dark'  => '#c026d3',
            'secondary'     => '#06b6d4',
            'accent'        => '#eab308',
            'text'          => '#18181b',
            'text_light'    => '#6b7280',
            'bg'            => '#ffffff',
            'bg_alt'        => '#fdf4ff',
            'border'        => '#e5e7eb',
            'heading_font'  => 'Space Grotesk',
            'body_font'     => 'Work Sans',
            'font_url'      => 'https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Work+Sans:wght@400;500;600;700&display=swap',
            'footer_bg'     => '#18181b, #2e1065',
            'radius'        => '12px',
            'category'      => 'light',
        ],
        'wnb-zenith' => [
            'name'          => 'WebnewBiz Zenith',
            'description'   => 'Futuristic dark-cyan theme. For SaaS, tech, and developer sites.',
            'template'      => 'zenith',
            'primary'       => '#06b6d4',
            'primary_dark'  => '#0891b2',
            'secondary'     => '#0f172a',
            'accent'        => '#84cc16',
            'text'          => '#e2e8f0',
            'text_light'    => '#94a3b8',
            'bg'            => '#0f172a',
            'bg_alt'        => '#1e293b',
            'border'        => '#334155',
            'heading_font'  => 'JetBrains Mono',
            'body_font'     => 'Inter',
            'font_url'      => 'https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&family=Inter:wght@300;400;500;600;700&display=swap',
            'footer_bg'     => '#020617, #0f172a',
            'radius'        => '8px',
            'category'      => 'dark',
        ],
        'wnb-bloom' => [
            'name'          => 'WebnewBiz Bloom',
            'description'   => 'Organic nature-inspired theme. For wellness, eco, and lifestyle brands.',
            'template'      => 'agency',
            'primary'       => '#2d6a4f',
            'primary_dark'  => '#1b4332',
            'secondary'     => '#95d5b2',
            'accent'        => '#8b7355',
            'text'          => '#1b2a1b',
            'text_light'    => '#78716c',
            'bg'            => '#ffffff',
            'bg_alt'        => '#f0faf0',
            'border'        => '#e5e7eb',
            'heading_font'  => 'Merriweather',
            'body_font'     => 'Source Sans 3',
            'font_url'      => 'https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400;700&family=Source+Sans+3:wght@400;500;600;700&display=swap',
            'footer_bg'     => '#1b2a1b, #2d6a4f',
            'radius'        => '8px',
            'category'      => 'light',
        ],
        'wnb-pulse' => [
            'name'          => 'WebnewBiz Pulse',
            'description'   => 'Energetic red-orange theme. For fitness, sports, and entertainment.',
            'template'      => 'starter',
            'primary'       => '#ef4444',
            'primary_dark'  => '#dc2626',
            'secondary'     => '#f97316',
            'accent'        => '#fbbf24',
            'text'          => '#18181b',
            'text_light'    => '#6b7280',
            'bg'            => '#ffffff',
            'bg_alt'        => '#fef2f2',
            'border'        => '#e5e7eb',
            'heading_font'  => 'Bebas Neue',
            'body_font'     => 'Roboto',
            'font_url'      => 'https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Roboto:wght@300;400;500;700&display=swap',
            'footer_bg'     => '#18181b, #7f1d1d',
            'radius'        => '8px',
            'category'      => 'light',
        ],
        'wnb-slate' => [
            'name'          => 'WebnewBiz Slate',
            'description'   => 'Sophisticated dark slate-emerald theme. For consulting and finance.',
            'template'      => 'corporate',
            'primary'       => '#10b981',
            'primary_dark'  => '#059669',
            'secondary'     => '#334155',
            'accent'        => '#94a3b8',
            'text'          => '#f1f5f9',
            'text_light'    => '#94a3b8',
            'bg'            => '#1e293b',
            'bg_alt'        => '#334155',
            'border'        => '#475569',
            'heading_font'  => 'IBM Plex Sans',
            'body_font'     => 'IBM Plex Serif',
            'font_url'      => 'https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&family=IBM+Plex+Serif:wght@400;500;600&display=swap',
            'footer_bg'     => '#0f172a, #1e293b',
            'radius'        => '8px',
            'category'      => 'dark',
        ],
        'wnb-aurora' => [
            'name'          => 'WebnewBiz Aurora',
            'description'   => 'Northern lights purple-teal theme. For creative and innovative brands.',
            'template'      => 'vivid',
            'primary'       => '#7c3aed',
            'primary_dark'  => '#6d28d9',
            'secondary'     => '#0d9488',
            'accent'        => '#c084fc',
            'text'          => '#1e1b4b',
            'text_light'    => '#6b7280',
            'bg'            => '#ffffff',
            'bg_alt'        => '#f5f3ff',
            'border'        => '#e5e7eb',
            'heading_font'  => 'Outfit',
            'body_font'     => 'Figtree',
            'font_url'      => 'https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Figtree:wght@400;500;600;700&display=swap',
            'footer_bg'     => '#1e1b4b, #312e81',
            'radius'        => '12px',
            'category'      => 'light',
        ],
        'wnb-ember' => [
            'name'          => 'WebnewBiz Ember',
            'description'   => 'Warm fire-inspired theme. For restaurants, bakeries, and artisan brands.',
            'template'      => 'flavor',
            'primary'       => '#b45309',
            'primary_dark'  => '#92400e',
            'secondary'     => '#dc2626',
            'accent'        => '#f59e0b',
            'text'          => '#292524',
            'text_light'    => '#78716c',
            'bg'            => '#ffffff',
            'bg_alt'        => '#fffbeb',
            'border'        => '#e7e5e4',
            'heading_font'  => 'Bitter',
            'body_font'     => 'Cabin',
            'font_url'      => 'https://fonts.googleapis.com/css2?family=Bitter:wght@400;500;600;700&family=Cabin:wght@400;500;600;700&display=swap',
            'footer_bg'     => '#292524, #44403c',
            'radius'        => '8px',
            'category'      => 'light',
        ],
        'wnb-oceanic' => [
            'name'          => 'WebnewBiz Oceanic',
            'description'   => 'Ocean-inspired blue-teal theme. For travel, marine, and wellness.',
            'template'      => 'agency',
            'primary'       => '#0369a1',
            'primary_dark'  => '#075985',
            'secondary'     => '#0891b2',
            'accent'        => '#38bdf8',
            'text'          => '#0c4a6e',
            'text_light'    => '#64748b',
            'bg'            => '#ffffff',
            'bg_alt'        => '#f0f9ff',
            'border'        => '#e0f2fe',
            'heading_font'  => 'Josefin Sans',
            'body_font'     => 'Mulish',
            'font_url'      => 'https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300;400;500;600;700&family=Mulish:wght@400;500;600;700&display=swap',
            'footer_bg'     => '#0c4a6e, #164e63',
            'radius'        => '10px',
            'category'      => 'light',
        ],
        'wnb-carbon' => [
            'name'          => 'WebnewBiz Carbon',
            'description'   => 'Ultra-minimal dark theme. For photography, portfolio, and modern brands.',
            'template'      => 'starter',
            'primary'       => '#ffffff',
            'primary_dark'  => '#e5e5e5',
            'secondary'     => '#404040',
            'accent'        => '#a3a3a3',
            'text'          => '#fafafa',
            'text_light'    => '#a3a3a3',
            'bg'            => '#0a0a0a',
            'bg_alt'        => '#171717',
            'border'        => '#262626',
            'heading_font'  => 'Sora',
            'body_font'     => 'Manrope',
            'font_url'      => 'https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap',
            'footer_bg'     => '#000000, #0a0a0a',
            'radius'        => '6px',
            'category'      => 'dark',
        ],
        'wnb-sahara' => [
            'name'          => 'WebnewBiz Sahara',
            'description'   => 'Desert-inspired sand tones. For real estate, architecture, and design.',
            'template'      => 'prestige',
            'primary'       => '#a16207',
            'primary_dark'  => '#854d0e',
            'secondary'     => '#d97706',
            'accent'        => '#ca8a04',
            'text'          => '#1c1917',
            'text_light'    => '#78716c',
            'bg'            => '#ffffff',
            'bg_alt'        => '#fefce8',
            'border'        => '#e7e5e4',
            'heading_font'  => 'Archivo',
            'body_font'     => 'Karla',
            'font_url'      => 'https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;600;700;800&family=Karla:wght@400;500;600;700&display=swap',
            'footer_bg'     => '#1c1917, #292524',
            'radius'        => '6px',
            'category'      => 'light',
        ],
        'wnb-arctic' => [
            'name'          => 'WebnewBiz Arctic',
            'description'   => 'Clean ice-blue theme. For healthcare, fintech, and SaaS products.',
            'template'      => 'corporate',
            'primary'       => '#3b82f6',
            'primary_dark'  => '#2563eb',
            'secondary'     => '#6366f1',
            'accent'        => '#93c5fd',
            'text'          => '#0f172a',
            'text_light'    => '#64748b',
            'bg'            => '#ffffff',
            'bg_alt'        => '#f8fafc',
            'border'        => '#e2e8f0',
            'heading_font'  => 'Plus Jakarta Sans',
            'body_font'     => 'Nunito Sans',
            'font_url'      => 'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Nunito+Sans:wght@400;500;600;700&display=swap',
            'footer_bg'     => '#0f172a, #1e293b',
            'radius'        => '10px',
            'category'      => 'light',
        ],
        'wnb-velvet' => [
            'name'          => 'WebnewBiz Velvet',
            'description'   => 'Rich burgundy-plum theme. For wine, fashion, and luxury hospitality.',
            'template'      => 'prestige',
            'primary'       => '#881337',
            'primary_dark'  => '#6b1030',
            'secondary'     => '#7c2d12',
            'accent'        => '#fda4af',
            'text'          => '#1c1917',
            'text_light'    => '#78716c',
            'bg'            => '#ffffff',
            'bg_alt'        => '#fff1f2',
            'border'        => '#e7e5e4',
            'heading_font'  => 'EB Garamond',
            'body_font'     => 'Crimson Text',
            'font_url'      => 'https://fonts.googleapis.com/css2?family=EB+Garamond:wght@400;500;600;700;800&family=Crimson+Text:wght@400;600;700&display=swap',
            'footer_bg'     => '#1c1917, #44403c',
            'radius'        => '4px',
            'category'      => 'light',
        ],
        'wnb-citrus' => [
            'name'          => 'WebnewBiz Citrus',
            'description'   => 'Fresh lime-orange theme. For food, kids, and lifestyle brands.',
            'template'      => 'flavor',
            'primary'       => '#ea580c',
            'primary_dark'  => '#c2410c',
            'secondary'     => '#65a30d',
            'accent'        => '#facc15',
            'text'          => '#1c1917',
            'text_light'    => '#78716c',
            'bg'            => '#ffffff',
            'bg_alt'        => '#fefce8',
            'border'        => '#e5e7eb',
            'heading_font'  => 'Quicksand',
            'body_font'     => 'Rubik',
            'font_url'      => 'https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&family=Rubik:wght@400;500;600;700&display=swap',
            'footer_bg'     => '#1c1917, #292524',
            'radius'        => '10px',
            'category'      => 'light',
        ],
        'wnb-timber' => [
            'name'          => 'WebnewBiz Timber',
            'description'   => 'Rustic woodsy theme. For construction, outdoor, and craft brands.',
            'template'      => 'corporate',
            'primary'       => '#78350f',
            'primary_dark'  => '#652b0a',
            'secondary'     => '#166534',
            'accent'        => '#a16207',
            'text'          => '#1c1917',
            'text_light'    => '#78716c',
            'bg'            => '#ffffff',
            'bg_alt'        => '#faf5ef',
            'border'        => '#e7e5e4',
            'heading_font'  => 'Vollkorn',
            'body_font'     => 'PT Sans',
            'font_url'      => 'https://fonts.googleapis.com/css2?family=Vollkorn:wght@400;500;600;700&family=PT+Sans:wght@400;700&display=swap',
            'footer_bg'     => '#1c1917, #292524',
            'radius'        => '6px',
            'category'      => 'light',
        ],
        'wnb-neon' => [
            'name'          => 'WebnewBiz Neon',
            'description'   => 'Electric neon-on-dark theme. For gaming, nightlife, and entertainment.',
            'template'      => 'vivid',
            'primary'       => '#22d3ee',
            'primary_dark'  => '#06b6d4',
            'secondary'     => '#f43f5e',
            'accent'        => '#a3e635',
            'text'          => '#f0fdfa',
            'text_light'    => '#94a3b8',
            'bg'            => '#020617',
            'bg_alt'        => '#0f172a',
            'border'        => '#1e293b',
            'heading_font'  => 'Orbitron',
            'body_font'     => 'Exo 2',
            'font_url'      => 'https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800&family=Exo+2:wght@300;400;500;600;700&display=swap',
            'footer_bg'     => '#000000, #020617',
            'radius'        => '8px',
            'category'      => 'dark',
        ],
        // ===== Batch 3: 10 themes with unique header styles =====
        'wnb-metro' => [
            'name'          => 'WebnewBiz Metro',
            'description'   => 'Metropolitan with top utility bar. For law firms and agencies.',
            'template'      => 'corporate',
            'header_style'  => 'topbar',
            'primary'       => '#0ea5e9',
            'primary_dark'  => '#0284c7',
            'secondary'     => '#6366f1',
            'accent'        => '#f59e0b',
            'text'          => '#0f172a',
            'text_light'    => '#64748b',
            'bg'            => '#ffffff',
            'bg_alt'        => '#f8fafc',
            'border'        => '#e2e8f0',
            'heading_font'  => 'DM Sans',
            'body_font'     => 'Inter',
            'font_url'      => 'https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap',
            'footer_bg'     => '#0f172a, #1e293b',
            'radius'        => '8px',
            'category'      => 'light',
        ],
        'wnb-flora' => [
            'name'          => 'WebnewBiz Flora',
            'description'   => 'Botanical centered-header theme. For florists and wellness brands.',
            'template'      => 'agency',
            'header_style'  => 'centered',
            'primary'       => '#059669',
            'primary_dark'  => '#047857',
            'secondary'     => '#d97706',
            'accent'        => '#34d399',
            'text'          => '#064e3b',
            'text_light'    => '#6b7280',
            'bg'            => '#ffffff',
            'bg_alt'        => '#ecfdf5',
            'border'        => '#d1fae5',
            'heading_font'  => 'Playfair Display',
            'body_font'     => 'Nunito',
            'font_url'      => 'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family=Nunito:wght@400;500;600;700&display=swap',
            'footer_bg'     => '#064e3b, #065f46',
            'radius'        => '16px',
            'category'      => 'light',
        ],
        'wnb-forge' => [
            'name'          => 'WebnewBiz Forge',
            'description'   => 'Industrial bold theme. For manufacturing and construction.',
            'template'      => 'starter',
            'header_style'  => 'bold',
            'primary'       => '#ea580c',
            'primary_dark'  => '#c2410c',
            'secondary'     => '#374151',
            'accent'        => '#fbbf24',
            'text'          => '#111827',
            'text_light'    => '#6b7280',
            'bg'            => '#ffffff',
            'bg_alt'        => '#f9fafb',
            'border'        => '#d1d5db',
            'heading_font'  => 'Oswald',
            'body_font'     => 'Source Sans 3',
            'font_url'      => 'https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Source+Sans+3:wght@400;500;600;700&display=swap',
            'footer_bg'     => '#111827, #1f2937',
            'radius'        => '4px',
            'category'      => 'light',
        ],
        'wnb-luxe' => [
            'name'          => 'WebnewBiz Luxe',
            'description'   => 'Ultra-minimal luxury with thin header. For jewelry and fashion.',
            'template'      => 'prestige',
            'header_style'  => 'minimal',
            'primary'       => '#b8860b',
            'primary_dark'  => '#996f09',
            'secondary'     => '#1a1a1a',
            'accent'        => '#d4af37',
            'text'          => '#1a1a1a',
            'text_light'    => '#737373',
            'bg'            => '#ffffff',
            'bg_alt'        => '#fafafa',
            'border'        => '#e5e5e5',
            'heading_font'  => 'Cormorant',
            'body_font'     => 'Montserrat',
            'font_url'      => 'https://fonts.googleapis.com/css2?family=Cormorant:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap',
            'footer_bg'     => '#0a0a0a, #1a1a1a',
            'radius'        => '0px',
            'category'      => 'light',
        ],
        'wnb-prism' => [
            'name'          => 'WebnewBiz Prism',
            'description'   => 'Gradient rainbow theme with colorful header. For events and marketing.',
            'template'      => 'vivid',
            'header_style'  => 'gradient',
            'primary'       => '#8b5cf6',
            'primary_dark'  => '#7c3aed',
            'secondary'     => '#ec4899',
            'accent'        => '#06b6d4',
            'text'          => '#1e1b4b',
            'text_light'    => '#6b7280',
            'bg'            => '#ffffff',
            'bg_alt'        => '#faf5ff',
            'border'        => '#e9d5ff',
            'heading_font'  => 'Poppins',
            'body_font'     => 'Lato',
            'font_url'      => 'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Lato:wght@300;400;700&display=swap',
            'footer_bg'     => '#1e1b4b, #312e81',
            'radius'        => '12px',
            'category'      => 'light',
        ],
        'wnb-dusk' => [
            'name'          => 'WebnewBiz Dusk',
            'description'   => 'Dark twilight with transparent header. For photography and portfolios.',
            'template'      => 'zenith',
            'header_style'  => 'transparent',
            'primary'       => '#a78bfa',
            'primary_dark'  => '#8b5cf6',
            'secondary'     => '#f472b6',
            'accent'        => '#fbbf24',
            'text'          => '#f8fafc',
            'text_light'    => '#94a3b8',
            'bg'            => '#0f0a1e',
            'bg_alt'        => '#1a1333',
            'border'        => '#2d2549',
            'heading_font'  => 'Space Grotesk',
            'body_font'     => 'DM Sans',
            'font_url'      => 'https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=DM+Sans:wght@400;500;600;700&display=swap',
            'footer_bg'     => '#0a0615, #0f0a1e',
            'radius'        => '12px',
            'category'      => 'dark',
        ],
        'wnb-cove' => [
            'name'          => 'WebnewBiz Cove',
            'description'   => 'Coastal beach theme with pill nav. For resorts and travel brands.',
            'template'      => 'agency',
            'header_style'  => 'pill',
            'primary'       => '#0891b2',
            'primary_dark'  => '#0e7490',
            'secondary'     => '#f97316',
            'accent'        => '#67e8f9',
            'text'          => '#164e63',
            'text_light'    => '#64748b',
            'bg'            => '#ffffff',
            'bg_alt'        => '#ecfeff',
            'border'        => '#cffafe',
            'heading_font'  => 'Sora',
            'body_font'     => 'Outfit',
            'font_url'      => 'https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap',
            'footer_bg'     => '#164e63, #155e75',
            'radius'        => '99px',
            'category'      => 'light',
        ],
        'wnb-royal' => [
            'name'          => 'WebnewBiz Royal',
            'description'   => 'Regal ornate with bordered header. For hotels and premium services.',
            'template'      => 'prestige',
            'header_style'  => 'bordered',
            'primary'       => '#7e22ce',
            'primary_dark'  => '#6b21a8',
            'secondary'     => '#c2410c',
            'accent'        => '#f59e0b',
            'text'          => '#1e1b4b',
            'text_light'    => '#6b7280',
            'bg'            => '#ffffff',
            'bg_alt'        => '#faf5ff',
            'border'        => '#e9d5ff',
            'heading_font'  => 'Cinzel',
            'body_font'     => 'Libre Baskerville',
            'font_url'      => 'https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700;800&family=Libre+Baskerville:wght@400;700&display=swap',
            'footer_bg'     => '#1e1b4b, #312e81',
            'radius'        => '4px',
            'category'      => 'light',
        ],
        'wnb-terra' => [
            'name'          => 'WebnewBiz Terra',
            'description'   => 'Earthy terracotta with large header. For pottery and interior design.',
            'template'      => 'flavor',
            'header_style'  => 'large',
            'primary'       => '#b45309',
            'primary_dark'  => '#92400e',
            'secondary'     => '#65a30d',
            'accent'        => '#d97706',
            'text'          => '#292524',
            'text_light'    => '#78716c',
            'bg'            => '#ffffff',
            'bg_alt'        => '#fef3c7',
            'border'        => '#e7e5e4',
            'heading_font'  => 'DM Serif Display',
            'body_font'     => 'Karla',
            'font_url'      => 'https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Karla:wght@300;400;500;600;700&display=swap',
            'footer_bg'     => '#292524, #44403c',
            'radius'        => '8px',
            'category'      => 'light',
        ],
        'wnb-frost' => [
            'name'          => 'WebnewBiz Frost',
            'description'   => 'Glassmorphism frosted header. For tech startups and SaaS apps.',
            'template'      => 'zenith',
            'header_style'  => 'glass',
            'primary'       => '#3b82f6',
            'primary_dark'  => '#2563eb',
            'secondary'     => '#a855f7',
            'accent'        => '#22d3ee',
            'text'          => '#e2e8f0',
            'text_light'    => '#94a3b8',
            'bg'            => '#0f172a',
            'bg_alt'        => '#1e293b',
            'border'        => '#334155',
            'heading_font'  => 'Plus Jakarta Sans',
            'body_font'     => 'Figtree',
            'font_url'      => 'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Figtree:wght@400;500;600;700&display=swap',
            'footer_bg'     => '#020617, #0f172a',
            'radius'        => '12px',
            'category'      => 'dark',
        ],
    ];

    /**
     * Get all theme configs for dropdowns.
     */
    public static function get_all_themes(): array {
        return self::$themes;
    }

    /**
     * Get a single theme config.
     */
    public static function get_theme(string $slug): ?array {
        return self::$themes[$slug] ?? null;
    }

    /**
     * Get the Elementor template style for a theme.
     */
    public static function get_template_style(string $theme_slug): string {
        return self::$themes[$theme_slug]['template'] ?? 'agency';
    }

    /**
     * Get theme colors as array for template hydration.
     */
    public static function get_colors(string $theme_slug): array {
        $t = self::$themes[$theme_slug] ?? self::$themes['wnb-agency'];
        return [
            'primary'   => $t['primary'],
            'secondary' => $t['secondary'],
            'accent'    => $t['accent'],
        ];
    }

    /**
     * Check if a theme is installed in wp-content/themes.
     */
    public static function is_installed(string $slug): bool {
        return is_dir(get_theme_root() . '/' . $slug);
    }

    /**
     * Install a theme by generating it into wp-content/themes/.
     */
    public static function install_theme(string $slug): bool {
        $config = self::$themes[$slug] ?? null;
        if (!$config) return false;

        $theme_dir = get_theme_root() . '/' . $slug;

        // Already installed — check for version/updates
        if (is_dir($theme_dir)) return true;

        // Create directories
        wp_mkdir_p($theme_dir . '/template-parts');

        $header_style = $config['header_style'] ?? 'standard';

        // Write style.css (with header-style-specific CSS)
        file_put_contents($theme_dir . '/style.css', self::generate_style_css($slug, $config));

        // Write functions.php
        file_put_contents($theme_dir . '/functions.php', self::generate_functions_php($slug, $config));

        // Write template PHP files (header varies by header_style)
        self::write_template_files($theme_dir, $slug, $header_style);

        return is_dir($theme_dir);
    }

    /**
     * Activate a theme.
     */
    public static function activate_theme(string $slug): bool {
        if (!self::is_installed($slug)) {
            if (!self::install_theme($slug)) {
                return false;
            }
        }

        switch_theme($slug);
        return get_stylesheet() === $slug;
    }

    /**
     * Install and activate a theme. Returns the template style for Elementor data.
     */
    public static function setup_theme(string $slug): string {
        self::activate_theme($slug);
        return self::get_template_style($slug);
    }

    /**
     * Get list of installed wnb themes.
     */
    public static function get_installed_themes(): array {
        $installed = [];
        foreach (self::$themes as $slug => $config) {
            if (self::is_installed($slug)) {
                $installed[$slug] = $config;
            }
        }
        return $installed;
    }

    // =========================================================================
    // File generators
    // =========================================================================

    private static function generate_style_css(string $slug, array $t): string {
        $is_dark = ($t['category'] ?? 'light') === 'dark';
        $nav_hover_bg = $is_dark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.04)';
        $nav_active_border = $is_dark ? 'rgba(255,255,255,0.15)' : $t['primary'];

        $css = <<<CSS
/*
Theme Name: {$t['name']}
Theme URI: https://webnewbiz.com
Description: {$t['description']}
Version: 1.0.0
Author: WebnewBiz
Author URI: https://webnewbiz.com
License: GNU General Public License v3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Text Domain: {$slug}
Tags: elementor, business, one-column, custom-logo, custom-menu, featured-images, full-width-template
Requires at least: 6.0
Requires PHP: 8.0
*/

:root {
    --wnb-primary: {$t['primary']};
    --wnb-primary-dark: {$t['primary_dark']};
    --wnb-secondary: {$t['secondary']};
    --wnb-accent: {$t['accent']};
    --wnb-text: {$t['text']};
    --wnb-text-light: {$t['text_light']};
    --wnb-bg: {$t['bg']};
    --wnb-bg-alt: {$t['bg_alt']};
    --wnb-border: {$t['border']};
    --wnb-font-heading: '{$t['heading_font']}', -apple-system, BlinkMacSystemFont, sans-serif;
    --wnb-font-body: '{$t['body_font']}', -apple-system, BlinkMacSystemFont, sans-serif;
    --wnb-radius: {$t['radius']};
    --wnb-container: 1200px;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { font-size: 16px; scroll-behavior: smooth; -webkit-text-size-adjust: 100%; }
body { font-family: var(--wnb-font-body); color: var(--wnb-text); background: var(--wnb-bg); line-height: 1.7; -webkit-font-smoothing: antialiased; }
h1, h2, h3, h4, h5, h6 { font-family: var(--wnb-font-heading); font-weight: 700; line-height: 1.3; color: var(--wnb-text); margin-bottom: 0.5em; }
h1 { font-size: 2.5rem; } h2 { font-size: 2rem; } h3 { font-size: 1.5rem; } h4 { font-size: 1.25rem; }
p { margin-bottom: 1.5em; }
a { color: var(--wnb-primary); text-decoration: none; transition: color 0.2s; }
a:hover { color: var(--wnb-primary-dark); }
img { max-width: 100%; height: auto; display: block; }
ul, ol { margin: 0 0 1.5em 1.5em; }
blockquote { border-left: 4px solid var(--wnb-primary); padding: 1em 1.5em; margin: 1.5em 0; background: var(--wnb-bg-alt); }
table { width: 100%; border-collapse: collapse; margin-bottom: 1.5em; }
th, td { padding: 0.75em 1em; border: 1px solid var(--wnb-border); }
th { background: var(--wnb-bg-alt); font-weight: 600; }
pre { background: var(--wnb-bg-alt); padding: 1.5em; border-radius: var(--wnb-radius); overflow-x: auto; margin-bottom: 1.5em; }
code { background: var(--wnb-bg-alt); padding: 0.15em 0.4em; border-radius: 3px; font-size: 0.9em; }

.screen-reader-text { clip: rect(1px,1px,1px,1px); position: absolute !important; height: 1px; width: 1px; overflow: hidden; }

.site-header { background: var(--wnb-bg); border-bottom: 1px solid var(--wnb-border); position: sticky; top: 0; z-index: 1000; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
.site-header-container { max-width: var(--wnb-container); margin: 0 auto; padding: 0 24px; display: flex; align-items: center; justify-content: space-between; height: 72px; }
.site-branding { display: flex; align-items: center; gap: 12px; }
.site-branding .custom-logo-link img { max-height: 45px; width: auto; }
.site-title { font-size: 1.4rem; font-weight: 700; margin: 0; }
.site-title-link { color: var(--wnb-text); text-decoration: none; }
.site-title-link:hover { color: var(--wnb-primary); }
.site-description { font-size: 0.8rem; color: var(--wnb-text-light); margin: 0; }

.main-navigation ul { list-style: none; margin: 0; padding: 0; display: flex; gap: 4px; }
.main-navigation li a { display: block; padding: 8px 16px; color: var(--wnb-text); font-size: 0.95rem; font-weight: 500; border-radius: var(--wnb-radius); transition: all 0.2s; }
.main-navigation li a:hover,
.main-navigation li.current-menu-item a { color: var(--wnb-primary); background: {$nav_hover_bg}; }
.menu-toggle { display: none; background: none; border: none; cursor: pointer; padding: 8px; flex-direction: column; gap: 5px; }
.menu-bar { display: block; width: 24px; height: 2px; background: var(--wnb-text); border-radius: 2px; transition: 0.3s; }

.site-content { min-height: calc(100vh - 72px - 200px); }
.content-area { max-width: var(--wnb-container); margin: 0 auto; padding: 40px 24px; }

.entry-header { margin-bottom: 1.5em; }
.entry-title { font-size: 2rem; }
.entry-title a { color: var(--wnb-text); }
.entry-title a:hover { color: var(--wnb-primary); }
.entry-meta { color: var(--wnb-text-light); font-size: 0.9rem; display: flex; gap: 16px; margin-top: 8px; }
.post-thumbnail { margin-bottom: 1.5em; border-radius: var(--wnb-radius); overflow: hidden; }
.post-thumbnail img { width: 100%; object-fit: cover; }
.entry-content { font-size: 1.05rem; line-height: 1.8; }
.entry-footer { margin-top: 2em; padding-top: 1em; border-top: 1px solid var(--wnb-border); font-size: 0.9rem; color: var(--wnb-text-light); }
article + article { margin-top: 3em; padding-top: 3em; border-top: 1px solid var(--wnb-border); }

.error-404 { text-align: center; padding: 80px 24px; }
.error-code { font-size: 8rem; font-weight: 800; line-height: 1; background: linear-gradient(135deg, var(--wnb-primary), var(--wnb-accent)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.error-404 .page-title { font-size: 1.5rem; margin-bottom: 12px; }
.error-description { color: var(--wnb-text-light); max-width: 480px; margin: 0 auto 24px; }
.error-home-link { display: inline-block; background: var(--wnb-primary); color: #fff; padding: 12px 32px; border-radius: var(--wnb-radius); font-weight: 600; }
.error-home-link:hover { background: var(--wnb-primary-dark); color: #fff; }

.search-form { display: flex; gap: 8px; max-width: 480px; }
.search-field { flex: 1; padding: 10px 16px; border: 1px solid var(--wnb-border); border-radius: var(--wnb-radius); font-size: 1rem; background: var(--wnb-bg); color: var(--wnb-text); }
.search-submit { padding: 10px 20px; background: var(--wnb-primary); color: #fff; border: none; border-radius: var(--wnb-radius); cursor: pointer; font-weight: 600; }

.comments-area { margin-top: 3em; }
.comment-list { list-style: none; margin: 0; padding: 0; }
.comment-list .comment { padding: 1.5em 0; border-bottom: 1px solid var(--wnb-border); }
.comment-author img { border-radius: 50%; float: left; margin-right: 12px; }
.comment-form input[type="text"], .comment-form input[type="email"], .comment-form input[type="url"], .comment-form textarea { width: 100%; padding: 10px 14px; border: 1px solid var(--wnb-border); border-radius: var(--wnb-radius); font-size: 1rem; background: var(--wnb-bg); color: var(--wnb-text); margin-bottom: 1em; }
.comment-form .form-submit input { background: var(--wnb-primary); color: #fff; border: none; padding: 12px 28px; border-radius: var(--wnb-radius); cursor: pointer; font-weight: 600; }

.widget { margin-bottom: 2em; }
.widget-title { font-size: 1.1rem; font-weight: 600; margin-bottom: 1em; padding-bottom: 0.5em; border-bottom: 2px solid var(--wnb-primary); }

.site-footer { background: linear-gradient(135deg, {$t['footer_bg']}); color: rgba(255,255,255,0.8); padding: 40px 0 24px; }
.site-footer-container { max-width: var(--wnb-container); margin: 0 auto; padding: 0 24px; }
.footer-widgets { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 32px; margin-bottom: 32px; padding-bottom: 32px; border-bottom: 1px solid rgba(255,255,255,0.1); }
.footer-widget-area .widget-title { color: #fff; border-bottom-color: var(--wnb-accent); }
.footer-widget-area a { color: rgba(255,255,255,0.7); }
.footer-widget-area a:hover { color: #fff; }
.site-info { text-align: center; font-size: 0.9rem; }
.site-info p { margin: 8px 0 0; }
#footer-menu { list-style: none; margin: 0; padding: 0; display: flex; justify-content: center; gap: 24px; flex-wrap: wrap; }
#footer-menu a { color: rgba(255,255,255,0.7); }
#footer-menu a:hover { color: #fff; }

.elementor-page .site-content { padding: 0; }
.elementor-page .content-area { padding: 0; max-width: none; }

@media (max-width: 768px) {
    .site-header-container { height: 60px; }
    .menu-toggle { display: flex; }
    .main-navigation ul { display: none; position: absolute; top: 60px; left: 0; right: 0; background: var(--wnb-bg); flex-direction: column; padding: 12px 24px; border-bottom: 1px solid var(--wnb-border); box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
    .main-navigation ul.toggled { display: flex; }
    .main-navigation li a { padding: 12px 0; }
    h1 { font-size: 2rem; } h2 { font-size: 1.5rem; }
    .error-code { font-size: 5rem; }
    .footer-widgets { grid-template-columns: 1fr; }
}
@media (max-width: 480px) { h1 { font-size: 1.75rem; } .content-area { padding: 24px 16px; } }

.alignleft { float: left; margin-right: 1.5em; margin-bottom: 1em; }
.alignright { float: right; margin-left: 1.5em; margin-bottom: 1em; }
.aligncenter { display: block; margin: 0 auto 1em; }
.wp-caption-text { font-size: 0.85rem; color: var(--wnb-text-light); text-align: center; }
.sticky .entry-title::before { content: "\\2605"; color: var(--wnb-accent); margin-right: 8px; }
CSS;

        // Append header-style-specific CSS overrides
        $header_style = $t['header_style'] ?? 'standard';
        $css .= self::get_header_style_css($header_style, $t);

        return $css;
    }

    /**
     * Generate CSS overrides for specific header styles.
     */
    private static function get_header_style_css(string $style, array $t): string {
        switch ($style) {
            case 'topbar':
                return <<<CSS

/* Header - Top Bar */
.site-topbar { background: var(--wnb-bg-alt); border-bottom: 1px solid var(--wnb-border); padding: 6px 0; font-size: 0.8rem; color: var(--wnb-text-light); }
.site-topbar-container { max-width: var(--wnb-container); margin: 0 auto; padding: 0 24px; display: flex; align-items: center; justify-content: space-between; }
.topbar-info { display: flex; gap: 20px; align-items: center; }
.topbar-info span { display: flex; align-items: center; gap: 6px; }
.site-header-container { height: 68px; }
@media (max-width: 768px) { .site-topbar { display: none; } }
CSS;

            case 'centered':
                return <<<CSS

/* Header - Centered */
.site-header { border-bottom: 1px solid var(--wnb-border); box-shadow: none; }
.site-header-container { flex-direction: column; align-items: center; gap: 12px; height: auto; padding: 16px 24px; }
.site-branding { text-align: center; }
.site-title { font-size: 1.8rem; }
.main-navigation ul { justify-content: center; }
.main-navigation li a { padding: 8px 20px; letter-spacing: 0.03em; text-transform: uppercase; font-size: 0.85rem; }
@media (max-width: 768px) { .site-header-container { padding: 12px 24px; gap: 8px; } }
CSS;

            case 'bold':
                return <<<CSS

/* Header - Bold Industrial */
.site-header { border-bottom: 4px solid var(--wnb-primary); box-shadow: none; }
.site-header-container { height: 76px; }
.site-title { font-size: 1.6rem; text-transform: uppercase; letter-spacing: 0.05em; }
.main-navigation li a { text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.05em; font-weight: 700; border-bottom: 3px solid transparent; border-radius: 0; }
.main-navigation li a:hover, .main-navigation li.current-menu-item a { border-bottom-color: var(--wnb-primary); color: var(--wnb-primary); background: none; }
CSS;

            case 'minimal':
                return <<<CSS

/* Header - Minimal Luxury */
.site-header { border-bottom: none; box-shadow: none; }
.site-header-container { height: 64px; }
.site-title { font-size: 1.3rem; letter-spacing: 0.15em; text-transform: uppercase; font-weight: 400; }
.main-navigation li a { font-size: 0.8rem; letter-spacing: 0.1em; text-transform: uppercase; font-weight: 500; color: var(--wnb-text-light); }
.main-navigation li a:hover, .main-navigation li.current-menu-item a { color: var(--wnb-text); background: none; }
.site-header::after { content: ''; display: block; height: 1px; background: var(--wnb-border); position: absolute; bottom: 0; left: 5%; right: 5%; }
CSS;

            case 'gradient':
                return <<<CSS

/* Header - Gradient */
.site-header { background: linear-gradient(135deg, var(--wnb-primary), var(--wnb-secondary)); border-bottom: none; box-shadow: 0 4px 20px rgba(0,0,0,0.15); }
.site-title, .site-title-link, .site-title-link:hover { color: #fff !important; -webkit-text-fill-color: #fff; }
.site-description { color: rgba(255,255,255,0.8); }
.main-navigation li a { color: rgba(255,255,255,0.9); }
.main-navigation li a:hover, .main-navigation li.current-menu-item a { color: #fff; background: rgba(255,255,255,0.15); }
.menu-bar { background: #fff; }
@media (max-width: 768px) { .main-navigation ul { background: var(--wnb-primary); } }
CSS;

            case 'transparent':
                return <<<CSS

/* Header - Transparent */
.site-header { background: rgba(15,10,30,0.85); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border-bottom: 1px solid rgba(255,255,255,0.08); box-shadow: none; }
.site-title, .site-title-link { color: #fff !important; }
.site-description { color: rgba(255,255,255,0.6); }
.main-navigation li a { color: rgba(255,255,255,0.8); }
.main-navigation li a:hover, .main-navigation li.current-menu-item a { color: #fff; background: rgba(255,255,255,0.1); }
.menu-bar { background: #fff; }
@media (max-width: 768px) { .main-navigation ul { background: rgba(15,10,30,0.95); } }
CSS;

            case 'pill':
                return <<<CSS

/* Header - Pill Nav */
.main-navigation ul { background: var(--wnb-bg-alt); padding: 4px; border-radius: 99px; gap: 2px; }
.main-navigation li a { padding: 8px 20px; border-radius: 99px; font-size: 0.9rem; }
.main-navigation li a:hover, .main-navigation li.current-menu-item a { background: var(--wnb-primary); color: #fff; }
@media (max-width: 768px) { .main-navigation ul { background: var(--wnb-bg); border-radius: 0; padding: 12px 24px; } }
CSS;

            case 'bordered':
                return <<<CSS

/* Header - Bordered Regal */
.site-header { border-bottom: 2px solid var(--wnb-border); box-shadow: none; }
.site-header::before { content: ''; display: block; height: 4px; background: linear-gradient(90deg, var(--wnb-primary), var(--wnb-accent), var(--wnb-primary)); }
.site-header-container { height: 76px; }
.site-title { font-size: 1.6rem; letter-spacing: 0.08em; }
.main-navigation li a { border: 1px solid transparent; }
.main-navigation li a:hover, .main-navigation li.current-menu-item a { border-color: var(--wnb-primary); color: var(--wnb-primary); background: transparent; }
CSS;

            case 'large':
                return <<<CSS

/* Header - Large with Tagline */
.site-header-container { height: 84px; }
.site-branding { flex-direction: column; gap: 2px; align-items: flex-start; }
.site-title { font-size: 1.7rem; line-height: 1.1; }
.site-description { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; display: block !important; }
@media (max-width: 768px) { .site-header-container { height: 68px; } .site-title { font-size: 1.3rem; } }
CSS;

            case 'glass':
                return <<<CSS

/* Header - Glassmorphism */
.site-header { background: rgba(15,23,42,0.6); backdrop-filter: blur(20px) saturate(180%); -webkit-backdrop-filter: blur(20px) saturate(180%); border-bottom: 1px solid rgba(255,255,255,0.08); box-shadow: none; }
.site-title, .site-title-link { color: #fff !important; }
.site-description { color: rgba(255,255,255,0.6); }
.main-navigation li a { color: rgba(255,255,255,0.8); }
.main-navigation li a:hover, .main-navigation li.current-menu-item a { color: #fff; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15); }
.menu-bar { background: #fff; }
@media (max-width: 768px) { .main-navigation ul { background: rgba(15,23,42,0.95); backdrop-filter: blur(20px); } }
CSS;

            default: // 'standard'
                return '';
        }
    }

    private static function generate_functions_php(string $slug, array $t): string {
        $font_url = $t['font_url'];
        return <<<'PHP'
<?php
if (!defined('ABSPATH')) exit;

define('WNB_THEME_VERSION', '1.0.0');

function wnb_theme_setup() {
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('custom-background', ['default-color' => 'ffffff']);
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('custom-logo', ['height' => 250, 'width' => 250, 'flex-width' => true, 'flex-height' => true]);
    register_nav_menus(['header_menu' => 'Header Menu', 'footer_menu' => 'Footer Menu']);
    add_theme_support('elementor');
    add_theme_support('header-footer-elementor');
}
add_action('after_setup_theme', 'wnb_theme_setup');

function wnb_theme_content_width() { $GLOBALS['content_width'] = 960; }
add_action('after_setup_theme', 'wnb_theme_content_width', 0);

function wnb_theme_widgets_init() {
    $args = ['before_widget' => '<section id="%1$s" class="widget %2$s">', 'after_widget' => '</section>', 'before_title' => '<h3 class="widget-title">', 'after_title' => '</h3>'];
    register_sidebar(array_merge($args, ['name' => 'Sidebar', 'id' => 'sidebar-1']));
    register_sidebar(array_merge($args, ['name' => 'Footer 1', 'id' => 'footer-1']));
    register_sidebar(array_merge($args, ['name' => 'Footer 2', 'id' => 'footer-2']));
}
add_action('widgets_init', 'wnb_theme_widgets_init');

function wnb_theme_scripts() {
PHP
        . "\n    wp_enqueue_style('wnb-fonts', '{$font_url}', [], null);\n" .
<<<'PHP'
    wp_enqueue_style('wnb-style', get_stylesheet_uri(), [], WNB_THEME_VERSION);
}
add_action('wp_enqueue_scripts', 'wnb_theme_scripts');

function wnb_elementor_settings() {
    update_option('elementor_disable_color_schemes', 'yes');
    update_option('elementor_disable_typography_schemes', 'yes');
}
add_action('after_switch_theme', 'wnb_elementor_settings');

function wnb_theme_body_classes($classes) {
    if (!is_singular()) $classes[] = 'hfeed';
    if (!is_active_sidebar('sidebar-1')) $classes[] = 'no-sidebar';
    return $classes;
}
add_filter('body_class', 'wnb_theme_body_classes');

function wnb_menu_toggle_script() { ?>
<script>document.addEventListener('DOMContentLoaded',function(){var t=document.querySelector('.menu-toggle'),m=document.getElementById('primary-menu');if(t&&m)t.addEventListener('click',function(){m.classList.toggle('toggled');t.setAttribute('aria-expanded',t.getAttribute('aria-expanded')!=='true')})});</script>
<?php }
add_action('wp_footer', 'wnb_menu_toggle_script');
PHP;
    }

    private static function write_template_files(string $dir, string $slug, string $header_style = 'standard'): void {
        // header.php — varies by header_style
        file_put_contents($dir . '/header.php', self::generate_header_php($header_style));

        // footer.php
        file_put_contents($dir . '/footer.php', '    </div>
    <footer id="colophon" class="site-footer">
        <div class="site-footer-container">
            <?php if (is_active_sidebar(\'footer-1\') || is_active_sidebar(\'footer-2\')): ?>
            <div class="footer-widgets">
                <?php if (is_active_sidebar(\'footer-1\')): ?><div class="footer-widget-area"><?php dynamic_sidebar(\'footer-1\'); ?></div><?php endif; ?>
                <?php if (is_active_sidebar(\'footer-2\')): ?><div class="footer-widget-area"><?php dynamic_sidebar(\'footer-2\'); ?></div><?php endif; ?>
            </div>
            <?php endif; ?>
            <div class="site-info">
                <?php wp_nav_menu([\'theme_location\' => \'footer_menu\', \'menu_id\' => \'footer-menu\', \'fallback_cb\' => false, \'container\' => false, \'depth\' => 1]); ?>
                <p>&copy; <?php echo date(\'Y\'); ?> <?php bloginfo(\'name\'); ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>
</div>
<?php wp_footer(); ?>
</body>
</html>
');

        // index.php + page.php (Elementor-aware)
        $elementor_check = '<?php
get_header();
$is_elementor = false;
if (defined(\'ELEMENTOR_VERSION\')) {
    $doc = \\Elementor\\Plugin::instance()->documents->get(get_the_ID());
    if ($doc && !is_bool($doc) && $doc->is_built_with_elementor()) $is_elementor = true;
}
if ($is_elementor) { while (have_posts()): the_post(); the_content(); endwhile; } else { ?>
<div id="primary" class="content-area"><main id="main" class="site-main">';

        file_put_contents($dir . '/index.php', $elementor_check . '
<?php if (have_posts()): while (have_posts()): the_post(); get_template_part(\'template-parts/content\', get_post_type()); endwhile; the_posts_navigation(); else: get_template_part(\'template-parts/content\', \'none\'); endif; ?>
</main></div><?php } get_footer();');

        file_put_contents($dir . '/page.php', $elementor_check . '
<?php while (have_posts()): the_post(); get_template_part(\'template-parts/content\', \'page\'); if (comments_open() || get_comments_number()) comments_template(); endwhile; ?>
</main></div><?php } get_footer();');

        // single.php
        file_put_contents($dir . '/single.php', '<?php get_header(); ?>
<div id="primary" class="content-area"><main id="main" class="site-main">
<?php while (have_posts()): the_post(); get_template_part(\'template-parts/content\', get_post_type()); the_post_navigation(); if (comments_open() || get_comments_number()) comments_template(); endwhile; ?>
</main></div>
<?php get_footer();');

        // 404.php
        file_put_contents($dir . '/404.php', '<?php get_header(); ?>
<div id="primary" class="content-area"><main id="main" class="site-main">
<section class="error-404 not-found"><div class="error-404-content">
<h1 class="error-code">404</h1>
<h2 class="page-title">Page Not Found</h2>
<p class="error-description">The page you are looking for might have been removed or is temporarily unavailable.</p>
<a href="<?php echo esc_url(home_url(\'/\')); ?>" class="error-home-link">Back to Homepage</a>
</div></section></main></div>
<?php get_footer();');

        // search.php
        file_put_contents($dir . '/search.php', '<?php get_header(); ?>
<div id="primary" class="content-area"><main id="main" class="site-main">
<?php if (have_posts()): ?><header class="page-header"><h1 class="page-title">Search Results for: <span><?php echo get_search_query(); ?></span></h1></header>
<?php while (have_posts()): the_post(); get_template_part(\'template-parts/content\', \'search\'); endwhile; the_posts_navigation();
else: get_template_part(\'template-parts/content\', \'none\'); endif; ?>
</main></div>
<?php get_footer();');

        // sidebar.php
        file_put_contents($dir . '/sidebar.php', '<?php if (!is_active_sidebar(\'sidebar-1\')) return; ?>
<aside id="secondary" class="widget-area"><?php dynamic_sidebar(\'sidebar-1\'); ?></aside>');

        // comments.php
        file_put_contents($dir . '/comments.php', '<?php if (post_password_required()) return; ?>
<div id="comments" class="comments-area">
<?php if (have_comments()): ?><h2 class="comments-title"><?php printf(_n(\'%s Comment\', \'%s Comments\', get_comments_number()), number_format_i18n(get_comments_number())); ?></h2>
<ol class="comment-list"><?php wp_list_comments([\'style\' => \'ol\', \'short_ping\' => true, \'avatar_size\' => 48]); ?></ol>
<?php the_comments_navigation(); endif; comment_form(); ?>
</div>');

        // template-parts/content.php
        file_put_contents($dir . '/template-parts/content.php', '<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<header class="entry-header">
<?php if (is_singular()): the_title(\'<h1 class="entry-title">\', \'</h1>\'); else: the_title(\'<h2 class="entry-title"><a href="\' . esc_url(get_permalink()) . \'">\', \'</a></h2>\'); endif;
if (\'post\' === get_post_type()): ?><div class="entry-meta"><span class="posted-on"><?php echo get_the_date(); ?></span><span class="byline"><?php the_author(); ?></span></div><?php endif; ?>
</header>
<?php if (has_post_thumbnail()): ?><div class="post-thumbnail"><?php the_post_thumbnail(\'large\'); ?></div><?php endif; ?>
<div class="entry-content"><?php if (is_singular()): the_content(); wp_link_pages(); else: the_excerpt(); endif; ?></div>
<footer class="entry-footer"><?php edit_post_link(\'Edit\', \'<span class="edit-link">\', \'</span>\'); ?></footer>
</article>');

        // template-parts/content-page.php
        file_put_contents($dir . '/template-parts/content-page.php', '<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<header class="entry-header"><?php the_title(\'<h1 class="entry-title">\', \'</h1>\'); ?></header>
<div class="entry-content"><?php the_content(); wp_link_pages(); ?></div>
</article>');

        // template-parts/content-search.php
        file_put_contents($dir . '/template-parts/content-search.php', '<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<header class="entry-header"><?php the_title(\'<h2 class="entry-title"><a href="\' . esc_url(get_permalink()) . \'">\', \'</a></h2>\'); ?>
<?php if (\'post\' === get_post_type()): ?><div class="entry-meta"><span><?php echo get_the_date(); ?></span></div><?php endif; ?>
</header>
<div class="entry-summary"><?php the_excerpt(); ?></div>
</article>');

        // template-parts/content-none.php
        file_put_contents($dir . '/template-parts/content-none.php', '<section class="no-results not-found">
<header class="page-header"><h1 class="page-title">Nothing Found</h1></header>
<div class="page-content"><p>Sorry, no results found. Please try again.</p><?php get_search_form(); ?></div>
</section>');
    }

    /**
     * Generate header.php with different layouts per header_style.
     */
    private static function generate_header_php(string $style): string {
        $head = '<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo(\'charset\'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#content">Skip to content</a>
';

        $branding = '            <div class="site-branding">
                <?php if (has_custom_logo()): the_custom_logo(); else: ?>
                    <a href="<?php echo esc_url(home_url(\'/\')); ?>" class="site-title-link"><h1 class="site-title"><?php bloginfo(\'name\'); ?></h1></a>
                <?php endif; $d = get_bloginfo(\'description\', \'display\'); if ($d): ?><p class="site-description"><?php echo $d; ?></p><?php endif; ?>
            </div>';

        $nav = '            <nav id="site-navigation" class="main-navigation">
                <?php wp_nav_menu([\'theme_location\' => \'header_menu\', \'menu_id\' => \'primary-menu\', \'fallback_cb\' => false, \'container\' => false]); ?>
                <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><span class="menu-bar"></span><span class="menu-bar"></span><span class="menu-bar"></span></button>
            </nav>';

        $content_open = '    <div id="content" class="site-content">
';

        // Topbar header has an extra bar above the main header
        if ($style === 'topbar') {
            $topbar = '    <div class="site-topbar">
        <div class="site-topbar-container">
            <div class="topbar-info">
                <span>&#9742; <?php echo get_theme_mod(\'wnb_phone\', \'+1 (555) 123-4567\'); ?></span>
                <span>&#9993; <?php echo get_theme_mod(\'wnb_email\', \'info@example.com\'); ?></span>
            </div>
            <div class="topbar-info">
                <span><?php echo get_theme_mod(\'wnb_hours\', \'Mon-Fri: 9AM - 5PM\'); ?></span>
            </div>
        </div>
    </div>
';
            return $head . $topbar . '    <header id="masthead" class="site-header">
        <div class="site-header-container">
' . $branding . '
' . $nav . '
        </div>
    </header>
' . $content_open;
        }

        // All other styles use the same HTML structure — CSS handles the visual difference
        return $head . '    <header id="masthead" class="site-header">
        <div class="site-header-container">
' . $branding . '
' . $nav . '
        </div>
    </header>
' . $content_open;
    }
}
