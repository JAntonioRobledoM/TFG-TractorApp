<!-- resources/js/components/User/ListingShow.vue -->
<template>
    <div class="bg-opacity-75 fixed inset-0 z-50 flex h-full w-full items-center justify-center overflow-y-auto bg-gray-800 px-4">
        <div class="mx-auto w-full max-w-3xl overflow-hidden rounded-lg bg-white shadow-lg">
            <div class="flex items-center justify-between bg-green-700 p-4 font-medium text-white">
                <div class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" />
                        <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z" />
                    </svg>
                    <span>Detalles del Anuncio</span>
                </div>
                <button @click="$emit('close')" class="text-white transition duration-200 hover:text-green-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path
                            fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </button>
            </div>

            <!-- Vista de Detalles -->
            <div v-if="!isEditing" class="space-y-5 p-6">
                <!-- Estado y tipo -->
                <div class="flex justify-between">
                    <span
                        class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium"
                        :class="{
                            'bg-green-100 text-green-800': listing.is_active,
                            'bg-gray-100 text-gray-800': !listing.is_active,
                        }"
                    >
                        {{ listing.is_active ? 'Activo' : 'Inactivo' }}
                    </span>
                    <span
                        class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium"
                        :class="{
                            'bg-blue-100 text-blue-800': listing.type === 'rental',
                            'bg-purple-100 text-purple-800': listing.type === 'sale',
                        }"
                    >
                        {{ listing.type === 'rental' ? 'Alquiler' : 'Venta' }}
                    </span>
                </div>

                <!-- Información del tractor -->
                <div v-if="listing.tractor">
                    <h4 class="mb-3 text-lg font-medium text-gray-800">Información del tractor</h4>
                    <div class="space-y-2 rounded-lg bg-gray-50 p-4">
                        <div class="text-xl font-medium text-gray-800">{{ listing.tractor.brand }} {{ listing.tractor.model }}</div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Año</p>
                                <p class="font-medium">{{ listing.tractor.year || 'No especificado' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Potencia</p>
                                <p class="font-medium">{{ listing.tractor.horsepower ? `${listing.tractor.horsepower} CV` : 'No especificada' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detalles del anuncio -->
                <div>
                    <h4 class="mb-3 text-lg font-medium text-gray-800">Detalles del anuncio</h4>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500">Precio</p>
                            <p class="text-xl font-bold text-green-600">{{ formatCurrency(listing.price) }}</p>
                            <p class="text-xs text-gray-500">
                                {{ listing.type === 'rental' ? 'por día' : 'precio de venta' }}
                            </p>
                        </div>
                        <div v-if="listing.type === 'rental'">
                            <p class="text-sm text-gray-500">Disponibilidad</p>
                            <p class="font-medium">{{ formatDate(listing.start_date) }} - {{ formatDate(listing.end_date) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Descripción -->
                <div>
                    <h4 class="mb-3 text-lg font-medium text-gray-800">Descripción</h4>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <p class="whitespace-pre-line text-gray-700">{{ listing.description || 'No hay descripción disponible.' }}</p>
                    </div>
                </div>

                <!-- Solicitudes recibidas -->
                <div v-if="listing.requests && listing.requests.length > 0">
                    <h4 class="mb-3 text-lg font-medium text-gray-800">Solicitudes recibidas ({{ listing.requests.length }})</h4>
                    <div class="space-y-2">
                        <div
                            v-for="request in listing.requests"
                            :key="request.id"
                            class="flex items-center justify-between rounded-lg bg-gray-50 p-3"
                        >
                            <div>
                                <p class="font-medium">Solicitud de {{ request.requester?.first_name || `Usuario #${request.requester_id}` }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ formatDate(request.created_at) }} -
                                    <span
                                        class="font-medium"
                                        :class="{
                                            'text-yellow-600': request.status === 'pending',
                                            'text-green-600': request.status === 'accepted',
                                            'text-red-600': request.status === 'rejected',
                                            'text-blue-600': request.status === 'completed',
                                        }"
                                    >
                                        {{ requestStatusText(request.status) }}
                                    </span>
                                </p>
                            </div>
                            <button @click="$emit('view-request', request)" class="text-sm font-medium text-green-600 hover:text-green-700">
                                Ver detalles
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="flex space-x-3 border-t border-gray-100 pt-4">
                    <button
                        @click="toggleEdit"
                        class="flex flex-1 items-center justify-center rounded-md bg-green-600 py-2 text-white transition duration-200 hover:bg-green-700"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"
                            />
                        </svg>
                        Editar
                    </button>
                    <button
                        @click="$emit('toggle-status', listing)"
                        class="flex flex-1 items-center justify-center rounded-md bg-yellow-600 py-2 text-white transition duration-200 hover:bg-yellow-700"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        {{ listing.is_active ? 'Desactivar' : 'Activar' }}
                    </button>
                    <button
                        @click="$emit('delete', listing)"
                        class="flex flex-1 items-center justify-center rounded-md bg-red-600 py-2 text-white transition duration-200 hover:bg-red-700"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                fill-rule="evenodd"
                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        Eliminar
                    </button>
                </div>
            </div>

            <!-- Formulario de Edición -->
            <div v-else class="space-y-4 p-6">
                <form @submit.prevent="updateListing">
                    <!-- Tractor - No se puede cambiar -->
                    <div class="mb-6">
                        <label class="mb-1 block text-sm font-medium text-gray-700">Tractor</label>
                        <div class="rounded-md bg-gray-50 p-3">
                            <span class="font-medium">{{
                                listing.tractor ? `${listing.tractor.brand} ${listing.tractor.model}` : 'Tractor no seleccionado'
                            }}</span>
                        </div>
                    </div>

                    <!-- Tipo de anuncio -->
                    <div class="mb-6">
                        <label class="mb-1 block text-sm font-medium text-gray-700">Tipo de anuncio</label>
                        <div class="grid grid-cols-2 gap-4">
                            <div
                                class="flex cursor-pointer items-center space-x-2 rounded-md border p-3"
                                :class="{
                                    'border-green-500 bg-green-50': editForm.type === 'sale',
                                    'border-gray-300 hover:border-gray-400': editForm.type !== 'sale',
                                }"
                                @click="editForm.type = 'sale'"
                            >
                                <div class="flex h-4 w-4 items-center justify-center rounded-full border border-gray-400">
                                    <div v-if="editForm.type === 'sale'" class="h-2 w-2 rounded-full bg-green-500"></div>
                                </div>
                                <span>Venta</span>
                            </div>
                            <div
                                class="flex cursor-pointer items-center space-x-2 rounded-md border p-3"
                                :class="{
                                    'border-green-500 bg-green-50': editForm.type === 'rental',
                                    'border-gray-300 hover:border-gray-400': editForm.type !== 'rental',
                                }"
                                @click="editForm.type = 'rental'"
                            >
                                <div class="flex h-4 w-4 items-center justify-center rounded-full border border-gray-400">
                                    <div v-if="editForm.type === 'rental'" class="h-2 w-2 rounded-full bg-green-500"></div>
                                </div>
                                <span>Alquiler</span>
                            </div>
                        </div>
                    </div>

                    <!-- Precio -->
                    <div class="mb-6">
                        <label for="price" class="mb-1 block text-sm font-medium text-gray-700">
                            Precio (€)
                            <span class="text-xs text-gray-500">
                                {{ editForm.type === 'rental' ? '(por día)' : '' }}
                            </span>
                        </label>
                        <input
                            type="number"
                            id="price"
                            v-model="editForm.price"
                            min="0"
                            step="0.01"
                            class="w-full rounded-md border border-gray-300 p-2 focus:border-green-500 focus:ring-green-500"
                        />
                    </div>

                    <!-- Fechas de disponibilidad (solo para alquiler) -->
                    <div v-if="editForm.type === 'rental'" class="mb-6 grid grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="mb-1 block text-sm font-medium text-gray-700">Fecha inicio</label>
                            <input
                                type="date"
                                id="start_date"
                                v-model="editForm.start_date"
                                class="w-full rounded-md border border-gray-300 p-2 focus:border-green-500 focus:ring-green-500"
                            />
                        </div>
                        <div>
                            <label for="end_date" class="mb-1 block text-sm font-medium text-gray-700">Fecha fin</label>
                            <input
                                type="date"
                                id="end_date"
                                v-model="editForm.end_date"
                                class="w-full rounded-md border border-gray-300 p-2 focus:border-green-500 focus:ring-green-500"
                            />
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div class="mb-6">
                        <label for="description" class="mb-1 block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea
                            id="description"
                            v-model="editForm.description"
                            rows="4"
                            class="w-full rounded-md border border-gray-300 p-2 focus:border-green-500 focus:ring-green-500"
                            placeholder="Describe tu tractor y las condiciones del anuncio..."
                        ></textarea>
                    </div>

                    <!-- Activo -->
                    <div class="mb-6 flex items-center">
                        <input
                            type="checkbox"
                            id="is_active"
                            v-model="editForm.is_active"
                            class="h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-500"
                        />
                        <label for="is_active" class="ml-2 block text-sm text-gray-700"> Activar anuncio (visible inmediatamente) </label>
                    </div>

                    <!-- Botones -->
                    <div class="flex space-x-3 border-t border-gray-100 pt-4">
                        <button
                            type="button"
                            @click="toggleEdit"
                            class="flex flex-1 items-center justify-center rounded-md bg-gray-300 py-2 text-gray-700 transition duration-200 hover:bg-gray-400"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            class="flex flex-1 items-center justify-center rounded-md bg-green-600 py-2 text-white transition duration-200 hover:bg-green-700"
                            :disabled="isUpdating"
                        >
                            {{ isUpdating ? 'Guardando...' : 'Guardar Cambios' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, watchEffect } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    listing: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['close', 'edit', 'delete', 'toggle-status', 'view-request']);

const isEditing = ref(false);
const isUpdating = ref(false);

// Inicializar formulario de edición
const editForm = reactive({
  tractor_id: '',
  type: '',
  price: '',
  description: '',
  is_active: true,
  start_date: '',
  end_date: '',
});

// Actualizar el formulario cuando cambie el listing
watchEffect(() => {
  if (props.listing) {
    editForm.tractor_id = props.listing.tractor_id || '';
    editForm.type = props.listing.type || 'sale';
    editForm.price = props.listing.price || '';
    editForm.description = props.listing.description || '';
    editForm.is_active = props.listing.is_active !== undefined ? props.listing.is_active : true;
    editForm.start_date = props.listing.start_date || '';
    editForm.end_date = props.listing.end_date || '';
  }
});

// Formateador de moneda
const formatCurrency = (value) => {
    if (!value) return '---';
    return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(value);
};

// Formateador de fecha
const formatDate = (date) => {
    if (!date) return 'No especificada';
    return new Date(date).toLocaleDateString('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    });
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

function toggleEdit() {
  isEditing.value = !isEditing.value;
  
  // Si se está cancelando la edición, reiniciar el formulario
  if (!isEditing.value) {
    editForm.tractor_id = props.listing.tractor_id || '';
    editForm.type = props.listing.type || 'sale';
    editForm.price = props.listing.price || '';
    editForm.description = props.listing.description || '';
    editForm.is_active = props.listing.is_active !== undefined ? props.listing.is_active : true;
    editForm.start_date = props.listing.start_date || '';
    editForm.end_date = props.listing.end_date || '';
  }
}

function updateListing() {
  isUpdating.value = true;
  
  router.put(route('user.listings.update', props.listing.id), editForm, {
    onSuccess: () => {
      isUpdating.value = false;
      isEditing.value = false;
      // Notificar al componente padre que se ha actualizado
      router.reload();
    },
    onError: (errors) => {
      isUpdating.value = false;
      console.error('Error al actualizar el anuncio:', errors);
    }
  });
}

</script>
