import axios from 'axios';

/**
 * Cliente HTTP central. La URL base se toma de la variable de entorno
 * VITE_API_URL para mantener el frontend desacoplado del backend.
 */
const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8000/api/v1',
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json',
  },
});

export const CatalogService = {
  get: () => api.get('/catalogs').then((r) => r.data.data),
};

export const HotelService = {
  list: () => api.get('/hotels').then((r) => r.data.data),
  get: (id) => api.get(`/hotels/${id}`).then((r) => r.data.data),
  create: (payload) => api.post('/hotels', payload).then((r) => r.data.data),
  update: (id, payload) => api.put(`/hotels/${id}`, payload).then((r) => r.data.data),
  remove: (id) => api.delete(`/hotels/${id}`),
  assignRoom: (hotelId, payload) =>
    api.post(`/hotels/${hotelId}/rooms`, payload).then((r) => r.data.data),
};

export default api;
