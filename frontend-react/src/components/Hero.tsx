import { useState } from 'react';

interface HeroProps {
  onGenerate: (prompt: string) => void;
}

export default function Hero({ onGenerate }: HeroProps) {
  const [prompt, setPrompt] = useState('');

  const handleGenerate = () => {
    if (prompt.trim().length >= 10) onGenerate(prompt.trim());
  };

  return (
    <section id="hero" className="relative min-h-screen flex items-center justify-center overflow-hidden">
      {/* Background grid */}
      <div className="absolute inset-0" style={{
        backgroundImage: 'linear-gradient(rgba(0,0,0,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(0,0,0,0.03) 1px, transparent 1px)',
        backgroundSize: '60px 60px'
      }} />

      {/* Floating orbs */}
      <div className="absolute top-20 left-10 w-72 h-72 bg-neutral-100 rounded-full blur-3xl opacity-60" style={{ animation: 'float 6s ease-in-out infinite' }} />
      <div className="absolute bottom-20 right-10 w-96 h-96 bg-neutral-50 rounded-full blur-3xl opacity-80" style={{ animation: 'float 8s ease-in-out 2s infinite' }} />

      <div className="relative z-10 max-w-5xl mx-auto px-6 text-center">
        {/* Badge */}
        <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-neutral-100 border border-neutral-200 mb-8 animate-fade-in-up">
          <span className="w-2 h-2 rounded-full bg-black animate-pulse" />
          <span className="text-xs font-semibold text-neutral-600 tracking-wide uppercase">AI-Powered Website Builder</span>
        </div>

        {/* Headline */}
        <h1 className="text-5xl md:text-7xl lg:text-8xl font-black tracking-tighter leading-[0.9] mb-6 animate-fade-in-up" style={{ animationDelay: '0.1s' }}>
          Build Your Website<br/>
          <span className="text-neutral-300">In Seconds</span>
        </h1>

        {/* Subtitle */}
        <p className="text-lg md:text-xl text-neutral-500 max-w-2xl mx-auto mb-12 leading-relaxed animate-fade-in-up" style={{ animationDelay: '0.2s' }}>
          Describe your business and let AI create a stunning, fully functional
          WordPress website. No coding. No templates. Just results.
        </p>

        {/* Prompt Box */}
        <div className="max-w-2xl mx-auto animate-fade-in-up" style={{ animationDelay: '0.3s' }}>
          <div className="bg-white border border-neutral-200 rounded-[20px] p-4 px-5 shadow-[0_4px_40px_rgba(0,0,0,0.06)] transition-all focus-within:border-black focus-within:shadow-[0_8px_60px_rgba(0,0,0,0.1)]">
            <div className="flex items-start gap-3">
              <div className="mt-3 ml-1">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#a3a3a3" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                  <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
              </div>
              <textarea
                value={prompt}
                onChange={(e) => setPrompt(e.target.value)}
                placeholder="Describe your business... e.g., 'A modern dental clinic in Dubai offering teeth whitening and cosmetic dentistry'"
                rows={3}
                className="flex-1 bg-transparent border-none outline-none resize-none text-base text-black placeholder-neutral-400 py-3 font-[inherit] leading-relaxed"
                onKeyDown={(e) => { if (e.key === 'Enter') { e.preventDefault(); handleGenerate(); } }}
              />
            </div>
            <div className="flex items-center justify-between pt-3 border-t border-neutral-100">
              <span className="text-xs text-neutral-400">{prompt.length > 0 ? `${prompt.length} characters` : 'Minimum 10 characters'}</span>
              <button
                className={`btn-primary !py-2.5 !px-6 !text-sm !rounded-xl flex items-center gap-2 ${prompt.trim().length < 10 ? 'opacity-40' : ''}`}
                disabled={prompt.trim().length < 10}
                onClick={handleGenerate}
              >
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                  <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                </svg>
                Generate Website
              </button>
            </div>
          </div>
        </div>

        {/* Stats */}
        <div className="flex flex-wrap items-center justify-center gap-8 md:gap-16 mt-16 animate-fade-in-up" style={{ animationDelay: '0.5s' }}>
          <div className="text-center">
            <div className="text-3xl md:text-4xl font-black text-black">10K+</div>
            <div className="text-xs text-neutral-500 mt-1 font-medium">Websites Created</div>
          </div>
          <div className="w-px h-10 bg-neutral-200 hidden md:block" />
          <div className="text-center">
            <div className="text-3xl md:text-4xl font-black text-black">30s</div>
            <div className="text-xs text-neutral-500 mt-1 font-medium">Average Build Time</div>
          </div>
          <div className="w-px h-10 bg-neutral-200 hidden md:block" />
          <div className="text-center">
            <div className="text-3xl md:text-4xl font-black text-black">99.9%</div>
            <div className="text-xs text-neutral-500 mt-1 font-medium">Uptime Guaranteed</div>
          </div>
        </div>
      </div>

      {/* Scroll indicator */}
      <div className="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#a3a3a3" strokeWidth="2" strokeLinecap="round">
          <path d="M12 5v14M19 12l-7 7-7-7"/>
        </svg>
      </div>
    </section>
  );
}
