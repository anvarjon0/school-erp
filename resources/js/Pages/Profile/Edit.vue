<template>
  <AdminLayout>
    <template #header>Profilni Tahrirlash</template>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      
      <!-- Profile Info -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
          <h3 class="text-lg font-bold text-gray-800">Shaxsiy ma'lumotlar</h3>
        </div>
        <div class="p-6">
          <form @submit.prevent="submitProfile" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">F.I.SH. *</label>
              <input v-model="profileForm.name" type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
              <p v-if="profileForm.errors.name" class="text-red-500 text-xs mt-1">{{ profileForm.errors.name }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
              <input v-model="profileForm.email" type="email" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
              <p v-if="profileForm.errors.email" class="text-red-500 text-xs mt-1">{{ profileForm.errors.email }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Telefon</label>
              <input v-model="profileForm.phone" type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            <div class="pt-4 flex justify-end">
              <button type="submit" :disabled="profileForm.processing" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                Saqlash
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Password Update -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
          <h3 class="text-lg font-bold text-gray-800">Parolni Yangilash</h3>
        </div>
        <div class="p-6">
          <form @submit.prevent="submitPassword" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Joriy parol *</label>
              <input v-model="passwordForm.current_password" type="password" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
              <p v-if="passwordForm.errors.current_password" class="text-red-500 text-xs mt-1">{{ passwordForm.errors.current_password }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Yangi parol *</label>
              <input v-model="passwordForm.password" type="password" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
              <p v-if="passwordForm.errors.password" class="text-red-500 text-xs mt-1">{{ passwordForm.errors.password }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Yangi parolni tasdiqlash *</label>
              <input v-model="passwordForm.password_confirmation" type="password" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
            </div>

            <div class="pt-4 flex justify-end">
              <button type="submit" :disabled="passwordForm.processing" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                Parolni yangilash
              </button>
            </div>
          </form>
        </div>
      </div>
      
    </div>
  </AdminLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    user: Object,
});

const profileForm = useForm({
    name: props.user.name,
    email: props.user.email,
    phone: props.user.phone || '',
    _method: 'PUT'
});

const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
    _method: 'PUT'
});

const submitProfile = () => {
    profileForm.post('/profile', {
        preserveScroll: true,
    });
};

const submitPassword = () => {
    passwordForm.post('/profile/password', {
        preserveScroll: true,
        onSuccess: () => passwordForm.reset(),
    });
};
</script>
