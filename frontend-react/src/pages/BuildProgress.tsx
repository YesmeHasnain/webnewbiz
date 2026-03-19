import { useState, useEffect, useRef } from 'react';
import { useParams, Link } from 'react-router-dom';
import Navbar from '../components/Navbar';
import { websiteService } from '../services/website.service';

const steps = [
  { key: 'wordpress_install', label: 'Installing WordPress', icon: '🔧' },
  { key: 'ai_content', label: 'Generating AI Content', icon: '✨' },
  { key: 'images', label: 'Downloading Images', icon: '🖼️' },
  { key: 'building_pages', label: 'Building Pages', icon: '📄' },
  { key: 'header_footer', label: 'Creating Header & Footer', icon: '🎨' },
  { key: 'plugins', label: 'Finalizing Site', icon: '⚙️' },
  { key: 'complete', label: 'Complete!', icon: '🚀' },
];

const funFacts = [
  'Setting up your digital storefront...',
  'AI is crafting your brand voice...',
  'Picking the perfect color palette...',
  'Optimizing for lightning-fast speed...',
  'Making it mobile-responsive...',
  'Adding the finishing touches...',
  'Almost there, hang tight...',
];

export default function BuildProgress() {
  const { id } = useParams<{ id: string }>();
  const [status, setStatus] = useState('pending');
  const [buildStep, setBuildStep] = useState('queued');
  const [buildLog, setBuildLog] = useState<string[]>([]);
  const [siteUrl, setSiteUrl] = useState('');
  const [wpAdminUrl, setWpAdminUrl] = useState('');
  const [websiteName, setWebsiteName] = useState('');
  const [factIdx, setFactIdx] = useState(0);
  const [visibleLogs, setVisibleLogs] = useState(0);
  const pollRef = useRef<ReturnType<typeof setInterval> | null>(null);
  const logEndRef = useRef<HTMLDivElement>(null);
  const prevLogLen = useRef(0);

  useEffect(() => {
    if (!id) return;
    const websiteId = Number(id);

    const check = () => {
      websiteService.getStatus(websiteId).then((res) => {
        const data = res.data;
        setStatus(data.status);
        setBuildStep(data.build_step || 'queued');
        setBuildLog(data.build_log || []);
        setSiteUrl(data.url || '');
        setWpAdminUrl(data.wp_admin_url || '');
        setWebsiteName(data.name || '');

        if (data.status === 'active' || data.status === 'failed') {
          if (pollRef.current) {
            clearInterval(pollRef.current);
            pollRef.current = null;
          }
        }
      }).catch(() => {});
    };

    check();
    pollRef.current = setInterval(check, 3000);
    return () => { if (pollRef.current) clearInterval(pollRef.current); };
  }, [id]);

  // Animate log entries appearing one by one
  useEffect(() => {
    if (buildLog.length > prevLogLen.current) {
      const newCount = buildLog.length;
      let i = prevLogLen.current;
      const timer = setInterval(() => {
        i++;
        setVisibleLogs(i);
        if (i >= newCount) clearInterval(timer);
      }, 120);
      prevLogLen.current = newCount;
      return () => clearInterval(timer);
    }
  }, [buildLog]);

  // Auto-scroll log
  useEffect(() => {
    logEndRef.current?.scrollIntoView({ behavior: 'smooth' });
  }, [visibleLogs]);

  // Rotate fun facts
  useEffect(() => {
    if (status === 'active' || status === 'failed') return;
    const t = setInterval(() => setFactIdx(i => (i + 1) % funFacts.length), 3500);
    return () => clearInterval(t);
  }, [status]);

  const stepOrder = steps.map(s => s.key);
  const currentIdx = stepOrder.indexOf(buildStep);
  const progress = status === 'active' ? 100 : Math.max(0, ((currentIdx + 1) / steps.length) * 100);

  const isStepDone = (key: string) => {
    const idx = stepOrder.indexOf(key);
    return idx < currentIdx || status === 'active';
  };
  const isStepActive = (key: string) => buildStep === key && status !== 'active' && status !== 'failed';

  return (
    <div style={{ minHeight: '100vh', background: '#fafafa' }}>
      <Navbar forceDark />
      <main style={{ paddingTop: 100, paddingBottom: 60, maxWidth: 640, margin: '0 auto', padding: '100px 24px 60px' }}>

        {/* Success */}
        {status === 'active' && (
          <div style={{ textAlign: 'center', animation: 'fadeUp .6s ease' }}>
            <div style={{
              width: 88, height: 88, borderRadius: 24, background: 'linear-gradient(135deg, #d1fae5, #a7f3d0)',
              display: 'flex', alignItems: 'center', justifyContent: 'center', margin: '0 auto 24px',
              boxShadow: '0 8px 32px rgba(16,185,129,.2)',
            }}>
              <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#059669" strokeWidth="2.5" strokeLinecap="round"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <h1 style={{ fontSize: 32, fontWeight: 800, letterSpacing: '-0.02em', margin: '0 0 8px', color: '#111' }}>Your Website is Ready!</h1>
            <p style={{ color: '#888', fontSize: 16, margin: '0 0 32px' }}>{websiteName} has been built successfully</p>
            <div style={{ display: 'flex', gap: 12, justifyContent: 'center', flexWrap: 'wrap' }}>
              {siteUrl && (
                <>
                  <a href={siteUrl} target="_blank" rel="noopener noreferrer" style={btnStyle('#111', '#fff')}>Visit Website →</a>
                  <a href={wpAdminUrl || siteUrl + '/wp-admin/'} target="_blank" rel="noopener noreferrer" style={btnStyle('transparent', '#111', '1px solid #ddd')}>Open WordPress</a>
                </>
              )}
              <Link to="/dashboard" style={btnStyle('transparent', '#111', '1px solid #ddd')}>Dashboard</Link>
            </div>
          </div>
        )}

        {/* Failed */}
        {status === 'failed' && (
          <div style={{ textAlign: 'center', animation: 'fadeUp .6s ease' }}>
            <div style={{
              width: 88, height: 88, borderRadius: 24, background: 'linear-gradient(135deg, #fee2e2, #fecaca)',
              display: 'flex', alignItems: 'center', justifyContent: 'center', margin: '0 auto 24px',
            }}>
              <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#DC2626" strokeWidth="2.5" strokeLinecap="round">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
              </svg>
            </div>
            <h1 style={{ fontSize: 32, fontWeight: 800, letterSpacing: '-0.02em', margin: '0 0 8px', color: '#111' }}>Build Failed</h1>
            <p style={{ color: '#888', fontSize: 16, margin: '0 0 32px' }}>Something went wrong. Please try again.</p>
            <Link to="/dashboard" style={btnStyle('#111', '#fff')}>Back to Dashboard</Link>
          </div>
        )}

        {/* Building */}
        {status !== 'active' && status !== 'failed' && (
          <div style={{ textAlign: 'center', marginBottom: 32 }}>
            {/* Animated orb */}
            <div style={{
              width: 80, height: 80, margin: '0 auto 24px', position: 'relative',
            }}>
              <div style={{
                width: 80, height: 80, borderRadius: '50%',
                background: 'conic-gradient(from 0deg, #111 0%, transparent 60%, #111 100%)',
                animation: 'spin 1.2s linear infinite',
              }} />
              <div style={{
                position: 'absolute', top: 4, left: 4, right: 4, bottom: 4,
                borderRadius: '50%', background: '#fafafa',
                display: 'flex', alignItems: 'center', justifyContent: 'center',
                fontSize: 28,
              }}>
                {steps[Math.min(currentIdx, steps.length - 1)]?.icon || '🔧'}
              </div>
            </div>
            <h1 style={{ fontSize: 28, fontWeight: 800, letterSpacing: '-0.02em', margin: '0 0 6px', color: '#111' }}>Building Your Website</h1>
            <p style={{
              color: '#999', fontSize: 15, margin: 0, height: 22, overflow: 'hidden',
              transition: 'opacity .4s', opacity: 1,
            }} key={factIdx}>
              {funFacts[factIdx]}
            </p>
          </div>
        )}

        {/* Progress Bar */}
        <div style={{
          height: 6, background: '#eee', borderRadius: 3, margin: '0 0 32px', overflow: 'hidden',
        }}>
          <div style={{
            height: '100%', background: status === 'active' ? '#059669' : status === 'failed' ? '#DC2626' : '#111',
            borderRadius: 3, width: `${progress}%`,
            transition: 'width .8s cubic-bezier(.4,0,.2,1)',
          }} />
        </div>

        {/* Steps */}
        <div style={{
          background: '#fff', borderRadius: 16, border: '1px solid #f0f0f0',
          boxShadow: '0 1px 3px rgba(0,0,0,.04)', overflow: 'hidden',
        }}>
          {steps.map((s, idx) => {
            const done = isStepDone(s.key);
            const active = isStepActive(s.key);
            return (
              <div key={s.key} style={{
                display: 'flex', alignItems: 'center', gap: 14, padding: '14px 20px',
                borderBottom: idx < steps.length - 1 ? '1px solid #f5f5f5' : 'none',
                background: active ? '#fffbeb' : 'transparent',
                transition: 'background .3s',
              }}>
                <div style={{
                  width: 32, height: 32, borderRadius: '50%', flexShrink: 0,
                  display: 'flex', alignItems: 'center', justifyContent: 'center',
                  background: done ? '#d1fae5' : active ? '#fef3c7' : '#f5f5f5',
                  transition: 'all .4s',
                }}>
                  {done ? (
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#059669" strokeWidth="3" strokeLinecap="round"><polyline points="20 6 9 17 4 12"/></svg>
                  ) : active ? (
                    <div style={{
                      width: 14, height: 14, border: '2px solid #d97706', borderTopColor: 'transparent',
                      borderRadius: '50%', animation: 'spin .8s linear infinite',
                    }} />
                  ) : (
                    <div style={{ width: 6, height: 6, borderRadius: '50%', background: '#d4d4d4' }} />
                  )}
                </div>
                <span style={{
                  fontSize: 14, fontWeight: done || active ? 600 : 400,
                  color: done ? '#065f46' : active ? '#92400e' : '#aaa',
                  transition: 'color .3s',
                }}>
                  {s.label}
                </span>
                {active && (
                  <span style={{
                    marginLeft: 'auto', fontSize: 11, color: '#d97706',
                    fontWeight: 600, letterSpacing: '.5px', textTransform: 'uppercase',
                    animation: 'pulse 1.5s ease infinite',
                  }}>
                    In Progress
                  </span>
                )}
                {done && (
                  <svg style={{ marginLeft: 'auto' }} width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#059669" strokeWidth="2" strokeLinecap="round"><polyline points="20 6 9 17 4 12"/></svg>
                )}
              </div>
            );
          })}
        </div>

        {/* Build Log — animated terminal */}
        {buildLog.length > 0 && (
          <div style={{
            marginTop: 24, borderRadius: 14, overflow: 'hidden',
            background: '#1a1a2e', border: '1px solid #2a2a3e',
            boxShadow: '0 4px 24px rgba(0,0,0,.15)',
          }}>
            {/* Terminal header */}
            <div style={{
              padding: '10px 16px', background: '#12121f',
              display: 'flex', alignItems: 'center', gap: 8,
            }}>
              <div style={{ width: 12, height: 12, borderRadius: '50%', background: '#ff5f57' }} />
              <div style={{ width: 12, height: 12, borderRadius: '50%', background: '#febc2e' }} />
              <div style={{ width: 12, height: 12, borderRadius: '50%', background: '#28c840' }} />
              <span style={{ marginLeft: 12, fontSize: 12, color: '#666', fontFamily: 'monospace' }}>build-log</span>
            </div>
            {/* Terminal body */}
            <div style={{
              padding: '16px 20px', maxHeight: 280, overflowY: 'auto',
              fontFamily: "'JetBrains Mono', 'Fira Code', monospace", fontSize: 13, lineHeight: 1.8,
            }}>
              {buildLog.slice(0, visibleLogs).map((log, idx) => (
                <div key={idx} style={{
                  color: log.includes('ready') || log.includes('complete') || log.includes('success')
                    ? '#4ade80'
                    : log.includes('error') || log.includes('fail')
                    ? '#f87171'
                    : log.includes('...')
                    ? '#fbbf24'
                    : '#94a3b8',
                  animation: 'typeIn .3s ease',
                  display: 'flex', gap: 10,
                }}>
                  <span style={{ color: '#4ade80', userSelect: 'none' }}>▸</span>
                  {log}
                </div>
              ))}
              {status !== 'active' && status !== 'failed' && (
                <div style={{ color: '#4ade80', animation: 'blink 1s step-end infinite' }}>▍</div>
              )}
              <div ref={logEndRef} />
            </div>
          </div>
        )}
      </main>

      <style>{`
        @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: none; } }
        @keyframes spin { to { transform: rotate(360deg); } }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .4; } }
        @keyframes typeIn { from { opacity: 0; transform: translateX(-8px); } to { opacity: 1; transform: none; } }
        @keyframes blink { 50% { opacity: 0; } }
      `}</style>
    </div>
  );
}

function btnStyle(bg: string, color: string, border?: string): React.CSSProperties {
  return {
    display: 'inline-flex', alignItems: 'center', gap: 6,
    padding: '12px 28px', borderRadius: 12,
    background: bg, color, border: border || 'none',
    fontSize: 14, fontWeight: 600, textDecoration: 'none',
    cursor: 'pointer', transition: 'all .2s',
  };
}
