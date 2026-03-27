import { useState, useEffect, useRef, useCallback } from 'react';
import { useOutletContext } from 'react-router-dom';
import aiCopilotService, {
  type CopilotMessage,
  type CopilotAction,
  type CopilotSuggestion,
} from '../../services/ai-copilot.service';

interface MessageItem {
  role: 'user' | 'assistant';
  content: string;
  actions?: CopilotAction[];
}

export default function AiEditor() {
  const { website } = useOutletContext<{ website: any }>();
  const [messages, setMessages] = useState<MessageItem[]>([]);
  const [input, setInput] = useState('');
  const [sending, setSending] = useState(false);
  const [suggestions, setSuggestions] = useState<CopilotSuggestion[]>([]);
  const [iframeKey, setIframeKey] = useState(0);
  const [sessionId, setSessionId] = useState<number | undefined>();
  const [pageId] = useState<number>(website.home_page_id || 0);
  const [undoing, setUndoing] = useState<number | null>(null);
  const endRef = useRef<HTMLDivElement>(null);
  const inputRef = useRef<HTMLTextAreaElement>(null);

  useEffect(() => {
    aiCopilotService.getSuggestions(website.id, pageId)
      .then(setSuggestions)
      .catch(() => {});
  }, [website.id, pageId]);

  useEffect(() => { endRef.current?.scrollIntoView({ behavior: 'smooth' }); }, [messages]);

  const send = useCallback(async (text?: string) => {
    const msg = text || input.trim();
    if (!msg || sending) return;
    setInput('');

    const userMsg: MessageItem = { role: 'user', content: msg };
    setMessages(prev => [...prev, userMsg]);
    setSending(true);

    try {
      const history: CopilotMessage[] = messages.map(m => ({ role: m.role, content: m.content }));
      history.push({ role: 'user', content: msg });

      const res = await aiCopilotService.chat(website.id, msg, history, pageId, sessionId);

      setSessionId(res.session_id);
      setMessages(prev => [...prev, {
        role: 'assistant',
        content: res.reply,
        actions: res.actions,
      }]);

      // Auto-refresh preview if changes were made
      if (res.has_changes) {
        setTimeout(() => setIframeKey(k => k + 1), 1500);
      }
    } catch {
      setMessages(prev => [...prev, { role: 'assistant', content: 'Something went wrong. Please try again.' }]);
    } finally {
      setSending(false);
      inputRef.current?.focus();
    }
  }, [input, sending, website.id, messages, pageId, sessionId]);

  const handleUndo = async (actionId: number) => {
    setUndoing(actionId);
    try {
      const res = await aiCopilotService.undo(website.id, actionId);
      if (res.success) {
        setMessages(prev => [...prev, { role: 'assistant', content: '↩ Change undone successfully.' }]);
        setTimeout(() => setIframeKey(k => k + 1), 1000);
      } else {
        setMessages(prev => [...prev, { role: 'assistant', content: `Undo failed: ${res.error || 'Unknown error'}` }]);
      }
    } catch {
      setMessages(prev => [...prev, { role: 'assistant', content: 'Failed to undo. Please try again.' }]);
    } finally {
      setUndoing(null);
    }
  };

  const handleKey = (e: React.KeyboardEvent) => {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); send(); }
  };

  const refreshPreview = () => setIframeKey(k => k + 1);

  return (
    <div style={{ display: 'flex', height: 'calc(100vh - 56px)', background: '#f5f5f5', overflow: 'hidden' }}>
      {/* Left: Website Preview */}
      <div style={{ flex: 1, display: 'flex', flexDirection: 'column', minWidth: 0 }}>
        <div style={{
          padding: '10px 16px', background: '#fff', borderBottom: '1px solid #e5e7eb',
          display: 'flex', alignItems: 'center', justifyContent: 'space-between',
        }}>
          <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
            <div style={{ width: 10, height: 10, borderRadius: '50%', background: '#22c55e' }} />
            <span style={{ fontSize: 13, color: '#666', fontFamily: 'monospace' }}>{website.url}</span>
          </div>
          <div style={{ display: 'flex', gap: 8 }}>
            <button onClick={refreshPreview} style={toolBtn} title="Refresh">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round">
                <path d="M1 4v6h6"/><path d="M3.51 15a9 9 0 105.34-9.43L1 10"/>
              </svg>
            </button>
            <a href={website.url} target="_blank" rel="noopener noreferrer" style={toolBtn} title="Open in new tab">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round">
                <path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>
              </svg>
            </a>
            <a href={`${website.url}/wp-admin/post.php?post=${pageId}&action=elementor`} target="_blank" rel="noopener noreferrer" style={{ ...toolBtn, background: '#7c5cfc', color: '#fff', border: 'none' }} title="Edit with Elementor">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round">
                <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
              </svg>
              <span style={{ fontSize: 11, fontWeight: 600 }}>Elementor</span>
            </a>
          </div>
        </div>
        <iframe
          key={iframeKey}
          src={website.url}
          style={{ flex: 1, border: 'none', width: '100%', background: '#fff' }}
          title="Website Preview"
        />
      </div>

      {/* Right: AI Copilot Panel */}
      <div style={{
        width: 400, flexShrink: 0, display: 'flex', flexDirection: 'column',
        background: '#fff', borderLeft: '1px solid #e5e7eb',
      }}>
        {/* Header */}
        <div style={{
          padding: '16px 20px', borderBottom: '1px solid #f0f0f0',
          background: 'linear-gradient(135deg, #7c5cfc 0%, #6a4ae8 100%)',
        }}>
          <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
            <div style={{
              width: 36, height: 36, borderRadius: 10,
              background: 'rgba(255,255,255,.15)', display: 'flex',
              alignItems: 'center', justifyContent: 'center',
            }}>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="white"/>
              </svg>
            </div>
            <div>
              <div style={{ fontSize: 15, fontWeight: 700, color: '#fff' }}>AI Copilot</div>
              <div style={{ fontSize: 11, color: 'rgba(255,255,255,.7)' }}>{website.name}</div>
            </div>
          </div>
        </div>

        {/* Messages */}
        <div style={{
          flex: 1, overflowY: 'auto', padding: 16,
          display: 'flex', flexDirection: 'column', gap: 10,
        }}>
          {messages.length === 0 && (
            <div style={{ textAlign: 'center', padding: '24px 0' }}>
              <svg width="40" height="40" viewBox="0 0 24 24" fill="none" style={{ margin: '0 auto 12px' }}>
                <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="#7c5cfc"/>
              </svg>
              <p style={{ fontSize: 16, fontWeight: 700, color: '#111', margin: '0 0 4px' }}>
                AI Copilot
              </p>
              <p style={{ fontSize: 13, color: '#999', margin: '0 0 20px', lineHeight: 1.5 }}>
                I can edit content, change styles, add sections, manage products &mdash; just tell me!
              </p>
              <div style={{ display: 'flex', flexDirection: 'column', gap: 6 }}>
                {suggestions.slice(0, 6).map((s, i) => (
                  <button key={i} onClick={() => send(s.prompt)} style={sugBtn}>
                    {s.text}
                  </button>
                ))}
              </div>
            </div>
          )}

          {messages.map((m, i) => (
            <div key={i}>
              {/* Message bubble */}
              <div style={{
                display: 'flex', justifyContent: m.role === 'user' ? 'flex-end' : 'flex-start',
              }}>
                <div style={{
                  maxWidth: '88%', padding: '10px 14px', borderRadius: 14,
                  fontSize: 13, lineHeight: 1.7, whiteSpace: 'pre-wrap',
                  ...(m.role === 'user'
                    ? { background: 'linear-gradient(135deg,#7c5cfc,#6a4ae8)', color: '#fff', borderBottomRightRadius: 4 }
                    : { background: '#f3f4f6', color: '#111', borderBottomLeftRadius: 4 }),
                }}>
                  {m.content}
                </div>
              </div>

              {/* Action cards */}
              {m.actions && m.actions.length > 0 && (
                <div style={{ marginTop: 8, display: 'flex', flexDirection: 'column', gap: 4 }}>
                  <div style={{ display: 'flex', alignItems: 'center', gap: 6, fontSize: 12, fontWeight: 600, color: '#10b981', padding: '4px 0' }}>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#10b981" strokeWidth="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    {m.actions.length} Change{m.actions.length > 1 ? 's' : ''} Applied
                  </div>
                  {m.actions.map((action, j) => (
                    <div key={j} style={{
                      display: 'flex', alignItems: 'center', gap: 10,
                      padding: '8px 12px', borderRadius: 10, fontSize: 12,
                      background: action.result?.success ? '#f0fdf4' : '#fef2f2',
                      border: `1px solid ${action.result?.success ? '#bbf7d0' : '#fecaca'}`,
                      color: action.result?.success ? '#166534' : '#991b1b',
                    }}>
                      <span style={{ fontSize: 14 }}>{getActionIcon(action.tool)}</span>
                      <span style={{ flex: 1 }}>{getActionLabel(action)}</span>
                      {action.result?.success && (
                        <button
                          onClick={() => handleUndo(action.result?.action_id)}
                          disabled={undoing === action.result?.action_id}
                          style={{
                            width: 28, height: 28, borderRadius: 6,
                            border: '1px solid #bbf7d0', background: '#fff',
                            cursor: 'pointer', fontSize: 14, display: 'flex',
                            alignItems: 'center', justifyContent: 'center',
                          }}
                          title="Undo"
                        >
                          {undoing === action.result?.action_id ? '...' : '↩'}
                        </button>
                      )}
                    </div>
                  ))}
                </div>
              )}
            </div>
          ))}

          {sending && (
            <div style={{ display: 'flex', gap: 5, padding: 10 }}>
              {[0, .15, .3].map(d => (
                <div key={d} style={{
                  width: 8, height: 8, borderRadius: '50%', background: '#c4b5fd',
                  animation: `aiBounce .8s ease infinite ${d}s`,
                }} />
              ))}
            </div>
          )}
          <div ref={endRef} />
        </div>

        {/* Input */}
        <div style={{ padding: '12px 16px', borderTop: '1px solid #f0f0f0', background: '#fafafa' }}>
          {pageId > 0 && (
            <div style={{ display: 'flex', gap: 6, marginBottom: 8 }}>
              <span style={{
                display: 'inline-flex', alignItems: 'center', padding: '2px 8px',
                background: '#f3f4f6', borderRadius: 10, fontSize: 10, color: '#6b7280', fontWeight: 500,
              }}>
                Page #{pageId}
              </span>
            </div>
          )}
          <div style={{ display: 'flex', gap: 8, alignItems: 'flex-end' }}>
            <textarea
              ref={inputRef}
              value={input}
              onChange={e => setInput(e.target.value)}
              onKeyDown={handleKey}
              placeholder="Change headline, add products, update colors..."
              rows={2}
              style={{
                flex: 1, padding: '10px 14px', borderRadius: 12,
                border: '1px solid #e5e7eb', fontSize: 13, outline: 'none',
                resize: 'none', fontFamily: 'inherit', lineHeight: 1.5,
                background: '#fff',
              }}
              onFocus={e => e.target.style.borderColor = '#7c5cfc'}
              onBlur={e => e.target.style.borderColor = '#e5e7eb'}
            />
            <button onClick={() => send()} disabled={!input.trim() || sending} style={{
              width: 40, height: 40, borderRadius: 12, border: 'none', flexShrink: 0,
              background: input.trim() && !sending ? 'linear-gradient(135deg,#7c5cfc,#6a4ae8)' : '#e5e7eb',
              cursor: input.trim() && !sending ? 'pointer' : 'default',
              display: 'flex', alignItems: 'center', justifyContent: 'center',
            }}>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke={input.trim() && !sending ? '#fff' : '#999'} strokeWidth="2" strokeLinecap="round">
                <line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/>
              </svg>
            </button>
          </div>
          <div style={{ fontSize: 10, color: '#bbb', marginTop: 6, textAlign: 'center' }}>
            Enter to send · Shift+Enter new line · Preview auto-refreshes after changes
          </div>
        </div>
      </div>

      <style>{`
        @keyframes aiBounce { 0%,60%,100%{transform:translateY(0)} 30%{transform:translateY(-6px)} }
      `}</style>
    </div>
  );
}

