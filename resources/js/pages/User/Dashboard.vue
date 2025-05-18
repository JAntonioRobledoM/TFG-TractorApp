<!-- resources/js/Pages/User/Dashboard.vue -->
<template>
  <div class="min-h-screen bg-gradient-to-b from-green-50 to-green-100">
    <PublicLayout>
      <template #header>
        <h2 class="text-2xl font-semibold text-white">
          Mi panel de control
        </h2>
      </template>

      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <!-- Bienvenida al usuario -->
          <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-8">
            <div class="p-6 border-b border-gray-200 flex items-center space-x-4">
              <div class="bg-green-100 p-3 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
              </div>
              <div>
                <h2 class="text-xl font-semibold text-gray-800">
                  Bienvenido/a, {{ $page.props.auth.user.first_name }}
                </h2>
                <p class="text-gray-600">
                  Gestiona tus tractores, anuncios y solicitudes desde aquí.
                </p>
              </div>
            </div>
          </div>

          <!-- Secciones principales -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Mis Tractores -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
              <div class="p-4 bg-green-700 text-white font-medium flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M19 8h-2c0-.6-.4-1-1-1h-2c-.6 0-1 .4-1 1h-2c-1.7 0-3 1.3-3 3v3c0 1.7 1.3 3 3 3h8c1.7 0 3-1.3 3-3v-3c0-1.7-1.3-3-3-3zm-2 9c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3zM5 11c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"/>
                </svg>
                <span>Mis Tractores</span>
              </div>
              <div class="p-4">
                <div v-if="userTractors && userTractors.length > 0" class="space-y-3">
                  <div v-for="tractor in userTractors" :key="tractor.id" class="border-b border-gray-100 pb-2 last:border-b-0 last:pb-0">
                    <div class="font-medium text-gray-800">
                      {{ tractor.brand }} {{ tractor.model }}
                    </div>
                    <div class="text-sm text-gray-600">
                      {{ tractor.year ? 'Año: ' + tractor.year : 'Sin año especificado' }}
                    </div>
                  </div>
                </div>
                <div v-else class="text-gray-500 text-center py-4">
                  No tienes tractores registrados
                </div>
                <div class="mt-4">
                  <button class="w-full py-2 bg-green-100 text-green-700 hover:bg-green-200 rounded-md transition duration-200 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                    </svg>
                    Añadir tractor
                  </button>
                </div>
              </div>
            </div>

            <!-- Mis Anuncios -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
              <div class="p-4 bg-green-700 text-white font-medium flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" />
                  <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z" />
                </svg>
                <span>Mis Anuncios</span>
              </div>
              <div class="p-4">
                <div v-if="userListings && userListings.length > 0" class="space-y-3">
                  <div 
                    v-for="listing in userListings" 
                    :key="listing.id" 
                    class="border-b border-gray-100 pb-2 last:border-b-0 last:pb-0 hover:bg-green-50 cursor-pointer transition-colors duration-200 p-2 rounded-md"
                    @click="showListingDetails(listing)"
                  >
                    <div class="font-medium text-gray-800">
                      {{ listing.title || (listing.tractor ? `${listing.tractor.brand} ${listing.tractor.model}` : 'Anuncio #' + listing.id) }}
                    </div>
                    <div class="text-sm text-gray-600 flex justify-between">
                      <span>{{ listing.type === 'sale' ? 'Venta' : 'Alquiler' }}</span>
                      <span class="font-bold text-green-600">{{ formatCurrency(listing.price) }}</span>
                    </div>
                  </div>
                </div>
                <div v-else class="text-gray-500 text-center py-4">
                  No tienes anuncios publicados
                </div>
                <div class="mt-4">
                  <button @click="showCreateListingForm" class="w-full py-2 bg-green-100 text-green-700 hover:bg-green-200 rounded-md transition duration-200 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                    </svg>
                    Publicar anuncio
                  </button>
                </div>
              </div>
            </div>

            <!-- Mis Solicitudes -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
              <div class="p-4 bg-green-700 text-white font-medium flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v7h-2l-1 2H8l-1-2H5V5z" clip-rule="evenodd" />
                </svg>
                <span>Mis Solicitudes</span>
              </div>
              <div class="p-4">
                <div v-if="userRequests && userRequests.length > 0" class="space-y-3">
                  <div 
                    v-for="request in userRequests" 
                    :key="request.id" 
                    class="border-b border-gray-100 pb-2 last:border-b-0 last:pb-0 hover:bg-green-50 cursor-pointer transition-colors duration-200 p-2 rounded-md"
                    @click="showRequestDetails(request)"
                  >
                    <div class="font-medium text-gray-800 flex items-center">
                      <span class="mr-2">Solicitud #{{ request.id }}</span>
                      <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium"
                        :class="{
                          'bg-yellow-100 text-yellow-800': request.status === 'pending',
                          'bg-green-100 text-green-800': request.status === 'accepted',
                          'bg-red-100 text-red-800': request.status === 'rejected',
                          'bg-blue-100 text-blue-800': request.status === 'completed'
                        }">
                        {{ requestStatusText(request.status) }}
                      </span>
                    </div>
                    <div class="text-sm text-gray-600">
                      {{ request.type === 'sale' ? 'Compra' : 'Alquiler' }} - 
                      <span class="font-bold text-green-600">{{ formatCurrency(request.offered_price) }}</span>
                    </div>
                  </div>
                </div>
                <div v-else class="text-gray-500 text-center py-4">
                  No tienes solicitudes
                </div>
                <div class="mt-4">
                  <button @click="showCreateRequestForm" class="w-full py-2 bg-green-100 text-green-700 hover:bg-green-200 rounded-md transition duration-200 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                    </svg>
                    Nueva solicitud
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Actividad reciente -->
          <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="p-4 bg-green-700 text-white font-medium">
              Actividad reciente
            </div>
            <div class="p-6">
              <div class="space-y-4">
                <div class="flex items-start">
                  <div class="bg-green-100 p-2 rounded-full mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                    </svg>
                  </div>
                  <div>
                    <div class="text-sm font-medium text-gray-800">Has iniciado sesión</div>
                    <div class="text-xs text-gray-500">Hace unos momentos</div>
                  </div>
                </div>
                <div class="border-b border-gray-100 pb-2"></div>
                
                <div class="text-center text-gray-500">
                  No hay más actividades recientes
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </PublicLayout>

    <!-- Modal para mostrar detalles del anuncio -->
    <ListingShow 
      v-if="selectedListing" 
      :listing="selectedListing" 
      @close="selectedListing = null"
      @edit="editListing"
      @delete="confirmDeleteListing"
      @toggle-status="toggleListingStatus"
      @view-request="showRequestDetails"
    />

    <!-- Modal para mostrar detalles de la solicitud -->
    <RequestShow 
      v-if="selectedRequest" 
      :request="selectedRequest"
      :current-user-id="$page.props.auth.user.id"
      @close="selectedRequest = null"
      @cancel="cancelRequest"
      @accept="acceptRequest"
      @reject="rejectRequest"
      @complete="completeRequest"
      @view-listing="showListingDetails"
    />

    <!-- Modal de confirmación para eliminar -->
    <div v-if="showDeleteConfirmation" class="fixed inset-0 bg-gray-800 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center px-4">
      <div class="bg-white shadow-lg rounded-lg overflow-hidden max-w-md w-full mx-auto">
        <div class="p-4 bg-red-600 text-white font-medium">
          Confirmar eliminación
        </div>
        <div class="p-6">
          <p class="text-gray-700 mb-4">
            ¿Estás seguro de que deseas eliminar este elemento? Esta acción no se puede deshacer.
          </p>
          <div class="flex space-x-3">
            <button 
              @click="deleteConfirmed" 
              class="flex-1 py-2 bg-red-600 text-white hover:bg-red-700 rounded-md transition duration-200"
            >
              Eliminar
            </button>
            <button 
              @click="cancelDelete" 
              class="flex-1 py-2 bg-gray-300 text-gray-700 hover:bg-gray-400 rounded-md transition duration-200"
            >
              Cancelar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import ListingShow from '@/Components/User/ListingShow.vue';
