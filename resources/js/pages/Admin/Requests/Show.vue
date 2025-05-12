<template>
  <AdminLayout>
    <template #header>
      <h2 class="font-semibold text-2xl text-white leading-tight">
        Detalles de Solicitud
      </h2>
    </template>

    <div class="container mx-auto p-6">
      <div v-if="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ successMessage }}
      </div>
      
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-green-800">
          Solicitud #{{ request.id }}
        </h1>
        <div class="flex space-x-2">
          <Link 
            :href="route('requests.index')" 
            class="text-gray-600 hover:text-gray-900 px-4 py-2 rounded"
          >
            Volver a Solicitudes
          </Link>
          <Link 
            :href="route('requests.edit', request.id)"
            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition"
          >
            Editar
          </Link>
        </div>
      </div>

      <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
          <div class="flex justify-between items-center">
            <div>
              <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                <span 
                  class="mr-3 px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                  :class="{
                    'bg-blue-100 text-blue-800': request.type === 'sale',
                    'bg-yellow-100 text-yellow-800': request.type === 'rental'
                  }"
                >
                  {{ request.type === 'sale' ? 'Compra' : 'Alquiler' }}
                </span>
                <span 
                  class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                  :class="{
                    'bg-yellow-100 text-yellow-800': request.status === 'pending',
                    'bg-green-100 text-green-800': request.status === 'accepted',
                    'bg-red-100 text-red-800': request.status === 'rejected',
                    'bg-blue-100 text-blue-800': request.status === 'completed'
                  }"
                >
                  {{ requestStatusText(request.status) }}
                </span>
              </h3>
              <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Creada el {{ formatDate(request.created_at) }}
              </p>
            </div>
            <div class="text-2xl font-bold text-green-700">
              {{ formatCurrency(request.offered_price) }}
            </div>
          </div>
        </div>

        <div class="px-4 py-5 sm:p-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Requester Information -->
            <div>
              <h4 class="text-lg font-medium text-green-800 mb-4">
                Información del Solicitante
              </h4>
              <div class="bg-green-50 rounded-lg p-4">
                <div class="flex items-center mb-3">
                  <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                    <span class="text-green-800 font-semibold">
                      {{ request.requester && request.requester.first_name ? 
                        (request.requester.first_name.charAt(0) + (request.requester.last_name ? request.requester.last_name.charAt(0) : '')) : 
                        '?' 
                      }}
                    </span>
                  </div>
                  <div>
                    <div class="text-sm font-medium text-gray-900">
                      {{ request.requester ? 
                        (request.requester.first_name + ' ' + (request.requester.last_name || '')) : 
                        'Usuario #' + request.requester_id 
                      }}
                    </div>
                    <div class="text-sm text-gray-600">
                      {{ request.requester ? request.requester.email : '' }}
                    </div>
                  </div>
                </div>
                <Link 
                  v-if="request.requester"
                  :href="route('users.show', request.requester.id)"
                  class="text-green-600 hover:text-green-800 text-sm"
                >
                  Ver perfil completo
                </Link>
              </div>
            </div>

            <!-- Listing Information -->
            <div>
              <h4 class="text-lg font-medium text-green-800 mb-4">
                Detalles del Anuncio
              </h4>
              <div class="bg-green-50 rounded-lg p-4">
                <div class="flex justify-between items-center mb-3">
                  <div>
                    <div class="text-sm font-medium text-gray-900">
                      {{ request.listing && request.listing.tractor ? request.listing.tractor.model : 'Tractor #' + (request.listing ? request.listing.tractor_id : '') }}
                    </div>
                    <div class="text-sm text-gray-600">
                      {{ request.listing ? (request.listing.type === 'sale' ? 'Venta' : 'Alquiler') : '' }}
                    </div>
                  </div>
                  <div class="text-sm font-bold text-green-700">
                    {{ formatCurrency(request.listing ? request.listing.price : null) }}
                  </div>
                </div>
                <Link 
                  v-if="request.listing"
                  :href="route('listings.show', request.listing.id)"
                  class="text-green-600 hover:text-green-800 text-sm"
                >
                  Ver detalles del anuncio
                </Link>
              </div>
            </div>

            <!-- Rental Dates (for rental requests) -->
            <div v-if="request.type === 'rental' && (request.requested_start_date || request.requested_end_date)">
              <h4 class="text-lg font-medium text-green-800 mb-4">
                Periodo de Alquiler
              </h4>
              <div class="bg-yellow-50 rounded-lg p-4 flex justify-between items-center">
                <div>
                  <div class="text-sm text-gray-600">Fecha de Inicio</div>
                  <div class="font-medium text-gray-900">
                    {{ formatDate(request.requested_start_date) }}
                  </div>
                </div>
                <div>
                  <div class="text-sm text-gray-600">Fecha de Fin</div>
                  <div class="font-medium text-gray-900">
                    {{ formatDate(request.requested_end_date) }}
                  </div>
                </div>
              </div>
            </div>

            <!-- Message -->
            <div v-if="request.message" class="md:col-span-2">
              <h4 class="text-lg font-medium text-green-800 mb-4">
                Mensaje
              </h4>
              <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-gray-700">{{ request.message }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="px-4 py-4 bg-gray-50 flex justify-end space-x-3 border-t border-gray-200">
          <button 
            v-if="request.status === 'pending'"
            @click="acceptRequest"
            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition"
            :disabled="processing"
          >
            {{ processing ? 'Procesando...' : 'Aceptar' }}
          </button>
          <button 
            v-if="request.status === 'pending'"
            @click="rejectRequest"
            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition"
            :disabled="processing"
          >
            {{ processing ? 'Procesando...' : 'Rechazar' }}
          </button>
          <button 
            v-if="request.status === 'accepted'"
            @click="completeRequest"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
            :disabled="processing"
          >
            {{ processing ? 'Procesando...' : 'Marcar como Completado' }}
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref } from 'vue';

