import { useState, useEffect, useCallback } from 'react';
import { useParams, useNavigate, useOutletContext } from 'react-router-dom';
import { builderService } from '../../services/builder.service';
import { websiteService } from '../../services/website.service';
import type { Website, LayoutOption, AnalysisResult } from '../../models/types';

const layoutColors: Record<string, string> = {
  noir: '#FF4500', ivory: '#1B4D3E', azure: '#2563EB', blush: '#C9A87C',
  ember: '#DC2626', forest: '#2D6A4F', slate: '#334155', royal: '#7C3AED', biddut: '#007FFF',
};
const pageOptions = ['home', 'about', 'services', 'portfolio', 'contact'];

export default function AIBuilder() {
  const { id } = useParams<{ id: string }>();
  const navigate = useNavigate();
  const { website } = useOutletContext<{ website: Website }>();
  const websiteId = Number(id);

  const [step, setStep] = useState(1);
  const [prompt, setPrompt] = useState('');
  const [layouts, setLayouts] = useState<LayoutOption[]>([]);
  const [selectedLayout, setSelectedLayout] = useState('');
  const [businessName, setBusinessName] = useState('');
  const [businessType, setBusinessType] = useState('');
  const [pages, setPages] = useState<string[]>([]);
  const [analysis, setAnalysis] = useState<AnalysisResult | null>(null);

  const [analyzing, setAnalyzing] = useState(false);
  const [enhancing, setEnhancing] = useState(false);
  const [building, setBuilding] = useState(false);
  const [error, setError] = useState('');

  // Pre-fill from existing website
  useEffect(() => {
    if (website) {
      setPrompt(website.ai_prompt || '');
      setSelectedLayout(website.ai_theme || 'azure');
      setBusinessName(website.name || '');
      setBusinessType(website.business_type || '');
      const existing = website.pages;
      if (Array.isArray(existing) && existing.length) {
        setPages(existing);
      } else {
        setPages(['home', 'about', 'services', 'contact']);
      }
    }
  }, [website]);

  useEffect(() => {
    builderService.getLayouts().then(res => setLayouts(res.data)).catch(() => {});
  }, []);

  const enhancePrompt = useCallback(async () => {
    if (prompt.trim().length < 3) return;
    setEnhancing(true);
    setError('');
    try {
      const res = await builderService.enhancePrompt(prompt);
      if (res.data.success && res.data.enhanced) setPrompt(res.data.enhanced);
    } catch { setError('Enhancement failed.'); }
    finally { setEnhancing(false); }
  }, [prompt]);

  const analyzePrompt = useCallback(async () => {
    if (prompt.trim().length < 10) return;
    setAnalyzing(true);
    setError('');
    try {
      const res = await builderService.analyzePrompt(prompt);
      const r = res.data;
      setAnalysis(r);
      if (r.business_name) setBusinessName(r.business_name);
      if (r.business_type) setBusinessType(r.business_type);
      if (r.pages?.length) setPages(r.pages);
      if (r.recommended_layout) setSelectedLayout(r.recommended_layout);
      setStep(2);
    } catch (e: any) {
      setError(e.response?.data?.message || 'Analysis failed.');
    } finally { setAnalyzing(false); }
  }, [prompt]);

  const skipAnalysis = () => setStep(2);

  const togglePage = (p: string) => {
    setPages(prev => prev.includes(p) ? prev.filter(x => x !== p) : [...prev, p]);
  };

  const startRebuild = useCallback(async () => {
    setBuilding(true);
    setError('');
    try {
      await websiteService.rebuild(websiteId, {
        prompt,
        layout: selectedLayout,
        pages,
        business_name: businessName,
        business_type: businessType,
      });
      navigate(`/builder/progress/${websiteId}`);
    } catch (e: any) {
      setError(e.response?.data?.message || 'Rebuild failed.');
      setBuilding(false);
    }
  }, [websiteId, prompt, selectedLayout, pages, businessName, businessType, navigate]);

  return (
    <div style={s.page}>
      <div style={s.inner}>
        {/* Progress Steps */}
        <div style={s.steps}>
          {[1, 2, 3].map(n => (
            <div key={n} style={s.stepRow}>
              <div style={{ ...s.stepCircle, ...(step >= n ? s.stepActive : {}) }}>
                {step > n ? (
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="3" strokeLinecap="round"><polyline points="20 6 9 17 4 12"/></svg>
                ) : n}
              </div>
              {n < 3 && <div style={{ ...s.stepLine, ...(step > n ? { background: '#111827' } : {}) }} />}
            </div>
          ))}
        </div>

        {error && <div style={s.error}>{error}</div>}

        {/* Step 1: Describe */}
        {step === 1 && (
          <div style={s.card}>
            <div style={s.cardHeader}>
              <div style={s.iconWrap}>
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeWidth="2" strokeLinecap="round" d="M15.232 5.232l3.536 3.536M4 20h4.586a1 1 0 00.707-.293l10.9-10.9a2 2 0 000-2.828l-2.172-2.172a2 2 0 00-2.828 0l-10.9 10.9A1 1 0 004 15.414V20z"/>
                </svg>
              </div>
              <div>
                <h2 style={s.h2}>Describe Your Business</h2>
                <p style={s.sub}>Update your description or keep the current one. AI will rebuild your website.</p>
              </div>
            </div>

            <div style={s.field}>
              <label style={s.label}>Business Description</label>
              <textarea
                style={s.textarea}
                value={prompt}
                onChange={e => setPrompt(e.target.value)}
                rows={5}
                placeholder="Describe your business, services, target audience..."
              />
              <div style={s.charCount}>{prompt.length} characters (min. 10)</div>
            </div>

            <div style={s.row2}>
              <div style={{ ...s.field, flex: 1 }}>
                <label style={s.label}>Business Name</label>
                <input style={s.input} value={businessName} onChange={e => setBusinessName(e.target.value)} />
              </div>
              <div style={{ ...s.field, flex: 1 }}>
                <label style={s.label}>Business Type</label>
                <input style={s.input} value={businessType} onChange={e => setBusinessType(e.target.value)} />
              </div>
            </div>

            <div style={s.actions}>
              <button style={s.btnSecondary} className="aib-btn-sec" onClick={enhancePrompt} disabled={prompt.trim().length < 3 || enhancing}>
                {enhancing ? <><Spinner /> Enhancing...</> : (
                  <>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><path d="M12 2l2.4 7.4H22l-6 4.6 2.3 7-6.3-4.6L5.7 21 8 14 2 9.4h7.6z"/></svg>
                    Enhance with AI
                  </>
                )}
              </button>
              <div style={{ display: 'flex', gap: 8 }}>
                <button style={s.btnOutline} className="aib-btn-sec" onClick={skipAnalysis} disabled={prompt.trim().length < 10}>
                  Skip Analysis
                </button>
                <button style={s.btnPrimary} className="aib-btn-pri" onClick={analyzePrompt} disabled={prompt.trim().length < 10 || analyzing}>
                  {analyzing ? <><Spinner /> Analyzing...</> : (
                    <>
                      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                      Analyze with AI
                    </>
                  )}
                </button>
              </div>
            </div>

            {analysis && (
              <div style={s.analysisBox}>
                <h4 style={{ margin: '0 0 8px', fontSize: 13, fontWeight: 600 }}>AI Analysis</h4>
                <div style={{ display: 'flex', flexWrap: 'wrap', gap: 6, marginBottom: 8 }}>
                  {analysis.features.map(f => (
                    <span key={f} style={s.chip}>{f}</span>
                  ))}
                </div>
                <p style={{ margin: 0, fontSize: 12, color: '#6B7280' }}>{analysis.reasoning}</p>
              </div>
            )}
          </div>
        )}

        {/* Step 2: Choose Layout */}
        {step === 2 && (
          <div style={s.card}>
            <div style={s.cardHeader}>
              <div style={s.iconWrap}>
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeWidth="2" strokeLinecap="round" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                </svg>
              </div>
              <div>
                <h2 style={s.h2}>Choose Layout</h2>
                <p style={s.sub}>Select a design for your website. Current: <strong style={{ textTransform: 'capitalize' }}>{website.ai_theme}</strong></p>
              </div>
            </div>

            <div style={s.layoutGrid}>
              {layouts.map(l => {
                const active = selectedLayout === l.slug;
                const recommended = analysis?.recommended_layout === l.slug;
                return (
                  <button
                    key={l.slug}
                    onClick={() => setSelectedLayout(l.slug)}
                    style={{ ...s.layoutCard, ...(active ? { borderColor: '#111827', boxShadow: '0 4px 12px rgba(0,0,0,0.1)' } : {}) }}
                    className="aib-layout"
                  >
                    <div style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 6 }}>
                      <div style={{ width: 28, height: 28, borderRadius: 8, background: layoutColors[l.slug] || '#666', flexShrink: 0 }} />
                      <div>
                        <div style={{ fontSize: 13, fontWeight: 600, textTransform: 'capitalize' as const }}>{l.name}</div>
                        <div style={{ fontSize: 11, color: '#9CA3AF', textTransform: 'capitalize' as const }}>{l.style}</div>
                      </div>
                      {active && (
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#111827" strokeWidth="3" strokeLinecap="round" style={{ marginLeft: 'auto' }}>
                          <polyline points="20 6 9 17 4 12"/>
                        </svg>
                      )}
                    </div>
                    <div style={{ fontSize: 11, color: '#6B7280' }}>{l.best_for?.slice(0, 3).join(', ')}</div>
                    {recommended && (
                      <div style={{ marginTop: 4, fontSize: 11, fontWeight: 600, color: '#059669', display: 'flex', alignItems: 'center', gap: 4 }}>
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="3" strokeLinecap="round"><polyline points="20 6 9 17 4 12"/></svg>
                        AI Recommended
                      </div>
                    )}
                    {website.ai_theme === l.slug && !recommended && (
                      <div style={{ marginTop: 4, fontSize: 11, fontWeight: 500, color: '#2563EB' }}>Current layout</div>
                    )}
                  </button>
                );
              })}
            </div>

            <div style={{ ...s.field, marginTop: 16 }}>
              <label style={s.label}>Pages to Build</label>
              <div style={{ display: 'flex', flexWrap: 'wrap', gap: 8 }}>
                {pageOptions.map(p => (
                  <button
                    key={p}
                    onClick={() => togglePage(p)}
                    style={{ ...s.pageChip, ...(pages.includes(p) ? { background: '#111827', color: '#fff', borderColor: '#111827' } : {}) }}
                    className="aib-chip"
                  >
                    {p.charAt(0).toUpperCase() + p.slice(1)}
                  </button>
                ))}
              </div>
            </div>

            <div style={s.navActions}>
              <button style={s.btnOutline} className="aib-btn-sec" onClick={() => setStep(1)}>Back</button>
              <button style={s.btnPrimary} className="aib-btn-pri" onClick={() => setStep(3)} disabled={!selectedLayout}>
                Review & Build
              </button>
            </div>
          </div>
        )}

        {/* Step 3: Review & Rebuild */}
        {step === 3 && (
          <div style={s.card}>
            <div style={s.cardHeader}>
              <div style={{ ...s.iconWrap, background: '#DEF7EC' }}>
                <svg width="22" height="22" fill="none" stroke="#059669" viewBox="0 0 24 24">
                  <path strokeWidth="2" strokeLinecap="round" d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                </svg>
              </div>
              <div>
                <h2 style={s.h2}>Ready to Rebuild</h2>
                <p style={s.sub}>Review your settings. This will regenerate your website with new content.</p>
              </div>
            </div>

            <div style={s.warning}>
              <svg width="16" height="16" fill="none" stroke="#92400E" viewBox="0 0 24 24" style={{ flexShrink: 0 }}>
                <path strokeWidth="2" strokeLinecap="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <span>Rebuilding will replace all current pages and content. Your URL will stay the same.</span>
            </div>

            <div style={s.reviewList}>
              <div style={s.reviewRow}>
                <span style={s.reviewLabel}>Business Name</span>
                <span style={s.reviewValue}>{businessName}</span>
              </div>
              <div style={s.reviewRow}>
                <span style={s.reviewLabel}>Business Type</span>
                <span style={{ ...s.reviewValue, textTransform: 'capitalize' }}>{businessType}</span>
              </div>
              <div style={s.reviewRow}>
                <span style={s.reviewLabel}>Layout</span>
                <span style={{ ...s.reviewValue, display: 'flex', alignItems: 'center', gap: 8 }}>
                  <span style={{ width: 14, height: 14, borderRadius: 4, background: layoutColors[selectedLayout] || '#666', display: 'inline-block' }} />
                  <span style={{ textTransform: 'capitalize' }}>{selectedLayout}</span>
                  {selectedLayout !== website.ai_theme && (
                    <span style={{ fontSize: 11, color: '#DC2626', fontWeight: 500 }}>(changed)</span>
                  )}
                </span>
              </div>
              <div style={s.reviewRow}>
                <span style={s.reviewLabel}>Pages</span>
                <span style={s.reviewValue}>{pages.map(p => p.charAt(0).toUpperCase() + p.slice(1)).join(', ')}</span>
              </div>
              <div style={{ ...s.reviewRow, borderBottom: 'none' }}>
                <span style={s.reviewLabel}>Description</span>
                <span style={{ ...s.reviewValue, fontSize: 12, color: '#6B7280', maxWidth: 350 }}>{prompt.slice(0, 120)}{prompt.length > 120 ? '...' : ''}</span>
              </div>
            </div>

            <div style={s.navActions}>
              <button style={s.btnOutline} className="aib-btn-sec" onClick={() => setStep(2)}>Back</button>
              <button style={{ ...s.btnPrimary, padding: '11px 28px' }} className="aib-btn-pri" onClick={startRebuild} disabled={building}>
                {building ? <><Spinner /> Rebuilding...</> : (
                  <>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                    Rebuild Website
                  </>
                )}
              </button>
            </div>
          </div>
        )}
      </div>

      <style>{cssStr}</style>
    </div>
  );
}

