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
            <input 
              id="price" 
              v-model="form.price" 
              type="number" 
              placeholder="Precio del anuncio" 
              step="0.01"
              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            />
            <div v-if="form.errors.price" class="text-red-500 text-xs mt-1">
              {{ form.errors.price }}
            </div>
          </div>

          <div v-if="form.type === 'rental'">
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

          <div v-if="form.type === 'rental'">
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

          <div class="col-span-1 md:col-span-2">
            <label class="inline-flex items-center">
              <input 
                type="checkbox" 
                v-model="form.is_active" 
                class="form-checkbox h-5 w-5 text-green-600"
              >
              <span class="ml-2 text-gray-700">Anuncio activo</span>
            </label>
          </div>
        </div>

        <div class="flex items-center justify-between mt-6">
          <button 
            type="submit" 
            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition"
          >
            Actualizar Anuncio
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
tractors: Array
});

const form = useForm({
tractor_id: props.listing.tractor_id || '',
type: props.listing.type || '',
price: props.listing.price || null,
start_date: props.listing.start_date || null,
end_date: props.listing.end_date || null,
description: props.listing.description || '',
is_active: props.listing.is_active || false,
seller_id: props.listing.seller_id // Keep original seller
});

const submit = () => {
form.put(route('listings.update', props.listing.id), {
  onFinish: () => form.reset()
});
};
</script>