<template>
  <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="bg-white shadow rounded-lg overflow-hidden">
      <!-- Cabecera -->
      <div class="bg-green-900 px-4 py-5 border-b border-gray-200 sm:px-6">
        <h2 class="text-xl font-bold text-yellow-400">
          Editar Perfil
        </h2>
        <p class="mt-1 text-sm text-green-100">
          Actualiza tu información personal y datos de contacto
        </p>
      </div>

      <!-- Formulario -->
      <form @submit.prevent="updateProfile" enctype="multipart/form-data">
        <div class="px-4 py-5 sm:p-6 space-y-6">
          <div v-if="success" class="rounded-md bg-green-50 p-4 mb-4">
            <div class="flex">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
              </div>
              <div class="ml-3">
                <p class="text-sm font-medium text-green-800">
                  {{ success }}
                </p>
              </div>
            </div>
          </div>

          <!-- Foto de Perfil -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-1">
              <h3 class="text-lg font-medium leading-6 text-gray-900">Foto de Perfil</h3>
              <p class="mt-1 text-sm text-gray-500">
                Sube una foto para personalizar tu perfil
              </p>
            </div>
            <div class="md:col-span-2 flex items-center space-x-6">
              <div class="flex-shrink-0 h-32 w-32 rounded-full overflow-hidden bg-gray-100 border-4 border-green-700">
                <div v-if="previewImage" class="h-full w-full">
                  <img :src="previewImage" alt="Vista previa" class="h-full w-full object-cover" />
                </div>
                <div v-else-if="user.pfp" class="h-full w-full">
                  <img :src="'/storage/' + user.pfp" alt="Foto de perfil" class="h-full w-full object-cover" />
                </div>
                <div v-else class="h-full w-full bg-green-700 flex items-center justify-center text-3xl font-bold text-yellow-400">
                  {{ user.first_name.charAt(0) }}{{ user.last_name.charAt(0) }}
                </div>
              </div>
              <div class="space-y-2">
                <label for="pfp" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-800 focus:ring focus:ring-green-300 disabled:opacity-25 transition cursor-pointer">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  Cambiar Foto
                </label>
                <input 
                  type="file" 
                  id="pfp" 
                  ref="fileInput" 
                  @change="previewFile" 
                  class="hidden" 
                  accept="image/*"
                />
                <button 
                  v-if="user.pfp || previewImage" 
                  type="button" 
                  @click="removePhoto" 
                  class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-800 focus:ring focus:ring-red-300 disabled:opacity-25 transition"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                  Eliminar Foto
                </button>
              </div>
            </div>
          </div>

          <div class="border-t border-gray-200 pt-6"></div>

          <!-- Información personal -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-1">
              <h3 class="text-lg font-medium leading-6 text-gray-900">Información Personal</h3>
              <p class="mt-1 text-sm text-gray-500">
                Esta información será visible para otros usuarios
              </p>
            </div>
            <div class="md:col-span-2 grid grid-cols-6 gap-6">
              <div class="col-span-6 sm:col-span-3">
                <label for="username" class="block text-sm font-medium text-gray-700">Nombre de usuario</label>
                <input 
                  type="text" 
                  name="username" 
                  id="username" 
                  v-model="form.username" 
                  class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                  :class="{ 'border-red-500': errors.username }"
                />
                <div v-if="errors.username" class="text-red-500 text-xs mt-1">{{ errors.username }}</div>
              </div>

              <div class="col-span-6 sm:col-span-3">
                <label for="email" class="block text-sm font-medium text-gray-700">Correo electrónico</label>
                <input 
                  type="email" 
                  name="email" 
                  id="email" 
                  v-model="form.email" 
                  class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                  :class="{ 'border-red-500': errors.email }"
                />
                <div v-if="errors.email" class="text-red-500 text-xs mt-1">{{ errors.email }}</div>
              </div>

              <div class="col-span-6 sm:col-span-3">
                <label for="first_name" class="block text-sm font-medium text-gray-700">Nombre</label>
                <input 
                  type="text" 
                  name="first_name" 
                  id="first_name" 
                  v-model="form.first_name" 
                  class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                  :class="{ 'border-red-500': errors.first_name }"
                />
                <div v-if="errors.first_name" class="text-red-500 text-xs mt-1">{{ errors.first_name }}</div>
              </div>

              <div class="col-span-6 sm:col-span-3">
                <label for="last_name" class="block text-sm font-medium text-gray-700">Apellidos</label>
                <input 
                  type="text" 
                  name="last_name" 
                  id="last_name" 
                  v-model="form.last_name" 
                  class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                  :class="{ 'border-red-500': errors.last_name }"
                />
                <div v-if="errors.last_name" class="text-red-500 text-xs mt-1">{{ errors.last_name }}</div>
              </div>

              <div class="col-span-6 sm:col-span-3">
                <label for="dni" class="block text-sm font-medium text-gray-700">DNI</label>
                <input 
                  type="text" 
                  name="dni" 
                  id="dni" 
                  v-model="form.dni" 
                  class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                  :class="{ 'border-red-500': errors.dni }"
                />
                <div v-if="errors.dni" class="text-red-500 text-xs mt-1">{{ errors.dni }}</div>
              </div>

              <div class="col-span-6 sm:col-span-3">
                <label for="tlf" class="block text-sm font-medium text-gray-700">Teléfono</label>
                <input 
                  type="tel" 
                  name="tlf" 
                  id="tlf" 
                  v-model="form.tlf" 
                  class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                  :class="{ 'border-red-500': errors.tlf }"
                />
                <div v-if="errors.tlf" class="text-red-500 text-xs mt-1">{{ errors.tlf }}</div>
              </div>
            </div>
          </div>

          <div class="border-t border-gray-200 pt-6"></div>

          <!-- Cambio de contraseña -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-1">
              <h3 class="text-lg font-medium leading-6 text-gray-900">Cambiar Contraseña</h3>
              <p class="mt-1 text-sm text-gray-500">
                Deja estos campos en blanco si no deseas cambiar tu contraseña
              </p>
            </div>
            <div class="md:col-span-2 grid grid-cols-6 gap-6">
              <div class="col-span-6 sm:col-span-4">
                <label for="current_password" class="block text-sm font-medium text-gray-700">Contraseña actual</label>
                <input 
                  type="password" 
                  name="current_password" 
                  id="current_password" 
                  v-model="form.current_password" 
                  class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                  :class="{ 'border-red-500': errors.current_password }"
                />
                <div v-if="errors.current_password" class="text-red-500 text-xs mt-1">{{ errors.current_password }}</div>
              </div>

              <div class="col-span-6 sm:col-span-4">
                <label for="new_password" class="block text-sm font-medium text-gray-700">Nueva contraseña</label>
                <input 
                  type="password" 
                  name="new_password" 
                  id="new_password" 
                  v-model="form.new_password" 
                  class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                  :class="{ 'border-red-500': errors.new_password }"
                />
                <div v-if="errors.new_password" class="text-red-500 text-xs mt-1">{{ errors.new_password }}</div>
              </div>

              <div class="col-span-6 sm:col-span-4">
                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar nueva contraseña</label>
                <input 
                  type="password" 
                  name="new_password_confirmation" 
                  id="new_password_confirmation" 
                  v-model="form.new_password_confirmation" 
                  class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                />
              </div>
            </div>
          </div>
        </div>
        
        <!-- Botones de acción -->
        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 flex justify-end space-x-3">
          <Link 
            :href="route('user.profile.show')" 
            class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring focus:ring-gray-200 disabled:opacity-25 transition"
          >
            Cancelar
          </Link>
          <button 
            type="submit" 
            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-800 focus:ring focus:ring-green-300 disabled:opacity-25 transition"
            :disabled="isSubmitting"
          >
            <svg v-if="isSubmitting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Guardar Cambios
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

