<script setup>
import { useToast } from '@/composables/useToast'
import { CheckCircle, XCircle, Info, X } from 'lucide-vue-next'

const { toasts, removeToast } = useToast()

const getIcon = (type) => {
  if (type === 'success') return CheckCircle
  if (type === 'error') return XCircle
  return Info
}

const getStyles = (type) => {
  if (type === 'success') return 'bg-emerald-50 border-emerald-200 text-emerald-800 dark:bg-emerald-900/30 dark:border-emerald-800 dark:text-emerald-300'
  if (type === 'error') return 'bg-red-50 border-red-200 text-red-800 dark:bg-red-900/30 dark:border-red-800 dark:text-red-300'
  return 'bg-blue-50 border-blue-200 text-blue-800 dark:bg-blue-900/30 dark:border-blue-800 dark:text-blue-300'
}

const getIconColor = (type) => {
  if (type === 'success') return 'text-emerald-500 dark:text-emerald-400'
  if (type === 'error') return 'text-red-500 dark:text-red-400'
  return 'text-blue-500 dark:text-blue-400'
}
</script>

<template>
  <div class="fixed top-4 right-4 z-[100] flex flex-col gap-2 pointer-events-none w-full max-w-sm">
    <transition-group 
      enter-active-class="transform ease-out duration-300 transition"
      enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
      enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
      leave-active-class="transition ease-in duration-100"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div 
        v-for="toast in toasts" 
        :key="toast.id"
        class="pointer-events-auto w-full max-w-sm rounded-lg border shadow-lg overflow-hidden p-4 flex items-start"
        :class="getStyles(toast.type)"
      >
        <div class="flex-shrink-0">
          <component :is="getIcon(toast.type)" class="h-5 w-5" :class="getIconColor(toast.type)" />
        </div>
        <div class="ml-3 w-0 flex-1 pt-0.5">
          <p class="text-sm font-medium">{{ toast.message }}</p>
        </div>
        <div class="ml-4 flex-shrink-0 flex">
          <button @click="removeToast(toast.id)" class="inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
            <X class="h-4 w-4" />
          </button>
        </div>
      </div>
    </transition-group>
  </div>
</template>
