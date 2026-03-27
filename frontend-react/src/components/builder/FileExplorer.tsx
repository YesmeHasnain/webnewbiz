import { useState } from 'react';
import type { FileTreeNode } from '../../models/types';

interface Props {
  fileTree: FileTreeNode[];
  activeFile: string | null;
  onFileSelect: (path: string) => void;
  onFileDelete?: (path: string) => void;
}

function getFileColor(name: string): string {
  const ext = name.split('.').pop()?.toLowerCase();
  const colors: Record<string, string> = {
    html: '#e34c26', htm: '#e34c26',
    css: '#264de4', scss: '#cd6799', less: '#1d365d',
    js: '#f7df1e', mjs: '#f7df1e', jsx: '#61dafb',
    ts: '#3178c6', tsx: '#3178c6',
    json: '#5bb882', md: '#ffffff',
    svg: '#ffb13b', png: '#a4c639', jpg: '#a4c639', gif: '#a4c639', webp: '#a4c639',
    php: '#777bb3', py: '#3776ab', rb: '#cc342d',
    yaml: '#cb171e', yml: '#cb171e', toml: '#9c4221',
    sh: '#4eaa25', bash: '#4eaa25',
    env: '#ecd53f', gitignore: '#f05032',
  };
  return colors[ext || ''] || '#808080';
}

function FolderItem({ node, activeFile, onFileSelect, onFileDelete, depth = 0 }: {
  node: FileTreeNode;
  activeFile: string | null;
  onFileSelect: (path: string) => void;
  onFileDelete?: (path: string) => void;
  depth?: number;
}) {
  const [isOpen, setIsOpen] = useState(depth < 2);

  if (node.type === 'directory') {
    return (
      <div>
        <button
          onClick={() => setIsOpen(!isOpen)}
          className="flex items-center gap-1.5 w-full py-[5px] text-[13px] text-gray-400 hover:text-gray-200 hover:bg-white/[0.03] transition-colors group"
          style={{ paddingLeft: `${depth * 16 + 12}px` }}
        >
          <svg className={`w-3 h-3 text-gray-600 transition-transform flex-shrink-0 ${isOpen ? 'rotate-90' : ''}`} fill="currentColor" viewBox="0 0 20 20">
            <path d="M6 6L14 10L6 14V6Z" />
          </svg>
          <svg className={`w-4 h-4 flex-shrink-0 ${isOpen ? 'text-blue-400' : 'text-gray-500'}`} fill="currentColor" viewBox="0 0 20 20">
            {isOpen
              ? <path fillRule="evenodd" d="M2 6a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1H8a3 3 0 00-3 3v1.5a1.5 1.5 0 01-3 0V6z" clipRule="evenodd" />
              : <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
            }
          </svg>
          <span className="truncate">{node.name}</span>
        </button>
        {isOpen && node.children?.map((child) => (
          <FolderItem key={child.path} node={child} activeFile={activeFile} onFileSelect={onFileSelect} onFileDelete={onFileDelete} depth={depth + 1} />
        ))}
      </div>
    );
  }

  const isActive = activeFile === node.path;
  const dotColor = getFileColor(node.name);

  return (
    <button
      onClick={() => onFileSelect(node.path)}
      className={`flex items-center gap-1.5 w-full py-[5px] text-[13px] transition-colors group ${
        isActive
          ? 'bg-blue-600/10 text-blue-300'
          : 'text-gray-400 hover:text-gray-200 hover:bg-white/[0.03]'
      }`}
      style={{ paddingLeft: `${depth * 16 + 28}px` }}
    >
      <span className="w-2 h-2 rounded-sm flex-shrink-0" style={{ backgroundColor: dotColor }} />
      <span className="truncate">{node.name}</span>
      {onFileDelete && (
        <svg
          onClick={(e) => { e.stopPropagation(); onFileDelete(node.path); }}
          className="w-3.5 h-3.5 text-gray-700 hover:text-red-400 ml-auto mr-2 opacity-0 group-hover:opacity-100 flex-shrink-0 cursor-pointer transition"
          fill="none" stroke="currentColor" viewBox="0 0 24 24"
        >
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
      )}
    </button>
  );
}

export default function FileExplorer({ fileTree, activeFile, onFileSelect, onFileDelete }: Props) {
  return (
    <div className="flex flex-col h-full bg-[#0f1119]">
      <div className="flex-1 overflow-y-auto py-1 custom-scrollbar">
        {fileTree.length === 0 ? (
          <div className="px-4 py-8 text-center">
            <div className="w-10 h-10 mx-auto mb-3 rounded-xl bg-[#12121a] border border-[#1e1e2e] flex items-center justify-center">
              <svg className="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" strokeWidth={1} viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
              </svg>
            </div>
            <p className="text-xs text-gray-600">No files yet</p>
            <p className="text-[10px] text-gray-700 mt-1">Ask AI to generate your project</p>
          </div>
        ) : (
          fileTree.map((node) => (
            <FolderItem key={node.path} node={node} activeFile={activeFile} onFileSelect={onFileSelect} onFileDelete={onFileDelete} />
          ))
        )}
      </div>
    </div>
  );
}