import { useState, useRef, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { websiteService } from '../services/website.service';
import api from '../services/api';

/* ── Types ── */
interface SitePage { title: string; slug: string; sections: { type: string; label: string }[] }
interface ChatMsg { role: 'user' | 'ai'; text: string; kind?: 'text' | 'thinking' | 'skeleton' | 'structure'; pages?: SitePage[] }

/* ── Component ── */
export default function WebsiteGenerator() {
  const nav = useNavigate();
  const [prompt, setPrompt] = useState('');
  const [msgs, setMsgs] = useState<ChatMsg[]>([]);
  const [phase, setPhase] = useState<'idle' | 'thinking' | 'done' | 'building'>('idle');
  const [structure, setStructure] = useState<SitePage[]>([]);
  const [bName, setBName] = useState('');
  const [bType, setBType] = useState('');
  const [theme, setTheme] = useState('azure');
  const [err, setErr] = useState('');
  const endRef = useRef<HTMLDivElement>(null);
  const taRef = useRef<HTMLTextAreaElement>(null);

  useEffect(() => { endRef.current?.scrollIntoView({ behavior: 'smooth' }); }, [msgs]);

  const resize = (v: string) => {
    setPrompt(v);
    const t = taRef.current;
    if (t) { t.style.height = 'auto'; t.style.height = Math.min(t.scrollHeight, 150) + 'px'; }
  };

  const submit = async () => {
    const txt = prompt.trim();
    if (txt.length < 10 || phase === 'thinking') return;
    setPrompt(''); setErr(''); setPhase('thinking');
    setRevealedPages(0); setStructure([]);
    setMsgs([{ role: 'user', text: txt }]);
    // brief delay then thinking
    await new Promise(r => setTimeout(r, 200));
    setMsgs(p => [...p, { role: 'ai', text: '', kind: 'thinking' }]);

    try {
      const { data } = await api.post('/builder/site-plan', { prompt: txt });
      setBName(data.business_name || 'My Website');
      setBType(data.business_type || 'business');
      if (data.theme) setTheme(data.theme);
      const aiTxt = `Creating a website outline that reflects your ${data.business_type || 'business'}${data.business_name ? `, ${data.business_name}` : ''}, highlighting the key aspects from your description.`;

      // show text response
      setMsgs([{ role: 'user', text: txt }, { role: 'ai', text: aiTxt, kind: 'text' }]);
      await new Promise(r => setTimeout(r, 600));

      // show skeleton
      setMsgs(p => [...p, { role: 'ai', text: '', kind: 'skeleton' }]);
      await new Promise(r => setTimeout(r, 1400));

      // show structure
      const pages = data.pages?.length ? data.pages : fallbackPages();
      setStructure(pages);
      setMsgs([
        { role: 'user', text: txt },
        { role: 'ai', text: aiTxt, kind: 'text' },
        { role: 'ai', text: '', kind: 'structure', pages },
      ]);
      setPhase('done');
    } catch (e: any) {
      setErr(e.response?.data?.message || 'Something went wrong.');
      setPhase('idle'); setMsgs([]);
    }
  };

  const build = async () => {
    setPhase('building'); setErr('');
    try {
      const res = await websiteService.generate({
        business_name: bName, business_type: bType,
        prompt: msgs[0]?.text || '', layout: theme,
        pages: structure.map(p => p.slug),
      });
      nav(`/builder/progress/${res.data.id}`);
    } catch (e: any) { setErr(e.response?.data?.message || 'Build failed.'); setPhase('done'); }
  };

  const reset = () => { setMsgs([]); setStructure([]); setPhase('idle'); setErr(''); setPrompt(''); setRevealedPages(0); };
  const chatMode = msgs.length > 0;
  const [revealedPages, setRevealedPages] = useState(0);

  // Stagger reveal pages one by one with visible delay
  useEffect(() => {
    if (phase !== 'done' || structure.length === 0) return;
    if (revealedPages >= structure.length) return;
    const timer = setTimeout(() => {
      setRevealedPages(prev => prev + 1);
    }, revealedPages === 0 ? 300 : 500); // first page 300ms, rest 500ms
    return () => clearTimeout(timer);
  }, [phase, structure.length, revealedPages]);

  return (
    <div className="wg-page">
      {/* Header */}
      <header className="wg-header">
        <div className="wg-logo">
          <svg width="22" height="22" viewBox="0 0 24 24"><path d="M12 2L22 12L12 22L2 12Z" fill="#6366f1"/></svg>
          <span>WebNewBiz</span>
        </div>
      </header>

      {/* Hero - only when idle */}
      {!chatMode && (
        <div className="wg-hero fade-up">
          <h1>AI Website Builder.</h1>
          <p>Powered by AI that plans, designs, and builds your site from prompt to production-ready.</p>
        </div>
      )}

      {/* Chat */}
      {chatMode && (
        <div className="wg-chat">
          {msgs.map((m, i) => (
            <div key={i} className={`wg-msg fade-up`} style={{ animationDelay: `${i * 0.08}s` }}>
              {m.role === 'user' && <div className="wg-user-bubble">{m.text}</div>}
              {m.role === 'ai' && m.kind === 'thinking' && (
                <div className="wg-ai-row">
                  <div className="wg-diamond"><svg width="18" height="18" viewBox="0 0 24 24"><defs><linearGradient id="dg" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stopColor="#6366f1"/><stop offset="100%" stopColor="#a78bfa"/></linearGradient></defs><path d="M12 2L22 12L12 22L2 12Z" fill="url(#dg)"/></svg></div>
                  <span className="wg-thinking-text">Thinking ...</span>
                </div>
              )}
              {m.role === 'ai' && m.kind === 'text' && <div className="wg-ai-text">{m.text}</div>}
              {m.role === 'ai' && m.kind === 'skeleton' && (
                <div className="wg-skeleton-wrap">
                  <div className="wg-skeleton-card">
                    <div className="wg-skel-tab"><span>Home</span><span className="wg-skel-plus">+</span></div>
                    <div className="wg-skel-body">
                      <div className="wg-skel-line w40" /><div className="wg-skel-line w70" /><div className="wg-skel-block" />
                    </div>
                  </div>
                </div>
              )}
              {m.role === 'ai' && m.kind === 'structure' && m.pages && (
                <div style={{ paddingTop: 8 }}>
                  <div style={{
                    background: '#f6f6f6', borderRadius: 16, padding: '32px 36px',
                    border: '1px solid #eee', marginBottom: 28,
                    animation: 'wgCardIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) both',
                  }}>
                    <div style={{ display: 'flex', justifyContent: 'flex-end', marginBottom: 24 }}>
                      <button onClick={() => nav('/builder/customize', { state: { pages: structure, businessName: bName, businessType: bType, prompt: msgs[0]?.text || '', theme } })} style={{
                        display: 'flex', alignItems: 'center', gap: 6, padding: '8px 18px',
                        borderRadius: 10, border: '1.5px solid #c7c7f8', background: '#fff',
                        color: '#6366f1', fontSize: 13, fontWeight: 600, cursor: 'pointer',
                      }}>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><path d="M4 21v-7M4 10V3M12 21v-9M12 8V3M20 21v-5M20 12V3M1 14h6M9 8h6M17 16h6"/></svg>
                        Customize structure
                      </button>
                    </div>
                    {m.pages.slice(0, revealedPages).map((pg, pi) => (
                      <div key={pg.slug} style={{
                        marginBottom: 20,
                        animation: 'wgPageIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) both',
                      }}>
                        <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 4 }}>
                          {pg.slug === 'home'
                            ? <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#555" strokeWidth="2" strokeLinecap="round"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                            : <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#555" strokeWidth="2" strokeLinecap="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                          }
                          <strong style={{ fontSize: 14, color: '#222' }}>{pg.title}</strong>
                        </div>
                        {pg.sections.map((s, si) => (
                          <div key={si} style={{
                            paddingLeft: 28, fontSize: 13, color: '#999', lineHeight: 2.1,
                          }}>{s.label}</div>
                        ))}
                      </div>
                    ))}
                  </div>
                  {revealedPages >= (m.pages?.length || 0) && (
                    <div style={{ animation: 'wgFadeUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) both' }}>
                      <p style={{ fontSize: 14, color: '#999', textAlign: 'center', margin: '0 0 18px' }}>
                        Customize your website structure if needed, then click Start Building to begin generation.
                      </p>
                      <button onClick={build} disabled={phase === 'building'} style={{
                        display: 'block', width: '100%', padding: '18px 0', borderRadius: 10,
                        background: '#3b38f1', color: '#fff', fontSize: 15, fontWeight: 600,
                        border: 'none', cursor: 'pointer', transition: 'all 0.2s',
                      }}>
                        {phase === 'building' ? 'Building...' : 'Start building'}
                      </button>
                      <button onClick={reset} style={{
                        display: 'block', width: '100%', textAlign: 'center', marginTop: 16,
                        background: 'none', border: 'none', color: '#999', fontSize: 13,
                        textDecoration: 'underline', cursor: 'pointer',
                      }}>I have a new idea. Let's start from scratch.</button>
                    </div>
                  )}
                </div>
              )}
            </div>
          ))}
          <div ref={endRef} />
        </div>
      )}

      {/* Error */}
      {err && <div className="wg-error">{err} <button onClick={() => setErr('')}>x</button></div>}

      {/* Input - hide when structure is shown */}
      {phase !== 'building' && phase !== 'done' && (
        <div className={`wg-input-area ${chatMode ? 'wg-input-bottom' : 'wg-input-center'}`}>
          <div className="wg-input-card">
            <textarea
              ref={taRef} value={prompt} onChange={e => resize(e.target.value)}
              placeholder="Describe your website in a few words..."
              rows={1}
              onKeyDown={e => { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); submit(); } }}
            />
            <div className="wg-input-bottom-row">
              <button className="wg-icon-btn wg-attach-btn" title="Attach">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#aaa" strokeWidth="2" strokeLinecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              </button>
              <div className="wg-input-right">
                <button className="wg-icon-btn" title="Voice">
                  <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#aaa" strokeWidth="2" strokeLinecap="round"><path d="M12 1a3 3 0 00-3 3v8a3 3 0 006 0V4a3 3 0 00-3-3z"/><path d="M19 10v2a7 7 0 01-14 0v-2"/><line x1="12" y1="19" x2="12" y2="23"/></svg>
                </button>
                <button className={`wg-send-btn ${prompt.trim().length >= 10 ? 'active' : ''}`} onClick={submit} disabled={prompt.trim().length < 10 || phase as string === 'thinking'} title="Generate">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#fff" strokeWidth="2.5" strokeLinecap="round"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
                </button>
              </div>
            </div>
          </div>
          {!chatMode && (
            <div className="wg-agent-tags fade-up" style={{ animationDelay: '0.3s' }}>
              <span className="wg-tag"><Diamond /> AI Website Builder Agent</span>
              <span className="wg-tag"><Diamond /> AI Editor Agent</span>
              <span className="wg-tag"><Box /> Ecommerce Agent</span>
              <span className="wg-tag dim"><Search /> SEO Agent <em>(soon)</em></span>
              <span className="wg-tag dim"><Cloud /> Hosting Agent <em>(soon)</em></span>
            </div>
          )}
        </div>
      )}

      <style>{css}</style>
    </div>
  );
}

