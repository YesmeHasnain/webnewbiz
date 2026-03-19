import { useState, useCallback, useRef, useEffect } from 'react';
import { NavLink } from 'react-router-dom';
import type { Website } from '../../models/types';

interface NavDropdown {
  key: string;
  label: string;
  icon: string;
  children: { label: string; route: string }[];
}

interface WebsiteSidebarProps {
  website: Website;
  mobileOpen: boolean;
  onCloseMobile: () => void;
  hasWooCommerce?: boolean;
}

export default function WebsiteSidebar({ website, mobileOpen, onCloseMobile, hasWooCommerce = false }: WebsiteSidebarProps) {
  const [openDropdowns, setOpenDropdowns] = useState<Record<string, boolean>>({});
  const [iframeScale, setIframeScale] = useState(0.15);
  const thumbRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    const el = thumbRef.current;
    if (!el) return;
    const update = () => {
      const w = el.clientWidth;
      if (w > 0) setIframeScale(w / 1440);
    };
    update();
    const ro = new ResizeObserver(update);
    ro.observe(el);
    return () => ro.disconnect();
  }, []);

  const baseRoute = `/websites/${website.id}`;

  const toggleDropdown = useCallback((key: string) => {
    setOpenDropdowns((prev) => ({ ...prev, [key]: !prev[key] }));
  }, []);

  const onNavClick = useCallback(() => {
    onCloseMobile();
  }, [onCloseMobile]);

  const navItems = [
    { label: 'Main', route: 'manage', icon: 'home' },
    { label: 'AI Builder', route: 'ai-builder', icon: 'bulb' },
  ];

  const ecommerceDropdown: NavDropdown = {
    key: 'ecommerce',
    label: 'Ecommerce',
    icon: 'bag',
    children: [
      { label: 'Orders', route: 'ecommerce/orders' },
      { label: 'Products', route: 'ecommerce/products' },
      { label: 'Customers', route: 'ecommerce/customers' },
      { label: 'Emails', route: 'ecommerce/emails' },
      { label: 'Settings', route: 'ecommerce/settings' },
    ],
  };

  const navItems2 = [
    { label: 'Domains', route: 'domains', icon: 'globe' },
    { label: 'Backups', route: 'backups', icon: 'archive' },
    { label: 'Security', route: 'security', icon: 'shield' },
  ];

  const crmDropdown: NavDropdown = {
    key: 'crm',
    label: 'CRM',
    icon: 'users',
    children: [
      { label: 'Dashboard', route: 'crm/dashboard' },
      { label: 'Campaigns', route: 'crm/campaigns' },
      { label: 'Leads', route: 'crm/leads' },
      { label: 'Subscribers', route: 'crm/subscribers' },
      { label: 'Bookings', route: 'crm/bookings' },
      { label: 'Abandoned Carts', route: 'crm/abandoned-carts' },
      { label: 'AI Chatbots', route: 'crm/chatbot' },
    ],
  };

  const analyticsItem = { label: 'Analytics', route: 'analytics', icon: 'chart' };

  const brandingDropdown: NavDropdown = {
    key: 'branding',
    label: 'Branding',
    icon: 'palette',
    children: [
      { label: 'Overview', route: 'branding/overview' },
      { label: 'Logo & assets', route: 'branding/logo-assets' },
      { label: 'Business card', route: 'branding/business-card' },
      { label: 'Social media', route: 'branding/social-media' },
      { label: 'Social Publishing', route: 'branding/social-publishing' },
      { label: 'Ad Campaigns', route: 'branding/ad-campaigns' },
      { label: 'Link in Bio', route: 'branding/link-in-bio' },
      { label: 'Email signature', route: 'branding/email-signature' },
      { label: 'Invoices', route: 'branding/invoices' },
    ],
  };

  const pluginsDropdown: NavDropdown = {
    key: 'plugins-themes',
    label: 'Plugins & themes',
    icon: 'puzzle',
    children: [
      { label: 'WordPress plugins', route: 'plugins' },
      { label: 'WordPress themes', route: 'themes' },
    ],
  };

  const boosterDropdown: NavDropdown = {
    key: 'booster',
    label: 'Website Booster',
    icon: 'bolt',
    children: [
      { label: 'Main', route: 'booster/main' },
      { label: 'Pages', route: 'booster/pages' },
      { label: 'Image Optimizer', route: 'booster/image-optimizer' },
      { label: 'Cloudflare CDN', route: 'booster/cloudflare' },
      { label: 'Settings', route: 'booster/settings' },
    ],
  };

  const seoDropdown: NavDropdown = {
    key: 'seo',
    label: 'SEO Tools',
    icon: 'search',
    children: [
      { label: 'Dashboard', route: 'seo/dashboard' },
      { label: 'Site Audit', route: 'seo/audit' },
      { label: 'AI Suggestions', route: 'seo/suggestions' },
      { label: 'Pages', route: 'seo/pages' },
      { label: 'History', route: 'seo/history' },
    ],
  };

  const allDropdowns = hasWooCommerce ? [ecommerceDropdown] : [];

  function renderIcon(icon: string) {
    switch (icon) {
      case 'home':
        return (
          <svg style={styles.ico} viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" d="M3 12l2-2 7-7 7 7 2 2M5 10v10a1 1 0 001 1h3m10-11v10a1 1 0 01-1 1h-3m-6 0v-4a1 1 0 011-1h2a1 1 0 011 1v4"/>
          </svg>
        );
      case 'bulb':
        return (
          <svg style={styles.ico} viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" d="M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
            <path strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" d="M9.663 17h4.673"/>
          </svg>
        );
      case 'globe':
        return (
          <svg style={styles.ico} viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"/>
          </svg>
        );
      case 'archive':
        return (
          <svg style={styles.ico} viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
          </svg>
        );
      case 'chart':
        return (
          <svg style={styles.ico} viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
          </svg>
        );
      case 'bag':
        return (
          <svg style={styles.ico} viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
          </svg>
        );
      case 'users':
        return (
          <svg style={styles.ico} viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
          </svg>
        );
      case 'palette':
        return (
          <svg style={styles.ico} viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0a4 4 0 004-4v-4a2 2 0 012-2h4a2 2 0 012 2v4a4 4 0 01-4 4h-8z"/>
          </svg>
        );
      case 'puzzle':
        return (
          <svg style={styles.ico} viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/>
          </svg>
        );
      case 'bolt':
        return (
          <svg style={styles.ico} viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
          </svg>
        );
      case 'search':
        return (
          <svg style={styles.ico} viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
        );
      case 'shield':
        return (
          <svg style={styles.ico} viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
          </svg>
        );
      default:
        return null;
    }
  }

  function renderDropdown(dd: NavDropdown) {
    const isOpen = !!openDropdowns[dd.key];
    return (
      <div key={dd.key} style={{ marginTop: 4 }}>
        <button
          type="button"
          onClick={() => toggleDropdown(dd.key)}
          style={styles.ddBtn}
          className="ws-dd-btn"
        >
          <span style={styles.ddLeft}>
            {renderIcon(dd.icon)}
            <span>{dd.label}</span>
          </span>
          <svg
            style={{ ...styles.caret, ...(isOpen ? { transform: 'rotate(90deg)' } : {}) }}
            viewBox="0 0 24 24" fill="none" stroke="currentColor"
          >
            <path strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" d="M9 5l7 7-7 7"/>
          </svg>
        </button>

        {isOpen && (
          <div style={styles.ddMenu}>
            {dd.children.map((child) => (
              <NavLink
                key={child.route}
                to={`${baseRoute}/${child.route}`}
                onClick={onNavClick}
                style={({ isActive }) => ({
                  ...styles.sub,
                  ...(isActive ? styles.subActive : {}),
                })}
                className="ws-sub"
              >
                {child.label}
              </NavLink>
            ))}
          </div>
        )}
      </div>
    );
  }

  return (
    <>
      <aside
        style={{
          ...styles.sidebar,
          ...(mobileOpen ? {} : {}),
        }}
        className={`ws-sidebar ${mobileOpen ? 'ws-mobile-open' : ''}`}
      >
        {/* Mobile close button */}
        <button style={styles.mobileClose} className="ws-mobile-close" onClick={onCloseMobile}>
          <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>

        {/* Top section */}
        <div style={styles.sTop}>
          {/* Live environment */}
          <div style={styles.env}>
            <span style={styles.dot} />
            <div style={styles.envText}>
              <div style={styles.envTitle}>Live environment</div>
              <div style={styles.envSub}>{website.slug}.localhost</div>
            </div>
          </div>

          {/* Website card */}
          <div style={styles.sitecard}>
            <div style={styles.thumb} ref={thumbRef}>
              {website.url && website.status === 'active' ? (
                <div style={styles.iframeWrap}>
                  <iframe
                    src={website.url}
                    title={website.name}
                    style={{ ...styles.iframe, transform: `scale(${iframeScale})` }}
                    loading="lazy"
                    sandbox="allow-same-origin"
                    tabIndex={-1}
                  />
                </div>
              ) : (
                <div style={styles.thumbEmpty}>
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" strokeWidth="1.2" strokeLinecap="round" strokeLinejoin="round">
                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                    <line x1="8" y1="21" x2="16" y2="21"/>
                    <line x1="12" y1="17" x2="12" y2="21"/>
                  </svg>
                </div>
              )}
              {/* Score badge */}
              <div className="ws-score-badge">
                <svg viewBox="0 0 36 36" style={styles.scoreSvg}>
                  <circle cx="18" cy="18" r="15" fill="none" stroke="#e6e9ed" strokeWidth="2.5"/>
                  <circle cx="18" cy="18" r="15" fill="none" stroke="#22c55e" strokeWidth="2.5"
                    strokeDasharray="94.25" strokeDashoffset={website.status === 'active' ? '0' : '94.25'}
                    transform="rotate(-90 18 18)" strokeLinecap="round"/>
                </svg>
                <span style={styles.scoreText}>{website.status === 'active' ? 100 : '--'}</span>
              </div>
              {/* Action pills */}
              {website.url && (
                <div style={styles.thumbActions}>
                  <a
                    href={website.auto_login_url || website.wp_admin_url || website.url + '/wp-admin/'}
                    target="_blank"
                    rel="noreferrer"
                    style={styles.pill}
                    className="ws-pill"
                    title="WordPress Admin"
                  >
                    <span style={styles.wp}>W</span>
                  </a>
                  <a
                    href={website.elementor_url || website.url + '/wp-admin/'}
                    target="_blank"
                    rel="noreferrer"
                    style={styles.pill}
                    className="ws-pill"
                    title="Edit with Elementor"
                  >
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                      <path strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" d="M15.232 5.232l3.536 3.536M4 20h4.586a1 1 0 00.707-.293l10.9-10.9a2 2 0 000-2.828l-2.172-2.172a2 2 0 00-2.828 0l-10.9 10.9A1 1 0 004 15.414V20z"/>
                    </svg>
                  </a>
                </div>
              )}
            </div>
            <div style={styles.domain}>{website.slug}.localhost</div>
          </div>
        </div>

        {/* Navigation */}
        <nav style={styles.nav}>
          {/* Main & AI Builder */}
          {navItems.map((item) => (
            <NavLink
              key={item.route}
              to={`${baseRoute}/${item.route}`}
              end={item.route === 'manage'}
              onClick={onNavClick}
              style={({ isActive }) => ({
                ...styles.item,
                ...(isActive ? styles.itemActive : {}),
              })}
              className="ws-item"
            >
              {renderIcon(item.icon)}
              <span>{item.label}</span>
            </NavLink>
          ))}

          {/* Ecommerce Dropdown */}
          {allDropdowns.map((dd) => renderDropdown(dd))}

          {/* Domains & Backups */}
          {navItems2.map((item) => (
            <NavLink
              key={item.route}
              to={`${baseRoute}/${item.route}`}
              onClick={onNavClick}
              style={({ isActive }) => ({
                ...styles.item,
                ...(isActive ? styles.itemActive : {}),
              })}
              className="ws-item"
            >
              {renderIcon(item.icon)}
              <span>{item.label}</span>
            </NavLink>
          ))}

          {/* CRM Dropdown */}
          {renderDropdown(crmDropdown)}

          {/* Analytics */}
          <NavLink
            to={`${baseRoute}/${analyticsItem.route}`}
            onClick={onNavClick}
            style={({ isActive }) => ({
              ...styles.item,
              ...(isActive ? styles.itemActive : {}),
            })}
            className="ws-item"
          >
            {renderIcon(analyticsItem.icon)}
            <span>{analyticsItem.label}</span>
          </NavLink>

          {/* Branding Dropdown */}
          {renderDropdown(brandingDropdown)}

          {/* Plugins & themes Dropdown */}
          {renderDropdown(pluginsDropdown)}

          {/* Website Booster Dropdown */}
          {renderDropdown(boosterDropdown)}

          {/* SEO Tools Dropdown */}
          {renderDropdown(seoDropdown)}
        </nav>
      </aside>

      <style>{cssStyles}</style>
    </>
  );
}

