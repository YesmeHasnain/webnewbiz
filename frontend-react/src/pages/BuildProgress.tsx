import { useState, useEffect, useRef } from 'react';
import { useParams } from 'react-router-dom';
import { websiteService } from '../services/website.service';

/* ── Types ── */
type Phase = 'chat' | 'split' | 'complete';
type RightPanel = 'building' | 'preview' | 'diff';

interface BuildStep { label: string; done: boolean; active: boolean }

/* ── Build step labels mapped from backend ── */
const STEP_MAP: Record<string, string> = {
  wordpress_install: 'Setting up the website structure',
  ai_content: 'Generating AI content',
  images: 'Downloading stock images',
  building_pages: 'Creating page layouts',
  header_footer: 'Building header & footer',
  plugins: 'Finalizing site configuration',
  complete: 'Completing build',
};

export default function BuildProgress() {
  const { id } = useParams<{ id: string }>();
  const [status, setStatus] = useState('pending');
  const [buildStep, setBuildStep] = useState('queued');
  const [buildLog, setBuildLog] = useState<string[]>([]);
  const [siteUrl, setSiteUrl] = useState('');
  const [siteName, setSiteName] = useState('');
  const [phase, setPhase] = useState<Phase>('chat');
  const [rightPanel, setRightPanel] = useState<RightPanel>('building');
  const [visibleSteps, setVisibleSteps] = useState(0);
  const [steps, setSteps] = useState<BuildStep[]>([]);
  const pollRef = useRef<ReturnType<typeof setInterval> | null>(null);
  const chatEndRef = useRef<HTMLDivElement>(null);

  // Poll backend for status
  useEffect(() => {
    if (!id) return;
    const check = () => {
      websiteService.getStatus(Number(id)).then(res => {
        const d = res.data;
        setStatus(d.status);
        setBuildStep(d.build_step || 'queued');
        setBuildLog(d.build_log || []);
        setSiteUrl(d.url || '');
        setSiteName(d.name || 'Your Website');

        // Build steps from log
        const newSteps: BuildStep[] = (d.build_log || []).map((log: string, i: number) => ({
          label: log,
          done: i < (d.build_log || []).length - 1 || d.status === 'active',
          active: i === (d.build_log || []).length - 1 && d.status !== 'active' && d.status !== 'failed',
        }));
        setSteps(newSteps);

        // Phase transitions
        if (newSteps.length >= 3 && phase === 'chat') setPhase('split');
        if (d.status === 'active' || d.status === 'failed') {
          setPhase('complete');
          if (d.status === 'active') setRightPanel('preview');
          if (pollRef.current) { clearInterval(pollRef.current); pollRef.current = null; }
        }
      }).catch(() => {});
    };
    check();
    pollRef.current = setInterval(check, 3000);
    return () => { if (pollRef.current) clearInterval(pollRef.current); };
  }, [id]);

  // Stagger step reveal
  useEffect(() => {
    if (visibleSteps < steps.length) {
      const t = setTimeout(() => setVisibleSteps(v => v + 1), 400);
      return () => clearTimeout(t);
    }
  }, [steps.length, visibleSteps]);

  // Auto scroll chat
  useEffect(() => {
    chatEndRef.current?.scrollIntoView({ behavior: 'smooth' });
  }, [visibleSteps, phase]);

  const isSplit = phase === 'split' || phase === 'complete';

  return (
    <div className="bp-root">
      {/* Top Bar - only in split mode */}
      {isSplit && (
        <div className="bp-topbar">
          <div className="bp-topbar-left">
            <svg width="22" height="22" viewBox="0 0 24 24"><path d="M12 2L22 12L12 22L2 12Z" fill="#6366f1"/></svg>
            <span className="bp-site-name">{siteName}</span>
          </div>
          <div className="bp-topbar-center">
            <button className="bp-tb-btn" title="Refresh"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 11-2.12-9.36L23 10"/></svg></button>
            <button className="bp-tb-btn" title="Split view"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="12" y1="3" x2="12" y2="21"/></svg></button>
            <button className="bp-code-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg> Code editor</button>
          </div>
          <div className="bp-topbar-right">
            <button className="bp-publish-btn">Publish</button>
          </div>
        </div>
      )}

      <div className={`bp-body ${isSplit ? 'bp-split' : 'bp-full'}`}>
        {/* Left Panel / Full Chat */}
        <div className={`bp-chat ${isSplit ? 'bp-chat-side' : 'bp-chat-full'}`}>
          <div className="bp-chat-scroll">
            {/* User message */}
            <div className="bp-user-msg">Start building my website</div>

            {/* AI text */}
            {steps.length > 0 && (
              <p className="bp-ai-text">I'm starting to build your {siteName} website.</p>
            )}

            {/* Working indicator */}
            {status !== 'active' && status !== 'failed' && steps.length > 0 && (
              <div className="bp-working-row">
                <div className="bp-diamond-spin"><svg width="20" height="20" viewBox="0 0 24 24"><defs><linearGradient id="bpg" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stopColor="#6366f1"/><stop offset="100%" stopColor="#a78bfa"/></linearGradient></defs><path d="M12 2L22 12L12 22L2 12Z" fill="url(#bpg)"/></svg></div>
                <span>Working</span>
              </div>
            )}

            {/* Steps */}
            <div className="bp-steps">
              {steps.slice(0, visibleSteps).map((s, i) => (
                <div key={i} className="bp-step fade-in">
                  {s.done ? (
                    <svg className="bp-check" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#22c55e" strokeWidth="3" strokeLinecap="round"><polyline points="20 6 9 17 4 12"/></svg>
                  ) : s.active ? (
                    <div className="bp-spinner" />
                  ) : (
                    <div className="bp-circle" />
                  )}
                  <span className={s.done ? 'bp-step-done' : ''}>{s.label}</span>
                </div>
              ))}
            </div>

            {/* Complete summary */}
            {status === 'active' && (
              <div className="bp-complete fade-in">
                <p className="bp-ai-text">I've successfully created the {siteName} website.</p>
                <div className="bp-mod-card">
                  <div className="bp-mod-header">
                    <span className="bp-mod-label">Latest Modification</span>
                    <button className="bp-restore-btn">↺ Restore</button>
                  </div>
                  <p className="bp-mod-desc">Built {siteName} website.</p>
                  <div className="bp-mod-actions">
                    <button className={`bp-preview-btn ${rightPanel === 'preview' ? 'active' : ''}`} onClick={() => setRightPanel('preview')}>
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                      Preview
                    </button>
                    <button className={`bp-diff-btn ${rightPanel === 'diff' ? 'active' : ''}`} onClick={() => setRightPanel('diff')}>
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                      Code diff
                    </button>
                  </div>
                </div>
                <div className="bp-feedback">
                  <button title="Good">👍</button>
                  <button title="Bad">👎</button>
                  <span className="bp-time">{new Date().toLocaleDateString()} {new Date().toLocaleTimeString()}</span>
                </div>
              </div>
            )}

            {/* Failed */}
            {status === 'failed' && (
              <div className="bp-failed fade-in">
                <p>Build failed. Please try again.</p>
              </div>
            )}

            <div ref={chatEndRef} />
          </div>

          {/* Bottom input */}
          {isSplit && (
            <div className="bp-input-bar">
              <input placeholder="Ask WebNewBiz AI..." readOnly />
              <div className="bp-input-actions">
                <button className="bp-input-icon">+</button>
                <button className="bp-input-icon bp-edit-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> Edit</button>
                <button className="bp-input-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><path d="M12 1a3 3 0 00-3 3v8a3 3 0 006 0V4a3 3 0 00-3-3z"/><path d="M19 10v2a7 7 0 01-14 0v-2"/></svg></button>
                <button className="bp-send-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" strokeWidth="2.5" strokeLinecap="round"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg></button>
              </div>
            </div>
          )}
        </div>

        {/* Right Panel */}
        {isSplit && (
          <div className="bp-right">
            {rightPanel === 'building' && (
              <div className="bp-building-panel">
                <div className="bp-diamond-large">
                  <svg width="40" height="40" viewBox="0 0 24 24"><defs><linearGradient id="bplg" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stopColor="#818cf8"/><stop offset="100%" stopColor="#6366f1"/></linearGradient></defs><path d="M12 2L22 12L12 22L2 12Z" fill="url(#bplg)"/><path d="M12 7l3 5-3 5-3-5z" fill="#fff" opacity="0.8"/></svg>
                </div>
                <h2>Building your site may take 4–5 minutes.</h2>
                <p>Once ready, your site preview will automatically appear on this screen.</p>
              </div>
            )}
            {rightPanel === 'preview' && siteUrl && (
              <div className="bp-preview-panel">
                <iframe src={siteUrl} title="Site Preview" />
              </div>
            )}
            {rightPanel === 'preview' && !siteUrl && (
              <div className="bp-building-panel">
                <h2>Setting up preview</h2>
              </div>
            )}
            {rightPanel === 'diff' && (
              <div className="bp-diff-panel">
                <div className="bp-diff-header">
                  <span>📄 Diff</span>
                  <button onClick={() => setRightPanel('preview')}>Exit</button>
                </div>
                <div className="bp-diff-list">
                  {buildLog.map((log, i) => (
                    <div key={i} className="bp-diff-file">
                      <span>📄 {log}</span>
                      <span className="bp-add-badge">add</span>
                    </div>
                  ))}
                </div>
              </div>
            )}
          </div>
        )}
      </div>

      <style>{css}</style>
    </div>
  );
}

