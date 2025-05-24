<template>
  <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="w-full max-w-lg rounded-lg bg-white shadow-lg">
      <div class="rounded-t-lg bg-green-600 p-4 text-lg font-semibold text-white">
        {{ aperoToEdit ? 'Editar Apero' : 'Crear Apero' }}
      </div>
      <div class="space-y-4 p-6">
        <div>
          <label class="block text-sm font-medium text-gray-700">Nombre</label>
          <input v-model="form.name" type="text" class="mt-1 w-full rounded-md border border-gray-300 shadow-sm" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Marca</label>
          <input v-model="form.brand" type="text" class="mt-1 w-full rounded-md border border-gray-300 shadow-sm" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Modelo</label>
          <input v-model="form.model" type="text" class="mt-1 w-full rounded-md border border-gray-300 shadow-sm" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Año</label>
          <input v-model="form.year" type="number" class="mt-1 w-full rounded-md border border-gray-300 shadow-sm" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Asociar a Tractor</label>
          <select v-model="form.tractor_id" class="mt-1 w-full rounded-md border border-gray-300 shadow-sm">
            <option disabled value="">Selecciona un tractor</option>
            <option v-for="tractor in userTractors" :key="tractor.id" :value="tractor.id">
              {{ tractor.brand }} {{ tractor.model }}
            </option>
          </select>
        </div>
        <div class="flex justify-end space-x-2 pt-4">
          <button @click="$emit('close')" class="rounded-md bg-gray-200 px-4 py-2 text-sm text-gray-700 hover:bg-gray-300">
            Cancelar
          </button>
          <button @click="submitForm" class="rounded-md bg-green-600 px-4 py-2 text-sm text-white hover:bg-green-700">
            {{ aperoToEdit ? 'Guardar cambios' : 'Crear' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const emit = defineEmits(['close', 'created']);
const props = defineProps({
  userTractors: Array,
  aperoToEdit: Object
});

const form = ref({
  name: props.aperoToEdit?.name || '',
  brand: props.aperoToEdit?.brand || '',
  model: props.aperoToEdit?.model || '',
  year: props.aperoToEdit?.year || '',
  tractor_id: props.aperoToEdit?.tractors?.[0]?.id || ''
});

const submitForm = () => {
  const method = props.aperoToEdit ? 'put' : 'post';
  const url = props.aperoToEdit
    ? `/user/aperos/${props.aperoToEdit.id}`
    : '/user/aperos';

  router[method](url, form.value, {
    onSuccess: () => emit('created'),
    onError: (errors) => {
      console.error('Errores de validación:', errors);
      alert('Error al guardar el apero. Asegúrate de completar todos los campos.');
    }
  });
};
</script>
