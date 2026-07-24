<template>
  <div class="login-container">
    <div class="card">
      <div class="card-header text-center">
        <h3>🔐 Admin Login</h3>
      </div>
      <div class="card-body">
        <form @submit.prevent="handleLogin">
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input 
              v-model="email" 
              type="email" 
              class="form-control" 
              required
            >
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input 
              v-model="password" 
              type="password" 
              class="form-control" 
              required
            >
          </div>
          <button type="submit" class="btn btn-primary w-100">Login</button>
          <div v-if="error" class="alert alert-danger mt-3">{{ error }}</div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { useAuthStore } from '../stores/auth'

export default {
  name: 'Login',
  data() {
    return {
      email: '',
      password: '',
      error: ''
    }
  },
  setup() {
    const authStore = useAuthStore()
    return { authStore }
  },
  methods: {
    async handleLogin() {
      this.error = ''
      try {
        await this.authStore.login(this.email, this.password)
        this.$router.push('/dashboard')
      } catch (error) {
        this.error = error.message || 'Login failed'
      }
    }
  }
}
</script>

<style scoped>
.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
}

.card {
  width: 100%;
  max-width: 400px;
  box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}
</style>
