export default function SeoHistory() {
  const history = [
    { date: '2026-03-28', event: 'SEO Audit completed', score: 85, change: '+3' },
    { date: '2026-03-25', event: 'Meta descriptions updated (5 pages)', score: 82, change: '+5' },
    { date: '2026-03-20', event: 'Images optimized (12 files)', score: 77, change: '+4' },
    { date: '2026-03-15', event: 'Sitemap generated', score: 73, change: '+2' },
    { date: '2026-03-10', event: 'robots.txt created', score: 71, change: '+1' },
    { date: '2026-03-05', event: 'Initial SEO setup', score: 70, change: '—' },
  ];
  return (
    <div className="p-6">
      <h1 className="text-xl font-bold text-white mb-1">SEO History</h1>
      <p className="text-sm text-gray-500 mb-6">Track your SEO improvements over time</p>
      <div className="space-y-3">
        {history.map((h, i) => (
          <div key={i} className="bg-[#12121a] border border-[#1e1e2e] rounded-xl p-4 flex items-center gap-4">
            <div className="w-1 h-10 rounded-full bg-blue-500" />
            <div className="flex-1"><p className="text-sm text-white">{h.event}</p><p className="text-xs text-gray-500 mt-0.5">{h.date}</p></div>
            <div className="text-right"><p className="text-lg font-bold text-white">{h.score}</p><p className="text-xs text-emerald-400">{h.change}</p></div>
          </div>
        ))}
      </div>
    </div>
  );
}
