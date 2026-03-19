import { useState, useEffect } from 'react';
import { useOutletContext } from 'react-router-dom';
import { builderPluginService } from '../../services/builder-plugin.service';

interface AnalyticsData {
  views: number;
  unique_visitors: number;
  realtime: number;
  devices: { desktop: number; mobile: number; tablet: number };
  daily: { date: string; views: number }[];
  popular_pages: { page: string; views: number; unique: number }[];
  top_referrers: { source: string; visits: number }[];
}

const emptyData: AnalyticsData = {
  views: 0,
  unique_visitors: 0,
  realtime: 0,
  devices: { desktop: 0, mobile: 0, tablet: 0 },
  daily: [],
  popular_pages: [],
  top_referrers: [],
};

const periodOptions = [
  { value: 'today', label: 'Today' },
  { value: '7days', label: 'Last 7 Days' },
  { value: '30days', label: 'Last 30 Days' },
  { value: 'all', label: 'All Time' },
];

export default function AnalyticsPage() {
  const { website } = useOutletContext<{ website: any }>();
  const websiteId = website.id;

  const [period, setPeriod] = useState('7days');
  const [data, setData] = useState<AnalyticsData>(emptyData);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    let cancelled = false;
    setLoading(true);
    setError('');
    builderPluginService.getAnalytics(websiteId, period)
      .then((res) => {
        if (!cancelled) setData({ ...emptyData, ...res.data });
      })
      .catch(() => {
        if (!cancelled) {
          setError('Failed to load analytics data');
          setData(emptyData);
        }
      })
      .finally(() => { if (!cancelled) setLoading(false); });
    return () => { cancelled = true; };
  }, [websiteId, period]);

  const maxDaily = Math.max(...data.daily.map((d) => d.views), 1);
  const totalDevices = data.devices.desktop + data.devices.mobile + data.devices.tablet || 1;

  const formatNum = (n: number): string => {
    if (n >= 1000000) return (n / 1000000).toFixed(1) + 'M';
    if (n >= 1000) return (n / 1000).toFixed(1) + 'K';
    return n.toString();
  };

  if (loading) {
    return (
      <div style={s.loadingWrap}>
        <div style={s.spinner} />
        <p style={{ color: '#6B7280', fontSize: 13 }}>Loading analytics...</p>
        <style>{spinCss}</style>
      </div>
    );
  }

  return (
    <div style={s.page}>
      {error && (
        <div style={s.errorBar}>
          {error}
          <button style={s.errorClose} onClick={() => setError('')}>&times;</button>
        </div>
      )}

      {/* Header */}
      <div style={s.header}>
        <div>
          <h1 style={s.h1}>Analytics</h1>
          <p style={s.subtitle}>Track your website visitors and engagement</p>
        </div>
        <select
          style={s.periodSelect}
          className="an-period"
          value={period}
          onChange={(e) => setPeriod(e.target.value)}
        >
          {periodOptions.map((opt) => (
            <option key={opt.value} value={opt.value}>{opt.label}</option>
          ))}
        </select>
      </div>

      {/* Stats Row */}
      <div style={s.statsRow} className="an-stats-row">
        <div style={s.statCard}>
          <div style={{ ...s.statIcon, background: '#F3F0FF' }}>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#7c5cfc" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
            </svg>
          </div>
          <div style={s.statNum}>{formatNum(data.views)}</div>
          <div style={s.statLabel}>Total Views</div>
        </div>
        <div style={s.statCard}>
          <div style={{ ...s.statIcon, background: '#ECFDF5' }}>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10B981" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
              <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>
            </svg>
          </div>
          <div style={s.statNum}>{formatNum(data.unique_visitors)}</div>
          <div style={s.statLabel}>Unique Visitors</div>
        </div>
        <div style={s.statCard}>
          <div style={{ ...s.statIcon, background: '#FEF3C7' }}>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#F59E0B" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
              <circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/>
            </svg>
          </div>
          <div style={s.statNum}>{data.realtime}</div>
          <div style={s.statLabel}>Real-time</div>
        </div>
        <div style={s.statCard}>
          <div style={{ ...s.statIcon, background: '#FEE2E2' }}>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#EF4444" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
              <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
            </svg>
          </div>
          <div style={s.statNum}>{Math.round((data.devices.desktop / totalDevices) * 100)}%</div>
          <div style={s.statLabel}>Desktop</div>
        </div>
      </div>

      {/* Chart */}
      <div style={s.card}>
        <h2 style={s.cardTitle}>Views Over Time</h2>
        {data.daily.length === 0 ? (
          <div style={s.emptyChart}>
            <svg width="40" height="40" fill="none" stroke="#D1D5DB" strokeWidth="1.2" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <p style={{ fontSize: 13, color: '#6B7280', margin: '8px 0 0' }}>No data available for this period</p>
          </div>
        ) : (
          <div style={s.chartWrap}>
            <div style={s.chartBars} className="an-chart-bars">
              {data.daily.map((day, i) => (
                <div key={i} style={s.barCol}>
                  <div style={s.barTrack}>
                    <div
                      style={{
                        ...s.bar,
                        height: `${Math.max((day.views / maxDaily) * 100, 4)}%`,
                      }}
                      title={`${day.views} views`}
                      className="an-bar"
                    />
                  </div>
                  <div style={s.barLabel}>
                    {new Date(day.date).toLocaleDateString('en', { month: 'short', day: 'numeric' })}
                  </div>
                </div>
              ))}
            </div>
          </div>
        )}
      </div>

      {/* Device breakdown */}
      <div style={s.card}>
        <h2 style={s.cardTitle}>Device Breakdown</h2>
        <div style={{ marginTop: 16 }}>
          {[
            { label: 'Desktop', value: data.devices.desktop, color: '#7c5cfc' },
            { label: 'Mobile', value: data.devices.mobile, color: '#10B981' },
            { label: 'Tablet', value: data.devices.tablet, color: '#F59E0B' },
          ].map((d) => {
            const pct = Math.round((d.value / totalDevices) * 100);
            return (
              <div key={d.label} style={s.deviceRow}>
                <div style={s.deviceLabel}>
                  <span style={{ ...s.deviceDot, background: d.color }} />
                  {d.label}
                </div>
                <div style={s.deviceBarTrack}>
                  <div style={{ ...s.deviceBar, width: `${pct}%`, background: d.color }} />
                </div>
                <span style={s.devicePct}>{pct}%</span>
              </div>
            );
          })}
        </div>
      </div>

      {/* Bottom Row */}
      <div style={s.bottomRow} className="an-bottom-row">
        {/* Popular Pages */}
        <div style={s.card}>
          <h2 style={s.cardTitle}>Popular Pages</h2>
          {data.popular_pages.length === 0 ? (
            <p style={s.emptyText}>No page data yet</p>
          ) : (
            <table style={s.table}>
              <thead>
                <tr>
                  <th style={s.th}>Page</th>
                  <th style={{ ...s.th, textAlign: 'right' }}>Views</th>
                  <th style={{ ...s.th, textAlign: 'right' }}>Unique</th>
                </tr>
              </thead>
              <tbody>
                {data.popular_pages.map((p, i) => (
                  <tr key={i}>
                    <td style={s.td}>
                      <span style={s.pagePath}>{p.page}</span>
                    </td>
                    <td style={{ ...s.td, textAlign: 'right' }}>{formatNum(p.views)}</td>
                    <td style={{ ...s.td, textAlign: 'right' }}>{formatNum(p.unique)}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          )}
        </div>

        {/* Top Referrers */}
        <div style={s.card}>
          <h2 style={s.cardTitle}>Top Referrers</h2>
          {data.top_referrers.length === 0 ? (
            <p style={s.emptyText}>No referrer data yet</p>
          ) : (
            <table style={s.table}>
              <thead>
                <tr>
                  <th style={s.th}>Source</th>
                  <th style={{ ...s.th, textAlign: 'right' }}>Visits</th>
                </tr>
              </thead>
              <tbody>
                {data.top_referrers.map((r, i) => (
                  <tr key={i}>
                    <td style={s.td}>{r.source}</td>
                    <td style={{ ...s.td, textAlign: 'right' }}>{formatNum(r.visits)}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          )}
        </div>
      </div>

      <style>{cssStr}</style>
    </div>
  );
}

const spinCss = `@keyframes an-spin { to { transform: rotate(360deg); } }`;

const cssStr = `
  ${spinCss}
  .an-stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 20px;
  }
  .an-chart-bars {
    display: flex;
    align-items: flex-end;
    gap: 6px;
    height: 200px;
  }
  .an-bar:hover { opacity: 0.8; }
  .an-bottom-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
  }
  .an-period:focus { border-color: #7c5cfc; outline: none; }
  @media (max-width: 900px) {
    .an-stats-row { grid-template-columns: repeat(2, 1fr) !important; }
    .an-bottom-row { grid-template-columns: 1fr !important; }
  }
  @media (max-width: 500px) {
    .an-stats-row { grid-template-columns: 1fr !important; }
  }
`;

const s: Record<string, React.CSSProperties> = {
  page: {},
  loadingWrap: {
    display: 'flex', flexDirection: 'column', alignItems: 'center',
    justifyContent: 'center', minHeight: 300,
  },
  spinner: {
    width: 28, height: 28, border: '3px solid #E5E7EB', borderTopColor: '#7c5cfc',
    borderRadius: '50%', animation: 'an-spin 0.6s linear infinite',
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
  periodSelect: {
    padding: '8px 14px', border: '1px solid #D1D5DB', borderRadius: 8,
    fontSize: 13, color: '#374151', background: '#fff', cursor: 'pointer',
  },
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
  emptyChart: {
    display: 'flex', flexDirection: 'column', alignItems: 'center',
    justifyContent: 'center', padding: '40px 0',
  },
  chartWrap: { marginTop: 16 },
  chartBars: {},
  barCol: {
    flex: 1, display: 'flex', flexDirection: 'column', alignItems: 'center',
    minWidth: 0,
  },
  barTrack: {
    width: '100%', maxWidth: 40, height: '100%',
    display: 'flex', alignItems: 'flex-end', justifyContent: 'center',
  },
  bar: {
    width: '100%', background: '#7c5cfc', borderRadius: '4px 4px 0 0',
    transition: 'height 0.3s', cursor: 'pointer',
  },
  barLabel: {
    fontSize: 10, color: '#9CA3AF', marginTop: 6, whiteSpace: 'nowrap',
  },
  deviceRow: {
    display: 'flex', alignItems: 'center', gap: 12, marginBottom: 12,
  },
  deviceLabel: {
    display: 'flex', alignItems: 'center', gap: 8, fontSize: 13,
    fontWeight: 500, color: '#374151', width: 80, flexShrink: 0,
  },
  deviceDot: {
    width: 8, height: 8, borderRadius: '50%', flexShrink: 0,
  },
  deviceBarTrack: {
    flex: 1, height: 8, borderRadius: 4, background: '#F3F4F6',
    overflow: 'hidden',
  },
  deviceBar: {
    height: '100%', borderRadius: 4, transition: 'width 0.3s',
  },
  devicePct: { fontSize: 13, fontWeight: 600, color: '#111827', width: 40, textAlign: 'right' },
  bottomRow: {},
  table: { width: '100%', borderCollapse: 'collapse', marginTop: 12 },
  th: {
    textAlign: 'left', padding: '8px 0', fontSize: 11, fontWeight: 600,
    color: '#6B7280', borderBottom: '1px solid #E5E7EB',
    textTransform: 'uppercase', letterSpacing: 0.3,
  },
  td: {
    padding: '10px 0', fontSize: 13, color: '#111827',
    borderBottom: '1px solid #F3F4F6',
  },
  pagePath: {
    fontSize: 13, color: '#374151', fontWeight: 500,
    maxWidth: 200, overflow: 'hidden', textOverflow: 'ellipsis',
    whiteSpace: 'nowrap', display: 'block',
  },
  emptyText: { fontSize: 13, color: '#9CA3AF', margin: '20px 0', textAlign: 'center' },
};
