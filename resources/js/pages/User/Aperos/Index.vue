<template>
  <UserLayout>
    <Head title="Mis Aperos" />

    <div class="py-12 bg-gradient-to-br from-green-50 to-yellow-50 min-h-screen">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <!-- Header -->
          <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-8">
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-3">
                <div class="bg-white p-3 rounded-full">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                  </svg>
                </div>
                <div>
                  <h1 class="text-3xl font-bold text-white">Mis Aperos</h1>
                  <p class="text-green-100 mt-1">Gestiona tus implementos agrícolas</p>
                </div>
              </div>
              <div class="flex items-center space-x-4">
                <div class="text-right">
                  <div class="text-3xl font-bold text-white">{{ aperos?.length || 0 }}</div>
                  <div class="text-green-100 text-sm">Aperos disponibles</div>
                </div>
                <button 
                  @click="showCreateModal = true"
                  class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                  </svg>
                  <span>Agregar Apero</span>
                </button>
              </div>
            </div>
          </div>

          <div class="p-6">
            <!-- Mensaje de estado -->
            <div v-if="$page.props.flash?.message" class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
              {{ $page.props.flash.message }}
            </div>
            <div v-if="$page.props.flash?.error" class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
              {{ $page.props.flash.error }}
            </div>

            <!-- Lista de aperos -->
            <div v-if="aperos && aperos.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <div 
                v-for="apero in aperos" 
                :key="apero.id"
                class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden"
              >
                <!-- Imagen del apero -->
                <div class="relative h-48 bg-gray-100">
                  <img 
                    v-if="apero.image_url" 
                    :src="apero.image_url" 
                    :alt="apero.name"
                    class="w-full h-full object-cover"
                  />
                  <div v-else class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                  </div>
                  
                  <!-- Badge de estado superpuesto -->
                  <span 
                    :class="apero.is_available ? 'bg-green-500 text-white' : 'bg-red-500 text-white'"
                    class="absolute top-2 right-2 px-2 py-1 text-xs font-medium rounded-full"
                  >
                    {{ apero.is_available ? 'Disponible' : 'No disponible' }}
                  </span>
                </div>

                <div class="p-6">
                  <!-- Encabezado del apero -->
                  <div class="mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ apero.name }}</h3>
                    <p v-if="apero.type" class="text-sm text-gray-600">{{ apero.type }}</p>
                  </div>

                  <!-- Información del apero -->
                  <div class="space-y-2 mb-4">
                    <div v-if="apero.brand" class="flex items-center text-sm text-gray-600">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                      </svg>
                      Marca: {{ apero.brand }}
                    </div>
                    <div v-if="apero.model" class="flex items-center text-sm text-gray-600">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                      </svg>
                      Modelo: {{ apero.model }}
                    </div>
                    <div v-if="apero.year" class="flex items-center text-sm text-gray-600">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                      Año: {{ apero.year }}
                    </div>
                  </div>

                  <!-- Tractores conectados -->
                  <div v-if="apero.tractors && apero.tractors.length > 0" class="mb-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Conectado a:</h4>
                    <div class="flex flex-wrap gap-1">
                      <span 
                        v-for="tractor in apero.tractors" 
                        :key="tractor.id"
                        class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full"
                      >
                        {{ tractor.brand || 'Tractor' }} {{ tractor.model || `#${tractor.id}` }}
                      </span>
                    </div>
                  </div>

                  <!-- Descripción -->
                  <div v-if="apero.description" class="mb-4">
                    <p class="text-sm text-gray-600 line-clamp-2">{{ apero.description }}</p>
                  </div>

                  <!-- Acciones -->
                  <div class="flex justify-end space-x-2">
                    <Link 
                      :href="route('user.aperos.show', apero.id)"
                      class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                      </svg>
                      Ver detalles
                    </Link>
                  </div>
                </div>
              </div>
            </div>

            <!-- Estado vacío -->
            <div v-else class="text-center py-12">
              <div class="bg-gray-100 rounded-full p-6 w-24 h-24 mx-auto mb-4 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
              </div>
              <h3 class="text-lg font-medium text-gray-900 mb-2">No tienes aperos</h3>
              <p class="text-gray-600 mb-4">
                Crea tu primer apero para comenzar a gestionar tus implementos agrícolas.
              </p>
              <button 
                @click="showCreateModal = true"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Agregar primer apero
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal para crear apero -->
    <div v-if="showCreateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
            </svg>
          </div>
          <h3 class="text-lg font-medium text-gray-900 text-center mt-4">Crear Nuevo Apero</h3>
          <div class="mt-6">
            <form @submit.prevent="createApero" class="space-y-4" enctype="multipart/form-data">
              <!-- Campo de imagen -->
              <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">
                  Imagen del Apero
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-green-400 transition-colors">
                  <div class="space-y-1 text-center">
                    <div v-if="imagePreview" class="mb-4">
                      <img :src="imagePreview" alt="Preview" class="mx-auto h-32 w-32 object-cover rounded-lg" />
                    </div>
                    <svg v-else class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                      <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex text-sm text-gray-600">
                      <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500">
                        <span>{{ imagePreview ? 'Cambiar imagen' : 'Subir una imagen' }}</span>
                        <input 
                          id="image" 
                          @change="handleImageChange"
                          type="file" 
                          accept="image/*"
                          class="sr-only"
                        />
                      </label>
                      <p class="pl-1">o arrastrar y soltar</p>
                    </div>
                    <p class="text-xs text-gray-500">PNG, JPG, GIF hasta 2MB</p>
                  </div>
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                  <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                    Nombre del Apero *
                  </label>
                  <input 
                    id="name" 
                    v-model="createForm.name"
                    type="text"
                    required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 text-gray-900"
                    placeholder="Ej: Arado de discos, Sembradora, Cosechadora"
                  />
                </div>
                
                <div>
                  <label for="type" class="block text-sm font-medium text-gray-700 mb-1">
                    Tipo
                  </label>
                  <select 
                    id="type" 
                    v-model="createForm.type"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500"
                  >
                    <option value="">Seleccionar tipo...</option>
                    <option value="Arado">Arado</option>
                    <option value="Rastra">Rastra</option>
                    <option value="Sembradora">Sembradora</option>
                    <option value="Cosechadora">Cosechadora</option>
                    <option value="Pulverizadora">Pulverizadora</option>
                    <option value="Cultivador">Cultivador</option>
                    <option value="Segadora">Segadora</option>
                    <option value="Empacadora">Empacadora</option>
                    <option value="Esparcidor">Esparcidor</option>
                    <option value="Otro">Otro</option>
                  </select>
                </div>
                
                <div>
                  <label for="tractor_id" class="block text-sm font-medium text-gray-700 mb-1">
                    Conectar a Tractor *
                  </label>
                  <select 
                    id="tractor_id" 
                    v-model="createForm.tractor_id"
                    required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500"
                  >
                    <option value="">Seleccionar tractor...</option>
                    <option 
                      v-for="tractor in userTractors" 
                      :key="tractor.id" 
                      :value="tractor.id"
                    >
                      {{ tractor.brand || 'Tractor' }} {{ tractor.model || `#${tractor.id}` }}
                    </option>
                  </select>
                </div>
                
                <div>
                  <label for="brand" class="block text-sm font-medium text-gray-700 mb-1">
                    Marca
                  </label>
                  <input 
                    id="brand" 
                    v-model="createForm.brand"
                    type="text"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 text-gray-900"
                    placeholder="Ej: John Deere, Kverneland"
                  />
                </div>
                
                <div>
                  <label for="model" class="block text-sm font-medium text-gray-700 mb-1">
                    Modelo
                  </label>
                  <input 
                    id="model" 
                    v-model="createForm.model"
                    type="text"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 text-gray-900"
                    placeholder="Ej: 2600, CX 3.20"
                  />
                </div>
                
                <div>
                  <label for="year" class="block text-sm font-medium text-gray-700 mb-1">
                    Año
                  </label>
                  <input 
                    id="year" 
                    v-model="createForm.year"
                    type="number"
                    min="1900"
                    :max="new Date().getFullYear()"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 text-gray-900"
                    placeholder="Ej: 2020"
                  />
                </div>
              </div>
              
              <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                  Descripción
                </label>
                <textarea 
                  id="description" 
                  v-model="createForm.description"
                  rows="3"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 text-gray-900"
                  placeholder="Descripción adicional del apero, características especiales, estado, etc."
                ></textarea>
              </div>
              
              <div class="flex items-center justify-end space-x-3 pt-4">
                <button 
                  type="button"
                  @click="closeCreateModal"
                  class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                >
                  Cancelar
                </button>
                <button 
                  type="submit"
                  class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                >
                  Crear Apero
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </UserLayout>
</template>

