<!-- resources/js/Components/User/ListingCreate.vue -->
<template>
  <div class="fixed inset-0 bg-gray-800 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center px-4">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden max-w-3xl w-full mx-auto">
      <div class="p-4 bg-green-700 text-white font-medium flex items-center justify-between">
        <div class="flex items-center space-x-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          <span>Publicar nuevo anuncio</span>
        </div>
        <button @click="$emit('close')" class="text-white hover:text-green-200 transition duration-200">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </button>
      </div>
      
      <form @submit.prevent="submitForm" class="p-6 space-y-5">
        <!-- Mensaje de error general -->
        <div v-if="form.errors.general" class="bg-red-50 border border-red-200 text-red-700 p-3 rounded-md">
          {{ form.errors.general }}
        </div>
        
        <!-- Selección de tractor -->
        <div>
          <label for="tractor_id" class="block text-sm font-medium text-gray-700 mb-1">Tractor</label>
          <select 
            id="tractor_id" 
            v-model="form.tractor_id" 
            class="w-full p-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
            :class="{ 'border-red-500': form.errors.tractor_id }"
          >
            <option value="">Selecciona un tractor</option>
            <option v-for="tractor in userTractors" :key="tractor.id" :value="tractor.id">
              {{ tractor.brand }} {{ tractor.model }} ({{ tractor.year }})
            </option>
          </select>
          <p v-if="form.errors.tractor_id" class="mt-1 text-sm text-red-600">{{ form.errors.tractor_id }}</p>
        </div>
        
        <!-- Tipo de anuncio -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de anuncio</label>
          <div class="grid grid-cols-2 gap-4">
            <div 
              class="border rounded-md p-3 cursor-pointer flex items-center space-x-2"
              :class="{ 
                'border-green-500 bg-green-50': form.type === 'sale',
                'border-gray-300 hover:border-gray-400': form.type !== 'sale'
              }"
              @click="form.type = 'sale'"
            >
              <div class="h-4 w-4 rounded-full border border-gray-400 flex items-center justify-center">
                <div v-if="form.type === 'sale'" class="h-2 w-2 rounded-full bg-green-500"></div>
              </div>
              <span>Venta</span>
            </div>
            <div 
              class="border rounded-md p-3 cursor-pointer flex items-center space-x-2"
              :class="{ 
                'border-green-500 bg-green-50': form.type === 'rental',
                'border-gray-300 hover:border-gray-400': form.type !== 'rental'
              }"
              @click="form.type = 'rental'"
            >
              <div class="h-4 w-4 rounded-full border border-gray-400 flex items-center justify-center">
                <div v-if="form.type === 'rental'" class="h-2 w-2 rounded-full bg-green-500"></div>
              </div>
              <span>Alquiler</span>
            </div>
          </div>
          <p v-if="form.errors.type" class="mt-1 text-sm text-red-600">{{ form.errors.type }}</p>
        </div>
        
        <!-- Precio -->
        <div>
          <label for="price" class="block text-sm font-medium text-gray-700 mb-1">
            Precio (€)
            <span class="text-gray-500 text-xs">
              {{ form.type === 'rental' ? '(por día)' : '' }}
            </span>
          </label>
          <input 
            type="number" 
            id="price" 
            v-model="form.price" 
            min="0" 
            step="0.01" 
            class="w-full p-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
            :class="{ 'border-red-500': form.errors.price }"
          >
          <p v-if="form.errors.price" class="mt-1 text-sm text-red-600">{{ form.errors.price }}</p>
        </div>
        
        <!-- Fechas de disponibilidad (solo para alquiler) -->
        <div v-if="form.type === 'rental'" class="grid grid-cols-2 gap-4">
          <div>
            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Fecha inicio</label>
            <input 
              type="date" 
              id="start_date" 
              v-model="form.start_date" 
              class="w-full p-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
              :class="{ 'border-red-500': form.errors.start_date }"
            >
            <p v-if="form.errors.start_date" class="mt-1 text-sm text-red-600">{{ form.errors.start_date }}</p>
          </div>
          <div>
            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Fecha fin</label>
            <input 
              type="date" 
              id="end_date" 
              v-model="form.end_date" 
              class="w-full p-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
              :class="{ 'border-red-500': form.errors.end_date }"
            >
            <p v-if="form.errors.end_date" class="mt-1 text-sm text-red-600">{{ form.errors.end_date }}</p>
          </div>
        </div>
        
        <!-- Descripción -->
        <div>
          <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
          <textarea 
            id="description" 
            v-model="form.description" 
            rows="4" 
            class="w-full p-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
            :class="{ 'border-red-500': form.errors.description }"
            placeholder="Describe tu tractor y las condiciones del anuncio..."
          ></textarea>
          <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">{{ form.errors.description }}</p>
        </div>
        
        <!-- Activo -->
        <div class="flex items-center">
          <input 
            type="checkbox" 
            id="is_active" 
            v-model="form.is_active" 
            class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
          >
          <label for="is_active" class="ml-2 block text-sm text-gray-700">
            Activar anuncio (visible inmediatamente)
          </label>
        </div>
        
        <!-- Botones -->
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
            <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ form.processing ? 'Publicando...' : 'Publicar anuncio' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<!-- resources/js/components/User/ListingCreate.vue -->
<script setup lang="ts">
import { ref, reactive } from 'vue';
import { useForm, router } from '@inertiajs/vue3';

const props = defineProps({
  userTractors: {
    type: Array,
    required: true
  }
});

defineEmits(['close', 'created']);

// Inicializar formulario
const form = useForm({
  tractor_id: '',
  type: 'sale',
  price: '',
  description: '',
  is_active: true,
  start_date: '',
  end_date: '',
});

// Enviar formulario
const submitForm = () => {
  // Validación adicional
  if (form.type === 'rental' && (!form.start_date || !form.end_date)) {
    if (!form.start_date) form.errors.start_date = 'La fecha de inicio es obligatoria para alquileres';
    if (!form.end_date) form.errors.end_date = 'La fecha de fin es obligatoria para alquileres';
    return;
  }
  
  form.post(route('user.listings.store'), {
    onSuccess: () => {
      // Recargar la página para actualizar la lista de anuncios
      router.reload();
    },
    onError: (errors) => {
      // Las validaciones del backend se manejan automáticamente
      // pero podemos agregar lógica adicional si es necesario
      console.error('Error al crear el anuncio:', errors);
    }
  });
};
</script>