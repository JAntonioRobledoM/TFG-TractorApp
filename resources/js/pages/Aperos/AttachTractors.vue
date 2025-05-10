<template>
    <AdminLayout>
      <template #header>
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
          Conectar Tractores
        </h2>
      </template>
      <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-2xl font-bold text-green-800">Tractores para: {{ apero.name }}</h1>
          <Link 
            :href="route('aperos.show', apero.id)" 
            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition"
          >
            Volver a Detalles
          </Link>
        </div>
  
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Tractores conectados -->
          <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 bg-green-50">
              <h3 class="text-lg leading-6 font-medium text-gray-900">Tractores Conectados</h3>
              <p class="mt-1 max-w-2xl text-sm text-gray-500">Tractores actualmente conectados a este apero</p>
            </div>
            <div class="overflow-y-auto max-h-96">
              <ul class="divide-y divide-gray-200">
                <li v-for="tractor in attachedTractors" :key="tractor.id" class="px-4 py-4 hover:bg-green-50 transition">
                  <div class="flex justify-between items-center">
                    <div>
                      <p class="font-medium text-gray-900">{{ tractor.brand }} {{ tractor.model }}</p>
                      <p class="text-sm text-gray-500">Matrícula: {{ tractor.license_plate || 'Sin matrícula' }}</p>
                      <p class="text-sm text-gray-500">
                        Conectado el: {{ tractor.pivot?.attached_at ? formatDate(tractor.pivot.attached_at) : 'No registrado' }}
                      </p>
                      <p v-if="tractor.pivot?.detached_at" class="text-sm text-gray-500">
                        Desconectado el: {{ formatDate(tractor.pivot.detached_at) }}
                      </p>
                    </div>
                    <div class="flex space-x-2">
                      <Link 
                        :href="route('tractors.show', tractor.id)" 
                        class="text-blue-600 hover:text-blue-900"
                      >
                        Ver
                      </Link>
                      <button 
                        @click="detachTractor(tractor.id)" 
                        v-if="!tractor.pivot?.detached_at"
                        class="text-red-600 hover:text-red-900"
                      >
                        Desconectar
                      </button>
                    </div>
                  </div>
                </li>
                <li v-if="attachedTractors.length === 0" class="px-4 py-4 text-gray-500">
                  No hay tractores conectados a este apero
                </li>
              </ul>
            </div>
          </div>
          
          <!-- Tractores disponibles -->
          <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 bg-blue-50">
              <h3 class="text-lg leading-6 font-medium text-gray-900">Tractores Disponibles</h3>
              <p class="mt-1 max-w-2xl text-sm text-gray-500">Conecte un tractor a este apero</p>
            </div>
            
            <div class="p-4">
              <form @submit.prevent="attachTractor">
                <div class="flex space-x-2">
                  <div class="flex-grow">
                    <select 
                      v-model="form.tractor_id" 
                      class="block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                      required
                    >
                      <option value="">Seleccione un tractor</option>
                      <option v-for="tractor in availableTractors" :key="tractor.id" :value="tractor.id">
                        {{ tractor.brand }} {{ tractor.model }} ({{ tractor.license_plate || 'Sin matrícula' }})
                      </option>
                    </select>
                  </div>
                  <button 
                    type="submit" 
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
                    :disabled="form.processing || !form.tractor_id"
                  >
                    Conectar
                  </button>
                </div>
                <div v-if="form.errors.tractor_id" class="text-red-500 text-sm mt-1">{{ form.errors.tractor_id }}</div>
              </form>
            </div>
            
            <div class="overflow-y-auto max-h-96">
              <ul class="divide-y divide-gray-200">
                <li v-for="tractor in availableTractors" :key="tractor.id" class="px-4 py-4 hover:bg-blue-50 transition">
                  <div class="flex justify-between items-center">
                    <div>
                      <p class="font-medium text-gray-900">{{ tractor.brand }} {{ tractor.model }}</p>
                      <p class="text-sm text-gray-500">Matrícula: {{ tractor.license_plate || 'Sin matrícula' }}</p>
                      <p class="text-sm text-gray-500" v-if="tractor.year">Año: {{ tractor.year }}</p>
                      <p class="text-sm text-gray-500" v-if="tractor.horsepower">Potencia: {{ tractor.horsepower }} CV</p>
                    </div>
                    <div class="flex space-x-2">
                      <Link 
                        :href="route('tractors.show', tractor.id)" 
                        class="text-blue-600 hover:text-blue-900"
                      >
                        Ver
                      </Link>
                      <button 
                        @click="quickAttach(tractor.id)" 
                        class="text-green-600 hover:text-green-900"
                      >
                        Conectar
                      </button>
                    </div>
                  </div>
                </li>
                <li v-if="availableTractors.length === 0" class="px-4 py-4 text-gray-500">
                  No hay tractores disponibles para conectar
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
      year: 'numeric'
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
    if (confirm('¿Estás seguro de que quieres desconectar este tractor?')) {
      router.delete(route('aperos.detach-tractor', [props.apero.id, tractorId]));
    }
  };
  </script>
  
  <style scoped>
  .overflow-y-auto {
    overflow-y: auto;
  }
  </style>