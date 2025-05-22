<template>
  <UserLayout>
    <Head :title="`Tractor: ${tractor.brand || 'Tractor'} ${tractor.model || `#${tractor.id}`}`" />

    <div class="py-12 bg-gradient-to-br from-green-50 to-yellow-50 min-h-screen">
      <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <!-- Header -->
          <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-8">
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-4">
                <Link 
                  :href="route('user.tractors.index')"
                  class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white p-2 rounded-full transition-colors duration-200"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                  </svg>
                </Link>
                <div class="bg-white p-3 rounded-full">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 8h-2c0-.6-.4-1-1-1h-2c-.6 0-1 .4-1 1h-2c-1.7 0-3 1.3-3 3v3c0 1.7 1.3 3 3 3h8c1.7 0 3-1.3 3-3v-3c0-1.7-1.3-3-3-3zm-2 9c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3zM5 11c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"/>
                  </svg>
                </div>
                <div>
                  <h1 class="text-3xl font-bold text-white">
                    {{ tractor.brand || 'Tractor' }} {{ tractor.model || `#${tractor.id}` }}
                  </h1>
                  <p v-if="tractor.year" class="text-green-100 mt-1">Año {{ tractor.year }}</p>
                </div>
              </div>
              <span 
                :class="tractor.is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                class="px-3 py-1 text-sm font-medium rounded-full"
              >
                {{ tractor.is_available ? 'Disponible' : 'No disponible' }}
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
                <!-- Detalles del tractor -->
                <div class="bg-gray-50 rounded-lg p-6">
                  <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Información del Tractor
                  </h2>
                  
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div v-if="tractor.brand" class="flex items-center space-x-2">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                      </svg>
                      <span class="text-sm font-medium text-gray-600">Marca:</span>
                      <span class="text-sm text-gray-900">{{ tractor.brand }}</span>
                    </div>
                    
                    <div v-if="tractor.model" class="flex items-center space-x-2">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                      </svg>
                      <span class="text-sm font-medium text-gray-600">Modelo:</span>
                      <span class="text-sm text-gray-900">{{ tractor.model }}</span>
                    </div>
                    
                    <div v-if="tractor.year" class="flex items-center space-x-2">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                      <span class="text-sm font-medium text-gray-600">Año:</span>
                      <span class="text-sm text-gray-900">{{ tractor.year }}</span>
                    </div>
                    
                    <div v-if="tractor.horsepower" class="flex items-center space-x-2">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                      </svg>
                      <span class="text-sm font-medium text-gray-600">Potencia:</span>
                      <span class="text-sm text-gray-900">{{ tractor.horsepower }} HP</span>
                    </div>
                    
                    <div v-if="tractor.working_hours" class="flex items-center space-x-2">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      <span class="text-sm font-medium text-gray-600">Horas de trabajo:</span>
                      <span class="text-sm text-gray-900">{{ tractor.working_hours }} horas</span>
                    </div>
                    
                    <div v-if="tractor.license_plate" class="flex items-center space-x-2">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                      </svg>
                      <span class="text-sm font-medium text-gray-600">Matrícula:</span>
                      <span class="text-sm text-gray-900">{{ tractor.license_plate }}</span>
                    </div>
                    
                    <div v-if="tractor.color" class="flex items-center space-x-2">
                      <div class="h-5 w-5 rounded-full border border-gray-300" :style="{ backgroundColor: tractor.color }"></div>
                      <span class="text-sm font-medium text-gray-600">Color:</span>
                      <span class="text-sm text-gray-900">{{ tractor.color }}</span>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      <span class="text-sm font-medium text-gray-600">Estado:</span>
                      <span 
                        :class="tractor.is_available ? 'text-green-600' : 'text-red-600'"
                        class="text-sm font-medium"
                      >
                        {{ tractor.is_available ? 'Disponible' : 'No disponible' }}
                      </span>
                    </div>
                  </div>
                  
                  <div v-if="tractor.description" class="mt-4">
                    <h3 class="text-sm font-medium text-gray-600 mb-2">Descripción:</h3>
                    <p class="text-sm text-gray-900 leading-relaxed">{{ tractor.description }}</p>
                  </div>
                </div>

                <!-- Aperos conectados -->
                <div class="bg-blue-50 rounded-lg p-6">
                  <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                      </svg>
                      Aperos Conectados ({{ activeAperos.length }})
                    </h2>
                    <button 
                      @click="showConnectAperoModal = true"
                      class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                      </svg>
                      Conectar Apero
                    </button>
                  </div>
                  
                  <!-- Lista de aperos conectados -->
                  <div v-if="activeAperos.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div 
                      v-for="apero in activeAperos" 
                      :key="apero.id"
                      class="bg-white border border-blue-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200"
                    >
                      <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                          <div class="bg-blue-100 p-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                          </div>
                          <div>
                            <h3 class="font-medium text-gray-900">{{ apero.name }}</h3>
                            <p v-if="apero.type" class="text-sm text-gray-600">{{ apero.type }}</p>
                            <p v-if="apero.pivot && apero.pivot.attached_at" class="text-xs text-gray-500">
                              Conectado: {{ formatDate(apero.pivot.attached_at) }}
                            </p>
                          </div>
                        </div>
                        <div class="flex items-center space-x-2">
                          <Link 
                            :href="route('user.aperos.show', apero.id)"
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                          >
                            Ver
                          </Link>
                          <button 
                            @click="disconnectApero(apero)"
                            class="text-red-600 hover:text-red-800 text-sm font-medium"
                          >
                            Desconectar
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Estado vacío -->
                  <div v-else class="text-center py-8">
                    <div class="bg-blue-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                      </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay aperos conectados</h3>
                    <p class="text-gray-600 mb-4">
                      Conecta aperos a este tractor para maximizar su funcionalidad.
                    </p>
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
                      :href="route('user.tractors.index')"
                      class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                      </svg>
                      Volver a Mis Tractores
                    </Link>
                    
                    <Link 
                      :href="route('user.aperos.index')"
                      class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                      </svg>
                      Ver Mis Aperos
                    </Link>
                    
                    <button 
                      @click="deleteTractor"
                      class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                      Eliminar Tractor
                    </button>
                  </div>
                </div>

                <!-- Información adicional -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                  <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Consejos
                  </h3>
                  <div class="text-sm text-gray-700 space-y-2">
                    <p>
                      <strong>Mantenimiento:</strong> Revisa regularmente las horas de trabajo y programa el mantenimiento preventivo.
                    </p>
                    <p>
                      <strong>Aperos:</strong> Conecta los aperos necesarios según el trabajo que vayas a realizar.
                    </p>
                    <p>
                      <strong>Anuncios:</strong> Puedes poner este tractor en venta o alquiler desde tu panel de control.
                    </p>
                  </div>
                </div>

                <!-- Estadísticas -->
                <div class="bg-gray-50 rounded-lg p-6">
                  <h3 class="text-lg font-semibold text-gray-900 mb-4">Estadísticas</h3>
                  <div class="space-y-3">
                    <div class="flex justify-between">
                      <span class="text-sm text-gray-600">Aperos conectados:</span>
                      <span class="text-sm font-medium text-gray-900">{{ activeAperos.length }}</span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-sm text-gray-600">Estado:</span>
                      <span 
                        :class="tractor.is_available ? 'text-green-600' : 'text-red-600'"
                        class="text-sm font-medium"
                      >
                        {{ tractor.is_available ? 'Operativo' : 'Inactivo' }}
                      </span>
                    </div>
                    <div v-if="tractor.year" class="flex justify-between">
                      <span class="text-sm text-gray-600">Antigüedad:</span>
                      <span class="text-sm font-medium text-gray-900">
                        {{ new Date().getFullYear() - tractor.year }} años
                      </span>
                    </div>
                    <div v-if="tractor.horsepower" class="flex justify-between">
                      <span class="text-sm text-gray-600">Potencia:</span>
                      <span class="text-sm font-medium text-gray-900">{{ tractor.horsepower }} HP</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal para conectar apero -->
    <div v-if="showConnectAperoModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
            </svg>
          </div>
          <h3 class="text-lg font-medium text-gray-900 text-center mt-4">Conectar Apero</h3>
          <div class="mt-4">
            <form @submit.prevent="connectApero">
              <div class="mb-4">
                <label for="apero_id" class="block text-sm font-medium text-gray-700 mb-2">
                  Seleccionar Apero
                </label>
                <select 
                  id="apero_id" 
                  v-model="connectAperoForm.apero_id"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                  required
                >
                  <option value="">Selecciona un apero...</option>
                  <option 
                    v-for="apero in availableAperos" 
                    :key="apero.id" 
                    :value="apero.id"
                  >
                    {{ apero.name }} {{ apero.type ? `(${apero.type})` : '' }}
                  </option>
                </select>
              </div>
              <div class="flex items-center justify-end space-x-2">
                <button 
                  type="button"
                  @click="showConnectAperoModal = false"
                  class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                >
                  Cancelar
                </button>
                <button 
                  type="submit"
                  class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                  Conectar
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
import { ref, reactive, computed, onMounted } from 'vue';

