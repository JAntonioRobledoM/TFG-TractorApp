<template>
    <AdminLayout>
      <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-2xl font-bold text-green-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-yellow-600" viewBox="0 0 24 24" fill="currentColor">
              <path d="M19 8h-2c0-.6-.4-1-1-1h-2c-.6 0-1 .4-1 1h-2c-1.7 0-3 1.3-3 3v3c0 1.7 1.3 3 3 3h8c1.7 0 3-1.3 3-3v-3c0-1.7-1.3-3-3-3zm-2 9c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3zM5 11c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"/>
            </svg>
            Tractores de {{ user.first_name }} {{ user.last_name }}
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
  
        <div v-if="tractors.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div 
            v-for="tractor in tractors" 
            :key="tractor.id" 
            class="bg-white shadow-lg overflow-hidden rounded-lg border-t-4 border-yellow-600"
          >
            <div class="px-4 py-5 sm:px-6 bg-green-50">
              <h3 class="text-lg leading-6 font-medium text-green-800">
                {{ tractor.model || 'Tractor #' + tractor.id }}
              </h3>
              <p class="mt-1 max-w-2xl text-sm text-green-600">
                Año: {{ tractor.year || 'No especificado' }}
              </p>
            </div>
            <div class="border-t border-green-200">
              <dl>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500">Descripción</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ tractor.description || 'Sin descripción' }}
                  </dd>
                </div>
              </dl>
            </div>
            <div class="px-4 py-3 bg-green-50 text-right sm:px-6">
              <Link 
                :href="route('tractors.show', tractor.id)" 
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
          <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-4 text-yellow-500" viewBox="0 0 24 24" fill="currentColor">
            <path d="M19 8h-2c0-.6-.4-1-1-1h-2c-.6 0-1 .4-1 1h-2c-1.7 0-3 1.3-3 3v3c0 1.7 1.3 3 3 3h8c1.7 0 3-1.3 3-3v-3c0-1.7-1.3-3-3-3zm-2 9c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3zM5 11c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"/>
          </svg>
          <p class="text-gray-500">No hay tractores asociados a este usuario.</p>
        </div>
      </div>
    </AdminLayout>
  </template>
  
  <script setup lang="ts">
  import { Link } from '@inertiajs/vue3';
  import AdminLayout from '@/Layouts/AdminLayout.vue';
  
  defineProps({
    user: Object,
    tractors: Array
  });
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