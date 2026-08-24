<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'
import { 
  ArrowLeft, 
  Save, 
  User, 
  Users, 
  BookOpen, 
  Phone,
  Image as ImageIcon
} from 'lucide-vue-next'

const router = useRouter()
const route = useRoute()
const loading = ref(true)
const submitting = ref(false)
const error = ref('')

const grades = ref([])
const sections = ref([])
const academicYears = ref([])

const form = ref({
  first_name: '',
  last_name: '',
  gender: 'male',
  date_of_birth: '',
  address: '',
  grade_id: '',
  section_id: '',
  academic_year_id: '',
  admission_date: '',
  status: 'active',
  
  father_name: '',
  father_phone: '',
  mother_name: '',
  mother_phone: '',
  parent_address: ''
})

const photoFile = ref(null)
const photoPreview = ref(null)
const currentPhotoUrl = ref(null)

const fetchFormData = async () => {
  try {
    const response = await axios.get(`/students/${route.params.id}/edit`)
    const data = response.data
    
    grades.value = data.grades
    sections.value = data.sections
    academicYears.value = data.academicYears
    
    const student = data.student
    form.value = {
      first_name: student.first_name,
      last_name: student.last_name,
      gender: student.gender,
      date_of_birth: student.date_of_birth || '',
      address: student.address || '',
      grade_id: student.grade_id,
      section_id: student.section_id,
      academic_year_id: student.academic_year_id,
      admission_date: student.admission_date || '',
      status: student.status,
      
      father_name: student.parent_info?.father_name || '',
      father_phone: student.parent_info?.father_phone || '',
      mother_name: student.parent_info?.mother_name || '',
      mother_phone: student.parent_info?.mother_phone || '',
      parent_address: student.parent_info?.address || ''
    }
    
    if (student.photo) {
      // Assuming storage path
      currentPhotoUrl.value = `/storage/${student.photo}`
    }
    
  } catch (err) {
    console.error('Error fetching student data', err)
    error.value = "Ma'lumotlarni yuklashda xatolik yuz berdi"
  } finally {
    loading.value = false
  }
}

const fetchSections = async () => {
  if (!form.value.grade_id) {
    sections.value = []
    form.value.section_id = ''
    return
  }
  try {
    const response = await axios.get(`/grades/${form.value.grade_id}/sections`)
    sections.value = response.data
    form.value.section_id = '' // reset section
  } catch (err) {
    console.error('Error fetching sections', err)
  }
}

const handlePhotoUpload = (e) => {
  const file = e.target.files[0]
  if (!file) return
  
  photoFile.value = file
  const reader = new FileReader()
  reader.onload = (e) => {
    photoPreview.value = e.target.result
  }
  reader.readAsDataURL(file)
}

