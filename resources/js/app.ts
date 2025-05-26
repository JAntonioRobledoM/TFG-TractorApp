import '../css/app.css';

// Axios, Echo, Pusher
import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Inertia, Vue, Ziggy
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { initializeTheme } from './composables/useAppearance';

// Componentes personalizados
import Chat from './Components/Chat.vue';
import ChatButton from './Components/ChatButton.vue';
import NotificacionesChat from './Components/NotificacionesChat.vue';

// Declaraciones de tipo para Vite
declare module 'vite/client' {
    interface ImportMetaEnv {
        readonly VITE_APP_NAME: string;
        readonly VITE_PUSHER_APP_KEY: string;
        readonly VITE_PUSHER_APP_CLUSTER: string;
        [key: string]: string | boolean | undefined;
    }

    interface ImportMeta {
        readonly env: ImportMetaEnv;
        readonly glob: <T>(pattern: string) => Record<string, () => Promise<T>>;
    }
}

// Declaraciones globales para TypeScript
declare global {
    interface Window {
        Pusher: typeof Pusher;
        Echo: Echo;
        listenToChatChannel?: (conversationId: number | string, callback: (message: any) => void) => any;
    }
}

// Axios config
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.withCredentials = true;

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (csrfToken) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

window.axios = axios;

// Echo y Pusher config
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
});

// Depuración de conexión Pusher
window.Echo.connector.pusher.connection.bind('connected', () => {
    console.log('Pusher conectado correctamente');
});

window.Echo.connector.pusher.connection.bind('error', (err: any) => {
    console.error('Error de conexión Pusher:', err);
});

// Escuchar canal de chat por ID de conversación
window.listenToChatChannel = (conversationId, callback) => {
    const channel = window.Echo.private(`chat.${conversationId}`);
    
    channel.listen('NewChatMessage', (event: any) => {
        console.log('Nuevo mensaje recibido via Pusher:', event);
        if (typeof callback === 'function') {
            callback(event.message);
        }
    });

    return channel;
};

// Inertia + Vue 3 App
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });

        // Registrar componentes globales
        app.component('Chat', Chat);
        app.component('ChatButton', ChatButton);
        app.component('NotificacionesChat', NotificacionesChat);

        // Usar plugins
        app.use(plugin).use(ZiggyVue).mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

// Tema claro/oscuro
initializeTheme();