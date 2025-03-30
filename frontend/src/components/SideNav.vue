<template>
  <div class="border-r bg-muted/40 md:w-64 md:flex-col md:fixed md:inset-y-0 hidden md:flex">
    <div class="flex h-16 items-center border-b px-6">
      <router-link to="/" class="flex items-center gap-2 font-semibold">
        <FolderKanban class="h-6 w-6 text-primary" />
        <span>CREATE</span>
      </router-link>
    </div>
    <div class="flex-1 overflow-auto py-2">
      <nav class="grid items-start px-4 text-sm font-medium">
        <router-link
          to="/dashboard"
          class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all"
          :class="{ 'bg-primary/10 text-primary': isActive('/dashboard'), 'text-muted-foreground hover:text-primary hover:bg-primary/10': !isActive('/dashboard') }"
        >
          <LayoutDashboard class="h-4 w-4" />
          Dashboard
        </router-link>
        <router-link
          to="/projects"
          class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all"
          :class="{ 'bg-primary/10 text-primary': isActive('/projects'), 'text-muted-foreground hover:text-primary hover:bg-primary/10': !isActive('/projects') }"
        >
          <FolderKanban class="h-4 w-4" />
          Projects
        </router-link>
        <router-link
          to="/tasks"
          class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all"
          :class="{ 'bg-primary/10 text-primary': isActive('/tasks'), 'text-muted-foreground hover:text-primary hover:bg-primary/10': !isActive('/tasks') }"
        >
          <ListTodo class="h-4 w-4" />
          Tasks
        </router-link>
        <router-link
          to="/calendar"
          class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all"
          :class="{ 'bg-primary/10 text-primary': isActive('/calendar'), 'text-muted-foreground hover:text-primary hover:bg-primary/10': !isActive('/calendar') }"
        >
          <Calendar class="h-4 w-4" />
          Calendar
        </router-link>
        <router-link
          to="/reports"
          class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all"
          :class="{ 'bg-primary/10 text-primary': isActive('/reports'), 'text-muted-foreground hover:text-primary hover:bg-primary/10': !isActive('/reports') }"
        >
          <FileText class="h-4 w-4" />
          Reports
        </router-link>
        <router-link
          to="/team"
          class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all"
          :class="{ 'bg-primary/10 text-primary': isActive('/team'), 'text-muted-foreground hover:text-primary hover:bg-primary/10': !isActive('/team') }"
        >
          <Users class="h-4 w-4" />
          Team
        </router-link>
        <router-link
          to="/settings"
          class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all"
          :class="{ 'bg-primary/10 text-primary': isActive('/settings'), 'text-muted-foreground hover:text-primary hover:bg-primary/10': !isActive('/settings') }"
        >
          <Settings class="h-4 w-4" />
          Settings
        </router-link>
      </nav>
    </div>
  </div>
</template>

<script setup>
import { useRoute } from 'vue-router'
import { FolderKanban, LayoutDashboard, ListTodo, Calendar, FileText, Users, Settings } from 'lucide-vue-next'

const route = useRoute()

const isActive = (path) => {
  return route.path.startsWith(path)
}
</script>