<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Navigation Component -->
    <PublicNav>
      <!-- Puedes pasar el componente NotificacionesChat como slot al PublicNav si tiene slots definidos -->
      <template #notifications v-if="$page.props.auth.user">
        <NotificacionesChat :userId="$page.props.auth.user.id" />
      </template>
    </PublicNav>

    <!-- Page Heading -->
    <header v-if="$slots.header" class="bg-green-800 shadow-md">
      <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
        <slot name="header"></slot>
        
        <!-- Alternativa: Colocar las notificaciones en el header -->
        <div v-if="$page.props.auth.user" class="flex items-center">
          <NotificacionesChat :userId="$page.props.auth.user.id" />
        </div>
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
import { usePage } from '@inertiajs/vue3';
import PublicNav from '@/Components/Public/PublicNav.vue';
import PublicFooter from '@/Components/Public/PublicFooter.vue';
import NotificacionesChat from '@/Components/NotificacionesChat.vue';

const page = usePage();
</script>