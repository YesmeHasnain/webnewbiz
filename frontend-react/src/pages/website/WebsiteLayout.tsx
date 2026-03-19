import { useState, useEffect } from 'react';
import { useParams, useNavigate, Outlet, Link } from 'react-router-dom';
import { websiteService } from '../../services/website.service';
import { wpManagerService } from '../../services/wp-manager.service';
import WebsiteSidebar from './WebsiteSidebar';
import AiChatWidget from '../../components/AiChatWidget';
import type { Website } from '../../models/types';

export default function WebsiteLayout() {
  const { id } = useParams<{ id: string }>();
  const navigate = useNavigate();
  const [website, setWebsite] = useState<Website | null>(null);
  const [loading, setLoading] = useState(true);
  const [mobileOpen, setMobileOpen] = useState(false);
  const [hasWooCommerce, setHasWooCommerce] = useState(false);

  useEffect(() => {
    const websiteId = Number(id);
    if (!websiteId) {
      navigate('/dashboard');
      return;
    }
    websiteService.get(websiteId)
      .then((res) => {
        setWebsite(res.data);
        setLoading(false);
        // Check if WooCommerce is active
        if (res.data.status === 'active') {
          wpManagerService.getOverview(websiteId)
            .then((ov) => setHasWooCommerce(ov.data.data?.woocommerce_active ?? false))
            .catch(() => {});
        }
      })
      .catch(() => {
        setLoading(false);
        navigate('/dashboard');
      });
  }, [id, navigate]);

  if (loading) {
    return (
      <div style={styles.loading}>
        <svg style={styles.spinnerSvg} viewBox="0 0 24 24" fill="none" className="wl-spinner">
          <circle cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="3" opacity="0.2" />
          <path d="M12 2a10 10 0 0 1 10 10" stroke="currentColor" strokeWidth="3" strokeLinecap="round" />
        </svg>
        <span>Loading website...</span>
        <style>{spinnerKeyframes}</style>
      </div>
    );
  }

  if (!website) return null;

  return (
    <>
      {/* Mobile Hamburger */}
      <button style={styles.mobileBtn} className="wl-mobile-btn" onClick={() => setMobileOpen(true)}>
        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>

      {/* Mobile Overlay */}
      {mobileOpen && (
        <div style={styles.mobileOverlay} onClick={() => setMobileOpen(false)} />
      )}

      <div style={styles.layout}>
        {/* Icon Rail */}
        <aside style={styles.rail} className="wl-rail">
          <button style={styles.railBtn} title="Workspace">
            <span style={styles.railBadge}>{website.name?.charAt(0)?.toUpperCase() || 'W'}</span>
          </button>
          <div style={{ flex: 1 }} />
          <Link to="/dashboard" style={styles.railBtn} title="My Websites">
            <svg style={styles.railIco} viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" d="M21 12a9 9 0 11-9-9 9 9 0 019 9z"/>
              <path strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" d="M3 12h18"/>
              <path strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" d="M12 3c3 3.5 3 14 0 18"/>
              <path strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" d="M12 3c-3 3.5-3 14 0 18"/>
            </svg>
          </Link>
        </aside>

        {/* Main Sidebar */}
        <WebsiteSidebar
          website={website}
          mobileOpen={mobileOpen}
          onCloseMobile={() => setMobileOpen(false)}
          hasWooCommerce={hasWooCommerce}
        />

        {/* Content Area */}
        <div style={styles.content}>
          {/* Top Bar */}
          <header style={styles.topbar}>
            <div style={styles.topbarLeft}>
              <div style={styles.topbarNameRow}>
                <h1 style={styles.topbarTitle}>{website.name}</h1>
                <span style={styles.publicBadge}>
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                    <circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/>
                  </svg>
                  Public
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><path d="M6 9l6 6 6-6"/></svg>
                </span>
              </div>
              {website.url && (
                <a href={website.url} target="_blank" rel="noreferrer" style={styles.topbarLink} className="wl-topbar-link">
                  {website.url}
                </a>
              )}
            </div>
            <div style={styles.topbarActions} className="wl-topbar-actions">
              {website.url && (
                <>
                  <button style={styles.btnIcon} className="wl-btn-icon" title="AI">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
                      <path d="M12 2l1.09 3.26L16.36 6l-3.27 1.09L12 10.36l-1.09-3.27L7.64 6l3.27-1.09L12 2z"/>
                      <path d="M18 12l.7 2.1L20.8 15l-2.1.7L18 17.8l-.7-2.1L15.2 15l2.1-.7L18 12z"/>
                      <path d="M7 14l.5 1.5L9 16l-1.5.5L7 18l-.5-1.5L5 16l1.5-.5L7 14z"/>
                    </svg>
                  </button>
                  <a
                    href={website.elementor_url || website.url + '/wp-admin/'}
                    target="_blank"
                    rel="noreferrer"
                    style={styles.btnPrimary}
                    className="wl-btn-primary"
                  >
                    Edit your website
                  </a>
                  <a
                    href={website.auto_login_url || website.wp_admin_url || website.url + '/wp-admin/'}
                    target="_blank"
                    rel="noreferrer"
                    style={styles.btnIcon}
                    className="wl-btn-icon"
                    title="WordPress Admin"
                  >
                    <span style={styles.wpIcon}>W</span>
                  </a>
                  <button style={styles.btnIcon} className="wl-btn-icon" title="More options">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                      <circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/>
                    </svg>
                  </button>
                </>
              )}
            </div>
          </header>

          {/* Router Outlet */}
          <main style={styles.main}>
            <Outlet context={{ website }} />
          </main>
        </div>
      </div>

      {/* AI Chat Widget */}
      {website.status === 'active' && (
        <AiChatWidget
          websiteId={website.id}
          websiteName={website.name}
          baseRoute={`/websites/${website.id}`}
        />
      )}

      <style>{cssStyles}</style>
    </>
  );
}

