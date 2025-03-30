<template>
  <div class="flex min-h-screen flex-col">
    <div class="flex flex-col md:flex-row">
      <SideNav />
      <div class="flex-1 md:ml-64">
        <TopNav />
        <main class="grid flex-1 items-start gap-4 p-4 md:gap-8 md:p-6">
          <div class="flex items-center gap-4">
            <h1 class="flex-1 text-2xl font-semibold">Dashboard</h1>
          </div>
          <div class="space-y-4">
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
              <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                  <h3 class="text-sm font-medium">Total Projects</h3>
                  <FolderKanban class="h-4 w-4 text-muted-foreground" />
                </div>
                <div class="p-6 pt-0">
                  <div class="text-2xl font-bold">{{ dashboardStats.totalProjects }}</div>
                  <p class="text-xs text-muted-foreground">+2 from last month</p>
                </div>
              </div>
              <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                  <h3 class="text-sm font-medium">Active Tasks</h3>
                  <ListTodo class="h-4 w-4 text-muted-foreground" />
                </div>
                <div class="p-6 pt-0">
                  <div class="text-2xl font-bold">{{ dashboardStats.activeTasks }}</div>
                  <p class="text-xs text-muted-foreground">+24 from last week</p>
                </div>
              </div>
              <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                  <h3 class="text-sm font-medium">Budget Utilization</h3>
                  <BarChart class="h-4 w-4 text-muted-foreground" />
                </div>
                <div class="p-6 pt-0">
                  <div class="text-2xl font-bold">{{ dashboardStats.budgetUtilization }}%</div>
                  <p class="text-xs text-muted-foreground">+4% from last month</p>
                </div>
              </div>
              <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                  <h3 class="text-sm font-medium">Time to Completion</h3>
                  <Clock class="h-4 w-4 text-muted-foreground" />
                </div>
                <div class="p-6 pt-0">
                  <div class="text-2xl font-bold">{{ dashboardStats.timeToCompletion }} days</div>
                  <p class="text-xs text-muted-foreground">-3 days from estimate</p>
                </div>
              </div>
            </div>
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-7">
              <div class="col-span-4 rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                  <h3 class="text-2xl font-semibold leading-none tracking-tight">Projects Overview</h3>
                </div>
                <div class="p-6 pt-0 pl-2">
                  <ProjectsOverview />
                </div>
              </div>
              <div class="col-span-3 rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                  <h3 class="text-2xl font-semibold leading-none tracking-tight">Recent Activity</h3>
                  <p class="text-sm text-muted-foreground">
                    Latest updates across your projects
                  </p>
                </div>
                <div class="p-6 pt-0">
                  <RecentActivity :activities="recentActivity" />
                </div>
              </div>
            </div>
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-7">
              <div class="col-span-3 rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                  <h3 class="text-2xl font-semibold leading-none tracking-tight">Project Metrics</h3>
                  <p class="text-sm text-muted-foreground">
                    Performance metrics for active projects
                  </p>
                </div>
                <div class="p-6 pt-0">
                  <ProjectMetrics :projects="projectMetrics" />
                </div>
              </div>
              <div class="col-span-4 rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                  <h3 class="text-2xl font-semibold leading-none tracking-tight">Upcoming Milestones</h3>
                  <p class="text-sm text-muted-foreground">
                    Critical milestones in the next 30 days
                  </p>
                </div>
                <div class="p-6 pt-0">
                  <div class="space-y-4">
                    <div v-for="(milestone, i) in upcomingMilestones" :key="i" class="flex items-center justify-between border-b pb-4 last:border-0 last:pb-0">
                      <div class="space-y-1">
                        <p class="text-sm font-medium leading-none">{{ milestone.milestone }}</p>
                        <p class="text-sm text-muted-foreground">{{ milestone.project }}</p>
                      </div>
                      <div class="flex items-center gap-4">
                        <div class="text-sm text-muted-foreground">{{ milestone.date }}</div>
                        <div :class="`text-xs px-2 py-1 rounded-full ${
                          milestone.status === 'On Track' 
                            ? 'bg-green-100 text-green-800' 
                            : milestone.status === 'At Risk' 
                              ? 'bg-yellow-100 text-yellow-800' 
                              : 'bg-red-100 text-red-800'
                        }`">
                          {{ milestone.status }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="flex items-center p-6 pt-0">
                  <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2 w-full">
                    View All Milestones
                  </button>
                </div>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { FolderKanban, ListTodo, BarChart, Clock } from 'lucide-vue-next'
import SideNav from '@/components/SideNav.vue'
import TopNav from '@/components/TopNav.vue'
import ProjectsOverview from '@/components/ProjectsOverview.vue'
import RecentActivity from '@/components/RecentActivity.vue'
import ProjectMetrics from '@/components/ProjectMetrics.vue'
import axios from 'axios'

const dashboardStats = ref({
  totalProjects: 12,
  activeTasks: 86,
  budgetUtilization: 68,
  timeToCompletion: 24
})

const recentActivity = ref([])
const projectMetrics = ref([])
const upcomingMilestones = ref([
  {
    project: "Downtown Office Tower",
    milestone: "Foundation Completion",
    date: "Apr 15, 2025",
    status: "On Track"
  },
  {
    project: "Riverside Bridge",
    milestone: "Steel Structure Assembly",
    date: "Apr 22, 2025",
    status: "At Risk"
  },
  {
    project: "Metro Station Renovation",
    milestone: "Electrical Systems Installation",
    date: "May 3, 2025",
    status: "On Track"
  },
  {
    project: "Harbor Expansion",
    milestone: "Dredging Phase 1",
    date: "May 10, 2025",
    status: "Delayed"
  }
])

onMounted(async () => {
  try {
    // Fetch dashboard data from API
    const statsResponse = await axios.get('/api/dashboard/stats')
    dashboardStats.value = statsResponse.data
    
    const activityResponse = await axios.get('/api/dashboard/recent-activity')
    recentActivity.value = activityResponse.data
    
    const metricsResponse = await axios.get('/api/dashboard/project-metrics')
    projectMetrics.value = metricsResponse.data
    
    const milestonesResponse = await axios.get('/api/dashboard/upcoming-milestones')
    upcomingMilestones.value = milestonesResponse.data
  } catch (error) {
    console.error('Error fetching dashboard data:', error)
  }
})
</script>