import RequestShow from '@/Components/User/RequestShow.vue';

// Propiedades del componente
const props = defineProps({
  userTractors: Array,
  userListings: Array,
  userRequests: Array,
});

// Estados para los modales y selecciones
const selectedListing = ref(null);
const selectedRequest = ref(null);
const showDeleteConfirmation = ref(false);
const itemToDelete = ref(null);

// Formateador de moneda
const formatCurrency = (value) => {
  if (!value) return '---';
  return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(value);
};

// Texto para el estado de la solicitud
const requestStatusText = (status) => {
  switch (status) {
    case 'pending': return 'Pendiente';
    case 'accepted': return 'Aceptada';
    case 'rejected': return 'Rechazada';
    case 'completed': return 'Completada';
    case 'cancelled': return 'Cancelada';
    default: return status;
  }
};

// Funciones para mostrar detalles
const showListingDetails = (listing) => {
  // Cargar los datos completos si es necesario
  if (!listing.tractor || !listing.requests) {
    router.get(route('user.listings.show', listing.id), {}, {
      preserveState: true,
      onSuccess: (page) => {
        selectedListing.value = page.props.listing;
      }
    });
  } else {
    selectedListing.value = listing;
  }
};

const showRequestDetails = (request) => {
  // Cargar los datos completos si es necesario
  if (!request.listing) {
    router.get(route('user.requests.show', request.id), {}, {
      preserveState: true,
      onSuccess: (page) => {
        selectedRequest.value = page.props.request;
      }
    });
  } else {
    selectedRequest.value = request;
  }
};

