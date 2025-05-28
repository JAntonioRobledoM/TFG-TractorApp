<template>
    <AdminLayout>
      <template #header>
          <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Usuarios
          </h2>
        </template>
      <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-2xl font-bold text-green-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-green-600" viewBox="0 0 20 20" fill="currentColor">
              <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
            </svg>
            Usuarios
          </h1>
          <Link 
            :href="route('users.create')" 
            class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition duration-200 flex items-center shadow-md"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
              <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
            </svg>
            Crear Nuevo Usuario
          </Link>
        </div>
  
        <div class="bg-white shadow-lg overflow-hidden rounded-lg border-t-4 border-green-600">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-green-200">
              <thead class="bg-green-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                    Avatar
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                    Usuario
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                    Información Personal
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                    Email
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                    Rol
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                    Tractores
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                    Acciones
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-green-100">
                <tr v-for="user in users" :key="user.id" class="hover:bg-green-50 transition duration-200">
                  <!-- Avatar -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex-shrink-0 h-12 w-12">
                      <img 
                        v-if="user.pfp" 
                        class="h-12 w-12 rounded-full object-cover border-2 border-green-200" 
                        :src="`/storage/${user.pfp}`" 
                        :alt="user.username"
                      />
                      <div v-else class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center border-2 border-green-200">
                        <span class="text-sm font-medium text-green-700">
                          {{ (user.first_name?.[0] || '') + (user.last_name?.[0] || '') }}
                        </span>
                      </div>
                    </div>
                  </td>

                  <!-- Usuario -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">@{{ user.username }}</div>
                    <div class="text-sm text-gray-500">ID: {{ user.id }}</div>
                  </td>

                  <!-- Información Personal -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">
                      {{ user.first_name }} {{ user.last_name }}
                    </div>
                    <div class="text-sm text-gray-500 space-y-1">
                      <div v-if="user.dni" class="flex items-center">
                        <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 114 0v2m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c0 1.5 1.5 3 4 3s4-1.5 4-3"></path>
                        </svg>
                        {{ user.dni }}
                      </div>
                      <div v-if="user.tlf" class="flex items-center">
                        <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        {{ formatPhoneNumber(user.tlf) }}
                      </div>
                    </div>
                  </td>

                  <!-- Email -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ user.email }}</div>
                  </td>

                  <!-- Rol -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span 
                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                      :class="user.is_admin ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'"
                    >
                      <svg 
                        class="w-3 h-3 mr-1" 
                        fill="none" 
                        stroke="currentColor" 
                        viewBox="0 0 24 24"
                      >
                        <path 
                          v-if="user.is_admin"
                          stroke-linecap="round" 
                          stroke-linejoin="round" 
                          stroke-width="2" 
                          d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                        ></path>
                        <path 
                          v-else
                          stroke-linecap="round" 
                          stroke-linejoin="round" 
                          stroke-width="2" 
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                        ></path>
                      </svg>
                      {{ user.is_admin ? 'Administrador' : 'Usuario' }}
                    </span>
                  </td>

                  <!-- Tractores -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div v-if="user.tractors && user.tractors.length > 0" class="flex items-center space-x-2">
                      <div class="flex -space-x-1 overflow-hidden">
                        <div 
                          v-for="(tractor, index) in user.tractors.slice(0, 3)" 
                          :key="tractor.id"
                          class="inline-block h-6 w-6 rounded-full ring-2 ring-white bg-gray-100 flex items-center justify-center"
                          :title="`${tractor.brand} ${tractor.model}`"
                        >
                          <img 
                            v-if="tractor.image" 
                            :src="`/storage/${tractor.image}`" 
                            :alt="`${tractor.brand} ${tractor.model}`"
                            class="h-6 w-6 rounded-full object-cover"
                          />
                          <svg v-else class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                          </svg>
                        </div>
                      </div>
                      <span class="text-xs text-gray-500">
                        {{ user.tractors.length }} tractor{{ user.tractors.length !== 1 ? 'es' : '' }}
                      </span>
                      <div v-if="user.tractors.length > 3" class="text-xs text-gray-400">
                        +{{ user.tractors.length - 3 }}
                      </div>
                    </div>
                    <span v-else class="text-xs text-gray-400 italic">Sin tractores</span>
                  </td>

                  <!-- Acciones -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex flex-col space-y-1">
                      <div class="flex space-x-2">
                        <Link 
                          :href="route('users.show', user.id)" 
                          class="text-green-600 hover:text-green-900 font-medium inline-flex items-center text-xs"
                        >
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                          </svg>
                          Ver
                        </Link>
                        <Link 
                          :href="route('users.edit', user.id)" 
                          class="text-yellow-600 hover:text-yellow-900 font-medium inline-flex items-center text-xs"
                        >
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                          </svg>
                          Editar
                        </Link>
                        <Link 
                          :href="route('users.assign-tractors', user.id)" 
                          class="text-blue-600 hover:text-blue-900 font-medium inline-flex items-center text-xs"
                        >
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                          </svg>
                          Tractores
                        </Link>
                      </div>
                      <div>
                        <button 
                          @click="confirmDelete(user.id)" 
                          class="text-red-600 hover:text-red-900 font-medium inline-flex items-center text-xs"
                        >
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                          </svg>
                          Eliminar
                        </button>
                      </div>
                    </div>
                  </td>
                </tr>
                <tr v-if="users.length === 0">
                  <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <p class="mt-2">No hay usuarios registrados</p>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </AdminLayout>
  </template>
  
  <script setup lang="ts">
  import { Link, router } from '@inertiajs/vue3';
  import AdminLayout from '@/Layouts/AdminLayout.vue';
  
  defineProps({
    users: Array
  });
  
  const formatPhoneNumber = (phone) => {
    if (!phone) return '';
    const phoneStr = phone.toString();
    // Formato español: +34 XXX XXX XXX
    if (phoneStr.length === 9) {
      return `${phoneStr.slice(0, 3)} ${phoneStr.slice(3, 6)} ${phoneStr.slice(6)}`;
    }
    return phoneStr;
  };
  
  const confirmDelete = (userId) => {
    if (confirm('¿Estás seguro de que quieres eliminar este usuario? Esta acción no se puede deshacer.')) {
      router.delete(route('users.destroy', userId));
    }
  };
  </script>

  <style scoped>
  /* Add subtle field texture to the table background */
  .bg-white {
      background-image: 
          radial-gradient(circle at 10px 10px, rgba(34, 197, 94, 0.03) 2px, transparent 2px);
      background-size: 20px 20px;
  }

  /* Enhanced shadow for table */
  .shadow-lg {
      box-shadow: 0 10px 15px -3px rgba(34, 197, 94, 0.1), 0 4px 6px -2px rgba(34, 197, 94, 0.05);
  }

  /* Add pattern to the table header */
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

  .overflow-x-auto {
    overflow-x: auto;
  }
  </style>