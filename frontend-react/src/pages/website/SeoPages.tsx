export default function SeoPages() {
  const pages = [
    { url: '/', title: 'Home', score: 92, indexed: true, keywords: 5 },
    { url: '/about', title: 'About Us', score: 78, indexed: true, keywords: 3 },
    { url: '/services', title: 'Services', score: 85, indexed: true, keywords: 7 },
    { url: '/contact', title: 'Contact', score: 65, indexed: false, keywords: 1 },
    { url: '/blog', title: 'Blog', score: 88, indexed: true, keywords: 12 },
  ];
  const getColor = (s: number) => s >= 90 ? 'emerald' : s >= 70 ? 'amber' : 'red';
  return (
    <div className="p-6">
      <h1 className="text-xl font-bold text-white mb-1">SEO by Page</h1>
      <p className="text-sm text-gray-500 mb-6">Per-page SEO analysis and scores</p>
      <div className="bg-[#12121a] border border-[#1e1e2e] rounded-2xl overflow-hidden">
        <table className="w-full">
          <thead><tr className="border-b border-[#1e1e2e]">
            <th className="text-left text-xs text-gray-500 font-medium px-5 py-3">Page</th>
            <th className="text-left text-xs text-gray-500 font-medium px-5 py-3">URL</th>
            <th className="text-left text-xs text-gray-500 font-medium px-5 py-3">SEO Score</th>
            <th className="text-left text-xs text-gray-500 font-medium px-5 py-3">Indexed</th>
            <th className="text-left text-xs text-gray-500 font-medium px-5 py-3">Keywords</th>
          </tr></thead>
          <tbody>{pages.map(p => (
            <tr key={p.url} className="border-b border-[#1e1e2e]/50 hover:bg-white/[0.02]">
              <td className="px-5 py-3 text-sm text-white font-medium">{p.title}</td>
              <td className="px-5 py-3 text-sm text-gray-500 font-mono">{p.url}</td>
              <td className="px-5 py-3"><span className={`text-sm font-bold text-${getColor(p.score)}-400`}>{p.score}</span></td>
              <td className="px-5 py-3">{p.indexed ? <span className="text-emerald-400 text-xs">Yes</span> : <span className="text-red-400 text-xs">No</span>}</td>
              <td className="px-5 py-3 text-sm text-gray-400">{p.keywords}</td>
            </tr>
          ))}</tbody>
        </table>
      </div>
    </div>
  );
}
