<script setup>
import { ref, watch } from 'vue'
import { X } from 'lucide-vue-next'

const props = defineProps({
  modelValue: Boolean,
  title: String,
  width: {
    type: String,
    default: 'max-w-2xl'
  }
})

const emit = defineEmits(['update:modelValue', 'close'])

const close = () => {
  emit('update:modelValue', false)
  emit('close')
}

// Prevent body scroll when open
watch(() => props.modelValue, (val) => {
  if (val) {
    document.body.style.overflow = 'hidden'
  } else {
    document.body.style.overflow = ''
  }
})
</script>

<template>
  <div class="relative z-50" aria-labelledby="slide-over-title" role="dialog" aria-modal="true" v-show="modelValue">
    
    <!-- Background overlay -->
    <transition
      enter-active-class="ease-in-out duration-500"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="ease-in-out duration-500"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div v-show="modelValue" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" @click="close"></div>
    </transition>

    <div class="fixed inset-0 overflow-hidden pointer-events-none">
      <div class="absolute inset-0 overflow-hidden">
        <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10 sm:pl-16">
          
          <!-- Slide-over panel -->
          <transition
            enter-active-class="transform transition ease-in-out duration-500 sm:duration-700"
            enter-from-class="translate-x-full"
            enter-to-class="translate-x-0"
            leave-active-class="transform transition ease-in-out duration-500 sm:duration-700"
            leave-from-class="translate-x-0"
            leave-to-class="translate-x-full"
          >
            <div v-show="modelValue" :class="['pointer-events-auto w-screen', width]">
              <div class="flex h-full flex-col overflow-y-scroll bg-white dark:bg-gray-800 shadow-2xl custom-scrollbar">
                
                <div class="px-4 py-6 sm:px-6 bg-gray-50/50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
                  <div class="flex items-start justify-between">
                    <h2 class="text-xl font-bold leading-6 text-gray-900 dark:text-white" id="slide-over-title">{{ title }}</h2>
                    <div class="ml-3 flex h-7 items-center">
                      <button type="button" @click="close" class="relative rounded-md bg-transparent text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <span class="absolute -inset-2.5"></span>
                        <span class="sr-only">Yopish</span>
                        <X class="h-6 w-6" aria-hidden="true" />
                      </button>
                    </div>
                  </div>
                </div>

                <div class="relative flex-1 px-4 py-6 sm:px-6">
                  <slot></slot>
                </div>
                
              </div>
            </div>
          </transition>

        </div>
      </div>
    </div>
  </div>
</template>
