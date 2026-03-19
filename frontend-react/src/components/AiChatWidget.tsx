import { useState, useRef, useEffect, useCallback } from 'react';
import { useNavigate } from 'react-router-dom';
import { builderPluginService } from '../services/builder-plugin.service';

interface Message {
  id: string;
  role: 'user' | 'ai';
  text: string;
  time: string;
}

interface AiChatWidgetProps {
  websiteId: number | string;
  websiteName: string;
  baseRoute: string;
}

const quickPrompts = [
  { label: 'Optimize Speed', icon: '⚡', prompt: 'Analyze my website performance and suggest optimizations' },
  { label: 'Improve SEO', icon: '🔍', prompt: 'Review my website SEO and suggest improvements' },
  { label: 'Security Check', icon: '🛡️', prompt: 'Run a security audit on my website' },
  { label: 'Generate Content', icon: '✍️', prompt: 'Help me write better content for my homepage' },
];

const navSuggestions: Record<string, { route: string; label: string }> = {
  'performance': { route: '/booster/main', label: 'Website Booster' },
  'speed': { route: '/booster/main', label: 'Website Booster' },
  'cache': { route: '/booster/main', label: 'Cache Manager' },
  'seo': { route: '/seo/dashboard', label: 'SEO Tools' },
  'security': { route: '/security', label: 'Security' },
  'backup': { route: '/backups', label: 'Backups' },
  'image': { route: '/booster/image-optimizer', label: 'Image Optimizer' },
  'analytics': { route: '/analytics', label: 'Analytics' },
  'plugin': { route: '/plugins', label: 'Plugins' },
  'theme': { route: '/themes', label: 'Themes' },
  'domain': { route: '/domains', label: 'Domains' },
  'ecommerce': { route: '/ecommerce/products', label: 'Ecommerce' },
  'product': { route: '/ecommerce/products', label: 'Products' },
  'order': { route: '/ecommerce/orders', label: 'Orders' },
};

