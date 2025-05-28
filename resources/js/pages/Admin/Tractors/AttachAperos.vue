<template>
    <AdminLayout>
      <template #header>
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
          Conectar Aperos
        </h2>
      </template>
      <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
          <div>
            <h1 class="text-2xl font-bold text-green-800">Aperos para: {{ tractor.brand }} {{ tractor.model }}</h1>
            <p class="text-sm text-gray-600 mt-1">Gestiona las conexiones entre este tractor y los aperos</p>
          </div>
          <div class="space-x-2">
            <Link 
              :href="route('tractors.index')" 
              class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition"
            >
              Lista de Tractores
            </Link>
            <Link 
              :href="route('tractors.show', tractor.id)" 
              class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition"
            >
              Volver a Detalles
            </Link>
          </div>
        </div>

        <!-- Información del Tractor -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
          <div class="px-4 py-5 sm:px-6 bg-green-50">
            <div class="flex items-center space-x-4">
              <div class="flex-shrink-0">
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
              </div>
              <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ tractor.brand }} {{ tractor.model }}</h3>
                <div class="mt-1 flex items-center space-x-4 text-sm text-gray-500">
                  <span v-if="tractor.year">Año: {{ tractor.year }}</span>
                  <span v-if="tractor.horsepower">{{ tractor.horsepower }} CV</span>
                  <span v-if="tractor.license_plate" class="font-mono bg-gray-100 px-2 py-1 rounded">{{ tractor.license_plate }}</span>
                </div>
                <span 
                  class="mt-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                  :class="tractor.is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                >
                  {{ tractor.is_available ? 'Disponible' : 'No disponible' }}
                </span>
              </div>
            </div>
          </div>
        </div>
  
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
          <!-- Aperos conectados -->
          <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 bg-green-50">
              <div class="flex justify-between items-center">
                <div>
                  <h3 class="text-lg leading-6 font-medium text-gray-900">Aperos Conectados</h3>
                  <p class="mt-1 max-w-2xl text-sm text-gray-500">Aperos que han estado conectados a este tractor</p>
                </div>
                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                  {{ attachedAperos.length }} aperos
                </span>
              </div>
            </div>
            <div class="overflow-y-auto max-h-96">
              <ul class="divide-y divide-gray-200">
                <li v-for="apero in attachedAperos" :key="apero.id" class="px-4 py-4 hover:bg-green-50 transition">
                  <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-3">
                      <!-- Imagen del apero -->
                      <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
                          <img 
                            v-if="apero.image" 
                            :src="`/storage/${apero.image}`" 
                            :alt="apero.name"
                            class="w-full h-full object-cover"
                          />
                          <svg v-else class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                          </svg>
                        </div>
                      </div>
                      
                      <!-- Información del apero -->
                      <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-900">{{ apero.name }}</p>
                        <p class="text-sm text-gray-500">{{ apero.type || 'Sin tipo' }}</p>
                        <div class="mt-1 flex items-center space-x-4 text-xs text-gray-500">
                          <span>{{ apero.brand || 'Sin marca' }}</span>
                          <span v-if="apero.model">{{ apero.model }}</span>
                          <span v-if="apero.year">{{ apero.year }}</span>
                        </div>
                        
                        <!-- Fechas de conexión -->
                        <div class="mt-2 space-y-1">
                          <p class="text-xs text-gray-500">
                            <span class="font-medium">Conectado:</span> 
                            {{ apero.pivot?.attached_at ? formatDate(apero.pivot.attached_at) : 'No registrado' }}
                          </p>
                          <div v-if="apero.pivot?.detached_at" class="flex items-center">
                            <span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded-full">Desconectado</span>
                            <span class="ml-2 text-xs text-gray-500">
                              {{ formatDate(apero.pivot.detached_at) }}
                            </span>
                          </div>
                          <div v-else class="flex items-center">
                            <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Actualmente conectado</span>
                          </div>
                        </div>

                        <!-- Propietario del apero -->
                        <div v-if="apero.user" class="mt-2 flex items-center space-x-2">
                          <div class="flex-shrink-0 h-6 w-6">
                            <img 
                              v-if="apero.user.pfp" 
                              class="h-6 w-6 rounded-full object-cover" 
                              :src="`/storage/${apero.user.pfp}`" 
                              :alt="apero.user.username"
                            />
                            <div v-else class="h-6 w-6 rounded-full bg-gray-300 flex items-center justify-center">
                              <span class="text-xs font-medium text-gray-700">
                                {{ (apero.user.first_name?.[0] || '') + (apero.user.last_name?.[0] || '') }}
                              </span>
                            </div>
                          </div>
                          <span class="text-xs text-gray-500">
                            {{ apero.user.first_name }} {{ apero.user.last_name }}
                          </span>
                        </div>
                      </div>
                    </div>
                    
                    <!-- Acciones -->
                    <div class="flex flex-col space-y-1">
                      <Link 
                        :href="route('aperos.show', apero.id)" 
                        class="text-blue-600 hover:text-blue-900 text-sm"
                      >
                        Ver
                      </Link>
                      <button 
                        @click="detachApero(apero.id)" 
                        v-if="!apero.pivot?.detached_at"
                        class="text-red-600 hover:text-red-900 text-sm"
                      >
                        Desconectar
                      </button>
                      <button 
                        @click="reconnectApero(apero.id)" 
                        v-else
                        class="text-green-600 hover:text-green-900 text-sm"
                      >
                        Reconectar
                      </button>
                    </div>
                  </div>
                </li>
                <li v-if="attachedAperos.length === 0" class="px-4 py-8 text-center text-gray-500">
                  <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                  </svg>
                  <p class="mt-2">No hay aperos conectados a este tractor</p>
                </li>
              </ul>
            </div>
          </div>
          
          <!-- Aperos disponibles -->
          <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 bg-blue-50">
              <div class="flex justify-between items-center">
                <div>
                  <h3 class="text-lg leading-6 font-medium text-gray-900">Aperos Disponibles</h3>
                  <p class="mt-1 max-w-2xl text-sm text-gray-500">Conecte nuevos aperos a este tractor</p>
                </div>
                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                  {{ availableAperos.length }} disponibles
                </span>
              </div>
            </div>
            
            <!-- Formulario de conexión rápida -->
            <div class="p-4 border-b border-gray-200 bg-gray-50">
              <form @submit.prevent="attachApero">
                <div class="flex space-x-2">
                  <div class="flex-grow">
                    <select 
                      v-model="form.apero_id" 
                      class="block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900 text-sm"
                      required
                    >
                      <option value="">Seleccione un apero para conectar</option>
                      <option v-for="apero in availableAperos" :key="apero.id" :value="apero.id">
                        {{ apero.name }} 
                        {{ apero.brand && apero.model ? `(${apero.brand} ${apero.model})` : '' }}
                      </option>
                    </select>
                  </div>
                  <button 
                    type="submit" 
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition text-sm disabled:opacity-50"
                    :disabled="form.processing || !form.apero_id"
                  >
                    {{ form.processing ? 'Conectando...' : 'Conectar' }}
                  </button>
                </div>
                <div v-if="form.errors.apero_id" class="text-red-500 text-sm mt-1">{{ form.errors.apero_id }}</div>
              </form>
            </div>
            
            <!-- Lista de aperos disponibles -->
            <div class="overflow-y-auto max-h-96">
              <ul class="divide-y divide-gray-200">
                <li v-for="apero in availableAperos" :key="apero.id" class="px-4 py-4 hover:bg-blue-50 transition">
                  <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-3">
                      <!-- Imagen del apero -->
                      <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
                          <img 
                            v-if="apero.image" 
                            :src="`/storage/${apero.image}`" 
                            :alt="apero.name"
                            class="w-full h-full object-cover"
                          />
                          <svg v-else class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                          </svg>
                        </div>
                      </div>
                      
                      <!-- Información del apero -->
                      <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-900">{{ apero.name }}</p>
                        <p class="text-sm text-gray-500">{{ apero.type || 'Sin tipo' }}</p>
                        <div class="mt-1 flex items-center space-x-4 text-xs text-gray-500">
                          <span>{{ apero.brand || 'Sin marca' }}</span>
                          <span v-if="apero.model">{{ apero.model }}</span>
                          <span v-if="apero.year">{{ apero.year }}</span>
                        </div>
                        
                        <!-- Propietario del apero -->
                        <div v-if="apero.user" class="mt-2 flex items-center space-x-2">
                          <div class="flex-shrink-0 h-6 w-6">
                            <img 
                              v-if="apero.user.pfp" 
                              class="h-6 w-6 rounded-full object-cover" 
                              :src="`/storage/${apero.user.pfp}`" 
                              :alt="apero.user.username"
                            />
                            <div v-else class="h-6 w-6 rounded-full bg-gray-300 flex items-center justify-center">
                              <span class="text-xs font-medium text-gray-700">
                                {{ (apero.user.first_name?.[0] || '') + (apero.user.last_name?.[0] || '') }}
                              </span>
                            </div>
                          </div>
                          <span class="text-xs text-gray-500">
                            {{ apero.user.first_name }} {{ apero.user.last_name }}
                          </span>
                        </div>
                        
                        <!-- Estado de disponibilidad -->
                        <div class="mt-2">
                          <span 
                            class="px-2 py-1 text-xs font-medium rounded-full" 
                            :class="apero.is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                          >
                            {{ apero.is_available ? 'Disponible' : 'No disponible' }}
                          </span>
                        </div>
                      </div>
                    </div>
                    
                    <!-- Acciones -->
                    <div class="flex flex-col space-y-1">
                      <Link 
                        :href="route('aperos.show', apero.id)" 
                        class="text-blue-600 hover:text-blue-900 text-sm"
                      >
                        Ver
                      </Link>
                      <button 
                        @click="quickAttach(apero.id)" 
                        class="text-green-600 hover:text-green-900 text-sm"
                        :disabled="!apero.is_available"
                      >
                        Conectar
                      </button>
                    </div>
                  </div>
                </li>
                <li v-if="availableAperos.length === 0" class="px-4 py-8 text-center text-gray-500">
                  <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                  </svg>
                  <p class="mt-2">No hay aperos disponibles para conectar</p>
                  <p class="text-sm">Todos los aperos ya están conectados a este tractor</p>
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
    tractor: Object,
    attachedAperos: Array,
    availableAperos: Array
  });
  
  const form = useForm({
    apero_id: ''
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
  
  const attachApero = () => {
    form.post(route('tractors.attach-apero', props.tractor.id), {
      onSuccess: () => {
        form.reset();
      }
    });
  };
  
  const quickAttach = (aperoId) => {
    form.apero_id = aperoId;
    attachApero();
  };
  
  const detachApero = (aperoId) => {
    if (confirm('¿Estás seguro de que quieres desconectar este apero? Se registrará la fecha de desconexión.')) {
      router.delete(route('tractors.detach-apero', [props.tractor.id, aperoId]));
    }
  };
  
  const reconnectApero = (aperoId) => {
    if (confirm('¿Quieres reconectar este apero? Se creará una nueva conexión.')) {
      form.apero_id = aperoId;
      attachApero();
    }
  };
  </script>
  
<style src="../../../../css/Admin/Tractors/tractor_attachaperos_style.css" scoped></style>