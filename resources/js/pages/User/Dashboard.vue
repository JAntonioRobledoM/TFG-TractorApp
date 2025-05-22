<!-- resources/js/Pages/User/Dashboard.vue -->
<template>
    <div class="min-h-screen bg-gradient-to-b from-green-50 to-green-100">
        <PublicLayout>
            <template #header>
                <h2 class="text-2xl font-semibold text-white">Mi panel de control</h2>
            </template>

            <div class="py-12">
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <!-- Bienvenida al usuario -->
                    <div class="mb-8 overflow-hidden rounded-lg bg-white shadow-lg">
                        <div class="flex items-center space-x-4 border-b border-gray-200 p-6">
                            <div class="rounded-full bg-green-100 p-3">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-8 w-8 text-green-600"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                    />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800">Bienvenido/a, {{ $page.props.auth.user.first_name }}</h2>
                                <p class="text-gray-600">Gestiona tus tractores, anuncios y solicitudes desde aquí.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Secciones principales -->
                    <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-3">
                        <!-- Mis Tractores -->
                        <div class="overflow-hidden rounded-lg bg-white shadow-lg">
                            <div class="flex items-center space-x-2 bg-green-700 p-4 font-medium text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M19 8h-2c0-.6-.4-1-1-1h-2c-.6 0-1 .4-1 1h-2c-1.7 0-3 1.3-3 3v3c0 1.7 1.3 3 3 3h8c1.7 0 3-1.3 3-3v-3c0-1.7-1.3-3-3-3zm-2 9c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3zM5 11c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"
                                    />
                                </svg>
                                <span>Mis Tractores</span>
                            </div>
                            <div class="p-4">
                                <div v-if="userTractors && userTractors.length > 0" class="space-y-3">
                                    <div
                                        v-for="tractor in userTractors"
                                        :key="tractor.id"
                                        class="cursor-pointer rounded-md border-b border-gray-100 p-2 pb-2 transition-colors duration-200 last:border-b-0 last:pb-0 hover:bg-green-50"
                                        @click="showTractorDetails(tractor)"
                                    >
                                        <div class="font-medium text-gray-800">{{ tractor.brand }} {{ tractor.model }}</div>
                                        <div class="text-sm text-gray-600">
                                            {{ tractor.year ? 'Año: ' + tractor.year : 'Sin año especificado' }}
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="py-4 text-center text-gray-500">No tienes tractores registrados</div>
                                <div class="mt-4">
                                    <button
                                        @click="handleShowCreateTractorForm"
                                        class="flex w-full items-center justify-center rounded-md bg-green-100 py-2 text-green-700 transition duration-200 hover:bg-green-200"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                        Añadir tractor
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Mis Anuncios -->
                        <div class="overflow-hidden rounded-lg bg-white shadow-lg">
                            <div class="flex items-center space-x-2 bg-green-700 p-4 font-medium text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" />
                                    <path
                                        d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"
                                    />
                                </svg>
                                <span>Mis Anuncios</span>
                            </div>
                            <div class="p-4">
                                <div v-if="userListings && userListings.length > 0" class="space-y-3">
                                    <div
                                        v-for="listing in userListings"
                                        :key="listing.id"
                                        class="cursor-pointer rounded-md border-b border-gray-100 p-2 pb-2 transition-colors duration-200 last:border-b-0 last:pb-0 hover:bg-green-50"
                                        @click="showListingDetails(listing)"
                                    >
                                        <!-- Contenido del anuncio -->
                                    </div>
                                </div>
                                <div v-else class="py-4 text-center text-gray-500">No tienes anuncios publicados</div>
                                <div class="mt-4">
                                    <button
                                        @click="handleShowCreateListingForm"
                                        class="flex w-full items-center justify-center rounded-md bg-green-100 py-2 text-green-700 transition duration-200 hover:bg-green-200"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                        Publicar anuncio
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Mis Solicitudes -->
                        <div class="overflow-hidden rounded-lg bg-white shadow-lg">
                            <div class="flex items-center space-x-2 bg-green-700 p-4 font-medium text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path
                                        fill-rule="evenodd"
                                        d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v7h-2l-1 2H8l-1-2H5V5z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                                <span>Mis Solicitudes</span>
                            </div>
                            <div class="p-4">
                                <div v-if="userRequests && userRequests.length > 0" class="space-y-3">
                                    <div
                                        v-for="request in userRequests"
                                        :key="request.id"
                                        class="cursor-pointer rounded-md border-b border-gray-100 p-2 pb-2 transition-colors duration-200 last:border-b-0 last:pb-0 hover:bg-green-50"
                                        @click="showRequestDetails(request)"
                                    >
                                        <div class="flex items-center font-medium text-gray-800">
                                            <span class="mr-2">Solicitud #{{ request.id }}</span>
                                            <span
                                                class="inline-flex items-center rounded px-2 py-0.5 text-xs font-medium"
                                                :class="{
                                                    'bg-yellow-100 text-yellow-800': request.status === 'pending',
                                                    'bg-green-100 text-green-800': request.status === 'accepted',
                                                    'bg-red-100 text-red-800': request.status === 'rejected',
                                                    'bg-blue-100 text-blue-800': request.status === 'completed',
                                                }"
                                            >
                                                {{ requestStatusText(request.status) }}
                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            {{ request.type === 'sale' ? 'Compra' : 'Alquiler' }} -
                                            <span class="font-bold text-green-600">{{ formatCurrency(request.offered_price) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="py-4 text-center text-gray-500">No tienes solicitudes</div>
                                <div class="mt-4">
                                    <button
                                        @click="handleShowCreateRequestForm"
                                        class="flex w-full items-center justify-center rounded-md bg-green-100 py-2 text-green-700 transition duration-200 hover:bg-green-200"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                        Nueva solicitud
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actividad reciente -->
                    <div class="overflow-hidden rounded-lg bg-white shadow-lg">
                        <div class="bg-green-700 p-4 font-medium text-white">Actividad reciente</div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <div class="mr-3 rounded-full bg-green-100 p-2">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5 text-green-600"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-800">Has iniciado sesión</div>
                                        <div class="text-xs text-gray-500">Hace unos momentos</div>
                                    </div>
                                </div>
                                <div class="border-b border-gray-100 pb-2"></div>

                                <div class="text-center text-gray-500">No hay más actividades recientes</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </PublicLayout>

        <!-- Modal para mostrar detalles del tractor -->
        <TractorShow
            v-if="selectedTractor"
            :tractor="selectedTractor"
            @close="selectedTractor = null"
            @edit="editTractor"
            @delete="confirmDeleteTractor"
        />

        <!-- Modal para crear un nuevo tractor -->
        <TractorCreate v-if="showCreateTractorForm" @close="showCreateTractorForm = false" @created="handleTractorCreated" />

        <!-- Modal para mostrar detalles del anuncio -->
        <ListingShow
            v-if="selectedListing"
            :listing="selectedListing"
            @close="selectedListing = null"
            @edit="startEditingListing"
            @delete="confirmDeleteListing"
            @toggle-status="toggleListingStatus"
            @view-request="showRequestDetails"
        />

        <!-- Modal para crear un nuevo anuncio -->
        <ListingCreate
            v-if="showCreateListingForm"
            :user-tractors="userTractors"
            @close="showCreateListingForm = false"
            @created="handleListingCreated"
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

        <!-- Modal para crear una nueva solicitud -->
        <RequestCreate
            v-if="showCreateRequestForm"
            :available-listings="availableListings"
            @close="showCreateRequestForm = false"
            @created="handleRequestCreated"
        />

        <!-- Modal de confirmación para eliminar -->
        <DeleteConfirmation v-if="itemToDelete" :item-type="itemToDelete.type" @confirm="deleteConfirmed" @cancel="cancelDelete" />
    </div>
</template>

<script setup lang="ts">
import DeleteConfirmation from '@/Components/User/DeleteConfirmation.vue';
import ListingCreate from '@/Components/User/ListingCreate.vue';
import ListingShow from '@/Components/User/ListingShow.vue';
import RequestCreate from '@/Components/User/RequestCreate.vue';
import RequestShow from '@/Components/User/RequestShow.vue';
import TractorCreate from '@/Components/User/TractorCreate.vue';
import TractorShow from '@/Components/User/TractorShow.vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { ref } from 'vue';

// Propiedades del componente
const props = defineProps({
    userTractors: Array,
    userListings: Array,
    userRequests: Array,
    availableListings: {
        type: Array,
        default: () => [],
    },
});

// Estados para los modales y selecciones
const selectedTractor = ref(null);
const selectedListing = ref(null);
const isEditingListing = ref(false);
const selectedRequest = ref(null);
const showCreateTractorForm = ref(false);
const showCreateListingForm = ref(false);
const showCreateRequestForm = ref(false);
const itemToDelete = ref(null);

// Formateador de moneda
const formatCurrency = (value) => {
    if (!value) return '---';
    return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(value);
};

// Texto para el estado de la solicitud
const requestStatusText = (status) => {
    switch (status) {
        case 'pending':
            return 'Pendiente';
        case 'accepted':
            return 'Aceptada';
        case 'rejected':
            return 'Rechazada';
        case 'completed':
            return 'Completada';
        case 'cancelled':
            return 'Cancelada';
        default:
            return status;
    }
};

// Funciones para tractores
const showTractorDetails = (tractor) => {
    selectedTractor.value = tractor;
};

const handleShowCreateTractorForm = () => {
    showCreateTractorForm.value = true;
};

const handleTractorCreated = () => {
    showCreateTractorForm.value = false;
    router.reload();
};

const editTractor = (tractor) => {
    selectedTractor.value = null;
    // Implementar lógica para editar tractor
};

const confirmDeleteTractor = (tractor) => {
    selectedTractor.value = null;
    itemToDelete.value = { type: 'tractor', item: tractor };
};

// Funciones para mostrar y editar anuncios
const showListingDetails = (listing) => {
  // Cargar los datos completos del anuncio
  axios.get(route('user.listings.show', listing.id))
    .then(response => {
      // Dependiendo de cómo se estructuren los datos en la respuesta
      if (response.data.listing) {
        selectedListing.value = response.data.listing;
      } else if (response.data.props && response.data.props.listing) {
        selectedListing.value = response.data.props.listing;
      } else {
        // Si los datos vienen directamente
        selectedListing.value = response.data;
      }
      console.log('Datos del anuncio cargados:', selectedListing.value);
    })
    .catch(error => {
      console.error('Error al cargar detalles del anuncio:', error);
    });
};

const startEditingListing = (listing) => {
  isEditingListing.value = true;
  // No es necesario cerrar el modal actual, solo indicar que está en modo edición
};


const showRequestDetails = (request) => {
    // Cargar los datos completos si es necesario
    if (!request.listing) {
        axios
            .get(route('user.requests.show', request.id))
            .then((response) => {
                selectedRequest.value = response.data.props.request;
            })
            .catch((error) => {
                console.error('Error al cargar detalles de la solicitud:', error);
            });
    } else {
        selectedRequest.value = request;
    }
};

// Funciones para gestionar anuncios
const editListing = (listing) => {
    selectedListing.value = null;
    // Implementar lógica para editar anuncio
};

const confirmDeleteListing = (listing) => {
    selectedListing.value = null;
    itemToDelete.value = { type: 'listing', item: listing };
};

// Funciones para gestionar solicitudes
const cancelRequest = (request) => {
    axios
        .patch(route('user.requests.cancel', request.id))
        .then((response) => {
            // Actualizar el estado de la solicitud localmente
            request.status = 'cancelled';
            selectedRequest.value = null;

            // Actualizar la lista de solicitudes
            const index = props.userRequests.findIndex((r) => r.id === request.id);
            if (index !== -1) {
                props.userRequests[index].status = 'cancelled';
            }
        })
        .catch((error) => {
            console.error('Error al cancelar la solicitud:', error);
        });
};

// Funciones para mostrar formularios de creación
const handleShowCreateListingForm = () => {
    showCreateListingForm.value = true;
};

const handleShowCreateRequestForm = () => {
    showCreateRequestForm.value = true;
};

// Manejadores para cuando se crea un nuevo elemento
const handleListingCreated = () => {
    showCreateListingForm.value = false;
    // Recargar la página para actualizar la lista de anuncios
    router.reload();
};

const handleRequestCreated = () => {
    showCreateRequestForm.value = false;
    // Recargar la página para actualizar la lista de solicitudes
    router.reload();
};

const cancelDelete = () => {
    itemToDelete.value = null;
};

// Función para eliminar elementos con recarga automática
const deleteConfirmed = () => {
    if (itemToDelete.value.type === 'tractor') {
        const tractor = itemToDelete.value.item;
        router.delete(route('user.tractors.destroy', tractor.id), {
            onSuccess: () => {
                router.reload();
            },
        });
    } else if (itemToDelete.value.type === 'listing') {
        const listing = itemToDelete.value.item;
        router.delete(route('user.listings.destroy', listing.id), {
            onSuccess: () => {
                router.reload();
            },
        });
    } else if (itemToDelete.value.type === 'request') {
        const request = itemToDelete.value.item;
        router.delete(route('user.requests.destroy', request.id), {
            onSuccess: () => {
                router.reload();
            },
        });
    }

    // Cerrar el modal de confirmación
    itemToDelete.value = null;
};

// Función para cambiar el estado del anuncio
const toggleListingStatus = (listing) => {
    router.put(
        route('user.listings.toggle-status', listing.id),
        {},
        {
            onSuccess: () => {
                // Recargar para ver los cambios
                router.reload();
            },
        },
    );
};

// Funciones para gestionar solicitudes
const acceptRequest = (request) => {
    router.put(
        route('user.requests.accept', request.id),
        {},
        {
            onSuccess: () => {
                router.reload();
            },
        },
    );
};

const rejectRequest = (request) => {
    router.put(
        route('user.requests.reject', request.id),
        {},
        {
            onSuccess: () => {
                router.reload();
            },
        },
    );
};

const completeRequest = (request) => {
    router.put(
        route('user.requests.complete', request.id),
        {},
        {
            onSuccess: () => {
                router.reload();
            },
        },
    );
};
</script>
