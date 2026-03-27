export default function EcomEmails() {
  const templates = [
    { id: 1, name: 'Order Confirmation', status: 'active', lastSent: '2026-03-25' },
    { id: 2, name: 'Shipping Notification', status: 'active', lastSent: '2026-03-24' },
    { id: 3, name: 'Abandoned Cart Reminder', status: 'inactive', lastSent: null },
    { id: 4, name: 'Welcome Email', status: 'active', lastSent: '2026-03-20' },
    { id: 5, name: 'Review Request', status: 'active', lastSent: '2026-03-22' },
  ];

  return (
    <div className="p-6">
      <h1 className="text-xl font-bold text-white mb-1">Email Templates</h1>
      <p className="text-sm text-gray-500 mb-6">Manage WooCommerce email notifications</p>
      <div className="space-y-3">
        {templates.map(t => (
          <div key={t.id} className="bg-[#12121a] border border-[#1e1e2e] rounded-xl p-4 flex items-center justify-between hover:border-blue-500/20 transition">
            <div><p className="text-sm text-white font-medium">{t.name}</p>{t.lastSent && <p className="text-xs text-gray-500 mt-1">Last sent: {t.lastSent}</p>}</div>
            <div className="flex items-center gap-3">
              <span className={`px-2.5 py-1 rounded-lg text-xs ${t.status === 'active' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-gray-500/10 text-gray-400'}`}>{t.status}</span>
              <button className="px-3 py-1.5 bg-white/5 hover:bg-white/10 rounded-lg text-xs text-gray-400 hover:text-white transition">Edit</button>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
