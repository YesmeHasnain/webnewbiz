import { useState, useEffect } from 'react';
import { crmService } from '../../services/crm.service';

interface ContactItem { id: number; first_name: string; last_name: string; email: string; phone: string; company: string; status: string; tags: string[]; last_activity_at: string | null; }

export default function Contacts() {
  const [contacts, setContacts] = useState<ContactItem[]>([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');
  const [showForm, setShowForm] = useState(false);
  const [form, setForm] = useState({ first_name: '', last_name: '', email: '', phone: '', company: '' });

  useEffect(() => { loadContacts(); }, []);

  const loadContacts = async (s?: string) => {
    try { const r = await crmService.getContacts(s ? { search: s } : undefined); setContacts(r.data.data || r.data); } catch {} finally { setLoading(false); }
  };

  const handleSearch = (v: string) => { setSearch(v); loadContacts(v); };

  const handleCreate = async () => {
    if (!form.first_name.trim()) return;
    try { await crmService.createContact(form); setShowForm(false); setForm({ first_name: '', last_name: '', email: '', phone: '', company: '' }); loadContacts(); } catch {}
  };

  const handleDelete = async (id: number) => {
    if (!confirm('Delete this contact?')) return;
    try { await crmService.deleteContact(id); loadContacts(); } catch {}
  };

  if (loading) return <div className="min-h-screen bg-[#0a0a0f] flex items-center justify-center"><div className="w-10 h-10 border-2 border-blue-500/30 border-t-blue-500 rounded-full animate-spin" /></div>;

  return (
    <div className="min-h-screen bg-[#0a0a0f] text-white">
      <div className="max-w-7xl mx-auto px-6 py-8">
        <div className="flex items-center justify-between mb-6">
          <div><h1 className="text-2xl font-bold">Contacts</h1><p className="text-sm text-gray-500 mt-1">{contacts.length} total contacts</p></div>
          <button onClick={() => setShowForm(!showForm)} className="px-4 py-2 bg-blue-600 hover:bg-blue-500 rounded-xl text-sm font-medium transition">+ Add Contact</button>
        </div>

        {/* Search */}
        <div className="mb-6">
          <input value={search} onChange={e => handleSearch(e.target.value)} placeholder="Search contacts..." className="w-full max-w-md bg-[#12121a] border border-[#1e1e2e] rounded-xl px-4 py-2.5 text-sm text-white outline-none focus:border-blue-500/50 placeholder-gray-600" />
        </div>

        {/* Create form */}
        {showForm && (
          <div className="bg-[#12121a] border border-[#1e1e2e] rounded-2xl p-6 mb-6 grid grid-cols-2 gap-4">
            <input value={form.first_name} onChange={e => setForm({...form, first_name: e.target.value})} placeholder="First name *" className="bg-[#0a0a12] border border-[#1e1e2e] rounded-xl px-4 py-3 text-sm text-white outline-none focus:border-blue-500/50" />
            <input value={form.last_name} onChange={e => setForm({...form, last_name: e.target.value})} placeholder="Last name" className="bg-[#0a0a12] border border-[#1e1e2e] rounded-xl px-4 py-3 text-sm text-white outline-none focus:border-blue-500/50" />
            <input value={form.email} onChange={e => setForm({...form, email: e.target.value})} placeholder="Email" className="bg-[#0a0a12] border border-[#1e1e2e] rounded-xl px-4 py-3 text-sm text-white outline-none focus:border-blue-500/50" />
            <input value={form.phone} onChange={e => setForm({...form, phone: e.target.value})} placeholder="Phone" className="bg-[#0a0a12] border border-[#1e1e2e] rounded-xl px-4 py-3 text-sm text-white outline-none focus:border-blue-500/50" />
            <input value={form.company} onChange={e => setForm({...form, company: e.target.value})} placeholder="Company" className="bg-[#0a0a12] border border-[#1e1e2e] rounded-xl px-4 py-3 text-sm text-white outline-none focus:border-blue-500/50 col-span-2" />
            <div className="col-span-2 flex gap-3 justify-end">
              <button onClick={() => setShowForm(false)} className="px-4 py-2 text-gray-400 text-sm">Cancel</button>
              <button onClick={handleCreate} className="px-5 py-2 bg-blue-600 hover:bg-blue-500 rounded-xl text-sm font-medium">Save Contact</button>
            </div>
          </div>
        )}

        {/* Table */}
        <div className="bg-[#12121a] border border-[#1e1e2e] rounded-2xl overflow-hidden">
          <table className="w-full">
            <thead><tr className="border-b border-[#1e1e2e]">
              <th className="text-left text-xs text-gray-500 font-medium px-5 py-3">Name</th>
              <th className="text-left text-xs text-gray-500 font-medium px-5 py-3">Email</th>
              <th className="text-left text-xs text-gray-500 font-medium px-5 py-3">Phone</th>
              <th className="text-left text-xs text-gray-500 font-medium px-5 py-3">Company</th>
              <th className="text-left text-xs text-gray-500 font-medium px-5 py-3">Status</th>
              <th className="text-right text-xs text-gray-500 font-medium px-5 py-3">Actions</th>
            </tr></thead>
            <tbody>
              {contacts.map(c => (
                <tr key={c.id} className="border-b border-[#1e1e2e]/50 hover:bg-white/[0.02]">
                  <td className="px-5 py-3"><div className="flex items-center gap-3"><div className="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-xs font-bold">{c.first_name[0]}</div><span className="text-sm text-white">{c.first_name} {c.last_name}</span></div></td>
                  <td className="px-5 py-3 text-sm text-gray-400">{c.email || '—'}</td>
                  <td className="px-5 py-3 text-sm text-gray-400">{c.phone || '—'}</td>
                  <td className="px-5 py-3 text-sm text-gray-400">{c.company || '—'}</td>
                  <td className="px-5 py-3"><span className={`px-2 py-1 rounded-lg text-xs ${c.status === 'active' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-gray-500/10 text-gray-400'}`}>{c.status}</span></td>
                  <td className="px-5 py-3 text-right"><button onClick={() => handleDelete(c.id)} className="text-xs text-red-400 hover:text-red-300">Delete</button></td>
                </tr>
              ))}
            </tbody>
          </table>
          {contacts.length === 0 && <div className="text-center py-12"><p className="text-gray-600">No contacts yet. Add your first contact!</p></div>}
        </div>
      </div>
    </div>
  );
}
