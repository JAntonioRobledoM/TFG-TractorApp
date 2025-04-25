<template>
  <AdminLayout>
    <template #header>
      <h2 class="font-semibold text-2xl text-white leading-tight">
        Nueva Solicitud
      </h2>
    </template>
  
    <div class="container mx-auto p-6">
      <div v-if="formSuccess" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ formSuccess }}
      </div>

      <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
          <h3 class="text-lg leading-6 font-medium text-gray-900">
            Crear Solicitud de {{ listing ? (listing.type === 'sale' ? 'Compra' : 'Alquiler') : 'Anuncio' }}
          </h3>
          <p class="mt-1 max-w-2xl text-sm text-gray-500">
            {{ listing ? `Para ${listing.tractor ? listing.tractor.model : 'Tractor'}` : 'Selecciona un anuncio' }}
          </p>
        </div>
  
        <form @submit.prevent="submit" class="border-t border-gray-200">
          <div class="px-4 py-5 sm:p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Listing Selection -->
              <div v-if="!listing" class="md:col-span-2">
                <label for="listing_id" class="block text-sm font-medium text-gray-700 mb-2">
                  Seleccionar Anuncio
                </label>
                <select 
                  id="listing_id" 
                  v-model="form.listing_id"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 bg-white text-gray-900"
                  :class="{ 'border-red-500': form.errors.listing_id }"
                >
                  <option value="" disabled>Selecciona un anuncio</option>
                  <option 
                    v-for="listingOption in listings" 
                    :key="listingOption.id" 
                    :value="listingOption.id"
                    class="text-gray-900"
                  >
                    {{ listingOption.tractor ? listingOption.tractor.model : 'Tractor #' + listingOption.tractor_id }} 
                    - {{ listingOption.type === 'sale' ? 'Venta' : 'Alquiler' }} 
                    - {{ formatCurrency(listingOption.price) }}
                  </option>
                </select>
                <p v-if="form.errors.listing_id" class="mt-2 text-sm text-red-500">
                  {{ form.errors.listing_id }}
                </p>
              </div>
  
              <!-- Request Type -->
              <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                  Tipo de Solicitud
                </label>
                <select 
                  id="type" 
                  v-model="form.type"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 bg-white text-gray-900"
                  :class="{ 'border-red-500': form.errors.type }"
                  :disabled="!!listing"
                >
                  <option value="" disabled>Selecciona el tipo</option>
                  <option value="sale" class="text-gray-900">Compra</option>
                  <option value="rental" class="text-gray-900">Alquiler</option>
                </select>
                <p v-if="form.errors.type" class="mt-2 text-sm text-red-500">
                  {{ form.errors.type }}
                </p>
              </div>
  
              <!-- Offered Price -->
              <div>
                <label for="offered_price" class="block text-sm font-medium text-gray-700 mb-2">
                  Precio Ofrecido (€)
                </label>
                <input 
                  type="number" 
                  id="offered_price" 
                  v-model="form.offered_price"
                  step="0.01" 
                  min="0"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 bg-white text-gray-900"
                  :class="{ 'border-red-500': form.errors.offered_price }"
                  placeholder="Introduce el precio ofrecido"
                />
                <p v-if="form.errors.offered_price" class="mt-2 text-sm text-red-500">
                  {{ form.errors.offered_price }}
                </p>
              </div>
  
              <!-- Rental Date Range (only for rental type) -->
              <template v-if="form.type === 'rental'">
                <div>
                  <label for="requested_start_date" class="block text-sm font-medium text-gray-700 mb-2">
                    Fecha de Inicio
                  </label>
                  <input 
                    type="date" 
                    id="requested_start_date" 
                    v-model="form.requested_start_date"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 bg-white text-gray-900"
                    :class="{ 'border-red-500': form.errors.requested_start_date }"
                  />
                  <p v-if="form.errors.requested_start_date" class="mt-2 text-sm text-red-500">
                    {{ form.errors.requested_start_date }}
                  </p>
                </div>
  
                <div>
                  <label for="requested_end_date" class="block text-sm font-medium text-gray-700 mb-2">
                    Fecha de Fin
                  </label>
                  <input 
                    type="date" 
                    id="requested_end_date" 
                    v-model="form.requested_end_date"
                    :min="form.requested_start_date"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 bg-white text-gray-900"
                    :class="{ 'border-red-500': form.errors.requested_end_date }"
                  />
                  <p v-if="form.errors.requested_end_date" class="mt-2 text-sm text-red-500">
                    {{ form.errors.requested_end_date }}
                  </p>
                </div>
              </template>
  
              <!-- Message -->
              <div class="md:col-span-2">
                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                  Mensaje (Opcional)
                </label>
                <textarea 
                  id="message" 
                  v-model="form.message"
                  rows="4"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 bg-white text-gray-900"
                  placeholder="Escribe un mensaje para el vendedor"
                ></textarea>
              </div>
            </div>
          </div>
  
          <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
            <div class="flex justify-end space-x-3">
              <Link 
                :href="route('requests.index')" 
                class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:text-gray-500"
              >
                Cancelar
              </Link>
              <button 
                type="submit" 
                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                :disabled="form.processing"
              >
                {{ form.processing ? 'Procesando...' : 'Crear Solicitud' }}
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { watch, onMounted, ref } from 'vue';

