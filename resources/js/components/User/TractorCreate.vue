<!-- resources/js/Components/User/TractorCreate.vue -->
<template>
  <div class="fixed inset-0 bg-gray-800 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center px-4">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden max-w-3xl w-full mx-auto">
      <div class="p-4 bg-green-700 text-white font-medium flex items-center justify-between">
        <div class="flex items-center space-x-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          <span>Nuevo Tractor</span>
        </div>
        <button @click="$emit('close')" class="text-white hover:text-green-200 transition duration-200">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </button>
      </div>
      <form @submit.prevent="submitForm" class="p-6 space-y-5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="brand" class="block text-sm font-medium text-gray-700 mb-1">Marca</label>
            <input 
              type="text" 
              id="brand" 
              v-model="form.brand" 
              class="w-full p-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
              :class="{ 'border-red-500': form.errors.brand }"
            >
            <p v-if="form.errors.brand" class="mt-1 text-sm text-red-600">{{ form.errors.brand }}</p>
          </div>
          <div>
            <label for="model" class="block text-sm font-medium text-gray-700 mb-1">Modelo</label>
            <input 
              type="text" 
              id="model" 
              v-model="form.model" 
              class="w-full p-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
              :class="{ 'border-red-500': form.errors.model }"
            >
            <p v-if="form.errors.model" class="mt-1 text-sm text-red-600">{{ form.errors.model }}</p>
          </div>
          <div>
            <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Año</label>
            <input 
              type="number" 
              id="year" 
              v-model="form.year" 
              class="w-full p-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
              :class="{ 'border-red-500': form.errors.year }"
            >
            <p v-if="form.errors.year" class="mt-1 text-sm text-red-600">{{ form.errors.year }}</p>
          </div>
          <div>
            <label for="horsepower" class="block text-sm font-medium text-gray-700 mb-1">Potencia (CV)</label>
            <input 
              type="number" 
              id="horsepower" 
              v-model="form.horsepower" 
              class="w-full p-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
              :class="{ 'border-red-500': form.errors.horsepower }"
            >
            <p v-if="form.errors.horsepower" class="mt-1 text-sm text-red-600">{{ form.errors.horsepower }}</p>
          </div>
          <div>
            <label for="working_hours" class="block text-sm font-medium text-gray-700 mb-1">Horas de uso</label>
            <input 
              type="number" 
              id="working_hours" 
              v-model="form.working_hours" 
              class="w-full p-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
              :class="{ 'border-red-500': form.errors.working_hours }"
              step="0.01"
            >
            <p v-if="form.errors.working_hours" class="mt-1 text-sm text-red-600">{{ form.errors.working_hours }}</p>
          </div>
          <div class="md:col-span-2">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
            <textarea 
              id="description" 
              v-model="form.description" 
              rows="4" 
              class="w-full p-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
              :class="{ 'border-red-500': form.errors.description }"
            ></textarea>
            <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">{{ form.errors.description }}</p>
          </div>
          <div class="flex items-center">
            <input 
              type="checkbox" 
              id="is_available" 
              v-model="form.is_available" 
              class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
            >
            <label for="is_available" class="ml-2 block text-sm text-gray-700">
              Disponible
            </label>
          </div>
        </div>
        <div class="flex space-x-3 pt-4 border-t border-gray-100">
          <button 
            type="button"
            @click="$emit('close')" 
            class="flex-1 py-2 bg-gray-300 text-gray-700 hover:bg-gray-400 rounded-md transition duration-200 flex items-center justify-center"
          >
            Cancelar
          </button>
          <button 
            type="submit" 
            class="flex-1 py-2 bg-green-600 text-white hover:bg-green-700 rounded-md transition duration-200 flex items-center justify-center"
            :disabled="form.processing"
          >
            {{ form.processing ? 'Guardando...' : 'Guardar' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

defineEmits(['close', 'created']);

const form = useForm({
  brand: '',
  model: '',
  year: null,
  description: '',
  horsepower: null,
  working_hours: null,
  is_available: true,
});

const submitForm = () => {
  form.post(route('user.tractors.store'), {
    onSuccess: () => {
      form.reset();
      form.clearErrors();
      emit('created');
    },
    onError: (errors) => {
      console.error('Error al crear el tractor:', errors);
    }
  });
};
</script>