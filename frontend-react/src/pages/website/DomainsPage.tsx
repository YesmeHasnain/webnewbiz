import { useState } from 'react';

export default function DomainsPage() {
  const [domains, setDomains] = useState<Array<{id: number; domain: string; status: string; type: string}>>([]);
  const [newDomain, setNewDomain] = useState('');

  const handleAdd = () => {
    if (!newDomain.trim()) return;
    setDomains(prev => [...prev, { id: Date.now(), domain: newDomain, status: 'pending', type: 'primary' }]);
    setNewDomain('');
  };

  return (
    <div className="p-6">
      <h1 className="text-xl font-bold text-white mb-1">Custom Domains</h1>
      <p className="text-sm text-gray-500 mb-6">Connect your own domain to this website</p>

      <div className="flex gap-3 mb-6">
        <input value={newDomain} onChange={e => setNewDomain(e.target.value)} placeholder="yourdomain.com" className="flex-1 bg-[#0a0a12] border border-[#1e1e2e] rounded-xl px-4 py-2.5 text-sm text-white outline-none focus:border-blue-500/50" onKeyDown={e => e.key === 'Enter' && handleAdd()} />
        <button onClick={handleAdd} className="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 rounded-xl text-sm font-medium text-white">Add Domain</button>
      </div>

      {domains.length > 0 ? (
        <div className="space-y-3">
          {domains.map(d => (
            <div key={d.id} className="bg-[#12121a] border border-[#1e1e2e] rounded-xl p-4 flex items-center justify-between">
              <div><p className="text-sm text-white font-medium">{d.domain}</p><p className="text-xs text-gray-500 mt-1">Type: {d.type}</p></div>
              <span className={`px-2.5 py-1 rounded-lg text-xs font-medium ${d.status === 'active' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-amber-500/10 text-amber-400'}`}>{d.status}</span>
            </div>
          ))}
        </div>
      ) : (
        <div className="bg-[#12121a] border border-[#1e1e2e] rounded-xl p-8 text-center">
          <p className="text-gray-500 text-sm">No custom domains configured</p>
          <p className="text-xs text-gray-600 mt-2">Add a domain above, then update your DNS records</p>
          <div className="mt-6 bg-[#0a0a12] rounded-xl p-4 text-left max-w-md mx-auto">
            <p className="text-xs text-gray-400 font-medium mb-2">DNS Records Required:</p>
            <div className="space-y-1 text-xs text-gray-500 font-mono">
              <p>A     @    → 167.172.x.x</p>
              <p>CNAME www  → your-site.webnewbiz.app</p>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
