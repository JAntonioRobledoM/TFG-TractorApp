<template>
  <div class="min-h-screen bg-gradient-to-b from-green-50 to-green-100">
    <PublicLayout>
      <template #header>
        <h2 class="font-semibold text-2xl text-white leading-tight">
          TractorApp - Compra y Alquiler de Maquinaria Agrícola
        </h2>
      </template>

      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <!-- Hero Section -->
          <div class="bg-white overflow-hidden shadow-lg rounded-lg mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2">
              <div class="p-8 flex flex-col justify-center">
                <h1 class="text-3xl md:text-4xl font-bold text-green-800 mb-4">
                  Encuentra el equipo agrícola perfecto para tus necesidades
                </h1>
                <p class="text-lg text-gray-600 mb-6">
                  Conectamos agricultores y proveedores de maquinaria agrícola en una plataforma sencilla y segura.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                  <Link :href="route('register')" class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-green-600 hover:bg-green-700 transition duration-200">
                    Regístrate Gratis
                  </Link>
                  <span class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-green-700 bg-green-100 hover:bg-green-200 transition duration-200 cursor-pointer">
                    Ver Anuncios
                  </span>
                </div>
              </div>
              <div class="bg-green-600 p-6 flex items-center justify-center">
                <div class="w-full h-64 bg-green-500 rounded-lg flex items-center justify-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-32 w-32 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M19 8h-2c0-.6-.4-1-1-1h-2c-.6 0-1 .4-1 1h-2c-1.7 0-3 1.3-3 3v3c0 1.7 1.3 3 3 3h8c1.7 0 3-1.3 3-3v-3c0-1.7-1.3-3-3-3zm-2 9c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3zM5 11c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"/>
                  </svg>
                </div>
              </div>
            </div>
          </div>

          <!-- Feature Cards -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-lg rounded-lg p-6 border-t-4 border-green-600">
              <div class="flex items-center mb-4">
                <div class="rounded-full p-3 bg-green-100 mr-4">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 8h-2c0-.6-.4-1-1-1h-2c-.6 0-1 .4-1 1h-2c-1.7 0-3 1.3-3 3v3c0 1.7 1.3 3 3 3h8c1.7 0 3-1.3 3-3v-3c0-1.7-1.3-3-3-3zm-2 9c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3zM5 11c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"/>
                  </svg>
                </div>
                <h3 class="text-lg font-semibold text-green-800">Tractores</h3>
              </div>
              <p class="text-gray-600 mb-4">
                Encuentra tractores de todas las marcas y potencias. Compra o alquila según tus necesidades.
              </p>
              <span class="text-green-600 hover:text-green-800 font-medium cursor-pointer">
                Ver tractores disponibles →
              </span>
            </div>
            
            <div class="bg-white overflow-hidden shadow-lg rounded-lg p-6 border-t-4 border-blue-600">
              <div class="flex items-center mb-4">
                <div class="rounded-full p-3 bg-blue-100 mr-4">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zm7-10a1 1 0 01.707.293l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L13.586 9H10a1 1 0 110-2h3.586l-2.293-2.293A1 1 0 0112 2z" clip-rule="evenodd" />
                  </svg>
                </div>
                <h3 class="text-lg font-semibold text-green-800">Aperos</h3>
              </div>
              <p class="text-gray-600 mb-4">
                Amplia selección de aperos para todo tipo de labores agrícolas, con opciones de compra y alquiler.
              </p>
              <span class="text-green-600 hover:text-green-800 font-medium cursor-pointer">
                Ver aperos disponibles →
              </span>
            </div>
            
            <div class="bg-white overflow-hidden shadow-lg rounded-lg p-6 border-t-4 border-yellow-600">
              <div class="flex items-center mb-4">
                <div class="rounded-full p-3 bg-yellow-100 mr-4">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" />
                    <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z" />
                  </svg>
                </div>
                <h3 class="text-lg font-semibold text-green-800">Anuncios</h3>
              </div>
              <p class="text-gray-600 mb-4">
                Explora anuncios de venta y alquiler de maquinaria agrícola, o publica los tuyos propios.
              </p>
              <span class="text-green-600 hover:text-green-800 font-medium cursor-pointer">
                Ver todos →
              </span>
            </div>
          </div>

          <!-- How It Works Section -->
          <div class="bg-white overflow-hidden shadow-lg rounded-lg p-6 mb-8">
            <h2 class="text-2xl font-bold text-green-800 mb-6 text-center">¿Cómo funciona?</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                  <span class="text-2xl font-bold text-green-600">1</span>
                </div>
                <h3 class="text-lg font-semibold text-green-800 mb-2">Regístrate</h3>
                <p class="text-gray-600">
                  Crea una cuenta gratuita en nuestra plataforma para empezar a buscar o publicar maquinaria.
                </p>
              </div>
              
              <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                  <span class="text-2xl font-bold text-green-600">2</span>
                </div>
                <h3 class="text-lg font-semibold text-green-800 mb-2">Encuentra o Publica</h3>
                <p class="text-gray-600">
                  Busca entre miles de anuncios o publica tu propio equipo para venta o alquiler.
                </p>
              </div>
              
              <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                  <span class="text-2xl font-bold text-green-600">3</span>
                </div>
                <h3 class="text-lg font-semibold text-green-800 mb-2">Conecta</h3>
                <p class="text-gray-600">
                  Contacta directamente con propietarios o interesados y cierra tu negocio de forma segura.
                </p>
              </div>
            </div>
          </div>

          <!-- Latest Listings -->
          <div class="bg-white overflow-hidden shadow-lg rounded-lg p-6 mb-8">
            <div class="flex justify-between items-center mb-6">
              <h2 class="text-2xl font-bold text-green-800">Últimos Anuncios</h2>
              <span class="text-green-600 hover:text-green-800 font-medium cursor-pointer">
                Ver todos →
              </span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div v-for="listing in latestListings" :key="listing.id" class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition duration-200">
                <div class="h-48 bg-gray-200 relative">
                  <div class="absolute top-2 right-2 px-2 py-1 rounded text-xs font-bold"
                    :class="listing.type === 'sale' ? 'bg-blue-600 text-white' : 'bg-yellow-500 text-white'">
                    {{ listing.type === 'sale' ? 'Venta' : 'Alquiler' }}
                  </div>
                </div>
                <div class="p-4">
                  <h3 class="font-semibold text-lg text-green-800 mb-2">
                    {{ listing.title || (listing.tractor && (listing.tractor.brand + ' ' + listing.tractor.model)) || 'Tractor en venta' }}
                  </h3>
                  <div class="text-gray-600 mb-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                    </svg>
                    {{ listing.location || 'Ubicación no especificada' }}
                  </div>
                  <div class="font-bold text-xl text-green-600 mb-3">
                    {{ formatCurrency(listing.price) }}
                  </div>
                  <span class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded transition duration-200 cursor-pointer">
                    Ver Detalle
                  </span>
                </div>
              </div>
              
              <div v-if="latestListings.length === 0" class="col-span-3 text-center py-8">
                <p class="text-gray-500">No hay anuncios disponibles en este momento.</p>
              </div>
            </div>
          </div>

          <!-- Testimonials Section -->
          <div class="bg-white overflow-hidden shadow-lg rounded-lg p-6 mb-8">
            <h2 class="text-2xl font-bold text-green-800 mb-6 text-center">Lo que dicen nuestros usuarios</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="border border-gray-100 rounded-lg p-6">
                <div class="flex items-center mb-4">
                  <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                    <span class="text-xl font-bold text-green-600">JM</span>
                  </div>
                  <div>
                    <h3 class="font-semibold text-green-800">Juan Martínez</h3>
                    <p class="text-sm text-gray-600">Agricultor, Sevilla</p>
                  </div>
                </div>
                <p class="text-gray-700 italic">
                  "Encontré un tractor John Deere en excelente estado a un precio mucho mejor que en los concesionarios. El proceso fue rápido y seguro."
                </p>
              </div>
              
              <div class="border border-gray-100 rounded-lg p-6">
                <div class="flex items-center mb-4">
                  <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                    <span class="text-xl font-bold text-green-600">ML</span>
                  </div>
                  <div>
                    <h3 class="font-semibold text-green-800">María López</h3>
                    <p class="text-sm text-gray-600">Cooperativa Agrícola, Valencia</p>
                  </div>
                </div>
                <p class="text-gray-700 italic">
                  "Alquilamos varios aperos para la temporada de siembra y nos ha permitido ahorrar una gran cantidad de dinero. Volveremos a utilizar la plataforma sin duda."
                </p>
              </div>
            </div>
          </div>

          <!-- CTA Section -->
          <div class="bg-green-600 overflow-hidden shadow-lg rounded-lg p-8 text-center">
            <h2 class="text-2xl md:text-3xl font-bold text-white mb-4">
              ¿Listo para encontrar tu próxima máquina agrícola?
            </h2>
            <p class="text-green-100 text-lg mb-6 max-w-3xl mx-auto">
              Únete a miles de agricultores que ya están aprovechando nuestra plataforma para comprar, vender y alquilar maquinaria agrícola.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
              <Link :href="route('register')" class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-green-700 bg-white hover:bg-green-50 transition duration-200">
                Regístrate Ahora
              </Link>
              <span class="inline-flex items-center justify-center px-6 py-3 border border-white rounded-md shadow-sm text-base font-medium text-white hover:bg-green-700 transition duration-200 cursor-pointer">
                Explorar Anuncios
              </span>
            </div>
          </div>
        </div>
      </div>
    </PublicLayout>
  </div>
</template>

<script lang="ts">
import { Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const component = {
  components: {
    Link,
    PublicLayout,
  },
  props: {
    latestListings: {
      type: Array,
      default: () => [],
    },
  },
  methods: {
    formatCurrency(value) {
      if (!value) return '---';
      return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(value);
    },
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

.hover\:bg-green-50:hover {
    background-color: rgba(240, 253, 244, 0.9);
    transition: background-color 0.2s ease-in-out;
}
</style>