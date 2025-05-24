<template>
  <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="w-full max-w-lg rounded-lg bg-white p-6 shadow-lg">
      <h2 class="mb-4 text-xl font-bold text-gray-800">Detalles del Apero</h2>
      <div class="space-y-2">
        <p><strong>Nombre:</strong> {{ apero.name }}</p>
        <p><strong>Marca:</strong> {{ apero.brand }}</p>
        <p><strong>Modelo:</strong> {{ apero.model }}</p>
        <p><strong>Año:</strong> {{ apero.year || 'No especificado' }}</p>
        <p><strong>Tipo:</strong> {{ apero.type || 'No especificado' }}</p>
        <p><strong>Descripción:</strong> {{ apero.description || 'Sin descripción' }}</p>
        <p><strong>Disponible:</strong> {{ apero.is_available ? 'Sí' : 'No' }}</p>
      </div>

      <div class="mt-6 flex justify-end space-x-2">
        <button @click="$emit('close')" class="rounded-md bg-gray-200 px-4 py-2 text-sm">Cerrar</button>
        <button @click="$emit('edit', apero)" class="rounded-md bg-yellow-500 px-4 py-2 text-sm text-white hover:bg-yellow-600">Editar</button>
        <button @click="$emit('delete', apero)" class="rounded-md bg-red-600 px-4 py-2 text-sm text-white hover:bg-red-700">Eliminar</button>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  apero: Object,
});
</script>
