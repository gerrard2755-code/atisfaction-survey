<template>
  <div class="dashboard">
    <h1 class="mb-4">📊 Dashboard</h1>
    
    <div class="row mb-4">
      <div class="col-md-3">
        <div class="card text-center">
          <div class="card-body">
            <h5 class="card-title">Total Responses</h5>
            <h2>{{ stats.total_responses || 0 }}</h2>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-center">
          <div class="card-body">
            <h5 class="card-title">Average Score</h5>
            <h2>{{ stats.avg_score || 0 }}/5</h2>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">Quick Actions</div>
          <div class="card-body">
            <button @click="exportPDF" class="btn btn-primary me-2">📄 Export PDF</button>
            <button @click="exportExcel" class="btn btn-success me-2">📊 Export Excel</button>
            <button @click="printReport" class="btn btn-secondary">🖨️ Print</button>
          </div>
        </div>
      </div>
    </div>
    
    <div class="row">
      <div class="col-lg-6">
        <div class="card mb-4">
          <div class="card-header">Category Scores</div>
          <div class="card-body">
            <div id="categoryChart"></div>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="card mb-4">
          <div class="card-header">Daily Responses</div>
          <div class="card-body">
            <div id="dailyChart"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

const API_URL = 'http://localhost:8000/api'

export default {
  name: 'Dashboard',
  data() {
    return {
      stats: {}
    }
  },
  mounted() {
    this.loadDashboardData()
  },
  methods: {
    async loadDashboardData() {
      try {
        const response = await axios.get(`${API_URL}/admin/dashboard`)
        this.stats = response.data
      } catch (error) {
        console.error('Error loading dashboard:', error)
      }
    },
    exportPDF() {
      alert('PDF export coming soon!')
    },
    exportExcel() {
      alert('Excel export coming soon!')
    },
    printReport() {
      window.print()
    }
  }
}
</script>

<style scoped>
.dashboard {
  max-width: 1200px;
  margin: 0 auto;
}

.card {
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style>
