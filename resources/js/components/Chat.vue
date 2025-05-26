<!-- resources/js/Components/Chat.vue -->
<template>
    <div class="bg-opacity-50 fixed inset-0 z-50 flex items-center justify-center bg-black" v-if="showChat">
        <div class="mx-auto flex h-3/4 w-full max-w-md flex-col rounded-lg bg-white shadow-lg">
            <!-- Cabecera del chat -->
            <div class="flex items-center justify-between rounded-t-lg border-b bg-green-600 p-4 text-white">
                <div>
                    <h3 class="text-xl font-bold">Chat de {{ chatInfo.type === 'sale' ? 'venta' : 'alquiler' }}</h3>
                    <p class="text-sm">{{ chatInfo.producto ? chatInfo.producto : 'Cargando...' }}</p>
                </div>
                <button @click="cerrarChat" class="text-2xl text-white hover:text-red-300">&times;</button>
            </div>

            <!-- Mensajes -->
            <div class="flex-1 overflow-y-auto p-4" ref="mensajesContainer">
                <div v-if="cargando" class="flex h-full items-center justify-center">
                    <div class="h-12 w-12 animate-spin rounded-full border-t-2 border-b-2 border-green-500"></div>
                </div>

                <template v-else>
                    <div
                        v-for="mensaje in mensajes"
                        :key="mensaje.id"
                        :class="[
                            'mb-3 max-w-3/4 rounded-lg p-3',
                            mensaje.sender_id === userId ? 'ml-auto bg-green-100 text-right' : 'mr-auto bg-gray-100',
                        ]"
                    >
                        <p class="text-xs text-gray-500">
                            {{ mensaje.sender ? `${mensaje.sender.first_name} ${mensaje.sender.last_name}` : 'Usuario' }}
                        </p>
                        <p>{{ mensaje.content }}</p>
                        <p class="text-xs text-gray-400">
                            {{ formatearFecha(mensaje.created_at) }}
                            <span v-if="mensaje.is_read && mensaje.sender_id === userId" class="ml-1 text-green-600">✓</span>
                        </p>
                    </div>

                    <div v-if="mensajes.length === 0" class="flex h-full items-center justify-center text-gray-400">
                        No hay mensajes. ¡Sé el primero en escribir!
                    </div>
                </template>
            </div>

            <!-- Formulario para enviar mensaje -->
            <div class="border-t p-4">
                <form @submit.prevent="enviarMensaje" class="flex">
                    <input
                        type="text"
                        v-model="nuevoMensaje"
                        class="flex-1 rounded-l-lg border p-2 text-lg focus:ring-2 focus:ring-green-500 focus:outline-none"
                        placeholder="Escribe tu mensaje..."
                        :disabled="enviando"
                    />
                    <button
                        type="submit"
                        class="rounded-r-lg bg-green-600 px-4 py-2 text-white hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:outline-none"
                        :disabled="enviando || !nuevoMensaje.trim()"
                    >
                        <span v-if="enviando">...</span>
                        <span v-else>Enviar</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { nextTick, onMounted, onUnmounted, ref, watch } from 'vue';

