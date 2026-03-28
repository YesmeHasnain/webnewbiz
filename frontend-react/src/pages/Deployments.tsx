import { useState, useEffect } from 'react';
import api from '../services/api';

interface Deployment {
  id: number; type: string; status: string; domain: string | null; subdomain: string;
  url: string; provider: string; ssl_status: string; server_ip: string;
  build_log: Array<{ time: string; msg: string }> | null; env_vars: Record<string, string> | null;
  deployed_at: string; expires_at: string; created_at: string;
}

export default function Deployments() {
  const [deployments, setDeployments] = useState<Deployment[]>([]);
  const [loading, setLoading] = useState(true);
  const [selected, setSelected] = useState<Deployment | null>(null);
  const [tab, setTab] = useState<'overview' | 'logs' | 'env' | 'domain'>('overview');
  const [newEnvKey, setNewEnvKey] = useState('');
  const [newEnvVal, setNewEnvVal] = useState('');
  const [customDomain, setCustomDomain] = useState('');

  useEffect(() => { loadDeployments(); }, []);
  const loadDeployments = () => { api.get('/deployments').then(r => { setDeployments(r.data); setLoading(false); }).catch(() => setLoading(false)); };

  const handleRedeploy = async (id: number) => {
    try { await api.post(`/deployments/${id}/redeploy`); loadDeployments(); } catch {}
  };
  const handleStop = async (id: number) => {
    if (!confirm('Stop this deployment?')) return;
    try { await api.post(`/deployments/${id}/stop`); loadDeployments(); setSelected(null); } catch {}
  };
  const handleAddDomain = async (id: number) => {
    if (!customDomain.trim()) return;
    try { await api.post(`/deployments/${id}/domain`, { domain: customDomain }); setCustomDomain(''); loadDeployments(); } catch {}
  };

  if (loading) return <div className="min-h-screen bg-[#0a0a0f] flex items-center justify-center"><div className="w-10 h-10 border-2 border-blue-500/30 border-t-blue-500 rounded-full animate-spin" /></div>;

  return (
    <div className="min-h-screen bg-[#0a0a0f] text-white">
      <div className="max-w-7xl mx-auto px-6 py-8">
        <div className="flex items-center justify-between mb-8">
          <div><h1 className="text-2xl font-bold">Deployments</h1><p className="text-sm text-gray-500 mt-1">{deployments.length} active deployment{deployments.length !== 1 ? 's' : ''}</p></div>
        </div>

        {deployments.length === 0 ? (
          <div className="text-center py-20">
            <div className="w-16 h-16 mx-auto mb-4 rounded-2xl bg-[#12121a] border border-[#1e1e2e] flex items-center justify-center">
              <svg className="w-8 h-8 text-gray-700" fill="none" stroke="currentColor" strokeWidth={1} viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <p className="text-gray-500">No deployments yet</p>
            <p className="text-xs text-gray-700 mt-1">Build a website or app, then deploy it here</p>
          </div>
        ) : (
          <div className="flex gap-6">
            {/* Deployment list */}
            <div className="w-80 space-y-3 flex-shrink-0">
              {deployments.map(d => (
                <button key={d.id} onClick={() => { setSelected(d); setTab('overview'); }} className={`w-full text-left bg-[#12121a] border rounded-xl p-4 transition ${selected?.id === d.id ? 'border-blue-500/50 bg-blue-600/5' : 'border-[#1e1e2e] hover:border-[#2e2e3e]'}`}>
                  <div className="flex items-center gap-3">
                    <div className={`w-2.5 h-2.5 rounded-full flex-shrink-0 ${d.status === 'active' ? 'bg-emerald-400' : d.status === 'deploying' ? 'bg-amber-400 animate-pulse' : 'bg-gray-600'}`} />
                    <div className="min-w-0"><p className="text-sm font-medium text-white truncate">{d.domain || d.subdomain}</p><p className="text-xs text-gray-500">{d.type} &middot; {d.provider}</p></div>
                  </div>
                </button>
              ))}
            </div>

            {/* Detail panel */}
            {selected ? (
              <div className="flex-1 bg-[#12121a] border border-[#1e1e2e] rounded-2xl overflow-hidden">
                {/* Header */}
                <div className="p-5 border-b border-[#1e1e2e] flex items-center justify-between">
                  <div>
                    <h2 className="text-lg font-semibold">{selected.domain || selected.subdomain}</h2>
                    <p className="text-xs text-gray-500 mt-0.5">{selected.server_ip} &middot; SSL: {selected.ssl_status}</p>
                  </div>
                  <div className="flex gap-2">
                    <button onClick={() => handleRedeploy(selected.id)} className="px-3 py-1.5 bg-blue-600 hover:bg-blue-500 rounded-lg text-xs font-medium">Redeploy</button>
                    <a href={selected.url} target="_blank" rel="noopener" className="px-3 py-1.5 bg-white/5 hover:bg-white/10 rounded-lg text-xs text-gray-300">Visit</a>
                    <button onClick={() => handleStop(selected.id)} className="px-3 py-1.5 bg-red-600/10 hover:bg-red-600/20 rounded-lg text-xs text-red-400">Stop</button>
                  </div>
                </div>

                {/* Tabs */}
                <div className="flex border-b border-[#1e1e2e]">
                  {(['overview', 'logs', 'env', 'domain'] as const).map(t => (
                    <button key={t} onClick={() => setTab(t)} className={`px-5 py-3 text-xs font-medium transition ${tab === t ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-500 hover:text-gray-300'}`}>
                      {t === 'env' ? 'Environment' : t.charAt(0).toUpperCase() + t.slice(1)}
                    </button>
                  ))}
                </div>

                <div className="p-5">
                  {tab === 'overview' && (
                    <div className="grid grid-cols-2 gap-4">
                      {[
                        { label: 'Status', value: selected.status },
                        { label: 'Provider', value: selected.provider },
                        { label: 'SSL', value: selected.ssl_status },
                        { label: 'Server IP', value: selected.server_ip },
                        { label: 'Deployed', value: selected.deployed_at ? new Date(selected.deployed_at).toLocaleString() : '—' },
                        { label: 'Expires', value: selected.expires_at ? new Date(selected.expires_at).toLocaleDateString() : '—' },
                      ].map(item => (
                        <div key={item.label} className="bg-[#0a0a12] rounded-xl p-4"><p className="text-xs text-gray-500">{item.label}</p><p className="text-sm text-white mt-1">{item.value}</p></div>
                      ))}
                    </div>
                  )}

                  {tab === 'logs' && (
                    <div className="bg-[#0a0a12] rounded-xl p-4 font-mono text-xs space-y-1 max-h-96 overflow-auto">
                      {selected.build_log?.map((log, i) => (
                        <div key={i} className="flex gap-3"><span className="text-gray-600 flex-shrink-0">{new Date(log.time).toLocaleTimeString()}</span><span className="text-gray-300">{log.msg}</span></div>
                      )) || <p className="text-gray-600">No logs available</p>}
                    </div>
                  )}

                  {tab === 'env' && (
                    <div className="space-y-4">
                      <div className="flex gap-3">
                        <input value={newEnvKey} onChange={e => setNewEnvKey(e.target.value)} placeholder="KEY" className="flex-1 bg-[#0a0a12] border border-[#1e1e2e] rounded-lg px-3 py-2 text-xs text-white font-mono outline-none" />
                        <input value={newEnvVal} onChange={e => setNewEnvVal(e.target.value)} placeholder="value" className="flex-1 bg-[#0a0a12] border border-[#1e1e2e] rounded-lg px-3 py-2 text-xs text-white font-mono outline-none" />
                        <button onClick={() => { setNewEnvKey(''); setNewEnvVal(''); }} className="px-4 py-2 bg-blue-600 rounded-lg text-xs font-medium">Add</button>
                      </div>
                      <div className="bg-[#0a0a12] rounded-xl p-4 space-y-2">
                        {Object.entries(selected.env_vars || {}).map(([k, v]) => (
                          <div key={k} className="flex items-center gap-3 font-mono text-xs"><span className="text-emerald-400">{k}</span><span className="text-gray-600">=</span><span className="text-gray-300">{v}</span></div>
                        ))}
                        {!selected.env_vars && <p className="text-gray-600 text-xs">No environment variables set</p>}
                      </div>
                    </div>
                  )}

                  {tab === 'domain' && (
                    <div className="space-y-4">
                      <div className="flex gap-3">
                        <input value={customDomain} onChange={e => setCustomDomain(e.target.value)} placeholder="yourdomain.com" className="flex-1 bg-[#0a0a12] border border-[#1e1e2e] rounded-lg px-4 py-2.5 text-sm text-white outline-none" />
                        <button onClick={() => handleAddDomain(selected.id)} className="px-5 py-2.5 bg-blue-600 rounded-lg text-sm font-medium">Connect</button>
                      </div>
                      <div className="bg-[#0a0a12] rounded-xl p-4">
                        <p className="text-xs text-gray-400 font-medium mb-3">DNS Records:</p>
                        <div className="space-y-2 font-mono text-xs">
                          <div className="flex gap-4"><span className="text-gray-500 w-16">A</span><span className="text-gray-500 w-8">@</span><span className="text-white">{selected.server_ip}</span></div>
                          <div className="flex gap-4"><span className="text-gray-500 w-16">CNAME</span><span className="text-gray-500 w-8">www</span><span className="text-white">{selected.subdomain}</span></div>
                        </div>
                      </div>
                    </div>
                  )}
                </div>
              </div>
            ) : (
              <div className="flex-1 flex items-center justify-center text-gray-600 text-sm">Select a deployment to view details</div>
            )}
          </div>
        )}
      </div>
    </div>
  );
}