const submitForm = async () => {
  submitting.value = true
  error.value = ''
  
  const formData = new FormData()
  // Add _method=PUT for Laravel to parse FormData as PUT request
  formData.append('_method', 'PUT')
  
  Object.keys(form.value).forEach(key => {
    if (form.value[key] !== null && form.value[key] !== '') {
      formData.append(key, form.value[key])
    }
  })
  
  if (photoFile.value) {
    formData.append('photo', photoFile.value)
  }
  
  try {
    await axios.post(`/students/${route.params.id}`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
    router.push('/students')
  } catch (err) {
    error.value = err.response?.data?.message || "Xatolik yuz berdi, ma'lumotlarni tekshiring."
    console.error(err)
  } finally {
    submitting.value = false
  }
}

onMounted(() => {
  fetchFormData()
})
</script>

<template>
  <div class="space-y-6 max-w-5xl mx-auto pb-10">
    <div class="flex items-center justify-between">
      <div class="flex items-center space-x-4">
        <router-link to="/students" class="p-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors shadow-sm">
          <ArrowLeft class="w-5 h-5" />
        </router-link>
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">O'quvchini tahrirlash</h1>
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">O'quvchi ma'lumotlarini o'zgartirish formasi</p>
        </div>
      </div>
      <button 
        @click="submitForm"
        :disabled="submitting"
        class="inline-flex items-center justify-center px-5 py-2.5 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all disabled:opacity-70"
      >
        <Save class="w-4 h-4 mr-2" />
        {{ submitting ? 'Saqlanmoqda...' : 'Saqlash' }}
      </button>
    </div>

    <div v-if="error" class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
      <p class="text-sm text-red-700">{{ error }}</p>
    </div>

    <div v-if="loading" class="animate-pulse space-y-6">
      <div class="h-64 bg-white dark:bg-gray-800 rounded-2xl"></div>
      <div class="h-64 bg-white dark:bg-gray-800 rounded-2xl"></div>
    </div>

    <form v-else @submit.prevent="submitForm" class="space-y-6">
      
      <!-- Section 1: Personal Info -->
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
            <User class="w-5 h-5 mr-2 text-primary-500" />
            Shaxsiy ma'lumotlar
          </h3>
        </div>
        
        <div class="p-6">
          <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <div class="lg:col-span-1 flex flex-col items-center">
              <div class="relative group">
                <div class="w-32 h-32 rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center bg-gray-50 dark:bg-gray-700 overflow-hidden">
                  <img v-if="photoPreview" :src="photoPreview" class="w-full h-full object-cover" />
                  <img v-else-if="currentPhotoUrl" :src="currentPhotoUrl" class="w-full h-full object-cover" />
                  <ImageIcon v-else class="w-10 h-10 text-gray-400" />
                </div>
                <label for="photo-upload" class="absolute inset-0 w-full h-full bg-black/50 flex items-center justify-center rounded-2xl opacity-0 group-hover:opacity-100 cursor-pointer transition-opacity text-white text-sm font-medium">
                  Rasm o'zgartirish
                </label>
                <input id="photo-upload" type="file" accept="image/*" class="hidden" @change="handlePhotoUpload" />
              </div>
              <p class="text-xs text-gray-500 mt-3 text-center">Tavsiya etiladi: 1:1 format, max 2MB</p>
            </div>

            <div class="lg:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ism <span class="text-red-500">*</span></label>
                <input v-model="form.first_name" type="text" required class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white sm:text-sm">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Familiya <span class="text-red-500">*</span></label>
                <input v-model="form.last_name" type="text" required class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white sm:text-sm">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tug'ilgan sana</label>
                <input v-model="form.date_of_birth" type="date" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white sm:text-sm">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jinsi <span class="text-red-500">*</span></label>
                <div class="flex space-x-4 mt-2">
                  <label class="inline-flex items-center">
                    <input type="radio" v-model="form.gender" value="male" class="text-primary-600 focus:ring-primary-500">
                    <span class="ml-2 text-gray-700 dark:text-gray-300 text-sm">O'g'il</span>
                  </label>
                  <label class="inline-flex items-center">
                    <input type="radio" v-model="form.gender" value="female" class="text-primary-600 focus:ring-primary-500">
                    <span class="ml-2 text-gray-700 dark:text-gray-300 text-sm">Qiz</span>
                  </label>
                </div>
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Yashash manzili</label>
                <textarea v-model="form.address" rows="2" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white sm:text-sm"></textarea>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Section 2: Academic Info -->
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
            <BookOpen class="w-5 h-5 mr-2 text-primary-500" />
            Ta'lim ma'lumotlari
          </h3>
        </div>
        
        <div class="p-6">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">O'quv yili <span class="text-red-500">*</span></label>
              <select v-model="form.academic_year_id" required class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white sm:text-sm">
                <option v-for="year in academicYears" :key="year.id" :value="year.id">{{ year.name }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Qabul sana <span class="text-red-500">*</span></label>
              <input v-model="form.admission_date" type="date" required class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white sm:text-sm">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sinf <span class="text-red-500">*</span></label>
              <select v-model="form.grade_id" @change="fetchSections" required class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white sm:text-sm">
                <option value="" disabled>Sinfni tanlang</option>
                <option v-for="grade in grades" :key="grade.id" :value="grade.id">{{ grade.name }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Guruh <span class="text-red-500">*</span></label>
              <select v-model="form.section_id" required class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white sm:text-sm" :disabled="!form.grade_id">
                <option value="" disabled>Guruhni tanlang</option>
                <option v-for="section in sections" :key="section.id" :value="section.id">{{ section.name }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status <span class="text-red-500">*</span></label>
              <select v-model="form.status" required class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white sm:text-sm">
                <option value="active">Faol</option>
                <option value="graduated">Bitirgan</option>
                <option value="expelled">Chetlatilgan</option>
                <option value="transferred">Ko'chirilgan</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <!-- Section 3: Parents Info -->
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
            <Users class="w-5 h-5 mr-2 text-primary-500" />
            Ota-ona ma'lumotlari
          </h3>
        </div>
        
        <div class="p-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
              <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Otasining ma'lumotlari</h4>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">F.I.SH</label>
                <input v-model="form.father_name" type="text" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white sm:text-sm">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telefon raqam</label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <Phone class="h-4 w-4 text-gray-400" />
                  </div>
                  <input v-model="form.father_phone" type="text" placeholder="+998" class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white sm:text-sm">
                </div>
              </div>
            </div>
            
            <div class="space-y-4">
              <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Onasining ma'lumotlari</h4>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">F.I.SH</label>
                <input v-model="form.mother_name" type="text" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white sm:text-sm">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telefon raqam</label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <Phone class="h-4 w-4 text-gray-400" />
                  </div>
                  <input v-model="form.mother_phone" type="text" placeholder="+998" class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white sm:text-sm">
                </div>
              </div>
            </div>

            <div class="md:col-span-2 mt-2">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ota-ona manzili (agar boshqa bo'lsa)</label>
              <textarea v-model="form.parent_address" rows="2" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white sm:text-sm"></textarea>
            </div>
          </div>
        </div>
      </div>

    </form>
  </div>
</template>
