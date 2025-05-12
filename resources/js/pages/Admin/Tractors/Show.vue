<template>
  <AdminLayout>
    <template #header>
      <h2 class="font-semibold text-2xl text-white leading-tight">
        Detalles del Tractor
      </h2>
    </template>
    <div class="container mx-auto p-6">
      <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
          <div>
            <h2 class="text-lg leading-6 font-medium text-gray-900">
              {{ tractor.brand ? tractor.brand + ' ' + (tractor.model || '') : 'Tractor' }} 
              <span v-if="tractor.license_plate" class="text-sm text-green-600 ml-2">({{ tractor.license_plate }})</span>
            </h2>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
              Información detallada del tractor
            </p>
          </div>
          <div class="flex space-x-2">
            <Link 
              :href="route('tractors.attach-aperos', tractor.id)" 
              class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition flex items-center"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zm7-10a1 1 0 01.707.293l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L13.586 9H10a1 1 0 110-2h3.586l-2.293-2.293A1 1 0 0112 2z" clip-rule="evenodd" />
              </svg>
              Gestionar Aperos
            </Link>
            <Link 
              :href="route('tractors.edit', tractor.id)" 
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
              <dt class="text-sm font-medium text-gray-500">Matrícula</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                {{ tractor.license_plate || 'No especificada' }}
              </dd>
            </div>

            <!-- Información técnica -->
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Color</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                {{ tractor.color || 'No especificado' }}
              </dd>
            </div>
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Potencia</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                {{ tractor.horsepower ? `${tractor.horsepower} CV` : 'No especificada' }}
              </dd>
            </div>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Horas de trabajo</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                {{ tractor.working_hours ? `${tractor.working_hours} h` : 'No especificadas' }}
              </dd>
            </div>
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
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
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Descripción</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                {{ tractor.description || 'Sin descripción' }}
              </dd>
            </div>
          </dl>
        </div>
      </div>

      <!-- Aperos asociados -->
      <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
          <h3 class="text-lg leading-6 font-medium text-gray-900">
            Aperos Asociados
          </h3>
          <Link 
            :href="route('tractors.attach-aperos', tractor.id)" 
            class="text-blue-600 hover:text-blue-800"
          >
            Gestionar aperos
          </Link>
        </div>
        <div v-if="tractor.aperos && tractor.aperos.length" class="border-t border-gray-200">
          <div v-for="apero in tractor.aperos" :key="apero.id" class="px-4 py-4 sm:px-6 border-b border-gray-200 last:border-b-0">
            <div class="flex justify-between items-center">
              <div>
                <p class="text-sm font-medium text-gray-900">
                  {{ apero.name || 'Apero #' + apero.id }}
                </p>
                <p class="text-sm text-gray-500">
                  {{ apero.type ? 'Tipo: ' + apero.type : (apero.brand ? 'Marca: ' + apero.brand : '') }}
                </p>
              </div>
              <div class="flex space-x-2">
                <span v-if="apero.pivot && apero.pivot.attached_at" class="text-xs text-gray-500">
                  Acoplado: {{ formatDate(apero.pivot.attached_at) }}
                </span>
                <Link 
                  :href="route('aperos.show', apero.id)" 
                  class="text-blue-600 hover:text-blue-800"
                >
                  Ver detalles
                </Link>
              </div>
            </div>
          </div>
        </div>
        <div v-else class="px-4 py-4 sm:px-6 text-sm text-gray-500">
          No hay aperos asociados a este tractor
        </div>
      </div>

      <!-- Usuarios asociados -->
      <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
          <h3 class="text-lg leading-6 font-medium text-gray-900">
            Propietarios
          </h3>
          <Link 
            :href="route('tractors.users', tractor.id)" 
            class="text-green-600 hover:text-green-800"
          >
            Ver todos los propietarios
          </Link>
        </div>
        <div v-if="tractor.owners && tractor.owners.length" class="border-t border-gray-200">
          <div v-for="user in tractor.owners.slice(0, 3)" :key="user.id" class="px-4 py-4 sm:px-6 border-b border-gray-200 last:border-b-0">
            <div class="flex justify-between items-center">
              <div>
                <p class="text-sm font-medium text-gray-900">
                  {{ user.first_name }} {{ user.last_name }}
                </p>
                <p class="text-sm text-gray-500">
                  {{ user.email || user.username }}
                </p>
              </div>
              <Link 
                :href="route('users.show', user.id)" 
                class="text-green-600 hover:text-green-800"
              >
                Ver perfil
              </Link>
            </div>
          </div>
        </div>
        <div v-else class="px-4 py-4 sm:px-6 text-sm text-gray-500">
          No hay propietarios asociados a este tractor
        </div>
      </div>

      <!-- Anuncios relacionados -->
      <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
          <h3 class="text-lg leading-6 font-medium text-gray-900">
            Anuncios Relacionados
          </h3>
          <Link 
            :href="route('tractors.listings', tractor.id)" 
            class="text-green-600 hover:text-green-800"
          >
            Ver todos los anuncios
          </Link>
        </div>
        <div v-if="tractor.listings && tractor.listings.length" class="border-t border-gray-200">
          <div v-for="listing in tractor.listings.slice(0, 3)" :key="listing.id" class="px-4 py-4 sm:px-6 border-b border-gray-200 last:border-b-0">
            <div class="flex justify-between items-center">
              <div>
                <p class="text-sm font-medium text-gray-900 flex items-center">
                  <span 
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mr-2"
                    :class="listing.type === 'sale' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800'"
                  >
                    {{ listing.type === 'sale' ? 'Venta' : 'Alquiler' }}
                  </span>
                  <span 
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                    :class="listing.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                  >
                    {{ listing.is_active ? 'Activo' : 'Inactivo' }}
                  </span>
                </p>
                <p class="text-sm text-gray-500 mt-1">
                  Precio: {{ formatCurrency(listing.price) }}
                </p>
                <p v-if="listing.type === 'rental' && listing.start_date && listing.end_date" class="text-xs text-gray-500">
                  Periodo: {{ formatDate(listing.start_date) }} - {{ formatDate(listing.end_date) }}
                </p>
              </div>
              <Link 
                :href="route('listings.show', listing.id)" 
                class="text-green-600 hover:text-green-800"
              >
                Ver detalles
              </Link>
            </div>
          </div>
        </div>
        <div v-else class="px-4 py-4 sm:px-6 text-sm text-gray-500">
          No hay anuncios relacionados con este tractor
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
  return new Intl.DateTimeFormat('es-ES', { dateStyle: 'medium' }).format(date);
};
</script>