const spinnerKeyframes = `
  @keyframes wl-spin {
    to { transform: rotate(360deg); }
  }
  .wl-spinner {
    animation: wl-spin 1s linear infinite;
  }
`;

const cssStyles = `
  .wl-mobile-btn {
    display: none !important;
  }
  .wl-btn-icon:hover {
    background: #F9FAFB !important;
    border-color: #9CA3AF !important;
  }
  .wl-btn-primary:hover {
    background: #374151 !important;
  }
  .wl-topbar-link:hover {
    color: #1D4ED8 !important;
  }
  @media (max-width: 768px) {
    .wl-mobile-btn {
      display: flex !important;
    }
    .wl-rail {
      display: none !important;
    }
    .wl-topbar-actions {
      display: none !important;
    }
  }
`;

const styles: Record<string, React.CSSProperties> = {
  loading: {
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    justifyContent: 'center',
    height: '100vh',
    gap: 16,
    color: '#6B7280',
    fontSize: 14,
  },
  spinnerSvg: {
    width: 32,
    height: 32,
    color: '#3B82F6',
  },
  mobileBtn: {
    display: 'none',
    position: 'fixed',
    top: 16,
    left: 16,
    zIndex: 10000,
    width: 44,
    height: 44,
    background: 'white',
    border: '1px solid #E5E7EB',
    borderRadius: 12,
    boxShadow: '0 4px 12px rgba(0,0,0,0.1)',
    cursor: 'pointer',
    alignItems: 'center',
    justifyContent: 'center',
  },
  mobileOverlay: {
    position: 'fixed',
    inset: 0,
    background: 'rgba(0,0,0,0.5)',
    zIndex: 9997,
  },
  layout: {
    display: 'flex',
    minHeight: '100vh',
  },
  rail: {
    width: 53,
    background: '#f7f9fa',
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    padding: '14px 10px',
    gap: 12,
    flexShrink: 0,
  },
  railBtn: {
    width: 38,
    height: 38,
    borderRadius: 10,
    background: '#fff',
    border: '1px solid #E5E7EB',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    textDecoration: 'none',
    color: '#111827',
    boxShadow: '0 1px 0 rgba(0,0,0,.02)',
    cursor: 'pointer',
  },
  railBadge: {
    fontWeight: 700,
    fontSize: 14,
    lineHeight: 1,
  },
  railIco: {
    width: 18,
    height: 18,
  },
  content: {
    flex: 1,
    display: 'flex',
    flexDirection: 'column',
    overflow: 'hidden',
    minWidth: 0,
  },
  topbar: {
    background: '#fff',
    borderBottom: '1px solid #E5E7EB',
    padding: '16px 28px',
    display: 'flex',
    alignItems: 'flex-start',
    justifyContent: 'space-between',
    gap: 16,
    flexShrink: 0,
  },
  topbarLeft: {
    minWidth: 0,
  },
  topbarNameRow: {
    display: 'flex',
    alignItems: 'center',
    gap: 10,
    marginBottom: 2,
  },
  topbarTitle: {
    fontSize: 22,
    fontWeight: 700,
    color: '#111827',
    margin: 0,
    whiteSpace: 'nowrap',
    overflow: 'hidden',
    textOverflow: 'ellipsis',
  },
  publicBadge: {
    display: 'inline-flex',
    alignItems: 'center',
    gap: 4,
    padding: '3px 10px',
    borderRadius: 20,
    border: '1px solid #D1D5DB',
    fontSize: 12,
    fontWeight: 500,
    color: '#374151',
    background: '#fff',
    cursor: 'pointer',
    whiteSpace: 'nowrap' as const,
  },
  topbarLink: {
    fontSize: 13,
    color: '#6B7280',
    textDecoration: 'none',
  },
  topbarActions: {
    display: 'flex',
    alignItems: 'center',
    gap: 8,
    flexShrink: 0,
    marginTop: 4,
  },
  btnIcon: {
    width: 36,
    height: 36,
    borderRadius: 8,
    border: '1px solid #D1D5DB',
    background: '#fff',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    cursor: 'pointer',
    color: '#374151',
    textDecoration: 'none',
  },
  wpIcon: {
    fontWeight: 800,
    fontSize: 14,
    color: '#374151',
  },
  btnPrimary: {
    padding: '8px 18px',
    background: '#111827',
    color: '#fff',
    borderRadius: 8,
    fontSize: 13,
    fontWeight: 600,
    textDecoration: 'none',
    cursor: 'pointer',
    display: 'inline-flex',
    alignItems: 'center',
    height: 36,
  },
  main: {
    flex: 1,
    overflowY: 'auto',
    padding: 20,
    background: '#F9FAFB',
  },
};
