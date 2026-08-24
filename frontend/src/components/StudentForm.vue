<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import axios from 'axios'
import { Save, Image as ImageIcon } from 'lucide-vue-next'
import { useToast } from '@/composables/useToast'

const props = defineProps({
  studentId: {
    type: [Number, String],
    default: null
  }
})

const emit = defineEmits(['saved', 'cancel'])
const toast = useToast()

const loading = ref(true)
const submitting = ref(false)

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
  admission_date: new Date().toISOString().split('T')[0],
  status: 'active',
  father_name: '',
  father_phone: '',
  mother_name: '',
  mother_phone: '',
  parent_address: '',
  pinfl: '',
  passport_series: '',
  passport_number: ''
})

const photoFile = ref(null)
const photoPreview = ref(null)
const currentPhotoUrl = ref(null)

const isEdit = computed(() => !!props.studentId)

const fetchData = async () => {
  loading.value = true
  try {
    const url = isEdit.value ? `/students/${props.studentId}/edit` : '/students/create'
    const response = await axios.get(url)
    const data = response.data
    
    grades.value = data.grades
    academicYears.value = data.academicYears
    
    if (isEdit.value) {
      sections.value = data.sections
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
        parent_address: student.parent_info?.address || '',
        pinfl: student.parent_info?.pinfl || '',
        passport_series: student.parent_info?.passport_series || '',
        passport_number: student.parent_info?.passport_number || ''
      }
      if (student.photo) currentPhotoUrl.value = `/storage/${student.photo}`
    } else {
      if (data.currentYear) form.value.academic_year_id = data.currentYear.id
    }
  } catch (err) {
    toast.error("Ma'lumotlarni yuklashda xatolik yuz berdi")
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
    form.value.section_id = ''
  } catch (err) {}
}

const handlePhotoUpload = (e) => {
  const file = e.target.files[0]
  if (!file) return
  photoFile.value = file
  const reader = new FileReader()
  reader.onload = (e) => photoPreview.value = e.target.result
  reader.readAsDataURL(file)
}

const submitForm = async () => {
  submitting.value = true
  
  const formData = new FormData()
  if (isEdit.value) formData.append('_method', 'PUT')
  
  Object.keys(form.value).forEach(key => {
    if (form.value[key] !== null && form.value[key] !== '') {
      formData.append(key, form.value[key])
    }
  })
  
  if (photoFile.value) formData.append('photo', photoFile.value)
  
  try {
    const url = isEdit.value ? `/students/${props.studentId}` : '/students'
    const res = await axios.post(url, formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })
    toast.success(res.data.message || 'Muvaffaqiyatli saqlandi!')
    emit('saved')
  } catch (err) {
    toast.error(err.response?.data?.message || "Xatolik yuz berdi")
  } finally {
    submitting.value = false
  }
}

watch(() => props.studentId, () => {
  fetchData()
}, { immediate: true })
</script>

