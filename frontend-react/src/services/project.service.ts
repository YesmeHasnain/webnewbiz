import api from './api';
import type { Project, ProjectMessage, FileTreeNode } from '../models/types';

export const projectService = {
  // List all projects
  list: () => api.get<Project[]>('/projects'),

  // Create new project (instant, no AI)
  create: (data: { name: string; framework?: string }) =>
    api.post<{ project: Project }>('/projects', data),

  // Get project details
  get: (id: number) => api.get<Project>(`/projects/${id}`),

  // Delete project
  delete: (id: number) => api.delete(`/projects/${id}`),

  // Read file content
  readFile: (id: number, path: string) =>
    api.get<{ path: string; content: string }>(`/projects/${id}/files`, { params: { path } }),

  // Write/save file
  writeFile: (id: number, path: string, content: string) =>
    api.put<{ message: string; file_tree: FileTreeNode[] }>(`/projects/${id}/files`, { path, content }),

  // Delete file
  deleteFile: (id: number, path: string) =>
    api.delete<{ message: string; file_tree: FileTreeNode[] }>(`/projects/${id}/files`, { params: { path } }),

  // Create new file or folder
  createFile: (id: number, path: string, type: 'file' | 'directory') =>
    api.post<{ message: string; file_tree: FileTreeNode[] }>(`/projects/${id}/files/create`, { path, type }),

  // Rename/move file
  renameFile: (id: number, from: string, to: string) =>
    api.post<{ message: string; file_tree: FileTreeNode[] }>(`/projects/${id}/files/rename`, { from, to }),

  // Send AI chat message — returns immediately (background generation)
  chat: (id: number, message: string) =>
    api.post<{ success: boolean; status: string; message: string }>(`/projects/${id}/chat`, { message }),

  // Poll stream — get current AI generation status
  getStream: (id: number) =>
    api.get<{
      status: 'generating' | 'done';
      text: string;
      files_changed: string[];
      file_tree: FileTreeNode[];
    }>(`/projects/${id}/stream`),

  // Get chat history
  getMessages: (id: number) => api.get<ProjectMessage[]>(`/projects/${id}/messages`),

  // Execute terminal command
  terminal: (id: number, command: string) =>
    api.post<{ output: string; exit_code: number }>(`/projects/${id}/terminal`, { command }),

  // Git operations
  git: (id: number, action: string, data?: { message?: string; files?: string[]; branch?: string }) =>
    api.post<{ action: string; output: string; exit_code: number }>(`/projects/${id}/git`, { action, ...data }),

  // Search across files
  search: (id: number, query: string) =>
    api.get<{ results: Array<{ file: string; line: number; text: string }> }>(`/projects/${id}/search`, { params: { query } }),

  // Get preview URL
  getPreviewUrl: (id: number) => `/api/projects/${id}/preview/`,
};
