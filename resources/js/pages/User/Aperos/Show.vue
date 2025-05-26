<template>
  <UserLayout>
    <Head :title="`Apero: ${apero.name}`" />

    <div class="py-12 bg-gradient-to-br from-green-50 to-yellow-50 min-h-screen">
      <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <!-- Header -->
          <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-8">
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-4">
                <Link 
                  :href="route('user.aperos.index')"
                  class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white p-2 rounded-full transition-colors duration-200"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                  </svg>
                </Link>
                <div class="bg-white p-3 rounded-full">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                  </svg>
                </div>
                <div>
                  <h1 class="text-3xl font-bold text-white">{{ apero.name }}</h1>
                  <p v-if="apero.type" class="text-green-100 mt-1">{{ apero.type }}</p>
                </div>
              </div>
              <span 
                :class="apero.is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                class="px-3 py-1 text-sm font-medium rounded-full"
              >
                {{ apero.is_available ? 'Disponible' : 'No disponible' }}
              </span>
            </div>
          </div>

          <div class="p-6">
            <!-- Mensajes de estado -->
            <div v-if="$page.props.flash?.message" class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
              {{ $page.props.flash.message }}
            </div>
            <div v-if="$page.props.flash?.error" class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
              {{ $page.props.flash.error }}
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
              <!-- Información principal -->
              <div class="lg:col-span-2 space-y-6">
                <!-- Imagen del apero -->
                <div v-if="apero.image_url" class="bg-gray-50 rounded-lg p-6">
                  <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Imagen del Apero
                  </h2>
                  <div class="relative">
                    <img 
                      :src="apero.image_url" 
                      :alt="apero.name"
                      class="w-full h-64 md:h-80 object-cover rounded-lg shadow-md"
                    />
                    <button 
                      @click="showImageModal = true"
                      class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-20 transition-all duration-200 rounded-lg flex items-center justify-center opacity-0 hover:opacity-100"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                      </svg>
                    </button>
                  </div>
                </div>

                <!-- Detalles del apero -->
                <div class="bg-gray-50 rounded-lg p-6">
                  <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Información del Apero
                  </h2>
                  
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div v-if="apero.brand" class="flex items-center space-x-2">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                      </svg>
                      <span class="text-sm font-medium text-gray-600">Marca:</span>
                      <span class="text-sm text-gray-900">{{ apero.brand }}</span>
                    </div>
                    
                    <div v-if="apero.model" class="flex items-center space-x-2">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                      </svg>
                      <span class="text-sm font-medium text-gray-600">Modelo:</span>
                      <span class="text-sm text-gray-900">{{ apero.model }}</span>
                    </div>
                    
                    <div v-if="apero.year" class="flex items-center space-x-2">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                      <span class="text-sm font-medium text-gray-600">Año:</span>
                      <span class="text-sm text-gray-900">{{ apero.year }}</span>
                    </div>
                    
                    <div v-if="apero.type" class="flex items-center space-x-2">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                      </svg>
                      <span class="text-sm font-medium text-gray-600">Tipo:</span>
                      <span class="text-sm text-gray-900">{{ apero.type }}</span>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      <span class="text-sm font-medium text-gray-600">Estado:</span>
                      <span 
                        :class="apero.is_available ? 'text-green-600' : 'text-red-600'"
                        class="text-sm font-medium"
                      >
                        {{ apero.is_available ? 'Disponible' : 'No disponible' }}
                      </span>
                    </div>
                  </div>
                  
                  <div v-if="apero.description" class="mt-4">
                    <h3 class="text-sm font-medium text-gray-600 mb-2">Descripción:</h3>
                    <p class="text-sm text-gray-900 leading-relaxed">{{ apero.description }}</p>
                  </div>
                </div>

                <!-- Tractores conectados -->
                <div v-if="apero.tractors && apero.tractors.length > 0" class="bg-blue-50 rounded-lg p-6">
                  <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-600" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M19 8h-2c0-.6-.4-1-1-1h-2c-.6 0-1 .4-1 1h-2c-1.7 0-3 1.3-3 3v3c0 1.7 1.3 3 3 3h8c1.7 0 3-1.3 3-3v-3c0-1.7-1.3-3-3-3zm-2 9c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3zM5 11c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"/>
                    </svg>
                    Tractores Conectados
                  </h2>
                  
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div 
                      v-for="tractor in apero.tractors" 
                      :key="tractor.id"
                      class="bg-white border border-blue-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200"
                    >
                      <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                          <div class="bg-blue-100 p-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" viewBox="0 0 24 24" fill="currentColor">
                              <path d="M19 8h-2c0-.6-.4-1-1-1h-2c-.6 0-1 .4-1 1h-2c-1.7 0-3 1.3-3 3v3c0 1.7 1.3 3 3 3h8c1.7 0 3-1.3 3-3v-3c0-1.7-1.3-3-3-3zm-2 9c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3zM5 11c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"/>
                            </svg>
                          </div>
                          <div>
                            <h3 class="font-medium text-gray-900">
                              {{ tractor.brand || 'Tractor' }} {{ tractor.model || `#${tractor.id}` }}
                            </h3>
                            <p v-if="tractor.year" class="text-sm text-gray-600">Año {{ tractor.year }}</p>
                          </div>
                        </div>
                        <Link 
                          :href="route('user.tractors.show', tractor.id)"
                          class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                        >
                          Ver detalles
                        </Link>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Panel lateral -->
              <div class="space-y-6">
                <!-- Acciones rápidas -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                  <h3 class="text-lg font-semibold text-gray-900 mb-4">Acciones</h3>
                  <div class="space-y-3">
                    <Link 
                      :href="route('user.aperos.index')"
                      class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                      </svg>
                      Volver a Mis Aperos
                    </Link>
                    
                    <Link 
                      :href="route('user.tractors.index')"
                      class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 8h-2c0-.6-.4-1-1-1h-2c-.6 0-1 .4-1 1h-2c-1.7 0-3 1.3-3 3v3c0 1.7 1.3 3 3 3h8c1.7 0 3-1.3 3-3v-3c0-1.7-1.3-3-3-3zm-2 9c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3zM5 11c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"/>
                      </svg>
                      Ver Mis Tractores
                    </Link>
                  </div>
                </div>

                <!-- Información adicional -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                  <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Información
                  </h3>
                  <div class="text-sm text-gray-700 space-y-2">
                    <p>
                      <strong>Los aperos aparecen aquí</strong> cuando están conectados a alguno de tus tractores.
                    </p>
                    <p>
                      Para conectar o desconectar aperos, ve a la página de detalles de cada tractor.
                    </p>
                    <p v-if="apero.tractors && apero.tractors.length === 0" class="text-yellow-700">
                      <strong>Nota:</strong> Este apero no está actualmente conectado a ninguno de tus tractores.
                    </p>
                  </div>
                </div>

                <!-- Estadísticas -->
                <div class="bg-gray-50 rounded-lg p-6">
                  <h3 class="text-lg font-semibold text-gray-900 mb-4">Estadísticas</h3>
                  <div class="space-y-3">
                    <div class="flex justify-between">
                      <span class="text-sm text-gray-600">Tractores conectados:</span>
                      <span class="text-sm font-medium text-gray-900">
                        {{ apero.tractors?.length || 0 }}
                      </span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-sm text-gray-600">Estado:</span>
                      <span 
                        :class="apero.is_available ? 'text-green-600' : 'text-red-600'"
                        class="text-sm font-medium"
                      >
                        {{ apero.is_available ? 'Operativo' : 'Inactivo' }}
                      </span>
                    </div>
                    <div v-if="apero.year" class="flex justify-between">
                      <span class="text-sm text-gray-600">Antigüedad:</span>
                      <span class="text-sm font-medium text-gray-900">
                        {{ new Date().getFullYear() - apero.year }} años
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal para ver imagen en tamaño completo -->
    <div v-if="showImageModal && apero.image_url" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4">
      <div class="relative max-w-4xl max-h-full">
        <button 
          @click="showImageModal = false"
          class="absolute -top-10 right-0 text-white hover:text-gray-300 z-10"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
        <img 
          :src="apero.image_url" 
          :alt="apero.name"
          class="max-w-full max-h-full object-contain rounded-lg"
        />
      </div>
    </div>
  </UserLayout>
</template>

<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import { ref } from 'vue';

defineProps<{
  apero: {
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
      year?: number;
    }>;
  };
}>();

const showImageModal = ref(false);
</script>