<template>
    <AdminLayout>
      <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6 text-green-800">Editar Tractor</h1>
  
        <form @submit.prevent="submit" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2" for="model">
                Modelo
              </label>
              <input 
                id="model" 
                v-model="form.model" 
                type="text" 
                placeholder="Modelo del tractor" 
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
              />
              <div v-if="form.errors.model" class="text-red-500 text-xs mt-1">
                {{ form.errors.model }}
              </div>
            </div>
  
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2" for="year">
                Año
              </label>
              <input 
                id="year" 
                v-model="form.year" 
                type="number" 
                placeholder="Año de fabricación" 
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
              />
              <div v-if="form.errors.year" class="text-red-500 text-xs mt-1">
                {{ form.errors.year }}
              </div>
            </div>
  
            <div class="col-span-1 md:col-span-2">
              <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                Descripción
              </label>
              <textarea 
                id="description" 
                v-model="form.description" 
                placeholder="Descripción detallada del tractor" 
                rows="4"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
              ></textarea>
              <div v-if="form.errors.description" class="text-red-500 text-xs mt-1">
                {{ form.errors.description }}
              </div>
            </div>
  
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2" for="field_2">
                Campo Adicional (Opcional)
              </label>
              <input 
                id="field_2" 
                v-model="form.field_2" 
                type="number" 
                placeholder="Campo adicional" 
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
              />
              <div v-if="form.errors.field_2" class="text-red-500 text-xs mt-1">
                {{ form.errors.field_2 }}
              </div>
            </div>
          </div>
  
          <div class="flex items-center justify-between mt-6">
            <button 
              type="submit" 
              class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition"
            >
              Actualizar Tractor
            </button>
            <Link 
              :href="route('tractors.index')" 
              class="text-gray-600 hover:text-gray-900"
            >
              Cancelar
            </Link>
          </div>
        </form>
      </div>
    </AdminLayout>
  </template>
  
  <script setup>
  import { useForm, Link } from '@inertiajs/vue3';
  import AdminLayout from '@/Layouts/AdminLayout.vue';
  
  const props = defineProps({
    tractor: Object
  });
  
  const form = useForm({
    model: props.tractor.model || '',
    year: props.tractor.year || null,
    description: props.tractor.description || '',
    field_2: props.tractor.field_2 || null
  });
  
  const submit = () => {
    form.put(route('tractors.update', props.tractor.id), {
      onFinish: () => form.reset()
    });
  };
  </script>