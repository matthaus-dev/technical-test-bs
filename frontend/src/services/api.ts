import axios from 'axios'

const BASE = (import.meta as any).env?.VITE_API_BASE_URL || 'http://localhost:8000'

const api = axios.create({
  baseURL: BASE,
  headers: {
    'Content-Type': 'application/json',
  },
})

export default api
