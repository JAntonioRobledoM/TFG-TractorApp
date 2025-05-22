<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Navigation Component -->
    <PublicNav />

    <!-- Page Heading -->
    <header v-if="$slots.header" class="bg-green-800 shadow-md">
      <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <slot name="header"></slot>
      </div>
    </header>

    <!-- Page Content -->
    <main>
      <slot></slot>
    </main>

    <!-- Footer Component -->
    <PublicFooter />
  </div>
</template>

<script setup>
import PublicNav from '@/Components/Public/PublicNav.vue';
import PublicFooter from '@/Components/Public/PublicFooter.vue';
</script>