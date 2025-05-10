<template>
  <div class="min-h-screen bg-gradient-to-b from-green-50 to-green-100">
    <AdminLayout>
      <template #header>
        <h2 class="font-semibold text-2xl text-white leading-tight">
          Panel de Control
        </h2>
      </template>

      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <!-- Dashboard Stats -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-lg rounded-lg p-6 border-l-4 border-green-600">
              <div class="text-gray-900 font-semibold flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                </svg>
                Total Usuarios
              </div>
              <div class="text-3xl font-bold text-green-700">{{ stats.users }}</div>
            </div>
            <div class="bg-white overflow-hidden shadow-lg rounded-lg p-6 border-l-4 border-yellow-600">
              <div class="text-gray-900 font-semibold flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-yellow-600" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M19 8h-2c0-.6-.4-1-1-1h-2c-.6 0-1 .4-1 1h-2c-1.7 0-3 1.3-3 3v3c0 1.7 1.3 3 3 3h8c1.7 0 3-1.3 3-3v-3c0-1.7-1.3-3-3-3zm-2 9c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3zM5 11c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"/>
                </svg>
                Total Tractores
              </div>
              <div class="text-3xl font-bold text-green-700">{{ stats.tractors }}</div>
            </div>
            <!-- Nuevo bloque de Aperos -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg p-6 border-l-4 border-blue-600">
              <div class="text-gray-900 font-semibold flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zm7-10a1 1 0 01.707.293l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L13.586 9H10a1 1 0 110-2h3.586l-2.293-2.293A1 1 0 0112 2z" clip-rule="evenodd" />
                </svg>
                Total Aperos
              </div>
              <div class="text-3xl font-bold text-green-700">{{ stats.aperos }}</div>
            </div>
            <div class="bg-white overflow-hidden shadow-lg rounded-lg p-6 border-l-4 border-green-600">
              <div class="text-gray-900 font-semibold flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" />
                  <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z" />
                </svg>
                Anuncios Activos
              </div>
              <div class="text-3xl font-bold text-green-700">{{ stats.activeListings }}</div>
            </div>
            <div class="bg-white overflow-hidden shadow-lg rounded-lg p-6 border-l-4 border-yellow-600">
              <div class="text-gray-900 font-semibold flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-yellow-600" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v7h-2l-1 2H8l-1-2H5V5z" clip-rule="evenodd" />
                </svg>
                Solicitudes Pendientes
              </div>
              <div class="text-3xl font-bold text-green-700">{{ stats.pendingRequests }}</div>
            </div>
          </div>

          <!-- Quick Access Modules -->
          <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Users Module -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg p-6 border-t-4 border-green-600">
              <h3 class="text-lg font-semibold text-green-800 mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                </svg>
                Usuarios
              </h3>
              <div class="space-y-2">
                <Link :href="route('users.index')" class="block w-full text-green-600 hover:text-green-800 hover:bg-green-50 px-3 py-2 rounded-md transition duration-200">
                  Ver todos los usuarios
                </Link>
                <Link :href="route('users.create')" class="block w-full text-green-600 hover:text-green-800 hover:bg-green-50 px-3 py-2 rounded-md transition duration-200">
                  Crear nuevo usuario
                </Link>
              </div>
              <div class="mt-4">
                <h4 class="text-sm font-medium text-gray-600 mb-2">Usuarios recientes</h4>
                <div v-if="recentUsers.length > 0" class="space-y-2">
                  <div v-for="user in recentUsers" :key="user.id" class="p-2 hover:bg-green-50 rounded-md transition duration-200">
                    <Link :href="route('users.show', user.id)" class="flex items-center">
                      <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center text-green-800 mr-2">
                        {{ user.first_name.charAt(0) }}{{ user.last_name.charAt(0) }}
                      </div>
                      <div>
                        <div class="text-sm font-medium text-gray-900">{{ user.first_name }} {{ user.last_name }}</div>
                        <div class="text-xs text-gray-500">{{ user.email || user.username }}</div>
                      </div>
                    </Link>
                  </div>
                </div>
                <div v-else class="text-sm text-gray-500">No hay usuarios recientes</div>
              </div>
            </div>

            <!-- Tractors Module -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg p-6 border-t-4 border-yellow-600">
              <h3 class="text-lg font-semibold text-green-800 mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-yellow-600" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M19 8h-2c0-.6-.4-1-1-1h-2c-.6 0-1 .4-1 1h-2c-1.7 0-3 1.3-3 3v3c0 1.7 1.3 3 3 3h8c1.7 0 3-1.3 3-3v-3c0-1.7-1.3-3-3-3zm-2 9c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3zM5 11c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"/>
                </svg>
                Tractores
              </h3>
              <div class="space-y-2">
                <Link :href="route('tractors.index')" class="block w-full text-green-600 hover:text-green-800 hover:bg-green-50 px-3 py-2 rounded-md transition duration-200">
                  Ver todos los tractores
                </Link>
                <Link :href="route('tractors.create')" class="block w-full text-green-600 hover:text-green-800 hover:bg-green-50 px-3 py-2 rounded-md transition duration-200">
                  Añadir nuevo tractor
                </Link>
              </div>
              <div class="mt-4">
                <h4 class="text-sm font-medium text-gray-600 mb-2">Tractores recientes</h4>
                <div v-if="recentTractors.length > 0" class="space-y-2">
                  <div v-for="tractor in recentTractors" :key="tractor.id" class="p-2 hover:bg-green-50 rounded-md transition duration-200">
                    <Link :href="route('tractors.show', tractor.id)" class="flex items-center">
                      <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-800 mr-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                          <path d="M19 8h-2c0-.6-.4-1-1-1h-2c-.6 0-1 .4-1 1h-2c-1.7 0-3 1.3-3 3v3c0 1.7 1.3 3 3 3h8c1.7 0 3-1.3 3-3v-3c0-1.7-1.3-3-3-3zm-2 9c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3zM5 11c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"/>
                        </svg>
                      </div>
                      <div>
                        <div class="text-sm font-medium text-gray-900">{{ tractor.brand ? tractor.brand + ' ' + (tractor.model || '') : 'Tractor #' + tractor.id }}</div>
                        <div class="text-xs text-gray-500">{{ tractor.year ? 'Año: ' + tractor.year : 'Sin año especificado' }}</div>
                      </div>
                    </Link>
                  </div>
                </div>
                <div v-else class="text-sm text-gray-500">No hay tractores recientes</div>
              </div>
            </div>

            <!-- Aperos Module (Nuevo) -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg p-6 border-t-4 border-blue-600">
              <h3 class="text-lg font-semibold text-green-800 mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zm7-10a1 1 0 01.707.293l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L13.586 9H10a1 1 0 110-2h3.586l-2.293-2.293A1 1 0 0112 2z" clip-rule="evenodd" />
                </svg>
                Aperos
              </h3>
              <div class="space-y-2">
                <Link :href="route('aperos.index')" class="block w-full text-green-600 hover:text-green-800 hover:bg-green-50 px-3 py-2 rounded-md transition duration-200">
                  Ver todos los aperos
                </Link>
                <Link :href="route('aperos.create')" class="block w-full text-green-600 hover:text-green-800 hover:bg-green-50 px-3 py-2 rounded-md transition duration-200">
                  Añadir nuevo apero
                </Link>
              </div>
              <div class="mt-4">
                <h4 class="text-sm font-medium text-gray-600 mb-2">Aperos recientes</h4>
                <div v-if="recentAperos && recentAperos.length > 0" class="space-y-2">
                  <div v-for="apero in recentAperos" :key="apero.id" class="p-2 hover:bg-green-50 rounded-md transition duration-200">
                    <Link :href="route('aperos.show', apero.id)" class="flex items-center">
                      <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-800 mr-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zm7-10a1 1 0 01.707.293l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L13.586 9H10a1 1 0 110-2h3.586l-2.293-2.293A1 1 0 0112 2z" clip-rule="evenodd" />
                        </svg>
                      </div>
                      <div>
                        <div class="text-sm font-medium text-gray-900">{{ apero.name || 'Apero #' + apero.id }}</div>
                        <div class="text-xs text-gray-500">{{ apero.type ? 'Tipo: ' + apero.type : (apero.brand ? 'Marca: ' + apero.brand : 'Sin tipo especificado') }}</div>
                      </div>
                    </Link>
                  </div>
                </div>
                <div v-else class="text-sm text-gray-500">No hay aperos recientes</div>
              </div>
            </div>

            <!-- Listings Module -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg p-6 border-t-4 border-green-600">
              <h3 class="text-lg font-semibold text-green-800 mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" />
                  <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z" />
                </svg>
                Anuncios
              </h3>
              <div class="space-y-2">
                <Link :href="route('listings.index')" class="block w-full text-green-600 hover:text-green-800 hover:bg-green-50 px-3 py-2 rounded-md transition duration-200">
                  Ver todos los anuncios
                </Link>
                <Link :href="route('listings.create')" class="block w-full text-green-600 hover:text-green-800 hover:bg-green-50 px-3 py-2 rounded-md transition duration-200">
                  Crear nuevo anuncio
                </Link>
                <Link :href="route('listings.sales')" class="block w-full text-green-600 hover:text-green-800 hover:bg-green-50 px-3 py-2 rounded-md transition duration-200">
                  Ver anuncios de venta
                </Link>
                <Link :href="route('listings.rentals')" class="block w-full text-green-600 hover:text-green-800 hover:bg-green-50 px-3 py-2 rounded-md transition duration-200">
                  Ver anuncios de alquiler
                </Link>
              </div>
              <div class="mt-4">
                <h4 class="text-sm font-medium text-gray-600 mb-2">Anuncios recientes</h4>
                <div v-if="recentListings.length > 0" class="space-y-2">
                  <div v-for="listing in recentListings" :key="listing.id" class="p-2 hover:bg-green-50 rounded-md transition duration-200">
                    <Link :href="route('listings.show', listing.id)" class="flex items-center">
                      <div class="w-8 h-8 rounded-full flex items-center justify-center mr-2"
                        :class="listing.type === 'sale' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800'">
                        {{ listing.type === 'sale' ? 'V' : 'A' }}
                      </div>
                      <div>
                        <div class="text-sm font-medium text-gray-900">
                          {{ listing.type === 'sale' ? 'Venta' : 'Alquiler' }}: {{ (listing.tractor && listing.tractor.model) || 'Tractor #' + listing.tractor_id }}
                        </div>
                        <div class="text-xs text-gray-500">{{ formatCurrency(listing.price) }}</div>
                      </div>
                    </Link>
                  </div>
                </div>
                <div v-else class="text-sm text-gray-500">No hay anuncios recientes</div>
              </div>
            </div>
          </div>

          <!-- Recent Activity and Requests -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
            <!-- Recent Requests -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg p-6 border-t-4 border-yellow-600">
              <h3 class="text-lg font-semibold text-green-800 mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-yellow-600" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v7h-2l-1 2H8l-1-2H5V5z" clip-rule="evenodd" />
                </svg>
                Solicitudes Recientes
              </h3>
              <div class="space-y-2">
                <Link :href="route('requests.index')" class="block w-full text-green-600 hover:text-green-800 hover:bg-green-50 px-3 py-2 rounded-md transition duration-200 mb-4">
                  Ver todas las solicitudes
                </Link>
              </div>
              <div class="space-y-3">
                <div v-for="request in recentRequests" :key="request.id" class="border-b border-green-100 pb-3 last:border-b-0">
                  <Link :href="route('requests.show', request.id)" class="hover:text-green-600">
                    <div class="flex items-center mb-1">
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mr-2"
                        :class="{
                          'bg-yellow-100 text-yellow-800': request.status === 'pending',
                          'bg-green-100 text-green-800': request.status === 'accepted',
                          'bg-red-100 text-red-800': request.status === 'rejected',
                          'bg-blue-100 text-blue-800': request.status === 'completed'
                        }">
                        {{ requestStatusText(request.status) }}
                      </span>
                      <span class="text-sm font-medium text-gray-900">
                        {{ request.type === 'sale' ? 'Compra' : 'Alquiler' }}
                      </span>
                    </div>
                    <div class="text-xs text-gray-600">
                      De: {{ request.requester ? (request.requester.first_name + ' ' + request.requester.last_name) : 'Usuario #' + request.requester_id }}
                    </div>
                    <div class="text-xs text-gray-600">
                      Precio ofrecido: {{ formatCurrency(request.offered_price) }}
                    </div>
                    <div v-if="request.message" class="text-xs text-gray-500 mt-1 truncate">
                      {{ request.message }}
                    </div>
                  </Link>
                </div>
                <div v-if="recentRequests.length === 0" class="text-sm text-gray-500">
                  No hay solicitudes recientes
                </div>
              </div>
            </div>

            <!-- Notifications -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg p-6 border-t-4 border-green-600">
              <h3 class="text-lg font-semibold text-green-800 mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                </svg>
                Notificaciones Recientes
              </h3>
              <div class="space-y-2">
                <Link :href="route('notifications.index')" class="block w-full text-green-600 hover:text-green-800 hover:bg-green-50 px-3 py-2 rounded-md transition duration-200 mb-4">
                  Ver todas las notificaciones
                </Link>
              </div>
              <div class="space-y-3">
                <div v-for="notification in recentNotifications" :key="notification.id" class="border-b border-green-100 pb-3 last:border-b-0">
                  <Link :href="route('notifications.show', notification.id)" class="hover:text-green-600">
                    <div class="flex items-center justify-between mb-1">
                      <span class="text-sm font-medium" :class="notification.is_read ? 'text-gray-600' : 'text-green-900 font-semibold'">
                        {{ notification.type }}
                      </span>
                      <span v-if="!notification.is_read" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        No leída
                      </span>
                    </div>
                    <div class="text-xs text-gray-600 truncate">
                      {{ notification.message }}
                    </div>
                    <div class="text-xs text-gray-500 mt-1">
                      {{ formatDate(notification.created_at) }}
                    </div>
                  </Link>
                </div>
                <div v-if="recentNotifications.length === 0" class="text-sm text-gray-500">
                  No hay notificaciones recientes
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </AdminLayout>
  </div>
