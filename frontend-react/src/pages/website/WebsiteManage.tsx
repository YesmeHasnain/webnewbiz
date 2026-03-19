import { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import { websiteService } from '../../services/website.service';
import { wpManagerService } from '../../services/wp-manager.service';
import { builderPluginService } from '../../services/builder-plugin.service';
import type { Website } from '../../models/types';

export default function WebsiteManage() {
  const { id } = useParams<{ id: string }>();
  const [website, setWebsite] = useState<Website | null>(null);
  const [overview, setOverview] = useState<any>(null);
  const [dashboard, setDashboard] = useState<any>(null);
  const [loading, setLoading] = useState(true);

  const baseRoute = `/websites/${id}`;

  useEffect(() => {
    const wid = Number(id);
    if (!wid) return;

    setLoading(true);
    Promise.all([
      websiteService.get(wid).then(r => setWebsite(r.data)).catch(() => {}),
      wpManagerService.getOverview(wid).then(r => setOverview(r.data?.data || r.data)).catch(() => {}),
      builderPluginService.getDashboard(wid).then(r => setDashboard(r.data)).catch(() => {}),
    ]).finally(() => setLoading(false));
  }, [id]);

  const healthScore = dashboard?.security_score ?? 0;
  const healthLabel = healthScore >= 80 ? 'Good' : healthScore >= 50 ? 'Fair' : 'Critical';
  const healthColor = healthScore >= 80 ? '#22C55E' : healthScore >= 50 ? '#F59E0B' : '#EF4444';

  const sinceDate = website?.created_at
    ? new Date(website.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
    : '';

  const stats = [
    {
      value: overview?.wp_version || '—',
      label: 'WordPress',
      bg: '#EBF5FF',
      color: '#2563EB',
      icon: (
        <svg width="22" height="22" viewBox="0 0 24 24" fill="#2563EB">
          <path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm-1.5 15.08L6.76 8.57c.6-.03 1.14-.09 1.14-.09.54-.06.47-.85-.06-.82 0 0-1.62.13-2.66.13-.19 0-.41 0-.65-.01A7.96 7.96 0 0112 4c2.18 0 4.17.87 5.62 2.29-.04 0-.07-.01-.11-.01-1.01 0-1.73.88-1.73 1.83 0 .85.49 1.57 1.01 2.42.39.68.85 1.55.85 2.8 0 .87-.33 1.88-.77 3.29l-1.01 3.37-3.36-10c.6-.03 1.14-.09 1.14-.09.53-.06.47-.85-.07-.82 0 0-1.62.13-2.66.13-.19 0-.38 0-.58-.01zm2.16 1.37l2.83-8.2c.53-1.32.7-2.37.7-3.31 0-.34-.02-.66-.07-.95A7.97 7.97 0 0120 12c0 3.04-1.7 5.68-4.2 7.02l-3.14-8.57zM4 12c0-1.2.26-2.33.74-3.35l4.07 11.15A8.01 8.01 0 014 12z"/>
        </svg>
      ),
    },
    {
      value: overview?.active_plugins ?? '—',
      label: 'Active Plugins',
      bg: '#F3E8FF',
      color: '#7C3AED',
      icon: (
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#7C3AED" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
          <path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/>
        </svg>
      ),
    },
    {
      value: overview?.total_pages ?? '—',
      label: 'Pages',
      bg: '#ECFDF5',
      color: '#059669',
      icon: (
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#059669" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
          <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
          <polyline points="14 2 14 8 20 8"/>
          <line x1="16" y1="13" x2="8" y2="13"/>
          <line x1="16" y1="17" x2="8" y2="17"/>
          <polyline points="10 9 9 9 8 9"/>
        </svg>
      ),
    },
    {
      value: overview?.total_posts ?? '—',
      label: 'Posts',
      bg: '#FFF7ED',
      color: '#EA580C',
      icon: (
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#EA580C" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
          <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
          <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
        </svg>
      ),
    },
    {
      value: overview?.disk_usage_mb ? `${overview.disk_usage_mb} MB` : '—',
      label: 'Uploads',
      bg: '#E0F7FA',
      color: '#0891B2',
      icon: (
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0891B2" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
          <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
          <polyline points="17 8 12 3 7 8"/>
          <line x1="12" y1="3" x2="12" y2="15"/>
        </svg>
      ),
    },
    {
      value: overview?.db_size_mb ? `${overview.db_size_mb} MB` : '—',
      label: 'Database',
      bg: '#EEF2FF',
      color: '#4F46E5',
      icon: (
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
          <ellipse cx="12" cy="5" rx="9" ry="3"/>
          <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/>
          <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/>
        </svg>
      ),
    },
  ];

  const quickActions = [
    {
      title: 'Website Booster',
      desc: 'Optimize speed & performance',
      route: `${baseRoute}/booster/main`,
      bg: '#E0F7FA',
      icon: (
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0891B2" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
          <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
      ),
    },
    {
      title: 'Cache Manager',
      desc: 'Purge & manage caches',
      route: `${baseRoute}/booster/main`,
      bg: '#F3E8FF',
      icon: (
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#7C3AED" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
          <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
          <line x1="8" y1="21" x2="16" y2="21"/>
          <line x1="12" y1="17" x2="12" y2="21"/>
        </svg>
      ),
    },
    {
      title: 'Security',
      desc: 'Harden your site security',
      route: `${baseRoute}/security`,
      bg: '#EEF2FF',
      icon: (
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
          <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
        </svg>
      ),
    },
    {
      title: 'Image Optimizer',
      desc: 'Compress & optimize images',
      route: `${baseRoute}/booster/image-optimizer`,
      bg: '#EBF5FF',
      icon: (
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563EB" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
          <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
          <circle cx="8.5" cy="8.5" r="1.5"/>
          <polyline points="21 15 16 10 5 21"/>
        </svg>
      ),
    },
    {
      title: 'Backups',
      desc: 'Manage your site backups',
      route: `${baseRoute}/backups`,
      bg: '#ECFDF5',
      icon: (
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#059669" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
          <path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/>
          <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
          <line x1="12" y1="22.08" x2="12" y2="12"/>
        </svg>
      ),
    },
    {
      title: 'SEO Tools',
      desc: 'Optimize search rankings',
      route: `${baseRoute}/seo/dashboard`,
      bg: '#FFF7ED',
      icon: (
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#EA580C" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
          <circle cx="11" cy="11" r="8"/>
          <line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
      ),
    },
    {
      title: 'Analytics',
      desc: 'View traffic & visitor insights',
      route: `${baseRoute}/analytics`,
      bg: '#F3E8FF',
      icon: (
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#7C3AED" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
          <line x1="18" y1="20" x2="18" y2="10"/>
          <line x1="12" y1="20" x2="12" y2="4"/>
          <line x1="6" y1="20" x2="6" y2="14"/>
        </svg>
      ),
    },
    {
      title: 'Database',
      desc: 'Cleanup & optimize database',
      route: `${baseRoute}/booster/main`,
      bg: '#EEF2FF',
      icon: (
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
          <ellipse cx="12" cy="5" rx="9" ry="3"/>
          <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/>
          <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/>
        </svg>
      ),
    },
  ];

  // Donut chart math
  const radius = 54;
  const circumference = 2 * Math.PI * radius;
  const healthOffset = circumference * (1 - healthScore / 100);

  if (loading) {
    return (
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', minHeight: 300, color: '#6B7280', fontSize: 14 }}>
        Loading dashboard...
      </div>
    );
  }

  return (
    <div>
      {/* Header */}
      <div style={s.header} className="wm-header">
        <div style={s.headerLeft}>
          <div style={s.headerIcon}>
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#7c5cfc" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
              <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
          </div>
          <div>
            <div style={s.headerTitle}>
              WebNewBiz Builder
              <span style={s.premiumBadge}>Premium</span>
              <span style={s.version}>v1.0.0</span>
            </div>
          </div>
        </div>
        <div style={s.headerRight}>
          {website?.url && (
            <a
              href={website.auto_login_url || website.url + '/wp-admin/'}
              target="_blank"
              rel="noreferrer"
              style={s.btnPrimary}
              className="wm-btn-primary"
            >
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                <path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/>
                <polyline points="15 3 21 3 21 9"/>
                <line x1="10" y1="14" x2="21" y2="3"/>
              </svg>
              Go to WP Admin
            </a>
          )}
          {website?.url && (
            <a
              href={website.url}
              target="_blank"
              rel="noreferrer"
              style={s.btnOutline}
              className="wm-btn-outline"
            >
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                <path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/>
                <polyline points="15 3 21 3 21 9"/>
                <line x1="10" y1="14" x2="21" y2="3"/>
              </svg>
              Visit Site
            </a>
          )}
        </div>
      </div>

      {/* Connection Status */}
      <div style={s.connectionBar}>
        <div style={s.connectionLeft}>
          <span style={s.greenDot} />
          <span style={s.connectionText}>Connected to WebNewBiz Platform</span>
        </div>
        {sinceDate && <span style={s.connectionDate}>Since {sinceDate}</span>}
      </div>

      {/* Stats Row */}
      <div style={s.statsGrid} className="wm-stats-grid">
        {stats.map((st, i) => (
          <div key={i} style={s.statCard}>
            <div style={{ ...s.statIcon, background: st.bg }}>
              {st.icon}
            </div>
            <div>
              <div style={s.statValue}>{st.value}</div>
              <div style={s.statLabel}>{st.label}</div>
            </div>
          </div>
        ))}
      </div>

      {/* Bottom Grid: Health + Quick Actions */}
      <div style={s.bottomGrid} className="wm-bottom-grid">
        {/* Site Health Score */}
        <div style={s.card}>
          <div style={s.cardHeaderRow}>
            <div style={s.cardTitleRow}>
              <span style={{ color: healthColor, fontSize: 18 }}>&#9829;</span>
              <h3 style={s.cardTitle}>Site Health Score</h3>
            </div>
          </div>
          <div style={s.cardDivider} />

          <div style={s.healthCenter}>
            <div style={s.donutWrap}>
              <svg width="140" height="140" viewBox="0 0 140 140">
                <circle cx="70" cy="70" r={radius} fill="none" stroke="#E5E7EB" strokeWidth="8" />
                <circle
                  cx="70" cy="70" r={radius} fill="none"
                  stroke={healthColor}
                  strokeWidth="8"
                  strokeDasharray={circumference}
                  strokeDashoffset={healthOffset}
                  strokeLinecap="round"
                  transform="rotate(-90 70 70)"
                  style={{ transition: 'stroke-dashoffset 0.6s ease' }}
                />
              </svg>
              <div style={s.donutText}>
                <span style={s.donutNum}>{healthScore}</span>
                <span style={s.donutSub}>/ 100</span>
              </div>
            </div>
            <div style={{ ...s.healthLabel, color: healthColor }}>{healthLabel}</div>
          </div>

          {/* Performance toggles quick summary */}
          <div style={s.healthMeta}>
            <div style={s.healthRow}>
              <span style={s.healthDot(dashboard?.performance?.lazy_load_images)} />
              <span style={s.healthMetaText}>Lazy Load Images</span>
            </div>
            <div style={s.healthRow}>
              <span style={s.healthDot(dashboard?.performance?.disable_emojis)} />
              <span style={s.healthMetaText}>Emojis Disabled</span>
            </div>
            <div style={s.healthRow}>
              <span style={s.healthDot(dashboard?.performance?.dns_prefetch)} />
              <span style={s.healthMetaText}>DNS Prefetch</span>
            </div>
            <div style={s.healthRow}>
              <span style={s.healthDot(dashboard?.performance?.minify_html)} />
              <span style={s.healthMetaText}>HTML Minification</span>
            </div>
          </div>

          <Link to={`${baseRoute}/security`} style={s.cardLink} className="wm-card-link">
            View full security report &rarr;
          </Link>
        </div>

        {/* Quick Actions */}
        <div style={s.card}>
          <div style={s.cardHeaderRow}>
            <div style={s.cardTitleRow}>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6B7280" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                <circle cx="12" cy="12" r="3"/>
                <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06A1.65 1.65 0 0019.32 9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z"/>
              </svg>
              <h3 style={s.cardTitle}>Quick Actions</h3>
            </div>
          </div>
          <div style={s.cardDivider} />

          <div style={s.actionsList}>
            {quickActions.map((qa, i) => (
              <Link key={i} to={qa.route} style={s.actionItem} className="wm-action-item">
                <div style={{ ...s.actionIcon, background: qa.bg }}>
                  {qa.icon}
                </div>
                <div style={s.actionText}>
                  <div style={s.actionTitle}>{qa.title}</div>
                  <div style={s.actionDesc}>{qa.desc}</div>
                </div>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" style={{ flexShrink: 0 }}>
                  <polyline points="9 18 15 12 9 6"/>
                </svg>
              </Link>
            ))}
          </div>
        </div>
      </div>

      {/* Bottom Info Row */}
      <div style={s.infoGrid} className="wm-info-grid">
        {/* Visits Card */}
        <div style={s.card}>
          <div style={s.cardHeaderRow}>
            <div style={s.cardTitleRow}>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6B7280" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
              </svg>
              <h3 style={s.cardTitle}>Visitor Stats</h3>
            </div>
            <Link to={`${baseRoute}/analytics`} style={s.manageLink} className="wm-manage-link">View all &rarr;</Link>
          </div>
          <div style={s.cardDivider} />
          <div style={s.visitStats}>
            <div style={s.visitItem}>
              <div style={s.visitNum}>{dashboard?.views_today ?? 0}</div>
              <div style={s.visitLabel}>Today</div>
            </div>
            <div style={s.visitDivider} />
            <div style={s.visitItem}>
              <div style={s.visitNum}>{dashboard?.views_7days ?? 0}</div>
              <div style={s.visitLabel}>Last 7 Days</div>
            </div>
            <div style={s.visitDivider} />
            <div style={s.visitItem}>
              <div style={s.visitNum}>{dashboard?.views_30days ?? 0}</div>
              <div style={s.visitLabel}>Last 30 Days</div>
            </div>
            <div style={s.visitDivider} />
            <div style={s.visitItem}>
              <div style={s.visitNum}>{dashboard?.unique_30days ?? 0}</div>
              <div style={s.visitLabel}>Unique (30d)</div>
            </div>
          </div>
        </div>

        {/* Site Info Card */}
        <div style={s.card}>
          <div style={s.cardHeaderRow}>
            <div style={s.cardTitleRow}>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6B7280" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="16" x2="12" y2="12"/>
                <line x1="12" y1="8" x2="12.01" y2="8"/>
              </svg>
              <h3 style={s.cardTitle}>Site Information</h3>
            </div>
          </div>
          <div style={s.cardDivider} />
          <div style={s.infoList}>
            <div style={s.infoRow}>
              <span style={s.infoLabel}>Site Title</span>
              <span style={s.infoValue}>{overview?.site_title || website?.name || '—'}</span>
            </div>
            <div style={s.infoRow}>
              <span style={s.infoLabel}>PHP Version</span>
              <span style={s.infoValue}>{overview?.php_version || '—'}</span>
            </div>
            <div style={s.infoRow}>
              <span style={s.infoLabel}>Active Theme</span>
              <span style={s.infoValue}>{overview?.active_theme || '—'}</span>
            </div>
            <div style={s.infoRow}>
              <span style={s.infoLabel}>Server</span>
              <span style={s.infoValue}>{overview?.server_software ? overview.server_software.split(' ')[0] : '—'}</span>
            </div>
            <div style={s.infoRow}>
              <span style={s.infoLabel}>Admin Email</span>
              <span style={s.infoValue}>{overview?.admin_email || '—'}</span>
            </div>
            <div style={s.infoRow}>
              <span style={s.infoLabel}>WooCommerce</span>
              <span style={{ ...s.infoValue, color: overview?.woocommerce_active ? '#059669' : '#6B7280' }}>
                {overview?.woocommerce_active ? 'Active' : 'Inactive'}
              </span>
            </div>
          </div>
        </div>
      </div>

      <style>{css}</style>
    </div>
  );
}

const css = `
  .wm-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 16px;
  }
  .wm-stats-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 12px;
    margin-bottom: 16px;
  }
  .wm-bottom-grid {
    display: grid;
    grid-template-columns: 1fr 1.2fr;
    gap: 16px;
    margin-bottom: 16px;
  }
  .wm-info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
  }
  .wm-btn-primary:hover {
    background: #6a4ae8 !important;
  }
  .wm-btn-outline:hover {
    background: #F3F4F6 !important;
  }
  .wm-action-item:hover {
    background: #F9FAFB !important;
  }
  .wm-card-link:hover {
    color: #6a4ae8 !important;
  }
  .wm-manage-link:hover {
    color: #111827 !important;
  }
  @media (max-width: 1200px) {
    .wm-stats-grid {
      grid-template-columns: repeat(3, 1fr) !important;
    }
  }
  @media (max-width: 900px) {
    .wm-bottom-grid {
      grid-template-columns: 1fr !important;
    }
    .wm-info-grid {
      grid-template-columns: 1fr !important;
    }
  }
  @media (max-width: 640px) {
    .wm-stats-grid {
      grid-template-columns: repeat(2, 1fr) !important;
    }
    .wm-header {
      flex-direction: column !important;
      align-items: flex-start !important;
    }
  }
`;

const s: Record<string, any> = {
  /* Header */
  header: {
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'space-between',
    background: '#fff',
    borderRadius: 12,
    border: '1px solid #F3F4F6',
    padding: '18px 24px',
    boxShadow: '0 1px 2px rgba(0,0,0,0.03)',
    marginBottom: 16,
  },
  headerLeft: {
    display: 'flex',
    alignItems: 'center',
    gap: 14,
  },
  headerIcon: {
    width: 44,
    height: 44,
    borderRadius: 12,
    background: '#F5F3FF',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
  },
  headerTitle: {
    fontSize: 20,
    fontWeight: 700,
    color: '#111827',
    display: 'flex',
    alignItems: 'center',
    gap: 10,
    flexWrap: 'wrap' as const,
  },
  premiumBadge: {
    fontSize: 11,
    fontWeight: 700,
    color: '#fff',
    background: '#7c5cfc',
    padding: '3px 10px',
    borderRadius: 6,
    letterSpacing: 0.3,
  },
  version: {
    fontSize: 13,
    fontWeight: 400,
    color: '#9CA3AF',
  },
  headerRight: {
    display: 'flex',
    alignItems: 'center',
    gap: 8,
  },
  btnPrimary: {
    display: 'inline-flex',
    alignItems: 'center',
    gap: 6,
    padding: '9px 18px',
    borderRadius: 8,
    background: '#7c5cfc',
    color: '#fff',
    fontSize: 13,
    fontWeight: 600,
    textDecoration: 'none',
    border: 'none',
    cursor: 'pointer',
  },
  btnOutline: {
    display: 'inline-flex',
    alignItems: 'center',
    gap: 6,
    padding: '9px 18px',
    borderRadius: 8,
    background: '#fff',
    color: '#374151',
    fontSize: 13,
    fontWeight: 600,
    textDecoration: 'none',
    border: '1px solid #D1D5DB',
    cursor: 'pointer',
  },

  /* Connection Bar */
  connectionBar: {
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'space-between',
    background: '#fff',
    borderRadius: 12,
    border: '1px solid #F3F4F6',
    padding: '14px 24px',
    marginBottom: 16,
    boxShadow: '0 1px 2px rgba(0,0,0,0.03)',
  },
  connectionLeft: {
    display: 'flex',
    alignItems: 'center',
    gap: 10,
  },
  greenDot: {
    width: 10,
    height: 10,
    borderRadius: '50%',
    background: '#22C55E',
    flexShrink: 0,
  },
  connectionText: {
    fontSize: 14,
    fontWeight: 600,
    color: '#059669',
  },
  connectionDate: {
    fontSize: 13,
    color: '#9CA3AF',
    fontStyle: 'italic' as const,
  },

  /* Stats Grid */
  statsGrid: {},
  statCard: {
    display: 'flex',
    alignItems: 'center',
    gap: 12,
    background: '#fff',
    borderRadius: 12,
    border: '1px solid #F3F4F6',
    padding: '16px 14px',
    boxShadow: '0 1px 2px rgba(0,0,0,0.03)',
  },
  statIcon: {
    width: 44,
    height: 44,
    borderRadius: 12,
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    flexShrink: 0,
  },
  statValue: {
    fontSize: 20,
    fontWeight: 700,
    color: '#111827',
    lineHeight: 1.2,
  },
  statLabel: {
    fontSize: 12,
    color: '#6B7280',
    marginTop: 1,
  },

  /* Cards */
  card: {
    background: '#fff',
    borderRadius: 12,
    border: '1px solid #F3F4F6',
    padding: '20px 24px',
    boxShadow: '0 1px 2px rgba(0,0,0,0.03)',
  },
  cardHeaderRow: {
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'space-between',
  },
  cardTitleRow: {
    display: 'flex',
    alignItems: 'center',
    gap: 8,
  },
  cardTitle: {
    fontSize: 16,
    fontWeight: 600,
    color: '#111827',
    margin: 0,
  },
  cardDivider: {
    height: 1,
    background: '#F3F4F6',
    margin: '14px 0',
  },
  cardLink: {
    display: 'block',
    textAlign: 'center' as const,
    fontSize: 13,
    fontWeight: 500,
    color: '#7c5cfc',
    textDecoration: 'none',
    marginTop: 16,
    padding: '10px 0',
    borderTop: '1px solid #F3F4F6',
  },
  manageLink: {
    fontSize: 13,
    fontWeight: 500,
    color: '#6B7280',
    textDecoration: 'none',
  },

  /* Health Score */
  healthCenter: {
    display: 'flex',
    flexDirection: 'column' as const,
    alignItems: 'center',
    padding: '16px 0',
  },
  donutWrap: {
    position: 'relative' as const,
    width: 140,
    height: 140,
  },
  donutText: {
    position: 'absolute' as const,
    inset: 0,
    display: 'flex',
    flexDirection: 'column' as const,
    alignItems: 'center',
    justifyContent: 'center',
  },
  donutNum: {
    fontSize: 36,
    fontWeight: 700,
    color: '#111827',
    lineHeight: 1,
  },
  donutSub: {
    fontSize: 13,
    color: '#9CA3AF',
    marginTop: 2,
  },
  healthLabel: {
    fontSize: 15,
    fontWeight: 600,
    marginTop: 8,
  },
  healthMeta: {
    display: 'flex',
    flexDirection: 'column' as const,
    gap: 8,
    padding: '12px 0 0',
    borderTop: '1px solid #F3F4F6',
  },
  healthRow: {
    display: 'flex',
    alignItems: 'center',
    gap: 8,
  },
  healthDot: (active: boolean) => ({
    width: 8,
    height: 8,
    borderRadius: '50%',
    background: active ? '#22C55E' : '#D1D5DB',
    flexShrink: 0,
  }),
  healthMetaText: {
    fontSize: 13,
    color: '#374151',
  },

  /* Quick Actions */
  actionsList: {
    display: 'flex',
    flexDirection: 'column' as const,
  },
  actionItem: {
    display: 'flex',
    alignItems: 'center',
    gap: 14,
    padding: '12px 8px',
    borderBottom: '1px solid #F3F4F6',
    textDecoration: 'none',
    borderRadius: 8,
    transition: 'background 0.15s',
  },
  actionIcon: {
    width: 40,
    height: 40,
    borderRadius: 10,
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    flexShrink: 0,
  },
  actionText: {
    flex: 1,
    minWidth: 0,
  },
  actionTitle: {
    fontSize: 14,
    fontWeight: 600,
    color: '#111827',
  },
  actionDesc: {
    fontSize: 12,
    color: '#6B7280',
    marginTop: 1,
  },

  /* Visits */
  visitStats: {
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'space-around',
    padding: '16px 0',
  },
  visitItem: {
    textAlign: 'center' as const,
    flex: 1,
  },
  visitNum: {
    fontSize: 24,
    fontWeight: 700,
    color: '#111827',
  },
  visitLabel: {
    fontSize: 12,
    color: '#6B7280',
    marginTop: 2,
  },
  visitDivider: {
    width: 1,
    height: 36,
    background: '#E5E7EB',
    flexShrink: 0,
  },

  /* Info List */
  infoList: {
    display: 'flex',
    flexDirection: 'column' as const,
  },
  infoRow: {
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'space-between',
    padding: '10px 0',
    borderBottom: '1px solid #F3F4F6',
  },
  infoLabel: {
    fontSize: 13,
    color: '#6B7280',
  },
  infoValue: {
    fontSize: 13,
    fontWeight: 600,
    color: '#111827',
  },
};
