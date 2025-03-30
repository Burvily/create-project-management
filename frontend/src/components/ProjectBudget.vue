<template>
  <div class="space-y-6">
    <div class="grid gap-4 md:grid-cols-3">
      <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
        <div class="flex flex-col space-y-1.5 p-6 pb-2">
          <h3 class="text-sm font-medium">Total Budget</h3>
        </div>
        <div class="p-6">
          <div class="text-2xl font-bold">${{ (budget.totalBudget / 1000000).toFixed(1) }}M</div>
        </div>
      </div>
      <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
        <div class="flex flex-col space-y-1.5 p-6 pb-2">
          <h3 class="text-sm font-medium">Spent</h3>
        </div>
        <div class="p-6">
          <div class="text-2xl font-bold">${{ (budget.totalSpent / 1000000).toFixed(1) }}M</div>
          <div class="text-xs text-muted-foreground">{{ budget.percentSpent.toFixed(1) }}% of total budget</div>
        </div>
      </div>
      <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
        <div class="flex flex-col space-y-1.5 p-6 pb-2">
          <h3 class="text-sm font-medium">Remaining</h3>
        </div>
        <div class="p-6">
          <div class="text-2xl font-bold">${{ (budget.remainingBudget / 1000000).toFixed(1) }}M</div>
          <div class="text-xs text-muted-foreground">{{ (100 - budget.percentSpent).toFixed(1) }}% of total budget</div>
        </div>
      </div>
    </div>
    
    <div class="space-y-2">
      <div class="flex justify-between items-center">
        <span class="text-sm font-medium">Budget Utilization</span>
        <span class="text-sm font-medium">{{ budget.percentSpent.toFixed(1) }}%</span>
      </div>
      <div class="h-2 w-full overflow-hidden rounded-full bg-secondary">
        <div 
          class="h-full bg-primary" 
          :style="{ width: `${budget.percentSpent}%` }"
        ></div>
      </div>
    </div>
    
    <div class="space-y-4">
      <div class="border-b">
        <div class="flex flex-wrap -mb-px">
          <button 
            v-for="tab in budgetTabs" 
            :key="tab.value"
            @click="activeBudgetTab = tab.value"
            :class="`inline-flex items-center justify-center whitespace-nowrap rounded-t-md px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border-b-2 ${
              activeBudgetTab === tab.value
                ? 'border-primary text-foreground'
                : 'border-transparent text-muted-foreground hover:text-foreground'
            }`"
          >
            {{ tab.label }}
          </button>
        </div>
      </div>
      
      <div v-if="activeBudgetTab === 'by-phase'" class="space-y-4">
        <div ref="phaseChartContainer" class="h-[300px]"></div>
        
        <div class="space-y-4">
          <div v-for="(phase, i) in budget.phaseData" :key="i" class="space-y-2">
            <div class="flex justify-between items-center">
              <span class="text-sm font-medium">{{ phase.name }}</span>
              <span class="text-sm text-muted-foreground">
                ${{ phase.spent }}M / ${{ phase.allocated }}M
              </span>
            </div>
            <div class="h-2 w-full overflow-hidden rounded-full bg-secondary">
              <div 
                class="h-full bg-primary" 
                :style="{ width: `${(phase.spent / phase.allocated) * 100}%` }"
              ></div>
            </div>
          </div>
        </div>
      </div>
      
      <div v-if="activeBudgetTab === 'by-category'" class="space-y-4">
        <div ref="categoryChartContainer" class="h-[300px]"></div>
        
        <div class="space-y-4">
          <div v-for="(category, i) in budget.categoryData" :key="i" class="space-y-2">
            <div class="flex justify-between items-center">
              <span class="text-sm font-medium">{{ category.name }}</span>
              <span class="text-sm text-muted-foreground">
                ${{ category.spent }}M / ${{ category.allocated }}M
              </span>
            </div>
            <div class="h-2 w-full overflow-hidden rounded-full bg-secondary">
              <div 
                class="h-full bg-primary" 
                :style="{ width: `${(category.spent / category.allocated) * 100}%` }"
              ></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import * as echarts from 'echarts'

const props = defineProps({
  budget: {
    type: Object,
    default: () => ({
      totalBudget: 24500000,
      totalSpent: 5840000,
      remainingBudget: 18660000,
      percentSpent: 23.8,
      phaseData: [
        { name: "Site Preparation", allocated: 4.5, spent: 4.2 },
        { name: "Structural Framework", allocated: 8.2, spent: 1.64 },
        { name: "Building Envelope", allocated: 5.8, spent: 0 },
        { name: "Interior Systems", allocated: 4.5, spent: 0 },
        { name: "Building Systems", allocated: 1.5, spent: 0 }
      ],
      categoryData: [
        { name: "Materials", allocated: 12.5, spent: 3.8 },
        { name: "Labor", allocated: 8.2, spent: 1.5 },
        { name: "Equipment", allocated: 2.3, spent: 0.4 },
        { name: "Permits & Fees", allocated: 0.8, spent: 0.1 },
        { name: "Contingency", allocated: 0.7, spent: 0 }
      ]
    })
  }
})

const budgetTabs = [
  { label: 'By Phase', value: 'by-phase' },
  { label: 'By Category', value: 'by-category' }
]

const activeBudgetTab = ref('by-phase')
const phaseChartContainer = ref(null)
const categoryChartContainer = ref(null)
let phaseChart = null
let categoryChart = null

const initPhaseChart = () => {
  if (phaseChartContainer.value && props.budget.phaseData) {
    if (phaseChart) phaseChart.dispose()
    phaseChart = echarts.init(phaseChartContainer.value)
    
    const option = {
      tooltip: {
        trigger: 'axis',
        axisPointer: {
          type: 'shadow'
        }
      },
      legend: {
        data: ['Allocated Budget', 'Actual Spent']
      },
      grid: {
        left: '3%',
        right: '4%',
        bottom: '3%',
        containLabel: true
      },
      xAxis: {
        type: 'category',
        data: props.budget.phaseData.map(item => item.name)
      },
      yAxis: {
        type: 'value',
        name: 'Million $',
        nameLocation: 'middle',
        nameGap: 40
      },
      series: [
        {
          name: 'Allocated Budget',
          type: 'bar',
          data: props.budget.phaseData.map(item => item.allocated),
          itemStyle: {
            color: '#3b82f6'
          }
        },
        {
          name: 'Actual Spent',
          type: 'bar',
          data: props.budget.phaseData.map(item => item.spent),
          itemStyle: {
            color: '#10b981'
          }
        }
      ]
    }
    
    phaseChart.setOption(option)
  }
}

const initCategoryChart = () => {
  if (categoryChartContainer.value && props.budget.categoryData) {
    if (categoryChart) categoryChart.dispose()
    categoryChart = echarts.init(categoryChartContainer.value)
    
    const option = {
      tooltip: {
        trigger: 'axis',
        axisPointer: {
          type: 'shadow'
        }
      },
      legend: {
        data: ['Allocated Budget', 'Actual Spent']
      },
      grid: {
        left: '3%',
        right: '4%',
        bottom: '3%',
        containLabel: true
      },
      xAxis: {
        type: 'category',
        data: props.budget.categoryData.map(item => item.name)
      },
      yAxis: {
        type: 'value',
        name: 'Million $',
        nameLocation: 'middle',
        nameGap: 40
      },
      series: [
        {
          name: 'Allocated Budget',
          type: 'bar',
          data: props.budget.categoryData.map(item => item.allocated),
          itemStyle: {
            color: '#3b82f6'
          }
        },
        {
          name: 'Actual Spent',
          type: 'bar',
          data: props.budget.categoryData.map(item => item.spent),
          itemStyle: {
            color: '#10b981'
          }
        }
      ]
    }
    
    categoryChart.setOption(option)
  }
}

onMounted(() => {
  initPhaseChart()
  
  window.addEventListener('resize', () => {
    if (phaseChart) phaseChart.resize()
    if (categoryChart) categoryChart.resize()
  })
})

watch(activeBudgetTab, (newValue) => {
  if (newValue === 'by-phase') {
    setTimeout(() => {
      initPhaseChart()
    }, 0)
  } else if (newValue === 'by-category') {
    setTimeout(() => {
      initCategoryChart()
    }, 0)
  }
})

// Clean up charts when component is unmounted
onUnmounted(() => {
  if (phaseChart) phaseChart.dispose()
  if (categoryChart) categoryChart.dispose()
  window.removeEventListener('resize', () => {
    if (phaseChart) phaseChart.resize()
    if (categoryChart) categoryChart.resize()
  })
})
</script>