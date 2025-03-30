<template>
  <div class="space-y-4">
    <div v-for="(activity, i) in activities" :key="i" class="flex items-start gap-4">
      <div class="h-8 w-8 rounded-full bg-muted flex items-center justify-center overflow-hidden">
        <img v-if="activity.user.avatar" :src="activity.user.avatar" :alt="activity.user.name" class="h-full w-full object-cover" />
        <span v-else class="text-xs font-medium">{{ activity.user.initials }}</span>
      </div>
      <div class="space-y-1">
        <p class="text-sm">
          <span class="font-medium">{{ activity.user.name }}</span>
          {{ activity.action }} on
          <span class="font-medium">{{ activity.project }}</span>
        </p>
        <div class="flex items-center text-xs text-muted-foreground gap-1">
          <div class="flex items-center gap-1">
            <component :is="getIconComponent(activity.icon)" class="h-3 w-3" />
            <span>{{ activity.task }}</span>
          </div>
          <span>•</span>
          <div class="flex items-center gap-1">
            <Clock class="h-3 w-3" />
            <span>{{ activity.time }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Clock, CheckCircle2, FileText, AlertCircle } from 'lucide-vue-next'

const props = defineProps({
  activities: {
    type: Array,
    default: () => []
  }
})

const getIconComponent = (iconName) => {
  const icons = {
    'CheckCircle2': CheckCircle2,
    'FileText': FileText,
    'AlertCircle': AlertCircle
  }
  return icons[iconName] || FileText
}
</script>