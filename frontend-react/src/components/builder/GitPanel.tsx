import { useState, useEffect } from 'react';
import { projectService } from '../../services/project.service';

interface Props {
  projectId: number;
}

interface GitChange {
  status: string;
  file: string;
}

export default function GitPanel({ projectId }: Props) {
  const [initialized, setInitialized] = useState(false);
  const [changes, setChanges] = useState<GitChange[]>([]);
  const [commits, setCommits] = useState<string[]>([]);
  const [branches, setBranches] = useState<string[]>([]);
  const [commitMsg, setCommitMsg] = useState('');
  const [loading, setLoading] = useState(false);
  const [statusText, setStatusText] = useState('');

  const checkGitStatus = async () => {
    try {
      const res = await projectService.git(projectId, 'status');
      if (res.data.exit_code !== 0 && res.data.output.includes('not a git repository')) {
        setInitialized(false);
        return;
      }
      setInitialized(true);

      // Parse status
      const lines = res.data.output.split('\n').filter(Boolean);
      const parsed = lines.map(line => ({
        status: line.substring(0, 2).trim(),
        file: line.substring(3).trim(),
      }));
      setChanges(parsed);

      // Load commits
      const logRes = await projectService.git(projectId, 'log');
      if (logRes.data.exit_code === 0) {
        setCommits(logRes.data.output.split('\n').filter(Boolean));
      }

      // Load branches
      const branchRes = await projectService.git(projectId, 'branch');
      if (branchRes.data.exit_code === 0) {
        setBranches(branchRes.data.output.split('\n').map(b => b.trim()).filter(Boolean));
      }
    } catch {
      setInitialized(false);
    }
  };

  useEffect(() => {
    checkGitStatus();
  }, [projectId]);

  const handleInit = async () => {
    setLoading(true);
    setStatusText('Initializing git...');
    try {
      const res = await projectService.git(projectId, 'init');
      setStatusText(res.data.output);
      await checkGitStatus();
    } catch {
      setStatusText('Failed to initialize git.');
    }
    setLoading(false);
  };

  const handleCommit = async () => {
    if (!commitMsg.trim()) return;
    setLoading(true);
    setStatusText('Committing...');
    try {
      const res = await projectService.git(projectId, 'commit', { message: commitMsg });
      setStatusText(res.data.output);
      setCommitMsg('');
      await checkGitStatus();
    } catch {
      setStatusText('Commit failed.');
    }
    setLoading(false);
  };

  const statusIcon = (status: string) => {
    switch (status) {
      case 'M': return <span className="text-amber-400 text-xs font-bold w-4">M</span>;
      case 'A': case '??': return <span className="text-emerald-400 text-xs font-bold w-4">U</span>;
      case 'D': return <span className="text-red-400 text-xs font-bold w-4">D</span>;
      case 'R': return <span className="text-blue-400 text-xs font-bold w-4">R</span>;
      default: return <span className="text-gray-400 text-xs font-bold w-4">{status || '?'}</span>;
    }
  };

  if (!initialized) {
    return (
      <div className="p-4 flex flex-col items-center justify-center h-full gap-4">
        <div className="w-14 h-14 rounded-2xl bg-[#12121a] border border-[#1e1e2e] flex items-center justify-center">
          <svg className="w-7 h-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
          </svg>
        </div>
        <div className="text-center">
          <p className="text-sm text-gray-300 font-medium">No Git Repository</p>
          <p className="text-xs text-gray-600 mt-1">Initialize version control for this project</p>
        </div>
        <button
          onClick={handleInit}
          disabled={loading}
          className="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg text-xs font-medium transition disabled:opacity-50"
        >
          {loading ? 'Initializing...' : 'Initialize Git'}
        </button>
        {statusText && <p className="text-xs text-gray-500 text-center mt-2">{statusText}</p>}
      </div>
    );
  }

  return (
    <div className="flex flex-col h-full overflow-hidden">
      {/* Branch indicator */}
      <div className="px-4 py-2 border-b border-[#1a1d27] flex items-center gap-2">
        <svg className="w-3.5 h-3.5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
        </svg>
        <span className="text-xs text-purple-400 font-medium">
          {branches.find(b => b.startsWith('*'))?.replace('* ', '') || 'main'}
        </span>
        <span className="text-xs text-gray-600 ml-auto">{changes.length} changes</span>
      </div>

      {/* Changes */}
      <div className="flex-1 overflow-auto">
        {changes.length > 0 ? (
          <div className="py-1">
            <div className="px-4 py-1.5">
              <span className="text-xs text-gray-500 uppercase tracking-wider font-medium">Changes</span>
            </div>
            {changes.map((change, i) => (
              <div key={i} className="flex items-center gap-2 px-4 py-1 hover:bg-white/5 cursor-pointer group">
                {statusIcon(change.status)}
                <span className="text-xs text-gray-300 truncate flex-1">{change.file}</span>
              </div>
            ))}
          </div>
        ) : (
          <div className="flex items-center justify-center py-8">
            <p className="text-xs text-gray-600">No changes detected</p>
          </div>
        )}

        {/* Recent commits */}
        {commits.length > 0 && (
          <div className="py-1 border-t border-[#1a1d27]">
            <div className="px-4 py-1.5">
              <span className="text-xs text-gray-500 uppercase tracking-wider font-medium">Recent Commits</span>
            </div>
            {commits.slice(0, 10).map((commit, i) => (
              <div key={i} className="flex items-center gap-2 px-4 py-1.5 hover:bg-white/5">
                <div className="w-2 h-2 rounded-full bg-blue-500 flex-shrink-0" />
                <span className="text-xs text-gray-400 truncate">{commit}</span>
              </div>
            ))}
          </div>
        )}
      </div>

      {/* Commit section */}
      {changes.length > 0 && (
        <div className="border-t border-[#1a1d27] p-3 space-y-2">
          <input
            value={commitMsg}
            onChange={(e) => setCommitMsg(e.target.value)}
            placeholder="Commit message..."
            className="w-full bg-[#0a0a12] border border-[#1e1e2e] rounded-lg px-3 py-2 text-xs text-white outline-none focus:border-blue-500/50 placeholder-gray-600"
            onKeyDown={(e) => e.key === 'Enter' && handleCommit()}
          />
          <button
            onClick={handleCommit}
            disabled={!commitMsg.trim() || loading}
            className="w-full py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg text-xs font-medium transition disabled:opacity-40"
          >
            {loading ? 'Committing...' : 'Commit All Changes'}
          </button>
        </div>
      )}

      {statusText && (
        <div className="px-3 py-2 border-t border-[#1a1d27]">
          <p className="text-xs text-gray-500 truncate">{statusText}</p>
        </div>
      )}
    </div>
  );
}
