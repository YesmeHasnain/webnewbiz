import { useState } from 'react';

export default function BoosterSettings() {
  const [settings, setSettings] = useState({
    lazyLoading: true, preloadFonts: true, deferJs: true, minifyCss: true,
    minifyJs: true, minifyHtml: false, gzip: true, cachePages: true, cacheTtl: '3600',
  });
  const toggle = (key: string) => setSettings(s => ({ ...s, [key]: !s[key as keyof typeof s] }));

  const groups = [
    { title: 'Performance', items: [
      { key: 'lazyLoading', label: 'Lazy Load Images', desc: 'Load images only when visible in viewport' },
      { key: 'preloadFonts', label: 'Preload Fonts', desc: 'Preload critical fonts for faster rendering' },
      { key: 'deferJs', label: 'Defer JavaScript', desc: 'Load non-critical JS after page render' },
    ]},
    { title: 'Minification', items: [
      { key: 'minifyCss', label: 'Minify CSS', desc: 'Remove whitespace from CSS files' },
      { key: 'minifyJs', label: 'Minify JavaScript', desc: 'Compress JavaScript files' },
      { key: 'minifyHtml', label: 'Minify HTML', desc: 'Remove whitespace from HTML output' },
    ]},
    { title: 'Caching', items: [
      { key: 'gzip', label: 'Gzip Compression', desc: 'Compress responses for faster transfer' },
      { key: 'cachePages', label: 'Page Caching', desc: 'Cache full page output for faster loads' },
    ]},
  ];

  return (
    <div className="p-6">
      <h1 className="text-xl font-bold text-white mb-1">Booster Settings</h1>
      <p className="text-sm text-gray-500 mb-6">Fine-tune performance optimization settings</p>
      <div className="space-y-6 max-w-2xl">
        {groups.map(g => (
          <div key={g.title} className="bg-[#12121a] border border-[#1e1e2e] rounded-xl p-5">
            <h3 className="text-sm font-semibold text-gray-300 mb-4">{g.title}</h3>
            <div className="space-y-4">
              {g.items.map(item => (
                <div key={item.key} className="flex items-center justify-between">
                  <div><p className="text-sm text-white">{item.label}</p><p className="text-xs text-gray-600">{item.desc}</p></div>
                  <button onClick={() => toggle(item.key)} className={`w-10 h-5 rounded-full transition ${settings[item.key as keyof typeof settings] ? 'bg-blue-600' : 'bg-gray-700'}`}>
                    <div className={`w-4 h-4 rounded-full bg-white transition-transform ${settings[item.key as keyof typeof settings] ? 'translate-x-5' : 'translate-x-0.5'}`} />
                  </button>
                </div>
              ))}
            </div>
          </div>
        ))}
        <button className="px-6 py-2.5 bg-blue-600 hover:bg-blue-500 rounded-xl text-sm font-medium text-white">Save Settings</button>
      </div>
    </div>
  );
}
