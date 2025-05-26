<template>
  <UserLayout>
    <Head title="Mis Tractores" />

    <div class="py-12 bg-gradient-to-br from-green-50 to-yellow-50 min-h-screen">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <!-- Header -->
          <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-8">
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-3">
                <div class="bg-white p-3 rounded-full">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 8h-2c0-.6-.4-1-1-1h-2c-.6 0-1 .4-1 1h-2c-1.7 0-3 1.3-3 3v3c0 1.7 1.3 3 3 3h8c1.7 0 3-1.3 3-3v-3c0-1.7-1.3-3-3-3zm-2 9c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3zM5 11c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"/>
                  </svg>
                </div>
                <div>
                  <h1 class="text-3xl font-bold text-white">Mis Tractores</h1>
                  <p class="text-green-100 mt-1">Gestiona tu maquinaria agrícola</p>
                </div>
              </div>
              <div class="flex items-center space-x-4">
                <div class="text-right">
                  <div class="text-3xl font-bold text-white">{{ tractors?.length || 0 }}</div>
                  <div class="text-green-100 text-sm">Tractores disponibles</div>
                </div>
                <button 
                  @click="showCreateModal = true"
                  class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                  </svg>
                  <span>Agregar Tractor</span>
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

            <!-- Lista de tractores -->
            <div v-if="tractors && tractors.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <div 
                v-for="tractor in tractors" 
                :key="tractor.id"
                class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden"
              >
                <!-- Imagen del tractor -->
                <div class="relative h-48 bg-gray-100">
                  <img 
                    v-if="tractor.image_url" 
                    :src="tractor.image_url" 
                    :alt="`${tractor.brand || 'Tractor'} ${tractor.model || ''}`"
                    class="w-full h-full object-cover"
                  />
                  <div v-else class="w-full h-full flex items-center justify-center bg-gradient-to-br from-green-100 to-green-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-green-400" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M19 8h-2c0-.6-.4-1-1-1h-2c-.6 0-1 .4-1 1h-2c-1.7 0-3 1.3-3 3v3c0 1.7 1.3 3 3 3h8c1.7 0 3-1.3 3-3v-3c0-1.7-1.3-3-3-3zm-2 9c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3zM5 11c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"/>
                    </svg>
                  </div>
                  
                  <!-- Badge de estado superpuesto -->
                  <span 
                    :class="tractor.is_available ? 'bg-green-500 text-white' : 'bg-red-500 text-white'"
                    class="absolute top-2 right-2 px-2 py-1 text-xs font-medium rounded-full"
                  >
                    {{ tractor.is_available ? 'Disponible' : 'No disponible' }}
                  </span>
                </div>

                <div class="p-6">
                  <!-- Encabezado del tractor -->
                  <div class="mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                      {{ tractor.brand || 'Tractor' }} {{ tractor.model || `#${tractor.id}` }}
                    </h3>
                    <p v-if="tractor.year" class="text-sm text-gray-600">Año {{ tractor.year }}</p>
                  </div>

                  <!-- Información del tractor -->
                  <div class="space-y-2 mb-4">
                    <div v-if="tractor.horsepower" class="flex items-center text-sm text-gray-600">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                      </svg>
                      {{ tractor.horsepower }} HP
                    </div>
                    <div v-if="tractor.working_hours" class="flex items-center text-sm text-gray-600">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      {{ tractor.working_hours }} horas
                    </div>
                  </div>

                  <!-- Descripción -->
                  <div v-if="tractor.description" class="mb-4">
                    <p class="text-sm text-gray-600 line-clamp-2">{{ tractor.description }}</p>
                  </div>

                  <!-- Acciones -->
                  <div class="flex justify-between space-x-2">
                    <Link 
                      :href="route('user.tractors.show', tractor.id)"
                      class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                      </svg>
                      Ver detalles
                    </Link>
                    <button 
                      @click="deleteTractor(tractor)"
                      class="inline-flex items-center px-3 py-2 border border-red-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Estado vacío -->
            <div v-else class="text-center py-12">
              <div class="bg-gray-100 rounded-full p-6 w-24 h-24 mx-auto mb-4 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M19 8h-2c0-.6-.4-1-1-1h-2c-.6 0-1 .4-1 1h-2c-1.7 0-3 1.3-3 3v3c0 1.7 1.3 3 3 3h8c1.7 0 3-1.3 3-3v-3c0-1.7-1.3-3-3-3zm-2 9c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3zM5 11c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"/>
                </svg>
              </div>
              <h3 class="text-lg font-medium text-gray-900 mb-2">No tienes tractores</h3>
              <p class="text-gray-600 mb-4">
                Agrega tu primer tractor para comenzar a gestionar tu maquinaria agrícola.
              </p>
              <button 
                @click="showCreateModal = true"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Agregar primer tractor
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal para crear tractor -->
    <div v-if="showCreateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" viewBox="0 0 24 24" fill="currentColor">
              <path d="M19 8h-2c0-.6-.4-1-1-1h-2c-.6 0-1 .4-1 1h-2c-1.7 0-3 1.3-3 3v3c0 1.7 1.3 3 3 3h8c1.7 0 3-1.3 3-3v-3c0-1.7-1.3-3-3-3zm-2 9c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3zM5 11c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"/>
            </svg>
          </div>
          <h3 class="text-lg font-medium text-gray-900 text-center mt-4">Agregar Nuevo Tractor</h3>
          <div class="mt-6">
            <form @submit.prevent="createTractor" class="space-y-4" enctype="multipart/form-data">
              <!-- Campo de imagen -->
              <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">
                  Imagen del Tractor
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
                <div>
                  <label for="brand" class="block text-sm font-medium text-gray-700 mb-1">
                    Marca
                  </label>
                  <input 
                    id="brand" 
                    v-model="createForm.brand"
                    type="text"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 text-gray-900"
                    placeholder="Ej: John Deere, Massey Ferguson"
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
                    placeholder="Ej: 5075E, MF 4707"
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
                <div>
                  <label for="horsepower" class="block text-sm font-medium text-gray-700 mb-1">
                    Potencia (HP)
                  </label>
                  <input 
                    id="horsepower" 
                    v-model="createForm.horsepower"
                    type="number"
                    min="0"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 text-gray-900"
                    placeholder="Ej: 75"
                  />
                </div>
                <div>
                  <label for="working_hours" class="block text-sm font-medium text-gray-700 mb-1">
                    Horas de trabajo
                  </label>
                  <input 
                    id="working_hours" 
                    v-model="createForm.working_hours"
                    type="number"
                    min="0"
                    step="0.1"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 text-gray-900"
                    placeholder="Ej: 1250.5"
                  />
                </div>
                <div>
                  <label class="flex items-center">
                    <input 
                      v-model="createForm.is_available"
                      type="checkbox"
                      class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500 text-gray-900"
                    />
                    <span class="ml-2 text-sm text-gray-700">Disponible para uso</span>
                  </label>
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
                  placeholder="Descripción adicional del tractor..."
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
                  Crear Tractor
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
import { ref, reactive } from 'vue';

