<template>
  <div class="space-y-4">
    <div v-for="(phase, phaseIndex) in wbs" :key="phaseIndex" class="border rounded-lg">
      <div 
        class="flex items-center justify-between p-4 cursor-pointer hover:bg-muted/50"
        @click="togglePhase(phaseIndex)"
      >
        <div class="flex items-center gap-2">
          <component :is="expandedPhases[phaseIndex] ? ChevronDown : ChevronRight" class="h-5 w-5 text-muted-foreground" />
          <FolderOpen class="h-5 w-5 text-primary" />
          <span class="font-medium">{{ phase.name }}</span>
          <span 
            :class="`inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 ${
              phase.status === 'Completed' 
                ? 'border-transparent bg-secondary text-secondary-foreground' 
                : phase.status === 'In Progress' 
                  ? 'border-transparent bg-secondary text-secondary-foreground' 
                  : phase.status === 'Just Started'
                    ? 'border-transparent bg-primary text-primary-foreground'
                    : 'border-transparent bg-secondary text-secondary-foreground'
            }`"
          >
            {{ phase.status }}
          </span>
        </div>
        <div class="flex items-center gap-4">
          <div class="flex items-center gap-1 text-sm text-muted-foreground">
            <Clock class="h-4 w-4" />
            <span>{{ phase.duration }}</span>
          </div>
          <div class="flex items-center gap-1 text-sm text-muted-foreground">
            <DollarSign class="h-4 w-4" />
            <span>${{ (phase.budget.allocated / 1000000).toFixed(1) }}M</span>
          </div>
          <div class="w-24 text-right text-sm font-medium">
            {{ phase.progress }}%
          </div>
        </div>
      </div>
      
      <div v-if="expandedPhases[phaseIndex]" class="px-4 pb-4 pt-0">
        <div class="ml-6 mb-4">
          <div class="h-2 w-full overflow-hidden rounded-full bg-secondary">
            <div 
              class="h-full bg-primary" 
              :style="{ width: `${phase.progress}%` }"
            ></div>
          </div>
        </div>
        
        <div class="space-y-4 ml-6">
          <div v-for="(milestone, milestoneIndex) in phase.milestones" :key="milestoneIndex" class="border rounded-lg p-4">
            <div class="flex items-center justify-between mb-2">
              <div class="flex items-center gap-2">
                <FileText class="h-4 w-4 text-primary" />
                <span class="font-medium">{{ milestone.name }}</span>
                <span 
                  :class="`inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 ${
                    milestone.status === 'Completed' 
                      ? 'border-transparent bg-secondary text-secondary-foreground' 
                      : milestone.status === 'In Progress' 
                        ? 'border-transparent bg-secondary text-secondary-foreground' 
                        : milestone.status === 'Just Started'
                          ? 'border-transparent bg-primary text-primary-foreground'
                          : 'border-transparent bg-secondary text-secondary-foreground'
                  }`"
                >
                  {{ milestone.status }}
                </span>
              </div>
              <div class="text-sm font-medium">
                {{ milestone.progress }}%
              </div>
            </div>
            
            <div class="h-2 w-full overflow-hidden rounded-full bg-secondary mb-4">
              <div 
                class="h-full bg-primary" 
                :style="{ width: `${milestone.progress}%` }"
              ></div>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
              <div class="flex items-center gap-1 text-muted-foreground">
                <Clock class="h-4 w-4" />
                <span>{{ milestone.duration }}</span>
              </div>
              <div class="flex items-center gap-1 text-muted-foreground">
                <DollarSign class="h-4 w-4" />
                <span>${{ (milestone.budget.allocated / 1000000).toFixed(2) }}M</span>
              </div>
            </div>
            
            <div v-if="milestone.tasks && milestone.tasks.length > 0" class="space-y-2 mt-4">
              <div class="flex items-center gap-1 text-sm font-medium">
                <Users class="h-4 w-4" />
                <span>Tasks</span>
              </div>
              <ul class="space-y-1 ml-5 list-disc text-sm">
                <li v-for="(task, taskIndex) in milestone.tasks" :key="taskIndex" class="text-muted-foreground">
                  {{ task.name }}
                  <span 
                    :class="`inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent ml-2 text-xs`"
                  >
                    {{ task.status }}
                  </span>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { ChevronDown, ChevronRight, FolderOpen, FileText, Clock, DollarSign, Users } from 'lucide-vue-next'

const props = defineProps({
  wbs: {
    type: Array,
    default: () => []
  }
})

const expandedPhases = ref({})

// Initialize first phase as expanded
if (props.wbs.length > 0) {
  expandedPhases.value[0] = true
}

const togglePhase = (phaseIndex) => {
  expandedPhases.value[phaseIndex] = !expandedPhases.value[phaseIndex]
}
</script>