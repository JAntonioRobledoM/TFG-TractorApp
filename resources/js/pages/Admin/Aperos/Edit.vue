<template>
    <AdminLayout>
      <template #header>
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
          Editar Apero
        </h2>
      </template>
      <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-2xl font-bold text-green-800">Editar: {{ apero.name }}</h1>
          <div class="space-x-2">
            <Link 
              :href="route('aperos.index')" 
              class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition"
            >
              Volver a la Lista
            </Link>
            <Link 
              :href="route('aperos.show', apero.id)" 
              class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition"
            >
              Ver Detalles
            </Link>
          </div>
        </div>
  
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
          <div class="p-6">
            <form @submit.prevent="submit">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                  <input 
                    type="text" 
                    id="name" 
                    v-model="form.name" 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                    required
                  />
                  <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</div>
                </div>
  
                <div>
                  <label for="type" class="block text-sm font-medium text-gray-700">Tipo</label>
                  <input 
                    type="text" 
                    id="type" 
                    v-model="form.type" 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                  />
                  <div v-if="form.errors.type" class="text-red-500 text-sm mt-1">{{ form.errors.type }}</div>
                </div>
  
                <div>
                  <label for="brand" class="block text-sm font-medium text-gray-700">Marca</label>
                  <input 
                    type="text" 
                    id="brand" 
                    v-model="form.brand" 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                  />
                  <div v-if="form.errors.brand" class="text-red-500 text-sm mt-1">{{ form.errors.brand }}</div>
                </div>
  
                <div>
                  <label for="model" class="block text-sm font-medium text-gray-700">Modelo</label>
                  <input 
                    type="text" 
                    id="model" 
                    v-model="form.model" 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                  />
                  <div v-if="form.errors.model" class="text-red-500 text-sm mt-1">{{ form.errors.model }}</div>
                </div>
  
                <div>
                  <label for="year" class="block text-sm font-medium text-gray-700">Año</label>
                  <input 
                    type="number" 
                    id="year" 
                    v-model="form.year" 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                    min="1900"
                    max="2099"
                  />
                  <div v-if="form.errors.year" class="text-red-500 text-sm mt-1">{{ form.errors.year }}</div>
                </div>
  
                <div>
                  <label for="is_available" class="block text-sm font-medium text-gray-700">Disponibilidad</label>
                  <select 
                    id="is_available" 
                    v-model="form.is_available" 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                  >
                    <option :value="true">Disponible</option>
                    <option :value="false">No disponible</option>
                  </select>
                  <div v-if="form.errors.is_available" class="text-red-500 text-sm mt-1">{{ form.errors.is_available }}</div>
                </div>
              </div>
  
              <div class="mt-6">
                <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                <textarea 
                  id="description" 
                  v-model="form.description" 
                  rows="4" 
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                ></textarea>
                <div v-if="form.errors.description" class="text-red-500 text-sm mt-1">{{ form.errors.description }}</div>
              </div>
  
              <div class="mt-6 flex space-x-2">
                <button 
                  type="submit" 
                  class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition"
                  :disabled="form.processing"
                >
                  {{ form.processing ? 'Guardando...' : 'Guardar Cambios' }}
                </button>
                <Link 
                  :href="route('aperos.index')" 
                  class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition"
                >
                  Cancelar
                </Link>
              </div>
            </form>
          </div>
        </div>
      </div>
    </AdminLayout>
  </template>
  
  <script setup lang="ts">
  import { useForm, Link } from '@inertiajs/vue3';
  import AdminLayout from '@/Layouts/AdminLayout.vue';
  
  const props = defineProps({
    apero: Object
  });
  
  const form = useForm({
    name: props.apero.name,
    type: props.apero.type,
    description: props.apero.description,
    brand: props.apero.brand,
    model: props.apero.model,
    year: props.apero.year,
    is_available: props.apero.is_available,
  });
  
  const submit = () => {
    form.put(route('aperos.update', props.apero.id));
  };
  </script>