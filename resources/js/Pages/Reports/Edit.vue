<template>
  <AdminLayout>
    <template #header>Reports - Edit</template>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center">
      <i class="fas fa-tools text-indigo-300 text-6xl mb-4"></i>
      <h3 class="text-xl font-bold text-gray-800 mb-2">Ushbu sahifa Vue 3 ga o'tkazilmoqda...</h3>
      <p class="text-gray-500 mb-6">Bu sahifa hozirda qayta yozilmoqda. Tez orada to'liq ishga tushadi.</p>
      
      <div class="flex justify-center">
        <Link href="/dashboard" class="px-4 py-2 bg-indigo-600 text-white rounded-md font-medium shadow-sm hover:bg-indigo-700">
          Bosh sahifaga qaytish
        </Link>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps(Object); // Accept any props passed from controller
</script>
