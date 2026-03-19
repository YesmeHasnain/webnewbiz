import { useState, useEffect, useCallback } from 'react';
import { useParams } from 'react-router-dom';
import { wpManagerService } from '../../services/wp-manager.service';
import type { WooOrder } from '../../models/types';

export default function WooOrders() {
  const { id } = useParams<{ id: string }>();
  const websiteId = Number(id);

  const [orders, setOrders] = useState<WooOrder[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [page, setPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [total, setTotal] = useState(0);
  const [statusFilter, setStatusFilter] = useState('');

  const fetchOrders = useCallback(async () => {
    setLoading(true);
    setError('');
    try {
      const res = await wpManagerService.listOrders(websiteId, { page, per_page: 20, status: statusFilter || undefined });
      setOrders(res.data.data || []);
      setTotalPages(res.data.pages || 1);
      setTotal(res.data.total || 0);
    } catch (e: any) {
      setError(e.response?.data?.message || e.message || 'Failed to load orders');
    } finally {
      setLoading(false);
    }
  }, [websiteId, page, statusFilter]);

  useEffect(() => { fetchOrders(); }, [fetchOrders]);

  const statusColor = (status: string) => {
    const map: Record<string, { bg: string; color: string }> = {
      completed: { bg: '#DEF7EC', color: '#03543F' },
      processing: { bg: '#DBEAFE', color: '#1E40AF' },
      'on-hold': { bg: '#FEF3C7', color: '#92400E' },
      pending: { bg: '#FEF3C7', color: '#92400E' },
      cancelled: { bg: '#FDE8E8', color: '#9B1C1C' },
      refunded: { bg: '#E5E7EB', color: '#374151' },
      failed: { bg: '#FDE8E8', color: '#9B1C1C' },
    };
    return map[status] || { bg: '#E5E7EB', color: '#374151' };
  };

  return (
    <div style={s.page}>
      <div style={s.header}>
        <div>
          <h2 style={s.title}>Orders</h2>
          <p style={s.subtitle}>{total} order{total !== 1 ? 's' : ''} in WooCommerce</p>
        </div>
        <select style={s.select} value={statusFilter} onChange={e => { setStatusFilter(e.target.value); setPage(1); }}>
          <option value="">All Statuses</option>
          <option value="processing">Processing</option>
          <option value="completed">Completed</option>
          <option value="on-hold">On Hold</option>
          <option value="pending">Pending</option>
          <option value="cancelled">Cancelled</option>
          <option value="refunded">Refunded</option>
        </select>
      </div>

      {error && <div style={s.error}>{error}</div>}

      <div style={s.tableWrap}>
        {loading ? (
          <div style={s.center}>
            <div style={s.spinner} />
            <p style={{ color: '#6B7280', marginTop: 12, fontSize: 13 }}>Loading orders...</p>
          </div>
        ) : orders.length === 0 ? (
          <div style={s.center}>
            <svg width="48" height="48" fill="none" stroke="#D1D5DB" viewBox="0 0 24 24">
              <path strokeWidth="1.5" strokeLinecap="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <p style={{ color: '#6B7280', marginTop: 12, fontSize: 14 }}>No orders yet</p>
          </div>
        ) : (
          <table style={s.table}>
            <thead>
              <tr>
                <th style={s.th}>Order</th>
                <th style={{ ...s.th, textAlign: 'left' }}>Customer</th>
                <th style={s.th}>Status</th>
                <th style={s.th}>Items</th>
                <th style={s.th}>Total</th>
                <th style={s.th}>Date</th>
              </tr>
            </thead>
            <tbody>
              {orders.map(o => {
                const sc = statusColor(o.status);
                return (
                  <tr key={o.id} style={s.tr} className="woo-tr">
                    <td style={{ ...s.td, fontWeight: 600 }}>#{o.id}</td>
                    <td style={{ ...s.td, textAlign: 'left' }}>
                      <div style={{ fontWeight: 500 }}>{o.customer || 'Guest'}</div>
                      <div style={{ fontSize: 11, color: '#9CA3AF' }}>{o.email}</div>
                    </td>
                    <td style={s.td}>
                      <span style={{ ...s.badge, background: sc.bg, color: sc.color }}>{o.status}</span>
                    </td>
                    <td style={s.td}>{o.items_count}</td>
                    <td style={{ ...s.td, fontWeight: 600 }}>{o.currency} {o.total}</td>
                    <td style={{ ...s.td, fontSize: 12, color: '#6B7280' }}>{new Date(o.date_created).toLocaleDateString()}</td>
                  </tr>
                );
              })}
            </tbody>
          </table>
        )}
      </div>

      {totalPages > 1 && (
        <div style={s.pagination}>
          <button style={{ ...s.pageBtn, ...(page <= 1 ? { opacity: 0.4 } : {}) }} className="woo-page-btn" disabled={page <= 1} onClick={() => setPage(p => p - 1)}>Previous</button>
          <span style={{ fontSize: 13, color: '#6B7280' }}>Page {page} of {totalPages}</span>
          <button style={{ ...s.pageBtn, ...(page >= totalPages ? { opacity: 0.4 } : {}) }} className="woo-page-btn" disabled={page >= totalPages} onClick={() => setPage(p => p + 1)}>Next</button>
        </div>
      )}

      <style>{`
        .woo-tr:hover { background: #F9FAFB !important; }
        .woo-page-btn:hover:not(:disabled) { background: #F3F4F6 !important; }
        @keyframes woo-spin { to { transform: rotate(360deg); } }
      `}</style>
    </div>
  );
}

const s: Record<string, React.CSSProperties> = {
  page: { padding: '24px 28px', maxWidth: 1100 },
  header: { display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 20, flexWrap: 'wrap' as const, gap: 12 },
  title: { fontSize: 20, fontWeight: 700, color: '#111827', margin: 0 },
  subtitle: { fontSize: 13, color: '#6B7280', marginTop: 2 },
  select: { padding: '8px 14px', border: '1px solid #E5E7EB', borderRadius: 10, fontSize: 13, background: '#fff', cursor: 'pointer' },
  error: { padding: '10px 14px', background: '#FEE2E2', color: '#991B1B', borderRadius: 10, fontSize: 13, marginBottom: 14 },
  tableWrap: { background: '#fff', border: '1px solid #E5E7EB', borderRadius: 14, overflow: 'hidden' },
  table: { width: '100%', borderCollapse: 'collapse' as const },
  th: { padding: '12px 14px', fontSize: 11, fontWeight: 600, color: '#6B7280', textTransform: 'uppercase' as const, borderBottom: '1px solid #E5E7EB', textAlign: 'center' as const, letterSpacing: 0.5 },
  tr: { borderBottom: '1px solid #F3F4F6', transition: 'background 0.15s' },
  td: { padding: '12px 14px', fontSize: 13, color: '#374151', textAlign: 'center' as const, verticalAlign: 'middle' as const },
  badge: { display: 'inline-block', padding: '3px 10px', borderRadius: 20, fontSize: 11, fontWeight: 600 },
  center: { display: 'flex', flexDirection: 'column' as const, alignItems: 'center', justifyContent: 'center', padding: 60 },
  spinner: { width: 28, height: 28, border: '3px solid #E5E7EB', borderTopColor: '#111827', borderRadius: '50%', animation: 'woo-spin 0.6s linear infinite' },
  pagination: { display: 'flex', justifyContent: 'center', alignItems: 'center', gap: 16, marginTop: 16, padding: '12px 0' },
  pageBtn: { padding: '7px 16px', border: '1px solid #E5E7EB', borderRadius: 8, background: '#fff', fontSize: 13, cursor: 'pointer', color: '#374151' },
};
