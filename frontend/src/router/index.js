import { createRouter, createWebHistory } from 'vue-router'
import HomePage from '@/views/HomePage.vue'
import DashboardView from '@/views/DashboardView.vue'
import ProjectsView from '@/views/ProjectsView.vue'
import ProjectDetailView from '@/views/ProjectDetailView.vue'
import NewProjectView from '@/views/NewProjectView.vue'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: HomePage
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: DashboardView
  },
  {
    path: '/projects',
    name: 'Projects',
    component: ProjectsView
  },
  {
    path: '/projects/new',
    name: 'NewProject',
    component: NewProjectView
  },
  {
    path: '/projects/:id',
    name: 'ProjectDetail',
    component: ProjectDetailView
  },
  {
    path: '/tasks',
    name: 'Tasks',
    redirect: '/dashboard'
  },
  {
    path: '/calendar',
    name: 'Calendar',
    redirect: '/dashboard'
  },
  {
    path: '/reports',
    name: 'Reports',
    redirect: '/dashboard'
  },
  {
    path: '/team',
    name: 'Team',
    redirect: '/dashboard'
  },
  {
    path: '/settings',
    name: 'Settings',
    redirect: '/dashboard'
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

export default router