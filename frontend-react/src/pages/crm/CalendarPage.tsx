import { useState, useEffect } from 'react';
import { crmService } from '../../services/crm.service';

interface BookingItem { id: number; start_time: string; end_time: string; status: string; notes: string | null; contact: { id: number; first_name: string; last_name: string; email: string } | null; }

export default function CalendarPage() {
  const [bookings, setBookings] = useState<BookingItem[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => { loadBookings(); }, []);
  const loadBookings = async () => { try { const r = await crmService.getBookings(); setBookings(r.data); } catch {} finally { setLoading(false); } };

  const formatDate = (d: string) => new Date(d).toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });
  const formatTime = (d: string) => new Date(d).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });

  if (loading) return <div className="min-h-screen bg-[#0a0a0f] flex items-center justify-center"><div className="w-10 h-10 border-2 border-blue-500/30 border-t-blue-500 rounded-full animate-spin" /></div>;

  // Group bookings by date
  const grouped = bookings.reduce<Record<string, BookingItem[]>>((acc, b) => {
    const date = formatDate(b.start_time);
    (acc[date] = acc[date] || []).push(b);
    return acc;
  }, {});

  return (
    <div className="min-h-screen bg-[#0a0a0f] text-white">
      <div className="max-w-4xl mx-auto px-6 py-8">
        <div className="flex items-center justify-between mb-8">
          <div><h1 className="text-2xl font-bold">Calendar & Bookings</h1><p className="text-sm text-gray-500 mt-1">Manage appointments and meetings</p></div>
        </div>

        {Object.keys(grouped).length === 0 ? (
          <div className="text-center py-16"><div className="w-16 h-16 mx-auto mb-4 bg-[#12121a] border border-[#1e1e2e] rounded-2xl flex items-center justify-center"><svg className="w-8 h-8 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1} d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg></div><p className="text-gray-600">No bookings scheduled</p></div>
        ) : (
          <div className="space-y-6">
            {Object.entries(grouped).map(([date, items]) => (
              <div key={date}>
                <h3 className="text-sm font-medium text-gray-400 mb-3">{date}</h3>
                <div className="space-y-2">
                  {items.map(b => (
                    <div key={b.id} className="bg-[#12121a] border border-[#1e1e2e] rounded-xl p-4 flex items-center gap-4">
                      <div className="w-1 h-10 rounded-full bg-blue-500" />
                      <div className="flex-1">
                        <p className="text-sm text-white font-medium">{formatTime(b.start_time)} - {formatTime(b.end_time)}</p>
                        {b.contact && <p className="text-xs text-gray-500">{b.contact.first_name} {b.contact.last_name}</p>}
                        {b.notes && <p className="text-xs text-gray-600 mt-1">{b.notes}</p>}
                      </div>
                      <span className={`px-2 py-1 rounded-lg text-xs ${b.status === 'confirmed' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-gray-500/10 text-gray-400'}`}>{b.status}</span>
                    </div>
                  ))}
                </div>
              </div>
            ))}
          </div>
        )}
      </div>
    </div>
  );
}
