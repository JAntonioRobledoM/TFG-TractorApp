<template>
  <div class="min-h-screen bg-gradient-to-b from-green-50 to-green-100">
    <Layout>
      <template #header>
        <h2 class="font-semibold text-2xl text-white leading-tight">
          {{ pageTitle }}
        </h2>
      </template>

      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <!-- Filters and Sorting Section -->
          <div class="bg-white overflow-hidden shadow-lg rounded-lg mb-6 p-4">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
              <!-- Filter tabs -->
              <div class="flex space-x-2">
                <Link 
                  :href="route('user.listings.index')" 
                  class="px-4 py-2 rounded-md text-sm font-medium transition-colors"
                  :class="!type ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                >
                  Todos
                </Link>
                <Link 
                  :href="route('user.listings.sales')" 
                  class="px-4 py-2 rounded-md text-sm font-medium transition-colors"
                  :class="type === 'sale' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                >
                  Venta
                </Link>
                <Link 
                  :href="route('user.listings.rentals')" 
                  class="px-4 py-2 rounded-md text-sm font-medium transition-colors"
                  :class="type === 'rental' ? 'bg-yellow-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                >
                  Alquiler
                </Link>
              </div>

              <!-- Search and sort -->
              <div class="flex items-center space-x-2 w-full md:w-auto">
                <div class="relative flex-grow">
                  <input 
                    v-model="search" 
                    type="text" 
                    placeholder="Buscar anuncios..." 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                  />
                  <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                  </div>
                </div>
                <select 
                  v-model="sortBy" 
                  class="rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                >
                  <option value="newest">Más recientes</option>
                  <option value="oldest">Más antiguos</option>
                  <option value="priceAsc">Precio: menor a mayor</option>
                  <option value="priceDesc">Precio: mayor a menor</option>
                </select>
              </div>
            </div>
          </div>

          <!-- No results message -->
          <div v-if="!listings || listings.length === 0 || filteredListings.length === 0" class="bg-white overflow-hidden shadow-lg rounded-lg p-12 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">No se encontraron anuncios</h3>
            <p class="text-gray-500">
              No hay anuncios que coincidan con tu búsqueda. Intenta cambiar los filtros o busca otro término.
            </p>
          </div>

          <!-- Listings Grid -->
          <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div 
              v-for="listing in filteredListings" 
              :key="listing.id" 
              class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-300"
            >
              <!-- Listing Card -->
              <div class="h-48 bg-gray-200 relative">
                <div class="absolute top-2 right-2 px-3 py-1 rounded-full text-xs font-bold"
                  :class="listing.type === 'sale' ? 'bg-blue-600 text-white' : 'bg-yellow-500 text-white'">
                  {{ listing.type === 'sale' ? 'Venta' : 'Alquiler' }}
                </div>
              </div>
              
              <div class="p-5">
                <h3 class="font-semibold text-lg text-green-800 mb-2 truncate">
                  {{ getTractorName(listing) }}
                </h3>
                
                <div class="flex items-center text-gray-600 text-sm mb-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  </svg>
                  <span class="truncate">{{ getSellerName(listing) }}</span>
                </div>
                
                <div v-if="listing.type === 'rental' && listing.start_date && listing.end_date" class="flex items-center text-gray-600 text-sm mb-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  <span>{{ formatDate(listing.start_date) }} - {{ formatDate(listing.end_date) }}</span>
                </div>
                
                <div class="font-bold text-xl text-green-600 mb-4">
                  {{ formatCurrency(listing.price) }}
                  <span v-if="listing.type === 'rental'" class="text-sm font-normal text-gray-500">/día</span>
                </div>
                
                <p v-if="listing.description" class="text-gray-600 text-sm mb-4 line-clamp-2">
                  {{ listing.description }}
                </p>
                
                <Link 
                  :href="route('user.listings.show', listing.id)" 
                  class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded transition duration-200"
                >
                  Ver Detalle
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </Layout>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import Layout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
  listings: {
    type: Array,
    default: () => []
  },
  type: {
    type: String,
    default: null
  }
});

const search = ref('');
const sortBy = ref('newest');

const pageTitle = computed(() => {
  if (props.type === 'sale') return 'Tractores en Venta';
  if (props.type === 'rental') return 'Tractores en Alquiler';
  return 'Todos los Anuncios';
});

const filteredListings = computed(() => {
  if (!props.listings || !Array.isArray(props.listings)) {
    return [];
  }
  
  let results = [...props.listings];
  
  // Filter by search term
  if (search.value) {
    const searchTerm = search.value.toLowerCase();
    results = results.filter(listing => {
      if (!listing) return false;
      
      const tractorInfo = getTractorName(listing).toLowerCase();
      const sellerInfo = getSellerName(listing).toLowerCase();
      const description = listing.description ? listing.description.toLowerCase() : '';
      
      return tractorInfo.includes(searchTerm) || 
             sellerInfo.includes(searchTerm) || 
             description.includes(searchTerm);
    });
  }
  
  // Sort results
  if (results.length > 0) {
    switch (sortBy.value) {
      case 'newest':
        results.sort((a, b) => new Date(b.created_at || 0) - new Date(a.created_at || 0));
        break;
      case 'oldest':
        results.sort((a, b) => new Date(a.created_at || 0) - new Date(b.created_at || 0));
        break;
      case 'priceAsc':
        results.sort((a, b) => (a.price || 0) - (b.price || 0));
        break;
      case 'priceDesc':
        results.sort((a, b) => (b.price || 0) - (a.price || 0));
        break;
    }
  }
  
  return results;
});

function getTractorName(listing) {
  if (!listing) return 'Tractor';
  if (!listing.tractor) return 'Tractor';
  
  const brand = listing.tractor.brand || '';
  const model = listing.tractor.model || '';
  
  if (brand && model) {
    return `${brand} ${model}`;
  } else if (brand) {
    return brand;
  } else if (model) {
    return model;
  }
  
  return 'Tractor';
}

function getSellerName(listing) {
  if (!listing) return 'Vendedor';
  if (!listing.seller) return 'Vendedor';
  
  const firstName = listing.seller.first_name || '';
  const lastName = listing.seller.last_name || '';
  
  if (firstName && lastName) {
    return `${firstName} ${lastName}`;
  } else if (firstName) {
    return firstName;
  } else if (lastName) {
    return lastName;
  }
  
  return 'Vendedor';
}

function formatCurrency(value) {
  if (!value) return '---';
  return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(value);
}

function formatDate(dateString) {
  if (!dateString) return '';
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' }).format(date);
}
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

/* Line clamp utility for description */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;  
  overflow: hidden;
}
</style>