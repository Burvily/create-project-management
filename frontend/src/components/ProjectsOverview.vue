<template>
  <div ref="chartContainer" class="h-[300px]"></div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import * as echarts from 'echarts'

const chartContainer = ref(null)
let chart = null

const data = [
  { month: "Jan", planned: 10, actual: 8 },
  { month: "Feb", planned: 20, actual: 18 },
  { month: "Mar", planned: 30, actual: 28 },
  { month: "Apr", planned: 40, actual: 35 },
  { month: "May", planned: 50, actual: 45 },
  { month: "Jun", planned: 60, actual: 55 },
  { month: "Jul", planned: 70, actual: 65 },
  { month: "Aug", planned: 80, actual: 75 },
  { month: "Sep", planned: 90, actual: 85 },
  { month: "Oct", planned: 100, actual: 95 },
]

onMounted(() => {
  if (chartContainer.value) {
    chart = echarts.init(chartContainer.value)
    
    const option = {
      tooltip: {
        trigger: 'axis',
        axisPointer: {
          type: 'shadow'
        }
      },
      legend: {
        data: ['Planned Progress', 'Actual Progress']
      },
      grid: {
        left: '3%',
        right: '4%',
        bottom: '3%',
        containLabel: true
      },
      xAxis: {
        type: 'category',
        data: data.map(item => item.month)
      },
      yAxis: {
        type: 'value'
      },
      series: [
        {
          name: 'Planned Progress',
          type: 'bar',
          data: data.map(item => item.planned),
          itemStyle: {
            color: '#3b82f6'
          }
        },
        {
          name: 'Actual Progress',
          type: 'bar',
          data: data.map(item => item.actual),
          itemStyle: {
            color: '#10b981'
          }
        }
      ]
    }
    
    chart.setOption(option)
    
    window.addEventListener('resize', () => {
      chart.resize()
    })
  }
})

// Clean up chart when component is unmounted
onUnmounted(() => {
  if (chart) {
    chart.dispose()
    window.removeEventListener('resize', chart.resize)
  }
})
</script>