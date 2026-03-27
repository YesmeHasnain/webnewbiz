import { useState } from 'react';

const API_BASE = 'https://api.webnewbiz.app/api';

type Tab = 'generate' | 'image' | 'export' | 'settings';

function App() {
  const [tab, setTab] = useState<Tab>('generate');
  const [prompt, setPrompt] = useState('');
  const [style, setStyle] = useState('modern');
  const [outputType, setOutputType] = useState('react');
  const [apiKey, setApiKey] = useState('');
  const [status, setStatus] = useState('');
  const [loading, setLoading] = useState(false);

  // Listen for messages from plugin code
  window.onmessage = (event) => {
    const msg = event.data.pluginMessage;
    if (!msg) return;
    if (msg.type === 'status') setStatus(msg.message);
    if (msg.type === 'done') { setStatus(msg.message); setLoading(false); }
    if (msg.type === 'error') { setStatus(msg.message); setLoading(false); }
  };

  const handleGenerate = () => {
    if (!prompt.trim()) return;
    setLoading(true);
    parent.postMessage({ pluginMessage: { type: 'generate-design', data: { prompt, style } } }, '*');
  };

  const handleExport = () => {
    setLoading(true);
    parent.postMessage({ pluginMessage: { type: 'export-to-code', data: { outputType } } }, '*');
  };

  return (
    <div style={{ fontFamily: 'Inter, system-ui, sans-serif', background: '#0a0a0f', color: '#fff', minHeight: '100vh', padding: '16px' }}>
      {/* Header */}
      <div style={{ display: 'flex', alignItems: 'center', gap: '8px', marginBottom: '16px' }}>
        <div style={{ width: 28, height: 28, borderRadius: 8, background: 'linear-gradient(135deg, #3b82f6, #8b5cf6)', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 14, fontWeight: 700 }}>W</div>
        <span style={{ fontWeight: 600, fontSize: 14 }}>WebNewBiz AI</span>
      </div>

      {/* Tabs */}
      <div style={{ display: 'flex', gap: '4px', marginBottom: '16px', background: '#12121a', borderRadius: 10, padding: 3 }}>
        {(['generate', 'image', 'export', 'settings'] as Tab[]).map(t => (
          <button key={t} onClick={() => setTab(t)} style={{ flex: 1, padding: '8px 0', borderRadius: 8, border: 'none', fontSize: 11, fontWeight: 500, cursor: 'pointer', background: tab === t ? '#1e1e2e' : 'transparent', color: tab === t ? '#3b82f6' : '#666' }}>
            {t === 'generate' ? 'Generate' : t === 'image' ? 'Image→Design' : t === 'export' ? 'Export' : 'Settings'}
          </button>
        ))}
      </div>

      {/* Generate Tab */}
      {tab === 'generate' && (
        <div>
          <label style={{ fontSize: 12, color: '#888', display: 'block', marginBottom: 6 }}>Describe your design</label>
          <textarea value={prompt} onChange={e => setPrompt(e.target.value)} placeholder="A modern SaaS landing page with dark theme, hero section, features grid, pricing cards..." rows={4} style={{ width: '100%', background: '#12121a', border: '1px solid #1e1e2e', borderRadius: 10, padding: '10px 12px', color: '#fff', fontSize: 13, resize: 'none', outline: 'none' }} />
          <label style={{ fontSize: 12, color: '#888', display: 'block', marginTop: 12, marginBottom: 6 }}>Style</label>
          <select value={style} onChange={e => setStyle(e.target.value)} style={{ width: '100%', background: '#12121a', border: '1px solid #1e1e2e', borderRadius: 10, padding: '10px 12px', color: '#fff', fontSize: 13 }}>
            <option value="modern">Modern / SaaS</option>
            <option value="minimal">Minimal / Clean</option>
            <option value="bold">Bold / Creative</option>
            <option value="elegant">Elegant / Luxury</option>
            <option value="playful">Playful / Fun</option>
          </select>
          <button onClick={handleGenerate} disabled={loading || !prompt.trim()} style={{ width: '100%', marginTop: 16, padding: '12px', borderRadius: 10, border: 'none', background: '#3b82f6', color: '#fff', fontSize: 13, fontWeight: 600, cursor: 'pointer', opacity: loading ? 0.5 : 1 }}>
            {loading ? 'Generating...' : 'Generate Design'}
          </button>
        </div>
      )}

      {/* Export Tab */}
      {tab === 'export' && (
        <div>
          <p style={{ fontSize: 12, color: '#888', marginBottom: 12 }}>Select a frame in Figma, then choose output format:</p>
          <select value={outputType} onChange={e => setOutputType(e.target.value)} style={{ width: '100%', background: '#12121a', border: '1px solid #1e1e2e', borderRadius: 10, padding: '10px 12px', color: '#fff', fontSize: 13 }}>
            <option value="react">React (JSX + Tailwind)</option>
            <option value="html">HTML/CSS</option>
            <option value="wordpress">WordPress (Elementor)</option>
            <option value="react-native">React Native</option>
          </select>
          <button onClick={handleExport} disabled={loading} style={{ width: '100%', marginTop: 16, padding: '12px', borderRadius: 10, border: 'none', background: '#3b82f6', color: '#fff', fontSize: 13, fontWeight: 600, cursor: 'pointer', opacity: loading ? 0.5 : 1 }}>
            {loading ? 'Exporting...' : 'Export to Code'}
          </button>
        </div>
      )}

      {/* Image Tab */}
      {tab === 'image' && (
        <div style={{ textAlign: 'center', padding: '40px 0' }}>
          <p style={{ fontSize: 13, color: '#888' }}>Drag & drop an image or screenshot</p>
          <p style={{ fontSize: 11, color: '#555', marginTop: 8 }}>AI will convert it to editable Figma layers</p>
        </div>
      )}

      {/* Settings Tab */}
      {tab === 'settings' && (
        <div>
          <label style={{ fontSize: 12, color: '#888', display: 'block', marginBottom: 6 }}>API Key</label>
          <input value={apiKey} onChange={e => setApiKey(e.target.value)} placeholder="Your WebNewBiz API key" style={{ width: '100%', background: '#12121a', border: '1px solid #1e1e2e', borderRadius: 10, padding: '10px 12px', color: '#fff', fontSize: 13, outline: 'none' }} />
          <p style={{ fontSize: 11, color: '#555', marginTop: 8 }}>Get your API key from webnewbiz.app/dashboard</p>
        </div>
      )}

      {/* Status */}
      {status && (
        <div style={{ marginTop: 16, padding: '10px 12px', background: '#12121a', borderRadius: 8, fontSize: 12, color: '#888' }}>
          {status}
        </div>
      )}
    </div>
  );
}

export default App;
