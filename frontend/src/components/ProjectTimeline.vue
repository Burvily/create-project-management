<template>
  <div class="space-y-6">
    <div class="flex justify-between items-center">
      <div class="flex items-center gap-2">
        <button 
          @click="currentView = 'months'"
          :class="`inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 ${currentView === 'months' ? 'bg-primary/10' : ''}`"
        >
          Monthly View
        </button>
        <button 
          @click="currentView = 'quarters'"
          :class="`inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 ${currentView === 'quarters' ? 'bg-primary/10' : ''}`"
        >
          Quarterly View
        </button>
      </div>
      <div class="flex items-center gap-2">
        <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 w-10">
          <ChevronLeft class="h-4 w-4" />
        </button>
        <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 w-10">
          <ChevronRight class="h-4 w-4" />
        </button>
      </div>
    </div>
    
    <div class="relative overflow-x-auto">
      <div class="grid grid-cols-[200px_1fr] border rounded-lg">
        <div class="border-r bg-muted/50 p-3 font-medium">Timeline</div>
        <div class="grid" :style="{ 
          gridTemplateColumns: `repeat(${timeUnits.length}, minmax(120px, 1fr))` 
        }">
          <div v-for="(unit, i) in timeUnits" :key="i" class="border-r last:border-r-0 p-3 text-center font-medium text-sm">
            {{ unit }}
          </div>
        </div>
        
        <template v-for="(phase, i) in timeline.milestones" :key="i">
          <div class="border-t border-r bg-muted/30 p-3 font-medium">
            {{ phase.phase }}
          </div>
          <div class="border-t grid" :style="{ 
            gridTemplateColumns: `repeat(${timeUnits.length}, minmax(120px, 1fr))` 
          }">
            <div v-for="(unit, j) in timeUnits" :key="j" class="border-r last:border-r-0 p-2 min-h-[80px] relative">
              <div v-if="getEventsForTimeUnit(phase.events, unit).length > 0" class="space-y-2">
                <div 
                  v-for="(event, k) in getEventsForTimeUnit(phase.events, unit)" 
                  :key="k" 
                  class="rounded-lg border bg-card text-card-foreground shadow-sm p-0 overflow-hidden"
                >
                  <div class="p-2 text-xs">
                    <div class="flex items-center gap-1 font-medium">
                      <component 
                        :is="getEventStatusIcon(event.status)" 
                        class="h-3 w-3" 
                        :class="getEventStatusIconClass(event.status)" 
                      />
                      {{ event.name }}
                    </div>
                    <div class="text-muted-foreground mt-1">{{ event.date }}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </template>
      </div>
    </div>
    
    <div class="flex items-center gap-4 text-sm">
      <div class="flex items-center gap-1">
        <CheckCircle class="h-4 w-4 text-green-500" />
        <span>Completed</span>
      </div>
      <div class="flex items-center gap-1">
        <Clock class="h-4 w-4 text-yellow-500" />
        <span>In Progress</span>
      </div>
      <div class="flex items-center gap-1">
        <AlertCircle class="h-4 w-4 text-muted-foreground" />
        <span>Upcoming</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { ChevronLeft, ChevronRight, CheckCircle, Clock, AlertCircle } from 'lucide-vue-next'

const props = defineProps({
  timeline: {
    type: Object,
    default: () => ({
      milestones: [
        {
          phase: "Site Preparation & Foundation",
          events: [
            { name: "Site Clearing", date: "Mar 15, 2024", status: "Completed" },
            { name: "Excavation", date: "Apr 5, 2024", status: "Completed" },
            { name: "Foundation Concrete", date: "May 20, 2024", status: "In Progress" },
            { name: "Foundation Completion", date: "Jun 30, 2024", status: "Upcoming" }
          ]
        },
        {
          phase: "Structural Framework",
          events: [
            { name: "Steel Delivery", date: "Jun 15, 2024", status: "Upcoming" },
            { name: "Column Installation", date: "Jul 10, 2024", status: "Upcoming" },
            { name: "Beam Installation", date: "Aug 25, 2024", status: "Upcoming" },
            { name: "Floor Decking", date: "Oct 15, 2024", status: "Upcoming" },
            { name: "Concrete Core Completion", date: "Dec 20, 2024", status: "Upcoming" }
          ]
        },
        {
          phase: "Building Envelope",
          events: [
            { name: "Exterior Wall Start", date: "Dec 30, 2024", status: "Upcoming" },
            { name: "Window Installation", date: "Feb 15, 2025", status: "Upcoming" },
            { name: "Roofing System", date: "Mar 10, 2025", status: "Upcoming" },
            { name: "Building Envelope Completion", date: "Apr 30, 2025", status: "Upcoming" }
          ]
        }
      ],
      months: [
        "Mar 2024", "Apr 2024", "May 2024", "Jun 2024", "Jul 2024", "Aug 2024", 
        "Sep 2024", "Oct 2024", "Nov 2024", "Dec 2024", "Jan 2025", "Feb 2025", 
        "Mar 2025", "Apr 2025", "May 2025", "Jun 2025", "Jul 2025", "Aug 2025",
        "Sep 2025", "Oct 2025", "Nov 2025", "Dec 2025"
      ],
      quarters: [
        "Q1 2024", "Q2 2024", "Q3 2024", "Q4 2024", 
        "Q1 2025", "Q2 2025", "Q3 2025", "Q4 2025"
      ]
    })
  }
})

const currentView = ref('months')

const timeUnits = computed(() => {
  return currentView.value === 'months' ? props.timeline.months : props.timeline.quarters
})

const getEventsForTimeUnit = (events, unit) => {
  return events.filter(event => {
    const eventDate = new Date(event.date)
    const eventMonth = `${eventDate.toLocaleString('default', { month: 'short' })} ${eventDate.getFullYear()}`
    const eventQuarter = `Q${Math.floor(eventDate.getMonth() / 3) + 1} ${eventDate.getFullYear()}`
    return currentView.value === 'months' ? eventMonth === unit : eventQuarter === unit
  })
}

const getEventStatusIcon = (status) => {
  if (status === 'Completed') return CheckCircle
  if (status === 'In Progress') return Clock
  return AlertCircle
}

const getEventStatusIconClass = (status) => {
  if (status === 'Completed') return 'text-green-500'
  if (status === 'In Progress') return 'text-yellow-500'
  return 'text-muted-foreground'
}
</script>