const props = defineProps({
  listing: {
    type: Object,
    default: null
  },
  listings: {
    type: Array,
    default: () => []
  }
});

const formSuccess = ref('');

const form = useForm({
  listing_id: props.listing ? props.listing.id : '',
  type: props.listing ? props.listing.type : '',
  offered_price: '',
  requested_start_date: '',
  requested_end_date: '',
  message: '',
});

// Agregar watcher para cuando cambia el listing_id
watch(() => form.listing_id, (newValue) => {
  if (newValue) {
    const selectedListing = props.listings.find(listing => listing.id === parseInt(newValue));
    if (selectedListing) {
      form.type = selectedListing.type;
      
      // Reestablecer fechas si cambia de tipo
      if (form.type !== 'rental') {
        form.requested_start_date = '';
        form.requested_end_date = '';
      }
    }
  }
});

const submit = () => {
  // Validación en el cliente antes de enviar
  if (!form.listing_id) {
    form.errors.listing_id = 'Debe seleccionar un anuncio';
    return;
  }
  
  if (!form.type) {
    form.errors.type = 'Debe seleccionar un tipo de solicitud';
    return;
  }
  
  if (!form.offered_price || form.offered_price <= 0) {
    form.errors.offered_price = 'Debe introducir un precio válido';
    return;
  }
  
  if (form.type === 'rental') {
    if (!form.requested_start_date) {
      form.errors.requested_start_date = 'Debe seleccionar una fecha de inicio';
      return;
    }
    
    if (!form.requested_end_date) {
      form.errors.requested_end_date = 'Debe seleccionar una fecha de fin';
      return;
    }
  }
  
  // Para depuración
  console.log('Enviando formulario', form);
  
  form.post(route('requests.store'), {
    preserveScroll: true,
    onSuccess: () => {
      console.log('Solicitud creada con éxito');
      formSuccess.value = 'Solicitud creada correctamente';
      form.reset();
      
      // Opcional: redirigir después de un tiempo
      setTimeout(() => {
        window.location.href = route('requests.index');
      }, 2000);
    },
    onError: (errors) => {
      console.error('Error al crear la solicitud', errors);
      
      // Asignar errores del servidor a variables locales para facilitar la depuración
      Object.keys(errors).forEach(key => {
        console.error(`Error en ${key}: ${errors[key]}`);
      });
    }
  });
};

const formatCurrency = (value) => {
  if (!value) return '---';
  return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(value);
};

// Verificar si hay errores en la consola
onMounted(() => {
  console.log('Componente montado', {
    listing: props.listing,
    listings: props.listings,
    formData: form
  });
  
  // Inicializar fechas para alquiler si es necesario
  if (form.type === 'rental' && !form.requested_start_date) {
    const today = new Date();
    const tomorrow = new Date();
    tomorrow.setDate(today.getDate() + 1);
    
    form.requested_start_date = today.toISOString().split('T')[0];
    form.requested_end_date = tomorrow.toISOString().split('T')[0];
  }
});
</script>