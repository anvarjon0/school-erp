<template>
  <div class="flex h-screen bg-gray-50 font-sans">
    
    <!-- Sidebar -->
    <aside :class="['bg-gray-900 text-white transition-all duration-300 flex flex-col', sidebarOpen ? 'w-64' : 'w-20']">
      
      <!-- Logo Area -->
      <div class="h-16 flex items-center justify-center border-b border-gray-800">
        <Link href="/dashboard" class="flex items-center">
          <i class="fas fa-graduation-cap text-indigo-400 text-2xl"></i>
          <span v-if="sidebarOpen" class="ml-3 font-bold text-xl tracking-wide">
            School<span class="text-indigo-400">ERP</span>
          </span>
        </Link>
      </div>

      <!-- Navigation -->
      <nav class="flex-1 overflow-y-auto py-4">
        <ul class="space-y-1">
          <li>
            <Link href="/dashboard" :class="['flex items-center px-4 py-3 hover:bg-gray-800 transition-colors', $page.url === '/dashboard' ? 'bg-indigo-600 border-l-4 border-indigo-300' : '']">
              <i class="fas fa-tachometer-alt text-lg w-6 text-center"></i>
              <span v-if="sidebarOpen" class="ml-3 font-medium">Dashboard</span>
            </Link>
          </li>
          
          <li class="px-4 py-2 mt-4" v-if="sidebarOpen">
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">O'quv Bo'limi</span>
          </li>
          
          <li>
            <Link href="/students" :class="['flex items-center px-4 py-3 hover:bg-gray-800 transition-colors', $page.url.startsWith('/students') ? 'bg-gray-800 text-indigo-300 border-l-4 border-indigo-400' : '']">
              <i class="fas fa-user-graduate text-lg w-6 text-center"></i>
              <span v-if="sidebarOpen" class="ml-3 font-medium">O'quvchilar</span>
            </Link>
          </li>
          
          <!-- Attendances -->
          <li>
            <Link href="/attendances" :class="['flex items-center px-4 py-3 hover:bg-gray-800 transition-colors', $page.url.startsWith('/attendances') ? 'bg-gray-800 text-indigo-300 border-l-4 border-indigo-400' : '']">
              <i class="fas fa-clipboard-check text-lg w-6 text-center"></i>
              <span v-if="sidebarOpen" class="ml-3 font-medium">Davomat</span>
            </Link>
          </li>

          <!-- Finance -->
          <li class="px-4 py-2 mt-4" v-if="sidebarOpen">
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Moliya</span>
          </li>
          
          <li>
            <Link href="/payments" :class="['flex items-center px-4 py-3 hover:bg-gray-800 transition-colors', $page.url.startsWith('/payments') ? 'bg-gray-800 text-indigo-300 border-l-4 border-indigo-400' : '']">
              <i class="fas fa-money-bill-wave text-lg w-6 text-center"></i>
              <span v-if="sidebarOpen" class="ml-3 font-medium">To'lovlar</span>
            </Link>
          </li>
          
          <li>
            <Link href="/expenses" :class="['flex items-center px-4 py-3 hover:bg-gray-800 transition-colors', $page.url.startsWith('/expenses') ? 'bg-gray-800 text-indigo-300 border-l-4 border-indigo-400' : '']">
              <i class="fas fa-receipt text-lg w-6 text-center"></i>
              <span v-if="sidebarOpen" class="ml-3 font-medium">Xarajatlar</span>
            </Link>
          </li>

          <!-- Administration -->
          <li class="px-4 py-2 mt-4" v-if="sidebarOpen">
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Tizim</span>
          </li>
          
          <li>
            <Link href="/users" :class="['flex items-center px-4 py-3 hover:bg-gray-800 transition-colors', $page.url.startsWith('/users') ? 'bg-gray-800 text-indigo-300 border-l-4 border-indigo-400' : '']">
              <i class="fas fa-users-cog text-lg w-6 text-center"></i>
              <span v-if="sidebarOpen" class="ml-3 font-medium">Xodimlar (Users)</span>
            </Link>
          </li>
        </ul>
      </nav>

      <!-- User Info (Bottom) -->
      <div class="p-4 border-t border-gray-800">
        <div class="flex items-center" v-if="sidebarOpen">
          <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center font-bold text-lg">
            {{ $page.props.auth.user.name.charAt(0) }}
          </div>
          <div class="ml-3 overflow-hidden">
            <p class="text-sm font-medium truncate">{{ $page.props.auth.user.name }}</p>
            <p class="text-xs text-gray-400 truncate">{{ $page.props.auth.user.email }}</p>
          </div>
        </div>
        <div class="flex justify-center" v-else>
           <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center font-bold text-lg">
            {{ $page.props.auth.user.name.charAt(0) }}
          </div>
        </div>
      </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      
      <!-- Top Navbar -->
      <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 z-10 shadow-sm">
        <div class="flex items-center">
          <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700 focus:outline-none">
            <i class="fas fa-bars text-xl"></i>
          </button>
          <h1 class="ml-4 text-xl font-semibold text-gray-800 hidden sm:block">
            <slot name="header">Boshqaruv paneli</slot>
          </h1>
        </div>
        
        <div class="flex items-center space-x-4">
          <!-- Logout Button -->
          <button @click="logout" class="text-gray-500 hover:text-red-600 font-medium text-sm flex items-center transition-colors">
            <i class="fas fa-sign-out-alt mr-1"></i> Chiqish
          </button>
        </div>
      </header>

      <!-- Page Content -->
      <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-4 sm:p-6 lg:p-8">
        
        <!-- Flash Messages -->
        <div v-if="$page.props.flash.success" class="mb-4 bg-green-50 border-l-4 border-green-400 p-4 rounded-md shadow-sm">
          <div class="flex">
            <div class="flex-shrink-0">
              <i class="fas fa-check-circle text-green-400"></i>
            </div>
            <div class="ml-3">
              <p class="text-sm text-green-700 font-medium">
                {{ $page.props.flash.success }}
              </p>
            </div>
          </div>
        </div>
        
        <div v-if="$page.props.flash.error" class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 rounded-md shadow-sm">
          <div class="flex">
            <div class="flex-shrink-0">
              <i class="fas fa-exclamation-circle text-red-400"></i>
            </div>
            <div class="ml-3">
              <p class="text-sm text-red-700 font-medium">
                {{ $page.props.flash.error }}
              </p>
            </div>
          </div>
        </div>

        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { router, Link } from '@inertiajs/vue3';

const sidebarOpen = ref(true);

const logout = () => {
    router.post('/logout');
};
</script>
