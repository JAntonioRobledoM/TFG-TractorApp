<template>
    <AdminLayout>
      <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-2xl font-bold text-green-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-green-600" viewBox="0 0 20 20" fill="currentColor">
              <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" />
              <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z" />
            </svg>
            Anuncios de {{ user.first_name }} {{ user.last_name }}
          </h1>
          <Link 
            :href="route('users.show', user.id)" 
            class="text-green-600 hover:text-green-800 inline-flex items-center px-4 py-2 rounded-lg hover:bg-green-50 transition duration-200"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Volver al perfil
          </Link>
        </div>
  
        <div v-if="listings.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div 
            v-for="listing in listings" 
            :key="listing.id" 
            class="bg-white shadow-lg overflow-hidden rounded-lg border-t-4 border-green-600"
          >
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center bg-green-50">
              <div>
                <h3 class="text-lg leading-6 font-medium text-green-800">
                  {{ listing.type === 'sale' ? 'Venta' : 'Alquiler' }}
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-green-600">
                  {{ listing.tractor ? listing.tractor.model : 'Tractor no especificado' }}
                </p>
              </div>
              <span 
                class="px-2 py-1 text-xs font-medium rounded-full"
                :class="{
                  'bg-green-100 text-green-800': listing.is_active,
                  'bg-red-100 text-red-800': !listing.is_active
                }"
              >
                {{ listing.is_active ? 'Activo' : 'Inactivo' }}
              </span>
            </div>
            <div class="border-t border-green-200">
              <dl>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500">Precio</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-semibold">
                    {{ formatCurrency(listing.price) }}
                  </dd>
                </div>
                <div class="bg-green-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500">Descripción</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ listing.description || 'Sin descripción' }}
                  </dd>
                </div>
                <div v-if="listing.type === 'rental'" class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500">Periodo</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ formatDate(listing.start_date) }} - {{ formatDate(listing.end_date) }}
                  </dd>
                </div>
              </dl>
            </div>
            <div class="px-4 py-3 bg-green-50 text-right sm:px-6">
              <Link 
                :href="route('listings.show', listing.id)" 
                class="text-green-600 hover:text-green-900 inline-flex items-center font-medium"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                  <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                </svg>
                Ver detalles
              </Link>
            </div>
          </div>
        </div>
        <div v-else class="bg-white shadow-lg overflow-hidden rounded-lg p-6 text-center border-t-4 border-yellow-600">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-4 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <p class="text-gray-500">No hay anuncios asociados a este usuario.</p>
        </div>
      </div>
    </AdminLayout>
  </template>
  
  <script setup lang="ts">
  import { Link } from '@inertiajs/vue3';
  import AdminLayout from '@/Layouts/AdminLayout.vue';
  
  defineProps({
    user: Object,
    listings: Array
  });
  
  const formatCurrency = (value) => {
    if (!value) return '---';
    return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(value);
  };
  
  const formatDate = (dateString) => {
    if (!dateString) return 'No especificado';
    return new Date(dateString).toLocaleDateString('es-ES', { 
      year: 'numeric', 
      month: 'short', 
      day: 'numeric' 
    });
  };
  </script>

  <style scoped>
  /* Add subtle field texture to the grid background */
  .bg-white {
      background-image: 
          radial-gradient(circle at 10px 10px, rgba(34, 197, 94, 0.03) 2px, transparent 2px);
      background-size: 20px 20px;
  }

  /* Add pattern to the card headers */
  .bg-green-50 {
      background-image: 
          linear-gradient(to bottom, rgba(240, 253, 244, 0.9), rgba(240, 253, 244, 0.9)),
          repeating-linear-gradient(
              45deg,
              rgba(34, 197, 94, 0.05) 0px,
              rgba(34, 197, 94, 0.05) 10px,
              transparent 10px,
              transparent 20px
          );
  }

  /* Enhanced shadow for cards */
  .shadow-lg {
      box-shadow: 0 10px 15px -3px rgba(34, 197, 94, 0.1), 0 4px 6px -2px rgba(34, 197, 94, 0.05);
  }
  </style>