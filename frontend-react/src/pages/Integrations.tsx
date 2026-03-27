import { useState, useEffect } from 'react';
import api from '../services/api';

interface Integration {
  id: number; platform: string; store_name: string; store_url: string; status: string; created_at: string;
}

const platforms = [
  { id: 'shopify', name: 'Shopify', icon: '🛍️', color: 'bg-green-500/10 border-green-500/20', desc: 'Connect your Shopify store' },
  { id: 'squarespace', name: 'Squarespace', icon: '⬜', color: 'bg-white/5 border-white/10', desc: 'Connect your Squarespace site' },
  { id: 'woocommerce', name: 'WooCommerce', icon: '🟣', color: 'bg-purple-500/10 border-purple-500/20', desc: 'Connect your WooCommerce store' },
];

export default function Integrations() {
  const [integrations, setIntegrations] = useState<Integration[]>([]);
  const [loading, setLoading] = useState(true);
  const [showConnect, setShowConnect] = useState<string | null>(null);
  const [storeName, setStoreName] = useState('');
  const [storeUrl, setStoreUrl] = useState('');

  useEffect(() => {
    api.get('/integrations').then(r => { setIntegrations(r.data); setLoading(false); }).catch(() => setLoading(false));
  }, []);

  const handleConnect = async () => {
    if (!showConnect || !storeName || !storeUrl) return;
    try {
      const res = await api.post('/integrations', { platform: showConnect, store_name: storeName, store_url: storeUrl });
      setIntegrations(prev => [...prev, res.data]);
      setShowConnect(null); setStoreName(''); setStoreUrl('');
    } catch { alert('Failed to connect'); }
  };

  if (loading) return <div className="min-h-screen bg-[#0a0a0f] flex items-center justify-center"><div className="w-10 h-10 border-2 border-blue-500/30 border-t-blue-500 rounded-full animate-spin" /></div>;

  return (
    <div className="min-h-screen bg-[#0a0a0f] text-white">
      <div className="max-w-5xl mx-auto px-6 py-8">
        <h1 className="text-2xl font-bold mb-1">Integrations</h1>
        <p className="text-sm text-gray-500 mb-8">Connect external platforms</p>

        {/* Available platforms */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
          {platforms.map(p => {
            const connected = integrations.find(i => i.platform === p.id && i.status === 'connected');
            return (
              <div key={p.id} className={`${p.color} border rounded-2xl p-5`}>
                <div className="text-2xl mb-2">{p.icon}</div>
                <h3 className="text-base font-semibold">{p.name}</h3>
                <p className="text-xs text-gray-500 mt-1">{p.desc}</p>
                {connected ? (
                  <div className="mt-3 flex items-center gap-2">
                    <div className="w-2 h-2 bg-emerald-400 rounded-full" />
                    <span className="text-xs text-emerald-400">Connected: {connected.store_name}</span>
                  </div>
                ) : (
                  <button onClick={() => setShowConnect(p.id)}
                    className="mt-3 px-4 py-1.5 bg-white/10 rounded-lg text-xs font-medium hover:bg-white/20 transition">
                    Connect
                  </button>
                )}
              </div>
            );
          })}
        </div>

        {/* Connect modal */}
        {showConnect && (
          <div className="fixed inset-0 bg-black/60 flex items-center justify-center z-50" onClick={() => setShowConnect(null)}>
            <div className="bg-[#12121a] border border-[#1e1e2e] rounded-2xl p-6 w-full max-w-md" onClick={e => e.stopPropagation()}>
              <h3 className="text-lg font-semibold mb-4">Connect {platforms.find(p => p.id === showConnect)?.name}</h3>
              <div className="space-y-3">
                <input value={storeName} onChange={e => setStoreName(e.target.value)} placeholder="Store Name"
                  className="w-full bg-[#0a0a12] border border-[#1e1e2e] rounded-xl px-4 py-3 text-sm text-white outline-none" />
                <input value={storeUrl} onChange={e => setStoreUrl(e.target.value)} placeholder="Store URL (https://...)"
                  className="w-full bg-[#0a0a12] border border-[#1e1e2e] rounded-xl px-4 py-3 text-sm text-white outline-none" />
              </div>
              <div className="flex justify-end gap-3 mt-5">
                <button onClick={() => setShowConnect(null)} className="px-4 py-2 text-sm text-gray-400">Cancel</button>
                <button onClick={handleConnect} className="px-5 py-2 bg-blue-600 text-white rounded-xl text-sm font-medium">Connect</button>
              </div>
            </div>
          </div>
        )}

        {/* Connected integrations */}
        {integrations.length > 0 && (
          <div>
            <h2 className="text-lg font-semibold mb-4">Connected</h2>
            <div className="space-y-3">
              {integrations.map(i => (
                <div key={i.id} className="bg-[#12121a] border border-[#1e1e2e] rounded-xl p-4 flex items-center justify-between">
                  <div>
                    <p className="text-sm font-medium capitalize">{i.platform} — {i.store_name}</p>
                    <p className="text-xs text-gray-500">{i.store_url}</p>
                  </div>
                  <span className={`px-2 py-0.5 rounded text-xs ${i.status === 'connected' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-red-500/10 text-red-400'}`}>{i.status}</span>
                </div>
              ))}
            </div>
          </div>
        )}
      </div>
    </div>
  );
}
