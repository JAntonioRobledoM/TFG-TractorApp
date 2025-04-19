<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    email: '',
    pass: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('pass'),
    });
};
</script>

<template>
    <AuthBase 
        title="Iniciar sesión" 
        description="Introduce tu correo electrónico y contraseña para iniciar sesión"
        class="tractor-auth-layout"
    >
        <Head title="Iniciar sesión" />

        <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label 
                        for="email"
                        class="text-gray-700 font-medium"
                    >
                        Correo electrónico
                    </Label>
                    <Input
                        id="email"
                        type="email"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="email"
                        v-model="form.email"
                        placeholder="correo@ejemplo.com"
                        class="border-green-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition duration-200"
                    />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <div class="flex items-center justify-between">
                        <Label 
                            for="pass"
                            class="text-gray-700 font-medium"
                        >
                            Contraseña
                        </Label>
                        <TextLink 
                            v-if="canResetPassword" 
                            :href="route('password.request')" 
                            class="text-sm text-green-600 hover:text-green-800 transition duration-200" 
                            :tabindex="5"
                        >
                            ¿Olvidaste tu contraseña?
                        </TextLink>
                    </div>
                    <Input
                        id="pass"
                        type="password"
                        required
                        :tabindex="2"
                        autocomplete="current-password"
                        v-model="form.pass"
                        placeholder="Contraseña"
                        class="border-green-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition duration-200"
                    />
                    <InputError :message="form.errors.pass" />
                </div>

                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg" :tabindex="3">
                    <Label for="remember" class="flex items-center space-x-3">
                        <Checkbox 
                            id="remember" 
                            v-model="form.remember" 
                            :tabindex="4"
                            class="text-green-600 focus:ring-green-500 border-green-300 rounded"
                        />
                        <span class="text-gray-700">Recuérdame</span>
                    </Label>
                </div>

                <Button 
                    type="submit" 
                    class="mt-4 w-full bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition duration-200 flex items-center justify-center shadow-md" 
                    :tabindex="4" 
                    :disabled="form.processing"
                >
                    <template v-if="form.processing">
                        <LoaderCircle class="h-4 w-4 animate-spin mr-2" />
                        Iniciando sesión...
                    </template>
                    <template v-else>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        Iniciar sesión
                    </template>
                </Button>
            </div>

            <div class="text-center text-sm text-gray-600">
                ¿No tienes una cuenta?
                <TextLink 
                    :href="route('register')" 
                    :tabindex="5"
                    class="text-green-600 hover:text-green-800 font-medium transition duration-200"
                >
                    Regístrate
                </TextLink>
            </div>
        </form>
    </AuthBase>
</template>

<style scoped>
/* Añade patrones de fondo al formulario de login */
:deep(.tractor-auth-layout) {
    background-image: 
        linear-gradient(to bottom, rgba(240, 253, 244, 0.95), rgba(220, 252, 231, 0.95)),
        repeating-linear-gradient(
            45deg,
            rgba(34, 197, 94, 0.03) 0px,
            rgba(34, 197, 94, 0.03) 10px,
            transparent 10px,
            transparent 20px
        );
}

/* Patrón sutil para el checkbox recuérdame */
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

/* Sombra personalizada para el botón */
:deep(button) {
    box-shadow: 0 10px 15px -3px rgba(34, 197, 94, 0.1), 0 4px 6px -2px rgba(34, 197, 94, 0.05);
}

/* Input focus styles */
:deep(input:focus) {
    box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.2);
}

/* Checkbox custom styling */
:deep(input[type="checkbox"]) {
    accent-color: #16a34a;
}
</style>