<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import { ref, reactive, onMounted } from 'vue';

defineProps<{
  aperos?: Array<{
    id: number;
    name: string;
    type?: string;
    description?: string;
    brand?: string;
    model?: string;
    year?: number;
    is_available: boolean;
    image?: string;
    image_url?: string;
    tractors?: Array<{
      id: number;
      brand?: string;
      model?: string;
    }>;
  }>;
}>();

const showCreateModal = ref(false);
const imagePreview = ref<string | null>(null);
const selectedImage = ref<File | null>(null);
const userTractors = ref([]);

const createForm = reactive({
  name: '',
  type: '',
  brand: '',
  model: '',
  year: null,
  description: '',
  tractor_id: '',
  image: null as File | null,
});

// Cargar tractores del usuario al montar el componente
onMounted(async () => {
  try {
    const response = await fetch(route('user.tractors.index'));
    if (response.ok) {
      // En lugar de hacer otra petición, obtenemos los tractores del usuario desde el backend
      // Para esto necesitaremos una ruta API o usar los datos que ya están disponibles
      loadUserTractors();
    }
  } catch (error) {
    console.error('Error loading user tractors:', error);
  }
});

const loadUserTractors = async () => {
  try {
    // Usar los datos disponibles en la página o hacer una petición específica
    const response = await fetch('/user/api/tractors');
    if (response.ok) {
      const data = await response.json();
      userTractors.value = data.tractors || [];
    }
  } catch (error) {
    // Si no hay ruta API, usar datos mock o los disponibles en $page.props
    userTractors.value = [];
  }
};

