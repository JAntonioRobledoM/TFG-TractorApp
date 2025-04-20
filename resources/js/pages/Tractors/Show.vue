<template>
    <AdminLayout>
      <div class="container mx-auto p-6">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
          <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <div>
              <h2 class="text-lg leading-6 font-medium text-gray-900">
                Detalles del Tractor
              </h2>
              <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Información detallada del tractor
              </p>
            </div>
            <div class="flex space-x-2">
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
              </button></div>
        </div>
        <div class="border-t border-gray-200">
          <dl>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Modelo</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                {{ tractor.model || 'No especificado' }}
              </dd>
            </div>
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Año</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                {{ tractor.year || 'No especificado' }}
              </dd>
            </div>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Descripción</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                {{ tractor.description || 'Sin descripción' }}
              </dd>
            </div>
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Campo Adicional</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                {{ tractor.field_2 || 'No especificado' }}
              </dd>
            </div>
          </dl>
        </div>
      </div>

      <!-- Usuarios asociados -->
      <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
          <h3 class="text-lg leading-6 font-medium text-gray-900">
            Usuarios Asociados
          </h3>
          <Link 
            :href="route('tractors.users', tractor.id)" 
            class="text-green-600 hover:text-green-800"
          >
            Ver todos los usuarios
          </Link>
        </div>
        <div v-if="tractor.users && tractor.users.length" class="border-t border-gray-200">
          <div v-for="user in tractor.users.slice(0, 3)" :key="user.id" class="px-4 py-4 sm:px-6 border-b border-gray-200 last:border-b-0">
            <div class="flex justify-between items-center">
              <div>
                <p class="text-sm font-medium text-gray-900">
                  {{ user.first_name }} {{ user.last_name }}
                </p>
                <p class="text-sm text-gray-500">
                  {{ user.email }}
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
          No hay usuarios asociados a este tractor
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
                <p class="text-sm font-medium text-gray-900">
                  {{ listing.type === 'sale' ? 'Venta' : 'Alquiler' }}
                </p>
                <p class="text-sm text-gray-500">
                  Precio: {{ formatCurrency(listing.price) }}
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
</script>