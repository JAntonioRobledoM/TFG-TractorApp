<!-- resources/js/Components/User/TractorShow.vue -->
<template>
  <div class="fixed inset-0 bg-gray-800 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center px-4">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden max-w-3xl w-full mx-auto">
      <div class="p-4 bg-green-700 text-white font-medium flex items-center justify-between">
        <div class="flex items-center space-x-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
            <path d="M19 8h-2c0-.6-.4-1-1-1h-2c-.6 0-1 .4-1 1h-2c-1.7 0-3 1.3-3 3v3c0 1.7 1.3 3 3 3h8c1.7 0 3-1.3 3-3v-3c0-1.7-1.3-3-3-3zm-2 9c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3zM5 11c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"/>
          </svg>
          <span>Detalles del Tractor</span>
        </div>
        <button @click="$emit('close')" class="text-white hover:text-green-200 transition duration-200">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </button>
      </div>
      <div class="p-6 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <h3 class="text-2xl font-bold text-gray-800">{{ tractor.brand }} {{ tractor.model }}</h3>
            <div class="grid grid-cols-2 gap-4 mt-4">
              <div>
                <p class="text-sm text-gray-500">Año</p>
                <p class="font-medium">{{ tractor.year || 'No especificado' }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Potencia</p>
                <p class="font-medium">{{ tractor.horsepower ? `${tractor.horsepower} CV` : 'No especificada' }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Horas de uso</p>
                <p class="font-medium">{{ tractor.working_hours ? `${tractor.working_hours} h` : 'No especificadas' }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Disponible</p>
                <p class="font-medium">{{ tractor.is_available ? 'Sí' : 'No' }}</p>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-sm text-gray-500 mb-2">Descripción</p>
            <p class="text-gray-700 whitespace-pre-line">{{ tractor.description || 'No hay descripción disponible.' }}</p>
          </div>
        </div>
        <div class="flex space-x-3 pt-4 border-t border-gray-100">
          <button 
            @click="$emit('edit', tractor)" 
            class="flex-1 py-2 bg-green-600 text-white hover:bg-green-700 rounded-md transition duration-200 flex items-center justify-center"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
              <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
            </svg>
            Editar
          </button>
          <button 
            @click="$emit('delete', tractor)" 
            class="flex-1 py-2 bg-red-600 text-white hover:bg-red-700 rounded-md transition duration-200 flex items-center justify-center"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            Eliminar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps({
  tractor: {
    type: Object,
    required: true
  }
});
defineEmits(['close', 'edit', 'delete']);
</script>