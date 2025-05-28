<template>
  <div class="min-h-screen bg-gradient-to-b from-green-50 to-green-100">
    <UserLayout>
      <template #header>
        <h2 class="font-semibold text-2xl text-white leading-tight">
          Detalle del Anuncio
        </h2>
      </template>

      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="mb-6">
            <Link :href="route('user.listings.index')" class="text-green-600 hover:text-green-800 font-medium">
              <span class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver a todos los anuncios
              </span>
            </Link>
          </div>

          <!-- Main content -->
          <div class="bg-white overflow-hidden shadow-lg rounded-lg mb-6">
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left column: Images -->
                <div class="relative">
                  <!-- Imagen principal del tractor -->
                  <div class="bg-gray-200 rounded-lg h-80 w-full overflow-hidden">
                    <img 
                      v-if="listing.tractor && listing.tractor.image_url" 
                      :src="listing.tractor.image_url" 
                      :alt="listing.tractor ? (listing.tractor.brand + ' ' + listing.tractor.model) : 'Tractor'"
                      class="w-full h-full object-cover cursor-pointer transition-transform duration-300 hover:scale-105"
                      @click="openImageModal"
                      @error="handleImageError"
                    />
                    <!-- Placeholder cuando no hay imagen -->
                    <div 
                      v-else 
                      class="w-full h-full flex items-center justify-center bg-gray-200"
                    >
                      <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-gray-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-gray-500 text-sm">Sin imagen disponible</p>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Botón para ver imagen completa (solo si hay imagen) -->
                  <button 
                    v-if="listing.tractor && listing.tractor.image_url"
                    @click="openImageModal"
                    class="absolute bottom-2 right-2 bg-black/50 text-white px-3 py-1 rounded-md text-sm hover:bg-black/70 transition-colors backdrop-blur-sm"
                  >
                    Ver imagen completa
                  </button>
                </div>

                <!-- Right column: Info -->
                <div>
                  <div class="flex items-center mb-4">
                    <h1 class="text-2xl font-bold text-green-800 mr-3">
                      {{ listing.tractor ? (listing.tractor.brand + ' ' + listing.tractor.model) : 'Tractor' }}
                    </h1>
                    <span 
                      class="px-3 py-1 rounded-full text-xs font-bold"
                      :class="listing.type === 'sale' ? 'bg-blue-600 text-white' : 'bg-yellow-500 text-white'"
                    >
                      {{ listing.type === 'sale' ? 'Venta' : 'Alquiler' }}
                    </span>
                  </div>

                  <div class="text-3xl font-bold text-green-600 mb-4">
                    {{ formatCurrency(listing.price) }}
                    <span v-if="listing.type === 'rental'" class="text-base font-normal text-gray-500">/día</span>
                  </div>

                  <div class="bg-green-50 p-4 rounded-lg mb-6 border border-green-100">
                    <h3 class="font-semibold text-green-800 mb-2">Detalles del Tractor</h3>
                    <div v-if="listing.tractor" class="grid grid-cols-2 gap-4">
                      <div>
                        <p class="text-sm text-gray-500">Marca</p>
                        <p class="font-medium text-gray-900">{{ listing.tractor.brand }}</p>
                      </div>
                      <div>
                        <p class="text-sm text-gray-500">Modelo</p>
                        <p class="font-medium text-gray-900">{{ listing.tractor.model }}</p>
                      </div>
                      <div>
                        <p class="text-sm text-gray-500">Año</p>
                        <p class="font-medium text-gray-900">{{ listing.tractor.year || 'No especificado' }}</p>
                      </div>
                      <div>
                        <p class="text-sm text-gray-500">Potencia</p>
                        <p class="font-medium text-gray-900">{{ listing.tractor.horsepower || 'No especificado' }} CV</p>
                      </div>
                    </div>
                    <div v-else class="text-gray-500">
                      No hay información disponible sobre el tractor.
                    </div>
                  </div>

                  <div v-if="listing.type === 'rental' && listing.start_date && listing.end_date" class="mb-6">
                    <h3 class="font-semibold text-green-800 mb-2">Período de Alquiler</h3>
                    <div class="flex items-center">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                      <span>{{ formatDate(listing.start_date) }} - {{ formatDate(listing.end_date) }}</span>
                    </div>
                  </div>

                  <div class="mb-6">
                    <h3 class="font-semibold text-green-800 mb-2">Vendedor</h3>
                    <div class="flex items-center">
                      <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                        <span class="text-lg font-bold text-green-600">
                          {{ listing.seller ? listing.seller.first_name.charAt(0) : 'U' }}
                        </span>
                      </div>
                      <div>
                        <p class="font-medium text-gray-900">
                          {{ listing.seller ? (listing.seller.first_name + ' ' + listing.seller.last_name) : 'Usuario' }}
                        </p>
                        <p class="text-sm text-gray-500">
                          {{ listing.seller ? (listing.seller.email) : 'email@example.com' }}
                        </p>
                      </div>
                    </div>
                  </div>

                  <!-- Request button -->
                  <div v-if="!isOwnListing">
                    <div v-if="userRequest" class="mb-4">
                      <div class="p-3 rounded-md"
                        :class="{
                          'bg-yellow-50 text-yellow-700 border border-yellow-200': userRequest.status === 'pending',
                          'bg-green-50 text-green-700 border border-green-200': userRequest.status === 'accepted',
                          'bg-red-50 text-red-700 border border-red-200': userRequest.status === 'rejected'
                        }"
                      >
                        <div class="flex items-center">
                          <svg v-if="userRequest.status === 'pending'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                          </svg>
                          <svg v-if="userRequest.status === 'accepted'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                          </svg>
                          <svg v-if="userRequest.status === 'rejected'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                          </svg>
                          <span>
                            {{ requestStatusText[userRequest.status] }}
                          </span>
                        </div>
                      </div>
                    </div>
                    <button 
                      v-else
                      @click="submitRequest"
                      class="w-full text-center bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded transition duration-200"
                      :disabled="loading"
                    >
                      <span v-if="loading">Enviando solicitud...</span>
                      <span v-else>
                        {{ listing.type === 'sale' ? 'Solicitar Compra' : 'Solicitar Alquiler' }}
                      </span>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Description -->
          <div class="bg-white overflow-hidden shadow-lg rounded-lg mb-6">
            <div class="p-6">
              <h2 class="text-xl font-bold text-green-800 mb-4">Descripción</h2>
              <div v-if="listing.description" class="text-gray-700 prose max-w-none">
                <p>{{ listing.description }}</p>
              </div>
              <div v-else class="text-gray-500 italic">
                No hay descripción disponible para este anuncio.
              </div>
            </div>
          </div>

          <!-- Similar listings -->
          <div v-if="similarListings.length > 0" class="bg-white overflow-hidden shadow-lg rounded-lg">
            <div class="p-6">
              <h2 class="text-xl font-bold text-green-800 mb-4">Anuncios Similares</h2>
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div 
                  v-for="similar in similarListings" 
                  :key="similar.id" 
                  class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition duration-200"
                >
                  <div class="h-36 bg-gray-200 relative overflow-hidden">
                    <!-- Imagen del tractor similar -->
                    <img 
                      v-if="similar.tractor && similar.tractor.image_url" 
                      :src="similar.tractor.image_url" 
                      :alt="similar.tractor ? (similar.tractor.brand + ' ' + similar.tractor.model) : 'Tractor'"
                      class="w-full h-full object-cover"
                      @error="handleImageError"
                    />
                    <!-- Placeholder para tractores similares sin imagen -->
                    <div 
                      v-else 
                      class="w-full h-full flex items-center justify-center bg-gray-200"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                    </div>
                    
                    <div class="absolute top-2 right-2 px-2 py-1 rounded text-xs font-bold"
                      :class="similar.type === 'sale' ? 'bg-blue-600 text-white' : 'bg-yellow-500 text-white'">
                      {{ similar.type === 'sale' ? 'Venta' : 'Alquiler' }}
                    </div>
                  </div>
                  <div class="p-4">
                    <h3 class="font-semibold text-green-800 mb-1 truncate">
                      {{ similar.tractor ? (similar.tractor.brand + ' ' + similar.tractor.model) : 'Tractor' }}
                    </h3>
                    <div class="font-bold text-green-600 mb-2">
                      {{ formatCurrency(similar.price) }}
                    </div>
                    <Link 
                      :href="route('user.listings.show', similar.id)" 
                      class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-medium py-1 px-4 rounded transition duration-200 text-sm"
                    >
                      Ver Detalle
                    </Link>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal para ver imagen completa -->
      <div 
        v-if="showImageModal" 
        class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4"
        @click="closeImageModal"
      >
        <div class="relative max-w-4xl max-h-full">
          <img 
            :src="listing.tractor.image_url" 
            :alt="listing.tractor ? (listing.tractor.brand + ' ' + listing.tractor.model) : 'Tractor'"
            class="max-w-full max-h-full object-contain rounded-lg"
          />
          <button 
            @click="closeImageModal"
            class="absolute top-2 right-2 bg-black/50 text-white p-2 rounded-full hover:bg-black/70 transition-colors"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </UserLayout>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';

