<template>
  <AdminLayout>
    <template #header>
      <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
        Detalles del Usuario
      </h2>
    </template>
    <div class="container mx-auto p-6">
      <!-- Header con información principal -->
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-2xl font-bold text-green-800">
            {{ user.first_name }} {{ user.last_name }}
          </h1>
          <p class="text-sm text-gray-600 mt-1">@{{ user.username }}</p>
        </div>
        <div class="space-x-2">
          <Link 
            :href="route('users.index')" 
            class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition"
          >
            Lista de Usuarios
          </Link>
          <Link 
            :href="route('users.edit', user.id)"
            class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700 transition"
          >
            Editar
          </Link>
          <Link 
            :href="route('users.assign-tractors', user.id)"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
          >
            Gestionar Tractores
          </Link>
          <button 
            @click="confirmDelete"
            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition"
          >
            Eliminar
          </button>
        </div>
      </div>

      <!-- Grid principal: Información y Foto -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Información del Usuario -->
        <div class="lg:col-span-2">
          <div class="bg-white shadow-lg overflow-hidden rounded-lg border-t-4 border-green-600">
            <div class="px-4 py-5 sm:px-6 bg-green-50">
              <h3 class="text-lg leading-6 font-medium text-green-800">
                Información Personal
              </h3>
              <p class="mt-1 max-w-2xl text-sm text-green-600">
                Datos de contacto y perfil del usuario
              </p>
            </div>
            <div class="border-t border-green-200">
              <dl>
                <!-- Username -->
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Nombre de Usuario
                  </dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-mono bg-gray-100 px-2 py-1 rounded">
                    @{{ user.username }}
                  </dd>
                </div>

                <!-- Nombre completo -->
                <div class="bg-green-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Nombre Completo
                  </dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-medium">
                    {{ user.first_name }} {{ user.last_name }}
                  </dd>
                </div>

                <!-- Email -->
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 7.89a2 2 0 002.83 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Correo Electrónico
                  </dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <div v-if="user.email" class="flex items-center">
                      <span>{{ user.email }}</span>
                      <a :href="`mailto:${user.email}`" class="ml-2 text-green-600 hover:text-green-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                      </a>
                    </div>
                    <span v-else class="text-gray-400 italic">No proporcionado</span>
                  </dd>
                </div>

                <!-- DNI -->
                <div class="bg-green-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 114 0v2m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c0 1.5 1.5 3 4 3s4-1.5 4-3"></path>
                    </svg>
                    DNI
                  </dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <span v-if="user.dni" class="font-mono bg-gray-100 px-2 py-1 rounded">{{ user.dni }}</span>
                    <span v-else class="text-gray-400 italic">No proporcionado</span>
                  </dd>
                </div>

                <!-- Teléfono -->
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    Teléfono
                  </dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <div v-if="user.tlf" class="flex items-center">
                      <span>{{ formatPhoneNumber(user.tlf) }}</span>
                      <a :href="`tel:${user.tlf}`" class="ml-2 text-green-600 hover:text-green-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                      </a>
                    </div>
                    <span v-else class="text-gray-400 italic">No proporcionado</span>
                  </dd>
                </div>

                <!-- Rol -->
                <div class="bg-green-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    Rol de Usuario
                  </dt>
                  <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">
                    <span 
                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                      :class="user.is_admin ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'"
                    >
                      <svg 
                        class="w-3 h-3 mr-1" 
                        fill="none" 
                        stroke="currentColor" 
                        viewBox="0 0 24 24"
                      >
                        <path 
                          v-if="user.is_admin"
                          stroke-linecap="round" 
                          stroke-linejoin="round" 
                          stroke-width="2" 
                          d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                        ></path>
                        <path 
                          v-else
                          stroke-linecap="round" 
                          stroke-linejoin="round" 
                          stroke-width="2" 
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                        ></path>
                      </svg>
                      {{ user.is_admin ? 'Administrador' : 'Usuario' }}
                    </span>
                  </dd>
                </div>
              </dl>
            </div>
          </div>
        </div>

        <!-- Foto de perfil -->
        <div class="lg:col-span-1">
          <div class="bg-white shadow-lg overflow-hidden rounded-lg border-t-4 border-green-600">
            <div class="px-4 py-5 sm:px-6 bg-green-50">
              <h3 class="text-lg leading-6 font-medium text-green-800">Foto de Perfil</h3>
            </div>
            <div class="p-6">
              <div class="w-full aspect-square rounded-full overflow-hidden bg-gray-100 flex items-center justify-center mx-auto max-w-xs">
                <img 
                  v-if="user.pfp" 
                  :src="`/storage/${user.pfp}`" 
                  :alt="user.username"
                  class="w-full h-full object-cover"
                />
                <div v-else class="text-center">
                  <div class="w-24 h-24 rounded-full bg-green-100 flex items-center justify-center mx-auto">
                    <span class="text-2xl font-medium text-green-700">
                      {{ (user.first_name?.[0] || '') + (user.last_name?.[0] || '') }}
                    </span>
                  </div>
                  <p class="mt-4 text-sm text-gray-500">Sin foto de perfil</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Tractores del Usuario -->
      <div class="bg-white shadow-lg overflow-hidden rounded-lg border-t-4 border-yellow-600 mb-6">
        <div class="px-4 py-5 sm:px-6 bg-yellow-50 flex justify-between items-center">
          <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-yellow-600" viewBox="0 0 24 24" stroke="currentColor" fill="none">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
              </svg>
              Tractores Asignados
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Tractores que posee este usuario</p>
          </div>
          <div class="flex items-center space-x-2">
            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
              {{ user.tractors?.length || 0 }} tractores
            </span>
            <Link 
              :href="route('users.assign-tractors', user.id)"
              class="text-yellow-600 hover:text-yellow-800 text-sm font-medium"
            >
              Gestionar →
            </Link>
          </div>
        </div>
        <div v-if="user.tractors && user.tractors.length" class="border-t border-yellow-200">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-6">
            <div 
              v-for="tractor in user.tractors" 
              :key="tractor.id"
              class="bg-gray-50 rounded-lg p-4 hover:bg-yellow-50 transition border border-gray-200"
            >
              <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                  <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
                    <img 
                      v-if="tractor.image" 
                      :src="`/storage/${tractor.image}`" 
                      :alt="`${tractor.brand} ${tractor.model}`"
                      class="w-full h-full object-cover"
                    />
                    <svg v-else class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                  </div>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium text-gray-900">
                    {{ tractor.brand }} {{ tractor.model }}
                  </p>
                  <div class="mt-1 space-y-1">
                    <p v-if="tractor.year" class="text-xs text-gray-500">Año: {{ tractor.year }}</p>
                    <p v-if="tractor.horsepower" class="text-xs text-gray-500">{{ tractor.horsepower }} CV</p>
                    <p v-if="tractor.license_plate" class="text-xs text-gray-500 font-mono">{{ tractor.license_plate }}</p>
                  </div>
                  <div class="mt-2">
                    <span 
                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                      :class="tractor.is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                    >
                      {{ tractor.is_available ? 'Disponible' : 'No disponible' }}
                    </span>
                  </div>
                </div>
              </div>
              <div class="mt-3">
                <Link 
                  :href="route('tractors.show', tractor.id)" 
                  class="text-yellow-600 hover:text-yellow-900 text-sm font-medium"
                >
                  Ver detalles →
                </Link>
              </div>
            </div>
          </div>
        </div>
        <div v-else class="px-4 py-8 text-center text-gray-500">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
          </svg>
          <p class="mt-2">Este usuario no tiene tractores asignados</p>
          <Link 
            :href="route('users.assign-tractors', user.id)" 
            class="mt-4 inline-block bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700 transition"
          >
            Asignar Tractores
          </Link>
        </div>
      </div>

      <!-- Anuncios del Usuario -->
      <div class="bg-white shadow-lg overflow-hidden rounded-lg border-t-4 border-blue-600">
        <div class="px-4 py-5 sm:px-6 bg-blue-50 flex justify-between items-center">
          <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" />
                <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z" />
              </svg>
              Anuncios Publicados
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Anuncios de venta y alquiler creados por este usuario</p>
          </div>
          <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
            {{ user.listings?.length || 0 }} anuncios
          </span>
        </div>
        <div v-if="user.listings && user.listings.length" class="border-t border-blue-200 divide-y divide-blue-100">
          <div 
            v-for="listing in user.listings" 
            :key="listing.id"
            class="px-6 py-4 hover:bg-blue-50 transition"
          >
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-4">
                <div class="flex space-x-2">
                  <span 
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                    :class="listing.type === 'sale' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800'"
                  >
                    {{ listing.type === 'sale' ? 'Venta' : 'Alquiler' }}
                  </span>
                  <span 
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                    :class="listing.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                  >
                    {{ listing.is_active ? 'Activo' : 'Inactivo' }}
                  </span>
                </div>
                <div>
                  <p class="text-sm font-medium text-gray-900">
                    {{ formatCurrency(listing.price) }}
                  </p>
                  <p v-if="listing.tractor" class="text-sm text-gray-500">
                    {{ listing.tractor.brand }} {{ listing.tractor.model }}
                  </p>
                  <p v-if="listing.type === 'rental' && listing.start_date && listing.end_date" class="text-xs text-gray-500">
                    {{ formatDate(listing.start_date) }} - {{ formatDate(listing.end_date) }}
                  </p>
                </div>
              </div>
              <Link 
                :href="route('listings.show', listing.id)" 
                class="text-blue-600 hover:text-blue-900 text-sm font-medium"
              >
                Ver anuncio →
              </Link>
            </div>
          </div>
        </div>
        <div v-else class="px-4 py-8 text-center text-gray-500">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
          </svg>
          <p class="mt-2">Este usuario no ha publicado anuncios</p>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  user: Object
});