/* ── Tiny Icons ── */
function Diamond() { return <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><path d="M12 2L22 12L12 22L2 12Z"/></svg>; }
function Box() { return <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>; }
function Search() { return <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>; }
function Cloud() { return <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><path d="M18 10h-1.26A8 8 0 109 20h9a5 5 0 000-10z"/></svg>; }

function fallbackPages(): SitePage[] {
  return [
    { title: 'Home', slug: 'home', sections: [{ type: 'hero', label: 'Hero Banner' },{ type: 'features', label: 'Key Features' },{ type: 'about_preview', label: 'About Us' },{ type: 'testimonials', label: 'Testimonials' },{ type: 'cta', label: 'Get Started' }] },
    { title: 'About', slug: 'about', sections: [{ type: 'hero', label: 'Header' },{ type: 'content', label: 'Our Story' },{ type: 'team', label: 'Our Team' }] },
    { title: 'Services', slug: 'services', sections: [{ type: 'hero', label: 'Header' },{ type: 'features', label: 'Our Services' }] },
    { title: 'Contact', slug: 'contact', sections: [{ type: 'hero', label: 'Header' },{ type: 'contact_form', label: 'Get In Touch' }] },
  ];
}

/* ── CSS ── */
const css = `
/* Page */
.wg-page { min-height:100vh; background:#fafafa; font-family:system-ui,-apple-system,sans-serif; overflow-x:hidden; }

/* Header */
.wg-header { position:fixed; top:0; left:0; right:0; height:56px; background:#fff; border-bottom:1px solid #eee; display:flex; align-items:center; padding:0 24px; z-index:100; }
.wg-logo { display:flex; align-items:center; gap:8px; font-weight:700; font-size:15px; color:#111; }

/* Hero */
.wg-hero { text-align:center; padding-top:200px; padding-bottom:40px; }
.wg-hero h1 { font-size:clamp(32px,5vw,48px); font-weight:800; color:#111; letter-spacing:-0.03em; margin:0 0 16px; line-height:1.1; }
.wg-hero p { font-size:16px; color:#999; line-height:1.6; max-width:480px; margin:0 auto; }

/* Chat */
.wg-chat { max-width:700px; margin:0 auto; padding:80px 24px 60px; }
.wg-msg { margin-bottom:20px; }
.wg-user-bubble { background:#f5f5f5; border-radius:16px; padding:16px 22px; max-width:85%; margin-left:auto; font-size:14.5px; color:#333; line-height:1.65; border:1px solid #ececec; }
.wg-ai-row { display:flex; align-items:center; gap:10px; }
.wg-diamond { animation:diamondSpin 2s ease-in-out infinite; }
.wg-thinking-text { font-size:15px; color:#999; animation:pulse 1.5s ease infinite; }
.wg-ai-text { font-size:14.5px; color:#555; line-height:1.7; margin-bottom:8px; }

/* Skeleton */
.wg-skeleton-wrap { display:flex; justify-content:center; padding:16px 0; }
.wg-skeleton-card { width:300px; background:#fff; border-radius:14px; box-shadow:0 2px 24px rgba(0,0,0,.06); overflow:hidden; }
.wg-skel-tab { display:flex; align-items:center; justify-content:space-between; padding:10px 16px; border-bottom:1px solid #f0f0f0; font-size:13px; color:#777; }
.wg-skel-plus { color:#bbb; font-size:16px; }
.wg-skel-body { padding:18px; }
.wg-skel-line { height:8px; border-radius:4px; background:linear-gradient(90deg,#f0f0f0 25%,#e4e4e4 50%,#f0f0f0 75%); background-size:200% 100%; animation:shimmer 1.5s infinite; margin-bottom:8px; }
.wg-skel-line.w40 { width:40%; }
.wg-skel-line.w70 { width:70%; }
.wg-skel-block { height:90px; border-radius:10px; background:linear-gradient(90deg,#f0f0f0 25%,#e4e4e4 50%,#f0f0f0 75%); background-size:200% 100%; animation:shimmer 1.5s infinite; }

/* Structure */
.wg-structure-wrap { padding-top:8px; }
.wg-structure-card { background:#f6f6f6; border-radius:16px; padding:32px 36px; margin-bottom:28px; border:1px solid #eee; }
.wg-structure-top { display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; }
.wg-customize-btn { display:flex; align-items:center; gap:6px; padding:8px 18px; border-radius:10px; border:1.5px solid #c7c7f8; background:#fff; color:#6366f1; font-size:13px; font-weight:600; cursor:pointer; transition:all .2s; }
.wg-customize-btn:hover { background:#f0f0ff; border-color:#6366f1; }
.wg-page-group { margin-bottom:18px; }
.wg-page-title { display:flex; align-items:center; gap:8px; margin-bottom:4px; }
.wg-page-title strong { font-size:14px; color:#222; }
.wg-section-item { padding-left:28px; font-size:13px; color:#999; line-height:2.1; letter-spacing:0.01em; }
.wg-cta-hint { font-size:14px; color:#999; text-align:center; margin:0 0 18px; }
.wg-build-btn { display:block; width:100%; padding:18px 0; border-radius:10px; background:#3b38f1; color:#fff; font-size:15px; font-weight:600; border:none; cursor:pointer; transition:all .25s; letter-spacing:0.01em; }
.wg-build-btn:hover { background:#3330e0; transform:translateY(-1px); box-shadow:0 6px 24px rgba(59,56,241,.35); }
.wg-build-btn:disabled { opacity:.6; cursor:not-allowed; transform:none; }
.wg-reset-link { display:block; width:100%; text-align:center; margin-top:16px; background:none; border:none; color:#999; font-size:13px; text-decoration:underline; cursor:pointer; }
.wg-reset-link:hover { color:#666; }
.structure-card-in { animation:cardIn .5s cubic-bezier(.16,1,.3,1) both; }
@keyframes cardIn { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:translateY(0)} }
.page-fade-in { animation:pageIn .4s ease both; }
@keyframes pageIn { from{opacity:0;transform:translateX(-10px)} to{opacity:1;transform:translateX(0)} }
.section-fade-in { animation:secIn .3s ease both; }
@keyframes secIn { from{opacity:0} to{opacity:1} }
.cta-animate { animation:fadeUp .4s ease .5s both; }
.btn-animate { animation:fadeUp .4s ease .6s both; }
@keyframes fadeUp { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }

/* Input Area */
.wg-input-area { position:fixed; left:0; right:0; z-index:50; padding:0 24px 28px; }
.wg-input-area::before { content:''; position:absolute; top:-40px; left:0; right:0; height:40px; background:linear-gradient(transparent,#fafafa); pointer-events:none; }
.wg-input-center { bottom:50%; transform:translateY(50%); position:absolute; left:0; right:0; }
.wg-input-bottom { bottom:0; }
.wg-input-card { max-width:640px; margin:0 auto; background:#fff; border-radius:22px; border:1px solid #e4e4e4; padding:14px 18px; box-shadow:0 4px 30px rgba(0,0,0,.06); transition:box-shadow .3s, border-color .3s; }
.wg-input-card:focus-within { border-color:#c7c7f8; box-shadow:0 4px 30px rgba(99,102,241,.1); }
.wg-input-card textarea { width:100%; border:none; resize:none; font-size:15px; line-height:1.6; font-family:inherit; background:transparent; padding:2px 0; min-height:24px; max-height:150px; outline:none; color:#333; }
.wg-input-card textarea::placeholder { color:#bbb; }
.wg-input-bottom-row { display:flex; align-items:center; justify-content:space-between; margin-top:10px; }
.wg-input-right { display:flex; align-items:center; gap:6px; }
.wg-icon-btn { width:36px; height:36px; border-radius:50%; border:1px solid #e8e8e8; background:#fff; display:flex; align-items:center; justify-content:center; cursor:pointer; transition:all .2s; }
.wg-icon-btn:hover { background:#f5f5f5; border-color:#ccc; }
.wg-attach-btn { border:1px solid #e0e0e0; }
.wg-send-btn { width:36px; height:36px; border-radius:50%; border:none; background:#ddd; display:flex; align-items:center; justify-content:center; cursor:not-allowed; transition:all .25s; }
.wg-send-btn.active { background:#6366f1; cursor:pointer; box-shadow:0 2px 10px rgba(99,102,241,.3); }
.wg-send-btn.active:hover { background:#4f46e5; transform:scale(1.05); }

/* Agent Tags */
.wg-agent-tags { display:flex; flex-wrap:wrap; justify-content:center; gap:12px; max-width:640px; margin:18px auto 0; }
.wg-tag { display:flex; align-items:center; gap:6px; font-size:13px; color:#777; font-weight:500; }
.wg-tag.dim { color:#bbb; }
.wg-tag em { font-style:normal; font-size:11px; color:#ccc; margin-left:2px; }

/* Error */
.wg-error { position:fixed; bottom:100px; left:50%; transform:translateX(-50%); background:#fef2f2; border:1px solid #fecaca; color:#b91c1c; padding:10px 18px; border-radius:12px; font-size:14px; z-index:60; display:flex; align-items:center; gap:8px; animation:fadeIn .3s; }
.wg-error button { background:none; border:none; cursor:pointer; color:#b91c1c; font-weight:700; font-size:16px; }

/* Animations */
@keyframes fadeIn { from{opacity:0} to{opacity:1} }
@keyframes fade-up { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
.fade-up { animation:fade-up .5s ease both; }
@keyframes diamondSpin { 0%{transform:rotate(0)} 50%{transform:rotate(180deg) scale(.9)} 100%{transform:rotate(360deg)} }
@keyframes shimmer { 0%{background-position:-200% 0} 100%{background-position:200% 0} }
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.4} }
@keyframes wgCardIn { 0%{opacity:0;transform:translateY(30px) scale(0.98)} 100%{opacity:1;transform:translateY(0) scale(1)} }
@keyframes wgPageIn { 0%{opacity:0;transform:translateX(-20px)} 100%{opacity:1;transform:translateX(0)} }
@keyframes wgFadeUp { 0%{opacity:0;transform:translateY(16px)} 100%{opacity:1;transform:translateY(0)} }
`;
