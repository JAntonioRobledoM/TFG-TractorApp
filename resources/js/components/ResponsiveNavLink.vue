<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps({
  href: {
    type: String,
    required: true
  },
  active: {
    type: Boolean,
    default: false
  }
});
</script>

<template>
  <Link 
    :href="href" 
    :class="{ 
      'block w-full pl-3 pr-4 py-2 border-l-4 transition duration-150 ease-in-out': true, 
      'border-indigo-400 text-indigo-700 bg-indigo-50 focus:outline-none focus:text-indigo-800 focus:bg-indigo-100 focus:border-indigo-700': active, 
      'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300': !active 
    }"
  >
    <slot />
  </Link>
</template>