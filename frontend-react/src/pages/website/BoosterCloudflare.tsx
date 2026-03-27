import { useState } from 'react';

export default function BoosterCloudflare() {
  const [connected, setConnected] = useState(false);
  const [apiToken, setApiToken] = useState('');
  const stats = { requests: '12.4K', bandwidth: '1.2 GB', threats: 3, cached: '78%' };

  return (
    <div className="p-6">
      <h1 className="text-xl font-bold text-white mb-1">Cloudflare CDN</h1>
      <p className="text-sm text-gray-500 mb-6">Connect Cloudflare for global CDN, DDoS protection, and caching</p>
      {!connected ? (
        <div className="bg-[#12121a] border border-[#1e1e2e] rounded-2xl p-8 max-w-lg">
          <div className="text-center mb-6"><span className="text-4xl">☁️</span><h3 className="text-lg font-semibold text-white mt-3">Connect Cloudflare</h3><p className="text-sm text-gray-500 mt-1">Enter your Cloudflare API token to enable CDN</p></div>
          <input value={apiToken} onChange={e => setApiToken(e.target.value)} placeholder="Cloudflare API Token" className="w-full bg-[#0a0a12] border border-[#1e1e2e] rounded-xl px-4 py-3 text-sm text-white outline-none mb-4" />
          <button onClick={() => setConnected(true)} className="w-full py-3 bg-blue-600 hover:bg-blue-500 rounded-xl text-sm font-medium text-white">Connect</button>
        </div>
      ) : (
        <div className="space-y-6">
          <div className="grid grid-cols-4 gap-4">
            {Object.entries(stats).map(([k, v]) => (
              <div key={k} className="bg-[#12121a] border border-[#1e1e2e] rounded-xl p-4"><p className="text-xs text-gray-500 capitalize">{k}</p><p className="text-xl font-bold text-white mt-1">{v}</p></div>
            ))}
          </div>
          <div className="bg-[#12121a] border border-[#1e1e2e] rounded-xl p-5">
            <h3 className="text-sm font-medium text-white mb-4">CDN Settings</h3>
            <div className="space-y-3">
              {['Auto Minify (JS/CSS/HTML)', 'Browser Cache TTL (4 hours)', 'Always HTTPS', 'Automatic Platform Optimization'].map(s => (
                <div key={s} className="flex items-center justify-between"><span className="text-sm text-gray-300">{s}</span><div className="w-10 h-5 bg-blue-600 rounded-full"><div className="w-4 h-4 bg-white rounded-full translate-x-5 mt-0.5" /></div></div>
              ))}
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
