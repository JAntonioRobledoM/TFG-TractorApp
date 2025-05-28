<template>
  <AdminLayout>
    <template #header>
      <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
        Asignar Tractores
      </h2>
    </template>
    <div class="container mx-auto p-6">
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-2xl font-bold text-green-800">
            Tractores para: {{ user.first_name }} {{ user.last_name }}
          </h1>
          <p class="text-sm text-gray-600 mt-1">Gestiona la asignación de tractores a este usuario</p>
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

      <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <!-- Tractores asignados -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
          <div class="px-4 py-5 sm:px-6 bg-green-50">
            <div class="flex justify-between items-center">
              <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">Tractores Asignados</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Tractores que posee este usuario</p>
              </div>
              <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                {{ user.tractors?.length || 0 }} tractores
              </span>
            </div>
          </div>
          <div class="max-h-96 overflow-y-auto">
            <div v-if="user.tractors && user.tractors.length" class="divide-y divide-gray-200">
              <div 
                v-for="tractor in user.tractors" 
                :key="tractor.id" 
                class="px-4 py-4 hover:bg-green-50 transition"
              >
                <div class="flex items-start justify-between">
                  <div class="flex items-start space-x-3">
                    <!-- Imagen del tractor -->
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
                    
                    <!-- Información del tractor -->
                    <div class="flex-1 min-w-0">
                      <p class="font-medium text-gray-900">{{ tractor.brand }} {{ tractor.model }}</p>
                      <div class="mt-1 flex items-center space-x-4 text-xs text-gray-500">
                        <span v-if="tractor.year">Año: {{ tractor.year }}</span>
                        <span v-if="tractor.horsepower">{{ tractor.horsepower }} CV</span>
                        <span v-if="tractor.license_plate" class="font-mono">{{ tractor.license_plate }}</span>
                      </div>
                      <div class="mt-2">
                        <span 
                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                          :class="tractor.is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                        >
                          {{ tractor.is_available ? 'Disponible' : 'No disponible' }}
                        </span>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Acciones -->
                  <div class="flex flex-col space-y-1">
                    <Link 
                      :href="route('tractors.show', tractor.id)" 
                      class="text-green-600 hover:text-green-900 text-sm"
                    >
                      Ver
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
            <div v-else class="px-4 py-8 text-center text-gray-500">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
              </svg>
              <p class="mt-2">No hay tractores asignados</p>
            </div>
          </div>
        </div>
        
        <!-- Tractores disponibles -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
          <div class="px-4 py-5 sm:px-6 bg-blue-50">
            <div class="flex justify-between items-center">
              <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">Tractores Disponibles</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Asigna tractores a este usuario</p>
              </div>
              <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                {{ availableTractors.length }} disponibles
              </span>
            </div>
          </div>
          
          <!-- Formulario de asignación rápida -->
          <div class="p-4 border-b border-gray-200 bg-gray-50">
            <form @submit.prevent="assignTractor">
              <div class="flex space-x-2">
                <div class="flex-grow">
                  <select 
                    v-model="form.tractor_id" 
                    class="block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900 text-sm"
                    required
                  >
                    <option value="">Seleccione un tractor para asignar</option>
                    <option 
                      v-for="tractor in availableTractors" 
                      :key="tractor.id" 
                      :value="tractor.id"
                    >
                      {{ tractor.brand }} {{ tractor.model }} 
                      {{ tractor.year ? `(${tractor.year})` : '' }}
                    </option>
                  </select>
                </div>
                <button 
                  type="submit" 
                  class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition text-sm disabled:opacity-50"
                  :disabled="form.processing || !form.tractor_id"
                >
                  {{ form.processing ? 'Asignando...' : 'Asignar' }}
                </button>
              </div>
              <div v-if="form.errors.tractor_id" class="text-red-500 text-sm mt-1">{{ form.errors.tractor_id }}</div>
            </form>
          </div>
          
          <!-- Lista de tractores disponibles -->
          <div class="max-h-96 overflow-y-auto">
            <div v-if="availableTractors.length" class="divide-y divide-gray-200">
              <div 
                v-for="tractor in availableTractors" 
                :key="tractor.id" 
                class="px-4 py-4 hover:bg-blue-50 transition"
              >
                <div class="flex items-start justify-between">
                  <div class="flex items-start space-x-3">
                    <!-- Imagen del tractor -->
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
                    
                    <!-- Información del tractor -->
                    <div class="flex-1 min-w-0">
                      <p class="font-medium text-gray-900">{{ tractor.brand }} {{ tractor.model }}</p>
                      <div class="mt-1 flex items-center space-x-4 text-xs text-gray-500">
                        <span v-if="tractor.year">Año: {{ tractor.year }}</span>
                        <span v-if="tractor.horsepower">{{ tractor.horsepower }} CV</span>
                        <span v-if="tractor.license_plate" class="font-mono">{{ tractor.license_plate }}</span>
                      </div>
                      
                      <!-- Propietarios actuales -->
                      <div v-if="tractor.owners && tractor.owners.length > 0" class="mt-2">
                        <p class="text-xs text-gray-500">
                          <span class="font-medium">Propietarios:</span>
                          <span v-for="(owner, index) in tractor.owners.slice(0, 2)" :key="owner.id">
                            {{ owner.first_name }} {{ owner.last_name }}{{ index < Math.min(tractor.owners.length, 2) - 1 ? ', ' : '' }}
                          </span>
                          <span v-if="tractor.owners.length > 2">
                            +{{ tractor.owners.length - 2 }} más
                          </span>
                        </p>
                      </div>
                      
                      <!-- Estado de disponibilidad -->
                      <div class="mt-2">
                        <span 
                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                          :class="tractor.is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                        >
                          {{ tractor.is_available ? 'Disponible' : 'No disponible' }}
                        </span>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Acciones -->
                  <div class="flex flex-col space-y-1">
                    <Link 
                      :href="route('tractors.show', tractor.id)" 
                      class="text-blue-600 hover:text-blue-900 text-sm"
                    >
                      Ver
                    </Link>
                    <button 
                      @click="quickAssign(tractor.id)" 
                      class="text-green-600 hover:text-green-900 text-sm"
                    >
                      Asignar
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <div v-else class="px-4 py-8 text-center text-gray-500">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
              </svg>
              <p class="mt-2">No hay tractores disponibles para asignar</p>
              <p class="text-sm">Todos los tractores ya están asignados a este usuario</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Resumen estadístico -->
      <div v-if="user.tractors && user.tractors.length" class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 bg-gray-50">
          <h3 class="text-lg leading-6 font-medium text-gray-900">Resumen</h3>
          <p class="mt-1 max-w-2xl text-sm text-gray-500">Estadísticas de los tractores de este usuario</p>
        </div>
        <div class="border-t border-gray-200">
          <div class="grid grid-cols-1 md:grid-cols-4 divide-y md:divide-y-0 md:divide-x divide-gray-200">
            <div class="px-6 py-4 text-center">
              <div class="text-2xl font-bold text-green-600">{{ user.tractors.length }}</div>
              <div class="text-sm text-gray-500">Total Tractores</div>
            </div>
            <div class="px-6 py-4 text-center">
              <div class="text-2xl font-bold text-green-600">{{ availableTractorsCount }}</div>
              <div class="text-sm text-gray-500">Disponibles</div>
            </div>
            <div class="px-6 py-4 text-center">
              <div class="text-2xl font-bold text-yellow-600">{{ totalHorsepower }}</div>
              <div class="text-sm text-gray-500">CV Totales</div>
            </div>
            <div class="px-6 py-4 text-center">
              <div class="text-2xl font-bold text-blue-600">{{ averageYear }}</div>
              <div class="text-sm text-gray-500">Año Promedio</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup lang="ts">
