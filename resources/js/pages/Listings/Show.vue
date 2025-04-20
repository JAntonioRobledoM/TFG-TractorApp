<template>
  <AdminLayout>
    <div class="container mx-auto p-6">
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
            <button 
              @click="confirmDelete" 
              class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition"
            >
              Eliminar
            </button>
          </div>
        </div>
        
        <div class="border-t border-gray-200">
          <dl>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Tipo de Anuncio</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                {{ listing.type === 'sale' ? 'Venta' : 'Alquiler' }}
              </dd>
            </div>
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Precio</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                {{ formatCurrency(listing.price) }}
              </dd>
            </div>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Estado</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                {{ listing.is_active ? 'Activo' : 'Inactivo' }}
              </dd>
            </div>
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Descripción</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                {{ listing.description || 'Sin descripción' }}
              </dd>
            </div>
            <div v-if="listing.type === 'rental'" class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Periodo de Alquiler</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                {{ formatDate(listing.start_date) }} - {{ formatDate(listing.end_date) }}
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
                <p class="text-sm text-gray-500">
                  Estado: {{ requestStatusText(request.status) }} | 
                  Precio: {{ formatCurrency(request.offered_price) }}
                </p>
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
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  listing: Object
});

const confirmDelete = () => {
  if (confirm('¿Estás seguro de que quieres eliminar este anuncio?')) {
    router.delete(route('listings.destroy', props.listing.id));
  }
};

const formatCurrency = (value) => {
  if (!value) return '---';
  return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(value);
};

const formatDate = (dateString) => {
  if (!dateString) return 'No especificado';
  return new Date(dateString).toLocaleDateString('es-ES');
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
</script>