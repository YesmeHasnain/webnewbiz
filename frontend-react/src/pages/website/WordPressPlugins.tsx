import { useState, useEffect, useCallback } from 'react';
import { useParams } from 'react-router-dom';
import { wpManagerService } from '../../services/wp-manager.service';
import { PluginIconBadge } from '../../components/PluginIcons';
import type { WpPlugin } from '../../models/types';

type TabKey = 'all' | 'active' | 'inactive' | 'updates';

export default function WordPressPlugins() {
  const { id } = useParams<{ id: string }>();
  const websiteId = Number(id);

  const [plugins, setPlugins] = useState<WpPlugin[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [search, setSearch] = useState('');
  const [tab, setTab] = useState<TabKey>('all');
  const [actionLoading, setActionLoading] = useState<string | null>(null);
  const [selected, setSelected] = useState<Set<string>>(new Set());

  const fetchPlugins = useCallback(async () => {
    setLoading(true);
    setError('');
    try {
      const res = await wpManagerService.listPlugins(websiteId);
      setPlugins(res.data.data || []);
    } catch (e: any) {
      setError(e.response?.data?.message || e.message || 'Failed to load plugins');
    } finally {
      setLoading(false);
    }
  }, [websiteId]);

  useEffect(() => { fetchPlugins(); }, [fetchPlugins]);

  const filtered = plugins.filter(p => {
    if (tab === 'active' && !p.is_active) return false;
    if (tab === 'inactive' && p.is_active) return false;
    if (tab === 'updates' && !p.update_available) return false;
    if (search) {
      const q = search.toLowerCase();
      return p.name.toLowerCase().includes(q) || p.description.toLowerCase().includes(q);
    }
    return true;
  });

  const counts = {
    all: plugins.length,
    active: plugins.filter(p => p.is_active).length,
    inactive: plugins.filter(p => !p.is_active).length,
    updates: plugins.filter(p => p.update_available).length,
  };

  const toggleSelect = (file: string) => {
    setSelected(prev => {
      const next = new Set(prev);
      if (next.has(file)) next.delete(file); else next.add(file);
      return next;
    });
  };

  const toggleAll = () => {
    if (selected.size === filtered.length) {
      setSelected(new Set());
    } else {
      setSelected(new Set(filtered.map(p => p.file)));
    }
  };

  const handleActivate = async (plugin: string) => {
    setActionLoading(plugin);
    try {
      await wpManagerService.activatePlugin(websiteId, plugin);
      await fetchPlugins();
    } catch (e: any) {
      setError(e.response?.data?.message || e.message || 'Activation failed');
    } finally {
      setActionLoading(null);
    }
  };

  const handleDeactivate = async (plugin: string) => {
    setActionLoading(plugin);
    try {
      await wpManagerService.deactivatePlugin(websiteId, plugin);
      await fetchPlugins();
    } catch (e: any) {
      setError(e.response?.data?.message || e.message || 'Deactivation failed');
    } finally {
      setActionLoading(null);
    }
  };

  const handleUpdate = async (plugin: string) => {
    setActionLoading(plugin);
    try {
      await wpManagerService.updatePlugin(websiteId, plugin);
      await fetchPlugins();
    } catch (e: any) {
      setError(e.response?.data?.message || e.message || 'Update failed');
    } finally {
      setActionLoading(null);
    }
  };

  const handleDelete = async (plugin: string) => {
    if (!confirm('Delete this plugin? This cannot be undone.')) return;
    setActionLoading(plugin);
    try {
      await wpManagerService.deletePlugin(websiteId, plugin);
      await fetchPlugins();
      setSelected(prev => { const n = new Set(prev); n.delete(plugin); return n; });
    } catch (e: any) {
      setError(e.response?.data?.message || e.message || 'Delete failed');
    } finally {
      setActionLoading(null);
    }
  };

  const handleBulkDeactivate = async () => {
    const toDeactivate = [...selected].filter(f => plugins.find(p => p.file === f)?.is_active);
    if (!toDeactivate.length) return;
    setActionLoading('bulk');
    try {
      for (const plugin of toDeactivate) {
        await wpManagerService.deactivatePlugin(websiteId, plugin);
      }
      setSelected(new Set());
      await fetchPlugins();
    } catch (e: any) {
      setError(e.response?.data?.message || e.message || 'Bulk deactivation failed');
    } finally {
      setActionLoading(null);
    }
  };

  const handleBulkDelete = async () => {
    const toDelete = [...selected].filter(f => !plugins.find(p => p.file === f)?.is_active);
    if (!toDelete.length) {
      setError('Deactivate plugins before deleting them.');
      return;
    }
    if (!confirm(`Delete ${toDelete.length} plugin(s)? This cannot be undone.`)) return;
    setActionLoading('bulk');
    try {
      for (const plugin of toDelete) {
        await wpManagerService.deletePlugin(websiteId, plugin);
      }
      setSelected(new Set());
      await fetchPlugins();
    } catch (e: any) {
      setError(e.response?.data?.message || e.message || 'Bulk delete failed');
    } finally {
      setActionLoading(null);
    }
  };

  const tabs: { key: TabKey; label: string }[] = [
    { key: 'all', label: 'All plugins' },
    { key: 'active', label: 'Active plugins' },
    { key: 'inactive', label: 'Inactive plugins' },
    { key: 'updates', label: 'Available updates' },
  ];

  return (
    <div style={s.page}>
      {/* Tabs Row */}
      <div style={s.tabsRow}>
        <div style={s.tabsLeft}>
          {tabs.map(t => (
            <button
              key={t.key}
              style={{ ...s.tabBtn, ...(tab === t.key ? s.tabActive : {}) }}
              className="wp-tab"
              onClick={() => { setTab(t.key); setSelected(new Set()); }}
            >
              {t.label}
              {counts[t.key] > 0 && <span style={s.tabCount}>{counts[t.key]}</span>}
            </button>
          ))}
        </div>
        <div style={s.searchBox}>
          <svg width="15" height="15" fill="none" stroke="#9CA3AF" viewBox="0 0 24 24">
            <path strokeWidth="2" strokeLinecap="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          <input
            style={s.searchInput}
            placeholder="SEARCH PLUGINS"
            value={search}
            onChange={e => setSearch(e.target.value)}
          />
        </div>
      </div>

      {error && (
        <div style={s.error}>
          {error}
          <button style={s.dismissBtn} onClick={() => setError('')}>&times;</button>
        </div>
      )}

      {/* Table */}
      <div style={s.tableWrap}>
        {loading ? (
          <div style={s.center}>
            <div style={s.spinner} />
            <p style={{ color: '#6B7280', marginTop: 12, fontSize: 13 }}>Loading plugins...</p>
          </div>
        ) : filtered.length === 0 ? (
          <div style={s.center}>
            <svg width="48" height="48" fill="none" stroke="#D1D5DB" viewBox="0 0 24 24">
              <path strokeWidth="1.5" strokeLinecap="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <p style={{ color: '#6B7280', marginTop: 12, fontSize: 14 }}>No plugins found</p>
          </div>
        ) : (
          <>
            {/* Header Row */}
            <div style={s.tableHeader}>
              <div style={s.checkCol}>
                <input
                  type="checkbox"
                  checked={selected.size === filtered.length && filtered.length > 0}
                  onChange={toggleAll}
                  style={s.checkbox}
                />
              </div>
              <div style={{ ...s.colName, fontWeight: 600, fontSize: 12, color: '#6B7280', textTransform: 'uppercase', letterSpacing: 0.5 }}>
                Plugins
              </div>
              <div style={{ ...s.colVersion, fontWeight: 600, fontSize: 12, color: '#6B7280', textTransform: 'uppercase', letterSpacing: 0.5 }}>
                Current Version
              </div>
              <div style={{ ...s.colActions, fontWeight: 600, fontSize: 12, color: '#6B7280', textTransform: 'uppercase', letterSpacing: 0.5 }}>
                Actions
              </div>
            </div>

            {/* Plugin Rows */}
            {filtered.map(plugin => (
              <div key={plugin.file} style={s.pluginRow} className="wp-plugin-row">
                <div style={s.checkCol}>
                  <input
                    type="checkbox"
                    checked={selected.has(plugin.file)}
                    onChange={() => toggleSelect(plugin.file)}
                    style={s.checkbox}
                  />
                </div>
                <div style={s.colName}>
                  <PluginIconBadge slug={plugin.slug} name={plugin.name} size={44} />
                  <div style={{ flex: 1 }}>
                    <div style={s.pluginName}>
                      {plugin.name}
                      {plugin.is_active && <span style={s.badgeActive}>Active</span>}
                      {!plugin.is_active && <span style={s.badgeInactive}>Inactive</span>}
                    </div>
                    <div style={s.pluginDesc}>{plugin.description}</div>
                    {plugin.author && <div style={s.pluginAuthor}>By {plugin.author}</div>}
                  </div>
                </div>
                <div style={s.colVersion}>
                  <span style={s.versionText}>Version {plugin.version}</span>
                </div>
                <div style={s.colActions}>
                  {plugin.update_available ? (
                    <button
                      style={s.updateBtn}
                      className="wp-update-btn"
                      onClick={() => handleUpdate(plugin.file)}
                      disabled={actionLoading === plugin.file}
                    >
                      {actionLoading === plugin.file ? (
                        <div style={{ ...s.spinner, width: 14, height: 14, borderWidth: 2 }} />
                      ) : (
                        <>Update to {plugin.update_available}</>
                      )}
                    </button>
                  ) : (
                    <span style={s.upToDate}>Up to date</span>
                  )}
                  <div style={s.actionBtns}>
                    {plugin.is_active ? (
                      <button
                        style={s.smallBtn}
                        className="wp-small-btn"
                        onClick={() => handleDeactivate(plugin.file)}
                        disabled={actionLoading === plugin.file}
                        title="Deactivate"
                      >
                        {actionLoading === plugin.file ? '...' : 'Deactivate'}
                      </button>
                    ) : (
                      <>
                        <button
                          style={{ ...s.smallBtn, color: '#059669' }}
                          className="wp-small-btn"
                          onClick={() => handleActivate(plugin.file)}
                          disabled={actionLoading === plugin.file}
                          title="Activate"
                        >
                          {actionLoading === plugin.file ? '...' : 'Activate'}
                        </button>
                        <button
                          style={{ ...s.smallBtn, color: '#DC2626' }}
                          className="wp-small-btn-del"
                          onClick={() => handleDelete(plugin.file)}
                          disabled={actionLoading === plugin.file}
                          title="Delete"
                        >
                          Delete
                        </button>
                      </>
                    )}
                  </div>
                </div>
              </div>
            ))}
          </>
        )}
      </div>

      {/* Bottom Actions */}
      {selected.size > 0 && (
        <div style={s.bulkBar}>
          <span style={{ fontSize: 13, color: '#374151' }}>{selected.size} selected</span>
          <div style={{ display: 'flex', gap: 8 }}>
            <button
              style={s.bulkBtn}
              className="wp-bulk-btn"
              onClick={handleBulkDeactivate}
              disabled={actionLoading === 'bulk'}
            >
              Deactivate
            </button>
            <button
              style={s.bulkBtnDanger}
              className="wp-bulk-btn-danger"
              onClick={handleBulkDelete}
              disabled={actionLoading === 'bulk'}
            >
              Deactivate & Delete
            </button>
          </div>
        </div>
      )}

      <style>{cssStr}</style>
    </div>
  );
}

const cssStr = `
  .wp-tab:hover { color: #111827 !important; }
  .wp-plugin-row:hover { background: #F9FAFB !important; }
  .wp-update-btn:hover:not(:disabled) { background: #15803D !important; }
  .wp-small-btn:hover { text-decoration: underline !important; }
  .wp-small-btn-del:hover { text-decoration: underline !important; }
  .wp-bulk-btn:hover:not(:disabled) { background: #F3F4F6 !important; }
  .wp-bulk-btn-danger:hover:not(:disabled) { background: #B91C1C !important; }
  @keyframes wp-spin { to { transform: rotate(360deg); } }
`;

const s: Record<string, React.CSSProperties> = {
  page: { padding: '0' },
  tabsRow: {
    display: 'flex', alignItems: 'center', justifyContent: 'space-between',
    borderBottom: '1px solid #E5E7EB', padding: '0 0 0 0', flexWrap: 'wrap', gap: 8,
    background: '#fff', marginBottom: 0,
  },
  tabsLeft: { display: 'flex', gap: 0 },
  tabBtn: {
    padding: '14px 20px', fontSize: 13, fontWeight: 500, color: '#6B7280',
    background: 'none', border: 'none', borderBottom: '2px solid transparent',
    cursor: 'pointer', whiteSpace: 'nowrap',
  },
  tabActive: {
    color: '#111827', fontWeight: 600, borderBottomColor: '#111827',
  },
  tabCount: {
    display: 'inline-flex', alignItems: 'center', justifyContent: 'center',
    marginLeft: 6, minWidth: 20, height: 20, padding: '0 6px',
    borderRadius: 10, fontSize: 11, fontWeight: 600,
    background: '#F3F4F6', color: '#374151',
  },
  searchBox: {
    display: 'flex', alignItems: 'center', gap: 6,
    padding: '7px 14px', border: '1px solid #E5E7EB', borderRadius: 8,
    background: '#fff', marginRight: 16,
  },
  searchInput: {
    border: 0, outline: 'none', fontSize: 12, background: 'transparent',
    width: 140, fontWeight: 500, letterSpacing: 0.5, color: '#374151',
  },
  error: {
    display: 'flex', alignItems: 'center', justifyContent: 'space-between',
    padding: '10px 16px', background: '#FEE2E2', color: '#991B1B',
    fontSize: 13, margin: '12px 16px 0',  borderRadius: 10,
  },
  dismissBtn: {
    background: 'none', border: 'none', fontSize: 18, cursor: 'pointer',
    color: '#991B1B', padding: '0 4px',
  },
  tableWrap: {
    background: '#fff', minHeight: 200,
  },
  tableHeader: {
    display: 'flex', alignItems: 'center', padding: '12px 20px',
    borderBottom: '1px solid #E5E7EB', background: '#FAFAFA',
  },
  checkCol: { width: 40, flexShrink: 0, display: 'flex', alignItems: 'center', justifyContent: 'center' },
  checkbox: { width: 16, height: 16, cursor: 'pointer', accentColor: '#111827' },
  colName: { flex: 1, display: 'flex', alignItems: 'flex-start', gap: 12, minWidth: 0 },
  colVersion: { width: 140, textAlign: 'center' as const, flexShrink: 0 },
  colActions: { width: 200, textAlign: 'center' as const, flexShrink: 0, display: 'flex', flexDirection: 'column' as const, alignItems: 'center', gap: 6 },
  pluginRow: {
    display: 'flex', alignItems: 'center', padding: '16px 20px',
    borderBottom: '1px solid #F3F4F6', transition: 'background 0.15s',
  },
  pluginIcon: {
    width: 44, height: 44, borderRadius: 10, background: '#F3F4F6',
    display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0,
  },
  pluginName: { fontSize: 14, fontWeight: 600, color: '#111827', marginBottom: 2 },
  pluginDesc: { fontSize: 12, color: '#6B7280', lineHeight: 1.4, maxWidth: 400, overflow: 'hidden', textOverflow: 'ellipsis', display: '-webkit-box', WebkitLineClamp: 2, WebkitBoxOrient: 'vertical' },
  pluginAuthor: { fontSize: 11, color: '#9CA3AF', marginTop: 2 },
  badgeActive: {
    display: 'inline-flex', padding: '1px 8px', borderRadius: 20,
    fontSize: 10, fontWeight: 600, background: '#DCFCE7', color: '#15803D', marginLeft: 8,
  },
  badgeInactive: {
    display: 'inline-flex', padding: '1px 8px', borderRadius: 20,
    fontSize: 10, fontWeight: 600, background: '#F3F4F6', color: '#6B7280', marginLeft: 8,
  },
  versionText: { fontSize: 13, color: '#374151', fontWeight: 500 },
  updateBtn: {
    padding: '5px 14px', background: '#16A34A', color: '#fff', border: 'none',
    borderRadius: 6, fontSize: 12, fontWeight: 600, cursor: 'pointer',
    display: 'inline-flex', alignItems: 'center', gap: 4,
  },
  upToDate: { fontSize: 12, color: '#16A34A', fontWeight: 500 },
  actionBtns: { display: 'flex', gap: 8 },
  smallBtn: {
    background: 'none', border: 'none', fontSize: 12, fontWeight: 500,
    color: '#6B7280', cursor: 'pointer', padding: '2px 4px',
  },
  center: {
    display: 'flex', flexDirection: 'column' as const, alignItems: 'center',
    justifyContent: 'center', padding: 60,
  },
  spinner: {
    width: 28, height: 28, border: '3px solid #E5E7EB', borderTopColor: '#111827',
    borderRadius: '50%', animation: 'wp-spin 0.6s linear infinite',
  },
  bulkBar: {
    display: 'flex', alignItems: 'center', justifyContent: 'space-between',
    padding: '12px 20px', background: '#fff', borderTop: '1px solid #E5E7EB',
  },
  bulkBtn: {
    padding: '8px 20px', border: '1px solid #D1D5DB', borderRadius: 8,
    background: '#fff', fontSize: 13, fontWeight: 500, cursor: 'pointer', color: '#374151',
  },
  bulkBtnDanger: {
    padding: '8px 20px', border: 'none', borderRadius: 8,
    background: '#DC2626', color: '#fff', fontSize: 13, fontWeight: 600, cursor: 'pointer',
  },
};