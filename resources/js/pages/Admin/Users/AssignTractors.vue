<template>
  <AdminLayout>
    <div class="container mx-auto p-6">
      <h1 class="text-2xl font-bold mb-6 text-green-800">
        Asignar Tractor a {{ user.first_name }} {{ user.last_name }}
      </h1>
      
      <form 
        @submit.prevent="assignTractor" 
        class="bg-white shadow-lg rounded-lg px-8 pt-6 pb-8 mb-4 border-t-4 border-green-600"
      >
        <div class="mb-4">
          <label class="block text-green-900 text-sm font-bold mb-2" for="tractor">
            Seleccionar Tractor
          </label>
          <select 
            v-model="form.tractor_id" 
            class="shadow-md appearance-none border rounded w-full py-2 px-3 
            text-green-900 bg-green-50 border-green-300 
            focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
            required
          >
            <option 
              v-for="tractor in availableTractors" 
              :key="tractor.id" 
              :value="tractor.id"
              class="text-green-900"
            >
              {{ tractor.model || 'Tractor #' + tractor.id }} ({{ tractor.year || 'Año no especificado' }})
            </option>
          </select>
        </div>
  
        <div class="flex items-center justify-between">
          <button 
            type="submit" 
            class="bg-green-600 text-white px-4 py-2 rounded-lg 
            hover:bg-green-700 transition duration-300 
            focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50"
          >
            Asignar Tractor
          </button>
          <Link 
            :href="route('users.show', user.id)" 
            class="text-green-700 hover:text-green-900 hover:underline transition duration-300"
          >
            Cancelar
          </Link>
        </div>
      </form>
  
      <div class="mt-8">
        <h2 class="text-xl font-bold mb-4 text-green-800">Tractores Asignados</h2>
        <div v-if="user.tractors.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <div 
            v-for="tractor in user.tractors" 
            :key="tractor.id" 
            class="bg-green-50 shadow-md rounded-lg p-4 border-l-4 border-yellow-600 hover:shadow-lg transition"
          >
            <div class="flex justify-between items-center">
              <div>
                <h3 class="font-semibold text-green-900">
                  {{ tractor.model || 'Tractor #' + tractor.id }}
                </h3>
                <p class="text-sm text-green-700">
                  Año: {{ tractor.year || 'No especificado' }}
                </p>
              </div>
              <button 
                @click="removeTractor(tractor.id)" 
                class="text-red-600 hover:text-red-800 hover:bg-red-50 p-2 rounded-full transition"
              >
                Eliminar
              </button>
            </div>
          </div>
        </div>
        <p v-else class="text-green-700 text-center">No hay tractores asignados</p>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup lang="ts">
import { useForm, router, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  user: Object,
  availableTractors: Array
});

const form = useForm({
  tractor_id: null
});

const assignTractor = () => {
  form.post(route('users.add-tractor', props.user.id), {
    onSuccess: () => form.reset()
  });
};

const removeTractor = (tractorId) => {
  if (confirm('¿Estás seguro de eliminar este tractor del usuario?')) {
    router.delete(route('users.remove-tractor', { 
      user: props.user.id, 
      tractor: tractorId 
    }));
  }
};
</script>