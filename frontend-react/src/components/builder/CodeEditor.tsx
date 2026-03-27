import { useRef, useCallback } from 'react';
import Editor, { type OnMount } from '@monaco-editor/react';

interface Props {
  filePath: string | null;
  content: string;
  onChange: (value: string) => void;
  onSave: () => void;
  openFiles: string[];
  onFileSelect: (path: string) => void;
  onCloseFile: (path: string) => void;
  unsavedFile: string | null;
}

function getLanguage(path: string): string {
  const ext = path.split('.').pop()?.toLowerCase();
  const map: Record<string, string> = {
    html: 'html', htm: 'html', css: 'css', scss: 'scss', less: 'less',
    js: 'javascript', mjs: 'javascript', jsx: 'javascript',
    ts: 'typescript', tsx: 'typescript',
    json: 'json', md: 'markdown', svg: 'xml', xml: 'xml',
    php: 'php', py: 'python', rb: 'ruby', go: 'go', rs: 'rust',
    yaml: 'yaml', yml: 'yaml', toml: 'toml', sh: 'shell',
  };
  return map[ext || ''] || 'plaintext';
}

function getFileIcon(name: string) {
  const ext = name.split('.').pop()?.toLowerCase();
  const icons: Record<string, { color: string; label: string }> = {
    html: { color: '#e34c26', label: 'HTML' },
    css: { color: '#264de4', label: 'CSS' },
    scss: { color: '#cd6799', label: 'SCSS' },
    js: { color: '#f7df1e', label: 'JS' },
    jsx: { color: '#61dafb', label: 'JSX' },
    ts: { color: '#3178c6', label: 'TS' },
    tsx: { color: '#3178c6', label: 'TSX' },
    json: { color: '#5bb882', label: '{}' },
    md: { color: '#ffffff', label: 'MD' },
    svg: { color: '#ffb13b', label: 'SVG' },
    png: { color: '#a4c639', label: 'IMG' },
    jpg: { color: '#a4c639', label: 'IMG' },
    php: { color: '#777bb3', label: 'PHP' },
    py: { color: '#3776ab', label: 'PY' },
  };
  return icons[ext || ''] || { color: '#808080', label: '··' };
}

export default function CodeEditor({ filePath, content, onChange, onSave, openFiles, onFileSelect, onCloseFile, unsavedFile }: Props) {
  const editorRef = useRef<any>(null);

  const handleMount: OnMount = useCallback((editor, monaco) => {
    editorRef.current = editor;
    editor.addAction({
      id: 'save-file',
      label: 'Save File',
      keybindings: [monaco.KeyMod.CtrlCmd | monaco.KeyCode.KeyS],
      run: () => onSave(),
    });
    editor.focus();
  }, [onSave]);

  // No file open state
  if (!filePath) {
    return (
      <div className="flex-1 flex flex-col h-full bg-[#0d1017]">
        {/* Empty tab bar */}
        <div className="h-9 bg-[#0d1017] border-b border-[#1a1d27] flex-shrink-0" />
        <div className="flex-1 flex items-center justify-center">
          <div className="text-center max-w-xs">
            <div className="w-16 h-16 mx-auto mb-4 rounded-2xl bg-[#12121a] border border-[#1e1e2e] flex items-center justify-center">
              <svg className="w-8 h-8 text-gray-700" fill="none" stroke="currentColor" strokeWidth={1} viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
              </svg>
            </div>
            <p className="text-sm text-gray-500 font-medium">No file open</p>
            <p className="text-xs text-gray-700 mt-1">Select a file from the explorer or ask AI to generate code</p>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="flex-1 flex flex-col h-full bg-[#0d1017]">
      {/* File tabs */}
      <div className="h-9 bg-[#0d1017] border-b border-[#1a1d27] flex items-end overflow-x-auto flex-shrink-0 custom-scrollbar">
        {openFiles.map((file) => {
          const fileName = file.split('/').pop() || file;
          const icon = getFileIcon(fileName);
          const isActive = file === filePath;
          const isUnsaved = file === unsavedFile;

          return (
            <div
              key={file}
              onClick={() => onFileSelect(file)}
              className={`group flex items-center gap-1.5 px-3 h-full cursor-pointer border-r border-[#1a1d27] min-w-0 max-w-[180px] transition-colors ${
                isActive
                  ? 'bg-[#12121a] text-gray-200 border-t-2 border-t-blue-500 pt-0.5'
                  : 'text-gray-500 hover:text-gray-300 hover:bg-[#12121a]/50 border-t-2 border-t-transparent pt-0.5'
              }`}
            >
              <span className="w-2 h-2 rounded-sm flex-shrink-0" style={{ backgroundColor: icon.color }} />
              <span className="truncate text-xs">{fileName}</span>
              {isUnsaved && <div className="w-1.5 h-1.5 bg-amber-500 rounded-full flex-shrink-0" />}
              <button
                onClick={(e) => { e.stopPropagation(); onCloseFile(file); }}
                className="ml-auto p-0.5 rounded opacity-0 group-hover:opacity-100 hover:bg-white/10 flex-shrink-0 transition"
              >
                <svg className="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          );
        })}
      </div>

      {/* Breadcrumb */}
      <div className="h-6 flex items-center px-4 bg-[#0d1017] border-b border-[#1a1d27]/50 flex-shrink-0">
        <span className="text-xs text-gray-600">{filePath.replace(/\//g, ' > ')}</span>
      </div>

      {/* Monaco Editor */}
      <div className="flex-1 min-h-0">
        <Editor
          height="100%"
          language={getLanguage(filePath)}
          value={content}
          theme="vs-dark"
          onChange={(val) => onChange(val || '')}
          onMount={handleMount}
          options={{
            fontSize: 13,
            lineHeight: 20,
            fontFamily: "'JetBrains Mono', 'Cascadia Code', 'Fira Code', monospace",
            fontLigatures: true,
            minimap: { enabled: true, scale: 1, showSlider: 'mouseover' },
            scrollBeyondLastLine: false,
            wordWrap: 'on',
            padding: { top: 12, bottom: 12 },
            smoothScrolling: true,
            cursorBlinking: 'smooth',
            cursorSmoothCaretAnimation: 'on',
            renderWhitespace: 'none',
            bracketPairColorization: { enabled: true },
            guides: { bracketPairs: true, indentation: true },
            tabSize: 2,
            formatOnPaste: true,
            renderLineHighlight: 'line',
            scrollbar: { verticalScrollbarSize: 8, horizontalScrollbarSize: 8 },
            overviewRulerBorder: false,
            hideCursorInOverviewRuler: true,
          }}
        />
      </div>
    </div>
  );
}