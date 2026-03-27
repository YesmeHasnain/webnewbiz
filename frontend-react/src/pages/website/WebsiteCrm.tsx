import { Link, useParams } from 'react-router-dom';

// Website-level CRM pages (these are sub-pages under /websites/:id/crm/*)
export function CrmDashboardSub() {
  const { id } = useParams();
  const stats = [
    { label: 'Leads', value: '24', icon: '👤', to: `/websites/${id}/crm/leads` },
    { label: 'Subscribers', value: '156', icon: '📧', to: `/websites/${id}/crm/subscribers` },
    { label: 'Bookings', value: '8', icon: '📅', to: `/websites/${id}/crm/bookings` },
    { label: 'Cart Recovery', value: '12', icon: '🛒', to: `/websites/${id}/crm/abandoned-carts` },
  ];
  return (
    <div className="p-6">
      <h1 className="text-xl font-bold text-white mb-1">Website CRM</h1>
      <p className="text-sm text-gray-500 mb-6">Manage leads and customers from this website</p>
      <div className="grid grid-cols-2 gap-4">
        {stats.map(s => (
          <Link key={s.label} to={s.to} className="bg-[#12121a] border border-[#1e1e2e] rounded-xl p-5 hover:border-blue-500/30 transition">
            <span className="text-2xl">{s.icon}</span>
            <p className="text-2xl font-bold text-white mt-2">{s.value}</p>
            <p className="text-xs text-gray-500">{s.label}</p>
          </Link>
        ))}
      </div>
    </div>
  );
}

export function CrmLeads() {
  const leads = [
    { name: 'Ahmed Khan', email: 'ahmed@mail.com', source: 'Contact Form', date: '2026-03-27' },
    { name: 'Sara Ali', email: 'sara@mail.com', source: 'Newsletter', date: '2026-03-26' },
    { name: 'Bilal Raza', email: 'bilal@mail.com', source: 'Popup', date: '2026-03-25' },
  ];
  return (
    <div className="p-6">
      <h1 className="text-xl font-bold text-white mb-6">Leads</h1>
      <div className="bg-[#12121a] border border-[#1e1e2e] rounded-2xl overflow-hidden">
        <table className="w-full">
          <thead><tr className="border-b border-[#1e1e2e]"><th className="text-left text-xs text-gray-500 px-5 py-3">Name</th><th className="text-left text-xs text-gray-500 px-5 py-3">Email</th><th className="text-left text-xs text-gray-500 px-5 py-3">Source</th><th className="text-left text-xs text-gray-500 px-5 py-3">Date</th></tr></thead>
          <tbody>{leads.map((l, i) => <tr key={i} className="border-b border-[#1e1e2e]/50"><td className="px-5 py-3 text-sm text-white">{l.name}</td><td className="px-5 py-3 text-sm text-gray-400">{l.email}</td><td className="px-5 py-3 text-sm text-gray-500">{l.source}</td><td className="px-5 py-3 text-sm text-gray-500">{l.date}</td></tr>)}</tbody>
        </table>
      </div>
    </div>
  );
}

export function CrmSubscribers() {
  return (
    <div className="p-6">
      <h1 className="text-xl font-bold text-white mb-1">Subscribers</h1>
      <p className="text-sm text-gray-500 mb-6">Email newsletter subscribers from this website</p>
      <div className="bg-[#12121a] border border-[#1e1e2e] rounded-xl p-8 text-center">
        <p className="text-3xl font-bold text-white mb-1">156</p>
        <p className="text-sm text-gray-500">Total subscribers</p>
        <button className="mt-4 px-5 py-2 bg-blue-600 rounded-xl text-sm font-medium text-white">Export CSV</button>
      </div>
    </div>
  );
}

export function CrmBookingsSub() {
  return (
    <div className="p-6">
      <h1 className="text-xl font-bold text-white mb-1">Bookings</h1>
      <p className="text-sm text-gray-500 mb-6">Appointments booked through this website</p>
      <div className="bg-[#12121a] border border-[#1e1e2e] rounded-xl p-8 text-center">
        <p className="text-gray-500">No bookings from this website yet</p>
        <p className="text-xs text-gray-600 mt-2">Add a booking widget to your website to start collecting appointments</p>
      </div>
    </div>
  );
}

export function CrmAbandonedCarts() {
  const carts = [
    { customer: 'Guest User', items: 3, value: '$89.99', abandoned: '2 hours ago' },
    { customer: 'john@mail.com', items: 1, value: '$29.99', abandoned: '5 hours ago' },
  ];
  return (
    <div className="p-6">
      <h1 className="text-xl font-bold text-white mb-1">Abandoned Carts</h1>
      <p className="text-sm text-gray-500 mb-6">Recover lost sales with automated reminders</p>
      <div className="space-y-3">
        {carts.map((c, i) => (
          <div key={i} className="bg-[#12121a] border border-[#1e1e2e] rounded-xl p-4 flex items-center justify-between">
            <div><p className="text-sm text-white">{c.customer}</p><p className="text-xs text-gray-500">{c.items} items — {c.value} — {c.abandoned}</p></div>
            <button className="px-3 py-1.5 bg-blue-600 rounded-lg text-xs text-white">Send Reminder</button>
          </div>
        ))}
      </div>
    </div>
  );
}

export function CrmChatbot() {
  return (
    <div className="p-6">
      <h1 className="text-xl font-bold text-white mb-1">AI Chatbot</h1>
      <p className="text-sm text-gray-500 mb-6">Configure AI-powered chatbot for your website</p>
      <div className="bg-[#12121a] border border-[#1e1e2e] rounded-xl p-6 max-w-xl space-y-4">
        <div className="flex items-center justify-between"><span className="text-sm text-gray-300">Enable Chatbot</span><div className="w-10 h-5 bg-blue-600 rounded-full"><div className="w-4 h-4 bg-white rounded-full translate-x-5 mt-0.5" /></div></div>
        <div><label className="text-xs text-gray-500 block mb-1.5">Welcome Message</label><input defaultValue="Hi! How can I help you today?" className="w-full bg-[#0a0a12] border border-[#1e1e2e] rounded-xl px-4 py-3 text-sm text-white outline-none" /></div>
        <div><label className="text-xs text-gray-500 block mb-1.5">AI Personality</label><select className="w-full bg-[#0a0a12] border border-[#1e1e2e] rounded-xl px-4 py-3 text-sm text-white outline-none"><option>Professional</option><option>Friendly</option><option>Casual</option></select></div>
        <div><label className="text-xs text-gray-500 block mb-1.5">Business Context</label><textarea rows={3} placeholder="Tell the AI about your business..." className="w-full bg-[#0a0a12] border border-[#1e1e2e] rounded-xl px-4 py-3 text-sm text-white outline-none resize-none" /></div>
        <button className="px-6 py-2.5 bg-blue-600 rounded-xl text-sm font-medium text-white">Save Settings</button>
      </div>
    </div>
  );
}

export function CrmCampaignsSub() {
  return (
    <div className="p-6">
      <h1 className="text-xl font-bold text-white mb-1">Email Campaigns</h1>
      <p className="text-sm text-gray-500 mb-6">Send email campaigns to this website's subscribers</p>
      <div className="bg-[#12121a] border border-[#1e1e2e] rounded-xl p-8 text-center">
        <p className="text-gray-500">Create a campaign from the main CRM module</p>
        <Link to="/crm/campaigns" className="inline-block mt-3 px-5 py-2 bg-blue-600 rounded-xl text-sm font-medium text-white">Go to Campaigns</Link>
      </div>
    </div>
  );
}
