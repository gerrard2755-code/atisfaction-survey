import { createRouter, createWebHistory } from 'vue-router'
import SurveyForm from '../views/SurveyForm.vue'
import Dashboard from '../views/Dashboard.vue'
import Login from '../views/Login.vue'

const routes = [
  {
    path: '/',
    redirect: '/survey'
  },
  {
    path: '/survey',
    component: SurveyForm,
    name: 'Survey'
  },
  {
    path: '/dashboard',
    component: Dashboard,
    name: 'Dashboard',
    meta: { requiresAuth: true }
  },
  {
    path: '/login',
    component: Login,
    name: 'Login'
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to, from, next) => {
  const isLoggedIn = localStorage.getItem('token')
  
  if (to.meta.requiresAuth && !isLoggedIn) {
    next('/login')
  } else {
    next()
  }
})

export default router
