import { useState, useEffect, useCallback } from 'react';
import { useParams } from 'react-router-dom';
import { wpManagerService } from '../../services/wp-manager.service';
import type { WooProduct, WooCategory } from '../../models/types';

type ModalMode = 'create' | 'edit' | null;

interface ProductForm {
  name: string;
  regular_price: string;
  sale_price: string;
  sku: string;
  description: string;
  short_description: string;
  stock_status: string;
  stock_quantity: string;
  status: string;
  image_url: string;
  image_file: File | null;
  image_preview: string;
  category_ids: number[];
}

const emptyForm: ProductForm = {
  name: '', regular_price: '', sale_price: '', sku: '',
  description: '', short_description: '',
  stock_status: 'instock', stock_quantity: '', status: 'publish',
  image_url: '', image_file: null, image_preview: '', category_ids: [],
};

export default function WooProducts() {
  const { id } = useParams<{ id: string }>();
  const websiteId = Number(id);

  const [products, setProducts] = useState<WooProduct[]>([]);
  const [categories, setCategories] = useState<WooCategory[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [search, setSearch] = useState('');
  const [page, setPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [total, setTotal] = useState(0);

  // Modal
  const [modalMode, setModalMode] = useState<ModalMode>(null);
  const [editingId, setEditingId] = useState<number | null>(null);
  const [form, setForm] = useState<ProductForm>({ ...emptyForm });
  const [saving, setSaving] = useState(false);
  const [deleting, setDeleting] = useState<number | null>(null);

  const fetchProducts = useCallback(async () => {
    setLoading(true);
    setError('');
    try {
      const res = await wpManagerService.listProducts(websiteId, { page, per_page: 20, search: search || undefined });
      setProducts(res.data.data || []);
      setTotalPages(res.data.pages || 1);
      setTotal(res.data.total || 0);
    } catch (e: any) {
      setError(e.response?.data?.message || e.message || 'Failed to load products');
    } finally {
      setLoading(false);
    }
  }, [websiteId, page, search]);

  const fetchCategories = useCallback(async () => {
    try {
      const res = await wpManagerService.listCategories(websiteId);
      setCategories(res.data.data || []);
    } catch { /* ignore */ }
  }, [websiteId]);

  useEffect(() => { fetchProducts(); }, [fetchProducts]);
  useEffect(() => { fetchCategories(); }, [fetchCategories]);

  const openCreate = () => {
    setForm({ ...emptyForm });
    setEditingId(null);
    setModalMode('create');
  };

  const openEdit = (p: WooProduct) => {
    setForm({
      name: p.name,
      regular_price: p.regular_price,
      sale_price: p.sale_price,
      sku: p.sku,
      description: p.description,
      short_description: p.short_description,
      stock_status: p.stock_status,
      stock_quantity: p.stock_quantity != null ? String(p.stock_quantity) : '',
      status: p.status,
      image_url: '',
      image_file: null,
      image_preview: p.featured_image || '',
      category_ids: p.categories.map(c => c.id),
    });
    setEditingId(p.id);
    setModalMode('edit');
  };

  const handleSave = async () => {
    setSaving(true);
    setError('');
    try {
      const fd = new FormData();
      fd.append('name', form.name);
      fd.append('regular_price', form.regular_price);
      fd.append('status', form.status);
      fd.append('stock_status', form.stock_status);
      if (form.sale_price) fd.append('sale_price', form.sale_price);
      if (form.sku) fd.append('sku', form.sku);
      if (form.description) fd.append('description', form.description);
      if (form.short_description) fd.append('short_description', form.short_description);
      if (form.stock_quantity) fd.append('stock_quantity', form.stock_quantity);
      if (form.category_ids.length) fd.append('category_ids', JSON.stringify(form.category_ids));
      if (form.image_file) {
        fd.append('image', form.image_file);
      } else if (form.image_url) {
        fd.append('image_url', form.image_url);
      }

      if (modalMode === 'create') {
        await wpManagerService.createProduct(websiteId, fd);
      } else if (editingId) {
        await wpManagerService.updateProduct(websiteId, editingId, fd);
      }
      setModalMode(null);
      fetchProducts();
    } catch (e: any) {
      setError(e.response?.data?.message || e.message || 'Save failed');
    } finally {
      setSaving(false);
    }
  };

  const handleDelete = async (productId: number) => {
    if (!confirm('Delete this product?')) return;
    setDeleting(productId);
    try {
      await wpManagerService.deleteProduct(websiteId, productId, true);
      fetchProducts();
    } catch (e: any) {
      setError(e.response?.data?.message || e.message || 'Delete failed');
    } finally {
      setDeleting(null);
    }
  };

  const updateField = (field: keyof ProductForm, value: any) => {
    setForm(prev => ({ ...prev, [field]: value }));
  };

  const toggleCategory = (catId: number) => {
    setForm(prev => ({
      ...prev,
      category_ids: prev.category_ids.includes(catId)
        ? prev.category_ids.filter(id => id !== catId)
        : [...prev.category_ids, catId],
    }));
  };

  const stockBadge = (status: string) => {
    const colors: Record<string, { bg: string; color: string }> = {
      instock: { bg: '#DEF7EC', color: '#03543F' },
      outofstock: { bg: '#FDE8E8', color: '#9B1C1C' },
      onbackorder: { bg: '#FEF3C7', color: '#92400E' },
    };
    const c = colors[status] || colors.instock;
    return <span style={{ ...s.badge, background: c.bg, color: c.color }}>{status.replace('of', ' of ')}</span>;
  };

  const statusBadge = (status: string) => {
    const colors: Record<string, { bg: string; color: string }> = {
      publish: { bg: '#DEF7EC', color: '#03543F' },
      draft: { bg: '#E5E7EB', color: '#374151' },
      pending: { bg: '#FEF3C7', color: '#92400E' },
      private: { bg: '#DBEAFE', color: '#1E40AF' },
    };
    const c = colors[status] || { bg: '#E5E7EB', color: '#374151' };
    return <span style={{ ...s.badge, background: c.bg, color: c.color }}>{status}</span>;
  };

  return (
    <div style={s.page}>
      {/* Header */}
      <div style={s.header}>
        <div>
          <h2 style={s.title}>Products</h2>
          <p style={s.subtitle}>{total} product{total !== 1 ? 's' : ''} in WooCommerce</p>
        </div>
        <button style={s.addBtn} className="woo-add-btn" onClick={openCreate}>
          <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeWidth="2" strokeLinecap="round" d="M12 4v16m8-8H4" />
          </svg>
          Add Product
        </button>
      </div>

      {/* Search */}
      <div style={s.toolbar}>
        <div style={s.searchWrap}>
          <svg width="16" height="16" fill="none" stroke="#9CA3AF" viewBox="0 0 24 24" style={{ flexShrink: 0 }}>
            <path strokeWidth="2" strokeLinecap="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          <input
            style={s.searchInput}
            placeholder="Search products..."
            value={search}
            onChange={e => { setSearch(e.target.value); setPage(1); }}
          />
        </div>
      </div>

      {error && <div style={s.error}>{error}</div>}

      {/* Table */}
      <div style={s.tableWrap}>
        {loading ? (
          <div style={s.center}>
            <div style={s.spinner} />
            <p style={{ color: '#6B7280', marginTop: 12, fontSize: 13 }}>Loading products...</p>
          </div>
        ) : products.length === 0 ? (
          <div style={s.center}>
            <svg width="48" height="48" fill="none" stroke="#D1D5DB" viewBox="0 0 24 24">
              <path strokeWidth="1.5" strokeLinecap="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            <p style={{ color: '#6B7280', marginTop: 12, fontSize: 14 }}>No products found</p>
            <button style={{ ...s.addBtn, marginTop: 12 }} className="woo-add-btn" onClick={openCreate}>Add First Product</button>
          </div>
        ) : (
          <table style={s.table}>
            <thead>
              <tr>
                <th style={s.th}>Image</th>
                <th style={{ ...s.th, textAlign: 'left' }}>Name</th>
                <th style={s.th}>SKU</th>
                <th style={s.th}>Price</th>
                <th style={s.th}>Stock</th>
                <th style={s.th}>Status</th>
                <th style={s.th}>Actions</th>
              </tr>
            </thead>
            <tbody>
              {products.map(p => (
                <tr key={p.id} style={s.tr} className="woo-tr">
                  <td style={s.td}>
                    {p.featured_image ? (
                      <img src={p.featured_image} alt="" style={s.img} />
                    ) : (
                      <div style={s.noImg}>
                        <svg width="20" height="20" fill="none" stroke="#D1D5DB" viewBox="0 0 24 24">
                          <path strokeWidth="1.5" strokeLinecap="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                      </div>
                    )}
                  </td>
                  <td style={{ ...s.td, textAlign: 'left', fontWeight: 500 }}>
                    <div>{p.name}</div>
                    {p.categories.length > 0 && (
                      <div style={{ fontSize: 11, color: '#9CA3AF', marginTop: 2 }}>
                        {p.categories.map(c => c.name).join(', ')}
                      </div>
                    )}
                  </td>
                  <td style={{ ...s.td, fontFamily: 'monospace', fontSize: 12 }}>{p.sku || '—'}</td>
                  <td style={s.td}>
                    {p.sale_price ? (
                      <div>
                        <span style={{ textDecoration: 'line-through', color: '#9CA3AF', fontSize: 12 }}>${p.regular_price}</span>
                        <span style={{ color: '#DC2626', fontWeight: 600, marginLeft: 4 }}>${p.sale_price}</span>
                      </div>
                    ) : (
                      <span style={{ fontWeight: 500 }}>${p.regular_price || p.price}</span>
                    )}
                  </td>
                  <td style={s.td}>{stockBadge(p.stock_status)}</td>
                  <td style={s.td}>{statusBadge(p.status)}</td>
                  <td style={s.td}>
                    <div style={{ display: 'flex', gap: 6, justifyContent: 'center' }}>
                      <button style={s.iconBtn} className="woo-icon-btn" onClick={() => openEdit(p)} title="Edit">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path strokeWidth="2" strokeLinecap="round" d="M15.232 5.232l3.536 3.536M4 20h4.586a1 1 0 00.707-.293l10.9-10.9a2 2 0 000-2.828l-2.172-2.172a2 2 0 00-2.828 0l-10.9 10.9A1 1 0 004 15.414V20z" />
                        </svg>
                      </button>
                      <button
                        style={{ ...s.iconBtn, color: '#DC2626' }}
                        className="woo-icon-btn-del"
                        onClick={() => handleDelete(p.id)}
                        disabled={deleting === p.id}
                        title="Delete"
                      >
                        {deleting === p.id ? (
                          <div style={{ ...s.spinner, width: 14, height: 14, borderWidth: 2 }} />
                        ) : (
                          <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeWidth="2" strokeLinecap="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                          </svg>
                        )}
                      </button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        )}
      </div>

      {/* Pagination */}
      {totalPages > 1 && (
        <div style={s.pagination}>
          <button
            style={{ ...s.pageBtn, ...(page <= 1 ? { opacity: 0.4, cursor: 'default' } : {}) }}
            className="woo-page-btn"
            disabled={page <= 1}
            onClick={() => setPage(p => p - 1)}
          >
            Previous
          </button>
          <span style={{ fontSize: 13, color: '#6B7280' }}>Page {page} of {totalPages}</span>
          <button
            style={{ ...s.pageBtn, ...(page >= totalPages ? { opacity: 0.4, cursor: 'default' } : {}) }}
            className="woo-page-btn"
            disabled={page >= totalPages}
            onClick={() => setPage(p => p + 1)}
          >
            Next
          </button>
        </div>
      )}

      {/* Modal */}
      {modalMode && (
        <div style={s.overlay} onClick={() => !saving && setModalMode(null)}>
          <div style={s.modal} onClick={e => e.stopPropagation()}>
            <div style={s.modalHeader}>
              <h3 style={{ margin: 0, fontSize: 17, fontWeight: 600 }}>
                {modalMode === 'create' ? 'Add Product' : 'Edit Product'}
              </h3>
              <button style={s.closeBtn} onClick={() => setModalMode(null)}>&times;</button>
            </div>

            <div style={s.modalBody}>
              {/* Name */}
              <div style={s.field}>
                <label style={s.label}>Product Name *</label>
                <input style={s.input} value={form.name} onChange={e => updateField('name', e.target.value)} placeholder="e.g. Classic T-Shirt" />
              </div>

              {/* Price row */}
              <div style={s.row}>
                <div style={{ ...s.field, flex: 1 }}>
                  <label style={s.label}>Regular Price *</label>
                  <input style={s.input} value={form.regular_price} onChange={e => updateField('regular_price', e.target.value)} placeholder="29.99" />
                </div>
                <div style={{ ...s.field, flex: 1 }}>
                  <label style={s.label}>Sale Price</label>
                  <input style={s.input} value={form.sale_price} onChange={e => updateField('sale_price', e.target.value)} placeholder="19.99" />
                </div>
              </div>

              {/* SKU + Stock */}
              <div style={s.row}>
                <div style={{ ...s.field, flex: 1 }}>
                  <label style={s.label}>SKU</label>
                  <input style={s.input} value={form.sku} onChange={e => updateField('sku', e.target.value)} placeholder="SKU-001" />
                </div>
                <div style={{ ...s.field, flex: 1 }}>
                  <label style={s.label}>Stock Status</label>
                  <select style={s.input} value={form.stock_status} onChange={e => updateField('stock_status', e.target.value)}>
                    <option value="instock">In Stock</option>
                    <option value="outofstock">Out of Stock</option>
                    <option value="onbackorder">On Backorder</option>
                  </select>
                </div>
              </div>

              {/* Status + Stock Qty */}
              <div style={s.row}>
                <div style={{ ...s.field, flex: 1 }}>
                  <label style={s.label}>Status</label>
                  <select style={s.input} value={form.status} onChange={e => updateField('status', e.target.value)}>
                    <option value="publish">Published</option>
                    <option value="draft">Draft</option>
                    <option value="pending">Pending</option>
                    <option value="private">Private</option>
                  </select>
                </div>
                <div style={{ ...s.field, flex: 1 }}>
                  <label style={s.label}>Stock Quantity</label>
                  <input style={s.input} type="number" value={form.stock_quantity} onChange={e => updateField('stock_quantity', e.target.value)} placeholder="100" />
                </div>
              </div>

              {/* Image Upload */}
              <div style={s.field}>
                <label style={s.label}>Product Image</label>
                {form.image_preview ? (
                  <div style={s.imgPreviewWrap}>
                    <img src={form.image_preview} alt="Preview" style={s.imgPreview} />
                    <button
                      type="button"
                      style={s.imgRemoveBtn}
                      onClick={() => { updateField('image_preview', ''); updateField('image_file', null); updateField('image_url', ''); }}
                    >
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                  </div>
                ) : (
                  <label
                    style={s.dropZone}
                    className="woo-drop-zone"
                    onDragOver={e => { e.preventDefault(); e.currentTarget.style.borderColor = '#111827'; }}
                    onDragLeave={e => { e.currentTarget.style.borderColor = '#D1D5DB'; }}
                    onDrop={e => {
                      e.preventDefault();
                      e.currentTarget.style.borderColor = '#D1D5DB';
                      const file = e.dataTransfer.files[0];
                      if (file?.type.startsWith('image/')) {
                        setForm(prev => ({ ...prev, image_file: file, image_preview: URL.createObjectURL(file), image_url: '' }));
                      }
                    }}
                  >
                    <input
                      type="file"
                      accept="image/*"
                      style={{ display: 'none' }}
                      onChange={e => {
                        const file = e.target.files?.[0];
                        if (file) {
                          setForm(prev => ({ ...prev, image_file: file, image_preview: URL.createObjectURL(file), image_url: '' }));
                        }
                      }}
                    />
                    <svg width="32" height="32" fill="none" stroke="#9CA3AF" viewBox="0 0 24 24">
                      <path strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span style={{ fontSize: 13, color: '#6B7280', marginTop: 6 }}>Click to upload or drag & drop</span>
                    <span style={{ fontSize: 11, color: '#9CA3AF' }}>PNG, JPG, WEBP up to 5MB</span>
                  </label>
                )}
                {/* Or paste URL */}
                {!form.image_preview && (
                  <div style={{ marginTop: 8 }}>
                    <input
                      style={s.input}
                      value={form.image_url}
                      onChange={e => updateField('image_url', e.target.value)}
                      placeholder="Or paste image URL..."
                    />
                  </div>
                )}
              </div>

              {/* Short Description */}
              <div style={s.field}>
                <label style={s.label}>Short Description</label>
                <textarea style={{ ...s.input, minHeight: 60 }} value={form.short_description} onChange={e => updateField('short_description', e.target.value)} placeholder="Brief product summary..." />
              </div>

              {/* Description */}
              <div style={s.field}>
                <label style={s.label}>Description</label>
                <textarea style={{ ...s.input, minHeight: 90 }} value={form.description} onChange={e => updateField('description', e.target.value)} placeholder="Full product description..." />
              </div>

              {/* Categories */}
              {categories.length > 0 && (
                <div style={s.field}>
                  <label style={s.label}>Categories</label>
                  <div style={{ display: 'flex', flexWrap: 'wrap', gap: 6 }}>
                    {categories.filter(c => c.slug !== 'uncategorized').map(cat => (
                      <button
                        key={cat.id}
                        type="button"
                        style={{
                          ...s.catChip,
                          ...(form.category_ids.includes(cat.id)
                            ? { background: '#111827', color: '#fff', borderColor: '#111827' }
                            : {}),
                        }}
                        onClick={() => toggleCategory(cat.id)}
                      >
                        {cat.name}
                      </button>
                    ))}
                  </div>
                </div>
              )}
            </div>

            <div style={s.modalFooter}>
              <button style={s.cancelBtn} className="woo-cancel-btn" onClick={() => setModalMode(null)} disabled={saving}>
                Cancel
              </button>
              <button style={s.saveBtn} className="woo-save-btn" onClick={handleSave} disabled={saving || !form.name || !form.regular_price}>
                {saving ? 'Saving...' : modalMode === 'create' ? 'Create Product' : 'Update Product'}
              </button>
            </div>
          </div>
        </div>
      )}

      <style>{cssStr}</style>
    </div>
  );
}

const cssStr = `
  .woo-add-btn:hover { background: #1F2937 !important; }
  .woo-tr:hover { background: #F9FAFB !important; }
  .woo-icon-btn:hover { background: #F3F4F6 !important; }
  .woo-icon-btn-del:hover { background: #FEE2E2 !important; }
  .woo-page-btn:hover:not(:disabled) { background: #F3F4F6 !important; }
  .woo-cancel-btn:hover { background: #F3F4F6 !important; }
  .woo-save-btn:hover:not(:disabled) { background: #1F2937 !important; }
  @keyframes woo-spin { to { transform: rotate(360deg); } }
`;

const s: Record<string, React.CSSProperties> = {
  page: { padding: '24px 28px', maxWidth: 1100 },
  header: { display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 20, flexWrap: 'wrap', gap: 12 },
  title: { fontSize: 20, fontWeight: 700, color: '#111827', margin: 0 },
  subtitle: { fontSize: 13, color: '#6B7280', marginTop: 2 },
  addBtn: {
    display: 'flex', alignItems: 'center', gap: 6,
    padding: '9px 18px', background: '#111827', color: '#fff',
    border: 0, borderRadius: 10, fontSize: 13, fontWeight: 600, cursor: 'pointer',
  },
  toolbar: { marginBottom: 16 },
  searchWrap: {
    display: 'flex', alignItems: 'center', gap: 8,
    padding: '9px 14px', border: '1px solid #E5E7EB', borderRadius: 10,
    background: '#fff', maxWidth: 320,
  },
  searchInput: { border: 0, outline: 'none', fontSize: 13, flex: 1, background: 'transparent' },
  error: { padding: '10px 14px', background: '#FEE2E2', color: '#991B1B', borderRadius: 10, fontSize: 13, marginBottom: 14 },
  tableWrap: { background: '#fff', border: '1px solid #E5E7EB', borderRadius: 14, overflow: 'hidden' },
  table: { width: '100%', borderCollapse: 'collapse' },
  th: { padding: '12px 14px', fontSize: 11, fontWeight: 600, color: '#6B7280', textTransform: 'uppercase' as const, borderBottom: '1px solid #E5E7EB', textAlign: 'center' as const, letterSpacing: 0.5 },
  tr: { borderBottom: '1px solid #F3F4F6', transition: 'background 0.15s' },
  td: { padding: '10px 14px', fontSize: 13, color: '#374151', textAlign: 'center' as const, verticalAlign: 'middle' as const },
  img: { width: 44, height: 44, objectFit: 'cover' as const, borderRadius: 8, border: '1px solid #E5E7EB' },
  noImg: { width: 44, height: 44, borderRadius: 8, background: '#F9FAFB', border: '1px solid #E5E7EB', display: 'flex', alignItems: 'center', justifyContent: 'center', margin: '0 auto' },
  badge: { display: 'inline-block', padding: '3px 10px', borderRadius: 20, fontSize: 11, fontWeight: 600 },
  iconBtn: { width: 32, height: 32, border: '1px solid #E5E7EB', borderRadius: 8, background: '#fff', cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#374151' },
  center: { display: 'flex', flexDirection: 'column' as const, alignItems: 'center', justifyContent: 'center', padding: 60 },
  spinner: { width: 28, height: 28, border: '3px solid #E5E7EB', borderTopColor: '#111827', borderRadius: '50%', animation: 'woo-spin 0.6s linear infinite' },
  pagination: { display: 'flex', justifyContent: 'center', alignItems: 'center', gap: 16, marginTop: 16, padding: '12px 0' },
  pageBtn: { padding: '7px 16px', border: '1px solid #E5E7EB', borderRadius: 8, background: '#fff', fontSize: 13, cursor: 'pointer', color: '#374151' },

  // Modal
  overlay: { position: 'fixed' as const, inset: 0, background: 'rgba(0,0,0,0.4)', display: 'flex', alignItems: 'center', justifyContent: 'center', zIndex: 9999, padding: 20 },
  modal: { background: '#fff', borderRadius: 16, width: '100%', maxWidth: 560, maxHeight: '90vh', display: 'flex', flexDirection: 'column' as const, boxShadow: '0 20px 60px rgba(0,0,0,0.2)' },
  modalHeader: { display: 'flex', justifyContent: 'space-between', alignItems: 'center', padding: '18px 24px', borderBottom: '1px solid #E5E7EB' },
  closeBtn: { width: 32, height: 32, border: 0, background: '#F3F4F6', borderRadius: 8, fontSize: 20, cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#6B7280' },
  modalBody: { padding: '20px 24px', overflowY: 'auto' as const, flex: 1 },
  modalFooter: { display: 'flex', justifyContent: 'flex-end', gap: 10, padding: '16px 24px', borderTop: '1px solid #E5E7EB' },
  field: { marginBottom: 14 },
  label: { display: 'block', fontSize: 12, fontWeight: 600, color: '#374151', marginBottom: 5 },
  input: { width: '100%', padding: '9px 12px', border: '1px solid #D1D5DB', borderRadius: 8, fontSize: 13, outline: 'none', boxSizing: 'border-box' as const, background: '#fff', fontFamily: 'inherit', resize: 'vertical' as const },
  row: { display: 'flex', gap: 12 },
  catChip: { padding: '5px 12px', border: '1px solid #D1D5DB', borderRadius: 20, fontSize: 12, cursor: 'pointer', background: '#fff', color: '#374151', fontWeight: 500 },
  cancelBtn: { padding: '9px 18px', border: '1px solid #E5E7EB', borderRadius: 10, background: '#fff', fontSize: 13, cursor: 'pointer', color: '#374151', fontWeight: 500 },
  saveBtn: { padding: '9px 22px', background: '#111827', color: '#fff', border: 0, borderRadius: 10, fontSize: 13, fontWeight: 600, cursor: 'pointer' },

  // Image upload
  dropZone: {
    display: 'flex', flexDirection: 'column' as const, alignItems: 'center', justifyContent: 'center',
    padding: '24px 16px', border: '2px dashed #D1D5DB', borderRadius: 12,
    cursor: 'pointer', transition: 'border-color 0.15s', background: '#FAFAFA',
  },
  imgPreviewWrap: {
    position: 'relative' as const, display: 'inline-block', borderRadius: 12, overflow: 'hidden',
    border: '1px solid #E5E7EB',
  },
  imgPreview: { width: '100%', maxHeight: 200, objectFit: 'cover' as const, display: 'block', borderRadius: 12 },
  imgRemoveBtn: {
    position: 'absolute' as const, top: 8, right: 8,
    width: 28, height: 28, borderRadius: '50%', background: 'rgba(0,0,0,0.6)',
    border: 0, cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center',
    color: '#fff',
  },
};