export default {
    props: {
        conversationId: {
            type: Number,
            required: true,
        },
        showChat: {
            type: Boolean,
            default: false,
        },
        userId: {
            type: Number,
            required: true,
        },
    },

    emits: ['close'],

    setup(props, { emit }) {
        const mensajes = ref([]);
        const nuevoMensaje = ref('');
        const cargando = ref(true);
        const enviando = ref(false);
        const mensajesContainer = ref(null);
        const chatInfo = ref({});
        const echo = ref(null);

        // Cargar mensajes
        const cargarMensajes = async () => {
            try {
                cargando.value = true;
                console.log('Cargando mensajes para conversación:', props.conversationId);

                const response = await axios.get(`/chat/conversations/${props.conversationId}/messages`);
                console.log('Respuesta de mensajes:', response.data);

                if (response.data && response.data.messages) {
                    mensajes.value = response.data.messages;

                    const conversation = response.data.conversation;
                    if (conversation && conversation.request) {
                        const request = conversation.request;

                        chatInfo.value = {
                            type: request.type || (request.listing && request.listing.type ? request.listing.type : 'sale'),
                            producto:
                                request.listing && request.listing.tractor
                                    ? `${request.listing.tractor.brand} ${request.listing.tractor.model}`
                                    : 'Tractor',
                        };
                    } else {
                        console.warn('No se encontraron datos completos de la conversación');
                        chatInfo.value = {
                            type: 'solicitud',
                            producto: 'Producto',
                        };
                    }

                    // Scroll hacia abajo después de cargar mensajes
                    await nextTick();
                    scrollToBottom();
                } else {
                    console.error('Formato de respuesta incorrecto al cargar mensajes');
                    mensajes.value = [];
                }
            } catch (error) {
                console.error('Error al cargar mensajes:', error);
                mensajes.value = [];
                alert('No se pudieron cargar los mensajes. Por favor, recarga la página.');
            } finally {
                cargando.value = false;
            }
        };

        // Enviar mensaje
        const enviarMensaje = async () => {
            if (!nuevoMensaje.value.trim() || enviando.value) return;

            try {
                console.log('Enviando mensaje:', nuevoMensaje.value, 'a conversación:', props.conversationId);
                enviando.value = true;

                // Asegurarse de que estamos enviando con el formato correcto
                const response = await axios.post(`/chat/conversations/${props.conversationId}/messages`, {
                    content: nuevoMensaje.value,
                });

                console.log('Respuesta al enviar mensaje:', response.data);

                // Verificar que obtuvimos una respuesta válida
                if (response.data) {
                    mensajes.value.push(response.data);
                    nuevoMensaje.value = '';

                    // Scroll hacia abajo después de enviar mensaje
                    await nextTick();
                    scrollToBottom();
                } else {
                    console.error('No se recibió respuesta del servidor al enviar mensaje');
                }
            } catch (error) {
                console.error('Error al enviar mensaje:', error);
                alert('Error al enviar el mensaje. Por favor, intenta de nuevo.');
            } finally {
                enviando.value = false;
            }
        };

        // Formatear fecha
        const formatearFecha = (fecha) => {
            const date = new Date(fecha);
            return date.toLocaleTimeString('es-ES', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false,
            });
        };

        // Scroll hacia abajo
        const scrollToBottom = () => {
            if (mensajesContainer.value) {
                mensajesContainer.value.scrollTop = mensajesContainer.value.scrollHeight;
            }
        };

        // Cerrar chat
        const cerrarChat = () => {
            emit('close');
        };

        // Configurar Echo para escuchar mensajes nuevos
        const configurarEcho = () => {
            if (window.Echo) {
                console.log('Configurando Echo para el canal: chat.' + props.conversationId);
                console.log('VITE_PUSHER_APP_KEY:', import.meta.env.VITE_PUSHER_APP_KEY);
                console.log('VITE_PUSHER_APP_CLUSTER:', import.meta.env.VITE_PUSHER_APP_CLUSTER);

                echo.value = window.Echo.private(`chat.${props.conversationId}`).listen('NewChatMessage', (e) => {
                    console.log('Evento NewChatMessage recibido:', e);
                    mensajes.value.push(e.message);
                    nextTick(() => {
                        scrollToBottom();
                    });
                });

                console.log('Echo configurado correctamente');
            } else {
                console.error('Echo no está disponible. Verifica la configuración de Laravel Echo y Pusher.');
            }
        };

        onMounted(() => {
            if (props.showChat) {
                cargarMensajes();
                configurarEcho();
            }
        });

        onUnmounted(() => {
            if (echo.value) {
                echo.value.stopListening('NewChatMessage');
            }
        });

        // Observar cambios en showChat
        watch(
            () => props.showChat,
            (newValue) => {
                if (newValue) {
                    cargarMensajes();
                    configurarEcho();
                } else if (echo.value) {
                    echo.value.stopListening('NewChatMessage');
                }
            },
        );

        return {
            mensajes,
            nuevoMensaje,
            cargando,
            enviando,
            mensajesContainer,
            chatInfo,
            cargarMensajes,
            enviarMensaje,
            formatearFecha,
            scrollToBottom,
            cerrarChat,
        };
    },
};
</script>
