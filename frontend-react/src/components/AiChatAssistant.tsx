import { useState, useEffect, useRef, useCallback } from 'react';
import { aiChatService, type ChatMessage, type AiSuggestion } from '../services/ai-chat.service';

interface Props {
  websiteId: number;
  websiteName: string;
}

export default function AiChatAssistant({ websiteId, websiteName }: Props) {
  const [open, setOpen] = useState(false);
  const [messages, setMessages] = useState<ChatMessage[]>([]);
  const [input, setInput] = useState('');
  const [sending, setSending] = useState(false);
  const [suggestions, setSuggestions] = useState<AiSuggestion[]>([]);
  const [showPulse, setShowPulse] = useState(true);
  const endRef = useRef<HTMLDivElement>(null);
  const inputRef = useRef<HTMLTextAreaElement>(null);

  useEffect(() => {
    if (open && suggestions.length === 0) {
      aiChatService.getSuggestions(websiteId)
        .then(res => setSuggestions(res.data.suggestions || []))
        .catch(() => {});
    }
    if (open) {
      setShowPulse(false);
      setTimeout(() => inputRef.current?.focus(), 200);
    }
  }, [open, websiteId, suggestions.length]);

  useEffect(() => {
    endRef.current?.scrollIntoView({ behavior: 'smooth' });
  }, [messages]);

  const send = useCallback(async (text?: string) => {
    const msg = text || input.trim();
    if (!msg || sending) return;
    setInput('');
    const userMsg: ChatMessage = { role: 'user', content: msg };
    setMessages(prev => [...prev, userMsg]);
    setSending(true);
    try {
      const res = await aiChatService.sendMessage(websiteId, msg, [...messages, userMsg]);
      setMessages(prev => [...prev, { role: 'assistant', content: res.data.reply }]);
    } catch {
      setMessages(prev => [...prev, { role: 'assistant', content: 'Sorry, something went wrong. Please try again.' }]);
    } finally {
      setSending(false);
    }
  }, [input, sending, websiteId, messages]);

  const handleKey = (e: React.KeyboardEvent) => {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); send(); }
  };

  return (
    <>
      {/* Floating Button */}
      <button onClick={() => setOpen(!open)} style={{
        position: 'fixed', bottom: 24, right: 24, zIndex: 99999,
        width: 56, height: 56, borderRadius: '50%',
        background: 'linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%)',
        border: 'none', cursor: 'pointer', color: '#fff',
        display: 'flex', alignItems: 'center', justifyContent: 'center',
        boxShadow: '0 4px 24px rgba(99,102,241,.4)',
        transition: 'all .3s cubic-bezier(.4,0,.2,1)',
        transform: open ? 'rotate(180deg) scale(.9)' : 'scale(1)',
      }}>
        {open ? (
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" strokeWidth="2.5" strokeLinecap="round">
            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
          </svg>
        ) : (
          <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#fff" strokeWidth="1.5" strokeLinecap="round">
            <path d="M12 2C6.48 2 2 6.04 2 11c0 2.76 1.36 5.22 3.5 6.84V22l3.68-2.02C10.09 20.32 11.03 20.5 12 20.5c5.52 0 10-4.04 10-9S17.52 2 12 2z"/>
            <path d="M8 11h.01M12 11h.01M16 11h.01" strokeWidth="2.5"/>
          </svg>
        )}
        {showPulse && !open && (
          <span style={{
            position: 'absolute', top: -2, right: -2,
            width: 16, height: 16, borderRadius: '50%',
            background: '#ef4444', border: '2px solid #fff',
            animation: 'aicPulse 2s ease infinite',
          }} />
        )}
      </button>

      {/* Chat Panel */}
      {open && (
        <div style={{
          position: 'fixed', bottom: 92, right: 24, zIndex: 99998,
          width: 400, height: 560, borderRadius: 20,
          background: '#fff', border: '1px solid #e5e7eb',
          boxShadow: '0 20px 60px rgba(0,0,0,.15), 0 0 0 1px rgba(0,0,0,.03)',
          display: 'flex', flexDirection: 'column', overflow: 'hidden',
          animation: 'aicSlideUp .3s cubic-bezier(.4,0,.2,1)',
        }}>
          {/* Header */}
          <div style={{
            padding: '16px 20px', borderBottom: '1px solid #f3f4f6',
            background: 'linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%)',
            color: '#fff',
          }}>
            <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
              <div style={{
                width: 36, height: 36, borderRadius: 10,
                background: 'rgba(255,255,255,.2)', display: 'flex',
                alignItems: 'center', justifyContent: 'center', fontSize: 18,
              }}>&#10024;</div>
              <div>
                <div style={{ fontSize: 15, fontWeight: 700 }}>AI Assistant</div>
                <div style={{ fontSize: 11, opacity: .8 }}>{websiteName}</div>
              </div>
            </div>
          </div>

          {/* Messages */}
          <div style={{
            flex: 1, overflowY: 'auto', padding: '16px 16px 8px',
            display: 'flex', flexDirection: 'column', gap: 12,
          }}>
            {messages.length === 0 && !sending && (
              <div style={{ textAlign: 'center', padding: '20px 0' }}>
                <div style={{ fontSize: 36, marginBottom: 12 }}>&#129302;</div>
                <p style={{ fontSize: 14, fontWeight: 600, color: '#111', margin: '0 0 4px' }}>
                  Hi! I'm your AI assistant
                </p>
                <p style={{ fontSize: 13, color: '#999', margin: '0 0 20px' }}>
                  Ask me anything about your website
                </p>
                {suggestions.length > 0 && (
                  <div style={{ display: 'flex', flexDirection: 'column', gap: 6 }}>
                    {suggestions.slice(0, 4).map((s, i) => (
                      <button key={i} onClick={() => send(s.prompt)} style={{
                        padding: '10px 14px', borderRadius: 12,
                        border: '1px solid #e5e7eb', background: '#fafafa',
                        fontSize: 13, color: '#374151', cursor: 'pointer',
                        textAlign: 'left', transition: 'all .15s',
                        display: 'flex', alignItems: 'center', gap: 8,
                      }}
                        onMouseEnter={e => { e.currentTarget.style.borderColor = '#6366f1'; e.currentTarget.style.background = '#f0f0ff'; }}
                        onMouseLeave={e => { e.currentTarget.style.borderColor = '#e5e7eb'; e.currentTarget.style.background = '#fafafa'; }}
                      >
                        <span>{s.icon}</span> {s.text}
                      </button>
                    ))}
                  </div>
                )}
              </div>
            )}

            {messages.map((msg, i) => (
              <div key={i} style={{
                display: 'flex', justifyContent: msg.role === 'user' ? 'flex-end' : 'flex-start',
                animation: 'aicFadeIn .3s ease',
              }}>
                <div style={{
                  maxWidth: '85%', padding: '10px 14px', borderRadius: 14,
                  fontSize: 13, lineHeight: 1.7, whiteSpace: 'pre-wrap',
                  ...(msg.role === 'user' ? {
                    background: 'linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%)',
                    color: '#fff', borderBottomRightRadius: 4,
                  } : {
                    background: '#f3f4f6', color: '#111',
                    borderBottomLeftRadius: 4,
                  }),
                }}>
                  {msg.content}
                </div>
              </div>
            ))}

            {sending && (
              <div style={{ display: 'flex', gap: 6, padding: '8px 14px', animation: 'aicFadeIn .3s ease' }}>
                <div style={{ ...dot, animationDelay: '0s' }} />
                <div style={{ ...dot, animationDelay: '.15s' }} />
                <div style={{ ...dot, animationDelay: '.3s' }} />
              </div>
            )}
            <div ref={endRef} />
          </div>

          {/* Input */}
          <div style={{
            padding: '12px 16px', borderTop: '1px solid #f3f4f6',
            display: 'flex', gap: 8, alignItems: 'flex-end',
          }}>
            <textarea
              ref={inputRef}
              value={input}
              onChange={e => setInput(e.target.value)}
              onKeyDown={handleKey}
              placeholder="Ask anything about your website..."
              rows={1}
              style={{
                flex: 1, padding: '10px 14px', borderRadius: 12,
                border: '1px solid #e5e7eb', fontSize: 14, outline: 'none',
                resize: 'none', fontFamily: 'inherit', lineHeight: 1.5,
                maxHeight: 100, transition: 'border .2s',
              }}
              onFocus={e => e.target.style.borderColor = '#6366f1'}
              onBlur={e => e.target.style.borderColor = '#e5e7eb'}
            />
            <button
              onClick={() => send()}
              disabled={!input.trim() || sending}
              style={{
                width: 40, height: 40, borderRadius: 12, border: 'none',
                background: input.trim() && !sending
                  ? 'linear-gradient(135deg, #6366f1, #8b5cf6)'
                  : '#e5e7eb',
                cursor: input.trim() && !sending ? 'pointer' : 'default',
                display: 'flex', alignItems: 'center', justifyContent: 'center',
                transition: 'all .2s', flexShrink: 0,
              }}
            >
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                stroke={input.trim() && !sending ? '#fff' : '#999'}
                strokeWidth="2" strokeLinecap="round">
                <line x1="22" y1="2" x2="11" y2="13"/>
                <polygon points="22 2 15 22 11 13 2 9 22 2"/>
              </svg>
            </button>
          </div>
        </div>
      )}

      <style>{`
        @keyframes aicSlideUp { from { opacity:0; transform:translateY(16px) scale(.96); } to { opacity:1; transform:none; } }
        @keyframes aicFadeIn { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:none; } }
        @keyframes aicPulse { 0%,100% { transform:scale(1); opacity:1; } 50% { transform:scale(1.3); opacity:.6; } }
        @keyframes aicDot { 0%,60%,100% { transform:translateY(0); } 30% { transform:translateY(-6px); } }
      `}</style>
    </>
  );
}

const dot: React.CSSProperties = {
  width: 8, height: 8, borderRadius: '50%', background: '#c4b5fd',
  animation: 'aicDot .8s ease infinite',
};