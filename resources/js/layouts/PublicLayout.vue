<template>
  <div>
    <!-- Navigation -->
    <PublicNav />

    <!-- Page Header -->
    <header class="bg-green-800 shadow">
      <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
        <slot name="header"></slot>
      </div>
    </header>

    <!-- Main Content -->
    <main>
      <slot></slot>
    </main>

    <!-- Footer -->
    <PublicFooter />
  </div>
</template>

<script setup lang="ts">
import PublicNav from '@/components/Public/PublicNav.vue';
import PublicFooter from '@/components/Public/PublicFooter.vue';
</script>

<style scoped>
/* Estilos adicionales si son necesarios */
</style>