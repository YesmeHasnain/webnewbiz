import { useState, useRef, useEffect } from 'react';
import type { ProjectMessage } from '../../models/types';

interface Props {
  messages: ProjectMessage[];
  isLoading: boolean;
  onSend: (message: string) => void;
  framework?: string;
}

const suggestions = [
  { icon: '🚀', text: 'Build a modern SaaS landing page with hero, features, pricing, and CTA' },
  { icon: '🎨', text: 'Create a stunning portfolio website with dark theme and smooth animations' },
  { icon: '🛒', text: 'Build an e-commerce storefront with product grid and shopping cart' },
  { icon: '📝', text: 'Create a blog with article cards, categories, and newsletter signup' },
];

function getTasksFromFiles(files: string[]): Array<{ file: string; label: string }> {
  // Build task list dynamically from actual files
  const tasks: Array<{ file: string; label: string }> = [];
  const labelMap: Record<string, string> = {
    'index.html': 'Build home page with hero section',
    'about.html': 'Create about page',
    'services.html': 'Build services/features page',
    'contact.html': 'Create contact page with form',
    'portfolio.html': 'Create portfolio/gallery page',
    'pricing.html': 'Build pricing page',
    'css/styles.css': 'Write custom styles and animations',
    'css/animations.css': 'Add CSS animations',
    'js/main.js': 'Add interactivity and navigation',
    'js/form.js': 'Build form validation',
    'styles.css': 'Write custom styles',
    'App.jsx': 'Build main React app component',
    'src/App.jsx': 'Create React app with routing',
    'src/components/Navbar.jsx': 'Build navigation component',
    'src/components/Hero.jsx': 'Create hero section',
    'src/components/About.jsx': 'Build about section',
    'src/components/Footer.jsx': 'Create footer component',
    'src/components/Contact.jsx': 'Build contact form',
    'src/components/Skills.jsx': 'Create skills/features section',
    'src/components/Projects.jsx': 'Build projects gallery',
    'src/pages/HomePage.jsx': 'Create home page',
    'src/pages/AboutPage.jsx': 'Create about page',
    'src/pages/ContactPage.jsx': 'Create contact page',
  };

  for (const f of files) {
    tasks.push({ file: f, label: labelMap[f] || `Create ${f}` });
  }
  return tasks;
}

// Framework-specific default tasks
function getDefaultTasks(framework: string) {
  if (framework === 'react' || framework === 'nextjs') {
    return [
      { file: 'index.html', label: 'Setup HTML with React CDN and Babel' },
      { file: 'App.jsx', label: 'Build all React components (Navbar, Hero, About, Services, Contact, Footer, App)' },
      { file: 'css/styles.css', label: 'Write custom styles and animations' },
    ];
  }
  if (framework === 'vue') {
    return [
      { file: 'index.html', label: 'Setup HTML with Vue 3 CDN' },
      { file: 'App.js', label: 'Build all Vue components' },
      { file: 'css/styles.css', label: 'Write custom styles' },
    ];
  }
  // HTML, Angular, Svelte — multi-page HTML
  return [
    { file: 'index.html', label: 'Build home page with hero section' },
    { file: 'about.html', label: 'Create about page' },
    { file: 'services.html', label: 'Build services/features page' },
    { file: 'contact.html', label: 'Create contact page with form' },
    { file: 'css/styles.css', label: 'Write custom styles and animations' },
    { file: 'js/main.js', label: 'Add interactivity and navigation' },
  ];
}

