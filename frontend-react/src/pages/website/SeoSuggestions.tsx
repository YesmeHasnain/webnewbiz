export default function SeoSuggestions() {
  const suggestions = [
    { priority: 'high', title: 'Add meta descriptions', desc: 'Pages without meta descriptions may not rank well in search results', pages: 3, action: 'Fix Now' },
    { priority: 'high', title: 'Optimize images', desc: 'Large images slow down page load times and hurt rankings', pages: 8, action: 'Optimize' },
    { priority: 'medium', title: 'Add internal links', desc: 'Connect related pages to improve crawlability', pages: 5, action: 'Review' },
    { priority: 'medium', title: 'Improve heading structure', desc: 'Use proper H1-H6 hierarchy for better content structure', pages: 2, action: 'Fix' },
    { priority: 'low', title: 'Add structured data', desc: 'Add JSON-LD schema markup for rich search results', pages: 0, action: 'Add' },
    { priority: 'low', title: 'Create XML sitemap', desc: 'Help search engines discover all your pages', pages: 0, action: 'Generate' },
  ];
  const colors: Record<string, string> = { high: 'red', medium: 'amber', low: 'blue' };
  return (
    <div className="p-6">
      <h1 className="text-xl font-bold text-white mb-1">SEO Suggestions</h1>
      <p className="text-sm text-gray-500 mb-6">AI-powered recommendations to improve your rankings</p>
      <div className="space-y-3">
        {suggestions.map((s, i) => (
          <div key={i} className="bg-[#12121a] border border-[#1e1e2e] rounded-xl p-5 flex items-center justify-between">
            <div className="flex items-center gap-4">
              <span className={`px-2 py-1 rounded-lg text-xs font-medium bg-${colors[s.priority]}-500/10 text-${colors[s.priority]}-400 uppercase`}>{s.priority}</span>
              <div><p className="text-sm text-white font-medium">{s.title}</p><p className="text-xs text-gray-500 mt-0.5">{s.desc}{s.pages > 0 && ` — ${s.pages} pages affected`}</p></div>
            </div>
            <button className="px-4 py-2 bg-white/5 hover:bg-white/10 rounded-lg text-xs text-gray-300 hover:text-white transition">{s.action}</button>
          </div>
        ))}
      </div>
    </div>
  );
}
