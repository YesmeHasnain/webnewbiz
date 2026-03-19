import { useState, useEffect, useCallback } from 'react';
import { useOutletContext } from 'react-router-dom';
import { builderPluginService } from '../../services/builder-plugin.service';

interface Backup {
  id: string;
  type: string;
  date: string;
  size: string;
}

export default function BackupsPage() {
  const { website } = useOutletContext<{ website: any }>();
  const websiteId = website.id;

  const [backups, setBackups] = useState<Backup[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [toast, setToast] = useState('');
  const [creating, setCreating] = useState('');
  const [actionLoading, setActionLoading] = useState('');
  const [confirmRestore, setConfirmRestore] = useState<string | null>(null);

  const showToast = (msg: string) => {
    setToast(msg);
    setTimeout(() => setToast(''), 3000);
  };

  const fetchBackups = useCallback(async () => {
    setLoading(true);
    setError('');
    try {
      const res = await builderPluginService.getBackups(websiteId);
      setBackups(res.data?.backups || res.data?.data || []);
    } catch {
      setError('Failed to load backups');
      setBackups([]);
    } finally {
      setLoading(false);
    }
  }, [websiteId]);

  useEffect(() => { fetchBackups(); }, [fetchBackups]);

  const handleCreate = async (type: string) => {
    setCreating(type);
    setError('');
    try {
      await builderPluginService.createBackup(websiteId, type);
      showToast(`${type.charAt(0).toUpperCase() + type.slice(1)} backup created`);
      await fetchBackups();
    } catch {
      setError('Failed to create backup');
    } finally {
      setCreating('');
    }
  };

  const handleRestore = async (backupId: string) => {
    setConfirmRestore(null);
    setActionLoading(backupId);
    setError('');
    try {
      await builderPluginService.restoreBackup(websiteId, backupId);
      showToast('Backup restored successfully');
    } catch {
      setError('Failed to restore backup');
    } finally {
      setActionLoading('');
    }
  };

  const handleDelete = async (backupId: string) => {
    if (!confirm('Delete this backup? This cannot be undone.')) return;
    setActionLoading(backupId);
    setError('');
    try {
      await builderPluginService.deleteBackup(websiteId, backupId);
      showToast('Backup deleted');
      await fetchBackups();
    } catch {
      setError('Failed to delete backup');
    } finally {
      setActionLoading('');
    }
  };

  const getTypeBadge = (type: string) => {
    const colors: Record<string, { bg: string; color: string }> = {
      full: { bg: '#F3F0FF', color: '#7c5cfc' },
      database: { bg: '#ECFDF5', color: '#059669' },
      files: { bg: '#FEF3C7', color: '#D97706' },
    };
    const c = colors[type] || colors.full;
    return (
      <span style={{ ...s.typeBadge, background: c.bg, color: c.color }}>
        {type.charAt(0).toUpperCase() + type.slice(1)}
      </span>
    );
  };

  return (
    <div style={s.page}>
      {/* Toast */}
      {toast && <div style={s.toast}>{toast}</div>}

      {/* Restore Confirmation Modal */}
      {confirmRestore && (
        <div style={s.overlay} onClick={() => setConfirmRestore(null)}>
          <div style={s.modal} onClick={(e) => e.stopPropagation()}>
            <div style={s.modalIcon}>
              <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#F59E0B" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
              </svg>
            </div>
            <h3 style={s.modalTitle}>Restore Backup?</h3>
            <p style={s.modalDesc}>
              This will replace your current website with the backup. This action cannot be undone.
            </p>
            <div style={s.modalActions}>
              <button
                style={s.modalCancel}
                className="bu-modal-cancel"
                onClick={() => setConfirmRestore(null)}
              >
                Cancel
              </button>
              <button
                style={s.modalConfirm}
                className="bu-modal-confirm"
                onClick={() => handleRestore(confirmRestore)}
              >
                Yes, Restore
              </button>
            </div>
          </div>
        </div>
      )}

      {/* Error */}
      {error && (
        <div style={s.errorBar}>
          {error}
          <button style={s.errorClose} onClick={() => setError('')}>&times;</button>
        </div>
      )}

      {/* Header */}
      <div style={s.header}>
        <div>
          <h1 style={s.h1}>Backups</h1>
          <p style={s.subtitle}>Create and manage website backups</p>
        </div>
      </div>

      {/* Create Backup Section */}
      <div style={s.card}>
        <h2 style={s.cardTitle}>Create New Backup</h2>
        <p style={s.cardDesc}>Choose the type of backup you want to create</p>
        <div style={s.typeRow} className="bu-type-row">
          {[
            { type: 'database', label: 'Database', desc: 'Database tables only', icon: 'M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4' },
            { type: 'files', label: 'Files', desc: 'All site files', icon: 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z' },
            { type: 'full', label: 'Full Backup', desc: 'Database + all files', icon: 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4' },
          ].map((item) => (
            <button
              key={item.type}
              style={{ ...s.typeCard, ...(creating === item.type ? { borderColor: '#7c5cfc' } : {}) }}
              className="bu-type-card"
              onClick={() => handleCreate(item.type)}
              disabled={creating !== ''}
            >
              {creating === item.type ? (
                <div style={s.typeSpinnerWrap}>
                  <div style={{ ...s.spinner, width: 20, height: 20, borderWidth: 2 }} />
                  <span style={s.typeCreating}>Creating...</span>
                </div>
              ) : (
                <>
                  <div style={s.typeIcon}>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#7c5cfc" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                      <path d={item.icon} />
                    </svg>
                  </div>
                  <div style={s.typeLabel}>{item.label}</div>
                  <div style={s.typeDesc}>{item.desc}</div>
                </>
              )}
            </button>
          ))}
        </div>
      </div>

      {/* Backups List */}
      <div style={s.card}>
        <h2 style={s.cardTitle}>Existing Backups</h2>
        {loading ? (
          <div style={s.loadingWrap}>
            <div style={s.spinner} />
            <p style={{ color: '#6B7280', fontSize: 13 }}>Loading backups...</p>
          </div>
        ) : backups.length === 0 ? (
          <div style={s.emptyWrap}>
            <svg width="48" height="48" fill="none" stroke="#D1D5DB" strokeWidth="1.2" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
            </svg>
            <p style={{ fontSize: 14, color: '#6B7280', marginTop: 12 }}>No backups yet</p>
            <p style={{ fontSize: 12, color: '#9CA3AF' }}>Create your first backup above</p>
          </div>
        ) : (
          <table style={s.table}>
            <thead>
              <tr>
                <th style={s.th}>Type</th>
                <th style={s.th}>Date</th>
                <th style={s.th}>Size</th>
                <th style={{ ...s.th, textAlign: 'right' }}>Actions</th>
              </tr>
            </thead>
            <tbody>
              {backups.map((b) => (
                <tr key={b.id} className="bu-row">
                  <td style={s.td}>{getTypeBadge(b.type)}</td>
                  <td style={s.td}>
                    <span style={s.dateText}>
                      {new Date(b.date).toLocaleDateString('en', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' })}
                    </span>
                  </td>
                  <td style={s.td}><span style={s.sizeText}>{b.size}</span></td>
                  <td style={{ ...s.td, textAlign: 'right' }}>
                    {actionLoading === b.id ? (
                      <div style={{ display: 'inline-block' }}>
                        <div style={{ ...s.spinner, width: 18, height: 18, borderWidth: 2 }} />
                      </div>
                    ) : (
                      <div style={s.rowActions}>
                        <button
                          style={s.restoreBtn}
                          className="bu-restore-btn"
                          onClick={() => setConfirmRestore(b.id)}
                        >
                          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                            <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                          </svg>
                          Restore
                        </button>
                        <button
                          style={s.deleteBtn}
                          className="bu-delete-btn"
                          onClick={() => handleDelete(b.id)}
                        >
                          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                          </svg>
                          Delete
                        </button>
                      </div>
                    )}
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        )}
      </div>

      <style>{cssStr}</style>
    </div>
  );
}

const spinCss = `@keyframes bu-spin { to { transform: rotate(360deg); } }`;

const cssStr = `
  ${spinCss}
  .bu-type-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-top: 16px;
  }
  .bu-type-card:hover:not(:disabled) {
    border-color: #7c5cfc !important;
    box-shadow: 0 2px 8px rgba(124,92,252,0.1);
  }
  .bu-row:hover { background: #F9FAFB; }
  .bu-restore-btn:hover { background: #F3F0FF !important; color: #7c5cfc !important; }
  .bu-delete-btn:hover { background: #FEE2E2 !important; color: #DC2626 !important; }
  .bu-modal-cancel:hover { background: #F3F4F6 !important; }
  .bu-modal-confirm:hover { background: #DC2626 !important; }
  @media (max-width: 700px) {
    .bu-type-row { grid-template-columns: 1fr !important; }
  }
`;

const s: Record<string, React.CSSProperties> = {
  page: { position: 'relative' },
  toast: {
    position: 'fixed', top: 24, right: 24, zIndex: 9999,
    padding: '10px 20px', background: '#111827', color: '#fff',
    borderRadius: 8, fontSize: 13, fontWeight: 500,
    boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
  },
  overlay: {
    position: 'fixed', inset: 0, background: 'rgba(0,0,0,0.5)',
    zIndex: 10000, display: 'flex', alignItems: 'center', justifyContent: 'center',
  },
  modal: {
    background: '#fff', borderRadius: 16, padding: 32,
    maxWidth: 420, width: '90%', textAlign: 'center',
    boxShadow: '0 20px 60px rgba(0,0,0,0.2)',
  },
  modalIcon: { marginBottom: 16 },
  modalTitle: { fontSize: 18, fontWeight: 700, color: '#111827', margin: '0 0 8px' },
  modalDesc: { fontSize: 13, color: '#6B7280', lineHeight: 1.5, margin: '0 0 24px' },
  modalActions: { display: 'flex', gap: 12, justifyContent: 'center' },
  modalCancel: {
    padding: '10px 24px', border: '1px solid #D1D5DB', borderRadius: 8,
    background: '#fff', fontSize: 13, fontWeight: 500, color: '#374151', cursor: 'pointer',
  },
  modalConfirm: {
    padding: '10px 24px', border: 'none', borderRadius: 8,
    background: '#EF4444', color: '#fff', fontSize: 13, fontWeight: 600, cursor: 'pointer',
  },
  errorBar: {
    display: 'flex', alignItems: 'center', justifyContent: 'space-between',
    padding: '10px 16px', background: '#FEE2E2', color: '#991B1B',
    fontSize: 13, borderRadius: 10, marginBottom: 16,
  },
  errorClose: {
    background: 'none', border: 'none', fontSize: 18, cursor: 'pointer',
    color: '#991B1B', padding: '0 4px',
  },
  header: {
    display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between',
    marginBottom: 20,
  },
  h1: { fontSize: 22, fontWeight: 700, color: '#111827', margin: '0 0 4px' },
  subtitle: { fontSize: 13, color: '#6B7280', margin: 0 },
  card: {
    background: '#fff', borderRadius: 12, border: '1px solid #e5e7eb',
    padding: '24px', marginBottom: 20,
  },
  cardTitle: { fontSize: 16, fontWeight: 600, color: '#111827', margin: '0 0 4px' },
  cardDesc: { fontSize: 13, color: '#6B7280', margin: 0 },
  typeRow: {},
  typeCard: {
    display: 'flex', flexDirection: 'column', alignItems: 'center',
    padding: 24, border: '1px solid #e5e7eb', borderRadius: 12,
    background: '#fff', cursor: 'pointer', transition: 'all 0.15s',
    textAlign: 'center',
  },
  typeIcon: {
    width: 52, height: 52, borderRadius: 14, background: '#F3F0FF',
    display: 'flex', alignItems: 'center', justifyContent: 'center',
    marginBottom: 12,
  },
  typeLabel: { fontSize: 14, fontWeight: 600, color: '#111827', marginBottom: 4 },
  typeDesc: { fontSize: 12, color: '#6B7280' },
  typeSpinnerWrap: {
    display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 8,
    padding: '8px 0',
  },
  typeCreating: { fontSize: 13, color: '#7c5cfc', fontWeight: 500 },
  loadingWrap: {
    display: 'flex', flexDirection: 'column', alignItems: 'center',
    justifyContent: 'center', padding: '40px 0',
  },
  spinner: {
    width: 28, height: 28, border: '3px solid #E5E7EB', borderTopColor: '#7c5cfc',
    borderRadius: '50%', animation: 'bu-spin 0.6s linear infinite',
  },
  emptyWrap: {
    display: 'flex', flexDirection: 'column', alignItems: 'center',
    justifyContent: 'center', padding: '40px 0', textAlign: 'center',
  },
  table: { width: '100%', borderCollapse: 'collapse', marginTop: 12 },
  th: {
    textAlign: 'left', padding: '10px 0', fontSize: 11, fontWeight: 600,
    color: '#6B7280', borderBottom: '1px solid #E5E7EB',
    textTransform: 'uppercase', letterSpacing: 0.3,
  },
  td: {
    padding: '14px 0', fontSize: 13, color: '#111827',
    borderBottom: '1px solid #F3F4F6', verticalAlign: 'middle',
  },
  typeBadge: {
    display: 'inline-flex', padding: '3px 12px', borderRadius: 20,
    fontSize: 12, fontWeight: 600,
  },
  dateText: { fontSize: 13, color: '#374151' },
  sizeText: { fontSize: 13, color: '#6B7280', fontWeight: 500 },
  rowActions: { display: 'flex', gap: 8, justifyContent: 'flex-end' },
  restoreBtn: {
    display: 'inline-flex', alignItems: 'center', gap: 4,
    padding: '6px 14px', border: '1px solid #e5e7eb', borderRadius: 8,
    background: '#fff', fontSize: 12, fontWeight: 500, color: '#374151',
    cursor: 'pointer', transition: 'all 0.15s',
  },
  deleteBtn: {
    display: 'inline-flex', alignItems: 'center', gap: 4,
    padding: '6px 14px', border: '1px solid #e5e7eb', borderRadius: 8,
    background: '#fff', fontSize: 12, fontWeight: 500, color: '#6B7280',
    cursor: 'pointer', transition: 'all 0.15s',
  },
};
