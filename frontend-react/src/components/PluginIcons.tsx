import { useState } from 'react';

/**
 * Loads real plugin logos from WordPress.org plugin directory.
 * Falls back to a colored letter badge if the image fails.
 */

const SLUG_COLORS: Record<string, string> = {
  elementor: '#93003A',
  'elementor-pro': '#93003A',
  woocommerce: '#7F54B3',
  'header-footer-elementor': '#F06B35',
  'ultimate-elementor': '#F06B35',
};

const FALLBACK_COLORS = [
  '#2563EB', '#7C3AED', '#DC2626', '#059669', '#D97706',
  '#DB2777', '#4F46E5', '#0891B2', '#65A30D', '#EA580C',
];

function colorForSlug(slug: string): string {
  if (SLUG_COLORS[slug]) return SLUG_COLORS[slug];
  let hash = 0;
  for (let i = 0; i < slug.length; i++) hash = slug.charCodeAt(i) + ((hash << 5) - hash);
  return FALLBACK_COLORS[Math.abs(hash) % FALLBACK_COLORS.length];
}

/* Premium plugins that aren't on wp.org — map to their free counterpart's icon */
const SLUG_ALIAS: Record<string, string> = {
  'elementor-pro': 'elementor',
};

/* Custom plugins with local or inline icons (not on wp.org) */
const CUSTOM_ICON_SLUGS = new Set(['webnewbiz-builder']);

/* Some plugins use different icon formats on wp.org */
const ICON_OVERRIDES: Record<string, string> = {
  elementor: 'https://ps.w.org/elementor/assets/icon.svg',
  woocommerce: 'https://ps.w.org/woocommerce/assets/icon-128x128.gif',
  'header-footer-elementor': 'https://ps.w.org/header-footer-elementor/assets/icon-128x128.jpg',
};

function getIconUrl(slug: string): string {
  const resolved = SLUG_ALIAS[slug] || slug;
  if (ICON_OVERRIDES[resolved]) return ICON_OVERRIDES[resolved];
  return `https://ps.w.org/${resolved}/assets/icon-128x128.png`;
}

function WebNewBizIcon({ size }: { size: number }) {
  const r = size > 36 ? 10 : 8;
  return (
    <svg width={size} height={size} viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" style={{ borderRadius: r, flexShrink: 0, display: 'block', overflow: 'hidden' }}>
      <rect width="64" height="64" rx="14" fill="url(#wnb-bg)"/>
      {/* Left block — slanted right edge */}
      <path d="M12 27C12 24.2 14.2 22 17 22H30L22 42H17C14.2 42 12 39.8 12 37V27Z" fill="#1a1a2e"/>
      {/* Right block — slanted left edge */}
      <path d="M26 42L34 22H47C49.8 22 52 24.2 52 27V37C52 39.8 49.8 42 47 42H26Z" fill="#1a1a2e"/>
      <defs>
        <linearGradient id="wnb-bg" x1="0" y1="0" x2="64" y2="64">
          <stop offset="0%" stopColor="#f5f0e6"/>
          <stop offset="100%" stopColor="#e8e0d0"/>
        </linearGradient>
      </defs>
    </svg>
  );
}

function LetterFallback({ name, slug, size }: { name: string; slug: string; size: number }) {
  const letter = (name || slug || '?')[0].toUpperCase();
  return (
    <div style={{
      width: size, height: size, borderRadius: size > 36 ? 10 : 8,
      background: colorForSlug(slug),
      display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0,
      color: '#fff', fontWeight: 700, fontSize: size * 0.45, lineHeight: 1,
    }}>
      {letter}
    </div>
  );
}

export function PluginIconBadge({ slug, name, size = 32 }: { slug: string; name?: string; size?: number }) {
  const [failed, setFailed] = useState(false);
  const radius = size > 36 ? 10 : 8;

  if (CUSTOM_ICON_SLUGS.has(slug)) {
    return <WebNewBizIcon size={size} />;
  }

  if (failed) {
    return <LetterFallback name={name || slug} slug={slug} size={size} />;
  }

  return (
    <img
      src={getIconUrl(slug)}
      alt=""
      onError={() => setFailed(true)}
      style={{
        width: size, height: size, borderRadius: radius,
        objectFit: 'cover', flexShrink: 0, display: 'block',
      }}
    />
  );
}

export function ThemeIconBadge({ screenshot, name, size = 32 }: { screenshot?: string; name?: string; size?: number }) {
  const [failed, setFailed] = useState(false);
  const radius = size > 36 ? 10 : 8;

  if (screenshot && !failed) {
    return (
      <img
        src={screenshot}
        alt=""
        onError={() => setFailed(true)}
        style={{
          width: size, height: size, borderRadius: radius,
          objectFit: 'cover', flexShrink: 0, display: 'block',
        }}
      />
    );
  }

  const letter = (name || '?')[0].toUpperCase();
  return (
    <div style={{
      width: size, height: size, borderRadius: radius,
      background: '#6366F1',
      display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0,
      color: '#fff', fontWeight: 700, fontSize: size * 0.45, lineHeight: 1,
    }}>
      {letter}
    </div>
  );
}