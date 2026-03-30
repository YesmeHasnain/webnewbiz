import axios from 'axios';

// Dev token — auth bypassed for now
const DEV_TOKEN = '1|3tbABBBRkj5Z6PY1JU43vbwNyyPuovm7mTMtBFbTbd790932';

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