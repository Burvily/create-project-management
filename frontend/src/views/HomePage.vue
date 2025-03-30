<template>
  <div class="flex min-h-screen flex-col">
    <header class="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
      <div class="container flex h-16 items-center space-x-4 sm:justify-between sm:space-x-0">
        <div class="flex gap-2 items-center text-xl font-bold">
          <FolderKanban class="h-6 w-6 text-primary" />
          <span>CREATE</span>
        </div>
        <div class="flex flex-1 items-center justify-end space-x-4">
          <nav class="flex items-center space-x-2">
            <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
              Help
            </button>
            <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 py-2">
              Sign In
            </button>
          </nav>
        </div>
      </div>
    </header>
    <main class="flex-1">
      <section class="w-full py-12 md:py-24 lg:py-32 bg-muted/40">
        <div class="container px-4 md:px-6">
          <div class="grid gap-6 lg:grid-cols-[1fr_400px] lg:gap-12 xl:grid-cols-[1fr_600px]">
            <div class="flex flex-col justify-center space-y-4">
              <div class="space-y-2">
                <h1 class="text-3xl font-bold tracking-tighter sm:text-5xl xl:text-6xl/none">
                  Revolutionize Your Project Management
                </h1>
                <p class="max-w-[600px] text-muted-foreground md:text-xl">
                  CREATE empowers construction and engineering teams with AI-driven insights, structured workflows, and real-time monitoring.
                </p>
              </div>
              <div class="flex flex-col gap-2 min-[400px]:flex-row">
                <router-link to="/dashboard">
                  <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2 gap-1">
                    Get Started <ArrowRight class="h-4 w-4" />
                  </button>
                </router-link>
                <router-link to="/projects">
                  <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                    View Demo Projects
                  </button>
                </router-link>
              </div>
            </div>
            <div class="flex items-center justify-center">
              <div class="rounded-lg border bg-card text-card-foreground shadow-sm w-full">
                <div class="flex flex-col space-y-1.5 p-6">
                  <h3 class="text-2xl font-semibold leading-none tracking-tight">AI-Powered Project Management</h3>
                  <p class="text-sm text-muted-foreground">
                    Streamline your construction and engineering projects with intelligent planning and monitoring
                  </p>
                </div>
                <div class="p-6 pt-0 grid gap-4">
                  <div class="flex items-center gap-4 rounded-md border p-4">
                    <BarChart3 class="h-8 w-8 text-primary" />
                    <div class="space-y-1">
                      <h3 class="font-medium leading-none">Automated Work Breakdown Structure</h3>
                      <p class="text-sm text-muted-foreground">
                        Detailed breakdown of project phases, milestones, and dependencies
                      </p>
                    </div>
                  </div>
                  <div class="flex items-center gap-4 rounded-md border p-4">
                    <PieChart class="h-8 w-8 text-primary" />
                    <div class="space-y-1">
                      <h3 class="font-medium leading-none">Cost Estimation & Budgeting</h3>
                      <p class="text-sm text-muted-foreground">
                        Accurate budget estimates and expense tracking for each phase
                      </p>
                    </div>
                  </div>
                  <div class="flex items-center gap-4 rounded-md border p-4">
                    <Clock class="h-8 w-8 text-primary" />
                    <div class="space-y-1">
                      <h3 class="font-medium leading-none">Real-Time Project Monitoring</h3>
                      <p class="text-sm text-muted-foreground">
                        Track progress, receive insights, and manage risks effectively
                      </p>
                    </div>
                  </div>
                  <div class="flex items-center gap-4 rounded-md border p-4">
                    <FileText class="h-8 w-8 text-primary" />
                    <div class="space-y-1">
                      <h3 class="font-medium leading-none">Report Generation & Insights</h3>
                      <p class="text-sm text-muted-foreground">
                        Automated performance reports and actionable recommendations
                      </p>
                    </div>
                  </div>
                </div>
                <div class="flex items-center p-6 pt-0">
                  <router-link to="/dashboard" class="w-full">
                    <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2 w-full">
                      Start Your First Project
                    </button>
                  </router-link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>
  </div>
</template>

<script setup>
import { FolderKanban, ArrowRight, BarChart3, PieChart, Clock, FileText } from 'lucide-vue-next'
</script>