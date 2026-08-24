<script setup>
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'
import { 
  Search, 
  Plus, 
  Filter, 
  MoreVertical,
  ChevronLeft,
  ChevronRight,
  UserCircle,
  Eye,
  Edit,
  Trash2
} from 'lucide-vue-next'

const students = ref([])
const grades = ref([])
const loading = ref(true)
const pagination = ref({
  current_page: 1,
  last_page: 1,
  total: 0
})

const filters = ref({
  search: '',
  grade_id: '',
  status: 'active'
})

const fetchStudents = async (page = 1) => {
  loading.value = true
  try {
    const response = await axios.get('/students', {
      params: {
        page,
        ...filters.value
      }
    })
    students.value = response.data.students.data
    pagination.value = {
      current_page: response.data.students.current_page,
      last_page: response.data.students.last_page,
      total: response.data.students.total,
      links: response.data.students.links
    }
    if(response.data.grades) {
      grades.value = response.data.grades
    }
  } catch (error) {
    console.error('Failed to fetch students', error)
  } finally {
    loading.value = false
  }
}

// Watch filters to trigger fetch
let debounceTimeout
watch(filters, () => {
  clearTimeout(debounceTimeout)
  debounceTimeout = setTimeout(() => {
    fetchStudents(1)
  }, 500)
}, { deep: true })

onMounted(() => {
  fetchStudents()
})

const getStatusBadge = (status) => {
  const badges = {
    'active': 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
    'graduated': 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
    'expelled': 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
    'transferred': 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400'
  }
  return badges[status] || badges['active']
}

const getStatusText = (status) => {
  const texts = {
    'active': 'Faol',
    'graduated': 'Bitirgan',
    'expelled': 'Chetlatilgan',
    'transferred': 'Ko\'chirilgan'
  }
  return texts[status] || status
}
</script>

<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">O'quvchilar</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Jami: <span class="font-bold text-gray-700 dark:text-gray-300">{{ pagination.total }}</span> ta o'quvchi</p>
      </div>
      <div class="flex items-center space-x-3">
        <router-link to="/students/create" class="inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
          <Plus class="w-4 h-4 mr-2" />
          Yangi qo'shish
        </router-link>
      </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Search -->
        <div class="md:col-span-2 relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <Search class="h-4 w-4 text-gray-400" />
          </div>
          <input 
            v-model="filters.search"
            type="text" 
            placeholder="Ism, familiya yoki ID orqali qidirish..." 
            class="block w-full pl-10 pr-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg leading-5 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition-colors"
          >
        </div>
        
        <!-- Filter Class -->
        <div>
          <select v-model="filters.grade_id" class="block w-full pl-3 pr-10 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
            <option value="">Barcha sinflar</option>
            <option v-for="grade in grades" :key="grade.id" :value="grade.id">
              {{ grade.name }}
            </option>
          </select>
        </div>

        <!-- Filter Status -->
        <div>
          <select v-model="filters.status" class="block w-full pl-3 pr-10 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
            <option value="">Barcha holatlar</option>
            <option value="active">Faol</option>
            <option value="graduated">Bitirgan</option>
            <option value="expelled">Chetlatilgan</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
      <div class="overflow-x-auto custom-scrollbar">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead class="bg-gray-50/50 dark:bg-gray-800/50">
            <tr>
              <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-16">#ID</th>
              <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">O'quvchi</th>
              <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sinf</th>
              <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ota-ona (Tel)</th>
              <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Holat</th>
              <th scope="col" class="relative px-6 py-4 w-16"></th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
            <tr v-if="loading">
              <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                Yuklanmoqda...
              </td>
            </tr>
            <tr v-else-if="students.length === 0">
              <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                O'quvchilar topilmadi.
              </td>
            </tr>
            <tr v-for="student in students" :key="student.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                {{ student.student_id }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                    <div class="h-10 w-10 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400 font-bold">
                      {{ student.first_name.charAt(0) }}
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-semibold text-gray-900 dark:text-white">
                      {{ student.first_name }} {{ student.last_name }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                      {{ student.gender === 'male' ? 'O\'g\'il' : 'Qiz' }}
                    </div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ student.grade?.name }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">{{ student.section?.name || 'Biriktirilmagan' }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                <div v-if="student.parent_info">
                  <div>{{ student.parent_info.father_name || student.parent_info.mother_name || 'Kiritilmagan' }}</div>
                  <div class="text-xs">{{ student.parent_info.father_phone || student.parent_info.mother_phone || '-' }}</div>
                </div>
                <div v-else>-</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="['inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium', getStatusBadge(student.status)]">
                  {{ getStatusText(student.status) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex items-center justify-end space-x-2">
                  <button class="p-1.5 text-gray-400 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-lg transition-colors">
                    <Eye class="w-4 h-4" />
                  </button>
                  <button class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors">
                    <Edit class="w-4 h-4" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="pagination.last_page > 1" class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between sm:px-6">
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
          <div>
            <p class="text-sm text-gray-700 dark:text-gray-300">
              Jami <span class="font-medium">{{ pagination.total }}</span> tadan 
              sahifa <span class="font-medium">{{ pagination.current_page }}</span> / {{ pagination.last_page }}
            </p>
          </div>
          <div>
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
              <button 
                @click="fetchStudents(pagination.current_page - 1)"
                :disabled="pagination.current_page === 1"
                class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50"
              >
                <ChevronLeft class="h-5 w-5" />
              </button>
              
              <button 
                v-for="page in pagination.last_page" :key="page"
                @click="fetchStudents(page)"
                :class="[
                  page === pagination.current_page ? 'z-10 bg-primary-50 border-primary-500 text-primary-600 dark:bg-primary-900/20 dark:border-primary-500 dark:text-primary-400' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:hover:bg-gray-700',
                  'relative inline-flex items-center px-4 py-2 border text-sm font-medium'
                ]"
              >
                {{ page }}
              </button>

              <button 
                @click="fetchStudents(pagination.current_page + 1)"
                :disabled="pagination.current_page === pagination.last_page"
                class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50"
              >
                <ChevronRight class="h-5 w-5" />
              </button>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
