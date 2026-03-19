import { useState, useEffect } from 'react';
import { useOutletContext } from 'react-router-dom';
import { builderPluginService } from '../../services/builder-plugin.service';

type Tab = 'settings' | 'redirects' | 'robots';

interface SeoSettings {
  organization_name: string;
  logo_url: string;
  phone: string;
  address: string;
  enable_schema: boolean;
  enable_sitemap: boolean;
  enable_og_tags: boolean;
}

interface Redirect {
  from: string;
  to: string;
  hits: number;
}

const defaultSeo: SeoSettings = {
  organization_name: '',
  logo_url: '',
  phone: '',
  address: '',
  enable_schema: false,
  enable_sitemap: false,
  enable_og_tags: false,
};

export default function SeoPage() {
  const { website } = useOutletContext<{ website: any }>();
  const websiteId = website.id;

  const [tab, setTab] = useState<Tab>('settings');
  const [seo, setSeo] = useState<SeoSettings>(defaultSeo);
  const [redirects, setRedirects] = useState<Redirect[]>([]);
  const [robotsTxt, setRobotsTxt] = useState('');
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [toast, setToast] = useState('');
  const [saving, setSaving] = useState(false);
  const [generating, setGenerating] = useState(false);

  // Redirect form
  const [newFrom, setNewFrom] = useState('');
  const [newTo, setNewTo] = useState('');

  const showToast = (msg: string) => {
    setToast(msg);
    setTimeout(() => setToast(''), 2500);
  };

  useEffect(() => {
    let cancelled = false;
    setLoading(true);
    builderPluginService.getSeo(websiteId)
      .then((res) => {
        if (cancelled) return;
        if (res.data?.settings) setSeo({ ...defaultSeo, ...res.data.settings });
        if (res.data?.redirects) setRedirects(res.data.redirects);
        if (res.data?.robots_txt !== undefined) setRobotsTxt(res.data.robots_txt);
      })
      .catch(() => {
        if (!cancelled) setError('Failed to load SEO settings');
      })
      .finally(() => { if (!cancelled) setLoading(false); });
    return () => { cancelled = true; };
  }, [websiteId]);

  const handleSaveSeo = async () => {
    setSaving(true);
    setError('');
    try {
      await builderPluginService.saveSeo(websiteId, seo);
      showToast('SEO settings saved');
    } catch {
      setError('Failed to save SEO settings');
    } finally {
      setSaving(false);
    }
  };

  const handleGenerateSitemap = async () => {
    setGenerating(true);
    setError('');
    try {
      await builderPluginService.generateSitemap(websiteId);
      showToast('Sitemap generated successfully');
    } catch {
      setError('Failed to generate sitemap');
    } finally {
      setGenerating(false);
    }
  };

  const handleAddRedirect = async () => {
    if (!newFrom.trim() || !newTo.trim()) return;
    setError('');
    try {
      await builderPluginService.addRedirect(websiteId, newFrom, newTo);
      setRedirects([...redirects, { from: newFrom, to: newTo, hits: 0 }]);
      setNewFrom('');
      setNewTo('');
      showToast('Redirect added');
    } catch {
      setError('Failed to add redirect');
    }
  };

  const handleDeleteRedirect = async (from: string) => {
    setError('');
    try {
      await builderPluginService.deleteRedirect(websiteId, from);
      setRedirects(redirects.filter((r) => r.from !== from));
      showToast('Redirect deleted');
    } catch {
      setError('Failed to delete redirect');
    }
  };

  const handleSaveRobots = async () => {
    setSaving(true);
    setError('');
    try {
      await builderPluginService.saveRobots(websiteId, robotsTxt);
      showToast('robots.txt saved');
    } catch {
      setError('Failed to save robots.txt');
    } finally {
      setSaving(false);
    }
  };

  const toggleSeoFlag = (key: 'enable_schema' | 'enable_sitemap' | 'enable_og_tags') => {
    setSeo({ ...seo, [key]: !seo[key] });
  };

  if (loading) {
    return (
      <div style={s.loadingWrap}>
        <div style={s.spinner} />
        <p style={{ color: '#6B7280', fontSize: 13 }}>Loading SEO tools...</p>
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
          <h1 style={s.h1}>SEO Tools</h1>
          <p style={s.subtitle}>Optimize your site for search engines</p>
        </div>
      </div>

      {/* Tabs */}
      <div style={s.tabsWrap}>
        {(['settings', 'redirects', 'robots'] as Tab[]).map((t) => (
          <button
            key={t}
            style={{ ...s.tabBtn, ...(tab === t ? s.tabActive : {}) }}
            className="seo-tab"
            onClick={() => setTab(t)}
          >
            {t === 'settings' ? 'Settings' : t === 'redirects' ? 'Redirects' : 'Robots.txt'}
          </button>
        ))}
      </div>

      {/* Settings Tab */}
      {tab === 'settings' && (
        <div style={s.card}>
          <h2 style={s.cardTitle}>Organization Info</h2>
          <div style={s.formGrid} className="seo-form-grid">
            <div style={s.field}>
              <label style={s.label}>Organization Name</label>
              <input
                style={s.input}
                className="seo-input"
                value={seo.organization_name}
                onChange={(e) => setSeo({ ...seo, organization_name: e.target.value })}
                placeholder="Your business name"
              />
            </div>
            <div style={s.field}>
              <label style={s.label}>Logo URL</label>
              <input
                style={s.input}
                className="seo-input"
                value={seo.logo_url}
                onChange={(e) => setSeo({ ...seo, logo_url: e.target.value })}
                placeholder="https://example.com/logo.png"
              />
            </div>
            <div style={s.field}>
              <label style={s.label}>Phone Number</label>
              <input
                style={s.input}
                className="seo-input"
                value={seo.phone}
                onChange={(e) => setSeo({ ...seo, phone: e.target.value })}
                placeholder="+1 (555) 123-4567"
              />
            </div>
            <div style={s.field}>
              <label style={s.label}>Business Address</label>
              <input
                style={s.input}
                className="seo-input"
                value={seo.address}
                onChange={(e) => setSeo({ ...seo, address: e.target.value })}
                placeholder="123 Main St, City, State"
              />
            </div>
          </div>

          <h3 style={{ ...s.subTitle, marginTop: 24 }}>Features</h3>
          {[
            { key: 'enable_schema' as const, title: 'Schema Markup', desc: 'Add structured data for rich search results' },
            { key: 'enable_sitemap' as const, title: 'XML Sitemap', desc: 'Automatically generate and update sitemap' },
            { key: 'enable_og_tags' as const, title: 'Open Graph Tags', desc: 'Add social sharing meta tags to pages' },
          ].map((item) => (
            <div key={item.key} style={s.toggleRow}>
              <div style={s.toggleInfo}>
                <div style={s.toggleTitle}>{item.title}</div>
                <div style={s.toggleDesc}>{item.desc}</div>
              </div>
              <button
                style={{ ...s.toggle, ...(seo[item.key] ? s.toggleOn : {}) }}
                className="seo-toggle"
                onClick={() => toggleSeoFlag(item.key)}
                role="switch"
                aria-checked={seo[item.key]}
              >
                <span style={{ ...s.toggleKnob, ...(seo[item.key] ? s.toggleKnobOn : {}) }} />
              </button>
            </div>
          ))}

          <div style={s.actionRow}>
            <button
              style={s.btnPrimary}
              className="seo-btn-primary"
              onClick={handleSaveSeo}
              disabled={saving}
            >
              {saving ? 'Saving...' : 'Save Settings'}
            </button>
            <button
              style={s.btnOutline}
              className="seo-btn-outline"
              onClick={handleGenerateSitemap}
              disabled={generating}
            >
              {generating ? 'Generating...' : 'Generate Sitemap'}
            </button>
          </div>
        </div>
      )}

      {/* Redirects Tab */}
      {tab === 'redirects' && (
        <div style={s.card}>
          <h2 style={s.cardTitle}>Redirects</h2>
          <p style={s.cardDesc}>Manage URL redirections (301)</p>

          {/* Add redirect form */}
          <div style={s.redirectForm} className="seo-redirect-form">
            <div style={s.redirectField}>
              <label style={s.label}>From URL</label>
              <input
                style={s.input}
                className="seo-input"
                value={newFrom}
                onChange={(e) => setNewFrom(e.target.value)}
                placeholder="/old-page"
              />
            </div>
            <div style={s.redirectField}>
              <label style={s.label}>To URL</label>
              <input
                style={s.input}
                className="seo-input"
                value={newTo}
                onChange={(e) => setNewTo(e.target.value)}
                placeholder="/new-page"
              />
            </div>
            <button
              style={s.addBtn}
              className="seo-add-btn"
              onClick={handleAddRedirect}
              disabled={!newFrom.trim() || !newTo.trim()}
            >
              Add
            </button>
          </div>

          {/* Redirects table */}
          {redirects.length === 0 ? (
            <p style={s.emptyText}>No redirects configured</p>
          ) : (
            <table style={s.table}>
              <thead>
                <tr>
                  <th style={s.th}>From</th>
                  <th style={s.th}>To</th>
                  <th style={{ ...s.th, textAlign: 'right' }}>Hits</th>
                  <th style={{ ...s.th, textAlign: 'right' }}>Action</th>
                </tr>
              </thead>
              <tbody>
                {redirects.map((r, i) => (
                  <tr key={i} className="seo-redirect-row">
                    <td style={s.td}><code style={s.codePath}>{r.from}</code></td>
                    <td style={s.td}><code style={s.codePath}>{r.to}</code></td>
                    <td style={{ ...s.td, textAlign: 'right' }}>{r.hits}</td>
                    <td style={{ ...s.td, textAlign: 'right' }}>
                      <button
                        style={s.delBtn}
                        className="seo-del-btn"
                        onClick={() => handleDeleteRedirect(r.from)}
                      >
                        Delete
                      </button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          )}
        </div>
      )}

      {/* Robots.txt Tab */}
      {tab === 'robots' && (
        <div style={s.card}>
          <h2 style={s.cardTitle}>Robots.txt</h2>
          <p style={s.cardDesc}>Edit your robots.txt file to control search engine crawling</p>
          <textarea
            style={s.textarea}
            className="seo-textarea"
            value={robotsTxt}
            onChange={(e) => setRobotsTxt(e.target.value)}
            rows={14}
            placeholder={'User-agent: *\nDisallow: /wp-admin/\nAllow: /wp-admin/admin-ajax.php\n\nSitemap: ' + (website.url || 'https://example.com') + '/sitemap.xml'}
          />
          <div style={{ marginTop: 16 }}>
            <button
              style={s.btnPrimary}
              className="seo-btn-primary"
              onClick={handleSaveRobots}
              disabled={saving}
            >
              {saving ? 'Saving...' : 'Save Robots.txt'}
            </button>
          </div>
        </div>
      )}

      <style>{cssStr}</style>
    </div>
  );
}

const spinCss = `@keyframes seo-spin { to { transform: rotate(360deg); } }`;

const cssStr = `
  ${spinCss}
  .seo-tab:hover { color: #111827 !important; }
  .seo-input:focus { border-color: #7c5cfc !important; outline: none; }
  .seo-textarea:focus { border-color: #7c5cfc !important; outline: none; }
  .seo-toggle:hover { opacity: 0.85; }
  .seo-btn-primary:hover:not(:disabled) { background: #6a4ae8 !important; }
  .seo-btn-outline:hover:not(:disabled) { background: #F3F4F6 !important; }
  .seo-add-btn:hover:not(:disabled) { background: #6a4ae8 !important; }
  .seo-del-btn:hover { color: #DC2626 !important; text-decoration: underline !important; }
  .seo-redirect-row:hover { background: #F9FAFB; }
  .seo-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-top: 16px;
  }
  .seo-redirect-form {
    display: flex;
    align-items: flex-end;
    gap: 12px;
    margin: 16px 0 20px;
  }
  @media (max-width: 700px) {
    .seo-form-grid { grid-template-columns: 1fr !important; }
    .seo-redirect-form { flex-direction: column !important; align-items: stretch !important; }
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
    borderRadius: '50%', animation: 'seo-spin 0.6s linear infinite',
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
  tabsWrap: {
    display: 'flex', gap: 0, borderBottom: '1px solid #E5E7EB',
    marginBottom: 20, background: '#fff', borderRadius: '12px 12px 0 0',
  },
  tabBtn: {
    padding: '14px 24px', fontSize: 13, fontWeight: 500, color: '#6B7280',
    background: 'none', border: 'none', borderBottom: '2px solid transparent',
    cursor: 'pointer', marginBottom: -1,
  },
  tabActive: {
    color: '#7c5cfc', fontWeight: 600, borderBottomColor: '#7c5cfc',
  },
  card: {
    background: '#fff', borderRadius: 12, border: '1px solid #e5e7eb',
    padding: '24px', marginBottom: 20,
  },
  cardTitle: { fontSize: 16, fontWeight: 600, color: '#111827', margin: '0 0 4px' },
  cardDesc: { fontSize: 13, color: '#6B7280', margin: 0 },
  subTitle: { fontSize: 14, fontWeight: 600, color: '#111827', margin: '0 0 12px' },
  formGrid: {},
  field: { display: 'flex', flexDirection: 'column' },
  label: { fontSize: 12, fontWeight: 600, color: '#374151', marginBottom: 6 },
  input: {
    padding: '10px 14px', border: '1px solid #D1D5DB', borderRadius: 8,
    fontSize: 13, color: '#111827', background: '#fff', width: '100%',
    boxSizing: 'border-box',
  },
  toggleRow: {
    display: 'flex', alignItems: 'center', justifyContent: 'space-between',
    padding: '14px 0', borderBottom: '1px solid #F3F4F6',
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
  actionRow: {
    display: 'flex', gap: 12, marginTop: 24,
  },
  btnPrimary: {
    padding: '10px 24px', background: '#7c5cfc', color: '#fff', border: 'none',
    borderRadius: 8, fontSize: 13, fontWeight: 600, cursor: 'pointer',
  },
  btnOutline: {
    padding: '10px 24px', background: '#fff', color: '#374151',
    border: '1px solid #D1D5DB', borderRadius: 8, fontSize: 13,
    fontWeight: 500, cursor: 'pointer',
  },
  redirectForm: {},
  redirectField: { flex: 1, display: 'flex', flexDirection: 'column' },
  addBtn: {
    padding: '10px 24px', background: '#7c5cfc', color: '#fff', border: 'none',
    borderRadius: 8, fontSize: 13, fontWeight: 600, cursor: 'pointer',
    whiteSpace: 'nowrap', height: 42, alignSelf: 'flex-end',
  },
  table: { width: '100%', borderCollapse: 'collapse' },
  th: {
    textAlign: 'left', padding: '10px 0', fontSize: 11, fontWeight: 600,
    color: '#6B7280', borderBottom: '1px solid #E5E7EB',
    textTransform: 'uppercase', letterSpacing: 0.3,
  },
  td: {
    padding: '12px 0', fontSize: 13, color: '#111827',
    borderBottom: '1px solid #F3F4F6',
  },
  codePath: {
    fontSize: 12, fontFamily: 'monospace', color: '#7c5cfc',
    background: '#F8F7FF', padding: '2px 8px', borderRadius: 4,
  },
  delBtn: {
    background: 'none', border: 'none', fontSize: 12, fontWeight: 500,
    color: '#6B7280', cursor: 'pointer',
  },
  textarea: {
    width: '100%', padding: 14, border: '1px solid #D1D5DB', borderRadius: 8,
    fontSize: 13, fontFamily: 'monospace', color: '#111827', background: '#FAFAFA',
    resize: 'vertical', marginTop: 16, boxSizing: 'border-box',
  },
  emptyText: { fontSize: 13, color: '#9CA3AF', margin: '20px 0', textAlign: 'center' },
};
