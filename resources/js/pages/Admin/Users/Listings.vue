<template>
    <AdminLayout>
      <template #header>
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
          Anuncios del Usuario
        </h2>
      </template>
      <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
          <div>
            <h1 class="text-2xl font-bold text-green-800 flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" />
                <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z" />
              </svg>
              Anuncios de {{ user.first_name }} {{ user.last_name }}
            </h1>
            <p class="text-sm text-gray-600 mt-1">@{{ user.username }} • {{ listings.length }} anuncios</p>
          </div>
          <div class="space-x-2">
            <Link 
              :href="route('users.index')" 
              class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition"
            >
              Lista de Usuarios
            </Link>
            <Link 
              :href="route('users.show', user.id)" 
              class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition"
            >
              Ver Perfil
            </Link>
          </div>
        </div>

        <!-- Información del usuario -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
          <div class="px-4 py-5 sm:px-6 bg-blue-50">
            <div class="flex items-center space-x-4">
              <div class="flex-shrink-0">
                <img 
                  v-if="user.pfp" 
                  class="h-16 w-16 rounded-full object-cover border-2 border-blue-200" 
                  :src="`/storage/${user.pfp}`" 
                  :alt="user.username"
                />
                <div v-else class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center border-2 border-blue-200">
                  <span class="text-lg font-medium text-blue-700">
                    {{ (user.first_name?.[0] || '') + (user.last_name?.[0] || '') }}
                  </span>
                </div>
              </div>
              <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ user.first_name }} {{ user.last_name }}</h3>
                <div class="mt-1 flex items-center space-x-4 text-sm text-gray-500">
                  <span>@{{ user.username }}</span>
                  <span v-if="user.email">{{ user.email }}</span>
                  <span 
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                    :class="user.is_admin ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'"
                  >
                    {{ user.is_admin ? 'Administrador' : 'Usuario' }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Filtros -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
          <div class="px-4 py-3 border-b border-gray-200">
            <div class="flex flex-wrap items-center gap-4">
              <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700">Filtros:</label>
                <select v-model="selectedType" class="text-sm border-gray-300 rounded-md">
                  <option value="">Todos los tipos</option>
                  <option value="sale">Venta</option>
                  <option value="rental">Alquiler</option>
                </select>
              </div>
              <div class="flex items-center space-x-2">
                <select v-model="selectedStatus" class="text-sm border-gray-300 rounded-md">
                  <option value="">Todos los estados</option>
                  <option value="active">Activos</option>
                  <option value="inactive">Inactivos</option>
                </select>
              </div>
              <div class="flex items-center space-x-2 text-sm text-gray-500">
                Mostrando {{ filteredListings.length }} de {{ listings.length }} anuncios
              </div>
            </div>
          </div>
        </div>
  
        <div v-if="filteredListings.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div 
            v-for="listing in filteredListings" 
            :key="listing.id" 
            class="bg-white shadow-lg overflow-hidden rounded-lg border-t-4 hover:shadow-xl transition-shadow"
            :class="listing.type === 'sale' ? 'border-green-600' : 'border-purple-600'"
          >
            <!-- Header del anuncio -->
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center"
                 :class="listing.type === 'sale' ? 'bg-green-50' : 'bg-purple-50'">
              <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                  <div class="w-10 h-10 rounded-full flex items-center justify-center"
                       :class="listing.type === 'sale' ? 'bg-green-100' : 'bg-purple-100'">
                    <svg v-if="listing.type === 'sale'" class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                    <svg v-else class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                  </div>
                </div>
                <div>
                  <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ listing.type === 'sale' ? 'Venta' : 'Alquiler' }}
                  </h3>
                  <p class="text-sm text-gray-500">
                    {{ listing.tractor ? `${listing.tractor.brand} ${listing.tractor.model}` : 'Tractor no especificado' }}
                  </p>
                </div>
              </div>
              <div class="flex flex-col items-end space-y-2">
                <span 
                  class="px-2 py-1 text-xs font-medium rounded-full"
                  :class="listing.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                >
                  {{ listing.is_active ? 'Activo' : 'Inactivo' }}
                </span>
                <div class="text-right">
                  <div class="text-lg font-bold text-gray-900">{{ formatCurrency(listing.price) }}</div>
                  <div v-if="listing.type === 'rental'" class="text-xs text-gray-500">por período</div>
                </div>
              </div>
            </div>

            <!-- Información del tractor -->
            <div v-if="listing.tractor" class="px-4 py-3 bg-gray-50 border-t border-gray-200">
              <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                  <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
                    <img 
                      v-if="listing.tractor.image" 
                      :src="`/storage/${listing.tractor.image}`" 
                      :alt="`${listing.tractor.brand} ${listing.tractor.model}`"
                      class="w-full h-full object-cover"
                    />
                    <svg v-else class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                  </div>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium text-gray-900">
                    {{ listing.tractor.brand }} {{ listing.tractor.model }}
                  </p>
                  <div class="mt-1 flex items-center space-x-4 text-xs text-gray-500">
                    <span v-if="listing.tractor.year">{{ listing.tractor.year }}</span>
                    <span v-if="listing.tractor.horsepower">{{ listing.tractor.horsepower }} CV</span>
                    <span v-if="listing.tractor.license_plate" class="font-mono">{{ listing.tractor.license_plate }}</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Detalles del anuncio -->
            <div class="border-t border-gray-200">
              <dl class="divide-y divide-gray-200">
                <!-- Descripción -->
                <div v-if="listing.description" class="px-4 py-3">
                  <dt class="text-sm font-medium text-gray-500 mb-1">Descripción</dt>
                  <dd class="text-sm text-gray-900">
                    {{ listing.description.length > 120 ? listing.description.substring(0, 120) + '...' : listing.description }}
                  </dd>
                </div>

                <!-- Periodo para alquileres -->
                <div v-if="listing.type === 'rental' && (listing.start_date || listing.end_date)" class="px-4 py-3">
                  <dt class="text-sm font-medium text-gray-500 mb-1">Período de Alquiler</dt>
                  <dd class="text-sm text-gray-900 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    {{ formatDate(listing.start_date) }} - {{ formatDate(listing.end_date) }}
                  </dd>
                </div>

                <!-- Fecha de creación -->
                <div class="px-4 py-3 bg-gray-50">
                  <dt class="text-sm font-medium text-gray-500 mb-1">Publicado</dt>
                  <dd class="text-sm text-gray-900">
                    {{ formatDate(listing.created_at) }}
                  </dd>
                </div>
              </dl>
            </div>

            <!-- Estadísticas del anuncio -->
            <div v-if="listing.requests && listing.requests.length > 0" class="px-4 py-3 bg-yellow-50 border-t border-gray-200">
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                  <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a2 2 0 01-2-2v-1M3 4h12v12H7l-4 4V4z"></path>
                  </svg>
                  <span class="text-sm font-medium text-yellow-800">
                    {{ listing.requests.length }} solicitud{{ listing.requests.length !== 1 ? 'es' : '' }}
                  </span>
                </div>
                <Link 
                  :href="route('listings.requests', listing.id)" 
                  class="text-yellow-600 hover:text-yellow-800 text-sm font-medium"
                >
                  Ver →
                </Link>
              </div>
            </div>

            <!-- Acciones -->
            <div class="px-4 py-3 flex justify-between items-center"
                 :class="listing.type === 'sale' ? 'bg-green-50' : 'bg-purple-50'">
              <Link 
                :href="route('listings.show', listing.id)" 
                class="inline-flex items-center font-medium text-sm"
                :class="listing.type === 'sale' ? 'text-green-600 hover:text-green-900' : 'text-purple-600 hover:text-purple-900'"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                  <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                </svg>
                Ver anuncio
              </Link>
              <div class="flex space-x-2">
                <Link 
                  :href="route('listings.edit', listing.id)" 
                  class="text-blue-600 hover:text-blue-900 text-sm"
                >
                  Editar
                </Link>
                <button 
                  @click="toggleStatus(listing.id, listing.is_active)" 
                  class="text-sm"
                  :class="listing.is_active ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900'"
                >
                  {{ listing.is_active ? 'Desactivar' : 'Activar' }}
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Estado vacío -->
        <div v-else-if="listings.length === 0" class="bg-white shadow-lg overflow-hidden rounded-lg border-t-4 border-blue-600">
          <div class="px-6 py-12 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No hay anuncios publicados</h3>
            <p class="text-gray-500 mb-6">Este usuario aún no ha publicado ningún anuncio de venta o alquiler.</p>
          </div>
        </div>

        <!-- Mensaje de filtros sin resultados -->
        <div v-else class="bg-white shadow-lg overflow-hidden rounded-lg border-t-4 border-yellow-600">
          <div class="px-6 py-12 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No se encontraron anuncios</h3>
            <p class="text-gray-500 mb-6">No hay anuncios que coincidan con los filtros seleccionados.</p>
            <button 
              @click="clearFilters" 
              class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700 transition"
            >
              Limpiar Filtros
            </button>
          </div>
        </div>

        <!-- Resumen estadístico -->
        <div v-if="listings.length" class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
          <div class="px-4 py-5 sm:px-6 bg-gray-50">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Resumen de Anuncios</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Estadísticas de los anuncios de este usuario</p>
          </div>
          <div class="border-t border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-4 divide-y md:divide-y-0 md:divide-x divide-gray-200">
              <div class="px-6 py-4 text-center">
                <div class="text-2xl font-bold text-blue-600">{{ listings.length }}</div>
                <div class="text-sm text-gray-500">Total Anuncios</div>
              </div>
              <div class="px-6 py-4 text-center">
                <div class="text-2xl font-bold text-green-600">{{ activeListings }}</div>
                <div class="text-sm text-gray-500">Activos</div>
              </div>
              <div class="px-6 py-4 text-center">
                <div class="text-2xl font-bold text-purple-600">{{ saleListings }}</div>
                <div class="text-sm text-gray-500">Ventas</div>
              </div>
              <div class="px-6 py-4 text-center">
                <div class="text-2xl font-bold text-orange-600">{{ rentalListings }}</div>
                <div class="text-sm text-gray-500">Alquileres</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </AdminLayout>
