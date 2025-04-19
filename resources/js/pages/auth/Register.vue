<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

const form = useForm({
    username: '',
    first_name: '',
    last_name: '',
    email: '',
    pass: '',
    pass_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('pass', 'pass_confirmation'),
    });
};
</script>

<template>
    <AuthBase title="Crear una cuenta" description="Introduce tus datos para crear tu cuenta" class="tractor-auth-layout">
        <Head title="Registro" />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="username" class="text-gray-700 font-medium">Nombre de usuario</Label>
                    <Input id="username" type="text" required autofocus :tabindex="1" autocomplete="username" v-model="form.username" placeholder="Nombre de usuario" class="border-green-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition duration-200" />
                    <InputError :message="form.errors.username" />
                </div>

                <div class="grid gap-2">
                    <Label for="first_name" class="text-gray-700 font-medium">Nombre</Label>
                    <Input id="first_name" type="text" required :tabindex="2" v-model="form.first_name" placeholder="Nombre" class="border-green-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition duration-200" />
                    <InputError :message="form.errors.first_name" />
                </div>

                <div class="grid gap-2">
                    <Label for="last_name" class="text-gray-700 font-medium">Apellidos</Label>
                    <Input id="last_name" type="text" required :tabindex="3" v-model="form.last_name" placeholder="Apellidos" class="border-green-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition duration-200" />
                    <InputError :message="form.errors.last_name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email" class="text-gray-700 font-medium">Correo electrónico</Label>
                    <Input id="email" type="email" required :tabindex="4" autocomplete="email" v-model="form.email" placeholder="correo@ejemplo.com" class="border-green-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition duration-200" />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="pass" class="text-gray-700 font-medium">Contraseña</Label>
                    <Input
                        id="pass"
                        type="password"
                        required
                        :tabindex="5"
                        autocomplete="new-password"
                        v-model="form.pass"
                        placeholder="Contraseña"
                        class="border-green-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition duration-200"
                    />
                    <InputError :message="form.errors.pass" />
                </div>

                <div class="grid gap-2">
                    <Label for="pass_confirmation" class="text-gray-700 font-medium">Confirmar contraseña</Label>
                    <Input
                        id="pass_confirmation"
                        type="password"
                        required
                        :tabindex="6"
                        autocomplete="new-password"
                        v-model="form.pass_confirmation"
                        placeholder="Confirmar contraseña"
                        class="border-green-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition duration-200"
                    />
                    <InputError :message="form.errors.pass_confirmation" />
                </div>

                <Button type="submit" class="mt-2 w-full bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition duration-200 flex items-center justify-center shadow-md" tabindex="7" :disabled="form.processing">
                    <template v-if="form.processing">
                        <LoaderCircle class="h-4 w-4 animate-spin mr-2" />
                        Creando cuenta...
                    </template>
                    <template v-else>
                        Crear cuenta
                    </template>
                </Button>
            </div>

            <div class="text-center text-sm text-gray-600">
                ¿Ya tienes una cuenta?
                <TextLink :href="route('login')" class="text-green-600 hover:text-green-800 font-medium transition duration-200" :tabindex="8">Iniciar sesión</TextLink>
            </div>
        </form>
    </AuthBase>
</template>

<style scoped>
/* Añade patrones de fondo al formulario de registro */
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

/* Patrón sutil para elementos con fondo verde claro (si se agregan en el futuro) */
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

/* Checkbox custom styling, por si se añade más adelante */
:deep(input[type="checkbox"]) {
    accent-color: #16a34a;
}
</style>