function Spinner() {
  return (
    <svg style={{ width: 14, height: 14, animation: 'aib-spin 0.6s linear infinite' }} viewBox="0 0 24 24" fill="none">
      <circle cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" opacity="0.2"/>
      <path d="M12 2a10 10 0 0110 10" stroke="currentColor" strokeWidth="4" strokeLinecap="round"/>
    </svg>
  );
}

const cssStr = `
  .aib-btn-pri:hover:not(:disabled) { background: #1F2937 !important; }
  .aib-btn-sec:hover:not(:disabled) { background: #F3F4F6 !important; }
  .aib-layout:hover { border-color: #9CA3AF !important; }
  .aib-chip:hover { border-color: #6B7280 !important; }
  @keyframes aib-spin { to { transform: rotate(360deg); } }
`;

const s: Record<string, React.CSSProperties> = {
  page: { padding: '8px 16px 40px', maxWidth: 720, margin: '0 auto' },
  inner: {},
  steps: { display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 0, marginBottom: 28 },
  stepRow: { display: 'flex', alignItems: 'center', gap: 0 },
  stepCircle: { width: 32, height: 32, borderRadius: '50%', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 13, fontWeight: 600, background: '#F3F4F6', color: '#9CA3AF', transition: 'all 0.2s' },
  stepActive: { background: '#111827', color: '#fff' },
  stepLine: { width: 48, height: 2, background: '#E5E7EB', transition: 'background 0.2s' },
  error: { padding: '10px 14px', background: '#FEE2E2', color: '#991B1B', borderRadius: 12, fontSize: 13, marginBottom: 16 },
  card: { background: '#fff', border: '1px solid #E5E7EB', borderRadius: 16, padding: 28, boxShadow: '0 1px 3px rgba(0,0,0,0.04)' },
  cardHeader: { display: 'flex', alignItems: 'flex-start', gap: 14, marginBottom: 24 },
  iconWrap: { width: 44, height: 44, borderRadius: 12, background: '#F3F4F6', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#374151', flexShrink: 0 },
  h2: { margin: '0 0 2px', fontSize: 18, fontWeight: 700, color: '#111827' },
  sub: { margin: 0, fontSize: 13, color: '#6B7280' },
  field: { marginBottom: 16 },
  label: { display: 'block', fontSize: 12, fontWeight: 600, color: '#374151', marginBottom: 6 },
  textarea: { width: '100%', padding: '12px 14px', border: '1px solid #D1D5DB', borderRadius: 12, fontSize: 13, resize: 'vertical' as const, outline: 'none', fontFamily: 'inherit', boxSizing: 'border-box' as const, minHeight: 100 },
  input: { width: '100%', padding: '10px 14px', border: '1px solid #D1D5DB', borderRadius: 10, fontSize: 13, outline: 'none', fontFamily: 'inherit', boxSizing: 'border-box' as const },
  charCount: { fontSize: 11, color: '#9CA3AF', marginTop: 4 },
  row2: { display: 'flex', gap: 12, marginBottom: 16 },
  actions: { display: 'flex', justifyContent: 'space-between', alignItems: 'center', flexWrap: 'wrap' as const, gap: 10 },
  navActions: { display: 'flex', justifyContent: 'space-between', marginTop: 24 },
  btnPrimary: { display: 'flex', alignItems: 'center', gap: 7, padding: '10px 20px', background: '#111827', color: '#fff', border: 0, borderRadius: 10, fontSize: 13, fontWeight: 600, cursor: 'pointer' },
  btnSecondary: { display: 'flex', alignItems: 'center', gap: 6, padding: '9px 16px', background: '#fff', border: '1px solid #D1D5DB', borderRadius: 10, fontSize: 13, fontWeight: 500, cursor: 'pointer', color: '#374151' },
  btnOutline: { display: 'flex', alignItems: 'center', gap: 6, padding: '10px 20px', background: '#fff', border: '1px solid #E5E7EB', borderRadius: 10, fontSize: 13, fontWeight: 500, cursor: 'pointer', color: '#374151' },
  analysisBox: { marginTop: 16, padding: 16, background: '#F9FAFB', borderRadius: 12, border: '1px solid #E5E7EB' },
  chip: { padding: '4px 12px', background: '#E5E7EB', borderRadius: 20, fontSize: 12, color: '#374151', fontWeight: 500 },
  layoutGrid: { display: 'grid', gridTemplateColumns: 'repeat(auto-fill, minmax(200px, 1fr))', gap: 12 },
  layoutCard: { padding: 14, border: '2px solid #E5E7EB', borderRadius: 14, background: '#fff', cursor: 'pointer', textAlign: 'left' as const, transition: 'all 0.15s' },
  pageChip: { padding: '7px 16px', border: '1px solid #D1D5DB', borderRadius: 20, fontSize: 13, fontWeight: 500, cursor: 'pointer', background: '#fff', color: '#374151' },
  warning: { display: 'flex', alignItems: 'center', gap: 10, padding: '12px 16px', background: '#FFFBEB', border: '1px solid #FDE68A', borderRadius: 12, fontSize: 13, color: '#92400E', marginBottom: 20 },
  reviewList: { border: '1px solid #E5E7EB', borderRadius: 12, overflow: 'hidden' },
  reviewRow: { display: 'flex', justifyContent: 'space-between', alignItems: 'center', padding: '14px 16px', borderBottom: '1px solid #F3F4F6' },
  reviewLabel: { fontSize: 13, color: '#6B7280' },
  reviewValue: { fontSize: 13, fontWeight: 600, color: '#111827' },
};
