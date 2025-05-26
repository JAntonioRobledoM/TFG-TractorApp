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
                <!-- Imagen del tractor -->
                <div v-if="tractor.image_url" class="bg-gray-50 rounded-lg p-6">
                  <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Imagen del Tractor
                  </h2>
                  <div class="relative">
                    <img 
                      :src="tractor.image_url" 
                      :alt="`${tractor.brand || 'Tractor'} ${tractor.model || ''}`"
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
                    
                    <!-- Horas de trabajo con botón para actualizar -->
                    <div class="flex items-center space-x-2">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      <span class="text-sm font-medium text-gray-600">Horas de trabajo:</span>
                      <span class="text-sm text-gray-900">{{ tractor.working_hours || 0 }} horas</span>
                      <button 
                        @click="showHoursModal = true"
                        class="ml-2 text-xs text-blue-600 hover:text-blue-800 border border-blue-300 rounded-md px-2 py-1"
                      >
                        Actualizar
                      </button>
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
                      @click="showEditModal = true"
                      class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                      Editar Tractor
                    </button>
                    
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

    <!-- Modal para ver imagen en tamaño completo -->
    <div v-if="showImageModal && tractor.image_url" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4">
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
          :src="tractor.image_url" 
          :alt="`${tractor.brand || 'Tractor'} ${tractor.model || ''}`"
          class="max-w-full max-h-full object-contain rounded-lg"
        />
      </div>
    </div>

    <!-- Modal para actualizar horas de trabajo -->
    <div v-if="showHoursModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <h3 class="text-lg font-medium text-gray-900 text-center mt-4">Actualizar Horas de Trabajo</h3>
          <div class="mt-4">
            <form @submit.prevent="updateHours">
              <div class="mb-4">
                <label for="current_hours" class="block text-sm font-medium text-gray-700 mb-2">
                  Horas actuales
                </label>
                <div class="text-sm text-gray-600 bg-gray-100 p-2 rounded-md mb-3">
                  {{ tractor.working_hours || 0 }} horas
                </div>
                
                <label for="working_hours" class="block text-sm font-medium text-gray-700 mb-2">
                  Nuevas horas de trabajo
                </label>
                <input 
                  id="working_hours" 
                  v-model="hoursForm.working_hours"
                  type="number"
                  min="0"
                  step="0.1"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 text-gray-900"
                  placeholder="Ej: 1250.5"
                  required
                />
                <p class="text-xs text-gray-500 mt-1">
                  Introduce el valor actual de las horas de trabajo del tractor.
                </p>
              </div>
              <div class="flex items-center justify-end space-x-2">
                <button 
                  type="button"
                  @click="showHoursModal = false"
                  class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                >
                  Cancelar
                </button>
                <button 
                  type="submit"
                  class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                >
                  Actualizar
                </button>
              </div>
            </form>
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

    <!-- Modal para editar tractor -->
    <div v-if="showEditModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
          </div>
          <h3 class="text-lg font-medium text-gray-900 text-center mt-4">Editar Tractor</h3>
          <div class="mt-6">
            <form @submit.prevent="updateTractor" class="space-y-4" enctype="multipart/form-data">
              <!-- Campo de imagen -->
              <div>
                <label for="edit_image" class="block text-sm font-medium text-gray-700 mb-1">
                  Imagen del Tractor
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-green-400 transition-colors">
                  <div class="space-y-1 text-center">
                    <div v-if="editImagePreview || tractor.image_url" class="mb-4">
                      <img 
                        :src="editImagePreview || tractor.image_url" 
                        alt="Preview" 
                        class="mx-auto h-32 w-32 object-cover rounded-lg" 
                      />
                      <div v-if="tractor.image_url && !editImagePreview" class="mt-2">
                        <label class="inline-flex items-center">
                          <input 
                            v-model="editForm.remove_image"
                            type="checkbox"
                            class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500"
                          />
                          <span class="ml-2 text-sm text-red-600">Eliminar imagen actual</span>
                        </label>
                      </div>
                    </div>
                    <svg v-else class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                      <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex text-sm text-gray-600">
                      <label for="edit_image" class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500">
                        <span>{{ editImagePreview || tractor.image_url ? 'Cambiar imagen' : 'Subir una imagen' }}</span>
                        <input 
                          id="edit_image" 
                          @change="handleEditImageChange"
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
                  <label for="edit_brand" class="block text-sm font-medium text-gray-700 mb-1">
                    Marca
                  </label>
                  <input 
                    id="edit_brand" 
                    v-model="editForm.brand"
                    type="text"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 text-gray-900"
                    placeholder="Ej: John Deere, Massey Ferguson"
                  />
                </div>
                <div>
                  <label for="edit_model" class="block text-sm font-medium text-gray-700 mb-1">
                    Modelo
                  </label>
                  <input 
                    id="edit_model" 
                    v-model="editForm.model"
                    type="text"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 text-gray-900"
                    placeholder="Ej: 5075E, MF 4707"
                  />
                </div>
                <div>
                  <label for="edit_year" class="block text-sm font-medium text-gray-700 mb-1">
                    Año
                  </label>
                  <input 
                    id="edit_year" 
                    v-model="editForm.year"
                    type="number"
                    min="1900"
                    :max="new Date().getFullYear()"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 text-gray-900"
                    placeholder="Ej: 2020"
                  />
                </div>
                <div>
                  <label for="edit_horsepower" class="block text-sm font-medium text-gray-700 mb-1">
                    Potencia (HP)
                  </label>
                  <input 
                    id="edit_horsepower" 
                    v-model="editForm.horsepower"
                    type="number"
                    min="0"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 text-gray-900"
                    placeholder="Ej: 75"
                  />
                </div>
                <div>
                  <label for="edit_working_hours" class="block text-sm font-medium text-gray-700 mb-1">
                    Horas de trabajo
                  </label>
                  <input 
                    id="edit_working_hours" 
                    v-model="editForm.working_hours"
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
                      v-model="editForm.is_available"
                      type="checkbox"
                      class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500 text-gray-900"
                    />
                    <span class="ml-2 text-sm text-gray-700">Disponible para uso</span>
                  </label>
                </div>
              </div>
              <div>
                <label for="edit_description" class="block text-sm font-medium text-gray-700 mb-1">
                  Descripción
                </label>
                <textarea 
                  id="edit_description" 
                  v-model="editForm.description"
                  rows="3"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 text-gray-900"
                  placeholder="Descripción adicional del tractor..."
                ></textarea>
              </div>
              <div class="flex items-center justify-end space-x-3 pt-4">
                <button 
                  type="button"
                  @click="closeEditModal"
                  class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                >
                  Cancelar
                </button>
                <button 
                  type="submit"
                  class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                >
                  Guardar Cambios
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
    image?: string;
    image_url?: string;
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
const showHoursModal = ref(false);
const showEditModal = ref(false);
const showImageModal = ref(false);
const editImagePreview = ref<string | null>(null);
const selectedEditImage = ref<File | null>(null);

