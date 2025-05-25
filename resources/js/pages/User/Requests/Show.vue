<template>
    <div class="min-h-screen bg-gradient-to-b from-green-50 to-green-100">
        <UserLayout>
            <template #header>
                <h2 class="text-2xl leading-tight font-semibold text-white">Detalle de Solicitud</h2>
            </template>

            <div class="py-12">
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div class="mb-6">
                        <Link :href="route('user.requests.index')" class="font-medium text-green-600 hover:text-green-800">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Volver a mis solicitudes
                            </span>
                        </Link>
                    </div>

                    <!-- Status banner -->
                    <div
                        class="mb-6 rounded-lg p-4"
                        :class="{
                            'border border-yellow-200 bg-yellow-50': request.status === 'pending',
                            'border border-green-200 bg-green-50': request.status === 'accepted',
                            'border border-red-200 bg-red-50': request.status === 'rejected',
                            'border border-blue-200 bg-blue-50': request.status === 'completed',
                            'border border-gray-200 bg-gray-50': request.status === 'cancelled',
                        }"
                    >
                        <div class="flex items-center">
                            <svg
                                v-if="request.status === 'pending'"
                                xmlns="http://www.w3.org/2000/svg"
                                class="mr-2 h-6 w-6 text-yellow-600"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                            <svg
                                v-if="request.status === 'accepted'"
                                xmlns="http://www.w3.org/2000/svg"
                                class="mr-2 h-6 w-6 text-green-600"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                            <svg
                                v-if="request.status === 'rejected'"
                                xmlns="http://www.w3.org/2000/svg"
                                class="mr-2 h-6 w-6 text-red-600"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                            <svg
                                v-if="request.status === 'completed'"
                                xmlns="http://www.w3.org/2000/svg"
                                class="mr-2 h-6 w-6 text-blue-600"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                />
                            </svg>
                            <svg
                                v-if="request.status === 'cancelled'"
                                xmlns="http://www.w3.org/2000/svg"
                                class="mr-2 h-6 w-6 text-gray-600"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <div>
                                <h3
                                    class="font-semibold"
                                    :class="{
                                        'text-yellow-700': request.status === 'pending',
                                        'text-green-700': request.status === 'accepted',
                                        'text-red-700': request.status === 'rejected',
                                        'text-blue-700': request.status === 'completed',
                                        'text-gray-700': request.status === 'cancelled',
                                    }"
                                >
                                    Estado: {{ statusText[request.status] }}
                                </h3>
                                <p class="text-sm text-gray-600">
                                    {{ request.created_at ? formatDate(request.created_at) : '' }}
                                </p>
                            </div>
                        </div>

                        <div v-if="request.status === 'pending'" class="mt-4 flex">
                            <button
                                @click="cancelRequest"
                                class="inline-flex items-center justify-center rounded-md bg-red-100 px-4 py-2 text-sm font-medium text-red-700 transition hover:bg-red-200"
                            >
                                Cancelar solicitud
                            </button>
                        </div>
                        
                        <!-- Botón de chat colocado correctamente (fuera del div de ícono) -->
                        <div v-if="request.status === 'accepted'" class="mt-4">
                            <ChatButton :requestId="request.id" :userId="$page.props.auth.user.id" />
                        </div>
                    </div>

                    <!-- Main details -->
                    <div class="mb-6 overflow-hidden rounded-lg bg-white shadow-lg">
                        <div class="p-6">
                            <h2 class="mb-4 text-xl font-bold text-green-800">Detalles de la Solicitud</h2>

                            <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                                <!-- Left column: Request details -->
                                <div>
                                    <h3 class="mb-3 font-semibold text-green-700">Información de solicitud</h3>

                                    <div class="mb-4">
                                        <p class="mb-1 text-sm text-gray-500">Fecha de solicitud</p>
                                        <p class="font-medium">{{ request.created_at ? formatDate(request.created_at) : 'No disponible' }}</p>
                                    </div>

                                    <div class="mb-4">
                                        <p class="mb-1 text-sm text-gray-500">Tipo de solicitud</p>
                                        <p class="font-medium">
                                            {{ request.listing?.type === 'sale' ? 'Compra' : 'Alquiler' }}
                                        </p>
                                    </div>

                                    <div class="mb-4">
                                        <p class="mb-1 text-sm text-gray-500">Estado</p>
                                        <p class="font-medium">{{ statusText[request.status] }}</p>
                                    </div>

                                    <div v-if="request.status === 'rejected' && request.rejection_reason" class="mb-4">
                                        <p class="mb-1 text-sm text-gray-500">Motivo de rechazo</p>
                                        <p class="font-medium text-red-600">{{ request.rejection_reason }}</p>
                                    </div>

                                    <div v-if="request.message" class="mb-4">
                                        <p class="mb-1 text-sm text-gray-500">Mensaje</p>
                                        <p class="border-l-4 border-green-200 py-2 pl-3 italic">{{ request.message }}</p>
                                    </div>
                                </div>

                                <!-- Right column: Listing details -->
                                <div v-if="request.listing">
                                    <h3 class="mb-3 font-semibold text-green-700">Información del anuncio</h3>

                                    <div class="mb-4">
                                        <p class="mb-1 text-sm text-gray-500">Tractor</p>
                                        <p class="font-medium">
                                            {{
                                                request.listing.tractor
                                                    ? request.listing.tractor.brand + ' ' + request.listing.tractor.model
                                                    : 'No especificado'
                                            }}
                                        </p>
                                    </div>

                                    <div class="mb-4">
                                        <p class="mb-1 text-sm text-gray-500">Tipo de anuncio</p>
                                        <p class="font-medium">{{ request.listing.type === 'sale' ? 'Venta' : 'Alquiler' }}</p>
                                    </div>

                                    <div class="mb-4">
                                        <p class="mb-1 text-sm text-gray-500">Precio</p>
                                        <p class="font-medium text-green-600">
                                            {{ formatCurrency(request.listing.price) }}
                                            <span v-if="request.listing.type === 'rental'" class="text-sm text-gray-500">/día</span>
                                        </p>
                                    </div>

                                    <div
                                        v-if="request.listing.type === 'rental' && request.listing.start_date && request.listing.end_date"
                                        class="mb-4"
                                    >
                                        <p class="mb-1 text-sm text-gray-500">Periodo disponible</p>
                                        <p class="font-medium">
                                            {{ formatDate(request.listing.start_date) }} - {{ formatDate(request.listing.end_date) }}
                                        </p>
                                    </div>

                                    <div class="mb-4">
                                        <p class="mb-1 text-sm text-gray-500">Vendedor</p>
                                        <p class="font-medium">
                                            {{
                                                request.listing.seller
                                                    ? request.listing.seller.first_name + ' ' + request.listing.seller.last_name
                                                    : 'No especificado'
                                            }}
                                        </p>
                                    </div>

                                    <div class="mt-6">
                                        <Link
                                            :href="route('user.listings.show', request.listing_id)"
                                            class="inline-flex items-center justify-center rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-green-700"
                                        >
                                            Ver Anuncio Completo
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact seller section -->
                    <div v-if="request.status === 'accepted'" class="mb-6 overflow-hidden rounded-lg bg-white shadow-lg">
                        <div class="p-6">
                            <h2 class="mb-4 text-xl font-bold text-green-800">Contactar con el Vendedor</h2>

                            <div v-if="request.listing && request.listing.seller" class="rounded-lg border border-green-100 bg-green-50 p-4">
                                <div class="mb-4 flex items-center">
                                    <div class="mr-4 flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                                        <span class="text-xl font-bold text-green-600">
                                            {{ request.listing.seller.first_name.charAt(0) }}
                                        </span>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-medium">
                                            {{ request.listing.seller.first_name }} {{ request.listing.seller.last_name }}
                                        </h3>
                                        <p class="text-gray-600">{{ request.listing.seller.email }}</p>
                                    </div>
                                </div>

                                <p class="mb-4 text-gray-600">
                                    Tu solicitud ha sido aceptada. Puedes ponerte en contacto con el vendedor para coordinar los detalles de la
                                    {{ request.listing.type === 'sale' ? 'compra' : 'renta' }}.
                                </p>

                                
                                    :href="`mailto:${request.listing.seller.email}?subject=TractorApp - Solicitud aceptada para ${request.listing.tractor ? request.listing.tractor.brand + ' ' + request.listing.tractor.model : 'tu anuncio'}`"
                                    class="inline-flex items-center justify-center rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-green-700"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="mr-2 h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                        />
                                    </svg>
                                    Enviar Email
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </UserLayout>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'; // Añadido 'router' para poder usarlo en cancelRequest
import UserLayout from '@/Layouts/UserLayout.vue';
import ChatButton from '@/Components/ChatButton.vue';

const props = defineProps({
    request: Object,
});

const statusText = {
    pending: 'Pendiente',
    accepted: 'Aceptada',
    rejected: 'Rechazada',
    completed: 'Completada',
    cancelled: 'Cancelada',
};

function formatCurrency(value) {
    if (!value) return '---';
    return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(value);
}

function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' }).format(date);
}

function cancelRequest() {
    if (!confirm('¿Estás seguro de que deseas cancelar esta solicitud?')) {
        return;
    }

    router.patch(
        route('user.requests.cancel', props.request.id),
        {},
        {
            onSuccess: () => {
                // The page will refresh with updated data
            },
        },
    );
}
</script>

<style scoped>
/* Add subtle field texture to the background */
.bg-gradient-to-b {
    background-image:
        linear-gradient(to bottom, rgba(240, 253, 244, 0.95), rgba(220, 252, 231, 0.95)),
        repeating-linear-gradient(45deg, rgba(34, 197, 94, 0.03) 0px, rgba(34, 197, 94, 0.03) 10px, transparent 10px, transparent 20px);
}
</style>