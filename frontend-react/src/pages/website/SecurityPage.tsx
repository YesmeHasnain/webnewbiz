import { useState, useEffect } from 'react';
import { useOutletContext } from 'react-router-dom';
import { builderPluginService } from '../../services/builder-plugin.service';

interface SecuritySettings {
  disable_xmlrpc: boolean;
  disable_file_editor: boolean;
  hide_wp_version: boolean;
  security_headers: boolean;
  limit_login_attempts: boolean;
  disable_user_enumeration: boolean;
  block_php_uploads: boolean;
  force_ssl_admin: boolean;
}

interface SecurityEvent {
  id: string;
  type: string;
  message: string;
  date: string;
}

const defaultSettings: SecuritySettings = {
  disable_xmlrpc: false,
  disable_file_editor: false,
  hide_wp_version: false,
  security_headers: false,
  limit_login_attempts: false,
  disable_user_enumeration: false,
  block_php_uploads: false,
  force_ssl_admin: false,
};

const settingsMeta: { key: keyof SecuritySettings; title: string; desc: string; icon: string }[] = [
  { key: 'disable_xmlrpc', title: 'Disable XML-RPC', desc: 'Block remote API calls that can be exploited', icon: 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636' },
  { key: 'disable_file_editor', title: 'Disable File Editor', desc: 'Prevent code editing in WordPress admin', icon: 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z' },
  { key: 'hide_wp_version', title: 'Hide WP Version', desc: 'Remove version number from page source', icon: 'M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878l4.242 4.242M15 12a3 3 0 01-3 3m3-3l5.121 5.121M12 15l-3.878 3.878' },
  { key: 'security_headers', title: 'Security Headers', desc: 'Add X-Frame-Options, X-Content-Type etc.', icon: 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z' },
  { key: 'limit_login_attempts', title: 'Limit Login Attempts', desc: 'Block after 5 consecutive failed attempts', icon: 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z' },
  { key: 'disable_user_enumeration', title: 'Disable User Enumeration', desc: 'Block ?author=N queries to hide usernames', icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z' },
  { key: 'block_php_uploads', title: 'Block PHP in Uploads', desc: 'Prevent PHP execution in uploads directory', icon: 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4' },
  { key: 'force_ssl_admin', title: 'Force SSL Admin', desc: 'Require HTTPS for all admin pages', icon: 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z' },
];

export default function SecurityPage() {
  const { website } = useOutletContext<{ website: any }>();
  const websiteId = website.id;

  const [settings, setSettings] = useState<SecuritySettings>(defaultSettings);
  const [events, setEvents] = useState<SecurityEvent[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [toast, setToast] = useState('');

  const showToast = (msg: string) => {
    setToast(msg);
    setTimeout(() => setToast(''), 2500);
  };

  useEffect(() => {
    let cancelled = false;
    setLoading(true);
    builderPluginService.getSecurity(websiteId)
      .then((res) => {
        if (cancelled) return;
        if (res.data?.settings) setSettings({ ...defaultSettings, ...res.data.settings });
        if (res.data?.events) setEvents(res.data.events);
      })
      .catch(() => {
        if (!cancelled) setError('Failed to load security settings');
      })
      .finally(() => { if (!cancelled) setLoading(false); });
    return () => { cancelled = true; };
  }, [websiteId]);

  const toggleSetting = async (key: keyof SecuritySettings) => {
    const updated = { ...settings, [key]: !settings[key] };
    setSettings(updated);
    try {
      await builderPluginService.saveSecurity(websiteId, updated as any);
      showToast(`${settingsMeta.find((m) => m.key === key)?.title} ${updated[key] ? 'enabled' : 'disabled'}`);
    } catch {
      setSettings(settings);
      setError('Failed to save setting');
    }
  };

  const score = Math.round((Object.values(settings).filter(Boolean).length / 8) * 100);
  const scoreColor = score >= 70 ? '#10B981' : score >= 40 ? '#F59E0B' : '#EF4444';
  const circumference = 2 * Math.PI * 54;
  const offset = circumference * (1 - score / 100);

  if (loading) {
    return (
      <div style={s.loadingWrap}>
        <div style={s.spinner} />
        <p style={{ color: '#6B7280', fontSize: 13 }}>Loading security settings...</p>
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
          <h1 style={s.h1}>Security</h1>
          <p style={s.subtitle}>Harden your WordPress site against threats</p>
        </div>
      </div>

      {/* Score Section */}
      <div style={s.card}>
        <div style={s.scoreRow} className="se-score-row">
          <div style={s.scoreCircleWrap}>
            <svg width="130" height="130">
              <circle cx="65" cy="65" r="54" fill="none" stroke="#E5E7EB" strokeWidth="8" />
              <circle
                cx="65" cy="65" r="54" fill="none"
                stroke={scoreColor} strokeWidth="8"
                strokeDasharray={circumference}
                strokeDashoffset={offset}
                strokeLinecap="round"
                transform="rotate(-90 65 65)"
                style={{ transition: 'stroke-dashoffset 0.5s' }}
              />
            </svg>
            <div style={s.scoreInner}>
              <div style={{ ...s.scoreNum, color: scoreColor }}>{score}</div>
              <div style={s.scoreOf}>/ 100</div>
            </div>
          </div>
          <div style={s.scoreInfo}>
            <h3 style={s.scoreTitle}>Security Score</h3>
            <p style={s.scoreDesc}>
              {score >= 70
                ? 'Your site has strong security protections enabled.'
                : score >= 40
                  ? 'Your site has moderate protection. Enable more features for better security.'
                  : 'Your site needs attention. Enable security features below.'}
            </p>
            <div style={s.scoreBar}>
              <div style={{ ...s.scoreBarFill, width: `${score}%`, background: scoreColor }} />
            </div>
            <span style={{ fontSize: 12, color: '#6B7280' }}>
              {Object.values(settings).filter(Boolean).length} of 8 protections active
            </span>
          </div>
        </div>
      </div>

      {/* Security Toggles */}
      <div style={s.card}>
        <h2 style={s.cardTitle}>Security Features</h2>
        <div style={s.settingsGrid} className="se-settings-grid">
          {settingsMeta.map((meta) => (
            <div key={meta.key} style={s.settingCard} className="se-setting-card">
              <div style={s.settingTop}>
                <div style={s.settingIcon}>
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#7c5cfc" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                    <path d={meta.icon} />
                  </svg>
                </div>
                <button
                  style={{ ...s.toggle, ...(settings[meta.key] ? s.toggleOn : {}) }}
                  className="se-toggle"
                  onClick={() => toggleSetting(meta.key)}
                  role="switch"
                  aria-checked={settings[meta.key]}
                >
                  <span style={{ ...s.toggleKnob, ...(settings[meta.key] ? s.toggleKnobOn : {}) }} />
                </button>
              </div>
              <div style={s.settingTitle}>{meta.title}</div>
              <div style={s.settingDesc}>{meta.desc}</div>
            </div>
          ))}
        </div>
      </div>

      {/* Activity Log */}
      <div style={s.card}>
        <h2 style={s.cardTitle}>Activity Log</h2>
        {events.length === 0 ? (
          <div style={s.emptyLog}>
            <svg width="36" height="36" fill="none" stroke="#D1D5DB" strokeWidth="1.2" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            <p style={{ fontSize: 13, color: '#6B7280', margin: '10px 0 0' }}>No security events recorded</p>
          </div>
        ) : (
          <div style={s.eventList}>
            {events.map((ev, i) => (
              <div key={ev.id || i} style={s.eventRow}>
                <div style={{
                  ...s.eventDot,
                  background: ev.type === 'blocked' ? '#EF4444'
                    : ev.type === 'warning' ? '#F59E0B'
                      : '#10B981',
                }} />
                <div style={s.eventContent}>
                  <div style={s.eventMsg}>{ev.message}</div>
                  <div style={s.eventDate}>
                    {new Date(ev.date).toLocaleDateString('en', {
                      month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit',
                    })}
                  </div>
                </div>
              </div>
            ))}
          </div>
        )}
      </div>

      <style>{cssStr}</style>
    </div>
  );
}

const spinCss = `@keyframes se-spin { to { transform: rotate(360deg); } }`;

const cssStr = `
  ${spinCss}
  .se-score-row {
    display: flex;
    align-items: center;
    gap: 32px;
  }
  .se-settings-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
    margin-top: 16px;
  }
  .se-setting-card:hover { border-color: #D1D5DB; }
  .se-toggle:hover { opacity: 0.85; }
  @media (max-width: 700px) {
    .se-score-row { flex-direction: column !important; text-align: center; }
    .se-settings-grid { grid-template-columns: 1fr !important; }
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
    borderRadius: '50%', animation: 'se-spin 0.6s linear infinite',
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
  card: {
    background: '#fff', borderRadius: 12, border: '1px solid #e5e7eb',
    padding: '24px', marginBottom: 20,
  },
  cardTitle: { fontSize: 16, fontWeight: 600, color: '#111827', margin: '0 0 4px' },
  scoreRow: {},
  scoreCircleWrap: {
    position: 'relative', width: 130, height: 130, flexShrink: 0,
  },
  scoreInner: {
    position: 'absolute', inset: 0, display: 'flex', flexDirection: 'column',
    alignItems: 'center', justifyContent: 'center',
  },
  scoreNum: { fontSize: 36, fontWeight: 800, lineHeight: 1 },
  scoreOf: { fontSize: 12, color: '#9CA3AF', marginTop: 2 },
  scoreInfo: { flex: 1 },
  scoreTitle: { fontSize: 18, fontWeight: 700, color: '#111827', margin: '0 0 6px' },
  scoreDesc: { fontSize: 13, color: '#6B7280', lineHeight: 1.5, margin: '0 0 12px' },
  scoreBar: {
    height: 6, borderRadius: 3, background: '#F3F4F6', overflow: 'hidden',
    marginBottom: 8,
  },
  scoreBarFill: {
    height: '100%', borderRadius: 3, transition: 'width 0.5s',
  },
  settingsGrid: {},
  settingCard: {
    border: '1px solid #E5E7EB', borderRadius: 12, padding: 16,
    transition: 'border-color 0.15s',
  },
  settingTop: {
    display: 'flex', alignItems: 'center', justifyContent: 'space-between',
    marginBottom: 10,
  },
  settingIcon: {
    width: 40, height: 40, borderRadius: 10, background: '#F3F0FF',
    display: 'flex', alignItems: 'center', justifyContent: 'center',
  },
  settingTitle: { fontSize: 13, fontWeight: 600, color: '#111827', marginBottom: 4 },
  settingDesc: { fontSize: 12, color: '#6B7280', lineHeight: 1.4 },
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
  emptyLog: {
    display: 'flex', flexDirection: 'column', alignItems: 'center',
    padding: '30px 0', textAlign: 'center',
  },
  eventList: {
    maxHeight: 300, overflowY: 'auto', marginTop: 12,
  },
  eventRow: {
    display: 'flex', alignItems: 'flex-start', gap: 12,
    padding: '10px 0', borderBottom: '1px solid #F3F4F6',
  },
  eventDot: {
    width: 8, height: 8, borderRadius: '50%', flexShrink: 0, marginTop: 5,
  },
  eventContent: { flex: 1, minWidth: 0 },
  eventMsg: { fontSize: 13, color: '#374151', marginBottom: 2 },
  eventDate: { fontSize: 11, color: '#9CA3AF' },
};
