import { useState, useEffect } from 'react';
import api from '../services/api';

interface AnalyticsData {
  total_events: number;
  pageviews: number;
  downloads: number;
  top_sources: { source: string; count: number }[];
  top_countries: { country: string; count: number }[];
  daily_events: { date: string; count: number }[];
  active_deployments: number;
  total_projects: number;
  total_apps: number;
}

export default function PlatformAnalytics() {
  const [data, setData] = useState<AnalyticsData | null>(null);
  const [days, setDays] = useState(30);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    api.get(`/analytics/overview?days=${days}`).then(r => { setData(r.data); setLoading(false); }).catch(() => setLoading(false));
  }, [days]);

  if (loading) return <div className="min-h-screen bg-[#0a0a0f] flex items-center justify-center"><div className="w-10 h-10 border-2 border-blue-500/30 border-t-blue-500 rounded-full animate-spin" /></div>;

  const stats = [
    { label: 'Total Events', value: data?.total_events || 0, color: 'blue' },
    { label: 'Page Views', value: data?.pageviews || 0, color: 'emerald' },
    { label: 'Downloads', value: data?.downloads || 0, color: 'purple' },
    { label: 'Active Deployments', value: data?.active_deployments || 0, color: 'amber' },
    { label: 'Projects', value: data?.total_projects || 0, color: 'cyan' },
    { label: 'Apps', value: data?.total_apps || 0, color: 'pink' },
  ];

  return (
    <div className="min-h-screen bg-[#0a0a0f] text-white">
      <div className="max-w-7xl mx-auto px-6 py-8">
        <div className="flex items-center justify-between mb-8">
          <div>
            <h1 className="text-2xl font-bold">Analytics</h1>
            <p className="text-sm text-gray-500 mt-1">Unified view of all your websites and apps</p>
          </div>
          <div className="flex gap-2">
            {[7, 30, 90].map(d => (
              <button key={d} onClick={() => setDays(d)}
                className={`px-3 py-1.5 rounded-lg text-xs font-medium transition ${days === d ? 'bg-blue-600 text-white' : 'bg-[#12121a] text-gray-400 border border-[#1e1e2e] hover:border-[#2e2e3e]'}`}>
                {d}d
              </button>
            ))}
          </div>
        </div>

        {/* Stats grid */}
        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
          {stats.map(s => (
            <div key={s.label} className="bg-[#12121a] border border-[#1e1e2e] rounded-2xl p-4">
              <p className="text-xs text-gray-500">{s.label}</p>
              <p className="text-2xl font-bold mt-1">{s.value.toLocaleString()}</p>
            </div>
          ))}
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
          {/* Top Sources */}
          <div className="bg-[#12121a] border border-[#1e1e2e] rounded-2xl p-6">
            <h3 className="text-sm font-semibold text-gray-300 mb-4">Top Sources</h3>
            {(data?.top_sources || []).length === 0 ? (
              <p className="text-xs text-gray-600 text-center py-8">No data yet. Deploy a website to start tracking.</p>
            ) : (
              <div className="space-y-3">
                {data?.top_sources.map(s => (
                  <div key={s.source} className="flex items-center justify-between">
                    <span className="text-sm text-gray-300 capitalize">{s.source || 'direct'}</span>
                    <span className="text-sm font-medium">{s.count}</span>
                  </div>
                ))}
              </div>
            )}
          </div>

          {/* Top Countries */}
          <div className="bg-[#12121a] border border-[#1e1e2e] rounded-2xl p-6">
            <h3 className="text-sm font-semibold text-gray-300 mb-4">Top Countries</h3>
            {(data?.top_countries || []).length === 0 ? (
              <p className="text-xs text-gray-600 text-center py-8">No data yet. Deploy a website to start tracking.</p>
            ) : (
              <div className="space-y-3">
                {data?.top_countries.map(c => (
                  <div key={c.country} className="flex items-center justify-between">
                    <span className="text-sm text-gray-300">{c.country || 'Unknown'}</span>
                    <span className="text-sm font-medium">{c.count}</span>
                  </div>
                ))}
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}
