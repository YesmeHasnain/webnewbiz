import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { projectService } from '../services/project.service';

type Framework = 'html' | 'react' | 'nextjs' | 'vue' | 'angular' | 'svelte';

const frameworks: Array<{ id: Framework; name: string; icon: string; color: string }> = [
  { id: 'html', name: 'HTML/CSS/JS', icon: '🌐', color: '#e34c26' },
  { id: 'react', name: 'React', icon: '⚛️', color: '#61dafb' },
  { id: 'nextjs', name: 'Next.js', icon: '▲', color: '#ffffff' },
  { id: 'vue', name: 'Vue.js', icon: '💚', color: '#42b883' },
  { id: 'angular', name: 'Angular', icon: '🅰️', color: '#dd0031' },
  { id: 'svelte', name: 'Svelte', icon: '🔥', color: '#ff3e00' },
];

export default function PromptPage() {
  const navigate = useNavigate();
  const [siteName, setSiteName] = useState('');
  const [prompt, setPrompt] = useState('');
  const [selectedFw, setSelectedFw] = useState<Framework>('html');
  const [showFwPicker, setShowFwPicker] = useState(false);
  const [building, setBuilding] = useState(false);

  const currentFw = frameworks.find(f => f.id === selectedFw)!;

  const handleBuild = async () => {
    if (!prompt.trim() || !siteName.trim() || building) return;
    setBuilding(true);
    try {
      const name = siteName.trim();
      const res = await projectService.create({ name, framework: selectedFw });
      const proj = res.data.project;
      navigate(`/code-builder/${proj.id}?prompt=${encodeURIComponent(prompt)}`);
    } catch {
      setBuilding(false);
    }
  };

  return (
    <div className="fixed inset-0 bg-[#0a0a0f] flex flex-col">
      {/* Header */}
      <header className="h-14 flex items-center justify-between px-6 border-b border-[#1a1d27]">
        <div className="flex items-center gap-2">
          <div className="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-sm">W</div>
          <span className="text-white font-semibold">WebNewBiz</span>
        </div>
        <div className="flex items-center gap-3">
          <button onClick={() => navigate('/dashboard')} className="px-4 py-2 text-sm text-gray-400 hover:text-white transition">Dashboard</button>
          <button onClick={() => navigate('/billing')} className="px-4 py-2 text-sm text-gray-400 hover:text-white transition">Billing</button>
        </div>
      </header>

      {/* Main content */}
      <div className="flex-1 flex flex-col items-center justify-center px-6">
        {/* Glow effect */}
        <div className="absolute bottom-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-blue-600/5 rounded-full blur-[120px] pointer-events-none" />

        <h1 className="text-4xl md:text-5xl font-bold text-white text-center mb-4">
          What will you <span className="italic bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">build</span> today?
        </h1>
        <p className="text-gray-500 text-center mb-10 text-lg">Create stunning apps & websites by chatting with AI.</p>

        {/* Site name input */}
        <div className="w-full max-w-2xl mb-4">
          <input
            value={siteName}
            onChange={e => setSiteName(e.target.value)}
            placeholder="Your website name (e.g. FitZone, TechHub, Bella Cafe)"
            className="w-full bg-[#12121a] border border-[#1e1e2e] rounded-xl px-5 py-3.5 text-white text-sm outline-none placeholder-gray-600 focus:border-blue-500/50 transition"
          />
        </div>

        {/* Prompt box */}
        <div className="w-full max-w-2xl bg-[#12121a] border border-[#1e1e2e] rounded-2xl shadow-2xl shadow-black/50">
          <textarea
            value={prompt}
            onChange={e => setPrompt(e.target.value)}
            placeholder="Describe what you want to build..."
            rows={4}
            className="w-full bg-transparent px-5 pt-5 pb-2 text-white text-sm outline-none resize-none placeholder-gray-600"
            onKeyDown={e => { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); handleBuild(); } }}
            autoFocus
          />

          {/* Bottom bar of prompt box */}
          <div className="flex items-center justify-between px-4 py-3 border-t border-[#1e1e2e]/50">
            <div className="flex items-center gap-2">
              {/* Framework selector */}
              <div className="relative">
                <button
                  onClick={() => setShowFwPicker(!showFwPicker)}
                  className="flex items-center gap-2 px-3 py-1.5 bg-[#1a1d27] hover:bg-[#22252f] rounded-lg text-xs text-gray-300 transition"
                >
                  <span>{currentFw.icon}</span>
                  <span>{currentFw.name}</span>
                  <svg className="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" /></svg>
                </button>

                {showFwPicker && (
                  <>
                    <div className="fixed inset-0 z-10" onClick={() => setShowFwPicker(false)} />
                    <div className="absolute top-full left-0 mt-2 bg-[#1a1d27] border border-[#2a2d37] rounded-xl overflow-hidden shadow-xl z-20 w-48">
                      {frameworks.map(fw => (
                        <button
                          key={fw.id}
                          onClick={() => { setSelectedFw(fw.id); setShowFwPicker(false); }}
                          className={`w-full flex items-center gap-3 px-4 py-2.5 text-xs text-left transition hover:bg-white/5 ${selectedFw === fw.id ? 'bg-blue-600/10 text-blue-400' : 'text-gray-300'}`}
                        >
                          <span>{fw.icon}</span>
                          <span>{fw.name}</span>
                          {selectedFw === fw.id && <svg className="w-3 h-3 ml-auto text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" /></svg>}
                        </button>
                      ))}
                    </div>
                  </>
                )}
              </div>
            </div>

            <button
              onClick={handleBuild}
              disabled={!prompt.trim() || !siteName.trim() || building}
              className="flex items-center gap-2 px-5 py-2 bg-blue-600 hover:bg-blue-500 disabled:opacity-40 disabled:cursor-not-allowed rounded-xl text-sm text-white font-medium transition shadow-lg shadow-blue-600/20"
            >
              {building ? (
                <><svg className="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" /><path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" /></svg>Building...</>
              ) : (
                <>Build now <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" /></svg></>
              )}
            </button>
          </div>
        </div>

        {/* Import options */}
        <div className="flex items-center gap-4 mt-8 text-sm text-gray-600">
          <span>or import from</span>
          <button className="flex items-center gap-2 px-4 py-2 bg-[#12121a] border border-[#1e1e2e] rounded-xl hover:border-[#2e2e3e] transition text-gray-400 hover:text-white">
            <svg className="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.87 8.17 6.84 9.5.5.08.66-.23.66-.5v-1.69c-2.77.6-3.36-1.34-3.36-1.34-.46-1.16-1.11-1.47-1.11-1.47-.91-.62.07-.6.07-.6 1 .07 1.53 1.03 1.53 1.03.87 1.52 2.34 1.07 2.91.83.09-.65.35-1.09.63-1.34-2.22-.25-4.55-1.11-4.55-4.92 0-1.11.38-2 1.03-2.71-.1-.25-.45-1.29.1-2.64 0 0 .84-.27 2.75 1.02.79-.22 1.65-.33 2.5-.33.85 0 1.71.11 2.5.33 1.91-1.29 2.75-1.02 2.75-1.02.55 1.35.2 2.39.1 2.64.65.71 1.03 1.6 1.03 2.71 0 3.82-2.34 4.66-4.57 4.91.36.31.69.92.69 1.85V21c0 .27.16.59.67.5C19.14 20.16 22 16.42 22 12A10 10 0 0012 2z"/></svg>
            GitHub
          </button>
          <button className="flex items-center gap-2 px-4 py-2 bg-[#12121a] border border-[#1e1e2e] rounded-xl hover:border-[#2e2e3e] transition text-gray-400 hover:text-white">
            <svg className="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M5.5 9.5A2.5 2.5 0 018 7h8a2.5 2.5 0 012.5 2.5v5A2.5 2.5 0 0116 17H8a2.5 2.5 0 01-2.5-2.5v-5z"/></svg>
            Figma
          </button>
        </div>
      </div>
    </div>
  );
}
