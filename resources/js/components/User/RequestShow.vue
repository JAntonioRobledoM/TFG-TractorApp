<!-- resources/js/Components/User/RequestShow.vue -->
<template>
  <div class="fixed inset-0 bg-gray-800 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center px-4">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden max-w-3xl w-full mx-auto">
      <div class="p-4 bg-green-700 text-white font-medium flex items-center justify-between">
        <div class="flex items-center space-x-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v7h-2l-1 2H8l-1-2H5V5z" clip-rule="evenodd" />
          </svg>
          <span>Detalles de la Solicitud</span>
        </div>
        <button @click="$emit('close')" class="text-white hover:text-green-200 transition duration-200">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </button>
      </div>
      <div class="p-6 space-y-5">
        <!-- Estado y número -->
        <div class="flex justify-between items-center">
          <h3 class="text-xl font-bold text-gray-800">Solicitud #{{ request.id }}</h3>
          <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
            :class="{
              'bg-yellow-100 text-yellow-800': request.status === 'pending',
              'bg-green-100 text-green-800': request.status === 'accepted',
              'bg-red-100 text-red-800': request.status === 'rejected',
              'bg-blue-100 text-blue-800': request.status === 'completed',
              'bg-gray-100 text-gray-800': request.status === 'cancelled'
            }">
            {{ requestStatusText(request.status) }}
          </span>
        </div>

        <!-- Fecha y tipo -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <p class="text-sm text-gray-500">Fecha de solicitud</p>
            <p class="font-medium">{{ formatDate(request.created_at) }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-500">Tipo</p>
            <p class="font-medium">{{ request.type === 'sale' ? 'Compra' : 'Alquiler' }}</p>
          </div>
        </div>

        <!-- Información del precio -->
        <div>
          <h4 class="text-lg font-medium text-gray-800 mb-3">Información económica</h4>
          <div class="bg-gray-50 p-4 rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <p class="text-sm text-gray-500">Precio ofrecido</p>
                <p class="text-xl font-bold text-green-600">{{ formatCurrency(request.offered_price) }}</p>
              </div>
              <div v-if="request.listing && request.listing.price">
                <p class="text-sm text-gray-500">Precio anunciado</p>
                <p class="font-medium">{{ formatCurrency(request.listing.price) }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Fechas de alquiler (solo si es alquiler) -->
        <div v-if="request.type === 'rental'">
          <h4 class="text-lg font-medium text-gray-800 mb-3">Período de alquiler solicitado</h4>
          <div class="bg-gray-50 p-4 rounded-lg">
            <div class="grid grid-cols-2 gap-4">
              <div>
                <p class="text-sm text-gray-500">Fecha de inicio</p>
                <p class="font-medium">{{ formatDate(request.requested_start_date) }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Fecha de fin</p>
                <p class="font-medium">{{ formatDate(request.requested_end_date) }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Información del anuncio relacionado -->
        <div v-if="request.listing">
          <h4 class="text-lg font-medium text-gray-800 mb-3">Anuncio relacionado</h4>
          <div class="bg-gray-50 p-4 rounded-lg space-y-2">
            <div v-if="request.listing.tractor" class="flex justify-between items-center">
              <div>
                <p class="font-medium text-gray-800">
                  {{ request.listing.tractor.brand }} {{ request.listing.tractor.model }}
                </p>
                <p class="text-sm text-gray-600">
                  {{ request.listing.tractor.year ? `Año: ${request.listing.tractor.year}` : 'Sin año especificado' }}
                </p>
              </div>
              <button 
                @click="$emit('view-listing', request.listing)" 
                class="text-green-600 hover:text-green-700 text-sm font-medium"
              >
                Ver anuncio
              </button>
            </div>
            <div v-else class="text-gray-500">
              Información del anuncio no disponible
            </div>
          </div>
        </div>

        <!-- Mensaje de la solicitud -->
        <div v-if="request.message">
          <h4 class="text-lg font-medium text-gray-800 mb-3">Mensaje</h4>
          <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-gray-700 whitespace-pre-line">{{ request.message }}</p>
          </div>
        </div>

        <!-- Acciones (según el estado y si es el propietario) -->
        <div class="flex space-x-3 pt-4 border-t border-gray-100">
          <!-- Si es una solicitud enviada por el usuario actual y está pendiente -->
          <template v-if="isRequester && request.status === 'pending'">
            <button 
              @click="$emit('cancel', request)" 
              class="flex-1 py-2 bg-red-600 text-white hover:bg-red-700 rounded-md transition duration-200 flex items-center justify-center"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
              Cancelar solicitud
            </button>
          </template>
          
          <!-- Si es una solicitud recibida por el usuario actual y está pendiente -->
          <template v-if="!isRequester && request.status === 'pending'">
            <button 
              @click="$emit('accept', request)" 
              class="flex-1 py-2 bg-green-600 text-white hover:bg-green-700 rounded-md transition duration-200 flex items-center justify-center"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
              </svg>
              Aceptar
            </button>
            <button 
              @click="$emit('reject', request)" 
              class="flex-1 py-2 bg-red-600 text-white hover:bg-red-700 rounded-md transition duration-200 flex items-center justify-center"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
              Rechazar
            </button>
          </template>
          
          <!-- Si la solicitud está aceptada y es una venta pendiente de completar -->
          <template v-if="request.status === 'accepted' && request.type === 'sale'">
            <button 
              @click="$emit('complete', request)" 
              class="flex-1 py-2 bg-blue-600 text-white hover:bg-blue-700 rounded-md transition duration-200 flex items-center justify-center"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
              </svg>
              Marcar como completada
            </button>
          </template>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';

const props = defineProps({
  request: {
    type: Object,
    required: true
  },
  currentUserId: {
    type: Number,
    required: true
  }
});

const isRequester = computed(() => {
  return props.request.requester_id === props.currentUserId;
});

defineEmits(['close', 'cancel', 'accept', 'reject', 'complete', 'view-listing']);

// Formateador de moneda (reutilizado del dashboard)
const formatCurrency = (value) => {
  if (!value) return '---';
  return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(value);
};

// Formateador de fecha
const formatDate = (date) => {
  if (!date) return 'No especificada';
  return new Date(date).toLocaleDateString('es-ES', { 
    day: '2-digit',
    month: '2-digit', 
    year: 'numeric'
  });
};

// Texto para el estado de la solicitud (reutilizado del dashboard)
const requestStatusText = (status) => {
  switch (status) {
    case 'pending': return 'Pendiente';
    case 'accepted': return 'Aceptada';
    case 'rejected': return 'Rechazada';
    case 'completed': return 'Completada';
    case 'cancelled': return 'Cancelada';
    default: return status;
  }
};

function cancelRequest() {
  if (!confirm('¿Estás seguro de que deseas cancelar esta solicitud?')) {
    return;
  }
  
  router.patch(route('user.requests.cancel', props.request.id), {}, {
    onSuccess: () => {
      // Recargar la página después de cancelar
      router.reload();
    },
    onError: (error) => {
      console.error('Error al cancelar la solicitud:', error);
    }
  });
}
</script>