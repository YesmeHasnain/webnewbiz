import { useState, useEffect } from 'react';
import { crmService } from '../../services/crm.service';

interface Campaign { id: number; name: string; subject: string; status: string; stats: { total: number; opened: number; clicked: number } | null; sent_at: string | null; created_at: string; }

export default function Campaigns() {
  const [campaigns, setCampaigns] = useState<Campaign[]>([]);
  const [loading, setLoading] = useState(true);
  const [showForm, setShowForm] = useState(false);
  const [form, setForm] = useState({ name: '', subject: '', body_html: '<p>Your email content here...</p>' });

  useEffect(() => { loadCampaigns(); }, []);

  const loadCampaigns = async () => {
    try { const res = await crmService.getCampaigns(); setCampaigns(res.data); } catch {} finally { setLoading(false); }
  };

  const handleCreate = async () => {
    if (!form.name.trim() || !form.subject.trim()) return;
    try { await crmService.createCampaign(form); setShowForm(false); setForm({ name: '', subject: '', body_html: '<p>Your email content here...</p>' }); loadCampaigns(); } catch {}
  };

  const handleSend = async (id: number) => {
    if (!confirm('Send this campaign to all contacts?')) return;
    try { await crmService.sendCampaign(id); loadCampaigns(); } catch {}
  };

  if (loading) return <div className="min-h-screen bg-[#0a0a0f] flex items-center justify-center"><div className="w-10 h-10 border-2 border-blue-500/30 border-t-blue-500 rounded-full animate-spin" /></div>;

  return (
    <div className="min-h-screen bg-[#0a0a0f] text-white">
      <div className="max-w-6xl mx-auto px-6 py-8">
        <div className="flex items-center justify-between mb-8">
          <div><h1 className="text-2xl font-bold">Email Campaigns</h1><p className="text-sm text-gray-500 mt-1">Create and send email campaigns to your contacts</p></div>
          <button onClick={() => setShowForm(!showForm)} className="px-4 py-2 bg-blue-600 hover:bg-blue-500 rounded-xl text-sm font-medium transition">+ New Campaign</button>
        </div>

        {showForm && (
          <div className="bg-[#12121a] border border-[#1e1e2e] rounded-2xl p-6 mb-6 space-y-4">
            <input value={form.name} onChange={e => setForm({...form, name: e.target.value})} placeholder="Campaign name" className="w-full bg-[#0a0a12] border border-[#1e1e2e] rounded-xl px-4 py-3 text-sm text-white outline-none focus:border-blue-500/50" />
            <input value={form.subject} onChange={e => setForm({...form, subject: e.target.value})} placeholder="Email subject line" className="w-full bg-[#0a0a12] border border-[#1e1e2e] rounded-xl px-4 py-3 text-sm text-white outline-none focus:border-blue-500/50" />
            <textarea value={form.body_html} onChange={e => setForm({...form, body_html: e.target.value})} rows={4} className="w-full bg-[#0a0a12] border border-[#1e1e2e] rounded-xl px-4 py-3 text-sm text-white outline-none focus:border-blue-500/50 resize-none" />
            <div className="flex gap-3 justify-end">
              <button onClick={() => setShowForm(false)} className="px-4 py-2 text-gray-400 hover:text-white text-sm">Cancel</button>
              <button onClick={handleCreate} className="px-5 py-2 bg-blue-600 hover:bg-blue-500 rounded-xl text-sm font-medium">Create Campaign</button>
            </div>
          </div>
        )}

        <div className="space-y-3">
          {campaigns.map(c => (
            <div key={c.id} className="bg-[#12121a] border border-[#1e1e2e] rounded-xl p-5 flex items-center justify-between">
              <div>
                <h3 className="text-sm font-medium text-white">{c.name}</h3>
                <p className="text-xs text-gray-500 mt-1">Subject: {c.subject}</p>
                {c.stats && <p className="text-xs text-gray-600 mt-1">Sent: {c.stats.total} | Opened: {c.stats.opened} | Clicked: {c.stats.clicked}</p>}
              </div>
              <div className="flex items-center gap-3">
                <span className={`px-2.5 py-1 rounded-lg text-xs font-medium ${c.status === 'sent' ? 'bg-emerald-500/10 text-emerald-400' : c.status === 'draft' ? 'bg-gray-500/10 text-gray-400' : 'bg-amber-500/10 text-amber-400'}`}>{c.status}</span>
                {c.status === 'draft' && <button onClick={() => handleSend(c.id)} className="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 rounded-lg text-xs font-medium">Send</button>}
              </div>
            </div>
          ))}
          {campaigns.length === 0 && <div className="text-center py-12"><p className="text-gray-600">No campaigns yet</p></div>}
        </div>
      </div>
    </div>
  );
}
