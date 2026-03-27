import { useState, useEffect } from 'react';
import { crmService } from '../../services/crm.service';

interface DealItem { id: number; title: string; value: number; stage: string; probability: number; status: string; contact: { first_name: string; last_name: string } | null; }

const DEFAULT_STAGES = ['Lead', 'Qualified', 'Proposal', 'Negotiation', 'Won', 'Lost'];
const stageColors: Record<string, string> = { Lead: 'blue', Qualified: 'cyan', Proposal: 'purple', Negotiation: 'amber', Won: 'emerald', Lost: 'red' };

export default function Pipeline() {
  const [deals, setDeals] = useState<DealItem[]>([]);
  const [loading, setLoading] = useState(true);
  const [showForm, setShowForm] = useState(false);
  const [form, setForm] = useState({ title: '', value: '', stage: 'Lead', pipeline_id: 0 });

  useEffect(() => { loadDeals(); }, []);

  const loadDeals = async () => {
    try {
      const [dealsRes, pipelinesRes] = await Promise.all([crmService.getDeals(), crmService.getPipelines()]);
      setDeals(dealsRes.data);
      if (pipelinesRes.data.length > 0) setForm(f => ({ ...f, pipeline_id: pipelinesRes.data[0].id }));
    } catch {} finally { setLoading(false); }
  };

  const handleCreate = async () => {
    if (!form.title.trim()) return;
    try {
      if (!form.pipeline_id) {
        const pRes = await crmService.createPipeline({ name: 'Sales Pipeline', stages: DEFAULT_STAGES });
        form.pipeline_id = pRes.data.id;
      }
      await crmService.createDeal({ ...form, value: Number(form.value) || 0 });
      setShowForm(false); setForm(f => ({ ...f, title: '', value: '', stage: 'Lead' })); loadDeals();
    } catch {}
  };

  const handleStageChange = async (dealId: number, newStage: string) => {
    try { await crmService.updateDealStage(dealId, newStage); loadDeals(); } catch {}
  };

  if (loading) return <div className="min-h-screen bg-[#0a0a0f] flex items-center justify-center"><div className="w-10 h-10 border-2 border-blue-500/30 border-t-blue-500 rounded-full animate-spin" /></div>;

  return (
    <div className="min-h-screen bg-[#0a0a0f] text-white">
      <div className="px-6 py-8">
        <div className="flex items-center justify-between mb-6">
          <div><h1 className="text-2xl font-bold">Sales Pipeline</h1><p className="text-sm text-gray-500 mt-1">{deals.length} deals</p></div>
          <button onClick={() => setShowForm(!showForm)} className="px-4 py-2 bg-blue-600 hover:bg-blue-500 rounded-xl text-sm font-medium transition">+ Add Deal</button>
        </div>

        {showForm && (
          <div className="bg-[#12121a] border border-[#1e1e2e] rounded-2xl p-6 mb-6 flex gap-4 items-end">
            <div className="flex-1"><label className="text-xs text-gray-500 block mb-1">Deal Title</label><input value={form.title} onChange={e => setForm({...form, title: e.target.value})} placeholder="New deal..." className="w-full bg-[#0a0a12] border border-[#1e1e2e] rounded-xl px-4 py-2.5 text-sm text-white outline-none focus:border-blue-500/50" /></div>
            <div className="w-32"><label className="text-xs text-gray-500 block mb-1">Value ($)</label><input value={form.value} onChange={e => setForm({...form, value: e.target.value})} placeholder="0" type="number" className="w-full bg-[#0a0a12] border border-[#1e1e2e] rounded-xl px-4 py-2.5 text-sm text-white outline-none focus:border-blue-500/50" /></div>
            <div className="w-40"><label className="text-xs text-gray-500 block mb-1">Stage</label><select value={form.stage} onChange={e => setForm({...form, stage: e.target.value})} className="w-full bg-[#0a0a12] border border-[#1e1e2e] rounded-xl px-4 py-2.5 text-sm text-white outline-none">{DEFAULT_STAGES.map(s => <option key={s}>{s}</option>)}</select></div>
            <button onClick={handleCreate} className="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 rounded-xl text-sm font-medium whitespace-nowrap">Create</button>
          </div>
        )}

        {/* Kanban Board */}
        <div className="flex gap-4 overflow-x-auto pb-4">
          {DEFAULT_STAGES.map(stage => {
            const stageDeals = deals.filter(d => d.stage === stage);
            const color = stageColors[stage] || 'gray';
            const totalValue = stageDeals.reduce((s, d) => s + Number(d.value), 0);

            return (
              <div key={stage} className="flex-shrink-0 w-72">
                <div className="flex items-center justify-between mb-3 px-1">
                  <div className="flex items-center gap-2">
                    <div className={`w-2 h-2 rounded-full bg-${color}-500`} />
                    <span className="text-sm font-medium text-gray-300">{stage}</span>
                    <span className="text-xs text-gray-600">{stageDeals.length}</span>
                  </div>
                  <span className="text-xs text-gray-600">${totalValue.toLocaleString()}</span>
                </div>

                <div className="space-y-2 min-h-[200px]">
                  {stageDeals.map(deal => (
                    <div key={deal.id} className="bg-[#12121a] border border-[#1e1e2e] rounded-xl p-4 hover:border-blue-500/30 transition cursor-pointer">
                      <h4 className="text-sm font-medium text-white mb-1">{deal.title}</h4>
                      {deal.contact && <p className="text-xs text-gray-500">{deal.contact.first_name} {deal.contact.last_name}</p>}
                      <div className="flex items-center justify-between mt-3">
                        <span className="text-sm font-medium text-emerald-400">${Number(deal.value).toLocaleString()}</span>
                        <span className="text-xs text-gray-600">{deal.probability}%</span>
                      </div>
                      {/* Stage move buttons */}
                      <div className="flex gap-1 mt-3">
                        {DEFAULT_STAGES.filter(s => s !== stage).slice(0, 3).map(s => (
                          <button key={s} onClick={() => handleStageChange(deal.id, s)} className="px-2 py-1 bg-white/5 hover:bg-white/10 rounded text-[10px] text-gray-500 hover:text-white transition">{s}</button>
                        ))}
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            );
          })}
        </div>
      </div>
    </div>
  );
}