export default function AiChatWidget({ websiteId, websiteName, baseRoute }: AiChatWidgetProps) {
  const [open, setOpen] = useState(false);
  const [messages, setMessages] = useState<Message[]>([]);
  const [input, setInput] = useState('');
  const [sending, setSending] = useState(false);
  const [showQuick, setShowQuick] = useState(true);
  const messagesEndRef = useRef<HTMLDivElement>(null);
  const inputRef = useRef<HTMLTextAreaElement>(null);
  const navigate = useNavigate();

  const scrollToBottom = useCallback(() => {
    messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' });
  }, []);

  useEffect(() => {
    scrollToBottom();
  }, [messages, scrollToBottom]);

  useEffect(() => {
    if (open && inputRef.current) {
      inputRef.current.focus();
    }
  }, [open]);

  const now = () => new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
  const uid = () => Date.now().toString(36) + Math.random().toString(36).slice(2, 6);

  const detectNavigation = (text: string): { route: string; label: string } | null => {
    const lower = text.toLowerCase();
    for (const [keyword, nav] of Object.entries(navSuggestions)) {
      if (lower.includes(keyword)) return nav;
    }
    return null;
  };

  const sendMessage = async (text: string) => {
    if (!text.trim() || sending) return;

    const userMsg: Message = { id: uid(), role: 'user', text: text.trim(), time: now() };
    setMessages(prev => [...prev, userMsg]);
    setInput('');
    setShowQuick(false);
    setSending(true);

    try {
      const res = await builderPluginService.aiGenerate(websiteId, {
        type: 'chat',
        prompt: text.trim(),
        tone: 'professional',
      });

      const aiText = res.data?.content || res.data?.data?.content || res.data?.message || 'I\'ve processed your request. Check the relevant section in your dashboard for details.';

      const aiMsg: Message = { id: uid(), role: 'ai', text: aiText, time: now() };
      setMessages(prev => [...prev, aiMsg]);

      // Check if we should suggest navigation
      const nav = detectNavigation(text);
      if (nav) {
        const navMsg: Message = {
          id: uid(),
          role: 'ai',
          text: `💡 You can manage this in **${nav.label}**. [Go to ${nav.label} →](${baseRoute}${nav.route})`,
          time: now(),
        };
        setMessages(prev => [...prev, navMsg]);
      }
    } catch {
      const errorMsg: Message = {
        id: uid(),
        role: 'ai',
        text: 'Sorry, I couldn\'t process that right now. Please try again or navigate to the relevant section in your dashboard.',
        time: now(),
      };
      setMessages(prev => [...prev, errorMsg]);
    } finally {
      setSending(false);
    }
  };

  const handleKeyDown = (e: React.KeyboardEvent) => {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      sendMessage(input);
    }
  };

  const handleNavClick = (e: React.MouseEvent<HTMLDivElement>) => {
    const target = e.target as HTMLElement;
    if (target.tagName === 'A') {
      e.preventDefault();
      const href = target.getAttribute('href');
      if (href) {
        navigate(href);
        setOpen(false);
      }
    }
  };

  const renderText = (text: string) => {
    // Simple markdown: **bold**, [link](url)
    return text.split(/(\*\*.*?\*\*|\[.*?\]\(.*?\))/g).map((part, i) => {
      if (part.startsWith('**') && part.endsWith('**')) {
        return <strong key={i}>{part.slice(2, -2)}</strong>;
      }
      const linkMatch = part.match(/\[(.*?)\]\((.*?)\)/);
      if (linkMatch) {
        return <a key={i} href={linkMatch[2]} style={st.msgLink}>{linkMatch[1]}</a>;
      }
      return part;
    });
  };

  return (
    <>
      {/* Floating Button */}
      {!open && (
        <button onClick={() => setOpen(true)} style={st.fab} className="ai-fab">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
            <path d="M12 2l1.09 3.26L16.36 6l-3.27 1.09L12 10.36l-1.09-3.27L7.64 6l3.27-1.09L12 2z"/>
            <path d="M18 12l.7 2.1L20.8 15l-2.1.7L18 17.8l-.7-2.1L15.2 15l2.1-.7L18 12z"/>
            <path d="M7 14l.5 1.5L9 16l-1.5.5L7 18l-.5-1.5L5 16l1.5-.5L7 14z"/>
          </svg>
          <span style={st.fabPulse} className="ai-fab-pulse" />
        </button>
      )}

      {/* Chat Panel */}
      {open && (
        <div style={st.panel} className="ai-panel">
          {/* Header */}
          <div style={st.header}>
            <div style={st.headerLeft}>
              <div style={st.headerAvatar}>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                  <path d="M12 2l1.09 3.26L16.36 6l-3.27 1.09L12 10.36l-1.09-3.27L7.64 6l3.27-1.09L12 2z"/>
                  <path d="M7 14l.5 1.5L9 16l-1.5.5L7 18l-.5-1.5L5 16l1.5-.5L7 14z"/>
                </svg>
              </div>
              <div>
                <div style={st.headerTitle}>AI Assistant</div>
                <div style={st.headerSub}>for {websiteName}</div>
              </div>
            </div>
            <div style={st.headerActions}>
              <button
                onClick={() => { setMessages([]); setShowQuick(true); }}
                style={st.headerBtn}
                className="ai-header-btn"
                title="Clear chat"
              >
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                  <polyline points="1 4 1 10 7 10"/>
                  <path d="M3.51 15a9 9 0 102.13-9.36L1 10"/>
                </svg>
              </button>
              <button onClick={() => setOpen(false)} style={st.headerBtn} className="ai-header-btn" title="Close">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                  <line x1="18" y1="6" x2="6" y2="18"/>
                  <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
              </button>
            </div>
          </div>

          {/* Messages */}
          <div style={st.messages}>
            {/* Welcome */}
            {messages.length === 0 && (
              <div style={st.welcome}>
                <div style={st.welcomeIcon}>
                  <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#7c5cfc" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
                    <path d="M12 2l1.09 3.26L16.36 6l-3.27 1.09L12 10.36l-1.09-3.27L7.64 6l3.27-1.09L12 2z"/>
                    <path d="M18 12l.7 2.1L20.8 15l-2.1.7L18 17.8l-.7-2.1L15.2 15l2.1-.7L18 12z"/>
                    <path d="M7 14l.5 1.5L9 16l-1.5.5L7 18l-.5-1.5L5 16l1.5-.5L7 14z"/>
                  </svg>
                </div>
                <h3 style={st.welcomeTitle}>Hi! I'm your AI assistant</h3>
                <p style={st.welcomeText}>
                  Ask me anything about your website. I can help with content, SEO, performance, security, and more.
                </p>
              </div>
            )}

            {/* Quick Prompts */}
            {showQuick && messages.length === 0 && (
              <div style={st.quickGrid}>
                {quickPrompts.map((qp, i) => (
                  <button
                    key={i}
                    onClick={() => sendMessage(qp.prompt)}
                    style={st.quickCard}
                    className="ai-quick-card"
                  >
                    <span style={st.quickIcon}>{qp.icon}</span>
                    <span style={st.quickLabel}>{qp.label}</span>
                  </button>
                ))}
              </div>
            )}

            {/* Chat Messages */}
            {messages.map((msg) => (
              <div
                key={msg.id}
                style={msg.role === 'user' ? st.msgRowUser : st.msgRowAi}
                onClick={(e) => handleNavClick(e)}
              >
                {msg.role === 'ai' && (
                  <div style={st.aiAvatar}>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                      <path d="M12 2l1.09 3.26L16.36 6l-3.27 1.09L12 10.36l-1.09-3.27L7.64 6l3.27-1.09L12 2z"/>
                    </svg>
                  </div>
                )}
                <div style={msg.role === 'user' ? st.bubbleUser : st.bubbleAi}>
                  <div style={st.msgText}>{renderText(msg.text)}</div>
                  <div style={st.msgTime}>{msg.time}</div>
                </div>
              </div>
            ))}

            {/* Typing indicator */}
            {sending && (
              <div style={st.msgRowAi}>
                <div style={st.aiAvatar}>
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                    <path d="M12 2l1.09 3.26L16.36 6l-3.27 1.09L12 10.36l-1.09-3.27L7.64 6l3.27-1.09L12 2z"/>
                  </svg>
                </div>
                <div style={st.bubbleAi}>
                  <div style={st.typing} className="ai-typing">
                    <span style={st.typingDot} className="ai-dot ai-dot-1" />
                    <span style={st.typingDot} className="ai-dot ai-dot-2" />
                    <span style={st.typingDot} className="ai-dot ai-dot-3" />
                  </div>
                </div>
              </div>
            )}

            <div ref={messagesEndRef} />
          </div>

          {/* Input Area */}
          <div style={st.inputArea}>
            <div style={st.inputRow}>
              <textarea
                ref={inputRef}
                value={input}
                onChange={(e) => setInput(e.target.value)}
                onKeyDown={handleKeyDown}
                placeholder="Ask anything about your website..."
                style={st.input}
                className="ai-input"
                rows={1}
                disabled={sending}
              />
              <button
                onClick={() => sendMessage(input)}
                disabled={!input.trim() || sending}
                style={{
                  ...st.sendBtn,
                  opacity: !input.trim() || sending ? 0.5 : 1,
                }}
                className="ai-send-btn"
              >
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                  <line x1="22" y1="2" x2="11" y2="13"/>
                  <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                </svg>
              </button>
            </div>
            <div style={st.inputHint}>Press Enter to send, Shift+Enter for new line</div>
          </div>
        </div>
      )}

      <style>{css}</style>
    </>
  );
}

