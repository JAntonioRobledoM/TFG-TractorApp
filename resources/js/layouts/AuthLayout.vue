<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
    title: string;
    description?: string;
    class?: string;
}>();
</script>

<template>
    <div class="min-h-screen flex items-center justify-center" :class="$props.class">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl mx-4 p-8 relative overflow-hidden border border-green-100">
            <!-- Logo o ícono en la parte superior -->
            <div class="flex justify-center mb-4">
                <Link :href="route('home')" class="flex items-center text-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-2" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 8h-2c0-.6-.4-1-1-1h-2c-.6 0-1 .4-1 1h-2c-1.7 0-3 1.3-3 3v3c0 1.7 1.3 3 3 3h8c1.7 0 3-1.3 3-3v-3c0-1.7-1.3-3-3-3zm-2 9c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3zM5 11c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"/>
                    </svg>
                    <span class="text-xl font-bold">TractorApp</span>
                </Link>
            </div>

            <!-- Título y descripción -->
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ title }}</h1>
                <p v-if="description" class="text-gray-600 text-sm">
                    {{ description }}
                </p>
            </div>

            <!-- Contenido del formulario -->
            <slot />

            <!-- Patrón decorativo en las esquinas -->
            <div class="absolute -top-4 -right-4 w-24 h-24 bg-green-100 rounded-full opacity-60"></div>
            <div class="absolute -bottom-4 -left-4 w-24 h-24 bg-green-100 rounded-full opacity-60"></div>
        </div>
    </div>
</template>

<style scoped>
/* Añade estilos generales para AuthLayout */
.rounded-2xl {
    border-radius: 1rem;
}

.shadow-xl {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
</style>