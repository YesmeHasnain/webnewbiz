import api from './api';

export interface AppProject {
  id: number;
  name: string;
  slug: string;
  description: string | null;
  framework: string;
  status: string;
  app_icon: string | null;
  bundle_id: string | null;
  version: string;
  file_tree: any[];
  platforms: string[];
  created_at: string;
  updated_at: string;
}

export interface AppMessage {
  id: number;
  role: 'user' | 'assistant';
  content: string;
  files_changed: string[] | null;
  created_at: string;
}

export const appService = {
  list: () => api.get<AppProject[]>('/apps'),
  create: (data: { name: string; framework?: string; platforms?: string[] }) =>
    api.post<{ app: AppProject }>('/apps', data),
  get: (id: number) => api.get<AppProject>(`/apps/${id}`),
  delete: (id: number) => api.delete(`/apps/${id}`),
  readFile: (id: number, path: string) =>
    api.get<{ path: string; content: string }>(`/apps/${id}/files`, { params: { path } }),
  writeFile: (id: number, path: string, content: string) =>
    api.put<{ message: string; file_tree: any[] }>(`/apps/${id}/files`, { path, content }),
  chat: (id: number, message: string) =>
    api.post<{ success: boolean; status: string }>(`/apps/${id}/chat`, { message }),
  getStream: (id: number) =>
    api.get<{ status: string; text: string; files_changed: string[]; file_tree: any[] }>(`/apps/${id}/stream`),
  getMessages: (id: number) => api.get<AppMessage[]>(`/apps/${id}/messages`),
};
