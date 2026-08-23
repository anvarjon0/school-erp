<script setup>
import { ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '@/store/auth'
import { 
  LayoutDashboard, 
  Users, 
  GraduationCap, 
  Building2, 
  BookOpen, 
  FileText, 
  Wallet, 
  Landmark, 
  ArrowDownRight, 
  ArrowUpRight, 
  AlertCircle, 
  Banknote, 
  LineChart, 
  PieChart, 
  Bell, 
  MessageSquare, 
  Settings, 
  ShieldCheck, 
  Activity,
  Menu,
  Search,
  Plus,
  Moon,
  Sun,
  ChevronDown,
  LogOut,
  User as UserIcon
} from 'lucide-vue-next'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()

const isSidebarOpen = ref(true)
const isDarkMode = ref(false)
const showProfileDropdown = ref(false)

const toggleDarkMode = () => {
  isDarkMode.value = !isDarkMode.value
  if (isDarkMode.value) {
    document.documentElement.classList.add('dark')
  } else {
    document.documentElement.classList.remove('dark')
  }
}

const handleLogout = async () => {
  await authStore.logout()
  router.push('/login')
}

const menuGroups = [
  {
    title: 'MAIN',
    items: [
      { name: 'Dashboard', path: '/dashboard', icon: LayoutDashboard }
    ]
  },
  {
    title: 'MANAGEMENT',
    items: [
      { name: 'O\'quvchilar', path: '/students', icon: GraduationCap },
      { name: 'Xodimlar', path: '/employees', icon: Users },
      { name: 'Sinflar', path: '/classes', icon: BookOpen },
      { name: 'Shartnomalar', path: '/contracts', icon: FileText }
    ]
  },
  {
    title: 'FINANCE',
    items: [
      { name: 'To\'lovlar', path: '/payments', icon: Wallet },
      { name: 'Kassa', path: '/cash-register', icon: Landmark },
      { name: 'Daromadlar', path: '/income', icon: ArrowUpRight },
      { name: 'Xarajatlar', path: '/expenses', icon: ArrowDownRight },
      { name: 'Qarzdorlar', path: '/debts', icon: AlertCircle },
      { name: 'Oylik Maoshlar', path: '/salaries', icon: Banknote },
    ]
  },
  {
    title: 'REPORTS',
    items: [
      { name: 'Moliya Hisoboti', path: '/reports/financial', icon: LineChart },
      { name: 'Analitika', path: '/analytics', icon: PieChart }
    ]
  },
  {
    title: 'SYSTEM',
    items: [
      { name: 'Sozlamalar', path: '/settings', icon: Settings },
      { name: 'Tizim Jurnali', path: '/activity', icon: Activity }
    ]
  }
]
</script>

<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200 flex">
    
    <!-- Sidebar -->
    <aside 
      class="bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 transition-all duration-300 flex flex-col z-20 shadow-sm"
      :class="isSidebarOpen ? 'w-64' : 'w-20'"
    >
      <!-- Logo -->
      <div class="h-16 flex items-center justify-center border-b border-gray-100 dark:border-gray-700 px-4 shrink-0">
        <div class="flex items-center text-primary-600 dark:text-primary-500">
          <GraduationCap class="w-8 h-8" />
          <span v-if="isSidebarOpen" class="ml-3 font-bold text-xl tracking-tight text-gray-900 dark:text-white">
            School<span class="text-primary-600 dark:text-primary-500">ERP</span>
          </span>
        </div>
      </div>

      <!-- Navigation -->
      <nav class="flex-1 overflow-y-auto py-4 custom-scrollbar">
        <div v-for="(group, gIdx) in menuGroups" :key="gIdx" class="mb-6">
          <div v-if="isSidebarOpen" class="px-6 mb-2">
            <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
              {{ group.title }}
            </span>
          </div>
          <ul class="space-y-1 px-3">
            <li v-for="item in group.items" :key="item.path">
              <router-link 
                :to="item.path"
                class="flex items-center px-3 py-2.5 rounded-lg transition-colors group relative"
                :class="route.path.startsWith(item.path) ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-400 font-medium' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-gray-200'"
              >
                <component 
                  :is="item.icon" 
                  class="w-5 h-5 shrink-0 transition-colors"
                  :class="route.path.startsWith(item.path) ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-600 dark:group-hover:text-gray-300'"
                />
                <span v-if="isSidebarOpen" class="ml-3 text-sm truncate">{{ item.name }}</span>
                
                <!-- Tooltip for collapsed state -->
                <div v-if="!isSidebarOpen" class="absolute left-full ml-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity whitespace-nowrap z-50">
                  {{ item.name }}
                </div>
              </router-link>
            </li>
          </ul>
        </div>
      </nav>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
      
      <!-- Topbar -->
      <header class="h-16 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between px-4 sm:px-6 lg:px-8 z-10">
        <div class="flex items-center flex-1">
          <button @click="isSidebarOpen = !isSidebarOpen" class="p-2 mr-4 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
            <Menu class="w-5 h-5" />
          </button>
          
          <!-- Search -->
          <div class="hidden sm:flex max-w-md w-full relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <Search class="h-4 w-4 text-gray-400" />
            </div>
            <input type="text" placeholder="Qidiruv..." class="block w-full pl-10 pr-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg leading-5 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition-colors">
          </div>
        </div>

        <div class="flex items-center space-x-3 sm:space-x-4">
          <!-- Quick Action -->
          <button class="hidden sm:flex items-center px-3 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
            <Plus class="w-4 h-4 mr-1.5" />
            Yangi to'lov
          </button>

          <div class="h-6 w-px bg-gray-200 dark:bg-gray-700 hidden sm:block mx-1"></div>

          <!-- Theme Toggle -->
          <button @click="toggleDarkMode" class="p-2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
            <Moon v-if="!isDarkMode" class="w-5 h-5" />
            <Sun v-else class="w-5 h-5" />
          </button>

          <!-- Notifications -->
          <button class="p-2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors relative">
            <Bell class="w-5 h-5" />
            <span class="absolute top-1.5 right-1.5 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white dark:ring-gray-800"></span>
          </button>

          <!-- User Profile -->
          <div class="relative">
            <button @click="showProfileDropdown = !showProfileDropdown" class="flex items-center space-x-2 p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
              <div class="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900/50 flex items-center justify-center text-primary-700 dark:text-primary-400 font-bold text-sm ring-2 ring-white dark:ring-gray-800">
                {{ authStore.user?.name?.charAt(0) || 'A' }}
              </div>
              <div class="hidden md:flex flex-col items-start mr-1">
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-200 leading-none mb-1">{{ authStore.user?.name || 'Admin' }}</span>
                <span class="text-xs text-gray-500 dark:text-gray-400 leading-none">Super Admin</span>
              </div>
              <ChevronDown class="w-4 h-4 text-gray-400 hidden md:block" />
            </button>

            <!-- Dropdown -->
            <div v-if="showProfileDropdown" class="origin-top-right absolute right-0 mt-2 w-48 rounded-xl shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 dark:divide-gray-700 z-50">
              <div class="py-1">
                <a href="#" class="group flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                  <UserIcon class="mr-3 h-4 w-4 text-gray-400 group-hover:text-gray-500" /> Profile
                </a>
                <a href="#" class="group flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                  <Settings class="mr-3 h-4 w-4 text-gray-400 group-hover:text-gray-500" /> Settings
                </a>
              </div>
              <div class="py-1">
                <button @click="handleLogout" class="group flex w-full items-center px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/10">
                  <LogOut class="mr-3 h-4 w-4 text-red-500" /> Logout
                </button>
              </div>
            </div>
          </div>
        </div>
      </header>

      <!-- Main Page Content -->
      <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 dark:bg-gray-900 p-4 sm:p-6 lg:p-8">
        <router-view v-slot="{ Component }">
          <transition name="fade" mode="out-in">
            <component :is="Component" />
          </transition>
        </router-view>
      </main>
    </div>
  </div>
</template>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.15s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Custom Scrollbar for sidebar */
.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: #cbd5e1;
  border-radius: 20px;
}
.dark .custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: #475569;
}
</style>
