<template>
    <AdminLayout>
      <template #header>
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
          Conectar Aperos
        </h2>
      </template>
      <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-2xl font-bold text-green-800">Aperos para: {{ tractor.brand }} {{ tractor.model }}</h1>
          <Link 
            :href="route('tractors.show', tractor.id)" 
            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition"
          >
            Volver a Detalles
          </Link>
        </div>
  
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Aperos conectados -->
          <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 bg-green-50">
              <h3 class="text-lg leading-6 font-medium text-gray-900">Aperos Conectados</h3>
              <p class="mt-1 max-w-2xl text-sm text-gray-500">Aperos actualmente conectados a este tractor</p>
            </div>
            <div class="overflow-y-auto max-h-96">
              <ul class="divide-y divide-gray-200">
                <li v-for="apero in attachedAperos" :key="apero.id" class="px-4 py-4 hover:bg-green-50 transition">
                  <div class="flex justify-between items-center">
                    <div>
                      <p class="font-medium text-gray-900">{{ apero.name }}</p>
                      <p class="text-sm text-gray-500">Marca: {{ apero.brand || 'Sin marca' }}</p>
                      <p class="text-sm text-gray-500">Modelo: {{ apero.model || 'Sin modelo' }}</p>
                      <p class="text-sm text-gray-500">
                        Conectado el: {{ apero.pivot?.attached_at ? formatDate(apero.pivot.attached_at) : 'No registrado' }}
                      </p>
                      <p v-if="apero.pivot?.detached_at" class="text-sm text-gray-500">
                        Desconectado el: {{ formatDate(apero.pivot.detached_at) }}
                      </p>
                    </div>
                    <div class="flex space-x-2">
                      <Link 
                        :href="route('aperos.show', apero.id)" 
                        class="text-blue-600 hover:text-blue-900"
                      >
                        Ver
                      </Link>
                      <button 
                        @click="detachApero(apero.id)" 
                        v-if="!apero.pivot?.detached_at"
                        class="text-red-600 hover:text-red-900"
                      >
                        Desconectar
                      </button>
                    </div>
                  </div>
                </li>
                <li v-if="attachedAperos.length === 0" class="px-4 py-4 text-gray-500">
                  No hay aperos conectados a este tractor
                </li>
              </ul>
            </div>
          </div>
          
          <!-- Aperos disponibles -->
          <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 bg-blue-50">
              <h3 class="text-lg leading-6 font-medium text-gray-900">Aperos Disponibles</h3>
              <p class="mt-1 max-w-2xl text-sm text-gray-500">Conecte un apero a este tractor</p>
            </div>
            
            <div class="p-4">
              <form @submit.prevent="attachApero">
                <div class="flex space-x-2">
                  <div class="flex-grow">
                    <select 
                      v-model="form.apero_id" 
                      class="block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                      required
                    >
                      <option value="">Seleccione un apero</option>
                      <option v-for="apero in availableAperos" :key="apero.id" :value="apero.id">
                        {{ apero.name }} ({{ apero.brand || 'Sin marca' }} {{ apero.model || 'Sin modelo' }})
                      </option>
                    </select>
                  </div>
                  <button 
                    type="submit" 
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
                    :disabled="form.processing || !form.apero_id"
                  >
                    Conectar
                  </button>
                </div>
                <div v-if="form.errors.apero_id" class="text-red-500 text-sm mt-1">{{ form.errors.apero_id }}</div>
              </form>
            </div>
            
            <div class="overflow-y-auto max-h-96">
              <ul class="divide-y divide-gray-200">
                <li v-for="apero in availableAperos" :key="apero.id" class="px-4 py-4 hover:bg-blue-50 transition">
                  <div class="flex justify-between items-center">
                    <div>
                      <p class="font-medium text-gray-900">{{ apero.name }}</p>
                      <p class="text-sm text-gray-500">Tipo: {{ apero.type || 'Sin tipo' }}</p>
                      <p class="text-sm text-gray-500">Marca: {{ apero.brand || 'Sin marca' }}</p>
                      <p class="text-sm text-gray-500">Modelo: {{ apero.model || 'Sin modelo' }}</p>
                      <p class="text-sm text-gray-500" v-if="apero.year">Año: {{ apero.year }}</p>
                    </div>
                    <div class="flex space-x-2">
                      <Link 
                        :href="route('aperos.show', apero.id)" 
                        class="text-blue-600 hover:text-blue-900"
                      >
                        Ver
                      </Link>
                      <button 
                        @click="quickAttach(apero.id)" 
                        class="text-green-600 hover:text-green-900"
                      >
                        Conectar
                      </button>
                    </div>
                  </div>
                </li>
                <li v-if="availableAperos.length === 0" class="px-4 py-4 text-gray-500">
                  No hay aperos disponibles para conectar
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
      year: 'numeric'
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
    if (confirm('¿Estás seguro de que quieres desconectar este apero?')) {
      router.delete(route('tractors.detach-apero', [props.tractor.id, aperoId]));
    }
  };
  </script>
  
  <style scoped>
  .overflow-y-auto {
    overflow-y: auto;
  }
  </style>