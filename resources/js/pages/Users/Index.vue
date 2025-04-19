<template>
    <AdminLayout>
      <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-2xl font-bold text-green-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-green-600" viewBox="0 0 20 20" fill="currentColor">
              <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
            </svg>
            Usuarios
          </h1>
          <Link 
            :href="route('users.create')" 
            class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition duration-200 flex items-center shadow-md"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
              <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
            </svg>
            Crear Nuevo Usuario
          </Link>
        </div>
  
        <div class="bg-white shadow-lg overflow-hidden rounded-lg border-t-4 border-green-600">
          <table class="min-w-full divide-y divide-green-200">
            <thead class="bg-green-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">Nombre de Usuario</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">Nombre Completo</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">Acciones</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-green-100">
              <tr v-for="user in users" :key="user.id" class="hover:bg-green-50 transition duration-200">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ user.username }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ user.first_name }} {{ user.last_name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ user.email }}</td>
                <td class="px-6 py-4 whitespace-nowrap space-x-3 text-sm">
                  <Link 
                    :href="route('users.show', user.id)" 
                    class="text-green-600 hover:text-green-900 font-medium inline-flex items-center"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                      <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                      <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                    </svg>
                    Ver
                  </Link>
                  <Link 
                    :href="route('users.edit', user.id)" 
                    class="text-yellow-600 hover:text-yellow-900 font-medium inline-flex items-center"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                      <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                    Editar
                  </Link>
                  <button 
                    @click="confirmDelete(user.id)" 
                    class="text-red-600 hover:text-red-900 font-medium inline-flex items-center"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
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
  
  defineProps({
    users: Array
  });
  
  const confirmDelete = (userId) => {
    if (confirm('¿Estás seguro de que quieres eliminar este usuario?')) {
      router.delete(route('users.destroy', userId));
    }
  };
  </script>

  <style scoped>
  /* Add subtle field texture to the table background */
  .bg-white {
      background-image: 
          radial-gradient(circle at 10px 10px, rgba(34, 197, 94, 0.03) 2px, transparent 2px);
      background-size: 20px 20px;
  }

  /* Enhanced shadow for table */
  .shadow-lg {
      box-shadow: 0 10px 15px -3px rgba(34, 197, 94, 0.1), 0 4px 6px -2px rgba(34, 197, 94, 0.05);
  }

  /* Add pattern to the table header */
  .bg-green-50 {
      background-image: 
          linear-gradient(to bottom, rgba(240, 253, 244, 0.9), rgba(240, 253, 244, 0.9)),
          repeating-linear-gradient(
              45deg,
              rgba(34, 197, 94, 0.05) 0px,
              rgba(34, 197, 94, 0.05) 10px,
              transparent 10px,
              transparent 20px
          );
  }
  </style>