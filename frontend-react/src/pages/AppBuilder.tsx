import { useState, useEffect, useRef } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { appService, type AppProject, type AppMessage } from '../services/app.service';
import CodeEditor from '../components/builder/CodeEditor';
import AiChatPanel from '../components/builder/AiChatPanel';

type SimDevice = 'iphone15' | 'iphoneSE' | 'pixel8' | 'samsung';
type SideTab = 'ai' | 'files';

const deviceSpecs: Record<SimDevice, { name: string; width: number; height: number; os: 'ios' | 'android'; notch: boolean; radius: number }> = {
  iphone15:  { name: 'iPhone 15 Pro', width: 393, height: 852, os: 'ios', notch: true, radius: 55 },
  iphoneSE:  { name: 'iPhone SE', width: 375, height: 667, os: 'ios', notch: false, radius: 30 },
  pixel8:    { name: 'Pixel 8', width: 412, height: 915, os: 'android', notch: true, radius: 40 },
  samsung:   { name: 'Galaxy S24', width: 360, height: 780, os: 'android', notch: true, radius: 35 },
};

export default function AppBuilder() {
  const { id } = useParams<{ id: string }>();
  const navigate = useNavigate();

  const [app, setApp] = useState<AppProject | null>(null);
  const [loading, setLoading] = useState(true);
  const [messages, setMessages] = useState<AppMessage[]>([]);
  const [chatLoading, setChatLoading] = useState(false);
  const [fileTree, setFileTree] = useState<any[]>([]);
  const [activeFile, setActiveFile] = useState<string | null>(null);
  const [fileContent, setFileContent] = useState('');
  const [openFiles, setOpenFiles] = useState<string[]>([]);
  const [sideTab, setSideTab] = useState<SideTab>('ai');
  const [showCreate, setShowCreate] = useState(!id);
  const [newName, setNewName] = useState('');
  const [newPrompt, setNewPrompt] = useState('');
  const [creating, setCreating] = useState(false);
  const [leftDevice, setLeftDevice] = useState<SimDevice>('iphone15');
  const [rightDevice, setRightDevice] = useState<SimDevice>('pixel8');
  const [simScale, setSimScale] = useState(0.55);

  const pollingRef = useRef<ReturnType<typeof setInterval> | null>(null);
  const appRef = useRef<AppProject | null>(null);
  useEffect(() => { appRef.current = app; }, [app]);

  useEffect(() => {
    if (!id) { setLoading(false); setShowCreate(true); return; }
    loadApp(parseInt(id));
  }, [id]);

  const loadApp = async (appId: number) => {
    try {
      setLoading(true);
      const [appRes, msgRes] = await Promise.all([appService.get(appId), appService.getMessages(appId)]);
      setApp(appRes.data);
      setFileTree(appRes.data.file_tree || []);
      setMessages(msgRes.data);
    } catch (err) { console.error(err); } finally { setLoading(false); }
  };

  const openFile = async (appId: number, path: string) => {
    try {
      const res = await appService.readFile(appId, path);
      setActiveFile(path);
      setFileContent(res.data.content);
      setOpenFiles(prev => prev.includes(path) ? prev : [...prev, path]);
    } catch (err) { console.error(err); }
  };

  const startPolling = (appId: number) => {
    if (pollingRef.current) clearInterval(pollingRef.current);
    pollingRef.current = setInterval(async () => {
      try {
        const res = await appService.getStream(appId);
        if (res.data.file_tree?.length) setFileTree(res.data.file_tree);
        if (res.data.status === 'done') {
          clearInterval(pollingRef.current!);
          pollingRef.current = null;
          setChatLoading(false);
          setMessages(prev => {
            const filtered = prev.filter(m => m.id !== -1);
            return [...filtered, { id: Date.now(), role: 'assistant', content: res.data.text || 'App generated!', files_changed: res.data.files_changed, created_at: new Date().toISOString() }];
          });
          if (res.data.file_tree?.length) setFileTree(res.data.file_tree);
          try { const m = await appService.getMessages(appId); setMessages(m.data); } catch {}
        }
      } catch {}
    }, 3000);
  };

  const handleChatSend = async (message: string, appOverride?: AppProject) => {
    const a = appOverride || appRef.current;
    if (!a) return;
    setChatLoading(true);
    setMessages(prev => [...prev, { id: Date.now(), role: 'user', content: message, files_changed: null, created_at: new Date().toISOString() }]);
    setMessages(prev => [...prev, { id: -1, role: 'assistant', content: 'Building your app...', files_changed: null, created_at: new Date().toISOString() }]);
    try {
      await appService.chat(a.id, message);
      startPolling(a.id);
    } catch {
      setChatLoading(false);
      setMessages(prev => prev.filter(m => m.id !== -1).concat({ id: Date.now(), role: 'assistant', content: 'Failed to start. Try again.', files_changed: null, created_at: new Date().toISOString() }));
    }
  };

  const handleCreate = async () => {
    if (!newName.trim()) return;
    setCreating(true);
    try {
      const res = await appService.create({ name: newName });
      const a = res.data.app;
      setShowCreate(false);
      setApp(a);
      setFileTree(a.file_tree || []);
      navigate(`/app-builder/${a.id}`, { replace: true });
      setLoading(false);
      if (newPrompt?.trim()) handleChatSend(newPrompt.trim(), a);
    } catch { setCreating(false); }
  };

  useEffect(() => () => { if (pollingRef.current) clearInterval(pollingRef.current); }, []);

  // Device frame component
  const DeviceFrame = ({ device, side }: { device: SimDevice; side: 'left' | 'right' }) => {
    const spec = deviceSpecs[device];
    const isIos = spec.os === 'ios';

    return (
      <div className="flex flex-col items-center gap-2">
        {/* Device selector */}
        <select
          value={device}
          onChange={(e) => side === 'left' ? setLeftDevice(e.target.value as SimDevice) : setRightDevice(e.target.value as SimDevice)}
          className="bg-[#12121a] border border-[#1e1e2e] rounded-lg px-2 py-1 text-xs text-gray-300 outline-none"
        >
          {Object.entries(deviceSpecs).map(([k, v]) => (
            <option key={k} value={k}>{v.name} ({v.os === 'ios' ? 'iOS' : 'Android'})</option>
          ))}
        </select>

        {/* Phone frame */}
        <div
          className="relative bg-[#1a1a1a] shadow-2xl shadow-black/50 overflow-hidden"
          style={{
            width: spec.width * simScale,
            height: spec.height * simScale,
            borderRadius: spec.radius * simScale,
            border: `${3 * simScale}px solid #2a2a2a`,
          }}
        >
          {/* Status bar */}
          <div
            className="absolute top-0 left-0 right-0 flex items-center justify-between px-6 z-10"
            style={{ height: 44 * simScale, fontSize: 11 * simScale }}
          >
            {isIos && spec.notch && (
              <div className="absolute top-0 left-1/2 -translate-x-1/2 bg-black rounded-b-2xl" style={{ width: 120 * simScale, height: 34 * simScale }} />
            )}
            <span className="text-white font-semibold" style={{ fontSize: 12 * simScale }}>9:41</span>
            <div className="flex items-center gap-1">
              <div className="bg-white rounded-sm" style={{ width: 16 * simScale, height: 8 * simScale, opacity: 0.7 }} />
            </div>
          </div>

          {/* App content area */}
          <div className="absolute inset-0 bg-gradient-to-b from-[#1a1a2e] to-[#16213e] flex items-center justify-center">
            {fileTree.length > 0 ? (
              <div className="text-center" style={{ padding: 20 * simScale }}>
                <div className="bg-blue-500/20 rounded-full mx-auto mb-3 flex items-center justify-center" style={{ width: 50 * simScale, height: 50 * simScale }}>
                  <svg style={{ width: 24 * simScale, height: 24 * simScale }} className="text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                  </svg>
                </div>
                <p className="text-white font-semibold" style={{ fontSize: 14 * simScale }}>{app?.name}</p>
                <p className="text-gray-400" style={{ fontSize: 10 * simScale, marginTop: 4 * simScale }}>
                  {fileTree.length} files generated
                </p>
                <p className="text-blue-400" style={{ fontSize: 9 * simScale, marginTop: 8 * simScale }}>
                  {isIos ? 'iOS' : 'Android'} Preview
                </p>
              </div>
            ) : (
              <div className="text-center" style={{ padding: 20 * simScale }}>
                <p className="text-gray-500" style={{ fontSize: 11 * simScale }}>
                  {chatLoading ? 'Generating...' : 'No app yet'}
                </p>
              </div>
            )}
          </div>

          {/* Home indicator (iOS) */}
          {isIos && (
            <div className="absolute bottom-1 left-1/2 -translate-x-1/2 bg-white/30 rounded-full" style={{ width: 100 * simScale, height: 4 * simScale }} />
          )}

          {/* Nav bar (Android) */}
          {!isIos && (
            <div className="absolute bottom-0 left-0 right-0 flex items-center justify-center gap-6" style={{ height: 36 * simScale }}>
              <div className="border border-white/20 rounded-sm" style={{ width: 14 * simScale, height: 14 * simScale }} />
              <div className="bg-white/20 rounded-full" style={{ width: 14 * simScale, height: 14 * simScale }} />
              <div className="border-l-2 border-white/20" style={{ width: 0, height: 14 * simScale }} />
            </div>
          )}
        </div>
      </div>
    );
  };

  // CREATE MODAL
  if (showCreate) {
    return (
      <div className="fixed inset-0 bg-[#0a0a0f] flex items-center justify-center z-50">
        <div className="absolute inset-0 overflow-hidden">
          <div className="absolute top-1/4 left-1/3 w-96 h-96 bg-purple-600/5 rounded-full blur-3xl animate-pulse" />
          <div className="absolute bottom-1/3 right-1/4 w-96 h-96 bg-blue-600/5 rounded-full blur-3xl animate-pulse" style={{ animationDelay: '1s' }} />
        </div>
        <div className="relative bg-[#12121a] border border-[#1e1e2e] rounded-2xl p-8 w-full max-w-xl shadow-2xl">
          <div className="flex items-center gap-3 mb-6">
            <div className="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
              <svg className="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
              </svg>
            </div>
            <div>
              <h2 className="text-lg font-semibold text-white">Create New App</h2>
              <p className="text-sm text-gray-500">Build a mobile app with AI</p>
            </div>
          </div>
          <div className="space-y-5">
            <div>
              <label className="block text-sm font-medium text-gray-300 mb-1.5">App Name</label>
              <input value={newName} onChange={e => setNewName(e.target.value)} placeholder="My Amazing App" autoFocus
                className="w-full bg-[#0a0a12] border border-[#1e1e2e] rounded-xl px-4 py-3 text-white text-sm outline-none focus:border-purple-500/50" />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-300 mb-1.5">Describe your app <span className="text-gray-600 font-normal">(optional)</span></label>
              <textarea value={newPrompt} onChange={e => setNewPrompt(e.target.value)} rows={3}
                placeholder="e.g., A fitness tracking app with workout timer, exercise library, and progress charts..."
                className="w-full bg-[#0a0a12] border border-[#1e1e2e] rounded-xl px-4 py-3 text-white text-sm outline-none focus:border-purple-500/50 resize-none" />
            </div>
          </div>
          <div className="flex justify-end gap-3 mt-7">
            <button onClick={() => navigate('/dashboard')} className="px-5 py-2.5 text-sm text-gray-400 hover:text-white rounded-xl hover:bg-white/5 transition">Cancel</button>
            <button onClick={handleCreate} disabled={!newName.trim() || creating}
              className="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-pink-500 text-white rounded-xl text-sm font-medium disabled:opacity-40 transition-all shadow-lg shadow-purple-600/25 flex items-center gap-2">
              {creating ? 'Creating...' : 'Create App'}
            </button>
          </div>
        </div>
      </div>
    );
  }

  if (loading) {
    return <div className="fixed inset-0 bg-[#0a0a0f] flex items-center justify-center">
      <div className="w-12 h-12 border-2 border-purple-500/30 border-t-purple-500 rounded-full animate-spin" />
    </div>;
  }

  // MAIN LAYOUT
  return (
    <div className="fixed inset-0 bg-[#0d1017] flex flex-col text-sm select-none">
      {/* Top bar */}
      <header className="h-12 bg-[#0d1017] border-b border-[#1a1d27] flex items-center px-4 justify-between flex-shrink-0">
        <div className="flex items-center gap-3">
          <button onClick={() => navigate('/dashboard')} className="text-gray-500 hover:text-white transition p-1">
            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
          </button>
          <div className="w-px h-5 bg-[#1a1d27]" />
          <div className="w-6 h-6 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center">
            <svg className="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
          </div>
          <span className="text-sm font-medium text-gray-200">{app?.name}</span>
        </div>
        <div className="flex items-center gap-2">
          <span className="px-2.5 py-1 rounded-md text-xs font-medium bg-purple-500/10 text-purple-400 border border-purple-500/20">React Native</span>
          <span className={`px-2.5 py-1 rounded-md text-xs font-medium flex items-center gap-1.5 ${
            chatLoading ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20'
          }`}>
            <div className={`w-1.5 h-1.5 rounded-full ${chatLoading ? 'bg-amber-400 animate-pulse' : 'bg-emerald-400'}`} />
            {chatLoading ? 'generating' : app?.status}
          </span>
        </div>
      </header>

      {/* Main content */}
      <div className="flex-1 flex min-h-0">
        {/* Sidebar — AI Chat or Files */}
        <div className="w-[340px] flex flex-col bg-[#0f1119] border-r border-[#1a1d27] flex-shrink-0">
          <div className="flex bg-[#0d1017] border-b border-[#1a1d27]">
            {(['ai', 'files'] as SideTab[]).map(t => (
              <button key={t} onClick={() => setSideTab(t)}
                className={`flex-1 py-2 text-xs font-medium border-b-2 transition ${sideTab === t ? 'text-purple-400 border-purple-500' : 'text-gray-600 border-transparent hover:text-gray-400'}`}>
                {t === 'ai' ? 'AI Chat' : 'Files & Editor'}
              </button>
            ))}
          </div>
          <div className="flex-1 min-h-0 overflow-hidden">
            {sideTab === 'ai' ? (
              <AiChatPanel messages={messages as any} isLoading={chatLoading} onSend={(m) => handleChatSend(m)} />
            ) : (
              <div className="flex flex-col h-full">
                <div className="flex-1 min-h-0">
                  <CodeEditor filePath={activeFile} content={fileContent} onChange={setFileContent}
                    onSave={() => {}} openFiles={openFiles} onFileSelect={(p) => app && openFile(app.id, p)}
                    onCloseFile={(p) => setOpenFiles(prev => prev.filter(f => f !== p))} unsavedFile={null} />
                </div>
              </div>
            )}
          </div>
        </div>

        {/* Simulator area */}
        <div className="flex-1 flex flex-col bg-[#080810] min-h-0">
          {/* Simulator toolbar */}
          <div className="h-10 flex items-center justify-between px-4 border-b border-[#1a1d27] flex-shrink-0">
            <span className="text-xs text-gray-500 font-medium">Device Simulators</span>
            <div className="flex items-center gap-2">
              <span className="text-xs text-gray-600">Scale:</span>
              <input type="range" min="0.3" max="0.8" step="0.05" value={simScale}
                onChange={e => setSimScale(parseFloat(e.target.value))}
                className="w-20 accent-purple-500" />
              <span className="text-xs text-gray-400 w-8">{Math.round(simScale * 100)}%</span>
            </div>
          </div>

          {/* Simulators */}
          <div className="flex-1 flex items-center justify-center gap-8 p-6 overflow-auto">
            <DeviceFrame device={leftDevice} side="left" />
            <DeviceFrame device={rightDevice} side="right" />
          </div>
        </div>
      </div>

      {/* Status bar */}
      <footer className="h-6 bg-[#0d1017] border-t border-[#1a1d27] flex items-center px-3 justify-between flex-shrink-0">
        <div className="flex items-center gap-3">
          {chatLoading && <span className="text-xs text-amber-400 flex items-center gap-1.5"><div className="w-1.5 h-1.5 bg-amber-400 rounded-full animate-pulse" />Building app...</span>}
        </div>
        <div className="flex items-center gap-3 text-xs text-gray-600">
          <span>{fileTree.length} files</span>
          <span>React Native + Expo</span>
        </div>
      </footer>
    </div>
  );
}
