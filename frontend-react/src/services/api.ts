import axios from 'axios';

// Dev token — auth bypassed for now
const DEV_TOKEN = '26|lAZAcGDhH94sWb4bS26L0k52zgUZU8h0LhlDIJZXaa28b004';

const api = axios.create({
  baseURL: '/api',
  headers: { Accept: 'application/json' },
});

api.interceptors.request.use((config) => {
  const token = localStorage.getItem('auth_token') || DEV_TOKEN;
  config.headers.Authorization = `Bearer ${token}`;
  return config;
});

export default api;