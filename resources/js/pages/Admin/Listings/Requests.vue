<template>
  <div class="min-h-screen bg-gradient-to-b from-green-50 to-green-100">
    <AdminLayout>
      <template #header>
        <div class="flex items-center justify-between">
          <h2 class="font-semibold text-2xl text-white leading-tight">
            Solicitudes de Anuncio
          </h2>
          <div class="flex space-x-2">
            <Link :href="route('listings.show', listing.id)" class="px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-md transition duration-200 inline-flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
              </svg>
              Volver al Anuncio
            </Link>
          </div>
        </div>
      </template>

      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <!-- Flash message -->
          <div v-if="$page.props.flash && $page.props.flash.message" class="bg-green-100 border-l-4 border-green-600 text-green-700 p-4 mb-6 rounded-md">
            <div class="flex">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
              </div>
              <div class="ml-3">
                <p class="text-sm">{{ $page.props.flash.message }}</p>
              </div>
            </div>
          </div>

          <!-- Listing summary -->
          <div class="bg-white overflow-hidden shadow-lg rounded-lg mb-6">
            <div class="p-6">
              <div class="flex flex-col md:flex-row md:items-center justify-between">
                <div class="flex items-center mb-4 md:mb-0">
                  <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M19 8h-2c0-.6-.4-1-1-1h-2c-.6 0-1 .4-1 1h-2c-1.7 0-3 1.3-3 3v3c0 1.7 1.3 3 3 3h8c1.7 0 3-1.3 3-3v-3c0-1.7-1.3-3-3-3zm-2 9c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3zM5 11c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"/>
                    </svg>
                  </div>
                  <div>
                    <h3 class="text-xl font-semibold text-green-800">
                      {{ listing.tractor ? listing.tractor.model : 'Tractor #' + listing.tractor_id }}
                      <span class="ml-2 px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                        :class="listing.type === 'sale' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800'">
                        {{ listing.type === 'sale' ? 'Venta' : 'Alquiler' }}
                      </span>
                      <span class="ml-1 px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                        :class="listing.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'">
                        {{ listing.is_active ? 'Activo' : 'Inactivo' }}
                      </span>
                    </h3>
                    <p class="text-gray-600">
                      Vendedor: {{ listing.seller ? listing.seller.first_name + ' ' + listing.seller.last_name : 'Usuario #' + listing.seller_id }}
                    </p>
                  </div>
                </div>
                <div class="text-2xl font-bold text-green-700">
                  {{ formatCurrency(listing.price) }}
                </div>
              </div>
            </div>
          </div>

          <!-- Requests Table -->
          <div class="bg-white overflow-hidden shadow-lg rounded-lg">
            <div class="p-6 bg-white border-b border-green-200 flex flex-col md:flex-row justify-between items-start md:items-center">
              <div>
                <h3 class="font-semibold text-xl text-green-800">Solicitudes Recibidas</h3>
                <p class="text-gray-600 mt-1">Total: {{ requests.length }} solicitudes</p>
              </div>
              <div class="mt-4 md:mt-0">
                <select v-model="filter" class="rounded-md border-green-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                  <option value="all">Todos los estados</option>
                  <option value="pending">Pendientes</option>
                  <option value="accepted">Aceptadas</option>
                  <option value="rejected">Rechazadas</option>
                  <option value="completed">Completadas</option>
                </select>
              </div>
            </div>

            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-green-200">
                <thead class="bg-green-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">
                      Solicitante
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">
                      Tipo
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">
                      Precio Ofrecido
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">
                      Periodo Solicitado
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">
                      Estado
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">
                      Fecha
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">
                      Acciones
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-green-200">
                  <tr v-for="request in filteredRequests" :key="request.id" class="hover:bg-green-50 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                          <span class="text-green-800 font-semibold">
                            {{ request.requester.first_name ? request.requester.first_name.charAt(0) + request.requester.last_name.charAt(0) : '?' }}
                          </span>
                        </div>
                        <div class="ml-4">
                          <div class="text-sm font-medium text-gray-900">
                            {{ request.requester.first_name && request.requester.last_name ? 
                               request.requester.first_name + ' ' + request.requester.last_name : 
                               request.requester.username || 'Usuario #' + request.requester_id }}
                          </div>
                          <div class="text-xs text-gray-500">
                            {{ request.requester.email || request.requester.username }}
                          </div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full" 
                        :class="request.type === 'sale' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800'">
                        {{ request.type === 'sale' ? 'Compra' : 'Alquiler' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900 font-semibold">{{ formatCurrency(request.offered_price) }}</div>
                      <div v-if="request.offered_price !== listing.price" class="text-xs">
                        <span :class="request.offered_price > listing.price ? 'text-green-600' : 'text-red-600'">
                          {{ calculatePriceDifference(request.offered_price, listing.price) }}
                        </span>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      <div v-if="request.type === 'rental' && request.requested_start_date">
                        {{ formatDate(request.requested_start_date) }} - {{ formatDate(request.requested_end_date) }}
                      </div>
                      <div v-else>
                        N/A
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full" 
                        :class="{
                          'bg-yellow-100 text-yellow-800': request.status === 'pending',
                          'bg-green-100 text-green-800': request.status === 'accepted',
                          'bg-red-100 text-red-800': request.status === 'rejected',
                          'bg-blue-100 text-blue-800': request.status === 'completed'
                        }">
                        {{ requestStatusText(request.status) }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ formatDateWithTime(request.created_at) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <div class="flex space-x-2">
                        <Link :href="route('requests.show', request.id)" class="text-blue-600 hover:text-blue-900">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                          </svg>
                        </Link>
                        <button 
                          v-if="request.status === 'pending'" 
                          @click="updateRequestStatus(request.id, 'accepted')" 
                          class="text-green-600 hover:text-green-900"
                          title="Aceptar solicitud">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                          </svg>
                        </button>
                        <button 
                          v-if="request.status === 'pending'" 
                          @click="updateRequestStatus(request.id, 'rejected')" 
                          class="text-red-600 hover:text-red-900"
                          title="Rechazar solicitud">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                          </svg>
                        </button>
                        <button 
                          v-if="request.status === 'accepted'" 
                          @click="updateRequestStatus(request.id, 'completed')" 
                          class="text-blue-600 hover:text-blue-900"
                          title="Marcar como completada">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                          </svg>
                        </button>
                      </div>
                    </td>
                  </tr>
                  <tr v-if="filteredRequests.length === 0">
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                      No se encontraron solicitudes que coincidan con los filtros.
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Confirmation modal -->
      <div v-if="showModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg max-w-md w-full p-6 shadow-xl">
          <div class="mb-4">
            <h3 class="text-lg font-medium text-gray-900">Confirmar acción</h3>
            <p class="text-sm text-gray-500 mt-2">
              {{ modalMessage }}
            </p>
          </div>
          <div class="mt-5 flex justify-end">
            <button @click="showModal = false" class="mr-3 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
              Cancelar
            </button>
            <button @click="confirmAction" class="px-4 py-2 text-sm font-medium text-white border border-transparent rounded-md"
              :class="{
                'bg-green-600 hover:bg-green-700': pendingAction === 'accepted',
                'bg-red-600 hover:bg-red-700': pendingAction === 'rejected',
                'bg-blue-600 hover:bg-blue-700': pendingAction === 'completed'
              }">
              Confirmar
            </button>
          </div>
        </div>
      </div>
    </AdminLayout>
  </div>
</template>

<script>
import { Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref, computed } from 'vue';

export default {
  components: {
    Link,
    AdminLayout,
  },
  props: {
    listing: Object,
    requests: Array,
  },
  setup(props) {
    const filter = ref('all');
    const showModal = ref(false);
    const pendingRequestId = ref(null);
    const pendingAction = ref(null);
    const modalMessage = ref('');
    
    const filteredRequests = computed(() => {
      if (filter.value === 'all') {
        return props.requests;
      }
      
      return props.requests.filter(request => request.status === filter.value);
    });
    
    const requestStatusText = (status) => {
      switch (status) {
        case 'pending': return 'Pendiente';
        case 'accepted': return 'Aceptada';
        case 'rejected': return 'Rechazada';
        case 'completed': return 'Completada';
        default: return status;
      }
    };
    
    const formatCurrency = (value) => {
      if (!value) return '---';
      return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(value);
    };
    
    const formatDate = (dateString) => {
      if (!dateString) return '---';
      const date = new Date(dateString);
      return new Intl.DateTimeFormat('es-ES', { dateStyle: 'medium' }).format(date);
    };
    
    const formatDateWithTime = (dateString) => {
      if (!dateString) return '---';
      const date = new Date(dateString);
      return new Intl.DateTimeFormat('es-ES', { dateStyle: 'medium', timeStyle: 'short' }).format(date);
    };
    
    const calculatePriceDifference = (offeredPrice, listingPrice) => {
      const difference = offeredPrice - listingPrice;
      const percentage = (difference / listingPrice) * 100;
      
      if (difference > 0) {
        return `+${difference.toFixed(2)}€ (+${percentage.toFixed(1)}%)`;
      } else {
        return `${difference.toFixed(2)}€ (${percentage.toFixed(1)}%)`;
      }
    };
    
    const updateRequestStatus = (requestId, newStatus) => {
      pendingRequestId.value = requestId;
      pendingAction.value = newStatus;
      
      switch (newStatus) {
        case 'accepted':
          modalMessage.value = '¿Estás seguro de que deseas aceptar esta solicitud?';
          break;
        case 'rejected':
          modalMessage.value = '¿Estás seguro de que deseas rechazar esta solicitud?';
          break;
        case 'completed':
          modalMessage.value = '¿Estás seguro de que deseas marcar esta solicitud como completada?';
          break;
      }
      
      showModal.value = true;
    };
    
    const confirmAction = () => {
      router.patch(route('requests.update-status', pendingRequestId.value), {
        status: pendingAction.value
      }, {
        onSuccess: () => {
          showModal.value = false;
          pendingRequestId.value = null;
          pendingAction.value = null;
        }
      });
    };
    
    return {
      filter,
      filteredRequests,
      showModal,
      pendingRequestId,
      pendingAction,
      modalMessage,
      requestStatusText,
      formatCurrency,
      formatDate,
      formatDateWithTime,
      calculatePriceDifference,
      updateRequestStatus,
      confirmAction,
    };
  }
};
</script>

<style src="../../../../css/Admin/Listings/listings_requests_style.css" scoped></style>