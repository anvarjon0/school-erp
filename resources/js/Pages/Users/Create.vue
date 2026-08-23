<template>
  <AdminLayout>
    <template #header>Yangi xodim qo'shish</template>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 max-w-3xl mx-auto overflow-hidden">
      <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-800">Xodim ma'lumotlari</h3>
        <Link href="/users" class="text-gray-500 hover:text-gray-700">
          <i class="fas fa-arrow-left mr-1"></i> Ortga
        </Link>
      </div>

      <div class="p-6">
        <form @submit.prevent="submit" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Name -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">F.I.SH. *</label>
              <input v-model="form.name" type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
              <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</p>
            </div>

            <!-- Email -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
              <input v-model="form.email" type="email" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
              <p v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</p>
            </div>

            <!-- Phone -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Telefon</label>
              <input v-model="form.phone" type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="+998901234567">
              <p v-if="form.errors.phone" class="text-red-500 text-xs mt-1">{{ form.errors.phone }}</p>
            </div>

            <!-- Role -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Rol *</label>
              <select v-model="form.role_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                <option value="" disabled>Tanlang...</option>
                <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.display_name }}</option>
              </select>
              <p v-if="form.errors.role_id" class="text-red-500 text-xs mt-1">{{ form.errors.role_id }}</p>
            </div>

            <!-- Salary -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Asosiy Maosh (UZS)</label>
              <input v-model="form.base_salary" type="number" min="0" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
              <p v-if="form.errors.base_salary" class="text-red-500 text-xs mt-1">{{ form.errors.base_salary }}</p>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-100">
            <!-- Password -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Parol *</label>
              <input v-model="form.password" type="password" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
              <p v-if="form.errors.password" class="text-red-500 text-xs mt-1">{{ form.errors.password }}</p>
            </div>

            <!-- Password Confirm -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Parolni tasdiqlash *</label>
              <input v-model="form.password_confirmation" type="password" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
            </div>
          </div>

          <div class="flex justify-end pt-6">
            <Link href="/users" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 mr-3">
              Bekor qilish
            </Link>
            <button type="submit" :disabled="form.processing" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
              Saqlash
            </button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    roles: Array
});

const form = useForm({
    name: '',
    email: '',
    phone: '',
    role_id: '',
    base_salary: 0,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post('/users');
};
</script>
