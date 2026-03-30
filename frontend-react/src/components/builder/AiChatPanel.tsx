import { useState, useRef, useEffect } from 'react';
import type { ProjectMessage } from '../../models/types';

interface Props {
  messages: ProjectMessage[];
  isLoading: boolean;
  onSend: (message: string) => void;
}

const suggestions = [
  { icon: '🚀', text: 'Build a modern SaaS landing page with hero, features, pricing, and CTA' },
  { icon: '🎨', text: 'Create a stunning portfolio website with dark theme and smooth animations' },
  { icon: '🛒', text: 'Build an e-commerce storefront with product grid and shopping cart' },
  { icon: '📝', text: 'Create a blog with article cards, categories, and newsletter signup' },
];

function parseTaskPlan(content: string): Array<{ task: string; status: 'done' | 'running' | 'pending'; file?: string }> | null {
  // Try to detect task-like patterns in AI response
  const lines = content.split('\n');
  const tasks: Array<{ task: string; status: 'done' | 'running' | 'pending'; file?: string }> = [];

  for (const line of lines) {
    const trimmed = line.trim();
    if (trimmed.match(/^[-*]\s+/)) {
      tasks.push({ task: trimmed.replace(/^[-*]\s+/, ''), status: 'done' });
    }
  }

  return tasks.length >= 3 ? tasks : null;
}

