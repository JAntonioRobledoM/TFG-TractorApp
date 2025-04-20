<template>
    <AdminLayout>
      <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6 text-green-800">Añadir Nuevo Tractor</h1>
  
        <form @submit.prevent="submit" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
  
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2" for="field_2">
                Campo Adicional (Opcional)
              </label>
              <input 
                id="field_2" 
                v-model="form.field_2" 
                type="number" 
                placeholder="Campo adicional" 
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
              />
              <div v-if="form.errors.field_2" class="text-red-500 text-xs mt-1">
                {{ form.errors.field_2 }}
              </div>
            </div>
          </div>
  
          <!-- Sección de Asignación de Usuarios -->
          <div class="mt-6">
            <h3 class="text-lg font-semibold text-green-800 mb-4">Asignar Usuarios</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">
                  Usuarios Disponibles
                </label>
                <select 
                  v-model="selectedUsers" 
                  multiple 
                  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                >
                  <option 
                    v-for="user in availableUsers" 
                    :key="user.id" 
                    :value="user.id"
                  >
                    {{ user.first_name }} {{ user.last_name }} ({{ user.email }})
                  </option>
                </select>
              </div>
              <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">
                  Usuarios Seleccionados
                </label>
                <div v-if="selectedUsers.length" class="bg-green-50 rounded p-2">
                  <div 
                    v-for="userId in selectedUsers" 
                    :key="userId" 
                    class="flex justify-between items-center mb-1 last:mb-0"
                  >
                    <span class="text-sm text-gray-800 font-medium">
                      {{ getUserName(userId) }}
                    </span>
                    <button 
                      type="button" 
                      @click="removeUser(userId)" 
                      class="text-red-500 hover:text-red-700"
                    >
                      Eliminar
                    </button>
                  </div>
                </div>
                <p v-else class="text-gray-500 text-sm">Ningún usuario seleccionado</p>
              </div>
            </div>
          </div>
  
          <div class="flex items-center justify-between mt-6">
            <button 
              type="submit" 
              class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition"
            >
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
    model: '',
    year: null,
    description: '',
    field_2: null,
    users: []
  });
  
  const selectedUsers = ref([]);
  
  const getUserName = (userId) => {
    const user = props.availableUsers.find(u => u.id === userId);
    if (!user) return '';
    return `${user.first_name} ${user.last_name} (${user.email})`;
  };
  
  const removeUser = (userId) => {
    selectedUsers.value = selectedUsers.value.filter(id => id !== userId);
  };
  
  const submit = () => {
    form.users = selectedUsers.value;
    form.post(route('tractors.store'), {
      onFinish: () => {
        form.reset();
        selectedUsers.value = [];
      }
    });
  };
  </script>