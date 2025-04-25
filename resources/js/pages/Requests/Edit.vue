<template>
  <AdminLayout>
    <template #header>
      <h2 class="font-semibold text-2xl text-white leading-tight">
        Editar Solicitud
      </h2>
    </template>

    <div class="container mx-auto p-6">
      <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
          <h3 class="text-lg leading-6 font-medium text-gray-900">
            Editar Solicitud #{{ request.id }}
          </h3>
          <p class="mt-1 max-w-2xl text-sm text-gray-500">
            {{ request.type === 'sale' ? 'Solicitud de Compra' : 'Solicitud de Alquiler' }}
          </p>
        </div>

        <form @submit.prevent="submit" class="border-t border-gray-200">
          <div class="px-4 py-5 sm:p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Listing Selection -->
              <div class="md:col-span-2">
                <label for="listing_id" class="block text-sm font-medium text-gray-700 mb-2">
                  Anuncio
                </label>
                <select 
                  id="listing_id" 
                  v-model="form.listing_id"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                  :class="{ 'border-red-500': form.errors.listing_id }"
                >
                  <option value="" disabled class="text-gray-900">Selecciona un anuncio</option>
                  <option 
                    v-for="listing in listings" 
                    :key="listing.id" 
                    :value="listing.id"
                    class="text-gray-900"
                  >
                    {{ listing.tractor ? listing.tractor.model : 'Tractor #' + listing.tractor_id }} 
                    - {{ listing.type === 'sale' ? 'Venta' : 'Alquiler' }} 
                    - {{ formatCurrency(listing.price) }}
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
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                  :class="{ 'border-red-500': form.errors.type }"
                >
                  <option value="" disabled class="text-gray-900">Selecciona el tipo</option>
                  <option value="sale" class="text-gray-900">Compra</option>
                  <option value="rental" class="text-gray-900">Alquiler</option>
                </select>
                <p v-if="form.errors.type" class="mt-2 text-sm text-red-500">
                  {{ form.errors.type }}
                </p>
              </div>

              <!-- Request Status -->
              <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                  Estado de la Solicitud
                </label>
                <select 
                  id="status" 
                  v-model="form.status"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                  :class="{ 'border-red-500': form.errors.status }"
                >
                  <option value="" disabled class="text-gray-900">Selecciona el estado</option>
                  <option value="pending" class="text-gray-900">Pendiente</option>
                  <option value="accepted" class="text-gray-900">Aceptada</option>
                  <option value="rejected" class="text-gray-900">Rechazada</option>
                  <option value="completed" class="text-gray-900">Completada</option>
                </select>
                <p v-if="form.errors.status" class="mt-2 text-sm text-red-500">
                  {{ form.errors.status }}
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
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
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
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
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
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
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
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                  placeholder="Escribe un mensaje adicional"
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
                {{ form.processing ? 'Procesando...' : 'Actualizar Solicitud' }}
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
import { watch, onMounted } from 'vue';

const props = defineProps({
  request: {
    type: Object,
    required: true
  },
  listings: {
    type: Array,
    default: () => []
  }
});

const form = useForm({
  listing_id: props.request.listing_id,
  type: props.request.type,
  status: props.request.status,
  offered_price: props.request.offered_price,
  requested_start_date: props.request.requested_start_date || '',
  requested_end_date: props.request.requested_end_date || '',
  message: props.request.message || '',
  requester_id: props.request.requester_id // Aseguramos que se envíe este campo también
});

// Para depurar
onMounted(() => {
  console.log('Solicitud a editar:', props.request);
});

// Añadir watch para actualizar fechas cuando cambia el tipo
watch(() => form.type, (newType) => {
  if (newType !== 'rental') {
    form.requested_start_date = '';
    form.requested_end_date = '';
  }
});

const submit = () => {
  console.log('Enviando formulario de actualización', form);
  
  form.put(route('requests.update', props.request.id), {
    preserveScroll: true,
    onSuccess: () => {
      console.log('Solicitud actualizada con éxito');
    },
    onError: (errors) => {
      console.error('Error al actualizar la solicitud', errors);
    }
  });
};

const formatCurrency = (value) => {
  if (!value) return '---';
  return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(value);
};
</script>