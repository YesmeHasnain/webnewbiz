import { useState, useEffect, useCallback, useRef } from 'react';
import { useParams } from 'react-router-dom';
import { wpManagerService } from '../../services/wp-manager.service';

type Tab = 'current' | 'upload' | 'generate';

interface GenerateForm {
  business_name: string;
  style: string;
  colors: string;
}

export default function LogoAssets() {
  const { id } = useParams<{ id: string }>();
  const websiteId = Number(id);

  const [tab, setTab] = useState<Tab>('current');
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');

  // Current logo
  const [logoUrl, setLogoUrl] = useState('');
  const [siteName, setSiteName] = useState('');

  // Upload
  const [uploadFile, setUploadFile] = useState<File | null>(null);
  const [uploadPreview, setUploadPreview] = useState('');
  const [uploading, setUploading] = useState(false);
  const fileInputRef = useRef<HTMLInputElement>(null);

  // Generate
  const [genForm, setGenForm] = useState<GenerateForm>({ business_name: '', style: 'modern minimal', colors: '' });
  const [generating, setGenerating] = useState(false);
  const [svgPreview, setSvgPreview] = useState('');

  // Removing
  const [removing, setRemoving] = useState(false);

  const fetchLogo = useCallback(async () => {
    setLoading(true);
    setError('');
    try {
      const res = await wpManagerService.getLogo(websiteId);
      setLogoUrl(res.data.data.logo_url || '');
      setSiteName(res.data.data.site_name || '');
      if (res.data.data.site_name && !genForm.business_name) {
        setGenForm(prev => ({ ...prev, business_name: res.data.data.site_name }));
      }
    } catch (e: any) {
      setError(e.response?.data?.error || e.message || 'Failed to load logo');
    } finally {
      setLoading(false);
    }
  }, [websiteId]);

  useEffect(() => { fetchLogo(); }, [fetchLogo]);

  // Clear messages after 4s
  useEffect(() => {
    if (success) {
      const t = setTimeout(() => setSuccess(''), 4000);
      return () => clearTimeout(t);
    }
  }, [success]);

  const handleFileSelect = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (!file) return;
    setUploadFile(file);
    setUploadPreview(URL.createObjectURL(file));
  };

  const handleUpload = async () => {
    if (!uploadFile) return;
    setUploading(true);
    setError('');
    try {
      const fd = new FormData();
      fd.append('logo', uploadFile);
      const res = await wpManagerService.uploadLogo(websiteId, fd);
      setLogoUrl(res.data.data.logo_url);
      setSuccess('Logo uploaded successfully!');
      setUploadFile(null);
      setUploadPreview('');
      setTab('current');
    } catch (e: any) {
      setError(e.response?.data?.error || e.message || 'Upload failed');
    } finally {
      setUploading(false);
    }
  };

  const handleRemove = async () => {
    if (!confirm('Are you sure you want to remove the logo?')) return;
    setRemoving(true);
    setError('');
    try {
      await wpManagerService.removeLogo(websiteId);
      setLogoUrl('');
      setSuccess('Logo removed');
    } catch (e: any) {
      setError(e.response?.data?.error || e.message || 'Remove failed');
    } finally {
      setRemoving(false);
    }
  };

  const handleGenerate = async () => {
    if (!genForm.business_name.trim()) {
      setError('Business name is required');
      return;
    }
    setGenerating(true);
    setError('');
    setSvgPreview('');
    try {
      const res = await wpManagerService.generateLogo(websiteId, {
        business_name: genForm.business_name,
        style: genForm.style,
        colors: genForm.colors,
      });
      setLogoUrl(res.data.data.logo_url);
      setSvgPreview(res.data.data.svg_preview || '');
      setSuccess('AI logo generated and applied!');
      setTab('current');
    } catch (e: any) {
      setError(e.response?.data?.error || e.message || 'Generation failed');
    } finally {
      setGenerating(false);
    }
  };

  const dropHandler = (e: React.DragEvent) => {
    e.preventDefault();
    const file = e.dataTransfer.files?.[0];
    if (file && file.type.startsWith('image/')) {
      setUploadFile(file);
      setUploadPreview(URL.createObjectURL(file));
    }
  };

  if (loading) {
    return (
      <div style={s.center}>
        <div style={s.spinner} />
        <p style={s.loadText}>Loading logo...</p>
      </div>
    );
  }

  return (
    <div style={s.page}>
      <div style={s.header}>
        <h1 style={s.title}>Logo & Assets</h1>
        <p style={s.subtitle}>Upload your logo or generate one with AI</p>
      </div>

      {/* Messages */}
      {error && <div style={s.error}>{error}</div>}
      {success && <div style={s.success}>{success}</div>}

      {/* Tabs */}
      <div style={s.tabs}>
        {([
          ['current', 'Current Logo'],
          ['upload', 'Upload Logo'],
          ['generate', 'AI Generate'],
        ] as [Tab, string][]).map(([key, label]) => (
          <button
            key={key}
            onClick={() => { setTab(key); setError(''); }}
            style={{ ...s.tab, ...(tab === key ? s.tabActive : {}) }}
            className="logo-tab"
          >
            {key === 'current' && (
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
              </svg>
            )}
            {key === 'upload' && (
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
              </svg>
            )}
            {key === 'generate' && (
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
              </svg>
            )}
            {label}
          </button>
        ))}
      </div>

      {/* Current Logo Tab */}
      {tab === 'current' && (
        <div style={s.card}>
          <h3 style={s.cardTitle}>Current Logo</h3>
          {logoUrl ? (
            <div style={s.logoSection}>
              <div style={s.logoPreviewWrap}>
                <img src={logoUrl} alt="Site Logo" style={s.logoPreview} />
              </div>
              <div style={s.logoInfo}>
                <p style={s.logoLabel}>Active logo for <strong>{siteName}</strong></p>
                <p style={s.logoHint}>This logo appears in your website header. You can replace it by uploading a new one or generating with AI.</p>
                <div style={s.logoActions}>
                  <button onClick={() => setTab('upload')} style={s.btnPrimary} className="logo-btn-primary">
                    Replace Logo
                  </button>
                  <button onClick={handleRemove} disabled={removing} style={s.btnDanger} className="logo-btn-danger">
                    {removing ? 'Removing...' : 'Remove Logo'}
                  </button>
                </div>
              </div>
            </div>
          ) : (
            <div style={s.emptyState}>
              <div style={s.emptyIcon}>
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                </svg>
              </div>
              <h3 style={s.emptyTitle}>No logo set</h3>
              <p style={s.emptyDesc}>Your website doesn't have a logo yet. Upload one or let AI generate a professional logo for you.</p>
              <div style={s.emptyActions}>
                <button onClick={() => setTab('upload')} style={s.btnPrimary} className="logo-btn-primary">
                  Upload Logo
                </button>
                <button onClick={() => setTab('generate')} style={s.btnSecondary} className="logo-btn-secondary">
                  Generate with AI
                </button>
              </div>
            </div>
          )}
        </div>
      )}

      {/* Upload Tab */}
      {tab === 'upload' && (
        <div style={s.card}>
          <h3 style={s.cardTitle}>Upload Logo</h3>
          <p style={s.cardDesc}>Upload your own logo (PNG, JPG, SVG, or WebP). Recommended size: at least 200px wide.</p>

          <div
            style={s.dropZone}
            className="logo-dropzone"
            onDragOver={(e) => e.preventDefault()}
            onDrop={dropHandler}
            onClick={() => fileInputRef.current?.click()}
          >
            {uploadPreview ? (
              <div style={s.uploadPreviewWrap}>
                <img src={uploadPreview} alt="Preview" style={s.uploadPreviewImg} />
                <p style={s.uploadFileName}>{uploadFile?.name}</p>
              </div>
            ) : (
              <>
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
                  <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
                <p style={s.dropText}>Drag & drop your logo here or click to browse</p>
                <p style={s.dropHint}>PNG, JPG, SVG, WebP</p>
              </>
            )}
          </div>

          <input
            ref={fileInputRef}
            type="file"
            accept="image/png,image/jpeg,image/svg+xml,image/webp"
            style={{ display: 'none' }}
            onChange={handleFileSelect}
          />

          {uploadFile && (
            <div style={s.uploadActions}>
              <button onClick={handleUpload} disabled={uploading} style={s.btnPrimary} className="logo-btn-primary">
                {uploading ? 'Uploading...' : 'Apply Logo'}
              </button>
              <button
                onClick={() => { setUploadFile(null); setUploadPreview(''); }}
                style={s.btnSecondary}
                className="logo-btn-secondary"
              >
                Cancel
              </button>
            </div>
          )}
        </div>
      )}

      {/* Generate Tab */}
      {tab === 'generate' && (
        <div style={s.card}>
          <h3 style={s.cardTitle}>AI Logo Generator</h3>
          <p style={s.cardDesc}>Let AI create a professional logo for your business. Fill in the details and click generate.</p>

          <div style={s.form}>
            <div style={s.field}>
              <label style={s.label}>Business Name *</label>
              <input
                type="text"
                value={genForm.business_name}
                onChange={(e) => setGenForm(prev => ({ ...prev, business_name: e.target.value }))}
                placeholder="e.g. TechVision Pro"
                style={s.input}
                className="logo-input"
              />
            </div>

            <div style={s.field}>
              <label style={s.label}>Style</label>
              <select
                value={genForm.style}
                onChange={(e) => setGenForm(prev => ({ ...prev, style: e.target.value }))}
                style={s.input}
                className="logo-input"
              >
                <option value="modern minimal">Modern Minimal</option>
                <option value="bold professional">Bold Professional</option>
                <option value="elegant luxury">Elegant Luxury</option>
                <option value="playful creative">Playful Creative</option>
                <option value="tech futuristic">Tech Futuristic</option>
                <option value="classic traditional">Classic Traditional</option>
                <option value="organic natural">Organic Natural</option>
              </select>
            </div>

            <div style={s.field}>
              <label style={s.label}>Color Preferences (optional)</label>
              <input
                type="text"
                value={genForm.colors}
                onChange={(e) => setGenForm(prev => ({ ...prev, colors: e.target.value }))}
                placeholder="e.g. blue and white, dark theme, red accent"
                style={s.input}
                className="logo-input"
              />
            </div>

            {svgPreview && (
              <div style={s.genPreview}>
                <p style={s.label}>Preview:</p>
                <div style={s.svgWrap} dangerouslySetInnerHTML={{ __html: svgPreview }} />
              </div>
            )}

            <button onClick={handleGenerate} disabled={generating} style={s.btnPrimary} className="logo-btn-primary">
              {generating ? (
                <span style={s.btnLoading}>
                  <div style={s.btnSpinner} />
                  Generating...
                </span>
              ) : (
                <>Generate & Apply Logo</>
              )}
            </button>
          </div>
        </div>
      )}

      <style>{cssStyles}</style>
    </div>
  );
}