const connectAperoForm = reactive({
  apero_id: ''
});
const hoursForm = reactive({
  working_hours: props.tractor.working_hours || 0
});
const editForm = reactive({
  brand: props.tractor.brand || '',
  model: props.tractor.model || '',
  year: props.tractor.year || null,
  description: props.tractor.description || '',
  horsepower: props.tractor.horsepower || null,
  working_hours: props.tractor.working_hours || 0,
  is_available: props.tractor.is_available,
  image: null as File | null,
  remove_image: false
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

const handleEditImageChange = (event: Event) => {
  const target = event.target as HTMLInputElement;
  const file = target.files?.[0];
  
  if (file) {
    selectedEditImage.value = file;
    editForm.image = file;
    editForm.remove_image = false;
    
    // Crear preview
    const reader = new FileReader();
    reader.onload = (e) => {
      editImagePreview.value = e.target?.result as string;
    };
    reader.readAsDataURL(file);
  }
};

const closeEditModal = () => {
  showEditModal.value = false;
  editImagePreview.value = null;
  selectedEditImage.value = null;
  
  // Reset form to original values
  editForm.brand = props.tractor.brand || '';
  editForm.model = props.tractor.model || '';
  editForm.year = props.tractor.year || null;
  editForm.description = props.tractor.description || '';
  editForm.horsepower = props.tractor.horsepower || null;
  editForm.working_hours = props.tractor.working_hours || 0;
  editForm.is_available = props.tractor.is_available;
  editForm.image = null;
  editForm.remove_image = false;
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

// Nueva función para actualizar solo las horas de trabajo
const updateHours = () => {
  router.put(route('user.tractors.update-hours', props.tractor.id), hoursForm, {
    onSuccess: () => {
      showHoursModal.value = false;
    },
    onError: (errors) => {
      console.error('Error al actualizar horas de trabajo:', errors);
    }
  });
};

// Función para actualizar el tractor completo
const updateTractor = () => {
  // Crear FormData para manejar la subida de archivos
  const formData = new FormData();
  
  // Agregar todos los campos del formulario
  Object.keys(editForm).forEach(key => {
    if (key === 'image' && selectedEditImage.value) {
      formData.append(key, selectedEditImage.value);
    } else if (key !== 'image' && editForm[key] !== null && editForm[key] !== '') {
      formData.append(key, editForm[key]);
    }
  });

  // Agregar el método PUT para Laravel
  formData.append('_method', 'PUT');

  router.post(route('user.tractors.update', props.tractor.id), formData, {
    forceFormData: true,
    onSuccess: () => {
      closeEditModal();
    },
    onError: (errors) => {
      console.error('Error al actualizar tractor:', errors);
    }
  });
};

const deleteTractor = () => {
  const tractorName = `${props.tractor.brand || 'Tractor'} ${props.tractor.model || `#${props.tractor.id}`}`;
  
  if (confirm(`¿Estás seguro de que quieres eliminar el tractor "${tractorName}"? Esta acción no se puede deshacer.`)) {
    router.delete(route('user.tractors.destroy', props.tractor.id), {
      onSuccess: () => {
        // Redirigir a la lista de tractores después de eliminar
        router.visit(route('user.tractors.index'), {
          onFinish: () => {
            console.log('Redirigido a lista de tractores');
          }
        });
      },
      onError: (errors) => {
        console.error('Error al eliminar tractor:', errors);
      }
    });
  }
};
</script>