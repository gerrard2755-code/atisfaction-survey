import { defineStore } from 'pinia'
import apiClient from '../api/client'

const API_URL = process.env.VITE_API_URL || 'http://localhost:8000/api'

export const useSurveyStore = defineStore('survey', {
  state: () => ({
    surveys: [],
    currentSurvey: null,
    loading: false,
    error: null
  }),
  
  getters: {
    isLoading: (state) => state.loading,
    hasError: (state) => !!state.error,
    errorMessage: (state) => state.error
  },
  
  actions: {
    async fetchSurveys() {
      this.loading = true
      this.error = null
      try {
        const response = await apiClient.get('/surveys')
        this.surveys = response.data.data || []
        return response.data
      } catch (error) {
        this.error = error.message
        throw error
      } finally {
        this.loading = false
      }
    },
    
    async fetchSurvey(id) {
      this.loading = true
      this.error = null
      try {
        const response = await apiClient.get(`/surveys/${id}`)
        this.currentSurvey = response.data.data || response.data
        return response.data
      } catch (error) {
        this.error = error.message
        throw error
      } finally {
        this.loading = false
      }
    },
    
    async submitResponse(data) {
      this.loading = true
      this.error = null
      try {
        const response = await apiClient.post('/responses', data)
        return response.data
      } catch (error) {
        this.error = error.message
        throw error
      } finally {
        this.loading = false
      }
    },
    
    clearError() {
      this.error = null
    }
  }
})