import { useForm, router, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { computed } from 'vue';

const props = defineProps({
  user: Object,
  availableTractors: Array
});

const form = useForm({
  tractor_id: ''
});

const availableTractorsCount = computed(() => {
  return props.user.tractors?.filter(tractor => tractor.is_available).length || 0;
});

const totalHorsepower = computed(() => {
  return props.user.tractors?.reduce((total, tractor) => total + (tractor.horsepower || 0), 0) || 0;
});

const averageYear = computed(() => {
  const tractorsWithYear = props.user.tractors?.filter(tractor => tractor.year) || [];
  if (tractorsWithYear.length === 0) return 'N/A';
  const sum = tractorsWithYear.reduce((total, tractor) => total + tractor.year, 0);
  return Math.round(sum / tractorsWithYear.length);
});

const assignTractor = () => {
  form.post(route('users.add-tractor', props.user.id), {
    onSuccess: () => {
      form.reset();
    }
  });
};

const quickAssign = (tractorId) => {
  form.tractor_id = tractorId;
  assignTractor();
};

const removeTractor = (tractorId) => {
  if (confirm('¿Estás seguro de que quieres quitar este tractor del usuario? El tractor no se eliminará, solo se desasociará.')) {
    router.delete(route('users.remove-tractor', { 
      user: props.user.id, 
      tractor: tractorId 
    }));
  }
};
</script>

<style src="../../../../css/Admin/Users/user_assigntractors_style.css" scoped></style>