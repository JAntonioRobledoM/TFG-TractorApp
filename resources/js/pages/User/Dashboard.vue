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

                    <!-- Sección DEBUG (temporal) -->
                    <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 rounded">
                        <h3 class="font-bold text-yellow-800">DEBUG INFO:</h3>
                        <p class="text-yellow-700">
                            Anuncios recibidos: {{ userListings?.length || 0 }}
                        </p>
                        <div v-if="userListings && userListings.length > 0" class="text-xs text-yellow-600">
                            <div v-for="(listing, index) in userListings" :key="index">
                                Anuncio {{ index + 1 }}: {{ listing.tractor?.brand || 'Sin marca' }} {{ listing.tractor?.model || 'Sin modelo' }} - {{ listing.price }}€
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
                                    <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z" />
                                </svg>
                                <span>Mis Anuncios</span>
                            </div>
                            <div class="p-4">
                                <div v-if="userListings && userListings.length > 0" class="space-y-3">
                                    <div
                                        v-for="listing in userListings"
                                        :key="listing.id"
                                        class="cursor-pointer rounded-md border border-gray-200 p-3 transition-colors duration-200 hover:bg-green-50 hover:border-green-300"
                                        @click="showListingDetails(listing)"
                                    >
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <div class="font-medium text-gray-800">
                                                    {{ listing.tractor ? `${listing.tractor.brand || 'Tractor'} ${listing.tractor.model || ''}` : 'Tractor no especificado' }}
                                                </div>
                                                <div class="text-sm text-gray-600 mt-1">
                                                    {{ listing.type === 'sale' ? 'Venta' : 'Alquiler' }} - 
                                                    <span class="font-bold text-green-600">{{ formatCurrency(listing.price) }}</span>
                                                    <span v-if="listing.type === 'rental'" class="text-xs">/día</span>
                                                </div>
                                                <div class="text-xs text-gray-500 mt-1">
                                                    {{ listing.requests?.length || 0 }} solicitud{{ (listing.requests?.length || 0) !== 1 ? 'es' : '' }}
                                                </div>
                                                <div v-if="listing.description" class="text-xs text-gray-500 mt-1 truncate">
                                                    {{ listing.description.length > 50 ? listing.description.substring(0, 50) + '...' : listing.description }}
                                                </div>
                                            </div>
                                            <div class="flex flex-col items-end space-y-1 ml-3">
                                                <span 
                                                    class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium"
                                                    :class="{
                                                        'bg-green-100 text-green-800': listing.is_active,
                                                        'bg-gray-100 text-gray-800': !listing.is_active
                                                    }"
                                                >
                                                    {{ listing.is_active ? 'Activo' : 'Inactivo' }}
                                                </span>
                                                <span 
                                                    class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium"
                                                    :class="{
                                                        'bg-purple-100 text-purple-800': listing.type === 'sale',
                                                        'bg-blue-100 text-blue-800': listing.type === 'rental'
                                                    }"
                                                >
                                                    {{ listing.type === 'sale' ? 'Venta' : 'Alquiler' }}
                                                </span>
                                                <div class="text-xs text-gray-400">
                                                    {{ formatDateShort(listing.created_at) }}
                                                </div>
                                            </div>
                                        </div>
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

                    <!-- Notificaciones (Solicitudes Recibidas) -->
                    <div class="mb-8 overflow-hidden rounded-lg bg-white shadow-lg">
                        <div class="flex items-center justify-between bg-orange-600 p-4 font-medium text-white">
                            <div class="flex items-center space-x-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                                </svg>
                                <span>Notificaciones</span>
                            </div>
                            <span class="rounded-full bg-orange-500 px-2 py-1 text-xs font-bold">
                                {{ getUnreadNotificationsCount() }}
                            </span>
                        </div>
                        <div class="p-4">
                            <div v-if="receivedRequests && receivedRequests.length > 0" class="space-y-3 max-h-96 overflow-y-auto">
                                <div
                                    v-for="request in receivedRequests"
                                    :key="request.id"
                                    class="cursor-pointer rounded-md border p-3 transition-colors duration-200 hover:bg-orange-50"
                                    :class="{
                                        'border-orange-200 bg-orange-25': request.status === 'pending',
                                        'border-gray-200 bg-gray-50': request.status !== 'pending'
                                    }"
                                    @click="showReceivedRequestDetails(request)"
                                >
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2 mb-2">
                                                <div class="font-medium text-gray-800">
                                                    {{ request.requester ? `${request.requester.first_name} ${request.requester.last_name}` : 'Usuario desconocido' }}
                                                </div>
                                                <span
                                                    class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium"
                                                    :class="{
                                                        'bg-yellow-100 text-yellow-800': request.status === 'pending',
                                                        'bg-green-100 text-green-800': request.status === 'accepted',
                                                        'bg-red-100 text-red-800': request.status === 'rejected',
                                                        'bg-blue-100 text-blue-800': request.status === 'completed',
                                                        'bg-gray-100 text-gray-800': request.status === 'cancelled'
                                                    }"
                                                >
                                                    {{ requestStatusText(request.status) }}
                                                </span>
                                            </div>
                                            <div class="text-sm text-gray-600 mb-1">
                                                Solicita {{ request.type === 'sale' ? 'comprar' : 'alquilar' }}:
                                                <span class="font-medium">
                                                    {{ request.listing?.tractor ? `${request.listing.tractor.brand || 'Tractor'} ${request.listing.tractor.model || ''}` : 'Tractor' }}
                                                </span>
                                            </div>
                                            <div class="text-sm text-gray-600 mb-1">
                                                Ofrece: <span class="font-bold text-green-600">{{ formatCurrency(request.offered_price) }}</span>
                                                <span v-if="request.type === 'rental'" class="text-xs">/día</span>
                                                <span v-if="request.listing?.price" class="text-gray-500">
                                                    (Tu precio: {{ formatCurrency(request.listing.price) }})
                                                </span>
                                            </div>
                                            <div v-if="request.message" class="text-xs text-gray-500 italic truncate">
                                                "{{ request.message.length > 60 ? request.message.substring(0, 60) + '...' : request.message }}"
                                            </div>
                                        </div>
                                        <div class="flex flex-col items-end space-y-1 ml-3">
                                            <div class="text-xs text-gray-400">
                                                {{ formatDateShort(request.created_at) }}
                                            </div>
                                            <div v-if="request.status === 'pending'" class="flex space-x-1">
                                                <button
                                                    @click.stop="acceptReceivedRequest(request)"
                                                    class="inline-flex items-center px-2 py-1 bg-green-100 text-green-700 text-xs rounded hover:bg-green-200 transition-colors"
                                                    title="Aceptar solicitud"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                                <button
                                                    @click.stop="rejectReceivedRequest(request)"
                                                    class="inline-flex items-center px-2 py-1 bg-red-100 text-red-700 text-xs rounded hover:bg-red-200 transition-colors"
                                                    title="Rechazar solicitud"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="py-4 text-center text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-5a2 2 0 00-2-2H5a2 2 0 00-2 2v5z" />
                                </svg>
                                No tienes notificaciones
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
import { ref, onMounted } from 'vue';

