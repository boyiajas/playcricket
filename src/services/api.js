const API_BASE = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api';

const request = async (path, options) => {
  const response = await fetch(`${API_BASE}${path}`, {
    headers: { 'Content-Type': 'application/json' },
    ...options,
  });

  if (!response.ok) {
    throw new Error(`API ${response.status}`);
  }

  return await response.json();
};

export const api = {
  login: (role) => request('/login', { method: 'POST', body: JSON.stringify({ role }) }),
  members: () => request('/members'),
  bookings: () => request('/bookings'),
  createBooking: (payload) => request('/bookings', { method: 'POST', body: JSON.stringify(payload) }),
  campaigns: () => request('/campaigns'),
  chat: (memberId) => request(memberId ? `/chat?memberId=${memberId}` : '/chat'),
  audit: () => request('/audit'),
};
