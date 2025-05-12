<template>
    <AdminLayout>
      <template #header>
        <h2 class="font-semibold text-2xl text-white leading-tight">
          Solicitudes
        </h2>
      </template>
      <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-2xl font-bold text-green-800">Solicitudes</h1>
          <Link 
            :href="route('requests.create')" 
            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition"
          >
            Nueva Solicitud
          </Link>
        </div>
  
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-green-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                  ID
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                  Tipo
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                  Estado
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                  Solicitante
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                  Precio
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                  Acciones
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr 
                v-for="request in requests" 
                :key="request.id" 
                class="hover:bg-green-50 transition"
              >
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ request.id }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span 
                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                    :class="{
                      'bg-blue-100 text-blue-800': request.type === 'sale',
                      'bg-yellow-100 text-yellow-800': request.type === 'rental'
                    }"
                  >
                    {{ request.type === 'sale' ? 'Compra' : 'Alquiler' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span 
                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                    :class="{
                      'bg-yellow-100 text-yellow-800': request.status === 'pending',
                      'bg-green-100 text-green-800': request.status === 'accepted',
                      'bg-red-100 text-red-800': request.status === 'rejected',
                      'bg-blue-100 text-blue-800': request.status === 'completed'
                    }"
                  >
                    {{ requestStatusText(request.status) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ request.requester ? 
                    (request.requester.first_name + ' ' + request.requester.last_name) : 
                    'Usuario #' + request.requester_id 
                  }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ formatCurrency(request.offered_price) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                  <Link 
                    :href="route('requests.show', request.id)" 
                    class="text-green-600 hover:text-green-900"
                  >
                    Ver
                  </Link>
                  <Link 
                    :href="route('requests.edit', request.id)" 
                    class="text-yellow-600 hover:text-yellow-900"
                  >
                    Editar
                  </Link>
                  <button 
                    @click="confirmDelete(request.id)" 
                    class="text-red-600 hover:text-red-900"
                  >
                    Eliminar
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </AdminLayout>
  </template>
  
  <script setup lang="ts">
  import { Link, router } from '@inertiajs/vue3';
  import AdminLayout from '@/Layouts/AdminLayout.vue';
  
  const props = defineProps({
    requests: Array
  });
  
  const confirmDelete = (requestId) => {
    if (confirm('¿Estás seguro de que quieres eliminar esta solicitud?')) {
      router.delete(route('requests.destroy', requestId));
    }
  };
  
  const formatCurrency = (value) => {
    if (!value) return '---';
    return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(value);
  };
  
  const requestStatusText = (status) => {
    switch (status) {
      case 'pending': return 'Pendiente';
      case 'accepted': return 'Aceptada';
      case 'rejected': return 'Rechazada';
      case 'completed': return 'Completada';
      default: return status;
    }
  };
  </script>