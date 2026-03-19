import { useState, useEffect } from 'react';
import { useOutletContext } from 'react-router-dom';
import { builderPluginService } from '../../services/builder-plugin.service';

interface PerfSettings {
  disable_emojis: boolean;
  disable_embeds: boolean;
  remove_jquery_migrate: boolean;
  minify_html: boolean;
  lazy_load_images: boolean;
  lazy_load_iframes: boolean;
  dns_prefetch: boolean;
  remove_query_strings: boolean;
  disable_heartbeat: boolean;
  disable_self_pingbacks: boolean;
  disable_rss: boolean;
  preload_fonts: boolean;
}

interface CacheSettings {
  auto_purge_on_save: boolean;
  auto_purge_on_update: boolean;
  browser_cache_enabled: boolean;
}

const defaultPerf: PerfSettings = {
  disable_emojis: false,
  disable_embeds: false,
  remove_jquery_migrate: false,
  minify_html: false,
  lazy_load_images: false,
  lazy_load_iframes: false,
  dns_prefetch: false,
  remove_query_strings: false,
  disable_heartbeat: false,
  disable_self_pingbacks: false,
  disable_rss: false,
  preload_fonts: false,
};

const defaultCache: CacheSettings = {
  auto_purge_on_save: false,
  auto_purge_on_update: false,
  browser_cache_enabled: false,
};

const perfLabels: Record<keyof PerfSettings, { title: string; desc: string }> = {
  disable_emojis: { title: 'Disable Emojis', desc: 'Remove WordPress emoji scripts and styles' },
  disable_embeds: { title: 'Disable Embeds', desc: 'Remove oEmbed discovery and related scripts' },
  remove_jquery_migrate: { title: 'Remove jQuery Migrate', desc: 'Remove the legacy jQuery Migrate script' },
  minify_html: { title: 'Minify HTML', desc: 'Reduce page size by minifying HTML output' },
  lazy_load_images: { title: 'Lazy Load Images', desc: 'Defer off-screen images for faster load' },
  lazy_load_iframes: { title: 'Lazy Load Iframes', desc: 'Defer iframes until they enter viewport' },
  dns_prefetch: { title: 'DNS Prefetch', desc: 'Pre-resolve external domain DNS lookups' },
  remove_query_strings: { title: 'Remove Query Strings', desc: 'Strip version queries from static resources' },
  disable_heartbeat: { title: 'Disable Heartbeat', desc: 'Stop admin-ajax heartbeat polling' },
  disable_self_pingbacks: { title: 'Disable Self Pingbacks', desc: 'Prevent internal pingback requests' },
  disable_rss: { title: 'Disable RSS Feeds', desc: 'Remove RSS feed links from your site' },
  preload_fonts: { title: 'Preload Fonts', desc: 'Preload key fonts for faster text rendering' },
};

const cacheLabels: Record<keyof CacheSettings, { title: string; desc: string }> = {
  auto_purge_on_save: { title: 'Auto-Purge on Save', desc: 'Clear cache when posts are saved' },
  auto_purge_on_update: { title: 'Auto-Purge on Update', desc: 'Clear cache when plugins/themes update' },
  browser_cache_enabled: { title: 'Browser Cache', desc: 'Set long-lived cache headers for assets' },
};

