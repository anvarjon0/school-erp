<template>
  <AdminLayout>
    <template #header>Xodimlar (Users)</template>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <!-- Header / Actions -->
      <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
        <h3 class="text-lg font-bold text-gray-800">Barcha Xodimlar</h3>
        
        <div class="flex space-x-3">
          <Link href="/users/create" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
            <i class="fas fa-plus mr-2"></i> Yangi qo'shish
          </Link>
        </div>
      </div>

      <!-- Filters (Mockup for now) -->
      <div class="p-4 bg-gray-50 border-b border-gray-100">
         <div class="flex flex-col md:flex-row gap-4">
             <input type="text" placeholder="Qidirish..." class="w-full md:w-1/3 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
             <button class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">Izlash</button>
         </div>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Xodim</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Holat</th>
              <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amallar</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="(user, index) in users.data" :key="user.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ index + 1 }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-700 font-bold">
                    {{ user.name.charAt(0) }}
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
                    <div class="text-sm text-gray-500">{{ user.email }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span v-for="role in user.roles" :key="role.id" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 mr-1">
                  {{ role.display_name }}
                </span>
                <span v-if="user.roles.length === 0" class="text-sm text-gray-500">Rolsiz</span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span v-if="user.is_active" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Faol</span>
                <span v-else class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Faolsiz</span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <Link :href="`/users/${user.id}/edit`" class="text-indigo-600 hover:text-indigo-900 mr-3"><i class="fas fa-edit"></i></Link>
                <button @click="deleteUser(user.id)" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
              </td>
            </tr>
            <tr v-if="users.data.length === 0">
                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                    Hech qanday xodim topilmadi.
                </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Pagination -->
      <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
          <div class="text-sm text-gray-500">
              Jami: {{ users.total }} ta
          </div>
          <div class="flex space-x-1" v-if="users.links && users.links.length > 3">
              <!-- Basic pagination structure, can be enhanced -->
              <template v-for="(link, i) in users.links" :key="i">
                  <Link v-if="link.url" :href="link.url" v-html="link.label" 
                        class="px-3 py-1 border rounded text-sm" 
                        :class="link.active ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 hover:bg-gray-50 border-gray-300'">
                  </Link>
                  <span v-else v-html="link.label" class="px-3 py-1 border rounded text-sm bg-gray-100 text-gray-400 border-gray-200"></span>
              </template>
          </div>
      </div>

    </div>
  </AdminLayout>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    users: Object,
    roles: Array,
});

const deleteUser = (id) => {
    if (confirm('Rostdan ham o\'chirmoqchimisiz?')) {
        router.delete(`/users/${id}`);
    }
};
</script>
