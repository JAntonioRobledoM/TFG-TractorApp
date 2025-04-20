<template>
    <AdminLayout>
      <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6 text-green-800">Crear Nuevo Anuncio</h1>
  
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
                <option v-for="tractor in tractors" :key="tractor.id" :value="tractor.id">
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
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <span class="text-gray-500 sm:text-sm">€</span>
                </div>
                <input 
                  id="price" 
                  v-model="form.price" 
                  type="number" 
                  step="0.01"
                  min="0"
                  placeholder="0.00" 
                  class="shadow appearance-none border rounded w-full py-2 px-3 pl-7 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                />
              </div>
              <div v-if="form.errors.price" class="text-red-500 text-xs mt-1">
                {{ form.errors.price }}
              </div>
            </div>
  
            <div class="flex items-center">
              <input 
                id="is_active" 
                v-model="form.is_active" 
                type="checkbox" 
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
            >
              Crear Anuncio
            </button>
            <Link 
              :href="route('listings.index')" 
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
  import { ref, onMounted } from 'vue';
  
  const props = defineProps({
    tractors: Array,
  });
  
  // Crear formulario con useForm (igual que en tu ejemplo de tractores)
  const form = useForm({
    tractor_id: '',
    seller_id: '',
    type: '',
    price: null,
    description: '',
    is_active: true,
    start_date: null,
    end_date: null,
  });
  
  // Obtener el ID del usuario actual cuando el componente se monta
  onMounted(() => {
    // Si hay un usuario autenticado en las props de la página
    if (window.Inertia && window.Inertia.page.props.auth && window.Inertia.page.props.auth.user) {
      form.seller_id = window.Inertia.page.props.auth.user.id;
    } else {
      // Valor por defecto si no hay usuario
      form.seller_id = 1;
    }
  });
  
  // Método para enviar el formulario (usando el mismo patrón que tu ejemplo)
  const submit = () => {
    form.post(route('listings.store'), {
      onFinish: () => {
        // Solo resetear el formulario si es necesario
        // form.reset(); 
      },
    });
  };
  </script>