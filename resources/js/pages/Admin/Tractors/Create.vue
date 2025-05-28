<template>
  <AdminLayout>
    <template #header>
      <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
        Añadir Nuevo Tractor
      </h2>
    </template>
    <div class="container mx-auto p-6">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-green-800">Nuevo Tractor</h1>
        <Link 
          :href="route('tractors.index')" 
          class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition"
        >
          Lista de Tractores
        </Link>
      </div>

      <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="p-6">
          <form @submit.prevent="submit" enctype="multipart/form-data">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
              <!-- Información del tractor -->
              <div class="lg:col-span-2 space-y-6">
                <!-- Información general -->
                <div>
                  <h3 class="text-lg font-semibold text-green-800 mb-4 border-b border-green-200 pb-2">
                    Información General
                  </h3>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Marca -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700" for="brand">
                        Marca
                      </label>
                      <input 
                        id="brand" 
                        v-model="form.brand" 
                        type="text" 
                        placeholder="Ej: John Deere, New Holland, Case IH..." 
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                      />
                      <div v-if="form.errors.brand" class="text-red-500 text-sm mt-1">
                        {{ form.errors.brand }}
                      </div>
                    </div>

                    <!-- Modelo -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700" for="model">
                        Modelo
                      </label>
                      <input 
                        id="model" 
                        v-model="form.model" 
                        type="text" 
                        placeholder="Ej: 6120M, T7.270, MF7720..." 
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                      />
                      <div v-if="form.errors.model" class="text-red-500 text-sm mt-1">
                        {{ form.errors.model }}
                      </div>
                    </div>

                    <!-- Año -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700" for="year">
                        Año
                      </label>
                      <input 
                        id="year" 
                        v-model="form.year" 
                        type="number" 
                        :max="new Date().getFullYear()"
                        min="1900"
                        placeholder="Ej: 2020" 
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                      />
                      <div v-if="form.errors.year" class="text-red-500 text-sm mt-1">
                        {{ form.errors.year }}
                      </div>
                    </div>

                    <!-- Matrícula -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700" for="license_plate">
                        Matrícula
                      </label>
                      <input 
                        id="license_plate" 
                        v-model="form.license_plate" 
                        type="text" 
                        placeholder="Ej: 1234-ABC" 
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900 font-mono"
                      />
                      <div v-if="form.errors.license_plate" class="text-red-500 text-sm mt-1">
                        {{ form.errors.license_plate }}
                      </div>
                    </div>

                    <!-- Color -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700" for="color">
                        Color
                      </label>
                      <input 
                        id="color" 
                        v-model="form.color" 
                        type="text" 
                        placeholder="Ej: Verde, Rojo, Azul..." 
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                      />
                      <div v-if="form.errors.color" class="text-red-500 text-sm mt-1">
                        {{ form.errors.color }}
                      </div>
                    </div>

                    <!-- Disponibilidad -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700">
                        Disponibilidad
                      </label>
                      <div class="mt-2">
                        <label class="flex items-center">
                          <input 
                            v-model="form.is_available" 
                            type="checkbox" 
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-2 text-sm text-gray-600">Tractor disponible</span>
                        </label>
                      </div>
                      <div v-if="form.errors.is_available" class="text-red-500 text-sm mt-1">
                        {{ form.errors.is_available }}
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Especificaciones técnicas -->
                <div>
                  <h3 class="text-lg font-semibold text-green-800 mb-4 border-b border-green-200 pb-2">
                    Especificaciones Técnicas
                  </h3>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Potencia -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700" for="horsepower">
                        Potencia (CV)
                      </label>
                      <input 
                        id="horsepower" 
                        v-model="form.horsepower" 
                        type="number" 
                        min="0"
                        placeholder="Ej: 120" 
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                      />
                      <div v-if="form.errors.horsepower" class="text-red-500 text-sm mt-1">
                        {{ form.errors.horsepower }}
                      </div>
                    </div>

                    <!-- Horas de trabajo -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700" for="working_hours">
                        Horas de trabajo
                      </label>
                      <input 
                        id="working_hours" 
                        v-model="form.working_hours" 
                        type="number" 
                        step="0.1"
                        min="0"
                        placeholder="Ej: 1250.5" 
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                      />
                      <div v-if="form.errors.working_hours" class="text-red-500 text-sm mt-1">
                        {{ form.errors.working_hours }}
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Descripción -->
                <div>
                  <label class="block text-sm font-medium text-gray-700" for="description">
                    Descripción
                  </label>
                  <textarea 
                    id="description" 
                    v-model="form.description" 
                    placeholder="Describe las características, estado y funcionalidades del tractor..." 
                    rows="4"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                  ></textarea>
                  <div v-if="form.errors.description" class="text-red-500 text-sm mt-1">
                    {{ form.errors.description }}
                  </div>
                </div>

                <!-- Asignación de propietarios -->
                <div>
                  <h3 class="text-lg font-semibold text-green-800 mb-4 border-b border-green-200 pb-2">
                    Propietarios
                  </h3>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Usuarios disponibles -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">
                        Usuarios Disponibles
                      </label>
                      <select 
                        v-model="selectedUsers" 
                        multiple 
                        size="6"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                      >
                        <option 
                          v-for="user in availableUsers" 
                          :key="user.id" 
                          :value="user.id"
                        >
                          {{ user.first_name }} {{ user.last_name }} (@{{ user.username }})
                        </option>
                      </select>
                      <p class="text-xs text-gray-500 mt-1">Mantén Ctrl presionado para seleccionar múltiples</p>
                    </div>

                    <!-- Propietarios seleccionados -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">
                        Propietarios Seleccionados ({{ selectedUsers.length }})
                      </label>
                      <div class="bg-gray-50 rounded-md p-3 min-h-[150px] max-h-[200px] overflow-y-auto border border-gray-300">
                        <div v-if="selectedUsers.length === 0" class="text-center text-gray-500 text-sm py-8">
                          No hay propietarios seleccionados
                        </div>
                        <div v-else class="space-y-2">
                          <div 
                            v-for="userId in selectedUsers" 
                            :key="userId" 
                            class="flex justify-between items-center bg-white p-2 rounded shadow-sm"
                          >
                            <span class="text-sm font-medium text-gray-900">
                              {{ getUserName(userId) }}
                            </span>
                            <button 
                              type="button" 
                              @click="removeUser(userId)" 
                              class="text-red-600 hover:text-red-800 hover:bg-red-50 p-1 rounded"
                            >
                              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                              </svg>
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Gestión de imagen -->
              <div class="lg:col-span-1">
                <div class="bg-gray-50 rounded-lg p-6">
                  <h3 class="text-lg font-medium text-gray-900 mb-4">Imagen del Tractor</h3>
                  
                  <!-- Vista previa de imagen -->
                  <div v-if="imagePreview" class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">Vista previa:</p>
                    <div class="w-full aspect-square rounded-lg overflow-hidden bg-gray-100">
                      <img 
                        :src="imagePreview" 
                        alt="Vista previa"
                        class="w-full h-full object-cover"
                      />
                    </div>
                    <button 
                      type="button" 
                      @click="clearImagePreview"
                      class="mt-2 text-sm text-red-600 hover:text-red-800"
                    >
                      Eliminar imagen
                    </button>
                  </div>

                  <!-- Placeholder cuando no hay imagen -->
                  <div v-if="!imagePreview" class="mb-4">
                    <div class="w-full aspect-square rounded-lg bg-gray-100 flex items-center justify-center border-2 border-dashed border-gray-300">
                      <div class="text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">Sin imagen</p>
                      </div>
                    </div>
                  </div>

                  <!-- Input de imagen -->
                  <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                      Subir imagen
                    </label>
                    <input 
                      type="file" 
                      id="image" 
                      @change="handleImageChange"
                      accept="image/jpeg,image/png,image/jpg,image/gif"
                      class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100"
                    />
                    <p class="text-xs text-gray-500 mt-1">JPG, PNG, GIF hasta 2MB</p>
                  </div>
                  
                  <div v-if="form.errors.image" class="text-red-500 text-sm mt-1">{{ form.errors.image }}</div>
                  
                  <!-- Consejos para la imagen -->
                  <div class="mt-4 bg-blue-50 border border-blue-200 rounded-md p-3">
                    <h4 class="text-sm font-medium text-blue-800 mb-1">💡 Consejos para la imagen:</h4>
                    <ul class="text-xs text-blue-700 space-y-1">
                      <li>• Foto desde varios ángulos</li>
                      <li>• Buena iluminación natural</li>
                      <li>• Muestra detalles importantes</li>
                      <li>• Formato cuadrado es ideal</li>
                      <li>• Evita reflejos y sombras</li>
                    </ul>
                  </div>

                  <!-- Estadísticas rápidas -->
                  <div class="mt-4 bg-green-50 border border-green-200 rounded-md p-3">
                    <h4 class="text-sm font-medium text-green-800 mb-2">📊 Resumen:</h4>
                    <div class="space-y-1 text-xs text-green-700">
                      <div class="flex justify-between">
                        <span>Marca:</span>
                        <span>{{ form.brand || 'Sin especificar' }}</span>
                      </div>
                      <div class="flex justify-between">
                        <span>Modelo:</span>
                        <span>{{ form.model || 'Sin especificar' }}</span>
                      </div>
                      <div class="flex justify-between">
                        <span>Año:</span>
                        <span>{{ form.year || 'Sin especificar' }}</span>
                      </div>
                      <div class="flex justify-between">
                        <span>Potencia:</span>
                        <span>{{ form.horsepower ? `${form.horsepower} CV` : 'Sin especificar' }}</span>
                      </div>
                      <div class="flex justify-between">
                        <span>Propietarios:</span>
                        <span>{{ selectedUsers.length }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="mt-6 flex space-x-2">
              <button 
                type="submit" 
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition disabled:opacity-50"
                :disabled="form.processing"
              >
                {{ form.processing ? 'Creando...' : 'Crear Tractor' }}
              </button>
              <Link 
                :href="route('tractors.index')" 
                class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition"
              >
                Cancelar
              </Link>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref } from 'vue';

const props = defineProps({
  availableUsers: Array
});

const imagePreview = ref(null);
const selectedUsers = ref([]);

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
  image: null,
  users: []
});

const getUserName = (userId) => {
  const user = props.availableUsers.find(u => u.id === userId);
  if (!user) return '';
  return `${user.first_name} ${user.last_name}`;
};

const removeUser = (userId) => {
  selectedUsers.value = selectedUsers.value.filter(id => id !== userId);
};

const handleImageChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.image = file;
    
    // Crear vista previa
    const reader = new FileReader();
    reader.onload = (e) => {
      imagePreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
  }
};

const clearImagePreview = () => {
  imagePreview.value = null;
  form.image = null;
  // Reset input file
  const input = document.getElementById('image');
  if (input) input.value = '';
};

const submit = () => {
  // Asignar usuarios seleccionados
  form.users = selectedUsers.value;
  
  form.post(route('tractors.store'), {
    forceFormData: true,
    onFinish: () => {
      // Solo resetear si no hay errores
      if (!Object.keys(form.errors).length) {
        form.reset();
        selectedUsers.value = [];
        clearImagePreview();
      }
    }
  });
};
</script>

<style src="../../../../css/Admin/Tractors/tractor_create_style.css" scoped></style>