<template>
  <div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <router-link to="/" class="navbar-brand">📊 Satisfaction Survey</router-link>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <router-link to="/survey" class="nav-link">Survey</router-link>
            </li>
            <li class="nav-item" v-if="isLoggedIn">
              <router-link to="/dashboard" class="nav-link">Dashboard</router-link>
            </li>
            <li class="nav-item" v-if="isLoggedIn">
              <a href="#" @click.prevent="logout" class="nav-link">Logout</a>
            </li>
            <li class="nav-item" v-else>
              <router-link to="/login" class="nav-link">Login</router-link>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <main class="container my-4">
      <router-view />
    </main>
  </div>
</template>

<script>
import { useAuthStore } from './stores/auth'

export default {
  name: 'App',
  setup() {
    const authStore = useAuthStore()
    
    return {
      isLoggedIn: () => authStore.isLoggedIn,
      logout: () => authStore.logout()
    }
  }
}
</script>

<style scoped>
main {
  min-height: calc(100vh - 56px);
}
</style>
