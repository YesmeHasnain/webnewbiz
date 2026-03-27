import { useState, useEffect } from 'react';
import { crmService } from '../../services/crm.service';

interface Sequence { id: number; name: string; trigger: string; status: string; steps_count: number; created_at: string; }

export default function Sequences() {
  const [sequences, setSequences] = useState<Sequence[]>([]);
  const [loading, setLoading] = useState(true);
  const [showForm, setShowForm] = useState(false);
  const [form, setForm] = useState({ name: '', trigger: 'manual' });

  useEffect(() => { loadSequences(); }, []);
  const loadSequences = async () => { try { const r = await crmService.getSequences(); setSequences(r.data); } catch {} finally { setLoading(false); } };

  const handleCreate = async () => {
    if (!form.name.trim()) return;
    try { await crmService.createSequence(form); setShowForm(false); setForm({ name: '', trigger: 'manual' }); loadSequences(); } catch {}
  };

  if (loading) return <div className="min-h-screen bg-[#0a0a0f] flex items-center justify-center"><div className="w-10 h-10 border-2 border-blue-500/30 border-t-blue-500 rounded-full animate-spin" /></div>;

  return (
    <div className="min-h-screen bg-[#0a0a0f] text-white">
      <div className="max-w-6xl mx-auto px-6 py-8">
        <div className="flex items-center justify-between mb-8">
          <div><h1 className="text-2xl font-bold">Email Sequences</h1><p className="text-sm text-gray-500 mt-1">Automated drip campaigns for nurturing leads</p></div>
          <button onClick={() => setShowForm(!showForm)} className="px-4 py-2 bg-blue-600 hover:bg-blue-500 rounded-xl text-sm font-medium transition">+ New Sequence</button>
        </div>

        {showForm && (
          <div className="bg-[#12121a] border border-[#1e1e2e] rounded-2xl p-6 mb-6 space-y-4">
            <input value={form.name} onChange={e => setForm({...form, name: e.target.value})} placeholder="Sequence name" className="w-full bg-[#0a0a12] border border-[#1e1e2e] rounded-xl px-4 py-3 text-sm text-white outline-none focus:border-blue-500/50" />
            <select value={form.trigger} onChange={e => setForm({...form, trigger: e.target.value})} className="w-full bg-[#0a0a12] border border-[#1e1e2e] rounded-xl px-4 py-3 text-sm text-white outline-none">
              <option value="manual">Manual Enrollment</option>
              <option value="form_submit">Form Submission</option>
              <option value="tag_added">Tag Added</option>
              <option value="purchase">Purchase</option>
            </select>
            <div className="flex gap-3 justify-end">
              <button onClick={() => setShowForm(false)} className="px-4 py-2 text-gray-400 text-sm">Cancel</button>
              <button onClick={handleCreate} className="px-5 py-2 bg-blue-600 hover:bg-blue-500 rounded-xl text-sm font-medium">Create</button>
            </div>
          </div>
        )}

        <div className="space-y-3">
          {sequences.map(s => (
            <div key={s.id} className="bg-[#12121a] border border-[#1e1e2e] rounded-xl p-5 flex items-center justify-between">
              <div>
                <h3 className="text-sm font-medium text-white">{s.name}</h3>
                <p className="text-xs text-gray-500 mt-1">Trigger: {s.trigger} | {s.steps_count} steps</p>
              </div>
              <span className={`px-2.5 py-1 rounded-lg text-xs font-medium ${s.status === 'active' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-gray-500/10 text-gray-400'}`}>{s.status}</span>
            </div>
          ))}
          {sequences.length === 0 && <div className="text-center py-12"><p className="text-gray-600">No sequences yet</p></div>}
        </div>
      </div>
    </div>
  );
}
