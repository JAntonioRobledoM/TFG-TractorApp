<template>
  <AdminLayout>
    <div class="container mx-auto p-6">
      <!-- Flash message -->
      <div v-if="$page.props.flash && $page.props.flash.message" class="bg-green-100 border-l-4 border-green-600 text-green-700 p-4 mb-6 rounded-md">
        <div class="flex">
          <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-green-600" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
          </div>
          <div class="ml-3">
            <p class="text-sm">{{ $page.props.flash.message }}</p>
          </div>
        </div>
      </div>
      
      <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
          <div>
            <h2 class="text-lg leading-6 font-medium text-gray-900">
              Detalles del Anuncio
            </h2>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
              Información detallada del anuncio de tractor
            </p>
          </div>
          <div class="flex space-x-2">
            <Link 
              :href="route('listings.edit', listing.id)" 
              class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition"
            >
              Editar
            </Link>
            <Link 
              :href="route('listings.requests', listing.id)" 
              class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700 transition"
            >
              Ver Solicitudes ({{ listing.requests ? listing.requests.length : 0 }})
            </Link>
            <button 
              @click="confirmDelete" 
              class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition"
            >
              Eliminar
            </button>
            <Link 
              :href="route('listings.index')" 
              class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 transition"
            >
              Volver
            </Link>
          </div>
        </div>
        
        <div class="border-t border-gray-200">
          <dl>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Tipo de Anuncio</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 flex items-center">
                <span class="px-2 py-1 mr-2 rounded-full text-xs font-medium" 
                  :class="listing.type === 'sale' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800'">
                  {{ listing.type === 'sale' ? 'Venta' : 'Alquiler' }}
                </span>
                <span class="px-2 py-1 rounded-full text-xs font-medium" 
                  :class="listing.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'">
                  {{ listing.is_active ? 'Activo' : 'Inactivo' }}
                </span>
              </dd>
            </div>
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Precio</dt>
              <dd class="mt-1 text-sm font-bold text-green-700 sm:mt-0 sm:col-span-2">
                {{ formatCurrency(listing.price) }}
              </dd>
            </div>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Fecha de Creación</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                {{ formatDate(listing.created_at) }}
              </dd>
            </div>
            <div v-if="listing.type === 'rental'" class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Periodo de Alquiler</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                {{ formatDate(listing.start_date) }} - {{ formatDate(listing.end_date) }}
              </dd>
            </div>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Descripción</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 whitespace-pre-line">
                {{ listing.description || 'Sin descripción' }}
              </dd>
            </div>
          </dl>
        </div>
      </div>

      <!-- Información del Tractor -->
      <div v-if="listing.tractor" class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
          <h3 class="text-lg leading-6 font-medium text-gray-900">
            Información del Tractor
          </h3>
        </div>
        <div class="border-t border-gray-200">
          <dl>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Modelo</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                {{ listing.tractor.model || 'No especificado' }}
              </dd>
            </div>
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Año</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                {{ listing.tractor.year || 'No especificado' }}
              </dd>
            </div>
            <div v-if="listing.tractor.brand" class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Marca</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                {{ listing.tractor.brand }}
              </dd>
            </div>
            <div v-if="listing.tractor.power" class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Potencia</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                {{ listing.tractor.power }} HP
              </dd>
            </div>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Descripción del Tractor</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                {{ listing.tractor.description || 'Sin descripción' }}
              </dd>
            </div>
          </dl>
        </div>
      </div>

      <!-- Información del Vendedor -->
      <div v-if="listing.seller" class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
          <h3 class="text-lg leading-6 font-medium text-gray-900">
            Información del Vendedor
          </h3>
        </div>
        <div class="border-t border-gray-200">
          <dl>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Nombre</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                {{ listing.seller.first_name }} {{ listing.seller.last_name }}
              </dd>
            </div>
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Email</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                {{ listing.seller.email }}
              </dd>
            </div>
            <div v-if="listing.seller.tlf" class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                {{ listing.seller.tlf }}
              </dd>
            </div>
          </dl>
        </div>
      </div>

      <!-- Solicitudes -->
      <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
          <h3 class="text-lg leading-6 font-medium text-gray-900">
            Solicitudes ({{ listing.requests ? listing.requests.length : 0 }})
          </h3>
          <Link 
            :href="route('listings.requests', listing.id)" 
            class="text-green-600 hover:text-green-800"
          >
            Ver todas las solicitudes
          </Link>
        </div>
        <div v-if="listing.requests && listing.requests.length" class="border-t border-gray-200">
          <div 
            v-for="request in listing.requests.slice(0, 3)" 
            :key="request.id" 
            class="px-4 py-4 sm:px-6 border-b border-gray-200 last:border-b-0"
          >
            <div class="flex justify-between items-center">
              <div>
                <p class="text-sm font-medium text-gray-900">
                  {{ request.requester.first_name }} {{ request.requester.last_name }}
                </p>
                <div class="flex items-center mt-1">
                  <span class="px-2 py-0.5 rounded-full text-xs font-medium mr-2" 
                    :class="{
                      'bg-yellow-100 text-yellow-800': request.status === 'pending',
                      'bg-green-100 text-green-800': request.status === 'accepted',
                      'bg-red-100 text-red-800': request.status === 'rejected',
                      'bg-blue-100 text-blue-800': request.status === 'completed'
                    }">
                    {{ requestStatusText(request.status) }}
                  </span>
                  <p class="text-sm text-gray-500">
                    Precio: {{ formatCurrency(request.offered_price) }}
                    <span v-if="request.offered_price !== listing.price" 
                      :class="request.offered_price > listing.price ? 'text-green-600' : 'text-red-600'">
                      ({{ calculatePriceDifference(request.offered_price, listing.price) }})
                    </span>
                  </p>
                </div>
              </div>
              <Link 
                :href="route('requests.show', request.id)" 
                class="text-green-600 hover:text-green-800"
              >
                Ver detalles
              </Link>
            </div>
          </div>
        </div>
        <div v-else class="px-4 py-4 sm:px-6 text-sm text-gray-500">
          No hay solicitudes para este anuncio
        </div>
      </div>

      <!-- Delete confirmation modal -->
      <div v-if="showDeleteModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg max-w-md w-full p-6 shadow-xl">
          <div class="mb-4">
            <h3 class="text-lg font-medium text-gray-900">Confirmar eliminación</h3>
            <p class="text-sm text-gray-500 mt-2">
              ¿Estás seguro de que deseas eliminar este anuncio? Esta acción no se puede deshacer.
            </p>
            <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-md text-sm text-yellow-800">
              <p>
                <strong>Tipo:</strong> {{ listing.type === 'sale' ? 'Venta' : 'Alquiler' }}<br>
                <strong>Precio:</strong> {{ formatCurrency(listing.price) }}<br>
                <strong>Tractor:</strong> {{ listing.tractor ? listing.tractor.model || 'Tractor #' + listing.tractor_id : 'Tractor #' + listing.tractor_id }}
              </p>
              <p v-if="listing.requests && listing.requests.length > 0" class="mt-2 text-red-600">
                ¡Atención! Este anuncio tiene {{ listing.requests.length }} solicitudes. Al eliminarlo también se eliminarán todas las solicitudes asociadas.
              </p>
            </div>
          </div>
          <div class="mt-5 flex justify-end">
            <button @click="showDeleteModal = false" class="mr-3 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
              Cancelar
            </button>
            <button @click="deleteListing" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700" :disabled="isDeleting">
              {{ isDeleting ? 'Eliminando...' : 'Eliminar' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref } from 'vue';

const props = defineProps({
  listing: Object
});

const showDeleteModal = ref(false);
const isDeleting = ref(false);

const confirmDelete = () => {
  showDeleteModal.value = true;
};

const deleteListing = () => {
  isDeleting.value = true;
  router.delete(route('listings.destroy', props.listing.id), {
    onFinish: () => {
      isDeleting.value = false;
      showDeleteModal.value = false;
    }
  });
};

const formatCurrency = (value) => {
  if (!value) return '---';
  return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(value);
};

const formatDate = (dateString) => {
  if (!dateString) return 'No especificado';
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('es-ES', { dateStyle: 'medium' }).format(date);
};

const requestStatusText = (status) => {
  switch (status) {
    case 'pending': return 'Pendiente';
    case 'accepted': return 'Aceptada';
    case 'rejected': return 'Rechazada';
    case 'completed': return 'Completada';
    default: return 'Desconocido';
  }
};

const calculatePriceDifference = (offeredPrice, listingPrice) => {
  const difference = offeredPrice - listingPrice;
  const percentage = (difference / listingPrice) * 100;
  
  if (difference > 0) {
    return `+${difference.toFixed(2)}€ (+${percentage.toFixed(1)}%)`;
  } else {
    return `${difference.toFixed(2)}€ (${percentage.toFixed(1)}%)`;
  }
};
</script>