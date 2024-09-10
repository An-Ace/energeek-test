// src/helpers/axios.js
import axios from 'axios';
import { useRouter } from 'vue-router';
import { toast } from 'vue3-toastify'; // Adjust if using Vue-specific Toast library

// Create an Axios instance
const api = axios.create({
  baseURL: 'http://localhost:8000', // Replace with your API base URL
  timeout: 10000,
});

// Interceptors for requests and responses
// api.interceptors.request.use(
//   (config) => {
//     // You can add headers or modify the request here
//     config.headers['Authorization'] = 'Bearer YOUR_TOKEN'; // Example token
//     return config;
//   },
//   (error) => {
//     toast.error('Request error: ' + error.message);
//     return Promise.reject(error);
//   }
// );
api.interceptors.response.use(
  (response) => {
    // Handle success
    response.data.message && toast.success(response.data.message);
    response.data.user && localStorage.setItem('user-energeek', JSON.stringify(response.data.user));
    // if (response.data.redirect) {
    //   window.location = (error.response?.data?.redirect)
    // }
    return response.data.data;
  },
  (error) => {
    // Handle error
    const errorMessage = error.response?.data?.message || error.message;
    toast.error('Error: ' + errorMessage);
    error.response?.data?.user && localStorage.setItem('user-energeek', JSON.stringify(error.response?.data?.user));
    // if (error.response?.data?.redirect) {
    //   window.location = (error.response?.data?.redirect)
    // }
    return Promise.reject(error);
  }
);

// Create a wrapper for GET, POST, PUT, DELETE methods
const Api = {
  get: (url, params) => api.get(url, { params }),
  post: (url, data) => api.post(url, data),
  put: (url, data) => api.put(url, data),
  delete: (url) => api.delete(url),
};

export default Api;
