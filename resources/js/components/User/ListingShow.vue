<!-- resources/js/Components/User/ListingShow.vue -->
<template>
  <div class="fixed inset-0 bg-gray-800 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center px-4">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden max-w-3xl w-full mx-auto">
      <div class="p-4 bg-green-700 text-white font-medium flex items-center justify-between">
        <div class="flex items-center space-x-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" />
            <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z" />
          </svg>
          <span>Detalles del Anuncio</span>
        </div>
        <button @click="$emit('close')" class="text-white hover:text-green-200 transition duration-200">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </button>
      </div>
      <div class="p-6 space-y-5">
        <!-- Estado y tipo -->
        <div class="flex justify-between">
          <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
            :class="{
              'bg-green-100 text-green-800': listing.is_active,
              'bg-gray-100 text-gray-800': !listing.is_active
            }">
            {{ listing.is_active ? 'Activo' : 'Inactivo' }}
          </span>
          <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
            :class="{
              'bg-blue-100 text-blue-800': listing.type === 'rental',
              'bg-purple-100 text-purple-800': listing.type === 'sale'
            }">
            {{ listing.type === 'rental' ? 'Alquiler' : 'Venta' }}
          </span>
        </div>

        <!-- Información del tractor -->
        <div v-if="listing.tractor">
          <h4 class="text-lg font-medium text-gray-800 mb-3">Información del tractor</h4>
          <div class="bg-gray-50 p-4 rounded-lg space-y-2">
            <div class="text-xl font-medium text-gray-800">
              {{ listing.tractor.brand }} {{ listing.tractor.model }}
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <p class="text-sm text-gray-500">Año</p>
                <p class="font-medium">{{ listing.tractor.year || 'No especificado' }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Potencia</p>
                <p class="font-medium">{{ listing.tractor.horsepower ? `${listing.tractor.horsepower} CV` : 'No especificada' }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Detalles del anuncio -->
        <div>
          <h4 class="text-lg font-medium text-gray-800 mb-3">Detalles del anuncio</h4>
          <div class="grid grid-cols-2 gap-6">
            <div>
              <p class="text-sm text-gray-500">Precio</p>
              <p class="text-xl font-bold text-green-600">{{ formatCurrency(listing.price) }}</p>
              <p class="text-xs text-gray-500">
                {{ listing.type === 'rental' ? 'por día' : 'precio de venta' }}
              </p>
            </div>
            <div v-if="listing.type === 'rental'">
              <p class="text-sm text-gray-500">Disponibilidad</p>
              <p class="font-medium">
                {{ formatDate(listing.start_date) }} - {{ formatDate(listing.end_date) }}
              </p>
            </div>
          </div>
        </div>

        <!-- Descripción -->
        <div>
          <h4 class="text-lg font-medium text-gray-800 mb-3">Descripción</h4>
          <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-gray-700 whitespace-pre-line">{{ listing.description || 'No hay descripción disponible.' }}</p>
          </div>
        </div>

        <!-- Solicitudes recibidas -->
        <div v-if="listing.requests && listing.requests.length > 0">
          <h4 class="text-lg font-medium text-gray-800 mb-3">Solicitudes recibidas ({{ listing.requests.length }})</h4>
          <div class="space-y-2">
            <div 
              v-for="request in listing.requests" 
              :key="request.id" 
              class="bg-gray-50 p-3 rounded-lg flex justify-between items-center"
            >
              <div>
                <p class="font-medium">
                  Solicitud de {{ request.requester?.first_name || `Usuario #${request.requester_id}` }}
                </p>
                <p class="text-sm text-gray-500">
                  {{ formatDate(request.created_at) }} - 
                  <span class="font-medium" :class="{
                    'text-yellow-600': request.status === 'pending',
                    'text-green-600': request.status === 'accepted',
                    'text-red-600': request.status === 'rejected',
                    'text-blue-600': request.status === 'completed'
                  }">
                    {{ requestStatusText(request.status) }}
                  </span>
                </p>
              </div>
              <button 
                @click="$emit('view-request', request)" 
                class="text-green-600 hover:text-green-700 text-sm font-medium"
              >
                Ver detalles
              </button>
            </div>
          </div>
        </div>

        <!-- Acciones -->
        <div class="flex space-x-3 pt-4 border-t border-gray-100">
          <button 
            @click="$emit('edit', listing)" 
            class="flex-1 py-2 bg-green-600 text-white hover:bg-green-700 rounded-md transition duration-200 flex items-center justify-center"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
              <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
            </svg>
            Editar
          </button>
          <button 
            @click="$emit('toggle-status', listing)" 
            class="flex-1 py-2 bg-yellow-600 text-white hover:bg-yellow-700 rounded-md transition duration-200 flex items-center justify-center"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
            </svg>
            {{ listing.is_active ? 'Desactivar' : 'Activar' }}
          </button>
          <button 
            @click="$emit('delete', listing)" 
            class="flex-1 py-2 bg-red-600 text-white hover:bg-red-700 rounded-md transition duration-200 flex items-center justify-center"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            Eliminar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';

const props = defineProps({
  listing: {
    type: Object,
    required: true
  }
});

defineEmits(['close', 'edit', 'delete', 'toggle-status', 'view-request']);

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
</script>