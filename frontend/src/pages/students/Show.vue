<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'
import { 
  ArrowLeft, 
  Edit, 
  Trash2, 
  User, 
  MapPin, 
  Calendar, 
  BookOpen, 
  Phone,
  Wallet
} from 'lucide-vue-next'
import SlideOver from '@/components/SlideOver.vue'
import StudentForm from '@/components/StudentForm.vue'
import { useToast } from '@/composables/useToast'

const router = useRouter()
const route = useRoute()
const toast = useToast()

const student = ref(null)
const loading = ref(true)
const isEditFormOpen = ref(false)

const fetchStudent = async () => {
  try {
    const response = await axios.get(`/students/${route.params.id}`)
    student.value = response.data.student
  } catch (error) {
    toast.error('O\'quvchi ma\'lumotlarini yuklab bo\'lmadi')
  } finally {
    loading.value = false
  }
}

const deleteStudent = async () => {
  if (!confirm('Haqiqatan ham ushbu o\'quvchini o\'chirmoqchimisiz?')) return
  
  try {
    await axios.delete(`/students/${student.value.id}`)
    toast.success('O\'quvchi o\'chirildi')
    router.push('/students')
  } catch (error) {
    toast.error(error.response?.data?.message || 'Xatolik yuz berdi')
  }
}

const onFormSaved = () => {
  isEditFormOpen.value = false
  fetchStudent() // refresh data
}

