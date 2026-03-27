import { useState, useEffect } from 'react';
import { crmService } from '../../services/crm.service';

interface ConvItem { id: number; channel: string; status: string; last_message_at: string | null; contact: { first_name: string; last_name: string; email: string } | null; }
interface Msg { id: number; sender_type: string; content: string; type: string; created_at: string; }

export default function Conversations() {
  const [conversations, setConversations] = useState<ConvItem[]>([]);
  const [selected, setSelected] = useState<ConvItem | null>(null);
  const [messages, setMessages] = useState<Msg[]>([]);
  const [newMsg, setNewMsg] = useState('');
  const [loading, setLoading] = useState(true);

  useEffect(() => { loadConversations(); }, []);
  const loadConversations = async () => { try { const r = await crmService.getConversations(); setConversations(r.data); } catch {} finally { setLoading(false); } };

  const selectConv = async (conv: ConvItem) => {
    setSelected(conv);
    try { const r = await crmService.getMessages(conv.id); setMessages(r.data); } catch {}
  };

  const handleSend = async () => {
    if (!newMsg.trim() || !selected) return;
    try { await crmService.sendMessage(selected.id, newMsg); setNewMsg(''); const r = await crmService.getMessages(selected.id); setMessages(r.data); } catch {}
  };

  if (loading) return <div className="min-h-screen bg-[#0a0a0f] flex items-center justify-center"><div className="w-10 h-10 border-2 border-blue-500/30 border-t-blue-500 rounded-full animate-spin" /></div>;

  return (
    <div className="min-h-screen bg-[#0a0a0f] text-white flex">
      {/* Sidebar: conversation list */}
      <div className="w-80 border-r border-[#1e1e2e] flex flex-col">
        <div className="p-4 border-b border-[#1e1e2e]"><h2 className="text-lg font-bold">Inbox</h2><p className="text-xs text-gray-500">{conversations.length} conversations</p></div>
        <div className="flex-1 overflow-auto">
          {conversations.map(c => (
            <button key={c.id} onClick={() => selectConv(c)} className={`w-full text-left px-4 py-3 border-b border-[#1e1e2e]/50 hover:bg-white/[0.03] transition ${selected?.id === c.id ? 'bg-blue-600/10' : ''}`}>
              <div className="flex items-center gap-3">
                <div className="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-xs font-bold">{c.contact ? c.contact.first_name[0] : '?'}</div>
                <div className="flex-1 min-w-0">
                  <p className="text-sm text-white truncate">{c.contact ? `${c.contact.first_name} ${c.contact.last_name}` : 'Unknown'}</p>
                  <p className="text-xs text-gray-500 truncate">{c.channel} | {c.status}</p>
                </div>
              </div>
            </button>
          ))}
          {conversations.length === 0 && <p className="text-gray-600 text-center py-8 text-sm">No conversations</p>}
        </div>
      </div>

      {/* Chat area */}
      <div className="flex-1 flex flex-col">
        {selected ? (
          <>
            <div className="p-4 border-b border-[#1e1e2e] flex items-center gap-3">
              <div className="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-xs font-bold">{selected.contact?.first_name[0] || '?'}</div>
              <div><p className="text-sm font-medium">{selected.contact ? `${selected.contact.first_name} ${selected.contact.last_name}` : 'Unknown'}</p><p className="text-xs text-gray-500">{selected.contact?.email}</p></div>
            </div>
            <div className="flex-1 overflow-auto p-4 space-y-3">
              {messages.map(m => (
                <div key={m.id} className={`flex ${m.sender_type === 'user' ? 'justify-end' : 'justify-start'}`}>
                  <div className={`max-w-md px-4 py-2 rounded-2xl text-sm ${m.sender_type === 'user' ? 'bg-blue-600 text-white' : 'bg-[#12121a] border border-[#1e1e2e] text-gray-300'}`}>{m.content}</div>
                </div>
              ))}
              {messages.length === 0 && <p className="text-gray-600 text-center py-8">No messages yet</p>}
            </div>
            <div className="p-4 border-t border-[#1e1e2e] flex gap-3">
              <input value={newMsg} onChange={e => setNewMsg(e.target.value)} placeholder="Type a message..." className="flex-1 bg-[#12121a] border border-[#1e1e2e] rounded-xl px-4 py-2.5 text-sm text-white outline-none focus:border-blue-500/50" onKeyDown={e => e.key === 'Enter' && handleSend()} />
              <button onClick={handleSend} className="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 rounded-xl text-sm font-medium">Send</button>
            </div>
          </>
        ) : (
          <div className="flex-1 flex items-center justify-center"><p className="text-gray-600">Select a conversation</p></div>
        )}
      </div>
    </div>
  );
}