<template>
  <div v-if="loading" class="animate-pulse space-y-6">
    <div class="h-32 bg-gray-200 dark:bg-gray-700 rounded-xl"></div>
    <div class="h-32 bg-gray-200 dark:bg-gray-700 rounded-xl"></div>
  </div>
  
  <form v-else @submit.prevent="submitForm" class="space-y-6">
    <!-- Image & Basic -->
    <div class="flex flex-col items-center">
      <div class="relative group mb-4">
        <div class="w-24 h-24 rounded-full border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center bg-gray-50 dark:bg-gray-700 overflow-hidden">
          <img v-if="photoPreview" :src="photoPreview" class="w-full h-full object-cover" />
          <img v-else-if="currentPhotoUrl" :src="currentPhotoUrl" class="w-full h-full object-cover" />
          <ImageIcon v-else class="w-8 h-8 text-gray-400" />
        </div>
        <label class="absolute inset-0 w-full h-full bg-black/50 flex items-center justify-center rounded-full opacity-0 group-hover:opacity-100 cursor-pointer transition-opacity text-white text-xs font-medium">
          Rasm
          <input type="file" accept="image/*" class="hidden" @change="handlePhotoUpload" />
        </label>
      </div>
      <div class="w-full grid grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Ism <span class="text-red-500">*</span></label>
          <input v-model="form.first_name" type="text" required class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 sm:text-sm">
        </div>
        <div>
          <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Familiya <span class="text-red-500">*</span></label>
          <input v-model="form.last_name" type="text" required class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 sm:text-sm">
        </div>
      </div>
    </div>

    <!-- Academic -->
    <div class="border-t border-gray-100 dark:border-gray-700 pt-4">
      <h4 class="text-sm font-semibold mb-3">Ta'lim</h4>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-medium mb-1">O'quv yili</label>
          <select v-model="form.academic_year_id" required class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 sm:text-sm">
            <option v-for="year in academicYears" :key="year.id" :value="year.id">{{ year.name }}</option>
          </select>
        </div>
        <div v-if="isEdit">
          <label class="block text-xs font-medium mb-1">Status</label>
          <select v-model="form.status" required class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 sm:text-sm">
            <option value="active">Faol</option>
            <option value="graduated">Bitirgan</option>
            <option value="expelled">Chetlatilgan</option>
          </select>
        </div>
        <div>
          <label class="block text-xs font-medium mb-1">Sinf</label>
          <select v-model="form.grade_id" @change="fetchSections" required class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 sm:text-sm">
            <option value="" disabled>Tanlang</option>
            <option v-for="grade in grades" :key="grade.id" :value="grade.id">{{ grade.name }}</option>
          </select>
        </div>
        <div>
          <label class="block text-xs font-medium mb-1">Guruh</label>
          <select v-model="form.section_id" required class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 sm:text-sm" :disabled="!form.grade_id">
            <option value="" disabled>Tanlang</option>
            <option v-for="section in sections" :key="section.id" :value="section.id">{{ section.name }}</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Parent Info (Compact) -->
    <div class="border-t border-gray-100 dark:border-gray-700 pt-4">
      <h4 class="text-sm font-semibold mb-3">Ota-ona yoki Vasiy</h4>
      
      <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
          <label class="block text-xs font-medium mb-1">Otasining / Vasiy F.I.SH</label>
          <input v-model="form.father_name" type="text" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 sm:text-sm">
        </div>
        <div>
          <label class="block text-xs font-medium mb-1">Telefon</label>
          <input v-model="form.father_phone" type="text" placeholder="+998..." class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 sm:text-sm">
        </div>
      </div>

      <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
          <label class="block text-xs font-medium mb-1">Onasining F.I.SH</label>
          <input v-model="form.mother_name" type="text" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 sm:text-sm">
        </div>
        <div>
          <label class="block text-xs font-medium mb-1">Telefon (Ona)</label>
          <input v-model="form.mother_phone" type="text" placeholder="+998..." class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 sm:text-sm">
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
        <div class="sm:col-span-2">
          <label class="block text-xs font-medium mb-1">JSHSHIR (PINFL)</label>
          <input v-model="form.pinfl" type="text" maxlength="14" placeholder="14 xonali raqam" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 sm:text-sm">
        </div>
      </div>

      <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-4">
        <div class="sm:col-span-1">
          <label class="block text-xs font-medium mb-1">Pasport Seriyasi</label>
          <input v-model="form.passport_series" type="text" maxlength="2" placeholder="AA" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 sm:text-sm uppercase">
        </div>
        <div class="sm:col-span-2">
          <label class="block text-xs font-medium mb-1">Pasport Raqami</label>
          <input v-model="form.passport_number" type="text" maxlength="7" placeholder="1234567" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 sm:text-sm">
        </div>
      </div>
    </div>

    <div class="pt-4 flex justify-end space-x-3 border-t border-gray-100 dark:border-gray-700 mt-6">
      <button type="button" @click="$emit('cancel')" class="px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
        Bekor qilish
      </button>
      <button type="submit" :disabled="submitting" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-70">
        <Save class="w-4 h-4 mr-2" />
        {{ submitting ? 'Saqlanmoqda...' : 'Saqlash' }}
      </button>
    </div>
  </form>
</template>