const css = `
.bp-root { min-height:100vh; background:#fff; font-family:system-ui,-apple-system,sans-serif; display:flex; flex-direction:column; }

/* Top Bar */
.bp-topbar { height:52px; border-bottom:1px solid #eee; display:flex; align-items:center; justify-content:space-between; padding:0 20px; flex-shrink:0; }
.bp-topbar-left { display:flex; align-items:center; gap:10px; }
.bp-site-name { font-size:14px; font-weight:600; color:#111; }
.bp-topbar-center { display:flex; align-items:center; gap:6px; }
.bp-tb-btn { width:32px; height:32px; border:none; background:none; cursor:pointer; border-radius:6px; display:flex; align-items:center; justify-content:center; color:#888; }
.bp-tb-btn:hover { background:#f0f0f0; }
.bp-code-btn { display:flex; align-items:center; gap:5px; padding:6px 14px; border-radius:8px; border:1px solid #e0e0e0; background:#fff; font-size:12px; color:#555; cursor:pointer; }
.bp-topbar-right { display:flex; align-items:center; gap:10px; }
.bp-publish-btn { padding:8px 20px; border-radius:8px; background:#6366f1; color:#fff; font-size:13px; font-weight:600; border:none; cursor:pointer; }

/* Body */
.bp-body { flex:1; display:flex; }
.bp-full { flex-direction:column; }
.bp-split { flex-direction:row; }

/* Chat */
.bp-chat { display:flex; flex-direction:column; }
.bp-chat-full { max-width:700px; margin:0 auto; padding:40px 24px; width:100%; }
.bp-chat-side { width:420px; border-right:1px solid #eee; flex-shrink:0; }
.bp-chat-scroll { flex:1; overflow-y:auto; padding:24px; }

/* User message */
.bp-user-msg { background:#f3f4f6; border-radius:16px; padding:12px 18px; max-width:80%; margin-left:auto; font-size:14px; color:#333; margin-bottom:20px; }

/* AI text */
.bp-ai-text { font-size:14px; color:#333; line-height:1.6; margin:0 0 16px; }

/* Working */
.bp-working-row { display:flex; align-items:center; gap:8px; margin-bottom:12px; }
.bp-working-row span { font-size:14px; color:#444; font-weight:500; }
.bp-diamond-spin { animation:diamondSpin 2s ease-in-out infinite; }

/* Steps */
.bp-steps { padding-left:8px; border-left:2px solid #eee; margin-left:12px; }
.bp-step { display:flex; align-items:center; gap:10px; padding:6px 0; font-size:13px; color:#888; }
.bp-step-done { color:#888; }
.bp-check { flex-shrink:0; }
.bp-spinner { width:14px; height:14px; border:2px solid #ddd; border-top-color:#6366f1; border-radius:50%; animation:spin .8s linear infinite; flex-shrink:0; }
.bp-circle { width:14px; height:14px; border:1.5px solid #ddd; border-radius:50%; flex-shrink:0; }

/* Complete */
.bp-complete { margin-top:20px; }
.bp-mod-card { border:2px dashed #c7c7f8; border-radius:14px; padding:16px; margin:16px 0; }
.bp-mod-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:6px; }
.bp-mod-label { font-size:12px; color:#f59e0b; font-weight:600; }
.bp-restore-btn { background:none; border:none; color:#888; font-size:12px; cursor:pointer; }
.bp-mod-desc { font-size:14px; color:#333; margin:0 0 12px; }
.bp-mod-actions { display:flex; gap:8px; }
.bp-preview-btn, .bp-diff-btn { display:flex; align-items:center; gap:5px; padding:8px 16px; border-radius:10px; font-size:13px; font-weight:600; cursor:pointer; transition:all .15s; }
.bp-preview-btn { background:#fff; border:1px solid #ddd; color:#555; }
.bp-preview-btn.active { background:#6366f1; color:#fff; border-color:#6366f1; }
.bp-diff-btn { background:#6366f1; color:#fff; border:none; }
.bp-diff-btn.active { background:#4f46e5; }
.bp-feedback { display:flex; align-items:center; gap:8px; margin-top:12px; }
.bp-feedback button { background:none; border:none; cursor:pointer; font-size:16px; padding:4px; }
.bp-time { font-size:11px; color:#bbb; margin-left:auto; }

/* Failed */
.bp-failed { color:#dc2626; font-size:14px; }

/* Input Bar */
.bp-input-bar { border-top:1px solid #eee; padding:12px 16px; }
.bp-input-bar input { width:100%; padding:10px 14px; border-radius:12px; border:1px solid #e8e8e8; font-size:13px; outline:none; color:#333; margin-bottom:8px; }
.bp-input-actions { display:flex; align-items:center; gap:6px; }
.bp-input-icon { width:32px; height:32px; border-radius:50%; border:1px solid #e8e8e8; background:#fff; display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:14px; color:#999; }
.bp-edit-btn { width:auto; padding:0 12px; gap:4px; border-radius:8px; font-size:12px; }
.bp-send-btn { width:32px; height:32px; border-radius:50%; background:#6366f1; border:none; display:flex; align-items:center; justify-content:center; cursor:pointer; margin-left:auto; }

/* Right Panel */
.bp-right { flex:1; background:#f8f8f8; border-left:1px solid #eee; display:flex; flex-direction:column; }

/* Building Panel */
.bp-building-panel { flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center; padding:40px; }
.bp-diamond-large { width:64px; height:64px; background:linear-gradient(135deg,#818cf8,#6366f1); border-radius:50%; display:flex; align-items:center; justify-content:center; margin-bottom:24px; animation:pulse 2s ease infinite; box-shadow:0 8px 30px rgba(99,102,241,.3); }
.bp-building-panel h2 { font-size:22px; font-weight:700; color:#111; margin:0 0 8px; }
.bp-building-panel p { font-size:14px; color:#999; margin:0; }

/* Preview */
.bp-preview-panel { flex:1; }
.bp-preview-panel iframe { width:100%; height:100%; border:none; }

/* Diff */
.bp-diff-panel { flex:1; overflow-y:auto; }
.bp-diff-header { display:flex; justify-content:space-between; align-items:center; padding:16px 20px; border-bottom:1px solid #eee; }
.bp-diff-header span { font-size:14px; font-weight:600; color:#333; }
.bp-diff-header button { background:none; border:none; color:#888; cursor:pointer; font-size:13px; }
.bp-diff-list { padding:8px 0; }
.bp-diff-file { display:flex; align-items:center; justify-content:space-between; padding:14px 20px; border-bottom:1px solid #f0f0f0; font-size:13px; color:#333; }
.bp-add-badge { background:#fef3c7; color:#d97706; font-size:11px; font-weight:600; padding:2px 8px; border-radius:4px; }

/* Animations */
@keyframes diamondSpin { 0%{transform:rotate(0)} 50%{transform:rotate(180deg) scale(.9)} 100%{transform:rotate(360deg)} }
@keyframes spin { to{transform:rotate(360deg)} }
@keyframes pulse { 0%,100%{transform:scale(1);opacity:1} 50%{transform:scale(1.05);opacity:.8} }
.fade-in { animation:fadeIn .4s ease both; }
@keyframes fadeIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
`;