const cssStyles = `
  .ws-sidebar {
    width: 265px;
    background: #fff;
    border-right: 1px solid #E5E7EB;
    display: flex;
    flex-direction: column;
    flex-shrink: 0;
    overflow-y: auto;
  }
  .ws-mobile-close {
    display: none;
  }
  .ws-item:hover {
    background: #F6F7F9 !important;
  }
  .ws-dd-btn:hover {
    background: #F6F7F9 !important;
  }
  .ws-sub:hover {
    color: #111827 !important;
    background: #F6F7F9 !important;
  }
  .ws-pill:hover {
    background: #f8fafc !important;
    box-shadow: 0 2px 8px rgba(0,0,0,0.12) !important;
  }
  .ws-score-badge {
    position: absolute;
    top: 6px;
    left: 6px;
    width: 34px;
    height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff;
    border-radius: 50%;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    z-index: 2;
  }
  @media (max-width: 768px) {
    .ws-sidebar {
      position: fixed !important;
      left: -320px;
      top: 0;
      bottom: 0;
      width: 300px !important;
      max-width: 85vw;
      z-index: 9998;
      transition: left 0.3s ease;
      box-shadow: 4px 0 20px rgba(0,0,0,0.2);
      overflow-y: auto;
      background: white;
    }
    .ws-sidebar.ws-mobile-open {
      left: 0 !important;
    }
    .ws-mobile-close {
      display: flex !important;
      position: absolute;
      top: 12px;
      right: 12px;
      width: 32px;
      height: 32px;
      background: #F3F4F6;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      align-items: center;
      justify-content: center;
      z-index: 1000;
    }
    .ws-mobile-close:hover {
      background: #E5E7EB;
    }
  }
`;

