import api from './api';
import type { Website, GenerateParams, StatusResponse } from '../models/types';

export const websiteService = {
  list() {
    return api.get<Website[]>('/websites');
  },

  get(id: number) {
    return api.get<Website>(`/websites/${id}`);
  },

  generate(params: GenerateParams) {
    return api.post<Website>('/websites/generate', params);
  },

  getStatus(id: number) {
    return api.get<StatusResponse>(`/websites/${id}/status`);
  },

  rebuild(id: number, params: { prompt: string; layout: string; pages?: string[]; business_name?: string; business_type?: string }) {
    return api.post<Website>(`/websites/${id}/rebuild`, params);
  },

  delete(id: number) {
    return api.delete(`/websites/${id}`);
  },
};
