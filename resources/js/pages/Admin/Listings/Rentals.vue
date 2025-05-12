<template>
  <AdminLayout>
    <div class="container mx-auto p-6">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-green-800">Anuncios de Alquiler</h1>
        <div class="flex space-x-2">
          <Link :href="route('listings.index')" class="text-gray-600 hover:text-gray-900 px-4 py-2 rounded">
          Todos los Anuncios
          </Link>
          <Link :href="route('listings.create')"
            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
          Nuevo Anuncio
          </Link>
        </div>
      </div>

      <!-- Calendar view -->
      <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6">
          <h3 class="text-lg font-semibold text-green-800 mb-4">
            Calendario de Disponibilidad
          </h3>
          <div>
            <div class="flex justify-between mb-4">
              <button @click="prevMonth" class="text-green-600 hover:text-green-800">
                &lt; Anterior
              </button>
              <h4 class="text-lg font-medium text-gray-800">
                {{ currentMonthName }} {{ currentYear }}
              </h4>
              <button @click="nextMonth" class="text-green-600 hover:text-green-800">
                Siguiente &gt;
              </button>
            </div>
            <div class="grid grid-cols-7 gap-1 mb-2">
              <div v-for="day in ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom']" :key="day"
                class="text-center text-sm font-medium text-gray-600">
                {{ day }}
              </div>
            </div>
            <div class="grid grid-cols-7 gap-1">
              <div v-for="(day, index) in calendarDays" :key="index"
                class="text-center border rounded-md p-2 cursor-pointer" :class="getDateClass(day)"
                @click="openDayModal(day)">
                <div class="text-sm">{{ day.date }}</div>
                <div v-if="day.rentals > 0" class="text-xs mt-1">
                  {{ day.rentals }} {{ day.rentals === 1 ? 'tractor' : 'tractores' }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Rental listings section -->
      <div class="bg-white shadow-md rounded-lg">
        <div class="p-6 flex justify-between items-center border-b">
          <div>
            <h3 class="text-lg font-semibold text-green-800">
              Tractores en Alquiler
            </h3>
            <p class="text-gray-600 text-sm">
              Mostrando {{ listings.length }} anuncios activos
            </p>
          </div>
          <div class="relative">
            <input type="text" v-model="searchQuery" placeholder="Buscar..."
              class="pl-8 pr-4 py-2 border rounded-md w-64 text-gray-800" />
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-2 top-3 text-gray-400"
              viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd"
                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                clip-rule="evenodd" />
            </svg>
          </div>
        </div>

        <!-- Listings Grid -->
        <div class="p-6">
          <div v-if="filteredListings.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="listing in filteredListings" :key="listing.id"
              class="border rounded-lg overflow-hidden shadow-md hover:shadow-lg transition">
              <div class="bg-green-50 p-6 flex justify-center items-center">
                <div class="w-24 h-24 bg-yellow-100 rounded-full flex items-center justify-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-yellow-600" viewBox="0 0 24 24"
                    fill="currentColor">
                    <path
                      d="M19 8h-2c0-.6-.4-1-1-1h-2c-.6 0-1 .4-1 1h-2c-1.7 0-3 1.3-3 3v3c0 1.7 1.3 3 3 3h8c1.7 0 3-1.3 3-3v-3c0-1.7-1.3-3-3-3zm-2 9c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3zM5 11c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z" />
                  </svg>
                </div>
              </div>
              <div class="p-4">
                <div class="flex justify-between items-start mb-2">
                  <div>
                    <h3 class="text-lg font-semibold text-green-800">
                      {{ listing.tractor ? listing.tractor.model : 'Tractor #' + listing.tractor_id }}
                    </h3>
                    <p class="text-gray-600 text-sm">
                      {{ listing.tractor && listing.tractor.year ? 'Año: ' + listing.tractor.year : 'Sin año' }}
                    </p>
                  </div>
                  <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">
                    Alquiler
                  </span>
                </div>

                <div class="bg-yellow-50 rounded p-2 mb-2">
                  <div class="flex items-center text-sm text-yellow-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20"
                      fill="currentColor">
                      <path fill-rule="evenodd"
                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                        clip-rule="evenodd" />
                    </svg>
                    <span>
                      {{ formatDate(listing.start_date) }} - {{ formatDate(listing.end_date) }}
                    </span>
                  </div>
                </div>

                <div class="flex justify-between items-center mt-4">
                  <div class="text-2xl font-bold text-green-700">
                    {{ formatCurrency(listing.price) }}
                  </div>
                  <Link :href="route('listings.show', listing.id)"
                    class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">
                  Ver detalle
                  </Link>
                </div>

                <div class="mt-3 pt-3 border-t border-green-100 text-gray-500 text-sm">
                  <div class="flex items-center mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20"
                      fill="currentColor">
                      <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                        clip-rule="evenodd" />
                    </svg>
                    <span>
                      {{ listing.seller ?
                        listing.seller.first_name + ' ' + listing.seller.last_name :
                        'Usuario #' + listing.seller_id }}
                    </span>
                  </div>
                  <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20"
                      fill="currentColor">
                      <path fill-rule="evenodd"
                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                        clip-rule="evenodd" />
                    </svg>
                    <span>{{ formatDate(listing.created_at) }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Empty state -->
          <div v-else class="text-center py-12">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-4" viewBox="0 0 20 20"
              fill="currentColor">
              <path
                d="M8 5a1 1 0 100 2h5.586l-1.293 1.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L13.586 5H8zM12 15a1 1 0 100-2H6.414l1.293-1.293a1 1 0 10-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L6.414 15H12z" />
            </svg>
            <h3 class="text-lg font-semibold text-gray-600">
              No hay anuncios de alquiler disponibles
            </h3>
            <p class="text-gray-500 mt-2">
              No se encontraron anuncios que coincidan con tu búsqueda
            </p>
            <div class="mt-6">
              <Link :href="route('listings.create')"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
              Crear nuevo anuncio
              </Link>
            </div>
          </div>
        </div>
      </div>

      <!-- Day Details Modal -->
      <div v-if="selectedDayRentals"
  class="fixed inset-0 bg-green-900 backdrop-blur-sm z-50 flex items-center justify-center p-4"
  @click.self="closeDayModal">
        <div class="bg-white rounded-lg max-w-2xl w-full max-h-[80vh] overflow-y-auto" @click.stop>
          <div class="p-6 border-b flex justify-between items-center">
            <h2 class="text-xl font-semibold text-green-800">
              Tractores en Alquiler - {{ formatSelectedDate }}
            </h2>
            <button @click="closeDayModal" class="text-gray-600 hover:text-gray-900">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <div v-if="selectedDayRentals.length" class="p-6">
            <div v-for="listing in selectedDayRentals" :key="listing.id"
              class="bg-green-50 rounded-lg p-4 mb-4 last:mb-0">
              <div class="flex justify-between items-start">
                <div>
                  <h3 class="text-lg font-semibold text-green-800">
                    {{ listing.tractor ? listing.tractor.model : 'Tractor #' + listing.tractor_id }}
                  </h3>
                  <p class="text-gray-600 text-sm">
                    {{ listing.tractor && listing.tractor.year ? 'Año: ' + listing.tractor.year : 'Sin año' }}
                  </p>
                </div>
                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">
                  Alquiler
                </span>
              </div>

              <div class="mt-2 bg-white rounded p-2">
                <div class="flex justify-between">
                  <div>
                    <p class="text-sm text-gray-600">Periodo de Alquiler</p>
                    <p class="font-medium">
                      {{ formatDate(listing.start_date) }} - {{ formatDate(listing.end_date) }}
                    </p>
                  </div>
                  <div>
                    <p class="text-sm text-gray-600">Precio</p>
                    <p class="font-bold text-green-700">
                      {{ formatCurrency(listing.price) }}
                    </p>
                  </div>
                </div>
              </div>

              <div class="mt-3 flex justify-between items-center">
                <div class="flex items-center text-sm text-gray-500">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                      clip-rule="evenodd" />
                  </svg>
                  <span>
                    {{ listing.seller ?
                      listing.seller.first_name + ' ' + listing.seller.last_name :
                      'Usuario #' + listing.seller_id }}
                  </span>
                </div>
                <Link :href="route('listings.show', listing.id)"
                  class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">
                Ver detalle
                </Link>
              </div>
            </div>
          </div>

          <div v-else class="p-6 text-center text-gray-500">
            No hay tractores en alquiler para esta fecha.
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref, computed, onMounted } from 'vue';

const props = defineProps({
  listings: {
    type: Array,
    default: () => []
  }
});

const searchQuery = ref('');
const currentMonth = ref(new Date().getMonth());
const currentYear = ref(new Date().getFullYear());
const selectedDay = ref(null);
const selectedDayRentals = ref(null);

const openDayModal = (day) => {
  if (!day.empty && day.date) {
    selectedDay.value = day;
    const selectedDate = new Date(currentYear.value, currentMonth.value, day.date);

    // Find listings that include this date
    selectedDayRentals.value = props.listings.filter(listing => {
      if (!listing.start_date || !listing.end_date) return false;

      const startDate = new Date(listing.start_date);
      const endDate = new Date(listing.end_date);

      return selectedDate >= startDate && selectedDate <= endDate;
    });
  }
};

const closeDayModal = () => {
  selectedDay.value = null;
  selectedDayRentals.value = null;
};

const formatSelectedDate = computed(() => {
  if (!selectedDay.value) return '';
  return new Date(currentYear.value, currentMonth.value, selectedDay.value.date)
    .toLocaleDateString('es-ES', {
      weekday: 'long',
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
});

// Calendar calculations
const daysInMonth = computed(() => {
  return new Date(currentYear.value, currentMonth.value + 1, 0).getDate();
});

const firstDayOfMonth = computed(() => {
  let firstDay = new Date(currentYear.value, currentMonth.value, 1).getDay();
  return firstDay === 0 ? 6 : firstDay - 1;
});

const currentMonthName = computed(() => {
  const months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
  return months[currentMonth.value];
});

const calendarDays = computed(() => {
  const days = [];

  // Add empty cells for days before the start of the month
  for (let i = 0; i < firstDayOfMonth.value; i++) {
    days.push({ date: '', rentals: 0, empty: true });
  }

  // Add days of the month
  for (let day = 1; day <= daysInMonth.value; day++) {
    const date = new Date(currentYear.value, currentMonth.value, day);
    // Count rentals for this day
    const rentalCount = countRentalsForDate(date);
    days.push({
      date: day,
      rentals: rentalCount,
      empty: false,
      today: isToday(date),
      hasRentals: rentalCount > 0
    });
  }

  // Add empty cells to complete the grid
  const totalCells = days.length;
  const remainder = totalCells % 7;
  if (remainder > 0) {
    const emptyCells = 7 - remainder;
    for (let i = 0; i < emptyCells; i++) {
      days.push({ date: '', rentals: 0, empty: true });
    }
  }

  return days;
});

const isToday = (date) => {
  const today = new Date();
  return date.getDate() === today.getDate() &&
    date.getMonth() === today.getMonth() &&
    date.getFullYear() === today.getFullYear();
};

const countRentalsForDate = (date) => {
  if (!props.listings) return 0;

  return props.listings.filter(listing => {
    if (!listing.start_date || !listing.end_date) return false;

    const startDate = new Date(listing.start_date);
    const endDate = new Date(listing.end_date);

    // Check if the date is within the rental period
    return date >= startDate && date <= endDate;
  }).length;
};

const getDateClass = (day) => {
  if (day.empty) {
    return 'bg-gray-50 text-gray-300';
  }

  if (day.today) {
    return 'bg-green-100 text-green-800 border-green-500';
  }

  if (day.hasRentals) {
    return 'bg-yellow-50 text-yellow-800 border-yellow-200';
  }

  return 'bg-white text-gray-700 hover:bg-gray-50';
};

const prevMonth = () => {
  if (currentMonth.value === 0) {
    currentMonth.value = 11;
    currentYear.value--;
  } else {
    currentMonth.value--;
  }
};

const nextMonth = () => {
  if (currentMonth.value === 11) {
    currentMonth.value = 0;
    currentYear.value++;
  } else {
    currentMonth.value++;
  }
};

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