const css = `
  .ai-fab {
    position: fixed;
    bottom: 24px;
    right: 24px;
    width: 56px;
    height: 56px;
    border-radius: 16px;
    background: linear-gradient(135deg, #7c5cfc 0%, #6a4ae8 100%);
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 20px rgba(124,92,252,0.4);
    z-index: 9990;
    transition: transform 0.2s, box-shadow 0.2s;
  }
  .ai-fab:hover {
    transform: scale(1.08);
    box-shadow: 0 6px 28px rgba(124,92,252,0.5);
  }
  .ai-fab-pulse {
    position: absolute;
    inset: -4px;
    border-radius: 20px;
    border: 2px solid rgba(124,92,252,0.4);
    animation: ai-pulse 2s ease-out infinite;
  }
  @keyframes ai-pulse {
    0% { opacity: 1; transform: scale(1); }
    100% { opacity: 0; transform: scale(1.3); }
  }
  .ai-panel {
    position: fixed;
    bottom: 24px;
    right: 24px;
    width: 400px;
    height: 600px;
    max-height: calc(100vh - 48px);
    border-radius: 16px;
    background: #fff;
    box-shadow: 0 8px 40px rgba(0,0,0,0.15), 0 0 0 1px rgba(0,0,0,0.05);
    z-index: 9990;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    animation: ai-slide-up 0.25s ease-out;
  }
  @keyframes ai-slide-up {
    from { opacity: 0; transform: translateY(16px); }
    to { opacity: 1; transform: translateY(0); }
  }
  .ai-header-btn:hover {
    background: rgba(255,255,255,0.2) !important;
  }
  .ai-quick-card:hover {
    background: #F5F3FF !important;
    border-color: #7c5cfc !important;
  }
  .ai-input:focus {
    outline: none;
  }
  .ai-input:disabled {
    opacity: 0.6;
  }
  .ai-send-btn:hover:not(:disabled) {
    background: #6a4ae8 !important;
  }
  @keyframes ai-bounce {
    0%, 60%, 100% { transform: translateY(0); }
    30% { transform: translateY(-4px); }
  }
  .ai-dot {
    display: inline-block;
    animation: ai-bounce 1.4s ease-in-out infinite;
  }
  .ai-dot-1 { animation-delay: 0s; }
  .ai-dot-2 { animation-delay: 0.2s; }
  .ai-dot-3 { animation-delay: 0.4s; }
  @media (max-width: 480px) {
    .ai-panel {
      width: calc(100vw - 16px) !important;
      height: calc(100vh - 16px) !important;
      bottom: 8px !important;
      right: 8px !important;
      border-radius: 12px !important;
    }
  }
`;

