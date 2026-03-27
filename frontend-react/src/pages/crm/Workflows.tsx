import { useState, useEffect } from 'react';
import { crmService } from '../../services/crm.service';

interface Workflow { id: number; name: string; trigger_type: string; status: string; steps_count: number; created_at: string; }

export default function Workflows() {
  const [workflows, setWorkflows] = useState<Workflow[]>([]);
  const [loading, setLoading] = useState(true);
  const [showForm, setShowForm] = useState(false);
  const [form, setForm] = useState({ name: '', trigger_type: 'form_submit' });

  useEffect(() => { loadWorkflows(); }, []);
  const loadWorkflows = async () => { try { const r = await crmService.getWorkflows(); setWorkflows(r.data); } catch {} finally { setLoading(false); } };

  const handleCreate = async () => {
    if (!form.name.trim()) return;
    try { await crmService.createWorkflow(form); setShowForm(false); setForm({ name: '', trigger_type: 'form_submit' }); loadWorkflows(); } catch {}
  };

  const handleToggle = async (id: number) => {
    try { await crmService.activateWorkflow(id); loadWorkflows(); } catch {}
  };

  const triggers: Record<string, string> = { form_submit: 'Form Submitted', purchase: 'Purchase Made', tag_added: 'Tag Added', date: 'Date Trigger', webhook: 'Webhook' };

  if (loading) return <div className="min-h-screen bg-[#0a0a0f] flex items-center justify-center"><div className="w-10 h-10 border-2 border-blue-500/30 border-t-blue-500 rounded-full animate-spin" /></div>;

  return (
    <div className="min-h-screen bg-[#0a0a0f] text-white">
      <div className="max-w-6xl mx-auto px-6 py-8">
        <div className="flex items-center justify-between mb-8">
          <div><h1 className="text-2xl font-bold">Automation Workflows</h1><p className="text-sm text-gray-500 mt-1">Automate actions based on triggers</p></div>
          <button onClick={() => setShowForm(!showForm)} className="px-4 py-2 bg-blue-600 hover:bg-blue-500 rounded-xl text-sm font-medium transition">+ New Workflow</button>
        </div>

        {showForm && (
          <div className="bg-[#12121a] border border-[#1e1e2e] rounded-2xl p-6 mb-6 space-y-4">
            <input value={form.name} onChange={e => setForm({...form, name: e.target.value})} placeholder="Workflow name" className="w-full bg-[#0a0a12] border border-[#1e1e2e] rounded-xl px-4 py-3 text-sm text-white outline-none focus:border-blue-500/50" />
            <select value={form.trigger_type} onChange={e => setForm({...form, trigger_type: e.target.value})} className="w-full bg-[#0a0a12] border border-[#1e1e2e] rounded-xl px-4 py-3 text-sm text-white outline-none">
              {Object.entries(triggers).map(([k, v]) => <option key={k} value={k}>{v}</option>)}
            </select>
            <div className="flex gap-3 justify-end">
              <button onClick={() => setShowForm(false)} className="px-4 py-2 text-gray-400 text-sm">Cancel</button>
              <button onClick={handleCreate} className="px-5 py-2 bg-blue-600 hover:bg-blue-500 rounded-xl text-sm font-medium">Create</button>
            </div>
          </div>
        )}

        <div className="space-y-3">
          {workflows.map(w => (
            <div key={w.id} className="bg-[#12121a] border border-[#1e1e2e] rounded-xl p-5 flex items-center justify-between">
              <div>
                <h3 className="text-sm font-medium text-white">{w.name}</h3>
                <p className="text-xs text-gray-500 mt-1">Trigger: {triggers[w.trigger_type] || w.trigger_type} | {w.steps_count} steps</p>
              </div>
              <button onClick={() => handleToggle(w.id)} className={`px-3 py-1.5 rounded-lg text-xs font-medium transition ${w.status === 'active' ? 'bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/20' : 'bg-gray-500/10 text-gray-400 hover:bg-gray-500/20'}`}>
                {w.status === 'active' ? 'Active' : 'Inactive'}
              </button>
            </div>
          ))}
          {workflows.length === 0 && <div className="text-center py-12"><p className="text-gray-600">No workflows yet</p></div>}
        </div>
      </div>
    </div>
  );
}
