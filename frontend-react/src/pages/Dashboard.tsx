import { useState, useEffect, useMemo, useCallback, useRef, type RefCallback } from 'react';
import { Link } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext';
import { websiteService } from '../services/website.service';
import type { Website } from '../models/types';

/* Scale iframes to fit their container */
function useIframeScale() {
  const [scales, setScales] = useState<Record<number, number>>({});
  const observers = useRef<Map<number, ResizeObserver>>(new Map());
  const refCache = useRef<Map<number, RefCallback<HTMLDivElement>>>(new Map());

  const getRef = useCallback((siteId: number): RefCallback<HTMLDivElement> => {
    const cached = refCache.current.get(siteId);
    if (cached) return cached;

    const refFn: RefCallback<HTMLDivElement> = (el) => {
      const old = observers.current.get(siteId);
      if (old) { old.disconnect(); observers.current.delete(siteId); }
      if (!el) return;

      const update = () => {
        const w = el.clientWidth;
        if (w > 0) setScales(prev => {
          if (prev[siteId] === w / 1440) return prev;
          return { ...prev, [siteId]: w / 1440 };
        });
      };
      update();
      const ro = new ResizeObserver(update);
      ro.observe(el);
      observers.current.set(siteId, ro);
    };
    refCache.current.set(siteId, refFn);
    return refFn;
  }, []);

  useEffect(() => {
    return () => { observers.current.forEach(o => o.disconnect()); };
  }, []);

  return { scales, getRef };
}

const statusConfig: Record<string, { color: string; bg: string; label: string }> = {
  active: { color: '#059669', bg: '#ECFDF5', label: 'Active' },
  building: { color: '#D97706', bg: '#FFFBEB', label: 'Building' },
  pending: { color: '#2563EB', bg: '#EFF6FF', label: 'Pending' },
  failed: { color: '#DC2626', bg: '#FEF2F2', label: 'Failed' },
};