// Funciones para gestionar anuncios
const editListing = (listing) => {
  selectedListing.value = null;
  // Implementar lógica para abrir modal de edición 
  // (se implementará en futuros desarrollos)
};

const confirmDeleteListing = (listing) => {
  itemToDelete.value = { type: 'listing', item: listing };
  showDeleteConfirmation.value = true;
};

const toggleListingStatus = (listing) => {
  router.put(route('user.listings.toggle-status', listing.id), {}, {
    onSuccess: () => {
      // Actualizar el estado del anuncio localmente
      listing.is_active = !listing.is_active;
    }
  });
};

// Funciones para gestionar solicitudes
const cancelRequest = (request) => {
  router.patch(route('user.requests.cancel', request.id), {}, {
    onSuccess: () => {
      // Actualizar el estado de la solicitud localmente
      request.status = 'cancelled';
      selectedRequest.value = null;
    }
  });
};

const acceptRequest = (request) => {
  router.put(route('user.requests.accept', request.id), {}, {
    onSuccess: () => {
      request.status = 'accepted';
    }
  });
};

const rejectRequest = (request) => {
  router.put(route('user.requests.reject', request.id), {}, {
    onSuccess: () => {
      request.status = 'rejected';
    }
  });
};

const completeRequest = (request) => {
  router.put(route('user.requests.complete', request.id), {}, {
    onSuccess: () => {
      request.status = 'completed';
    }
  });
};

// Gestión de formularios de creación
const showCreateListingForm = () => {
  // Implementar lógica para mostrar modal de creación de anuncio
  // (se implementará en futuros desarrollos)
};

const showCreateRequestForm = () => {
  // Implementar lógica para mostrar modal de creación de solicitud
  // (se implementará en futuros desarrollos)
};

// Funciones para eliminar elementos
const deleteConfirmed = () => {
  if (itemToDelete.value.type === 'listing') {
    const listing = itemToDelete.value.item;
    router.delete(route('user.listings.destroy', listing.id), {
      onSuccess: () => {
        selectedListing.value = null;
        // Actualizar la lista de anuncios eliminando el elemento del array
        props.userListings.splice(props.userListings.findIndex(l => l.id === listing.id), 1);
      }
    });
  }
  
  // Cerrar el modal de confirmación
  showDeleteConfirmation.value = false;
  itemToDelete.value = null;
};

const cancelDelete = () => {
  showDeleteConfirmation.value = false;
  itemToDelete.value = null;
};
</script>