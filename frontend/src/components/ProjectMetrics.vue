<template>
  <div class="space-y-4">
    <div v-for="(project, i) in projects" :key="i" class="space-y-2">
      <div class="flex items-center justify-between">
        <span class="font-medium text-sm">{{ project.name }}</span>
        <span 
          :class="`inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 ${
            project.status === 'On Track' 
              ? 'border-transparent bg-secondary text-secondary-foreground' 
              : project.status === 'At Risk' 
                ? 'border-transparent bg-secondary text-secondary-foreground' 
                : 'border-transparent bg-destructive text-destructive-foreground'
          }`"
        >
          {{ project.status }}
        </span>
      </div>
      <div class="space-y-1">
        <div class="flex justify-between text-xs text-muted-foreground">
          <span>Progress</span>
          <span>{{ project.progress }}%</span>
        </div>
        <div class="h-2 w-full overflow-hidden rounded-full bg-secondary">
          <div 
            class="h-full bg-primary" 
            :style="{ width: `${project.progress}%` }"
          ></div>
        </div>
      </div>
      <div class="space-y-1">
        <div class="flex justify-between text-xs text-muted-foreground">
          <span>Budget</span>
          <span>${{ (project.budget.spent / 1000000).toFixed(1) }}M / ${{ (project.budget.total / 1000000).toFixed(1) }}M</span>
        </div>
        <div class="h-2 w-full overflow-hidden rounded-full bg-secondary">
          <div 
            class="h-full bg-primary" 
            :style="{ width: `${(project.budget.spent / project.budget.total) * 100}%` }"
          ></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  projects: {
    type: Array,
    default: () => []
  }
})
</script>