import { useState } from 'react';

type Tab = 'tables' | 'logs' | 'security' | 'advanced';

const logTypes = [
  { name: 'Server Function Logs', desc: 'Server function execution logs with request/response data' },
  { name: 'Authentication Logs', desc: 'User authentication events and security logs' },
  { name: 'PostgreSQL Logs', desc: 'Database queries, errors, and performance metrics' },
  { name: 'Realtime Logs', desc: 'WebSocket connections and realtime subscriptions' },
  { name: 'Storage Logs', desc: 'File uploads, downloads, and storage operations' },
  { name: 'Cron Job Logs', desc: 'Scheduled job executions and cron task logs' },
  { name: 'Server Logs', desc: 'HTTP requests and responses from Server Functions' },
];

export default function DatabasePanel({ onClose }: { onClose: () => void }) {
  const [tab, setTab] = useState<Tab>('tables');
  const [logType, setLogType] = useState('Server Function Logs');
  const [logDropdown, setLogDropdown] = useState(false);

  return (
    <div className="fixed inset-0 z-50 flex">
      {/* Left: Settings sidebar */}
      <div className="w-64 bg-[#12121a] border-r border-[#1e1e2e] overflow-y-auto py-4">
        <div className="px-4 mb-4">
          <h3 className="text-xs font-semibold text-gray-500 uppercase tracking-wider">Project Settings</h3>
        </div>
        {['General', 'Domains & Hosting', 'Analytics', 'Database', 'Authentication', 'Server Functions', 'Secrets', 'User Management', 'File Storage', 'Knowledge', 'Backups'].map(item => (
          <button key={item} className={`w-full text-left px-4 py-2.5 text-sm transition ${item === 'Database' ? 'bg-blue-600/10 text-blue-400 font-medium' : 'text-gray-400 hover:text-white hover:bg-white/5'}`}>
            {item}
          </button>
        ))}
        <div className="px-4 mt-6 mb-4">
          <h3 className="text-xs font-semibold text-gray-500 uppercase tracking-wider">Personal Settings</h3>
        </div>
        {['General', 'Subscription & Tokens', 'Applications', 'Cloud', 'Knowledge', 'Connectors (MCP)', 'Add-on features'].map(item => (
          <button key={item} className="w-full text-left px-4 py-2.5 text-sm text-gray-400 hover:text-white hover:bg-white/5 transition">
            {item}
          </button>
        ))}
      </div>

      {/* Right: Content */}
      <div className="flex-1 bg-[#0d1017] overflow-y-auto">
        {/* Header */}
        <div className="flex items-center justify-between px-6 py-4 border-b border-[#1e1e2e]">
          <div className="flex gap-1">
            {(['tables', 'logs', 'security', 'advanced'] as Tab[]).map(t => (
              <button key={t} onClick={() => setTab(t)} className={`px-4 py-2 rounded-lg text-sm font-medium transition ${tab === t ? 'bg-blue-600/10 text-blue-400' : 'text-gray-400 hover:text-white'}`}>
                {t === 'security' ? 'Security Audit' : t.charAt(0).toUpperCase() + t.slice(1)}
              </button>
            ))}
          </div>
          <div className="flex items-center gap-3">
            <span className="text-xs text-gray-500 flex items-center gap-1.5">
              <svg className="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M3 12v3c0 1.657 3.134 3 7 3s7-1.343 7-3v-3c0 1.657-3.134 3-7 3s-7-1.343-7-3z" /><path d="M3 7v3c0 1.657 3.134 3 7 3s7-1.343 7-3V7c0 1.657-3.134 3-7 3S3 8.657 3 7z" /><path d="M17 5c0 1.657-3.134 3-7 3S3 6.657 3 5s3.134-3 7-3 7 1.343 7 3z" /></svg>
              Database
            </span>
            <button onClick={onClose} className="text-gray-500 hover:text-white p-1"><svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" /></svg></button>
          </div>
        </div>

        <div className="p-6">
          {tab === 'tables' && (
            <div>
              <h2 className="text-xl font-bold text-white mb-2">Tables</h2>
              <p className="text-sm text-gray-500 mb-6">View and manage database tables and records. Ask AI to create or modify tables.</p>
              <div className="bg-[#12121a] border border-[#1e1e2e] rounded-xl p-4 inline-flex items-center gap-3">
                <svg className="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0112 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 0v1.5c0 .621-.504 1.125-1.125 1.125" /></svg>
                <span className="text-sm text-gray-300">contact_submissions</span>
                <span className="text-xs text-gray-600">0 rows</span>
              </div>
            </div>
          )}

          {tab === 'logs' && (
            <div>
              <h2 className="text-xl font-bold text-white mb-2">Logs</h2>
              <p className="text-sm text-gray-500 mb-6">Monitor database queries, authentication events, and backend services.</p>
              <div className="relative mb-6">
                <button onClick={() => setLogDropdown(!logDropdown)} className="w-80 flex items-center justify-between bg-[#12121a] border border-[#1e1e2e] rounded-xl px-4 py-3 text-sm text-white">
                  {logType}
                  <svg className="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" /></svg>
                </button>
                {logDropdown && (
                  <>
                    <div className="fixed inset-0 z-10" onClick={() => setLogDropdown(false)} />
                    <div className="absolute top-full left-0 mt-1 w-80 bg-[#1a1d27] border border-[#2a2d37] rounded-xl shadow-xl z-20 py-1 max-h-64 overflow-y-auto">
                      <div className="px-3 py-2"><input placeholder="Search log types..." className="w-full bg-[#12121a] border border-[#1e1e2e] rounded-lg px-3 py-2 text-xs text-white outline-none" /></div>
                      {logTypes.map(lt => (
                        <button key={lt.name} onClick={() => { setLogType(lt.name); setLogDropdown(false); }} className="w-full text-left px-4 py-2.5 hover:bg-white/5 transition">
                          <p className="text-xs text-white font-medium">{lt.name}</p>
                          <p className="text-[11px] text-gray-600">{lt.desc}</p>
                        </button>
                      ))}
                    </div>
                  </>
                )}
              </div>
              <div className="bg-[#12121a] border border-[#1e1e2e] rounded-xl p-12 text-center">
                <svg className="w-12 h-12 text-gray-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1} d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625" /></svg>
                <p className="text-sm text-gray-400 font-medium">No {logType} available yet</p>
                <p className="text-xs text-gray-600 mt-1">Check back later for activity logs</p>
              </div>
            </div>
          )}

          {tab === 'security' && (
            <div>
              <div className="flex items-center justify-between mb-6">
                <div>
                  <h2 className="text-xl font-bold text-white mb-2">Security Audit</h2>
                  <p className="text-sm text-gray-500">Identifies vulnerabilities like missing RLS policies and insecure permissions.</p>
                </div>
                <button className="px-4 py-2 bg-[#12121a] border border-[#1e1e2e] rounded-xl text-sm text-gray-300 hover:text-white transition flex items-center gap-2">
                  <div className="w-4 h-4 bg-gradient-to-br from-blue-500 to-purple-600 rounded text-[8px] text-white font-bold flex items-center justify-center">W</div>
                  Ask AI to fix
                </button>
              </div>
              <div className="bg-[#12121a] border border-[#1e1e2e] rounded-xl p-5">
                <div className="flex items-center gap-2 mb-4">
                  <span className="text-sm font-medium text-white">RLS Policy Always True</span>
                  <span className="px-2 py-0.5 bg-amber-500/10 text-amber-400 rounded text-xs">Warning</span>
                </div>
                <div className="space-y-3 text-sm">
                  <div><span className="text-gray-500">Table</span><div className="mt-1 bg-[#0a0a12] rounded-lg px-3 py-2 font-mono text-xs text-gray-300">public.contact_submissions</div></div>
                  <div><span className="text-gray-500">has an RLS policy</span><div className="mt-1 bg-[#0a0a12] rounded-lg px-3 py-2 font-mono text-xs text-gray-300">Anyone can submit contact form</div></div>
                  <div><span className="text-gray-500">for</span><div className="mt-1 bg-[#0a0a12] rounded-lg px-3 py-2 font-mono text-xs text-gray-300">INSERT</div></div>
                  <p className="text-xs text-gray-500">that allows unrestricted access (WITH CHECK clause is always true). This effectively bypasses row-level security for anon, authenticated.</p>
                </div>
              </div>
            </div>
          )}

          {tab === 'advanced' && (
            <div>
              <h2 className="text-xl font-bold text-white mb-2">Advanced Settings</h2>
              <p className="text-sm text-gray-500 mb-8">Manage database connections and ownership.</p>
              <div className="space-y-6">
                <div className="flex items-center justify-between">
                  <div><p className="text-sm text-white font-medium">Connect to an existing database</p><p className="text-xs text-gray-500 mt-1">Connect to a different database project to manage your data.</p></div>
                  <button className="px-5 py-2 bg-blue-600 hover:bg-blue-500 rounded-xl text-sm text-white font-medium">Connect</button>
                </div>
                <div className="border-t border-[#1e1e2e]" />
                <div className="flex items-center justify-between">
                  <div><p className="text-sm text-white font-medium">Claim Database</p><p className="text-xs text-gray-500 mt-1">Move this database into your own account.</p></div>
                  <button className="text-sm text-blue-400 hover:text-blue-300">Claim</button>
                </div>
              </div>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