const styles: Record<string, React.CSSProperties> = {
  sidebar: {},
  mobileClose: {
    display: 'none',
    position: 'absolute',
    top: 12,
    right: 12,
    width: 32,
    height: 32,
    background: '#F3F4F6',
    border: 'none',
    borderRadius: 8,
    cursor: 'pointer',
    alignItems: 'center',
    justifyContent: 'center',
    zIndex: 1000,
  },
  sTop: {
    padding: '14px 14px 10px',
    borderBottom: '1px solid #EEF2F7',
  },
  env: {
    display: 'flex',
    alignItems: 'center',
    gap: 10,
    border: '1px solid #E5E7EB',
    borderRadius: 12,
    padding: '10px 12px',
    background: '#fff',
  },
  dot: {
    width: 8,
    height: 8,
    borderRadius: '50%',
    background: '#22C55E',
    flexShrink: 0,
  },
  envText: {
    flex: 1,
    minWidth: 0,
  },
  envTitle: {
    fontSize: 12,
    fontWeight: 600,
    color: '#111827',
    lineHeight: 1.1,
  },
  envSub: {
    fontSize: 12,
    color: '#6B7280',
    whiteSpace: 'nowrap',
    overflow: 'hidden',
    textOverflow: 'ellipsis',
    marginTop: 2,
  },
  sitecard: {
    marginTop: 12,
    border: '1px solid #E5E7EB',
    borderRadius: 14,
    overflow: 'hidden',
    background: '#fff',
  },
  thumb: {
    position: 'relative',
    paddingTop: '50%',
    overflow: 'hidden',
    background: 'linear-gradient(135deg, #f8f9fd 0%, #eef1f8 50%, #e8ecf4 100%)',
  },
  iframeWrap: {
    position: 'absolute' as const,
    inset: 0,
    overflow: 'hidden',
    pointerEvents: 'none' as const,
  },
  iframe: {
    position: 'absolute' as const,
    top: 0,
    left: 0,
    width: 1440,
    height: 900,
    border: 'none',
    pointerEvents: 'none' as const,
    transformOrigin: 'top left',
  },
  thumbEmpty: {
    position: 'absolute' as const,
    inset: 0,
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
  },
  scoreSvg: {
    position: 'absolute' as const,
    inset: 0,
    width: '100%',
    height: '100%',
  },
  scoreText: {
    fontSize: 10,
    fontWeight: 800,
    color: '#22c55e',
    zIndex: 1,
    position: 'relative' as const,
  },
  thumbActions: {
    position: 'absolute' as const,
    top: 6,
    right: 6,
    display: 'flex',
    gap: 4,
  },
  pill: {
    width: 28,
    height: 28,
    borderRadius: 7,
    background: '#fff',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    boxShadow: '0 1px 4px rgba(0,0,0,0.08)',
    cursor: 'pointer',
    textDecoration: 'none',
    color: '#475569',
  },
  wp: {
    fontWeight: 800,
    fontSize: 11,
    color: '#21759b',
    fontFamily: 'Georgia, serif',
  },
  domain: {
    padding: '8px 10px',
    fontSize: 12,
    color: '#111827',
    whiteSpace: 'nowrap',
    overflow: 'hidden',
    textOverflow: 'ellipsis',
  },
  nav: {
    padding: '10px 8px 14px',
    overflow: 'auto',
    flex: 1,
  },
  item: {
    display: 'flex',
    alignItems: 'center',
    gap: 10,
    padding: '10px 12px',
    borderRadius: 12,
    textDecoration: 'none',
    color: '#111827',
    fontSize: 13,
    fontWeight: 500,
  },
  itemActive: {
    background: '#F3F4F6',
    fontWeight: 600,
  },
  ico: {
    width: 18,
    height: 18,
    color: '#111827',
    opacity: 0.9,
    flexShrink: 0,
  },
  ddBtn: {
    width: '100%',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'space-between',
    padding: '10px 12px',
    borderRadius: 12,
    background: 'transparent',
    border: 0,
    color: '#111827',
    fontSize: 13,
    fontWeight: 500,
    cursor: 'pointer',
  },
  ddLeft: {
    display: 'flex',
    alignItems: 'center',
    gap: 10,
  },
  caret: {
    width: 16,
    height: 16,
    color: '#9CA3AF',
    transition: 'transform 0.18s ease',
  },
  ddMenu: {
    padding: '6px 0 6px 34px',
  },
  sub: {
    display: 'block',
    padding: '8px 10px',
    borderRadius: 10,
    textDecoration: 'none',
    fontSize: 12,
    color: '#6B7280',
  },
  subActive: {
    background: '#F3F4F6',
    color: '#111827',
    fontWeight: 600,
  },
};
