import { defineStore } from 'pinia'
import axios from 'axios'

const API_URL = 'http://localhost:8000/api'

export const useSurveyStore = defineStore('survey', {
  state: () => ({
    surveys: [],
    currentSurvey: null,
    loading: false
  }),
  
  actions: {
    async fetchSurveys() {
      this.loading = true
      try {
        const response = await axios.get(`${API_URL}/surveys`)
        this.surveys = response.data
      } catch (error) {
        console.error('Error fetching surveys:', error)
      } finally {
        this.loading = false
      }
    },
    
    async fetchSurvey(id) {
      this.loading = true
      try {
        const response = await axios.get(`${API_URL}/surveys/${id}`)
        this.currentSurvey = response.data
      } catch (error) {
        console.error('Error fetching survey:', error)
      } finally {
        this.loading = false
      }
    },
    
    async submitResponse(data) {
      try {
        const response = await axios.post(`${API_URL}/responses`, data)
        return response.data
      } catch (error) {
        throw error.response?.data || error
      }
    }
  }
})