const cssStyles = `
  .logo-tab:hover { background: #F3F4F6 !important; }
  .logo-btn-primary:hover:not(:disabled) { opacity: 0.9 !important; }
  .logo-btn-secondary:hover { background: #F3F4F6 !important; }
  .logo-btn-danger:hover:not(:disabled) { background: #FEE2E2 !important; color: #991B1B !important; }
  .logo-dropzone:hover { border-color: #6366F1 !important; background: #F5F3FF !important; }
  .logo-input:focus { outline: none; border-color: #6366F1 !important; box-shadow: 0 0 0 3px rgba(99,102,241,0.1) !important; }
  @keyframes spin { to { transform: rotate(360deg); } }
`;

const s: Record<string, React.CSSProperties> = {
  page: { padding: '24px 28px', maxWidth: 800 },
  header: { marginBottom: 24 },
  title: { fontSize: 22, fontWeight: 700, color: '#111827', margin: 0 },
  subtitle: { fontSize: 14, color: '#6B7280', marginTop: 4 },

  // Messages
  error: { padding: '10px 16px', background: '#FEF2F2', color: '#991B1B', borderRadius: 10, fontSize: 13, marginBottom: 16, border: '1px solid #FECACA' },
  success: { padding: '10px 16px', background: '#F0FDF4', color: '#166534', borderRadius: 10, fontSize: 13, marginBottom: 16, border: '1px solid #BBF7D0' },

  // Tabs
  tabs: { display: 'flex', gap: 4, marginBottom: 20, background: '#F3F4F6', borderRadius: 12, padding: 4 },
  tab: { flex: 1, display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 6, padding: '10px 16px', border: 'none', borderRadius: 10, background: 'transparent', color: '#6B7280', fontSize: 13, fontWeight: 500, cursor: 'pointer', transition: 'all 0.15s' },
  tabActive: { background: '#fff', color: '#111827', fontWeight: 600, boxShadow: '0 1px 3px rgba(0,0,0,0.08)' },

  // Card
  card: { background: '#fff', border: '1px solid #E5E7EB', borderRadius: 14, padding: 24 },
  cardTitle: { fontSize: 16, fontWeight: 600, color: '#111827', margin: '0 0 6px' },
  cardDesc: { fontSize: 13, color: '#6B7280', margin: '0 0 20px', lineHeight: 1.5 },

  // Current logo
  logoSection: { display: 'flex', gap: 24, alignItems: 'flex-start', flexWrap: 'wrap' as const },
  logoPreviewWrap: { width: 200, height: 120, border: '1px solid #E5E7EB', borderRadius: 12, display: 'flex', alignItems: 'center', justifyContent: 'center', padding: 16, background: '#FAFAFA', flexShrink: 0 },
  logoPreview: { maxWidth: '100%', maxHeight: '100%', objectFit: 'contain' as const },
  logoInfo: { flex: 1, minWidth: 200 },
  logoLabel: { fontSize: 14, color: '#374151', margin: '0 0 8px' },
  logoHint: { fontSize: 13, color: '#9CA3AF', margin: '0 0 16px', lineHeight: 1.5 },
  logoActions: { display: 'flex', gap: 10 },

  // Empty state
  emptyState: { textAlign: 'center' as const, padding: '40px 20px' },
  emptyIcon: { marginBottom: 16 },
  emptyTitle: { fontSize: 18, fontWeight: 600, color: '#374151', margin: '0 0 8px' },
  emptyDesc: { fontSize: 14, color: '#9CA3AF', maxWidth: 400, margin: '0 auto 24px', lineHeight: 1.5 },
  emptyActions: { display: 'flex', gap: 10, justifyContent: 'center' },

  // Upload
  dropZone: { border: '2px dashed #D1D5DB', borderRadius: 14, padding: 40, textAlign: 'center' as const, cursor: 'pointer', transition: 'all 0.15s' },
  dropText: { fontSize: 14, color: '#6B7280', marginTop: 12, marginBottom: 4 },
  dropHint: { fontSize: 12, color: '#9CA3AF' },
  uploadPreviewWrap: { display: 'flex', flexDirection: 'column' as const, alignItems: 'center', gap: 12 },
  uploadPreviewImg: { maxWidth: 200, maxHeight: 100, objectFit: 'contain' as const },
  uploadFileName: { fontSize: 12, color: '#6B7280' },
  uploadActions: { display: 'flex', gap: 10, marginTop: 16 },

  // Form
  form: { display: 'flex', flexDirection: 'column' as const, gap: 16 },
  field: {},
  label: { display: 'block', fontSize: 13, fontWeight: 500, color: '#374151', marginBottom: 6 },
  input: { width: '100%', padding: '10px 14px', border: '1px solid #D1D5DB', borderRadius: 10, fontSize: 14, color: '#111827', background: '#fff', boxSizing: 'border-box' as const, transition: 'all 0.15s' },

  // Generate preview
  genPreview: { padding: 16, background: '#F9FAFB', borderRadius: 12, border: '1px solid #E5E7EB' },
  svgWrap: { display: 'flex', justifyContent: 'center', padding: 16, background: '#fff', borderRadius: 8 },

  // Buttons
  btnPrimary: { padding: '10px 20px', background: '#4F46E5', color: '#fff', border: 'none', borderRadius: 10, fontSize: 13, fontWeight: 600, cursor: 'pointer', transition: 'all 0.15s' },
  btnSecondary: { padding: '10px 20px', background: '#fff', color: '#374151', border: '1px solid #D1D5DB', borderRadius: 10, fontSize: 13, fontWeight: 500, cursor: 'pointer', transition: 'all 0.15s' },
  btnDanger: { padding: '10px 20px', background: '#fff', color: '#DC2626', border: '1px solid #FECACA', borderRadius: 10, fontSize: 13, fontWeight: 500, cursor: 'pointer', transition: 'all 0.15s' },
  btnLoading: { display: 'flex', alignItems: 'center', gap: 8 },
  btnSpinner: { width: 14, height: 14, border: '2px solid rgba(255,255,255,0.3)', borderTopColor: '#fff', borderRadius: '50%', animation: 'spin 0.6s linear infinite' },

  // Loading
  center: { display: 'flex', flexDirection: 'column' as const, alignItems: 'center', justifyContent: 'center', minHeight: 300 },
  spinner: { width: 32, height: 32, border: '3px solid #E5E7EB', borderTopColor: '#4F46E5', borderRadius: '50%', animation: 'spin 0.6s linear infinite' },
  loadText: { marginTop: 12, fontSize: 14, color: '#6B7280' },
};
