import { useState, useEffect, useCallback, useRef } from 'react';
import { useParams, useNavigate, useSearchParams } from 'react-router-dom';
import { projectService } from '../services/project.service';
import type { Project, ProjectMessage, FileTreeNode } from '../models/types';
import FileExplorer from '../components/builder/FileExplorer';
import CodeEditor from '../components/builder/CodeEditor';
import PreviewPanel from '../components/builder/PreviewPanel';
import AiChatPanel from '../components/builder/AiChatPanel';
import Terminal from '../components/builder/Terminal';

type TopView = 'preview' | 'code' | 'chat' | 'settings';
type BottomTab = 'output' | 'terminal';

export default function CodeBuilder() {
  const { id } = useParams<{ id: string }>();
  const navigate = useNavigate();
  const [searchParams] = useSearchParams();
  const initialPrompt = searchParams.get('prompt');

  const [project, setProject] = useState<Project | null>(null);
  const [loading, setLoading] = useState(true);
  const [fileTree, setFileTree] = useState<FileTreeNode[]>([]);
  const [activeFile, setActiveFile] = useState<string | null>(null);
  const [openFiles, setOpenFiles] = useState<string[]>([]);
  const [fileContent, setFileContent] = useState('');
  const [unsavedChanges, setUnsavedChanges] = useState(false);
  const [messages, setMessages] = useState<ProjectMessage[]>([]);
  const [chatLoading, setChatLoading] = useState(false);
  const [topView, setTopView] = useState<TopView>('preview');
  const [bottomTab, setBottomTab] = useState<BottomTab>('output');
  const [bottomOpen, setBottomOpen] = useState(false);
  const [iframeKey, setIframeKey] = useState(0);
  const [showSettings, setShowSettings] = useState(false);
  const [outputLog, setOutputLog] = useState<string[]>([]);

  // Stream polling
  const pollingRef = useRef<ReturnType<typeof setInterval> | null>(null);
  const streamTextRef = useRef('');
  const projectRef = useRef<Project | null>(null);
  useEffect(() => { projectRef.current = project; }, [project]);

  // Load project
  useEffect(() => {
    if (!id) { setLoading(false); return; }
    loadProject(parseInt(id));
  }, [id]);

  // Auto-send initial prompt from PromptPage
  const sentInitialRef = useRef(false);
  useEffect(() => {
    if (project && initialPrompt && !sentInitialRef.current && !chatLoading) {
      sentInitialRef.current = true;
      // Clear any existing messages and start fresh
      setMessages([]);
      setTimeout(() => handleChatSend(initialPrompt, project), 300);
    }
  }, [project, initialPrompt]);

  const loadProject = async (pid: number) => {
    try {
      setLoading(true);
      const [projRes, msgRes] = await Promise.all([projectService.get(pid), projectService.getMessages(pid)]);
      setProject(projRes.data);
      setFileTree(projRes.data.file_tree || []);
      setMessages(msgRes.data);
      const first = findFirstFile(projRes.data.file_tree || []);
      if (first) await openFile(pid, first);
    } catch { } finally { setLoading(false); }
  };

  const findFirstFile = (tree: FileTreeNode[]): string | null => {
    for (const n of tree) {
      if (n.type === 'file') return n.path;
      if (n.children) { const f = findFirstFile(n.children); if (f) return f; }
    }
    return null;
  };

  const openFile = async (pid: number, path: string) => {
    try {
      const res = await projectService.readFile(pid, path);
      setActiveFile(path);
      setFileContent(res.data.content);
      setUnsavedChanges(false);
      setOpenFiles(prev => prev.includes(path) ? prev : [...prev, path]);
      setTopView('code');
    } catch { }
  };

  const handleFileSelect = (path: string) => { if (project) openFile(project.id, path); };

  const handleCloseFile = (path: string) => {
    setOpenFiles(prev => prev.filter(f => f !== path));
    if (activeFile === path) {
      const remaining = openFiles.filter(f => f !== path);
      if (remaining.length && project) openFile(project.id, remaining[remaining.length - 1]);
      else { setActiveFile(null); setFileContent(''); }
    }
  };

  const handleFileDelete = async (path: string) => {
    if (!project || !confirm(`Delete ${path}?`)) return;
    try {
      const res = await projectService.deleteFile(project.id, path);
      setFileTree(res.data.file_tree);
      if (activeFile === path) { setActiveFile(null); setFileContent(''); }
      setOpenFiles(prev => prev.filter(f => f !== path));
    } catch { }
  };

  const handleEditorChange = (value: string) => { setFileContent(value); setUnsavedChanges(true); };

  const handleSave = useCallback(async () => {
    if (!project || !activeFile || !unsavedChanges) return;
    try {
      const res = await projectService.writeFile(project.id, activeFile, fileContent);
      setFileTree(res.data.file_tree);
      setUnsavedChanges(false);
      setIframeKey(k => k + 1);
    } catch { }
  }, [project, activeFile, fileContent, unsavedChanges]);

  // Stream polling for AI generation
  const startStreamPolling = (pid: number) => {
    if (pollingRef.current) clearInterval(pollingRef.current);
    streamTextRef.current = '';
    pollingRef.current = setInterval(async () => {
      try {
        const res = await projectService.getStream(pid);
        const data = res.data;
        if (data.file_tree?.length) { setFileTree(data.file_tree); setIframeKey(k => k + 1); }

        // ALWAYS update assistant message with latest files_changed (for real-time task plan)
        setMessages(prev => {
          const last = prev[prev.length - 1];
          if (last?.role === 'assistant' && last.id === -1) {
            return [...prev.slice(0, -1), {
              ...last,
              content: data.text || last.content,
              files_changed: data.files_changed?.length ? data.files_changed : last.files_changed,
            }];
          }
          return prev;
        });
        if (data.status === 'done') {
          stopPolling();
          setChatLoading(false);
          addOutput('Build completed successfully.');

          // Update chat message
          setMessages(prev => {
            const last = prev[prev.length - 1];
            if (last?.role === 'assistant' && last.id === -1)
              return [...prev.slice(0, -1), { ...last, id: Date.now(), content: data.text || 'Code generation complete!', files_changed: data.files_changed }];
            return prev;
          });

          // Reload full project from server to get fresh file_tree
          try {
            const freshProject = await projectService.get(pid);
            setProject(freshProject.data);
            setFileTree(freshProject.data.file_tree || []);
          } catch {
            if (data.file_tree?.length) setFileTree(data.file_tree);
          }

          // Open first changed file in editor
          if (data.files_changed?.length) {
            const firstFile = data.files_changed.find((f: string) => f.endsWith('.html')) || data.files_changed[0];
            await openFile(pid, firstFile);
          }

          // Auto-switch to preview to show the generated website
          setTopView('preview');
          setIframeKey(k => k + 1);

          // Reload messages from DB
          try { const m = await projectService.getMessages(pid); setMessages(m.data); } catch { }

          addOutput(`Files created: ${data.files_changed?.join(', ') || 'unknown'}`);
        }
      } catch { }
    }, 3000);
  };

  const stopPolling = () => { if (pollingRef.current) { clearInterval(pollingRef.current); pollingRef.current = null; } };
  useEffect(() => () => stopPolling(), []);

  const handleChatSend = async (message: string, projectOverride?: Project) => {
    const proj = projectOverride || projectRef.current;
    if (!proj) return;
    setChatLoading(true);
    addOutput(`> ${message}`);
    addOutput('Starting code generation...');
    setMessages(prev => [...prev, { id: Date.now(), role: 'user', content: message, files_changed: null, created_at: new Date().toISOString() }]);

    // Build Bolt-style description based on user prompt
    const description = `I'll build a modern, production-ready website for "${proj.name}". Let me break this down into tasks:`;

    setMessages(prev => [...prev, { id: -1, role: 'assistant', content: description, files_changed: [], created_at: new Date().toISOString() }]);
    try {
      await projectService.chat(proj.id, message);
      startStreamPolling(proj.id);
    } catch {
      setChatLoading(false);
      setMessages(prev => [...prev.filter(m => m.id !== -1), { id: Date.now() + 1, role: 'assistant', content: 'Failed to start generation. Please try again.', files_changed: null, created_at: new Date().toISOString() }]);
    }
  };

  const addOutput = (msg: string) => setOutputLog(prev => [...prev, `[${new Date().toLocaleTimeString()}] ${msg}`]);

  const previewUrl = project ? projectService.getPreviewUrl(project.id) : '';

  // Loading state
  if (loading) return (
    <div className="fixed inset-0 bg-[#0a0a0f] flex items-center justify-center">
      <div className="flex flex-col items-center gap-4">
        <div className="w-12 h-12 border-2 border-blue-500/30 border-t-blue-500 rounded-full animate-spin" />
        <span className="text-sm text-gray-500">Loading project...</span>
      </div>
    </div>
  );

  if (!project) { navigate('/code-builder'); return null; }

  return (
    <div className="fixed inset-0 bg-[#0d1017] flex flex-col text-sm">
      {/* ═══ TOP BAR ═══ */}
      <header className="h-12 bg-[#0d1017] border-b border-[#1a1d27] flex items-center px-4 justify-between flex-shrink-0 z-30">
        <div className="flex items-center gap-3">
          <button onClick={() => navigate('/code-builder')} className="w-7 h-7 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-xs">W</button>
          <span className="text-sm font-medium text-gray-200">{project.name}</span>
          {unsavedChanges && <div className="w-2 h-2 bg-amber-500 rounded-full" />}
        </div>

        {/* Center — View toggle icons */}
        <div className="flex items-center bg-[#12121a] rounded-xl border border-[#1e1e2e] p-0.5">
          {([
            { id: 'preview' as TopView, icon: <><path strokeLinecap="round" strokeLinejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /><path strokeLinecap="round" strokeLinejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></>, tip: 'Preview' },
            { id: 'code' as TopView, icon: <><polyline points="16 18 22 12 16 6" /><polyline points="8 6 2 12 8 18" /></>, tip: 'Code' },
            { id: 'chat' as TopView, icon: <><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z" /></>, tip: 'Chat' },
          ]).map(v => (
            <button key={v.id} onClick={() => setTopView(v.id)} title={v.tip}
              className={`p-2 rounded-lg transition ${topView === v.id ? 'bg-[#1e1e2e] text-white' : 'text-gray-500 hover:text-gray-300'}`}>
              <svg className="w-4 h-4" fill="none" stroke="currentColor" strokeWidth={1.5} viewBox="0 0 24 24">{v.icon}</svg>
            </button>
          ))}
          <div className="w-px h-5 bg-[#1e1e2e] mx-0.5" />
          <div className="relative">
            <button onClick={() => setShowSettings(!showSettings)} title="Settings"
              className={`p-2 rounded-lg transition ${showSettings ? 'bg-[#1e1e2e] text-white' : 'text-gray-500 hover:text-gray-300'}`}>
              <svg className="w-4 h-4" fill="none" stroke="currentColor" strokeWidth={1.5} viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><circle cx="12" cy="12" r="3" /></svg>
            </button>
            {showSettings && (
              <>
                <div className="fixed inset-0 z-20" onClick={() => setShowSettings(false)} />
                <div className="absolute top-full right-0 mt-2 w-56 bg-[#1a1d27] border border-[#2a2d37] rounded-xl shadow-xl z-30 py-1 overflow-hidden">
                  {[
                    { icon: '📊', label: 'Analytics' }, { icon: '🔐', label: 'Authentication' },
                    { icon: '🧠', label: 'Knowledge' }, { icon: '⚡', label: 'Server Functions' },
                    { icon: '🔑', label: 'Secrets' }, { icon: '🔗', label: 'Connectors' },
                  ].map(item => (
                    <button key={item.label} className="w-full flex items-center gap-3 px-4 py-2.5 text-xs text-gray-300 hover:bg-white/5 transition">
                      <span>{item.icon}</span><span>{item.label}</span>
                    </button>
                  ))}
                  <div className="border-t border-[#2a2d37] my-1" />
                  <button className="w-full flex items-center gap-3 px-4 py-2.5 text-xs text-gray-300 hover:bg-white/5"><span>⚙️</span><span>All project settings</span><svg className="w-3 h-3 ml-auto text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" /></svg></button>
                </div>
              </>
            )}
          </div>
        </div>

        {/* Right — Share + Publish */}
        <div className="flex items-center gap-2">
          <span className={`px-2.5 py-1 rounded-md text-xs font-medium ${
            project.framework === 'react' ? 'bg-cyan-500/10 text-cyan-400' :
            project.framework === 'vue' ? 'bg-emerald-500/10 text-emerald-400' :
            project.framework === 'angular' ? 'bg-red-500/10 text-red-400' :
            'bg-gray-500/10 text-gray-400'
          }`}>
            {{ html: 'HTML', react: 'React', nextjs: 'Next.js', vue: 'Vue', angular: 'Angular', svelte: 'Svelte' }[project.framework]}
          </span>
          <button className="px-4 py-1.5 text-sm text-gray-400 hover:text-white border border-[#1e1e2e] rounded-xl transition">Share</button>
          <button className="px-4 py-1.5 text-sm text-white bg-blue-600 hover:bg-blue-500 rounded-xl font-medium transition">Publish</button>
        </div>
      </header>

      {/* ═══ MAIN AREA — 3 columns ═══ */}
      <div className="flex-1 flex min-h-0">
        {/* LEFT — AI Chat (always visible) */}
        <div className="w-[420px] flex-shrink-0 border-r border-[#1a1d27] flex flex-col bg-[#0a0a0f]">
          <AiChatPanel messages={messages} isLoading={chatLoading} onSend={handleChatSend} />
        </div>

        {/* RIGHT — Preview / Code / File Explorer */}
        <div className="flex-1 flex flex-col min-h-0 min-w-0">
          {/* Main content area */}
          <div className="flex-1 flex min-h-0">
            {topView === 'preview' && (
              <div className="flex-1 min-h-0">
                <PreviewPanel previewUrl={previewUrl} iframeKey={iframeKey} onRefresh={() => setIframeKey(k => k + 1)} />
              </div>
            )}
            {topView === 'code' && (
              <>
                {/* File explorer */}
                <div className="w-56 flex-shrink-0 border-r border-[#1a1d27] overflow-hidden">
                  <div className="h-10 flex items-center gap-4 px-4 border-b border-[#1a1d27]">
                    <span className="text-xs font-medium text-gray-400">Files</span>
                    <button className="text-xs text-gray-600 hover:text-gray-300 transition">Search</button>
                  </div>
                  <FileExplorer
                    fileTree={fileTree} activeFile={activeFile}
                    onFileSelect={handleFileSelect} onFileDelete={handleFileDelete}
                    onFileCreate={async (path, type) => {
                      if (!project) return;
                      try { const r = await projectService.createFile(project.id, path, type); setFileTree(r.data.file_tree); if (type === 'file') await openFile(project.id, path); } catch { }
                    }}
                  />
                </div>
                {/* Code editor */}
                <div className="flex-1 min-h-0 min-w-0">
                  <CodeEditor
                    filePath={activeFile} content={fileContent}
                    onChange={handleEditorChange} onSave={handleSave}
                    openFiles={openFiles} onFileSelect={p => project && openFile(project.id, p)}
                    onCloseFile={handleCloseFile} unsavedFile={unsavedChanges ? activeFile : null}
                  />
                </div>
              </>
            )}
            {topView === 'chat' && (
              <div className="flex-1 flex items-center justify-center text-gray-600">
                <p>Chat is always visible on the left panel</p>
              </div>
            )}
          </div>

          {/* ═══ BOTTOM TAB BAR ═══ */}
          <div className="border-t border-[#1a1d27]">
            {/* Tab buttons */}
            <div className="h-9 flex items-center px-3 gap-1 bg-[#0d1017]">
              {([
                { id: 'output' as BottomTab, icon: '⚡', label: 'Output' },
                { id: 'terminal' as BottomTab, icon: '💻', label: 'Terminal' },
              ]).map(tab => (
                <button key={tab.id} onClick={() => { setBottomTab(tab.id); setBottomOpen(true); }}
                  className={`flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs transition ${bottomTab === tab.id && bottomOpen ? 'bg-[#1e1e2e] text-white' : 'text-gray-500 hover:text-gray-300'}`}>
                  <span className="text-[10px]">{tab.icon}</span>{tab.label}
                </button>
              ))}
              <button className="ml-1 text-gray-600 hover:text-gray-300 text-lg leading-none">+</button>
              <div className="flex-1" />
              {chatLoading && <span className="text-xs text-amber-400 flex items-center gap-1.5"><div className="w-1.5 h-1.5 bg-amber-400 rounded-full animate-pulse" />Generating...</span>}
              <button onClick={() => setBottomOpen(!bottomOpen)} className="text-gray-600 hover:text-gray-300 p-1">
                <svg className={`w-4 h-4 transition-transform ${bottomOpen ? '' : 'rotate-180'}`} fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" /></svg>
              </button>
            </div>

            {/* Tab content */}
            {bottomOpen && (
              <div className="h-48 bg-[#0a0a12] overflow-hidden">
                {bottomTab === 'output' && (
                  <div className="h-full overflow-auto p-3 font-mono text-xs space-y-0.5">
                    {outputLog.length > 0 ? outputLog.map((line, i) => (
                      <div key={i} className="text-gray-500">{line}</div>
                    )) : (
                      <div className="text-gray-600">No output yet. Send a prompt to start building.</div>
                    )}
                  </div>
                )}
                {bottomTab === 'terminal' && project && (
                  <Terminal projectId={project.id} onCommand={async (cmd) => {
                    const res = await projectService.terminal(project.id, cmd);
                    if (/^(touch|mkdir|rm|mv|cp|npm|npx|git|yarn)/.test(cmd)) {
                      try { const p = await projectService.get(project.id); setFileTree(p.data.file_tree || []); } catch { }
                    }
                    return res.data;
                  }} />
                )}
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}
