<template>
  <div class="min-h-screen bg-gradient-to-b from-green-50 to-green-100">
    <AdminLayout>
      <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-2xl font-bold text-green-800">Anuncios de Venta</h1>
          <div class="flex space-x-2">
            <Link 
              :href="route('listings.index')" 
              class="text-gray-600 hover:text-gray-900 px-4 py-2 rounded"
            >
              Todos los Anuncios
            </Link>
            <Link 
              :href="route('listings.create')"
              class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition"
            >
              Nuevo Anuncio
            </Link>
          </div>
        </div>

        <!-- Sales listings section -->
        <div class="bg-white shadow-md rounded-lg">
          <div class="p-6 flex justify-between items-center border-b">
            <div>
              <h2 class="text-lg font-semibold text-green-800 flex items-center">
                <span class="mr-2 inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                  </svg>
                </span>
                Tractores en Venta
              </h2>
              <p class="text-gray-600 text-sm">
                Mostrando {{ listings.length }} anuncios activos de venta
              </p>
            </div>
            <div class="relative">
              <input 
                type="text" 
                v-model="searchQuery" 
                placeholder="Buscar por modelo..."
                class="pl-8 pr-4 py-2 border rounded-md w-64 text-gray-800"
              />
              <svg 
                xmlns="http://www.w3.org/2000/svg" 
                class="h-5 w-5 absolute left-2 top-3 text-gray-400" 
                viewBox="0 0 20 20" 
                fill="currentColor"
              >
                <path 
                  fill-rule="evenodd" 
                  d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" 
                  clip-rule="evenodd" 
                />
              </svg>
            </div>
          </div>

          <!-- Grid of tractors for sale -->
          <div class="p-6">
            <div 
              v-if="filteredListings.length > 0" 
              class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
            >
              <div 
                v-for="listing in filteredListings" 
                :key="listing.id" 
                class="bg-white border border-green-100 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-200"
              >
                <!-- Tractor image/icon -->
                <div class="h-48 bg-green-50 flex items-center justify-center">
                  <div class="h-24 w-24 rounded-full bg-green-100 flex items-center justify-center">
                    <svg 
                      xmlns="http://www.w3.org/2000/svg" 
                      class="h-16 w-16 text-green-600" 
                      viewBox="0 0 24 24" 
                      fill="currentColor"
                    >
                      <path d="M19 8h-2c0-.6-.4-1-1-1h-2c-.6 0-1 .4-1 1h-2c-1.7 0-3 1.3-3 3v3c0 1.7 1.3 3 3 3h8c1.7 0 3-1.3 3-3v-3c0-1.7-1.3-3-3-3zm-2 9c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3zM5 11c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"/>
                    </svg>
                  </div>
                </div>
                
                <!-- Tractor details -->
                <div class="p-4">
                  <div class="flex justify-between items-start">
                    <div>
                      <h3 class="text-lg font-semibold text-green-800">
                        {{ listing.tractor ? listing.tractor.model : 'Tractor #' + listing.tractor_id }}
                      </h3>
                      <p class="text-gray-600 text-sm">
                        {{ listing.tractor && listing.tractor.year ? 'Año: ' + listing.tractor.year : 'Sin año especificado' }}
                      </p>
                    </div>
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                      Venta
                    </span>
                  </div>
                  
                  <div class="mt-4 flex justify-between items-center">
                    <div class="text-2xl font-bold text-green-700">
                      {{ formatCurrency(listing.price) }}
                    </div>
                    <Link 
                      :href="route('listings.show', listing.id)" 
                      class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700"
                    >
                      Ver detalle
                    </Link>
                  </div>
                  
                  <div class="mt-3 pt-3 border-t border-green-100 text-gray-500 text-sm">
                    <div class="flex items-center mb-1">
                      <svg 
                        xmlns="http://www.w3.org/2000/svg" 
                        class="h-4 w-4 mr-2" 
                        viewBox="0 0 20 20" 
                        fill="currentColor"
                      >
                        <path 
                          fill-rule="evenodd" 
                          d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" 
                          clip-rule="evenodd" 
                        />
                      </svg>
                      <span>
                        {{ listing.seller ? 
                          listing.seller.first_name + ' ' + listing.seller.last_name : 
                          'Usuario #' + listing.seller_id }}
                      </span>
                    </div>
                    <div class="flex items-center">
                      <svg 
                        xmlns="http://www.w3.org/2000/svg" 
                        class="h-4 w-4 mr-2" 
                        viewBox="0 0 20 20" 
                        fill="currentColor"
                      >
                        <path 
                          fill-rule="evenodd" 
                          d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" 
                          clip-rule="evenodd" 
                        />
                      </svg>
                      <span>{{ formatDate(listing.created_at) }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Empty state -->
            <div v-else class="text-center py-12">
              <svg 
                xmlns="http://www.w3.org/2000/svg" 
                class="h-16 w-16 text-gray-400 mx-auto mb-4" 
                viewBox="0 0 20 20" 
                fill="currentColor"
              >
                <path 
                  fill-rule="evenodd" 
                  d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" 
                  clip-rule="evenodd" 
                />
              </svg>
              <h3 class="text-lg font-semibold text-gray-600">
                No hay anuncios de venta disponibles
              </h3>
              <p class="text-gray-500 mt-2">
                No se encontraron anuncios que coincidan con tu búsqueda
              </p>
              <div class="mt-6">
                <Link 
                  :href="route('listings.create')"
                  class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition"
                >
                  Crear nuevo anuncio
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </AdminLayout>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref, computed } from 'vue';

const props = defineProps({
  listings: {
    type: Array,
    default: () => []
  }
});

const searchQuery = ref('');

const filteredListings = computed(() => {
  if (!searchQuery.value) {
    return props.listings;
  }
  
  const query = searchQuery.value.toLowerCase();
  
  return props.listings.filter(listing => {
    // Search by tractor model
    const tractorModel = listing.tractor && listing.tractor.model ? 
      listing.tractor.model.toLowerCase() : '';
      
    // Search by tractor year
    const tractorYear = listing.tractor && listing.tractor.year ? 
      listing.tractor.year.toString() : '';
      
    // Search by seller name
    const sellerName = listing.seller ? 
      `${listing.seller.first_name} ${listing.seller.last_name}`.toLowerCase() : '';
      
    return tractorModel.includes(query) || 
           tractorYear.includes(query) || 
           sellerName.includes(query);
  });
});

const formatCurrency = (value) => {
  if (!value) return '---';
  return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(value);
};

const formatDate = (dateString) => {
  if (!dateString) return '---';
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('es-ES', { dateStyle: 'medium' }).format(date);
};
</script>

<style scoped>
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

.shadow-lg {
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}
</style>