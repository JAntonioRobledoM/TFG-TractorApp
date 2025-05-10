<template>
  <AdminLayout>
    <template #header>
      <h2 class="font-semibold text-2xl text-white leading-tight">
        Añadir Nuevo Tractor
      </h2>
    </template>
    <div class="container mx-auto p-6">
      <form @submit.prevent="submit" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Información general del tractor -->
          <div class="col-span-2 mb-4">
            <h3 class="text-lg font-semibold text-green-800 mb-2 border-b border-green-200 pb-2">
              Información General
            </h3>
          </div>

          <!-- Marca -->
          <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="brand">
              Marca
            </label>
            <input 
              id="brand" 
              v-model="form.brand" 
              type="text" 
              placeholder="Marca del tractor" 
              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            />
            <div v-if="form.errors.brand" class="text-red-500 text-xs mt-1">
              {{ form.errors.brand }}
            </div>
          </div>

          <!-- Modelo -->
          <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="model">
              Modelo
            </label>
            <input 
              id="model" 
              v-model="form.model" 
              type="text" 
              placeholder="Modelo del tractor" 
              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            />
            <div v-if="form.errors.model" class="text-red-500 text-xs mt-1">
              {{ form.errors.model }}
            </div>
          </div>

          <!-- Año -->
          <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="year">
              Año
            </label>
            <input 
              id="year" 
              v-model="form.year" 
              type="number" 
              placeholder="Año de fabricación" 
              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            />
            <div v-if="form.errors.year" class="text-red-500 text-xs mt-1">
              {{ form.errors.year }}
            </div>
          </div>

          <!-- Matrícula -->
          <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="license_plate">
              Matrícula
            </label>
            <input 
              id="license_plate" 
              v-model="form.license_plate" 
              type="text" 
              placeholder="Matrícula del tractor" 
              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            />
            <div v-if="form.errors.license_plate" class="text-red-500 text-xs mt-1">
              {{ form.errors.license_plate }}
            </div>
          </div>

          <!-- Color -->
          <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="color">
              Color
            </label>
            <input 
              id="color" 
              v-model="form.color" 
              type="text" 
              placeholder="Color del tractor" 
              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            />
            <div v-if="form.errors.color" class="text-red-500 text-xs mt-1">
              {{ form.errors.color }}
            </div>
          </div>

          <!-- Potencia -->
          <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="horsepower">
              Potencia (CV)
            </label>
            <input 
              id="horsepower" 
              v-model="form.horsepower" 
              type="number" 
              placeholder="Potencia en CV" 
              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            />
            <div v-if="form.errors.horsepower" class="text-red-500 text-xs mt-1">
              {{ form.errors.horsepower }}
            </div>
          </div>

          <!-- Horas de trabajo -->
          <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="working_hours">
              Horas de trabajo
            </label>
            <input 
              id="working_hours" 
              v-model="form.working_hours" 
              type="number" 
              step="0.01"
              placeholder="Horas de trabajo" 
              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            />
            <div v-if="form.errors.working_hours" class="text-red-500 text-xs mt-1">
              {{ form.errors.working_hours }}
            </div>
          </div>

          <!-- Disponibilidad -->
          <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">
              Disponibilidad
            </label>
            <div class="flex items-center mt-2">
              <input 
                id="is_available" 
                v-model="form.is_available" 
                type="checkbox" 
                class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
              />
              <label for="is_available" class="ml-2 block text-sm text-gray-900">
                Tractor disponible
              </label>
            </div>
            <div v-if="form.errors.is_available" class="text-red-500 text-xs mt-1">
              {{ form.errors.is_available }}
            </div>
          </div>

          <!-- Descripción -->
          <div class="col-span-1 md:col-span-2">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
              Descripción
            </label>
            <textarea 
              id="description" 
              v-model="form.description" 
              placeholder="Descripción detallada del tractor" 
              rows="4"
              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            ></textarea>
            <div v-if="form.errors.description" class="text-red-500 text-xs mt-1">
              {{ form.errors.description }}
            </div>
          </div>
        </div>

        <!-- Sección de Asignación de Usuarios -->
        <div class="mt-6">
          <h3 class="text-lg font-semibold text-green-800 mb-4 border-b border-green-200 pb-2">Asignar Propietarios</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2">
                Usuarios Disponibles
              </label>
              <select 
                v-model="selectedUsers" 
                multiple 
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                size="5"
              >
                <option 
                  v-for="user in availableUsers" 
                  :key="user.id" 
                  :value="user.id"
                >
                  {{ user.first_name }} {{ user.last_name }} ({{ user.email || user.username }})
                </option>
              </select>
              <p class="text-xs text-gray-500 mt-1">Mantén presionada la tecla Ctrl para seleccionar múltiples usuarios</p>
            </div>
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2">
                Usuarios Seleccionados
              </label>
              <div v-if="selectedUsers.length" class="bg-green-50 rounded p-2 min-h-[100px] max-h-[200px] overflow-y-auto">
                <div 
                  v-for="userId in selectedUsers" 
                  :key="userId" 
                  class="flex justify-between items-center mb-1 last:mb-0 bg-white p-2 rounded shadow-sm"
                >
                  <span class="text-sm text-gray-800 font-medium">
                    {{ getUserName(userId) }}
                  </span>
                  <button 
                    type="button" 
                    @click="removeUser(userId)" 
                    class="text-red-500 hover:text-red-700"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>
              </div>
              <p v-else class="text-gray-500 text-sm bg-gray-50 rounded p-4 text-center min-h-[100px] flex items-center justify-center">
                Ningún usuario seleccionado
              </p>
            </div>
          </div>
        </div>

        <div class="flex items-center justify-between mt-6">
          <button 
            type="submit" 
            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition flex items-center"
            :disabled="form.processing"
          >
            <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Crear Tractor
          </button>
          <Link 
            :href="route('tractors.index')" 
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
  availableUsers: Array
});

const form = useForm({
  brand: '',
  model: '',
  year: null,
  description: '',
  license_plate: '',
  color: '',
  horsepower: null,
  working_hours: null,
  is_available: true,
  users: []
});

const selectedUsers = ref([]);

const getUserName = (userId) => {
  const user = props.availableUsers.find(u => u.id === userId);
  if (!user) return '';
  return `${user.first_name} ${user.last_name} (${user.email || user.username})`;
};

const removeUser = (userId) => {
  selectedUsers.value = selectedUsers.value.filter(id => id !== userId);
};

const submit = () => {
  form.users = selectedUsers.value;
  form.post(route('tractors.store'), {
    onFinish: () => {
      // Solo resetear el formulario si la solicitud es exitosa
      if (!Object.keys(form.errors).length) {
        form.reset();
        selectedUsers.value = [];
      }
    }
  });
};
</script>

<style scoped>
select[multiple] {
  height: auto;
}
</style>