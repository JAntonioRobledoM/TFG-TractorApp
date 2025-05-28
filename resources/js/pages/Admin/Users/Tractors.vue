<template>
    <AdminLayout>
      <template #header>
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
          Tractores del Usuario
        </h2>
      </template>
      <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
          <div>
            <h1 class="text-2xl font-bold text-green-800 flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-yellow-600" viewBox="0 0 24 24" stroke="currentColor" fill="none">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
              </svg>
              Tractores de {{ user.first_name }} {{ user.last_name }}
            </h1>
            <p class="text-sm text-gray-600 mt-1">@{{ user.username }} • {{ tractors.length }} tractores</p>
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
            <Link 
              :href="route('users.assign-tractors', user.id)" 
              class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
            >
              Gestionar Tractores
            </Link>
          </div>
        </div>

        <!-- Información del usuario -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
          <div class="px-4 py-5 sm:px-6 bg-green-50">
            <div class="flex items-center space-x-4">
              <div class="flex-shrink-0">
                <img 
                  v-if="user.pfp" 
                  class="h-16 w-16 rounded-full object-cover border-2 border-green-200" 
                  :src="`/storage/${user.pfp}`" 
                  :alt="user.username"
                />
                <div v-else class="h-16 w-16 rounded-full bg-green-100 flex items-center justify-center border-2 border-green-200">
                  <span class="text-lg font-medium text-green-700">
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
  
        <div v-if="tractors.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div 
            v-for="tractor in tractors" 
            :key="tractor.id" 
            class="bg-white shadow-lg overflow-hidden rounded-lg border-t-4 border-yellow-600 hover:shadow-xl transition-shadow"
          >
            <!-- Header del tractor -->
            <div class="px-4 py-5 sm:px-6 bg-yellow-50">
              <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
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
                </div>
                <div class="flex-1 min-w-0">
                  <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ tractor.brand }} {{ tractor.model }}
                  </h3>
                  <div class="mt-1 flex items-center space-x-2 text-sm text-gray-500">
                    <span v-if="tractor.year">{{ tractor.year }}</span>
                    <span v-if="tractor.horsepower">{{ tractor.horsepower }} CV</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Información del tractor -->
            <div class="border-t border-yellow-200">
              <dl class="divide-y divide-gray-200">
                <!-- Matrícula -->
                <div v-if="tractor.license_plate" class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500">Matrícula</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <span class="font-mono bg-gray-100 px-2 py-1 rounded text-xs">{{ tractor.license_plate }}</span>
                  </dd>
                </div>

                <!-- Color -->
                <div v-if="tractor.color" class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500">Color</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <div class="flex items-center space-x-2">
                      <span>{{ tractor.color }}</span>
                      <div 
                        class="w-4 h-4 rounded-full border border-gray-300"
                        :style="{ backgroundColor: getColorValue(tractor.color) }"
                      ></div>
                    </div>
                  </dd>
                </div>

                <!-- Horas de trabajo -->
                <div v-if="tractor.working_hours" class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500">Horas</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ formatHours(tractor.working_hours) }}
                  </dd>
                </div>

                <!-- Estado -->
                <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500">Estado</dt>
                  <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">
                    <span 
                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                      :class="tractor.is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                    >
                      {{ tractor.is_available ? 'Disponible' : 'No disponible' }}
                    </span>
                  </dd>
                </div>

                <!-- Descripción -->
                <div v-if="tractor.description" class="px-4 py-3 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500 mb-2">Descripción</dt>
                  <dd class="text-sm text-gray-900">
                    {{ tractor.description.length > 100 ? tractor.description.substring(0, 100) + '...' : tractor.description }}
                  </dd>
                </div>
              </dl>
            </div>

            <!-- Estadísticas rápidas -->
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
              <div class="grid grid-cols-2 gap-4 text-center">
                <div>
                  <div class="text-lg font-semibold text-gray-900">{{ tractor.aperos?.length || 0 }}</div>
                  <div class="text-xs text-gray-500">Aperos</div>
                </div>
                <div>
                  <div class="text-lg font-semibold text-gray-900">{{ tractor.listings?.length || 0 }}</div>
                  <div class="text-xs text-gray-500">Anuncios</div>
                </div>
              </div>
            </div>

            <!-- Acciones -->
            <div class="px-4 py-3 bg-yellow-50 flex justify-between items-center">
              <Link 
                :href="route('tractors.show', tractor.id)" 
                class="text-yellow-600 hover:text-yellow-900 inline-flex items-center font-medium text-sm"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                  <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                </svg>
                Ver detalles
              </Link>
              <div class="flex space-x-2">
                <Link 
                  :href="route('tractors.edit', tractor.id)" 
                  class="text-blue-600 hover:text-blue-900 text-sm"
                >
                  Editar
                </Link>
                <button 
                  @click="removeTractor(tractor.id)" 
                  class="text-red-600 hover:text-red-900 text-sm"
                >
                  Quitar
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Estado vacío -->
        <div v-else class="bg-white shadow-lg overflow-hidden rounded-lg border-t-4 border-yellow-600">
          <div class="px-6 py-12 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No hay tractores asignados</h3>
            <p class="text-gray-500 mb-6">Este usuario aún no tiene tractores asociados a su cuenta.</p>
            <Link 
              :href="route('users.assign-tractors', user.id)" 
              class="bg-yellow-600 text-white px-6 py-3 rounded-lg hover:bg-yellow-700 transition inline-flex items-center"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
              </svg>
              Asignar Tractores
            </Link>
          </div>
        </div>

        <!-- Resumen estadístico -->
        <div v-if="tractors.length" class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
          <div class="px-4 py-5 sm:px-6 bg-gray-50">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Resumen</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Estadísticas de los tractores de este usuario</p>
          </div>
          <div class="border-t border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-4 divide-y md:divide-y-0 md:divide-x divide-gray-200">
              <div class="px-6 py-4 text-center">
                <div class="text-2xl font-bold text-green-600">{{ tractors.length }}</div>
                <div class="text-sm text-gray-500">Total Tractores</div>
              </div>
              <div class="px-6 py-4 text-center">
                <div class="text-2xl font-bold text-green-600">{{ availableTractors }}</div>
                <div class="text-sm text-gray-500">Disponibles</div>
              </div>
              <div class="px-6 py-4 text-center">
                <div class="text-2xl font-bold text-yellow-600">{{ totalAperos }}</div>
                <div class="text-sm text-gray-500">Aperos Totales</div>
              </div>
              <div class="px-6 py-4 text-center">
                <div class="text-2xl font-bold text-blue-600">{{ totalListings }}</div>
                <div class="text-sm text-gray-500">Anuncios Activos</div>
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
import { computed } from 'vue';

const props = defineProps({
  user: Object,
  tractors: Array
});

const availableTractors = computed(() => {
  return props.tractors.filter(tractor => tractor.is_available).length;
});

const totalAperos = computed(() => {
  return props.tractors.reduce((total, tractor) => total + (tractor.aperos?.length || 0), 0);
});

const totalListings = computed(() => {
  return props.tractors.reduce((total, tractor) => total + (tractor.listings?.filter(listing => listing.is_active).length || 0), 0);
});

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

const removeTractor = (tractorId) => {
  if (confirm('¿Estás seguro de que quieres quitar este tractor del usuario? El tractor no se eliminará, solo se desasociará.')) {
    router.delete(route('users.remove-tractor', [props.user.id, tractorId]));
  }
};
</script>

<style src="../../../../css/Admin/Users/user_tractors_style.css" scoped></style>