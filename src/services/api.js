import axios from './axios';

const API_PREFIX = '/api';

export const api = {
  login: (role) => axios.post(`${API_PREFIX}/login`, { role }).then(res => res.data),
  members: () => axios.get(`${API_PREFIX}/members`).then(res => res.data),
  bookings: () => axios.get(`${API_PREFIX}/bookings`).then(res => res.data),
  createBooking: (payload) => axios.post(`${API_PREFIX}/bookings`, payload).then(res => res.data),
  deleteBooking: (id) => axios.delete(`${API_PREFIX}/bookings/${id}`).then(res => res.data),
  campaigns: () => axios.get(`${API_PREFIX}/campaigns`).then(res => res.data),
  chat: (memberId) => axios.get(memberId ? `${API_PREFIX}/chat?memberId=${memberId}` : `${API_PREFIX}/chat`).then(res => res.data),
  audit: () => axios.get(`${API_PREFIX}/audit`).then(res => res.data),
};
