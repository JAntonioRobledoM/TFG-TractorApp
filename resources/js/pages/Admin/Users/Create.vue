<template>
    <AdminLayout>
      <template #header>
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
          Crear Usuario
        </h2>
      </template>
      <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-2xl font-bold text-green-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-green-600" viewBox="0 0 20 20" fill="currentColor">
              <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
            </svg>
            Crear Nuevo Usuario
          </h1>
          <Link 
            :href="route('users.index')" 
            class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition"
          >
            Lista de Usuarios
          </Link>
        </div>

        <div class="bg-white shadow-lg rounded-lg border-t-4 border-green-600">
          <div class="p-6">
            <form @submit.prevent="submit" enctype="multipart/form-data">
              <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Información del usuario -->
                <div class="lg:col-span-2 space-y-6">
                  <!-- Información básica -->
                  <div>
                    <h3 class="text-lg font-semibold text-green-800 mb-4 border-b border-green-200 pb-2">
                      Información Básica
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                      <!-- Username -->
                      <div>
                        <label class="block text-sm font-medium text-gray-700" for="username">
                          Nombre de Usuario *
                        </label>
                        <input 
                          id="username" 
                          v-model="form.username" 
                          type="text" 
                          required 
                          placeholder="Ej: juan_agricola"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                        />
                        <p class="text-xs text-gray-500 mt-1">Solo letras, números y guiones bajos</p>
                        <div v-if="form.errors.username" class="text-red-500 text-sm mt-1">
                          {{ form.errors.username }}
                        </div>
                      </div>

                      <!-- Email -->
                      <div>
                        <label class="block text-sm font-medium text-gray-700" for="email">
                          Correo Electrónico *
                        </label>
                        <input 
                          id="email" 
                          v-model="form.email" 
                          type="email" 
                          required
                          placeholder="Ej: juan@email.com"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                        />
                        <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">
                          {{ form.errors.email }}
                        </div>
                      </div>

                      <!-- Nombre -->
                      <div>
                        <label class="block text-sm font-medium text-gray-700" for="first_name">
                          Nombre *
                        </label>
                        <input 
                          id="first_name" 
                          v-model="form.first_name" 
                          type="text" 
                          required 
                          placeholder="Ej: Juan"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                        />
                        <div v-if="form.errors.first_name" class="text-red-500 text-sm mt-1">
                          {{ form.errors.first_name }}
                        </div>
                      </div>

                      <!-- Apellidos -->
                      <div>
                        <label class="block text-sm font-medium text-gray-700" for="last_name">
                          Apellidos *
                        </label>
                        <input 
                          id="last_name" 
                          v-model="form.last_name" 
                          type="text" 
                          required 
                          placeholder="Ej: García López"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                        />
                        <div v-if="form.errors.last_name" class="text-red-500 text-sm mt-1">
                          {{ form.errors.last_name }}
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Información de contacto -->
                  <div>
                    <h3 class="text-lg font-semibold text-green-800 mb-4 border-b border-green-200 pb-2">
                      Información de Contacto
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                      <!-- DNI -->
                      <div>
                        <label class="block text-sm font-medium text-gray-700" for="dni">
                          DNI
                        </label>
                        <input 
                          id="dni" 
                          v-model="form.dni" 
                          type="text" 
                          placeholder="Ej: 12345678A"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                        />
                        <div v-if="form.errors.dni" class="text-red-500 text-sm mt-1">
                          {{ form.errors.dni }}
                        </div>
                      </div>

                      <!-- Teléfono -->
                      <div>
                        <label class="block text-sm font-medium text-gray-700" for="tlf">
                          Teléfono
                        </label>
                        <input 
                          id="tlf" 
                          v-model="form.tlf" 
                          type="tel" 
                          placeholder="Ej: 666123456"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                        />
                        <div v-if="form.errors.tlf" class="text-red-500 text-sm mt-1">
                          {{ form.errors.tlf }}
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Seguridad -->
                  <div>
                    <h3 class="text-lg font-semibold text-green-800 mb-4 border-b border-green-200 pb-2">
                      Seguridad
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                      <!-- Contraseña -->
                      <div>
                        <label class="block text-sm font-medium text-gray-700" for="pass">
                          Contraseña *
                        </label>
                        <input 
                          id="pass" 
                          v-model="form.pass" 
                          type="password" 
                          required 
                          placeholder="Mínimo 8 caracteres"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                        />
                        <p class="text-xs text-gray-500 mt-1">Mínimo 8 caracteres</p>
                        <div v-if="form.errors.pass" class="text-red-500 text-sm mt-1">
                          {{ form.errors.pass }}
                        </div>
                      </div>

                      <!-- Confirmar contraseña -->
                      <div>
                        <label class="block text-sm font-medium text-gray-700" for="pass_confirmation">
                          Confirmar Contraseña *
                        </label>
                        <input 
                          id="pass_confirmation" 
                          v-model="form.pass_confirmation" 
                          type="password" 
                          required 
                          placeholder="Repetir contraseña"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                        />
                        <p class="text-xs text-gray-500 mt-1" :class="passwordsMatch ? 'text-green-600' : 'text-red-600'">
                          {{ passwordsMatch && form.pass_confirmation ? '✓ Las contraseñas coinciden' : 'Las contraseñas deben coincidir' }}
                        </p>
                      </div>
                    </div>

                    <!-- Rol de administrador -->
                    <div class="mt-4">
                      <label class="block text-sm font-medium text-gray-700 mb-3">
                        Permisos de Usuario
                      </label>
                      <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                        <label class="flex items-center">
                          <input 
                            id="is_admin" 
                            v-model="form.is_admin" 
                            type="checkbox" 
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <div class="ml-3">
                            <span class="text-sm font-medium text-gray-700">Administrador</span>
                            <p class="text-xs text-gray-500">Acceso completo al panel de administración</p>
                          </div>
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                          </svg>
                        </label>
                      </div>
                      <div v-if="form.errors.is_admin" class="text-red-500 text-sm mt-1">
                        {{ form.errors.is_admin }}
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Foto de perfil y resumen -->
                <div class="lg:col-span-1 space-y-6">
                  <!-- Foto de perfil -->
                  <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Foto de Perfil</h3>
                    
                    <!-- Vista previa de foto -->
                    <div v-if="imagePreview" class="mb-4">
                      <p class="text-sm text-gray-600 mb-2">Vista previa:</p>
                      <div class="w-32 h-32 mx-auto rounded-full overflow-hidden bg-gray-100">
                        <img 
                          :src="imagePreview" 
                          alt="Vista previa"
                          class="w-full h-full object-cover"
                        />
                      </div>
                      <div class="mt-3 text-center">
                        <button 
                          type="button" 
                          @click="clearImagePreview"
                          class="text-sm text-red-600 hover:text-red-800"
                        >
                          Eliminar foto
                        </button>
                      </div>
                    </div>

                    <!-- Placeholder cuando no hay foto -->
                    <div v-if="!imagePreview" class="mb-4">
                      <div class="w-32 h-32 mx-auto rounded-full bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center">
                        <div class="text-center">
                          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                          </svg>
                          <p class="mt-2 text-xs text-gray-500">Sin foto</p>
                        </div>
                      </div>
                    </div>

                    <!-- Input de foto -->
                    <div>
                      <label for="pfp" class="block text-sm font-medium text-gray-700 mb-2">
                        Subir foto de perfil
                      </label>
                      <input 
                        type="file" 
                        id="pfp" 
                        @change="handleImageChange"
                        accept="image/jpeg,image/png,image/jpg,image/gif"
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100"
                      />
                      <p class="text-xs text-gray-500 mt-1">JPG, PNG, GIF hasta 2MB</p>
                    </div>
                    
                    <div v-if="form.errors.pfp" class="text-red-500 text-sm mt-1">{{ form.errors.pfp }}</div>

                    <!-- Consejos -->
                    <div class="mt-4 bg-blue-50 border border-blue-200 rounded-md p-3">
                      <h4 class="text-sm font-medium text-blue-800 mb-1">💡 Consejos para la foto:</h4>
                      <ul class="text-xs text-blue-700 space-y-1">
                        <li>• Foto clara del rostro</li>
                        <li>• Buena iluminación natural</li>
                        <li>• Formato cuadrado es ideal</li>
                        <li>• Evita fondos complejos</li>
                        <li>• Sonríe de forma natural</li>
                      </ul>
                    </div>
                  </div>

                  <!-- Resumen del usuario -->
                  <div class="bg-green-50 rounded-lg p-6 border border-green-200">
                    <h3 class="text-lg font-medium text-green-900 mb-4">📋 Resumen</h3>
                    <div class="space-y-2 text-sm">
                      <div class="flex justify-between">
                        <span class="text-green-700">Usuario:</span>
                        <span class="text-green-900 font-medium">@{{ form.username || 'Sin especificar' }}</span>
                      </div>
                      <div class="flex justify-between">
                        <span class="text-green-700">Nombre:</span>
                        <span class="text-green-900">{{ form.first_name || 'Sin especificar' }}</span>
                      </div>
                      <div class="flex justify-between">
                        <span class="text-green-700">Apellidos:</span>
                        <span class="text-green-900">{{ form.last_name || 'Sin especificar' }}</span>
                      </div>
                      <div class="flex justify-between">
                        <span class="text-green-700">Email:</span>
                        <span class="text-green-900">{{ form.email || 'Sin especificar' }}</span>
                      </div>
                      <div class="flex justify-between">
                        <span class="text-green-700">DNI:</span>
                        <span class="text-green-900">{{ form.dni || 'Sin especificar' }}</span>
                      </div>
                      <div class="flex justify-between">
                        <span class="text-green-700">Teléfono:</span>
                        <span class="text-green-900">{{ form.tlf || 'Sin especificar' }}</span>
                      </div>
                      <div class="flex justify-between">
                        <span class="text-green-700">Contraseña:</span>
                        <span class="text-green-900">{{ form.pass ? '********' : 'Sin especificar' }}</span>
                      </div>
                      <div class="flex justify-between">
                        <span class="text-green-700">Rol:</span>
                        <span 
                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                          :class="form.is_admin ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'"
                        >
                          {{ form.is_admin ? 'Administrador' : 'Usuario' }}
                        </span>
                      </div>
                    </div>
                  </div>

                  <!-- Progreso del formulario -->
                  <div class="bg-yellow-50 rounded-lg p-6 border border-yellow-200">
                    <h3 class="text-lg font-medium text-yellow-900 mb-4">⚡ Progreso</h3>
                    <div class="space-y-2">
                      <div class="flex justify-between text-sm">
                        <span class="text-yellow-700">Campos completados:</span>
                        <span class="text-yellow-900 font-medium">{{ completedFields }}/{{ totalFields }}</span>
                      </div>
                      <div class="w-full bg-yellow-200 rounded-full h-2">
                        <div 
                          class="bg-yellow-600 h-2 rounded-full transition-all duration-300" 
                          :style="{ width: progressPercentage + '%' }"
                        ></div>
                      </div>
                      <p class="text-xs text-yellow-700 text-center">
                        {{ progressPercentage }}% completado
                      </p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="mt-6 flex space-x-2">
                <button 
                  type="submit" 
                  class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition disabled:opacity-50"
                  :disabled="form.processing || !canSubmit"
                >
                  {{ form.processing ? 'Creando...' : 'Crear Usuario' }}
                </button>
                <Link 
                  :href="route('users.index')" 
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
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref, computed } from 'vue';