export default function BoosterMain() {
  const { website } = useOutletContext<{ website: any }>();
  const websiteId = website.id;

  const [perf, setPerf] = useState<PerfSettings>(defaultPerf);
  const [cache, setCache] = useState<CacheSettings>(defaultCache);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [toast, setToast] = useState('');
  const [purging, setPurging] = useState('');

  useEffect(() => {
    let cancelled = false;
    setLoading(true);
    Promise.all([
      builderPluginService.getPerformance(websiteId).catch(() => null),
      builderPluginService.getCacheStats(websiteId).catch(() => null),
    ]).then(([perfRes, cacheRes]) => {
      if (cancelled) return;
      if (perfRes?.data?.settings) setPerf({ ...defaultPerf, ...perfRes.data.settings });
      if (cacheRes?.data?.settings) setCache({ ...defaultCache, ...cacheRes.data.settings });
    }).catch(() => {
      if (!cancelled) setError('Failed to load settings');
    }).finally(() => {
      if (!cancelled) setLoading(false);
    });
    return () => { cancelled = true; };
  }, [websiteId]);

  const showToast = (msg: string) => {
    setToast(msg);
    setTimeout(() => setToast(''), 2500);
  };

  const togglePerf = async (key: keyof PerfSettings) => {
    const updated = { ...perf, [key]: !perf[key] };
    setPerf(updated);
    try {
      await builderPluginService.savePerformance(websiteId, updated as any);
      showToast(`${perfLabels[key].title} ${updated[key] ? 'enabled' : 'disabled'}`);
    } catch {
      setPerf(perf);
      setError('Failed to save setting');
    }
  };

  const toggleCache = async (key: keyof CacheSettings) => {
    const updated = { ...cache, [key]: !cache[key] };
    setCache(updated);
    try {
      await builderPluginService.saveCacheSettings(websiteId, updated as any);
      showToast(`${cacheLabels[key].title} ${updated[key] ? 'enabled' : 'disabled'}`);
    } catch {
      setCache(cache);
      setError('Failed to save cache setting');
    }
  };

  const handlePurge = async (type: string) => {
    setPurging(type);
    try {
      await builderPluginService.purgeCache(websiteId, type);
      showToast(`${type} cache purged successfully`);
    } catch {
      setError('Failed to purge cache');
    } finally {
      setPurging('');
    }
  };

  const activeCount = Object.values(perf).filter(Boolean).length;
  const perfScore = Math.round((activeCount / 12) * 100);

  if (loading) {
    return (
      <div style={s.loadingWrap}>
        <div style={s.spinner} />
        <p style={{ color: '#6B7280', fontSize: 13 }}>Loading booster settings...</p>
        <style>{spinCss}</style>
      </div>
    );
  }

  return (
    <div style={s.page}>
      {/* Toast */}
      {toast && <div style={s.toast}>{toast}</div>}

      {/* Error */}
      {error && (
        <div style={s.errorBar}>
          {error}
          <button style={s.errorClose} onClick={() => setError('')}>&times;</button>
        </div>
      )}

      {/* Header */}
      <div style={s.header}>
        <div>
          <h1 style={s.h1}>Website Booster</h1>
          <p style={s.subtitle}>Optimize performance and caching for faster load times</p>
        </div>
        <span style={s.premiumBadge}>Premium</span>
      </div>

      {/* Stats Row */}
      <div style={s.statsRow} className="bm-stats-row">
        <div style={s.statCard}>
          <div style={s.statIcon}>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#7c5cfc" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
              <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
          </div>
          <div style={s.statNum}>{activeCount}</div>
          <div style={s.statLabel}>Active Optimizations</div>
        </div>
        <div style={s.statCard}>
          <div style={s.statIcon}>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#7c5cfc" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
              <path d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
            </svg>
          </div>
          <div style={s.statNum}>{cache.auto_purge_on_save || cache.auto_purge_on_update ? 'Active' : 'Inactive'}</div>
          <div style={s.statLabel}>Cache Status</div>
        </div>
        <div style={s.statCard}>
          <div style={s.statIcon}>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#7c5cfc" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
              <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
            </svg>
          </div>
          <div style={s.statNum}>{cache.browser_cache_enabled ? 'Enabled' : 'Disabled'}</div>
          <div style={s.statLabel}>Browser Cache</div>
        </div>
        <div style={s.statCard}>
          <div style={s.statIcon}>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#7c5cfc" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
              <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
            </svg>
          </div>
          <div style={{ ...s.statNum, color: perfScore >= 70 ? '#10B981' : perfScore >= 40 ? '#F59E0B' : '#EF4444' }}>{perfScore}%</div>
          <div style={s.statLabel}>Performance Score</div>
        </div>
      </div>

      {/* Performance Section */}
      <div style={s.card}>
        <div style={s.cardHead}>
          <h2 style={s.cardTitle}>Performance Optimizations</h2>
          <span style={s.countBadge}>{activeCount} of 12 active</span>
        </div>
        <div style={s.toggleGrid} className="bm-toggle-grid">
          {(Object.keys(perfLabels) as (keyof PerfSettings)[]).map((key) => (
            <div key={key} style={s.toggleRow}>
              <div style={s.toggleInfo}>
                <div style={s.toggleTitle}>{perfLabels[key].title}</div>
                <div style={s.toggleDesc}>{perfLabels[key].desc}</div>
              </div>
              <button
                style={{ ...s.toggle, ...(perf[key] ? s.toggleOn : {}) }}
                className="bm-toggle"
                onClick={() => togglePerf(key)}
                role="switch"
                aria-checked={perf[key]}
              >
                <span style={{ ...s.toggleKnob, ...(perf[key] ? s.toggleKnobOn : {}) }} />
              </button>
            </div>
          ))}
        </div>
      </div>

      {/* Cache Section */}
      <div style={s.card}>
        <div style={s.cardHead}>
          <h2 style={s.cardTitle}>Cache Management</h2>
        </div>

        {/* Purge Buttons */}
        <div style={s.purgeRow} className="bm-purge-row">
          {[
            { type: 'all', label: 'Purge All Cache', icon: 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16' },
            { type: 'elementor', label: 'Elementor CSS', icon: 'M15.232 5.232l3.536 3.536M4 20h4.586a1 1 0 00.707-.293l10.9-10.9a2 2 0 000-2.828l-2.172-2.172a2 2 0 00-2.828 0l-10.9 10.9A1 1 0 004 15.414V20z' },
            { type: 'object', label: 'Object Cache', icon: 'M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4' },
            { type: 'transients', label: 'Transients', icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z' },
          ].map((item) => (
            <button
              key={item.type}
              style={s.purgeBtn}
              className="bm-purge-btn"
              onClick={() => handlePurge(item.type)}
              disabled={purging === item.type}
            >
              {purging === item.type ? (
                <div style={{ ...s.spinner, width: 16, height: 16, borderWidth: 2 }} />
              ) : (
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                  <path d={item.icon} />
                </svg>
              )}
              {item.label}
            </button>
          ))}
        </div>

        {/* Cache Settings Toggles */}
        <div style={{ marginTop: 24 }}>
          <h3 style={s.subTitle}>Cache Settings</h3>
          {(Object.keys(cacheLabels) as (keyof CacheSettings)[]).map((key) => (
            <div key={key} style={s.toggleRow}>
              <div style={s.toggleInfo}>
                <div style={s.toggleTitle}>{cacheLabels[key].title}</div>
                <div style={s.toggleDesc}>{cacheLabels[key].desc}</div>
              </div>
              <button
                style={{ ...s.toggle, ...(cache[key] ? s.toggleOn : {}) }}
                className="bm-toggle"
                onClick={() => toggleCache(key)}
                role="switch"
                aria-checked={cache[key]}
              >
                <span style={{ ...s.toggleKnob, ...(cache[key] ? s.toggleKnobOn : {}) }} />
              </button>
            </div>
          ))}
        </div>
      </div>

      <style>{cssStr}</style>
    </div>
  );
}

const spinCss = `@keyframes bm-spin { to { transform: rotate(360deg); } }`;

const cssStr = `
  ${spinCss}
  .bm-stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 20px;
  }
  .bm-toggle-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0;
  }
  .bm-purge-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
  }
  .bm-toggle:hover { opacity: 0.85; }
  .bm-purge-btn:hover:not(:disabled) {
    background: #7c5cfc !important;
    color: #fff !important;
    border-color: #7c5cfc !important;
  }
  @media (max-width: 900px) {
    .bm-stats-row { grid-template-columns: repeat(2, 1fr) !important; }
    .bm-toggle-grid { grid-template-columns: 1fr !important; }
    .bm-purge-row { grid-template-columns: repeat(2, 1fr) !important; }
  }
  @media (max-width: 500px) {
    .bm-stats-row { grid-template-columns: 1fr !important; }
    .bm-purge-row { grid-template-columns: 1fr !important; }
  }
`;

const s: Record<string, React.CSSProperties> = {
  page: { position: 'relative' },
  loadingWrap: {
    display: 'flex', flexDirection: 'column', alignItems: 'center',
    justifyContent: 'center', minHeight: 300,
  },
  spinner: {
    width: 28, height: 28, border: '3px solid #E5E7EB', borderTopColor: '#7c5cfc',
    borderRadius: '50%', animation: 'bm-spin 0.6s linear infinite',
  },
  toast: {
    position: 'fixed', top: 24, right: 24, zIndex: 9999,
    padding: '10px 20px', background: '#111827', color: '#fff',
    borderRadius: 8, fontSize: 13, fontWeight: 500,
    boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
  },
  errorBar: {
    display: 'flex', alignItems: 'center', justifyContent: 'space-between',
    padding: '10px 16px', background: '#FEE2E2', color: '#991B1B',
    fontSize: 13, borderRadius: 10, marginBottom: 16,
  },
  errorClose: {
    background: 'none', border: 'none', fontSize: 18, cursor: 'pointer',
    color: '#991B1B', padding: '0 4px',
  },
  header: {
    display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between',
    marginBottom: 20,
  },
  h1: { fontSize: 22, fontWeight: 700, color: '#111827', margin: '0 0 4px' },
  subtitle: { fontSize: 13, color: '#6B7280', margin: 0 },
  premiumBadge: {
    padding: '4px 14px', borderRadius: 20, background: '#F3F0FF',
    color: '#7c5cfc', fontSize: 12, fontWeight: 600,
  },
  statsRow: {},
  statCard: {
    background: '#fff', borderRadius: 12, border: '1px solid #e5e7eb',
    padding: 20, display: 'flex', flexDirection: 'column', alignItems: 'center',
    textAlign: 'center',
  },
  statIcon: {
    width: 44, height: 44, borderRadius: 12, background: '#F3F0FF',
    display: 'flex', alignItems: 'center', justifyContent: 'center',
    marginBottom: 12,
  },
  statNum: { fontSize: 20, fontWeight: 700, color: '#111827', marginBottom: 4 },
  statLabel: { fontSize: 12, color: '#6B7280' },
  card: {
    background: '#fff', borderRadius: 12, border: '1px solid #e5e7eb',
    padding: '24px', marginBottom: 20,
  },
  cardHead: {
    display: 'flex', alignItems: 'center', justifyContent: 'space-between',
    marginBottom: 20,
  },
  cardTitle: { fontSize: 16, fontWeight: 600, color: '#111827', margin: 0 },
  countBadge: {
    padding: '3px 12px', borderRadius: 20, background: '#F3F0FF',
    color: '#7c5cfc', fontSize: 12, fontWeight: 500,
  },
  toggleGrid: {},
  toggleRow: {
    display: 'flex', alignItems: 'center', justifyContent: 'space-between',
    padding: '14px 16px', borderBottom: '1px solid #F3F4F6',
  },
  toggleInfo: { flex: 1, minWidth: 0, marginRight: 16 },
  toggleTitle: { fontSize: 13, fontWeight: 600, color: '#111827', marginBottom: 2 },
  toggleDesc: { fontSize: 12, color: '#6B7280' },
  toggle: {
    width: 44, height: 24, borderRadius: 12, background: '#D1D5DB',
    border: 'none', cursor: 'pointer', position: 'relative',
    transition: 'background 0.2s', flexShrink: 0, padding: 0,
  },
  toggleOn: { background: '#7c5cfc' },
  toggleKnob: {
    position: 'absolute', top: 2, left: 2,
    width: 20, height: 20, borderRadius: 10, background: '#fff',
    transition: 'left 0.2s', boxShadow: '0 1px 3px rgba(0,0,0,0.15)',
  },
  toggleKnobOn: { left: 22 },
  purgeRow: {},
  purgeBtn: {
    display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 8,
    padding: '12px 16px', background: '#fff', border: '1px solid #e5e7eb',
    borderRadius: 10, fontSize: 13, fontWeight: 500, color: '#374151',
    cursor: 'pointer', transition: 'all 0.15s',
  },
  subTitle: {
    fontSize: 14, fontWeight: 600, color: '#111827', margin: '0 0 12px',
  },
};
