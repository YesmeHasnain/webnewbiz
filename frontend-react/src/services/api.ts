import axios from 'axios';

// Dev token — auth bypassed for now
const DEV_TOKEN = '2|zDWCzTpd0dJLQjm9ZPW5qEfS8agHBny9OyKiklCd32990a83';

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