// Propiedades del componente
const props = defineProps({
    userTractors: Array,
    userListings: Array,
    userRequests: Array,
    receivedRequests: Array,
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

// Debug al montar el componente
onMounted(() => {
    console.log('Dashboard montado');
    console.log('userListings:', props.userListings);
    console.log('userTractors:', props.userTractors);
    console.log('userRequests:', props.userRequests);
});

// Formateador de moneda
const formatCurrency = (value) => {
    if (!value) return '---';
    console.log('Formateando precio:', value); // Debug line
    return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(value);
};

// Formateador de fecha corta
const formatDateShort = (date) => {
    if (!date) return 'Sin fecha';
    const dateObj = new Date(date);
    const now = new Date();
    const diffTime = Math.abs(now - dateObj);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    if (diffDays <= 1) {
        return 'Hoy';
    } else if (diffDays <= 7) {
        return `Hace ${diffDays} días`;
    } else {
        return dateObj.toLocaleDateString('es-ES', { 
            day: 'numeric', 
            month: 'short' 
        });
    }
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
    console.log('Mostrando detalles del anuncio:', listing);
    
    // Si el listing ya tiene todos los datos necesarios, usarlos directamente
    if (listing.tractor && listing.requests !== undefined) {
        selectedListing.value = listing;
        return;
    }
    
    // Si no, cargar los datos completos desde el servidor
    axios.get(route('user.listings.details', listing.id))
        .then(response => {
            selectedListing.value = response.data.listing;
            console.log('Datos del anuncio cargados:', selectedListing.value);
        })
        .catch(error => {
            console.error('Error al cargar detalles del anuncio:', error);
            // Como fallback, usar los datos que tenemos
            selectedListing.value = listing;
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

// Contar notificaciones no leídas (solicitudes pendientes)
const getUnreadNotificationsCount = () => {
    if (!props.receivedRequests) return 0;
    return props.receivedRequests.filter(request => request.status === 'pending').length;
};

// Mostrar detalles de una solicitud recibida
const showReceivedRequestDetails = (request) => {
    console.log('Mostrando detalles de solicitud recibida:', request);
    selectedRequest.value = request;
};

// Aceptar una solicitud recibida
const acceptReceivedRequest = (request) => {
    if (!confirm(`¿Aceptar la solicitud de ${request.requester?.first_name || 'este usuario'}?`)) {
        return;
    }
    
    router.put(route('user.requests.accept', request.id), {}, {
        onSuccess: () => {
            // Actualizar el estado localmente para feedback inmediato
            request.status = 'accepted';
            router.reload();
        },
        onError: (errors) => {
            console.error('Error al aceptar la solicitud:', errors);
        }
    });
};

// Rechazar una solicitud recibida
const rejectReceivedRequest = (request) => {
    if (!confirm(`¿Rechazar la solicitud de ${request.requester?.first_name || 'este usuario'}?`)) {
        return;
    }
    
    router.put(route('user.requests.reject', request.id), {}, {
        onSuccess: () => {
            // Actualizar el estado localmente para feedback inmediato
            request.status = 'rejected';
            router.reload();
        },
        onError: (errors) => {
            console.error('Error al rechazar la solicitud:', errors);
        }
    });
};

// Actualizar también la función de debug onMounted:
onMounted(() => {
    console.log('Dashboard montado');
    console.log('userListings:', props.userListings);
    console.log('userTractors:', props.userTractors);
    console.log('userRequests:', props.userRequests);
    console.log('receivedRequests:', props.receivedRequests); // Nueva línea
});

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