const props = defineProps<{
  tractor: {
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
    aperos?: Array<{
      id: number;
      name: string;
      type?: string;
      pivot?: {
        attached_at?: string;
        detached_at?: string;
      };
    }>;
  };
  availableAperos?: Array<{
    id: number;
    name: string;
    type?: string;
  }>;
}>();

// Estados reactivos
const showConnectAperoModal = ref(false);
const connectAperoForm = reactive({
  apero_id: ''
});
const availableAperos = ref(props.availableAperos || []);

// Computed para filtrar aperos activos
const activeAperos = computed(() => {
  return props.tractor?.aperos?.filter(apero => !apero.pivot?.detached_at) || [];
});

// Cargar aperos disponibles al montar el componente
onMounted(async () => {
  try {
    const response = await fetch(route('user.aperos.available-for-tractor', props.tractor.id));
    const data = await response.json();
    availableAperos.value = data.aperos || [];
  } catch (error) {
    console.error('Error loading available aperos:', error);
  }
});

// Funciones
const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('es-ES');
};

const connectApero = () => {
  router.post(route('user.tractors.attach-apero', props.tractor.id), connectAperoForm, {
    onSuccess: () => {
      showConnectAperoModal.value = false;
      connectAperoForm.apero_id = '';
      // Recargar aperos disponibles
      onMounted();
    },
    onError: (errors) => {
      console.error('Error al conectar apero:', errors);
    }
  });
};

const disconnectApero = (apero: any) => {
  if (confirm(`¿Estás seguro de que quieres desconectar el apero "${apero.name}"?`)) {
    router.delete(route('user.tractors.detach-apero', [props.tractor.id, apero.id]), {
      onSuccess: () => {
        // Recargar aperos disponibles
        onMounted();
      },
      onError: (errors) => {
        console.error('Error al desconectar apero:', errors);
      }
    });
  }
};

const deleteTractor = () => {
  const tractorName = `${props.tractor.brand || 'Tractor'} ${props.tractor.model || `#${props.tractor.id}`}`;
  if (confirm(`¿Estás seguro de que quieres eliminar el tractor "${tractorName}"?`)) {
    router.delete(route('user.tractors.destroy', props.tractor.id), {
      onSuccess: () => {
        router.visit(route('user.tractors.index'));
      },
      onError: (errors) => {
        console.error('Error al eliminar tractor:', errors);
      }
    });
  }
};
</script>