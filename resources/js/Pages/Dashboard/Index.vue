<template>
  <AdminLayout>
    <template #header>Boshqaruv paneli</template>

    <div>
      <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Xush kelibsiz, {{ $page.props.auth.user.name }}!</h2>
        <p class="text-sm text-gray-500 mt-1">Tizimdagi so'nggi ma'lumotlar va qisqacha hisobotlar</p>
      </div>

      <!-- KPI Cards for Admins/Accountants -->
      <div v-if="isAdminOrAccountant" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Students -->
        <div v-if="canViewStudents" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center hover:shadow-md transition-shadow">
          <div class="p-4 rounded-full bg-blue-50 text-blue-500 mr-4">
            <i class="fas fa-user-graduate text-2xl"></i>
          </div>
          <div>
            <p class="text-sm font-medium text-gray-500">Jami O'quvchilar</p>
            <p class="text-2xl font-bold text-gray-800">{{ totalStudents }}</p>
          </div>
        </div>

        <!-- Income -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center hover:shadow-md transition-shadow">
          <div class="p-4 rounded-full bg-green-50 text-green-500 mr-4">
            <i class="fas fa-coins text-2xl"></i>
          </div>
          <div>
            <p class="text-sm font-medium text-gray-500">Bu Oydagi Daromad</p>
            <p class="text-2xl font-bold text-gray-800">{{ formatCurrency(monthlyIncome) }}</p>
          </div>
        </div>

        <!-- Expense -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center hover:shadow-md transition-shadow">
          <div class="p-4 rounded-full bg-red-50 text-red-500 mr-4">
            <i class="fas fa-file-invoice-dollar text-2xl"></i>
          </div>
          <div>
            <p class="text-sm font-medium text-gray-500">Bu Oydagi Xarajat</p>
            <p class="text-2xl font-bold text-gray-800">{{ formatCurrency(monthlyExpense) }}</p>
          </div>
        </div>

        <!-- Debtors -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center hover:shadow-md transition-shadow">
          <div class="p-4 rounded-full bg-orange-50 text-orange-500 mr-4">
            <i class="fas fa-exclamation-triangle text-2xl"></i>
          </div>
          <div>
            <p class="text-sm font-medium text-gray-500">Qarzdorlar</p>
            <p class="text-2xl font-bold text-gray-800">{{ debtorCount }}</p>
          </div>
        </div>

      </div>

      <!-- Main Content Grid -->
      <div v-if="isAdminOrAccountant" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Chart (Placeholder for now, usually requires chart.js integration in Vue) -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
          <h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-4 mb-4">6 Oylik Daromad va Xarajat</h3>
          <div class="h-72 flex items-center justify-center bg-gray-50 rounded border border-dashed border-gray-200">
             <p class="text-gray-400">Grafikni ko'rish (Tez kunda vue-chartjs orqali)</p>
          </div>
        </div>

        <!-- Recent Payments -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
          <div class="flex justify-between items-center border-b border-gray-100 pb-4 mb-4">
            <h3 class="text-lg font-bold text-gray-800">So'nggi To'lovlar</h3>
            <Link href="/payments" class="text-sm text-indigo-600 font-medium hover:text-indigo-800">Barchasi</Link>
          </div>
          
          <div class="space-y-4">
            <div v-if="recentPayments.length === 0" class="text-center text-gray-500 py-4">
              Hozircha to'lovlar yo'q
            </div>
            
            <div v-for="payment in recentPayments" :key="payment.id" class="flex justify-between items-center p-3 hover:bg-gray-50 rounded-lg transition-colors border border-transparent hover:border-gray-100">
              <div class="flex items-center">
                <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center mr-3 font-bold text-sm">
                  <i class="fas fa-check"></i>
                </div>
                <div>
                  <p class="text-sm font-bold text-gray-800">{{ payment.student?.full_name || 'Noma\'lum' }}</p>
                  <p class="text-xs text-gray-500">{{ new Date(payment.created_at).toLocaleString('uz-UZ') }}</p>
                </div>
              </div>
              <div class="text-right">
                <span class="inline-block px-2 py-1 bg-green-50 text-green-700 text-xs font-bold rounded">+{{ formatCurrency(payment.paid_amount) }}</span>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>
  </AdminLayout>
</template>

<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    totalStudents: Number,
    monthlyIncome: Number,
    monthlyExpense: Number,
    debtorCount: Number,
    recentPayments: Array,
    chartLabels: Array,
    chartIncome: Array,
    chartExpense: Array,
    
    // Teacher specific
    myStudents: Number,
    todayPresent: Number,
    todayAbsent: Number,
    todayAttendance: Number,
});

const page = usePage();
const userRoles = page.props.auth.user.roles || []; // Assuming relationships are eager loaded or passed

const isAdminOrAccountant = true; // In a real app, calculate based on roles array
const canViewStudents = true; 

const formatCurrency = (value) => {
    return new Intl.NumberFormat('uz-UZ').format(value || 0) + ' UZS';
};
</script>
