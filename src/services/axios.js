import axios from 'axios';

// Use root-relative base URL so calls go to the same origin by default
axios.defaults.baseURL = '/';
axios.defaults.withCredentials = true; // important for Sanctum cookies

// Re-hydrate auth header on page refresh so protected API calls keep working
const storedToken = localStorage.getItem('cricket_token');
if (storedToken) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${storedToken}`;
}

export default axios;
