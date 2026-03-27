import { useState, useEffect } from 'react';
import { crmService } from '../../services/crm.service';

interface Invoice { id: number; number: string; status: string; total: number; subtotal: number; tax: number; due_date: string | null; paid_at: string | null; contact: { first_name: string; last_name: string } | null; created_at: string; }

export default function Invoices() {
  const [invoices, setInvoices] = useState<Invoice[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => { loadInvoices(); }, []);
  const loadInvoices = async () => { try { const r = await crmService.getInvoices(); setInvoices(r.data); } catch {} finally { setLoading(false); } };

  const handleMarkPaid = async (id: number) => {
    try { await crmService.markInvoicePaid(id); loadInvoices(); } catch {}
  };

  const total = invoices.reduce((sum, i) => sum + Number(i.total), 0);
  const paid = invoices.filter(i => i.status === 'paid').reduce((sum, i) => sum + Number(i.total), 0);

  if (loading) return <div className="min-h-screen bg-[#0a0a0f] flex items-center justify-center"><div className="w-10 h-10 border-2 border-blue-500/30 border-t-blue-500 rounded-full animate-spin" /></div>;

  return (
    <div className="min-h-screen bg-[#0a0a0f] text-white">
      <div className="max-w-6xl mx-auto px-6 py-8">
        <h1 className="text-2xl font-bold mb-2">Invoices</h1>
        <p className="text-sm text-gray-500 mb-8">Manage your billing and invoices</p>

        <div className="grid grid-cols-3 gap-4 mb-8">
          <div className="bg-[#12121a] border border-[#1e1e2e] rounded-xl p-5">
            <p className="text-xs text-gray-500">Total Invoiced</p>
            <p className="text-2xl font-bold mt-1">${total.toLocaleString()}</p>
          </div>
          <div className="bg-[#12121a] border border-[#1e1e2e] rounded-xl p-5">
            <p className="text-xs text-gray-500">Paid</p>
            <p className="text-2xl font-bold text-emerald-400 mt-1">${paid.toLocaleString()}</p>
          </div>
          <div className="bg-[#12121a] border border-[#1e1e2e] rounded-xl p-5">
            <p className="text-xs text-gray-500">Outstanding</p>
            <p className="text-2xl font-bold text-amber-400 mt-1">${(total - paid).toLocaleString()}</p>
          </div>
        </div>

        <div className="bg-[#12121a] border border-[#1e1e2e] rounded-2xl overflow-hidden">
          <table className="w-full">
            <thead><tr className="border-b border-[#1e1e2e]">
              <th className="text-left text-xs text-gray-500 font-medium px-5 py-3">Invoice #</th>
              <th className="text-left text-xs text-gray-500 font-medium px-5 py-3">Contact</th>
              <th className="text-left text-xs text-gray-500 font-medium px-5 py-3">Amount</th>
              <th className="text-left text-xs text-gray-500 font-medium px-5 py-3">Due Date</th>
              <th className="text-left text-xs text-gray-500 font-medium px-5 py-3">Status</th>
              <th className="text-right text-xs text-gray-500 font-medium px-5 py-3">Action</th>
            </tr></thead>
            <tbody>
              {invoices.map(inv => (
                <tr key={inv.id} className="border-b border-[#1e1e2e]/50 hover:bg-white/[0.02]">
                  <td className="px-5 py-3 text-sm text-blue-400">{inv.number}</td>
                  <td className="px-5 py-3 text-sm text-gray-300">{inv.contact ? `${inv.contact.first_name} ${inv.contact.last_name}` : '—'}</td>
                  <td className="px-5 py-3 text-sm text-white font-medium">${Number(inv.total).toLocaleString()}</td>
                  <td className="px-5 py-3 text-sm text-gray-400">{inv.due_date || '—'}</td>
                  <td className="px-5 py-3"><span className={`px-2 py-1 rounded-lg text-xs ${inv.status === 'paid' ? 'bg-emerald-500/10 text-emerald-400' : inv.status === 'draft' ? 'bg-gray-500/10 text-gray-400' : 'bg-amber-500/10 text-amber-400'}`}>{inv.status}</span></td>
                  <td className="px-5 py-3 text-right">{inv.status !== 'paid' && <button onClick={() => handleMarkPaid(inv.id)} className="text-xs text-emerald-400 hover:text-emerald-300">Mark Paid</button>}</td>
                </tr>
              ))}
            </tbody>
          </table>
          {invoices.length === 0 && <div className="text-center py-12"><p className="text-gray-600">No invoices yet</p></div>}
        </div>
      </div>
    </div>
  );
}
