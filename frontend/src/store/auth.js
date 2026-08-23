import { defineStore } from 'pinia'
import axios from 'axios'

// Configure global axios defaults
axios.defaults.baseURL = 'http://95.130.227.30/api'
axios.defaults.headers.common['Accept'] = 'application/json'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null,
  }),
  getters: {
    isAuthenticated: (state) => !!state.token,
  },
  actions: {
    async login(credentials) {
      try {
        const response = await axios.post('/login', credentials)
        this.token = response.data.access_token
        this.user = response.data.user
        localStorage.setItem('token', this.token)
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
        return true
      } catch (error) {
        throw error.response?.data?.message || 'Login failed'
      }
    },
    logout() {
      this.token = null
      this.user = null
      localStorage.removeItem('token')
      delete axios.defaults.headers.common['Authorization']
    },
    async fetchUser() {
      if (!this.token) return
      
      axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
      try {
        const response = await axios.get('/me')
        this.user = response.data
      } catch (error) {
        this.logout()
      }
    }
  }
})
