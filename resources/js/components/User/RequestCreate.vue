<!-- resources/js/Components/User/RequestCreate.vue -->
<template>
  <div class="fixed inset-0 bg-gray-800 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center px-4">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden max-w-3xl w-full mx-auto">
      <div class="p-4 bg-green-700 text-white font-medium flex items-center justify-between">
        <div class="flex items-center space-x-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          <span>Nueva solicitud</span>
        </div>
        <button @click="$emit('close')" class="text-white hover:text-green-200 transition duration-200">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </button>
      </div>
      
      <div v-if="!selectedListing" class="p-6 space-y-5">
        <h3 class="text-lg font-medium text-gray-800 mb-4">Seleccione un anuncio</h3>
        
        <!-- Filtro de tipo -->
        <div class="flex space-x-4 mb-4">
          <button 
            @click="filterType = 'all'" 
            class="px-4 py-2 rounded-md text-sm font-medium transition duration-200"
            :class="filterType === 'all' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
          >
            Todos
          </button>
          <button 
            @click="filterType = 'sale'" 
            class="px-4 py-2 rounded-md text-sm font-medium transition duration-200"
            :class="filterType === 'sale' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
          >
            Venta
          </button>
          <button 
            @click="filterType = 'rental'" 
            class="px-4 py-2 rounded-md text-sm font-medium transition duration-200"
            :class="filterType === 'rental' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
          >
            Alquiler
          </button>
        </div>
        
        <!-- Lista de anuncios disponibles -->
        <div class="space-y-3 max-h-96 overflow-y-auto pr-2">
          <div 
            v-for="listing in filteredListings" 
            :key="listing.id" 
            class="border rounded-md p-4 hover:bg-green-50 cursor-pointer transition-colors duration-200"
            @click="selectListing(listing)"
          >
            <div class="flex justify-between items-start">
              <div>
                <h4 class="font-medium text-gray-800">{{ listing.title || (listing.tractor ? `${listing.tractor.brand} ${listing.tractor.model}` : `Anuncio #${listing.id}`) }}</h4>
                <p class="text-sm text-gray-600">
                  {{ listing.tractor ? `${listing.tractor.brand} ${listing.tractor.model} - ${listing.tractor.year || 'Sin año'}` : 'Tractor no especificado' }}
                </p>
                <p class="text-sm text-gray-600">
                  Vendedor: {{ listing.seller ? `${listing.seller.first_name} ${listing.seller.last_name}` : 'Desconocido' }}
                </p>
              </div>
              <div class="text-right">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                  :class="{
                    'bg-purple-100 text-purple-800': listing.type === 'sale',
                    'bg-blue-100 text-blue-800': listing.type === 'rental'
                  }">
                  {{ listing.type === 'sale' ? 'Venta' : 'Alquiler' }}
                </span>
                <p class="font-bold text-green-600 mt-1">{{ formatCurrency(listing.price) }}</p>
                <p v-if="listing.type === 'rental'" class="text-xs text-gray-500">por día</p>
              </div>
            </div>
          </div>
          
          <div v-if="filteredListings.length === 0" class="text-center text-gray-500 py-4">
            No hay anuncios disponibles {{ filterType !== 'all' ? `de tipo ${filterType === 'sale' ? 'venta' : 'alquiler'}` : '' }}
          </div>
        </div>
      </div>
      
      <form v-else @submit.prevent="submitForm" class="p-6 space-y-5">
        <!-- Detalle del anuncio seleccionado -->
        <div class="bg-gray-50 p-4 rounded-lg">
          <div class="flex justify-between items-start">
            <div>
              <h4 class="font-medium text-gray-800">{{ selectedListing.title || (selectedListing.tractor ? `${selectedListing.tractor.brand} ${selectedListing.tractor.model}` : `Anuncio #${selectedListing.id}`) }}</h4>
              <p class="text-sm text-gray-600">
                {{ selectedListing.tractor ? `${selectedListing.tractor.brand} ${selectedListing.tractor.model} - ${selectedListing.tractor.year || 'Sin año'}` : 'Tractor no especificado' }}
              </p>
            </div>
            <div class="text-right">
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                :class="{
                  'bg-purple-100 text-purple-800': selectedListing.type === 'sale',
                  'bg-blue-100 text-blue-800': selectedListing.type === 'rental'
                }">
                {{ selectedListing.type === 'sale' ? 'Venta' : 'Alquiler' }}
              </span>
              <p class="font-bold text-green-600 mt-1">{{ formatCurrency(selectedListing.price) }}</p>
              <p v-if="selectedListing.type === 'rental'" class="text-xs text-gray-500">por día</p>
            </div>
          </div>
          <button 
            type="button" 
            @click="selectedListing = null" 
            class="mt-3 text-sm text-green-600 hover:text-green-700 flex items-center"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Cambiar anuncio
          </button>
        </div>
        
        <!-- Precio ofrecido -->
        <div>
          <label for="offered_price" class="block text-sm font-medium text-gray-700 mb-1">
            Precio ofrecido (€)
            <span class="text-gray-500 text-xs">
              {{ selectedListing.type === 'rental' ? '(por día)' : '' }}
            </span>
          </label>
          <input 
            type="number" 
            id="offered_price" 
            v-model="form.offered_price" 
            min="0" 
            step="0.01" 
            class="w-full p-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
            :class="{ 'border-red-500': form.errors.offered_price }"
            :placeholder="'Precio anunciado: ' + formatCurrency(selectedListing.price)"
          >
          <p v-if="form.errors.offered_price" class="mt-1 text-sm text-red-600">{{ form.errors.offered_price }}</p>
        </div>
        
        <!-- Fechas solicitadas (solo para alquiler) -->
        <div v-if="selectedListing.type === 'rental'" class="grid grid-cols-2 gap-4">
          <div>
            <label for="requested_start_date" class="block text-sm font-medium text-gray-700 mb-1">Fecha inicio</label>
            <input 
              type="date" 
              id="requested_start_date" 
              v-model="form.requested_start_date" 
              class="w-full p-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
              :class="{ 'border-red-500': form.errors.requested_start_date }"
              :min="selectedListing.start_date"
              :max="selectedListing.end_date"
            >
            <p v-if="form.errors.requested_start_date" class="mt-1 text-sm text-red-600">{{ form.errors.requested_start_date }}</p>
          </div>
          <div>
            <label for="requested_end_date" class="block text-sm font-medium text-gray-700 mb-1">Fecha fin</label>
            <input 
              type="date" 
              id="requested_end_date" 
              v-model="form.requested_end_date" 
              class="w-full p-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
              :class="{ 'border-red-500': form.errors.requested_end_date }"
              :min="form.requested_start_date || selectedListing.start_date"
              :max="selectedListing.end_date"
            >
            <p v-if="form.errors.requested_end_date" class="mt-1 text-sm text-red-600">{{ form.errors.requested_end_date }}</p>
          </div>
        </div>
        
        <!-- Mensaje -->
        <div>
          <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Mensaje para el vendedor (opcional)</label>
          <textarea 
            id="message" 
            v-model="form.message" 
            rows="4" 
            class="w-full p-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
            :class="{ 'border-red-500': form.errors.message }"
            placeholder="Escribe un mensaje para el vendedor..."
          ></textarea>
          <p v-if="form.errors.message" class="mt-1 text-sm text-red-600">{{ form.errors.message }}</p>
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
            {{ form.processing ? 'Enviando...' : 'Enviar solicitud' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  availableListings: {
    type: Array,
    required: true
  }
});

