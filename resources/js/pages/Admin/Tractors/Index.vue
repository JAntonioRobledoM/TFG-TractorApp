<template>
  <AdminLayout>
    <template #header>
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
          Tractores
        </h2>
      </template>
    <div class="container mx-auto p-6">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-green-800">Tractores</h1>
        <Link 
          :href="route('tractors.create')" 
          class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition"
        >
          Añadir Nuevo Tractor
        </Link>
      </div>

      <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-green-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                  Imagen
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                  Marca/Modelo
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                  Año
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                  Matrícula
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                  Potencia
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                  Horas
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                  Propietarios
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
              <tr v-for="tractor in tractors" :key="tractor.id" class="hover:bg-green-50 transition">
                <!-- Imagen -->
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
                    <img 
                      v-if="tractor.image" 
                      :src="`/storage/${tractor.image}`" 
                      :alt="`${tractor.brand} ${tractor.model}`"
                      class="w-full h-full object-cover"
                    />
                    <svg v-else class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                  </div>
                </td>
                
                <!-- Marca/Modelo -->
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  <div>
                    <div class="font-medium text-gray-900">
                      {{ tractor.brand || 'Sin marca' }}
                    </div>
                    <div class="text-sm text-gray-500">
                      {{ tractor.model || 'Sin modelo' }}
                    </div>
                    <div v-if="tractor.color" class="text-xs text-gray-400">
                      {{ tractor.color }}
                    </div>
                  </div>
                </td>
                
                <!-- Año -->
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ tractor.year || 'Sin año' }}
                </td>
                
                <!-- Matrícula -->
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  <span v-if="tractor.license_plate" class="font-mono bg-gray-100 px-2 py-1 rounded text-xs">
                    {{ tractor.license_plate }}
                  </span>
                  <span v-else class="text-gray-400 italic">Sin matrícula</span>
                </td>
                
                <!-- Potencia -->
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  <span v-if="tractor.horsepower" class="font-medium">
                    {{ tractor.horsepower }} CV
                  </span>
                  <span v-else class="text-gray-400">Sin datos</span>
                </td>
                
                <!-- Horas de trabajo -->
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  <span v-if="tractor.working_hours" class="text-sm">
                    {{ formatHours(tractor.working_hours) }}
                  </span>
                  <span v-else class="text-gray-400">Sin datos</span>
                </td>
                
                <!-- Propietarios -->
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  <div v-if="tractor.owners && tractor.owners.length > 0" class="space-y-1">
                    <div v-for="owner in tractor.owners.slice(0, 2)" :key="owner.id" class="flex items-center space-x-2">
                      <div class="flex-shrink-0 h-6 w-6">
                        <img 
                          v-if="owner.pfp" 
                          class="h-6 w-6 rounded-full object-cover" 
                          :src="`/storage/${owner.pfp}`" 
                          :alt="owner.username"
                        />
                        <div v-else class="h-6 w-6 rounded-full bg-gray-300 flex items-center justify-center">
                          <span class="text-xs font-medium text-gray-700">
                            {{ (owner.first_name?.[0] || '') + (owner.last_name?.[0] || '') }}
                          </span>
                        </div>
                      </div>
                      <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-900 truncate">
                          {{ owner.first_name }} {{ owner.last_name }}
                        </p>
                      </div>
                    </div>
                    <div v-if="tractor.owners.length > 2" class="text-xs text-gray-500">
                      +{{ tractor.owners.length - 2 }} más
                    </div>
                  </div>
                  <span v-else class="text-gray-400 italic text-xs">Sin propietarios</span>
                </td>
                
                <!-- Estado -->
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  <span 
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                    :class="tractor.is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                  >
                    {{ tractor.is_available ? 'Disponible' : 'No disponible' }}
                  </span>
                </td>
                
                <!-- Acciones -->
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <div class="flex flex-col space-y-1">
                    <div class="flex space-x-2">
                      <Link 
                        :href="route('tractors.show', tractor.id)" 
                        class="text-green-600 hover:text-green-900 text-xs"
                      >
                        Ver
                      </Link>
                      <Link 
                        :href="route('tractors.edit', tractor.id)" 
                        class="text-yellow-600 hover:text-yellow-900 text-xs"
                      >
                        Editar
                      </Link>
                      <Link 
                        :href="route('tractors.attach-aperos', tractor.id)" 
                        class="text-blue-600 hover:text-blue-900 text-xs"
                      >
                        Aperos
                      </Link>
                    </div>
                    <div>
                      <button 
                        @click="confirmDelete(tractor.id)" 
                        class="text-red-600 hover:text-red-900 text-xs"
                      >
                        Eliminar
                      </button>
                    </div>
                  </div>
                </td>
              </tr>
              <tr v-if="tractors.length === 0">
                <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                  No hay tractores registrados
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
  tractors: Array
});

const formatHours = (hours) => {
  if (hours >= 1000) {
    return `${(hours / 1000).toFixed(1)}k h`;
  }
  return `${hours} h`;
};

const confirmDelete = (tractorId) => {
  if (confirm('¿Estás seguro de que quieres eliminar este tractor?')) {
    router.delete(route('tractors.destroy', tractorId));
  }
};
</script>

<style src="../../../../css/Admin/Tractors/tractor_index_style.css" scoped></style>