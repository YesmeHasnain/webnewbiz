import { useState, useEffect } from 'react';
import api from '../services/api';

interface Deployment {
  id: number; type: string; status: string; domain: string | null; subdomain: string;
  url: string; provider: string; ssl_status: string; server_ip: string;
  deployed_at: string; expires_at: string; created_at: string;
}

export default function Deployments() {
  const [deployments, setDeployments] = useState<Deployment[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    api.get('/deployments').then(r => { setDeployments(r.data); setLoading(false); }).catch(() => setLoading(false));
  }, []);

  if (loading) return <div className="min-h-screen bg-[#0a0a0f] flex items-center justify-center"><div className="w-10 h-10 border-2 border-blue-500/30 border-t-blue-500 rounded-full animate-spin" /></div>;

  return (
    <div className="min-h-screen bg-[#0a0a0f] text-white">
      <div className="max-w-6xl mx-auto px-6 py-8">
        <div className="flex items-center justify-between mb-8">
          <div>
            <h1 className="text-2xl font-bold">Deployments</h1>
            <p className="text-sm text-gray-500 mt-1">Manage your live websites and apps</p>
          </div>
        </div>

        {deployments.length === 0 ? (
          <div className="text-center py-20">
            <div className="w-16 h-16 mx-auto mb-4 rounded-2xl bg-[#12121a] border border-[#1e1e2e] flex items-center justify-center">
              <svg className="w-8 h-8 text-gray-700" fill="none" stroke="currentColor" strokeWidth={1} viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <p className="text-gray-500">No deployments yet</p>
            <p className="text-xs text-gray-700 mt-1">Build a website or app, then deploy it here</p>
          </div>
        ) : (
          <div className="grid gap-4">
            {deployments.map(d => (
              <div key={d.id} className="bg-[#12121a] border border-[#1e1e2e] rounded-2xl p-5 flex items-center justify-between">
                <div className="flex items-center gap-4">
                  <div className={`w-3 h-3 rounded-full ${d.status === 'active' ? 'bg-emerald-400' : d.status === 'deploying' ? 'bg-amber-400 animate-pulse' : 'bg-gray-600'}`} />
                  <div>
                    <p className="text-sm font-medium text-white">{d.domain || d.subdomain}</p>
                    <p className="text-xs text-gray-500 mt-0.5">{d.type} &middot; {d.provider} &middot; {d.server_ip}</p>
                  </div>
                </div>
                <div className="flex items-center gap-3">
                  <span className={`px-2.5 py-1 rounded-md text-xs font-medium ${
                    d.ssl_status === 'active' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-amber-500/10 text-amber-400'
                  }`}>SSL {d.ssl_status}</span>
                  <a href={d.url} target="_blank" rel="noopener" className="text-xs text-blue-400 hover:underline">Visit</a>
                </div>
              </div>
            ))}
          </div>
        )}
      </div>
    </div>
  );
}
