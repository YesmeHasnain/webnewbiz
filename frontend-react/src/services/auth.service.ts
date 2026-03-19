import api from './api';
import type { User, AuthResponse } from '../models/types';

export const authService = {
  login(email: string, password: string) {
    return api.post<AuthResponse>('/auth/login', { email, password });
  },

  register(name: string, email: string, password: string) {
    return api.post<AuthResponse>('/auth/register', { name, email, password });
  },

  logout() {
    return api.post('/auth/logout');
  },

  me() {
    return api.get<User>('/auth/me');
  },
};
