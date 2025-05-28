<template>
    <AdminLayout>
      <template #header>
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
          Detalles del Apero
        </h2>
      </template>
      <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-2xl font-bold text-green-800">{{ apero.name }}</h1>
          <div class="space-x-2">
            <Link 
              :href="route('aperos.index')" 
              class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition"
            >
              Volver a la Lista
            </Link>
            <Link 
              :href="route('aperos.edit', apero.id)" 
              class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700 transition"
            >
              Editar
            </Link>
            <Link 
              :href="route('aperos.attach-tractors', apero.id)" 
              class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
            >
              Gestionar Tractores
            </Link>
            <button 
              @click="confirmDelete" 
              class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition"
            >
              Eliminar
            </button>
          </div>
        </div>

        <!-- Grid layout para información e imagen -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
          <!-- Información del Apero -->
          <div class="lg:col-span-2">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
              <div class="px-4 py-5 sm:px-6 bg-green-50">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Información del Apero</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Detalles y especificaciones</p>
              </div>
              <div class="border-t border-gray-200">
                <dl>
                  <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Nombre</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ apero.name || 'Sin nombre' }}</dd>
                  </div>
                  <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Tipo</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ apero.type || 'Sin tipo' }}</dd>
                  </div>
                  <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Marca</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ apero.brand || 'Sin marca' }}</dd>
                  </div>
                  <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Modelo</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ apero.model || 'Sin modelo' }}</dd>
                  </div>
                  <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Año</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ apero.year || 'Sin año' }}</dd>
                  </div>
                  <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Estado</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                      <span 
                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                        :class="apero.is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                      >
                        {{ apero.is_available ? 'Disponible' : 'No disponible' }}
                      </span>
                    </dd>
                  </div>
                  <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Descripción</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ apero.description || 'Sin descripción' }}</dd>
                  </div>
                </dl>
              </div>
            </div>
          </div>

          <!-- Imagen y Propietario -->
          <div class="lg:col-span-1 space-y-6">
            <!-- Imagen del Apero -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
              <div class="px-4 py-5 sm:px-6 bg-green-50">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Imagen</h3>
              </div>
              <div class="p-6">
                <div class="w-full aspect-square rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
                  <img 
                    v-if="apero.image" 
                    :src="`/storage/${apero.image}`" 
                    :alt="apero.name"
                    class="w-full h-full object-cover"
                  />
                  <div v-else class="text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">Sin imagen</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Información del Propietario -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
              <div class="px-4 py-5 sm:px-6 bg-green-50">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Propietario</h3>
              </div>
              <div class="p-6">
                <div v-if="apero.user" class="flex items-center space-x-4">
                  <div class="flex-shrink-0">
                    <img 
                      v-if="apero.user.pfp" 
                      class="h-16 w-16 rounded-full object-cover" 
                      :src="`/storage/${apero.user.pfp}`" 
                      :alt="apero.user.username"
                    />
                    <div v-else class="h-16 w-16 rounded-full bg-gray-300 flex items-center justify-center">
                      <span class="text-lg font-medium text-gray-700">
                        {{ (apero.user.first_name?.[0] || '') + (apero.user.last_name?.[0] || '') }}
                      </span>
                    </div>
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="text-lg font-medium text-gray-900 truncate">
                      {{ apero.user.first_name }} {{ apero.user.last_name }}
                    </p>
                    <p class="text-sm text-gray-500 truncate">
                      @{{ apero.user.username }}
                    </p>
                    <p class="text-sm text-gray-500 truncate">
                      {{ apero.user.email }}
                    </p>
                    <div class="mt-2">
                      <Link 
                        :href="route('users.show', apero.user.id)" 
                        class="text-green-600 hover:text-green-900 text-sm font-medium"
                      >
                        Ver perfil completo →
                      </Link>
                    </div>
                  </div>
                </div>
                <div v-else class="text-center py-4">
                  <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                  </svg>
                  <p class="mt-2 text-sm text-gray-500">Sin propietario asignado</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Tractores Conectados -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg" v-if="apero.tractors && apero.tractors.length > 0">
          <div class="px-4 py-5 sm:px-6 bg-green-50">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Tractores Conectados</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Tractores a los que está conectado este apero</p>
          </div>
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-green-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                    Imagen
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                    Marca
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                    Modelo
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                    Matrícula
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                    Fecha de Conexión
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                    Estado
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                    Acciones
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="tractor in apero.tractors" :key="tractor.id" class="hover:bg-green-50 transition">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
                      <img 
                        v-if="tractor.image" 
                        :src="`/storage/${tractor.image}`" 
                        :alt="`${tractor.brand} ${tractor.model}`"
                        class="w-full h-full object-cover"
                      />
                      <svg v-else class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                      </svg>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ tractor.brand || 'Sin marca' }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ tractor.model || 'Sin modelo' }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ tractor.license_plate || 'Sin matrícula' }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ tractor.pivot?.attached_at ? formatDate(tractor.pivot.attached_at) : 'No registrada' }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <span 
                      class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                      :class="!tractor.pivot?.detached_at ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                    >
                      {{ !tractor.pivot?.detached_at ? 'Conectado' : 'Desconectado' }}
                    </span>
                    <div v-if="tractor.pivot?.detached_at" class="text-xs text-gray-500 mt-1">
                      {{ formatDate(tractor.pivot.detached_at) }}
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <Link 
                      :href="route('tractors.show', tractor.id)" 
                      class="text-green-600 hover:text-green-900"
                    >
                      Ver Tractor
                    </Link>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div v-else class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
          <div class="text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            <p class="mt-2 text-gray-500">No hay tractores conectados a este apero</p>
            <Link 
              :href="route('aperos.attach-tractors', apero.id)" 
              class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
            >
              Conectar Tractores
            </Link>
          </div>
        </div>
      </div>
    </AdminLayout>
  </template>
  
  <script setup lang="ts">
  import { Link, router } from '@inertiajs/vue3';
  import AdminLayout from '@/Layouts/AdminLayout.vue';
  
  const props = defineProps({
    apero: Object
  });
  
  const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    });
  };
  
  const confirmDelete = () => {
    if (confirm('¿Estás seguro de que quieres eliminar este apero?')) {
      router.delete(route('aperos.destroy', props.apero.id));
    }
  };
  </script>
  
 <style src="../../../../css/Admin/Aperos/aperos_show_style.css" scoped></style>