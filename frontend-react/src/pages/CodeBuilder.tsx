import { useState, useEffect, useCallback, useRef } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { projectService } from '../services/project.service';
import type { Project, ProjectMessage, FileTreeNode } from '../models/types';
import FileExplorer from '../components/builder/FileExplorer';
import CodeEditor from '../components/builder/CodeEditor';
import PreviewPanel from '../components/builder/PreviewPanel';
import AiChatPanel from '../components/builder/AiChatPanel';
import Terminal from '../components/builder/Terminal';
import GitPanel from '../components/builder/GitPanel';
import SearchPanel from '../components/builder/SearchPanel';

type SidebarTab = 'files' | 'ai' | 'search' | 'git' | 'settings';

export default function CodeBuilder() {
  const { id } = useParams<{ id: string }>();
  const navigate = useNavigate();

  // Project state
  const [project, setProject] = useState<Project | null>(null);
  const [loading, setLoading] = useState(true);

  // File state
  const [fileTree, setFileTree] = useState<FileTreeNode[]>([]);
  const [activeFile, setActiveFile] = useState<string | null>(null);
  const [openFiles, setOpenFiles] = useState<string[]>([]);
  const [fileContent, setFileContent] = useState('');
  const [unsavedChanges, setUnsavedChanges] = useState(false);

  // Chat state
  const [messages, setMessages] = useState<ProjectMessage[]>([]);
  const [chatLoading, setChatLoading] = useState(false);

  // UI state
  const [sidebarTab, setSidebarTab] = useState<SidebarTab>('ai');
  const [iframeKey, setIframeKey] = useState(0);
  const [sidebarWidth, setSidebarWidth] = useState(320);
  const [editorWidth, setEditorWidth] = useState(50); // percentage of remaining
  const [showCreateModal, setShowCreateModal] = useState(!id);
  const [sidebarCollapsed, setSidebarCollapsed] = useState(false);
  const [bottomPanelHeight] = useState(0);
  const [showBottomPanel, setShowBottomPanel] = useState(false);

  // Create modal state
  const [newName, setNewName] = useState('');
  const [newFramework, setNewFramework] = useState<'html' | 'react' | 'nextjs' | 'vue' | 'angular' | 'svelte'>('html');
  const [newPrompt, setNewPrompt] = useState('');
  const [creating, setCreating] = useState(false);

  // Resizer refs
  const sidebarDragging = useRef(false);
  const editorDragging = useRef(false);

  // Load project
  useEffect(() => {
    if (!id) { setLoading(false); setShowCreateModal(true); return; }
    loadProject(parseInt(id));
  }, [id]);

  const loadProject = async (projectId: number) => {
    try {
      setLoading(true);
      const [projRes, msgRes] = await Promise.all([
        projectService.get(projectId),
        projectService.getMessages(projectId),
      ]);
      setProject(projRes.data);
      setFileTree(projRes.data.file_tree || []);
      setMessages(msgRes.data);
      const firstFile = findFirstFile(projRes.data.file_tree || []);
      if (firstFile) await openFile(projectId, firstFile);
    } catch (err) {
      console.error('Failed to load project:', err);
    } finally {
      setLoading(false);
    }
  };

  const findFirstFile = (tree: FileTreeNode[]): string | null => {
    for (const node of tree) {
      if (node.type === 'file') return node.path;
      if (node.children) { const f = findFirstFile(node.children); if (f) return f; }
    }
    return null;
  };

  const openFile = async (projectId: number, path: string) => {
    try {
      const res = await projectService.readFile(projectId, path);
      setActiveFile(path);
      setFileContent(res.data.content);
      setUnsavedChanges(false);
      setOpenFiles(prev => prev.includes(path) ? prev : [...prev, path]);
    } catch (err) {
      console.error('Failed to read file:', err);
    }
  };

  const handleFileSelect = (path: string) => {
    if (project) openFile(project.id, path);
  };

  const handleCloseFile = (path: string) => {
    setOpenFiles(prev => prev.filter(f => f !== path));
    if (activeFile === path) {
      const remaining = openFiles.filter(f => f !== path);
      if (remaining.length) {
        if (project) openFile(project.id, remaining[remaining.length - 1]);
      } else {
        setActiveFile(null);
        setFileContent('');
      }
    }
  };

  const handleFileDelete = async (path: string) => {
    if (!project || !confirm(`Delete ${path}?`)) return;
    try {
      const res = await projectService.deleteFile(project.id, path);
      setFileTree(res.data.file_tree);
      if (activeFile === path) { setActiveFile(null); setFileContent(''); }
      setOpenFiles(prev => prev.filter(f => f !== path));
      refreshPreview();
    } catch (err) {
      console.error('Failed to delete file:', err);
    }
  };

  const handleEditorChange = (value: string) => {
    setFileContent(value);
    setUnsavedChanges(true);
  };

  const handleSave = useCallback(async () => {
    if (!project || !activeFile || !unsavedChanges) return;
    try {
      const res = await projectService.writeFile(project.id, activeFile, fileContent);
      setFileTree(res.data.file_tree);
      setUnsavedChanges(false);
      refreshPreview();
    } catch (err) {
      console.error('Failed to save file:', err);
    }
  }, [project, activeFile, fileContent, unsavedChanges]);

  // Stream polling — polls /stream endpoint for real-time AI progress
  const pollingRef = useRef<ReturnType<typeof setInterval> | null>(null);
  const streamTextRef = useRef('');

  const startStreamPolling = (projectId: number) => {
    if (pollingRef.current) clearInterval(pollingRef.current);
    streamTextRef.current = '';

    pollingRef.current = setInterval(async () => {
      try {
        const res = await projectService.getStream(projectId);
        const data = res.data;

        // Update file tree in real-time (files appear as Claude creates them)
        if (data.file_tree?.length) {
          setFileTree(data.file_tree);
          refreshPreview();
        }

        // Update the streaming text in chat (show what Claude is doing)
        if (data.text && data.text !== streamTextRef.current) {
          streamTextRef.current = data.text;
          // Update the last assistant message with new text
          setMessages(prev => {
            const lastMsg = prev[prev.length - 1];
            if (lastMsg?.role === 'assistant' && lastMsg.id === -1) {
              return [...prev.slice(0, -1), { ...lastMsg, content: data.text, files_changed: data.files_changed }];
            }
            return prev;
          });
        }

        // Check if done
        if (data.status === 'done') {
          stopPolling();
          setChatLoading(false);

          // Finalize the message
          setMessages(prev => {
            const lastMsg = prev[prev.length - 1];
            if (lastMsg?.role === 'assistant' && lastMsg.id === -1) {
              return [...prev.slice(0, -1), { ...lastMsg, id: Date.now(), content: data.text || 'Code generation complete!', files_changed: data.files_changed }];
            }
            return [...prev, { id: Date.now(), role: 'assistant', content: data.text || 'Code generation complete!', files_changed: data.files_changed, created_at: new Date().toISOString() }];
          });

          if (data.file_tree?.length) setFileTree(data.file_tree);

          // Open first changed file
          if (data.files_changed?.length) {
            await openFile(projectId, data.files_changed[0]);
            setSidebarTab('files');
          }

          refreshPreview();

          // Reload messages from DB to get proper IDs
          try {
            const msgRes = await projectService.getMessages(projectId);
            setMessages(msgRes.data);
          } catch { /* ignore */ }
        }
      } catch { /* ignore polling errors */ }
    }, 3000); // poll every 3 seconds
  };

  const stopPolling = () => { if (pollingRef.current) { clearInterval(pollingRef.current); pollingRef.current = null; } };
  useEffect(() => () => stopPolling(), []);

  // Use ref to always have latest project available
  const projectRef = useRef<Project | null>(null);
  useEffect(() => { projectRef.current = project; }, [project]);

  const handleChatSend = async (message: string, projectOverride?: Project) => {
    const proj = projectOverride || projectRef.current;
    if (!proj) return;
    setChatLoading(true);

    // Add user message
    setMessages(prev => [...prev, { id: Date.now(), role: 'user', content: message, files_changed: null, created_at: new Date().toISOString() }]);

    // Add placeholder assistant message (will be updated by stream polling)
    setMessages(prev => [...prev, { id: -1, role: 'assistant', content: 'Starting code generation...', files_changed: null, created_at: new Date().toISOString() }]);

    try {
      // This returns immediately — Claude runs in background
      await projectService.chat(proj.id, message);

      // Start polling for updates
      startStreamPolling(proj.id);
    } catch {
      setChatLoading(false);
      setMessages(prev => {
        const filtered = prev.filter(m => m.id !== -1);
        return [...filtered, { id: Date.now() + 1, role: 'assistant', content: 'Failed to start AI generation. Please try again.', files_changed: null, created_at: new Date().toISOString() }];
      });
    }
  };

  const refreshPreview = () => setIframeKey(k => k + 1);

  const handleCreate = async () => {
    if (!newName.trim()) return;
    setCreating(true);
    try {
      const res = await projectService.create({ name: newName, framework: newFramework });
      const proj = res.data.project;
      setShowCreateModal(false);
      setProject(proj);
      setFileTree(proj.file_tree || []);
      navigate(`/code-builder/${proj.id}`, { replace: true });
      setLoading(false);
      if (newPrompt?.trim()) handleChatSend(newPrompt.trim(), proj);
    } catch (err) {
      console.error('Failed to create project:', err);
      setCreating(false);
    }
  };

  // Resizer handlers
  useEffect(() => {
    const onMove = (e: MouseEvent) => {
      if (sidebarDragging.current) {
        setSidebarWidth(Math.max(240, Math.min(600, e.clientX - 48)));
      }
      if (editorDragging.current) {
        const mainArea = window.innerWidth - (sidebarCollapsed ? 48 : 48 + sidebarWidth);
        const pct = ((e.clientX - (sidebarCollapsed ? 48 : 48 + sidebarWidth)) / mainArea) * 100;
        setEditorWidth(Math.max(20, Math.min(80, pct)));
      }
    };
    const onUp = () => { sidebarDragging.current = false; editorDragging.current = false; document.body.style.cursor = ''; document.body.style.userSelect = ''; };
    window.addEventListener('mousemove', onMove);
    window.addEventListener('mouseup', onUp);
    return () => { window.removeEventListener('mousemove', onMove); window.removeEventListener('mouseup', onUp); };
  }, [sidebarWidth, sidebarCollapsed]);

  const startDrag = (which: 'sidebar' | 'editor') => {
    if (which === 'sidebar') sidebarDragging.current = true;
    else editorDragging.current = true;
    document.body.style.cursor = 'col-resize';
    document.body.style.userSelect = 'none';
  };

  const previewUrl = project ? projectService.getPreviewUrl(project.id) : '';
  const fileCount = fileTree.length;

  // ── CREATE MODAL ──
  if (showCreateModal) {
    return (
      <div className="fixed inset-0 bg-[#0a0a0f] flex items-center justify-center z-50">
        {/* Animated background */}
        <div className="absolute inset-0 overflow-hidden">
          <div className="absolute top-1/4 left-1/4 w-96 h-96 bg-blue-600/5 rounded-full blur-3xl animate-pulse" />
          <div className="absolute bottom-1/4 right-1/4 w-96 h-96 bg-purple-600/5 rounded-full blur-3xl animate-pulse" style={{ animationDelay: '1s' }} />
        </div>

        <div className="relative bg-[#12121a] border border-[#1e1e2e] rounded-2xl p-8 w-full max-w-xl shadow-2xl shadow-blue-900/10">
          {/* Header */}
          <div className="flex items-center gap-3 mb-6">
            <div className="w-10 h-10 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/25">
              <svg className="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
            </div>
            <div>
              <h2 className="text-lg font-semibold text-white">Create New Project</h2>
              <p className="text-sm text-gray-500">Build something incredible with AI</p>
            </div>
          </div>

          <div className="space-y-5">
            {/* Project Name */}
            <div>
              <label className="block text-sm font-medium text-gray-300 mb-1.5">Project Name</label>
              <input
                value={newName}
                onChange={(e) => setNewName(e.target.value)}
                placeholder="My Awesome Project"
                className="w-full bg-[#0a0a12] border border-[#1e1e2e] rounded-xl px-4 py-3 text-white text-sm outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/25 transition-all placeholder-gray-600"
                autoFocus
                onKeyDown={(e) => e.key === 'Enter' && newName.trim() && handleCreate()}
              />
            </div>

            {/* Framework */}
            <div>
              <label className="block text-sm font-medium text-gray-300 mb-1.5">Framework</label>
              <div className="grid grid-cols-3 gap-3">
                {([
                  { id: 'html' as const, name: 'HTML/CSS/JS', icon: '🌐', desc: 'Vanilla web' },
                  { id: 'react' as const, name: 'React', icon: '⚛️', desc: 'Component-based' },
                  { id: 'nextjs' as const, name: 'Next.js', icon: '▲', desc: 'Full-stack React' },
                  { id: 'vue' as const, name: 'Vue.js', icon: '💚', desc: 'Progressive JS' },
                  { id: 'angular' as const, name: 'Angular', icon: '🅰️', desc: 'Enterprise apps' },
                  { id: 'svelte' as const, name: 'Svelte', icon: '🔥', desc: 'Compiled UI' },
                ]).map((fw) => (
                  <button
                    key={fw.id}
                    onClick={() => setNewFramework(fw.id)}
                    className={`relative px-4 py-3 rounded-xl border text-left transition-all ${
                      newFramework === fw.id
                        ? 'bg-blue-600/10 border-blue-500/50 shadow-lg shadow-blue-500/10'
                        : 'bg-[#0a0a12] border-[#1e1e2e] hover:border-[#2e2e3e]'
                    }`}
                  >
                    <div className="text-lg mb-1">{fw.icon}</div>
                    <div className={`text-sm font-medium ${newFramework === fw.id ? 'text-blue-400' : 'text-gray-300'}`}>{fw.name}</div>
                    <div className="text-xs text-gray-600">{fw.desc}</div>
                    {newFramework === fw.id && (
                      <div className="absolute top-2 right-2 w-2 h-2 bg-blue-500 rounded-full" />
                    )}
                  </button>
                ))}
              </div>
            </div>

            {/* Prompt */}
            <div>
              <label className="block text-sm font-medium text-gray-300 mb-1.5">
                Describe your project <span className="text-gray-600 font-normal">(optional)</span>
              </label>
              <textarea
                value={newPrompt}
                onChange={(e) => setNewPrompt(e.target.value)}
                placeholder="e.g., A modern SaaS landing page with dark theme, pricing section, testimonials, and a newsletter signup..."
                rows={3}
                className="w-full bg-[#0a0a12] border border-[#1e1e2e] rounded-xl px-4 py-3 text-white text-sm outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/25 transition-all resize-none placeholder-gray-600"
              />
            </div>
          </div>

          {/* Actions */}
          <div className="flex justify-end gap-3 mt-7">
            <button
              onClick={() => navigate('/dashboard')}
              className="px-5 py-2.5 text-sm text-gray-400 hover:text-white rounded-xl hover:bg-white/5 transition"
            >
              Cancel
            </button>
            <button
              onClick={handleCreate}
              disabled={!newName.trim() || creating}
              className="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-xl text-sm font-medium hover:from-blue-500 hover:to-blue-400 disabled:opacity-40 disabled:cursor-not-allowed transition-all shadow-lg shadow-blue-600/25 flex items-center gap-2"
            >
              {creating ? (
                <><svg className="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" /><path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" /></svg>Creating...</>
              ) : (
                <><svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>Create Project</>
              )}
            </button>
          </div>
        </div>
      </div>
    );
  }

  // ── LOADING ──
  if (loading) {
    return (
      <div className="fixed inset-0 bg-[#0a0a0f] flex items-center justify-center">
        <div className="flex flex-col items-center gap-4">
          <div className="w-12 h-12 border-2 border-blue-500/30 border-t-blue-500 rounded-full animate-spin" />
          <span className="text-sm text-gray-500">Loading project...</span>
        </div>
      </div>
    );
  }

  // ── MAIN IDE LAYOUT ──
  return (
    <div className="fixed inset-0 bg-[#0d1017] flex flex-col text-sm select-none">
      {/* ═══ TOP BAR ═══ */}
      <header className="h-12 bg-[#0d1017] border-b border-[#1a1d27] flex items-center px-4 justify-between flex-shrink-0 z-20">
        {/* Left side */}
        <div className="flex items-center gap-3">
          <button onClick={() => navigate('/dashboard')} className="text-gray-500 hover:text-white transition p-1">
            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
          </button>
          <div className="w-px h-5 bg-[#1a1d27]" />
          <div className="flex items-center gap-2">
            <div className="w-6 h-6 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
              <svg className="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
              </svg>
            </div>
            <span className="text-sm font-medium text-gray-200">{project?.name}</span>
            {unsavedChanges && <div className="w-2 h-2 bg-amber-500 rounded-full" title="Unsaved changes" />}
          </div>
        </div>

        {/* Center - Run button */}
        <div className="flex items-center gap-2">
          <button
            onClick={refreshPreview}
            className="flex items-center gap-1.5 px-4 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-medium transition shadow-lg shadow-emerald-600/20"
          >
            <svg className="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
              <path d="M6.3 2.84A1.5 1.5 0 004 4.11v11.78a1.5 1.5 0 002.3 1.27l9.344-5.891a1.5 1.5 0 000-2.538L6.3 2.84z" />
            </svg>
            Run
          </button>
        </div>

        {/* Right side */}
        <div className="flex items-center gap-2">
          <span className={`px-2.5 py-1 rounded-md text-xs font-medium ${
            project?.framework === 'react' ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' :
            project?.framework === 'nextjs' ? 'bg-white/10 text-white border border-white/20' :
            project?.framework === 'vue' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' :
            project?.framework === 'angular' ? 'bg-red-500/10 text-red-400 border border-red-500/20' :
            project?.framework === 'svelte' ? 'bg-orange-500/10 text-orange-400 border border-orange-500/20' :
            'bg-orange-500/10 text-orange-400 border border-orange-500/20'
          }`}>
            {{ html: 'HTML', react: 'React', nextjs: 'Next.js', vue: 'Vue.js', angular: 'Angular', svelte: 'Svelte' }[project?.framework || 'html']}
          </span>
          <span className={`px-2.5 py-1 rounded-md text-xs font-medium flex items-center gap-1.5 ${
            chatLoading ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' :
            project?.status === 'ready' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' :
            'bg-gray-500/10 text-gray-400 border border-gray-500/20'
          }`}>
            <div className={`w-1.5 h-1.5 rounded-full ${chatLoading ? 'bg-amber-400 animate-pulse' : project?.status === 'ready' ? 'bg-emerald-400' : 'bg-gray-400'}`} />
            {chatLoading ? 'generating' : project?.status}
          </span>
        </div>
      </header>

      {/* ═══ MAIN AREA ═══ */}
      <div className="flex-1 flex min-h-0">
        {/* ── ACTIVITY BAR (far left icons) ── */}
        <div className="w-12 bg-[#0d1017] border-r border-[#1a1d27] flex flex-col items-center py-2 gap-1 flex-shrink-0">
          {([
            { id: 'files' as SidebarTab, icon: <><path d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" /></>, tip: 'Files' },
            { id: 'ai' as SidebarTab, icon: <><path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" /></>, tip: 'AI Assistant' },
            { id: 'search' as SidebarTab, icon: <><circle cx="11" cy="11" r="8" /><path d="M21 21l-4.35-4.35" /></>, tip: 'Search' },
            { id: 'git' as SidebarTab, icon: <><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></>, tip: 'Git' },
          ]).map((item) => (
            <button
              key={item.id}
              onClick={() => {
                if (sidebarTab === item.id && !sidebarCollapsed) setSidebarCollapsed(true);
                else { setSidebarTab(item.id); setSidebarCollapsed(false); }
              }}
              className={`w-10 h-10 rounded-xl flex items-center justify-center transition-all ${
                sidebarTab === item.id && !sidebarCollapsed
                  ? 'bg-blue-600/15 text-blue-400'
                  : 'text-gray-600 hover:text-gray-300 hover:bg-white/5'
              }`}
              title={item.tip}
            >
              <svg className="w-5 h-5" fill="none" stroke="currentColor" strokeWidth={1.5} viewBox="0 0 24 24">
                {item.icon}
              </svg>
            </button>
          ))}

          <div className="flex-1" />

          {/* Settings */}
          <button
            onClick={() => { setSidebarTab('settings'); setSidebarCollapsed(false); }}
            className={`w-10 h-10 rounded-xl flex items-center justify-center transition-all ${
              sidebarTab === 'settings' && !sidebarCollapsed ? 'bg-blue-600/15 text-blue-400' : 'text-gray-600 hover:text-gray-300 hover:bg-white/5'
            }`}
            title="Settings"
          >
            <svg className="w-5 h-5" fill="none" stroke="currentColor" strokeWidth={1.5} viewBox="0 0 24 24">
              <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
              <circle cx="12" cy="12" r="3" />
            </svg>
          </button>
        </div>

        {/* ── SIDEBAR PANEL ── */}
        {!sidebarCollapsed && (
          <>
            <div className="flex flex-col bg-[#0f1119] border-r border-[#1a1d27] overflow-hidden" style={{ width: sidebarWidth }}>
              {/* Sidebar header */}
              <div className="h-10 flex items-center justify-between px-4 border-b border-[#1a1d27] flex-shrink-0">
                <span className="text-xs font-semibold text-gray-400 uppercase tracking-wider">
                  {sidebarTab === 'files' ? 'Explorer' : sidebarTab === 'ai' ? 'AI Assistant' : sidebarTab === 'search' ? 'Search' : sidebarTab === 'git' ? 'Source Control' : 'Settings'}
                </span>
                <button onClick={() => setSidebarCollapsed(true)} className="text-gray-600 hover:text-gray-300 transition p-1">
                  <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                  </svg>
                </button>
              </div>

              {/* Sidebar content */}
              <div className="flex-1 min-h-0 overflow-hidden">
                {sidebarTab === 'files' && (
                  <FileExplorer
                    fileTree={fileTree}
                    activeFile={activeFile}
                    onFileSelect={handleFileSelect}
                    onFileDelete={handleFileDelete}
                    onFileCreate={async (path, type) => {
                      if (!project) return;
                      try {
                        const res = await projectService.createFile(project.id, path, type);
                        setFileTree(res.data.file_tree);
                        if (type === 'file') await openFile(project.id, path);
                      } catch (err) { console.error('Failed to create:', err); }
                    }}
                    onFileRename={async (from, to) => {
                      if (!project) return;
                      try {
                        const res = await projectService.renameFile(project.id, from, to);
                        setFileTree(res.data.file_tree);
                      } catch (err) { console.error('Failed to rename:', err); }
                    }}
                  />
                )}
                {sidebarTab === 'ai' && (
                  <AiChatPanel messages={messages} isLoading={chatLoading} onSend={handleChatSend} />
                )}
                {sidebarTab === 'search' && project && (
                  <SearchPanel projectId={project.id} onFileSelect={handleFileSelect} />
                )}
                {sidebarTab === 'git' && project && (
                  <GitPanel projectId={project.id} />
                )}
                {sidebarTab === 'settings' && (
                  <div className="p-4 space-y-4">
                    <div>
                      <label className="text-xs text-gray-400 block mb-1">Project Name</label>
                      <input value={project?.name || ''} readOnly className="w-full bg-[#0a0a12] border border-[#1e1e2e] rounded-lg px-3 py-2 text-sm text-white outline-none" />
                    </div>
                    <div>
                      <label className="text-xs text-gray-400 block mb-1">Framework</label>
                      <div className="px-3 py-2 bg-[#0a0a12] border border-[#1e1e2e] rounded-lg text-sm text-gray-300">{project?.framework}</div>
                    </div>
                    <div>
                      <label className="text-xs text-gray-400 block mb-1">Files</label>
                      <div className="px-3 py-2 bg-[#0a0a12] border border-[#1e1e2e] rounded-lg text-sm text-gray-300">{fileCount} files</div>
                    </div>
                  </div>
                )}
              </div>
            </div>

            {/* Sidebar resizer */}
            <div onMouseDown={() => startDrag('sidebar')} className="w-px bg-[#1a1d27] hover:bg-blue-500 cursor-col-resize flex-shrink-0 transition-colors hover:w-0.5" />
          </>
        )}

        {/* ── EDITOR + PREVIEW AREA ── */}
        <div className="flex-1 flex flex-col min-h-0 min-w-0">
          <div className="flex-1 flex min-h-0">
            {/* Code Editor Panel */}
            <div className="flex flex-col min-h-0 min-w-0" style={{ width: `${editorWidth}%` }}>
              <CodeEditor
                filePath={activeFile}
                content={fileContent}
                onChange={handleEditorChange}
                onSave={handleSave}
                openFiles={openFiles}
                onFileSelect={(path) => project && openFile(project.id, path)}
                onCloseFile={handleCloseFile}
                unsavedFile={unsavedChanges ? activeFile : null}
              />
            </div>

            {/* Editor-Preview resizer */}
            <div onMouseDown={() => startDrag('editor')} className="w-px bg-[#1a1d27] hover:bg-blue-500 cursor-col-resize flex-shrink-0 transition-colors hover:w-0.5" />

            {/* Preview Panel */}
            <div className="flex-1 min-h-0 min-w-0">
              <PreviewPanel previewUrl={previewUrl} iframeKey={iframeKey} onRefresh={refreshPreview} />
            </div>
          </div>

          {/* Bottom panel (Terminal) */}
          {showBottomPanel && project && (
            <>
              <div className="h-px bg-[#1a1d27]" />
              <div className="bg-[#0a0a12] border-t border-[#1a1d27] flex flex-col" style={{ height: bottomPanelHeight || 220 }}>
                <div className="flex items-center h-9 px-4 border-b border-[#1a1d27] gap-4 flex-shrink-0">
                  <span className="text-xs text-blue-400 font-medium border-b-2 border-blue-400 pb-2 pt-2">Terminal</span>
                  <div className="flex-1" />
                  <button onClick={() => setShowBottomPanel(false)} className="text-gray-600 hover:text-white transition">
                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>
                <div className="flex-1 min-h-0">
                  <Terminal
                    projectId={project.id}
                    onCommand={async (cmd) => {
                      const res = await projectService.terminal(project.id, cmd);
                      // Refresh file tree after commands that might change files
                      if (/^(touch|mkdir|rm|mv|cp|npm|npx|git|yarn|pnpm)/.test(cmd)) {
                        try {
                          const projRes = await projectService.get(project.id);
                          setFileTree(projRes.data.file_tree || []);
                        } catch {}
                      }
                      return res.data;
                    }}
                  />
                </div>
              </div>
            </>
          )}
        </div>
      </div>

      {/* ═══ STATUS BAR ═══ */}
      <footer className="h-6 bg-[#0d1017] border-t border-[#1a1d27] flex items-center px-3 justify-between flex-shrink-0 z-20">
        <div className="flex items-center gap-3">
          <button
            onClick={() => setShowBottomPanel(!showBottomPanel)}
            className="text-gray-600 hover:text-gray-300 flex items-center gap-1 text-xs transition"
          >
            <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Terminal
          </button>
          {chatLoading && (
            <span className="text-xs text-amber-400 flex items-center gap-1.5">
              <div className="w-1.5 h-1.5 bg-amber-400 rounded-full animate-pulse" />
              AI generating...
            </span>
          )}
        </div>
        <div className="flex items-center gap-3 text-xs text-gray-600">
          {activeFile && <span>{activeFile.split('.').pop()?.toUpperCase()}</span>}
          <span>{fileCount} files</span>
          <span>UTF-8</span>
        </div>
      </footer>
    </div>
  );
}