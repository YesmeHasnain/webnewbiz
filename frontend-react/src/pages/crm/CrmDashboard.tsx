import { useState, useEffect } from 'react';
import { crmService } from '../../services/crm.service';
import { Link } from 'react-router-dom';

export default function CrmDashboard() {
  const [stats, setStats] = useState({ contacts: 0, deals: 0, conversations: 0, revenue: 0 });
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    Promise.all([
      crmService.getContacts().catch(() => ({ data: { total: 0 } })),
      crmService.getDeals().catch(() => ({ data: [] })),
      crmService.getConversations().catch(() => ({ data: [] })),
      crmService.getInvoices().catch(() => ({ data: [] })),
    ]).then(([contacts, deals, convs, invoices]) => {
      const dealsData = Array.isArray(deals.data) ? deals.data : [];
      const invoiceData = Array.isArray(invoices.data) ? invoices.data : [];
      setStats({
        contacts: contacts.data.total || (Array.isArray(contacts.data) ? contacts.data.length : 0),
        deals: dealsData.length,
        conversations: Array.isArray(convs.data) ? convs.data.length : 0,
        revenue: invoiceData.filter((i: Record<string, unknown>) => i.status === 'paid').reduce((s: number, i: Record<string, unknown>) => s + Number(i.total || 0), 0),
      });
      setLoading(false);
    });
  }, []);

  const cards = [
    { label: 'Total Contacts', value: stats.contacts, color: 'blue', icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z' },
    { label: 'Active Deals', value: stats.deals, color: 'emerald', icon: 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' },
    { label: 'Open Conversations', value: stats.conversations, color: 'purple', icon: 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z' },
    { label: 'Revenue', value: `$${stats.revenue.toLocaleString()}`, color: 'amber', icon: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z' },
  ];

  const navLinks = [
    { to: '/crm/contacts', label: 'Contacts', desc: 'Manage your contact database' },
    { to: '/crm/pipeline', label: 'Pipeline', desc: 'Track deals in Kanban board' },
    { to: '/crm/campaigns', label: 'Campaigns', desc: 'Email marketing campaigns' },
    { to: '/crm/sequences', label: 'Sequences', desc: 'Drip email sequences' },
    { to: '/crm/workflows', label: 'Workflows', desc: 'Automation workflows' },
    { to: '/crm/calendar', label: 'Calendar', desc: 'Bookings & appointments' },
    { to: '/crm/invoices', label: 'Invoices', desc: 'Billing & invoices' },
    { to: '/crm/conversations', label: 'Conversations', desc: 'Unified inbox' },
  ];

  if (loading) return <div className="min-h-screen bg-[#0a0a0f] flex items-center justify-center"><div className="w-10 h-10 border-2 border-blue-500/30 border-t-blue-500 rounded-full animate-spin" /></div>;

  return (
    <div className="min-h-screen bg-[#0a0a0f] text-white">
      <div className="max-w-7xl mx-auto px-6 py-8">
        <h1 className="text-2xl font-bold mb-2">CRM Dashboard</h1>
        <p className="text-sm text-gray-500 mb-8">Manage contacts, deals, campaigns, and more</p>

        {/* Stats */}
        <div className="grid grid-cols-4 gap-4 mb-8">
          {cards.map(c => (
            <div key={c.label} className="bg-[#12121a] border border-[#1e1e2e] rounded-2xl p-5">
              <div className="flex items-center gap-3 mb-3">
                <div className={`w-10 h-10 rounded-xl bg-${c.color}-500/10 flex items-center justify-center`}>
                  <svg className={`w-5 h-5 text-${c.color}-400`} fill="none" stroke="currentColor" strokeWidth={1.5} viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" d={c.icon} /></svg>
                </div>
              </div>
              <p className="text-2xl font-bold">{c.value}</p>
              <p className="text-xs text-gray-500 mt-1">{c.label}</p>
            </div>
          ))}
        </div>

        {/* Quick nav */}
        <h2 className="text-lg font-semibold mb-4">Modules</h2>
        <div className="grid grid-cols-4 gap-4">
          {navLinks.map(link => (
            <Link key={link.to} to={link.to} className="bg-[#12121a] border border-[#1e1e2e] rounded-xl p-5 hover:border-blue-500/30 hover:bg-blue-600/5 transition group">
              <h3 className="text-sm font-medium text-white group-hover:text-blue-400 transition">{link.label}</h3>
              <p className="text-xs text-gray-600 mt-1">{link.desc}</p>
            </Link>
          ))}
        </div>
      </div>
    </div>
  );
}
