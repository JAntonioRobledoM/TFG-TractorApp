<template>
    <AdminLayout>
      <template #header>
          <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Aperos
          </h2>
        </template>
      <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-2xl font-bold text-green-800">Aperos</h1>
          <Link 
            :href="route('aperos.create')" 
            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition"
          >
            Añadir Nuevo Apero
          </Link>
        </div>
  
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-green-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                    Nombre
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                    Tipo
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                    Marca
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                    Modelo
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                    Año
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
                <tr v-for="apero in aperos" :key="apero.id" class="hover:bg-green-50 transition">
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ apero.name || 'Sin nombre' }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ apero.type || 'Sin tipo' }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ apero.brand || 'Sin marca' }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ apero.model || 'Sin modelo' }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ apero.year || 'Sin año' }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <span 
                      class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                      :class="apero.is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                    >
                      {{ apero.is_available ? 'Disponible' : 'No disponible' }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                    <Link 
                      :href="route('aperos.show', apero.id)" 
                      class="text-green-600 hover:text-green-900"
                    >
                      Ver
                    </Link>
                    <Link 
                      :href="route('aperos.edit', apero.id)" 
                      class="text-yellow-600 hover:text-yellow-900"
                    >
                      Editar
                    </Link>
                    <Link 
                      :href="route('aperos.attach-tractors', apero.id)" 
                      class="text-blue-600 hover:text-blue-900"
                    >
                      Tractores
                    </Link>
                    <button 
                      @click="confirmDelete(apero.id)" 
                      class="text-red-600 hover:text-red-900"
                    >
                      Eliminar
                    </button>
                  </td>
                </tr>
                <tr v-if="aperos.length === 0">
                  <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                    No hay aperos registrados
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
  
  const props = defineProps({
    aperos: Array
  });
  
  const confirmDelete = (aperoId) => {
    if (confirm('¿Estás seguro de que quieres eliminar este apero?')) {
      router.delete(route('aperos.destroy', aperoId));
    }
  };
  </script>
  
  <style scoped>
  .overflow-x-auto {
    overflow-x: auto;
  }
  </style>