onMounted(() => {
  fetchStudent()
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

const formatMoney = (amount) => {
  return new Intl.NumberFormat('uz-UZ').format(amount || 0) + ' UZS'
}
</script>

<template>
  <div class="space-y-6 max-w-7xl mx-auto pb-10">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div class="flex items-center space-x-4">
        <router-link to="/students" class="p-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors shadow-sm">
          <ArrowLeft class="w-5 h-5" />
        </router-link>
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">O'quvchi Profili</h1>
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">ID: {{ student?.student_id || '...' }}</p>
        </div>
      </div>
      <div class="flex space-x-3" v-if="!loading">
        <button 
          @click="isEditFormOpen = true"
          class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-lg text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors"
        >
          <Edit class="w-4 h-4 mr-2" />
          Tahrirlash
        </button>
        <button 
          @click="deleteStudent"
          class="inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
        >
          <Trash2 class="w-4 h-4 mr-2" />
          O'chirish
        </button>
      </div>
    </div>

    <div v-if="loading" class="animate-pulse space-y-6">
      <div class="h-48 bg-white dark:bg-gray-800 rounded-2xl"></div>
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="h-64 bg-white dark:bg-gray-800 rounded-2xl"></div>
        <div class="lg:col-span-2 h-64 bg-white dark:bg-gray-800 rounded-2xl"></div>
      </div>
    </div>

    <template v-else>
      <!-- Profile Header Card -->
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex flex-col sm:flex-row items-start sm:items-center gap-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-primary-50 dark:bg-primary-900/10 rounded-full blur-3xl -mr-20 -mt-20"></div>
        
        <div class="relative w-24 h-24 sm:w-32 sm:h-32 rounded-2xl bg-gray-100 dark:bg-gray-700 border-4 border-white dark:border-gray-800 shadow-md flex items-center justify-center overflow-hidden shrink-0 z-10">
          <img v-if="student.photo" :src="`/storage/${student.photo}`" class="w-full h-full object-cover" />
          <span v-else class="text-4xl font-bold text-gray-400">{{ student.first_name.charAt(0) }}</span>
        </div>
        
        <div class="relative z-10 flex-1">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
              <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ student.first_name }} {{ student.last_name }}</h2>
              <div class="flex items-center gap-4 mt-2 text-sm text-gray-500 dark:text-gray-400">
                <span class="flex items-center"><BookOpen class="w-4 h-4 mr-1" /> {{ student.grade?.name }} - {{ student.section?.name || 'Guruhsiz' }}</span>
                <span class="flex items-center"><Calendar class="w-4 h-4 mr-1" /> {{ student.admission_date }}</span>
              </div>
            </div>
            <div>
              <span :class="['inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium', getStatusBadge(student.status)]">
                {{ getStatusText(student.status) }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Info -->
        <div class="space-y-6">
          <!-- Personal Info -->
          <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Shaxsiy ma'lumotlar</h3>
            <ul class="space-y-4">
              <li class="flex items-start">
                <User class="w-5 h-5 text-gray-400 mr-3 mt-0.5" />
                <div>
                  <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Jinsi</p>
                  <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ student.gender === 'male' ? 'O\'g\'il bola' : 'Qiz bola' }}</p>
                </div>
              </li>
              <li class="flex items-start">
                <Calendar class="w-5 h-5 text-gray-400 mr-3 mt-0.5" />
                <div>
                  <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tug'ilgan sana</p>
                  <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ student.date_of_birth || 'Kiritilmagan' }}</p>
                </div>
              </li>
              <li class="flex items-start">
                <MapPin class="w-5 h-5 text-gray-400 mr-3 mt-0.5" />
                <div>
                  <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Manzil</p>
                  <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ student.address || 'Kiritilmagan' }}</p>
                </div>
              </li>
            </ul>
          </div>

          <!-- Parents Info -->
          <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Ota-ona ma'lumotlari</h3>
            <div v-if="student.parent_info" class="space-y-5">
              <div v-if="student.parent_info.father_name">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Otasining F.I.SH</p>
                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ student.parent_info.father_name }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 flex items-center"><Phone class="w-3 h-3 mr-1"/> {{ student.parent_info.father_phone || 'Yo\'q' }}</p>
              </div>
              <div v-if="student.parent_info.mother_name">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Onasining F.I.SH</p>
                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ student.parent_info.mother_name }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 flex items-center"><Phone class="w-3 h-3 mr-1"/> {{ student.parent_info.mother_phone || 'Yo\'q' }}</p>
              </div>
            </div>
            <div v-else class="text-sm text-gray-500">
              Ma'lumotlar kiritilmagan
            </div>
          </div>
        </div>

        <!-- Right Column: Payments & Actions -->
        <div class="lg:col-span-2 space-y-6">
          <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex flex-col h-full">
            <div class="flex items-center justify-between mb-6">
              <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                <Wallet class="w-5 h-5 mr-2 text-primary-500" />
                So'nggi to'lovlar
              </h3>
              <button class="text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400">
                To'lov qo'shish
              </button>
            </div>

            <div class="flex-1">
              <div v-if="!student.payments || student.payments.length === 0" class="flex flex-col items-center justify-center h-48 text-gray-500">
                <Wallet class="w-12 h-12 mb-3 opacity-20" />
                <p>Hali to'lovlar amalga oshirilmagan</p>
              </div>
              <div v-else class="space-y-3">
                <div v-for="payment in student.payments" :key="payment.id" class="flex items-center justify-between p-4 rounded-xl bg-gray-50 dark:bg-gray-700/30 border border-gray-100 dark:border-gray-700">
                  <div>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ payment.month }}/{{ payment.year }} oyi uchun</p>
                    <p class="text-xs text-gray-500 mt-1">{{ new Date(payment.created_at).toLocaleDateString('uz-UZ') }} da to'langan</p>
                  </div>
                  <div class="text-right">
                    <p class="font-bold text-gray-900 dark:text-white">+{{ formatMoney(payment.paid_amount) }}</p>
                    <span v-if="payment.status === 'paid'" class="inline-flex mt-1 items-center px-2 py-0.5 rounded text-[10px] font-medium bg-emerald-100 text-emerald-800 uppercase">To'liq</span>
                    <span v-else class="inline-flex mt-1 items-center px-2 py-0.5 rounded text-[10px] font-medium bg-amber-100 text-amber-800 uppercase">Qisman</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </template>

    <!-- Slide-over for Edit -->
    <SlideOver 
      v-model="isEditFormOpen" 
      title="O'quvchini tahrirlash"
      width="max-w-md sm:max-w-lg"
    >
      <StudentForm 
        v-if="isEditFormOpen"
        :student-id="student.id"
        @saved="onFormSaved"
        @cancel="isEditFormOpen = false"
      />
    </SlideOver>

  </div>
</template>
