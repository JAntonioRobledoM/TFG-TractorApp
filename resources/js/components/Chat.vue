<!-- resources/js/Components/Chat.vue -->
<template>
  <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" v-if="showChat">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-auto flex flex-col h-3/4">
      <!-- Cabecera del chat -->
      <div class="p-4 border-b flex justify-between items-center bg-green-600 text-white rounded-t-lg">
        <div>
          <h3 class="text-xl font-bold">Chat de {{ chatInfo.type === 'sale' ? 'venta' : 'alquiler' }}</h3>
          <p class="text-sm">{{ chatInfo.producto ? chatInfo.producto : 'Cargando...' }}</p>
        </div>
        <button @click="cerrarChat" class="text-white text-2xl hover:text-red-300">&times;</button>
      </div>
      
      <!-- Mensajes -->
      <div class="flex-1 p-4 overflow-y-auto" ref="mensajesContainer">
        <div v-if="cargando" class="flex justify-center items-center h-full">
          <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-green-500"></div>
        </div>
        
        <template v-else>
          <div v-for="mensaje in mensajes" :key="mensaje.id" 
              :class="[
                'mb-3 max-w-3/4 rounded-lg p-3', 
                mensaje.sender_id === userId ? 
                  'ml-auto bg-green-100 text-right' : 
                  'mr-auto bg-gray-100'
              ]">
            <p class="text-xs text-gray-500">{{ mensaje.sender ? `${mensaje.sender.first_name} ${mensaje.sender.last_name}` : 'Usuario' }}</p>
            <p>{{ mensaje.content }}</p>
            <p class="text-xs text-gray-400">
              {{ formatearFecha(mensaje.created_at) }}
              <span v-if="mensaje.is_read && mensaje.sender_id === userId" 
                class="ml-1 text-green-600">✓</span>
            </p>
          </div>
          
          <div v-if="mensajes.length === 0" class="flex justify-center items-center h-full text-gray-400">
            No hay mensajes. ¡Sé el primero en escribir!
          </div>
        </template>
      </div>
      
      <!-- Formulario para enviar mensaje -->
      <div class="p-4 border-t">
        <form @submit.prevent="enviarMensaje" class="flex">
          <input 
            type="text" 
            v-model="nuevoMensaje" 
            class="flex-1 border rounded-l-lg p-2 text-lg focus:outline-none focus:ring-2 focus:ring-green-500" 
            placeholder="Escribe tu mensaje..."
            :disabled="enviando"
          />
          <button 
            type="submit" 
            class="bg-green-600 text-white px-4 py-2 rounded-r-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
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
import { ref, onMounted, onUnmounted, nextTick, watch } from 'vue';
import axios from 'axios';

export default {
  props: {
    conversationId: {
      type: Number,
      required: true
    },
    showChat: {
      type: Boolean,
      default: false
    },
    userId: {
      type: Number,
      required: true
    }
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
        const response = await axios.get(`/chat/conversations/${props.conversationId}/messages`);
        mensajes.value = response.data.messages;
        
        const conversation = response.data.conversation;
        const request = conversation.request;
        
        chatInfo.value = {
          type: request.type,
          producto: request.listing.tractor 
            ? `${request.listing.tractor.brand} ${request.listing.tractor.model}` 
            : 'Tractor'
        };
        
        cargando.value = false;
        
        // Scroll hacia abajo después de cargar mensajes
        await nextTick();
        scrollToBottom();
      } catch (error) {
        console.error('Error al cargar mensajes:', error);
        cargando.value = false;
      }
    };
    
    // Enviar mensaje
    const enviarMensaje = async () => {
      if (!nuevoMensaje.value.trim() || enviando.value) return;
      
      try {
        enviando.value = true;
        const response = await axios.post(`/chat/conversations/${props.conversationId}/messages`, {
          content: nuevoMensaje.value
        });
        
        mensajes.value.push(response.data);
        nuevoMensaje.value = '';
        enviando.value = false;
        
        // Scroll hacia abajo después de enviar mensaje
        await nextTick();
        scrollToBottom();
      } catch (error) {
        console.error('Error al enviar mensaje:', error);
        enviando.value = false;
      }
    };
    
    // Formatear fecha
    const formatearFecha = (fecha) => {
      const date = new Date(fecha);
      return date.toLocaleTimeString('es-ES', { 
        hour: '2-digit', 
        minute: '2-digit',
        hour12: false 
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
        echo.value = window.Echo.private(`chat.${props.conversationId}`)
          .listen('NewChatMessage', (e) => {
            mensajes.value.push(e.message);
            nextTick(() => {
              scrollToBottom();
            });
          });
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
    watch(() => props.showChat, (newValue) => {
      if (newValue) {
        cargarMensajes();
        configurarEcho();
      } else if (echo.value) {
        echo.value.stopListening('NewChatMessage');
      }
    });
    
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
      cerrarChat
    };
  }
}
</script>