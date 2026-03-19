import api from './api';
import type { WpPlugin, WpTheme, SiteOverview, UpdatesInfo, WpPage, Backup, CustomDomain, WooProduct, WooOrder, WooCategory } from '../models/types';

export const wpManagerService = {
  // Overview
  getOverview(websiteId: number) {
    return api.get<{ success: boolean; data: SiteOverview }>(`/websites/${websiteId}/overview`);
  },
  getUpdates(websiteId: number) {
    return api.get<{ success: boolean; data: UpdatesInfo }>(`/websites/${websiteId}/updates`);
  },
  clearCache(websiteId: number) {
    return api.post<{ success: boolean; cleared: string[] }>(`/websites/${websiteId}/cache/clear`);
  },
  getPages(websiteId: number) {
    return api.get<{ success: boolean; data: WpPage[] }>(`/websites/${websiteId}/wp-pages`);
  },
  getOptions(websiteId: number, keys: string[]) {
    return api.get<{ success: boolean; data: Record<string, string> }>(`/websites/${websiteId}/options`, { params: { keys } });
  },
  updateOptions(websiteId: number, options: Record<string, string>) {
    return api.put<{ success: boolean }>(`/websites/${websiteId}/options`, { options });
  },

  // Plugins
  listPlugins(websiteId: number) {
    return api.get<{ success: boolean; data: WpPlugin[] }>(`/websites/${websiteId}/plugins`);
  },
  activatePlugin(websiteId: number, plugin: string) {
    return api.post<{ success: boolean }>(`/websites/${websiteId}/plugins/activate`, { plugin });
  },
  deactivatePlugin(websiteId: number, plugin: string) {
    return api.post<{ success: boolean }>(`/websites/${websiteId}/plugins/deactivate`, { plugin });
  },
  installPlugin(websiteId: number, slug: string) {
    return api.post<{ success: boolean }>(`/websites/${websiteId}/plugins/install`, { slug });
  },
  deletePlugin(websiteId: number, plugin: string) {
    return api.delete<{ success: boolean }>(`/websites/${websiteId}/plugins`, { data: { plugin } });
  },
  updatePlugin(websiteId: number, plugin: string) {
    return api.post<{ success: boolean }>(`/websites/${websiteId}/plugins/update`, { plugin });
  },

  // Themes
  listThemes(websiteId: number) {
    return api.get<{ success: boolean; data: WpTheme[] }>(`/websites/${websiteId}/themes`);
  },
  activateTheme(websiteId: number, theme: string) {
    return api.post<{ success: boolean }>(`/websites/${websiteId}/themes/activate`, { theme });
  },
  installTheme(websiteId: number, slug: string) {
    return api.post<{ success: boolean }>(`/websites/${websiteId}/themes/install`, { slug });
  },
  deleteTheme(websiteId: number, theme: string) {
    return api.delete<{ success: boolean }>(`/websites/${websiteId}/themes`, { data: { theme } });
  },
  updateTheme(websiteId: number, theme: string) {
    return api.post<{ success: boolean }>(`/websites/${websiteId}/themes/update`, { theme });
  },

  // Backups
  listBackups(websiteId: number) {
    return api.get<Backup[]>(`/websites/${websiteId}/backups`);
  },
  createBackup(websiteId: number, notes?: string) {
    return api.post<Backup>(`/websites/${websiteId}/backups`, { notes });
  },
  downloadBackup(websiteId: number, backupId: number) {
    return api.get(`/websites/${websiteId}/backups/${backupId}/download`, { responseType: 'blob' });
  },
  restoreBackup(websiteId: number, backupId: number) {
    return api.post<{ success: boolean }>(`/websites/${websiteId}/backups/${backupId}/restore`);
  },
  deleteBackup(websiteId: number, backupId: number) {
    return api.delete<{ success: boolean }>(`/websites/${websiteId}/backups/${backupId}`);
  },

  // Domains
  listDomains(websiteId: number) {
    return api.get<CustomDomain[]>(`/websites/${websiteId}/domains`);
  },
  addDomain(websiteId: number, domain: string, type: string = 'primary') {
    return api.post<CustomDomain>(`/websites/${websiteId}/domains`, { domain, type });
  },
  deleteDomain(websiteId: number, domainId: number) {
    return api.delete<{ success: boolean }>(`/websites/${websiteId}/domains/${domainId}`);
  },

  // Branding / Logo
  getLogo(websiteId: number) {
    return api.get<{ success: boolean; data: { logo_url: string; logo_id: number; site_icon: string; site_name: string } }>(`/websites/${websiteId}/branding/logo`);
  },
  uploadLogo(websiteId: number, data: FormData) {
    return api.post<{ success: boolean; data: { logo_url: string; logo_id: number; message: string } }>(`/websites/${websiteId}/branding/logo`, data);
  },
  removeLogo(websiteId: number) {
    return api.delete<{ success: boolean; data: { message: string } }>(`/websites/${websiteId}/branding/logo`);
  },
  generateLogo(websiteId: number, params: { business_name: string; style?: string; colors?: string }) {
    return api.post<{ success: boolean; data: { logo_url: string; logo_id: number; svg_preview: string; message: string } }>(`/websites/${websiteId}/branding/logo/generate`, params);
  },

  // WooCommerce - Products
  listProducts(websiteId: number, params?: { page?: number; per_page?: number; search?: string; status?: string; category?: number }) {
    return api.get<{ success: boolean; data: WooProduct[]; total: number; pages: number }>(`/websites/${websiteId}/woo/products`, { params });
  },
  getProduct(websiteId: number, productId: number) {
    return api.get<{ success: boolean; data: WooProduct }>(`/websites/${websiteId}/woo/products/${productId}`);
  },
  createProduct(websiteId: number, data: FormData | (Partial<WooProduct> & { image_url?: string; category_ids?: string })) {
    return api.post<{ success: boolean; data: WooProduct }>(`/websites/${websiteId}/woo/products`, data);
  },
  updateProduct(websiteId: number, productId: number, data: FormData | (Partial<WooProduct> & { image_url?: string; category_ids?: string })) {
    if (data instanceof FormData) {
      data.append('_method', 'PUT');
      return api.post<{ success: boolean; data: WooProduct }>(`/websites/${websiteId}/woo/products/${productId}`, data);
    }
    return api.put<{ success: boolean; data: WooProduct }>(`/websites/${websiteId}/woo/products/${productId}`, data);
  },
  deleteProduct(websiteId: number, productId: number, force = false) {
    return api.delete<{ success: boolean }>(`/websites/${websiteId}/woo/products/${productId}`, { params: { force } });
  },

  // WooCommerce - Orders
  listOrders(websiteId: number, params?: { page?: number; per_page?: number; status?: string }) {
    return api.get<{ success: boolean; data: WooOrder[]; total: number; pages: number }>(`/websites/${websiteId}/woo/orders`, { params });
  },

  // WooCommerce - Categories
  listCategories(websiteId: number) {
    return api.get<{ success: boolean; data: WooCategory[] }>(`/websites/${websiteId}/woo/categories`);
  },
};