const handleImageChange = (event: Event) => {
  const target = event.target as HTMLInputElement;
  const file = target.files?.[0];
  
  if (file) {
    selectedImage.value = file;
    createForm.image = file;
    
    // Crear preview
    const reader = new FileReader();
    reader.onload = (e) => {
      imagePreview.value = e.target?.result as string;
    };
    reader.readAsDataURL(file);
  }
};

const closeCreateModal = () => {
  showCreateModal.value = false;
  imagePreview.value = null;
  selectedImage.value = null;
  
  // Reset form
  Object.keys(createForm).forEach(key => {
    if (typeof createForm[key] === 'number') {
      createForm[key] = null;
    } else {
      createForm[key] = key === 'image' ? null : '';
    }
  });
};

const createApero = () => {
  // Validar que se haya seleccionado un tractor
  if (!createForm.tractor_id) {
    alert('Por favor selecciona un tractor para conectar el apero.');
    return;
  }

  // Crear FormData para manejar la subida de archivos
  const formData = new FormData();
  
  // Agregar todos los campos del formulario
  Object.keys(createForm).forEach(key => {
    if (createForm[key] !== null && createForm[key] !== '') {
      if (key === 'image' && selectedImage.value) {
        formData.append(key, selectedImage.value);
      } else if (key !== 'image') {
        formData.append(key, createForm[key]);
      }
    }
  });

  router.post(route('user.aperos.store'), formData, {
    forceFormData: true,
    onSuccess: () => {
      closeCreateModal();
    },
    onError: (errors) => {
      console.error('Error al crear apero:', errors);
    }
  });
};
</script>