</template>

<script lang="ts">
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const component = {
  components: {
    Link,
    AdminLayout,
  },
  props: {
    stats: Object,
    recentUsers: Array,
    recentTractors: Array,
    recentAperos: Array, // Nuevo prop para aperos
    recentListings: Array,
    recentRequests: Array,
    recentNotifications: Array,
  },
  methods: {
    formatCurrency(value) {
      if (!value) return '---';
      return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(value);
    },
    formatDate(dateString) {
      if (!dateString) return '';
      const date = new Date(dateString);
      return new Intl.DateTimeFormat('es-ES', { dateStyle: 'medium', timeStyle: 'short' }).format(date);
    },
    requestStatusText(status) {
      switch (status) {
        case 'pending': return 'Pendiente';
        case 'accepted': return 'Aceptada';
        case 'rejected': return 'Rechazada';
        case 'completed': return 'Completada';
        default: return status;
      }
    }
  }
};

export default component;
</script>

<style scoped>
/* Add subtle field texture to the background */
.bg-gradient-to-b {
    background-image: 
        linear-gradient(to bottom, rgba(240, 253, 244, 0.95), rgba(220, 252, 231, 0.95)),
        repeating-linear-gradient(
            45deg,
            rgba(34, 197, 94, 0.03) 0px,
            rgba(34, 197, 94, 0.03) 10px,
            transparent 10px,
            transparent 20px
        );
}

/* Add subtle pattern to the stat cards */
.shadow-lg {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

/* Add tractor wheel pattern to borders */
.border-t-4 {
    border-top-width: 4px;
}

.border-l-4 {
    border-left-width: 4px;
}

.hover\:bg-green-50:hover {
    background-color: rgba(240, 253, 244, 0.9);
    transition: background-color 0.2s ease-in-out;
}
</style>