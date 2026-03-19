import api from './api';

const base = (websiteId: number | string) => `/websites/${websiteId}/wnb`;

export const builderPluginService = {
  // Dashboard
  getDashboard: (id: number | string) => api.get(`${base(id)}/dashboard`),

  // Analytics
  getAnalytics: (id: number | string, period = '7days') => api.get(`${base(id)}/analytics`, { params: { period } }),

  // Performance
  getPerformance: (id: number | string) => api.get(`${base(id)}/performance`),
  savePerformance: (id: number | string, settings: Record<string, boolean>) => api.post(`${base(id)}/performance`, settings),

  // Cache
  getCacheStats: (id: number | string) => api.get(`${base(id)}/cache`),
  purgeCache: (id: number | string, type: string) => api.post(`${base(id)}/cache/purge`, { type }),
  saveCacheSettings: (id: number | string, settings: Record<string, boolean>) => api.post(`${base(id)}/cache/settings`, settings),

  // Security
  getSecurity: (id: number | string) => api.get(`${base(id)}/security`),
  saveSecurity: (id: number | string, settings: Record<string, boolean>) => api.post(`${base(id)}/security`, settings),

  // Backups
  getBackups: (id: number | string) => api.get(`${base(id)}/backups`),
  createBackup: (id: number | string, type: string) => api.post(`${base(id)}/backups`, { type }),
  deleteBackup: (id: number | string, backupId: string) => api.delete(`${base(id)}/backups/${backupId}`),
  restoreBackup: (id: number | string, backupId: string) => api.post(`${base(id)}/backups/${backupId}/restore`),

  // Database
  getDatabaseStats: (id: number | string) => api.get(`${base(id)}/database`),
  databaseCleanup: (id: number | string, type: string) => api.post(`${base(id)}/database/cleanup`, { type }),
  databaseOptimize: (id: number | string) => api.post(`${base(id)}/database/optimize`),

  // Maintenance
  getMaintenance: (id: number | string) => api.get(`${base(id)}/maintenance`),
  toggleMaintenance: (id: number | string, enabled: boolean) => api.post(`${base(id)}/maintenance/toggle`, { enabled }),
  saveMaintenanceSettings: (id: number | string, settings: any) => api.post(`${base(id)}/maintenance/settings`, settings),

  // Images
  getImageStats: (id: number | string) => api.get(`${base(id)}/images`),
  optimizeImages: (id: number | string, limit = 10) => api.post(`${base(id)}/images/optimize`, { limit }),
  saveImageSettings: (id: number | string, settings: any) => api.post(`${base(id)}/images/settings`, settings),

  // SEO
  getSeo: (id: number | string) => api.get(`${base(id)}/seo`),
  saveSeo: (id: number | string, settings: any) => api.post(`${base(id)}/seo`, settings),
  addRedirect: (id: number | string, from: string, to: string) => api.post(`${base(id)}/seo/redirects`, { from, to }),
  deleteRedirect: (id: number | string, from: string) => api.delete(`${base(id)}/seo/redirects`, { data: { from } }),
  generateSitemap: (id: number | string) => api.post(`${base(id)}/seo/sitemap`),
  saveRobots: (id: number | string, content: string) => api.post(`${base(id)}/seo/robots`, { content }),

  // AI
  aiGenerate: (id: number | string, params: { type: string; prompt: string; tone?: string; length?: string }) => api.post(`${base(id)}/ai/generate`, params),
  getAiHistory: (id: number | string) => api.get(`${base(id)}/ai/history`),
  clearAiHistory: (id: number | string) => api.post(`${base(id)}/ai/history/clear`, { action: 'clear' }),
};