export default function Dashboard() {
  const { user, logout } = useAuth();
  const [websites, setWebsites] = useState<Website[]>([]);
  const [loading, setLoading] = useState(true);
  const [searchQuery, setSearchQuery] = useState('');
  const [openMenuId, setOpenMenuId] = useState<number | null>(null);
  const [addMenuOpen, setAddMenuOpen] = useState(false);
  const addMenuRef = useRef<HTMLDivElement>(null);
  const { scales, getRef } = useIframeScale();

  useEffect(() => {
    websiteService.list()
      .then((res) => setWebsites(res.data))
      .catch(() => {})
      .finally(() => setLoading(false));
  }, []);

  useEffect(() => {
    const handler = (e: MouseEvent) => {
      if (addMenuRef.current && !addMenuRef.current.contains(e.target as Node)) {
        setAddMenuOpen(false);
      }
    };
    document.addEventListener('mousedown', handler);
    return () => document.removeEventListener('mousedown', handler);
  }, []);

  // Close dropdown on outside click
  useEffect(() => {
    const handler = () => {
      if (openMenuId !== null) setOpenMenuId(null);
    };
    document.addEventListener('click', handler);
    return () => document.removeEventListener('click', handler);
  }, [openMenuId]);

  const filteredWebsites = useMemo(() => {
    if (!searchQuery.trim()) return websites;
    const q = searchQuery.toLowerCase();
    return websites.filter(
      (w) =>
        w.name.toLowerCase().includes(q) ||
        w.business_type?.toLowerCase().includes(q) ||
        w.slug?.toLowerCase().includes(q),
    );
  }, [websites, searchQuery]);

  const getUserInitial = useCallback(() => {
    return user?.name?.charAt(0).toUpperCase() || 'U';
  }, [user]);

  const toggleMenu = useCallback((siteId: number, e: React.MouseEvent) => {
    e.stopPropagation();
    e.preventDefault();
    setOpenMenuId((prev) => (prev === siteId ? null : siteId));
  }, []);

  const deleteSite = useCallback((site: Website, e: React.MouseEvent) => {
    e.stopPropagation();
    setOpenMenuId(null);
    if (!confirm(`Delete "${site.name}"? This cannot be undone.`)) return;
    websiteService.delete(site.id).then(() => {
      setWebsites((prev) => prev.filter((w) => w.id !== site.id));
    });
  }, []);

  const getStatus = (status: string) => statusConfig[status] || statusConfig['pending'];

  const getScreenshotUrl = (site: Website) => {
    if (site.url && site.status === 'active') {
      return site.url;
    }
    return null;
  };

  return (
    <div className="db-root">
      {/* ─── Sidebar ─── */}
      <aside className="db-sidebar">
        <div className="db-sidebar-top">
          {/* Workspace */}
          <div className="db-ws">
            <div className="db-ws-avatar">
              <img src="/assets/logo/Web-New-Biz-logo.png" alt="" className="db-ws-logo" />
            </div>
            <div className="db-ws-info">
              <span className="db-ws-name">{user?.name || 'My'} workspace</span>
              <span className="db-ws-role">Role: owner</span>
            </div>
            <button className="db-ws-toggle">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
          </div>

          {/* Invite users */}
          <a href="#" className="db-nav-item db-invite" onClick={e => e.preventDefault()}>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
              <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/>
            </svg>
            <span>Invite users</span>
          </a>

          {/* Main Nav */}
          <nav className="db-nav">
            <Link to="/dashboard" className="db-nav-item active">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
              </svg>
              <span>Websites</span>
            </Link>
            <a href="#" className="db-nav-item" onClick={e => e.preventDefault()}>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
              </svg>
              <span>Domains portfolio</span>
            </a>
            <a href="#" className="db-nav-item" onClick={e => e.preventDefault()}>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
                <polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/>
              </svg>
              <span>API</span>
            </a>
          </nav>

          {/* Resources */}
          <div className="db-resources">
            <div className="db-resources-label">Resources</div>
            <a href="#" className="db-nav-item" onClick={e => e.preventDefault()}>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
              </svg>
              <span>Usage summary</span>
            </a>
            <a href="#" className="db-nav-item" onClick={e => e.preventDefault()}>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
              </svg>
              <span>Knowledge base</span>
            </a>
            <a href="#" className="db-nav-item" onClick={e => e.preventDefault()}>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>
              </svg>
              <span>What's new</span>
            </a>
          </div>
        </div>

        {/* Sidebar Footer */}
        <div className="db-sidebar-foot">
          <button onClick={logout} className="db-nav-item db-logout">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
            <span>Log out</span>
          </button>
        </div>
      </aside>

      {/* ─── Main Content ─── */}
      <main className="db-main">
        {/* Top Bar */}
        <header className="db-topbar">
          <div className="db-topbar-left">
            <div className="db-search-wrap">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" strokeWidth="2" strokeLinecap="round">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
              </svg>
              <input
                type="text"
                placeholder="Search"
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                className="db-search-input"
              />
            </div>
          </div>
          <div className="db-topbar-right">
            <div className="db-add-wrap" ref={addMenuRef}>
              <button className="db-btn-add" onClick={() => setAddMenuOpen(v => !v)}>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round">
                  <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Add website
              </button>
              {addMenuOpen && (
                <div className="db-add-dropdown">
                  <Link to="/builder" className="db-add-item" onClick={() => setAddMenuOpen(false)}>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
                      <path d="M12 2a7 7 0 0 1 7 7c0 2.38-1.19 4.47-3 5.74V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2.26C6.19 13.47 5 11.38 5 9a7 7 0 0 1 7-7z"/>
                      <path d="M10 22h4"/>
                    </svg>
                    <div>
                      <div className="db-add-title">Create with AI</div>
                      <div className="db-add-desc">Describe your business and let AI build it</div>
                    </div>
                  </Link>
                  <Link to="/builder" className="db-add-item" onClick={() => setAddMenuOpen(false)}>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
                      <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
                    </svg>
                    <div>
                      <div className="db-add-title">Blank WordPress</div>
                      <div className="db-add-desc">Start with a fresh WordPress install</div>
                    </div>
                  </Link>
                  <Link to="/code-builder" className="db-add-item" onClick={() => setAddMenuOpen(false)}>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
                      <polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/>
                    </svg>
                    <div>
                      <div className="db-add-title">Code Builder</div>
                      <div className="db-add-desc">Build with AI - HTML, React, Next.js</div>
                    </div>
                  </Link>
                </div>
              )}
            </div>
            <div className="db-user-avatar">{getUserInitial()}</div>
          </div>
        </header>

        {/* Scrollable Content */}
        <div className="db-scroll">
          <div className="db-content">
            {loading ? (
              <div className="db-loading">
                <div className="db-spinner" />
                <p>Loading your websites...</p>
              </div>
            ) : filteredWebsites.length === 0 && websites.length === 0 ? (
              <div className="db-empty">
                <div className="db-empty-icon">
                  <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" strokeWidth="1.2" strokeLinecap="round" strokeLinejoin="round">
                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                    <line x1="8" y1="21" x2="16" y2="21"/>
                    <line x1="12" y1="17" x2="12" y2="21"/>
                  </svg>
                </div>
                <h3 className="db-empty-title">No websites yet</h3>
                <p className="db-empty-text">Click "Add website" to create your first AI-powered website</p>
                <Link to="/builder" className="db-btn-create">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                  </svg>
                  Create your first website
                </Link>
              </div>
            ) : filteredWebsites.length === 0 ? (
              <div className="db-empty">
                <p className="db-empty-text">No websites match "{searchQuery}"</p>
              </div>
            ) : (
              <div className="db-grid">
                {filteredWebsites.map((site) => {
                  const status = getStatus(site.status);
                  const screenshotUrl = getScreenshotUrl(site);
                  return (
                    <div key={site.id} className="db-card">
                      {/* Preview area with screenshot */}
                      <div className="db-card-preview" ref={getRef(site.id)}>
                        {screenshotUrl ? (
                          <div className="db-card-screenshot">
                            <iframe
                              src={screenshotUrl}
                              title={site.name}
                              className="db-card-iframe"
                              style={{ transform: `scale(${scales[site.id] || 0.3})` }}
                              loading="lazy"
                              sandbox="allow-same-origin"
                              tabIndex={-1}
                            />
                          </div>
                        ) : (
                          <div className="db-card-placeholder">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" strokeWidth="1.2" strokeLinecap="round" strokeLinejoin="round">
                              <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                              <line x1="8" y1="21" x2="16" y2="21"/>
                              <line x1="12" y1="17" x2="12" y2="21"/>
                            </svg>
                            {site.status === 'building' && (
                              <div className="db-card-building">
                                <div className="db-mini-spinner" />
                                <span>Building...</span>
                              </div>
                            )}
                          </div>
                        )}

                        {/* Action icons overlay — top */}
                        <div className="db-card-overlay">
                          {/* PageSpeed score badge */}
                          {site.status === 'active' && (
                            <div className="db-score-badge">
                              <svg viewBox="0 0 36 36">
                                <circle cx="18" cy="18" r="15.5" fill="none" stroke="#e6e9ed" strokeWidth="2.5"/>
                                <circle cx="18" cy="18" r="15.5" fill="none" stroke="#22c55e" strokeWidth="2.5"
                                  strokeDasharray="97.39" strokeDashoffset="0"
                                  transform="rotate(-90 18 18)" strokeLinecap="round"/>
                              </svg>
                              <span className="db-score-text">100</span>
                            </div>
                          )}

                          {/* Status badge for non-active */}
                          {site.status !== 'active' && (
                            <div className="db-status-pill" style={{ background: status.bg, color: status.color }}>
                              {(site.status === 'building' || site.status === 'pending') && (
                                <span className="db-dot-pulse" style={{ background: status.color }} />
                              )}
                              {status.label}
                            </div>
                          )}

                          {/* Quick action icons — right side */}
                          {site.status === 'active' && site.url && (
                            <div className="db-card-icons">
                              <a
                                href={`${site.auto_login_url || site.url + '/wp-admin/'}post.php?post=${site.home_page_id || ''}&action=elementor`}
                                target="_blank" rel="noreferrer"
                                className="db-icon-btn" title="Edit with Elementor"
                                onClick={e => e.stopPropagation()}
                              >
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                              </a>
                              <a href={site.url} target="_blank" rel="noreferrer" className="db-icon-btn" title="View website" onClick={e => e.stopPropagation()}>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                                </svg>
                              </a>
                              <a href={site.wp_admin_url || site.url + '/wp-admin/'} target="_blank" rel="noreferrer" className="db-icon-btn db-icon-wp" title="WordPress Admin" onClick={e => e.stopPropagation()}>
                                <span style={{ fontWeight: 800, fontSize: '13px' }}>W</span>
                              </a>
                              <button className="db-icon-btn" title="More options" onClick={(e) => toggleMenu(site.id, e)}>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                  <circle cx="12" cy="5" r="2"/><circle cx="12" cy="12" r="2"/><circle cx="12" cy="19" r="2"/>
                                </svg>
                              </button>
                            </div>
                          )}

                          {/* Dropdown menu */}
                          {openMenuId === site.id && (
                            <div className="db-dropdown" onClick={(e) => e.stopPropagation()}>
                              {site.url && (
                                <>
                                  <a href={site.url} target="_blank" rel="noreferrer" className="db-dropdown-item">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    View website
                                  </a>
                                  <a href={site.wp_admin_url || site.url + '/wp-admin/'} target="_blank" rel="noreferrer" className="db-dropdown-item">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/></svg>
                                    WordPress admin
                                  </a>
                                  <div className="db-dropdown-divider" />
                                </>
                              )}
                              <Link to={`/websites/${site.id}`} className="db-dropdown-item">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                                Settings
                              </Link>
                              <button className="db-dropdown-item db-dropdown-danger" onClick={(e) => deleteSite(site, e)}>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                Delete
                              </button>
                            </div>
                          )}
                        </div>
                      </div>

                      {/* Card Footer */}
                      <div className="db-card-footer">
                        <div className="db-card-info">
                          <h3 className="db-card-name">{site.name}</h3>
                          <span className="db-card-url">
                            {site.url
                              ? site.url.replace('http://', '').replace('https://', '')
                              : site.slug ? `${site.slug}.webnewbiz.cloud` : site.business_type || 'Website'}
                          </span>
                        </div>
                        {site.status === 'active' && (
                          <Link to={`/websites/${site.id}`} className="db-btn-manage" onClick={(e) => e.stopPropagation()}>
                            Manage
                          </Link>
                        )}
                        {(site.status === 'building' || site.status === 'pending') && (
                          <Link to={`/builder/progress/${site.id}`} className="db-btn-progress" onClick={(e) => e.stopPropagation()}>
                            <div className="db-mini-spinner" />
                            Building
                          </Link>
                        )}
                        {site.status === 'failed' && (
                          <button className="db-btn-retry" onClick={(e) => deleteSite(site, e)}>
                            Retry
                          </button>
                        )}
                      </div>
                    </div>
                  );
                })}
              </div>
            )}
          </div>
        </div>

        {/* Talk to sales floating button */}
        <button className="db-chat-fab">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
          </svg>
          Talk to sales
        </button>
      </main>

      <style>{dashboardCSS}</style>
    </div>
  );
}

