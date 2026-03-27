export default function BoosterPages() {
  const pages = [
    { url: '/', name: 'Home', loadTime: '1.2s', size: '245 KB', score: 94, status: 'optimized' },
    { url: '/about', name: 'About', loadTime: '1.8s', size: '312 KB', score: 82, status: 'needs work' },
    { url: '/services', name: 'Services', loadTime: '2.1s', size: '487 KB', score: 71, status: 'needs work' },
    { url: '/contact', name: 'Contact', loadTime: '0.9s', size: '156 KB', score: 97, status: 'optimized' },
    { url: '/blog', name: 'Blog', loadTime: '1.5s', size: '389 KB', score: 85, status: 'good' },
  ];
  const getColor = (s: number) => s >= 90 ? 'emerald' : s >= 75 ? 'amber' : 'red';
  return (
    <div className="p-6">
      <h1 className="text-xl font-bold text-white mb-1">Page Performance</h1>
      <p className="text-sm text-gray-500 mb-6">Individual page load times and optimization status</p>
      <div className="bg-[#12121a] border border-[#1e1e2e] rounded-2xl overflow-hidden">
        <table className="w-full">
          <thead><tr className="border-b border-[#1e1e2e]">
            <th className="text-left text-xs text-gray-500 font-medium px-5 py-3">Page</th>
            <th className="text-left text-xs text-gray-500 font-medium px-5 py-3">Load Time</th>
            <th className="text-left text-xs text-gray-500 font-medium px-5 py-3">Size</th>
            <th className="text-left text-xs text-gray-500 font-medium px-5 py-3">Score</th>
            <th className="text-left text-xs text-gray-500 font-medium px-5 py-3">Status</th>
            <th className="text-right text-xs text-gray-500 font-medium px-5 py-3">Action</th>
          </tr></thead>
          <tbody>{pages.map(p => (
            <tr key={p.url} className="border-b border-[#1e1e2e]/50 hover:bg-white/[0.02]">
              <td className="px-5 py-3"><p className="text-sm text-white">{p.name}</p><p className="text-xs text-gray-600 font-mono">{p.url}</p></td>
              <td className="px-5 py-3 text-sm text-gray-300">{p.loadTime}</td>
              <td className="px-5 py-3 text-sm text-gray-400">{p.size}</td>
              <td className="px-5 py-3"><span className={`text-sm font-bold text-${getColor(p.score)}-400`}>{p.score}</span></td>
              <td className="px-5 py-3"><span className={`px-2 py-1 rounded-lg text-xs ${p.status === 'optimized' ? 'bg-emerald-500/10 text-emerald-400' : p.status === 'good' ? 'bg-blue-500/10 text-blue-400' : 'bg-amber-500/10 text-amber-400'}`}>{p.status}</span></td>
              <td className="px-5 py-3 text-right"><button className="text-xs text-blue-400 hover:text-blue-300">Optimize</button></td>
            </tr>
          ))}</tbody>
        </table>
      </div>
    </div>
  );
}
