import { useState, useEffect } from 'react';
import { useNavigate, useSearchParams } from 'react-router-dom';
import Navbar from '../components/Navbar';
import { builderService } from '../services/builder.service';
import { websiteService } from '../services/website.service';
import type { AnalysisResult, LayoutOption } from '../models/types';

const layoutColors: Record<string, string> = {
  noir: '#FF4500', ivory: '#1B4D3E', azure: '#2563EB', blush: '#C9A87C',
  ember: '#DC2626', forest: '#2D6A4F', slate: '#334155', royal: '#7C3AED', biddut: '#007FFF',
};

const layoutEmojis: Record<string, string> = {
  noir: '🌑', ivory: '🤍', azure: '💎', blush: '🌸',
  ember: '🔥', forest: '🌿', slate: '🪨', royal: '👑', biddut: '⚡',
};

const pageOptions = ['home', 'about', 'services', 'portfolio', 'contact'];
const stepLabels = ['Describe', 'Review', 'Design', 'Launch'];

function titleCase(s: string) {
  return s.charAt(0).toUpperCase() + s.slice(1);
}

export default function BuilderWizard() {
  const navigate = useNavigate();
  const [searchParams] = useSearchParams();

  const [step, setStep] = useState(1);
  const [prompt, setPrompt] = useState('');
  const [analysis, setAnalysis] = useState<AnalysisResult | null>(null);
  const [layouts, setLayouts] = useState<LayoutOption[]>([]);
  const [selectedLayout, setSelectedLayout] = useState('');
  const [businessName, setBusinessName] = useState('');
  const [pages, setPages] = useState<string[]>([]);
  const [analyzing, setAnalyzing] = useState(false);
  const [enhancing, setEnhancing] = useState(false);
  const [building, setBuilding] = useState(false);
  const [error, setError] = useState('');
  const [animKey, setAnimKey] = useState(0);

  useEffect(() => {
    const urlPrompt = searchParams.get('prompt');
    if (urlPrompt) setPrompt(urlPrompt);
    else {
      const pending = sessionStorage.getItem('pending_prompt');
      if (pending) { setPrompt(pending); sessionStorage.removeItem('pending_prompt'); }
    }
    builderService.getLayouts().then(res => setLayouts(res.data)).catch(() => {});
  }, [searchParams]);

  const goStep = (s: number) => { setStep(s); setAnimKey(k => k + 1); };

  const enhancePrompt = async () => {
    if (prompt.trim().length < 3) return;
    setEnhancing(true); setError('');
    try {
      const res = await builderService.enhancePrompt(prompt);
      if (res.data.success && res.data.enhanced) setPrompt(res.data.enhanced);
    } catch { setError('Enhancement failed.'); }
    finally { setEnhancing(false); }
  };

  const analyzePrompt = async () => {
    if (prompt.trim().length < 10) return;
    setAnalyzing(true); setError('');
    try {
      const res = await builderService.analyzePrompt(prompt);
      setAnalysis(res.data);
      setBusinessName(res.data.business_name);
      setPages(res.data.pages);
      setSelectedLayout(res.data.recommended_layout);
      goStep(2);
    } catch (err: any) {
      setError(err.response?.data?.message || 'Analysis failed.');
    } finally { setAnalyzing(false); }
  };

  const togglePage = (page: string) => {
    setPages(prev => prev.includes(page) ? prev.filter(p => p !== page) : [...prev, page]);
  };

  const startBuild = async () => {
    setBuilding(true); setError('');
    try {
      const res = await websiteService.generate({
        business_name: businessName,
        business_type: analysis?.business_type || 'business',
        prompt, layout: selectedLayout, pages,
      });
      navigate(`/builder/progress/${res.data.id}`);
    } catch (err: any) {
      setBuilding(false);
      setError(err.response?.data?.message || 'Build failed.');
    }
  };

  return (
    <div style={{ minHeight: '100vh', background: 'linear-gradient(180deg, #f8f8f8 0%, #fff 40%)' }}>
      <Navbar forceDark />
      <main style={{ paddingTop: 110, paddingBottom: 80, maxWidth: 720, margin: '0 auto', padding: '110px 24px 80px' }}>

        {/* Step Indicator */}
        <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 0, marginBottom: 48 }}>
          {stepLabels.map((label, i) => {
            const s = i + 1;
            const done = step > s;
            const active = step === s;
            return (
              <div key={s} style={{ display: 'flex', alignItems: 'center' }}>
                <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 6 }}>
                  <div style={{
                    width: 40, height: 40, borderRadius: '50%',
                    display: 'flex', alignItems: 'center', justifyContent: 'center',
                    fontSize: 14, fontWeight: 700,
                    background: done ? '#111' : active ? 'linear-gradient(135deg, #111 0%, #333 100%)' : '#f0f0f0',
                    color: done || active ? '#fff' : '#bbb',
                    boxShadow: active ? '0 4px 20px rgba(0,0,0,.15)' : 'none',
                    transition: 'all .4s cubic-bezier(.4,0,.2,1)',
                    transform: active ? 'scale(1.1)' : 'scale(1)',
                  }}>
                    {done ? (
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" strokeWidth="3" strokeLinecap="round"><polyline points="20 6 9 17 4 12"/></svg>
                    ) : s}
                  </div>
                  <span style={{
                    fontSize: 11, fontWeight: active ? 700 : 500,
                    color: done || active ? '#111' : '#bbb',
                    letterSpacing: '.3px', transition: 'all .3s',
                  }}>{label}</span>
                </div>
                {s < 4 && (
                  <div style={{
                    width: 60, height: 2, margin: '0 8px',
                    background: done ? '#111' : '#eee',
                    borderRadius: 1, transition: 'background .4s',
                    marginBottom: 20,
                  }} />
                )}
              </div>
            );
          })}
        </div>

        {error && (
          <div style={{
            marginBottom: 24, padding: '14px 20px', borderRadius: 14,
            background: '#fef2f2', border: '1px solid #fecaca', color: '#b91c1c',
            fontSize: 14, display: 'flex', alignItems: 'center', gap: 10,
          }}>
            <span>&#9888;</span> {error}
          </div>
        )}

        <div key={animKey} style={{ animation: 'wizardIn .5s cubic-bezier(.4,0,.2,1)' }}>

          {/* Step 1: Describe */}
          {step === 1 && (
            <>
              <div style={{ textAlign: 'center', marginBottom: 32 }}>
                <div style={{ fontSize: 40, marginBottom: 12 }}>&#9889;</div>
                <h1 style={{ fontSize: 30, fontWeight: 800, letterSpacing: '-.03em', margin: '0 0 8px', color: '#111' }}>Describe Your Business</h1>
                <p style={{ color: '#999', fontSize: 15, margin: 0 }}>Tell us what you do and AI will design the perfect website</p>
              </div>
              <div style={cardStyle}>
                <textarea
                  value={prompt}
                  onChange={e => setPrompt(e.target.value)}
                  rows={5}
                  placeholder="Example: A modern dental clinic in Dubai specializing in teeth whitening, cosmetic dentistry, and orthodontics..."
                  style={{
                    width: '100%', padding: '16px 18px', borderRadius: 14,
                    border: '1px solid #e5e5e5', fontSize: 15, lineHeight: 1.7,
                    resize: 'none', outline: 'none', fontFamily: 'inherit',
                    transition: 'border .2s',
                    background: '#fafafa',
                  }}
                  onFocus={e => e.target.style.borderColor = '#111'}
                  onBlur={e => e.target.style.borderColor = '#e5e5e5'}
                />
                <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginTop: 16 }}>
                  <span style={{ fontSize: 12, color: '#ccc' }}>{prompt.length} / 10 min</span>
                  <div style={{ display: 'flex', gap: 10 }}>
                    <button
                      onClick={enhancePrompt}
                      disabled={prompt.trim().length < 3 || enhancing || analyzing}
                      style={btnOutline}
                    >
                      {enhancing ? <><Spin /> Enhancing...</> : <><Star /> Enhance with AI</>}
                    </button>
                    <button
                      onClick={analyzePrompt}
                      disabled={prompt.trim().length < 10 || analyzing}
                      style={btnPrimary}
                    >
                      {analyzing ? <><Spin /> Analyzing...</> : <>Continue &#8594;</>}
                    </button>
                  </div>
                </div>
              </div>
            </>
          )}

          {/* Step 2: AI Analysis */}
          {step === 2 && analysis && (
            <>
              <div style={{ textAlign: 'center', marginBottom: 32 }}>
                <div style={{ fontSize: 40, marginBottom: 12 }}>&#10024;</div>
                <h1 style={{ fontSize: 30, fontWeight: 800, letterSpacing: '-.03em', margin: '0 0 8px', color: '#111' }}>AI Analysis Complete</h1>
                <p style={{ color: '#999', fontSize: 15, margin: 0 }}>Review and customize before choosing a design</p>
              </div>
              <div style={cardStyle}>
                <div style={{ marginBottom: 24 }}>
                  <label style={labelStyle}>Business Name</label>
                  <input
                    type="text" value={businessName}
                    onChange={e => setBusinessName(e.target.value)}
                    style={inputStyle}
                  />
                </div>
                <div style={{ marginBottom: 24 }}>
                  <label style={labelStyle}>Business Type</label>
                  <div style={{ ...inputStyle, background: '#f8f8f8', color: '#666', cursor: 'default' }}>
                    {analysis.business_type}
                  </div>
                </div>
                <div style={{ marginBottom: 24 }}>
                  <label style={labelStyle}>Key Features</label>
                  <div style={{ display: 'flex', flexWrap: 'wrap', gap: 8 }}>
                    {analysis.features.map(f => (
                      <span key={f} style={{
                        padding: '6px 14px', borderRadius: 20, fontSize: 13,
                        background: '#f5f5f5', color: '#555', fontWeight: 500,
                      }}>{f}</span>
                    ))}
                  </div>
                </div>
                <div style={{ marginBottom: 24 }}>
                  <label style={labelStyle}>Pages</label>
                  <div style={{ display: 'flex', flexWrap: 'wrap', gap: 8 }}>
                    {pageOptions.map(p => (
                      <button key={p} onClick={() => togglePage(p)} style={{
                        padding: '8px 18px', borderRadius: 20, fontSize: 13, fontWeight: 600,
                        border: '2px solid', cursor: 'pointer', transition: 'all .2s',
                        background: pages.includes(p) ? '#111' : '#fff',
                        color: pages.includes(p) ? '#fff' : '#555',
                        borderColor: pages.includes(p) ? '#111' : '#e0e0e0',
                      }}>
                        {pages.includes(p) && '✓ '}{titleCase(p)}
                      </button>
                    ))}
                  </div>
                </div>
                <div style={{
                  padding: '16px 20px', borderRadius: 14, background: '#f0fdf4', border: '1px solid #bbf7d0',
                  marginBottom: 20,
                }}>
                  <p style={{ fontSize: 13, color: '#166534', margin: 0, lineHeight: 1.7 }}>
                    <strong>AI Insight:</strong> {analysis.reasoning}
                  </p>
                </div>
                <div style={{ display: 'flex', justifyContent: 'space-between' }}>
                  <button onClick={() => goStep(1)} style={btnOutline}>&#8592; Back</button>
                  <button onClick={() => goStep(3)} style={btnPrimary}>Choose Design &#8594;</button>
                </div>
              </div>
            </>
          )}

          {/* Step 3: Choose Layout */}
          {step === 3 && (
            <>
              <div style={{ textAlign: 'center', marginBottom: 32 }}>
                <div style={{ fontSize: 40, marginBottom: 12 }}>&#127912;</div>
                <h1 style={{ fontSize: 30, fontWeight: 800, letterSpacing: '-.03em', margin: '0 0 8px', color: '#111' }}>Choose Your Design</h1>
                <p style={{ color: '#999', fontSize: 15, margin: 0 }}>Each layout is crafted for specific industries</p>
              </div>
              <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: 14, marginBottom: 32 }}>
                {layouts.map(layout => {
                  const selected = selectedLayout === layout.slug;
                  const recommended = analysis?.recommended_layout === layout.slug;
                  const color = layoutColors[layout.slug] || '#2563EB';
                  const accent = layout.accent || color;
                  const isDark = layout.is_dark;
                  const previewBg = layout.preview_bg || '#fff';
                  return (
                    <button
                      key={layout.slug}
                      onClick={() => setSelectedLayout(layout.slug)}
                      style={{
                        padding: 0, borderRadius: 16, border: '2px solid',
                        borderColor: selected ? color : '#f0f0f0',
                        background: '#fff',
                        textAlign: 'left', cursor: 'pointer',
                        transition: 'all .25s cubic-bezier(.4,0,.2,1)',
                        transform: selected ? 'scale(1.03)' : 'scale(1)',
                        boxShadow: selected ? `0 8px 30px ${color}22` : '0 1px 3px rgba(0,0,0,.03)',
                        position: 'relative', overflow: 'hidden',
                      }}
                    >
                      {/* Mini preview bar showing layout's color palette */}
                      <div style={{
                        height: 48, background: previewBg, position: 'relative',
                        display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 4,
                        borderBottom: '1px solid #f0f0f0',
                      }}>
                        {/* Mini "hero" mockup */}
                        <div style={{ width: '70%', display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 3 }}>
                          <div style={{ width: '60%', height: 4, borderRadius: 2, background: isDark ? '#fff' : '#333', opacity: 0.8 }} />
                          <div style={{ width: '40%', height: 3, borderRadius: 2, background: isDark ? 'rgba(255,255,255,0.3)' : 'rgba(0,0,0,0.15)' }} />
                          <div style={{ display: 'flex', gap: 3, marginTop: 2 }}>
                            <div style={{ width: 20, height: 6, borderRadius: 3, background: color }} />
                            <div style={{ width: 20, height: 6, borderRadius: 3, border: `1px solid ${isDark ? 'rgba(255,255,255,0.3)' : 'rgba(0,0,0,0.2)'}`, background: 'transparent' }} />
                          </div>
                        </div>
                        {/* Color dots */}
                        <div style={{ position: 'absolute', right: 8, top: 8, display: 'flex', gap: 3 }}>
                          <div style={{ width: 8, height: 8, borderRadius: '50%', background: color, boxShadow: `0 0 0 1px rgba(0,0,0,.1)` }} />
                          <div style={{ width: 8, height: 8, borderRadius: '50%', background: accent, boxShadow: `0 0 0 1px rgba(0,0,0,.1)` }} />
                        </div>
                        {recommended && (
                          <div style={{
                            position: 'absolute', top: 6, left: 8,
                            background: '#d1fae5', color: '#065f46', fontSize: 9, fontWeight: 700,
                            padding: '2px 6px', borderRadius: 4, letterSpacing: '.3px',
                          }}>AI PICK</div>
                        )}
                      </div>
                      <div style={{ padding: '14px 16px 16px' }}>
                        <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 6 }}>
                          <span style={{ fontSize: 18 }}>{layoutEmojis[layout.slug] || '🎨'}</span>
                          <div>
                            <div style={{ fontSize: 14, fontWeight: 700, color: '#111', textTransform: 'capitalize' }}>
                              {layout.name}
                            </div>
                            <div style={{ fontSize: 11, color: '#999', textTransform: 'capitalize' }}>
                              {layout.style}{isDark ? ' · dark' : ' · light'}
                            </div>
                          </div>
                        </div>
                        {layout.description && (
                          <div style={{ fontSize: 11, color: '#aaa', lineHeight: 1.4, marginBottom: 8 }}>
                            {layout.description}
                          </div>
                        )}
                        <div style={{ display: 'flex', flexWrap: 'wrap', gap: 4 }}>
                          {layout.best_for?.slice(0, 3).map((tag: string) => (
                            <span key={tag} style={{
                              fontSize: 10, padding: '2px 7px', borderRadius: 4,
                              background: selected ? `${color}15` : '#f5f5f5',
                              color: selected ? color : '#888', fontWeight: 500,
                            }}>{tag}</span>
                          ))}
                        </div>
                      </div>
                      {selected && (
                        <div style={{
                          position: 'absolute', bottom: 0, left: 0, right: 0, height: 3,
                          background: `linear-gradient(90deg, ${color}, ${accent})`,
                        }} />
                      )}
                    </button>
                  );
                })}
              </div>
              <div style={{ display: 'flex', justifyContent: 'space-between' }}>
                <button onClick={() => goStep(2)} style={btnOutline}>&#8592; Back</button>
                <button onClick={() => goStep(4)} disabled={!selectedLayout} style={btnPrimary}>Review & Launch &#8594;</button>
              </div>
            </>
          )}

          {/* Step 4: Confirm */}
          {step === 4 && (
            <>
              <div style={{ textAlign: 'center', marginBottom: 32 }}>
                <div style={{ fontSize: 40, marginBottom: 12 }}>&#128640;</div>
                <h1 style={{ fontSize: 30, fontWeight: 800, letterSpacing: '-.03em', margin: '0 0 8px', color: '#111' }}>Ready to Launch</h1>
                <p style={{ color: '#999', fontSize: 15, margin: 0 }}>Everything looks good — let's build your website</p>
              </div>
              <div style={cardStyle}>
                {[
                  ['Business', businessName],
                  ['Type', analysis?.business_type || ''],
                  ['Design', selectedLayout],
                  ['Pages', pages.map(p => titleCase(p)).join(', ')],
                ].map(([label, value], i) => (
                  <div key={i} style={{
                    display: 'flex', justifyContent: 'space-between', alignItems: 'center',
                    padding: '16px 0',
                    borderBottom: i < 3 ? '1px solid #f5f5f5' : 'none',
                  }}>
                    <span style={{ fontSize: 14, color: '#999' }}>{label}</span>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                      {label === 'Design' && (
                        <div style={{
                          width: 16, height: 16, borderRadius: 4,
                          background: layoutColors[selectedLayout] || '#2563EB',
                        }} />
                      )}
                      <span style={{ fontSize: 14, fontWeight: 600, color: '#111', textTransform: 'capitalize' }}>{value}</span>
                    </div>
                  </div>
                ))}
                <div style={{ display: 'flex', justifyContent: 'space-between', marginTop: 24 }}>
                  <button onClick={() => goStep(3)} style={btnOutline}>&#8592; Back</button>
                  <button onClick={startBuild} disabled={building} style={{
                    ...btnPrimary,
                    padding: '14px 36px', fontSize: 15,
                    background: building ? '#666' : 'linear-gradient(135deg, #111 0%, #333 100%)',
                  }}>
                    {building ? <><Spin /> Building...</> : <>&#9889; Build My Website</>}
                  </button>
                </div>
              </div>
            </>
          )}
        </div>
      </main>

      <style>{`
        @keyframes wizardIn {
          from { opacity: 0; transform: translateY(16px); }
          to { opacity: 1; transform: none; }
        }
      `}</style>
    </div>
  );
}

