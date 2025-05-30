<template>
  <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="bg-white shadow rounded-lg overflow-hidden">
      <!-- Cabecera con foto de perfil -->
      <div class="bg-green-900 px-4 py-5 border-b border-gray-200 sm:px-6 relative">
        <div class="flex items-center">
          <div class="flex-shrink-0 h-24 w-24 relative">
            <div v-if="user.pfp" class="h-24 w-24 rounded-full overflow-hidden bg-gray-100 border-4 border-yellow-400">
              <img :src="'/storage/' + user.pfp" alt="Foto de perfil" class="h-full w-full object-cover" />
            </div>
            <div v-else class="h-24 w-24 rounded-full overflow-hidden bg-green-700 border-4 border-yellow-400 flex items-center justify-center text-3xl font-bold text-yellow-400">
              {{ user.first_name.charAt(0) }}{{ user.last_name.charAt(0) }}
            </div>
          </div>
          <div class="ml-6">
            <h2 class="text-2xl font-bold text-yellow-400">
              {{ user.first_name }} {{ user.last_name }}
            </h2>
            <p class="text-sm text-green-100">
              @{{ user.username }}
            </p>
            <p v-if="user.email" class="text-sm text-green-200">
              {{ user.email }}
            </p>
          </div>
        </div>
        <div class="absolute top-4 right-4">
          <Link 
            :href="route('user.profile.edit')" 
            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-800 focus:ring focus:ring-green-300 disabled:opacity-25 transition"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
            </svg>
            Editar Perfil
          </Link>
        </div>
      </div>

      <!-- Información del perfil -->
      <div class="px-4 py-5 sm:p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Columna izquierda -->
          <div class="space-y-6">
            <div>
              <h3 class="text-lg leading-6 font-medium text-gray-900 border-b border-gray-200 pb-2">
                Información Personal
              </h3>
              <div class="mt-4 space-y-4">
                <div>
                  <div class="text-sm font-medium text-gray-500">Nombre de usuario</div>
                  <div class="mt-1 text-sm text-gray-900">{{ user.username }}</div>
                </div>
                <div>
                  <div class="text-sm font-medium text-gray-500">Nombre</div>
                  <div class="mt-1 text-sm text-gray-900">{{ user.first_name }}</div>
                </div>
                <div>
                  <div class="text-sm font-medium text-gray-500">Apellidos</div>
                  <div class="mt-1 text-sm text-gray-900">{{ user.last_name }}</div>
                </div>
                <div v-if="user.dni">
                  <div class="text-sm font-medium text-gray-500">DNI</div>
                  <div class="mt-1 text-sm text-gray-900">{{ user.dni }}</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Columna derecha -->
          <div class="space-y-6">
            <div>
              <h3 class="text-lg leading-6 font-medium text-gray-900 border-b border-gray-200 pb-2">
                Información de Contacto
              </h3>
              <div class="mt-4 space-y-4">
                <div>
                  <div class="text-sm font-medium text-gray-500">Correo electrónico</div>
                  <div class="mt-1 text-sm text-gray-900">{{ user.email }}</div>
                </div>
                <div v-if="user.tlf">
                  <div class="text-sm font-medium text-gray-500">Teléfono</div>
                  <div class="mt-1 text-sm text-gray-900">{{ user.tlf }}</div>
                </div>
              </div>
            </div>

            <div>
              <h3 class="text-lg leading-6 font-medium text-gray-900 border-b border-gray-200 pb-2">
                Información de la Cuenta
              </h3>
              <div class="mt-4 space-y-4">
                <div>
                  <div class="text-sm font-medium text-gray-500">Tipo de cuenta</div>
                  <div class="mt-1 text-sm text-gray-900">
                    <span v-if="user.is_admin" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                      Administrador
                    </span>
                    <span v-else class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                      Usuario
                    </span>
                  </div>
                </div>
                <div>
                  <div class="text-sm font-medium text-gray-500">Fecha de registro</div>
                  <div class="mt-1 text-sm text-gray-900">{{ formatDate(user.created_at) }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Volver al panel -->
      <div class="px-4 py-4 sm:px-6 bg-gray-50 border-t border-gray-200 flex justify-end">
        <Link 
          :href="route('user.dashboard')" 
          class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring focus:ring-gray-200 disabled:opacity-25 transition"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
          </svg>
          Volver al Panel
        </Link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

// Props
const props = defineProps({
  user: Object,
});

// Funciones
const formatDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('es-ES', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  }).format(date);
};
</script>

<style scoped>
/* Estilos específicos si son necesarios */
</style>