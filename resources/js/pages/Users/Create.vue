<template>
    <AdminLayout>
      <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6 text-green-800 flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-green-600" viewBox="0 0 20 20" fill="currentColor">
            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
          </svg>
          Crear Nuevo Usuario
        </h1>
  
        <form @submit.prevent="submit" class="bg-white shadow-lg rounded-lg px-8 pt-6 pb-8 mb-4 border-t-4 border-green-600">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                Nombre de Usuario
              </label>
              <input 
                id="username" 
                v-model="form.username" 
                type="text" 
                required 
                class="shadow appearance-none border border-green-200 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition duration-200"
              />
              <div v-if="form.errors.username" class="text-red-500 text-xs mt-1">
                {{ form.errors.username }}
              </div>
            </div>
  
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                Correo Electrónico
              </label>
              <input 
                id="email" 
                v-model="form.email" 
                type="email" 
                class="shadow appearance-none border border-green-200 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition duration-200"
              />
              <div v-if="form.errors.email" class="text-red-500 text-xs mt-1">
                {{ form.errors.email }}
              </div>
            </div>
  
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2" for="first_name">
                Nombre
              </label>
              <input 
                id="first_name" 
                v-model="form.first_name" 
                type="text" 
                required 
                class="shadow appearance-none border border-green-200 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition duration-200"
              />
              <div v-if="form.errors.first_name" class="text-red-500 text-xs mt-1">
                {{ form.errors.first_name }}
              </div>
            </div>
  
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2" for="last_name">
                Apellidos
              </label>
              <input 
                id="last_name" 
                v-model="form.last_name" 
                type="text" 
                required 
                class="shadow appearance-none border border-green-200 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition duration-200"
              />
              <div v-if="form.errors.last_name" class="text-red-500 text-xs mt-1">
                {{ form.errors.last_name }}
              </div>
            </div>
  
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2" for="pass">
                Contraseña
              </label>
              <input 
                id="pass" 
                v-model="form.pass" 
                type="password" 
                required 
                class="shadow appearance-none border border-green-200 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition duration-200"
              />
              <div v-if="form.errors.pass" class="text-red-500 text-xs mt-1">
                {{ form.errors.pass }}
              </div>
            </div>
  
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2" for="pass_confirmation">
                Confirmar Contraseña
              </label>
              <input 
                id="pass_confirmation" 
                v-model="form.pass_confirmation" 
                type="password" 
                required 
                class="shadow appearance-none border border-green-200 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition duration-200"
              />
            </div>
  
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2" for="dni">
                DNI (Opcional)
              </label>
              <input 
                id="dni" 
                v-model="form.dni" 
                type="text" 
                class="shadow appearance-none border border-green-200 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition duration-200"
              />
            </div>
  
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2" for="tlf">
                Teléfono (Opcional)
              </label>
              <input 
                id="tlf" 
                v-model="form.tlf" 
                type="tel" 
                class="shadow appearance-none border border-green-200 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition duration-200"
              />
            </div>
  
            <div class="col-span-1 md:col-span-2 flex items-center p-4 bg-green-50 rounded-lg">
              <input 
                id="is_admin" 
                v-model="form.is_admin" 
                type="checkbox" 
                class="mr-3 h-5 w-5 text-green-600 focus:ring-green-500 border-green-300 rounded"
              />
              <label for="is_admin" class="text-gray-700 text-sm font-medium">
                Administrador
              </label>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
            </div>
          </div>
  
          <div class="flex items-center justify-between mt-8 pt-6 border-t border-green-100">
            <button 
              type="submit" 
              class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition duration-200 flex items-center shadow-md"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
              </svg>
              Crear Usuario
            </button>
            <Link 
              :href="route('users.index')" 
              class="text-gray-600 hover:text-gray-900 px-4 py-2 flex items-center"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
              </svg>
              Cancelar
            </Link>
          </div>
        </form>
      </div>
    </AdminLayout>
  </template>
  
  <script setup lang="ts">
  import { useForm, Link } from '@inertiajs/vue3';
  import AdminLayout from '@/Layouts/AdminLayout.vue';
  
  const form = useForm({
    username: '',
    first_name: '',
    last_name: '',
    email: '',
    pass: '',
    pass_confirmation: '',
    dni: null,
    tlf: null,
    is_admin: false
  });
  
  const submit = () => {
    form.post(route('users.store'), {
      onFinish: () => form.reset()
    });
  };
  </script>

  <style scoped>
  /* Add subtle field texture to the form background */
  .bg-white {
      background-image: 
          radial-gradient(circle at 10px 10px, rgba(34, 197, 94, 0.03) 2px, transparent 2px);
      background-size: 20px 20px;
  }

  /* Add pattern to the admin checkbox container */
  .bg-green-50 {
      background-image: 
          linear-gradient(to bottom, rgba(240, 253, 244, 0.6), rgba(240, 253, 244, 0.6)),
          repeating-linear-gradient(
              45deg,
              rgba(34, 197, 94, 0.05) 0px,
              rgba(34, 197, 94, 0.05) 10px,
              transparent 10px,
              transparent 20px
          );
  }

  /* Enhanced shadow for form */
  .shadow-lg {
      box-shadow: 0 10px 15px -3px rgba(34, 197, 94, 0.1), 0 4px 6px -2px rgba(34, 197, 94, 0.05);
  }

  /* Input focus styles */
  input:focus {
      box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.2);
  }

  /* Checkbox custom styling */
  input[type="checkbox"] {
      accent-color: #16a34a;
  }
  </style>