const props = defineProps({
  listing: Object,
  userRequest: Object
});

const page = usePage();

const loading = ref(false);
const similarListings = ref([]);
const showImageModal = ref(false);

const isOwnListing = computed(() => {
  if (!props.listing || !props.listing.seller || !props.listing.seller.id) return false;
  return props.listing.seller.id === (page.props.auth.user ? page.props.auth.user.id : null);
});

const requestStatusText = {
  'pending': 'Tu solicitud está pendiente de revisión',
  'accepted': 'Tu solicitud ha sido aceptada',
  'rejected': 'Tu solicitud ha sido rechazada',
  'completed': 'Esta transacción ha sido completada'
};

onMounted(() => {
  // In a real implementation, you would fetch similar listings from the server
  // For demonstration, we'll just set an empty array
  similarListings.value = [];
});

function formatCurrency(value) {
  if (!value) return '---';
  return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(value);
}

function formatDate(dateString) {
  if (!dateString) return '';
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' }).format(date);
}

function submitRequest() {
  if (!props.listing || !props.listing.id) return;
  
  loading.value = true;
  
  router.post(route('user.requests.store'), {
    listing_id: props.listing.id,
    message: 'Estoy interesado en este anuncio',
  }, {
    onSuccess: () => {
      loading.value = false;
    },
    onError: () => {
      loading.value = false;
    }
  });
}

function handleImageError(event) {
  // Oculta la imagen rota y muestra el placeholder
  event.target.style.display = 'none';
}

function openImageModal() {
  showImageModal.value = true;
  // Previene el scroll del body cuando el modal está abierto
  document.body.style.overflow = 'hidden';
}

function closeImageModal() {
  showImageModal.value = false;
  // Restaura el scroll del body
  document.body.style.overflow = 'auto';
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
</style>