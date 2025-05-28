<template>
  <div class="min-h-screen bg-gradient-to-b from-green-50 to-green-100">
    <AdminLayout>
      <template #header>
        <h2 class="font-semibold text-2xl text-white leading-tight">
          Anuncios
        </h2>
      </template>

      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-lg rounded-lg">
            <!-- Header with filters and add button -->
            <div class="p-6 bg-white border-b border-green-200 flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
              <div>
                <h3 class="font-semibold text-xl text-green-800">Lista de Anuncios</h3>
                <p class="text-gray-600 mt-1">Gestiona todos los anuncios de venta y alquiler de tractores</p>
              </div>
              <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                <!-- Filters -->
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                  <select v-model="filters.type" class="rounded-md border-green-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 text-gray-700">
                    <option value="">Todos los tipos</option>
                    <option value="sale">Venta</option>
                    <option value="rental">Alquiler</option>
                  </select>
                  <select v-model="filters.active" class="rounded-md border-green-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 text-gray-700">
                    <option value="">Todos los estados</option>
                    <option value="true">Activos</option>
                    <option value="false">Inactivos</option>
                  </select>
                  
                  <!-- Filtro de vendedor -->
                  <select v-if="groupedBySeller.length > 1" v-model="filters.seller" class="rounded-md border-green-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 text-gray-700">
                    <option value="">Todos los vendedores</option>
                    <option v-for="seller in groupedBySeller" :key="seller.id" :value="seller.id">
                      {{ seller.name }}
                    </option>
                  </select>
                </div>
                <!-- Create button -->
                <Link :href="route('listings.create')" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition duration-200 inline-flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                  </svg>
                  Nuevo Anuncio
                </Link>
              </div>
            </div>

            <!-- Acceso rápido a solo ventas o solo alquileres -->
            <div class="px-6 py-3 bg-green-50 border-b border-green-200">
              <div class="flex flex-wrap gap-2">
                <Link :href="route('listings.sales')" class="px-3 py-1 text-sm rounded-full bg-blue-100 text-blue-800 hover:bg-blue-200 transition">
                  Ver solo ventas
                </Link>
                <Link :href="route('listings.rentals')" class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-800 hover:bg-yellow-200 transition">
                  Ver solo alquileres
                </Link>
                <Link :href="route('listings.index')" class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-800 hover:bg-green-200 transition">
                  Ver todos
                </Link>
              </div>
            </div>

            <!-- Flash message -->
            <div v-if="$page.props.flash && $page.props.flash.message" class="bg-green-100 border-l-4 border-green-600 text-green-700 p-4 mb-4 mx-6 mt-4 rounded-md">
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

            <!-- Mensaje de error si existe -->
            <div v-if="$page.props.flash && $page.props.flash.error" class="bg-red-100 border-l-4 border-red-600 text-red-700 p-4 mb-4 mx-6 mt-4 rounded-md">
              <div class="flex">
                <div class="flex-shrink-0">
                  <svg class="h-5 w-5 text-red-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                  </svg>
                </div>
                <div class="ml-3">
                  <p class="text-sm">{{ $page.props.flash.error }}</p>
                </div>
              </div>
            </div>

            <!-- Listings Table -->
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-green-200">
                <thead class="bg-green-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">
                      ID
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">
                      Tractor
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">
                      Vendedor
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">
                      Tipo
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">
                      Precio
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
                  <tr v-for="listing in filteredListings" :key="listing.id" class="hover:bg-green-50 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                      {{ listing.id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19 8h-2c0-.6-.4-1-1-1h-2c-.6 0-1 .4-1 1h-2c-1.7 0-3 1.3-3 3v3c0 1.7 1.3 3 3 3h8c1.7 0 3-1.3 3-3v-3c0-1.7-1.3-3-3-3zm-2 9c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3zM5 11c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"/>
                          </svg>
                        </div>
                        <div class="ml-4">
                          <div class="text-sm font-medium text-gray-900">
                            {{ listing.tractor ? listing.tractor.model : 'Tractor #' + listing.tractor_id }}
                          </div>
                          <div class="text-sm text-gray-500">
                            {{ listing.tractor && listing.tractor.year ? 'Año: ' + listing.tractor.year : 'Sin año especificado' }}
                          </div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">
                        {{ listing.seller ? `${listing.seller.first_name} ${listing.seller.last_name}` : 'Usuario #' + listing.seller_id }}
                      </div>
                      <div class="text-sm text-gray-500">
                        {{ listing.seller ? listing.seller.email || listing.seller.username : '' }}
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full" 
                        :class="listing.type === 'sale' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800'">
                        {{ listing.type === 'sale' ? 'Venta' : 'Alquiler' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                      {{ formatCurrency(listing.price) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full" 
                        :class="listing.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'">
                        {{ listing.is_active ? 'Activo' : 'Inactivo' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      <div v-if="listing.type === 'rental' && listing.start_date">
                        {{ formatDate(listing.start_date) }} - {{ formatDate(listing.end_date) }}
                      </div>
                      <div v-else>
                        {{ formatDate(listing.created_at) }}
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <div class="flex space-x-2">
                        <Link :href="route('listings.show', listing.id)" class="text-blue-600 hover:text-blue-900" title="Ver detalles">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                          </svg>
                        </Link>
                        <Link :href="route('listings.edit', listing.id)" class="text-green-600 hover:text-green-900" title="Editar">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                          </svg>
                        </Link>
                        <Link :href="route('listings.requests', listing.id)" class="text-yellow-600 hover:text-yellow-900" title="Ver solicitudes">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v7h-2l-1 2H8l-1-2H5V5z" clip-rule="evenodd" />
                          </svg>
                        </Link>
                        <button @click="confirmDelete(listing)" class="text-red-600 hover:text-red-900" title="Eliminar">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                          </svg>
                        </button>
                      </div>
                    </td>
                  </tr>
                  <tr v-if="filteredListings.length === 0">
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                      No se encontraron anuncios que coincidan con los filtros.
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Delete confirmation modal -->
      <div v-if="showDeleteModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg max-w-md w-full p-6 shadow-xl">
          <div class="mb-4">
            <h3 class="text-lg font-medium text-gray-900">Confirmar eliminación</h3>
            <p class="text-sm text-gray-500 mt-2">
              ¿Estás seguro de que deseas eliminar este anuncio? Esta acción no se puede deshacer.
            </p>
            <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-md text-sm text-yellow-800">
              <p class="font-medium">Detalles del anuncio:</p>
              <p v-if="listingToDelete">
                <strong>Tractor:</strong> {{ listingToDelete.tractor ? listingToDelete.tractor.model : 'Tractor #' + listingToDelete.tractor_id }}<br>
                <strong>Tipo:</strong> {{ listingToDelete && listingToDelete.type === 'sale' ? 'Venta' : 'Alquiler' }}<br>
                <strong>Precio:</strong> {{ listingToDelete ? formatCurrency(listingToDelete.price) : '' }}
              </p>
            </div>
          </div>
          <div class="mt-5 flex justify-end">
            <button @click="showDeleteModal = false" class="mr-3 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
              Cancelar
            </button>
            <form @submit.prevent="deleteListing">
              <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700" :disabled="deleting">
                {{ deleting ? 'Eliminando...' : 'Eliminar' }}
              </button>
            </form>
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
    listings: Array,
  },
  setup(props) {
    const filters = ref({
      type: '',
      active: '',
      seller: '',
    });
    
    // Obtener vendedores únicos para el filtro
    const groupedBySeller = computed(() => {
      const sellers = {};
      
      props.listings.forEach(listing => {
        if (listing.seller) {
          const sellerId = listing.seller.id;
          if (!sellers[sellerId]) {
            sellers[sellerId] = {
              id: sellerId,
              name: `${listing.seller.first_name} ${listing.seller.last_name}`,
            };
          }
        }
      });
      
      return Object.values(sellers);
    });
    
    const filteredListings = computed(() => {
      return props.listings.filter(listing => {
        let matchesType = true;
        let matchesActive = true;
        let matchesSeller = true;
        
        if (filters.value.type !== '') {
          matchesType = listing.type === filters.value.type;
        }
        
        if (filters.value.active !== '') {
          matchesActive = listing.is_active === (filters.value.active === 'true');
        }
        
        if (filters.value.seller !== '') {
          matchesSeller = listing.seller && listing.seller.id === parseInt(filters.value.seller);
        }
        
        return matchesType && matchesActive && matchesSeller;
      });
    });
    
    const showDeleteModal = ref(false);
    const listingToDelete = ref(null);
    const deleting = ref(false);
    
    const confirmDelete = (listing) => {
      listingToDelete.value = listing;
      showDeleteModal.value = true;
    };
    
    const deleteListing = () => {
      deleting.value = true;
      router.delete(route('listings.destroy', listingToDelete.value.id), {
        onFinish: () => {
          showDeleteModal.value = false;
          listingToDelete.value = null;
          deleting.value = false;
        }
      });
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
    
    return {
      filters,
      filteredListings,
      groupedBySeller,
      showDeleteModal,
      listingToDelete,
      deleting,
      confirmDelete,
      deleteListing,
      formatCurrency,
      formatDate,
    };
  }
};
</script>

<style src="../../../../css/Admin/Listings/listings_index_style.css" scoped></style>