/* --- Shared Styles --- */
const cardStyle: React.CSSProperties = {
  background: '#fff', borderRadius: 20, padding: 32,
  border: '1px solid #f0f0f0',
  boxShadow: '0 1px 3px rgba(0,0,0,.03), 0 8px 30px rgba(0,0,0,.04)',
};

const labelStyle: React.CSSProperties = {
  display: 'block', fontSize: 13, fontWeight: 600, color: '#555',
  marginBottom: 8, letterSpacing: '.2px',
};

const inputStyle: React.CSSProperties = {
  width: '100%', padding: '14px 18px', borderRadius: 14,
  border: '1px solid #e5e5e5', fontSize: 15, outline: 'none',
  fontFamily: 'inherit', transition: 'border .2s', boxSizing: 'border-box',
};

const btnPrimary: React.CSSProperties = {
  display: 'inline-flex', alignItems: 'center', gap: 8,
  padding: '12px 28px', borderRadius: 14,
  background: 'linear-gradient(135deg, #111 0%, #333 100%)',
  color: '#fff', fontSize: 14, fontWeight: 600,
  border: 'none', cursor: 'pointer', transition: 'all .2s',
  boxShadow: '0 4px 14px rgba(0,0,0,.12)',
};

const btnOutline: React.CSSProperties = {
  display: 'inline-flex', alignItems: 'center', gap: 8,
  padding: '12px 24px', borderRadius: 14,
  background: '#fff', color: '#555', fontSize: 14, fontWeight: 600,
  border: '1px solid #e0e0e0', cursor: 'pointer', transition: 'all .2s',
};

function Spin() {
  return (
    <svg style={{ animation: 'spin .8s linear infinite' }} width="16" height="16" viewBox="0 0 24 24" fill="none">
      <circle cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="3" opacity=".25" />
      <path fill="currentColor" opacity=".75" d="M4 12a8 8 0 018-8V0C5.4 0 0 5.4 0 12h4zm2 5.3A8 8 0 014 12H0c0 3 1.1 5.8 3 7.9l3-2.6z" />
    </svg>
  );
}

function Star() {
  return (
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round">
      <path d="M12 2l2.4 7.4H22l-6 4.6 2.3 7-6.3-4.6L5.7 21 8 14 2 9.4h7.6z"/>
    </svg>
  );
}
