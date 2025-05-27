<template>
    <AdminLayout>
      <template #header>
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
          Conectar Tractores
        </h2>
      </template>
      <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
          <div>
            <h1 class="text-2xl font-bold text-green-800">Tractores para: {{ apero.name }}</h1>
            <p class="text-sm text-gray-600 mt-1">Gestiona las conexiones entre este apero y los tractores</p>
          </div>
          <div class="space-x-2">
            <Link 
              :href="route('aperos.index')" 
              class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition"
            >
              Lista de Aperos
            </Link>
            <Link 
              :href="route('aperos.show', apero.id)" 
              class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition"
            >
              Volver a Detalles
            </Link>
          </div>
        </div>

        <!-- Información del Apero -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
          <div class="px-4 py-5 sm:px-6 bg-green-50">
            <div class="flex items-center space-x-4">
              <div class="flex-shrink-0">
                <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
                  <img 
                    v-if="apero.image" 
                    :src="`/storage/${apero.image}`" 
                    :alt="apero.name"
                    class="w-full h-full object-cover"
                  />
                  <svg v-else class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                  </svg>
                </div>
              </div>
              <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ apero.name }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ apero.type || 'Sin tipo' }} - {{ apero.brand || 'Sin marca' }} {{ apero.model || '' }}</p>
                <span 
                  class="mt-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                  :class="apero.is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                >
                  {{ apero.is_available ? 'Disponible' : 'No disponible' }}
                </span>
              </div>
            </div>
          </div>
        </div>
  
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
          <!-- Tractores conectados -->
          <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 bg-green-50">
              <div class="flex justify-between items-center">
                <div>
                  <h3 class="text-lg leading-6 font-medium text-gray-900">Tractores Conectados</h3>
                  <p class="mt-1 max-w-2xl text-sm text-gray-500">Tractores que han estado conectados a este apero</p>
                </div>
                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                  {{ attachedTractors.length }} tractores
                </span>
              </div>
            </div>
            <div class="overflow-y-auto max-h-96">
              <ul class="divide-y divide-gray-200">
                <li v-for="tractor in attachedTractors" :key="tractor.id" class="px-4 py-4 hover:bg-green-50 transition">
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
                        <p class="text-sm text-gray-500">Matrícula: {{ tractor.license_plate || 'Sin matrícula' }}</p>
                        <div class="mt-1 flex items-center space-x-4 text-xs text-gray-500">
                          <span v-if="tractor.year">Año: {{ tractor.year }}</span>
                          <span v-if="tractor.horsepower">{{ tractor.horsepower }} CV</span>
                        </div>
                        
                        <!-- Fechas de conexión -->
                        <div class="mt-2 space-y-1">
                          <p class="text-xs text-gray-500">
                            <span class="font-medium">Conectado:</span> 
                            {{ tractor.pivot?.attached_at ? formatDate(tractor.pivot.attached_at) : 'No registrado' }}
                          </p>
                          <div v-if="tractor.pivot?.detached_at" class="flex items-center">
                            <span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded-full">Desconectado</span>
                            <span class="ml-2 text-xs text-gray-500">
                              {{ formatDate(tractor.pivot.detached_at) }}
                            </span>
                          </div>
                          <div v-else class="flex items-center">
                            <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Actualmente conectado</span>
                          </div>
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
                        @click="detachTractor(tractor.id)" 
                        v-if="!tractor.pivot?.detached_at"
                        class="text-red-600 hover:text-red-900 text-sm"
                      >
                        Desconectar
                      </button>
                      <button 
                        @click="reconnectTractor(tractor.id)" 
                        v-else
                        class="text-green-600 hover:text-green-900 text-sm"
                      >
                        Reconectar
                      </button>
                    </div>
                  </div>
                </li>
                <li v-if="attachedTractors.length === 0" class="px-4 py-8 text-center text-gray-500">
                  <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                  </svg>
                  <p class="mt-2">No hay tractores conectados a este apero</p>
                </li>
              </ul>
            </div>
          </div>
          
          <!-- Tractores disponibles -->
          <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 bg-blue-50">
              <div class="flex justify-between items-center">
                <div>
                  <h3 class="text-lg leading-6 font-medium text-gray-900">Tractores Disponibles</h3>
                  <p class="mt-1 max-w-2xl text-sm text-gray-500">Conecte nuevos tractores a este apero</p>
                </div>
                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                  {{ availableTractors.length }} disponibles
                </span>
              </div>
            </div>
            
            <!-- Formulario de conexión rápida -->
            <div class="p-4 border-b border-gray-200 bg-gray-50">
              <form @submit.prevent="attachTractor">
                <div class="flex space-x-2">
                  <div class="flex-grow">
                    <select 
                      v-model="form.tractor_id" 
                      class="block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900 text-sm"
                      required
                    >
                      <option value="">Seleccione un tractor para conectar</option>
                      <option v-for="tractor in availableTractors" :key="tractor.id" :value="tractor.id">
                        {{ tractor.brand }} {{ tractor.model }} 
                        {{ tractor.license_plate ? `(${tractor.license_plate})` : '' }}
                      </option>
                    </select>
                  </div>
                  <button 
                    type="submit" 
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition text-sm disabled:opacity-50"
                    :disabled="form.processing || !form.tractor_id"
                  >
                    {{ form.processing ? 'Conectando...' : 'Conectar' }}
                  </button>
                </div>
                <div v-if="form.errors.tractor_id" class="text-red-500 text-sm mt-1">{{ form.errors.tractor_id }}</div>
              </form>
            </div>
            
            <!-- Lista de tractores disponibles -->
            <div class="overflow-y-auto max-h-96">
              <ul class="divide-y divide-gray-200">
                <li v-for="tractor in availableTractors" :key="tractor.id" class="px-4 py-4 hover:bg-blue-50 transition">
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
                        <p class="text-sm text-gray-500">Matrícula: {{ tractor.license_plate || 'Sin matrícula' }}</p>
                        <div class="mt-1 flex items-center space-x-4 text-xs text-gray-500">
                          <span v-if="tractor.year">Año: {{ tractor.year }}</span>
                          <span v-if="tractor.horsepower">{{ tractor.horsepower }} CV</span>
                          <span v-if="tractor.working_hours">{{ tractor.working_hours }}h</span>
                        </div>
                        
                        <!-- Propietarios -->
                        <div v-if="tractor.owners && tractor.owners.length > 0" class="mt-2">
                          <p class="text-xs text-gray-500">
                            <span class="font-medium">Propietarios:</span>
                            <span v-for="(owner, index) in tractor.owners" :key="owner.id">
                              {{ owner.first_name }} {{ owner.last_name }}{{ index < tractor.owners.length - 1 ? ', ' : '' }}
                            </span>
                          </p>
                        </div>
                        
                        <!-- Estado de disponibilidad -->
                        <div class="mt-2">
                          <span 
                            class="px-2 py-1 text-xs font-medium rounded-full" 
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
                        @click="quickAttach(tractor.id)" 
                        class="text-green-600 hover:text-green-900 text-sm"
                        :disabled="!tractor.is_available"
                      >
                        Conectar
                      </button>
                    </div>
                  </div>
                </li>
                <li v-if="availableTractors.length === 0" class="px-4 py-8 text-center text-gray-500">
                  <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                  </svg>
                  <p class="mt-2">No hay tractores disponibles para conectar</p>
                  <p class="text-sm">Todos los tractores ya están conectados a este apero</p>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </AdminLayout>
  </template>
  
  <script setup lang="ts">
  import { useForm, Link, router } from '@inertiajs/vue3';
  import AdminLayout from '@/Layouts/AdminLayout.vue';
  
  const props = defineProps({
    apero: Object,
    attachedTractors: Array,
    availableTractors: Array
  });
  
  const form = useForm({
    tractor_id: ''
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
  
  const attachTractor = () => {
    form.post(route('aperos.attach-tractor', props.apero.id), {
      onSuccess: () => {
        form.reset();
      }
    });
  };
  
  const quickAttach = (tractorId) => {
    form.tractor_id = tractorId;
    attachTractor();
  };
  
  const detachTractor = (tractorId) => {
    if (confirm('¿Estás seguro de que quieres desconectar este tractor? Se registrará la fecha de desconexión.')) {
      router.delete(route('aperos.detach-tractor', [props.apero.id, tractorId]));
    }
  };
  
  const reconnectTractor = (tractorId) => {
    if (confirm('¿Quieres reconectar este tractor? Se creará una nueva conexión.')) {
      form.tractor_id = tractorId;
      attachTractor();
    }
  };
  </script>
  
  <style scoped>
  .overflow-y-auto {
    overflow-y: auto;
  }
  </style>