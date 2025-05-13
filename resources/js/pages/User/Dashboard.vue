<template>
  <div class="min-h-screen bg-gradient-to-b from-green-50 to-green-100">
    <PublicLayout>
      <template #header>
        <h2 class="text-2xl font-semibold text-white">
          Mi panel de control
        </h2>
      </template>

      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <!-- Bienvenida al usuario -->
          <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-8">
            <div class="p-6 border-b border-gray-200 flex items-center space-x-4">
              <div class="bg-green-100 p-3 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
              </div>
              <div>
                <h2 class="text-xl font-semibold text-gray-800">
                  Bienvenido/a, {{ $page.props.auth.user.first_name }}
                </h2>
                <p class="text-gray-600">
                  Gestiona tus tractores, anuncios y solicitudes desde aquí.
                </p>
              </div>
            </div>
          </div>

          <!-- Secciones principales -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Mis Tractores -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
              <div class="p-4 bg-green-700 text-white font-medium flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M19 8h-2c0-.6-.4-1-1-1h-2c-.6 0-1 .4-1 1h-2c-1.7 0-3 1.3-3 3v3c0 1.7 1.3 3 3 3h8c1.7 0 3-1.3 3-3v-3c0-1.7-1.3-3-3-3zm-2 9c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3zM5 11c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"/>
                </svg>
                <span>Mis Tractores</span>
              </div>
              <div class="p-4">
                <div v-if="userTractors && userTractors.length > 0" class="space-y-3">
                  <div v-for="tractor in userTractors" :key="tractor.id" class="border-b border-gray-100 pb-2 last:border-b-0 last:pb-0">
                    <div class="font-medium text-gray-800">
                      {{ tractor.brand }} {{ tractor.model }}
                    </div>
                    <div class="text-sm text-gray-600">
                      {{ tractor.year ? 'Año: ' + tractor.year : 'Sin año especificado' }}
                    </div>
                  </div>
                </div>
                <div v-else class="text-gray-500 text-center py-4">
                  No tienes tractores registrados
                </div>
                <div class="mt-4">
                  <button class="w-full py-2 bg-green-100 text-green-700 hover:bg-green-200 rounded-md transition duration-200 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                    </svg>
                    Añadir tractor
                  </button>
                </div>
              </div>
            </div>

            <!-- Mis Anuncios -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
              <div class="p-4 bg-green-700 text-white font-medium flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" />
                  <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z" />
                </svg>
                <span>Mis Anuncios</span>
              </div>
              <div class="p-4">
                <div v-if="userListings && userListings.length > 0" class="space-y-3">
                  <div v-for="listing in userListings" :key="listing.id" class="border-b border-gray-100 pb-2 last:border-b-0 last:pb-0">
                    <div class="font-medium text-gray-800">
                      {{ listing.title || 'Anuncio #' + listing.id }}
                    </div>
                    <div class="text-sm text-gray-600 flex justify-between">
                      <span>{{ listing.type === 'sale' ? 'Venta' : 'Alquiler' }}</span>
                      <span class="font-bold text-green-600">{{ formatCurrency(listing.price) }}</span>
                    </div>
                  </div>
                </div>
                <div v-else class="text-gray-500 text-center py-4">
                  No tienes anuncios publicados
                </div>
                <div class="mt-4">
                  <button class="w-full py-2 bg-green-100 text-green-700 hover:bg-green-200 rounded-md transition duration-200 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                    </svg>
                    Publicar anuncio
                  </button>
                </div>
              </div>
            </div>

            <!-- Mis Solicitudes -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
              <div class="p-4 bg-green-700 text-white font-medium flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v7h-2l-1 2H8l-1-2H5V5z" clip-rule="evenodd" />
                </svg>
                <span>Mis Solicitudes</span>
              </div>
              <div class="p-4">
                <div v-if="userRequests && userRequests.length > 0" class="space-y-3">
                  <div v-for="request in userRequests" :key="request.id" class="border-b border-gray-100 pb-2 last:border-b-0 last:pb-0">
                    <div class="font-medium text-gray-800 flex items-center">
                      <span class="mr-2">Solicitud #{{ request.id }}</span>
                      <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium"
                        :class="{
                          'bg-yellow-100 text-yellow-800': request.status === 'pending',
                          'bg-green-100 text-green-800': request.status === 'accepted',
                          'bg-red-100 text-red-800': request.status === 'rejected',
                          'bg-blue-100 text-blue-800': request.status === 'completed'
                        }">
                        {{ requestStatusText(request.status) }}
                      </span>
                    </div>
                    <div class="text-sm text-gray-600">
                      {{ request.type === 'sale' ? 'Compra' : 'Alquiler' }} - 
                      <span class="font-bold text-green-600">{{ formatCurrency(request.offered_price) }}</span>
                    </div>
                  </div>
                </div>
                <div v-else class="text-gray-500 text-center py-4">
                  No tienes solicitudes
                </div>
                <div class="mt-4">
                  <button class="w-full py-2 bg-green-100 text-green-700 hover:bg-green-200 rounded-md transition duration-200 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                    </svg>
                    Nueva solicitud
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Actividad reciente -->
          <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="p-4 bg-green-700 text-white font-medium">
              Actividad reciente
            </div>
            <div class="p-6">
              <div class="space-y-4">
                <div class="flex items-start">
                  <div class="bg-green-100 p-2 rounded-full mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                    </svg>
                  </div>
                  <div>
                    <div class="text-sm font-medium text-gray-800">Has iniciado sesión</div>
                    <div class="text-xs text-gray-500">Hace unos momentos</div>
                  </div>
                </div>
                <div class="border-b border-gray-100 pb-2"></div>
                
                <div class="text-center text-gray-500">
                  No hay más actividades recientes
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </PublicLayout>
  </div>
</template>

<script setup lang="ts">
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';

// Propiedades del componente
const props = defineProps({
  userTractors: Array,
  userListings: Array,
  userRequests: Array,
});

// Formateador de moneda
const formatCurrency = (value) => {
  if (!value) return '---';
  return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(value);
};

// Texto para el estado de la solicitud
const requestStatusText = (status) => {
  switch (status) {
    case 'pending': return 'Pendiente';
    case 'accepted': return 'Aceptada';
    case 'rejected': return 'Rechazada';
    case 'completed': return 'Completada';
    default: return status;
  }
};
</script>