<template>
  <AdminLayout>
    <div class="container mx-auto p-6">
      <h1 class="text-2xl font-bold mb-6 text-green-800">Editar Anuncio</h1>

      <form @submit.prevent="submit" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="tractor_id">
              Tractor
            </label>
            <select 
              id="tractor_id" 
              v-model="form.tractor_id" 
              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            >
              <option value="" disabled>Selecciona un tractor</option>
              <option 
                v-for="tractor in tractors" 
                :key="tractor.id" 
                :value="tractor.id"
              >
                {{ tractor.model || 'Tractor #' + tractor.id }} {{ tractor.year ? '- Año: ' + tractor.year : '' }}
              </option>
            </select>
            <div v-if="form.errors.tractor_id" class="text-red-500 text-xs mt-1">
              {{ form.errors.tractor_id }}
            </div>
          </div>
          
          <!-- Selector de vendedor (agregado según el controlador) -->
          <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="seller_id">
              Vendedor
            </label>
            <select 
              id="seller_id" 
              v-model="form.seller_id" 
              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            >
              <option value="" disabled>Selecciona un vendedor</option>
              <option v-for="user in users" :key="user.id" :value="user.id">
                {{ user.first_name }} {{ user.last_name }} ({{ user.username }})
              </option>
            </select>
            <div v-if="form.errors.seller_id" class="text-red-500 text-xs mt-1">
              {{ form.errors.seller_id }}
            </div>
          </div>

          <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="type">
              Tipo de Anuncio
            </label>
            <select 
              id="type" 
              v-model="form.type" 
              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            >
              <option value="" disabled>Selecciona el tipo</option>
              <option value="sale">Venta</option>
              <option value="rental">Alquiler</option>
            </select>
            <div v-if="form.errors.type" class="text-red-500 text-xs mt-1">
              {{ form.errors.type }}
            </div>
          </div>

          <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="price">
              Precio (€)
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="text-gray-500 sm:text-sm">€</span>
              </div>
              <input 
                id="price" 
                v-model="form.price" 
                type="number" 
                placeholder="0.00" 
                step="0.01"
                min="0"
                class="shadow appearance-none border rounded w-full py-2 px-3 pl-7 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
              />
            </div>
            <div v-if="form.errors.price" class="text-red-500 text-xs mt-1">
              {{ form.errors.price }}
            </div>
          </div>

          <div class="flex items-center">
            <input 
              type="checkbox" 
              id="is_active"
              v-model="form.is_active" 
              class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
            />
            <label for="is_active" class="ml-2 block text-sm text-gray-700">
              Anuncio activo
            </label>
            <div v-if="form.errors.is_active" class="text-red-500 text-xs mt-1">
              {{ form.errors.is_active }}
            </div>
          </div>

          <div v-if="form.type === 'rental'" class="col-span-1 md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2" for="start_date">
                Fecha de inicio
              </label>
              <input 
                id="start_date" 
                v-model="form.start_date" 
                type="date" 
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
              />
              <div v-if="form.errors.start_date" class="text-red-500 text-xs mt-1">
                {{ form.errors.start_date }}
              </div>
            </div>

            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2" for="end_date">
                Fecha de fin
              </label>
              <input 
                id="end_date" 
                v-model="form.end_date" 
                type="date" 
                :min="form.start_date"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
              />
              <div v-if="form.errors.end_date" class="text-red-500 text-xs mt-1">
                {{ form.errors.end_date }}
              </div>
            </div>
          </div>

          <div class="col-span-1 md:col-span-2">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
              Descripción
            </label>
            <textarea 
              id="description" 
              v-model="form.description" 
              placeholder="Descripción detallada del anuncio" 
              rows="4"
              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            ></textarea>
            <div v-if="form.errors.description" class="text-red-500 text-xs mt-1">
              {{ form.errors.description }}
            </div>
          </div>
        </div>

        <div class="flex items-center justify-between mt-6">
          <button 
            type="submit" 
            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition"
            :disabled="form.processing"
          >
            {{ form.processing ? 'Actualizando...' : 'Actualizar Anuncio' }}
          </button>
          <Link 
            :href="route('listings.show', listing.id)" 
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
  listing: Object,
  tractors: Array,
  users: Array, // Agregado: Recibir la lista de usuarios del controlador
});

const form = useForm({
  tractor_id: props.listing.tractor_id || '',
  seller_id: props.listing.seller_id || '', // Mantener el vendedor original
  type: props.listing.type || '',
  price: props.listing.price || null,
  start_date: props.listing.start_date || null,
  end_date: props.listing.end_date || null,
  description: props.listing.description || '',
  is_active: props.listing.is_active !== undefined ? props.listing.is_active : false,
});

const submit = () => {
  form.put(route('listings.update', props.listing.id), {
    onSuccess: () => {
      // Opcional: Mostrar mensaje de éxito
    },
  });
};
</script>