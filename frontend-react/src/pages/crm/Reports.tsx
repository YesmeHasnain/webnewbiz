import { useState } from 'react';

type ReportType = 'revenue' | 'pipeline' | 'campaigns' | 'contacts';

export default function Reports() {
  const [activeReport, setActiveReport] = useState<ReportType>('revenue');
  const [period, setPeriod] = useState('30d');

  const reports: Record<ReportType, { title: string; metrics: Array<{ label: string; value: string; change: string }> }> = {
    revenue: {
      title: 'Revenue Report',
      metrics: [
        { label: 'Total Revenue', value: '$12,450', change: '+12.5%' },
        { label: 'Invoices Paid', value: '24', change: '+8' },
        { label: 'Average Deal Size', value: '$518', change: '+$42' },
        { label: 'Outstanding', value: '$3,200', change: '-15%' },
      ],
    },
    pipeline: {
      title: 'Pipeline Report',
      metrics: [
        { label: 'Total Deals', value: '47', change: '+5' },
        { label: 'Won Deals', value: '12', change: '+3' },
        { label: 'Win Rate', value: '34%', change: '+2%' },
        { label: 'Pipeline Value', value: '$89,500', change: '+$12K' },
      ],
    },
    campaigns: {
      title: 'Campaign Report',
      metrics: [
        { label: 'Emails Sent', value: '2,450', change: '+340' },
        { label: 'Open Rate', value: '24.5%', change: '+1.2%' },
        { label: 'Click Rate', value: '3.8%', change: '+0.5%' },
        { label: 'Unsubscribes', value: '12', change: '-3' },
      ],
    },
    contacts: {
      title: 'Contact Report',
      metrics: [
        { label: 'Total Contacts', value: '1,234', change: '+56' },
        { label: 'New This Month', value: '89', change: '+12' },
        { label: 'Active', value: '956', change: '+34' },
        { label: 'Lifetime Value', value: '$45,200', change: '+$3.2K' },
      ],
    },
  };

  const report = reports[activeReport];

  return (
    <div className="min-h-screen bg-[#0a0a0f] text-white">
      <div className="max-w-6xl mx-auto px-6 py-8">
        <div className="flex items-center justify-between mb-8">
          <div><h1 className="text-2xl font-bold">CRM Reports</h1><p className="text-sm text-gray-500 mt-1">Analytics and insights across your CRM</p></div>
          <div className="flex items-center gap-2">
            {['7d', '30d', '90d', 'all'].map(p => (
              <button key={p} onClick={() => setPeriod(p)} className={`px-3 py-1.5 rounded-lg text-xs font-medium transition ${period === p ? 'bg-blue-600 text-white' : 'bg-white/5 text-gray-400 hover:text-white'}`}>{p === 'all' ? 'All Time' : p}</button>
            ))}
            <button className="ml-2 px-4 py-1.5 bg-white/5 hover:bg-white/10 rounded-lg text-xs text-gray-400 transition">Export PDF</button>
          </div>
        </div>

        {/* Report tabs */}
        <div className="flex gap-2 mb-6">
          {(Object.keys(reports) as ReportType[]).map(key => (
            <button key={key} onClick={() => setActiveReport(key)} className={`px-4 py-2 rounded-xl text-sm font-medium transition ${activeReport === key ? 'bg-blue-600/15 text-blue-400 border border-blue-500/30' : 'bg-white/5 text-gray-400 hover:text-white border border-transparent'}`}>
              {reports[key].title.replace(' Report', '')}
            </button>
          ))}
        </div>

        {/* Metrics */}
        <div className="grid grid-cols-4 gap-4 mb-8">
          {report.metrics.map(m => (
            <div key={m.label} className="bg-[#12121a] border border-[#1e1e2e] rounded-xl p-5">
              <p className="text-xs text-gray-500">{m.label}</p>
              <p className="text-2xl font-bold mt-1">{m.value}</p>
              <p className={`text-xs mt-1 ${m.change.startsWith('+') ? 'text-emerald-400' : m.change.startsWith('-') ? 'text-red-400' : 'text-gray-500'}`}>{m.change} vs previous period</p>
            </div>
          ))}
        </div>

        {/* Chart placeholder */}
        <div className="bg-[#12121a] border border-[#1e1e2e] rounded-2xl p-6 mb-6">
          <h3 className="text-sm font-medium text-gray-300 mb-4">{report.title} — Trend</h3>
          <div className="h-64 flex items-end gap-1 px-4">
            {Array.from({ length: 30 }, (_, i) => {
              const h = 20 + Math.random() * 80;
              return <div key={i} className="flex-1 bg-blue-600/30 hover:bg-blue-600/50 rounded-t transition" style={{ height: `${h}%` }} />;
            })}
          </div>
          <div className="flex justify-between mt-2 px-4"><span className="text-xs text-gray-600">30 days ago</span><span className="text-xs text-gray-600">Today</span></div>
        </div>
      </div>
    </div>
  );
}