</template>
  
<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref, computed } from 'vue';

const props = defineProps({
  user: Object,
  listings: Array
});

const selectedType = ref('');
const selectedStatus = ref('');

const filteredListings = computed(() => {
  let filtered = props.listings;
  
  if (selectedType.value) {
    filtered = filtered.filter(listing => listing.type === selectedType.value);
  }
  
  if (selectedStatus.value) {
    const isActive = selectedStatus.value === 'active';
    filtered = filtered.filter(listing => listing.is_active === isActive);
  }
  
  return filtered;
});

const activeListings = computed(() => {
  return props.listings.filter(listing => listing.is_active).length;
});

const saleListings = computed(() => {
  return props.listings.filter(listing => listing.type === 'sale').length;
});

const rentalListings = computed(() => {
  return props.listings.filter(listing => listing.type === 'rental').length;
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

const clearFilters = () => {
  selectedType.value = '';
  selectedStatus.value = '';
};

const toggleStatus = (listingId, currentStatus) => {
  const action = currentStatus ? 'desactivar' : 'activar';
  if (confirm(`¿Estás seguro de que quieres ${action} este anuncio?`)) {
    router.patch(route('listings.toggle-status', listingId));
  }
};
</script>

<style src="../../../../css/Admin/Users/user_listings_style.css" scoped></style>