function TaskPlan({ filesChanged, isComplete, framework = 'html' }: { filesChanged: string[]; isComplete: boolean; framework?: string }) {
  const completedFiles = new Set(filesChanged || []);
  const tasks = getDefaultTasks(framework);

  return (
    <div className="space-y-1 mt-3">
      <div className="flex items-center gap-2 mb-2">
        <svg className="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" strokeWidth={1.5} viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
        </svg>
        <span className="text-xs font-semibold text-gray-300">Plan</span>
      </div>

      {tasks.map((task, i) => {
        const isDone = isComplete || (completedFiles.size > 0 && completedFiles.has(task.file));
        const isActive = !isDone && !isComplete && completedFiles.size > 0 && i === tasks.findIndex(t => !completedFiles.has(t.file));

        return (
          <div key={task.file} className="flex items-start gap-2.5 py-1.5">
            {/* Status icon */}
            {isDone ? (
              <div className="w-5 h-5 rounded-full bg-emerald-500/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg className="w-3 h-3 text-emerald-400" fill="none" stroke="currentColor" strokeWidth={3} viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" d="M5 13l4 4L19 7" />
                </svg>
              </div>
            ) : isActive ? (
              <div className="w-5 h-5 rounded-full border-2 border-blue-400 flex items-center justify-center flex-shrink-0 mt-0.5">
                <div className="w-2 h-2 bg-blue-400 rounded-full animate-pulse" />
              </div>
            ) : (
              <div className="w-5 h-5 rounded-full border border-[#2a2d37] flex-shrink-0 mt-0.5" />
            )}

            {/* Task content */}
            <div className="flex-1 min-w-0">
              <p className={`text-xs leading-relaxed ${isDone ? 'text-gray-300 font-medium' : isActive ? 'text-white font-medium' : 'text-gray-600'}`}>
                {task.label}
              </p>
              {/* Show file being written */}
              {isActive && (
                <div className="flex items-center gap-1.5 mt-1">
                  <svg className="w-3 h-3 text-blue-400 animate-spin flex-shrink-0" fill="none" viewBox="0 0 24 24">
                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                  </svg>
                  <span className="text-[11px] text-gray-500">Writing</span>
                  <code className="text-[11px] text-blue-400 bg-[#1a1d27] px-1.5 py-0.5 rounded">{task.file}</code>
                </div>
              )}
            </div>
          </div>
        );
      })}

      {isComplete && (
        <div className="flex items-center gap-2 mt-2 pt-2 border-t border-[#1e1e2e]">
          <svg className="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" strokeWidth={2} viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span className="text-xs text-emerald-400 font-medium">Plan completed</span>
        </div>
      )}
    </div>
  );
}

export default function AiChatPanel({ messages, isLoading, onSend, framework = 'html' }: Props) {
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
      <div className="flex-1 overflow-y-auto">
        {!hasMessages ? (
          <div className="flex flex-col items-center justify-center h-full px-6">
            <div className="w-14 h-14 bg-gradient-to-br from-blue-500/20 to-purple-500/20 rounded-2xl flex items-center justify-center mb-4 border border-blue-500/10">
              <svg className="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" strokeWidth={1.5} viewBox="0 0 24 24">
                <path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
              </svg>
            </div>
            <h3 className="text-sm font-semibold text-white mb-1">AI Code Assistant</h3>
            <p className="text-xs text-gray-500 text-center mb-6">Describe what you want to build</p>
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
                    <div className="flex items-center gap-2 mb-2">
                      <div className="w-5 h-5 bg-gradient-to-br from-blue-500 to-purple-600 rounded-md flex items-center justify-center text-[10px] text-white font-bold">W</div>
                      <span className="text-xs text-gray-500 font-medium">WebNewBiz AI</span>
                    </div>

                    {msg.id === -1 && isLoading ? (
                      <div>
                        <p className="text-sm text-gray-300 leading-relaxed mb-1">{msg.content}</p>
                        <TaskPlan filesChanged={msg.files_changed || []} isComplete={false} framework={framework} />
                      </div>
                    ) : (
                      <div>
                        {/* Completed message with task plan */}
                        {msg.files_changed && msg.files_changed.length > 0 ? (
                          <div>
                            <p className="text-sm text-gray-300 leading-relaxed mb-1">
                              Your website is ready! Here's what was built:
                            </p>
                            <TaskPlan filesChanged={msg.files_changed} isComplete={true} framework={framework} />

                            {/* Summary from Claude — shown after task plan */}
                            {msg.content && !msg.content.startsWith("I'll build") && (
                              <div className="mt-4 pt-3 border-t border-[#1e1e2e]">
                                <p className="text-sm text-gray-300 leading-relaxed whitespace-pre-wrap">{msg.content}</p>
                              </div>
                            )}

                            {/* Version checkpoint */}
                            <div className="mt-4 bg-[#12121a] border border-[#1e1e2e] rounded-xl px-4 py-3 flex items-center justify-between">
                              <div>
                                <p className="text-xs text-gray-300 font-medium">Website generated</p>
                                <p className="text-[11px] text-gray-600">Version 1 at {new Date(msg.created_at).toLocaleString()}</p>
                              </div>
                              <svg className="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" strokeWidth={1.5} viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z" /></svg>
                            </div>
                          </div>
                        ) : (
                          <p className="text-sm text-gray-300 leading-relaxed whitespace-pre-wrap">{msg.content}</p>
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

      {/* Input */}
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
            <button className="p-1.5 text-gray-600 hover:text-gray-300 rounded-lg hover:bg-white/5 transition">
              <svg className="w-4 h-4" fill="none" stroke="currentColor" strokeWidth={1.5} viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" d="M12 4v16m8-8H4" /></svg>
            </button>
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
