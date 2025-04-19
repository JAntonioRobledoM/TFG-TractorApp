<template>
    <AdminLayout>
      <div class="container mx-auto p-6">
        <div class="bg-white shadow-lg overflow-hidden rounded-lg border-t-4 border-green-600">
          <div class="px-4 py-5 sm:px-6 flex justify-between items-center bg-green-50">
            <div>
              <h2 class="text-lg leading-6 font-medium text-green-800">
                Detalles del Usuario
              </h2>
              <p class="mt-1 max-w-2xl text-sm text-green-600">
                Información personal y de contacto
              </p>
            </div>
            <div class="flex space-x-2">
              <Link 
                :href="route('users.edit', user.id)" 
                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-200 flex items-center"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
                Editar
              </Link>
              <button 
                @click="confirmDelete" 
                class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-200 flex items-center"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                Eliminar
              </button>
            </div>
          </div>
          <div class="border-t border-green-200">
            <dl>
              <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Nombre de Usuario</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                  {{ user.username }}
                </dd>
              </div>
              <div class="bg-green-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Nombre Completo</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                  {{ user.first_name }} {{ user.last_name }}
                </dd>
              </div>
              <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Correo Electrónico</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                  {{ user.email || 'No proporcionado' }}
                </dd>
              </div>
              <div class="bg-green-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">DNI</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                  {{ user.dni || 'No proporcionado' }}
                </dd>
              </div>
              <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                  {{ user.tlf || 'No proporcionado' }}
                </dd>
              </div>
              <div class="bg-green-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Rol de Administrador</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                  <span :class="user.is_admin ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'" 
                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                    {{ user.is_admin ? 'Sí' : 'No' }}
                  </span>
                </dd>
              </div>
            </dl>
          </div>
        </div>
  
        <!-- Tractors Section -->
        <div class="mt-8 bg-white shadow-lg overflow-hidden rounded-lg border-t-4 border-yellow-600">
          <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <h3 class="text-lg leading-6 font-medium text-green-800 flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-yellow-600" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19 8h-2c0-.6-.4-1-1-1h-2c-.6 0-1 .4-1 1h-2c-1.7 0-3 1.3-3 3v3c0 1.7 1.3 3 3 3h8c1.7 0 3-1.3 3-3v-3c0-1.7-1.3-3-3-3zm-2 9c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3zM5 11c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"/>
              </svg>
              Tractores
            </h3>
            <Link 
              :href="route('users.tractors', user.id)" 
              class="text-green-600 hover:text-green-800 hover:bg-green-50 px-3 py-2 rounded-lg transition duration-200"
            >
              Ver todos los tractores
            </Link>
          </div>
          <div v-if="user.tractors && user.tractors.length" class="border-t border-green-200">
            <div v-for="tractor in user.tractors.slice(0, 3)" :key="tractor.id" class="px-4 py-4 sm:px-6 border-b border-green-100 last:border-b-0 hover:bg-green-50 transition duration-200">
              <div class="flex justify-between items-center">
                <div>
                  <p class="text-sm font-medium text-gray-900">
                    {{ tractor.model || 'Tractor #' + tractor.id }}
                  </p>
                  <p class="text-sm text-gray-500">
                    Año: {{ tractor.year || 'No especificado' }}
                  </p>
                </div>
                <Link 
                  :href="route('tractors.show', tractor.id)" 
                  class="text-green-600 hover:text-green-800 inline-flex items-center"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                  </svg>
                  Ver detalles
                </Link>
              </div>
            </div>
          </div>
          <div v-else class="px-4 py-4 sm:px-6 text-sm text-gray-500">
            No hay tractores asociados
          </div>
        </div>
  
        <!-- Listings Section -->
        <div class="mt-8 bg-white shadow-lg overflow-hidden rounded-lg border-t-4 border-green-600">
          <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <h3 class="text-lg leading-6 font-medium text-green-800 flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" />
                <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z" />
              </svg>
              Anuncios
            </h3>
            <Link 
              :href="route('users.listings', user.id)" 
              class="text-green-600 hover:text-green-800 hover:bg-green-50 px-3 py-2 rounded-lg transition duration-200"
            >
              Ver todos los anuncios
            </Link>
          </div>
          <div v-if="user.listings && user.listings.length" class="border-t border-green-200">
            <div v-for="listing in user.listings.slice(0, 3)" :key="listing.id" class="px-4 py-4 sm:px-6 border-b border-green-100 last:border-b-0 hover:bg-green-50 transition duration-200">
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
                  class="text-green-600 hover:text-green-800 inline-flex items-center"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                  </svg>
                  Ver detalles
                </Link>
              </div>
            </div>
          </div>
          <div v-else class="px-4 py-4 sm:px-6 text-sm text-gray-500">
            No hay anuncios
          </div>
        </div>
      </div>
    </AdminLayout>
  </template>
  
  <script setup lang="ts">
  import { Link, router } from '@inertiajs/vue3';
  import AdminLayout from '@/Layouts/AdminLayout.vue';
  
  const props = defineProps({
    user: Object
  });
  
  const confirmDelete = () => {
    if (confirm('¿Estás seguro de que quieres eliminar este usuario?')) {
      router.delete(route('users.destroy', props.user.id));
    }
  };
  
  const formatCurrency = (value) => {
    if (!value) return '---';
    return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(value);
  };
  </script>

  <style scoped>
  /* Add subtle field texture to the detail sections */
  .bg-white {
      background-image: 
          radial-gradient(circle at 10px 10px, rgba(34, 197, 94, 0.03) 2px, transparent 2px);
      background-size: 20px 20px;
  }

  /* Add pattern to the alternating rows */
  .bg-green-50 {
      background-image: 
          linear-gradient(to bottom, rgba(240, 253, 244, 0.9), rgba(240, 253, 244, 0.9)),
          repeating-linear-gradient(
              45deg,
              rgba(34, 197, 94, 0.05) 0px,
              rgba(34, 197, 94, 0.05) 10px,
              transparent 10px,
              transparent 20px
          );
  }

  /* Enhanced shadow for cards */
  .shadow-lg {
      box-shadow: 0 10px 15px -3px rgba(34, 197, 94, 0.1), 0 4px 6px -2px rgba(34, 197, 94, 0.05);
  }
  </style>