function extractFilesFromContent(content: string): string[] {
  const filePatterns = content.match(/(?:Writing|Creating|Updated|Modified)\s+[`"]?([a-zA-Z0-9_/.-]+\.[a-zA-Z]+)[`"]?/gi);
  if (!filePatterns) return [];
  return filePatterns.map(m => {
    const match = m.match(/[`"]?([a-zA-Z0-9_/.-]+\.[a-zA-Z]+)[`"]?$/);
    return match ? match[1] : '';
  }).filter(Boolean);
}

export default function AiChatPanel({ messages, isLoading, onSend }: Props) {
  const [input, setInput] = useState('');
  const messagesEndRef = useRef<HTMLDivElement>(null);
  const textareaRef = useRef<HTMLTextAreaElement>(null);

  useEffect(() => { messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' }); }, [messages, isLoading]);

  const handleSend = () => {
    if (!input.trim() || isLoading) return;
    onSend(input.trim());
    setInput('');
    if (textareaRef.current) textareaRef.current.style.height = 'auto';
  };

  const handleInput = (e: React.ChangeEvent<HTMLTextAreaElement>) => {
    setInput(e.target.value);
    e.target.style.height = 'auto';
    e.target.style.height = Math.min(e.target.scrollHeight, 140) + 'px';
  };

  const hasMessages = messages.length > 0;

  return (
    <div className="flex flex-col h-full">
      {/* Messages area */}
      <div className="flex-1 overflow-y-auto">
        {!hasMessages ? (
          <div className="flex flex-col items-center justify-center h-full px-6">
            <div className="w-14 h-14 bg-gradient-to-br from-blue-500/20 to-purple-500/20 rounded-2xl flex items-center justify-center mb-4 border border-blue-500/10">
              <svg className="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" strokeWidth={1.5} viewBox="0 0 24 24">
                <path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
              </svg>
            </div>
            <h3 className="text-sm font-semibold text-white mb-1">AI Code Assistant</h3>
            <p className="text-xs text-gray-500 text-center mb-6">Describe what you want to build and I'll generate the code for you</p>
            <div className="space-y-2 w-full">
              {suggestions.map((s, i) => (
                <button key={i} onClick={() => onSend(s.text)} className="w-full flex items-start gap-3 px-4 py-3 bg-[#12121a] border border-[#1e1e2e] rounded-xl text-left hover:border-blue-500/30 hover:bg-blue-600/5 transition group">
                  <span className="text-base mt-0.5">{s.icon}</span>
                  <span className="text-xs text-gray-400 group-hover:text-gray-200 transition leading-relaxed">{s.text}</span>
                </button>
              ))}
            </div>
          </div>
        ) : (
          <div className="px-4 py-4 space-y-4">
            {messages.map((msg) => (
              <div key={msg.id} className="space-y-2">
                {msg.role === 'user' ? (
                  <div className="bg-[#1a1d27] rounded-xl px-4 py-3">
                    <p className="text-sm text-white leading-relaxed">{msg.content}</p>
                  </div>
                ) : (
                  <div>
                    {/* AI label */}
                    <div className="flex items-center gap-2 mb-2">
                      <div className="w-5 h-5 bg-gradient-to-br from-blue-500 to-purple-600 rounded-md flex items-center justify-center text-[10px] text-white font-bold">W</div>
                      <span className="text-xs text-gray-500 font-medium">WebNewBiz AI</span>
                      {msg.id === -1 && <span className="text-xs text-gray-600">...</span>}
                    </div>

                    {/* Task plan detection */}
                    {msg.id === -1 && isLoading ? (
                      <div className="space-y-2">
                        <p className="text-sm text-gray-300 leading-relaxed">{msg.content}</p>
                        <div className="flex items-center gap-2 mt-2">
                          <div className="flex gap-1">
                            <div className="w-1.5 h-1.5 bg-blue-400 rounded-full animate-bounce" />
                            <div className="w-1.5 h-1.5 bg-blue-400 rounded-full animate-bounce" style={{ animationDelay: '150ms' }} />
                            <div className="w-1.5 h-1.5 bg-blue-400 rounded-full animate-bounce" style={{ animationDelay: '300ms' }} />
                          </div>
                          <span className="text-xs text-gray-500">Generating code...</span>
                        </div>
                      </div>
                    ) : (
                      <div>
                        <p className="text-sm text-gray-300 leading-relaxed whitespace-pre-wrap">{msg.content}</p>
                        {/* Files changed */}
                        {msg.files_changed && msg.files_changed.length > 0 && (
                          <div className="mt-3 space-y-1">
                            <p className="text-xs text-gray-500 font-medium">Files modified:</p>
                            {msg.files_changed.map((f, i) => (
                              <div key={i} className="flex items-center gap-2 px-3 py-1.5 bg-[#12121a] rounded-lg border border-[#1e1e2e]">
                                <svg className="w-3 h-3 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                <span className="text-xs text-gray-400 font-mono">{f}</span>
                              </div>
                            ))}
                          </div>
                        )}
                      </div>
                    )}
                  </div>
                )}
              </div>
            ))}
            <div ref={messagesEndRef} />
          </div>
        )}
      </div>

      {/* Input area */}
      <div className="border-t border-[#1a1d27] p-4">
        <div className="bg-[#12121a] border border-[#1e1e2e] rounded-xl overflow-hidden focus-within:border-blue-500/30 transition">
          <textarea
            ref={textareaRef}
            value={input}
            onChange={handleInput}
            placeholder="How can I help you today?"
            rows={2}
            className="w-full bg-transparent px-4 pt-3 pb-1 text-sm text-white outline-none resize-none placeholder-gray-600"
            onKeyDown={e => { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); handleSend(); } }}
          />
          <div className="flex items-center justify-between px-3 py-2">
            <div className="flex items-center gap-2">
              <button className="p-1.5 text-gray-600 hover:text-gray-300 rounded-lg hover:bg-white/5 transition">
                <svg className="w-4 h-4" fill="none" stroke="currentColor" strokeWidth={1.5} viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" d="M12 4v16m8-8H4" /></svg>
              </button>
            </div>
            <button onClick={handleSend} disabled={!input.trim() || isLoading}
              className={`p-2 rounded-xl transition ${input.trim() && !isLoading ? 'bg-blue-600 hover:bg-blue-500 text-white' : 'bg-[#1a1d27] text-gray-600'}`}>
              <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" /></svg>
            </button>
          </div>
        </div>
        <p className="text-[10px] text-gray-700 text-center mt-2">Press Enter to send, Shift+Enter for new line</p>
      </div>
    </div>
  );
}
