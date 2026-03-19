import { useState, useEffect } from 'react';
import { useOutletContext } from 'react-router-dom';
import { builderPluginService } from '../../services/builder-plugin.service';

interface ImageStats {
  total_images: number;
  optimized: number;
  savings_percent: number;
  total_size: string;
  saved_size: string;
}

interface ImageSettings {
  quality: number;
  max_width: number;
  max_height: number;
  webp_enabled: boolean;
  auto_optimize: boolean;
  strip_exif: boolean;
}

interface OptResult {
  filename: string;
  original_size: string;
  optimized_size: string;
  savings: string;
}

const defaultStats: ImageStats = {
  total_images: 0,
  optimized: 0,
  savings_percent: 0,
  total_size: '0 MB',
  saved_size: '0 MB',
};

const defaultSettings: ImageSettings = {
  quality: 82,
  max_width: 2048,
  max_height: 2048,
  webp_enabled: true,
  auto_optimize: false,
  strip_exif: true,
};

export default function ImageOptimizerPage() {
  const { website } = useOutletContext<{ website: any }>();
  const websiteId = website.id;

  const [stats, setStats] = useState<ImageStats>(defaultStats);
  const [settings, setSettings] = useState<ImageSettings>(defaultSettings);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [toast, setToast] = useState('');
  const [optimizing, setOptimizing] = useState(false);
  const [results, setResults] = useState<OptResult[]>([]);
  const [batchSize, setBatchSize] = useState(10);
  const [saving, setSaving] = useState(false);

  const showToast = (msg: string) => {
    setToast(msg);
    setTimeout(() => setToast(''), 2500);
  };

  useEffect(() => {
    let cancelled = false;
    setLoading(true);
    builderPluginService.getImageStats(websiteId)
      .then((res) => {
        if (cancelled) return;
        if (res.data?.stats) setStats({ ...defaultStats, ...res.data.stats });
        if (res.data?.settings) setSettings({ ...defaultSettings, ...res.data.settings });
      })
      .catch(() => {
        if (!cancelled) setError('Failed to load image data');
      })
      .finally(() => { if (!cancelled) setLoading(false); });
    return () => { cancelled = true; };
  }, [websiteId]);

  const handleOptimize = async () => {
    setOptimizing(true);
    setResults([]);
    setError('');
    try {
      const res = await builderPluginService.optimizeImages(websiteId, batchSize);
      setResults(res.data?.results || []);
      if (res.data?.stats) setStats({ ...stats, ...res.data.stats });
      showToast(`Optimized ${res.data?.results?.length || 0} images`);
    } catch {
      setError('Failed to optimize images');
    } finally {
      setOptimizing(false);
    }
  };

  const handleSaveSettings = async () => {
    setSaving(true);
    setError('');
    try {
      await builderPluginService.saveImageSettings(websiteId, settings);
      showToast('Settings saved');
    } catch {
      setError('Failed to save settings');
    } finally {
      setSaving(false);
    }
  };

  const progressPct = stats.total_images > 0
    ? Math.round((stats.optimized / stats.total_images) * 100)
    : 0;

  if (loading) {
    return (
      <div style={s.loadingWrap}>
        <div style={s.spinner} />
        <p style={{ color: '#6B7280', fontSize: 13 }}>Loading image optimizer...</p>
        <style>{spinCss}</style>
      </div>
    );
  }

  return (
    <div style={s.page}>
      {toast && <div style={s.toast}>{toast}</div>}

      {error && (
        <div style={s.errorBar}>
          {error}
          <button style={s.errorClose} onClick={() => setError('')}>&times;</button>
        </div>
      )}

      {/* Header */}
      <div style={s.header}>
        <div>
          <h1 style={s.h1}>Image Optimizer</h1>
          <p style={s.subtitle}>Compress and optimize images for faster page loads</p>
        </div>
      </div>

      {/* Stats Cards */}
      <div style={s.statsRow} className="io-stats-row">
        <div style={s.statCard}>
          <div style={{ ...s.statIcon, background: '#F3F0FF' }}>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#7c5cfc" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
              <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21,15 16,10 5,21"/>
            </svg>
          </div>
          <div style={s.statNum}>{stats.total_images}</div>
          <div style={s.statLabel}>Total Images</div>
        </div>
        <div style={s.statCard}>
          <div style={{ ...s.statIcon, background: '#ECFDF5' }}>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10B981" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
              <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22,4 12,14.01 9,11.01"/>
            </svg>
          </div>
          <div style={s.statNum}>{stats.optimized}</div>
          <div style={s.statLabel}>Optimized</div>
        </div>
        <div style={s.statCard}>
          <div style={{ ...s.statIcon, background: '#FEF3C7' }}>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#F59E0B" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
              <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
          </div>
          <div style={{ ...s.statNum, color: '#F59E0B' }}>{stats.savings_percent}%</div>
          <div style={s.statLabel}>Total Savings</div>
        </div>
      </div>

      {/* Progress Bar */}
      <div style={s.card}>
        <div style={s.progressHeader}>
          <span style={s.progressLabel}>Optimization Progress</span>
          <span style={s.progressPct}>{progressPct}%</span>
        </div>
        <div style={s.progressTrack}>
          <div style={{ ...s.progressFill, width: `${progressPct}%` }} />
        </div>
        <div style={s.progressInfo}>
          <span>{stats.optimized} of {stats.total_images} images optimized</span>
          <span>Saved {stats.saved_size}</span>
        </div>
      </div>

      {/* Optimize Now */}
      <div style={s.card}>
        <h2 style={s.cardTitle}>Optimize Now</h2>
        <p style={s.cardDesc}>Select how many images to optimize in this batch</p>
        <div style={s.optimizeRow}>
          <div style={s.batchSelector}>
            {[5, 10, 25, 50].map((n) => (
              <button
                key={n}
                style={{ ...s.batchBtn, ...(batchSize === n ? s.batchActive : {}) }}
                className="io-batch-btn"
                onClick={() => setBatchSize(n)}
              >
                {n}
              </button>
            ))}
          </div>
          <button
            style={s.optimizeBtn}
            className="io-optimize-btn"
            onClick={handleOptimize}
            disabled={optimizing}
          >
            {optimizing ? (
              <>
                <div style={{ ...s.spinner, width: 16, height: 16, borderWidth: 2, borderTopColor: '#fff' }} />
                Optimizing...
              </>
            ) : (
              <>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                  <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Optimize {batchSize} Images
              </>
            )}
          </button>
        </div>
      </div>

      {/* Results */}
      {results.length > 0 && (
        <div style={s.card}>
          <h2 style={s.cardTitle}>Optimization Results</h2>
          <table style={s.table}>
            <thead>
              <tr>
                <th style={s.th}>Filename</th>
                <th style={{ ...s.th, textAlign: 'right' }}>Original</th>
                <th style={{ ...s.th, textAlign: 'right' }}>Optimized</th>
                <th style={{ ...s.th, textAlign: 'right' }}>Savings</th>
              </tr>
            </thead>
            <tbody>
              {results.map((r, i) => (
                <tr key={i} className="io-result-row">
                  <td style={s.td}>
                    <span style={s.filename}>{r.filename}</span>
                  </td>
                  <td style={{ ...s.td, textAlign: 'right' }}>{r.original_size}</td>
                  <td style={{ ...s.td, textAlign: 'right' }}>{r.optimized_size}</td>
                  <td style={{ ...s.td, textAlign: 'right' }}>
                    <span style={s.savingsBadge}>{r.savings}</span>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}

      {/* Settings */}
      <div style={s.card}>
        <h2 style={s.cardTitle}>Optimization Settings</h2>

        {/* Quality Slider */}
        <div style={s.settingRow}>
          <div style={s.settingInfo}>
            <div style={s.settingTitle}>Quality Level</div>
            <div style={s.settingDesc}>Higher quality means larger file sizes (1-100)</div>
          </div>
          <div style={s.sliderWrap}>
            <input
              type="range"
              min="1"
              max="100"
              value={settings.quality}
              onChange={(e) => setSettings({ ...settings, quality: Number(e.target.value) })}
              style={s.slider}
              className="io-slider"
            />
            <span style={s.sliderValue}>{settings.quality}</span>
          </div>
        </div>

        {/* Max Width */}
        <div style={s.settingRow}>
          <div style={s.settingInfo}>
            <div style={s.settingTitle}>Max Width</div>
            <div style={s.settingDesc}>Resize images wider than this (px)</div>
          </div>
          <input
            type="number"
            style={s.numInput}
            className="io-num-input"
            value={settings.max_width}
            onChange={(e) => setSettings({ ...settings, max_width: Number(e.target.value) })}
            min={100}
            max={10000}
          />
        </div>

        {/* Max Height */}
        <div style={s.settingRow}>
          <div style={s.settingInfo}>
            <div style={s.settingTitle}>Max Height</div>
            <div style={s.settingDesc}>Resize images taller than this (px)</div>
          </div>
          <input
            type="number"
            style={s.numInput}
            className="io-num-input"
            value={settings.max_height}
            onChange={(e) => setSettings({ ...settings, max_height: Number(e.target.value) })}
            min={100}
            max={10000}
          />
        </div>

        {/* Toggles */}
        {[
          { key: 'webp_enabled' as const, title: 'Convert to WebP', desc: 'Serve modern WebP format when supported' },
          { key: 'auto_optimize' as const, title: 'Auto-Optimize Uploads', desc: 'Automatically optimize newly uploaded images' },
          { key: 'strip_exif' as const, title: 'Strip EXIF Data', desc: 'Remove metadata to reduce file size' },
        ].map((item) => (
          <div key={item.key} style={s.settingRow}>
            <div style={s.settingInfo}>
              <div style={s.settingTitle}>{item.title}</div>
              <div style={s.settingDesc}>{item.desc}</div>
            </div>
            <button
              style={{ ...s.toggle, ...(settings[item.key] ? s.toggleOn : {}) }}
              className="io-toggle"
              onClick={() => setSettings({ ...settings, [item.key]: !settings[item.key] })}
              role="switch"
              aria-checked={settings[item.key]}
            >
              <span style={{ ...s.toggleKnob, ...(settings[item.key] ? s.toggleKnobOn : {}) }} />
            </button>
          </div>
        ))}

        <div style={{ marginTop: 20 }}>
          <button
            style={s.saveBtn}
            className="io-save-btn"
            onClick={handleSaveSettings}
            disabled={saving}
          >
            {saving ? 'Saving...' : 'Save Settings'}
          </button>
        </div>
      </div>

      <style>{cssStr}</style>
    </div>
  );
}

const spinCss = `@keyframes io-spin { to { transform: rotate(360deg); } }`;

const cssStr = `
  ${spinCss}
  .io-stats-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-bottom: 20px;
  }
  .io-batch-btn:hover { border-color: #7c5cfc !important; color: #7c5cfc !important; }
  .io-optimize-btn:hover:not(:disabled) { background: #6a4ae8 !important; }
  .io-toggle:hover { opacity: 0.85; }
  .io-save-btn:hover:not(:disabled) { background: #6a4ae8 !important; }
  .io-num-input:focus { border-color: #7c5cfc !important; outline: none; }
  .io-result-row:hover { background: #F9FAFB; }
  .io-slider {
    -webkit-appearance: none;
    width: 100%;
    height: 6px;
    border-radius: 3px;
    background: #E5E7EB;
    outline: none;
  }
  .io-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: #7c5cfc;
    cursor: pointer;
    box-shadow: 0 1px 4px rgba(0,0,0,0.2);
  }
  .io-slider::-moz-range-thumb {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: #7c5cfc;
    cursor: pointer;
    border: none;
    box-shadow: 0 1px 4px rgba(0,0,0,0.2);
  }
  @media (max-width: 700px) {
    .io-stats-row { grid-template-columns: 1fr !important; }
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
    borderRadius: '50%', animation: 'io-spin 0.6s linear infinite',
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
  statsRow: {},
  statCard: {
    background: '#fff', borderRadius: 12, border: '1px solid #e5e7eb',
    padding: 20, display: 'flex', flexDirection: 'column', alignItems: 'center',
    textAlign: 'center',
  },
  statIcon: {
    width: 44, height: 44, borderRadius: 12,
    display: 'flex', alignItems: 'center', justifyContent: 'center',
    marginBottom: 12,
  },
  statNum: { fontSize: 24, fontWeight: 700, color: '#111827', marginBottom: 4 },
  statLabel: { fontSize: 12, color: '#6B7280' },
  card: {
    background: '#fff', borderRadius: 12, border: '1px solid #e5e7eb',
    padding: '24px', marginBottom: 20,
  },
  cardTitle: { fontSize: 16, fontWeight: 600, color: '#111827', margin: '0 0 4px' },
  cardDesc: { fontSize: 13, color: '#6B7280', margin: '0 0 16px' },
  progressHeader: {
    display: 'flex', alignItems: 'center', justifyContent: 'space-between',
    marginBottom: 8,
  },
  progressLabel: { fontSize: 13, fontWeight: 600, color: '#111827' },
  progressPct: { fontSize: 13, fontWeight: 700, color: '#7c5cfc' },
  progressTrack: {
    height: 10, borderRadius: 5, background: '#F3F4F6', overflow: 'hidden',
  },
  progressFill: {
    height: '100%', borderRadius: 5, background: 'linear-gradient(90deg, #7c5cfc, #a78bfa)',
    transition: 'width 0.5s',
  },
  progressInfo: {
    display: 'flex', justifyContent: 'space-between', marginTop: 8,
    fontSize: 12, color: '#6B7280',
  },
  optimizeRow: {
    display: 'flex', alignItems: 'center', gap: 16, flexWrap: 'wrap',
  },
  batchSelector: { display: 'flex', gap: 8 },
  batchBtn: {
    width: 44, height: 36, borderRadius: 8, border: '1px solid #E5E7EB',
    background: '#fff', fontSize: 13, fontWeight: 600, color: '#374151',
    cursor: 'pointer', transition: 'all 0.15s',
  },
  batchActive: {
    background: '#F3F0FF', borderColor: '#7c5cfc', color: '#7c5cfc',
  },
  optimizeBtn: {
    display: 'inline-flex', alignItems: 'center', gap: 8,
    padding: '10px 24px', background: '#7c5cfc', color: '#fff', border: 'none',
    borderRadius: 8, fontSize: 13, fontWeight: 600, cursor: 'pointer',
  },
  table: { width: '100%', borderCollapse: 'collapse', marginTop: 12 },
  th: {
    textAlign: 'left', padding: '10px 0', fontSize: 11, fontWeight: 600,
    color: '#6B7280', borderBottom: '1px solid #E5E7EB',
    textTransform: 'uppercase', letterSpacing: 0.3,
  },
  td: {
    padding: '10px 0', fontSize: 13, color: '#111827',
    borderBottom: '1px solid #F3F4F6',
  },
  filename: {
    fontSize: 13, color: '#374151', fontWeight: 500, maxWidth: 200,
    overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap',
    display: 'block',
  },
  savingsBadge: {
    display: 'inline-flex', padding: '2px 10px', borderRadius: 20,
    fontSize: 12, fontWeight: 600, background: '#ECFDF5', color: '#059669',
  },
  settingRow: {
    display: 'flex', alignItems: 'center', justifyContent: 'space-between',
    padding: '14px 0', borderBottom: '1px solid #F3F4F6',
  },
  settingInfo: { flex: 1, minWidth: 0, marginRight: 16 },
  settingTitle: { fontSize: 13, fontWeight: 600, color: '#111827', marginBottom: 2 },
  settingDesc: { fontSize: 12, color: '#6B7280' },
  sliderWrap: {
    display: 'flex', alignItems: 'center', gap: 12, width: 200,
  },
  slider: { flex: 1 },
  sliderValue: {
    fontSize: 14, fontWeight: 700, color: '#7c5cfc', minWidth: 30,
    textAlign: 'right',
  },
  numInput: {
    width: 100, padding: '8px 12px', border: '1px solid #D1D5DB', borderRadius: 8,
    fontSize: 13, color: '#111827', textAlign: 'right',
  },
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
  saveBtn: {
    padding: '10px 24px', background: '#7c5cfc', color: '#fff', border: 'none',
    borderRadius: 8, fontSize: 13, fontWeight: 600, cursor: 'pointer',
  },
};
