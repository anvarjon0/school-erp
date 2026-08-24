<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import VueApexCharts from 'vue3-apexcharts'
import { 
  Users, 
  Wallet, 
  TrendingUp, 
  TrendingDown, 
  AlertCircle,
  Banknote,
  MoreVertical,
  Download
} from 'lucide-vue-next'

const loading = ref(true)
const stats = ref({})

const fetchDashboardData = async () => {
  try {
    const response = await axios.get('/dashboard')
    stats.value = response.data
  } catch (error) {
    console.error('Error fetching dashboard data:', error)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchDashboardData()
})

// Format currency
const formatMoney = (amount) => {
  return new Intl.NumberFormat('uz-UZ').format(amount || 0) + ' UZS'
}

// Chart Options
const chartOptions = computed(() => ({
  chart: {
    type: 'area',
    fontFamily: 'Plus Jakarta Sans, sans-serif',
    toolbar: { show: false },
    zoom: { enabled: false },
    dropShadow: {
      enabled: true,
      top: 10,
      left: 0,
      blur: 10,
      color: '#000',
      opacity: 0.1
    }
  },
  colors: ['#6366f1', '#ef4444'], // Primary and Red
  dataLabels: { enabled: false },
  stroke: { curve: 'smooth', width: 3 },
  fill: {
    type: 'gradient',
    gradient: {
      shadeIntensity: 1,
      opacityFrom: 0.4,
      opacityTo: 0.05,
      stops: [0, 90, 100]
    }
  },
  xaxis: {
    categories: stats.value.chartLabels || [],
    axisBorder: { show: false },
    axisTicks: { show: false },
    labels: {
      style: { colors: '#64748b' }
    }
  },
  yaxis: {
    labels: {
      style: { colors: '#64748b' },
      formatter: (value) => {
        return (value / 1000000).toFixed(1) + 'M'
      }
    }
  },
  grid: {
    borderColor: '#e2e8f0',
    strokeDashArray: 4,
    yaxis: { lines: { show: true } }
  },
  legend: { position: 'top', horizontalAlign: 'right' },
  tooltip: {
    theme: 'light',
    y: {
      formatter: (val) => formatMoney(val)
    }
  }
}))

const chartSeries = computed(() => [
  {
    name: 'Daromad (Kirim)',
    data: stats.value.chartIncome || []
  },
  {
    name: 'Xarajat (Chiqim)',
    data: stats.value.chartExpense || []
  }
])

const kpiCards = computed(() => [
  {
    title: 'Oylik Daromad',
    value: formatMoney(stats.value.monthlyIncome),
    icon: TrendingUp,
    iconBg: 'bg-emerald-100 dark:bg-emerald-900/30',
    iconColor: 'text-emerald-600 dark:text-emerald-400',
    trend: '+12.5%',
    trendUp: true
  },
  {
    title: 'Oylik Xarajat',
    value: formatMoney(stats.value.monthlyExpense),
    icon: TrendingDown,
    iconBg: 'bg-red-100 dark:bg-red-900/30',
    iconColor: 'text-red-600 dark:text-red-400',
    trend: '-2.4%',
    trendUp: false
  },
  {
    title: 'Jami O\'quvchilar',
    value: stats.value.totalStudents || 0,
    icon: Users,
    iconBg: 'bg-primary-100 dark:bg-primary-900/30',
    iconColor: 'text-primary-600 dark:text-primary-400',
    trend: '+4',
    trendUp: true
  },
  {
    title: 'Qarzdorlar (Shu oy)',
    value: stats.value.debtorCount || 0,
    icon: AlertCircle,
    iconBg: 'bg-amber-100 dark:bg-amber-900/30',
    iconColor: 'text-amber-600 dark:text-amber-400',
    trend: 'Oshdi',
    trendUp: false
  }
])

const getStatusBadge = (status) => {
  if (status === 'paid') return 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400'
  if (status === 'partial') return 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400'
  return 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'
}

const getStatusText = (status) => {
  if (status === 'paid') return 'To\'langan'
  if (status === 'partial') return 'Qisman'
  return 'To\'lanmagan'
}
</script>

<template>
  <div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Dashboard</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Tizimning umumiy moliyaviy va o'quv holati</p>
      </div>
      <div class="flex items-center space-x-3">
        <button class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-lg text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
          <Download class="w-4 h-4 mr-2" />
          Hisobot (PDF)
        </button>
      </div>
    </div>

    <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div v-for="i in 4" :key="i" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 animate-pulse">
        <div class="h-12 w-12 bg-gray-200 dark:bg-gray-700 rounded-xl mb-4"></div>
        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/2 mb-2"></div>
        <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-3/4"></div>
      </div>
    </div>

    <!-- KPI Cards -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div v-for="(card, index) in kpiCards" :key="index" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 hover:shadow-md transition-shadow relative overflow-hidden group">
        <!-- Decoration -->
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-full opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
        
        <div class="relative z-10 flex items-center justify-between">
          <div :class="[card.iconBg, card.iconColor, 'p-3 rounded-xl']">
            <component :is="card.icon" class="w-6 h-6" />
          </div>
          <div class="flex items-center space-x-1 text-sm font-medium" :class="card.trendUp ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400'">
            <TrendingUp v-if="card.trendUp" class="w-4 h-4" />
            <TrendingDown v-else class="w-4 h-4" />
            <span>{{ card.trend }}</span>
          </div>
        </div>
        
        <div class="relative z-10 mt-4">
          <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ card.title }}</h3>
          <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ card.value }}</p>
        </div>
      </div>
    </div>

    <!-- Charts and Tables -->
    <div v-if="!loading" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      
      <!-- Chart -->
      <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-lg font-bold text-gray-900 dark:text-white">Moliya Dinamikasi</h2>
          <button class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
            <MoreVertical class="w-5 h-5" />
          </button>
        </div>
        <div class="h-[300px] w-full">
          <VueApexCharts type="area" height="100%" :options="chartOptions" :series="chartSeries" />
        </div>
      </div>

      <!-- Recent Payments Table -->
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex flex-col">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-lg font-bold text-gray-900 dark:text-white">So'nggi to'lovlar</h2>
          <router-link to="/payments" class="text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
            Barchasi
          </router-link>
        </div>

        <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
          <div v-if="stats.recentPayments?.length === 0" class="flex flex-col items-center justify-center h-full text-gray-500 pb-10">
            <Wallet class="w-10 h-10 mb-3 opacity-20" />
            <p>Hozircha to'lovlar yo'q</p>
          </div>
          <div v-else class="space-y-4">
            <div v-for="payment in stats.recentPayments" :key="payment.id" class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group border border-transparent dark:border-gray-700 hover:border-gray-100">
              <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400 font-bold shrink-0">
                  {{ payment.student?.first_name?.charAt(0) || 'O' }}
                </div>
                <div>
                  <p class="text-sm font-semibold text-gray-900 dark:text-white leading-tight">
                    {{ payment.student?.first_name }} {{ payment.student?.last_name }}
                  </p>
                  <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ new Date(payment.created_at).toLocaleDateString('uz-UZ') }}</p>
                </div>
              </div>
              <div class="text-right">
                <p class="text-sm font-bold text-gray-900 dark:text-white">+{{ formatMoney(payment.paid_amount) }}</p>
                <span :class="['inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium uppercase tracking-wider mt-1', getStatusBadge(payment.status)]">
                  {{ getStatusText(payment.status) }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>
