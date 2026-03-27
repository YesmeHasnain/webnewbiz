import api from './api';

export const crmService = {
  // Contacts
  getContacts: (params?: Record<string, string>) => api.get('/crm/contacts', { params }),
  createContact: (data: Record<string, unknown>) => api.post('/crm/contacts', data),
  getContact: (id: number) => api.get(`/crm/contacts/${id}`),
  updateContact: (id: number, data: Record<string, unknown>) => api.put(`/crm/contacts/${id}`, data),
  deleteContact: (id: number) => api.delete(`/crm/contacts/${id}`),

  // Pipelines & Deals
  getPipelines: () => api.get('/crm/pipelines'),
  createPipeline: (data: { name: string; stages: string[] }) => api.post('/crm/pipelines', data),
  getDeals: (params?: Record<string, string>) => api.get('/crm/deals', { params }),
  createDeal: (data: Record<string, unknown>) => api.post('/crm/deals', data),
  updateDeal: (id: number, data: Record<string, unknown>) => api.put(`/crm/deals/${id}`, data),
  updateDealStage: (id: number, stage: string) => api.put(`/crm/deals/${id}/stage`, { stage }),

  // Campaigns
  getCampaigns: () => api.get('/crm/campaigns'),
  createCampaign: (data: Record<string, unknown>) => api.post('/crm/campaigns', data),
  sendCampaign: (id: number) => api.post(`/crm/campaigns/${id}/send`),
  getCampaignStats: (id: number) => api.get(`/crm/campaigns/${id}/stats`),

  // Sequences
  getSequences: () => api.get('/crm/sequences'),
  createSequence: (data: Record<string, unknown>) => api.post('/crm/sequences', data),
  updateSequence: (id: number, data: Record<string, unknown>) => api.put(`/crm/sequences/${id}`, data),

  // Workflows
  getWorkflows: () => api.get('/crm/workflows'),
  createWorkflow: (data: Record<string, unknown>) => api.post('/crm/workflows', data),
  updateWorkflow: (id: number, data: Record<string, unknown>) => api.put(`/crm/workflows/${id}`, data),
  activateWorkflow: (id: number) => api.post(`/crm/workflows/${id}/activate`),

  // Calendar & Bookings
  getCalendars: () => api.get('/crm/calendars'),
  createCalendar: (data: Record<string, unknown>) => api.post('/crm/calendars', data),
  getBookings: () => api.get('/crm/bookings'),
  createBooking: (data: Record<string, unknown>) => api.post('/crm/bookings', data),

  // Invoices
  getInvoices: () => api.get('/crm/invoices'),
  createInvoice: (data: Record<string, unknown>) => api.post('/crm/invoices', data),
  getInvoice: (id: number) => api.get(`/crm/invoices/${id}`),
  markInvoicePaid: (id: number) => api.post(`/crm/invoices/${id}/paid`),

  // Conversations
  getConversations: () => api.get('/crm/conversations'),
  getMessages: (id: number) => api.get(`/crm/conversations/${id}/messages`),
  sendMessage: (id: number, content: string) => api.post(`/crm/conversations/${id}/messages`, { content }),
};
