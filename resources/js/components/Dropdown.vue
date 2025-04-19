<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';

defineProps({
  align: {
    type: String,
    default: 'right'
  },
  width: {
    type: String,
    default: '48'
  },
  contentClasses: {
    type: Array,
    default: () => ['py-1', 'bg-white']
  }
});

const open = ref(false);

const toggleOpen = () => {
  open.value = !open.value;
};

const closeDropdown = (event) => {
  if (!event.target.closest('.dropdown-container')) {
    open.value = false;
  }
};

onMounted(() => {
  document.addEventListener('click', closeDropdown);
});

onUnmounted(() => {
  document.removeEventListener('click', closeDropdown);
});
</script>

<template>
  <div class="relative dropdown-container">
    <div @click="toggleOpen">
      <slot name="trigger" />
    </div>

    <div 
      v-show="open"
      class="absolute z-50 mt-2 rounded-md shadow-lg"
      :class="[
        { 'origin-top-right': align === 'right' },
        { 'origin-top-left': align === 'left' },
        `w-${width}`
      ]"
    >
      <div 
        class="rounded-md ring-1 ring-black ring-opacity-5"
        :class="contentClasses"
      >
        <slot name="content" />
      </div>
    </div>
  </div>
</template>