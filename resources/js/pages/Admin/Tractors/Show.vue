<template>
  <AdminLayout>
    <template #header>
      <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
        Detalles del Tractor
      </h2>
    </template>
    <div class="container mx-auto p-6">
      <!-- Header con información principal -->
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-2xl font-bold text-green-800">
            {{ tractor.brand ? tractor.brand + ' ' + (tractor.model || '') : 'Tractor' }}
          </h1>
          <p v-if="tractor.license_plate" class="text-sm text-gray-600 mt-1">
            Matrícula: <span class="font-mono bg-gray-100 px-2 py-1 rounded">{{ tractor.license_plate }}</span>
          </p>
        </div>
        <div class="space-x-2">
          <Link 
            :href="route('tractors.index')" 
            class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition"
          >
            Lista de Tractores
          </Link>
          <Link 
            :href="route('tractors.edit', tractor.id)" 
            class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700 transition"
          >
            Editar
          </Link>
          <Link 
            :href="route('tractors.attach-aperos', tractor.id)" 
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
          >
            Gestionar Aperos
          </Link>
          <button 
            @click="confirmDelete" 
            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition"
          >
            Eliminar
          </button>
        </div>
      </div>

      <!-- Grid principal: Información e Imagen -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Información del Tractor -->
        <div class="lg:col-span-2">
          <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 bg-green-50">
              <h3 class="text-lg leading-6 font-medium text-gray-900">Información del Tractor</h3>
              <p class="mt-1 max-w-2xl text-sm text-gray-500">Especificaciones técnicas y detalles</p>
            </div>
            <div class="border-t border-gray-200">
              <dl>
                <!-- Información básica -->
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500">Marca</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ tractor.brand || 'No especificada' }}
                  </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500">Modelo</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ tractor.model || 'No especificado' }}
                  </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500">Año</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ tractor.year || 'No especificado' }}
                  </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500">Color</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <div class="flex items-center space-x-2">
                      <span>{{ tractor.color || 'No especificado' }}</span>
                      <div 
                        v-if="tractor.color" 
                        class="w-4 h-4 rounded-full border border-gray-300"
                        :style="{ backgroundColor: getColorValue(tractor.color) }"
                      ></div>
                    </div>
                  </dd>
                </div>

                <!-- Información técnica -->
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500">Potencia</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ tractor.horsepower ? `${tractor.horsepower} CV` : 'No especificada' }}
                  </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500">Horas de trabajo</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ tractor.working_hours ? formatHours(tractor.working_hours) : 'No especificadas' }}
                  </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500">Estado</dt>
                  <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">
                    <span 
                      class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                      :class="tractor.is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                    >
                      {{ tractor.is_available ? 'Disponible' : 'No disponible' }}
                    </span>
                  </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500">Descripción</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ tractor.description || 'Sin descripción' }}
                  </dd>
                </div>
              </dl>
            </div>
          </div>
        </div>

        <!-- Imagen del Tractor -->
        <div class="lg:col-span-1">
          <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 bg-green-50">
              <h3 class="text-lg leading-6 font-medium text-gray-900">Imagen</h3>
            </div>
            <div class="p-6">
              <div class="w-full aspect-square rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
                <img 
                  v-if="tractor.image" 
                  :src="`/storage/${tractor.image}`" 
                  :alt="`${tractor.brand} ${tractor.model}`"
                  class="w-full h-full object-cover"
                />
                <div v-else class="text-center">
                  <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                  </svg>
                  <p class="mt-2 text-sm text-gray-500">Sin imagen</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Propietarios -->
      <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 bg-green-50 flex justify-between items-center">
          <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">Propietarios</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Usuarios que poseen este tractor</p>
          </div>
          <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
            {{ tractor.owners?.length || 0 }} propietarios
          </span>
        </div>
        <div v-if="tractor.owners && tractor.owners.length" class="border-t border-gray-200">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-6">
            <div v-for="user in tractor.owners" :key="user.id" class="bg-gray-50 rounded-lg p-4">
              <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                  <img 
                    v-if="user.pfp" 
                    class="h-12 w-12 rounded-full object-cover" 
                    :src="`/storage/${user.pfp}`" 
                    :alt="user.username"
                  />
                  <div v-else class="h-12 w-12 rounded-full bg-gray-300 flex items-center justify-center">
                    <span class="text-sm font-medium text-gray-700">
                      {{ (user.first_name?.[0] || '') + (user.last_name?.[0] || '') }}
                    </span>
                  </div>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium text-gray-900 truncate">
                    {{ user.first_name }} {{ user.last_name }}
                  </p>
                  <p class="text-sm text-gray-500 truncate">
                    @{{ user.username }}
                  </p>
                  <p class="text-xs text-gray-500 truncate">
                    {{ user.email }}
                  </p>
                </div>
              </div>
              <div class="mt-3">
                <Link 
                  :href="route('users.show', user.id)" 
                  class="text-green-600 hover:text-green-900 text-sm font-medium"
                >
                  Ver perfil →
                </Link>
              </div>
            </div>
          </div>
        </div>
        <div v-else class="px-4 py-8 text-center text-gray-500">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
          </svg>
          <p class="mt-2">No hay propietarios asignados a este tractor</p>
        </div>
      </div>

      <!-- Aperos Conectados -->
      <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 bg-blue-50 flex justify-between items-center">
          <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">Aperos Conectados</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Aperos que han estado conectados a este tractor</p>
          </div>
          <div class="flex items-center space-x-2">
            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
              {{ tractor.aperos?.length || 0 }} aperos
            </span>
            <Link 
              :href="route('tractors.attach-aperos', tractor.id)" 
              class="text-blue-600 hover:text-blue-800 text-sm font-medium"
            >
              Gestionar →
            </Link>
          </div>
        </div>
        <div v-if="tractor.aperos && tractor.aperos.length" class="border-t border-gray-200">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-blue-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Imagen</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Nombre</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Tipo</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Conectado</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Estado</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Acciones</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="apero in tractor.aperos" :key="apero.id" class="hover:bg-blue-50 transition">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
                      <img 
                        v-if="apero.image" 
                        :src="`/storage/${apero.image}`" 
                        :alt="apero.name"
                        class="w-full h-full object-cover"
                      />
                      <svg v-else class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                      </svg>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ apero.name }}</div>
                    <div class="text-sm text-gray-500">{{ apero.brand }} {{ apero.model }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ apero.type || 'Sin tipo' }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ apero.pivot?.attached_at ? formatDate(apero.pivot.attached_at) : 'No registrado' }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span 
                      class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                      :class="!apero.pivot?.detached_at ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                    >
                      {{ !apero.pivot?.detached_at ? 'Conectado' : 'Desconectado' }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <Link 
                      :href="route('aperos.show', apero.id)" 
                      class="text-blue-600 hover:text-blue-900"
                    >
                      Ver detalles
                    </Link>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div v-else class="px-4 py-8 text-center text-gray-500">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
          </svg>
          <p class="mt-2">No hay aperos conectados a este tractor</p>
          <Link 
            :href="route('tractors.attach-aperos', tractor.id)" 
            class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
          >
            Conectar Aperos
          </Link>
        </div>
      </div>

      <!-- Anuncios Relacionados -->
      <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 bg-yellow-50 flex justify-between items-center">
          <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">Anuncios Relacionados</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Anuncios de venta y alquiler de este tractor</p>
          </div>
          <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
            {{ tractor.listings?.length || 0 }} anuncios
          </span>
        </div>
        <div v-if="tractor.listings && tractor.listings.length" class="border-t border-gray-200 divide-y divide-gray-200">
          <div v-for="listing in tractor.listings" :key="listing.id" class="px-6 py-4">
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-4">
                <div class="flex space-x-2">
                  <span 
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                    :class="listing.type === 'sale' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800'"
                  >
                    {{ listing.type === 'sale' ? 'Venta' : 'Alquiler' }}
                  </span>
                  <span 
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                    :class="listing.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                  >
                    {{ listing.is_active ? 'Activo' : 'Inactivo' }}
                  </span>
                </div>
                <div>
                  <p class="text-sm font-medium text-gray-900">
                    {{ formatCurrency(listing.price) }}
                  </p>
                  <p v-if="listing.type === 'rental' && listing.start_date && listing.end_date" class="text-xs text-gray-500">
                    {{ formatDate(listing.start_date) }} - {{ formatDate(listing.end_date) }}
                  </p>
                  <p v-if="listing.seller" class="text-xs text-gray-500">
                    Vendedor: {{ listing.seller.first_name }} {{ listing.seller.last_name }}
                  </p>
                </div>
              </div>
              <Link 
                :href="route('listings.show', listing.id)" 
                class="text-yellow-600 hover:text-yellow-900 text-sm font-medium"
              >
                Ver anuncio →
              </Link>
            </div>
          </div>
        </div>
        <div v-else class="px-4 py-8 text-center text-gray-500">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
          </svg>
          <p class="mt-2">No hay anuncios relacionados con este tractor</p>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  tractor: Object
});

const confirmDelete = () => {
  if (confirm('¿Estás seguro de que quieres eliminar este tractor?')) {
    router.delete(route('tractors.destroy', props.tractor.id));
  }
};

const formatCurrency = (value) => {
  if (!value) return '---';
  return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(value);
};

const formatDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('es-ES', { 
    dateStyle: 'medium',
    timeStyle: 'short'
  }).format(date);
};

const formatHours = (hours) => {
  if (hours >= 1000) {
    return `${(hours / 1000).toFixed(1)}k h`;
  }
  return `${hours} h`;
};

const getColorValue = (colorName) => {
  const colors = {
    'rojo': '#dc2626',
    'azul': '#2563eb',
    'verde': '#16a34a',
    'amarillo': '#ca8a04',
    'negro': '#000000',
    'blanco': '#ffffff',
    'gris': '#6b7280',
    'naranja': '#ea580c'
  };
  return colors[colorName?.toLowerCase()] || colorName || '#6b7280';
};
</script>

<style src="../../../../css/Admin/Tractors/tractor_show_style.css" scoped></style>