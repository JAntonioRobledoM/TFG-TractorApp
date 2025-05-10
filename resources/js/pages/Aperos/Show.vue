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
  
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
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
                    Fecha de Desconexión
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                    Acciones
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="tractor in apero.tractors" :key="tractor.id" class="hover:bg-green-50 transition">
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
                    {{ tractor.pivot?.detached_at ? formatDate(tractor.pivot.detached_at) : 'Actualmente conectado' }}
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
          <p class="text-gray-500">No hay tractores conectados a este apero</p>
          <Link 
            :href="route('aperos.attach-tractors', apero.id)" 
            class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
          >
            Conectar Tractores
          </Link>
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
      year: 'numeric'
    });
  };
  
  const confirmDelete = () => {
    if (confirm('¿Estás seguro de que quieres eliminar este apero?')) {
      router.delete(route('aperos.destroy', props.apero.id));
    }
  };
  </script>
  
  <style scoped>
  .overflow-x-auto {
    overflow-x: auto;
  }
  </style>