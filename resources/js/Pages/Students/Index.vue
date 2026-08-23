<template>
  <AdminLayout>
    <template #header>O'quvchilar</template>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-800">O'quvchilar ro'yxati</h3>
        
        <Link href="/students/create" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
          <i class="fas fa-plus mr-2"></i> Yangi o'quvchi
        </Link>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID / O'quvchi</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sinf</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Holat</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amallar</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="student in students.data" :key="student.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ student.first_name }} {{ student.last_name }}</div>
                    <div class="text-xs text-gray-500">ID: {{ student.student_id }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ student.grade?.name }}</div>
                <div class="text-xs text-gray-500">{{ student.section?.name }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800" v-if="student.status === 'active'">Faol</span>
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800" v-else>{{ student.status }}</span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <Link :href="`/students/${student.id}/edit`" class="text-indigo-600 hover:text-indigo-900 mr-3"><i class="fas fa-edit"></i></Link>
                <button @click="deleteItem(student.id)" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
              </td>
            </tr>
            <tr v-if="students.data.length === 0">
              <td colspan="4" class="px-6 py-4 text-center text-gray-500">Ma'lumot topilmadi</td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
          <div class="text-sm text-gray-500">Jami: {{ students.total }} ta</div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    students: Object,
    grades: Array,
});

const deleteItem = (id) => {
    if (confirm('Tasdiqlaysizmi?')) {
        router.delete(`/students/${id}`);
    }
};
</script>
