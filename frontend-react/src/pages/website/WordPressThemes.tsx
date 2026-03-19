import { useState, useEffect, useCallback } from 'react';
import { useParams } from 'react-router-dom';
import { wpManagerService } from '../../services/wp-manager.service';
import type { WpTheme } from '../../models/types';

export default function WordPressThemes() {
  const { id } = useParams<{ id: string }>();
  const websiteId = Number(id);

  const [themes, setThemes] = useState<WpTheme[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [search, setSearch] = useState('');
  const [actionLoading, setActionLoading] = useState<string | null>(null);
  const [selected, setSelected] = useState<Set<string>>(new Set());

  const fetchThemes = useCallback(async () => {
    setLoading(true);
    setError('');
    try {
      const res = await wpManagerService.listThemes(websiteId);
      setThemes(res.data.data || []);
    } catch (e: any) {
      setError(e.response?.data?.message || e.message || 'Failed to load themes');
    } finally {
      setLoading(false);
    }
  }, [websiteId]);

  useEffect(() => { fetchThemes(); }, [fetchThemes]);

  const activeTheme = themes.find(t => t.is_active);
  const otherThemes = themes.filter(t => !t.is_active);

  const filtered = otherThemes.filter(t => {
    if (!search) return true;
    const q = search.toLowerCase();
    return t.name.toLowerCase().includes(q) || t.description.toLowerCase().includes(q);
  });

  const toggleSelect = (slug: string) => {
    setSelected(prev => {
      const next = new Set(prev);
      if (next.has(slug)) next.delete(slug); else next.add(slug);
      return next;
    });
  };

  const toggleAll = () => {
    if (selected.size === filtered.length) {
      setSelected(new Set());
    } else {
      setSelected(new Set(filtered.map(t => t.slug)));
    }
  };

  const handleActivate = async (theme: string) => {
    setActionLoading(theme);
    try {
      await wpManagerService.activateTheme(websiteId, theme);
      await fetchThemes();
      setSelected(new Set());
    } catch (e: any) {
      setError(e.response?.data?.message || e.message || 'Activation failed');
    } finally {
      setActionLoading(null);
    }
  };

  const handleUpdate = async (theme: string) => {
    setActionLoading(theme);
    try {
      await wpManagerService.updateTheme(websiteId, theme);
      await fetchThemes();
    } catch (e: any) {
      setError(e.response?.data?.message || e.message || 'Update failed');
    } finally {
      setActionLoading(null);
    }
  };

  const handleDelete = async (theme: string) => {
    if (!confirm('Delete this theme? This cannot be undone.')) return;
    setActionLoading(theme);
    try {
      await wpManagerService.deleteTheme(websiteId, theme);
      await fetchThemes();
      setSelected(prev => { const n = new Set(prev); n.delete(theme); return n; });
    } catch (e: any) {
      setError(e.response?.data?.message || e.message || 'Delete failed');
    } finally {
      setActionLoading(null);
    }
  };

  const handleBulkDelete = async () => {
    if (!selected.size) return;
    if (!confirm(`Delete ${selected.size} theme(s)? This cannot be undone.`)) return;
    setActionLoading('bulk');
    try {
      for (const theme of selected) {
        await wpManagerService.deleteTheme(websiteId, theme);
      }
      setSelected(new Set());
      await fetchThemes();
    } catch (e: any) {
      setError(e.response?.data?.message || e.message || 'Bulk delete failed');
    } finally {
      setActionLoading(null);
    }
  };

  return (
    <div style={s.page}>
      {error && (
        <div style={s.error}>
          {error}
          <button style={s.dismissBtn} onClick={() => setError('')}>&times;</button>
        </div>
      )}

      {loading ? (
        <div style={s.center}>
          <div style={s.spinner} />
          <p style={{ color: '#6B7280', marginTop: 12, fontSize: 13 }}>Loading themes...</p>
        </div>
      ) : (
        <>
          {/* Active Theme Section */}
          {activeTheme && (
            <div style={s.activeSection}>
              <h3 style={s.sectionTitle}>Active Theme</h3>
              <div style={s.activeCard}>
                <div style={s.activeScreenshot}>
                  {activeTheme.screenshot ? (
                    <img src={activeTheme.screenshot} alt={activeTheme.name} style={s.screenshotImg} />
                  ) : (
                    <div style={s.noScreenshot}>
                      <svg width="40" height="40" fill="none" stroke="#D1D5DB" viewBox="0 0 24 24">
                        <path strokeWidth="1.5" strokeLinecap="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                    </div>
                  )}
                </div>
                <div style={s.activeInfo}>
                  <div style={s.activeNameRow}>
                    <span style={s.activeName}>{activeTheme.name}</span>
                    <span style={s.badgeActive}>Active</span>
                  </div>
                  <div style={s.activeVersion}>Current version {activeTheme.version}</div>
                  {activeTheme.author && <div style={s.activeAuthor}>By {activeTheme.author}</div>}
                  {activeTheme.description && (
                    <div style={s.activeDesc}>{activeTheme.description}</div>
                  )}
                  {activeTheme.update_available && (
                    <button
                      style={s.updateBtn}
                      className="wpt-update-btn"
                      onClick={() => handleUpdate(activeTheme.slug)}
                      disabled={actionLoading === activeTheme.slug}
                    >
                      {actionLoading === activeTheme.slug ? (
                        <div style={{ ...s.spinner, width: 14, height: 14, borderWidth: 2 }} />
                      ) : (
                        <>Update to {activeTheme.update_available}</>
                      )}
                    </button>
                  )}
                </div>
              </div>
            </div>
          )}

          {/* Other Themes */}
          <div style={s.othersSection}>
            <div style={s.othersHeader}>
              <h3 style={s.sectionTitle}>Themes</h3>
              <div style={s.searchBox}>
                <svg width="15" height="15" fill="none" stroke="#9CA3AF" viewBox="0 0 24 24">
                  <path strokeWidth="2" strokeLinecap="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input
                  style={s.searchInput}
                  placeholder="SEARCH THEMES"
                  value={search}
                  onChange={e => setSearch(e.target.value)}
                />
              </div>
            </div>

            {/* Table Header */}
            <div style={s.tableHeader}>
              <div style={s.checkCol}>
                <input
                  type="checkbox"
                  checked={selected.size === filtered.length && filtered.length > 0}
                  onChange={toggleAll}
                  style={s.checkbox}
                />
              </div>
              <div style={{ ...s.colTheme, fontWeight: 600, fontSize: 12, color: '#6B7280', textTransform: 'uppercase', letterSpacing: 0.5 }}>
                Theme
              </div>
              <div style={{ ...s.colVersion, fontWeight: 600, fontSize: 12, color: '#6B7280', textTransform: 'uppercase', letterSpacing: 0.5 }}>
                Version
              </div>
              <div style={{ ...s.colActions, fontWeight: 600, fontSize: 12, color: '#6B7280', textTransform: 'uppercase', letterSpacing: 0.5 }}>
                Actions
              </div>
            </div>

            {filtered.length === 0 ? (
              <div style={s.emptyState}>
                <p style={{ color: '#6B7280', fontSize: 13 }}>No other themes installed</p>
              </div>
            ) : (
              filtered.map(theme => (
                <div key={theme.slug} style={s.themeRow} className="wpt-theme-row">
                  <div style={s.checkCol}>
                    <input
                      type="checkbox"
                      checked={selected.has(theme.slug)}
                      onChange={() => toggleSelect(theme.slug)}
                      style={s.checkbox}
                    />
                  </div>
                  <div style={s.colTheme}>
                    <div style={s.themeThumb}>
                      {theme.screenshot ? (
                        <img src={theme.screenshot} alt={theme.name} style={s.thumbImg} />
                      ) : (
                        <div style={s.noThumb}>
                          <svg width="20" height="20" fill="none" stroke="#D1D5DB" viewBox="0 0 24 24">
                            <path strokeWidth="1.5" strokeLinecap="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                          </svg>
                        </div>
                      )}
                    </div>
                    <div style={{ flex: 1 }}>
                      <div style={s.themeName}>{theme.name}</div>
                      <div style={s.themeDesc}>{theme.description}</div>
                      {theme.author && <div style={s.themeAuthor}>By {theme.author}</div>}
                    </div>
                  </div>
                  <div style={s.colVersion}>
                    <span style={s.versionText}>Version {theme.version}</span>
                    {theme.update_available && (
                      <button
                        style={{ ...s.updateBtn, marginTop: 4 }}
                        className="wpt-update-btn"
                        onClick={() => handleUpdate(theme.slug)}
                        disabled={actionLoading === theme.slug}
                      >
                        {actionLoading === theme.slug ? '...' : `Update to ${theme.update_available}`}
                      </button>
                    )}
                  </div>
                  <div style={s.colActions}>
                    <button
                      style={s.activateBtn}
                      className="wpt-activate-btn"
                      onClick={() => handleActivate(theme.slug)}
                      disabled={actionLoading === theme.slug}
                    >
                      {actionLoading === theme.slug ? (
                        <div style={{ ...s.spinner, width: 14, height: 14, borderWidth: 2 }} />
                      ) : 'Activate'}
                    </button>
                    <button
                      style={s.deleteBtn}
                      className="wpt-delete-btn"
                      onClick={() => handleDelete(theme.slug)}
                      disabled={actionLoading === theme.slug}
                    >
                      Delete
                    </button>
                  </div>
                </div>
              ))
            )}
          </div>

          {/* Bulk Actions */}
          {selected.size > 0 && (
            <div style={s.bulkBar}>
              <span style={{ fontSize: 13, color: '#374151' }}>{selected.size} selected</span>
              <div style={{ display: 'flex', gap: 8 }}>
                <button
                  style={s.bulkActivateBtn}
                  className="wpt-bulk-activate"
                  onClick={() => {
                    const first = [...selected][0];
                    if (first) handleActivate(first);
                  }}
                  disabled={actionLoading === 'bulk' || selected.size !== 1}
                  title={selected.size !== 1 ? 'Select exactly one theme to activate' : 'Activate selected theme'}
                >
                  Activate
                </button>
                <button
                  style={s.bulkDeleteBtn}
                  className="wpt-bulk-delete"
                  onClick={handleBulkDelete}
                  disabled={actionLoading === 'bulk'}
                >
                  Delete
                </button>
              </div>
            </div>
          )}
        </>
      )}

      <style>{cssStr}</style>
    </div>
  );
}

const cssStr = `
  .wpt-theme-row:hover { background: #F9FAFB !important; }
  .wpt-update-btn:hover:not(:disabled) { background: #15803D !important; }
  .wpt-activate-btn:hover:not(:disabled) { background: #F3F4F6 !important; }
  .wpt-delete-btn:hover:not(:disabled) { background: #FEE2E2 !important; color: #991B1B !important; }
  .wpt-bulk-activate:hover:not(:disabled) { background: #F3F4F6 !important; }
  .wpt-bulk-delete:hover:not(:disabled) { background: #B91C1C !important; }
  @keyframes wpt-spin { to { transform: rotate(360deg); } }
`;

const s: Record<string, React.CSSProperties> = {
  page: { padding: 0 },
  error: {
    display: 'flex', alignItems: 'center', justifyContent: 'space-between',
    padding: '10px 16px', background: '#FEE2E2', color: '#991B1B',
    fontSize: 13, margin: '12px 20px 0', borderRadius: 10,
  },
  dismissBtn: {
    background: 'none', border: 'none', fontSize: 18, cursor: 'pointer',
    color: '#991B1B', padding: '0 4px',
  },
  center: {
    display: 'flex', flexDirection: 'column' as const, alignItems: 'center',
    justifyContent: 'center', padding: 60,
  },
  spinner: {
    width: 28, height: 28, border: '3px solid #E5E7EB', borderTopColor: '#111827',
    borderRadius: '50%', animation: 'wpt-spin 0.6s linear infinite',
  },

  // Active Theme
  activeSection: { padding: '20px 20px 0', borderBottom: '1px solid #E5E7EB' },
  sectionTitle: { fontSize: 16, fontWeight: 700, color: '#111827', margin: '0 0 16px' },
  activeCard: { display: 'flex', gap: 24, paddingBottom: 20, flexWrap: 'wrap' as const },
  activeScreenshot: {
    width: 240, height: 180, borderRadius: 12, overflow: 'hidden',
    border: '1px solid #E5E7EB', flexShrink: 0, background: '#F9FAFB',
  },
  screenshotImg: { width: '100%', height: '100%', objectFit: 'cover' as const },
  noScreenshot: {
    width: '100%', height: '100%', display: 'flex', alignItems: 'center',
    justifyContent: 'center', background: '#F3F4F6',
  },
  activeInfo: { flex: 1, minWidth: 200 },
  activeNameRow: { display: 'flex', alignItems: 'center', gap: 8, marginBottom: 4 },
  activeName: { fontSize: 18, fontWeight: 700, color: '#111827' },
  badgeActive: {
    display: 'inline-flex', padding: '2px 10px', borderRadius: 20,
    fontSize: 11, fontWeight: 600, background: '#DCFCE7', color: '#15803D',
  },
  activeVersion: { fontSize: 13, color: '#6B7280', marginBottom: 4 },
  activeAuthor: { fontSize: 12, color: '#9CA3AF', marginBottom: 8 },
  activeDesc: { fontSize: 13, color: '#6B7280', lineHeight: 1.5, maxWidth: 500 },

  // Others
  othersSection: { background: '#fff' },
  othersHeader: {
    display: 'flex', alignItems: 'center', justifyContent: 'space-between',
    padding: '16px 20px', flexWrap: 'wrap' as const, gap: 8,
  },
  searchBox: {
    display: 'flex', alignItems: 'center', gap: 6,
    padding: '7px 14px', border: '1px solid #E5E7EB', borderRadius: 8, background: '#fff',
  },
  searchInput: {
    border: 0, outline: 'none', fontSize: 12, background: 'transparent',
    width: 140, fontWeight: 500, letterSpacing: 0.5, color: '#374151',
  },
  tableHeader: {
    display: 'flex', alignItems: 'center', padding: '12px 20px',
    borderBottom: '1px solid #E5E7EB', background: '#FAFAFA',
  },
  checkCol: { width: 40, flexShrink: 0, display: 'flex', alignItems: 'center', justifyContent: 'center' },
  checkbox: { width: 16, height: 16, cursor: 'pointer', accentColor: '#111827' },
  colTheme: { flex: 1, display: 'flex', alignItems: 'center', gap: 12, minWidth: 0 },
  colVersion: { width: 160, textAlign: 'center' as const, flexShrink: 0, display: 'flex', flexDirection: 'column' as const, alignItems: 'center' },
  colActions: { width: 180, flexShrink: 0, display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 8 },
  themeRow: {
    display: 'flex', alignItems: 'center', padding: '14px 20px',
    borderBottom: '1px solid #F3F4F6', transition: 'background 0.15s',
  },
  themeThumb: {
    width: 64, height: 48, borderRadius: 8, overflow: 'hidden',
    border: '1px solid #E5E7EB', flexShrink: 0, background: '#F9FAFB',
  },
  thumbImg: { width: '100%', height: '100%', objectFit: 'cover' as const },
  noThumb: {
    width: '100%', height: '100%', display: 'flex', alignItems: 'center',
    justifyContent: 'center', background: '#F3F4F6',
  },
  themeName: { fontSize: 14, fontWeight: 600, color: '#111827', marginBottom: 2 },
  themeDesc: {
    fontSize: 12, color: '#6B7280', lineHeight: 1.3, maxWidth: 400,
    overflow: 'hidden', textOverflow: 'ellipsis', display: '-webkit-box',
    WebkitLineClamp: 2, WebkitBoxOrient: 'vertical',
  },
  themeAuthor: { fontSize: 11, color: '#9CA3AF', marginTop: 2 },
  versionText: { fontSize: 13, color: '#374151', fontWeight: 500 },
  updateBtn: {
    padding: '5px 14px', background: '#16A34A', color: '#fff', border: 'none',
    borderRadius: 6, fontSize: 12, fontWeight: 600, cursor: 'pointer',
    display: 'inline-flex', alignItems: 'center', gap: 4,
  },
  activateBtn: {
    padding: '6px 16px', border: '1px solid #D1D5DB', borderRadius: 8,
    background: '#fff', fontSize: 12, fontWeight: 600, cursor: 'pointer', color: '#374151',
    display: 'inline-flex', alignItems: 'center', gap: 4,
  },
  deleteBtn: {
    padding: '6px 16px', border: '1px solid #FECACA', borderRadius: 8,
    background: '#fff', fontSize: 12, fontWeight: 600, cursor: 'pointer', color: '#DC2626',
  },
  emptyState: { padding: '40px 20px', textAlign: 'center' as const },

  // Bulk
  bulkBar: {
    display: 'flex', alignItems: 'center', justifyContent: 'space-between',
    padding: '12px 20px', background: '#fff', borderTop: '1px solid #E5E7EB',
  },
  bulkActivateBtn: {
    padding: '8px 20px', border: '1px solid #D1D5DB', borderRadius: 8,
    background: '#fff', fontSize: 13, fontWeight: 500, cursor: 'pointer', color: '#374151',
  },
  bulkDeleteBtn: {
    padding: '8px 20px', border: 'none', borderRadius: 8,
    background: '#DC2626', color: '#fff', fontSize: 13, fontWeight: 600, cursor: 'pointer',
  },
};