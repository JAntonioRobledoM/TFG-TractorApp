<template>
  <div class="min-h-screen bg-gradient-to-b from-green-50 to-green-100">
    <UserLayout>
      <template #header>
        <h2 class="font-semibold text-2xl text-white leading-tight">
          Mis Solicitudes
        </h2>
      </template>

      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <!-- Filter tabs -->
          <div class="bg-white overflow-hidden shadow-lg rounded-lg mb-6 p-4">
            <div class="flex space-x-2">
              <button 
                @click="filter = 'all'" 
                class="px-4 py-2 rounded-md text-sm font-medium transition-colors"
                :class="filter === 'all' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
              >
                Todas
              </button>
              <button 
                @click="filter = 'pending'" 
                class="px-4 py-2 rounded-md text-sm font-medium transition-colors"
                :class="filter === 'pending' ? 'bg-yellow-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
              >
                Pendientes
              </button>
              <button 
                @click="filter = 'accepted'" 
                class="px-4 py-2 rounded-md text-sm font-medium transition-colors"
                :class="filter === 'accepted' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
              >
                Aceptadas
              </button>
              <button 
                @click="filter = 'rejected'" 
                class="px-4 py-2 rounded-md text-sm font-medium transition-colors"
                :class="filter === 'rejected' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
              >
                Rechazadas
              </button>
            </div>
          </div>
          
          <!-- No requests message -->
          <div v-if="filteredRequests.length === 0" class="bg-white overflow-hidden shadow-lg rounded-lg p-12 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">No se encontraron solicitudes</h3>
            <p class="text-gray-500">
              {{ filter === 'all' ? 'Aún no has realizado ninguna solicitud.' : `No tienes solicitudes con estado "${statusText[filter]}".` }}
            </p>
            <Link :href="route('user.listings.index')" class="mt-4 inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
              Ver Anuncios Disponibles
            </Link>
          </div>

          <!-- Requests list -->
          <div v-else class="space-y-6">
            <div 
              v-for="request in filteredRequests" 
              :key="request.id" 
              class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-300"
            >
              <div class="p-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between">
                  <div class="mb-4 md:mb-0">
                    <div class="flex items-center mb-2">
                      <h3 class="font-semibold text-lg text-green-800 mr-2">
                        {{ request.listing?.tractor ? (request.listing.tractor.brand + ' ' + request.listing.tractor.model) : 'Tractor' }}
                      </h3>
                      <span 
                        class="px-3 py-1 rounded-full text-xs font-bold"
                        :class="request.listing?.type === 'sale' ? 'bg-blue-600 text-white' : 'bg-yellow-500 text-white'"
                      >
                        {{ request.listing?.type === 'sale' ? 'Venta' : 'Alquiler' }}
                      </span>
                    </div>
                    <p class="text-gray-600 mb-1">
                      <span class="font-medium">Vendedor:</span> 
                      {{ request.listing?.seller ? (request.listing.seller.first_name + ' ' + request.listing.seller.last_name) : 'Vendedor' }}
                    </p>
                    <p class="text-gray-600 mb-1">
                      <span class="font-medium">Precio:</span> 
                      {{ formatCurrency(request.listing?.price) }}
                      <span v-if="request.listing?.type === 'rental'" class="text-sm text-gray-500">/día</span>
                    </p>
                    <div class="mt-3">
                      <span 
                        class="px-3 py-1 rounded-md text-xs font-bold"
                        :class="{
                          'bg-yellow-100 text-yellow-800 border border-yellow-200': request.status === 'pending',
                          'bg-green-100 text-green-800 border border-green-200': request.status === 'accepted',
                          'bg-red-100 text-red-800 border border-red-200': request.status === 'rejected',
                          'bg-blue-100 text-blue-800 border border-blue-200': request.status === 'completed',
                          'bg-gray-100 text-gray-800 border border-gray-200': request.status === 'cancelled'
                        }"
                      >
                        {{ statusText[request.status] || request.status }}
                      </span>
                      <span class="text-xs text-gray-500 ml-2">
                        {{ formatDate(request.created_at) }}
                      </span>
                    </div>
                  </div>
                  <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-2">
                    <Link 
                      :href="route('user.listings.show', request.listing_id)" 
                      class="inline-flex items-center justify-center px-4 py-2 bg-green-600 rounded-md text-sm font-medium text-white hover:bg-green-700 transition"
                    >
                      Ver Anuncio
                    </Link>
                    <Link 
                      :href="route('user.requests.show', request.id)" 
                      class="inline-flex items-center justify-center px-4 py-2 bg-gray-200 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-300 transition"
                    >
                      Detalles
                    </Link>
                    <button 
                      v-if="request.status === 'pending'"
                      @click="cancelRequest(request.id)"
                      class="inline-flex items-center justify-center px-4 py-2 bg-red-100 rounded-md text-sm font-medium text-red-700 hover:bg-red-200 transition"
                    >
                      Cancelar
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </UserLayout>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';

const props = defineProps({
  requests: Array,
});

const filter = ref('all');

const statusText = {
  'pending': 'Pendiente',
  'accepted': 'Aceptada',
  'rejected': 'Rechazada',
  'completed': 'Completada',
  'cancelled': 'Cancelada',
  'all': 'Todas'
};

const filteredRequests = computed(() => {
  if (filter.value === 'all') {
    return props.requests;
  }
  return props.requests.filter(request => request.status === filter.value);
});

function formatCurrency(value) {
  if (!value) return '---';
  return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(value);
}

function formatDate(dateString) {
  if (!dateString) return '';
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' }).format(date);
}

function cancelRequest(requestId) {
  if (!confirm('¿Estás seguro de que deseas cancelar esta solicitud?')) {
    return;
  }
  
  router.patch(route('user.requests.cancel', requestId), {}, {
    preserveScroll: true,
    onSuccess: () => {
      // The page will refresh with updated data
    },
  });
}
</script>

<style src="../../../../css/User/requests_index_style.css" scoped></style>