const st: Record<string, React.CSSProperties> = {
  /* FAB */
  fab: {},
  fabPulse: {
    position: 'absolute',
    inset: -4,
    borderRadius: 20,
  },

  /* Panel */
  panel: {},

  /* Header */
  header: {
    background: 'linear-gradient(135deg, #7c5cfc 0%, #6a4ae8 100%)',
    padding: '16px 18px',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'space-between',
    flexShrink: 0,
  },
  headerLeft: {
    display: 'flex',
    alignItems: 'center',
    gap: 10,
  },
  headerAvatar: {
    width: 36,
    height: 36,
    borderRadius: 10,
    background: 'rgba(255,255,255,0.2)',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
  },
  headerTitle: {
    fontSize: 15,
    fontWeight: 700,
    color: '#fff',
  },
  headerSub: {
    fontSize: 12,
    color: 'rgba(255,255,255,0.7)',
    marginTop: 1,
  },
  headerActions: {
    display: 'flex',
    gap: 4,
  },
  headerBtn: {
    width: 32,
    height: 32,
    borderRadius: 8,
    background: 'rgba(255,255,255,0.1)',
    border: 'none',
    color: '#fff',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    cursor: 'pointer',
    transition: 'background 0.15s',
  },

  /* Messages */
  messages: {
    flex: 1,
    overflowY: 'auto',
    padding: '16px',
    display: 'flex',
    flexDirection: 'column',
    gap: 12,
  },

  /* Welcome */
  welcome: {
    textAlign: 'center',
    padding: '20px 10px 8px',
  },
  welcomeIcon: {
    width: 56,
    height: 56,
    borderRadius: 16,
    background: '#F5F3FF',
    display: 'inline-flex',
    alignItems: 'center',
    justifyContent: 'center',
    marginBottom: 12,
  },
  welcomeTitle: {
    fontSize: 16,
    fontWeight: 700,
    color: '#111827',
    margin: '0 0 6px',
  },
  welcomeText: {
    fontSize: 13,
    color: '#6B7280',
    lineHeight: 1.5,
    margin: 0,
  },

  /* Quick Prompts */
  quickGrid: {
    display: 'grid',
    gridTemplateColumns: '1fr 1fr',
    gap: 8,
    padding: '4px 0',
  },
  quickCard: {
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    gap: 6,
    padding: '14px 8px',
    borderRadius: 12,
    border: '1px solid #E5E7EB',
    background: '#fff',
    cursor: 'pointer',
    transition: 'all 0.15s',
  },
  quickIcon: {
    fontSize: 20,
  },
  quickLabel: {
    fontSize: 12,
    fontWeight: 600,
    color: '#374151',
  },

  /* Message Rows */
  msgRowUser: {
    display: 'flex',
    justifyContent: 'flex-end',
  },
  msgRowAi: {
    display: 'flex',
    alignItems: 'flex-start',
    gap: 8,
  },
  aiAvatar: {
    width: 28,
    height: 28,
    borderRadius: 8,
    background: 'linear-gradient(135deg, #7c5cfc 0%, #6a4ae8 100%)',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    flexShrink: 0,
    marginTop: 2,
  },
  bubbleUser: {
    maxWidth: '80%',
    background: '#7c5cfc',
    color: '#fff',
    borderRadius: '14px 14px 4px 14px',
    padding: '10px 14px',
  },
  bubbleAi: {
    maxWidth: '80%',
    background: '#F3F4F6',
    color: '#111827',
    borderRadius: '14px 14px 14px 4px',
    padding: '10px 14px',
  },
  msgText: {
    fontSize: 13,
    lineHeight: 1.5,
    whiteSpace: 'pre-wrap',
    wordBreak: 'break-word',
  },
  msgTime: {
    fontSize: 10,
    opacity: 0.6,
    marginTop: 4,
    textAlign: 'right',
  },
  msgLink: {
    color: '#7c5cfc',
    fontWeight: 600,
    textDecoration: 'none',
    cursor: 'pointer',
  },

  /* Typing */
  typing: {
    display: 'flex',
    gap: 4,
    padding: '4px 0',
  },
  typingDot: {
    width: 6,
    height: 6,
    borderRadius: '50%',
    background: '#9CA3AF',
  },

  /* Input */
  inputArea: {
    borderTop: '1px solid #E5E7EB',
    padding: '12px 16px',
    flexShrink: 0,
    background: '#fff',
  },
  inputRow: {
    display: 'flex',
    alignItems: 'flex-end',
    gap: 8,
    background: '#F9FAFB',
    borderRadius: 12,
    border: '1px solid #E5E7EB',
    padding: '8px 8px 8px 14px',
  },
  input: {
    flex: 1,
    border: 'none',
    background: 'transparent',
    fontSize: 13,
    color: '#111827',
    resize: 'none',
    lineHeight: 1.5,
    maxHeight: 80,
    fontFamily: 'inherit',
  },
  sendBtn: {
    width: 36,
    height: 36,
    borderRadius: 10,
    background: '#7c5cfc',
    border: 'none',
    color: '#fff',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    cursor: 'pointer',
    flexShrink: 0,
    transition: 'background 0.15s, opacity 0.15s',
  },
  inputHint: {
    fontSize: 10,
    color: '#9CA3AF',
    marginTop: 6,
    textAlign: 'center',
  },
};
