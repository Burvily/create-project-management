<template>
  <div class="flex min-h-screen flex-col">
    <div class="flex flex-col md:flex-row">
      <SideNav />
      <div class="flex-1 md:ml-64">
        <TopNav>
          <router-link to="/projects/new">
            <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 py-2 ml-auto gap-1">
              <Plus class="h-4 w-4" /> New Project
            </button>
          </router-link>
        </TopNav>
        <main class="grid flex-1 items-start gap-4 p-4 md:gap-8 md:p-6">
          <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold">Projects</h1>
            <div class="flex items-center gap-2">
              <div class="relative">
                <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                <input
                  type="search"
                  placeholder="Search projects..."
                  class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 w-[200px] pl-8 md:w-[260px] bg-background"
                  v-model="searchQuery"
                />
              </div>
              <select 
                v-model="statusFilter"
                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 w-[120px]"
              >
                <option value="all">All Projects</option>
                <option value="active">Active</option>
                <option value="completed">Completed</option>
                <option value="on-hold">On Hold</option>
              </select>
            </div>
          </div>
          <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <router-link 
              v-for="(project, i) in filteredProjects" 
              :key="i" 
              :to="`/projects/${project.id}`" 
              class="block"
            >
              <div class="rounded-lg border bg-card text-card-foreground shadow-sm h-full transition-all hover:border-primary/50 hover:shadow-md">
                <div class="flex flex-col space-y-1.5 p-6">
                  <div class="flex justify-between items-start">
                    <h3 class="text-2xl font-semibold leading-none tracking-tight">{{ project.title }}</h3>
                    <span 
                      :class="`inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 ${
                        project.risk === 'Low' 
                          ? 'border-transparent bg-secondary text-secondary-foreground' 
                          : project.risk === 'Medium' 
                            ? 'border-transparent bg-secondary text-secondary-foreground' 
                            : 'border-transparent bg-destructive text-destructive-foreground'
                      }`"
                    >
                      {{ project.risk }} Risk
                    </span>
                  </div>
                  <p class="text-sm text-muted-foreground">{{ project.description }}</p>
                </div>
                <div class="p-6 pt-0 grid gap-4">
                  <div class="flex justify-between items-center">
                    <span class="text-sm font-medium">Progress</span>
                    <span class="text-sm font-medium">{{ project.progress }}%</span>
                  </div>
                  <div class="h-2 w-full overflow-hidden rounded-full bg-secondary">
                    <div 
                      class="h-full bg-primary" 
                      :style="{ width: `${project.progress}%` }"
                    ></div>
                  </div>
                  <div class="grid grid-cols-2 gap-4 pt-2">
                    <div class="flex flex-col">
                      <span class="text-xs text-muted-foreground">Budget</span>
                      <span class="font-medium">{{ project.budget }}</span>
                    </div>
                    <div class="flex flex-col">
                      <span class="text-xs text-muted-foreground">Due Date</span>
                      <span class="font-medium">{{ project.dueDate }}</span>
                    </div>
                    <div class="flex flex-col">
                      <span class="text-xs text-muted-foreground">Tasks</span>
                      <span class="font-medium">{{ project.tasks.completed }}/{{ project.tasks.total }}</span>
                    </div>
                    <div class="flex flex-col">
                      <span class="text-xs text-muted-foreground">Status</span>
                      <span class="font-medium">{{ project.status }}</span>
                    </div>
                  </div>
                </div>
                <div class="flex items-center p-6 pt-0">
                  <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2 w-full gap-1">
                    View Details <ArrowUpRight class="h-4 w-4" />
                  </button>
                </div>
              </div>
            </router-link>
          </div>
        </main>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Plus, Search, ArrowUpRight } from 'lucide-vue-next'
import SideNav from '@/components/SideNav.vue'
import TopNav from '@/components/TopNav.vue'
import axios from 'axios'

const projects = ref([
  {
    id: 1,
    title: "Downtown Office Tower",
    description: "Construction of a 30-story office building in the city center",
    progress: 45,
    status: "In Progress",
    budget: "$24.5M",
    tasks: { completed: 86, total: 210 },
    dueDate: "Dec 15, 2025",
    risk: "Medium"
  },
  {
    id: 2,
    title: "Riverside Bridge",
    description: "Design and construction of a suspension bridge across the river",
    progress: 32,
    status: "In Progress",
    budget: "$18.2M",
    tasks: { completed: 54, total: 180 },
    dueDate: "Aug 30, 2025",
    risk: "High"
  },
  {
    id: 3,
    title: "Metro Station Renovation",
    description: "Complete renovation of the central metro station",
    progress: 68,
    status: "In Progress",
    budget: "$12.8M",
    tasks: { completed: 124, total: 165 },
    dueDate: "May 20, 2025",
    risk: "Low"
  },
  {
    id: 4,
    title: "Harbor Expansion",
    description: "Expansion of the commercial harbor to increase capacity",
    progress: 22,
    status: "In Progress",
    budget: "$32.1M",
    tasks: { completed: 42, total: 230 },
    dueDate: "Feb 28, 2026",
    risk: "Medium"
  },
  {
    id: 5,
    title: "Solar Farm Installation",
    description: "Installation of a 50MW solar farm on the outskirts of the city",
    progress: 12,
    status: "In Progress",
    budget: "$15.6M",
    tasks: { completed: 18, total: 145 },
    dueDate: "Nov 10, 2025",
    risk: "Low"
  },
  {
    id: 6,
    title: "Hospital Wing Addition",
    description: "Construction of a new wing for the regional hospital",
    progress: 5,
    status: "Just Started",
    budget: "$28.4M",
    tasks: { completed: 8, total: 195 },
    dueDate: "Apr 15, 2026",
    risk: "Medium"
  }
])

const searchQuery = ref('')
const statusFilter = ref('all')

const filteredProjects = computed(() => {
  return projects.value.filter(project => {
    // Filter by search query
    const matchesSearch = project.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                          project.description.toLowerCase().includes(searchQuery.value.toLowerCase())
    
    // Filter by status
    const matchesStatus = statusFilter.value === 'all' || 
                          (statusFilter.value === 'active' && project.status === 'In Progress') ||
                          (statusFilter.value === 'completed' && project.status === 'Completed') ||
                          (statusFilter.value === 'on-hold' && project.status === 'On Hold')
    
    return matchesSearch && matchesStatus
  })
})

onMounted(async () => {
  try {
    const response = await axios.get('/api/projects')
    projects.value = response.data.data.map(project => ({
      id: project.id,
      title: project.title,
      description: project.description,
      progress: project.progress,
      status: project.status,
      budget: `$${(project.budget_total / 1000000).toFixed(1)}M`,
      tasks: { 
        completed: project.tasks_completed || 0, 
        total: project.tasks_total || 0 
      },
      dueDate: new Date(project.due_date).toLocaleDateString('en-US', { 
        month: 'short', 
        day: 'numeric', 
        year: 'numeric' 
      }),
      risk: project.risk_level
    }))
  } catch (error) {
    console.error('Error fetching projects:', error)
  }
})
</script>