function getActionIcon(tool: string): string {
  const icons: Record<string, string> = {
    'edit_element_text': '✏️', 'edit_element_style': '🎨', 'edit_element_image': '🖼️',
    'add_section': '➕', 'remove_section': '🗑️', 'reorder_sections': '↕️',
    'create_page': '📄', 'update_page_title': '📝', 'delete_page': '🗑️',
    'set_global_colors': '🎨', 'set_global_fonts': '🔤', 'update_page_seo': '🔍',
    'create_product': '🛍️', 'update_product': '🛍️', 'install_plugin': '🔌',
    'upload_image': '🖼️', 'update_menu': '📋', 'update_site_settings': '⚙️',
  };
  return icons[tool] || '⚡';
}

function getActionLabel(action: CopilotAction): string {
  const { tool, input } = action;
  switch (tool) {
    case 'edit_element_text': return `Updated "${input.field}" text`;
    case 'edit_element_style': return `Changed ${input.property} to ${input.value}`;
    case 'edit_element_image': return 'Updated image';
    case 'add_section': return `Added ${input.section_type || 'new'} section`;
    case 'remove_section': return 'Removed section';
    case 'create_page': return `Created page "${input.title}"`;
    case 'update_page_seo': return 'Updated SEO meta';
    case 'create_product': return `Created product "${input.name}"`;
    case 'set_global_colors': return 'Updated brand colors';
    case 'set_global_fonts': return 'Updated fonts';
    case 'install_plugin': return `Installed "${input.slug}"`;
    case 'upload_image': return 'Uploaded image';
    case 'update_menu': return 'Updated navigation';
    default: return tool.replace(/_/g, ' ');
  }
}

const toolBtn: React.CSSProperties = {
  display: 'inline-flex', alignItems: 'center', gap: 4,
  padding: '6px 10px', borderRadius: 8, border: '1px solid #e5e7eb',
  background: '#fff', cursor: 'pointer', color: '#555', fontSize: 12,
  transition: 'all .15s',
};

const sugBtn: React.CSSProperties = {
  padding: '10px 14px', borderRadius: 10, border: '1px solid #e5e7eb',
  background: '#fafafa', fontSize: 13, color: '#374151', cursor: 'pointer',
  textAlign: 'left', display: 'flex', alignItems: 'center', gap: 8,
  transition: 'all .15s',
};