const props = defineProps({
  request: {
    type: Object,
    required: true
  }
});

const processing = ref(false);
const successMessage = ref('');

const formatCurrency = (value) => {
  if (!value) return '---';
  return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(value);
};

const formatDate = (dateString) => {
  if (!dateString) return '---';
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('es-ES', { 
    dateStyle: 'long', 
    timeStyle: 'short' 
  }).format(date);
};

const requestStatusText = (status) => {
  switch (status) {
    case 'pending': return 'Pendiente';
    case 'accepted': return 'Aceptada';
    case 'rejected': return 'Rechazada';
    case 'completed': return 'Completada';
    default: return status;
  }
};

// Utiliza las rutas específicas para cada acción
const acceptRequest = () => {
  processing.value = true;
  router.post(route('requests.accept', props.request.id), {}, {
    preserveScroll: true,
    onSuccess: () => {
      processing.value = false;
      successMessage.value = 'Solicitud aceptada correctamente';
    },
    onError: (errors) => {
      processing.value = false;
      console.error('Error al aceptar la solicitud', errors);
    }
  });
};

const rejectRequest = () => {
  processing.value = true;
  router.post(route('requests.reject', props.request.id), {}, {
    preserveScroll: true,
    onSuccess: () => {
      processing.value = false;
      successMessage.value = 'Solicitud rechazada correctamente';
    },
    onError: (errors) => {
      processing.value = false;
      console.error('Error al rechazar la solicitud', errors);
    }
  });
};

const completeRequest = () => {
  processing.value = true;
  router.post(route('requests.complete', props.request.id), {}, {
    preserveScroll: true,
    onSuccess: () => {
      processing.value = false;
      successMessage.value = 'Solicitud completada correctamente';
    },
    onError: (errors) => {
      processing.value = false;
      console.error('Error al completar la solicitud', errors);
    }
  });
};
</script>