// Props
const props = defineProps({
  user: Object,
  errors: Object,
  success: String,
});

// Estado local
const fileInput = ref(null);
const previewImage = ref(null);
const isSubmitting = ref(false);

// Formulario
const form = useForm({
  username: props.user.username,
  first_name: props.user.first_name,
  last_name: props.user.last_name,
  dni: props.user.dni || '',
  tlf: props.user.tlf || '',
  email: props.user.email || '',
  current_password: '',
  new_password: '',
  new_password_confirmation: '',
  pfp: null,
  _method: 'PUT',
});

// Funciones
const previewFile = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.pfp = file;
    const reader = new FileReader();
    reader.onload = (e) => {
      previewImage.value = e.target.result;
    };
    reader.readAsDataURL(file);
  }
};

const removePhoto = () => {
  form.pfp = null;
  previewImage.value = null;
  if (fileInput.value) {
    fileInput.value.value = '';
  }
};

const updateProfile = () => {
  isSubmitting.value = true;
  form.post(route('user.profile.update'), {
    forceFormData: true,
    preserveScroll: true,
    onSuccess: () => {
      isSubmitting.value = false;
      form.reset('current_password', 'new_password', 'new_password_confirmation');
      if (form.pfp === null && previewImage.value) {
        previewImage.value = null;
      }
    },
    onError: () => {
      isSubmitting.value = false;
    },
    onFinish: () => {
      isSubmitting.value = false;
    }
  });
};
</script>

<style scoped>
/* Estilos específicos si son necesarios */
</style>