const imagePreview = ref(null);

const form = useForm({
  username: '',
  first_name: '',
  last_name: '',
  email: '',
  dni: '',
  tlf: '',
  pass: '',
  pass_confirmation: '',
  is_admin: false,
  pfp: null
});

const passwordsMatch = computed(() => {
  return form.pass === form.pass_confirmation && form.pass_confirmation !== '';
});

const completedFields = computed(() => {
  let count = 0;
  if (form.username) count++;
  if (form.first_name) count++;
  if (form.last_name) count++;
  if (form.email) count++;
  if (form.pass) count++;
  if (form.pass_confirmation && passwordsMatch.value) count++;
  return count;
});

const totalFields = 6; // Campos requeridos

const progressPercentage = computed(() => {
  return Math.round((completedFields.value / totalFields) * 100);
});

const canSubmit = computed(() => {
  return form.username && 
         form.first_name && 
         form.last_name && 
         form.email && 
         form.pass && 
         passwordsMatch.value;
});

const handleImageChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.pfp = file;
    
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
  form.pfp = null;
  // Reset input file
  const input = document.getElementById('pfp');
  if (input) input.value = '';
};

const submit = () => {
  form.post(route('users.store'), {
    forceFormData: true,
    onFinish: () => {
      if (!Object.keys(form.errors).length) {
        form.reset();
        clearImagePreview();
      }
    }
  });
};
</script>

<style scoped>
/* Input focus styles */
input:focus {
  box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.2);
}

/* Checkbox custom styling */
input[type="checkbox"] {
  accent-color: #16a34a;
}
</style>