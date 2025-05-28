<template>
    <AdminLayout>
      <template #header>
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
          Añadir Nuevo Apero
        </h2>
      </template>
      <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-2xl font-bold text-green-800">Nuevo Apero</h1>
          <Link 
            :href="route('aperos.index')" 
            class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition"
          >
            Volver a la Lista
          </Link>
        </div>
  
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
          <div class="p-6">
            <form @submit.prevent="submit" enctype="multipart/form-data">
              <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Información básica -->
                <div class="lg:col-span-2 space-y-6">
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                      <label for="name" class="block text-sm font-medium text-gray-700">Nombre *</label>
                      <input 
                        type="text" 
                        id="name" 
                        v-model="form.name" 
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                        placeholder="Ej: Arado reversible John Deere"
                        required
                      />
                      <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</div>
                    </div>
      
                    <div>
                      <label for="type" class="block text-sm font-medium text-gray-700">Tipo</label>
                      <input 
                        type="text" 
                        id="type" 
                        v-model="form.type" 
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                        placeholder="Ej: Arado, Cultivador, Sembradora..."
                      />
                      <div v-if="form.errors.type" class="text-red-500 text-sm mt-1">{{ form.errors.type }}</div>
                    </div>
      
                    <div>
                      <label for="brand" class="block text-sm font-medium text-gray-700">Marca</label>
                      <input 
                        type="text" 
                        id="brand" 
                        v-model="form.brand" 
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                        placeholder="Ej: John Deere, New Holland, Case IH..."
                      />
                      <div v-if="form.errors.brand" class="text-red-500 text-sm mt-1">{{ form.errors.brand }}</div>
                    </div>
      
                    <div>
                      <label for="model" class="block text-sm font-medium text-gray-700">Modelo</label>
                      <input 
                        type="text" 
                        id="model" 
                        v-model="form.model" 
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                        placeholder="Ej: 2100-5, RX420..."
                      />
                      <div v-if="form.errors.model" class="text-red-500 text-sm mt-1">{{ form.errors.model }}</div>
                    </div>
      
                    <div>
                      <label for="year" class="block text-sm font-medium text-gray-700">Año</label>
                      <input 
                        type="number" 
                        id="year" 
                        v-model="form.year" 
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                        min="1900"
                        :max="new Date().getFullYear()"
                        placeholder="Ej: 2020"
                      />
                      <div v-if="form.errors.year" class="text-red-500 text-sm mt-1">{{ form.errors.year }}</div>
                    </div>
      
                    <div>
                      <label for="is_available" class="block text-sm font-medium text-gray-700">Disponibilidad</label>
                      <select 
                        id="is_available" 
                        v-model="form.is_available" 
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                      >
                        <option :value="true">Disponible</option>
                        <option :value="false">No disponible</option>
                      </select>
                      <div v-if="form.errors.is_available" class="text-red-500 text-sm mt-1">{{ form.errors.is_available }}</div>
                    </div>
                  </div>

                  <!-- Propietario -->
                  <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700">Propietario</label>
                    <select 
                      id="user_id" 
                      v-model="form.user_id" 
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                    >
                      <option value="">Sin propietario asignado</option>
                      <option v-for="user in users" :key="user.id" :value="user.id">
                        {{ user.first_name }} {{ user.last_name }} (@{{ user.username }})
                      </option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Selecciona el usuario propietario de este apero</p>
                    <div v-if="form.errors.user_id" class="text-red-500 text-sm mt-1">{{ form.errors.user_id }}</div>
                  </div>
      
                  <!-- Descripción -->
                  <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea 
                      id="description" 
                      v-model="form.description" 
                      rows="4" 
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                      placeholder="Describe las características, funcionalidades y condiciones del apero..."
                    ></textarea>
                    <div v-if="form.errors.description" class="text-red-500 text-sm mt-1">{{ form.errors.description }}</div>
                  </div>
                </div>

                <!-- Gestión de imagen -->
                <div class="lg:col-span-1">
                  <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Imagen del Apero</h3>
                    
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
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
                        <li>• Usa imágenes claras y bien iluminadas</li>
                        <li>• Muestra el apero desde varios ángulos</li>
                        <li>• Incluye detalles importantes</li>
                        <li>• Formato cuadrado es ideal</li>
                      </ul>
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
                  {{ form.processing ? 'Guardando...' : 'Crear Apero' }}
                </button>
                <Link 
                  :href="route('aperos.index')" 
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
  
  <script setup lang="ts">
  import { useForm, Link } from '@inertiajs/vue3';
  import { ref } from 'vue';
  import AdminLayout from '@/Layouts/AdminLayout.vue';
  
  const props = defineProps({
    users: Array
  });
  
  const imagePreview = ref(null);
  
  const form = useForm({
    name: '',
    type: '',
    description: '',
    brand: '',
    model: '',
    year: null,
    is_available: true,
    user_id: '',
    image: null,
  });
  
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
    form.post(route('aperos.store'), {
      forceFormData: true
    });
  };
  </script>
  
<style src="../../../../css/Admin/Aperos/aperos_create_style.css" scoped></style>