defineEmits(['close', 'created']);

// Estado para el filtro y la selección
const filterType = ref('all');
const selectedListing = ref(null);

// Filtrar anuncios según el tipo seleccionado
const filteredListings = computed(() => {
  if (filterType.value === 'all') {
    return props.availableListings;
  }
  return props.availableListings.filter(listing => listing.type === filterType.value);
});

// Seleccionar un anuncio
const selectListing = (listing) => {
  selectedListing.value = listing;
  // Establecer el precio ofrecido por defecto igual al precio del anuncio
  form.offered_price = listing.price;
  
  // Si es alquiler, establecer las fechas por defecto
  if (listing.type === 'rental') {
    form.requested_start_date = listing.start_date;
    form.requested_end_date = listing.end_date;
  }
};

// Inicializar formulario
const form = useForm({
  listing_id: '',
  offered_price: '',
  requested_start_date: '',
  requested_end_date: '',
  message: '',
});

// Formatear moneda
const formatCurrency = (value) => {
  if (!value) return '---';
  return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(value);
};

// Enviar formulario
const submitForm = () => {
  // Establecer el ID del anuncio seleccionado
  form.listing_id = selectedListing.value.id;
  
  // Validación adicional
  if (selectedListing.value.type === 'rental' && (!form.requested_start_date || !form.requested_end_date)) {
    if (!form.requested_start_date) form.errors.requested_start_date = 'La fecha de inicio es obligatoria para alquileres';
    if (!form.requested_end_date) form.errors.requested_end_date = 'La fecha de fin es obligatoria para alquileres';
    return;
  }
  
  form.post(route('user.requests.store'), {
    onSuccess: () => {
      // Emitir evento para informar que se ha creado la solicitud
      form.reset();
      form.clearErrors();
      // Notificar al componente padre que se ha creado una solicitud
      emit('created');
    },
    onError: (errors) => {
      console.error('Error al crear la solicitud:', errors);
    }
  });
};
</script>