const dashboardCSS = `
  /* ──── Reset for dashboard ──── */
  .db-root {
    display: flex;
    height: 100vh;
    background: #f0f0f0;
    font-family: -apple-system, BlinkMacSystemFont, 'Inter', 'Segoe UI', sans-serif;
    color: #1e293b;
  }

  /* ──── Sidebar ──── */
  .db-sidebar {
    width: 220px;
    background: #fff;
    border-right: 1px solid #e5e7eb;
    display: flex;
    flex-direction: column;
    flex-shrink: 0;
  }
  .db-sidebar-top {
    flex: 1;
    overflow-y: auto;
    padding: 0;
  }

  /* Workspace */
  .db-ws {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 16px 16px 12px;
    border-bottom: 1px solid #f1f5f9;
  }
  .db-ws-avatar {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    overflow: hidden;
  }
  .db-ws-logo {
    width: 100%;
    height: 100%;
    object-fit: contain;
  }
  .db-ws-info {
    display: flex;
    flex-direction: column;
    min-width: 0;
    flex: 1;
  }
  .db-ws-name {
    font-size: 13px;
    font-weight: 600;
    color: #1e293b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.3;
  }
  .db-ws-role {
    font-size: 11px;
    color: #94a3b8;
    line-height: 1.3;
  }
  .db-ws-toggle {
    background: none;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    padding: 4px;
    flex-shrink: 0;
  }

  /* Invite */
  .db-invite {
    margin: 8px 10px 4px;
    color: #6366f1 !important;
  }

  /* Nav */
  .db-nav {
    padding: 4px 10px;
    display: flex;
    flex-direction: column;
    gap: 1px;
  }
  .db-nav-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 10px;
    border-radius: 8px;
    color: #64748b;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.12s;
    cursor: pointer;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    line-height: 1;
    font-family: inherit;
  }
  .db-nav-item:hover {
    background: #f8fafc;
    color: #334155;
  }
  .db-nav-item.active {
    background: #f1f5f9;
    color: #1e293b;
    font-weight: 600;
  }

  /* Resources */
  .db-resources {
    padding: 4px 10px;
    margin-top: 12px;
    border-top: 1px solid #f1f5f9;
  }
  .db-resources-label {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: #94a3b8;
    padding: 10px 10px 6px;
  }

  /* Sidebar Footer */
  .db-sidebar-foot {
    padding: 8px 10px 12px;
    border-top: 1px solid #f1f5f9;
  }
  .db-logout {
    color: #94a3b8 !important;
    font-size: 12px !important;
  }
  .db-logout:hover {
    color: #ef4444 !important;
    background: #fef2f2 !important;
  }

  /* ──── Main ──── */
  .db-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    min-width: 0;
    position: relative;
  }

  /* Top Bar */
  .db-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 24px;
    background: #fff;
    border-bottom: 1px solid #e5e7eb;
    min-height: 52px;
    flex-shrink: 0;
    gap: 16px;
  }
  .db-topbar-left {
    flex: 1;
    max-width: 320px;
  }
  .db-topbar-right {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  /* Search */
  .db-search-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 7px 12px;
    transition: all 0.15s;
  }
  .db-search-wrap:focus-within {
    border-color: #94a3b8;
    background: #fff;
  }
  .db-search-input {
    border: none;
    background: none;
    outline: none;
    font-size: 13px;
    color: #1e293b;
    width: 100%;
    font-family: inherit;
  }
  .db-search-input::placeholder { color: #94a3b8; }

  /* Add Website Button */
  .db-add-wrap { position: relative; }
  .db-btn-add {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 16px;
    background: #37B34A;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    font-family: inherit;
    transition: background 0.15s;
  }
  .db-btn-add:hover { background: #2f9e40; }

  .db-add-dropdown {
    position: absolute;
    top: calc(100% + 6px);
    right: 0;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    box-shadow: 0 12px 40px rgba(0,0,0,0.12);
    z-index: 50;
    min-width: 280px;
    padding: 6px;
  }
  .db-add-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 12px;
    border-radius: 8px;
    color: #1e293b;
    text-decoration: none;
    transition: background 0.1s;
    cursor: pointer;
  }
  .db-add-item:hover { background: #f8fafc; }
  .db-add-item svg { margin-top: 2px; flex-shrink: 0; }
  .db-add-title { font-size: 13px; font-weight: 600; }
  .db-add-desc { font-size: 11px; color: #94a3b8; margin-top: 2px; }

  /* User Avatar */
  .db-user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #6366f1;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 700;
    font-size: 13px;
    cursor: pointer;
    flex-shrink: 0;
  }

  /* Scrollable area */
  .db-scroll {
    flex: 1;
    overflow-y: auto;
    padding: 24px;
  }
  .db-content {
    max-width: 1400px;
    margin: 0 auto;
  }

  /* Loading */
  .db-loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 80px 0;
    color: #94a3b8;
    gap: 12px;
  }
  .db-loading p { font-size: 13px; margin: 0; }
  .db-spinner {
    width: 28px;
    height: 28px;
    border: 2.5px solid #e2e8f0;
    border-top-color: #6366f1;
    border-radius: 50%;
    animation: db-spin 0.7s linear infinite;
  }
  @keyframes db-spin { to { transform: rotate(360deg); } }

  /* Empty */
  .db-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 80px 0;
    gap: 12px;
  }
  .db-empty-icon {
    width: 80px;
    height: 80px;
    background: #f1f5f9;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 4px;
  }
  .db-empty-title {
    font-size: 18px;
    font-weight: 600;
    color: #334155;
    margin: 0;
  }
  .db-empty-text {
    font-size: 14px;
    color: #94a3b8;
    margin: 0;
  }
  .db-btn-create {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-top: 8px;
    padding: 10px 20px;
    background: #37B34A;
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: background 0.15s;
  }
  .db-btn-create:hover { background: #2f9e40; }

  /* ──── Grid ──── */
  .db-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
  }
  @media (max-width: 1200px) {
    .db-grid { grid-template-columns: repeat(2, 1fr); }
  }
  @media (max-width: 768px) {
    .db-grid { grid-template-columns: 1fr; }
  }

  /* ──── Card ──── */
  .db-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    transition: box-shadow 0.25s, transform 0.25s;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
  }
  .db-card:hover {
    box-shadow: 0 8px 30px rgba(0,0,0,0.1);
    transform: translateY(-2px);
  }

  /* Card Preview */
  .db-card-preview {
    position: relative;
    padding-top: 56%;  /* 16:9 ratio — looks like a real browser */
    background: linear-gradient(135deg, #f8f9fd 0%, #eef1f8 50%, #e8ecf4 100%);
    overflow: hidden;
  }

  /* Screenshot iframe — fills the preview via absolute positioning */
  .db-card-screenshot {
    position: absolute;
    inset: 0;
    overflow: hidden;
    pointer-events: none;
  }
  .db-card-iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 1440px;
    height: 900px;
    border: none;
    pointer-events: none;
    transform-origin: top left;
  }

  /* Placeholder for non-active sites */
  .db-card-placeholder {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    background: linear-gradient(135deg, #f8f9fd 0%, #eef1f8 50%, #e8ecf4 100%);
  }
  .db-card-building {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: #94a3b8;
  }
  .db-mini-spinner {
    width: 12px;
    height: 12px;
    border: 2px solid #e2e8f0;
    border-top-color: #6366f1;
    border-radius: 50%;
    animation: db-spin 0.7s linear infinite;
    flex-shrink: 0;
  }

  /* Card overlay */
  .db-card-overlay {
    position: absolute;
    inset: 0;
    padding: 10px 12px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    z-index: 2;
    pointer-events: none;
  }
  .db-card-overlay > * {
    pointer-events: auto;
  }

  /* PageSpeed score badge — 10Web style large green circle */
  .db-score-badge {
    position: relative;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff;
    border-radius: 50%;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  }
  .db-score-badge svg {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
  }
  .db-score-text {
    font-size: 11px;
    font-weight: 800;
    color: #22c55e;
    z-index: 1;
  }

  /* Status pill */
  .db-status-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 12px 4px 8px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    line-height: 1.5;
  }
  .db-dot-pulse {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    flex-shrink: 0;
    animation: db-pulse 1.5s ease-in-out infinite;
  }
  @keyframes db-pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
  }

  /* Quick action icons — ALWAYS visible like 10Web */
  .db-card-icons {
    display: flex;
    gap: 5px;
  }
  .db-icon-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #475569;
    cursor: pointer;
    transition: all 0.12s;
    border: none;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    text-decoration: none;
    padding: 0;
    font-family: inherit;
  }
  .db-icon-btn:hover {
    background: #f8fafc;
    box-shadow: 0 3px 10px rgba(0,0,0,0.14);
    color: #1e293b;
  }
  .db-icon-wp {
    color: #21759b !important;
    font-family: Georgia, serif;
  }

  /* Dropdown */
  .db-dropdown {
    position: absolute;
    top: 44px;
    right: 10px;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    z-index: 20;
    min-width: 180px;
    padding: 4px;
  }
  .db-dropdown-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 10px;
    font-size: 13px;
    color: #374151;
    text-decoration: none;
    border-radius: 6px;
    transition: background 0.1s;
    cursor: pointer;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    font-family: inherit;
  }
  .db-dropdown-item:hover { background: #f8fafc; }
  .db-dropdown-danger { color: #dc2626; }
  .db-dropdown-danger:hover { background: #fef2f2 !important; }
  .db-dropdown-divider {
    height: 1px;
    background: #f1f5f9;
    margin: 4px 0;
  }

  /* Card Footer */
  .db-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 14px;
    gap: 12px;
    border-top: 1px solid #f1f5f9;
  }
  .db-card-info {
    min-width: 0;
    flex: 1;
  }
  .db-card-name {
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .db-card-url {
    font-size: 12px;
    color: #94a3b8;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: block;
  }

  /* Manage button */
  .db-btn-manage {
    padding: 6px 20px;
    background: #1e293b;
    color: #fff;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.12s;
    cursor: pointer;
    white-space: nowrap;
    flex-shrink: 0;
    border: none;
  }
  .db-btn-manage:hover {
    background: #334155;
  }

  /* Building button */
  .db-btn-progress {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    background: #fffbeb;
    color: #92400e;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    border: 1px solid #fde68a;
    white-space: nowrap;
    flex-shrink: 0;
  }

  /* Retry button */
  .db-btn-retry {
    padding: 6px 14px;
    background: #fef2f2;
    color: #991b1b;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    border: 1px solid #fecaca;
    cursor: pointer;
    white-space: nowrap;
    flex-shrink: 0;
    font-family: inherit;
  }
  .db-btn-retry:hover { background: #fee2e2; }

  /* Chat FAB */
  .db-chat-fab {
    position: fixed;
    bottom: 24px;
    left: 244px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: #1e293b;
    color: #fff;
    border: none;
    border-radius: 24px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    font-family: inherit;
    transition: background 0.15s;
    z-index: 40;
  }
  .db-chat-fab:hover { background: #334155; }

  /* ──── Responsive ──── */
  @media (max-width: 768px) {
    .db-sidebar { display: none; }
    .db-chat-fab { left: 24px; }
    .db-scroll { padding: 16px; }
    .db-topbar { padding: 10px 16px; }
  }
`;