const confirmDelete = () => {
  if (confirm('¿Estás seguro de que quieres eliminar este usuario? Esta acción no se puede deshacer.')) {
    router.delete(route('users.destroy', props.user.id));
  }
};

const formatCurrency = (value) => {
  if (!value) return '---';
  return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(value);
};

const formatDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('es-ES', { dateStyle: 'medium' }).format(date);
};

const formatPhoneNumber = (phone) => {
  if (!phone) return '';
  const phoneStr = phone.toString();
  // Formato español: XXX XXX XXX
  if (phoneStr.length === 9) {
    return `${phoneStr.slice(0, 3)} ${phoneStr.slice(3, 6)} ${phoneStr.slice(6)}`;
  }
  return phoneStr;
};
</script>

<style scoped>
.aspect-square {
  aspect-ratio: 1 / 1;
}

/* Add subtle field texture to the detail sections */
.bg-white {
  background-image:
    radial-gradient(circle at 10px 10px, rgba(34, 197, 94, 0.03) 2px, transparent 2px);
  background-size: 20px 20px;
}

/* Add pattern to the alternating rows */
.bg-green-50 {
  background-image:
    linear-gradient(to bottom, rgba(240, 253, 244, 0.9), rgba(240, 253, 244, 0.9)),
    repeating-linear-gradient(45deg,
      rgba(34, 197, 94, 0.05) 0px,
      rgba(34, 197, 94, 0.05) 10px,
      transparent 10px,
      transparent 20px);
}

/* Enhanced shadow for cards */
.shadow-lg {
  box-shadow: 0 10px 15px -3px rgba(34, 197, 94, 0.1), 0 4px 6px -2px rgba(34, 197, 94, 0.05);
}
</style>