defineProps<{
  tractors?: Array<{
    id: number;
    brand?: string;
    model?: string;
    year?: number;
    description?: string;
    horsepower?: number;
    working_hours?: number;
    license_plate?: string;
    color?: string;
    is_available: boolean;
    image?: string;
    image_url?: string;
  }>;
}>();

const showCreateModal = ref(false);
const imagePreview = ref<string | null>(null);
const selectedImage = ref<File | null>(null);

const createForm = reactive({
  brand: '',
  model: '',
  year: null,
  description: '',
  horsepower: null,
  working_hours: null,
  is_available: true,
  image: null as File | null,
});

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
    if (key === 'is_available') {
      createForm[key] = true;
    } else if (typeof createForm[key] === 'number') {
      createForm[key] = null;
    } else {
      createForm[key] = key === 'image' ? null : '';
    }
  });
};

const createTractor = () => {
  // Crear FormData para manejar la subida de archivos
  const formData = new FormData();
  
  // Agregar todos los campos del formulario
  Object.keys(createForm).forEach(key => {
    if (createForm[key] !== null && createForm[key] !== '') {
      if (key === 'image' && selectedImage.value) {
        formData.append(key, selectedImage.value);
      } else if (key === 'is_available') {
        // Convertir explícitamente a boolean y luego a string para FormData
        formData.append(key, createForm[key] ? '1' : '0');
      } else if (key !== 'image') {
        formData.append(key, createForm[key]);
      }
    }
  });

  router.post(route('user.tractors.store'), formData, {
    forceFormData: true,
    onSuccess: () => {
      closeCreateModal();
    },
    onError: (errors) => {
      console.error('Error al crear tractor:', errors);
    }
  });
};

const deleteTractor = (tractor) => {
  const tractorName = `${tractor.brand || 'Tractor'} ${tractor.model || `#${tractor.id}`}`;
  
  if (confirm(`¿Estás seguro de que quieres eliminar el tractor "${tractorName}"? Esta acción no se puede deshacer.`)) {
    router.delete(route('user.tractors.destroy', tractor.id), {
      preserveScroll: true,
      onSuccess: (page) => {
        console.log('Tractor eliminado exitosamente');
      },
      onError: (errors) => {
        console.error('Error al eliminar tractor:', errors);
      },
      onFinish: () => {
        console.log('Operación de eliminación finalizada');
      }
    });
  }
};
</script>