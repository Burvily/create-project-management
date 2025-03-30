<template>
  <div class="flex min-h-screen flex-col">
    <div class="flex flex-col md:flex-row">
      <SideNav />
      <div class="flex-1 md:ml-64">
        <TopNav>
          <router-link to="/projects" class="flex items-center gap-1 text-sm font-medium text-muted-foreground hover:text-foreground">
            <ArrowLeft class="h-4 w-4" /> Back to Projects
          </router-link>
          <div class="ml-auto flex items-center gap-2">
            <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 gap-1">
              <Download class="h-4 w-4" /> Export
            </button>
            <div class="relative">
              <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 w-10">
                <MoreHorizontal class="h-4 w-4" />
                <span class="sr-only">More options</span>
              </button>
            </div>
          </div>
        </TopNav>
        <main class="grid flex-1 items-start gap-4 p-4 md:gap-8 md:p-6">
          <div class="flex flex-col gap-2">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <h1 class="text-2xl font-semibold">{{ project.title }}</h1>
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
              <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-secondary text-secondary-foreground">
                {{ project.status }}
              </span>
            </div>
            <p class="text-muted-foreground">{{ project.description }}</p>
          </div>
          
          <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
              <div class="flex flex-col space-y-1.5 p-6 pb-2">
                <h3 class="text-sm font-medium">Progress</h3>
              </div>
              <div class="p-6">
                <div class="flex justify-between items-center mb-2">
                  <span class="text-2xl font-bold">{{ project.progress }}%</span>
                  <span class="text-sm text-muted-foreground">
                    {{ project.tasks.completed }}/{{ project.tasks.total }} Tasks
                  </span>
                </div>
                <div class="h-2 w-full overflow-hidden rounded-full bg-secondary">
                  <div 
                    class="h-full bg-primary" 
                    :style="{ width: `${project.progress}%` }"
                  ></div>
                </div>
              </div>
            </div>
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
              <div class="flex flex-col space-y-1.5 p-6 pb-2">
                <h3 class="text-sm font-medium">Budget</h3>
              </div>
              <div class="p-6">
                <div class="flex justify-between items-center mb-2">
                  <span class="text-2xl font-bold">${{ (project.budget.spent / 1000000).toFixed(1) }}M</span>
                  <span class="text-sm text-muted-foreground">
                    of ${{ (project.budget.total / 1000000).toFixed(1) }}M
                  </span>
                </div>
                <div class="h-2 w-full overflow-hidden rounded-full bg-secondary">
                  <div 
                    class="h-full bg-primary" 
                    :style="{ width: `${(project.budget.spent / project.budget.total) * 100}%` }"
                  ></div>
                </div>
              </div>
            </div>
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
              <div class="flex flex-col space-y-1.5 p-6 pb-2">
                <h3 class="text-sm font-medium">Timeline</h3>
              </div>
              <div class="p-6">
                <div class="flex justify-between items-center">
                  <div class="space-y-1">
                    <div class="text-sm text-muted-foreground">Start Date</div>
                    <div class="font-medium">{{ project.startDate }}</div>
                  </div>
                  <div class="space-y-1 text-right">
                    <div class="text-sm text-muted-foreground">Due Date</div>
                    <div class="font-medium">{{ project.dueDate }}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="space-y-4">
            <div class="border-b">
              <div class="flex flex-wrap -mb-px">
                <button 
                  v-for="tab in tabs" 
                  :key="tab.value"
                  @click="activeTab = tab.value"
                  :class="`inline-flex items-center justify-center whitespace-nowrap rounded-t-md px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border-b-2 ${
                    activeTab === tab.value
                      ? 'border-primary text-foreground'
                      : 'border-transparent text-muted-foreground hover:text-foreground'
                  }`"
                >
                  {{ tab.label }}
                </button>
              </div>
            </div>
            
            <div v-if="activeTab === 'overview'" class="space-y-4">
              <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                  <h3 class="text-2xl font-semibold leading-none tracking-tight">Project Overview</h3>
                  <p class="text-sm text-muted-foreground">
                    Key information and status of the project
                  </p>
                </div>
                <div class="p-6 pt-0 space-y-6">
                  <div class="grid gap-4 md:grid-cols-2">
                    <div>
                      <h3 class="text-lg font-medium">Project Details</h3>
                      <div class="h-px w-full bg-border my-2"></div>
                      <dl class="space-y-2">
                        <div class="flex justify-between">
                          <dt class="text-sm font-medium text-muted-foreground">Client:</dt>
                          <dd class="text-sm font-medium">{{ project.client }}</dd>
                        </div>
                        <div class="flex justify-between">
                          <dt class="text-sm font-medium text-muted-foreground">Project Manager:</dt>
                          <dd class="text-sm font-medium">{{ project.manager }}</dd>
                        </div>
                        <div class="flex justify-between">
                          <dt class="text-sm font-medium text-muted-foreground">Start Date:</dt>
                          <dd class="text-sm font-medium">{{ project.startDate }}</dd>
                        </div>
                        <div class="flex justify-between">
                          <dt class="text-sm font-medium text-muted-foreground">Due Date:</dt>
                          <dd class="text-sm font-medium">{{ project.dueDate }}</dd>
                        </div>
                        <div class="flex justify-between">
                          <dt class="text-sm font-medium text-muted-foreground">Status:</dt>
                          <dd class="text-sm font-medium">{{ project.status }}</dd>
                        </div>
                        <div class="flex justify-between">
                          <dt class="text-sm font-medium text-muted-foreground">Risk Level:</dt>
                          <dd class="text-sm font-medium">{{ project.risk }}</dd>
                        </div>
                      </dl>
                    </div>
                    <div>
                      <h3 class="text-lg font-medium">Project Metrics</h3>
                      <div class="h-px w-full bg-border my-2"></div>
                      <dl class="space-y-2">
                        <div class="flex justify-between">
                          <dt class="text-sm font-medium text-muted-foreground">Tasks Completed:</dt>
                          <dd class="text-sm font-medium">{{ project.tasks.completed }} of {{ project.tasks.total }}</dd>
                        </div>
                        <div class="flex justify-between">
                          <dt class="text-sm font-medium text-muted-foreground">Budget Spent:</dt>
                          <dd class="text-sm font-medium">${{ (project.budget.spent / 1000000).toFixed(2) }}M</dd>
                        </div>
                        <div class="flex justify-between">
                          <dt class="text-sm font-medium text-muted-foreground">Budget Remaining:</dt>
                          <dd class="text-sm font-medium">${{ (project.budget.remaining / 1000000).toFixed(2) }}M</dd>
                        </div>
                        <div class="flex justify-between">
                          <dt class="text-sm font-medium text-muted-foreground">Progress:</dt>
                          <dd class="text-sm font-medium">{{ project.progress }}%</dd>
                        </div>
                        <div class="flex justify-between">
                          <dt class="text-sm font-medium text-muted-foreground">Team Size:</dt>
                          <dd class="text-sm font-medium">{{ project.team.length }} members</dd>
                        </div>
                      </dl>
                    </div>
                  </div>
                  
                  <div>
                    <h3 class="text-lg font-medium">Recent Activity</h3>
                    <div class="h-px w-full bg-border my-2"></div>
                    <div class="space-y-4">
                      <div v-for="(activity, i) in recentActivities" :key="i" class="flex items-start gap-4">
                        <div class="mt-1">
                          <component :is="activity.icon" class="h-5 w-5" :class="activity.iconClass" />
                        </div>
                        <div class="space-y-1">
                          <p class="text-sm font-medium">{{ activity.action }}: {{ activity.description }}</p>
                          <p class="text-xs text-muted-foreground">By {{ activity.user }} • {{ activity.time }}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div v-if="activeTab === 'wbs'" class="space-y-4">
              <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                  <h3 class="text-2xl font-semibold leading-none tracking-tight">Work Breakdown Structure</h3>
                  <p class="text-sm text-muted-foreground">
                    AI-generated breakdown of project phases, milestones, and tasks
                  </p>
                </div>
                <div class="p-6 pt-0">
                  <WorkBreakdownStructure :wbs="workBreakdownStructure" />
                </div>
              </div>
            </div>
            
            <div v-if="activeTab === 'budget'" class="space-y-4">
              <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                  <h3 class="text-2xl font-semibold leading-none tracking-tight">Budget Management</h3>
                  <p class="text-sm text-muted-foreground">
                    Track and manage project expenses against planned budget
                  </p>
                </div>
                <div class="p-6 pt-0">
                  <ProjectBudget :budget="budget" />
                </div>
              </div>
            </div>
            
            <div v-if="activeTab === 'timeline'" class="space-y-4">
              <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                  <h3 class="text-2xl font-semibold leading-none tracking-tight">Project Timeline</h3>
                  <p class="text-sm text-muted-foreground">
                    Visual representation of project schedule and milestones
                  </p>
                </div>
                <div class="p-6 pt-0">
                  <ProjectTimeline :timeline="timeline" />
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
import { useRoute } from 'vue-router'
import { ArrowLeft, Download, MoreHorizontal, CheckCircle2, AlertCircle } from 'lucide-vue-next'
import SideNav from '@/components/SideNav.vue'
import TopNav from '@/components/TopNav.vue'
import WorkBreakdownStructure from '@/components/WorkBreakdownStructure.vue'
import ProjectBudget from '@/components/ProjectBudget.vue'
import ProjectTimeline from '@/components/ProjectTimeline.vue'
import axios from 'axios'

const route = useRoute()
const projectId = route.params.id

const project = ref({
  id: 1,
  title: "Downtown Office Tower",
  description: "Construction of a 30-story office building in the city center with modern amenities, sustainable design features, and smart building technology integration.",
  progress: 45,
  status: "In Progress",
  budget: {
    total: 24500000,
    spent: 11025000,
    remaining: 13475000
  },
  tasks: { completed: 86, total: 210 },
  startDate: "Mar 10, 2024",
  dueDate: "Dec 15, 2025",
  risk: "Medium",
  manager: "John Doe",
  team: ["Sarah Johnson", "Michael Chen", "Priya Patel", "David Rodriguez"],
  client: "Skyline Developments Inc."
})

const tabs = [
  { label: 'Overview', value: 'overview' },
  { label: 'Work Breakdown', value: 'wbs' },
  { label: 'Budget', value: 'budget' },
  { label: 'Timeline', value: 'timeline' },
  { label: 'Team', value: 'team' },
  { label: 'Documents', value: 'documents' }
]

const activeTab = ref('overview')

const recentActivities = ref([
  {
    action: "Task completed",
    description: "Foundation concrete pouring completed",
    user: "Michael Chen",
    time: "2 hours ago",
    icon: CheckCircle2,
    iconClass: "text-green-500"
  },
  {
    action: "Budget updated",
    description: "Steel material costs increased by 5%",
    user: "John Doe",
    time: "Yesterday",
    icon: AlertCircle,
    iconClass: "text-yellow-500"
  },
  {
    action: "Milestone reached",
    description: "Basement level construction completed",
    user: "Sarah Johnson",
    time: "2 days ago",
    icon: CheckCircle2,
    iconClass: "text-green-500"
  }
])

const workBreakdownStructure = ref([])
const budget = ref({})
const timeline = ref({})

onMounted(async () => {
  try {
    // Fetch project details
    const response = await axios.get(`/api/projects/${projectId}`)
    project.value = {
      id: response.data.id,
      title: response.data.title,
      description: response.data.description,
      progress: response.data.progress,
      status: response.data.status,
      budget: {
        total: response.data.budget_total,
        spent: response.data.budget_spent,
        remaining: response.data.budget_total - response.data.budget_spent
      },
      tasks: { 
        completed: response.data.tasks_completed || 0, 
        total: response.data.tasks_total || 0 
      },
      startDate: new Date(response.data.start_date).toLocaleDateString('en-US', { 
        month: 'short', 
        day: 'numeric', 
        year: 'numeric' 
      }),
      dueDate: new Date(response.data.due_date).toLocaleDateString('en-US', { 
        month: 'short', 
        day: 'numeric', 
        year: 'numeric' 
      }),
      risk: response.data.risk_level,
      manager: response.data.manager.name,
      team: response.data.team.map(member => member.name),
      client: response.data.client
    }
    
    // Fetch WBS data
    const wbsResponse = await axios.get(`/api/projects/${projectId}/wbs`)
    workBreakdownStructure.value = wbsResponse.data.phases
    
    // Fetch budget data
    const budgetResponse = await axios.get(`/api/projects/${projectId}/budget`)
    budget.value = budgetResponse.data
    
    // Fetch timeline data
    const timelineResponse = await axios.get(`/api/projects/${projectId}/timeline`)
    timeline.value = timelineResponse.data
  } catch (error) {
    console.error('Error fetching project data:', error)
  }
})
</script>