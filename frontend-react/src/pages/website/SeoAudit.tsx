import { useState } from 'react';

export default function SeoAudit() {
  const [running, setRunning] = useState(false);
  const [results, setResults] = useState<Array<{category: string; score: number; issues: string[]}> | null>(null);

  const runAudit = () => {
    setRunning(true);
    setTimeout(() => {
      setResults([
        { category: 'Meta Tags', score: 85, issues: ['Missing meta description on 2 pages', 'Title too long on About page'] },
        { category: 'Performance', score: 72, issues: ['Large images not optimized', 'No lazy loading detected', 'CSS not minified'] },
        { category: 'Accessibility', score: 90, issues: ['3 images missing alt text'] },
        { category: 'Best Practices', score: 88, issues: ['No robots.txt found', 'HTTP links on HTTPS page'] },
        { category: 'Content', score: 95, issues: ['Thin content on Contact page'] },
      ]);
      setRunning(false);
    }, 2000);
  };

  const getColor = (s: number) => s >= 90 ? 'emerald' : s >= 70 ? 'amber' : 'red';

  return (
    <div className="p-6">
      <div className="flex items-center justify-between mb-6">
        <div><h1 className="text-xl font-bold text-white">SEO Audit</h1><p className="text-sm text-gray-500">Analyze your website for SEO issues</p></div>
        <button onClick={runAudit} disabled={running} className="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 rounded-xl text-sm font-medium text-white disabled:opacity-50">{running ? 'Running...' : 'Run Audit'}</button>
      </div>
      {results ? (
        <div className="space-y-4">
          {results.map(r => (
            <div key={r.category} className="bg-[#12121a] border border-[#1e1e2e] rounded-xl p-5">
              <div className="flex items-center justify-between mb-3">
                <h3 className="text-sm font-medium text-white">{r.category}</h3>
                <span className={`text-lg font-bold text-${getColor(r.score)}-400`}>{r.score}</span>
              </div>
              <div className="w-full h-2 bg-[#1e1e2e] rounded-full overflow-hidden mb-3">
                <div className={`h-full bg-${getColor(r.score)}-500 rounded-full transition-all`} style={{ width: `${r.score}%` }} />
              </div>
              {r.issues.map((issue, i) => (
                <p key={i} className="text-xs text-gray-500 flex items-center gap-2 py-1"><span className="w-1 h-1 rounded-full bg-amber-500" />{issue}</p>
              ))}
            </div>
          ))}
        </div>
      ) : (
        <div className="bg-[#12121a] border border-[#1e1e2e] rounded-xl p-12 text-center">
          <p className="text-gray-500">Click "Run Audit" to analyze your website</p>
        </div>
      )}
    </div>
  );
}
